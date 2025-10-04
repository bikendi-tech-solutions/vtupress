<?php
$option_array = json_decode(get_option("vp_options"),true);
if(vp_option_array($option_array,"vprun") != "block"){
$option_array = json_decode(get_option("vp_options"),true);
apply_filters("vpformfirst","");

$uniqidvalue = date("YmdHi").uniqid("1",false);

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
else{$network ="network_id";}
$id = get_current_user_id();
$bill = get_userdata($id);
$plan = vp_getuser($id, "vr_plan", true);

vp_updateuser($id,'run_code',uniqid());
$session =  vp_getuser($id,'run_code',true);

$bal = vp_getuser($id, 'vp_bal', true);

global $wpdb;
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name WHERE name = '$plan'");
?>

<div class="container-md mt-3">
		<?php vp_kyc_update();?>
    <div class="bill-form p-3" style="border: 1px solid grey; border-radius: 5px;">
        <div class="mb-2">

            <form id="cfor"  class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>
            <div class="mb-2">
                <label for="network" class="form-label">Disco Type</label>
                <select name="type" id="billtypesel" class="form-select form-select-sm bill-type" aria-label=".form-select-sm example">
                    <option value="none" selected>---Select---</option>
                    <?php
                    for($i=0; $i<=3; $i++){
                    $doos = vp_option_array($option_array,"billid".$i);
                    if($doos != ""){
                     echo '<option value="'.vp_option_array($option_array,"billid".$i).'" id="'.$i.'" >'.vp_option_array($option_array,"billname".$i).'</option>';
                      }
                      }
                      ?>
                </select>
                <div id="validationServer04Feedback" class="invalid-feedback">
                    <span class="bill-select-network">Please Choose a Disco. </span>
                  </div>
                </div>
            <div class="mb-2 d-bill">
                    <label for="billplan" class="form-label">Disco Plan</label>
                    <select name="cbill" id="cbillsel" class="form-select form-select-sm bill-plan" aria-label=".form-select-sm example">
                    <?php
                     for($i=0; $i<=35; $i++){
                      $doos = vp_option_array($option_array,"cbill".$i);

                      if($doos != ""){
                      echo '<option value="'.vp_option_array($option_array,"cbill".$i).'" id="'.$i.'">'.vp_option_array($option_array,"cbilln".$i).'</option>';
                      }
                      }

                     ?>

                    </select>

                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <span class="bill-select-network">Please Choose A Disco Plan. </span>
                      </div>
            </div>

			<div class="mb-2 visually-hidden">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="vpname" class="form-control bill-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $bill->user_login; ?>">
            </div>
            <div class="mb-2 visually-hidden">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="vpemail" class="form-control bill-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $bill->user_email; ?>">
            </div>
			<div class="mb-2 visually-hidden">
				<input type="hidden" id="tcode" name="tcode" value="cbill">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo $uniqidvalue;?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false)?>">
			</div>

               <div class="mb-2 the-meter">
                    <label for="phone" class="form-label">Meter Number</label>
                    <div class="input-group mb-2" >
                    <input name="meterno" id="cmeterno" list="beneficiaries" type="number" class="form-control bill-meter meter-number" placeholder="Meter Number" aria-label="Meter Number" aria-describedby="basic-addon1">
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
                    <span class="input-group-text" id="basic-addon1">
                            <div class="spinner-grow text-secondary visually-hidden" role="status">
                            </div>
                            <input type="button" class="btn verify-bill btn-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow " value="Verify">
                    </span>
                    </div>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                       Error: <span class="number-error-message"></span>.
                      </div>
                </div>
                <div class="mb-2">
                    <label for="network" class="form-label">Amount</label>
                    <div class="input-group mb-2" >
                        <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                        <input onchange="calcit();" type="number" class="form-control bill-amount" max="<?php echo $bal;?>" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1"  id="amt" name="amount">
                        <span class="input-group-text" id="basic-addon1">.00</span>
                    </div> 
                </div>
											<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					?>
			<div class="mb-2">

						<?php
					if(vp_option_array($option_array,"discount_method") == "direct"){
					?>
                <label for="network" class="form-label">Amount To Pay + Charge(<?php echo floatval(vp_option_array($option_array,"bill_charge"));?>)</label>
				<?php
					}
					else{
				?>
                <label for="network" class="form-label">Charge Back Bonus</label>
				<?php	
					}
				
					?>
                <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                    <input id="amttopay" type="number" class="form-control amttopay" max="<?php echo $bal;?>" placeholder="Amount To Pay" aria-label="Username" aria-describedby="basic-addon1" readonly>
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="bill-amount-error-message"></span>
                      </div>
                </div>
            </div>
			<?php
				}
				?>
                <div class="vstack gap-2">
				<?php
