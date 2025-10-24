<?php
if (!defined('ABSPATH')) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
}
if (WP_DEBUG == false) {
    error_reporting(0);
}
include_once(ABSPATH . "wp-load.php");
include_once(ABSPATH . 'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH . 'wp-content/plugins/vtupress/foradmin.php');

if (vp_getoption("resell") != "yes") {
    vp_die("Please Upgrade To Premium Package or Lifetime Package To Enjoy This Feature");
}
?>

<div class="row">

    <div class="col-12">
        <link rel="stylesheet" type="text/css"
            href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css" />
        <link
            href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
            rel="stylesheet" />
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bio Data</h5>

                <div class="border border-secondary rounded p-2 my-2">
                    <p>Here You Can View And Manage All The Biometric Data Uploaded By Users. You Can Remove Biometric
                        Data If Needed.</p>
                    <div class="row">
                        <div class="col d-flex flex-column align-items-start">
                            <span class="fw-bold">enForce Face Capturing Before Access To Dashboard</span>
                            <select name="" id="enforce" for="access" class="form-control">
                                <option value="yes" <?php if (vp_getoption("vtupress_enforce_biometric_access") == "yes") {
                                    echo "selected";
                                } ?>>Yes</option>
                                <option value="no" <?php if (vp_getoption("vtupress_enforce_biometric_access") != "yes") {
                                    echo "selected";
                                } ?>>No</option>
                            </select>
                        </div>
                        <div class="col">
                            <span class="fw-bold">enForce Face Capturing Before Access To Transactions</span>
                            <select name="" id="enforce2" for="transactions" class="form-control">
                                <option value="yes" <?php if (vp_getoption("vtupress_enforce_biometric_transactions") == "yes") {
                                    echo "selected";
                                } ?>>Yes</option>
                                <option value="no" <?php if (vp_getoption("vtupress_enforce_biometric_transactions") != "yes") {
                                    echo "selected";
                                } ?>>No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <?php

                pagination_kyc_before("bio");


                ?>

                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Biometric</th>
                                <th>Selfie</th>
                                <th>User Id</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php


                            global $users;
                            if ($users == "null") {
                                ?>
                                <tr class="text-center">
                                    <td colspan="8">No Bio Recorded</td>
                                </tr>
                                <?php
                            } else {
                                $option_array = json_decode(get_option("vp_options"), true);
                                global $wpdb;
                                #GET LEVELS
                            

                                $current_amt = 0;

                                foreach ($users as $users) {

                                    $bio = !empty($users->code) ? "Added" : "N/A";
                                    $photo = !empty($users->photo_link) ? "<a href='$users->photo_link' class='btn btn-primary'>View</a>" : "N/A";
                                    $remove_bio = !empty($users->code) ? "<button type='button' class='btn btn-secondary' onclick='removebio(\"$users->user_id\", \"bio\");'  doc='bio'>Remove Bio</button>" : "";
                                    $remove_photo = !empty($users->photo_link) ? "<button type='button' class='btn btn-secondary' onclick='removebio(\"$users->user_id\", \"photo\");'  doc='photo'>Remove Photo</button>" : "";


                                    echo "
    <tr>
    <td>$users->id</td>
    <td>" . get_userdata($users->user_id)->user_login . "</td>
    <td>$bio</td>
    <td>$photo</td>
    <td>$users->user_id</td>
    <td>$users->the_time</td>
  <td>
    $remove_bio
    $remove_photo

  </td>
  </tr>
    ";

                                }

                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Biometric</th>
                                <th>Selfie</th>
                                <th>User Id</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    <script>
                        function removebio(user, doc) {

                            if (confirm("Do You Want To Remove The Selected Bio?") == true) {

                                jQuery(".preloader").show();

                                var obj = {};
                                obj["doc"] = doc;
                                obj["name"] = "remove";
                                obj["d_user_id"] = user;
                                obj["spraycode"] = "<?php echo vp_getoption("spraycode"); ?>";


                                jQuery.ajax({
                                    url: "<?php echo esc_url(plugins_url('vtupress/fingerprint/auth.php')); ?>",
                                    data: obj,
                                    dataType: 'json',
                                    'cache': false,
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

                                         try{
                                            msg = JSON.parse(jqXHR.responseText);
                                            swal({
                                                title: "Error!",
                                                text: msg.message,
                                                icon: "error",
                                                button: "Okay",
                                            });

                                        }catch(e){
                                            msg = "Uncaught Error.\n" + jqXHR.responseText;
                                            swal({
                                                title: "Error!",
                                                text: msg,
                                                icon: "error",
                                                button: "Okay",
                                            });
                                        }
                                    }
                                    },

                                    success: function (data) {
                                        jQuery(".preloader").hide();
                                        if (data.success) {
                                            swal({
                                                title: "Selected Bio Removed!",
                                                text: "Thanks",
                                                icon: "success",
                                                button: "Okay",
                                            }).then((value) => {
                                                location.reload();
                                            });
                                        }
                                        else {
                                            jQuery(".preloader").hide();
                                            swal({
                                                buttons: {
                                                    cancel: "Why?",
                                                    defeat: "Okay",
                                                },
                                                title: "Error Changing Status",
                                                text: "Click Why To See Reason",
                                                icon: "warning",
                                            })
                                                .then((value) => {
                                                    switch (value) {

                                                        case "defeat":
                                                            location.reload();
                                                            break;
                                                        default:
                                                            swal(data.message, {
                                                                icon: "info",
                                                            });
                                                    }
                                                });
                                        }
                                    },
                                    type: 'POST'
                                });


                            }

                        }

                        jQuery("#enforce,#enforce2").change(function () {
                            var val = jQuery(this).val();
                            var forwhat = jQuery(this).attr("for");

                            jQuery(".preloader").show();

                            var obj = {};
                            obj["name"] = "enforce_biometric";
                            obj["for"] = forwhat;
                            obj["value"] = val;
                            obj["spraycode"] = "<?php echo vp_getoption("spraycode"); ?>";
                            jQuery.ajax({
                                url: "<?php echo esc_url(plugins_url('vtupress/fingerprint/auth.php')); ?>",
                                data: obj,
                                dataType: 'json',
                                'cache': false,
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
                                        try{
                                            msg = JSON.parse(jqXHR.responseText);
                                            swal({
                                                title: "Error!",
                                                text: msg.message,
                                                icon: "error",
                                                button: "Okay",
                                            });

                                        }catch(e){
                                            msg = "Uncaught Error.\n" + jqXHR.responseText;
                                            swal({
                                                title: "Error!",
                                                text: msg,
                                                icon: "error",
                                                button: "Okay",
                                            });
                                        }

                                    }
                                },

                                success: function (data) {
                                    jQuery(".preloader").hide();
                                    if (data.success) {
                                        swal({
                                            title: "Setting Updated!",
                                            text: "Thanks",
                                            icon: "success",
                                            button: "Okay",
                                        }).then((value) => {
                                            location.reload();
                                        });
                                    }
                                    else {
                                        jQuery(".preloader").hide();
                                        swal({
                                            buttons: {
                                                cancel: "Why?",
                                                defeat: "Okay",
                                            },
                                            title: "Error Changing Status",
                                            text: "Click Why To See Reason",
                                            icon: "warning",
                                        })
                                            .then((value) => {
                                                switch (value) {

                                                    case "defeat":
                                                        location.reload();
                                                        break;
                                                    default:
                                                        swal(data.message, {
                                                            icon: "info",
                                                        });
                                                }
                                            });
                                    }
                                },
                                type: 'POST'
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>


</div>