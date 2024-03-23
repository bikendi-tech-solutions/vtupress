<?php
if(isset($_GET["vend"]) && $_GET["vend"]=="transfer" && vp_getoption('wallet_to_wallet') == "yes" && isset($level) && strtolower($level[0]->transfer) == "yes" ){

echo'
<style>
	.side-wallet-w{background-color: white !important;
    padding: 20px !important;
    text-align: center !important;
    border-radius: 10px !important;
	border:2px solid purple;
		  }
		  
</style>
		
<div id="side-wallet-w" class="side-wallet-w dark-white">
<div class="fund-other-wallet">

<form method="post" target="_self" >

<label for="user-id" class="form-label">User ID</label><br>

<div class="input-group mb-2">
<input type="number" name="user_id" class="form-control user_id" required><br>
<span class="dark-white input-group-text" id="basic-addon1">
                            <div class="spinner-grow text-secondary visually-hidden" role="status">
                            </div>
                            <input type="button" class="btn p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  verify-user btn-secondary" value="Verify">
 </span>
</div>

<label for="amount" class="form-label">Amount [Min. '.esc_html(vp_getoption('minimum_amount_transferable')).']</label><br>
<input type="number" name="amount" class="form-control mb-2 amount" max="'.$bal.'" required><br>

<input type="button" name="fund_other" class="form-submit mb-2 btn-primary w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow fund_other" value="Transfer"><br>

</form>

<script>

/*VERIFY USERS JQUERY*/
jQuery(".verify-user").click(function(){
	jQuery(".spinner-grow").removeClass("visually-hidden");
	var user_id = jQuery(".user_id").val();
	jQuery(".verify-user").hide();
	var obj = {};
obj["verify_user"] = "verify_user";
obj["user_id"] = user_id;


jQuery.ajax({
  url: "'.esc_url(plugins_url("vtupress/vend.php")).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
	jQuery(".spinner-grow").addClass("visually-hidden");
	jQuery(".verify-user").show();
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
	jQuery(".spinner-grow").addClass("visually-hidden");
	jQuery(".verify-user").show();
        if(data.status == "100" && data.user_name != "" ){
		  swal({
  title: data.user_name,
  text: "Verification Confirmed Valid",
  icon: "success",
  button: "Okay",
});
	  }
	  else{
	jQuery(".spinner-grow").addClass("visually-hidden");
	jQuery(".verify-user").show();
	 swal({
  title: "Invalid",
  text: "No User With ID "+jQuery(".user_id").val(),
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});




/*FUND OTHER JQUERY*/
jQuery(".fund_other").click(function(){

if(jQuery(".amount").val() <= '.$bal.'){
jQuery("#cover-spin").show();
	
var obj = {};
obj["fund_other"] = "fund_other";
obj["amount"] = jQuery(".amount").val();
obj["user_id"] = jQuery(".user_id").val();

jQuery.ajax({
  url: "'.esc_url(plugins_url("vtupress/vend.php")).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,

  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
	jQuery(".spinner-grow").addClass("visually-hidden");
	jQuery(".verify-user").show();
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
	 jQuery(".user-balance").text(data.balance);
	jQuery("#cover-spin").hide();
        if(data.status == "100"){
		  swal({
  title: "Transaction Successful!",
  text: "User Credited",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data.status == "200"){
		  var msg = data.balance;
	  swal({
  title: "Not Successful!",
  text: msg,
  icon: "error",
  button: "Okay",
});
	  }
  else{
	  swal({
  title: "Not Successful!",
  text: "There Was A Problem Processing Transfer!",
  icon: "error",
  button: "Okay",
});
}

  },
  type: "POST"
});
}else{
	  swal({
  title: "Not Successful!",
  text: "Balance Too Low!",
  icon: "error",
  button: "Okay",
});
}

});
</script>

<br>
';

echo'

</div>
		
		
		
		</div>
		
		<!-- Account End -->
		
		';
		
}
else{

echo "<h1>TRANSFER NOT ENABLED FOR THIS PLAN OR HAS BEEN TURNED OFF GENERALLY</h1>";
	
}

?>