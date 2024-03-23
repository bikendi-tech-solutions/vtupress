<?php
if(!defined('ABSPATH')){
die();
}
ob_start();
add_action("admin_menu","addMenu");

function addMenu(){
  add_menu_page("Vtu Press","Vtupress","vtupress_access_vtupress","vtupanel","vtupanel", "dashicons-calculator");

}

function vtupanel(){
  add_action('init','vtupress_remove_notices');
  function vtupress_remove_notices(){
    remove_all_actions( 'admin_notices');
    remove_all_actions( 'user_admin_notices');
  };
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/admin.php');
}


function vtulevels(){
	global $wpdb;
	
	add_option("drop_tb1",1);
	
if(get_option("drop_tb1") == 1){
$table_name = $wpdb->prefix . 'vp_levels';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);


$charset_collate=$wpdb->get_charset_collate();
$sql= "CREATE TABLE IF NOT EXISTS $table_name(
id int NOT NULL AUTO_INCREMENT,
name text,

mtn_vtu DECIMAL(5,2) zerofill,
glo_vtu DECIMAL(5,2) zerofill,
mobile_vtu DECIMAL(5,2) zerofill,
airtel_vtu DECIMAL(5,2) zerofill,

mtn_awuf DECIMAL(5,2) zerofill,
glo_awuf DECIMAL(5,2) zerofill,
mobile_awuf DECIMAL(5,2) zerofill,
airtel_awuf DECIMAL(5,2) zerofill,

mtn_share DECIMAL(5,2) zerofill,
glo_share DECIMAL(5,2) zerofill,
mobile_share DECIMAL(5,2) zerofill,
airtel_share DECIMAL(5,2) zerofill,


mtn_sme DECIMAL(5,2) zerofill,
glo_sme DECIMAL(5,2) zerofill,
mobile_sme DECIMAL(5,2) zerofill,
airtel_sme DECIMAL(5,2) zerofill,

mtn_corporate DECIMAL(5,2) zerofill,
glo_corporate DECIMAL(5,2) zerofill,
mobile_corporate DECIMAL(5,2) zerofill,
airtel_corporate DECIMAL(5,2) zerofill,

mtn_gifting DECIMAL(5,2) zerofill,
glo_gifting DECIMAL(5,2) zerofill,
mobile_gifting DECIMAL(5,2) zerofill,
airtel_gifting DECIMAL(5,2) zerofill,

cable DECIMAL(5,2) zerofill,
bill_prepaid DECIMAL(5,2) zerofill,

card_mtn DECIMAL(5,2) zerofill,
card_glo DECIMAL(5,2) zerofill,
card_9mobile DECIMAL(5,2) zerofill,
card_airtel DECIMAL(5,2) zerofill,

epin_waec DECIMAL(5,2) zerofill,
epin_neco DECIMAL(5,2) zerofill,
epin_jamb DECIMAL(5,2) zerofill,
epin_nabteb DECIMAL(5,2) zerofill,


status text,
upgrade bigint,
developer text,
transfer text,

total_level int,
level_1 DECIMAL(5,2) zerofill,
level_1_upgrade DECIMAL(5,2) zerofill,


PRIMARY KEY (id))$charset_collate;";

require_once(ABSPATH.'wp-admin/includes/upgrade.php');
dbDelta($sql);

global $wpdb;
$table_name = $wpdb->prefix."vp_levels";
$customer = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s",'customer'));
$reseller = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s",'reseller'));

if($reseller == NULL){
$wpdb->insert($table_name, array(
'name'=> "reseller",
'mtn_vtu'=> "0",
'glo_vtu'=> "0",
'mobile_vtu'=> "0",
'airtel_vtu'=> "0",

'mtn_awuf'=> "0",
'glo_awuf'=> "0",
'mobile_awuf'=> "0",
'airtel_awuf'=> "0",

'mtn_share'=> "0",
'glo_share'=> "0",
'mobile_share'=> "0",
'airtel_share'=> "0",

'mtn_sme'=> "0",
'glo_sme'=> "0",
'mobile_sme'=> "0",
'airtel_sme'=> "0",

'mtn_corporate'=> "0",
'glo_corporate'=> "0",
'mobile_corporate'=> "0",
'airtel_corporate'=> "0",

'mtn_gifting'=> "0",
'glo_gifting'=> "0",
'mobile_gifting'=> "0",
'airtel_gifting'=> "0",

'cable'=> "0",

'bill_prepaid'=> "0",

'card_mtn'=> "0",
'card_glo'=> "0",
'card_9mobile'=> "0",
'card_airtel'=> "0",

'epin_waec'=> "0",
'epin_neco'=> "0",
'epin_jamb'=> "0",
'epin_nabteb'=> "0",

'status'=> "active",

'upgrade'=> "1000",
'total_level'=> "0",
'level_1'=> "0",
'level_1_upgrade'=> "0",
'developer'=> "no",
'transfer'=> "no"


));



}


