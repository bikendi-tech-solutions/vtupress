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
if(current_user_can("vtupress_admin")){

$option_array = json_decode(get_option("vp_options"),true);

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

                <div class="row">
  <div class="form-check form-switch d-flex col">
  <div class="input-group">
<label class="form-check-label input-group-text" for="flexSwitchCheckChecked">Awuf Airtime Status</label>
<input onchange="changestatus('awuf')" value="checked" class="form-check-input awuf h-100 input-group-text  " type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"awufcontrol");?>>
</div>
</div>

<div class="form-check form-switch col d-flex justify-content-end ">
  <div class="input-group">
<label class="form-check-label input-group-text" for="flexSwitchCheckChecked1">Airtime Status</label>
<input onchange="changestatus('airtime')" value="checked" class="form-check-input h-100 input-group-text airtime" type="checkbox" role="switch" id="flexSwitchCheckChecked1" <?php echo vp_option_array($option_array,"setairtime");?>>
</div>
</div>

</div>

<script>
function changestatus(type){
var obj = {}
if(jQuery("input."+type).is(":checked")){
  obj["set_status"] = "checked";
}
else{
  obj["set_status"] = "unchecked";
}

obj["set_control"] = type;
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";



  jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/controls.php'));?>",
  data: obj,
  dataType: "text",
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
	jQuery(".preloader").hide();
        if(data == "100" ){
	location.reload();
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

}

</script>

                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
                 If you have a running services prior before now then your subscription might be interupted. Kindly check your vtupress account or           </div>
    </div>


<div class="row">
<div class="col saveit">





<div id="awufairtime">

<h3>AWUF</h3><br>
<div class="alert alert-success mb-2" role="alert">
<?php echo vp_option_array($option_array,"awuf_info");?>
</div>

<div class="not-simple">

<div class="input-group md-3">
<span class="input-group-text">Airtime BaseUrl</span>
<input type="text" id="wairtimebaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"wairtimebaseurl");?>" name="wairtimebaseurl" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime EndPoint</span>
<input type="text" id="wairtimeendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"wairtimeendpoint");?>" name="wairtimeendpoint" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime Request Method</span>
<input type="text" id="wairtimerequesttext" name="wairtimerequesttext" value="<?php echo vp_option_array($option_array,"wairtimerequesttext");?>" readonly class="form-control">
<select name="wairtimerequest" id="wairtimerequest" class="input-group-text wairtimerequest">
<option value="<?php echo vp_option_array($option_array,"wairtimerequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#wairtimerequest").on("change",function(){
	jQuery("#wairtimerequesttext").val(jQuery(".wairtimerequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime Response Method</span>
<input type="text" id="airtimeresponsetext3" name="airtimeresponsetext3" value="<?php echo vp_option_array($option_array,"airtime3_response_format_text");?>" readonly class="form-control">
<select name="airtimeresponse3" id="airtimeresponse3" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"airtime3_response_format");?>"><?php echo vp_option_array($option_array,"airtime3_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#airtimeresponse3").on("change",function(){
	jQuery("#airtimeresponsetext3").val(jQuery("#airtimeresponse3 option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">AWUF Airtime Query</span>
<input type="text" id="awufquerytext" name="awufquerytext" value="<?php echo vp_option_array($option_array,"awufquerytext");?>" class="visually-hidden form-control">
<select name="awufquerymethod" id="awufquerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"awufquerymethod");?>"><?php echo vp_option_array($option_array,"awufquerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="awufaddendpoint" id="awufaddendpoint" class="visually-hidden input-group-text">
<option value="<?php echo vp_option_array($option_array,"awufaddendpoint");?>"><?php echo vp_option_array($option_array,"awufaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group md-3">
<span class="input-group-text">AWUF Airtime Response ID</span>
<input type="text" id="awufresponse_id" name="awufresponse_id" value="<?php echo vp_option_array($option_array,"awufresponse_id");?>" class="form-control">
</div>


<div class="input-group md-4">
<span class="input-group-text">Add Post Datas to Airtime Service?</span> 
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimeaddpost");?>" class="input-group-text wairtimeaddpost2" readonly class="form-control">
<select name="wairtimeaddpost" class="input-group-text wairtimeaddpost">
<option value="<?php echo vp_option_array($option_array,"wairtimeaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
<script>
jQuery(".wairtimeaddpost").on("change",function(){
	jQuery(".wairtimeaddpost2").val(jQuery(".wairtimeaddpost").val());
});
</script>
</div>

</div>
<br>
<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($airtimeheaders=1; $airtimeheaders<=1; $airtimeheaders++){
?>
<div class="input-group md-3">
<select class="airtime-head3" name="airtimehead3">
<option value="<?php echo vp_option_array($option_array,"airtime_head3");?>"><?php echo vp_option_array($option_array,"airtime_head3");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span> 	
<input type="text" name="wairtimehead<?php echo $airtimeheaders;?>" value="<?php echo vp_option_array($option_array,"wairtimehead".$airtimeheaders);?>"  placeholder="Key" class="form-control simple">
<span class="input-group-text simple">Value</span> 
<input placeholder="Value" type="text" name="wairtimevalue<?php echo $airtimeheaders;?>" value="<?php echo vp_option_array($option_array,"wairtimevalue".$airtimeheaders);?>" class="form-control awufpostkey simple fillable">
</div>
<?php
}
?>
<br>
<label class="form-label simple">Other Headers</label>
<br>
<?php
for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
	
				if($awufaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>
<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this?>">Key</span> 	
<input type="text" name="awufaddheaders<?php echo $awufaddheaders;?>" value="<?php echo vp_option_array($option_array,"awufaddheaders".$awufaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this?>">Value</span> 
<input placeholder="Value" type="text" name="awufaddvalue<?php echo $awufaddheaders;?>" value="<?php echo vp_option_array($option_array,"awufaddvalue".$awufaddheaders);?>" class="form-control <?php echo $hide_this;?> simple fillable awufaddvalue<?php echo $awufaddheaders;?>">
</div>

<?php
}

?>
<br>
<label class="form-label simple">Awuf Post Datas </label>
<br>
<?php
for($airtimepost=1; $airtimepost<=5; $airtimepost++){
		if($airtimepost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>
<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $airtimepost;?></span> 
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"wairtimepostdata".$airtimepost);?>" name="wairtimepostdata<?php echo $airtimepost;?>" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Post Value <?php echo $airtimepost;?></span> 
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"wairtimepostvalue".$airtimepost);?>" name="wairtimepostvalue<?php echo $airtimepost;?>" class="form-control <?php echo $hide_this;?>  wairtimepostvalue<?php echo $airtimepost;?> simple fillable">
</div>
<?php
}

?>
<br>

<div class="not-simple">
<label class="form-label">Service Parameters</label>
<br>
<div class="input-group md-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimeamountattribute");?>" name="wairtimeamountattribute"  id="wairtimeamountattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Phone Number Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimephoneattribute");?>" name="wairtimephoneattribute"  id="wairtimephoneattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimenetworkattribute");?>" name="wairtimenetworkattribute"  id="wairtimenetworkattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"warequest_id");?>" name="warequest_id" class="form-control">
</div>

<br>
<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group md-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimesuccesscode");?>" name="wairtimesuccesscode" placeholder="success value e.g success or status" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimesuccessvalue");?>" name="wairtimesuccessvalue" placeholder="success value" class="form-control"> 
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimesuccessvalue2");?>" name="wairtimesuccessvalue2" placeholder="alternative success value" class="form-control">
</div>

<br>
<label class="form-label">Service IDs</label>
<br>
<div class="input-group md-3">
<span class="input-group-text">MTN</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimemtn");?>" name="wairtimemtn" id="wairtimemtn" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">GLO</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimeglo");?>" name="wairtimeglo" id="wairtimeglo" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">9MOBILE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtime9mobile");?>" name="wairtime9mobile"  id="wairtime9mobile" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">AIRTEL</span>
<input type="text" value="<?php echo vp_option_array($option_array,"wairtimeairtel");?>" name="wairtimeairtel"  id="wairtimeairtel" class="form-control">

</div>

</div>
<button class="btn btn-primary  mt-3" onclick="saveit()">SAVE SETTINGS</button>
</div>

</div><!--==================COL====================-->
</div><!--==================ROW======================-->

<script>


function saveit(){
var obj = {};
jQuery(".preloader").show();

var obj = {};
var toatl_input = jQuery(".saveit input,.saveit select").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".saveit input,.saveit select").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}


	
}
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";


jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/apis/'.$_GET["subpage"].'.php'));?>",
  data: obj,
  dataType: "text",
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
	jQuery(".preloader").hide();
        if(data == "100" ){
	
		  swal({
  title: "DONE",
  text: "Saved Successfully!",
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


}
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