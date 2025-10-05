<?php
if(!defined('ABSPATH')){
      $pagePath = explode('/wp-content/', dirname(__FILE__));
      include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
  }
  if(WP_DEBUG == false){
  error_reporting(0);	
  }
  include_once(ABSPATH."wp-load.php");
  include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
  include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

  $phpVersion = phpversion();
  if(floatval($phpVersion) < floatval("7.4.0") || floatval($phpVersion) > floatval("8.3.60")){

    $title = "PHP VERSION ERROR ";
    $message = "You are using an incompatible php version of <b>$phpVersion</b>, this might break up your experience. Kindly send a request to your developer or change your php version yourself on cpanel if you use one to not less than <b>7.4</b> and not greater than <b>8.3</b> ";
    dump_error($title, $message);
  }

  if(get_option('permalink_structure') != '/%postname%/'){
    update_option('permalink_structure','/%postname%/');
  }

add_filter( 'admin_footer_text', '__return_empty_string', 11 ); 
add_filter( 'update_footer', '__return_empty_string', 11 );

global $wpdb;
$userd = $wpdb->prefix."users";
$datad = $wpdb->prefix."sdata";
$withd = $wpdb->prefix."vp_withdrawal";
$messd = $wpdb->prefix."vp_chat";
$noti = $wpdb->prefix."vp_notifications";
$atcd = $wpdb->prefix."vp_wallet";

$links = "VTUPRESS ";
foreach($_GET as $key => $value){
  if($value != "dashboard"){
$links .= strtoupper("> $value");
  }
}

?>
<script>
jQuery("title").text("<?php echo $links;?>");
</script>

<?php


$messages = $wpdb->get_results("SELECT * FROM (SELECT * FROM $messd WHERE type='received' AND status = 'unread' ORDER BY id DESC LIMIT 3) AS x GROUP BY user_id");

$notifications = $wpdb->get_results("SELECT * FROM $noti WHERE status = 'unread' ORDER BY id DESC LIMIT 3");

?>
<div class="vtupress_admin_panel position-absolute">
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/favicon.png"
    />
    
    <link href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/css/float-chart.css" rel="stylesheet" />
    <link href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/css/style.min.css" rel="stylesheet" />
    <link href="<?php echo esc_url(plugins_url("vtupress/css")); ?>/datatables.css" rel="stylesheet" />
    <link href="<?php echo esc_url(plugins_url("vtupress/css")); ?>/toast.css" rel="stylesheet" />
    <style>
.card {
    margin-top: 0;
    padding: 0;
    max-width: 100%;
}

.modal-backdrop{
z-index:-1 !important;
}

.modal{
z-index: 9999999;
}
        
