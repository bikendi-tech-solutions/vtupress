<?php
/**
 * VtuPress
 * 
 * @package VtuPress 
 * @author Akor Victor
 * @copyright 2020 vtupress 
 * @license GPL-3.0-or-later 
 *
*Plugin Name: VTU Press
*Plugin URI: http://vtupress.com
*Description: This is the very first <b>VTU plugin</b>. It's VTU services are all Automated with wonderful features
*Version: 5.9.8
*Author: Akor Victor
*Author URI: https://facebook.com/vtupressceo
*License:      GPL3
*License URI:  https://www.gnu.org/licenses/gpl.html
*Domain Path:  /languages
*/
/*
{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.
 
{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {URI to Plugin License}.
*/

//--hRequires PHP: 7.4




if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}


include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-admin/includes/plugin.php');
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
add_action('wp_head','vtupress_user_update');

global $current_timestamp;
$current_timestamp = current_time('timestamp');


//add_shortcode( 'vtupress', 'vtupress_func' );
add_shortcode( 'vtupress_airtime', 'vtupress_airtime' );
add_shortcode( 'vtupress_data', 'vtupress_data' );
add_shortcode( 'vtupress_bet', 'vtupress_bet' );

do_action("vtupressmain");
include_once('database.php');
include_once('vtusettings.php');
include_once('vtupressaction.php');
include_once('transaction.php');
include_once('actions.php');
include_once('vpcustom.php');
include_once('vpuser.php');


error_log(date('Y-m-d h:i:s A',$current_timestamp));

function vtupress_user_update(){
ob_start();
header("Content-Security-Policy: https:");
//Script_Transport-Security
header("strict-transport-security: max-age=31536000 ");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: same-origin");
header("X-Xss-Protection: 1");
header('Permissions-Policy: geolocation=(self ),camera=(self), microphone=(self)');

ob_flush();


if(is_user_logged_in()){
    if(current_user_can("administrator")){
  global $wpdb;
  $user = $wpdb->prefix.'users';
  $all_users = $wpdb->get_results("SELECT * FROM $user");
  foreach($all_users as $use){
  
    $id = $use->ID;
    $bal = vp_getuser($id, 'vp_bal', true);
    if(empty($bal)){
      $bal = 0;
      vp_updateuser($id, 'vp_bal', "0");
    }
    
    $arr = ['vp_bal' => $bal ];
    $where = ['ID' => $id];
    $updated = $wpdb->update($user, $arr, $where);
    
    }
  
  }
  
  }


  if(vp_getoption("vtu_timeout") != "false" && vp_getoption("vtu_timeout") != "0" && !empty(vp_getoption("vtu_timeout")) && is_user_logged_in() && is_numeric(vp_getoption("vtu_timeout"))){
    
    if(intval(vp_getoption("vtu_timeout")) <= 60 && intval(vp_getoption("vtu_timeout")) > 0){
    ob_start();
    if (!isset($_SESSION)) {
      if (headers_sent()) {
    //
    } else {
     session_start();
    }
    }
global $current_timestamp;
  
    if(isset($_SESSION["last_login"])){
    $last_login = $_SESSION["last_login"];
    $dur = vp_getoption("vtu_timeout");
    $cur = date('Y-m-d H:i:s',$current_timestamp);
    $timeout = date("Y-m-d H:i:s",strtotime("$last_login +$dur minutes"));
      if($cur < $timeout){
        $_SESSION["last_login"] = date('Y-m-d H:i:s',$current_timestamp);

        
      }
      else{
      wp_logout();
      }
    }else{
      wp_logout();
    }

    session_write_close();

    ob_end_flush();

    }
  }

}

require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bikendi-tech-solutions/vtupress',
	__FILE__,
	'vtupress'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//Optional: If you're using a private repository, specify the access token like this:

/*
require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://vtupress.com/vtupress.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'vtupress'
);
*/

/*
add_action( 'admin_init', 'wpdocs_plugin_admin_init' );
function wpdocs_plugin_admin_init() {
	wp_register_style( 'wpdocsPluginStylesheet', plugins_url("vtupress/admin")."/dist/css/style.min.css" );
}
add_action( "admin_print_styles", 'wpdocs_plugin_admin_styles' );
function wpdocs_plugin_admin_styles() {
	wp_enqueue_style( 'wpdocsPluginStylesheet' );
}
*/


add_action('wp_login','vtupress_login_session');
function vtupress_login_session(){
  global $current_timestamp;
  ob_start();
  if (!isset($_SESSION)) {
    if (headers_sent()) {
  //
  } else {
    session_start();
  }
  }


  $_SESSION["vtuloggedin"] = "yes";
  $_SESSION["last_login"] = date('Y-m-d H:i:s',$current_timestamp);

  session_write_close();
  ob_end_flush();
}



add_action('shutdown','vtupress_shutdown');
function vtupress_shutdown(){

  session_write_close();

}

vp_addoption('enable_raptor',"no");
vp_addoption("spraycode",uniqid());

vp_addoption("auto_refund","yes");
vp_addoption('minimum_amount_transferable',10000);
vp_addoption('minimum_amount_fundable',1000);

