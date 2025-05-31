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
                  <h5 class="card-title">Bank Transfers</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_wallet_before("WHERE type = 'Transfer' ");
}
elseif(empty($_GET["trans_id"])){
    pagination_wallet_before("WHERE type = 'Transfer' ");
}
else{
    if(is_numeric($_GET["trans_id"])){
        $id = $_GET["trans_id"];
        pagination_wallet_before("WHERE id = '$id' AND type = 'Transfer' ");
    }
    else{
        $id = str_replace("u","",$_GET["trans_id"]);
        pagination_wallet_before("WHERE user_id = '$id' AND type = 'Transfer'");
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
<th scope='col'>Id</th>
<th scope='col'>By</th>
<th scope='col'>To</th>
<th scope='col'>Type</th>
<th scope='col'>Amount</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>Description</th>
<th scope='col'>status</th>
<th scope='col'>User_Id</th>
<th scope='col'>time</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No Fundings</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);



foreach($transactions as $resultsa){


    echo "
    <tr>
    <td scope='row'>".$resultsa->id."</td>
    <td>".$resultsa->name."</td>
    <td>".get_userdata($resultsa->user_id)->user_login."</td>
    <td>".$resultsa->type."</td>
    <td>".$resultsa->fund_amount."</td>
    <td>".$resultsa->before_amount."</td>
    <td>".$resultsa->now_amount."</td>
    <td>".$resultsa->description."</td>
    
";
if(strtolower($resultsa->status) == "approved" || $resultsa->status == "Approve"  || $resultsa->status == "Successful"){
	echo"
<td><span class='btn-success rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
elseif($resultsa->status == "Pending"  || $resultsa->status == "Processing"){
		echo"
<td><span class='btn-info rounded shadow p-1'>".$resultsa->status."</span></td>
";
}
else{
	echo"
<td><span class='btn-danger text-white rounded shadow p-1'>".$resultsa->status."</span></td>
";
}

echo"


<td>".$resultsa->user_id."</td>
<td>".$resultsa->the_time."</td>
  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
<th scope='col'>Id</th>
<th scope='col'>By</th>
<th scope='col'>To</th>
<th scope='col'>Type</th>
<th scope='col'>Amount</th>
<th scope='col'>Previous Balance</th>
<th scope='col'>Current Balance</th>
<th scope='col'>Description</th>
<th scope='col'>status</th>
<th scope='col'>User_Id</th>
<th scope='col'>time</th>
</tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>
</div>


</div>