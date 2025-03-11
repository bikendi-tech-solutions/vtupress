<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if($_GET["subpage"] == "general"){

  vp_addoption("auto_transfer", "yes");

?>
<div class="row">
<div class="card col">
              <div class="card-body">
                  <h5 class="card-title">General</h5>
                  <div class="table-responsive">
<?php

if(current_user_can("vtupress_access_general")){

    vp_addoption("vp_redirect","vpaccount");
    vp_addoption("totcons","yes");
    echo '
    <form method="post" class="fset_form" target="_SELF"><br>
    <!--///////////////////////////////////////////DEBUG///////////////////-->
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Debug</span>
    <select class="form-select updateThis form-select-sm" name="vpdebug">
    <option value="'.vp_getoption("vpdebug").'">'.vp_getoption("vpdebug").'</option>
    <option value="yes">Yes</option>
    <option value="no">No</option>
    </select>
    </div>

    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Spray Code: (do not edit)</span>
    <input type="password" readonly value="'.vp_getoption("spraycode").'" name="spraycode" class="spraycode form-control updateThis " />
    </div>

    
    <div class="border border-secondary mb-3 me-3" >
    <div id="template" class="p-2 py-3">Template</div>
    <div class="p-3"  style="background-color:#f0f0f1;">
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Template</span>
    <select class="form-select updateThis form-select-sm" name="template">
    <option value="'.vp_getoption("vp_template").'">'.vp_getoption("vp_template").'</option>
    <option value="default">Default</option>
    ';
    if(vp_getoption("resell") == "yes"){
        echo'
    <option value="classic">Classic</option>
    ';
    do_action("list_vtupress_templates");
    }

    echo'
    </select>
    </div>
    
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Registrations?</span>
                       <select name="vp_enable_registration"  class="form-control updateThis ">
                       <option value="'.vp_getoption('vp_enable_registration').'">'.strtoupper(vp_getoption('vp_enable_registration')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>

    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Registration Complete Message</span>
    <input type="text" class="form-control updateThis " placeholder="Welcome Message" name="upgradeamt" value="'.vp_getoption('resc').'">
    </div>
    
    
    
    
    
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Logout Redirection [without starting /]</span>
    <input type="text" class="form-control updateThis " value="'.get_site_url().'/" readOnly>
    <input type="text" class="form-control updateThis " placeholder="Redirect to e.g vpaccount" name="vpredirect" value="'.vp_getoption("vp_redirect").'">
    </div>
    
        
    <div class="input-group mb-2 ">
    <span class="input-group-text" id="basic-addon1">Max. Idle Timeout In Minute</span>
    <input type="text" class="form-control updateThis " placeholder="2" name="vtu_timeout" value="'.vp_getoption("vtu_timeout").'">
    <input type="text" class="form-control updateThis " placeholder="2" value="Minute MAX = 60 (Enter 0 or false to disable)" readonly>
    </div>
    

    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Redirect WP default registration/login page to VpAccount Login Page </span>
                       <select name="wplogin_redirect"  class="form-control updateThis ">
                       <option value="'.vp_getoption('wplogin_redirect').'">'.vp_getoption('wplogin_redirect').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>

    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Allow Users To Enter Their Referrer ID On Registration:</span>
                       <select name="id_on_reg"  class="form-control updateThis ">
                       <option value="'.vp_getoption('id_on_reg').'">'.vp_getoption('id_on_reg').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>

    
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Beneficiaries:</span>
                       <select name="enable_beneficiaries"  class="form-control updateThis ">
                       <option value="'.vp_getoption('enable_beneficiaries').'">'.vp_getoption('enable_beneficiaries').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>

    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">HIDE the Why button from users? (Not Recommended)</span>
                       <select name="hide_why"  class="form-control updateThis ">
                       <option value="'.vp_getoption('hide_why').'">'.vp_getoption('hide_why').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>

    
    </div>
    </div>
    
    <div class="border border-secondary mb-3 me-3" >
    <div id="contact" class="p-2 py-3">Contact-Information</div>
    <div class="p-3"  style="background-color:#f0f0f1;">
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Phone +234(0)</span>
    <input type="number" class="form-control updateThis " name="vpphone" value="'.vp_getoption("vp_phone_line").'" required>
    </div>
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">WhatsApp +234(0)</span>
    <input type="number" class="form-control updateThis " name="vpwhatsapp" value="'.vp_getoption("vp_whatsapp").'" required>
    </div>
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">WhatsApp Group Link</span>
    <input type="text" class="form-control updateThis " name="vpwhatsappg" value="'.vp_getoption("vp_whatsapp_group").'" required>
    </div>
    
    </div>
    </div>
    
    <div class="border border-secondary mb-3 me-3" >
    <div id="services" class="p-2 py-3">RAPTOR: </div>
    <div class="p-3"  style="background-color:#f0f0f1;">
        <div class="input-group mb-2">
                <span class="input-group-text" id="basic-addon1">Enable Raptor:</span>
                <select class="enable_raptor" name="enable_raptor">
                  <option value="'.vp_getoption('enable_raptor').'">'.vp_getoption('enable_raptor').'</option>
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text " id="basic-addon1">BVN/NIN Verification Charge: </span>
            <input type="number" value="'.intval(vp_getoption('bvn_verification_charge')).'" class="bvn_verification_charge  updateThis " name="bvn_verification_charge">
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text" id="basic-addon1">Raptor Api Key:</span>
            <input type="text" value="'.vp_getoption('raptor_apikey').'" class="raptor_apikey  updateThis " name="raptor_apikey">
        </div>
        <div class="input-group mb-2">
          <span class="input-group-text" id="basic-addon1">Raptor - Website Connection ID:</span>
          <input type="text" value="'.vp_getoption('raptor_conid').'" class="raptor_conid  updateThis " name="raptor_conid">
        </div>
';

if(vp_getoption("vtupress_custom_bvn") == "yes"){

  echo '
  
   <span> <b>Details Verifications</b></span>
   <br>
   <div class="p-2 border border-secondary">
        <div class="input-group mb-2">
          <span class="input-group-text" id="basic-addon1">Enable Bvn/Nin Verification</span>
          <select class="setbvn" name="setbvn" name="setbvn">
            <option  value="'.vp_getoption('setbvn').'">'.vp_getoption('setbvn').'</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>

        <div class="input-group mb-2">
          <span class="input-group-text" id="basic-addon1">Bvn Verification Charge</span>
          <input type="text" value="'.vp_getoption('u_bvn_verification_charge').'" class="u_bvn_verification_charge  updateThis " name="u_bvn_verification_charge">
        </div>

        <div class="input-group mb-2">
          <span class="input-group-text" id="basic-addon1">Nin Verification Charge</span>
          <input type="text" value="'.vp_getoption('u_nin_verification_charge').'" class="u_nin_verification_charge  updateThis " name="u_nin_verification_charge">
        </div>
  </div>
        
    ';
}

echo'
        <div class="mt-2 d-flex justify-content-end">
            <button type="button" class="btn btn-primary test-connection">Test & Save Connection</button>
        </div>

        <script>
jQuery(".test-connection").on("click",function(){

  var raptorApiKey = jQuery(".raptor_apikey").val();
  var raptorConId = jQuery(".raptor_conid").val();
  var bvn = jQuery(".u_bvn_verification_charge").val();
  var nin = jQuery(".u_nin_verification_charge").val();
  var charge = jQuery(".bvn_verification_charge").val();
  var enable = jQuery(".enable_raptor").val();
  var enable_bvn = jQuery(".setbvn").val();

  if(raptorApiKey == "" || raptorConId == ""){
    alert("Raptor ApiKey and Connection Id can\'t be empty");
    return;
  }

jQuery(".preloader").show();
obj = {};
obj["Authorization"] = raptorApiKey;
obj["connectionid"] = raptorConId;
obj["u_bvn_verification_charge"] = bvn;
obj["u_nin_verification_charge"] = nin;
obj["charge"] = charge;
obj["enable_bvn"] = enable_bvn;
obj["enable"] = enable;
obj["verificationType"] = "testConnection";
obj["domain"] = "'.$_SERVER['SERVER_NAME'].'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/raptorConnection.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
      jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
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
        } else if (exception === "parsererror"){
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
    jQuery(".preloader").hide();

    if(typeof data === "object"){
        if(data.status == true ){
    
          swal({
  title: "Confirmed!",
  text: "The connection is correct and saved!",
  icon: "success",
  button: "Okay",
}).then((value) => {
    //location.reload();
});
      }
      else{
          

     swal({
  title: "Error",
  text: data.message,
  icon: "error",
  button: "Okay",
});
      }

    }
    else{
      swal({
        title: "Error With Response",
        text: data,
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
    
    
    
    
    <div class="border border-secondary mb-3 me-3" >
    <div id="services" class="p-2 py-3">Services And Funds </div>
    <div class="p-3"  style="background-color:#f0f0f1;">

    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Crypto</span>
                       <select name="allow_crypto"  class="form-control updateThis ">
                       <option value="'.vp_getoption('allow_crypto').'">'.vp_getoption('allow_crypto').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <span class="input-group-text" id="basic-addon1">Enable Gift Cards</span>
                       <select name="allow_cards"  class="form-control updateThis ">
                       <option value="'.vp_getoption('allow_cards').'">'.vp_getoption('allow_cards').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    
    </div>
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Show Total Amount Of Service Consumed</span>
                       <select name="totcons"  class="form-control updateThis ">
                       <option value="'.vp_getoption('totcons').'">'.ucfirst(vp_getoption('totcons')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>

    <div class="input-group  mb-2 visually-hidden">
    
    <span class="input-group-text" id="basic-addon1">Minimum Amount Fundable</span>
    <input value="'.vp_getoption('minimum_amount_fundable').'" class=" updateThis minimum_amount_fundable" name="minimum_amount_fundable">
    
    </div>
    
    ';
    
        
    if(is_plugin_active('vprest/vprest.php') && vp_getoption("resell") == "yes" ){
    
    echo'
    

    
    
    <div class="input-group mb-2">
    <span class="input-group-text" id="basic-addon1">Allow Withdrawal?</span>
                       <select name="allow_withdrawal"  class="form-control updateThis ">
                       <option value="'.vp_getoption('allow_withdrawal').'">'.vp_getoption('allow_withdrawal').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <span class="input-group-text" id="basic-addon1">Allow Withdrawal To Bank</span>
                       <select name="allow_to_bank"  class="form-control updateThis ">
                       <option value="'.vp_getoption('allow_to_bank').'">'.vp_getoption('allow_to_bank').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    
    </div>
    
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Allow Wallet To Wallet Transfer</span>
                       <select name="wallettowallet"  class="form-control updateThis ">
                       <option value="'.vp_getoption('wallet_to_wallet').'">'.vp_getoption('wallet_to_wallet').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    </div>
    
    <div class="input-group  mb-2">
    
    <span class="input-group-text" id="basic-addon1">Minimum Amount Transferable</span>
    <input value="'.vp_getoption('minimum_amount_transferable').'" class="minimum_amount_transferable updateThis" name="minimum_amount_transferable">
    
    </div>
    
    
    
    ';
    }
    
    
    echo'
    
    </div>
    </div>
    
    
    <div class="border border-secondary mb-3 me-3" >
    <div id="emails" class="p-2 py-3">Transactions</div>
    <div class="p-3"  style="background-color:#f0f0f1;">
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Auto-Refund Users When Webhook Gets A Non Successful Response?</span>
                       <select name="auto_refund"  class="form-control updateThis ">
                       <option value="'.vp_getoption('auto_refund').'">'.strtoupper(vp_getoption('auto_refund')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Header Check For Providers Response?</span>
                       <select name="t_header_check"  class="form-control updateThis ">
                       <option value="'.vp_getoption('t_header_check').'">'.strtoupper(vp_getoption('t_header_check')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Force Active User Balance?</span>
                       <select name="for_active_user_balance"  class="form-control updateThis ">
                       <option value="'.vp_getoption('for_active_user_balance').'">'.strtoupper(vp_getoption('for_active_user_balance')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
';
if(vp_getoption("vp_security") == "yes"){

echo'
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Automate All Wallet To Wallet Transfers?</span>
                       <select name="auto_transfer"  class="form-control updateThis ">
                       <option value="'.vp_getoption('auto_transfer').'">'.strtoupper(vp_getoption('auto_transfer')).'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
    ';
}
echo'


    </div>
    </div>
    
    
    <div class="border border-secondary mb-3 me-3" >
    <div id="emails" class="p-2 py-3">Notifications</div>
    <div class="p-3"  style="background-color:#f0f0f1;">


        <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Email Notification For Transactions </span>
                       <select name="email_transaction"  class="form-control updateThis ">
                       <option value="'.vp_getoption('email_transaction').'">'.vp_getoption('email_transaction').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>

    
    ';

    if(is_plugin_active('vprest/vprest.php') && vp_getoption("resell") == "yes" ){
      echo'

      <div class="input-group  mb-2">
      <span class="input-group-text" id="basic-addon1">Enable Email Verification </span>
                         <select name="email_verification"  class="form-control updateThis ">
                         <option value="'.vp_getoption('email_verification').'">'.vp_getoption('email_verification').'</option>
                         <option value="yes">Yes</option>
                         <option value="no">No</option>
                         </select>
      <br>
      </div>

    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Emails & Notifications For Wallet-Wallet Transfers</span>
                       <select name="email_transfer"  class="form-control updateThis ">
                       <option value="'.vp_getoption('email_transfer').'">'.vp_getoption('email_transfer').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>


    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Emails & Notifications For Withdrawals</span>
                       <select name="email_withdrawal"  class="form-control updateThis ">
                       <option value="'.vp_getoption('email_withdrawal').'">'.vp_getoption('email_withdrawal').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>

    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Emails & Notifications For KYC Uploads</span>
                       <select name="email_kyc"  class="form-control updateThis ">
                       <option value="'.vp_getoption('email_kyc').'">'.vp_getoption('email_kyc').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
    <br>
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Sms Notification For Transactions For Admin </span>
                       <select name="sms_transaction_admin"  class="form-control updateThis ">
                       <option value="'.vp_getoption('sms_transaction_admin').'">'.vp_getoption('sms_transaction_admin').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable Sms Notification For Transactions For User </span>
                       <select name="sms_transaction_user"  class="form-control updateThis ">
                       <option value="'.vp_getoption('sms_transaction_user').'">'.vp_getoption('sms_transaction_user').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    </div>

';
    }
    echo'
    
    </div>
    </div>
    
    
    <div class="border border-secondary mb-3 me-3 d-none" >
    <div id="hollatag" class="p-2 py-3">HollaTag</div>
    <div class="p-3"  style="background-color:#f0f0f1;">
    <div class="input-group  mb-2">
    <span class="input-group-text" id="basic-addon1">Enable HollaTags For Airtel Services?</span>
                       <select name="enablehollatag"  class="form-control updateThis  enablehollatag">
                       <option value="'.vp_getoption('enablehollatag').'">'.vp_getoption('enablehollatag').'</option>
                       <option value="yes">Yes</option>
                       <option value="no">No</option>
                       </select>
    <br>
    
    </div>
    <div class="mb-2 hollatagdiv">
    <label>Receive SMS Alert TO e.g (07049626922)</label><br>
    <input type="text" name="hollatagcompany" class="hollatagcompany" placeholder="HollaTag Company Name" value="'.vp_getoption('hollatagcompany').'" ><br>
    <label> HollaTags Username </label><br>
    <input type="text" name="hollatagusername" class="hollausername" placeholder="HollaTags Username" value="'.vp_getoption('hollatagusername').'" ><br>
    <label> HollaTags Password </label><br>
    <input type="text" name="hollatagpassword" placeholder="HollaTags Password" value="'.vp_getoption('hollatagpassword').'"><br>
    <br>
    <span id="basic-addon1">HollaTags For Airtel In e.g (sme,corporate,direct):- </span><br>
    <input type="text" name="hollatagservices" placeholder="Enter Services Separate By Comma To Use HollaTags" value="'.vp_getoption('hollatagservices').'"  >
    <br>
    
    <script>
    var holla = jQuery(".enablehollatag").val();
    if(holla == "yes" || "'.vp_getoption('enablehollatag').'" == "yes" ){
        jQuery(".hollatagdiv").show();
    }
    else{
        jQuery(".hollatagdiv").hide();
    }
    
    jQuery(".enablehollatag").on("change",function(){
        var holla = jQuery(".enablehollatag").val();
    if(holla == "yes" ){
        jQuery(".hollatagdiv").show();
    }
    else{
        jQuery(".hollatagdiv").hide();
    }
    });
    </script>
    
    </div>
    
    
    </div>
    </div>


    <div class="border border-secondary mb-3 me-3" >
    <div id="messages" class="p-2 py-3">Messages</div>
    <div class="p-3"  style="background-color:#f0f0f1;">
    <div class="mb-2">
    <label class="form-label" >Message Your Users</label><br>
    <textarea class="form-control updateThis " name="message">'.vp_getoption("vpwalm").'</textarea>
    <div class="input-group">
    <span class="input-group-text">Show Pop-Up</span>
    <select name="show_notify" class="show_notify updateThis">
    <option value="'.vp_getoption("show_notify").'">'.vp_getoption("show_notify").'</option>
    <option value="yes">Yes</option>
    <option value="no">No</option>
    </select>
    </div>
    <br>
    </div>
    
    <div class="mb-2">
    <label class="form-label" >Message For Users On Fund Wallet Page</label><br>
    <textarea class="form-control updateThis " name="fundmessage">'.vp_getoption("manual_funding").'</textarea>
    <br>
    </div>
    
    </div>
    </div>



    
';

if(vp_getoption("vtupress_custom_weblinksms") == "yes"){
  echo '
    <div class="border border-secondary mb-3 me-3" >
      <div id="smsblasts" class="p-2 py-3">Weblink SMS Blaster</div>
      <div class="p-3"  style="background-color:#f0f0f1;">
          <div class="mb-2">
              <div class="input-group">
                  <span class="input-group-text">Enable Transactional SMS</span>
                  <select name="sms_transactional" class="sms_transactional updateThis">
                      <option value="'.vp_getoption("sms_transactional").'">'.vp_getoption("sms_transactional").'</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                  </select>
              </div>
              <div class="input-group">
                  <span class="input-group-text">Enable Welcome Message Sent To Sms</span>
                  <select name="sms_welcome" class="sms_welcome updateThis">
                      <option value="'.vp_getoption("sms_welcome").'">'.vp_getoption("sms_welcome").'</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                  </select>
              </div>
          <br>
          </div>
      </div>
    </div>
';
}

echo '
    <div class="border border-secondary mb-3 me-3" >

    <div id="customs" class="p-2 py-3">Customs</div>

    <div class="p-3"  style="background-color:#f0f0f1;">
    
';
if(vp_getoption("vtupress_custom_ibrolinks_profit") =="yes"){

?>
    <div class="mb-2">
      <div class="input-group">
      <span class="input-group-text">Ibrolinks (Whole Number) :</span>
      <input type="number" name="ibrolinks_profit" class="ibrolinks_profit updateThis" value="<?php echo intval(vp_getoption("ibrolinks_profit"));?>"/>
      </div>
        <br>
    </div>
<?php

}

echo' 
    </div>
    </div>
    
    <input type="button" class="btn btn-primary fset"  name="fset" value="Save" >
    </form>
    
    <script>
    
    jQuery(".fset").click(function(){
        jQuery(".preloader").show();
    var obj = {};
    var toatl_input = jQuery("input.updateThis, select.updateThis, textarea.updateThis ").length;
    var run_obj;
    
    for(run_obj = 0; run_obj <= toatl_input; run_obj++){
    var current_input = jQuery("input.updateThis, select.updateThis, textarea.updateThis").eq(run_obj);
    
    
    var obj_name = current_input.attr("name");
    var obj_value = current_input.val();
    
    if(typeof obj_name !== typeof undefined && obj_name !== false){

    obj[obj_name] = obj_value;
    }
        
        
    }
    
    obj["fset"] = "yes";
    obj["spraycode"] = jQuery(".spraycode").val();
    
    jQuery.ajax({
      url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/general.php')).'",
      data: obj,
      dataType: "json",
      "cache": false,
      "async": true,
      error: function (jqXHR, exception) {
          jQuery(".preloader").hide();
            var msg = "";
            if (jqXHR.status === 0) {
                msg = "No Connection.\n Verify Network.";
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
            } else if (exception === "parsererror"){
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
    ';
    
    }
    else{
	
        echo'
        <div class="bg bg-primary text-white container p-3" >
        Permission Not Granted!
        </div>
        ';
    }



?>

  </div>
  </div>
  </div>
</div>

<?php
}?>