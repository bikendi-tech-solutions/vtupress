<?php
die();
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

  global $wpdb;


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
    $query = "WHERE time > '$toDate'";
  }else{
    $query = "WHERE time BETWEEN '$fromDate' AND '$toDate'";
  }
  
  //bill_Table

  $bill_table = $wpdb->prefix."sbill";

  $sql_query = "SELECT SUM(amount) as amount FROM $bill_table $query AND status = 'Successful'";

  $bill_balance = intval($wpdb->get_var($sql_query));

  echo $bill_balance;