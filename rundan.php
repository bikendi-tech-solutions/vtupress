<?php
die();
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm\/wp-admin/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}
if(!is_user_logged_in()){
  die("Please Login");
}
elseif(!current_user_can("vtupress_admin")){
  die("Not Allowed");
}


if(!isset($_POST["spraycode"])){
  die("NO SPRAY CODE");
}
$spray_code = trim(htmlspecialchars($_POST["spraycode"]));
$real_code =  vp_getoption("spraycode");

if(empty($spray_code)){
  die("SPRAY CODE CAN'T BE EMPTY");
}
elseif(strtolower($spray_code) != "false"){

  if($real_code == "false"){
      $cur_id = get_current_user_id();
      $update_code = uniqid("vtu_$cur_id");
      vp_updateoption("spraycode",$update_code);
  }elseif($real_code != $spray_code ){
      die("INVALID SPRAYCODE");
  }else{
     //  die("INVALID SPRAYCODE");
  }
}elseif(strtolower($spray_code) == "false"){
  if($real_code == "false"){
      $cur_id = get_current_user_id();
      $update_code = uniqid("vtu_$cur_id");
      vp_updateoption("spraycode",$update_code);
  }elseif($real_code != $spray_code ){
      die("INVALID SPRAYCODE");
  }else{
     //  die("INVALID SPRAYCODE");
  }
}



if(isset($_REQUEST["rundan"])){
 // vp_updateoption(uniqid());
if(vp_getoption('paychoice') == "monnify"){
  if(is_multisite()){
    global $wpdb, $blog_id;
    $user_query = new WP_User_Query( array( 'blog_id' => $blog_id, 'order' => 'ASC' ) );
    $resultfad = $user_query->get_results();
  }
  else{
    global $wpdb;
    $table_name = $wpdb->prefix.'users';
    $resultfad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name ORDER BY %s ASC", 'ID'));
  }
  

  if($_POST["dan_for"] == "few"){
  foreach($resultfad as $users){
	  $userid = $users->ID;

if(empty(vp_getuser($userid,"account_number")) || empty(vp_getuser($userid,"account_number1")) || empty(vp_getuser($userid,"account_number2")) || vp_getuser($userid,"account_number") == "false" || vp_getuser($userid,"account_number1") == "false" || vp_getuser($userid,"account_number2") == "false"){
	  $email = get_userdata($userid)->user_email;
	  $fun = get_userdata($userid)->user_firstname;
	  $lun = get_userdata($userid)->user_lastname;
	  $user = get_userdata($userid)->user_login;
	
if(vp_getoption('monnifytestmode') == "true" ){
	$baseurl =  "https://sandbox.monnify.com";
	$mode = "test";
}
else{
	$baseurl =  "https://api.monnify.com";
	$mode = "live";
}
	

$apikeym = vp_getoption("monnifyapikey");
$secretkeym = vp_getoption("monnifysecretkey");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v1/auth/login/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode("$apikeym:$secretkeym") 
        ],
));

$respo = curl_exec($curl);

$json = json_decode($respo)->responseBody->accessToken;


$curl = curl_init();
$code = rand(1000,100000);
curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v2/bank-transfer/reserved-accounts',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "accountReference": "'.$code.'",
    "accountName": "'.$user.'",
    "currencyCode": "NGN",
    "contractCode": "'. vp_getoption("monnifycontractcode").'",
    "customerEmail": "'.$email.'",
    "customerName": "'.$fun." ".$lun.'",
    "getAllAvailableBanks": false,
    "preferredBanks": ["035","232","50515"]
}',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $json",
    'Content-Type: application/json'
  ),
));

$respon = curl_exec($curl);

curl_close($curl);

$response = json_decode($respon,true);
if(isset($response["responseBody"]["accountReference"])){

}else{
  die("NO RESPONSE FROM MONNIFY. Please check your API KEYS and CONTRACT CODES");
}

$reference = $response["responseBody"]["accountReference"];
$customerName = $response["responseBody"]["accounts"][0]["accountName"];
$accountNumber = $response["responseBody"]["accounts"][0]["accountNumber"];
$bankName = $response["responseBody"]["accounts"][0]["bankName"];

vp_updateuser($userid,"bank_reference",$reference);
vp_updateuser($userid,"account_mode",$mode);

vp_updateuser($userid,"account_name",$customerName);
vp_updateuser($userid,"account_number",$accountNumber);
vp_updateuser($userid,"bank_name",$bankName);

