 
 <script>
  $.ajaxSetup({ cache: false });

  //url to wp-content/plugins/vtupress/fingerprint/auth.php
  var url = "<?php echo esc_url( plugins_url( 'auth.php', __FILE__ ) ); ?>";
  var sitename = "<?php echo esc_js( get_option('blogname') ); ?>";
  // alert(url);

  // Helper for base64 encoding
  function base64urlEncode(buffer) {
   return btoa(String.fromCharCode(...new Uint8Array(buffer)))
    .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  }

  function base64urlDecode(base64url) {
   const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
   const pad = base64.length % 4 ? 4 - (base64.length % 4) : 0;
   const binary = atob(base64 + '='.repeat(pad));
   return Uint8Array.from(binary, c => c.charCodeAt(0));
  }

  // ✅ Send via normal POST
  function sendToServer(name, data) {
   return new Promise((resolve) => {
    $.ajax({
     url: url,
     method: "POST",
     data: { name, ...data },
     dataType: "json",
     success: function (res, textStatus, xhr) {
      resolve({ success: true, ...res, http_status: xhr.status });
     },
     error: function (xhr, textStatus, errorThrown) {
      let parsed;
      try { parsed = JSON.parse(xhr.responseText); }
      catch { parsed = { message: xhr.responseText || "No readable response" }; }
      resolve({
       success: false,
       http_status: xhr.status,
       message: parsed.message || "Server error",
       server_response: parsed,
      });
     },
    });
   });
  }


  // --- NEW CAMERA AND LIVENESS LOGIC ---

  const LIVENESS_ID = 'smart-liveness-camera-module';

  // Full HTML structure for the camera, now including a close button
  const CAMERA_HTML = `
   <div class="overlay" id="${LIVENESS_ID}">
    <div class="face-wrap">
     <video id="video-cam" autoplay muted playsinline></video>
     <canvas id="overlay-cam"></canvas>
     <div class="status" id="message-cam">Loading models…</div>
     <div class="controls">
      <button id="restartBtn-cam">Restart</button>
      <button id="stopBtn-cam">Stop</button>
      <button id="closeBtn-cam">Close</button>
     </div>
    </div>
   </div>
   <textarea id="b64-cam" readonly style="display:none;"></textarea>
  `;

  // Full CSS styles for the camera overlay and components
  const CAMERA_CSS = `
   :root { --accent:#22c55e; --muted:#9aa0a6; }
   /* CRITICAL: High z-index to bring it to the front */
   #${LIVENESS_ID} {
    position:fixed !important; 
    inset:0;
    z-index: 10000; /* Z-index to the very front */
    display:flex; 
    align-items:center; 
    justify-content:center;
    background:rgba(0,0,0,0.85);
   }
   #${LIVENESS_ID} * { font-family:Inter,system-ui,Arial; }

   .face-wrap {
    position:relative;
    width:420px; 
    height:420px;
   }
   #video-cam {
    width:420px; 
    height:420px;
    object-fit:cover;
    border-radius:50%; 
    transform:scaleX(-1);
    box-shadow:0 0 0 9999px rgba(0,0,0,0.65);
   }
   #video-cam.captured {
    background: black;
   }
   #overlay-cam {
    position:absolute; left:0; top:0;
    width:420px; 
    height:420px;
    pointer-events:none;
   }
   #message-cam {
    position:absolute; bottom:-70px; width:100%;
    text-align:center; color:var(--muted);
    font-size:16px; font-weight:500;
   }
   .controls {
    position:absolute; bottom:-120px; width:100%;
    text-align:center;
   }
   .controls button {
    background:transparent; color:#ddd;
    border:1px solid rgba(255,255,255,0.2);
    border-radius:8px; padding:8px 14px; margin:0 6px;
    cursor: pointer;
    transition: background 0.15s;
   }
   .controls button:hover {
    background: rgba(255,255,255,0.05);
   }
  `;

  /**
   * Stops the camera stream and removes the entire liveness overlay from the DOM.
   */
  function closeLivenessCamera(stream) {
   if (stream) {
    stream.getTracks().forEach(t => t.stop());
   }
   $(`#${LIVENESS_ID}`).remove();
   $('#b64-cam').remove();
   $('#liveness-style').remove();
   console.log("Liveness camera closed and removed from DOM.");
  }

  /**
   * Main function to start and run the Liveness Camera.
   */
  async function startLivenessCamera() {
   // Inject styles and HTML first, ensuring z-index is applied
   if ($('#liveness-style').length === 0) {
    $('head').append(`<style id="liveness-style">${CAMERA_CSS}</style>`);
   }
   $('body').append(CAMERA_HTML);

   // Load face-api.js dynamically
   if (typeof faceapi === 'undefined') {
    await new Promise((resolve, reject) => {
     const s = document.createElement("script");
     s.src = "https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js";
     s.onload = resolve; s.onerror = () => reject("Failed to load face-api.js");
     document.head.appendChild(s);
    });
   }

   const $video = $("#video-cam"), $canvas = $("#overlay-cam"), ctx = $canvas[0].getContext("2d");
   const $msg = $("#message-cam"), $b64 = $("#b64-cam");
   const $restart = $("#restartBtn-cam"), $stop = $("#stopBtn-cam"), $close = $("#closeBtn-cam");
   const $controls = $(".controls");

   const W = 420;
   const H = 420;
   $canvas[0].width = W;
   $canvas[0].height = H;

   let stream = null, state = 0, baselineYaw = null, lastDetection = Date.now();
   let progress = 0, startDelayPassed = false;
   let isVerified = false;

   const MODEL_URL = "https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights";
   $msg.text("Loading AI models...");

   await Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
    faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
   ]);

   $msg.text("Starting camera…");

   async function startCamera() {
    $controls.show();
    $video.removeClass('captured');

    if (stream && stream.active) return;
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }

    try {
     stream = await navigator.mediaDevices.getUserMedia({ video: true });
     $video[0].srcObject = stream;
     await $video[0].play();

     state = 0; baselineYaw = null; progress = 0;
     startDelayPassed = false;
     isVerified = false;
     setTimeout(() => { startDelayPassed = true; }, 1000);

     $msg.text("Camera ready — look straight ahead");
     requestAnimationFrame(loop);
    } catch (e) {
     $msg.text("❌ Camera error: " + e.message);
     console.error("Camera error:", e);
    }
   }

   async function loop() {
    if (isVerified || $video[0].paused || $video[0].ended) return;

    const det = await faceapi
     .detectSingleFace($video[0], new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.35 }))
     .withFaceLandmarks()
     .withFaceExpressions();

    ctx.clearRect(0, 0, $canvas[0].width, $canvas[0].height);

    // Drawing logic for the border progress
    const cx = W / 2;
    const cy = H / 2;
    const radius = W / 2;
    const borderWidth = 5;
    const innerRadius = radius - borderWidth / 2;

    ctx.beginPath();
    ctx.arc(cx, cy, innerRadius, 0, 2 * Math.PI);
    ctx.strokeStyle = "rgba(255,255,255,0.15)";
    ctx.lineWidth = borderWidth;
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(cx, cy, innerRadius, -Math.PI / 2, (-Math.PI / 2) + (progress / 3) * 2 * Math.PI);
    ctx.strokeStyle = "#22c55e";
    ctx.lineWidth = borderWidth;
    ctx.shadowColor = "#22c55e";
    ctx.shadowBlur = 10;
    ctx.stroke();
    ctx.shadowBlur = 0;


    if (det && startDelayPassed) {
     lastDetection = Date.now();
     const landmarks = det.landmarks;
     const nose = landmarks.getNose();
     const leftEye = landmarks.getLeftEye();
     const rightEye = landmarks.getRightEye();
     const eyeMidX = (leftEye[0]._x + rightEye[3]._x) / 2;
     const yaw = (nose[3]._x - eyeMidX) / (rightEye[3]._x - leftEye[0]._x);

     if (baselineYaw === null) {
      baselineYaw = yaw;
      $msg.text("Hold still to calibrate…");
      return requestAnimationFrame(loop);
     }

     // step flow (Right turn -> Left turn -> Smile)
     if (state === 0) {
      $msg.text("Step 1 — turn your head LEFT 👈");
      if (yaw - baselineYaw > 0.12) { state = 1; progress = 1; $msg.text("Good! Now turn RIGHT 👉"); }
     } else if (state === 1) {
      if (yaw - baselineYaw < -0.12) { state = 2; progress = 2; $msg.text("Great! Now smile 😊"); }
     } else if (state === 2) {
      const exp = det.expressions;
      if (exp.happy > 0.7) {
       progress = 3;
       $msg.text("✅ Verified! Capturing and sending photo...");
       captureBase64();
       finalizeCaptureAndSendToPHP();
      } else {
       $msg.text("Step 3 — please smile 😊");
      }
     }
    } else {
     if (Date.now() - lastDetection > 800 && startDelayPassed) {
      $msg.text("Face not detected");
     }
    }

    requestAnimationFrame(loop);
   }

   async function finalizeCaptureAndSendToPHP() {
    isVerified = true;
    
    // 1. Stop the camera stream
    if (stream) {
     stream.getTracks().forEach(t => t.stop());
     $video.addClass('captured');
     stream = null;
    }

    // Hide controls and clear canvas
    $controls.hide();
    ctx.clearRect(0, 0, $canvas[0].width, $canvas[0].height);
    $msg.text("Verification Complete. Sending data to server...");

    const base64Data = $b64.val();

    // --- Use the existing sendToServer function ---
    try {
     // Assuming the PHP endpoint handles the 'cam' action and image data
     const res = await sendToServer('cam', { image_data: base64Data });

     if (res.success) {
      $msg.text(`✅ Success! Photo saved/verified.`);
      setTimeout(() => {
             location.reload();
      }, 2000);
     } else {
      $msg.text(`❌ Upload Failed: ${res.message || 'Unknown Server Error'}`);
      console.error("PHP Error:", res.message, "HTTP Status:", res.http_status);
            setTimeout(() => {
             location.reload();
      }, 2000);
     }

    } catch (error) {
     $msg.text("❌ Critical Error during communication. Check console.");
     console.error("AJAX/SendToServer Error:", error);
           setTimeout(() => {
             location.reload();
      }, 2000);
    }
   }

   function captureBase64() {
    const c = document.createElement("canvas");
    c.width = $video[0].videoWidth; c.height = $video[0].videoHeight;
    const cx = c.getContext("2d");
    cx.scale(-1, 1);
    cx.drawImage($video[0], -c.width, 0, c.width, c.height);
    // Store the full data URI
    const data = c.toDataURL("image/png"); 
    $b64.val(data);
   }

   // Attach listeners
   $restart.on("click", () => {
    state = 0; baselineYaw = null; progress = 0;
    $msg.text("Restarted — hold still for calibration");
    startCamera();
   });
   $stop.on("click", () => {
    if (stream) {
     stream.getTracks().forEach(t => t.stop());
     stream = null;
     $video.addClass('captured');
     $msg.text("Camera stopped manually. Click Close to exit.");
     $controls.hide();
     $close.show(); 
     ctx.clearRect(0, 0, $canvas[0].width, $canvas[0].height);
    }
   });
   $close.on("click", () => {
    closeLivenessCamera(stream);
   });

   await startCamera();
  }
  
  // The new function to fire the camera
  async function bio_cam() {
   await startLivenessCamera();
  }

  // --- EXISTING FINGERPRINT FUNCTIONS (UNCHANGED) ---

  async function bio_transaction(proceed = (pin) => {}) {
   const saved = JSON.parse(localStorage.getItem(sitename+"-credential") || "{}");
   if (!saved.code) {
    $("#status").text("⚠️ Please register first.");
    return;
   }

   $("#status").text("🔄 Waiting...");
   const challenge = new Uint8Array(32);
   window.crypto.getRandomValues(challenge);

   const options = {
    publicKey: {
     challenge,
     allowCredentials: [{
      type: "public-key",
      id: base64urlDecode(saved.code)
     }],
     timeout: 60000,
     userVerification: "preferred" // ✅ Not required, just preferred
    }
   };

   try {
    // Step 1: Ask for fingerprint verification
    await navigator.credentials.get(options);
    $("#status").text("✅ Checking...");

    // Step 2: Verify code with backend
    const res = await sendToServer("verify", { code: saved.code });
    if (res.success) {
     //<br>HTTP ${res.http_status}
     proceed(saved.code);
    } else {
     console.log(res.message);
     if(res.http_status == "401"){
       $("#status").html(`⚠️disabled<br>`);
       return;
     }
     $("#status").html(`❌failed (HTTP ${res.http_status})<br>`);
    }
   } catch (err) {
      alert(err.message);
    $("#status").text("❌ auth failed");
   }
  }

  // ✅ Registration flow
  async function bio_register() {
   <?php if(!is_user_logged_in()) { ?>
    $("#status").text("❌ Please log in to register.");
    return;
   <?php } ?>
   var email = "<?php echo esc_js( wp_get_current_user()->user_email ); ?>";
   var displayName = "<?php echo vp_getoption("first_name")." ".vp_getoption("last_name"); ?>";
   $("#status").text("🔄 Starting registration...");
   const challenge = new Uint8Array(32);
   window.crypto.getRandomValues(challenge);

   const options = {
    publicKey: {
     challenge,
     rp: { name: "Biometric Auth" },
     user: {
      id: new Uint8Array(16),
      name: email,
      displayName: displayName
     },
     pubKeyCredParams: [{ alg: -7, type: "public-key" }],
     authenticatorSelection: { userVerification: "preferred" },
     timeout: 60000,
     attestation: "none"
    }
   };

   try {
    const credential = await navigator.credentials.create(options);
    $("#status").text("✅ Fingerprint captured. Saving...");

    const toSave = {
     user_id: email,
     code: base64urlEncode(credential.rawId)
    };

    const res = await sendToServer("register", toSave);
    if (!res.success) {
     console.log(res.message);
     if(res.http_status == "401"){
       $("#status").html(`⚠️ Biometric feature is currently disabled<br>`);
       return;
     }
     $("#status").html(`⚠️ Registration failed (HTTP ${res.http_status})<br>`);
     return;
    }

    localStorage.setItem(sitename+"-credential", JSON.stringify(toSave));
     location.reload();
    $("#status").text("✅ Registration complete. You can now log in with biometric.");
   } catch (err) {
     console.log(err.message);
    $("#status").text("❌ Registration failed");
   }
  }

  // ✅ Login flow (with biometric + server validation)
  async function bio_login() {
   const saved = JSON.parse(localStorage.getItem(sitename+"-credential") || "{}");
   if (!saved.code) {
    $("#status").text("⚠️ Please register first.");
    return;
   }

   $("#status").text("🔄 Waiting for fingerprint verification...");
   const challenge = new Uint8Array(32);
   window.crypto.getRandomValues(challenge);

   const options = {
    publicKey: {
     challenge,
     allowCredentials: [{
      type: "public-key",
      id: base64urlDecode(saved.code)
     }],
     timeout: 60000,
     userVerification: "preferred" // ✅ Not required, just preferred
    }
   };

   try {
    // Step 1: Ask for fingerprint verification
    await navigator.credentials.get(options);
    $("#status").text("✅ Fingerprint verified locally. Checking with server...");

    // Step 2: Verify code with backend
    const res = await sendToServer("login", { code: saved.code });
    if (res.success && res.user_name) {
     //<br>HTTP ${res.http_status}
     location.reload();
     $("#status").html(`✅ Welcome <b>${res.user_name}</b>`);
    } else {
     console.log(res.message);
     if(res.http_status == "401"){
       $("#status").html(`⚠️ Biometric feature is currently disabled<br>`);
       return;
     }
     $("#status").html(`❌ Login failed (HTTP ${res.http_status})<br>`);
    }
   } catch (err) {
    $("#status").text("❌ Biometric authentication failed");
   }
  }

  async function bio_remove() {
   <?php if(!is_user_logged_in()) { ?>
    $("#status").text("❌ Please log in to remove biometric data.");
    return;
   <?php } ?>
   $("#status").text("🔄 Removing biometric data...");
   const res = await sendToServer("remove", { user_id: "<?php echo esc_js( wp_get_current_user()->user_email ); ?>" });
   if (res.success) {
    localStorage.removeItem(sitename+"-credential");
    $("#status").text("✅ Biometric data removed successfully");
    location.reload();
   } else {
    console.log(res.message);
    $("#status").html(`❌ Removal failed (HTTP ${res.http_status})<br>`);
   }
  }


  $("#d_bio_register").on("click", function(e){
   e.preventDefault();
   bio_register();
  });
  $("#d_bio_login").on("click", function(e){
   e.preventDefault();
   bio_login();
  });
  $("#d_bio_register_not").on("click", function(e){
   e.preventDefault();
   bio_remove();
  });
$("#d_bio_cam").on("click", function(e){
   e.preventDefault();
   bio_cam();
  });

  $("#d_bio_transaction").on("click", function(e){
   e.preventDefault();
   bio_transaction(() => {
    $("#status").text("✅ Transaction proceeded.");
   });
  });
 </script>
