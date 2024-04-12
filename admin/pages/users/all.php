<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

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
                  <h5 class="card-title">All Users</h5>

                  <?php

if(!isset($_GET["user_id"])){
    pagination_users_before();
}
    else{
    if(is_numeric($_GET["user_id"])){
        $id = $_GET["user_id"];
        pagination_users_before("WHERE id = '$id'");
    }
    elseif(is_numeric(stripos($_GET["user_id"],'@')) ){
        $id = $_GET["user_id"];
        pagination_users_before("WHERE user_email = '$id'");
    }
    elseif(trim(strtolower($_GET["user_id"])) == 'all' || empty(strtolower($_GET["user_id"]))){
        $id = $_GET["user_id"];
        pagination_users_before();
    }
    else{
        $id = $_GET["user_id"];
        pagination_users_before("WHERE user_login = '$id'");
    }
}


?>

                  <div class="table-responsive">
                    <script>
function sortby(sort){

window.location = window.location.href+"&sortby="+sort;

}
                      </script>
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                        <tr>
                        <th class="">
                          <label class="customcheckbox mb-3">
                            <input type="checkbox" id="mainCheckbox"  />
                            <span class="checkmark"></span>
                          </label>
                        </th>
                        <th  onclick="sortby('ID');">ID</th>
                        <th onclick="sortby('user_login');">Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th onclick="sortby('vp_bal');">Balance</th>
                        <th>Pin</th>
                        <th>Plan</th>
                        <th>Pv</th>
                        <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $users;
if($users == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No User Found</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);
global $wpdb;
#GET LEVELS
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name");

$current_amt = 0;

foreach($users as $users){
     if(empty($users->vp_bal)){
      global $wpdb;
      $db = $wpdb->prefix."users";
$wpdb->update($db,['vp_bal' => 0],['ID' => $users->ID]);
     } 
        
    $id = $users->ID;
    
    $user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
    
    $name = get_userdata($id)->user_login;
    $email = get_userdata($id)->user_email;
    $pv = $users->vp_user_pv;

    $phone = vp_user_array($user_array,$id, "vp_phone", true);
    $balance = vp_user_array($user_array,$id, "vp_bal", true);
    
    $current_amt += $balance;
    

    $vpaccess = vp_user_array($user_array,$id, "vp_user_access", true);
	$pin = vp_user_array($user_array,$id, "vp_pin", true);

    if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes" ){
        $user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
    $apikey = vp_user_array($user_array,$id, 'vr_id', true);
    $plan = vp_user_array($user_array,$id,'vr_plan', true);
    }
    else{
        $apikey = "---";
        $plan = "---";
    }
    



    echo"
    <tr>
    <th class=''>
    <label class='customcheckbox'>
      <input type='checkbox' class='listCheckbox' value='$id' user='$id'/>
      <span class='checkmark'></span>
    </label>
  </th>
    <td>$id</td>
    <td>$name</td>
    <td>$email</td>
    <td>$phone</td>
    <td>$balance</td>
    <td>$pin</td>
    <td>$plan</td>
    <td>$pv</td>
  <td><button type='button' class='btn btn-secondary' onclick='loadinfo($id);'>Info</button></td>
  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                    <th class="">
<label class="customcheckbox mb-3">
  <input type="checkbox" id="mainCheckbox"  />
  <span class="checkmark"></span>
</label>
</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Balance</th>
                        <th>Pin</th>
                        <th>Plan</th>
                        <th>Pv</th>
                        <th>Action</th>
</tr>
                      </tfoot>
                    </table>

<div class="input-group">
  <span class="input-group-text">Bulk Action</span>

  <select onchange="multipleactions()" class="maction">
                      <option >--Select--</option>
                      <option value="mban">Ban</option>
                      <option value="munban">Un-Ban</option>
                      <option value="mrundan_monnify">Run-Dan Monnify</option>
                      <?php
                          if(vp_getoption("vtupress_custom_ncwallet") == "yes"){
                            ?>
                      <option value="mrundan_ncwallet">Run-Dan Ncwallet Africa</option>
                      <?php
                          }
                          if(vp_getoption("vtupress_custom_vpay") == "yes"){
                            ?>
                      <option value="mrundan_vpay">Run-Dan Vpay</option>
                      <?php
                          }
                          if(vp_getoption("vtupress_custom_kuda") == "yes"){
                            ?>
                      <option value="mrundan_kuda">Run-Dan Kuda</option>
                      <?php
                          }
                          if(vp_getoption("vtupress_custom_gtbank") == "yes"){
                            ?>
                      <option value="mrundan_squadco">Run-Dan Squadco</option>
                    <?php
                          }
                      ?>
</select>
<?php include_once(ABSPATH."wp-content/plugins/vtupress/admin/pages/users/users.php");?>
    
</div>

<script>
function loadinfo(id){

window.location = "?page=vtupanel&adminpage=users&subpage=info&id="+id;

}
</script>
                  </div>
                </div>
              </div>
</div>


</div>