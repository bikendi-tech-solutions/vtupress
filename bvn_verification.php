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

if(!isset($_POST["type"]) || !isset($_POST["value"])){
    die("NO TYPE OR VALUE");
}

$type = $_POST["type"];
$value = $_POST["value"];

if(isset($_POST["card"])){
$card = $_POST["card"];
}
else{
$card = "portrait_card";
}
$user_id = get_current_user_id();

$fn = vp_getuser($user_id,"first_name");
$ln = vp_getuser($user_id,"last_name");

$userFullName = $fn." ".$ln;

$phone = vp_getuser($user_id, 'vp_phone', true);

$current_bal = vp_getuser($user_id,"vp_bal",true);


switch($type){
    case"bvn":
        $verTypeId = "bvn";
        $charge = intval(vp_getoption("u_bvn_verification_charge"));
        $method = "BVN";
        
    break;
    case"nin":
        $verTypeId = "nin";
        $charge = intval(vp_getoption("u_nin_verification_charge"));
        $method = "NIN";


    break;
    default: die("Type Undefined/Incorrect");
}

if($current_bal < $charge && $charge > 2){
    die("Balanace is too low");
}


//check for raptor details
$raptor_conid = vp_getoption('raptor_conid');
$raptor_apikey = vp_getoption('raptor_apikey');


//datas
$datass = array(
	"verificationType" => $verTypeId,
	"firstName" => $fn,
	"lastName" => $ln,
	"phone" => $phone,
	"value" => $value
	);

$array = [];
$array["Authorization"] = "Token $raptor_apikey";
$array["cache-control"] = "no-cache";
$array["content-type"] = "application/json";
$array["connectionid"] = $raptor_conid;

$http_args = array(
    'headers' => $array,
    'timeout' => '300',
    'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'blocking'=> true,
    'body' => json_encode($datass)
    );

$url = "https://dashboard.raptor.ng/api/v1/verification/";


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);

$bvnDetails = json_decode($response);

if(!isset($bvnDetails->status)){
	print_r($response);
die("There is something wrong . Please reach out to us");
}
elseif(!$bvnDetails->status){
	$message = $bvnDetails->message;
	die("ERR/RPT - ".$message);
}else{
	//It Is Real!!!

    
	if($charge > 0 ){
        $new_bal_now = $current_bal - $charge;
        vp_updateuser($user_id,"vp_bal",$new_bal_now);
    }else{
        $charge = 0;
        $new_bal_now = $current_bal - $charge;
    }
    

	global $wpdb;
	$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $userFullName,
'type'=> "Wallet",
'description'=> "Debited for $method verification ",
'fund_amount' => $charge,
'before_amount' => $current_bal,
'now_amount' => $new_bal_now,
'user_id' => $user_id,
'status' => "approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


global $wpdb;
$table_name = $wpdb->prefix.'vp_verifications';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $userFullName,
'type'=> strtoupper($type),
'value' => $value,
'fund_amount' => $charge,
'card_type' => $card,
'before_amount' => $current_bal,
'now_amount' => $new_bal_now,
'user_id' => $user_id,
'vDatas' =>  str_replace("\/","/",$response),
'status' => "approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



die("success");

}