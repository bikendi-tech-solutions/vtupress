<?php
set_time_limit(3000);
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH.'wp-admin/includes/plugin.php');

global $current_timestamp;
$current_timestamp = current_time('timestamp');

if(isset($_GET)){
	foreach($_GET as $key => $val){
		$_GET[$key] = sanitize_text_field($val);
	}
}elseif(isset($_POST)){
	foreach($_POST as $key => $val){
		$_POST[$key] = sanitize_text_field($val);
	}
}elseif(isset($_REQUEST)){
	foreach($_REQUEST as $key => $val){
		$_REQUEST[$key] = sanitize_text_field($val);
	}
}else{}


if(file_exists(__DIR__."/do_not_tamper.php")){

do_action( 'litespeed_control_set_nocache', 'nocache due to logged in' );

if(!isset($_GET["vend"])){
	$_GET["vend"] = "dashboard";
}

///BEGINNING OF UPDATE AND ADD


//error_log(date("Y-m-d h:i:s")." -- ".current_time('mysql'),0);
$path = WP_PLUGIN_DIR.'/vtupress/functions.php';
if(!function_exists("vp_updateuser") || is_plugin_active("vtupress/vtupress.php")){
function vp_updateuser($id="",$meta="",$value=""){
	$update_meta = get_user_meta($id,"vp_user_data",true);
	if(empty($update_meta)){
add_user_meta($id,"vp_user_data",'{"default":"yes"}');
	}
$array = json_decode($update_meta,true);

$array[$meta] = $value;

update_user_meta($id,"vp_user_data",json_encode($array));
return "true";

}

function vp_updateoption($meta="",$value=""){
	
add_option("vp_options",'{"default":"yes"}');
$array = json_decode(get_option("vp_options"),true);

$array[$meta] = $value;

update_option("vp_options",json_encode($array));
return "true";
}

///END OF UPDATE AND ADD

///BEGINNING OF GET
function vp_getoption($meta=""){
$value = get_option($meta);
$val = get_option("vp_options");
if($meta != "siteurl" && $meta != "blogname" && $meta != "home" && $meta != "admin_email" && $meta != "blogdescription"){
if($value !== false && $meta != "vp_options"){
	
	vp_updateoption($meta,$value);
	delete_option($meta);
$array = json_decode($val,true);

if(isset($array[$meta])){
return $array[$meta];
}
else{
return "false";
}
}
else{
	if($val !== false){
		$array = json_decode($val,true);
		if(isset($array[$meta])){
return $array[$meta];
}
else{
return "false";
}
	}
	else{
	return "false";	
	}
}

}
else{
	return $value;
}

}

function vp_getuser($id="",$meta="",$single = true){
	$getdata = get_user_meta($id,"vp_user_data",true);
	$get_meta = get_user_meta($id,$meta,true);
	//check if user meta exist explicitly on wp_usermeta
if($meta != "vp_user_data" && (!empty($get_meta) || $get_meta == "0") ){//if exist
	$value = $get_meta;//get value
	vp_updateuser($id,$meta,$value);//add it to the vp_user_data of the user ID
	delete_user_meta($id,$meta);//Delete The user meta from being explicit on wp_usermeta
	
$array = json_decode($getdata,true); //Now Get The vp_user_data and convert to an array

if(isset($array[$meta])){// If meta array key exist, return the value
return $array[$meta];
}
else{//else return false
return "false";
}
}
else{
	if(!empty($getdata)){ //check if user already has private vp_user_data
		$array = json_decode($getdata,true);//convert to array
		if(isset($array[$meta])){//check if meta exist
return $array[$meta];//return value
}
else{ //else return false
return "false";
}
	}
	else{
	return "false";	
	} //if no vp_user_data return false
}
}

function vp_option_array($array= array(),$meta=""){
if(!empty($array[$meta])){
	return $array[$meta];
}
else{
	vp_getoption($meta);
}
}


function vp_user_array($array=array(),$id="",$meta="",$single = true){
if(!empty($array[$meta])){
	return $array[$meta];
}
else{
	vp_getuser($id,$meta,$single);
}
}
///END OF GET
function vp_deleteuser($id=""){
	delete_user_meta($id,"vp_user_data");
	return "true";
}

function vp_adduser($id="",$meta="",$value=""){
	$ths = get_user_meta($id,"vp_user_data",true);
if(empty($ths)){
add_user_meta($id,"vp_user_data",'{"default":"yes"}');
	}

$array = json_decode($ths,true);
if(isset($array[$meta])){
	return "false";
}
else{
	$array[$meta] = $value;

update_user_meta($id,"vp_user_data",json_encode($array));
return "true";
}

}

function vp_addoption($meta="",$value=""){
$array = json_decode(get_option("vp_options"),true);
if(isset($array[$meta])){
	return "false";
}
else{
	$array[$meta] = $value;

update_option("vp_options",json_encode($array));
return "true";
}

}

}


function vp_get_contents($url){
	$call =  wp_remote_get($url);
	$response =  wp_remote_retrieve_body($call);
	return $response;
}
function pagination_before($name="",$altname="",$dbname="",$var="none",$where=""){
	  
 global $post, $wpdb, ${$var};


$limit = isset($_REQUEST["".$altname.$name."-limit-records"]) ? $_REQUEST["".$altname.$name."-limit-records"] : 10;
$page = isset($_GET[''.$altname.$name.'-page']) ? $_GET[''.$altname.$name.'-page'] : 1;
$start = ($page - 1) * $limit;


    $table_name = $wpdb->prefix.$dbname;
	${$var} = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");
	
	if(!isset(${$var})){
		ECHO "NO DATABASE FOUND FOR {$var}";
	}
	elseif(empty(${$var})){
		ECHO "{$var} IS EMPTY";
	}
	
    #${$var} = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name $where ORDER BY %s DESC LIMIT $start, $limit", 'ID'));
	#SELECT * FROM `wpzl_sairtime` ORDER BY ID DESC LIMIT 10
	$num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
	$pages = ceil( $num  / $limit );
	
	$cur_page = $_GET['page'];
	
	$Previous = $page - 1;
	$Next = $page + 1;
	echo '
	<div class="container well">
		<div class="row mt-3 md-2">
			<div class=" col-md-10">
				<nav aria-label="Page navigation">
					<ul class="pagination">
				    <li class="mx-2">
				      <a href="?page='.$cur_page.'&'.$altname.$name.'-page='.$Previous.'&'.$altname.$name.'-limit-records='.$limit.'#'.$name.'" aria-label="Previous">
				        <span aria-hidden="true">&laquo; Previous</span>
				      </a>
				    </li>
					';
				     for($i = 1; $i<= $pages; $i++){
						 if(isset($_GET["".$altname.$name."-page"]) && $_GET["".$altname.$name."-page"] == $i){
							 $color = "text-danger";
						 }else{
							 $color = "text-primary";
						 }
						 echo'
				    	<li class="border border-primary px-2"><a class="'.$color.'" href="?page='.$cur_page.'&'.$altname.$name.'-page='. $i.'&'.$altname.$name.'-limit-records='.$limit.'#'.$name.'">'. $i .'</a></li>
						';
}

echo'
				    <li class="mx-2">
				      <a href="?page='.$cur_page.'&'.$altname.$name.'-page='. $Next.'&'.$altname.$name.'-limit-records='.$limit.'#'.$name.'" aria-label="Next">
				        <span aria-hidden="true">Next &raquo;</span>
				      </a>
				    </li>
				  </ul>
				</nav>
			</div>
			<div class="text-center col-md-2">
				<form >
				<div class="input-group">
				<span class="input-group-text">Limit</span>
						<select class="" name="'.$altname.$name.'-limit-records" id="'.$altname.$name.'-limit-records">
						';
						
							foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
								 if( isset($_GET["".$altname.$name."-limit-records"]) && $_GET["".$altname.$name."-limit-records"] == $limit){
								 $echo = "selected";
								 }
								 else{
									 $echo = "opt";
								 }
								echo'
								<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
								';
							}
							echo'
						</select>
						</div>
				</form>
			</div>
		</div>
		<div>
';
	
}



