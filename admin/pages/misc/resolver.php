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
               This would fix issues with database. If after resolving you still have issues with database then you might need to consult a webside/database developer.
            </div>
           </div>

           <!--/////////////////////ROW//////////////////////-->
            <div class="row">
            <div class="col">
<table class="table table-responsive table-stripped table table-bordered">
  <thead>
    <tr>
      <th scope="col" >Table</th>
      <th  scope="col" >Action</th>
    </tr>
  </thead>
  <tbody>
  <tr>
      <td>Airtime</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 sairtime bg bg-danger text-white"  onclick="fixit('fixit','sairtime')"></i><i class="mdi mdi-refresh start me-2 p-2 sairtime bg bg-success text-white" onclick="fixit('startit','sairtime')"></i></td>
    </tr>
    <tr>
      <td>Data</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 sdata bg bg-danger text-white"  onclick="fixit('fixit','sdata')"></i><i class="mdi mdi-refresh start me-2 p-2 sdata bg bg-success text-white" onclick="fixit('startit','sdata')"></i></td>
    </tr>
    <?php
    if(is_plugin_active("bcmv/bcmv.php")){
      
    ?>
    <tr>
      <td>Cable</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 scable bg bg-danger text-white"  onclick="fixit('fixit','scable')"></i><i class="mdi mdi-refresh start me-2 p-2 scable bg bg-success text-white"  onclick="fixit('startit','scable')"></i></td>
    </tr>
    <tr>
      <td>Bill</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 sbill bg bg-danger text-white"  onclick="fixit('fixit','sbill')"></i><i class="mdi mdi-refresh start me-2 p-2 sbill bg bg-success text-white"  onclick="fixit('startit','sbill')"></i></td>
    </tr>
    <?php
}
      
    ?>
        <?php
    if(is_plugin_active("vpcards/vpcards.php")){
      
    ?>
    <tr>
      <td>E-Card</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 scards bg bg-danger text-white"  onclick="fixit('fixit','scards')"></i><i class="mdi mdi-refresh start me-2 p-2 scards bg bg-success text-white"  onclick="fixit('startit','scards')"></i></td>
    </tr>
    <?php
}
      
    ?>

<?php
    if(is_plugin_active("vpepin/vpepin.php")){
      
    ?>
    <tr>
      <td>E-Pin</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 sepins bg bg-danger text-white"  onclick="fixit('fixit','sepins')"></i><i class="mdi mdi-refresh start me-2 p-2 sepins bg bg-success text-white"  onclick="fixit('startit','sepins')"></i></td>
    </tr>

    <?php
}
      
    ?>
    <?php
    if(is_plugin_active("vpsms/vpsms.php")){
      
    ?>
    <tr>
      <td>Sms</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 ssms bg bg-danger text-white"  onclick="fixit('fixit','ssms')"></i><i class="mdi mdi-refresh start me-2 p-2 ssms bg bg-success text-white"  onclick="fixit('startit','ssms')"></i></td>
    </tr>
    <?php
}
      
    ?>
        <?php
    if(strtolower(vp_getoption("resell")) == "yes"){
      
    ?>
    <tr>
      <td>Messaging System</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 vp_chat bg bg-danger text-white"  onclick="fixit('fixit','vp_chat')"></i><i class="mdi mdi-refresh start me-2 p-2 vp_chat bg bg-success text-white"  onclick="fixit('startit','vp_chat')"></i></td>
    </tr>
    <?php
    if(is_plugin_active("vpmlm/vpmlm.php")){
      
    ?>
    <tr>
      <td>Levels</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 vp_chat bg bg-danger text-white"  onclick="fixit('fixit','vp_levels')"></i></td>
    </tr>
    <?php
    }
}
      
    ?>
    <tr>
      <td>Un-Recorded Log</td>
      <td><i class="mdi mdi-screwdriver fix me-2 p-2 vp_transactions bg bg-danger text-white"  onclick="fixit('fixit','vp_transactions')"></i><i class="mdi mdi-refresh start me-2 p-2 vp_transactions bg bg-success text-white"  onclick="fixit('startit','vp_transactions')"></td>
    </tr>
    
</tbody>
</table>
        </div><!--{Progress}-->
        </div><!--{Col}-->
        </div><!--{Row}-->
                   <!--/////////////////////ROW//////////////////////-->

            
          <script>

function fixit(action,database){
var obj = {};
obj["action"] = action;
obj["database"] = database;

if(action == "fixit"){
  if(confirm("Do You Want To fix Issues With The Selected Database Table?")){

  }else{
    return;
  }
}
else{
  if(confirm("Do You Want To Restart This Database?\n All Logged Data On This Database Will Be Lost And Table Will Be Recreated!")){

}else{
  return;
}
}

jQuery("#cover-spin").show();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";


jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/misc/save/resolver.php'));?>",
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
          swal({
  title: "Successful!",
  text: "The System Has Already fix me-2 p-2ed Issues With This Database If Its Fault",
  icon: "success",
  button: "Okay",
});


	  }
    else if(data == "101"){
      swal({
  title: "Successful!",
  text: "The Database Has Been Re-Created Successfully",
  icon: "success",
  button: "Okay",
});
    }
    else if(data == "001"){
      swal({
  title: "Oops!",
  text: "Nothing To fix me-2 p-2",
  icon: "error",
  button: "Okay",
});
    }
	  else{
		 jQuery(".preloader").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error In Action",
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