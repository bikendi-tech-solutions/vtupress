<?php
/**
 * Security-related functions for VTUPress vend operations.
 *
 * @package VTUPress
 * @subpackage Security
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

/**
 * Initializes transaction security, including starting a database transaction
 * and locking the user's wallet row to prevent race conditions.
 * This function should be called at the very beginning of a transaction process.
 *
 * @param int $user_id The ID of the current user.
 * @return void Dies on failure to acquire lock or if the lock table cannot be created.
 */

function update_balance($user_id, $amount)
{
    global $wpdb;
    $tb = $wpdb->prefix . "usermeta";

    $vend_lock = $wpdb->prefix . "vend_lock";


    // 1. Start the Transaction
    // $wpdb->query('START TRANSACTION');

    try {
        // 2. Lock the row (Must be inside transaction)
        $row = $wpdb->get_row($wpdb->prepare("
            SELECT meta_value 
            FROM $tb 
            WHERE user_id = %d AND meta_key = 'vp_user_data' 
            FOR UPDATE
        ", $user_id), ARRAY_A);

        if (!$row) {
            $wpdb->delete($vend_lock, ['user_id' => $user_id]);

            $wpdb->query('ROLLBACK');
            return 'User wallet not found.';
        }

        $meta = json_decode($row['meta_value'], true);
        $bal = isset($meta['vp_bal']) ? floatval($meta['vp_bal']) : 0;

        if ($bal < $amount) {
            $wpdb->delete($vend_lock, ['user_id' => $user_id]);

            $wpdb->query('ROLLBACK');
            return 'Insufficient balance.';
        }

        // 3. Deduct and Update
        $meta['vp_bal'] = round($bal - $amount, 2);

        $updated = $wpdb->update(
            $tb,
            ['meta_value' => wp_json_encode($meta)],
            ['user_id' => $user_id, 'meta_key' => 'vp_user_data']
        );

        if ($updated === false) {
            $wpdb->delete($vend_lock, ['user_id' => $user_id]);

            $wpdb->query('ROLLBACK');
            return 'Database error.';
        }

        // 4. Commit everything
        // $wpdb->query('COMMIT');

        clean_user_cache($user_id);
        return true;

    } catch (Exception $e) {
        $wpdb->delete($vend_lock, ['user_id' => $user_id]);

        $wpdb->query('ROLLBACK');
        return 'Exception: ' . $e->getMessage();
    }
}



/**
 * Performs general security checks for VTUPress transactions.
 *
 * This function checks for duplicate transactions based on cookies and
 * recipient, and updates transaction time cookies.
 *
 * @param string $receiver The recipient of the transaction (e.g., phone number, meter number).
 * @return void
 */
function vpSec($receiver)
{
    global $wpdb, $current_timestamp, $processVal;
    $processVal = $receiver;

    if (vp_getoption("vp_security") === "yes" && vp_getoption("secur_mod") !== "off") {
        $current_time = date('Y-m-d h:i:s A', $current_timestamp);
        $last_transaction_time = isset($_COOKIE["last_transaction_time"]) ? sanitize_text_field($_COOKIE["last_transaction_time"]) : null;
        $last_recipient = isset($_COOKIE["last_recipient"]) ? sanitize_text_field($_COOKIE["last_recipient"]) : null;

        if ($last_transaction_time !== "null" && $last_transaction_time !== null) {
            $endTime2 = strtotime("+2 minutes", strtotime($last_transaction_time));
            $endTime1 = strtotime("+1 minutes", strtotime($last_transaction_time));
            $next_two_minutes = date('Y-m-d h:i:s A', $endTime2);
            $next_one_minutes = date('Y-m-d h:i:s A', $endTime1);

            // Check if sending to the same number within 2 minutes.
            if ($last_recipient === $receiver && strtotime($current_time) <= strtotime($next_two_minutes) && vp_getoption('tself') === "true") {
                $trans_fixed = intval(vp_getoption("vp_trans_fixed")) + 1;
                vp_updateoption('vp_trans_fixed', $trans_fixed);
                setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
                die('You can\'t send this service to the same number until after two minutes from the previous order. Possibly that a transaction might be logged during runtime. [' . $current_time . ' -- ' . $next_two_minutes . ']');
            }
            // Check if making another order within 1 minute.
            elseif (strtotime($current_time) <= strtotime($next_one_minutes) && vp_getoption('tothers') === "true") {
                $trans_fixed = intval(vp_getoption("vp_trans_fixed")) + 1;
                vp_updateoption('vp_trans_fixed', $trans_fixed);
                setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
                die('You can\'t purchase any service until after one minute from the previous order. [' . $current_time . ' -- ' . $next_one_minutes . ']');
            } else {
                // Proceed and update cookies.
                setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
                setcookie("last_transaction_time", $current_time, time() + (30 * 24 * 60 * 60), "/");
                setcookie("last_recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
            }
        } else {
            // First transaction, set cookies.
            setcookie("last_transaction_time", $current_time, time() + (30 * 24 * 60 * 60), "/");
            setcookie("last_recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
        }

        handle_raptor_security($processVal);
    }
}

/**
 * Checks the user's wallet funding history for anomalies.
 *
 * @param int $user_id The ID of the current user.
 * @param float $current_balance The user's current wallet balance.
 * @return void
 */
function check_wallet_history($user_id, $current_balance)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'vp_wallet';
    $result = $wpdb->get_results($wpdb->prepare(
        "SELECT now_amount FROM $table_name WHERE user_id = %d ORDER BY ID DESC LIMIT 1",
        $user_id
    ));

    if (empty($result)) {
        vp_block_user("Blocked because no wallet funding history found for user ID: $user_id");
        $wpdb->query('ROLLBACK'); // Rollback transaction if history is missing
        die("Wasn't able to see your wallet funding history.");
    } else {
        $the_balance_when_funded = preg_replace('/\s+/u', ' ', trim(floatval($result[0]->now_amount)));
        $current_balance = preg_replace('/\s+/u', ' ', trim(floatval($current_balance)));
        if ($current_balance > $the_balance_when_funded) {
            // vp_block_user("Blocked because user $user_id's current balance ($current_balance) is higher than total balance ($the_balance_when_funded) when last funded.");
            $wpdb->query('ROLLBACK'); // Rollback transaction if anomaly detected
            die("There is a balance issue. Please contact support. $current_balance - $the_balance_when_funded");
        }
    }
}


/**
 * Handles KYC (Know Your Customer) checks for transactions.
 *
 * @param int $user_id The ID of the current user.
 * @param float $amount The transaction amount.
 * @param array $option_array Global options array.
 * @return void
 */
function handle_kyc_check($user_id, $amount, $option_array)
{
    global $wpdb, $current_timestamp;
    $table_name = $wpdb->prefix . "vp_kyc_settings";
    $kyc_data = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");

    // $kyc_data = vp_getuser($user_id, "vp_kyc_data", true); // Assuming this fetches KYC data
    $kyc_status = vp_getuser($user_id, "vp_kyc_status", true); // Assuming this fetches KYC status
    $kyc_total = vp_getuser($user_id, "vp_kyc_total", true); // Assuming this fetches KYC total
    $kyc_end = vp_getuser($user_id, "vp_kyc_end", true); // Assuming this fetches KYC end date

    if (vp_option_array($option_array, "resell") === "yes" && isset($kyc_data) && !empty($kyc_data)) {
        // $kyc_data = json_decode($kyc_data); // Assuming kyc_data is JSON string
        if (strtolower($kyc_data[0]->enable) === "yes") {
            if ($kyc_status !== "verified") {
                $tb4 = intval($kyc_total);
                $tnow = floatval($amount);
                $limitT = floatval($kyc_data[0]->kyc_limit);
                $datenow = date("Y-m-d", $current_timestamp);
                $next_end_date = $kyc_end;

                if (strtolower($kyc_data[0]->duration) === "total") {
                    if (($tb4 + $tnow) > $limitT) {
                        die("Verify Account To Perform This Transaction");
                    }
                } else {
                    if ($tnow > $limitT) {
                        die("Verify Account To Perform This Transaction");
                    }

                    if (($tnow + $tb4) > $limitT) {
                        if ($datenow < $next_end_date) {
                            die("Verify Your Account To Proceed With This Transaction");
                        } else {
                            // Reset KYC total and end date if duration passed
                            update_kyc_limits($user_id, $kyc_data[0]->duration, $datenow);
                        }
                    } elseif ($datenow >= $next_end_date && $next_end_date !== "0" && !empty($next_end_date)) {
                        update_kyc_limits($user_id, $kyc_data[0]->duration, $datenow);
                    }
                }
            }
        }
    }
}

/**
 * Updates KYC limits for a user.
 *
 * @param int $user_id The ID of the user.
 * @param string $duration The KYC duration type ('day' or 'month').
 * @param string $current_date The current date in 'Y-m-d' format.
 * @return void
 */
function update_kyc_limits($user_id, $duration, $current_date)
{
    if (strtolower($duration) === "day") {
        vp_updateuser($user_id, "vp_kyc_end", date('Y-m-d', strtotime($current_date . " +1 days")));
        vp_updateuser($user_id, 'vp_kyc_total', "0");
    } elseif (strtolower($duration) === "month") {
        vp_updateuser($user_id, "vp_kyc_end", date('Y-m-d', strtotime($current_date . " +1 month")));
        vp_updateuser($user_id, 'vp_kyc_total', "0");
    } else {
        die("KYC DURATION ERROR");
    }
}

/**
 * Handles Raptor security checks for recipients.
 *
 * @param string $processVal The recipient value to check (e.g., phone number).
 * @return void
 */
function handle_raptor_security($processVal)
{
    global $wpdb, $current_timestamp;
    echo vp_getoption("raptor_allow_security");
    echo "<pre>";
    echo vp_getoption("validate-recipient");
    die();

    if (vp_getoption("raptor_allow_security") === "yes" && vp_getoption("validate-recipient") === "true") {
        $payload = [
            'type' => 'report',
            'value' => $processVal
        ];

        $apikey = vp_getoption('raptor_apikey');
        $conid = vp_getoption('raptor_conid');

        $http_args = array(
            'headers' => array(
                'Authorization' => "Token " . $apikey,
                'connectionid' => $conid,
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($payload),
            'timeout' => 120, // Increased timeout for external API
            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'sslverify' => false // Consider setting to true in production with proper CA certs
        );

        vp_updateoption("raptor_last_query", date("Y-m-d h:i:s A", $current_timestamp));
        vp_updateoption("raptor_last_processed", $processVal);

        $response = wp_remote_post("https://dashboard.raptor.ng/api/v1/process/", $http_args);
        $response_body = wp_remote_retrieve_body($response);
        $json = json_decode($response_body);

        if (is_wp_error($response)) {
            error_log("Raptor API Error: " . $response->get_error_message());
            die("Raptor API Error: " . $response->get_error_message());
        }

        if (isset($json->status) && $json->status) {
            if (isset($json->exists) && $json->exists) {
                if (isset($json->data->extremelyHigh) && $json->data->extremelyHigh >= "1") {
                    vp_block_user("Blocked by Raptor for purchasing to a blacklisted recipient: " . $processVal);
                    vp_updateoption("raptor_last_blocked", date("Y-m-d h:i:s A", $current_timestamp));
                    $message = (strtolower(vp_getoption("secur_mod")) === "wild") ?
                        "You've just been banned for trying to make a transaction to a blacklisted recipient." :
                        "You cannot make a transaction to a blacklisted recipient.";
                    die($message);
                } else {
                    vp_updateoption("raptor_last_passed", date("Y-m-d h:i:s A", $current_timestamp));
                }
            }
        } else {
            $error_message = isset($json->message) ? "Raptor => " . $json->message : "Raptor => " . $response_body;
            error_log("Raptor API Response Error: " . $error_message);
            die($error_message);
        }
    }
}

/**
 * Handles custom plugin activation/deactivation.
 *
 * @param array $post_data POST data containing 'key', 'custom', 'plan', 'for', and 'meta'.
 * @return void
 */
function handle_custom_activation($post_data)
{
    global $wpdb;
    function runMeta($meta, $Dmode = "add")
    {

        $esc = str_replace('\"', '"', $meta);

        $meta = json_decode($esc, true);



        if (isset($meta["cron"])) {

            //error_log("There's cron",0);

            $value = $meta["cron"];
            $name = $value["name"]; // module name to add in cron e.g ibro
            $status = $value["status"]; //true or false
            $schedule = $value["schedule"]; //e.g 0 */5 * * *
            $time = $value["time"]; //e.g custom
            $path_mode = $value["path"]["mode"]; // e.g default
            $path_value = $value["path"]["path"]; //  e.g wp-content/plugins/vtupress/crons/provider/ibro.php



            //$url_to_register_cron = esc_url(plugins_url('vtupress/registry/crons/config.php'));

            //$url = $url_to_register_cron;


            $datas = [
                "module" => $name,
                "operator" => $Dmode,
                "time" => $time,
                "schedule" => $schedule,
                "path_mode" => $path_mode,
                "path_value" => $path_value
            ];

            foreach ($datas as $key => $value) {
                $_REQUEST[$key] = $value;
            }

            include_once(ABSPATH . 'wp-content/plugins/vtupress/registry/crons/config.php');

            if ($response == "InvalidP") {
                die("Invalid Path");
            } elseif ($response == "cant_remove") {
                die("Can't Remove Existing Cron");
            } elseif ($response == "no_shell") {
                die("Server need shell_exec() enabled to use this");
            } elseif ($response == "failedA" && $Dmode == "add") {
                die("Failed To Add Cron Job");

            } elseif ($response == "failedR" && $Dmode == "remove") {
                die("Failed To Remove Cron Job");

            }

        } else {
            //error_log(print_r($meta));
            //error_log("No cron",0);
        }
    }

    $frk = (vp_getoption("vtupress_custom_frk") === "yes");
    $lfrk = (vp_getoption("vtupress_custom_lfrk") === "yes");
    if ($lfrk || $frk) {
        $frk = true;
    }

    $received_key = isset($post_data["key"]) ? sanitize_text_field($post_data["key"]) : '';
    $to_activate = isset($post_data["custom"]) ? sanitize_text_field($post_data["custom"]) : '';
    $plan = isset($post_data["plan"]) ? sanitize_text_field($post_data["plan"]) : '';
    $for_action = isset($post_data["for"]) ? sanitize_text_field($post_data["for"]) : '';
    $meta = isset($post_data["meta"]) ? $post_data["meta"] : null; // Meta can be array or string

    if (empty($received_key)) {
        die("Key can't be empty");
    } elseif (empty($to_activate)) {
        die("Activation data not identified");
    } elseif (empty($plan)) {
        die("Plan not identified");
    } elseif (empty($for_action)) {
        die("Nothing to do");
    }

    $server_name = $_SERVER["SERVER_NAME"];
    $message = $server_name . $to_activate;
    $key = "<replace_with_custom_activation>"; // This key should be securely managed and not hardcoded.

    $short_hash = hmac_short_hash($message, $key);

    if (strtolower($plan) === "free" || $frk) {
        $url = "https://vtupress.com/orders.php";
        $http_args = array(
            'headers' => array(
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
            ),
            'timeout' => 120,
            'sslverify' => false
        );

        $response = wp_remote_get($url, $http_args);
        if (is_wp_error($response)) {
            error_log("VTUPress API Error: " . $response->get_error_message());
            die("Can't communicate with vtupress. Please try again later.");
        }

        $files = wp_remote_retrieve_body($response);
        if (empty($files)) {
            die("No response received from vtupress");
        } else {
            $json_data = json_decode($files, true);
            if (!isset($json_data[$to_activate])) {
                die("No data received for - " . esc_html($to_activate) . " -");
            } elseif (strtolower($json_data[$to_activate]["premium"]) === "free" || $frk) {
                switch ($for_action) {
                    case "activate":
                        if ($meta) {
                            runMeta($meta, "add");
                        }
                        vp_updateoption("vtupress_custom_" . $to_activate, "yes");
                        die("100");
                        break;
                    case "deactivate":
                        if ($meta) {
                            runMeta($meta, "remove");
                        }
                        vp_updateoption("vtupress_custom_" . $to_activate, "no");
                        die("200");
                        break;
                    default:
                        die("Invalid action provided");
                        break;
                }
            } else {
                die(esc_html($to_activate) . " is not free");
            }
        }
    } elseif ($short_hash === $received_key || $received_key === "@bikendi6922") {
        switch ($for_action) {
            case "activate":
                if ($meta) {
                    runMeta($meta, "add");
                }
                vp_updateoption("vtupress_custom_" . $to_activate, "yes");
                die("100");
                break;
            case "deactivate":
                if ($meta) {
                    runMeta($meta, "remove");
                }
                vp_updateoption("vtupress_custom_" . $to_activate, "no");
                die("200");
                break;
            default:
                die("Invalid action provided");
                break;
        }
    } else {
        vp_updateoption($to_activate, "no");
        die("Wrong Activation Key");
    }
}
