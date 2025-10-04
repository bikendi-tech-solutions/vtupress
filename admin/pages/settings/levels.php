<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

$option_array = json_decode(get_option("vp_options"),true);


if(current_user_can("vtupress_access_users") && $_GET["subpage"] == "levels"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }

    if(!is_plugin_active("vprest/vprest.php")  || vp_option_array($option_array,"resell") != "yes"){

        vp_die("YOU CANT ACCESS THIS PAGE BECAUSE YOU ARE A PERSONAL/DEMO USER OR DOES NOT HAVE VP RESELLER ADDON INSTALLED");
    }else{
        $path = $_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/vprest/vprest.php";
        $path = get_plugin_data($path);
        
        
        if($path["Version"] < "2.1.0"){
        vp_die("VP RESELLER ADDON IS LESS THAN 2.1.0 PLEASE UPDATE IT.");
        
        }
        else{
        
        if(!is_plugin_active("vpmlm/vpmlm.php")  || vp_option_array($option_array,"resell") != "yes"){
            
        vp_die(" YOU CAN EXTEND YOUR PLUGIN FUNCTIONALITY INSTALLING VPMLM.");
        
        
        }
        else{
        $path2 = $_SERVER["DOCUMENT_ROOT"]."/wp-content/plugins/vpmlm/vpmlm.php";
        $path2 = get_plugin_data($path2);
            if($path2["Version"] < "2.1.0"){

                vp_die("VP MLM ADDON IS LESS THAN 2.1.0 PLEASE UPDATE IT.");
        
        }

        
        
        }
            
        }
        }

        
	$vp_country = vp_country();
	$glo = $vp_country["glo"];
	$mobile = $vp_country["9mobile"];
	$mtn = $vp_country["mtn"];
	$airtel = $vp_country["airtel"];
	$bypass = $vp_country["bypass"];
	$currency = $vp_country["currency"];
	$symbol = $vp_country["symbol"];
?>

<div class="container-fluid license-container">
            <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
            <style>
                div.vtusettings-container *{
                    font-family:roboto;
                }
                .swal-button.swal-button--confirm {
                    width: fit-content;
                    padding: 10px !important;
                }
            </style>

<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.

                  </p>


<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

global $wpdb;
$table_name = $wpdb->prefix."vp_levels";


  

$level = isset($_REQUEST["level"]) ? $_REQUEST["level"] : "1";
global $datas;
$where = "WHERE id = $level";
$datas = $wpdb->get_results("SELECT * FROM  $table_name $where");
$data = $wpdb->get_results("SELECT * FROM  $table_name");

function levelsDb($value){
  global $datas,$wpdb;

  if(!empty($value)){
  if(isset($datas[0]->{$value})){
   $ret =  $datas[0]->{$value};
  }else{

		$table_name = $wpdb->prefix."vp_levels";
		maybe_add_column($table_name,$value, "ALTER TABLE $table_name ADD $value text ");

    $ret = "000";
  }
  }else{
    $ret = "e0";
  }

   return $ret;

}

?>

<div class="row">

    <div class="col-12">
    <link
      rel="stylesheet"
      type="text/css"
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
<div class="card">
                <div class="card-body">
                  <h5 class="card-title">Users Level/Packages/Bonuses/Refs</h5> 
                  <div class="table-responsive">
<div class="p-4">

<div class="row">
<form class="vtupress_level">
<div class="col-12 bg bg-white">


<div class="row pt-5 bg-primary">

<div class="col-12 col-sm  ">
<div class="input-group">

<span class="input-group-text">NEW LEVEL</span>
<input type="text" placeholder="Plan Name" class="level_name">
<input type="button" class="create_level" value="CREATE">

</div>
</div>

<div class="col-12 col-sm ">

<div class="input-group">
<span class="input-group-text">SELECT LEVEL</span>
<select class="form-control level_id">
<option value="<?php echo levelsDb("id");?>"><?php echo strtoupper(levelsDb("name"));?></option>
<?php
foreach($data as $levels){
	?>
<option value="<?php echo $levels->id;?>"><?php echo strtoupper($levels->name);?></option>
	<?php
}
?>
</select>
<input type="button" value="DELETE" class="delete_level">
</div>
</div>

