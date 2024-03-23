<?php
if(!defined('ABSPATH')){
  die("BAD!!!!");
}


if(!is_user_logged_in()){
    die("Please Login");
}
elseif(!current_user_can("vtupress_access_general")){
    die("Not Allowed");
}