function pagination_after($name="",$altname="",$dbname ="none"){


	$cur_page = $_GET['page'];
echo '
</div>

</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("select#'.$altname.$name.'-limit-records").change(function(){
			var val = $("select#'.$altname.$name.'-limit-records").val();
			window.location = "?page='.$cur_page.'&'.$altname.$name.'-page=1&'.$altname.$name.'-limit-records="+val+"#'.$name.'";
		});
	});
</script>

';


}



function pagination_before_front($url="", $name="",$altname="",$dbname="",$var="none",$where=""){
	  
 global $post, $wpdb, ${$var};


$limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
$page = isset($_GET['pages']) ? $_GET['pages'] : 1;
$start = ($page - 1) * $limit;

$recipient = "";

if(isset($_GET["recipient"])){
	if(isset($_GET["type"])){
		$type = $_GET["type"];
		$recipient = $_GET["recipient"];
		if($type == "airtime" || $type == "data"){
			$where .=" AND phone LIKE '%$recipient%'";
		}
		elseif($type == "cable" || $type == "sms"){
			$where .= "AND recipient LIKE '%$recipient%'";
		}
		elseif($type == "bill"){
			$where .= "AND iucno LIKE '%$recipient%'";
		}
	}
}

    $table_name = $wpdb->prefix.$dbname;
	${$var} = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");
	
    #${$var} = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name $where ORDER BY %s DESC LIMIT $start, $limit", 'ID'));
	#SELECT * FROM `wpzl_sairtime` ORDER BY ID DESC LIMIT 10
	$num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
	$pages = ceil( $num  / $limit );
	

	
	$Previous = $page - 1;
	$Next = $page + 1;
	echo '
	<div class="container well">
		<div class="row mt-3 md-2">
			<div class=" col-md-9 table-responsive pe-2">

			<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
	if(isset($_GET["pages"]) && $_GET["pages"] == $i){
		$color = "text-danger";
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
		$color = "text-primary";
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}

$for = $_GET["for"];
$type = $_GET["type"];
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();

  function changepage(){
	var pg = jQuery(".change-page").val();
window.location.href = "'.$url.'&for='.$for.'&type='.$type.'&pages="+pg+"&limit-records='.$limit.'";
  }
  </script>
  </div>
';



				  echo'
			</div>
			<div class="text-center col-md-3">
				<form >
				<div class="input-group">
				<span class="input-group-text">Limit</span>
						<select class="" name="limit-records" id="limit-records">
						';
						
							foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
								 if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
								 $echo = "selected";
								 }
								 else{
									 $echo = "opt";
								 }
								echo'
								<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
								';
							}
							echo'
						</select>
						</div>
				</form>
			</div>
		</div>


		<div class="row">
							<div class="col">
							<div class="input-group">

							<input type="text" class="form-control border-end-0 border rounded-pill search-trans" placeholder="Search By Recipient Number" value="'.$recipient.'"/>
							<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>

<script>
function searchtrans(value){
	var pg = jQuery(".change-page").val();

	//alert("'.$where.'");
	window.location.href = "'.$url.'&for='.$for.'&type='.$type.'&pages="+pg+"&limit-records='.$limit.'&recipient="+value;
}
</script>
							</div>
		</div>
		<div>

		</div>
';
	
}


function pagination_after_front($url="",$name="",$altname="",$dbname ="none"){


	
echo '
</div>

</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("select#limit-records").change(function(){
			var val = $("select#limit-records").val();
			window.location = window.location.href+"&limit-records="+val;
		});
	});
</script>

';


}



