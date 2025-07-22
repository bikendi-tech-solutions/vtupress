<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(current_user_can("vtupress_access_license")){?>

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

?>

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
                  <h5 class="card-title">VTUPRESS LICENSE ACTIVATION
                    <?php
                    if(vp_getoption("vprun") != "block" ){
                    ?>
                  <span class="btn btn-sm btn-success rounded-circle w-20 h-20" stype="width:20px !important; height:20px !important;"></span>
                  <?php
                    }
                    else{
                  ?>
                  <span class="btn btn-sm btn-danger rounded-circle w-20 h-20"  stype="width:20px !important; height:20px !important;"></span>
                    <?php
                    }
                  ?>
                
                </h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
              
                Your URL must have been added on your vtupress account<br>
                It is adviceabe to add your url without http://, https://, www.<br>
                If your site can't be accessed without adding www. then you should add www. on your vtupress account<br>
              
                 If you have a running services prior before now then your subscription might be interupted. Kindly check your vtupress account or contact your developer
                   </div>
    </div>


<div class="form-row row">
    <div class="col-12 col-md mb-3 mb-md-1">
    <label for="id"><li class="fas fa-user pe-2"></li>User ID:</label>
      <input type="number" id="id" class="form-control id" placeholder="e.g 1219" value="<?php echo vp_getoption('vpid');?>">
    </div>
    <div class="col-12 col-md  mb-3 mb-md-1">
    <label for="key"><li class="fas fa-key pe-2"></li>Activation Key:</label>
      <input type="text" id="key" class="form-control actkey" placeholder="e.g vtu-58363874"  value="<?php echo vp_getoption('actkey');?>">
    </div>
    <div class="col-12 col-md  mb-3 mb-md-1">
    <label for="submit"><li class="fab fa-telegram-plane pe-2"></li>Action:</label>
    <?php
if(vp_getoption("vprun") == "block" ){
?>
      <input type="button" id="submit" class="form-control btn btn-success text-white" value="Activate" onclick="activate(jQuery('.id').val(),jQuery('.actkey').val())">
<?php
}
elseif(vp_getoption("vprun") == "block" && !empty(vp_getoption('vpid'))){
?>
     <input type="button" id="submit" class="form-control btn btn-primary text-white" value="Re-Activate" onclick="activate(jQuery('.id').val(),jQuery('.actkey').val())">
<?php
}
else{
?>
     <input type="button" id="submit" class="form-control btn btn-info text-white" value="De-Activate" onclick="deactivate()">
<?php
}
?>
    </div>
  </div>

<script>
function activate(id,actkey ){
var obj = {};
jQuery(".preloader").show();

obj['setactivation'] = "yea";
obj['actid'] = id;
obj['actkey'] = actkey;
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/vend.php'));?>",
  data: obj,
  dataType: "json",
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
      
      } else if (jqXHR.status == 403) {
            msg = "Access Forbidden [403].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
         else if (exception === "parsererror") {
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
        if(data.status == "100" ){
	
		  swal({
  title: "DONE",
  text: "Activated Successfully!",
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
  text: data.message,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});


}

function deactivate( ){
var obj = {};
obj['setactivation'] = "no";
obj['actid'] = "";
obj['actkey'] = "";
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery(".preloader").show();

    jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/vend.php'));?>",
  data: obj,
  dataType: "json",
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
        if(data.status == "100" ){
	
		  swal({
  title: "DONE",
  text: "Activated Successfully!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Off-Site",
  text: "Deactivated!!!",
  icon: "warning",
  button: "Okay",
}).then((value) => {
	location.reload();
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