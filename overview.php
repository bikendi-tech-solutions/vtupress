<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

function overview(){
    //sairtime balance
global $wpdb;
$mabal = $wpdb->prefix.'sairtime';
$mabalrs = $wpdb->get_results("SELECT * FROM $mabal");
$mabalsum = 0;
foreach($mabalrs as $mabalr){
$mabalsum += $mabalr->amount;
}
//sdata balance
global $wpdb;
$sdbal = $wpdb->prefix.'sdata';
$sdbalrs = $wpdb->get_results("SELECT * FROM $sdbal");
$sdbalsum = 0;
foreach($sdbalrs as $sdbalr){
$sdbalsum += $sdbalr->amount;
}

//failed airtime balance
global $wpdb;
$fabal = $wpdb->prefix.'fairtime';
$fabalrs = $wpdb->get_results("SELECT * FROM $fabal");
$fabalsum = 0;
foreach($fabalrs as $fabalr){
$fabalsum += $fabalr->amount;
}

//failed data balance
global $wpdb;
$fdbal = $wpdb->prefix.'fdata';
$fdbalrs = $wpdb->get_results("SELECT * FROM $fdbal");
$fdbalsum = 0;
foreach($fdbalrs as $fdbalr){
$fdbalsum += $fdbalr->amount;
}

echo apply_filters("vpover","heloppo");
echo '
<style>

.overview{
padding-left:5px;
padding-right:5px;
border:2px solid lightblue;

}

.grid-container{
display:grid;
grid-template-columns:20% 20% 20% 20%;
grid-auto-rows:minmax(100px, auto);
grid-template-areas:
"value value value value"
"chart chart chart chart";
margin-left:auto;
margin-right:auto;

}




.value-container{
grid-area:value;
display:grid;
grid-template-columns:1fr 1fr 1fr 1fr;
grid-auto-rows:minmax(100px, auto);
grid-template-areas:
"airtime data cable bill";
text-align:center;

}




#airtime-content{
grid-area:airtime;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"airtimeName airtimeName"
"airtimeValues airtimeValuef";
text-align:center;
font-size:20px;
font-family:Arial;
}
#airtime-name{
grid-area:airtimeName;
border-bottom:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
font-family: cursive;
}

#airtime-values{
grid-area:airtimeValues;
border-right:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
color:green;
}

#airtime-valuef{
grid-area:airtimeValuef;
border-right:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
color:red;
}


#data-content{
grid-area:data;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"dataName dataName"
"dataValues dataValuef";
text-align:center;
font-size:20px;
font-family:Arial;
}

#data-name{
grid-area:dataName;
border-bottom:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
font-family: cursive;
}

#data-values{
grid-area:dataValues;
border-right:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
color:green;
}
#data-valuef{
grid-area:dataValuef;
display: flex;
justify-content: center;
align-items: center;
color:red;
}






#cable-content{
grid-area:cable;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"cableName cableName"
"cableValues cableValuef";
text-align:center;
font-size:20px;
font-family:Arial;
}

#cable-name{
grid-area:cableName;
border-bottom:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
font-family: cursive;
}

#cable-values{
grid-area:cableValues;
border-right:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
color:green;
}
#cable-valuef{
grid-area:cableValuef;
display: flex;
justify-content: center;
align-items: center;
color:red;
}





#bill-content{
grid-area:bill;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"billName billName"
"billValues billValuef";
text-align:center;
font-size:20px;
font-family:Arial;
}

#bill-name{
grid-area:billName;
border-bottom:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
font-family: cursive;
}

#bill-values{
grid-area:billValues;
border-right:1px solid blue;
display: flex;
justify-content: center;
align-items: center;
color:green;
}
#bill-valuef{
grid-area:billValuef;
display: flex;
justify-content: center;
align-items: center;
color:red;
}






.chart-grid-container{
grid-area:chart;
display:grid;
grid-template-columns:1fr 1fr 1fr 1fr;
grid-auto-rows:minmax(400px, auto);
grid-template-areas:
"left right right right";

}
.left-grid{
display:grid;
grid-template-columns:1fr 1fr;
grid-template-areas:
"leftMain leftMain"
"leftDown1 leftDown2";
grid-auto-rows:minmax(100px,auto);
grid-area:left;
background-color:#f1f1f1;
border:1px solid blue;

}





