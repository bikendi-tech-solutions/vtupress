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
     //    die("INVALID SPRAYCODE");
    }
}elseif(strtolower($spray_code) == "false"){
    if($real_code == "false"){
        $cur_id = get_current_user_id();
        $update_code = uniqid("vtu_$cur_id");
        vp_updateoption("spraycode",$update_code);
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
     //    die("INVALID SPRAYCODE");
    }
}



if(isset($_POST)){


vp_updateoption('awufresponse_id',$_REQUEST["awufresponse_id"]);
vp_updateoption("awufquerytext",$_REQUEST["awufquerytext"]);
vp_updateoption("awufquerymethod",$_REQUEST["awufquerymethod"]);
vp_updateoption("awufaddendpoint",$_REQUEST["awufaddendpoint"]);


///////////////////////////////AWUF AIRTIME UPDATE////////////////////////////////
vp_updateoption("wairtimebaseurl",$_REQUEST["wairtimebaseurl"]);
vp_updateoption("wairtimeendpoint",$_REQUEST["wairtimeendpoint"]);
vp_updateoption("wairtimerequest",$_REQUEST["wairtimerequest"]);
vp_updateoption("wairtimerequesttext",$_REQUEST["wairtimerequesttext"]);
vp_updateoption("wairtimesuccesscode",$_REQUEST["wairtimesuccesscode"]);
vp_updateoption("wairtimesuccessvalue",$_REQUEST["wairtimesuccessvalue"]);
vp_updateoption("wairtimesuccessvalue2",$_REQUEST["wairtimesuccessvalue2"]);

vp_updateoption("warequest_id",$_REQUEST["warequest_id"]);

for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("wairtimehead".$cheaders,$_REQUEST["wairtimehead".$cheaders]);
vp_updateoption("wairtimevalue".$cheaders,$_REQUEST["wairtimevalue".$cheaders]);
}

vp_updateoption("wairtimeaddpost",$_REQUEST["wairtimeaddpost"]);


for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("wairtimepostdata".$cpost,$_REQUEST["wairtimepostdata".$cpost]);
vp_updateoption("wairtimepostvalue".$cpost,$_REQUEST["wairtimepostvalue".$cpost]);
}

vp_updateoption("wairtimeamountattribute",$_REQUEST["wairtimeamountattribute"]);
vp_updateoption("wairtimephoneattribute",$_REQUEST["wairtimephoneattribute"]);
vp_updateoption("wairtimenetworkattribute",$_REQUEST["wairtimenetworkattribute"]);
vp_updateoption("wairtimemtn",$_REQUEST["wairtimemtn"]);
vp_updateoption("wairtimeglo",$_REQUEST["wairtimeglo"]);
vp_updateoption("wairtime9mobile",$_REQUEST["wairtime9mobile"]);
vp_updateoption("wairtimeairtel",$_REQUEST["wairtimeairtel"]);

/////////////////////////////////////////////////////////////////////////////

for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
    vp_updateoption("awufaddheaders".$awufaddheaders,$_REQUEST["awufaddheaders".$awufaddheaders]);
    vp_updateoption("awufaddvalue".$awufaddheaders,$_REQUEST["awufaddvalue".$awufaddheaders]);
}

vp_updateoption("airtime_head3",$_REQUEST["airtimehead3"]);
vp_updateoption("airtime3_response_format",$_REQUEST["airtimeresponse3"]);
vp_updateoption("airtime3_response_format_text",$_REQUEST["airtimeresponsetext3"]);


die("100");

}
?>