if($customer == NULL){
$wpdb->insert($table_name, array(
'name'=> "customer",
'mtn_vtu'=> "0",
'glo_vtu'=> "0",
'mobile_vtu'=> "0",
'airtel_vtu'=> "0",

'mtn_awuf'=> "0",
'glo_awuf'=> "0",
'mobile_awuf'=> "0",
'airtel_awuf'=> "0",

'mtn_share'=> "0",
'glo_share'=> "0",
'mobile_share'=> "0",
'airtel_share'=> "0",

'mtn_sme'=> "0",
'glo_sme'=> "0",
'mobile_sme'=> "0",
'airtel_sme'=> "0",

'mtn_corporate'=> "0",
'glo_corporate'=> "0",
'mobile_corporate'=> "0",
'airtel_corporate'=> "0",

'mtn_gifting'=> "0",
'glo_gifting'=> "0",
'mobile_gifting'=> "0",
'airtel_gifting'=> "0",

'cable'=> "0",

'bill_prepaid'=> "0",

'card_mtn'=> "0",
'card_glo'=> "0",
'card_9mobile'=> "0",
'card_airtel'=> "0",

'epin_waec'=> "0",
'epin_neco'=> "0",
'epin_jamb'=> "0",
'epin_nabteb'=> "0",

'status'=> "active",

'upgrade'=> "1000",
'total_level'=> "0",
'level_1'=> "0",
'level_1_upgrade'=> "0",
'developer'=> "no",
'transfer'=> "no"


));



}

update_option("drop_tb1",2);
}
	
	
	
	
	
	
	
	


	
	?>




<?php


?>






	
	<?php
	
}

function vpsystem(){
	?>
<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'vtupress/css/bootstrap.min.css?v=1') );?>" />
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/bootstrap.min.js?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/jquery.js?v=1') );?>"></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/sweet.js?v=1') );?>" ></script>
<style>
    #cover-spin {
        position:fixed;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        /*display:none;*/
    }
	    #cover-spin::after {
        content:"";
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
	
</style>
	
      <div id="cover-spin" >
	  
	  </div>
<div class="container">
<div class="row border border-secondary mb-4">

<div class="col-3 border border-secondary bg-success text-white" style="text-align:center;">
<h3>Security:
<div class="spinner-border text-primary" role="status">

</div>

</h3><br>
<h4>
<?php
if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off"){
echo "<span class='badge badge-info'>Running</span> ";
}
else{
echo "<span class='badge badge-danger'>Not Running</span>";	
}
?>
</h4>
</div>

<div class="col-6 border border-secondary bg-primary text-white">
<?php
//whether ip is from share internet
if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
	$system = "Shared Internet";
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = trim($_SERVER['HTTP_X_FORWARDED_FOR']);
	if(is_numeric(stripos($ip_address,','))){
		$ip_address = trim(explode(',',$ip_address)[1]);
	}
	$system = "Proxy";
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
	$system = "Remote";
  }
$details = json_decode(file_get_contents("https://ipinfo.io/{$ip_address}/json"));

if(vp_getoption("vp_security") == "yes"){
	$disabled = "data=''";
}else{
	$disabled = "disabled";
}

?>
<h3>Last Login IP: - <span class="badge badge-secondary"><?php echo $system;?></span></h3><br>
<h4><?php echo $ip_address;?></h4>
</div>


<div class="col-3 border border-secondary bg-secondary text-white" style="text-align:center;">
<h3>Location</h3><br>
<h4><?php echo $details->city;?> - <?php echo $details->country;?></h4>


</div>


</div>

