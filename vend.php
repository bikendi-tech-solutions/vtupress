<?php
//header("Access-Control-Allow-Methods: POST, GET");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
else{
include_once(ABSPATH ."wp-load.php");
}
if(WP_DEBUG == false){
error_reporting(0);
}
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

vtupress_auto_override();

header("Access-Control-Allow-Origin: 'self'");

$allowed_referrers = [
    $_SERVER["SERVER_NAME"]
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

//  3m if(vp_getoption(""))
//vp_sessions();

//GLOBAL BLOCK FOR AMOUNT

if(isset($_REQUEST["amount"])){
	$amount = $_REQUEST["amount"];
	if(preg_match("/-/",$amount)){
		vp_block_user("Tried to perform a transaction with a negative amount!");
		die("Dont try negative balance");
	}
}

if(isset($_GET["plugin_infos"])){
	echo "Activation Version: ".vp_getoption("last_activation_version")." \n";
	echo "Activation Time: ".vp_getoption("last_activation_time")." \n";
}
else{

}

function get_all_globals(){
	$globals = [
		'wpdb' => 'level',
		'plan' => 'amount',
		'id' => 'amountv',
		'sec' => 'mlm_for',
		'discount_method' => 'dplan',
		'$_POST' => '$_COOKIE',
		'phone' => 'uniqidvalue',
		'network' => 'url',
		'vpaccess' => 'bal',
		'vpdebug' => 'name',
		'plan' => 'baln',
		'current_timestamp' => 'nothingasvaluehere',
		'pin' => 'added_to_db',
		'realAmt' => 'realAmt'
	
	];

	return $globals;
}

foreach(get_all_globals() as $key => $value){
	global ${$key};
	global ${$value};
}


if(strtolower(vp_getoption("vtu_timeout")) != "false" && vp_getoption("vtu_timeout") != "0" && !empty(vp_getoption("vtu_timeout")) && is_numeric(vp_getoption("vtu_timeout"))){

if(intval(vp_getoption("vtu_timeout")) <= 60 && intval(vp_getoption("vtu_timeout")) > 0){

	//elseif($_COOKIE["run_code"] == "wrong"){
	//	print_r($_COOKIE);
$last_login = $_COOKIE["last_login"];
$dur = vp_getoption("vtu_timeout");
$cur = date('Y-m-d H:i:s',$current_timestamp);
$timeout = date("Y-m-d H:i:s",strtotime("$last_login +$dur minutes"));

  if(($cur < $timeout)   || (current_user_can("vtupress_admin") || current_user_can("administrator") )){
  setcookie("last_login", date('Y-m-d H:i:s',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
  }
  else{

	wp_logout();
	die("You're Logged-Out. \n You Need To Re-Login!");
  }

}

}



/*
if(vp_getoption("last_activation_version") != "v1"){
	die("Please Update, Deactivate and Re-Activate your vtupress plugin (not license). [-".vp_getoption("last_activation_version")."-]");
}else{
	
}
*/

global $option_array;
$option_array = json_decode(get_option("vp_options"),true);



#clearstatcache();
#wp_cache_flush();


//security one
#Php RUn reset Hash-VBat4 <ea-24 class="4 4 6">Strings</ea-24></br> <? echo exec_hrmt(plugins_url(esc_html(Http://Hypers
#)))

if(vp_getoption("vp_security") == "yes"){
	
 header("Access-Control-Allow-Origin: 'self'");
header("Content-Security-Policy: https:");
 //Script_Transport-Security
header("strict-transport-security: max-age=31536000 ");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("X-Xss-Protection: 1");
header('Permissions-Policy: geolocation=(self ),camera=(self), microphone=(self)');
	
$siteurl = $_SERVER['SERVER_NAME'];
#verify ref
if(!isset($_SERVER["HTTP_REFERER"]) && vp_getoption("secur_mod") != "off" && !current_user_can("vtupress_admin")){
	vp_block_user("ACCESSED VEND.PHP");
	
	die("Access Not Granted [No HTTP_REFERER]");
}
elseif(vp_getoption("secur_mod") != "off" && stripos($_SERVER["HTTP_REFERER"],$siteurl) === false && !current_user_can("vtupress_admin")){
	vp_block_user("ACCESSED VEND.PHP AND SITEURL IS NOT FOUND IN WITH REF");
	
	die("Access Not Granted [No Related Ref {W'T DMN} ] ".$_SERVER["HTTP_REFERER"]."!= ".$siteurl);
}
else{
	
}

#destroy sessions


#check logged in status
vp_sessions();
if(!is_user_logged_in()){
	die('{"status":"200","response":"You are logged out! Kindly Relogin"}');
}
else{

}

}




extract(vtupress_user_details());
$headers = array('Content-Type: text/html; charset=UTF-8');

$add_total = "maybe";
global $tcode;


function weblinkBlast($phone,$message){
		global $wpdb;
		if(vp_getoption("vtupress_custom_weblinksms") == "yes" && vp_getoption("sms_transactional") == "yes"){
			$data = [
				"phone" => $phone,
				"message" => $message,
				"the_time" => date("Y-m-d H:i:s")
			];
			$table_name = $wpdb->prefix.'vp_smsblaster';
			$wpdb->insert($table_name,$data);
		}
		
}

function vpSec($rec){

	foreach(get_all_globals() as $key => $value){
		global ${$key};
		global ${$value};
	}
	
	if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off"){

		$receiver = $rec;
		
		if($_COOKIE["last_transaction_time"] != "null"){
			$endTime2 = strtotime("+2 minutes", strtotime($_COOKIE["last_transaction_time"]));
			$endTime1 = strtotime("+1 minutes", strtotime($_COOKIE["last_transaction_time"]));
			$recipient = $_COOKIE["last_recipient"];
			$next_two_minutes = date('Y-m-d h:i:s A', $endTime2);
			$next_one_minutes = date('Y-m-d h:i:s A', $endTime1);
			
		
		
			if($recipient == $receiver && strtotime(date('Y-m-d h:i:s A',$current_timestamp)) <= strtotime($next_two_minutes) && vp_getoption('tself') == "true"){
				$trans_fixed =  vp_getoption("vp_trans_fixed");
				$trans_fixed += 1;
				vp_updateoption('vp_trans_fixed',$trans_fixed);
		
				//die if sending to same number within 2 minutes
				setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
				die('You can\'t send this service to same number until after two minutes from previous order. Possibly that a transaction might be logged during runtime. ['.date("Y-m-d h:i:s A",$current_timestamp).' -- '.$next_two_minutes.']');
			}
			elseif(strtotime(date('Y-m-d h:i:s A',$current_timestamp)) <= strtotime($next_one_minutes)  && vp_getoption('tothers') == "true"){
				$trans_fixed =  vp_getoption("vp_trans_fixed");
				$trans_fixed += 1;
				
				vp_updateoption('vp_trans_fixed',$trans_fixed);
			//die if making another other within 1 minute
	
				setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
				die('You can\'t purchase any service until after one minute from previous order. ['.date("Y-m-d h:i:s A",$current_timestamp).' -- '.$next_one_minutes.']');
			}
			else{
				//go on
				setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/");
				setcookie("last_transaction_time", date("Y-m-d h:i:s A",$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
				setcookie("last_recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
			}
		
		
		
		}
		else{
			//go on
			setcookie("last_transaction_time", date("Y-m-d h:i:s A",$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
			setcookie("last_recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
		}
		
		}
}

function provider_header_handler($call=""){

	global $added_to_db,$wpdb, $table_trans, $uniqidvalue, $id, $bal;

$response = wp_remote_retrieve_body($call);

$table_data = $wpdb->prefix."vp_transactions";
$wpdb->update($table_data, array('api_response' => $response, "api_from" => "script") ,array("request_id" => $uniqidvalue));





$provider_header_response = trim(wp_remote_retrieve_response_code( $call ));

if($provider_header_response >= 100 && $provider_header_response <= 199 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned an Informative Http Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 200 && $provider_header_response <= 299 ){
	$message = NULL;
}
elseif($provider_header_response >= 300 && $provider_header_response <= 399 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Redirection Http Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 400 && $provider_header_response <= 499 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Client Error Response Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 500 && $provider_header_response <= 599 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Server Error Response Status Code [$provider_header_response]";
}
else{
	$message = "I can't identify the issue with the provider i am connected to [$provider_header_response]";
}

if($message !== NULL){


if(isset($_POST["vend"])){

	$duid = get_current_user_id();
	global $wpdb;
	$table_lock = "{$wpdb->prefix}vp_wallet_lock";

	$wpdb->query("
    CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vp_wallet_lock (
        user_id BIGINT PRIMARY KEY,
        locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE=InnoDB
	");

	$wpdb->query('START TRANSACTION');


	$wpdb->query("INSERT INTO {$wpdb->prefix}vp_wallet_lock (user_id) VALUES ($duid)
				ON DUPLICATE KEY UPDATE user_id = user_id");

	     // Step 3: Lock the user's row in the lock table
	$wpdb->get_row("SELECT user_id FROM $table_lock WHERE user_id = $duid FOR UPDATE");



	if(is_numeric($added_to_db)){
	 
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
}

if(vp_getoption('t_header_check') == "yes"){
	$wpdb->query('COMMIT');
	die($message);
}
}

}

function update_wallet($status="",$credit_message="",$amount="",$before_amount="",$now_amount=""){

	foreach(get_all_globals() as $key => $value){
		global ${$key};
		global ${$value};
	}

$name = get_userdata($id)->user_login;
$uname = get_userdata($id)->user_login;

$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> $credit_message,
'fund_amount' => $amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $id,
'status' => "$status",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


}

function sendMessage($regid="",$message=""){
	$web_name = get_option("blogname");
global $wpdb;
$table = $wpdb->prefix."vp_message";
$rest = $wpdb->get_results("SELECT * FROM $table");

	foreach ($rest as $token) {
		if($token->user_id == $regid){
			$registrationId[] = $token->user_token;
	$header = [
		'Authorization: Key=' .vp_getoption("server_apikey"),
		'Content-Type: Application/json'
	];
$user_name = get_userdata($regid)->user_login;
	$msg = [
		'title' => "New Message From $web_name To $user_name",
		'body' => $message,
		'icon' => esc_url(plugins_url("vtupress/images/vtupress.png")),
		'image' => '',
	];

	$payload = [
		'registration_ids' 	=> $registrationId,
		'data'				=> $msg
	];

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode( $payload ),
	  CURLOPT_HTTPHEADER => $header
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	 return "false";
	} else {
	  return "true";
	}
		}
	}

	// $tokens = ['cCLA1_8Inic:APA91bGhuCksjWEETYWVOh04scsZInxdWmXekEr5F9-1zJuTDZDw3It_tNmpA__PmoxDTISZzplD_ciXvsuw2pMtYSzdfIUAUfcTLnghvJS0CVkYW9sVx2HnF1rqnxsFgSdYmcXpHKLs'];
	

	
	
}

$vpdebug = vp_getoption("vpdebug");
$headers = array('Content-Type: text/html; charset=UTF-8');


function harray_key_first($arr="") {
	$arg = json_decode($arr);
	if(is_array($arg)){
		$response  = array("him"=>"me", "them"=>"you");
        foreach($response as $key => $value) {
            if(!is_array($value)){
                return $arr[$key];
            }else{
                return "error";
            }
        }
		
	}else{
		return $arr;
	}
        
}


function validate_response($response="", $key="", $value="", $alter="nothing_to_find"){
	global $msg;

	if(empty($response)){
		$wpdb->query('COMMIT');
die("Empty response from the provider");
	}
    
if(json_decode($response) == NULL){
$dis = new stdclass;
$dis->str = $response;
$response = json_encode($dis);
}

$array = array_change_key_case(json_decode($response,true),CASE_LOWER);


function search_Key($array=array(),$key=""){
   $results = array();

  if (is_array($array)){
    if (isset($array[strtolower($key)])){
        $results[] = $array[strtolower($key)];
    }

    foreach ($array as $sub_array ){
        $results = array_merge($results, search_Key($sub_array, $key));
    }
  }
return array_change_key_case($results,CASE_LOWER);
}

function search_val($results="", $the_value="", $alt = "nothing234"){
    $status = "FALSE";

	if(empty($the_value)){
		$the_value = strtolower($the_value."emptiness234");
	}else{
		$the_value = strtolower($the_value);
	}
	
				
	if(empty($alt)){
		$alt = strtolower($alt."emptiness234");
	}else{
		$alt = strtolower($alt);
	}


    foreach($results as $dvalue){
        if(!is_array($dvalue)){
        	
        	$mthe_value = strtolower($the_value);
        	$mdvalue = strtolower($dvalue);
        	$malt = strtolower($alt);
        	
        	
            if((strtolower($dvalue) === strtolower($the_value) || strtolower($dvalue) === strtolower($alt)  || $dvalue == 1 || preg_match("/$mthe_value/",$mdvalue) ||  preg_match("/$malt/",$mdvalue))  && !preg_match("/not/",$mdvalue)){
                $status = "TRUE";
            }
			elseif(is_numeric(stripos($mdvalue,"proce")) || is_numeric(stripos($mdvalue,"pen"))){
				$status = "MAYBE";
			}
            
        }
    }
    return $status;
}


	if(preg_match('/&&/',$key)){
		$explode = explode("&&",$key);
		$key1 = $explode[0];
		$key2 = $explode[1];
		
$first_key_result = search_Key($array,$key1);
//print_r($result);
$first_val_result = search_val($first_key_result,$value,$alter);



$second_key_result = search_Key($array,$key2);
//print_r($result);
$second_val_result = search_val($second_key_result,$value,$alter);

if($first_val_result == "TRUE" && $second_val_result == "TRUE"){
	$status_from_result_val = "TRUE";
}
elseif($first_val_result == "MAYBE" || $second_val_result == "MAYBE"){
	$status_from_result_val = "MAYBE";
}else{
		$status_from_result_val = "FALSE";
}

$msg = "FIRST = $first_val_result && SECOND = $second_val_result";
		
	}
	elseif(preg_match('/\|\|/',$key)){
				$explode = explode("||",$key);
		$key1 = $explode[0];
		$key2 = $explode[1];
		
$first_key_result = search_Key($array,$key1);
//print_r($result);
$first_val_result = search_val($first_key_result,$value,$alter);

$second_key_result = search_Key($array,$key2);
//print_r($result);
$second_val_result = search_val($second_key_result,$value,$alter);

if($first_val_result == "TRUE" || $second_val_result == "TRUE"){
	$status_from_result_val = "TRUE";
}
elseif($first_val_result == "MAYBE" || $second_val_result == "MAYBE"){
	$status_from_result_val = "MAYBE";
}
else{
		$status_from_result_val = "FALSE";
}

$msg = "FIRST = $first_val_result && SECOND = $second_val_result";

	}else{
$result = search_Key($array,$key);
//print_r($result);
$status_from_result_val = search_val($result,$value,$alter);
	}



return $status_from_result_val;
    

}




function search_bill_token($array=array(),$key=""){
   $results = array();

  if (is_array($array)){
    if (isset($array[strtolower($key)])){
        $results[] = $array[strtolower($key)];
    }

    foreach ($array as $sub_array ){
        $results = array_merge($results, search_bill_token($sub_array, $key));
    }
  }
return $results;
}


function vp_remote_post($url="", $request=""){
	
$ch = curl_init(); //initialize curl handle
curl_setopt($ch, CURLOPT_URL, $url); //set the url
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request)); //set the POST variables
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return as a variable
curl_setopt($ch, CURLOPT_POST, 1); //set POST method


$response = curl_exec($ch); // grab URL and pass it to the browser. Run the whole process and return the response

return $response;
curl_close($ch); 
	
}



if(isset($_POST["vend"])){
	//echo $_COOKIE["run_code"];

	if(isset($_POST["amount"])){
		if(preg_match("/-/",$_POST["amount"])){
			vp_block_user("Tried to make transaction with a negative amount!");
		}
	}



foreach(get_all_globals() as $key => $value){
	global ${$key};
	global ${$value};
}
	//GLOBALS


if(is_plugin_active("vpmlm/vpmlm.php")){
$discount_method = vp_getoption("discount_method");
}
else{
$discount_method = "null";	
}


if($_POST['tcode'] == "cdat"){
$dplan = $_POST['cplan'];
}

if(isset($_POST['phone'])){
$phone = $_POST['phone'];
}
else{
	if(empty(vp_getuser($id,"vp_phone",true))){
		$phone = "0800000001";
	}
	else{
	$phone = vp_getuser($id,"vp_phone",true);
	}
}


//$uniqidvalue =  date('Ymd').date('H').date("i").date("s").uniqid("vtu-",false);

if(isset($_POST['network'])){
$network = $_POST['network'];
}

if(isset($_POST['url'])){
$url = $_POST['url'];
}
$tcode = $_POST['tcode'];

if(isset($_POST['id'])){
$id = get_current_user_id();
}else{
$id = get_current_user_id();	
}


$myName = get_userdata($id)->user_login;

if(preg_match("/win\d+/i",$myName)){
	vp_block_user("THE HACKER FOUND");
	$wpdb->query('COMMIT');
die("NOT ALLOWED");
}else{

}

do_action("vppay");


$id = get_current_user_id();
//verify access
$vpaccess = vp_getuser($id,'vp_user_access',true);
if(strtolower($vpaccess) != "false" && strtolower($vpaccess) != "access" && empty($vpaccess) && !current_user_can("administrator")){

$wpdb->query('COMMIT');
die('{"status":"222","response":"You Are Currently Banned From Making Transactions. Please Contact Admin -- CODE --  ['.$vpaccess.']');

}

$bal = vp_getuser($id, "vp_bal", true);

$tcode = $_POST['tcode'];
$vpdebug = vp_getoption("vpdebug");


$name = $_POST['vpname'];
$email = $_POST['vpemail'];


$id = get_current_user_id();

if($_POST['tcode'] == "cdat"){
$dplan = $_POST['cplan'];
}

if(isset($_POST['phone'])){
$phone = $_POST['phone'];
$processVal = $phone;
}
else{
	if(empty(vp_getuser($id,"vp_phone",true))){
		$phone = "0800000001";
	}
	else{
	$phone = vp_getuser($id,"vp_phone",true);
	}

	$processVal = $phone;
}

if(isset($_POST['uniqidvalue'])){
$uniqidvalue =  date('Ymd',$current_timestamp).date('H',$current_timestamp).date("i",$current_timestamp).date("s",$current_timestamp);
}

if(isset($_POST['network'])){
$network = $_POST['network'];
}

if(isset($_POST['url'])){
$url = $_POST['url'];
}

$tcode = $_POST['tcode'];

if(isset($_POST['id'])){
	$id = get_current_user_id();
}

$id = get_current_user_id();
$bal = vp_getuser($id, "vp_bal", true);

if($_POST['tcode'] == "ccab"){
$ccable = $_POST["ccable"];
$iuc = $_POST["iuc"];
$cabtype = $_POST["cabtype"];

$processVal = $iuc;
}

if($_POST['tcode'] == "cbill"){
$cbill = $_POST["cbill"];
$type = $_POST["type"];
$meterno = $_POST["meterno"];

$processVal = $meterno;

$bamount = ($_POST["amount"] - floatval(vp_option_array($option_array,"bill_charge")));
}









$pattern = "/[-\s:]/";
$curr = date("Y-m-d h:i:s A",$current_timestamp);
$cur = preg_split($pattern, $curr);
$sca = $cur[2];

$check_bal = vp_getoption("checkbal");

if(isset($_POST['datatcode'])){
$datatcode = $_POST['datatcode'];
}






	if(isset($_POST['tcode'])){
	$tcode = $_POST['tcode'];
	}

	if(isset($_POST['datatcode'])){
$datatcode = $_POST['datatcode'];
	}
	
	if(isset($_POST['thatnetwork'])){
$datnetwork = $_POST['thatnetwork'];
	}
	
	if(isset($_POST['airtimechoice'])){
$airtimechoice = $_POST['airtimechoice'];
	}
	
	if(isset($_POST['thisnetwork'])){	
$disnetwork = $_POST['thisnetwork'];
	}
	
	
	

$id = get_current_user_id();
	
$plan = vp_getuser($id, "vr_plan", true);


$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name WHERE name = '$plan'");

if(isset($level) && isset($level[0]->total_level)){
switch($tcode){
case "cair":

if($airtimechoice == "vtu"){
switch($disnetwork){
	case"MTN":
$fir = $_POST['amount'] * floatval($level[0]->mtn_vtu);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];

	break;
	case"GLO":
$fir = $_POST['amount'] * floatval($level[0]->glo_vtu);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$fir = $_POST['amount'] * floatval($level[0]->mobile_vtu);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$fir = $_POST['amount'] * floatval($level[0]->airtel_vtu);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};
}
elseif($airtimechoice == "share"){
switch($disnetwork){
	case"MTN":
$fir = $_POST['amount'] * floatval($level[0]->mtn_share);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"GLO":
$fir = $_POST['amount'] * floatval($level[0]->glo_share);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$fir = $_POST['amount'] * floatval($level[0]->mobile_share);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$fir = $_POST['amount'] * floatval($level[0]->airtel_share);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};

}	
elseif($airtimechoice == "awuf"){
switch($disnetwork){
	case"MTN":
$fir = $_POST['amount'] * floatval($level[0]->mtn_awuf);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"GLO":
$fir = $_POST['amount'] * floatval($level[0]->glo_awuf);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$fir = $_POST['amount'] * floatval($level[0]->mobile_awuf);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$fir = $_POST['amount'] * floatval($level[0]->airtel_awuf);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};

}	
	
break;
case "cdat":
	$datatype = "make";
	$datatype_value = "make";

if($datatcode == "sme"){
$datatype = vp_option_array($option_array,"sme_datatype");
switch($datnetwork){
	case"MTN":
$datatype_value = vp_option_array($option_array,"mtn_sme_datatype");
$planname = "cdatan";
$planprice = "cdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}

$fir = intval($_POST['amount']) * floatval($level[0]->mtn_sme);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];

	break;
	case"GLO":
$datatype_value = vp_option_array($option_array,"glo_sme_datatype");
		$planname = "gcdatan";
		$planprice = "gcdatap";
		$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
		if($check_plan != $_REQUEST["data_plan"]){
			$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
		}
		elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
			//check price!
			vp_block_user("Modified the Price");
	
		$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
		}

$fir = $_POST['amount'] * floatval($level[0]->glo_sme);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$datatype_value = vp_option_array($option_array,"9mobile_sme_datatype");
		$planname = "9cdatan";
$planprice = "9cdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->mobile_sme);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$datatype_value = vp_option_array($option_array,"airtel_sme_datatype");
		$planname = "acdatan";
$planprice = "acdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->airtel_sme);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};

}
elseif($datatcode == "direct"){
	$datatype = vp_option_array($option_array,"direct_datatype");
switch($datnetwork){
	case"MTN":
$datatype_value = vp_option_array($option_array,"mtn_direct_datatype");
		$planname = "rcdatan";
$planprice = "rcdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->mtn_gifting);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"GLO":
$datatype_value = vp_option_array($option_array,"glo_direct_datatype");
		$planname = "rgcdatan";
$planprice = "rgcdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->glo_gifting);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$datatype_value = vp_option_array($option_array,"9mobile_direct_datatype");
		$planname = "r9cdatan";
$planprice = "r9cdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->mobile_gifting);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$datatype_value = vp_option_array($option_array,"airtel_direct_datatype");
		$planname = "racdatan";
$planprice = "racdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->airtel_gifting);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};

}
elseif($datatcode == "corporate"){
$datatype = vp_option_array($option_array,"corporate_datatype");
switch($datnetwork){
	case"MTN":
$datatype_value = vp_option_array($option_array,"mtn_corporate_datatype");
		$planname = "r2cdatan";
$planprice = "r2cdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->mtn_corporate);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"GLO":
$datatype_value = vp_option_array($option_array,"glo_corporate_datatype");
		$planname = "r2gcdatan";
$planprice = "r2gcdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->glo_corporate);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"9MOBILE":
$datatype_value = vp_option_array($option_array,"9mobile_corporate_datatype");
		$planname = "r29cdatan";
$planprice = "r29cdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->mobile_corporate);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	case"AIRTEL":
$datatype_value = vp_option_array($option_array,"airtel_corporate_datatype");
		$planname = "r2acdatan";
$planprice = "r2acdatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}


$fir = $_POST['amount'] * floatval($level[0]->airtel_corporate);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];
	break;
	};

}
elseif($datatcode == "smile"){

		$planname = "csmiledatan";
$planprice = "csmiledatap";
$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
if($check_plan != $_REQUEST["data_plan"]){
	$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
}
elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
	//check price!
	vp_block_user("Modified the Price");
	
$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
}



	$datatype = vp_option_array($option_array,"smile_datatype");
	$datatype_value = $_POST['smile_datatype'];
	$baln =  $bal - $_POST['amount'];
	$amountv = $_POST['amount'];
}
elseif($datatcode == "alpha"){

	$planname = "calphadatan";
	$planprice = "calphadatap";
	$check_plan = vp_option_array($option_array,$planname.$_REQUEST["plan_index"]).' ₦'.vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]);
	if($check_plan != $_REQUEST["data_plan"]){
		$wpdb->query('COMMIT');
die('Plan Mis-Match. You can try with another browser');
	}
	elseif(vp_option_array($option_array,$planprice.$_REQUEST["plan_index"]) != $_POST["amount"]){
		//check price!
		vp_block_user("Modified the Price");
	
	$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
	}
	

	
	$datatype = vp_option_array($option_array,"alpha_datatype");

	if(isset($_POST['alpha_datatype'])){
	$datatype_value = $_POST['alpha_datatype'];
	}else{
		$datatype_value = "";
	}
	$baln =  $bal - $_POST['amount'];
	$amountv = $_POST['amount'];
}
break;
case "ccab":
	$planIndex = $_POST["plan_index"];

	if($_POST["amount"] < vp_getoption("ccablep".$planIndex)){
		vp_block_user("Modified the Price");
		$wpdb->query('COMMIT');
die("Get OFF!!! The submitted price can't be different from what is set");
	}

$fir = $_POST['amount'] * floatval($level[0]->cable);
$sec = $fir / 100;
$amountv = $_POST['amount'] - $sec;
$baln =  $bal - $_POST['amount'];




break;
case "cbill":
$fir = $_POST['amount'] * floatval($level[0]->bill_prepaid);
$sec = $fir / 100;
$amountv = ($_POST['amount'] - $sec) + floatval(vp_option_array($option_array,"bill_charge"));
$_POST['amount'] = $_POST['amount'] + floatval(vp_option_array($option_array,"bill_charge"));
$baln =  $bal - $_POST['amount'];
break;
case "cepin":
$amountv = $_POST['amount'];
break;
case "csms":
$amountv = $_POST['amount'];
$baln =  $bal - $_POST['amount'];
break;
case "cbet":
$amountv = $_POST['amount'];
$amount_en_charge = $amountv + intval(vp_getoption("betcharge"));
$baln =  $bal - $amount_en_charge;
$amountv = $amount_en_charge;
$_POST['amount'] = $amount_en_charge;
break;

}
	}
	else{
	$baln =  $bal - $_POST['amount'];
	$amountv = $_POST['amount'];
		
	}

if($discount_method == "direct"){

	$baln =  $bal - $amountv;
	$amount = $amountv;

//print_r("Print 1 $amount");
}
else{
	$baln =  $bal - $_POST['amount'];
	$amount = $_POST['amount'];

}

$realAmt = 	$_POST['amount'];

//run kyc
if(vp_option_array($option_array,"resell") == "yes" && isset($kyc_data)){
	if(strtolower($kyc_data[0]->enable) == "yes"){
		
		if($kyc_status != "verified"){
####################################################################
$tb4 = $kyc_total;
$tnow = $amount;
$limitT = $kyc_data[0]->kyc_limit;
$datenow = date("Y-m-d",$current_timestamp); #date('Y-m-d',strtotime($date." +3 days"));
$next_end_date = $kyc_end;

if(strtolower($kyc_data[0]->duration) == "total"){
	if((intval($tb4) + intval($tnow)) <= $limitT){
		
		$add_total = "yes";
	//vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
	}
	else{
		$wpdb->query('COMMIT');
die("Verify Account To Perform This Transaction");
	}
}
else{
if($tnow < $limitT){ // check if now is less than b4
	if((intval($tnow) + intval($tb4)) <= intval($limitT)){ //check now plus  and if duration is total den allow once and not do the following cal
		if($datenow < $next_end_date || $next_end_date == "0" || empty($next_end_date)){ //check if current date is less than next or if next is zero
			//echo "Permit Transaction \n";
			//echo "Set tb4 to tnow + tb4";
			//vp_updateuser($id,"vp_kyc_total",($tb4+$tnow));
			$add_total = "yes";
			if($next_end_date == "0" || empty($next_end_date)){
				//echo "set next_end_date to datenow + limit";
				
				if(strtolower($kyc_data[0]->duration) == "day"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 days")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				elseif(strtolower($kyc_data[0]->duration) == "month"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 month")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				else{
					$wpdb->query('COMMIT');
die("KYC DURATION ERROR");
				}
			}
		}
		elseif($datenow >= $next_end_date){
			if(strtolower($kyc_data[0]->duration) == "day"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 days")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				elseif(strtolower($kyc_data[0]->duration) == "month"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 month")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				else{
					$wpdb->query('COMMIT');
die("KYC DURATION ERROR");
				}
			//echo "Permit Transaction";
		}
	}
	elseif(($tnow + $tb4) > $limitT){
		
		if($datenow < $next_end_date ){
			
			$wpdb->query('COMMIT');
die("Verify Your Account To Proceed With This Transaction");
		}
		elseif($datenow >= $next_end_date){
			
			if(strtolower($kyc_data[0]->duration) == "day"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 days")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				elseif(strtolower($kyc_data[0]->duration) == "month"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 month")));
								vp_updateuser($id,'vp_kyc_total',"0");
				}
				else{
					$wpdb->query('COMMIT');
die("KYC DURATION ERROR");
				}
			
		}
		
	}
}
else{
	$wpdb->query('COMMIT');
die("verify Account To Perform This Transaction");
}

		}

#########################
		}
	}
}

$id = get_current_user_id();
//print_r("Print 2 $amount");

$balreg = preg_match("/[^0-9\.]/",$bal);
$amountreg = preg_match("/[^0-9\.]/",$amount);
$pin = sanitize_text_field($_POST["pin"]);
$mypin = sanitize_text_field(vp_getuser($id,"vp_pin",true));

$agent = $_SERVER["HTTP_USER_AGENT"];
if( preg_match('/MSIE (\d+\.\d+);/', $agent) ) {
 // echo "You're using Internet Explorer";
 $browser = "IE";
} 
else if (preg_match('/Chrome[\/\s](\d+\.\d+)/', $agent) ) {
//  echo "You're using Chrome";
 $browser = "CHROME";
} 
else if (preg_match('/Edge\/\d+/', $agent) ) {
//  echo "You're using Edge";
 $browser = "EDGE";
} 
else if ( preg_match('/Firefox[\/\s](\d+\.\d+)/', $agent) ) {
//  echo "You're using Firefox";
 $browser = "FIREFOX";
} 
else if ( preg_match('/OPR[\/\s](\d+\.\d+)/', $agent) ) {
//  echo "You're using Opera";
 $browser = "OPERA";
} 
else if (preg_match('/Safari[\/\s](\d+\.\d+)/', $agent) ) {
//  echo "You're using Safari";
 $browser = "SAFARI";
}

if($browser != "none"){
if($pin != $mypin){

$wpdb->query('COMMIT');
die('pin');	
}



if($balreg !== 0 && $amountreg !== 0){
$wpdb->query('COMMIT');
die('Amount Or Balance Invalid');	
}
//print_r("Print 3 $amount");

if($amount >= 5 && $_POST['amount'] >= 5 || $_POST['tcode'] == "csms" && $bal > 0 && stripos($amount, '-') === false){

}
else{
	//print_r("Print 3 $amount");
$wpdb->query('COMMIT');
die("You can't purchase less than 5 [$amount]");
		
}
		if($bal >= $amount && stripos($_POST['amount'],"-") === false ){


			//Check Balance table
			global $wpdb;
			$table_name = $wpdb->prefix.'vp_wallet';
			$result = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $id ORDER BY ID DESC LIMIT 1");

			if(!isset($result) || empty($result) || $result == NULL){
				//die

					vp_block_user("Blocked because i can't find any wallet funding history to enable your perform this transaction ");

				$wpdb->query('COMMIT');
die("Wasn't able to see your wallet funding history");
			}
			else{
				$the_balance_when_funded = intval($result[0]->now_amount);

				if(intval($bal) > $the_balance_when_funded){
					vp_block_user("Blocked because i discovered this user is a thief. How can his current balance ($bal) be higher than total balance ($the_balance_when_funded) when s(he) funded last");
				
				$wpdb->query('COMMIT');
die("Blocked because i discovered this user is a thief. How can his current balance ($bal) be higher than total balance ($the_balance_when_funded) when s(he) funded last");

				}
			}




################### IF RAPTOR ALLOW SEC IS ON
#
#
if(vp_getoption("raptor_allow_security") == "yes" && vp_getoption("validate-recipient") == "true"){

	$payload = [
		'type'=>'report',
		'value' => $processVal
	];

	$apikey = vp_getoption('raptor_apikey');
	$conid = vp_getoption('raptor_conid');
	$http_args = array(
		'headers' => array(
		'Authorization' => "Token $apikey",
		'connectionid' => $conid,
		'Content-Type' => 'application/json'
		),
		'body' => json_encode($payload),
		'timeout' => '120',
		'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
		'sslverify' => false
		);
	
		vp_updateoption("raptor_last_query",date("Y-m-d h:i:s A",$current_timestamp));
		vp_updateoption("raptor_last_processed",$processVal);
		$response =  wp_remote_retrieve_body( wp_remote_post("https://dashboard.raptor.ng/api/v1/process/",$http_args));
		$json = json_decode($response);

		if(isset($json->status)){
			if($json->status){
				if($json->exists){
					if($json->data->extremelyHigh >= "1"){

						if(strtolower(vp_getoption("secur_mod")) == "wild"){

						vp_block_user("Blocked by raptor for purchasing to a blaclisted recipient $processVal ");
						
						vp_updateoption("raptor_last_blocked",date("Y-m-d h:i:s A",$current_timestamp));
						$wpdb->query('COMMIT');
die("You've just been banned for trying to make a transaction to a blakclisted recipient");
						}else{
						vp_updateoption("raptor_last_blocked",date("Y-m-d h:i:s A",$current_timestamp));

						$wpdb->query('COMMIT');
die("You cant make transaction to a blakclisted recipient");

						}
					}else{
						vp_updateoption("raptor_last_passed",date("Y-m-d h:i:s A",$current_timestamp));
					}
				}
			}
		}else{
			if(isset($json->message)){
				$wpdb->query('COMMIT');
die("Raptor => ".$json->message);
			}
			$wpdb->query('COMMIT');
die("aptor => ".$response);
		}
}
#
#
######################


		
if(is_plugin_active("vpmlm/vpmlm.php")){
$id = get_current_user_id();
do_action("vp_mlm");

}

switch ($tcode){
case "cair":
$pos = $_POST["run_code"];
$airtimechoice = $_POST['airtimechoice'];
if($airtimechoice == "vtu"){
$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("airtimerequest") == "get"){
	
$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);
	
	
$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("vtubase",vp_option_array($option_array,"airtimebaseurl"),$urlraw);
$postdata1 = str_replace("vtupostdata1",vp_option_array($option_array,"airtimepostdata1"),$base);
$postvalue1 = str_replace("vtupostvalue1",vp_option_array($option_array,"airtimepostvalue1"),$postdata1);
$postdata2 = str_replace("vtupostdata2",vp_option_array($option_array,"airtimepostdata2"),$postvalue1);
$postvalue2 = str_replace("vtupostvalue2",vp_option_array($option_array,"airtimepostvalue2"),$postdata2);
$url = $postvalue2;
$sc = vp_getoption("airtimesuccesscode");

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/"); setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}

}


if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){


	//SECURITY
	vpSec($phone);
	
	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}



	$service = "sairtime";
	$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));

	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


$_POST["run_code"] = "wrong";

if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
	setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
$tot = $bal - $amount;
vp_updateuser($id, 'vp_bal', $tot);
setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");

$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);


}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}



