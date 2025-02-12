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

function harray_key_first($arr) {
	$arg = json_decode($arr);
	if(is_array($arg)){
		$response  = array("him"=>"me", "them"=>"you");
        foreach($response  as $key => $value) {
            if(!is_array($value)){
                return $arr[$key];
            }else{
                return "error";
            }
        }
		
	}else{
		return $arr;
	}
        
}



if (!function_exists('getallheaders')){
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}


function convertArrayKeysToLowerCase($array) {
    $result = array();
    
    foreach ($array as $key => $value) {
        $result[strtolower($key)] = is_array($value) ? convertArrayKeysToLowerCase($value) : $value;
    }
    
    return $result;
}

$_XERVER = convertArrayKeysToLowerCase($_SERVER);




$input = file_get_contents("php://input");
$payload = $input;

$event = json_decode(str_replace(" ","",$input));
$array = json_decode(str_replace(" ","",$input), true);

if(isset($_SERVER['REMOTE_ADDR'])){
    
    $server_ip = $_SERVER['REMOTE_ADDR'];

}else{

    $server_ip = "NULL";

}




$admine = get_bloginfo('admin_email');

$headers = array('Content-Type: text/html; charset=UTF-8');

function computeSHA512TransactionHash($stringifiedData, $clientSecret) {
    $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
    return $computedHash;
}

