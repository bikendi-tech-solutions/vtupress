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
if($_GET["subpage"] == "all" ){
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/all.php');
}
elseif($_GET["subpage"] == "info" ){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/info.php');
}
elseif($_GET["subpage"] == "banned"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/banned.php');  
}
elseif($_GET["subpage"] == "kycbanned"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/ban.php');  
}
elseif($_GET["subpage"] == "bio"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/biometric.php');  
}
elseif($_GET["subpage"] == "approved"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/approved.php');  
}
elseif($_GET["subpage"] == "disapproved"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/disapprove.php');  
}
elseif($_GET["subpage"] == "pending"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/pending.php');  
}
elseif($_GET["subpage"] == "settings"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/kyc/settings.php');  
}
?>


</div>
<?php   
}?>