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
include_once(ABSPATH ."wp-load.php");
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


if(isset($_POST["firstreset"])){

$name = $_POST["username"];
$email = sanitize_email($_POST["email"]);
$pin = intval($_POST["pin"]);
$password = $_POST["password"];

if (!is_email($email)) {
    // The email is valid, proceed with further processing
	die('{"status":"200","message":"Use a valid email"}');

} elseif(!is_numeric($pin)){
	die('{"status":"200","message":"Use a valid numeric pin"}');

}


$status = get_user_by('login',$name);

if($status === false){
	die('{"status":"200","message":"USERNAME NOT FOUND!!!!"}');
}else{
	//die('{"status":"200","message":"Please contact the admin with your username @ '.$name.' to change ur password"}');
}

$adid = get_user_by('login',$name)->ID;

$id = $status->ID;

$original_pin = vp_getuser($id,"vp_pin",true);
$original_email = $status->user_email;

if($original_pin == $pin && $original_email == $email && strtolower($status->user_login) == strtolower($_POST["username"]) && strtolower($_POST["username"]) != "admin" && $adid != "1"){
		
		
		if(strtolower($status->user_email) == strtolower($_POST["email"])){
			
			$mypin = vp_getuser($id,"vp_pin",true);
			$pin = $_POST["pin"];
			
			if($pin == $mypin){
			
				$verify_pass = preg_match("/[^a-zA-Z0-9\.]/",$password);
				if($verify_pass === 0){
			wp_set_password($password,$id);
			
			die('{"status":"100","message":"Password Changed Successfully"}');
				}
				else{
				die('{"status":"200","message":"Password Can Only Contain Alpha-Numeric Figure with optional [dot\'.\']"}');	
				}
			}
			else{
				die('{"status":"200","message":"PIN NOT CORRECT"}');
			}
		
		}
		else{
	
	die('{"status":"200","message":"EMAIL NOT CORRECT"}');
	
		}
	
	
		
	}
	else{
	
	die('{"status":"200","message":"DETAILS NOT CORRECT"}');
	
	}




}
elseif(isset($_POST["correct"])){
	
if(!is_user_logged_in()){
	die('{"status":"200","message":"Please Login"}');
}

$id = get_current_user_id();

$user_data = get_user_by("ID",$id);

$name = $user_data->user_login;

if(isset($_POST["phone"])){
$phone = $_POST["phone"];
}
else{
$phone = "";
}
if(isset($_POST["password"])){
$password = $_POST["password"];
}
else{
$password = "";	
}

if(isset($_POST["email"])){
$email = $_POST["email"];
}
else{
$email = "";
}

if(isset($_POST["pin"])){
$pin = $_POST["pin"];
}
else{
$pin = "";
}

if(!empty($id) && !empty($name) && !empty($email) && !empty($phone) && !empty($pin)){
	if(!is_numeric($pin)){
		die('{"status":"200","message":"PIN Must Be Numbers"}');
	}
	if(!is_numeric($phone)){
		die('{"status":"200","message":"PHONE Must Be Numbers"}');
	}
	
$current_email = $user_data->user_email;
if(email_exists($email) && $current_email != $email){
	die('{"status":"200","message":"Email Already Exist"}');
}
elseif($current_email == $email){
//do nothing
}
else{

global $wpdb;
$table_name = $wpdb->base_prefix.'users';
$user_data = $wpdb->update( $table_name, array('user_email'=>$email), array('id'=>$id));

}

vp_updateuser($id,"vp_phone",$phone);
vp_updateuser($id,"vp_pin",$pin);
if(!empty($password)){
wp_set_password($password,$id);
}
die('{"status":"100"}');

}
else{
die('{"status":"200","message":"No Field Should Be Empty"}');
}


	
}
else{
	die('{"status":"200","message":"POST DATA ONLY"}');
}


?>