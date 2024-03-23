<?php
header("Access-Control-Allow-Origin: 'self'");
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

  		$option_array = json_decode(get_option("vp_options"),true);


  global $wpdb;


if(!is_user_logged_in()){
    die("Please Login");
}
elseif(!current_user_can("vtupress_admin")){
    die("Not Allowed");
}

  
if(!isset($_GET["spraycode"])){
  die("NO SPRAY CODE");
}
$spray_code = trim(htmlspecialchars($_GET["spraycode"]));
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




  if(isset($_GET["fromDate"])){
    $fromDate = $_GET["fromDate"];
  }
  else{
    $fromDate = date("Y-m-d");
  }

  if(isset($_GET["toDate"])){
    $toDate = $_GET["toDate"];
  }
  else{
    $toDate = date("Y-m-d");
  }

  if($fromDate == $toDate){
    $query = "WHERE the_time > '$toDate' AND email != 'summusuniversity@gmail.com' AND amount > 1";
  }else{
    $query = "WHERE the_time BETWEEN '$fromDate' AND '$toDate' AND email != 'summusuniversity@gmail.com' AND amount > 1";
  }
  

  $earn_balance =  0;

  //Airtime_Table

  $airtime_table = $wpdb->prefix."sairtime";

  $sql_query = "SELECT SUM(amount) as amount FROM $airtime_table $query AND status = 'Successful'";

  $airtime_balance = intval($wpdb->get_var($sql_query));

  $earn_balance +=  $airtime_balance;
//  echo $airtime_balance;


  $data_table = $wpdb->prefix."sdata";

  $sql_query = "SELECT SUM(amount) as amount FROM $data_table $query AND status = 'Successful'";

  $data_balance = intval($wpdb->get_var($sql_query));
  $earn_balance +=  $data_balance;
 // echo $data_balance;





 #---------------------SMS
 if(is_plugin_active("vpsms/vpsms.php")){

 $sms_table = $wpdb->prefix."ssms";

$sql_query = "SELECT SUM(amount) as amount FROM $sms_table $query AND status = 'Successful'";

$sms_balance = intval($wpdb->get_var($sql_query));

$earn_balance +=  $sms_balance;

$sms_available = true;
 }else{
    $sms_balance = "-";
    $sms_available = false;
 }
// echo $bill_balance;




 #---------------------SCARDS

 if(is_plugin_active("vpcards/vpcards.php")){
 $scards_table = $wpdb->prefix."scards";

$sql_query = "SELECT SUM(amount) as amount FROM $scards_table $query AND status = 'Successful'";

$scards_balance = intval($wpdb->get_var($sql_query));
$earn_balance +=  $scards_balance;
$scards_available = true;
 }
 else{
    $scards_balance = "-";
    $scards_available = false;
 }
// echo $bill_balance;



 #---------------------SDATACARD

 if(is_plugin_active("vpdatas/vpdatas.php")){

 $sdatacards_table = $wpdb->prefix."sdatacard";

$sql_query = "SELECT SUM(amount) as amount FROM  $sdatacards_table $query AND status = 'Successful'";

$sdatacards_balance = intval($wpdb->get_var($sql_query));
$earn_balance +=  $sdatacards_balance;
$sdatacards_available = true;
 }
 else{
    $sdatacards_balance = "-";
    $sdatacards_available = false;
 }
// echo $bill_balance;

 #---------------------SEPINS
 if(is_plugin_active("vpepin/vpepin.php")){

 $sepins_table = $wpdb->prefix."sepins";

$sql_query = "SELECT SUM(amount) as amount FROM $sepins_table $query AND status = 'Successful'";

$sepins_balance = intval($wpdb->get_var($sql_query));
$earn_balance +=  $sepins_balance;
$sepins_available = true;
 }
 else{
    $sepins_balance = "-";
    $sepins_available = false;
 }
// echo $bill_balance;




 if($fromDate == $toDate){
    $query = "WHERE time > '$toDate' AND email != 'summusuniversity@gmail.com' AND amount > 1";
  }else{
    $query = "WHERE time BETWEEN '$fromDate' AND '$toDate' AND email != 'summusuniversity@gmail.com' AND amount > 1";
  }
  if(is_plugin_active("bcmv/bcmv.php")){


  $cable_table = $wpdb->prefix."scable";

  $sql_query = "SELECT SUM(amount) as amount FROM $cable_table $query AND status = 'Successful'";

  $cable_balance = intval($wpdb->get_var($sql_query));

  $earn_balance +=  $cable_balance;
 // echo $cable_balance;

 $bill_table = $wpdb->prefix."sbill";

 $sql_query = "SELECT SUM(amount) as amount FROM $bill_table $query AND status = 'Successful'";

 $bill_balance = intval($wpdb->get_var($sql_query));
 $earn_balance +=  $bill_balance;

 $cable_available = true;
 $bill_available = true;

  }
  else{

    $cable_balance = "-";
    $bill_balance = "-";
 $cable_available = false;
 $bill_available = false;

  }

// echo $bill_balance;







 $obj = new stdClass();
 $obj->airtime = "₦".number_format(floatval($airtime_balance));
 $obj->airtime_check = vp_option_array($option_array,"setairtime");
 $obj->data = "₦".number_format(floatval($data_balance));
 $obj->data_check = vp_option_array($option_array,"setdata");
 $obj->cable = "₦".number_format(floatval($cable_balance));
 $obj->cable_check = vp_option_array($option_array,"setcable");
 $obj->cable_available = $cable_available;
 $obj->bill = "₦".number_format(floatval($bill_balance));
 $obj->bill_check = vp_option_array($option_array,"setbill");
 $obj->bill_available = $bill_available;
 $obj->recharge = "₦".number_format(floatval($scards_balance));
 $obj->recharge_check = vp_option_array($option_array,"cardscontrol");
 $obj->recharge_available = $scards_available;
 $obj->datacard = "₦".number_format(floatval($sdatacards_balance));
 $obj->datacard_check = vp_option_array($option_array,"datascontrol");
 $obj->datacard_available = $sdatacards_available;
 $obj->epin = "₦".number_format(floatval($sepins_balance));
 $obj->epin_check = vp_option_array($option_array,"epinscontrol");
 $obj->epin_available = $sepins_available;
 $obj->sms = "₦".number_format(floatval($sms_balance));
 $obj->sms_check = vp_option_array($option_array,"smscontrol");
 $obj->sms_available = $sms_available;
 $obj->earn = "₦".number_format(floatval($earn_balance));

 echo json_encode($obj);