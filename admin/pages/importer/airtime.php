<?php
if(!defined('ABSPATH')){
  $pagePath = explode('/wp-content/', dirname(__FILE__));
  include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');
if(current_user_can("vtupress_access_users")){
?>

<div class="container-fluid license-container">
            <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
            <style>
                div.vtusettings-container *{
                    font-family:roboto;
                }
                .swal-button.swal-button--confirm {
                    width: fit-content;
                    padding: 10px !important;
                }
            </style>

<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.

                  </p>



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
                  <h5 class="card-title">DATA IMPORTER</h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
            Please note that the listed vendors does not belong to vtupress and issues with them should be directed to their admins/customer care reps.
          </div>
    </div>


<div class="row">

<div class="col vtu_airtime">

<span class="input-group-text">VTU AIRTIME</span>
<select name="vtu_airtime_select" class="vtu_airtime_select" >
<option value="<?php echo vp_option_array($option_array,"vtu_airtime_platform");?>"><?php echo vp_option_array($option_array,"vtu_airtime_platform");?></option>

<?php
$url = "https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?vtu_names";

$http_args = array(
  'headers' => array(
  'cache-control' => 'no-cache',
  'content-type' => 'application/json',
  'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
  ),
  'timeout' => 120,
  'sslverify' => false);

$data =  vp_get_contents($url);


$json = json_decode($data, true);
foreach($json as $key => $value){
	?>
	<option value='<?php echo $value;?>'><?php echo $key;?></option>
	<?php
}
?>
</select>
<input type="button" name="vtu_airtime_import" class="vtu_airtime_import" value="IMPORT">

</div>


<div class="col share_airtime">

<span class="input-group-text">SHARED AIRTIME</span>
<select name="share_airtime_select" class="share_airtime_select" >
<option value="<?php echo vp_option_array($option_array,"share_airtime_platform");?>"><?php echo vp_option_array($option_array,"share_airtime_platform");?></option>

<?php
$url = "https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?share_names";

$http_args = array(
  'headers' => array(
  'cache-control' => 'no-cache',
  'content-type' => 'application/json',
  'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
  ),
  'timeout' => 120,
  'sslverify' => false);

$data =  vp_get_contents($url);

$json = json_decode($data, true);
foreach($json as $key => $value){
	?>
	<option value='<?php echo $value;?>'><?php echo $key;?></option>
	<?php
}
?>
</select>
<input type="button" name="share_airtime_import" class="share_airtime_import" value="IMPORT">

</div>


<div class="col awuf_airtime">

<span class="input-group-text">AWUF AIRTIME</span>
<select name="awuf_airtime_select" class="awuf_airtime_select" >
<option value="<?php echo vp_option_array($option_array,"awuf_airtime_platform");?>"><?php echo vp_option_array($option_array,"awuf_airtime_platform");?></option>

<?php
$url = "https://vtupress.com/wp-content/plugins/vpimporter/vpimporter.php?awuf_names";


$http_args = array(
  'headers' => array(
  'cache-control' => 'no-cache',
  'content-type' => 'application/json',
  'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
  ),
  'timeout' => 120,
  'sslverify' => false);

$data =  vp_get_contents($url);

$json = json_decode($data, true);
foreach($json as $key => $value){
	?>
	<option value='<?php echo $value;?>'><?php echo $key;?></option>
	<?php
}
?>
</select>
<input type="button" name="awuf_airtime_import" class="awuf_airtime_import" value="IMPORT">

</div>


</div>

<script>

jQuery(".vtu_airtime_import").click(function(){
	jQuery("#cover-spin").show();
var obj = {};
var toatl_input = jQuery(".vtu_airtime select, .vtu_airtime input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".vtu_airtime select, .vtu_airtime input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
}
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/importer.php'));?>",
  data: obj,
  dataType: "text",
  "cache": false,
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
        if(data == "100" ){
	
		  swal({
  title: "Imported!",
  text: "Go To The Service To See Changes",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data == "101" ){
jQuery("#cover-spin").hide();
var select = jQuery(".vtu_airtime_select option:selected").text();
	swal({
  title: "Error",
  text: jQuery(".vtu_airtime_select option:selected").text()+" Importer Doesn\'t Exist  For This Service",
  icon: "error",
  button: "Okay",
});	  
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error!",
  text: data,
  icon: "warning",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});

</script>

<script>

jQuery(".share_airtime_import").click(function(){
	jQuery("#cover-spin").show();
var obj = {};
var toatl_input = jQuery(".share_airtime select, .share_airtime input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".share_airtime select, .share_airtime input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
}
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/importer.php'));?>",
  data: obj,
  dataType: "text",
  "cache": false,
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
        if(data == "100" ){
	
		  swal({
  title: "Imported!",
  text: "Go To The Service To See Changes",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data == "101" ){
jQuery("#cover-spin").hide();
var select = jQuery(".share_airtime_select option:selected").text();
	swal({
  title: "Error",
  text: jQuery(".share_airtime_select option:selected").text()+" Chosen Importer Doesn\'t Exist",
  icon: "error",
  button: "Okay",
});	  
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error!",
  text: data,
  icon: "warning",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});

</script>



<script>

jQuery(".awuf_airtime_import").click(function(){
	jQuery("#cover-spin").show();
var obj = {};
var toatl_input = jQuery(".awuf_airtime select, .awuf_airtime input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".awuf_airtime select, .awuf_airtime input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
}
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/importer.php'));?>",
  data: obj,
  dataType: "text",
  "cache": false,
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
        if(data == "100" ){
	
		  swal({
  title: "Imported!",
  text: "Go To The Service To See Changes",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data == "101" ){
jQuery("#cover-spin").hide();
var select = jQuery(".awuf_airtime_select option:selected").text();
	swal({
  title: "Error",
  text: jQuery(".awuf_airtime_select option:selected").text()+" Chosen Importer Doesn\'t Exist",
  icon: "error",
  button: "Okay",
});	  
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error!",
  text: data,
  icon: "warning",
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
</div>


</div>



</div>
<?php   
}?>