</style>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/sweet.js?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/jquery.js?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/datatables.js?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/toast.js?v=1') );?>" ></script>
<script src="<?php echo esc_url( plugins_url( 'vtupress/js/custom.js?v=1') );?>" ></script>
<script>
var width = jQuery(window).width()+"px";
//alert(width);
jQuery(".container-fluid").css("max-width",width);
jQuery(".notice").hide();

  jQuery(".notice").hide();
  </script>

    <!--This page JavaScript -->
    <!-- <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/js/pages/dashboards/dashboard1.js"></script> -->
    <!-- Charts js Files -->


    <div class="preloader" id="cover-spin" style="z-index:99999999 !important;">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin5">
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="#">
              <!-- Logo icon -->
              <b class="logo-icon">
                <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                <!-- Dark Logo icon -->
                <img
                  src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/vlogo.png"
                  alt="homepage"
                  class="light-logo"
                  width="50px"
                />
              </b>
              <!--End Logo icon -->
              <!-- Logo text -->
              <span class="logo-text ms-2">
                <!-- dark Logo text -->
                <img
                  src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/vtupress.png"
                  alt="homepage"
                  class="light-logo"
                  width="100px"
                />
              </span>
              <!-- Logo icon -->
              <!-- <b class="logo-icon"> -->
              <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
              <!-- Dark Logo icon -->
              <!-- <img src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/logo-text.png" alt="homepage" class="light-logo" /> -->

              <!-- </b> -->
              <!--End Logo icon -->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a
              class="nav-toggler waves-effect waves-light d-block d-md-none"
              href="javascript:void(0)"
              ><i class="ti-menu ti-close"></i
            ></a>
          </div>
          <!-- ============================================================== -->
          <!-- End Logo -->
          <!-- ============================================================== -->
          <div
            class="navbar-collapse collapse"
            id="navbarSupportedContent"
            data-navbarbg="skin5"
          >
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-start me-auto">
              <li class="nav-item d-none d-lg-block">
                <a
                  class="nav-link sidebartoggler waves-effect waves-light"
                  href="javascript:void(0)"
                  data-sidebartype="mini-sidebar"
                  ><i class="mdi mdi-menu font-24"></i
                ></a>
              </li>
              <!-- ============================================================== -->
              <!-- create new -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown d-none">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <span class="d-none d-md-block"
                    >Create New <i class="fa fa-angle-down"></i
                  ></span>
                  <span class="d-block d-md-none"
                    ><i class="fa fa-plus"></i
                  ></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- Search -->
              <!-- ============================================================== -->
              <li class="nav-item search-box">
                <a
                  class="nav-link waves-effect waves-dark"
                  href="javascript:void(0)"
                  ><i class="mdi mdi-magnify fs-4"></i
                ></a>
                <form class="app-search position-absolute">
                  <input
                    type="text"
                    class="form-control"
                    placeholder="Search &amp; enter"
                  />
                  <a class="srh-btn"><i class="mdi mdi-window-close"></i></a>
                </form>
              </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-end">
              <!-- ============================================================== -->
              <!-- Comment -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown d-none">
                <a
                  class="nav-link  dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="mdi mdi-bell font-24"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>
                </ul>
              </li>

            <!-- ===================== NOTIFICATIONS ================== -->
                        <!-- ===================== NOTIFICATIONS ================== -->
                                    <!-- ===================== NOTIFICATIONS ================== -->
                                                <!-- ===================== NOTIFICATIONS ================== -->

              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark position-relative"
                  href="#"
                  id="2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                <?php
                if(!empty($notifications)){
                  ?>
                <span title="See Notifications" class="position-absolute position-absolute top-0 start-0 badge rounded-pill bg-danger" style="z-index:2;"><i class="mdi mdi-bell-ring-outline"></i></span>
                 <?php
                }
                ?>
              <i class="mdi mdi-bell font-24"></i>
                </a>
                <ul
                  class="
                    dropdown-menu dropdown-menu-end
                    mailbox
                    animated
                    bounceInDown
                  "
                  aria-labelledby="2"
                >
                  <ul class="list-style-none">
                    <li>
                      <div class="">
                        <!-- Message -->
                      <?php
                      if(vp_getoption("resell") != "yes"){
                        ?>
                          <div class="text-center">
                              Upgrade Your Package
                          </div>
                          <?php
                      }
                      elseif(empty($notifications)){
                        ?>
                          <div class="text-center">
                              No New Notifications
                          </div>
                          <?php
                      }
                      else{
                      foreach($notifications as $notification){
                        ?>
                        <a href="?page=vtupanel&adminpage=notifications&sb=id&did=<?php echo $notification->user_id;?>" class="link border-top ps-2">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-success btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              >
                              <?php
                              if($notification->type == "transfer"){
                                  ?>
  	                                  <i class="mdi mdi-send text-white fs-4"></i>
                                  <?php
                              }
                              elseif($notification->type == "withdrawal"){
                                ?>
                                  <i class="mdi mdi-bank text-white fs-4"></i>
                               <?php

                              }
                              elseif($notification->type == "kyc"){
                                ?>
                                <i class="mdi mdi-account-check text-white fs-4"></i>
                             <?php
                              }
                              elseif($notification->type == "security"){
                                ?>
                                <i class="mdi mdi-verified text-white fs-4"></i>
                             <?php
                              }
                              ?>
                              
                            
                            </span>
                            <div class="ms-2">
                              <h5 class="mb-0"><?php echo $notification->title;?></h5>
                              <span class="mail-desc"
                                ><?php echo substr($notification->message,0,40);?></span
                              >
                            </div>
                          </div>
                        </a>
                      <?php
                      }
                      ?>
                      <a
                      href="?page=vtupanel&adminpage=notifications"
                      class="text-decoration-none"
                      style="background-color:#6c757d1a !important;"
                      >
                          <div class="text-center">
                              See All Notifications
                          </div>
                    </a>
                      <?php
                    }
                      ?>

                      </div>
                    </li>
                  </ul>
                </ul>
              </li>
              <!-- =========================END OF NOTIFICATIONS =============== -->
              <!-- ============================================================== -->
              <!-- End Comment -->
              <!-- ============================================================== -->
              <!-- ============================================================== -->
              <!-- Messages -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle waves-effect waves-dark position-relative"
                  href="#"
                  id="2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                <?php
                if(!empty($messages)){
                  ?>
                <span title="Messages" data-bs-toggle="modal" data-bs-target="#balance" class="position-absolute position-absolute top-0 start-0 badge rounded-pill bg-danger" style="z-index:2;"><i class="mdi mdi-bell-ring-outline"></i></span>
                 <?php
                }
                ?>
              <i class="font-24 mdi mdi-comment-processing"></i>
                </a>
                <ul
                  class="
                    dropdown-menu dropdown-menu-end
                    mailbox
                    animated
                    bounceInDown
                  "
                  aria-labelledby="2"
                >
                  <ul class="list-style-none">
                    <li>
                      <div class="">
                        <!-- Message -->
                      <?php
                      if(vp_getoption("resell") != "yes"){
                        ?>
                          <div class="text-center">
                              Upgrade Your Package
                          </div>
                          <?php
                      }
                      elseif(empty($messages)){
                        ?>
                          <div class="text-center">
                              No New Messages
                          </div>
                          <?php
                      }
                      else{
                      foreach($messages as $message){
                        ?>
                        <a href="?page=vtupanel&adminpage=messages&user_id=<?php echo $message->user_id;?>" class="link border-top ps-2">
                          <div class="d-flex no-block align-items-center p-10">
                            <span
                              class="
                                btn btn-success btn-circle
                                d-flex
                                align-items-center
                                justify-content-center
                              "
                              ><i class="mdi mdi-comment-account-outline text-white fs-4"></i
                            ></span>
                            <div class="ms-2">
                              <h5 class="mb-0"><?php echo $message->name;?></h5>
                              <span class="mail-desc"
                                ><?php echo substr($message->message,0,40);?></span
                              >
                            </div>
                          </div>
                        </a>
                      <?php
                      }
                      ?>
                      <a
                      href="?page=vtupanel&adminpage=messages"
                      class="text-decoration-none"
                      style="background-color:#6c757d1a !important;"
                      >
                          <div class="text-center">
                              See All Messages
                          </div>
                    </a>
                      <?php
                    }
                      ?>

                      </div>
                    </li>
                  </ul>
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- End Messages -->
              <!-- ============================================================== -->

              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
              <li class="nav-item dropdown">
                <a
                  class="
                    nav-link
                    dropdown-toggle
                    text-muted
                    waves-effect waves-dark
                    pro-pic
                  "
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg"
                    alt="user"
                    class="rounded-circle"
                    width="31"
                  />
                </a>
                <ul
                  class="dropdown-menu dropdown-menu-end user-dd animated"
                  aria-labelledby="navbarDropdown"
                >
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-account me-1 ms-1"></i> My Profile</a
                  >
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-wallet me-1 ms-1"></i> My Balance</a
                  >
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-email me-1 ms-1"></i> Inbox</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="mdi mdi-settings me-1 ms-1"></i> Account
                    Setting</a
                  >
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="javascript:void(0)"
                    ><i class="fa fa-power-off me-1 ms-1"></i> Logout</a
                  >
                  <div class="dropdown-divider"></div>
                  <div class="ps-4 p-10">
                    <a
                      href="javascript:void(0)"
                      class="btn btn-sm btn-success btn-rounded text-white"
                      >View Profile</a
                    >
                  </div>
                </ul>
              </li>
              <!-- ============================================================== -->
              <!-- User profile and search -->
              <!-- ============================================================== -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
            <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="?page=vtupanel"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu">Dashboard</span></a
                >
              </li>

              <li class="sidebar-item position-relative">
                      <a href="?page=vtupanel&adminpage=messages" class="sidebar-link "
                      ><i class="mdi mdi-message-text"></i
                      ><span class="hide-menu">Messages</span></a
                    >
                    <?php
                if(!empty($messages)){
                  ?>
                <span title="Messages" data-bs-toggle="modal" data-bs-target="#balance" class="position-absolute position-absolute top-0 start-0 badge rounded-pill bg-danger" style="z-index:2;"><i class="mdi mdi-bell-ring-outline"></i></span>
                 <?php
                }
                ?>
             </li>
             <li class="sidebar-item position-relative">
             <a href="?page=vtupanel&adminpage=notifications" class="sidebar-link "
                      ><i class="mdi mdi-bell "></i>
                      <span class="hide-menu">Notifications</span></a
                    >
                  <?php  if(vp_getoption("vtupress_custom_weblinksms") == "yes"): ?>
                    <a href="?page=vtupanel&adminpage=weblinksms" class="sidebar-link "
                      ><i class="mdi mdi-bell "></i>
                      <span class="hide-menu">W. Sms Blaster</span></a
                    >
                    <?php
                    endif;
                if(!empty($notifications)){
                  ?>
                <span title="Notifications" data-bs-toggle="modal" data-bs-target="#balance" class="position-absolute position-absolute top-0 start-0 badge rounded-pill bg-danger" style="z-index:2;"><i class="mdi mdi-bell-ring-outline"></i></span>
                 <?php
                }
                ?>
             </li>
