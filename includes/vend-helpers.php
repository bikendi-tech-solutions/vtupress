<?php
/**
 * Helper functions for VTUPress vend operations.
 *
 * @package VTUPress
 * @subpackage Helpers
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

/**
 * Returns an array of global variables used in the vend script.
 * This helps in centralizing global declarations.
 *
 * @return array
 */
function get_all_vend_globals()
{
    return [
        'wpdb' => 'level',
        'plan' => 'amount',
        'id' => 'amountv',
        'sec' => 'mlm_for',
        'discount_method' => 'dplan',
        '$_POST' => '$_COOKIE', // This mapping is problematic and should be refactored.
        'phone' => 'uniqidvalue',
        'network' => 'url',
        'vpaccess' => 'bal',
        'vpdebug' => 'name',
        'plan' => 'baln',
        'current_timestamp' => 'nothingasvaluehere', // 'nothingasvaluehere' is a placeholder, should be removed or properly used.
        'pin' => 'added_to_db',
        'realAmt' => 'realAmt'
    ];
}

/**
 * Sends a message using the custom web link SMS blaster.
 *
 * @param string $phone The recipient phone number.
 * @param string $message The message content.
 * @return void
 */
function weblinkBlast($phone, $message)
{
    global $wpdb;
    if (vp_getoption("vtupress_custom_weblinksms") === "yes" && vp_getoption("sms_transactional") === "yes") {
        $data = [
            "phone" => sanitize_text_field($phone),
            "message" => sanitize_textarea_field($message),
            "the_time" => current_time('mysql')
        ];
        $table_name = $wpdb->prefix . 'vp_smsblaster';
        $wpdb->insert($table_name, $data);
    }
}

/**
 * Updates the user's wallet with a new transaction entry.
 *
 * @param string $status Status of the wallet update (e.g., "Approved", "Failed").
 * @param string $credit_message Description of the transaction.
 * @param float $amount The amount of the transaction.
 * @param float $before_amount The balance before the transaction.
 * @param float $now_amount The balance after the transaction.
 * @return void
 */
function update_wallet($status, $credit_message, $amount, $before_amount, $now_amount)
{
    global $wpdb, $current_timestamp;
    $user_id = get_current_user_id();
    $user_data = get_userdata($user_id);
    $name = $user_data ? $user_data->user_login : 'Unknown';

    $table_name = $wpdb->prefix . 'vp_wallet';
    $wpdb->insert($table_name, array(
        'name' => sanitize_user($name),
        'type' => "Wallet",
        'description' => sanitize_text_field($credit_message),
        'fund_amount' => floatval($amount),
        'before_amount' => floatval($before_amount),
        'now_amount' => floatval($now_amount),
        'user_id' => $user_id,
        'status' => sanitize_text_field($status),
        'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
    ));
}

/**
 * Sends a push notification message to a registered device.
 *
 * @param int $regid The user ID (registration ID).
 * @param string $message The message content.
 * @return string "true" on success, "false" on failure.
 */
function sendMessage($regid, $message)
{
    global $wpdb;
    $web_name = get_option("blogname");
    $table = $wpdb->prefix . "vp_message";
    $tokens = $wpdb->get_results($wpdb->prepare("SELECT user_token FROM $table WHERE user_id = %d", $regid));

    if (empty($tokens)) {
        return "false";
    }

    $registrationIds = [];
    foreach ($tokens as $token) {
        $registrationIds[] = $token->user_token;
    }

    $header = [
        'Authorization: Key=' . vp_getoption("server_apikey"),
        'Content-Type: Application/json'
    ];

    $user_name = get_userdata($regid)->user_login;
    $msg = [
        'title' => "New Message From " . esc_html($web_name) . " To " . esc_html($user_name),
        'body' => sanitize_textarea_field($message),
        'icon' => esc_url(plugins_url("vtupress/images/vtupress.png")),
        'image' => '',
    ];

    $payload = [
        'registration_ids' => $registrationIds,
        'data' => $msg
    ];

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => $header
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        error_log("FCM Send Error: " . $err);
        return "false";
    } else {
        return "true";
    }
}

/**
 * Attempts to get the first key from a JSON string representing an array.
 * This function's logic seems flawed and might need review.
 *
 * @param string $arr JSON string.
 * @return mixed The first key's value or "error" or the original string.
 */
function harray_key_first($arr = "")
{
    // If it's an array or object, convert to JSON string
    if (is_array($arr) || is_object($arr)) {
        $arr = json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    // If JSON string, normalize
    if (is_string($arr) && is_array(json_decode($arr, true))) {
        $arr = json_encode(json_decode($arr, true), JSON_UNESCAPED_UNICODE);
    }

    // Encode HTML so it displays literally, but keep quotes readable
    return $arr;
}



/**
 * Validates an API response against a success key and value.
 *
 * @param string $response The raw API response.
 * @param string $key The key to search for in the response. Can be 'key1&&key2' or 'key1||key2'.
 * @param string $value The expected success value.
 * @param string $alter An alternative success value.
 * @return string "TRUE", "MAYBE", or "FALSE".
 */
function validate_response($response = "", $key = "", $value = "", $alter = "nothing_to_find")
{
    global $msg; // Consider removing global $msg and returning it.

    if (empty($response)) {
        die("Empty response from the provider");
    }
    $array = json_decode($response, true);

    if ($array === null && json_last_error() !== JSON_ERROR_NONE) {
        // Not JSON → wrap it in an array
        $array = ['str' => $response];
    } elseif (is_array($array)) {
        // Only change keys if it's an array
        $array = array_change_key_case($array, CASE_LOWER);
    } else {
        // Valid JSON but not an array → wrap it in array for consistency
        $array = ['str' => $array];
    }


    // Nested helper function to search for a key in a multi-dimensional array.
    function search_Key_recursive($array, $key_to_find)
    {
        $results = [];
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                if (strtolower($k) === strtolower($key_to_find)) {
                    $results[] = $v;
                }
                if (is_array($v)) {
                    $results = array_merge($results, search_Key_recursive($v, $key_to_find));
                }
            }
        }
        return $results;
    }

    // Nested helper function to search for a value in an array of results.
    function search_val_in_results($results, $the_value, $alt)
    {
        $status = "FALSE";
        $the_value = strtolower(trim((string) $the_value));
        $alt = strtolower(trim((string) $alt));

        foreach ($results as $dvalue) {
            if (!is_array($dvalue)) {
                $mdvalue = strtolower(trim((string) $dvalue));

                //  If expected value is numeric → strict compare
                if (is_numeric($the_value)) {
                    if (
                        ($mdvalue === $the_value || $mdvalue === $alt || $mdvalue === "1")
                        && strpos($mdvalue, "not") === false
                    ) {
                        return "TRUE";
                    }
                } else {
                    //  For text → allow substrings (succ -> success)
                    if (
                        (strpos($mdvalue, $the_value) !== false || strpos($mdvalue, $alt) !== false || $mdvalue === "1")
                        && strpos($mdvalue, "not") === false
                    ) {
                        return "TRUE";
                    }
                }

                // MAYBE cases
                if (strpos($mdvalue, "proce") !== false || strpos($mdvalue, "pen") !== false) {
                    $status = "MAYBE";
                }
            }
        }

        return $status;
    }

    $status_from_result_val = "FALSE";

    if (strpos($key, '&&') !== false) {
        $explode = explode("&&", $key);
        $key1 = trim($explode[0]);
        $key2 = trim($explode[1]);

        $first_key_result = search_Key_recursive($array, $key1);
        $first_val_result = search_val_in_results($first_key_result, $value, $alter);

        $second_key_result = search_Key_recursive($array, $key2);
        $second_val_result = search_val_in_results($second_key_result, $value, $alter);

        if ($first_val_result === "TRUE" && $second_val_result === "TRUE") {
            $status_from_result_val = "TRUE";
        } elseif ($first_val_result === "MAYBE" || $second_val_result === "MAYBE") {
            $status_from_result_val = "MAYBE";
        }
        $msg = "FIRST = $first_val_result && SECOND = $second_val_result";
    } elseif (strpos($key, '||') !== false) {
        $explode = explode("||", $key);
        $key1 = trim($explode[0]);
        $key2 = trim($explode[1]);

        $first_key_result = search_Key_recursive($array, $key1);
        $first_val_result = search_val_in_results($first_key_result, $value, $alter);

        $second_key_result = search_Key_recursive($array, $key2);
        $second_val_result = search_val_in_results($second_key_result, $value, $alter);

        if ($first_val_result === "TRUE" || $second_val_result === "TRUE") {
            $status_from_result_val = "TRUE";
        } elseif ($first_val_result === "MAYBE" || $second_val_result === "MAYBE") {
            $status_from_result_val = "MAYBE";
        }
        $msg = "FIRST = $first_val_result || SECOND = $second_val_result";
    } else {
        $result = search_Key_recursive($array, $key);
        $status_from_result_val = search_val_in_results($result, $value, $alter);
    }

    return $status_from_result_val;
}


