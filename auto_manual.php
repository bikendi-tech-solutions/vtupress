<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

global $wpdb;
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
    if(!preg_match("/$nm/",$referer)) {
        die("NO REF");
    }

}

if(!is_user_logged_in()){
    die("Login Required");
}

$user_id = get_current_user_id();

if(!isset($_POST["sessionID"])){
    die("NO Session Id");
}

$sessionid = trim(sanitize_text_field($_POST["sessionID"]));
$account = trim(sanitize_text_field($_POST["accountNumber"]));

$sessionTable = $wpdb->prefix."vp_auto_manual";

//check if table exist

$table_exists = $wpdb->query("SHOW TABLES LIKE '$sessionTable'");
if($table_exists == 0){
    $status = [
        "status" => false,
        "message" => "Table Not Found"
    ];
    die(json_encode($status));
}

//check if sessionid exists

$session_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $sessionTable WHERE sessionId = %s", $sessionid));
if($session_exists >= 1){
    $status = [
        "status" => true,
        "message" => "Session ID Already Exists"
    ];
    die(json_encode($status));
}

/*
  id int NOT NULL AUTO_INCREMENT,
  sessionId text ,
  user_id text,
  amount text,
  charge text,
  api_response text,
  the_time text,
  status text,
*/



// $status = [
//     "status" => true,
//     "message" => "You'd get credited in less than 2min"
// ];
// die(json_encode($status));

//generate a requiest id of 27 code starting from year, month, dat, hour,minute,seconds and any other random number

$year = date("Y");
$month = date("m");
$day = date("d");
$hour = date("H");
$minute = date("i");
$second = date("s");

$random = mt_rand(100000000, 999999999);

$request_id = $year.$month.$day.$hour.$minute.$second.$random."ab";

//make a wordpress http_request

$url = "https://ipayng.com/api/live/v1/automanual";
$apikey = vp_getoption("auto_manual_apikey");
$headers = array(
    "Authorization: Bearer $apikey",
    "Content-Type: application/json",
);
// {
//     "accountNo": "7079963052",
//     "sessionIdOrReference": "250224010100409903288217",
//     "amount": "100",
//     "reference":"20240p0t2vpo3seIIInopOsepoi199"
// }
$payload = [
    "accountNo" => $account,
    "sessionIdOrReference" => $sessionid,
    "amount" => "100",
    "reference" => $request_id,
];

$response = wp_remote_post( $url, array(
    'headers' => $headers,
    'body' => json_encode($payload),
    'timeout' => 200,
    'method'  => 'POST',
) );

// $body = wp_remote_retrieve_body($response);

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apikey" // If required
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Convert array to JSON

$response = curl_exec($ch);
curl_close($ch);

$body = $response;


$data = json_decode($body, true);
if(!isset($data["code"])){
    $status = [
        "status" => false,
        "message" => "Error Processing Payment"
    ];
    die(json_encode($status));
}else{
    if($data["code"] == "201"){
        //successful
        
        $status = [
            "status" => true,
            "message" => "Payment Successful! You'd get credited in less than 2min"
        ];

        $data = [
            "sessionId" => $sessionid,
            "user_id" => $user_id,
            "amount" => "",
            "charge" => "",
            "api_response" => "",
            "status" => "pending",
            "accountNumber" => $account,
            "the_time" => date("Y-m-d H:i:s"),
        ];
        
        $format = ['%s', '%s', '%s', '%s', '%s', '%s', '%s','%s'];
        
        $insert_id = $wpdb->insert($sessionTable, $data, $format);
        
    }elseif($data["code"] == "424"){
        if(isset($data["code"]["message"])){
            $status = [
                "status" => false,
                "message" => $data["code"]["message"]
            ];
        }else{
            $status = [
                "status" => false,
                "message" => "Error Processing Payment"
            ];
        }
        
    }
    else{
        $status = [
            "status" => false,
            "message" => $body
        ];
    }
    die(json_encode($status));
}