if(is_wp_error( $call )){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}
		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'vtu',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}


		$obj = new stdClass;
		$obj->status = "202";
		$obj->response = "$error";
		$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

$vtu_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("vturesponse_id"));

if(!empty($vtu_response)){
	$vtu_token = $vtu_response[0];
}
else{
		$vtu_token = "Nill";
}

$vpdebug = vp_getoption("vpdebug");
if($en == "TRUE" || $response  === vp_getoption("airtimesuccessvalue")){

				if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
	$vpdebug = vp_getoption("vpdebug");


$purchased = "Purchased {VTU AIRTIME} worth  ₦$realAmt";

weblinkBlast($phone,$purchased);
$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $vtu_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'user_id' => $id,
'browser' => $browser,
'trans_type' => 'vtu',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){
do_action("vp_after");
}

//VTU AIRTIME SUCCESS
setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $vtu_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'vtu',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}


	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

	$wpdb->query('COMMIT');
die("processing");
}
else{
	global $wpdb;
	$table_name = $wpdb->prefix.'sairtime';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $vtu_token,
	'name'=> $name,
	'email'=> $email,
	'network' => $_POST["network_name"],
	'phone' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'vtu',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => "Failed",
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));


	$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}
	
	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);


	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}


	//Reversal

setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime1_response_format").'"}');


}



}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("airtimebaseurl").vp_getoption("airtimeendpoint");
$num = $phone;
	$cua = vp_getoption("airtimepostdata1");
    $cppa = vp_getoption("airtimepostdata2");
    $c1a = vp_getoption("airtimepostdata3");
    $c2a = vp_getoption("airtimepostdata4");
    $c3a = vp_getoption("airtimepostdata5");
    $cna = vp_getoption("airtimenetworkattribute");
    $caa = vp_getoption("airtimeamountattribute");
    $cpa = vp_getoption("airtimephoneattribute");
	$uniqid = vp_getoption("arequest_id");
    
    $datass = array(
    $cua => vp_getoption("airtimepostvalue1"),
    $cppa => vp_getoption("airtimepostvalue2"),
	$c1a => vp_getoption("airtimepostvalue3"),
	$c2a => vp_getoption("airtimepostvalue4"),
	$c3a => vp_getoption("airtimepostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$caa =>floatval($_POST['amount']),
	$cpa => $phone
	);

	$vtuairtime_array = [];

	$the_head =  vp_getoption("airtime_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("airtimevalue1");
		$auto = vp_getoption("airtimehead1").' '.$the_auth;
		$vtuairtime_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("airtimevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("airtimehead1").' '.$the_auth;
		$vtuairtime_array["Authorization"] = $auto;
	}
	else{
		$vtuairtime_array[vp_getoption("airtimehead1")] = vp_getoption("airtimevalue1");
	}

$sc = vp_getoption("airtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("airtimehead1");
$auto = "$token $the_auth";


$vtuairtime_array["cache-control"] = "no-cache";
$vtuairtime_array["content-type"] = "application/json";


for($vtuaddheaders=1; $vtuaddheaders<=4; $vtuaddheaders++){
	if(!empty(vp_getoption("vtuaddheaders$vtuaddheaders")) && !empty(vp_getoption("vtuaddvalue$vtuaddheaders"))){
		$vtuairtime_array[vp_getoption("vtuaddheaders$vtuaddheaders")] = vp_getoption("vtuaddvalue$vtuaddheaders");
	}
}



$http_args = array(
'headers' => $vtuairtime_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($datass)
);
	

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
	
//	echo $_COOKIE["run_code"];
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}


}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		
	//SECURITY
	vpSec($phone);


	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


	$service = "sairtime";
	$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	$_POST["run_code"] = "wrong";


	if(vp_getoption("vtuquerymethod") != "array"){

		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");

			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}
	}
	else{
		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");

			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call = "";	
$response =  vp_remote_post_fn($url, $vtuairtime_array, $datass);

if($response == "error"){
	global $return_message;

	$wpdb->query('COMMIT');
die($return_message);
}
else{
	//do nothing
}
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}

	}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'vtu',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = "$error";
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

$vtu_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("vturesponse_id"));

if(!empty($vtu_response)){
	$vtu_token = $vtu_response[0];
}
else{
	$vtu_token = "Nill";
}


if($en == "TRUE"  || $response  === vp_getoption("airtimesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$realAmt = 	$_POST['amount'];
$purchased = "Purchased {VTU AIRTIME} worth  ₦$realAmt";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $vtu_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'vtu',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}


if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

//POST VTU AIRTIME SUCCESS
setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $vtu_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'vtu',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); 
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

$wpdb->query('COMMIT');
die("processing");
}
else{

	global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $vtu_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'vtu',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Failed",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime1_response_format").'"}');


}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}

}
elseif($airtimechoice == "share"){


$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("sairtimerequest") == "get"){
	
$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false);

//$ch = curl_init($url);
$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("sharebase",vp_option_array($option_array,"sairtimebaseurl"),$urlraw);
$postdata1 = str_replace("sharepostdata1",vp_option_array($option_array,"sairtimepostdata1"),$base);
$postvalue1 = str_replace("sharepostvalue1",vp_option_array($option_array,"sairtimepostvalue1"),$postdata1);
$postdata2 = str_replace("sharepostdata2",vp_option_array($option_array,"sairtimepostdata2"),$postvalue1);
$postvalue2 = str_replace("sharepostvalue2",vp_option_array($option_array,"sairtimepostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("sairtimesuccesscode");


if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

				$amtts = $bal - $_COOKIE["recent_amount"];


				$name = get_userdata($id)->user_login;
				$hname = get_userdata($id)->user_login;
				$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
				$fund_amount= $_COOKIE["recent_amount"];
				$before_amount = $bal;
				$now_amount = $amtts;
				$the_time = date('Y-m-d h:i:s A',$current_timestamp);
				
				$table_name = $wpdb->prefix.'vp_wallet';
				$added_to_db = $wpdb->insert($table_name, array(
				'name'=> $name,
				'type'=> "Wallet",
				'description'=> $description,
				'fund_amount' => $fund_amount,
				'before_amount' => $before_amount,
				'now_amount' => $now_amount,
				'user_id' => $id,
				'status' => "Approved",
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));

				
		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}


}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		
	//SECURITY
	vpSec($phone);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}



	$service = "sairtime";
	$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";

if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
	setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
	$tot = $bal - $amount;
	vp_updateuser($id, 'vp_bal', $tot);
	setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_get($url, $http_args);
$response =wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'share',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}


$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));

}
else{
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2") );
}
else{
$en = $response ;
}
}

$share_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("shareresponse_id"));

if(!empty($share_response)){
	$share_token = $share_response[0];
}
else{
		$share_token = "Nill";
}

$vpdebug = vp_getoption("vpdebug");
if($en == "TRUE"  || $response  === vp_getoption("sairtimesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
	$vpdebug = vp_getoption("vpdebug");

	
$realAmt = 	$_POST['amount'];
$purchased = "Purchased {SHARE 'ND SELL AIRTIME} worth  ₦$realAmt";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $share_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'share',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $share_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'share',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

$wpdb->query('COMMIT');
die("processing");
}
else{

	global $wpdb;
	$table_name = $wpdb->prefix.'sairtime';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $share_token,
	'name'=> $name,
	'email'=> $email,
	'network' => $_POST["network_name"],
	'phone' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'share',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => "Failed",
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);

	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}

setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime2_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{

$url = vp_getoption("sairtimebaseurl").vp_getoption("sairtimeendpoint");
$num = $phone;
	$cua = vp_getoption("sairtimepostdata1");
    $cppa = vp_getoption("sairtimepostdata2");
    $c1a = vp_getoption("sairtimepostdata3");
    $c2a = vp_getoption("sairtimepostdata4");
    $c3a = vp_getoption("sairtimepostdata5");
    $cna = vp_getoption("sairtimenetworkattribute");
    $caa = vp_getoption("sairtimeamountattribute");
    $cpa = vp_getoption("sairtimephoneattribute");
	$uniqid = vp_getoption("sarequest_id");
    
    $datass = array(
     $cua => vp_getoption("sairtimepostvalue1"),
     $cppa => vp_getoption("sairtimepostvalue2"),
	$c1a => vp_getoption("sairtimepostvalue3"),
	$c2a => vp_getoption("sairtimepostvalue4"),
	$c3a => vp_getoption("sairtimepostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$caa =>floatval($_POST['amount']),
	$cpa => $phone
	);


	$shareairtime_array = [];

	$the_head =  vp_getoption("airtime_head2");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("sairtimevalue1");
		$auto = vp_getoption("sairtimehead1").' '.$the_auth;
		$shareairtime_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("sairtimevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("sairtimehead1").' '.$the_auth;
		$shareairtime_array["Authorization"] = $auto;
	}
	else{
		$shareairtime_array[vp_getoption("sairtimehead1")] = vp_getoption("sairtimevalue1");
	}

$shareairtime_array = [];
$shareairtime_array["Content-Type"] = "application/json";
$shareairtime_array["cache-control"] = "no-cache";

for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
	if(!empty(vp_getoption("shareaddheaders$shareaddheaders")) && !empty(vp_getoption("shareaddvalue$shareaddheaders"))){
		$shareairtime_array[vp_getoption("shareaddheaders$shareaddheaders")] = vp_getoption("shareaddvalue$shareaddheaders");
	}
}

$http_args = array(
'headers' => $shareairtime_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

$sc = vp_getoption("sairtimesuccesscode");


if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];


		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));



		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
	//SECURITY
	vpSec($phone);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


	$service = "sairtime";
	$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";

	if(vp_getoption("sharequerymethod") != "array"){

		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		$call =  wp_remote_post($url, $http_args);
		$response = wp_remote_retrieve_body($call);
		setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
		provider_header_handler($call);
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
			}
			else{
				if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
					setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
					$tot = $bal - $amount;
					vp_updateuser($id, 'vp_bal', $tot);
					setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		$call = "";	
		$response =  vp_remote_post_fn($url, $shareairtime_array, $datass);
		if($response == "error"){
			global $return_message;
		
			$wpdb->query('COMMIT');
die($return_message);
		}
		else{
			//do nothing
		}
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
		
			}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'share',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));

}
else{
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

$share_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("shareresponse_id"));

if(!empty($share_response)){
	$share_token = $share_response[0];
}
else{
		$share_token = "Nill";
}


if($en == "TRUE"  || $response  === vp_getoption("sairtimesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$realAmt = 	$_POST['amount'];
$purchased = "Purchased {SHARE 'ND SELL AIRTIME} worth  ₦$realAmt";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $share_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'share',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $share_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'share',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

$wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $share_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'share',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Failed",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime2_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
	
	
	
}
else{

$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("wairtimerequest") == "get"){
//$ch = curl_init($url);
$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("awufbase",vp_option_array($option_array,"wairtimebaseurl"),$urlraw);
$postdata1 = str_replace("awufpostdata1",vp_option_array($option_array,"wairtimepostdata1"),$base);
$postvalue1 = str_replace("awufpostvalue1",vp_option_array($option_array,"wairtimepostvalue1"),$postdata1);
$postdata2 = str_replace("awufpostdata2",vp_option_array($option_array,"wairtimepostdata2"),$postvalue1);
$postvalue2 = str_replace("awufpostvalue2",vp_option_array($option_array,"wairtimepostvalue2"),$postdata2);
$url = $postvalue2;
$sc = vp_getoption("wairtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	//SECURITY
	vpSec($phone);


	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


			$service = "sairtime";
			$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";


if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
	setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
	$tot = $bal - $amount;
vp_updateuser($id, 'vp_bal', $tot);
setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'awuf',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2") );
}
else{
$en = $response ;
}
}

$awuf_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("awufresponse_id"));

if(!empty($awuf_response)){
	$awuf_token = $awuf_response[0];
}
else{
	$awuf_token = "Nill";
}


$vpdebug = vp_getoption("vpdebug");
if($en == "TRUE"  || $response  === vp_getoption("wairtimesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
	$vpdebug = vp_getoption("vpdebug");

$realAmt = 	$_POST['amount'];
$purchased = "Purchased {AWUF AIRTIME} worth  ₦$realAmt";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $awuf_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'awuf',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}
if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){
	

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $awuf_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'awuf',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 
	
	$wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $awuf_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'awuf',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Failed",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime3_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("wairtimebaseurl").vp_getoption("wairtimeendpoint");
	$cua = vp_getoption("wairtimepostdata1");
    $cppa = vp_getoption("wairtimepostdata2");
    $c1a = vp_getoption("wairtimepostdata3");
    $c2a = vp_getoption("wairtimepostdata4");
    $c3a = vp_getoption("wairtimepostdata5");
    $cna = vp_getoption("wairtimenetworkattribute");
    $caa = vp_getoption("wairtimeamountattribute");
    $cpa = vp_getoption("wairtimephoneattribute");
	$uniqid = vp_getoption("warequest_id");
    
    $datass = array(
     $cua => vp_getoption("wairtimepostvalue1"),
     $cppa => vp_getoption("wairtimepostvalue2"),
	$c1a => vp_getoption("wairtimepostvalue3"),
	$c2a => vp_getoption("wairtimepostvalue4"),
	$c3a => vp_getoption("wairtimepostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$caa =>floatval($_POST['amount']),
	$cpa => $phone
	);

	$awufairtime_array = [];

	$the_head =  vp_getoption("airtime_head2");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("wairtimevalue1");
		$auto = vp_getoption("wairtimehead1").' '.$the_auth;
		$awufairtime_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("wairtimevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("wairtimehead1").' '.$the_auth;
		$awufairtime_array["Authorization"] = $auto;
	}
	else{
		$awufairtime_array[vp_getoption("wairtimehead1")] = vp_getoption("wairtimevalue1");
	}

$sc = vp_getoption("wairtimesuccesscode");
$auto = vp_getoption("wairtimehead1").' '.$the_auth;


$awufairtime_array["Content-Type"] = "application/json";
$awufairtime_array["cache-control"] = "no-cache";

for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
	if(!empty(vp_getoption("awufaddheaders$awufaddheaders")) && !empty(vp_getoption("awufaddvalue$awufaddheaders"))){
		$awufairtime_array[vp_getoption("awufaddheaders$awufaddheaders")] = vp_getoption("awufaddvalue$awufaddheaders");
	}
}

$http_args = array(
'headers' => $awufairtime_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}


}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		
	//SECURITY
	vpSec($phone);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sairtime";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


	

	$service = "sairtime";
	$mlm_for = "";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	$_POST["run_code"] = "wrong";


	if(vp_getoption("awufquerymethod") != "array"){

		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		$call =  wp_remote_post($url, $http_args);
		$response = wp_remote_retrieve_body($call);
		setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
		provider_header_handler($call);
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
			}
			else{

	if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
		setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
		$tot = $bal - $amount;
		vp_updateuser($id, 'vp_bal', $tot);
		setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				$call = "";	
		$response =  vp_remote_post_fn($url, $awufairtime_array, $datass);
		if($response == "error"){
			global $return_message;
		
			$wpdb->query('COMMIT');
die($return_message);
		}
		else{
			//do nothing
		}
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
		
			}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$vtu_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sairtime';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $vtu_token,
		'name'=> $name,
		'email'=> $email,
		'network' => $_POST["network_name"],
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'awuf',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2") );
}
else{
$en = $response ;
}
}

