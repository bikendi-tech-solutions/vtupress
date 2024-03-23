<?php

if(isset($_GET["vend"]) && $_GET["vend"]=="developer" && is_plugin_active("vprest/vprest.php") && vp_option_array($option_array,"resell") == "yes" && isset($level)){
	
if(strtolower($level[0]->developer) == "yes"){
	$id = get_current_user_id();
	$apikey = vp_getuser($id, "vr_id", true);
?>
<h1>Developers Api Documentation</h1><br>
<div class="col">
<div class="mb-3">
<h5>End Point</h5><br>
Method: [Post or Get]<br>
<?php echo esc_url(plugins_url('vprest/')); ?>
</div>
<div class="mb-3">
<h5>Authentication</h5><br>
<p>Requirements are <code>ID(<?php echo $id?> )</code> and <code>ApiKey(<?php echo $apikey;?>)</code> </p>


</div>
<div class="mb-3">
<h2>Check User Details [Get/Post]</h2><br>
Parameters:<br>
<table class="table table-responsive table-hover">
<tbody>
<tr>
<th  scope="col">Parameter</th>
<th  scope="col">Meaning</th>
<th  scope="col">Value</th>
</tr>
<tr>
<td>q</td>
<td>Query</td>
<td>user</td>
</tr>
<tr>
<td>id</td>
<td>your user id</td>
<td>20</td>
</tr>
<tr>
<td>apikey</td>
<td>your Api key</td>
<td>23403</td>
</tr>
</tbody>
</table>
<br>
Example: <?php echo esc_url(plugins_url('vprest/?q=user&id='.$id.'&apikey='.$apikey.''));?><br>

Response [JSON]: {"Status":"100","Successful":"true","Id":"20","Plan":"reseller","Balance":"13372","Referred_By":"0"}<br>

<!--https://dev.betabundles.com.ng/wp-content/plugins/vprest/?id=1&apikey=2344&q=data&phone=07049626922&amount=200&network=mtn&type=sme&dataplan=1-->
</div>

<div class="mb-3">
<h2>Airtime API DOC[Get/Post]</h2><br>
Parameters:<br>
<table>
<tbody>
<table class="table table-responsive table-hover">
<tbody>
<tr>
<th  scope="col">Parameter</th>
<th  scope="col">Meaning</th>
<th  scope="col">Value</th>
</tr>
<tr>
<td>q</td>
<td>Query</td>
<td>user</td>
</tr>
<tr>
<td>id</td>
<td>your user id</td>
<td>20</td>
</tr>
<tr>
<td>apikey</td>
<td>your Api key</td>
<td>23403</td>
</tr>
<tr>
<td>phone</td>
<td>recipient phone number</td>
<td>07049626922</td>
</tr>
<tr>
<td>network</td>
<td>Telecom Network</td>
<td>mtn/glo/airtel/9mobile</td>
</tr>
<tr>
<td>amount</td>
<td>Amount you wanna vend recipient</td>
<td>200</td>
</tr>
<tr>
<td>type</td>
<td>Airtime Type</td>
<td>vtu/share/awuf</td>
</tr>
</tbody>
</table>
<br>
Example: <?php echo esc_url(plugins_url('vprest/?q=airtime&id='.$id.'&apikey='.$apikey.'&phone=07049626922&amount=200&network=mtn&type=sme'));?><br>

Response [JSON]:
<div class="" style="overflow-x:auto;">
<?php
$obj = new stdClass;
$obj->Status = "100";
$obj->Successful = "true";
$obj->Message = "Purchase Was Successful";
$obj->Previous_Balance = 500;
$obj->Current_Balance = 300;
$obj->Amount_Charged = 200;
$obj->Type = "sme";
$obj->Receiver = "07049626922";
$obj->Network = "mtn";
echo json_encode($obj);
?>
</div>
<br>
</div>

<div class="col my-2 shadow rounded p-3">
<h5 class="font-bold code">AIRTIME</h5><br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Type</th>
<th scope='col'>Product ID</th>
<th scope='col'>Network</th>
<th scope='col'>Discount</th>
</tr>
</thead>
<tbody>
<?php

//VTU

#MTN VTU
$discount = floatval($level[0]->mtn_vtu);
$plan_network = "MTN";
$plan_type = "VTU";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"vtucontrol") == "checked" && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}