<?php
if(current_user_can("vtupress_access_history")){
  ?>
<li class="sidebar-item bg bg-primary">
                <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu">History</span></a
                >
                <!--////////////////////////////AIRTIME ----------------->
    <ul aria-expanded="false" class="collapse first-level ">
                  <a href="?page=vtupanel&adminpage=history&subpage=wallet" class="sidebar-link">
                      <i class="fas fa-clipboard-list"></i>
                      <span class="hide-menu">Funds History</span>
                  </a>
            <?php if(vp_getoption("vtupress_custom_transfer") == "yes"):?>
                  <a href="?page=vtupanel&adminpage=history&subpage=bank_transfer" class="sidebar-link"
                      ><i class="fas fa-clipboard-list"></i>
                      <span class="hide-menu">Bank Transfer</span></a
                    >
            <?php endif;?>
       <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-cellphone-iphone"></i
                  ><span class="hide-menu">Airtime</span></a
                >
          <ul aria-expanded="false" class="collapse first-level">
          <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime&type=unsuccessful" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
          <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime&type=successful" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Successful</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime&type=failed" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Failed</span></a
                    >
                  </li>
          </ul>
        </li>
<!--------------------------------------------------------------------------------------------------------------->
<!--//////////////////////////////////////////////////DATA /////////////////////////////////////////-->
<li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="fas fa-wifi"></i
                  ><span class="hide-menu">Data</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=data&type=unsuccessful" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                   <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=data&type=successful" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Successful</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=data&type=failed" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Failed</span></a
                    >
                  </li>
            </ul>
        </li>

<!------------------------------------------------------------------------------------------------------------------------->

<!--------------------------------------------------------------------------------------------------------------->
<!--////////////////////////////////////////////////// BETTING /////////////////////////////////////////-->
<li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="fas fa-wifi"></i
                  ><span class="hide-menu">Bet Funding</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=bet&type=unsuccessful" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                   <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=bet&type=successful" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Successful</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=bet&type=failed" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Failed</span></a
                    >
                  </li>
            </ul>
</li>

<!------------------------------------------------------------------------------------------------------------------------->


<!------------------------------------------------------------------------------------------------------------------------->

<!--------------------------------------------------------------------------------------------------------------->
<!--////////////////////////////////////////////////// BETTING /////////////////////////////////////////-->
<li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-account-search"></i
                  ><span class="hide-menu">Verifications</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
<?php if(!$bypass):?>

                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=verification&type=bvn" class="sidebar-link"
                      ><i class="far fa-user"></i
                      ><span class="hide-menu">BVN</span></a
                    >
                  </li>
<?php endif;?>
                   <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=verification&type=nin" class="sidebar-link"
                      ><i class="far fa-user"></i
                      ><span class="hide-menu">NIN</span></a
                    >
                  </li>
            </ul>
</li>

<!------------------------------------------------------------------------------------------------------------------------->


                  <?php

do_action("vtupress_history_submenu");
                
?>

<li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-send"></i
                  ><span class="hide-menu">Transfers</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=transfer&type=pending" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                   <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=transfer&type=approved" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Approved</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=transfer&type=cancled" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Cancled</span></a
                    >
                  </li>
            </ul>
      </li>

<?php
if(current_user_can("vtupress_access_withdrawal")){
?>
      <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-briefcase"></i
                  ><span class="hide-menu">Withdrawal</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=withdrawal&type=pending" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                   <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=withdrawal&type=approved" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Approved</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=withdrawal&type=cancled" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Cancled</span></a
                    >
                  </li>
            </ul>
      </li>
      <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu">Airtime Conversion</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime_conversion&type=pending" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime_conversion&type=approved" class="sidebar-link"
                      ><i class="far fa-check-circle"></i
                      ><span class="hide-menu">Approved</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=airtime_conversion&type=failed" class="sidebar-link"
                      ><i class="fas fa-ban"></i
                      ><span class="hide-menu">Failed</span></a
                    >
                  </li>
            </ul>
      </li>

<?php
}
?>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=transaction&type=unrecorded" class="sidebar-link"
                      ><i class="mdi mdi-stop-circle-outline"></i
                      ><span class="hide-menu">Un-Recorded</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=webhook" class="sidebar-link"
                      ><i class="mdi mdi-webhook"></i
                      ><span class="hide-menu">Transactions Webhook</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                      <a href="?page=vtupanel&adminpage=history&subpage=fwebhook" class="sidebar-link"
                      ><i class="mdi mdi-webhook"></i
                      ><span class="hide-menu">Wallet Webhook</span></a
                    >
                  </li>
      </ul>
</li>
<?php
}
if(current_user_can("vtupress_access_settings")){
?>
            
          <li class="sidebar-item bg bg-primary">
                <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-settings"></i
                  ><span class="hide-menu">Settings</span></a
                >
                <ul aria-expanded="false" class="collapse first-level">
                <?php
if(current_user_can("vtupress_access_general")){
?>
                
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=general" class="sidebar-link"
                      ><i class="mdi mdi-format-float-none"></i
                      ><span class="hide-menu"> General </span></a
                    >
                  </li>
<?php
}
if(current_user_can("vtupress_access_payment")){
?>
        <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-receipt"></i
                  ><span class="hide-menu">Payments</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=paymentgateway" class="sidebar-link"
                      ><i class="fab fa-paypal"></i
                      ><span class="hide-menu"> Payment-Gateway </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=conversion" class="sidebar-link"
                      ><i class="fas fa-exchange-alt"></i
                      ><span class="hide-menu">Airtime To Cash </span></a
                    >
                  </li>
                
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=coupon" class="sidebar-link"
                      ><i class="fas fa-barcode"></i
                      ><span class="hide-menu">coupon </span></a
                    >
                  </li>


             </ul>
          </li>
                  

<?php
}
}
if(current_user_can("vtupress_access_mlm")){
?>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=mlm" class="sidebar-link"
                      ><i class="fas fa-object-group"></i
                      ><span class="hide-menu"> Mlm Settings</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=settings&subpage=levels" class="sidebar-link"
                      ><i class="mdi mdi-elevation-rise"></i
                      ><span class="hide-menu"> Users Packages</span></a
                    >
                  </li>
<?php
}
?>
          </ul>
    </li>



