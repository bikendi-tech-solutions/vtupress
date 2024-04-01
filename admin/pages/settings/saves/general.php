<?php
header("Access-Control-Allow-Origin: 'self'");
//header("Access-Control-Allow-Methods: POST, GET");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
else{
include_once(ABSPATH ."wp-load.php");
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH.'wp-admin/includes/plugin.php');
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
        $spray_code = $update_code;
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
        //die("INVALID SPRAYCODE $spray_code != $spray_code");
    }
}elseif(strtolower($spray_code) == "false"){
    if(strtolower($real_code) == "false"){
        $cur_id = get_current_user_id();
        $update_code = uniqid("vtu_$cur_id");
        vp_updateoption("spraycode",$update_code);

        $spray_code = $update_code;
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
        //die("INVALID SPRAYCODE");
    }
}



if(isset($_POST["fset"])){


    vp_updateoption("vpdebug", trim($_POST["vpdebug"]));
    vp_updateoption("spraycode", $spray_code);
    if(vp_getoption("vp_security") == "yes"){
    vp_updateoption("auto_transfer", trim($_POST["auto_transfer"]));
    }
    vp_updateoption("vp_enable_registration", trim($_POST["vp_enable_registration"]));
    vp_updateoption("enable_beneficiaries", trim($_POST["enable_beneficiaries"]));
    vp_updateoption("vp_template", trim($_POST["template"]));
    vp_updateoption("vtu_timeout", trim($_POST["vtu_timeout"]));
    vp_updateoption("hide_why", trim($_POST["hide_why"]));
    vp_updateoption("resc", trim($_POST["upgradeamt"]));
    vp_updateoption("vp_redirect", trim($_POST["vpredirect"]));
    vp_updateoption("wplogin_redirect", trim($_POST["wplogin_redirect"]));
    vp_updateoption("vp_phone_line", trim($_POST["vpphone"]));
    vp_updateoption("vp_whatsapp", trim($_POST["vpwhatsapp"]));
    vp_updateoption("vp_whatsapp_group", trim($_POST["vpwhatsappg"]));
    vp_updateoption("allow_crypto", trim($_POST["allow_crypto"]));
    vp_updateoption("allow_cards", trim($_POST["allow_cards"]));
    vp_updateoption("totcons", trim($_POST["totcons"]));
    vp_updateoption("minimum_amount_fundable", trim($_POST["minimum_amount_fundable"]));
    vp_updateoption("t_header_check", trim($_POST["t_header_check"]));
    
    //Emails Nd Auto Refund
    if(is_plugin_active('vprest/vprest.php') && vp_getoption("resell") == "yes" ){
    vp_updateoption("bvn_verification_charge", trim($_POST["bvn_verification_charge"]));
  //  vp_updateoption("raptor_apikey", trim($_POST["raptor_apikey"]));
 //   vp_updateoption("raptor_conid", trim($_POST["raptor_conid"]));
    vp_updateoption("auto_refund", trim($_POST["auto_refund"]));
    vp_updateoption("sms_transaction_admin", trim($_POST["sms_transaction_admin"]));
    vp_updateoption("sms_transaction_user", trim($_POST["sms_transaction_user"]));
    vp_updateoption("email_verification", trim($_POST["email_verification"]));
    vp_updateoption("email_transaction", trim($_POST["email_transaction"]));
    vp_updateoption("email_transfer", trim($_POST["email_transfer"]));
    vp_updateoption("email_withdrawal", trim($_POST["email_withdrawal"]));
    vp_updateoption("email_kyc", trim($_POST["email_kyc"]));
    vp_updateoption("for_active_user_balance", trim($_POST["for_active_user_balance"]));

    if(is_plugin_active('vpmlm/vpmlm.php') ){
    vp_updateoption("id_on_reg", trim($_POST["id_on_reg"]));
    }
    }



    


    vp_updateoption("vpwalm", trim($_POST["message"]));
    vp_updateoption("show_notify", trim($_POST["show_notify"]));
    vp_updateoption("manual_funding", trim($_POST["fundmessage"]));

    
    if(is_plugin_active("vprest/vprest.php")){

        vp_updateoption("allow_withdrawal", trim($_POST["allow_withdrawal"]));
        vp_updateoption("allow_to_bank", trim($_POST["allow_to_bank"]));
        vp_updateoption("wallet_to_wallet", trim($_POST["wallettowallet"]));
        vp_updateoption("minimum_amount_transferable", trim($_POST["minimum_amount_transferable"]));
    

    }
    
    die('{"status":"100"}');
    
}?>