#GLO VTU
$discount = floatval($level[0]->glo_vtu);
$plan_network = "GLO";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#9MOBILE VTU
$discount = floatval($level[0]->mobile_vtu);
$plan_network = "9MOBILE";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#AIRTEL VTU
$discount = floatval($level[0]->airtel_vtu);
$plan_network = "AIRTEL";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}






//SHARE
#MTN SHARE
$discount = floatval($level[0]->mtn_share);
$plan_network = "MTN";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}




#GLO SHARE
$discount = floatval($level[0]->glo_share);
$plan_network = "GLO";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#9MOBILE SHARE
$discount = floatval($level[0]->mobile_share);
$plan_network = "9MOBILE";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#AIRTEL SHARE
$discount = floatval($level[0]->airtel_share);
$plan_network = "AIRTEL";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"sharecontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}






//AWUF
#MTN AWUF
$discount = floatval($level[0]->mtn_awuf);
$plan_network = "MTN";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"awufcontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}




#GLO AWUF
$discount = floatval($level[0]->glo_awuf);
$plan_network = "GLO";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"awufcontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#9MOBILE AWUF
$discount = floatval($level[0]->mobile_awuf);
$plan_network = "9MOBILE";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"awufcontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}





#AIRTEL AWUF
$discount = floatval($level[0]->airtel_awuf);
$plan_network = "AIRTEL";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"awufcontrol") == "checked"  && vp_option_array($option_array,"setairtime") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> $discount% </td>
</tr>
";

}




?>
</tbody>
</table>
</div>


<div class="mb-3 mt-3">
<h2>Data API DOC [Get/Post]</h2><br>
Parameters:<br>
<table>
<tbody>
<table class="table table-responsive table-hover">
<tbody>
<tr>
<th  scope="col">Parameter</th>
<th  scope="col">Meaning</th>
<th  scope="col">Value</th>
</tr>
<tr>
<td>q</td>
<td>Query</td>
<td>user</td>
</tr>
<tr>
<td>id</td>
<td>your user id</td>
<td>20</td>
</tr>
<tr>
<td>apikey</td>
<td>your Api key</td>
<td>23403</td>
</tr>
<tr>
<td>phone</td>
<td>recipient phone number</td>
<td>07049626922</td>
</tr>
<tr>
<td>network</td>
<td>Telecom Network</td>
<td>mtn/glo/airtel/9mobile</td>
</tr>
<tr>
<td>dataplan</td>
<td>The Dataplan You Wanna Buy</td>
<td>1,2,3,4....see below for list of dataplans and id</td>
</tr>
<tr>
<td>type</td>
<td>Data Type</td>
<td>sme/direct/corporate</td>
</tr>
</tbody>
</table>
<br>
Example: <?php echo esc_url(plugins_url('vprest/?id=1&apikey=234&q=data&phone=07049626922&network=mtn&type=sme&dataplan=1'));?><br>

Response [JSON]: 
<?php

$obj = new stdClass;
$obj->Status = "100";
$obj->Successful = "true";
$obj->Message = "Purchase Of MTN 1GB Was Successful";
$obj->Previous_Balance = 1000;
$obj->Current_Balance = 900;
$obj->Amount_Charged = 100;
$obj->Data_Plan = "Mtn 1GB";
$obj->Plan_Code = 1;
$obj->Data_Type = "SME";
$obj->Network = "Mtn";
$obj->Receiver = "07049626922";
echo json_encode($obj);

?>
<br>

<!--https://dev.betabundles.com.ng/wp-content/plugins/vprest/?id=1&apikey=2344&q=data&phone=07049626922&amount=200&network=mtn&type=sme&dataplan=1-->
</div>


</div>
<div class="col">
<h5>RESPONSES</h5><br>
<table class="table table-hover table-responsive">
<tbody>
<tr>
<th>Response Code</th>
<th>Response Meaning</th>
</tr>
<tr>
<td>Status:100 or Successful:true</td>
<td>Successful Query/Transaction</td>
</tr>
<tr>
<td>Status:200 or Successful:false</td>
<td>Failed Query/Transaction</td>
</tr>
<tr>
<td>Message</td>
<td>Response Message</td>
</tr>
<tr>
<td>Response</td>
<td>Response Message</td>
</tr>
</tbody>
</table>

</div>




