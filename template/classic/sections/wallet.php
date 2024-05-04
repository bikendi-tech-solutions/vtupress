<?php

if(isset($_GET["vend"]) && $_GET["vend"]=="wallet"){

  $array["showbtn"] = "";
  $array["showmodal"] = "";
  function banksbtn(){
    global $array;
    if(!empty($array["showbtn"])){
      $msg = "";
    }else{
      $msg = "active";
      $array["showbtn"] = "active";
    }

    return $msg;
  }

  function banksmodal(){
    global $array;
    if(!empty($array["showmodal"])){
      $msg = "";
    }else{
      $msg = "show active";
      $array["showmodal"] = "show active";
    }

    return $msg;
  }

  function accountNumber($aza, $generate){
    $aza = strtolower($aza);

    if(emppty($aza) || $aza == "false" || $aza == "null"){
      echo "<span class='btn btn-primary generate_account' for='$generate'><i class='fa fa-history'></i> Generate Account Number</span>";
    }
    else{
      echo $aza;
    }
  }

			?>
<script>
jQuery("body").ready(function(){
		jQuery("#airtimehist").show();
		jQuery("#datahist").hide();
		jQuery("#cablehist").hide();
		jQuery("#billhist").hide();
});
</script>
<?php

$user_email = get_userdata($id)->user_email;

$bvn = vp_getuser($id,"myBvn",true);
$nin = vp_getuser($id,"myNin",true);
if(vp_getoption('enable_monnify') == "yes"  || vp_getoption('enable_ncwallet') == "yes"  || vp_getoption('enablesquadco') == "yes"  || vp_getoption('enablevpay') == "yes"  || vp_getoption('enablekuda') == "yes" && ($bvn != 'false' || $nin != 'false' || vp_getoption('enablevpay') == "yes" ) && (!empty($bvn) || !empty($nin) ||  vp_getoption('enablevpay') == "yes" ) && (mb_strlen($bvn) > 10 || mb_strlen($nin) > 10 || vp_getoption('enablevpay') == "yes" )){

  
  if(vp_getoption("charge_method") == "fixed"){
   $chargef =  "₦".floatval(vp_getoption("charge_back"));
   }
   else{
    $chargef =  floatval(vp_getoption("charge_back"))."%";
   }
  
   

?>
<div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingZero">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseZero" aria-expanded="false" aria-controls="flush-collapseZero">
       Automated Funding
      </button>
    </h2>
    <div id="flush-collapseZero" class="accordion-collapse collapse" aria-labelledby="flush-headingZero" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body dark-white">
			
				<style>
/* Shoutout to Maite Rosalie for the gold svg gradient which can be seen here below. */

/* https://codepen.io/maiterosalie/pen/ppRRLV?q=gold+gradient&limit=all&type=type-pens */

.Wrap {
  display: flex;
  justify-content: center;
  align-items: center;
   background: #f4f6f9;
  font-family: 'Roboto', sans-serif;
  font-weight: 400;
}

.Wrap .Base {
  background: #ccc;
  height: 100%;
  width: 100%;
  border-radius: 15px;
}

.Wrap .Inner-wrap {
  background-color: #0c0014;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25' viewBox='0 0 1600 800'%3E%3Cg %3E%3Cpolygon fill='%230d1838' points='1600%2C160 0%2C460 0%2C350 1600%2C50'/%3E%3Cpolygon fill='%230e315d' points='1600%2C260 0%2C560 0%2C450 1600%2C150'/%3E%3Cpolygon fill='%230f4981' points='1600%2C360 0%2C660 0%2C550 1600%2C250'/%3E%3Cpolygon fill='%231062a6' points='1600%2C460 0%2C760 0%2C650 1600%2C350'/%3E%3Cpolygon fill='%23117aca' points='1600%2C800 0%2C800 0%2C750 1600%2C450'/%3E%3C/g%3E%3C/svg%3E");
  background-size: auto 147%;
  background-position: center;
  position: relative;
  height: 100%;
  width: 100%;
  border-radius: 13px;
  box-sizing: border-box;
  color: #fff;
}

.Wrap p {
  margin: 0;
  font-size: 2em;
}

/* Controls top right logo */

.Wrap .Logo {
  position: absolute;
  height: 80px;
  width: 80px;
  right: 0;
  top: 0;
  padding: inherit;
  fill: #117aca;
}

/* Controls chip icon */

.Wrap .Chip {
  height: 40px;
  margin: 20px 0 25px 0;
}

.Wrap .gold path{
  fill: url(#gold-gradient);
}

.Wrap svg {
  display: block;
}

/* Controls name size */

.Wrap .Logo-name {
  transform: scale(.5);
  margin-left: -75px;
}

.Wrap .Card-number p {
  text-align: center;
}

.Wrap .Card-number {
  margin-top: -25px;
  display: flex;
  justify-content: center;
  color: rgba(255, 255, 255, 0.9);
}

.Wrap ul {
  padding: 0;
}

.Wrap ul li {
  list-style: none;
  float: left;
  margin: 0px 10px;
  font-size: 2.2em;
}

.Wrap #first-li {
  margin-left: 0;
}

.Wrap #last-li {
  margin-right: 0;
}

.Wrap .Expire {
  font-size: .75em;
  text-align: center;
}

.Wrap .Expire h4 {
  font-weight: 400;
  color: #aaa;
  margin: 0;
/*   word-spacing: 9999999px; */
  text-transform: uppercase;
}

.Expire p {
  font-size: 1.55em;
  color: rgba(255, 255, 255, 0.9);
}

.Wrap .Name h3 {
  position: relative;
  bottom: 0;
  text-align:center;
  text-transform: uppercase;
  font-weight: 400;
  font-size: 1.35em;
  color: rgba(255, 255, 255, 0.85);
}

.Wrap .Visa {
  width: 115px;
  position: relative;
  right: 0;
}

</style>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">


<div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <?php if(vp_getoption('enablesquadco') == "yes"  && vp_getoption("vtupress_custom_gtbank") == "yes"){
                    
                    $squad = "GTBank";
                    $squadAccountName = vp_getuser($id,"squadAccountName");
                    $squadAccountNumber = vp_getuser($id,"squadAccountNumber");

                    if(vp_getoption("gtb_charge_method") == "fixed"){
                      $gtb_chargef =  "₦".floatval(vp_getoption("gtb_charge_back"));
                      }
                      else{
                       $gtb_chargef =  floatval(vp_getoption("gtb_charge_back"))."%";
                      }
                     
   
                      
                  ?>
                    

                  <li class="nav-item">
                    <a
                      class="nav-link <?php echo banksbtn();?>"
                      data-bs-toggle="tab"
                      href="#squad"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $squad;?></span></a
                    >
                  </li>



                  <?php } 
                
                if(vp_getoption('enablevpay') == "yes"  && vp_getoption("vtupress_custom_vpay") == "yes"){
                    
                  $vpay = "Vpay / Vfd";
                  $vpayAccountName = vp_getuser($id,"vpayAccountName");
                  $vpayAccountNumber = vp_getuser($id,"vpayAccountNumber");

                  if(vp_getoption("vpay_charge_method") == "fixed"){
                    $vpay_chargef =  "₦".floatval(vp_getoption("vpay_charge_back"));
                    }
                    else{
                     $vpay_chargef =  floatval(vp_getoption("vpay_charge_back"))."%";
                    }
                   
                    
                ?>
                  

                <li class="nav-item">
                  <a
                    class="nav-link <?php echo banksbtn();?>"
                    data-bs-toggle="tab"
                    href="#vpay"
                    role="tab"
                    ><span class="hidden-sm-up"></span>
                    <span class="hidden-xs-down"><?php echo $vpay;?></span></a
                  >
                </li>



                <?php }
                                if(vp_getoption('enable_ncwallet') == "yes"  && vp_getoption("vtupress_custom_ncwallet") == "yes"){
                    
                                  $ncwallet = "Safehaven";
                                  $ncwallet_AccountName = vp_getuser($id,"ncwallet_accountname");
                                  $ncwallet_AccountNumber = vp_getuser($id,"ncwallet_accountnumber");
                
                                  if(vp_getoption("ncwallet_charge_method") == "fixed"){
                                      $ncwallet_chargef =  "₦".floatval(vp_getoption("ncwallet_charge_back"));
                                    }
                                    else{
                                     $ncwallet_chargef =  floatval(vp_getoption("ncwallet_charge_back"))."%";
                                    }
                                   
                                    
                                ?>
                                  
                
                                <li class="nav-item">
                                  <a
                                    class="nav-link <?php echo banksbtn();?>"
                                    data-bs-toggle="tab"
                                    href="#ncwallet"
                                    role="tab"
                                    ><span class="hidden-sm-up"></span>
                                    <span class="hidden-xs-down"><?php echo $ncwallet;?></span></a
                                  >
                                </li>
                
                
                
                    <?php } 
                  if(vp_getoption('enablekuda') == "yes"  && vp_getoption("vtupress_custom_kuda") == "yes"){
    
                    $kuda = "Kuda";
                    $kudaAccountName = vp_getuser($id,"kudaAccountName");
                    $kudaAccountNumber = vp_getuser($id,"kudaAccountNumber");

                    if(vp_getoption("kuda_charge_method") == "fixed"){
                      $kuda_chargef =  "₦".floatval(vp_getoption("kuda_charge_back"));
                      }
                      else{
                       $kuda_chargef =  floatval(vp_getoption("kuda_charge_back"))."%";
                      }
                     
   
                      
                  ?>
                    
  
                  <li class="nav-item">
                    <a
                      class="nav-link <?php echo banksbtn();?>"
                      data-bs-toggle="tab"
                      href="#kuda"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $kuda;?></span></a
                    >
                  </li>
  
  
  
                  <?php } 
                  
                  if(vp_getoption('enable_monnify') == "yes"){?>
                  <li class="nav-item <?php if(stripos("mon",$bank_name2) === false){echo "d-none";} ?>">
                    <a
                      class="nav-link <?php echo banksbtn();?>"
                      data-bs-toggle="tab"
                      href="#home"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name2;?></span></a
                    >
                  </li>
                  <li class="nav-item  <?php if(stripos("mon",$bank_name1) === false){echo "d-none";} ?>">
                    <a
                      class="nav-link <?php echo banksbtn();?>"
                      data-bs-toggle="tab"
                      href="#profile"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name1;?></span></a
                    >
                  </li>
                  <li class="nav-item  <?php if(stripos("mon",$bank_name) === false){echo "d-none";} ?>">
                    <a
                      class="nav-link <?php echo banksbtn();?>"
                      data-bs-toggle="tab"
                      href="#messages"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name;?></span></a
                    >
                  </li>
                  <?php } ?>
                </ul>

                
                <!-- Tab panes -->
                <div class="tab-content tabcontent-border">

                <!--GTBANK -->
                <?php if(vp_getoption('enablevpay') == "yes"  && vp_getoption("vtupress_custom_vpay") == "yes"){?>

                <div class="tab-pane <?php echo banksmodal();?>" id="vpay" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->
  <div class="Wrap mb-2 cdebit-card   position-relative">
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
VPAY / Vfd
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($vpayAccountNumber,"vpay");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $vpayAccountName;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $vpay_chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


                    <!------------------>
                    </div>
                  </div>

  <?php }

if(vp_getoption('enable_ncwallet') == "yes"  && vp_getoption("vtupress_custom_ncwallet") == "yes"){?>

  <div class="tab-pane <?php echo banksmodal();?>" id="ncwallet" role="tabpanel">
      <div class="p-md-20">

      <!-------------CONTENT----------->
<div class="Wrap mb-2 cdebit-card   position-relative">
<div class="Base">
<div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
Safehaven
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($ncwallet_AccountNumber,"ncwallet");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $ncwallet_AccountName;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $ncwallet_chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


      <!------------------>
      </div>
    </div>

<?php }
if(vp_getoption('enablesquadco') == "yes"  && vp_getoption("vtupress_custom_gtbank") == "yes"){?>

<div class="tab-pane <?php echo banksmodal();?>" id="squad" role="tabpanel">
    <div class="p-md-20">

    <!-------------CONTENT----------->
<div class="Wrap mb-2 cdebit-card   position-relative">
<div class="Base">
<div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
GTBANK
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($squadAccountNumber,"gtb");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $squadAccountName;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $gtb_chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


    <!------------------>
    </div>
  </div>

<?php } 

if(vp_getoption('enablekuda') == "yes"  && vp_getoption("vtupress_custom_kuda") == "yes"){?>

  <div class="tab-pane <?php echo banksmodal();?>" id="kuda" role="tabpanel">
      <div class="p-md-20">
  
      <!-------------CONTENT----------->
  <div class="Wrap mb-2 cdebit-card   position-relative">
  <div class="Base">
  <div class="Inner-wrap">
  
  <div class=" container text-white p-4 roundeds">
  
  <div class="row mb-3">
  <div class="col Logo-name fs-3">
  KUDA
  </div>
  </div>
  
  <div class="row mb-3">
  <div class="col card-number text-center">
  <p><?php accountNumber($kudaAccountNumber,"kuda");?></p>
  </div>
  </div>
  
  <div class="row mb-3">
  <div class="col Name white  text-center">
  <p><?php echo $kudaAccountName;?></p>
  </div>
  </div>
  
  
  <div class="row">
  <div class="col fs-5">
  VISA
  </div>
  <div class="col flex justify-content-end  fs-5">
  <?php echo $kuda_chargef;?> Charge Applied
  </div>
  </div>
  
  </div>
  
  </div>
  </div>
  </div>
  
  
      <!------------------>
      </div>
    </div>
  
  <?php } 
  
  if(vp_getoption('enable_monnify') == "yes"){?>



                <!--END OF GTBANK -->

                  <div class="tab-pane <?php echo banksmodal();?> " id="home" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->
  <div class="Wrap mb-2 cdebit-card   position-relative">
 <!-- <div class="position-absolute bg bg-primary w-100 h-100">

  

</div>-->
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name2;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($account_number2,"monnify");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name2;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


                    <!------------------>
                    </div>
                  </div>
                  <div class="tab-pane <?php echo banksmodal();?>" id="profile" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->

                    <div class="Wrap mb-2 cdebit-card   position-relative">
 <!-- <div class="position-absolute bg bg-primary w-100 h-100">

  

</div>-->
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name1;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($account_number1,"monnify");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name1;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>




                    <!------------------>
                    </div>
                  </div>
                  <div class="tab-pane <?php echo banksmodal();?>" id="messages" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->


                    <div class="Wrap mb-2 cdebit-card   position-relative">
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php accountNumber($account_number,"monnify");?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


                    <!------------------>
                    </div>
                  </div>

                  <?php } ?>


                </div>
              </div>

	 
		</div>
    </div>
  </div>
 <?php
}

