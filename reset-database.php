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
include_once(ABSPATH ."wp-load.php");
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
     //    die("INVALID SPRAYCODE");
    }
}elseif(strtolower($spray_code) == "false"){
    if($real_code == "false"){
        $cur_id = get_current_user_id();
        $update_code = uniqid("vtu_$cur_id");
        vp_updateoption("spraycode",$update_code);
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
       //  die("INVALID SPRAYCODE");
    }
}



if(isset($_REQUEST["reset"])){
	
if(isset($_REQUEST['wallet'])){
global $wpdb;
$table_name = $wpdb->prefix . 'vp_wallet';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);
die("100");
}


if(isset($_REQUEST['airtime'])){
global $wpdb;
$table_name = $wpdb->prefix . 'sairtime';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sdata';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fairtime';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fdata';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

//$airtime = vp_get_contents(esc_url(plugins_url('vtupress/install.php?vpaction=install&slug=vtupress/vtupress.php&link=https://vtupress.com/vtupress.zip')));
die("100");

}

if(isset($_REQUEST['bill'])){

global $wpdb;
$table_name = $wpdb->prefix . 'scable';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'sbill';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fcable';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

global $wpdb;
$table_name = $wpdb->prefix . 'fbill';
$sql = "DROP TABLE IF EXISTS $table_name";
$wpdb->query($sql);

	
$bill = vp_get_contents(esc_url(plugins_url('vtupress/install.php?vpaction=install&slug=bcmv/bcmv.php&link=https://vtupress.com/bcmv.zip')));

die("100");

}

die("200");
}

?>