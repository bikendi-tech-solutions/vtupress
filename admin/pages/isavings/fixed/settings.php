
<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.
</p>

    <style>
        .ssettings{
            border-radius:5px;
        }
        .cursor{
            cursor:pointer;
        }

    </style>
<div class="container-fluid settings-container">

    <div class="alert my-3 alert-primary d-flex flex-column align-items-center justify-items-center">
        <h3>Welcome to iSavings Settings Page</h3>
        <h5>Save yourself from running at lost! Kindly do your paper works well before starting an saving/investment system<h5>
    </div>

    <div class="row">
        <div class="col savings-div">

            <?php 
                global $wpdb;
                $table_name = $wpdb->prefix.'vp_fixed_savings_settings';
                $results = $wpdb->get_results("SELECT * FROM $table_name ");

               if(empty($results)){
            ?>
                <div class="alert alert-info justify-content-center align-items-center">
                    <button class="btn btn-info text-white">Generate DB</button>
                </div>
            <?php }else{
            ?>
            <div class="dtable d-flex justify-content-center bg-white p-2 mb-2 table-responsive">
            <table class="table table-hover datatables table-responsive">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Duration</th>
                                <th>SignUp</th>
                                <th>Applicable After</th>
                                <th>Recurrent</th>
                                <th>Charge Figure</th>
                                <th>Charge Type</th>
                                <th>Minimum Save</th>
                                <th>Interest</th>
                                <th>Status</th>
                                <th>Ref.Comm</th>
                                <th>Penalty</th>
                                <th>Action</th>
                            <tr>
                        </thead>
                        <tbody>

                                <?php
                                $num = 0;

                                    foreach($results as $result):
                                        $num += 1;
                                        $id = $result->id;
                                        $sign_up_fee = $result->sign_up_fee;
                                        $duration = $result->duration;
                                        $applicable_after = $result->applicable_after;
                                        $recurrent_after_first = $result->recurrent_after_first;
                                        $cut = $result->cut;
                                        $cut_type = $result->cut_type;
                                        $minimum_amount = $result->minimum_amount;
                                        $interest = $result->interest;
                                        $status = $result->status;
                                        $referer_commission = $result->referer_commission;
                                        $penalty = $result->penalty;

                                        ?>
                                        <tr id="<?php echo $id;?>">
                                            <td><?php echo $num;?></td>
                                            <td for="sign_up_fee"><?php echo $sign_up_fee;?></td>
                                            <td for="duration"><?php echo $duration;?></td>
                                            <td for="applicable_after"><?php echo $applicable_after;?></td>
                                            <td for="recurrent_after_first"><?php echo $recurrent_after_first;?></td>
                                            <td for="cut"><?php echo $cut;?></td>
                                            <td for="cut_type"><?php echo $cut_type;?></td>
                                            <td for="minimum_amount"><?php echo $minimum_amount;?></td>
                                            <td for="interest"><?php echo $interest;?></td>
                                            <td for="status"><?php echo $status;?></td>
                                            <td for="referer_commission"><?php echo $referer_commission;?></td>
                                            <td for="penalty"><?php echo $penalty;?></td>
                                            <td><button class="edit btn-warning" id="<?php echo $id;?>">Edit</button></td>

                                        <?php
                                    endforeach;
                                ?>
                        </tbody>
                </table>
            </div>

            <script>
                jQuery(".edit").on("click",function(){
                    var forId = jQuery(this).attr("id");

                    jQuery("tr#"+forId+" td[for]").each(function(){
                        var forId = jQuery(this).attr("for");
                        var word = jQuery(this).text();

                        jQuery("#"+forId).val(word);

                    });

                        $('html, body').animate({
                            scrollTop: $('.savingsForm').offset().top
                        }, 1000);

                });

            </script>


                <div class="card">
                    <div class="card-head px-4 pt-3">
                        <h4>Settings</h4>
                    </div>
                    <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-md-7 ssettings border rounded rounded-3">

                                <div class="py-3 px-2 savingsForm">
                                        <label for="sign_up_fee mt-2">SignUp Fee <?php echo $symbol;?></label>
                                        <input class="form-control" id="sign_up_fee" value="">


                                        <label for="minimum_amount mt-2">Minimum Saving Amount <?php echo $symbol;?></label>
                                        <input class="form-control" id="minimum_amount"  value="">
                                        
                                        <label for="duration mt-2">Duration </label>
                                        <input class="form-control" id="duration" value="">


                                        <label for="interest mt-2">Interest %</label>
                                        <input class="form-control" id="interest"  value="">

                                        <label for="applicable_after mt-2">Applicable After (Interest Delay) </label>
                                        <input class="form-control" id="applicable_after"  value="">

                                        <label for="penalty mt-2">Liquidation Penalty %</label>
                                        <input class="form-control" id="penalty"  value="">


                                        <label for="recurrent_after_first mt-2">Continue Giving Interest / Duration</label>
                                        <select class="form-control" id="recurrent_after_first">
                                            <option value="no">NO</option>
                                            <option value="yes" >Yes</option>
                                        </select>

                                        <label for="referer_commission mt-2">Referer commission </label>
                                        <input class="form-control" id="referer_commission"  value="">

                                        <label for="charge mt-2">Charge </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Figure</span>
                                            <input type="number" class="form-control" id="cut"  value="">
                                            <span class="input-group-text">Type</span>
                                            <select name="" id="cut_type" class="form-control">
                                                <option value="off" class="">Cut</option>
                                                <option value="percentage" class="">Percentage</option>
                                            </select>
                                        </div>

                                        <label for="status mt-2">Status </label>
                                        <select name="" id="status" class="form-control">
                                                <option value="off" class="">Off</option>
                                                <option value="on" class="">On</option>
                                        </select>

                                        <button class="save mt-3 btn btn-primary cursor">Save</button>
                                </div>

                                <script>
                                    jQuery(".save").on("click",function(){
                                        var obj = {};
                                        var pAttern = /^[a-zA-Z0-9]+$/;
                                        var dpattern = /^[0-9]\s(?:month|day|year)s?$/i;


                                        var bad = false;
                                        jQuery(".savingsForm input,.savingsForm select").each(function(){
                                            var id = jQuery(this).attr("id");
                                            var val = jQuery(this).val();

                                            if(val == ""){
                                                bad = true;
                                                showToast("All fields are required","red",5000);
                                            }else{

                                                if(id == "duration" || id == "applicable_after"){

                                                    if(!dpattern.test(val)){
                                                        showToast("Duration or Applicable After Pattern Mismatch","red",5000);
                                                        bad = true;
                                                    }else{
                                                        obj[id] = val;
                                                    }

                                                }
                                                else{

                                                    if(!pAttern.test(val)){
                                                        showToast("Pattern Mismatch","red",5000);
                                                        bad = true;
                                                    }else{
                                                        obj[id] = val;
                                                    }
                                                }
                                                
                                            }

                                        });

                                        if(bad){
                                            return;
                                        }


                                        obj["fixed-settings"] = "yes";
                                        showToast("Processing...","yellow",5000);

                                        //make ajax call
                                        jQuery.ajax({
                                            url: "/wp-content/plugins/vtupress/admin/pages/isavings/process.php",
                                            data: obj,
                                            dataType: 'text',
                                            'cache': false,
                                            "async": true,
                                            error: function (jqXHR, exception) {
                                                                
                                                    jQuery(".registerNow").text("Register");

                                                    if (jqXHR.status === 0) {
                                                        msg = "No Connection. Verify Network.";
                                            
                                                    } else if (jqXHR.status == 404) {
                                                        msg = "Requested page not found. [404]";
                                    
                                                    } else if (jqXHR.status == 500) {
                                                        msg = "Internal Server Error [500].";
                                    
                                                    } else if (exception === "parsererror") {
                                                        msg = "Requested JSON parse failed.";
                                    
                                                    } else if (exception === "timeout") {
                                                        msg = "Time out error.";
                                    
                                                    } else if (exception === "abort") {
                                                        msg = "Ajax request aborted.";
                                    
                                                    } else {
                                                        msg = "Uncaught Error." + jqXHR.responseText;
                                    
                                                    }
                                    
                                                    showToast(msg,"red",5000);

                                    
                                                
                                                },
                                            
                                            success: function(data) {
                                                
                                                
                                                var color;
                                                
                                                if(data == "100" || data == 100){
                                    
                                                    msg = "success";
                                                    color = "green";

                                                }
                                                else{
                                                    msg = data;
                                                    color = "red";

                                                }
                                                showToast(msg,color,5000);

                                                location.reload();
                                    
                                    
                                                
                                    
                                    
                                            },
                                            type: 'POST'
                                        });

                                    });

                                </script>



                        </div>
                        <div class="col-12 col-md-5 info">
                                <div class="alert alert-primary d-flex justify-content-center  align-items-center">
                                    <h5>Basic Infos</h5>
                                </div>
                                <p><b>SignUp Fee [number]</b> | This is the charge you require for your users to be able to apply for fixed Saving</p>
                                <p><b>Duration [text e.g 3 Months]</b> | This is how long you want this plan to last</p>
                                <p><b>Minimum Savings Amount [number]</b> | Minimum amount required to be able to save</p>
                                <p><b>Applicable After [text e.g 2 Months]</b> | Also known as (Interest Delay). lets say you want the interest to be added after 2 months whereby the duration is 3 months. i.e the interest will only be added after the 2 months</p>
                                <p><b>Continue giving interest / duration [yes/no]</b> | Do you want to continue giving interest following [Applicable After] recurrently?</p>
                                <p><b>Liquidation Penalty [number]</b> | How much % do you want to deduct from the principal amount incase the saver wants to withdraw before saving ripes. Interest are not added afterwards ?</p>
                                <p><b>Interest [number]</b> | How much interest % you want to give your user i.e principal + interest = withdrawable amount</p>
                                <p><b>Referer Commissions [number]</b> | How much interest in <?php echo $symbol;?> you want give the saver's referer</p>
                                <p><b>Charge Figure [number]</b> | How much you want to charge per [month] upon withdrawal</p>
                                <p><b>Charge Type [off / percentage]</b> | <i>Off</i> means your cut from total saving. e.g if the saver saves 1k daily for a 3 months plan and you set charge figure to 1 then you get 1k. Same applies to 2 meaning 2 daily savings from total duration plan. <br> <i>Percentage</i> is (charge figure)% off total savings </p>
                        </div>
                    </div>
                    
                        



                    </div>


                </div>





            <?php }?>
        </div>
    </div>