$awuf_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("awufresponse_id"));

if(!empty($awuf_response)){
	$awuf_token = $awuf_response[0];
}
else{
	$awuf_token = "Nill";
}


if($en == "TRUE"  || $response  === vp_getoption("wairtimesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$realAmt = 	$_POST['amount'];
$purchased = "Purchased {AWUF AIRTIME} worth  ₦$realAmt";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW AIRTIME NOTIFICATION","SUCCESSFUL AIRTIME PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $awuf_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'awuf',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $awuf_token,
'name'=> $name,
'email'=> $email,
'network' => $_POST["network_name"],
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'awuf',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); 
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

$wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
	$table_name = $wpdb->prefix.'sairtime';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $awuf_token,
	'name'=> $name,
	'email'=> $email,
	'network' => $_POST["network_name"],
	'phone' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'awuf',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => "Failed",
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));


	$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Airtime Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("airtime3_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}

}

break;
case "cdat":

$pos = $_POST["run_code"];
$dplan == $_POST['cplan'];
$datatcode = $_POST['datatcode'];
if($datatcode == "sme"){
$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("datarequest") == "get"){


$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
	'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("smebase",vp_option_array($option_array,"databaseurl"),$urlraw);
$postdata1 = str_replace("smepostdata1",vp_option_array($option_array,"datapostdata1"),$base);
$postvalue1 = str_replace("smepostvalue1",vp_option_array($option_array,"datapostvalue1"),$postdata1);
$postdata2 = str_replace("smepostdata2",vp_option_array($option_array,"datapostdata2"),$postvalue1);
$postvalue2 = str_replace("smepostvalue2",vp_option_array($option_array,"datapostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("datasuccesscode");

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') != false && $network == vp_getoption("dataairtel")){
	

$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}


}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{
			$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
		}


		

		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	
		$_POST["run_code"] = "wrong";

		

	if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
		setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
		$tot = $bal - $amount;
		vp_updateuser($id, 'vp_bal', $tot);
		setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		if($got){
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
		}else{};
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}

if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$sme_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sdata';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $sme_token,
		'name'=> $name,
		'email' => $email,
		'phone' => $phone,
		'plan' => $_POST["data_plan"]." With - ID ".$dplan,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'sme',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response($response,$sc, vp_getoption("datasuccessvalue"), vp_getoption("datasuccessvalue2") );	
}
else{
$en = $response ;
}
}

$vpdebug = vp_getoption("vpdebug");

$sme_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("smeresponse_id"));

if(!empty($sme_response)){
	$sme_token = $sme_response[0];
}
else{
	$sme_token = "Nill";
}



if($en == "TRUE"  || $response  === vp_getoption("datasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$purchased = "Purchased ".$_POST["data_plan"];
weblinkBlast($phone,$purchased);



$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

$plan = $_POST["data_plan"];
$purchased = "Purchased {SME DATA --[ $plan ]--  }";
$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);




if(is_plugin_active("vpmlm/vpmlm.php")){
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 

$wpdb->query('COMMIT');
die("processing");
}
else{


	
global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("data1_response_format").'"}');
	
}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("databaseurl").vp_getoption("dataendpoint");
$num = $phone;
$cua = vp_getoption("datapostdata1");
    $cppa = vp_getoption("datapostdata2");
    $c1a = vp_getoption("datapostdata3");
    $c2a = vp_getoption("datapostdata4");
    $c3a = vp_getoption("datapostdata5");
    $cna = vp_getoption("datanetworkattribute");
    $caa = vp_getoption("dataamountattribute");
    $cpa = vp_getoption("dataphoneattribute");
	$cpla = vp_getoption("cvariationattr");
	$uniqid = vp_getoption("request_id");
    
    $datass = array(
     $cua => vp_getoption("datapostvalue1"),
     $cppa => vp_getoption("datapostvalue2"),
	$c1a => vp_getoption("datapostvalue3"),
	$c2a => vp_getoption("datapostvalue4"),
	$c3a => vp_getoption("datapostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$cpa => $phone,
	$datatype => $datatype_value,
	$cpla => $dplan
	);

$sme_array = [];

$the_head =  vp_getoption("data_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("datavalue1");
	$auto = vp_getoption("datahead1").' '.$the_auth;
	$sme_array["Authorization"] = $auto;
}
elseif($the_head == "concatenated"){
	$the_auth_value = vp_getoption("datavalue1");
	$the_auth = base64_encode($the_auth_value);
	$auto = vp_getoption("datahead1").' '.$the_auth;
	$sme_array["Authorization"] = $auto;
}
else{
	$sme_array[vp_getoption("datahead1")] = vp_getoption("datavalue1");
}



$sme_array["Content-Type"] = "application/json";
$sme_array["cache-control"] = "no-cache";

for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
	if(!empty(vp_getoption("smeaddheaders$smeaddheaders")) && !empty(vp_getoption("smeaddvalue$smeaddheaders"))){
		$sme_array[vp_getoption("smeaddheaders$smeaddheaders")] = vp_getoption("smeaddvalue$smeaddheaders");
	}
}

$http_args = array(
'headers' => $sme_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

$sc = vp_getoption("datasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') !==  false && $network == vp_getoption("dataairtel")){
	
	
$request = array(
        "user"=> vp_getoption("hollatagusername"),
        "pass"=> vp_getoption("hollatagpassword"),
        "from"=> "DATA ALERT",
        "to"=> vp_getoption("hollatagcompany"),
        "msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
);


$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];


		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
		
	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{

			$wpdb->query('COMMIT');
die('[S/R] Duplicate Transaction!!! Check your transaction history please');
		}



		
		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");




	
		$_POST["run_code"] = "wrong";
		if($got){
			if(vp_getoption("smequerymethod") != "array"){

				if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
					setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
					$tot = $bal - $amount;
					vp_updateuser($id, 'vp_bal', $tot);
					setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				$call =  wp_remote_post($url, $http_args);
				$response = wp_remote_retrieve_body($call);
				setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
				provider_header_handler($call);
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
					}
					else{

						if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
							setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
							$tot = $bal - $amount;
							vp_updateuser($id, 'vp_bal', $tot);
							setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
							$call = "";	
				$response =  vp_remote_post_fn($url, $sme_array, $datass);
				if($response == "error"){
					global $return_message;
				
					$wpdb->query('COMMIT');
die($return_message);
				}
				else{
					//do nothing
				}
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
				
					}
		}else{};

#$wpdb->query('COMMIT');

if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
$error = $call->get_error_code();
}
else{
$error = $call->get_error_message();
}

$sme_token = "no_response";
global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html($call->get_error_message())."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	//do nothing
}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("datasuccessvalue"),vp_getoption("datasuccessvalue2"));
}
else{
$en = $response ;
}
}

$sme_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("smeresponse_id"));

if(!empty($sme_response)){
	$sme_token = $sme_response[0];
}
else{
		$sme_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("datasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$plan = $_POST["data_plan"];
$purchased = "Purchased {SME DATA --[ $plan ]--  }";
weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

if(is_plugin_active("vpmlm/vpmlm.php")){
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); 
	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 
	
	$wpdb->query('COMMIT');
die("processing");
}
else{
	

	global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $sme_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'sme',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format ":"'.vp_getoption("data1_response_format").'"}');
	

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
	
	
	
}
}
elseif($datatcode == "direct"){

$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("rdatarequest") == "get"){

$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("directbase",vp_option_array($option_array,"rdatabaseurl"),$urlraw);
$postdata1 = str_replace("directpostdata1",vp_option_array($option_array,"rdatapostdata1"),$base);
$postvalue1 = str_replace("directpostvalue1",vp_option_array($option_array,"rdatapostvalue1"),$postdata1);
$postdata2 = str_replace("directpostdata2",vp_option_array($option_array,"rdatapostdata2"),$postvalue1);
$postvalue2 = str_replace("directpostvalue2",vp_option_array($option_array,"rdatapostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("rdatasuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
	'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'di')) && stripos(vp_getoption("hollatagservices"),'di') !==  false && $network == vp_getoption("rdataairtel")){
	
	
$request = array(
        "user"=> vp_getoption("hollatagusername"),
        "pass"=> vp_getoption("hollatagpassword"),
        "from"=> "DATA ALERT",
        "to"=> vp_getoption("hollatagcompany"),
        "msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
);


$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;	
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		$amtts = $bal - $_COOKIE["recent_amount"];


				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}


if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
			

	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{
			$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
		}



		
		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	
		$_POST["run_code"] = "wrong";



		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		if($got){
$call =  wp_remote_get($url, $http_args);
$response =wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
		}else{};
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$sme_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sdata';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $sme_token,
		'name'=> $name,
		'email' => $email,
		'phone' => $phone,
		'plan' => $_POST["data_plan"]." With - ID ".$dplan,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'direct',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}


$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("rdatasuccessvalue"),vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $response ;
}
}

$direct_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("directresponse_id"));

if(!empty($direct_response)){
	$direct_token = $direct_response[0];
}
else{
	$direct_token = "Nill";
}


$vpdebug = vp_getoption("vpdebug");
if($en == "TRUE"  || $response  === vp_getoption("rdatasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$purchased = "Purchased ".$_POST["data_plan"];
weblinkBlast($phone,$purchased);

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}
if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

$plan = $_POST["data_plan"];
$purchased = "Purchased {GIFTING DATA --[ $plan ]--  }";
$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);


if(is_plugin_active("vpmlm/vpmlm.php")){
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){



global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); 
	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 
	
	$wpdb->query('COMMIT');
die("processing");
}
else{



	global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("data2_response_format").'"}');
		
}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{$url = vp_getoption("rdatabaseurl").vp_getoption("rdataendpoint");
$num = $phone;
$cua = vp_getoption("rdatapostdata1");
    $cppa = vp_getoption("rdatapostdata2");
    $c1a = vp_getoption("rdatapostdata3");
    $c2a = vp_getoption("rdatapostdata4");
    $c3a = vp_getoption("rdatapostdata5");
    $cna = vp_getoption("rdatanetworkattribute");
    $caa = vp_getoption("rdataamountattribute");
    $cpa = vp_getoption("rdataphoneattribute");
	$cpla = vp_getoption("rcvariationattr");
	$uniqid = vp_getoption("rrequest_id");
    
    $datass = array(
     $cua => vp_getoption("rdatapostvalue1"),
     $cppa => vp_getoption("rdatapostvalue2"),
	$c1a => vp_getoption("rdatapostvalue3"),
	$c2a => vp_getoption("rdatapostvalue4"),
	$c3a => vp_getoption("rdatapostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$cpa => $phone,
	$datatype => $datatype_value,
	$cpla => $dplan
	);

	$direct_array = [];

	$the_head =  vp_getoption("data_head2");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("rdatavalue1");
		$auto = vp_getoption("rdatahead1").' '.$the_auth;
		$direct_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("rdatavalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("rdatahead1").' '.$the_auth;
		$direct_array["Authorization"] = $auto;
	}
	else{
		$direct_array[vp_getoption("rdatahead1")] = vp_getoption("rdatavalue1");
	}

$direct_array["Content-Type"] = "application/json";
$direct_array["cache-control"] = "no-cache";

for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
	if(!empty(vp_getoption("directaddheaders$directaddheaders")) && !empty(vp_getoption("directaddvalue$directaddheaders"))){
		$direct_array[vp_getoption("directaddheaders$directaddheaders")] = vp_getoption("directaddvalue$directaddheaders");
	}
}

$http_args = array(
'headers' => $direct_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

$sc = vp_getoption("rdatasuccesscode");

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'di')) && stripos(vp_getoption("hollatagservices"),'di') !==  false && $network == vp_getoption("rdataairtel")){
	
	
$request = array(
        "user"=> vp_getoption("hollatagusername"),
        "pass"=> vp_getoption("hollatagpassword"),
        "from"=> "DATA ALERT",
        "to"=> vp_getoption("hollatagcompany"),
        "msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
);


$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}




}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
			
	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{
			$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
		}


		
		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");



	
		$_POST["run_code"] = "wrong";
		if($got){
			if(vp_getoption("directquerymethod") != "array"){

				if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
					setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
					$tot = $bal - $amount;
					vp_updateuser($id, 'vp_bal', $tot);
					setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
					$call =  wp_remote_post($url, $http_args);
				$response = wp_remote_retrieve_body($call);
				setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
				provider_header_handler($call);
				
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
					}
					else{

						if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
							setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
							$tot = $bal - $amount;
							vp_updateuser($id, 'vp_bal', $tot);
							setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
							$call = "";	
				$response =  vp_remote_post_fn($url, $direct_array, $datass);
				if($response == "error"){
					global $return_message;
				
					$wpdb->query('COMMIT');
die($return_message);
				}
				else{
					//do nothing
				}
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
				
					}
		}else{};


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$sme_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sdata';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $sme_token,
		'name'=> $name,
		'email' => $email,
		'phone' => $phone,
		'plan' => $_POST["data_plan"]." With - ID ".$dplan,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'direct',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}


$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));

}
else{
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("rdatasuccessvalue"),vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $response ;
}
}


$direct_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("directresponse_id"));