if(current_user_can("administrator")){
	?>
                <button type="button" class="btn btn-outline-secondary view-url">View Url</button>
<?php 
}
?>
			<?php
if(vp_option_array($option_array,"vpdebug") != "yes" || current_user_can("administrator")){
	?>
                    <button type="button" class="w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-bill" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Purchase</button>
           <?php
}
else{
  ?>
<button onclick="alert('Currently Under Maintainance. Please Try Again Later.');" type="button" class="w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow on-dev" >Under Development</button>

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
                      <h5 class="modal-title" id="exampleModalLabel">Bill Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div>
                        Meter Type: <span class="bill-type-confirm"></span><br>
                        Meter No: <span class="bill-meter-confirm"></span><br>
                        Original Amount : <?php echo $symbol;?><span class="bill-amount-confirm"></span><br>
                        Charge : <?php echo $symbol;?><span class=""><?php echo floatval(vp_option_array($option_array,"bill_charge"));?></span><br>
					<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					
					if(vp_option_array($option_array,"discount_method") == "direct"){
					?>
					Amount To Pay : <?php echo $symbol;?><span class="amttopay2" ></span><br>
					Discount On Original Amount: <span class="discount-amount-confirm"></span> <br>
					<?php
					}else{
					?>
					Charge Back Bonus : <?php echo $symbol;?><span class="amttopay2" ></span><br>
					Commission : <span class="discount-amount-confirm"></span><br>	
					<?php	
					}
				};
					?>
                        Status : <span class="bill-status-confirm"></span><br>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="password" name="pin">
					</div>
					</div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="p-2 text-xs font-bold text-dark uppercase bg-gray-600 rounded shadow bill-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" name="wallet" id="wallet" class=" p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow bill-proceed" form="cfor">Proceed</button>
                    </div>
                  </div>
                </div>
            </div>
    <script>
	
	

function calcit(){
var ori = jQuery("#amt").val();
var original =  parseFloat(ori)+<?php echo floatval(vp_option_array($option_array,"bill_charge"));?>;
var topay = jQuery("#amttopay");
var discount = "";
<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
discount = <?php echo floatval($level[0]->bill_prepaid);?>;
<?php
}
?>
var the_amo = (original* discount)/100;
var the_amount = original - the_amo;

								<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					
					if(vp_option_array($option_array,"discount_method") == "direct"){
					?>
topay.val(the_amount);
jQuery(".amttopay2").text(the_amount);
<?php
					}
					else{
					?>
topay.val(the_amo);
jQuery(".amttopay2").text(the_amo);
<?php
				}
				}
				?>


}

	
	
	
	
	
	
	
        //when purchase is clicked
	jQuery(".d-bill").hide();
	jQuery(".the-meter").hide();
	
	jQuery(".bill-type").val("none");
    jQuery(".purchase-bill").on("click",function($){

    var request_id =  jQuery("#uniqidvalue").val();
    var cmeter = jQuery(".bill-meter").val();
    var ctype = jQuery(".bill-type").val();
    var camount = jQuery(".bill-amount").val();
    var cplan = jQuery(".bill-plan").val();
	
<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
	jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->bill_prepaid);?>%");
<?php
}
?>
    
    var url = jQuery("#url").val(lin);
    
    var durl = jQuery("#url").val();

