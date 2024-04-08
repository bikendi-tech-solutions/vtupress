<?php
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/registry/function.php');



function shell_exec_enabled() {
    return function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))));
}

function removeCronJob($path) {
    if (empty($path) || !file_exists($path)) {
        return false;
    }

    
    if (!shell_exec_enabled()) {
        return "no_shell"; // Shell exec not enabled
    }

    // Check if cron job exists
    $command = "php " . $path;
    $result = shell_exec('crontab -l');
    if ($result !== null) {
        // Crontab retrieved successfully
        $updatedCronJobs = array();

        // Remove the specific cron job
        foreach (explode(PHP_EOL, $result) as $cronJob) {
            if (strpos($cronJob, $command) === false) {
                // Retain lines not matching the specified command
                $updatedCronJobs[] = $cronJob;
            }
        }

        // Save the updated crontab
        $updatedCronContent = implode(PHP_EOL, $updatedCronJobs);
        file_put_contents('/tmp/crontab.txt', $updatedCronContent);

        // Apply the updated crontab
        $output = shell_exec('crontab /tmp/crontab.txt');
        if ($output === null) {
            return true; // Cron job removed successfully
        } else {
            return "cant_remove"; // Failed to remove cron job
        }
    } else {
        return false; // Error retrieving crontab
    }
}

function createCronJob($path, $schedule) {
    if (empty($path) || !file_exists($path)) {
        return false;
    }

    $schedule = trim($schedule);

    if(empty($schedule)){
        $schedule = convertToCronSchedule();
    }
    elseif(!preg_match("/^\S+ \S+ \S+ \S+ \S+$/", $schedule)){
        die("Invalid Schedule Format");
    }
    else{
        $schedule = convertToCronSchedule("custom", $schedule);


    }


    if (!shell_exec_enabled()) {
        return "no_shell"; // Shell exec not enabled
    }

    // Check if cron job exists and remove if needed
    $removeResult = removeCronJob($path);
    if ($removeResult === "cant_remove") {
        return "cant_remove"; // Failed to remove existing cron job
    }

    // Add cron job
    $command = "php " . $path;
    $output = shell_exec('(crontab -l ; echo "'.$schedule.' '.$command.'") | crontab -');
    if ($output === null) {
        return true; // Cron job added successfully
    } else {
        return false; // Failed to add cron job
    }
}







?>
