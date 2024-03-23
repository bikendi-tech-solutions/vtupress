<?php
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}

if(!is_user_logged_in()){
  die("Please Login");
}
if(isset($_REQUEST["set_token"])){
 $id = get_current_user_id();
 $username = get_userdata($id)->user_login;
 $token = $_REQUEST["set_token"];

    global $wpdb;
    $table_name = $wpdb->prefix.'vp_message';
    $resultfad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name ORDER BY %s ASC", 'ID'));
	
foreach($resultfad as $user){
if($user->user_id == $id && $token == $user->user_token){
	die("300");
}
elseif($user->user_id == $id && $token != $user->user_token) {
	global $wpdb;
$table_name = $wpdb->prefix.'vp_message';
$wpdb->update($table_name, array(
'user_id'=> $id,
'user_name' => $username,
'user_token' => $token
), array('user_id' => $id));

die("100");
}

}

global $wpdb;
$table_name = $wpdb->prefix.'vp_message';
$wpdb->insert($table_name, array(
'user_id'=> $id,
'user_name' => $username,
'user_token' => $token
));


die("100");
	
	
}

if(isset($_REQUEST["fcm_generate"])){

$file = fopen(ABSPATH."firebase-messaging-sw.js",'w');
$apikey = $_REQUEST["apikey"];
$domain = $_REQUEST["authdomain"];
$project = $_REQUEST["project"];
$sender = $_REQUEST["sender"];
$gcm = $_REQUEST["gcm"];

vp_updateoption("server_apikey",$apikey);
vp_updateoption("server_authdomain",$domain);
vp_updateoption("server_project",$project);
vp_updateoption("server_sender",$sender);
vp_updateoption("server_gcm",$gcm);

$txt = <<<HERE
importScripts('https://www.gstatic.com/firebasejs/4.9.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.9.1/firebase-messaging.js');
/*Update this config*/
var config = {
    apiKey: "$apikey",
    authDomain: "$domain",
    databaseURL: "",
    projectId: "$project",
    storageBucket: "",
    messagingSenderId: "$sender"
  };
  firebase.initializeApp(config);

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  const notificationTitle = payload.data.title;
  const notificationOptions = {
    body: payload.data.body,
	icon: '',
	image: ''
  };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
// [END background_handler]
HERE;

fwrite($file,$txt);
fclose($file);

$file1 = fopen("manifest.json",'w');

$txt = <<<HERE
{
  "gcm_sender_id": "$gcm"
}
HERE;

fwrite($file1,$txt);
fclose($file1);

vp_updateoption("fcm_generate","yes");
die("100");
}

if(isset($_REQUEST["fcm_enable"])){
$value = $_REQUEST["fcm_enable"];

vp_updateoption("fcm", $value);

die("100");	
	
}
?>