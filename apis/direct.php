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
      //   die("INVALID SPRAYCODE");
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

vp_updateoption('directresponse_id',$_REQUEST["directresponse_id"]);
vp_updateoption("directquerytext",$_REQUEST["directquerytext"]);
vp_updateoption("directquerymethod",$_REQUEST["directquerymethod"]);
vp_updateoption("directaddendpoint",$_REQUEST["directaddendpoint"]);
vp_updateoption("direct_visible_networks",$_REQUEST["direct_visible_networks"]);



    ///////////////////////DATATYPE/////////////////////////////////
    vp_updateoption('direct_datatype',$_REQUEST["direct_datatype"]);  
    vp_updateoption('mtn_direct_datatype',$_REQUEST["mtn_direct_datatype"]); 
    vp_updateoption('glo_direct_datatype',$_REQUEST["glo_direct_datatype"]); 
    vp_updateoption('airtel_direct_datatype',$_REQUEST["airtel_direct_datatype"]);
    vp_updateoption('9mobile_direct_datatype',$_REQUEST["9mobile_direct_datatype"]);


///////////////////////////////DIRECT DATA UPDATE////////////////////////////////

vp_updateoption("rdatabaseurl",$_REQUEST["rdatabaseurl"]);
vp_updateoption("rdataendpoint",$_REQUEST["rdataendpoint"]);
vp_updateoption("rdatarequest",$_REQUEST["rdatarequest"]);
vp_updateoption("rdatarequesttext",$_REQUEST["rdatarequesttext"]);
vp_updateoption("rdatasuccesscode",$_REQUEST["rdatasuccesscode"]);
vp_updateoption("rdatasuccessvalue",$_REQUEST["rdatasuccessvalue"]);
vp_updateoption("rdatasuccessvalue2",$_REQUEST["rdatasuccessvalue2"]);

vp_updateoption("rrequest_id",$_REQUEST["rrequest_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("rdatahead".$cheaders,$_REQUEST["rdatahead".$cheaders]);
vp_updateoption("rdatavalue".$cheaders,$_REQUEST["rdatavalue".$cheaders]);
}


vp_updateoption("rdataaddpost",$_REQUEST["rdataaddpost"]);

for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("rdatapostdata".$cpost,$_REQUEST["rdatapostdata".$cpost]);
vp_updateoption("rdatapostvalue".$cpost,$_REQUEST["rdatapostvalue".$cpost]);
}

vp_updateoption("rdataamountattribute",$_REQUEST["rdataamountattribute"]);
vp_updateoption("rcvariationattr",$_REQUEST["rcvariationattr"]);
vp_updateoption("rdataphoneattribute",$_REQUEST["rdataphoneattribute"]);
vp_updateoption("rdatanetworkattribute",$_REQUEST["rdatanetworkattribute"]);

for($i=0; $i<=20; $i++){
vp_updateoption("rcdata".$i,$_REQUEST["rcdata".$i]);
vp_updateoption("rcdatan".$i,$_REQUEST["rcdatan".$i]);
vp_updateoption("rcdatap".$i,$_REQUEST["rcdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("racdata".$i,$_REQUEST["racdata".$i]);
vp_updateoption("racdatan".$i,$_REQUEST["racdatan".$i]);
vp_updateoption("racdatap".$i,$_REQUEST["racdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("r9cdata".$i,$_REQUEST["r9cdata".$i]);
vp_updateoption("r9cdatan".$i,$_REQUEST["r9cdatan".$i]);
vp_updateoption("r9cdatap".$i,$_REQUEST["r9cdatap".$i]);
}

for($i=0; $i<=20; $i++){
vp_updateoption("rgcdata".$i,$_REQUEST["rgcdata".$i]);
vp_updateoption("rgcdatan".$i,$_REQUEST["rgcdatan".$i]);
vp_updateoption("rgcdatap".$i,$_REQUEST["rgcdatap".$i]);
}


vp_updateoption("rdatamtn",$_REQUEST["rdatamtn"]);
vp_updateoption("rdataglo",$_REQUEST["rdataglo"]);
vp_updateoption("rdata9mobile",$_REQUEST["rdata9mobile"]);
vp_updateoption("rdataairtel",$_REQUEST["rdataairtel"]);

/////////////////////////////////////////////////////////////////////////////


vp_updateoption("direct_mtn_balance",$_REQUEST["direct_mtn_balance"]);
vp_updateoption("direct_airtel_balance",$_REQUEST["direct_airtel_balance"]);
vp_updateoption("direct_glo_balance",$_REQUEST["direct_glo_balance"]);
vp_updateoption("direct_9mobile_balance",$_REQUEST["direct_9mobile_balance"]);


for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
    vp_updateoption("directaddheaders".$directaddheaders,$_REQUEST["directaddheaders".$directaddheaders]);
    vp_updateoption("directaddvalue".$directaddheaders,$_REQUEST["directaddvalue".$directaddheaders]);
    }

vp_updateoption("data_head2",$_REQUEST["datahead2"]);
vp_updateoption("data2_response_format",$_REQUEST["dataresponse2"]);
vp_updateoption("data2_response_format_text",$_REQUEST["dataresponsetext2"]);


die("100");

}
?>