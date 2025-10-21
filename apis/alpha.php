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


vp_updateoption('alpharesponse_id',$_REQUEST["alpharesponse_id"]);
vp_updateoption("alphaquerytext",$_REQUEST["alphaquerytext"]);
vp_updateoption("alphaquerymethod",$_REQUEST["alphaquerymethod"]);
vp_updateoption("alphaaddendpoint",$_REQUEST["alphaaddendpoint"]);


for($alphaaddheaders=1; $alphaaddheaders<=4; $alphaaddheaders++){
    vp_updateoption("alphaaddheaders".$alphaaddheaders,$_REQUEST["alphaaddheaders".$alphaaddheaders]);
    vp_updateoption("alphaaddvalue".$alphaaddheaders,$_REQUEST["alphaaddvalue".$alphaaddheaders]);
    }

    ///////////////////////DATATYPE/////////////////////////////////
    vp_updateoption('alpha_datatype',$_REQUEST["alpha_datatype"]); 
    vp_updateoption('alpha_datatype',$_REQUEST["alpha_datatype"]);

    ///////////////////////////////VTU DATA UPDATE////////////////////////////////

vp_updateoption("alphabaseurl",$_REQUEST["alphabaseurl"]);
vp_updateoption("alphaendpoint",$_REQUEST["alphaendpoint"]);
vp_updateoption("alpharequest",$_REQUEST["alpharequest"]);
vp_updateoption("alpharequesttext",$_REQUEST["alpharequesttext"]);
vp_updateoption("alphasuccesscode",$_REQUEST["alphasuccesscode"]);
vp_updateoption("alphasuccessvalue",$_REQUEST["alphasuccessvalue"]);
vp_updateoption("alphasuccessvalue2",$_REQUEST["alphasuccessvalue2"]);

vp_updateoption("alpharequest_id",$_REQUEST["alpharequest_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("alphahead".$cheaders,$_REQUEST["alphahead".$cheaders]);
vp_updateoption("alphavalue".$cheaders,$_REQUEST["alphavalue".$cheaders]);
}


vp_updateoption("alphaaddpost",$_REQUEST["alphaaddpost"]);

for($cpost=1; $cpost<=8; $cpost++){
vp_updateoption("alphapostdata".$cpost,isset($_REQUEST["alphapostdata".$cpost]) ? $_REQUEST["alphapostdata".$cpost] : "");
vp_updateoption("alphapostvalue".$cpost,$_REQUEST["alphapostvalue".$cpost]);
}

vp_updateoption("alphaamountattribute",$_REQUEST["alphaamountattribute"]);
vp_updateoption("alphavariationattr",$_REQUEST["alphavariationattr"]);
vp_updateoption("alphaphoneattribute",$_REQUEST["alphaphoneattribute"]);
vp_updateoption("alphanetworkattribute",$_REQUEST["alphanetworkattribute"]);

for($i=0; $i<=15; $i++){
vp_updateoption("calphadata".$i,$_REQUEST["calphadata".$i]);
vp_updateoption("calphadatan".$i,$_REQUEST["calphadatan".$i]);
vp_updateoption("calphadatap".$i,$_REQUEST["calphadatap".$i]);

}





vp_updateoption("alphaidattr",$_REQUEST["alphaidattr"]);
vp_updateoption("alpha_extra1",$_REQUEST["alpha_extra1"]);
/////////////////////////////////////////////////////////////////////////////


vp_updateoption("alpha_head",$_REQUEST["alphahead"]);
vp_updateoption("alpha1_response_format",$_REQUEST["alpharesponse"]);
vp_updateoption("alpha1_response_format_text",$_REQUEST["alpharesponsetext"]);




die("100");

}
?>