.left-main{
grid-area:leftMain;
border:1px solid grey;
}
.left-down1{
grid-area:leftDown1;
border:1px solid grey;
}
.left-down2{
grid-area:leftDown2;
border:1px solid grey;
}

.right-grid{
grid-area:right;
background-color:#f1f1f1;
border:1px solid blue;
}


.chart-container{
margin-left:auto;
margin-right:auto;
position: relative;
height:auto; 
width:auto;
}

.line-container{
margin-left:auto;
margin-right:auto;
margin-top:auto;
margin-bottom:auto;
position: relative; 
height:50%; 
width:50%;
}


@media only screen and (min-device-width: 240px) and (max-device-width: 768px){
body{
background-color:grey;
}
.overview{
padding-left:5px;
padding-right:5px;
border:2px solid lightblue;

}

.grid-container{
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(100px, auto);
grid-template-areas:
"value value"
"chart chart";
max-width:800px;
margin-left:auto;
margin-right:auto;
grid-row-gap:20px;
font-style:bold;
}




.value-container{
grid-area:value;
display:grid;
grid-template-columns:1fr 1fr;
grid-row-gap:20px;
grid-template-areas:
"airtime data"
"cable bill";
grid-auto-rows:minmax(100px, auto);
text-align:center;
background-color:#f1f1f0;
}




#airtime-content{
grid-area:airtime;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"airtimeName airtimeName"
"airtimeValues airtimeValuef";
text-align:center;
}
#airtime-name{
grid-area:airtimeName;
border-bottom:1px solid blue;
}
#airtime-values{
grid-area:airtimeValues;
border-right:1px solid blue;
}
#airtime-valuef{
grid-area:airtimeValuef;
}


#data-content{
grid-area:data;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"dataName dataName"
"dataValues dataValuef";
text-align:center;
}

#data-name{
grid-area:dataName;
border-bottom:1px solid blue;
}
#data-values{
grid-area:dataValues;
border-right:1px solid blue;
}
#data-valuef{
grid-area:dataValuef;
}






.cable-content{
grid-area:cable;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"cableName cableName"
"cableValues cableValuef";
text-align:center;
}

.cable-name{
grid-area:cableName;
border-bottom:1px solid blue;
}
.cable-values{
grid-area:cableValues;
border-right:1px solid blue;
}
.cable-valuef{
grid-area:cableValuef;
}





.bill-content{
grid-area:bill;
border:1px solid blue;
display:grid;
grid-template-columns:1fr 1fr;
grid-auto-rows:minmax(40px, auto);
grid-template-areas:
"billName billName"
"billValues billValuef";
text-align:center;
}

.bill-name{
grid-area:billName;
border-bottom:1px solid blue;
}
.bill-values{
grid-area:billValues;
border-right:1px solid blue;
}
.bill-valuef{
grid-area:billValuef;
}






.chart-grid-container{
grid-area:chart;
display:grid;
grid-template-columns:1fr 1fr;
grid-template-areas:
"left left"
"right right";
grid-auto-rows:minmax(100px, auto);

}
.left-grid{
grid-row-start:1;
grid-row-end:2;
grid-column-start:1;
grid-column-end:3;
grid-area:left;
display:grid;
grid-template-columns:1fr 1fr;
grid-template-areas:
"leftMain leftMain"
"leftDown1 leftDown2";
background-color:#f1f1f1;
border:1px solid blue;
}





.left-main{
grid-area:leftMain;
border:1px solid grey;
}
.left-down1{
grid-area:leftDown1;
border:1px solid grey;
}
.left-down2{
grid-area:leftDown2;
border:1px solid grey;
}



.right-grid{
grid-row-start:2;
grid-row-end:3;
grid-column-start:2;
grid-column-end:3;
grid-area:right;
background-color:#f1f1f1;
border:1px solid blue;
grid-area:right;

}


