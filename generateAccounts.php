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

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
    if(!preg_match("/$nm/",$referer)) {
        die("NO REF");
    }

}


global $time;

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



$id = get_current_user_id();


if(!isset($_POST["for"])){
    die("No For Perm");
}

$for = $_POST["for"];

switch($for){
    case"billstack":
        global $wpdb;
        $hd = $id;

        if(empty($hd)){
            die("Please Login");
        }
        $gateways = "";
        $token = vp_getoption("billstack_apikey");
    
        if(vp_getoption('enable_billstack') == "yes"  && vp_getoption("vtupress_custom_billstack") == "yes"){


            $gateways = "BILLSTACK & ";
            $total = 0;
                    $userid = $hd;

                    $username = get_userdata($hd)->user_login;
                    $email = get_userdata($hd)->user_email;
                    $fun = vp_getuser($hd,"first_name",true);
                    $lun = vp_getuser($hd,"last_name",true);
                    $phone = vp_getuser($hd, "vp_phone",true);




                $payload =  [
                    "firstName" => $fun,
                    "lastName" => $lun,
                    "bank" => "9PSB",
                    "email" => $email,
                    "reference" => uniqid(),
                    "phone" => $phone

                ];
            


            $url = "https://api.billstack.co/v2/thirdparty/generateVirtualAccount/";



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
                        die($res);
                    }

                    if($response["status"] != "true"){
                        die($res);
                    }

                    if(!isset($response["data"]["account"])){
                        die($res);
                    }

                    $account = $response["data"]["account"][0];


                vp_updateuser($hd,"billstack_accountnumber",$account["account_number"]);
                vp_updateuser($hd,"billstack_accountname",$account["account_name"]);


            }





            die("100");

        }
        else{
            die("Please enable Billstack");
        }


    break;
    case"ncwallet":

        $token = vp_getoption("ncwallet_apikey");
        $pin = vp_getoption("ncwallet_pin");

        if(vp_getoption('enable_ncwallet') == "yes"){
    
            $apikey = vp_getoption("ncwallet_apikey");
            $pin = vp_getoption("ncwallet_pin");
            $hd = $id;
            $admin_bvn = strtolower(vp_getoption("ncwallet_admin_bvn"));
                    if(!empty($hd)){
                        $userid = $hd;
                    
                        $bvn = trim(vp_getuser($userid,"myBvn",true));
                        $nin = trim(vp_getuser($userid,"myNin",true));
                        $phone = vp_getuser($userid, "vp_phone",true);

    
                        if((($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 && mb_strlen($nin) < 10 )) && (empty($admin_bvn) || $admin_bvn == "false")){
                            die("BVN / NIN KYC VERIFICATION IS NECESSARY");
                        }else{
                        
                        }
    
                       $username = get_userdata($hd)->user_login;
                       $email = get_userdata($hd)->user_email;
                       $fun = vp_getuser($hd,"first_name",true);
                       $lun = vp_getuser($hd,"last_name",true);

                            
    
                if($admin_bvn == "false" || empty($admin_bvn)){
                       $payload =  [
                        "account_name" => $fun." ".$lun,
                        "bank_code" => "providus",
                        "account_type" => "static",
                        "email" => $email,
                        "validation_type" => "BVN",
                        "validation_number" => $bvn,
                        "phone_number" => $phone

                    ];
                }else{
                    $payload =  [
                        "account_name" => $fun." ".$lun,
                        "bank_code" => "providus",
                        "account_type" => "static",
                        "email" => $email,
                        "validation_type" => "BVN",
                        "validation_number" => $admin_bvn,
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




                        }else{
                            die("PLEASE LOGIN");
                        }           
    
    
                    die("100");
        }else{
            die("Ncwallet Africa Is Currently Not Enabled!");
        }
    break;
    case"monnify":

        if(vp_getoption('enable_monnify') == "yes"){
    
            $contract_code = vp_getoption("monnifycontractcode");
            $hd = $id;
                    if(!empty($hd)){
                        $userid = $hd;
                    
                        $bvn = trim(vp_getuser($userid,"myBvn",true));
                        $nin = trim(vp_getuser($userid,"myNin",true));

    
                        if(($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 && mb_strlen($nin) < 10 )){
                            die("BVN / NIN KYC VERIFICATION IS NECESSARY");
                        }else{
                        
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
                            $code = rand(10000,9999999999);
    
                            $data["accountReference"] = $code;
                            $data["accountName"] = $username;
                            $data["currencyCode"] = "NGN";
                            $data["contractCode"] = $contract_code;
                            $data["customerEmail"] = $email;
                            $data["customerName"] = $fun." ".$lun;
                            $data["getAllAvailableBanks"] = false;
                            $data["preferredBanks"] = ["035","232","50515"];
                            if($bvn != "false" && !empty($bvn) && mb_strlen($bvn) > 10 && is_numeric($bvn)){
                                $data["bvn"] = $bvn;
                            }
                            if($nin != "false" && !empty($nin) && mb_strlen($nin) > 10 && is_numeric($nin)){
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
                        }else{
                            die("PLEASE LOGIN");
                        }           
    
    
                    die("100");
        }else{
            die("Monnify Is Currently Not Enabled!");
        }
    break;
    case"kuda":
        if(vp_getoption('enablekuda') == "yes"  && vp_getoption("vtupress_custom_kuda") == "yes"){

            $hd = $id;
                    if(!empty($hd)){
            
                $gateways = "KUDA & ";
                        $userid = $hd;

                        $bvn = vp_getuser($userid,"myBvn",true);
                        $nin = vp_getuser($userid,"myNin",true);
    
                        if(($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin))  || (mb_strlen($bvn) < 10 /*&& mb_strlen($nin) < 10*/ )){
                           die("BVN KYC VERIFICATION IS NECESSARY");
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
            
                    }else{
                        die("Please Login");
                    }

                    die("100");
    }else{
        die("Kuda is currently not enabled!");
    }

    break;
    case"gtb":
            
        if(vp_getoption('enablesquadco') == "yes"  && vp_getoption("vtupress_custom_gtbank") == "yes"){
            $gateways = "SQUADCO & ";

            $hd = $id;
            $admin_bvn = strtolower(vp_getoption("squad_admin_bvn"));
            $admin_fn = vp_getoption("squad_admin_fn");
            $admin_ln = vp_getoption("squad_admin_ln");
            $admin_dob = vp_getoption("squad_admin_dob");

                if(!empty($hd)){
                    $userid = $hd;

                                    
                $bvn = vp_getuser($userid,"myBvn",true);
                $nin = vp_getuser($userid,"myNin",true);

                if((($bvn == 'false' && $nin == 'false') || (empty($bvn) && empty($nin)) || (mb_strlen($bvn) < 10 /*&& mb_strlen($nin) < 10*/ )) && ( empty($admin_bvn)  || $admin_bvn == "false" )){
                    die("BVN / NIN KYC VERIFICATION IS NECESSARY");
                }


                   $username = get_userdata($hd)->user_login;
                   $email = get_userdata($hd)->user_email;
                   $fun = vp_getuser($hd,"first_name",true);
                   $lun = vp_getuser($hd,"last_name",true);

            //sk_627c407a788c25602fbf36fdf99d5e888644766031
            //pk_627c407a788c25602fbd2c939f993a8ce3507d0042
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
"first_name" => "VTU-$customer_firstN",
"customer_identifier" => $ref,
"last_name" => $customer_lastN,
"mobile_num" => $customer_phone,
"email" => $customer_email,
"bvn" => $bvn,
"dob" => "",
"address" => "here",
"gender" => "1"

];

}
else{
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




                }else{
                    die("Please Login");
                }


            $did = "yes";

            die("100");

        }else{
            die("GTB Not Enabled");
        }
    break;
    case"vpay":
            
        if(vp_getoption('enablevpay') == "yes" && vp_getoption("vtupress_custom_vpay") == "yes"){
            $gateways = "VPAY & ";

            $hd = $id;
                if(!empty($hd)){
                    $userid = $hd;

                   $username = get_userdata($hd)->user_login;
                   $email = get_userdata($hd)->user_email;
                   $fun = vp_getuser($hd,"first_name",true);
                   $lun = vp_getuser($hd,"last_name",true);

            //sk_627c407a788c25602fbf36fdf99d5e888644766031
            //pk_627c407a788c25602fbd2c939f993a8ce3507d0042
$vpublickey = vp_getoption("vpay_public");
$public_key = $vpublickey;
$vusername = vp_getoption("vpay_email");
$vpassword = vp_getoption("vpay_password");




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

$response_id = (isset($apikey->id) == true)?  $apikey->id : die("Error Retrieving Id");

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
die("Error Retriving Account Number $response");
}else{
    $accountNumber = $apikey->nuban;
}

}

// Close cURL session
curl_close($ch);


vp_updateuser($userid,"vpayAccountNumber",$accountNumber);
vp_updateuser($userid,"vpayAccountName",$customer_firstN);




                }else{
                    die("Please Login");
                }


            $did = "yes";

            die("100");

        }else{
            die("VPAY is Not Enabled");
        }
    break;
    default: die("No gateway chosen");
}

