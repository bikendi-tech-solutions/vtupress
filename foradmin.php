<?php
if(!defined('ABSPATH')){
  die("BAD!!!!");
}


if(!is_user_logged_in()){
    die("Please Login");
}
elseif(!current_user_can("vtupress_access_vtupress")){
    die("Only Admins Allowed");
}

if(isset($_POST) ):
  $_POST = wp_unslash($_POST);
elseif(isset($_GET) ):
  $_GET = wp_unslash($_GET);
endif;

/*
function remove_wp_slashes_from_post($data) {
    return wp_unslash($data);
}

add_filter('pre_process_input', 'remove_wp_slashes_from_post');

*/