</div>

<div class="row p-4 bg bg-primary">
<div class="font-bold text-black bg bg-warning p-3 p-sm-5">
Please note that all values are percentage based and are calculated based on fixed price e.g fixed price on the data prices page compared to the percentage given for the service.
</div>
<div class="col-12 bg bg-secondary">
<div class="row">

<div class="col-12 col-sm  bg bg-secondary p-3">
<div class="input-group">
<span class="input-group-text">NAME</span>
<input type="text" placeholder="Plan Name" name="name" value="<?php echo levelsDb("name");?>" class="d_level_name" readOnly>
</div>

<div class="input-group">
<span class="input-group-text">UPGRADE AMOUNT</span>
<input type="number" placeholder="Plan Price" name="upgrade" value="<?php echo levelsDb("upgrade");?>">
<span class="input-group-text">UPGRADE BONUS</span>
<input type="number" placeholder="Bonus Price" name="upgrade_bonus" value="<?php echo floatval(vp_getvalue(levelsDb("upgrade_bonus")));?>">
</div>
<div class="input-group">
<span class="input-group-text">FUNDING COMMISION FROM REFEREE %</span>
<input type="number" placeholder="Fund Chargeback" name="charge_back_percentage" value="<?php echo floatval(vp_getvalue(levelsDb("charge_back_percentage")));?>">
</div>
</div>

<div class="col-12 col-sm bg bg-secondary p-3">
<div class="input-group">
<span class="input-group-text">STATUS</span>
<select class="form-control status" name="status">
<option value="<?php echo levelsDb("status");?>" disabled selected><?php echo strtoupper(levelsDb("status"));?></option>
<option value="in-active">IN ACTIVE</option>
<option value="active">ACTIVE</option>
</select>
</div>

<div class="input-group">
<span class="input-group-text">API ACCESS</span>
<select class="form-control developer"  name="developer">
<option value="<?php echo levelsDb("developer");?>"><?php echo strtoupper(levelsDb("developer"));?></option>
<option value="no">NO</option>
<option value="yes">YES</option>
</select>
<span class="input-group-text">Transfer Access</span>
<select class="form-control transfer"  name="transfer">
<option value="<?php echo levelsDb("transfer");?>"><?php echo strtoupper(levelsDb("transfer"));?></option>
<option value="no">NO</option>
<option value="yes">YES</option>
</select>
</div>

<div class="input-group">
<span class="input-group-text">Monthly Subscription Type:</span>
<select class="form-control monthly_sub"  name="monthly_sub">
<option value="<?php echo levelsDb("monthly_sub");?>"><?php echo strtoupper(levelsDb("monthly_sub"));?></option>
<option value="no">NO</option>
<option value="yes">YES</option>
</select>
</div>
<div class="input-group">
<span class="input-group-text">UPGRADE PV</span>
<input type="number" placeholder="Upgrade PV" name="upgrade_pv" value="<?php echo floatval(vp_getvalue(levelsDb("upgrade_pv")));?>">
</div>
</div>

</div>
</div>
</div>

<div class="col-12 col-sm my-5 mx-1">
<span class="font-bold">Monthly Membership Rule:</span><br>
<code>Please leave any field @ 0 if you don't want it to take effect. users are enforced to make sure they meet up with the stated else they fall back to the default CUSTOMER Plan</code>

<div class="input-group">
				<span class="input-group-text">Monthly Referees:</span>
				<input type="number" class="input-group-text form-control" name="monthly_referee" value="<?php echo floatval(levelsDb("monthly_referee"));?>">
</div>
<div class="input-group">
				<span class="input-group-text">Monthly Number Of Transactions:</span>
				<input type="number" class="input-group-text form-control" name="monthly_transactions_number" value="<?php echo floatval(levelsDb("monthly_transactions_number"));?>">
</div>
<div class="input-group">
				<span class="input-group-text">Total Transactions Amount:</span>
				<input type="number" class="input-group-text form-control" name="monthly_transactions_amount" value="<?php echo floatval(levelsDb("monthly_transactions_amount"));?>">
