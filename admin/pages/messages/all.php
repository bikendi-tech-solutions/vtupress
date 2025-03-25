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
//include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/messages/functions.php');
global $wpdb;
$messd = $wpdb->prefix."vp_chat";
$messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' AND status = 'unread' ORDER BY id DESC LIMIT 10) AS x GROUP BY user_id");

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
                  <h5 class="card-title">All Messages</h5>
 
 <div class="input-group m-3">
 <input class="form-control border-end-0 border rounded-pill search-ser" type="search" placeholder="search by user ID or UserName" id="example-search-input">
 <span class="input-group-append">
    <button onclick="searchduser(jQuery('.search-ser').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
        <i class="fa fa-search"></i>
    </button>
 </span>
 </div>
 <script>
 function searchduser(val){
 location.href = "<?php echo $_SERVER["REQUEST_URI"];?>&mess="+val;
 }
 </script>

<?
$user_id = $_GET["user_id"];
if(!isset($_GET["mess"])){
pagination_message_before("WHERE user_id = $user_id ");
}
else{
  $message = $_GET["mess"];
  if(is_numeric($message)){
  pagination_message_before("WHERE user_id = $message ");//AND message LIKE %'$message'%
  }
  else{
    pagination_message_before("WHERE name = $message ");//AND message LIKE %'$message'%
  }
}
?>

                  <div class="table-responsive row">
                    <div class="table-responsive border border-secondary scrollable col-12 col-md-3"  style="max-height: 475px">
                    
                    <?php
                    foreach($messages as $message){

                    ?>
                      <div class="mt-2 p-2 border border-success">
                        <div class="input-group">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                              />
                      <span class="mb-3 d-block"
                        ><?php echo substr($message->message,0,20);?>
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
                     <div class="chat-box col-12 col-md-9 scrollable" style="max-height: 475px">
                    <!--chat Row -->
                    <ul class="chat-list">
                      <!--chat Row RECEIVED-->

                      <!--chat Row SEND -->
 

                    <?php
global $messages;

if($messages == "null"){

}
else{
    $sid = $messages[0]->user_id;
    $udata = [ 'status' => 'read' ];
    $where = [ 'user_id' => $sid, 'type' => 'received' ];
    $updated = $wpdb->update( $messd , $udata, $where);
foreach($messages as $message){
if($message->type == "received"){
?>
          <li class="chat-item">
                        <div class="chat-img">
                          <img src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg" alt="user" />
                        </div>
                        <div class="chat-content">
                          <h6 class="font-medium"><?php echo ucfirst($message->name);?></h6>
                          <div class="box bg-light-info">
                          <?php echo $message->message;?>
                          </div>
                        </div>
                        <div class="chat-time"><?php echo $message->the_time;?></div>
          </li>
<?php
}
else{
?>

<li class="odd chat-item">
                        <div class="chat-content">
                        <?php
                          if($message->status == 'unread'){
?>
                          <div class="box p-3 bg-light-inverse text-white" >
<?php
                          }else{
                            ?>
                          <div class="box p-3 bg-success text-white" >
                            <?php

                          }
                          ?>
                          <?php echo $message->message;?>
                          </div>
                          <br />
                        </div>
</li>


<?php
}

}
}
?>


                    </ul>
                  </div>
              
                <div class="card-body border-top">
                  <div class="row">
                    <div class="col-9">
                      <div class="input-field mt-0 mb-0">
                        <textarea
                          id="textarea1"
                          placeholder="Type and enter"
                          class="form-control border-0 send-message"
                        ></textarea>
                      </div>
                    </div>
                    <div class="col-3">
                      <a onclick="sendmessage()"
                        class="btn-circle btn-lg btn-cyan float-end text-white"
                        href="javascript:void(0)"
                        >
                        <i class="mdi mdi-send fs-3 send-btn"></i
                      >
                      <div class="spinner-grow sending" role="status">
                      </div>
                    
                    </a>
                    </div>
                  </div>
                </div>

<script>

jQuery(".sending").hide();

jQuery('.chat-box').scrollTop(jQuery('.chat-box')[0].scrollHeight);
function sendmessage(){
var obj = {};
obj["send"] = "yes";
obj["user_id"] = "<?php echo intval($_GET["user_id"]);?>";
obj["message"] = jQuery(".send-message").val();

jQuery(".sending").show();
jQuery(".send-btn").hide();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

  jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/messages/functions.php'));?>",
data : obj,
dataType: 'text',
'cache': false,
 "async": true,
error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
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
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	  jQuery(".preloader").hide();
        if(data == "100"){

	location.reload();

	  }
	  else{
		 jQuery(".preloader").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Error Changing Status",
  text: "Click Why To See Reason",
  icon: "warning",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
		location.reload();
      break;
    default:
      swal(data, {
      icon: "info",
    });
  }
});
	  }
  },
type : 'POST'
});


}

</script>

          
          </div>
                </div>
              </div>
</div>


</div>