vp_addoption("vp_users_email","Nast, v1, win2, win");

if(vp_getoption("http_redirect") == "true"){
	
add_action('template_redirect', 'vp_redirect_core', 50);
add_action('init', 'vp_redirect_core', 50);
add_action('wp_loaded', 'vp_redirect_core', 50);
function vp_redirect_core(){
  if (!is_ssl()) {
    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301);

  }
}
	
}



	

	


if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off" && !is_admin() &&  stripos($_SERVER["SCRIPT_NAME"],"system.php") === false &&  stripos($_SERVER["SCRIPT_NAME"],"vend.php") === false &&  stripos($_SERVER["SCRIPT_NAME"],"saveauth.php") === false && stripos($_SERVER["SCRIPT_NAME"],"index.php") === false &&  stripos($_SERVER["SCRIPT_NAME"],"index.php") === false &&  stripos($_SERVER["SCRIPT_NAME"],"vpsystem.php") === false){





$ban_ip = vp_getoption("vp_ips_ban");
if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
	$system = "Shared Internet";
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
	if(is_numeric(stripos($ip_address,','))){
		$ip_address = trim(explode(',',$ip_address)[1]);
	}
	$system = "Proxy";
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
	$system = "Remote";
  }
if(is_numeric(stripos($ban_ip,$ip_address)) != false || stripos($ban_ip,$ip_address) != false && !empty($ip_address)){
	if(vp_getoption("access_website") == "true"){
			die(
	'
	<div style="text-align:center;">
	<h1>ACCESS DENIED</h1><br>
	<h4>You Have Been Denied Access To Access This Site [IP - '.$system." {".$_SERVER["SCRIPT_NAME"].'}]</h4><br>
	<p>Security Powered By: <a href="https://vtupress.com">vtupress</a></p><br>
	<p>0edc-8480-3434</p><br>
	</div>
	'
	);

	}
}

/*
if(vp_getoption("access_country") == "true"){
$details = json_decode(file_get_contents("https://ipinfo.io/$ip_address/json"));
if(isset($details->country)){
	if(strtolower($details->country) != "ng" && !empty($details->country)){
	die(
	'
	<div style="text-align:center;">
	<h1>ACCESS DENIED</h1><br>
	<h4>You Have Been Denied Access To Access This Site [NG - '.$_SERVER["SCRIPT_NAME"].']</h4><br>
	<p>Security Powered By: <a href="https://vtupress.com">vtupress</a></p><br>
	<p>0edc-8480-3434</p><br>
	</div>
	'
	);		

	}
}
}*/

}




vp_addoption('allow_card_method','yes');
vp_addoption('cron_successful','0');
vp_addoption('cron_failed','0');
vp_addoption('enablehollatag','no');
vp_addoption('hollatagcompany','');
vp_addoption('hollatagusername','');
vp_addoption('hollatagpassword','');
vp_addoption('hollatagservices','');

add_option("vtupress_options2","0");