/**
 * Searches for a specific key in a multi-dimensional array and returns its values.
 * This is a duplicate of search_Key_recursive, consolidate if possible.
 *
 * @param array $array The array to search.
 * @param string $key The key to find.
 * @return array An array of values associated with the key.
 */
function search_bill_token($array = [], $key = "")
{
    $results = [];
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            if (strtolower($k) === strtolower($key)) {
                $results[] = $v;
            }
            if (is_array($v)) {
                $results = array_merge($results, search_bill_token($v, $key));
            }
        }
    }
    return $results;
}

/**
 * Performs a remote POST request using cURL.
 * This function should ideally use wp_remote_post for WordPress compatibility and better error handling.
 *
 * @param string $url The URL to send the request to.
 * @param array $request The POST data as an associative array.
 * @return string The response body.
 */
function vp_remote_post_curl($url = "", $request = "")
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    // Consider adding CURLOPT_TIMEOUT, CURLOPT_SSL_VERIFYPEER for security.

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        return "error"; // Or throw an exception
    }
    curl_close($ch);
    return $response;
}

/**
 * Gets the user's level details from the database.
 *
 * @param int $user_id The ID of the user.
 * @return array|null User level details or null if not found.
 */
function get_user_level_details($user_id)
{
    global $wpdb;
    $plan = vp_getuser($user_id, "vr_plan", true);
    $table_name = $wpdb->prefix . "vp_levels";
    $level = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s", $plan));
    return $level;
}

/**
 * Calculates transaction amounts based on the transaction code and user level.
 *
 * @param string $tcode Transaction code (e.g., 'cair', 'cdat', 'ccab').
 * @param array $post_data POST data.
 * @param array $level User level details.
 * @param float $current_bal Current user balance.
 * @param array $option_array Global options array.
 * @return array An array containing [calculated_amount_v, new_balance].
 */
function calculate_transaction_amounts($tcode, $post_data, $level, $current_bal, $option_array)
{
    $amountv = floatval($post_data['amount']);
    $baln = $current_bal - $amountv;

    if (!isset($level[0])) {
        return [$amountv, $baln]; // Return defaults if level data is missing
    }

    switch ($tcode) {
        case "cair":
            $airtime_choice = $post_data['airtimechoice'];
            $disnetwork = $post_data['thisnetwork'];
            $discount_percentage = 0;

            if ($airtime_choice === "vtu") {
                $discount_percentage = floatval($level[0]->{strtolower($disnetwork) . "_vtu"});
            } elseif ($airtime_choice === "share") {
                $discount_percentage = floatval($level[0]->{strtolower($disnetwork) . "_share"});
            } elseif ($airtime_choice === "awuf") {
                $discount_percentage = floatval($level[0]->{strtolower($disnetwork) . "_awuf"});
            }
            $discount_amount = ($amountv * $discount_percentage) / 100;
            $amountv -= $discount_amount;
            $baln = $current_bal - $post_data['amount']; // Original amount deducted from balance
            break;

        case "cdat":
            $datatcode = $post_data['datatcode'];
            $datnetwork = $post_data['thatnetwork'];
            $plan_index = $post_data["plan_index"];
            $data_plan_name = $post_data["data_plan"];

            $plan_prefix = '';
            $discount_field = '';

            if ($datatcode === "sme") {
                $plan_prefix = 'c';
                $discount_field = strtolower($datnetwork) . '_sme';
            } elseif ($datatcode === "direct") {
                $plan_prefix = 'rc';
                $discount_field = strtolower($datnetwork) . '_gifting';
            } elseif ($datatcode === "corporate") {
                $plan_prefix = 'r2c';
                $discount_field = strtolower($datnetwork) . '_corporate';
            } elseif ($datatcode === "smile") {
                $plan_prefix = 'csmile';
                // No discount calculation based on level for smile in original code, direct amount used
            } elseif ($datatcode === "alpha") {
                $plan_prefix = 'calpha';
                // No discount calculation based on level for alpha in original code, direct amount used
            }

            if ($datatcode !== "smile" && $datatcode !== "alpha") {
                $planname_option = $plan_prefix . 'datan' . $plan_index;
                $planprice_option = $plan_prefix . 'datap' . $plan_index;
                
                $vp_country = vp_country();
                $symbol = $vp_country["symbol"];

                $check_plan_name = vp_option_array($option_array, $planname_option);
                $check_plan_price = floatval(vp_option_array($option_array, $planprice_option));

                if ($check_plan_name . " $symbol" . $check_plan_price !== $data_plan_name) {
                    die('Plan Mis-Match. You can try with another browser');
                }
                if ($check_plan_price !== floatval($post_data["amount"])) {
                    vp_block_user("Modified the Price for data plan: " . $data_plan_name);
                    die("Get OFF!!! The submitted price can't be different from what is set");
                }

                $discount_percentage = floatval($level[0]->{$discount_field});
                $discount_amount = (floatval($post_data['amount']) * $discount_percentage) / 100;
                $amountv = floatval($post_data['amount']) - $discount_amount;
                $baln = $current_bal - floatval($post_data['amount']);
            } else {
                $baln = $current_bal - floatval($post_data['amount']);
                $amountv = floatval($post_data['amount']);
            }
            break;

        case "ccab":
            $planIndex = $post_data["plan_index"];
            $cable_plan_price = floatval(vp_getoption("ccablep" . $planIndex));
            if (floatval($post_data["amount"]) < $cable_plan_price) {
                vp_block_user("Modified the Price for cable plan.");
                die("Get OFF!!! The submitted price can't be different from what is set");
            }
            $discount_percentage = floatval($level[0]->cable);
            $discount_amount = (floatval($post_data['amount']) * $discount_percentage) / 100;
            $amountv = floatval($post_data['amount']) - $discount_amount;
            $baln = $current_bal - floatval($post_data['amount']);
            break;

        case "cbill":
            $bill_charge = floatval(vp_option_array($option_array, "bill_charge"));
            $discount_percentage = floatval($level[0]->bill_prepaid);
            $discount_amount = (floatval($post_data['amount']) * $discount_percentage) / 100;
            $amountv = (floatval($post_data['amount']) - $discount_amount) + $bill_charge;
            $baln = $current_bal - (floatval($post_data['amount']) + $bill_charge);
            $_POST['amount'] = floatval($post_data['amount']) + $bill_charge; // Update global $_POST for consistency
            break;

        case "cepin":
            $amountv = floatval($post_data['amount']);
            $baln = $current_bal - $amountv; // Assuming no discount for epin
            break;

        case "csms":
            $amountv = floatval($post_data['amount']);
            $baln = $current_bal - $amountv; // Assuming no discount for SMS
            break;

        case "cbet":
            $amountv = floatval($post_data['amount']);
            $bet_charge = intval(vp_getoption("betcharge"));
            $amount_en_charge = $amountv + $bet_charge;
            $baln = $current_bal - $amount_en_charge;
            $amountv = $amount_en_charge;
            $_POST['amount'] = $amount_en_charge; // Update global $_POST for consistency
            break;

        default:
            // No specific discount, use original amount
            $baln = $current_bal - floatval($post_data['amount']);
            $amountv = floatval($post_data['amount']);
            break;
    }

    return [$amountv, $baln];
}

