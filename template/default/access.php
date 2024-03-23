<!DOCTYPE html>
<html lang="en"><!-- Basic -->
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">   
   
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
 
     <!-- Site Metas -->
    <title>Login</title>  
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="<?php echo get_site_icon_url( 75, vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/login.png' ) ;?>" type="image/x-icon">
	<link rel="shortcut icon" href="<?php echo get_site_icon_url( 75, vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/login.png' ) ;?>" type="image/x-icon">
	<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/jquery.js';?>" ></script>
	<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/sweet.js';?>" ></script>

    <!-- Bootstrap CSS -->
    <link href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/bootstrap.min.css';?>" rel="stylesheet">
	<!-- Site CSS -->
    <link rel="stylesheet" href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/style.css';?>"> 
	<!-- Fontawesome CSS -->
    <link rel="stylesheet" href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/all.min.css';?>">  	
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/responsive.css';?>">
    <!-- Custom CSS -->
    <!--<link rel="stylesheet" href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/template/default/vtupress/csss/custom.css';?>">-->
	
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/js/jquery.min.js';?>"></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/js/bootstrap.min.js';?>"></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/js/particles.min.js';?>"></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/js/index.js';?>"></script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<script>
$('.forgot-password-form').hide();
</script>


<style>
	.form-container{
	background: linear-gradient(150deg,#273B7AA1 33%,#2D9DA733 34%,#2D9DA7 66%,#EAA22F 67%);
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
	display:none;
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
    background-color: #273B7A;
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
	</style>

</head>

<body>
<div id="particles-js" class="main-form-box">
	<div class="md-form">
		<div class="container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<div class="panel panel-login">
						<div class="logo-top">
							<a href="<?php echo home_url();?>"><img src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/login.png';?>" alt="" style="height:75px; width:75px;"/></a>
						</div>
						<div class="panel-heading">
							<div class="row">
								<div class="col-lg-6 col-sm-6 col-xl-6">
									<a href="#" class="active" id="login-form-link">Login</a>
								</div>
								<div class="col-lg-6 col-sm-6 col-xl-6">
									<a href="#" id="register-form-link" >Register</a>
								</div>
								<div class="or">OR</div>
							</div>
						</div>
						<div class="panel-body register-login-form">
							<div class="row">
								<div class="col-lg-12">
									<form id="login-form" method="post" role="form" class="loginit" style="display: block;">
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fa fa-eye dpass" aria-hidden="true"></i></label>
											<input type="password" name="password" id="password" tabindex="2" class="form-control showPass" placeholder="Password" required="">
											
										</div>
										<div class="che-box d-none">
											<label class="checkbox-in">
												<input name="rememberme" type="checkbox" tabindex="3" id="remember" checked> <span></span>
												Remember Me
											</label>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-sm-6 offset-sm-3">
													<input type="button" role="button" name="vplogin" id="login-submit" tabindex="4" class="form-control btn btn-login loginow" value="Log In">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-lg-12">
													<div class="text-center">
														<a href="#" tabindex="5" class="forgot-password">Forgot Password?</a>
													</div>
												</div>
											</div>
										</div>


                                        
                                        <div class="form-group">
											<div class="row">
												<div class="col-lg-12">
													<div class="text-center">
														<a href="#" tabindex="5" class="activate-account shadow w-100">Activate Account</a>
													</div>
												</div>
											</div>
										</div>

                                        
									</form>


                                    <form id="activation-form" method="post" role="form" class="activation-form" style="display: none;">
										<div class="form-group">
											<label class="icon-lp mr-3"><i class="fas fa-user-tie"></i></label>
											<input type="text" name="user" id="username" tabindex="1" class="form-control" placeholder="Username" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp mr-3"><i class="fa fa-eye" aria-hidden="true"></i></label>
											<input type="text" name="verify" id="activation" tabindex="2" class="form-control " placeholder="Activation Code" required="">
											
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col-sm-6 offset-sm-3">
													<input type="button" role="button" name="actit" id="activation-submit" tabindex="4" class="form-control btn btn-login shadow rounded w-10 activate-now" value="Activate">
												</div>
											</div>
										</div>

									</form>
									
									
									<form id="register-form" method="post" class="signup" role="form" style="display: none;">
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="text" name="username" id="username" tabindex="1" class="form-control username" placeholder="Username" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="text" name="fun" id="username" tabindex="1" class="form-control firstname" placeholder="First Name" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="text" name="lun" id="username" tabindex="1" class="form-control lastname" placeholder="Last Name" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-phone"></i></label>
											<input type="number" name="phone" id="username" tabindex="1" class="form-control phone" placeholder="Phone" value="" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-envelope"></i></label>
											<input type="email" name="email" id="email" tabindex="1" class="form-control email" placeholder="Email Address" value="" required="">
										</div>
										<?php
										if(is_plugin_active("vpmlm/vpmlm.php")){
                                            if(!isset($_GET["ref"]) && strtolower(vp_getoption("id_on_reg")) != "false" && strtolower(vp_getoption("id_on_reg") != "no")){
										?>
										<div class="form-group regid">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="number" name="ref" id="username" tabindex="1" class="form-control regid" placeholder="Referrer" value="1" required="">
										<script>
										jQuery(".registernow").on("click",function(){
										if(jQuery.isNumeric(jQuery(".regid").val())){
											
										}else{
										var runitnow = "no";	
										alert("The Referrer Can Only Be In Number i.e user ID not letters");
										}
										});
										</script>
										</div>
										<?php
										}
                                        elseif(!isset($_GET["ref"]) && strtolower(vp_getoption("id_on_reg")) == "false" || strtolower(vp_getoption("id_on_reg") == "no")){
                                            ?>
                                            <div class="form-group regid">
                                                <label class="icon-lp mr-3"><i class="fas fa-user-tie"></i></label>
                                                <input type="number" name="ref" id="username" tabindex="1" class="form-control regid" placeholder="Referrer" value="1" required="">
                                            <script>
                                            jQuery(".registernow").on("click",function(){
                                            if(jQuery.isNumeric(jQuery(".regid").val())){
                                                
                                            }else{
                                            var runitnow = "no";
                                            alert("The Referrer Can Only Be In Number i.e user ID not letters");
                                            }
                                            });
                                            </script>
                                            </div>
                                            <?php
                                            }
										elseif(isset($_GET["ref"])){
											
										$myref = $_GET["ref"];
										?>
										
										<div class="form-group d-none">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="number" name="ref" id="username" tabindex="1" class="form-control regid" placeholder="Referrer" value="<?php echo $myref;?>" required="">
										</div>
										<?php
	
										}
										}else{
											?>
											<div class="form-group d-none">
											<label class="icon-lp"><i class="fas fa-user-tie"></i></label>
											<input type="number" name="ref" id="username" tabindex="1" class="form-control regid" placeholder="Referrer" value="1" required="">
										</div>
										<?php	
										}

										?>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-eye dpass" aria-hidden="true"></i></label>
											<input type="password" name="pswd" id="password" tabindex="2" class="form-control pswd showPass" placeholder="Password" required="">
										</div>
										<div class="form-group">
											<label class="icon-lp"><i class="fas fa-key" aria-hidden="true"></i></label>
											<input type="number" name="pin" id="password" tabindex="2" class="form-control pin" placeholder="Transaction Pin" required="">
										</div>
										<!--<div class="form-group d-none">
											<label class="icon-lp"><i class="fas fa-key"></i></label>
											<input type="password" name="conpswd" id="confirm-password" tabindex="2" class="form-control conpswd" placeholder="Confirm Password" required="">
										</div>
										
										<div class="che-box d-none">
											<label class="checkbox-in"> 
												<input name="checkbox" type="checkbox"> <span></span>I am not a robot</a>
											</label>
										</div>-->
										
										<div class="form-group">
											<div class="row">
												<div class="col-sm-6 offset-sm-3">
													<input type="button" role="button" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register registernow" value="Register Now">
												</div>
											</div>
										</div>										
									</form>
								</div>
							</div>						
						</div>
						
													
<div class="forgot-password-form">
	<div class="form-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="form-container reset-form">
                    <form class="form-horizontal">
                        <div class="form-icon">
                            <i class="fa fa-user-circle"></i>
                        </div>
						<div class="first-level">
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" placeholder="Username" name="username">
                        </div>
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-user"></i></span>
                            <input type="email" class="form-control" placeholder="Email" name="email">
                        </div>
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-key"></i></span>
                            <input type="number" class="form-control" placeholder="Your Pin" name="pin">
                        </div>
						<div class="form-group">
                            <span class="input-icon"><i class="fa fa-eye dpass"></i></span>
                            <input type="password" class="form-control showPass" placeholder="New Password" name="password">
                        </div>
						</div>
						<input type="button" class="btn signin first-level-signin" value="Reset Password" name="firstreset">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
						
	
						
					</div>
					
					<p class="footer-company-name">All Rights Reserved. <a href="<?php echo home_url();?>"><?php echo get_bloginfo('name');?></a></p>
					
				</div>
			</div>
		</div>	
	</div>
	
</div>

	

<script>

jQuery(".activate-now").click(function(){
jQuery(".activate-now").val("Please Wait...");
var obj = {};
var toatl_input = jQuery(".activation-form input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".activation-form input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
}
	
	
}


jQuery.ajax({
  url: "<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/logit.php';?>",
  data: obj,
  dataType:'text',
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  console.log(jqXHR);
       console.log(exception);
		jQuery(".activate-now").val("Activate Now");
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
  success: function(data,status,request) {
	  console.log(data);
       console.log(status);
       console.log(request);
       jQuery(".activate-now").val("Activate Now");
        if(data == "100" ){
	
		  swal({
  title: "Activation Successful!!!",
  text: "Now You Can Login",
  icon: "success",
  button: "Proceed",
}).then((value) => {
    window.location.reload();
});
	  }

      else if(data == "101" ){
        swal({
  title: "No Operation",
  text: "Already A Verified Member!",
  icon: "info",
  button: "Okay",
});

      }
	  else{
	 swal({
  title: "Oops!",
  text: "Wrong Username or Code",
  icon: "info",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});




$('.forgot-password-form').hide();
		$(function() {
			$('#login-form-link').click(function(e) {
		$('.register-login-form').show("slow");
		$('.forgot-password-form').hide("slow");
        $('.activation-form').hide("slow");
				$("#login-form").fadeIn(100);
				$("#register-form").fadeOut(100);
				$('#register-form-link').removeClass('active');
				$(this).addClass('active');
				e.preventDefault();
			});
			$('#register-form-link').click(function(e) {
		$('.register-login-form').show("slow");
		$('.forgot-password-form').hide("slow");
        $('.activation-form').hide("slow");
				$("#register-form").fadeIn(100);
				$("#login-form").fadeOut(100);
				$('#login-form-link').removeClass('active');
				$(this).addClass('active');
				e.preventDefault();
			});


            $('.activate-account').click(function(e) {
		$('.register-login-form').show("slow");
		$('.forgot-password-form').hide("slow");
        $('.activation-form').show("slow");
        $("#login-form").fadeOut(100);
				$("#register-form").fadeOut(100);
				$('#register-form-link').removeClass('active');
                $('#login-form-link').removeClass('active');
			});

		});

        

        
		
		$('.form-group input').focus(function () {
			$(this).parent().addClass('addcolor');
		}).blur(function () {
			$(this).parent().removeClass('addcolor');
		});
		
		$('.forgot-password').click(function(){
		$('.register-login-form').hide("slow");
		$('.forgot-password-form').show("slow");
		
		});
		
		
		
		
jQuery(".first-level-signin").click(function(){
jQuery(".first-level-signin").val("Please Wait");
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
		jQuery(".first-level-signin").val("Reset Password");
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
  success: function(data,status,request) {
		jQuery(".first-level-signin").val("Reset Password");
        if(data.status == "100" ){
	
		  swal({
  title: "Password Changed Successfully!",
  text: data.message,
  icon: "success",
  button: "Proceed",
}).then((value) => {
	$('.first-level-signin').removeAttr("name");
	$('.second-level-signin').attr("name","secondreset");
	$('.second-level-signin').show();
	$('.second-level').show();
	
	$('.first-level-signin').hide();
	$('.first-level').hide();
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
  text: "Error Fetching Data",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});

	
 
jQuery('.dpass').on('click', function(){
      var passInput = jQuery(".showPass");
      if(passInput.attr('type')==='password'){
          passInput.attr('type','text');
      }else{
         passInput.attr('type','password');
      }
});
	
jQuery(".loginow").click(function(){
jQuery(".loginow").val("Please Wait...");
var obj = {};
var toatl_input = jQuery(".loginit input").length;
var run_obj;

for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".loginit input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;
//alert(obj_name);
//alert(obj_value);
}
	
	
}


jQuery.ajax({
  url: "<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/logit.php';?>",
  data: obj,
  dataType:'json',
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  console.log(jqXHR);
       console.log(exception);
		jQuery(".loginow").val("Login");
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
  success: function(data,status,request) {
	  console.log(data);
       console.log(status);
       console.log(request);
		jQuery(".loginow").val("Login");
        if(data.status == "100" ){
	
		  swal({
  title: "Welcome",
  text: data.name,
  icon: "success",
  button: "Proceed",
}).then((value) => {
let searchParams = new URLSearchParams(window.location.search);
if(searchParams.has('vend')){
	window.location.href = "/vpaccount?"+searchParams+"#";
}
else{
	window.location.href = "/vpaccount?"+data.name;
}
});
	  }
else if(data.status == "101" ){
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
  text: "Error Signing In",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

});



jQuery(".registernow").click(function(){
	jQuery(".registernow").val("Please Wait...");
var runitnow = "yes";
runitnow = "yes";
var obj = {};
var toatl_input = jQuery(".signup input").length;
var run_obj;
var usernamer = jQuery(".signup .username").val();
var username = usernamer.indexOf(" ");
var emailr = jQuery(".signup .email").val();
var email = emailr.indexOf("@");
var phone;

/*
if(username != "-1"){
alert("username Cannot Contain Spaces");
runitnow = "no";
jQuery(".registernow").val("Register Now");
}

if(usernamer.indexOf("@") != "-1" || usernamer.indexOf(".") != "-1" || usernamer.indexOf(",") != "-1" || usernamer.indexOf("$") != "-1" || usernamer.indexOf("#") != "-1" || usernamer.indexOf("*") != "-1" || usernamer.indexOf("-") != "-1" || usernamer.indexOf("_") != "-1" || usernamer.indexOf("=") != "-1" || usernamer.indexOf("&") != "-1" || usernamer.indexOf("^") != "-1" || usernamer.indexOf("%") != "-1"){
alert("username Cannot Any Special Character \n Use only letters and alphabets for usernames");
runitnow = "no";
jQuery(".registernow").val("Register Now");
}

if(email == "-1"){
alert("Enter A Valid Email Please");
runitnow = "no";
jQuery(".registernow").val("Register Now");
}

*/


var emptysp = "no";
for(run_obj = 0; run_obj <= toatl_input; run_obj++){
var current_input = jQuery(".signup input").eq(run_obj);


var obj_name = current_input.attr("name");
var obj_value = current_input.val();

if(typeof obj_name !== typeof undefined && obj_name !== false){
obj[obj_name] = obj_value;

if(current_input.val() === ""){

runitnow = "no";
emptysp = "yes";	
}else{
runitnow = "yes";
emptysp = "no";	
}

}
	
	
}

if(emptysp == "yes"){
	alert("All Fields Are Required");
	jQuery(".registernow").val("Register Now");
}
else{
	emptysp == "no";
}


if(runitnow == "yes" ){
jQuery.ajax({
  url: "<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/userlogin.php';?>",
  data: obj,
  dataType:'json',
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
		jQuery(".registernow").val("Register Now");
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
		jQuery(".registernow").val("Register Now");
        if(data.status == "100" ){
	if("<?php echo htmlspecialchars(vp_getoption('resc'));?>" == 1000 || "<?php echo htmlspecialchars(vp_getoption('resc'));?>" == 10000){
        var ttt = "Proceed To Login";
    }
    else{

        var ttt = "<?php echo htmlspecialchars(vp_getoption('resc'));?>";
    }
		  swal({

  <?php
  if(strtolower(vp_getoption("email_verification")) == 'false' || strtolower(vp_getoption("email_verification")) == "no"){
	  ?>
  title: "Registered",
  text: ttt,
  <?php
  }
  else{
	?>
  title: "Email Verification Required",
  text: "Please check your inbox or spam folder for the verification email",
<?php	
  }
  ?>
  icon: "success",
  button: "Proceed",
}).then((value) => {
	location.reload();
});
	  }
else if(data.status == "101" ){
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
  type: "POST"
});

}
else{
	jQuery(".registernow").val("Register Now");
}
});

</script>

</body>
</html>
