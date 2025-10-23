  
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


    async function bio_transaction(proceed = (pin) => {}) {
      const saved = JSON.parse(localStorage.getItem(sitename+"-credential") || "{}");
      if (!saved.code) {
        $("#status").text("⚠️ Please register for biometric first.");
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
          $("#status").html(`✅ Authenticated <br>`);
          proceed(saved.code);
        } else {
          console.log(res.message);
          if(res.http_status == "401"){
              $("#status").html(`⚠️ biometric currently disabled<br>`);
              return;
          }
          //${res.message}
          $("#status").html(`❌failed (HTTP ${res.http_status})<br> `);
        }
      } catch (err) {
        $("#status").text("❌ Auth failed");
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

    $("#d_bio_transaction").on("click", function(e){
      e.preventDefault();
      bio_transaction(() => {
        $("#status").text("✅ Transaction proceeded.");
      });
    });
  </script>