<div class="col my-2 shadow rounded p-3">
<h5 class="font-bold code">DATA PLANS AND PRODUCT ID </h5><br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Type</th>
<th scope='col'>Product ID</th>
<th scope='col'>Network</th>
<th scope='col'>Name</th>
<th scope='col'>Amount</th>
<th scope='col'>Discount</th>
</tr>
</thead>
<tbody>
<?php

for($i = 0; $i <= 10; $i++ ){
$api = vp_option_array($option_array,"api$i");
$disamount = vp_option_array($option_array,"cdatap$i");
$discount = floatval($level[0]->mtn_sme);
$plan_network = "MTN";
$plan_type = "SME";
$plan_name = vp_option_array($option_array,"cdatan$i");
$plan = vp_option_array($option_array,"cdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"  && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}

}

for($i = 0; $i <= 10; $i++ ){
$api = vp_option_array($option_array,"aapi$i");
$plan = vp_option_array($option_array,"acdata$i");
$disamount = vp_option_array($option_array,"acdatap$i");
$discount = floatval($level[0]->airtel_sme);
$plan_network = "AIRTEL";
$plan_type = "SME";
$plan_name = vp_option_array($option_array,"acdatan$i");
$plan = vp_option_array($option_array,"acdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td>
<td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}
}


for($i = 0; $i <= 10; $i++ ){
$api = vp_option_array($option_array,"9api$i");
$plan = vp_option_array($option_array,"9cdata$i");
$disamount = vp_option_array($option_array,"9cdatap$i");
$plan_network = "9MOBILE";
$plan_type = "SME";
$discount = floatval($level[0]->mobile_sme);
$plan_name = vp_option_array($option_array,"9cdatan$i");
$plan = vp_option_array($option_array,"9cdata$i");


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}

}


for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"gapi$i");
$plan = vp_option_array($option_array,"gcdata$i");
$disamount = vp_option_array($option_array,"gcdatap$i");
$plan_name = vp_option_array($option_array,"gcdatan$i");
$plan_network = "GLO";
$plan_type = "SME";
$discount = floatval($level[0]->glo_sme);
$plan = vp_option_array($option_array,"gcdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)  && vp_option_array($option_array,"smecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}
}

//GIFTING
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"api2$i");
$disamount = vp_option_array($option_array,"rcdatap$i");
$plan_network = "MTN";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"rcdatan$i");
$plan = vp_option_array($option_array,"rcdata$i");
$discount = floatval($level[0]->mtn_gifting);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}

for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"aapi2$i");
$disamount = vp_option_array($option_array,"racdatap$i");
$plan_network = "AIRTEL";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"racdatan$i");
$plan = vp_option_array($option_array,"racdata$i");
$discount = floatval($level[0]->airtel_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}


for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"9api2$i");
$disamount = vp_option_array($option_array,"r9cdatap$i");
$plan_network = "9MOBILE";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"r9cdatan$i");
$plan = vp_option_array($option_array,"r9cdata$i");
$discount = floatval($level[0]->mobile_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}

}

for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"gapi2$i");
$disamount = vp_option_array($option_array,"rgcdatap$i");
$plan_network = "GLO";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"rgcdatan$i");
$plan = vp_option_array($option_array,"rgcdata$i");
$discount = floatval($level[0]->glo_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}

//CORPORATE
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"api3$i");
$disamount =  vp_option_array($option_array,"r2cdatap$i");
$plan_network = "MTN";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2cdatan$i");
$plan = vp_option_array($option_array,"r2cdata$i");
$discount = floatval($level[0]->mtn_corporate);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}

for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"aapi3$i");
$disamount = vp_option_array($option_array,"r2acdatap$i");
$plan_network = "AIRTEL";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2acdatan$i");
$plan = vp_option_array($option_array,"r2acdata$i");
$discount = floatval($level[0]->airtel_corporate);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}


for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"9api3$i");
$disamount = vp_option_array($option_array,"r29cdatap$i");
$plan_network = "9MOBILE";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r29cdatan$i");
$plan = vp_option_array($option_array,"r29cdata$i");
$discount = floatval($level[0]->mobile_corporate);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}


for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"gloapi3$i");
$disamount = vp_option_array($option_array,"r2gcdatap$i");
$plan_network = "GLO";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2gcdatan$i");
$plan = vp_option_array($option_array,"r2gcdata$i");
$discount = floatval($level[0]->glo_corporate);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"   && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
<td> $api </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}



