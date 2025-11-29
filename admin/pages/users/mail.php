<?php
// mass_mailer_utility.php

// Ensure this file is only accessed within a WordPress context
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb, $message, $sent_count, $mail_body, $mail_header, $audience_type, $inactive_days, $specific_emails_input;

// Global variable to hold the success message
$message = '';
$sent_count = 0;
$mail_body = '';
$mail_header = '';
$audience_type = '';
$inactive_days = '';
$specific_emails_input = '';

// Define the custom action hook for the background task
define('MASS_MAILER_TASK_HOOK', 'mass_mailer_background_send');

/**
 * Executes the mass mail sending in the background.
 * This function is hooked to the WP-Cron action and runs non-blockingly.
 *
 * @param string $task_id The unique ID used to retrieve the transient data.
 */
function execute_mass_mail_task($task_id) {
    // 1. Retrieve the task data from the transient
    $task_data = get_transient($task_id);

    // If data is missing or transient expired, exit.
    if (empty($task_data) || !is_array($task_data) || empty($task_data['emails'])) {
        error_log("Mass Mail Task $task_id failed: Data missing or expired.");
        return;
    }

    $target_emails = $task_data['emails'];
    $mail_header   = $task_data['subject'];
    $email_template = $task_data['template'];
    $headers       = $task_data['headers'];
    $success_count = 0;

    // 2. Send Emails in a loop
    foreach ($target_emails as $email) {
        // wp_mail returns true on success, false on failure
        if (wp_mail($email, $mail_header, $email_template, $headers)) {
            $success_count++;
        }
    }

    // 3. Cleanup and Notification
    delete_transient($task_id); // Remove the transient data

    // Log the result (for admin/debugging)
    $log_message = sprintf(
        "Mass Mail Task %s Completed: Targeted %d, Successfully Sent %d.",
        $task_id,
        count($target_emails),
        $success_count
    );
    error_log($log_message);
}
add_action(MASS_MAILER_TASK_HOOK, 'execute_mass_mail_task', 10, 1);


/**
 * Handles the mass mail form submission by scheduling the task.
 */
