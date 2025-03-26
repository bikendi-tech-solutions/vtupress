<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

$allowed_referrers = [
    $_SERVER['SERVER_NAME']
];

$allowed_referrers = [
    $_SERVER['SERVER_NAME']
];

// Check if the referrer is set
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    
    // Check if the referrer is in the allowed list
    if (!in_array($referer, $allowed_referrers)) {
        die("REF ENT PERM");
    }
} else {
    die("BAD");
}


if(!is_user_logged_in()){
    die("Please Login");
}
elseif(!current_user_can("vtupress_admin")){
    die("Not Allowed");
}


if(!isset($_POST["spraycode"])){
    die("NO SPRAY CODE");
}
$spray_code = trim(htmlspecialchars($_POST["spraycode"]));
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
        // die("INVALID SPRAYCODE");
    }
}




$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'Content-Type' => 'application/json'
),
'timeout' => '120',
'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
'sslverify' => false
);
	
if(vp_getoption("vp_access_importer") == "yes"){
if(isset($_REQUEST["vtu_airtime_import"])){
	$vtu_airtime_select = $_REQUEST["vtu_airtime_select"];
	vp_updateoption("vtu_airtime_platform",$vtu_airtime_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?vtu_airtime_import=$vtu_airtime_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("vturesponse_id","$value");
		break;
		case"vtuquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("vtuquerytext",$query[0]);
				vp_updateoption("vtuquerymethod",$query[1]);
				vp_updateoption("vtuaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders1","$key2");
				vp_updateoption("vtuaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders2","$key2");
				vp_updateoption("vtuaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders3","$key2");
				vp_updateoption("vtuaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders4","$key2");
				vp_updateoption("vtuaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("airtimebaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("airtimeendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtimerequesttext","$key2");
				vp_updateoption("airtimerequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime1_response_format_text","$key2");
				vp_updateoption("airtime1_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("airtimeaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime_head","$key2");
				vp_updateoption("airtimehead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimepostdata1","$key2");
				vp_updateoption("airtimepostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimepostdata2","$key2");
				vp_updateoption("airtimepostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimepostdata3","$key2");
				vp_updateoption("airtimepostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimepostdata4","$key2");
				vp_updateoption("airtimepostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimepostdata5","$key2");
				vp_updateoption("airtimepostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("airtimeamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("airtimephoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("airtimenetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("arequest_id","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("airtimesuccesscode","$key2");
				vp_updateoption("airtimesuccessvalue","$value2");
			}
		break;
		case"altcode":
				vp_updateoption("airtimesuccessvalue2","$value");
		break;
		case"mtnattr":
				vp_updateoption("airtimemtn","$value");
		break;
		case"gloattr":
				vp_updateoption("airtimeglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("airtime9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("airtimeairtel","$value");
		break;
		case"vtuinfo":
			vp_updateoption("vtu_info","$value");
		break;
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["share_airtime_import"])){
	$share_airtime_select = $_REQUEST["share_airtime_select"];
	vp_updateoption("share_airtime_platform",$share_airtime_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?share_airtime_import=$share_airtime_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("shareresponse_id","$value");
		break;
		case"sharequery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("sharequerytext",$query[0]);
				vp_updateoption("sharequerymethod",$query[1]);
				vp_updateoption("shareaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders1","$key2");
				vp_updateoption("shareaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders2","$key2");
				vp_updateoption("shareaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders3","$key2");
				vp_updateoption("shareaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders4","$key2");
				vp_updateoption("shareaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("sairtimesuccessvalue2","$value");
		break;
		case"sharedinfo":
				vp_updateoption("shared_info","$value");
		break;
		case"shareadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders1","$key2");
				vp_updateoption("shareaddvalue1","$value2");
		}
		break;
		case"shareadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders2","$key2");
				vp_updateoption("shareaddvalue2","$value2");
		}
		break;
		case"shareadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders3","$key2");
				vp_updateoption("shareaddvalue3","$value2");
		}
		break;
		case"shareadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("shareaddheaders4","$key2");
				vp_updateoption("shareaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("sairtimebaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("sairtimeendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("sairtimerequesttext","$key2");
				vp_updateoption("sairtimerequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime2_response_format_text","$key2");
				vp_updateoption("airtime2_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("sairtimeaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime_head2","$key2");
				vp_updateoption("sairtimehead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimepostdata1","$key2");
				vp_updateoption("sairtimepostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimepostdata2","$key2");
				vp_updateoption("sairtimepostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimepostdata3","$key2");
				vp_updateoption("sairtimepostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimepostdata4","$key2");
				vp_updateoption("sairtimepostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimepostdata5","$key2");
				vp_updateoption("sairtimepostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("sairtimeamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("sairtimephoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("sairtimenetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("sarequest_id","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("sairtimesuccesscode","$key2");
				vp_updateoption("sairtimesuccessvalue","$value2");
			}
		break;
		case"mtnattr":
				vp_updateoption("sairtimemtn","$value");
		break;
		case"gloattr":
				vp_updateoption("sairtimeglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("sairtime9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("sairtimeairtel","$value");
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["awuf_airtime_import"])){
	$awuf_airtime_select = $_REQUEST["awuf_airtime_select"];
	vp_updateoption("awuf_airtime_platform",$awuf_airtime_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?awuf_airtime_import=$awuf_airtime_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("awufresponse_id","$value");
		break;
		case"awufquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("awufquerytext",$query[0]);
				vp_updateoption("awufquerymethod",$query[1]);
				vp_updateoption("awufaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders1","$key2");
				vp_updateoption("awufaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders2","$key2");
				vp_updateoption("awufaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders3","$key2");
				vp_updateoption("awufaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders4","$key2");
				vp_updateoption("awufaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("wairtimesuccessvalue2","$value");
		break;
		case"awufinfo":
				vp_updateoption("awuf_info","$value");
		break;
			case"awufadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders1","$key2");
				vp_updateoption("awufaddvalue1","$value2");
		}
		break;
		case"awufadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders2","$key2");
				vp_updateoption("awufaddvalue2","$value2");
		}
		break;
		case"awufadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders3","$key2");
				vp_updateoption("awufaddvalue3","$value2");
		}
		break;
		case"awufadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("awufaddheaders4","$key2");
				vp_updateoption("awufaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("wairtimebaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("wairtimeendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("wairtimerequesttext","$key2");
				vp_updateoption("wairtimerequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime3_response_format_text","$key2");
				vp_updateoption("airtime3_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("wairtimeaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("airtime_head3","$key2");
				vp_updateoption("wairtimehead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimepostdata1","$key2");
				vp_updateoption("wairtimepostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimepostdata2","$key2");
				vp_updateoption("wairtimepostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimepostdata3","$key2");
				vp_updateoption("wairtimepostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimepostdata4","$key2");
				vp_updateoption("wairtimepostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimepostdata5","$key2");
				vp_updateoption("wairtimepostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("wairtimeamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("wairtimephoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("wairtimenetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("warequest_id","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("wairtimesuccesscode","$key2");
				vp_updateoption("wairtimesuccessvalue","$value2");
			}
		break;
		case"mtnattr":
				vp_updateoption("wairtimemtn","$value");
		break;
		case"gloattr":
				vp_updateoption("wairtimeglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("wairtime9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("wairtimeairtel","$value");
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["sme_data_import"])){
	$sme_data_select = $_REQUEST["sme_data_select"];
	vp_updateoption("sme_data_platform",$sme_data_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?sme_data_import=$sme_data_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("smeresponse_id","$value");
		break;
		case"smequery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("smequerytext",$query[0]);
				vp_updateoption("smequerymethod",$query[1]);
				vp_updateoption("smeaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("smeaddheaders1","$key2");
				vp_updateoption("smeaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("smeaddheaders2","$key2");
				vp_updateoption("smeaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("smeaddheaders3","$key2");
				vp_updateoption("smeaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("smeaddheaders4","$key2");
				vp_updateoption("smeaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("datasuccessvalue2","$value");
		break;
		case"smevisible":
				vp_updateoption("sme_visible_networks","$value");
		break;
		case"smeinfo":
				vp_updateoption("sme_info","$value");
		break;
		case"baseurl":
		vp_updateoption("databaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("dataendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("datarequesttext","$key2");
				vp_updateoption("datarequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("data1_response_format_text","$key2");
				vp_updateoption("data1_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("dataaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("data_head","$key2");
				vp_updateoption("datahead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("datapostdata1","$key2");
				vp_updateoption("datapostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("datapostdata2","$key2");
				vp_updateoption("datapostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("datapostdata3","$key2");
				vp_updateoption("datapostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("datapostdata4","$key2");
				vp_updateoption("datapostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("datapostdata5","$key2");
				vp_updateoption("datapostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("dataamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("dataphoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("datanetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("request_id","$value");
		break;
		case"varattr":
				vp_updateoption("cvariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("datasuccesscode","$key2");
				vp_updateoption("datasuccessvalue","$value2");
			}
		break;
		case"mtnattr":
				vp_updateoption("datamtn","$value");
		break;
		case"gloattr":
				vp_updateoption("dataglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("data9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("dataairtel","$value");
		break;
		case"mtndata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("cdata".$now,"$key2");
				vp_updateoption("cdatan".$now,"$value2");
				$now++;
			}
		break;
		case"glodata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("gcdata".$now,"$key2");
				vp_updateoption("gcdatan".$now,"$value2");
				$now++;
			}
		break;
		case"9mobiledata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("9cdata".$now,"$key2");
				vp_updateoption("9cdatan".$now,"$value2");
				$now++;
			}
		break;
		case"airteldata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("acdata".$now,"$key2");
				vp_updateoption("acdatan".$now,"$value2");
				$now++;
			}
		break;
		case"smedatatype":
	
		foreach($value as $key2 => $value2){
switch($key2){
	case"attr":
		vp_updateoption("sme_datatype","$value2");
	break;
	case"MTN":
		vp_updateoption("mtn_sme_datatype","$value2");
	break;
	case"GLO":
		vp_updateoption("glo_sme_datatype","$value2");
	break;
	case"AIRTEL":
		vp_updateoption("airtel_sme_datatype","$value2");
	break;
	case"9MOBILE":
		vp_updateoption("9mobile_sme_datatype","$value2");
	break;
}
		
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["smile_data_import"])){
	$smile_data_select = $_REQUEST["smile_data_select"];
	vp_updateoption("smile_data_platform",$smile_data_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?smile_data_import=$smile_data_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("smileresponse_id","$value");
		break;
		case"smilequery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("smilequerytext",$query[0]);
				vp_updateoption("smilequerymethod",$query[1]);
				vp_updateoption("smileaddendpoint",$query[2]);
		}
		break;
		case"smileadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders1","$key2");
				vp_updateoption("smileaddvalue1","$value2");
		}
		break;
		case"smileadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders2","$key2");
				vp_updateoption("smileaddvalue2","$value2");
		}
		break;
		case"smileadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders3","$key2");
				vp_updateoption("smileaddvalue3","$value2");
		}
		break;
		case"smileadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders4","$key2");
				vp_updateoption("smileaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("smilesuccessvalue2","$value");
		break;
		case"smileinfo":
				vp_updateoption("smile_info","$value");
		break;
		case"smileattr":
			vp_updateoption("smileidattr","$value");
		break;
		case"smileextra1":
			vp_updateoption("smile_extra1","$value");
		break;
		case"smileadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders1","$key2");
				vp_updateoption("smileaddvalue1","$value2");
		}
		break;
		case"smileadd2":
			foreach($value as $key2 => $value2){
					vp_updateoption("smileaddheaders2","$key2");
					vp_updateoption("smileaddvalue2","$value2");
			}
		break;
		case"smileadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders3","$key2");
				vp_updateoption("smileaddvalue3","$value2");
		}
		break;
		case"smileadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("smileaddheaders4","$key2");
				vp_updateoption("smileaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("smilebaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("smileendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("smilerequesttext","$key2");
				vp_updateoption("smilerequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("smile1_response_format_text","$key2");
				vp_updateoption("smile1_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("smileaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("smile_head","$key2");
				vp_updateoption("smilehead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilepostdata1","$key2");
				vp_updateoption("smilepostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilepostdata2","$key2");
				vp_updateoption("smilepostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilepostdata3","$key2");
				vp_updateoption("smilepostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilepostdata4","$key2");
				vp_updateoption("smilepostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilepostdata5","$key2");
				vp_updateoption("smilepostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("smileamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("smilephoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("smilenetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("request_id","$value");
		break;
		case"varattr":
				vp_updateoption("smilevariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("smilesuccesscode","$key2");
				vp_updateoption("smilesuccessvalue","$value2");
			}
		break;
		case"smiledata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("csmiledata".$now,"$key2");
				vp_updateoption("csmiledatan".$now,"$value2");
				$now++;
			}
		break;
		case"smiledatatype":

		vp_updateoption("smile_datatype","$value");

		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["bet_import"])){
	$bet_select = $_REQUEST["bet_select"];
	vp_updateoption("bet_platform",$bet_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?bet_import=$bet_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("betresponse_id","$value");
		break;
		case"betquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("betquerytext",$query[0]);
				vp_updateoption("betquerymethod",$query[1]);
				vp_updateoption("betaddendpoint",$query[2]);
		}
		break;
		case"betadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders1","$key2");
				vp_updateoption("betaddvalue1","$value2");
		}
		break;
		case"betadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders2","$key2");
				vp_updateoption("betaddvalue2","$value2");
		}
		break;
		case"betadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders3","$key2");
				vp_updateoption("betaddvalue3","$value2");
		}
		break;
		case"betadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders4","$key2");
				vp_updateoption("betaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("betsuccessvalue2","$value");
		break;
		case"betinfo":
				vp_updateoption("bet_info","$value");
		break;
		case"betattr":
			vp_updateoption("betidattr","$value");
		break;
		case"betadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders1","$key2");
				vp_updateoption("betaddvalue1","$value2");
		}
		break;
		case"betadd2":
			foreach($value as $key2 => $value2){
					vp_updateoption("betaddheaders2","$key2");
					vp_updateoption("betaddvalue2","$value2");
			}
		break;
		case"betadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders3","$key2");
				vp_updateoption("betaddvalue3","$value2");
		}
		break;
		case"betadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("betaddheaders4","$key2");
				vp_updateoption("betaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("betbaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("betendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("betrequesttext","$key2");
				vp_updateoption("betrequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("bet1_response_format_text","$key2");
				vp_updateoption("bet1_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("betaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("bet_head","$key2");
				vp_updateoption("bethead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("betpostdata1","$key2");
				vp_updateoption("betpostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("betpostdata2","$key2");
				vp_updateoption("betpostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("betpostdata3","$key2");
				vp_updateoption("betpostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("betpostdata4","$key2");
				vp_updateoption("betpostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("betpostdata5","$key2");
				vp_updateoption("betpostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("betamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("betcustomeridattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("betcompanyattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("betrequest_id","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("betsuccesscode","$key2");
				vp_updateoption("betsuccessvalue","$value2");
			}
		break;
		case"betdata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("cbetdata".$now,"$key2");
				vp_updateoption("cbetdatan".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["alpha_data_import"])){
	$alpha_data_select = $_REQUEST["alpha_data_select"];
	vp_updateoption("alpha_data_platform",$alpha_data_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?alpha_data_import=$alpha_data_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("alpharesponse_id","$value");
		break;
		case"alphaquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("alphaquerytext",$query[0]);
				vp_updateoption("alphaquerymethod",$query[1]);
				vp_updateoption("alphaaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders1","$key2");
				vp_updateoption("alphaaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders2","$key2");
				vp_updateoption("alphaaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders3","$key2");
				vp_updateoption("alphaaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders4","$key2");
				vp_updateoption("alphaaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("alphasuccessvalue2","$value");
		break;
		case"alphainfo":
				vp_updateoption("alpha_info","$value");
		break;
		case"alphaattr":
			vp_updateoption("alphaidattr","$value");
		break;
		case"alphaextra1":
			vp_updateoption("alpha_extra1","$value");
		break;
		case"alphaadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders1","$key2");
				vp_updateoption("alphaaddvalue1","$value2");
		}
		break;
		case"alphaadd2":
			foreach($value as $key2 => $value2){
					vp_updateoption("alphaaddheaders2","$key2");
					vp_updateoption("alphaaddvalue2","$value2");
			}
		break;
		case"alphaadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders3","$key2");
				vp_updateoption("alphaaddvalue3","$value2");
		}
		break;
		case"alphaadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("alphaaddheaders4","$key2");
				vp_updateoption("alphaaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("alphabaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("alphaendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("alpharequesttext","$key2");
				vp_updateoption("alpharequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("alpha1_response_format_text","$key2");
				vp_updateoption("alpha1_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("alphaaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("alpha_head","$key2");
				vp_updateoption("alphahead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphapostdata1","$key2");
				vp_updateoption("alphapostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphapostdata2","$key2");
				vp_updateoption("alphapostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphapostdata3","$key2");
				vp_updateoption("alphapostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphapostdata4","$key2");
				vp_updateoption("alphapostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphapostdata5","$key2");
				vp_updateoption("alphapostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("alphaamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("alphaphoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("alphanetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("request_id","$value");
		break;
		case"varattr":
				vp_updateoption("alphavariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("alphasuccesscode","$key2");
				vp_updateoption("alphasuccessvalue","$value2");
			}
		break;
		case"alphadata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("calphadata".$now,"$key2");
				vp_updateoption("calphadatan".$now,"$value2");
				$now++;
			}
		break;
		case"alphadatatype":

		vp_updateoption("alpha_datatype","$value");

		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["direct_data_import"])){
	$direct_data_select = $_REQUEST["direct_data_select"];
	vp_updateoption("direct_data_platform",$direct_data_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?direct_data_import=$direct_data_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"directdatatype":
	
			foreach($value as $key2 => $value2){
	switch($key2){
		case"attr":
			vp_updateoption("direct_datatype","$value2");
		break;
		case"MTN":
			vp_updateoption("mtn_direct_datatype","$value2");
		break;
		case"GLO":
			vp_updateoption("glo_direct_datatype","$value2");
		break;
		case"AIRTEL":
			vp_updateoption("airtel_direct_datatype","$value2");
		break;
		case"9MOBILE":
			vp_updateoption("9mobile_direct_datatype","$value2");
		break;
	}
			
				}
			break;
		case"response_id":
				vp_updateoption("directresponse_id","$value");
		break;
		case"directquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("directquerytext",$query[0]);
				vp_updateoption("directquerymethod",$query[1]);
				vp_updateoption("directaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders1","$key2");
				vp_updateoption("directaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders2","$key2");
				vp_updateoption("directaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders3","$key2");
				vp_updateoption("directaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders4","$key2");
				vp_updateoption("directaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("rdatasuccessvalue2","$value");
		break;
		case"directvisible":
				vp_updateoption("direct_visible_networks","$value");
		break;
		case"directinfo":
				vp_updateoption("direct_info","$value");
		break;
				case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders1","$key2");
				vp_updateoption("directaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders2","$key2");
				vp_updateoption("directaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders3","$key2");
				vp_updateoption("directaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("directaddheaders4","$key2");
				vp_updateoption("directaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("rdatabaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("rdataendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("rdatarequesttext","$key2");
				vp_updateoption("rdatarequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("data2_response_format_text","$key2");
				vp_updateoption("data2_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("rdataaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("data_head2","$key2");
				vp_updateoption("rdatahead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatapostdata1","$key2");
				vp_updateoption("rdatapostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatapostdata2","$key2");
				vp_updateoption("rdatapostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatapostdata3","$key2");
				vp_updateoption("rdatapostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatapostdata4","$key2");
				vp_updateoption("rdatapostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatapostdata5","$key2");
				vp_updateoption("rdatapostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("rdataamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("rdataphoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("rdatanetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("rrequest_id","$value");
		break;
		case"varattr":
				vp_updateoption("rcvariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("rdatasuccesscode","$key2");
				vp_updateoption("rdatasuccessvalue","$value2");
			}
		break;
		case"mtnattr":
				vp_updateoption("rdatamtn","$value");
		break;
		case"gloattr":
				vp_updateoption("rdataglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("rdata9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("rdataairtel","$value");
		break;
		case"mtndata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("rcdata".$now,"$key2");
				vp_updateoption("rcdatan".$now,"$value2");
				$now++;
			}
		break;
		case"glodata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("rgcdata".$now,"$key2");
				vp_updateoption("rgcdatan".$now,"$value2");
				$now++;
			}
		break;
		case"9mobiledata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("r9cdata".$now,"$key2");
				vp_updateoption("r9cdatan".$now,"$value2");
				$now++;
			}
		break;
		case"airteldata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("racdata".$now,"$key2");
				vp_updateoption("racdatan".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["corporate_data_import"])){
	$corporate_data_select = $_REQUEST["corporate_data_select"];
	vp_updateoption("corporate_data_platform",$corporate_data_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?corporate_data_import=$corporate_data_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"corporatedatatype":
	
			foreach($value as $key2 => $value2){
	switch($key2){
		case"attr":
			vp_updateoption("corporate_datatype","$value2");
		break;
		case"MTN":
			vp_updateoption("mtn_corporate_datatype","$value2");
		break;
		case"GLO":
			vp_updateoption("glo_corporate_datatype","$value2");
		break;
		case"AIRTEL":
			vp_updateoption("airtel_corporate_datatype","$value2");
		break;
		case"9MOBILE":
			vp_updateoption("9mobile_corporate_datatype","$value2");
		break;
	}
			
				}
			break;
		case"response_id":
				vp_updateoption("corporateresponse_id","$value");
		break;
		case"corporatequery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("corporatequerytext",$query[0]);
				vp_updateoption("corporatequerymethod",$query[1]);
				vp_updateoption("corporateaddendpoint",$query[2]);
		}
		break;
		case"altcode":
				vp_updateoption("r2datasuccessvalue2","$value");
		break;
		case"corporatevisible":
				vp_updateoption("corporate_visible_networks","$value");
		break;
		case"corporateinfo":
				vp_updateoption("corporate_info","$value");
		break;
			case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("corporateaddheaders1","$key2");
				vp_updateoption("corporateaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("corporateaddheaders2","$key2");
				vp_updateoption("corporateaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("corporateaddheaders3","$key2");
				vp_updateoption("corporateaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("corporateaddheaders4","$key2");
				vp_updateoption("corporateaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("r2databaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("r2dataendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("r2datarequesttext","$key2");
				vp_updateoption("r2datarequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("data3_response_format_text","$key2");
				vp_updateoption("data3_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("r2dataaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("data_head3","$key2");
				vp_updateoption("r2datahead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datapostdata1","$key2");
				vp_updateoption("r2datapostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datapostdata2","$key2");
				vp_updateoption("r2datapostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datapostdata3","$key2");
				vp_updateoption("r2datapostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datapostdata4","$key2");
				vp_updateoption("r2datapostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datapostdata5","$key2");
				vp_updateoption("r2datapostvalue5","$value2");
			}
		break;
		case"amountattr":
				vp_updateoption("r2dataamountattribute","$value");
		break;
		case"phoneattr":
				vp_updateoption("r2dataphoneattribute","$value");
		break;
		case"networkattr":
				vp_updateoption("r2datanetworkattribute","$value");
		break;
		case"requestattr":
				vp_updateoption("r2request_id","$value");
		break;
		case"varattr":
				vp_updateoption("r2cvariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("r2datasuccesscode","$key2");
				vp_updateoption("r2datasuccessvalue","$value2");
			}
		break;
		case"mtnattr":
				vp_updateoption("r2datamtn","$value");
		break;
		case"gloattr":
				vp_updateoption("r2dataglo","$value");
		break;
		case"9mobileattr":
				vp_updateoption("r2data9mobile","$value");
		break;
		case"airtelattr":
				vp_updateoption("r2dataairtel","$value");
		break;
		case"mtndata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("r2cdata".$now,"$key2");
				vp_updateoption("r2cdatan".$now,"$value2");
				$now++;
			}
		break;
		case"glodata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("r2gcdata".$now,"$key2");
				vp_updateoption("r2gcdatan".$now,"$value2");
				$now++;
			}
		break;
		case"9mobiledata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("r29cdata".$now,"$key2");
				vp_updateoption("r29cdatan".$now,"$value2");
				$now++;
			}
		break;
		case"airteldata":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("r2acdata".$now,"$key2");
				vp_updateoption("r2acdatan".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["cable_import"])){
	$cable_select = $_REQUEST["cable_select"];
	vp_updateoption("cable_platform",$cable_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?cable_import=$cable_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"response_id":
				vp_updateoption("cableresponse_id","$value");
		break;
		case"cablequery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("cablequerytext",$query[0]);
				vp_updateoption("cablequerymethod",$query[1]);
				vp_updateoption("cableaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders1","$key2");
				vp_updateoption("cableaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders2","$key2");
				vp_updateoption("cableaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders3","$key2");
				vp_updateoption("cableaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders4","$key2");
				vp_updateoption("cableaddvalue4","$value2");
		}
		break;
		case"altcode":
				vp_updateoption("cablesuccessvalue2","$value");
		break;
		case"cableinfo":
				vp_updateoption("cable_info","$value");
		break;		
	case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders1","$key2");
				vp_updateoption("cableaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders2","$key2");
				vp_updateoption("cableaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders3","$key2");
				vp_updateoption("cableaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("cableaddheaders4","$key2");
				vp_updateoption("cableaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("cablebaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("cableendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("cablerequesttext","$key2");
				vp_updateoption("cablerequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("cable_response_format_text","$key2");
				vp_updateoption("cable_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("cableaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("cable_head","$key2");
				vp_updateoption("cablehead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablepostdata1","$key2");
				vp_updateoption("cablepostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablepostdata2","$key2");
				vp_updateoption("cablepostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablepostdata3","$key2");
				vp_updateoption("cablepostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablepostdata4","$key2");
				vp_updateoption("cablepostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablepostdata5","$key2");
				vp_updateoption("cablepostvalue5","$value2");
			}
		break;
		case"typeattr":
				vp_updateoption("ctypeattr","$value");
		break;
		case"iucattr":
				vp_updateoption("ciucattr","$value");
		break;
		case"requestattr":
				vp_updateoption("crequest_id","$value");
		break;
		case"varattr":
				vp_updateoption("ccvariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("cablesuccesscode","$key2");
				vp_updateoption("cablesuccessvalue","$value2");
			}
		break;
		case"cables":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("cableid".$now,"$key2");
				$now++;
			}
		break;
		case"plans":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("ccable".$now,"$key2");
				vp_updateoption("ccablen".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["bill_import"])){
	$bill_select = $_REQUEST["bill_select"];
	vp_updateoption("bill_platform",$bill_select);
$response =  file_get_contents("https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?bill_import=$bill_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"altcode":
			vp_updateoption("billsuccessvalue2","$value");
		break;
		case"response_id":
				vp_updateoption("billresponse_id","$value");
		break;
		case"billquery":
		foreach($value as $key2 => $value2){
			$query = explode(',',$value2);
				vp_updateoption("billquerytext",$query[0]);
				vp_updateoption("billquerymethod",$query[1]);
				vp_updateoption("billaddendpoint",$query[2]);
		}
		break;
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders1","$key2");
				vp_updateoption("billaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders2","$key2");
				vp_updateoption("billaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders3","$key2");
				vp_updateoption("billaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders4","$key2");
				vp_updateoption("billaddvalue4","$value2");
		}
		break;
		case"billinfo":
				vp_updateoption("bill_info","$value");
		break;
		case"tokenattr":
				vp_updateoption("metertoken","$value");
		break;
			case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders1","$key2");
				vp_updateoption("billaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders2","$key2");
				vp_updateoption("billaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders3","$key2");
				vp_updateoption("billaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("billaddheaders4","$key2");
				vp_updateoption("billaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("billbaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("billendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("billrequesttext","$key2");
				vp_updateoption("billrequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("bill_response_format_text","$key2");
				vp_updateoption("bill_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("billaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("bill_head","$key2");
				vp_updateoption("billhead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("billpostdata1","$key2");
				vp_updateoption("billpostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("billpostdata2","$key2");
				vp_updateoption("billpostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("billpostdata3","$key2");
				vp_updateoption("billpostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("billpostdata4","$key2");
				vp_updateoption("billpostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("billpostdata5","$key2");
				vp_updateoption("billpostvalue5","$value2");
			}
		break;
		case"typeattr":
				vp_updateoption("btypeattr","$value");
		break;
		case"amountattr":
				vp_updateoption("billamountattribute","$value");
		break;
		case"meterattr":
				vp_updateoption("cmeterattr","$value");
		break;
		case"requestattr":
				vp_updateoption("brequest_id","$value");
		break;
		case"varattr":
				vp_updateoption("cbvariationattr","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("billsuccesscode","$key2");
				vp_updateoption("billsuccessvalue","$value2");
			}
		break;
		case"bills":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("billid".$now,"$key2");
				vp_updateoption("billname".$now,"$value2");
				$now++;
			}
		break;
		case"plans":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("cbill".$now,"$key2");
				vp_updateoption("cbilln".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}
elseif(isset($_REQUEST["sms_import"])){
	$sms_select = $_REQUEST["sms_select"];
	vp_updateoption("sms_platform",$sms_select);
$response =  file_get_contents("https:///wp-content/plugins/vpimporter/vpimporter.php?sms_import=$sms_select");
$json = json_decode($response, true);

if($json["available"] == "yes"){
foreach($json as $key => $value){
	switch($key){
		case"vtuadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders1","$key2");
				vp_updateoption("vtuaddvalue1","$value2");
		}
		break;
		case"vtuadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders2","$key2");
				vp_updateoption("vtuaddvalue2","$value2");
		}
		break;
		case"vtuadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders3","$key2");
				vp_updateoption("vtuaddvalue3","$value2");
		}
		break;
		case"vtuadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("vtuaddheaders4","$key2");
				vp_updateoption("vtuaddvalue4","$value2");
		}
		break;
	case"altcode":
				vp_updateoption("smssuccessvalue2","$value");
		break;
		case"smsinfo":
				vp_updateoption("sms_info","$value");
		break;
				case"smsadd1":
		foreach($value as $key2 => $value2){
				vp_updateoption("smsaddheaders1","$key2");
				vp_updateoption("smsaddvalue1","$value2");
		}
		break;
		case"smsadd2":
		foreach($value as $key2 => $value2){
				vp_updateoption("smsaddheaders2","$key2");
				vp_updateoption("smsaddvalue2","$value2");
		}
		break;
		case"smsadd3":
		foreach($value as $key2 => $value2){
				vp_updateoption("smsaddheaders3","$key2");
				vp_updateoption("smsaddvalue3","$value2");
		}
		break;
		case"smsadd4":
		foreach($value as $key2 => $value2){
				vp_updateoption("smsaddheaders4","$key2");
				vp_updateoption("smsaddvalue4","$value2");
		}
		break;
		case"baseurl":
		vp_updateoption("smsbaseurl","$value");
		break;
		case"endpoint":
		vp_updateoption("smsendpoint","$value");
		break;
		case"request":
		foreach($value as $key2 => $value2){
				vp_updateoption("smsrequesttext","$key2");
				vp_updateoption("smsrequest","$value2");
			}
		break;
		case"response":
		foreach($value as $key2 => $value2){
				vp_updateoption("sms_response_format_text","$key2");
				vp_updateoption("sms_response_format","$value2");
			}
		break;
		case"addpost":
		vp_updateoption("smsaddpost","$value");
		break;
		case"head":
		foreach($value as $key2 => $value2){
				vp_updateoption("sms_head","$key2");
				vp_updateoption("smshead1","$value2");
			}
		break;
		case"postdata1":
			foreach($value as $key2 => $value2){
				vp_updateoption("smspostdata1","$key2");
				vp_updateoption("smspostvalue1","$value2");
			}
		break;
		case"postdata2":
			foreach($value as $key2 => $value2){
				vp_updateoption("smspostdata2","$key2");
				vp_updateoption("smspostvalue2","$value2");
			}
		break;
		case"postdata3":
			foreach($value as $key2 => $value2){
				vp_updateoption("smspostdata3","$key2");
				vp_updateoption("smspostvalue3","$value2");
			}
		break;
		case"postdata4":
			foreach($value as $key2 => $value2){
				vp_updateoption("smspostdata4","$key2");
				vp_updateoption("smspostvalue4","$value2");
			}
		break;
		case"postdata5":
			foreach($value as $key2 => $value2){
				vp_updateoption("smspostdata5","$key2");
				vp_updateoption("smspostvalue5","$value2");
			}
		break;
		case"message":
				vp_updateoption("messageattr","$value");
		break;
		case"sender":
				vp_updateoption("senderattr","$value");
		break;
		case"receiver":
				vp_updateoption("receiverattr","$value");
		break;
		case"character":
				vp_updateoption("smscharacter","$value");
		break;
		case"amountattr":
				vp_updateoption("smsamountattribute","$value");
		break;
		case"meterattr":
				vp_updateoption("cmeterattr","$value");
		break;
		case"requestattr":
				vp_updateoption("smsrequest_id","$value");
		break;
		case"successcode":
			foreach($value as $key2 => $value2){
				vp_updateoption("smssuccesscode","$key2");
				vp_updateoption("smssuccessvalue","$value2");
			}
		break;
		case"flash":
			foreach($value as $key2 => $value2){
				vp_updateoption("flashattr","$key2");
				vp_updateoption("flash_value","$value2");
			}
		break;
		case"plans":
		$now = 0;
		foreach($value as $key2 => $value2){
				vp_updateoption("csms".$now,"$key2");
				vp_updateoption("csmsn".$now,"$value2");
				$now++;
			}
		break;
		
		
	}
	
}
die("100");

}
else{
die("101");
}
	
}

else{
echo'{"status":"200"}';		
}

}
else{
	echo'{"status":"200","response":"You Need To Add This Website Url On The Field Next To The \'Add Url\' Field On Your Vtupress Account"}';	
}