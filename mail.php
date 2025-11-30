<?php

/**
 * Standalone Mass Mailer Cron Script
 * ----------------------------------
 * Processes up to 50 emails per cron execution.
 * Efficient and safe for large mailing lists (5,000–100,000 users).
 *
 * Cron example (every 5 mins):
 * /usr/bin/php /home/user/public_html/wp-content/mass_mailer_cron.php
 */

if (!defined('ABSPATH')) {
    // Auto-locate WordPress root
    $root = explode('/wp-content/', __DIR__);
    require_once $root[0] . '/wp-load.php';
}

if (!WP_DEBUG) {
    error_reporting(0);
}

// update_option('vp_mailer_last_cron', time());


// Safety (optional)
// if (php_sapi_name() !== 'cli') exit("CLI only.\n");

const MASS_MAIL_TRANSIENT_PREFIX = 'mass_mail_';
$transient_db_prefix = '_transient_';

global $wpdb;

// Find all transients that belong to the mass mailer
$search_pattern = $transient_db_prefix . MASS_MAIL_TRANSIENT_PREFIX . '%';

$sql = $wpdb->prepare(
    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
    $search_pattern
);

$transient_keys = $wpdb->get_col($sql);

if (empty($transient_keys)) {
    exit("No pending mass mailer tasks found.\n");
}


// Force HTML content-type
add_filter('wp_mail_content_type', function () {
    return "text/html";
});

$tasks_processed = 0;

foreach ($transient_keys as $option_name) {

    // Strip out "_transient_"
    $raw_key = substr($option_name, strlen($transient_db_prefix));

    $task = get_transient($raw_key);

    if ($task === false) {
        echo "Expired/missing task: {$raw_key}\n";
        delete_transient($raw_key);
        continue;
    }

    echo "Processing task: {$raw_key}\n";

    // Extract data
    $emails  = $task["emails"] ?? [];
    $subject = $task["subject"] ?? "No Subject";
    $body    = $task["template"] ?? "Empty email";
    $headers = $task["headers"] ?? [];

    if (!is_array($emails) || count($emails) === 0) {
        echo "No emails left. Deleting task {$raw_key}\n";
        delete_transient($raw_key);
        continue;
    }

    // Maximum emails per cron execution
    $batch_size = 50;

    // Extract 50 emails only
    $batch = array_splice($emails, 0, $batch_size);

    echo "Sending batch of " . count($batch) . " emails...\n";

    foreach ($batch as $email) {
        try {
            wp_mail($email, $subject, $body, $headers);
            echo "Sent to: {$email}\n";

        } catch (Exception $e) {
            error_log("MassMailer ERROR on {$raw_key} => " . $e->getMessage());
            echo "Failed to send email to {$email}\n";
        }
    }

    // Update transient with remaining emails
    if (count($emails) === 0) {
        echo "Completed task {$raw_key}. Deleting...\n";
        delete_transient($raw_key);

    } else {
        $task["emails"] = $emails;

        // Extend task by 24 hours
        update_transient($raw_key, $task, 3600 * 24);

        echo count($emails) . " emails remaining...\n";
    }

    $tasks_processed++;
}

// Remove HTML filter
remove_filter('wp_mail_content_type', 'set_html_content_type');

echo "\nCompleted. Tasks processed: {$tasks_processed}\n";
