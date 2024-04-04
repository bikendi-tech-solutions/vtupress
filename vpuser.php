<?php
if (!isset($_SESSION)) {
  if (headers_sent()) {
//
} else {
  session_start();
}
}
$option_array = json_decode(get_option("vp_options"),true);

vp_addoption('manual_funding', "no message");
vp_addoption("show_services_bonus", "yes");
vp_addoption("refbo",0);
vp_addoption("cb",0);

add_shortcode("vpaccount","vpaccount");
vp_addoption("vpap","no");
vp_addoption("vpwalm","No message");


//user account





function vpaccount(){

  $vp_temp = vp_getoption("vp_template");


  if(!is_admin()){
    $_SESSION["current_clr"] = uniqid("current_clr-",false);
  $_SESSION["run_code"] = "vtupress";
  if(!isset($_SESSION["last_bal"])){
    $_SESSION["last_bal"] = 0;
  }

  if(!isset($_SESSION["trans_reversal"])){
    $_SESSION["trans_reversal"] = "no";
  }


if(!isset($_SESSION["last_transaction_time"])){
$_SESSION["last_transaction_time"] = "null";
$_SESSION["last_recipient"] = "null";
}
$_SESSION["run_code"] = "vtupress";
$id = get_current_user_id();



$nw_updt = 1;

if(vp_getuser($id,"fix_version",true) != $nw_updt){
  vp_updateuser($id,"beneficiaries","");
  vp_updateuser($id,"fix_version",$nw_updt);
}


  vp_updateoption($id,"beneficiaries", "");


if(!defined('ABSPATH')){
  $pagePath = explode('/wp-content/', dirname(__FILE__));
  include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');


  //Checking Dependencies
  $phpVersion = phpversion();
  if(floatval($phpVersion) < floatval("7.4.0") || floatval($phpVersion) > floatval("8.0.60")){

    $title = "PHP VERSION ERROR ";
    $message = "You are using an incompatible php version of <b>$phpVersion</b>, this might break up your experience. Kindly send a request to your developer or change your php version yourself on cpanel if you use one to not less than <b>7.4</b> and not greater than <b>8.0</b> ";
    dump_error($title, $message);
  }
  elseif(vp_getoption("vprun") != "none"){

    if(!current_user_can("vtupress_admin")){
      $title = "Service Currently Inactive!";
      $message = "Please note that we are currently inactive. We would get back to you as soon as possible. Kindly bear with us.";  
    }
    else{
      $title = "Subscription Expired!";
      $message = "Your subscription must have expired as this service is currently inactive please check on well and make renewal else contact us @ vtupress if the issue is not related to subscrition. You can try re-activating your license to see error message";  
    }
 dump_error($title, $message);
  }


  
if(strtolower(vp_getoption("vpdebug")) != "yes"){
  error_reporting(0);
  }
  else{
  error_reporting(E_ALL & ~E_NOTICE);
  }

if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off"){
ob_start();
header("Access-Control-Allow-Origin: 'self'");
header("Content-Security-Policy: https:");
 //Script_Transport-Security
header("strict-transport-security: max-age=31536000 ");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("X-Xss-Protection: 1");
header( 'Permissions-Policy: geolocation=(self ),camera=(self), microphone=(self)');
ob_end_flush();

}

$option_array = json_decode(get_option("vp_options"),true);


if( is_user_logged_in() ){
$ma_id = get_current_user_id();
$vpaccess = vp_getuser($ma_id,'vp_user_access',true);
$vp_most_secured_version = vp_getuser($ma_id,'vp_most_secured_version',true);
$msv = 1;
if($vp_most_secured_version != $msv){
  $new_pin = rand(1111,9999);
  vp_updateuser($ma_id,'vr_id',uniqid($ma_id));
 // vp_updateuser($ma_id,'vp_pin',$new_pin);
  vp_updateuser($ma_id,'vp_most_secured_version',$msv);
}

$my_current_plan = vp_getuser($ma_id,'vr_plan',true);

//Add VP_USER
vp_adduser($ma_id,"all_my_plans",$my_current_plan );

$all_my_plans = vp_getuser($ma_id,"all_my_plans",true );

if(!is_numeric(stripos($all_my_plans,$my_current_plan ))){
  vp_updateuser($ma_id,"all_my_plans",$all_my_plans.",".$my_current_plan);
}

vp_adduser($ma_id,"beneficiaries","");





$verify_email = strtolower(vp_getoption("email_verification"));
if($verify_email != "false" && $verify_email != "no" ){

$verify = vp_getuser($ma_id,"email_verified");
if(strtolower( $verify ) != "verified" && !current_user_can("administrator")){

  $DIAL = "<a href=".wp_logout_url(get_permalink()).">Log-Out</a>";
die("Please Verify Your Email Before You Can Proceed. $DIAL");
}else{}

}
else{}

$_SESSION["run_code"] = "vtupress";


//////////////////////////////////////

if(isset($_SESSION["user_id"]) && isset($_SESSION["add_unrecorded"])){
	if(is_numeric($_SESSION["user_id"]) && $_SESSION["add_unrecorded"] == 'yes' && isset($_SESSION["recipient"]) && isset($_SESSION["service"]) && $_SESSION["service"] !=  "ssms"){
  global $wpdb;
  
  $service = $_SESSION["service"];
  $trans_id = $_SESSION["request_id"];
  $serv_tab = $wpdb->prefix.$service;
  $service_result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $serv_tab WHERE request_id = %s",$trans_id));
  
  if(empty($service_result) || $service_result == null){
  $vptable = $wpdb->prefix."vp_transactions";
  $vpresult = $wpdb->get_results($wpdb->prepare("SELECT * FROM $vptable WHERE request_id = %s ", $trans_id));
  
  if(empty($vpresult) || $vpresult == null){
  
    if(isset($_SESSION["api_from"])){
      $fro = $_SESSION["api_from"];
    }else{
      $fro = "User_Dashboard";
    }

    if(isset($_SESSION["api_response"])){
      $resp = $_SESSION["api_response"];
    }else{
      $resp = "Undefined";
    }

  global $wpdb;
  $table_trans = $wpdb->prefix.'vp_transactions';
  $unrecorded_added = $wpdb->insert($table_trans, array(
  'status' => 'Fa',
  'service' => $service,
  'name'=> $_SESSION["name"],
  'email'=> $_SESSION["email"],
  'recipient' => $_SESSION["recipient"],
  'bal_bf' => $_SESSION["bal_bf"],
  'bal_nw' => $_SESSION["bal_nw"],
  'amount' => $_SESSION["amount"],
  'request_id' => $trans_id,
  'user_id' => $_SESSION["user_id"],
  'api_response' => $resp,
  'api_from' => $fro,
  'the_time' => $_SESSION["the_time"]
  ));
  
  $_SESSION["add_unrecorded"] = 'no';
  $_SESSION["user_id"] = "none";
  unset($_SESSION["user_id"]);
  unset($_SESSION["recipient"]);
  
  }else{}
  
  }else{}
  
  }else{}
  
  }else{}

///////////////////
//////////////////

 if(strtolower($vpaccess) == "false" || strtolower($vpaccess) == "access" || current_user_can("administrator")){

  if(vp_getoption("vtu_timeout") != "false" && vp_getoption("vtu_timeout") != "0" && !empty(vp_getoption("vtu_timeout")) && is_numeric(vp_getoption("vtu_timeout"))){


  //die("SEEfN");

  if(intval(vp_getoption("vtu_timeout")) <= 60 && intval(vp_getoption("vtu_timeout")) > 0){

    if(isset($_SESSION["vtuloggedin"]) && isset($_SESSION["last_login"])){

    }
    else{
  
      wp_logout();

      if(!is_plugin_active("$vp_temp/$vp_temp.php")){
        include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/access.php");
      }
      else{
        include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/access.php");
      }

      die();
    }


  $last_login = $_SESSION["last_login"];
  $dur = vp_getoption("vtu_timeout");
  $cur = date('Y-m-d H:i:s',$current_timestamp);
  $timeout = date("Y-m-d H:i:s",strtotime("$last_login +$dur minutes"));
     
 // die( $cur ." - ".  $timeout ." -l-= " .  $last_login );
    if($cur < $timeout){

    $_SESSION["last_login"] = date('Y-m-d H:i:s',$current_timestamp);

    if(!is_plugin_active("$vp_temp/$vp_temp.php")){
      include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/dashboard.html");
    }
    else{
      include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/dashboard.php");
    }


    die();
  }
  else{
    wp_logout();
      if(!is_plugin_active("$vp_temp/$vp_temp.php")){
        include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/access.php");
      }
      else{
        include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/access.php");
      }
    die();
  }
  }else{

    if(!is_plugin_active("$vp_temp/$vp_temp.php")){
      include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/dashboard.html");
    }
    else{
      include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/dashboard.php");
    }

    die();
  }
  }else{
        if(!is_plugin_active("$vp_temp/$vp_temp.php")){
        include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/dashboard.html");
      }
      else{
        include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/dashboard.php");
      }
  }

  session_write_close();

 }
 else{
	 die("<h1>You have been banned to access your dashboard</h1>");
 }

}
else{

      if(!is_plugin_active("$vp_temp/$vp_temp.php")){
        include_once(ABSPATH."wp-content/plugins/vtupress/template/".constant('vtupress_template')."/access.php");
      }
      else{
        include_once(ABSPATH."wp-content/plugins/".constant('vtupress_template')."/access.php");
      }
      
die();

}



}

}



function create_vpaccount(){

$user_ID = 1;

$query = new WP_Query( array( 'pagename' => 'vpaccount' ) );

    if( !$query->have_posts()){
	$new_post = array(
		      'post_title' => 'VTU DASHBOARD',
		      'post_name' => 'vpaccount',
		      'post_content' => '[vpaccount]',
		      'post_status' => 'publish',
		      'post_date' => date('Y-m-d H:i:s', time()),
		      'post_author' => $user_ID,
		      'post_type' => 'page'
		);
	$post_id = wp_insert_post($new_post);
   }
  

}



session_write_close();
?>