?>
</tbody>
</table>
</div>
<?php
if(is_plugin_active("vprest/vprest.php") && vp_getoption("cardscontrol") == "checked"){
?>

<div class="col my-2 shadow rounded p-3">
<h2 class="font-bold code"> RECHARGE CARD API DOC  [Get/Post]</h2><br>
Request Method: [GET/POST]</br>
Endpoint : <?php echo esc_url(plugins_url('vprest/'));?><br>

Parameters : <br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Key</th>
<th scope='col'>Description</th>
<th scope='col'>Sample Value</th>
</tr>
</thead>
<tbody>
<tr>
<td>q</td>
<td>This hold the service api u want to consume</td>
<td>recharge_card</td>
</tr>
<tr>
<td>id</td>
<td>Your user id</td>
<td>1234</td>
</tr>
<tr>
<td>apikey</td>
<td>Your Api Key as seen in the pricing page or any provided page</td>
<td>akhpuwuu37jdkskjaldjskms</td>
</tr>
<tr>
<td>network</td>
<td>The network you need to purchase (in lower case).</td>
<td>mtn, glo, airtel, 9mobile</td>
</tr>
<tr>
<td>denomination</td>
<td>The Recharge_Card denomination u want to purchase.</td>
<td>100 / 200 / 500 / 1000</td>
</tr>
<tr>
<td>quantity</td>
<td>The number on quantities you want to purchase.</td>
<td>1,2,3,4,5,6,7,8,9,10,20,50,100...</td>
</tr>

</tbody>
</table>

Example : <?php echo esc_url(plugins_url('vprest/?q=recharge_card&id='.$id.'&apikey='.$apikey.'&quantity=2&network=mtn&denomination=100'));?><br>

RESPONSE CODE:<br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Key</th>
<th scope='col'>Value and Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>status</td>
<td>
	100 - Successful
	200 - Failed
</td>
</tr>
<tr>
<td>pin</td>
<td>If successful (,2224242432,24534635,64756867679,5685745475)</td>
</tr>
<tr>
<td>message</td>
<td>Status Message</td>
</tr>
</tbody>
</table>

</div>


<?php
}

