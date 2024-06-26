<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

$allowed_referrers = [
    $_SERVER['SERVER_NAME']
];

// Check if the referrer is set
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    
    // Check if the referrer is in the allowed list
    if (!in_array($referer, $allowed_referrers)) {
        die("REF ENT PERM");
    }
} else {
    die("BAD");
}


if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if(isset($_REQUEST["enable-schedule"])){
	
	vp_updateoption("enable-schedule",$_REQUEST["enable-schedule"]);
	die("100");
}

if(isset($_REQUEST["schedule-time"])){
	
	vp_updateoption("schedule-time",$_REQUEST["schedule-time"]);
	$now = date('h:i:s', strtotime(date('h:i:s',$current_timestamp). '+'.intval($_REQUEST["schedule-time"]).' Minutes'));
	vp_updateoption("next-schedule", $now);
	die("100");
}

?>