function vtupress_js_css_user(){
	
	echo'
	<script src="'.esc_url(plugins_url("vtupress/js/bootstrap.min.js")).'"></script>
	<script src="'.esc_url(plugins_url("vtupress/js/jquery.js")).'"></script>
	<script src="'.esc_url(plugins_url("vtupress/js/sweet.js")).'"></script>
	<script src="'.esc_url(plugins_url("vtupress/js/pdf.js")).'"></script>
	<script src="'.esc_url(plugins_url("vtupress/js/print.js")).'"></script>
	<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/bootstrap.min.css")).'">
	<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/font-awesome.min.css")).'">
	<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/print.css")).'">
	<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/all.min.css")).'">
	
	';
}

add_action( 'wp_enqueue_scripts', 'vtupress_js_css_user' );

function vtupress_js_css_user_plain(){

echo'
<script src="'.esc_url(plugins_url("vtupress/js/bootstrap.min.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/jquery.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/sweet.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/pdf.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/print.js?v=1")).'"></script>
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/all.min.css?v=1")).'">
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/print.css?v=1")).'">
';


}

function vtupress_js_css_user_plain_admin(){


echo'
<script src="'.esc_url(plugins_url("vtupress/js/jquery.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/bootstrap.min.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/sweet.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/pdf.js?v=1")).'"></script>
<script src="'.esc_url(plugins_url("vtupress/js/print.js?v=1")).'"></script>
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/bootstrap.min.css?v=1")).'">
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/font-awesome.min.css?v=1")).'">
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/all.min.css?v=1")).'">
<link rel="stylesheet" href="'.esc_url(plugins_url("vtupress/css/print.css?v=1")).'">
';


}








vp_addoption("vp_whatsapp_group","link");

vp_addoption("vp_template","default");

$vp_temp = vp_getoption("vp_template");

if(vp_getoption("resell") != "yes"){
	$vp_temp = "default";
}
elseif($vp_temp != "default" && $vp_temp != "classic" && !is_plugin_active("$vp_temp/$vp_temp.php")){
	$vp_temp = "default";
}
else{
	$vp_temp = vp_getoption("vp_template");
}


define('vtupress_template',$vp_temp);


function vp_kyc_update(){
	global $current_timestamp;
	$id = get_current_user_id();

$option_array = json_decode(get_option("vp_options"),true);
$user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
$kyc_status = vp_user_array($user_array,$id,'vp_kyc_status',true);
$kyc_end = vp_user_array($user_array,$id,'vp_kyc_end',true);
$kyc_total = vp_user_array($user_array,$id,'vp_kyc_total',true);

global $wpdb;
$table_name = $wpdb->prefix."vp_kyc_settings";
$kyc_data = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");

if(strtolower($kyc_status) != "verified" && strtolower($kyc_data[0]->enable) == "yes"){
$datenow = date("Y-m-d",$current_timestamp); #date('Y-m-d',strtotime($date." +3 days"));
$next_end_date = $kyc_end;
		if($datenow < $next_end_date || $next_end_date == "0" || empty($next_end_date)){ //check if current date is less than next or if next is zero
		
			if($next_end_date == "0" || empty($next_end_date)){
				//echo "set next_end_date to datenow + limit";
				
				if(strtolower($kyc_data[0]->duration) == "day"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 days")));
				vp_updateuser($id,'vp_kyc_total',"0");
				}
				elseif(strtolower($kyc_data[0]->duration) == "month"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 month")));
				vp_updateuser($id,'vp_kyc_total',"0");
				}
			}
		}
			elseif($datenow >= $next_end_date){
			if(strtolower($kyc_data[0]->duration) == "day"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 days")));
				vp_updateuser($id,'vp_kyc_total',"0");
				}
				elseif(strtolower($kyc_data[0]->duration) == "month"){
				vp_updateuser($id,"vp_kyc_end",date('Y-m-d',strtotime($datenow." +1 month")));
				vp_updateuser($id,'vp_kyc_total',"0");
				}
			//echo "Permit Transaction";
		}

$kyc_status = vp_user_array($user_array,$id,'vp_kyc_status',true);
$kyc_end = vp_user_array($user_array,$id,'vp_kyc_end',true);
$kyc_total = vp_user_array($user_array,$id,'vp_kyc_total',true);
if(empty($kyc_total)){
	$used  = 0;
}
else{
	$used = $kyc_total;
}

$allowed = ucfirst($kyc_data[0]->duration);
if($allowed == "Total"){
	
	$tot = "Total";
}
else{
	$tot = "One $allowed";
}

$limit = $kyc_data[0]->kyc_limit;
echo"
<div class='row my-3'>
<div class='col font-bold text-white bg bg-danger p-3 rounded shadow'>
You have consumed ₦$used out of ₦$limit in $tot 
<br>
<small>Please verify your account <b><a style='text-decoration:none;' class='text-white' href='?vend=kyc'>{ Here }</a></b></small>
</div>
</div>

";

}


}



function vp_getvalue($value=""){
	global $current_timestamp;
if(isset($value)){
	return $value;
}
else{
	return "No Value";
}
	
}


if(strtolower(vp_getoption('enable-schedule')) == "yes"){
$time = date('h:i:s',$current_timestamp);
if(vp_getoption('next-schedule') <= $time ){
vp_updateoption('last-schedule',$time);
wp_remote_get(get_home_url()."/wp-content/plugins/vtupress/query.php");
$now = date('h:i:s', strtotime(date('h:i:s',$current_timestamp). '+'.intval(vp_getoption("schedule-time")).' Minutes'));
vp_updateoption("next-schedule", $now);
}


}


