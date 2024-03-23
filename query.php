<?php
header("Access-Control-Allow-Origin: 'self'");
die();
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
else{
include_once(ABSPATH .""/$nm/"wp-load.php");
}
if(isset($_GET['debug'])){

}
else{
	error_reporting(0);	
}
if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match('/$nm/',$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}

include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

$option_array = json_decode(get_option("vp_options"),true);

$allow = "no";

if($allow == "yes"){
function search_bill_token1($array,$key){
   $results = array();

  if (is_array($array)){
    if (isset($array[strtolower($key)])){
        $results[] = $array[strtolower($key)];
    }

    foreach ($array as $sub_array ){
        $results = array_merge($results, search_bill_token1($sub_array, $key));
    }
  }
return $results;
}


function validate_response_query($response, $key, $value, $alter="nothing_to_find"){
    
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


function harray_key_first_query($arr) {
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

#if(strtolower(vp_getoption('enable-schedule')) == "yes"){

global $wpdb;
$airtime = $wpdb->prefix.'sairtime';
$data = $wpdb->prefix.'sdata';
$cable = $wpdb->prefix.'scable';
$bill = $wpdb->prefix.'sbill';

$result_airtime = $wpdb->get_results("SELECT * FROM  $airtime WHERE status != 'Successful' AND via != 'No Value	'");
$result_data = $wpdb->get_results("SELECT * FROM  $data WHERE status != 'Successful' AND via != 'No Value	'");
$result_cable = $wpdb->get_results("SELECT * FROM  $cable WHERE status != 'Successful' AND via != 'No Value	'");
$result_bill = $wpdb->get_results("SELECT * FROM  $bill WHERE status != 'Successful' AND via != 'No Value	'");

//AIRTIME

foreach($result_airtime as $the_airtime){

$cron_failed = vp_getoption("cron_failed");
$cron_successful = vp_getoption("cron_successful");


$type = strtolower(vp_getvalue($the_airtime->trans_type));
/*
echo "<pre>";
echo $the_airtime->id .$type;
*/
switch($type){
	case"vtu":

if(!empty(vp_getoption("vtuquerytext"))){
if(strtolower(vp_getoption('vtuquerymethod')) == "get"){

$sc = vp_getoption("airtimesuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"airtimebaseurl").vp_option_array($option_array,"vtuquerytext")."&".vp_option_array($option_array,"airtimepostdata1").'='.vp_option_array($option_array,"airtimepostvalue1")."&".vp_option_array($option_array,"arequest_id")."=".vp_getvalue($the_airtime->request_id)."&".vp_option_array($option_array,"airtimepostdata2").'='.vp_option_array($option_array,"airtimepostvalue2")."&".vp_option_array($option_array,"airtimepostdata3").'='.vp_option_array($option_array,"airtimepostvalue3")."&".vp_option_array($option_array,"airtimepostdata4").'='.vp_option_array($option_array,"airtimepostvalue4")."&".vp_option_array($option_array,"airtimepostdata5").'='.vp_option_array($option_array,"airtimepostvalue5");

$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#dddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("airtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);



}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('vtuquerymethod')) == "get2"){

if(strtolower(vp_getoption("vtuaddendpoint")) == "no"){
$url = vp_getoption("airtimebaseurl").vp_getoption("vtuquerytext");
}
else{
$url = vp_getoption("airtimebaseurl").vp_getoption("vtuquerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("airtimepostdata1");
    $cppa = vp_getoption("airtimepostdata2");
    $c1a = vp_getoption("airtimepostdata3");
    $c2a = vp_getoption("airtimepostdata4");
    $c3a = vp_getoption("airtimepostdata5");
    $cna = vp_getoption("airtimenetworkattribute");
    $caa = vp_getoption("airtimeamountattribute");
    $cpa = vp_getoption("airtimephoneattribute");
	$uniqid = vp_getoption("arequest_id");
    
    $datass = array(
    $cua => vp_getoption("airtimepostvalue1"),
    $cppa => vp_getoption("airtimepostvalue2"),
	$c1a => vp_getoption("airtimepostvalue3"),
	$c2a => vp_getoption("airtimepostvalue4"),
	$c3a => vp_getoption("airtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("airtimevalue1");
}
else{
	$the_auth_value = vp_getoption("airtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("airtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("airtimehead1");
$auto = "$token $the_auth";

$vtuairtime_array = [];
$vtuairtime_array["cache-control"] = "no-cache";
$vtuairtime_array["content-type"] = "application/json";
$vtuairtime_array["Authorization"] = $auto;
for($vtuaddheaders=1; $vtuaddheaders<=4; $vtuaddheaders++){
	if(!empty(vp_getoption("vtuaddheaders$vtuaddheaders")) && !empty(vp_getoption("vtuaddvalue$vtuaddheaders"))){
		$vtuairtime_array[vp_getoption("vtuaddheaders$vtuaddheaders")] = vp_getoption("vtuaddvalue$vtuaddheaders");
	}
}



$http_args = array(
'headers' => $vtuairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($datass)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("airtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
	
}





}
else{

if(strtolower(vp_getoption("vtuaddendpoint")) == "no"){
$url = vp_getoption("airtimebaseurl").vp_getoption("vtuquerytext");
}
else{
$url = vp_getoption("airtimebaseurl").vp_getoption("vtuquerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("airtimepostdata1");
    $cppa = vp_getoption("airtimepostdata2");
    $c1a = vp_getoption("airtimepostdata3");
    $c2a = vp_getoption("airtimepostdata4");
    $c3a = vp_getoption("airtimepostdata5");
    $cna = vp_getoption("airtimenetworkattribute");
    $caa = vp_getoption("airtimeamountattribute");
    $cpa = vp_getoption("airtimephoneattribute");
	$uniqid = vp_getoption("arequest_id");
    
    $datass = array(
    $cua => vp_getoption("airtimepostvalue1"),
    $cppa => vp_getoption("airtimepostvalue2"),
	$c1a => vp_getoption("airtimepostvalue3"),
	$c2a => vp_getoption("airtimepostvalue4"),
	$c3a => vp_getoption("airtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("airtimevalue1");
}
else{
	$the_auth_value = vp_getoption("airtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("airtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("airtimehead1");
$auto = "$token $the_auth";

$vtuairtime_array = [];
$vtuairtime_array["cache-control"] = "no-cache";
$vtuairtime_array["content-type"] = "application/json";
$vtuairtime_array["Authorization"] = $auto;
for($vtuaddheaders=1; $vtuaddheaders<=4; $vtuaddheaders++){
	if(!empty(vp_getoption("vtuaddheaders$vtuaddheaders")) && !empty(vp_getoption("vtuaddvalue$vtuaddheaders"))){
		$vtuairtime_array[vp_getoption("vtuaddheaders$vtuaddheaders")] = vp_getoption("vtuaddvalue$vtuaddheaders");
	}
}



$http_args = array(
'headers' => $vtuairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($datass)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime1_response_format") == "JSON" || vp_getoption("airtime1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("airtimesuccessvalue"), vp_getoption("airtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("airtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
	
}
	
	break;
	case"share":

if(!empty(vp_getoption("sharequerytext"))){
if(strtolower(vp_getoption('sharequerymethod')) == "get"){

$sc = vp_getoption("sairtimesuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"sairtimebaseurl").vp_option_array($option_array,"sharequerytext")."&".vp_option_array($option_array,"sairtimepostdata1").'='.vp_option_array($option_array,"sairtimepostvalue1")."&".vp_option_array($option_array,"sarequest_id")."=".vp_getvalue($the_airtime->request_id)."&".vp_option_array($option_array,"sairtimepostdata2").'='.vp_option_array($option_array,"sairtimepostvalue2")."&".vp_option_array($option_array,"sairtimepostdata3").'='.vp_option_array($option_array,"sairtimepostvalue3")."&".vp_option_array($option_array,"sairtimepostdata4").'='.vp_option_array($option_array,"sairtimepostvalue4")."&".vp_option_array($option_array,"sairtimepostdata5").'='.vp_option_array($option_array,"sairtimepostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#dddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("sairtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('sharequerymethod')) == "get2"){

if(strtolower(vp_getoption("shareaddendpoint")) == "no"){
$url = vp_getoption("sairtimebaseurl").vp_getoption("sharequerytext");
}
else{
$url = vp_getoption("sairtimebaseurl").vp_getoption("sharequerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("sairtimepostdata1");
    $cppa = vp_getoption("sairtimepostdata2");
    $c1a = vp_getoption("sairtimepostdata3");
    $c2a = vp_getoption("sairtimepostdata4");
    $c3a = vp_getoption("sairtimepostdata5");
    $cna = vp_getoption("sairtimenetworkattribute");
    $caa = vp_getoption("sairtimeamountattribute");
    $cpa = vp_getoption("sairtimephoneattribute");
	$uniqid = vp_getoption("sarequest_id");
    
    $datass = array(
    $cua => vp_getoption("sairtimepostvalue1"),
    $cppa => vp_getoption("sairtimepostvalue2"),
	$c1a => vp_getoption("sairtimepostvalue3"),
	$c2a => vp_getoption("sairtimepostvalue4"),
	$c3a => vp_getoption("sairtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head2");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("sairtimevalue1");
}
else{
	$the_auth_value = vp_getoption("sairtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("sairtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("sairtimehead1");
$auto = "$token $the_auth";

$shareairtime_array = [];
$shareairtime_array["Content-Type"] = "application/json";
$shareairtime_array["cache-control"] = "no-cache";
$shareairtime_array["Authorization"] = $auto;
for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
	if(!empty(vp_getoption("shareaddheaders$shareaddheaders")) && !empty(vp_getoption("shareaddvalue$shareaddheaders"))){
		$shareairtime_array[vp_getoption("shareaddheaders$shareaddheaders")] = vp_getoption("shareaddvalue$shareaddheaders");
	}
}



$http_args = array(
'headers' => $shareairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("sairtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
else{


if(strtolower(vp_getoption("shareaddendpoint")) == "no"){
$url = vp_getoption("sairtimebaseurl").vp_getoption("sharequerytext");
}
else{
$url = vp_getoption("sairtimebaseurl").vp_getoption("sharequerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("sairtimepostdata1");
    $cppa = vp_getoption("sairtimepostdata2");
    $c1a = vp_getoption("sairtimepostdata3");
    $c2a = vp_getoption("sairtimepostdata4");
    $c3a = vp_getoption("sairtimepostdata5");
    $cna = vp_getoption("sairtimenetworkattribute");
    $caa = vp_getoption("sairtimeamountattribute");
    $cpa = vp_getoption("sairtimephoneattribute");
	$uniqid = vp_getoption("sarequest_id");
    
    $datass = array(
    $cua => vp_getoption("sairtimepostvalue1"),
    $cppa => vp_getoption("sairtimepostvalue2"),
	$c1a => vp_getoption("sairtimepostvalue3"),
	$c2a => vp_getoption("sairtimepostvalue4"),
	$c3a => vp_getoption("sairtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head2");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("sairtimevalue1");
}
else{
	$the_auth_value = vp_getoption("sairtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("sairtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("sairtimehead1");
$auto = "$token $the_auth";

$shareairtime_array = [];
$shareairtime_array["Content-Type"] = "application/json";
$shareairtime_array["cache-control"] = "no-cache";
$shareairtime_array["Authorization"] = $auto;
for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
	if(!empty(vp_getoption("shareaddheaders$shareaddheaders")) && !empty(vp_getoption("shareaddvalue$shareaddheaders"))){
		$shareairtime_array[vp_getoption("shareaddheaders$shareaddheaders")] = vp_getoption("shareaddvalue$shareaddheaders");
	}
}



$http_args = array(
'headers' => $shareairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime2_response_format") == "JSON" || vp_getoption("airtime2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("sairtimesuccessvalue"), vp_getoption("sairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("sairtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);



}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}



}
	
}	
	
	break;
	case"awuf":

if(!empty(vp_getoption("awufquerytext"))){
if(strtolower(vp_getoption('awufquerymethod')) == "get"){

$sc = vp_getoption("wairtimesuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"wairtimebaseurl").vp_option_array($option_array,"awufquerytext")."&".vp_option_array($option_array,"wairtimepostdata1").'='.vp_option_array($option_array,"wairtimepostvalue1")."&".vp_option_array($option_array,"warequest_id")."=".vp_getvalue($the_airtime->request_id)."&".vp_option_array($option_array,"wairtimepostdata2").'='.vp_option_array($option_array,"wairtimepostvalue2")."&".vp_option_array($option_array,"wairtimepostdata3").'='.vp_option_array($option_array,"wairtimepostvalue3")."&".vp_option_array($option_array,"wairtimepostdata4").'='.vp_option_array($option_array,"wairtimepostvalue4")."&".vp_option_array($option_array,"wairtimepostdata5").'='.vp_option_array($option_array,"wairtimepostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#dddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("wairtimesuccessvalue")){

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);
}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('awufquerymethod')) == "get2"){

if(strtolower(vp_getoption("awufaddendpoint")) == "no"){
$url = vp_getoption("wairtimebaseurl").vp_getoption("awufquerytext");
}
else{
$url = vp_getoption("wairtimebaseurl").vp_getoption("awufquerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("wairtimepostdata1");
    $cppa = vp_getoption("wairtimepostdata2");
    $c1a = vp_getoption("wairtimepostdata3");
    $c2a = vp_getoption("wairtimepostdata4");
    $c3a = vp_getoption("wairtimepostdata5");
    $cna = vp_getoption("wairtimenetworkattribute");
    $caa = vp_getoption("wairtimeamountattribute");
    $cpa = vp_getoption("wairtimephoneattribute");
	$uniqid = vp_getoption("warequest_id");
    
    $datass = array(
    $cua => vp_getoption("wairtimepostvalue1"),
    $cppa => vp_getoption("wairtimepostvalue2"),
	$c1a => vp_getoption("wairtimepostvalue3"),
	$c2a => vp_getoption("wairtimepostvalue4"),
	$c3a => vp_getoption("wairtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head3");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("wairtimevalue1");
}
else{
	$the_auth_value = vp_getoption("wairtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("wairtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("wairtimehead1");
$auto = "$token $the_auth";

$awufairtime_array = [];
$awufairtime_array["Content-Type"] = "application/json";
$awufairtime_array["cache-control"] = "no-cache";
$awufairtime_array["Authorization"] = $auto;
for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
	if(!empty(vp_getoption("awufaddheaders$awufaddheaders")) && !empty(vp_getoption("awufaddvalue$awufaddheaders"))){
		$awufairtime_array[vp_getoption("awufaddheaders$awufaddheaders")] = vp_getoption("awufaddvalue$awufaddheaders");
	}
}



$http_args = array(
'headers' => $awufairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("wairtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
	
}





}
else{


if(strtolower(vp_getoption("awufaddendpoint")) == "no"){
$url = vp_getoption("wairtimebaseurl").vp_getoption("awufquerytext");
}
else{
$url = vp_getoption("wairtimebaseurl").vp_getoption("awufquerytext").vp_getvalue($the_airtime->request_id);
}

	$cua = vp_getoption("wairtimepostdata1");
    $cppa = vp_getoption("wairtimepostdata2");
    $c1a = vp_getoption("wairtimepostdata3");
    $c2a = vp_getoption("wairtimepostdata4");
    $c3a = vp_getoption("wairtimepostdata5");
    $cna = vp_getoption("wairtimenetworkattribute");
    $caa = vp_getoption("wairtimeamountattribute");
    $cpa = vp_getoption("wairtimephoneattribute");
	$uniqid = vp_getoption("warequest_id");
    
    $datass = array(
    $cua => vp_getoption("wairtimepostvalue1"),
    $cppa => vp_getoption("wairtimepostvalue2"),
	$c1a => vp_getoption("wairtimepostvalue3"),
	$c2a => vp_getoption("wairtimepostvalue4"),
	$c3a => vp_getoption("wairtimepostvalue5"),
	$uniqid => $the_airtime->request_id,
	);

$the_head =  vp_getoption("airtime_head3");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("wairtimevalue1");
}
else{
	$the_auth_value = vp_getoption("wairtimevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("wairtimesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("wairtimehead1");
$auto = "$token $the_auth";

$awufairtime_array = [];
$awufairtime_array["Content-Type"] = "application/json";
$awufairtime_array["cache-control"] = "no-cache";
$awufairtime_array["Authorization"] = $auto;
for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
	if(!empty(vp_getoption("awufaddheaders$awufaddheaders")) && !empty(vp_getoption("awufaddvalue$awufaddheaders"))){
		$awufairtime_array[vp_getoption("awufaddheaders$awufaddheaders")] = vp_getoption("awufaddvalue$awufaddheaders");
	}
}



$http_args = array(
'headers' => $awufairtime_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("airtime3_response_format") == "JSON" || vp_getoption("airtime3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("wairtimesuccessvalue"), vp_getoption("wairtimesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("wairtimesuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$data = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_airtime->request_id ];
$updated = $wpdb->update($airtime, $data, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}


}
	
}
	
	break;
	
	
	
	
}
	
}


//DATA
foreach($result_data as $the_data){

$cron_failed = vp_getoption("cron_failed");
$cron_successful = vp_getoption("cron_successful");


$type = strtolower(vp_getvalue($the_data->trans_type));

switch($type){
	case"sme":

if(!empty(vp_getoption("smequerytext"))){
if(strtolower(vp_getoption('smequerymethod')) == "get"){

$sc = vp_getoption("datasuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"databaseurl").vp_option_array($option_array,"smequerytext")."&".vp_option_array($option_array,"datapostdata1").'='.vp_option_array($option_array,"datapostvalue1")."&".vp_option_array($option_array,"arequest_id")."=".vp_getvalue($the_data->request_id)."&".vp_option_array($option_array,"datapostdata2").'='.vp_option_array($option_array,"datapostvalue2")."&".vp_option_array($option_array,"datapostdata3").'='.vp_option_array($option_array,"datapostvalue3")."&".vp_option_array($option_array,"datapostdata4").'='.vp_option_array($option_array,"datapostvalue4")."&".vp_option_array($option_array,"datapostdata5").'='.vp_option_array($option_array,"datapostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#dddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("datasuccessvalue"), vp_getoption("datasuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("datasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('smequerymethod')) == "get2"){

if(strtolower(vp_getoption("smeaddendpoint")) == "no"){
$url = vp_getoption("databaseurl").vp_getoption("smequerytext");
}
else{
$url = vp_getoption("databaseurl").vp_getoption("smequerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("datapostdata1");
    $cppa = vp_getoption("datapostdata2");
    $c1a = vp_getoption("datapostdata3");
    $c2a = vp_getoption("datapostdata4");
    $c3a = vp_getoption("datapostdata5");
    $cna = vp_getoption("datanetworkattribute");
    $caa = vp_getoption("dataamountattribute");
    $cpa = vp_getoption("dataphoneattribute");
	$uniqid = vp_getoption("arequest_id");
    
    $datass = array(
    $cua => vp_getoption("datapostvalue1"),
    $cppa => vp_getoption("datapostvalue2"),
	$c1a => vp_getoption("datapostvalue3"),
	$c2a => vp_getoption("datapostvalue4"),
	$c3a => vp_getoption("datapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("datavalue1");
}
else{
	$the_auth_value = vp_getoption("datavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("datasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("datahead1");
$auto = "$token $the_auth";

$smedata_array = [];
$smedata_array["cache-control"] = "no-cache";
$smedata_array["content-type"] = "application/json";
$smedata_array["Authorization"] = $auto;
for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
	if(!empty(vp_getoption("smeaddheaders$smeaddheaders")) && !empty(vp_getoption("smeaddvalue$smeaddheaders"))){
		$smedata_array[vp_getoption("smeaddheaders$smeaddheaders")] = vp_getoption("smeaddvalue$smeaddheaders");
	}
}



$http_args = array(
'headers' => $smedata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($datass)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("datasuccessvalue"), vp_getoption("datasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("datasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
else{

if(strtolower(vp_getoption("smeaddendpoint")) == "no"){
$url = vp_getoption("databaseurl").vp_getoption("smequerytext");
}
else{
$url = vp_getoption("databaseurl").vp_getoption("smequerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("datapostdata1");
    $cppa = vp_getoption("datapostdata2");
    $c1a = vp_getoption("datapostdata3");
    $c2a = vp_getoption("datapostdata4");
    $c3a = vp_getoption("datapostdata5");
    $cna = vp_getoption("datanetworkattribute");
    $caa = vp_getoption("dataamountattribute");
    $cpa = vp_getoption("dataphoneattribute");
	$uniqid = vp_getoption("arequest_id");
    
    $datass = array(
    $cua => vp_getoption("datapostvalue1"),
    $cppa => vp_getoption("datapostvalue2"),
	$c1a => vp_getoption("datapostvalue3"),
	$c2a => vp_getoption("datapostvalue4"),
	$c3a => vp_getoption("datapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("datavalue1");
}
else{
	$the_auth_value = vp_getoption("datavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("datasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("datahead1");
$auto = "$token $the_auth";

$smedata_array = [];
$smedata_array["cache-control"] = "no-cache";
$smedata_array["content-type"] = "application/json";
$smedata_array["Authorization"] = $auto;
for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
	if(!empty(vp_getoption("smeaddheaders$smeaddheaders")) && !empty(vp_getoption("smeaddvalue$smeaddheaders"))){
		$smedata_array[vp_getoption("smeaddheaders$smeaddheaders")] = vp_getoption("smeaddvalue$smeaddheaders");
	}
}



$http_args = array(
'headers' => $smedata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($datass)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#dddie(json_encode($obj));
}
else{
if(vp_getoption("data1_response_format") == "JSON" || vp_getoption("data1_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("datasuccessvalue"), vp_getoption("datasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("datasuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
}
	
	
	break;
	case"corporate":

if(!empty(vp_getoption("corporatequerytext"))){
if(strtolower(vp_getoption('corporatequerymethod')) == "get"){

$sc = vp_getoption("r2datasuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"r2databaseurl").vp_option_array($option_array,"corporatequerytext")."&".vp_option_array($option_array,"r2datapostdata1").'='.vp_option_array($option_array,"r2datapostvalue1")."&".vp_option_array($option_array,"r2request_id")."=".vp_getvalue($the_data->request_id)."&".vp_option_array($option_array,"r2datapostdata2").'='.vp_option_array($option_array,"r2datapostvalue2")."&".vp_option_array($option_array,"r2datapostdata3").'='.vp_option_array($option_array,"r2datapostvalue3")."&".vp_option_array($option_array,"r2datapostdata4").'='.vp_option_array($option_array,"r2datapostvalue4")."&".vp_option_array($option_array,"r2datapostdata5").'='.vp_option_array($option_array,"r2datapostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#ddddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("r2datasuccessvalue"), vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("r2datasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);

}
else{
#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('corporatequerymethod')) == "get2"){

if(strtolower(vp_getoption("corporateaddendpoint")) == "no"){
$url = vp_getoption("r2databaseurl").vp_getoption("corporatequerytext");
}
else{
$url = vp_getoption("r2databaseurl").vp_getoption("corporatequerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("r2datapostdata1");
    $cppa = vp_getoption("r2datapostdata2");
    $c1a = vp_getoption("r2datapostdata3");
    $c2a = vp_getoption("r2datapostdata4");
    $c3a = vp_getoption("r2datapostdata5");
    $cna = vp_getoption("r2datanetworkattribute");
    $caa = vp_getoption("r2dataamountattribute");
    $cpa = vp_getoption("r2dataphoneattribute");
	$uniqid = vp_getoption("r2request_id");
    
    $datass = array(
    $cua => vp_getoption("r2datapostvalue1"),
    $cppa => vp_getoption("r2datapostvalue2"),
	$c1a => vp_getoption("r2datapostvalue3"),
	$c2a => vp_getoption("r2datapostvalue4"),
	$c3a => vp_getoption("r2datapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head2");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("r2datavalue1");
}
else{
	$the_auth_value = vp_getoption("r2datavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("r2datasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("r2datahead1");
$auto = "$token $the_auth";

$corporatedata_array = [];
$corporatedata_array["Content-Type"] = "application/json";
$corporatedata_array["cache-control"] = "no-cache";
$corporatedata_array["Authorization"] = $auto;
for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
	if(!empty(vp_getoption("corporateaddheaders$corporateaddheaders")) && !empty(vp_getoption("corporateaddvalue$corporateaddheaders"))){
		$corporatedata_array[vp_getoption("corporateaddheaders$corporateaddheaders")] = vp_getoption("corporateaddvalue$corporateaddheaders");
	}
}



$http_args = array(
'headers' => $corporatedata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("r2datasuccessvalue"), vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("r2datasuccessvalue")){

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);
}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
else{


if(strtolower(vp_getoption("corporateaddendpoint")) == "no"){
$url = vp_getoption("r2databaseurl").vp_getoption("corporatequerytext");
}
else{
$url = vp_getoption("r2databaseurl").vp_getoption("corporatequerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("r2datapostdata1");
    $cppa = vp_getoption("r2datapostdata2");
    $c1a = vp_getoption("r2datapostdata3");
    $c2a = vp_getoption("r2datapostdata4");
    $c3a = vp_getoption("r2datapostdata5");
    $cna = vp_getoption("r2datanetworkattribute");
    $caa = vp_getoption("r2dataamountattribute");
    $cpa = vp_getoption("r2dataphoneattribute");
	$uniqid = vp_getoption("r2request_id");
    
    $datass = array(
    $cua => vp_getoption("r2datapostvalue1"),
    $cppa => vp_getoption("r2datapostvalue2"),
	$c1a => vp_getoption("r2datapostvalue3"),
	$c2a => vp_getoption("r2datapostvalue4"),
	$c3a => vp_getoption("r2datapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head2");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("r2datavalue1");
}
else{
	$the_auth_value = vp_getoption("r2datavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("r2datasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("r2datahead1");
$auto = "$token $the_auth";

$corporatedata_array = [];
$corporatedata_array["Content-Type"] = "application/json";
$corporatedata_array["cache-control"] = "no-cache";
$corporatedata_array["Authorization"] = $auto;
for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
	if(!empty(vp_getoption("corporateaddheaders$corporateaddheaders")) && !empty(vp_getoption("corporateaddvalue$corporateaddheaders"))){
		$corporatedata_array[vp_getoption("corporateaddheaders$corporateaddheaders")] = vp_getoption("corporateaddvalue$corporateaddheaders");
	}
}



$http_args = array(
'headers' => $corporatedata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("data2_response_format") == "JSON" || vp_getoption("data2_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("r2datasuccessvalue"), vp_getoption("r2datasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("r2datasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}


}
}	
	
	break;
	case"direct":

if(!empty(vp_getoption("directquerytext"))){
if(strtolower(vp_getoption('directquerymethod')) == "get"){

$sc = vp_getoption("rdatasuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"rdatabaseurl").vp_option_array($option_array,"directquerytext")."&".vp_option_array($option_array,"rdatapostdata1").'='.vp_option_array($option_array,"rdatapostvalue1")."&".vp_option_array($option_array,"warequest_id")."=".vp_getvalue($the_data->request_id)."&".vp_option_array($option_array,"rdatapostdata2").'='.vp_option_array($option_array,"rdatapostvalue2")."&".vp_option_array($option_array,"rdatapostdata3").'='.vp_option_array($option_array,"rdatapostvalue3")."&".vp_option_array($option_array,"rdatapostdata4").'='.vp_option_array($option_array,"rdatapostvalue4")."&".vp_option_array($option_array,"rdatapostdata5").'='.vp_option_array($option_array,"rdatapostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#ddddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("rdatasuccessvalue"), vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("rdatasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);

/*
$data = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $data, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('directquerymethod')) == "get2"){

if(strtolower(vp_getoption("directaddendpoint")) == "no"){
$url = vp_getoption("rdatabaseurl").vp_getoption("directquerytext");
}
else{
$url = vp_getoption("rdatabaseurl").vp_getoption("directquerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("rdatapostdata1");
    $cppa = vp_getoption("rdatapostdata2");
    $c1a = vp_getoption("rdatapostdata3");
    $c2a = vp_getoption("rdatapostdata4");
    $c3a = vp_getoption("rdatapostdata5");
    $cna = vp_getoption("rdatanetworkattribute");
    $caa = vp_getoption("rdataamountattribute");
    $cpa = vp_getoption("rdataphoneattribute");
	$uniqid = vp_getoption("rrequest_id");
    
    $datass = array(
    $cua => vp_getoption("rdatapostvalue1"),
    $cppa => vp_getoption("rdatapostvalue2"),
	$c1a => vp_getoption("rdatapostvalue3"),
	$c2a => vp_getoption("rdatapostvalue4"),
	$c3a => vp_getoption("rdatapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head3");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("rdatavalue1");
}
else{
	$the_auth_value = vp_getoption("rdatavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("rdatasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("rdatahead1");
$auto = "$token $the_auth";

$directdata_array = [];
$directdata_array["Content-Type"] = "application/json";
$directdata_array["cache-control"] = "no-cache";
$directdata_array["Authorization"] = $auto;
for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
	if(!empty(vp_getoption("directaddheaders$directaddheaders")) && !empty(vp_getoption("directaddvalue$directaddheaders"))){
		$directdata_array[vp_getoption("directaddheaders$directaddheaders")] = vp_getoption("directaddvalue$directaddheaders");
	}
}



$http_args = array(
'headers' => $directdata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("rdatasuccessvalue"), vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("rdatasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
else{


if(strtolower(vp_getoption("directaddendpoint")) == "no"){
$url = vp_getoption("rdatabaseurl").vp_getoption("directquerytext");
}
else{
$url = vp_getoption("rdatabaseurl").vp_getoption("directquerytext").vp_getvalue($the_data->request_id);
}

	$cua = vp_getoption("rdatapostdata1");
    $cppa = vp_getoption("rdatapostdata2");
    $c1a = vp_getoption("rdatapostdata3");
    $c2a = vp_getoption("rdatapostdata4");
    $c3a = vp_getoption("rdatapostdata5");
    $cna = vp_getoption("rdatanetworkattribute");
    $caa = vp_getoption("rdataamountattribute");
    $cpa = vp_getoption("rdataphoneattribute");
	$uniqid = vp_getoption("rrequest_id");
    
    $datass = array(
    $cua => vp_getoption("rdatapostvalue1"),
    $cppa => vp_getoption("rdatapostvalue2"),
	$c1a => vp_getoption("rdatapostvalue3"),
	$c2a => vp_getoption("rdatapostvalue4"),
	$c3a => vp_getoption("rdatapostvalue5"),
	$uniqid => $the_data->request_id,
	);

$the_head =  vp_getoption("data_head3");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("rdatavalue1");
}
else{
	$the_auth_value = vp_getoption("rdatavalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("rdatasuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("rdatahead1");
$auto = "$token $the_auth";

$directdata_array = [];
$directdata_array["Content-Type"] = "application/json";
$directdata_array["cache-control"] = "no-cache";
$directdata_array["Authorization"] = $auto;
for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
	if(!empty(vp_getoption("directaddheaders$directaddheaders")) && !empty(vp_getoption("directaddvalue$directaddheaders"))){
		$directdata_array[vp_getoption("directaddheaders$directaddheaders")] = vp_getoption("directaddvalue$directaddheaders");
	}
}



$http_args = array(
'headers' => $directdata_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false,
'body' => json_encode($datass)
);


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("data3_response_format") == "JSON" || vp_getoption("data3_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("rdatasuccessvalue"), vp_getoption("rdatasuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("rdatasuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ddata = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_data->request_id ];
$updated = $wpdb->update($data, $ddata, $where);


}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}


}
}	
	
	
	break;
	
	
	
	
}
	
}

//CABLE
foreach($result_cable as $the_cable){

$cron_failed = vp_getoption("cron_failed");
$cron_successful = vp_getoption("cron_successful");

if(!empty(vp_getoption("cablequerytext"))){
if(strtolower(vp_getoption('cablequerymethod')) == "get"){

$sc = vp_getoption("cablesuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"cablebaseurl").vp_option_array($option_array,"cablequerytext")."&".vp_option_array($option_array,"cablepostcable1").'='.vp_option_array($option_array,"cablepostvalue1")."&".vp_option_array($option_array,"crequest_id")."=".vp_getvalue($the_cable->request_id)."&".vp_option_array($option_array,"cablepostcable2").'='.vp_option_array($option_array,"cablepostvalue2")."&".vp_option_array($option_array,"cablepostcable3").'='.vp_option_array($option_array,"cablepostvalue3")."&".vp_option_array($option_array,"cablepostcable4").'='.vp_option_array($option_array,"cablepostvalue4")."&".vp_option_array($option_array,"cablepostcable5").'='.vp_option_array($option_array,"cablepostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#ddddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("cablesuccessvalue"), vp_getoption("cablesuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("cablesuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ccable = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_cable->request_id ];
$updated = $wpdb->update($cable, $ccable, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$ccable = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_cable->request_id ];
$updated = $wpdb->update($cable, $ccable, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('cablequerymethod')) == "get2"){

if(strtolower(vp_getoption("cableaddendpoint")) == "no"){
$url = vp_getoption("cablebaseurl").vp_getoption("cablequerytext");
}
else{
$url = vp_getoption("cablebaseurl").vp_getoption("cablequerytext").vp_getvalue($the_cable->request_id);
}

	$cua = vp_getoption("cablepostcable1");
    $cppa = vp_getoption("cablepostcable2");
    $c1a = vp_getoption("cablepostcable3");
    $c2a = vp_getoption("cablepostcable4");
    $c3a = vp_getoption("cablepostcable5");
	$uniqid = vp_getoption("crequest_id");
    
    $cabless = array(
    $cua => vp_getoption("cablepostvalue1"),
    $cppa => vp_getoption("cablepostvalue2"),
	$c1a => vp_getoption("cablepostvalue3"),
	$c2a => vp_getoption("cablepostvalue4"),
	$c3a => vp_getoption("cablepostvalue5"),
	$uniqid => $the_cable->crequest_id,
	);

$the_head =  vp_getoption("cable_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("cablevalue1");
}
else{
	$the_auth_value = vp_getoption("cablevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("cablesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("cablehead1");
$auto = "$token $the_auth";

$cable_array = [];
$cable_array["cache-control"] = "no-cache";
$cable_array["content-type"] = "application/json";
$cable_array["Authorization"] = $auto;
for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
	if(!empty(vp_getoption("cableaddheaders$cableaddheaders")) && !empty(vp_getoption("cableaddvalue$cableaddheaders"))){
		$cablecable_array[vp_getoption("cableaddheaders$cableaddheaders")] = vp_getoption("cableaddvalue$cableaddheaders");
	}
}



$http_args = array(
'headers' => $cable_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($cabless)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("cablesuccessvalue"), vp_getoption("cablesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("cablesuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ccable = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_cable->request_id ];
$updated = $wpdb->update($cable, $ccable, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}




}
else{

if(strtolower(vp_getoption("cableaddendpoint")) == "no"){
$url = vp_getoption("cablebaseurl").vp_getoption("cablequerytext");
}
else{
$url = vp_getoption("cablebaseurl").vp_getoption("cablequerytext").vp_getvalue($the_cable->request_id);
}

	$cua = vp_getoption("cablepostcable1");
    $cppa = vp_getoption("cablepostcable2");
    $c1a = vp_getoption("cablepostcable3");
    $c2a = vp_getoption("cablepostcable4");
    $c3a = vp_getoption("cablepostcable5");
	$uniqid = vp_getoption("crequest_id");
    
    $cabless = array(
    $cua => vp_getoption("cablepostvalue1"),
    $cppa => vp_getoption("cablepostvalue2"),
	$c1a => vp_getoption("cablepostvalue3"),
	$c2a => vp_getoption("cablepostvalue4"),
	$c3a => vp_getoption("cablepostvalue5"),
	$uniqid => $the_cable->crequest_id,
	);

$the_head =  vp_getoption("cable_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("cablevalue1");
}
else{
	$the_auth_value = vp_getoption("cablevalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("cablesuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("cablehead1");
$auto = "$token $the_auth";

$cable_array = [];
$cable_array["cache-control"] = "no-cache";
$cable_array["content-type"] = "application/json";
$cable_array["Authorization"] = $auto;
for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
	if(!empty(vp_getoption("cableaddheaders$cableaddheaders")) && !empty(vp_getoption("cableaddvalue$cableaddheaders"))){
		$cablecable_array[vp_getoption("cableaddheaders$cableaddheaders")] = vp_getoption("cableaddvalue$cableaddheaders");
	}
}



$http_args = array(
'headers' => $cable_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($cabless)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("cable_response_format") == "JSON" || vp_getoption("cable_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("cablesuccessvalue"), vp_getoption("cablesuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("cablesuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$ccable = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."" ];
$where = [ 'request_id' => $the_cable->request_id ];
$updated = $wpdb->update($cable, $ccable, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}



}
}	

}

//BILL
foreach($result_bill as $the_bill){

$cron_failed = vp_getoption("cron_failed");
$cron_successful = vp_getoption("cron_successful");

if(!empty(vp_getoption("billquerytext"))){
if(strtolower(vp_getoption('billquerymethod')) == "get"){

$sc = vp_getoption("billsuccesscode");

$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);


$url = vp_option_array($option_array,"billbaseurl").vp_option_array($option_array,"billquerytext")."&".vp_option_array($option_array,"billpostbill1").'='.vp_option_array($option_array,"billpostvalue1")."&".vp_option_array($option_array,"crequest_id")."=".vp_getvalue($the_bill->request_id)."&".vp_option_array($option_array,"billpostbill2").'='.vp_option_array($option_array,"billpostvalue2")."&".vp_option_array($option_array,"billpostbill3").'='.vp_option_array($option_array,"billpostvalue3")."&".vp_option_array($option_array,"billpostbill4").'='.vp_option_array($option_array,"billpostvalue4")."&".vp_option_array($option_array,"billpostbill5").'='.vp_option_array($option_array,"billpostvalue5");
	
$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);		

if(is_wp_error( $call )){
$error = $call->get_error_message();

#DO ERROR HERE;
#ddddie('{"status":"200","response":"'.$error.'"}');

}
else{
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("billsuccessvalue"), vp_getoption("billsuccessvalue2"));
}
else{
$en = $response ;
}
}

if($en == "TRUE" || $response  === vp_getoption("billsuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$bill_response = search_bill_token1(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}


$bbill = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."", 'meter_token' =>  $meter_token ];
$where = [ 'request_id' => $the_bill->request_id ];
$updated = $wpdb->update($bill, $bbill, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
/*
$bbill = [ 'status' => 'Failed' ];
$where = [ 'request_id' => $the_bill->request_id ];
$updated = $wpdb->update($bill, $bill, $where);
*/	
	
}

#END OF GET			
}
elseif(strtolower(vp_getoption('billquerymethod')) == "get2"){

if(strtolower(vp_getoption("billaddendpoint")) == "no"){
$url = vp_getoption("billbaseurl").vp_getoption("billquerytext");
}
else{
$url = vp_getoption("billbaseurl").vp_getoption("billquerytext").vp_getvalue($the_bill->request_id);
}

	$cua = vp_getoption("billpostbill1");
    $cppa = vp_getoption("billpostbill2");
    $c1a = vp_getoption("billpostbill3");
    $c2a = vp_getoption("billpostbill4");
    $c3a = vp_getoption("billpostbill5");
	$uniqid = vp_getoption("crequest_id");
    
    $billss = array(
    $cua => vp_getoption("billpostvalue1"),
    $cppa => vp_getoption("billpostvalue2"),
	$c1a => vp_getoption("billpostvalue3"),
	$c2a => vp_getoption("billpostvalue4"),
	$c3a => vp_getoption("billpostvalue5"),
	$uniqid => $the_bill->crequest_id,
	);

$the_head =  vp_getoption("bill_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("billvalue1");
}
else{
	$the_auth_value = vp_getoption("billvalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("billsuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("billhead1");
$auto = "$token $the_auth";

$bill_array = [];
$bill_array["cache-control"] = "no-cache";
$bill_array["content-type"] = "application/json";
$bill_array["Authorization"] = $auto;
for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
	if(!empty(vp_getoption("billaddheaders$billaddheaders")) && !empty(vp_getoption("billaddvalue$billaddheaders"))){
		$billbill_array[vp_getoption("billaddheaders$billaddheaders")] = vp_getoption("billaddvalue$billaddheaders");
	}
}



$http_args = array(
'headers' => $bill_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($billss)
);
	


$call =  wp_remote_get($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("billsuccessvalue"), vp_getoption("billsuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("billsuccessvalue")){


$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$bill_response = search_bill_token1(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}


$bbill = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."", 'meter_token' =>  $meter_token ];
$where = [ 'request_id' => $the_bill->request_id ];
$updated = $wpdb->update($bill, $bbill, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
	#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}





}
else{

if(strtolower(vp_getoption("billaddendpoint")) == "no"){
$url = vp_getoption("billbaseurl").vp_getoption("billquerytext");
}
else{
$url = vp_getoption("billbaseurl").vp_getoption("billquerytext").vp_getvalue($the_bill->request_id);
}

	$cua = vp_getoption("billpostbill1");
    $cppa = vp_getoption("billpostbill2");
    $c1a = vp_getoption("billpostbill3");
    $c2a = vp_getoption("billpostbill4");
    $c3a = vp_getoption("billpostbill5");
	$uniqid = vp_getoption("crequest_id");
    
    $billss = array(
    $cua => vp_getoption("billpostvalue1"),
    $cppa => vp_getoption("billpostvalue2"),
	$c1a => vp_getoption("billpostvalue3"),
	$c2a => vp_getoption("billpostvalue4"),
	$c3a => vp_getoption("billpostvalue5"),
	$uniqid => $the_bill->crequest_id,
	);

$the_head =  vp_getoption("bill_head");
if($the_head == "not_concatenated"){
	$the_auth = vp_getoption("billvalue1");
}
else{
	$the_auth_value = vp_getoption("billvalue1");
	$the_auth = base64_encode($the_auth_value);
}
$sc = vp_getoption("billsuccesscode");
//echo "<script>alert('url1".$url."');</script>";

$token = vp_getoption("billhead1");
$auto = "$token $the_auth";

$bill_array = [];
$bill_array["cache-control"] = "no-cache";
$bill_array["content-type"] = "application/json";
$bill_array["Authorization"] = $auto;
for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
	if(!empty(vp_getoption("billaddheaders$billaddheaders")) && !empty(vp_getoption("billaddvalue$billaddheaders"))){
		$billbill_array[vp_getoption("billaddheaders$billaddheaders")] = vp_getoption("billaddvalue$billaddheaders");
	}
}



$http_args = array(
'headers' => $bill_array,
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'blocking'=> true,
'body' => json_encode($billss)
);
	


$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);



if(is_wp_error($call)){
$error = $call->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = "$error";
#DO ERROR HERE
#ddddie(json_encode($obj));
}
else{
if(vp_getoption("bill_response_format") == "JSON" || vp_getoption("bill_response_format") == "json"){
$en = validate_response_query($response,$sc, vp_getoption("billsuccessvalue"), vp_getoption("billsuccessvalue2"));
}
else{
$en = $response ;
}
}


if($en == "TRUE"  || $response  === vp_getoption("billsuccessvalue")){

$cron_successful += 1;
#$cron_failed += 1;
vp_updateoption("cron_successful",$cron_successful);
#vp_updateoption("cron_failed",$cron_failed);

$bill_response = search_bill_token1(array_change_key_case(json_decode($response,true),CASE_LOWER),vp_getoption("metertoken"));

if(!empty($bill_response)){
	$meter_token = $bill_response[0];
}
else{
		$meter_token = "Nill";
}


$bbill = [ 'status' => 'Successful', 'resp_log' => "".esc_html(harray_key_first_query($response))."", 'meter_token' =>  $meter_token ];
$where = [ 'request_id' => $the_bill->request_id ];
$updated = $wpdb->update($bill, $bbill, $where);

}
else{
	#$cron_successful += 1;
$cron_failed += 1;
#vp_updateoption("cron_successful",$cron_successful);
vp_updateoption("cron_failed",$cron_failed);
}




}
}	

}






#}


}