<!--USERS  MENU-->
<?php
if(current_user_can("vtupress_access_users")){
  ?>
<li class="sidebar-item bg bg-primary">
                <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="fas fa-users"></i
                  ><span class="hide-menu">Users</span></a
                >
      <ul aria-expanded="false" class="collapse first-level">

                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=all&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-all-inclusive"></i
                      ><span class="hide-menu"> All </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=banned&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-block-helper"></i
                      ><span class="hide-menu"> Banned </span></a
                    >
                  </li>

<?php if(!$bypass):?>
          <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-verified"></i
                  ><span class="hide-menu">kyc</span></a
                >
            <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=settings" class="sidebar-link"
                      ><i class="mdi mdi-account-settings-variant"></i
                      ><span class="hide-menu">Settings</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=pending&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-network-question"></i
                      ><span class="hide-menu">Pending</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=approved&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-account-check"></i
                      ><span class="hide-menu"> Approved </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=disapproved&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-account-remove"></i
                      ><span class="hide-menu"> Disapproved </span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=users&subpage=kycbanned&userpage=1&limit-records=10" class="sidebar-link"
                      ><i class="mdi mdi-account-star"></i
                      ><span class="hide-menu"> Banned </span></a
                    >
                  </li>
            </ul>
          </li>
<?php endif;?>

</ul>
 </li>

 <?php
}