function handle_mass_mail_submission() {
    global $wpdb, $message, $mail_body, $mail_header, $audience_type, $inactive_days, $specific_emails_input;

    // Check for POST request and nonce for security (essential for admin pages)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_mail_nonce']) && wp_verify_nonce($_POST['send_mail_nonce'], 'send_mass_mail')) {

        // 1. Sanitize and Validate Input (Inputs are saved to global variables for form persistence)
        $mail_header = sanitize_text_field($_POST['mail_header']);
        $mail_body = wp_kses_post($_POST['mail_body']);
        $audience_type = sanitize_text_field($_POST['audience_type']);
        $inactive_days = isset($_POST['inactive_days']) ? intval($_POST['inactive_days']) : 0;
        $specific_emails_input = isset($_POST['specific_emails']) ? sanitize_textarea_field($_POST['specific_emails']) : '';
        $from_email = get_option('admin_email');

        if (empty($mail_header) || empty($mail_body)) {
            $message = '<div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 1rem; border-radius: 0.25rem;" role="alert">Error: Email header and body cannot be empty.</div>';
            return;
        }

        $target_emails = [];

        // 2. Determine Target Users based on Audience Type (Logic remains the same)
        if ($audience_type === 'all') {
            $users = get_users(['fields' => ['user_email']]);
            foreach ($users as $user) {
                if (is_email($user->user_email)) {
                    $target_emails[] = $user->user_email;
                }
            }

        } elseif ($audience_type === 'inactive' && $inactive_days > 0) {
            $cutoff_date = date('Y-m-d H:i:s', strtotime("-$inactive_days days", current_time('timestamp', 1)));

            $airtime_table = $wpdb->prefix . 'sairtime';
            $data_table = $wpdb->prefix . 'sdata';
            $users_table = $wpdb->prefix . 'users';

            $active_user_ids = $wpdb->get_col( $wpdb->prepare("
                SELECT DISTINCT user_id FROM (
                    SELECT user_id, the_time FROM $airtime_table WHERE status = 'successful' AND the_time > %s
                    UNION
                    SELECT user_id, the_time FROM $data_table WHERE status = 'successful' AND the_time > %s
                ) AS recent_transactions
            ", $cutoff_date, $cutoff_date));

            $active_user_id_list = !empty($active_user_ids) ? implode(',', array_map('intval', $active_user_ids)) : '0';

            $inactive_users = $wpdb->get_results("
                SELECT user_email FROM $users_table
                WHERE ID NOT IN ($active_user_id_list) AND ID != 0
            ");

            foreach ($inactive_users as $user) {
                if (is_email($user->user_email)) {
                    $target_emails[] = $user->user_email;
                }
            }
        } elseif ($audience_type === 'specific') {
            $emails_array = array_map('trim', explode(',', $specific_emails_input));

            foreach ($emails_array as $email) {
                if (is_email($email)) {
                    $target_emails[] = $email;
                }
            }

            if (empty($target_emails)) {
                $message = '<div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; margin-bottom: 1rem; border-radius: 0.25rem;" role="alert">Warning: No valid emails were found in the specific emails list.</div>';
                return;
            }
        }

        if (empty($target_emails)) {
             $message = '<div style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 15px; margin-bottom: 1rem; border-radius: 0.25rem;" role="alert">Warning: No target users found for the selected audience.</div>';
             return;
        }

        // 3. Prepare Email Content and Headers
        $email_template = get_email_template($mail_header, $mail_body);
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            "From: " . get_bloginfo('name') . " <$from_email>",
        ];

        // 4. Save data to Transient and Schedule Task (ASYNC!)
        $task_id = uniqid('mass_mail_');
        $task_data = [
            'emails' => $target_emails,
            'subject' => $mail_header,
            'template' => $email_template,
            'headers' => $headers,
        ];

        // Store the task data for 1 hour
        set_transient($task_id, $task_data, HOUR_IN_SECONDS);

        // Schedule the task to run as soon as possible via WP-Cron
        wp_schedule_single_action(time(), MASS_MAILER_TASK_HOOK, [$task_id]);

        $message = "<div style='background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; margin-bottom: 1rem; border-radius: 0.25rem;' role='alert'>
            <strong>Success!</strong> The mass email process has been initiated in the background. It will send to <strong>" . count($target_emails) . "</strong> users. You can close this page now. The task will complete asynchronously.
        </div>";

    } else {
        // Nonce check failed or not a POST request
        $message = '';
    }
}

// Run the submission handler
handle_mass_mail_submission();

/**
 * Creates the HTML email template.
 *
 * @param string $header The email subject/header.
 * @param string $body The main content of the email.
 * @return string The complete HTML email body.
 */
function get_email_template($header, $body) {
    // Basic, responsive HTML email template
    $template = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . esc_html($header) . '</title>
    <style>
        body, html { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #1e3a8a; color: white; padding: 20px; text-align: center; border-top-left-radius: 8px; border-top-right-radius: 8px; }
        .content { padding: 30px; line-height: 1.6; color: #333333; }
        .footer { padding: 20px; text-align: center; font-size: 0.8em; color: #999999; border-top: 1px solid #eeeeee; }
        h1 { margin: 0; font-size: 24px; }
        p { margin-top: 0; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>' . esc_html($header) . '</h1>
        </div>
        <div class="content">
            ' . wpautop(wp_kses_post($body)) . '
        </div>
        <div class="footer">
            <p>&copy; ' . date("Y") . ' ' . get_bloginfo('name') . '. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
    ';
    return $template;
}

// Start of the HTML output for the admin page
?>
<div class="wrap container-fluid">
    <style>
        /* Custom CSS to emulate Bootstrap layout and components */
        .wrap {
            max-width: 900px !important;
            margin: 20px auto;
        }
        h1 {
            font-size: 1.75rem; /* ~28px */
            font-weight: 700;
            color: #212529; /* Dark grey/black */
            margin-bottom: 1.5rem;
        }
        .main-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        /* Form controls */
        .form-group {
            margin-bottom: 1rem;
        }
        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }
        /* Button styling */
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            padding: .75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .3rem;
            transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
            width: 100%;
            cursor: pointer;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
        /* Section styling */
        .card-section {
            background-color: #f8f9fa; /* light grey */
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }
        .section-header {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 1rem;
        }
        /* Utilities */
        .d-block { display: block; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        .text-muted { color: #6c757d; font-size: 0.85rem; }
    </style>

    <h1 style="font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 1.5rem;">Mass Email Utility</h1>
    <p style="color: #666; margin-bottom: 1.5rem;">Send personalized emails to your users, targeting all, inactive users, or specific email addresses.</p>

    <?php echo $message; // Display status message ?>

    <div class="main-card">
        <form method="post" action="">
            <?php wp_nonce_field('send_mass_mail', 'send_mail_nonce'); ?>

            <!-- 1. Email Header -->
            <div class="form-group">
                <label for="mail_header" class="d-block mb-2" style="font-weight: 500;">Email Subject/Header</label>
                <input type="text" name="mail_header" id="mail_header" class="form-control" required value="<?php echo esc_attr($mail_header);?>">
            </div>

            <!-- 2. Email Body  (Supports HTML content)-->
            <div class="form-group mb-4">
                <label for="mail_body" class="d-block mb-2" style="font-weight: 500;">Email Body </label>
                <textarea name="mail_body" id="mail_body" rows="8" class="form-control" required placeholder="Enter the main body of your email here."><?php echo esc_textarea($mail_body);?></textarea>
            </div>

            <!-- 3. Targeting Audience -->
            <div class="card-section">
                <div class="section-header">Target Audience</div>
                <div class="form-group row">
                    <label for="audience_type" class="d-block mb-2" style="font-weight: 500;">Audience Selection</label>
                    <!-- Audience Selector -->
                    <select name="audience_type" id="audience_type" class="form-control">
                        <option value="all" <?php selected($audience_type, "all");?>>All Users</option>
                        <option value="inactive" <?php selected($audience_type, "inactive");?>>Users Inactive for X Days</option>
                        <option value="specific" <?php selected($audience_type, "specific");?>>Specific Emails (Comma Separated)</option>
                    </select>
                </div>

                <!-- Conditional Input for Inactive Days -->
                <div id="inactive_days_container" class="mt-4 hidden">
                    <label for="inactive_days" class="d-block mb-2" style="font-weight: 500;">Number of Days Inactive</label>
                    <input type="number" name="inactive_days" id="inactive_days" value="<?php echo (!empty($inactive_days)) ? esc_attr($inactive_days) : "30";?>" min="1" class="form-control" style="width: 100px;">
                    <p class="text-muted mt-1">Users who haven't made a successful Airtime or Data purchase in the last this many days.</p>
                </div>

                <!-- Conditional Input for Specific Emails -->
                <div id="specific_emails_container" class="mt-4 hidden">
                    <label for="specific_emails" class="d-block mb-2" style="font-weight: 500;">Specific Emails (Comma Separated)</label>
                    <textarea name="specific_emails" id="specific_emails" rows="3" class="form-control" placeholder="e.g., user1@example.com, user2@example.com, test@site.com"><?php echo esc_textarea($specific_emails_input);?></textarea>
                    <p class="text-muted mt-1">Enter valid email addresses separated by commas.</p>
                </div>

            </div>

            <!-- 4. Submit Button -->
            <button type="submit" class="btn btn-primary">
                Send Mass Mail
            </button>
        </form>
    </div>
</div>

<!-- jQuery and Custom Script -->
<script>
jQuery(document).ready(function($) {
    const audienceSelect = $('#audience_type');
    const inactiveDaysContainer = $('#inactive_days_container');
    const inactiveDaysInput = $('#inactive_days');
    const specificEmailsContainer = $('#specific_emails_container');
    const specificEmailsInput = $('#specific_emails');

    // Function to manage the visibility and requirements of conditional inputs
    function toggleConditionalInputs() {
        // Hide all conditional inputs and remove required status first
        inactiveDaysContainer.hide();
        inactiveDaysInput.prop('required', false);
        specificEmailsContainer.hide();
        specificEmailsInput.prop('required', false);

        // Check the selected value and show the relevant input
        const selectedAudience = audienceSelect.val();

        if (selectedAudience === 'inactive') {
            inactiveDaysContainer.show();
            // Note: PHP validation handles the required check, but setting here for visual cue
        } else if (selectedAudience === 'specific') {
            specificEmailsContainer.show();
            // Note: PHP validation handles the required check, but setting here for visual cue
        }
    }

    // Initial check on load
    toggleConditionalInputs();

    // Event listener for changes
    audienceSelect.on('change', toggleConditionalInputs);
});
</script>

<?php
// End of PHP file
?>