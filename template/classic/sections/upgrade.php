<?php


if(is_plugin_active('vprest/vprest.php') && vp_option_array($option_array,"resell") == "yes"){		
			if(isset($_GET["vend"]) && $_GET["vend"]=="upgrade"){
				echo'
	<style>
		  .side-upgrade-w{background-color: white !important;
    padding: 20px !important;
    text-align: center !important;
    border-radius: 10px !important;
		  }
		</style>
		

				<div id="side-upgrade-w" style="background-color:white; padding:20px; text-align:center; border-radius:10px;">';
	do_action("vpaccount");
	
		echo'
		</div>
			
		';
			}
		}
		
		?>