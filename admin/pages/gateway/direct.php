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


	$vp_country = vp_country();
	$glo = $vp_country["glo"];
	$mobile = $vp_country["9mobile"];
	$mtn = $vp_country["mtn"];
	$airtel = $vp_country["airtel"];
	$bypass = $vp_country["bypass"];
	$currency = $vp_country["currency"];
	$symbol = $vp_country["symbol"];
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
<label class="form-check-label float-start input-group-text " for="flexSwitchCheckChecked">Direct Data Status</label>
<input onchange="changestatus('direct')" value="checked" class="input-group-text h-100 form-check-input direct float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"directcontrol");?>>
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



<!--///////////////////////////////////////////////////DIRECT DATA/////////////////////////////////////////////-->
<div id="directdata">

<h3>DIRECT</h3><br>
<div class="alert alert-warning mb-2" role="alert">
<?php echo vp_option_array($option_array,"direct_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="rdatabaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"rdatabaseurl");?>" name="rdatabaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="rdataendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"rdataendpoint");?>" name="rdataendpoint" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="rdatarequesttext" name="rdatarequesttext" value="<?php echo vp_option_array($option_array,"rdatarequesttext");?>" readonly class="form-control">
<select name="rdatarequest" id="rdatarequest" >
<option value="<?php echo vp_option_array($option_array,"rdatarequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#rdatarequest").on("change",function(){
	jQuery("#rdatarequesttext").val(jQuery("#rdatarequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="dataresponsetext2" name="dataresponsetext2" value="<?php echo vp_option_array($option_array,"data2_response_format_text");?>" readonly class="form-control">
<select name="dataresponse2" id="dataresponse2" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"data2_response_format");?>"><?php echo vp_option_array($option_array,"data2_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#dataresponse2").on("change",function(){
	jQuery("#dataresponsetext2").val(jQuery("#dataresponse2 option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">DIRECT Data Re-Query</span>
<input type="text" id="directquerytext" name="directquerytext" value="<?php echo vp_option_array($option_array,"directquerytext");?>" class="visually-hidden form-control">
<select name="directquerymethod" id="directquerymethod" class=" input-group-text">
<option value="<?php echo vp_option_array($option_array,"directquerymethod");?>"><?php echo vp_option_array($option_array,"directquerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="directaddendpoint" id="directaddendpoint" class="visually-hidden input-group-text">
<option value="<?php echo vp_option_array($option_array,"directaddendpoint");?>"><?php echo vp_option_array($option_array,"directaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
<div class="input-group md-3">
<span class="input-group-text">DIRECT data Response ID</span>
<input type="text" id="directresponse_id" name="directresponse_id" value="<?php echo vp_option_array($option_array,"directresponse_id");?>" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdataaddpost");?>" class="rdataaddpost2" readOnly class="form-control"><br>
<select name="rdataaddpost" class="rdataaddpost">
<option value="<?php echo vp_option_array($option_array,"rdataaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
<script>
jQuery(".rdataaddpost").on("change",function(){
	jQuery(".rdataaddpost2").val(jQuery(".rdataaddpost option:selected").val());
});
</script>
</div>
<div class="input-group mb-3">
<span class="input-group-text">Avaialable Networks</span>
<input type="text" class="form-control" name="direct_visible_networks" value="<?php echo vp_option_array($option_array,"direct_visible_networks");?>">
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($dataheaders=1; $dataheaders<=1; $dataheaders++){
?>

<div class="input-group mb-2">
<select class="data-head2" name="datahead2">
<option value="<?php echo vp_option_array($option_array,"data_head2");?>"><?php echo vp_option_array($option_array,"data_head2");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="rdatahead<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"rdatahead".$dataheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="rdatavalue<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"rdatavalue".$dataheaders);?>" class="form-control simple fillable directpostkey">
</div>

<?php
}

?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
	
				if($corporateaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="directaddheaders<?php echo $corporateaddheaders;?>" value="<?php echo vp_option_array($option_array,"directaddheaders".$corporateaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple directaddheaders<?php echo $corporateaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="directaddvalue<?php echo $corporateaddheaders;?>" value="<?php echo vp_option_array($option_array,"directaddvalue".$corporateaddheaders);?>" class="form-control <?php echo $hide_this;?> simple fillable directaddvalue<?php echo $corporateaddheaders;?>">
</div>

<?php
}
?>

<label class="form-label simple">GIFTING Post Datas</label>
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

<div class="input-group mb-3 <?php echo $hide_this;?> ">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $datapost;?></span>
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"rdatapostdata".$datapost);?>" name="rdatapostdata<?php echo $datapost;?>" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Post Value <?php echo $datapost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"rdatapostvalue".$datapost);?>" name="rdatapostvalue<?php echo $datapost;?>" class="form-control simple <?php echo $hide_this;?> fillable rdatapostvalue<?php echo $datapost;?>">
</div>

<?php
}
?>

<br>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Phone Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdataphoneattribute");?>" name="rdataphoneattribute"  id="rdataphoneattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdatanetworkattribute");?>" name="rdatanetworkattribute"  id="rdatanetworkattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdataamountattribute");?>" name="rdataamountattribute"  id="rdataamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Data Variation Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rcvariationattr");?>" name="rcvariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rrequest_id");?>" name="rrequest_id" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DataType Attribute</span>
<input type="text" value="<?php echo vp_option_array($option_array,"direct_datatype");?>" name="direct_datatype" class="form-control">
</div>

