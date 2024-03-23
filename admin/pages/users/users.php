<script>

function multipleactions(db,type){
    var ids = "";
    var name = "";
  jQuery('input[type=checkbox].listCheckbox').each(function () {
             if (this.checked) {
               //  console.log();
                 ids +=","+jQuery(this).attr("user");
             }
  
  });
  
  
  
  var obj = {};
  obj['userid'] = ids;
  obj['action'] = jQuery(".maction").val();
  
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";
  
    jQuery(".preloader").show();
  jQuery.ajax({
    url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/users/saves/users.php'));?>",
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
          if(data == "100" ){
      
            swal({
    title: "Done!",
    text: "D.A.N SUCCESSFUL",
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
  
  
  
 
  
  }
</script>