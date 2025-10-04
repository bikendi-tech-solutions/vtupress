<?php
$option_array = json_decode(get_option("vp_options"),true);
if(vp_option_array($option_array,"vprun") != "block"){
$option_array = json_decode(get_option("vp_options"),true);
apply_filters("vpformfirst","");



$uniqidvalue = uniqid("1",false);

$paychoice = vp_option_array($option_array,"paychoice");
if($paychoice == "flutterwave"){
$endpoint = "pay.php";
}
else{
$endpoint = "pay.php";	
}
//$s = vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/process.php';

global $wpdb;
$vtutable = $wpdb->prefix."vtuchoice";
$vturesult = $wpdb->get_row("SELECT * FROM $vtutable WHERE ID = 1");
$choice = $vturesult->vtuchoice;


if($choice == "mobile"){
$network = "network";
}
else{
	$network ="network_id";
}

$id = get_current_user_id();
$data = get_userdata($id);
$plan = vp_getuser($id, "vr_plan", true);

$bal = vp_getuser($id, 'vp_bal', true);

vp_updateuser($id,'run_code',uniqid());
$session =  vp_getuser($id,'run_code',true);




global $wpdb;
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name WHERE name = '$plan'");
?>


<div class="container-md mt-3">
<?php vp_kyc_update();?>
    <div class="airtime-form p-3" style="border: 1px solid grey; border-radius: 5px;">
	
    <div class="mb-2">
        <form  class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>

        <div class="mb-2">
         <label for="company" class="form-label">Company</label>
            <select class="form-select form-select-sm bet-company" aria-label="form-select-sm example" id="company" name="company" >
                <option value="none" selected>---Select---</option>
                <?php 

                    for($i=0; $i<=15; $i++){
                       $compid = vp_getoption("cbetdata".$i);
                       $compname = vp_getoption("cbetdatan".$i);
                        if(!empty($compid) && !empty($compname)){
                        echo'
                            <option value="'.$compid.'" class="company-choiceModal">'.$compname.'</option>
                        ';
                        }
                        
                    }
				?>
            </select>
            <div id="validationServer04Feedback" class="invalid-feedback">
                <span class="company-error-message">Please Choose a Betting Company. </span>
              </div>
        </div>
		

            <div class="mb-2 visually-hidden">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="vpname" class="form-control airtime-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $data->user_login; ?>">
            </div>
            <div class="mb-2 visually-hidden">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="vpemail" class="form-control airtime-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $data->user_email; ?>">
            </div>
			<div class="mb-2 visually-hidden">
				<input type="hidden" id="tcode" name="tcode" value="cbet">
				<input type="hidden" id="datatcode" name="datatcode">
				<input type="hidden" id="thisnetwork" name="thisnetwork">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo $uniqidvalue;?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false)?>">
			</div>

            <div class="mb-2">
                <label for="customerId" class="form-label">Customer ID</label>
                <input id="customerId" name="customerid" list="beneficiaries" type="text" class="form-control airtime-number customerid" maxlength="11" placeholder="Customer ID" aria-label="Customer ID" aria-describedby="basic-addon1">
                <datalist id="beneficiaries">
<?php
$bens = explode(",",vp_getuser($id,"beneficiaries",true));
if(count($bens) >= 1 && vp_getoption("enable_beneficiaries") == "yes"){
    foreach($bens as $ben){
        if(!empty($ben)){
            echo "<option value='$ben'>";
        }
    }
}
?>
                </datalist>
                <div id="validationServer04Feedback" class="invalid-feedback">
                   Error: <span class="customerid-error-message"></span>.
                  </div>
            </div>

            <div class="mb-2">
                <label for="network" class="form-label">Original Amount</label>
                <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                    <input id="amt" name="amount" type="number" class="form-control bet-amount" onchange="calcit();" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="bet-amount-error-message"></span>
                      </div>
                </div>
                </div>
            </div>
											<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					?>
			<div class="mb-2">

                <label for="network" class="form-label">Amount To Pay</label>
	
                <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                    <input id="amttopay" type="number" class="form-control amttopay" max="<?php echo $bal;?>" placeholder="Amount To Pay" aria-label="Username" aria-describedby="basic-addon1" readonly>
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="bet-amount2pay-error-message"></span>
                      </div>
            </div>

            <?php
				}
				?>
			

            <div class="vstack gap-2">
			<?php

if(vp_option_array($option_array,"vpdebug") != "yes" || current_user_can("administrator")){
	?>
                <button type="button" class="w-full p-2 text-xs bg-secondary  font-bold text-white uppercase bg-indigo-600 rounded shadow fund-bet-wallet btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Purchase</button>
<?php
}
else{
    ?>
 <button onclick="alert('Currently Under Maintainance. Please Try Again Later.');" type="button" class="w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow on-dev btn btn-info" >Under Development</button>

    <?php
}
?>  
		   </div>
        </form>

        <!--The Modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Airtime Purchase Confirmation</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                    Bet Company : <span class="bet-company-confirm"></span><br>
                    Customer ID : <span class="customer-id-confirm"></span><br>
					Original Amount: <?php echo $symbol;?><span class="amount-confirm"></span><br>
                    Charge : <?php echo $symbol;?><span class="charge-confirm"></span><br>
                    Amount To Pay : <?php echo $symbol;?><span class="amttopay2" ></span><br>
                    Status : <span class="bet-status-confirm"></span><br>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="number" name="pin">
					</div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="p-2 text-xs font-bold text-dark uppercase bg-gray-600 rounded shadow funding-proceed-cancled btn-danger" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" name="wallet" id="wallet" class=" p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow funding-proceed btn-success" form="cfor">Proceed</button>
                </div>
              </div>
            </div>
          
          
          
          
    </div>
