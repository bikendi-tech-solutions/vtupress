<?php

if(is_plugin_active('vpmlm/vpmlm.php')  && vp_option_array($option_array,'mlm') == "yes" ){
if(isset($_GET["vend"]) && $_GET["vend"]=="withdraw"){
				
				
				if($total_bal_with >= $minwithle){
			echo'
			<style>
.with-form-container {
    background-color: #e9e9ef !important;
    padding: 20px !important;
    text-align: center !important;
    width: 83% !important;
    margin-left: auto !important;
    margin-right: auto !important;
    box-sizing: border-box !important;
    box-shadow: -5px 5px 20px #00000038 !important;
}

span.with-form .form {
      background-color: #bcc5bc !important;
    padding: 20px !important;
    box-sizing: border-box !important;
    border-radius: 10px !important;
}


.form form label {
    line-height: 1.5em !important;
    font-family: arial !important;
    float:left !important;
    margin-top: 5px;
}
.form form input, .form form select{
width:100% !important;
}


.form form {
    width: fit-content !important;
    margin-left: auto !important;
    margin-right: auto !important;
}

	

</style>

<div class="with-form-container dark-white">
<div class="input-group mb-2 mt-1">
';
if(strtolower(vp_option_array($option_array,"allow_withdrawal")) == "yes"){
	echo'
<button class="btn btn-primary btn-sm show-with-form">Withdraw</button>
';
}
echo'
</div>

<span class="with-form">
    <div class="form">
    <form method="post" target="_self" class="withdraw_now">

<label class="form-label visually-hidden dark">Source</label>
   <select name="source" class="source">
   <option value="bonus">Total Bonus Balance</option>
   <option value="wallet">My Wallet Balance</option>
   </select>

<label class="form-label visually-hidden">Amount To Be Deducted</label>
        <input class="form-control frombonus" type="number" value="'.$total_bal_with.'" readonly max="'.$total_bal_with.'">
        <input class="form-control fromwallet" type="number" value="'.$bal.'" max="'.$bal.'">
		
		
<label class="form-label dark">Withdraw To</label><br>
        <select name="withto" class="withto" class="form-select">
            <option value="wallet" class="towallet dark">Wallet</option>
			';
			if(strtolower(vp_option_array($option_array,"allow_to_bank")) == "yes"){
				echo'
            <option value="bank" class="tobank dark">Bank Account</option>
			';
			}
			echo'
    </select>
            <div class="bankdetails" style="display:none;width:100%;">
			<br>
			<textarea class="form-control" name="bankdetails" style="width:100%;" value="Enter your bank details here"></textarea>
			</div>
            <br>
        <input type="button" value="submit" name="withdrawit" class="w-full bg-success p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  withdrawit"><br>
        
        
</form>
    
    </div>
    
    </div>
        </span>

		
		
		<script>
		//Default show frombonus and hide fromwallet
jQuery(".fromwallet").hide();
jQuery(".frombonus").show();
		
		//get value and display
var showthis = jQuery(".source").val();

if(showthis == "bonus"){
	jQuery(".fromwallet").hide();
	jQuery(".fromwallet").removeClass("withamt");
	jQuery(".fromwallet").removeAttr("name","withamt");
	
	jQuery(".frombonus").show();
	jQuery(".frombonus").addClass("withamt");
	jQuery(".frombonus").attr("name","withamt");
	
	jQuery(".tobank").show();
	jQuery(".towallet").show();
	
	

}
else if(showthis == "wallet"){
	jQuery(".frombonus").hide();
	jQuery(".frombonus").removeClass("withamt");
	jQuery(".frombonus").removeAttr("name","withamt");
	
	jQuery(".fromwallet").show();
	jQuery(".fromwallet").addClass("withamt");
	jQuery(".fromwallet").attr("name","withamt");
	
	jQuery(".tobank").show();
	jQuery(".towallet").hide();
}



		
jQuery(".source").on("change",function(){
var showth = jQuery(".source").val();

if(showth == "bonus"){
	jQuery(".fromwallet").hide();
	jQuery(".fromwallet").removeClass("withamt");
	jQuery(".fromwallet").removeAttr("name","withamt");
	
	jQuery(".frombonus").show();
	jQuery(".frombonus").addClass("withamt");
	jQuery(".frombonus").attr("name","withamt");
	
	jQuery(".tobank").show();
	jQuery(".towallet").show();
	
	

}
else if(showth == "wallet"){
	jQuery(".frombonus").hide();
	jQuery(".frombonus").removeClass("withamt");
	jQuery(".frombonus").removeAttr("name","withamt");
	
	jQuery(".fromwallet").show();
	jQuery(".fromwallet").addClass("withamt");
	jQuery(".fromwallet").attr("name","withamt");
	
	jQuery(".tobank").show();
	jQuery(".towallet").hide();
}

});	
		
		
		
	
jQuery(".with-history").hide();

jQuery(".show-with-form").click(function(){
jQuery(".with-history").hide();
jQuery(".with-form").show();
});

jQuery(".show-with-history").click(function(){
jQuery(".with-history").show();
jQuery(".with-form").hide();
});
	

jQuery(".with-history").hide();
	
	


	
	

		
jQuery(".withdrawit").click(function(){
	jQuery("#cover-spin").show();
	
	var obj = {};
	var toatl_input = jQuery(".withdraw_now input, .withdraw_now select, .withdraw_now textarea").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".withdraw_now input, .withdraw_now select, .withdraw_now textarea").eq(run_obj);

var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}	
}

jQuery.ajax({
  url: "'.esc_url(plugins_url("vtupress/vend.php")).'",
  data: obj,
  dataType: "json",
  "cache": false,
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
  success: function(data){
  jQuery("#cover-spin").hide();
        if(data.status == "100" ){
	jQuery("input.currbal").val(0);
		  swal({
  title: "Withdrawn TO Wallet",
  text: "Refresh The Page If Not Refreshed Automatically",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data.status == "101" ){
		  jQuery("input.currbal").val(0);
	 swal({
  title: "Withdrawal Request Sent",
  text: "Pending Approval",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	 else if(data.status == "400" ){
		  jQuery("input.currbal").val(0);
	 swal({
  title: "BAD!!!",
  text: "Insufficient Balance",
  icon: "error",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  	 else if(data.status == "450" ){
		  jQuery("input.currbal").val(0);
	 swal({
  title: "BAD!!!",
  text: "Can\'t withdraw from wallet to wallet",
  icon: "error",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data.status == "410" ){
		  jQuery("input.currbal").val(0);
	 swal({
  title: "BAD!!!",
  text: "The withdraw amount must be your total bonus amount",
  icon: "error",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
  },
  type: "POST"
});

});

		
		
		
		
		
		jQuery(".withto").on("change", function(){
	var option = jQuery(".withto option:selected").val();
	if(option == "bank"){
		jQuery(".bankdetails").show();
	}
	else{
		jQuery(".bankdetails").hide();
	}
		});
		</script>

</div>
			';
			
			}
			else{
				
				echo"
				<style>
				h3.h3{
					text-align:center;
				}
				
				</style>
				<h3 class=\"h3\">
				You are not eligible for a withdrawal at the moment - <br>
				Your Withdrawal Balance is: $total_bal_with <br>
				Minimum Withdrawable amount is: $minwithle<br>
				<h3>
				
				";
				
				
			}
			
}
			
if(isset($_GET["vend"]) && $_GET["vend"]=="referrals"){
				$curid = get_current_user_id();
			?>
			<!--REFERAL DETAILS-->
			
			<main>

  <section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="fw-light">Referrees Details</h1>
        <p class="lead text-muted">We encourage you to build your network! Follow-up and Earn More on activities.</p>
        <p>
          <a class="btn btn-primary my-2 first-level-btn" style="color:white;">First Level</a>
          <a class="btn btn-secondary my-2 second-level-btn" style="color:white;">Second Level</a>
          <a class="btn btn-secondary my-2 third-level-btn" style="color:white;">Third Level</a>
        </p>
      </div>
    </div>
  </section>

<div class="album py-5 bg-light">
<div class="container first-level this-ref">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
	  
	  
	  <?php
	  $refs = 	vp_user_array($user_array,$curid, "vp_tot_ref_id", true);
	  $array = explode(",",$refs);
	  foreach($array as $id){
		  
		  if(!empty($id) && $id != "0" && $id != false){
			  
			  $username = get_userdata($id)->user_login;
			  $email = get_userdata($id)->user_email;
			  $phone = vp_user_array($user_array,$id, "vp_phone" , true);
			  $plan = vp_user_array($user_array,$id, "vr_plan" , true);
			  $reg = get_userdata($id)->user_registered;
			  
	  echo'
        <div class="col">
         <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>'.$username.' Details</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">'.$username.'</text></svg>

            <div class="card-body">
              <p class="card-text"><i class="fas fa-envelope" title="User Email"></i> - '.$email.'</p>
              <p class="card-text"><i class="fas fa-phone-alt" title="User Phone Number"></i> - '.$phone.'</p>
              <p class="card-text"><i class="far fa-calendar-alt" title="User Registeration Date"></i> - '.$reg.'</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="whatsapp://send?phone=234'.vp_option_array($option_array,"vp_whatsapp").'&amp;text=Hi,+I+Wanna+Report+A+Case+On+'.$username.'"><button type="button" class="btn btn-sm btn-outline-secondary">Report</button></a>
                  <a href="tel:+234'.$phone.'"><button type="button" class="btn btn-sm btn-outline-secondary">Call</button></a>
                </div>
                <small class="text-muted">'.$plan.'</small>
              </div>
            </div>
          </div>
        </div>
		';
		
		  }
	  }
   
   ?>
   
        </div>
      </div>
    
	
<div class="container second-level this-ref">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
	  
	  
	  <?php
	  $refs = 	vp_user_array($user_array,$curid, "vp_tot_in_ref_id", true);
	  $array = explode(",",$refs);
	  foreach($array as $id){
		  
		  if(!empty($id) && $id != "0" && $id != false){
			  
			  $username = get_userdata($id)->user_login;
			  $email = get_userdata($id)->user_email;
			  $phone = vp_user_array($user_array,$id, "vp_phone" , true);
			  $plan = vp_user_array($user_array,$id, "vr_plan" , true);
			  $reg = get_userdata($id)->user_registered;
			  
	  echo'
        <div class="col">
         <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>'.$username.' Details</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">'.$username.'</text></svg>

            <div class="card-body">
              <p class="card-text"><i class="fas fa-envelope" title="User Email"></i> - '.$email.'</p>
              <p class="card-text"><i class="fas fa-phone-alt" title="User Phone Number"></i> - '.$phone.'</p>
              <p class="card-text"><i class="far fa-calendar-alt" title="User Registeration Date"></i> - '.$reg.'</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="whatsapp://send?phone=234'.vp_option_array($option_array,"vp_whatsapp").'&amp;text=Hi,+I+Wanna+Report+A+Case+On+'.$username.'"><button type="button" class="btn btn-sm btn-outline-secondary">Report</button></a>
                  <a href="tel:+234'.$phone.'"><button type="button" class="btn btn-sm btn-outline-secondary">Call</button></a>
                </div>
                <small class="text-muted">'.$plan.'</small>
              </div>
            </div>
          </div>
        </div>
		';
		
		  }
	  }
   
   ?>
   
        </div>
      </div>
   
	
<div class="container third-level this-ref">

      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
	  
	  
	  <?php
	  $refs = 	vp_user_array($user_array,$curid, "vp_tot_in_ref3_id", true);
	  $array = explode(",",$refs);
	  foreach($array as $id){
		  
		  if(!empty($id) && $id != "0" && $id != false){
			  
			  $username = get_userdata($id)->user_login;
			  $email = get_userdata($id)->user_email;
			  $phone = vp_user_array($user_array,$id, "vp_phone" , true);
			  $plan = vp_user_array($user_array,$id, "vr_plan" , true);
			  $reg = get_userdata($id)->user_registered;
			  
	  echo'
        <div class="col">
         <div class="card shadow-sm">
            <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>'.$username.' Details</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">'.$username.'</text></svg>

            <div class="card-body">
              <p class="card-text"><i class="fas fa-envelope" title="User Email"></i> - '.$email.'</p>
              <p class="card-text"><i class="fas fa-phone-alt" title="User Phone Number"></i> - '.$phone.'</p>
              <p class="card-text"><i class="far fa-calendar-alt" title="User Registeration Date"></i> - '.$reg.'</p>
              <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                  <a href="whatsapp://send?phone=234'.vp_option_array($option_array,"vp_whatsapp").'&amp;text=Hi,+I+Wanna+Report+A+Case+On+'.$username.'"><button type="button" class="btn btn-sm btn-outline-secondary">Report</button></a>
                  <a href="tel:+234'.$phone.'"><button type="button" class="btn btn-sm btn-outline-secondary">Call</button></a>
                </div>
                <small class="text-muted">'.$plan.'</small>
              </div>
            </div>
          </div>
        </div>
		';
		
		  }
	  }
   
   ?>
   
        </div>
      </div>
  
</div>

<script>
jQuery(".second-level").hide();
jQuery(".third-level").hide();

jQuery(".first-level-btn").on("click",function(){
jQuery(".this-ref").addClass("visually-hidden");
jQuery(".first-level").removeClass("visually-hidden");
jQuery(".first-level").show();
	});
	
jQuery(".second-level-btn").on("click",function(){
jQuery(".this-ref").addClass("visually-hidden");
jQuery(".second-level").removeClass("visually-hidden");
jQuery(".second-level").show();
	});
	
jQuery(".third-level-btn").on("click",function(){
jQuery(".this-ref").addClass("visually-hidden");
jQuery(".third-level").removeClass("visually-hidden");
jQuery(".third-level").show();
	});
</script>
</main>
<footer class="text-muted py-5">
  <div class="container">
    <p class="float-end mb-1">
      <a href="#">Back to top</a>
    </p>
    <p class="mb-1">The more you Refer, the more your build your network... The bigger your network the larger your purse</p>
     </div>
</footer>

			
			
			<?php
			
}
			
}

?>