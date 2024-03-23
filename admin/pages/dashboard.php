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
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

$option_array = json_decode(get_option("vp_options"),true);


$banedusers = intval($wpdb->get_var("SELECT count(id) AS id FROM $userd WHERE vp_ban = 'ban' "));
$highestbal = $wpdb->get_results("SELECT MAX(vp_bal) AS vp_bal FROM $userd")[0]->vp_bal;
$highestbaluser = $wpdb->get_results("SELECT * FROM $userd ORDER BY vp_bal DESC LIMIT 10 ");
$allusers = intval($wpdb->get_var("SELECT count(id) AS id FROM $userd"));
$usersbalance = intval($wpdb->get_var("SELECT sum(vp_bal) AS vp_bal FROM $userd"));
$userswithfunds = intval($wpdb->get_var("SELECT count(id) AS vp_bal FROM $userd WHERE vp_bal > 0"));
$userswithoutfunds = intval($wpdb->get_var("SELECT count(id) AS vp_bal FROM $userd WHERE vp_bal <= 0 "));
$datapending = intval($wpdb->get_var("SELECT count(id) AS id FROM $datad WHERE status = 'Pending' "));
$withpending = intval($wpdb->get_var("SELECT count(id) AS id FROM $withd WHERE status = 'Pending' "));
$atcpending = intval($wpdb->get_var("SELECT count(id) AS id FROM $atcd WHERE status = 'pending' AND type = 'Airtime_To_Wallet'"));


//$messages = $wpdb->get_results("SELECT * FROM $messd WHERE type = 'received' AND status = 'unread' GROUP BY user_id ORDER BY id DESC LIMIT 3 ");


?>
<style>
  .toggler-wrapper {
  display: block;
  width: 30px;
  height: 15px;
  cursor: pointer;
  position: relative;
}

.toggler-wrapper input[type="checkbox"] {
  display: none;
}

.toggler-wrapper input[type="checkbox"]:checked+.toggler-slider {
  background-color: #44cc66;
}

.toggler-wrapper .toggler-slider {
  background-color: #ccc;
  position: absolute;
  border-radius: 100px;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  -webkit-transition: all 300ms ease;
  transition: all 300ms ease;
}

.toggler-wrapper .toggler-knob {
  position: absolute;
  -webkit-transition: all 300ms ease;
  transition: all 300ms ease;
}

.toggler-wrapper.style-1 input[type="checkbox"]:checked+.toggler-slider .toggler-knob {
  left: calc(100% - 10px - 3px);
}

.toggler-wrapper.style-1 .toggler-knob {
  width: calc(25px - 6px);
  height: calc(22px - 6px);
  border-radius: 50%;
  /*left: 3px;
  top: 3px;*/
  background-color: #fff;
}


