<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

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

$meterno = $_REQUEST['meterno'];
$bill = $_REQUEST['bills'];

$http_args = array(
    'headers' => array(
    'cache-control' => 'no-cache',
    'Content-Type' => 'application/json'
    ),
    'timeout' => '300',
    'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'sslverify' => false
    );

$me =  wp_remote_retrieve_body( wp_remote_get( "https://vtupress.com/billget.php?billget=yes&service=$bill&meter=$meterno", $http_args));

echo $me;

?>