<div class="row">
<div class="col">
<div class="input-group">
<span class="input-group-text">URL</span>
<span class="input-group-text"><?php echo vp_getoption("siteurl");?></span>
</div>
</div>

<div class="col-2">
<div class="input-group">
<span class="input-group-text">SSL</span>
<?php
$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$read = fopen(vp_getoption("siteurl"), "rb", false, $stream);
$cont = stream_context_get_params($read);
$var = ($cont["options"]["ssl"]["peer_certificate"]);
$result = (!is_null($var)) ? true : false;
if(is_null($var)){
?>
<span class="input-group-text">NO</span>
<?php
}else{
	?>
<span class="input-group-text">YES</span>
<?php
}
?>
</div>
</div>

<div class="col">
<div class="input-group">
<span class="input-group-text">SERVER NAME</span>
<span class="input-group-text"><?php echo $_SERVER["SERVER_NAME"];?></span>
</div>
</div>

<div class="col">
<div class="input-group">
<span class="input-group-text">HTTP HOST</span>
<span class="input-group-text"><?php echo $_SERVER['HTTP_HOST'];?></span>
</div>
</div>


</div>



<div class="row border border-secondary mb-4">
<div class="col border border-primary">
<div class="info">
<label class="form-label">HTTP REDIRECTION</label>
<br>
Redirect All http request to https (recommended)
</div><br>
<div class="input-group">
<span class="input-group-text">Force Http</span>
<input type="checkbox" class="btn-check hr" id="btn-check-outlined" autocomplete="off" name="http-redirection" >
<label class="btn btn-outline-primary hrt" for="btn-check-outlined">...</label>

</div>
 </div>
 
 <div class="col border border-primary">
 <div class="info">
 <label class="form-label">SECURITY MODE</label>
 <br>
Calm: Make the login security level focus on Vpaccount login page.<br>
Calm: Automatically takes time to ban users<br>
Wild: Makes the login security level focus on vpaccount and wp_login levels<br>
Wild: Watches system strictly and automatically handles users with it's Artificial Intelligence<br>
 </div><br>
 <?php
 if(vp_getoption("vp_security") == "yes"){
?>
 <div class="bottom-button" style="position:relative; bottom:0;">
 <input type="radio" class="btn-check sm off" name="security-mode" id="success-outlied" autocomplete="off" value="off">
<label class="btn btn-outline-primary" for="success-outlied">Off</label>
 
<input type="radio" class="btn-check sm calm" name="security-mode" id="success-outlined" autocomplete="off" value="calm">
<label class="btn btn-outline-success" for="success-outlined">Calm</label>

<input type="radio" class="btn-check sm wild" name="security-mode" id="danger-outlined" autocomplete="off" value="wild">
<label class="btn btn-outline-danger" for="danger-outlined">Wild</label>
 </div>
 
<?php
}
else{
?>
<a href="https://api.whatsapp.com/send/?phone=2347049626922&text=Unlock%20Security%20Feature%20For%20Me&app_absent=0" >
<input type="button" class="btn-check btn-primary" name="security-mode" id="danger-outlined" autocomplete="off" checked>
<label class="btn btn-outline-primary text-whit" for="danger-outlined">UNLOCK SECURITY FEATURE</label>
</a>
<?php	
}
?>
 </div>
 

  <div class="col border border-primary">
 <div class="info">
 
 
 <label class="form-label">GLOBAL SECURITY</label>
 <br>
Choose whether you want vtupress to handle security steps from your site across all other sites using vtupress.<br>
<blockquote>That is, Once you ban a user, the user will not be able to access other websites using vtupress<blockquote><br>
</div><br>
<?php
if(vp_getoption("vp_security") == "yes"){
?>
 <div class="bottom-button" style="position:relative; bottom:0;">

<input type="radio" class="btn-check gs enabled" name="global-security" id="success-outline" autocomplete="off" value="enabled">
<label class="btn btn-outline-success" for="success-outline">Enable</label>

<input type="radio" class="btn-check gs disabled" name="global-security" id="danger-outline" autocomplete="off" value="disabled">
<label class="btn btn-outline-danger" for="danger-outline">Disable</label>
</div>
<?php
}
else{
?>
<a href="https://api.whatsapp.com/send/?phone=2347049626922&text=Unlock%20Security%20Feature%20For%20Me&app_absent=0" >
<input type="button" class="btn-check bg-primary" name="security-mode" id="primary-outlined" autocomplete="off"  checked>
<label class="btn btn-outline-primary text-whit" for="primary-outlined">UNLOCK SECURITY FEATURE</label>
</a>
<?php	
}
?>
 </div>
 </div>



