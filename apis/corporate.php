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
        // die("INVALID SPRAYCODE");
    }
}




if(isset($_POST)){

vp_updateoption('corporateresponse_id',$_REQUEST["corporateresponse_id"]);
vp_updateoption("corporatequerytext",$_REQUEST["corporatequerytext"]);
vp_updateoption("corporatequerymethod",$_REQUEST["corporatequerymethod"]);
vp_updateoption("corporateaddendpoint",$_REQUEST["corporateaddendpoint"]);

vp_updateoption("corporate_visible_networks",$_REQUEST["corporate_visible_networks"]);


    ///////////////////////DATATYPE/////////////////////////////////
    vp_updateoption('corporate_datatype',$_REQUEST["corporate_datatype"]);  
    vp_updateoption('mtn_corporate_datatype',$_REQUEST["mtn_corporate_datatype"]); 
    vp_updateoption('glo_corporate_datatype',$_REQUEST["glo_corporate_datatype"]); 
    vp_updateoption('airtel_corporate_datatype',$_REQUEST["airtel_corporate_datatype"]);
    vp_updateoption('9mobile_corporate_datatype',$_REQUEST["9mobile_corporate_datatype"]);



///////////////////////////////CORPORATE DATA UPDATE////////////////////////////////

vp_updateoption("r2databaseurl",$_REQUEST["r2databaseurl"]);
vp_updateoption("r2dataendpoint",$_REQUEST["r2dataendpoint"]);
vp_updateoption("r2datarequest",$_REQUEST["r2datarequest"]);
vp_updateoption("r2datarequesttext",$_REQUEST["r2datarequesttext"]);
vp_updateoption("r2datasuccesscode",$_REQUEST["r2datasuccesscode"]);
vp_updateoption("r2datasuccessvalue",$_REQUEST["r2datasuccessvalue"]);
vp_updateoption("r2datasuccessvalue2",$_REQUEST["r2datasuccessvalue2"]);

vp_updateoption("r2request_id",$_REQUEST["r2request_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("r2datahead".$cheaders,$_REQUEST["r2datahead".$cheaders]);
vp_updateoption("r2datavalue".$cheaders,$_REQUEST["r2datavalue".$cheaders]);
}


vp_updateoption("r2dataaddpost",$_REQUEST["r2dataaddpost"]);

for($cpost=1; $cpost<=8; $cpost++){
vp_updateoption("r2datapostdata".$cpost,isset($_REQUEST["r2datapostdata".$cpost]) ? $_REQUEST["r2datapostdata".$cpost] : "");
vp_updateoption("r2datapostvalue".$cpost,$_REQUEST["r2datapostvalue".$cpost]);
}

vp_updateoption("r2dataamountattribute",$_REQUEST["r2dataamountattribute"]);
vp_updateoption("r2cvariationattr",$_REQUEST["r2cvariationattr"]);
vp_updateoption("r2dataphoneattribute",$_REQUEST["r2dataphoneattribute"]);
vp_updateoption("r2datanetworkattribute",$_REQUEST["r2datanetworkattribute"]);

for($i=0; $i<=20; $i++){
vp_updateoption("r2cdata".$i,$_REQUEST["r2cdata".$i]);
vp_updateoption("r2cdatan".$i,$_REQUEST["r2cdatan".$i]);
vp_updateoption("r2cdatap".$i,$_REQUEST["r2cdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("r2acdata".$i,$_REQUEST["r2acdata".$i]);
vp_updateoption("r2acdatan".$i,$_REQUEST["r2acdatan".$i]);
vp_updateoption("r2acdatap".$i,$_REQUEST["r2acdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("r29cdata".$i,$_REQUEST["r29cdata".$i]);
vp_updateoption("r29cdatan".$i,$_REQUEST["r29cdatan".$i]);
vp_updateoption("r29cdatap".$i,$_REQUEST["r29cdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("r2gcdata".$i,$_REQUEST["r2gcdata".$i]);
vp_updateoption("r2gcdatan".$i,$_REQUEST["r2gcdatan".$i]);
vp_updateoption("r2gcdatap".$i,$_REQUEST["r2gcdatap".$i]);
}

vp_updateoption("r2datamtn",$_REQUEST["r2datamtn"]);
vp_updateoption("r2dataglo",$_REQUEST["r2dataglo"]);
vp_updateoption("r2data9mobile",$_REQUEST["r2data9mobile"]);
vp_updateoption("r2dataairtel",$_REQUEST["r2dataairtel"]);

/////////////////////////////////////////////////////////////



vp_updateoption("corporate_mtn_balance",$_REQUEST["corporate_mtn_balance"]);
vp_updateoption("corporate_airtel_balance",$_REQUEST["corporate_airtel_balance"]);
vp_updateoption("corporate_glo_balance",$_REQUEST["corporate_glo_balance"]);
vp_updateoption("corporate_9mobile_balance",$_REQUEST["corporate_9mobile_balance"]);

for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
    vp_updateoption("corporateaddheaders".$corporateaddheaders,$_REQUEST["corporateaddheaders".$corporateaddheaders]);
    vp_updateoption("corporateaddvalue".$corporateaddheaders,$_REQUEST["corporateaddvalue".$corporateaddheaders]);
}

vp_updateoption("data_head3",$_REQUEST["datahead3"]);

vp_updateoption("data3_response_format",$_REQUEST["dataresponse3"]);

vp_updateoption("data3_response_format_text",$_REQUEST["dataresponsetext3"]);


die("100");

}
?>