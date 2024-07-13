
<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.
</p>
<div class="container-fluid settings-container">

    <div class="alert my-3 alert-primary d-flex flex-column align-items-center justify-items-center">
        <h3>Savings Withdrawal/Liquidation Page</h3>
    </div>

    <div class="row">
        <div class="col savings-div table-responsive">

            <?php 
                global $wpdb;
                $table_name = $wpdb->prefix.'vp_savings_withdrawal';
                $results = $wpdb->get_results("SELECT * FROM $table_name ");

               if(empty($results)){
            ?>
                <div class="alert alert-info d-flex justify-content-center align-items-center">
                    <span class=" fw-bold">No Withdrawal Recorded Yet</span>
                </div>
            <?php }else{
            ?>
                    <table class=" datatables table table-hover table-responsive ">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>User Id</th>
                                <th>Type</th>
                                <th>Savings Plan</th>
                                <th>Amount Saved</th>
                                <th>Amount Withdrawn</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                <?php

                    $num = 0;
                foreach($results as $result):

                    $num += 1;
                    $user_id = $result->user_id;
                    $type = $result->type;
                    $duration = $result->duration;
                    $amount_saved = $result->amount_saved;
                    $amount_withdrawn = $result->amount_withdrawn;
                    $status = $result->status;
                    $wallet_id = $result->wallet_id;
                    $savings_id = $result->savings_id;

                    $select = <<<EOT
                            <select class="setStatus form-control" plan="$type" id="$user_id" wallet-id="$wallet_id"  savings-id="$savings_id">
                                    <option value="" >-- Set Status --</option>
                                    <option value="withdrawn" class="">Approve</option>
                                    <option value="rejected" class="">Reject</option>
                            </select>
                    EOT;

                    if($type == "daily"){
                        $select = "No Action";
                    }
                    elseif($status == "liquidated"){
                        $select = "No Action";
                    }
                    elseif($status == "withdrawn"){
                        $select = "No Action";
                    }
            
            ?>
                            <tr>
                                <td><?php echo $num;?></td>
                                <td><?php echo $user_id;?></td>
                                <td><?php echo $type;?></td>
                                <td><?php echo $duration;?></td>
                                <td><?php echo $amount_saved;?></td>
                                <td><?php echo $amount_withdrawn;?></td>
                                <td><?php echo $status;?></td>
                                <td><?php echo $select;?></td>
                            </tr>



                    <script>
                        jQuery(".setStatus").on("change",function(){
                            var opt = jQuery(this).val();
                            var userid = jQuery(this).attr("id");
                            var wallet_id = jQuery(this).attr("wallet-id");
                            var savings_id = jQuery(this).attr("savings-id");
                            var plan = jQuery(this).attr("plan");
                            var word = "approve";

                            if(opt != "withdrawn"){
                                word = "reject";
                            }

                            if(!confirm("Do you want to "+word+" this withdrawal?")){
                                return;
                            }

                            var obj = {};
                            obj["setstatus"] = opt;
                            obj["userid"] = userid;
                            obj["wallet_id"] = wallet_id;
                            obj["savings_id"] = savings_id;
                            obj["plan"] = plan;

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


            <?php 
                endforeach;
                ?>       </tbody>
                    </table>
        <?php
            }
            
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.datatables').DataTable();
        });
    </script>