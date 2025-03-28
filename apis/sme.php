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


vp_updateoption('smeresponse_id',$_REQUEST["smeresponse_id"]);
vp_updateoption("smequerytext",$_REQUEST["smequerytext"]);
vp_updateoption("smequerymethod",$_REQUEST["smequerymethod"]);
vp_updateoption("smeaddendpoint",$_REQUEST["smeaddendpoint"]);
vp_updateoption("sme_visible_networks",$_REQUEST["sme_visible_networks"]);

vp_updateoption("sme_mtn_balance",$_REQUEST["sme_mtn_balance"]);
vp_updateoption("sme_airtel_balance",$_REQUEST["sme_airtel_balance"]);
vp_updateoption("sme_glo_balance",$_REQUEST["sme_glo_balance"]);
vp_updateoption("sme_9mobile_balance",$_REQUEST["sme_9mobile_balance"]);

for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
    vp_updateoption("smeaddheaders".$smeaddheaders,$_REQUEST["smeaddheaders".$smeaddheaders]);
    vp_updateoption("smeaddvalue".$smeaddheaders,$_REQUEST["smeaddvalue".$smeaddheaders]);
    }

    ///////////////////////DATATYPE/////////////////////////////////
    vp_updateoption('sme_datatype',$_REQUEST["sme_datatype"]);  
    vp_updateoption('mtn_sme_datatype',$_REQUEST["mtn_sme_datatype"]); 
    vp_updateoption('glo_sme_datatype',$_REQUEST["glo_sme_datatype"]); 
    vp_updateoption('airtel_sme_datatype',$_REQUEST["airtel_sme_datatype"]);
    vp_updateoption('9mobile_sme_datatype',$_REQUEST["9mobile_sme_datatype"]);
     

    ///////////////////////////////VTU DATA UPDATE////////////////////////////////

vp_updateoption("databaseurl",$_REQUEST["databaseurl"]);
vp_updateoption("dataendpoint",$_REQUEST["dataendpoint"]);
vp_updateoption("datarequest",$_REQUEST["datarequest"]);
vp_updateoption("datarequesttext",$_REQUEST["datarequesttext"]);
vp_updateoption("datasuccesscode",$_REQUEST["datasuccesscode"]);
vp_updateoption("datasuccessvalue",$_REQUEST["datasuccessvalue"]);
vp_updateoption("datasuccessvalue2",$_REQUEST["datasuccessvalue2"]);

vp_updateoption("request_id",$_REQUEST["request_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("datahead".$cheaders,$_REQUEST["datahead".$cheaders]);
vp_updateoption("datavalue".$cheaders,$_REQUEST["datavalue".$cheaders]);
}


vp_updateoption("dataaddpost",$_REQUEST["dataaddpost"]);

for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("datapostdata".$cpost,$_REQUEST["datapostdata".$cpost]);
vp_updateoption("datapostvalue".$cpost,$_REQUEST["datapostvalue".$cpost]);
}

vp_updateoption("dataamountattribute",$_REQUEST["dataamountattribute"]);
vp_updateoption("cvariationattr",$_REQUEST["cvariationattr"]);
vp_updateoption("dataphoneattribute",$_REQUEST["dataphoneattribute"]);
vp_updateoption("datanetworkattribute",$_REQUEST["datanetworkattribute"]);

for($i=0; $i<=20; $i++){
vp_updateoption("cdata".$i,$_REQUEST["cdata".$i]);
vp_updateoption("cdatan".$i,$_REQUEST["cdatan".$i]);
vp_updateoption("cdatap".$i,$_REQUEST["cdatap".$i]);

}

for($i=0; $i<=20; $i++){
vp_updateoption("acdata".$i,$_REQUEST["acdata".$i]);
vp_updateoption("acdatan".$i,$_REQUEST["acdatan".$i]);
vp_updateoption("acdatap".$i,$_REQUEST["acdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("9cdata".$i,$_REQUEST["9cdata".$i]);
vp_updateoption("9cdatan".$i,$_REQUEST["9cdatan".$i]);
vp_updateoption("9cdatap".$i,$_REQUEST["9cdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("gcdata".$i,$_REQUEST["gcdata".$i]);
vp_updateoption("gcdatan".$i,$_REQUEST["gcdatan".$i]);
vp_updateoption("gcdatap".$i,$_REQUEST["gcdatap".$i]);
}



vp_updateoption("datamtn",$_REQUEST["datamtn"]);
vp_updateoption("dataglo",$_REQUEST["dataglo"]);
vp_updateoption("data9mobile",$_REQUEST["data9mobile"]);
vp_updateoption("dataairtel",$_REQUEST["dataairtel"]);
/////////////////////////////////////////////////////////////////////////////


vp_updateoption("data_head",$_REQUEST["datahead"]);
vp_updateoption("data1_response_format",$_REQUEST["dataresponse"]);
vp_updateoption("data1_response_format_text",$_REQUEST["dataresponsetext"]);




die("100");

}
?>