function vp_transaction_email($subject="", $topic="",$transaction="",$purchased="", $recipient="", $amount="", $prev="",$now="", $admin = true, $user = true){

	$verify_email = strtolower(vp_getoption("email_transaction"));
	$id = get_current_user_id();
	$username = get_userdata($id)->user_login;
	$user_email = get_userdata($id)->user_email;

	if($verify_email != "false" && $verify_email != "no" ){
	$email_headers = array('Content-Type: text/html; charset=UTF-8');

	
	
	if($admin){
		$admin_email = get_option("admin_email");
	$subject = "ADMIN NOTICE: $subject";
	
	$message = <<<EOB
	
	<div style="height:fit-content">
	<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
	<span style="" > $topic </span>
	
	</div>
	<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">
	
	<p>Username: $username</p>
	<p>Email: $user_email</p>
	<p>Transaction ID: $transaction</p>
	<p>Purchased: $purchased</p>
	<p>Recipient:  $recipient</p>
	<p>Total Amount:  ₦$amount</p>
	</div>
	<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
	<b style="float:left" > Previous: $prev</b> <b style="float:right" >Now: $now </b>
	
	</div>
	
	</div>
	
	EOB;
	wp_mail($admin_email,$subject,$message,$email_headers);
	}
	else{
		
	}
	
	if($user){
		
	$subject = "$subject";
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$message = <<<EOB
	
	<div style="height:fit-content">
	<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
	<span style="" > $topic </span>
	
	</div>
	<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">
	
	<p>Username: $username</p>
	<p>Email: $user_email</p>
	<p>Transaction ID: $transaction</p>
	<p>Purchased: $purchased</p>
	<p>Recipient:  $recipient</p>
	<p>Total Amount:  ₦$amount</p>
	</div>
	<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
	<b style="float:left" > Previous: $prev</b> <b style="float:right" >Now: $now </b>
	
	</div>
	
	</div>
	
	EOB;
	wp_mail($user_email,$subject,$message,$email_headers);
	}
	else{
		
	}

	$http_args = array(
		'headers' => array(
		'Content-Type' => 'application/json'
		),
		'timeout' => '120',
		'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
		'sslverify' => false
		);	


	$site = substr(get_bloginfo('name'), 0, 10);
	$message = $purchased;
	$message = str_replace("MTN","M|N",$message);
	$message = str_replace("GLO","G|O",$message);
	$message = str_replace("AIRTEL","A|RTEL",$message);
	$message = str_replace("9MOBILE","9MOB|LE",$message);
	$message = str_replace("₦","NGN",$message);
	$message = str_replace("cash","cach",$message);
	$message .= " by $username";
	
	$token = vp_getoption("smspostvalue1");

	if(strtolower(vp_getoption("sms_transaction_admin")) == "yes" && !empty($token)){


		if(stripos(vp_getoption("smsbaseurl"),"bulksmsnigeria") !== false && (stripos($topic,"airtime") !== false || stripos($topic,"data") !== false) ){


$phone = "0".vp_getoption("vp_phone_line");
$response =  wp_remote_get("https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=$token&from=$site&to=$phone&body=$message&dnd=1",$http_args);


		}


	}

	
	if(strtolower(vp_getoption("sms_transaction_user")) == "yes" && !empty($token)){


	
		if(stripos(vp_getoption("smsbaseurl"),"bulksmsnigeria") !== false && (stripos($topic,"airtime") !== false || stripos($topic,"data") !== false) ){

	
$phone = vp_getoption($id,'vp_phone',true);
$response =  wp_remote_get("https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=$token&from=$site&to=$phone&body=$message&dnd=1",$http_args);

		}
	
	
	}
	
	
	}
	
	}


	function vp_admin_email($subject="", $message="",  $type="", $link="#"){
global $current_timestamp;
		$uuid = get_current_user_id();
		global $wpdb;
		$sd_name = $wpdb->prefix.'vp_notifications';
		$wpdb->insert($sd_name, array(
		'user_id'=> $uuid ,
		'title' => $subject,
		'type' => $type,
		'admin_link' => "",
		'user_link' => "",
		'message'=> $message,
		'status' => "unread",
		'the_time' => date('Y-m-d H:i:s A',$current_timestamp)
		));
		
			$admin_email = get_option("admin_email");
			$email_headers = array('Content-Type: text/html; charset=UTF-8');
		
		$message = <<<EOB
		
		<div style="height:fit-content">
		<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
		<span style="" > $subject </span>
		
		</div>
		<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">
	
		<p>$message</p>

		</div>
		<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >

		</div>
		
		</div>
		
		EOB;
		wp_mail($admin_email,$subject,$message,$email_headers);
	
		
		
		}

function vp_ban_email(){

$email_headers = array('Content-Type: text/html; charset=UTF-8');
$id = get_current_user_id();
$username = get_userdata($id)->user_login;
$user_email = get_userdata($id)->user_email;

$admin_email = get_option("admin_email");
$subject = "ADMIN BAN NOTICE: Hacker Detected And Banned";

$message = <<<EOB

<div style="height:fit-content">
<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
<span style="" > New Hacker Detected And Banned </span>

</div>
<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

<p>Username: $username</p>
<p>Email: $user_email</p>
<p>User With The Above Details Has Been Banned Due To A Suspicious Occurence On Account</p>
</div>
<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
<b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>

</div>

</div>

EOB;
wp_mail($admin_email,$subject,$message,$email_headers);

	
$subject = "NEW BAN NOTICE";
$headers = array('Content-Type: text/html; charset=UTF-8');
$message = <<<EOB

<div style="height:fit-content">
<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
<span style="" > You Are Banned!!! </span>

</div>
<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

<p>Username: $username</p>
<p>Email: $user_email</p>
<p>You Are Banned Due To A Suspicious Occurence On Your Account! Kindly Contact Admin If This Decision Is A Mistake</p>
</div>
<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
<b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>

</div>

</div>

EOB;
wp_mail($user_email,$subject,$message,$email_headers);





}


function vp_sessions(){

#Destroy Sessions
if(is_user_logged_in() && !current_user_can('administrator') && !current_user_can('vtupress_admin')){
$user_id = get_current_user_id();
$sessions = WP_Session_Tokens::get_instance( $user_id );
$sessions->destroy_others(  wp_get_session_token() );
}
else{
	
}
		

}



