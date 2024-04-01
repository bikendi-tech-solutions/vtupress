<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(current_user_can("vtupress_access_history")){?>

<div class="container-fluid history-container">
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

<?php
if($_GET["subpage"] == "airtime" && $_GET["type"] == "successful"){
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/sairtime.php');
}
elseif($_GET["subpage"] == "airtime" && $_GET["type"] == "unsuccessful"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/fairtime.php');
}
elseif($_GET["subpage"] == "airtime" && $_GET["type"] == "failed"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/opairtime.php');
}
elseif($_GET["subpage"] == "data" && $_GET["type"] == "successful"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/sdata.php');
}
elseif($_GET["subpage"] == "data" && $_GET["type"] == "unsuccessful"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/fdata.php');
}
elseif($_GET["subpage"] == "data" && $_GET["type"] == "failed"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/opdata.php');
}
elseif($_GET["subpage"] == "bet" && $_GET["type"] == "successful"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/sbet.php');
}
elseif($_GET["subpage"] == "bet" && $_GET["type"] == "unsuccessful"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/fbet.php');
}
elseif($_GET["subpage"] == "bet" && $_GET["type"] == "failed"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/opbet.php');
}
elseif($_GET["subpage"] == "verification" && $_GET["type"] == "bvn"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/bvnverify.php');
}
elseif($_GET["subpage"] == "verification" && $_GET["type"] == "nin"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/ninverify.php');
}
elseif($_GET["subpage"] == "transfer" && $_GET["type"] == "cancled"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/transfer-failed.php');
}
elseif($_GET["subpage"] == "transfer" && $_GET["type"] == "pending"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/transfer-pending.php');
}
elseif($_GET["subpage"] == "transfer" && $_GET["type"] == "approved"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/transfer-approved.php');
}
elseif($_GET["subpage"] == "withdrawal" && $_GET["type"] == "approved"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/approved.php');
}
elseif($_GET["subpage"] == "withdrawal" && $_GET["type"] == "pending"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/processing.php');
}
elseif($_GET["subpage"] == "withdrawal" && $_GET["type"] == "cancled"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/failed.php');
}
elseif($_GET["subpage"] == "webhook"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/webhook.php');
}
elseif($_GET["subpage"] == "fwebhook"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/fwebhook.php');
}
elseif($_GET["subpage"] == "airtime_conversion" && $_GET["type"] == "pending"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/airtime-pending.php');
}
elseif($_GET["subpage"] == "airtime_conversion" && $_GET["type"] == "approved"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/airtime-approved.php');
}
elseif($_GET["subpage"] == "airtime_conversion" && $_GET["type"] == "failed"){
  if(vp_getoption("resell") != "yes"){
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/airtime-failed.php');
}
elseif($_GET["subpage"] == "transaction" && $_GET["type"] == "unrecorded"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/unrecorded.php');
}
elseif($_GET["subpage"] == "wallet"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/wallets.php');
}
do_action("vtupress_history_condition");
?>

</div>
</div>
</div>
<?php   
}?>