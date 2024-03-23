<?php
if( vp_option_array($option_array,"resell") == "yes"){		
			if(isset($_GET["vend"]) && $_GET["vend"]=="message"){
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/messages/functions.php');

				echo'

   
		
<div class="dark-white" style="background-color:white; padding:20px; border-radius:10px;">';

$user_id = get_current_user_id();
if(!isset($_GET["message"])){
    pagination_message_user_before("WHERE user_id = $user_id ");
}
else{
  $message = $_GET["message"];
  pagination_message_user_before("WHERE user_id = $user_id AND message LIKE '%$message%'"); 
}
?>
<!--<link href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/css/style.min.css" rel="stylesheet" />-->
 
<div class="dark-white chat-box  scrollable" >

<div class="container-fluid chat-box2 table-responsive" style="max-height: 475px; overflow-y:auto;">
                    <!--chat Row -->
                    <ul class="chat-list">
                      <!--chat Row RECEIVED-->

                      <!--chat Row SEND -->
 

                    <?php
    

global $messages;

if($messages == "null"){

}
else{
global $wpdb;
$messd = $wpdb->prefix."vp_chat";
  $sid = get_current_user_id();
  $udata = [ 'status' => 'read' ];
  $where = [ 'user_id' => $sid, 'type'=> 'send' ];
  $updated = $wpdb->update( $messd , $udata, $where);

foreach($messages as $message){
if($message->type == "send"){
?>
<div class="row w-100 m-2 bg text-black">
          <div class="col-6 chat-item float-start ">
                        <div class="chat-content">
                          <div class="box p-3 bg-secondary text-white"  >
                          <?php echo $message->message;?>
                          </div>
                        </div>
</div>
</div>
<?php
}
else{
?>
<div class="row w-100 m-2">
    <div class="col-6  p-3 ">

</div>
<div class=" col-6  d-flex odd float-right chat-item justify-content-end">
                        <div class="chat-content">
                          <?php
                          if($message->status == 'unread'){
?>
                          <div class="box p-3 bg-primary text-white" >
<?php
                          }else{
                            ?>
                          <div class="box p-3 bg-success text-white">
                            <?php

                          }
                          ?>

                          <?php echo $message->message;?>
                          </div>
                          <br />
                        </div>
</div>
</div>


<?php
}

}
}
?>


                    </ul>
                  </div>
</div>
              
                <div class="dark-white card-body border-top">
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
                        class="btn-circle btn-lg btn-primary text-white"
                        href="javascript:void(0)"
                        >
                        <span class="send-btn">SEND</span>
                        <div class="spinner-grow sending" role="status">
                      </div>
                    </a>
                    </div>
                  </div>
                </div>

<script>

jQuery('.chat-box2').scrollTop(jQuery('.chat-box2')[0].scrollHeight);

jQuery(".sending").hide();


function sendmessage(){
var obj = {};
obj["receive"] = "yes";
obj["user_id"] = "<?php echo get_current_user_id();?>";
obj["message"] = jQuery(".send-message").val();

jQuery(".sending").show();
jQuery(".send-btn").hide();

  jQuery.ajax({
url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/messages/functions.php'));?>",
data : obj,
dataType: 'text',
'cache': false,
 "async": true,
error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
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
	  jQuery("#cover-spin").hide();
        if(data == "100"){

	location.reload();

	  }
	  else{
		 jQuery("#cover-spin").hide();
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

<?php
	
echo'
</div>
			
		';
			}
		}
		
		?>