</div>

</div>

<div class="col-12 col-sm my-5 mx-1">
		<span class="font-bold">AIRTIME 

    <div class="input-group">
				<span class="input-group-text">PV:</span>
				<input type="number" class="input-group-text form-control" name="airtime_pv" value="<?php echo floatval(vp_getvalue(levelsDb("airtime_pv")));?>">
		</div>

    </span>
		<div class="p-3">
	<div class="row">
	
		<div class="col-12 col-sm my-3">
		<span> VTU </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> VTU</span>
				<input type="number" class="input-group-text form-control" name="mtn_vtu" value="<?php echo floatval(levelsDb("mtn_vtu"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> VTU</span>
				<input type="number" class="input-group-text form-control"  name="glo_vtu" value="<?php echo floatval(levelsDb("glo_vtu"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> VTU</span>
				<input type="number" class="input-group-text form-control"  name="airtel_vtu"  value="<?php echo floatval(levelsDb("airtel_vtu"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> VTU</span>
				<input type="number" class="input-group-text form-control"  name="mobile_vtu"  value="<?php echo floatval(levelsDb("mobile_vtu"));?>">
			</div>
		</div>


		<div class="col-12 col-sm  my-3">
		<span> SHARE & SELL </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> SHARE</span>
				<input type="number" class="input-group-text form-control"  name="mtn_share"  value="<?php echo floatval(levelsDb("mtn_share"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> SHARE</span>
				<input type="number" class="input-group-text form-control"  name="glo_share"  value="<?php echo floatval(levelsDb("glo_share"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> SHARE</span>
				<input type="number" class="input-group-text form-control"  name="airtel_share"  value="<?php echo floatval(levelsDb("airtel_share"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> SHARE</span>
				<input type="number" class="input-group-text form-control"  name="mobile_share"  value="<?php echo floatval(levelsDb("mobile_share"));?>">
			</div>
		</div>
		
		
		<div class="col-12 col-sm  my-3">
		<span> AWUF </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> AWUF</span>
				<input type="number" class="input-group-text form-control"  name="mtn_awuf"  value="<?php echo floatval(levelsDb("mtn_awuf"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> AWUF</span>
				<input type="number" class="input-group-text form-control"  name="glo_awuf"  value="<?php echo floatval(levelsDb("glo_awuf"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> AWUF</span>
				<input type="number" class="input-group-text form-control"  name="airtel_awuf"  value="<?php echo floatval(levelsDb("airtel_awuf"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> AWUF</span>
				<input type="number" class="input-group-text form-control"  name="mobile_awuf" value="<?php echo floatval(levelsDb("mobile_awuf"));?>">
			</div>
		</div>
		
</div>
	</div>
</div>



<div class="col-12 col-sm my-5 mx-1">
		<span class="font-bold">DATA

    <div class="input-group">
				<span class="input-group-text">PV:</span>
				<input type="number" class="input-group-text form-control" name="data_pv" value="<?php echo floatval(vp_getvalue(levelsDb("data_pv")));?>">
		</div>

    </span>
		<div class="p-3">
	<div class="row">
	
		<div class="col-12 col-sm my-3">
		<span> SME </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> SME</span>
				<input type="number" class="input-group-text form-control"  name="mtn_sme"  value="<?php echo floatval(levelsDb("mtn_sme"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> SME</span>
				<input type="number" class="input-group-text form-control"  name="glo_sme"  value="<?php echo floatval(levelsDb("glo_sme"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> SME</span>
				<input type="number" class="input-group-text form-control"  name="airtel_sme"  value="<?php echo floatval(levelsDb("airtel_sme"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> SME</span>
				<input type="number" class="input-group-text form-control"  name="mobile_sme"  value="<?php echo floatval(levelsDb("mobile_sme"));?>">
			</div>
		</div>


		<div class="col-12 col-sm my-3">
		<span> CORPORATE </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> Corp</span>
				<input type="number" class="input-group-text form-control"  name="mtn_corporate"  value="<?php echo floatval(levelsDb("mtn_corporate"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> Corp</span>
				<input type="number" class="input-group-text form-control"  name="glo_corporate"  value="<?php echo floatval(levelsDb("glo_corporate"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> Corp</span>
				<input type="number" class="input-group-text form-control"  name="airtel_corporate"  value="<?php echo floatval(levelsDb("airtel_corporate"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> Corp</span>
				<input type="number" class="input-group-text form-control"  name="mobile_corporate"  value="<?php echo floatval(levelsDb("mobile_corporate"));?>">
			</div>
		</div>
		
		
		<div class="col-12 col-sm my-3">
		<span> GIFTING </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> Gift</span>
				<input type="number" class="input-group-text form-control"  name="mtn_gifting"  value="<?php echo floatval(levelsDb("mtn_gifting"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> Gift</span>
				<input type="number" class="input-group-text form-control"   name="glo_gifting"    value="<?php echo floatval(levelsDb("glo_gifting"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> Gift</span>
				<input type="number" class="input-group-text form-control"   name="airtel_gifting"   value="<?php echo floatval(levelsDb("airtel_gifting"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> Gift</span>
				<input type="number" class="input-group-text form-control"   name="mobile_gifting"   value="<?php echo floatval(levelsDb("mobile_gifting"));?>">
			</div>
		</div>
		

	</div>

