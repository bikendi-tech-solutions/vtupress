<script>
  $.ajaxSetup({ cache: false });

  //url to wp-content/plugins/vtupress/fingerprint/auth.php
  var url = "<?php echo esc_url(plugins_url('auth.php', __FILE__)); ?>";
  var sitename = "<?php echo esc_js(get_option('blogname')); ?>";
  // alert(url);

  // Helper for base64 encoding (CRITICAL for sending binary data safely)

  // ✅ Send via normal POST
  // ✅ Uses your original version exactly
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
  if ($('#liveness-style').length === 0)
    $('head').append(`<style id="liveness-style">${CAMERA_CSS}</style>`);
  $('body').append(CAMERA_HTML);

  // Load face-api.js
  if (typeof faceapi === 'undefined') {
    await new Promise((resolve, reject) => {
      const s = document.createElement("script");
      s.src = "https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js";
      s.onload = resolve;
      s.onerror = () => reject("Failed to load face-api.js");
      document.head.appendChild(s);
    });
  }

  const $video = $("#video-cam"), $canvas = $("#overlay-cam"), ctx = $canvas[0].getContext("2d");
  const $msg = $("#message-cam"), $b64 = $("#b64-cam");
  const $restart = $("#restartBtn-cam"), $stop = $("#stopBtn-cam"), $close = $("#closeBtn-cam");
  const $controls = $(".controls");

  const W = 420, H = 420;
  $canvas[0].width = W;
  $canvas[0].height = H;

  let stream = null, baselineYaw = null, baselinePitch = null, lastDetection = Date.now();
  let progress = 0, startDelayPassed = false, isVerified = false;
  let currentChallengeIndex = 0, currentChallengeStart = Date.now();
  let usedChallenges = new Set();

  // Challenge definitions
  const allChallenges = [
    { name: "turn LEFT 👈", test: (yaw, pitch, by, bp, e) => yaw - by > 0.12 },
    { name: "turn RIGHT 👉", test: (yaw, pitch, by, bp, e) => yaw - by < -0.12 },
    { name: "tilt DOWN 👇", test: (yaw, pitch, by, bp, e) => pitch - bp < -0.08 },
    { name: "tilt UP ☝️", test: (yaw, pitch, by, bp, e) => pitch - bp > 0.08 },
    { name: "smile 😊", test: (yaw, pitch, by, bp, e) => e.happy > 0.7 }
  ];

  // Pick 4 random challenges initially
  let challenges = shuffle(allChallenges).slice(0, 4);

  const MODEL_URL = "https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js@master/weights";
  $msg.text("Loading AI models…");

  await Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
    faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
    faceapi.nets.faceExpressionNet.loadFromUri(MODEL_URL)
  ]);

  $msg.text("Starting camera…");

  async function startCamera() {
    $controls.show();
    $video.removeClass('captured');
    if (stream) stream.getTracks().forEach(t => t.stop());

    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      $video[0].srcObject = stream;
      await $video[0].play();

      baselineYaw = null;
      baselinePitch = null;
      progress = 0;
      startDelayPassed = false;
      isVerified = false;
      currentChallengeIndex = 0;
      usedChallenges.clear();
      setTimeout(() => { startDelayPassed = true; }, 1000);

      $msg.text("Camera ready — look straight ahead");
      requestAnimationFrame(loop);
    } catch (e) {
      $msg.text("❌ Camera error: " + e.message);
      console.error(e);
    }
  }

  function computePitch(landmarks) {
    const nose = landmarks.getNose();
    const jaw = landmarks.getJawOutline();
    const dy = (nose[6]._y - jaw[8]._y) / (jaw[0]._y - jaw[8]._y);
    return dy;
  }

  async function loop() {
    if (isVerified || $video[0].paused || $video[0].ended) return;

    const det = await faceapi
      .detectSingleFace($video[0], new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.35 }))
      .withFaceLandmarks()
      .withFaceExpressions();

    ctx.clearRect(0, 0, $canvas[0].width, $canvas[0].height);

    // Draw circular progress border
    drawProgress(ctx, W, H, progress, challenges.length);

    if (det && startDelayPassed) {
      lastDetection = Date.now();
      const landmarks = det.landmarks;
      const exp = det.expressions;

      const nose = landmarks.getNose();
      const leftEye = landmarks.getLeftEye();
      const rightEye = landmarks.getRightEye();
      const eyeMidX = (leftEye[0]._x + rightEye[3]._x) / 2;
      const yaw = (nose[3]._x - eyeMidX) / (rightEye[3]._x - leftEye[0]._x);
      const pitch = computePitch(landmarks);

      if (baselineYaw === null || baselinePitch === null) {
        baselineYaw = yaw;
        baselinePitch = pitch;
        $msg.text("Hold still to calibrate…");
        return requestAnimationFrame(loop);
      }

      const current = challenges[currentChallengeIndex];
      if (!current) return;

      $msg.text(`Step ${currentChallengeIndex + 1}/${challenges.length} — ${current.name}`);

      // If user completes current challenge
      if (current.test(yaw, pitch, baselineYaw, baselinePitch, exp)) {
        usedChallenges.add(current.name);
        progress++;
        currentChallengeIndex++;
        currentChallengeStart = Date.now();

        if (progress >= challenges.length) {
          progress = challenges.length;
          $msg.text("✅ Verified! Capturing...");
          captureBase64();
          finalizeCaptureAndSendToPHP();
          return;
        }

        setTimeout(() => {
          $msg.text(`Next: ${challenges[currentChallengeIndex].name}`);
        }, 800);
      }

      // Timeout if 8 seconds passed without success
      if (Date.now() - currentChallengeStart > 8000) {
        $msg.text("⏱ Timeout! Choosing another challenge...");
        challenges = replaceChallenge(challenges, currentChallengeIndex, usedChallenges, allChallenges);
        currentChallengeStart = Date.now();
      }
    } else if (Date.now() - lastDetection > 800 && startDelayPassed) {
      $msg.text("Face not detected");
    }

    requestAnimationFrame(loop);
  }

  async function finalizeCaptureAndSendToPHP() {
    isVerified = true;
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    $video.addClass('captured');
    $controls.hide();
    ctx.clearRect(0, 0, $canvas[0].width, $canvas[0].height);
    $msg.text("Verification Complete. Sending data...");

    const base64Data = $b64.val();
    try {
      const res = await sendToServer('cam', { image_data: base64Data });
      if (res.success) {
        $msg.text(`✅ Success!`);
        setTimeout(() => location.reload(), 2000);
      } else {
        $msg.text(`❌ Upload Failed: ${res.message || 'Unknown Server Error'}`);
        console.error(res);
      }
    } catch (error) {
      $msg.text("❌ Communication error");
      console.error(error);
    }
  }

  function captureBase64() {
    const c = document.createElement("canvas");
    c.width = $video[0].videoWidth;
    c.height = $video[0].videoHeight;
    const cx = c.getContext("2d");
    cx.scale(-1, 1);
    cx.drawImage($video[0], -c.width, 0, c.width, c.height);
    const data = c.toDataURL("image/png");
    $b64.val(data);
  }

  $restart.on("click", () => { baselineYaw = null; baselinePitch = null; progress = 0; startCamera(); });
  $stop.on("click", () => { if (stream) stream.getTracks().forEach(t => t.stop()); $controls.hide(); });
  $close.on("click", () => closeLivenessCamera(stream));

  await startCamera();
}

