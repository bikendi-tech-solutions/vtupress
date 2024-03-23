<?php	
if(isset($_GET["vend"]) && $_GET["vend"]=="bet" && vp_option_array($option_array,"betcontrol") == "checked"){
			echo'
			<!-- data -->
		<div id="side-data-w">
		Bet Funding<br>
<div class="mb-2 row" style="height:fit-content;">
       <span style="float:left;" class="col"> Wallet: '.$bal.'</span>
<span style="float:right;" class="col"><a href="?vend=wallet" style="text-decoration:none; float:right;" class="btn-primary btn-sm">Fund Wallet</a></span>

</div>
		<div class="user-vends">
		'.do_shortcode('[vtupress_bet][/vtupress_bet]').'
			</div>
		</div>
		
		<!-- Cable End -->
		
		
			';
}
	?>