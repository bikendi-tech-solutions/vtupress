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


vp_updateoption('billresponse_id',$_REQUEST["billresponse_id"]);

vp_updateoption("billquerytext",$_REQUEST["billquerytext"]);
vp_updateoption("billquerymethod",$_REQUEST["billquerymethod"]);
vp_updateoption("billaddendpoint",$_REQUEST["billaddendpoint"]);

vp_updateoption("bill_charge",$_REQUEST["bill_charge"]);


////////////////////////////////////////////////BILL////////////////////////////////////
vp_updateoption("billbaseurl",$_REQUEST["billbaseurl"]);
vp_updateoption("billendpoint",$_REQUEST["billendpoint"]);
vp_updateoption("billrequest",$_REQUEST["billrequest"]);
vp_updateoption("billrequesttext",$_REQUEST["billrequesttext"]);
vp_updateoption("billsuccesscode",$_REQUEST["billsuccesscode"]);
vp_updateoption("billsuccessvalue",$_REQUEST["billsuccessvalue"]);
vp_updateoption("billsuccessvalue2",$_REQUEST["billsuccessvalue2"]);
vp_updateoption("metertoken",$_REQUEST["metertoken"]);

vp_updateoption("brequest_id",$_REQUEST["brequest_id"]);

for($cheaders=1; $cheaders<=1; $cheaders++){
vp_updateoption("billhead".$cheaders,$_REQUEST["billhead".$cheaders]);
vp_updateoption("billvalue".$cheaders,$_REQUEST["billvalue".$cheaders]);
}

vp_updateoption("billaddpost",$_REQUEST["billaddpost"]);


for($cpost=1; $cpost<=5; $cpost++){
vp_updateoption("billpostdata".$cpost,$_REQUEST["billpostdata".$cpost]);
vp_updateoption("billpostvalue".$cpost,$_REQUEST["billpostvalue".$cpost]);
}

for($i=0; $i<=35; $i++){

    vp_updateoption("cbill".$i,$_REQUEST["cbill".$i]);
    vp_updateoption("cbilln".$i,$_REQUEST["cbilln".$i]);


}


vp_updateoption("billamountattribute",$_REQUEST["billamountattribute"]);
vp_updateoption("billphoneattribute",$_REQUEST["billphoneattribute"]);
vp_updateoption("cbvariationattr",$_REQUEST["cbvariationattr"]);
vp_updateoption("btypeattr",$_REQUEST["btypeattr"]);
vp_updateoption("cmeterattr",$_REQUEST["cmeterattr"]);

for($j=0; $j<=3; $j++){
vp_updateoption("billname".$j,$_REQUEST["billname".$j]);
vp_updateoption("billid".$j,$_REQUEST["billid".$j]);
}

for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
vp_updateoption("billaddheaders".$billaddheaders,$_REQUEST["billaddheaders".$billaddheaders]);
vp_updateoption("billaddvalue".$billaddheaders,$_REQUEST["billaddvalue".$billaddheaders]);
}

vp_updateoption("bill_response_format_text",$_REQUEST["billresponsetext"]);

vp_updateoption("bill_response_format",$_REQUEST["billresponse"]);

vp_updateoption("bill_head",$_REQUEST["billhead"]);



die("100");


}


?>