</div>

<label class="simple">USSD SETTINGS</label>
<br>
<div class="input-group mb-3">
<span class="input-group-text simple"><?php echo $mtn;?> BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"direct_mtn_balance");?>" name="direct_mtn_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple"><?php echo $glo;?> BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"direct_glo_balance");?>" name="direct_glo_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple"><?php echo $mobile;?> BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"direct_9mobile_balance");?>" name="direct_9mobile_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple"><?php echo $airtel;?> BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"direct_airtel_balance");?>" name="direct_airtel_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<br>

<div class="not-simple">

<br>
<label class="form-label">Service IDs</label>
<div class="input-group mb-3">
<span class="input-group-text"><?php echo $mtn;?> Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdatamtn");?>" name="rdatamtn" id="rdatamtn" class="form-control">
<span class="input-group-text"><?php echo $mtn;?> Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"mtn_direct_datatype");?>" name="mtn_direct_datatype" id="mtn_direct_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text"><?php echo $glo;?> Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdataglo");?>" name="rdataglo" id="rdataglo" class="form-control">
<span class="input-group-text"><?php echo $glo;?> Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"glo_direct_datatype");?>" name="glo_direct_datatype" id="glo_direct_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text"><?php echo $mobile;?> Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdata9mobile");?>" name="rdata9mobile"  id="rdata9mobile" class="form-control">
<span class="input-group-text"><?php echo $mobile;?> Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"9mobile_direct_datatype");?>" name="9mobile_direct_datatype" id="9mobile_direct_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text"><?php echo $airtel;?> Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdataairtel");?>" name="rdataairtel"  id="rdataairtel" class="form-control">
<span class="input-group-text"><?php echo $airtel;?> Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"airtel_direct_datatype");?>" name="airtel_direct_datatype" id="airtel_direct_datatype" class="form-control">

</div>
<br>


<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdatasuccesscode");?>" name="rdatasuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdatasuccessvalue");?>" name="rdatasuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rdatasuccessvalue2");?>" name="rdatasuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

</div>

<label class="form-label simple"><?php echo $mtn;?> DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rcdata".$i);?>"  name="rcdata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rcdatan".$i);?>"  name="rcdatan<?php echo $i;?>" class="form-control simple ">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"rcdatap".$i);?>"  name="rcdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> <?php echo $airtel;?> DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"racdata".$i);?>"  name="racdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"racdatan".$i);?>"  name="racdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"racdatap".$i);?>"  name="racdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> <?php echo $mobile;?> DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r9cdata".$i);?>"  name="r9cdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r9cdatan".$i);?>"  name="r9cdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"r9cdatap".$i);?>"  name="r9cdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> <?php echo $glo;?> DATA PLAN</label><br>

<?php
for($i=0; $i<=20; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rgcdata".$i);?>"  name="rgcdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"rgcdatan".$i);?>"  name="rgcdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"rgcdatap".$i);?>"  name="rgcdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

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