.chart-container{
margin-left:auto;
margin-right:auto;
margin-top:auto;
margin-bottom:auto;
position: relative;
height:auto; 
width:350px;
}

.line-container{
margin-left:auto;
margin-right:auto;
margin-top:auto;
margin-bottom:auto;
position: relative; 
height:auto; 
width:450px;
}


}




/* Portrait and Landscape */
@media only screen and (min-device-width: 800px) and (max-device-width: 1280px) {

}









</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>



<div class="overview">
<h3 style="text-align:center;">OverView</h3>
<div class="grid-container">

<div class="value-container">
<div id="airtime-content">
<div id="airtime-name">
AIRTIME
</div>
<div id="airtime-values">
NGN <b  id="airtime-s">'.$mabalsum.'</b>
</div>
<div id="airtime-valuef">
NGN <b  id="airtime-f">'.$fabalsum.'</b>
</div>

</div>
<div id="data-content">
<div id="data-name">
DATA
</div>
<div id="data-values">
NGN <b  id="data-s">'.$sdbalsum.'</b>
</div>
<div id="data-valuef">
NGN <b  id="data-f">'.$fdbalsum.'</b>
</div>
</div>
';

if(is_plugin_active('bcmv/bcmv.php')){
include_once(ABSPATH."wp-load.php");
global $wpdb;
$sbbal = $wpdb->prefix.'sbill';
$sbbalrs = $wpdb->get_results("SELECT * FROM $sbbal");
$sbbalsum = 0;
foreach($sbbalrs as $sbbalr){
$sbbalsum += $sbbalr->amount;
}
//fbill balance
global $wpdb;
$fbbal = $wpdb->prefix.'fbill';
$fbbalrs = $wpdb->get_results("SELECT * FROM $fbbal");
$fbbalsum = 0;
foreach($fbbalrs as $fbbalr){
$fbbalsum += $fbbalr->amount;
}
//scable balance
global $wpdb;
$scbal = $wpdb->prefix.'scable';
$scbalrs = $wpdb->get_results("SELECT * FROM $scbal");
$scbalsum = 0;
foreach($scbalrs as $scbalr){
$scbalsum += $scbalr->amount;
}
//scable balance
global $wpdb;
$fcbal = $wpdb->prefix.'fcable';
$fcbalrs = $wpdb->get_results("SELECT * FROM $fcbal");
$fcbalsum = 0;
foreach($fcbalrs as $fcbalr){
$fcbalsum += $fcbalr->amount;
}	
	
	
	
echo '
<div id="cable-content">
<div id="cable-name">
CABLE
</div>

<div id="cable-values">
NGN <b  id="cable-s">'.$scbalsum.'</b>
</div>
<div id="cable-valuef">
NGN <b  id="cable-f">'.$fcbalsum.'</b>
</div>
</div>
<div id="bill-content">
<div id="bill-name">
BILL
</div>
<div id="bill-values">
NGN <b  id="bill-s">'.$sbbalsum.'</b>
</div>
<div id="bill-valuef">
NGN <b  id="bill-f">'.$fbbalsum.'</b>
</div>
</div>
';
}
else{
	
echo '
<div id="cable-content">
<div id="cable-name">
CABLE
</div>

<div id="cable-values">
NGN <b  id="cable-s">1</b>
</div>
<div id="cable-valuef">
NGN <b  id="cable-f">1</b>
</div>
</div>
<div id="bill-content">
<div id="bill-name">
BILL
</div>
<div id="bill-values">
NGN <b  id="bill-s">1</b>
</div>
<div id="bill-valuef">
NGN <b  id="bill-f">1</b>
</div>
</div>
';
	
}
echo '
</div>

<div class="chart-grid-container">
<div class="left-grid">

<div class="left-main">
<div class="chart-container">
<canvas id="myChart" width="400" height="400"></canvas>
</div>
</div>
<div class="left-down1">

</div>
<div class="left-down2">

</div>
</div>
<div class="right-grid">

<div class="line-container">
<canvas id="lineChart" width="400" height="400"></canvas>
</div>

</div>