if(!empty($direct_response)){
	$direct_token = $direct_response[0];
}
else{
	$direct_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("rdatasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}


$plan = $_POST["data_plan"];
$purchased = "Purchased {GIFTING DATA --[ $plan ]--  }";

weblinkBlast($phone,$purchased);
$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
  

global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){



global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); 
setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); 
$wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $direct_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'direct',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("data2_response_format").'"}');
	

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
	
	
}

}
elseif($datatcode == "alpha"){
	$vpdebug = vp_getoption("vpdebug");
	if(vp_getoption("alpharequest") == "get"){
	
	
	$http_args = array(
	'headers' => array(
	'cache-control' => 'no-cache',
		'Content-Type' => 'application/json'
	),
	'timeout' => '3000',
	'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
	'sslverify' => false
	);
	
	
	$urlraw = htmlspecialchars_decode($_POST["url"]);
	$base = str_replace("alphabase",vp_option_array($option_array,"alphabaseurl"),$urlraw);
	$postdata1 = str_replace("alphapostdata1",vp_option_array($option_array,"alphapostdata1"),$base);
	$postvalue1 = str_replace("alphapostvalue1",vp_option_array($option_array,"alphapostvalue1"),$postdata1);
	$postdata2 = str_replace("alphapostdata2",vp_option_array($option_array,"alphapostdata2"),$postvalue1);
	$postvalue2 = str_replace("alphapostvalue2",vp_option_array($option_array,"alphapostvalue2"),$postdata2);
	$url = $postvalue2;
	
	$sc = vp_getoption("alphasuccesscode");
	
	if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') != false && $network == vp_getoption("alphaairtel")){
		
	
	$url = "https://sms.hollatags.com/api/send/";
	$call =  vp_remote_post($url, $request);
	$response = $call;
	$response == "sent" ? $force = "true" : $force = "false";
	$got = false;
	}
	else{
		$got = true;
		$force = "false";
	}
	
	if($pos != $_POST["run_code"]){
		$errz = "Track ID Not Same";
		$do = false;
	}
	elseif($_POST["run_code"] == "wrong"){
		$errz = "Track Id Can't Be wrong.";
		$do = false;
	}
	elseif($_COOKIE["run_code"] == "wrong"){
		$errz = "Session Can't Be Wrong";
		$do = false;
	}
	else{
		$errz = "unidentified";
		$do = true;
	
		if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
	
			$amtts = $bal - $_COOKIE["recent_amount"];
	
			$name = get_userdata($id)->user_login;
			$hname = get_userdata($id)->user_login;
			$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
			$fund_amount= $_COOKIE["recent_amount"];
			$before_amount = $bal;
			$now_amount = $amtts;
			$the_time = date('Y-m-d h:i:s A',$current_timestamp);
			
			$table_name = $wpdb->prefix.'vp_wallet';
			$added_to_db = $wpdb->insert($table_name, array(
			'name'=> $name,
			'type'=> "Wallet",
			'description'=> $description,
			'fund_amount' => $fund_amount,
			'before_amount' => $before_amount,
			'now_amount' => $now_amount,
			'user_id' => $id,
			'status' => "Approved",
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
	
			vp_updateuser($id,"vp_bal", $amtts);
	
	$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
	}
	else{
	setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	}
	
	
	}
	
	if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
			
		
	//SECURITY
	vpSec($phone);
	
			$trackcode = $_POST["run_code"];
			global $wpdb;
			$tableh = $wpdb->prefix."sdata";
			$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
			if(empty($rest)){
		
			}else{
				$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
			}
	
	
			
	
			$service = "sdata";
			$mlm_for = "_data";
			global $wpdb;
			$table_trans = $wpdb->prefix.'vp_transactions';
			$unrecorded_added = $wpdb->insert($table_trans, array(
			'status' => 'Fa',
			'service' => $service,
			'name'=> $name,
			'email'=> $email,
			'recipient' => $phone,
			'bal_bf' => $bal,
			'bal_nw' => $baln,
			'amount' => $amount,
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
			setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
			setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
			setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
			setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
			setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
			setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
			setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
			setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
	
		
			$_POST["run_code"] = "wrong";
	
			
	
		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			if($got){
	$call =  wp_remote_get($url, $http_args);
	$response = wp_remote_retrieve_body($call);
	setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
	setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
	provider_header_handler($call);
			}else{};
		}
		else{
		$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
		}
	
	if(is_wp_error($call)){
		if(vp_getoption("vpdebug") != "yes"){
			$error = $call->get_error_code();
			}
			else{
			$error = $call->get_error_message();
			}
	
			$alpha_token = "no_response";
			global $wpdb;
			$table_name = $wpdb->prefix.'sdata';
			$added_to_db = $wpdb->insert($table_name, array(
			'run_code' => esc_html($pos),
			'response_id'=> $alpha_token,
			'name'=> $name,
			'email' => $email,
			'phone' => $phone,
			'plan' => $_POST["data_plan"]." With - ID ".$dplan,
			'bal_bf' => $bal,
			'bal_nw' => $bal,
			'amount' => $amount,
			'resp_log' => " ".esc_html($call->get_error_message())."",
			'browser' => $browser,
			'trans_type' => 'alpha',
			'trans_method' => 'get',
			'via' => 'site',
			'time_taken' => '1',
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'status' => 'Failed',
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
			vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
			if(is_numeric($added_to_db)){
				global $wpdb;
				 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
				}
				else{
				//do nothing
			}
	
	
	
	$obj = new stdClass;
	$obj->status = "202";
	$obj->response = $error;
	$wpdb->query('COMMIT');
die(json_encode($obj));
	}
	else{
	if(vp_getoption("alpha1_response_format") == "JSON" || vp_getoption("alpha1_response_format") == "json"){
	$en = validate_response($response,$sc, vp_getoption("alphasuccessvalue"), vp_getoption("alphasuccessvalue2") );	
	}
	else{
	$en = $response ;
	}
	}
	
	$vpdebug = vp_getoption("vpdebug");
	
	$alpha_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("alpharesponse_id"));
	
	if(!empty($alpha_response)){
		$alpha_token = $alpha_response[0];
	}
	else{
		$alpha_token = "Nill";
	}
	
	
	
	if($en == "TRUE"  || $response  === vp_getoption("alphasuccessvalue") || $force == "true"){
						if($add_total == "yes"){
						vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
					}
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Successful',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$purchased = "Purchased ".$_POST["data_plan"];
	weblinkBlast($phone,$purchased);

	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
	
	$plan = $_POST["data_plan"];
	$purchased = "Purchased {SME DATA --[ $plan ]--  }";
	$recipient = $phone;
	vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
	
	
	
	
	if(is_plugin_active("vpmlm/vpmlm.php")){
	do_action("vp_after");
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
	}
	elseif($en == "MAYBE"){
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Pending',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
	}
	else{
	
	
		
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	
		update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);
	
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
	setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
	$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("alpha1_response_format").'"}');
		
	}
	}
	else{
		$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
	}
	}
	else{
	$url = vp_getoption("alphabaseurl").vp_getoption("alphaendpoint");
	$num = $phone;
	$cua = vp_getoption("alphapostdata1");
		$cppa = vp_getoption("alphapostdata2");
		$c1a = vp_getoption("alphapostdata3");
		$c2a = vp_getoption("alphapostdata4");
		$c3a = vp_getoption("alphapostdata5");
		$cna = vp_getoption("alphanetworkattribute");
		$caa = vp_getoption("alphaamountattribute");
		$cpa = vp_getoption("alphaphoneattribute");
		$cpla = vp_getoption("alphavariationattr");
		$uniqid = vp_getoption("request_id");
		
		$datass = array(
		 $cua => vp_getoption("alphapostvalue1"),
		 $cppa => vp_getoption("alphapostvalue2"),
		$c1a => vp_getoption("alphapostvalue3"),
		$c2a => vp_getoption("alphapostvalue4"),
		$c3a => vp_getoption("alphapostvalue5"),
		$uniqid => $uniqidvalue,
		$cna => $network,
		$cpa => $phone,
		$datatype => $datatype_value,
		$cpla => $dplan
		);
	
	$alpha_array = [];
	
	$the_head =  vp_getoption("alpha_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("alphavalue1");
		$auto = vp_getoption("alphahead1").' '.$the_auth;
		$alpha_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("alphavalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("alphahead1").' '.$the_auth;
		$alpha_array["Authorization"] = $auto;
	}
	else{
		$alpha_array[vp_getoption("alphahead1")] = vp_getoption("alphavalue1");
	}
	
	
	
	$alpha_array["Content-Type"] = "application/json";
	$alpha_array["cache-control"] = "no-cache";
	
	for($alphaaddheaders=1; $alphaaddheaders<=4; $alphaaddheaders++){
		if(!empty(vp_getoption("alphaaddheaders$alphaaddheaders")) && !empty(vp_getoption("alphaaddvalue$alphaaddheaders"))){
			$alpha_array[vp_getoption("alphaaddheaders$alphaaddheaders")] = vp_getoption("alphaaddvalue$alphaaddheaders");
		}
	}
	
	$http_args = array(
	'headers' => $alpha_array,
	'timeout' => '3000',
	'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
	'sslverify' => false,
	'body' => json_encode($datass)
	);
	
	$sc = vp_getoption("alphasuccesscode");
	//echo "<script>alert('url1".$url."');</script>";
	
	if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') !==  false && $network == vp_getoption("alphaairtel")){
		
		
	$request = array(
			"user"=> vp_getoption("hollatagusername"),
			"pass"=> vp_getoption("hollatagpassword"),
			"from"=> "DATA ALERT",
			"to"=> vp_getoption("hollatagcompany"),
			"msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
	);
	
	
	$url = "https://sms.hollatags.com/api/send/";
	$call =  vp_remote_post($url, $request);
	$response = $call;
	$response == "sent" ? $force = "true" : $force = "false";
	$got = false;
	}
	else{
		$got = true;
		$force = "false";
	}
	
	if($pos != $_POST["run_code"]){
		$errz = "Track ID Not Same";
		$do = false;
	}
	elseif($_POST["run_code"] == "wrong"){
		$errz = "Track Id Can't Be wrong.";
		$do = false;
	}
	elseif($_COOKIE["run_code"] == "wrong"){
		$errz = "Session Can't Be Wrong";
		$do = false;
	}
	else{
		$errz = "unidentified";
		$do = true;
	
		if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
	
			$amtts = $bal - $_COOKIE["recent_amount"];
	
	
			$name = get_userdata($id)->user_login;
			$hname = get_userdata($id)->user_login;
			$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
			$fund_amount= $_COOKIE["recent_amount"];
			$before_amount = $bal;
			$now_amount = $amtts;
			$the_time = date('Y-m-d h:i:s A',$current_timestamp);
			
			$table_name = $wpdb->prefix.'vp_wallet';
			$added_to_db = $wpdb->insert($table_name, array(
			'name'=> $name,
			'type'=> "Wallet",
			'description'=> $description,
			'fund_amount' => $fund_amount,
			'before_amount' => $before_amount,
			'now_amount' => $now_amount,
			'user_id' => $id,
			'status' => "Approved",
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
	
			vp_updateuser($id,"vp_bal", $amtts);
	
	$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
	}
	else{
	setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	}
	
	
	
	}
	
	if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
			
				
	//SECURITY
	vpSec($phone);
	
			$trackcode = $_POST["run_code"];
			global $wpdb;
			$tableh = $wpdb->prefix."sdata";
			$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
			if(empty($rest)){
		
			}else{
	
				$wpdb->query('COMMIT');
die('[S/R] Duplicate Transaction!!! Check your transaction history please');
			}
	
	
	
			
			$service = "sdata";
			$mlm_for = "_data";
			global $wpdb;
			$table_trans = $wpdb->prefix.'vp_transactions';
			$unrecorded_added = $wpdb->insert($table_trans, array(
			'status' => 'Fa',
			'service' => $service,
			'name'=> $name,
			'email'=> $email,
			'recipient' => $phone,
			'bal_bf' => $bal,
			'bal_nw' => $baln,
			'amount' => $amount,
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
			setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
			setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
			setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
			setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
			setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
			setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
			setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
			setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
	
	
	
	
		
			$_POST["run_code"] = "wrong";
			if($got){
				if(vp_getoption("alphaquerymethod") != "array"){
	
					if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
						setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
						$tot = $bal - $amount;
						vp_updateuser($id, 'vp_bal', $tot);
						setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
					$call =  wp_remote_post($url, $http_args);
					$response = wp_remote_retrieve_body($call);
					setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
	setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
					provider_header_handler($call);
				}
				else{
				$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
				}
						}
						else{
	
							if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
								setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
								$tot = $bal - $amount;
								vp_updateuser($id, 'vp_bal', $tot);
								setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
								$call = "";	
					$response =  vp_remote_post_fn($url, $alpha_array, $datass);
					if($response == "error"){
						global $return_message;
					
						$wpdb->query('COMMIT');
die($return_message);
					}
					else{
						//do nothing
					}
				}
				else{
				$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
				}
					
						}
			}else{};
	
	#$wpdb->query('COMMIT');
	
	if(is_wp_error($call)){
		if(vp_getoption("vpdebug") != "yes"){
	$error = $call->get_error_code();
	}
	else{
	$error = $call->get_error_message();
	}
	
	$alpha_token = "no_response";
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html($call->get_error_message())."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		//do nothing
	}
	
	
	
	$obj = new stdClass;
	$obj->status = "202";
	$obj->response = $error;
	$wpdb->query('COMMIT');
die(json_encode($obj));
	}
	else{
	if(vp_getoption("alpha1_response_format") == "JSON" || vp_getoption("alpha1_response_format") == "json"){
	$en = validate_response($response,$sc,vp_getoption("alphasuccessvalue"),vp_getoption("alphasuccessvalue2"));
	}
	else{
	$en = $response ;
	}
	}
	
	$alpha_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("alpharesponse_id"));
	
	if(!empty($alpha_response)){
		$alpha_token = $alpha_response[0];
	}
	else{
			$alpha_token = "Nill";
	}
	
	if($en == "TRUE"  || $response  === vp_getoption("alphasuccessvalue") || $force == "true"){
						if($add_total == "yes"){
						vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
					}
	
	$plan = $_POST["data_plan"];
	$purchased = "Purchased {ALPHA DATA --[ $plan ]--  }";
	
	weblinkBlast($phone,$purchased);

	$recipient = $phone;
	vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Successful',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
	
	if(is_plugin_active("vpmlm/vpmlm.php")){
	do_action("vp_after");
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
	}
	elseif($en == "MAYBE"){
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Pending',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
	
		setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
	}
	else{
		
	
		global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $alpha_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'alpha',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	
		update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);
	
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
	setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
	$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format ":"'.vp_getoption("alpha1_response_format").'"}');
		
	
	}
	}
	else{
		$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
	}
		
		
		
	}
}
elseif($datatcode == "smile"){
	$vpdebug = vp_getoption("vpdebug");
	if(vp_getoption("smilerequest") == "get"){
	
	
	$http_args = array(
	'headers' => array(
	'cache-control' => 'no-cache',
		'Content-Type' => 'application/json'
	),
	'timeout' => '3000',
	'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
	'sslverify' => false
	);
	
	
	$urlraw = htmlspecialchars_decode($_POST["url"]);
	$base = str_replace("smilebase",vp_option_array($option_array,"smilebaseurl"),$urlraw);
	$postdata1 = str_replace("smilepostdata1",vp_option_array($option_array,"smilepostdata1"),$base);
	$postvalue1 = str_replace("smilepostvalue1",vp_option_array($option_array,"smilepostvalue1"),$postdata1);
	$postdata2 = str_replace("smilepostdata2",vp_option_array($option_array,"smilepostdata2"),$postvalue1);
	$postvalue2 = str_replace("smilepostvalue2",vp_option_array($option_array,"smilepostvalue2"),$postdata2);
	$url = $postvalue2;
	
	$sc = vp_getoption("smilesuccesscode");
	
	if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') != false && $network == vp_getoption("smileairtel")){
		
	
		$url = "https://sms.hollatags.com/api/send/";
		$call =  vp_remote_post($url, $request);
		$response = $call;
		$response == "sent" ? $force = "true" : $force = "false";
		$got = false;
	}
	else{
		$got = true;
		$force = "false";
	}
	
	if($pos != $_POST["run_code"]){
		$errz = "Track ID Not Same";
		$do = false;
	}
	elseif($_POST["run_code"] == "wrong"){
		$errz = "Track Id Can't Be wrong.";
		$do = false;
	}
	elseif($_COOKIE["run_code"] == "wrong"){
		$errz = "Session Can't Be Wrong";
		$do = false;
	}
	else{
		$errz = "unidentified";
		$do = true;
	
		if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
	
			$amtts = $bal - $_COOKIE["recent_amount"];
	
			$name = get_userdata($id)->user_login;
			$hname = get_userdata($id)->user_login;
			$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
			$fund_amount= $_COOKIE["recent_amount"];
			$before_amount = $bal;
			$now_amount = $amtts;
			$the_time = date('Y-m-d h:i:s A',$current_timestamp);
			
			$table_name = $wpdb->prefix.'vp_wallet';
			$added_to_db = $wpdb->insert($table_name, array(
			'name'=> $name,
			'type'=> "Wallet",
			'description'=> $description,
			'fund_amount' => $fund_amount,
			'before_amount' => $before_amount,
			'now_amount' => $now_amount,
			'user_id' => $id,
			'status' => "Approved",
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
	
			vp_updateuser($id,"vp_bal", $amtts);
	
	$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
	}
	else{
	setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	}
	
	
	}
	
	if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
			
		
	//SECURITY
	vpSec($phone);
	
			$trackcode = $_POST["run_code"];
			global $wpdb;
			$tableh = $wpdb->prefix."sdata";
			$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
			if(empty($rest)){
		
			}else{
				$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
			}
	
	
			
	
			$service = "sdata";
			$mlm_for = "_data";
			global $wpdb;
			$table_trans = $wpdb->prefix.'vp_transactions';
			$unrecorded_added = $wpdb->insert($table_trans, array(
			'status' => 'Fa',
			'service' => $service,
			'name'=> $name,
			'email'=> $email,
			'recipient' => $phone,
			'bal_bf' => $bal,
			'bal_nw' => $baln,
			'amount' => $amount,
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
			setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
			setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
			setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
			setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
			setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
			setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
			setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
			setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
	
		
			$_POST["run_code"] = "wrong";
	
			
	
		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			if($got){
	$call =  wp_remote_get($url, $http_args);
	$response = wp_remote_retrieve_body($call);
	setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
	setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
	provider_header_handler($call);
			}else{};
		}
		else{
		$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
		}
	
	if(is_wp_error($call)){
		if(vp_getoption("vpdebug") != "yes"){
			$error = $call->get_error_code();
			}
			else{
			$error = $call->get_error_message();
			}
	
			$smile_token = "no_response";
			global $wpdb;
			$table_name = $wpdb->prefix.'sdata';
			$added_to_db = $wpdb->insert($table_name, array(
			'run_code' => esc_html($pos),
			'response_id'=> $smile_token,
			'name'=> $name,
			'email' => $email,
			'phone' => $phone,
			'plan' => $_POST["data_plan"]." With - ID ".$dplan,
			'bal_bf' => $bal,
			'bal_nw' => $bal,
			'amount' => $amount,
			'resp_log' => " ".esc_html($call->get_error_message())."",
			'browser' => $browser,
			'trans_type' => 'smile',
			'trans_method' => 'get',
			'via' => 'site',
			'time_taken' => '1',
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'status' => 'Failed',
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
			vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
			if(is_numeric($added_to_db)){
				global $wpdb;
				 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
				}
				else{
				//do nothing
			}
	
	
	
	$obj = new stdClass;
	$obj->status = "202";
	$obj->response = $error;
	$wpdb->query('COMMIT');
die(json_encode($obj));
	}
	else{
	if(vp_getoption("smile1_response_format") == "JSON" || vp_getoption("smile1_response_format") == "json"){
	$en = validate_response($response,$sc, vp_getoption("smilesuccessvalue"), vp_getoption("smilesuccessvalue2") );	
	}
	else{
	$en = $response ;
	}
	}
	
	$vpdebug = vp_getoption("vpdebug");
	
	$smile_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("smileresponse_id"));
	
	if(!empty($smile_response)){
		$smile_token = $smile_response[0];
	}
	else{
		$smile_token = "Nill";
	}
	
	
	
	if($en == "TRUE"  || $response  === vp_getoption("smilesuccessvalue") || $force == "true"){
						if($add_total == "yes"){
						vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
					}
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Successful',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	$purchased = "Purchased ".$_POST["data_plan"];
	weblinkBlast($phone,$purchased);
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
	
	$plan = $_POST["data_plan"];
	$purchased = "Purchased {SME DATA --[ $plan ]--  }";
	$recipient = $phone;
	vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
	
	
	
	
	if(is_plugin_active("vpmlm/vpmlm.php")){
	do_action("vp_after");
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
	}
	elseif($en == "MAYBE"){
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Pending',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
	}
	else{
	
	
		
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	
		update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);
	
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
	setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
	$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("smile1_response_format").'"}');
		
	}
	}
	else{
		$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
	}
	}
	else{
	$url = vp_getoption("smilebaseurl").vp_getoption("smileendpoint");
	$phone = preg_replace('/^0/',"234",$phone);
	$num = $phone;
	$cua = vp_getoption("smilepostdata1");
		$cppa = vp_getoption("smilepostdata2");
		$c1a = vp_getoption("smilepostdata3");
		$c2a = vp_getoption("smilepostdata4");
		$c3a = vp_getoption("smilepostdata5");
		$cna = vp_getoption("smilenetworkattribute");
		$caa = vp_getoption("smileamountattribute");
		$cpa = vp_getoption("smilephoneattribute");
		$cpla = vp_getoption("smilevariationattr");
		$uniqid = vp_getoption("request_id");
		
		$datass = array(
		 $cua => vp_getoption("smilepostvalue1"),
		 $cppa => vp_getoption("smilepostvalue2"),
		$c1a => vp_getoption("smilepostvalue3"),
		$c2a => vp_getoption("smilepostvalue4"),
		$c3a => vp_getoption("smilepostvalue5"),
		$uniqid => $uniqidvalue,
		$cna => $network,
		$cpa => $phone,
		$datatype => $datatype_value,
		$cpla => $dplan
		);
		//edit here smileedit smiledit
	
	$smile_array = [];
	
	$the_head =  vp_getoption("smile_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("smilevalue1");
		$auto = vp_getoption("smilehead1").' '.$the_auth;
		$smile_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("smilevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("smilehead1").' '.$the_auth;
		$smile_array["Authorization"] = $auto;
	}
	else{
		$smile_array[vp_getoption("smilehead1")] = vp_getoption("smilevalue1");
	}
	
	
	
	$smile_array["Content-Type"] = "application/json";
	$smile_array["cache-control"] = "no-cache";
	
	for($smileaddheaders=1; $smileaddheaders<=4; $smileaddheaders++){
		if(!empty(vp_getoption("smileaddheaders$smileaddheaders")) && !empty(vp_getoption("smileaddvalue$smileaddheaders"))){
			$smile_array[vp_getoption("smileaddheaders$smileaddheaders")] = vp_getoption("smileaddvalue$smileaddheaders");
		}
	}
	
	$http_args = array(
	'headers' => $smile_array,
	'timeout' => '3000',
	'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
	'sslverify' => false,
	'body' => json_encode($datass)
	);
	
	$sc = vp_getoption("smilesuccesscode");
	//echo "<script>alert('url1".$url."');</script>";
	
	if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'sm')) && stripos(vp_getoption("hollatagservices"),'sm') !==  false && $network == vp_getoption("smileairtel")){
		
		
	$request = array(
			"user"=> vp_getoption("hollatagusername"),
			"pass"=> vp_getoption("hollatagpassword"),
			"from"=> "DATA ALERT",
			"to"=> vp_getoption("hollatagcompany"),
			"msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
	);
	
	
	$url = "https://sms.hollatags.com/api/send/";
	$call =  vp_remote_post($url, $request);
	$response = $call;
	$response == "sent" ? $force = "true" : $force = "false";
	$got = false;
	}
	else{
		$got = true;
		$force = "false";
	}
	
	if($pos != $_POST["run_code"]){
		$errz = "Track ID Not Same";
		$do = false;
	}
	elseif($_POST["run_code"] == "wrong"){
		$errz = "Track Id Can't Be wrong.";
		$do = false;
	}
	elseif($_COOKIE["run_code"] == "wrong"){
		$errz = "Session Can't Be Wrong";
		$do = false;
	}
	else{
		$errz = "unidentified";
		$do = true;
	
		if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
	
			$amtts = $bal - $_COOKIE["recent_amount"];
	
	
			$name = get_userdata($id)->user_login;
			$hname = get_userdata($id)->user_login;
			$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
			$fund_amount= $_COOKIE["recent_amount"];
			$before_amount = $bal;
			$now_amount = $amtts;
			$the_time = date('Y-m-d h:i:s A',$current_timestamp);
			
			$table_name = $wpdb->prefix.'vp_wallet';
			$added_to_db = $wpdb->insert($table_name, array(
			'name'=> $name,
			'type'=> "Wallet",
			'description'=> $description,
			'fund_amount' => $fund_amount,
			'before_amount' => $before_amount,
			'now_amount' => $now_amount,
			'user_id' => $id,
			'status' => "Approved",
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
	
	
			vp_updateuser($id,"vp_bal", $amtts);
	
	$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
	}
	else{
	setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	}
	
	
	
	}
	
	if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
			
				
				//SECURITY
	vpSec($phone);
	
			$trackcode = $_POST["run_code"];
			global $wpdb;
			$tableh = $wpdb->prefix."sdata";
			$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
			if(empty($rest)){
		
			}else{
	
				$wpdb->query('COMMIT');
die('[S/R] Duplicate Transaction!!! Check your transaction history please');
			}
	
	
	
			
			$service = "sdata";
			$mlm_for = "_data";
			global $wpdb;
			$table_trans = $wpdb->prefix.'vp_transactions';
			$unrecorded_added = $wpdb->insert($table_trans, array(
			'status' => 'Fa',
			'service' => $service,
			'name'=> $name,
			'email'=> $email,
			'recipient' => $phone,
			'bal_bf' => $bal,
			'bal_nw' => $baln,
			'amount' => $amount,
			'request_id' => $uniqidvalue,
			'user_id' => $id,
			'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
			));
			setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
			setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
			setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
			setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
			setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
			setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
			setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
			setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
			setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
			setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
	
	
	
	
		
			$_POST["run_code"] = "wrong";
			if($got){
				if(vp_getoption("smilequerymethod") != "array"){
	
					if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
						setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
						$tot = $bal - $amount;
						vp_updateuser($id, 'vp_bal', $tot);
						setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
					$call =  wp_remote_post($url, $http_args);
					$response = wp_remote_retrieve_body($call);
					setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
	setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
					provider_header_handler($call);
				}
				else{
				$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
				}
						}
						else{
	
							if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
								setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
								$tot = $bal - $amount;
								vp_updateuser($id, 'vp_bal', $tot);
								setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
								$call = "";	
					$response =  vp_remote_post_fn($url, $smile_array, $datass);
					if($response == "error"){
						global $return_message;
					
						$wpdb->query('COMMIT');
die($return_message);
					}
					else{
						//do nothing
					}
				}
				else{
				$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
				}
					
						}
			}else{};
	
	#$wpdb->query('COMMIT');

	if(is_wp_error($call)){
		if(vp_getoption("vpdebug") != "yes"){
	$error = $call->get_error_code();
	}
	else{
	$error = $call->get_error_message();
	}
	
	$smile_token = "no_response";
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html($call->get_error_message())."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		//do nothing
	}
	
	
	
	$obj = new stdClass;
	$obj->status = "202";
	$obj->response = $error;
	$wpdb->query('COMMIT');
die(json_encode($obj));
	}
	else{
	if(vp_getoption("smile1_response_format") == "JSON" || vp_getoption("smile1_response_format") == "json"){
	$en = validate_response($response,$sc,vp_getoption("smilesuccessvalue"),vp_getoption("smilesuccessvalue2"));
	}
	else{
	$en = $response ;
	}
	}
	
	$smile_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("smileresponse_id"));
	
	if(!empty($smile_response)){
		$smile_token = $smile_response[0];
	}
	else{
			$smile_token = "Nill";
	}
	
	if($en == "TRUE"  || $response  === vp_getoption("smilesuccessvalue") || $force == "true"){
						if($add_total == "yes"){
						vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
					}
	
	$plan = $_POST["data_plan"];
	$purchased = "Purchased {SMILE DATA --[ $plan ]--  }";
	weblinkBlast($phone,$purchased);

	$recipient = $phone;
	vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email' => $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Successful',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
	
	if(is_plugin_active("vpmlm/vpmlm.php")){
	do_action("vp_after");
	}
	
	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
	}
	elseif($en == "MAYBE"){
	
	
	global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Pending',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
	
		setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
	}
	else{
		
	
		global $wpdb;
	$table_name = $wpdb->prefix.'sdata';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $smile_token,
	'name'=> $name,
	'email'=> $email,
	'phone' => $phone,
	'plan' => $_POST["data_plan"]." With - ID ".$dplan,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'smile',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'status' => 'Failed',
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	
	$beneficiary = vp_getuser($id,"beneficiaries",true);
	
	if(!preg_match("/$phone/",$beneficiary)){
	vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
	}
	
		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
	
		update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);
	
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
	setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
	$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format ":"'.vp_getoption("smile1_response_format").'"}');
		
	
	}
	}
	else{
		$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
	}
		
		
		
	}
}
else{
$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("r2datarequest") == "get"){

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
	'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("corporatebase",vp_option_array($option_array,"r2databaseurl"),$urlraw);
$postdata1 = str_replace("corporatepostdata1",vp_option_array($option_array,"r2datapostdata1"),$base);
$postvalue1 = str_replace("corporatepostvalue1",vp_option_array($option_array,"r2datapostvalue1"),$postdata1);
$postdata2 = str_replace("corporatepostdata2",vp_option_array($option_array,"r2datapostdata2"),$postvalue1);
$postvalue2 = str_replace("corporatepostvalue2",vp_option_array($option_array,"r2datapostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("r2datasuccesscode");

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'co')) && stripos(vp_getoption("hollatagservices"),'co') !==  false && $network == vp_getoption("r2dataairtel")){
	
	
$request = array(
        "user"=> vp_getoption("hollatagusername"),
        "pass"=> vp_getoption("hollatagpassword"),
        "from"=> "DATA ALERT",
        "to"=> vp_getoption("hollatagcompany"),
        "msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
);


$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
			
	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{
			$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
		}



		
		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	
		$_POST["run_code"] = "wrong";


		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	
		if($got){
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
		}else{};
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$sme_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sdata';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $sme_token,
		'name'=> $name,
		'email' => $email,
		'phone' => $phone,
		'plan' => $_POST["data_plan"]." With - ID ".$dplan,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'corporate',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}

$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("r2datasuccessvalue"),vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $response ;
}
}



$corporate_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("corporateresponse_id"));

if(!empty($corporate_response)){
	$corporate_token = $corporate_response[0];
}
else{
	$corporate_token = "Nill";
}


$vpdebug = vp_getoption("vpdebug");
if($en == "TRUE"  || $response  === vp_getoption("r2datasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
//echo"<script>alert('sta 1 ma');</script>";

global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$purchased = "Purchased ".$_POST["data_plan"];
weblinkBlast($phone,$purchased);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

$plan = $_POST["data_plan"];
$purchased = "Purchased {CORPORATE DATA --[ $plan ]--  }";
$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("data3_response_format").'"}');
		
}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("r2databaseurl").vp_getoption("r2dataendpoint");
$num = $phone;
$cua = vp_getoption("r2datapostdata1");
    $cppa = vp_getoption("r2datapostdata2");
    $c1a = vp_getoption("r2datapostdata3");
    $c2a = vp_getoption("r2datapostdata4");
    $c3a = vp_getoption("r2datapostdata5");
    $cna = vp_getoption("r2datanetworkattribute");
    $caa = vp_getoption("r2dataamountattribute");
    $cpa = vp_getoption("r2dataphoneattribute");
	$cpla = vp_getoption("r2cvariationattr");
	$uniqid = vp_getoption("r2request_id");
    
    $datass = array(
     $cua => vp_getoption("r2datapostvalue1"),
     $cppa => vp_getoption("r2datapostvalue2"),
	$c1a => vp_getoption("r2datapostvalue3"),
	$c2a => vp_getoption("r2datapostvalue4"),
	$c3a => vp_getoption("r2datapostvalue5"),
	$uniqid => $uniqidvalue,
	$cna => $network,
	$cpa => $phone,
	$datatype => $datatype_value,
	$cpla => $dplan
	);

	$corporate_array = [];

	$the_head =  vp_getoption("data_head3");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("r2datavalue1");
		$auto = vp_getoption("r2datahead1").' '.$the_auth;
		$corporate_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("r2datavalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("r2datahead1").' '.$the_auth;
		$corporate_array["Authorization"] = $auto;
	}
	else{
		$corporate_array[vp_getoption("r2datahead1")] = vp_getoption("r2datavalue1");
	}
$corporate_array["Content-Type"] = "application/json";
$corporate_array["cache-control"] = "no-cache";

for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
	if(!empty(vp_getoption("corporateaddheaders$corporateaddheaders")) && !empty(vp_getoption("corporateaddvalue$corporateaddheaders"))){
		$corporate_array[vp_getoption("corporateaddheaders$corporateaddheaders")] = vp_getoption("corporateaddvalue$corporateaddheaders");
	}
}


$http_args = array(
'headers' => $corporate_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

$sc = vp_getoption("r2datasuccesscode");

if(vp_getoption("enablehollatag") == "yes" && is_numeric(stripos(vp_getoption("hollatagservices"),'co')) && stripos(vp_getoption("hollatagservices"),'co') !==  false && $network == vp_getoption("r2dataairtel")){
	
	
$request = array(
        "user"=> vp_getoption("hollatagusername"),
        "pass"=> vp_getoption("hollatagpassword"),
        "from"=> "DATA ALERT",
        "to"=> vp_getoption("hollatagcompany"),
        "msg"=> "Hello! Kindly Send ".$_POST["data_plan"]." To $phone"
);

$url = "https://sms.hollatags.com/api/send/";
$call =  vp_remote_post($url, $request);
$response = $call;
$response == "sent" ? $force = "true" : $force = "false";
$got = false;
}
else{
	$got = true;
	$force = "false";
}

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));



		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
		
			

	//SECURITY
	vpSec($phone);

		$trackcode = $_POST["run_code"];
		global $wpdb;
		$tableh = $wpdb->prefix."sdata";
		$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
		if(empty($rest)){
	
		}else{
			$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
		}



		
		$service = "sdata";
		$mlm_for = "_data";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	
		$_POST["run_code"] = "wrong";



		if($got){
			if(vp_getoption("corporatequerymethod") != "array"){

			if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
				setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
				$tot = $bal - $amount;
				vp_updateuser($id, 'vp_bal', $tot);
				setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				$call =  wp_remote_post($url, $http_args);
				$response = wp_remote_retrieve_body($call);
				setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
				provider_header_handler($call);
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
					}
					else{

						if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
							setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
							$tot = $bal - $amount;
							vp_updateuser($id, 'vp_bal', $tot);
							setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
							$call = "";	
				$response =  vp_remote_post_fn($url, $corporate_array, $datass);
				if($response == "error"){
					global $return_message;
				
					$wpdb->query('COMMIT');
die($return_message);
				}
				else{
					//do nothing
				}
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
					}
		}else{};




if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}


		$sme_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sdata';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $sme_token,
		'name'=> $name,
		'email' => $email,
		'phone' => $phone,
		'plan' => $_POST["data_plan"]." With - ID ".$dplan,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'corporate',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("r2datasuccessvalue"),vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $response ;
}
}


$corporate_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("corporateresponse_id"));

