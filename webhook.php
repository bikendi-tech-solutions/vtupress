<?php
header("Access-Control-Allow-Origin: *");

/*

if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
else{
include_once(ABSPATH ."wp-load.php");
}
if(isset($_GET['debug'])){

}
else{
	error_reporting(0);	
}
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

$option_array = json_decode(get_option("vp_options"),true);


function search_bill_token_webhook($darray,$key){
  global  $current_timestamp;
    $array = array_change_key_case($darray,CASE_LOWER);
   $results = array();

  if (is_array($array)){
    if (isset($array[strtolower($key)])){
        $results[] = $array[strtolower($key)];
    }

    foreach ($array as $sub_array ){
        $results = array_merge($results, search_bill_token_webhook($sub_array, $key));
    }
  }
return $results;
}


function validate_response_webhook($response, $key, $value, $alter="nothing_to_find"){
    global  $current_timestamp;
if(json_decode($response) == NULL){
$dis = new stdclass;
$dis->str = $response;
$response = json_encode($dis);
}

    $array = array_change_key_case(json_decode($response,true),CASE_LOWER);


function search_Key_query($array,$key){
   $results = array();

  if (is_array($array)){
    if (isset($array[strtolower($key)])){
        $results[] =$array[strtolower($key)];
    }

    foreach ($array as $sub_array ){
        $results = array_merge($results, search_Key_query($sub_array, $key));
    }
  }
return array_change_key_case($results,CASE_LOWER);
}

function search_val_query($results, $the_value, $alt = "nothing"){
    $status = "FALSE";
    foreach($results as $dvalue){
        if(!is_array($dvalue)){
            if(strtolower($dvalue) === strtolower($the_value) || strtolower($dvalue) === strtolower($alt)){
                $status = "TRUE";
            }
            
        }
    }
    return $status;
}

$result = search_Key_query($array,$key);
//print_r($result);
$status_from_result_val = search_val_query($result,$value,$alter);

return $status_from_result_val;
    

}


function harray_key_first_webhook($arr) {
	$arg = json_decode($arr);
	if(is_array($arg)){
		$response  = array("him"=>"me", "them"=>"you");
        foreach($response as $key => $value) {
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

$incomingcontenttype = $_SERVER["Content-Type"];
if($incomingcontenttype !== "application/json"){
$content = trim(vp_get_contents("php://input"));
$array = json_decode($content,true);


$ipa = $_SERVER['HTTP_USER_AGENT']??$_SERVER['USER_AGENT'];
//print_r(getallheaders());
//echo "///A".$_SERVER['HTTP_USER_AGENT'];
/*
global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "",
'service_id' => "",
'request_id' => "",
'response_id' => "",
'server_ip' => "",
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
*/






/*
global $wpdb;
$airtime = $wpdb->prefix.'sairtime';
$data = $wpdb->prefix.'sdata';
$cable = $wpdb->prefix.'scable';
$bill = $wpdb->prefix.'sbill';


$vtu_response = vp_getoption("vturesponse_id");
$share_response = vp_getoption("shareresponse_id");
$awuf_response = vp_getoption("awufresponse_id");
$sme_response = vp_getoption("smeresponse_id");
$corporate_response = vp_getoption("corporateresponse_id");
$direct_response = vp_getoption("directresponse_id");
$cable_response = vp_getoption("cableresponse_id");
$bill_response = vp_getoption("billresponse_id");

$airtime_response_id = "";
$data_response_id = "";
$cable_response_id = "";
$bill_response_id = "";
$result_airtime = "";
$result_data = "";
$result_cable = "";
$result_bill = "";


$search_vtu = search_bill_token_webhook($array,$vtu_response);
$search_share = search_bill_token_webhook($array,$share_response);
$search_awuf = search_bill_token_webhook($array,$awuf_response);
if(empty($search_vtu)){
	$search_vtu = 'nosearch';
}
else{
	$search_vtu = $search_vtu[0];
}
if(empty($search_share)){
	$search_share = 'nosearch';
}
else{
	$search_share = $search_share[0];
}
if(empty($search_awuf)){
	$search_awuf = 'nosearch';
}
else{
	$search_awuf = $search_awuf[0];
}



$result_airtime = $wpdb->get_results("SELECT * FROM  $airtime WHERE status != 'Successful' AND time_taken = 1 AND (response_id = '$search_vtu' OR response_id = '$search_share'  OR response_id = '$search_awuf' )");

if(empty($result_airtime)){
$search_sme = search_bill_token_webhook($array,$sme_response);
$search_corporate = search_bill_token_webhook($array,$corporate_response);
$search_direct = search_bill_token_webhook($array,$direct_response);
if(empty($search_sme)){
	$search_sme = 'nosearch';
}
else{
	$search_sme = $search_sme[0];
}
if(empty($search_corporate)){
	$search_corporate = 'nosearch';
}
else{
	$search_corporate = $search_corporate[0];
}
if(empty($search_direct)){
	$search_direct = 'nosearch';
}
else{
	$search_direct = $search_direct[0];
}

$result_data = $wpdb->get_results("SELECT * FROM  $data WHERE status != 'Successful' AND time_taken = 1 AND  (response_id = '$search_sme' OR response_id = '$search_corporate'  OR response_id = '$search_direct' )");
	
}

if(empty($result_airtime) && empty($result_data)){
	$cab = search_bill_token_webhook($array,$cable_response);
if(!empty($cab)){
$cable_response_id = $cab[0];
}
else{
$cable_response_id = 'nosearch';	
}
$result_cable = $wpdb->get_results("SELECT * FROM  $cable WHERE status != 'Successful' AND  time_taken = 1 AND response_id = '$cable_response_id' ");
}

if(empty($result_airtime) && empty($result_data) && empty($result_cable)){
	$billa = search_bill_token_webhook($array,$bill_response);
if(!empty($billa)){
$bill_response_id = $billa[0];
}
else{
$bill_response_id = 'nosearch';	
}

$result_bill = $wpdb->get_results("SELECT * FROM  $bill WHERE status != 'Successful' AND  time_taken = 1 AND response_id = '$bill_response_id' ");

}


//AIRTIME

if(!empty($result_airtime) && $result_airtime != NULL){
foreach($result_airtime as $the_airtime){


$type = strtolower(vp_getvalue($the_airtime->trans_type));
/*
echo "<pre>";
echo $the_airtime->id .$type;
*/