/**
 * Performs a hash-based message authentication code (HMAC) and returns a short hash.
 * This function should ideally be in a dedicated cryptography utility file.
 *
 * @param string $message The message to hash.
 * @param string $key The secret key.
 * @return string The short HMAC hash.
 */
function hmac_short_hash($message, $key)
{
    $algorithm = 'sha256';
    $hash = hash_hmac($algorithm, $message, $key);
    $short_hash = substr($hash, 0, 8); // Adjust length as needed
    return $short_hash;
}

/**
 * Handles user paywall upgrades.
 *
 * @param array $post_data POST data containing 'level_name', 'level_id', 'withamt', 'withto', etc.
 * @return void
 */
function handle_paywall_upgrade($post_data)
{
    global $current_timestamp, $option_array;

    $id = get_current_user_id();
    $bal = vp_getuser($id, "vp_bal", true);
    $level_name = $_POST["level_name"];
    $level_id = $_POST["level_id"];

    $user_data = get_userdata($id);
    $pname = $user_data->user_login;
    $pdescription = "Upgraded To $level_name";

    global $wpdb;
    $table_name = $wpdb->prefix . "vp_levels";
    $data = $wpdb->get_results("SELECT * FROM  $table_name WHERE id = $level_id");

    $level_amount = $data[0]->upgrade;

    //error_log("Levek with id level_id ($level_id) below.",0);



    if ($bal >= $level_amount && stripos($bal, "-") == false) {
        $tot = $bal - $level_amount;
        vp_updateuser($id, "vp_bal", $tot);
        vp_updateuser($id, 'vr_plan', $level_name);

        //notify user about the update
        $table_name = $wpdb->prefix . 'vp_wallet';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $pname,
            'type' => "Wallet",
            'description' => $pdescription,
            'fund_amount' => $level_amount,
            'before_amount' => $bal,
            'now_amount' => $tot,
            'user_id' => $id,
            'status' => "Approved",
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));



        //error_log("bal($bal) > level_amount($level_amount)",0);



        $cur_plan = vp_getuser($id, 'vr_plan', true);
        //error_log("Plan now $cur_plan",0);
        $memRuleTable = $wpdb->prefix . "vp_membership_rule_stats";
        $current_date = date("Y-m-d", $current_timestamp);

        $pdata = [
            'user_id' => $id,
            'ref' => 0,
            'transaction_number' => 0,
            'transaction_amount' => 0,
            'start_count' => $current_date
        ];
        $wpdb->insert($memRuleTable, $pdata);


        //echo vp_getuser($id, 'vr_plan',true)." = ";
        vp_updateuser($id, 'vp_monthly_sub', date("Y-m-d H:i:s", $current_timestamp));
        //die($level_name);


        $apikey = vp_getuser($id, 'vr_id', true);
        if (empty($apikey) || strtolower($apikey) == "null" || $apikey === "0") {
            vp_updateuser($id, 'vr_id', uniqid());
        }


        if (isset($data[0]->upgrade_bonus)) {


            $level_amount_bonus = $data[0]->upgrade_bonus;

            //	error_log("Theres upgrade bonus of $level_amount_bonus",0);

            $get_bal = floatval(vp_getuser($id, "vp_bal", true)) + floatval($level_amount_bonus);

            $name = get_userdata($id)->user_login;
            $hname = $name;
            $description = "Got an upgrade bonus ";
            $fund_amount = floatval($level_amount_bonus);
            $before_amount = floatval(vp_getuser($id, "vp_bal", true));
            $now_amount = $get_bal;
            $the_time = date('Y-m-d h:i:s A', $current_timestamp);

            $table_name = $wpdb->prefix . 'vp_wallet';
            $added_to_db = $wpdb->insert($table_name, array(
                'name' => $name,
                'type' => "Wallet",
                'description' => $description,
                'fund_amount' => $fund_amount,
                'before_amount' => $before_amount,
                'now_amount' => $now_amount,
                'user_id' => $id,
                'status' => "Approved",
                'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
            ));


            vp_updateuser($id, "vp_bal", $get_bal);

            //	error_log("Fully updated to $now_amount",0);

        } else {
            //		error_log("No Upgrade Bonus",0);
            //		error_log(print_r($data,true),0);

        }

        global $wpdb;
        $usrs = $wpdb->prefix . "users";
        $usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $id");

        if (isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL && isset($data[0]->upgrade_pv)) {
            $d_pv = floatval($usrs_table[0]->vp_user_pv) + $data[0]->upgrade_pv;
            global $wpdb;
            $wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID' => $id));
        } else {
        }







        if (isset($data[0]->enable_extra_service) && vp_option_array($option_array, "vtupress_custom_mlmsub") == "yes") {
            if ($data[0]->enable_extra_service == "enabled") {
                //Start With User Airtime
                $user_airtime_bonus = $data[0]->airtime_bonus_ex1;
                $user_data_bonus = $data[0]->data_bonus_ex1;
                $ref_user_data_bonus = $data[0]->ref_data_bonus_ex1;
                $accessId = $data[0]->extra_feature_assigned_uId;
                $accessKey = vp_getuser($accessId, "vr_id", true);
                $phone = vp_getuser($id, "vp_phone", true);
                $prePhone = substr($phone, 0, 4);
                $ref_id = vp_getuser($id, 'vp_who_ref', true);
                $ref_phone = vp_getuser($ref_id, 'vp_phone', true);
                $ref_prePhone = substr($ref_phone, 0, 4);

                if ($ref_id != 1) {
                    $not_admin = true;
                } else {
                    $not_admin = false;
                }

                function getNetwork($prePhone)
                {
                    $network = "none";
                    switch ($prePhone) {
                        case "0703"://MTN
                            $network = "MTN";
                            break;
                        case "0704":
                            $network = "MTN";
                            break;
                        case "0706":
                            $network = "MTN";
                            break;
                        case "0803":
                            $network = "MTN";
                            break;
                        case "0806":
                            $network = "MTN";
                            break;
                        case "0810":
                            $network = "MTN";
                            break;
                        case "0813":
                            $network = "MTN";
                            break;
                        case "0814":
                            $network = "MTN";
                            break;
                        case "0816":
                            $network = "MTN";
                            break;
                        case "0903":
                            $network = "MTN";
                            break;
                        case "0906":
                            $network = "MTN";
                            break;
                        case "0913":
                            $network = "MTN";
                            break;
                        case "0916":
                            $network = "MTN";
                            break;
                        case "0701"://END OF MTN
                            $network = "AIRTEL";
                            break;
                        case "0708":
                            $network = "AIRTEL";
                            break;
                        case "0802":
                            $network = "AIRTEL";
                            break;
                        case "0808":
                            $network = "AIRTEL";
                            break;
                        case "0812":
                            $network = "AIRTEL";
                            break;
                        case "0901":
                            $network = "AIRTEL";
                            break;
                        case "0902":
                            $network = "AIRTEL";
                            break;
                        case "0904":
                            $network = "AIRTEL";
                            break;
                        case "0907":
                            $network = "AIRTEL";
                            break;
                        case "0912":
                            $network = "AIRTEL";
                            break;
                        case "0705"://END OF AIRTEL
                            $network = "GLO";
                            break;
                        case "0805":
                            $network = "GLO";
                            break;
                        case "0807":
                            $network = "GLO";
                            break;
                        case "0811":
                            $network = "GLO";
                            break;
                        case "0815":
                            $network = "GLO";
                            break;
                        case "0905":
                            $network = "GLO";
                            break;
                        case "0915":
                            $network = "GLO";
                            break;
                        case "0703"://END OF GLO
                            $network = "9MOBILE";
                            break;
                        case "0809":
                            $network = "9MOBILE";
                            break;
                        case "0817":
                            $network = "9MOBILE";
                            break;
                        case "0818":
                            $network = "9MOBILE";
                            break;
                        case "0908":
                            $network = "9MOBILE";
                            break;
                        case "0909":
                            $network = "9MOBILE";
                            break;
                    }
                    return strtolower($network);
                }


                $network = getNetwork($prePhone);
                $ref_network = getNetwork($ref_prePhone);

                if (intval($user_airtime_bonus) >= 100 && $network != "none") {

                    $amount = $data[0]->airtime_bonus_ex1;


                    $ref_amount = $data[0]->ref_airtime_bonus_ex1;


                    $url = strtolower(plugins_url('vprest/?q=airtime&id=' . $accessId . '&apikey=' . $accessKey . '&phone=' . $phone . '&amount=' . $amount . '&network=' . $network . '&type=vtu'));

                    $http_args = array(
                        'headers' => array(
                            'cache-control' => 'no-cache',
                            'Content-Type' => 'application/json'
                        ),
                        'timeout' => '3000',
                        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                        'sslverify' => false
                    );

                    wp_remote_get($url, $http_args);


                    if ($not_admin) {
                        //FOR REF
                        $url = strtolower(plugins_url('vprest/?q=airtime&id=' . $accessId . '&apikey=' . $accessKey . '&phone=' . $ref_phone . '&amount=' . $ref_amount . '&network=' . $ref_network . '&type=vtu'));

                        $http_args = array(
                            'headers' => array(
                                'cache-control' => 'no-cache',
                                'Content-Type' => 'application/json'
                            ),
                            'timeout' => '3000',
                            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                            'sslverify' => false
                        );

                        wp_remote_get($url, $http_args);
                        //echo $url;

                    }
                }






                function surfNetwork($network)
                {
                    $network_index = [];
                    switch ($network) {
                        case "mtn":
                            $network_index = ["cdatan", "api"];
                            break;
                        case "glo":
                            $network_index = ["gcdatan", "gapi"];
                            break;
                        case "9mobile":
                            $network_index = ["9cdatan", "9api"];
                            break;
                        case "airtel":
                            $network_index = ["acdatan", "aapi"];
                            break;
                    }

                    return $network_index;
                }

                function getThePlan($type, $network, $preplan)
                {
                    global $option_array;
                    switch ($type) {
                        case "sme":
                            $network_module = surfNetwork($network);

                            if (!empty($network_module)) {
                                for ($i = 0; $i <= 20; $i++) {
                                    $plan_name = strtoupper(vp_option_array($option_array, $network_module[0] . $i));

                                    //echo $plan_name."<br>";

                                    if (preg_match("/$preplan/", $plan_name)) {
                                        $plan_id = vp_option_array($option_array, $network_module[1] . $i);
                                        //echo $plan_id."sme";
                                        break;
                                    } else {
                                        $plan_id = NULL;
                                    }


                                }
                            } else {
                                $plan_id = NULL;
                            }

                            break;
                        case "direct":
                            $network_module = surfNetwork($network);
                            if (!empty($network_module)) {
                                for ($i = 0; $i <= 20; $i++) {
                                    $plan_name = strtoupper(vp_option_array($option_array, "r" . $network_module[0] . $i));

                                    if (preg_match("/$preplan/", $plan_name)) {
                                        $plan_id = vp_option_array($option_array, $network_module[1] . "2" . $i);
                                        //echo $plan_id."direct";
                                        break;
                                    } else {
                                        $plan_id = NULL;
                                    }

                                }
                            } else {
                                $plan_id = NULL;
                            }

                            break;
                        case "corporate":
                            $network_module = surfNetwork($network);
                            if (!empty($network_module)) {
                                for ($i = 0; $i <= 20; $i++) {
                                    $plan_name = strtoupper(vp_option_array($option_array, "r2" . $network_module[0] . $i));

                                    if (preg_match("/$preplan/", $plan_name)) {
                                        $plan_id = vp_option_array($option_array, $network_module[1] . "3" . $i);
                                        //echo $plan_id."corporate";
                                        break;
                                    } else {
                                        $plan_id = NULL;
                                    }


                                }
                            } else {
                                $plan_id = NULL;
                            }
                            break;
                        default:
                            $plan_id = NULL;
                    }
                    // $plan_id;
                    return $plan_id;

                }

                if (!empty($user_data_bonus) && !is_numeric($user_data_bonus)) {

                    $preplan = str_replace(" ", "\s*", strtoupper($data[0]->data_bonus_ex1));
                    $ref_preplan = str_replace(" ", "\s*", strtoupper($data[0]->ref_data_bonus_ex1));
                    $type = strtolower($data[0]->data_bonus_type_ex1);

                    $plan_id = getThePlan($type, $network, $preplan);

                    if ($plan_id != NULL) {

                        $url = strtolower(plugins_url('vprest/?q=data&id=' . $accessId . '&apikey=' . $accessKey . '&phone=' . $phone . '&dataplan=' . $plan_id . '&network=' . $network . '&type=' . $type));

                        $http_args = array(
                            'headers' => array(
                                'cache-control' => 'no-cache',
                                'Content-Type' => 'application/json'
                            ),
                            'timeout' => '3000',
                            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                            'sslverify' => false
                        );

                        wp_remote_get($url, $http_args);
                        //echo $url;

                        //die("Suure");



                    }
                }


                if (!empty($ref_user_data_bonus) && !is_numeric($ref_user_data_bonus) && $not_admin) {

                    $ref_preplan = str_replace(" ", "\s*", strtoupper($data[0]->ref_data_bonus_ex1));
                    $type = strtolower($data[0]->data_bonus_type_ex1);

                    $plan_id = getThePlan($type, $ref_network, $ref_preplan);

                    if ($plan_id != NULL) {

                        $url = strtolower(plugins_url('vprest/?q=data&id=' . $accessId . '&apikey=' . $accessKey . '&phone=' . $ref_phone . '&dataplan=' . $plan_id . '&network=' . $ref_network . '&type=' . $type));

                        $http_args = array(
                            'headers' => array(
                                'cache-control' => 'no-cache',
                                'Content-Type' => 'application/json'
                            ),
                            'timeout' => '3000',
                            'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                            'sslverify' => false
                        );

                        wp_remote_get($url, $http_args);
                        //echo $url;

                        //die("Suure");



                    }
                }



            }

        }

        if (is_plugin_active("vpmlm/vpmlm.php")) {
            /*	
                ////////////DIRECT//////////////////
                $my_d_ref = vp_getuser($id, "vp_who_ref", true); //get my ref id
                $dir_ref_bonus = vp_getoption("vp_first_level_bonus"); //get bonus amount for my direct ref




                /////////////////INDIR/////////////

                //credit grand ref
                $my_ind_ref = vp_getuser($my_d_ref, "vp_who_ref", true); //who ref my ref
                $indir_ref_bonus = vp_getoption("vp_second_level_bonus"); //get bonus amount for who ref my ref


                $cur_indir_bonus = vp_getuser($my_ind_ref, "vp_tot_in_ref_earn",true); // amt of my indirect ref like me
                $credit_indir_ref = intval($cur_indir_bonus) + intval($indir_ref_bonus); //cal total of bonus. current bal plus bonus
                vp_updateuser($my_ind_ref, "vp_tot_in_ref_earn", $credit_indir_ref); //add bonus to my ref

                //credit my great grand ref
            */


            if (strtolower($level_name) != "custome" && isset($data) && isset($data[0]->upgrade)) {

                $my_dir_ref = vp_getuser($id, "vp_who_ref", true);//get direct refer id
                //error_log("Who ref is $my_dir_ref",0);
                $total_level = $data[0]->total_level;

                $the_user = $my_dir_ref;

                global $wpdb;
                $usrs = $wpdb->prefix . "users";

                for ($lev = 1; $lev <= $total_level; $lev++) {

                    $current_level = "level_" . $lev . "_upgrade";
                    $discount = floatval($data[0]->$current_level);
                    //error_log("current_level is $current_level",0);

                    $give_away = intval($discount);


                    if (isset($data[0]->{"level_" . $lev . "_upgrade_pv"})) {
                        $give_pv = floatval($data[0]->{"level_" . $lev . "_upgrade_pv"});
                    } else {
                        $give_pv = 0;
                    }
                    //error_log("Pv is $give_pv",0);



                    if (vp_getuser($the_user, "vr_plan", true) != "custome" && $lev == 1 && $the_user != "0" && $the_user != "false") {
                        //error_log("Level is $lev and the user_id is $the_user",0);

                        $cur_dir_bonus = vp_getuser($the_user, "vp_tot_ref_earn", true);
                        $credit_dir_ref = intval($cur_dir_bonus) + intval($give_away); //cal total of bonus. current bal plus bonus
                        vp_updateuser($the_user, "vp_tot_ref_earn", $credit_dir_ref); //add bonus to my ref
                        //error_log("Earn is $cur_dir_bonus + $give_away = $credit_dir_ref",0);

                        $usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
                        if (isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL) {
                            $d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
                            global $wpdb;
                            $wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID' => $the_user));
                        } else {
                        }


                    } elseif (vp_getuser($the_user, "vr_plan", true) != "custome" && $lev == 2 && $the_user != "0" && $the_user != "false") {
                        $cur_indir_bonus = vp_getuser($the_user, "vp_tot_in_ref_earn", true); // amt of my indirect ref like me
                        $credit_indir_ref = intval($cur_indir_bonus) + intval($give_away); //cal total of bonus. current bal plus bonus
                        vp_updateuser($the_user, "vp_tot_in_ref_earn", $credit_indir_ref); //add bonus to my ref

                        //error_log("FOr level 2 with user id is $the_user = Earn is $cur_indir_bonus + $give_away = $credit_indir_ref",0);


                        $usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
                        if (isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL) {
                            $d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
                            global $wpdb;
                            $wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID' => $the_user));
                        } else {
                        }


                    } elseif (vp_getuser($the_user, "vr_plan", true) != "custome" && $lev == 3 && $the_user != "0" && $the_user != "false") {
                        $curr_indir_trans_bonus3 = vp_getuser($the_user, "vp_tot_in_ref_earn3", true);
                        $add_to_indirect_transb3 = intval($curr_indir_trans_bonus3) + intval($give_away);
                        vp_updateuser($the_user, "vp_tot_in_ref_earn3", $add_to_indirect_transb3);

                        $usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
                        if (isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL) {
                            $d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
                            global $wpdb;
                            $wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID' => $the_user));
                        } else {
                        }


                    } elseif (vp_getuser($the_user, "vr_plan", true) != "custome" && $lev != 3 && $lev != 2 && $lev != 1 && $the_user != "0" && $the_user != "false") {
                        $cur_indir_bonus3 = vp_getuser($the_user, "vp_tot_in_ref_earn3", true); // amt of my gg ref like me
                        $credit_indir_ref3 = intval($cur_indir_bonus3) + intval($give_away); //cal total of bonus. current bal plus bonus
                        vp_updateuser($the_user, "vp_tot_in_ref_earn3", $credit_indir_ref3); //add bonus to my gg

                        $usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
                        if (isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL) {
                            $d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
                            global $wpdb;
                            $wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID' => $the_user));
                        } else {
                        }

                    } else {
                        $lev = 90000000000;
                    }


                    $next_user = vp_getuser($the_user, "vp_who_ref", true);

                    $the_user = $next_user;

                }


            }

        }


        die('100');

    } else {
        die('101');

    }



}