if(current_user_can("vtupress_access_security")){
  ?>
        <li class="sidebar-item bg bg-primary">   
                    <a
                    class="sidebar-link has-arrow waves-effect waves-dark"
                    href="javascript:void(0)"
                    aria-expanded="false"
                    ><i class="mdi mdi-hand-pointing-right"></i
                    ><span class="hide-menu">Misc</span></a
                  >
                  <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                        <a href="/wp-admin/site-health.php?tab=debug" class="sidebar-link"
                        ><i class="mdi mdi-information"></i
                        ><span class="hide-menu">System Info</span></a
                      >
                  </li>
                  <li class="sidebar-item">
                        <a href="?page=vtupanel&adminpage=misc&subpage=resolver" class="sidebar-link"
                        ><i class="mdi mdi-database-plus"></i
                        ><span class="hide-menu">Database Resolver</span></a
                      >
                    </li>
                    <li class="sidebar-item">
                        <a href="?page=vtupanel&adminpage=misc&subpage=folder" class="sidebar-link"
                        ><i class="mdi mdi-folder-multiple"></i
                        ><span class="hide-menu">Folder/File Scan</span></a
                      >
                    </li>
                    <li class="sidebar-item">
                        <a href="?page=vtupanel&adminpage=misc&subpage=security" class="sidebar-link"
                        ><i class="mdi mdi-security"></i
                        ><span class="hide-menu">Security</span></a
                      >
                    </li>
              </ul>
        </li>
  <?php
  }
  if(vp_getoption("vtupress_custom_isavings") == "yes"){
    ?>
          <li class="sidebar-item bg bg-primary">   
                      <a
                      class="sidebar-link has-arrow waves-effect waves-dark"
                      href="javascript:void(0)"
                      aria-expanded="false"
                      ><i class="mdi mdi-bank"></i
                      ><span class="hide-menu">iSavings</span></a
                    >
                    <ul aria-expanded="false" class="collapse first-level">
                    <li class="sidebar-item">
                          <a href="?page=vtupanel&adminpage=isavings&subpage=daily-settings" class="sidebar-link"
                          ><i class="mdi mdi-clock"></i
                          ><span class="hide-menu">Daily Settings</span></a
                        >
                    </li>
                    <li class="sidebar-item">
                          <a href="?page=vtupanel&adminpage=isavings&subpage=fixed-settings" class="sidebar-link"
                          ><i class="mdi mdi-calendar-clock"></i
                          ><span class="hide-menu">Fixed Settings</span></a
                        >
                    </li>
                    <li class="sidebar-item">
                          <a href="?page=vtupanel&adminpage=isavings&subpage=history" class="sidebar-link"
                          ><i class="mdi mdi-history"></i
                          ><span class="hide-menu">History</span></a
                        >
                      </li>
                      <li class="sidebar-item ">
                          <a href="?page=vtupanel&adminpage=isavings&subpage=withdrawal" class="sidebar-link"
                          ><i class="mdi mdi-coin"></i
                          ><span class="hide-menu">Withdrawal</span></a
                        >
                      </li>
                </ul>
          </li>
    <?php
    }
