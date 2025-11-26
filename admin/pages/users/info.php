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


	$vp_country = vp_country();
	$glo = $vp_country["glo"];
	$mobile = $vp_country["9mobile"];
	$mtn = $vp_country["mtn"];
	$airtel = $vp_country["airtel"];
	$bypass = $vp_country["bypass"];
	$currency = $vp_country["currency"];
	$symbol = $vp_country["symbol"];

$option_array = json_decode(get_option("vp_options"),true);

global $wpdb;
	$table_name = $wpdb->prefix."vp_levels";
	$level = $wpdb->get_results("SELECT * FROM  $table_name");


$id = $_GET["id"];
$user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);

$user = get_userdata($id);

$balance = vp_user_array($user_array,$id, "vp_bal", true);

$phone = vp_user_array($user_array,$id, "vp_phone", true);

$vpaccess = vp_user_array($user_array,$id, "vp_user_access", true);

$emailverify = vp_user_array($user_array,$id, "email_verified", true);

$pin = vp_user_array($user_array,$id, "vp_pin", true);

$fullname = vp_getuser($id,"first_name").' '.vp_getuser($id,"last_name");

if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes" ){
    $user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
    $plan = vp_user_array($user_array,$id,'vr_plan', true);

    $vrid = vp_user_array($user_array,$id, 'vr_id', true);
    $tme =  vp_user_array($user_array,$id, 'telegram_username', true);
        
    $ref = vp_user_array($user_array,$id, "vp_who_ref", true);

    $total_refered = vp_user_array($user_array,$id, "vp_tot_ref",true); // first level refered

    $total_inrefered = vp_user_array($user_array,$id, "vp_tot_in_ref",true);//second level refered

    $total_inrefered3 = vp_user_array($user_array,$id, "vp_tot_in_ref3",true);//third level refered


    $total_dir_earn = vp_user_array($user_array,$id, "vp_tot_ref_earn",true);//total earned from first level referree


    $total_indir_earn = vp_user_array($user_array,$id, "vp_tot_in_ref_earn",true); //total earned from second level referree


    $total_indir_earn3 = vp_user_array($user_array,$id, "vp_tot_in_ref_earn3",true); //total earned from third level referree


    $total_trans_bonus = vp_user_array($user_array,$id, "vp_tot_trans_bonus",true); //total earned from transactions


    $total_dirtrans_bonus = vp_user_array($user_array,$id, "vp_tot_dir_trans",true); //total earned from first level transactions bonus


    $total_indirtrans_bonus = vp_user_array($user_array,$id, "vp_tot_indir_trans",true); //total earned from second level transaction bonus


    $total_indirtrans_bonus3 = vp_user_array($user_array,$id, "vp_tot_indir_trans3",true); //total earned from third level transaction bonus


    $total_trans_attempt = vp_user_array($user_array,$id, "vp_tot_trans",true); //total transaction attempted


    $total_suc_trans = vp_user_array($user_array,$id, "vp_tot_suc_trans",true); //total successful transactions


    $total_withdraws = vp_user_array($user_array,$id, "vp_tot_withdraws",true); //total withdrawals

    $total_bal_with = intval($total_dir_earn) + intval($total_indir_earn) + intval($total_indir_earn3) + intval($total_trans_bonus) + intval($total_dirtrans_bonus) + intval($total_indirtrans_bonus) + intval($total_indirtrans_bonus3); //withdrawable
      
}



function get_referees_by_referrer_id($referrer_id) {
    global $wpdb;
    $data = [];
    $table = $wpdb->prefix . "usermeta";
    $prepare = "SELECT user_id FROM $table  WHERE meta_key = 'vp_user_data'  AND meta_value LIKE '%\"vp_who_ref\":\"$referrer_id\"%'";
            

    $user_ids = $wpdb->get_col($prepare);

    // echo $prepare;

    foreach ($user_ids as $this_user_id) {
        $bvn = vp_getuser($this_user_id, "myBvn", true);
        $nin = vp_getuser($this_user_id, "myNin", true);

        $tabs = ["sairtime", "sdata"];
        foreach ($tabs as $tab_name) {
            $stabs = $wpdb->prefix . $tab_name;
            $count = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(id) FROM $stabs WHERE user_id = %d AND status = 'Successful'",
                    $this_user_id
                )
            );
            $$tab_name = $count;
        }

        $data[$this_user_id] = [
            "airtime" => $sairtime ?? 0,
            "data" => $sdata ?? 0,
            "bvn" => $bvn,
            "nin" => $nin,
        ];
    }

    return $data;
}


