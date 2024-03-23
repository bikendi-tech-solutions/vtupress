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
<label class="form-check-label input-group-text float-start" for="flexSwitchCheckChecked">Bet Funding Status</label>
<input onchange="changestatus('bet')" value="checked" class="form-check-input input-group-text h-100 bet float-start" type="checkbox" role="switch" id="flexSwitchCheckChecked" <?php echo vp_option_array($option_array,"betcontrol");?>>
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


<h3>BET</h3><br>
 <div class="alert alert-danger mb-2" role="alert">
<?php echo vp_option_array($option_array,"bet_info");?>
</div>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">DATA BaseUrl</span>
<input type="text" id="betbaseurl" placeholder="" value="<?php echo vp_option_array($option_array,"betbaseurl");?>" name="betbaseurl" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">DATA EndPoint</span>
<input type="text" id="betendpoint" placeholder="" value="<?php echo vp_option_array($option_array,"betendpoint");?>" name="betendpoint" class="form-control">
</div>


<div class="input-group mb-3">
<span class="input-group-text">DATA Request Method</span>
<input type="text" id="betrequesttext" name="betrequesttext" value="<?php echo vp_option_array($option_array,"betrequesttext");?>" readonly class="form-control"><br>
<select name="betrequest" id="betrequest" >
<option value="<?php echo vp_option_array($option_array,"betrequest");?>">Select</option>
<option value="get">GET 1</option>
<option value="post">GET 2</option>
<option value="post">POST</option>
</select>
<script>
jQuery("#betrequest").on("change",function(){
	jQuery("#betrequesttext").val(jQuery("#betrequest option:selected").text());
});
</script>
</div>


<div class="input-group md-3">
<span class="input-group-text">Data Response Method</span>
<input type="text" id="betresponsetext" name="betresponsetext" value="<?php echo vp_option_array($option_array,"bet1_response_format_text");?>" readonly class="form-control">
<select name="betresponse" id="betresponse" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"bet1_response_format");?>"><?php echo vp_option_array($option_array,"bet1_response_format");?></option>
<option value="json">JSON</option>
<option value="plain">PLAIN</option>
</select>
<script>
jQuery("#betresponse").on("change",function(){
	jQuery("#betresponsetext").val(jQuery("#betresponse option:selected").text());
});
</script>
</div>

<div class="input-group md-3">
<span class="input-group-text">BET Data Re-Query</span>
<input type="text" id="betquerytext" name="betquerytext" value="<?php echo vp_option_array($option_array,"betquerytext");?>" class="visually-hidden form-control">
<select name="betquerymethod" id="betquerymethod" class="input-group-text">
<option value="<?php echo vp_option_array($option_array,"betquerymethod");?>"><?php echo vp_option_array($option_array,"betquerymethod");?></option>
<option value="array">ARRAY</option>
<option value="json">JSON</option>
</select>
<span class="input-group-text visually-hidden">Add ID To EndPoint?</span>
<select name="betaddendpoint" id="betaddendpoint" class="input-group-text visually-hidden">
<option value="<?php echo vp_option_array($option_array,"betaddendpoint");?>"><?php echo vp_option_array($option_array,"betaddendpoint");?></option>
<option value="yes">YES</option>
<option value="no">NO</option>
</select>
</div>
<div class="input-group md-3">
<span class="input-group-text">BET Data Response ID</span>
<input type="text" id="betresponse_id" name="betresponse_id" value="<?php echo vp_option_array($option_array,"betresponse_id");?>" class="form-control">
</div>

<div class="input-group mb-3">
<span class="input-group-text">Add Post Data To Service?</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betaddpost");?>" class="betaddpost2" readonly class="form-control"><br>
<select name="betaddpost" class="betaddpost">
<option value="<?php echo vp_option_array($option_array,"betaddpost");?>">Select</option>
<option value="yes">YES</option>
<option value="no">No</option>
<script>
jQuery(".betaddpost").on("change",function(){
	jQuery(".betaddpost2").val(jQuery(".betaddpost option:selected").val());
});
</script>
</select>
</div>

</div>

<label class="form-label simple">Header Authorization</label>
<br>