// Utility: shuffle array
function shuffle(arr) {
  return arr.map(a => ({ sort: Math.random(), value: a }))
            .sort((a, b) => a.sort - b.sort)
            .map(a => a.value);
}

// Utility: replace current challenge with a new unused one
function replaceChallenge(challenges, index, usedSet, allChallenges) {
  const available = allChallenges.filter(ch => !usedSet.has(ch.name) && !challenges.includes(ch));
  if (available.length === 0) return challenges;
  const replacement = available[Math.floor(Math.random() * available.length)];
  challenges[index] = replacement;
  return challenges;
}

// Utility: draw circular progress
function drawProgress(ctx, W, H, progress, total) {
  const cx = W / 2, cy = H / 2, radius = W / 2, borderWidth = 5;
  const innerRadius = radius - borderWidth / 2;
  ctx.beginPath();
  ctx.arc(cx, cy, innerRadius, 0, 2 * Math.PI);
  ctx.strokeStyle = "rgba(255,255,255,0.15)";
  ctx.lineWidth = borderWidth;
  ctx.stroke();
  ctx.beginPath();
  ctx.arc(cx, cy, innerRadius, -Math.PI / 2, (-Math.PI / 2) + (progress / total) * 2 * Math.PI);
  ctx.strokeStyle = "#22c55e";
  ctx.lineWidth = borderWidth;
  ctx.shadowColor = "#22c55e";
  ctx.shadowBlur = 10;
  ctx.stroke();
  ctx.shadowBlur = 0;
}

