<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-admin/includes/plugin.php');
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


$allowed_referrers = [
  $_SERVER['SERVER_NAME']
];

// Check if the referrer is set
if (isset($_SERVER['HTTP_REFERER'])) {
  $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
  
  // Check if the referrer is in the allowed list
  if (!in_array($referer, $allowed_referrers)) {
      die("REF ENT PERM");
  }
} else {
  die("BAD");
}


if(!is_user_logged_in()){
  die("Please Login");
}
elseif(!current_user_can("vtupress_admin")){
  die("Not Allowed");
}


if(!isset($_POST["spraycode"])){
  die("NO SPRAY CODE");
}
$spray_code = trim(htmlspecialchars($_POST["spraycode"]));
$real_code =  vp_getoption("spraycode");

if(empty($spray_code)){
  die("SPRAY CODE CAN'T BE EMPTY");
}
elseif(strtolower($spray_code) != "false"){

  if($real_code == "false"){
      $cur_id = get_current_user_id();
      $update_code = uniqid("vtu_$cur_id");
      vp_updateoption("spraycode",$update_code);
  }elseif($real_code != $spray_code ){
      die("INVALID SPRAYCODE");
  }else{
    //   die("INVALID SPRAYCODE");
  }
}elseif(strtolower($spray_code) == "false"){
  if($real_code == "false"){
      $cur_id = get_current_user_id();
      $update_code = uniqid("vtu_$cur_id");
      vp_updateoption("spraycode",$update_code);
  }elseif($real_code != $spray_code ){
      die("INVALID SPRAYCODE");
  }else{
    //   die("INVALID SPRAYCODE");
  }
}



function is_plugin_installe( $slug ) {
  if ( ! function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }
  $all_plugins = get_plugins();
   
  if ( !empty( $all_plugins[$slug] ) ) {
    return true;
  } else {
    return false;
  }
}
 
function install_plugin( $plugin_zip ) {
  include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
  wp_cache_flush();
   
  $upgrader = new Plugin_Upgrader();
  $installed = $upgrader->install( $plugin_zip );
 
  return $installed;
}
 
function upgrade_plugin( $plugin_slug ) {
  include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
  wp_cache_flush();
   
  $upgrader = new Plugin_Upgrader();
  $upgraded = $upgrader->upgrade( $plugin_slug );
 
  return $upgraded;
}


if(isset($_REQUEST["vpaction"])){

	$action = $_REQUEST["vpaction"];
//&vpaction=install&slug=bcmv%2Fbcmv.php&link=http%3A%2F%2Fdownload.5gmall.com.ng%2Fbcmv.zip

if($action = "Install"){
$link = $_REQUEST["link"];
$slug = $_REQUEST["slug"];
  // modify these variables with your new/old plugin values
  $plugin_slug = $_REQUEST["slug"];
  $plugin_zip = $link;
  if($plugin_slug != 'vtupress/vtupress.php'){
 $del =   delete_plugins( array( $plugin_slug ) );
  }
  else{
      $del = true;
  }
 if ($del === true){
  if ( is_plugin_installe( $plugin_slug ) ) {
 //   echo 'it\'s installed! Making sure it\'s the latest version.';
    upgrade_plugin( $plugin_slug );
    $installed = true;
  } else {
 //   echo 'it\'s not installed. Installing.';
  $installed = install_plugin( $plugin_zip );
  }
   
  if ( !is_wp_error( $installed ) && $installed ) {
  //  echo 'Activating new plugin.';
    $activate = activate_plugin( $plugin_slug );
     
    if ( is_null($activate) ) {
      //echo '<br>Deactivating old plugin.<br>';
      //deactivate_plugins( array( $old_plugin_slug ) );
       
    }
  die("100");
  } else {
die("200");
  }

}
else{
die("Unable To Complete The Function on Deletion");
}	
} 
	
}

?>