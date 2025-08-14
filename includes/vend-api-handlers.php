<?php
/**
 * API interaction functions for VTUPress vend operations.
 *
 * @package VTUPress
 * @subpackage API_Handlers
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}

/**
 * Handles the HTTP header response from a provider API call.
 * Reverses transaction if the response indicates an error and 't_header_check' is enabled.
 *
 * @param WP_Error|array $call The result of wp_remote_get or wp_remote_post.
 * @return void
 */
function provider_header_handler($call) {
    global $wpdb, $uniqidvalue, $id, $bal;

    $response_code = wp_remote_retrieve_response_code($call);
    $response_body = wp_remote_retrieve_body($call);

    // Update transaction record with API response.
    $table_data = $wpdb->prefix . "vp_transactions";
    $wpdb->update(
        $table_data,
        array('api_response' => $response_body, "api_from" => "script"),
        array("request_id" => $uniqidvalue)
    );

    $message = null;
    if ($response_code >= 100 && $response_code <= 199) {
        $message = "Informative HTTP Status Code [" . $response_code . "]";
    } elseif ($response_code >= 300 && $response_code <= 399) {
        $message = "Redirection HTTP Status Code [" . $response_code . "]";
    } elseif ($response_code >= 400 && $response_code <= 499) {
        $message = "Client Error Response Status Code [" . $response_code . "]";
    } elseif ($response_code >= 500 && $response_code <= 599) {
        $message = "Server Error Response Status Code [" . $response_code . "]";
    } elseif ($response_code === 0 || $response_code === null) {
        $message = "No HTTP Status Code received (possible connection issue or timeout)";
    }

    if ($message !== null) {
        // Only attempt reversal if it's a 'vend' POST request.
        if (isset($_POST["vend"])) {
            $user_id = get_current_user_id();
            $table_lock = "{$wpdb->prefix}vp_wallet_lock";

            // Ensure the lock table exists.
            $wpdb->query("
                CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vp_wallet_lock (
                    user_id BIGINT PRIMARY KEY,
                    locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB
            ");

            // Start transaction and acquire lock.
            $wpdb->query('START TRANSACTION');
            $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}vp_wallet_lock (user_id) VALUES (%d) ON DUPLICATE KEY UPDATE user_id = user_id", $user_id));
            $wpdb->get_row($wpdb->prepare("SELECT user_id FROM $table_lock WHERE user_id = %d FOR UPDATE", $user_id));

            // Delete the unrecorded transaction if it was added.
            $table_trans = $wpdb->prefix . 'vp_transactions';
            $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));

            // Revert user balance.
            vp_updateuser($id, "vp_bal", $bal);
            setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

            $wpdb->query('COMMIT'); // Commit the transaction.
        }

        if (vp_getoption('t_header_check') === "yes") {
            die("There must be something wrong with the provider I am connected to. \n " . $message);
        }
    }
}

/**
 * Custom remote POST function using wp_remote_post, supporting array body.
 * This function should be preferred over raw cURL for WordPress.
 *
 * @param string $url The request URL.
 * @param array $headers Request headers.
 * @param array $body Request body data.
 * @return string The response body or "error" on failure.
 */
// function vp_remote_post_fn($url, $headers, $body) {
//     global $return_message; // Consider returning this instead of global.

//     $args = array(
//         'headers' => $headers,
//         'body' => json_encode($body),
//         'timeout' => 3000, // Increased timeout
//         'user-agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url'),
//         'sslverify' => false, // Set to true in production with proper CA certs
//     );

//     $response = wp_remote_post($url, $args);

//     if (is_wp_error($response)) {
//         $return_message = "Remote POST Error: " . $response->get_error_message();
//         error_log($return_message);
//         return "error";
//     }

//     return wp_remote_retrieve_body($response);
// }
