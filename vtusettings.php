<?php
if(!defined('ABSPATH')){
 die();
}
ob_start();

function vtusettings(){
	
	if(vp_getoption('siteurl') != "https://vtupress.com" || vp_getoption('siteurl') != "vtupress.com" ){
	vp_addoption("separate_charges",0);
	if(vp_getoption("separate_charges") == 0){
vp_addoption("9airtime_to_cash_charge",0);
vp_addoption("gairtime_to_cash_charge",0);
vp_addoption("aairtime_to_cash_charge",0);
vp_addoption("aairtime_to_wallet_charge",0);
vp_addoption("9airtime_to_wallet_charge",0);
vp_addoption("gairtime_to_wallet_charge",0);
vp_updateoption("separate_charges",1);
	}

?>
<?php




/*THE DASHBOARD*/
echo '
<div class="container-fluid"> <br>

';







if(current_user_can("vtupress_access_users")){
//for vdatset
echo "<div class='vdatset' id='vdatset'>";
if (version_compare(phpversion(), '7.4.0') >= 0 && version_compare(phpversion(), '8.0.0') == -1) {
vpusers();
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.0. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php
}

echo "

<br><br>
</div>
";
}

if(current_user_can("vtupress_access_withdrawal")){
echo"

<div class='withs' id='withs'>
";
if (version_compare(phpversion(), '7.4.0') >= 0 && version_compare(phpversion(), '8.0.0') == -1) {
withdrawals();
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.0. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php
}
echo"
</div>
";
}


if(current_user_can("vtupress_access_mlm")){
//for vdatset
echo "<div class='mdatset' id='mdatset'>";
if (version_compare(phpversion(), '7.4.0') >= 0 && version_compare(phpversion(), '8.0.0') == -1) {
if(is_plugin_active("vpmlm/vpmlm.php")){
include_once(ABSPATH .'wp-content/plugins/vpmlm/vpmlm.php');
mlm_set();
}
else{

  echo"
  <h5>You Need To Be Premium User and VP MLM Installed</h5>
  ";
}
}
else{
?>
<div class="alert alert-primary mb-2" role="alert">
<b>PHP VERSION ERROR:</b><br>
PHP Version must be at least 7.4 but not up to 8.0. Your current version is <?php echo phpversion();?>.
<br>
<b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
</div>
<?php
}
echo '

<br><br>
</div>
';
}

echo'

<script>
jQuery("div.airtime_gateway").hide();
jQuery("div.coupon_div").hide();

jQuery("#withs").hide();
  var hash = jQuery(location).prop("hash").substr(1);
  if(hash  == "general"){
jQuery(".vtuaccount").show();
jQuery("#flt").hide();
jQuery("#prd").hide();
jQuery("#vdatset").hide();
jQuery("#mdatset").hide();
jQuery("#withs").hide();
jQuery("#push").hide();
  }

 else if(hash  == "gateway"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").show();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  }
  
 else if(hash  == "transactions"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").hide();
    jQuery("#prd").show();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  }

 else if(hash  == "users"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").hide();
    jQuery("#prd").hide();
    jQuery("#vdatset").show();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  }

 else if(hash  == "mlm"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").hide();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").show();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  }
  else if(hash  == "withdrawal"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").hide();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").show();
	jQuery("#push").hide();
  }
    else if(hash  == "push"){
    jQuery(".vtuaccount").hide();
    jQuery("#flt").hide();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").show();
  }

  else{
    jQuery(".vtuaccount").show();
    jQuery("#flt").hide();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  }


jQuery("#bbtn").on("click", function(){
  jQuery(".vtuaccount").show();
  jQuery("#flt").hide();
  jQuery("#prd").hide();
  jQuery("#vdatset").hide();
  jQuery("#mdatset").hide();
  jQuery("#withs").hide();
  jQuery("#push").hide();
});


  jQuery("#bbtn1").on("click", function(){
    jQuery(".vtuaccount").hide();
    jQuery(".flutterwave").show();
    jQuery("div.airtime_gateway").hide();
    jQuery("div.payment_gateway").show();
    jQuery("#prd").hide();
    jQuery("#vdatset").hide();
    jQuery("#mdatset").hide();
	jQuery("#withs").hide();
	jQuery("#push").hide();
  });

    jQuery("#bbtn2").on("click", function(){
      jQuery(".vtuaccount").hide();
      jQuery("#flt").hide();
      jQuery("#prd").show();
      jQuery("#vdatset").hide();
      jQuery("#mdatset").hide();
	  jQuery("#withs").hide();
	  jQuery("#push").hide();
    });
 
      jQuery("#bbtn3").on("click", function(){
        jQuery(".vtuaccount").hide();
        jQuery("#flt").hide();
        jQuery("#prd").hide();
        jQuery("#vdatset").show();
        jQuery("#mdatset").hide();
		jQuery("#withs").hide();
		jQuery("#push").hide();
      });

        jQuery("#bbtn4").on("click", function(){
          jQuery(".vtuaccount").hide();
          jQuery("#flt").hide();
          jQuery("#prd").hide();
          jQuery("#vdatset").hide();
          jQuery("#mdatset").show();
          jQuery("#withs").hide();
		  jQuery("#push").hide();
          });
		  
		 jQuery("#bbtn5").on("click", function(){
          jQuery(".vtuaccount").hide();
          jQuery("#flt").hide();
          jQuery("#prd").hide();
          jQuery("#vdatset").hide();
          jQuery("#mdatset").hide();
		  jQuery("#withs").show();
		  jQuery("#push").hide();
          });
		  
		  jQuery("#bbtn6").on("click", function(){
          jQuery(".vtuaccount").hide();
          jQuery("#flt").hide();
          jQuery("#prd").hide();
          jQuery("#vdatset").hide();
          jQuery("#mdatset").hide();
		  jQuery("#withs").hide();
		  jQuery("#push").show();
          });
		  
		  
   
 jQuery("button.airtime_gateway").on("click", function(){
    jQuery("div.airtime_gateway").show();
    jQuery("div.payment_gateway").hide();
	jQuery("div.coupon_div").hide();
  });
     
 jQuery("button.payment_gateway").on("click", function(){
    jQuery("div.airtime_gateway").hide();
    jQuery("div.payment_gateway").show();
	jQuery("div.coupon_div").hide();
  });
  
  jQuery("button.coupon_gateway").on("click", function(){
    jQuery("div.airtime_gateway").hide();
    jQuery("div.payment_gateway").hide();
    jQuery("div.coupon_div").show();
  });

</script>

</div><!--DASHBOARD CONTAINER END -->
';

if(vp_getoption("vprun") == "block"){
echo '<script>
document.getElementsByTagName("button").disabled = true;
</script>
';
}
}
}
return ob_get_clean();
?>