function vp_block_user($reason = "none"){
	global $wpdb;
if(is_user_logged_in() && !current_user_can("administrator")){
	$id = get_current_user_id();
	vp_updateuser($id,'vp_user_access',"ban");
	
$arr = ['vp_ban' => 'ban' ];
$where = ['ID' => $id];
$updated = $wpdb->update($wpdb->prefix."users", $arr, $where);

if($reason == "none"){
$reason = "Due To A Suspicious Occurence";
}
else{
	$reason .="FOR $reason";
}

$email_headers = array('Content-Type: text/html; charset=UTF-8');
$id = get_current_user_id();
$username = get_userdata($id)->user_login;
$user_email = get_userdata($id)->user_email;

$admin_email = get_option("admin_email");
$subject = "ADMIN BAN NOTICE: Hacker Detected And Banned";

$message = <<<EOB

<div style="height:fit-content">
<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
<span style="" > New Hacker Detected And Banned </span>

</div>
<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

<p>Username: $username</p>
<p>Email: $user_email</p>
<p>User With The Above Details Has Been Banned $reason On Account</p>
</div>
<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
<b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>

</div>

</div>

EOB;
wp_mail($admin_email,$subject,$message,$email_headers);

	
$subject = "NEW BAN NOTICE";
$headers = array('Content-Type: text/html; charset=UTF-8');
$message = <<<EOB

<div style="height:fit-content">
<div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
<span style="" > You Are Banned!!! </span>

</div>
<div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

<p>Username: $username</p>
<p>Email: $user_email</p>
<p>You Are Banned $reason On Your Account! Kindly Contact Admin If This Decision Is A Mistake</p>
</div>
<div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
<b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>

</div>

</div>

EOB;
wp_mail($user_email,$subject,$message,$email_headers);





}
}

function vp_remote_post_fn($url="", $headers="", $datass=""){

	global $added_to_db,$wpdb, $table_trans, $uniqidvalue, $return_message;
	$arra = [];
	foreach($headers as $key => $value){
		if(!empty($key) && !empty($value) && isset($key) && strtolower($key) != "content-type"){
		$arra[] = $key.": ".$value;
		}
	}

	$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => $url,
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => $datass,
CURLOPT_HTTPHEADER => $arra,
));
$response = curl_exec($curl);

$provider_header_response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);


if($provider_header_response >= 100 && $provider_header_response <= 199 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned an Informative Http Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 200 && $provider_header_response <= 299 ){
	$message = NULL;
}
elseif($provider_header_response >= 300 && $provider_header_response <= 399 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Redirection Http Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 400 && $provider_header_response <= 499 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Client Error Response Status Code [$provider_header_response]";
}
elseif($provider_header_response >= 500 && $provider_header_response <= 599 ){
	$message = "There must be something wrong with the provider I am connected to. \n It returned a Server Error Response Status Code [$provider_header_response]";
}
else{
	$message = "I can't identify the issue with the provider i am connected to [$provider_header_response]";
}

if($message !== NULL){

	if(is_numeric($added_to_db)){
	 
		 $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
	}


	//die($message);
	return "error";
}


return $response;

}

function vp_die($string=""){
	global $current_timestamp;
	$url = $_SERVER["REQUEST_URI"]."?page=vtupanel&adminpage=upgrade";
	$url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;
}

vp_addoption("vp_check_date", date("Y-m-d h:i",$current_timestamp));

//global $array;

