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
<label class="form-check-label input-group-text float-start" for="flexSwitchCheckChecked">Alpha Data Status</label>
<input onchange="changestatus('alpha')" value="checked" class="form-check-input input-group-text h-100 alpha float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"alphacontrol");?>>
</div>
</div>

<div class="form-check form-switch col d-flex justify-content-end ">
<div class="input-group">
<label class="form-check-label input-group-text" for="flexSwitchCheckChecked1">Data Status</label>
<input onchange="changestatus('data')" value="checked" class="form-check-input input-group-text h-100 data" type="checkbox" role="switch" id="flexSwitchCheckChecked1" <?php echo vp_option_array($option_array,"setdata");?>>
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
        }  else if (jqXHR.status == 404) {
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





<div id="shareddata">


<h3>ALPHA</h3><br>
 <div class="alert alert-danger mb-2" role="alert">
<?php echo vp_option_array($option_array,"alpha_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="alphabaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"alphabaseurl");?>" name="alphabaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="alphaendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"alphaendpoint");?>" name="alphaendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="alpharequesttext" name="alpharequesttext" value="<?php echo vp_option_array($option_array,"alpharequesttext");?>" readonly class="form-control"><br>
<select name="alpharequest" id="alpharequest" >
<option value="<?php echo vp_option_array($option_array,"alpharequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#alpharequest").on("change",function(){
	jQuery("#alpharequesttext").val(jQuery("#alpharequest option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="alpharesponsetext" name="alpharesponsetext" value="<?php echo vp_option_array($option_array,"alpha1_response_format_text");?>" readonly class="form-control">
<select name="alpharesponse" id="alpharesponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"alpha1_response_format");?>"><?php echo vp_option_array($option_array,"alpha1_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#alpharesponse").on("change",function(){
	jQuery("#alpharesponsetext").val(jQuery("#alpharesponse option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">ALPHA Data Re-Query</span>
<input type="text" id="alphaquerytext" name="alphaquerytext" value="<?php echo vp_option_array($option_array,"alphaquerytext");?>" class="visually-hidden form-control">
<select name="alphaquerymethod" id="alphaquerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"alphaquerymethod");?>"><?php echo vp_option_array($option_array,"alphaquerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="alphaaddendpoint" id="alphaaddendpoint" class="input-group-text visually-hidden">
<option value="<?php echo vp_option_array($option_array,"alphaaddendpoint");?>"><?php echo vp_option_array($option_array,"alphaaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
<div class="input-group md-3">
<span class="input-group-text">ALPHA Data Response ID</span>
<input type="text" id="alpharesponse_id" name="alpharesponse_id" value="<?php echo vp_option_array($option_array,"alpharesponse_id");?>" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphaaddpost");?>" class="alphaaddpost2" readonly class="form-control"><br>
<select name="alphaaddpost" class="alphaaddpost">
<option value="<?php echo vp_option_array($option_array,"alphaaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
<script>
jQuery(".alphaaddpost").on("change",function(){
	jQuery(".alphaaddpost2").val(jQuery(".alphaaddpost option:selected").val());
});
</script>
</select>
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($alphaheaders=1; $alphaheaders<=1; $alphaheaders++){
?>

<div class="input-group mb-2">
<select class="alpha-head" name="alphahead">
<option value="<?php echo vp_option_array($option_array,"alpha_head");?>"><?php echo vp_option_array($option_array,"alpha_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="alphahead<?php echo $alphaheaders;?>" value="<?php echo vp_option_array($option_array,"alphahead".$alphaheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="alphavalue<?php echo $alphaheaders;?>" value="<?php echo vp_option_array($option_array,"alphavalue".$alphaheaders);?>" class="form-control alphapostkey simple fillable">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($alphaaddheaders=1; $alphaaddheaders<=4; $alphaaddheaders++){
	
				if($alphaaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="alphaaddheaders<?php echo $alphaaddheaders;?>" value="<?php echo vp_option_array($option_array,"alphaaddheaders".$alphaaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple alphaaddheaders<?php echo $alphaaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="alphaaddvalue<?php echo $alphaaddheaders;?>" value="<?php echo vp_option_array($option_array,"alphaaddvalue".$alphaaddheaders);?>" class="form-control simple <?php echo $hide_this;?>  fillable alphaaddvalue<?php echo $alphaaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">ALPHA Post Datas </label>
<br>
<?php
for($alphapost=1; $alphapost<=5; $alphapost++){
		if($alphapost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $alphapost;?></span>
<input type="text" placeholder="alpha" value="<?php echo vp_option_array($option_array,"alphapostdata".$alphapost);?>" name="alphapostdata<?php echo $alphapost;?>" class="simple <?php echo $hide_this;?> form-control">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value <?php echo $alphapost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"alphapostvalue".$alphapost);?>" name="alphapostvalue<?php echo $alphapost;?>" class=" simple fillable <?php echo $hide_this;?>  form-control alphapostvalue<?php echo $alphapost;?>"><br>
</div>

<?php
}
?>

<br>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Phone Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphaphoneattribute");?>" name="alphaphoneattribute"  id="alphaphoneattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphanetworkattribute");?>" name="alphanetworkattribute"  id="alphanetworkattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphaamountattribute");?>" name="alphaamountattribute"  id="alphaamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Data Variation Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphavariationattr");?>" name="alphavariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alpharequest_id");?>" name="alpharequest_id" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">DataType Attribute</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alpha_datatype");?>" name="alpha_datatype" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Extra Attribute [1</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alpha_extra1");?>" name="alpha_extra1" class="form-control">
</div>

</div>

<br>

<div class="not-simple">
<br>
<label class="form-label">Service IDs</label>
<div class="input-group mb-3">
<span class="input-group-text">ALPHA Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphaidattr");?>" name="alphaidattr" id="alphaidattr" class="form-control">
<span class="input-group-text">ALPHA Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphaid_datatype");?>" name="alphaid_datatype" id="alphaid_datatype" class="form-control">


</div>
<br>



<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphasuccesscode");?>" name="alphasuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphasuccessvalue");?>" name="alphasuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"alphasuccessvalue2");?>" name="alphasuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

</div>

<label class="form-label simple">ALPHA DATA PLAN</label><br>

<?php
for($i=0; $i<=15; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"calphadata".$i);?>"  name="calphadata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"calphadatan".$i);?>"  name="calphadatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"calphadatap".$i);?>"  name="calphadatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">
</div>

<?php
}
?>

<button class="btn btn-primary" onclick="saveit()">SAVE SETTINGS</button>
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