</style>

        <div class="container-fluid dashboard-container">
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
<?php
                  if(vp_option_array($option_array,'vp_security') == "yes" && vp_option_array($option_array,"secur_mod") != "off"){
                    ?>
                    <i class="fas fa-check"></i>
                    <?php
                  }
                  else{
                    ?>
                    <i class="fas fa-times"></i>
                    <?php
                  }
                  ?>
                  </h1>
                  <h6 class="text-white">Security</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                 <?php echo intval($banedusers);?>
                  </h1>
                  <h6 class="text-white">Users Banned</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                   <?php echo $usersbalance;?>
                  </h1>
                  <h6 class="text-white">Users Balance</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                  <h1 class="font-light text-white">
                  <?php echo intval(vp_option_array($option_array,'vp_funds_fixed'));?>
                  </h1>
                  <h6 class="text-white">Funds Fixed</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                  <?php echo intval(vp_option_array($option_array,'vp_trans_fixed'));?>
                  </h1>
                  <h6 class="text-white">Trans Fixed/Saved</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-md-6 col-lg-4 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-danger text-center">
                  <h1 class="font-light text-white">
                    <?php echo intval($datapending);?>
                  </h1>
                  <h6 class="text-white">Data Pending History</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-warning text-center">
                  <h1 class="font-light text-white">
                    0
                  </h1>
                  <h6 class="text-white">Pending Transfers</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <?php echo intval($allusers);?>
                  </h1>
                  <h6 class="text-white">Users</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <?php echo intval($withpending);?>
                  </h1>
                  <h6 class="text-white">Pending Withdrawals</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <?php echo $atcpending;?>
                  </h1>
                  <h6 class="text-white">A.T.C</h6>
                </div>
              </div>
            </div>
            <!-- Column -->
            <!-- Column -->
          </div>
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->

          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-md-flex align-items-center">
                    <div>
                      <h4 class="card-title">Sales Analysis</h4>
                      <h5 class="card-subtitle">All Sales Analysis</h5>
                    </div>
                  </div>


                  <div class="row">
                    <!-- column --> 
                    <div class="col-lg-12">
                      <!-- ADD STATS HERE -->
                      <div class="xcontainer-fluidx">
                          <div class="row">
                              <div class="col-md my-1 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col-12 date_picker_col">
                                          <select name="" id="" class="form-control date_picker" >
                                              <option value="last_30_days">Last 30 Days</option>
                                              <option value="last_90_days">Last 90 Days</option>
                                              <option value="all_time">All Time</option>
                                              <option value="today" selected>Today</option>
                                              <option value="this_week">This Week</option>
                                              <option value="this_month">This Month</option>
                                              <option value="this_year">This Year</option>
                                              <option value="custom">Custom</option>
                                          </select>
                                      </div>
                                      <div class="col-12 custom_date d-none">
                                          
                                          <div class="input-group">
                                              <span class="from_date_label "><i class="far fa-times-circle input-group-text text-danger close_custom_dp" onclick="jQuery('.custom_date').addClass('d-none');jQuery('.date_picker_col').removeClass('d-none');jQuery('.date_picker').val('today');"></i></span>
                                              <span class="from_date_label input-group-text">From</span>
                                              <input type="date" class="form-control  from_date"  min="2021-12-01" max="<?php echo date("Y-m-d");?>">
                                              <span class="to_date_label input-group-text">To</span>
                                              <input type="date" class="form-control  to_date" min="2021-12-01" max="<?php echo date("Y-m-d");?>">
                                              <button class="customQueryDb btn btn-success text-white btn-sm " ><i class="far fa-check-circle fw-100 fs-4"></i></button>
                                          </div>
                                      </div>
                                  </div>



                              </div>
                              <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col">
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-6 fs-6">Total Earned</span>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-2 fs-2 sales_first_load total_earned">00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-2 text-md-center d-flex align-items-center justify-content-end">
                                          <i class="far fa-arrow-alt-circle-right text-success mx-3 fs-4"></i>
                                          <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>

                                      </div>
                                  </div>



                              </div>
                          </div>


                          <div class="row my-3">
                              <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col">
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-6 fs-6">Airtime</span>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-2 fs-2 sales_first_load airtime_earned">00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-2  airtime_available d-none text-md-center">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox" class="airtime_check airtime" onchange="changestatus('airtime')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 airtime_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 airtime_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 airtime_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                          
                                      </div>
                                  </div>



                              </div>
                              <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col">
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-6 fs-6">Data</span>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-2 fs-2 sales_first_load data_earned">00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-2  data_available d-none text-md-center">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="data_check data" onchange="changestatus('data')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 data_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 data_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 data_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                          
                                      </div>
                                  </div>



                              </div>
                              <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col">
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-6 fs-6">Cable</span>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-2 fs-2 sales_first_load cable_earned">00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-2 text-md-center cable_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="cable_check cable" onchange="changestatus('cable')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 cable_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 cable_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 cable_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                          
                                      </div>
                                  </div>



                              </div>
                              <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                  <div class="row">
                                      <div class="col">
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-6 fs-6">Bill</span>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col">
                                                  <span class="fw-2 fs-2 sales_first_load bill_earned">00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-2 text-md-center bill_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="bill_check bill" onchange="changestatus('bill')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 bill_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 bill_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 bill_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                          
                                      </div>
                                  </div>



                              </div>
                          </div>


                            <div class="row">
                                <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-6 fs-6">Sms</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-2 fs-2 sales_first_load sms_earned">00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-md-center sms_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="sms_check sms" onchange="changestatus('sms')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 sms_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 sms_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 sms_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>

                                        </div>
                                    </div>



                                </div>
                                <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-6 fs-6">Recharge Card</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-2 fs-2 sales_first_load recharge_earned">00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-md-center recharge_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="recharge_check cards"  onchange="changestatus('cards')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 recharge_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 recharge_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 recharge_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                            
                                        </div>
                                    </div>



                                </div>
                                <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-6 fs-6">Data Card</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-2 fs-2 sales_first_load datacard_earned">00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-md-center datacard_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="datacard_check datas"  onchange="changestatus('datas')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 datacard_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 datacard_href"></a>
                                            <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4 datacard_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                            
                                        </div>
                                    </div>



                                </div>
                                <div class="col-md my-2 my-md-0 shadow-none p-3 border bg-light rounded">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-6 fs-6">Exam Pin</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <span class="fw-2 fs-2 sales_first_load epin_earned">00</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 text-md-center epin_available d-none">
                                                <label class="toggler-wrapper style-1">
                                                            <input type="checkbox"  class="epin_check epins"   onchange="changestatus('epins')">
                                                            <div class="toggler-slider">
                                                              <div class="toggler-knob"></div>
                                                              </div>
                                                </label>
                                                <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-success fs-4 epin_href"></a>
                                                <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-danger fs-4 epin_href"></a>
                                                <a href="#" class="text-decoration-none far fa-arrow-alt-circle-right text-warning fs-4  epin_href"></a>
                                            <i class="far fa-arrow-alt-circle-down d-none text-primary fs-4"></i>
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>
                       </div>


                       <script>

