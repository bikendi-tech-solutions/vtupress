<?php

//cron control

// Function to check if a cron job is already active
function isCronJobActive($command) {
    $output = shell_exec("crontab -l");
    return (strpos($output, $command) !== false);
}

// Function to create a cron job
function createCronJob($command, $schedule) {
    exec("echo \"" . $schedule . " " . $command . "\" | crontab -");
}

// Command to be executed
$command = "php /path/to/your/script.php";

// Cron job schedule (e.g., every minute: * * * * *)
$schedule = "* * * * *";

// Check if shell_exec() is supported
if (function_exists('shell_exec')) {
    // Use exec() instead of shell_exec() if it's supported
    function shell_exec_enabled() {
        return strtolower(ini_get('disable_functions')) !== 'shell_exec';
    }
    if (shell_exec_enabled()) {
        // Check if the cron job is already active
        if (!isCronJobActive($command)) {
            // Create a new cron job
            createCronJob($command, $schedule);
            echo "Cron job created successfully.";
        } else {
            echo "Cron job is already active.";
        }
    } else {
        // Use exec() instead of shell_exec()
        if (!isCronJobActive($command)) {
            // Create a new cron job
            createCronJob($command, $schedule);
            echo "Cron job created successfully.";
        } else {
            echo "Cron job is already active.";
        }
    }
} else {
    // shell_exec() not supported, using exec()
    if (!isCronJobActive($command)) {
        // Create a new cron job
        createCronJob($command, $schedule);
        echo "Cron job created successfully.";
    } else {
        echo "Cron job is already active.";
    }
}

?>
