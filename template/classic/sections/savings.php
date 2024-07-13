<?php	
if(isset($_GET["vend"]) && $_GET["vend"]=="savings" && vp_option_array($option_array,"vtupress_custom_isavings") == "yes"){
    $id = get_current_user_id();
    $bal = intval(vp_getuser($id, 'vp_bal', true));
?>

<style>

.savings-div{
    border-radius: 10px;
}
.daily-savings.savings-div,.fixed-savings.savings-div{
    /*background: linear-gradient(90deg,#800080e8 30%,#800080  70%);*/
    background-color: #4c00b0;
    position:relative;
}

.savings-type{
    position:absolute;
    top:0;
    left:0;
    padding:10px;
    color:white;
    font-weight:bold;
}


h2.amount {
    font-size: 40px;
}
.actbtn{
    background-color: #03254c;
    font-weight:bold;
}
.notice{
    font-size: 20px;
}

.notices ul li{
    font-size:15px;
    font-weight:bold;
    display:block;
    margin:10px 0;
}


p {
    margin-top: 0 !important;
    margin-bottom: 1rem !important;
}

.btn-primary {
    background-color: var(--bs-primary) !important;
}

.btn-secondary {
    background-color: var(--bs-secondary) !important;
}

.btn-success {
    background-color: var(--bs-success) !important;
}
.btn-warning {
    background-color: var(--bs-warning) !important;
}

.btn-info {
    background-color: var(--bs-info) !important;
}

.btn-danger {
    background-color: var(--bs-danger) !important;
}
</style>

<?php

global $wpdb;
$table = $wpdb->prefix."vp_savings";
$daily_amount = intval($wpdb->get_var("SELECT SUM(amount_saved) FROM $table WHERE user_id = '$id' AND type = 'daily' AND status = 'active'"));
$fixed_amount = intval($wpdb->get_var("SELECT SUM(amount_saved) FROM $table WHERE user_id = '$id' AND type = 'fixed' AND status = 'active'"));





?>

<div class="container">
    <div class="mt-3 row px-md-4">
        <div class="col">
            <div class="alert alert-primary d-flex justify-content-center align-items-center p-2">
                <h3>Keep your money safe with us !</h3>

            </div>

        </div>


    </div>
    <div class="row px-md-4">
        <div class="col-12 col-md-6">

            <div class="daily-savings mt-2 mx-2 mx-md-1 savings-div d-flex flex-column justify-content-between align-items-center">
                <span class="savings-type">Daily</span>
                <span class="savings-amount p-4 text-white d-flex">₦<h2 class="amount fw-bold"><?php echo $daily_amount;?></h2></span>

                <div class="w-100 savings-control ">

                    <div class="inactive-savings d-flex justify-content-around align-items-center px-2 py-3">
                        <button class="btn actbtn addSavings text-white "   data-bs-toggle="modal" data-bs-target="#selectdailyInfoModal"><i class="fas fa-piggy-bank me-2"></i>Add Savings</button>
                        <button class="btn actbtn info text-white" data-bs-toggle="modal" data-bs-target="#dailyInfoModal"><i class="fas fa-info me-2"></i>Info</button>
                    </div>

                    <div class="active-savings d-flex justify-content-around align-items-center d-none"></div>
                </div>

            </div>


            <div class="fixed-savings mt-2  mx-2 mx-md-1 savings-div d-flex flex-column justify-content-between align-items-center">
                    <span class="savings-type">Fixed</span>

                    <span class="savings-amount text-white p-4 d-flex"><span>₦</span><h2 class="amount fw-bold"><?php echo $fixed_amount;?></h2></span>

                    <div class="w-100 savings-control ">

                        <div class="inactive-savings d-flex justify-content-around align-items-center px-2 py-3">
                            <button class="btn  actbtn addSavings text-white"   data-bs-toggle="modal" data-bs-target="#selectFixedInfoModal"><i class="fas fa-piggy-bank me-2"></i>Add Savings</button>
                            <button class="btn  actbtn info text-white"  data-bs-toggle="modal" data-bs-target="#fixedInfoModal"><i class="fas fa-info me-2"></i>Info</button>
                        </div>

                        <div class="active-savings d-flex justify-content-around align-items-center d-none"></div>
                    </div>

            </div>

        </div>
        <div class="col-12 col-md-6 d-none d-md-block">

            <div class="  mt-2">
                <div class="alert alert-secondary">
                    <span class="fw-bold notice"> <i class="fas fa-info-circle me-2"></i>Important Notice</span>
                </div>
                <div class="notices p-3 bg-white">
                    <ul>
                        <li>Funds from your main wallet are withdrawn automatically to your savings account</li>
                        <li>Please make sure you have enough funds on your wallet to continue steady savings</li>
                        <li>Savings are to be withdrawn at least on/after the due date</li>
                        <li>Savings are to be your main wallet which can then be withdrawn to your bank</li>
                    </ul>


                </div>
                

            </div>

        </div>
        <div class="my-savings mt-3">
            <div class="row  px-4 ">
                <div class="col-12 bg-white">
                    <span class="my-3 d-block" style="font-weight:bold; font-size:20px;"><i class="fas fa-piggy-bank me-2"></i>Savings</span>
                </div>
                <div class="col-12 bg-white table-responsive">
                    <table class=" table   table-stripe table-hover datatables">
                        <thead >
                            <tr>
                                <th>Type</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Saving Interval</th>
                                <th>Next Saving Day</th>
                                <th>Count</th>
                                <th>Saved</th>
                                <th>Interest</th>
                                <th>Applicable on</th>
                                <th>Applied</th>
                                <th>Automatic</th>
                                <th>Started At</th>
                                <th>Ends On</th>
                                <th>Withdraw On</th>
                                <th>Liquidated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                global $wpdb;

                                $table = $wpdb->prefix."vp_savings";
                                $result = $wpdb->get_results("SELECT * FROM $table WHERE user_id = '$id' ORDER BY ID DESC");
                                
                                if(empty($result)){

                                }else{

                                    $return = "";
                                    foreach($result as $thisSavings){
                                        $id = $thisSavings->id;
                                        $type = $thisSavings->type;
                                        $status = $thisSavings->status;
                                        $duration = $thisSavings->duration;
                                        $interval = $thisSavings->save_interval;
                                        $amt = $thisSavings->start_amount;

                                        if($interval == "day"){
                                            $interval = "daily";
                                        }
                                        elseif( $interval == "week"){
                                            $interval = "weekly";
                                        }
                                        elseif( $interval == "month"){
                                            $interval = "monthly";
                                        }
                                        $saves = $amt." ".$interval;
                                        $next = $thisSavings->next_save;
                                        $count = $thisSavings->save_count;
                                        $saved = $thisSavings->amount_saved;
                                        $interest = $thisSavings->interest;
                                        $applicable_on = $thisSavings->applicable_on;
                                        $automatic = $thisSavings->automatic;
                                        $started = $thisSavings->started_at;
                                        $ends = $thisSavings->ends_at;
                                        $withon = strtotime($ends." +1 day");
                                        $liquidated = $thisSavings->liquidated;
                                        $applied = $thisSavings->interest_applied;

                                        $today = date("Y-m-d");

                                        if($status == "pending-withdrawal"){
                                            $opt = "<span class='bg rounded border bg-warning p-3 text-white'>Pending</span>";
                                        }
                                        elseif($status != "active"){
                                            $opt = "<span class='bg rounded border bg-info p-3 text-white'>$status</span>";
                                        }
                                        else{

                                            if(strtotime($today) <= strtotime($ends)){
                                                $opt = "<button id='$id' class='btn btn-danger text-white withdraw-$type' for='$type' action='liquidate'>Liquidate</button>";
                                            }
                                            else{
                                                $opt = "<button id='$id' class='btn btn-success text-white withdraw-$type' for-'$type' action='withdraw'>Withdraw</button>";
                                            }

                                        }





                                        $return .= "

                                            <tr>
                                                <td>$type</td>
                                                <td>$duration</td>
                                                <td>$status</td>
                                                <td>$saves</td>
                                                <td>$next</td>
                                                <td>$count</td>
                                                <td>$saved</td>
                                                <td>$interest</td>
                                                <td>$applicable_on</td>
                                                <td>$applied</td>
                                                <td>$automatic</td>
                                                <td>$started</td>
                                                <td>$ends</td>
                                                <td>$withon</td>
                                                <td>$liquidated</td>
                                                <td class='d-flex align-items-center'>$opt</td>
                                            </tr>

                                        ";
                                    }

                                }
                                ?>
                                <?php echo $return;?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
 
    </div>

                <!-- Modal DAILY-->
        <div class="modal fade" id="dailyInfoModal" tabindex="-1" aria-labelledby="dailyInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dailyInfoModalLabel">Daily Savings Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sign Up Fee is charged from your wallet balance</p>
                    <p>Savings are either debited automatically or manually trnasfered from your main wallet to your savings wallet</p>
                    <p>An interest is applied to your savings and can be seen on the daily savings info when you want to apply</p>
                    <p>Referer can earn as well</p>
                    <p>Charges are updated and can be seen on the savings modal when you click on add savings</p>
                    <p>We wont charge in a month you did not save</p>
                    <p>Click add savings to get started</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save changes</button>
                </div>
                </div>
            </div>
        </div>

                <!-- Modal FIXED-->
        <div class="modal fade" id="fixedInfoModal" tabindex="-1" aria-labelledby="fixedInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fixedInfoModalLabel">Fixed Savings Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Sign Up Fee can be seen on fixed saving modal when you click on add savings</p>
                    <p>Savings are either debited automatically or manually trnasfered from your main wallet to your savings wallet</p>
                    <p>Interest differs based on plan</p>
                    <p>Charge differs based on plan</p>
                    <p>Referer is liable to earn</p>
                    <p>You can save any amount daily, weekly or monthly</p>
                    <p>You can select a plan, either 3months, 6months, 12months or more depending on whats available and your choice</p>
                    <p>Withdrawal of fixed savings before maturity date attracts penalty. Interest forfeited and a percentage is deducted off funds depending on plan</p>
                    <p>Withdrawal is manual. We verify and make immediate transfers</p>
                    <p>Click add savings to see plan</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary d-none">Save changes</button>
                </div>
                </div>
            </div>
        </div>




        <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "vp_daily_savings_settings";
            $exists = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'on' ");
            $return = "";
            $infotxt = "";
            $block = "d-none";

            if (empty($exists)) {
                $return = "
                    <div class='alert alert-info'>
                        <h4>No Plan Available For Now</h4>
                    </div>
                ";
            } else {
                $block = "";
                $exist = $exists[0];
                $minimum_amount = $exist->minimum_amount;
                $return .= "
                    <div class='daily-plan-amount my-3'>
                        <div class='input-group'>
                            <span class='input-group-text'>Save ₦</span>
                            <input type='number' class='daily-save-amount form-control' value='$minimum_amount'>
                            <span class='input-group-text '>Every Day</span>
                        </div>
                    </div>
                ";

                $infotxt = "";
                $info = $exist;
                $id = intval($info->id);
                $sign_up_fee = intval($info->sign_up_fee);
                $cut_type = $info->cut_type;
                $cut = $info->cut;

                if ($cut_type == "percentage") {
                    $cut_type = "%";
                }

                $minimum_amount = intval($info->minimum_amount);
                $interest = intval($info->interest);
                $referer_commission = intval($info->referer_commission);

                $infotxt .= "
                    <div class='my-3 accordion accordion-flush' id='accordionFlushExample'>
                        <div class='accordion-item'>
                            <h2 class='accordion-header'>
                                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-collapseOne' aria-expanded='false' aria-controls='flush-collapseOne'>
                                    Daily Saving Info
                                </button>
                            </h2>
                            <div id='flush-collapseOne' class='accordion-collapse collapse' data-bs-parent='#accordionFlushExample'>
                                <div class='accordion-body'>
                                    <div class='p-3 mb-2 daily-plan-info daily-info-$id'>
                                        <p>Sign Up Fee: $sign_up_fee</p>
                                        <p>Minimum Amount: $minimum_amount</p>
                                        <p>Interest: $interest%</p>
                                        <p>Your Referer Will Get: $referer_commission</p>
                                        <p>Charge is just $cut$cut_type of your total savings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='form-check my-3 px-3'>
                        <input class='form-check-input automatic' type='checkbox' value='' id='flexCheckChecked' checked>
                        <label class='form-check-label' for='flexCheckChecked'>
                            Automatically deduct ₦<span class='daily-deductAmount'>$minimum_amount</span> every day
                        </label>
                    </div>
                ";

                $infotxt .= "
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        jQuery('.daily-save-amount').on('input', function() {
                            var amount = parseInt(jQuery(this).val());
                            jQuery('.daily-deductAmount').text(amount);
                        });

                        jQuery('.saveNowDaily').on('click', function() {
                            var planid = 'daily';
                            var saveAmount = jQuery('.daily-save-amount').val();
                            var saveDuration = 'daily';
                            var checkAutomatic = jQuery('.automatic').is(':checked');
                            var automatic = 'no';
                            var minimum = parseInt($minimum_amount);
                            var sign = parseInt($sign_up_fee);

                            if (checkAutomatic) {
                                automatic = 'yes';
                            } else {
                                automatic = 'no';
                            }

                            if (planid == '' || saveAmount == '' || saveDuration == '') {
                                showToast('All fields are required', 'red', 5000);
                                return;
                            } else if (parseInt($bal) < saveAmount) {
                                showToast('Your balance is lower than what you wanna save ₦' + saveAmount, 'red', 5000);
                                return;
                            } else if (parseInt($bal) < sign) {
                                showToast('Your balance is lower than the signup fee ₦' + sign, 'red', 5000);
                                return;
                            } else if (parseInt($bal) < minimum) {
                                showToast('Your balance is lower than minimum balance of ₦' + minimum, 'red', 5000);
                                return;
                            } else if (saveAmount < minimum) {
                                showToast('You can\'t save below the minimum amount of ₦' + minimum, 'red', 5000);
                                return;
                            } else {
                                showToast('Processing... ', 'yellow', 5000);
                            }

                            var obj = {};
                            obj['daily-planid'] = planid;
                            obj['saveAmount'] = saveAmount;
                            obj['saveDuration'] = saveDuration;
                            obj['automatic'] = automatic;

                            jQuery.ajax({
                                url: '/wp-content/plugins/vtupress/admin/pages/isavings/process.php',
                                data: obj,
                                dataType: 'text',
                                cache: false,
                                async: true,
                                error: function(jqXHR, exception) {
                                    jQuery('.registerNow').text('Register');
                                    if (jqXHR.status === 0) {
                                        msg = 'No Connection. Verify Network.';
                                    } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.' + jqXHR.responseText;
                                    }
                                    showToast(msg, 'red', 5000);
                                },
                                success: function(data) {
                                    var color;
                                    if (data == '100' || data == 100) {
                                        msg = 'success';
                                        color = 'green';
                                            showToast(msg, color, 5000);
                                            setTimeout(function() {
                                                location.reload();
                                            }, 1000);
                                    } else {
                                        msg = data;
                                        color = 'red';
                                         showToast(msg, color, 5000);
                                    }

                                },
                                type: 'POST'
                            });
                        });

                        jQuery('.withdraw-daily').on('click', function() {
                            var obj = {};
                            var action = jQuery(this).attr('action');
                            var savingid = jQuery(this).attr('id');
                            obj['savingid'] = savingid;
                            obj['userAction'] = action;


                            if(action == 'liquidate'){

                                if(!confirm('Do you want to liquidate this savings. Action is irreversible')){
                                    return;
                                }
                            }else{
                                if(!confirm('Do you want to withdraw this savings. Action is irreversible')){
                                    return;
                                }

                            }

                            showToast('Processing...','yellow',5000);
                            jQuery.ajax({
                                url: '/wp-content/plugins/vtupress/admin/pages/isavings/process.php',
                                data: obj,
                                dataType: 'text',
                                cache: false,
                                async: true,
                                error: function(jqXHR, exception) {
                                    jQuery('.registerNow').text('Register');
                                    if (jqXHR.status === 0) {
                                        msg = 'No Connection. Verify Network.';
                                    } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.' + jqXHR.responseText;
                                    }
                                    showToast(msg, 'red', 5000);
                                },
                                success: function(data) {
                                    var color;
                                    if (data == '100' || data == 100) {
                                        msg = 'success';
                                        color = 'green';
                                        showToast(msg, color, 5000);
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);
                                    } else {
                                        msg = data;
                                        color = 'red';
                                        showToast(msg, color, 5000);

                                    }

                                },
                                type: 'POST'
                            });
                        });
                    });
                    </script>
                ";
            }
        ?>

        <!-- Select DAILY-->
        <div class="modal fade" id="selectdailyInfoModal" tabindex="-1" aria-labelledby="selectdailyInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectdailyInfoModalLabel">Initiate Your Daily Saving Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $return; ?>
                        <?php echo $infotxt; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary saveNowDaily <?php echo $block; ?>">Start Saving</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "vp_fixed_savings_settings";
            $exist = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'on' ");
            $return = "";
            $infotxt = "";
            $block = "d-none";

            if (empty($exist)) {
                $return = "
                    <div class='alert alert-info'>
                        <h4>No Plan Available For Now</h4>
                    </div>
                ";
            } else {
                $block = "";
                $return .= "<select class='form-control plan-choice' for='select'>";
                foreach ($exist as $plan) {
                    $pid = intval($plan->id);
                    $duration = $plan->duration;
                    $minimum_amount = intval($plan->minimum_amount);
                    $interest = intval($plan->interest);
                    $cut_type = $plan->cut_type;
                    $sign_up_fee = intval($plan->sign_up_fee);
                    $cut = $plan->cut;

                    $return .= "
                        <option value=''>--Select Plan --</option>
                        <option value='$pid' sign='$sign_up_fee' minimum='$minimum_amount' interest='$interest' charge='$cut' charge-type='$cut_type'>$duration - [ ₦$minimum_amount ] Minimum</option>
                    ";
                }
                $return .= "</select>
                    <div class='plan-amount my-3'>
                        <div class='input-group'>
                            <span class='input-group-text'>Save ₦</span>
                            <input type='number' class='save-amount form-control' value='$minimum_amount'>
                            <span class='input-group-text '>Every</span>
                            <select class='save-duration form-control'>
                                <option class='day'>Day</option>
                                <option class='week'>Week</option>
                                <option class='month'>Month</option>
                            </select>
                        </div>
                    </div>
                ";

                foreach ($exist as $info) {
                    $id = intval($info->id);
                    $sign_up_fee = intval($info->sign_up_fee);
                    $duration = $info->duration;
                    $cut_type = $info->cut_type;
                    $cut = $info->cut;
                    $cut_type = ($cut_type == "percentage") ? "%" : $cut_type;
                    $minimum_amount = intval($info->minimum_amount);
                    $interest = intval($info->interest);
                    $referer_commission = intval($info->referer_commission);
                    $applicable_after = $info->applicable_after;
                    $recurrent_after_first = $info->recurrent_after_first;
                    $penalty = intval($info->penalty);

                    $infotxt .= "
                        <div class='my-3 accordion accordion-flush' id='accordionFlushExample'>
                            <div class='accordion-item'>
                                <h2 class='accordion-header'>
                                    <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#flush-collapseOne' aria-expanded='false' aria-controls='flush-collapseOne'>
                                        Saving Info
                                    </button>
                                </h2>
                                <div id='flush-collapseOne' class='accordion-collapse collapse' data-bs-parent='#accordionFlushExample'>
                                    <div class='accordion-body'>
                                        <div class='p-3 mb-2 d-none plan-info info-$id'>
                                            <p>Sign Up Fee: $sign_up_fee</p>
                                            <p>Minimum Amount: $minimum_amount</p>
                                            <p>Interest: $interest%</p>
                                            <p>Your Referer Will Get: $referer_commission</p>
                                            <p>Your interest of $interest % will be added in $applicable_after</p>
                                            <p>Will you get same interest recurrently @ same duration? [$recurrent_after_first !]</p>
                                            <p>We will penalize you with a charge of $penalty % from your principal if you liquidate (withdraw before plan ends) your account and no interest will be added</p>
                                            <p>Charge is just $cut$cut_type of your total savings</p>
                                            <span class='cal fw-bold fs-4 my-2'>e.g You will get <b class='price'>₦0</b> in 1 Day @ saving amount + $interest% - $cut$cut_type if you save daily and interest is to me applied everyday</span>
                                            <p>Edit the amount you wanna save to see calculated changes example</p>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class='form-check my-3 px-3'>
                            <input class='form-check-input automatic' type='checkbox' value='' id='flexCheckChecked' checked>
                            <label class='form-check-label' for='flexCheckChecked'>
                                Automatically deduct ₦<span class='deductAmount'>$minimum_amount</span> every <span class='deductDay'>day</span>
                            </label>
                        </div>
                    ";
                }

                $infotxt .= "
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        jQuery('.plan-choice').on('change', function() {
                            var valId = jQuery(this).val();
                            jQuery('.plan-info').addClass('d-none');
                            jQuery('.info-' + valId).removeClass('d-none');
                        });

                        jQuery('.save-duration').on('change', function() {
                            var day = jQuery(this).val();
                            jQuery('.deductDay').text(day.toLowerCase());
                        });

                        jQuery('.plan-choice').change();

                        jQuery('.save-amount').on('input', function() {
                            var amount = parseInt(jQuery(this).val());

                            if (jQuery('.plan-choice').val() == '') {
                                showToast('Please choose plan', 'red', 5000);
                                return;
                            } else if (amount < 1) {
                                jQuery('.price').text(0);
                                return;
                            }

                            jQuery('.deductAmount').text(amount);
                            var interest = (parseInt(jQuery('.plan-choice option:selected').attr('interest')) * amount) / 100;

                            var charge = jQuery('.plan-choice option:selected').attr('charge');
                            var charge_type = jQuery('.plan-choice option:selected').attr('charge-type');

                            var totalGain = amount + interest;

                            if (charge_type == 'percentage') {
                                var remove = (parseInt(charge) * totalGain) / 100;
                                totalGain -= remove;
                            }

                            jQuery('.price').text(totalGain);
                        });

                        jQuery('.saveNow').on('click', function() {
                            var planid = jQuery('.plan-choice').val();
                            var saveAmount = jQuery('.save-amount').val();
                            var saveDuration = jQuery('.save-duration').val();
                            var checkAutomatic = jQuery('.automatic').is(':checked');
                            var automatic = 'no';
                            var minimum = parseInt(jQuery('.plan-choice option:selected').attr('minimum'));
                            var sign = parseInt(jQuery('.plan-choice option:selected').attr('sign'));

                            if (checkAutomatic) {
                                automatic = 'yes';
                            } else {
                                automatic = 'no';
                            }

                            if (planid == '' || saveAmount == '' || saveDuration == '') {
                                showToast('All fields are required', 'red', 5000);
                                return;
                            } else if (parseInt($bal) < saveAmount) {
                                showToast('Your balance is lower than what you wanna save ₦' + saveAmount, 'red', 5000);
                                return;
                            } else if (parseInt($bal) < sign) {
                                showToast('Your balance is lower than the signup fee ₦' + sign, 'red', 5000);
                                return;
                            } else if (parseInt($bal) < minimum) {
                                showToast('Your balance is lower than minimum balance of ₦' + minimum, 'red', 5000);
                                return;
                            } else if (saveAmount < minimum) {
                                showToast('You can\'t save below the minimum amount of ₦' + minimum, 'red', 5000);
                                return;
                            } else {
                                showToast('Processing... ', 'yellow', 5000);
                            }

                            var obj = {};
                            obj['planid'] = planid;
                            obj['saveAmount'] = saveAmount;
                            obj['saveDuration'] = saveDuration;
                            obj['automatic'] = automatic;

                            jQuery.ajax({
                                url: '/wp-content/plugins/vtupress/admin/pages/isavings/process.php',
                                data: obj,
                                dataType: 'text',
                                cache: false,
                                async: true,
                                error: function(jqXHR, exception) {
                                    jQuery('.registerNow').text('Register');
                                    if (jqXHR.status === 0) {
                                        msg = 'No Connection. Verify Network.';
                                    } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.' + jqXHR.responseText;
                                    }
                                    showToast(msg, 'red', 5000);
                                },
                                success: function(data) {
                                    var color;
                                    if (data == '100' || data == 100) {
                                        msg = 'success';
                                        color = 'green';
                                        
                                        showToast(msg, color, 5000);
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);

                                    } else {
                                        msg = data;
                                        color = 'red';
                                        showToast(msg, color, 5000);
                                    }
                                },
                                type: 'POST'
                            });
                        });

                        jQuery('.withdraw-fixed').on('click', function() {
                            var obj = {};
                            var action = jQuery(this).attr('action');
                            var savingid = jQuery(this).attr('id');

                            obj['savingid'] = savingid;
                            obj['userAction'] = action;

                            
                            if(action == 'liquidate'){

                                if(!confirm('Do you want to liquidate this savings. Action is irreversible')){
                                    return;
                                }
                            }else{
                                if(!confirm('Do you want to withdraw this savings. Action is irreversible')){
                                    return;
                                }

                            }

                            showToast('Processing..','yellow',5000);

                            jQuery.ajax({
                                url: '/wp-content/plugins/vtupress/admin/pages/isavings/process.php',
                                data: obj,
                                dataType: 'text',
                                cache: false,
                                async: true,
                                error: function(jqXHR, exception) {
                                    jQuery('.registerNow').text('Register');
                                    if (jqXHR.status === 0) {
                                        msg = 'No Connection. Verify Network.';
                                    } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                    } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                    } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                    } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                    } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                    } else {
                                        msg = 'Uncaught Error.' + jqXHR.responseText;
                                    }
                                    showToast(msg, 'red', 5000);
                                },
                                success: function(data) {
                                    var color;
                                    if (data == '100' || data == 100) {
                                        msg = 'success';
                                        color = 'green';
                                        showToast(msg, color, 5000);
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);
                                    } else {
                                        msg = data;
                                        color = 'red';
                                        showToast(msg, color, 5000);
                                    }

                                },
                                type: 'POST'
                            });
                        });
                    });
                    </script>
                ";
            }
        ?>
        <!-- Select FIXED-->
        <div class="modal fade" id="selectFixedInfoModal" tabindex="-1" aria-labelledby="selectFixedInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectFixedInfoModalLabel">Initiate A Saving Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="select">Select Plan</label>
                        <?php echo $return; ?>
                        <?php echo $infotxt; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary saveNow <?php echo $block; ?>">Start Saving</button>
                    </div>
                </div>
            </div>
        </div>






</div>


<?php }?>