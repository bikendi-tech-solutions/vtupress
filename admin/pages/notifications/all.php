<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/notifications/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


global $wpdb;
$messd = $wpdb->prefix."vp_notifications";
//$messages = $wpdb->get_results("SELECT * FROM $noti WHERE status = 'unread' ORDER BY id DESC LIMIT 3");

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
                  <h5 class="card-title">All Notifications</h5>
 
 <div class="input-group m-3">
  <select class="search_by me-2">
  <option value="id">ID</option>
  <option value="user_id">User ID</option>
</select>
 <input class="form-control border-end-0 border rounded-pill search-ser" type="search by id" placeholder="search " id="example-search-input">
 <span class="input-group-append">
    <button onclick="searchduser(jQuery('.search_by').val(), jQuery('.search-ser').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
        <i class="fa fa-search"></i>
    </button>
 </span>
 </div>
 <script>
 function searchduser(by,val){
 location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&sb="+by+"&did="+val;
 }
 </script>

<?

if(!isset($_GET["status"])){
if(!isset($_GET["did"]) && !isset($_GET["sb"])){
pagination_no_before();
}
else{
  $message = $_GET["did"];
  $by = $_GET["sb"];
  if(is_numeric($message)){
  pagination_no_before("AND $by = $message ");//AND message LIKE %'$message'%
  }
}
}
else{
  $status = $_GET["status"];
  if(!isset($_GET["did"]) && !isset($_GET["sb"])){
    pagination_no_before("AND status = '$status' ");
    }
    else{
      $message = $_GET["did"];
      $by = $_GET["sb"];
      if(is_numeric($message)){
      pagination_no_before("AND $by = $message AND status = '$status' ");//AND message LIKE %'$message'%
      }
    }
}
?>

<button onclick="searchby('all')" class="btn btn-sm bg bg-primary text-white">All</button> <button class="btn text-white btn-sm bg bg-success" onclick="searchby('withdrawal')">Withdrawal </button> <button class="btn text-white btn-sm bg bg-info" onclick="searchby('transfer')">Transfers </button> <button  class="btn text-white btn-sm bg bg-warning" onclick="searchby('kyc')">KYC</button>
<select onchange="statusit(this.value)">
  <?php
  if(isset($_GET["status"])){
    if($_GET["status"] == "read"){
      echo'
      <option value="read" disabled selected >Read</option>
      <option value="unread">Un-Read</option>
      ';
          }
          else{
            echo'
            <option value="read">Read</option>
            <option value="unread" disabled selected >Un-Read</option>
            ';
                }
  }else{
    echo'
    <option value="" disabled selected >--Select--</option>
    <option value="read">Read</option>
    <option value="unread">Un-Read</option>
    ';
  }
  ?>
</select>

<script>
  function statusit(status){
   
    switch(status){
      case"read":
        location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&status="+status;
        break;
        case"unread":
          location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&status="+status;
          break;
    }
  }


function searchby(val){

  switch(val){
    case"all":
      location.href = "?page=vtupanel&adminpage=notifications";
      break;

      case"withdrawal":
        location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&type="+val;
        break;
       
        case"transfer":
          location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&type="+val;
          break;

          case"kyc":
          location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&type="+val;
          break;
  }
}

</script>
                  <div class="table-responsive row">
                    <div class="table-responsive border border-secondary scrollable col-12"  style="max-height: 475px">
                    
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
<th scope='col' class=''>ID</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>Type</th>
<th scope='col' class=''>Title</th>
<th scope='col' class=''>Notifications</th>
<th scope='col' class=''>Time</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $messages;
if($messages == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No Notifications</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);

foreach($messages as $result){
  $id = $messages[0]->id;
  $udata = [ 'status' => 'read' ];
  $where = [ 'id' => $id];
  $updated = $wpdb->update( $messd , $udata, $where);

    echo"
    <tr>
    <th class=''>
    <label class=\"customcheckbox\">
      <input type=\"checkbox\" class=\"listCheckbox\" value=\"$result->id\" />
      <span class=\"checkmark\"></span>
    </label>
  </th>
  <th scope='col' class=''>$result->id</th>
  <th scope='col' class=''>$result->user_id</th>
  <th scope='col' class=''>$result->type</th>
  <th scope='col' class=''>$result->title</th>
  <th scope='col' class=''>$result->message</th>
  <th scope='col' class=''>$result->the_time</th>


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
<th scope='col' class=''>ID</th>
<th scope='col' class=''>User ID</th>
<th scope='col' class=''>Type</th>
<th scope='col' class=''>Title</th>
<th scope='col' class=''>Notifications</th>
<th scope='col' class=''>Time</th>
</tr>
                      </tfoot>
                    </table>

<div class="input-group">
  <span class="input-group-text">Bulk Action</span>

  <select onchange="openfunction('vp_notification','Notifications',false)">
                      <option >--Select--</option>
                      <option value="delete">Delete Selected Record</option>
</select>
<?php include_once(ABSPATH."wp-content/plugins/vtupress/admin/pages/history/history.php");?>
    
</div>



                     </div>
              


<script>


</script>

          
          </div>
                </div>
              </div>
</div>


</div>