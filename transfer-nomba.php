<?php
if(!defined('ABSPATH')){
  die("Not Allowed");
}


$nomba = ["090645"=>"Nombank","120001"=>"9PSB","090270"=>"AB Microfinance Bank","418"=>"AG Mortgage Bank","090180"=>"AMJU Microfinance Bank","090001"=>"ASO Savings & Loans","070010"=>"Abbey Mortgage Bank","820"=>"Abucoop MFB","044"=>"Access Bank","100052"=>"Access Yellow","090134"=>"Accion Microfinance Bank","090160"=>"Addosser Microfinance Bank","347"=>"Aella MFB","120004"=>"Airtel Smartcash PSB","100029"=>"Alternative Bank","090529"=>"Ampersand Microfinance Bank","090287"=>"Asset Matrix Microfinance Bank","090264"=>"Auchi Microfinance Bank","895"=>"Avuenegbe MFB","110072"=>"Bank 78","090136"=>"Baobab Microfinance Bank","348"=>"Bestar MFB","50931"=>"Bowen Microfinance Bank","050006"=>"Branch International Financial Services","983"=>"Bud Infrastructure","956"=>"Capricon Digital","100026"=>"Carbon","748"=>"Cash Connect MFB","090490"=>"Chukwunenye Microfinance Bank","023"=>"Citibank Nigeria Limited","100032"=>"Contec Global Infotech Limited","766"=>"Core Step MFB","346"=>"Creditville MFB","453"=>"Crust MFB","50159"=>"DavoDani MFB","090391"=>"Davodani Microfinance Bank","063"=>"Diamond Bank","090470"=>"Dot MFB","999999"=>"E-Settlement Ltd.","050"=>"Ecobank Nigeria","090097"=>"Ekondo Microfinance Bank","090539"=>"Enrich Microfinance Bank","084"=>"Enterprise Bank","400001"=>"FSDH Merchant Bank","090551"=>"Fairmoney Microfinance Bank","070"=>"Fidelity Bank","608"=>"Finatrust MFB","011"=>"First Bank of Nigeria","214"=>"First City Monument Bank","309"=>"First Monnie Wallet","622"=>"Flutterwave","058"=>"GTBank","000027"=>"Globus Bank","090574"=>"Goldman MFB","090495"=>"Good News Microfinance Bank","090599"=>"Greenacres MFB","090195"=>"Grooming MFB","110059"=>"Habari Pay","090147"=>"Hackman Microfinance Bank","090291"=>"Hala Credit Microfinance Bank","030"=>"Heritage Bank","120002"=>"Hope Payment Service Bank","090118"=>"Ibile Microfinance Bank","090536"=>"Ikoyi Osun MFB","301"=>"Jaiz Bank","090602"=>"Kenechukwu Microfinance Bank","082"=>"Key Stone Bank","899"=>"Kolomoni MFB","321"=>"Konga Pay","090380"=>"Kredi Microfinance Bank","090267"=>"Kuda Microfinance Bank","090177"=>"Lapo Microfinance Bank","397"=>"LeadCity MFB","090420"=>"Letshego Microfinance Bank","000029"=>"Lotus Bank","090171"=>"Mainstreet Microfinance Bank","648"=>"Malachy MFB","090455"=>"Mkobo Microfinance Bank","120003"=>"Momo Payment Service Bank","090405"=>"Moniepoint Microfinance Bank","950"=>"Netapps Technology","090194"=>"Nirsal MFB","090645"=>"Nombank","090345"=>"OAU Microfinance Bank","090295"=>"Omiye MFB","327"=>"Paga","070008"=>"Page Financials","100033"=>"Palmpay","000030"=>"Parallex MF Bank","311"=>"Parkway Projects","329"=>"PayAttitude Online","305"=>"Paycom (Opay)","100039"=>"Paystack Titan","076"=>"Polaris Bank","000031"=>"Premium Trust Bank","090499"=>"Pristine Divitis Microfinance Bank","090503"=>"Projetcs Microfinance Bank","50739"=>"Prospa Capital MFB","101"=>"Providus Bank","090496"=>"Randalpha Microfinance Bank","090198"=>"RenMoney Microfinance Bank","090138"=>"Royal Exchange Microfinance Bank","649"=>"Rubies MFB","090286"=>"Safe Haven MFB","090502"=>"Shalom Microfinance Bank","942"=>"Smart Cash PSB","090325"=>"Sparkle","090436"=>"Spectrum MFB","039"=>"Stanbic IBTC Bank","068"=>"Standard Chartered Bank Nigeria","667"=>"Stellas MFB","232"=>"Sterling Bank Plc","100"=>"SunTrust Bank Nigeria Limited","000026"=>"Taj Bank","000025"=>"Titan Trust Bank","090251"=>"UNN Microfinance Bank","672"=>"Uda MFB","090193"=>"Unical MFB","032"=>"Union Bank of Nigeria","033"=>"United Bank for Africa","215"=>"Unity Bank","566"=>"VFD Microfinance Bank Limited","050020"=>"Vale Finance","035"=>"Wema Bank","148"=>"XPress MTS","738"=>"XPress Payments","391"=>"XPress Wallet","964"=>"Yello Digital Services","792"=>"ZWallet","057"=>"Zenith Bank","090504"=>"Zikora Microfinance Bank","306"=>"eTranzact"];



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
$wpdb->query('ROLLBACK');
die($msg);

} else {

    if(!isset($response["data"]["access_token"])){
        $return_account = new stdClass;
        $return_account->status = 'failed';
        $return_account->message = $res;

        $msg = json_encode($return_account);
        error_log($msg);
        $wpdb->query('ROLLBACK');
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
  $wpdb->query('ROLLBACK');
    die($result);
else:
    if(strtolower($json["description"]) != "success"){
      $wpdb->query('ROLLBACK');
        die($result);
    }
endif;
  
if(isset($json["data"]["accountName"])):
  $name = $json["data"]["accountName"];
  $bank_name = $nomba[$bank_code] ? $nomba[$bank_code] :"";
else:
  $name = "";
  $bank_code = "";
  $bank_name = $bank_code;
endif;


if(empty($name) || empty($bank_code)):
  $wpdb->query('ROLLBACK');
  die("Invalid bank details");
endif;

if ($get_details && !botAccess()):
  $wpdb->query('ROLLBACK');
  die($name);
elseif (botAccess() && $get_details):
  die(json_encode(['success' => true, 'name' => $name]));
endif;



  if($amount < 9999 ){
    $charge = 20;
  }
  else{
    $charge = 50;
  }


  $amountWithCharge = ($amount + $charge);

  if($current_balance < $amount):
    $wpdb->query('ROLLBACK');
    die("Insufficient balance [$current_balance]");
  elseif($amount < 100):
    $wpdb->query('ROLLBACK');
      die("Minimum transfer amount is 100");
  elseif($current_balance < $amountWithCharge):
    $wpdb->query('ROLLBACK');
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
    $wpdb->query('ROLLBACK');
      die($status);
  endif;
else:
  error_log("running transfer error .. ".$result);
  $wpdb->query('ROLLBACK');
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
      'amount' => $current_balance,
      'amount_now' => $updatedBalance,
      'status' => "success",
      'bank_details' => $name."-".$accountNo."-".$bank_name,
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
      'bank_details' => $name."-".$accountNo."-".$bank_name,
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


$wpdb->query('COMMIT');

if(botAccess()){
  die(json_encode($data));
}
die($return);