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

vp_addoption("vp_folder_scan","none");
vp_addoption("vp_content_folder_scan",0);
vp_addoption("vp_content_plugin_scan",0);
vp_addoption("vp_content_vtupress_scan",0);
vp_addoption("vp_content_vend_scan",0);

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
                  <h5 class="card-title">Security</h5> 
<div class="table-responsive">
<div class="p-4">

             <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
              Note that vtupress itself has it's built in security codeline running. This section is an advance security system that is a paid service. It is never added to any subscritpion Package/Plan on vtupress as its powered externally by MegaZuls Security Systems <i class="fas fa-trademark"></i>. 
            </div>
           </div>

           <!--=================================================-->




<div class="container">
<div class="row border border-secondary mb-4">

<div class="col border border-secondary bg-success text-white" style="text-align:center;">


<h3>Security:
<div class="spinner-border text-primary" role="status"></div>
</h3><br>
<h4>
<?php
if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off" &&  vp_getoption("raptor_allow_security") == "yes"){
echo "<span class='badge badge-info display-7'>Running</span> ";
}
else{
  vp_updateoption("secur_mod","off");
echo "<span class='badge badge-danger display-7'>Not Running</span>";	
}
?>
</h4>


</div>
<?php
//print_r($details);
if(vp_getoption("vp_security") == "yes"){
	$disabled = "data=''";
}else{
	$disabled = "disabled";
}
?>






</div>

<div class="row">
<div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">URL</span>
<span class="input-group-text"><?php echo vp_getoption("siteurl");?></span>
</div>
</div>

<div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">SSL</span>
<?php
$stream = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$read = fopen(vp_getoption("siteurl"), "rb", false, $stream);
$cont = stream_context_get_params($read);
$var = ($cont["options"]["ssl"]["peer_certificate"]);
$result = (!is_null($var)) ? true : false;
if(is_null($var)){
?>
<span class="input-group-text">NO</span>
<?php
}else{
	?>
<span class="input-group-text">YES</span>
<?php
}
?>
</div>
</div>

<div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">SERVER NAME</span>
<span class="input-group-text"><?php echo $_SERVER["SERVER_NAME"];?></span>
</div>
</div>


</div>



<div class="row border border-secondary mb-4">
<div class="col-12 col-md border border-primary">
<div class="info">
<label class="form-label">HTTP REDIRECTION</label>
<br>
Redirect All http request to https (recommended)
</div><br>
<div class="input-group">
<span class="input-group-text">Force Http</span>
<input type="checkbox" class="input-group-text btn-check hr" id="btn-check-outlined" autocomplete="off" name="http-redirection" >
<label class="input-group-text btn btn-outline-primary hrt" for="btn-check-outlined">...</label>

</div>
 </div>
 
 <div class="col-12 col-md border border-primary">
 <div class="info">
 <label class="form-label">SECURITY MODE</label>
 <br>
Calm: Does not automaticall ban user when any of our security algorithm matches.<br>
Calm: Send email notification when there's a threat or suspicious activity on your website<br>
Wild: Automatically bans the user and sends you a notification if any suspicious activity is found<br>
Wild: Watches system strictly and automatically handles users with it's tough algorithm in relation to raptor processor<br>
 </div><br>
 <?php
 if(vp_getoption("vp_security") == "yes" && vp_getoption("raptor_allow_security") == "yes"){
?>
 <div class="bottom-button" style="position:relative; bottom:0;">
 <input type="radio" class="btn-check sm off" name="security-mode" id="success-outlied" autocomplete="off" value="off">
<label class="btn btn-outline-primary" for="success-outlied">Off</label>
 
<input type="radio" class="btn-check sm calm" name="security-mode" id="success-outlined" autocomplete="off" value="calm">
<label class="btn btn-outline-success" for="success-outlined">Calm</label>

<input type="radio" class="btn-check sm wild" name="security-mode" id="danger-outlined" autocomplete="off" value="wild">
<label class="btn btn-outline-danger" for="danger-outlined">Wild</label>
 </div>
 
<?php
}
else{
?>
<a href="https://api.whatsapp.com/send/?phone=2349152620963&text=Unlock%20Security%20Feature%20For%20Me&app_absent=0" >
<input type="button" class="btn-check btn-primary" name="security-mode" id="danger-outlined" autocomplete="off" checked>
<label class="btn btn-outline-primary text-whit" for="danger-outlined">UNLOCK SECURITY FEATURE</label>
</a>
<?php	
}
?>
 </div>
 

 </div>