</div>

</div>




<div class="col-12 col-sm my-5 mx-1">
		<span class="font-bold">MISC.</span>
		<div class="p-3">
	<div class="row">
	
		<div class="col-12 col-sm my-3">
		<span> UTILITIES </span>
			<div class="input-group">
				<span class="input-group-text">CABLES</span>
				<input type="number" class="input-group-text form-control"   name="cable"  value="<?php echo floatval(levelsDb("cable"));?>">
        <span class="input-group-text">PV:</span>
        <input type="number" class="input-group-text form-control"   name="cable_pv"  value="<?php echo floatval(vp_getvalue(levelsDb("cable_pv")));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text">BILLS</span>
				<input type="number" class="input-group-text form-control"   name="bill_prepaid"   value="<?php echo floatval(levelsDb("bill_prepaid"));?>">
        <span class="input-group-text">PV:</span>
        <input type="number" class="input-group-text form-control"   name="bill_pv"  value="<?php echo floatval(vp_getvalue(levelsDb("bill_pv")));?>">
      </div>
		</div>


		<div class="col-12 col-sm my-3">
		<span> ECARDS </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> CARDS</span>
				<input type="number" class="input-group-text form-control"  name="card_mtn"  value="<?php echo floatval(levelsDb("card_mtn"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> CARDS</span>
				<input type="number" class="input-group-text form-control" name="card_glo" value="<?php echo floatval(levelsDb("card_glo"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> CARDS</span>
				<input type="number" class="input-group-text form-control" name="card_airtel" value="<?php echo floatval(levelsDb("card_airtel"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> CARDS</span>
				<input type="number" class="input-group-text form-control" name="card_9mobile" value="<?php echo floatval(levelsDb("card_9mobile"));?>">
			</div>
		</div>

    
		<div class="col-12 col-sm my-3">
		<span> DATA CARDS </span>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mtn;?> D.CARDS</span>
				<input type="number" class="input-group-text form-control"  name="data_mtn"  value="<?php echo floatval(levelsDb("data_mtn"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $glo;?> D.CARDS</span>
				<input type="number" class="input-group-text form-control" name="data_glo" value="<?php echo floatval(levelsDb("data_glo"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $airtel;?> D.CARDS</span>
				<input type="number" class="input-group-text form-control" name="data_airtel" value="<?php echo floatval(levelsDb("data_airtel"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text"><?php echo $mobile;?> D.CARDS</span>
				<input type="number" class="input-group-text form-control" name="data_9mobile" value="<?php echo floatval(levelsDb("data_9mobile"));?>">
			</div>
		</div>
		
		
		<div class="col-12 col-sm my-3">
		<span> EPINS </span>
			<div class="input-group">
				<span class="input-group-text">WAEC EPINS</span>
				<input type="number" class="input-group-text form-control" name="epin_waec" value="<?php echo floatval(levelsDb("epin_waec"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text">NECO EPINS</span>
				<input type="number" class="input-group-text form-control"   name="epin_neco" value="<?php echo floatval(levelsDb("epin_neco"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text">JAMB EPINS</span>
				<input type="number" class="input-group-text form-control"  name="epin_jamb"  value="<?php echo floatval(levelsDb("epin_jamb"));?>">
			</div>
			<div class="input-group">
				<span class="input-group-text">NABTEB EPINS</span>
				<input type="number" class="input-group-text form-control"  name="epin_nabteb"  value="<?php echo floatval(levelsDb("epin_nabteb"));?>">
			</div>
		</div>
		

	</div>

