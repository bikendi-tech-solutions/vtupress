<?php
header("Access-Control-Allow-Origin: 'self'");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if (!defined('ABSPATH')) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
}

if (WP_DEBUG == false) {
    error_reporting(0);
}

require_once(ABSPATH . 'wp-load.php');
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
include_once(ABSPATH . 'wp-content/plugins/vtupress/functions.php');

// Referrer protection (as in original)
$allowed_referrers = [
    $_SERVER['SERVER_NAME']
];

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    if (!in_array($referer, $allowed_referrers)) {
        die("REF ENT PERM");
    }
} else {
    die("BAD");
}

if (!isset($_POST['pay'])) {
    echo "Error";
    exit;
}

do_action("vppay");

$paychoice = $_POST["paymentchoice"] ?? null;

// handle amounte cookie (original behavior)
if (isset($_POST["amounte"])) {
    $userid = $_POST["userid"] ?? '';
    setcookie('userid', $userid, time() + (30 * 24 * 60 * 60), "/");
    $amounte = $_POST['amounte'];
}

// tcode / amount handling (original behavior)
if (isset($_POST["tcode"])) {
    $id = get_current_user_id();
    $user = get_userdata($id);
    $name = $user ? $user->display_name : '';
    $email = $user ? $user->user_email : '';

    if (vp_getoption("charge_method") == "fixed") {
        $amount = intval($_POST['amount']) + floatval(vp_getoption("charge_back"));
    } else {
        $remove = (intval($_POST['amount']) * floatval(vp_getoption("charge_back"))) / 100;
        $amount = intval($_POST['amount']) + $remove;
    }

    $tcode = $_POST['tcode'] ?? '';
    setcookie('amount', $amount, time() + (30 * 24 * 60 * 60), "/");

    if (isset($_POST['secret'])) {
        setcookie('secret', $_POST['secret'], time() + (30 * 24 * 60 * 60), "/");
    }
    setcookie('tcode', "wallet", time() + (30 * 24 * 60 * 60), "/");
}

if (isset($_POST["ud"])) {
    setcookie('ud', $_POST['ud'], time() + (30 * 24 * 60 * 60), "/");
}
if (isset($_POST["id"])) {
    setcookie('id', $_POST['id'], time() + (30 * 24 * 60 * 60), "/");
}

$check_bal = vp_getoption("checkbal");