$ref_data = get_referees_by_referrer_id($id);


?>

<div class="row">
<div class="col-12">

<div class="card">
                <div class="card-body">
                
                <h5 class="card-title">(<?php echo $id;?>) <?php echo strtoupper($user->user_login);?> <button class=" float-end btn-sm switch-to rounded bg bg-info text-white" onclick="switchto(<?php echo $id;?>);">Switch To Account </button>  <button class="btn btn-sm rounded ban bg bg-danger text-white"  onclick="ban(<?php echo $id;?>);">Ban User</button> <button class="btn rounded btn-sm un-ban bg bg-success text-white"  onclick="unban(<?php echo $id;?>);">Un-Ban User</button> <button class="btn rounded btn-sm verify-email bg bg-success text-white"  onclick="verifyemail(<?php echo $id;?>);">Verify User Email</button> </h5>

                <div class="table-responsive">

                  <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a
                      class="nav-link active"
                      data-bs-toggle="tab"
                      href="#action"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Info & Actions</span></a
                    >
                  </li>
                  <?php
                  if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes" ){
                    ?>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#info"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Other Info</span></a
                    >
                  </li>
                  <?php
                  }
                  ?>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#metric"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Ref Metric</span></a
                    >
                  </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabcontent-border">
                  <div class="tab-pane active" id="action" role="tabpanel">
                    <div class="p-20">

                                <!--//////////////////////////////////////////////////////-->
                              <div class="row">
                                            <!-- Column -->
                                    <div class="col-md-6 col-lg-2 col-xlg-3 position-relative">
                                            <span title="Set Balance" data-bs-toggle="modal" data-bs-target="#balance" class="position-absolute position-absolute top-0 end-5 badge rounded-pill bg-secondary" style="z-index:2;"><i class="mdi mdi-settings fs-6"></i></span>
                                            <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-secondary" style="z-index:2;"><i class="">Balance</i></span>
                                              <div class="card card-hover">
                                                <div class="box bg-cyan text-center">
                                                  <h1 class="font-light text-white">
                                                    <i class="mdi mdi-view-dashboard"></i>
                                                  </h1>
                                                  <h6 class="text-white"> <?php echo $symbol.$balance;?></h6>
                                                </div>
                                              </div>
                                    </div>
                                            <!-- Column -->
                                    <div class="col-md-6 col-lg-4 col-xlg-3 position-relative">
                                          <span  title="Change Email"  data-bs-toggle="modal" data-bs-target="#email"  class="position-absolute position-absolute top-0 end-5 badge rounded-pill bg-secondary" style="z-index:2;"><i class="mdi mdi-settings fs-6"></i></span>
                                          <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-secondary" style="z-index:2;"><i class="">Nm/Em</i></span>
                                            <div class="card card-hover">
                                              <div class="box bg-success text-center">
                                                <h1 class="font-light text-white">
                                                  <i class="mdi mdi-chart-areaspline"></i>
                                                </h1>
                                                <h6 class="text-white"><?php echo $fullname;?></h6>
                                                <h6 class="text-white"><?php echo $user->user_email;?></h6>
                                              </div>
                                            </div>
                                    </div>
                                            <!-- Column -->
                                    <div class="col-md-6 col-lg-2 col-xlg-3 position-relative">
                                      <span  title="Change Phone Number"  data-bs-toggle="modal" data-bs-target="#phone"  class="position-absolute position-absolute top-0 end-5 badge rounded-pill bg-secondary" style="z-index:2;"><i class="mdi mdi-settings fs-6"></i></span>
                                      <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-secondary" style="z-index:2;"><i class="">Phone</i></span>
                                        <div class="card card-hover">
                                          <div class="box bg-warning text-center">
                                            <h1 class="font-light text-white">
                                              <i class="mdi mdi-collage"></i>
                                            </h1>
                                            <h6 class="text-white"><?php echo $phone;?></h6>
                                          </div>
                                        </div>
                                    </div>
                                            <!-- Column -->
                                    <div class="col-md-6 col-lg-2 col-xlg-3 position-relative">
                                      <span   title="Change Pin" data-bs-toggle="modal" data-bs-target="#pin"  class="position-absolute position-absolute top-0 end-5 badge rounded-pill bg-secondary" style="z-index:2;"><i class="mdi mdi-settings fs-6"></i></span>
                                      <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-secondary" style="z-index:2;"><i class="">Pin</i></span>
                                        <div class="card card-hover">
                                          <div class="box bg-danger text-center">
                                            <h1 class="font-light text-white">
                                              <i class="mdi mdi-border-outside"></i>
                                            </h1>
                                            <h6 class="text-white"><?php echo $pin;?></h6>
                                          </div>
                                        </div>
                                    </div>
                                            <!-- Column -->
                                    <?php
                                        if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes" ){
                                        ?>
                                                    <div class="col-md-6 col-lg-2 col-xlg-3 position-relative">
                                                    <span  title="Change Plan || Api Key"  data-bs-toggle="modal" data-bs-target="#plan"   class="position-absolute position-absolute top-0 end-5 badge rounded-pill bg-secondary" style="z-index:2;"><i class="mdi mdi-settings fs-6"></i></span>
                                                    <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-secondary" style="z-index:2;"><i class="">Plan</i></span>
                                                      <div class="card card-hover">
                                                        <div class="box bg-info text-center">
                                                          <h1 class="font-light text-white">
                                                            <i class="mdi mdi-arrow-all"></i>
                                                          </h1>
                                                          <h6 class="text-white"><?php echo ucfirst($plan);?></h6>
                                                          <h6 class="text-white"><?php echo ucfirst($vrid);?></h6>
                                                        </div>
                                                      </div>
                                                    </div>
                                        <?php
                                        }
                                      ?>
                                            <!-- Column -->
                                            <!-- Column -->
                                </div>




                              <!--BALANCE MODAL -->
                              <div class="modal fade" id="balance" tabindex="-1" aria-labelledby="balance" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Manage User Balance</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <form>
                                        <div class="mb-3">
                                          <label for="recipient-name" class="col-form-label">Amount:</label>
                                          <input type="number" class="form-control balance-amount" id="recipient-name">
                                        </div>
                                        <div class="mb-3">
                                          <label for="message-text" class="col-form-label">Reason:</label>
                                          <textarea class="form-control balance-message" id="message-text"></textarea>
                                        </div>
                                      </form>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-danger remove-balance" onclick="userbalance(<?php echo $id;?>, 'remove');">Remove Balance</button>
                                      <button type="button" class="btn btn-info set-balance" onclick="userbalance(<?php echo $id;?>, 'set');">Set Balance</button>
                                      <button type="button" class="btn btn-success add-balance" onclick="userbalance(<?php echo $id;?>, 'add');">Add Balance</button>
                                    </div>
                                  </div>
                                </div>
                              </div>


                              <!--EMAIL MODAL -->
                              <div class="modal fade" id="email" tabindex="-1" aria-labelledby="email" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Email Settings</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <form>
                                        <div class="mb-3">
                                          <label for="recipient-email" class="col-form-label">Change Full Name To:</label>
                                          <input type="text" class="form-control full-name" id="full-name" value="<?php echo $fullname;?>" placeholder="e.g Akor Victor" >
                                          <label for="recipient-email" class="col-form-label">Change Email To:</label>
                                          <input type="text" class="form-control user-email" id="recipient-email" value="<?php echo $user->user_email;?>">
                                        </div>
                                      </form>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-success" onclick="changeemail(<?php echo $id;?>);">Change Details</button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!--PHONE MODAL -->
                              <div class="modal fade" id="phone" tabindex="-1" aria-labelledby="phone" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Phone Number Setting</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <form>
                                        <div class="mb-3">
                                          <label for="recipient-phone" class="col-form-label">Change Phone Number To:</label>
                                          <input type="number" class="form-control user-phone" id="recipient-phone">
                                        </div>
                                      </form>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-success" onclick="changephone(<?php echo $id;?>);">Change Phone Number</button>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <!--PIN MODAL -->
                              <div class="modal fade" id="pin" tabindex="-1" aria-labelledby="pin" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Pin Setting</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>balance-reason
                                    <div class="modal-body">
                                      <form>
                                        <div class="mb-3">
                                          <label for="recipient-pin" class="col-form-label">Change Pin To:</label>
                                          <input type="number" class="form-control user-pin" id="recipient-pin">
                                        </div>
                                      </form>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-success" onclick="changepin(<?php echo $id;?>);">Change Pin</button>
                                    </div>
                                  </div>
                                </div>
                              </div>


                              <!--PLAN MODAL -->
                              <div class="modal fade" id="plan" tabindex="-1" aria-labelledby="plan" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="exampleModalLabel">Package Setting</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <form>
                                        <div class="mb-3">
                                                <select class="form-control user-plan" id="recipient-plan">
                                                        <?php
                                                              foreach($level as $lev){
                                                                  if($plan != $lev->name){
                                                                    echo"
                                                                    <option value='{$lev->name}'>{$lev->name}</option>
                                                                    ";	
                                                                  }
                                                                  else{
                                                                      echo"
                                                                      <option value='{$lev->name}' selected >{$lev->name}</option>
                                                                      ";	                         
                                                                  }

                                                              }

                                                        ?>

                                                </select>
                                        </div>
                                        <div class="mb-3">
                                                    <label for="recipient-key" class="col-form-label">API KEY:</label>
                                                    <input type="text" class="form-control user-key" id="recipient-key" value="<?php echo $vrid;?>" \>
                                        </div>
                                        <div class="mb-3">
                                                    <label for="recipient-key" class="col-form-label">TELEGRAM USERNAME:</label>
                                                    <input type="text" class="form-control telegram-username" id="telegram-username" value="<?php echo $tme;?>" \>
                                        </div>
                                      </form>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="button" class="btn btn-success" onclick="changeplan(<?php echo $id;?>);">Change Plan</button>
                                    </div>
                                  </div>
                                </div>
                              </div>



                    </div>
                  </div>
                  <?php
                  if(is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array,'mlm') == "yes" ){
                    ?>
                    <div class="tab-pane p-20" id="info" role="tabpanel">
                      <div class="p-20 table-responsive">

                          <!--//////////////////////////////////////////////////////-->
                          <table id="zero_config" class="table table-striped table-bordered" >
                                  <thead>
                                        <tr>
                                            <th>API KEY</th>
                                            <th>Total Transactions Attempted</th>
                                            <th>Total Successful Transaction</th>
                                            <th>Total Transaction Bonus</th>
                                            <th>Referer</th>
                                            <th>1st Level Referee</th>
                                            <th>2nd Level Referee</th>
                                            <th>3rd Level Referee</th>
                                            <th>Ref. Bonus Earned From 1st Level</th>
                                            <th>Ref. Bonus Earned From 2nd Level</th>
                                            <th>Ref. Bonus Earned From 3rd Level</th>
                                            <th>Transaction Bonus Earned From 1st Level</th>
                                            <th>Transaction Bonus Earned From 2nd Level</th>
                                            <th>Transaction Bonus Earned From 3rd Level</th>
                                            <th>Total Withdrawals</th>
                                            <th>Balance Withdrawable</th>
                                        </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                        <?php

                                          echo"
                                          <td>$vrid</td>
                                          <td>$total_trans_attempt</td>
                                          <td>$total_suc_trans</td>
                                          <td>$total_trans_bonus</td>
                                          <td>$ref</td>
                                          <td>$total_refered</td>
                                          <td>$total_inrefered</td>
                                          <td>$total_inrefered3</td>
                                          <td>$total_dir_earn</td>
                                          <td>$total_indir_earn</td>
                                          <td>$total_indir_earn3</td>
                                          <td>$total_dirtrans_bonus</td>
                                          <td>$total_indirtrans_bonus</td>
                                          <td>$total_indirtrans_bonus3</td>
                                          <td>$total_withdraws</td>
                                          <td>$total_bal_with</td>
                                          ";
                                        ?>
                                      </tr>
                                  </tbody>
                          </table>



                      </div>
                    </div>
                              <?php
                  }
                    ?>
                  <div class="tab-pane p-20" id="metric" role="tabpanel">
                    <div class="p-20 table-responsive">
                        <table class="table table-striped table-bordered">
                          <thead>
                            <tr>
                              <th>User Id</th>
                              <th>Airtime</th>
                              <th>Data</th>
                              <th>Bvn</th>
                              <th>Nin</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach($ref_data as $key => $val):?>
                                <tr>
                                  <td><?php echo $key;?></td>
                                  <td><?php echo $val["airtime"];?></td>
                                  <td><?php echo $val["data"];?></td>
                                  <td><?php echo $val["bvn"];?></td>
                                  <td><?php echo $val["nin"];?></td>
                                </tr>
                            <?php endforeach;?>
                          </tbody>
                        </table>

                      </div>
                  </div>


                </div>
              </div>




