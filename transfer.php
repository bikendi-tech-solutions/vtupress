<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if(!is_user_logged_in()){
    die("Please login");
}

if(!isset($_POST["action"])){
    die("Invalid request");
}

$action = $_POST["action"];


$id = get_current_user_id();



if(vp_getoption('allow_to_bank') != "yes" || vp_getoption("vtupress_custom_transfer") != "yes"){
    die("Enable Bank To Bank transfer");
}

$secret_key = vp_getoption('psec'); //get_option('jettrade_paystack_secret');

$name = vp_getuser($userid, 'first_name')." ".vp_getuser($userid, 'last_name');
$accountNo = trim($_POST["account_number"]);
$bank_code = trim($_POST["bank_code"]);
$currency = "NGN";
$amount = intval(str_replace("-","",trim($_POST["amount"])));
$current_balance = intval(vp_getuser($id, 'vp_bal', true));


$get_details = false;

if(isset($_POST["get_details"])):
    $get_details = true;
endif;

//create recipient

$url  = "https://api.paystack.co/transferrecipient";
  $fields = [
    'type' => "nuban",
    'name' => $name,
    'account_number' => $accountNo,
    'bank_code' => $bank_code,
    'currency' => $currency
  ];
  
   $fields_string = http_build_query($fields);
  
    //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $secret_key",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);

$json = json_decode($result,true);



if(isset($json["data"]["recipient_code"])):
    $result = $json["data"]["recipient_code"];
else:
    error_log("create recipient error .. ".$result);
    if(isset($json["message"])):
        die($json["message"]);
    endif;
        die("101");
endif;
  
 
if(isset($json["data"]["details"]["account_name"])):
  $name = $json["data"]["details"]["account_name"];
  $bank_code = $json["data"]["details"]["bank_name"];
else:
  $name = "";
  $bank_code = "";
endif;

if(empty($name) || empty($bank_code)):
  die("Invalid bank details");
endif;

if($get_details):
    die($name);
endif;

if($amount < 50000){
  $charge = 70;
}
else{
  $charge = 100;
}

$amountWithCharge = ($amount + $charge);

if($current_balance < $amount):
  die("Insufficient balance [$current_balance]");
elseif($amount < 100):
    die("Minimum transfer amount is 100");
elseif($current_balance < $amountWithCharge):
  die("Insufficient balance to cover transfer fee [$charge] inclusively");
else:
//charge = 

  $updatedBalance = $current_balance - $amountWithCharge;


endif;

$status = "failed";
   
    //initiate transfer

$url  = "https://api.paystack.co/transfer";
  $fields = [
    'source' => "balance",
    'reason' => "Money Transfer From Trade",
    'amount' => $amount*100,
    'reference' => uniqid(),
    'recipient' => $result
  ];
  
   $fields_string = http_build_query($fields);
  
    //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $secret_key",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);

$json = json_decode($result,true);
    
if(isset($json["data"]["status"])):
    $status = $json["data"]["status"];
else:
    error_log("running transfer error .. ".$result);
    if(isset($json["message"])):
        die($json["message"]);
    endif;
    die("102");
endif;



if(preg_match("/succ/",$status) || preg_match("/receiv/",$status) || preg_match("/pend/",$status) || preg_match("/proc/",$status)):
    $return = "success";

    //$user_details["balance"] = $updatedBalance;
    //$wpdb->update( $user_table, $user_details, array( 'user_id' => $id ) );
    vp_updateuser($id,"vp_bal",$updatedBalance);

    $data = [
      'user_id' => $id,
      'amount' => $amount,
      'amount_before' => $current_balance,
      'amount_now' => $updatedBalance,
      'status' => "success",
      'bank_details' => $name."-".$accountNo."-".$bank_code,
    ];

    $wallet = [
      'user_id' => $id,
      'amount' => $amount,
      'amount_before' => $current_balance,
      'amount_now' => $updatedBalance,
      'type' => "debit"
    ];

  //  global $wpdb;
  //  $table_name = $wpdb->prefix. "jettrade_wallet_history";
   // $wpdb->insert( $table_name, $wallet );
   // insert to wallet


   $sta = "approved";
    $chag = "@ #$charge charge";
    //debit user
else:
    $return = "failed";

    $data = [
      'user_id' => $id,
      'amount' => $amount,
      'amount_before' => $current_balance,
      'amount_now' => $current_balance,
      'status' => "failed",
      'bank_details' => $name."-".$accountNo."-".$bank_code,
    ];

    $updatedBalance = $current_balance;
    $sta = "declined";
    $chag = " failed";

endif;

$userData = get_userdata($id);

// global $wpdb;
//   $table_name = $wpdb->prefix. "jettrade_withdrawals";

//   $wpdb->insert( $table_name, $data );


global $wpdb;
$table_name = $wpdb->prefix.'vp_wallet';
maybe_add_column($table_name,"bank","ALTER TABLE $table_name ADD bank text");
maybe_add_column($table_name,"charge","ALTER TABLE $table_name ADD charge text");

 $added_to_db = $wpdb->insert($table_name, array(
 'name'=> $userData->user_login,
 'type'=> "Transfer",
 'description'=> "Bank Transfer to [ $accountNo <=> $bank_code ] $chag",
 'fund_amount' => $amount,
 'before_amount' => $current_balance,
 'now_amount' =>  $updatedBalance,
 'user_id' => $id,
 'status' => $sta,
 'bank' => $data["bank_details"],
 'charge' => $charge,
 'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
 ));



die($return);