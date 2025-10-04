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

vp_updateuser($id,'run_code',uniqid());
$session =  vp_getuser($id,'run_code',true);

$bal = vp_getuser($id, 'vp_bal', true);




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
         <label for="network" class="form-label">Network</label>
            <select class="form-select form-select-sm airtime-network" aria-label="form-select-sm example" id="net" name="network" onchange="makeChoice()">
                <option value="none" selected>---Select---</option>
                <option value="" for="mtn" class="formtn">MTN</option>
                <option value="" for="glo" class="forglo">GLO</option>
                <option value="" for="airtel" class="forairtel">AIRTEL</option>
                <option value="" for="9mobile"  class="for9mobile">9MOBILE</option>
            </select>
            <div id="validationServer04Feedback" class="invalid-feedback">
                <span class="airtime-select-network">Please Choose a Network. </span>
              </div>
        </div>


        <div class="mb-2 ">
		<label for="network_type" class="form-label">Airtime Type</label><br>
			<select id="airtimechoice" name="airtimechoice" class="airtime-choice form-select form-select-sm" aria-label=".form-select-sm example" onchange="makeChoice()">
			<option value="none" >---Select---</option>
					<?php 
						if(!empty(vp_option_array($option_array,"airtimebaseurl")) && !empty(vp_option_array($option_array,"airtimeendpoint")) && vp_option_array($option_array,"vtucontrol") == "checked"){
							echo'
								<option value="vtu" class="airtime-choiceModal">VTU</option>
								';
							}
						if(!empty(vp_option_array($option_array,"sairtimebaseurl")) && !empty(vp_option_array($option_array,"sairtimeendpoint"))  && vp_option_array($option_array,"sharecontrol") == "checked"){
							echo'
								<option value="share" class="airtime-choiceModal">SHARE AND SELL</option>
								';
							}
						if(!empty(vp_option_array($option_array,"wairtimebaseurl")) && !empty(vp_option_array($option_array,"wairtimeendpoint"))  && vp_option_array($option_array,"awufcontrol") == "checked"){
							echo'
								<option value="awuf" class="airtime-choiceModal">AWUF</option>
								';
							}
					?>

			</select>
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
				<input type="hidden" id="tcode" name="tcode" value="cair">
				<input type="hidden" id="datatcode" name="datatcode">
				<input type="hidden" id="thisnetwork" name="thisnetwork">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo $uniqidvalue;?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false)?>">
			</div>

            <div class="mb-2">
                <label for="phone" class="form-label">Phone</label>
                <input id="phone" name="phone" list="beneficiaries" type="number" class="form-control airtime-number airtime-phone" maxlength="11" placeholder="Phone Number" aria-label="Phone Number" aria-describedby="basic-addon1">
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
                   Error: <span class="number-error-message"></span>.
                  </div>
            </div>

            <div class="mb-2">
                <label for="network" class="form-label">Original Amount</label>
                <div class="input-group mb-2">
                    <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                    <input id="amt" name="amount" type="number" class="form-control airtime-amount" onchange="calcit();" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1">
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
                <label for="network" class="form-label">Amount To Pay</label>
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
                      Error: <span class="airtime-amount-error-message"></span>
                      </div>
                </div>
            </div>
			
            <?php
				}
			?>

<div class="form-check">
  <input class="form-check-input bypass" type="checkbox" value="" id="flexCheckDefault">
  <label class="form-check-label" for="flexCheckDefault">
    Bypass Number Validator
  </label>
