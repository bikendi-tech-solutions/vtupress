<?php
//here
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

?>

        <div class="container-fluid vtusettings-container">
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




<div class="row">
<div class="col">

<!--GENERAL PAGE-->
<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/general.php');
?>
<!--END GENERAL PAGE-->


<!--PAYMENT PAGE-->
<?php
if(current_user_can("vtupress_access_payment")){
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/subpage/conversion.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/subpage/paymentgateway.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/subpage/coupon.php');
    
}
?>
<!--END PAYMENT PAGE-->


<!--PAYMENT PAGE-->
<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/mlm.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings/levels.php');
?>
<!--END PAYMENT PAGE-->
<!--END OF VTU-SETTINGS-->
</div>
</div>

        </div>
