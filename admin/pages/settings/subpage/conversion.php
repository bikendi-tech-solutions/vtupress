<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

    if($_GET["subpage"] == "conversion"){
      if(vp_getoption("resell") != "yes"){
        vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
      }
echo'
<div class="card col">
<div class="card-body">
    <h5 class="card-title">Conversion</h5>
    <div class="table-responsive">

<div class="airtime_gateway update1">
<form method="post">
<div class="enable_conversion mb-2">
<label class="form-label">Enable Conversions</label><br>
<div class="input-group">
<span class="input-group-text">Enable Airtime To Wallet</span>
<select class="airtime_to_wallet" name="airtime_to_wallet">
<option value="'.vp_getoption("airtime_to_wallet").'">'.vp_getoption("airtime_to_wallet").'</option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group">
<span class="input-group-text">Enable Airtime To Cash [Manual]</span>
<select class="airtime_to_cash" name="airtime_to_cash">
<option value="'.vp_getoption("airtime_to_cash").'">'.vp_getoption("airtime_to_cash").'</option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
</div>
  
<div class="to_pay_to md-2">
<label class="form-label">Enter Numbers To Pay To</label>
<div class="input-group">
<span class="input-group-text">MTN</span>
<input class="form-control mtn_airtime" type="number" name="mtn_airtime" value="'.vp_getoption("mtn_airtime").'">
</div>

<div class="input-group">
<span class="input-group-text">GLO</span>
<input class="form-control glo_airtime" type="number" name="glo_airtime" value="'.vp_getoption("glo_airtime").'">
</div>

<div class="input-group">
<span class="input-group-text">AIRTEL</span>
<input class="form-control airtel_airtime" type="number" name="airtel_airtime" value="'.vp_getoption("airtel_airtime").'">
</div>

<div class="input-group">
<span class="input-group-text">9MOBILE</span>
<input class="form-control 9mobile_airtime" type="number" name="9mobile_airtime" value="'.vp_getoption("9mobile_airtime").'">
</div>
</div>
<!--End Of Numbers To Pay To-->

<div class="conversion_charge mt-2 mb-2">
<label class="form-label">MTN Charges</label>
<div class="input-group">
<span class="input-group-text">Airtime To Wallet</span>
<input type="number" class="airtime_to_wallet_charge" value="'.vp_getoption("airtime_to_wallet_charge").'" name="airtime_to_wallet_charge">
<span class="input-group-text">%</span>
<span class="input-group-text">Airtime To Cash</span>
<input type="number" class="airtime_to_cash_charge" value="'.vp_getoption("airtime_to_cash_charge").'" name="airtime_to_cash_charge">
<span class="input-group-text">%</span>
</div>
</div>


<div class="conversion_charge mt-2 mb-2">
<label class="form-label">GLO Charges</label>
<div class="input-group">
<span class="input-group-text">Airtime To Wallet</span>
<input type="number" class="gairtime_to_wallet_charge" value="'.vp_getoption("gairtime_to_wallet_charge").'" name="gairtime_to_wallet_charge">
<span class="input-group-text">%</span>
<span class="input-group-text">Airtime To Cash</span>
<input type="number" class="gairtime_to_cash_charge" value="'.vp_getoption("gairtime_to_cash_charge").'" name="gairtime_to_cash_charge">
<span class="input-group-text">%</span>
</div>
</div>

<div class="conversion_charge mt-2 mb-2">
<label class="form-label">AIRTEL Charges</label>
<div class="input-group">
<span class="input-group-text">Airtime To Wallet</span>
<input type="number" class="aairtime_to_wallet_charge" value="'.vp_getoption("aairtime_to_wallet_charge").'" name="aairtime_to_wallet_charge">
<span class="input-group-text">%</span>
<span class="input-group-text">Airtime To Cash</span>
<input type="number" class="aairtime_to_cash_charge" value="'.vp_getoption("aairtime_to_cash_charge").'" name="aairtime_to_cash_charge">
<span class="input-group-text">%</span>
</div>
</div>

<div class="conversion_charge mt-2 mb-2">
<label class="form-label">9MOBILE Charges</label>
<div class="input-group">
<span class="input-group-text">Airtime To Wallet</span>
<input type="number" class="9airtime_to_wallet_charge" value="'.vp_getoption("9airtime_to_wallet_charge").'" name="9airtime_to_wallet_charge">
<span class="input-group-text">%</span>
<span class="input-group-text">Airtime To Cash</span>
<input type="number" class="9airtime_to_cash_charge" value="'.vp_getoption("9airtime_to_cash_charge").'" name="9airtime_to_cash_charge">
<span class="input-group-text">%</span>
</div>
</div>

<input type="button" name="update1" value="Save Coversion" class="btn btn-primary update22">
</form>
<script>
    
    
jQuery(".update22").on("click",function(){
     jQuery(".preloader").show();
var obj = {};

obj["update1"] = "update";
obj["airtime_to_cash"] = jQuery(".airtime_to_cash").val();
obj["airtime_to_wallet"] = jQuery(".airtime_to_wallet").val();
obj["mtn_airtime"] = jQuery(".mtn_airtime").val();
obj["glo_airtime"] = jQuery(".glo_airtime").val();
obj["airtel_airtime"] = jQuery(".airtel_airtime").val();
obj["9mobile_airtime"] = jQuery(".9mobile_airtime").val();
obj["airtime_to_wallet_charge"] = jQuery(".airtime_to_wallet_charge").val();
obj["9airtime_to_wallet_charge"] = jQuery(".9airtime_to_wallet_charge").val();
obj["aairtime_to_wallet_charge"] = jQuery(".aairtime_to_wallet_charge").val();
obj["gairtime_to_wallet_charge"] = jQuery(".gairtime_to_wallet_charge").val();
obj["airtime_to_cash_charge"] = jQuery(".airtime_to_cash_charge").val();
obj["aairtime_to_cash_charge"] = jQuery(".aairtime_to_cash_charge").val();
obj["9airtime_to_cash_charge"] = jQuery(".9airtime_to_cash_charge").val();
obj["gairtime_to_cash_charge"] = jQuery(".gairtime_to_cash_charge").val();
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/atc.php')).'",
  data: obj,
  dataType: "text",
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
        if(data == "100" ){
    
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
</div>
';
}?>
