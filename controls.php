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

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm\/wp-admin/",$referer)) {
		die("REF ENT PERM");
	}

}else{
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
       // die("INVALID SPRAYCODE");
    }
}elseif(strtolower($spray_code) == "false"){
    if($real_code == "false"){
        $cur_id = get_current_user_id();
        $update_code = uniqid("vtu_$cur_id");
        vp_updateoption("spraycode",$update_code);
    }elseif($real_code != $spray_code ){
        die("INVALID SPRAYCODE");
    }else{
        //die("INVALID SPRAYCODE");
    }
}




if(isset($_POST["set_control"])){
$status = $_POST["set_status"];
    switch($_POST["set_control"]){
        case"vtu":
            vp_updateoption("vtucontrol",$status);
            die('100');	
        break;
        case"bet":
            vp_updateoption("betcontrol",$status);
            die('100');	
        break;
        case"shared":
            vp_updateoption("sharecontrol",$status);
            die('100');	
        break;
        case"awuf":
            vp_updateoption("awufcontrol",$status);
            die('100');	
        break;
        case"sme":
            vp_updateoption("smecontrol",$status);
            die('100');	
        break;
        case"smile":
            vp_updateoption("smilecontrol",$status);
            die('100');	
        break;
        case"alpha":
            vp_updateoption("alphacontrol",$status);
            die('100');	
        break;
        case"corporate":
            vp_updateoption("corporatecontrol",$status);
            die('100');	
        break;
        case"direct":
            vp_updateoption("directcontrol",$status);
            die('100');	
        break;
        case"airtime":
            vp_updateoption("setairtime",$status);
            die('100');	
        break;
        case"data":
            vp_updateoption("setdata",$status);
            die('100');	
        break;
        case"cable":
            vp_updateoption("setcable",$status);
            die('100');	
        break;
        case"bill":
            vp_updateoption("setbill",$status);
            die('100');	
        break;
        case"epins":
            vp_updateoption("epinscontrol",$status);
            die('100');	
        break;
        case"cards":
            vp_updateoption("cardscontrol",$status);
            die('100');	
        break;
        case"datas":
            vp_updateoption("datascontrol",$status);
            die('100');	
        break;
        case"sms":
            vp_updateoption("smscontrol",$status);
            die('100');	
        break;
    }



}




?>