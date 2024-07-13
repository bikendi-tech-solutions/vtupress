
<p style="visibility:hidden;">
Please take note to always have security system running and checked. DO not disclose your login details to anyone except for confidential reasons. 
Not even the developers of this plugin should be trusted enough to grant access anyhow.
</p>
<div class="container-fluid settings-container">

    <div class="alert my-3 alert-primary d-flex flex-column align-items-center justify-items-center">
        <h3>Savings Log Page</h3>
    </div>

    <div class="row">
        <div class="col savings-div table-responsive">

            <?php 
                global $wpdb;
                $table_name = $wpdb->prefix.'vp_savings';
                $results = $wpdb->get_results("SELECT * FROM $table_name ");

               if(empty($results)){
            ?>
                <div class="alert alert-info d-flex justify-content-center align-items-center">
                    <span class=" fw-bold">No Savings Recorded Yet</span>
                </div>
            <?php }else{
            ?>
            
            <table class=" datatables table table-responsive table-hover">
                        <thead>
                            <tr>
                                <th>S/N</th>
                                <th>User Id</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Saving Interval</th>
                                <th>Count</th>
                                <th>Amount Saved</th>
                                <th>Interest</th>
                                <th>Applicable On</th>
                                <th>Status</th>
                                <th>Automatic</th>
                                <th>Started</th>
                                <th>Ends</th>
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
                    $saveInterval = $result->save_interval;
                    $start_amount = $result->start_amount;
                   
                    $interval = $saveInterval;
                    if($interval == "day"){
                        $interval = "daily";
                    }
                    elseif( $interval == "week"){
                        $interval = "weekly";
                    }
                    elseif( $interval == "month"){
                        $interval = "monthly";
                    }
                    $saves = $start_amount." ".$interval;

                    $save_count = $result->save_count;
                    $amount_saved = $result->amount_saved;
                    $interest = $result->interest;
                    $applicable_on = $result->applicable_on;
                    $status = $result->status;
                    $automatic = $result->automatic;
                    $started_at = $result->started_at;
                    $ends_at = $result->ends_at;
                    $liquidated = $result->liquidated;
            ?>

                            <tr>
                                <td><?php echo $num;?></td>
                                <td><?php echo $user_id;?></td>
                                <td><?php echo $type;?></td>
                                <td><?php echo $duration;?></td>
                                <td><?php echo $saves;?></td>
                                <td><?php echo $save_count;?></td>
                                <td><?php echo $amount_saved;?></td>
                                <td><?php echo $interest;?></td>
                                <td><?php echo $applicable_on;?></td>
                                <td><?php echo $status;?></td>
                                <td><?php echo $automatic;?></td>
                                <td><?php echo $started_at;?></td>
                                <td><?php echo $ends_at;?></td>
                            </tr>



            <?php 
                endforeach;
                ?>

                        </tbody>
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