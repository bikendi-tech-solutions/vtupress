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
<label class="form-check-label input-group-text float-start" for="flexSwitchCheckChecked">Corporate Data Status</label>
<input onchange="changestatus('corporate')" value="checked" class="input-group-text h-100 form-check-input corporate float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"corporatecontrol");?>>
</div>
</div>

<div class="form-check form-switch col d-flex justify-content-end ">
<div class="input-group">
<label class="form-check-label input-group-text" for="flexSwitchCheckChecked1">Data Status</label>
<input onchange="changestatus('data')" value="checked" class="input-group-text h-100 form-check-input data" type="checkbox" role="switch" id="flexSwitchCheckChecked1" <?php echo vp_option_array($option_array,"setdata");?>>
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




<!--//////////////////////////////////////////////CORPORATE/////////////////////////////////////-->


<div id="corporatedata">


<h3>CORPORATE</h3><br>
<div class="alert alert-info mb-2" role="alert">
<?php echo vp_option_array($option_array,"corporate_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="r2databaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"r2databaseurl");?>" name="r2databaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="r2dataendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"r2dataendpoint");?>" name="r2dataendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="r2datarequesttext" name="r2datarequesttext" value="<?php echo vp_option_array($option_array,"r2datarequesttext");?>" readonly class="form-control">
<select name="r2datarequest" id="r2datarequest" >
<option value="<?php echo vp_option_array($option_array,"r2datarequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#r2datarequest").on("change",function(){
	jQuery("#r2datarequesttext").val(jQuery("#r2datarequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="dataresponsetext3" name="dataresponsetext3" value="<?php echo vp_option_array($option_array,"data3_response_format_text");?>" readonly class="form-control">
<select name="dataresponse3" id="dataresponse3" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"data3_response_format");?>"><?php echo vp_option_array($option_array,"data3_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#dataresponse3").on("change",function(){
	jQuery("#dataresponsetext3").val(jQuery("#dataresponse3 option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text ">CORPORATE Data Re-Query</span>
<input type="text" id="corporatequerytext" name="corporatequerytext" value="<?php echo vp_option_array($option_array,"corporatequerytext");?>" class="visually-hidden form-control">
<select name="corporatequerymethod" id="corporatequerymethod" class=" input-group-text">
<option value="<?php echo vp_option_array($option_array,"corporatequerymethod");?>"><?php echo vp_option_array($option_array,"corporatequerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="corporateaddendpoint" id="corporateaddendpoint" class="visually-hidden input-group-text">
<option value="<?php echo vp_option_array($option_array,"corporateaddendpoint");?>"><?php echo vp_option_array($option_array,"corporateaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group md-3">
<span class="input-group-text">CORPORATE Data Response ID</span>
<input type="text" id="corporateresponse_id" name="corporateresponse_id" value="<?php echo vp_option_array($option_array,"corporateresponse_id");?>" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2dataaddpost");?>" class="r2dataaddpost2" readOnly class="form-control"><br>
<select name="r2dataaddpost" class="r2dataaddpost">
<option value="<?php echo vp_option_array($option_array,"r2dataaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
<script>
jQuery(".r2dataaddpost").on("change",function(){
	jQuery(".r2dataaddpost2").val(jQuery(".r2dataaddpost option:selected").val());
});
</script>
</div>
<div class="input-group mb-3">
<span class="input-group-text">Avaialable Networks</span>
<input type="text" class="form-control" name="corporate_visible_networks" value="<?php echo vp_option_array($option_array,"corporate_visible_networks");?>">
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($dataheaders=1; $dataheaders<=1; $dataheaders++){
?>

<div class="input-group mb-2">
<select class="data-head3" name="datahead3">
<option value="<?php echo vp_option_array($option_array,"data_head3");?>"><?php echo vp_option_array($option_array,"data_head3");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="r2datahead<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"r2datahead".$dataheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="r2datavalue<?php echo $dataheaders;?>" value="<?php echo vp_option_array($option_array,"r2datavalue".$dataheaders);?>" class="form-control simple fillable corporatepostkey">
</div>
<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
	
				if($directaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="corporateaddheaders<?php echo $directaddheaders;?>" value="<?php echo vp_option_array($option_array,"corporateaddheaders".$directaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?>  simple corporateaddheaders<?php echo $directaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="corporateaddvalue<?php echo $directaddheaders;?>" value="<?php echo vp_option_array($option_array,"corporateaddvalue".$directaddheaders);?>" class="form-control <?php echo $hide_this;?> simple fillable corporateaddvalue<?php echo $directaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">C.G Post Datas</label>
<br>
<?php
for($datapost=1; $datapost<=5; $datapost++){
	
			if($datapost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $datapost;?></span>
<input type="text" placeholder="Data" value="<?php echo vp_option_array($option_array,"r2datapostdata".$datapost);?>" name="r2datapostdata<?php echo $datapost;?>" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Post Value <?php echo $datapost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"r2datapostvalue".$datapost);?>" name="r2datapostvalue<?php echo $datapost;?>" class="form-control  <?php echo $hide_this;?> simple fillable r2datapostvalue<?php echo $datapost;?>">
</div>

<?php
}
?>

<br>
<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Phone Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2dataphoneattribute");?>" name="r2dataphoneattribute"  id="r2dataphoneattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Network Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2datanetworkattribute");?>" name="r2datanetworkattribute"  id="r2datanetworkattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2dataamountattribute");?>" name="r2dataamountattribute"  id="r2dataamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Data Variation Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2cvariationattr");?>" name="r2cvariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2request_id");?>" name="r2request_id" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DataType Attribute</span>
<input type="text" value="<?php echo vp_option_array($option_array,"corporate_datatype");?>" name="corporate_datatype" class="form-control">
</div>

</div>

<label class="simple">USSD SETTINGS</label>
<br>
<div class="input-group mb-3">
<span class="input-group-text simple">MTN BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"corporate_mtn_balance");?>" name="corporate_mtn_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple">GLO BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"corporate_glo_balance");?>" name="corporate_glo_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple">9MOBILE BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"corporate_9mobile_balance");?>" name="corporate_9mobile_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>

<div class="input-group mb-3">
<span class="input-group-text simple">AIRTEL BALANCE USSD</span>
<input type="text" value="<?php echo vp_option_array($option_array,"corporate_airtel_balance");?>" name="corporate_airtel_balance" class="simple fillable form-control" style="border: 3px solid pink;">
</div>



<br>

<div class="not-simple">

<br>
<label class="form-label">Service IDs</label>
<div class="input-group mb-3">
<span class="input-group-text">MTN Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2datamtn");?>" name="r2datamtn" id="r2datamtn" class="form-control">
<span class="input-group-text">MTN Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"mtn_corporate_datatype");?>" name="mtn_corporate_datatype" id="mtn_corporate_datatype" class="form-control">

</div>
<div class="input-group mb-3">
<span class="input-group-text">GLO Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2dataglo");?>" name="r2dataglo" id="r2dataglo" class="form-control">
<span class="input-group-text">GLO Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"glo_corporate_datatype");?>" name="glo_corporate_datatype" id="glo_corporate_datatype" class="form-control">


</div>
<div class="input-group mb-3">
<span class="input-group-text">9MOBILE Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2data9mobile");?>" name="r2data9mobile"  id="r2data9mobile" class="form-control">
<span class="input-group-text">9MOBILE Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"9mobile_corporate_datatype");?>" name="9mobile_corporate_datatype" id="9mobile_corporate_datatype" class="form-control">


