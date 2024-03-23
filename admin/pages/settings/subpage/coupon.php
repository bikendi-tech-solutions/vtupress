<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

    if($_GET["subpage"] == "coupon"){
      if(vp_getoption("resell") != "yes"){
        vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
      }
        
echo'

<!-- End Of Airtime Conversion-->

<div class="coupon_div row">

<div class="card col">
              <div class="card-body">
                  <h5 class="card-title">Coupon</h5>
                  <div class="table-responsive">

<!--///////////////////////////////////////////////////////////////////////////////-->
<div class="input-group mb-2 mt-2">
<span class="input-group-text">Enable Coupon System</span>
<select name="enable_coupon" class="enable_coupon">
<option value="'.vp_getoption("enable_coupon").'">'.vp_getoption("enable_coupon").'</option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>

<script>
jQuery("select.enable_coupon").on("change",function(){
 jQuery(".preloader").show();
var change = jQuery("select.enable_coupon").val();

var obj = {};

obj["enable_coupon"] = jQuery("select.enable_coupon").val();
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
      }
      else if (jqXHR.status == 404) {
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
  text: "Coupon set to "+change,
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
<!--///////////////////////////////////////////END OF ENABLE COUPON SYSTEM ////////////////////////////////////-->


<!--///////////////////////////////////////////BEGINNING OF GENERATE COUPON SYSTEM ////////////////////////////////////-->
<div class="coupon_form mb-2 mt-2">
<div class="input-group">
<span class="input-group-text">CODE</span>
<input class="form-control coupon_code" type="text" name="">
<span class="input-group-text">Applicable To [USER IDS]</span>
<input class="applicable_to form-control" type="text" name="">
<span class="input-group-text">Amount</span>
<input class="form-control coupon_amount">

<input type="button" class="btn btn-success form-control coupon_generate1" value="Generate">
</div>
<script>
jQuery(".applicable_to").on("click",function(){
    alert("Separate each User Id\'s by comma [,]");
});

jQuery("input.coupon_generate1").on("click",function(){
 jQuery(".preloader").show();
var obj = {};

obj["coupon_generate"] = "update";
obj["coupon_code"] = jQuery(".coupon_code").val();
obj["applicable_to"] = jQuery(".applicable_to").val();
obj["coupon_amount"] = jQuery(".coupon_amount").val();
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
  title: "SUCCESS",
  text: "Coupon Generated",
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
<!-- End of generate coupon form group -->
<!--///////////////////////////////////////////END OF GENERATE COUPON SYSTEM ////////////////////////////////////-->
';




######################################################## RUN COUPONS ####################################
global $wpdb;
$table_name = $wpdb->prefix.'vp_coupon';
$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY ID DESC");
 
echo"
<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
<th scope='col'>Id</th>
<th scope='col'>Code</th>
<th scope='col'>Applicable To [ID's]</th>
<th scope='col'>Used By [ID's]</th>
<th scope='col'>Amount</th>
<th scope='col'>Status</th>
<th scope='col'>Action</th>
</tr>
</thead>
";
foreach($results as $coupon){
$id = $coupon->id;
$code = $coupon->code;
$app = $coupon->applicable_to;
$used = $coupon->used_by;
$status = $coupon->status;
$amount = $coupon->amount;
echo"

<tr>
<td scope='col'>$id</td>
<td scope='col'>$code</td>
<td scope='col'>$app</td>
<td scope='col'>$used</td>
<td scope='col'>$amount</td>
<td scope='col'>$status</td>
<td scope='col'>
<select class='set_coupon_status_to$id'>
<option value='none'>--Select--</option>
<option value='close'>CLOSE</option>
<option value='open'>OPEN</option>
<option value='delete'>DELETE</option>
<option value='edit'>Edit Users ID</option>
</select>


<script>
jQuery('.set_coupon_status_to$id').on('change',function(){
    var obj = {};
var edit_value = jQuery('.set_coupon_status_to$id').val();
switch(edit_value){
    case'close':
    if(confirm('Want To Close This Coupon?') == true){
    ";
echo'
   jQuery(".preloader").show();
obj["coupon_close_edit"] = "close";
obj["coupon_id"] = "'.$id.'";
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
  title: "CLOSED",
  text: "Coupon Closed Successfully",
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

';

echo"	
    }
    break;
    case'open':
        if(confirm('Want To Open This Coupon?') == true){
    ";
echo'
   jQuery(".preloader").show();
obj["coupon_open_edit"] = "close";
obj["coupon_id"] = "'.$id.'";
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
  title: "OPENED",
  text: "Coupon Opened Successfully",
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

';

echo"	
    }
    break;
    case'delete':
            if(confirm('Want To Delete This Coupon?') == true){
    ";
echo'
   jQuery(".preloader").show();
obj["coupon_delete_edit"] = "close";
obj["coupon_id"] = "'.$id.'";
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
  title: "DELETED",
  text: "Coupon Deleted Successfully",
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

';

echo"	
    }
    break;
    case'edit':
    let ids = prompt('EDIT USERS ID', '$app');

if (ids != null && ids != '$app') {
    
    ";
echo'
   jQuery(".preloader").show();
obj["coupon_edit"] = "edit";
obj["coupon_user_edit"] = ids;
obj["coupon_id"] = "'.$id.'";
obj["spraycode"] = "'.vp_getoption("spraycode").'";

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php')).'",
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
  title: "EDITED",
  text: "Coupon Users Editted",
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

';

echo"	
}
    break;
    
    
}

});
</script>

</td>
</tr>
";	
    
    
}

echo'
</table>
<!--END OF TABLES -->
<!--//////////////////////////////////////////////////////////////////////////////////END OF COUPON DIV /////////////////////////////////////-->
</div>
</div>
</div>
</div>
<!-- END OF COUPON DIV-->
';

}?>

