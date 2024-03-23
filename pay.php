<?php
header("Access-Control-Allow-Origin: 'self'");
session_start();
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
require_once(ABSPATH.'wp-load.php');
include_once(ABSPATH.'wp-admin/includes/plugin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}

if(isset($_POST['pay'])){

do_action("vppay");

//$uniqidvalue = $_POST['uniqidvalue'];

$paychoice = $_POST["paymentchoice"];


if(isset($_POST["amounte"])){
$userid = $_POST["userid"];
$_SESSION['userid'] = $_POST["userid"];
$amounte = $_POST['amounte'];
}


if(isset($_POST["tcode"])){


$id = get_current_user_id();
$name = get_userdata($id)->user_name;
$email = get_userdata($id)->user_email;

if(vp_getoption("charge_method") == "fixed"){
$amount = intval($_POST['amount']) + floatval(vp_getoption("charge_back"));
}
else{
$remove = (intval($_POST['amount']) *  floatval(vp_getoption("charge_back"))) / 100;
$amount = intval($_POST['amount']) + $remove;
}


$tcode = $_POST['tcode'];


$_SESSION['amount'] = $amount;

if(isset($_POST['secret'])){
$_SESSION['secret'] = $_POST['secret'];
}
$_SESSION['tcode'] = "wallet";
}
if(isset($_POST["ud"])){
	$ud = $_POST['ud'];
	$_SESSION['ud'] = $_POST['ud'];
}
if(isset($_POST["id"])){
	$id = $_POST['id'];
	$_SESSION['id'] = $_POST['id'];
}



$check_bal = vp_getoption("checkbal");



if(isset($amounte) || $check_bal == "no"){
	
if($paychoice == "flutterwave"){

echo'
<div style="visibility:hidden;">
<form>
  <script src="https://checkout.flutterwave.com/v3.js"></script>
  <button type="button" onClick="makePayment()" id="payf">Pay Now</button>
</form>
  </div>
<script>
  function makePayment() {
    FlutterwaveCheckout({
      public_key: "'.$k.'",
      tx_ref: "vtu"+Math.floor((Math.random() * 1000000000) + 1),
      amount: "'.$amount.'",
      currency: "NGN",
      country: "NG",
      payment_options: " ",
      customer: {
        email:  "'.$email.'",
        phone_number: "07049626922",
        name: "'.$name.'",
      },
      callback: function (data) {
     let dstatus = data.status;
	 let dtransaction_id = data.transaction_id;
	 let dtx_ref = data.tx_ref;
	 if(dstatus == "successful" || dstatus == "Completed" || dstatus == "completed"){

    			swal({
  title: "Funding Successful",
  text: "Might Take Few Minute To Finallize Transaction",
  icon: "success",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
	window.location.href = "/vpaccount";
      break;
    default:
    window.location.href = "/vpaccount";
  }
}); 
    
	 }
	 else{
		alert("Transaction Failed To Complete!, You\'ll Be Redirected Back CODE["+dstatus+"]"); 
		  window.history.back();
	 }
         console.log(data);
	  },
      onclose: function() {
        // close 
		window.history.back();
      },
      customizations: {
        title: "'; bloginfo("name"); echo'",
        description: "Payment for vtu services",
      },
    });
  }
</script>
<script>
    
    document.getElementById("payf").click();
</script>
';
}
elseif($paychoice == "paystack"){

  if(vp_getoption("paystack_charge_method") == "fixed"){
    $amount = intval($_POST['amount']) + floatval(vp_getoption("paystack_charge_back"));
    }
    else{
    $remove = (intval($_POST['amount']) *  floatval(vp_getoption("paystack_charge_back"))) / 100;
    $amount = intval($_POST['amount']) + $remove;
    }


    $_SESSION['amount'] = $amount;

echo '
<div style="visibility:hidden;">
<form id="paymentForm" >
<div class="form-submit" >
    <button type="submit" onclick="payWithPaystack()" id="payk"> Pay </button>
  </div>
</form>
</div


<link href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/formstyle.css?v=1" rel="stylesheet" />
      <div id="cover-spin" >
	  
	  </div>



<script src="'.esc_url(plugins_url("vtupress/js/sweet.js?v=1")).'" ></script>
<script src="'.esc_url(plugins_url("vtupress/js/jquery.js?v=1")).'" ></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
const paymentForm = document.getElementById("paymentForm");
paymentForm.addEventListener("submit", payWithPaystack, false);
function payWithPaystack(e) {
  e.preventDefault();
  let handler = PaystackPop.setup({
    key: "'.vp_getoption("ppub").'", // Replace with your public key
    email:  "'.$email.'",
    amount: '.$amount.' * 100,
    channels: "",
    ref: "vtu"+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    // label: "Optional string that replaces customer email"
    onClose: function(){
      window.history.back();
    },
    callback: function(response){
		 var locatio = "'.vp_getoption("siteurl").'/wp-content/plugins/vtupress/process.php?status=successful&current_clr='.$_SESSION["current_clr"].'&gateway=paystack&amount='.$amount.'&reference=" + response.reference;
     
     			swal({
  title: "Wait",
  text: "\"Press Okay And Wait For A Response To Fund Your Account\" ",
  icon: "success",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
	/*window.location.href = "/vpaccount";*/
	 
	   jQuery("#cover-spin").show();
	  ';
	?>  
	  jQuery.ajax({
  url: locatio,
 dataType: 'json',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful!!!",
  text: "Account Funded!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	window.location.href = "/vpaccount";
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
swal({
  title: "Error",
  text: data,
  icon: "error",
  button: "Okay",
}).then((value) => {
	jQuery("#cover-spin").show();
	window.location.href = "/vpaccount";
});

  }
  },
  type: 'POST'

});
	  
	  <?php
	  echo'
	  
	  
	  break;
    default:
	
	';
	?>
	 jQuery("#cover-spin").show();
		  jQuery.ajax({
  url: locatio,
 dataType: 'json',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful!!!",
  text: "Account Funded",
  icon: "success",
  button: "Okay",
}).then((value) => {
	window.location.href = "/vpaccount";
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
swal({
  title: "Error",
  text: data,
  icon: "error",
  button: "Okay",
}).then((value) => {
	jQuery("#cover-spin").show();
	window.location.href = "/vpaccount";
});

  }
  },
  type: 'POST'

});
	
	<?php
	
	echo'
     /* window.location.href = "/vpaccount";*/
  }
});  
    }
  });
  handler.openIframe();
}

