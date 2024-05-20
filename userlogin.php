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
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

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

if(isset($_POST["lun"])){


if(vp_getoption("vp_enable_registration") == "no"){
	die('{"status":"101","message":"Registration Currently Not Allowed"}');
}else{

}
$user = trim($_POST["username"]);
$email = trim($_POST["email"]);
$pass = $_POST["pswd"];
$phone = trim($_POST["phone"]);
$ref = trim($_POST["ref"]);
$fun = trim($_POST["fun"]);
$lun = trim($_POST["lun"]);
$pin = trim($_POST["pin"]);

if(vp_getoption("vp_security") == "yes"){
$ban_list = vp_getoption("vp_users_email");

if(is_numeric(stripos($ban_list,$user)) || is_numeric(stripos($ban_list,$email)) ){
	
	
die('{"status":"101","message":NOT ALLOWED (X)"}');

}

}

$verify_username = preg_match("/^[a-zA-Z0-9_-]{3,16}$/", $user);
if($verify_username){
	$verify_email = preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",$email);
if($verify_email){
	$verify_pass = preg_match("/^[a-zA-Z0-9\.]/",$pass);
	if($verify_pass){
		$verify_phone = preg_match("/^[0-9]{11}$/",$phone);
		if($verify_phone){
			$verify_ref = preg_match("/^[0-9]/",$ref);
			if( $verify_ref || !is_plugin_active("vpmlm/vpmlm.php")){
				$verify_fn = preg_match("/^[a-zA-Z]{3,16}$/",$fun);
				$verify_ln = preg_match("/^[a-zA-Z]{3,16}$/",$lun);
				if($verify_fn && $verify_ln){
					  
					  $verify_zero = preg_match("/^0\d+$/",$pin);
					  
				if($verify_zero === 0){  
					  $verify_pin = preg_match("/[^0-9]/", $pin);
				
				if($verify_pin === 0 && strlen($pin) == 4 ){

					if(username_exists($user)){
						die('{"status":"101","message":"Sorry, that username already exists!"}');
					}
					elseif(email_exists($email)){
						die('{"status":"101","message":"Sorry, that email already exists!"}');
					}
					  
					  $userdata = array(
'user_login' => sanitize_user($user),
'user_email' => sanitize_email($email),
'user_pass' => sanitize_text_field($pass)
);

$userid = wp_insert_user($userdata);

if ( is_wp_error( $userid ) ) {
 // echo"No!!";
	// There was an error; possibly this user doesn't exist.
	$error_message = $userid->get_error_message();
  
  echo'{"status":"101","message":"'.$error_message.'"}';
  } 
  else{
   // echo"yes!!";
  //do your stuff
  if(basename(__FILE__) == "userlogin.php"){
   // return;
  }else{
    
  }

  if(is_plugin_active("vpmlm/vpmlm.php")){
    $ref = $_POST["ref"];// my ref id
    if($userid == $ref){
      $ref = "1";
    }
  }
  else{
    $ref = "1";
  }


vp_updateuser($userid,'vp_pin_set','yes');
vp_updateuser($userid, 'vp_phone', $phone);
vp_updateuser($userid, 'vp_pin', $pin);
vp_updateuser($userid, 'vp_bal', 0);
vp_updateuser($userid, 'vr_id', uniqid());
vp_updateuser($userid, 'vp_ref', 0);
vp_updateuser($userid, 'vporr', 0);
vp_updateuser($userid, 'last_name', $lun);
vp_updateuser($userid, 'first_name', $fun);
vp_updateuser($userid, "vr_plan", "customer");

global $wpdb;
$user = $wpdb->prefix."users";
$arr = ['vp_bal' => "0", 'vp_ban' => "access" ];
$where = ['ID' => $userid];
$updated = $wpdb->update($user, $arr, $where);

vp_updateuser($userid, 'vp_who_ref' , $ref); //who referred me
vp_updateuser($userid, 'vp_tot_ref' , 0); //number of my direct referrs
vp_updateuser($userid, 'vp_tot_in_ref' , 0); //number of my indirect referrs
vp_updateuser($userid, 'vp_tot_in_ref3' , 0); //number of my third level referrs


//membership rule!
global $wpdb;
$memRuleTable = $wpdb->prefix."vp_membership_rule_stats";
$memRes = $wpdb->get_results($wpdb->prepare("SELECT * FROM $memRuleTable WHERE user_id = %s ORDER BY ID DESC LIMIT 1",$ref));

if($memRes != NULL && !empty($memRes)){
$disID = $memRes[0]->id;
$data["ref"] = intval($memRes[0]->ref) + 1;
$wpdb->update($memRuleTable,$data,["user_id"=>$ref]);
}



vp_updateuser($userid, 'vp_tot_ref_earn' , 0); // total earned from direct referrers
vp_updateuser($userid, 'vp_tot_in_ref_earn' , 0); // total earned from indirect referrers
vp_updateuser($userid, 'vp_tot_in_ref_earn3' , 0); // total earned from third level referrers



vp_updateuser($userid, 'vp_tot_trans' , 0);  // total transactions Attempted
vp_updateuser($userid, 'vp_tot_suc_trans' , 0);  // total Successful transactions made
vp_updateuser($userid, 'vp_tot_trans_amt' , 0); //total transactions amount consumed
vp_updateuser($userid, 'vp_tot_trans_bonus' , 0); //total transactions bonus earned
vp_updateuser($userid, 'vp_tot_withdraws' , 0); // total withdrawals made
vp_updateuser($userid, 'vp_tot_dir_trans' , 0); // total amount earned from direct trans
vp_updateuser($userid, 'vp_tot_indir_trans' , 0); // total amount earned from indirect trans
vp_updateuser($userid, 'vp_tot_indir_trans3' , 0); // total amount earned from indirect trans
	


  if(vp_getuser($userid,"email_verified", true) != "false"){

      $uniqid = vp_getuser($userid,"email_verified", true);
  }
  else{
      $ddid = uniqid("vtu-",false);
      vp_updateuser($userid,"email_verified", $ddid);
      $uniqid = vp_getuser($userid,"email_verified", true);
  }


  if(vp_getoption('email_verification') == "yes"){
$usernamepper = ucfirst($user);
$subject = "[ $usernamepper ] - EMAIL VERIFICATION";
$headers = array('Content-Type: text/html; charset=UTF-8');
$message = <<<EOB

<div style="height:fit-content">
<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
<span style="" > Email Verification </span>

</div>
<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

Please for smoothness of our services and safety, we indulge you to verify your email. Your Activation Code Is <b>$uniqid</b>
</div>
<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
Thank You
</div>

</div>

EOB;
wp_mail($email,$subject,$message,$headers);
  }

  
function generateRandomSyllable() {
  $syllables = ['ab', 'ac', 'ad', 'ba', 'be', 'ca', 'ce', 'da', 'de', 'do', 'fa', 'fi', 'ga', 'ge', 'go', 'ha', 'he', 'hi', 'ja', 'jo', 'ka', 'ko', 'la', 'le', 'li', 'lo', 'ma', 'me', 'mi', 'mo', 'na', 'ne', 'ni', 'no', 'pa', 'pe', 'pi', 'po', 'ra', 're', 'ri', 'ro', 'sa', 'se', 'si', 'so', 'ta', 'te', 'ti', 'to', 'va', 've', 'vi', 'vo', 'wa', 'we', 'wi', 'wo', 'ya', 'yo', 'za', 'ze', 'zi', 'zo'];
  return $syllables[rand(0, count($syllables) - 1)];
}

function generateRandomEmail() {
  global $time;
  $email = generateRandomSyllable() . generateRandomSyllable() . generateRandomSyllable() .$time. '@gmail.com';
  return $email;
}



function create_email($numberOfEmails = 1){
  for ($i = 0; $i < $numberOfEmails; $i++) {
    $the_email = generateRandomEmail();
  }
  
  return  $the_email;
  }

 /*
  if(vp_getoption('enablekuda') == "yes"  && vp_getoption("vtupress_custom_kuda") == "yes"){
    $gateways = "KUDA & ";

          $hd = $userid;
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


$unid = uniqid();
$genemail = create_email($userid);
$num = "0".rand(7,9)."0".rand(11111111,99999999);

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

$generated = "KUDA & ";


        


    $did = "yes";

}
*/
/*
if(vp_getoption('enablesquadco') == "yes" && vp_getoption("vtupress_custom_gtbank") == "yes"){


                       $hd = $userid;
                     $username = get_userdata($hd)->user_login;
                     $email = get_userdata($hd)->user_email;
                     $fun = vp_getuser($hd,"first_name",true);
                     $lun = vp_getuser($hd,"last_name",true);

              //sk_627c407a788c25602fbf36fdf99d5e888644766031
              //pk_627c407a788c25602fbd2c939f993a8ce3507d0042
$token = vp_getoption("squad_secret");

global $time;

$time = date('mis');



$numberOfEmails = 1; // Change this to generate more or fewer emails



$unid = uniqid();
$genemail = create_email();
$num = "0".rand(7,9)."0".rand(11111111,99999999);

$sub = uniqid();

$customer_firstN = $fun;
$customer_lastN = $lun;
$ref = uniqid();
$customer_phone = $num;
$customer_email = $email;

$payload =  [
  "first_name" => "VTU-$customer_firstN",
  "customer_identifier" => $ref,
  "last_name" => $customer_lastN,
  "mobile_num" => $customer_phone,
  "email" => $customer_email,
  "bvn" => "22505795245",
  "dob" => "03/09/2000",
  "address" => "here",
  "gender" => "1"

];

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



              $did = "yes";

          }

*/


if(is_plugin_active("vpmlm/vpmlm.php")){

//direct
$ref = $_POST["ref"];// my ref id
$total_dir_ref = vp_getuser($ref, "vp_tot_ref", true); //his cur total dir ref
$sum_tot_dir_ref =  intval($total_dir_ref) + 1;
vp_updateuser($ref, "vp_tot_ref", $sum_tot_dir_ref);


//indirect
$who_reref = vp_getuser($ref, "vp_who_ref", true); // who ref my ref
$total_indir_ref = vp_getuser($who_reref, "vp_tot_in_ref", true); //his cur total indir ref
$sum_tot_indir_ref =  intval($total_indir_ref) + 1;
vp_updateuser($who_reref, "vp_tot_in_ref", $sum_tot_indir_ref);




$who_reref3 = vp_getuser($who_reref, "vp_who_ref", true); // who ref my ref
$total_indir_ref3 = vp_getuser($who_reref3, "vp_tot_in_ref3", true); //his cur total indir ref
$sum_tot_indir_ref3 =  intval($total_indir_ref3) + 1;
vp_updateuser($who_reref3, "vp_tot_in_ref3", $sum_tot_indir_ref3);

$refs_id = vp_getuser($ref, "vp_tot_ref_id", true);
vp_updateuser($ref, "vp_tot_ref_id", $refs_id."$userid,");

$inrefs_id = vp_getuser($who_reref, "vp_tot_in_ref_id", true);
vp_updateuser($who_reref, "vp_tot_in_ref_id", $inrefs_id."$userid,");

$inrefs3_id = vp_getuser($who_reref3, "vp_tot_in_ref3_id", true);
vp_updateuser($who_reref3, "vp_tot_in_ref3_id", $inrefs3_id."$userid,");
}



do_action( 'user_register', $userid, $userdata );

die('{"status":"100"}');

}







				}
				else{
				die('{"status":"101","message":"Pin Must Be 4 Digits"}');
				}
				
				}
				else{
				die('{"status":"101","message":"Pin Must Not Start With Zero"}');	
				}
				}
				else{
					die('{"status":"101","message":"First And Last Name Must Be Of At Least (3) Letters Only Without Space"}');	
				}
				}
				else{
				die('{"status":"101","message":"Your Refer Code Must Be The Default ID Of 1 Or A Valid User ID"}');	
			}	
		}
		else{
		die('{"status":"101","message":"Enter Your 11 Digits Phone Numbers"}');	
		}
	}
	else{
	die('{"status":"101","message":"Password Must Contain Only AlphaNumeric With/Or Character Without {} or Space"}');	
	}
}
else{
	die('{"status":"101","message":"Incorrect Email"}');
}	
}
else{
	die('{"status":"101","message":"Username Must Contain Only Alpha-Numeric Character without @-/.#$%^&* or space"}');
}
}
?>