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
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


if(!isset($_POST)){
    die("Only Post");
}

if(!isset($_POST["lines"]) || !isset($_POST["message"])){
    die("Parameters Missing");
}

$lines = trim($_POST["lines"]);
$messages = trim($_POST["message"]);

//check lines

$eachLine = [];

if(preg_match("/,/",$lines)){
    $elines = explode(",",$lines);
    foreach($elines as $lin){
        if(strlen($lin) != 11){
            die("One or more lines is less/greater than 11");
        }
        else{
            $eachLine[] = $lin;
        }
    }
}
elseif(strtolower($lines) == "all"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');

   global  $wpdb, $users;
   $table_name = $wpdb->prefix."users";
   $use = $wpdb->get_results("SELECT * FROM  $table_name");

   foreach($use as $user){
        $id = $user->ID;
        $user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
        $phone = vp_user_array($user_array,$id, "vp_phone", true);
        if(strlen($phone) != 11){
            continue;
        }
        else{
            $eachLine[] = $phone;
        }


   }

}
elseif(is_numeric($lines)){
    if(strlen($lines) != 11){
        die("Recipient is less/greater than 11");
    }

    $eachLine[] = $lines;
}
else{
    die("Can't identify lines");
}


//input

foreach($eachLine as $line){

    $data = [
        "phone" => $line,
        "message" => wp_unslash($messages),
        "the_time" => date("Y-m-d H:i:s")
    ];

    $table_name = $wpdb->prefix.'vp_smsblaster';
    $wpdb->insert($table_name,$data);

}

die("100");