
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
                $table_name = $wpdb->prefix.'vp_daily_savings_settings';
                $results = $wpdb->get_results("SELECT * FROM $table_name ");

               if(empty($results)){
            ?>
                <div class="alert alert-info justify-content-center align-items-center">
                    <button class="btn btn-info text-white">Generate DB</button>
                </div>
            <?php }else{
                $result = $results[0];
                
                $sign_up_fee = $result->sign_up_fee;
                $cut = $result->cut;
                $cut_type = $result->cut_type;
                $minimum_amount = $result->minimum_amount;
                $interest = $result->interest;
                $status = $result->status;
                $referer_commission = $result->referer_commission;
            ?>

                <div class="card">
                    <div class="card-head px-4 pt-3">
                        <h4>Settings</h4>
                    </div>
                    <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-md-7 ssettings border rounded rounded-3">

                                <div class="py-3 px-2 savingsForm">
                                        <label for="sign_up_fee mt-2">SignUp Fee <?php echo $symbol;?></label>
                                        <input class="form-control" id="sign_up_fee" value="<?php echo $sign_up_fee;?>">

                                        <label for="minimum_amount mt-2">Minimum Saving Amount <?php echo $symbol;?></label>
                                        <input class="form-control" id="minimum_amount"  value="<?php echo $minimum_amount;?>">
                                        
                                        <label for="interest mt-2">Interest %</label>
                                        <input class="form-control" id="interest"  value="<?php echo $interest;?>">


                                        <label for="referer_commission mt-2">Referer Commission <?php echo $symbol;?></label>
                                        <input class="form-control" id="referer_commission"  value="<?php echo $referer_commission;?>">

                                        <label for="charge mt-2">Charge </label>
                                        <div class="input-group">
                                            <span class="input-group-text">Figure</span>
                                            <input type="number" class="form-control" id="cut"  value="<?php echo $cut;?>">
                                            <span class="input-group-text">Type</span>
                                            <select name="" id="cut_type" class="form-control">
                                                <option value="<?php echo $cut_type;?>" class=""><?php echo strtoupper($cut_type);?></option>
                                                <option value="off" class="">Cut</option>
                                                <option value="percentage" class="">Percentage</option>
                                            </select>
                                        </div>

                                        <label for="status mt-2">Status ? </label>
                                        <select name="" id="status" class="form-control">
                                                <option value="<?php echo $status;?>" class=""><?php echo strtoupper($status);?></option>
                                                <option value="off" class="">Off</option>
                                                <option value="on" class="">On</option>
                                        </select>

                                        <button class="save mt-3 btn btn-primary cursor">Save</button>
                                </div>

                                <script>
                                    jQuery(".save").on("click",function(){
                                        var obj = {};
                                        var pAttern = /^[a-zA-Z0-9]+$/;


                                        var bad = false;
                                        jQuery(".savingsForm input,.savingsForm select").each(function(){
                                            var id = jQuery(this).attr("id");
                                            var val = jQuery(this).val();

                                            if(val == ""){
                                                bad = true;
                                                showToast("All fields are required","red",5000);
                                            }else{

                                                if(!pAttern.test(val)){
                                                    showToast("Pattern Mismatch","red",5000);
                                                    bad = true;
                                                }else{
                                                    obj[id] = val;
                                                }
                                                
                                            }

                                        });

                                        if(bad){
                                            return;
                                        }

                                        obj["daily-settings"] = "yes";
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
                                <p><b>SignUp Fee [number]</b> | This is the charge you require for your users to be able to apply for daily Saving</p>
                                <p><b>Minimum Savings Amount [number]</b> | Minimum threshold to be able to save</p>
                                <p><b>Interest [number]</b> | How much interest % you want to give your user i.e principal + interest = withdrawable amount</p>
                                <p><b>Referer Commissions [number]</b> | How much interest in <?php echo $symbol;?> you want give the saver's referer</p>
                                <p><b>Charge Figure [number]</b> | How much you want to charge per [month] upon withdrawal</p>
                                <p><b>Charge Type [off / percentage]</b> | <i>Off</i> means your cut from total saving. e.g if the saver saved 1k daily and you set charge figure to 1 then you get 1k. Same applies to 2 meaning 2 daily savings / total daily savings. <br> <i>Percentage</i> is (charge figure)% off total savings </p>
                        </div>
                    </div>
                    
                        



                    </div>


                </div>





            <?php }?>
        </div>
    </div>