// Proceed only if amount present or balance check disabled
if (!isset($amounte) && $check_bal != "no") {
    echo "<script>
            alert('Sorry this transaction can\\'t be completed at the moment please check back later');
            window.history.back();
          </script>";
    exit;
}

/**
 * Helper: output minimal JS/CSS includes used by multiple gateways
 * (keeps behavior same as original where files are included inline)
 */
function output_shared_assets()
{
    // NOTE: these paths match your original structure
    echo '<link href="' . get_option('siteurl') . '/wp-content/plugins/vtupress/formstyle.css?v=1" rel="stylesheet" />';
    echo '<div id="cover-spin" style="display:none;"></div>';
    echo '<script src="' . esc_url(plugins_url("vtupress/js/sweet.js?v=1")) . '"></script>';
    echo '<script src="' . esc_url(plugins_url("vtupress/js/jquery.js?v=1")) . '"></script>';
}

/**
 * Helper: a simple unified AJAX processor for Paystack responses
 * url: the process.php url (constructed in Paystack section)
 */
function output_paystack_ajax_helpers()
{
    ?>
    <script>
    // show/hide cover spinner
    function vpShowSpinner() {
        try { jQuery("#cover-spin").show(); } catch(e) {}
    }
    function vpHideSpinner() {
        try { jQuery("#cover-spin").hide(); } catch(e) {}
    }

    function vpProcessPaystack(url) {
        vpShowSpinner();
        jQuery.ajax({
            url: url,
            dataType: 'text',
            cache: false,
            async: true,
            type: 'POST',
            success: function(data) {
                vpHideSpinner();
                if (data == "100") {
                    swal({
                        title: "Successful!",
                        text: "Account Funded!",
                        icon: "success",
                        button: "Okay",
                    }).then(function() { window.location.href = "/vpaccount"; });
                } else {
                    swal({
                        title: "Error",
                        text: data,
                        icon: "error",
                        button: "Okay",
                    }).then(function() { window.location.href = "/vpaccount"; });
                }
            },
            error: function(jqXHR, exception) {
                vpHideSpinner();
                var msg = "";
                if (jqXHR.status === 0) {
                    msg = "No Connection. Verify Network.";
                } else if (jqXHR.status == 404) {
                    msg = "Requested page not found. [404]";
                } else if (jqXHR.status == 500) {
                    msg = "Internal Server Error [500].";
                } else if (exception === "parsererror") {
                    msg = "Requested JSON parse failed.";
                } else if (exception === "timeout") {
                    msg = "Time out error.";
                } else if (exception === "abort") {
                    msg = "Ajax request aborted.";
                } else {
                    msg = "Uncaught Error.\n" + (jqXHR.responseText || "");
                }
                swal({ title: "Error!", text: msg, icon: "error", button: "Okay" });
            }
        });
    }
    </script>
    <?php
}

// -------------------------
// FLUTTERWAVE
// -------------------------
if ($paychoice === "flutterwave") {
    // Keep your $k, $currency, $country, $name, $email as-is (you said they're defined elsewhere or earlier)
    // Output a hidden button that auto-clicks to open Flutterwave checkout (v3/v2 compatibility handled by including correct script)
    // Using FlutterwaveCheckout as in your original but cleaned and simplified.
    echo '<div style="visibility:hidden;">
            <form>
              <script src="https://checkout.flutterwave.com/v3.js"></script>
              <button type="button" id="payf" onclick="makePayment()">Pay Now</button>
            </form>
          </div>';

    // JS for flutterwave
    // preserve $k, $amount, $currency, $country, $email, $name variables (assumed defined)
    ?>
    <script>
    function makePayment() {
        FlutterwaveCheckout({
            public_key: "<?php echo $k; ?>",
            tx_ref: "vtu" + Math.floor((Math.random() * 1000000000) + 1),
            amount: "<?php echo $amount; ?>",
            currency: "<?php echo $currency; ?>",
            country: "<?php echo $country; ?>",
            customer: {
                email: "<?php echo addslashes($email ?? ''); ?>",
                phone_number: "07049626922",
                name: "<?php echo addslashes($name ?? ''); ?>"
            },
            callback: function (data) {
                var status = data.status;
                if (status === "successful" || status === "Completed" || status === "completed") {
                    swal({
                        title: "Funding Successful",
                        text: "Might take a few minutes to finalize transaction",
                        icon: "success"
                    }).then(function() { window.location.href = "/vpaccount"; });
                } else {
                    swal({
                        title: "Transaction Failed",
                        text: "Transaction failed! CODE[" + status + "]",
                        icon: "error"
                    }).then(function() { window.history.back(); });
                }
            },
            onclose: function() {
                window.history.back();
            },
            customizations: {
                title: "<?php echo get_bloginfo('name'); ?>",
                description: "Payment for vtu services"
            }
        });
    }

    document.getElementById("payf").click();
    </script>
    <?php
    exit;
}

// -------------------------
// PAYSTACK (v2 inline)
// -------------------------
if ($paychoice === "paystack") {
    // calculate amount as original code does for paystack charge method
    if (vp_getoption("paystack_charge_method") == "fixed") {
        $amount = intval($_POST['amount']) + floatval(vp_getoption("paystack_charge_back"));
    } else {
        $remove = (intval($_POST['amount']) * floatval(vp_getoption("paystack_charge_back"))) / 100;
        $amount = intval($_POST['amount']) + $remove;
    }

    setcookie('amount', $amount, time() + (30 * 24 * 60 * 60), "/");

    // Output shared assets (css + sweet + jquery + cover spinner)
    output_shared_assets();
    output_paystack_ajax_helpers();

    // Use Paystack v2 inline script (the v2 script URL)
    // and use PaystackPop.setup (standard inline integration)
    ?>
    <div style="visibility:hidden;">
        <form id="paymentForm">
            <div class="form-submit">
                <button type="submit" id="payk"> Pay </button>
            </div>
        </form>
    </div>

    <script src="https://js.paystack.co/v2/inline.js"></script>
    <script>
    document.getElementById("paymentForm").addEventListener("submit", payWithPaystack, false);

    function payWithPaystack(e) {
        e.preventDefault();

        var handler = PaystackPop.setup({
            key: "<?php echo vp_getoption("ppub"); ?>",
            email: "<?php echo addslashes($email ?? ''); ?>",
            amount: <?php echo intval($amount); ?> * 100,
            currency: "<?php echo $currency; ?>",
            ref: "vtu" + Math.floor((Math.random() * 1000000000) + 1),
            onClose: function(){
                window.history.back();
            },
            callback: function(response){
                // Construct the server verification URL (matches your original)
                var locatio = "<?php echo vp_getoption("siteurl"); ?>/wp-content/plugins/vtupress/process.php?status=successful&current_clr=<?php echo urlencode($_COOKIE["current_clr"] ?? ""); ?>&gateway=paystack&amount=<?php echo intval($amount); ?>&reference=" + response.reference;

                swal({
                    title: "Please Wait",
                    text: "Press Okay and wait for account funding...",
                    icon: "info"
                }).then(function() {
                    // Use unified AJAX processor
                    vpProcessPaystack(locatio);
                });
            }
        });

        

        handler.openIframe();
    }

    // auto-click to start
    document.getElementById("payk").click();
    </script>
    <?php
    exit;
}

// -------------------------
// MONNIFY (preserve original logic, cleaned)
// -------------------------
if ($paychoice === "monnify") {
    if (vp_getoption("charge_method") == "fixed") {
        $amount = intval($_POST['amount']) + floatval(vp_getoption("charge_back"));
    } else {
        $remove = (intval($_POST['amount']) * floatval(vp_getoption("charge_back"))) / 100;
        $amount = intval($_POST['amount']) + $remove;
    }

    setcookie('amount', $amount, time() + (30 * 24 * 60 * 60), "/");

    $apikeym = vp_getoption("monnifyapikey");
    $secretkeym = vp_getoption("monnifysecretkey");

    $baseurl = (stripos($apikeym, "prod") === false) ? "https://sandbox.monnify.com" : "https://api.monnify.com";

    // Get access token
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $baseurl . '/api/v1/auth/login/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode("$apikeym:$secretkeym")
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $res = json_decode($response);

    if (!isset($res->responseBody->accessToken)) {
        die("CREDENTIALS INCORRECT OR ERROR WITH MONNIFY");
    }
    $auth = $res->responseBody->accessToken;

    // Initiate transaction
    $payload = json_encode([
        "amount" => $amount,
        "customerName" => $name,
        "customerEmail" => $email,
        "paymentReference" => uniqid("vtu-", false),
        "paymentDescription" => "VTU SERVICE",
        "currencyCode" => $currency,
        "contractCode" => vp_getoption("monnifycontractcode"),
        "redirectUrl" => vp_getoption("siteurl") . '/vpaccount',
        "paymentMethods" => ["CARD", "ACCOUNT_TRANSFER"]
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $baseurl . '/api/v1/merchant/transactions/init-transaction',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $auth,
            'Content-Type: application/json'
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);
    $respp = json_decode($response);

    if (isset($respp->responseBody->checkoutUrl)) {
        header("Location: " . $respp->responseBody->checkoutUrl);
        exit;
    } else {
        die("NO RESPONSE FROM MONNIFY. Please check your API KEYS and CONTRACT CODES");
    }
}

// If we reach here, invalid gateway
echo "<script>
    alert('Invalid payment gateway selected! Sorry this transaction can\\'t be completed at the moment please check back later');
    window.history.back();
    </script>";
exit;
?>