<script>


function calcit(){
var original = parseInt(jQuery("#amt").val());
var topay = jQuery("#amttopay");
var charge = <?php echo intval(vp_getoption("betcharge"));?>;


jQuery(".charge-confirm").text(charge);
var the_amo = original + charge;
var the_amount = the_amo;
topay.val(the_amount);



}


//END OF CHECK FOR AIRTIMECHOICE VAL BY DEFAULT


//ON PURCHASE CLICK
jQuery(".fund-bet-wallet").on("click",function($){


var request_id =  jQuery("#uniqidvalue").val();

var bet_company = jQuery(".bet-company").val();
var customerid = jQuery(".customerid").val();
var original_amount = jQuery(".bet-amount").val();
var amount_to_pay = jQuery(".amttopay").val();
var charge = <?php echo intval(vp_getoption("betcharge"));?>;
var url = jQuery("#url");

<?php
	echo'
url.val("betbase'.vp_option_array($option_array,"betendpoint")."betpostdata1".'='."betpostvalue1".'&'.vp_option_array($option_array,"betrequest_id").'="+request_id+"&'."betpostdata2".'='."betpostvalue2".'&'.vp_option_array($option_array,"betpostdata3").'='.vp_option_array($option_array,"betpostvalue3").'&'.vp_option_array($option_array,"betpostdata4").'='.vp_option_array($option_array,"betpostvalue4").'&'.vp_option_array($option_array,"betpostdata5").'='.vp_option_array($option_array,"betpostvalue5").'&'.vp_option_array($option_array,"betcompanyattribute").'="+bet_company+"&'.vp_option_array($option_array,"betamountattribute").'="+original_amount+"&'.vp_option_array($option_array,"betcustomeridattribute").'="+customerid);

	';
	?>

//VERIFICATION FIELD

if(customerid == "" ){
jQuery(".customerid-error-message").text("");

jQuery("input.customerid").removeClass("is-valid");
 jQuery("input.customerid").addClass("is-invalid");
jQuery(".customerid-error-message").text("The Phone Number Must Be 11 Digits");
 


}else{
    jQuery(".customerid-error-message").text("");

jQuery("input.customerid").removeClass("is-invalid");
 jQuery("input.customerid").addClass("is-valid");
    
}


if(bet_company == "none" || bet_company == ""){
jQuery(".company-error-message").text("");

jQuery("input.bet-company").removeClass("is-valid");
 jQuery("input.bet-company").addClass("is-invalid");
jQuery(".company-error-message").text("Please Choose A Betting Company");
 

}else{
jQuery(".company-error-message").text("");

jQuery("input.bet-company").removeClass("is-invalid");
 jQuery("input.bet-company").addClass("is-valid");

 
}

if(original_amount == "" || parseInt(original_amount) < 100){
jQuery(".bet-amount-error-message").text("");

jQuery("input.bet-amount").removeClass("is-valid");
 jQuery("input.bet-amount").addClass("is-invalid");
jQuery(".bet-amount-error-message").text("Fund Amount Should Be 100 And Above");
 

}else{
jQuery(".bet-amount-error-message").text("");

jQuery("input.bet-amount").removeClass("is-invalid");
jQuery("input.bet-amount").addClass("is-valid");
  
}


if((parseInt(original_amount) + charge) != amount_to_pay){
    jQuery(".bet-amount2pay-error-message").text("");

    jQuery(".amttopay").removeClass("is-valid");
    jQuery(".amttopay").addClass("is-invalid");

    jQuery(".bet-amount2pay-error-message").text("Original Amount + Charge Isn't Correct With This");

}else{
    jQuery(".bet-amount2pay-error-message").text("");

jQuery(".amttopay").removeClass("is-invalid");
jQuery(".amttopay").addClass("is-valid"); 
}





var customerid_class = jQuery("input.customerid").hasClass("is-invalid");
var company_class = jQuery("select.bet-company").hasClass("is-invalid");
var original_amount_class = jQuery("input.bet-amount").hasClass("is-invalid");
var amount2pay_class = jQuery("input.amttopay").hasClass("is-invalid");
var charge = <?php echo intval(vp_getoption("betcharge"));?>;


if(customerid_class || company_class || original_amount_class  || amount2pay_class){


    jQuery(".bet-company-confirm").text( jQuery("select.bet-company option:selected").text());
    jQuery(".customer-id-confirm").text( jQuery("input.customerid").val());
    jQuery(".amount-confirm").text( jQuery("input.bet-amount").val());
    jQuery(".charge-confirm").text(charge);
    jQuery(".amttopay2").text(jQuery("input.amttopay").val());
    jQuery(".bet-status-confirm").text("There's An Error You Need To Fix");
    
    jQuery(".funding-proceed").hide();
    return;
    
}
else{
    jQuery(".bet-company-confirm").text( jQuery("select.bet-company option:selected").text());
    jQuery(".customer-id-confirm").text( jQuery("input.customerid").val());
    jQuery(".amount-confirm").text( jQuery("input.bet-amount").val());
    jQuery(".charge-confirm").text(charge);
    jQuery(".amttopay2").text(jQuery("input.amttopay").val());
    jQuery(".bet-status-confirm").text("Correct");

    jQuery(".funding-proceed").show(); 
}
    

});

