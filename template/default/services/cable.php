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
else{$network ="network_id";
}

$id = get_current_user_id();
$cable = get_userdata($id);
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
    <div class="cable-form p-3" style="border: 1px solid grey; border-radius: 5px;">
        <div class="mb-2">
		
            <form id="cfor"  class="for" id="cfor" method="post" <?php echo apply_filters('formaction','target="_self"');?>>
            <div class="mb-2">
                <label for="network" class="form-label">Cable Type</label>
                <select name="cabtype" id="cabletypesel" class="form-select form-select-sm cable-type" aria-label=".form-select-sm example">
                    <option value="none" selected>---Select---</option>
                    <?php
                    for($i=0; $i<=3; $i++){
                    $doos = vp_option_array($option_array,"cableid".$i);
                    if($doos != ""){
                     echo '<option value="'.vp_option_array($option_array,"cableid".$i).'" id="'.$i.'" >'.vp_option_array($option_array,"cablename".$i).'</option>';
                      }
                      }
                      ?>
                </select>
                <div id="validationServer04Feedback" class="invalid-feedback">
                    <span class="cable-select-network">Please Choose a Cable Tv. </span>
                  </div>
                </div>
            <div class="mb-2 d-cable">
                    <label for="cableplan" class="form-label">Cable Plan</label>
                    <select name="ccable" id="ccablesel" class="form-select form-select-sm cable-plan" aria-label=".form-select-sm example">
                    <option value="none" selected>---Select---</option>
					<?php
                     for($i=0; $i<=35; $i++){
                      $doos = vp_option_array($option_array,"ccable".$i);
                      $cname = vp_option_array($option_array,"ccablen".$i);
                    $gotv = "GOTV";
                    $dstv = "DSTV";
                    $startimes = "STARTIMES";
                    if(stripos($cname, $gotv) !== false ){
                        $cableclass = "igotv";
                    }
                    elseif(stripos($cname, $dstv) !== false ){
                      $cableclass = "idstv";
                    }
                    else{
                      $cableclass = "istartimes";
                     }
                      if($doos != ""){
                      echo '<option value="'.vp_option_array($option_array,"ccable".$i).'" id="'.$i.'" class="'.$cableclass.'">'.vp_option_array($option_array,"ccablen".$i)." $symbol".vp_option_array($option_array,"ccablep".$i).'</option>';
                      }
                      }

                     ?>

                    </select>

                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <span class="cable-select-network">Please Choose A Disco Plan. </span>
                      </div>
            </div>

			<div class="mb-2 visually-hidden">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="vpname" class="form-control cable-name" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1" value="<?php echo $cable->user_login; ?>">
            </div>
            <div class="mb-2 visually-hidden">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="vpemail" class="form-control cable-email" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" value="<?php echo $cable->user_email; ?>">
            </div>
			<div class="mb-2 visually-hidden">
				<input type="hidden" id="tcode" name="tcode" value="ccab">
				<input type="hidden" id="url" name="url">
				<input type="hidden" id="uniqidvalue" name="uniqidvalue" value="<?php echo $uniqidvalue;?>">
				<input type="hidden" id="url1" name="url1" value="<?php echo esc_url(plugins_url('vtupress/process.php'));?>">
				<input type="hidden" id="id" name="id" value="<?php echo uniqid('VTU-',false)?>">
			</div>



                <div class="mb-2 the-iuc ">
                    <label for="iuc" class="form-label">IUC</label>
                    <div class="input-group mb-2" >
                    <input name="iuc" id="ciuc" type="number" list="beneficiaries" class="form-control cable-iuc" placeholder="Iuc Number" aria-label="Iuc Number" aria-describedby="basic-addon1">
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
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <input type="button" class="btn verify-cable btn-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow " value="Verify">
                    </span>
                    </div>
                    <div id="validationServer04Feedback" class="invalid-feedback">
                       Error: <span class="number-error-message"></span>.
                      </div>
                </div>
                <div class="mb-2">
                    <label for="network" class="form-label">Amount + Charge(<?php echo floatval(vp_option_array($option_array,"cable_charge"));?>)</label>
                    <div class="input-group mb-2" >
                        <span class="input-group-text" id="basic-addon1"><?php echo $currency;?>.</span>
                        <input type="number" class="form-control cable-amount" max="<?php echo $bal;?>" placeholder="Amount" aria-label="Username" aria-describedby="basic-addon1"  id="amt" name="amount" readonly>
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
                      Error: <span class="cable-amount-error-message"></span>
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
                    <button type="button" class="w-full  bg bg-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-cable" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Purchase</button>
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
                      <h5 class="modal-title" id="exampleModalLabel">cable Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div>
                        Cable Type : <span class="cable-type-confirm"></span><br>
                        Cable Info: <span class="cable-plan-confirm"></span><br>
                        Iuc : <span class="cable-iuc-confirm"></span><br>
                        Charge : <?php echo $symbol;?><span class="cable_charge"><?php echo floatval(vp_option_array($option_array,"cable_charge"));?></span><br>
                        Total Amount(Original Amount + Charge) : <?php echo $symbol;?><span class="cable-amount-confirm"></span><br>
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
                        Status : <span class="cable-status-confirm"></span><br>
					<div class="input-group form">
					<span class="input-group-text">PIN</span>
					<input class="form-control pin" type="number" name="pin">
					</div>
                    </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="p-2  bg bg-primary text-xs font-bold text-dark uppercase bg-gray-600 rounded shadow cable-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" name="wallet" id="wallet" class=" bg bg-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow cable-proceed" form="cfor">Proceed</button>
                    </div>
                  </div>
                </div>
            </div>
    <script>
	
	
	function calcit(){
var original = jQuery("#amt").val();
var topay = jQuery("#amttopay");

var discount = "";
//DISCOUNT BGN
	<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
discount = <?php echo floatval($level[0]->cable);?>;
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



        //when purchase is clicked
		
		jQuery(".cable-type").val("none");
		jQuery(".cable-plan").val("none");
		jQuery(".the-iuc").hide();
		
		jQuery(".cable-type").on("change",function(){
		jQuery(".cable-plan").val("none");
			var type = jQuery(".cable-type").val();
			if(type != "none"){
				jQuery(".the-iuc").show();
			}
			else{
				jQuery(".the-iuc").hide();
			}
		});
		
		  jQuery(".cable-plan").on("change",function($){
var ccab = document.getElementById("ccablesel");
var amtd = document.getElementById("amt");
		 
switch(ccab.value){
	<?php
	echo'
case "'.vp_option_array($option_array,"ccable0").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep0"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable1").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep1"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable2").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep2"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable3").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep3"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable4").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep4"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable5").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep5"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable6").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep6"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable7").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep7"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable8").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep8"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable9").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep9"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable10").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep10"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable11").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep11"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable12").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep12"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable13").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep13"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable14").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep14"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable15").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep15"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable16").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep16"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable17").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep17"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable18").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep18"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable19").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccable19"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable20").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep20"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable21").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep21"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable22").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep22"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable23").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep23"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable24").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep24"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable25").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep25"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable26").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep26"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable27").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep27"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable28").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep28"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable29").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep29"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable30").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep30"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable31").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep31"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable32").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep32"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable33").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep33"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable34").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep34"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable35").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep35"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
default: alert("no price set for this");
';
?>

calcit();
}

			  
		  });
		
		
		
		
		
		
	jQuery(".d-cable").hide();
    jQuery(".purchase-cable").on("click",function($){

    var request_id =  jQuery("#uniqidvalue").val();
    var ciuc = jQuery(".cable-iuc").val();
    var ctype = jQuery(".cable-type").val();
    var camount = jQuery(".cable-amount").val();
    var cplan = jQuery(".cable-plan").val();
    
    var url = jQuery("#url").val(lin);
    
    var durl = jQuery("#url").val();
	
	<?php
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,'resell') == "yes" && isset($level) && isset($level[0]->total_level)){
	?>
	jQuery(".discount-amount-confirm").text("<?php echo floatval($level[0]->cable);?>%");
	<?php
}
?>

var cable_type = jQuery("select.cable-type option:selected").text(); //check the cable type
var cable_iuc_length = jQuery("input.cable-iuc").val().length; //check the length of the IUC field
var cable_amount = jQuery("#amttopay").val();
var cable_plan  = jQuery("select.cable-plan option:selected").text();

    if(cable_type != "---Select---"){
        jQuery("select.cable-plan").show();
        jQuery("select.cable-type").removeClass("is-invalid");
        jQuery("select.cable-type").addClass("is-valid");
    if( jQuery("input.cable-iuc").hasClass("is-invalid")){
        
        jQuery(".number-error-message").text("Please Enter An Iuc Number");
    }
    }
    else{
        jQuery("select.cable-type").removeClass("is-valid");
        jQuery("select.cable-type").addClass("is-invalid");
		jQuery("select.cable-plan").hide();
        //jQuery(".number-error-message").text("Please Choose A Network");
    }
    
    if(cable_plan != "---Select---"){
		jQuery("select.cable-plan").show();
        jQuery("select.cable-plan").removeClass("is-invalid");
        jQuery("select.cable-plan").addClass("is-valid");
    }
    else{
        jQuery("select.cable-plan").removeClass("is-valid");
        jQuery("select.cable-plan").addClass("is-invalid");
        //jQuery(".number-error-message").text("Please Choose A Network");
    }

    if(cable_iuc_length == "0" ){
    //alert(cable_phone_length);
    jQuery("input.cable-iuc").removeClass("is-valid");
     jQuery("input.cable-iuc").addClass("is-invalid");
     jQuery(".number-error-message").text("IUC can't be empty");
    
    }
    else{
      jQuery("input.cable-iuc").removeClass("is-invalid");
     jQuery("input.cable-iuc").addClass("is-valid");
    }
    
    if(cable_amount == "" || cable_amount > <?php echo $bal;?> || cable_amount < 0){
        jQuery(".amttopay").addClass("is-invalid");
        jQuery(".cable-amount-error-message").text("Insufficient Balance");
    }
    else{
        jQuery(".amttopay").removeClass("is-invalid");
        jQuery(".amttopay").addClass("is-valid");
    }

    var cable_iuc_class = jQuery("input.cable-iuc").hasClass("is-invalid");
    var cable_type_class = jQuery("select.cable-type").hasClass("is-invalid");
    var cable_amount_class = jQuery("input.cable-amount").hasClass("is-invalid");
    var cable_amount_class2 = jQuery("input#amttopay").hasClass("is-invalid");

   
    if(cable_iuc_class || cable_type_class || cable_amount_class || cable_amount_class2){
    
        jQuery(".cable-type-confirm").text( jQuery("select.cable-type option:selected").text());
        jQuery(".cable-iuc-confirm").text( jQuery("input.cable-iuc").val());
        jQuery(".cable-amount-confirm").text(jQuery("#amt").val());
        jQuery(".cable-status-confirm").text("There's An Error You Need To Fix");
        jQuery(".cable-plan-confirm").text( jQuery("select.cable-plan option:selected").text());
        
        jQuery(".cable-proceed").hide();
        
    }
    else{
        jQuery(".cable-type-confirm").text( jQuery("select.cable-type option:selected").text());
        jQuery(".cable-iuc-confirm").text( jQuery("input.cable-iuc").val());
        jQuery(".cable-amount-confirm").text( jQuery("#amt").val());
        jQuery(".cable-plan-confirm").text( jQuery("select.cable-plan option:selected").text());
        jQuery(".cable-status-confirm").text("Correct");
    
        jQuery(".cable-proceed").show(); 
    }
    
	
	
		
		
    var request_id =  jQuery("#uniqidvalue").val();
    var ciuc = jQuery(".cable-iuc").val();
    var ctype = jQuery(".cable-type").val();
    var camount = jQuery(".cable-amount").val();
    var cplan = jQuery(".cable-plan").val();
	
    <?php
	
	echo'
    var lin = "cablebase'.vp_option_array($option_array,"cableendpoint")."cablepostdata1".'='.'cablepostvalue1'.'&'.vp_option_array($option_array,"crequest_id").'="+request_id+"&'."cablepostdata2".'='."cablepostvalue2".'&'.vp_option_array($option_array,"cablepostdata3").'='.vp_option_array($option_array,"cablepostvalue3").'&'.vp_option_array($option_array,"cablepostdata4").'='.vp_option_array($option_array,"cablepostvalue4").'&'.vp_option_array($option_array,"cablepostdata5").'='.vp_option_array($option_array,"cablepostvalue5").'&'.vp_option_array($option_array,"ctypeattr").'="+ctype+"&'.vp_option_array($option_array,"cableamountattribute").'="+camount+"&'.vp_option_array($option_array,"ccvariationattr").'="+cplan+"&'.vp_option_array($option_array,"ciucattr").'="+ciuc;
';
?>
jQuery("#url").val(lin);
	
	
    
    });

	jQuery(".cable-type").on("change", function($){
		var cable_type_text = jQuery("select.cable-type option:selected").text();
		if(cable_type_text == "GOTV" ){
			jQuery(".d-cable").show();
			jQuery("option.igotv").show();
			jQuery("option.idstv").hide();
			jQuery("option.istartimes").hide();
		}
		if(cable_type_text == "DSTV" ){
			jQuery(".d-cable").show();
			jQuery("option.igotv").hide();
			jQuery("option.istartimes").hide();
			jQuery("option.idstv").show();
		}
		if(cable_type_text == "STARTIMES" ){
			jQuery(".d-cable").show();
			jQuery("option.idstv").hide();
			jQuery("option.istartimes").show();
			jQuery("option.igotv").hide();
		}

		if(cable_type_text == "---Select---" ){
			jQuery(".d-cable").hide();
			jQuery("option.igotv").hide();
			jQuery("option.idstv").hide();
			jQuery("option.istartimes").hide();
		}

	});
    
    jQuery(".cable-plan").on("change",function($){
chgcp();
    });
    
