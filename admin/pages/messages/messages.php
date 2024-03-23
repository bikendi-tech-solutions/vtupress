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
if(current_user_can("vtupress_access_users")){
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



global $wpdb;
$messd = $wpdb->prefix."vp_chat";

if(!isset($_GET["status"])){
$messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' AND status = 'unread' ORDER BY id DESC LIMIT 200) AS x GROUP BY user_id");
}
elseif($_GET["status"] == "unread"){
  $messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' AND status = 'unread' ORDER BY id DESC LIMIT 200) AS x GROUP BY user_id");
}
elseif($_GET["status"] == "read"){
  $messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' AND status = 'read' ORDER BY id DESC LIMIT 200) AS x GROUP BY user_id");
}
else{
  $messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' ORDER BY id DESC LIMIT 200) AS x GROUP BY user_id");
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
                  <h5 class="card-title">Messages</h5> 
                  <div class="table-responsive">
<div class="p-4 row">

<div class="m-4">
<button class="btn bg bg-danger text-white" onclick="viewunread()">Unread Messages</button>
<button class="btn bg bg-success text-white" onclick="viewread()">Read Messages</button>
<button class="btn bg bg-info text-white" onclick="viewall()">All Messages</button>

<script>
function viewunread(){
location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&status=unread"
}

function viewread(){
location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&status=read"
}

function viewall(){
location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&status=all"
}

</script>
</div>

<?php
foreach($messages as $message){
?>

<div class="mt-2 p-2 col col-md-3 border border-success">
                        <div class="input-group">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                              />
                      <span class="mb-3 d-block"
                        ><?php echo substr($message->message,0,50);?>
                      </span>
                    </div>
                        <span class="text-muted float-end"><?php echo $message->name;?></span>
                       <a href="?page=vtupanel&adminpage=messages&user_id=<?php echo $message->user_id;?>"class="btn"> <button
                          type="button"
                          class="btn btn-cyan btn-sm text-white"
                        >
                          Read
                        </button></a>
</div>

<?php
}

?>

</div>





                  </div>
                </div>
              </div>
</div>


</div>



</div>
<?php   
}?>