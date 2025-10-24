<?php
if(!defined('ABSPATH')){
   die();
}
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

function pagination_users_before($where=""){
	  
   global  $wpdb, $users;
  
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['userpage']) ? $_GET['userpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
      $table_name = $wpdb->prefix."users";
  if(!isset($_GET["orderpage"])){
   if(!isset($_GET["sortby"])){
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");
   }
   else{
      $sortby = $_GET["sortby"];
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY $sortby DESC LIMIT $start, $limit");
   }
  }else{
   $sort = strtoupper($_GET["orderpage"]);
   if(!isset($_GET["sortby"])){
   $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID $sort LIMIT $start, $limit");
   }
   else{
      $sortby = $_GET["sortby"];
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY $sortby $sort LIMIT $start, $limit");
   }
  }

      if($use == null){
       $users = "null";
      }
      else{
       $users = $use;


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
if(isset($_GET["userpage"]) && $_GET["userpage"] == $i){
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
  <span class="input-group-text">Order</span>

 <script> 
function changeord(sor){

   window.location = window.location.href+"&orderpage="+sor;
   
   }
</script>
<select class="change-order float-left" onchange="changeord(this.value)">
';
if(isset($_GET["orderpage"])){
if(strtolower($_GET["orderpage"]) == "asc"){
   echo'
   <option value="ASC" class="opt" disabled selected >ASC</option>
   <option value="DESC"  class="opt" >DESC</option>
   ';
}else{
   echo'
   <option value="DESC" class="opt" disabled selected >DESC</option>
   <option value="ASC"  class="opt" >ASC</option>
   '; 
}
}else{
   echo'
   <option value="DESC" class="opt" disabled selected >DESC</option>
   <option value="ASC"  class="opt" >ASC</option>
   '; 
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
<input class="form-control border-end-0 border rounded-pill search-user" type="search" placeholder="search" id="example-search-input">
<span class="input-group-append">
   <button onclick="searchuser(jQuery(\'.search-user\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
       <i class="fa fa-search"></i>
   </button>
</span>
</div>
<script>
function searchuser(val){
location.href = "'.$_SERVER["REQUEST_URI"].'&user_id="+val;
}
</script>
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
var subpage = "<?php echo $_GET["subpage"];?>";
var userpage = "<?php echo $_GET["userpage"];?>";

window.location = "?page=vtupanel&adminpage=users&subpage="+subpage+"&userpage="+userpage+"&limit-records="+limit;

}
function changepage(){
var userpage = jQuery(".change-page").val();
var subpage = "<?php echo $_GET["subpage"];?>";
var limit = "<?php echo $_GET["limit-records"];?>";

window.location = "?page=vtupanel&adminpage=users&subpage="+subpage+"&userpage="+userpage+"&limit-records="+limit;

}

</script>

<?php


}
}




function pagination_kyc_before($where=""){
	  
   global  $wpdb, $users;
  
  
  $limit = isset($_REQUEST["limit-records"]) ? $_REQUEST["limit-records"] : 10;
  $page = isset($_GET['userpage']) ? $_GET['userpage'] : 1;
  $start = ($page - 1) * $limit;
  
  
  if($where == "bio"){
      $table_name = $wpdb->prefix."vp_profile";
  }else{
      $table_name = $wpdb->prefix."vp_kyc";
  }
  
      $use = $wpdb->get_results("SELECT * FROM  $table_name $where ORDER BY ID DESC LIMIT $start, $limit");

      if($use == null){
       $users = "null";
      }
      else{
       $users = $use;


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
if(isset($_GET["userpage"]) && $_GET["userpage"] == $i){
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
var subpage = "<?php echo $_GET["subpage"];?>";
var userpage = "<?php echo $_GET["userpage"];?>";

window.location = "?page=vtupanel&adminpage=users&subpage="+subpage+"&userpage="+userpage+"&limit-records="+limit;

}
function changepage(){
var userpage = jQuery(".change-page").val();
var subpage = "<?php echo $_GET["subpage"];?>";
var limit = "<?php echo $_GET["limit-records"];?>";

window.location = "?page=vtupanel&adminpage=users&subpage="+subpage+"&userpage="+userpage+"&limit-records="+limit;

}
</script>

<?php


}
}?>