</div>

<div class="container">
<div class="row border border-secondary mb-4">
<div class="col p-3">
<div class="row">
<div class="col-12 col-md">
<h5>Ban IPs:<small class="small">Separate Ips by Comma [,]</small></h5><br>
<textarea class="form-control ips" <?php echo $disabled;?> ><?php echo vp_getoption("vp_ips_ban");?></textarea>
</div>
<div class="col-12 col-md">
<h5>Ban users:<small class="small" >Separate Ips by Comma [,]</small></h5><br>
<textarea class="form-control users" <?php echo $disabled;?>><?php echo vp_getoption("vp_users_ban");?></textarea>
</div>
<div class="col-12 col-md">
<h5>Restrict Signup With Username/Emails:<small class="small" >Separate Ips by Comma [,]</small></h5><br>
<textarea class="form-control ban-emails" <?php echo $disabled;?>><?php echo vp_getoption("vp_users_email");?></textarea>
</div>
</div>
<br>

<div class="row">
<div class="col-12 col-sm">
<h5>Ban From:</h5><br>
<div class="form-check">
  <input class="form-check-input user-dashboard" type="checkbox" value="" id="flexCheckDefault" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefault">
   Accessing User Dashboard
  </label>
</div>
<div class="form-check">
  <input class="form-check-input entire-website" type="checkbox" value="" id="flexCheckChecked" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckChecked">
   Accessing Entire Website
  </label>
</div>
<div class="form-check">
  <input class="form-check-input other-country" type="checkbox" value="" id="flexCheckCheckedr" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckCheckedr">
   Accessing Website In Other Country
  </label>
</div>

</div>
<div class="col-12 col-sm">
<h5>Transactions:</h5><br>
<div class="form-check">
  <input class="form-check-input limit-transaction-others" type="checkbox" value="" id="flexCheckDefaultf" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefaultf">
   Users Must Wait Every 1MIN Before Processing Next Order To Other Recipient
  </label>
</div>

<div class="form-check">
  <input class="form-check-input limit-transaction-same" type="checkbox" value="" id="flexCheckDefaultg" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefaultg">
   Users Must Wait Every 2MINs Before Processing Next Order To Same Recipient
  </label>
</div>

</div>
</div>

</div>

<div  class="mt-2 mb-2">
 <?php
 if(vp_getoption("vp_security") == "yes"){
?>
<input type="button" value="SAVE" class="btn btn-success save-security">
<?php
 }
 ?>
</div>
</div>
</div>

<script>
jQuery(window).on("load",function(){
jQuery("#cover-spin").hide();
});
jQuery(".hr").on("change",function(){
	if(jQuery(this).is(":checked")){
		jQuery('.hr').attr('checked','checked');
		jQuery('.hrt').text('Enabled');
	}
	else{
		jQuery('.hr').removeAttr('checked');
		jQuery('.hrt').text('Disabled');
	}
});
<?php
if(vp_getoption("http_redirect") == "true"){
echo"
jQuery('.hr').attr('checked','checked');
jQuery('.hr').trigger('click');
jQuery('.hrt').text('Enabled');
jQuery('.hrt').trigger('click');
";
}
else{
echo"
jQuery('.hrt').text('Disabled');
";	
}

echo"
jQuery('.".vp_getoption("global_security")."').prop('checked');
jQuery('.".vp_getoption("global_security")."').trigger('click');
";


echo "jQuery('.".vp_getoption("secur_mod")."').prop('checked');";
echo "jQuery('.".vp_getoption("secur_mod")."').trigger('click');";


if(vp_getoption("access_website") == "true"){
	echo "jQuery('.entire-website').prop('checked');";
	echo "jQuery('.entire-website').trigger('click');";
}

if(vp_getoption("tothers") == "true"){
	echo "jQuery('.limit-transaction-others').prop('checked');";
	echo "jQuery('.limit-transaction-others').trigger('click');";
}

