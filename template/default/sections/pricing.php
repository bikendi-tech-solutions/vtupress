<?php
if(isset($_GET["vend"]) && $_GET["vend"]=="pricing" && vp_option_array($option_array,"resell") == "yes" && isset($level)){
	

?>
<h3>PRICING</h3><br>
<div class="col">
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
$discount =  floatval($level[0]->mtn_vtu);
$plan_network = "MTN";
$plan_type = "VTU";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"vtucontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->glo_vtu);
$plan_network = "GLO";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->mobile_vtu);
$plan_network = "9MOBILE";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->airtel_vtu);
$plan_network = "AIRTEL";
$plan_type = "VTU";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"vtucontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->mtn_share);
$plan_network = "MTN";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->glo_share);
$plan_network = "GLO";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->mobile_share);
$plan_network = "9MOBILE";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"sharecontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->airtel_share);
$plan_network = "AIRTEL";
$plan_type = "SHARE & SELL";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"sharecontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->mtn_awuf);
$plan_network = "MTN";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"awufcontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->glo_awuf);
$plan_network = "GLO";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"awufcontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->mobile_awuf);
$plan_network = "9MOBILE";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if( vp_option_array($option_array,"awufcontrol") == "checked"   && vp_option_array($option_array,"setairtime") == "checked"){
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
$discount =  floatval($level[0]->airtel_awuf);
$plan_network = "AIRTEL";
$plan_type = "AWUF";
$api = strtolower($plan_network);
if(vp_option_array($option_array,"awufcontrol") == "checked"    && vp_option_array($option_array,"setairtime") == "checked"){
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
</div>




<div class="col my-2 shadow rounded p-3">
<h5 class="font-bold code">DATA PLANS </h5><br>
<table class="table table-responsive table-hover history-successful h6 font-size">
<thead>
<tr>
<th scope='col'>Type</th>
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

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
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
$discount =  floatval($level[0]->airtel_sme);
$plan_network = "AIRTEL";
$plan_type = "SME";
$plan_name = vp_option_array($option_array,"acdatan$i");
$plan = vp_option_array($option_array,"acdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
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
$discount =  floatval($level[0]->mobile_sme);
$plan_name = vp_option_array($option_array,"9cdatan$i");
$plan = vp_option_array($option_array,"9cdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"smecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
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
$discount =  floatval($level[0]->glo_sme);
$plan = vp_option_array($option_array,"gcdata$i");

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)  && vp_option_array($option_array,"smecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
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
$plan = vp_option_array($option_array,"rcdata$i");
$disamount = vp_option_array($option_array,"rcdatap$i");
$plan_network = "MTN";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"rcdatan$i");
$plan = vp_option_array($option_array,"rcdata$i");
$discount =   floatval($level[0]->mtn_gifting);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}

for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"aapi2$i");
$plan = vp_option_array($option_array,"racdata$i");
$disamount =  vp_option_array($option_array,"racdatap$i");
$plan_network = "AIRTEL";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"racdatan$i");
#$plan = vp_option_array($option_array,"racdata$i");
$discount =  floatval($level[0]->airtel_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"9api2$i");
$plan = vp_option_array($option_array,"r9cdata$i");
$disamount = vp_option_array($option_array,"r9cdatap$i");
$plan_network = "9MOBILE";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"r9cdatan$i");
$plan = vp_option_array($option_array,"r9cdata$i");
$discount =  floatval($level[0]->mobile_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}

}
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"gapi2$i");
$plan = vp_option_array($option_array,"rgcdata$i");
$disamount = vp_option_array($option_array,"rgcdatap$i");
$plan_network = "GLO";
$plan_type = "DIRECT";
$plan_name = vp_option_array($option_array,"rgcdatan$i");
$plan = vp_option_array($option_array,"rgcdata$i");
$discount =  floatval($level[0]->glo_gifting);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"directcontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
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
$plan = vp_option_array($option_array,"r2cdata$i");
$disamount = vp_option_array($option_array,"r2cdatap$i");
$plan_network = "MTN";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2cdatan$i");
$plan = vp_option_array($option_array,"r2cdata$i");
$discount =  floatval($level[0]->mtn_corporate);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#ffc107;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}

for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"aapi3$i");
$plan = vp_option_array($option_array,"r2acdata$i");
$disamount = vp_option_array($option_array,"r2acdatap$i");
$plan_network = "AIRTEL";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2acdatan$i");
$plan = vp_option_array($option_array,"r2acdata$i");
$discount =  floatval($level[0]->airtel_corporate);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#e83e8c; color:white;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"9api3$i");
$plan = vp_option_array($option_array,"r29cdata$i");
$disamount = vp_option_array($option_array,"r29cdatap$i");
$plan_network = "9MOBILE";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r29cdatan$i");
$plan = vp_option_array($option_array,"r29cdata$i");
$discount =  floatval($level[0]->mobile_corporate);

if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#20c997; color:white;'>
<td scope='row'> $plan_type </td>
<td> $plan_network </td><td> ".strtoupper($plan_name)." </td>
<td> NGN $disamount </td>
<td> $discount% </td>
</tr>
";

}


}
for($i = 0; $i <= 10; $i++ ){
$api =  vp_option_array($option_array,"gapi3$i");
$plan = vp_option_array($option_array,"r2gcdata$i");
$disamount = vp_option_array($option_array,"r2gcdatap$i");
$plan_network = "GLO";
$plan_type = "CORPORATE";
$plan_name = vp_option_array($option_array,"r2gcdatan$i");
$plan = vp_option_array($option_array,"r2gcdata$i");
$discount =  floatval($level[0]->glo_corporate);


if(!empty($plan) && !empty($plan_name)&& !empty($disamount)   && vp_option_array($option_array,"corporatecontrol") == "checked"    && vp_option_array($option_array,"setdata") == "checked"){
echo"
<tr style='background-color:#28a745;color:white;'>
<td scope='row'> $plan_type </td>
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
			


}
elseif(isset($_GET["vend"]) && $_GET["vend"]=="pricing" && !isset($level)){
echo"
Your Current Plan Cannot Allow Visibility Of Pricing Page, Kindly Access The Service Page Such As Airtime or Data To See Actual Prices. Thanks
";
}
		
?>