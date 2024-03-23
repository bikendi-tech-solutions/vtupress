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
                  <h5 class="card-title">Failed Airtime Transactions</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_history_before("sairtime","null");
}
elseif(empty($_GET["trans_id"])){
    pagination_history_before("sairtime","null");
  }
    else{
    if(is_numeric($_GET["trans_id"])){
        $id = $_GET["trans_id"];
        pagination_history_before("sairtime","null","AND id = '$id'");
    }
    else{
        $id = $_GET["trans_id"];
        pagination_history_before("sairtime","null","AND request_id = '$id'");
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
                      <th class="">
<label class="customcheckbox mb-3">
  <input type="checkbox" id="mainCheckbox"  />
  <span class="checkmark"></span>
</label>
</th>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>Request ID</th>
<th scope='col' class=''>Response ID</th>
<th scope='col' class=''>Track ID</th>
<th scope='col' class=''>User Name</th>
<th scope='col' class=''>User Email</th>
<th scope='col' class=''>Network</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Phone</th>
<th scope='col' class=''>Previous Balance</th>
<th scope='col' class=''>Current Balance</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>Time</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col' class=''>Response</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No Failed Transaction Found</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);
global $wpdb;
#GET LEVELS
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name");

$current_amt = 0;

foreach($transactions as $result){


    echo"
    <tr>
    <th class=''>
    <label class=\"customcheckbox\">
      <input type=\"checkbox\" class=\"listCheckbox\" value=\"$result->id\" amount='$result->amount' user='$result->user_id'/>
      <span class=\"checkmark\"></span>
    </label>
  </th>
    <th scope='row'>".$result->id."</th>
    <th scope='row'>".vp_getvalue($result->request_id)."</th>
    <th scope='row'>".vp_getvalue($result->response_id)."</th>
    <th scope='row'>".vp_getvalue($result->run_code)."</th>
    <td>".$result->name."</td>
    <td>".$result->email."</td>
    <td>".$result->network."</td>
    <td>".$result->amount."</td>
    <td>".$result->phone."</td>
    <td>".$result->bal_bf."</td>
    <td>".$result->bal_nw."</td>
    <td>".$result->user_id."</td>
    <td>".$result->the_time."</td>
    <td>".vp_getvalue($result->browser)."</td>
    <td>".vp_getvalue($result->trans_type)."</td>
    <td>".vp_getvalue($result->trans_method)."</td>
    <td>".vp_getvalue($result->time_taken)."</td>
    <td>".vp_getvalue($result->via)."</td>
    <td>".$result->resp_log."</td>


  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                    <th class="">
<label class="customcheckbox mb-3">
  <input type="checkbox" id="mainCheckbox"  />
  <span class="checkmark"></span>
</label>
</th>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>Request ID</th>
<th scope='col' class=''>Response ID</th>
<th scope='col' class=''>Track ID</th>
<th scope='col' class=''>User Name</th>
<th scope='col' class=''>User Email</th>
<th scope='col' class=''>Network</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Phone</th>
<th scope='col' class=''>Previous Balance</th>
<th scope='col' class=''>Current Balance</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>Time</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col' class=''>Response</th>
</tr>
                      </tfoot>
                    </table>

                    <div class="input-group">
  <span class="input-group-text">Bulk Action</span>

  <select onchange="openfunction('sairtime','Airtime',false)">
                      <option >--Select--</option>
                      <option value="delete">Delete Selected Record</option>
</select>
<?php include_once(ABSPATH."wp-content/plugins/vtupress/admin/pages/history/history.php");?>
    
</div>


<script>
function reversetransaction(db,trans_id,user_id,amount){

var obj = {};
obj['trans_id'] = trans_id;
obj['user_id'] = user_id;
obj['amount'] = amount;
obj['table'] = db;
obj['type'] = "Airtime";
obj['action'] = 'reverse';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

if(confirm("Do You Want To Reverse The Transaction With ID "+trans_id+"?") == true){
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
  text: "Transaction Refunded",
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


function aprrovetransaction(db,trans_id,user_id,amount){

var obj = {};
obj['trans_id'] = trans_id;
obj['user_id'] = user_id;
obj['amount'] = amount;
obj['table'] = db;
obj['type'] = "Airtime";
obj['action'] = 'success';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

if(confirm("Do You Want To Mark The Transaction With ID "+trans_id+" Successful?")){
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
  text: "Transaction Refunded",
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