</div>
</div>
</div>
<script>
var airtime = parseInt(document.getElementById("airtime-s").innerHTML);
var data = parseInt(document.getElementById("data-s").innerHTML);
var cable = parseInt(document.getElementById("cable-s").innerHTML);
var bill = parseInt(document.getElementById("bill-s").innerHTML);

var airtimef = parseInt(document.getElementById("airtime-f").innerHTML);
var dataf = parseInt(document.getElementById("data-f").innerHTML);
var cablef = parseInt(document.getElementById("cable-f").innerHTML);
var billf = parseInt(document.getElementById("bill-f").innerHTML);

var totalvendf = airtimef + dataf + cablef + billf;


var totalvend = airtime + data + cable + bill;





var aperc = (airtime * 360) / totalvend;
var dperc = (data * 360) / totalvend;
var cperc = (cable * 360) / totalvend;
var bperc = (bill * 360) / totalvend;


var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: "pie",
    data: {
        labels: ["Airtime", "Data", "Cable", "Bill"],
        datasets: [{
            label: ["Overview"],
            data: [aperc, dperc, cperc, bperc],
            backgroundColor: [
                "green",
                "red",
                "rgba(255, 206, 86, 0.2)",
                "rgba(75, 192, 192, 0.2)",
                "rgba(153, 102, 255, 0.2)",
                "rgba(255, 159, 64, 0.2)"
            ],
            borderColor: [
                "rgba(255,99,132,1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)"
            ],
            borderWidth: 1
        }]
    },
    options: {
		responsive: true,
		elements: {
            line: {
                tension: 0, // disables bezier curves
            }
        },
		legend: {
            display: true,
		
            labels: {
                fontColor: "rgb(255, 99, 132)",
				fontStyle: "bold",
				fontSize:13,
				fontFamily: "Arial"
            }
        },
		 title: {
            display: true,
            text: "Vendor\'s Chart"
        },
       /* scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }*/
    }
});



