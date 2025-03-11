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

    
    if(vp_getoption("vtupress_custom_paymentpoint") == "yes"){
      echo"
      <option value='paymentpoint'>Paymentpoint</option>";
    }

    if(vp_getoption("vtupress_custom_payvessel") == "yes"){
      echo"
      <option value='payvessel'>Payvessel</option>";
    }

    if(vp_getoption("vtupress_custom_billstack") == "yes"){
      echo"
      <option value='billstack'>Bill Stack</option>";
    }

    if(vp_getoption("vtupress_custom_ncwallet") == "yes"){
      echo"
      <option value='ncwallet'>Ncwallet Africa</option>";
    }


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
    if(vp_getoption("vtupress_custom_auto_manual") == "yes"){
      echo"
      <option value='auto_manual'>Auto Manual</option>";
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



<!-- NCWALLET-->
<div class='ncwallet'>
    <div class='mb-3'>
    <label for='ppublickey' class='form-label'>Ncwallet Public Key.</label><br>
    <input type='text' class='form-control' name='ncwallet_apikey' value='".vp_getoption('ncwallet_apikey')."'><br>
    <label for='secretkey'>Ncwallet Trnx Pin.</label><br>
    <input type='text' class='form-control' name='ncwallet_pin' value='".vp_getoption('ncwallet_pin')."'><br>


    <div class='my-2'>
    
    <code>Enter your bvn here to use your bvn to generate an account number. Note: unlike squadco, it doesnt use your name or details to generate account number. bvn is just mandated. Ignoring will enforce your users to do KYC before they can generate an account number</code>
    <label>Your BVN:</label>
    <input type='text' class='form-control ncwallet_admin_bvn' name='ncwallet_admin_bvn'  value='".intval(vp_getoption('ncwallet_admin_bvn'))."'/>

    </div>


    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='ncwallet_charge_method' class='form-control ncwallet_charge_method '>
      <option value='".vp_getoption('ncwallet_charge_method')."'>".vp_getoption('ncwallet_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control ncwallet_charge_back ' name='ncwallet_charge_back' value='".floatval(vp_getoption('ncwallet_charge_back'))."'>
    </div>
    


    <br>
    <label for='enable_ncwallet'>Enable Ncwallet: </label> <br>
    <select name='enable_ncwallet'>
      <option value='".vp_getoption('enable_ncwallet')."' >".strtoupper(vp_getoption('enable_ncwallet'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
    </div>
</div>



<!--PAYVESSEL-->
<div class='payvessel'>
    <div class='mb-3'>
    <label for='payvesselbiz' class='form-label'>Payvessel Business Id.</label><br>
    <input type='text' class='form-control' name='payvessel_biz' value='".vp_getoption('payvessel_biz')."'><br>

    <label for='payvesselbiz' class='form-label mt-2'>Payvessel Admin Bvn.</label><br>
    <input type='text' class='form-control' name='payvessel_admin_bvn' value='".vp_getoption('payvessel_admin_bvn')."'><br>


    <label for='payvesselapi' class='form-label mt-2'>Payvessel Api Key.</label><br>
    <input type='text' class='form-control' name='payvessel_apikey' value='".vp_getoption('payvessel_apikey')."'><br>

    <label for='payvesselsec' class='form-label mt-2'>Payvessel Secret/Api Key.</label><br>
    <input type='text' class='form-control' name='payvessel_seckey' value='".vp_getoption('payvessel_seckey')."'><br>


    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='payvessel_charge_method' class='form-control payvessel_charge_method '>
      <option value='".vp_getoption('payvessel_charge_method')."'>".vp_getoption('payvessel_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control payvessel_charge_back ' name='payvessel_charge_back' value='".floatval(vp_getoption('payvessel_charge_back'))."'>
    </div>
    


    <br>
    <label for='enable_payvessel'>Enable Payvessel: </label> <br>
    <select name='enable_payvessel'>
      <option value='".vp_getoption('enable_payvessel')."' >".strtoupper(vp_getoption('enable_payvessel'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
    </div>
</div>




<!--PAYVESSEL-->
<div class='paymentpoint'>
    <div class='mb-3'>
    <label for='paymentpointbiz' class='form-label'>Paymentpoint Business Id.</label><br>
    <input type='text' class='form-control' name='paymentpoint_biz' value='".vp_getoption('paymentpoint_businessid')."'><br>

    <label for='paymentpointbiz' class='form-label mt-2'>Paymentpoint Admin Bvn.</label><br>
    <input type='text' class='form-control' name='paymentpoint_admin_bvn' value='".vp_getoption('paymentpoint_admin_bvn')."'><br>


    <label for='paymentpointapi' class='form-label mt-2'>Paymentpoint Api Key.</label><br>
    <input type='text' class='form-control' name='paymentpoint_apikey' value='".vp_getoption('paymentpoint_apikey')."'><br>

    <label for='paymentpointsec' class='form-label mt-2'>Paymentpoint Secret Key.</label><br>
    <input type='text' class='form-control' name='paymentpoint_seckey' value='".vp_getoption('paymentpoint_secretkey')."'><br>


    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='paymentpoint_charge_method' class='form-control paymentpoint_charge_method '>
      <option value='".vp_getoption('paymentpoint_charge_method')."'>".vp_getoption('paymentpoint_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control paymentpoint_charge_back ' name='paymentpoint_charge_back' value='".floatval(vp_getoption('paymentpoint_charge_back'))."'>
    </div>
    


    <br>
    <label for='enable_paymentpoint'>Enable Paymentpoint: </label> <br>
    <select name='enable_paymentpoint'>
      <option value='".vp_getoption('enable_paymentpoint')."' >".strtoupper(vp_getoption('enable_paymentpoint'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
    </div>
</div>

<!--PAYVESSEL-->
<div class='auto_manual'>
    <div class='mb-3'>

    <label for='auto_manualapi' class='form-label mt-2'>Paymentpoint Api Key.</label><br>
    <input type='text' class='form-control' name='auto_manual_apikey' value='".vp_getoption('auto_manual_apikey')."'><br>


    <div class='row'>
    ";

    for($i = 1; $i <= 3; $i++){
      echo "
        <div class='col-12 col-md-4 p-md-2 border'>
          <label for='bank$i'> Account Number </label>
          <input type='number' class='form-control' name='auto_manual_account_number$i' value='".vp_getoption('auto_manual_account_number'.$i)."'>
          <label for='bank$i'> Bank Name </label>
          <input type='text' class='form-control' name='auto_manual_bank_name$i' value='".vp_getoption('auto_manual_bank_name'.$i)."'>
          <label for='bank$i'> Account Name </label>
          <input type='text' class='form-control' name='auto_manual_account_name$i' value='".vp_getoption('auto_manual_account_name'.$i)."'>
        </div>
      ";
    }

    echo "
      <label class='mt-2'>Enter Message:</label>
      <textarea class='form-control mb-2' name='auto_manual_info' >".vp_getoption('auto_manual_info')."</textarea>
    </div>


    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='auto_manual_charge_method' class='form-control auto_manual_charge_method '>
      <option value='".vp_getoption('auto_manual_charge_method')."'>".vp_getoption('auto_manual_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control auto_manual_charge_back ' name='auto_manual_charge_back' value='".floatval(vp_getoption('auto_manual_charge_back'))."'>
    </div>
    


    <br>
    <label for='enable_auto_manual'>Enable Auto Manual: </label> <br>
    <select name='enable_auto_manual'>
      <option value='".vp_getoption('enable_auto_manual')."' >".strtoupper(vp_getoption('enable_auto_manual'))."</option>
      <option value='yes' >YES</option>
      <option value='no' >NO</option>
    </select>
    </div>
</div>



<!--BILLSTACK-->
<div class='billstack'>
    <div class='mb-3'>
    <label for='billstacksec' class='form-label'>Billstack Secret/Api Key.</label><br>
    <input type='text' class='form-control' name='billstack_apikey' value='".vp_getoption('billstack_apikey')."'><br>

    <div class='input-group  mb-2'>
      <span class='input-group-text' id='basic-addon1'>Wallet Funding Charge</span>
      <select name='billstack_charge_method' class='form-control billstack_charge_method '>
      <option value='".vp_getoption('billstack_charge_method')."'>".vp_getoption('billstack_charge_method')."</option>
      <option value='percentage'>Percentage[%]</option>
      <option value='fixed'>Fixed[NGN]</option>
      </select>
      <input class='form-control billstack_charge_back ' name='billstack_charge_back' value='".floatval(vp_getoption('billstack_charge_back'))."'>
    </div>
    


    <br>
    <label for='enable_billstack'>Enable Billstack: </label> <br>
    <select name='enable_billstack'>
      <option value='".vp_getoption('enable_billstack')."' >".strtoupper(vp_getoption('enable_billstack'))."</option>
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

<div class='my-2 border'>
(Recommended) - <code>Filling your details here would make use of your bvn to generate account number for your users without them needing to do KYC which will also contain your name but whenever your user
makes any transfer to that account, their account will automatically be credited. Its more of less giving them your account number for manual funding
but its all automated
</code>
<code class='mt-2'>You can leave the bvn empty to disable using your details to generate an account for your users</code>
<br>
    <label>First Name:</label>
    <input type='text' class='form-control squad_admin_fn' name='squad_admin_fn' value='".vp_getoption('squad_admin_fn')."'>
    <br>
    <label>Last Name:</label>
    <input type='text' class='form-control squad_admin_ln' name='squad_admin_ln' value='".vp_getoption('squad_admin_ln')."'>
    <br>
    <label>Date Of Birth:</label>
    <input type='text' class='form-control squad_admin_dob' name='squad_admin_dob' value='".vp_getoption('squad_admin_dob')."'>
    <code>Make sure your date of birth is in this format MM/DD/YYYY</code>
    <br>
    <label>Bvn:</label>
    <input type='text' class='form-control squad_admin_bvn' name='squad_admin_bvn' value='".vp_getoption('squad_admin_bvn')."'>

</div>

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
function selectGateway(){
    var popt = jQuery(".payment-opt").val();
    if(popt == "paystack"){
      jQuery(".monnify").hide();
      jQuery(".vpay").hide();
      jQuery(".paystack").show();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();

    }
    else if(popt == "monnify"){
      jQuery(".monnify").show();
      jQuery(".vpay").hide();
      jQuery(".paystack").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".squadco").hide();
      jQuery(".auto_manual").hide();

    }
    else if(popt == "squadco"){
      jQuery(".monnify").hide();
      jQuery(".vpay").hide();
      jQuery(".paystack").hide();
      jQuery(".kuda").hide();
      jQuery(".squadco").show();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();


      
    }
    else if(popt == "kuda"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".ncwallet").hide();
      jQuery(".kuda").show();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();


    }
    else if(popt == "vpay"){
      jQuery(".vpay").show();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".ncwallet").hide();
      jQuery(".kuda").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();


    }
    else if(popt == "ncwallet"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").show();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();


    }
     else if(popt == "billstack"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").show();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();



    }
    else if(popt == "payvessel"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").show();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").hide();



    }
    else if(popt == "paymentpoint"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").show();
      jQuery(".auto_manual").hide();



    }
    else if(popt == "auto_manual"){
      jQuery(".vpay").hide();
      jQuery(".monnify").hide();
      jQuery(".paystack").hide();
      jQuery(".squadco").hide();
      jQuery(".kuda").hide();
      jQuery(".ncwallet").hide();
      jQuery(".billstack").hide();
      jQuery(".payvessel").hide();
      jQuery(".paymentpoint").hide();
      jQuery(".auto_manual").show();



    }

  }
  

  selectGateway();

    jQuery(".payment-opt").on("change",function(){

    selectGateway();
        
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