/**
 * Handles user withdrawal requests.
 *
 * @param array $post_data POST data containing withdrawal details.
 * @return void
 */
function handle_withdrawal($post_data)
{
    global $current_timestamp, $option_array;

    $withdrawal_status = vp_getoption('allow_withdrawal');
    $withdrawal_to_bank = vp_getoption('allow_to_bank');

    $source = $_POST["source"];
    $current_withdrawal_balance = $_POST["withamt"];
    $withdrawal_amount = $_POST["withamt"];
    $id = get_current_user_id();
    $withdrawal_option = $_POST["withto"];


    if (preg_match("/-/", $withdrawal_amount)) {
        vp_block_user("Tried to withdraw with a negative amount!");
        die("Dont try negative balance");
    }

    if ($withdrawal_status != "yes") {
        die('{"status":"Withdrawal not enabled"}');
    }


    switch ($source) {
        case "bonus":
            if ($current_withdrawal_balance > $total_bal_with) {
                die('{"status":"400"}');
            } elseif ($current_withdrawal_balance < $total_bal_with) {
                die('{"status":"410"}');
            }

            break;
        case "wallet":
            if ($current_withdrawal_balance > $bal) {
                die('{"status":"400"}');
            } elseif (strtolower($withdrawal_option) == "wallet") {
                die('{"status":"450"}');
            }
            break;


    }

    $name = get_userdata($id)->user_login;


    $bankdetails = $_POST["bankdetails"];
    $date = date("Y-m-d h:i:s A", $current_timestamp);
    $phone = vp_getoption("vp_phone_line");
    $whatsapp = vp_getoption("vp_whatsapp");

    if ($withdrawal_option == "wallet" && strtolower($source) == "bonus") {


        $id = get_current_user_id();
        $get_total_withdraws = vp_getuser($id, "vp_tot_withdraws", true);
        $set_total_withdraws = $get_total_withdraws + 1;
        vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
        vp_updateuser($id, "vp_tot_ref_earn", 0);
        vp_updateuser($id, "vp_tot_in_ref_earn", 0);
        vp_updateuser($id, "vp_tot_in_ref_earn3", 0);

        vp_updateuser($id, "vp_tot_dir_trans", 0);
        vp_updateuser($id, "vp_tot_indir_trans", 0);
        vp_updateuser($id, "vp_tot_indir_trans3", 0);
        vp_updateuser($id, "vp_tot_trans_bonus", 0);

        $cal_wall_bal = floatval(vp_getuser($id, "vp_bal", true)) + floatval($withdrawal_amount);


        $before_amount = vp_getuser($id, "vp_bal", true);
        $now_amount = $cal_wall_bal;

        vp_updateuser($id, "vp_bal", $cal_wall_bal);

        $table_name = $wpdb->prefix . 'vp_wallet';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $name,
            'type' => "Withdrawal",
            'description' => "Bonus Withdrawal",
            'fund_amount' => $withdrawal_amount,
            'before_amount' => $before_amount,
            'now_amount' => $now_amount,
            'user_id' => $id,
            'status' => "Approved",
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));

        if (vp_getoption("email_withdrawal") == "yes") {
            $id = get_current_user_id();
            $name = get_userdata(get_current_user_id())->user_login;
            $subject = "[$name] - New Successful Withdrawal Notification ";
            $message = "$name has just made a successful withdrawal of $withdrawal_amount from CashBack-Wallet to Main-Wallet";
            vp_admin_email($subject, $message, "withdrawal");

        }

        die('{"status":"100"}');

    } elseif ($withdrawal_option == "bank" && strtolower($source) == "bonus") {

        if ($withdrawal_to_bank != "yes") {
            die('{"status":"Withdrawal to bank not enabled"}');
        }


        $id = get_current_user_id();
        $get_total_withdraws = vp_getuser($id, "vp_tot_withdraws", true);
        $set_total_withdraws = $get_total_withdraws + 1;
        vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
        vp_updateuser($id, "vp_tot_ref_earn", 0);
        vp_updateuser($id, "vp_tot_in_ref_earn", 0);
        vp_updateuser($id, "vp_tot_in_ref_earn3", 0);

        vp_updateuser($id, "vp_tot_dir_trans", 0);
        vp_updateuser($id, "vp_tot_indir_trans", 0);
        vp_updateuser($id, "vp_tot_indir_trans3", 0);
        vp_updateuser($id, "vp_tot_trans_bonus", 0);

        $dname = get_userdata($id)->user_login;
        $demail = get_userdata($id)->user_email;




        global $wpdb;
        $name = $dname;
        $description = $bankdetails;
        $amount = $withdrawal_amount;
        $status = 'Pending';
        $user_id = $id;
        $table_name = $wpdb->prefix . 'vp_withdrawal';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $name,
            'description' => $bankdetails,
            'amount' => $amount,
            'status' => $status,
            'user_id' => $id,
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));

        if (vp_getoption("email_withdrawal") == "yes") {

            $admin_email = vp_getoption("admin_email");
            wp_mail($admin_email, "New Withdrawal Request From ID[$id]", "A User With Id [$id] made a withdrawal request of $withdrawal_amount to [details--[$bankdetails]] on $date", $headers);

            $name = get_userdata(get_current_user_id())->user_login;
            $subject = "[$name] - New Withdrawal Notification ";
            $message = "$name has just made a withdrawal request!";
            //$link = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=history&subpage=withdrawal&type=pending&trans_id=4";
            vp_admin_email($subject, $message, "withdrawal");

        }

        die('{"status":"101"}');


    } elseif ($withdrawal_option == "bank" && strtolower($source) == "wallet") {

        if ($withdrawal_to_bank != "yes") {
            die('{"status":"Withdrawal to bank not enabled"}');
        }

        $id = get_current_user_id();
        $get_total_withdraws = vp_getuser($id, "vp_tot_withdraws", true);
        $set_total_withdraws = $get_total_withdraws + 1;
        vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
        $dname = get_userdata($id)->user_login;
        $demail = get_userdata($id)->user_email;

        $tot = $bal - $withdrawal_amount;
        vp_updateuser($id, "vp_bal", $tot);

        global $wpdb;
        $name = $dname;
        $description = $bankdetails;
        $amount = $withdrawal_amount;
        $status = 'Pending';
        $user_id = $id;
        $table_name = $wpdb->prefix . 'vp_withdrawal';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $name,
            'description' => $bankdetails,
            'amount' => $amount,
            'status' => $status,
            'user_id' => $id,
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));

        if (vp_getoption("email_withdrawal") == "yes") {


            $admin_email = vp_getoption("admin_email");
            wp_mail($admin_email, "New Withdrawal Request From ID[$id]", "New User With Id [$id] made a withdrawal request of $withdrawal_amount to [details--[$bankdetails]] on $date", $headers);

            $name = get_userdata(get_current_user_id())->user_login;
            $subject = "[$name] - New Withdrawal Notification ";
            $message = "$name has just made a withdrawal request!";
            vp_admin_email($subject, $message, "withdrawal");

        }
        die('{"status":"101"}');


    }



}