jQuery(".verify-cable").click(function(){

    jQuery(".verify-cable").addClass("visually-hidden");
    jQuery(".spinner-grow").removeClass("visually-hidden");
var iucg = jQuery(".cable-iuc").val();

var cableg = jQuery(".cable-type option:selected").text();

if(cableg == "GOTV"){
 var  cableh = "gotv";
}
if(cableg == "DSTV"){
 var  cableh = "dstv";
}

if(cableg == "STARTIMES"){
 var  cableh = "startimes";
}


var obj = {};
obj["iuc"] = iucg;
obj["cable"] = cableh;

   jQuery.ajax({
      url: '<?php echo esc_url(plugins_url('vtupress/cableget.php'));?>',
      data: obj,
	  dataType: 'text',
	  'cache': false,
	  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
	 jQuery(".verify-cable").removeClass("visually-hidden");
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
		
		if(jqXHR.responseText.length === 0){
	msg1 = "No Name Found Try Again";
}
else{
  msg1 = jqXHR.responseText;
}
			   swal({
  title: msg,
  text: msg1,
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
 
	 if(typeof data == typeof undefined){
	 alert("No Name Found [Try Again]");
}
else if(data.length === 0){
		 alert("No Name Found [Try Again]");
}
else{
      alert(data);
}
  

    jQuery(".verify-cable").removeClass("visually-hidden");
    jQuery(".spinner-grow").addClass("visually-hidden");
          
      },
      type: 'GET'
   });


});

<?php

echo'
function chgcp(){
var ccab = document.getElementById("ccablesel").value;
var amtd = document.getElementById("amt");
switch(ccab){
case "'.vp_option_array($option_array,"ccable0").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep0"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable1").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep1"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable2").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep2"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable3").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep3"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable4").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep4"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable5").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep5"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable6").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep6"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable7").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep7"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable8").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep8"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable9").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep9"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable10").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep10"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable11").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep11"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable12").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep12"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable13").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep13"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable14").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep14"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable15").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep15"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable16").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep16"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable17").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep17"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable18").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep18"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable19").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccable19"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable20").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep20"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable21").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep21"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable22").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep22"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable23").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep23"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable24").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep24"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable25").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep25"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable26").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep26"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable27").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep27"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable28").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep28"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable29").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep29"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable30").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep30"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable31").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep31"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable32").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep32"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable33").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep33"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable34").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep34"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
case "'.vp_option_array($option_array,"ccable35").'":
amtd.value = "'.(floatval(vp_option_array($option_array,"ccablep35"))+floatval(vp_option_array($option_array,"cable_charge"))).'";
break;
default: alert("no price set for this");
}


calcit();

}

';

?>


//ACTION FIELD
jQuery(".view-url").on("click",function($){
	
    var request_id =  jQuery("#uniqidvalue").val();
    var ciuc = jQuery(".cable-iuc").val();
    var ctype = jQuery(".cable-type").val();
    var camount = jQuery(".cable-amount").val();
    var cplan = jQuery(".cable-plan").val();
	
    <?php
	
	echo'
    var lin = "cablebase'.vp_option_array($option_array,"cableendpoint")."cablepostdata1".'='."cablepostvalue1".'&'.vp_option_array($option_array,"crequest_id").'="+request_id+"&'."cablepostdata2".'='."cablepostvalue2".'&'.vp_option_array($option_array,"cablepostdata3").'='.vp_option_array($option_array,"cablepostvalue3").'&'.vp_option_array($option_array,"cablepostdata4").'='.vp_option_array($option_array,"cablepostvalue4").'&'.vp_option_array($option_array,"cablepostdata5").'='.vp_option_array($option_array,"cablepostvalue5").'&'.vp_option_array($option_array,"ctypeattr").'="+ctype+"&'.vp_option_array($option_array,"cableamountattribute").'="+camount+"&'.vp_option_array($option_array,"ccvariationattr").'="+cplan+"&'.vp_option_array($option_array,"ciucattr").'="+ciuc;
';
?>
    var url = jQuery("#url").val(lin);
    
    var durl = jQuery("#url").val();
    
    alert(durl);
    
    });
	
		
jQuery(".cable-proceed").click(function(){
	
		
    var request_id =  jQuery("#uniqidvalue").val();
    var ciuc = jQuery(".cable-iuc").val();
    var ctype = jQuery(".cable-type").val();
    var camount = jQuery(".cable-amount").val();
    var cplan = jQuery(".cable-plan").val();
	
    <?php
	
	echo'
    var lin = "cablebase'.vp_option_array($option_array,"cableendpoint")."cablepostdata1".'='."cablepostvalue1".'&'.vp_option_array($option_array,"crequest_id").'="+request_id+"&'."cablepostdata2".'='."cablepostvalue2".'&'.vp_option_array($option_array,"cablepostdata3").'='.vp_option_array($option_array,"cablepostvalue3").'&'.vp_option_array($option_array,"cablepostdata4").'='.vp_option_array($option_array,"cablepostvalue4").'&'.vp_option_array($option_array,"cablepostdata5").'='.vp_option_array($option_array,"cablepostvalue5").'&'.vp_option_array($option_array,"ctypeattr").'="+ctype+"&'.vp_option_array($option_array,"cableamountattribute").'="+camount+"&'.vp_option_array($option_array,"ccvariationattr").'="+cplan+"&'.vp_option_array($option_array,"ciucattr").'="+ciuc;
';
?>
    var url = jQuery("#url").val(lin);
	
	
	jQuery('.btn-close').trigger('click');
	jQuery("#cover-spin").show();
	
var obj = {};
obj["vend"] = "vend";
obj["cabtype"] = jQuery(".cable-type").val();
obj["ccable"] = jQuery(".cable-plan").val();
obj["plan_index"] = jQuery(".cable-plan option:selected").attr("id");
obj["vpname"] = jQuery(".cable-name").val();
obj["vpemail"] = jQuery(".cable-email").val();
obj["tcode"] = jQuery("#tcode").val();
obj["url"] = jQuery("#url").val();
obj["uniqidvalue"] = jQuery("#uniqidvalue").val();
obj["url1"] = jQuery("#url1").val();
obj["id"] = jQuery("#id").val();
obj["iuc"] = jQuery("#ciuc").val();
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
  text: "You've Paid "+jQuery(".cable-plan option:selected").text()+" For "+jQuery("#ciuc").val(),
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