if(current_user_can("vtupress_access_gateway")){
  ?>

<li class="sidebar-item bg bg-primary">
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-web"></i
                  ><span class="hide-menu">Gateway</span></a
                >
                <ul aria-expanded="false" class="collapse first-level">

          
      <!---------GATEWAY SETTING---------->          
      <li class="sidebar-item bg bg-primary">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-lan-connect"></i
                  ><span class="hide-menu">Gateway Settings</span></a
                >
<ul aria-expanded="false" class="collapse first-level">
  <!------------BEGINNING ------------->

  <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="fas fa-phone-volume"></i
                  ><span class="hide-menu">Airtime</span></a>
            <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=vtu" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Vtu</span></a
                    >
                  </li>
<?php if(!$bypass):?>

                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=shared" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Share & Sell</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=awuf" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Awuf</span></a
                    >
                  </li>
<?php endif;?>
            </ul>
        </li>

        <li class="sidebar-item bg bg-success">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class="mdi mdi-database"></i
                  ><span class="hide-menu">Data</span></a>
            <ul aria-expanded="false" class="collapse first-level">
<?php if(!$bypass):?>

                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=sme" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Sme</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=corporate" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Corporate</span></a
                    >
                  </li>

<?php endif;?>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=direct" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Direct</span></a
                    >
                  </li>
                  <?php if(vp_getoption("vtupress_custom_smile") == "yes" && !$bypass){
						?>
                    <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=smile" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Smile</span></a
                    >
                  </li>
                  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=alpha" class="sidebar-link"
                      ><i class="fas fa-phone-volume"></i
                      ><span class="hide-menu">Alpha</span></a
                    >
                  </li>
					<?php
					}
					?>

            </ul>
  </li>
<?php
if(is_plugin_active("bcmv/bcmv.php")){
?>

  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=cable" class="sidebar-link"
                      ><i class="mdi mdi-television-guide"></i
                      ><span class="hide-menu">Cable</span></a
                    >
  </li>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=bill" class="sidebar-link"
                      ><i class="mdi mdi-lightbulb"></i
                      ><span class="hide-menu">Bill</span></a
                    >
  </li>
<?php
}
if(vp_getoption("vtupress_custom_bet") == "yes"){
?>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=gateway&subpage=bet" class="sidebar-link"
                      ><i class="mdi mdi-lightbulb"></i
                      ><span class="hide-menu">Bet Funding</span></a
                    >
  </li>

<?php 
}
echo do_action("vtupress_gateway_submenu");?>
</ul>

</li>

<?php
}
?>
      <!---------END GATEWAY SETTING---------->
      <!--\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\/////////////////////////////-->
      
    
      <!---------GATEWAY IMPORT----------> 
<?php
      if(current_user_can("vtupress_access_importer")){
if(vp_getoption('vp_access_importer') == "yes"){
?>       
      <li class="sidebar-item bg bg-primary">   
                  <a
                  class="sidebar-link has-arrow waves-effect waves-dark"
                  href="javascript:void(0)"
                  aria-expanded="false"
                  ><i class=" fas fa-download"></i
                  ><span class="hide-menu">Import Api</span></a
                >
<ul aria-expanded="false" class="collapse first-level">
  <!------------BEGINNING ------------->
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=airtime" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Airtime</span></a
                    >
  </li>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=data" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Data</span></a
                    >
  </li>

  <?php
if(is_plugin_active("bcmv/bcmv.php")){
?>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=cable" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Cable</span></a
                    >
  </li>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=bill" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Bill</span></a
                    >
  </li>
  <?php
}
if(is_plugin_active("vpsms/vpsms.php")){
?>
  <li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=sms" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Sms</span></a
                    >
  </li>
  <?php
}
if(vp_getoption("vtupress_custom_bet") == "yes"){
?>

<li class="sidebar-item">
                    <a href="?page=vtupanel&adminpage=import&subpage=bet" class="sidebar-link"
                      ><i class="mdi mdi-call-received"></i
                      ><span class="hide-menu">Bet Funding</span></a
                    >
</li>
<?php
}
 echo do_action("vtupress_import_submenu");?>
</ul>

</li>
<?php
}
      }
