<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(current_user_can("vtupress_access_users")){
?>

<div class="container-fluid users-container">
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
if($_GET["subpage"] == "system" ){
#include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc/system.php');
}
elseif($_GET["subpage"] == "database" ){
    #include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc/database.php');
}
elseif($_GET["subpage"] == "resolver" ){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc/resolver.php');
}
elseif($_GET["subpage"] == "folder" ){
    if(vp_getoption("resell") != "yes"){
        vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
      }
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc/folder.php');
}
elseif($_GET["subpage"] == "security" ){
    if(vp_getoption("vp_security") != "yes"){
        vp_die("here");
      }
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc/security.php');
}
?>


</div>
<?php   
}?>