var bill_type = jQuery("select.bill-type option:selected").text(); //check the bill type
var bill_iuc_length = jQuery("input.bill-meter").val().length; //check the length of the IUC field
var bill_amount = jQuery("#amttopay").val();
var bill_plan  = jQuery("select.bill-plan option:selected").text();

    if(bill_type != "---Select---"){
        jQuery("select.bill-plan").show();
        jQuery("select.bill-type").removeClass("is-invalid");
        jQuery("select.bill-type").addClass("is-valid");
    if( jQuery("input.bill-meter").hasClass("is-invalid")){
        
        jQuery(".number-error-message").text("Please Enter An Iuc Number");
    }
    }
    else{
        jQuery("select.bill-type").removeClass("is-valid");
        jQuery("select.bill-type").addClass("is-invalid");
		jQuery("select.bill-plan").hide();
        //jQuery(".number-error-message").text("Please Choose A Network");
    }
    
    if(bill_plan != "---Select---"){
		jQuery("select.bill-plan").show();
        jQuery("select.bill-plan").removeClass("is-invalid");
        jQuery("select.bill-plan").addClass("is-valid");
    }
    else{
        jQuery("select.bill-plan").removeClass("is-valid");
        jQuery("select.bill-plan").addClass("is-invalid");
        //jQuery(".number-error-message").text("Please Choose A Network");
    }

    if(bill_iuc_length == "0" ){
    //alert(bill_phone_length);
    jQuery("input.bill-meter").removeClass("is-valid");
     jQuery("input.bill-meter").addClass("is-invalid");
     jQuery(".number-error-message").text("IUC can't be empty");
    
    }
    else{
      jQuery("input.bill-meter").removeClass("is-invalid");
     jQuery("input.bill-meter").addClass("is-valid");
    }
    
    if(bill_amount == "" || bill_amount > <?php echo $bal;?> || bill_amount < 0){
        jQuery(".amttopay").addClass("is-invalid");
        jQuery(".bill-amount-error-message").text("Insufficient Balance");
    }
    else{
        jQuery(".amttopay").removeClass("is-invalid");
        jQuery(".amttopay").addClass("is-valid");
    }

    var bill_meter_class = jQuery("input.bill-meter").hasClass("is-invalid");
    var bill_type_class = jQuery("select.bill-type").hasClass("is-invalid");
    var bill_amount_class = jQuery("input.bill-amount").hasClass("is-invalid");
    var bill_amount_class2 = jQuery("input#amttopay").hasClass("is-invalid");

   
    if(bill_meter_class || bill_type_class || bill_amount_class || bill_amount_class2){
    
        jQuery(".bill-type-confirm").text( jQuery("select.bill-type option:selected").text());
        jQuery(".bill-meter-confirm").text( jQuery("input.bill-meter").val());
        jQuery(".bill-amount-confirm").text( jQuery("input.bill-amount").val());
        jQuery(".bill-status-confirm").text("There's An Error You Need To Fix");
        
        jQuery(".bill-proceed").hide();
        
    }
    else{
        jQuery(".bill-type-confirm").text( jQuery("select.bill-type option:selected").text());
        jQuery(".bill-meter-confirm").text( jQuery("input.bill-meter").val());
        jQuery(".bill-amount-confirm").text( jQuery("input.bill-amount").val());
        jQuery(".bill-status-confirm").text("Correct");
    
        jQuery(".bill-proceed").show(); 
    }
	
	
	
		
	var request_id =  jQuery("#uniqidvalue").val();
    var bmeter = jQuery(".bill-meter").val();
    var btype = jQuery(".bill-type").val();
    var bamount = jQuery(".bill-amount").val();
    var bplan = jQuery(".bill-plan").val();
    
	<?php
	echo'
    var lin = "billbase'.vp_option_array($option_array,"billendpoint")."billpostdata1".'='."billpostvalue1".'&'.vp_option_array($option_array,"brequest_id").'="+request_id+"&'."billpostdata2".'='."billpostvalue2".'&'.vp_option_array($option_array,"billpostdata3").'='.vp_option_array($option_array,"billpostvalue3").'&'.vp_option_array($option_array,"billpostdata4").'='.vp_option_array($option_array,"billpostvalue4").'&'.vp_option_array($option_array,"billpostdata5").'='.vp_option_array($option_array,"billpostvalue5").'&'.vp_option_array($option_array,"btypeattr").'="+btype+"&'.vp_option_array($option_array,"billamountattribute").'="+bamount+"&'.vp_option_array($option_array,"cbvariationattr").'="+bplan+"&'.vp_option_array($option_array,"cmeterattr").'="+bmeter;
';
?>
jQuery("#url").val(lin);
    
    
    });


	jQuery(".bill-type").on("change", function($){
		var bill_type_text = jQuery("select.bill-type option:selected").text();
		if(bill_type_text != "---Select---" ){
			jQuery(".d-bill").show();
				jQuery(".the-meter").show();
		}
		else{
			jQuery(".d-bill").hide();
			jQuery(".the-meter").hide();
		}

	});
    
