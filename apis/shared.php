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
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

$vpdebug = vp_getoption("vpdebug");

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



if(isset($_POST)){


vp_updateoption('shareresponse_id',$_REQUEST["shareresponse_id"]);
vp_updateoption("sharequerytext",$_REQUEST["sharequerytext"]);
vp_updateoption("sharequerymethod",$_REQUEST["sharequerymethod"]);
vp_updateoption("shareaddendpoint",$_REQUEST["shareaddendpoint"]);
for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
    vp_updateoption("shareaddheaders".$shareaddheaders,$_REQUEST["shareaddheaders".$shareaddheaders]);
    vp_updateoption("shareaddvalue".$shareaddheaders,$_REQUEST["shareaddvalue".$shareaddheaders]);
}

vp_updateoption("airtime_head2",$_REQUEST["airtimehead2"]);
vp_updateoption("airtime2_response_format",$_REQUEST["airtimeresponse2"]);
vp_updateoption("airtime2_response_format_text",$_REQUEST["airtimeresponsetext2"]);

///////////////////////////////SHARED AIRTIME UPDATE////////////////////////////////
vp_updateoption("sairtimebaseurl",$_REQUEST["sairtimebaseurl"]);
vp_updateoption("sairtimeendpoint",$_REQUEST["sairtimeendpoint"]);
vp_updateoption("sairtimerequest",$_REQUEST["sairtimerequest"]);
vp_updateoption("sairtimerequesttext",$_REQUEST["sairtimerequesttext"]);
vp_updateoption("sairtimesuccesscode",$_REQUEST["sairtimesuccesscode"]);
vp_updateoption("sairtimesuccessvalue",$_REQUEST["sairtimesuccessvalue"]);
vp_updateoption("sairtimesuccessvalue2",$_REQUEST["sairtimesuccessvalue2"]);

vp_updateoption("sarequest_id",$_REQUEST["sarequest_id"]);

for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("sairtimehead".$cheaders,$_REQUEST["sairtimehead".$cheaders]);
vp_updateoption("sairtimevalue".$cheaders,$_REQUEST["sairtimevalue".$cheaders]);
}

vp_updateoption("sairtimeaddpost",$_REQUEST["sairtimeaddpost"]);


for($cpost=1; $cpost<=8; $cpost++){
vp_updateoption("sairtimepostdata".$cpost,isset($_REQUEST["sairtimepostdata".$cpost]) ? $_REQUEST["sairtimepostdata".$cpost] : "");
vp_updateoption("sairtimepostvalue".$cpost,$_REQUEST["sairtimepostvalue".$cpost]);
}

vp_updateoption("sairtimeamountattribute",$_REQUEST["sairtimeamountattribute"]);
vp_updateoption("sairtimephoneattribute",$_REQUEST["sairtimephoneattribute"]);
vp_updateoption("sairtimenetworkattribute",$_REQUEST["sairtimenetworkattribute"]);
vp_updateoption("sairtimemtn",$_REQUEST["sairtimemtn"]);
vp_updateoption("sairtimeglo",$_REQUEST["sairtimeglo"]);
vp_updateoption("sairtime9mobile",$_REQUEST["sairtime9mobile"]);
vp_updateoption("sairtimeairtel",$_REQUEST["sairtimeairtel"]);

die("100");

}
?>