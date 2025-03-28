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
else{$network ="network_id";
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
    <div class="data-form p-3" style="border: 1px solid grey; border-radius: 5px;">
	<div class="p-2 check-balance" style="text-align:center;">
	
    </div>
        <div class="mb-2">

            <form id="cfor"  class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>
            
			<div class="mb-2">
                <label for="network" class="form-label">Network</label>
                <select class="form-select form-select-sm data-network" aria-label="form-select-sm example" name="network">
                    <option value="none" >---Select---</option>
                    <option value="" for="mtn" id="formtn">MTN</option>
                    <option value="" for="glo"  id="forglo">GLO</option>
                    <option value=""  for="airtel"  id="forairtel">AIRTEL</option>
                    <option value=""  for="9mobile"  id="for9mobile">9MOBILE</option>
					<?php if(vp_getoption("smilecontrol") == "checked" && vp_getoption("vtupress_custom_smile") == "yes"){
						?>
                    <option value="smile"  for="smileNetwork"  id="forsmileNetwork">SMILE</option>
					<?php
					}
					?>

                </select>
                <div id="validationServer04Feedback" class="invalid-feedback">
                    <span class="data-select-network">Please Choose a Network. </span>
                  </div>
            </div>

			
			<div class="mb-2 dataTypeDiv">
			<label  for="datatype" class="form-label" >Data Type</label>
				<select id="datachoice" aria-label="form-select-sm example" class="form-select form-select-sm datachoice" name="datachoice" >
						<option value="none" selected>---Select---</option>
						<?php
						if(!empty(vp_option_array($option_array,"databaseurl")) && !empty(vp_option_array($option_array,"dataendpoint"))  && vp_option_array($option_array,"smecontrol") == "checked"){
							echo'
								<option value="sme"  class="smeOpt datasOpt">SME</option>
							';
						}
						if(!empty(vp_option_array($option_array,"r2databaseurl")) && !empty(vp_option_array($option_array,"r2dataendpoint"))  && vp_option_array($option_array,"corporatecontrol") == "checked"){
							echo'
								<option value="corporate" class="corporateOpt datasOpt" >CORPORATE</option>
							';
						}
						if(!empty(vp_option_array($option_array,"rdatabaseurl")) && !empty(vp_option_array($option_array,"rdataendpoint"))  && vp_option_array($option_array,"directcontrol") == "checked"){
							echo'
								<option value="direct"  class="giftingOpt datasOpt">GIFTING</option>
							';
						}
						
						
						?>

				</select>
			</div>


            <div class="mb-2 d-data">
                    <label for="dataplan" class="form-label">Data Plan</label>
                    <select class="form-select form-select-sm data-plan" aria-label="form-select-sm example" name="cplan" id="cdat">
						<option value="none">---Select---</option>
						<div class="sme_data_plan">
						<?php
						for($i=0; $i<=20; $i++){
						$doos = vp_option_array($option_array,"cdata".$i);
						if($doos != "" ){
						echo '<option value="'.vp_option_array($option_array,"cdata".$i).'" id="'.$i.'" class="imtn smedataplan">'.vp_option_array($option_array,"cdatan".$i).' ₦'.vp_option_array($option_array,"cdatap".$i).'</option>';
						}
						}
						?>

						<?php
						for($i=0; $i<=20; $i++){
						$doos = vp_option_array($option_array,"acdata".$i);
						if($doos != "" ){
						echo '<option value="'.vp_option_array($option_array,"acdata".$i).'" id="'.$i.'" class="iairtel smedataplan">'.vp_option_array($option_array,"acdatan".$i).' ₦'.vp_option_array($option_array,"acdatap".$i).'</option>';
						}
						}
						?>

						<?php
						for($i=0; $i<=20; $i++){
						$doos = vp_option_array($option_array,"9cdata".$i);
						if($doos != "" ){
						echo '<option value="'.vp_option_array($option_array,"9cdata".$i).'" id="'.$i.'" class="i9mobile smedataplan">'.vp_option_array($option_array,"9cdatan".$i).' ₦'.vp_option_array($option_array,"9cdatap".$i).'</option>';
						}
						}
						?>

						<?php
						for($i=0; $i<=20; $i++){
						$doos = vp_option_array($option_array,"gcdata".$i);
						if($doos != "" ){
						echo '<option value="'.vp_option_array($option_array,"gcdata".$i).'" id="'.$i.'" class="iglo smedataplan">'.vp_option_array($option_array,"gcdatan".$i).' ₦'.vp_option_array($option_array,"gcdatap".$i).'</option>';
						}
						}
						?>
						</div>
						
						<div  class="corporate_data_plan">
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"r2cdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"r2cdata".$i).'" id="'.$i.'" class="r2imtn corporatedataplan">'.vp_option_array($option_array,"r2cdatan".$i).' ₦'.vp_option_array($option_array,"r2cdatap".$i).'</option>';
							}
							}
						?>
						
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"r2acdata".$i);
							if($doos != "" ){
							echo '<option value="'.vp_option_array($option_array,"r2acdata".$i).'" id="'.$i.'" class="r2iairtel corporatedataplan">'.vp_option_array($option_array,"r2acdatan".$i).' ₦'.vp_option_array($option_array,"r2acdatap".$i).'</option>';
							}
							}
						?>
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"r29cdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"r29cdata".$i).'" id="'.$i.'" class="r2i9mobile corporatedataplan">'.vp_option_array($option_array,"r29cdatan".$i).' ₦'.vp_option_array($option_array,"r29cdatap".$i).'</option>';
							}
							}
						?>
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"r2gcdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"r2gcdata".$i).'" id="'.$i.'" class="r2iglo corporatedataplan">'.vp_option_array($option_array,"r2gcdatan".$i).' ₦'.vp_option_array($option_array,"r2gcdatap".$i).'</option>';
							}
							}
						?>
						</div>
                    
						<div  class="direct_data_plan">
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"rcdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"rcdata".$i).'" id="'.$i.'" class="rimtn directdataplan">'.vp_option_array($option_array,"rcdatan".$i).' ₦'.vp_option_array($option_array,"rcdatap".$i).'</option>';
							}
							}
						?>
						
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"racdata".$i);
							if($doos != "" ){
							echo '<option value="'.vp_option_array($option_array,"racdata".$i).'" id="'.$i.'" class="riairtel directdataplan">'.vp_option_array($option_array,"racdatan".$i).' ₦'.vp_option_array($option_array,"racdatap".$i).'</option>';
							}
							}
						?>
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"r9cdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"r9cdata".$i).'" id="'.$i.'" class="ri9mobile directdataplan">'.vp_option_array($option_array,"r9cdatan".$i).' ₦'.vp_option_array($option_array,"r9cdatap".$i).'</option>';
							}
							}
						?>
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"rgcdata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"rgcdata".$i).'" id="'.$i.'" class="riglo directdataplan">'.vp_option_array($option_array,"rgcdatan".$i).' ₦'.vp_option_array($option_array,"rgcdatap".$i).'</option>';
							}
							}
						?>
						</div>

						<div  class="smile_data_plan">
						<?php
						for($i=0; $i<=20; $i++){
							$doos = vp_option_array($option_array,"csmiledata".$i);
							if($doos != "" ){
								echo '<option value="'.vp_option_array($option_array,"csmiledata".$i).'" id="'.$i.'" class="smiledata smiledataplan">'.vp_option_array($option_array,"csmiledatan".$i).' ₦'.vp_option_array($option_array,"csmiledatap".$i).'</option>';
							}
							}
						?>
						</div>
					
					</select>

                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <span class="data-select-network">Please Choose A Plan. </span>
                      </div>
            </div>

			<div class="mb-2 smileReqs">
					<div class="smileEmailDiv">
                		<label for="smile_email" class="form-label">Smile Email</label>
						<div class="input-group" >
                		<input type="email" name="smile_email" class="form-control smile_email" placeholder="Smile Email" aria-label="smile_email" aria-describedby="basic-addon1" value="">
						<span class="input-group-text" id="basic-addon1">
                            <div class="spinner-grow text-secondary visually-hidden" role="status">
                            </div>
                            <input type="button" class="btn verify-email btn-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow " value="Verify">
                    	</span>
						</div>
					</div>
					<div class="smileIdDiv">
                		<label for="smile_account_id" class="form-label">Smile Email</label>
                		<input type="text" name="smile_account_id" class="form-control smile_account_id" placeholder="ID" aria-label="smile_account_id" aria-describedby="basic-addon1" value="" readOnly>
					</div>
			</div>

			<div class="mb-2 visually-hidden">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="vpname" class="form-control data-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $data->user_login; ?>">
            </div>
            <div class="mb-2 visually-hidden">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="vpemail" class="form-control data-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $data->user_email; ?>">
            </div>
			<div class="mb-2 visually-hidden">
				<input type="hidden" id="tcode" name="tcode" value="cdat">
				<input type="hidden" id="datatcode" class="datatcode" name="datatcode">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="thatnetwork" name="thatnetwork">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo $uniqidvalue;?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false)?>">
			</div>



                <div class="mb-2 phoneDiv">
                    <label for="phone" class="form-label">Phone</label>
                    <input id="phone" name="phone" list="beneficiaries" type="number" class="form-control data-number data-phone" maxlength="11" placeholder="Phone Number" aria-label="Phone Number" aria-describedby="basic-addon1">
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
                    <div class="input-group mb-2" >
                        <span class="input-group-text" id="basic-addon1">NGN.</span>
                        <input type="number" class="form-control data-amount" placeholder="Amount" aria-label="Amount" aria-describedby="basic-addon1"  id="amt" name="amount" readonly>
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
                    <span class="input-group-text" id="basic-addon1">NGN.</span>
                    <input id="amttopay" type="number" class="form-control amttopay" max="<?php echo $bal;?>" placeholder="Amount To Pay" aria-label="Username" aria-describedby="basic-addon1" readonly>
                    <span class="input-group-text" id="basic-addon1">.00</span>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                      Error: <span class="data-amount-error-message"></span>
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
if(vp_option_array($option_array,"vpdebug") != "yes" ||  current_user_can("administrator")){
	?>
                    <button type="button" class="w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-data" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Purchase</button>
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
                      <h5 class="modal-title" id="exampleModalLabel">Data Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div>
                    Network : <span class="data-network-confirm"></span><br>
                    Phone : <span class="data-number-confirm"></span><br>
                    Data Info: <span class="data-plan-confirm"></span><br>
                    Amount : ₦<span class="data-amount-confirm"></span><br>
					<?php
				if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes"){
					
					if(vp_option_array($option_array,"discount_method") == "direct"){
					?>
					Amount To Pay : ₦<span class="amttopay2" ></span><br>
					Discount : <span class="discount-amount-confirm"></span> <br>
					<?php
					}else{
					?>
					Charge Back Bonus : ₦<span class="amttopay2" ></span><br>
					Commission : <span class="discount-amount-confirm"></span><br>	
					<?php	
					}
				};
					?>
                    Status : <span class="data-status-confirm"></span><br>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="password" name="pin">
					</div>
                    </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="p-2 text-xs font-bold text-dark uppercase bg-gray-600 rounded shadow data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" name="wallet" id="wallet" class=" p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow data-proceed" form="cfor">Proceed</button>
                    </div>
                  </div>
                </div>
            </div>
    
	
			<script type="text/javascript">
	
	


	function calcit(){
	var original = jQuery("#amt").val();
	var topay = jQuery("#amttopay");
	var data_network_value = jQuery("select.data-network").val();
	
	var datachoice = jQuery(".datachoice").val();
	var discount = 0;
	//DISCOUNT BGN
	<?php
	if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
		?>
	if(datachoice == "sme"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'datamtn');?>":
			discount = <?php echo floatval($level[0]->mtn_sme);?>;
			break;
			case"<?php echo vp_option_array($option_array,'dataglo');?>":
			discount = <?php echo floatval($level[0]->glo_sme);?>;
			break;
			case"<?php echo vp_option_array($option_array,'dataairtel');?>":
			discount = <?php echo floatval($level[0]->airtel_sme);?>;
			break;
			case"<?php echo vp_option_array($option_array,'data9mobile');?>":
			discount = <?php echo floatval($level[0]->mobile_sme);?>;
			break;
		}
	}
	else if(datachoice == "direct"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'rdatamtn');?>":
			discount = <?php echo floatval($level[0]->mtn_gifting);?>;
			break;
			case"<?php echo vp_option_array($option_array,'rdataglo');?>":
			discount = <?php echo floatval($level[0]->glo_gifting);?>;
			break;
			case"<?php echo vp_option_array($option_array,'rdataairtel');?>":
			discount = <?php echo floatval($level[0]->airtel_gifting);?>;
			break;
			case"<?php echo vp_option_array($option_array,'rdata9mobile');?>":
			discount = <?php echo floatval($level[0]->mobile_gifting);?>;
			break;
		}
	}
	else if(datachoice == "corporate"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'r2datamtn');?>":
			discount = <?php echo floatval($level[0]->mtn_corporate);?>;
			break;
			case"<?php echo vp_option_array($option_array,'r2dataglo');?>":
			discount = <?php echo floatval($level[0]->glo_corporate);?>;
			break;
			case"<?php echo vp_option_array($option_array,'r2dataairtel');?>":
			discount = <?php echo floatval($level[0]->airtel_corporate);?>;
			break;
			case"<?php echo vp_option_array($option_array,'r2data9mobile');?>":
			discount = <?php echo floatval($level[0]->mobile_corporate);?>;
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
		
	
		
		jQuery(".data-network").val("none");
		jQuery(".data-plan").val("none");
		jQuery(".datachoice").val("none");
		jQuery(".datasOpt").hide();
		jQuery(".datatcode").val(jQuery(".datachoice").val());
		jQuery("#amt").val(0);
		
		
		jQuery(document).ready(function(){
	
		
		jQuery(".data-network").on("change",function(){
			jQuery("#amt").val(0);
			jQuery(".data-plan").val("none");
			jQuery(".datasOpt").hide();
			var network_value = jQuery(".data-network option:selected").text();
			switch(network_value){
				case"MTN":
					jQuery(".phoneDiv").show();
					jQuery("input.data-number").val("");
					jQuery(".purchase-data").show();
					jQuery(".dataTypeDiv").show();
					jQuery(".smileReqs").hide();
					if("<?php if(preg_match("/mtn/",vp_option_array($option_array,"sme_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".smeOpt").show();
					}
					if("<?php if(preg_match("/mtn/",vp_option_array($option_array,"corporate_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						
						jQuery(".corporateOpt").show();
					}
					if("<?php if(preg_match("/mtn/",vp_option_array($option_array,"direct_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
			
						
						jQuery(".giftingOpt").show();
					}
	
				break;
				case"GLO":
					jQuery(".phoneDiv").show();
					jQuery("input.data-number").val("");
					jQuery(".purchase-data").show();
					jQuery(".dataTypeDiv").show();
					jQuery(".smileReqs").hide();
					if("<?php if(preg_match("/glo/",vp_option_array($option_array,"sme_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".smeOpt").show();
					}
					if("<?php if(preg_match("/glo/",vp_option_array($option_array,"corporate_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".corporateOpt").show();
					}
					if("<?php if(preg_match("/glo/",vp_option_array($option_array,"direct_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".giftingOpt").show();
					}
	
				break;
				case"9MOBILE":
					jQuery(".phoneDiv").show();
					jQuery("input.data-number").val("");
					jQuery(".purchase-data").show();
					jQuery(".dataTypeDiv").show();
					jQuery(".smileReqs").hide();
					if("<?php if(preg_match("/9mobile/",vp_option_array($option_array,"sme_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".smeOpt").show();
					}
					if("<?php if(preg_match("/9mobile/",vp_option_array($option_array,"corporate_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".corporateOpt").show();
					}
					if("<?php if(preg_match("/9mobile/",vp_option_array($option_array,"direct_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".giftingOpt").show();
					}
	
				break;
				case"AIRTEL":
					jQuery(".phoneDiv").show();
					jQuery("input.data-number").val("");
					jQuery(".purchase-data").show();
					jQuery(".dataTypeDiv").show();
					jQuery(".smileReqs").hide();
					if("<?php if(preg_match("/airtel/",vp_option_array($option_array,"sme_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".smeOpt").show();
					}
					if("<?php if(preg_match("/airtel/",vp_option_array($option_array,"corporate_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".corporateOpt").show();
					}
					if("<?php if(preg_match("/airtel/",vp_option_array($option_array,"direct_visible_networks"))){echo "true";}else{echo "false";}?>" == "true"){
						
						jQuery(".giftingOpt").show();
					}
	
				break;
				case"SMILE":
						jQuery(".phoneDiv").hide();
						jQuery(".purchase-data").hide();
						jQuery(".dataTypeDiv").hide();
						jQuery(".smileReqs").show();
						
				break;
			}
	
			jQuery(".datachoice").val("none");
			networktoplan();
		});
		
		jQuery(".verify-email").on("click",function(){
			var demail = jQuery(".smile_email").val();
			jQuery(".verify-email").addClass("visually-hidden");
			jQuery(".spinner-grow").removeClass("visually-hidden");
			
			var obj = {};
			obj["demail"] = demail;
			jQuery.ajax({
	  url: '<?php echo esc_url(plugins_url('vtupress/apis/verify_smile.php'));?>',
	  data: obj,
	  dataType: 'json',
	  'cache': false,
	  "async": true,
	  error: function (jqXHR, exception) {
		jQuery(".verify-email").removeClass("visually-hidden");
	jQuery(".spinner-grow").addClass("visually-hidden");
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
		
	jQuery(".verify-email").removeClass("visually-hidden");
	jQuery(".spinner-grow").addClass("visually-hidden");
	
		  jQuery("#cover-spin").hide();
		  console.log(data);
	  
		  
		if ('status' in data) {
	 
			if(data.status == "success"){
		  var accountId = data.message;
		  jQuery("input.data-number").val(accountId);
		  jQuery(".smile_account_id").val(accountId);
		  jQuery(".purchase-data").show();
			}
			else if(data.status == "failed"){
					swal({
					title: "Error Verifying Email",
					text: data.message,
					icon: "warning",
					button: "Okay"
					});
			}
	
	
		}
		  else{
	
		  console.log(data);
			 jQuery("#cover-spin").hide();
		swal({
	  title: "Error Verifying Email",
	  text: data.message,
	  icon: "warning",
	  button: "Okay"
	});
		  }
	  },
	  type: 'POST'
	});
	
	
		});
			
		jQuery(".datachoice").on("change",function(){
			jQuery(".datatcode").val(jQuery(".datachoice").val());
	
			var the_net = jQuery(".data-network option:selected").attr("for");
		
		var ddatachoice = jQuery(".datachoice").val();
		if(ddatachoice == "sme"){
			switch(the_net){
				case"mtn":
					jQuery("#formtn").attr("value","<?php echo vp_option_array($option_array,'datamtn');?>");
				break
				case"glo":
					jQuery("#forglo").attr("value","<?php echo vp_option_array($option_array,'dataglo');?>");
	
				break
				case"airtel":
					jQuery("#forairtel").attr("value","<?php echo vp_option_array($option_array,'dataairtel');?>");
	
				break
				case"9mobile":
					jQuery("#for9mobile").attr("value","<?php echo vp_option_array($option_array,'data9mobile');?>");
	
				break;
	
			}
			jQuery("#amt").val(0);
			jQuery(".data-plan").val("none");
			jQuery(".datatcode").val(jQuery(".datachoice").val());
		}
		else if(ddatachoice == "corporate"){
			switch(the_net){
				case"mtn":
					jQuery("#formtn").attr("value","<?php echo vp_option_array($option_array,'r2datamtn');?>");
				break
				case"glo":
					jQuery("#forglo").attr("value","<?php echo vp_option_array($option_array,'r2dataglo');?>");
	
				break
				case"airtel":
					jQuery("#forairtel").attr("value","<?php echo vp_option_array($option_array,'r2dataairtel');?>");
	
				break
				case"9mobile":
					jQuery("#for9mobile").attr("value","<?php echo vp_option_array($option_array,'r2data9mobile');?>");
	
				break;
	
			}
			jQuery("#amt").val(0);
			jQuery(".data-plan").val("none");
		jQuery(".datatcode").val(jQuery(".datachoice").val());
		}
		else if(ddatachoice == "direct"){
			switch(the_net){
				case"mtn":
					jQuery("#formtn").attr("value","<?php echo vp_option_array($option_array,'rdatamtn');?>");
				break
				case"glo":
					jQuery("#forglo").attr("value","<?php echo vp_option_array($option_array,'rdataglo');?>");
	
				break
				case"airtel":
					jQuery("#forairtel").attr("value","<?php echo vp_option_array($option_array,'rdataairtel');?>");
	
				break
				case"9mobile":
					jQuery("#for9mobile").attr("value","<?php echo vp_option_array($option_array,'rdata9mobile');?>");
	
				break;
	
			}
			jQuery("#amt").val(0);
			jQuery(".data-plan").val("none");
			jQuery(".datatcode").val(jQuery(".datachoice").val());
		}
		else if(ddatachoice == "none"){
			switch(the_net){
				case"mtn":
					jQuery("#formtn").attr("value","0");
				break
				case"glo":
					jQuery("#forglo").attr("value","0>");
	
				break
				case"airtel":
					jQuery("#forairtel").attr("value","0");
	
				break
				case"9mobile":
					jQuery("#for9mobile").attr("value","0");
	
				break;
	
			}
			jQuery("#amt").val(0);
			jQuery(".datatcode").val(jQuery(".datachoice").val());
		}
		
		
			jQuery(".data-plan").val("none");
			networktoplan();
		});
	
		jQuery(".check-balance").text("Select A Data Plan");
		jQuery(".check-balance").css({"background-color":"#0dcaf0","color":"black"});
		
		function networktoplan(){
			var d2atachoice = jQuery(".datachoice").val();
			var d2atanetwork = jQuery(".data-network option:selected").text();
			var d2atanetworkval = jQuery(".data-network").val();
			
				if(d2atachoice == "sme" && d2atanetwork == "MTN"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .smedataplan.imtn").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'sme_mtn_balance');?>");
					jQuery(".check-balance").css({"background-color":"#ffc107","color":"black"});
					
				}
				else if(d2atachoice == "sme" && d2atanetwork == "GLO"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .smedataplan.iglo").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'sme_glo_balance');?>");
					jQuery(".check-balance").css({"background-color":"#20c997","color":"black"});
					
					
				}
				else if(d2atachoice == "sme" && d2atanetwork == "9MOBILE"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .smedataplan.i9mobile").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'sme_9mobile_balance');?>");
					jQuery(".check-balance").css({"background-color":"#198754","color":"white"});
					
				}
				else if(d2atachoice == "sme" && d2atanetwork == "AIRTEL"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .smedataplan.iairtel").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'sme_airtel_balance');?>");
					jQuery(".check-balance").css({"background-color":"#dc3545","color":"white"});
					
				}
				
				else if(d2atachoice == "corporate" && d2atanetwork == "MTN"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .corporatedataplan.r2imtn").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'corporate_mtn_balance');?>");
					jQuery(".check-balance").css({"background-color":"#ffc107","color":"black"});
					
				}
				else if(d2atachoice == "corporate" && d2atanetwork == "GLO"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .corporatedataplan.r2iglo").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'corporate_glo_balance');?>");
					jQuery(".check-balance").css({"background-color":"#20c997","color":"black"});
					
				}
				else if(d2atachoice == "corporate" && d2atanetwork == "9MOBILE"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .corporatedataplan.r2i9mobile").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'corporate_9mobile_balance');?>");
					jQuery(".check-balance").css({"background-color":"#198754","color":"white"});
				}
				else if(d2atachoice == "corporate" && d2atanetwork == "AIRTEL"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .corporatedataplan.r2iairtel").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'corporate_airtel_balance');?>");
					jQuery(".check-balance").css({"background-color":"#dc3545","color":"white"});
				}
				
				else if(d2atachoice == "direct" && d2atanetwork == "MTN"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .directdataplan.rimtn").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'direct_mtn_balance');?>");
					jQuery(".check-balance").css({"background-color":"#ffc107","color":"black"});
				}
				else if(d2atachoice == "direct" && d2atanetwork == "GLO"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .directdataplan.riglo").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'direct_glo_balance');?>");
					jQuery(".check-balance").css({"background-color":"#20c997","color":"black"});
					
					
				}
				else if(d2atachoice == "direct" && d2atanetwork == "9MOBILE"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .directdataplan.ri9mobile").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'direct_9mobile_balance');?>");
					jQuery(".check-balance").css({"background-color":"#198754","color":"white"});
					
				}
				else if(d2atachoice == "direct" && d2atanetwork == "AIRTEL"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata").hide();
					jQuery(".data-plan .directdataplan.riairtel").show();
					jQuery(".check-balance").text("Check Balance: <?php echo vp_option_array($option_array,'direct_airtel_balance');?>");
					jQuery(".check-balance").css({"background-color":"#dc3545","color":"white"});
					
				}
				else if( d2atanetwork == "SMILE"){
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".data-plan .smiledata.smiledataplan").show();
					jQuery(".check-balance").text("SMILE");
					jQuery(".check-balance").css({"background-color":"#dc3545","color":"white"});
					
				}
				
				else if(d2atachoice == "none" && d2atanetworkval == "none"){
					jQuery(".check-balance").text("Select A Data Plan");
					jQuery(".check-balance").css({"background-color":"#0dcaf0","color":"black"});
					
				}
				
				else if(d2atachoice == "none"){
					
					jQuery(".data-plan .smedataplan").hide();
					jQuery(".data-plan .corporatedataplan").hide();
					jQuery(".data-plan .directdataplan").hide();
					jQuery(".check-balance").text("Select A Data Plan");
					jQuery(".check-balance").css({"background-color":"#0dcaf0","color":"black"});
					
				}
			
			
			
			
			
		}
		
		
		});
		
		
		
		
		
		
		
		
		
		
		
		
			//when purchase is clicked
		jQuery(".d-data").hide();
		jQuery(".smileReqs").hide();
		jQuery(".purchase-data").on("click",function($){
	
	
	jQuery("#thatnetwork").val(jQuery(".data-network option:selected").text());
	
		var cdat = jQuery(".data-plan").val();
		var request_id =  jQuery("#uniqidvalue").val();
		var phone = jQuery(".data-number").val();
		var network = jQuery(".data-network").val();
		var amount = jQuery(".data-amount").val();
		var cvar = jQuery(".data-plan").val();
		var dur = jQuery(".datachoice").val();
		  var url = jQuery("#url");
		var datacode = jQuery("#datatcode").val();
		var accountId = jQuery(".smile_account_id").val();
		
	switch(dur){
		case"sme":
		<?php
		echo'
	url.val("smebase'.vp_option_array($option_array,"dataendpoint")."smepostdata1".'='."smepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smepostdata2".'='."smepostvalue2".'&'.vp_option_array($option_array,"datapostdata3").'='.vp_option_array($option_array,"datapostvalue3").'&'.vp_option_array($option_array,"datapostdata4").'='.vp_option_array($option_array,"datapostvalue4").'&'.vp_option_array($option_array,"datapostdata5").'='.vp_option_array($option_array,"datapostvalue5").'&'.vp_option_array($option_array,"datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"cvariationattr").'="+cdat+"&datacode="+datacode);
	';?>
		break;
		case "direct":
			<?php
		echo'
	url.val("directbase'.vp_option_array($option_array,"rdataendpoint")."directpostdata1".'='."directpostvalue1".'&'.vp_option_array($option_array,"rrequest_id").'="+request_id+"&'."directpostdata2".'='."directpostvalue2".'&'.vp_option_array($option_array,"rdatapostdata3").'='.vp_option_array($option_array,"rdatapostvalue3").'&'.vp_option_array($option_array,"rdatapostdata4").'='.vp_option_array($option_array,"rdatapostvalue4").'&'.vp_option_array($option_array,"rdatapostdata5").'='.vp_option_array($option_array,"rdatapostvalue5").'&'.vp_option_array($option_array,"rdatanetworkattribute").'="+network+"&'.vp_option_array($option_array,"rdataamountattribute").'="+amount+"&'.vp_option_array($option_array,"rdataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"rcvariationattr").'="+cdat+"&datacode="+datacode);
	';?>
		
		break;
		case "corporate":
			<?php
		echo'
	url.val("corporatebase'.vp_option_array($option_array,"r2dataendpoint")."corporatepostdata1".'='."corporatepostvalue1".'&'.vp_option_array($option_array,"r2request_id").'="+request_id+"&'."corporatepostdata2".'='."corporatepostvalue2".'&'.vp_option_array($option_array,"r2datapostdata3").'='.vp_option_array($option_array,"r2datapostvalue3").'&'.vp_option_array($option_array,"r2datapostdata4").'='.vp_option_array($option_array,"r2datapostvalue4").'&'.vp_option_array($option_array,"r2datapostdata5").'='.vp_option_array($option_array,"r2datapostvalue5").'&'.vp_option_array($option_array,"r2datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"r2dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"r2dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"r2cvariationattr").'="+cdat+"&datacode="+datacode);
	';
	?>
		
		break;
		default:		if(network == "smile"){
				<?php
		echo'
	url.val("smilebase'.vp_option_array($option_array,"smileendpoint")."smilepostdata1".'='."smilepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smilepostdata2".'='."smilepostvalue2".'&'.vp_option_array($option_array,"smilepostdata3").'='.vp_option_array($option_array,"smilepostvalue3").'&'.vp_option_array($option_array,"smilepostdata4").'='.vp_option_array($option_array,"smilepostvalue4").'&'.vp_option_array($option_array,"smilepostdata5").'='.vp_option_array($option_array,"smilepostvalue5").'&'.vp_option_array($option_array,"smilenetworkattribute").'="+accountId+"&'.vp_option_array($option_array,"smileamountattribute").'="+amount+"&'.vp_option_array($option_array,"smilephoneattribute").'="+phone+"&'.vp_option_array($option_array,"smilevariationattr").'="+cdat2+"&datacode="+datacode);
	';?>		
			}else{
				alert("Network / Plan choice error");
			};
	}
	
	 
	
		
		var phone = jQuery("input.data-number").val(); //the phone number value
		var phoneslice = phone.slice(0,4); //check from 0 to 4th character of phone
		var data_network_value = jQuery("select.data-network").val(); //check the network value
		var data_network_text = jQuery("select.data-network option:selected").text(); //check the network text
		var data_plan_text = jQuery("select.data-plan option:selected").text(); //check the plan text
		var data_phone_length = jQuery("input.data-number").val().length; //check the length of the phone number field
		var data_amount_value = jQuery("#amttopay").val();
		var discount_amount_confirm = jQuery(".discount-amount-confirm").text();
		var datachoice = jQuery(".datachoice").val();
	
	//DISCOUNT BGN
	<?php
	if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
		?>
	if(datachoice == "sme"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'datamtn');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_sme);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'dataglo');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_sme);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'dataairtel');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_sme);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'data9mobile');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_sme);?>%");
			break;
		}
	}
	else if(datachoice == "direct"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'rdatamtn');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_gifting);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'rdataglo');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_gifting);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'rdataairtel');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_gifting);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'rdata9mobile');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_gifting);?>%");
			break;
		}
	}
	else if(datachoice == "corporate"){
		switch(data_network_value){
			case"<?php echo vp_option_array($option_array,'r2datamtn');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mtn_corporate);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'r2dataglo');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->glo_corporate);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'r2dataairtel');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->airtel_corporate);?>%");
			break;
			case"<?php echo vp_option_array($option_array,'r2data9mobile');?>":
			jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->mobile_corporate);?>%");
			break;	
		}
	}
	
	<?php
	}
	?>
		
		
		
	
		
	if(data_phone_length == "11"  || datachoice.toLowerCase() == "smile"){
		jQuery(".number-error-message").text("");
	
	if(jQuery('input.bypass').is(':checked') == true) {
		console.log("Bypass On");	
	jQuery("input.data-number").removeClass("is-invalid");
	 jQuery("input.data-number").addClass("is-valid");
	 if(data_phone_length != "11"   && datachoice.toLowerCase() != "smile"){
	
	jQuery("input.data-number").removeClass("is-valid");
	jQuery("input.data-number").addClass("is-invalid");
	 
	jQuery(".number-error-message").text("The Phone Number Must Be 11 Digits");
	 }
	
	
	}
	else{
		
			console.log("Bypass Off");	
	switch (phoneslice){
	case "0703"://MTN
	   if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0704":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0706":
	if(data_network_text == "MTN" || data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").addClass("is-invalid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0803":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").addClass("is-invalid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0806":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").addClass("is-invalid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0810":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").addClass("is-invalid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0813":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").addClass("is-invalid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0814":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0816":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0903":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0906":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0913":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0916":
	if(data_network_text == "MTN" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0701"://END OF MTN
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0708":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0802":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0808":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0812":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0901":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0902":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0904":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0907":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0912":
	if(data_network_text == "AIRTEL" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0705"://END OF AIRTEL
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0805":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0807":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0811":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0815":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0905":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0915":
	if(data_network_text == "GLO" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0703"://END OF GLO
	if(data_network_text == "9MOBILE" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0809":
	if(data_network_text == "9MOBILE"  || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0817":
	if(data_network_text == "9MOBILE" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0818":
	if(data_network_text == "9MOBILE" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0908":
	 
	if(data_network_text == "9MOBILE" || data_network_text == "SMILE" ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
	   else{
	jQuery("input.data-phone").removeClass("is-valid");
	 jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	case "0909":
	if(data_network_text == "9MOBILE" || data_network_text == "SMILE"  ){
	
			jQuery("input.data-phone").removeClass("is-invalid");
			jQuery("input.data-phone").addClass("is-valid");
	
		}
		else{
			
			jQuery("input.data-phone").addClass("is-invalid");
	
		}
	break;
	
	default: jQuery("input.data-phone").addClass("is-invalid");
	}
	}
		if(data_network_text != "---Select---"){
			jQuery("select.data-network").removeClass("is-invalid");
			jQuery("select.data-network").addClass("is-valid");
		if( jQuery("input.data-phone").hasClass("is-invalid")){
			
			jQuery(".number-error-message").text("The Phone Number Is Not For "+data_network_text);
		}
		}
		else{
			jQuery("select.data-network").removeClass("is-valid");
			jQuery("select.data-network").addClass("is-invalid");
			jQuery("select.data-plan").hide();
			//jQuery(".number-error-message").text("Please Choose A Network");
		}
		
		if(data_plan_text != "---Select---"){
			jQuery("select.data-plan").show();
			jQuery("select.data-plan").removeClass("is-invalid");
			jQuery("select.data-plan").addClass("is-valid");
		}
		else{
			jQuery("select.data-plan").removeClass("is-valid");
			jQuery("select.data-plan").addClass("is-invalid");
			//jQuery(".number-error-message").text("Please Choose A Network");
		}
	
		}
		if(data_phone_length != "11" && data_phone_length != "0" && data_network_text.toUpperCase() != "SMILE"){
		//alert(data_phone_length);
		jQuery("input.data-phone").removeClass("is-valid");
		 jQuery("input.data-phone").addClass("is-invalid");
		 jQuery(".number-error-message").text("Number should be 11 in numbers");
		
		}
		if(data_phone_length == "0"){
			jQuery("input.data-phone").removeClass("is-valid");
		 jQuery("input.data-phone").addClass("is-invalid");
		 jQuery("span.number-error-message").text("This Can't Be Empty");
		
		}
	 
		if(data_amount_value == "" || data_amount_value > <?php echo $bal;?> || data_amount_value < 0){
			jQuery(".amttopay").addClass("is-invalid");
			jQuery(".data-amount-error-message").text("Insufficient Balance");
	
		}
		else{
			jQuery(".amttopay").removeClass("is-invalid");
			jQuery(".amttopay").addClass("is-valid");
		}
		var data_phone_class = jQuery("input.data-number").hasClass("is-invalid");
		var data_network_class = jQuery("select.data-network").hasClass("is-invalid");
		var data_amount_class = jQuery("input.data-amount").hasClass("is-invalid");
		var data_amount_class2 = jQuery("input#amttopay").hasClass("is-invalid");
		
		/*if(!data_phone_class || !data_network_class){
			
		jQuery(".purchase-data").attr("data-bs-toggle", "modal");
		jQuery(".purchase-data").click();
		
		}*/
		
		if(data_phone_class || data_network_class || data_amount_class || data_amount_class2){
		
			jQuery(".data-network-confirm").text( jQuery("select.data-network option:selected").text());
			jQuery(".data-number-confirm").text( jQuery("input.data-number").val());
			jQuery(".data-amount-confirm").text( jQuery("input.data-amount").val());
			jQuery(".data-status-confirm").text("There's An Error You Need To Fix");
			jQuery(".data-plan-confirm").text( jQuery("select.data-plan option:selected").text());
			
			jQuery(".data-proceed").hide();
			
		}
		else{
			jQuery(".data-network-confirm").text( jQuery("select.data-network option:selected").text());
			jQuery(".data-number-confirm").text( jQuery("input.data-number").val());
			jQuery(".data-amount-confirm").text( jQuery("input.data-amount").val());
			jQuery(".data-plan-confirm").text( jQuery("select.data-plan option:selected").text());
			jQuery(".data-status-confirm").text("Correct");
		
			jQuery(".data-proceed").show(); 
		}
			
		/*
		jQuery(".data-proceed-cancled").click(function(){
			jQuery(".purchase-data").attr("data-bs-toggle","modal"); 
		});
		*/
		
		
			
		var request_id =  jQuery("#uniqidvalue").val();
		var phone = jQuery(".data-number").val();
		var network = jQuery(".data-network").val();
		var amount = jQuery(".data-amount").val();
		var cvar = jQuery(".data-plan").val();
		var datacode = jQuery("#datatcode").val();
		var url = jQuery("#url");
		var dur = jQuery(".datachoice").val();
		var cdat2 = jQuery(".data-plan").val();
	switch(dur){
		case"sme":
		
		<?php
		echo'
	url.val("smebase'.vp_option_array($option_array,"dataendpoint")."smepostdata1".'='."smepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smepostdata2".'='."smepostvalue2".'&'.vp_option_array($option_array,"datapostdata3").'='.vp_option_array($option_array,"datapostvalue3").'&'.vp_option_array($option_array,"datapostdata4").'='.vp_option_array($option_array,"datapostvalue4").'&'.vp_option_array($option_array,"datapostdata5").'='.vp_option_array($option_array,"datapostvalue5").'&'.vp_option_array($option_array,"datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"cvariationattr").'="+cdat+"&datacode="+datacode);
	';?>
		break;
		case "direct":
		
			<?php
		echo'
	url.val("directbase'.vp_option_array($option_array,"rdataendpoint")."directpostdata1".'='."directpostvalue1".'&'.vp_option_array($option_array,"rrequest_id").'="+request_id+"&'."directpostdata2".'='."directpostvalue2".'&'.vp_option_array($option_array,"rdatapostdata3").'='.vp_option_array($option_array,"rdatapostvalue3").'&'.vp_option_array($option_array,"rdatapostdata4").'='.vp_option_array($option_array,"rdatapostvalue4").'&'.vp_option_array($option_array,"rdatapostdata5").'='.vp_option_array($option_array,"rdatapostvalue5").'&'.vp_option_array($option_array,"rdatanetworkattribute").'="+network+"&'.vp_option_array($option_array,"rdataamountattribute").'="+amount+"&'.vp_option_array($option_array,"rdataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"rcvariationattr").'="+cdat+"&datacode="+datacode);
	';?>
		
		break;
		case "corporate":
			<?php
		echo'	
	url.val("corporatebase'.vp_option_array($option_array,"r2dataendpoint")."corporatepostdata1".'='."corporatepostvalue1".'&'.vp_option_array($option_array,"r2request_id").'="+request_id+"&'."corporatepostdata2".'='."corporatepostvalue2".'&'.vp_option_array($option_array,"r2datapostdata3").'='.vp_option_array($option_array,"r2datapostvalue3").'&'.vp_option_array($option_array,"r2datapostdata4").'='.vp_option_array($option_array,"r2datapostvalue4").'&'.vp_option_array($option_array,"r2datapostdata5").'='.vp_option_array($option_array,"r2datapostvalue5").'&'.vp_option_array($option_array,"r2datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"r2dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"r2dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"r2cvariationattr").'="+cdat+"&datacode="+datacode);
	';
	?>
		
		break;
		default:		if(network == "smile"){
				<?php
		echo'
	url.val("smilebase'.vp_option_array($option_array,"smileendpoint")."smilepostdata1".'='."smilepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smilepostdata2".'='."smilepostvalue2".'&'.vp_option_array($option_array,"smilepostdata3").'='.vp_option_array($option_array,"smilepostvalue3").'&'.vp_option_array($option_array,"smilepostdata4").'='.vp_option_array($option_array,"smilepostvalue4").'&'.vp_option_array($option_array,"smilepostdata5").'='.vp_option_array($option_array,"smilepostvalue5").'&'.vp_option_array($option_array,"smilenetworkattribute").'="+network+"&'.vp_option_array($option_array,"smileamountattribute").'="+amount+"&'.vp_option_array($option_array,"smilephoneattribute").'="+phone+"&'.vp_option_array($option_array,"smilevariationattr").'="+cdat2+"&datacode="+datacode);
	';?>		
			}else{
				alert("Network / Plan choice error");
			};
	}
	
	
		
		
		
		});
	
		jQuery(".data-network").on("change", function($){
			var data_network_text = jQuery("select.data-network option:selected").text();
			if(data_network_text == "MTN" ){
				jQuery(".d-data").show();
				jQuery("option.imtn").show();
				jQuery("option.iairtel").hide();
				jQuery("option.i9mobile").hide();
				jQuery("option.iglo").hide();
			}
			if(data_network_text == "GLO" ){
				jQuery(".d-data").show();
				jQuery("option.imtn").hide();
				jQuery("option.iairtel").hide();
				jQuery("option.i9mobile").hide();
				jQuery("option.iglo").show();
			}
			if(data_network_text == "9MOBILE" ){
				jQuery(".d-data").show();
				jQuery("option.imtn").hide();
				jQuery("option.iairtel").hide();
				jQuery("option.i9mobile").show();
				jQuery("option.iglo").hide();
			}
			if(data_network_text == "AIRTEL" ){
				jQuery(".d-data").show();
				jQuery("option.imtn").hide();
				jQuery("option.iairtel").show();
				jQuery("option.i9mobile").hide();
				jQuery("option.iglo").hide();
			}
			if(data_network_text == "SMILE" ){
				jQuery(".d-data").show();
				jQuery("option.imtn").hide();
				jQuery("option.iairtel").hide();
				jQuery("option.i9mobile").hide();
				jQuery("option.iglo").hide();
				jQuery("option.smiledata").show();
			}
	
			if(data_network_text == "---Select---" ){
				jQuery(".d-data").hide();
				jQuery("option.imtn").hide();
				jQuery("option.iairtel").show();
				jQuery("option.i9mobile").hide();
				jQuery("option.iglo").hide();
			}
	
		});
	
		
	
	
	<?php
	
	echo'
	jQuery(".data-plan").on("change",function(){
	var cdat = document.getElementById("cdat").value;
	var amtd = document.getElementById("amt");
	var datachoice = jQuery(".datachoice").val();
	var datanetwork = jQuery(".data-network option:selected").text();
	
	
	var smedataprices = {};
	var corpdataprices = {};
	var giftdataprices = {};
	var smiledataprices = {};


	';
	$count = $count1 = $count2 =  $count3 = 0;
	$countc = $countc1 = $countc2 =  $countc3 = 0;
	$countg = $countg1 = $countg2 = $countg3 = 0;
	for($i=0; $i<=80; $i++){
		if($i <= 20){
			echo'
				smedataprices["'.vp_option_array($option_array,"cdata".$count).'"] = "'.vp_option_array($option_array,"cdatap".$count).'";
			';
			$count++;
		}
		elseif($i <= 40){
			

			echo'
				smedataprices["'.vp_option_array($option_array,"acdata".$count1).'"] = "'.vp_option_array($option_array,"acdatap".$count1).'";
			';
			$count1++;
		}
		elseif($i <= 60){
			$count2 = 0;

			echo'
				smedataprices["'.vp_option_array($option_array,"9cdata".$count2).'"] = "'.vp_option_array($option_array,"9cdatap".$count2).'";
			';
			$count2++;
		}else{
			echo'
				smedataprices["'.vp_option_array($option_array,"gcdata".$count3).'"] = "'.vp_option_array($option_array,"gcdatap".$count3).'";
			';
			$count3++;
		}

	}
	for($i=0; $i<=80; $i++){
		if($i <= 20){
			echo'
				corpdataprices["'.vp_option_array($option_array,"r2cdata".$countc).'"] = "'.vp_option_array($option_array,"r2cdatap".$countc).'";
			';
			$countc++;
		}
		elseif($i <= 40){
			

			echo'
				corpdataprices["'.vp_option_array($option_array,"r2acdata".$countc1).'"] = "'.vp_option_array($option_array,"r2acdatap".$countc1).'";
			';
			$countc1++;
		}
		elseif($i <= 60){
			$countc2 = 0;

			echo'
				corpdataprices["'.vp_option_array($option_array,"r29cdata".$countc2).'"] = "'.vp_option_array($option_array,"r29cdatap".$countc2).'";
			';
			$countc2++;
		}else{
			echo'
				corpdataprices["'.vp_option_array($option_array,"r2gcdata".$countc3).'"] = "'.vp_option_array($option_array,"r2gcdatap".$countc3).'";
			';
			$countc3++;
		}

	}
	for($i=0; $i<=80; $i++){
		if($i <= 20){
			echo'
				giftdataprices["'.vp_option_array($option_array,"rcdata".$countg).'"] = "'.vp_option_array($option_array,"rcdatap".$countg).'";
			';
			$countg++;
		}
		elseif($i <= 40){
			

			echo'
				giftdataprices["'.vp_option_array($option_array,"racdata".$countg1).'"] = "'.vp_option_array($option_array,"racdatap".$countg1).'";
			';
			$countg1++;
		}
		elseif($i <= 60){
			$countg2 = 0;

			echo'
				giftdataprices["'.vp_option_array($option_array,"r9cdata".$countg2).'"] = "'.vp_option_array($option_array,"r9cdatap".$countg2).'";
			';
			$countg2++;
		}else{
			echo'
				giftdataprices["'.vp_option_array($option_array,"rgcdata".$countg3).'"] = "'.vp_option_array($option_array,"rgcdatap".$countg3).'";
			';
			$countg3++;
		}

	}
	for($i=0; $i<=20; $i++){
		    echo'
                smiledataprices["'.vp_option_array($option_array,"csmiledata".$i).'"] = "'.vp_option_array($option_array,"csmiledatap".$i).'";
            ';
	}
	echo'
	if(datachoice == "sme"){
			amtd.value = smedataprices[cdat];
	}
	else if(datachoice == "direct"){
			amtd.value = giftdataprices[cdat];
	}
	else if(datachoice == "corporate"){
			amtd.value = corpdataprices[cdat];

	}
	else if(datanetwork == "SMILE"){
			amtd.value = smiledataprices[cdat];
	}
	
	
	calcit();
	
	});
	
	';
	
	?>
	
	
	//ACTION FIELD
	jQuery(".view-url").on("click",function($){
		
		var request_id =  jQuery("#uniqidvalue").val();
		var phone = jQuery(".data-number").val();
		var network = jQuery(".data-network").val();
		var amount = jQuery(".data-amount").val();
		var cvar = jQuery(".data-plan").val();
		var datacode = jQuery("#datatcode").val();
		var url = jQuery("#url");
		var dur = jQuery(".datachoice").val();
		var cdat2 = jQuery(".data-plan").val();
	switch(dur){
		case"sme":
		
		<?php
		echo'
	url.val("smebase'.vp_option_array($option_array,"dataendpoint")."smepostdata1".'='."smepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smepostdata2".'='."smepostvalue2".'&'.vp_option_array($option_array,"datapostdata3").'='.vp_option_array($option_array,"datapostvalue3").'&'.vp_option_array($option_array,"datapostdata4").'='.vp_option_array($option_array,"datapostvalue4").'&'.vp_option_array($option_array,"datapostdata5").'='.vp_option_array($option_array,"datapostvalue5").'&'.vp_option_array($option_array,"datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"cvariationattr").'="+cdat2+"&datacode="+datacode);
	';?>
		break;
		case "direct":
			<?php
		echo'
	url.val("directbase'.vp_option_array($option_array,"rdataendpoint")."directpostdata1".'='."directpostvalue1".'&'.vp_option_array($option_array,"rrequest_id").'="+request_id+"&'."directpostdata2".'='."directpostvalue2".'&'.vp_option_array($option_array,"rdatapostdata3").'='.vp_option_array($option_array,"rdatapostvalue3").'&'.vp_option_array($option_array,"rdatapostdata4").'='.vp_option_array($option_array,"rdatapostvalue4").'&'.vp_option_array($option_array,"rdatapostdata5").'='.vp_option_array($option_array,"rdatapostvalue5").'&'.vp_option_array($option_array,"rdatanetworkattribute").'="+network+"&'.vp_option_array($option_array,"rdataamountattribute").'="+amount+"&'.vp_option_array($option_array,"rdataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"rcvariationattr").'="+cdat2+"&datacode="+datacode);
	';?>
		
		break;
		case "corporate":
			<?php
		echo'
	url.val("corporatebase'.vp_option_array($option_array,"r2dataendpoint")."corporatepostdata1".'='."corporatepostvalue1".'&'.vp_option_array($option_array,"r2request_id").'="+request_id+"&'."corporatepostdata2".'='."corporatepostvalue2".'&'.vp_option_array($option_array,"r2datapostdata3").'='.vp_option_array($option_array,"r2datapostvalue3").'&'.vp_option_array($option_array,"r2datapostdata4").'='.vp_option_array($option_array,"r2datapostvalue4").'&'.vp_option_array($option_array,"r2datapostdata5").'='.vp_option_array($option_array,"r2datapostvalue5").'&'.vp_option_array($option_array,"r2datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"r2dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"r2dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"r2cvariationattr").'="+cdat2+"&datacode="+datacode);
	
		';
		?>
		break;
		default:		if(network == "smile"){
				<?php
		echo'
	url.val("smilebase'.vp_option_array($option_array,"smileendpoint")."smilepostdata1".'='."smilepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smilepostdata2".'='."smilepostvalue2".'&'.vp_option_array($option_array,"smilepostdata3").'='.vp_option_array($option_array,"smilepostvalue3").'&'.vp_option_array($option_array,"smilepostdata4").'='.vp_option_array($option_array,"smilepostvalue4").'&'.vp_option_array($option_array,"smilepostdata5").'='.vp_option_array($option_array,"smilepostvalue5").'&'.vp_option_array($option_array,"smilenetworkattribute").'="+network+"&'.vp_option_array($option_array,"smileamountattribute").'="+amount+"&'.vp_option_array($option_array,"smilephoneattribute").'="+phone+"&'.vp_option_array($option_array,"smilevariationattr").'="+cdat2+"&datacode="+datacode);
	';?>		
			}else{
				alert("Network / Plan choice error");
			};
	}
	
	 
		
		var durl = jQuery("#url").val();
		
		alert("DATA RESQUEST\n"+durl);
		
		});
		
		
		
	jQuery(".data-proceed").click(function(){
		
		var request_id =  jQuery("#uniqidvalue").val();
		var phone = jQuery(".data-number").val();
		var network = jQuery(".data-network").val();
		var amount = jQuery(".data-amount").val();
		var cvar = jQuery(".data-plan").val();
		var datacode = jQuery("#datatcode").val();
		var url = jQuery("#url");
		var dur = jQuery(".datachoice").val();
		var cdat2 = jQuery(".data-plan").val();
	switch(dur){
		case"sme":
		
		<?php
		echo'
	url.val("smebase'.vp_option_array($option_array,"dataendpoint")."smepostdata1".'='."smepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smepostdata2".'='."smepostvalue2".'&'.vp_option_array($option_array,"datapostdata3").'='.vp_option_array($option_array,"datapostvalue3").'&'.vp_option_array($option_array,"datapostdata4").'='.vp_option_array($option_array,"datapostvalue4").'&'.vp_option_array($option_array,"datapostdata5").'='.vp_option_array($option_array,"datapostvalue5").'&'.vp_option_array($option_array,"datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"cvariationattr").'="+cdat2+"&datacode="+datacode);
	';?>
		break;
		case "direct":
			<?php
		echo'
	url.val("directbase'.vp_option_array($option_array,"rdataendpoint")."directpostdata1".'='."directpostvalue1".'&'.vp_option_array($option_array,"rrequest_id").'="+request_id+"&'."directpostdata2".'='."directpostvalue2".'&'.vp_option_array($option_array,"rdatapostdata3").'='.vp_option_array($option_array,"rdatapostvalue3").'&'.vp_option_array($option_array,"rdatapostdata4").'='.vp_option_array($option_array,"rdatapostvalue4").'&'.vp_option_array($option_array,"rdatapostdata5").'='.vp_option_array($option_array,"rdatapostvalue5").'&'.vp_option_array($option_array,"rdatanetworkattribute").'="+network+"&'.vp_option_array($option_array,"rdataamountattribute").'="+amount+"&'.vp_option_array($option_array,"rdataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"rcvariationattr").'="+cdat2+"&datacode="+datacode);
	';?>
		
		break;
		case "corporate":
			<?php
		echo'
	url.val("corporatebase'.vp_option_array($option_array,"r2dataendpoint")."corporatepostdata1".'='."corporatepostvalue1".'&'.vp_option_array($option_array,"r2request_id").'="+request_id+"&'."corporatepostdata2".'='."corporatepostvalue2".'&'.vp_option_array($option_array,"r2datapostdata3").'='.vp_option_array($option_array,"r2datapostvalue3").'&'.vp_option_array($option_array,"r2datapostdata4").'='.vp_option_array($option_array,"r2datapostvalue4").'&'.vp_option_array($option_array,"r2datapostdata5").'='.vp_option_array($option_array,"r2datapostvalue5").'&'.vp_option_array($option_array,"r2datanetworkattribute").'="+network+"&'.vp_option_array($option_array,"r2dataamountattribute").'="+amount+"&'.vp_option_array($option_array,"r2dataphoneattribute").'="+phone+"&'.vp_option_array($option_array,"r2cvariationattr").'="+cdat2+"&datacode="+datacode);
	
		';
		?>
		break;
		
		default:
			if(network == "smile"){
				<?php
		echo'
	url.val("smilebase'.vp_option_array($option_array,"smileendpoint")."smilepostdata1".'='."smilepostvalue1".'&'.vp_option_array($option_array,"request_id").'="+request_id+"&'."smilepostdata2".'='."smilepostvalue2".'&'.vp_option_array($option_array,"smilepostdata3").'='.vp_option_array($option_array,"smilepostvalue3").'&'.vp_option_array($option_array,"smilepostdata4").'='.vp_option_array($option_array,"smilepostvalue4").'&'.vp_option_array($option_array,"smilepostdata5").'='.vp_option_array($option_array,"smilepostvalue5").'&'.vp_option_array($option_array,"smilenetworkattribute").'="+network+"&'.vp_option_array($option_array,"smileamountattribute").'="+amount+"&'.vp_option_array($option_array,"smilephoneattribute").'="+phone+"&'.vp_option_array($option_array,"smilevariationattr").'="+cdat2+"&datacode="+datacode);
	';?>		
			}else{
				alert("Network / Plan choice error");
			};
	}
	
	 
		
		var durl = jQuery("#url").val();
		
		
		
		jQuery('.btn-close').trigger('click');
		jQuery("#cover-spin").show();
		
	var obj = {};
	obj["vend"] = "vend";
	obj["network"] = jQuery(".data-network").val();
	obj["cplan"] = jQuery(".data-plan").val();
	obj["vpname"] = jQuery(".data-name").val();
	obj["network_name"] = jQuery(".data-network option:selected").text();
	obj["data_plan"] = jQuery(".data-plan option:selected").text();
	obj["plan_index"] = jQuery(".data-plan option:selected").attr("id");
	obj["data_choice"] = jQuery(".datachoice option:selected").text();
	obj["vpemail"] = jQuery(".data-email").val();
	obj["tcode"] = jQuery("#tcode").val();
	if(obj["network_name"] == "SMILE"){
		jQuery(".datatcode").val("smile");
		obj["datatcode"] = jQuery("#datatcode").val();
	}else{
		obj["datatcode"] = jQuery("#datatcode").val();
	}
	obj["smile_email"] = jQuery(".smile_email").val();
	obj["url"] = durl;
	obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
	obj["thatnetwork"] = jQuery("#thatnetwork").val();
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
	}).then((value) => {
		location.reload();
	}); 
	  
			} else if (jqXHR.status == 404) {
				msg = "Requested page not found. [404]";
				 swal({
	  title: "Error!",
	  text: msg,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			} else if (jqXHR.status == 500) {
				msg = "Internal Server Error [500].";
				 swal({
	  title: "Error!",
	  text: msg,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			} else if (exception === "parsererror") {
				msg = "Requested JSON parse failed.";
				   swal({
	  title: msg,
	  text: jqXHR.responseText,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			} else if (exception === "timeout") {
				msg = "Time out error.";
				 swal({
	  title: "Error!",
	  text: msg,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			} else if (exception === "abort") {
				msg = "Ajax request aborted.";
				 swal({
	  title: "Error!",
	  text: msg,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			} else {
				msg = "Uncaught Error.\n" + jqXHR.responseText;
				 swal({
	  title: "Error!",
	  text: msg,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
	}); 
			}
		},
	  
	  success: function(data) {
		  jQuery("#cover-spin").hide();
		  let result = data.includes("status");
		  
			if(data == "100" || data == 100){
			  swal({
	  title: "Transaction Successful!",
	  text: "You've Sent "+jQuery(".data-plan option:selected").text()+" Data Plan To "+jQuery("#phone").val(),
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
		location.reload();
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
		  else if(result != true){
			jQuery("#cover-spin").hide();
	swal({
	  title: "Oops!",
	  text: data,
	  icon: "error",
	  button: "Okay",
	}).then((value) => {
		location.reload();
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