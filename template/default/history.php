<?php	
if(isset($_GET["vend"]) && $_GET["vend"]=="history"){
		echo '
		<!--History-->
		<div class="history-wrap" id="side-history-w">
		
		<div class="history-head">
		<div class="history-topic">
		<b class="b">Histories</b>
		</div>
		<div class="form-search">
		<form>
		<input type="number" class="history-search" placeholder="enter max. rows to display"><i class="fa fa-search"></i>
		</form>
		</div>
		</div>
		<!--Transaction History Head Ends-->
		<style>	  
		  .wallet-history{
			max-width:100%;
			overflow:auto;
		  }
		  
table.table th{
  font-size:0.9em;
  font-style:bold;
  }
  
  table.table td{
      font-size:0.7em;
      font-style:normal;
      }
		</style>
		
		<div class="history-content">
		
	<button class="btn btn-primary transaction-button btn-sm">Transaction</button><button class="btn btn-primary wallet-button btn-sm">Wallet</button><button class="btn btn-primary btn-sm show-with-history">Withdrawal History</button>
	<br>
		<div class="history-successful h6 font-size-table" style="text-align:center;">
		<div class="input-group mb-1 mt-2 trans-hist">
		<button class="airtime-hist btn-sm btn-primary btn">>Airtime</button>
		<button class="data-hist btn-sm btn-primary btn">>Data</button>
		<button class="bet-hist btn-sm btn-primary btn">>Bet Funding</button>
		';
		if(is_plugin_active('bcmv/bcmv.php')){
			?>
		<button class="cable-hist btn-sm btn-primary btn">>Cable</button>
		<button class="bill-hist btn-sm btn-primary btn">>Bill</button>
		<?php
		}
		
		do_action("add_user_history_button");
		echo'
		</div>
		
		<script>
		jQuery(document).ready(function(){
		jQuery(".thistory").hide();
		jQuery("#airtimehist").show();
		});
		
		
		jQuery(".airtime-hist").on("click",function(){
		jQuery(".thistory").hide();
		jQuery("#airtimehist").show();
		jQuery("#datahist").hide();
		jQuery("#cablehist").hide();
		jQuery("#billhist").hide();
		jQuery(".with-history").hide();
		jQuery("#bethist").hide();

		});
		
		jQuery(".data-hist").on("click",function(){
			jQuery(".thistory").hide();
			jQuery("#airtimehist").hide();
			jQuery("#datahist").show();
			jQuery("#cablehist").hide();
			jQuery("#billhist").hide();
			jQuery(".with-history").hide();
			jQuery("#bethist").hide();

		});
		
		jQuery(".bet-hist").on("click",function(){
			jQuery(".thistory").hide();
			jQuery("#airtimehist").hide();
			jQuery("#datahist").hide();
			jQuery("#bethist").show();
			jQuery("#cablehist").hide();
			jQuery("#billhist").hide();
			jQuery(".with-history").hide();
		});
		
		jQuery(".cable-hist").on("click",function(){
		jQuery(".thistory").hide();
		jQuery("#airtimehist").hide();
		jQuery("#datahist").hide();
		jQuery("#cablehist").show();
		jQuery("#billhist").hide();
		jQuery(".with-history").hide();
		jQuery("#bethist").hide();

		});
		
		jQuery(".bill-hist").on("click",function(){
		jQuery(".thistory").hide();
		jQuery("#airtimehist").hide();
		jQuery("#datahist").hide();
		jQuery("#cablehist").hide();
		jQuery("#billhist").show();
		jQuery(".with-history").hide();
		jQuery("#bethist").hide();

		});
		';
		do_action("add_user_history_script");
		echo'
		</script>
		
		<!--For Transactions -->
		<div class="transaction-table">
		';
		
		do_action("add_user_history_tab");
		echo'
		<div id="airtimehist" class="thistory">
		Airtime Successful History
		<br>
		';
		
		
pagination_before_front("?vend=history","airtime","air", "sairtime", "resultsad", "WHERE user_id = $id");

echo'
		<table class="table table-responsive table-hover history-successful h6 font-size h6 font-size">
		<tbody>
		';
		
/*global $wpdb;
$table_name = $wpdb->prefix.'sairtime';
$resultsad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY the_time DESC LIMIT %d", $id, 10));
*/


echo"
<tr>
<th scope='col'>Id</th>
<th scope='col'>Network</th>
<th scope='col'>Amount</th>
<th scope='col'>Phone</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>time</th>
<th scope='col'>Status</th>
</tr>
";
global $resultsad;
foreach ($resultsad as $resultsa){ 
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->network."</td>
<td>".$resultsa->amount."</td>
<td>".$resultsa->phone."</td>
";
if(isset($resultsa->bal_bf)){
	echo"
<td>".$resultsa->bal_bf."</td>
<td>".$resultsa->bal_nw."</td>
";
}
else{
	echo"
<td>Nill</td>
<td>Nill</td>
";	
}

echo"
<td>".$resultsa->the_time."</td>

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

</tr>
";
}
echo'</tbody>
		</table>
		';
		
pagination_after_front("?vend=history","airtime","air");	
		
		echo'
</div>
		<div id="datahist" class="thistory">
		Successful Data History <br>
		';
		
		pagination_before_front("?vend=history","data","dat", "sdata", "resultsadd", "WHERE user_id = $id");
		echo'
	<table class="history-successful h6 font-size data-history table table-responsive table-hover ">
		<tbody>
		';
		/*
global $wpdb;
$table_name = $wpdb->prefix.'sdata';
$resultsadd = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY the_time DESC LIMIT %d", $id, 10));
*/

echo "
<tr>
<th scope='col'>Id</th>
<th scope='col'>Plan</th>
<th scope='col'>Amount</th>
<th scope='col'>Phone</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th>time</th>
<th>Status</th>
</tr>
";
global $resultsadd;
foreach ($resultsadd as $resultsd){ 
echo "
<tr>
<td scope='row'>".$resultsd->id."</td>
<td>".$resultsd->plan."</td>
<td>".$resultsd->amount."</td>
<td>".$resultsd->phone."</td>
";
if(isset($resultsd->bal_bf)){
	echo"
<td>".$resultsd->bal_bf."</td>
<td>".$resultsd->bal_nw."</td>
";
}
else{
	echo"
<td>Nill</td>
<td>Nill</td>
";	
}

echo"
<td>".$resultsd->the_time."</td>
";

if($resultsd->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
elseif($resultsd->status == "Pending"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$resultsd->status."</span></td>
";
}

echo"
</tr>
";
}

echo'</tbody>
		</table>
';

pagination_after_front("?vend=history","data","dat");

echo'		
		
		</div>
		<!--End of Data History Div-->


		<div id="bethist" class="thistory">
		Successful Bet Funding History <br>
		';
		
		pagination_before_front("?vend=history","bet","bet", "sbet", "resultsadd", "WHERE user_id = $id");
		echo'
	<table class="history-successful h6 font-size bet-history table table-responsive table-hover ">
		<tbody>
		';
		/*
global $wpdb;
$table_name = $wpdb->prefix.'sbet';
$resultsadd = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY the_time DESC LIMIT %d", $id, 10));
*/

echo "
<tr>
<th scope='col'>Id</th>
<th scope='col'>Company</th>
<th scope='col'>Amount</th>
<th scope='col'>Customer ID</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th>time</th>
<th>Status</th>
</tr>
";
global $resultsadd;
foreach ($resultsadd as $resultsd){ 
echo "
<tr>
<td scope='row'>".$resultsd->id."</td>
<td>".$resultsd->company."</td>
<td>".$resultsd->amount."</td>
<td>".$resultsd->customerid."</td>
";
if(isset($resultsd->bal_bf)){
	echo"
<td>".$resultsd->bal_bf."</td>
<td>".$resultsd->bal_nw."</td>
";
}
else{
	echo"
<td>Nill</td>
<td>Nill</td>
";	
}

echo"
<td>".$resultsd->the_time."</td>
";

if($resultsd->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
elseif($resultsd->status == "Pending"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$resultsd->status."</span></td>
";
}

echo"
</tr>
";
}

echo'</tbody>
		</table>
';

pagination_after_front("?vend=history","bet","dat");

echo'		
		
		</div>
		<!--End of Bet Funding History Div-->
	
<!--CABLE AND BILL BEGINNING-->
';

if(is_plugin_active('bcmv/bcmv.php')){
echo'
	<div id="cablehist" class="thistory">
		Cable Successful History
		<br>
		';
			pagination_before_front("?vend=history","cable","cab", "scable", "resultsad", "WHERE user_id = $id");
		
		echo'
		<table class="table table-responsive table-hover history-successful h6 font-size">
		<tbody>
		';
		/*
global $wpdb;
$table_name = $wpdb->prefix.'scable';
$resultsad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY time DESC LIMIT %d", $id, 10));
*/
echo"
<tr>
<th scope='col'>Id</th>
<th scope='col'>Type</th>
<th scope='col'>Iuc No</th>
<th scope='col'>Product Id</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>time</th>
<th scope='col'>Status</th>
</tr>
";
global $resultsad;
foreach ($resultsad as $resultsa){ 
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->type."</td>
<td>".$resultsa->iucno."</td>
<td>".$resultsa->product_id."</td>
";
if(isset($resultsa->bal_bf)){
	echo"
<td>".$resultsa->bal_bf."</td>
<td>".$resultsa->bal_nw."</td>
";
}
else{
	echo"
<td>Nill</td>
<td>Nill</td>
";	
}

echo"
<td>".$resultsa->time."</td>
";

if($resultsa->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
elseif($resultsa->status == "Pending"){
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
</tr>
";
}
echo'</tbody>
		</table>
		
';

pagination_after_front("?vend=history","cable","cab");

echo'
</div>
		<div id="billhist" class="thistory">
		Successful Bill History <br>
		';
		
pagination_before_front("?vend=history","bill","bil", "sbill", "resultsadd", "WHERE user_id = $id");
		echo'
	<table class="history-successful h6 font-size data-history table table-responsive table-hover ">
		<tbody>
		';
		/*
global $wpdb;
$table_name = $wpdb->prefix.'sbill';
$resultsadd = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY time DESC LIMIT %d", $id, 10));
*/

echo "
<tr>
<th scope='col'>Id</th>
<th scope='col'>Type</th>
<th scope='col'>Meter No</th>
<th scope='col'>Product ID </th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th>time</th>
<th>Status</th>
</tr>
";
global $resultsadd;
foreach ($resultsadd as $resultsd){ 
echo "
<tr>
<td scope='row'>".$resultsd->id."</td>
<td>".$resultsd->type."</td>
<td>".$resultsd->meterno."</td>
<td>".$resultsd->product_id."</td>

";
if(isset($resultsd->bal_bf)){
	echo"
<td>".$resultsd->bal_bf."</td>
<td>".$resultsd->bal_nw."</td>
";
}
else{
	echo"
<td>Nill</td>
<td>Nill</td>
";	
}

echo"

<td>".$resultsd->time."</td>
";

if($resultsd->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
elseif($resultsd->status == "Pending"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$resultsd->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$resultsd->status."</span></td>
";
}

echo"
</tr>
";
}
echo'</tbody>
		</table>	
		
';
pagination_after_front("?vend=history","bill","bil");
echo'	
	
		</div>
		
';
}
echo'
<!-- End of cable and bill History Div-->
		
		
		
		</div>
		<!--End of Transaction Table DIV-->
<div class="with-history">

';
pagination_before_front("?vend=history","withdrawal","with", "vp_withdrawal", "resultsad", "WHERE user_id = $id");
echo'
<table class="table table-responsive table-hover withdraw-history-table">
		<tbody>
		';
	
	echo"
<tr>
<th scope='col'>Id</th>
<th scope='col'>Name</th>
<th scope='col'>Details</th>
<th scope='col'>Amount</th>
<th scope='col'>Status</th>
<th scope='col'>time</th>
</tr>
";
foreach ($resultsad as $resultsa){ 
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->name."</td>
<td>".$resultsa->description."</td>
<td>".$resultsa->amount."</td>
";

if($resultsa->status == "Approved" || $resultsa->status == "Approve"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
elseif($resultsa->status == "Processing"){
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
<td>".$resultsa->the_time."</td>
</tr>
";
}
echo'</tbody>
		</table>
		';
		
pagination_after_front("?vend=history","withdrawal","with");

echo'

</div>
		<!--Beginning of Wallet History DIV-->
		<div class="wallet-table">
		
		<label for="wallet-history" class="wallet-history">Wallet History</label>
<style>
table.table th{
  font-size:0.9em;
  font-style:bold;
  }
  
  table.table td{
      font-size:0.7em;
      font-style:normal;
      }
</style>
<div class="wallet-history">
';

pagination_before_front("?vend=history","wallet","wall", "vp_wallet", "resultsad", "WHERE user_id = $id");

echo'
<table class="table table-striped table-hover table-bordered table-responsive">
<tbody>
';

/*
global $wpdb;
$table_name = $wpdb->prefix.'vp_wallet';
$resultsad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE user_id= %d ORDER BY the_time DESC LIMIT %d", $id, 10));
*/


echo"
<tr>
<th scope='col'>Id</th>
<th scope='col'>By</th>
<th scope='col'>Type</th>
<th scope='col'>Status</th>
<th scope='col'>Amount</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>Description</th>
<th scope='col'>time</th>
</tr>
";
foreach ($resultsad as $resultsa){ 
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->name."</td>
<td>".$resultsa->type."</td>

";
if($resultsa->status == "Approved" || $resultsa->status == "Approve"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
elseif($resultsa->status == "Pending"){
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
<td>".$resultsa->fund_amount."</td>
<td>".$resultsa->before_amount."</td>
<td>".$resultsa->now_amount."</td>
<td>".$resultsa->description."</td>
<td>".$resultsa->the_time."</td>
</tr>
";
}
echo'</tbody>
		</table>
		';
pagination_after_front("?vend=history","wallet","wall");
		
echo'
		<br>
		</div>
		<!--End Of Wallet History Table-->
		
		
		</div>
		<!--End Of Wallet History Div-->
		
		
		<script>
		
		jQuery(".wallet-table").hide();
		jQuery(".transaction-table").show();
		jQuery(".trans-hist").show();
		jQuery(".with-history").hide();
		
		jQuery(".show-with-history").on("click",function(){
		jQuery(".wallet-table").hide();
		jQuery(".transaction-table").hide();
		jQuery(".trans-hist").hide();
		jQuery(".with-history").show();
		});
		
		jQuery(".transaction-button").on("click",function(){
			jQuery(".wallet-table").hide();
			jQuery(".transaction-table").show();
			jQuery(".trans-hist").show();
			jQuery(".with-history").hide();
		});
		
		jQuery(".wallet-button").on("click",function(){
			jQuery(".wallet-table").show();
			jQuery(".transaction-table").hide();
			jQuery(".trans-hist").hide();
			jQuery(".with-history").hide();
			
		});
		
		</script>
		
		</div>
		<!--End Of Table Div With Style of text-center -->
		
		</div>
		<!--End of History Content-->
		
		
		</div>
		<!--End Of All History DIV-->
		<!-- History End -->
		';
}
		
?>