function vtupress_user_details(){
	global $current_timestamp;
	if(is_user_logged_in()){
	$id = get_current_user_id();
	$array["user_id"] = $id;
	$array["name"] = get_userdata($id)->user_login;
	$array["email"] = get_userdata($id)->user_email;
	
	
	$option_array = json_decode(get_option("vp_options"),true);
	$user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
	
	$array["phone"] = vp_user_array($user_array,$id,"vp_phone",true);
	$array["pin"] = vp_user_array($user_array,$id,"vp_pin",true);
	
	$array["option_array"] = $option_array;
	$array["user_array"] = $user_array;
	
	$array["kyc_status"] = vp_user_array($user_array,$id,'vp_kyc_status',true);
	$array["kyc_end"] = vp_user_array($user_array,$id,'vp_kyc_end',true);
	$array["kyc_total"] = vp_user_array($user_array,$id,'vp_kyc_total',true);
	
	global $wpdb;
	$table_name = $wpdb->prefix."vp_kyc_settings";
	$array["kyc_data"] = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
	
	
	$array["admin_whatsapp"] = vp_option_array($option_array,"vp_whatsapp");
	
	
	$my_pv = intval(get_userdata($id)->vp_user_pv);
	global $wpdb;
	$tab = $wpdb->prefix.'vp_pv_rules';
	#$max_required_pv = $wpdb->get_results("SELECT * FROM $tab WHERE required_pv = (SELECT MAX(required_pv) FROM $tab) ORDER BY required_pv DESC LIMIT 1");
	$rules = $wpdb->get_results("SELECT * FROM $tab WHERE required_pv <= $my_pv ORDER BY required_pv DESC LIMIT 1");
	
	foreach($rules as $rule){
		if($my_pv >= $rule->required_pv && $rule->id != vp_user_array($user_array,$id, "vp_pv_limit", true) && strtolower($rule->upgrade_plan) != "none"){
			$get_plan = $rule->upgrade_plan;
			$get_bonus = $rule->upgrade_balance;
	vp_updateuser($id,"vr_plan", $get_plan);
	
	$give_bal = floatval(vp_user_array($user_array,$id, "vp_bal", true)) + intval($get_bonus);
	
	vp_updateuser($id,"vp_bal", $give_bal );
	vp_updateuser($id,"vp_pv_limit", $rule->id );
	
		}
	}
	
	$array["bal"] = vp_getuser($id, "vp_bal", true);
	$array["balance"] = $array["bal"];
	$array["myplan"] = vp_getuser($id, 'vr_plan', true);
	
	
	$dplan = $array["myplan"];
	
	global $wpdb;
	$table_name = $wpdb->prefix."vp_levels";
	global $level, $levels;
	$array["level"] = $wpdb->get_results("SELECT * FROM $table_name WHERE name = '$dplan'");

//DO MEMBERSHIP RULES
global $wpdb;

$memRuleTable = $wpdb->prefix."vp_membership_rule_stats";
$membership_rule = $wpdb->get_results("SELECT * FROM $memRuleTable WHERE user_id = '$id' ORDER BY ID DESC LIMIT 1");

if($membership_rule != NULL && !empty($membership_rule)){

#Expected total no. of users
$expTotUsers = intval($array["level"][0]->monthly_referee);
#Expected total no. of transactions
$expTotTrans = intval($array["level"][0]->monthly_transactions_number);
#Expected total amount of transactions
$expTotAmount= intval($array["level"][0]->monthly_transactions_amount);

#Get Total transaction_number
$total_ref = intval($membership_rule[0]->ref);
$transNo =  intval($membership_rule[0]->transaction_number);
$transAmount =  intval($membership_rule[0]->transaction_amount);

//Timing
$current_date = date("Y-m-d",$current_timestamp);
$start_count = $membership_rule[0]->start_count;
$one_month_after = date("Y-m-d",strtotime($start_count."+1 month"));

if($current_date > $one_month_after){

if($expTotUsers > $total_ref || $expTotTrans > $transNo || $expTotAmount > $transAmount){
	vp_updateuser($id, 'vr_plan', "customer");

}

$data = [
	'user_id' => $id,
	'ref' => 0,
	'transaction_number' => 0,
	'transaction_amount' => 0,
	'start_count' => $current_date
  ];
$wpdb->insert($memRuleTable,$data);

}
}










	$array["levels"] = $wpdb->get_results("SELECT * FROM $table_name");
	$level = $array["level"];
	global $current_timestamp;
	if($level != NULL && !empty($level) && vp_option_array($option_array,"vtupress_custom_mlmsub") == "yes"){
		if(isset($level[0]->monthly_sub)){

			if($level[0]->monthly_sub == "yes"){
				if(vp_user_array($user_array,$id, "vp_monthly_sub",true) != "false" && !empty(vp_user_array($user_array,$id, "vp_monthly_sub",true))){
					$last_sub = vp_user_array($user_array,$id, "vp_monthly_sub",true);
					$one_month = date("Y-m-d H:i:s", strtotime($last_sub."+1month"));

					if(date("Y-m-d H:i:s") > $one_month){

						
						$all_my_plans = str_replace($dplan,"",vp_getuser($id, "all_my_plans", true));

						vp_updateuser($id, "all_my_plans", $all_my_plans );
						vp_updateuser($id,'vr_plan', "customer");

						//die($last_sub." = ".$one_month);
					}
				}else{
						vp_updateuser($id,'vp_monthly_sub', date("Y-m-d H:i:s",$current_timestamp));
				}
			}

		}
	}
	$levels = $array["levels"];
	
	$array["mess"] = vp_option_array($option_array,"vpwalm");
	$array["notification"] = $array["mess"];
	
	$array["vtudiscounts"] = max(array(floatval(vp_option_array($option_array,"vtu_mad")),floatval(vp_option_array($option_array,"vtu_gad")),floatval(vp_option_array($option_array,"vtu_9ad")),floatval(vp_option_array($option_array,"vtu_aad"))));
	$array["sharediscounts"] = max(array(floatval(vp_option_array($option_array,"share_mad")),floatval(vp_option_array($option_array,"share_gad")),floatval(vp_option_array($option_array,"share_9ad")),floatval(vp_option_array($option_array,"share_aad"))));
	$array["awufdiscounts"] = max(array(floatval(vp_option_array($option_array,"awuf_mad")),floatval(vp_option_array($option_array,"awuf_gad")),floatval(vp_option_array($option_array,"awuf_9ad")),floatval(vp_option_array($option_array,"awuf_aad"))));
	
	$vtudiscounts = $array["vtudiscounts"];
	$sharediscounts = $array["sharediscounts"];
	$awufdiscounts = $array["awufdiscounts"];
	$array["airtimediscount"] = max(array($vtudiscounts,$sharediscounts,$awufdiscounts ));
	
	
	$array["smediscounts"] = max(array(floatval(vp_option_array($option_array,"sme_mdd")),floatval(vp_option_array($option_array,"sme_gdd")),floatval(vp_option_array($option_array,"sme_9dd")),floatval(vp_option_array($option_array,"sme_ddd"))));
	$array["directdiscounts"] = max(array(floatval(vp_option_array($option_array,"direct_mdd")),floatval(vp_option_array($option_array,"direct_gdd")),floatval(vp_option_array($option_array,"direct_9dd")),floatval(vp_option_array($option_array,"direct_ddd"))));
	$array["corporatediscounts"] = max(array(floatval(vp_option_array($option_array,"corporate_mdd")),floatval(vp_option_array($option_array,"corporate_gdd")),floatval(vp_option_array($option_array,"corporate_9dd")),floatval(vp_option_array($option_array,"corporate_ddd"))));
	
	$smediscounts = $array["smediscounts"];
	$directdiscounts = $array["directdiscounts"];
	$corporatediscounts = $array["corporatediscounts"];
	$array["datadiscount"] = max(array($smediscounts,$directdiscounts,$corporatediscounts ));
	
	
	if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes"){
	$array["total_inref3_id"] = 	vp_user_array($user_array,$id, "vp_tot_in_ref3_id", true);

	$array["cur_suc_trans_amt"] = vp_user_array($user_array,$id, "vp_tot_trans_amt",true);
	$array["total_amount_of_successful_transactions"] = $array["cur_suc_trans_amt"];

	$array["ref"] = vp_user_array($user_array,$id, "vp_ref", true);
	$array["my_upline"] = $array["ref"];

	$array["refbo"] = vp_option_array($option_array,"refbo");

	$array["total_1st_downlines"] = vp_user_array($user_array,$id, "vp_tot_ref",true);
	$array["total_refered"] = $array["total_1st_downlines"];
	$array["total_inrefered"] = vp_user_array($user_array,$id, "vp_tot_in_ref",true);
	$array["total_2nd_downlines"] = $array["total_inrefered"];

	$array["total_inrefered3"] = vp_user_array($user_array,$id, "vp_tot_in_ref3",true);
	$array["total_other_downlines"] = $array["total_inrefered3"];

	$array["total_dir_earn"] = vp_user_array($user_array,$id, "vp_tot_ref_earn",true);
	$array["upgrade_bonus_from_1st"] = $array["total_dir_earn"];

	$array["total_indir_earn"] = vp_user_array($user_array,$id, "vp_tot_in_ref_earn",true);
	$array["upgrade_bonus_from_2nd"] = $array["total_indir_earn"];

	$array["total_indir_earn3"] = vp_user_array($user_array,$id, "vp_tot_in_ref_earn3",true);
	$array["upgrade_bonus_from_others"] = $array["total_indir_earn3"];

	$array["total_trans_bonus"] = vp_user_array($user_array,$id, "vp_tot_trans_bonus",true);
	$array["transaction_bonus"] = $array["total_trans_bonus"];

	$array["total_dirtrans_bonus"] = vp_user_array($user_array,$id, "vp_tot_dir_trans",true);
	$array["transactions_bonus_from_1st"] = $array["total_dirtrans_bonus"];

	$array["total_indirtrans_bonus"] = vp_user_array($user_array,$id, "vp_tot_indir_trans",true);
	$array["transactions_bonus_from_2nd"] = $array["total_indirtrans_bonus"];

	$array["total_indirtrans_bonus3"] = vp_user_array($user_array,$id, "vp_tot_indir_trans3",true);
	$array["transactions_bonus_from_others"] = $array["total_indirtrans_bonus3"];

	$array["total_trans_attempt"] = vp_user_array($user_array,$id, "vp_tot_trans",true);
	$array["total_transaction_attempted"] = $array["total_trans_attempt"];

	$array["total_suc_trans"] = vp_user_array($user_array,$id, "vp_tot_suc_trans",true);
	$array["total_successful_transactions"] = $array["total_suc_trans"];

	$array["total_trans_bonus"] = vp_user_array($user_array,$id, "vp_tot_trans_bonus",true);
	$array["total_transaction_bonus"] = $array["total_trans_bonus"];

	$array["total_withdraws"] = vp_user_array($user_array,$id, "vp_tot_withdraws",true);
	
	$total_dir_earn = $array["total_dir_earn"];
	$total_indir_earn = $array["total_indir_earn"];
	$total_indir_earn3 = $array["total_indir_earn3"];
	$total_trans_bonus = $array["total_trans_bonus"];
	$total_dirtrans_bonus = $array["total_dirtrans_bonus"];
	$total_indirtrans_bonus = $array["total_indirtrans_bonus"];
	$total_indirtrans_bonus3 = $array["total_indirtrans_bonus3"];
	
	$array["total_bal_with"] = intval($total_dir_earn) + intval($total_indir_earn) + intval($total_indir_earn3) + intval($total_trans_bonus) + intval($total_dirtrans_bonus) + intval($total_indirtrans_bonus) + intval($total_indirtrans_bonus3);
	$array["total_withdrawal_balance"] = $array["total_bal_with"];
	
	
	$array["minwithle"] = vp_option_array($option_array,"vp_min_withdrawal");
	$array["minimum_withdrawal_amount"] = $array["minwithle"];

	$array["ref_by"] = vp_user_array($user_array,$id, "vp_who_ref",true);
	$array["cheepa"] = 0;
	}
	
	$bank_mode = vp_user_array($user_array,$id,"account_mode",true);					
	$array["bank_mode"] = $bank_mode;
	if($bank_mode == "live"){
	
		$array["bank_ref"] = vp_user_array($user_array,$id,"bank_reference",true);
		$array["account_name"] = vp_user_array($user_array,$id,"account_name",true);
		$array["account_number"] = vp_user_array($user_array,$id,"account_number",true);
	
		if(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name",true), "wema"))){
			$array["bank_name"] = "WEMA";
		}
		elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name",true), "ster"))){
			$array["bank_name"] = "STERLING";
		}
		elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name",true), "mon"))){
			$array["bank_name"] = "MONNIEPOINT";
		}
		else{}
	
		if(!empty(vp_user_array($user_array,$id,"account_name1",true)) && vp_user_array($user_array,$id,"account_name1",true) != "false"){
			$array["account_name1"] = vp_user_array($user_array,$id,"account_name1",true);
			$array["account_number1"] = vp_user_array($user_array,$id,"account_number1",true);
	
			if(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name1",true), "wema"))){
				$array["bank_name1"] = "WEMA";
			}
			elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name1",true), "ster"))){
				$array["bank_name1"] = "STERLING";
			}
			elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name1",true), "mon"))){
				$array["bank_name1"] = "MONNIEPOINT";
			}else{}
	
			
				}else{
					$array["account_name1"] = "NULL";
					$array["account_number1"] = "NULL";
					$array["bank_name1"] = "NULL1";
	
				}
	
				
		if(!empty(vp_user_array($user_array,$id,"account_name2",true)) && vp_user_array($user_array,$id,"account_name2",true) != "false"){
			$array["account_name2"] = vp_user_array($user_array,$id,"account_name2",true);
			$array["account_number2"] = vp_user_array($user_array,$id,"account_number2",true);
	
			if(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name2",true), "wema"))){
				$array["bank_name2"] = "WEMA";
			}
			elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name2",true), "ster"))){
				$array["bank_name2"] = "STERLING";
			}
			elseif(is_numeric(stripos(vp_user_array($user_array,$id,"bank_name2",true), "mon"))){
				$array["bank_name2"] = "MONNIEPOINT";
			}else{}
				}else{
					$array["account_name2"] = "NULL";
					$array["account_number2"] = "NULL";
					$array["bank_name2"] = "NULL";
	
				}
	
	
	
	}
	else{
		$array["bank_ref"] = "NULL";
		$array["account_name"] = "NULL";
		$array["account_number"] = "NULL";
		$array["bank_name"] = "NULL";

		$array["bank_ref"] = "NULL";
		$array["account_name1"] = "NULL";
		$array["account_number1"] = "NULL";
		$array["bank_name1"] = "NULL";

		$array["bank_ref"] = "NULL";
		$array["account_name2"] = "NULL";
		$array["account_number2"] = "NULL";
		$array["bank_name2"] = "NULL";
	
	}
	
	$array["template_url"] = plugins_url("vtupress/template");
				
	return $array;
	}

}

	