</div>
			

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
                <button type="button" class="w-full bg-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-airtime" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Purchase</button>
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
                  <h5 class="modal-title" id="exampleModalLabel">Airtime Purchase Confirmation</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                    Network : <span class="airtime-network-confirm"></span><br>
                    Phone : <span class="airtime-number-confirm"></span><br>
					Original Amount: <?php echo $symbol;?><span class="airtime-amount-confirm"></span><br>
					<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					
					if(vp_option_array($option_array,"discount_method") == "direct"){
					?>
					Amount To Pay : <?php echo $symbol;?><span class="amttopay2" ></span><br>
					Discount : <span class="discount-amount-confirm"></span> <br>
					<?php
					}else{
					?>
					Charge Back Bonus : <?php echo $symbol;?><span class="amttopay2" ></span><br>
					Commission : <span class="discount-amount-confirm"></span><br>	
					<?php	
					}
				};
					?>
                    Status : <span class="airtime-status-confirm"></span><br>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="number" name="pin">
					</div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="p-2 text-xs  bg-primary font-bold text-dark uppercase bg-gray-600 rounded shadow airtime-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" name="wallet" id="wallet" class=" p-2  bg-secondary  text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow airtime-proceed" form="cfor">Proceed</button>
                </div>
              </div>
            </div>
          
          
          
          
    </div>
<script>



function calcit(){
var original = jQuery("#amt").val();
var topay = jQuery("#amttopay");
//var airtime_network_for = jQuery("select.airtime-network option:selected").attr("for");

var airtimechoice = jQuery("#airtimechoice").val();


var airtime_network_value = jQuery("select.airtime-network").val();


var discount = "";
<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
if(airtimechoice == "vtu"){
	switch(airtime_network_value){
		case"<?php echo vp_option_array($option_array,'airtimemtn');?>":
		discount = <?php echo floatval($level[0]->mtn_vtu);?>;
		break;
		case"<?php echo vp_option_array($option_array,'airtimeglo');?>":
		discount = <?php echo floatval($level[0]->glo_vtu);?>;
		break;
		case"<?php echo vp_option_array($option_array,'airtimeairtel');?>":
		discount = <?php echo floatval($level[0]->airtel_vtu);?>;
		break;
		case"<?php echo vp_option_array($option_array,'airtime9mobile');?>":
		discount = <?php echo floatval($level[0]->mobile_vtu);?>;
		break;
	}
}	

if(airtimechoice == "share"){
	switch(airtime_network_value){
case"<?php echo vp_option_array($option_array,'sairtimemtn');?>":
		discount = <?php echo floatval($level[0]->mtn_share);?>;
		break;
		case"<?php echo vp_option_array($option_array,'sairtimeglo');?>":
		discount = <?php echo floatval($level[0]->glo_share);?>;
		break;
		case"<?php echo vp_option_array($option_array,'sairtimeairtel');?>":
		discount = <?php echo floatval($level[0]->airtel_share);?>;
		break;
		case"<?php echo vp_option_array($option_array,'sairtime9mobile');?>":
		discount = <?php echo floatval($level[0]->mobile_share);?>;
		break;
	}
}

if(airtimechoice == "awuf"){
	switch(airtime_network_value){
		case"<?php echo vp_option_array($option_array,'wairtimemtn');?>":
		discount = <?php echo floatval($level[0]->mtn_awuf);?>;
		break;
		case"<?php echo vp_option_array($option_array,'wairtimeglo');?>":
		discount = <?php echo floatval($level[0]->glo_awuf);?>;
		break;
		case"<?php echo vp_option_array($option_array,'wairtimeairtel');?>":
		discount = <?php echo floatval($level[0]->airtel_awuf);?>;
		break;
		case"<?php echo vp_option_array($option_array,'wairtime9mobile');?>":
		discount = <?php echo floatval($level[0]->mobile_awuf);?>;
		break;	
	}
}

<?php
}
?>

var the_amo = (original * discount)/100;
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


var airtime_network = jQuery("select.airtime-network").val();

//alert(airtimechoice);
if(airtime_network != "none"){
    //"for vtu
jQuery(".airtime-choiceModal").show();
}
else{
    jQuery(".airtime-choiceModal").hide(); 
}