if(isset($event->event_type) && isset($event->settled_amount)){

    if(strtoupper($event->event_type) != "COLLECTION"){
        die("Not a successful tranaction - Ncwallet / Providus");
    }

    if(function_exists('getallheaders')){

        if(isset(getallheaders()["api-key"])){
            $signature = getallheaders()["api-key"];
        }
        elseif(isset(getallheaders()["api_key"])){

            $signature = getallheaders()["api_key"];
        }
        else{
        die("No Signature From Ncwallet");
        }

    }else{
        die("getallheaders() Not Running On Your Server. Contact US");  
    }

    $apikey = vp_getoption("ncwallet_apikey");
    $hashed = computeSHA512TransactionHash($input, $apikey);
    if($hashed != $signature){
        die("Signature Mismatch - Ncwallet Africa");
    }

    $userID = get_user_by("email",$array["email"])->ID;
    $userData = get_userdata($userID);

    $amount = $array["amount"];
    $processor = "ncwallet";    
    $session_id = $array["session_id"];
    $email =  $userData->user_email;
    $user_data = $userData;
    $userid = $userID;
    $ref = $session_id;
    $total_amount = $amount;

}
elseif (  array_key_exists('http_payvessel_http_signature', $_XERVER)) {


   // error_log("Yes payvessel",0);
    // CSRF exemption

    $payvessel_signature = $_XERVER['http_payvessel_http_signature'];
    //this line maybe be differ depends on your server
    //$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR']; 
    $ip_address = $_SERVER['REMOTE_ADDR']; 
    $secret = vp_getoption("payvessel_seckey");
    $hashkey = hash_hmac('sha512', $payload, $secret);

    if ($payvessel_signature == $hashkey && $ip_address == "162.246.254.36") {


        /*
        {
        "transaction":{"date":"---","reference":"---","sessionid":"---"},
            "order":{
            "currency":"NGN",
            "amount":500,
            "settlement_amount":470,
            "fee":30,
            "description":"---"
            },
            "customer":{
            "email":"----",
            "phone":"--"
            },
            "virtualAccount":{
            "virtualAccountNumber":"---",
            "virtualBank":"120001"
            },
            "sender":{
            "senderAccountNumber":"---",
            "SenderBankCode":"---",
            "senderBankName":"---",
            "senderName":"---"
            },"message":"---",
            "code":"00"
            }
        */
   // error_log("payvessel match",0);

        $data = json_decode($payload, true);
        $amount = floatval($data['order']['amount']);
        $settlementAmount = floatval($data['order']['settlement_amount']);
        $fee = floatval($data['order']['fee']);
        $reference = $data['transaction']['reference'];
        $description = $data['order']['description'];
        $settlementAmount = $settlementAmount;


        $userD = get_user_by("email",$array["customer"]["email"]);
        
        if(empty($userD)){
            die("User not found");
        }
        $userID  = $userD->ID;
        $userData = get_userdata($userID);
        $processor = "payvessel";    
        $session_id =  $data['transaction']['sessionid'];;
        $email =  $userData->user_email;
        $user_data = $userData;
        $userid = $userID;
        $ref = $reference;
        $total_amount = $amount;

    } else {

   // error_log("payvessel mismatch",0);

        echo json_encode(["message" => "Permission denied, invalid hash or ip address."]);
        http_response_code(400);
    }

}
elseif(isset($event->event)  && array_key_exists('http_x_wiaxy_signature', $_XERVER)){

    $apikey = vp_getoption("billstack_apikey");



    $signature = $_XERVER['http_x_wiaxy_signature'];

    if( strtolower($signature) !== strtolower(md5($apikey)) ){


         die("HASH DOES NOT TALLY!");
     }





     $userID = get_user_by("email",$array["data"]["customer"]["email"])->ID;
     $userData = get_userdata($userID);
 
     $amount = $array["data"]["amount"];
     $processor = "billstack";    
     $session_id = $array["data"]["transaction_ref"];
     $email =  $userData->user_email;
     $user_data = $userData;
     $userid = $userID;
     $ref = $session_id;
     $total_amount = $amount;


}
elseif(isset($event->eventType) ){
    if($event->eventType != "SUCCESSFUL_TRANSACTION" ){
        die("Not a successful transaction - Monnify");
    }




    if(function_exists('getallheaders')){

        if(isset(getallheaders()["monnify-signature"])){
            $signature = getallheaders()["monnify-signature"];
        }
        elseif(isset(getallheaders()["Monnify-Signature"])){

            $signature = getallheaders()["Monnify-Signature"];
        }
        else{
        die("No Signature From MONNIFY");
        }

    }else{
        die("getallheaders() Not Running On Your Server. Contact US");  
    }



    $DEFAULT_MERCHANT_CLIENT_SECRET = trim(vp_getoption("monnifysecretkey"));

    $computedHash = computeSHA512TransactionHash($input, $DEFAULT_MERCHANT_CLIENT_SECRET);

    if($signature != $computedHash){
    die("Signature Mismatch");
    }
    else{
    //echo "Signature = ComputedHash <br>";
    }


    $email =  $event->eventData->customer->email;
    $amount = $event->eventData->amountPaid;
    $total_amount = $amount;
    $userid = get_user_by( 'email', $email )->ID;

    $ref = $event->eventData->transactionReference;

    $processor = "Monnify";

}
elseif((strtoupper($_XERVER['request_method']) == 'POST' ) &&  array_key_exists('http_x_squad_signature', $_XERVER)){
 #######################--- FOR SQUAD CO TRANSFER -----###################
 
    //   error_log("1".$input.print_r($_XERVER,true),0);
  
 //   error_log("X PASS",0);

    $squadSecretKey = vp_getoption('squad_secret');
    define('SQUAD_SECRET_KEY',$squadSecretKey ); //ENTER YOUR SECRET KEY HERE

    if( $_XERVER['http_x_squad_signature'] !== strtolower(hash_hmac('sha512', $input, SQUAD_SECRET_KEY)) ){
    //error_log("Hash",0);
    
        die("HASH DOES NOT TALLY!");
    }
    else{

        $body = json_decode(str_replace(" ","",$input),true);

    }

    //error_log($input,0);

    if(isset($body['transaction_indicator'])){

        if(strtolower($body['transaction_indicator']) == "c"){
        //  error_log("char suc",0);

        }else{
    // error_log("charge nt suc",0);

            die("Charge Not Successful");
        }
    }
    else{
    // error_log("no event".print_r($body,true),0);

        die("No Event");
    }

    $the_accountNumber = $body["virtual_account_number"];
    global $wpdb;
    $userdata_tb = $wpdb->prefix."usermeta";
    $userdata_result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $userdata_tb WHERE meta_value LIKE %s","%$the_accountNumber%"));

    if($userdata_result == NULL){
    //   error_log("[TRANSACTION ERROR] - USER WITH $the_accountNumber DOES NOT EXIST",0);
        die("[TRANSACTION ERROR] - USER WITH $the_accountNumber DOES NOT EXIST");
    }
    else{
    $uid =  $userdata_result[0]->user_id;
    }
    

    $transaction_type = "Credit";
    $amount =  intval($body['principal_amount']);
    $total_amount = $amount;
    $user_data = get_user_by( 'ID',$uid);
    $email =  $user_data->user_email;
    $userid = $user_data->ID;
    $ref = $body['transaction_reference'];

    $processor = "SquadCo";
}
elseif((strtoupper($_XERVER['request_method']) == 'POST' ) &&  array_key_exists('http_x_squad_encrypted_body', $_XERVER)){
    ####################### --- CARD PAYMENT --- ################
    
    // error_log("1".$input.print_r($_XERVER,true),0);
     
    //   error_log("X PASS",0);
   
       $squadSecretKey = vp_getoption('squad_secr et');
       define('SQUAD_SECRET_KEY',$squadSecretKey ); //ENTER YOUR SECRET KEY HERE
   
   if( $_XERVER['http_x_squad_encrypted_body'] !== strtolower(hash_hmac('sha512', $input, SQUAD_SECRET_KEY)) ){
      //error_log("Hash",0);
     
       die("HASH DOES NOT TALLY!");
   }
   else{
   
       $body = json_decode(str_replace(" ","",$input),true);
   
   }
   
   //error_log($input,0);
   
   if(isset($body['Event'])){
   
       if(strtolower($body['Event']) == "charge_successful"){
         // error_log("char suc",0);
   
       }else{
      //error_log("charge nt suc",0);
   
           die("Charge Not Successful");
       }
   }
   else{
     //  error_log("no event".print_r($body,true),0);
   
       die("No Event");
   }

     
   
   $transaction_type = "Credit";
   $amount =  intval($body['Body']['amount'])/100;
   $total_amount = $amount;
   $email =  $body['Body']['email'];
   $user_data = get_user_by( 'email',$email);

   $userid = $user_data->ID;
   $ref = $body['Body']['transaction_ref'];
   
   $processor = "SquadCo";
   
}
elseif(($server_ip == "54.173.229.200" || $server_ip == "54.175.230.252" || $server_ip == "35.233.63.150") && (strtoupper($_XERVER['request_method']) == 'POST' ) &&  isset($array["reference"]) && isset($array["amount"])  &&  isset($array["account_number"])   &&  isset($array["fee"])  ){

    if(empty($array["fee"]) || empty($array["amount"]) || empty($array["account_number"]) ){
        die("Bad Para");
    }

    $body = json_decode(str_replace(" ","",$input),true);

    $recipient_number = $array["account_number"];

    global $wpdb;
    $usermeta = $wpdb->prefix."usermeta";
    $userTb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $usermeta WHERE meta_value LIKE %s ","%$recipient_number%"));
    if($userTb == NULL || empty($userTb)) {
    die("User With The Account Not Found");
    }

    $userID = $userTb[0]->user_id;
    $userData = get_userdata($userID);

    $amount = $array["amount"];
    $processor = "Vpay";    
    $session_id = $array["session_id"];
    $email =  $userData->user_email;
    $user_data = $userData;
    $userid = $userID;
    $ref = $session_id;
    $total_amount = $amount;

}
elseif(array_key_exists('http_paymentpoint_signature', $_XERVER)){
    /*
    {
        "notification_status":"payment_successful",
        "transaction_id":"fe976992ac3418a32e74863cbac1071ce6d4429b",
        "amount_paid":100,
        "settlement_amount":99.5,
        "settlement_fee":0.5,
        "transaction_status":"success",
        "sender":{"name":"VICTOR OJOGBANE AKOR","account_number":"****6922","bank":"OPAY"},
        "receiver":{"name":"Imperialmobile enterprise-Ako(Paymentpoint)",
        "account_number":"6677946038",
        "bank":"PalmPay"},
        "customer":{"name":"Akor Victor","email":"akorvictor26@gmail.com","phone":null,"customer_id":"17b041448457465c0e810c9b1675a9e2f8243d83"},
        "description":"Your payment has been successfully processed.",
        "timestamp":"2025-01-31T20:15:37.639947Z"
    }
    */

    $signatureHeader = $_SERVER['HTTP_PAYMENTPOINT_SIGNATURE'];

    $paymentpoint = vp_getoption("paymentpoint_secretkey");
    $calculatedSignature = hash_hmac('sha256', $input, $paymentpoint);
    
    if(!hash_equals($calculatedSignature, $signatureHeader)){
        //error_log("Hash",0);
        // error_log("NOT MATCH");
            die("HASH DOES NOT TALLY!");
    }
    else{
    
            $body = json_decode(str_replace(" ","",$input),true);
            error_log(print_r($body,true),0);
            $webhookData = $body;
    
    }

    $transactionId = $webhookData['transaction_id'] ?? null;
    $amount = $webhookData['amount_paid'] ?? null;
    $settlementAmount = $webhookData['settlement_amount'] ?? null;
    $status = $webhookData['transaction_status'] ?? null;
    $email = $webhookData['customer']["email"] ?? null;
    $recipient_number = $webhookData['receiver']["account_number"] ?? null;
    
    // error_log($email);

    // Check if required data is present
    if (!$transactionId || !$amount || !$settlementAmount || !$status) {
        http_response_code(400);
        echo "Missing required data.";
        exit;
    }
    elseif($status != "success"){
        http_response_code(400);
        echo "Not Successful";
        exit;
    }

    $total_amount = $amount;
    $userid = get_user_by( 'email', $email );
    if(empty($userid)){
        global $wpdb;
        $usermeta = $wpdb->prefix."usermeta";
        $userTb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $usermeta WHERE meta_value LIKE %s ","%$recipient_number%"));
        if($userTb == NULL || empty($userTb)) {
        die("User With The Account Not Found");
        }
    
        $userid = $userTb[0]->user_id;
    }else{
        $userid = $userid->ID;
    }

    $ref = $transactionId;
    $processor = "Paymentpoint";



}
elseif(isset($array["transactionType"])){
    if(strtolower($array["transactionType"]) != "credit"){
        die("Not Kuda");
    }

    $token =  vp_getoption('kuda_generated_apikey');
    //Get transaction data
    $kudaressap = <<<EOD

    {"payingBank":"Kuda",
        "amount":1000,
        "transactionDate":"2023-08-28T00:00:00",
        "transactionReference":"230828187276",
        "accountName":"Adebayo Emmanuel",
        "accountNumber":"2504236668",
        "narrations":"Maze withdraw",
        "transactionType":"Credit",
        "senderName":"Adebayo Emmanuel",
        "senderAccountNumber":"2000355517",
        "recipientName":"Adebayo Emmanuel",
        "instrumentNumber":"16932447073645600961506853044504IWA2H04HC",
        "SessionId":null,
        "clientRequestRef":"16932447073645600961506853044504IWA2H04HC"}
    EOD;

    $recipient_number = $array["accountNumber"];
    $session_id = $array["SessionId"];


    global $wpdb;
    $usermeta = $wpdb->prefix."usermeta";
    $userTb = $wpdb->get_results($wpdb->prepare("SELECT * FROM $usermeta WHERE meta_value LIKE %s ","%$recipient_number%"));
    if($userTb == NULL || empty($userTb)) {
    die("User With The Account Not Found");
    }

    $userID = $userTb[0]->user_id;
    $userData = get_userdata($userID);

    $trackingRef = vp_getuser($userID,"kudaTrackingRef",true);

    $transaction_type = "Credit";
    $amount = $array["amount"]/100;
    $total_amount = $amount;
    $email =  $userData->user_email;
    $user_data = $userData;
    $userid = $userID;
    $ref = $session_id;




    $payload = [
        'serviceType' => "WITHDRAW_VIRTUAL_ACCOUNT",
        'requestRef' => uniqid(),
        'Data' => [
            "TrackingReference" => $trackingRef,
            "Amount" => $array["amount"],
            "Narration" => "To main account",
            "narration" => "text it",
            "ClientFeeCharge" => 0
            ]
        ];
        
    $live_url = "https://kuda-openapi.kuda.com/v2.1";

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL =>  $live_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>json_encode($payload),
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "content-type: application/json",
        "Authorization: Bearer $token"
    ],
    ]);

    $res = curl_exec($curl);
    $response = json_decode($res,true); 
    $err = curl_error($curl);

    curl_close($curl);


    $processor = "KUDA";

}
else{
   error_log("2".$input.print_r($_XERVER,true),0);

    error_log("None Successful",0);

	//echo "None Successful";

    die("None Successful");
}


