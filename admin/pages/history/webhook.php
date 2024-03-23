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
//nothing
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


pagination_webhook();
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
                  <h5 class="card-title">Webhooks</h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                Webhool Url:
            </div>
            <div class="col col-11">
                <?php
                echo esc_url(plugins_url("vtupress/webhook.php"));
                ?>
            </div>
    </div>


<div class="row">
<div class="col">
<table class="table table-striped table-hover table-bordered table-responsive overflow-auto">
<thead>
<tr>
<th scope='row' class="p-2">ID</th>
<th scope='row' class="p-2">Service ID</th>
<th scope='row' class="p-2">Service Type</th>
<th scope='row' class="p-2">Request ID</th>
<th scope='row' class="p-2">Response ID</th>
<th scope='row' class="p-2">CallBack Source</th>
<th scope='row' class="p-2">Webhook Response</th>
<th scope='row' class="p-2">CallBack Time</th>
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
        $service_id = $webhook->service_id;
        $service = $webhook->service;
        $request_id = $webhook->request_id;
        $response_id = $webhook->response_id;
        $ip = $webhook->server_ip;
        $time = $webhook->the_time;
        $response = $webhook->resp_log;
        echo"
        <tr>
        <td scope='col'>$id</td>
        <td scope='col'>$service_id</td>
        <td scope='col'>$service</td>
        <td scope='col'>$request_id </td>
        <td scope='col'>$response_id</td>
        <td scope='col'>$ip</td>
        <td scope='col'>$response</td>
        <td scope='col'>$time</td>
        </tr>
        ";
    }
}
?>


</tbody>
<tfoot>
<tr>
<th scope='row' class="p-2">ID</th>
<th scope='row' class="p-2">Service ID</th>
<th scope='row' class="p-2">Service Type</th>
<th scope='row' class="p-2">Request ID</th>
<th scope='row' class="p-2">Response ID</th>
<th scope='row' class="p-2">CallBack Source</th>
<th scope='row' class="p-2">Webhook Response</th>
<th scope='row' class="p-2">CallBack Time</th>
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