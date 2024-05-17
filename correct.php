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
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


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






if(isset($_POST["correct"])){
	
if(isset($_POST["id"])){
$id = $_POST["id"];
}
else{
$id = "";	
}
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
if(isset($_POST["username"])){
$name = $_POST["username"];
}
else{
$name = "";	
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

if($id != "230" ){
	
$user_data = "";//wp_update_user( array( 'ID' => $id, 'user_email' => $email, 'user_login' => $name ) );

if($user_data == ""){
echo'{"status":"200","message":"hi"}';
}
else{
vp_updateuser($id,"vp_phone",$phone);
if(!empty($password)){
wp_set_password($password,$id);
echo'{"status":"100"}';
}
}
}
else{
echo'{"status":"200","message":"No Field Should Be Empty"}';
}


	
}

?>