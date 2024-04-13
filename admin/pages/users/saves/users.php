<?php
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

global $time;



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
      //   die("INVALID SPRAYCODE");
    }
}elseif(strtolower($spray_code) == "false"){
    if($real_code == "false"){
        $cur_id = get_current_user_id();
        $update_code = uniqid("vtu_$cur_id");
        vp_updateoption("spraycode",$update_code);
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
      //   die("INVALID SPRAYCODE");
    }
}




$time = date('mis');

function generateRandomSyllable() {
    $syllables = ['ab', 'ac', 'ad', 'ba', 'be', 'ca', 'ce', 'da', 'de', 'do', 'fa', 'fi', 'ga', 'ge', 'go', 'ha', 'he', 'hi', 'ja', 'jo', 'ka', 'ko', 'la', 'le', 'li', 'lo', 'ma', 'me', 'mi', 'mo', 'na', 'ne', 'ni', 'no', 'pa', 'pe', 'pi', 'po', 'ra', 're', 'ri', 'ro', 'sa', 'se', 'si', 'so', 'ta', 'te', 'ti', 'to', 'va', 've', 'vi', 'vo', 'wa', 'we', 'wi', 'wo', 'ya', 'yo', 'za', 'ze', 'zi', 'zo'];
    return $syllables[rand(0, count($syllables) - 1)];
}

function generateRandomEmail() {
    global $time;
    $email = generateRandomSyllable() . generateRandomSyllable() . generateRandomSyllable() .$time. '@gmail.com';
    return $email;
}


$numberOfEmails = 1; // Change this to generate more or fewer emails

function create_email($numberOfEmails = 1){
for ($i = 0; $i < $numberOfEmails; $i++) {
    $the_email = generateRandomEmail();
}

return  $the_email;
}