//for line
var ctx = document.getElementById("lineChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["Successful", "Failed"],
        datasets: [{
            label: ["Successful"],
            data: [totalvend, totalvendf],
            backgroundColor: [
                "green",
                "red"
                //"rgba(255, 206, 86, 0.2)",
               // "rgba(75, 192, 192, 0.2)",
                //"rgba(153, 102, 255, 0.2)",
                //"rgba(255, 159, 64, 0.2)"
            ],
            borderColor: [
                "rgba(255,99,132,1)",
                "rgba(54, 162, 235, 1)",
                "rgba(255, 206, 86, 1)",
                "rgba(75, 192, 192, 1)",
                "rgba(153, 102, 255, 1)",
                "rgba(255, 159, 64, 1)"
            ],
            borderWidth: 1
        }]
    },
    options: {
		responsive: true,
		elements: {
            line: {
                tension: 0, // disables bezier curves
            }
        },
		legend: {
            display: true,
			
            labels: {
                fontColor: "rgb(255, 99, 132)",
				fontStyle: "bold",
				fontSize:20,
				fontFamily: "Arial"
            }
        },
		 title: {
            display: true,
            text: "Vendor\'s Chart"
        },
       scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>

';



	if(vp_getoption("bmethod") == "post"){
$bl = vp_getoption("balanceattr");

$data = array(
    vp_getoption("usernameattr") => vp_getoption("username"),
    vp_getoption("passwordattr") => vp_getoption("password"),
	vp_getoption("cpara1attr") => vp_getoption("cpara1"),
	vp_getoption("cpara2attr") => vp_getoption("cpara2"),
	vp_getoption("cpara3attr") => vp_getoption("cpara3"),
	vp_getoption("cpara4attr") => vp_getoption("cpara4")
);

$ur = vp_getoption("baseurl").vp_getoption("balanceend");
//.vp_getoption("usernameattr").'='.vp_getoption("username").'&'.vp_getoption("passwordattr").'='.vp_getoption("password").'&'.vp_getoption("cpara1attr").'='.vp_getoption("cpara1").'&'.vp_getoption("cpara2attr").'='.vp_getoption("cpara2").'&'.vp_getoption("cpara3attr").'='.vp_getoption("cpara3").'&'.vp_getoption("cpara4attr").'='.vp_getoption("cpara4");
$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
$ch = curl_init($ur);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
if(vp_getoption("baddpost") == "yes"){
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
}
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//if(vp_getoption("head1") != ""){
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: ' .vp_getoption("hvalue")." ".vp_getoption("head1")

));
//}
$cresp = curl_exec($ch);
if($e = curl_error($ch)){
echo $e;
//echo "<script>alert('".$e."');</script>";
}
else{$en = json_decode($cresp);
$bdata1 = vp_getoption("bdata1");
$bdata2 = vp_getoption("bdata2");
switch(vp_getoption("balt")){
case "nested":
$tba = $en->$bdata1->$bdata2;
break;
case "not-nested":
$tba = $en->$bdata1;
break;

}

curl_close($ch);
}
}
else{
$bl = vp_getoption("balanceattr");

$ur = vp_getoption("baseurl").vp_getoption("balanceend").vp_getoption("usernameattr").'='.vp_getoption("username").'&'.vp_getoption("passwordattr").'='.vp_getoption("password").'&'.vp_getoption("cpara1attr").'='.vp_getoption("cpara1").'&'.vp_getoption("cpara2attr").'='.vp_getoption("cpara2").'&'.vp_getoption("cpara3attr").'='.vp_getoption("cpara3").'&'.vp_getoption("cpara4attr").'='.vp_getoption("cpara4");
$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
$ch = curl_init($ur);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
//if(vp_getoption("head1") != ""){
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: ' .vp_getoption("hvalue")." ".vp_getoption("head1")

));
//}
$response = curl_exec($ch);
if($e = curl_error($ch)){
echo $e;
//echo "<script>alert('".$e."');</script>";
}
else{$en = json_decode($response);
$bdata1 = vp_getoption("bdata1");
$bdata2 = vp_getoption("bdata2");
switch(vp_getoption("balt")){
case "nested":
$tba = $en->$bdata1->$bdata2;
break;
case "not-nested":
$tba = $en->$bdata1;
break;

}

curl_close($ch);
}
}
$bdata1 = vp_getoption("bdata1");
$bdata2 = vp_getoption("bdata2");
switch(vp_getoption("balt")){
case "nested":
$tba = $en->$bdata1->$bdata2;
break;
case "not-nested":
$tba = $en->$bdata1;
break;

}
echo '
<style>
.div{width:70%; border-radius:25px; border:5px solid blue; height:47px; position:relative; overflow:auto;}
span.sym{background-color: blue; color:white; font-size:40px; position: absolute;left:0;height:40px;padding-top:
10px;}
span.price{background-color: white; position: absolute; height:40px; right:0; font-size:40px; padding-top:10px;}
d{border-bottom:2px solid blue; border-left:2px solid blue; height:auto; width:auto;}
</style>
<d>
<b>Total Amount In Your Custom Account</b>
<div class="div">
<span class="sym">
=N=
</span>
<span class="price">
<b>'.$tba.'
</b>
</span>
</div>
</d><br>
';
	

echo'
<input type="checkbox" onchange="checkbal();" id="checkbalbtn">Hide Connection Feed
<div id="checkbal">
<h3>Connection Feed</h4><br>
<b>For MobileMila</b><br>
'.$resp.'<br>

<b>for VTU.NG</b><br>
'.$vresp.'<br>

<b>for Custom</b><br>
'.$cresp.'<br>
</div>
';
do_action("vpoverafter");

}
/*
echo'

<div class="value-container">
<div id="airtime-content">
<div id="airtime-name">
MobileMila Balance
</div>
<div id="airt-values">
NGN <b>2000</b>
</div>
<div id="airt-valuef">

</div>

</div>
<div id="data-content">
<div id="data-name">
VTU NG Balance
</div>
<div id="dat-values">
NGN <b>4000</b>
</div>
<div id="data-valuef">

</div>
</div>
<div id="cable-content">
<div id="cable-name">
CUSTOM Balance
</div>
<div id="cab-values">
NGN <b>4000</b>
</div>
<div id="cab-valuef">

</div>
</div>

</div>

';*/
?>