$update_vtupress_options = 40;
if(get_option("vtupress_options2") != $update_vtupress_options){

  update_option('timezone_string',"Africa/Lagos");
  update_option('start_of_week',0);

  vtupress_verification();



  $url = home_url();
  $url = str_replace("http://","https://",$url);
  vp_updateoption("siteurl",$url);
  vp_updateoption("site_url",$url);
  vp_updateoption("site-url",$url);

  global $wpdb;
  $table_name = $wpdb->prefix.'vp_kyc';
  maybe_add_column($table_name,"accountNumber","ALTER TABLE $table_name ADD accountNumber text");
  maybe_add_column($table_name,"matchRate","ALTER TABLE $table_name ADD matchRate text");
  maybe_add_column($table_name,"accountName","ALTER TABLE $table_name ADD accountName text");
  maybe_add_column($table_name,"bankName","ALTER TABLE $table_name ADD bankName text");
  maybe_add_column($table_name,"bankCode","ALTER TABLE $table_name ADD bankCode text");

vp_addoption('allow_crypto','no');
vp_addoption('allow_cards','no');
vp_addoption("allow_withdrawal","no");
vp_addoption("allow_to_bank","no");
vp_addoption("vtu_mad",0);
vp_addoption("vtu_aad",0);
vp_addoption("vtu_9ad",0);
vp_addoption("vtu_gad",0);

vp_addoption("share_mad",0);
vp_addoption("share_aad",0);
vp_addoption("share_9ad",0);
vp_addoption("share_gad",0);

vp_addoption("awuf_mad",0);
vp_addoption("awuf_aad",0);
vp_addoption("awuf_9ad",0);
vp_addoption("awuf_gad",0);


vp_addoption("sme_mdd",0);
vp_addoption("sme_add",0);
vp_addoption("sme_9dd",0);
vp_addoption("sme_gdd",0);

vp_addoption("direct_mdd",0);
vp_addoption("direct_add",0);
vp_addoption("direct_9dd",0);
vp_addoption("direct_gdd",0);

vp_addoption("corporate_mdd",0);
vp_addoption("corporate_add",0);
vp_addoption("corporate_9dd",0);
vp_addoption("corporate_gdd",0);

vp_addoption("enable_coupon", "no");
vp_addoption("airtime_to_wallet", "no");
vp_addoption("airtime_to_cash", "no");
vp_addoption("mtn_airtime", "08012346789");
vp_addoption("glo_airtime", "08012346789");
vp_addoption("airtel_airtime", "08012346789");
vp_addoption("9mobile_airtime", "08012346789");
vp_addoption("airtime_to_wallet_charge", "0");
vp_addoption("airtime_to_cash_charge", "0");


vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("show_notify", "no");
vp_addoption("http_redirect", "true");
vp_addoption("global_security", "enabled");
vp_addoption("secur_mod", "calm");
vp_addoption("vp_ips_ban", "0.0.0.0,");
vp_addoption("vp_users_ban", "anonymous,hackers,demo");
vp_addoption("access_website", "true");
vp_addoption("access_user_dashboard", "true");
vp_addoption("access_country", "true");


vp_addoption("discount_method","direct");
vp_addoption("charge_back",0);
vp_addoption("wallet_to_wallet","yes");

vp_addoption("showlicense","none");

vp_addoption("manual_funding","No Message");

vp_addoption("reb", "yes");
vp_addoption("ppub", "Your Paystack Public Key");
vp_addoption("psec", "Your Paystack Secret Key");
vp_addoption("paychoice", "flutterwave");
vp_addoption("menucolo", "#cee8b8");
vp_addoption("dashboardcolo", "#ffff00");
vp_addoption("buttoncolo", "#ffff00");
vp_addoption("headcolo", "#4CAF50");
vp_addoption("sucreg", "/vpaccount");
vp_addoption("cairtimeb", "1");
vp_addoption("cdatab", "1");
vp_addoption("cdatabd", "1");
vp_addoption("cdatabc", "1");

vp_addoption("ccableb", "1");
vp_addoption("cbillb", "1");
vp_addoption("rairtimeb", "1");

vp_addoption("rdatab", "1");
vp_addoption("rdatabd", "1");
vp_addoption("rdatabc", "1");

vp_addoption("rcableb", "1");
vp_addoption("rbillb", "1");
vp_addoption("monnifyapikey","Your API KEY");
vp_addoption("monnifysecretkey","Your SECRET KEY");
vp_addoption("monnifycontractcode","Your Contract Code");
vp_addoption("monnifytestmode","false");



vp_addoption("airtime1_response_format_text","JSON");
vp_addoption("airtime1_response_format","json");
vp_addoption("airtime2_response_format_text","JSON");
vp_addoption("airtime2_response_format","json");
vp_addoption("airtime3_response_format_text","JSON");
vp_addoption("airtime3_response_format","json");
vp_addoption("data1_response_format_text","JSON");
vp_addoption("data1_response_format","json");
vp_addoption("data2_response_format_text","JSON");
vp_addoption("data2_response_format","json");
vp_addoption("data3_response_format_text","JSON");
vp_addoption("data3_response_format","json");
vp_addoption("cable_response_format_text","JSON");
vp_addoption("cable_response_format","json");
vp_addoption("bill_response_format_text","JSON");
vp_addoption("bill_response_format","json");


vp_addoption("airtime_head","not_concatenated");
vp_addoption("airtime_head2","not_concatenated");
vp_addoption("airtime_head3","not_concatenated");
vp_addoption("data_head","not_concatenated");
vp_addoption("data_head2","not_concatenated");
vp_addoption("data_head3","not_concatenated");
vp_addoption("cable_head","not_concatenated");
vp_addoption("bill_head","not_concatenated");




//SME
vp_updateoption("api0", 1);
vp_updateoption("api1", 2);
vp_updateoption("api2", 3);
vp_updateoption("api3", 4);
vp_updateoption("api4", 5);
vp_updateoption("api5", 6);
vp_updateoption("api6", 7);
vp_updateoption("api7", 8);
vp_updateoption("api8", 9);
vp_updateoption("api9", 10);
vp_updateoption("api10", 11);

vp_updateoption("aapi0", 12);
vp_updateoption("aapi1", 13);
vp_updateoption("aapi2", 14);
vp_updateoption("aapi3", 15);
vp_updateoption("aapi4", 16);
vp_updateoption("aapi5", 17);
vp_updateoption("aapi6", 18);
vp_updateoption("aapi7", 19);
vp_updateoption("aapi8", 20);
vp_updateoption("aapi9", 21);
vp_updateoption("aapi10", 22);

vp_updateoption("9api0", 23);
vp_updateoption("9api1", 24);
vp_updateoption("9api2", 25);
vp_updateoption("9api3", 26);
vp_updateoption("9api4", 27);
vp_updateoption("9api5", 28);
vp_updateoption("9api6", 29);
vp_updateoption("9api7", 30);
vp_updateoption("9api8", 31);
vp_updateoption("9api9", 32);
vp_updateoption("9api10", 33);

vp_updateoption("gapi0", 34);
vp_updateoption("gapi1", 35);
vp_updateoption("gapi2", 36);
vp_updateoption("gapi3", 37);
vp_updateoption("gapi4", 38);
vp_updateoption("gapi5", 39);
vp_updateoption("gapi6", 40);
vp_updateoption("gapi7", 41);
vp_updateoption("gapi8", 42);
vp_updateoption("gapi9", 43);
vp_updateoption("gapi10", 44);
//END SME


//CORPORATE
vp_updateoption("api20", 45);
vp_updateoption("api21", 46);
vp_updateoption("api22", 47);
vp_updateoption("api23", 48);
vp_updateoption("api24", 49);
vp_updateoption("api25", 50);
vp_updateoption("api26", 51);
vp_updateoption("api27", 52);
vp_updateoption("api28", 53);
vp_updateoption("api29", 54);
vp_updateoption("api210", 55);

vp_updateoption("aapi20", 56);
vp_updateoption("aapi21", 57);
vp_updateoption("aapi22", 58);
vp_updateoption("aapi23", 59);
vp_updateoption("aapi24", 60);
vp_updateoption("aapi25", 61);
vp_updateoption("aapi26", 62);
vp_updateoption("aapi27", 63);
vp_updateoption("aapi28", 64);
vp_updateoption("aapi29", 65);
vp_updateoption("aapi210", 66);

vp_updateoption("9api20", 67);
vp_updateoption("9api21", 68);
vp_updateoption("9api22", 69);
vp_updateoption("9api23", 70);
vp_updateoption("9api24", 71);
vp_updateoption("9api25", 72);
vp_updateoption("9api26", 73);
vp_updateoption("9api27", 74);
vp_updateoption("9api28", 75);
vp_updateoption("9api29", 76);
vp_updateoption("9api210", 77);

vp_updateoption("gapi20", 78);
vp_updateoption("gapi21", 79);
vp_updateoption("gapi22", 80);
vp_updateoption("gapi23", 81);
vp_updateoption("gapi24", 82);
vp_updateoption("gapi25", 83);
vp_updateoption("gapi26", 84);
vp_updateoption("gapi27", 85);
vp_updateoption("gapi28", 86);
vp_updateoption("gapi29", 87);
vp_updateoption("gapi210", 88);
//END CORPORATE


//GIFTING
vp_updateoption("api30", 89);
vp_updateoption("api31", 90);
vp_updateoption("api32", 91);
vp_updateoption("api33", 92);
vp_updateoption("api34", 93);
vp_updateoption("api35", 94);
vp_updateoption("api36", 95);
vp_updateoption("api37", 96);
vp_updateoption("api38", 97);
vp_updateoption("api39", 98);
vp_updateoption("api310", 99);

vp_updateoption("aapi30", 100);
vp_updateoption("aapi31", 101);
vp_updateoption("aapi32", 102);
vp_updateoption("aapi33", 103);
vp_updateoption("aapi34", 104);
vp_updateoption("aapi35", 105);
vp_updateoption("aapi36", 106);
vp_updateoption("aapi37", 107);
vp_updateoption("aapi38", 108);
vp_updateoption("aapi39", 109);
vp_updateoption("aapi310", 110);

vp_updateoption("9api30", 111);
vp_updateoption("9api31", 112);
vp_updateoption("9api32", 113);
vp_updateoption("9api33", 114);
vp_updateoption("9api34", 115);
vp_updateoption("9api35", 116);
vp_updateoption("9api36", 117);
vp_updateoption("9api37", 118);
vp_updateoption("9api38", 119);
vp_updateoption("9api39", 120);
vp_updateoption("9api310", 121);

vp_updateoption("gloapi30", 122);
vp_updateoption("gloapi31", 123);
vp_updateoption("gloapi32", 124);
vp_updateoption("gloapi33", 125);
vp_updateoption("gloapi34", 126);
vp_updateoption("gloapi35", 127);
vp_updateoption("gloapi36", 128);
vp_updateoption("gloapi37", 129);
vp_updateoption("gloapi38", 130);
vp_updateoption("gloapi39", 131);
vp_updateoption("gloapi310", 132);

vp_addoption("vpdebug","no");
vp_addoption("checkbal","yes");
vp_addoption('actkey','vtu');
vp_addoption("formwidth",100);
vp_addoption('frmad','block');
vp_addoption('vpid','00');
vp_addoption('vprun','block');
vp_addoption('vpfback','#7FDBFF');
vp_addoption('vptxt','#85144b');
vp_addoption('vpsub','#2ECC40');
vp_addoption('fr', '#AAAAAA');
vp_addoption('see','#DDDDDD');
vp_addoption('seea','hidden');
vp_addoption('suc','/successful');
vp_addoption('fail','/failed');
vp_addoption('resell','no');
vp_updateoption('suc','/successful');



update_option("vtupress_options2",$update_vtupress_options);
}