?> 
      <!---------END GATEWAY IMPORT---------->  


</ul>
</li>

<?php
      if(current_user_can("vtupress_access_addons")){
?>
<li class="sidebar-item p-2">
                <a
                  href="?page=vtupanel&adminpage=addons"
                  class="
                    btn btn-info
                    d-flex
                    align-items-center
                    text-white
                  "
                  ><i class="mdi mdi-webpack font-20 me-2"></i> <span class="hide-menu"> Addons </span></a
                >
</li>
<li class="sidebar-item p-2">
                <a
                  href="?page=vtupanel&adminpage=orders"
                  class="
                    btn btn-info
                    d-flex
                    align-items-center
                    text-white
                  "
                  ><i class="mdi mdi-webpack font-20 me-2"></i> <span class="hide-menu"> Custom Orders </span></a
                >
</li>
  <?php
      }

      if(current_user_can("vtupress_access_license")){
  ?>
              <li class="sidebar-item p-2">
                <a
                  href="?page=vtupanel&adminpage=license"
                  class="
                    btn btn-cyan
                    d-flex
                    align-items-center
                    text-white
                  "
                  ><i class="fas fa-key font-20 me-2"></i> <span class="hide-menu"> License </span></a
                >
              </li>
  <?php
    }
  ?>


<li class="sidebar-item p-2">
                <a
                  href= "/<?php echo vp_getoption("vp_redirect");?>"
                  class="
                    btn btn-danger
                    d-flex
                    align-items-center
                    text-white
                  "
                  ><i class="fas fa-eye font-20 me-2"></i> <span class="hide-menu"> View Dashboard </span></a
                >
</li>

          </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Library
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>

        <div class="alert alert-info">
          <strong> <i class="fas fa-info"></i></strong>
          <span>Please note vtupress now work in other countries, only switch your country to another if you are building for customers of that country. You can't revert any country switch made...
            <a href="#" class="d-none">Read More</a>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================= -->
<?php

if(!isset($_GET["adminpage"]) && $_GET["page"] == "vtupanel" ){
  echo"";
  if(vp_getoption("vprun") != "none"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';
//wp_redirect($url);
    echo $string;

  }else{
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/dashboard.php');
  }

}
elseif($_GET["adminpage"] == "upgrade" ){
 
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/upgrade.php');

}
elseif($_GET["adminpage"] == "addons" ){
 
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/addons.php');

}
elseif($_GET["adminpage"] == "weblinksms" ){
 
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/weblink/sms.php');

}
elseif($_GET["adminpage"] == "orders" ){
 
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/customorders.php');

}
elseif(isset($_GET["adminpage"] ) && isset($_GET["subpage"] ) && $_GET["adminpage"] == "settings"){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/settings.php');
  }

}
elseif(isset($_GET["adminpage"] ) && isset($_GET["subpage"] ) && $_GET["adminpage"] == "history" && current_user_can('vtupress_access_history')){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history.php');
  }
 
}
elseif(isset($_GET["adminpage"] ) && isset($_GET["subpage"] ) && $_GET["adminpage"] == "users" && current_user_can('vtupress_access_users')){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users.php');
  }

}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "license" ){

  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/license.php');

}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "misc" ){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/misc.php');
  }
}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "messages"){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
  if(vp_getoption("resell") == "yes"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/messages.php');
  }
  else{
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
}

}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "notifications"){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
  if(vp_getoption("resell") == "yes"){
  include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/notifications.php');
  }
  else{
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
  }
}

}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "gateway" && isset($_GET["subpage"])){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
  if($_GET["subpage"] == "vtu"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/vtu.php');
  }
  elseif($_GET["subpage"] == "bet"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/bet.php');
  }
  elseif($_GET["subpage"] == "shared"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/shared.php');
  }
  elseif($_GET["subpage"] == "awuf"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/awuf.php');
  }
  elseif($_GET["subpage"] == "sme"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/sme.php');
  }
  elseif($_GET["subpage"] == "smile"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/smile.php');
  }
  elseif($_GET["subpage"] == "alpha"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/alpha.php');
  }
  elseif($_GET["subpage"] == "corporate"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/corporate.php');
  }
  elseif($_GET["subpage"] == "direct"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/direct.php');
  }
  elseif($_GET["subpage"] == "cable"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/cable.php');
  }
  elseif($_GET["subpage"] == "bill"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/gateway/bill.php');
  }

  do_action("vtupress_gateway_tab");
}