</div>

<div class="container">
<div class="row border border-secondary mb-4">
<div class="col p-3">
<div class="row">
<div class="col-12 col-md">
<h5>Ban IPs:<small class="small">Separate Ips by Comma [,]</small></h5><br>
<textarea class="form-control ips" <?php echo $disabled;?> ><?php echo vp_getoption("vp_ips_ban");?></textarea>
</div>
<div class="col-12 d-none col-md">
<h5>Ban users:<small class="small" >Separate Username by Comma [,]</small></h5><br>
<textarea class="form-control users" <?php echo $disabled;?>><?php echo vp_getoption("vp_users_ban");?></textarea>
</div>
<div class="col-12 col-md">
<h5>Restrict Signup With Username/Emails:<small class="small" >Separate Ips by Comma [,]</small></h5><br>
<textarea class="form-control ban-emails" <?php echo $disabled;?>><?php echo vp_getoption("vp_users_email");?></textarea>
</div>
</div>
<br>

<div class="row">
<div class="col-12 col-sm">
<h5>Ban From:</h5><br>
<div class="form-check">
  <input class="form-check-input user-dashboard" type="checkbox" value="" id="flexCheckDefault" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefault">
   Accessing User Dashboard
  </label>
</div>
<div class="form-check">
  <input class="form-check-input entire-website" type="checkbox" value="" id="flexCheckChecked" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckChecked">
   Accessing Entire Website
  </label>
</div>
<div class="form-check d-none">
  <input class="form-check-input other-country d-none" type="checkbox" value="" id="flexCheckCheckedr" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckCheckedr">
   Accessing Website From Other Country
  </label>
</div>

</div>
<div class="col-12 col-sm">
<h5>Transactions:</h5><br>
<div class="form-check">
  <input class="form-check-input limit-transaction-others" type="checkbox" value="" id="flexCheckDefaultf" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefaultf">
   Users Must Wait Every 1MIN Before Processing Next Order To Other Recipient
  </label>
</div>

<div class="form-check">
  <input class="form-check-input limit-transaction-same" type="checkbox" value="" id="flexCheckDefaultg" <?php echo $disabled;?>>
  <label class="form-check-label" for="flexCheckDefaultg">
   Users Must Wait Every 2MINs Before Processing Next Order To Same Recipient
  </label>
</div>

</div>
</div>

</div>

<div class="col-12 p-3 border border-secondary m-1">
<span> <h4>Raptor Shield</h4></span>
<br>
<span> Last Update: <?php echo vp_getoption("raptor_last_query");?></span><br>
<span> Blocked: <?php echo vp_getoption("raptor_last_blocked");?> </span><br>
<span> Passed: <?php echo vp_getoption("raptor_last_passed");?></span><br>
<span> Last Details Processed: <?php echo vp_getoption("raptor_last_processed");?></span><br>

<div class="input-group d-flex justify-content-end">
<span class="input-group-text">Validate Recipient</span>
<input type="checkbox" class="input-group-text btn-check vr" id="btn-check-outlined2" autocomplete="off" name="validate-recipient" >
<label class="input-group-text btn btn-outline-primary vrt" for="btn-check-outlined2">...</label>

</div>


</div>

<div  class="mt-2 mb-2">
 <?php
 if(vp_getoption("vp_security") == "yes"){
?>
<input type="button" value="SAVE" class="btn btn-success save-security">
<?php
 }
 ?>
</div>
</div>
</div>

<script>
jQuery(window).on("load",function(){
jQuery("#cover-spin").hide();
});
jQuery(".hr").on("change",function(){
	if(jQuery(this).is(":checked")){
		jQuery('.hr').attr('checked','checked');
		jQuery('.hrt').text('Enabled');
	}
	else{
		jQuery('.hr').removeAttr('checked');
		jQuery('.hrt').text('Disabled');
	}
});

