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



if(!is_user_logged_in()){
    die("Please Login");
}
elseif(!current_user_can("vtupress_access_history")){
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
    
    
    

if(isset($_POST["action"])){
    global $wpdb;

    $action = $_POST["action"];
    
    $id = $_POST["user_id"];
    $sid = $_POST["trans_id"];
    $table = $_POST["table"];
    $type = $_POST["type"];
    
    $name = get_userdata(get_current_user_id())->user_login;
    
    switch($_POST["action"]){
            case"reverse":
    $amount = $_POST["amount"];
    //set status to failed
    $data = [ 'status' => 'Failed' ];
    $where = [ 'id' => $sid ];
    $updated = $wpdb->update( $wpdb->prefix.$table, $data, $where);
    
    //credit the user
    $before_amount = floatval(vp_getuser($id,"vp_bal",true))+0.1;
    $now_amount = $before_amount + $amount;
    
    if(preg_match("/-/",$amount)){
        die("Amount can't contain minus [ - ]");
    }
    vp_updateuser($id, "vp_bal", $now_amount);
    
    
    //notify user about the update
    $table_name = $wpdb->prefix.'vp_wallet';
    $wpdb->insert($table_name, array(
    'name'=> $name,
    'type'=> "Wallet",
    'description'=> "Reversal For $type Transaction With ID $sid",
    'fund_amount' => $amount,
    'before_amount' => $before_amount,
    'now_amount' => $now_amount,
    'user_id' => $id,
    'status' => "Approved",
    'the_time' => date('Y-m-d h:i:s A')
    ));
    
    die("100");
    
            break;
            case"success":
            
    //set status to failed
    $data = [ 'status' => 'Successful' ];
    $where = [ 'id' => $sid ];
    $updated = $wpdb->update( $wpdb->prefix.$table, $data, $where);
    
    die("100");
            break;	
    }
        
    

}
elseif(isset($_POST["transfer"])){

        $trans_id = $_POST["trans_id"];

switch($_POST["transfer"]){
case"successful":
        $my_id = $_POST["from"];
        $to = $_POST["to"];

        $amount = $_POST["amount"];

        if(preg_match("/-/",$amount)){
            die("Amount can't contain minus [ - ]");
        }

$user_id = $to;

$user_current_balance = vp_getuser($user_id,"vp_bal",true);
$fund_user = floatval($user_current_balance) + floatval($amount);

vp_updateuser($user_id,"vp_bal",$fund_user);
    //set status to failed
    $data = [ 'status' => 'Approved' ];
    $where = [ 'id' => $trans_id ];
    $updated = $wpdb->update( $wpdb->prefix."vp_transfer", $data, $where);

    
global $wpdb;
$name = get_userdata($my_id)->user_login;
$description = "Credited By $name On Transfer";
$fund_amount= $amount;
$before_amount = $user_current_balance;
$now_amount = $fund_user;
$user_id = $user_id;
$the_time = date('Y-m-d h:i:s A');

$table_name = $wpdb->prefix.'vp_wallet';
$wpdb->insert($table_name, array(
'name'=> $name,
'type'=> "Wallet",
'description'=> $description,
'fund_amount' => $fund_amount,
'before_amount' => $before_amount,
'now_amount' => $now_amount,
'user_id' => $user_id,
'status' => "Approved",
'the_time' => date('Y-m-d h:i:s A')
));

die("100");

break;
case"failed":

    //set status to failed
    $data = [ 'status' => 'Failed' ];
    $where = [ 'id' => $trans_id ];
    $updated = $wpdb->update( $wpdb->prefix."vp_transfer", $data, $where);
    die("100");
break;
}

}
elseif(isset($_POST["process_with"])){
	
	global $wpdb;
	
	$dothis = $_POST["process_with"];
	$forthis = $_POST["with_user_id"];
	$amount = $_POST["with_amount"];

    if(preg_match("/-/",$amount)){
        die("Amount can't contain minus [ - ]");
    }

	$row = $_POST["the_row_id"];
	
	if($dothis == "Approve"){
		$update = "Approved";
		$cur = vp_getuser($forthis,'vp_bal',true);
		$tot = $cur + $amount;
		vp_updateuser($forthis,'vp_bal',$tot);
	}
	elseif($dothis == "Fail"){
		$update = "failed";
	
	}
	else{
		$update = "Processing";
	}
	
$data = [ 'status' => $update ];
$where = [ 'id' => $row ];
$updated = $wpdb->update( $wpdb->prefix.'vp_withdrawal', $data, $where);

if($updated !== false){
echo'100';
}
else{
echo'200';	
}

}elseif(isset($_POST['convert_to'])){
	$convert_to = $_POST["convert_to"];
	$convert_id = $_POST["convert_id"];
	$convert_user_id = $_POST["convert_user_id"];
	$convert_amount = $_POST["convert_amount"];
        $convert_type = $_POST["type"];

        if(preg_match("/-/",$convert_amount)){
            die("Amount can't contain minus [ - ]");
        }
	

		global $wpdb;
if(strtolower($convert_to) == "approve"){
		$update = "Approved";

                if($convert_type == "wallet"){
$before_amount = vp_getuser($convert_user_id,"vp_bal",true);
$now_amount = $before_amount + $convert_amount;
vp_updateuser($convert_user_id, "vp_bal", $now_amount);
                }
	}
	else{
		$update = "Failed";
	}
	
$data = [ 'status' => $update ];
$where = [ 'id' => $convert_id ];
$updated = $wpdb->update( $wpdb->prefix.'vp_wallet', $data, $where);


if($updated !== false){
die("100");
}
else{
die("Error!!!");
}

	
	
}?>    