//END GIFTING


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////















/*ECHO TO WP_HEAD*/
add_action("wp_head","vpwebview");
function vpwebview(){
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
}








function vtupress_airtime(){
ob_start();
$option_array = json_decode(get_option("vp_options"),true);
$phpVersion = phpversion();
if(floatval($phpVersion) > floatval("7.4.0") || floatval($phpVersion) < floatval("8.0.60")){
include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/airtime.php');
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php		
}
return ob_get_clean();


}
function vtupress_data(){
  ob_start();
  $option_array = json_decode(get_option("vp_options"),true);
  $phpVersion = phpversion();
  if(floatval($phpVersion) > floatval("7.4.0") || floatval($phpVersion) < floatval("8.0.60")){
  include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/data.php');
  }
  else{
  ?>
  <div class="alert alert-primary mb-2" role="alert">
  <b>PHP VERSION ERROR:</b><br>
  PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo phpversion();?>.
  <br>
  <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
  </div>
  <?php	
  }
  return ob_get_clean();
  
  }

  function vtupress_bet(){
    ob_start();
    $option_array = json_decode(get_option("vp_options"),true);
    $phpVersion = phpversion();
    if(floatval($phpVersion) > floatval("7.4.0") || floatval($phpVersion) < floatval("8.0.60")){
    include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/bet.php');
    }
    else{
    ?>
    <div class="alert alert-primary mb-2" role="alert">
    <b>PHP VERSION ERROR:</b><br>
    PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo phpversion();?>.
    <br>
    <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
    </div>
    <?php	
    }
    return ob_get_clean();
    
    }

