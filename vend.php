<?php
/**
 * Main entry point for transaction processing.
 *
 * This file handles initial setup, security checks, and dispatches requests
 * to appropriate handlers based on the 'vend' and 'tcode' parameters.
 *
 * @package VTUPress
 */

// Ensure WordPress environment is loaded.
if (!defined('ABSPATH')) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
} else {
    include_once(ABSPATH . "wp-load.php");
}

// Suppress errors in production.
if (WP_DEBUG === false) {
    error_reporting(0);
}

// Include necessary WordPress and VTUPress functions.
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
include_once(ABSPATH . 'wp-content/plugins/vtupress/functions.php'); // Ensure your functions.php is included

// Auto-override VTUPress settings.
vtupress_auto_override();

// Set CORS header for self-origin.
header("Access-Control-Allow-Origin: 'self'");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("X-Xss-Protection: 1; mode=block");
header('Permissions-Policy: geolocation=(self), camera=(self), microphone=(self)');

// Include custom security, helper, and API handler files.
require_once __DIR__ . '/includes/vend-security.php';
require_once __DIR__ . '/includes/vend-helpers.php';
require_once __DIR__ . '/includes/vend-api-handlers.php';
require_once __DIR__ . '/includes/vend-transactions.php';


// Referrer check for security.
$allowed_referrers = [$_SERVER["SERVER_NAME"]];
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url(sanitize_url($_SERVER['HTTP_REFERER']), PHP_URL_HOST);
    if (!in_array($referer, $allowed_referrers, true)) {
        error_log("Unauthorized referrer access attempt: " . $referer);
        die("REF ENT PERM");
    }
} else {
    error_log("Missing HTTP_REFERER header.");
    die("BAD");
}

// Global variables setup (consider passing these as arguments instead of globals).
// This function needs to be defined in vend-helpers.php
foreach (get_all_vend_globals() as $key => $value) {
    global ${$key};
    global ${$value};
}

// Global variable for current timestamp.
global $current_timestamp;
$current_timestamp = current_time('timestamp');

// Sanitize all incoming $_REQUEST data as a first line of defense.
// More specific sanitization should occur at the point of use for critical data.
if (isset($_REQUEST) && is_array($_REQUEST)) {
    foreach ($_REQUEST as $key => $val) {
        $_REQUEST[$key] = sanitize_text_field($val);
    }
}

// User session timeout check.
if (strtolower(vp_getoption("vtu_timeout")) !== "false" && vp_getoption("vtu_timeout") !== "0" && !empty(vp_getoption("vtu_timeout")) && is_numeric(vp_getoption("vtu_timeout"))) {
    if (intval(vp_getoption("vtu_timeout")) <= 60 && intval(vp_getoption("vtu_timeout")) > 0) {
        $last_login = isset($_COOKIE["last_login"]) ? sanitize_text_field($_COOKIE["last_login"]) : '';
        $dur = vp_getoption("vtu_timeout");
        $cur = date('Y-m-d H:i:s', $current_timestamp);
        $timeout = date("Y-m-d H:i:s", strtotime("$last_login +$dur minutes"));

        if (($cur < $timeout) || (current_user_can("vtupress_admin") || current_user_can("administrator"))) {
            setcookie("last_login", date('Y-m-d H:i:s', $current_timestamp), time() + (30 * 24 * 60 * 60), "/");
        } else {
            wp_logout();
            die("You're Logged-Out. \n You Need To Re-Login!");
        }
    }
}

global $option_array;
$option_array = json_decode(get_option("vp_options"), true);

// Security checks from vend-old.php
if (vp_getoption("vp_security") == "yes") {
    // These headers are already set at the top, but keeping this block for any other security logic
    // specific to vp_security option if it were to contain more than just headers.

    // Check logged in status
    vp_sessions(); // Ensure session is started/managed
    if (!is_user_logged_in()) {
        die('{"status":"200","response":"You are logged out! Kindly Relogin"}');
    }
}

