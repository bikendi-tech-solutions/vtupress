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
        // die("INVALID SPRAYCODE");
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




if(isset($_REQUEST["secure"])){
$http_redirect = $_REQUEST["http"];
$validaterecipient = $_REQUEST["validate-recipient"];
$global_security = $_REQUEST["global"];
$security_mode = $_REQUEST["security"];
$ips = $_REQUEST["httips"];
$users = $_REQUEST["users"];
$ban_email = $_REQUEST["email"];
$access_website = $_REQUEST["access-website"];
$access_user = $_REQUEST["user-dashboard"];
//$access_country = $_REQUEST["other-country"];
$tself = $_REQUEST["tself"];
$tothers = $_REQUEST["tothers"];

/*
echo $http_redirect."http <br>";
echo $global_security."global <br>";
echo $security_mode."sec mide <br>";
echo $ips."ips <br>";
echo $users."users <br>";
echo $access_website."aw <br>";
echo $access_user."au <br>";
echo $access_country."oc <br>";
*/
$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$read = fopen(vp_getoption("siteurl"), "rb", false, $stream);
$cont = stream_context_get_params($read);
$var = ($cont["options"]["ssl"]["peer_certificate"]);
if(is_null($var)){
	
}
else{
    if($http_redirect == "true" && vp_getoption("siteurl") == "http://".$_SERVER["SERVER_NAME"] && vp_getoption("siteurl") == "http://".$_SERVER['HTTP_HOST']){
        vp_updateoption("siteurl", "https://".$_SERVER['HTTP_HOST']);
        }
}

if($validaterecipient == "true"){
    vp_updateoption("validate-recipient", "true");  
}else{
    vp_updateoption("validate-recipient", "false");  

}

vp_updateoption("http_redirect", $http_redirect);
vp_updateoption("global_security", $global_security);
vp_updateoption("secur_mod", $security_mode);
vp_updateoption("vp_ips_ban", $ips);
vp_updateoption("vp_users_ban", $users);
vp_updateoption("access_website", $access_website);
vp_updateoption("access_user_dashboard", $access_user);
vp_updateoption("vp_users_email", $ban_email);
//vp_updateoption("access_country", $access_country);
vp_updateoption("tself", $tself);
vp_updateoption("tothers", $tothers);

/*
echo vp_getoption("http_redirect")."http <br>";
echo vp_getoption("global_security")."global <br>";
echo vp_getoption("secur_mod")."security <br>";
echo vp_getoption("vp_ips_ban")."ips <br>";
echo vp_getoption("vp_users_ban")."users <br>";
echo vp_getoption("access_website")."aw <br>";
echo vp_getoption("access_user_dashboard")."au <br>";
echo vp_getoption("access_country")."oc <br>";
*/


die("100");
}
?>