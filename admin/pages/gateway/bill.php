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

<div class="form-check form-switch card-title d-flex">
<div class="input-group">
<label class="form-check-label float-start input-group-text" for="flexSwitchCheckChecked">Bill Status</label>
<input onchange="changestatus('bill')" value="checked" class="form-check-input bill input-group-text h-100 float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"setbill");?>>
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



<div id="billeasyaccess" class="core-services" >

<div class="alert alert-dark mb-2" role="alert">
<?php echo vp_option_array($option_array,"bill_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Bill BaseUrl</span>
<input type="text" id="billbaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"billbaseurl");?>" name="billbaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Bill EndPoint</span>
<input type="text" id="billendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"billendpoint");?>" name="billendpoint" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Method</span>
<input type="text" id="billrequesttext" name="billrequesttext" value="<?php echo vp_option_array($option_array,"billrequesttext");?>" readonly class="form-control">
<select name="billrequest" id="billrequest">
<option value="<?php echo vp_option_array($option_array,"billrequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#billrequest").on("change",function(){
	jQuery("#billrequesttext").val(jQuery("#billrequest option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">Bill Response Method</span>
<input type="text" id="billresponsetext" name="billresponsetext" value="<?php echo vp_option_array($option_array,"bill_response_format_text");?>" readonly class="form-control">
<select name="billresponse" id="billresponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"bill_response_format");?>"><?php echo vp_option_array($option_array,"bill_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#billresponse").on("change",function(){
	jQuery("#billresponsetext").val(jQuery("#billresponse option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">BILL Re-Query</span>
<input type="text" id="billquerytext" name="billquerytext" value="<?php echo vp_option_array($option_array,"billquerytext");?>" class="visually-hidden form-control">
<select name="billquerymethod" id="billquerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"billquerymethod");?>"><?php echo vp_option_array($option_array,"billquerymethod");?></option>
<option value="get">GET 1</option>
<option value="get2">GET 2</option>
<option value="post">POST</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="billaddendpoint" id="billaddendpoint" class="visually-hidden input-group-text">
<option value="<?php echo vp_option_array($option_array,"billaddendpoint");?>"><?php echo vp_option_array($option_array,"billaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>

<div class="input-group md-3">
<span class="input-group-text">BILL Response ID</span>
<input type="text" id="billresponse_id" name="billresponse_id" value="<?php echo vp_option_array($option_array,"billresponse_id");?>" class="form-control">
</div>



<br>
<div class="input-group mb-3">
<span class="input-group-text">Add Post Datas To Bill Service?</span>
<input type="text"  value="<?php echo vp_option_array($option_array,"billaddpost");?>" readOnly class="form-control">
<select name="billaddpost">
<option value="<?php echo vp_option_array($option_array,"billaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
</select>
</div>

</div>
<br>


<label class="simple ">Header Authorization</label>
<br>
<?php
for($billheaders=1; $billheaders<=1; $billheaders++){
?>
<div class="input-group mb-3">
<select class="bill-head" name="billhead">
<option value="<?php echo vp_option_array($option_array,"bill_head");?>"><?php echo vp_option_array($option_array,"bill_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="billhead<?php echo $billheaders;?>" value="<?php echo vp_option_array($option_array,"billhead".$billheaders);?>"  placeholder="bill" class="form-control simple">
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="billvalue<?php echo $billheaders;?>" value="<?php echo vp_option_array($option_array,"billvalue".$billheaders);?>" class="form-control billpostkey simple fillable">
</div>

<?php
}

?>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
	
			if($billaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>
<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="billaddheaders<?php echo $billaddheaders;?>" value="<?php echo vp_option_array($option_array,"billaddheaders".$billaddheaders);?>"  placeholder="Key" class="form-control simple <?php echo $hide_this;?> ">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="billaddvalue<?php echo $billaddheaders;?>" value="<?php echo vp_option_array($option_array,"billaddvalue".$billaddheaders);?>" class="form-control <?php echo $hide_this;?>  billaddvalue<?php echo $billaddheaders;?> simple fillable">
</div>
<?php
}

?>
<br>
<label class="simple ">Bill Post Datas</label>
<br>

<?php
for($billpost=1; $billpost<=5; $billpost++){
	
			if($billpost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
	
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Post Data</span>
<input type="text" placeholder="bill" value="<?php echo vp_option_array($option_array,"billpostdata".$billpost);?>" name="billpostdata<?php echo $billpost;?>" class="form-control <?php echo $hide_this;?> simple">
<span class="input-group-text simple <?php echo $hide_this;?> ">Post Value</span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"billpostvalue".$billpost);?>" name="billpostvalue<?php echo $billpost;?>" class="form-control simple <?php echo $hide_this;?>  fillable billpostvalue<?php echo $billpost;?>">
</div>

<?php
}
?>
<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">REQUEST ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"brequest_id");?>" name="brequest_id" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">BILL VARIATION ATTRIBUTE/ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cbvariationattr");?>" name="cbvariationattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">BILL TYPE ATTRIBUTE/ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"btypeattr");?>" name="btypeattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">METER NUMBER ATTRIBUTE/ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cmeterattr");?>" name="cmeterattr" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">AMOUNT ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billamountattribute");?>" name="billamountattribute" placeholder="enter your amount attribute" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">METER TOKEN ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"metertoken");?>" name="metertoken" placeholder="enter your meter token key attribute" class="form-control">
</div>


<label>Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billsuccesscode");?>" name="billsuccesscode" placeholder="success value e.g success or status" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billsuccessvalue");?>" name="billsuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billsuccessvalue2");?>" name="billsuccessvalue2" placeholder="Alternative success value" class="form-control"> 
</div>
<br>

<div class="input-group mb-3 visually-hidden">
<span class="input-group-text">PHONE ATTRIBUTE</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billphoneattribute");?>" name="billphoneattribute" placeholder="enter your phone attribute" class="form-control">
</div>


<label class="simple">Disco Type</label>
<br>


<?php

for($j=0; $j<=3; $j++){
?>

<div class="input-group mb-3">
<span class="input-group-text">DISCO TYPE NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billname".$j);?>" name="billname<?php echo $j;?>" placeholder="e.g Prepaid" class="form-control">
<span class="input-group-text">DISCO TYPE ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"billid".$j);?>" name="billid<?php echo $j;?>" placeholder="e.g prepaid" class="form-control">
</div>

<?php
}
?>

<label class="simple">Discos</label>
<br>

<?php
for($i=0; $i<=35; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cbill".$i);?>"  name="cbill<?php echo $i;?>" class="form-control">
<span class="input-group-text">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cbilln".$i);?>"  name="cbilln<?php echo $i;?>" class="form-control">
</div>

<?php
}

?>

</div>


<div class="input-group mb-2">
<span class="input-group-text simple" id="basic-addon1">Bill Charge ₦</span>
<input type="text" name="bill_charge" value="<?php echo vp_option_array($option_array,"bill_charge");?>" class="simple fillable form-control" style="border: 3px solid green;">
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