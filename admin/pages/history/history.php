<?php
//here
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

?>
<script>
function openfunction(db,type,forh = true){
    var verify = false;
jQuery('input[type=checkbox].listCheckbox').each(function () {
           if (this.checked) {
             //  console.log();
             verify = true;
           }
});

if(verify){

}
else{
      return;
}

if(forh == true){
swal({
  title: "Please Be Careful",
  text: "What Do You Wanna Do With The Checked?",
  icon: "warning",
  buttons: {
    cancel: "Close",
    confirm: "Reverse",
    delete: {
      text: "Delete!",
      value: "delete",
    },
    roll: {
      text: "Successful",
      value: "success",
    },
    catch: {
      text: "Successful & Debit",
      value: "success_deb",
    },
  },
}).then((value) => {

if(value == true){ //if confirm clicked then reverse

  multiplereverse(db,type);

  }else if(value == "null"){ //if close clicked then return/close
return;
}else if(value == "delete"){ // if delete clicked then delete selected

  multipledelete(db,type);
}
else if(value == "success"){ // if success clicked
  multiplesuccess(db,type);
}
else if(value == "success_deb"){ // if success clicked
  multiplesuccess(db,type,true);
}
else{
  return;
}

});

}
else{
  multipledelete(db,type); 
}


function multiplereverse(db,type){
    var ids = "";
    var name = "";
  jQuery('input[type=checkbox].listCheckbox').each(function () {
             if (this.checked) {
               //  console.log();
                 ids +=","+jQuery(this).val()+"-"+jQuery(this).attr("amount")+"-"+jQuery(this).attr("user");
             }
  
  });
  
  
  
  var obj = {};
  obj['trans_id'] = ids;
  obj['table'] = db;
  obj['type'] = type;
  obj['action'] = 'reverse';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";
  
  
  if(confirm("Do You Want To Reverse The Selected Transactions?") == true){
    jQuery(".preloader").show();
  jQuery.ajax({
    url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/multiplehistory.php'));?>",
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
    text: "Transactions Refunded",
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
    text: "Process Wasn't Completed",
    icon: "error",
    button: "Okay",
  });
        }
    },
    type: "POST"
  });
  
  
  
  }
  else{
  
      return;
  }
  
  }





  function multipledelete(db,type){
    var ids = "";
    var name = "";
  jQuery('input[type=checkbox].listCheckbox').each(function () {
             if (this.checked) {
               //  console.log();
                 ids +=","+jQuery(this).val()+"-"+jQuery(this).attr("amount")+"-"+jQuery(this).attr("user");
             }
  
  });
  
  
  
  var obj = {};
  obj['trans_id'] = ids;
  obj['table'] = db;
  obj['type'] = type;
  obj['action'] = 'delete';
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";
  
  
  if(confirm("Do You Want To Delete The Selected Transactions?") == true){
    jQuery(".preloader").show();
  jQuery.ajax({
    url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/multiplehistory.php'));?>",
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
    text: "Transactions Deleted!",
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
    text: "Process Wasn't Completed",
    icon: "error",
    button: "Okay",
  });
        }
    },
    type: "POST"
  });
  
  
  
  }
  else{
  
      return;
  }
  
  }



  
  function multiplesuccess(db,type,rev=false){
    var ids = "";
    var name = "";
  jQuery('input[type=checkbox].listCheckbox').each(function () {
             if (this.checked) {
               //  console.log();
                 ids +=","+jQuery(this).val()+"-"+jQuery(this).attr("amount")+"-"+jQuery(this).attr("user");
             }
  
  });
  
  
  
  var obj = {};
  obj['trans_id'] = ids;
  obj['table'] = db;
  obj['type'] = type;

  if(rev){
    obj['action'] = 'success_deb';
  }
  else{
    obj['action'] = 'success';
  }
  obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

  
  
  if(confirm("Do You Want To Mark The Selected Transactions As Succuessful?") == true){
    jQuery(".preloader").show();
  jQuery.ajax({
    url: "<?php echo esc_url(plugins_url('vtupress/admin/pages/history/saves/multiplehistory.php'));?>",
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
    text: "Transactions Successful!",
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
    text: "Process Wasn't Completed",
    icon: "error",
    button: "Okay",
  });
        }
    },
    type: "POST"
  });
  
  
  
  }
  else{
  
      return;
  }
  
  }







  }
</script>