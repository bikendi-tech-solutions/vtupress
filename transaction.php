<?php
ob_start();

function transactions(){
	global $results, $fdresults, $sdresults,$current_timestamp;
echo '
<style>
table.table th{
font-size:0.9em;
font-style:bold;
}

table.table td{
    font-size:0.7em;
    font-style:normal;
    }


.success{display:none;}
.failed{display:none;}
.dsuccess{display:none;}
.dfailed{display:none;}
'.apply_filters("vptstyle","").'
'.apply_filters("vptstyle1","").'
';
do_action("transaction_style");
do_action("transaction_style1");
echo'
</style>
<script>
function success(){
var e = document.getElementById("select");
var strUser = e.options[e.selectedIndex].value;
switch (strUser){
case "overview":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("overview").style.display = "block";
jQuery(".overview").show();
jQuery(".foverview").hide();
break;
case "airtime":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("success").style.display = "block";
'.apply_filters("vptcsairtime","").'
'.apply_filters("","").'
break;
case "data":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("dsuccess").style.display = "block";
'.apply_filters("vptcsdata","").'
'.apply_filters("vptcsdata1","").'
break;
case "wallet":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
jQuery(".twallet").show();
break;
'.apply_filters("vptscase","").'
'.apply_filters("vptscase1","").'
';
do_action("transaction_successful_case");
do_action("transaction_successful_case1");
echo'
default:alert("an error occurred");
}
}

function failed(){
var e = document.getElementById("select");
var strUser = e.options[e.selectedIndex].value;
switch (strUser){
case "overview":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("overview").style.display = "block";
jQuery(".overview").hide();
jQuery(".foverview").show();
break;
case "airtime":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("failed").style.display = "block";
'.apply_filters("vptcfairtime","").'
'.apply_filters("vptcfairtime1","").'
break;
case "data":
jQuery(".tsuccess").hide();
jQuery(".tfailed").hide();
document.getElementById("dfailed").style.display = "block";
'.apply_filters("vptcfdata","").'
'.apply_filters("vptcfdata1","").'
break;
case "wallet":
alert("Failed Wallet Transactions Are Not Recorded \n That\'s how i\'m programmed.");
break;
'.apply_filters("vptfcase","").'
'.apply_filters("vptfcase1","").'
';
do_action("transaction_failed_case");
do_action("transaction_failed_case1");
echo'
default: alert("an error occurred");
}
}
'.apply_filters("vptscript","").'
'.apply_filters("vptscript1","").'
</script>
';

echo '
<lead>Select One option and
Click One of the Buttons to Display</lead></divr>

<div class="input-group">
<select id="select">
<option value="overview">Overview</option>
<option value="wallet">Wallet</option>
<option value="airtime">Airtime</option>
<option value="data">Data</option>
'.apply_filters("vptcsel","").'
'.apply_filters("vptcsel1","").'
';
do_action("transaction_button");
echo'
</select>
'.apply_filters("vptbutton","").'
'.apply_filters("vptbutton1","").'
<divutton id="sucb" type="button" onclick="success()" class="btn btn-success">Successfull</divutton><divutton  type="button" id="failb" onclick="failed()" class="btn btn-danger">Failed</divutton>
<divutton type="button" class="btn btn-info flush_success">Flush Successful Histories</divutton>
</div>
';



echo "<div class='success tsuccess container-fluid' id='overview' >";
$enable_sch = vp_getoption("enable-schedule");
$last_query = vp_getoption("last-schedule");
$next_query = vp_getoption("next-schedule");
$current_time = date('h:i',$current_timestamp);
$cron_failed = vp_getoption("cron_failed");
$cron_successful = vp_getoption("cron_successful");
$sch_time = vp_getoption("schedule-time");
$js ='
<div class="row mt-5 overview">

	<div class="col-12 col-md mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-success text-white   p-5">
		<div class=" text-white display-3 total-wallet">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-info">
		<h5>Total Users Wallet</h5>
		</div >
		</div>
	
	
	
	</div>
	
	<div class="col-12 col-md  mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-success text-white   p-5">
		<div class=" text-white display-3 total-atransactions">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-info">
		<h5>Airtime Transactions</h5>
		</div >
		</div>
	
	
	
	</div>
	
	<div class="col-12 col-md   mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-success text-white   p-5">
		<div class="text-white display-3 total-transactions">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-info">
		<h5>Data Transactions</h5>
		</div >
		</div>
	
	
	
	</div>
	
	<div class="col-12 col-md   mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-success text-white   p-5">
		<div class=" text-white display-3 total-queries-successful">
		'.$cron_successful.'
		</div>

		</div>
		<div class="px-5 py-1 bg bg-info">
		<h5>Re-Queried</h5>
		</div >
		</div>
	
	
	
	</div>
	
</div>	
	
	
<div class="row mt-5 foverview">

	<div class="col-12 col-md mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-success text-white   p-5">
		<div class=" text-white display-3 total-wallet">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-info">
		<h5>Total Users Wallet</h5>
		</div >
		</div>
	
	
	
	</div>
	
		<div class="col-12 col-md  mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-danger text-white   p-5">
		<div class=" text-white display-3 ftotal-atransactions">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-warning">
		<h5>Airtime Transactions</h5>
		</div >
		</div>
	
	
	
	</div>
	
		<div class="col-12 col-md   mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-danger text-white   p-5">
		<div class="text-white display-3 ftotal-transactions">
		0.00
		</div>

		</div>
		<div class="px-5 py-1 bg bg-warning">
		<h5>Data Transactions</h5>
		</div >
		</div>
	
	
	
	</div>
	
		<div class="col-12 col-md   mt-3 mt-sm-1">
		<div >
		<div class=" bg bg-danger text-white   p-5">
		<div class=" text-white display-3 total-queries-failed">
		'.$cron_failed.'
		</div>

		</div>
		<div class="px-5 py-1 bg bg-warning">
		<h5>Re-Queried</h5>
		</div >
		</div>
	</div>
</div>

<!-- TRANSACTION ENDs -->
<div class="row mt-3 visually-hidden">
	<div class="col-12 border border-secondary px-2 py-2 text-md-center bg-warning text-dark">
	<p>Please selecting a lower Schedule Interval is very risky for websites with lower resources which can consume your server resources.</p>
	<p>We recommend running the query url (which can be found below) on your browser.</p>
	<p><b> [ '.get_home_url().'/wp-content/plugins/vtupress/query.php ] </b></p>
	</div>
</div>


<div class="row mt-3 visually-hidden">
	<div class="col-12 border border-secondary px-2 py-2 text-md-center">
	<h5> [TIME - '.$current_time.'] </h5>
	</div>
	<div class="col-12 border border-secondary px-2 py-2 text-md-center">
	<h5> [LAST-QUERIED: '.$last_query.'] --- Non Successful Transactions Re-Query Scheduler --- [NEXT-QUERY: '.$next_query.']</h5>
	</div>
	
	<div class="col-12 border border-secondary px-5 py-1">
		<div class="row">
			<div class="col-12 col-md">
				<div class="input-group d-flex justify-content-md-center">
					<span class="input-group-text">Enable Scheduler</span>
						<select class="enable-schedule">
						<option value="'.$enable_sch.'">'.strtoupper($enable_sch).'</option>
						<option value="yes">YES</option>
						<option value="no">NO</option>
						</select>
				</div>
			</div>
			<div class="col-12 col-md">
				<div class="input-group  justify-content-md-center">
					<span class="input-group-text">Interval</span>
						<select class="schedule-time">
						<option value="'.$sch_time.'">'.strtoupper($sch_time).' Minutes</option>>
						<option value="2">2 Minutes</option>
						<option value="5">5 Minutes</option>
						<option value="10">10 Minutes</option>
						<option value="15">15 Minutes</option>
						<option value="20">20 Minutes</option>
						<option value="25">25 Minutes</option>
						<option value="30">30 Minutes</option>
						<option value="40">40 Minutes</option>
						<option value="50">50 Minutes</option>
						<option value="60">60 Minutes</option>
						</select>
				</div>
			</div>
			
		</div>
	</div>
	
	<script>
	jQuery(".enable-schedule").on("change",function(){
		jQuery("#cover-spin").show();
			var obj = {};
			obj["enable-schedule"] = jQuery(".enable-schedule").val();
			
jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/sch.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});

	jQuery(".schedule-time").on("change",function(){
		
		jQuery("#cover-spin").show();
			var obj = {};
			obj["schedule-time"] = jQuery(".schedule-time").val();
			
jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/sch.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});



	</script>

</div>
';

echo $js;

echo "</div>";



echo "<div class='success tsuccess' id='success' >";

pagination_before("transactions","1","sairtime","results", 'WHERE status = "Successful" ');


pagination_after("transactions","1");
echo "
<div style='width:100%; overflow:auto;'>
<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
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
<th scope='col'>Status</th>
<th scope='col'>Action</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col' class=''>Response</th>
</tr>
</thead>
";
$total_transaction = 0;
foreach ($results as $result){

if($total_transaction < $result->id ){
$total_transaction = $result->id;
}
echo "
<script>
jQuery('.total-atransactions').text($total_transaction);
</script>
<tr>
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

";
if($result->status == "Approved" || $result->status == "Approve"  || $result->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$result->status."</span></td>
";
}
elseif($result->status == "Pending"  || $result->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$result->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$result->status."</span></td>
";
}

echo"


<td> 


<form class='sairservice-confirm-".$result->user_id.$result->id."'>
<input type='number' value='".$result->user_id."' class='user_id d-none visibility-hidden'>
<input type='text' value='sairtime' class='table d-none visibility-hidden'>
<input type='text' value='Reversal Of Failed Airtime With ID #".$result->id."' class='description d-none visibility-hidden'>
<select class=' status'>
<option value='select' selected>-Select-</option>
";
if($result->status == "Successful"){
	echo"
<option value='reverse'>Reverse</option>
";
}
elseif($result->status == "Pending"){
	echo"
<option value='success'>Approve</option>
<option value='reverse'>Fail</option>
";
}
else{
echo"
	<span class='btn-danger rounded shadow p-1'>Failed</span>
";
}

echo'
</select>

<script>
jQuery("form.sairservice-confirm-'.$result->user_id.$result->id.' select.status").on("change",function(){
jQuery("#cover-spin").show();
var obj = {};
obj["tune_status"] = "";
obj["status"] = jQuery("form.sairservice-confirm-'.$result->user_id.$result->id.' select.status").val();
obj["user_id"] = jQuery("form.sairservice-confirm-'.$result->user_id.$result->id.' input.user_id").val();
obj["table"] = jQuery("form.sairservice-confirm-'.$result->user_id.$result->id.' input.table").val();
obj["amount"] = "'.$result->amount.'";
obj["service_id"] = "'.$result->id.'";
obj["description"] = jQuery("form.sairservice-confirm-'.$result->user_id.$result->id.' input.description").val();

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});
</script>

</form>
';

echo"



 </td>
<td>".vp_getvalue($result->browser)."</td>
<td>".vp_getvalue($result->trans_type)."</td>
<td>".vp_getvalue($result->trans_method)."</td>
<td>".vp_getvalue($result->time_taken)."</td>
<td>".vp_getvalue($result->via)."</td>
<td>".$result->resp_log."</td>
</tr>
";}
echo "
</table>
</div>
";

echo "</div>";

echo "<div class='success tsuccess twallet' id='success' >";

pagination_before("transactions","2","vp_wallet","results");
pagination_after("transactions","2");
	
	
echo "
<div style='width:100%; overflow:auto;'>


<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
<th scope='col'>Id</th>
<th scope='col'>By</th>
<th scope='col'>To</th>
<th scope='col'>Type</th>
<th scope='col'>Amount</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>Description</th>
<th scope='col'>status</th>
<th scope='col'>User_Id</th>
<th scope='col'>time</th>
<th scope='col'>Action</th>
</tr>
</thead>
";




foreach ($results as $resultsa){
	

	
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->name."</td>
<td>".get_userdata($resultsa->user_id)->user_login."</td>
<td>".$resultsa->type."</td>
<td>".$resultsa->fund_amount."</td>
<td>".$resultsa->before_amount."</td>
<td>".$resultsa->now_amount."</td>
<td>".$resultsa->description."</td>

";
if($resultsa->status == "Approved" || $resultsa->status == "Approve"  || $resultsa->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
elseif($resultsa->status == "Pending"  || $resultsa->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$resultsa->status."</span></td>
";
}

echo"


<td>".$resultsa->user_id."</td>
<td>".$resultsa->the_time."</td>
<td>";
if(strtolower($resultsa->type) == "wallet" &&  strtolower($resultsa->type) == "withdrawal" && strtolower($resultsa->type) == "coupon"){
		echo "Not Applicable";
}
elseif(strtolower($resultsa->type) != "wallet" &&  strtolower($resultsa->type) != "withdrawal" && strtolower($resultsa->status) == "pending"){
		echo '
<form class="convert">
<input type="button" value="Approve" name="approve" class="btn-sm  approve1'.$resultsa->id.' dothis1'.$resultsa->id.' btn-success">
<input type="button" value="Failed" name="fail" class="btn-sm  fail1'.$resultsa->id.' dothis1'.$resultsa->id.' btn-danger">
</form>
<script>



jQuery(".convert input.dothis1'.$resultsa->id.'").click(function(){

jQuery("#cover-spin").show();
var obj = {};

var obj_value = jQuery(this).val();

obj["convert_to"] = obj_value;
obj["convert_id"] = "'.$resultsa->id.'";
obj["convert_user_id"] = "'.$resultsa->user_id.'";
obj["convert_amount"] = "'.$resultsa->fund_amount.'";
obj["convert_type"] = "'.$resultsa->type.'";




jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "text",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Conversion Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Conversion Wasn\'t Complete",
  text: data,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});

</script>';

	}
	else{
		echo "none";
	}

echo"</td>
</tr>
";
}
echo "
</table>
</div>
";

echo "</div>";

echo "<div class='failed tfailed' id='failed'>";

pagination_before("transactions","3","sairtime","results", 'WHERE status != "Successful"' );
pagination_after("transactions","3");
echo "
<div style='width:100%; overflow:auto;'>
<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
<th scope='col'>ID</th>
<th scope='col' class=''>Request ID</th>
<th scope='col'>Response ID</th>
<th scope='col'>Track ID</th>
<th scope='col'>User Name</th>
<th scope='col'>User Email</th>
<th scope='col'>Network</th>
<th scope='col'>Amount</th>
<th scope='col'>Phone</th>
<th scope='col'>User ID</th>
<th scope='col'>Time</th>
<th scope='col'>Status</th>
<th scope='col'>Action</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col'>Response</th>
</tr>
</thead>

";
$total_transaction = 0;
foreach ($results as $result){

if($total_transaction < $result->id ){
$total_transaction = $result->id;
}
echo "
<script>
jQuery('.ftotal-atransactions').text($total_transaction);
</script>
<tr>
<th scope='row'>".$result->id."</th>
<th scope='row'>".vp_getvalue($result->request_id)."</th>
<th scope='row'>".vp_getvalue($result->response_id)."</th>
<th scope='row'>".vp_getvalue($result->run_code)."</th>
<td>".$result->name."</td>
<td>".$result->email."</td>
<td>".$result->network."</td>
<td>".$result->amount."</td>
<td>".$result->phone."</td>
<td>".$result->user_id."</td>
<td>".$result->the_time."</td>

";
if($result->status == "Approved" || $result->status == "Approve"  || $result->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$result->status."</span></td>
";
}
elseif($result->status == "Pending"  || $result->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$result->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$result->status."</span></td>
";
}

echo"


<td>


<form class='fairservice-confirm-".$result->user_id.$result->id."'>
<input type='number' value='".$result->user_id."' class='user_id d-none visibility-hidden'>
<input type='text' value='sairtime' class='table d-none visibility-hidden'>
<input type='text' value='Reversal Of Failed Airtime With ID #".$result->id."' class='description d-none visibility-hidden'>
<select class=' status'>
<option value='select' selected>-Select-</option>
";
if($result->status == "Successful"){
	echo"
<option value='reverse'>Reverse</option>
";
}
elseif($result->status == "Pending"){
	echo"
<option value='success'>Approve</option>
<option value='reverse'>Fail</option>
";
}
else{
echo"
	<span class='btn-danger rounded shadow p-1'>Failed</span>
";
}

echo'
</select>

<script>
jQuery("form.fairservice-confirm-'.$result->user_id.$result->id.' select.status").on("change",function(){
jQuery("#cover-spin").show();
var obj = {};
obj["tune_status"] = "";
obj["status"] = jQuery("form.fairservice-confirm-'.$result->user_id.$result->id.' select.status").val();
obj["user_id"] = jQuery("form.fairservice-confirm-'.$result->user_id.$result->id.' input.user_id").val();
obj["table"] = jQuery("form.fairservice-confirm-'.$result->user_id.$result->id.' input.table").val();
obj["amount"] = "'.$result->amount.'";
obj["service_id"] = "'.$result->id.'";
obj["description"] = jQuery("form.fairservice-confirm-'.$result->user_id.$result->id.' input.description").val();

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});
</script>

</form>
';

echo"



 </td>
<td>".vp_getvalue($result->browser)."</td>
<td>".vp_getvalue($result->trans_type)."</td>
<td>".vp_getvalue($result->trans_method)."</td>
<td>".vp_getvalue($result->time_taken)."</td>
<td>".vp_getvalue($result->via)."</td>
<td>".$result->resp_log."</td>
</tr>
";}
echo "</table>
</div>
";

echo "</div>";	
//for data
echo "<div class='dsuccess tsuccess' id='dsuccess' >";

pagination_before("transactions","4","sdata","sdresults",'WHERE status = "Successful" ');
pagination_after("transactions","4");
echo "
<div style='width:100%; overflow:auto;'>
<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
<th scope='col'>ID</th>
<th scope='col' class=''>Request ID</th>
<th scope='col'>Response ID</th>
<th scope='col' class=''>Track ID</th>
<th scope='col'>User Name</th>
<th scope='col'>User Email</th>
<th scope='col'>Plan</th>
<th scope='col'>Amount</th>
<th scope='col'>Phone</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>User ID</th>
<th scope='col'>Time</th>
<th scope='col'>Status</th>
<th scope='col'>Action</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col'>Response</th>
</tr>
</thead>
";
$total_transaction = 0;
foreach ($sdresults as $sdresult){

if($total_transaction < $sdresult->id ){
$total_transaction = $sdresult->id;
}

	echo "
<script>
jQuery('.total-transactions').text($total_transaction);
</script>
<tr>
<th scope='row'>".$sdresult->id."</th>
<th scope='row'>".vp_getvalue($sdresult->request_id)."</th>
<th scope='row'>".vp_getvalue($sdresult->response_id)."</th>
<th scope='row'>".vp_getvalue($sdresult->run_code)."</th>
<td>".$sdresult->name."</td>
<td>".$sdresult->email."</td>
<td>".$sdresult->plan."</td>
<td>".$sdresult->amount."</td>
<td>".$sdresult->phone."</td>
<td>".$sdresult->bal_bf."</td>
<td>".$sdresult->bal_nw."</td>
<td>".$sdresult->user_id."</td>
<td>".$sdresult->the_time."</td>

";
if($sdresult->status == "Approved" || $sdresult->status == "Approve"  || $sdresult->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$sdresult->status."</span></td>
";
}
elseif($sdresult->status == "Pending"  || $sdresult->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$sdresult->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$sdresult->status."</span></td>
";
}

echo"


<td> 

<form class='sdataservice-confirm-".$sdresult->user_id.$sdresult->id."'>
<input type='number' value='".$sdresult->user_id."' class='user_id d-none visibility-hidden'>
<input type='text' value='sdata' class='table d-none visibility-hidden'>
<input type='text' value='Reversal Of Failed Data With ID #".$sdresult->id."' class='description d-none visibility-hidden'>
<select class=' status'>
<option value='select' selected>-Select-</option>
";
if($sdresult->status == "Successful"){
	echo"
<option value='reverse'>Reverse</option>
";
}
elseif($sdresult->status == "Pending"){
	echo"
<option value='success'>Approve</option>
<option value='reverse'>Fail</option>
";
}
else{
echo"
	<span class='btn-danger rounded shadow p-1'>Failed</span>
";
}

echo'
</select>

<script>
jQuery("form.sdataservice-confirm-'.$sdresult->user_id.$sdresult->id.' select.status").on("change",function(){
jQuery("#cover-spin").show();
var obj = {};
obj["tune_status"] = "";
obj["status"] = jQuery("form.sdataservice-confirm-'.$sdresult->user_id.$sdresult->id.' select.status").val();
obj["user_id"] = jQuery("form.sdataservice-confirm-'.$sdresult->user_id.$sdresult->id.' input.user_id").val();
obj["table"] = jQuery("form.sdataservice-confirm-'.$sdresult->user_id.$sdresult->id.' input.table").val();
obj["amount"] = "'.$sdresult->amount.'";
obj["service_id"] = "'.$sdresult->id.'";
obj["description"] = jQuery("form.sdataservice-confirm-'.$sdresult->user_id.$sdresult->id.' input.description").val();

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});
</script>

</form>
';

echo"


 </td>
<td>".vp_getvalue($sdresult->browser)."</td>
<td>".vp_getvalue($sdresult->trans_type)."</td>
<td>".vp_getvalue($sdresult->trans_method)."</td>
<td>".vp_getvalue($sdresult->time_taken)."</td>
<td>".vp_getvalue($sdresult->via)."</td>
<td>".$sdresult->resp_log."</td>
</tr>
";

}
echo "
</table>
</div>
";

echo "</div>";

echo "<div class='dfailed tfailed' id='dfailed'>";

pagination_before("transactions","5","sdata","fdresults", 'WHERE status != "Successful"');
pagination_after("transactions","5");

echo "
<div style='width:100%; overflow:auto;'>
<table class='table table-striped table-hover table-bordered table-responsive'>
<thead>
<tr>
<th scope='col'>ID</th>
<th scope='col' class=''>Request ID</th>
<th scope='col'>Response ID</th>
<th scope='col'>Track ID</th>
<th scope='col'>User Name</th>
<th scope='col'>User Email</th>
<th scope='col'>Plan</th>
<th scope='col'>Amount</th>
<th scope='col'>Phone</th>
<th scope='col'>User ID</th>
<th scope='col'>Time</th>
<th scope='col'>Status</th>
<th scope='col'>Action</th>
<th scope='col' class=''>Browser</th>
<th scope='col' class=''>T.Type</th>
<th scope='col' class=''>T.Method</th>
<th scope='col' class=''>T.Calls</th>
<th scope='col' class=''>Via</th>
<th scope='col'>Response</th>
</tr>
</thead>
";
$total_transaction = 0;
foreach ($fdresults as $fdresult){

if($total_transaction < $fdresult->id ){
$total_transaction = $fdresult->id;
}
echo "
<script>
jQuery('.ftotal-transactions').text($total_transaction);
</script>
<tr>
<th scope='row'>".$fdresult->id."</th>
<th scope='row'>".vp_getvalue($fdresult->request_id)."</th>
<th scope='row'>".vp_getvalue($fdresult->response_id)."</th>
<th scope='row'>".vp_getvalue($fdresult->run_code)."</th>
<td>".$fdresult->name."</td>
<td>".$fdresult->email."</td>
<td>".$fdresult->plan."</td>
<td>".$fdresult->amount."</td>
<td>".$fdresult->phone."</td>
<td>".$fdresult->user_id."</td>
<td>".$fdresult->the_time."</td>

";
if($fdresult->status == "Approved" || $fdresult->status == "Approve"  || $fdresult->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$fdresult->status."</span></td>
";
}
elseif($fdresult->status == "Pending"  || $fdresult->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$fdresult->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$fdresult->status."</span></td>
";
}

echo"

<td>

<form class='fdataservice-confirm-".$fdresult->user_id.$fdresult->id."'>
<input type='number' value='".$fdresult->user_id."' class='user_id d-none visibility-hidden'>
<input type='text' value='sdata' class='table d-none visibility-hidden'>
<input type='text' value='Reversal Of Failed Data With ID #".$fdresult->id."' class='description d-none visibility-hidden'>
<select class=' status'>
<option value='select' selected>-Select-</option>
";
if($fdresult->status == "Successful"){
	echo"
<option value='reverse'>Reverse</option>
";
}
elseif($fdresult->status == "Pending"){
	echo"
<option value='success'>Approve</option>
<option value='reverse'>Fail</option>
";
}
else{
echo"
	<span class='btn-danger rounded shadow p-1'>Failed</span>
";
}

echo'
</select>

<script>
jQuery("form.fdataservice-confirm-'.$fdresult->user_id.$fdresult->id.' select.status").on("change",function(){
jQuery("#cover-spin").show();
var obj = {};
obj["tune_status"] = "";
obj["status"] = jQuery("form.fdataservice-confirm-'.$fdresult->user_id.$fdresult->id.' select.status").val();
obj["user_id"] = jQuery("form.fdataservice-confirm-'.$fdresult->user_id.$fdresult->id.' input.user_id").val();
obj["table"] = jQuery("form.fdataservice-confirm-'.$fdresult->user_id.$fdresult->id.' input.table").val();
obj["amount"] = "'.$fdresult->amount.'";
obj["service_id"] = "'.$fdresult->id.'";
obj["description"] = jQuery("form.fdataservice-confirm-'.$fdresult->user_id.$fdresult->id.' input.description").val();

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	jQuery("#cover-spin").hide();
        if(data == "100" ){
	
		  swal({
  title: "Done!",
  text: "Action Completed",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	 swal({
  title: "Error",
  text: "Process Wasn\'t Completed",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

	
});
</script>

</form>
';

echo"

</td>
<td>".vp_getvalue($fdresult->browser)."</td>
<td>".vp_getvalue($fdresult->trans_type)."</td>
<td>".vp_getvalue($fdresult->trans_method)."</td>
<td>".vp_getvalue($fdresult->time_taken)."</td>
<td>".vp_getvalue($fdresult->via)."</td>
<td>".$fdresult->resp_log."</td>
</tr>
";}
echo "</table>
</div>
";

echo "</div>"; 
do_action("vpttab","");
do_action("vpttab1","");
do_action("transaction_tab","");
}




return ob_get_clean();
?>