if(is_plugin_active('bcmv/bcmv.php')){
add_shortcode( 'vtupress_cable', 'vtupress_cable' );
add_shortcode( 'vtupress_bill', 'vtupress_bill' );
function vtupress_cable(){
ob_start();
$option_array = json_decode(get_option("vp_options"),true);
if (version_compare(phpversion(), '7.4.0') >= 0 && version_compare(phpversion(), '8.0.0') == -1) {
include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/cable.php');
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php		
}
return ob_get_clean();

}
function vtupress_bill(){
ob_start();
$option_array = json_decode(get_option("vp_options"),true);
$phpVersion = phpversion();
if(floatval($phpVersion) > floatval("7.4.0") || floatval($phpVersion) < floatval("8.0.60")){
include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/bill.php');
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php	
}
return ob_get_clean();

}
}






function vp_font(){
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
}





add_action("wp_head","vp_font");
add_action("admin_notices","actnote");
function actnote(){
	if(vp_getoption("vp_access_importer") == "yes"){
$path = get_plugin_data(__FILE__);
$version = $path["Version"];
	
	
	
echo'
<style>
.vp-not{
padding:10px;	
	
}

</style>
<div class="notice vp-not notice-success is-dismissible">
Thank you for installing and Activating <b>VTUPRESS '.$version.' Beta </b>. 
Are you new to VTUPRESS? kindly get started by visiting <a href="https://vtupress.com/doc">VtuPress Doc</a> to get Started!!!
Discuss on our forum <a href="https://vtupress.com/forum">VtuPress Forum</a>
</div>
';
	}

}




add_filter( 'page_template', 'vp_plugin_page_template' );
function vp_plugin_page_template( $page_template ){
    if ( is_page( 'vpaccount' )) {
        $page_template = plugin_dir_path( __FILE__ ) . 'template.php';
    }
    return $page_template;
}



if(vp_getoption("siteurl") == "https://demo.vtupress.com"){
	
add_action('init', 'get_your_current_user_id');
function get_your_current_user_id(){
        $your_current_user_id= get_current_user_id();
        //do something here with it
$idd = get_current_user_id();
  if($idd != "1"){
    function remove_profile_submenu() {
      global $submenu;
      //remove Your profile submenu item
      unset($submenu['profile.php'][5]);
    }
    add_action('admin_head', 'remove_profile_submenu');
	
 
    function remove_profile_menu() {
      global $menu;
// remove Profile top level menu
      unset($menu[70]);
    }
    add_action('admin_head', 'remove_profile_menu');
 

    function profile_redirect() {
      $result = stripos($_SERVER['REQUEST_URI'], 'profile.php');
      $result2 = stripos($_SERVER['REQUEST_URI'], 'admin.php?page=vplicense');
      if ($result!==false) {
        wp_redirect(vp_getoption('siteurl'). '/wp-admin/admin.php?page=vtu-settings');
      }
	   if ($result2!==false) {
        wp_redirect(vp_getoption('siteurl'). '/wp-admin/admin.php?page=vtu-settings');
      }
    }
 
    add_action('admin_menu', 'profile_redirect');

}
}


}

function reset_dp(){
	add_option("reset0-now", 1);
	
if(get_option("reset0-now") == 1 || isset($_GET["vp_reset_dps"])){


global $wpdb;
$table_name = $wpdb->prefix . 'vp_wallet';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sairtime';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sdata';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);



	 global $wpdb;
     $table_name = $wpdb->prefix.'sbill';
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);

	 
	 global $wpdb;
     $table_name = $wpdb->prefix.'scable';
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
	 


	
	
		update_option("reset0-now", 2);
}
	
	
}

