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
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/registry/function.php');
include_once(__DIR__."/register.php");

if(!isset($_REQUEST["operator"]) || !isset($_REQUEST["module"])){

    die("Missing Paramter");
}

$operator = trim($_REQUEST["operator"]);  //add or remove required
$module = trim($_REQUEST["module"]); //name of the module required


if(isset($_REQUEST["time"])){
    $time = trim($_REQUEST["time"]);
    switch($time){
        case"default":
            $schedule = "";
        break;
        case"custom":
            if(!isset($_REQUEST["schedule"])){
                die("schedule valid format is is needed");
            }
            elseif(!preg_match("/^\S+ \S+ \S+ \S+ \S+$/", $schedule)){
                die("invalid schedule format");
            }
            else{
                $schedule = $_REQUEST["schedule"];  
            }
            
        break;
        default:
            die("Time neither default nor custom");
        break;
    }
}else{
    $schedule = "";
}

if(isset($_REQUEST["path_mode"])){
    $mode = trim($_REQUEST["path_mode"]);
    
    switch($mode){
        case"default":
                $path_mode = "default";
                $path_value = $module;
        break;
        case"custom":
                $path_mode = "custom";
            if(!isset($_REQUEST["path_value"])){
                die("path_value not defined");
            }
            else{
                $path_value = $_REQUEST["path_value"];  
            }
        break;
        default:
            die("path $mode not valid");
        break;
    }
}else{
    $path_mode = "default";
    $path_value = $module;
}




switch($operator){
    case"add":
        $path = fetch__path($path_value, $path_mode,"cron");
        if( $path !== null){
                // Usage example:
            $result = createCronJob($path, $schedule); // Example schedule: every 5 minutes
            if ($result === true) {
                $response = "success";
            } elseif ($result === false) {
                $response = "failedA";
            } elseif ($result === "no_shell") {
                $response = "no_shell";
            } elseif ($result === "cant_remove") {
                $response = "cant_remove";
            }

        }else{

            $response = "InvalidP";

        }
    break;
    case"remove":
        $path = fetch__path($path_value, $path_mode,"cron");
        if($path !== null){
                $rm = removeCronJob($path);
            if($rm){
                $response = "success";
            }
            elseif ($rm === "no_shell") {
                $response = "no_shell";
            } elseif ($rm === "cant_remove") {
                $response = "cant_remove";
            }
            elseif($rm == null){
                $response = "failedR";
                
            }
        }
        else{

            $response = "InvalidP";

        }
    break;
    default:
    $response = "InvalidO";
    break;
}