<?php
if(is_plugin_active('bcmv/bcmv.php')){

		if(isset($_GET["vend"]) && $_GET["vend"]=="cable" && vp_option_array($option_array,"setcable") == "checked"){
			echo'
				<!-- Tv -->

<div class="mb-2 row" style="height:fit-content;">
       <span style="float:left;" class="col"> Wallet: '.$bal.'</span>
<span style="float:right;" class="col"><a href="?vend=wallet" style="text-decoration:none; float:right;" class="white btn-primary btn-sm">Fund Wallet</a></span>

</div>
		<div class="user-vends">
		'.do_shortcode('[vtupress_cable][/vtupress_cable]').'
			</div>
		

		
		<!-- Cable End -->
		
		
			';
	
		}
	
		
}

?>