if(isset($_POST["userid"])){
    //global $wp_query;
    $ddid = $_POST["userid"];

    
    switch($_POST["action"]){
        case"mban":
global $wpdb;
            $hid = explode(",", $ddid);
            foreach($hid as $hd){
                if(!empty($hd)){
                    vp_updateuser($hd,'vp_user_access',"ban");
                    $data = [ 'vp_ban' => "ban" ];
                    $where = [ 'id' => $hd ];
                    $updated = $wpdb->update( $wpdb->prefix.'users', $data, $where);
                        
                }
            }
die("100");
        break;
        case"munban":
            global $wpdb;
            $hid = explode(",", $ddid);
            foreach($hid as $hd){
                if(!empty($hd)){
                    vp_updateuser($hd,'vp_user_access',"access");
                    $data = [ 'vp_ban' => "access" ];
                    $where = [ 'id' => $hd ];
                    $updated = $wpdb->update( $wpdb->prefix.'users', $data, $where);
                        
                }
            }
die("100");

        break;
        case"mrundan_vpay":
            global $wpdb;
            $hid = explode(",", $ddid);

            $gateways = "";
     


    if(vp_getoption('enablevpay') == "yes"){
        $gateways .= "VPAY";
        $did = "yes";

        $total = 0;


        $vpublickey = vp_getoption("vpay_public");
        $vusername = vp_getoption("vpay_email");
        $vpassword = vp_getoption("vpay_password");

            foreach($hid as $hd){

                if(!empty($hd)){
                    $userid = $hd;

                   $username = get_userdata($hd)->user_login;
                   $email = get_userdata($hd)->user_email;
                   $fun = vp_getuser($hd,"first_name",true);
                   $lun = vp_getuser($hd,"last_name",true);

            //sk_627c407a788c25602fbf36fdf99d5e888644766031
            //pk_627c407a788c25602fbd2c939f993a8ce3507d0042

                $public_key = $vpublickey;





$unid = uniqid();
$genemail = create_email();
$num = "0".rand(7,9)."0".rand(11111111,99999999);

$sub = uniqid();





###################
#####
##### --- Do Login
#####
###################

// API endpoint URL
$url = 'https://services2.vpay.africa/api/service/v1/query/merchant/login';

// Data to be sent in the request
$data = array(
    'username' => $vusername,
    'password' => $vpassword
);

$headers = array(
    'Content-Type: application/json', // Assuming JSON data is being sent
    'publicKey: '.$vpublickey // Example Authorization header
);



// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set headers


// Execute cURL session
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Handle response
$apikey = json_decode($response);

vp_addoption("vpay_apitoken","");

$token = (isset($apikey->token) == true)?  $apikey->token : "";

if(!empty($token)){
    vp_updateoption("vpay_apitoken",$token);
}else{
    $token = vp_getoption("vpay_apitoken");
}


}

// Close cURL session
curl_close($ch);
$token = vp_getoption("vpay_apitoken");

if(empty($token)){
    die("Tokenization Error|First Login Call");
}


##########################
######
###### --- SECOND ENDPOINT TO GET THE ID
######
##########################


// API endpoint URL
$url = 'https://services2.vpay.africa/api/service/v1/query/customer/add';

$customer_firstN = $fun;
$customer_lastN = $lun;
$ref = uniqid();
$customer_phone = $num;
$customer_email = $email;

// Data to be sent in the request
$data = array(
    'email' => $customer_email,
    'phone' => $customer_phone,
    'contactfirstname' => $customer_firstN,
    'contactlastname' => $customer_lastN
);

$headers = array(
    'Content-Type: application/json', // Assuming JSON data is being sent
    'publicKey: '.$public_key, // Example Authorization header
    'b-access-token: '.$token // Example Authorization header
);



// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set headers


// Execute cURL session
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Handle response
$apikey = json_decode($response);

$response_id = (isset($apikey->id) == true)?  $apikey->id : die($response);

}

// Close cURL session
curl_close($ch);



##########################
######
###### --- THIRD ENDPOINT TO GET THE USER DATA
######
##########################


// API endpoint URL
$url = "https://services2.vpay.africa/api/service/v1/query/customer/$response_id/show";


$headers = array(
    'Content-Type: application/json', // Assuming JSON data is being sent
    'publicKey: '.$public_key, // Example Authorization header
    'b-access-token: '.$token // Example Authorization header
);



// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // Set headers


// Execute cURL session
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // Handle response
$apikey = json_decode($response);

if(!isset($apikey->nuban)){
die($response);
}else{
    $accountNumber = $apikey->nuban;
}

}

// Close cURL session
curl_close($ch);


vp_updateuser($userid,"vpayAccountNumber",$accountNumber);
vp_updateuser($userid,"vpayAccountName",$customer_firstN);




                }

            }

                die("100");
    }else{
        die("Please Enable Vpay");
    }

        break;
        case"mrundan_monnify":
            global $wpdb;
            $hid = explode(",", $ddid);

            $gateways = "";
     


    if(vp_getoption('enable_monnify') == "yes"){
        $gateways .= "MONNIFY";
        $did = "yes";

        $total = 0;

        $contract_code = vp_getoption("monnifycontractcode");
            foreach($hid as $hd){
                if(!empty($hd)){
                    $userid = $hd;
                
                    $bvn = vp_getuser($userid,"myBvn",true);
                    $nin = vp_getuser($userid,"myNin",true);

                    if(($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 && mb_strlen($nin) < 10) ){
                        continue;
                    }else{
                        $total += 1; 
                    }

                   $username = get_userdata($hd)->user_login;
                   $email = get_userdata($hd)->user_email;
                   $fun = vp_getuser($hd,"first_name",true);
                   $lun = vp_getuser($hd,"last_name",true);

                   $apikeym = vp_getoption("monnifyapikey");
                   $secretkeym = vp_getoption("monnifysecretkey");
	
                        if(stripos($apikeym,"prod") == false) {
                            $baseurl =  "https://sandbox.monnify.com";
                            $mode = "test";
                        }
                        else{
                            $baseurl =  "https://api.monnify.com";
                            $mode = "live";
                        }
                            
                        

                        
                        $curl = curl_init();
                        
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $baseurl.'/api/v1/auth/login/',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_HTTPHEADER => [
                                    "Content-Type: application/json",
                                    "Authorization: Basic ".base64_encode("$apikeym:$secretkeym") 
                                ],
                        ));
                        
                        $respo = curl_exec($curl);
                        
                        $jsons = json_decode($respo);


                        if(isset($jsons->responseBody->accessToken)){
                        
                        
                        $curl = curl_init();
                        $code = rand(1000,100000);

                        $data["accountReference"] = $code;
                        $data["accountName"] = $username;
                        $data["currencyCode"] = "NGN";
                        $data["contractCode"] = $contract_code;
                        $data["customerEmail"] = $email;
                        $data["customerName"] = $fun." ".$lun;
                        $data["getAllAvailableBanks"] = false;
                        $data["preferredBanks"] = ["035","232","50515"];
                        if($bvn != "false" && !empty($bvn) && mb_strlen($nin) > 10 && is_numeric($bvn)){
                            $data["bvn"] = $bvn;
                        }
                        if($nin != "false" && !empty($nin) && mb_strlen($nin) > 9 && is_numeric($nin)){
                            $data["nin"] = $nin;
                        }
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $baseurl.'/api/v2/bank-transfer/reserved-accounts',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS => json_encode($data),
                          CURLOPT_HTTPHEADER => array(
                            "Authorization: Bearer ".$jsons->responseBody->accessToken,
                            'Content-Type: application/json'
                          ),
                        ));
                        
                        $respon = curl_exec($curl);
                        
                        curl_close($curl);
                        
                        $response = json_decode($respon,true);

                        if(!isset($response["responseBody"])){
                            print_r($respon);
                            die();
                        }
                        
                        $reference = $response["responseBody"]["accountReference"];
                        $customerName = $response["responseBody"]["accounts"][0]["accountName"];
                        $accountNumber = $response["responseBody"]["accounts"][0]["accountNumber"];
                        $bankName = $response["responseBody"]["accounts"][0]["bankName"];
                        
                        vp_updateuser($userid,"bank_reference",$reference);
                        vp_updateuser($userid,"account_mode",$mode);
                        
                        vp_updateuser($userid,"account_name",$customerName);
                        vp_updateuser($userid,"account_number",$accountNumber);
                        vp_updateuser($userid,"bank_name",$bankName);
                        
                        if(isset($response["responseBody"]["accounts"][1]["accountName"])){
                          $customerName = $response["responseBody"]["accounts"][1]["accountName"];
                          $accountNumber = $response["responseBody"]["accounts"][1]["accountNumber"];
                          $bankName = $response["responseBody"]["accounts"][1]["bankName"];
                        
                        vp_updateuser($userid,"account_name1",$customerName);
                        vp_updateuser($userid,"account_number1",$accountNumber);
                        vp_updateuser($userid,"bank_name1",$bankName);
                        
                          }
                          else{}
                        
                          
                          if(isset($response["responseBody"]["accounts"][2]["accountName"])){
                            $customerName = $response["responseBody"]["accounts"][2]["accountName"];
                            $accountNumber = $response["responseBody"]["accounts"][2]["accountNumber"];
                            $bankName = $response["responseBody"]["accounts"][2]["bankName"];
                        
                        vp_updateuser($userid,"account_name2",$customerName);
                        vp_updateuser($userid,"account_number2",$accountNumber);
                        vp_updateuser($userid,"bank_name2",$bankName);
                        
                            }
                            else{}

                 

                          
                        
                        }
                        else{
                            print_r($respo);
                            die();
                        }         
                    }              




            }

            if($total < 1){
                die("No user selected has bvn or nin attached/verified. Bvn is Mandatory");
            }
                die("100");
    }else{
        die("Please Enable Monnify");
    }

        break;
        case"mrundan_ncwallet":
            global $wpdb;
            $hid = explode(",", $ddid);

            $gateways = "";
            $token = vp_getoption("ncwallet_apikey");
            $pin = vp_getoption("ncwallet_pin");
        
            if(vp_getoption('enable_ncwallet') == "yes"  && vp_getoption("vtupress_custom_ncwallet") == "yes"){


                $gateways = "NCWALLET & ";
                $total = 0;
                $admin_bvn = strtolower(vp_getoption("ncwallet_admin_bvn"));
                foreach($hid as $hd){
                    if(!empty($hd)){
                        $userid = $hd;

                        $bvn = vp_getuser($userid,"myBvn",true);
                        $nin = vp_getuser($userid,"myNin",true);
    
                        if((($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 )) && (empty($admin_bvn)  || $admin_bvn == "false")  ){
                            continue;
                        }else{
                            $total += 1; 
                        }

                        $username = get_userdata($hd)->user_login;
                        $email = get_userdata($hd)->user_email;
                        $fun = vp_getuser($hd,"first_name",true);
                        $lun = vp_getuser($hd,"last_name",true);
                        $phone = vp_getuser($hd, "vp_phone",true);



                if(empty($admin_bvn) || $admin_bvn == "false"){
                    $payload =  [
                        "account_name" => $fun." ".$lun,
                        "bank_code" => "safehaven",
                        "account_type" => "static",
                        "email" => $email,
                        "bvn" => $bvn,
                        "phone_number" => $phone

                    ];
                }else{
                    $payload =  [
                        "account_name" => $fun." ".$lun,
                        "bank_code" => "safehaven",
                        "account_type" => "static",
                        "email" => $email,
                        "bvn" => $admin_bvn,
                        "phone_number" => $phone

                    ];   
                }


                $url = "https://ncwallet.africa/api/v1/bank/create";



                $curl = curl_init();

                curl_setopt_array($curl, [
                CURLOPT_URL =>  $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>json_encode($payload),
                CURLOPT_HTTPHEADER => [
                    "Authorization: $token",
                    "trnx_pin: $pin",
                    "Accept: application/json",
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

                        if(!isset($response["status"])){
                            die($res);
                        }

                        if($response["status"] != "success"){
                            die($res);
                        }


                    vp_updateuser($hd,"ncwallet_accountnumber",$response["data"]["account_number"]);
                    vp_updateuser($hd,"ncwallet_accountname",$response["data"]["account_name"]);


                }





                    }
                }

                die("100");

            }
            else{
                die("Please enable ncwallet");
            }


        break;
        case"mrundan_squadco":
            global $wpdb;
            $hid = explode(",", $ddid);

            $gateways = "";
        
            
            if(vp_getoption('enablesquadco') == "yes"  && vp_getoption("vtupress_custom_gtbank") == "yes"){
                $gateways = "SQUADCO & ";
                $total = 0;
                $admin_bvn = strtolower(vp_getoption("squad_admin_bvn"));
                $admin_fn = vp_getoption("squad_admin_fn");
                $admin_ln = vp_getoption("squad_admin_ln");
                $admin_dob = vp_getoption("squad_admin_dob");
                foreach($hid as $hd){
                    if(!empty($hd)){
                        $userid = $hd;

                                        
                    $bvn = vp_getuser($userid,"myBvn",true);
                    $nin = vp_getuser($userid,"myNin",true);

                    if((($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 /*&& mb_strlen($nin) < 10*/)) && (empty($admin_bvn) || $admin_bvn == "false")){
                        continue;
                    }else{
                        $total += 1; 
                    }


                       $username = get_userdata($hd)->user_login;
                       $email = get_userdata($hd)->user_email;
                       $fun = vp_getuser($hd,"first_name",true);
                       $lun = vp_getuser($hd,"last_name",true);


                    $token = vp_getoption("squad_secret");




                    $unid = uniqid();
                    $genemail = create_email();
                    $num = "0".rand(7,9)."0".rand(11111111,99999999);

                    $sub = uniqid();

                    $customer_firstN = $fun;
                    $customer_lastN = $lun;
                    $ref = uniqid();
                    $customer_phone = $num;
                    $customer_email = $email;

            if(empty($admin_bvn) || $admin_bvn == "false"){
                    $payload =  [
                        "first_name" => $customer_firstN,
                        "customer_identifier" => $ref,
                        "last_name" => $customer_lastN,
                        "mobile_num" => $customer_phone,
                        "email" => $customer_email,
                        "bvn" => "$bvn",
                        "dob" => "",
                        "address" => "",
                        "gender" => "1"

                    ];
            }else{
                $payload =  [
                    "first_name" => $admin_fn,
                    "customer_identifier" => $ref,
                    "last_name" => $admin_ln,
                    "mobile_num" => $customer_phone,
                    "email" => $customer_email,
                    "bvn" => $admin_bvn,
                    "dob" => $admin_dob,
                    "address" => "here",
                    "gender" => "1"

                ];
            }

//
// "beneficiary_account" => "0451037627",

if(preg_match('/sandbox/',$token)){
    $url = "https://sandbox-api-d.squadco.com/virtual-account";
}else{
    $url = "https://api-d.squadco.com/virtual-account";
}



$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL =>  $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>json_encode($payload),
  CURLOPT_HTTPHEADER => [
      "Authorization: Bearer $token",
    "Accept: application/json",
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

    if(!isset($response["status"])){
        
  $return_account = new stdClass;
  $return_account->status = 'failed';
  $return_account->message = $response;
  
$msg = json_encode($return_account);
error_log($msg);
die($msg);
    }elseif($response["status"] != "200"){
        $return_account = new stdClass;
        $return_account->status = 'failed';
        $return_account->message = $response;
      $msg = json_encode($return_account);
      error_log($msg);
      die($msg);
    }
}


$randomNumber = $response["data"]["virtual_account_number"];

vp_updateuser($userid,"squadAccountNumber",$randomNumber);
vp_updateuser($userid,"squadAccountName",$customer_firstN);




                    }
                }
                if($total < 1){
                    die("No user selected has bvn or nin attached/verified. BVN Is Mandatory");
                }

                $did = "yes";

                die("100");

            }else{
                die("Please enable squadco");
            }

        break;
        case"mrundan_kuda":
            
    if(vp_getoption('enablekuda') == "yes"  && vp_getoption("vtupress_custom_kuda") == "yes"){

            $total = 0;
                foreach($hid as $hd){
                    if(!empty($hd)){
            
                $gateways = "KUDA & ";
                        $userid = $hd;

                        $bvn = vp_getuser($userid,"myBvn",true);
                        $nin = vp_getuser($userid,"myNin",true);
    
                        if(($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 /*&& mb_strlen($nin) < 10*/) ){
                            continue;
                        }else{
                            $total += 1; 
                        }
    

                       $username = get_userdata($hd)->user_login;
                       $email = get_userdata($hd)->user_email;
                       $fun = vp_getuser($hd,"first_name",true);
                       $lun = vp_getuser($hd,"last_name",true);
            
                //sk_627c407a788c25602fbf36fdf99d5e888644766031
                //pk_627c407a788c25602fbd2c939f993a8ce3507d0042
            $token = vp_getoption('kuda_generated_apikey');
            
            global $time;
            
            $time = date('mis');
            
            
            $numberOfEmails = 1; // Change this to generate more or fewer emails
            
            
            $sub = uniqid();
            
            $customer_firstN = $fun;
            $customer_lastN = $lun;
            $ref = uniqid();
            $customer_phone = $num;
            $customer_email = $email;
            
            $unid = uniqid();
            $genemail = create_email();
            $num = "0".rand(7,9)."0".rand(11111111,99999999);
            $payload = [
            'serviceType' => 'ADMIN_CREATE_VIRTUAL_ACCOUNT',
            'requestRef' => $unid,
            'data' => [
            'email' => $genemail,
            'phoneNumber' => $num,
            'bvn' => $bvn,
            'lastName' => $customer_lastN,
            'firstName' => $customer_firstN,
            'trackingReference' => $unid,
            ]
            ];
            
            //
            // "beneficiary_account" => "0451037627",
            
            $url = "https://kuda-openapi.kuda.com/v2.1";
            
            $curl = curl_init();
            
            curl_setopt_array($curl, [
            CURLOPT_URL =>  $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($payload),
            CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Accept: application/json",
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
            die($msg);
            
            } 
            else {
            
            if(!isset($response["data"]["accountNumber"])){
            
            $return_account = new stdClass;
            $return_account->status = 'failed';
            $return_account->message = $response;
            
            $msg = json_encode($return_account);
            error_log($msg);
            die($msg);
            }elseif($response["status"] != "success"){
            $return_account = new stdClass;
            $return_account->status = 'failed';
            $return_account->message = $response;
            $msg = json_encode($return_account);
            error_log($msg);
            die($msg);
            }
            }
            
            
            $randomNumber = $response["data"]["accountNumber"];
            
            vp_updateuser($userid,"kudaAccountNumber",$randomNumber);
            vp_updateuser($userid,"kudaAccountName",$customer_firstN);
            vp_updateuser($userid,"kudaTrackingRef",$unid);
            
          
            
            
                    
            
            
                $did = "yes";
            
                    }
                }
                if($total < 1){
                    die("No user selected has bvn or nin attached/verified. BVN Is Mandatory");
                }
                    die("100");
    }else{
        die("Please Enable Kuda Bank");
    }
            
        break;
        case"changepin":
            $pin = $_POST["pin"];
            vp_updateuser($ddid, "vp_pin", $pin);
            die('{"status":"100"}') ;
        break;
        case"changephone":
            $phone = $_POST["phone"];
            vp_updateuser($ddid, "vp_phone", $phone);
            die('{"status":"100"}') ;
        break;
        case"changeemail":
            $email = trim($_POST["usemail"]);
            $fullname = trim($_POST["fullname"]);

            if(empty($email)){
                die("A valid Email is required");
            }
            if(empty($fullname)){
                die("A valid FullName is required");
            }

            if(!preg_match('/\s/',$fullname)){
                die("A valid FullName is required");
            }

            $splitname = explode(" ",$fullname);
            $first_name = trim($splitname[0]);
            $last_name = trim($splitname[1]);

            vp_updateuser($ddid,"first_name",$first_name);
            vp_updateuser($ddid,"last_name",$last_name);

                if(!email_exists($email)){
                $args = array(
                    'ID'         => $ddid,
                    'user_email' => esc_attr($email)
                );            
            wp_update_user( $args );
                die('{"status":"100"}') ;
            }
            else{
                die('{"status":"101"}');
            }
               
            break;
        case"userban":
            vp_updateuser(intval($_POST["userid"]),'vp_user_access',"ban");
            $data = [ 'vp_ban' => "ban" ];
            $where = [ 'id' => $ddid ];
            $updated = $wpdb->update( $wpdb->prefix.'users', $data, $where);
                echo '{"status":"100"}';
            break;
   case"useraccess":
   vp_updateuser(intval($_POST["userid"]),'vp_user_access',"access");
   $data = [ 'vp_ban' => "access" ];
   $where = [ 'id' => $ddid ];
   $updated = $wpdb->update( $wpdb->prefix.'users', $data, $where);
       echo '{"status":"100"}';
   break;
   case "addbalance":
    
    $amount = floatval($_POST["amount"]);
             $useramount = floatval(vp_getuser(intval($_POST["userid"]), 'vp_bal', true));
              $balsum = floatval($useramount) + floatval($_POST["amount"]);
             
          
  
  if($balsum > $useramount){
      
      $updated_user = vp_updateuser(intval($_POST["userid"]),'vp_bal', floatval($balsum));
      
      if($updated_user == true || $updated_user == "true" || $updated_user == "1"){

        $descriptionw = $_POST["reason"];
        if(!empty($descriptionw)){
      $description = $_POST["reason"];	
        }
       else{
      $description =  "Credited By Admin"; 
       }
      
            
      global $wpdb;
      $name = "Admin";
      
      $fund_amount = $_POST["amount"];
      $before_amount = $useramount;
      $now_amount = $balsum;
      $user_id = $_POST["userid"];
      $the_time = current_time('mysql', 1);
      
      $table_name = $wpdb->prefix.'vp_wallet';
      $wpdb->insert($table_name, array(
      'name'=> $name,
      'type'=> "Wallet",
      'description'=> $description,
      'fund_amount' => $fund_amount,
      'before_amount' => $before_amount,
      'now_amount' => $now_amount,
      'user_id' => $user_id,
      'status' => "Approved",
      'the_time' => date('Y-m-d h:i:s A')
      ));
      
      die('{"status":"100"}');
  }
  else{
      
      die('{"status":"200"}');
  }
  
  }
  


    break;
    case"verify_user":
    
    vp_updateuser($ddid,"email_verified","verified");
    
    //echo vp_getuser($ddid,"email_verified");
    echo '{"status":"100"}';
    
    break;
    case "removebalance":
        
    $amount = floatval($_POST["amount"]);
              $useramount = vp_getuser(intval($_POST["userid"]), 'vp_bal', true);
      
              $balsum = floatval($useramount ) - floatval($_POST["amount"]);
              
              
          
  if($balsum < $useramount){
      
      $updated_user = vp_updateuser(intval($_POST["userid"]),'vp_bal', floatval($balsum));
      
      if($updated_user == true || $updated_user == "true" || $updated_user == "1"){

        $descriptione = $_POST["reason"];
        if(!empty($descriptione)){
      $description = $_POST["reason"];	
        }
       else{
      $description =  "Debited By Admin"; 
       }
      
      
                
      global $wpdb;
      $name = "Admin";
      $fund_amount = $_POST["amount"];
      $before_amount = $useramount;
      $now_amount = $balsum;
      $user_id = $_POST["userid"];
      $the_time = current_time('mysql', 1);
      
      $table_name = $wpdb->prefix.'vp_wallet';
      $wpdb->insert($table_name, array(
      'name'=> $name,
      'type'=> "Wallet",
      'description'=> $description,
      'fund_amount' => $fund_amount,
      'before_amount' => $before_amount,
      'now_amount' => $now_amount,
      'user_id' => $user_id,
      'status' => "Approved",
      'the_time' => date('Y-m-d h:i:s A')
      ));
      
    
      
      die('{"status":"100"}');
  }
  else{
      
     die('{"status":"200"}');
  }
  }
  
  

    break;
    case "setbalance":
        
    $amount = floatval($_POST["amount"]);
              $useramount = vp_getuser(intval($_POST["userid"]), 'vp_bal', true);
   
               
      
      $updated_user = vp_updateuser(intval($_POST["userid"]),'vp_bal', floatval($_POST["amount"]));
      
      if($updated_user == true || $updated_user == "true" || $updated_user == "1"){

          
   $descriptione = $_POST["reason"];
   if(!empty($descriptione)){
 $description = $_POST["reason"];	
   }
  else{
 $description =  "Credited By Admin"; 
  }
 
 
 global $wpdb;
 $name = "Admin";
 $fund_amount = $_POST["amount"];
 $before_amount = $useramount;
 $now_amount = floatval($_POST["amount"]);
 $user_id = $_POST["userid"];
 $the_time = current_time('mysql', 1);
 
 $table_name = $wpdb->prefix.'vp_wallet';
 $wpdb->insert($table_name, array(
 'name'=> $name,
 'type'=> "Wallet",
 'description'=> $description,
 'fund_amount' => $fund_amount,
 'before_amount' => $before_amount,
 'now_amount' => $now_amount,
 'user_id' => $user_id,
 'status' => "Approved",
 'the_time' => date('Y-m-d h:i:s A')
 ));

      
     die('{"status":"100"}');
  }
  else{
      
      die('{"status":"200"}');
  }
  

    break;
    case "changeplan":
  
        $id = intval(trim($_POST["userid"]));
        $plan = trim($_POST["plan"]);
        $vrid = trim($_POST["apikey"]);
  
       $updated_user = vp_updateuser($id,'vr_plan', $plan);
       
  $apikey = trim(vp_getuser($id,'vr_id',true));
  if( empty($apikey) || strtolower($apikey) == "null" || $apikey === "0" || $apikey != $vrid ){

    if(!empty($vrid) && strtolower($vrid) != "null"){
  $updated_user1 = vp_updateuser($id,'vr_id',$vrid);
 // die("good");
    }
    else{
        $updated_user1 = vp_updateuser($id, 'vr_id',uniqid());   
    }
  }


  else{
  //  die("bad");
      $updated_user1 = true;
  }
  
  global $wpdb;
 if($updated_user == true || $updated_user == "true" || $updated_user == "1" && ($updated_user1 == true || $updated_user1 == "true" || $updated_user1 == "1")){
      
    $current_date = date("Y-m-d");

    $memRuleTable = $wpdb->prefix."vp_membership_rule_stats";
      $data = [
        'user_id' => $id,
        'ref' => 0,
        'transaction_number' => 0,
        'transaction_amount' => 0,
        'start_count' => $current_date
      ];
    $wpdb->insert($memRuleTable,$data);

    echo '{"status":"100"}';

  }
  else{
      
      echo '{"status":"200"}';
  } 

      break;
    case "switchto":
  $admin_id = get_current_user_id();
  $id = $_POST["userid"];
  $cookie_name = "switchto";
  //$cookie_value = get_userdata($id)->user_login;
  //$cookie_id = $id;
  setcookie($cookie_name, $admin_id, time() + (86400 * 30), "/"); // + 30 Days
  
           wp_clear_auth_cookie();
           wp_set_current_user($id);
           wp_set_auth_cookie($id, true);
  //$redirect_to = $_SERVER['REQUEST_URI'];
  
  die('{"status":"100"}');
  
    break;
    case "none":
  
    break;
  
      }
  
}?>