if(vp_getoption("allow_card_method") == "yes"){
?>

  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
        Card Payment & Payment Gateway
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body dark-white">
<?php
echo'
	 		<style>
		  .side-wallet-w{background-color: white !important;
    padding: 20px !important;
    text-align: center !important;
    border-radius: 10px !important;
	border:2px solid purple;
		  }
		  
		</style>
		
<div id="side-wallet-w" class="side-wallet-w bg-gray-600 ">
<lead class="white">Current Balance : <span class="user-balance">'.$bal.'</span></lead>

<div class="fund-self-wallet">
<h4 class="white">Fund Account</h4>
<form method="post" class="cardPayment" action="/wp-content/plugins/vtupress/pay.php"> 
<label class="form-label white">Amount</label><br>
<input type="number" name="amount" class="form-control mb-2 user_amount" required><br>

<label class="form-label white" >Choose Gateway</label><br>

<select class="paymentchoice form-control mb-2"  name="paymentchoice" required>
<option value="" selected>Select Gateway</option>
';

if(vp_getoption('enablesquadco') == "yes"){
  ?>
  <option value="squadco">SquadCo</option>
  <?php
}
if(vp_getoption('enable_paystack') == "yes"){
  ?>
<option value="paystack">Paystack</option>
<?php
}
if(vp_getoption('enable_monnify') == "yes"){
  ?>
<option value="monnify">Monnify</option>
<?php
}
echo'
</select>


<div class="input-group" id="basic-addon1">

<span class="input-group-text dcharge">Charge In <b class="methodinai"></b></span><br>

<input type="number" name="charge" class="form-control mb-2 charge_back_value" readonly value="0">
</div>
<input style="background-color:white;" type="hidden" name="vpemail" value="'.$user_email.'" class="user_email form-control">
<input type="hidden" name="tcode" value="wallet" >
<input type="hidden" name="userid" value="'.$id.'">
<input type="button" name="pay" value="Fund Wallet" formaction="/wp-content/plugins/vtupress/pay.php" style="background-color:#9c27b0; color:white; padding:10px; border-radius:10px; border:0;" class="mb-2 form-control p-3 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow  submit-form  "><br>
<input type="hidden" name="amounte" value="1">
<input type="hidden" id="id" name="id" value="'.uniqid("VTU-",false).'"><br>
<input type="hidden" value="'.$sec.'" name="secret"><br>
<input type="hidden" id="url1" name="url1" value="'.vp_option_array($option_array,'siteurl')."/wp-content/plugins/vtupress/process.php".'">
</form>
</div>
</div>


<script>
jQuery(".paymentchoice").on("change",function(){

var dchoice = jQuery(".paymentchoice").val();

switch(dchoice){

    case"paystack":
      var chg = parseFloat('.vp_option_array($option_array,'paystack_charge_back').');
      
';
if(vp_getoption("paystack_charge_method") == "fixed"){
	?>
jQuery(".dcharge .methodinai").text("₦");
<?php
}
else{
		?>
jQuery(".dcharge .methodinai").text("%");

<?php
}
echo'

    break;
    case"monnify":
      var chg = parseFloat('.vp_option_array($option_array,'charge_back').');
      
      ';
      if(vp_getoption("charge_method") == "fixed"){
        ?>
      jQuery(".dcharge .methodinai").text("₦");
      <?php
      }
      else{
          ?>
      jQuery(".dcharge .methodinai").text("%");
      
      <?php
      }
      echo'

    break;
    case"squadco":
      var chg = parseFloat('.vp_option_array($option_array,'gtb_charge_back').');
      
      ';
      if(vp_getoption("gtb_charge_method") == "fixed"){
        ?>
      jQuery(".dcharge .methodinai").text("₦");
      <?php
      }
      else{
          ?>
      jQuery(".dcharge .methodinai").text("%");
      
      <?php
      }
      echo'

    break;

}


jQuery(".charge_back_value").val(chg);

});
</script>



';

  ?>
<script src="https://checkout.squadco.com/widget/squad.min.js"></script> 
<script>
jQuery('.cardPayment input[type=button]').on('click',function (event) {
 
     //   event.preventDefault(); // Prevent the default form submission

        var the_choice = jQuery(".paymentchoice").val();

        switch(the_choice){
          case"paystack":
           // alert("here in "+the_choice);
           if("<?php echo vp_getoption('enable_paystack');?>" == "yes"){
            jQuery(".submit-form").attr("type","submit");
            jQuery(".submit-form").click();
            //jQuery(".cardPayment").submit();
           }
           else{
            alert("Can't use paystack now");
           }

          break;
          case"monnify":
           // alert("here in "+the_choice);
           if("<?php echo vp_getoption('enable_monnify');?>" == "yes"){
            jQuery(".submit-form").attr("type","submit");
            jQuery(".submit-form").click();
           }
           else{
            alert("Can't use monnify now");
           }

          break;
          case"squadco":
           // alert("here in "+the_choice);
           jQuery(".submit-form").attr("type","button");
           // jQuery(".submit-form").click();
           
if("<?php echo vp_getoption('enablesquadco');?>" == "yes"){
            var publicKey = "<?php echo vp_getoption("squad_public");?>";
            var email = jQuery(".user_email").val();
            var amount = jQuery(".user_amount").val();
          //  transaction_Successful();
            function transaction_Successful(){
    console.log("Transaction successful");
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });

      Toast.fire({
        icon: 'success',
        title: 'You\'d be redirected in 5 Seconds!"'
      });

     jQuery(".swal2-container").css({"z-index":"999999999"});

     setTimeout(function() {
        // Code to be executed after the delay
        //console.log("Delayed code executed after 5000 sec");
       location.reload();
    }, 5000);

}
            function SquadPay() {

var obj = {};
obj["webhook_for"] = "invoice";
obj["invoice_id"] = "$invoice_id";

const squadInstance = new squad({
  onClose: () => console.log("Widget closed"),
  onLoad: () => console.log("Widget Loaded Successfully"),
  onSuccess: () =>  transaction_Successful(),
  key: publicKey,
  //Change key (test_pk_sample-public-key-1) to the key on your Squad Dashboard
  email: email,
  amount: amount * 100,
  //Enter amount in Naira or Dollar (Base value Kobo/cent already multiplied by 100)
  currency_code: "NGN"
});
squadInstance.setup();
squadInstance.open();

}

SquadPay();


}else{
  alert("Can't use squadco for now");
}
          break;
          default: alert("Choose a valid gateway");
        }

        // You can perform any additional tasks here before submitting the form
        // For example, you can validate user input, send an AJAX request, etc.

        // To submit the form, you can use the .submit() method
         // This triggers the form submission
    });
    </script>
  <?php