if(!empty($ref) && !empty($amount) && !empty($total_amount)  && !empty($userid) && !empty($email)  && !empty($processor) ){

    $who_ref_id = vp_getuser($userid, "vp_who_ref", true); //ref id

$plan = vp_getuser( $who_ref_id , "vr_plan", true);
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE name = %s",$plan));

if($level != NULL && !empty($level)){
    $ref_chargeback = $level[0]->charge_back_percentage;

    if($ref_chargeback > 0){

    
 $ref_remove = ($total_amount *  $ref_chargeback) / 100;


$who_ref_bal =  vp_getuser($who_ref_id, "vp_bal", true);

$total_to_add =  $ref_remove;

$add_to_ref_bal = intval($who_ref_bal) + $total_to_add;

//error_log("id = $who_ref_id, plan = $plan, $who_ref_bal + $total_to_add = $add_to_ref_bal",0);

$rname = get_userdata($who_ref_id)->user_login;
$table_name = $wpdb->prefix.'vp_wallet';
$added = $wpdb->insert($table_name, array(
'name'=> "$rname",
'type'=> 'wallet',
'description'=> "Got ref bonus from $rname",
'fund_amount' => $total_to_add,
'before_amount' => $who_ref_bal ,
'now_amount' => $add_to_ref_bal,
'user_id' => $who_ref_id,
'status' => "approved",
'the_time' => current_time('mysql', 1)
));

vp_updateuser($who_ref_id,"vp_bal",$add_to_ref_bal);
}

}
else{
  //  error_log("whoref is $who_ref_id and Level is ".print_r($level,true),0);
}

global $wpdb;
$sd_name = $wpdb->prefix.'vp_wallet_webhook';
$rest = $wpdb->get_results($wpdb->prepare("SELECT * FROM $sd_name WHERE referrence = %s",$ref));
if(!empty($rest)){

http_response_code(200);

header("HTTP/1.1 200 OK");

    die("This Transaction Has Been Processed Before");
}
else{}

$wpdb->insert($sd_name, array(
'user_id'=> $userid,
'gateway' => $processor,
'amount'=> $amount,
'referrence' => $ref,
'status' => "pending",
'response' => " ".esc_html(harray_key_first($input))."",
'the_time' => date(current_time('mysql').' A')
));



$user_name =  get_user_by( 'email', $email )->user_login;

$ini = vp_getuser($userid, 'vp_bal', true);

/*
if(vp_getoption("charge_method") == "fixed"){
$minus = $total_amount - $charge;
}
else{
$remove = ($total_amount *  $charge) / 100;
$minus = $total_amount - $remove ;
}
*/

switch(strtolower($processor)){
    case"paymentpoint":
        $charge = floatval(vp_getoption("paymentpoint_charge_back"));

        if(vp_getoption("paymentpoint_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"monnify":
        $charge = floatval(vp_getoption("charge_back"));

        if(vp_getoption("charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"squadco":
        $charge = floatval(vp_getoption("gtb_charge_back"));
        if(vp_getoption("gtb_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"ncwallet":
        $charge = floatval(vp_getoption("ncwallet_charge_back"));
        if(vp_getoption("ncwallet_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"billstack":
        $charge = floatval(vp_getoption("billstack_charge_back"));
        if(vp_getoption("billstack_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"payvessel":
        $charge = floatval(vp_getoption("payvessel_charge_back"));
        if(vp_getoption("payvessel_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"vpay":
        $charge = floatval(vp_getoption("vpay_charge_back"));
        if(vp_getoption("vpay_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
    case"kuda":
        $charge = floatval(vp_getoption("kuda_charge_back"));
        if(vp_getoption("kuda_charge_method") == "fixed"){
            $minus = $total_amount - $charge;
            }
            else{
            $remove = ($total_amount *  $charge) / 100;
            $minus = $total_amount - $remove ;
            }
    break;
default:  $minus = $total_amount - 0 ;
}


$toti = $ini + $minus;

vp_updateuser($userid, 'vp_bal', $toti);

$now = vp_getuser($userid, 'vp_bal', true);


global $wpdb;
$name = get_userdata($userid)->user_login;
$description = 'Credited By You [Online]';
$fund_amount= $minus;
$before_amount = $ini;
$now_amount = $toti;
$user_id = $userid;
$the_time = current_time('mysql', 1);

$table_name = $wpdb->prefix.'vp_wallet';
$added = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> 'wallet',
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $user_id,
'status' => "approved",
'the_time' => current_time('mysql', 1)
));

if(is_numeric($added)){
    global $wpdb;
    $table_name = $wpdb->prefix."vp_wallet_webhook";
    $wpdb->update($table_name, array("status"=>"success"), array("referrence"=>$ref));
}
else{
    global $wpdb;
    $table_name = $wpdb->prefix."vp_wallet_webhook";
    $wpdb->update($table_name, array("status"=>"failed"), array("referrence"=>$ref));  
}

$content = "
<!DOCTYPE html>
<html>
<body>
<h3>New Transaction Logged!</h3><br/>
<table>
<thead>
<tr>
<th>Details</th>
<th>Data</th>
</tr>
</thead>
<tbody>
<tr>
<td>Name</td>
<td>$user_name</td>
</tr>
<tr>
<td>Email</td>
<td>$email</td>
</tr>
<tr>
<td>Previous Balane</td>
<td>$ini</td>
</tr>
<tr>
<td>Funded</td>
<td>$minus</td>
</tr>
</tbody>
<tfoot>
<tr>
<td>Current Balance</td>
<td>$toti</td>
</tr>
</tfoot>
</table>

</body>
</html>


";

wp_mail($admine, "$user_name Wallet Funding [$processor]", $content, $headers);
wp_mail($email, "$user_name Wallet Funding [$processor]", $content, $headers);
http_response_code(200);

header("HTTP/1.1 200 OK");

//echo "Successful For $processor";


http_response_code(200);

die("Successful For $processor");

}
else{
 //   error_log("A Requirement(s) is/are empty",0);
    die("A Requirement(s) is/are empty");
}


?>