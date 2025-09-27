<?php
/**
 * Transaction processing functions for VTUPress vend operations.
 *
 * This file contains the core logic for handling different types of transactions
 * (airtime, data, cable, bills, SMS, betting).
 *
 * @package VTUPress
 * @subpackage Transactions
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

/**
 * Returns the specific columns and their formats for a given service table.
 * This is crucial for handling different database structures.
 *
 * @param string $trans_type The type of transaction (e.g., 'vtu', 'sme', 'cable', 'bill', 'sms', 'bet').
 * @param array $data An associative array of data to be inserted.
 * @return array An array containing 'columns' and 'formats'.
 */
function get_service_table_columns($trans_type, $data) {
    $columns = [];
    $formats = [];

    switch ($trans_type) {
        case 'vtu': // For sairtime table
        case 'share':
        case 'awuf':
            $columns = [
                'run_code'     => $data['run_code'],
                'response_id'  => $data['response_id'],
                'name'         => $data['name'],
                'email'        => $data['email'],
                'network'      => $data['network'],
                'phone'        => $data['phone'],
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'resp_log'     => $data['resp_log'],
                'browser'      => $data['browser'],
                'trans_type'   => $data['trans_type'],
                'trans_method' => $data['trans_method'],
                'via'          => $data['via'],
                'time_taken'   => $data['time_taken'],
                'request_id'   => $data['request_id'],
                'user_id'      => $data['user_id'],
                'status'       => $data['status'],
                'the_time'     => $data['the_time'],
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'
            ];
            break;

        case 'sme': // For sdata table
        case 'direct':
        case 'corporate':
        case 'smile':
        case 'alpha':
            $columns = [
                'run_code'     => $data['run_code'],
                'response_id'  => $data['response_id'],
                'name'         => $data['name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'plan'         => $data['plan'], // Specific to data
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'resp_log'     => $data['resp_log'],
                'browser'      => $data['browser'],
                'trans_type'   => $data['trans_type'],
                'trans_method' => $data['trans_method'],
                'via'          => $data['via'],
                'time_taken'   => $data['time_taken'],
                'request_id'   => $data['request_id'],
                'user_id'      => $data['user_id'],
                'status'       => $data['status'],
                'the_time'     => $data['the_time'],
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'
            ];
            break;

        case 'cable': // For scable table
            $columns = [
                'run_code'     => $data['run_code'],
                'response_id'  => $data['response_id'],
                'name'         => $data['name'],
                'email'        => $data['email'],
                'iucno'        => $data['iucno'], // Specific to cable
                'phone'        => $data['phone'],
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'resp_log'     => $data['resp_log'],
                'browser'      => $data['browser'],
                'trans_type'   => $data['trans_type'],
                'trans_method' => $data['trans_method'],
                'via'          => $data['via'],
                'time_taken'   => $data['time_taken'],
                'request_id'   => $data['request_id'],
                'product_id'   => $data['product_id'], // Specific to cable
                'type'         => $data['type'],       // Specific to cable (e.g., DStv, GOtv)
                'status'       => $data['status'],
                'user_id'      => $data['user_id'],
                'time'         => $data['the_time'], // Note: 'time' column name in old vs 'the_time'
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s'
            ];
            break;

        case 'bill': // For sbill table
            $columns = [
                'run_code'     => $data['run_code'],
                'response_id'  => $data['response_id'],
                'name'         => $data['name'],
                'email'        => $data['email'],
                'meterno'      => $data['meterno'],    // Specific to bill
                'phone'        => $data['phone'],
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'resp_log'     => $data['resp_log'],
                'browser'      => $data['browser'],
                'charge'       => $data['charge'],     // Specific to bill
                'trans_type'   => $data['trans_type'],
                'trans_method' => $data['trans_method'],
                'via'          => $data['via'],
                'time_taken'   => $data['time_taken'],
                'request_id'   => $data['request_id'],
                'user_id'      => $data['user_id'],
                'status'       => $data['status'],
                'product_id'   => $data['product_id'], // Specific to bill
                'meter_token'  => $data['meter_token'],// Specific to bill
                'type'         => $data['type'],       // Specific to bill (e.g., prepaid, postpaid)
                'time'         => $data['the_time'],   // Note: 'time' column name in old vs 'the_time'
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s',
                '%s', '%f', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s'
            ];
            break;

        case 'sms': // For ssms table
            $columns = [
                'name'         => $data['name'],
                'email'        => $data['email'],
                'sender'       => $data['sender'],   // Specific to SMS
                'receiver'     => $data['receiver'], // Specific to SMS
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'user_id'      => $data['user_id'],
                'status'       => $data['status'],
                'resp_log'     => $data['resp_log'],
                'the_time'     => $data['the_time'],
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%d', '%s', '%s', '%s'
            ];
            break;

        case 'bet': // For sbet table
            $columns = [
                'run_code'     => $data['run_code'],
                'response_id'  => $data['response_id'],
                'name'         => $data['name'],
                'email'        => $data['email'],
                'customerid'   => $data['customerid'], // Specific to betting
                'company'      => $data['company'],    // Specific to betting
                'bal_bf'       => $data['bal_bf'],
                'bal_nw'       => $data['bal_nw'],
                'amount'       => $data['amount'],
                'resp_log'     => $data['resp_log'],
                'browser'      => $data['browser'],
                'trans_type'   => $data['trans_type'],
                'trans_method' => $data['trans_method'],
                'via'          => $data['via'],
                'time_taken'   => $data['time_taken'],
                'request_id'   => $data['request_id'],
                'user_id'      => $data['user_id'],
                'status'       => $data['status'],
                'the_time'     => $data['the_time'],
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s',
                '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s'
            ];
            break;

        default:
            // Fallback for unknown transaction types (e.g., log to vp_transactions)
            $columns = [
                'status'     => $data['status'],
                'service'    => $data['service'] ?? 'unknown',
                'name'       => $data['name'],
                'email'      => $data['email'],
                'recipient'  => $data['recipient'],
                'bal_bf'     => $data['bal_bf'],
                'bal_nw'     => $data['bal_nw'],
                'amount'     => $data['amount'],
                'request_id' => $data['request_id'],
                'user_id'    => $data['user_id'],
                'the_time'   => $data['the_time'],
            ];
            $formats = [
                '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s', '%d', '%s'
            ];
            break;
    }

    return ['columns' => $columns, 'formats' => $formats];
}


/**
 * Common logic for pre-recording transaction and cookie management.
 * This function now also handles starting the database transaction and locking the user's row.
 *
 * @param int $user_id The ID of the current user.
 * @param float $bal The user's balance before the transaction.
 * @param float $amount The transaction amount.
 * @param string $uniqidvalue A unique request ID for the transaction.
 * @param string $name The user's login name.
 * @param string $email The user's email.
 * @param string $phone The recipient's phone number.
 * @param string $service The service type (e.g., 'sairtime', 'sdata').
 * @param float $baln The user's balance after the transaction (calculated).
 * @param string $pos A track code for duplicate checks.
 * @return bool True if pre-transaction checks pass and setup is complete, dies on failure.
 */
function pre_transaction_checks_and_setup($user_id, $bal, $amount, $uniqidvalue, $name, $email, $phone, $service, $baln, $pos) {
    global $wpdb, $current_timestamp;

    $table_trans = $wpdb->prefix . 'vp_transactions';

    // Check for duplicate transaction track code in the relevant service table
    // This assumes 'run_code' is a common column for duplicate checks across service tables.
    // The actual table for this check might need to be passed as a parameter or derived.
    // For now, let's assume 'sairtime' for this check as in vend-old.php.
    $tableh = $wpdb->prefix . $service; // This needs to be dynamic based on the actual service type
    $rest = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableh WHERE run_code = %s", $pos));
    if (!empty($rest)) {
        $wpdb->query('ROLLBACK'); // Rollback if duplicate
        die('[T/C] Duplicate Transaction!!! Check your transaction history please');
    }

    // Check for balance anomaly from previous transaction
    if ($bal == (isset($_COOKIE["last_bal"]) ? $_COOKIE["last_bal"] : null) && (isset($_COOKIE["trans_reversal"]) ? $_COOKIE["trans_reversal"] : 'yes') === "no") {
        $amtts = $bal - (isset($_COOKIE["recent_amount"]) ? round(floatval($_COOKIE["recent_amount"]), 2) : 0);
        update_wallet("Approved", "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated", $_COOKIE["recent_amount"], $bal, $amtts);
        vp_updateuser($user_id, "vp_bal", $amtts);
        $wpdb->query('COMMIT'); // Rollback if anomaly detected
        die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
    } else {
        setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");
        setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
    }

    // Pre-record transaction as failed (will be updated later) in vp_transactions
    $unrecorded_added = $wpdb->insert($table_trans, array(
        'status' => 'Fa',
        'service' => sanitize_text_field(substr($service, 1)),
        'name' => sanitize_user($name),
        'email' => sanitize_email($email),
        'recipient' => sanitize_text_field($phone),
        'bal_bf' => round(floatval($bal), 2),
        'bal_nw' => round(floatval($baln), 2),
        'amount' => round(floatval($amount), 2),
        'request_id' => sanitize_text_field($uniqidvalue),
        'user_id' => $user_id,
        'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
    ),
    array(
        '%s', '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%s', '%d', '%s'
    ));

    if (!is_numeric($unrecorded_added) || $unrecorded_added === 0 || $unrecorded_added === false) {
        $wpdb->query('ROLLBACK'); // Rollback if pre-recording fails
        die("Error Pre-recording: Please refresh your browser and try again later");
    }

    // Set cookies for session tracking
    setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
    setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
    setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
    setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
    setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
    setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
    setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
    setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
    setcookie("user_id", $user_id, time() + (30 * 24 * 60 * 60), "/");
    setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
    setcookie("the_time", date('Y-m-d h:i:s A', $current_timestamp), time() + (30 * 24 * 60 * 60), "/");
    setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/"); // Invalidate current run_code

    // Deduct balance within the transaction
    $tot = $bal - $amount;
    vp_updateuser($user_id, 'vp_bal', $tot);
    setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");

    return true;
}


/**
 * Common logic for post-transaction handling (logging, email, MLM, reversal).
 * This function commits the database transaction on success or pending status.
 *
 * @param string $pos A track code for duplicate checks.
 * @param string $vtu_token The VTU token/response ID from the API.
 * @param string $name User's login name.
 * @param string $email User's email.
 * @param string $network_name The network name.
 * @param string $phone The recipient phone number.
 * @param float $bal User's balance before transaction.
 * @param float $baln User's balance after transaction.
 * @param float $amount Transaction amount.
 * @param string $browser User's browser.
 * @param string $trans_type Type of transaction (e.g., 'vtu', 'sme', 'cable', 'bill', 'sms', 'bet').
 * @param string $trans_method Transaction method (e.g., 'get', 'post').
 * @param string $uniqidvalue Unique request ID.
 * @param int $user_id Current user ID.
 * @param string $status Final status ('Successful' or 'Pending').
 * @param string $response_log A snippet of the API response for logging.
 * @param float $realAmt Real amount charged to user.
 * @param string $service_table_name The name of the service-specific database table (e.g., 'sairtime', 'sdata').
 * @param string $trans_table_name The name of the unrecorded transactions table (e.g., 'vp_transactions').
 * @param string $add_total Whether to add to KYC total ('yes' or 'no').
 * @param float $tb4 KYC total before.
 * @param float $tnow KYC total now.
 * @param array $extra_data Additional data specific to the transaction type (e.g., plan, iucno, meterno).
 * @return void Dies with success/pending message.
 */
function post_transaction_handling($pos, $vtu_token, $name, $email, $network_name, $phone, $bal, $baln, $amount, $browser, $trans_type, $trans_method, $uniqidvalue, $user_id, $status, $response_log, $realAmt, $service_table_name, $trans_table_name, $add_total, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    try {
        // Add beneficiary
        $beneficiary = vp_getuser($user_id, "beneficiaries", true);
        if (!preg_match("/" . preg_quote($phone, '/') . "/", $beneficiary)) {
            vp_updateuser($user_id, "beneficiaries", $beneficiary . "," . $phone);
        }

        // Prepare common data for insertion
        $common_data = [
            'run_code'     => esc_html($pos),
            'response_id'  => esc_html($vtu_token),
            'name'         => sanitize_user($name),
            'email'        => sanitize_email($email),
            'phone'        => sanitize_text_field($phone),
            'bal_bf'       => round(floatval($bal), 2),
            'bal_nw'       => round(floatval($baln), 2),
            'amount'       => round(floatval($amount), 2),
            'resp_log'     => sanitize_text_field($response_log),
            'browser'      => sanitize_text_field($browser),
            'trans_type'   => sanitize_text_field($trans_type),
            'trans_method' => sanitize_text_field($trans_method),
            'via'          => 'site',
            'time_taken'   => '1',
            'request_id'   => sanitize_text_field($uniqidvalue),
            'user_id'      => $user_id,
            'status'       => sanitize_text_field($status),
            'the_time'     => date('Y-m-d h:i:s A', $current_timestamp),
            'network'      => sanitize_text_field($network_name), // Added network for airtime/data
        ];

        // Merge common data with extra_data
        $insert_data = array_merge($common_data, $extra_data);

        // Get specific columns and formats for the service table
        $table_info = get_service_table_columns($trans_type, $insert_data);
        $columns_to_insert = $table_info['columns'];
        $formats_to_insert = $table_info['formats'];

        // Insert into service-specific table
        $wpdb->insert($wpdb->prefix . $service_table_name, $columns_to_insert, $formats_to_insert);

        // Delete from unrecorded transactions table
        $wpdb->delete($wpdb->prefix . $trans_table_name, array('request_id' => $uniqidvalue));

        // Handle KYC total update
        if ($add_total === "yes") {
            vp_updateuser($user_id, "vp_kyc_total", (intval($tb4) + intval($tnow)));
        }

        // MLM hook
        if (is_plugin_active("vpmlm/vpmlm.php")) {
            do_action("vp_after");
        }

        // Reset reversal cookie
        setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
        setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");

        // Commit the transaction
        $wpdb->query('COMMIT');

        $status = strtolower($status);
        if ($status === "successful") {
            die("100");
        } elseif ($status === "pending") {
            die("processing");
        }
    } catch (Exception $e) {
        // If an error occurs during post-handling, attempt to rollback
        $wpdb->query('ROLLBACK');
        error_log("Transaction post-handling failed for user $user_id (Request ID: $uniqidvalue): " . $e->getMessage());
        die("An unexpected error occurred after transaction. Please contact support.");
    }
}

/**
 * Function to handle transaction failure and reversal.
 * This function rolls back the database transaction on failure.
 *
 * @param string $pos A track code for duplicate checks.
 * @param string $vtu_token The VTU token/response ID from the API.
 * @param string $name User's login name.
 * @param string $email User's email.
 * @param string $network_name The network name.
 * @param string $phone The recipient phone number.
 * @param float $bal User's balance before transaction.
 * @param float $baln User's balance after transaction.
 * @param float $amount Transaction amount.
 * @param string $browser User's browser.
 * @param string $trans_type Type of transaction (e.g., 'vtu', 'sme', 'cable', 'bill', 'sms', 'bet').
 * @param string $trans_method Transaction method (e.g., 'get', 'post').
 * @param string $uniqidvalue Unique request ID.
 * @param int $user_id Current user ID.
 * @param string $response_log A snippet of the API response for logging.
 * @param string $service_table_name The name of the service-specific database table.
 * @param string $trans_table_name The name of the unrecorded transactions table.
 * @param int|null $api_response_code HTTP response code from API call.
 * @param string $en_status Evaluation status ('TRUE', 'MAYBE', 'FALSE').
 * @param string $response_format Response format (e.g., 'JSON', 'TEXT').
 * @param array $extra_data Additional data specific to the transaction type.
 * @return void Dies with error message.
 */
function handle_transaction_failure($pos, $vtu_token, $name, $email, $network_name, $phone, $bal, $baln, $amount, $browser, $trans_type, $trans_method, $uniqidvalue, $user_id, $response_log, $service_table_name, $trans_table_name, $api_response_code, $en_status, $response_format, $extra_data = []) {
    global $wpdb, $current_timestamp;

    try {
        $refund = strtolower(vp_getoption('auto_refund'));
        if($refund == "yes"){
            $baln = $bal;
        }
        // Prepare common data for insertion
        $common_data = [
            'run_code'     => esc_html($pos),
            'response_id'  => esc_html($vtu_token),
            'name'         => sanitize_user($name),
            'email'        => sanitize_email($email),
            'phone'        => sanitize_text_field($phone),
            'bal_bf'       => round(floatval($bal), 2),
            'bal_nw'       => round(floatval($baln), 2), // Balance remains same on failure before reversal
            'amount'       => round(floatval($amount), 2),
            'resp_log'     => sanitize_text_field($response_log),
            'browser'      => sanitize_text_field($browser),
            'trans_type'   => sanitize_text_field($trans_type),
            'trans_method' => sanitize_text_field($trans_method),
            'via'          => 'site',
            'time_taken'   => '1',
            'request_id'   => sanitize_text_field($uniqidvalue),
            'user_id'      => $user_id,
            'status'       => "Failed",
            'the_time'     => date('Y-m-d h:i:s A', $current_timestamp),
            'network'      => sanitize_text_field($network_name), // Added network for airtime/data
        ];

        // Merge common data with extra_data
        $insert_data = array_merge($common_data, $extra_data);

        // Get specific columns and formats for the service table
        $table_info = get_service_table_columns($trans_type, $insert_data);
        $columns_to_insert = $table_info['columns'];
        $formats_to_insert = $table_info['formats'];

        // Log failed transaction to service-specific table
        $inserted = $wpdb->insert($wpdb->prefix . $service_table_name, $columns_to_insert, $formats_to_insert);
        if ($inserted === false) {
            // Optional: also log query for debugging
            error_log("DB Insert failed: " . $wpdb->last_error . " | Query: " . $wpdb->last_query);
            die("Database insert failed: " . esc_html($wpdb->last_error));
        }

        // Add beneficiary
        $beneficiary = vp_getuser($user_id, "beneficiaries", true);
        if (!preg_match("/" . preg_quote($phone, '/') . "/", $beneficiary)) {
            vp_updateuser($user_id, "beneficiaries", $beneficiary . "," . $phone);
        }

        
        if($refund == "yes"){
            // Revert balance
            vp_updateuser($user_id, "vp_bal", $bal);
            setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
            // Update wallet with reversal entry
            update_wallet("Approved", "Reversal For Failed " . ucfirst($trans_type) . " Purchase With Id " . $uniqidvalue, $amount, $baln, $bal);
        }else{
            vp_updateuser($user_id, "vp_bal", $baln);
            setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
        }


        // Delete from unrecorded transactions table
        $wpdb->delete($wpdb->prefix . $trans_table_name, array('request_id' => $uniqidvalue));
        
        // Rollback the transaction
        $wpdb->query('COMMIT');

        // Die with error message
        $obj = new stdClass;
        $obj->status = "200";
        $obj->response = $response_log;
        $obj->response_code = $api_response_code;
        $obj->EN = $en_status;
        $obj->response_format = $response_format;
        die(json_encode($obj));

    } catch (Exception $e) {
        // If an error occurs during failure handling, log and die
        error_log("Transaction failure handling failed for user $user_id (Request ID: $uniqidvalue): " . $e->getMessage());
        die("An unexpected error occurred during transaction failure. Please contact support.");
    }
}


/**
 * Processes a transaction based on the provided transaction code.
 *
 * @param string $tcode The transaction code (e.g., 'cair', 'cdat').
 * @param array $post_data All POST data from the request.
 * @param int $user_id Current user ID.
 * @param string $name User's login name.
 * @param string $email User's email.
 * @param string $phone Recipient phone number.
 * @param string $network Network name.
 * @param string $url API URL.
 * @param string $uniqidvalue Unique request ID.
 * @param float $bal User's balance before transaction.
 * @param float $baln User's balance after transaction.
 * @param float $amount Transaction amount.
 * @param float $realAmt Real amount charged to user.
 * @param string $browser User's browser.
 * @param array $option_array Global options array.
 * @param string $add_total Whether to add to KYC total ('yes' or 'no').
 * @param float $tb4 KYC total before.
 * @param float $tnow KYC total now.
 * @return void
 */
function dbFromTcode($data=""){
        global $wpdb, $current_timestamp;
        switch($data){
            case"cair":
                return "sairtime";
            break;
            case"cdat":
                return "sdata";
            break;
            case"ccab":
                return "scable";
            break;
            case"cbill":
                return "sbill";
            break;
            case"cepin":
                return "sepins";
            break;
            case"csms":
                return "ssms";
            break;
            case"cbet":
                return "sbet";
            break;
            default:
                $wpdb->query('ROLLBACK');
                die("Invalid transaction code.");
            break;

        }
}
function process_transaction($tcode, $post_data, $user_id, $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount, $realAmt, $browser, $option_array, $add_total, $tb4, $tnow) {
    global $wpdb, $current_timestamp;


    $pos = sanitize_text_field($post_data["run_code"] ?? uniqid('run_')); // Track code for duplicate checks

    // Pre-transaction checks and setup (deduct balance, set cookies, log unrecorded)
    // This function now assumes the transaction and lock have already been started by vend_init()
    // It will perform the duplicate checks and pre-record the transaction.
    pre_transaction_checks_and_setup($user_id, $bal, $amount, $uniqidvalue, $name, $email, $phone, dbFromTcode($tcode), $baln, $pos);

    $service_table = '';
    $trans_type_for_db = ''; // This will store the specific type for the database column
    $extra_data = []; // To hold service-specific columns

    switch ($tcode) {
        case "cair": // Airtime
            $airtime_choice = sanitize_text_field($post_data['airtimechoice'] ?? '');
            $network_name = sanitize_text_field($post_data["network_name"] ?? '');
            $service_table = 'sairtime';
            $trans_type_for_db = $airtime_choice; // 'vtu', 'share', 'awuf'

            $payload_type = '';
            $var = "";

            if($airtime_choice == 'share'){
                $payload_type = 's';
                $var = $payload_type;
            }
            elseif($airtime_choice == 'awuf'){
                $payload_type = 'w';
                $var = $payload_type;
            }

            // Data specific to airtime transaction
            $extra_data = [
                'network' => $network_name,
            ];

            // Determine API options based on airtime choice
            $request_method = vp_getoption($payload_type . "airtimerequest");
            $api_url_option = $payload_type . "airtimebaseurl";
            $api_endpoint_option = $payload_type . "airtimeendpoint";
            $success_code_option = $payload_type . "airtimesuccesscode";
            $response_format_option = "airtime" . ($airtime_choice == "vtu" ? "1" : ($airtime_choice == "share" ? "2" : "3")) . "_response_format";
            $success_value_option = $payload_type . "airtimesuccessvalue";
            $success_value2_option = $payload_type . "airtimesuccessvalue2";
            $response_id_option = $airtime_choice . "response_id";
            $query_method_option = $airtime_choice . "querymethod";
            $post_data_map = [];
            for ($i = 1; $i <= 5; $i++) {
                $post_data_map[$payload_type . 'airtimepostdata' . $i] = $payload_type . 'airtimepostvalue' . $i;
            }
            $attribute_map = [
                'network' => $payload_type . 'airtimenetworkattribute',
                'amount' => $payload_type . 'airtimeamountattribute',
                'phone' => $payload_type . 'airtimephoneattribute',
                'request_id' => ($airtime_choice == "vtu" ? "arequest_id" : ($airtime_choice == "share" ? "sarequest_id" : "warequest_id"))
            ];
            $header_map = [
                'head_option' => 'airtime_head' . ($airtime_choice == "vtu" ? "" : ($airtime_choice == "share" ? "2" : "3")),
                'head1' => $payload_type . 'airtimehead1',
                'value1' => $payload_type . 'airtimevalue1'
            ];
            $add_headers_prefix = $airtime_choice . 'addheaders';
            $add_value_prefix = $airtime_choice . 'addvalue';

            handle_airtime_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
        break;

        case "cdat": // Data
            $datatcode = sanitize_text_field($post_data['datatcode'] ?? '');
            $service_table = 'sdata';
            $trans_type_for_db = $datatcode; // 'sme', 'direct', 'corporate', 'smile', 'alpha'
            $payload_type = '';
            $var = "c";

            if($datatcode == 'corporate'){
                $payload_type = 'r2';
                $var = $payload_type."c";
            }
            elseif($datatcode == 'direct'){
                $payload_type = 'r';
                $var = $payload_type."c";
            }
            elseif($datatcode == 'smile'){
                $payload_type = 'smile';
                $var = 'smile';
            }
            elseif($datatcode == 'alpha'){
                $payload_type = 'alpha';
                $var = 'alpha';
            }

            // Data specific to data transaction
            $extra_data = [
                'plan' => sanitize_text_field($post_data["data_plan"] ?? '') . " With - ID " . sanitize_text_field($post_data['cplan'] ?? ''),
            ];

            // Determine API options based on data type
            $request_method = vp_getoption($payload_type . "datarequest");
            $api_url_option = $datatcode =="smile" ? "smilebaseurl" : ($datatcode =="alpha" ? "alphabaseurl" : $payload_type . "databaseurl");

            // Define mappings once
            $endpointMap = [
                "smile" => "smileendpoint",
                "alpha" => "alphaendpoint",
            ];

            $successCodeMap = [
                "smile" => "smilesuccesscode",
                "alpha" => "alphasuccesscode",
            ];

            $responseFormatMap = [
                "sme"       => "data1",
                "direct"    => "data2",
                "corporate" => "data3",
                "smile"     => "smile1",
                "alpha"     => "alpha1",
            ];

            $successValueMap = [
                "smile" => "smilesuccessvalue",
                "alpha" => "alphasuccessvalue",
            ];

            $successValue2Map = [
                "smile" => "smilesuccessvalue2",
                "alpha" => "alphasuccessvalue2",
            ];

            // Use fallback with null coalescing (??)
            $api_endpoint_option      = $endpointMap[$datatcode]     ?? $payload_type . "dataendpoint";
            $success_code_option      = $successCodeMap[$datatcode]  ?? $payload_type . "datasuccesscode";
            $response_format_option   = ($responseFormatMap[$datatcode] ?? $payload_type . "data") . "_response_format";
            $success_value_option     = $successValueMap[$datatcode] ?? $payload_type . "datasuccessvalue";
            $success_value2_option    = $successValue2Map[$datatcode]?? $payload_type . "datasuccessvalue2";
            $response_id_option       = $payload_type . "response_id";
            $query_method_option      = $datatcode . "querymethod";

            // Post data/value mapping loop
            $post_data_map = [];
            for ($i = 1; $i <= 5; $i++) {
                if ($datatcode === "smile") {
                    $pind = "smilepostdata$i";
                    $vind = "smilepostvalue$i";
                } elseif ($datatcode === "alpha") {
                    $pind = "alphapostdata$i";
                    $vind = "alphapostvalue$i";
                } else {
                    $pind = $payload_type . "datapostdata$i";
                    $vind = $payload_type . "datapostvalue$i";
                }

                $post_data_map[$pind] = $vind;
            }

            $attrsp = in_array($datatcode, ["smile", "alpha"], true)
                ? $datatcode
                : $payload_type . "data";

            $attribute_map = [
                'network' => $attrsp . 'networkattribute',
                'amount' => $attrsp . 'amountattribute',
                'phone' => $attrsp . 'phoneattribute',
                'plan' => $var.'variationattr',
                'request_id' => $payload_type .'request_id'
            ];

            $header_map = [
                'head_option' => 'data_head' . ($datatcode == "sme" ? "" : ($datatcode == "direct" ? "2" : ($datatcode == "corporate" ? "3" : ($datatcode == "smile" ? "4" : "5")))),
                'head1' => $payload_type . 'datahead1',
                'value1' => $payload_type . 'datavalue1'
            ];

            $add_headers_prefix = $datatcode . 'addheaders';
            $add_value_prefix = $datatcode . 'addvalue';

            $network = ($_POST["network"]) ? sanitize_text_field($_POST["network"]) : "";

            if(empty($network)){
                die("No Network");
            }

            handle_data_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
        break;

        case "ccab": // Cable
            $ccable = sanitize_text_field($post_data["ccable"] ?? '');
            $iuc = sanitize_text_field($post_data["iuc"] ?? '');
            $cabtype = sanitize_text_field($post_data["cabtype"] ?? '');
            $service_table = 'scable';
            $trans_type_for_db = 'cable';

            // Data specific to cable transaction
            $extra_data = [
                'iucno'      => $iuc,
                'product_id' => $ccable,
                'type'       => $cabtype,
            ];

            // Determine API options for cable
            $request_method = vp_getoption("cablerequest");
            $api_url_option = "cablebaseurl";
            $api_endpoint_option = "cableendpoint";
            $success_code_option = "cablesuccesscode";
            $response_format_option = "cable_response_format";
            $success_value_option = "cablesuccessvalue";
            $success_value2_option = "cablesuccessvalue2";
            $response_id_option = "cableresponse_id";
            $query_method_option = "cablequerymethod";
            $post_data_map = [];
            for ($i = 1; $i <= 5; $i++) {
                $post_data_map['cablepostdata' . $i] = 'cablepostvalue' . $i;
            }
            $attribute_map = [
                'iucno' => 'ciucattr',
                'amount' => 'cableamountattribute',
                'phone' => 'ciucattr',
                'product_id' => 'ccvariationattr',
                'type' => 'ctypeattr',
                'request_id' => 'crequest_id'
            ];
            $header_map = [
                'head_option' => 'cable_head',
                'head1' => 'cablehead1',
                'value1' => 'cablevalue1'
            ];
            $add_headers_prefix = 'cableaddheaders';
            $add_value_prefix = 'cableaddvalue';

            handle_cable_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
            break;

        case "cbill": // Bill Payment
            $cbill = sanitize_text_field($post_data["cbill"] ?? '');
            $type = sanitize_text_field($post_data["type"] ?? ''); // prepaid/postpaid
            $meterno = sanitize_text_field($post_data["meterno"] ?? '');
            $service_table = 'sbill';
            $trans_type_for_db = 'bill';

            // Data specific to bill transaction
            $extra_data = [
                'meterno'     => $meterno,
                'product_id'  => $cbill,
                'type'        => $type,
                'charge'      => floatval(vp_option_array($option_array, "bill_charge")),
                'meter_token' => sanitize_text_field($post_data["meter_token"] ?? 'No Record'), // Assuming meter_token might be passed
            ];

            // Determine API options for bill
            $request_method = vp_getoption("billrequest");
            $api_url_option = "billbaseurl";
            $api_endpoint_option = "billendpoint";
            $success_code_option = "billsuccesscode";
            $response_format_option = "bill_response_format";
            $success_value_option = "billsuccessvalue";
            $success_value2_option = "billsuccessvalue2";
            $response_id_option = "billresponse_id";
            $query_method_option = "billquerymethod";
            $post_data_map = [];
            for ($i = 1; $i <= 5; $i++) {
                $post_data_map['billpostdata' . $i] = 'billpostvalue' . $i;
            }
            $attribute_map = [
                'meterno' => 'cmeterattr',
                'amount' => 'billamountattribute',
                'phone' => 'cmeterattr',
                'product_id' => 'cbvariationattr',
                'type' => 'btypeattr',
                'request_id' => 'brequest_id'
            ];
            $header_map = [
                'head_option' => 'bill_head',
                'head1' => 'billhead1',
                'value1' => 'billvalue1'
            ];
            $add_headers_prefix = 'billaddheaders';
            $add_value_prefix = 'billaddvalue';

            handle_bill_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
            break;

        case "cepin": // E-PIN (No specific handler provided, falls through to default if not handled here)
            // This case needs a dedicated handle_epin_transaction function if it interacts with an API
            // For now, it will proceed to post_transaction_handling directly if no API call is needed.
            $service_table = 'sepins'; // Assuming a table for epins
            $trans_type_for_db = 'epin';
            // No extra_data for now, but can be added if needed
            // If API call is needed, create handle_epin_transaction and call it here.
            post_transaction_handling(
                $pos, 'Nill', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                'N/A', $uniqidvalue, $user_id, 'E-PIN processed locally or no API response', $realAmt, $service_table,
                $service_table, $add_total, $tb4, $tnow, $extra_data
            );
            break;

        case "csms": // SMS
            $sender = sanitize_text_field($post_data["sender"] ?? '');
            $receiver = sanitize_text_field($post_data["receiver"] ?? '');
            $theMessage = sanitize_textarea_field($post_data["message"] ?? '');
            $service_table = 'ssms';
            $trans_type_for_db = 'sms';

            // Data specific to SMS transaction
            $extra_data = [
                'sender'   => $sender,
                'receiver' => $receiver,
                'resp_log' => $theMessage, // SMS message itself can be part of log
            ];

            // Spam word check (from vend-old.php)
            $spamWords = <<<EOD
UBA, CBN, Stanbic, Ibtc, C.B.N, BVN, B.V.N, Jaiz, gtbank, Diamond, B V N, C B N, B@nk, Fidelity, 27242, Quickteller, ATM, A.T.M, A T M, Paypal, Polaris, Cash, F c m b, FIDELITY, BVN, polaris, gtb, g.t.b, bank, KEYSTONE NPF, Police, Custom, Army, Airforce, Naval, NCS, US ARMY, Nig Custom, Millitary, USA, SSS, S. S. S, Mopol, EFCC, ikeja electric, AEDC, BEDC, EKEDC, EEDC, IBEDC, IKEDC, JEDC, KNEDC, KEDC, PHEDC, YEDC, NNPC, Chevron, ExxonMobil, N.N.P.C, N. N. P. C, JAMB, 55019, Admin, Administrator, Info, @, /, _, EEDC, Gotv, DSTV, Startimes, Ikeja Electric, Jumia, Konga, PDP, PROMO, WADA, DIAMOND, CONGRATULATION, congrats, apc, pdp, vote, code, google, central, fidelity, million, Zen-ith, Zenith, facebook, Integrity, M"T N N, 1.8.O, M"TN-N, M"T N N., M"T N-N.?, M"T N?, M"TN N., M T"N-NG, M T"N-N., M T"N-N, M T N, M .T N N?, M "T N N., M"T N N., M.T`N, M"TN-N, M.T`N,M.T`N GN, M.T`N-.NG, MT N,MTN, MTEL, M-T-N, -M-T-N-, MTN N, MTN NG, MTN-NG, YELLOLTD,YELLO, YELL0, mtn, 1.8.O, Recharge ,4I00 , A!rtel, AIRTEL, ALERT , ANGEL, MEZZY , AWOOF!!!, BankAccess, BANKM3py, Bumazek, C0NGRATS! , C0NGRTS, C0NGRTS , Y0U , cantv.net , Cards-Zone, Card-Zone, CLAIM, Coca-Cola , coinmac.net, CONGRAT, Congrats!, Congratulation, CONGRATULATIONS, CouplesOut, DIAMOND ,ETISALAT, Euro, Casino, Gl0lwinner, GLOGlo ,Promo! , GLOBACOM, Glowinner, GreatNews!, gsm promo, HSEFELOSHIP, http://www.permanenttsb, updates.org, http://www.yellopins.com ,info@nmobiledraw.org ,nfonmobiledraw.org, ISAMS YABA, Jemtrade , LIVINGWORD, lo1O10, LOADED, lottery , ottery, lotto, Maitap.be, MANSARD, L21, Megateq, mlottery@usa.com, mlttryusa@w.cn, mobile, promo, MT N,MTEL, MTN ,M-T-N , -M-T-N-, MTN N, MTN NG, MTN-NG, N0K, N10M, N2Million, nexteldraws@live.co.uk, NKM, BILE, NOK, Nokia inc., nokia promo, nokialondondept247@hotmail.co.uk, NOKlA UK, NTM, ORLAJ, P C EBUNILO , PR1ZE=0FFER, PRIMEGROCER, PROMO, reward, Rewarded, ROSGLORIOSM, Service180, Spam, StanbicIBTC , L SEGUN , SWFT , W1N, W0N, VISAFONE, VIP-CARD, vadia, usa.mlty@w.cn, ULTIMATE, TOYOTA, sweepstakes, kids, telsms@live.com, takko, SWIFTNG ,Swift_NG ,SWIFT 4G, wbre@gala.net , wbre2@gala.net , WIN, ZOOM, MULTILINKS, Your-Line, Your Number Have Win , been selected , number has, your mobile has been selected , wo n , won , YDD Welfare, Your Number Have Win, Your-Line, ZOOM MULTILINKS, Lumos , lacasera , smsalert, Alert , HSBC, Gionee, StanChart, Inform , Bulk SMS, BulkSMS, Singlsrally, NOBLE KIT SERVE, NOBLE KIT SER, Emma LovingYOU, Me4u, foryou, RICH-PRINCE, RichPrince, Yinkuccc, TheFle, Demoj, SWEETHEART MINISTRY, MINISTRY MzPretty, SirJTelecom, RMA Team, Good News, BOTLINK, Mama abuja, Oganihu, KAFA, Singlsrally, JesusFamily, MFB, m f b, m.f.b, m. f. b., Flutterwave, ALERT , Activation, Activation, social media, sup, Embassy, Grant, SEM, SEM Grant, Telpecon G, Telpecon , PAGA, account, Promo, PROMOTIONAL, CONGRATS, CONGRATULATIONS, PRIZE, YELLO, VOTE, APC
EOD;
            $first = preg_replace('/,\\s+/', ",", $spamWords);
            $second = preg_replace('/\s+,/', ",", $first);
            $spamWordsArray = explode(",", $second);
            global $theWord; // This global is used in containsSpam

            if (containsSpam($theMessage, $spamWordsArray) && preg_match('/cliqsms/', vp_getoption("smsbaseurl"))) {
                $wpdb->query('ROLLBACK');
                die("[" . $theWord . "] is filtered, replace with another word");
            }

            // Determine API options for SMS
            $request_method = vp_getoption("smsrequest");
            $api_url_option = "smsbaseurl";
            $api_endpoint_option = "smsendpoint";
            $success_code_option = "smssuccesscode";
            $response_format_option = "sms_response_format";
            $success_value_option = "smssuccessvalue";
            $success_value2_option = "smssuccessvalue2";
            $response_id_option = "smsresponse_id";
            $query_method_option = "smsquerymethod";
            $post_data_map = [
                'smspostdata1' => 'smspostvalue1',
                'smspostdata2' => 'smspostvalue2',
            ];
            $attribute_map = [
                'sender' => 'smssenderattribute',
                'receiver' => 'smsreceiverattribute',
                'message' => 'smsmessageattribute',
                'request_id' => 'smsrequest_id'
            ];
            $header_map = [
                'head_option' => 'sms_head',
                'head1' => 'smshead1',
                'value1' => 'smsvalue1'
            ];
            $add_headers_prefix = 'smsaddheaders';
            $add_value_prefix = 'smsaddvalue';

            handle_sms_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
            break;

        case "cbet": // Betting
            $customerid = sanitize_text_field($post_data["customerid"] ?? ''); // Assuming phone is customer ID
            $company = sanitize_text_field($post_data["bet_company"] ?? ''); // Assuming network is company
            $service_table = 'sbet';
            $trans_type_for_db = 'bet';

            // Data specific to betting transaction
            $extra_data = [
                'customerid' => $customerid,
                'company'    => $company,
            ];

            // Determine API options for betting
            $request_method = vp_getoption("betrequest");
            $api_url_option = "betbaseurl";
            $api_endpoint_option = "betendpoint";
            $success_code_option = "betsuccesscode";
            $response_format_option = "bet1_response_format";
            $success_value_option = "betsuccessvalue";
            $success_value2_option = "betsuccessvalue2";
            $response_id_option = "betresponse_id";
            $query_method_option = "betquerymethod";
            $post_data_map = [
                'betpostdata1' => 'betpostvalue1',
                'betpostdata2' => 'betpostvalue2',
            ];
            $attribute_map = [
                'customerid' => 'betcustomeridattribute',
                'amount' => 'betamountattribute',
                'company' => 'betcompanyattribute',
                'request_id' => 'betrequest_id'
            ];
            $header_map = [
                'head_option' => 'bet_head',
                'head1' => 'bethead1',
                'value1' => 'betvalue1'
            ];
            $add_headers_prefix = 'betaddheaders';
            $add_value_prefix = 'betaddvalue';

            handle_bet_transaction(
                $request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                $response_format_option, $success_value_option, $success_value2_option,
                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                $name, $email, $phone, $network, $url, $uniqidvalue, $bal, $baln, $amount,
                $realAmt, $browser, $option_array, $service_table, 'vp_transactions', $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data
            );
            break;

        default:
            $wpdb->query('ROLLBACK');
            die("Invalid transaction code.");
        break;
    }
}

/**
 * Handles airtime transactions.
 *
 * @param string $request_method
 * @param string $api_url_option
 * @param string $api_endpoint_option
 * @param string $success_code_option
 * @param string $response_format_option
 * @param string $success_value_option
 * @param string $success_value2_option
 * @param string $response_id_option
 * @param string $query_method_option
 * @param array $post_data_map
 * @param array $attribute_map
 * @param array $header_map
 * @param string $add_headers_prefix
 * @param string $add_value_prefix
 * @param array $post_data
 * @param int $user_id
 * @param string $name
 * @param string $email
 * @param string $phone
 * @param string $network
 * @param string $url_from_post (original URL from $_POST)
 * @param string $uniqidvalue
 * @param float $bal
 * @param float $baln
 * @param float $amount
 * @param float $realAmt
 * @param string $browser
 * @param array $option_array
 * @param string $service_table_name
 * @param string $trans_table_name
 * @param string $add_total
 * @param string $pos
 * @param string $trans_type_for_db
 * @param float $tb4 KYC total before.
 * @param float $tnow KYC total now.
 * @param array $extra_data Additional data specific to the transaction type.
 * @return void
 */
function handle_airtime_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                    $response_format_option, $success_value_option, $success_value2_option,
                                    $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                    $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                    $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                    $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    // Add dynamic attributes
    if (isset($attribute_map['network'])) $datass[vp_option_array($option_array, $attribute_map['network'])] = $network;
    if (isset($attribute_map['amount'])) $datass[vp_option_array($option_array, $attribute_map['amount'])] = round(floatval($realAmt), 2);
    if (isset($attribute_map['phone'])) $datass[vp_option_array($option_array, $attribute_map['phone'])] = $phone;
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';
    // die($request_method);

    if ($query_method != "array") { // Direct GET/POST request
        if ($request_method == "get") {
            $final_url = add_query_arg($datass, $url); // Append data to URL for GET
            // echo $final_url;
            // echo "\n";
            // echo $http_args;
            // die();
            $call = wp_remote_get($final_url, $http_args);
        } else { // post
            $http_args['body'] = json_encode($datass);
            $call = wp_remote_post($url, $http_args);
        }
        $response = wp_remote_retrieve_body($call);
    } else { // Array method (using vp_remote_post_fn)
        // This part needs vp_remote_post_fn to be correctly defined and handle array bodies
        // Assuming vp_remote_post_fn exists and returns the response body or "error"
        $response = vp_remote_post_fn($url, $headers_array, $datass);
        if ($response == "error") {
            global $return_message; // Assuming vp_remote_post_fn sets this global
            handle_transaction_failure(
                $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
            );
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $vtu_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $vtu_token = $response_token_data[0];
    }

    $log_response_snippet = harray_key_first($response); // Assuming harray_key_first is defined in helpers

    if ($en_status == "TRUE" || $response === $success_value) {
        $purchased_message = "Purchased {" . strtoupper($trans_type_for_db) . " AIRTIME} worth ₦" . number_format($realAmt, 2);
        weblinkBlast($phone, $purchased_message);
        vp_transaction_email("NEW AIRTIME NOTIFICATION", "SUCCESSFUL AIRTIME PURCHASE", $uniqidvalue, $purchased_message, $phone, $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $vtu_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id,"Successful", $log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $vtu_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id,"pending", $log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        ); // Status will be "Pending"
    } else {
        handle_transaction_failure(
            $pos, $vtu_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}

/**
 * Handles data transactions.
 * (Similar structure to handle_airtime_transaction, but with data-specific parameters)
 */
function handle_data_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                 $response_format_option, $success_value_option, $success_value2_option,
                                 $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                 $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                 $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                 $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    // Add dynamic attributes specific to data
    if (isset($attribute_map['network'])) $datass[vp_option_array($option_array, $attribute_map['network'])] = $network;
    if (isset($attribute_map['amount'])) $datass[vp_option_array($option_array, $attribute_map['amount'])] = round(floatval($amount), 2);
    if (isset($attribute_map['phone'])) $datass[vp_option_array($option_array, $attribute_map['phone'])] = $phone;
    if (isset($attribute_map['plan'])) $datass[vp_option_array($option_array, $attribute_map['plan'])] = sanitize_text_field($post_data['cplan'] ?? '');
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';
    $force_success = false; // For hollatag logic


    if (!$force_success) { // Only make API call if not forced by hollatag
        if ($query_method != "array") {
            if ($request_method == "get") {
                $final_url = add_query_arg($datass, $url);
                $call = wp_remote_get($final_url, $http_args);
            } else { // post
                $http_args['body'] = json_encode($datass);
                $call = wp_remote_post($url, $http_args);
            }
            $response = wp_remote_retrieve_body($call);
        } else {
            $response = vp_remote_post_fn($url, $headers_array, $datass);
            if ($response == "error") {
                global $return_message;
                handle_transaction_failure(
                    $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                    $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                    wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
                );
            }
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $data_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $data_token = $response_token_data[0];
    }

    $log_response_snippet = harray_key_first($response);

    if ($en_status == "TRUE" || $response === $success_value || $force_success) {
        $plan_name_for_email = sanitize_text_field($post_data["data_plan"] ?? '');
        $purchased_message = "Purchased {" . strtoupper($trans_type_for_db) . " DATA --[ " . $plan_name_for_email . " ]-- }";
        weblinkBlast($phone, $purchased_message);
        vp_transaction_email("NEW DATA NOTIFICATION", "SUCCESSFUL DATA PURCHASE", $uniqidvalue, $purchased_message, $phone, $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $data_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "Successful",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $data_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "pending", $log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } else {
        handle_transaction_failure(
            $pos, $data_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}

/**
 * Handles cable transactions.
 */
function handle_cable_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                 $response_format_option, $success_value_option, $success_value2_option,
                                 $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                 $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                 $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                 $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    // Add dynamic attributes specific to cable
    if (isset($attribute_map['iucno'])) $datass[vp_option_array($option_array, $attribute_map['iucno'])] = sanitize_text_field($post_data['iuc'] ?? '');
    if (isset($attribute_map['amount'])) $datass[vp_option_array($option_array, $attribute_map['amount'])] = round(floatval($amount), 2);
    if (isset($attribute_map['phone'])) $datass[vp_option_array($option_array, $attribute_map['phone'])] = $phone;
    if (isset($attribute_map['product_id'])) $datass[vp_option_array($option_array, $attribute_map['product_id'])] = sanitize_text_field($post_data['ccable'] ?? '');
    if (isset($attribute_map['type'])) $datass[vp_option_array($option_array, $attribute_map['type'])] = sanitize_text_field($post_data['cabtype'] ?? '');
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';

    if ($query_method != "array") {
        if ($request_method == "get") {
            $final_url = add_query_arg($datass, $url);
            $call = wp_remote_get($final_url, $http_args);
        } else { // post
            $http_args['body'] = json_encode($datass);
            $call = wp_remote_post($url, $http_args);
        }
        $response = wp_remote_retrieve_body($call);
    } else {
        $response = vp_remote_post_fn($url, $headers_array, $datass);
        if ($response == "error") {
            global $return_message;
            handle_transaction_failure(
                $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
            );
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $cable_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $cable_token = $response_token_data[0];
    }

    $log_response_snippet = harray_key_first($response);

    if ($en_status == "TRUE" || $response === $success_value) {
        $cable_type_for_email = strtoupper(sanitize_text_field($post_data['cabtype'] ?? ''));
        $purchased_message = "Paid " . $cable_type_for_email . " CableTv Subscription worth ₦" . number_format($realAmt, 2);
        weblinkBlast($phone, $purchased_message);
        vp_transaction_email("NEW CABLETV NOTIFICATION", "SUCCESSFUL CABLETV SUBSCRIPTION", $uniqidvalue, $purchased_message, sanitize_text_field($post_data['iuc'] ?? ''), $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $cable_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "Successful", $log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $cable_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "pending",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } else {
        handle_transaction_failure(
            $pos, $cable_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}


/**
 * Handles bill transactions.
 */
function handle_bill_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                 $response_format_option, $success_value_option, $success_value2_option,
                                 $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                 $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                 $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                 $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    
    $bill_charge = floatval(vp_option_array($option_array, "bill_charge"));
    $buyAmt = $amount - $bill_charge;

    // Add dynamic attributes specific to bill
    if (isset($attribute_map['meterno'])) $datass[vp_option_array($option_array, $attribute_map['meterno'])] = sanitize_text_field($post_data['meterno'] ?? '');
    if (isset($attribute_map['amount'])) $datass[vp_option_array($option_array, $attribute_map['amount'])] = round(floatval($buyAmt), 2);
    if (isset($attribute_map['phone'])) $datass[vp_option_array($option_array, $attribute_map['phone'])] = $phone;
    if (isset($attribute_map['product_id'])) $datass[vp_option_array($option_array, $attribute_map['product_id'])] = sanitize_text_field($post_data['cbill'] ?? '');
    if (isset($attribute_map['type'])) $datass[vp_option_array($option_array, $attribute_map['type'])] = sanitize_text_field($post_data['type'] ?? '');
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';

    if ($query_method != "array") {
        if ($request_method == "get") {
            $final_url = add_query_arg($datass, $url);
            $call = wp_remote_get($final_url, $http_args);
        } else { // post
            $http_args['body'] = json_encode($datass);
            $call = wp_remote_post($url, $http_args);
        }
        $response = wp_remote_retrieve_body($call);
    } else {
        $response = vp_remote_post_fn($url, $headers_array, $datass);
        if ($response == "error") {
            global $return_message;
            handle_transaction_failure(
                $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
            );
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $bill_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $bill_token = $response_token_data[0];
    }

    $meter_token = "Nill"; // Specific for bill payments
    $meter_token_data = search_bill_token(json_decode($response, true), vp_getoption("metertoken"));
    if (!empty($meter_token_data)) {
        $meter_token = $meter_token_data[0];
    }
    $extra_data['meter_token'] = $meter_token; // Update extra_data with actual meter token

    $log_response_snippet = harray_key_first($response);

    if ($en_status == "TRUE" || $response === $success_value) {
        $purchased_message = "Paid for UTILITY BILL worth ₦" . number_format($realAmt, 2);
        weblinkBlast($phone, $purchased_message);
        vp_transaction_email("NEW UTILITY BILL NOTIFICATION", "SUCCESSFUL UTILITY BILL PAYMENT", $uniqidvalue, $purchased_message, $meter_token, $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $bill_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "Successful",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $bill_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "pending",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } else {
        handle_transaction_failure(
            $pos, $bill_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}


/**
 * Handles SMS transactions.
 */
function handle_sms_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                $response_format_option, $success_value_option, $success_value2_option,
                                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    // Add dynamic attributes specific to SMS
    if (isset($attribute_map['sender'])) $datass[vp_option_array($option_array, $attribute_map['sender'])] = sanitize_text_field($post_data['sender'] ?? '');
    if (isset($attribute_map['receiver'])) $datass[vp_option_array($option_array, $attribute_map['receiver'])] = sanitize_text_field($post_data['receiver'] ?? '');
    if (isset($attribute_map['message'])) $datass[vp_option_array($option_array, $attribute_map['message'])] = sanitize_textarea_field($post_data['message'] ?? '');
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';

    if ($query_method != "array") {
        if ($request_method == "get") {
            $final_url = add_query_arg($datass, $url);
            $call = wp_remote_get($final_url, $http_args);
        } else { // post
            $http_args['body'] = json_encode($datass);
            $call = wp_remote_post($url, $http_args);
        }
        $response = wp_remote_retrieve_body($call);
    } else {
        $response = vp_remote_post_fn($url, $headers_array, $datass);
        if ($response == "error") {
            global $return_message;
            handle_transaction_failure(
                $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
            );
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $sms_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $sms_token = $response_token_data[0];
    }

    $log_response_snippet = harray_key_first($response);

    if ($en_status == "TRUE" || $response === $success_value) {
        $purchased_message = sanitize_textarea_field($post_data["message"] ?? '');
        weblinkBlast(sanitize_text_field($post_data["receiver"] ?? ''), $purchased_message);
        vp_transaction_email("SMS SENT NOTIFICATION", "MESSAGE SENT", $uniqidvalue, $purchased_message, sanitize_text_field($post_data["receiver"] ?? ''), $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $sms_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "Successful",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $sms_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "pending",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } else {
        handle_transaction_failure(
            $pos, $sms_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}


/**
 * Handles betting transactions.
 */
function handle_bet_transaction($request_method, $api_url_option, $api_endpoint_option, $success_code_option,
                                $response_format_option, $success_value_option, $success_value2_option,
                                $response_id_option, $query_method_option, $post_data_map, $attribute_map,
                                $header_map, $add_headers_prefix, $add_value_prefix, $post_data, $user_id,
                                $name, $email, $phone, $network, $url_from_post, $uniqidvalue, $bal, $baln, $amount,
                                $realAmt, $browser, $option_array, $service_table_name, $trans_table_name, $add_total, $pos, $trans_type_for_db, $tb4, $tnow, $extra_data = []) {
    global $wpdb, $current_timestamp;

    // Ensure $extra_data is an array. If it's not, initialize it as an empty array.
    if (!is_array($extra_data)) {
        $extra_data = [];
    }

    $api_base_url = vp_option_array($option_array, $api_url_option);
    $api_endpoint = vp_option_array($option_array, $api_endpoint_option);
    $success_code = vp_option_array($option_array, $success_code_option);
    $response_format = vp_option_array($option_array, $response_format_option);
    $success_value = vp_option_array($option_array, $success_value_option);
    $success_value2 = vp_option_array($option_array, $success_value2_option);
    $response_id_key = vp_option_array($option_array, $response_id_option);
    $query_method = vp_option_array($option_array, $query_method_option);

    $url = $api_base_url . $api_endpoint;
    $datass = [];
    foreach ($post_data_map as $post_key_option => $post_value_option) {
        $key = vp_option_array($option_array, $post_key_option);
        $value = vp_option_array($option_array, $post_value_option);
        if (!empty($key) && !empty($value)) {
            $datass[$key] = $value;
        }
    }

    // Add dynamic attributes specific to betting
    if (isset($attribute_map['customerid'])) $datass[vp_option_array($option_array, $attribute_map['customerid'])] = sanitize_text_field($post_data['phone'] ?? ''); // Assuming phone is customerid
    if (isset($attribute_map['amount'])) $datass[vp_option_array($option_array, $attribute_map['amount'])] = round(floatval($amount), 2);
    if (isset($attribute_map['company'])) $datass[vp_option_array($option_array, $attribute_map['company'])] = sanitize_text_field($post_data['network'] ?? ''); // Assuming network is company
    if (isset($attribute_map['request_id'])) $datass[vp_option_array($option_array, $attribute_map['request_id'])] = $uniqidvalue;

    $headers_array = [
        'Content-Type' => 'application/json',
        'cache-control' => 'no-cache',
    ];

    $the_head_option = vp_option_array($option_array, $header_map['head_option']);
    if ($the_head_option == "not_concatenated") {
        $the_auth = vp_option_array($option_array, $header_map['value1']);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } elseif ($the_head_option == "concatenated") {
        $the_auth_value = vp_option_array($option_array, $header_map['value1']);
        $the_auth = base64_encode($the_auth_value);
        $auto = vp_option_array($option_array, $header_map['head1']) . ' ' . $the_auth;
        $headers_array["Authorization"] = $auto;
    } else {
        $headers_array[vp_option_array($option_array, $header_map['head1'])] = vp_option_array($option_array, $header_map['value1']);
    }

    for ($i = 1; $i <= 4; $i++) {
        $header_key = vp_option_array($option_array, $add_headers_prefix . $i);
        $header_value = vp_option_array($option_array, $add_value_prefix . $i);
        if (!empty($header_key) && !empty($header_value)) {
            $headers_array[$header_key] = $header_value;
        }
    }

    $http_args = [
        'headers' => $headers_array,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'sslverify' => false,
    ];

    $call = null;
    $response = '';

    if ($query_method != "array") {
        if ($request_method == "get") {
            $final_url = add_query_arg($datass, $url);
            $call = wp_remote_get($final_url, $http_args);
        } else { // post
            $http_args['body'] = json_encode($datass);
            $call = wp_remote_post($url, $http_args);
        }
        $response = wp_remote_retrieve_body($call);
    } else {
        $response = vp_remote_post_fn($url, $headers_array, $datass);
        if ($response == "error") {
            global $return_message;
            handle_transaction_failure(
                $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
                $request_method, $uniqidvalue, $user_id, $return_message, $service_table_name, $trans_table_name,
                wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
            );
        }
    }

    // Handle API call errors
    if (is_wp_error($call)) {
        $error_message = vp_getoption("vpdebug") != "yes" ? $call->get_error_code() : $call->get_error_message();
        handle_transaction_failure(
            $pos, 'no_response', $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $error_message, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), 'FALSE', 'TEXT', $extra_data
        );
    }

    // Evaluate API response
    $en_status = '';
    if ($response_format == "JSON" || $response_format == "json") {
        $en_status = validate_response($response, $success_code, $success_value, $success_value2);
    } else {
        $en_status = $response;
    }

    $bet_token = "Nill";
    $response_token_data = search_bill_token(json_decode($response, true), $response_id_key);
    if (!empty($response_token_data)) {
        $bet_token = $response_token_data[0];
    }

    $log_response_snippet = harray_key_first($response);

    if ($en_status == "TRUE" || $response === $success_value) {
        $purchased_message = "Funded " . sanitize_text_field($post_data['phone'] ?? '') . " Wallet On " . sanitize_text_field($post_data['network'] ?? '') . " With " . number_format($amount, 2);
        weblinkBlast(sanitize_text_field($post_data['phone'] ?? ''), $purchased_message);
        vp_transaction_email("NEW BET FUNDING NOTIFICATION", "SUCCESSFUL BET FUNDING TRANSACTION", $uniqidvalue, $purchased_message, sanitize_text_field($post_data['phone'] ?? ''), $amount, $bal, $baln);

        post_transaction_handling(
            $pos, $bet_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "Successful",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } elseif ($en_status == "MAYBE") {
        post_transaction_handling(
            $pos, $bet_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, "pending",$log_response_snippet, $realAmt, $service_table_name,
            $trans_table_name, $add_total, $tb4, $tnow, $extra_data
        );
    } else {
        handle_transaction_failure(
            $pos, $bet_token, $name, $email, $network, $phone, $bal, $baln, $amount, $browser, $trans_type_for_db,
            $request_method, $uniqidvalue, $user_id, $log_response_snippet, $service_table_name, $trans_table_name,
            wp_remote_retrieve_response_code($call), $en_status, $response_format, $extra_data
        );
    }
}
