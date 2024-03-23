<?php
//here
if(!defined('ABSPATH')){
  $pagePath = explode('/wp-content/', dirname(__FILE__));
  include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
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
                  <h5 class="card-title">Upgarde Your Package
                    <?php
                    if(vp_getoption("vprun") != "block" ){
                    ?>
                  <span class="btn btn-sm btn-success rounded-circle w-20 h-20" stype="width:20px !important; height:20px !important;"></span>
                  <?php
                    }
                    else{
                  ?>
                  <span class="btn btn-sm btn-danger rounded-circle w-20 h-20"  stype="width:20px !important; height:20px !important;"></span>
                    <?php
                    }
                  ?>
                
                </h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
                 If you have a running services prior before now then your subscription might be interupted. Kindly check your vtupress account or contact your developer
                   </div>
    </div>


<div class="row">

<div class="col">
  <h4>  Please Upgrade To Premium Or Lifetime Package To Enjoy This Feature and More features to come.<br>
    There are more to premium and lifetime package than personal plan. The personal plan only mimcks the demo plan and is very less fearured compared to the premium package! </h4>
  <h5>
  Please Note: Aside the built in security, this feature is an advance version in collaboration with Megs Security Server so it is not included on any package but should be paid for separately. The price varies but was charged at 20k as at when this version was published (Sept 30, 2022)
                  </h5>
    
</div>
  </div>



</div>





                  </div>
                </div>
              </div>
</div>


</div>



</div>