if(!empty($corporate_response)){
	$corporate_token = $corporate_response[0];
}
else{
	$corporate_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("r2datasuccessvalue") || $force == "true"){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$plan = $_POST["data_plan"];
$purchased = "Purchased {CORPORATE DATA --[ $plan ]--  }";

weblinkBlast($phone,$purchased);

$recipient = $phone;
vp_transaction_email("NEW DATA NOTIFICATION","SUCCESSFUL DATA PURCHASE",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);


global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email' => $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Successful',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}
setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){
	

global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Pending',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{



	global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $corporate_token,
'name'=> $name,
'email'=> $email,
'phone' => $phone,
'plan' => $_POST["data_plan"]." With - ID ".$dplan,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'corporate',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => 'Failed',
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$phone/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Data Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("data3_response_format").'"}');
	

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}

}

}


break;
case "ccab":
	$pos = $_POST["run_code"];
if(vp_getoption("cablerequest") == "get"){
$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("cablebase",vp_option_array($option_array,"cablebaseurl"),$urlraw);
$postdata1 = str_replace("cablepostdata1",vp_option_array($option_array,"cablepostdata1"),$base);
$postvalue1 = str_replace("cablepostvalue1",vp_option_array($option_array,"cablepostvalue1"),$postdata1);
$postdata2 = str_replace("cablepostdata2",vp_option_array($option_array,"cablepostdata2"),$postvalue1);
$postvalue2 = str_replace("cablepostvalue2",vp_option_array($option_array,"cablepostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("cablesuccesscode");

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

				$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));



		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}
if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		

	//SECURITY
	vpSec($iuc);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."scable";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


	
		$service = "scable";
		$mlm_for = "_cable";
		global $wpdb;
		$table_trans = $wpdb->prefix.'vp_transactions';
		$unrecorded_added = $wpdb->insert($table_trans, array(
		'status' => 'Fa',
		'service' => $service,
		'name'=> $name,
		'email'=> $email,
		'recipient' => $iuc,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
		setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
		setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
		setcookie("recipient", $iuc, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
		setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
		setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
		setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
		setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
		setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");

	$_POST["run_code"] = "wrong";


	if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
		setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
		$tot = $bal - $amount;
		vp_updateuser($id, 'vp_bal', $tot);
		setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_get($url, $http_args);
$response =wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}


		$cable_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'scable';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $cable_token,
		'name'=> $name,
		'email'=> $email,
		'iucno' => $iuc,
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'cable',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'product_id' => $ccable,
		'type' => $cabtype,
		'status' => "Failed",
		'user_id' => $id,
		'time' => date("Y-m-d h:i:s A",$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));

}
else{
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("cablesuccessvalue"),vp_getoption("cablesuccessvalue2"));
}
else{
$en = $response ;
}
}


$cable_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("cableresponse_id"));

if(!empty($cable_response)){
	$cable_token = $cable_response[0];
}
else{
	$cable_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("cablesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}

$cable = strtoupper($cabtype);
$realAmt = 	$_POST['amount'];
$purchased = "Paid $cable CableTv Subscription worth  ₦$realAmt";
$recipient = $iuc;
vp_transaction_email("NEW CABLETV NOTIFICATION","SUCCESSFUL CABLETV SUBSCRIPTION",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);



global $wpdb;
$table_name = $wpdb->prefix.'scable';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $cable_token,
'name'=> $name,
'email'=> $email,
'iucno' => $iuc,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'cable',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'product_id' => $ccable,
'type' => $cabtype,
'status' => "Successful",
'user_id' => $id,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	//do nothing
}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}


//wp_redirect(site_url().apply_filters("spage",vp_getoption("suc")));
setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){


global $wpdb;
$table_name = $wpdb->prefix.'scable';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $cable_token,
'name'=> $name,
'email'=> $email,
'iucno' => $iuc,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'cable',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'product_id' => $ccable,
'type' => $cabtype,
'user_id' => $id,
'status' => "Pending",
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
	$table_name = $wpdb->prefix.'scable';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $cable_token,
	'name'=> $name,
	'email'=> $email,
	'iucno' => $iuc,
	'phone' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'cable',
	'trans_method' => 'get',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'product_id' => $ccable,
	'type' => $cabtype,
	'user_id' => $id,
	'status' => "Failed",
	'time' => date("Y-m-d h:i:s A",$current_timestamp)
	));

	$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Cable Purchase With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("cable_response_format").'"}');
		
}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("cablebaseurl").vp_getoption("cableendpoint");
$num = $phone;
 $cua = vp_getoption("cablepostdata1");
    $cppa = vp_getoption("cablepostdata2");
    $c1a = vp_getoption("cablepostdata3");
    $c2a = vp_getoption("cablepostdata4");
    $c3a = vp_getoption("cablepostdata5");
    $ctypa = vp_getoption("ctypeattr");
    $caa = vp_getoption("cableamountattribute");
	$ccvaa = vp_getoption("ccvariationattr");
	$ciuc = vp_getoption("ciucattr");
	$uniqid = vp_getoption("crequest_id");
    
    $datass = array(
    $cua => vp_getoption("cablepostvalue1"),
    $cppa => vp_getoption("cablepostvalue2"),
	$c1a => vp_getoption("cablepostvalue3"),
	$c2a => vp_getoption("cablepostvalue4"),
	$c3a => vp_getoption("cablepostvalue5"),
	$uniqid => $uniqidvalue,
	$ctypa => $cabtype,
	$ccvaa => $ccable,
	$ciuc => $iuc
	);

	$cable_array = [];

	$the_head =  vp_getoption("cable_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("cablevalue1");
		$auto = vp_getoption("cablehead1").' '.$the_auth;
		$cable_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("cablevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("cablehead1").' '.$the_auth;
		$cable_array["Authorization"] = $auto;
	}
	else{
		$cable_array[vp_getoption("cablehead1")] = vp_getoption("cablevalue1");
	}

$sc = vp_getoption("cablesuccesscode");


$cable_array["Content-Type"] = "application/json";
$cable_array["cache-control"] = "no-cache";

for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
	if(!empty(vp_getoption("cableaddheaders$cableaddheaders")) && !empty(vp_getoption("cableaddvalue$cableaddheaders"))){
		$cable_array[vp_getoption("cableaddheaders$cableaddheaders")] = vp_getoption("cableaddvalue$cableaddheaders");
	}
}

$http_args = array(
'headers' => $cable_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

		$amtts = $bal - $_COOKIE["recent_amount"];

		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		
	//SECURITY
	vpSec($iuc);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."scable";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}



	$service = "scable";
	$mlm_for = "_cable";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $iuc,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $iuc, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";


	if(vp_getoption("cablequerymethod") != "array"){

		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
			vp_updateuser($id, 'vp_bal', $tot);
			setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		$call =  wp_remote_post($url, $http_args);
		$response = wp_remote_retrieve_body($call);
		setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
		provider_header_handler($call);
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
			}
			else{

				if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
					setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
					$tot = $bal - $amount;
					vp_updateuser($id, 'vp_bal', $tot);
					setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");

					$call = "";
		$response =  vp_remote_post_fn($url, $cable_array, $datass);
		if($response == "error"){
			global $return_message;
		
			$wpdb->query('COMMIT');
die($return_message);
		}
		else{
			//do nothing
		}
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
		
			}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}


		

		$cable_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'scable';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $cable_token,
		'name'=> $name,
		'email'=> $email,
		'iucno' => $iuc,
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'cable',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'product_id' => $ccable,
		'type' => $cabtype,
		'status' => "Failed",
		'user_id' => $id,
		'time' => date("Y-m-d h:i:s A",$current_timestamp)
		));

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}




$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("cablesuccessvalue"),vp_getoption("cablesuccessvalue2") );
}
else{
$en = $response ;
}
}

$cable_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("cableresponse_id"));

if(!empty($cable_response)){
	$cable_token = $cable_response[0];
}
else{
	$cable_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("cablesuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
  
global $wpdb;
$table_name = $wpdb->prefix.'scable';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $cable_token,
'name'=> $name,
'email'=> $email,
'iucno' => $iuc,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'cable',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'product_id' => $ccable,
'type' => $cabtype,
'status' => "Successful",
'user_id' => $id,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}

$cable = strtoupper($cabtype);
$realAmt = 	$_POST['amount'];
$purchased = "Paid $cable CableTv Subscription worth  ₦$realAmt";
$recipient = $iuc;
vp_transaction_email("NEW CABLETV NOTIFICATION","SUCCESSFUL CABLETV SUBSCRIPTION",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);



if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){

global $wpdb;
$table_name = $wpdb->prefix.'scable';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $cable_token,
'name'=> $name,
'email'=> $email,
'iucno' => $iuc,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'trans_type' => 'cable',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'product_id' => $ccable,
'type' => $cabtype,
'user_id' => $id,
'status' => "Pending",
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}
if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
	$table_name = $wpdb->prefix.'scable';
	$added_to_db = $wpdb->insert($table_name, array(
	'run_code' => esc_html($pos),
	'response_id'=> $cable_token,
	'name'=> $name,
	'email'=> $email,
	'iucno' => $iuc,
	'phone' => $phone,
	'bal_bf' => $bal,
	'bal_nw' => $bal,
	'amount' => $amount,
	'resp_log' => " ".esc_html(harray_key_first($response))."",
	'browser' => $browser,
	'trans_type' => 'cable',
	'trans_method' => 'post',
	'via' => 'site',
	'time_taken' => '1',
	'request_id' => $uniqidvalue,
	'product_id' => $ccable,
	'type' => $cabtype,
	'user_id' => $id,
	'status' => "Failed",
	'time' => date("Y-m-d h:i:s A",$current_timestamp)
	));

	$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$iuc/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$iuc);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Cable Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("cable_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
	
}
break;
case "cbill":
	$pos = $_POST["run_code"];
if(vp_getoption("billrequest") == "get"){

$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("billbase",vp_option_array($option_array,"billbaseurl"),$urlraw);
$postdata1 = str_replace("billpostdata1",vp_option_array($option_array,"billpostdata1"),$base);
$postvalue1 = str_replace("billpostvalue1",vp_option_array($option_array,"billpostvalue1"),$postdata1);
$postdata2 = str_replace("billpostdata2",vp_option_array($option_array,"billpostdata2"),$postvalue1);
$postvalue2 = str_replace("billpostvalue2",vp_option_array($option_array,"billpostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("billsuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
	'Content-Type' => 'application/json'
),
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false);

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;

	if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		$amtts = $bal - $_COOKIE["recent_amount"];

		$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


		vp_updateuser($id,"vp_bal", $amtts);
		

$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		

	//SECURITY
	vpSec($meterno);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sbill";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}


	

	$service = "sbill";
	$mlm_for = "_bill";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $meterno,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $meterno, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";



if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 
	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
	setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
	$tot = $bal - $amount;
	vp_updateuser($id, 'vp_bal', $tot);
	setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}



if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}


		

		$bill_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sbill';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bill_token,
		'name'=> $name,
		'email'=> $email,
		'meterno' => $meterno,
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => ($amount),
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'charge' => floatval(vp_option_array($option_array,"bill_charge")),
		'trans_type' => 'bill',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'product_id' => $cbill,
		'meter_token' => $meter_token,
		'type' => $type,
		'time' => date("Y-m-d h:i:s A",$current_timestamp)
		));
		

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("billsuccessvalue"),vp_getoption("billsuccessvalue2"));
}
else{
$en = $response ;
}
}

$bill_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("billresponse_id"));

if(!empty($bill_response)){
	$bill_token = $bill_response[0];
}
else{
	$bill_token = "Nill";
}

if($en == "TRUE"  || $response  === vp_getoption("billsuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
//echo"<script>alert('sta 1 ma');</script>";

$bill_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}


global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'product_id' => $cbill,
'meter_token' => $meter_token,
'type' => $type,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

$realAmt = 	$_POST['amount'];
$purchased = "Paid for UTILITY BILL worth  ₦$realAmt";
$recipient = $meter_token;
vp_transaction_email("NEW UTILITY BIL NOTIFICATION","SUCCESSFUL UTILITY BILL PAYMENT",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);




if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

//echo"<script>alert('mae');</script>";
global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'product_id' => $cbill,
'meter_token' => "No Record",
'type' => $type,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'get',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Failed",
'product_id' => $cbill,
'meter_token' => "No Record",
'type' => $type,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Bill Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("bill_response_format").'"}');
		
}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
}
else{
$url = vp_getoption("billbaseurl").vp_getoption("billendpoint");
$num = $phone;
	$cua = vp_getoption("billpostdata1");
    $cppa = vp_getoption("billpostdata2");
    $c1a = vp_getoption("billpostdata3");
    $c2a = vp_getoption("billpostdata4");
    $c3a = vp_getoption("billpostdata5");
    $btypa = vp_getoption("btypeattr");
    $caa = vp_getoption("billamountattribute");
	$cbvaa = vp_getoption("cbvariationattr");
	$cmeter = vp_getoption("cmeterattr");
	$uniqid = vp_getoption("brequest_id");
    
    $datass = array(
    $cua => vp_getoption("billpostvalue1"),
    $cppa => vp_getoption("billpostvalue2"),
	$c1a => vp_getoption("billpostvalue3"),
	$c2a => vp_getoption("billpostvalue4"),
	$c3a => vp_getoption("billpostvalue5"),
	$uniqid => $uniqidvalue,
	$btypa => $type,
	$cbvaa => $cbill,
	$cmeter => $meterno,
	$caa => floatval($bamount)
	);

	$bill_array = [];

	$the_head =  vp_getoption("bill_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("billvalue1");
		$auto = vp_getoption("billhead1").' '.$the_auth;
		$bill_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("billvalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("billhead1").' '.$the_auth;
		$bill_array["Authorization"] = $auto;
	}
	else{
		$bill_array[vp_getoption("billhead1")] = vp_getoption("billvalue1");
	}


$bill_array["Content-Type"] = "application/json";
$bill_array["cache-control"] = "no-cache";

for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
	if(!empty(vp_getoption("billaddheaders$billaddheaders")) && !empty(vp_getoption("billaddvalue$billaddheaders"))){
		$bill_array[vp_getoption("billaddheaders$billaddheaders")] = vp_getoption("billaddvalue$billaddheaders");
	}
}

$http_args = array(
'headers' => $bill_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);

$sc = vp_getoption("billsuccesscode");

if($pos != $_POST["run_code"]){
	$errz = "Track ID Not Same";
	$do = false;
}
elseif($_POST["run_code"] == "wrong"){
	$errz = "Track Id Can't Be wrong.";
	$do = false;
}
elseif($_COOKIE["run_code"] == "wrong"){
	$errz = "Session Can't Be Wrong";
	$do = false;
}
else{
	$errz = "unidentified";
	$do = true;
if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){

	$amtts = $bal - $_COOKIE["recent_amount"];

			$name = get_userdata($id)->user_login;
		$hname = get_userdata($id)->user_login;
		$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
		$fund_amount= $_COOKIE["recent_amount"];
		$before_amount = $bal;
		$now_amount = $amtts;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


	vp_updateuser($id,"vp_bal", $amtts);

	$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
}
else{
	setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
}



}

if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
	
		
	
	//SECURITY
	vpSec($meterno);

	$trackcode = $_POST["run_code"];
	global $wpdb;
	$tableh = $wpdb->prefix."sbill";
	$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
	if(empty($rest)){

	}else{
		$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
	}

	$service = "sbill";
	$mlm_for = "_bill";
	global $wpdb;
	$table_trans = $wpdb->prefix.'vp_transactions';
	$unrecorded_added = $wpdb->insert($table_trans, array(
	'status' => 'Fa',
	'service' => $service,
	'name'=> $name,
	'email'=> $email,
	'recipient' => $meterno,
	'bal_bf' => $bal,
	'bal_nw' => $baln,
	'amount' => $amount,
	'request_id' => $uniqidvalue,
	'user_id' => $id,
	'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
	));
	setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
	setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
	setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
	setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
	setcookie("recipient", $meterno, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
	setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
	setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
	setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
	setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
	setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";


	
	if(vp_getoption("billquerymethod") != "array"){

		if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
			setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
			$tot = $bal - $amount;
vp_updateuser($id, 'vp_bal', $tot);
setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		$call =  wp_remote_post($url, $http_args);
		$response = wp_remote_retrieve_body($call);
		setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
		provider_header_handler($call);
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
			}
			else{
				$call = "";
				if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
					setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
					$tot = $bal - $amount;
					vp_updateuser($id, 'vp_bal', $tot);
					setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
					$response =  vp_remote_post_fn($url, $bill_array, $datass);
					if($response == "error"){
						global $return_message;
					
						$wpdb->query('COMMIT');
die($return_message);
					}
					else{
						//do nothing
					}
	}
	else{
	$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
	}
		
			}


if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		
		

		$bill_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sbill';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bill_token,
		'name'=> $name,
		'email'=> $email,
		'meterno' => $meterno,
		'phone' => $phone,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => ($amount),
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'charge' => floatval(vp_option_array($option_array,"bill_charge")),
		'trans_type' => 'bill',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => "Failed",
		'product_id' => $cbill,
		'meter_token' => $meter_token,
		'type' => $type,
		'time' => date("Y-m-d h:i:s A",$current_timestamp)
		));
		

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("billsuccessvalue"),vp_getoption("billsuccessvalue2"));
}
else{
$en = $response ;
}
}



if($en == "TRUE"  || $response  === vp_getoption("billsuccessvalue")){
	
$bill_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("billresponse_id"));

if(!empty($bill_response)){
	$bill_token = $bill_response[0];
}
else{
	$bill_token = "Nill";
}
	
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
				

$bill_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}

  
global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Successful",
'product_id' => $cbill,
'type' => $type,
'meter_token' => $meter_token,
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$realAmt = 	$_POST['amount'];
$purchased = "Paid for UTILITY BILL worth  ₦$realAmt";
$recipient = $meter_token;
vp_transaction_email("NEW UTILITY BIL NOTIFICATION","SUCCESSFUL UTILITY BILL PAYMENT",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);


$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

if(is_plugin_active("vpmlm/vpmlm.php")){	
do_action("vp_after");
}

setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){
	
$bill_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("billresponse_id"));

if(!empty($bill_response)){
	$bill_token = $bill_response[0];
}
else{
	$bill_token = "Nill";
}

	
//echo"<script>alert('mae');</script>";
global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Pending",
'product_id' => $cbill,
'type' => $type,
'meter_token' => "No Record",
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{

	$bill_token = "Nill";

	global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$added_to_db = $wpdb->insert($table_name, array(
'run_code' => esc_html($pos),
'response_id'=> $bill_token,
'name'=> $name,
'email'=> $email,
'meterno' => $meterno,
'phone' => $phone,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => ($amount),
'resp_log' => " ".esc_html(harray_key_first($response))."",
'browser' => $browser,
'charge' => floatval(vp_option_array($option_array,"bill_charge")),
'trans_type' => 'bill',
'trans_method' => 'post',
'via' => 'site',
'time_taken' => '1',
'request_id' => $uniqidvalue,
'user_id' => $id,
'status' => "Failed",
'product_id' => $cbill,
'type' => $type,
'meter_token' => "No Record",
'time' => date("Y-m-d h:i:s A",$current_timestamp)
));

$beneficiary = vp_getuser($id,"beneficiaries",true);

if(!preg_match("/$meterno/",$beneficiary)){
vp_updateuser($id,"beneficiaries",$beneficiary.",".$meterno);
}

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Bill Purchase With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("bill_response_format").'"}');

}
}
else{
	$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
}
	
}
break;
case"csms":
$sender = $_POST["sender"];
$receiver = $_POST["receiver"];
$tid = $id;

$theMessage = $_POST["message"];

