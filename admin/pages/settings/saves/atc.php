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
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');




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


vp_updateoption('airtime_to_wallet', $_REQUEST['airtime_to_wallet']);
vp_updateoption('airtime_to_cash', $_REQUEST['airtime_to_cash']);
vp_updateoption('mtn_airtime', $_REQUEST['mtn_airtime']);
vp_updateoption('glo_airtime', $_REQUEST['glo_airtime']);
vp_updateoption('airtel_airtime', $_REQUEST['airtel_airtime']);
vp_updateoption('9mobile_airtime', $_REQUEST['9mobile_airtime']);
vp_updateoption('airtime_to_cash_charge', $_REQUEST['airtime_to_cash_charge']);
vp_updateoption('airtime_to_wallet_charge', $_REQUEST['airtime_to_wallet_charge']);

vp_updateoption('aairtime_to_cash_charge', $_REQUEST['aairtime_to_cash_charge']);
vp_updateoption('aairtime_to_wallet_charge', $_REQUEST['aairtime_to_wallet_charge']);

vp_updateoption('9airtime_to_cash_charge', $_REQUEST['9airtime_to_cash_charge']);
vp_updateoption('9airtime_to_wallet_charge', $_REQUEST['9airtime_to_wallet_charge']);

vp_updateoption('gairtime_to_cash_charge', $_REQUEST['gairtime_to_cash_charge']);
vp_updateoption('gairtime_to_wallet_charge', $_REQUEST['gairtime_to_wallet_charge']);

die("100");
?>