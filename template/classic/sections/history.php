<?php	
if(isset($_GET["vend"]) && $_GET["vend"]=="history" && isset($_GET["for"])){
		echo '
		<script src="'.esc_url(plugins_url("vtupress/js/print.js")).'"></script>
		<!--History-->
		<div class="history-wrap mt-5" id="side-history-w">
		
		<!--Transaction History Head Ends-->
		
		<div class="history-content">
		
	<a href="?vend=history&for=transactions" class="btn text-decoration-none"><button class="btn btn-primary transaction-button btn-sm"> <i class="mdi mdi-history "></i> Transaction</button></a> 	<a href="?vend=history&for=wallet" class="btn text-decoration-none"><button class="btn btn-primary wallet-button btn-sm"> <i class="mdi mdi-wallet "></i>  Wallet</button></a> 	<a href="?vend=history&for=withdrawal" class="btn text-decoration-none"><button class="btn btn-primary btn-sm show-with-history"> <i class="mdi mdi-briefcase-download "></i> Withdrawal</button></a>
	<br>


		<div class="history-successful h6 font-size-table" style="text-align:center;">

		';

		if($_GET["for"] == "transactions"){
			echo'
		<div class="input-group mb-1 mt-2 trans-hist">
		<a href="?vend=history&for=transactions&type=airtime" class="pe-2 text-decoration-none"><button class="airtime-hist btn-sm btn-primary btn"> <i class="mdi mdi-cellphone "></i> >Airtime</button> </a>
		<a href="?vend=history&for=transactions&type=data" class="pe-2 text-decoration-none"><button class="data-hist btn-sm btn-primary btn"><i class="mdi mdi-wifi "></i> >Data</button></a>
		';
		if(vp_getoption("betcontrol") == "checked"){
			echo'
		<a href="?vend=history&for=transactions&type=bet" class="pe-2 text-decoration-none"><button class="data-hist btn-sm btn-primary btn"><i class="mdi mdi-wifi "></i> >Bet Funding</button></a>
		';
		}
		if(vp_getoption("setbvn") == "yes"){
			echo'
		<a href="?vend=history&for=transactions&type=verification" class="pe-2 text-decoration-none"><button class="data-hist btn-sm btn-primary btn"><i class="mdi mdi-account-search "></i> >Verification</button></a>
		';
		}
		if(is_plugin_active('bcmv/bcmv.php')){
			?>
		<a href="?vend=history&for=transactions&type=cable" class="pe-2 text-decoration-none"><button class="cable-hist btn-sm btn-primary btn"><i class="mdi mdi-television-guide "></i> >Cable</button></a>
		<a href="?vend=history&for=transactions&type=bill" class="pe-2 text-decoration-none"><button class="bill-hist btn-sm btn-primary btn"><i class="mdi  mdi-lightbulb-on-outline" ></i> >Bill</button></a>
		<?php
		}
		if(is_plugin_active('vpsms/vpsms.php')){
			?>
		<a href="?vend=history&for=transactions&type=sms" class="pe-2 text-decoration-none"><button class="sms-hist btn-sm btn-primary btn"><i class="mdi mdi-message-outline" ></i> >SMS</button></a>
		<?php
		}
		
		do_action("add_user_history_button");
		echo'
		</div>
		';
	}
	echo'
	
		</script>
		
		<!--For Transactions -->
		<div class="transaction-table">
		';
		
		do_action("add_user_history_tab");











if($_GET["for"] == "transactions"){
	if($_GET["type"] == "sms"){

		echo'
		<div id="smshist" class="thistory">
		SMS Successful History
		<br>
		';
		
		
pagination_before_front("?vend=history","sms","sms", "ssms", "resultsad", "WHERE user_id = $id");

pagination_after_front("?vend=history","sms","sms");

echo'

<div class="overflow-auto mx-3 ">
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
<th scope='col'>Amount</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>time</th>
<th scope='col'>Status</th>
<th scope='col'>Receipt</th>
</tr>
";
global $resultsad;
foreach ($resultsad as $resultsa){ 
echo "
<tr>
<td scope='row'>".$resultsa->id."</td>
<td>".$resultsa->amount."</td>
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
<td>

<button type='button' class=\"btn btn-sm btn-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  show_sms".$resultsa->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#smsexampleModal".$resultsa->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="smsexampleModal'.$resultsa->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">SMS Purchase </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="smsreceipt'.$resultsa->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>SENDER</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->sender).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>RECIPIENT</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->receiver).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->the_time).'</h5></span>
								</div>
							</div>
							

							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->status).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow  data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" id="" class="btn btn-info p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'smsreceipt'.$resultsa->id.'\');">Print</button>
                      <button type="button" name="epin_receipt" id="" class="btn btn-primary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  sms_proceed'.$resultsa->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>

jQuery(\".sms_proceed".$resultsa->id."\").on(\"click\",function(){
 var element = document.getElementById(\"smsreceipt".$resultsa->id."\");
html2pdf(element, {
  margin:       10,
  filename:     'sms.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>


</td>
</tr>
";
}
echo'</tbody>
		</table>
		
</div>


</div>
		';
		

	}
}		



################################-----AIRTIME-----#####################		
if($_GET["for"] == "transactions"){
	if($_GET["type"] == "airtime"){			

		echo'
		<div id="airtimehist" class="thistory">
		Airtime Successful History
		<br>
		';
		
		
pagination_before_front("?vend=history","airtime","air", "sairtime", "resultsad", "WHERE user_id = $id");

pagination_after_front("?vend=history","airtime","air");

echo'

<div class="overflow-auto mx-3 ">
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
<th scope='col'>Receipt</th>
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
<td>

<button type='button' class=\"btn btn-sm btn-secondary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  show_airtime".$resultsa->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#airtimeexampleModal".$resultsa->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="airtimeexampleModal'.$resultsa->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">'.strtoupper($resultsa->network).' Airtime Purchase Confirmation</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="airtimereceipt'.$resultsa->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>NETWORK</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->network).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>RECIPIENT</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->phone).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->the_time).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Amount</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->amount).'</h5></span>
								</div>
							</div>
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->status).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow  data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
                      <button type="button" id="" class="btn btn-info p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'airtimereceipt'.$resultsa->id.'\');">Print</button>
                      <button type="button" name="epin_receipt" id="" class="btn btn-primary p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  airtime_proceed'.$resultsa->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>

jQuery(\".airtime_proceed".$resultsa->id."\").on(\"click\",function(){
 var element = document.getElementById(\"airtimereceipt".$resultsa->id."\");
html2pdf(element, {
  margin:       10,
  filename:     'airtime.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>


</td>
</tr>
";
}
echo'</tbody>
		</table>
		
</div>


</div>
		';
		
	}
}

################################-----VERIFICATION-----#####################		
if($_GET["for"] == "transactions"){
	if($_GET["type"] == "verification"){	
		
		echo'

		<div id="verhist" class="thistory">
		<!--SERVICE NAME-->
		';
		
		pagination_before_front("?vend=history","verification","ver", "vp_verifications", "resultsadd", "WHERE user_id = $id");
		
		pagination_after_front("?vend=history","verification","ver");
		echo'
		
<div class="bg bg-white p-3 overflow-auto mx-3 ">
	<table class="history-successful h6 font-size verification-history d-flex justify-content-md-center table table-responsive table-hover ">
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
<th scope='col'>Type</th>
<th scope='col'>Value</th>
<th scope='col'>Charge</th>
<th scope='col'>Bal.Before</th>
<th scope='col'>Bal.Now</th>
<th scope='col'>Details</th>
<th>time</th>
</tr>
";
global $resultsadd;
foreach ($resultsadd as $resultsd){

(isset(json_decode($resultsd->vDatas)->data))? $verify_data = json_decode($resultsd->vDatas)->data :  $verify_data = "" ;

$accountImage = "data:image/jpeg;base64,".$verify_data->photo;


$id = $resultsd->id;
$type = $resultsd->type;
$value = $resultsd->value;

$fn = $verify_data->firstName;
$ln = $verify_data->lastName;
$mn = $verify_data->middleName;
$phone = $verify_data->phone;
$email = $verify_data->email;
$dob = $verify_data->birthdate;
$gender = $verify_data->gender;
$lgar = $verify_data->lgaOfResidence;
$sor = $verify_data->stateOfOrigin;
$lgao = $verify_data->lgaOfOrigin;
$ra = $verify_data->residentialAddress;

echo "
<tr>
<td scope='row'>".$resultsd->id."</td>
<td>".$resultsd->type."</td>
<td>".$resultsd->value."</td>
<td>".$resultsd->fund_amount."</td>
<td>".$resultsd->before_amount."</td>
<td>".$resultsd->now_amount."</td>
<td>
<button type='button' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   show_airtime".$resultsd->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#verexampleModal".$resultsd->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="verexampleModal'.$resultsd->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> '.strtoupper($resultsd->type).' Verification</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="verificationreceipt'.$resultsd->id.'">
								<div class="row text-white">
									<div class="mt-2 col text-white d-flex justify-content-center">
											<img src="'.$accountImage.'" width="150" height="150" />
									</div>
								</div>
								<hr>

								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>'.strtolower($type).' <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$value.'</h6>
									</div>
								</div>

								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>firstName <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$fn.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>lastName <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$ln.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>middleName <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$mn.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>d.o.b <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$dob.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>gender <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$gender.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>email <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$email.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>phone <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$phone.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>state Of Origin <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$sor.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>local gov. area (origin) <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$lgao.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>local gov. area (residential) <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$lgar.'</h6>
									</div>
								</div>
								<div class="row">
									<div class="col d-flex justify-content-start">
										<h5>residential addr. <code>*</code> : 
									</div>
									<div class="col d-flex justify-content-end">
										<h6>'.$ra.'</h6>
									</div>
								</div>

							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary  p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow   bet-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
					  <button type="button" id="" class="btn btn-info  p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'verificationreceipt'.$resultsd->id.'\');">Print</button>
                      <button type="button" name="verification_receipt" id="" class="btn btn-primary  p-2 text-xs font-bold text-black uppercase bg-indigo-600 rounded shadow   verification_proceed'.$resultsd->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>
jQuery(\".verification_proceed".$resultsd->id."\").on(\"click\",function(){

 var element = document.getElementById(\"verificationreceipt".$resultsd->id."\");
 alert('download would start shortly');
html2pdf(element, {
  margin:       10,
  filename:     'verification.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>



</td>
<td>".$resultsd->the_time."</td>



</tr>
";
}

echo'</tbody>
		</table>
		
</div>
</div>
';

	}

}

################################-----DATA-----#####################		
if($_GET["for"] == "transactions"){
	if($_GET["type"] == "data"){	
		
		echo'

		<div id="datahist" class="thistory">
		<!--SERVICE NAME-->
		';
		
		pagination_before_front("?vend=history","data","dat", "sdata", "resultsadd", "WHERE user_id = $id");
		
		pagination_after_front("?vend=history","data","dat");
		echo'
		
<div class="bg bg-white p-3 overflow-auto mx-3 ">
	<table class="history-successful h6 font-size data-history d-flex justify-content-md-center table table-responsive table-hover ">
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
<th>Receipt</th>
<th>Action</th>
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

$link = "";

if(stripos($resultsd->plan,"alpha") !== false){
	$link = "?vend=data&alpha&id=$resultsd->id";
}
elseif(stripos($resultsd->plan,"big") !== false){
	$link = "?vend=data&smile&id=$resultsd->id";
}else{
	$link = "?vend=data&id=$resultsd->id";
}

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


<td>
<button type='button' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   show_airtime".$resultsd->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#dataexampleModal".$resultsd->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="dataexampleModal'.$resultsd->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> DATA Purchase </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="datareceipt'.$resultsa->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>PLAN</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->plan).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>RECIPIENT</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->phone).'</h5></span>
								</div>
							</div>
							
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->the_time).'</h5></span>
								</div>
							</div>
							
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->status).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary  p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow   data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
					  <button type="button" id="" class="btn btn-info  p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'datareceipt'.$resultsa->id.'\');">Print</button>
                      <button type="button" name="data_receipt" id="" class="btn btn-primary  p-2 text-xs font-bold text-black uppercase bg-indigo-600 rounded shadow   data_proceed'.$resultsd->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>
jQuery(\".data_proceed".$resultsd->id."\").on(\"click\",function(){
 var element = document.getElementById(\"datareceipt".$resultsd->id."\");
 alert('download would start shortly');
html2pdf(element, {
  margin:       10,
  filename:     'data.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>



</td>
<td>

<a type='button' href='$link' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  \" >BUY AGAIN</a>

</td>


</tr>
";
}

echo'</tbody>
		</table>
		
</div>
</div>
';

	}
	elseif($_GET["type"] == "bet"){	
		
		echo'

		<div id="bethist" class="thistory">
		<!--SERVICE NAME-->
		';
		
		pagination_before_front("?vend=history","bet","dat", "sbet", "resultsadd", "WHERE user_id = $id");
		
		pagination_after_front("?vend=history","bet","dat");
		echo'
		
<div class="bg bg-white p-3 overflow-auto mx-3 ">
	<table class="history-successful h6 font-size bet-history d-flex justify-content-md-center table table-responsive table-hover ">
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
<th>Receipt</th>
<th>Action</th>
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

$link = "?vend=bet&id=$resultsd->id";


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


<td>
<button type='button' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   show_airtime".$resultsd->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#betexampleModal".$resultsd->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="betexampleModal'.$resultsd->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> Bet Funding </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="betreceipt'.$resultsa->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Company</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->company).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>RECIPIENT</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->customerid).'</h5></span>
								</div>
							</div>
							
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->the_time).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Amount</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->amount).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->status).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary  p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow   bet-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
					  <button type="button" id="" class="btn btn-info  p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'betreceipt'.$resultsa->id.'\');">Print</button>
                      <button type="button" name="bet_receipt" id="" class="btn btn-primary  p-2 text-xs font-bold text-black uppercase bg-indigo-600 rounded shadow   bet_proceed'.$resultsd->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>
jQuery(\".bet_proceed".$resultsd->id."\").on(\"click\",function(){
 var element = document.getElementById(\"betreceipt".$resultsd->id."\");
 alert('download would start shortly');
html2pdf(element, {
  margin:       10,
  filename:     'bet.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>



</td>
<td>

<a type='button' href='$link' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  \" >BUY AGAIN</a>

</td>


</tr>
";
}

echo'</tbody>
		</table>
		
</div>
</div>
';

	}
}



echo'		
		
		
		<!--End of Data History Div-->
	
<!--CABLE AND BILL BEGINNING-->
';

if(is_plugin_active('bcmv/bcmv.php')){

	################################-----CABLE-----#####################		
if($_GET["for"] == "transactions"){
	if($_GET["type"] == "cable"){	
echo'


	<div id="cablehist" class="thistory">
		Cable Successful History
		<br>
		';
		pagination_before_front("?vend=history","cable","cab", "scable", "resultsad", "WHERE user_id = $id");
		pagination_after_front("?vend=history","cable","cab");
		echo'
		<div class="overflow-auto mx-3 ">
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
<th scope='col'>Receipt</th>
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

<td>

<button type='button' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   show_cable".$resultsa->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#cableexampleModal".$resultsa->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="cableexampleModal'.$resultsa->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> CABLE Purchase</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="cablereceipt'.$resultsa->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TYPE</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->type).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>IUC NUMBER</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->iucno).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->time).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>PRODUCT ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->product_id).'</h5></span>
								</div>
							</div>
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsa->status).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary  p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow  data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
					  <button type="button" id="" class="btn btn-info  p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'cablereceipt'.$resultsa->id.'\');">Print</button>
                      <button type="button" name="cable_receipt" id="" class="btn btn-primary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   cable_proceed'.$resultsa->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>
jQuery(\".cable_proceed".$resultsa->id."\").on(\"click\",function(){
 var element = document.getElementById(\"cablereceipt".$resultsa->id."\");
html2pdf(element, {
  margin:       10,
  filename:     'cable.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>



</td>

</tr>
";
}
echo'</tbody>
		</table>
</div>	
</div>
';

	}
}
################################-----BILL-----#####################		
if($_GET["for"] == "transactions"){
	if($_GET["type"] == "bill"){	


echo'

		<div id="billhist" class="thistory">
		Successful Bill History <br>
		';
		
pagination_before_front("?vend=history","bill","bil", "sbill", "resultsadd", "WHERE user_id = $id");

pagination_after_front("?vend=history","bill","bil");
		echo'
		<div class="overflow-auto mx-3 ">
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
<th scope='col'>Amount</th>
<th scope='col'>Token</th>
<th scope='col'>Product ID </th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th>time</th>
<th>Status</th>
<th>Receipt</th>
</tr>
";
global $resultsadd;
foreach ($resultsadd as $resultsd){ 
echo "
<tr>
<td scope='row'>".$resultsd->id."</td>
<td>".$resultsd->type."</td>
<td>".$resultsd->meterno."</td>
<td>".$resultsd->amount."</td>
<td>".$resultsd->meter_token."</td>
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

<td>


<button type='button' class=\"btn btn-sm btn-secondary   p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   show_bill".$resultsd->id."\" data-bs-toggle=\"modal\" data-bs-target=\"#billexampleModal".$resultsd->id."\" data-bs-whatever='@getbootstrap'>VIEW</button>
";
echo '
            <div class="modal fade" id="billexampleModal'.$resultsd->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"> BILL Purchase </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
						<div class="container-fluid border border-secondary" id="billreceipt'.$resultsd->id.'">
								<div class="row bg bg-dark text-white">
									<div class="col bg bg-dark text-white">
										<span class=""><h3>@INVOICE</h3></span>
									</div>
								</div>
							
							
						<div class="row p-4">
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TYPE</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->type).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>METER NUMBER</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->meterno).'</h5></span>
								</div>
							</div>
							
													
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TOKEN</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->meter_token).'</h5></span>
								</div>
							</div>
							
							<div class="row bg text-dark border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>TIME</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->time).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>PRODUCT ID</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->product_id).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Status</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>'.strtoupper($resultsd->status).'</h5></span>
								</div>
							</div>
							
							<div class="row bg bg-secondary text-white border border-bottom-primary md-2">
								<div class="col">
										<span class="input-group-text1"><h5>Amount</h5></span>
								</div>
								<div class="col right">
										<span class="input-group-text1"><h5>₦'.strtoupper($resultsd->amount).'</h5></span>
								</div>
							</div>
							
						</div>
							
						
						
						</div>
		
					</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary  p-2 text-xs font-bold text-black uppercase bg-grey-600 rounded shadow   data-proceed-cancled" data-bs-dismiss="modal">Cancel</button>
					  <button type="button" id="" class="btn btn-info  p-2 text-xs font-bold text-white uppercase bg-blue-600 rounded shadow  "  onclick="printContent(\'billreceipt'.$resultsd->id.'\');">Print</button>
                      <button type="button" name="cable_receipt" id="" class="btn btn-primary  p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow   bill_proceed'.$resultsd->id.'" >Download</button>
                    </div>
                  </div>
                </div>
            </div>
';
echo"
<script>
jQuery(\".bill_proceed".$resultsd->id."\").on(\"click\",function(){
 var element = document.getElementById(\"billreceipt".$resultsd->id."\");
html2pdf(element, {
  margin:       10,
  filename:     'bill.pdf',
  image:        { type: 'jpeg', quality: 0.98 },
  html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
  jsPDF:        { unit:'mm', format: 'a4', orientation:'portrait' }
});
});

</script>




</td>
</tr>
";
}
echo'</tbody>
		</table>	

	</div>	
	</div>	
';
	}
}

echo'	
	
	
		
';
}

################################-----WITHDRAWAL-----#####################		
if($_GET["for"] == "withdrawal"){
echo'
<!-- End of cable and bill History Div-->
		
</div>		
		

		<!--End of Transaction Table DIV-->
<div class="with-history">

<label >Withdrawal History</label>
';
pagination_before_front("?vend=history","withdrawal","with", "vp_withdrawal", "resultsad", "WHERE user_id = $id");
pagination_after_front("?vend=history","withdrawal","with");
echo'
<div class="overflow-auto mx-3 ">
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
global $resultsad;
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
		</div>
		</div>

		';
		

}

################################-----WALLET-----#####################		
if($_GET["for"] == "wallet"){
echo'


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
pagination_after_front("?vend=history","wallet","wall");

echo'
<div class="overflow-auto mx-3 ">
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
global $resultsad;
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
		</div>
		</div>
		<!--End Of Wallet History Table-->
		<!--End Of Wallet History Div-->
';

}
echo'
		</div>
		';
		
echo'
		<br>
		

		
		
		</div>

		
		
		<script>
	
		
		
function printContent(areaID){
printJS({printable: areaID, type: "html", css: "'.esc_url(plugins_url("vtupress/css/bootstrap.min.css")).'"});
}
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