/*
switch($type){
	case"vtu":
#echo "vtu";

global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "AIRTIME VTU",
'service_id' => $the_airtime->id,
'request_id' => $the_airtime->request_id,
'response_id' => $the_airtime->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));


$sc = vp_getoption("airtimesuccesscode");
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $content  === vp_getoption("airtimesuccessvalue")){
	
$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
	
	
$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{


if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);


$data = [ 'status' => 'Failed', 'time_taken' => '2', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);	

$trans_id = $the_airtime->id;
$user = $the_airtime->user_id;
$name = get_option('blogname');
$amount_billed = $the_airtime->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Airtime transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}
	
}

	break;
	case"share":

global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "SHARE VTU",
'service_id' => $the_airtime->id,
'request_id' => $the_airtime->request_id,
'response_id' => $the_airtime->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));

$sc = vp_getoption("sairtimesuccesscode");
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $content  === vp_getoption("sairtimesuccessvalue")){
	
$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);


$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{
if(vp_getoption("auto_refund") == "yes"){
	
$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);


$data = [ 'status' => 'Failed', 'time_taken' => '2',  'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);	

$trans_id = $the_airtime->id;
$user = $the_airtime->user_id;
$name = get_option('blogname');
$amount_billed = $the_airtime->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Airtime transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}

}
break;
	case"awuf":
global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "AWUF VTU",
'service_id' => $the_airtime->id,
'request_id' => $the_airtime->request_id,
'response_id' => $the_airtime->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));

$sc = vp_getoption("wairtimesuccesscode");
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $content  === vp_getoption("wairtimesuccessvalue")){


$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);


$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{
if(vp_getoption("auto_refund") == "yes"){
	

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);


$data = [ 'status' => 'Failed',  'time_taken' => '2', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_airtime->response_id ];
$updated = $wpdb->update($airtime, $data, $where);	

$trans_id = $the_airtime->id;
$user = $the_airtime->user_id;
$name = get_option('blogname');
$amount_billed = $the_airtime->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Airtime transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}

}
	
	break;
	
	
	
	
}
	
}
}

//DATA
elseif(!empty($result_data) && $result_data != NULL){
foreach($result_data as $the_data){


$type = strtolower(vp_getvalue($the_data->trans_type));

switch($type){
	case"sme":
	
global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "DATA SME",
'service_id' => $the_data->id,
'request_id' => $the_data->request_id,
'response_id' => $the_data->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));

$sc = vp_getoption("datasuccesscode");
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("datasuccessvalue"), vp_getoption("datasuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $response  === vp_getoption("datasuccessvalue")){

$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{


if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);

$ddata = [ 'status' => 'Failed',  'time_taken' => '2', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);	

$trans_id = $the_data->id;
$user = $the_data->user_id;
$name = get_option('blogname');
$amount_billed = $the_data->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Data transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}



}	
	break;
	case"corporate":
	global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "DATA CORPORATE",
'service_id' => $the_data->id,
'request_id' => $the_data->request_id,
'response_id' => $the_data->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));


$sc = vp_getoption("r2datasuccesscode");
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("r2datasuccessvalue"), vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $response  === vp_getoption("r2datasuccessvalue")){

$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{
	
if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);

$ddata = [ 'status' => 'Failed',  'time_taken' => '2', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);	

$trans_id = $the_data->id;
$user = $the_data->user_id;
$name = get_option('blogname');
$amount_billed = $the_data->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Data transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}
	
}
	break;
	case"direct":

global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "DATA DIRECT",
'service_id' => $the_data->id,
'request_id' => $the_data->request_id,
'response_id' => $the_data->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));

$sc = vp_getoption("rdatasuccesscode");
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("rdatasuccessvalue"), vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $response  === vp_getoption("rdatasuccessvalue")){

$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);	
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{


if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);

$ddata = [ 'status' => 'Failed', 'time_taken' => '2',  'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_data->response_id ];
$updated = $wpdb->update($data, $ddata, $where);	

$trans_id = $the_data->id;
$user = $the_data->user_id;
$name = get_option('blogname');
$amount_billed = $the_data->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Data transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}

	
}
	break;
	
	
	
	
}
	
}
}

//CABLE
elseif(!empty($result_cable) && $result_cable != NULL){
foreach($result_cable as $the_cable){

global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "CABLE",
'service_id' => $the_cable->id,
'request_id' => $the_cable->request_id,
'response_id' => $the_cable->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));


$sc = vp_getoption("cablesuccesscode");
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("cablesuccessvalue"), vp_getoption("cablesuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $content  === vp_getoption("cablesuccessvalue")){
	
$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);


$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_cable->response_id ];
$updated = $wpdb->update($cable, $ddata, $where);
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{


if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);

$ddata = [ 'status' => 'Failed', 'time_taken' => '2',  'resp_log' => "".esc_html(harray_key_first_webhook($content))."" ];
$where = [ 'response_id' => $the_cable->response_id ];
$updated = $wpdb->update($cable, $ddata, $where);

$trans_id = $the_cable->id;
$user = $the_cable->user_id;
$name = get_option('blogname');
$amount_billed = $the_cable->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Cable transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}



}
}
}

//BILL
elseif(!empty($result_bill) && $result_bill != NULL){

foreach($result_bill as $the_bill){
	
global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "BILL",
'service_id' => $the_bill->id,
'request_id' => $the_bill->request_id,
'response_id' => $the_bill->response_id,
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));


$bill_response = search_bill_token_webhook($array,vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}

$sc = vp_getoption("billsuccesscode");
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response_webhook($content,$sc, vp_getoption("billsuccessvalue"), vp_getoption("billsuccessvalue2"));
}
else{
$en = $content ;
}
if($en == "TRUE" || $content  === vp_getoption("billsuccessvalue")){

$cron_successful = vp_getoption("cron_successful");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);

$bbill = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_webhook($content))."", 'meter_token' =>  $meter_token ];
$where = [ 'response_id' => $the_bill->response_id ];
$updated = $wpdb->update($bill, $bbill, $where);

header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));
}
else{

if(vp_getoption("auto_refund") == "yes"){

$cron_successful = vp_getoption("cron_failed");
$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_failed",$cron_successful);

$bbill = [ 'status' => 'Failed', 'time_taken' => '2',  'resp_log' => "".esc_html(harray_key_first_webhook($content)).""];
$where = [ 'response_id' => $the_bill->response_id ];
$updated = $wpdb->update($bill, $bbill, $where);


$trans_id = $the_bill->id;
$user = $the_bill->user_id;
$name = get_option('blogname');
$amount_billed = $the_bill->amount;
$curr = floatval(vp_getuser($user,"vp_bal",true))+0.1;
$bal = floatval($amount_billed+$curr);
vp_updateuser($user,'vp_bal',$bal);

//notify user about the update
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "[Webhook] Refund for Bill transaction with ID $trans_id",
'fund_amount' => $amount_billed,
'before_amount' => $curr,
'now_amount' => $bal,
'user_id' => $user,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));
header("Status: 200 OK");
$obj = new stdClass;
$obj->response = "success";
die(json_encode($obj));

}
else{
header("Status: 400");
}


}
}
}

else{
	
global $wpdb;
$table_name = $wpdb->prefix.'vpwebhook';
$wpdb->insert($table_name, array(
'service'=> "Nill",
'service_id' => "Nill",
'request_id' => "Nill",
'response_id' => "Nill",
'server_ip' => $ipa,
'resp_log' => " ".esc_html(harray_key_first_webhook($content))."",
'the_time' => date('Y-m-d h:i:s',$current_timestamp)
));

header("Status: 400");
$obj = new stdClass;
$obj->response = "Responce Id Not Found For Unsuccessful Transaction";
die(json_encode($obj));
	
}




}
else{
header("Status: 500 Internal Server Error");
die();
}

*/