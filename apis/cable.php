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

vp_updateoption('cableresponse_id',$_REQUEST["cableresponse_id"]);

vp_updateoption("cablequerytext",$_REQUEST["cablequerytext"]);
vp_updateoption("cablequerymethod",$_REQUEST["cablequerymethod"]);
vp_updateoption("cableaddendpoint",$_REQUEST["cableaddendpoint"]);

vp_updateoption("cable_charge",$_REQUEST["cable_charge"]);



////////////////////////////////////////////////CABLE////////////////////////////////////
vp_updateoption("cablebaseurl",$_REQUEST["cablebaseurl"]);
vp_updateoption("cableendpoint",$_REQUEST["cableendpoint"]);
vp_updateoption("cablerequest",$_REQUEST["cablerequest"]);
vp_updateoption("cablerequesttext",$_REQUEST["cablerequesttext"]);
vp_updateoption("cablesuccesscode",$_REQUEST["cablesuccesscode"]);
vp_updateoption("cablesuccessvalue",$_REQUEST["cablesuccessvalue"]);
vp_updateoption("cablesuccessvalue2",$_REQUEST["cablesuccessvalue2"]);

vp_updateoption("crequest_id",$_REQUEST["crequest_id"]);

for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("cablehead".$cheaders,$_REQUEST["cablehead".$cheaders]);
vp_updateoption("cablevalue".$cheaders,$_REQUEST["cablevalue".$cheaders]);
}


vp_updateoption("cableaddpost",$_REQUEST["cableaddpost"]);

for($cpost=1; $cpost<=8; $cpost++){
vp_updateoption("cablepostdata".$cpost,isset($_REQUEST["cablepostdata".$cpost]) ? $_REQUEST["cablepostdata".$cpost] : "");
vp_updateoption("cablepostvalue".$cpost,$_REQUEST["cablepostvalue".$cpost]);
}


vp_updateoption("ccvariationattr",$_REQUEST["ccvariationattr"]);
vp_updateoption("ctypeattr",$_REQUEST["ctypeattr"]);
vp_updateoption("ciucattr",$_REQUEST["ciucattr"]);


for($j=0; $j<=3; $j++){
vp_updateoption("cablename".$j,$_REQUEST["cablename".$j]);
vp_updateoption("cableid".$j,$_REQUEST["cableid".$j]);
}
for($i=0; $i<=35; $i++){
vp_updateoption("ccable".$i,$_REQUEST["ccable".$i]);
vp_updateoption("ccablen".$i,$_REQUEST["ccablen".$i]);
vp_updateoption("ccablep".$i,$_REQUEST["ccablep".$i]);
}

//////////////////////////////////////////////////////////////////////////////////////////


for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
    vp_updateoption("cableaddheaders".$cableaddheaders,$_REQUEST["cableaddheaders".$cableaddheaders]);
    vp_updateoption("cableaddvalue".$cableaddheaders,$_REQUEST["cableaddvalue".$cableaddheaders]);
    }



vp_updateoption("cable_response_format",$_REQUEST["cableresponse"]);
vp_updateoption("cable_response_format_text",$_REQUEST["cableresponsetext"]);

vp_updateoption("cable_head",$_REQUEST["cablehead"]);


die("100");

}
?>