/**
 * Handles airtime to wallet or airtime to cash conversions.
 *
 * @param array $post_data POST data for conversion.
 * @return void
 */
function handle_airtime_conversion($post_data)
{
                    $vp_country = vp_country();
                $symbol = $vp_country["symbol"];
    global $wpdb, $current_timestamp;
    $conversion = $_POST["conversion"];
    $network = $_POST["network"];
    $payto = $_POST["pay_to"];
    $paycharge = $_POST["pay_charge"];
    $amount = $_POST["amount"];
    $get = $_POST["pay_get"];
    $from = $_POST["from"];

    $atw = vp_getoption("airtime_to_wallet");
    $atc = vp_getoption("airtime_to_cash");


    if (preg_match("/-/", $amount)) {
        vp_block_user("Tried A-T-C with a negative amount!");
        die("Dont try negative balance");
    }

    if ($conversion == "wallet") {

        if ($atw != "yes") {
            die("Airtime To Wallet Not Permitted");
        }
        $id = get_current_user_id();
        $name = get_userdata($id)->user_login;
        $description = "To Pay The Sum Of #$amount For #$get Conversion Rate Charged @ $paycharge% From $from";
        $fund_amount = $get;
        $before_amount = vp_getuser($id, "vp_bal", true);
        $now_amount = $before_amount + $get;


        $table_name = $wpdb->prefix . 'vp_wallet';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $name,
            'type' => "Airtime_To_Wallet",
            'description' => $description,
            'fund_amount' => $fund_amount,
            'before_amount' => $before_amount,
            'now_amount' => $now_amount,
            'user_id' => $id,
            'status' => "Pending",
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));


        $purchased = "Airtime To Wallet Conversion Of  $symbol$fund_amount";
        $recipient = $name;
        vp_transaction_email("NEW AIRTIME TO WALLET NOTIFICATION", "AIRTIME TO WALLET REQUEST LOGGED", 'nill', $purchased, $recipient, $fund_amount, $before_amount, $now_amount, true, false);


        die("100");
    }


    if ($conversion == "cash") {

        if ($atc != "yes") {
            die("Airtime To Cash Not Permitted");
        }

        $id = get_current_user_id();
        $name = get_userdata($id)->user_login;
        $bank = $_POST["bank"];
        $description = "To Pay The Sum Of #$amount For #$get Conversion Rate Charged @ $paycharge% From $from To Details $bank";
        $fund_amount = $get;
        $before_amount = vp_getuser($id, "vp_bal", true);
        $now_amount = "Not Applicable";



        $table_name = $wpdb->prefix . 'vp_wallet';
        $added_to_db = $wpdb->insert($table_name, array(
            'name' => $name,
            'type' => "Airtime_To_Cash",
            'description' => $description,
            'fund_amount' => $fund_amount,
            'before_amount' => $before_amount,
            'now_amount' => $now_amount,
            'user_id' => $id,
            'status' => "Pending",
            'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
        ));

        $purchased = "Airtime To Cash Conversion Of  $symbol$fund_amount";
        $recipient = "From $name To $bank";
        vp_transaction_email("NEW AIRTIME TO CASH NOTIFICATION", "AIRTIME TO CASH REQUEST LOGGED", 'nill', $purchased, $recipient, $fund_amount, $before_amount, $now_amount, true, false);


        die("100");
    }

}


