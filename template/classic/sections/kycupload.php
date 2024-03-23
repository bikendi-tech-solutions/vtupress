<?php
error_reporting(0);
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm/",$referer) && !preg_match("/$nm\/wp-admin/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}

function verifyAccountName($actualName, $userInput) {
    // Convert both names to lowercase for a case-insensitive comparison
    $actualNameLower = strtolower($actualName);
    $userInputLower = strtolower($userInput);

    // Split the actual name into individual words
    $actualNameWords = explode(' ', $actualNameLower);

    // Check if each word in the user input exists in the actual name
    foreach (explode(' ', $userInputLower) as $inputWord) {
        $found = false;
        foreach ($actualNameWords as $actualWord) {
            // Check if the input word is a substring of any actual word
            if (strpos($actualWord, $inputWord) !== false) {
                $found = true;
                break;
            }
        }
        // If any word in the user input doesn't match any word in the actual name, return false
        if (!$found) {
            return false;
        }
    }

    // All words in the user input have a match in the actual name
    return true;
}



if(isset($_POST["method"])){
	$method = $_POST["method"];
if(isset($_POST["name"])  && !empty($_POST["name"])){
	
	if(isset($_POST["method"])  && !empty($_POST["method"])){
		$user_id = get_current_user_id();	

if($method == "nin" || $method == "bvn"){

if($method == "nin"){
	if(!isset($_POST["bvn_value"])){
		die("NIN Value Is Required");
	}
	elseif(empty($_POST["bvn_value"])){
		die("NIN value can't be empty");
	}
	$meth = "National Identical Number/Slip";

	$verTypeId = "nin";
}
elseif($method == "bvn"){

	if(!isset($_POST["bvn_value"])){
		die("BVN Value Is Required");
	}
	elseif(empty($_POST["bvn_value"])){
		die("BVN value can't be empty");
	}
	$meth = "Bank verification Number";
	$verTypeId = "bvn";
}


##############//IF BVN OR NIN DO MATH

	$name = $_POST["name"];
	$value = $_POST["bvn_value"];


#verify charge
$bvn_charge = intval(vp_getoption('bvn_verification_charge'));
$current_bal = intval(vp_getuser($user_id, "vp_bal",true));
$eraptor = vp_getoption('enable_raptor');



$userFullName = trim($_POST["name"]);

if(!preg_match('/\s/',$userFullName)){
	die("Please enter a full name");
}

$fn = explode(" ",$userFullName)[0];
$ln = explode(" ",$userFullName)[1];
$phone = vp_getuser($user_id, 'vp_phone', true);

if(!preg_match('/^[0-9]{11}/',$phone)){
	die("Your registered phone number must be 11 in numbers. Contact us to make modification");
}



if($eraptor == "yes"){

	if($bvn_charge > $current_bal && $bvn_charge != 0){
		die("NGN $bvn_charge is needed to complete this verification");
	}
//check for raptor details
$raptor_conid = vp_getoption('raptor_conid');
$raptor_apikey = vp_getoption('raptor_apikey');

if((empty($raptor_conid) || empty($raptor_apikey)) || (strtolower($raptor_conid) == "false" || strtolower($raptor_conid) == "false" )){
	die("{No Keys}- We can't verify at the moment.");
}


//datas
$datass = array(
	"verificationType" => $verTypeId,
	"firstName" => $fn,
	"lastName" => $ln,
	"phone" => $phone,
	"value" => $value
	);


$array = [];
$array["Authorization"] = "Token $raptor_apikey";
$array["cache-control"] = "no-cache";
$array["content-type"] = "application/json";
$array["connectionid"] = $raptor_conid;

$http_args = array(
    'headers' => $array,
    'timeout' => '300',
    'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'blocking'=> true,
    'body' => json_encode($datass)
    );

$url = "https://dashboard.raptor.ng/api/v1/verification/";

$call =  wp_remote_post($url, $http_args);
$response = wp_remote_retrieve_body($call);

$bvnDetails = json_decode($response);

if(!isset($bvnDetails->status)){
	print_r($response);
die("There is something wrong . Please reach out to us");
}
elseif(!$bvnDetails->status){
	$message = $bvnDetails->message;
	die("ERR - ".$message);
}else{
	//It Is Real!!!



	if($bvn_charge > 0){
	$new_bal_now = $current_bal - $bvn_charge;
	vp_updateuser($user_id,"vp_bal",$new_bal_now);
	}else{
	$bvn_charge = 0;
	$new_bal_now = $current_bal - $bvn_charge;
	}

	global $wpdb;
	$table_name = $wpdb->prefix.'vp_wallet';
$added_to_db = $wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> "Debited for $method verification during kyc",
'fund_amount' => $bvn_charge,
'before_amount' => $current_bal,
'now_amount' => $new_bal_now,
'user_id' => $user_id,
'status' => "approved",
'the_time' => date('Y-m-d h:i:s A')
));

	if(!$bvnDetails->nameMatch){
		die("NAME DOES NOT MATCH WITH VALUE!");
	}


	
	

	
	

}


}

	
$selfie = "#";
$proof = "#";



$name = $_POST["name"];
$kyc_update = array(
'name'=> $name,
'method'=> $meth,
'selfie' => $selfie,
'proof' => $proof,
'user_id' => $user_id,
'status' => 'verified',
'the_time' => current_time('mysql', 1)
);

if($eraptor != "yes"){
	$kyc_update["status"] = "review";
}

if($method == "bvn"){

	if($kyc_update["status"] == "verified"){
		vp_updateuser($user_id,"myBvn",$value);
	}
	vp_updateuser($user_id,"myBvnVal", $value);
}elseif($method == "nin"){
	if($kyc_update["status"] == "verified"){
		vp_updateuser($user_id,"myNin",$value);
	}
	vp_updateuser($user_id,"myNinVal", $value);
}
	

vp_updateuser($user_id,"vp_kyc_status", $kyc_update["status"] );

global $wpdb;
$table_name = $wpdb->prefix.'vp_kyc';
$wpdb->insert($table_name, $kyc_update);


if(vp_getoption("email_kyc") == "yes"){
$id = get_current_user_id();
$name = get_userdata(get_current_user_id())->user_login;
$subject = "[$name] - New KYC Notification ";
$message = "$name has just submitted his/her kyc credentials";
vp_admin_email($subject, $message,"kyc");

}


die("100");


}
elseif(isset($_FILES["file"])  && !empty($_FILES["file"])){
		if(isset($_FILES["fill"])  && !empty($_FILES["fill"])){
	
	
if($_FILES["file"]["error"] == 0){
	if($_FILES["fill"]["error"] == 0){
		
    $file_name = $_FILES["file"]["name"];
    $file_size = $_FILES["file"]["size"];
    $file_type = $_FILES["file"]["type"];
    $file_tmp = $_FILES["file"]["tmp_name"];
	
	$file_name2 = $_FILES["fill"]["name"];
    $file_size2 = $_FILES["fill"]["size"];
    $file_type2 = $_FILES["fill"]["type"];
    $file_tmp2 = $_FILES["fill"]["tmp_name"];
   
    $ext = pathinfo($file_name,PATHINFO_EXTENSION);
    $ext2 = pathinfo($file_name2,PATHINFO_EXTENSION);
    
    $exp_size = 5 * 1024 * 1024;
    
    $allowed_ext = array(
        "jpg" => "image/jpg",
        "jpeg" => "image/jpeg",
        "png" => "image/png"
        
        );
    
    if($file_size < $exp_size){
		
		    if($file_size2 < $exp_size){
        
        if(array_key_exists($ext,$allowed_ext)){ 
		
		if(array_key_exists($ext2,$allowed_ext)){

/*
		if(vp_getoption("vtupress_custom_kyc") == "yes"){
			$name = $_POST["name"];
			$accountNumber = $_POST["accountNumber"];
			$bankName = $_POST["bankName"];
			$bankCode = $_POST["bankCode"];

			$http_args = array(
				'headers' => array(
				'cache-control' => 'no-cache',
				'Content-Type' => 'application/json'
				),
				'timeout' => '300',
				'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
				'sslverify' => false
			);
					
			//paystack call
			$url = "https://vtupress.com/accountVerify.php?accountNumber=$accountNumber&bankCode=$bankCode";

			$call =  wp_remote_get($url, $http_args);
			$response = wp_remote_retrieve_body($call);

			$json_body = json_decode($response);

			$id = get_current_user_id();

			if(isset($json_body->status)){
				if($json_body->status == "true"){

					if(isset($json_body->data->account_name)){
						$accountName = $json_body->data->account_name;

					}else{
						die("Error Retrieving Account Name!!!");
					}


				}else{
					if(isset($json_body->message)){

						$error_message = $json_body->message;

						error_log($accountNumber." for ".$bankName." = Paystack throws ".$error_message);

						die("Account Details Incorrect!!!");

					}else{
						error_log($accountNumber." for ".$bankName."  = Paystack throws ".$response);
						die("Unknown PYSTK Error");
					}
				}

			}else{
				//monnify call


				$url = "https://vtupress.com/accountVerify2.php?verify=accountNumber&accountNumber=$accountNumber&bankCode=$bankCode";

				$call =  wp_remote_get($url, $http_args);
				$response = wp_remote_retrieve_body($call);
	
				$json_body = json_decode($response);

				
			if(isset($json_body->requestSuccessful)){
				if($json_body->requestSuccessful == "true"){

					if(isset($json_body->responseBody->accountName)){
						$accountName = $json_body->responseBody->accountName;

					}else{
						die("Error Retrieving Account Name!!!");
					}


				}else{
					if(isset($json_body->responseMessage)){

						$error_message = $json_body->responseMessage;

						error_log($accountNumber." for ".$bankName." = Monnify throws ".$error_message);

						die("Account Details Incorrect!!!");

					}else{
						error_log($accountNumber." for ".$bankName."  = Monnify throws ".$response);
						die("Unknown MNFY Error");
					}
				}

			}else{
				die("ERROR VERIFYING ACCOUNT NUMBER WITH ALL SERVERS");
			}

			}


			$fullname = vp_getuser($id,"first_name").' '.vp_getuser($id,"last_name");

if(!verifyAccountName($accountName,$name)){
	die("NAME MISMATCH! Use your personal account details that matches your registered user details");
}
elseif($name != $fullname){
	die("NAME MISMATCH! Do not edit the default user values");

}


		}
*/


if($method == "voters"){
	$meth = "Voters Card";
}
elseif($method == "drive"){
	$meth = "Driving License";
}
elseif($method == "pass"){
	$meth = "International Passport";
}
else{
	die("Could Not Identify Method Of Verification");
}






			$upload_overrides= array( 'test_form'=> false); 
            $return = media_handle_upload('file', 0); 
            $return2 = media_handle_upload('fill', 0); 
				 
			if(is_int($return) && is_int($return2)) { 	 

		$url_of_image = wp_get_attachment_url($return);
		$url_of_image2 = wp_get_attachment_url($return2);

		
		$selfie = $url_of_image;
		$proof = $url_of_image2;



		$name = $_POST["name"];
$kyc_update = array(
	'name'=> $name,
	'method'=> $meth,
	'selfie' => $selfie,
	'proof' => $proof,
	'user_id' => $user_id,
	'status' => 'review',
	'the_time' => current_time('mysql', 1)
);



global $wpdb;
$table_name = $wpdb->prefix.'vp_kyc';
$wpdb->insert($table_name, $kyc_update);


global $wpdb;
$table_name = $wpdb->prefix.'vp_profile';
	$wpdb->insert($table_name, array(
		'photo_link' => $selfie,
		'user_id' => $user_id,
		'the_time' => current_time('mysql', 1)
));

if(vp_getoption("email_kyc") == "yes"){
	$id = get_current_user_id();
	$name = get_userdata(get_current_user_id())->user_login;
	$subject = "[$name] - New KYC Notification ";
	$message = "$name has just submitted his/her kyc credentials";
	vp_admin_email($subject, $message,"kyc");
		
}

	
die("100");
			
			}
			else{
		var_dump($return);	
			}
			
			        }
        else{
            
            echo $ext2."NOT ALLOWED";
        }
			
        }
        else{
            
            echo $ext."NOT ALLOWED";
        }
		
		    }
    else{
        echo "$file_name2 FILE SIZE TOO HUGE";
    }
        
    }
    else{
        echo "$file_name FILE SIZE TOO HUGE";
    }
  
  }
else{
    
    echo "ERROR. FILL :". $_FILES["name"]["error"];
    
}
}
else{
    
    echo "ERROR. FILE:". $_FILES["name"]["error"];
    
}

}
else{

die("DOCUMENT PROOF Mustn't Be Empty");
	
}


}
else{

die("PASSPORT/SELFIE PHOTO Mustn't Be Empty");
	
}


}
else{

die("METHOD Mustn't Be Empty");
	
}

}
else{

die("NAME Mustn't Be Empty");
	
}



}
elseif(isset($_POST["action"])){
	$status = $_POST["status"];
	$where = ['user_id' => $_POST["id"] ];
$user_id = $_POST["id"];

if(!isset($_POST["doc"])){
	die("Doc Type Not Defined");
}
elseif(empty($_POST["doc"])){
	die("Doc Type Empty");
}

$doc = $_POST["doc"];

if($doc == "bvn"){
	$bvnVal = vp_getuser($user_id,"myBvnVal", true);
	if($status == "verified"){
		vp_updateuser($user_id,"myBvn",$bvnVal);
	}else{
		vp_updateuser($user_id,"myBvn","false");
	}
}
elseif($doc == "nin"){
	$ninVal = vp_getuser($user_id,"myNinVal", true);
	if($status == "verified"){
		vp_updateuser($user_id,"myNin",$ninVal);
	}else{
		vp_updateuser($user_id,"myNin","false");
	}
}





	$arr = ['status' => $status];



global $wpdb;
$table_name = $wpdb->prefix."vp_kyc";
$updated = $wpdb->update($table_name , $arr, $where);

vp_updateuser($_POST["id"],"vp_kyc_status", $status);
die("100");
}
elseif(isset($_POST["limit"])){
	$enable = $_POST["enable"];
	$duration = $_POST["duration"];
	$limit = $_POST["limit"];

$arr = ['enable' => $enable, 'duration' => $duration, 'kyc_limit' => $limit];
$where = ['id' => 1];
global $wpdb;
$table_name = $wpdb->prefix."vp_kyc_settings";
$updated = $wpdb->update($table_name , $arr, $where);

die("100");

}
else{
	die("NO ACTION");
}

?>