//SHORTCODES BTWN PLUGIN USE ONLY
/*
vtupress_airtime == for airtime
vtupress_data == for data
vtupress_cable == for cable
vtupress_bill == for bill
*/
//GENERAL SHORTCODES
/*

[vtupress get="attr"]

where attr(s) are
:::::::::::::::::::::::::

----General
user_id = the current user id
name = the current username
email = the current user email
phone = the current user phone number
pin = the current user pin
admin_whatsapp = Admin whatsapp line
balance = current user balance
myplan = current user plan
notification = The user notification message
minimum_withdrawal_amount = Minimum withdrawal (OPTION)

---KYC:
kyc_status = the current user kyc status
kyc_end = when the user transaction limit will end after he/she uses up her allowance (for unverified)
kyc_total = total amount spent from daily allowance (for unverified)
kyc_data = (ARRAY) of kyc settings

---Levels/Plans Data
level = (ARRAY) of the current user plan settings
levels = (ARRAY) List of all plans


----AIRTIME DISCOINTS:
airtimediscounts = (MAX) discount in all networks in vtu,shared and awuf
vtudiscounts = (MAX) discount in all vtu networks
sharediscounts = (MAX) discount in all shared networks
awufdiscounts = (MAX) discount in all awuf networks

-----DATA DISCOUNTS:
datadiscounts = (MAX) discount in all networks in sme, shared and gifting
smediscounts = (MAX) discount in all sme networks
directdiscounts = (MAX) discount in all gifting networks
corporatediscounts = (MAX) discount in all corporate networks

-----
my_upline = current user upline
total_1st_downlines = Total numbers of first generational downlines
total_2nd_downlines = Total numbers of 2nd generational downlines
total_other_downlines = Total numbers of generations from 3rd level

upgrade_bonus_from_1st = Total commission earned from 1st level ref upgrades
upgrade_bonus_from_2nd = Total commission earned from 2nd level ref upgrades
upgrade_bonus_from_others = Total commission earned from 1st level ref upgrades
transaction_bonus = Total transaction bonus
transactions_bonus_from_1st = Total bonus earned from 1st level ref transactions
transactions_bonus_from_2nd = Total bonus earned from 1st level ref transactions
transactions_bonus_from_others = Total bonus earned from 3rd level and upward refs transactions
total_transaction_attempted = Total transactions attempted
total_successful_transactions = Total successful transactions
total_transaction_bonu = Total bonus earned from transactions
total_withdraws = Total number of withdrawals made
total_withdrawal_balance = Withdrawal balance
total_amount_of_successful_transactions = The total amount of successful transactions

*/