</div>

</div>

<?php if(vp_option_array($option_array,"vtupress_custom_mlmsub") == "yes"){
  ?>
<div class="col-12 col-sm my-5 mx-1">
<span class="font-bold">
  Extra Feature 1: 
  <select name="enable_extra_service" class="form-select-sm" >
    <option value="<?php echo levelsDb("enable_extra_service");?>"><?php echo strtoupper(levelsDb("enable_extra_service"));?></option>
    <option value="enabled">Enable</option>
    <option value="disabled">Disable</option>
</select>


</span>
<div class="p-3">
<div class="row">
  <span class="pb-2"> SERVICE BONUSES TO USER: </span>
      <div class="input-group mx-2">
				<span class="input-group-text">Airtime <?php echo $symbol;?> -</span>
				<input type="number" class="input-group-text form-control"  name="airtime_bonus_ex1"  value="<?php echo floatval(levelsDb("airtime_bonus_ex1"));?>">
				<span class="input-group-text">Data</span>
				<select class="form-control" name="data_bonus_ex1" >
          <option value="<?php echo levelsDb("data_bonus_ex1");?>"><?php echo strtoupper(levelsDb("data_bonus_ex1"));?></option>
          <option value="500 MB">500 MB</option>
          <option value="1 GB">1 GB</option>
          <option value="1.5 GB">1.5 GB</option>
          <option value="2 GB">2 GB</option>
          <option value="3 GB">3 GB</option>
          <option value="4 GB">4 GB</option>
          <option value="5 GB">5 GB</option>
          <option value="10 GB">10 GB</option>
        </select>
        <span class="input-group-text">Data Type</span>
				<select class="form-control data_bonus_type_ex1" name="data_bonus_type_ex1" >
          <option value="<?php echo levelsDb("data_bonus_type_ex1");?>"><?php echo strtoupper(levelsDb("data_bonus_type_ex1"));?></option>
          <option value="SME">SME</option>
          <option value="CORPORATE">CORPORATE</option>
          <option value="DIRECT">DIRECT</option>
        </select>
          <script>
              jQuery(".data_bonus_type_ex1").on("change",function(){
                var thisVal = jQuery(this).val();
                jQuery(".ref_data_type").text(thisVal);

              });
          </script>
      </div>


  </div>

  <div class="row mt-3">
  <span class="pb-2"> SERVICE BONUSES TO REFERRER: </span>
      <div class="input-group mx-2">
				<span class="input-group-text">Airtime <?php echo $symbol;?> -</span>
				<input type="number" class="input-group-text form-control"  name="ref_airtime_bonus_ex1"  value="<?php echo floatval(levelsDb("ref_airtime_bonus_ex1"));?>">
				<span class="input-group-text">Data</span>
				<select class="form-control" name="ref_data_bonus_ex1" >
          <option value="<?php echo levelsDb("ref_data_bonus_ex1");?>"><?php echo strtoupper(levelsDb("ref_data_bonus_ex1"));?></option>
          <option value="500 MB">500 MB</option>
          <option value="1 GB">1 GB</option>
          <option value="1.5 GB">1.5 GB</option>
          <option value="2 GB">2 GB</option>
          <option value="3 GB">3 GB</option>
          <option value="4 GB">4 GB</option>
          <option value="5 GB">5 GB</option>
          <option value="10 GB">10 GB</option>
        </select>
        <span class="input-group-text">Data Type</span>
        <span class="input-group-text ref_data_type"><?php echo strtoupper(levelsDb("data_bonus_type_ex1"));?></span>

      </div>


  </div>

  <div class="mt-3">

    <small>This bonus transaction is carried out via this website API transaction. It is required that the assigned user Id below has access to API transaction i.e it should be on a plan that has api access and the apikey is not empty of null</small>
    <br>
    <div class="input-group mx-2">
    <span class="input-group-text"> User ID:</span>
				<input type="number" class="input-group-text form-control"  name="extra_feature_assigned_uId"  value="<?php echo floatval(levelsDb("extra_feature_assigned_uId"));?>">
        <span class="input-group-text"> User ApiKey:</span>
				<input type="text" class="input-group-text form-control" disabled   value="<?php echo vp_getuser(floatval(levelsDb("extra_feature_assigned_uId")),"vr_id", true);?>">
    </div>

  </div>
</div>
</div>

<?php
}
?>

