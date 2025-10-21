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
<label class="form-check-label input-group-text float-start" for="flexSwitchCheckChecked">Sme Data Status</label>
<input onchange="changestatus('sme')" value="checked" class="form-check-input input-group-text h-100 sme float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"smecontrol");?>>
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


<h3>SME</h3><br>
 <div class="alert alert-danger mb-2" role="alert">
<?php echo vp_option_array($option_array,"sme_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="databaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"databaseurl");?>" name="databaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="dataendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"dataendpoint");?>" name="dataendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="datarequesttext" name="datarequesttext" value="<?php echo vp_option_array($option_array,"datarequesttext");?>" readonly class="form-control"><br>
<select name="datarequest" id="datarequest" >
<option value="<?php echo vp_option_array($option_array,"datarequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#datarequest").on("change",function(){
	jQuery("#datarequesttext").val(jQuery("#datarequest option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="dataresponsetext" name="dataresponsetext" value="<?php echo vp_option_array($option_array,"data1_response_format_text");?>" readonly class="form-control">
<select name="dataresponse" id="dataresponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"data1_response_format");?>"><?php echo vp_option_array($option_array,"data1_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#dataresponse").on("change",function(){
	jQuery("#dataresponsetext").val(jQuery("#dataresponse option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">SME Data Re-Query</span>
<input type="text" id="smequerytext" name="smequerytext" value="<?php echo vp_option_array($option_array,"smequerytext");?>" class="visually-hidden form-control">
<select name="smequerymethod" id="smequerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"smequerymethod");?>"><?php echo vp_option_array($option_array,"smequerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="smeaddendpoint" id="smeaddendpoint" class="input-group-text visually-hidden">
<option value="<?php echo vp_option_array($option_array,"smeaddendpoint");?>"><?php echo vp_option_array($option_array,"smeaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
<div class="input-group md-3">
<span class="input-group-text">SME Data Response ID</span>
<input type="text" id="smeresponse_id" name="smeresponse_id" value="<?php echo vp_option_array($option_array,"smeresponse_id");?>" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"dataaddpost");?>" class="dataaddpost2" readonly class="form-control"><br>
<select name="dataaddpost" class="dataaddpost">
<option value="<?php echo vp_option_array($option_array,"dataaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
<script>
jQuery(".dataaddpost").on("change",function(){
	jQuery(".dataaddpost2").val(jQuery(".dataaddpost option:selected").val());
});
</script>
</select>
</div>
<div class="input-group mb-3">
<span class="input-group-text">Avaialable Networks</span>
<input type="text" class="form-control" name="sme_visible_networks" value="<?php echo vp_option_array($option_array,"sme_visible_networks");?>">
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($dataheaders=1; $dataheaders<=1; $dataheaders++){
?>

<div class="input-group mb-2">
<select class="data-head" name="datahead">
<option value="<?php echo vp_option_array($option_array,"data_head");?>"><?php echo vp_option_array($option_array,"data_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="datahead<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"datahead".$dataheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="datavalue<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"datavalue".$dataheaders);?>" class="form-control smepostkey simple fillable">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
	
				if($smeaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="smeaddheaders<?php echo $smeaddheaders;?>" value="<?php echo vp_option_array($option_array,"smeaddheaders".$smeaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple smeaddheaders<?php echo $smeaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="smeaddvalue<?php echo $smeaddheaders;?>" value="<?php echo vp_option_array($option_array,"smeaddvalue".$smeaddheaders);?>" class="form-control simple <?php echo $hide_this;?>  fillable smeaddvalue<?php echo $smeaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">SME Post Datas </label>
<br>
<?php
for($datapost=1; $datapost<=8; $datapost++){
		if($datapost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $datapost;?></span>
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"datapostdata".$datapost);?>" name="datapostdata<?php echo $datapost;?>" class="simple <?php echo $hide_this;?> form-control">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value <?php echo $datapost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"datapostvalue".$datapost);?>" name="datapostvalue<?php echo $datapost;?>" class=" simple fillable <?php echo $hide_this;?>  form-control datapostvalue<?php echo $datapost;?>"><br>
</div>

<?php
}
?>

<br>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Phone Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"dataphoneattribute");?>" name="dataphoneattribute"  id="dataphoneattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"datanetworkattribute");?>" name="datanetworkattribute"  id="datanetworkattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"dataamountattribute");?>" name="dataamountattribute"  id="dataamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Data Variation Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cvariationattr");?>" name="cvariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"request_id");?>" name="request_id" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DataType Attribute</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sme_datatype");?>" name="sme_datatype" class="form-control">
</div>

</div>

<label class="simple"> USSD SETTINGS </label>
<br>
<div class="input-group mb-3">
<span class="input-group-text simple">MTN BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sme_mtn_balance");?>" name="sme_mtn_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple">GLO BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sme_glo_balance");?>" name="sme_glo_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3 ">
<span class="input-group-text simple">9MOBILE BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sme_9mobile_balance");?>" name="sme_9mobile_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple">AIRTEL BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"sme_airtel_balance");?>" name="sme_airtel_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<br>

<div class="not-simple">
<br>
<label class="form-label">Service IDs</label>
<div class="input-group mb-3">
<span class="input-group-text">MTN Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"datamtn");?>" name="datamtn" id="datamtn" class="form-control">
<span class="input-group-text">MTN Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"mtn_sme_datatype");?>" name="mtn_sme_datatype" id="mtn_sme_datatype" class="form-control">


</div>
<div class="input-group mb-3">
<span class="input-group-text">GLO Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"dataglo");?>" name="dataglo" id="dataglo" class="form-control">
<span class="input-group-text">GLO Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"glo_sme_datatype");?>" name="glo_sme_datatype" id="glo_sme_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text">9MOBILE Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"data9mobile");?>" name="data9mobile"  id="data9mobile" class="form-control">
<span class="input-group-text">9MOBILE Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"9mobile_sme_datatype");?>" name="9mobile_sme_datatype" id="9mobile_sme_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text">AIRTEL Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"dataairtel");?>" name="dataairtel"  id="dataairtel" class="form-control">
<span class="input-group-text">AIRTEL Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"airtel_sme_datatype");?>" name="airtel_sme_datatype" id="airtel_sme_datatype" class="form-control">

</div>
<br>



<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"datasuccesscode");?>" name="datasuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"datasuccessvalue");?>" name="datasuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"datasuccessvalue2");?>" name="datasuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

</div>

<label class="form-label simple">MTN DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cdata".$i);?>"  name="cdata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cdatan".$i);?>"  name="cdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"cdatap".$i);?>"  name="cdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">
</div>

<?php
}
?>

<label class="simple"> AIRTEL DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"acdata".$i);?>"  name="acdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"acdatan".$i);?>"  name="acdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"acdatap".$i);?>"  name="acdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>
<?php
}
?>

<label class="simple"> 9MOBILE DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>
<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"9cdata".$i);?>"  name="9cdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"9cdatan".$i);?>"  name="9cdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"9cdatap".$i);?>"  name="9cdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> GLO DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"gcdata".$i);?>"  name="gcdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"gcdatan".$i);?>"  name="gcdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"gcdatap".$i);?>"  name="gcdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

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