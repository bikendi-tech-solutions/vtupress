<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(current_user_can("vtupress_access_users")){?>

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

if(isset($_GET["user_id"])){
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/messages/all.php');
}
else{
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/messages/messages.php');  
}

?>


</div>
<?php   
}?>