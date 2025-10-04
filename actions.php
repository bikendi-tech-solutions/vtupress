<?php
if(!defined('ABSPATH')){
die();
}
ob_start();
add_action("admin_menu","addMenu");


function addMenu(){
  add_menu_page("Vtu Press","Vtupress","vtupress_access_vtupress","vtupanel","vtupanel", "dashicons-calculator");

}



function vtupanel(){
  add_action('init','vtupress_remove_notices');
  function vtupress_remove_notices(){
    remove_all_actions( 'admin_notices');
    remove_all_actions( 'user_admin_notices');
  };

  $vp_country = vp_country();
	$glo = $vp_country["glo"];
	$mobile = $vp_country["9mobile"];
	$bypass = $vp_country["bypass"];
	$currency = $vp_country["currency"];
	$symbol = $vp_country["symbol"];
  $prefix = $vp_country["line_prefix"];
  $country = $vp_country["country"];
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/admin.php');
}


return ob_get_clean();
?>