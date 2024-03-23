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
       //  die("INVALID SPRAYCODE");
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



if(isset($_POST["coupon_generate"])){
	$code = trim($_POST["coupon_code"]);
	$app = $_POST["applicable_to"];
	$amount = $_POST["coupon_amount"];
global $wpdb;	
$table_name = $wpdb->prefix.'vp_coupon';
$wpdb->insert($table_name, array(
'code'=> $code,
'applicable_to'=> $app,
'amount' => $amount,
'status' => "Active",
'the_time' => current_time('mysql', 1)
));

die("100");
}

if(isset($_POST["coupon_user_edit"])){
	$ids = $_POST["coupon_user_edit"];
	$cid = $_POST["coupon_id"];
global $wpdb;
$data = [ 'applicable_to' => $ids ];
$where = [ 'id' => $cid ];
$wpdb->update( $wpdb->prefix.'vp_coupon', $data, $where);

	die("100");
}


if(isset($_POST["coupon_close_edit"])){

	$cid = $_POST["coupon_id"];
global $wpdb;
$data = [ 'status' => "Close" ];
$where = [ 'id' => $cid ];
$wpdb->update( $wpdb->prefix.'vp_coupon', $data, $where);

	die("100");	
}


if(isset($_POST["coupon_open_edit"])){

	$cid = $_POST["coupon_id"];
global $wpdb;
$data = [ 'status' => "Active" ];
$where = [ 'id' => $cid ];
$wpdb->update( $wpdb->prefix.'vp_coupon', $data, $where);

	die("100");	
}

if(isset($_POST["coupon_delete_edit"])){

	$cid = $_POST["coupon_id"];
global $wpdb;
$table_name = $wpdb->prefix.'vp_coupon';
$wpdb->delete($table_name , array( 'id' => $cid ));

	die("100");
	
}
	
if(isset($_POST["enable_coupon"])){


vp_updateoption("enable_coupon", $_POST["enable_coupon"]);

die("100");
	
}
	
if(isset($_POST["run_coupon"])){

$code = trim($_POST["run_coupon"]);
$id = get_current_user_id();

global $wpdb;
$table_name = $wpdb->prefix.'vp_coupon';
$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY ID DESC"); 


foreach($results as $coupon){
	if($coupon->code == $code){
		$code_id = $coupon->id;
		$amount = $coupon->amount;
		$used = $coupon->used_by;
		$app = $coupon->applicable_to;
		$myid = $id.",";
				if(strtolower($coupon->status) == "active"){
		if(in_array($id,explode(",",$app)) || is_numeric(stripos($app,"all"))){
			
			if(in_array($id,explode(",",$used)) !== true){
				
				$before_amount = vp_getuser($id,"vp_bal",true);
				$now_amount = $before_amount + $amount;
				vp_updateuser($id,"vp_bal",$now_amount);
				
		$name = get_userdata($id)->user_login;				
$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Coupon",
'description'=> "Redeemed Code $code",
'fund_amount' => $amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $id,
'status' => "Approved",
'the_time' => current_time('mysql', 1)
));


global $wpdb;
$data = [ 'used_by' => ",".$myid ];
$where = [ 'id' => $code_id ];
$wpdb->update( $wpdb->prefix.'vp_coupon', $data, $where);

			$obj = new stdClass;
			$obj->status = "100";
			$obj->message = "You Have Been Credited With $amount From the Redeemed Code";
			die(json_encode($obj));
			}
			else{
			$obj->status = "200";
			$obj->message = "Code Has Been Used By You";
			die(json_encode($obj));	
			}
			
		}
		else{
			$obj = new stdClass;
			$obj->status = "200";
			$obj->message = "Coupon Not Applicable To You";
			die(json_encode($obj));
		}
				}
				else{
			$obj->status = "200";
			$obj->message = "Coupon Code Has Been Closed";
			die(json_encode($obj));
				}
		
	}

}


			$obj = new stdClass;
			$obj->status = "200";
			$obj->message = "Code Doesn't Exist";
			die(json_encode($obj));


	
}?>