function makeChoice(){

var airtime_network_for = jQuery("select.airtime-network option:selected").attr("for");

var airtimechoice = jQuery("#airtimechoice").val();

switch(airtime_network_for){
    case"mtn":
        switch(airtimechoice){
            case"vtu":
               // alert("vtu");
                jQuery(".formtn").attr("value","<?php echo vp_option_array($option_array,'airtimemtn');?>");
            break;
            case"share":
                jQuery(".formtn").attr("value","<?php echo vp_option_array($option_array,'sairtimemtn');?>");

            break;
            case"awuf":
                jQuery(".formtn").attr("value","<?php echo vp_option_array($option_array,'wairtimemtn');?>");

            break;
        }
    break;
    case"glo":
        switch(airtimechoice){
            case"vtu":
                jQuery(".forglo").attr("value","<?php echo vp_option_array($option_array,'airtimeglo');?>");
            break;
            case"share":
                jQuery(".forglo").attr("value","<?php echo vp_option_array($option_array,'sairtimeglo');?>");

            break;
            case"awuf":
                jQuery(".forglo").attr("value","<?php echo vp_option_array($option_array,'wairtimeglo');?>");

            break;
        }
    break;
    case"airtel":
        switch(airtimechoice){
            case"vtu":
                jQuery(".forairtel").attr("value","<?php echo vp_option_array($option_array,'airtimeairtel');?>");
            break;
            case"share":
                jQuery(".forairtel").attr("value","<?php echo vp_option_array($option_array,'sairtimeairtel');?>");

            break;
            case"awuf":
                jQuery(".forairtel").attr("value","<?php echo vp_option_array($option_array,'wairtimeairtel');?>");

            break;
        }
    break;
    case"9mobile":
        switch(airtimechoice){
            case"vtu":
                jQuery(".for9mobile").attr("value","<?php echo vp_option_array($option_array,'airtime9mobile');?>");
            break;
            case"share":
                jQuery(".for9mobile").attr("value","<?php echo vp_option_array($option_array,'sairtime9mobile');?>");

            break;
            case"awuf":
                jQuery(".for9mobile").attr("value","<?php echo vp_option_array($option_array,'wairtime9mobile');?>");

            break;
        }
    break;

}
}


//END OF CHECK FOR AIRTIMECHOICE VAL BY DEFAULT


//BE CHANGE DO --- CHECK FOR AIRTIMECHOICE VAL ON CHANGE
jQuery("select.airtime-network").on("change",function(){
var airtimechoice = jQuery("select.airtime-network").val();

//alert(airtimechoice);

var airtime_network = jQuery("select.airtime-network").val();

//alert(airtimechoice);
if(airtime_network != "none"){
    //"for vtu
jQuery(".airtime-choiceModal").show();
}
else{
    jQuery(".airtime-choiceModal").hide(); 
}

	
	
});
//END OF CHECK FOR AIRTIMECHOICE ON CHANGE