function dump_error($title="",$message=""){

$return = <<<EOT
	<!DOCTYPE html>
	<html>
	<head>
	<style>
	.container {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  height: 90vh; /* Adjust this value to fit your needs */
	  z-index: 9999999999;
	  line-height: 25px;
	}
	
	
	.main {
	  background-color: #412929;
	  padding: 1.5em;
	  color: white;
	  font-family: arial;
	  max-width: 500px;
	  font-size: x-large;
	}
	
	.head {
	  font-weight: 900;
	  margin-bottom: 5px;
	}
	
	.body {
	  padding-left: 1em;
	}
	</style>
	</head>
	<body>
	  <div class="container">
	  <div class="main">
	  <div class="head">
	$title
	  </div>
	
	  <div class="body">
	$message
	
	  </div>
	  </div>
	  
	</div>
	</body>
	</html>
EOT;
	
	echo $return;
	die();
	}


add_shortcode("vtupress",function($atts=""){
	$defaults = array_change_key_case(vtupress_user_details(), CASE_LOWER);

	$attr = array_change_key_case((array)$atts, CASE_LOWER);
if(array_key_exists($attr["get"],$defaults)){
return $defaults[$attr["get"]];
}
else{
return "key_not_found";
}


});




function vtupress_auto_override() {
	if(vp_getoption("vtupress_custom_custom") !== "yes"){
		return;
	}
	// Get the debug backtrace to find the file that called this function
	$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
	$original_file = $backtrace[0]['file'];
  
	// Normalize the path and trim to get relative path after plugins/
	$plugin_base = WP_PLUGIN_DIR . '/';
	$relative_path = str_replace($plugin_base, '', $original_file);
  
	// Path to the override file
	$custom_file = WP_CONTENT_DIR . '/vtupress-custom/' . $relative_path;
  
	// If override file exists, load it and stop
	if (file_exists($custom_file)) {
		require_once $custom_file;
		exit; // Prevent original file from continuing
	}
  
	// Otherwise, allow original file to continue
}

/*$datenow = date("Y-m-d H:i A");

if($datenow >= vp_getoption("vp_check_date")){
	$datenow = date("Y-m-d H:i A");
	  $next = date('Y-m-d H:i A',strtotime($datenow." +3 hours"));
  
	  
	  $systemarray = [];
	  $systemarray["cache-control"] = "no-cache";
	  $systemarray["content-type"] = "application/json";

	  $datass =  json_encode(array(
		"setactivation"=> "yes",
		"actid"=> vp_getoption('vpid'),
		"actkey"=> vp_getoption('actkey')
	  ));

	  
$rst = 	vp_remote_post_fn(esc_url(plugins_url("vtupress/vend.php")),$systemarray, $datass);

	vp_updateoption("vtupress_response", $rst);

	vp_updateoption("vp_check_date", $next);

  }*/




//do not delete
}
//do not add anything
