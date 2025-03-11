<?php
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(current_user_can("vtupress_access_history")){
    global $transactions;
    ?>

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

                <link
            rel="stylesheet"
            type="text/css"
            href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css"
            />
            <link
            href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
            rel="stylesheet"
            />


                                <!-- settings -->
                                <div class="modal fade" id="settings" tabindex="-1" aria-labelledby="settingsLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="settingsLabel">Basic Settings</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group my-2">
                                        <label for="">Send To:</label>
                                        <input type="text" class="form-control blast-lines" >
                                        <small>Just type all to send to all users or recipient lines starting with 0 e.g 080. Separate lines by comma (,)</small>
                                    </div>
                                    <div class="form-group my-2">
                                        <label for="">Message:</label>
                                        <textarea type="text" class="form-control blast-message" ></textarea>
                                        <small>Please observe sms standards, stay away from spams too</small>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary d-none" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-outline-primary sendBlastMessages" data-bs-dismiss="modal">Send</button>
                                </div>
                            </div>
                        </div>
                    </div>


                <div class="card">
                    <div class="card-body">
                        <div class="d-flex top justify-content-between align-items-center">
                            <h5 class="card-title">Weblink SMS Blaster</h5>
                            <div class="options d-flex align-items-center">
                                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#settings">Blast SMS</button>
                            </div>
                        </div>

                    <?php

                        //pagination_history_before("vp_smsblaster","nulled");

                        if(!isset($_GET["trans_id"])){
                            pagination_history_before("vp_smsblaster","nulled");
                        }
                        elseif(empty($_GET["trans_id"])){
                            pagination_history_before("vp_smsblaster","nulled");
                        }
                        else{
                          if(is_numeric($_GET["trans_id"]) && strlen($_GET["trans_id"]) != 10 && strlen($_GET["trans_id"]) != 11 ){
                            $id = $_GET["trans_id"];
                            pagination_history_before("vp_smsblaster","nulled","WHERE id = '$id' ");
                            }
                            elseif(is_numeric($_GET["trans_id"]) && strlen($_GET["trans_id"]) == 10 || strlen($_GET["trans_id"]) == 11 ){
                            $id = $_GET["trans_id"];
                            pagination_history_before("vp_smsblaster","nulled","WHERE phone = '$id'");

                            }
                            else{
                                $id = $_GET["trans_id"];
                                // pagination_history_before("sairtime","true","AND request_id = '$id'");
                                pagination_history_before("vp_smsblaster","nulled");

                            }
                        }


                        ?>

                            <div class="table-responsive">
                                <table id="zero_config" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                            <th scope='col' class=''>ID</th>
                                            <th scope='col' class=''>Phone</th>
                                            <th scope='col' class=''>Message</th>
                                            <th scope='col' class=''>Logged</th>
                                            <th scope='col' class='d-none'>Sent</th>
                                            <th scope='col' class=''>Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                               
                                            if($transactions == "null"){
                                            ?>
                                                <tr  class="text-center">
                                                    <td colspan="4">No Blasted Sms</td>
                                                </tr>
                                            <?php
                                            }else{

                                                foreach($transactions as $result){
                                                    $id = $result->id;
                                                    $phone = $result->phone;
                                                    $message = $result->message;
                                                    $sent = $result->sent;
                                                    $logged = $result->logged;
                                                    $time = $result->the_time;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $id;?></td>
                                                        <td><?php echo $phone;?></td>
                                                        <td><?php echo $message;?></td>
                                                        <td><?php echo $logged;?></td>
                                                        <td class="d-none"><?php echo $sent;?></td>
                                                        <td><?php echo $time;?></td>
                                                    </tr>


                                                    <?php
                                                }

                                            }
                                            
                                        ?>
                                    </tbody>
                                </table>
                            </div>



                    </div>
                </div>



        </div>
    </div>
</div>

<?php 

            }
?>

<script>

    jQuery(".sendBlastMessages").click(function(){
        var lines = jQuery(".blast-lines").val();
        var messages = jQuery(".blast-message").val(); 
        var obj = {};


        if(lines == "" || messages == ""){
            alert("All fields required");
            return;
        }
        obj.lines = lines;
        obj.message = messages;
        jQuery(".preloader").show();


        jQuery.ajax({
                url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/weblink/blast.php'));?>",
                data: obj,
                dataType: "text",
                "cache": false,
                "async": true,
                error: function (jqXHR, exception) {
                    jQuery(".preloader").hide();
                        var msg = "";
                        if (jqXHR.status === 0) {
                            msg = "No Connection.\n Verify Network.";
                        } else if (jqXHR.status == 404) {
                            msg = "Requested page not found. [404]";
                        } else if (jqXHR.status == 500) {
                             msg = "Internal Server Error [500].";
                        } else if (jqXHR.status == 403) {
                            msg = "Access Forbidden [403].";
                        }
                        else if (exception === "parsererror") {
                            msg = "Requested JSON parse failed.";
                        } else if (exception === "timeout") {
                            msg = "Time out error.";
                        } else if (exception === "abort") {
                            msg = "Ajax request aborted.";
                        } else {
                            msg = "Uncaught Error.\n" + jqXHR.responseText;
                        }
                        swal({
                            title: "Error!",
                            text: msg,
                            icon: "error",
                            button: "Okay",
                        });
                    },
                success: function(data) {
                    jQuery(".preloader").hide();
                    if(data== "100" ){
                    
                        swal({
                            title: "DONE",
                            text: "Logged To Be Sent Successfully!",
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
                            text: data,
                            icon: "error",
                            button: "Okay",
                        });
                    }
                },
                type: "POST"
        });
    });



</script>