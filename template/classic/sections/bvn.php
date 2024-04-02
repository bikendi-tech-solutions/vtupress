<?php
if(isset($_GET["vend"]) && $_GET["vend"]=="verification" && vp_option_array($option_array,"setbvn") == "yes" && vp_option_array($option_array,"vtupress_custom_bvn") == "yes"){
			echo'
			<!-- bvn -->

		<div class="user-vends">
';
?>


<div class="container-md mt-3">
		<?php vp_kyc_update();?>
    <div class="data-form p-3" style="border: 1px solid grey; border-radius: 5px;">
	<div class="p-2 check-balance" style="text-align:center;"></div>
        <div class="mb-2">

            <label for="vtype">Verification Type<code>*</code></label>
                <select id="vtype" class="type form-select form-select-sm ">
                    <option value="">Please Select</option>
                    <option value="bvn">BVN</option>
                    <option value="nin">NIN</option>
                </select>

            <label for="vvalue" class="mt-2" >Verification Value<code>*</code> </label>
                <input id="vvalue" class="value form-control" type="number" placeholder="Please enter value"/>

            <label for="amount" class="mt-2">Charge</label>
                <input id="amount" class="form-control" type="number" readonly>

            <button class="btn mt-2 vverify w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow purchase-data">Verify</button>

            <script>

                jQuery("#vtype").on("change",function(){
                    var amt = jQuery("#amount");
                    var type = jQuery("#vtype").val();

                    switch(type){
                        case"bvn":
                            amt.val(parseInt("<?php echo vp_getoption('u_bvn_verification_charge');?>"));
                            break;
                        case"nin":
                            amt.val(parseInt("<?php echo vp_getoption('u_nin_verification_charge');?>"));
                            break;
                        default:
                            amt.val(0);
                            alert("Please choose a valid verification type");
                            break;
                    }

                });

    <?php
            if(isset($_GET["type"])){
                $type = $_GET["type"];
                switch($type){
                    case"bvn":
                        ?>
jQuery("#vtype").val("bvn");
jQuery(".value").attr("placeholder","Please enter bvn number");
jQuery("#vtype").prop("disabled",true);
jQuery("#vtype").change();
                        <?php
                    break;
                    case"nin":
                        ?>
jQuery("#vtype").val("nin");
jQuery(".value").attr("placeholder","Please enter nin number");
jQuery("#vtype").prop("disabled",true);
jQuery("#vtype").change();
                        <?php 
                    break;
                }
            }


    ?>

                		

                jQuery(".vverify").on("click",function(){
                    var vtype = jQuery("#vtype").val();
                    var vvalue = jQuery("#vvalue").val();

                    obj = {};
                    obj["type"] = vtype;
                    obj["value"] = vvalue;
                   
                    if(vtype == "" || vvalue == "" ){
                        alert("Please fill all the fields");
                        return;
                    }else{
                        jQuery("#cover-spin").show();

                        jQuery.ajax({
                            url:"<?php echo plugins_url('vtupress');?>/bvn_verification.php",
                            method:"POST",
                            data:obj,
                            error: function (jqXHR, exception) {
                                jQuery("#cover-spin").hide();
                                        var msg = "";
                                        if (jqXHR.status === 0) {
                                            msg = "No Connection.\n Verify Network.";
                                    swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                
                                        } else if (jqXHR.status == 404) {
                                            msg = "Requested page not found. [404]";
                                            swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        } else if (jqXHR.status == 500) {
                                            msg = "Internal Server Error [500].";
                                            swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        } else if (exception === "parsererror") {
                                            msg = "Requested JSON parse failed.";
                                            swal({
                                title: msg,
                                text: jqXHR.responseText,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        } else if (exception === "timeout") {
                                            msg = "Time out error.";
                                            swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        } else if (exception === "abort") {
                                            msg = "Ajax request aborted.";
                                            swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        } else {
                                            msg = "Uncaught Error.\n" + jqXHR.responseText;
                                            swal({
                                title: "Error!",
                                text: msg,
                                icon: "error",
                                button: "Okay",
                                }).then((value) => {
                                    location.reload();
                                }); 
                                        }
                                    },
                                
                            success:function(data){
                                jQuery("#cover-spin").hide();
                                if(data == "success"){
                                    swal({
                                    title: "Verification Successful!",
                                    text: "Please check history for verification information!",
                                    icon: "success",
                                    button: "Okay",
                                    }).then((value) => {
                                        location.reload();
                                    });
                                }else{
                                    swal({
                                    title: "Verification Failed!",
                                    text: data,
                                    icon: "danger",
                                    button: "Okay",
                                    });
                                }
                            }
                        });
                    }
                });

            </script>



        </div>


    </div>

</div>





<?php
echo'
		
        </div>

		
		<!-- bvn End -->
		
		
			';
			

		}

?>