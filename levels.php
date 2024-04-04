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
       //  die("INVALID SPRAYCODE");
    }
}




if(isset($_REQUEST["level_action"])){

	switch($_REQUEST["level_action"]){

		case"rule_action":

			global $wpdb;
			$table_name = $wpdb->prefix."vp_pv_rules";
			$rules = $wpdb->get_results("SELECT * FROM  $table_name");

		foreach($rules as $rule){
			$my_id = $rule->id;

			if(isset($_POST["set_plan$my_id"]) && isset($_POST["required_pv$my_id"]) && isset($_POST["bonus_amount$my_id"])){
			$set_plan = $_POST["set_plan$my_id"];
			$required_pv = $_POST["required_pv$my_id"];
			$bonus_amount = $_POST["bonus_amount$my_id"];

			$arg = [
				'required_pv' => $required_pv,
				'upgrade_plan' => $set_plan,
				'upgrade_balance' => $bonus_amount

			];

			$wpdb->update($table_name,$arg, array('id' => $my_id));

		}
		}


			global $wpdb;
			$table_name = $wpdb->prefix.'vp_pv_rules';
			$wpdb->insert($table_name, array(
			'required_pv'=> "1000",
			'upgrade_plan'=> "NONE",
			'upgrade_balance'=> "0",
			'status'=>"none",
			));
			die("100");
		break;
		
		case"remove_rule":
			$rule_id = $_POST["rule_id"];
			global $wpdb;
			$table_name = $wpdb->prefix.'vp_pv_rules';
			$wpdb->delete($table_name, array('id' => $rule_id));
			die("200");
		break;

		case"create_level":
		
		$name = $_REQUEST["level_name"];
		global $wpdb;
$table_name = $wpdb->prefix.'vp_levels';
$levels = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE name = %s",$name));

if($levels == NULL){
$wpdb->insert($table_name, array(
'name'=> "$name",
'airtime_pv'=> "0",
'data_pv'=> "0",
'cable_pv'=> "0",
'bill_pv'=> "0",
'mtn_vtu'=> "0",
'glo_vtu'=> "0",
'mobile_vtu'=> "0",
'airtel_vtu'=> "0",

'mtn_awuf'=> "0",
'glo_awuf'=> "0",
'mobile_awuf'=> "0",
'airtel_awuf'=> "0",

'mtn_share'=> "0",
'glo_share'=> "0",
'mobile_share'=> "0",
'airtel_share'=> "0",

'mtn_sme'=> "0",
'glo_sme'=> "0",
'mobile_sme'=> "0",
'airtel_sme'=> "0",

'mtn_corporate'=> "0",
'glo_corporate'=> "0",
'mobile_corporate'=> "0",
'airtel_corporate'=> "0",

'mtn_gifting'=> "0",
'glo_gifting'=> "0",
'mobile_gifting'=> "0",
'airtel_gifting'=> "0",

'cable'=> "0",

'bill_prepaid'=> "0",

'card_mtn'=> "0",
'card_glo'=> "0",
'card_9mobile'=> "0",
'card_airtel'=> "0",

'data_mtn'=> "0",
'data_glo'=> "0",
'data_9mobile'=> "0",
'data_airtel'=> "0",
'charge_back_percentage' => '0',

'epin_waec'=> "0",
'epin_neco'=> "0",
'epin_jamb'=> "0",
'epin_nabteb'=> "0",

'airtime_bonus_ex1'=> "0",
'extra_feature_assigned_uId'=> "0",
'data_bonus_ex1'=> "0",
'ref_airtime_bonus_ex1'=> "0",
'ref_data_bonus_ex1'=> "0",
'data_bonus_type_ex1'=> "0",
'enable_extra_service'=> "0",

'status'=> "in-active",

'upgrade'=> "000",
'monthly_sub'=> "no",
'upgrade_bonus'=> "000",
'upgrade_pv'=> "0",
'developer'=> "no",
'transfer'=> "no",

'monthly_referee'=> '0',
'monthly_transactions_number'=> '0',
'monthly_transactions_amount'=> '0',


'total_level'=> "0",
'level_1'=> "0",
'level_1_data'=> "0",
'level_1_cable'=> "0",
'level_1_bill'=> "0",
'level_1_ecards'=> "0",
'level_1_edatas'=> "0",
'level_1_epins'=> "0",

'level_1_pv'=> "0",
'level_1_data_pv'=> "0",
'level_1_cable_pv'=> "0",
'level_1_bill_pv'=> "0",
'level_1_ecards_pv'=> "0",
'level_1_edatas_pv'=> "0",
'level_1_epins_pv'=> "0",


'level_1_upgrade'=> "0",
'level_1_upgrade_pv'=> "0",


));

die("100");

}
else{
	
die("A Plan With Similar Name Already Exist");	
}



		break;
		
		case"create_ref_level":
		
		$id = $_REQUEST["level_id"];
		global $wpdb;
		$table_name = $wpdb->prefix."vp_levels";
		$datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE id = %d ",$id));
		$total_level = floatval($datas[0]->total_level);
		$next = $total_level + 1;
		$new_level = "level_".$next;
		$new_upgrade = "level_".$next."_upgrade";
		$new_upgrade_pv = "level_".$next."_upgrade_pv";
		maybe_add_column($table_name,$new_level, "ALTER TABLE $table_name ADD $new_level text");
		maybe_add_column($table_name,$new_level."_data", "ALTER TABLE $table_name ADD {$new_level}_data text");
		maybe_add_column($table_name,$new_level."_cable", "ALTER TABLE $table_name ADD {$new_level}_cable text");
		maybe_add_column($table_name,$new_level."_bill", "ALTER TABLE $table_name ADD {$new_level}_bill text");
		maybe_add_column($table_name,$new_level."_ecards", "ALTER TABLE $table_name ADD {$new_level}_ecards text");
		maybe_add_column($table_name,$new_level."_edatas", "ALTER TABLE $table_name ADD {$new_level}_edatas text");
		maybe_add_column($table_name,$new_level."_epins", "ALTER TABLE $table_name ADD {$new_level}_epins text");
		maybe_add_column($table_name,$new_level."_pv", "ALTER TABLE $table_name ADD {$new_level}_pv text");
		maybe_add_column($table_name,$new_level."_data_pv", "ALTER TABLE $table_name ADD {$new_level}_data_pv text");
		maybe_add_column($table_name,$new_level."_cable_pv", "ALTER TABLE $table_name ADD {$new_level}_cable_pv text");
		maybe_add_column($table_name,$new_level."_bill_pv", "ALTER TABLE $table_name ADD {$new_level}_bill_pv text");
		maybe_add_column($table_name,$new_level."_epins_pv", "ALTER TABLE $table_name ADD {$new_level}_epins_pv text");
		maybe_add_column($table_name,$new_level."_ecards_pv", "ALTER TABLE $table_name ADD {$new_level}_ecards_pv text");
		maybe_add_column($table_name,$new_level."_edatas_pv", "ALTER TABLE $table_name ADD {$new_level}_edatas_pv text");

		maybe_add_column($table_name,"$new_upgrade", "ALTER TABLE $table_name ADD $new_upgrade text");
		maybe_add_column($table_name,"$new_upgrade_pv", "ALTER TABLE $table_name ADD $new_upgrade_pv text");
		$where = [ 'id' => $id ];
		$arr = [$new_level => "0", $new_upgrade => "0", "total_level" => $next ];
$updated = $wpdb->update( $wpdb->prefix.'vp_levels', $arr, $where);

die("100");
		break;
		
				case"delete_ref_level":
		
		$id = $_REQUEST["level_id"];
		global $wpdb;
		$table_name = $wpdb->prefix."vp_levels";
		$datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE id = %d",$id));
		$total_level = floatval($datas[0]->total_level);
		$previous = $total_level - 1;
		$where = [ 'id' => $id ];
		$arr = [ "total_level" => $previous ];
$updated = $wpdb->update( $wpdb->prefix.'vp_levels', $arr, $where);

die("100");



		break;
		
		
		case"update_level":
		$id = $_REQUEST["level_id"];
		global $wpdb;
	
$arr = array();

global $wpdb;
$table = $wpdb->prefix."vp_levels";

if(vp_getoption("level_fixing_$id") != "13"){
maybe_add_column($table,"monthly_referee", "ALTER TABLE $table ADD monthly_referee bigint");
maybe_add_column($table,"monthly_transactions_number", "ALTER TABLE $table ADD monthly_transactions_number bigint");
maybe_add_column($table,"monthly_transactions_amount", "ALTER TABLE $table ADD monthly_transactions_amount bigint");
maybe_add_column($table,"upgrade_bonus", "ALTER TABLE $table ADD upgrade_bonus bigint");
maybe_add_column($table,"airtime_pv", "ALTER TABLE $table ADD airtime_pv text");
maybe_add_column($table,"data_pv", "ALTER TABLE $table ADD data_pv text");
maybe_add_column($table,"cable_pv", "ALTER TABLE $table ADD cable_pv text");
maybe_add_column($table,"bill_pv", "ALTER TABLE $table ADD bill_pv text");
maybe_add_column($table,"airtime_pv", "ALTER TABLE $table ADD airtime_pv text");
maybe_add_column($table,"data_mtn", "ALTER TABLE $table ADD data_mtn text");
maybe_add_column($table,"data_glo", "ALTER TABLE $table ADD data_glo text");
maybe_add_column($table,"data_airtel", "ALTER TABLE $table ADD data_airtel text");
maybe_add_column($table,"data_9mobile", "ALTER TABLE $table ADD data_9mobile text");

vp_updateoption("level_fixing_$id", "12");

}

$arr['name']= $_REQUEST['name'];
$arr['upgrade_bonus']= $_REQUEST['upgrade_bonus'];
$arr['monthly_sub']= $_REQUEST['monthly_sub'];
$arr['upgrade_pv']= $_REQUEST['upgrade_pv'];
$arr['airtime_pv']= $_REQUEST['airtime_pv'];
$arr['data_pv']= $_REQUEST['data_pv'];
$arr['cable_pv']= $_REQUEST['cable_pv'];
$arr['bill_pv']= $_REQUEST['bill_pv'];
$arr['mtn_vtu']= $_REQUEST['mtn_vtu'];
$arr['glo_vtu']= $_REQUEST['glo_vtu'];
$arr['mobile_vtu']= $_REQUEST['mobile_vtu'];
$arr['airtel_vtu']= $_REQUEST['airtel_vtu'];
$arr['charge_back_percentage'] = $_REQUEST['charge_back_percentage'];

$arr['mtn_awuf']= $_REQUEST['mtn_awuf'];
$arr['glo_awuf']= $_REQUEST['glo_awuf'];
$arr['mobile_awuf']= $_REQUEST['mobile_awuf'];
$arr['airtel_awuf']= $_REQUEST['airtel_awuf'];

$arr['mtn_share']= $_REQUEST['mtn_share'];
$arr['glo_share']= $_REQUEST['glo_share'];
$arr['mobile_share']= $_REQUEST['mobile_share'];
$arr['airtel_share']= $_REQUEST['airtel_share'];

$arr['mtn_sme']= $_REQUEST['mtn_sme'];
$arr['glo_sme']= $_REQUEST['glo_sme'];
$arr['mobile_sme']= $_REQUEST['mobile_sme'];
$arr['airtel_sme']= $_REQUEST['airtel_sme'];

$arr['mtn_corporate']= $_REQUEST['mtn_corporate'];
$arr['glo_corporate']= $_REQUEST['glo_corporate'];
$arr['mobile_corporate']= $_REQUEST['mobile_corporate'];
$arr['airtel_corporate']= $_REQUEST['airtel_corporate'];

$arr['mtn_gifting']= $_REQUEST['mtn_gifting'];
$arr['glo_gifting']= $_REQUEST['glo_gifting'];
$arr['mobile_gifting']= $_REQUEST['mobile_gifting'];
$arr['airtel_gifting']= $_REQUEST['airtel_gifting'];

$arr['cable']= $_REQUEST['cable'];

$arr['bill_prepaid']= $_REQUEST['bill_prepaid'];

$arr['monthly_referee']= $_REQUEST['monthly_referee'];
$arr['monthly_transactions_number']= $_REQUEST['monthly_transactions_number'];
$arr['monthly_transactions_amount']= $_REQUEST['monthly_transactions_amount'];

$arr['card_mtn']= $_REQUEST['card_mtn'];
$arr['card_glo']= $_REQUEST['card_glo'];
$arr['card_9mobile']= $_REQUEST['card_9mobile'];
$arr['card_airtel']= $_REQUEST['card_airtel'];

$arr['data_mtn']= $_REQUEST['data_mtn'];
$arr['data_glo']= $_REQUEST['data_glo'];
$arr['data_9mobile']= $_REQUEST['data_9mobile'];
$arr['data_airtel']= $_REQUEST['data_airtel'];

$arr['epin_waec']= $_REQUEST['epin_waec'];
$arr['epin_neco']= $_REQUEST['epin_neco'];
$arr['epin_jamb']= $_REQUEST['epin_jamb'];
$arr['epin_nabteb']= $_REQUEST['epin_nabteb'];

if(vp_getoption("vtupress_custom_mlmsub") == "yes"){
$arr['airtime_bonus_ex1']= $_REQUEST['airtime_bonus_ex1'];
$arr['extra_feature_assigned_uId']= $_REQUEST['extra_feature_assigned_uId'];
$arr['data_bonus_ex1']= $_REQUEST['data_bonus_ex1'];
$arr['ref_airtime_bonus_ex1']= $_REQUEST['ref_airtime_bonus_ex1'];
$arr['ref_data_bonus_ex1']= $_REQUEST['ref_data_bonus_ex1'];
$arr['enable_extra_service']= $_REQUEST['enable_extra_service'];
$arr['data_bonus_type_ex1']= $_REQUEST['data_bonus_type_ex1'];
}

$arr['status']= $_REQUEST['status'];

$arr['upgrade']= $_REQUEST['upgrade'];
$arr['developer']= $_REQUEST['developer'];
$arr['transfer']= $_REQUEST['transfer'];


		global $wpdb;
		$table_name = $wpdb->prefix."vp_levels";
		$datas = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name WHERE id = %d",$id));
		$total_level = floatval($datas[0]->total_level);
for($lev = 1; $lev <= $total_level; $lev++){

	if(!isset($datas[0]->{"level_".$lev."_edatas"})){
	maybe_add_column($table,'level_'.$lev.'_data', "ALTER TABLE $table ADD level_{$lev}_data text");
	maybe_add_column($table,'level_'.$lev.'_cable', "ALTER TABLE $table ADD level_{$lev}_cable text");
	maybe_add_column($table,'level_'.$lev.'_bill', "ALTER TABLE $table ADD level_{$lev}_bill text");
	maybe_add_column($table,'level_'.$lev.'_ecards', "ALTER TABLE $table ADD level_{$lev}_ecards text");
	maybe_add_column($table,'level_'.$lev.'_edatas', "ALTER TABLE $table ADD level_{$lev}_edatas text");
	maybe_add_column($table,'level_'.$lev.'_epins', "ALTER TABLE $table ADD level_{$lev}_epins text");

	maybe_add_column($table,'level_'.$lev.'_pv', "ALTER TABLE $table ADD level_{$lev}_pv text");
maybe_add_column($table,'level_'.$lev.'_data_pv', "ALTER TABLE $table ADD level_{$lev}_data_pv text");
maybe_add_column($table,'level_'.$lev.'_cable_pv', "ALTER TABLE $table ADD level_{$lev}_cable_pv text");
maybe_add_column($table,'level_'.$lev.'_bill_pv', "ALTER TABLE $table ADD level_{$lev}_bill_pv text");
maybe_add_column($table,'level_'.$lev.'_ecards_pv', "ALTER TABLE $table ADD level_{$lev}_ecards_pv text");
maybe_add_column($table,'level_'.$lev.'_edatas_pv', "ALTER TABLE $table ADD level_{$lev}_edatas_pv text");
maybe_add_column($table,'level_'.$lev.'_epins_pv', "ALTER TABLE $table ADD level_{$lev}_epins_pv text");
maybe_add_column($table,'level_'.$lev."_upgrade_pv", "ALTER TABLE $table ADD level_{$lev}_upgrade_pv text");

	}

	$arr['level_'.$lev]= $_REQUEST['level_'.$lev];
	$arr['level_'.$lev.'_data']= $_REQUEST['level_'.$lev.'_data'];
	$arr['level_'.$lev.'_cable']= $_REQUEST['level_'.$lev.'_cable'];
	$arr['level_'.$lev.'_bill']= $_REQUEST['level_'.$lev.'_bill'];
	$arr['level_'.$lev.'_ecards']= $_REQUEST['level_'.$lev.'_ecards'];
	$arr['level_'.$lev.'_edatas']= $_REQUEST['level_'.$lev.'_edatas'];
	$arr['level_'.$lev.'_epins']= $_REQUEST['level_'.$lev.'_epins'];

	$arr['level_'.$lev."_pv"]= $_REQUEST['level_'.$lev."_pv"];
	$arr['level_'.$lev.'_data_pv']= $_REQUEST['level_'.$lev.'_data_pv'];
	
	$arr['level_'.$lev.'_cable_pv']= $_REQUEST['level_'.$lev.'_cable_pv'];
	$arr['level_'.$lev.'_bill_pv']= $_REQUEST['level_'.$lev.'_bill_pv'];
	$arr['level_'.$lev.'_ecards_pv']= $_REQUEST['level_'.$lev.'_ecards_pv'];
	$arr['level_'.$lev.'_edatas_pv']= $_REQUEST['level_'.$lev.'_edatas_pv'];
	$arr['level_'.$lev.'_epins_pv']= $_REQUEST['level_'.$lev.'_epins_pv'];


	$arr['level_'.$lev."_upgrade"]= $_REQUEST['level_'.$lev."_upgrade"];
	$arr['level_'.$lev."_upgrade_pv"]= $_REQUEST['level_'.$lev."_upgrade_pv"];
}

$where = [ 'id' => $id ];
$updated = $wpdb->update( $wpdb->prefix.'vp_levels', $arr, $where);

die("100");
		break;
		case"delete_level":
		$id = $_REQUEST["level_id"];
		$name = $_REQUEST["level_name"];
		if(strtolower($name) != "customer" && strtolower($name) != "reseller"){
		global $wpdb;
		$table = $wpdb->prefix."vp_levels";
		$wpdb->delete($table,array('id'=>$id));
		die("100");
				}
		else{
			
die("You Can't Delete Default Value Namely $name");
		}
		break;
		
		
	}
	
	
	
	
}

?>