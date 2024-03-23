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
<label class="form-check-label input-group-text float-start" for="flexSwitchCheckChecked">Smile Data Status</label>
<input onchange="changestatus('smile')" value="checked" class="form-check-input input-group-text h-100 smile float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"smilecontrol");?>>
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


<h3>SMILE</h3><br>
 <div class="alert alert-danger mb-2" role="alert">
<?php echo vp_option_array($option_array,"smile_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="smilebaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"smilebaseurl");?>" name="smilebaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="smileendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"smileendpoint");?>" name="smileendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="smilerequesttext" name="smilerequesttext" value="<?php echo vp_option_array($option_array,"smilerequesttext");?>" readonly class="form-control"><br>
<select name="smilerequest" id="smilerequest" >
<option value="<?php echo vp_option_array($option_array,"smilerequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#smilerequest").on("change",function(){
	jQuery("#smilerequesttext").val(jQuery("#smilerequest option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="smileresponsetext" name="smileresponsetext" value="<?php echo vp_option_array($option_array,"smile1_response_format_text");?>" readonly class="form-control">
<select name="smileresponse" id="smileresponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"smile1_response_format");?>"><?php echo vp_option_array($option_array,"smile1_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#smileresponse").on("change",function(){
	jQuery("#smileresponsetext").val(jQuery("#smileresponse option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">SMILE Data Re-Query</span>
<input type="text" id="smilequerytext" name="smilequerytext" value="<?php echo vp_option_array($option_array,"smilequerytext");?>" class="visually-hidden form-control">
<select name="smilequerymethod" id="smilequerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"smilequerymethod");?>"><?php echo vp_option_array($option_array,"smilequerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="smileaddendpoint" id="smileaddendpoint" class="input-group-text visually-hidden">
<option value="<?php echo vp_option_array($option_array,"smileaddendpoint");?>"><?php echo vp_option_array($option_array,"smileaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
<div class="input-group md-3">
<span class="input-group-text">SMILE Data Response ID</span>
<input type="text" id="smileresponse_id" name="smileresponse_id" value="<?php echo vp_option_array($option_array,"smileresponse_id");?>" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smileaddpost");?>" class="smileaddpost2" readonly class="form-control"><br>
<select name="smileaddpost" class="smileaddpost">
<option value="<?php echo vp_option_array($option_array,"smileaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
<script>
jQuery(".smileaddpost").on("change",function(){
	jQuery(".smileaddpost2").val(jQuery(".smileaddpost option:selected").val());
});
</script>
</select>
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($smileheaders=1; $smileheaders<=1; $smileheaders++){
?>

<div class="input-group mb-2">
<select class="smile-head" name="smilehead">
<option value="<?php echo vp_option_array($option_array,"smile_head");?>"><?php echo vp_option_array($option_array,"smile_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="smilehead<?php echo $smileheaders;?>" value="<?php echo vp_option_array($option_array,"smilehead".$smileheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="smilevalue<?php echo $smileheaders;?>" value="<?php echo vp_option_array($option_array,"smilevalue".$smileheaders);?>" class="form-control smilepostkey simple fillable">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($smileaddheaders=1; $smileaddheaders<=4; $smileaddheaders++){
	
				if($smileaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="smileaddheaders<?php echo $smileaddheaders;?>" value="<?php echo vp_option_array($option_array,"smileaddheaders".$smileaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple smileaddheaders<?php echo $smileaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="smileaddvalue<?php echo $smileaddheaders;?>" value="<?php echo vp_option_array($option_array,"smileaddvalue".$smileaddheaders);?>" class="form-control simple <?php echo $hide_this;?>  fillable smileaddvalue<?php echo $smileaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">SMILE Post Datas </label>
<br>
<?php
for($smilepost=1; $smilepost<=5; $smilepost++){
		if($smilepost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $smilepost;?></span>
<input type="text" placeholder="smile" value="<?php echo vp_option_array($option_array,"smilepostdata".$smilepost);?>" name="smilepostdata<?php echo $smilepost;?>" class="simple <?php echo $hide_this;?> form-control">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value <?php echo $smilepost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"smilepostvalue".$smilepost);?>" name="smilepostvalue<?php echo $smilepost;?>" class=" simple fillable <?php echo $hide_this;?>  form-control smilepostvalue<?php echo $smilepost;?>"><br>
</div>

<?php
}
?>

<br>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Phone Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilephoneattribute");?>" name="smilephoneattribute"  id="smilephoneattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilenetworkattribute");?>" name="smilenetworkattribute"  id="smilenetworkattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smileamountattribute");?>" name="smileamountattribute"  id="smileamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Data Variation Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilevariationattr");?>" name="smilevariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilerequest_id");?>" name="smilerequest_id" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">DataType Attribute</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smile_datatype");?>" name="smile_datatype" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Extra Attribute [1</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smile_extra1");?>" name="smile_extra1" class="form-control">
</div>

</div>

<br>

<div class="not-simple">
<br>
<label class="form-label">Service IDs</label>
<div class="input-group mb-3">
<span class="input-group-text">SMILE Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smileidattr");?>" name="smileidattr" id="smileidattr" class="form-control">
<span class="input-group-text">SMILE Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smileid_datatype");?>" name="smileid_datatype" id="smileid_datatype" class="form-control">


</div>
<br>



<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilesuccesscode");?>" name="smilesuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilesuccessvalue");?>" name="smilesuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"smilesuccessvalue2");?>" name="smilesuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

</div>

<label class="form-label simple">SMILE DATA PLAN</label><br>

<?php
for($i=0; $i<=15; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"csmiledata".$i);?>"  name="csmiledata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"csmiledatan".$i);?>"  name="csmiledatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"csmiledatap".$i);?>"  name="csmiledatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">
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