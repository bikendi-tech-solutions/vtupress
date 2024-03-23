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
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

$apikey = $_POST["Authorization"];
$conid = $_POST["connectionid"];
$type = $_POST["verificationType"];
$domain = $_POST["domain"];
$charge = $_POST["charge"];
$eraptor = $_POST["enable"];

$payload = [
    "verificationType" => $type,
    "domain" => $domain
];

$http_args = array(
    'headers' => array(
    'Authorization' => "Token $apikey",
    'connectionid' => $conid,
    'Content-Type' => 'application/json'
    ),
    'body' => json_encode($payload),
    'timeout' => '120',
    'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
    'sslverify' => false
    );

    $response =  wp_remote_retrieve_body( wp_remote_post("https://dashboard.raptor.ng/api/v1/verification/",$http_args));
    $json = json_decode($response);

if(isset($json->status)){
    if($json->status){
        vp_updateoption("enable_raptor", $eraptor);
        vp_updateoption("raptor_apikey", $apikey);
        vp_updateoption("raptor_conid", $conid);
        vp_updateoption("bvn_verification_charge", $charge);

        if($json->processor){
            vp_updateoption("raptor_allow_security", "yes");
        }
        else{
            vp_updateoption("raptor_allow_security", "no"); 
        }
    }else{
        vp_updateoption("raptor_allow_security", "no");
    }
}
    die($response);