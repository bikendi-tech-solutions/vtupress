<?php
if(isset($_GET["vend"]) && $_GET["vend"]=="account"){
    $id = get_current_user_id();


if(vp_getuser($id,"update_profile",true) != "v2"){
    global $wpdb;
    $kyc = $wpdb->prefix.'vp_kyc';
    $profile = $wpdb->prefix.'vp_profile';

    $profiling = $wpdb->get_results("SELECT * FROM $kyc WHERE user_id = $id");

if(isset($profiling) && !empty($profiling)){
    $selfieh = $profiling[0]->selfie;
    global $wpdb;
    $wpdb->insert( $profile, array(
        'user_id'=> $id,
        'photo_link' => $selfieh,
        'the_time' => date('Y-m-d H:i:s A')
        ));
   # $updated = $wpdb->update( $profile, ['photo_link' => $selfieh], ["user_id" => $id]);

}

vp_updateuser($id,"update_profile","v2");

    }


global $wpdb;
$table = $wpdb->prefix."vp_profile";
$profile = $wpdb->get_results("SELECT * FROM $table WHERE user_id = $id ");


		?>
		<style>
	.form-container{
    background: linear-gradient(150deg,#DFE4E8 33%,#F4F6F9 34%,#ECF2F6 66%,#F4F6F9 67%);
    font-family: 'Raleway', sans-serif;
    text-align: center;
    padding: 30px 20px 50px;
}
.form-container .title{
    color: #fff;
    font-size: 23px;
    text-transform: capitalize;
    letter-spacing: 1px;
    margin: 0 0 60px;
}
.form-container .form-horizontal{
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
}
.form-horizontal .form-icon{
    color: #fff;
    background-color: #1B394D;
    font-size: 75px;
    line-height: 92px;
    height: 90px;
    width: 90px;
    margin: -65px auto 10px;
    border-radius: 50%;
}
.form-horizontal .form-group{
    margin: 0 0 10px;
    position: relative;
}
.form-horizontal .form-group:nth-child(3){ margin-bottom: 30px; }
.form-horizontal .form-group .input-icon{
    color: #e7e7e7;
    font-size: 23px;
    position: absolute;
    left: 7px;
    top: 5px;
}
.form-horizontal .form-control{
    color: #000;
    font-size: 16px;
    font-weight: 600;
    height: 50px;
    padding: 10px 10px 10px 40px;
    margin: 0 0 5px;
    border: none;
    border-bottom: 2px solid #e7e7e7;
    border-radius: 0px;
    box-shadow: none;
}
.form-horizontal .form-control:focus{
    box-shadow: none;
    border-bottom-color: #EC5F20;
}
.form-horizontal .form-control::placeholder{
    color: #000;
    font-size: 16px;
    font-weight: 600;
}
.form-horizontal .forgot{
    font-size: 13px;
    font-weight: 600;
    text-align: right;
    display: block;
}
.form-horizontal .forgot a{
    color: #777;
    transition: all 0.3s ease 0s;
}
.form-horizontal .forgot a:hover{
    color: #777;
    text-decoration:  underline;
}
.form-horizontal .signin{
    color: #fff;
    background-color: #1B394D;
    font-size: 17px;
    text-transform: capitalize;
    letter-spacing: 2px;
    width: 100%;
    padding: 12px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    transition: all 0.4s ease 0s;
}
.form-horizontal .signin:hover,
.form-horizontal .signin:focus{
    font-weight: 600;
    letter-spacing: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3) inset;
}
License Terms 
	</style>

		<!-- Account-->
		<div id="side-account-w">
	
		
	<div class="form-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="form-container">
                    <h3 class="title dark">My Account</h3>
                    <form class="form-horizontal reset-form">

                    <?php
                        if(!isset($profile) || empty($profile[0]->photo_link)){
                            echo'
                            <div data-bs-toggle="modal" data-bs-target="#profile"  class="form-icon position-relative" >
                            <i class="fa fa-user-circle"></i>
                            ';
                        }
                        else{
                      echo '
                      <div data-bs-toggle="modal" data-bs-target="#profile"  class="form-icon position-relative" style="background-repeat:no-repeat;background-size: cover; background-image:url('.$profile[0]->photo_link.');">
                      ';
                        }
                        ?>
                        
                        
                        </div>


                    <div class="resetaccount">
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="email" class="form-control" placeholder="Username" name="email" value="<?php echo $email;?>" >
                        </div>
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="number" class="form-control" placeholder="Phone" name="phone" value="<?php echo $phone;?>">
                        </div>
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="password" class="form-control" placeholder="Pin" name="pin" value="<?php echo $pin;?>">
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" placeholder="Password" name="password">
                        </div>
                        <input class="btn signin correct" type="button" name="correct" value="Update">
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
		
<script>

		
jQuery(".correct").click(function(){
jQuery(".correct").val("Please Wait");
var obj = {};
var toatl_input = jQuery(".reset-form input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".reset-form input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;

}
	
	
}

jQuery.ajax({
  url: "<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/reset.php';?>",
  data: obj,
  dataType:'json',
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  console.log(jqXHR);
       console.log(exception);
		jQuery(".correct").val("Update");
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
  text:jqXHR.responseText,
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
  success: function(data,status,request) {
	  console.log(data);
       console.log(status);
       console.log(request);
		jQuery(".correct").val("Update");
        if(data.status == "100" ){
	
		  swal({
  title: "Success",
  text: "Account Updated",
  icon: "success",
  button: "Proceed",
}).then((value) => {
location.reload();
});
	  }
else if(data.status == "200" ){
	msg = data.message;
	
		  swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Retry",
});
	  }
	  else{
		  
	 swal({
  title: "Error",
  text: data,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type:"POST"
});

});





		
</script>	
		
		</div>
		<?php
		
}

?>