if(is_plugin_active("vprest/vprest.php") && vp_getoption("datascontrol") == "checked"){
?>

<div class="col my-2 shadow rounded p-3">
<h2 class="font-bold code"> DATA CARD API DOC [Get/Post] </h2><br>
Request Method: [GET/POST]</br>
Endpoint : <?php echo esc_url(plugins_url('vprest/'));?><br>

Parameters : <br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Key</th>
<th scope='col'>Description</th>
<th scope='col'>Sample Value</th>
</tr>
</thead>
<tbody>
<tr>
<td>q</td>
<td>This hold the service api u want to consume</td>
<td>data_card</td>
</tr>
<tr>
<td>id</td>
<td>Your user id</td>
<td>1234</td>
</tr>
<tr>
<td>apikey</td>
<td>Your Api Key as seen in the pricing page or any provided page</td>
<td>akhpuwuu37jdkskjaldjskms</td>
</tr>
<tr>
<td>dataplan</td>
<td>The DataPlan [ID]</td>
<td>1 / 2 / 3 [See Dataplan Below]</td>
</tr>
<tr>
<td>quantity</td>
<td>The number on quantities you want to purchase.</td>
<td>1,2,3,4,5,6,7,8,9,10,20,50,100...</td>
</tr>

</tbody>
</table>

DataPlans : <br>
Please note that all dataplans are not always Available. Make Inquiries From the Admin
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>DataPlan ID</th>
<th scope='col'>Network</th>
<th scope='col'>Value</th>
<th scope='col'>Full Description</th>
</tr>
</thead>
<tbody>
<?php
for($i = 1; $i <= 28; $i++){

	switch($i){
		case"1":
			$network = "MTN";
			$plan = "500";
			$volume = "MB";
			$value = $network." ".$plan.$volume;
		break;
		case"2":
			$network = "MTN";
			$plan = "1";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"3":
			$network = "MTN";
			$plan = "1.5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"4":
			$network = "MTN";
			$plan = "2";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"5":
			$network = "MTN";
			$plan = "3";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"6":
			$network = "MTN";
			$plan = "5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"7":
			$network = "MTN";
			$plan = "10";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"8":
			////////////////////////////////////  GLO    /////////////////////////////////
			$network = "GLO";
			$plan = "500";
			$volume = "MB";
			$value = $network." ".$plan.$volume;
		break;
		case"9":
			$network = "GLO";
			$plan = "1";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"10":
			$network = "GLO";
			$plan = "1.5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"11":
			$network = "GLO";
			$plan = "2";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"12":
			$network = "GLO";
			$plan = "3";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"13":
			$network = "GLO";
			$plan = "5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"14":
			$network = "GLO";
			$plan = "10";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"15":
			////////////////////////////////////  AIRTEL    /////////////////////////////////
			$network = "AIRTEL";
			$plan = "500";
			$volume = "MB";
			$value = $network." ".$plan.$volume;
		break;
		case"16":
			$network = "AIRTEL";
			$plan = "1";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"17":
			$network = "AIRTEL";
			$plan = "1.5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"18":
			$network = "AIRTEL";
			$plan = "2";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"19":
			$network = "AIRTEL";
			$plan = "3";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"20":
			$network = "AIRTEL";
			$plan = "5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"21":
			$network = "AIRTEL";
			$plan = "10";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"22":
			////////////////////////////////////  9MOBILE    /////////////////////////////////
			$network = "9MOBILE";
			$plan = "500";
			$volume = "MB";
			$value = $network." ".$plan.$volume;
		break;
		case"23":
			$network = "9MOBILE";
			$plan = "1";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"24":
			$network = "9MOBILE";
			$plan = "1.5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"25":
			$network = "9MOBILE";
			$plan = "2";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"26":
			$network = "9MOBILE";
			$plan = "3";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"27":
			$network = "9MOBILE";
			$plan = "5";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		case"28":
			$network = "9MOBILE";
			$plan = "10";
			$volume = "GB";
			$value = $network." ".$plan.$volume;
		break;
		default: ;	
	}

?>
<tr>
<td><?php echo $i;?></td>
<td><?php echo $network;?></td>
<td><?php echo $plan.$volume;?></td>
<td><?php echo $value;?></td>
</tr>

<?php

}

?>

</tbody>
</table>

Example : <?php echo esc_url(plugins_url('vprest/?q=data_card&id='.$id.'&apikey='.$apikey.'&quantity=2&dataplan=1'));?><br>

RESPONSE CODE:<br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Key</th>
<th scope='col'>Value and Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>status</td>
<td>
	100 - Successful
	200 - Failed
</td>
</tr>
<tr>
<td>pin</td>
<td>If successful (,2224242432,24534635,64756867679,5685745475)</td>
</tr>
<tr>
<td>message</td>
<td>Status Message</td>
</tr>
</tbody>
</table>

</div>

<?php
}


if(is_plugin_active("vprest/vprest.php") && vp_getoption("setcable") == "checked" && is_plugin_active("bcmv/bcmv.php")){
	?>
		<div class="col my-2 shadow rounded p-3">
		<h2 class="font-bold code"> CABLE API DOC [Get/Post] </h2><br>
		Request Method: [GET/POST]</br>
		Endpoint : <?php echo esc_url(plugins_url('vprest/'));?><br>
		
		Parameters : <br>
		<table class="table table-responsive table-hover history-successful h6 font-size">
		<thead>
		<tr>
		<th scope='col'>Key</th>
		<th scope='col'>Description</th>
		<th scope='col'>Sample Value</th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td>q</td>
		<td>This hold the service api u want to consume</td>
		<td>cable</td>
		</tr>
		<tr>
		<td>id</td>
		<td>Your user id</td>
		<td>1234</td>
		</tr>
		<tr>
		<td>apikey</td>
		<td>Your Api Key as seen in the pricing page or any provided page</td>
		<td>akhpuwuu37jdkskjaldjskms</td>
		</tr>
		<tr>
		<td>type</td>
		<td>The type</td>
		<td>gotv, dstv, startimes</td>
		</tr>
		<tr>
		<td>iuc</td>
		<td>The recipient Smart Card Number.</td>
		<td>123456789093</td>
		</tr>
		<tr>
		<td>plan</td>
		<td>See Plan ID </td>
		<td>1</td>
		</tr>
		
		</tbody>
		</table>
	
		Example: <?php echo esc_url(plugins_url('vprest/?q=cable&id='.$id.'&apikey='.$apikey.''));?>&type=gotv&iuc=1212121212&plan=1<br>
	
	Cable Plans : <br>
	Please note that all cable id are not always Available. Make Inquiries From the Admin incase there is any update
	<table class="table table-responsive table-hover history-successful h6 font-size">
	<thead>
	<tr>
	<th scope='col'>Plan ID</th>
	<th scope='col'>NAME</th>
	<th scope='col'>Value</th>
	<th scope='col'>Discount %</th>
	</tr>
	</thead>
	<tbody>
	<?php
	
	for($i = 1;$i <= 15; $i++){
	
	$plan = vp_option_array($option_array,"ccable".($i-1));
	$price = vp_option_array($option_array,"ccablep".($i-1));
	$name = vp_option_array($option_array,"ccablen".($i-1));
	$discount = floatval($level[0]->cable);
	
	if(!empty($plan) && !empty($price) && !empty($name)){
	?>
	<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $name;?></td>
	<td><?php echo $price;?></td>
	<td><?php echo $discount;?></td>
	</tr>
	<?php
	}
	
	
	}
	
	?>
	</tbody>
	</table>
	
	
	
	
	</div>
	
	<?php
}