if(isset($response["responseBody"]["accounts"][1]["accountName"])){
  $customerName = $response["responseBody"]["accounts"][1]["accountName"];
  $accountNumber = $response["responseBody"]["accounts"][1]["accountNumber"];
  $bankName = $response["responseBody"]["accounts"][1]["bankName"];

vp_updateuser($userid,"account_name1",$customerName);
vp_updateuser($userid,"account_number1",$accountNumber);
vp_updateuser($userid,"bank_name1",$bankName);

  }
  else{}

  
  if(isset($response["responseBody"]["accounts"][2]["accountName"])){
    $customerName = $response["responseBody"]["accounts"][2]["accountName"];
    $accountNumber = $response["responseBody"]["accounts"][2]["accountNumber"];
    $bankName = $response["responseBody"]["accounts"][2]["bankName"];

vp_updateuser($userid,"account_name2",$customerName);
vp_updateuser($userid,"account_number2",$accountNumber);
vp_updateuser($userid,"bank_name2",$bankName);

    }
    else{}
    






  }

  }
  
}
else{


  foreach($resultfad as $users){
	  $userid = $users->ID;
	  $email = get_userdata($userid)->user_email;
	  $fun = get_userdata($userid)->user_firstname;
	  $lun = get_userdata($userid)->user_lastname;
	  $user = get_userdata($userid)->user_login;
	
if(vp_getoption('monnifytestmode') == "true" ){
	$baseurl =  "https://sandbox.monnify.com";
	$mode = "test";
}
else{
	$baseurl =  "https://api.monnify.com";
	$mode = "live";
}
	

$apikeym = vp_getoption("monnifyapikey");
$secretkeym = vp_getoption("monnifysecretkey");

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v1/auth/login/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Basic ".base64_encode("$apikeym:$secretkeym") 
        ],
));

$respo = curl_exec($curl);

$json = json_decode($respo)->responseBody->accessToken;


$curl = curl_init();
$code = rand(1000,100000);
curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v2/bank-transfer/reserved-accounts',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "accountReference": "'.$code.'",
    "accountName": "'.$user.'",
    "currencyCode": "NGN",
    "contractCode": "'. vp_getoption("monnifycontractcode").'",
    "customerEmail": "'.$email.'",
    "customerName": "'.$fun." ".$lun.'",
    "getAllAvailableBanks": false,
    "preferredBanks": ["035","232","50515"]
}',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer $json",
    'Content-Type: application/json'
  ),
));

$respon = curl_exec($curl);

curl_close($curl);

$response = json_decode($respon,true);
if(isset($response["responseBody"]["accountReference"])){

}else{
  die("NO RESPONSE FROM MONNIFY. Please check your API KEYS and CONTRACT CODES");
}

$reference = $response["responseBody"]["accountReference"];
$customerName = $response["responseBody"]["accounts"][0]["accountName"];
$accountNumber = $response["responseBody"]["accounts"][0]["accountNumber"];
$bankName = $response["responseBody"]["accounts"][0]["bankName"];

vp_updateuser($userid,"bank_reference",$reference);
vp_updateuser($userid,"account_mode",$mode);

vp_updateuser($userid,"account_name",$customerName);
vp_updateuser($userid,"account_number",$accountNumber);
vp_updateuser($userid,"bank_name",$bankName);

if(isset($response["responseBody"]["accounts"][1]["accountName"])){
  $customerName = $response["responseBody"]["accounts"][1]["accountName"];
  $accountNumber = $response["responseBody"]["accounts"][1]["accountNumber"];
  $bankName = $response["responseBody"]["accounts"][1]["bankName"];

vp_updateuser($userid,"account_name1",$customerName);
vp_updateuser($userid,"account_number1",$accountNumber);
vp_updateuser($userid,"bank_name1",$bankName);

  }
  else{}

  
  if(isset($response["responseBody"]["accounts"][2]["accountName"])){
    $customerName = $response["responseBody"]["accounts"][2]["accountName"];
    $accountNumber = $response["responseBody"]["accounts"][2]["accountNumber"];
    $bankName = $response["responseBody"]["accounts"][2]["bankName"];

vp_updateuser($userid,"account_name2",$customerName);
vp_updateuser($userid,"account_number2",$accountNumber);
vp_updateuser($userid,"bank_name2",$bankName);

    }
    else{}
    






  }

}
echo "100";
}
else{
die("You Can't Use Dedicated Account Number For ".vp_getoption('paychoice')."");	
}
}
else{
die("Can't Run Dedicated Account Number Now");	
}
?>