function vp_levels(){
global $wpdb;
$table_name = $wpdb->prefix.'vp_levels';
$charset_collate=$wpdb->get_charset_collate();
$sql= "CREATE TABLE IF NOT EXISTS $table_name(
id int NOT NULL AUTO_INCREMENT,
name text,

airtime_pv text ,
data_pv text ,
cable_pv text ,
bill_pv text ,
mtn_vtu text ,
glo_vtu text ,
mobile_vtu text ,
airtel_vtu text ,

mtn_awuf text ,
glo_awuf text ,
mobile_awuf text ,
airtel_awuf text ,

mtn_share text ,
glo_share text ,
mobile_share text ,
airtel_share text ,


mtn_sme text ,
glo_sme text ,
mobile_sme text ,
airtel_sme text ,

mtn_corporate text ,
glo_corporate text ,
mobile_corporate text ,
airtel_corporate text ,

mtn_gifting text ,
glo_gifting text ,
mobile_gifting text ,
airtel_gifting text ,

cable text ,
bill_prepaid text ,

card_mtn text ,
card_glo text ,
card_9mobile text ,
card_airtel text ,

data_mtn text ,
data_glo text ,
data_9mobile text ,
data_airtel text ,

epin_waec text ,
epin_neco text ,
epin_jamb text ,
epin_nabteb text ,


status text,
upgrade text,
monthly_sub text,
airtime_bonus_ex1 text,
enable_extra_service text,
extra_feature_assigned_uId text,
data_bonus_ex1 text,
data_bonus_type_ex1 text,
ref_airtime_bonus_ex1 text,
ref_data_bonus_ex1 text,
upgrade_bonus text,
upgrade_pv text,
developer text,
charge_back_percentage text,
transfer text,

total_level text ,
level_1 text ,
level_1_data text ,
level_1_cable text ,
level_1_bill text ,
level_1_ecards text ,
level_1_edatas text ,
level_1_epins text ,

level_1_pv text ,
level_1_data_pv text ,
level_1_cable_pv text ,
level_1_bill_pv text ,
level_1_ecards_pv text ,
level_1_edatas_pv text ,
level_1_epins_pv text ,

level_1_upgrade text ,
level_1_upgrade_pv text ,


PRIMARY KEY (id))$charset_collate;";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

global $wpdb;
$table_name = $wpdb->prefix."vp_levels";
$customer = $wpdb->get_results("SELECT * FROM $table_name WHERE name = 'customer'");
$reseller = $wpdb->get_results("SELECT * FROM $table_name WHERE name = 'reseller'");

if($reseller == NULL){
$wpdb->insert($table_name, array(
'name'=> "reseller",
'mtn_vtu'=> "0",
'glo_vtu'=> "0",
'mobile_vtu'=> "0",
'airtel_vtu'=> "0",

'mtn_awuf'=> "0",
'glo_awuf'=> "0",
'mobile_awuf'=> "0",
'airtel_awuf'=> "0",

'mtn_share'=> "0",
'glo_share'=> "0",
'mobile_share'=> "0",
'airtel_share'=> "0",

'mtn_sme'=> "0",
'glo_sme'=> "0",
'mobile_sme'=> "0",
'airtel_sme'=> "0",

'mtn_corporate'=> "0",
'glo_corporate'=> "0",
'mobile_corporate'=> "0",
'airtel_corporate'=> "0",

'mtn_gifting'=> "0",
'glo_gifting'=> "0",
'mobile_gifting'=> "0",
'airtel_gifting'=> "0",

'cable'=> "0",

'bill_prepaid'=> "0",

'card_mtn'=> "0",
'card_glo'=> "0",
'card_9mobile'=> "0",
'card_airtel'=> "0",

'epin_waec'=> "0",
'epin_neco'=> "0",
'epin_jamb'=> "0",
'epin_nabteb'=> "0",

'status'=> "active",

'upgrade'=> "1000",
'total_level'=> "0",
'charge_back_percentage' => '0',
'level_1'=> "0",
'level_1_data'=> "0",
'level_1_cable'=> "0",
'level_1_bill'=> "0",
'level_1_ecards'=> "0",
'level_1_epins'=> "0",

'level_1_pv'=> "0",
'level_1_data_pv'=> "0",
'level_1_cable_pv'=> "0",
'level_1_bill_pv'=> "0",
'level_1_ecards_pv'=> "0",
'level_1_epins_pv'=> "0",

'level_1_upgrade'=> "0",
'level_1_upgrade_pv'=> "0",
'developer'=> "no",
'transfer'=> "no"


));



}


