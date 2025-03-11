<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Allow CORS and return without logging/counting
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("HTTP/1.1 204 No Content"); // No response body
    exit();
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
    

if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}


$date = date("Y-m-d H:i:s");

global $wpdb;

$table_name = $wpdb->prefix.'vp_smsblaster';
$result = $wpdb->get_results("SELECT * FROM $table_name WHERE logged = 'no'");

if(empty($result)){
    
    print_r(json_encode([],JSON_UNESCAPED_SLASHES));
    return;
}

$wpdb->update($table_name,["logged"=>"yes"],["logged"=>"no"]);

echo json_encode($result,JSON_UNESCAPED_SLASHES);

