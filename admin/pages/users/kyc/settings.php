<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(vp_getoption("resell") != "yes"){
  vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
}

?>

<div class="row">

    <div class="col-12">
    <link
      rel="stylesheet"
      type="text/css"
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
<div class="card">
                <div class="card-body">
                  <h5 class="card-title">Settings</h5>
                  <div class="table-responsive">
<?php
	global $wpdb;
	$table_name = $wpdb->prefix."vp_kyc_settings";
	$datas = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
	$option_array = json_decode(get_option("vp_options"),true);
  ?>


<div class="form-row row">
    <div class="col-12 col-md mb-3 mb-md-1">
    <label for="id">Enable Kyc:</label>
<select class="enable_kyc form-control">
<option value="<?php echo $datas[0]->enable;?>"><?php echo strtoupper($datas[0]->enable);?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
    </div>
    <div class="col-12 col-md  mb-3 mb-md-1">
    <label for="key">Transaction Limit Only Applies For Unverified Users:</label>
    <input type="number" name="kyc_limit" class="kyc_limit form-control" value="<?php echo $datas[0]->kyc_limit;?>"><br>
    </div>
    <div class="col-12 col-md  mb-3 mb-md-1">
    <label for="submit">Duration:</label>
<select class="kyc_duration form-control">
<option value="<?php echo $datas[0]->duration;?>"><?php echo strtoupper($datas[0]->duration);?></option>
<option value="total">Total Transaction Sum</option>
<option value="day">Per Day</option>
<option value="month">Per Month</option>
</select>
    </div>
  </div>
<script>
jQuery(".enable_kyc, .kyc_duration, .kyc_limit").on("change",function(){
	var enable = jQuery(".enable_kyc").val();
	var limit = jQuery(".kyc_limit").val();
	var duration = jQuery(".kyc_duration").val();
	
 jQuery("#cover-spin").show();	

var obj = {};
obj["enable"] = enable;
obj["limit"] = limit;
obj["duration"] = duration;
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

 
jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/template/classic/sections/kycupload.php'));?>",
data : obj,
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
        if(data == "100"){
		  swal({
  title: "Successfully Changed KYC Status",
  text: "Thanks",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error Changing KYC tatus",
  text: "Click Why To See Reason",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
type : 'POST'
});


});

</script>
                  </div>
                </div>
              </div><!--End Of Card -->
</div>


</div>