//ON PURCHASE CLICK
jQuery(".purchase-airtime").on("click",function($){

jQuery("#thisnetwork").val(jQuery(".airtime-network option:selected").text());
var request_id =  jQuery("#uniqidvalue").val();
var phone = jQuery(".airtime-number").val();
var network = jQuery(".airtime-network").val();
var amount = jQuery(".airtime-amount").val();
var airtimechoice = jQuery("#airtimechoice").val();
var url = jQuery("#url");
if(airtimechoice == "vtu"){
	
<?php
	echo'
url.val("vtubase'.vp_option_array($option_array,"airtimeendpoint")."vtupostdata1".'=vtupostvalue1&'.vp_option_array($option_array,"arequest_id").'="+request_id+"&'."vtupostdata2".'='."vtupostvalue2".'&'.vp_option_array($option_array,"airtimepostdata3").'='.vp_option_array($option_array,"airtimepostvalue3").'&'.vp_option_array($option_array,"airtimepostdata4").'='.vp_option_array($option_array,"airtimepostvalue4").'&'.vp_option_array($option_array,"airtimepostdata5").'='.vp_option_array($option_array,"airtimepostvalue5").'&'.vp_option_array($option_array,"airtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"airtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"airtimephoneattribute").'="+phone);

	';
	?>

}
if(airtimechoice == "share"){
	<?php
	echo'
url.val("sharebase'.vp_option_array($option_array,"sairtimeendpoint")."sharepostdata1".'='."sharepostvalue1".'&'.vp_option_array($option_array,"sarequest_id").'="+request_id+"&'."sharepostdata2".'='."sharepostvalue2".'&'.vp_option_array($option_array,"sairtimepostdata3").'='.vp_option_array($option_array,"sairtimepostvalue3").'&'.vp_option_array($option_array,"sairtimepostdata4").'='.vp_option_array($option_array,"sairtimepostvalue4").'&'.vp_option_array($option_array,"sairtimepostdata5").'='.vp_option_array($option_array,"sairtimepostvalue5").'&'.vp_option_array($option_array,"sairtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"sairtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"sairtimephoneattribute").'="+phone);

	';
	?>
}
if(airtimechoice == "awuf"){

<?php
	echo'
url.val("awufbase'.vp_option_array($option_array,"wairtimeendpoint")."awufpostdata1".'='."awufpostvalue1".'&'.vp_option_array($option_array,"warequest_id").'="+request_id+"&'."awufpostdata2".'='."awufpostvalue2".'&'.vp_option_array($option_array,"wairtimepostdata3").'='.vp_option_array($option_array,"wairtimepostvalue3").'&'.vp_option_array($option_array,"wairtimepostdata4").'='.vp_option_array($option_array,"wairtimepostvalue4").'&'.vp_option_array($option_array,"wairtimepostdata5").'='.vp_option_array($option_array,"wairtimepostvalue5").'&'.vp_option_array($option_array,"wairtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"wairtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"wairtimephoneattribute").'="+phone);

	';
	?>	
}


//VERIFICATION FIELD
var phone = jQuery("input.airtime-number").val(); //the phone number value
var phoneslice = phone.slice(0,4); //check from 0 to 4th character of phone
var airtime_network_value = jQuery("select.airtime-network").val(); //check the network value
var airtime_network_text = jQuery("select.airtime-network option:selected").text(); //check the network text
var airtime_phone_length = jQuery("input.airtime-number").val().length; //check the length of the phone number field
var airtime_amount_value = jQuery(".amttopay").val();
var discount_amount_confirm = jQuery(".discount-amount-confirm").text();

//DISCOUNT BGN
var airtimechoice = jQuery("#airtimechoice").val();
<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
if(airtimechoice == "vtu"){
	switch(airtime_network_value){
		case"<?php echo vp_option_array($option_array,'airtimemtn');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_vtu);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'airtimeglo');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_vtu);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'airtimeairtel');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_vtu);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'airtime9mobile');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_vtu);?>%");
		break;
	}
}	

if(airtimechoice == "share"){
	switch(airtime_network_value){
case"<?php echo vp_option_array($option_array,'sairtimemtn');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_share);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'sairtimeglo');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_share);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'sairtimeairtel');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_share);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'sairtime9mobile');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_share);?>%");
		break;
	}
}

if(airtimechoice == "awuf"){
	switch(airtime_network_value){
		case"<?php echo vp_option_array($option_array,'wairtimemtn');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_awuf);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'wairtimeglo');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_awuf);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'wairtimeairtel');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_awuf);?>%");
		break;
		case"<?php echo vp_option_array($option_array,'wairtime9mobile');?>":
		jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_awuf);?>%");
		break;	
	}
}