document.addEventListener("DOMContentLoaded", () =>{
  jQuery(".sales_first_load").html('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');

  var sendFromDate;
  var sendToDate;

  var currentDate = new Date();
  var currentDate2 = new Date("2021-12-01");
  // Calculate the date four days ago
  var predate = new Date(currentDate);
  var prodate = new Date(currentDate);

function setDateFt(){

  currentDate = new Date();
  currentDate2 = new Date("2021-12-01");
  // Calculate the date four days ago
  predate = new Date(currentDate);
  prodate = new Date(currentDate);

 var date_picker = jQuery('.date_picker').val();

var dDate;

 switch(date_picker){
    case"last_30_days":

    predate.setDate(currentDate.getDate() - 30);
    prodate.setDate(currentDate.getDate());

        // Format the fourDaysAgo date as a string
       // dDate = predate.toDateString();



    break;
    case"last_90_days":
    predate.setDate(currentDate.getDate() - 90);
    prodate.setDate(currentDate.getDate());

        // Format the fourDaysAgo date as a string
        //dDate = predate.toDateString();
    break;
    case"all_time":
      predate = new Date(currentDate2);
      predate.setDate(currentDate2.getDate());
      prodate.setDate(currentDate.getDate());
    break;
    case"today":
    predate.setDate(currentDate.getDate());
    prodate.setDate(currentDate.getDate());
    break;
    case"this_week":
    var currentDay = currentDate.getDay(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
  
    predate.setDate(currentDate.getDate() - currentDay);
    prodate.setDate(currentDate.getDate());
    break;
    case"this_month":

    predate.setDate(1);
   // var currentMonth = currentDate.getMonth(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
  
    prodate.setDate(currentDate.getDate());
    break;
    case"this_year":
    predate.setMonth(0);
    predate.setDate(1);

   // var currentYear = currentDate.getFullYear(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    
   // predate.setDate(currentDate.getDate() - currentYear);
    prodate.setDate(currentDate.getDate());
    break;
    case"custom":
        jQuery(".custom_date").removeClass("d-none");
        jQuery(".date_picker_col").addClass("d-none");
    break;
 };


       // Format the oneMonthAgo date as "yyyy-mm-dd HH:mm:ss"
       var fromDate = predate.getFullYear() + '-' +
                         ('0' + (predate.getMonth() + 1)).slice(-2) + '-' +
                         ('0' + predate.getDate()).slice(-2);/* + ' ' +
                         ('0' + predate.getHours()).slice(-2) + ':' +
                         ('0' + predate.getMinutes()).slice(-2) + ':' +
                         ('0' + predate.getSeconds()).slice(-2);*/

        
               // Format the oneMonthAgo date as "yyyy-mm-dd HH:mm:ss"
       var toDate = prodate.getFullYear() + '-' +
                         ('0' + (prodate.getMonth() + 1)).slice(-2) + '-' +
                         ('0' + prodate.getDate()).slice(-2);/* + ' ' +
                         ('0' + predate.getHours()).slice(-2) + ':' +
                         ('0' + predate.getMinutes()).slice(-2) + ':' +
                         ('0' + predate.getSeconds()).slice(-2);*/

        

                         //alert(dDate+" =  "+formattedDate);
        jQuery(".from_date").val(fromDate);
        jQuery(".to_date").val(toDate);


        sendFromDate =   predate.getFullYear() + '-' +
                         ('0' + (predate.getMonth() + 1)).slice(-2) + '-' +
                         ('0' + predate.getDate()).slice(-2);+ ' ' +
                         ('0' + predate.getHours()).slice(-2) + ':' +
                         ('0' + predate.getMinutes()).slice(-2) + ':' +
                         ('0' + predate.getSeconds()).slice(-2);

        sendToDate =   prodate.getFullYear() + '-' +
                         ('0' + (prodate.getMonth() + 1)).slice(-2) + '-' +
                         ('0' + prodate.getDate()).slice(-2);+ ' ' +
                         ('0' + prodate.getHours()).slice(-2) + ':' +
                         ('0' + prodate.getMinutes()).slice(-2) + ':' +
                         ('0' + prodate.getSeconds()).slice(-2);

      //alert("From "+sendFromDate +"\nTo "+sendToDate);

};




function queryDB(from,to){

  jQuery.get("<?php echo plugins_url("vtupress/admin/loader/total.php");?>?spraycode=<?php echo htmlspecialchars(vp_getoption("spraycode"));?>&fromDate="+from+"&toDate="+to, function (ddata) {
                // Set JSON data into specific elements
                //$("#title").text(data.title);
               // $("#description").text(data.description);
                
                // Assuming data.items is an array, loop through it and add to a list

                try {
                    var jsonData = JSON.parse(ddata);
                    jQuery(".airtime_earned").text(jsonData.airtime);
                    jQuery(".data_earned").text(jsonData.data);
                    jQuery(".cable_earned").text(jsonData.cable);
                    jQuery(".bill_earned").text(jsonData.bill);
                    jQuery(".sms_earned").text(jsonData.sms);
                    jQuery(".recharge_earned").text(jsonData.recharge);
                    jQuery(".datacard_earned").text(jsonData.datacard);
                    jQuery(".epin_earned").text(jsonData.epin);
                    jQuery(".total_earned").text(jsonData.earn);

                    jQuery(".airtime_check").prop(jsonData.airtime_check,true);
                    jQuery(".data_check").prop(jsonData.data_check,true);
                    jQuery(".cable_check").prop(jsonData.cable_check,true);
                    jQuery(".bill_check").prop(jsonData.bill_check,true);
                    jQuery(".sms_check").prop(jsonData.sms_check,true);
                    jQuery(".recharge_check").prop(jsonData.recharge_check,true);
                    jQuery(".datacard_check").prop(jsonData.datacard_check,true);
                    jQuery(".epin_check").prop(jsonData.epin_check,true);

                    jQuery(".airtime_available").removeClass("d-none");
                    jQuery(".data_available").removeClass("d-none");
                    jQuery(".airtime_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=airtime&type=successful&fromDate="+from+"&toDate="+to);
                    jQuery(".airtime_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=airtime&type=unsuccessful&fromDate="+from+"&toDate="+to);
                    jQuery(".airtime_available a.text-danger").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=airtime&type=failed&fromDate="+from+"&toDate="+to);
                    jQuery(".data_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=data&type=successful&fromDate="+from+"&toDate="+to);
                    jQuery(".data_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=data&type=unsuccessful&fromDate="+from+"&toDate="+to);
                    jQuery(".data_available a.text-danger").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=data&type=failed&fromDate="+from+"&toDate="+to);
                    

                    if(jsonData.cable_available){
                      jQuery(".cable_available").removeClass("d-none");
                      jQuery(".cable_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=cable&type=successful&fromDate="+from+"&toDate="+to);
                      jQuery(".cable_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=cable&type=unsuccessful&fromDate="+from+"&toDate="+to);
                      jQuery(".cable_available a.text-danger").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=cable&type=failed&fromDate="+from+"&toDate="+to);
                    }
                    if(jsonData.bill_available){
                      jQuery(".bill_available").removeClass("d-none");
                      jQuery(".bill_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=bill&type=successful&fromDate="+from+"&toDate="+to);
                      jQuery(".bill_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=bill&type=unsuccessful&fromDate="+from+"&toDate="+to);
                      jQuery(".bill_available a.text-danger").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=bill&type=failed&fromDate="+from+"&toDate="+to);
                    }
                    if(jsonData.sms_available){
                      jQuery(".sms_available").removeClass("d-none");
                      jQuery(".sms_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=sms&type=approved&fromDate="+from+"&toDate="+to);
                      jQuery(".sms_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=sms&type=pending&fromDate="+from+"&toDate="+to);
                      jQuery(".sms_available a.text-danger").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=sms&type=failed&fromDate="+from+"&toDate="+to);
                    }
                    if(jsonData.recharge_available){
                      jQuery(".recharge_available").removeClass("d-none");
                      jQuery(".recharge_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=recharge&type=successful&fromDate="+from+"&toDate="+to);
                      jQuery(".recharge_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=recharge&type=unsuccessful&fromDate="+from+"&toDate="+to);
                      jQuery(".recharge_available a.text-danger").hide();
                    
                    }
                    if(jsonData.datacard_available){
                      jQuery(".datacard_available").removeClass("d-none");
                      jQuery(".datacard_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=datacard&type=successful&fromDate="+from+"&toDate="+to);
                      jQuery(".datacard_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=datacard&type=unsuccessful&fromDate="+from+"&toDate="+to);
                      jQuery(".datacard_available a.text-danger").hide();
                    }
                    if(jsonData.epin_available){
                      jQuery(".epin_available").removeClass("d-none");
                      jQuery(".epin_available a.text-success").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=epin&type=successful&fromDate="+from+"&toDate="+to);
                      jQuery(".epin_available a.text-warning").attr("href","admin.php?page=vtupanel&adminpage=history&subpage=epin&type=unsuccessful&fromDate="+from+"&toDate="+to);
                      jQuery(".epin_available a.text-danger").hide();
                    }
                } catch (error) {
                    console.error("Error parsing JSON response: " + error);
                }
                
                  
                  
                  
                  
                  
                  //console.error("Error: " + ddata);
            })
            .fail(function (xhr, status, error) {
                console.error("Error: " + status + " - " + error);
            });

            /*

jQuery(".airtime_earned").load("<?php echo plugins_url("vtupress/admin/loader/airtime.php");?>?fromDate="+from+"&toDate="+to);
jQuery(".data_earned").load("<?php echo plugins_url("vtupress/admin/loader/data.php");?>?fromDate="+from+"&toDate="+to);
jQuery(".cable_earned").load("<?php echo plugins_url("vtupress/admin/loader/cable.php");?>?fromDate="+from+"&toDate="+to);
jQuery(".bill_earned").load("<?php echo plugins_url("vtupress/admin/loader/bill.php");?>?fromDate="+from+"&toDate="+to);
jQuery(".total_earned").load("<?php echo plugins_url("vtupress/admin/loader/total.php");?>?fromDate="+from+"&toDate="+to);
*/
}

setDateFt();

queryDB(sendFromDate,sendToDate);

jQuery(".date_picker").on("change",function(){

var date_picker2 = jQuery(this).val();

setDateFt();

if(date_picker2 != "custom"){
  jQuery(".sales_first_load").html('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');



   // alert(sendFromDate+"\n"+sendToDate);
    queryDB(sendFromDate,sendToDate);
}

});


jQuery(".customQueryDb").on("click",function(){

  sendFromDate = jQuery(".from_date").val();
  sendToDate = jQuery(".to_date").val();

  //alert(sendFromDate+"\n"+sendToDate);

  jQuery(".sales_first_load").html('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');



   // alert(sendFromDate+"\n"+sendToDate);
    queryDB(sendFromDate,sendToDate);


});






});

function changestatus(type){
var obj = {}
if(jQuery("input."+type).is(":checked")){
  obj["set_status"] = "checked";
}
else{
  obj["set_status"] = "unchecked";
}
obj["set_control"] = type;
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";


  jQuery.ajax({
  url: "<?php echo esc_url(plugins_url('vtupress/controls.php'));?>",
  data: obj,
  dataType: "text",
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
  
        }   else if (jqXHR.status == 403) {
            msg = "Access Forbidden [403].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }  else if (jqXHR.status == 404) {
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
        if(data == "100" ){
	location.reload();
	  }
	  else{
		  
	jQuery(".preloader").hide();
	 swal({
  title: "Error",
  text: data,
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



                    <div class="col-lg-12">
                      <div class="row">
                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center">
                            <i class="mdi mdi-account fs-3 mb-1 font-16"></i><br>
                           <?php echo intval($allusers );?><br>
                            <small class="font-light">Total Users</small>
                          </div>
                        </div>
                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center">
                            <i class="fas fa-ban fs-3 mb-1 font-16"></i><br>
                            <?php echo intval($banedusers);?><br>
                            <small class="font-light">Banned Users</small>
                          </div>
                        </div>

                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center" title="User Hight Balance">
                            <i class="mdi mdi-wallet fs-3 mb-1 font-16"></i><br>
                           <?php echo intval($highestbal);?><br>
                            <small class="font-light">U.H Balance</small>
                          </div>
                        </div>
                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center" title="User With The Highest Balance">
                            <i class="fas fa-id-badge fs-3 mb-1 font-16"></i><br>
                            <?php
                              echo intval($highestbaluser[0]->ID);
                            ?><br>
                            <small class="font-light">User ID</small>
                          </div>
                        </div>

                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center">
                            <i class="fas fa-piggy-bank fs-3 mb-1 font-16"></i><br>
                            <?php echo $userswithfunds;?> <br>
                            <small class="font-light">With Funds</small>
                          </div>
                        </div>

                        <div class="col-6 mt-3">
                          <div class="bg-dark p-3 text-white text-center">
                            <i class="mdi mdi-delete-empty fs-3 mb-1 font-16"></i><br>
                            <?php echo $userswithoutfunds;?> <br>
                            <small class="font-light">Without Funds</small>
                          </div>
                        </div>
                      </div>

                    </div>
                    <!-- column -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Recent comment and chats -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- column -->
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">New Messages</h4>
                </div>
                <div class="comment-widgets scrollable">
                  <!-- Comment Row -->
                  <div class="d-flex flex-row comment-row mt-0">

<?php
                  foreach($messages as $message){
?>
                    <div class="p-2">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                      /> 
                </div>

<div class="comment-text w-100">
                      <h6 class="font-medium"><?php echo ucfirst($message->name);?></h6>
                      <span class="mb-3 d-block"
                        ><?php echo substr($message->message,0,30);?>
                      </span>
                      <div class="comment-footer">
                        <span class="text-muted float-end"><?php echo $message->the_time;?></span>
                       <a href="?page=vtupanel&adminpage=messages&user_id=<?php echo $message->user_id;?>"class="btn"> <button
                          type="button"
                          class="btn btn-cyan btn-sm text-white"
                        >
                          Read
                        </button></a>
                      </div>
  </div>

<?php
                  }
?>



                  </div>
                  <!-- Comment Row -->
                </div>
              </div>
              <!-- Card -->

              <!-- card -->
              <!-- card new HIDDEN USER NOTIFICATION -->
              <div class="card d-none">
                <div class="card-body">
                  <h4 class="card-title mb-0">Users Notifications</h4>
                </div>
                <ul class="list-style-none">
                  <li class="d-flex no-block card-body">
                    <i class="mdi mdi-check-circle fs-4 w-30px mt-1"></i>
                    <div>
                      <a href="#" class="mb-0 font-medium p-0"
                        >Lorem ipsum dolor sit amet, consectetur adipiscing
                        elit.</a
                      >
                      <span class="text-muted"
                        >dolor sit amet, consectetur adipiscing</span
                      >
                    </div>
                    <div class="ms-auto">
                      <div class="tetx-right">
                        <h5 class="text-muted mb-0">20</h5>
                        <span class="text-muted font-16">Jan</span>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex no-block card-body border-top">
                    <i class="mdi mdi-gift fs-4 w-30px mt-1"></i>
                    <div>
                      <a href="#" class="mb-0 font-medium p-0"
                        >Congratulation Maruti, Happy Birthday</a
                      >
                      <span class="text-muted"
                        >many many happy returns of the day</span
                      >
                    </div>
                    <div class="ms-auto">
                      <div class="tetx-right">
                        <h5 class="text-muted mb-0">11</h5>
                        <span class="text-muted font-16">Jan</span>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex no-block card-body border-top">
                    <i class="mdi mdi-plus fs-4 w-30px mt-1"></i>
                    <div>
                      <a href="#" class="mb-0 font-medium p-0"
                        >Maruti is a Responsive Admin theme</a
                      >
                      <span class="text-muted"
                        >But already everything was solved. It will ...</span
                      >
                    </div>
                    <div class="ms-auto">
                      <div class="tetx-right">
                        <h5 class="text-muted mb-0">19</h5>
                        <span class="text-muted font-16">Jan</span>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex no-block card-body border-top">
                    <i class="mdi mdi-leaf fs-4 w-30px mt-1"></i>
                    <div>
                      <a href="#" class="mb-0 font-medium p-0"
                        >Envato approved Maruti Admin template</a
                      >
                      <span class="text-muted"
                        >i am very happy to approved by TF</span
                      >
                    </div>
                    <div class="ms-auto">
                      <div class="tetx-right">
                        <h5 class="text-muted mb-0">20</h5>
                        <span class="text-muted font-16">Jan</span>
                      </div>
                    </div>
                  </li>
                  <li class="d-flex no-block card-body border-top">
                    <i
                      class="mdi mdi-comment-question-outline fs-4 w-30px mt-1"
                    ></i>
                    <div>
                      <a href="#" class="mb-0 font-medium p-0">
                        I am alwayse here if you have any question</a
                      >
                      <span class="text-muted"
                        >we glad that you choose our template</span
                      >
                    </div>
                    <div class="ms-auto">
                      <div class="tetx-right">
                        <h5 class="text-muted mb-0">15</h5>
                        <span class="text-muted font-16">Jan</span>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <!-- column -->
              <!-- card new HIDDEN CHATs -->
            <div class="col-lg-6 d-none">
              <!-- Card -->
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Chat Option</h4>
                  <div class="chat-box scrollable" style="height: 475px">
                    <!--chat Row -->
                    <ul class="chat-list">

                      <!--chat Row  RECEIVED-->
                      <li class="chat-item d-none">
                        <div class="chat-img">
                          <img src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg" alt="user" />
                        </div>
                        <div class="chat-content">
                          <h6 class="font-medium">James Anderson</h6>
                          <div class="box bg-light-info">
                            Lorem Ipsum is simply dummy text of the printing
                            &amp; type setting industry.
                          </div>
                        </div>
                        <div class="chat-time">10:56 am</div>
                      </li>
                      <!--chat Row SEND-->
                      <li class="odd chat-item d-none">
                        <div class="chat-content">
                          <div class="box bg-light-inverse">
                            I would love to join the team.
                          </div>
                          <br />
                        </div>
                      </li>
                    <?php
                        

                    ?>


                    </ul>
                  </div>
                </div>
                <div class="card-body border-top">
                  <div class="row">
                    <div class="col-9">
                      <div class="input-field mt-0 mb-0">
                        <textarea
                          id="textarea1"
                          placeholder="Type and enter"
                          class="form-control border-0"
                        ></textarea>
                      </div>
                    </div>
                    <div class="col-3">
                      <a
                        class="btn-circle btn-lg btn-cyan float-end text-white"
                        href="javascript:void(0)"
                        ><i class="mdi mdi-send fs-3"></i
                      ></a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- card  HIDDEN NEED HELP?-->
              <div class="card d-none">
                <div class="card-body">
                  <h4 class="card-title">Need Help?</h4>
                </div>
                <div
                  class="comment-widgets scrollable"
                  style="max-height: 130px"
                >
                  <!-- Comment Row -->
                  <div class="d-flex flex-row comment-row mt-0">
                    <div class="p-2">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/1.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                      />
                    </div>
                    <div class="comment-text w-100">
                      <h6 class="font-medium">VTUPRESS MESSAGE!</h6>
                      <span class="mb-3 d-block"
                        >
                        Should you have issues with this plugin? kindly Join Our Telegram Group!!!
                      </span>
                      <div class="comment-footer">
                        <span class="text-muted float-end">C.E.O Akor Victor</span>
                        <button
                          type="button"
                          class="btn btn-cyan btn-sm text-white"
                        >
                          Edit
                        </button>
                        <button
                          type="button"
                          class="btn btn-success btn-sm text-white"
                        >
                          Publish
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm text-white"
                        >
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- Comment Row -->
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/4.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                      />
                    </div>
                    <div class="comment-text active w-100">
                      <h6 class="font-medium">Michael Jorden</h6>
                      <span class="mb-3 d-block"
                        >Lorem Ipsum is simply dummy text of the printing and
                        type setting industry.
                      </span>
                      <div class="comment-footer">
                        <span class="text-muted float-end">May 10, 2021</span>
                        <button
                          type="button"
                          class="btn btn-cyan btn-sm text-white"
                        >
                          Edit
                        </button>
                        <button
                          type="button"
                          class="btn btn-success btn-sm text-white"
                        >
                          Publish
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm text-white"
                        >
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>
                  <!-- Comment Row -->
                  <div class="d-flex flex-row comment-row">
                    <div class="p-2">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/users/5.jpg"
                        alt="user"
                        width="50"
                        class="rounded-circle"
                      />
                    </div>
                    <div class="comment-text w-100">
                      <h6 class="font-medium">Johnathan Doeting</h6>
                      <span class="mb-3 d-block"
                        >Lorem Ipsum is simply dummy text of the printing and
                        type setting industry.
                      </span>
                      <div class="comment-footer">
                        <span class="text-muted float-end">August 1, 2021</span>
                        <button
                          type="button"
                          class="btn btn-cyan btn-sm text-white"
                        >
                          Edit
                        </button>
                        <button
                          type="button"
                          class="btn btn-success btn-sm text-white"
                        >
                          Publish
                        </button>
                        <button
                          type="button"
                          class="btn btn-danger btn-sm text-white"
                        >
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- accoridan part -->

              <!-- toggle part -->

              <!-- Tabs HIDDEN TABS1 - 3-->
              <div class="card d-none">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a
                      class="nav-link active"
                      data-bs-toggle="tab"
                      href="#home"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Tab1</span></a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#profile"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Tab2</span></a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#messages"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down">Tab3</span></a
                    >
                  </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabcontent-border">
                  <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="p-20">
                      <p>
                        And is full of waffle to It has multiple paragraphs and
                        is full of waffle to pad out the comment. Usually, you
                        just wish these sorts of comments would come to an
                        end.multiple paragraphs and is full of waffle to pad out
                        the comment..
                      </p>
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/background/img4.jpg"
                        class="img-fluid"
                      />
                    </div>
                  </div>
                  <div class="tab-pane p-20" id="profile" role="tabpanel">
                    <div class="p-20">
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/background/img4.jpg"
                        class="img-fluid"
                      />
                      <p class="mt-2">
                        And is full of waffle to It has multiple paragraphs and
                        is full of waffle to pad out the comment. Usually, you
                        just wish these sorts of comments would come to an
                        end.multiple paragraphs and is full of waffle to pad out
                        the comment..
                      </p>
                    </div>
                  </div>
                  <div class="tab-pane p-20" id="messages" role="tabpanel">
                    <div class="p-20">
                      <p>
                        And is full of waffle to It has multiple paragraphs and
                        is full of waffle to pad out the comment. Usually, you
                        just wish these sorts of comments would come to an
                        end.multiple paragraphs and is full of waffle to pad out
                        the comment..
                      </p>
                      <img
                        src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/images/background/img4.jpg"
                        class="img-fluid"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <!-- ============================================================== -->
          <!-- Recent comment and chats -->
          <!-- ============================================================== -->


    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/excanvas.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/jquery.flot.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/jquery.flot.pie.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/jquery.flot.time.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/jquery.flot.stack.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot/jquery.flot.crosshair.js"></script>
    <script src="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>

    <script type="text/javascript">
 var data = [     <?php
foreach($highestbaluser as $usar){
$id = $usar->ID;
$bal = $usar->vp_bal;
echo "[$id, $bal],";
}

?>
];
       // , [2, 40], [3, 80], [4, 160], [5, 159], [6, 370], [7, 330], [8, 350], [9, 370], [10, 400], [11, 330], [12, 350]
 
        var dataset = [{label: "line1",data: data}];
 
        var options = {
            series: {
                lines: { show: true },
                points: {
                    radius: 3,
                    show: true
                }
            }
        };
 
        jQuery(document).ready(function () {
            jQuery.plot(jQuery("#flot-placeholder"), dataset, options);
        });
    </script>

</div>