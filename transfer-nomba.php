<?php
if(!defined('ABSPATH')){
  die("Not Allowed");
}

$action = $_POST["action"];


$id = get_current_user_id();




$admin_first = vp_getuser($id,"first_name",true);
$admin_last = vp_getuser($id,"last_name",true);


$secret_key = vp_getoption("nomba_secretkey");
$client_id = vp_getoption("nomba_businessid");
$account_id = vp_getoption("nomba_apikey");



$admin_name = $admin_first." ".$admin_last;

$accountNo = trim($_POST["account_number"]);
$bank_code = trim($_POST["bank_code"]);
$currency = "NGN";
$amount = intval(str_replace("-","",trim($_POST["amount"])));
$current_balance = intval(vp_getuser($id, 'vp_bal', true));


$get_details = false;

if(isset($_POST["get_details"])):
    $get_details = true;
endif;

//get token
$payload =  [
    "grant_type" => "client_credentials",
    "client_id" => $client_id,
    "client_secret"=> $secret_key
];


$url = "https://api.nomba.com/v1/auth/token/issue";
$curl = curl_init();

curl_setopt_array($curl, [
CURLOPT_URL =>  $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>json_encode($payload,JSON_UNESCAPED_SLASHES),
CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $secret_key",
    "accountId: $account_id",
    "Content-Type: application/json"
],
]);

$res = curl_exec($curl);
$response = json_decode($res,true); 
$err = curl_error($curl);

curl_close($curl);

if ($err) {

$return_account = new stdClass;
$return_account->status = 'failed';
$return_account->message = $err;

$msg = json_encode($return_account);
error_log($msg);
die($msg);

} else {

    if(!isset($response["data"]["access_token"])){
        $return_account = new stdClass;
        $return_account->status = 'failed';
        $return_account->message = $res;

        $msg = json_encode($return_account);
        error_log($msg);
        die($msg);
    }else{
        $token = $response["data"]["access_token"];
    }
}




$secret_key = $token;
//lookup

$url  = "https://api.nomba.com/v1/transfers/bank/lookup";
  $fields = [
    'accountNumber' => $accountNo,
    'bankCode' => $bank_code
  ];
  
  
    //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $secret_key",
    "accountId: $account_id",
    "Content-Type: application/json",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);

$json = json_decode($result,true);



if(!isset($json["description"]) || !isset($json["data"]["accountName"])):
    die($result);
else:
    if(strtolower($json["description"]) != "success"){
        die($result);
    }
endif;
  
if(isset($json["data"]["accountName"])):
  $name = $json["data"]["accountName"];
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


  if($amount < 9999 ){
    $charge = 20;
  }
  else{
    $charge = 50;
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


  
$url  = "https://api.nomba.com/v1/transfers/bank";
$fields = [
  'narration' => "Money Transfer From Trade",
  'amount' => $amount,
  'merchantTxRef' => uniqid(),
  'bankCode' => $bank_code,
  'accountNumber' => $accountNo,
  'accountName' => $name,
  "senderName" => $admin_name
];

 $fields_string = json_encode($fields);

  //open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $secret_key",
    "accountId: $account_id",
    "Content-Type: application/json",
    "Cache-Control: no-cache",
  ));

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

//execute post
$result = curl_exec($ch);

$json = json_decode($result,true);
  
if(isset($json["description"])):
  $status = strtolower($json["description"]);
  if($status != "success"):
      die($status);
  endif;
else:
  error_log("running transfer error .. ".$result);
  die($result);
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
 'description'=> "Bank Transfer $chag",
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