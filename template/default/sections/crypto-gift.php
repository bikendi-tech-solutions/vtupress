<?php	
if(vp_option_array($option_array,'resell') == "yes" ){
if(isset($_GET["vend"]) && $_GET["vend"]=="gift-card"  && vp_option_array($option_array,"allow_cards") == "yes"){
?>
<div class="container">
<div class="row">
<div class="col">
<div class="card" style="width: 18rem;">
  <img src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/sellgc.jpg';?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Redeem Your GiftCard</h5>
    <p class="card-text">We are trusted by others who have traded their cards with us! redeem your cards for cash</p>
    <a href="whatsapp://send?phone=234<?php echo vp_option_array($option_array,"vp_whatsapp");?>&amp;text=Hi,+I+want+to+redeem+Gift+cards" class="btn btn-primary">Redeem Now</a>
  </div>
</div>

</div>

<div class="col">
<div class="card" style="width: 18rem;">
  <img src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/gc.jpg';?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Buy GiftCard</h5>
    <p class="card-text">We sell Gift Cards at an affordable price! Try Us Now!</p>
    <a href="whatsapp://send?phone=234<?php echo vp_option_array($option_array,"vp_whatsapp");?>&amp;text=Hi,+I+want+to+buy+Gift+Cards" class="btn btn-primary">Buy Gift Cards</a>
  </div>
</div>

</div>


</div>
</div>

<?php
				
}
				
if(isset($_GET["vend"]) && $_GET["vend"]=="crypto" && vp_option_array($option_array,"allow_crypto") == "yes"){
?>
<div class="container">
<div class="row">
<div class="col">
<div class="card" style="width: 18rem;">
  <img src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/sellcrypto.png';?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Sell Your Crypto Coins</h5>
    <p class="card-text">We are trusted by others who have traded their coins with us! Trade your Crypto Coins Now</p>
    <a href="whatsapp://send?phone=234<?php echo vp_option_array($option_array,"vp_whatsapp");?>&amp;text=Hi,+I+want+to+sell+crypto+coins" class="btn btn-primary">Sell Crypto Coins</a>
  </div>
</div>

</div>

<div class="col">
<div class="card" style="width: 18rem;">
  <img src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/images/crypto.jpg';?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title">Buy Crypto Currency</h5>
    <p class="card-text">We sell series of Crypto currencies at an affordable price! Try Us Now!</p>
    <a href="whatsapp://send?phone=234<?php echo vp_option_array($option_array,"vp_whatsapp");?>&amp;text=Hi,+I+want+to+buy+crypto+coins" class="btn btn-primary">Buy Crypto Coins</a>
  </div>
</div>

</div>


</div>
</div>

<?php
				
}
				
				
				
			}


?>