jQuery(".vr").on("change",function(){
	if(jQuery(this).is(":checked")){
		jQuery('.vr').attr('checked','checked');
		jQuery('.vrt').text('Enabled');
	}
	else{
		jQuery('.vr').removeAttr('checked');
		jQuery('.vrt').text('Disabled');
	}
});
<?php
if(vp_getoption("http_redirect") == "true"){
  echo"
  jQuery('.hr').attr('checked','checked');
  //jQuery('.hr').trigger('click');
  jQuery('.hrt').text('Enabled');
  //jQuery('.hrt').trigger('click');
  ";
  }
  else{
  echo"
  jQuery('.hr').removeAttr('checked');
  jQuery('.hrt').text('Disabled');
  ";	
  }

  if(vp_getoption("validate-recipient") == "true"){
    echo"
    jQuery('.vr').attr('checked','checked');
    //jQuery('.vr').trigger('click');
    jQuery('.vrt').text('Enabled');
    //jQuery('.vrt').trigger('click');
    ";
    }
    else{
    echo"
    jQuery('.vr').removeAttr('checked');
    jQuery('.vrt').text('Disabled');
    ";	
    }

echo"
jQuery('.".vp_getoption("global_security")."').prop('checked');
jQuery('.".vp_getoption("global_security")."').trigger('click');
";


echo "jQuery('.".vp_getoption("secur_mod")."').prop('checked');";
echo "jQuery('.".vp_getoption("secur_mod")."').trigger('click');";


if(vp_getoption("access_website") == "true"){
	echo "jQuery('.entire-website').prop('checked');";
	echo "jQuery('.entire-website').trigger('click');";
}

if(vp_getoption("tothers") == "true"){
	echo "jQuery('.limit-transaction-others').prop('checked');";
	echo "jQuery('.limit-transaction-others').trigger('click');";
}

if(vp_getoption("tself") == "true"){
	echo "jQuery('.limit-transaction-same').prop('checked');";
	echo "jQuery('.limit-transaction-same').trigger('click');";
}


if(vp_getoption("access_user_dashboard") == "true"){
	echo "jQuery('.user-dashboard').prop('checked');";
	echo "jQuery('.user-dashboard').trigger('click');";
}

if(vp_getoption("access_country") == "true"){
	echo "jQuery('.other-country').prop('checked');";
	echo "jQuery('.other-country').trigger('click');";
}

?>
jQuery(".save-security").on("click",function(){
jQuery("#cover-spin").show();
var savesecurity = jQuery(".sm:checked").val();
var httpredirection = jQuery(".hr").is(":checked");
var validaterecipient = jQuery(".vr").is(":checked");
//var globalsecurity = jQuery(".gs:checked").val();
var userdashboard = jQuery(".user-dashboard").is(":checked");
var enterwebsite = jQuery(".entire-website").is(":checked");
var othercountry = jQuery(".other-country").is(":checked");
var ips = jQuery(".ips").val();
var users = jQuery(".users").val();
var email = jQuery(".ban-emails").val();
var tself = jQuery(".limit-transaction-same").is(":checked");
var tothers = jQuery(".limit-transaction-others").is(":checked");


var obj = {};
obj["secure"] = "secure";
obj["http"] = httpredirection;
obj["global"] = false;
obj["security"] = savesecurity;
obj["httips"] = ips;
obj["users"] = users;
obj["email"] = email;
obj["access-website"] = enterwebsite;
obj["user-dashboard"] = userdashboard;
obj["other-country"] = othercountry;
obj["tself"] = tself;
obj["tothers"] = tothers;
obj["validate-recipient"] = validaterecipient;

/*
alert("http "+obj["http"]);
alert("global "+obj["global"]);
alert("security "+obj["security"]);
alert("ips "+obj["httips"]);
alert("users "+obj["users"]);
alert("aw "+obj["access-website"]);
alert("ud "+obj["user-dashboard"]);
alert("oc "+obj["other-country"]);
*/
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/system.php'));?>',
  data: obj,
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
		  swal({
  title: "SAVED",
  text: "Successfully",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
		  swal({
  title: "Error",
  text: data,
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: 'POST'
});





});

</script>





           <!--==================================================-->
</div>
</div>
</div>
</div>


</div>



</div>