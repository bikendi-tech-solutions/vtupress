<?php
// API Endpoint
//header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
else{
include_once(ABSPATH ."wp-load.php");
}
if(WP_DEBUG == false){
error_reporting(0);
}
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}
if(preg_match('/vtpass/',vp_getoption("smilebaseurl"))){

    if(preg_match('/sandbox/',vp_getoption("smilebaseurl"))){
        $url = 'https://sandbox.vtpass.com/api/merchant-verify/smile/email';
    }else{
        $url = 'https://api-service.vtpass.com/api/merchant-verify/smile/email';
    }


    $smile_array = [];
	
	$the_head =  vp_getoption("smile_head");
	if($the_head == "not_concatenated"){
		$the_auth = vp_getoption("smilevalue1");
		$auto = vp_getoption("smilehead1").' '.$the_auth;
		$smile_array["Authorization"] = $auto;
	}
	elseif($the_head == "concatenated"){
		$the_auth_value = vp_getoption("smilevalue1");
		$the_auth = base64_encode($the_auth_value);
		$auto = vp_getoption("smilehead1").' '.$the_auth;
		$smile_array["Authorization"] = $auto;
	}
	else{
		$smile_array[vp_getoption("smilehead1")] = vp_getoption("smilevalue1");
	}

    	
	$smile_array["Content-Type"] = "application/json";
	$smile_array["cache-control"] = "no-cache";

    for($smileaddheaders=1; $smileaddheaders<=4; $smileaddheaders++){
		if(!empty(vp_getoption("smileaddheaders$smileaddheaders")) && !empty(vp_getoption("smileaddvalue$smileaddheaders"))){
			$smile_array[vp_getoption("smileaddheaders$smileaddheaders")] = vp_getoption("smileaddvalue$smileaddheaders");
		}
	}
	
    // Data to be sent in the request
$postData = array(
    'serviceID' => 'smile-direct',
    'billersCode' => $_POST["demail"]
);

	$http_args = array(
	'headers' => $smile_array,
	'timeout' => '300',
	'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
	'sslverify' => false,
	'body' => json_encode($postData)
	);
	

    $call =  wp_remote_post($url, $http_args);
    $response = wp_remote_retrieve_body($call);

$json = json_decode($response,true);
//data.content.AccountList.Account[0].AccountId;
if(isset( $json['content']['AccountList']['Account'][0]['AccountId'] )){
    $id = $json['content']['AccountList']['Account'][0]['AccountId'];
    $obj = new stdClass;
    $obj->status = "success";
    $obj->message = $id;
    die(json_encode($obj));
}else{
    $obj = new stdClass;
    $obj->status = "failed";
    $obj->message = $response;
    die(json_encode($obj));
}

}
else{
    $obj = new stdClass;
    $obj->status = "failed";
    $obj->message = "Verifyer Not Confirmed";
    die(json_encode($obj));
}
?>
