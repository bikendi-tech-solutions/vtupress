<?php
header("Access-Control-Allow-Origin: 'self'");
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

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm\/wp-admin/",$referer)) {
		die("REF ENT PERM");
	}

}else{
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


vp_updateoption('betresponse_id',$_REQUEST["betresponse_id"]);
vp_updateoption("betquerytext",$_REQUEST["betquerytext"]);
vp_updateoption("betquerymethod",$_REQUEST["betquerymethod"]);
vp_updateoption("betaddendpoint",$_REQUEST["betaddendpoint"]);
vp_updateoption("betcharge",$_REQUEST["betcharge"]);


for($betaddheaders=1; $betaddheaders<=4; $betaddheaders++){
    vp_updateoption("betaddheaders".$betaddheaders,$_REQUEST["betaddheaders".$betaddheaders]);
    vp_updateoption("betaddvalue".$betaddheaders,$_REQUEST["betaddvalue".$betaddheaders]);
    }



    ///////////////////////////////VTU DATA UPDATE////////////////////////////////

vp_updateoption("betbaseurl",$_REQUEST["betbaseurl"]);
vp_updateoption("betendpoint",$_REQUEST["betendpoint"]);
vp_updateoption("betrequest",$_REQUEST["betrequest"]);
vp_updateoption("betrequesttext",$_REQUEST["betrequesttext"]);
vp_updateoption("betsuccesscode",$_REQUEST["betsuccesscode"]);
vp_updateoption("betsuccessvalue",$_REQUEST["betsuccessvalue"]);
vp_updateoption("betsuccessvalue2",$_REQUEST["betsuccessvalue2"]);

vp_updateoption("betrequest_id",$_REQUEST["betrequest_id"]);


for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("bethead".$cheaders,$_REQUEST["bethead".$cheaders]);
vp_updateoption("betvalue".$cheaders,$_REQUEST["betvalue".$cheaders]);
}


vp_updateoption("betaddpost",$_REQUEST["betaddpost"]);

for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("betpostdata".$cpost,$_REQUEST["betpostdata".$cpost]);
vp_updateoption("betpostvalue".$cpost,$_REQUEST["betpostvalue".$cpost]);
}

vp_updateoption("betamountattribute",$_REQUEST["betamountattribute"]);
vp_updateoption("betcustomeridattribute",$_REQUEST["betcustomeridattribute"]);
vp_updateoption("betcompanyattribute",$_REQUEST["betcompanyattribute"]);

for($i=0; $i<=15; $i++){
vp_updateoption("cbetdata".$i,$_REQUEST["cbetdata".$i]);
vp_updateoption("cbetdatan".$i,$_REQUEST["cbetdatan".$i]);

}



/////////////////////////////////////////////////////////////////////////////


vp_updateoption("bet_head",$_REQUEST["bethead"]);
vp_updateoption("bet1_response_format",$_REQUEST["betresponse"]);
vp_updateoption("bet1_response_format_text",$_REQUEST["betresponsetext"]);




die("100");

}
?>