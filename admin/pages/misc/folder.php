<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

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


<?php

vp_addoption("vp_folder_scan","none");
vp_addoption("vp_content_folder_scan",0);
vp_addoption("vp_content_plugin_scan",0);
vp_addoption("vp_content_vtupress_scan",0);
vp_addoption("vp_content_vend_scan",0);

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
                  <h5 class="card-title">Folder/FIle Scan</h5> 
<div class="table-responsive">
<div class="p-4">

             <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
                Please do not scan too often. Once or twice in a week is nice!    
            </div>
           </div>

           <!--/////////////////////ROW//////////////////////-->
            <div class="row">
            <div class="col justify-content-between">
            <div class="progress h-25">
            <?php
            if(vp_getoption("vp_folder_scan") == "none"){
                ?>
                    <div class="progress-bar pt-2 pb-2 progress-bar-animated  bg bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="<?php echo $lev;?>" aria-valuemin="0" aria-valuemax="100">0%</div>
                <?php
            }
            else{
                $lev = vp_getoption("vp_folder_scan");
                if($lev <= 25){
                    $color = "bg bg-danger";
                }
                elseif($lev <= 50){
                    $color = "bg bg-warning";
                }
                elseif($lev <= 80){
                    $color = "bg bg-info";
                }
                else{
                    $color = "bg bg-success";
                }

                ?>
                    <div class="progress-bar  pt-2 pb-2   progress-bar-animated <?php echo  $color;?> " role="progressbar" style="width: <?php echo $lev;?>%;  height:25px;" aria-valuenow="<?php echo $lev;?>" aria-valuemin="0" aria-valuemax="100"><?php echo $lev;?>%</div>
                <?php
            }
            ?>
         </div>
         <div class="text-center mt-3">
            <button class="btn btn-success text-white" onclick="scannow()">
            <span class="on-scan ">
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            Scanning... Please Wait
          </span>
            <span class="b4-scan">
            <i class="mdi pe-2 mdi-account-search"></i>Scan Now
            </span>
          
          
          </button>
        </div>
        </div><!--{Progress}-->
        </div><!--{Col}-->
        </div><!--{Row}-->
                   <!--/////////////////////ROW//////////////////////-->

                   <div class="row">
            <div class="col text-center">
                  <div>
                      <label >Content Folder: 
                        <?php
                        if( vp_getoption("vp_content_folder_scan") == "1"){
                          ?>
                            <i class="mdi mdi-check-circle text-success"></i>
                          <?php
                        }
                        else{
                          ?>
                          <i class=" fas fa-times-circle text-danger"></i>
                          <?php
                        }
                        ?>
                      
                    
                    </label>
                    </div>
                  <div>
                      <label >Plugin Folder: 
                      <?php
                        if( vp_getoption("vp_content_plugin_scan") == "1"){
                          ?>
                            <i class="mdi mdi-check-circle text-success"></i>
                          <?php
                        }
                        else{
                          ?>
                          <i class=" fas fa-times-circle text-danger"></i>
                          <?php
                        }
                        ?>

                      </label>
                  </div>
                  <div>
                      <label >Vtupress Folder:
                      <?php
                        if( vp_getoption("vp_content_vtupress_scan") == "1"){
                          ?>
                            <i class="mdi mdi-check-circle text-success"></i>
                          <?php
                        }
                        else{
                          ?>
                          <i class=" fas fa-times-circle text-danger"></i>
                          <?php
                        }
                        ?>
                         </label>
                  </div>
                  <div>
                      <label >Files: 
                      <?php
                        if( vp_getoption("vp_content_vend_scan") == "1"){
                          ?>
                            <i class="mdi mdi-check-circle text-success"></i>
                          <?php
                        }
                        else{
                          ?>
                          <i class=" fas fa-times-circle text-danger"></i>
                          <?php
                        }
                        ?>
                         </label>
                  </div>
                  <?php
              if( vp_getoption("vp_content_vend_scan") != "1" ||   vp_getoption("vp_content_vtupress_scan") != "1" ||  vp_getoption("vp_content_plugin_scan") != "1" ||  vp_getoption("vp_content_folder_scan") != "1"){
                    ?>    
                  <div>
            <button class="btn btn-success text-white" onclick="fixnow()">
            <span class="fon-scan ">
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            Fixing... Please Wait
          </span>
            <span class="fb4-scan">
            <i class="mdi pe-2 mdi-account-search"></i>Fix Now
            </span>
          </button>
               </div>
               <?php
             }
                ?>    
            
             </div><!-- {col} -->
            </div><!--{Row}-->

          <script>

jQuery(".on-scan").hide();
jQuery(".fon-scan").hide();
function scannow(){
var obj = {};
obj["action"] = "scan";

jQuery(".b4-scan").hide();
jQuery(".on-scan").show();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/misc/function.php'));?>",
data : obj,
dataType: 'text',
'cache': false,
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
        if(data == "100"){

	location.reload();

	  }
	  else{
		 jQuery(".preloader").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error Changing Status",
  text: "Click Why To See Reason",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
type : 'POST'
});




}


function fixnow(){
var obj = {};
obj["action"] = "fix";

jQuery(".fb4-scan").hide();
jQuery(".fon-scan").show();

obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/misc/function.php'));?>",
data : obj,
dataType: 'text',
'cache': false,
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
        if(data == "100"){

	location.reload();

	  }
	  else{
		 jQuery(".preloader").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error Changing Status",
  text: "Click Why To See Reason",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
type : 'POST'
});




}
          </script>
</div>
</div>
</div>
</div>


</div>



</div>