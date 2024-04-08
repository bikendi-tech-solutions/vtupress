<?php

// Function to check if shell_exec() is enabled
function shell_exec_enabled() {
    return strtolower(ini_get('disable_functions')) !== 'shell_exec';
}

// Function to remove a cron job
function removeCronJob($command) {
    if (shell_exec_enabled()) {
        $result = shell_exec('crontab -l');
    } else {
        exec('crontab -l', $result);
    }

    if ($result !== null) {
        // Crontab retrieved successfully
        $updatedCronJobs = array();

        // Remove the specific cron job
        foreach ($result as $cronJob) {
            if (strpos($cronJob, $command) === false) {
                // Retain lines not matching the specified command
                $updatedCronJobs[] = $cronJob;
            }
        }

        // Save the updated crontab
        $updatedCronContent = implode(PHP_EOL, $updatedCronJobs);
        file_put_contents('/tmp/crontab.txt', $updatedCronContent);

        if (shell_exec_enabled()) {
            shell_exec('crontab /tmp/crontab.txt');
        } else {
            exec('crontab /tmp/crontab.txt', $output, $returnCode);
        }

        if ($returnCode === 0) {
            echo "Cron job removed successfully.";
        } else {
            echo "Error removing cron job.";
        }
    } else {
        echo "Error retrieving crontab.";
    }
}

// Command to remove (replace with the actual command you want to remove)
$commandToRemove = "php /path/to/your/script.php";

// Remove the cron job
removeCronJob($commandToRemove);

?>