if(is_plugin_active("vprest/vprest.php") && vp_getoption("setbill") == "checked" && is_plugin_active("bcmv/bcmv.php")){
	?>
		<div class="col my-2 shadow rounded p-3">
		<h2 class="font-bold code"> BILL API DOC [Get/Post] </h2><br>
		Request Method: [GET/POST]</br>
		Endpoint : <?php echo esc_url(plugins_url('vprest/'));?><br>
		
		Parameters : <br>
		<table class="table table-responsive table-hover history-successful h6 font-size">
		<thead>
		<tr>
		<th scope='col'>Key</th>
		<th scope='col'>Description</th>
		<th scope='col'>Sample Value</th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td>q</td>
		<td>This hold the service api u want to consume</td>
		<td>bill</td>
		</tr>
		<tr>
		<td>id</td>
		<td>Your user id</td>
		<td>1234</td>
		</tr>
		<tr>
		<td>apikey</td>
		<td>Your Api Key as seen in the pricing page or any provided page</td>
		<td>akhpuwuu37jdkskjaldjskms</td>
		</tr>
		<tr>
		<td>type</td>
		<td>The type</td>
		<td>prepaid , postpaid</td>
		</tr>
		<tr>
		<td>meter_number</td>
		<td>The recipient Smart Card Number.</td>
		<td>123456789093</td>
		</tr>
		<tr>
		<td>plan</td>
		<td>See Plan ID </td>
		<td>1</td>
		</tr>
		<tr>
		<td>amount</td>
		<td>the amount </td>
		<td>10000</td>
		</tr>
		
		</tbody>
		</table>
	
		Example: <?php echo esc_url(plugins_url('vprest/?q=bill&id='.$id.'&apikey='.$apikey.''));?>&type=prepaid&meter_number=1212121212&plan=1&amount=10000<br>
	
	Bill Plans : <br>
	Please note that all bill ids are not always Available. Make Inquiries From the Admin incase there is any update
	<table class="table table-responsive table-hover history-successful h6 font-size">
	<thead>
	<tr>
	<th scope='col'>Plan ID</th>
	<th scope='col'>NAME</th>
	<th scope='col'>Discount %</th>
	</tr>
	</thead>
	<tbody>
	<?php
	
	for($i = 1;$i <= 15; $i++){
	
	$plan = vp_option_array($option_array,"cbill".($i-1));
	$name = vp_option_array($option_array,"cbilln".($i-1));
	$discount = floatval($level[0]->bill_prepaid);
	
	if(!empty($plan) && !empty($name)){
	?>
	<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $name;?></td>
	<td><?php echo $discount;?></td>
	</tr>
	<?php
	}
	
	
	}
	
	?>
	</tbody>
	</table>
	
	
	
	
	</div>
	
	<?php
}

}
else{
	?>
	<div class="col">
<div class="mb-3">
<h1>"API ACCESSIBILTY NOT AVAILABLE FOR THIS PLAN"</h1>
</div>
</div>
<?php
}

}
		
?>