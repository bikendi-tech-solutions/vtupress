<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

    if( $_GET["subpage"] == "paymentgateway"){

        echo"
        <div class=\"card col\">
              <div class=\"card-body\">
                  <h5 class=\"card-title\">Payment Gateway</h5>
                  <div class=\"table-responsive\">
    <div class='payment_gateway'>

    <form method='post' target='_SELF' class='updatefl'>

    <div class='input-group mb-3'>
    <span class='input-group-text'>Webhook</span>
    <input type='text' name='webhook' value='".vp_getoption('siteurl')."/wp-content/plugins/vtupress/index.php' readOnly class='form-control' >
    </select>
    </div>

    <div class='mb-3'>
    <label>Select Payment Gateway</label>
    <select name='paychoice' class='form-select payment-opt' >
    <option value='paystack'>PayStack</option>
    <option value='monnify'>Monnify</option>

    ";

    if(vp_getoption("vtupress_custom_gtbank") == "yes"){
      echo"
    <option value='squadco'>SquadCo</option>";
    }

    
    if(vp_getoption("vtupress_custom_vpay") == "yes"){
      echo"
      <option value='vpay'>Vpay</option>
      ";
    }

    if(vp_getoption("vtupress_custom_kuda") == "yes"){
      echo"
    <option value='kuda'>Kuda</option>";
    }

    echo"
    </select>
    </div>


    <!--PAYSTACK -->
<div class='container'>
<h4 class='paystack'>Paystack</h4>
<h4 class='monnify'>Monnify</h4>
<h4 class='vpay'>Vpay</h4>
<h4 class='squadco'>SquadCo</h4>
<h4 class='kuda'>Kuda</h4>
<div class='p-2'>

<div class='paystack'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>PayStack Public Key</label><br>
    <input type='text' class='form-control' name='ppublic'value='".vp_getoption('ppub')."'><br>
    <label for='secretkey'>PayStack SecretKey</label><br>
    <input type='text' class='form-control' name='psecret' value='".vp_getoption('psec')."'><br>


    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='paystack_charge_method' class='form-control paystack_charge_method '>
      <option value='".vp_getoption('paystack_charge_method')."'>".vp_getoption('paystack_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control paystack_charge_back ' name='paystack_charge_back' value='".floatval(vp_getoption('paystack_charge_back'))."'>
    </div>

    <br>
    <label for='enable_paystack'>Enable Paystack: </label> <br>
    <select name='enable_paystack'>
      <option value='".vp_getoption('enable_paystack')."' >".strtoupper(vp_getoption('enable_paystack'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
    </div>
</div>




<!-- VPAY -->
<div class='vpay'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>VPAY Public Key</label><br>
    <input type='text' class='form-control' name='vpay_public'value='".vp_getoption('vpay_public')."'><br>
    <label for='secretkey'>VPAY Email </label><br>
    <input type='text' class='form-control' name='vpay_email' value='".vp_getoption('vpay_email')."'><br>
    <label for='secretkey'>VPAY Password </label><br>
    <input type='text' class='form-control' name='vpay_password' value='".vp_getoption('vpay_password')."'><br>

    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='vpay_charge_method' class='form-control vpay_charge_method '>
      <option value='".vp_getoption('vpay_charge_method')."'>".vp_getoption('vpay_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control vpay_charge_back ' name='vpay_charge_back' value='".floatval(vp_getoption('vpay_charge_back'))."'>
    </div>
<br>
    <label> Enable Vpay: </label> <br>
    <select class='enablevpay' name='enablevpay' >
      <option value='".vp_getoption('enablevpay')."'>".vp_getoption('enablevpay')."</option>
      <option value='yes'>Yes</option>
      <option value='no'>No</option>
    </select>

    </div>
</div>





<!-- SQUAD CO -->
<div class='squadco'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>SquadCo Public Key</label><br>
    <input type='text' class='form-control' name='squadpublic'value='".vp_getoption('squad_public')."'><br>
    <label for='secretkey'>SquadCo SecretKey</label><br>
    <input type='text' class='form-control' name='squadsecret' value='".vp_getoption('squad_secret')."'><br>

    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='gtb_charge_method' class='form-control gtb_charge_method '>
      <option value='".vp_getoption('gtb_charge_method')."'>".vp_getoption('gtb_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control gtb_charge_back ' name='gtb_charge_back' value='".floatval(vp_getoption('gtb_charge_back'))."'>
    </div>

  <br>
  <label> Enable SqaudCo: </label><br>
    <select class='enablesquadco' name='enablesquadco' >
      <option value='".vp_getoption('enablesquadco')."'>".vp_getoption('enablesquadco')."</option>
      <option value='yes'>Yes</option>
      <option value='no'>No</option>
    </select>

    </div>
</div>


<!-- KUDA CO -->
<div class='kuda'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>Kuda Email</label><br>
    <input type='text' class='form-control' name='kuda_email'value='".vp_getoption('kuda_email')."'><br>
    <label for='secretkey'>Kuda Api Key</label><br>
    <input type='text' class='form-control' name='kuda_apikey' value='".vp_getoption('kuda_apikey')."'><br>
    <label for='secretkey'>Generated Key</label><br>
    <input type='text' class='form-control' value='".vp_getoption('kuda_generated_apikey')."' disabled><br>

    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='kuda_charge_method' class='form-control kuda_charge_method '>
      <option value='".vp_getoption('kuda_charge_method')."'>".vp_getoption('kuda_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control kuda_charge_back ' name='kuda_charge_back' value='".floatval(vp_getoption('kuda_charge_back'))."'>
    </div>

    <br>
    
    <label> Enable Kuda: </label> <br>
    <select class='enablekuda' name='enablekuda' >
      <option value='".vp_getoption('enablekuda')."'>".vp_getoption('enablekuda')."</option>
      <option value='yes'>Yes</option>
      <option value='no'>No</option>
    </select>

    </div>
</div>


    <!-- MONNIFY -->
 
  <div class='monnify'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>Monnify Api Key: </label><br>
    <input type='text' class='form-control' name='mapi'value='".vp_getoption('monnifyapikey')."'><br>
    
    <label for='psecretkey' class='form-label'>Monnify Secret Key: </label><br>
    <input type='text' class='form-control' name='msec'value='".vp_getoption('monnifysecretkey')."'><br>
    
    <label for='secretkey' class='form-label' >Monnify ContractCode: </label><br>
    <input type='text' class='form-control' name='mcontract' value='".vp_getoption('monnifycontractcode')."'><br>
      

    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='charge_method' class='form-control charge_method '>
      <option value='".vp_getoption('charge_method')."'>".vp_getoption('charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control charge_back ' name='charge_back' value='".floatval(vp_getoption('charge_back'))."'>
    </div>

  <br>

    <label for='enable_monnify'>Enable Monnify: </label><br>
    <select name='enable_monnify'>
      <option value='".vp_getoption('enable_monnify')."' >".strtoupper(vp_getoption('enable_monnify'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
";

    echo"
    </div>
    
    </div>

    </div>
<!--END OF MONNIFY -->


<div class='mb-3'>
<label>Allow Card Funding Payment Method</label>
<select name='allow_card_method' class='form-select' >
<option value='".vp_getoption('allow_card_method')."'>".vp_getoption('allow_card_method')."</option>
<option value='yes'>YES</option>
<option value='no'>No</option>
</select>
</div>

    </div>
    </div>
    
    
    ";
    
    
    echo '
    <input type="button" name="updatefl" value="Save" class="btn btn-primary updatef2">
    </form>
    
    <script>

    var popt = jQuery(".payment-opt").val();
    if(popt == "paystack"){
      jQuery(".monnify").hide();
      jQuery(".vpay").hide();
      jQuery(".paystack").show();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();

    }
    else if(popt == "monnify"){
      jQuery(".monnify").show();
      jQuery(".vpay").hide();
      jQuery(".paystack").hide();
      jQuery(".kuda").hide();

      jQuery(".squadco").hide();
    }
    else if(popt == "squadco"){
      jQuery(".monnify").hide();
      jQuery(".vpay").hide();
      jQuery(".paystack").hide();
      jQuery(".kuda").hide();
      jQuery(".squadco").show();
      
    }
    else if(popt == "kuda"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").show();
    }
    else if(popt == "vpay"){
      jQuery(".vpay").show();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
    }
  
    jQuery(".payment-opt").on("change",function(){
  
      var popt = jQuery(".payment-opt").val();
      if(popt == "paystack"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
        jQuery(".paystack").show();
        jQuery(".squadco").hide();
        jQuery(".kuda").hide();

      }
      else if(popt == "monnify"){
      jQuery(".vpay").hide();
      jQuery(".monnify").show();
        jQuery(".paystack").hide();
        jQuery(".squadco").hide();
        jQuery(".kuda").hide();

      }
      else if(popt == "squadco"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
        jQuery(".paystack").hide();
        jQuery(".kuda").hide();
        jQuery(".squadco").show();
  
      }
      else if(popt == "kuda"){
        jQuery(".vpay").hide();
        jQuery(".monnify").hide();
          jQuery(".paystack").hide();
          jQuery(".kuda").show();
          jQuery(".squadco").hide();
    
        }
      else if(popt == "vpay"){
          jQuery(".vpay").show();
          jQuery(".monnify").hide();
            jQuery(".paystack").hide();
            jQuery(".kuda").hide();
            jQuery(".squadco").hide();
    
        }
        
    });
  
  

    jQuery(".updatef2").on("click",function(){
        jQuery(".preloader").show();
   var obj = {};
   var toatl_input = jQuery(".updatefl input, .updatefl select, .updatefl textarea").length;
   var run_obj;
   
   for(run_obj = 0; run_obj <= toatl_input; run_obj++){
   var current_input = jQuery(".updatefl input, .updatefl select, .updatefl textarea").eq(run_obj);
   
   
   var obj_name = current_input.attr("name");
   var obj_value = current_input.val();
   
   if(typeof obj_name !== typeof undefined && obj_name !== false){
   obj[obj_name] = obj_value;
   }

        
   }

   obj["updatefl"] =  "j" ;
   obj["spraycode"] = "'.vp_getoption("spraycode").'";

   
   jQuery.ajax({
     url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/paychoice.php')).'",
     data: obj,
     dataType: "json",
     "cache": false,
     "async": true,
     error: function (jqXHR, exception) {
          jQuery(".preloader").hide();
           var msg = "";
           if (jqXHR.status === 0) {
               msg = "No Connection. Verify Network.";
        swal({
     title: "Error!",
     text: msg,
     icon: "error",
     button: "Okay",
   });
     
           }  else if (jqXHR.status == 403) {
            msg = "Access Forbidden [403].";
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
               msg = "Requested JSON parse failed!";
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
               msg = "Uncaught Error." + jqXHR.responseText;
                swal({
     title: "Error!",
     text: msg,
     icon: "error",
     button: "Okay",
   });
           }
       },
     success: function(data) {
        jQuery(".preloader").hide();
           if(data.status == "100" ){
       
             swal({
     title: "SAVED",
     text: "Update Completed",
     icon: "success",
     button: "Okay",
   }).then((value) => {
       location.reload();
   });
         }
         else{
             
        jQuery(".preloader").hide();
        swal({
     title: "Error",
     text: "Saving Wasn\"t Successful",
     icon: "error",
     button: "Okay",
   });
         }
     },
     type: "POST"
   });
   
   });
   
   
   </script>
   
    </div>
    </div>
    </div>
    </div>
    ';

}?>