<div class="col-12 col-sm my-5 mx-1">
		<span class="font-bold">Referral System (Calculated In %):</span>
		<div class="p-3">
	<div class="row">
	
		<div class="col-12 col-sm my-3">
		<span class="mb-4">Note that the values entered here are Bonuses(%) of what the referrer of each generation would get if their referee is on this Package except for Upgrade Bonuses which are fixed </span>
    <br>
		<?php $total_leve = levelsDb("total_level"); 
		$total_level = floatval($total_leve);
		
		for($level = 1; $level <= $total_level; $level++){

$lev = $level;

        if(!isset($datas[0]->{"level_".$lev."_data"})){
          global $wpdb;
          $table = $wpdb->prefix."vp_levels";
        maybe_add_column($table,'level_'.$lev.'_data', "ALTER TABLE $table ADD level_{$lev}_data DECIMAL(5,2)");
        maybe_add_column($table,'level_'.$lev.'_cable', "ALTER TABLE $table ADD level_{$lev}_cable DECIMAL(5,2)");
        maybe_add_column($table,'level_'.$lev.'_bill', "ALTER TABLE $table ADD level_{$lev}_bill DECIMAL(5,2)");
        maybe_add_column($table,'level_'.$lev.'_ecards', "ALTER TABLE $table ADD level_{$lev}_ecards DECIMAL(5,2)");
        maybe_add_column($table,'level_'.$lev.'_edatas', "ALTER TABLE $table ADD level_{$lev}_edatas DECIMAL(5,2)");
        maybe_add_column($table,'level_'.$lev.'_epins', "ALTER TABLE $table ADD level_{$lev}_epins DECIMAL(5,2)");
      
        maybe_add_column($table,'level_'.$lev.'_pv', "ALTER TABLE $table ADD level_{$lev}_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_data_pv', "ALTER TABLE $table ADD level_{$lev}_data_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_cable_pv', "ALTER TABLE $table ADD level_{$lev}_cable_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_bill_pv', "ALTER TABLE $table ADD level_{$lev}_bill_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_ecards_pv', "ALTER TABLE $table ADD level_{$lev}_ecards_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_edatas_pv', "ALTER TABLE $table ADD level_{$lev}_edatas_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev.'_epins_pv', "ALTER TABLE $table ADD level_{$lev}_epins_pv DECIMAL(5,2)");
      maybe_add_column($table,'level_'.$lev."_upgrade_pv", "ALTER TABLE $table ADD level_{$lev}_upgrade_pv DECIMAL(5,2)");
      
      
        }

			$level_price = $datas[0]->{"level_".$level};
      
      $level_data_price = vp_getvalue($datas[0]->{"level_".$level."_data"});
      
      $level_cable_price = vp_getvalue($datas[0]->{"level_".$level."_cable"});
      $level_bill_price = vp_getvalue($datas[0]->{"level_".$level."_bill"});
      $level_ecards_price = vp_getvalue($datas[0]->{"level_".$level."_ecards"});
      $level_edatas_price = vp_getvalue($datas[0]->{"level_".$level."_edatas"});
      $level_epins_price = vp_getvalue($datas[0]->{"level_".$level."_epins"});
			$level_price_upgrade = vp_getvalue($datas[0]->{"level_".$level."_upgrade"});
      $level_upgrade_pv = vp_getvalue($datas[0]->{"level_".$level."_upgrade_pv"});

      $level_pv = $datas[0]->{"level_".$level."_pv"};
      $level_data_pv = vp_getvalue($datas[0]->{"level_".$level."_data_pv"});
      $level_cable_pv = vp_getvalue($datas[0]->{"level_".$level."_cable_pv"});
      $level_bill_pv = vp_getvalue($datas[0]->{"level_".$level."_bill_pv"});
      $level_ecards_pv = vp_getvalue($datas[0]->{"level_".$level."_ecards_pv"});
      $level_edatas_pv = vp_getvalue($datas[0]->{"level_".$level."_edatas_pv"});
      $level_epins_pv = vp_getvalue($datas[0]->{"level_".$level."_epins_pv"});

			?>
      <div class="mb-3 p-3 border border-secondary">
        <h4 class="m-1" >Generation (<?php echo  $level;?>):</h4>
			<div class="input-group">
      <span class="input-group-text">Level_<?php echo $level;?> - Airtime Bonus %</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>"  value="<?php echo floatval($level_price);?>">

    <span class="input-group-text">Level_<?php echo $level;?> - Airtime Pv</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_pv"  value="<?php echo floatval($level_pv);?>">
    </div>
        
    <div class="input-group">
    <span class="input-group-text">Level_<?php echo $level;?> - Data Bonus %</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_data"  value="<?php echo floatval($level_data_price);?>">

    <span class="input-group-text">Level_<?php echo $level;?> - Data Pv</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_data_pv"  value="<?php echo floatval($level_data_pv);?>">
    </div>
        
    <div class="input-group">
    <span class="input-group-text">Level_<?php echo $level;?> - Cable Bonus %</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_cable"  value="<?php echo floatval($level_cable_price);?>">

    <span class="input-group-text">Level_<?php echo $level;?> - Cable Pv</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_cable_pv"  value="<?php echo floatval($level_cable_pv);?>">
    </div>
    
    
    <div class="input-group">
    <span class="input-group-text">Level_<?php echo $level;?> - Bill Bonus %</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_bill"  value="<?php echo floatval($level_bill_price);?>">

    <span class="input-group-text">Level_<?php echo $level;?> - Bill Pv</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_bill_pv"  value="<?php echo floatval($level_bill_pv);?>">
    </div>
        
    <div class="input-group">
    <span class="input-group-text visually-hidden">Level_<?php echo $level;?> - ECards Bonus %</span>
		<input type="number" class="input-group-text  visually-hidden"   name="level_<?php echo $level;?>_ecards"  value="<?php echo floatval($level_ecards_price);?>">

    <span class="input-group-text visually-hidden">Level_<?php echo $level;?> - ECards Pv</span>
		<input type="number" class="input-group-text visually-hidden"   name="level_<?php echo $level;?>_ecards_pv"  value="<?php echo floatval($level_ecards_pv);?>">
    </div>

    <div class="input-group">
    <span class="input-group-text visually-hidden">Level_<?php echo $level;?> - D.Card Bonus %</span>
		<input type="number" class="input-group-text  visually-hidden"   name="level_<?php echo $level;?>_edatas"  value="<?php echo floatval($level_edatas_price);?>">

    <span class="input-group-text visually-hidden">Level_<?php echo $level;?> - D.Card Pv</span>
		<input type="number" class="input-group-text visually-hidden"   name="level_<?php echo $level;?>_edatas_pv"  value="<?php echo floatval($level_edatas_pv);?>">
    </div>
        
    <div class="input-group">
    <span class="input-group-text  visually-hidden">Level_<?php echo $level;?> - Epins Bonus %</span>
		<input type="number" class="input-group-text  visually-hidden"   name="level_<?php echo $level;?>_epins"  value="<?php echo floatval($level_epins_price);?>">

    <span class="input-group-text visually-hidden">Level_<?php echo $level;?> - EPins Pv</span>
		<input type="number" class="input-group-text visually-hidden"   name="level_<?php echo $level;?>_epins_pv"  value="<?php echo floatval($level_epins_pv);?>">
    </div>
        
    <div class="input-group">
    <span class="input-group-text">Level_<?php echo $level;?> - Upgrade Bonus <?php echo $currency;?></span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_upgrade"  value="<?php echo floatval($level_price_upgrade);?>">

    <span class="input-group-text">Level_<?php echo $level;?> - Upgrade PV</span>
		<input type="number" class="input-group-text form-control"   name="level_<?php echo $level;?>_upgrade_pv"  value="<?php echo floatval($level_upgrade_pv);?>">
		
			</div>
      </div>
		<?php
		}
		?>
		</div>

		<input type="button" class="create_ref_level mb-1" value="Add A Referral Level">
		<?php
		if($total_level > "0"){
			?>
		<input type="button" class="delete_ref_level" value="Delete Last Referral Level">
	<?php
		}
		?>
	</div>

</div>

</div>

<input type="button" class="update_level btn btn-primary" value="UPDATE <?php echo strtoupper(levelsDb("name"));?> LEVEL ">
</form>
</div><!--ROW DIV END-->

<!--SCRIPT-->
<script>



jQuery(".delete_ref_level").on("click",function(){
	
var obj = {};
obj["level_action"] = "delete_ref_level";
obj["level_id"] = jQuery(".level_id").val();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";
jQuery("#cover-spin").show();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/levels.php"));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        }  else if (jqXHR.status == 403) {
            msg = "Access Forbidden [403].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data){
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful",
  text: "LAST REFERRAL LEVEL DELETED",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Failed To Delete Last Referral Level",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
  type: 'POST'
});



});