echo'
<script>
jQuery(".smspostkey, .vtupostkey, .sharedpostkey, .awufpostkey, .smepostkey, .smilepostkey, .directpostkey, .corporatepostkey, .cablepostkey, .billpostkey").css({"background-color":"#0dcaf0","color":"white"});

jQuery(".vtuaddvalue1, .shareaddvalue1, .awufaddvalue1, .smeaddvalue1, .smileaddvalue1, .directaddvalue1, .corporateaddvalue1, .cableaddvalue1, .billaddvalue1").css({"background-color":"pink","color":"white"});

jQuery(".vtuaddvalue2, .shareaddvalue2, .awufaddvalue2, .smeaddvalue2, .smileaddvalue2, .directaddvalue2, .corporateaddvalue2, .cableaddvalue2, .billaddvalue2").css({"background-color":"purple","color":"white"});

jQuery(".smspostvalue1, .airtimepostvalue1, .sairtimepostvalue1, .wairtimepostvalue1, .datapostvalue1, .rdatapostvalue1, .r2datapostvalue1, .cablepostvalue1, .billpostvalue1").css({"background-color":"#ffc107","color":"white"});

jQuery(".smspostvalue2, .airtimepostvalue2, .sairtimepostvalue2, .wairtimepostvalue2, .datapostvalue2, .rdatapostvalue2, .r2datapostvalue2, .cablepostvalue2, .billpostvalue2").css({"background-color":"#198754","color":"white"});


</script>
';

}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "isavings" && isset($_GET["subpage"])){
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;

  }else{
    if($_GET["subpage"] == "daily-settings"){
      include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/isavings/daily/settings.php');
    }
    elseif($_GET["subpage"] == "fixed-settings"){
      include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/isavings/fixed/settings.php');
    }
  elseif($_GET["subpage"] == "history"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/isavings/history.php');
  }
  elseif($_GET["subpage"] == "withdrawal"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/isavings/withdrawal.php');
  }

}


}
elseif(isset($_GET["adminpage"] ) && $_GET["adminpage"] == "import" && isset($_GET["subpage"])){

  if(!defined('ABSPATH')){
      $pagePath = explode('/wp-content/', dirname(__FILE__));
      include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
  }
  if(WP_DEBUG == false){
  error_reporting(0);	
  }
  include_once(ABSPATH."wp-load.php");
  include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

  $option_array = json_decode(get_option("vp_options"),true);
  
  if(vp_getoption("vprun") == "block"){
    $url = get_site_url()."/wp-admin/admin.php?page=vtupanel&adminpage=license";

    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url. '"';
    $string .= '</script>';

    echo $string;
    
  }else{
  if($_GET["subpage"] == "airtime"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/airtime.php');
  }
  elseif($_GET["subpage"] == "bet"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/bet.php');
  }
  elseif($_GET["subpage"] == "data"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/data.php');
  }
  elseif($_GET["subpage"] == "cable"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/cable.php');
  }
  elseif($_GET["subpage"] == "bill"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/bill.php');
  }
  elseif($_GET["subpage"] == "sms"){
    include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/importer/sms.php');
  }
  do_action("vtupress_admin_list_import");
}

}
else{

}

?>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
          All Rights Reserved by Bikendi
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/js/waves.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/dist/js/custom.min.js"></script>

    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/datatable-checkbox-init.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/jquery.multicheck.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/DataTables/datatables.min.js"></script>
    <script>
var width = jQuery(window).width()+"px";
//alert(width);
jQuery(".container-fluid").css("max-width",width);
jQuery(".container-fluid").css("min-height","150vh");
jQuery(".notice").hide();

<?php
if(get_site_url() == "https://demo.vtupress.com"){
?>
jQuery(document).ready(function(){
  var online = Math.floor(Math.random() * 600);
  var msg = "In no account should you pass any personal data, sensitive details or setup on this demo site! \n There are currently ["+online+"] people on this page and some might be spying. \n If you need to test live, kindly use your vtupress demo or live details on your website!!!";
  swal({
  title: "Beware!",
  text: msg,
  icon: "warning",
  button: "Okay",
});

});

<?php
}
?>


</script>

<!--Start of Tawk.to Script-->
<script type="text/javascript">

 // jQuery("a").attr("target","_blank");
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64a33990cc26a871b02624d7/1h4ephnkm';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


    </div>