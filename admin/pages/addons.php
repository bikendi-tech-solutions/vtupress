<?php
//here
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
<div class="container-fluid license-container">
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
                  <h5 class="card-title">Upgarde Your Package
                    <?php
                    if(vp_getoption("vprun") != "block" ){
                    ?>
                  <span class="btn btn-sm btn-success rounded-circle w-20 h-20" stype="width:20px !important; height:20px !important;"></span>
                  <?php
                    }
                    else{
                  ?>
                  <span class="btn btn-sm btn-danger rounded-circle w-20 h-20"  stype="width:20px !important; height:20px !important;"></span>
                    <?php
                    }
                  ?>
                
                </h5> 
                  <div class="table-responsive">
<div class="p-4">

    <div class="row mb-3 p-4 border border-secondary">
            <div class="col col-1">
                <li class="fas fa-info-circle align-middle"></li>
            </div>
            <div class="col col-11">
  Only Premium and Lifetime Users have access to all the addons here!
        </div>
    </div>


<div class="row">

<div class="col">

<?php
function is_vp_plugin_installed( $slug ) {
  if ( ! function_exists( 'get_plugins' )){
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }
  $all_plugins = get_plugins();
   
  if ( !empty( $all_plugins[$slug] ) ) {
    return true;
  } else {
    return false;
  }
}


	
$url = "https://vtupress.com/codex.php";

$http_args = array(
  'headers' => array(
  'cache-control' => 'no-cache',
  'content-type' => 'application/json',
  'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:100.0) Gecko/20100101 Firefox/100.0'
  ),
  'timeout' => 120,
  'sslverify' => false);

$files =  wp_remote_retrieve_body(wp_remote_get($url, $http_args));


$file = json_decode($files,true);

$path = get_plugin_data(ABSPATH ."wp-content/plugins/vtupress/vtupress.php");
$version = $path["Version"];
?>

<div class="container">


  <div class="row about-vtupress-div p-4">
  <div class="col">

    <div class="row">
      <div class="col">
    <figure class="text-center">
  <blockquote class="blockquote">
    <h1>v <?php echo $path["Version"];?></h2>
  </blockquote>
  <figcaption class="blockquote-footer">
    Beta <cite title="Source Title">In vtuPress</cite>
  </figcaption>
</figure>
    </div>
    </div>



  </div>
  </div>

  <div class="row update-vtupress-div">
    <div class="col-12">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Current Version</th>
            <th scope="col">Documentation</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
		
		<?php
		
		foreach($file as $key => $value){
			
	if(!is_vp_plugin_installed($value['slug'])){
				$installed = true;
			$path = ABSPATH."/wp-content/plugins/".$value["slug"];
			$path = get_plugin_data($path);
			
			if(!empty($path["Version"])){
			$version = $path["Version"];
			}else{
			$version = "---";	
			}	
?>

          <tr>
            <th scope="row"><?php echo $value['name'];?></th>
            <td><?php echo $value['description'];?></td>
            <td><?php echo $version;?></td>
            <td><a href="<?php echo $value['documentation'];?>">Link</a> </td>
            <td>

				<form targert="_self" method="post" class="do_this<?php echo $key;?>">
				<input type="text" class="btn btn-danger install visually-hidden link<?php echo $key;?>" value="<?php echo $value['plugin_link'];?>" name="link">
				<input type="text" class="btn btn-danger install visually-hidden slug<?php echo $key;?>" value="<?php echo $value['slug'];?>" name="slug">
				<input type="button" class="btn btn-danger install vpaction<?php echo $key;?>" value="Install" name="vpaction">
				</form>
        
            </td>		
					<script>
jQuery(document).ready(function(){jQuery("#cover-spin").hide()});
jQuery(".do_this<?php echo $key;?> .vpaction<?php echo $key;?>").on("click", function(){

jQuery("#cover-spin").show();

var obj = {};


obj["vpaction"] = jQuery(".vpaction<?php echo $key;?>").val();
obj["link"] = jQuery(".link<?php echo $key;?>").val();
obj["slug"] = jQuery(".slug<?php echo $key;?>").val();
obj["spraycode"] = "<?php echo vp_getoption("spraycode");?>";

jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/install.php'));?>',
  data: obj,
 dataType: 'text',
  'cache': false,
  "async": true,
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
  title: "Error",
  text: msg,
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
	  jQuery("#cover-spin").hide();
	  var text = data;
	 var val = text.includes("Downloading");
	 var val2 = text.includes("Version");
	 var val3 = text.includes("version");
        if(data == "100" || data == "" || val == true || val2 == true || val3 == true){
		  swal({
  title: "Successful",
  text: "Thanks",
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
	swal({
  buttons: {
    cancel: "Why?",
    defeat: "Okay",
  },
  title: "Not Successful",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data, {
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

      <div id="cover-spin" >
	  
	  </div>




</div>
  </div>



</div>





                  </div>
                </div>
              </div>
</div>


</div>
</div>