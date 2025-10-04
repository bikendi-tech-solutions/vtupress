
<?php

if(isset($_GET["vend"]) && $_GET["vend"]=="kyc" && is_plugin_active("vprest/vprest.php") && vp_option_array($option_array,"resell") == "yes"){


?>
<div class="bg bg-primary text-white font-bold p-3 kyc-info">
Hello! Verifying your account is however mandatory to keep our services safe. This will also help us know you more better and serve you more better without issues.
<br>
<small class="p-1 mt-1 text-warning">Please take note that your personal informations is not shared or stored for un-authorized activities but instead makes us lift the ban of transaction limits. Thanks for understanding</small>
</div>

<div class="p-5 shadow rounded">

<?php
$id = get_current_user_id();
global $wpdb;
$table_name = $wpdb->prefix."vp_kyc";
$data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $id ORDER BY id DESC LIMIT 1");

if($data == NULL || strtolower($data[0]->status) == "retry" && strtolower($data[0]->status) != "review" && strtolower($data[0]->status) != "verfified" && strtolower($data[0]->status) != "ban" ){

  $fn = vp_getuser($id,"first_name");
  $ln = vp_getuser($id,"last_name");
?>
<form method="post" enctype="multipart/form-data">


<label for="name">Full Name</label>
<br>
<input type="text" placeholder="Full Name" id="name" class="name must_fill form-control" value="<?php echo $fn.' '.$ln;?>" disabled>
<br>

<label for="method">Method Of Verification</label>
<br>
<select class="method  must_fill form-control" id="method">
<option value="select" > --SELECT--</option>
<option value="bvn" > BVN </option>
<option value="nin"> National ID Card </option>
</select>
<br>
<!--
<option value="voters" > Voters Card </option>
<option value="drive" > Driving License </option>
<option value="pass" > International Passport </option>

-->

<div class="oda">


<div class="row">
<div class="col-12 col-sm photo_div d-none">
<label for="uploadfile">Your Passport/Selfie Photograph</label>
<br>
<input class="form-control  must_fill " type="file" name="file" id="uploadfile"  accept=".jpg,.jpeg,.png"/>
<br>
</div>
<div class="col-12 col-sm photo mt-3 mt-md-1  photo_div d-none">
<label for="uploadfill">Photo Of Method Of Verification As Chosen </label>
<br>
<input class="form-control  must_fill " type="file" name="fill" id="uploadfill"  accept=".jpg,.jpeg,.png"/>
<br>
</div>
<div class="bvn_value_div d-none">
<label for="uploadfill">Enter The Value: </label>
<br>

<div class="bbbvvvvnnn">
<input class="form-control  bvn_value " type="number" name="bvn_value" />
<code>A charge of  <?php echo $symbol.intval(vp_getoption('bvn_verification_charge'));?> will be deducted from your balance</code>
</div>


<br>
</div>
<div class="col-12 col-sm d-none accountDetails mt-3 mt-md-1">
<label for="accountNumber">Bank Account Number: </label>
<br>
<input class="form-control accountNumber  " type="number" name="accountNumber" id="accountNumber" />
<br>
<label for="accountNumber">Bank Name: </label>
<br>
<select name="bankCode" class="form-control bankCode " id="bankCode" >
  <option value=""> Select </option>
  <option value="044"> Access Bank Nigeria Plc </option>
  <option value="063"> 	Diamond Bank Plc </option>
  <option value="050"> 	Ecobank Nigeria </option>
  <option value="084"> 	Enterprise Bank Plc </option>
  <option value="070"> 	Fidelity Bank Plc </option>
  <option value="011"> 	First Bank of Nigeria Plc </option>
  <option value="214"> 	First City Monument Bank </option>
  <option value="058"> 	Guaranty Trust Bank Plc </option>
  <option value="030"> 	Heritaage Banking Company Ltd </option>
  <option value="301"> 	Jaiz Bank </option>
  <option value="082"> 	Keystone Bank Ltd </option>
  <option value="014"> 	Mainstreet Bank Plc </option>
  <option value="076"> 	Skye Bank Plc </option>
  <option value="039"> 	Stanbic IBTC Plc </option>
  <option value="232"> 	Sterling Bank Plc </option>
  <option value="032"> 	Union Bank Nigeria Plc </option>
  <option value="033"> 	United Bank for Africa Plc </option>
  <option value="215"> 	Unity Bank Plc </option>
  <option value="035"> 	WEMA Bank Plc </option>
  <option value="057"> 	Zenith Bank International </option>
</select>
<br>
<small>Make sure you used your personal and correct details above.</small>
<br>
</div>

</div>


<input type="button" name="Submit" value="Submit" class="send_file btn btn-success bg bg-success text-white" />
<br>
<code class="mandatory_text">**All fields are mandatory**</code>
</div>



</form>
<div class="row mt-3 visibility-hidden d-none">
<div class="col">

<div class="input-group">
<span class="input-group-text">LIMIT</span>
<span class="input-group-text"><?php echo $kyc_data[0]->kyc_limit;?></span>
</div>
<div class="input-group">
<span class="input-group-text">Duration</span>
<span class="input-group-text"><?php echo strtoupper($kyc_data[0]->duration);?></span>
</div>
</div>

<div class="col">

<div class="input-group">
<span class="input-group-text">USED</span>
<span class="input-group-text"><?php echo $kyc_total;?></span>
</div>
<div class="input-group">
<span class="input-group-text">Expires On</span>
<span class="input-group-text"><?php echo $kyc_end;?></span>
</div>
<div class="input-group">
<span class="input-group-text">Status</span>
<span class="input-group-text"><?php echo $kyc_status;?></span>
</div>
</div>


</div>
<?php
}
elseif(isset($data) && strtolower($data[0]->status) == "verified"){
	?>
	<script>
	jQuery(".kyc-info").hide();
	</script>
<div class="bg bg-success text-white font-bold p-3">
Account Verified!
<br>
Thank You For Understanding.
</div>
	<?php
}
elseif(isset($data) && strtolower($data[0]->status) == "review"){
	?>
	<script>
	jQuery(".kyc-info").hide();
	</script>
<div class="bg bg-warning text-black font-bold p-3">
Account Under Review!
<br>
The KYC Form will be reopened once your account status is set to retry.
</div>
	<?php
}
elseif(isset($data) && strtolower($data[0]->status) == "ban"){
	?>
	<script>
	jQuery(".kyc-info").hide();
	</script>
<div class="bg bg-danger text-white font-bold p-3">
Account Suspended!
<br>
Henceforth you cant use this website or any related website using this same program.
</div>
	<?php
}
?>
<script>
  jQuery(".mandatory_text").hide();
var valr = jQuery(".method").val();

if(valr == "select"){
jQuery(".oda").hide();
}
else{
jQuery(".oda").show();
}



jQuery(".method").on("change",function(){
	
var valu = jQuery(".method").val();

if(valu == "select"){
jQuery(".oda").hide();
}
else{
jQuery(".oda").show();

if(valu == "bvn" || valu == "nin"){
  jQuery(".bvn_value_div").removeClass("d-none");
  jQuery(".photo_div").addClass("d-none");
}else{
  jQuery(".bvn_value_div").addClass("d-none");
  jQuery(".photo_div").removeClass("d-none");

}

}

	
});



jQuery(".send_file").on("click",function(){
  var valu = jQuery(".method").val();
  var isFalse = false;
  jQuery(".must_fill").each(function(){

if($(this).val() == "" && valu != "bvn" && valu != "nin"){
jQuery(".mandatory_text").show();
isFalse = true;
}else{
  jQuery(".mandatory_text").hide();
  isFalse = false;
}

});

if(isFalse){
  return;
}

	jQuery("#cover-spin").show();
var formData = new FormData();


formData.append('name', jQuery(".name").val());
formData.append('bankName', jQuery(".bankCode option:selected").text());
formData.append('bankCode', jQuery(".bankCode").val());
formData.append('accountNumber', jQuery(".accountNumber").val());
formData.append('method', jQuery(".method").val());

if(valu != "bvn" && valu != "nin"){
formData.append('file', document.getElementById('uploadfile').files[0]);
formData.append('fill', document.getElementById('uploadfill').files[0]);
}



if(valu == "bvn" || valu == "nin"){
formData.append('bvn_value', jQuery(".bvn_value").val());
}
 
jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/template/classic/sections/kycupload.php'));?>",
data : formData,
dataType: 'text',
processData: false,// tell jQuery not to process the data
contentType: false, // tell jQuery not to set contentType
'cache': false,
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
  title: "Successfully logged!",
  text: "Thanks",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error Logging",
  text: "Click Why To See Reason",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
type : 'POST'
});

 
});

</script>


</div>



<?php
}
		
?>