function handle_setpin($post){
    $_POST = $post;
    	$id = get_current_user_id();
	$pin = $_POST["pin"];

	if (strlen($pin) >= 4) {
		$verify_pin = preg_match("/[^0-9]/", $pin);
		if ($verify_pin === 0) {
			$verify_zero = preg_match("/^0\d+$/", $pin);
			if ($verify_zero === 0) {
				vp_updateuser($id, "vp_pin", $pin);
				vp_updateuser($id, "vp_pin_set", "yes");

				$obj = new stdClass;
				$obj->code = "100";
				$obj->message = "Pin Set To $pin";
				die(json_encode($obj));
			} else {
				$obj = new stdClass;
				$obj->status = "200";
				$obj->message = "Do Not Start Your Pin With Zero";
				die(json_encode($obj));
			}
		} else {
			$obj = new stdClass;
			$obj->status = "200";
			$obj->message = "Only Numbers Are Allowed For Pin";
			die(json_encode($obj));
		}


	} else {
		$obj = new stdClass;
		$obj->status = "200";
		$obj->message = "Pin Must Be At Least 4 Digits";
		die(json_encode($obj));
	}


}

function handle_activation(){
    

	$datenow = date("Y-m-d h:i A", $current_timestamp);
	$next = date('Y-m-d h:i A', strtotime($datenow . " +12 hours"));

	if ($_POST['setactivation'] == "yea") {
		vp_updateoption('actkey', trim($_POST["actkey"]));
		vp_updateoption('vpid', trim($_POST["actid"]));
	} else {

		vp_updateoption('mlm', 'no');
		vp_updateoption('resell', 'no');
		vp_updateoption("showlicense", "hide");
		vp_updateoption('vprun', 'block');
		vp_updateoption('frmad', 'block');
		vp_updateoption("vp_security", "no");
		die('{"status":"200","message":"Activation Key Or Id Incorrect-> "}');


	}

	$actkey = trim($_POST["actkey"]);

	$id = trim($_POST['actid']);


	$http_args = array(
		'headers' => array(
			'cache-control' => 'no-cache',
			'content-type' => 'application/json',
			'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
		),
		'timeout' => 120,
		'sslverify' => false
	);

	$user_id = get_current_user_id();
	$email = get_userdata($user_id)->user_email;

	$url = 'https://vtupress.com/wp-content/plugins/vtuadmin/v11/?id=' . trim($_POST["actid"]) . '&actkey=' . trim($_POST["actkey"]) . '&url=' . get_site_url() . '&time=' . date("Y-m-d h:i:s") . '&phone=' . vp_getoption("vp_phone_line") . '&whatsapp=' . vp_getoption("vp_whatsapp") . '&plan=' . vp_getoption("major_plan") . '&w_group=' . vp_getoption("vp_whatsapp_group") . '&email=' . $email;


	$call = wp_remote_get($url);
	$response = wp_remote_retrieve_body($call);


	if (is_wp_error($call)) {

		$error = $call->get_error_message();

		$obj = new stdClass;
		$obj->status = "200";
		$obj->message = $error;
		$json = json_encode($obj);

		die($json);
	} else {

		$resp_code = wp_remote_retrieve_response_code($call);
		if ($resp_code != 200) {

			$obj = new stdClass;
			$obj->status = "200";
			$obj->message = "Error Code:[$resp_code ] \n Can't Get A Supported Feedback From VTUPRESS! \nTRY UPDATE YOUR PLUGIN OR CONTACT SUPPORT";
			$json = json_encode($obj);
			die($json);
		}

		$en = json_decode($response, true);
		//$str = implode(",",$en);


		//$_SERVER['SERVER_NAME'];

		if (!empty($response)) {
			$murl = "no";
			if (!empty($en["url"])) {

				if (is_numeric(stripos($en["url"], ","))) {
					$explode = explode(",", $en["url"]);
					foreach ($explode as $url) {
						if ($url == $_SERVER['HTTP_HOST'] || $url == get_site_url()) {
							$murl = "yes";
						}
					}
				} elseif (trim($en["url"]) == $_SERVER['HTTP_HOST'] || trim($en["url"]) == get_site_url()) {
					$murl = "yes";
				} else {
					$murl = "no";
				}


			} else {
				$murl = "no";
			}

			if (!empty($en["importers"])) {
				if (preg_match("/" . $_SERVER['HTTP_HOST'] . "/i", $en["importers"]) != 0 || strpos($en["importers"], $_SERVER['HTTP_HOST']) !== false || strpos($en["importers"], $_SERVER['SERVER_NAME']) !== false || strpos($en["importers"], vp_getoption('siteurl')) !== false || is_numeric(strpos($en["importers"], $_SERVER['HTTP_HOST'])) !== false || is_numeric(strpos($en["importers"], $_SERVER['SERVER_NAME'])) !== false) {
					$imp = "yes";
				} else {
					$imp = "Err No URL";
				}
			} else {
				$imp = "Err no importer";
			}


			if (isset($en["status"])) {
				//Go On
				if ($en["status"] == "update") {
					die("PLEASE UPDATE YOUR VTUPRESS PLUGIN BEFORE YOU ACTIVATE!!!");
				}
			} else {

				provider_header_handler($call);
				die('{"status":"200","message":"SEEMS VTUPRESS IS DOWN! CONTACT SUPPORT"}');
			}



			if (isset($en["actkey"]) && ($en["actkey"] == $_POST["actkey"])) {

				$status = $en["status"];

				$url = $en["url"];

				$plan = $en["plan"];

				$security = $en["security"];

				$siteUrl = get_site_url();
				vp_updateoption("siteurl", $siteUrl);



				if ($murl == "yes") {

					if ($status == "active") {

						if ($plan) {

							if ($security == "yes") {
								vp_updateoption("vp_security", "yes");
							} else {
								vp_updateoption("vp_security", "no");
							}
							/////////////////////////////////
							#Send buffer datas to the main db for activation of the two syntaxed info which are missing

							vp_updateoption("major_plan", $plan);
							if ($plan == "demo") {
								vp_updateoption('mlm', 'yes');
								vp_updateoption('vprun', 'none');
								vp_updateoption('frmad', 'none');
								vp_updateoption('resell', 'yes');
								vp_updateoption('vp_access_importer', 'yes');

								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "unlimited") {
								vp_updateoption('mlm', 'yes');
								vp_updateoption('resell', 'yes');
								vp_updateoption('vprun', 'none');
								vp_updateoption('frmad', 'none');
								if ($imp == "yes") {
									vp_updateoption('vp_access_importer', 'yes');
								} else {
									vp_updateoption('vp_access_importer', 'no');
								}
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "verified") {
								vp_updateoption('resell', 'yes');
								vp_updateoption('mlm', 'no');
								vp_updateoption('vprun', 'none');
								vp_updateoption('frmad', 'none');
								vp_updateoption('vp_access_importer', 'yes');
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "personal-y") {
								vp_updateoption('resell', 'no');
								vp_updateoption('mlm', 'no');
								vp_updateoption('vprun', 'none');
								vp_updateoption('frmad', 'none');
								vp_updateoption('vp_access_importer', 'yes');
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "premium-y") {
								vp_updateoption('mlm', 'yes');
								vp_updateoption('vprun', 'none');
								vp_updateoption('resell', 'yes');
								vp_updateoption('frmad', 'none');
								if ($imp == "yes") {
									vp_updateoption('vp_access_importer', 'yes');
								} else {
									vp_updateoption('vp_access_importer', 'no');
								}
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "premium") {
								vp_updateoption('mlm', 'yes');
								vp_updateoption('vprun', 'none');
								vp_updateoption('frmad', 'none');
								vp_updateoption('resell', 'yes');
								vp_updateoption('vp_access_importer', 'yes');
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} elseif ($plan == "personal") {
								vp_updateoption('mlm', 'no');
								vp_updateoption('vprun', 'none');
								vp_updateoption('resell', 'no');
								vp_updateoption('frmad', 'none');
								vp_updateoption('vp_access_importer', 'yes');
								vp_updateoption("vp_check_date", $next);
								die('{"status":"100"}');
							} else {
								vp_updateoption('vp_access_importer', 'no');
								vp_updateoption('mlm', 'no');
								vp_updateoption('resell', 'no');
								vp_updateoption("showlicense", "hide");
								vp_updateoption('vprun', 'block');
								vp_updateoption('frmad', 'block');
								vp_updateoption("vp_security", "no");
								die('{"status":"200","message":"Plan Not Found"}');
							}
							////////////////////////////
						}

					} else {
						vp_updateoption('mlm', 'no');
						vp_updateoption('resell', 'no');
						vp_updateoption("showlicense", "hide");
						vp_updateoption('vprun', 'block');
						vp_updateoption('frmad', 'block');
						vp_updateoption("vp_security", "no");
						die('{"status":"200","message":"Status Is ' . $status . '"}');
					}
				} else {
					vp_updateoption('mlm', 'no');
					vp_updateoption('resell', 'no');
					vp_updateoption("showlicense", "hide");
					vp_updateoption('vprun', 'block');
					vp_updateoption('frmad', 'block');
					vp_updateoption("vp_security", "no");
					vp_updateoption("vp_security", "no");
					die("{\"status\":\"200\",\"message\":\"URL Doesn\'t Match or is not contained in the Url Directory in vtupress official site\"}");
				}

			} else {
				vp_updateoption('mlm', 'no');
				vp_updateoption('resell', 'no');
				vp_updateoption("showlicense", "hide");
				vp_updateoption('vprun', 'block');
				vp_updateoption('frmad', 'block');
				vp_updateoption("vp_security", "no");
				die('{"status":"200","message":"Activation Key Or Id Incorrect"}');

			}

		} else {
			die('{"status":"200","message":"Empty String"}');

		}


	}

}