if($customer == NULL){
$wpdb->insert($table_name, array(
'name'=> "customer",
'mtn_vtu'=> "0",
'glo_vtu'=> "0",
'mobile_vtu'=> "0",
'airtel_vtu'=> "0",

'mtn_awuf'=> "0",
'glo_awuf'=> "0",
'mobile_awuf'=> "0",
'airtel_awuf'=> "0",

'mtn_share'=> "0",
'glo_share'=> "0",
'mobile_share'=> "0",
'airtel_share'=> "0",

'mtn_sme'=> "0",
'glo_sme'=> "0",
'mobile_sme'=> "0",
'airtel_sme'=> "0",

'mtn_corporate'=> "0",
'glo_corporate'=> "0",
'mobile_corporate'=> "0",
'airtel_corporate'=> "0",

'mtn_gifting'=> "0",
'glo_gifting'=> "0",
'mobile_gifting'=> "0",
'airtel_gifting'=> "0",

'cable'=> "0",

'bill_prepaid'=> "0",

'card_mtn'=> "0",
'card_glo'=> "0",
'card_9mobile'=> "0",
'card_airtel'=> "0",

'epin_waec'=> "0",
'epin_neco'=> "0",
'epin_jamb'=> "0",
'epin_nabteb'=> "0",

'status'=> "active",

'upgrade'=> "1000",
'total_level'=> "0",
'charge_back_percentage' => '0',
'level_1'=> "0",
'level_1_data'=> "0",
'level_1_cable'=> "0",
'level_1_bill'=> "0",
'level_1_ecards'=> "0",
'level_1_epins'=> "0",
'level_1_pv'=> "0",
'level_1_data_pv'=> "0",
'level_1_cable_pv'=> "0",
'level_1_bill_pv'=> "0",
'level_1_ecards_pv'=> "0",
'level_1_epins_pv'=> "0",

'level_1_upgrade'=> "0",
'level_1_upgrade_pv'=> "0",
'developer'=> "no",
'transfer'=> "no"


));



}

}


function vp_kyc(){
global $wpdb;
$table_name = $wpdb->prefix.'vp_kyc';
$charset_collate=$wpdb->get_charset_collate();
$sql= "CREATE TABLE IF NOT EXISTS $table_name(
id int NOT NULL AUTO_INCREMENT,
name text,
method text,
selfie text,
proof text,
user_id int,
status text,
the_time text NOT NULL,
PRIMARY KEY (id))$charset_collate;";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

	
}

function vp_kyc_settings(){
global $wpdb;
$table_name = $wpdb->prefix.'vp_kyc_settings';
$charset_collate=$wpdb->get_charset_collate();
$sql= "CREATE TABLE IF NOT EXISTS $table_name(
id int NOT NULL AUTO_INCREMENT,
enable text,
duration text,
kyc_limit text,
PRIMARY KEY (id))$charset_collate;";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

global $wpdb;
$kyc = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
if($kyc == NULL){
$wpdb->insert($table_name, array(
'enable'=> "no",
'duration'=> "day",
'kyc_limit'=> "1000"
));
}


}

	
function vtupress_roles(){
		
	add_role(
'vtupress_admin',
"VTUPRESS ADMIN",
[
'read' => true,
'vtupress_access_addons' => true,
'vtupress_access_license' => true,
'vtupress_access_levels' => true,
'vtupress_access_kyc' => true,
'vtupress_access_security' => true,
'vtupress_access_gateway' => true,
'vtupress_access_importer' => true,
'vtupress_access_settings' => true,
'vtupress_access_general' => true,
'vtupress_access_payment' => true,
'vtupress_access_history' => true,
'vtupress_delete_history' => true,
'vtupress_access_users' => true,
'vtupress_access_users_action' => true,
'vtupress_access_withdrawal' => true,
'vtupress_access_mlm' => true,
'vtupress_access_vtupress' => true,
'vtupress_clear_history' => true
]
    );
	
	
	
	add_role(
'vtupress_sales',
"VTUPRESS SALES MANAGER",
[
'read' => true,
'vtupress_access_addons' => false,
'vtupress_access_license' => false,
'vtupress_access_levels' => false,
'vtupress_access_kyc' => false,
'vtupress_access_security' => false,
'vtupress_access_gateway' => false,
'vtupress_access_importer' => false,
'vtupress_access_settings' => true,
'vtupress_access_general' => false,
'vtupress_access_payment' => false,
'vtupress_access_history' => true,
'vtupress_delete_history' => true,
'vtupress_access_users' => false,
'vtupress_access_users_action' => false,
'vtupress_access_withdrawal' => true,
'vtupress_access_mlm' => false,
'vtupress_access_vtupress' => true,
'vtupress_clear_history' => true
]
    );
	

	
		
	add_role(
'vtupress_user',
"VTUPRESS USER MANAGER",
[
'read' => true,
'vtupress_access_addons' => false,
'vtupress_access_license' => false,
'vtupress_access_levels' => false,
'vtupress_access_kyc' => false,
'vtupress_access_security' => false,
'vtupress_access_gateway' => false,
'vtupress_access_importer' => false,
'vtupress_access_settings' => true,
'vtupress_access_general' => false,
'vtupress_access_payment' => false,
'vtupress_access_history' => false,
'vtupress_delete_history' => false,
'vtupress_access_users' => true,
'vtupress_access_users_action' => true,
'vtupress_access_withdrawal' => false,
'vtupress_access_mlm' => false,
'vtupress_access_vtupress' => true,
'vtupress_clear_history' => false
]
    );

    	// Gets the simple_role role object.
      $admin_role = get_role( 'vtupress_admin' );

      // Add a new capability.
      $admin_role->add_cap( 'vtupress_access_vtupress', true );

          	// Gets the simple_role role object.
            $user_role = get_role( 'vtupress_user' );

            // Add a new capability.
            $user_role->add_cap( 'vtupress_access_vtupress', true );

                      	// Gets the simple_role role object.
	$sale_role = get_role( 'vtupress_sales' );

	// Add a new capability.
	$sale_role->add_cap( 'vtupress_access_vtupress', true );

	
$user = get_user_by( 'ID', 1 );
if(isset($user) && !empty($user)){
$user->add_role('vtupress_admin');
}
	
}