async function bio_cam() { await startLivenessCamera(); }


  // --- EXISTING FINGERPRINT FUNCTIONS (UPDATED FOR SECURITY) ---
  // ✅ Helper: Base64URL encode/decode for WebAuthn data
  // ✅ Helper: Base64URL encode/decode for WebAuthn
function base64urlEncode(buffer) {
  return btoa(String.fromCharCode(...new Uint8Array(buffer)))
    .replace(/\+/g, "-")
    .replace(/\//g, "_")
    .replace(/=+$/, "");
}

function base64urlDecode(base64url) {
  const base64 = base64url.replace(/-/g, "+").replace(/_/g, "/");
  const pad = base64.length % 4 === 0 ? "" : "=".repeat(4 - (base64.length % 4));
  const str = atob(base64 + pad);
  const buffer = new ArrayBuffer(str.length);
  const bytes = new Uint8Array(buffer);
  for (let i = 0; i < str.length; i++) bytes[i] = str.charCodeAt(i);
  return buffer;
}



// ✅ Biometric Transaction Flow
async function bio_transaction(proceed = (pin) => {}) {
  const saved = JSON.parse(localStorage.getItem(sitename + "-credential") || "{}");
  if (!saved.code) {
    $("#status").text("⚠️ Please register for biometric first.");
    return;
  }

  $("#status").text("🔄 Waiting for challenge...");
  const challenge = new Uint8Array(32);
  window.crypto.getRandomValues(challenge);

  const options = {
    publicKey: {
      challenge,
      allowCredentials: [{ type: "public-key", id: base64urlDecode(saved.code) }],
      timeout: 60000,
      userVerification: "preferred",
    },
  };

  try {
    const assertion = await navigator.credentials.get(options);
    $("#status").text("✅ Fingerprint verified locally. Sending proof...");

    const dataToSend = {
      code: saved.code,
      authenticatorData: base64urlEncode(assertion.response.authenticatorData),
      clientDataJSON: base64urlEncode(assertion.response.clientDataJSON),
      signature: base64urlEncode(assertion.response.signature),
    };

    const res = await sendToServer("verify", dataToSend);
    if (res.success) {
      proceed(saved.code);
    } else {
      console.warn(res.message);
      if (res.http_status === 401) {
        $("#status").html("⚠️ Feature disabled<br>");
        return;
      }
      $("#status").html(`❌ Failed (HTTP ${res.http_status})<br>`);
    }
  } catch (err) {
    console.error(err);
    $("#status").text("❌ Authentication failed: " + err.message);
  }
}

// ✅ Biometric Registration Flow
async function bio_register() {
  <?php if (!is_user_logged_in()) { ?>
    $("#status").text("❌ Please log in to register.");
    return;
  <?php } ?>

  const email = "<?php echo esc_js(wp_get_current_user()->user_email); ?>";
  const displayName = "<?php echo esc_js(vp_getoption('first_name') . ' ' . vp_getoption('last_name')); ?>";
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
        displayName: displayName,
      },
      pubKeyCredParams: [{ alg: -7, type: "public-key" }],
      authenticatorSelection: { userVerification: "preferred" },
      timeout: 60000,
      attestation: "none",
    },
  };

  try {
    const credential = await navigator.credentials.create(options);
    $("#status").text("✅ Fingerprint captured. Saving...");

    const toSave = {
      user_id: email,
      code: base64urlEncode(credential.rawId),
      attestationObject: base64urlEncode(credential.response.attestationObject),
      clientDataJSON: base64urlEncode(credential.response.clientDataJSON),
    };

    const res = await sendToServer("register", toSave);
    if (!res.success) {
      console.warn(res.message);
      if (res.http_status === 401) {
        $("#status").html("⚠️ Biometric feature disabled<br>");
        return;
      }
      $("#status").html(`⚠️ Registration failed (HTTP ${res.http_status})<br>`);
      return;
    }

    localStorage.setItem(sitename + "-credential", JSON.stringify(toSave));
    $("#status").text("✅ Registration complete. Reloading...");
    setTimeout(() => location.reload(), 800);
  } catch (err) {
    console.error(err);
    $("#status").text("❌ Registration failed: " + err.message);
  }
}