echo'


	';?> 
	 
		</div>
    </div>
  </div>
 
<?php
}
?>

 <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
        Manual Bank Transfer
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body dark-white">
	  
      <div style="text-align:center; background-color: #6c6cd426;"><?php echo vp_option_array($option_array,'manual_funding');?></div>

	  </div>
    </div>
  </div>
 
 <?php
 if(vp_option_array($option_array,"airtime_to_cash") == "yes" || vp_option_array($option_array,"airtime_to_wallet") == "yes" && vp_option_array($option_array,"resell") == "yes"){
?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
        Airtime Conversion
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body dark-white">
	<div class="container-md mt-3">
    <div class="p-3" style="border: 1px solid grey; border-radius: 5px;">
	    <div class="mb-2">
			<label class="form-label">Conversion</label>
			<select class="conversion form-select" name="conversion">
			<option value="none">---Select---</option>
			<?php
			if(vp_option_array($option_array,"airtime_to_wallet") == "yes"){
				?>
			<option value="wallet">Airtime To Wallet</option>
			<?php
			}
			if(vp_option_array($option_array,"airtime_to_cash") == "yes"){
			?>
			<option value="cash">Airtime To Cash</option>
			<?php
			}
			?>
			</select>
			<label class="form-label">Network</label>
			<select class="network form-select" name="network">
			<option value="none">---Select---</option>
			<?php
			if(!empty(vp_option_array($option_array,"mtn_airtime")) && vp_option_array($option_array,"mtn_airtime") != "08012346789"){
				?>
				<option value="mtn">MTN</option>
				<?php
			}
			if(!empty(vp_option_array($option_array,"glo_airtime")) && vp_option_array($option_array,"glo_airtime") != "08012346789"){
				?>
				<option value="glo">GLO</option>
				<?php
			}
			if(!empty(vp_option_array($option_array,"airtel_airtime")) && vp_option_array($option_array,"airtel_airtime") != "08012346789"){
				?>
				<option value="airtel">AIRTEL</option>
				<?php
			}
			if(!empty(vp_option_array($option_array,"9mobile_airtime")) && vp_option_array($option_array,"9mobile_airtime") != "08012346789"){
				?>
				<option value="9mobile">9MOBILE</option>
				<?php
			}
				?>
			</select>
			<label class="form-label">Amount</label>
			<input type="number"  name="amount" class="amount form-control"value="">
			<div class="bank_div">
			<label class="form-label">Account Details</label>
			<textarea class="bank form-control" name="bank"></textarea>
			</div>
			<label class="form-label">To Be Transfered From</label>
			<input type="number"  name="transfer_from" class="transfer_from form-control" value="" placeholder="Phone Number">
			<label class="form-label">Pay To</label>
			<input type="text"  name="pay_to" class="pay_to form-control" readOnly value="">
			<label class="form-label">Charge</label>
			<div class="input-group">
			<input type="number" name="pay_charge" class="pay_charge form-control" readOnly value="0">
			<span class="input-group-text">%</span>
			</div>
			<label class="form-label">You'll Get</label>
			<input type="text" name="pay_get" class="pay_get form-control" readOnly value="">
			<input type="button" name="convert_it" value="Convert" class="form-control mt-2 convert_it w-full p-3 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow text-light">
		</div>
		
<script>

var convert = jQuery("select.conversion").val();
		jQuery(".conversion").val("none");
		jQuery(".network").val("none");
if(convert == "wallet"){
	jQuery(".network").val("none");
		jQuery(".bank_div").hide();
}
else if(convert == "cash"){
	jQuery(".network").val("none");
	jQuery(".bank_div").show();
}
else{
	jQuery(".pay_charge").val("0");
	jQuery(".network").val("none");
		jQuery(".bank_div").hide();
}


jQuery("select.conversion").on("change",function(){
	var convert = jQuery("select.conversion").val();
	
if(convert == "wallet"){
//jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'airtime_to_wallet_charge'));?>");
		jQuery(".network").val("none");
		/*
	var minus = (parseInt(jQuery(".amount").val()) * parseInt(jQuery(".pay_charge").val()))/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
		jQuery(".bank_div").hide();
	jQuery(".pay_get").val(charge);
	*/
}
if(convert == "cash"){
//jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'airtime_to_cash_charge'));?>");
		jQuery(".network").val("none");
			jQuery(".bank_div").show();
			/*
	var minus = (parseInt(jQuery(".amount").val()) * parseInt(jQuery(".pay_charge").val()))/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
	*/
}
	
});

jQuery("select.network").on("change",function(){
	var network = jQuery("select.network").val();
	var conversion = jQuery("select.conversion").val();
	switch(conversion){
		case"wallet":
if(network == "mtn"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'mtn_airtime'));?>");
	jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'airtime_to_wallet_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'airtime_to_wallet_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "glo"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'glo_airtime'));?>");
			jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'gairtime_to_wallet_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'gairtime_to_wallet_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "airtel"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'airtel_airtime'));?>");
			jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'aairtime_to_wallet_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'aairtime_to_wallet_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "9mobile"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'9mobile_airtime'));?>");
			jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'9airtime_to_wallet_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'9airtime_to_wallet_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

