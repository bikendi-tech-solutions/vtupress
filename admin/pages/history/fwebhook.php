<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');
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


pagination_fwebhook();
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
                  <h5 class="card-title">Funding Webhooks</h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                Webhool Url:
            </div>
            <div class="col col-11">
                <?php
                echo esc_url(plugins_url("vtupress/index.php"));
                ?>
            </div>
    </div>


<div class="row">
<div class="col">
<table class="table table-striped table-hover table-bordered table-responsive overflow-auto">
<thead>
<tr>
<th scope='row' class="p-2">ID</th>
<th scope='row' class="p-2">User ID</th>
<th scope='row' class="p-2">Gateway</th>
<th scope='row' class="p-2">Amount</th>
<th scope='row' class="p-2">Referrence</th>
<th scope='row' class="p-2">Status</th>
<th scope='row' class="p-2">Time</th>
<th scope='row' class="p-2">CallBack Response</th>
</tr>
</thead>
<tbody class="overflow-auto">

<?php
global $result;
if($result == "null"){
echo"
<tr>
<td colspan='8' class='align-center'>
No Webhook CallBack Recorded
</td>
</tr>

";

}else{

    foreach($result as $webhook){
        $id = $webhook->id;
        $user_id = $webhook->user_id;
        $gateway = $webhook->gateway;
        $amount = $webhook->amount;
        $referrence = $webhook->referrence;
        $status = $webhook->status;
        $time = $webhook->the_time;
        $response = $webhook->response;
        echo"
        <tr>
        <td scope='col'>$id</td>
        <td scope='col'>$user_id</td>
        <td scope='col'>$gateway</td>
        <td scope='col'>$amount</td>
        <td scope='col'>$referrence</td>
        <td scope='col'>$status</td>
        <td scope='col'>$time</td>
        <td scope='col'>$response</td>
        </tr>
        ";
    }
}
?>


</tbody>
<tfoot>
<tr>
<th scope='row' class="p-2">ID</th>
<th scope='row' class="p-2">User ID</th>
<th scope='row' class="p-2">Gateway</th>
<th scope='row' class="p-2">Amount</th>
<th scope='row' class="p-2">Referrence</th>
<th scope='row' class="p-2">Status</th>
<th scope='row' class="p-2">Time</th>
<th scope='row' class="p-2">CallBack Response</th>
</tr>
</tfoot>
</table>


</div>

</div>

</div>





                  </div>
                </div>
              </div>
</div>


</div>



</div>