//END OF PURCHASE CLICK

jQuery(".funding-proceed").click(function(){

jQuery(".fund-bet-wallet").click();



var request_id =  jQuery("#uniqidvalue").val();

var bet_company = jQuery(".bet-company").val();
var bet_company_text = jQuery(".bet-company option:selected").text();
var customerid = jQuery(".customerid").val();
var original_amount = jQuery(".bet-amount").val();
var amount_to_pay = jQuery(".amttopay").val();
var charge = <?php echo intval(vp_getoption("betcharge"));?>;

var url = jQuery("#url");

<?php
	echo'
url.val("betbase'.vp_option_array($option_array,"betendpoint")."betpostdata1".'='."betpostvalue1".'&'.vp_option_array($option_array,"betrequest_id").'="+request_id+"&'."betpostdata2".'='."betpostvalue2".'&'.vp_option_array($option_array,"betpostdata3").'='.vp_option_array($option_array,"betpostvalue3").'&'.vp_option_array($option_array,"betpostdata4").'='.vp_option_array($option_array,"betpostvalue4").'&'.vp_option_array($option_array,"betpostdata5").'='.vp_option_array($option_array,"betpostvalue5").'&'.vp_option_array($option_array,"betcompanyattribute").'="+bet_company+"&'.vp_option_array($option_array,"betamountattribute").'="+original_amount+"&'.vp_option_array($option_array,"betcustomeridattribute").'="+customerid);

	';
?>
	
	
jQuery('.btn-close').trigger('click');
	jQuery("#cover-spin").show();
	
var obj = {};
obj["vend"] = "vend";
obj["bet_company"] = bet_company;
obj["customerid"] = customerid;
obj["amount"] = original_amount;
obj["vpname"] = jQuery(".airtime-name").val();
obj["vpemail"] = jQuery(".airtime-email").val();
obj["tcode"] = jQuery("#tcode").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["pin"] = jQuery(".pin").val();
obj["run_code"] = "<?php echo $session;?>";
jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/vend.php'));?>',
  data: obj,
 dataType: 'text',
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
      let result = data.includes("status");
      
        if(data == "100" || data == 100){
		  swal({
  title: "Transaction Successful!",
  text: "You've funded Your "+bet_company_text+" wallet ("+customerid+") with "+original_amount,
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data == "pin"){
		jQuery("#cover-spin").hide();
swal({
  title: "Verification Failed",
  text: "Pin Incorrect",
  icon: "error",
  button: "Okay",
}).then((value) => {
	
});   
	  }
      else if(data == "processing"){
		jQuery("#cover-spin").hide();
swal({
  title: "Processing",
  text: "You will be updated shortly",
  icon: "error",
  button: "Okay",
}).then((value) => {
	location.reload();
});   
	  }
	  else if(data == "browser"){
		jQuery("#cover-spin").hide();
swal({
  title: "Verification Failed",
  text: "Browser Not Supported",
  icon: "error",
  button: "Okay",
}).then((value) => {
	
});   
	  }
      else if(data == "202"){
		jQuery("#cover-spin").hide();
swal({
  title: "Transaction Failed",
  text: "Funds Reversed",
  icon: "error",
  button: "Okay",
}).then((value) => {
	location.reload();
});   
	  }
      else if(result != true){
		jQuery("#cover-spin").hide();
swal({
  title: "Oops!",
  text: data,
  icon: "error",
  button: "Okay",
}).then((value) => {
	
});     
      }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    <?php
    if(vp_getoption("hide_why") != "yes"){
?>
    cancel: "Why?",
    <?php
    }
    ?>

    defeat: "Okay",
  },
  title: "Transaction Processing",
  text: "Funds Will Be Reversed If Debited And Status Marked Failed",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
	swal(data,{
  icon: "info",
  button: "Okay"
}).then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
   	location.reload();
  }
});

  }
});
	  }
  },
  type: 'POST'
});

});
    </script>
</div>
</div>
</div>

<?php

do_action("formwallet");
}
else{
$em = vp_option_array($option_array,'admin_email');
echo "<h1>Access Denied at the moment. Please try again later or contact-us on $em . <br> Thanks";	
	
}
?>