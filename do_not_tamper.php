<?php

$current_timestamp = current_time('timestamp');

	if(date("Y-m-d h:i A",$current_timestamp) >= vp_getoption("vp_check_date")){
		//if(2 >= 1){

			$datenow = date("Y-m-d h:i A",$current_timestamp);
			$next = date('Y-m-d h:i A',strtotime($datenow." +12 hours"));

	

$id = vp_getoption('vpid');
$actkey = vp_getoption('actkey');
$url = esc_url(plugins_url("vtupress/vend.php"));

if(vp_getoption("enable_beneficiaries") != "yes"){
	$ben = 'jQuery("datalist#beneficiaries").remove();';
}
else{
	$ben = "";
}
$mx = <<<TEXT
<script>
document.addEventListener("DOMContentLoaded", function(){
	$ben
  jQuery(window).on("load",function(){

var obj = {};  
obj["actkey"] = "$actkey";
obj["actid"] = "$id";
obj["setactivation"] = "yea";
$.ajax({
			url: '$url',
			method: 'post',
			data: obj
		}).done(function(result){
			console.log(result);
			console.log("Now"+"$datenow");
			console.log("Now"+"$next");

		});

	});

});

</script>
TEXT;

echo $mx;

}
else{


	if(vp_getoption("enable_beneficiaries") != "yes"){
		$ben = 'jQuery("datalist#beneficiaries").remove();';
	}
	else{
		$ben = "";
	}

$dt = vp_getoption("vp_check_date");

echo <<<TEXT

<script>
document.addEventListener("DOMContentLoaded", function(){
console.log("Now "+"$dt");
});

</script>

TEXT;

}

$id = get_current_user_id();
$user_balance = intval(vp_getuser($id,"vp_bal",true));

if($user_balance <= 50 && isset($_GET["vend"])){

	if(($_GET["vend"] == "airtime" || $_GET["vend"] == "data" || $_GET["vend"] == "cable"  || $_GET["vend"] == "bill" || $_GET["vend"] == "sms" || $_GET["vend"] == "cards" || $_GET["vend"] == "epins" || $_GET["vend"] == "datacard") && vp_getoption("for_active_user_balance") == "yes" ){

	
	//$url = get_site_url()."/vpaccount?vend=wallet";
$return = <<<EOT
<script>
document.addEventListener('DOMContentLoaded',function(){
	swal({
		title: "Insufficient Fund!!",
		text: "Please fund your account to continue with your transaction",
		icon: "warning",
		button: "Okay",
	  }).then((value) => {
		  window.location.href = '?vend=wallet';
	  });

});
</script>
EOT;

echo $return;

}

}

$return = <<<EOT
</body>
</html>

EOT;

echo $return;