</div>
<div class="input-group mb-3">
<span class="input-group-text">AIRTEL Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2dataairtel");?>" name="r2dataairtel"  id="r2dataairtel" class="form-control">
<span class="input-group-text">AIRTEL Datatype Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"airtel_corporate_datatype");?>" name="airtel_corporate_datatype" id="airtel_corporate_datatype" class="form-control">


</div>
<br>



<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2datasuccesscode");?>" name="r2datasuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2datasuccessvalue");?>" name="r2datasuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2datasuccessvalue2");?>" name="r2datasuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

</div>

<label class="form-label simple">MTN DATA PLAN</label><br>

<?php
for($i=0; $i<=10; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2cdata".$i);?>"  name="r2cdata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2cdatan".$i);?>"  name="r2cdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"r2cdatap".$i);?>"  name="r2cdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>
<?php
}
?>

<label class="simple"> AIRTEL DATA PLAN</label><br>

<?php
for($i=0; $i<=10; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2acdata".$i);?>"  name="r2acdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2acdatan".$i);?>"  name="r2acdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"r2acdatap".$i);?>"  name="r2acdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> 9MOBILE DATA PLAN</label><br>

<?php
for($i=0; $i<=10; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r29cdata".$i);?>"  name="r29cdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r29cdatan".$i);?>"  name="r29cdatan<?php echo $i;?>" class="form-control simple">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"r29cdatap".$i);?>"  name="r29cdatap<?php echo $i;?>" class="form-control simple fillable" style="border: 3px solid green;">

</div>

<?php
}
?>

<label class="simple"> GLO DATA PLAN</label><br>

<?php
for($i=0; $i<=10; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2gcdata".$i);?>"  name="r2gcdata<?php echo $i;?>" class="form-control">
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"r2gcdatan".$i);?>"  name="r2gcdatan<?php echo $i;?>" class="simple form-control">
<span class="input-group-text simple">PRICE</span>
<input type="number" value="<?php echo vp_option_array($option_array,"r2gcdatap".$i);?>"  name="r2gcdatap<?php echo $i;?>" class="simple fillable form-control" style="border: 3px solid green;">

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