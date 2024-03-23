<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/users/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


function pagination_no_before($where=""){
	  
    global  $wpdb, $messages;
   
   
   $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
   $type = isset($_REQUEST["type"])? "WHERE type = '".$_REQUEST["type"]."' $where " : "WHERE type != 'all' $where";
   $page = isset($_GET['userpage']) ? $_GET['userpage'] : 1;
   $start = ($page - 1) * $limit;
   
   $table_name = $wpdb->prefix."vp_notifications";
    
   $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $type");
   $pages = ceil( $num  / $limit );

$stat = $start;


   
       $use = $wpdb->get_results("SELECT * FROM  $table_name $type ORDER BY ID DESC LIMIT $stat, $limit");
 
       if($use == null){
         $messages = "null";
       }
       else{
         $messages = $use;
 
       
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
 if(isset($_GET["userpage"]) && $_GET["userpage"] == $i ){
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
 
 
 </div>
 
 ';
 
 ?>
 <script>
 function changepage(){
 var userpage = jQuery(".change-page").val();
 
 window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&userpage="+userpage;
 }
 </script>
 
 <?php
 
 
 }
 }


 
function pagination_no_user_before($where=""){
	  
    global  $wpdb, $messages;
   
   
   $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
   $page = isset($_GET['userpage']) ? $_GET['userpage'] : 1;
   $start = ($page - 1) * $limit;
   
   
       $table_name = $wpdb->prefix."vp_chat";
    
       $num = $wpdb->get_var("SELECT count(id) AS id FROM $table_name $where");
       $pages = ceil( $num  / $limit );

if(isset($_GET['userpage'])){
    $stat = $start;
    $selec = "vtu";
}
else{

  if($pages != "0"){
    for($i = 1; $i<= $pages; $i++){
    $stat = ($i - 1) * $limit;
    $selec = $i;
    }
  }
  else{
    $stat =   $page; 
    $selec = "vtu";
  }
}
   
       $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID ASC LIMIT $stat, $limit");
 
       if($use == null){
         $messages = "null";
       }
       else{
         $messages = $use;
 
       
       $Previous = $page - 1;
       $Next = $page + 1;
       echo '
 
       <div class="row dark-white">
       <div class="col-12 col-md-4">
 <div class="input-group">
 <span class="input-group-text">Page</span>
 <select class="change-page dark float-left" onchange="changepage();">
         ';
 for($i = 1; $i<= $pages; $i++){
 if(isset($_GET["userpage"]) && $_GET["userpage"] == $i || $selec == $i){
    echo'
    <option value="'.$i.'" disabled selected  class="opt dark" >'.$i.'<option>
    ';
     }elseif(empty($i)){
 
     }
     else{
        echo'
        <option value="'.$i.'" class="opt dark" >'.$i.'<option>
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
 <input class="form-control border-end-0 border rounded-pill search-user" type="search" placeholder="search keyword" id="example-search-input">
 <span class="input-group-append">
    <button onclick="searchuser(jQuery(\'.search-user\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
        <i class="fa fa-search"></i>
    </button>
 </span>
 </div>
 <script>
 function searchuser(val){
 location.href = "'.$_SERVER["REQUEST_URI"].'&message="+val;
 }
 </script>
 </div>
 
 
 </div>
 
 ';
 
 ?>
 <script>
 function changepage(){
 var userpage = jQuery(".change-page").val();
 
 window.location = "<?php echo $_SERVER["REQUEST_URI"];?>&userpage="+userpage;
 }
 </script>
 
 <?php
 
 
 }
 }
?>