if(vp_getoption("tself") == "true"){
	echo "jQuery('.limit-transaction-same').prop('checked');";
	echo "jQuery('.limit-transaction-same').trigger('click');";
}


if(vp_getoption("access_user_dashboard") == "true"){
	echo "jQuery('.user-dashboard').prop('checked');";
	echo "jQuery('.user-dashboard').trigger('click');";
}

if(vp_getoption("access_country") == "true"){
	echo "jQuery('.other-country').prop('checked');";
	echo "jQuery('.other-country').trigger('click');";
}

?>
jQuery(".save-security").on("click",function(){
jQuery("#cover-spin").show();
var savesecurity = jQuery(".sm:checked").val();
var httpredirection = jQuery(".hr").is(":checked");
var globalsecurity = jQuery(".gs:checked").val();
var userdashboard = jQuery(".user-dashboard").is(":checked");
var enterwebsite = jQuery(".entire-website").is(":checked");
var othercountry = jQuery(".other-country").is(":checked");
var ips = jQuery(".ips").val();
var users = jQuery(".users").val();
var email = jQuery(".ban-emails").val();
var tself = jQuery(".limit-transaction-same").is(":checked");
var tothers = jQuery(".limit-transaction-others").is(":checked");


var obj = {};
obj["secure"] = "secure";
obj["http"] = httpredirection;
obj["global"] = globalsecurity;
obj["security"] = savesecurity;
obj["httips"] = ips;
obj["users"] = users;
obj["email"] = email;
obj["access-website"] = enterwebsite;
obj["user-dashboard"] = userdashboard;
obj["other-country"] = othercountry;
obj["tself"] = tself;
obj["tothers"] = tothers;

/*
alert("http "+obj["http"]);
alert("global "+obj["global"]);
alert("security "+obj["security"]);
alert("ips "+obj["httips"]);
alert("users "+obj["users"]);
alert("aw "+obj["access-website"]);
alert("ud "+obj["user-dashboard"]);
alert("oc "+obj["other-country"]);
*/

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/system.php'));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
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
        if(data == "100"){
		  swal({
  title: "SAVED",
  text: "Successfully",
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
  text: data,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: 'POST'
});





});

</script>
<?php	
}

function vplicense(){
	?>
<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'vtupress/css/bootstrap.min.css?v=1') );?>" />
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/bootstrap.min.js?v=1?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/jquery.js?v=1?v=1') );?>"></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/sweet.js?v=1') );?>" ></script>
<style>
.swal-button.swal-button--confirm {
    width: fit-content;
    padding: 10px !important;
}

 #cover-spin {
        position:fixed;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        /*display:none;*/
    }
#cover-spin::after {
        content:"";
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }

</style>

<div id="cover-spin" >
	  
</div>
<script>
jQuery("body").ready(function(){
	jQuery("#cover-spin").hide();
});
</script>

<?php
	
$http_args = array(
'headers' => array(
'cache-control' => 'no-cache',
'content-type' => 'application/json',
'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
),
'sslverify' => false);
$url = 'https://vtupress.com/wp-content/plugins/vtuadmin?id='.vp_getoption('vpid').'&actkey='.vp_getoption('actkey');

$response = wp_remote_retrieve_body(wp_remote_get($url, $http_args));


