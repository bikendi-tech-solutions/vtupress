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
       //  die("INVALID SPRAYCODE");
    }
}


if(isset($_POST)){


vp_updateoption('smileresponse_id',$_REQUEST["smileresponse_id"]);
vp_updateoption("smilequerytext",$_REQUEST["smilequerytext"]);
vp_updateoption("smilequerymethod",$_REQUEST["smilequerymethod"]);
vp_updateoption("smileaddendpoint",$_REQUEST["smileaddendpoint"]);


for($smileaddheaders=1; $smileaddheaders<=4; $smileaddheaders++){
    vp_updateoption("smileaddheaders".$smileaddheaders,$_REQUEST["smileaddheaders".$smileaddheaders]);
    vp_updateoption("smileaddvalue".$smileaddheaders,$_REQUEST["smileaddvalue".$smileaddheaders]);
    }

    ///////////////////////DATATYPE/////////////////////////////////
    vp_updateoption('smile_datatype',$_REQUEST["smile_datatype"]); 
    vp_updateoption('smile_datatype',$_REQUEST["smile_datatype"]);

    ///////////////////////////////VTU DATA UPDATE////////////////////////////////

vp_updateoption("smilebaseurl",$_REQUEST["smilebaseurl"]);
vp_updateoption("smileendpoint",$_REQUEST["smileendpoint"]);
vp_updateoption("smilerequest",$_REQUEST["smilerequest"]);
vp_updateoption("smilerequesttext",$_REQUEST["smilerequesttext"]);
vp_updateoption("smilesuccesscode",$_REQUEST["smilesuccesscode"]);
vp_updateoption("smilesuccessvalue",$_REQUEST["smilesuccessvalue"]);
vp_updateoption("smilesuccessvalue2",$_REQUEST["smilesuccessvalue2"]);

vp_updateoption("smilerequest_id",$_REQUEST["smilerequest_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("smilehead".$cheaders,$_REQUEST["smilehead".$cheaders]);
vp_updateoption("smilevalue".$cheaders,$_REQUEST["smilevalue".$cheaders]);
}


vp_updateoption("smileaddpost",$_REQUEST["smileaddpost"]);

for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("smilepostdata".$cpost,$_REQUEST["smilepostdata".$cpost]);
vp_updateoption("smilepostvalue".$cpost,$_REQUEST["smilepostvalue".$cpost]);
}

vp_updateoption("smileamountattribute",$_REQUEST["smileamountattribute"]);
vp_updateoption("smilevariationattr",$_REQUEST["smilevariationattr"]);
vp_updateoption("smilephoneattribute",$_REQUEST["smilephoneattribute"]);
vp_updateoption("smilenetworkattribute",$_REQUEST["smilenetworkattribute"]);

for($i=0; $i<=15; $i++){
vp_updateoption("csmiledata".$i,$_REQUEST["csmiledata".$i]);
vp_updateoption("csmiledatan".$i,$_REQUEST["csmiledatan".$i]);
vp_updateoption("csmiledatap".$i,$_REQUEST["csmiledatap".$i]);

}





vp_updateoption("smileidattr",$_REQUEST["smileidattr"]);
vp_updateoption("smile_extra1",$_REQUEST["smile_extra1"]);
/////////////////////////////////////////////////////////////////////////////


vp_updateoption("smile_head",$_REQUEST["smilehead"]);
vp_updateoption("smile1_response_format",$_REQUEST["smileresponse"]);
vp_updateoption("smile1_response_format_text",$_REQUEST["smileresponsetext"]);




die("100");

}
?>