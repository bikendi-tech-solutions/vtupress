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
$symbol = vp_country()["symbol"];
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

                
                <div class="form-check form-switch card-title d-flex">
                <div class="input-group">
<label class="form-check-label float-start input-group-text" for="flexSwitchCheckChecked">Cable Status</label>
<input onchange="changestatus('cable')" value="checked" class="form-check-input input-group-text h-100 cable float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"setcable");?>>
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
//alert(type);
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






<div id="cableeasyaccess" class="core-services" >

<div class="alert alert-light mb-2 border border-dark" role="alert">
<?php echo vp_option_array($option_array,"cable_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Cable BaseUrl</span>
<input type="text" id="cablebaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"cablebaseurl");?>" name="cablebaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Cable EndPoint</span>
<input type="text" id="cableendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"cableendpoint");?>" name="cableendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">Cable Request Method</span>
<input type="text" id="cablerequesttext" name="cablerequesttext" value="<?php echo vp_option_array($option_array,"cablerequesttext");?>" readonly class="form-control">
<select name="cablerequest" id="cablerequest">
<option value="<?php echo vp_option_array($option_array,"cablerequest");?>"> <?php echo vp_option_array($option_array,"cablerequest");?></option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#cablerequest").on("change",function(){
	jQuery("#cablerequesttext").val(jQuery("#cablerequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Cable Response Method</span>
<input type="text" id="cableresponsetext" name="cableresponsetext" value="<?php echo vp_option_array($option_array,"cable_response_format_text");?>" readonly class="form-control">
<select name="cableresponse" id="cableresponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"cable_response_format");?>"><?php echo vp_option_array($option_array,"cable_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#cableresponse").on("change",function(){
	jQuery("#cableresponsetext").val(jQuery("#cableresponse option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">CABLE Re-Query</span>
<input type="text" id="cablequerytext" name="cablequerytext" value="<?php echo vp_option_array($option_array,"cablequerytext");?>" class="visually-hidden form-control">
<select name="cablequerymethod" id="cablequerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"cablequerymethod");?>"><?php echo vp_option_array($option_array,"cablequerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="cableaddendpoint" id="cableaddendpoint" class="input-group-text visually-hidden">
<option value="<?php echo vp_option_array($option_array,"cableaddendpoint");?>"><?php echo vp_option_array($option_array,"cableaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group md-3">
<span class="input-group-text">CABLE Response ID</span>
<input type="text" id="cableresponse_id" name="cableresponse_id" value="<?php echo vp_option_array($option_array,"cableresponse_id");?>" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">Add Post Datas To Cable Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cableaddpost");?>" class="cableaddpost2" readOnly class="form-control">
<select name="cableaddpost" class="cableaddpost">
<option value="<?php echo vp_option_array($option_array,"cableaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
<script>
jQuery(".cableaddpost").on("change",function(){
	jQuery(".cableaddpost2").val(jQuery(".cableaddpost option:selected").val());
});
</script>
</div>
<br>
</div>


<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($cableheaders=1; $cableheaders<=1; $cableheaders++){
?>

<div class="input-group mb-2">
<select class="cable-head" name="cablehead">
<option value="<?php echo vp_option_array($option_array,"cable_head");?>"><?php echo vp_option_array($option_array,"cable_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="cablehead<?php echo $cableheaders;?>" value="<?php echo vp_option_array($option_array,"cablehead".$cableheaders);?>"  placeholder="cable" class="form-control simple">
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="cablevalue<?php echo $cableheaders;?>" value="<?php echo vp_option_array($option_array,"cablevalue".$cableheaders);?>" class="form-control simple fillable cablepostkey">
</div>
<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
	
				if($cableaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="cableaddheaders<?php echo $cableaddheaders;?>" value="<?php echo vp_option_array($option_array,"cableaddheaders".$cableaddheaders);?>"  placeholder="Key" class="<?php echo $hide_this;?> form-control simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="cableaddvalue<?php echo $cableaddheaders;?>" value="<?php echo vp_option_array($option_array,"cableaddvalue".$cableaddheaders);?>" class="<?php echo $hide_this;?> form-control simple fillable cableaddvalue<?php echo $cableaddheaders;?>">
</div>

<?php
}

?>

<br>
<label class="simple" >Cable Post Datas</label>
<br>

<?php

for($cablepost=1; $cablepost<=5; $cablepost++){
	
			if($cablepost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data</span>
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"cablepostdata".$cablepost);?>" name="cablepostdata<?php echo $cablepost;?>" class="form-control simple <?php echo $hide_this;?> ">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value</span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"cablepostvalue".$cablepost);?>" name="cablepostvalue<?php echo $cablepost;?>" class="form-control <?php echo $hide_this;?> cablepostvalue<?php echo $cablepost;?> simple fillable">
</div>

<?php
}

?>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">REQUEST ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"crequest_id");?>" name="crequest_id" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">CABLE VARIATION ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"ccvariationattr");?>" name="ccvariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">CABLE TYPE ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"ctypeattr");?>" name="ctypeattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">IUC NUMBER ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"ciucattr");?>" name="ciucattr" class="form-control">
</div>


<label>Success/Status Attribute</label>
<br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cablesuccesscode");?>" name="cablesuccesscode" placeholder="success value e.g success or status" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cablesuccessvalue");?>" name="cablesuccessvalue" placeholder="success value" class="form-control"> 
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cablesuccessvalue2");?>" name="cablesuccessvalue2" placeholder="Alternative success value" class="form-control"> 
</div>
<div class="input-group mb-3 visually-hidden">
<span class="input-group-text">AMOUNT ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cableamountattribute");?>" name="cableamountattribute" placeholder="enter your amount attribute" class="form-control">
</div>
<br>

<?php
for($j=0; $j<=3; $j++){
?>

<div class="input-group mb-3">
<span class="input-group-text">Cable Name</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cablename".$j);?>" name="cablename<?php echo $j;?>" readOnly class="form-control">
<span class="input-group-text">Cable Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cableid".$j);?>" name="cableid<?php echo $j;?>" placeholder="e.g gotv" class="form-control"><br>
</div>

<?php
}
?>

</div>
<label class="simple">Cable Plans</label>
<br>

<?php
for($i=0; $i<=35; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"ccable".$i);?>"  name="ccable<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"ccablen".$i);?>"  name="ccablen<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"ccablep".$i);?>"  name="ccablep<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">
</div>

<?php
}
?>

<div class="input-group mb-2">
<span class="input-group-text simple" id="basic-addon1">Cable Charge <?php echo $symbol;?></span>
<input type="text" name="cable_charge" value="<?php echo vp_option_array($option_array,"cable_charge");?>" class="simple fillable form-control" style="border: 3px solid green;">
</div>


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