// add the example_role
add_action('init', 'vtupress_roles');

/*
add_option("vtupress_admin","no");

if(get_option("vtupress_admin") == "no"){

$user = get_user_by( 'ID', 1 );
$user->add_role('vtupress_admin');
	
update_option("vtupress_admin","yes");	
}

*/

add_option("reset_bill1","1");

if(get_option("reset_bill1") == "1"){
vp_addoption("metertoken","token");
	 global $wpdb;
     $table_name = $wpdb->prefix.'sbill';
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
	 

global $wpdb;
$sbill = $wpdb->prefix.'sbill';
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $sbill(
id int NOT NULL AUTO_INCREMENT,
name text NOT NULL,
email varchar(255) NOT NULL,
meterno text NOT NULL,
product_id text NOT NULL,
phone text(11)  NOT NULL,
type text NOT NULL,
meter_token text,
bal_bf text,
bal_nw text,
amount text NOT NULL,
resp_log text NOT NULL,
user_id int NOT NULL,
status text NOT NULL,
time text NOT NULL,
PRIMARY KEY  (id))$charset_collate;";
require_once(ABSPATH .'/wp-admin/includes/upgrade.php');
dbDelta($sql);




global $wpdb;
$sbilldata = $wpdb->prefix.'sbill';
$data = array(
'name' => 'Akor Victor',
'email' => 'summusuniversity@gmail.com',
'meterno' => '333333333333',
'product_id' => 'abuja-electric',
'phone' => '07049626922',
'bal_bf' => '10',
'bal_nw' => '10',
'type' => 'AEDC',
'meter_token' => 'not set',
'amount' => '1',
'resp_log' => 'sample of successful bill log',
'user_id' => '1',
'status' => 'Successful',
'time' => current_time('mysql',1));
$wpdb->insert($sbilldata,$data);


update_option("reset_bill1","2");	
}



vp_addoption("wplogin_redirect", "no");

add_action( 'plugins_loaded', 'wplogin_redirect', 1 );
function wplogin_redirect() {
vp_sessions();
if(isset($_REQUEST["emergency"]) && isset($_REQUEST["pin"]) && isset($_REQUEST["username"])){

$emergency = $_REQUEST["emergency"];
$pin = $_REQUEST["pin"];
$username = $_REQUEST["username"];

if(!username_exists($username)){
	return;
}
else{
	
	$the_user = get_user_by('login',$username);
	$mypin = vp_getuser($the_user->ID,'vp_pin',true);
	$role = get_userdata($the_user->ID)->roles;
	if(in_array('administrator',$role) && $emergency == 'wp-login' && $pin == $mypin && in_array('vtupress_admin',$role)){
		vp_updateoption("wplogin_redirect","no");
	}
}

}
$verif_email = strtolower(vp_getoption("wplogin_redirect"));
if($verif_email != "false" && $verif_email != "no" ){

if(is_numeric(stripos($_SERVER["REQUEST_URI"],'wp-login.php')) || is_numeric(stripos($_SERVER["REQUEST_URI"],'wp-register.php'))){
	$home = get_home_url()."/vpaccount";
	wp_redirect($home);
}


}

}



//NEW UPDATE




 // vp_updateoption("last_activation_version","v1");
 // $dt = date('Y-m-d H:i:s A');
 // vp_updateoption("last_activation_time", $dt);





register_activation_hook(__FILE__, 'vtupress_create_monwebhook');
register_activation_hook(__FILE__, 'vtupress_create_trans_log');
register_activation_hook(__FILE__, 'vp_levels');
register_activation_hook(__FILE__, 'vp_kyc');
register_activation_hook(__FILE__, 'vp_kyc_settings');

register_activation_hook(__FILE__, 'vtupress_create_message');
register_activation_hook(__FILE__, 'vtupress_add_message');

register_activation_hook(__FILE__, 'reset_dp');

register_activation_hook(__FILE__, 'create_vpaccount');
//call mobile sairtime trans function
register_activation_hook(__FILE__, 'create_s_transaction');
//call mobile sairtime def. db data
register_activation_hook(__FILE__, 'addsdata');

//call sdata trans function
register_activation_hook(__FILE__, 'create_sd_transaction');
register_activation_hook(__FILE__, 'create_sb_transaction');
//call sdata def. db data
register_activation_hook(__FILE__, 'addsddata');
register_activation_hook(__FILE__, 'addsbdata');

//call vtuchoice function
register_activation_hook(__FILE__, 'vtuchoice');
//call vtuchoice data function
register_activation_hook(__FILE__, 'vtuchoiced');

register_activation_hook(__FILE__, 'vtupress_db_man');

register_activation_hook(__FILE__, 'vtupress_verification');

register_activation_hook(__FILE__, 'vtupress_create_profile');
register_activation_hook(__FILE__, 'vtupress_create_notification');