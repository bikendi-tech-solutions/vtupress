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
                  <h5 class="card-title">Failed Withdrawals</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_withdrawal_before("vp_withdrawal","null");
}
elseif(empty($_GET["trans_id"])){
  pagination_withdrawal_before("vp_withdrawal","null");
}
else{
    if(is_numeric($_GET["trans_id"])){
        $id = $_GET["trans_id"];
        pagination_wthdrawal_before("vp_withdrawal","null","AND id = '$id'");
    }
    else{
        $id = $_GET["trans_id"];
        pagination_withdrawal_before("vp_withdrawal","null");
    }
}


?>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                      <tr>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>User Name</th>
<th scope='col' class=''>Details</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Time</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No Failed Withdrawals Found</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);
global $wpdb;
#GET LEVELS
$table_name = $wpdb->prefix."vp_levels";
$level = $wpdb->get_results("SELECT * FROM  $table_name");

$current_amt = 0;

foreach($transactions as $result){


    echo"
    <tr>
    <th scope='row'>".$result->id."</th>
    <td>".$result->user_id."</td>
    <td>".$result->name."</td>
    <td>".$result->description."</td>
    <td>".$result->amount."</td>
    <td>".$result->the_time."</td>


  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>User Name</th>
<th scope='col' class=''>Details</th>
<th scope='col' class=''>Amount</th>
<th scope='col' class=''>Time</th>
</tr>
                      </tfoot>
                    </table>

                  </div>
                </div>
              </div>
</div>


</div>