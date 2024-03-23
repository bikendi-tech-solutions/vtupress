<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if($_GET["subpage"] == "mlm"){
    if(vp_getoption("resell") != "yes"){
        vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
      }
?>
<div class="row">
    <div class="col">
    <div class="card">
              <div class="card-body">
                  <h5 class="card-title">Networking</h5>
                  <div class="table-responsive">
<?php

if(current_user_can("vtupress_access_mlm")){

    include_once(ABSPATH .'wp-content/plugins/vpmlm/vpmlm.php');
    mlm_set();

}
?>

</div>
</div>
</div>
</div>
</div>
<?php
}
?>