// ✅ Biometric Login Flow
async function bio_login() {
  const saved = JSON.parse(localStorage.getItem(sitename + "-credential") || "{}");
  if (!saved.code) {
    $("#status").text("⚠️ Please register for biometric first.");
    return;
  }

  $("#status").text("🔄 Waiting for fingerprint verification...");
  const challenge = new Uint8Array(32);
  window.crypto.getRandomValues(challenge);

  const options = {
    publicKey: {
      challenge,
      allowCredentials: [{ type: "public-key", id: base64urlDecode(saved.code) }],
      timeout: 60000,
      userVerification: "preferred",
    },
  };

  try {
    const assertion = await navigator.credentials.get(options);
    $("#status").text("✅ Fingerprint verified locally. Sending proof...");

    const dataToSend = {
      code: saved.code,
      authenticatorData: base64urlEncode(assertion.response.authenticatorData),
      clientDataJSON: base64urlEncode(assertion.response.clientDataJSON),
      signature: base64urlEncode(assertion.response.signature),
    };

    const res = await sendToServer("login", dataToSend);
    if (res.success && res.user_name) {
      $("#status").html(`✅ Welcome <b>${res.user_name}</b>`);
      setTimeout(() => location.reload(), 800);
    } else {
      console.warn(res.message);
      if (res.http_status === 401) {
        $("#status").html("⚠️ Feature disabled<br>");
        return;
      }
      $("#status").html(`❌ Login failed (HTTP ${res.http_status})<br>`);
    }
  } catch (err) {
    console.error(err);
    $("#status").text("❌ Biometric authentication failed: " + err.message);
  }
}



  async function bio_remove() {
 <?php if (!is_user_logged_in()) { ?>
        $("#status").text("❌ Please log in to remove biometric data.");
        return;
 <?php } ?>
      $("#status").text("🔄 Removing biometric data...");
    const res = await sendToServer("remove", { user_id: "<?php echo esc_js(wp_get_current_user()->user_email); ?>" });
    if (res.success) {
      localStorage.removeItem(sitename + "-credential");
      $("#status").text("✅ Biometric data removed successfully");
      location.reload();
    } else {
      console.log(res.message);
      $("#status").html(`❌ Removal failed (HTTP ${res.http_status})<br>`);
    }
  }


  $("#d_bio_register").on("click", function (e) {
    e.preventDefault();
    bio_register();
  });
  $("#d_bio_login").on("click", function (e) {
    e.preventDefault();
    bio_login();
  });
  $("#d_bio_register_not").on("click", function (e) {
    e.preventDefault();
    bio_remove();
  });
  $("#d_bio_cam").on("click", function (e) {
    e.preventDefault();
    bio_cam();
  });

  $("#d_bio_transaction").on("click", function (e) {
    e.preventDefault();
    bio_transaction(() => {
      $("#status").text("✅ Transaction proceeded.");
    });
  });
</script>