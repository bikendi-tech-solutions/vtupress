<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/admin/pages/history/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

?>

<div class="row table-responsive">

    <div class="col-12">
    <link
      rel="stylesheet"
      type="text/css"
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/extra-libs/multicheck/multicheck.css"
    />
    <link
      href="<?php echo esc_url(plugins_url("vtupress/admin")); ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
      rel="stylesheet"
    />
<div class="card">
                <div class="card-body">
                  <h5 class="card-title">Successful NIN Verifications</h5>

                  <?php

if(!isset($_GET["trans_id"])){
    pagination_history_before("vp_verifications","approved","AND type = 'nin'");
}
elseif(empty($_GET["trans_id"])){
  pagination_history_before("vp_verifications","approved","AND type = 'nin'");
}
else{
  if(is_numeric($_GET["trans_id"]) && strlen($_GET["trans_id"]) != 10 && strlen($_GET["trans_id"]) != 11 ){
    $id = $_GET["trans_id"];
    pagination_history_before("vp_verifications","approved","AND id = '$id' AND type = 'nin'");
}
elseif(is_numeric($_GET["trans_id"]) && strlen($_GET["trans_id"]) == 10 || strlen($_GET["trans_id"]) == 11 ){
  $id = $_GET["trans_id"];
  pagination_history_before("vp_verifications","approved","AND value = '$id' AND type = 'nin'");
}
    else{
        $id = $_GET["trans_id"];
        pagination_history_before("vp_verifications","approved","AND type = 'nin'");
    }
}


?>

                  <div class="table-responsive">
                    <table
                      id="zero_config"
                      class="table table-striped table-bordered"
                    >
                      <thead>
                      <tr>
<th scope='col' class=''>ID</th>
<th scope='col' class=''>UserName</th>
<th scope='col' class=''>UserID</th>
<th scope='col' class=''>Type</th>
<th scope='col' class=''>Value</th>
<th scope='col' class=''>Charge</th>
<th scope='col' class=''>Bal. Before</th>
<th scope='col' class=''>Bal. Now</th>
<th scope='col' class=''>Photo</th>
<th scope='col' class=''>firstName</th>
<th scope='col' class=''>lastName</th>
<th scope='col' class=''>middleName</th>
<th scope='col' class=''>Phone</th>
<th scope='col' class=''>Email</th>
<th scope='col' class=''>Dob</th>
<th scope='col' class=''>Gender</th>
<th scope='col' class=''>LGA</th>
<th scope='col' class=''>State Of O.</th>
<th scope='col' class=''>LGA Of O.</th>
<th scope='col' class=''>Addr.</th>
</tr>
                      </thead>
                      <tbody>
                   
                      <?php


global $transactions;
if($transactions == "null"){
?>
    <tr  class="text-center">
    <td colspan="8">No NIN Verification Found</td>
    </tr>
<?php
}else{
            $option_array = json_decode(get_option("vp_options"),true);



foreach($transactions as $result){

    $dusername = get_userdata($result->user_id)->user_login;
   (isset(json_decode($result->vDatas)->data))? $verify_data = json_decode($result->vDatas)->data :  $verify_data = "" ;

  // $photo = vp_getvalue($verify_data->photo);
   $accountImage = "data:image/jpeg;base64,".$verify_data->photo;

    echo"
    <tr>
    <th scope='row'>".$result->id."</th>
    <th scope='row'>".$dusername."</th>
    <th scope='row'>".$result->user_id."</th>
    <td>".$result->type."</td>
    <td>".$result->value."</td>
    <td>".$result->fund_amount."</td>
    <td>".$result->before_amount."</td>
    <td>".$result->now_amount."</td>
    <td><img src='".$accountImage."' width='75' height='75' /></td>
    <td>".$verify_data->firstName."</td>
    <td>".$verify_data->lastName."</td>
    <td>".$verify_data->middleName."</td>
    <td>".$verify_data->phone."</td>
    <td>".$verify_data->email."</td>
    <td>".$verify_data->birthdate."</td>
    <td>".$verify_data->gender."</td>
    <td>".$verify_data->lgaOfResidence."</td>
    <td>".$verify_data->stateOfOrigin."</td>
    <td>".$verify_data->lgaOfOrigin."</td>
    <td>".$verify_data->residentialAddress."</td>
    <td>".$result->the_time."</td>
  </tr>
    ";
    
}

}
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>


<th scope='col' class=''>ID</th>
<th scope='col' class=''>UserName</th>
<th scope='col' class=''>UserID</th>
<th scope='col' class=''>Type</th>
<th scope='col' class=''>Value</th>
<th scope='col' class=''>Charge</th>
<th scope='col' class=''>Bal. Before</th>
<th scope='col' class=''>Bal. Now</th>
<th scope='col' class=''>Photo</th>
<th scope='col' class=''>firstName</th>
<th scope='col' class=''>lastName</th>
<th scope='col' class=''>middleName</th>
<th scope='col' class=''>Phone</th>
<th scope='col' class=''>Email</th>
<th scope='col' class=''>Dob</th>
<th scope='col' class=''>Gender</th>
<th scope='col' class=''>LGA</th>
<th scope='col' class=''>State Of O.</th>
<th scope='col' class=''>LGA Of O.</th>
<th scope='col' class=''>Addr.</th>

</tr>
                      </tfoot>
                    </table>

                  </div>
                </div>
              </div>
</div>


</div>