<?php
}
?>
if(airtime_phone_length == "11" ){
jQuery(".number-error-message").text("");

if(jQuery('input.bypass').is(':checked')  == true) {
		console.log("Bypass On");	
jQuery("input.airtime-phone").removeClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-valid");
 if(airtime_phone_length != "11" ){

jQuery("input.airtime-phone").removeClass("is-valid");
jQuery("input.airtime-phone").addClass("is-invalid");
 
jQuery(".number-error-message").text("The Phone Number Must Be 11 Digits");
 }

}
else{
	
		console.log("Bypass Off");
		
switch (phoneslice){
case "0703"://MTN
   if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0704":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0706":
if(airtime_network_text == "MTN" || airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0803":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0806":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0810":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0813":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0814":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0816":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0903":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0906":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0913":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0916":
if(airtime_network_text == "MTN"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0701"://END OF MTN
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0708":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0802":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0808":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0812":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0901":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0902":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0904":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0907":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0912":
if(airtime_network_text == "AIRTEL"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0705"://END OF AIRTEL
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0805":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0807":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0811":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0815":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0905":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0915":
if(airtime_network_text == "GLO"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0703"://END OF GLO
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0809":
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0817":
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0818":
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0908":
 
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
   else{
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
case "0909":
if(airtime_network_text == "9MOBILE"){

        jQuery("input.airtime-phone").removeClass("is-invalid");
        jQuery("input.airtime-phone").addClass("is-valid");

    }
    else{
        
        jQuery("input.airtime-phone").addClass("is-invalid");

    }
break;
default: jQuery("input.airtime-phone").addClass("is-invalid");
}
}

if(airtime_network_text != "---Select---"){
    jQuery("select.airtime-network").removeClass("is-invalid");
    jQuery("select.airtime-network").addClass("is-valid");
if( jQuery("input.airtime-phone").hasClass("is-invalid")){
    
    jQuery(".number-error-message").text("The Phone Number Is Not For "+airtime_network_text);
}
}
else{
    jQuery("select.airtime-network").removeClass("is-valid");
    jQuery("select.airtime-network").addClass("is-invalid");
    //jQuery(".number-error-message").text("Please Choose A Network");
}

}
if(airtime_phone_length != "11" && airtime_phone_length != "0" ){
//alert(airtime_phone_length);
jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery(".number-error-message").text("Number should be 11 in numberss");

}
if(airtime_phone_length == "0"){
    jQuery("input.airtime-phone").removeClass("is-valid");
 jQuery("input.airtime-phone").addClass("is-invalid");
 jQuery("span.number-error-message").text("This Can't Be Empty");

}
if(airtime_amount_value == "" || airtime_amount_value > <?php echo $bal;?> || airtime_amount_value < 0){
    jQuery(".amttopay").addClass("is-invalid");
    jQuery(".airtime-amount-error-message").text("Insufficient Balance");
}
else{
        jQuery(".amttopay").removeClass("is-invalid");
        jQuery(".amttopay").addClass("is-valid");
}
var airtime_phone_class = jQuery("input.airtime-number").hasClass("is-invalid");
var airtime_network_class = jQuery("select.airtime-network").hasClass("is-invalid");
var airtime_amount_class = jQuery("input.airtime-amount").hasClass("is-invalid");
var airtime_amount_class2 = jQuery("input#amttopay").hasClass("is-invalid");

/*if(!airtime_phone_class || !airtime_network_class){
    
jQuery(".purchase-airtime").attr("data-bs-toggle", "modal");
jQuery(".purchase-airtime").click();

}*/

if(airtime_phone_class || airtime_network_class || airtime_amount_class  || airtime_amount_class2){

    jQuery(".airtime-network-confirm").text( jQuery("select.airtime-network option:selected").text());
    jQuery(".airtime-number-confirm").text( jQuery("input.airtime-number").val());
    jQuery(".airtime-amount-confirm").text( jQuery("input.airtime-amount").val());
    jQuery(".airtime-status-confirm").text("There's An Error You Need To Fix");
    
    jQuery(".airtime-proceed").hide();
    
}
else{
    jQuery(".airtime-network-confirm").text( jQuery("select.airtime-network option:selected").text());
    jQuery(".airtime-number-confirm").text( jQuery("input.airtime-number").val());
    jQuery(".airtime-amount-confirm").text( jQuery("input.airtime-amount").val());
    jQuery(".airtime-status-confirm").text("Correct");

    jQuery(".airtime-proceed").show(); 
}
    

});

//END OF PURCHASE CLICK


// ON CLICK VIEW URL
jQuery(".view-url").on("click",function($){
	
var request_id =  jQuery("#uniqidvalue").val();
var phone = jQuery(".airtime-number").val();
var network = jQuery(".airtime-network").val();
var amount = jQuery(".airtime-amount").val();
var airtimechoice = jQuery("#airtimechoice").val();

if(airtimechoice == "vtu"){

var lin = "<?php echo 'vtubase'.vp_option_array($option_array,"airtimeendpoint").'vtupostdata1';?>=vtupostvalue1&<?php echo vp_option_array($option_array,"arequest_id"); ?>="+request_id+"&vtupostdata2=vtupostvalue2&<?php echo vp_option_array($option_array,"airtimepostdata3");?>=<?php echo vp_option_array($option_array,"airtimepostvalue3");?>&<?php echo vp_option_array($option_array,"airtimepostdata4");?>=<?php echo vp_option_array($option_array,"airtimepostvalue4");?>&<?php echo vp_option_array($option_array,"airtimepostdata5");?>=<?php echo vp_option_array($option_array,"airtimepostvalue5");?>&<?php echo vp_option_array($option_array,"airtimenetworkattribute");?>="+network+"&<?php echo vp_option_array($option_array,"airtimeamountattribute");?>="+amount+"&<?php echo vp_option_array($option_array,"airtimephoneattribute");?>="+phone;
}
if(airtimechoice == "share"){


var lin = "<?php echo 'sharebase'.vp_option_array($option_array,"sairtimeendpoint").'sharepostdata1';?>=sharepostvalue1&<?php echo vp_option_array($option_array,"sarequest_id"); ?>="+request_id+"&sharepostdata2=sharepostvalue2&<?php echo vp_option_array($option_array,"sairtimepostdata3");?>=<?php echo vp_option_array($option_array,"sairtimepostvalue3");?>&<?php echo vp_option_array($option_array,"sairtimepostdata4");?>=<?php echo vp_option_array($option_array,"sairtimepostvalue4");?>&<?php echo vp_option_array($option_array,"sairtimepostdata5");?>=<?php echo vp_option_array($option_array,"sairtimepostvalue5");?>&<?php echo vp_option_array($option_array,"sairtimenetworkattribute");?>="+network+"&<?php echo vp_option_array($option_array,"sairtimeamountattribute");?>="+amount+"&<?php echo vp_option_array($option_array,"sairtimephoneattribute");?>="+phone;
	
}
if(airtimechoice == "awuf"){

var lin = "<?php echo 'awufbase'.vp_option_array($option_array,"wairtimeendpoint").'awufpostdata1';?>=awufpostvalue1&<?php echo vp_option_array($option_array,"warequest_id"); ?>="+request_id+"&awufpostdata2=awufpostvalue2&<?php echo vp_option_array($option_array,"wairtimepostdata3");?>=<?php echo vp_option_array($option_array,"wairtimepostvalue3");?>&<?php echo vp_option_array($option_array,"wairtimepostdata4");?>=<?php echo vp_option_array($option_array,"wairtimepostvalue4");?>&<?php echo vp_option_array($option_array,"wairtimepostdata5");?>=<?php echo vp_option_array($option_array,"wairtimepostvalue5");?>&<?php echo vp_option_array($option_array,"wairtimenetworkattribute");?>="+network+"&<?php echo vp_option_array($option_array,"wairtimeamountattribute");?>="+amount+"&<?php echo vp_option_array($option_array,"wairtimephoneattribute");?>="+phone;
	
}
var url = jQuery("#url").val(lin);

var durl = jQuery("#url").val();

alert(durl);

});




jQuery(".airtime-proceed").click(function(){
var request_id =  jQuery("#uniqidvalue").val();
var phone = jQuery(".airtime-number").val();
var network = jQuery(".airtime-network").val();
var amount = jQuery(".airtime-amount").val();
var airtimechoice = jQuery("#airtimechoice").val();
var url = jQuery("#url");
if(airtimechoice == "vtu"){
	
<?php
	echo'
url.val("'.vp_option_array($option_array,"airtimebaseurl").vp_option_array($option_array,"airtimeendpoint").vp_option_array($option_array,"airtimepostdata1").'='.vp_option_array($option_array,"airtimepostvalue1").'&'.vp_option_array($option_array,"arequest_id").'="+request_id+"&'.vp_option_array($option_array,"airtimepostdata2").'='.vp_option_array($option_array,"airtimepostvalue2").'&'.vp_option_array($option_array,"airtimepostdata3").'='.vp_option_array($option_array,"airtimepostvalue3").'&'.vp_option_array($option_array,"airtimepostdata4").'='.vp_option_array($option_array,"airtimepostvalue4").'&'.vp_option_array($option_array,"airtimepostdata5").'='.vp_option_array($option_array,"airtimepostvalue5").'&'.vp_option_array($option_array,"airtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"airtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"airtimephoneattribute").'="+phone);

	';
	?>

}
if(airtimechoice == "share"){
	<?php
	echo'
url.val("'.vp_option_array($option_array,"sairtimebaseurl").vp_option_array($option_array,"sairtimeendpoint").vp_option_array($option_array,"sairtimepostdata1").'='.vp_option_array($option_array,"sairtimepostvalue1").'&'.vp_option_array($option_array,"sarequest_id").'="+request_id+"&'.vp_option_array($option_array,"sairtimepostdata2").'='.vp_option_array($option_array,"sairtimepostvalue2").'&'.vp_option_array($option_array,"sairtimepostdata3").'='.vp_option_array($option_array,"sairtimepostvalue3").'&'.vp_option_array($option_array,"sairtimepostdata4").'='.vp_option_array($option_array,"sairtimepostvalue4").'&'.vp_option_array($option_array,"sairtimepostdata5").'='.vp_option_array($option_array,"sairtimepostvalue5").'&'.vp_option_array($option_array,"sairtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"sairtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"sairtimephoneattribute").'="+phone);

	';
	?>
}
if(airtimechoice == "awuf"){

<?php
	echo'
url.val("'.vp_option_array($option_array,"wairtimebaseurl").vp_option_array($option_array,"wairtimeendpoint").vp_option_array($option_array,"wairtimepostdata1").'='.vp_option_array($option_array,"wairtimepostvalue1").'&'.vp_option_array($option_array,"warequest_id").'="+request_id+"&'.vp_option_array($option_array,"wairtimepostdata2").'='.vp_option_array($option_array,"wairtimepostvalue2").'&'.vp_option_array($option_array,"wairtimepostdata3").'='.vp_option_array($option_array,"wairtimepostvalue3").'&'.vp_option_array($option_array,"wairtimepostdata4").'='.vp_option_array($option_array,"wairtimepostvalue4").'&'.vp_option_array($option_array,"wairtimepostdata5").'='.vp_option_array($option_array,"wairtimepostvalue5").'&'.vp_option_array($option_array,"wairtimenetworkattribute").'="+network+"&'.vp_option_array($option_array,"wairtimeamountattribute").'="+amount+"&'.vp_option_array($option_array,"wairtimephoneattribute").'="+phone);

	';
	?>	
}

	
	
jQuery('.btn-close').trigger('click');
	jQuery("#cover-spin").show();
	
var obj = {};
obj["vend"] = "vend";
obj["airtimechoice"] = jQuery("#airtimechoice").val();
obj["network"] = jQuery("#net").val();
obj["vpname"] = jQuery(".airtime-name").val();
obj["vpemail"] = jQuery(".airtime-email").val();
obj["network_name"] = jQuery("#net option:selected").text();
obj["airtime_choice"] = jQuery("#airtimechoice option:selected").text();
obj["tcode"] = jQuery("#tcode").val();
obj["datatcode"] = jQuery("#datatcode").val();
obj["thisnetwork"] = jQuery("#thisnetwork").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["phone"] = jQuery("#phone").val();
obj["amount"] = jQuery("#amt").val();
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
  text: "You've Sent "+jQuery("#thisnetwork").val()+" Airtime Worth "+jQuery("#amt").val()+" To "+jQuery("#phone").val(),
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