</script>
<script>
    
    document.getElementById("payk").click();
</script>
';
}
elseif($paychoice == "monnify"){
	
  if(vp_getoption("charge_method") == "fixed"){
    $amount = intval($_POST['amount']) + floatval(vp_getoption("charge_back"));
    }
    else{
    $remove = (intval($_POST['amount']) *  floatval(vp_getoption("charge_back"))) / 100;
    $amount = intval($_POST['amount']) + $remove;
    }


    $_SESSION['amount'] = $amount;
    
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

$response = curl_exec($curl);

curl_close($curl);
$res  = json_decode($response);

if(!isset($res->responseBody->accessToken)){
  die("CREDENTIALS INCORRECT OR ERROR WITH MONNIFY");
}
$auth = $res->responseBody->accessToken;


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $baseurl.'/api/v1/merchant/transactions/init-transaction',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "amount":'.$amount.',
  "customerName": "'.$name.'",
  "customerEmail": "'.$email.'",
  "paymentReference":"'.uniqid("vtu-",false).'",
  "paymentDescription": "VTU SERVICE",
  "currencyCode": "NGN",
  "contractCode": "'.vp_getoption("monnifycontractcode").'",
  "redirectUrl": "'.vp_getoption("siteurl").'/vpaccount",
  "paymentMethods": [
    "CARD",
    "ACCOUNT_TRANSFER"
  ]
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$auth,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$respp  = json_decode($response);

if(isset($respp->responseBody->checkoutUrl)){

header("Location:".$respp->responseBody->checkoutUrl);
}
else{
  die("NO RESPONSE FROM MONNIFY. Please check your API KEYS and CONTRACT CODES");
}

}else{
  echo "
<script>
alert('Invalid payment gateway selected! Sorry this transaction can\'t be completed at the moment please check back later');
window.history.back();

</script>

";
}
}
else{
	
echo "
<script>
alert('Sorry this transaction can\'t be completed at the moment please check back later');
window.history.back();

</script>

";

}
}
else{
echo "Error";	
	
}
?>