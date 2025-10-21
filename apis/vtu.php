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



if(isset($_POST)){


vp_updateoption("airtime1_response_format",$_REQUEST["airtimeresponse"]);
vp_updateoption("airtime1_response_format_text",$_REQUEST["airtimeresponsetext"]);
vp_updateoption("vtuquerytext",$_REQUEST["vtuquerytext"]);
vp_updateoption("vtuquerymethod",$_REQUEST["vtuquerymethod"]);
vp_updateoption("vtuaddendpoint",$_REQUEST["vtuaddendpoint"]);

for($vtuaddheaders=1; $vtuaddheaders<=4; $vtuaddheaders++){
    vp_updateoption("vtuaddheaders".$vtuaddheaders,$_REQUEST["vtuaddheaders".$vtuaddheaders]);
    vp_updateoption("vtuaddvalue".$vtuaddheaders,$_REQUEST["vtuaddvalue".$vtuaddheaders]);
    }


vp_updateoption("airtimebaseurl",$_REQUEST["airtimebaseurl"]);
vp_updateoption("airtimeendpoint",$_REQUEST["airtimeendpoint"]);
vp_updateoption("airtimerequest",$_REQUEST["airtimerequest"]);
vp_updateoption("airtimerequesttext",$_REQUEST["airtimerequesttext"]);
vp_updateoption("airtimesuccesscode",$_REQUEST["airtimesuccesscode"]);
vp_updateoption("airtimesuccessvalue",$_REQUEST["airtimesuccessvalue"]);
vp_updateoption("airtimesuccessvalue2",$_REQUEST["airtimesuccessvalue2"]);

vp_updateoption('vturesponse_id',$_REQUEST["vturesponse_id"]);

vp_updateoption("arequest_id",$_REQUEST["arequest_id"]);

for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("airtimehead".$cheaders,$_REQUEST["airtimehead".$cheaders]);
vp_updateoption("airtimevalue".$cheaders,$_REQUEST["airtimevalue".$cheaders]);
}

vp_updateoption("airtimeaddpost",$_REQUEST["airtimeaddpost"]);
vp_updateoption("airtime_head",$_REQUEST["airtimehead"]);

for($cpost=1; $cpost<=8; $cpost++){
vp_updateoption("airtimepostdata".$cpost,isset($_REQUEST["airtimepostdata".$cpost]) ? $_REQUEST["airtimepostdata".$cpost] : "");
vp_updateoption("airtimepostvalue".$cpost,$_REQUEST["airtimepostvalue".$cpost]);
}

vp_updateoption("airtimeamountattribute",$_REQUEST["airtimeamountattribute"]);
vp_updateoption("airtimephoneattribute",$_REQUEST["airtimephoneattribute"]);
vp_updateoption("airtimenetworkattribute",$_REQUEST["airtimenetworkattribute"]);
vp_updateoption("airtimemtn",$_REQUEST["airtimemtn"]);
vp_updateoption("airtimeglo",$_REQUEST["airtimeglo"]);
vp_updateoption("airtime9mobile",$_REQUEST["airtime9mobile"]);
vp_updateoption("airtimeairtel",$_REQUEST["airtimeairtel"]);




die("100");

}
?>