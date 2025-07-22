<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH.'wp-content/plugins/vtupress/functions.php');

$userid = get_current_user_id();

header("Access-Control-Allow-Origin: 'self'");

$current_clr = $_COOKIE["current_clr"];
$siteurl = get_option('siteurl');
if(isset($_REQUEST["current_clr"])){
  if($_REQUEST["current_clr"] == $current_clr){
//go on
  }else{
    die("CALLER SIGNATURE MIS-MATCH $current_clr != ".$_REQUEST["current_clr"]);
  }
}else{
  die("NO CALLER");
}

if(!isset($_SERVER["HTTP_REFERER"])){
die("Error!!! No Ref");
}
elseif(!is_numeric(stripos("url-".$_SERVER["HTTP_REFERER"],"$siteurl/wp-content/plugins/vtupress/pay.php"))){
die(stripos("url-".$_SERVER["HTTP_REFERER"],"$siteurl/wp-content/plugins/vtupress/pay.php")."Error!!! Inv ref".$_SERVER["HTTP_REFERER"]);
}
else{
  //go on
}


function harray_key_first($arr="") {
	$arg = json_decode($arr);
	if(is_array($arg)){
		$response  = array("him"=>"me", "them"=>"you");
        foreach($resp as $key => $value) {
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


if(isset($_REQUEST['gateway'])){
$psec = vp_getoption('psec');
$gateway = $_REQUEST['gateway'];

if(vp_getoption("charge_method") == "fixed"){
$amount = intval($_REQUEST["amount"]) - floatval(vp_getoption("charge_back"));
}
else{
$remove = (intval($_REQUEST["amount"]) *  floatval(vp_getoption("charge_back"))) / 100;
$amount = intval($_REQUEST["amount"]) - $remove;
}

switch($gateway){
	
	
	case"paystack":
	if(isset($_REQUEST["status"]) && $_REQUEST["status"] == "successful"){
$ref = $_REQUEST["reference"];
$curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$ref,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$psec,
      "Cache-Control: no-cache"
    ),
  ));
  
  $response = curl_exec($curl);

  $res = json_decode(str_replace(" ","",$response));
  $err = curl_error($curl);
  curl_close($curl);
  
  if ($err) {
  //  echo "cURL Error #:" . ;
	die($err);
  }
  else{
if($res->data->status == "success"){
  //go on

  global $wpdb;
$sd_name = $wpdb->prefix.'vp_wallet_webhook';
$rest = $wpdb->get_results("SELECT * FROM $sd_name WHERE referrence = '$ref'");
if(!empty($rest)){

http_response_code("HTTP 200 OK");

header("HTTP/1.1 200 OK");

    die("This Transaction Has Been Processed Before");
}
else{}

$wpdb->insert($sd_name, array(
'user_id'=> $userid,
'gateway' => 'Paystack',
'amount'=> ($res->data->amount)/100,
'referrence' => $ref,
'status' => "pending",
'response' => " ".esc_html(harray_key_first($response))."",
'the_time' => date('Y-m-d H:i:s A',$current_timestamp)
));


}
else{
  die("Transaction Query Response Status Is Not Successful");
}


$ini = vp_getuser($userid, 'vp_bal', true);
$tot = $ini+$amount;
vp_updateuser($userid, 'vp_bal', $tot);



global $wpdb;
$name = get_userdata($userid)->user_login;
$email = get_userdata($userid)->user_email;
$description = 'Credited By You [Online]';
$fund_amount = $amount;
$before_amount = $ini;
$now_amount = $tot;
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

//wp_mail($admine, "$user_name Wallet Funding [PATSTACK - N-WEB]", $content, $headers);
//wp_mail($email, "$user_name Wallet Funding [PAYSTACK - N-WEB]", $content, $headers);

die("100");


}
  
}
  
  break;
  
}



}