jQuery(".create_ref_level").on("click",function(){
	
var obj = {};
obj["level_action"] = "create_ref_level";
obj["level_id"] = jQuery(".level_id").val();

	jQuery("#cover-spin").show();
  obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";


jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/levels.php"));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data){
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful",
  text: "REFERRAL LEVEL CREATED",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Failed To Create Referral Level",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
  type: 'POST'
});
});




jQuery(".create_level").on("click",function(){
	
var obj = {};
obj["level_action"] = "create_level";
obj["level_name"] = jQuery(".level_name").val();

	jQuery("#cover-spin").show();

  obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/levels.php"));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data){
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful",
  text: "LEVEL CREATED",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Failed To Create Level",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
  type: 'POST'
});
});



jQuery(".delete_level").on("click",function(){
	
var obj = {};
obj["level_action"] = "delete_level";
obj["level_id"] = jQuery(".level_id").val();
obj["level_name"] = jQuery(".d_level_name").val();
	jQuery("#cover-spin").show();
var name = jQuery(".d_level_name").val();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

if(confirm("Do you want to delete plan "+name.toUpperCase()+"?") == true){
if(name != "customer" && name != "reseller"){
jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/levels.php"));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data){
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful",
  text: "LEVEL "+name+" DELETED",
  icon: "success",
  button: "Okay",
}).then((value) => {
	window.location = "?page=vtulevels";
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Failed To Delete Level",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
  type: 'POST'
});
}
else{
alert("You can't Delete Default Levels Namely [Customer/Reseller]");
	jQuery("#cover-spin").hide();	
}
}
else{
	
	jQuery("#cover-spin").hide();
	
}


});



jQuery(".update_level").on("click",function(){

	jQuery("#cover-spin").show();

var obj = {};
obj["level_action"] = "update_level";
obj["level_id"] = "<?php echo levelsDb("id"); ?>";
var toatl_input = jQuery(".vtupress_level select, .vtupress_level input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".vtupress_level select, .vtupress_level input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
}

	jQuery("#cover-spin").show();

  obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/levels.php"));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection. Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error." + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data){
	  jQuery("#cover-spin").hide();
        if(data == "100"){
		  swal({
  title: "Successful",
  text: "LEVEL UPDATED",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Failed To Update Level",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
  type: 'POST'
});
});


jQuery(".level_id").on("change",function(){
jQuery("#cover-spin").show();

var id = jQuery(".level_id").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&level="+id;

});

</script>


</div>





                  </div>
                </div>
              </div>
</div>


</div>



</div>
<?php   
}?>