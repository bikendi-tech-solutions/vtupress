<?php
function pagination_fwebhook($where=""){
	  
   global  $wpdb, $result;
  
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."vp_wallet_webhook";
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $result = "null";
      }
      else{
       $result = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-end">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}


function pagination_webhook($where=""){
	  
   global  $wpdb, $result;
  
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."vpwebhook";
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $result = "null";
      }
      else{
       $result = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-end">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}



function pagination_history_before($db="",$status="true",$whe=""){

   if(isset($_GET["fromDate"])){
      $fromDate = $_GET["fromDate"];
      $toDate = $_GET["toDate"];

      if($db == "scable" || $db == "sbill"){
         $dDate = " AND time BETWEEN '$fromDate' AND '$toDate' ";
      }
      else{
         $dDate = " AND the_time BETWEEN '$fromDate' AND '$toDate' ";
      }

      $currentState = " `$fromDate - $toDate`";

   }
   else{
      $dDate = "";
      $currentState ="";
   }
	  
   global  $wpdb, $transactions;
  if($status == "true"){
   $stat = "status = 'successful'";
   $where = "WHERE $stat ".$whe.$dDate;
  }
  elseif($status == "fa"){
   $stat = "status = 'fa'";
   $where = "WHERE $stat ".$whe.$dDate;

  }
  elseif($status == "false"){
   $stat = "status = 'Pending'";
   $where = "WHERE $stat ".$whe.$dDate;

  }
  elseif($status == "approved"){
   $stat = "status = 'approved'";
   $where = "WHERE $stat ".$whe.$dDate;
  }
  else{
   $stat = "status = 'Failed'";
   $where = "WHERE $stat ".$whe.$dDate;
  }
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix.$db;
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $transactions = "null";
       $dtransactions = "null";
      }
      else{
       $transactions = $use;
       $dtransactions = "good";

      }




      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left use_trans" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

<div class="col-12 col-md-4">

<div class="input-group">
<span class="input-group-prepend input-group-text" data-bs-toggle="modal" data-bs-target="#historyFilterModal">
<i class="fas fa-filter"></i>
</span>
<input class="use_trans form-control border-end-0 border rounded-pill search-trans" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="use_trans btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
';
$runScript =<<<EOT

<!-- Modal -->
<div class="modal fade" id="historyFilterModal" tabindex="-1" aria-labelledby="historyFilterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyFilterModalLabel">Filter History  $currentState</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
                        <div class="row">
                        <div class="col-12 date_picker_col">
                           <select name="" id="" class="form-control date_picker" >
                              <option value="none">-- Select Date -- </option>
                              <option value="last_30_days">Last 30 Days</option>
                              <option value="last_90_days">Last 90 Days</option>
                              <option value="all_time">All Time</option>
                              <option value="today">Today</option>
                              <option value="this_week">This Week</option>
                              <option value="this_month">This Month</option>
                              <option value="this_year">This Year</option>
                              <option value="custom">Custom</option>
                           </select>
                        </div>
                        <div class="col-12 custom_date d-none">
                           
                           <div class="input-group">
                              <span class="from_date_label "><i class="far fa-times-circle input-group-text text-danger close_custom_dp" onclick="jQuery('.custom_date').addClass('d-none');jQuery('.date_picker_col').removeClass('d-none');jQuery('.date_picker').val('today');"></i></span>
                              <span class="from_date_label input-group-text">From</span>
                              <input type="date" class="form-control  from_date" >
                              <span class="to_date_label input-group-text">To</span>
                              <input type="date" class="form-control  to_date" >
                              <button class="customQueryDb btn btn-success text-white btn-sm " ><i class="far fa-check-circle fw-100 fs-4"></i></button>
                           </div>
                        </div>
                  </div>

                  <script>
                  var sendFromDate;
                  var sendToDate;
                
                  var currentDate = new Date();
                  var currentDate2 = new Date("2020-12-01");
                  // Calculate the date four days ago
                  var predate = new Date(currentDate);
                  var prodate = new Date(currentDate);

                  function setDateFt(){

                     currentDate = new Date();
                     currentDate2 = new Date("2020-12-01");
                     // Calculate the date four days ago
                     predate = new Date(currentDate);
                     prodate = new Date(currentDate);
                   
                    var date_picker = jQuery('.date_picker').val();
                   
                   var dDate;
                   
                    switch(date_picker){
                       case"last_30_days":
                   
                       predate.setDate(currentDate.getDate() - 30);
                       prodate.setDate(currentDate.getDate());
                   
                           // Format the fourDaysAgo date as a string
                          // dDate = predate.toDateString();
                   
                   
                   
                       break;
                       case"last_90_days":
                       predate.setDate(currentDate.getDate() - 90);
                       prodate.setDate(currentDate.getDate());
                   
                           // Format the fourDaysAgo date as a string
                           //dDate = predate.toDateString();
                       break;
                       case"all_time":
                         predate = new Date(currentDate2);
                         predate.setDate(currentDate2.getDate());
                         prodate.setDate(currentDate.getDate());
                       break;
                       case"today":
                       predate.setDate(currentDate.getDate());
                       prodate.setDate(currentDate.getDate());
                       break;
                       case"this_week":
                       var currentDay = currentDate.getDay(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                     
                       predate.setDate(currentDate.getDate() - currentDay);
                       prodate.setDate(currentDate.getDate());
                       break;
                       case"this_month":
                   
                       predate.setDate(1);
                      // var currentMonth = currentDate.getMonth(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                     
                       prodate.setDate(currentDate.getDate());
                       break;
                       case"this_year":
                       predate.setMonth(0);
                       predate.setDate(1);
                   
                      // var currentYear = currentDate.getFullYear(); // Get the current day (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                       
                      // predate.setDate(currentDate.getDate() - currentYear);
                       prodate.setDate(currentDate.getDate());
                       break;
                       case"custom":
                           jQuery(".custom_date").removeClass("d-none");
                           jQuery(".date_picker_col").addClass("d-none");
                       break;
                    };
                   
                   
                          // Format the oneMonthAgo date as "yyyy-mm-dd HH:mm:ss"
                          var fromDate = predate.getFullYear() + '-' +
                                            ('0' + (predate.getMonth() + 1)).slice(-2) + '-' +
                                            ('0' + predate.getDate()).slice(-2);/* + ' ' +
                                            ('0' + predate.getHours()).slice(-2) + ':' +
                                            ('0' + predate.getMinutes()).slice(-2) + ':' +
                                            ('0' + predate.getSeconds()).slice(-2);*/
                   
                           
                                  // Format the oneMonthAgo date as "yyyy-mm-dd HH:mm:ss"
                          var toDate = prodate.getFullYear() + '-' +
                                            ('0' + (prodate.getMonth() + 1)).slice(-2) + '-' +
                                            ('0' + prodate.getDate()).slice(-2);/* + ' ' +
                                            ('0' + predate.getHours()).slice(-2) + ':' +
                                            ('0' + predate.getMinutes()).slice(-2) + ':' +
                                            ('0' + predate.getSeconds()).slice(-2);*/
                   
                           
                   
                                            //alert(dDate+" =  "+formattedDate);
                           jQuery(".from_date").val(fromDate);
                           jQuery(".to_date").val(toDate);
                   
                   
                           sendFromDate =   predate.getFullYear() + '-' +
                                            ('0' + (predate.getMonth() + 1)).slice(-2) + '-' +
                                            ('0' + predate.getDate()).slice(-2);+ ' ' +
                                            ('0' + predate.getHours()).slice(-2) + ':' +
                                            ('0' + predate.getMinutes()).slice(-2) + ':' +
                                            ('0' + predate.getSeconds()).slice(-2);
                   
                           sendToDate =   prodate.getFullYear() + '-' +
                                            ('0' + (prodate.getMonth() + 1)).slice(-2) + '-' +
                                            ('0' + prodate.getDate()).slice(-2);+ ' ' +
                                            ('0' + prodate.getHours()).slice(-2) + ':' +
                                            ('0' + prodate.getMinutes()).slice(-2) + ':' +
                                            ('0' + prodate.getSeconds()).slice(-2);
                   
                         //alert("From "+sendFromDate +"To "+sendToDate);
                   
                   };

                   


                   jQuery(".date_picker").on("change",function(){

                     var date_picker2 = jQuery(this).val();
                     
                     setDateFt();
                     
                     if(date_picker2 != "custom"){
                        queryDB(sendFromDate,sendToDate);
                     }
                  });

                  jQuery(".customQueryDb").on("click",function(){

                     sendFromDate = jQuery(".from_date").val();
                     sendToDate = jQuery(".to_date").val();
                   
                     //alert(sendFromDate+" "+sendToDate);
                   
                   
                      // alert(sendFromDate+" "+sendToDate);
                       queryDB(sendFromDate,sendToDate);
                   
                   
                   });

                  </script>
EOT;
echo $runScript;
                  echo'
      </div>
    </div>
  </div>
</div>


<script>
function searchtrans(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&trans_id="+val;
}
</script>
</div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-start">
<span class="input-group-text">Limit</span>
<select class="change-limit use_trans" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
if("<?php echo $dtransactions;?>"  == "null"){
         jQuery(".use_trans").attr("disabled","");
}
else{}

function queryDB(from,to){
   var date_picker3 = jQuery('.date_picker').val();

   if(date_picker3 != "none"){
      window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&fromDate="+from+"&toDate="+to;
   }



 }
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php



}






function pagination_withdrawal_before($db="",$status="true",$whe=""){
	  
   global  $wpdb, $transactions;
  if($status == "true"){
   $stat = "status = 'Approved'";
   $where = "WHERE $stat ".$whe;
  }
  elseif($status == "false"){
   $stat = "status = 'Pending'";
   $where = "WHERE $stat ".$whe;
  }
  else{
   $stat = "status = 'failed'";
   $where = "WHERE $stat ".$whe;
  }
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix.$db;
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $transactions = "null";
      }
      else{
       $transactions = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

<div class="col-12 col-md-4">

<div class="input-group">
<input class="form-control border-end-0 border rounded-pill search-trans" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
<script>
function searchtrans(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&trans_id="+val;
}
</script>
</div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-start">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}



function pagination_conversion_before($status="true",$whe=""){
	  
   global  $wpdb, $transactions;
  if($status == "true"){
   $stat = "status = 'pending' AND (type = 'Airtime_To_Wallet' OR type = 'Airtime_To_Cash')";
   $where = "WHERE $stat ".$whe;
  }
  elseif($status == "false"){
   $stat = "status = 'Approved' AND (type = 'Airtime_To_Wallet' OR type = 'Airtime_To_Cash') ";
   $where = "WHERE $stat ".$whe;
  }
  else{
   $stat = "status = 'Failed' AND (type = 'Airtime_To_Wallet' OR type = 'Airtime_To_Cash') ";
   $where = "WHERE $stat ".$whe;
  }
 

  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."vp_wallet";
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $transactions = "null";
      }
      else{
       $transactions = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

<div class="col-12 col-md-4">

<div class="input-group">
<input class="form-control border-end-0 border rounded-pill search-trans" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
<script>
function searchtrans(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&trans_id="+val;
}
</script>
</div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-start">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}








function pagination_transfer_before($status="true",$whe=""){
	  
   global  $wpdb, $transactions;
  if($status == "true"){
   $stat = "status = 'pending'";
   $where = "WHERE $stat ".$whe;
  }
  elseif($status == "false"){
   $stat = "status = 'Approved'";
   $where = "WHERE $stat ".$whe;
  }
  else{
   $stat = "status = 'Failed'";
   $where = "WHERE $stat ".$whe;
  }
 

  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."vp_transfer";
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $transactions = "null";
      }
      else{
       $transactions = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

<div class="col-12 col-md-4">

<div class="input-group">
<input class="form-control border-end-0 border rounded-pill search-trans" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
<script>
function searchtrans(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&trans_id="+val;
}
</script>
</div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-start">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}







function pagination_wallet_before($whe=""){
	  
   global  $wpdb, $transactions;

  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['transpage']) ? $_GET['transpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."vp_wallet";
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $whe ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $transactions = "null";
      }
      else{
       $transactions = $use;


      $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $whe");
      $pages = ceil( $num  / $limit );
      
      $Previous = $page - 1;
      $Next = $page + 1;
      echo '

      <div class="row">
      <div class="col-12 col-md-4">
<div class="input-group">
<span class="input-group-text">Page</span>
<select class="change-page float-left" onchange="changepage();">
        ';
for($i = 1; $i<= $pages; $i++){
if(isset($_GET["transpage"]) && $_GET["transpage"] == $i){
   echo'
   <option value="'.$i.'" disabled selected  class="opt" >'.$i.'<option>
   ';
    }elseif(empty($i)){

    }
    else{
       echo'
       <option value="'.$i.'" class="opt" >'.$i.'<option>
       ';
    }

}
  echo'
  </select>
  <script>
  jQuery(".change-page option:not(.opt)").hide();
  </script>
  </div>
  </div>

<div class="col-12 col-md-4">

<div class="input-group">
<input class="form-control border-end-0 border rounded-pill search-trans" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
<script>
function searchtrans(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&trans_id="+val;
}
</script>
</div>

  <div class="col-12 col-md-4">

<div class="input-group justify-content-start">
<span class="input-group-text">Limit</span>
<select class="change-limit" onchange="changelimit();">
                          ';
                          
foreach([10,20,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500,700,1000,2000] as $limit){
if( isset($_GET["limit-records"]) && $_GET["limit-records"] == $limit){
$echo = "selected disabled";
}
else{
$echo = "opt";
}
echo'
<option '. $echo.' value="'. $limit.'">'. $limit.'</option>
';
}
echo'
</select>
</div>
</div>
</div>

';

?>

<script>
function changelimit(){
var limit = jQuery(".change-limit").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&limit-records="+limit;

}
function changepage(){
var transpage = jQuery(".change-page").val();

window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&transpage="+transpage;

}
</script>

<?php


}
}?>