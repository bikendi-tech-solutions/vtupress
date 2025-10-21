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
<div class="form-check form-switch card-title d-flex col">
<div class="input-group">
<label class="form-check-label float-start input-group-text for="flexSwitchCheckChecked">Shared Airtime Status</label>
<input onchange="changestatus('shared')" value="checked" class="form-check-input nput-group-text h-100 shared float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"sharecontrol");?>>
</div>
</div>

<div class="form-check form-switch col d-flex justify-content-end ">
<div class="input-group">
<label class="form-check-label input-group-text" for="flexSwitchCheckChecked1">Airtime Status</label>
<input onchange="changestatus('airtime')" value="checked" class="input-group-text h-100 form-check-input airtime" type="checkbox" role="switch" id="flexSwitchCheckChecked1" <?php echo vp_option_array($option_array,"setairtime");?>>
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


<div id="sharedairtime">

<h3>SHARED</h3>
<br>
<div class="alert alert-secondary mb-2" role="alert">
<?php echo vp_option_array($option_array,"shared_info");?>
</div>

<div class="not-simple">
<div class="input-group md-3">
<span class="input-group-text">Airtime BaseUrl</span>
<input type="text" id="sairtimebaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"sairtimebaseurl");?>" name="sairtimebaseurl" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime EndPoint</span>
<input type="text" id="sairtimeendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"sairtimeendpoint");?>" name="sairtimeendpoint" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime Request Method</span>
<input type="text" id="sairtimerequesttext" name="sairtimerequesttext" value="<?php echo vp_option_array($option_array,"sairtimerequesttext");?>" readonly class="form-control">
<select name="sairtimerequest" id="sairtimerequest" class="input-group-text sairtimerequest">
<option value="<?php echo vp_option_array($option_array,"sairtimerequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery(".sairtimerequest").on("change",function(){
	jQuery("#sairtimerequesttext").val(jQuery(".sairtimerequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Airtime Response Method</span>
<input type="text" id="airtimeresponsetext2" name="airtimeresponsetext2" value="<?php echo vp_option_array($option_array,"airtime2_response_format_text");?>" readonly class="form-control">
<select name="airtimeresponse2" id="airtimeresponse2" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"airtime2_response_format");?>"><?php echo vp_option_array($option_array,"airtime2_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#airtimeresponse2").on("change",function(){
	jQuery("#airtimeresponsetext2").val(jQuery("#airtimeresponse2 option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">SHARED Airtime Re-Query</span>
<input type="text" id="sharequerytext" name="sharequerytext" value="<?php echo vp_option_array($option_array,"sharequerytext");?>" class="visually-hidden form-control">
<select name="sharequerymethod" id="sharequerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"sharequerymethod");?>"><?php echo vp_option_array($option_array,"sharequerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="shareaddendpoint" id="shareaddendpoint" class=" visually-hidden input-group-text">
<option value="<?php echo vp_option_array($option_array,"shareaddendpoint");?>"><?php echo vp_option_array($option_array,"shareaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group md-3">
<span class="input-group-text">SHARED Airtime Response ID</span>
<input type="text" id="shareresponse_id" name="shareresponse_id" value="<?php echo vp_option_array($option_array,"shareresponse_id");?>" class="form-control">
</div>


<div class="input-group md-4">
<span class="input-group-text">Add Post Datas to Airtime Service?</span> 
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimeaddpost");?>" class="input-group-text sairtimeaddpost2" readonly class="form-control">
<select name="sairtimeaddpost" class="input-group-text sairtimeaddpost">
<option value="<?php echo vp_option_array($option_array,"sairtimeaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
<script>
jQuery(".sairtimeaddpost").on("change",function(){
	jQuery(".sairtimeaddpost2").val(jQuery(".sairtimeaddpost").val());
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
<select class="airtime-head2" name="airtimehead2">
<option value="<?php echo vp_option_array($option_array,"airtime_head2");?>"><?php echo vp_option_array($option_array,"airtime_head2");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span> 	
<input type="text" name="sairtimehead<?php echo $airtimeheaders;?>" value="<?php echo vp_option_array($option_array,"sairtimehead".$airtimeheaders);?>"  placeholder="Key" class="form-control simple">
<span class="input-group-text simple">Value</span> 
<input placeholder="Value" type="text" name="sairtimevalue<?php echo $airtimeheaders;?>" value="<?php echo vp_option_array($option_array,"sairtimevalue".$airtimeheaders);?>" class="form-control simple fillable sharedpostkey">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>
<?php
for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
	
				if($shareaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="shareaddheaders<?php echo $shareaddheaders;?>" value="<?php echo vp_option_array($option_array,"shareaddheaders".$shareaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="shareaddvalue<?php echo $shareaddheaders;?>" value="<?php echo vp_option_array($option_array,"shareaddvalue".$shareaddheaders);?>" class="form-control simple <?php echo $hide_this;?> fillable shareaddvalue<?php echo $shareaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Shared Post Datas</label>
<br>
<?php
for($airtimepost=1; $airtimepost<=8; $airtimepost++){
		if($airtimepost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $airtimepost;?></span> 
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"sairtimepostdata".$airtimepost);?>" name="sairtimepostdata<?php echo $airtimepost;?>" class="form-control <?php echo $hide_this;?>  simple">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value <?php echo $airtimepost;?></span> 
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"sairtimepostvalue".$airtimepost);?>" name="sairtimepostvalue<?php echo $airtimepost;?>" class="form-control <?php echo $hide_this;?> sairtimepostvalue<?php echo $airtimepost;?> simple fillable">
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
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimeamountattribute");?>" name="sairtimeamountattribute"  id="sairtimeamountattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Phone Number Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimephoneattribute");?>" name="sairtimephoneattribute"  id="sairtimephoneattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimenetworkattribute");?>" name="sairtimenetworkattribute"  id="sairtimenetworkattribute" class="form-control">
</div>

<div class="input-group md-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sarequest_id");?>" name="sarequest_id" class="form-control">
</div>

<br>
<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group md-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimesuccesscode");?>" name="sairtimesuccesscode" placeholder="success value e.g success or status" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimesuccessvalue");?>" name="sairtimesuccessvalue" placeholder="success value" class="form-control"> 
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimesuccessvalue2");?>" name="sairtimesuccessvalue2" placeholder="alternative success value" class="form-control">
</div>

<br>
<label class="form-label">Service IDs</label>
<br>
<div class="input-group md-3">
<span class="input-group-text">MTN</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimemtn");?>" name="sairtimemtn" id="sairtimemtn" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">GLO</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimeglo");?>" name="sairtimeglo" id="sairtimeglo" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">9MOBILE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtime9mobile");?>" name="sairtime9mobile"  id="sairtime9mobile" class="form-control">

</div>

<div class="input-group md-3">
<span class="input-group-text">AIRTEL</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sairtimeairtel");?>" name="sairtimeairtel"  id="sairtimeairtel" class="form-control">

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
  
        }   else if (jqXHR.status == 403) {
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