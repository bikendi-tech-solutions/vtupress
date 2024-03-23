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
                  <h5 class="card-title">Failed Airtime Conversion</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_conversion_before("null");
}
elseif(empty($_GET["trans_id"])){
    pagination_conversion_before("null");
}
else{
    if(is_numeric($_GET["trans_id"])){
        $id = $_GET["trans_id"];
        pagination_conversion_before("null","AND id = '$id'");
    }
    else{
        pagination_conversion_before("null");
    }
}


?>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                      <tr>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>Name</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Details</th>
<th scope='col' class=''>User Id</th>
<th scope='col' class=''>Time</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="6">No Conversion Marked Failed</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);



foreach($transactions as $result){


    echo"
    <tr>
    <th scope='row'>".$result->id."</th>
    <td>".get_userdata($result->user_id)->user_login."</td>
    <td>".$result->fund_amount."</td>
    <td>".$result->description."</td>
    <td>".$result->user_id."</td>
    <td>".$result->the_time."</td>


  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>Name</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Details</th>
<th scope='col' class=''>User Id</th>
<th scope='col' class=''>Time</th>
</tr>
                      </tfoot>
                    </table>
<script>
function failwithdrawal(trans_id,user_id,amount){

var obj = {};
obj['convert_id'] = trans_id;
obj['convert_user_id'] = user_id;
obj['convert_amount'] = amount;
obj['convert_to'] = 'approve';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

if(confirm("Do You Want To Dis-Approve This Airtime Conversion With ID "+trans_id+"? \n Action Can't Be Reversed !") == true){
  jQuery(".preloader").show();
jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/history.php'));?>",
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
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Conversion Dis-Approved!",
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
  text: "Process Wasn't Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});



}
else{

    return;
}

}

function approvewithdrawal(trans_id,user_id,amount){

var obj = {};
obj['convert_id'] = trans_id;
obj['convert_user_id'] = user_id;
obj['convert_amount'] = amount;
obj['convert_to'] = 'approve';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

if(confirm("Do You Want To Approve This Airtime Conversion With ID "+trans_id+"? \n Action Can't Be Reversed !") == true){
  jQuery(".preloader").show();
jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/history.php'));?>",
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
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Conversion Approved!",
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
  text: "Process Wasn't Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});



}
else{

    return;
}

}

</script>
                  </div>
                </div>
              </div>
</div>


</div>