// --- MAIN TRANSACTION PROCESSING BLOCK ---
if (isset($_POST["vend"])) {
    // 1. Race condition fix: Start database transaction and lock user row immediately.
    // This is the earliest point to prevent concurrent modifications.
    $current_user_id = get_current_user_id();
    if ($current_user_id === 0) {
        die('{"status":"200","response":"User not logged in or invalid user ID."}');
    }
    vend_init($current_user_id); // Call the new function for early race condition fix

    // Sanitize and validate amount early
    if (isset($_POST["amount"])) {
        $amount_raw = $_POST["amount"];
        if (preg_match("/-/", $amount_raw)) {
            vp_block_user("Tried to perform a transaction with a negative amount!");
            $wpdb->query('ROLLBACK'); // Rollback if invalid amount
            die("Don't try negative balance");
        }
        $amount = floatval($amount_raw);
    } else {
        $wpdb->query('ROLLBACK'); // Rollback if amount is missing
        die("Invalid request: Amount is missing.");
    }

    // Extract user details
    extract(vtupress_user_details()); // This function should provide $id, $name, $email, $phone, $bal, $kyc_status, $kyc_total, $kyc_end etc.
    // Ensure these variables are properly set by vtupress_user_details()
    $id = get_current_user_id(); // Re-confirm user ID
    $name_obj = get_userdata($id);
    $name = $name_obj ? $name_obj->user_login : 'Unknown User';
    $email = $name_obj ? $name_obj->user_email : 'unknown@example.com';
    $phone = vp_getuser($id, "vp_phone", true);
    if (empty($phone)) {
        $phone = "0800000001"; // Default phone if not set for user
    }

    $tphone =
        $bal = floatval(vp_getuser($id, "vp_bal", true)); // User's current balance

    // Initialize $uniqidvalue
    $uniqidvalue = date('Ymd', $current_timestamp) . date('H', $current_timestamp) . date("i", $current_timestamp) . date("s", $current_timestamp) . uniqid('', false);


    // Browser detection
    $agent = $_SERVER["HTTP_USER_AGENT"];
    $browser = "UNKNOWN";
    if (preg_match('/MSIE (\d+\.\d+);/', $agent)) {
        $browser = "IE";
    } elseif (preg_match('/Chrome[\/\s](\d+\.\d+)/', $agent)) {
        $browser = "CHROME";
    } elseif (preg_match('/Edge\/\d+/', $agent)) {
        $browser = "EDGE";
    } elseif (preg_match('/Firefox[\/\s](\d+\.\d+)/', $agent)) {
        $browser = "FIREFOX";
    } elseif (preg_match('/OPR[\/\s](\d+\.\d+)/', $agent)) {
        $browser = "OPERA";
    } elseif (preg_match('/Safari[\/\s](\d+\.\d+)/', $agent)) {
        $browser = "SAFARI";
    }

    // Check for negative amount (already done above, but good to have a final check before processing)
    if ($amount < 0) {
        vp_block_user("Tried to make transaction with a negative amount!");
        $wpdb->query('ROLLBACK');
        die("Don't try negative balance");
    }

    // Check user access
    $vpaccess = vp_getuser($id, 'vp_user_access', true);
    if (strtolower($vpaccess) != "false" && strtolower($vpaccess) != "access" && empty($vpaccess) && !current_user_can("administrator")) {
        $wpdb->query('ROLLBACK');
        die('{"status":"222","response":"You Are Currently Banned From Making Transactions. Please Contact Admin -- CODE --  [' . $vpaccess . ']');
    }

    // Get transaction code
    $tcode = sanitize_text_field($_POST['tcode'] ?? '');

    // Initialize balance after transaction (baln) and amount charged (amountv)
    $baln = $bal; // Default to current balance, will be updated based on service
    $amountv = $amount; // Default to requested amount, will be updated based on service

    // Get user's plan level for discounts/charges
    $plan = vp_getuser($id, "vr_plan", true);
    $table_name_levels = $wpdb->prefix . "vp_levels";
    $level_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name_levels} WHERE name = %s", $plan));
    $level = !empty($level_data) ? $level_data[0] : null;
    $network = sanitize_text_field($_POST["network"] ?? '');


    if ($level == null) {
        $wpdb->query('ROLLBACK');
        die("Current User Package Not Found");
    } else {
        // Calculate actual amount and new balance based on service type and user level
        switch ($tcode) {
            case "cair": // Airtime
                $processVal = sanitize_text_field($_POST["phone"] ?? '');
                $airtimechoice = sanitize_text_field($_POST['airtimechoice'] ?? '');
                $disnetwork = sanitize_text_field($_POST['thisnetwork'] ?? ''); // Network for airtime
                $discount_rate = 0;

                if ($airtimechoice == "vtu") {
                    switch ($disnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_vtu);
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_vtu);
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_vtu);
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_vtu);
                            break;
                    }
                } elseif ($airtimechoice == "share") {
                    switch ($disnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_share);
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_share);
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_share);
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_share);
                            break;
                    }
                } elseif ($airtimechoice == "awuf") {
                    switch ($disnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_awuf);
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_awuf);
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_awuf);
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_awuf);
                            break;
                    }
                }
                $amountv = $amount - ($amount * $discount_rate / 100);
                $baln = $bal - $amount; // Balance is deducted by the full amount
                break;

            case "cdat": // Data
                $processVal = sanitize_text_field($_POST["phone"] ?? '');
                $datatcode = sanitize_text_field($_POST['datatcode'] ?? '');
                $datnetwork = sanitize_text_field($_POST['thatnetwork'] ?? ''); // Network for data
                $plan_index = intval($_REQUEST["plan_index"] ?? 0);
                $data_plan_name_from_request = sanitize_text_field($_REQUEST["data_plan"] ?? '');

                $discount_rate = 0;
                $plan_prefix_name = '';
                $plan_prefix_price = '';

                if ($datatcode == "sme") {
                    switch ($datnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_sme);
                            $plan_prefix_name = "cdatan";
                            $plan_prefix_price = "cdatap";
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_sme);
                            $plan_prefix_name = "gcdatan";
                            $plan_prefix_price = "gcdatap";
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_sme);
                            $plan_prefix_name = "9cdatan";
                            $plan_prefix_price = "9cdatap";
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_sme);
                            $plan_prefix_name = "acdatan";
                            $plan_prefix_price = "acdatap";
                            break;
                    }
                } elseif ($datatcode == "direct") {
                    switch ($datnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_gifting);
                            $plan_prefix_name = "rcdatan";
                            $plan_prefix_price = "rcdatap";
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_gifting);
                            $plan_prefix_name = "rgcdatan";
                            $plan_prefix_price = "rgcdatap";
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_gifting);
                            $plan_prefix_name = "r9cdatan";
                            $plan_prefix_price = "r9cdatap";
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_gifting);
                            $plan_prefix_name = "racdatan";
                            $plan_prefix_price = "racdatap";
                            break;
                    }
                } elseif ($datatcode == "corporate") {
                    switch ($datnetwork) {
                        case "MTN":
                            $discount_rate = floatval($level->mtn_corporate);
                            $plan_prefix_name = "r2cdatan";
                            $plan_prefix_price = "r2cdatap";
                            break;
                        case "GLO":
                            $discount_rate = floatval($level->glo_corporate);
                            $plan_prefix_name = "r2gcdatan";
                            $plan_prefix_price = "r2gcdatap";
                            break;
                        case "9MOBILE":
                            $discount_rate = floatval($level->mobile_corporate);
                            $plan_prefix_name = "r29cdatan";
                            $plan_prefix_price = "r29cdatap";
                            break;
                        case "AIRTEL":
                            $discount_rate = floatval($level->airtel_corporate);
                            $plan_prefix_name = "r2acdatan";
                            $plan_prefix_price = "r2acdatap";
                            break;
                    }
                } elseif ($datatcode == "smile" || $datatcode == "alpha") {
                    // For smile/alpha, assume no percentage discount, fixed amount
                    $amountv = $amount;
                    $baln = $bal - $amount;
                    // Plan validation is crucial here
                    $plan_prefix_name = ($datatcode == "smile") ? "csmiledatan" : "calphadatan";
                    $plan_prefix_price = ($datatcode == "smile") ? "csmiledatap" : "calphadatap";
                }

                if (!empty($plan_prefix_name) && !empty($plan_prefix_price)) {
                    $expected_plan_name = vp_option_array($option_array, $plan_prefix_name . $plan_index);
                    $expected_plan_price = floatval(vp_option_array($option_array, $plan_prefix_price . $plan_index));
                    $left = preg_replace('/\s+/u', ' ', trim($data_plan_name_from_request));
                    $right = preg_replace('/\s+/u', ' ', trim($expected_plan_name . ' ₦' . $expected_plan_price));

                    if ($left !== $right) {
                        $wpdb->query('ROLLBACK');
                        die('Plan mis-match please refresh and try again');
                    }
                    if ($amount != $expected_plan_price) {
                        vp_block_user("Modified the Price");
                        $wpdb->query('ROLLBACK');
                        die("Get OFF!!! The submitted price can't be different from what is set");
                    }
                }

                if ($datatcode != "smile" && $datatcode != "alpha") {
                    $amountv = $amount - ($amount * $discount_rate / 100);
                    $baln = $bal - $amount;
                }
                break;

            case "ccab": // Cable
                $planIndex = intval($_POST["plan_index"] ?? 0);
                $expected_cable_price = floatval(vp_getoption("ccablep" . $planIndex));
                if ($amount < $expected_cable_price) {
                    vp_block_user("Modified the Price");
                    $wpdb->query('ROLLBACK');
                    die("Get OFF!!! The submitted price can't be different from what is set");
                }
                $discount_rate = floatval($level->cable);
                $amountv = $amount - ($amount * $discount_rate / 100);
                $baln = $bal - $amount;
                $processVal = sanitize_text_field($_POST["iuc"] ?? '');
                break;
            case "cbill": // Bill Payment
                $discount_rate = floatval($level->bill_prepaid);
                $bill_charge = floatval(vp_option_array($option_array, "bill_charge"));
                $amountv = ($amount - ($amount * $discount_rate / 100));
                $_POST['amount'] = $amount; // Adjust total amount for balance deduction
                $baln = $bal - $_POST['amount'];
                $processVal = sanitize_text_field($_POST["meterno"] ?? '');
                break;

            case "cepin": // E-PIN
                $amountv = $amount;
                $baln = $bal - $amount;
                $processVal = '12345678';
                break;
            case "csms": // SMS
                $amountv = $amount;
                $baln = $bal - $amount;
                $processVal = sanitize_text_field($_POST["receiver"] ?? '');
                break;

            case "cbet": // Betting
                $bet_charge = intval(vp_getoption("betcharge"));
                $amount_en_charge = $amount - $bet_charge;
                $amountv = $amount_en_charge;
                $_POST['amount'] = $amount_en_charge; // Adjust total amount for balance deduction
                $baln = $bal - $amount_en_charge;
                $network = sanitize_text_field($_POST["bet_company"] ?? '');
                $processVal = sanitize_text_field($_POST["customerid"] ?? ''); // Assuming phone is customer ID for betting
                break;
            default:
                $baln = $bal - $amount;
                $amountv = $amount;
                break;
        }
    }

    // Adjust amount based on discount method if MLM is active
    if (is_plugin_active("vpmlm/vpmlm.php")) {
        $discount_method = vp_getoption("discount_method");
        if ($discount_method == "direct") {
            $baln = $bal - $amountv;
            $amount = $amountv;
        }
    } else {
        $discount_method = "null";
    }

    $realAmt = $_POST['amount']; // This should be the final amount to be charged to the user's balance

    // KYC check
    $add_total = "maybe"; // Default value
    $kyc_data = null; // Initialize kyc_data

    // Initialize $tb4 and $tnow with default values before KYC logic
    $tb4 = 0.0;
    $tnow = 0.0;

    // Fetch kyc_data if resell is enabled and user is logged in
    if (vp_option_array($option_array, "resell") == "yes" && is_user_logged_in()) {
        global $wpdb; // Ensure $wpdb is accessible here
        $table_name_kyc_settings = $wpdb->prefix . 'vp_kyc_settings';
        $kyc_results = $wpdb->get_results("SELECT * FROM {$table_name_kyc_settings} WHERE id = 1");
        if (!empty($kyc_results)) {
            $kyc_data = $kyc_results[0]; // Get the first row
        }
    }

    $kyc_status = vp_getuser($id, "vp_kyc_status", true);
    $kyc_total = floatval(vp_getuser($id, "vp_kyc_total", true));
    $kyc_end = vp_getuser($id, "vp_kyc_end", true);

    // Update $tb4 and $tnow based on actual KYC total and transaction amount
    // These will be used if the KYC check proceeds
    $tb4 = $kyc_total;
    $tnow = $amount;

    if (vp_option_array($option_array, "resell") == "yes" && $kyc_data !== null) {
        if (strtolower($kyc_data->enable) == "yes") {
            if ($kyc_status != "verified") {
                // $tb4 and $tnow are already initialized above,
                // their values might be updated within this block based on KYC logic.
                $limitT = floatval($kyc_data->kyc_limit);
                $datenow = date("Y-m-d", $current_timestamp);
                $next_end_date = $kyc_end;

                if (strtolower($kyc_data->duration) == "total") {
                    if (($tb4 + $tnow) <= $limitT) {
                        $add_total = "yes";
                    } else {
                        $wpdb->query('ROLLBACK');
                        die("Verify Account To Perform This Transaction");
                    }
                } else {
                    if ($tnow < $limitT) {
                        if (($tnow + $tb4) <= $limitT) {
                            $add_total = "yes";
                            if ($next_end_date == "0" || empty($next_end_date)) {
                                if (strtolower($kyc_data->duration) == "day") {
                                    vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 days")));
                                    vp_updateuser($id, 'vp_kyc_total', "0");
                                } elseif (strtolower($kyc_data->duration) == "month") {
                                    vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 month")));
                                    vp_updateuser($id, 'vp_kyc_total', "0");
                                } else {
                                    $wpdb->query('ROLLBACK');
                                    die("KYC DURATION ERROR");
                                }
                            }
                        } elseif ($datenow >= $next_end_date) {
                            if (strtolower($kyc_data->duration) == "day") {
                                vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 days")));
                                vp_updateuser($id, 'vp_kyc_total', "0");
                            } elseif (strtolower($kyc_data->duration) == "month") {
                                vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 month")));
                                vp_updateuser($id, 'vp_kyc_total', "0");
                            } else {
                                $wpdb->query('ROLLBACK');
                                die("KYC DURATION ERROR");
                            }
                        }
                    } else {
                        if ($datenow < $next_end_date) {
                            $wpdb->query('ROLLBACK');
                            die("Verify Your Account To Proceed With This Transaction");
                        } elseif ($datenow >= $next_end_date) {
                            if (strtolower($kyc_data->duration) == "day") {
                                vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 days")));
                                vp_updateuser($id, 'vp_kyc_total', "0");
                            } elseif (strtolower($kyc_data->duration) == "month") {
                                vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 month")));
                                vp_updateuser($id, 'vp_kyc_total', "0");
                            } else {
                                $wpdb->query('ROLLBACK');
                                die("KYC DURATION ERROR");
                            }
                        }
                    }
                }
            }
        }
    }

    // Pin verification
    $pin = sanitize_text_field($_POST["pin"] ?? '');
    $mypin = sanitize_text_field(vp_getuser($id, "vp_pin", true));
    if ($pin != $mypin) {
        $wpdb->query('ROLLBACK');
        die('pin');
    }

    // Balance and amount validation
    if (!is_numeric($bal) || !is_numeric($amount)) {
        $wpdb->query('ROLLBACK');
        die('Amount Or Balance Invalid');
    }

    // Minimum purchase amount check
    if ($amount < 5 && $tcode != "csms") { // SMS might have different minimums
        $wpdb->query('ROLLBACK');
        die("You can't purchase less than 5 [$amount]");
    }

    // Sufficient balance check
    if ($bal < $amount) {
        $wpdb->query('ROLLBACK');
        die("Insufficient Balance");
    }

    // Wallet history anomaly check
    check_wallet_history($id, $bal); // Calls the function from vend-security.php

    // Raptor security check
    if (vp_getoption("raptor_allow_security") == "yes" && vp_getoption("validate-recipient") == "true" && isset($processVal)) {
        $payload = [
            'type' => 'report',
            'value' => $processVal
        ];

        $apikey = vp_getoption('raptor_apikey');
        $conid = vp_getoption('raptor_conid');
        $http_args = array(
            'headers' => array(
                'Authorization' => "Token $apikey",
                'connectionid' => $conid,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($payload),
            'timeout' => '120',
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'sslverify' => false
        );

        vp_updateoption("raptor_last_query", date("Y-m-d h:i:s A", $current_timestamp));
        vp_updateoption("raptor_last_processed", $processVal);
        $response_raptor = wp_remote_retrieve_body(wp_remote_post("https://dashboard.raptor.ng/api/v1/process/", $http_args));
        $json_raptor = json_decode($response_raptor);

        if (isset($json_raptor->status)) {
            if ($json_raptor->status) {
                if ($json_raptor->exists) {
                    if ($json_raptor->data->extremelyHigh >= "1") {
                        if (strtolower(vp_getoption("secur_mod")) == "wild") {
                            vp_block_user("Blocked by raptor for purchasing to a blacklisted recipient $processVal ");
                            vp_updateoption("raptor_last_blocked", date("Y-m-d h:i:s A", $current_timestamp));
                            $wpdb->query('ROLLBACK');
                            die("You've just been banned for trying to make a transaction to a blacklisted recipient");
                        } else {
                            vp_updateoption("raptor_last_blocked", date("Y-m-d h:i:s A", $current_timestamp));
                            $wpdb->query('ROLLBACK');
                            die("You can't make transaction to a blacklisted recipient");
                        }
                    } else {
                        vp_updateoption("raptor_last_passed", date("Y-m-d h:i:s A", $current_timestamp));
                    }
                }
            }
        } else {
            if (isset($json_raptor->message)) {
                $wpdb->query('ROLLBACK');
                die("Raptor => " . $json_raptor->message);
            }
            $wpdb->query('ROLLBACK');
            die("Raptor => " . $response_raptor);
        }
    }

    // MLM hook
    if (is_plugin_active("vpmlm/vpmlm.php")) {
        do_action("vp_mlm");
    }

    // Process transaction based on tcode.
    // This function will now be responsible for calling pre_transaction_checks_and_setup
    // and then dispatching to the specific service handlers.
    process_transaction($tcode, $_POST, $id, $name, $email, $processVal, $network, $url, $uniqidvalue, $bal, $baln, $amount, $realAmt, $browser, $option_array, $add_total, $tb4, $tnow);

} elseif (isset($_GET["plugin_infos"])) {
    echo "Activation Version: " . esc_html(vp_getoption("last_activation_version")) . " \n";
    echo "Activation Time: " . esc_html(vp_getoption("last_activation_time")) . " \n";
} elseif (isset($_POST["custom_order"])) {
    include_once(__DIR__ . '/foradmin.php');
    handle_custom_activation($_POST);
} elseif (isset($_POST["paywall"])) {
    handle_paywall_upgrade($_POST);
} elseif (isset($_POST["withdrawit"])) {
    handle_withdrawal($_POST);
} elseif (isset($_POST["convert_it"])) {
    handle_airtime_conversion($_POST);
} elseif (isset($_POST["set_pin"])) {
    handle_setpin($_POST);
} elseif (isset($_POST["check_balance"])) {
    $my_id = get_current_user_id();
    $my_balance = vp_getuser($my_id, 'vp_bal', true);

    if ($my_balance != "0" || $my_balance !== false || $my_balance != "") {
        echo '{"status":"100", "balance":"' . $my_balance . '"}';
    } else {
        echo '{"status":"200"}';
    }

} elseif (isset($_POST['setmlm'])) {

    global $wpdb;
    $table_name = $wpdb->prefix . "vp_pv_rules";
    $rules = $wpdb->get_results("SELECT * FROM  $table_name");

    foreach ($rules as $rule) {
        $my_id = $rule->id;

        if (isset($_POST["set_plan$my_id"]) && isset($_POST["required_pv$my_id"]) && isset($_POST["bonus_amount$my_id"])) {
            $set_plan = $_POST["set_plan$my_id"];
            $required_pv = $_POST["required_pv$my_id"];
            $bonus_amount = $_POST["bonus_amount$my_id"];

            $arg = [
                'required_pv' => $required_pv,
                'upgrade_plan' => $set_plan,
                'upgrade_balance' => $bonus_amount

            ];

            $wpdb->update($table_name, $arg, array('id' => $my_id));

        }
    }



    vp_updateoption("vp_min_withdrawal", $_POST['minwith']);
    vp_updateoption("vp_trans_min", $_POST['mintrans']);
    vp_updateoption("discount_method", $_POST['discountmethod']);

    echo '{"status":"100"}';
} elseif (isset($_POST["verify_user"])) {

    if (!is_user_logged_in()) {
        die("Please Login");
    }

    $user_id = $_POST["user_id"];
    $user_name = get_userdata($user_id)->user_login;

    if ($user_name != "0" || $user_name !== false || $user_name != "") {
        echo '{"status":"100", "user_name":"' . $user_name . '"}';
    } else {
        echo '{"status":"200"}';
    }
} elseif (isset($_POST['setactivation'])) {
    handle_activation();
} else {
    // Default response or error for unhandled requests.
    die('{"status":"error","response":"Invalid Request."}'); // Changed from wp_die()
}
/*
Quick syntax check: php -l file.php

Run & debug with full errors: php -d display_errors=1 -d error_reporting=E_ALL file.php

Inline test: php -r 'your_code_here;'
*/

// Ensure no further output after wp_die.
exit;
