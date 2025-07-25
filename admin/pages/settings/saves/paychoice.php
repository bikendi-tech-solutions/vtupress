<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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





if(isset($_POST['updatefl'])){

    vp_updateoption('ppub', $_POST['ppublic']);
    vp_updateoption('psec', $_POST['psecret']);
    vp_updateoption('enable_paystack', $_POST['enable_paystack']);

    vp_updateoption('charge_method', $_POST['charge_method']);
    vp_updateoption('charge_back', $_POST['charge_back']);

    vp_updateoption('paystack_charge_method', $_POST['paystack_charge_method']);
    vp_updateoption('paystack_charge_back', $_POST['paystack_charge_back']);

    vp_updateoption('kuda_charge_method', $_POST['kuda_charge_method']);
    vp_updateoption('kuda_charge_back', $_POST['kuda_charge_back']);

    vp_updateoption('vpay_charge_method', $_POST['vpay_charge_method']);
    vp_updateoption('vpay_charge_back', $_POST['vpay_charge_back']);

    vp_updateoption('gtb_charge_method', $_POST['gtb_charge_method']);
    vp_updateoption('gtb_charge_back', $_POST['gtb_charge_back']);
    vp_updateoption('squad_admin_fn', $_POST['squad_admin_fn']);
    vp_updateoption('squad_admin_ln', $_POST['squad_admin_ln']);
    vp_updateoption('squad_admin_dob', $_POST['squad_admin_dob']);
    vp_updateoption('squad_admin_bvn', $_POST['squad_admin_bvn']);

  //  if(is_plugin_active("vtupress4squadco/vtupress4squadco.php")){

    vp_updateoption('vpay_public', $_POST['vpay_public']);
    vp_updateoption('vpay_email', $_POST['vpay_email']);
    vp_updateoption('vpay_password', $_POST['vpay_password']);
    vp_updateoption('enablevpay', $_POST['enablevpay']);


    vp_updateoption('squad_public', $_POST['squadpublic']);
    vp_updateoption('squad_secret', $_POST['squadsecret']);
    vp_updateoption('enablesquadco', $_POST['enablesquadco']);
    
    vp_updateoption('kuda_email', $_POST['kuda_email']);
    vp_updateoption('kuda_apikey', $_POST['kuda_apikey']);
    vp_updateoption('enablekuda', $_POST['enablekuda']);
    
  //  }


  //  vp_updateoption('monnifytestmode', $_POST['monnifytest']);
    vp_updateoption('monnifyapikey', $_POST['mapi']);
    vp_updateoption('monnifysecretkey', $_POST['msec']);
    vp_updateoption('monnifycontractcode', $_POST['mcontract']);
    vp_updateoption('monnifybvn', $_POST['monnifybvn']);
    vp_updateoption('enable_monnify', $_POST['enable_monnify']);



    vp_updateoption('allow_card_method', $_POST['allow_card_method']);
   // vp_updateoption('paychoice', $_POST['paychoice']);

   if(vp_getoption("vtupress_custom_ncwallet") == "yes"){

    vp_updateoption('ncwallet_apikey', $_POST['ncwallet_apikey']);
    vp_updateoption('ncwallet_pin', $_POST['ncwallet_pin']);
    vp_updateoption('ncwallet_charge_method', $_POST['ncwallet_charge_method']);
    vp_updateoption('ncwallet_charge_back', $_POST['ncwallet_charge_back']);
    vp_updateoption('ncwallet_admin_bvn', $_POST['ncwallet_admin_bvn']);
    vp_updateoption('enable_ncwallet', $_POST['enable_ncwallet']);

 }

 if(vp_getoption("vtupress_custom_billstack") == "yes"){

  vp_updateoption('billstack_apikey', $_POST['billstack_apikey']);
  vp_updateoption('billstack_charge_method', $_POST['billstack_charge_method']);
  vp_updateoption('billstack_charge_back', $_POST['billstack_charge_back']);
  vp_updateoption('enable_billstack', $_POST['enable_billstack']);

}
    

if(vp_getoption("vtupress_custom_payvessel") == "yes"){

  vp_updateoption('payvessel_apikey', $_POST['payvessel_apikey']);
  vp_updateoption('payvessel_seckey', $_POST['payvessel_seckey']);
  vp_updateoption('payvessel_biz', $_POST['payvessel_biz']);
  vp_updateoption('payvessel_admin_bvn', $_POST['payvessel_admin_bvn']);
  vp_updateoption('payvessel_charge_method', $_POST['payvessel_charge_method']);
  vp_updateoption('payvessel_charge_back', $_POST['payvessel_charge_back']);
  vp_updateoption('enable_payvessel', $_POST['enable_payvessel']);

}


if(vp_getoption("vtupress_custom_auto_manual") == "yes"){

  vp_updateoption('auto_manual_apikey', $_POST['auto_manual_apikey']);
  vp_updateoption('auto_manual_charge_method', $_POST['auto_manual_charge_method']);
  vp_updateoption('auto_manual_charge_back', $_POST['auto_manual_charge_back']);
  vp_updateoption('auto_manual_info', $_POST['auto_manual_info']);
  
  vp_updateoption('enable_auto_manual', $_POST['enable_auto_manual']);
  for($i = 1; $i <= 3; $i++){
    vp_updateoption('auto_manual_account_number'.$i, $_POST['auto_manual_account_number'.$i]);
    vp_updateoption('auto_manual_bank_name'.$i, $_POST['auto_manual_bank_name'.$i]);
    vp_updateoption('auto_manual_account_name'.$i, $_POST['auto_manual_account_name'.$i]);

  }

}


if(vp_getoption("vtupress_custom_paymentpoint") == "yes"){

  vp_updateoption('paymentpoint_apikey', $_POST['paymentpoint_apikey']);
  vp_updateoption('paymentpoint_secretkey', $_POST['paymentpoint_seckey']);
  vp_updateoption('paymentpoint_businessid', $_POST['paymentpoint_biz']);
  vp_updateoption('paymentpoint_admin_bvn', $_POST['paymentpoint_admin_bvn']);
  vp_updateoption('paymentpoint_charge_method', $_POST['paymentpoint_charge_method']);
  vp_updateoption('paymentpoint_charge_back', $_POST['paymentpoint_charge_back']);
  vp_updateoption('enable_paymentpoint', $_POST['enable_paymentpoint']);

}

if(vp_getoption("vtupress_custom_nomba") == "yes"){

  vp_updateoption('nomba_apikey', $_POST['nomba_apikey']);
  vp_updateoption('nomba_secretkey', $_POST['nomba_seckey']);
  vp_updateoption('nomba_businessid', $_POST['nomba_biz']);
  vp_updateoption('nomba_sign', $_POST['nomba_sign']);
  vp_updateoption('nomba_admin_bvn', $_POST['nomba_admin_bvn']);
  vp_updateoption('nomba_charge_method', $_POST['nomba_charge_method']);
  vp_updateoption('nomba_charge_back', $_POST['nomba_charge_back']);
  vp_updateoption('enable_nomba', $_POST['enable_nomba']);

}
    


   if(vp_getoption("vtupress_custom_kuda") == "yes" && $_POST['enablekuda'] == "yes"){

    $kuda_email = trim(vp_getoption("kuda_email"));
    $kuda_apikey = trim(vp_getoption("kuda_apikey"));

    if(!empty($kuda_email) && !empty($kuda_apikey)){



      $live_url = "https://kuda-openapi.kuda.com/v2.1/Account/GetToken";

      $array = [];
      $array["cache-control"] = "no-cache";
      $array["Content-Type"] = "application/JSON";
      $array["Accept"] = "application/JSON";
      
      $datass = [
          'email' => $kuda_email,
          'apiKey' => $kuda_apikey
          ];
          
          
      $http_args = array(
      'headers' => $array,
      'timeout' => '120',
      'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
      'blocking'=> true,
      'body' => json_encode($datass)
      );
      
      
      $call =  wp_remote_post($live_url, $http_args);
      $response = wp_remote_retrieve_body($call);
      
      
      vp_updateoption("kuda_generated_apikey",str_replace('"',"",$response));
      
      

    }


   }



    
die('{"status":"100"}');
}?>