</div>
</div>
</div>
<script>
<?php
if($vpaccess != "ban"){
  ?>
  jQuery(".un-ban").hide();
  <?php
}
  else{
  ?>
  jQuery(".ban").hide();
  <?php
  }
  if($emailverify == "verified"){
    ?>
    jQuery(".verify-email").hide();
    <?php
    }
    else{
    ?>
    jQuery(".verify-email").show();
    <?php
    }
?>

//verifyemail
function verifyemail(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "verify_user";
    
	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text:  "<?php echo ucfirst($user->user_login);?> Email Now Verified",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Verifying User Email ",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}



function unban(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "useraccess";
    
	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text:  "<?php echo ucfirst($user->user_login);?> Is Now Un-Banned",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Un-Banning Account",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}



function ban(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "userban";
    
	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text:  "<?php echo ucfirst($user->user_login);?> Is Now Banned",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Banning Account",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}



function switchto(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "switchto";
    
	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text:  "Successfully Switched To <?php echo ucfirst($user->user_login);?> Account",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.href = "<?php echo vp_option_array($option_array,'siteurl');?>/vpaccount";
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Switching Account",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}




function changeplan(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "changeplan";
    obj['plan'] = jQuery(".user-plan").val();
    obj['apikey'] = jQuery(".user-key").val();
    obj['tme'] = jQuery(".telegram-username").val();

    if(obj['apikey'] == "" || obj['apikey'] == " " || obj['apikey'].toLowerCase() == 'null'){
        var message = "User Plan And Api Key Changed";
    }
    else{
        var message = "User Plan Changed";
    }


	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text: message,
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Changing Plan",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}




function changepin(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "changepin";
    obj['pin'] = jQuery(".user-pin").val();


	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text: "Pin Changed!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Changing Pin",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}



function changephone(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "changephone";
    obj['phone'] = jQuery(".user-phone").val();


	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text: "Phone Number Changed!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Changing Phone",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}



function changeemail(id){
    var obj = {};
    var message;
    obj["userid"] = id;
    obj["action"] = "changeemail";
    obj['usemail'] = jQuery(".user-email").val();
    obj['fullname'] = jQuery(".full-name").val();


	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text: "Email Changed!",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data.status == "101"){
		  		  swal({
  title: "Error",
  text: "Name Updated But User With The Email Already Exist",
  icon: "warning",
  button: "Okay",
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Error Processing Email Change",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}




function userbalance(id, action){
    var obj = {};
    var message;
    obj["userid"] = id;
   
switch(action){
case"add":
    obj["action"] = "addbalance";
message = "<?php echo strtoupper($user->user_login);?> Is Now Funded With <?php echo $symbol;?>"+jQuery(".balance-amount").val();
    break;
    case"set":
        obj["action"] = "setbalance";
        message = "<?php echo strtoupper($user->user_login);?> Now Has A Balance Of  <?php echo $symbol;?>"+jQuery(".balance-amount").val();
        break;
        case"remove":
            obj["action"] = "removebalance";
            message = "<?php echo $symbol;?>"+jQuery(".balance-amount").val()+" Now deducted from  <?php echo strtoupper($user->user_login);?>'s Balance";
            break;
}

    obj["amount"] = jQuery(".balance-amount").val();
    obj["reason"] = jQuery(".balance-message").val();


	jQuery(".preloader").show();
    jQuery(".modal").hide();
    obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: "<?php echo esc_url(plugins_url("vtupress/admin/pages/users/saves/users.php"));?>",
  data: obj,
  dataType: "json",
  "cache": false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery(".preloader").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  success: function(data) {
	jQuery(".preloader").hide();
        if(data.status == "100" ){
	
		  swal({
  title: "Successful",
  text: message,
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else if(data.status == "101"){
		  		  swal({
  title: "SWITCHED",
  text: "You\'ll be redirected to the selected user Dashboard",
  icon: "success",
  button: "Okay",
}).then((value) => {
location.href = "/vpaccount";
});
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: "Saving Wasn\"t Successful",
  icon: "error",
  button: "Okay",
});
	  }
  },
  type: "POST"
});

}
     
</script>
</div>
</div>