if(is_wp_error($response)){
$error = $response->get_error_message();
$obj = new stdClass;
$obj->code = "200";
$obj->response = $error;
$json = json_encode($obj)->response;
echo"
<script>
alert($json);
</script>
";
}
else{
$en = json_decode($response, true);

if(!empty($en)){
	
$mkey = vp_getoption('actkey');

if(isset($en["security"])){
$security = $en["security"];
}
else{
$security = "no";
}

if(!empty($en["actkey"])){
	if($en["actkey"] == $mkey){
$check_key = "yes";
	}else{	
$check_key = "no";	
	}
}
else{
$check_key = "no";
}

//$_SERVER['SERVER_NAME'];

if(!empty($en["url"])){
	if(preg_match("/".$_SERVER['HTTP_HOST']."/i",$en["url"]) != 0 || strpos($en["url"],$_SERVER['HTTP_HOST']) != false || strpos($en["url"],$_SERVER['SERVER_NAME']) != false || strpos($en["url"],vp_getoption('siteurl')) != false || is_numeric(strpos($en["url"],$_SERVER['HTTP_HOST'])) != false || is_numeric(strpos($en["url"],$_SERVER['SERVER_NAME'])) != false){
$murl = "yes";
	}else{
$murl = "no";	
	}
//echo strpos($en["url"],$_SERVER['HTTP_HOST']);
//echo strpos($en["url"],$_SERVER['SERVER_NAME']);
}
else{
$murl = "no";

}



if($check_key == "yes" ){

$status = $en["status"];

$url = $en["url"];

$plan = $en["plan"];


    
    if( $murl == "yes"){
        
        if($status == "active"){
            
            if($plan){
if($security == "yes"){
	vp_updateoption("vp_security","yes");
}
else{
	vp_updateoption("vp_security","no");	
}

			/////////////////////////////////
	
if($plan == "demo"){
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('vprun','none');
vp_updateoption('resell','no');
}
elseif($plan == "unlimited"){
vp_updateoption('mlm','yes');
vp_updateoption('resell','yes');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
}
elseif($plan == "verified"){
vp_updateoption('resell','yes');
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
}
elseif($plan == "personal-y"){
vp_updateoption('resell','no');
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
}
elseif($plan == "premium-y"){
vp_updateoption('mlm','yes');
vp_updateoption('vprun','none');
vp_updateoption('resell','yes');
vp_updateoption('frmad','none');
}
elseif($plan == "premium"){
vp_updateoption('mlm','yes');
vp_updateoption('vprun','none');
vp_updateoption('frmad','none');
vp_updateoption('resell','yes');
}
elseif($plan == "personal"){
vp_updateoption('mlm','no');
vp_updateoption('vprun','none');
vp_updateoption('resell','no');
vp_updateoption('frmad','none');
}
else{
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");	
}
			////////////////////////////
            }
            
        }else{
//echo '{"status":"200","message":"Status Is '.$status.'"}';
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");	
        }
    }else{
		
//echo '{"status":"200","message":"URL Doesn\'t Match or is not contained in the Url Directory in vtupress official site"}';
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
    }
    
}
else{
vp_updateoption('mlm','no');
vp_updateoption('resell','no');
vp_updateoption("showlicense","hide");
vp_updateoption('vprun','block');
vp_updateoption('frmad','block');
vp_updateoption("vp_security","no");
//echo '{"status":"200","message":"Activation Key Or Id Incorrect"}';
	
}	

}



}
	
	

//echo vp_getoption('vprun');



if(vp_getoption('vprun') == 'none'){
	
	$dstat = "active";
}
if(vp_getoption('vprun') != 'none'){
	
	$dstat = "not active";
}

	$did = vp_getoption('vpid');
	$actkey = vp_getoption('actkey');
	$mainurl = $_SERVER['HTTP_HOST'];
	echo "
	<form method='post' target='_self' class='setactivation'>
	<label>Your ID</label><br>
	<input type='number' value='$did' name='actid'><br>
	<label>Your Activation Key</label><br>
	<input type='text' value='$actkey' name='actkey'><br>
	<input type='text' value='$mainurl' name='mainurl' readOnly><br>
	<input type='text' value='$dstat' readOnly><br>
	<input type='button' value='Activate' name='setactivation' class='setactivation1'>
	
	</form>
	";
	echo'
	<script>
	
jQuery(".setactivation1").click(function(){
	jQuery("#cover-spin").show();
var obj = {};
var toatl_input = jQuery(".setactivation input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".setactivation input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
	
}

jQuery.ajax({
  url: "'.esc_url(plugins_url('vtupress/vend.php')).'",
  data: obj,
  dataType: "json",
  cache: false,
  async: true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
		var text = "";
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
			text = jqXHR.responseText;
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
        if(data.status == "100" ){
	
		  swal({
  title: "Activated",
  text: "Activation Successful",
  icon: "success",
  button: "Okay",
}).then((value) => {
	console.log("vtupress-response: 100");
	location.reload();
});
	  }
	  else{
		  
	jQuery("#cover-spin").hide();
	
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Activation Wasn\'t Successful",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
	console.log("vtupress-response: "+data.message);
      swal(data.message, {
      icon: "info"
    });
  }
});
	
	
	
	  }
  },
  type: "POST"
});

});
	</script>
	';
	
}



return ob_get_clean();
?>