//ACTION FIELD
jQuery(".view-url").on("click",function($){
	
    var request_id =  jQuery("#uniqidvalue").val();
    var bmeter = jQuery(".bill-meter").val();
    var btype = jQuery(".bill-type").val();
    var bamount = jQuery(".bill-amount").val();
    var bplan = jQuery(".bill-plan").val();
    
	<?php
	echo'
    var lin = "billbase'.vp_option_array($option_array,"billendpoint")."billpostdata1".'='."billpostvalue1".'&'.vp_option_array($option_array,"brequest_id").'="+request_id+"&'."billpostdata2".'='."billpostvalue2".'&'.vp_option_array($option_array,"billpostdata3").'='.vp_option_array($option_array,"billpostvalue3").'&'.vp_option_array($option_array,"billpostdata4").'='.vp_option_array($option_array,"billpostvalue4").'&'.vp_option_array($option_array,"billpostdata5").'='.vp_option_array($option_array,"billpostvalue5").'&'.vp_option_array($option_array,"btypeattr").'="+btype+"&'.vp_option_array($option_array,"billamountattribute").'="+bamount+"&'.vp_option_array($option_array,"cbvariationattr").'="+bplan+"&'.vp_option_array($option_array,"cmeterattr").'="+bmeter;
';
?>
    var url = jQuery("#url").val(lin);
    
    var durl = jQuery("#url").val();
    
    alert(durl);
    
    });


    
    jQuery(".verify-bill").click(function(){

jQuery(".verify-bill").addClass("visually-hidden");
jQuery(".spinner-grow").removeClass("visually-hidden");
var iucg = jQuery(".meter-number").val();

var cableg = jQuery(".bill-plan option:selected").text();

if(cableg == "ABUJA"){
var cableh = "abuja-electric";
}
if(cableg == "EKO"){
var cableh = "eko-electric";
}

if(cableg == "IKEJA"){
var cableh = "ikeja-electric";
}

if(cableg == "KANO"){
var cableh = "kano-electric";
}

if(cableg == "PORTHARCOURT"){
var cableh = "portharcourt-electric";
}


if(cableg == "JOS"){
var cableh = "jos-electric";
}


if(cableg == "IBADAN"){
var cableh = "ibadan-electric";
}


if(cableg == "KADUNA"){
var cableh = "kaduna-electric";
}

if(cableg == "ENUGU"){
var cableh = "enugu-electric";
}


if(cableg == "BENIN"){
var cableh = "benin-electric";
}



if(cableg == "YOLA"){
var cableh = "yola-electric";
}




var obj = {};
obj["meterno"] = iucg;
obj["bills"] = cableh;

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/billget.php'));?>',
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
jQuery(".verify-bill").removeClass("visually-hidden");
jQuery(".spinner-grow").addClass("visually-hidden");
if(data.length === 0){
	 alert("No Name Found");
}
else{
     alert(data);
}
     
     jQuery(".verify-bill").removeClass("visually-hidden");
jQuery(".spinner-grow").addClass("visually-hidden");
      
  },
  type: 'GET'
});


});


		
jQuery(".bill-proceed").click(function(){
	
	
	var request_id =  jQuery("#uniqidvalue").val();
    var bmeter = jQuery(".bill-meter").val();
    var btype = jQuery(".bill-type").val();
    var bamount = jQuery(".bill-amount").val();
    var bplan = jQuery(".bill-plan").val();
    
	<?php
	echo'
    var lin = "billbase'.vp_option_array($option_array,"billendpoint")."billpostdata1".'='."billpostvalue1".'&'.vp_option_array($option_array,"brequest_id").'="+request_id+"&'."billpostdata2".'='."billpostvalue2".'&'.vp_option_array($option_array,"billpostdata3").'='.vp_option_array($option_array,"billpostvalue3").'&'.vp_option_array($option_array,"billpostdata4").'='.vp_option_array($option_array,"billpostvalue4").'&'.vp_option_array($option_array,"billpostdata5").'='.vp_option_array($option_array,"billpostvalue5").'&'.vp_option_array($option_array,"btypeattr").'="+btype+"&'.vp_option_array($option_array,"billamountattribute").'="+bamount+"&'.vp_option_array($option_array,"cbvariationattr").'="+bplan+"&'.vp_option_array($option_array,"cmeterattr").'="+bmeter;
';
?>
    var url = jQuery("#url").val(lin);
	
	jQuery('.btn-close').trigger('click');
	jQuery("#cover-spin").show();
	
var obj = {};
obj["vend"] = "vend";
obj["type"] = jQuery(".bill-type").val();
obj["cbill"] = jQuery(".bill-plan").val();
obj["vpname"] = jQuery(".bill-name").val();
obj["vpemail"] = jQuery(".bill-email").val();
obj["tcode"] = jQuery("#tcode").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["meterno"] = jQuery(".meter-number").val();
obj["amount"] = parseInt(jQuery("#amt").val())+  <?php echo floatval(vp_option_array($option_array,"bill_charge"));?>;
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
  title: msg,
  text: jqXHR.responseText,
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
  text: "You've Paid "+jQuery("#amt").val()+" To "+jQuery(".bill-plan option:selected").text()+" For "+jQuery(".meter-number").val(),
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