<?php
for($betheaders=1; $betheaders<=1; $betheaders++){
?>

<div class="input-group mb-2">
<select class="bet-head" name="bethead">
<option value="<?php echo vp_option_array($option_array,"bet_head");?>"><?php echo vp_option_array($option_array,"bet_head");?></option>
<option value="not_concatenated">Not Concatenated</option>
<option value="concatenated">Concatenated</option>
<option value="custom">Custom</option>
</select>
<span class="input-group-text simple">Key</span>
<input type="text" name="bethead<?php echo $betheaders;?>" value="<?php echo vp_option_array($option_array,"bethead".$betheaders);?>"  placeholder="Key" class="form-control simple"> 
<span class="input-group-text simple">Value</span>
<input placeholder="Value" type="text" name="betvalue<?php echo $betheaders;?>" value="<?php echo vp_option_array($option_array,"betvalue".$betheaders);?>" class="form-control betpostkey simple fillable">
</div>

<?php
}
?>

<br>
<label class="form-label simple">Other Headers</label>
<br>

<?php
for($betaddheaders=1; $betaddheaders<=4; $betaddheaders++){
	
				if($betaddheaders > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group md-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Key</span> 	
<input type="text" name="betaddheaders<?php echo $betaddheaders;?>" value="<?php echo vp_option_array($option_array,"betaddheaders".$betaddheaders);?>"  placeholder="Key" class="form-control <?php echo $hide_this;?> simple betaddheaders<?php echo $betaddheaders;?>">
<span class="input-group-text simple <?php echo $hide_this;?> ">Value</span> 
<input placeholder="Value" type="text" name="betaddvalue<?php echo $betaddheaders;?>" value="<?php echo vp_option_array($option_array,"betaddvalue".$betaddheaders);?>" class="form-control simple <?php echo $hide_this;?>  fillable betaddvalue<?php echo $betaddheaders;?>">
</div>

<?php
}
?>

<br>
<label class="form-label simple">BET Post Datas </label>
<br>
<?php
for($betpost=1; $betpost<=5; $betpost++){
		if($betpost > 2){
		$hide_this = "hide-data";
	}
	else{
		
		$hide_this = "show-data";
	}
?>

<div class="input-group mb-3 <?php echo $hide_this;?>">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Data <?php echo $betpost;?></span>
<input type="text" placeholder="bet" value="<?php echo vp_option_array($option_array,"betpostdata".$betpost);?>" name="betpostdata<?php echo $betpost;?>" class="simple <?php echo $hide_this;?> form-control">
<span class="input-group-text simple <?php echo $hide_this;?>">Post Value <?php echo $betpost;?></span>
<input type="text" placeholder="Value" value="<?php echo vp_option_array($option_array,"betpostvalue".$betpost);?>" name="betpostvalue<?php echo $betpost;?>" class=" simple fillable <?php echo $hide_this;?>  form-control betpostvalue<?php echo $betpost;?>"><br>
</div>

<?php
}
?>

<br>

<div class="not-simple">

<div class="input-group mb-3">
<span class="input-group-text">Customer ID Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betcustomeridattribute");?>" name="betcustomeridattribute"  id="betcustomeridattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Betting Company Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betcompanyattribute");?>" name="betcompanyattribute"  id="betcompanyattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Amount Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betamountattribute");?>" name="betamountattribute"  id="betamountattribute" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Betting Charge NGN </span>
<input type="number" value="<?php echo vp_option_array($option_array,"betcharge");?>" name="betcharge"  id="betcharge" class="form-control">
</div>
<div class="input-group mb-3">
<span class="input-group-text">Request Attribute/Id</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betrequest_id");?>" name="betrequest_id" class="form-control">
</div>

</div>

<br>

<label class="form-label">Success/Status Attribute</label><br>
<div class="input-group mb-3">
<span class="input-group-text">Key</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betsuccesscode");?>" name="betsuccesscode" placeholder="success key e.g success or status or 200" class="form-control">
<span class="input-group-text">Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betsuccessvalue");?>" name="betsuccessvalue" placeholder="success value" class="form-control">
<span class="input-group-text">Alternative Value</span>
<input type="text" value="<?php echo vp_option_array($option_array,"betsuccessvalue2");?>" name="betsuccessvalue2" placeholder="Alternative success value" class="form-control">
</div>
<br>

<label class="form-label simple">Betting Companies</label><br>

<?php
for($i=0; $i<=15; $i++){
?>

<div class="input-group mb-3">
<span class="input-group-text">ID</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cbetdata".$i);?>"  name="cbetdata<?php echo $i;?>" class="form-control"> 
<span class="input-group-text simple">NAME</span>
<input type="text" value="<?php echo vp_option_array($option_array,"cbetdatan".$i);?>"  name="cbetdatan<?php echo $i;?>" class="form-control simple">
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