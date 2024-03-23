<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(vp_getoption("resell") != "yes"){
  vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
}
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
                  <h5 class="card-title">Pending Users</h5>

                  <?php

    pagination_kyc_before("WHERE status = 'ban'");


?>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Doc</th>
                        <th>Selfie</th>
                        <th>Proof</th>
                        <th>User ID</th>
                        <th>Time</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $users;
if($users == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No User Banned</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);
global $wpdb;
#GET LEVELS
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name");

$current_amt = 0;

foreach($users as $users){

  if(stripos($users->method,"Bank") !== false){
    $doc = "bvn";
    $value = vp_getuser($users->user_id,"myBvnVal",true);
  }
  elseif(stripos($users->method,"Identical") !== false){
    $doc = "nin";
    $value = vp_getuser($users->user_id,"myNinVal",true);
  }else{
    $doc = "none";
    $value = "---";
  }


    echo"
    <tr>
    <td>$users->id</td>
    <td>$users->name</td>
    <td>$value</td>
    <td>$users->method</td>
    <td><a href='$users->selfie' class='btn btn-primary'>View</a></td>
    <td><a href='$users->proof' class='btn btn-primary'>View</a></td>
    <td>$users->user_id</td>
    <td>$users->the_time</td>
  <td>
<button type='button' class='btn btn-secondary' onclick='disapprove(\"$users->user_id\",\"$doc\");' doc='$doc'>Disapprove</button>
  
  </td>
  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Value</th>
                        <th>Doc</th>
                        <th>Selfie</th>
                        <th>Proof</th>
                        <th>User ID</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                      </tfoot>
                    </table>
<script>
function disapprove(user,doc){

if(confirm("Do You Want To Disapprove This Submittion?") == true){

jQuery(".preloader").show();

var obj = {};
obj["status"] = "retry";
obj["doc"] = doc;
obj["action"] = "retry";
obj["id"] = user;
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

 
jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/template/classic/sections/kycupload.php'));?>",
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
  title: "User Disapproved!",
  text: "Thanks",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
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

}
</script>
                  </div>
                </div>
              </div>
</div>


</div>