<?php
header("Access-Control-Allow-Origin: 'self'");
/*
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if (isset($_SERVER['HTTP_REFERER'])) {
	$referer = $_SERVER['HTTP_REFERER'];
$nm = $_SERVER['SERVER_NAME'];
	if(!preg_match("/$nm\/wp-admin/",$referer)) {
		die("REF ENT PERM");
	}

}else{
	die("BAD");
}

if(isset($_REQUEST["export"])){
  if(is_multisite()){
    global $wpdb, $blog_id;
    $user_query = new WP_User_Query( array( 'blog_id' => $blog_id, 'order' => 'ASC' ) );
    $resultfad = $user_query->get_results();
  }
  else{
    global $wpdb;
    $table_name = $wpdb->prefix.'users';
    $resultfad = $wpdb->get_results($wpdb->prepare("SELECT * FROM  $table_name ORDER BY %s ASC", 'ID'));
  }
  
$array = [];
foreach($resultfad as $user){
	$id = $user->ID;
	$name = get_user_by("ID",$user->ID)->user_login;
	$balance = vp_getuser($id, "vp_bal", true);
	$phone = vp_getuser($id, "vp_phone", true);
	$pin = vp_getuser($id, "vp_pin", true);
	$plan = vp_getuser($id, "vr_plan", true);

$data  = "$name -  $phone - $pin - $balance - $plan";

$array[] = $data;
	
}

$datas = [];
$datas[] = $array;

function array_to_csv_download($array, $filename = "export.csv", $delimiter=";", $enclosure=' ') {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    
    foreach ($array as $line) {
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter,$enclosure); 
        
            
        }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: text/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}

array_to_csv_download($datas);

die("100");

}


else{
?>
	<form action=<?php echo $_SERVER["PHP_SELF"]; ?> method="post" enctype="multipart/form-data">
<input type="file" name="vtupress" id="fileSelect">
<input type="submit" class="btn btn-primary importu" value="Import User Datas">
</form>
<?php
if(isset($_FILES["vtupress"])){
if($_FILES["vtupress"]["error"] > 0){
    echo "Error: " . $_FILES["vtupress"]["error"] . "<br>";
}
else{
    /*
    echo "File Name: " . $_FILES["vtupress"]["name"] . "<br>";
    echo "File Type: " . $_FILES["vtupress"]["type"] . "<br>";
    echo "File Size: " . ($_FILES["vtupress"]["size"] / 1024) . " KB<br>";
    echo "Stored in: " . $_FILES["vtupress"]["tmp_name"];
    */







  /*
$csv = array_map('str_getcsv', file($_FILES["vtupress"]["tmp_name"]));

//print_r($csv);
$explode_rows = explode(";",$csv[0][0]);

foreach($explode_rows as $mthis){
  $data = explode("-",$mthis);
  $id = get_user_by("login",$data[0])->ID;
//echo $id."<br>";
vp_updateuser($id , "vp_bal", trim($data[3]));
vp_updateuser($id , "vp_phone", trim($data[1]));
vp_updateuser($id , "vp_pin", trim($data[2]));
vp_updateuser($id , "vr_plan", trim($data[4]));
    
}
die("100");
}
}
}

?>
*/