break;
		case"cash":
if(network == "mtn"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'mtn_airtime'));?>");
			jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'airtime_to_cash_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'airtime_to_cash_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "glo"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'glo_airtime'));?>");
				jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'gairtime_to_cash_charge'));?>");	
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'gairtime_to_cash_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "airtel"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'airtel_airtime'));?>");
				jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'aairtime_to_cash_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'aairtime_to_cash_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

if(network == "9mobile"){
	jQuery("input.pay_to").val("<?php echo intval(vp_option_array($option_array,'9mobile_airtime'));?>");
	jQuery(".pay_charge").val("<?php echo intval(vp_option_array($option_array,'9airtime_to_cash_charge'));?>");
	var minus = (parseInt(jQuery(".amount").val()) * <?php echo intval(vp_option_array($option_array,'9airtime_to_cash_charge'));?>)/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
}

break;


	}
	
});

jQuery(".amount").on("change",function(){
	var minus = (parseInt(jQuery(".amount").val()) * parseInt(jQuery(".pay_charge").val()))/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
});



jQuery(".convert_it").click(function(){

	var minus = (parseInt(jQuery(".amount").val()) * parseInt(jQuery(".pay_charge").val()))/100;
	var charge = parseInt(jQuery(".amount").val()) - minus;
	
	jQuery(".pay_get").val(charge);
	
jQuery("#cover-spin").show();
	
var obj = {};
obj["convert_it"] = "airtime";
obj["conversion"] = jQuery(".conversion").val();
obj["network"] = jQuery(".network").val();
obj["pay_to"] = jQuery(".pay_to").val();
obj["pay_charge"] = jQuery(".pay_charge").val();
obj["pay_get"] = jQuery(".pay_get").val();
obj["amount"] = jQuery(".amount").val();
obj["bank"] = jQuery(".bank").val();
obj["from"] = jQuery(".transfer_from").val();
jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/vend.php'));?>',
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
  title: msg,
  text: jqXHR.responseText,
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
        if(data == "100"){
		  swal({
  title: "Conversion Submitted",
  text: "We'll Approve Your Conversion After Confirmation",
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
  title: "Submission Failed",
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
	
	</div>
	</div>
	  
	  </div>
    </div>
  </div>
<?php
 }
 if(vp_option_array($option_array,"enable_coupon") == "yes" && vp_option_array($option_array,"resell") == "yes"){
?>
  <div class="accordion-item">
    <h2 class="accordion-header" id="flush-headingFour">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
        Coupon Funding
      </button>
    </h2>
    <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body dark-white">
	<div class="container-md mt-3">
    <div class="p-3" style="border: 1px solid grey; border-radius: 5px;">
	    <div class="mb-2">
		<label class="form-label">Coupon Code</label>
			<input type="text" name="coupon_code" class="coupon_code form-control">
			<input type="button" name="run_coupon" value="Redeem Code" class="form-control run_coupon btn btn-secondary w-full p-2 text-xs font-bold text-white uppercase bg-indigo-600 rounded shadow text-light">
		</div>
		
<script>


jQuery(".run_coupon").click(function(){
	
jQuery("#cover-spin").show();
	
var obj = {};
obj["run_coupon"] = jQuery(".coupon_code").val();
jQuery.ajax({
  url: '<?php echo esc_url(plugins_url('vtupress/admin/pages/settings/saves/coupon.php'));?>',
  data: obj,
 dataType: 'json',
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
  title: msg,
  text: jqXHR.responseText,
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
        if(data.status == "100"){
		  swal({
  title: "Redeemed",
  text: data.message,
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
  title: "Redeem Failed",
  text: "Click \'Why\' To See reason",
  icon: "error",
})
.then((value) => {
  switch (value) {
 
    case "defeat":
      break;
    default:
      swal(data.message, {
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
	
	</div>
	</div>
	  
	  </div>
    </div>
  </div>
<?php
 }
 ?>
  
</div>


<?php

}
		
		
		?>