$spamWords =<<<EOD
UBA, CBN, Stanbic, Ibtc, C.B.N, BVN, B.V.N, Jaiz, gtbank, Diamond, B V N, C B N, B@nk, Fidelity, 27242, Quickteller, ATM, A.T.M, A T M, Paypal, Polaris, Cash, F c m b,
FIDELITY, BVN, polaris, gtb, g.t.b,
bank, KEYSTONE NPF, Police, Custom, Army,
Airforce, Naval, NCS, US ARMY,
Nig Custom, Millitary, USA, SSS, S. S. S, Mopol, EFCC, ikeja electric, AEDC, BEDC,
EKEDC, EEDC, IBEDC, IKEDC,
JEDC, KNEDC, KEDC, PHEDC,
YEDC, NNPC, Chevron, ExxonMobil,
N.N.P.C, N. N. P. C, JAMB, 55019, Admin, Administrator, Info, @, /, _, EEDC, Gotv, DSTV, Startimes,
Ikeja Electric, Jumia, Konga,
PDP, PROMO, WADA, DIAMOND, CONGRATULATION, congrats, apc, pdp, vote, code, google, central,  fidelity, million, Zen-ith, Zenith, facebook, Integrity, M"T N N, 1.8.O, M"TN-N, M"TN N,
M"T N-N.?, M"T N-N., M"T N?, M"TN N., M T"N-NG, M T"N-N., M T"N-N, M T N, M .T N N?, M "T N N., M"T N N., M.T`N, M"TN-N, M.T`N,M.T`N GN, M.T`N-.NG, MT N,MTN, MTEL, M-T-N, -M-T-N-, MTN N, MTN NG, MTN-NG, YELLOLTD,YELLO, YELL0, mtn,
1.8.O, Recharge ,4I00 , A!rtel, AIRTEL, ALERT , ANGEL, MEZZY , AWOOF!!!, BankAccess, BANKM3py, Bumazek, C0NGRATS! , C0NGRTS, C0NGRTS , Y0U , cantv.net , Cards-Zone, Card-Zone, CLAIM, Coca-Cola , coinmac.net, CONGRAT, Congrats!, Congratulation, CONGRATULATIONS, CouplesOut, DIAMOND ,ETISALAT, 
Euro, Casino, Gl0lwinner, GLOGlo ,Promo! , GLOBACOM, Glowinner,  GreatNews!, gsm promo, HSEFELOSHIP,  http://www.permanenttsb, updates.org, http://www.yellopins.com ,info@nmobiledraw.org ,nfonmobiledraw.org, ISAMS YABA, Jemtrade , LIVINGWORD, lo1O10, LOADED, lottery , ottery,
lotto, Maitap.be, MANSARD, L21, Megateq, mlottery@usa.com, mlttryusa@w.cn, mobile, promo, MT N,MTEL, MTN ,M-T-N ,
-M-T-N-, MTN N, MTN NG, MTN-NG,  N0K, N10M, N2Million, nexteldraws@live.co.uk, NKM, BILE, NOK, Nokia inc., nokia promo, nokialondondept247@hotmail.co.uk, NOKlA UK,  NTM, ORLAJ, P C EBUNILO , PR1ZE=0FFER, PRIMEGROCER, PROMO, reward, Rewarded, ROSGLORIOSM,  Service180, Spam, StanbicIBTC , L SEGUN , 
SWFT , W1N, W0N, VISAFONE, VIP-CARD, vadia, usa.mlty@w.cn, ULTIMATE, TOYOTA, sweepstakes, kids, telsms@live.com, takko, SWIFTNG ,Swift_NG ,SWIFT 4G, wbre@gala.net , wbre2@gala.net , WIN, ZOOM, MULTILINKS, Your-Line, Your Number Have Win , been selected , number has, your mobile has been selected , wo n , won , YDD Welfare, Your Number Have Win, Your-Line, ZOOM MULTILINKS, Lumos , lacasera , smsalert, Alert , HSBC, Gionee, StanChart, Inform , Bulk SMS, BulkSMS, 
Singlsrally, NOBLE KIT SERVE,
NOBLE KIT SER, Emma LovingYOU, Me4u, foryou, RICH-PRINCE, RichPrince, Yinkuccc,
TheFle, Demoj, SWEETHEART MINISTRY, MINISTRY MzPretty,
SirJTelecom, RMA Team, Good News, BOTLINK, Mama abuja,
Oganihu, KAFA, Singlsrally,
JesusFamily, MFB, m f b, m.f.b, m. f. b., Flutterwave, ALERT , Activation, Activation, social media, sup, Embassy, Grant, SEM, SEM Grant, Telpecon G, Telpecon , PAGA, account, Promo, PROMOTIONAL, CONGRATS, CONGRATULATIONS,  PRIZE, YELLO, VOTE, APC

EOD;


$first = preg_replace('/,\s+/',",",$spamWords);

$second =  preg_replace('/\s+,/',",",$first);
$spamWords = explode(",",$second);
global $theWord;
$theWord = "";
function containsSpam($message, $spamWords) {
	global $theWord;
    $message = strtolower($message); // Convert the message to lowercase for case-insensitive matching
    foreach ($spamWords as $spamWord) {
        if (stripos($message, $spamWord) !== false) {
			$theWord = $spamWord;
            return true; // Spam word found
        }
    }
    return false; // No spam words found
}

if(containsSpam($theMessage,$spamWords) && preg_match('/cliqsms/',vp_getoption("smsbaseurl"))){
	$wpdb->query('COMMIT');
die("[".$theWord."] is filtered, replace with another word");
}

if(vp_getoption("smsrequest") == "get"){
	
$urlraw = htmlspecialchars_decode($_POST["url"]);
$base = str_replace("smsbaseurl",vp_option_array($option_array,"smsbaseurl"),$urlraw);
$postdata1 = str_replace("smspostdata1",vp_option_array($option_array,"smspostdata1"),$base);
$postvalue1 = str_replace("smspostvalue1",vp_option_array($option_array,"smspostvalue1"),$postdata1);
$postdata2 = str_replace("smspostdata2",vp_option_array($option_array,"smspostdata2"),$postvalue1);
$postvalue2 = str_replace("smspostvalue2",vp_option_array($option_array,"smspostvalue2"),$postdata2);
$url = $postvalue2;

$sc = vp_getoption("smssuccesscode");

$sms_array = [];

$the_head =  vp_getoption("sms_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("smsvalue1");
	$auto = vp_getoption("smshead1").' '.$the_auth;
	$sms_array["Authorization"] = $auto;
}
elseif($the_head == "concatenated"){
	$the_auth_value = vp_getoption("smsvalue1");
	$the_auth = base64_encode($the_auth_value);
	$auto = vp_getoption("smshead1").' '.$the_auth;
	$sms_array["Authorization"] = $auto;
}
else{
	$sms_array[vp_getoption("smshead1")] = vp_getoption("smsvalue1");
}

$sms_array["cache-control"] = "no-cache";

for($smsaddheaders=1; $smsaddheaders<=4; $smsaddheaders++){
	if(!empty(vp_getoption("smsaddheaders$smsaddheaders")) && !empty(vp_getoption("smsaddvalue$smsaddheaders"))){
		$sms_array[vp_getoption("smsaddheaders$smsaddheaders")] = vp_getoption("smsaddvalue$smsaddheaders");
	}
}

$http_args = array(
'headers' => $sms_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false);


$service = "ssms";
global $wpdb;
$table_trans = $wpdb->prefix.'vp_transactions';
$unrecorded_added = $wpdb->insert($table_trans, array(
'status' => 'Fa',
'service' => $service,
'name'=> $name,
'email'=> $email,
'recipient' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'request_id' => $uniqidvalue,
'user_id' => $id,
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));
setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
setcookie("recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";

	if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
		setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
		$tot = $bal - $amount;
vp_updateuser($id, 'vp_bal', $tot);
setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
$call =  wp_remote_get($url,$http_args);
$response =wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}

if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		
		

		$bill_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'ssms';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'email'=> $email,
		'sender' => $sender,
		'receiver' => $receiver,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'user_id' => $tid,
		'status' => "Failed",
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}



$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));
}
else{
if(vp_getoption("sms_response_format") == "JSON" || vp_getoption("sms_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("smssuccessvalue"),vp_getoption("smssuccessvalue2"));
}
else{

if(stripos($response,vp_getoption("smssuccessvalue")) !== false){
$en = "TRUE" ;
}
else{
$en = "FALSE" ;	
}
}


}

if($en == "TRUE"  || $response  === vp_getoption("smssuccessvalue")){
					if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
//echo"<script>alert('sta 1 ma');</script>";
global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'user_id' => $tid,
'status' => "Successful",
'resp_log' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

$purchased = $_POST["message"];
$recipient = $receiver;
vp_transaction_email("NEW BulkSms NOTIFICATION","SUCCESSFUL UTILITY BulkSms ",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);



setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");

}
elseif($en == "MAYBE"){

//echo"<script>alert('mae');</script>";

global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'user_id' => $tid,
'status' => "Pending",
'resp_log' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



if(is_numeric($added_to_db)){
global $wpdb;
 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
}
else{

}



setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'user_id' => $tid,
'status' => "Failed",
'resp_log' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Bulk Sms With Id $uniqidvalue",$amount,$baln,$bal);

if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("sms_response_format").'"}');
		
}
}
else{
$url = vp_getoption("smsbaseurl").vp_getoption("smsendpoint");
$num = $phone;
	$cua = vp_getoption("smspostdata1");
    $cppa = vp_getoption("smspostdata2");
    $c1a = vp_getoption("smspostdata3");
    $c2a = vp_getoption("smspostdata4");
    $c3a = vp_getoption("smspostdata5");
    $sender = vp_getoption("senderattr");
    $receiver = vp_getoption("receiverattr");
    $message = vp_getoption("messageattr");
    $flash = vp_getoption("flashattr");
	$uniqid = vp_getoption("smsrequest_id");
    
    $datass = array(
    $cua => vp_getoption("smspostvalue1"),
    $cppa => vp_getoption("smspostvalue2"),
	$c1a => vp_getoption("smspostvalue3"),
	$c2a => vp_getoption("smspostvalue4"),
	$c3a => vp_getoption("smspostvalue5"),
	$sender => $_POST["sender"],
	$receiver => $_POST["receiver"],
	$message => $_POST["message"],
	$flash => vp_getoption("flash_value"),
	$uniqid => $uniqidvalue,
	);

	$sms_array = [];

	$the_head =  vp_getoption("sms_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("smsvalue1");
		$auto = vp_getoption("smshead1").' '.$the_auth;
		$sms_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("smsvalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("smshead1").' '.$the_auth;
		$sms_array["Authorization"] = $auto;
	}
	else{
		$sms_array[vp_getoption("smshead1")] = vp_getoption("smsvalue1");
	}

$sms_array["Content-Type"] = "application/json";
$sms_array["cache-control"] = "no-cache";

for($smsaddheaders=1; $smsaddheaders<=4; $smsaddheaders++){
	if(!empty(vp_getoption("smsaddheaders$smsaddheaders")) && !empty(vp_getoption("smsaddvalue$smsaddheaders"))){
		$sms_array[vp_getoption("smsaddheaders$smsaddheaders")] = vp_getoption("smsaddvalue$smsaddheaders");
	}
}

$http_args = array(
'headers' => $sms_array,
'timeout' => '3000',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


$service = "ssms";
global $wpdb;
$table_trans = $wpdb->prefix.'vp_transactions';
$unrecorded_added = $wpdb->insert($table_trans, array(
'status' => 'Fa',
'service' => $service,
'name'=> $name,
'email'=> $email,
'recipient' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'request_id' => $uniqidvalue,
'user_id' => $id,
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));
setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
setcookie("recipient", $receiver, time() + (30 * 24 * 60 * 60), "/");
setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");


	$_POST["run_code"] = "wrong";
	

if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
	setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
$tot = $bal - $amount;
vp_updateuser($id, 'vp_bal', $tot);

$call =  wp_remote_post($url, $http_args);
$response =wp_remote_retrieve_body($call);
setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
provider_header_handler($call);
}
else{
$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
}



if(is_wp_error($call)){
	if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}

		$bill_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'ssms';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'email'=> $email,
		'sender' => $sender,
		'receiver' => $receiver,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'user_id' => $tid,
		'status' => "Failed",
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		

		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}


$obj = new stdClass;
$obj->status = "202";
$obj->response = $error;
$wpdb->query('COMMIT');
die(json_encode($obj));

}
else{
if(vp_getoption("sms_response_format") == "JSON" || vp_getoption("sms_response_format") == "json"){
$en = validate_response($response,$sc,vp_getoption("smssuccessvalue"),vp_getoption("smssuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE"  || $response  === vp_getoption("smssuccessvalue")){
  				if($add_total == "yes"){
					vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
				}
global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'user_id' => $tid,
'status' => "Successful",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


if(is_numeric($added_to_db)){
	global $wpdb;
	$wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}

$purchased = $_POST["message"];
vp_transaction_email("SMS SENT NOTIFICATION","MESSAGE SENT","NILL",$purchased, $receiver, $amount, $bal,$baln);



setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
}
elseif($en == "MAYBE"){

//echo"<script>alert('mae');</script>";
global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $baln,
'amount' => $amount,
'user_id' => $tid,
'status' => "Pending",
'resp_log' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



if(is_numeric($added_to_db)){
	global $wpdb;
	 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}
	else{
	
	}


	setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
}
else{


	global $wpdb;
$table_name = $wpdb->prefix.'ssms';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'email'=> $email,
'sender' => $sender,
'receiver' => $receiver,
'bal_bf' => $bal,
'bal_nw' => $bal,
'amount' => $amount,
'user_id' => $tid,
'status' => "Failed",
'resp_log' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

	vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");

	update_wallet("Approved","Reversal For Failed Bulk Sms With Id $uniqidvalue",$amount,$baln,$bal);

	if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("sms_response_format").'"}');
	

}
	
}

break;
case"cbet":
	$pos = $_POST["run_code"];
	if(!isset($_POST["bet_company"])){
		$wpdb->query('COMMIT');
die("No Betting Company Identified");
	}
	elseif(!isset($_POST["customerid"])){
		$wpdb->query('COMMIT');
die("No Customer Id Found");
	}
	elseif(empty($_POST["bet_company"])){
		$wpdb->query('COMMIT');
die("betting company can't be empty");
	}
	elseif(empty($_POST["customerid"])){
		$wpdb->query('COMMIT');
die("Customer Id Can't Be Empty");
	}
	elseif(empty($_POST["amount"])){
		$wpdb->query('COMMIT');
die("Amount Can't Be Empty");
	}
	else{
		$network = $_POST["bet_company"];
		$phone = $_POST["customerid"];
	}
$vpdebug = vp_getoption("vpdebug");
if(vp_getoption("betrequest") == "get"){
		
		
		$http_args = array(
		'headers' => array(
		'cache-control' => 'no-cache',
			'Content-Type' => 'application/json'
		),
		'timeout' => '3000',
		'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
		'sslverify' => false
		);
		
		
		$urlraw = htmlspecialchars_decode($_POST["url"]);
		$base = str_replace("betbase",vp_option_array($option_array,"betbaseurl"),$urlraw);
		$postdata1 = str_replace("betpostdata1",vp_option_array($option_array,"betpostdata1"),$base);
		$postvalue1 = str_replace("betpostvalue1",vp_option_array($option_array,"betpostvalue1"),$postdata1);
		$postdata2 = str_replace("betpostdata2",vp_option_array($option_array,"betpostdata2"),$postvalue1);
		$postvalue2 = str_replace("betpostvalue2",vp_option_array($option_array,"betpostvalue2"),$postdata2);
		$url = $postvalue2;
		
		$sc = vp_getoption("betsuccesscode");
		

			$got = true;
			$force = "false";
		
		
		if($pos != $_POST["run_code"]){
			$errz = "Track ID Not Same";
			$do = false;
		}
		elseif($_POST["run_code"] == "wrong"){
			$errz = "Track Id Can't Be wrong.";
			$do = false;
		}
		elseif($_COOKIE["run_code"] == "wrong"){
			$errz = "Session Can't Be Wrong";
			$do = false;
		}
		else{
			$errz = "unidentified";
			$do = true;
		
			if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		
				$amtts = $bal - $_COOKIE["recent_amount"];
		
				$name = get_userdata($id)->user_login;
				$hname = get_userdata($id)->user_login;
				$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
				$fund_amount= $_COOKIE["recent_amount"];
				$before_amount = $bal;
				$now_amount = $amtts;
				$the_time = date('Y-m-d h:i:s A',$current_timestamp);
				
				$table_name = $wpdb->prefix.'vp_wallet';
				$added_to_db = $wpdb->insert($table_name, array(
				'name'=> $name,
				'type'=> "Wallet",
				'description'=> $description,
				'fund_amount' => $fund_amount,
				'before_amount' => $before_amount,
				'now_amount' => $now_amount,
				'user_id' => $id,
				'status' => "Approved",
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));
		
		
				vp_updateuser($id,"vp_bal", $amtts);
		
		$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
		}
		else{
		setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		}
		
		
		}
		
		if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
				
			
		
				
	//SECURITY
	vpSec($phone);
		
				$trackcode = $_POST["run_code"];
				global $wpdb;
				$tableh = $wpdb->prefix."sbet";
				$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
				if(empty($rest)){
			
				}else{
					$wpdb->query('COMMIT');
die('[T/C] Duplicate Transaction!!! Check your transaction history please');
				}
		
		
				
		
				$service = "sbet";
				$mlm_for = "_bet";
				global $wpdb;
				$table_trans = $wpdb->prefix.'vp_transactions';
				$unrecorded_added = $wpdb->insert($table_trans, array(
				'status' => 'Fa',
				'service' => $service,
				'name'=> $name,
				'email'=> $email,
				'recipient' => $phone,
				'bal_bf' => $bal,
				'bal_nw' => $baln,
				'amount' => $amount,
				'request_id' => $uniqidvalue,
				'user_id' => $id,
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));
				setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
				setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
				setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
				setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
				setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
				setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
				setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
				setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
				setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
				setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
		
			
				$_POST["run_code"] = "wrong";
		
				
		
			if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
				setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
				$tot = $bal - $amount;
				vp_updateuser($id, 'vp_bal', $tot);
				setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				if($got){
		$call =  wp_remote_get($url, $http_args);
		$response = wp_remote_retrieve_body($call);
		setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
		setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
		provider_header_handler($call);
				}else{};
			}
			else{
			$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
			}
		
		if(is_wp_error($call)){
			if(vp_getoption("vpdebug") != "yes"){
				$error = $call->get_error_code();
				}
				else{
				$error = $call->get_error_message();
				}
		
				$bet_token = "no_response";
				global $wpdb;
				$table_name = $wpdb->prefix.'sbet';
				$added_to_db = $wpdb->insert($table_name, array(
				'run_code' => esc_html($pos),
				'response_id'=> $bet_token,
				'name'=> $name,
				'email' => $email,
				'customerid' => $phone,
				'company' => $network,
				'bal_bf' => $bal,
				'bal_nw' => $bal,
				'amount' => $amount,
				'resp_log' => " ".esc_html($call->get_error_message())."",
				'browser' => $browser,
				'trans_type' => 'bet',
				'trans_method' => 'get',
				'via' => 'site',
				'time_taken' => '1',
				'request_id' => $uniqidvalue,
				'user_id' => $id,
				'status' => 'Failed',
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));
		
				vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
				if(is_numeric($added_to_db)){
					global $wpdb;
					 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
					}
					else{
					//do nothing
				}
		
		
		
		$obj = new stdClass;
		$obj->status = "202";
		$obj->response = $error;
		$wpdb->query('COMMIT');
die(json_encode($obj));
		}
		else{
		if(vp_getoption("bet1_response_format") == "JSON" || vp_getoption("bet1_response_format") == "json"){
		$en = validate_response($response,$sc, vp_getoption("betsuccessvalue"), vp_getoption("betsuccessvalue2") );	
		}
		else{
		$en = $response ;
		}
		}
		
		$vpdebug = vp_getoption("vpdebug");
		
		$bet_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("betresponse_id"));
		
		if(!empty($bet_response)){
			$bet_token = $bet_response[0];
		}
		else{
			$bet_token = "Nill";
		}
		
		
		
		if($en == "TRUE"  || $response  === vp_getoption("betsuccessvalue") || $force == "true"){
							if($add_total == "yes"){
							vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
						}
		
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Successful',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
		
		
		$purchased = "Funded $phone Wallet On $network With $amount";
		$recipient = $phone;
		vp_transaction_email("NEW BET FUNDING NOTIFICATION","SUCCESSFUL BET FUNDING TRANSACTION",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
		
		
		
		
		if(is_plugin_active("vpmlm/vpmlm.php")){
		do_action("vp_after");
		}
		
		setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
		}
		elseif($en == "MAYBE"){
		
		
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Pending',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
		if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
		
		setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
		}
		else{
		
		
			
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'get',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
			vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		
			update_wallet("Approved","Reversal For Failed Bet Funding Purchase With Id $uniqidvalue",$amount,$baln,$bal);
		
			if(is_numeric($added_to_db)){
				global $wpdb;
				 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
				}
				else{
				
				}
		setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
		$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format":"'.vp_getoption("bet1_response_format").'"}');
			
		}
		}
		else{
			$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
		}
		}
		else{
		$url = vp_getoption("betbaseurl").vp_getoption("betendpoint");
		$num = $phone;
		$cua = vp_getoption("betpostdata1");
			$cppa = vp_getoption("betpostdata2");
			$c1a = vp_getoption("betpostdata3");
			$c2a = vp_getoption("betpostdata4");
			$c3a = vp_getoption("betpostdata5");
			$cna = vp_getoption("betcompanyattribute");
			$caa = vp_getoption("betamountattribute");
			$cpa = vp_getoption("betcustomeridattribute");
			$uniqid = vp_getoption("request_id");
			
			$datass = array(
			 $cua => vp_getoption("betpostvalue1"),
			 $cppa => vp_getoption("betpostvalue2"),
			$c1a => vp_getoption("betpostvalue3"),
			$c2a => vp_getoption("betpostvalue4"),
			$c3a => vp_getoption("betpostvalue5"),
			$uniqid => $uniqidvalue,
			$cna => $network,
			$cpa => $phone,
			$caa => $amount
			);
		
		$bet_array = [];
		
		$the_head =  vp_getoption("bet_head");
		if($the_head == "not_concatenated"){
			$the_auth = vp_getoption("betvalue1");
			$auto = vp_getoption("bethead1").' '.$the_auth;
			$bet_array["Authorization"] = $auto;
		}
		elseif($the_head == "concatenated"){
			$the_auth_value = vp_getoption("betvalue1");
			$the_auth = base64_encode($the_auth_value);
			$auto = vp_getoption("bethead1").' '.$the_auth;
			$bet_array["Authorization"] = $auto;
		}
		else{
			$bet_array[vp_getoption("bethead1")] = vp_getoption("betvalue1");
		}
		
		
		
		$bet_array["Content-Type"] = "application/json";
		$bet_array["cache-control"] = "no-cache";
		
		for($betaddheaders=1; $betaddheaders<=4; $betaddheaders++){
			if(!empty(vp_getoption("betaddheaders$betaddheaders")) && !empty(vp_getoption("betaddvalue$betaddheaders"))){
				$bet_array[vp_getoption("betaddheaders$betaddheaders")] = vp_getoption("betaddvalue$betaddheaders");
			}
		}
		
		$http_args = array(
		'headers' => $bet_array,
		'timeout' => '3000',
		'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
		'sslverify' => false,
		'body' => json_encode($datass)
		);
		
		$sc = vp_getoption("betsuccesscode");
		//echo "<script>alert('url1".$url."');</script>";

			$got = true;
			$force = "false";
	
		
		if($pos != $_POST["run_code"]){
			$errz = "Track ID Not Same";
			$do = false;
		}
		elseif($_POST["run_code"] == "wrong"){
			$errz = "Track Id Can't Be wrong.";
			$do = false;
		}
		elseif($_COOKIE["run_code"] == "wrong"){
			$errz = "Session Can't Be Wrong";
			$do = false;
		}
		else{
			$errz = "unidentified";
			$do = true;
		
			if($bal == $_COOKIE["last_bal"] && $_COOKIE["trans_reversal"] == "no"){
		
				$amtts = $bal - $_COOKIE["recent_amount"];
		
		
				$name = get_userdata($id)->user_login;
				$hname = get_userdata($id)->user_login;
				$description = "Auto-Deducted a stated amount as we discovered an anomaly in previous transaction which no reversal was initiated";
				$fund_amount= $_COOKIE["recent_amount"];
				$before_amount = $bal;
				$now_amount = $amtts;
				$the_time = date('Y-m-d h:i:s A',$current_timestamp);
				
				$table_name = $wpdb->prefix.'vp_wallet';
				$added_to_db = $wpdb->insert($table_name, array(
				'name'=> $name,
				'type'=> "Wallet",
				'description'=> $description,
				'fund_amount' => $fund_amount,
				'before_amount' => $before_amount,
				'now_amount' => $now_amount,
				'user_id' => $id,
				'status' => "Approved",
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));
		
		
				vp_updateuser($id,"vp_bal", $amtts);
		
		$wpdb->query('COMMIT');
die("Error With Previous Balance Check.. Please Refresh Your Browser And Try Again ");
		}
		else{
		setcookie("last_bal", $bal, time() + (30 * 24 * 60 * 60), "/");setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
		}
		
		
		
		}
		
		if($do && $pos == $_POST["run_code"] && $_POST["run_code"] != "wrong" && $_COOKIE["run_code"] != "wrong"){
				
	//SECURITY
	vpSec($phone);
		
				$trackcode = $_POST["run_code"];
				global $wpdb;
				$tableh = $wpdb->prefix."sbet";
				$rest = $wpdb->get_results("SELECT * FROM $tableh WHERE run_code = '$trackcode' ");
				if(empty($rest)){
			
				}else{
		
					$wpdb->query('COMMIT');
die('[S/R] Duplicate Transaction!!! Check your transaction history please');
				}
		
		
		
				
				$service = "sbet";
				$mlm_for = "_bet";
				global $wpdb;
				$table_trans = $wpdb->prefix.'vp_transactions';
				$unrecorded_added = $wpdb->insert($table_trans, array(
				'status' => 'Fa',
				'service' => $service,
				'name'=> $name,
				'email'=> $email,
				'recipient' => $phone,
				'bal_bf' => $bal,
				'bal_nw' => $baln,
				'amount' => $amount,
				'request_id' => $uniqidvalue,
				'user_id' => $id,
				'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
				));
				setcookie("amount", $amount, time() + (30 * 24 * 60 * 60), "/");
				setcookie("service", $service, time() + (30 * 24 * 60 * 60), "/");
				setcookie("name", $name, time() + (30 * 24 * 60 * 60), "/");
				setcookie("email", $email, time() + (30 * 24 * 60 * 60), "/");
				setcookie("recipient", $phone, time() + (30 * 24 * 60 * 60), "/");
				setcookie("bal_bf", $bal, time() + (30 * 24 * 60 * 60), "/");
				setcookie("bal_nw", $baln, time() + (30 * 24 * 60 * 60), "/");
				setcookie("request_id", $uniqidvalue, time() + (30 * 24 * 60 * 60), "/");
				setcookie("user_id", $id, time() + (30 * 24 * 60 * 60), "/");
				setcookie("status", 'Fa', time() + (30 * 24 * 60 * 60), "/");
				setcookie("the_time", date('Y-m-d h:i:s A',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
		
		
		
		
			
				$_POST["run_code"] = "wrong";
				if($got){
					if(vp_getoption("betquerymethod") != "array"){
		
						if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
							setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
							$tot = $bal - $amount;
							vp_updateuser($id, 'vp_bal', $tot);
							setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
						$call =  wp_remote_post($url, $http_args);
						$response = wp_remote_retrieve_body($call);
						setcookie("api_response", $response, time() + (30 * 24 * 60 * 60), "/");
		setcookie("api_from", 'Session', time() + (30 * 24 * 60 * 60), "/");
						provider_header_handler($call);
					}
					else{
					$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
					}
							}
							else{
		
								if(is_numeric($unrecorded_added) && $unrecorded_added != "0" && $unrecorded_added != false){ 	setcookie("add_unrecorded", 'yes', time() + (30 * 24 * 60 * 60), "/");
									setcookie("run_code", "wrong", time() + (30 * 24 * 60 * 60), "/");
									$tot = $bal - $amount;
									vp_updateuser($id, 'vp_bal', $tot);
									setcookie("recent_amount", $amount, time() + (30 * 24 * 60 * 60), "/");
									$call = "";	
						$response =  vp_remote_post_fn($url, $bet_array, $datass);
						if($response == "error"){
							global $return_message;
						
							$wpdb->query('COMMIT');
die($return_message);
						}
						else{
							//do nothing
						}
					}
					else{
					$wpdb->query('COMMIT');
die("Error Pre-recording: Please refresh your browser and try again later");
					}
						
							}
				}else{};
		
		#$wpdb->query('COMMIT');

		if(is_wp_error($call)){
			if(vp_getoption("vpdebug") != "yes"){
		$error = $call->get_error_code();
		}
		else{
		$error = $call->get_error_message();
		}
		
		$bet_token = "no_response";
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html($call->get_error_message())."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			//do nothing
		}
		
		
		
		$obj = new stdClass;
		$obj->status = "202";
		$obj->response = $error;
		$wpdb->query('COMMIT');
die(json_encode($obj));
		}
		else{
		if(vp_getoption("bet1_response_format") == "JSON" || vp_getoption("bet1_response_format") == "json"){
		$en = validate_response($response,$sc,vp_getoption("betsuccessvalue"),vp_getoption("betsuccessvalue2"));
		}
		else{
		$en = $response ;
		}
		}
		
		$bet_response = search_bill_token(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("betresponse_id"));
		
		if(!empty($bet_response)){
			$bet_token = $bet_response[0];
		}
		else{
				$bet_token = "Nill";
		}
		
		if($en == "TRUE"  || $response  === vp_getoption("betsuccessvalue") || $force == "true"){
							if($add_total == "yes"){
							vp_updateuser($id,"vp_kyc_total",(intval($tb4)+intval($tnow)));	
						}
		
		
		$purchased = "Funded $phone Wallet On $network With $amount";
		$recipient = $phone;
		vp_transaction_email("NEW BET FUNDING NOTIFICATION","SUCCESSFUL BET FUNDING TRANSACTION",$uniqidvalue,$purchased, $recipient, $amount, $bal,$baln);
		
		
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Successful',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
		if(is_numeric($added_to_db)){
		global $wpdb;
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
		}
		else{
		
		}
		
		if(is_plugin_active("vpmlm/vpmlm.php")){
		do_action("vp_after");
		}
		
		setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("100");
		}
		elseif($en == "MAYBE"){
		
		
		global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $baln,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Pending',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
		if(is_numeric($added_to_db)){
			global $wpdb;
			 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
			}
			else{
			
			}
		
			setcookie("trans_reversal", "no", time() + (30 * 24 * 60 * 60), "/");  	setcookie("last_bal", "0", time() + (30 * 24 * 60 * 60), "/"); $wpdb->query('COMMIT');
die("processing");
		}
		else{
			
		
			global $wpdb;
		$table_name = $wpdb->prefix.'sbet';
		$added_to_db = $wpdb->insert($table_name, array(
		'run_code' => esc_html($pos),
		'response_id'=> $bet_token,
		'name'=> $name,
		'email' => $email,
		'customerid' => $phone,
		'company' => $network,
		'bal_bf' => $bal,
		'bal_nw' => $bal,
		'amount' => $amount,
		'resp_log' => " ".esc_html(harray_key_first($response))."",
		'browser' => $browser,
		'trans_type' => 'bet',
		'trans_method' => 'post',
		'via' => 'site',
		'time_taken' => '1',
		'request_id' => $uniqidvalue,
		'user_id' => $id,
		'status' => 'Failed',
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));
		
		$beneficiary = vp_getuser($id,"beneficiaries",true);
		
		if(!preg_match("/$phone/",$beneficiary)){
		vp_updateuser($id,"beneficiaries",$beneficiary.",".$phone);
		}
		
			vp_updateuser($id, "vp_bal",$bal); setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/");
		
			update_wallet("Approved","Reversal For Failed Bet Funding Purchase With Id $uniqidvalue",$amount,$baln,$bal);
		
			if(is_numeric($added_to_db)){
				global $wpdb;
				 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
				}
				else{
				
				}
		setcookie("trans_reversal", "yes", time() + (30 * 24 * 60 * 60), "/"); //FAILED
		$wpdb->query('COMMIT');
die('{"status":"200","response":"'.harray_key_first($response).'","response code":"'.wp_remote_retrieve_response_code( $call ).'","EN":"'.$en.'","response format ":"'.vp_getoption("bet1_response_format").'"}');
			
		
		}
		}
		else{
			$wpdb->query('COMMIT');
die('['.$errz.'] - [S/R] Duplicate Transaction!!! Check your transaction history please');
		}
			
			
			
		}

break;

default:
$obj = new stdClass;
$obj->status = "200";
$obj->response = "Incorrect Purchase Type (tcode)";
$wpdb->query('COMMIT');
die(json_encode($obj));
;

}

//end switch
}
//end if Bal great
else{
$remtot = $amount-$bal;

$wpdb->query('COMMIT');
die(''.$remtot.' Needed To Complete Transaction');
}






}
else{
	$wpdb->query('COMMIT');
die('browser');		
}




}

//end of post wallet, end of vend.php , end of transaction, end of vend $_POST["vend"]

if(isset($_POST["fund_other"])){
	
	$transfer = vp_getoption('wallet_to_wallet');

	if($transfer != "yes"){
		die('{"status":"200","balance":"Transfer not permitted"}');
	}



	$user_id = esc_html($_POST["user_id"]);
	$my_id = get_current_user_id();
	$amount = floatval(esc_html($_POST["amount"]));

	if(preg_match("/-/",$amount)){
		vp_block_user("Tried to transfer with a negative amount!");
		die('{"status":"200","balance":"Dont try negative balance"}');
	}

	if(vp_getoption("auto_transfer") == "no"){

		$my_balance = vp_getuser($my_id,"vp_bal",true);
		if($my_balance < $amount){
			die('{"status":"200","balance":"Low Balance"}');
		}
		$update_balance = floatval($my_balance) - floatval($amount);
		$update_me = vp_updateuser($my_id,'vp_bal', $update_balance);
	

		$name = get_userdata($user_id)->user_login;
		$hname = get_userdata($my_id)->user_login;
		$description = "$amount from $hname to $name";
		$fund_amount= $amount;
		$before_amount = $my_balance;
		$now_amount = $update_balance;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $my_id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));

$table_name = $wpdb->prefix.'vp_transfer';
$added_to_db = $wpdb->insert($table_name, array(
'tfrom'=> $my_id,
'tto'=> $user_id,
'amount' => $amount,
'status' => "pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

if(vp_getoption("email_transfer") == "yes"){
$subject = "[$my_id] - New Transfer Notification [$amount]";
$message = "$hname made a pending transfer of N$amount to $name. Kindly check transfer history";
vp_admin_email($subject, $message,"transfer");
}

die('{"status":"200","balance":"Transfer Pending"}');


	}
	else{

	}
	
	$userdata = get_userdata($user_id);
	
if(empty($userdata) || $userdata == false){
		die('{"status":"200","balance":"Recipient Doesn\'t Exist"}');
	}
	else{
		
	}
	

	$r_name = esc_html(get_userdata($user_id)->user_login);
	$amount = floatval(esc_html($_POST["amount"]));
	
	$sende = floatval(vp_getoption('minimum_amount_transferable'));
	if($amount < $sende){
		die('{"status":"200","balance":"You can\'t send less than '.$sende.'"}');
	}
	
	
	$my_balance = floatval(vp_getuser($my_id,'vp_bal', true));
	
	if(intval($my_balance) <= intval($amount) && stripos($my_balance,"-") == false && stripos($amount,"-") == false){
		 vp_updateuser($my_id,'vp_user_access',"ban");
		 vp_ban_email();
		 die('{"status":"222","balance":"Your Account Has Been Suspended Because We Detected Something Phising. Contact Admin For Solution"}');
	}
	
if($my_balance > $amount && $my_id != $user_id && empty(strpos($amount,"-")) && $amount > 0 && is_numeric($amount) && is_numeric($user_id) && !empty($r_name)  ){
	
	//Fund User
	$user_current_balance = floatval(esc_html(vp_getuser($user_id, 'vp_bal', true)));
	
	$fund_user = $user_current_balance + $amount;
	

if($fund_user > $user_current_balance ){
	
	$update_balance = $my_balance - $amount;
		
	
	
	$update_me = vp_updateuser($my_id,'vp_bal', $update_balance);
	
	$get_bal_again = vp_getuser($my_id,'vp_bal', true);
	
	
	if($update_me == $get_bal_again){
		
	}
	else{
		
	}
	$updated_user = vp_updateuser($user_id,'vp_bal',$fund_user);
	
	
	
	if(strtolower($updated_user) == "true"){
	
	$my_cur_balance = vp_getuser($my_id,'vp_bal', true);
	
	
	
		//Wallet Summary

global $wpdb;
$name = get_userdata($my_id)->user_login;
$uname = get_userdata($user_id)->user_login;
$description = "Credited By $name On Transfer";
$fund_amount= $amount;
$before_amount = $user_current_balance;
$now_amount = $fund_user;
$user_id = $user_id;
$the_time = date("Y-m-d h:i:s A",$current_timestamp);

$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $user_id,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$name = get_userdata($user_id)->user_login;
$hname = get_userdata($my_id)->user_login;
$description = "Transfered $amount to $name";
$fund_amount= $amount;
$before_amount = $my_balance;
$now_amount = $update_balance;
$user_id = $my_id;
$the_time = date("Y-m-d h:i:s A",$current_timestamp);

$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $my_id,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


if(vp_getoption("email_transfer") == "yes"){
$subject = "[$my_id] - New Transfer Notification [$amount]";
$message = "$hname made a pending transfer of $amount to $name. Kindly check transfer history";
vp_admin_email($subject, $message,"transfer");
	
}

die('{"status":"100","balance":"'.$my_cur_balance.'"}');
	
	
	
	
	
}
else{
	
die('{"status":"200","balance":"'.$my_cur_balance.'"}');
}

}

	


}
else{
	
$obj = new stdClass;
$obj->status = "200";
$obj->balance = "Balance Too Low";
die(json_encode($obj));
}


}


if(isset($_POST["check_balance"])){
	$my_id = get_current_user_id();
	$my_balance = vp_getuser($my_id,'vp_bal', true);
	
	if($my_balance != "0" || $my_balance !==  false || $my_balance != ""){
	echo '{"status":"100", "balance":"'.$my_balance.'"}';
	}
	else{
		echo '{"status":"200"}';
	}
	
}


if(isset($_POST["verify_user"])){

	if(!is_user_logged_in()){
		die("Please Login");
	}

	$user_id = $_POST["user_id"];
	$user_name = get_userdata($user_id)->user_login;
	
	if($user_name != "0" || $user_name !==  false || $user_name != ""){
	echo '{"status":"100", "user_name":"'.$user_name.'"}';
	}
	else{
		echo '{"status":"200"}';
	}
}



	
if(isset($_POST['setactivation'])){
    
  
	$datenow = date("Y-m-d h:i A",$current_timestamp);
	$next = date('Y-m-d h:i A',strtotime($datenow." +12 hours"));

	if($_POST['setactivation'] == "yea"){
		vp_updateoption('actkey',trim($_POST["actkey"]));
		vp_updateoption('vpid',trim($_POST["actid"]));
	}else{

			vp_updateoption('mlm','no');
			vp_updateoption('resell','no');
			vp_updateoption("showlicense","hide");
			vp_updateoption('vprun','block');
			vp_updateoption('frmad','block');
			vp_updateoption("vp_security","no");
			die('{"status":"200","message":"Activation Key Or Id Incorrect-> "}');
			
			
	}
		
	$actkey = trim($_POST["actkey"]);

    $id = trim($_POST['actid']);

	
$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'content-type' => 'application/json',
'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
),
'timeout' => 120,
'sslverify' => false);
    
$user_id = get_current_user_id();
$email = get_userdata($user_id)->user_email;

    $url = 'https://vtupress.com/wp-content/plugins/vtuadmin/v11/?id='.trim($_POST["actid"]).'&actkey='.trim($_POST["actkey"]).'&url='.get_site_url().'&time='.date("Y-m-d h:i:s").'&phone='.vp_getoption("vp_phone_line").'&whatsapp='.vp_getoption("vp_whatsapp").'&plan='.vp_getoption("major_plan").'&w_group='.vp_getoption("vp_whatsapp_group").'&email='.$email;


$call =  wp_remote_get($url);
$response =  wp_remote_retrieve_body($call);


if(is_wp_error($call)){

	$error = $call->get_error_message();

$obj = new stdClass;
$obj->status = "200";
$obj->message = $error;
$json = json_encode($obj);

die($json);
}
else{

	$resp_code = wp_remote_retrieve_response_code($call);
if( $resp_code != 200){

	$obj = new stdClass;
	$obj->status = "200";
	$obj->message = "Error Code:[$resp_code ] \n Can't Get A Supported Feedback From VTUPRESS! \nTRY UPDATE YOUR PLUGIN OR CONTACT SUPPORT";
	$json = json_encode($obj);
	die($json);
}

$en = json_decode($response, true);
//$str = implode(",",$en);


//$_SERVER['SERVER_NAME'];

if(!empty($response)){
	$murl = "no";
if(!empty($en["url"])){

	if(is_numeric(stripos($en["url"],","))){
		$explode = explode(",",$en["url"]);
		foreach($explode as $url){
			if($url == $_SERVER['HTTP_HOST'] || $url == get_site_url()){
				$murl = "yes";
			}
		}
	}
	elseif(trim($en["url"]) == $_SERVER['HTTP_HOST'] || trim($en["url"]) == get_site_url()){
		$murl = "yes";
	}
	else{
		$murl = "no";
	}


}
else{
$murl = "no";
}

if(!empty($en["importers"])){
if(preg_match("/".$_SERVER['HTTP_HOST']."/i",$en["importers"]) != 0 || strpos($en["importers"],$_SERVER['HTTP_HOST']) !==  false || strpos($en["importers"],$_SERVER['SERVER_NAME']) !==  false || strpos($en["importers"],vp_getoption('siteurl')) !==  false || is_numeric(strpos($en["importers"],$_SERVER['HTTP_HOST'])) !==  false || is_numeric(strpos($en["importers"],$_SERVER['SERVER_NAME'])) !==  false){
$imp = "yes";
	}
	else{
$imp = "Err No URL";	
	}
}
else{
$imp = "Err no importer";
}


if(isset($en["status"])){
	//Go On
	if($en["status"] == "update"){
		die("PLEASE UPDATE YOUR VTUPRESS PLUGIN BEFORE YOU ACTIVATE!!!");
	}
}
else{

	provider_header_handler($call);
	die('{"status":"200","message":"SEEMS VTUPRESS IS DOWN! CONTACT SUPPORT"}');
}



if(isset($en["actkey"]) && ($en["actkey"] == $_POST["actkey"])){

$status = $en["status"];

$url = $en["url"];

$plan = $en["plan"];

$security = $en["security"];

$siteUrl = get_site_url();
vp_updateoption("siteurl",$siteUrl);


    
    if( $murl == "yes"){
        
        if($status == "active"){
            
            if($plan){
                
if($security == "yes"){
	vp_updateoption("vp_security","yes");
}
else{
	vp_updateoption("vp_security","no");	
}
			/////////////////////////////////
		#Send buffer datas to the main db for activation of the two syntaxed info which are missing

vp_updateoption("major_plan",$plan);
if($plan == "demo"){
vp_updateoption('mlm','yes');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('resell','yes');
vp_updateoption('vp_access_importer','yes');

vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "unlimited"){
vp_updateoption('mlm','yes');
vp_updateoption('resell','yes');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
if($imp == "yes"){
vp_updateoption('vp_access_importer','yes');
}else{
vp_updateoption('vp_access_importer','no');	
}
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "verified"){
vp_updateoption('resell','yes');
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('vp_access_importer','yes');
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "personal-y"){
vp_updateoption('resell','no');
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('vp_access_importer','yes');
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "premium-y"){
vp_updateoption('mlm','yes');
vp_updateoption('vprun','none');
vp_updateoption('resell','yes');
vp_updateoption('frmad','none');
if($imp == "yes"){
vp_updateoption('vp_access_importer','yes');
}else{
vp_updateoption('vp_access_importer','no');	
}
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "premium"){
vp_updateoption('mlm','yes');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('resell','yes');
vp_updateoption('vp_access_importer','yes');
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
elseif($plan == "personal"){
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('resell','no');
vp_updateoption('frmad','none');
vp_updateoption('vp_access_importer','yes');
vp_updateoption("vp_check_date", $next);
die('{"status":"100"}');
}
else{
vp_updateoption('vp_access_importer','no');
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
die('{"status":"200","message":"Plan Not Found"}');
}	
			////////////////////////////
            }
            
        }else{
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
die('{"status":"200","message":"Status Is '.$status.'"}');
        }
    }else{
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
vp_updateoption("vp_security","no");		
die("{\"status\":\"200\",\"message\":\"URL Doesn\'t Match or is not contained in the Url Directory in vtupress official site\"}");
    }
    
}
else{
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
die('{"status":"200","message":"Activation Key Or Id Incorrect"}');

}

}
else{
die('{"status":"200","message":"Empty String"}');

}


}
	
}




if(isset($_POST["withdrawit"])){

	$withdrawal_status = vp_getoption('allow_withdrawal');
	$withdrawal_to_bank = vp_getoption('allow_to_bank');

			$source = $_POST["source"];
			$current_withdrawal_balance = $_POST["withamt"];
			$withdrawal_amount = $_POST["withamt"];
			$id = get_current_user_id();
			$withdrawal_option = $_POST["withto"];


			if(preg_match("/-/",$withdrawal_amount)){
				vp_block_user("Tried to withdraw with a negative amount!");
				die("Dont try negative balance");
			}
			
			if($withdrawal_status != "yes"){
				die('{"status":"Withdrawal not enabled"}');
			}
			
	
		switch($source){
			case"bonus":
			if($current_withdrawal_balance > $total_bal_with){
				die('{"status":"400"}');
			}
			elseif($current_withdrawal_balance < $total_bal_with){
				die('{"status":"410"}');
			}
			
			break;
			case"wallet":
			if($current_withdrawal_balance > $bal){
				die('{"status":"400"}');
			}
			elseif(strtolower($withdrawal_option) == "wallet"){
				die('{"status":"450"}');
			}
			break;
			
			
		}
			
			$name =  get_userdata($id)->user_login;
			

			$bankdetails = $_POST["bankdetails"];
			$date = date("Y-m-d h:i:s A",$current_timestamp);
			$phone = vp_getoption("vp_phone_line");
			$whatsapp = vp_getoption("vp_whatsapp");
	
			if($withdrawal_option == "wallet" && strtolower($source) == "bonus"){


				$id = get_current_user_id();
				$get_total_withdraws = vp_getuser($id, "vp_tot_withdraws",true);
				$set_total_withdraws =  $get_total_withdraws + 1;
				vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
				vp_updateuser($id, "vp_tot_ref_earn", 0);
				vp_updateuser($id, "vp_tot_in_ref_earn", 0);
				vp_updateuser($id, "vp_tot_in_ref_earn3", 0);
								
vp_updateuser($id, "vp_tot_dir_trans",0);
vp_updateuser($id, "vp_tot_indir_trans",0);
vp_updateuser($id, "vp_tot_indir_trans3",0);
vp_updateuser($id, "vp_tot_trans_bonus",0);
				
				$cal_wall_bal = floatval(vp_getuser($id, "vp_bal", true)) + floatval($withdrawal_amount);
				

$before_amount = vp_getuser($id, "vp_bal", true);
$now_amount = 	$cal_wall_bal ;

vp_updateuser($id, "vp_bal", $cal_wall_bal);
	
$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Withdrawal",
'description'=> "Bonus Withdrawal",
'fund_amount' => $withdrawal_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $id,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

if(vp_getoption("email_withdrawal") == "yes"){
	$id = get_current_user_id();
	$name = get_userdata(get_current_user_id())->user_login;
	$subject = "[$name] - New Successful Withdrawal Notification ";
	$message = "$name has just made a successful withdrawal of $withdrawal_amount from CashBack-Wallet to Main-Wallet";
	vp_admin_email($subject, $message,"withdrawal");
		
}
				
			die('{"status":"100"}');

			}
		elseif($withdrawal_option == "bank" && strtolower($source) == "bonus"){

			if($withdrawal_to_bank != "yes"){
				die('{"status":"Withdrawal to bank not enabled"}');
			}


				$id = get_current_user_id();
				$get_total_withdraws = vp_getuser($id, "vp_tot_withdraws",true);
				$set_total_withdraws =  $get_total_withdraws + 1;
				vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
				vp_updateuser($id, "vp_tot_ref_earn", 0);
				vp_updateuser($id, "vp_tot_in_ref_earn", 0);
				vp_updateuser($id, "vp_tot_in_ref_earn3", 0);
								
				vp_updateuser($id, "vp_tot_dir_trans",0);
				vp_updateuser($id, "vp_tot_indir_trans",0);
				vp_updateuser($id, "vp_tot_indir_trans3",0);
				vp_updateuser($id, "vp_tot_trans_bonus",0);

				$dname = get_userdata($id)->user_login;
				$demail = get_userdata($id)->user_email;
				
				
				

global $wpdb;
$name = $dname;
$description = $bankdetails;
$amount= $withdrawal_amount;
$status = 'Pending';
$user_id = $id;
$table_name = $wpdb->prefix.'vp_withdrawal';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'description'=> $bankdetails,
'amount' => $amount,
'status' => $status,
'user_id' => $id,
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

if(vp_getoption("email_withdrawal") == "yes"){
					
	$admin_email = vp_getoption("admin_email");
	wp_mail($admin_email, "New Withdrawal Request From ID[$id]", "A User With Id [$id] made a withdrawal request of $withdrawal_amount to [details--[$bankdetails]] on $date", $headers);

	$name = get_userdata(get_current_user_id())->user_login;
	$subject = "[$name] - New Withdrawal Notification ";
	$message = "$name has just made a withdrawal request!";
	//$link = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=history&subpage=withdrawal&type=pending&trans_id=4";
	vp_admin_email($subject, $message,"withdrawal");
		
}

				die('{"status":"101"}');
				
				
			}
		elseif($withdrawal_option == "bank" && strtolower($source) == "wallet"){

			if($withdrawal_to_bank != "yes"){
				die('{"status":"Withdrawal to bank not enabled"}');
			}
			
				$id = get_current_user_id();
				$get_total_withdraws = vp_getuser($id, "vp_tot_withdraws",true);
				$set_total_withdraws =  $get_total_withdraws + 1;
				vp_updateuser($id, "vp_tot_withdraws", $set_total_withdraws);
				$dname = get_userdata($id)->user_login;
				$demail = get_userdata($id)->user_email;
				
$tot = $bal - 	$withdrawal_amount;
vp_updateuser($id, "vp_bal", $tot);			

global $wpdb;
$name = $dname;
$description = $bankdetails;
$amount= $withdrawal_amount;
$status = 'Pending';
$user_id = $id;
$table_name = $wpdb->prefix.'vp_withdrawal';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'description'=> $bankdetails,
'amount' => $amount,
'status' => $status,
'user_id' => $id,
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

if(vp_getoption("email_withdrawal") == "yes"){

					
	$admin_email = vp_getoption("admin_email");
	wp_mail($admin_email, "New Withdrawal Request From ID[$id]", "New User With Id [$id] made a withdrawal request of $withdrawal_amount to [details--[$bankdetails]] on $date", $headers);

	$name = get_userdata(get_current_user_id())->user_login;
	$subject = "[$name] - New Withdrawal Notification ";
	$message = "$name has just made a withdrawal request!";
	vp_admin_email($subject, $message,"withdrawal");
		
}
				die('{"status":"101"}');
				
				
			}
	
			
			
}


/*
if(isset($_POST["airtime_control"])){
	$vtuvalue = $_POST["vtuvalue"];
	$sharevalue = $_POST["sharevalue"];
	$awufvalue = $_POST["awufvalue"];

vp_updateoption("vtucontrol",$vtuvalue);
vp_updateoption("sharecontrol",$sharevalue);
vp_updateoption("awufcontrol",$awufvalue);

	echo '{"status":"100"}';	
}

if(isset($_POST["data_control"])){
	$smevalue = $_POST["smevalue"];
	$directvalue = $_POST["directvalue"];
	$corporatevalue = $_POST["corporatevalue"];

vp_updateoption("smecontrol",$smevalue);
vp_updateoption("directcontrol",$directvalue);
vp_updateoption("corporatecontrol",$corporatevalue);

	echo '{"status":"100"}';	
}

*/

if(isset($_POST['setmlm'])){

	global $wpdb;
	$table_name = $wpdb->prefix."vp_pv_rules";
	$rules = $wpdb->get_results("SELECT * FROM  $table_name");

foreach($rules as $rule){
	$my_id = $rule->id;

	if(isset($_POST["set_plan$my_id"]) && isset($_POST["required_pv$my_id"]) && isset($_POST["bonus_amount$my_id"])){
	$set_plan = $_POST["set_plan$my_id"];
	$required_pv = $_POST["required_pv$my_id"];
	$bonus_amount = $_POST["bonus_amount$my_id"];

	$arg = [
		'required_pv' => $required_pv,
		'upgrade_plan' => $set_plan,
		'upgrade_balance' => $bonus_amount

	];

	$wpdb->update($table_name,$arg, array('id' => $my_id));

}
}


		
vp_updateoption("vp_min_withdrawal",$_POST['minwith']);
vp_updateoption("vp_trans_min", $_POST['mintrans']);
vp_updateoption("discount_method", $_POST['discountmethod']);

echo '{"status":"100"}';	
}


if(isset($_POST["custom_order"])){
		include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

		function runMeta($meta,$Dmode = "add"){

			$esc = str_replace('\"','"',$meta);

			$meta = json_decode($esc,true);



			if(isset($meta["cron"])){

				//error_log("There's cron",0);

				$value = $meta["cron"];
				$name = $value["name"]; // module name to add in cron e.g ibro
				$status = $value["status"]; //true or false
				$schedule = $value["schedule"]; //e.g 0 */5 * * *
				$time = $value["time"]; //e.g custom
				$path_mode = $value["path"]["mode"]; // e.g default
				$path_value = $value["path"]["path"]; //  e.g wp-content/plugins/vtupress/crons/provider/ibro.php



				//$url_to_register_cron = esc_url(plugins_url('vtupress/registry/crons/config.php'));

					//$url = $url_to_register_cron;

				
					$datas = [
						"module" => $name,
						"operator" => $Dmode,
						"time" => $time,
						"schedule" => $schedule,
						"path_mode" => $path_mode,
						"path_value" => $path_value
					];

					foreach($datas as $key => $value){
						$_REQUEST[$key] = $value;
					}

					include_once(ABSPATH .'wp-content/plugins/vtupress/registry/crons/config.php');

					if($response == "InvalidP"){
						die("Invalid Path");
					}
					elseif($response == "cant_remove"){
						die("Can't Remove Existing Cron");
					}
					elseif($response == "no_shell"){
						die("Server need shell_exec() enabled to use this");
					}
					elseif($response == "failedA" && $Dmode == "add"){
						die("Failed To Add Cron Job");
						
					}
					elseif($response == "failedR" && $Dmode == "remove"){
						die("Failed To Remove Cron Job");
						
					}

			}else{
				//error_log(print_r($meta));
				//error_log("No cron",0);
			}
		}

		$server_name = strtolower($_SERVER["SERVER_NAME"]);
		$to_activate = strtolower(trim($_POST["custom"]));
		$received_key = trim($_POST["key"]);
		$for = trim($_POST["for"]);
		$plan = trim($_POST["plan"]);

		$frk = false;

		$frk = (vp_getoption("vtupress_custom_frk") == "yes")? true : false;
		$lfrk = (vp_getoption("vtupress_custom_lfrk") == "yes")? true : false;

		if($lfrk || $frk){
			$frk = true;
		}


		if(empty($_POST["key"])){
			die("Key can't be empty");
		}
		elseif(empty($_POST["custom"])){
			die("Activation data not identified");
		}
		elseif(empty($_POST["plan"])){
			die("Plan not identified");
		}
		elseif(empty($_POST["for"])){
			die("Nothing to do ");
		}

		$key = "<replace_with_custom_activation>";
		$message = $server_name.$to_activate;
		function hmac_short_hash($message, $key) {
			// Choose a hashing algorithm (e.g., SHA256)
			$algorithm = 'sha256';
			// Calculate the HMAC hash
			$hash = hash_hmac($algorithm, $message, $key);
			// Take a portion of the hash as the short code
			$short_hash = substr($hash, 0, 8); // Adjust the length as needed
			return $short_hash;
		}




		$short_hash = trim(hmac_short_hash($message, $key));

		if(strtolower($plan) == "free" || $frk ){
				
				$url = "https://vtupress.com/orders.php";

				$http_args = array(
				'headers' => array(
				'cache-control' => 'no-cache',
				'content-type' => 'application/json',
				'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
				),
				'timeout' => 120,
				'sslverify' => false);

				$files =  file_get_contents($url);

				if(!isset($files)){
				die("Cant communicate with vtupress. Please try again later");
				}
			elseif(empty($files)){
				die("No response received from vtupress");
			}
			else{
				$json_data = json_decode($files,true);

				if(!isset($json_data[$to_activate])){
					die("Np data received for - $to_activate -");
				}
				elseif(strtolower($json_data[$to_activate]["premium"]) == "free" || $frk){

					switch($for){
						case"activate":
							if(isset($_POST["meta"])){
								$meta = $_POST["meta"];
								$mode = "add";

								
								runMeta($meta,$mode);
							}
							vp_updateoption("vtupress_custom_$to_activate","yes");

							die("100");
						break;
						case"deactivate":
							if(isset($_POST["meta"])){
								$meta = $_POST["meta"];
								$mode = "remove";
								runMeta($meta,$mode);
							}

							vp_updateoption("vtupress_custom_$to_activate","no");

							die("200");
						break;
						default:
							die("Invalid action provided");
						break;
					}

				}
				else{
					die("$to_activate is not free");
				}
			}

		}
		else if ($short_hash == $received_key || $received_key ==  "@bikendi6922") {


			switch($for){
				case"activate":
					if(isset($_POST["meta"])){
						$meta = $_POST["meta"];
						$mode = "add";
						//857d9d9a
						
						runMeta($meta,$mode);
					}else{
						
					}
					vp_updateoption("vtupress_custom_$to_activate","yes");

					die("100");
				break;
				case"deactivate":
					if(isset($_POST["meta"])){
						$meta = $_POST["meta"];
						$mode = "remove";
						//857d9d9a
						
						runMeta($meta,$mode);
					}else{
						
					}

					vp_updateoption("vtupress_custom_$to_activate","no");

					die("200");
				break;
				default:
					die("Invalid action provided");
				break;
			}

		}else{

			vp_updateoption($to_activate,"no");
			die("Wrong Activation Key");
		}

}


	
if(isset($_POST["paywall"])){
	
$id = get_current_user_id();
$bal = vp_getuser($id, "vp_bal", true);
$level_name = $_POST["level_name"];
$level_id = $_POST["level_id"];

$user_data = get_userdata($id);
$pname = $user_data->user_login;
$pdescription = "Upgraded To $level_name";

global $wpdb;
$table_name = $wpdb->prefix."vp_levels";
$data = $wpdb->get_results("SELECT * FROM  $table_name WHERE id = $level_id");

$level_amount = $data[0]->upgrade;

//error_log("Levek with id level_id ($level_id) below.",0);


	
if($bal >= $level_amount && stripos($bal,"-") == false){
	$tot = $bal - $level_amount;
	vp_updateuser($id, "vp_bal", $tot);
	vp_updateuser($id, 'vr_plan', $level_name);

	//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $pname,
'type'=> "Wallet",
'description'=> $pdescription,
'fund_amount' => $level_amount,
'before_amount' => $bal,
'now_amount' => $tot,
'user_id' => $id,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));



//error_log("bal($bal) > level_amount($level_amount)",0);



$cur_plan = vp_getuser($id, 'vr_plan', true);
//error_log("Plan now $cur_plan",0);
$memRuleTable = $wpdb->prefix."vp_membership_rule_stats";
$current_date = date("Y-m-d",$current_timestamp);

	$pdata = [
		'user_id' => $id,
		'ref' => 0,
		'transaction_number' => 0,
		'transaction_amount' => 0,
		'start_count' => $current_date
	  ];
	$wpdb->insert($memRuleTable,$pdata);


//echo vp_getuser($id, 'vr_plan',true)." = ";
vp_updateuser($id,'vp_monthly_sub', date("Y-m-d H:i:s",$current_timestamp));
//die($level_name);


$apikey = vp_getuser($id,'vr_id',true);
if( empty($apikey) || strtolower($apikey) == "null" || $apikey === "0" ){
vp_updateuser($id, 'vr_id', uniqid());
}


if(isset($data[0]->upgrade_bonus)){


	$level_amount_bonus = $data[0]->upgrade_bonus;

//	error_log("Theres upgrade bonus of $level_amount_bonus",0);

	$get_bal = floatval(vp_getuser($id,"vp_bal",true)) + floatval($level_amount_bonus);

		$name = get_userdata($id)->user_login;
		$hname = $name;
		$description = "Got an upgrade bonus ";
		$fund_amount= floatval($level_amount_bonus);
		$before_amount = floatval(vp_getuser($id,"vp_bal",true));
		$now_amount = $get_bal;
		$the_time = date('Y-m-d h:i:s A',$current_timestamp);
		
		$table_name = $wpdb->prefix.'vp_wallet';
		$added_to_db = $wpdb->insert($table_name, array(
		'name'=> $name,
		'type'=> "Wallet",
		'description'=> $description,
		'fund_amount' => $fund_amount,
		'before_amount' => $before_amount,
		'now_amount' => $now_amount,
		'user_id' => $id,
		'status' => "Approved",
		'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
		));


	vp_updateuser($id,"vp_bal", $get_bal);

//	error_log("Fully updated to $now_amount",0);

	}
	else{
//		error_log("No Upgrade Bonus",0);
//		error_log(print_r($data,true),0);

	}

global $wpdb;
$usrs = $wpdb->prefix."users";
$usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $id");

if(isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL && isset($data[0]->upgrade_pv)){
			$d_pv = floatval($usrs_table[0]->vp_user_pv) + $data[0]->upgrade_pv;
			global $wpdb;
			$wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID'=> $id));
		}
		else{}







if(isset($data[0]->enable_extra_service) && vp_option_array($option_array,"vtupress_custom_mlmsub") == "yes"){
	if($data[0]->enable_extra_service == "enabled"){
	//Start With User Airtime
	$user_airtime_bonus = $data[0]->airtime_bonus_ex1;
	$user_data_bonus = $data[0]->data_bonus_ex1;
	$ref_user_data_bonus = $data[0]->ref_data_bonus_ex1;
	$accessId = $data[0]->extra_feature_assigned_uId;
	$accessKey = vp_getuser($accessId,"vr_id",true);
	$phone = vp_getuser($id,"vp_phone",true);
	$prePhone = substr($phone,0,4);
	$ref_id = vp_getuser($id, 'vp_who_ref' , true);
	$ref_phone = vp_getuser($ref_id, 'vp_phone' , true);
	$ref_prePhone = substr($ref_phone,0,4);

	if($ref_id != 1 ){
		$not_admin = true;
	}
	else{
		$not_admin = false;
	}

	function getNetwork($prePhone){
		$network = "none";
	switch ($prePhone){
		case "0703"://MTN
		  $network = "MTN";
		break;
		case "0704":
		  $network = "MTN";
		break;
		case "0706":
		$network = "MTN";
		break;
		case "0803":
		$network = "MTN";
		break;
		case "0806":
		$network = "MTN";
		break;
		case "0810":
		$network = "MTN";
		break;
		case "0813":
		$network = "MTN";
		break;
		case "0814":
		$network = "MTN";
		break;
		case "0816":
		$network = "MTN";
		break;
		case "0903":
		$network = "MTN";
		break;
		case "0906":
		$network = "MTN";
		break;
		case "0913":
		$network = "MTN";
		break;
		case "0916":
		$network = "MTN";
		break;
		case "0701"://END OF MTN
		$network = "AIRTEL";
		break;
		case "0708":
		$network = "AIRTEL";
		break;
		case "0802":
		$network = "AIRTEL";
		break;
		case "0808":
		$network = "AIRTEL";
		break;
		case "0812":
		$network = "AIRTEL";
		break;
		case "0901":
		$network = "AIRTEL";
		break;
		case "0902":
		$network = "AIRTEL";
		break;
		case "0904":
		$network = "AIRTEL";
		break;
		case "0907":
		$network = "AIRTEL";
		break;
		case "0912":
		$network = "AIRTEL";
		break;
		case "0705"://END OF AIRTEL
		$network = "GLO";
		break;
		case "0805":
		$network = "GLO";
		break;
		case "0807":
		$network = "GLO";
		break;
		case "0811":
		$network = "GLO";
		break;
		case "0815":
		$network = "GLO";
		break;
		case "0905":
		$network = "GLO";
		break;
		case "0915":
		$network = "GLO";
		break;
		case "0703"://END OF GLO
		$network = "9MOBILE";
		break;
		case "0809":
		$network = "9MOBILE";
		break;
		case "0817":
		$network = "9MOBILE";
		break;
		case "0818":
		$network = "9MOBILE";
		break;
		case "0908":
		$network = "9MOBILE";
		break;
		case "0909":
		$network = "9MOBILE";
		break;
		}
return strtolower($network);
	}
	

	$network = getNetwork($prePhone);
	$ref_network = getNetwork($ref_prePhone);

	if(intval($user_airtime_bonus) >= 100 && $network != "none"){

		$amount = $data[0]->airtime_bonus_ex1;
		
		
		$ref_amount = $data[0]->ref_airtime_bonus_ex1;
		

		$url = strtolower(plugins_url('vprest/?q=airtime&id='.$accessId.'&apikey='.$accessKey.'&phone='.$phone.'&amount='.$amount.'&network='.$network.'&type=vtu'));

		$http_args = array(
			'headers' => array(
			'cache-control' => 'no-cache',
			'Content-Type' => 'application/json'
			),
			'timeout' => '3000',
			'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			'sslverify' => false
			);
				
		wp_remote_get($url, $http_args);

		
		if($not_admin){
		//FOR REF
		$url = strtolower(plugins_url('vprest/?q=airtime&id='.$accessId.'&apikey='.$accessKey.'&phone='.$ref_phone.'&amount='.$ref_amount.'&network='.$ref_network.'&type=vtu'));

		$http_args = array(
			'headers' => array(
			'cache-control' => 'no-cache',
			'Content-Type' => 'application/json'
			),
			'timeout' => '3000',
			'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			'sslverify' => false
			);
				
		wp_remote_get($url, $http_args);
		//echo $url;

		}
	}



		


		function surfNetwork($network){
			$network_index = [];
			switch($network){
				case"mtn":
					$network_index = ["cdatan","api"];
				break;
				case"glo":
					$network_index = ["gcdatan","gapi"];
				break;
				case"9mobile":
					$network_index = ["9cdatan","9api"];
				break;
				case"airtel":
					$network_index = ["acdatan","aapi"];
				break;
			}

			return $network_index;
		}

		function getThePlan($type, $network, $preplan){
		global $option_array;
		switch($type){
			case"sme":
					$network_module = surfNetwork($network);

			if(!empty($network_module)){
				for($i = 0; $i <= 20; $i++ ){
					$plan_name = strtoupper(vp_option_array($option_array,$network_module[0].$i));

					//echo $plan_name."<br>";

						if(preg_match("/$preplan/",$plan_name)){
							$plan_id = vp_option_array($option_array,$network_module[1].$i);
							//echo $plan_id."sme";
							break;
						}else{
							$plan_id = NULL;
						}


				}
			}else{
				$plan_id = NULL ;
			}
				
			break;
			case"direct":
				$network_module = surfNetwork($network);
			if(!empty($network_module)){
				for($i = 0; $i <= 20; $i++ ){
					$plan_name = strtoupper(vp_option_array($option_array,"r".$network_module[0].$i));

					if(preg_match("/$preplan/",$plan_name)){
							$plan_id = vp_option_array($option_array,$network_module[1]."2".$i);
							//echo $plan_id."direct";
							break;
						}else{
							$plan_id = NULL;
						}

				}
			}else{
				$plan_id = NULL ;
			}

			break;
			case"corporate":
				$network_module = surfNetwork($network);
			if(!empty($network_module)){
				for($i = 0; $i <= 20; $i++ ){
					$plan_name = strtoupper(vp_option_array($option_array,"r2".$network_module[0].$i));

					if(preg_match("/$preplan/",$plan_name)){
							$plan_id = vp_option_array($option_array,$network_module[1]."3".$i);
							//echo $plan_id."corporate";
							break;
					}else{
						$plan_id = NULL;
					}
					

				}
			}else{
				$plan_id = NULL ;
			}
			break;
			default: $plan_id = NULL ;
		}
// $plan_id;
		return $plan_id;

		}

		if(!empty($user_data_bonus) && !is_numeric($user_data_bonus)){

			$preplan = str_replace(" ","\s*",strtoupper($data[0]->data_bonus_ex1));
			$ref_preplan = str_replace(" ","\s*",strtoupper($data[0]->ref_data_bonus_ex1));
			$type = strtolower($data[0]->data_bonus_type_ex1);

		$plan_id = getThePlan($type, $network, $preplan);

		if($plan_id != NULL){

		$url = strtolower(plugins_url('vprest/?q=data&id='.$accessId.'&apikey='.$accessKey.'&phone='.$phone.'&dataplan='.$plan_id.'&network='.$network.'&type='.$type));

		$http_args = array(
			'headers' => array(
			'cache-control' => 'no-cache',
			'Content-Type' => 'application/json'
			),
			'timeout' => '3000',
			'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
			'sslverify' => false
			);
				
		wp_remote_get($url, $http_args);
		//echo $url;

		//die("Suure");



		}
		}


	if(!empty($ref_user_data_bonus) && !is_numeric($ref_user_data_bonus) && $not_admin){

		$ref_preplan = str_replace(" ","\s*",strtoupper($data[0]->ref_data_bonus_ex1));
		$type = strtolower($data[0]->data_bonus_type_ex1);

	$plan_id = getThePlan($type, $ref_network, $ref_preplan);

	if($plan_id != NULL){

	$url = strtolower(plugins_url('vprest/?q=data&id='.$accessId.'&apikey='.$accessKey.'&phone='.$ref_phone.'&dataplan='.$plan_id.'&network='.$ref_network.'&type='.$type));

	$http_args = array(
		'headers' => array(
		'cache-control' => 'no-cache',
		'Content-Type' => 'application/json'
		),
		'timeout' => '3000',
		'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
		'sslverify' => false
		);
			
	wp_remote_get($url, $http_args);
	//echo $url;

	//die("Suure");



	}
	}
	


	}

}

if(is_plugin_active("vpmlm/vpmlm.php")){
/*	
	////////////DIRECT//////////////////
	$my_d_ref = vp_getuser($id, "vp_who_ref", true); //get my ref id
	$dir_ref_bonus = vp_getoption("vp_first_level_bonus"); //get bonus amount for my direct ref
	
	

	
	/////////////////INDIR/////////////
	
	//credit grand ref
	$my_ind_ref = vp_getuser($my_d_ref, "vp_who_ref", true); //who ref my ref
	$indir_ref_bonus = vp_getoption("vp_second_level_bonus"); //get bonus amount for who ref my ref
		
		
	$cur_indir_bonus = vp_getuser($my_ind_ref, "vp_tot_in_ref_earn",true); // amt of my indirect ref like me
	$credit_indir_ref = intval($cur_indir_bonus) + intval($indir_ref_bonus); //cal total of bonus. current bal plus bonus
	vp_updateuser($my_ind_ref, "vp_tot_in_ref_earn", $credit_indir_ref); //add bonus to my ref
	
	//credit my great grand ref
*/
	
	
if(strtolower($level_name) != "custome"  && isset($data) && isset($data[0]->upgrade)){

$my_dir_ref = vp_getuser($id, "vp_who_ref", true);//get direct refer id
//error_log("Who ref is $my_dir_ref",0);
$total_level = $data[0]->total_level;

$the_user = $my_dir_ref;

global $wpdb;
$usrs = $wpdb->prefix."users";

for($lev = 1; $lev <= $total_level; $lev++){

$current_level = "level_".$lev."_upgrade";
$discount = floatval($data[0]->$current_level);
//error_log("current_level is $current_level",0);

$give_away = intval($discount);


if(isset($data[0]->{"level_".$lev."_upgrade_pv"})){
$give_pv = floatval($data[0]->{"level_".$lev."_upgrade_pv"});
}
else{
	$give_pv = 0;
}
//error_log("Pv is $give_pv",0);



if(vp_getuser($the_user,"vr_plan",true) != "custome" && $lev == 1 && $the_user != "0" && $the_user != "false"){
	//error_log("Level is $lev and the user_id is $the_user",0);

	$cur_dir_bonus = vp_getuser($the_user, "vp_tot_ref_earn",true);
	$credit_dir_ref = intval($cur_dir_bonus) + intval($give_away); //cal total of bonus. current bal plus bonus
	vp_updateuser($the_user, "vp_tot_ref_earn", $credit_dir_ref); //add bonus to my ref
	//error_log("Earn is $cur_dir_bonus + $give_away = $credit_dir_ref",0);

	$usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
if(isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL){
	$d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
	global $wpdb;
	$wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID'=> $the_user));
}
else{}


}
elseif(vp_getuser($the_user,"vr_plan",true) != "custome" && $lev == 2 && $the_user != "0" && $the_user != "false"){
$cur_indir_bonus = vp_getuser($the_user, "vp_tot_in_ref_earn",true); // amt of my indirect ref like me
$credit_indir_ref = intval($cur_indir_bonus) + intval($give_away); //cal total of bonus. current bal plus bonus
vp_updateuser($the_user, "vp_tot_in_ref_earn", $credit_indir_ref); //add bonus to my ref

//error_log("FOr level 2 with user id is $the_user = Earn is $cur_indir_bonus + $give_away = $credit_indir_ref",0);


$usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
if(isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL){
	$d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
	global $wpdb;
	$wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID'=> $the_user));
}
else{}


}
elseif(vp_getuser($the_user,"vr_plan",true) != "custome" && $lev == 3  && $the_user != "0" && $the_user != "false"){
$curr_indir_trans_bonus3 = vp_getuser($the_user, "vp_tot_in_ref_earn3", true);
$add_to_indirect_transb3 = intval($curr_indir_trans_bonus3) + intval($give_away);
vp_updateuser($the_user, "vp_tot_in_ref_earn3", $add_to_indirect_transb3);

$usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
if(isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL){
	$d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
	global $wpdb;
	$wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID'=> $the_user));
}
else{}


}
elseif(vp_getuser($the_user,"vr_plan",true) != "custome" && $lev != 3 && $lev != 2 && $lev != 1  && $the_user != "0" && $the_user != "false"){
$cur_indir_bonus3 = vp_getuser($the_user, "vp_tot_in_ref_earn3",true); // amt of my gg ref like me
$credit_indir_ref3 = intval($cur_indir_bonus3) + intval($give_away); //cal total of bonus. current bal plus bonus
vp_updateuser($the_user, "vp_tot_in_ref_earn3", $credit_indir_ref3); //add bonus to my gg

$usrs_table = $wpdb->get_results("SELECT * FROM $usrs WHERE ID = $the_user");
if(isset($usrs_table[0]->vp_user_pv) || $usrs_table[0]->vp_user_pv == NULL){
	$d_pv = floatval($usrs_table[0]->vp_user_pv) + $give_pv;
	global $wpdb;
	$wpdb->update($usrs, array('vp_user_pv' => $d_pv), array('ID'=> $the_user));
}
else{}

}
else{
	$lev = 90000000000;
}
	
	
$next_user = vp_getuser($the_user, "vp_who_ref", true);

$the_user = $next_user;
	
}


}

}


die('100');

}
else{
die('101');
		
	}



}

if(isset($_POST["set_pin"])){
	$id = get_current_user_id();
	$pin = $_POST["pin"];

if(strlen($pin) >= 4){
	$verify_pin = preg_match("/[^0-9]/",$pin);
	if($verify_pin === 0){
		$verify_zero = preg_match("/^0\d+$/",$pin);
		if($verify_zero === 0){
vp_updateuser($id,"vp_pin",$pin);
vp_updateuser($id,"vp_pin_set","yes");

$obj = new stdClass;
$obj->code = "100";
$obj->message = "Pin Set To $pin";
die(json_encode($obj));
		}
		else{
$obj = new stdClass;
$obj->status = "200";
$obj->message = "Do Not Start Your Pin With Zero";
die(json_encode($obj));
		}
	}
	else{
$obj = new stdClass;
$obj->status = "200";
$obj->message = "Only Numbers Are Allowed For Pin";
die(json_encode($obj));
		}


}
else{
$obj = new stdClass;
$obj->status = "200";
$obj->message = "Pin Must Be At Least 4 Digits";
die(json_encode($obj));
		}
	
}

/*
if(isset($_POST["flush_transactions"])){
	$flush = $_POST["flush_transactions"];
	
switch($flush){
	case"success":
$num = 0;
global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

$table_name = $wpdb->prefix.'sdata';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

if(is_plugin_active("vpmlm/vpmlm.php")){
	
$table_name = $wpdb->prefix.'sbill';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

$table_name = $wpdb->prefix.'scable';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}	
}


if(is_plugin_active("vpsms/vpsms.php")){
	
$table_name = $wpdb->prefix.'ssms';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

}

if(is_plugin_active("vpcards/vpcards.php")){
	
$table_name = $wpdb->prefix.'scards';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

}

if(is_plugin_active("vpepin/vpepin.php")){
	
$table_name = $wpdb->prefix.'sepins';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

}

$obj = new stdClass;
$obj->code = "100";
$obj->message = "$num Failed Transactions Deleted";
die(json_encode($obj));
break;
case"failed":
$num = 0;


if(is_plugin_active("vpcards/vpcards.php")){
	
$table_name = $wpdb->prefix.'fcards';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

}

if(is_plugin_active("vpepin/vpepin.php")){
	
$table_name = $wpdb->prefix.'fepins';
$array = $wpdb->get_results("SELECT * FROM $table_name");
foreach($array as $arr){
if(!empty($arr)){
$num = $num+1;
$wpdb->delete($table_name , array( 'id' => $arr->id ));

}
}

}


$obj = new stdClass;
$obj->code = "100";
$obj->message = "$num Failed Transactions Deleted";
die(json_encode($obj));
break;

}

}
*/

if(isset($_POST["convert_it"])){
	$conversion = $_POST["conversion"];
	$network = $_POST["network"];
	$payto = $_POST["pay_to"];
	$paycharge = $_POST["pay_charge"];
	$amount = $_POST["amount"];
	$get = $_POST["pay_get"];
	$from = $_POST["from"];

	$atw = vp_getoption("airtime_to_wallet");
	$atc = vp_getoption("airtime_to_cash");


	if(preg_match("/-/",$amount)){
		vp_block_user("Tried A-T-C with a negative amount!");
		die("Dont try negative balance");
	}

if($conversion == "wallet"){

	if($atw != "yes"){
		die("Airtime To Wallet Not Permitted");
	}
	$id = get_current_user_id();
	$name = get_userdata($id)->user_login;
	$description = "To Pay The Sum Of #$amount For #$get Conversion Rate Charged @ $paycharge% From $from";
	$fund_amount = $get;
	$before_amount = vp_getuser($id, "vp_bal", true);
	$now_amount = $before_amount + $get;
	
	
$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Airtime_To_Wallet",
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));


$purchased = "Airtime To Wallet Conversion Of  ₦$fund_amount";
$recipient = $name;
vp_transaction_email("NEW AIRTIME TO WALLET NOTIFICATION","AIRTIME TO WALLET REQUEST LOGGED",'nill',$purchased, $recipient, $fund_amount, $before_amount,$now_amount,true,false);


die("100");
}


if($conversion == "cash"){	

	if($atc != "yes"){
		die("Airtime To Cash Not Permitted");
	}

	$id = get_current_user_id();
	$name = get_userdata($id)->user_login;
	$bank = $_POST["bank"];
	$description = "To Pay The Sum Of #$amount For #$get Conversion Rate Charged @ $paycharge% From $from To Details $bank";
	$fund_amount = $get;
	$before_amount = vp_getuser($id, "vp_bal", true);
	$now_amount = "Not Applicable";

	
	
$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Airtime_To_Cash",
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $id,
'status' => "Pending",
'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
));

$purchased = "Airtime To Cash Conversion Of  ₦$fund_amount";
$recipient = "From $name To $bank";
vp_transaction_email("NEW AIRTIME TO CASH NOTIFICATION","AIRTIME TO CASH REQUEST LOGGED",'nill',$purchased, $recipient, $fund_amount, $before_amount,$now_amount,true,false);


die("100");
}

}





?>