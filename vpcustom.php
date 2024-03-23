<?php
if(!defined('ABSPATH')){
die();
}

$option_array = json_decode(get_option("vp_options"),true);
add_action("vtupress_actions","0");
if(get_option("vtupress_actions") == "0"){
vp_addoption("getts","none");
vp_addoption("usernameattr","username");
vp_addoption("passwordattr","password");
vp_addoption("username","");
vp_addoption("password","");
vp_addoption("cpara1attr","");
vp_addoption("cpara1","");
vp_addoption("cpara2attr","");
vp_addoption("cpara2","");
vp_addoption("cpara3attr","");
vp_addoption("cpara3","");
vp_addoption("cpara4attr","");
vp_addoption("cpara4","");
vp_addoption("airend", "");
vp_addoption("dataend", "");
vp_addoption("cableend", "");
vp_addoption("billend", "");
vp_addoption("baseurl","");
vp_addoption("camountattr","amount");
vp_addoption("cnetworkattr", "network");
vp_addoption("cphoneattr","phone");
vp_addoption("cmtnattr", "mtn");
vp_addoption("cgloattr", "glo");
vp_addoption("ciucattr", "iuc");
vp_addoption("cmeterattr", "meterno");
vp_addoption("c9mobileattr","9mobile");
vp_addoption("cairtelattr", "airtel");
vp_addoption("balanceend","");
vp_addoption("successcode","");
vp_addoption("successvalue","");
vp_addoption("balanceattr","");
vp_addoption("cvariationattr","");
vp_addoption("ccvariationattr","");
vp_addoption("ctypeattr","");
vp_addoption("cbvariationattr","");
vp_addoption("btypeattr","");
vp_addoption("bmethod","get");
vp_addoption("billthod","get");
vp_addoption("hvalue","");
vp_addoption("head1","");
vp_addoption("balt","one");
vp_addoption("bdata1","");
vp_addoption("bdata2","");
vp_addoption("airdis",0);
vp_addoption("addpost","");
vp_addoption("baddpost","");
vp_addoption("sme_mtn_balance","*131*4#");
vp_addoption("sme_glo_balance","*127*0#");
vp_addoption("sme_airtel_balance","*140#");
vp_addoption("sme_9mobile_balance","*232#");
vp_addoption("direct_mtn_balance","*131*4#");
vp_addoption("direct_glo_balance","*127*0#");
vp_addoption("direct_airtel_balance","*140#");
vp_addoption("direct_9mobile_balance","*232#");
vp_addoption("corporate_mtn_balance","*131*4#");
vp_addoption("corporate_glo_balance","*127*0#");
vp_addoption("corporate_airtel_balance","*140#");
vp_addoption("corporate_9mobile_balance","*232#");
vp_addoption("sme_visible_networks","mtn,glo,9mobile,airtel");
vp_addoption("corporate_visible_networks","mtn,glo,9mobile,airtel");
vp_addoption("direct_visible_networks","mtn,glo,9mobile,airtel");
vp_addoption("cable_charge","0");
vp_addoption("bill_charge","0");
for($i=0; $i<=3; $i++){
vp_addoption("cablename".$i, "");
vp_addoption("cableid".$i, "");
}

for($i=0; $i<=1; $i++){
vp_addoption("billname".$i, "");
vp_addoption("billid".$i, "");
}

for($i=0; $i<=35; $i++){
vp_addoption("cbill".$i, "");
vp_addoption("cbilln".$i, "");
}


for($i=0; $i<=10; $i++){
vp_addoption("cdata".$i, "");
vp_addoption("cdatan".$i, "");
vp_addoption("cdatap".$i, "");
}

for($i=0; $i<=10; $i++){
vp_addoption("acdata".$i, "");
vp_addoption("acdatan".$i, "");
vp_addoption("acdatap".$i, "");
}

for($i=0; $i<=10; $i++){
vp_addoption("9cdata".$i, "");
vp_addoption("9cdatan".$i, "");
vp_addoption("9cdatap".$i, "");
}

for($i=0; $i<=10; $i++){
vp_addoption("gcdata".$i, "");
vp_addoption("gcdatan".$i, "");
vp_addoption("gcdatap".$i, "");
}

for($i=0; $i<=35; $i++){
vp_addoption("ccable".$i, "");
vp_addoption("ccablen".$i, "");
vp_addoption("ccablep".$i, "");
}
for($vtuaddheaders=1; $vtuaddheaders<=4; $vtuaddheaders++){
vp_addoption("vtuaddheaders".$vtuaddheaders," ");
vp_addoption("vtuaddvalue".$vtuaddheaders," ");
}
for($shareaddheaders=1; $shareaddheaders<=4; $shareaddheaders++){
vp_addoption("shareaddheaders".$shareaddheaders," ");
vp_addoption("shareaddvalue".$shareaddheaders," ");
}
for($awufaddheaders=1; $awufaddheaders<=4; $awufaddheaders++){
vp_addoption("awufaddheaders".$awufaddheaders," ");
vp_addoption("awufaddvalue".$awufaddheaders," ");
}
for($smeaddheaders=1; $smeaddheaders<=4; $smeaddheaders++){
vp_addoption("smeaddheaders".$smeaddheaders," ");
vp_addoption("smeaddvalue".$smeaddheaders," ");
}
for($directaddheaders=1; $directaddheaders<=4; $directaddheaders++){
vp_addoption("directaddheaders".$directaddheaders," ");
vp_addoption("directaddvalue".$directaddheaders," ");
}
for($corporateaddheaders=1; $corporateaddheaders<=4; $corporateaddheaders++){
vp_addoption("corporateaddheaders".$corporateaddheaders," ");
vp_addoption("corporateaddvalue".$corporateaddheaders," ");
}
for($cableaddheaders=1; $cableaddheaders<=4; $cableaddheaders++){
vp_addoption("cableaddheaders".$cableaddheaders," ");
vp_addoption("cableaddvalue".$cableaddheaders," ");
}
for($billaddheaders=1; $billaddheaders<=4; $billaddheaders++){
vp_addoption("billaddheaders".$billaddheaders," ");
vp_addoption("billaddvalue".$billaddheaders," ");
}
vp_addoption("vtu_airtime_platform","Select");
vp_addoption("share_airtime_platform","Select");
vp_addoption("awuf_airtime_platform","Select");
vp_addoption("sme_data_platform","Select");
vp_addoption("direct_data_platform","Select");
vp_addoption("corporate_data_platform","Select");
vp_addoption("cable_platform","Select");
vp_addoption("bill_platform","Select");
vp_addoption("sms_platform","Select");
vp_addoption("epin_platform","Select");
vp_addoption("airtimebaseurl","");
vp_addoption("airtimeendpoint","");
vp_addoption("airtimerequest","");
vp_addoption("airtimerequesttext","");
vp_addoption("airtimesuccesscode","");
vp_addoption("airtimesuccessvalue","");
vp_addoption("airtimesuccessvalue2","");
for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("airtimehead".$cheaders,"");
vp_addoption("airtimevalue".$cheaders,"");
}

vp_addoption("airtimeaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("airtimepostdata".$cpost,"");
vp_addoption("airtimepostvalue".$cpost,"");
}

vp_addoption("airtimeamountattribute","");
vp_addoption("airtimephoneattribute","");
vp_addoption("airtimenetworkattribute","");
vp_addoption("airtimemtn","");
vp_addoption("airtimeglo","");
vp_addoption("airtime9mobile","");
vp_addoption("airtimeairtel","");
//////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////SHARE AND SELL AIRTIME////////////////////////////////////
vp_addoption("sairtimebaseurl","");
vp_addoption("sairtimeendpoint","");
vp_addoption("sairtimerequest","");
vp_addoption("sairtimerequesttext","");
vp_addoption("sairtimesuccesscode","");
vp_addoption("sairtimesuccessvalue","");
vp_addoption("sairtimesuccessvalue2","");

for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("sairtimehead".$cheaders,"");
vp_addoption("sairtimevalue".$cheaders,"");
}

vp_addoption("sairtimeaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("sairtimepostdata".$cpost,"");
vp_addoption("sairtimepostvalue".$cpost,"");
}

vp_addoption("sairtimeamountattribute","");
vp_addoption("sairtimephoneattribute","");
vp_addoption("sairtimenetworkattribute","");
vp_addoption("sairtimemtn","");
vp_addoption("sairtimeglo","");
vp_addoption("sairtime9mobile","");
vp_addoption("sairtimeairtel","");
//////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////AWFUF AIRTIME////////////////////////////////////
vp_addoption("wairtimebaseurl","");
vp_addoption("wairtimeendpoint","");
vp_addoption("wairtimerequest","");
vp_addoption("wairtimerequesttext","");
vp_addoption("wairtimesuccesscode","");
vp_addoption("wairtimesuccessvalue","");
vp_addoption("wairtimesuccessvalue2","");




for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("wairtimehead".$cheaders,"");
vp_addoption("wairtimevalue".$cheaders,"");
}

vp_addoption("wairtimeaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("wairtimepostdata".$cpost,"");
vp_addoption("wairtimepostvalue".$cpost,"");
}

vp_addoption("wairtimeamountattribute","");
vp_addoption("wairtimephoneattribute","");
vp_addoption("wairtimenetworkattribute","");
vp_addoption("wairtimemtn","");
vp_addoption("wairtimeglo","");
vp_addoption("wairtime9mobile","");
vp_addoption("wairtimeairtel","");
//////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////DATA////////////////////////////////////
vp_addoption("databaseurl","");
vp_addoption("dataendpoint","");
vp_addoption("datarequest","");
vp_addoption("datarequesttext","");
vp_addoption("datasuccesscode","");
vp_addoption("datasuccessvalue","");
vp_addoption("datasuccessvalue2","");
for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("datahead".$cheaders,"");
vp_addoption("datavalue".$cheaders,"");
}


vp_addoption("dataaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("datapostdata".$cpost,"");
vp_addoption("datapostvalue".$cpost,"");
}
vp_addoption("dataamountattribute","");
vp_addoption("cvariationattr","");
vp_addoption("dataphoneattribute","");
vp_addoption("datanetworkattribute","");

vp_addoption("datamtn","");
vp_addoption("dataglo","");
vp_addoption("data9mobile","");
vp_addoption("dataairtel","");



for($i=0; $i<=10; $i++){
vp_addoption("cdata".$i,"");
vp_addoption("cdatan".$i,"");
vp_addoption("cdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("acdata".$i,"");
vp_addoption("acdatan".$i,"");
vp_addoption("acdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("9cdata".$i,"");
vp_addoption("9cdatan".$i,"");
vp_addoption("9cdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("gcdata".$i,"");
vp_addoption("gcdatan".$i,"");
vp_addoption("gcdatap".$i,"");
}
//////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////DIRECT R DATA////////////////////////////////////
vp_addoption("rdatabaseurl","");
vp_addoption("rdataendpoint","");
vp_addoption("rdatarequest","");
vp_addoption("rdatarequesttext","");
vp_addoption("rdatasuccesscode","");
vp_addoption("rdatasuccessvalue","");
vp_addoption("rdatasuccessvalue2","");
vp_addoption("rdatamtn","");
vp_addoption("rdataglo","");
vp_addoption("rdata9mobile","");
vp_addoption("rdataairtel","");



for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("rdatahead".$cheaders,"");
vp_addoption("rdatavalue".$cheaders,"");
}


vp_addoption("rdataaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("rdatapostdata".$cpost,"");
vp_addoption("rdatapostvalue".$cpost,"");
}
vp_addoption("rdataamountattribute","");
vp_addoption("rcvariationattr","");
vp_addoption("rdataphoneattribute","");
vp_addoption("rdatanetworkattribute","");

for($i=0; $i<=10; $i++){
vp_addoption("rcdata".$i,"");
vp_addoption("rcdatan".$i,"");
vp_addoption("rcdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("racdata".$i,"");
vp_addoption("racdatan".$i,"");
vp_addoption("racdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("r9cdata".$i,"");
vp_addoption("r9cdatan".$i,"");
vp_addoption("r9cdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("rgcdata".$i,"");
vp_addoption("rgcdatan".$i,"");
vp_addoption("rgcdatap".$i,"");
}
//////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////CORPORATE R2 DATA////////////////////////////////////
vp_addoption("r2databaseurl","");
vp_addoption("r2dataendpoint","");
vp_addoption("r2datarequest","");
vp_addoption("r2datarequesttext","");
vp_addoption("r2datasuccesscode","");
vp_addoption("r2datasuccessvalue","");
vp_addoption("r2datasuccessvalue2","");

vp_addoption("r2datamtn","");
vp_addoption("r2dataglo","");
vp_addoption("r2data9mobile","");
vp_addoption("r2dataairtel","");



for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("r2datahead".$cheaders,"");
vp_addoption("r2datavalue".$cheaders,"");
}


vp_addoption("r2dataaddpost","");

for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("r2datapostdata".$cpost,"");
vp_addoption("r2datapostvalue".$cpost,"");
}
vp_addoption("r2dataamountattribute","");
vp_addoption("r2cvariationattr","");
vp_addoption("r2dataphoneattribute","");
vp_addoption("r2datanetworkattribute","");

for($i=0; $i<=10; $i++){
vp_addoption("r2cdata".$i,"");
vp_addoption("r2cdatan".$i,"");
vp_addoption("r2cdatap".$i,"");
}

for($i=0; $i<=10; $i++){
vp_addoption("r2acdata".$i,"");
vp_addoption("r2acdatan".$i,"");
vp_addoption("r2acdatap".$i,"");
}
for($i=0; $i<=10; $i++){
vp_addoption("r29cdata".$i,"");
vp_addoption("r29cdatan".$i,"");
vp_addoption("r29cdatap".$i,"");
}
for($i=0; $i<=10; $i++){
vp_addoption("r2gcdata".$i,"");
vp_addoption("r2gcdatan".$i,"");
vp_addoption("r2gcdatap".$i,"");
}
//////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////CABLE////////////////////////////////////
vp_addoption("cablebaseurl","");
vp_addoption("cableendpoint","");
vp_addoption("cablerequest","");
vp_addoption("cablerequesttext","");
vp_addoption("cablesuccesscode","");
vp_addoption("cablesuccessvalue","");
vp_addoption("cablesuccessvalue2","");
for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("cablehead".$cheaders,"");
vp_addoption("cablevalue".$cheaders,"");
}
vp_addoption("cableaddpost","");
for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("cablepostdata".$cpost,"");
vp_addoption("cablepostvalue".$cpost,"");
}
vp_addoption("cableamountattribute","amount");
vp_addoption("cablephoneattribute","phone");
vp_addoption("ccvariationattr","");
vp_addoption("ctypeattr","");
vp_addoption("ciucattr","");
for($j=0; $j<=3; $j++){
vp_addoption("cablename".$j,"");
vp_addoption("cableid".$j,"");
}
for($i=0; $i<=35; $i++){
vp_addoption("ccable".$i,"");
vp_addoption("ccablen".$i,"");
vp_addoption("ccablep".$i,"");
}
//////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////BILL////////////////////////////////////
vp_addoption("billbaseurl","");
vp_addoption("billendpoint","");
vp_addoption("billrequest","");
vp_addoption("billrequesttext","");
vp_addoption("billsuccesscode","");
vp_addoption("billsuccessvalue","");
vp_addoption("billsuccessvalue2","");
for($cheaders=1; $cheaders<=4; $cheaders++){
vp_addoption("billhead".$cheaders,"");
vp_addoption("billvalue".$cheaders,"");
}
vp_addoption("billaddpost","");
for($cpost=1; $cpost<=5; $cpost++){
vp_addoption("billpostdata".$cpost,"");
vp_addoption("billpostvalue".$cpost,"");
}
vp_addoption("billamountattribute","");
vp_addoption("billphoneattribute","");
vp_addoption("cbvariationattr","");
vp_addoption("btypeattr","");
vp_addoption("cmeterattr","");
for($j=0; $j<=3; $j++){
vp_addoption("billname".$j,"");
vp_addoption("billid".$j,"");
}
vp_addoption("vtu_info","Information will appear here after import");
vp_addoption("shared_info","Information Will appear here after import");
vp_addoption("awuf_info","Information will appear here after import");
vp_addoption("sme_info","Information will appear here after import");
vp_addoption("corporate_info","Information will appear here after import");
vp_addoption("direct_info","Information will appear here after import");
vp_addoption("cable_info","Information will appear here after import");
vp_addoption("bill_info","Information will appear here after import");
vp_addoption("setairtime","unchecked");
vp_addoption("setdata","unchecked");
vp_addoption("setcable","unchecked");
vp_addoption("setbill","unchecked");
vp_addoption("vtucontrol","unchecked");
vp_addoption("sharecontrol","unchecked");
vp_addoption("awufcontrol","unchecked");
vp_addoption("smecontrol","unchecked");
vp_addoption("directcontrol","unchecked");
vp_addoption("corporatecontrol","unchecked");
vp_addoption("cablecontrol","unchecked");
vp_addoption("billcontrol","unchecked");
vp_addoption("smscontrol","unchecked");
vp_addoption("epincontrol","unchecked");

update_option("vtupress_actions","1");
}


add_action('brequest_id',"request_id");
add_action('arequest_id',"request_id");
add_action('sarequest_id',"request_id");
add_action('warequest_id',"request_id");
add_action('r2request_id',"r2request_id");
add_action('sarequest_id',"request_id");
add_action('warequest_id',"request_id");
do_action("vpdropins");


vp_updateoption("cablename0","GOTV");
vp_updateoption("cablename1","DSTV");
vp_updateoption("cablename2","STARTIMES");

add_action("vpsubpages", "vtupress_vpcustomsh");
function vtupress_vpcustomsh(){
add_submenu_page("vtu-press","Gateway", "Gateway", "vtupress_access_gateway", "gateway", "vpcustoms");
}
/*
add_filter("customchoice", "vtupress_customchoice");
function vtupress_customchoice($cchoice){
return '
<option value="custom">Custom</option>
';
}
*/


	$option_array = json_decode(get_option("vp_options"),true);





add_action('request_id',"request_id");





add_action('rrequest_id',"rrequest_id");



add_action('crequest_id',"request_id");





function vpcustoms(){
	
	

if(get_option("vtupress_options2") != "2"){

vp_addoption('vturesponse_id','re-import');
vp_addoption('shareresponse_id','re-import');
vp_addoption('awufresponse_id','re-import');
vp_addoption('smeresponse_id','re-import');
vp_addoption('corporateresponse_id','re-import');
vp_addoption('directresponse_id','re-import');
vp_addoption('cableresponse_id','re-import');
vp_addoption('billresponse_id','re-import');


vp_addoption('allow_cards','no');
vp_addoption("allow_withdrawal","no");
vp_addoption("allow_to_bank","no");
vp_addoption("vtu_mad",0);
vp_addoption("vtu_aad",0);
vp_addoption("vtu_9ad",0);
vp_addoption("vtu_gad",0);

vp_addoption("share_mad",0);
vp_addoption("share_aad",0);
vp_addoption("share_9ad",0);
vp_addoption("share_gad",0);

vp_addoption("awuf_mad",0);
vp_addoption("awuf_aad",0);
vp_addoption("awuf_9ad",0);
vp_addoption("awuf_gad",0);


vp_addoption("sme_mdd",0);
vp_addoption("sme_add",0);
vp_addoption("sme_9dd",0);
vp_addoption("sme_gdd",0);

vp_addoption("direct_mdd",0);
vp_addoption("direct_add",0);
vp_addoption("direct_9dd",0);
vp_addoption("direct_gdd",0);

vp_addoption("corporate_mdd",0);
vp_addoption("corporate_add",0);
vp_addoption("corporate_9dd",0);
vp_addoption("corporate_gdd",0);

vp_addoption("enable_coupon", "no");
vp_addoption("airtime_to_wallet", "no");
vp_addoption("airtime_to_cash", "no");
vp_addoption("mtn_airtime", "08012346789");
vp_addoption("glo_airtime", "08012346789");
vp_addoption("airtel_airtime", "08012346789");
vp_addoption("9mobile_airtime", "08012346789");
vp_addoption("airtime_to_wallet_charge", "0");
vp_addoption("airtime_to_cash_charge", "0");


vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("charge_method", "fixed");
vp_addoption("show_notify", "no");
vp_addoption("http_redirect", "true");
vp_addoption("global_security", "enabled");
vp_addoption("secur_mod", "calm");
vp_addoption("vp_ips_ban", "0.0.0.0,");
vp_addoption("vp_users_ban", "anonymous,hackers,demo");
vp_addoption("access_website", "true");
vp_addoption("access_user_dashboard", "true");
vp_addoption("access_country", "true");


vp_addoption("discount_method","direct");
vp_addoption("charge_back",0);
vp_addoption("wallet_to_wallet","yes");

vp_addoption("showlicense","none");

vp_addoption("manual_funding","No Message");

vp_addoption("reb", "yes");
vp_addoption("ppub", "Your Paystack Public Key");
vp_addoption("psec", "Your Paystack Secret Key");
vp_addoption("paychoice", "flutterwave");
vp_addoption("menucolo", "#cee8b8");
vp_addoption("dashboardcolo", "#ffff00");
vp_addoption("buttoncolo", "#ffff00");
vp_addoption("headcolo", "#4CAF50");
vp_addoption("sucreg", "/vpaccount");
vp_addoption("cairtimeb", "1");
vp_addoption("cdatab", "1");
vp_addoption("cdatabd", "1");
vp_addoption("cdatabc", "1");

vp_addoption("ccableb", "1");
vp_addoption("cbillb", "1");
vp_addoption("rairtimeb", "1");

vp_addoption("rdatab", "1");
vp_addoption("rdatabd", "1");
vp_addoption("rdatabc", "1");

vp_addoption("rcableb", "1");
vp_addoption("rbillb", "1");
vp_addoption("monnifyapikey","Your API KEY");
vp_addoption("monnifysecretkey","Your SECRET KEY");
vp_addoption("monnifycontractcode","Your Contract Code");
vp_addoption("monnifytestmode","false");



vp_addoption("airtime1_response_format_text","JSON");
vp_addoption("airtime1_response_format","json");
vp_addoption("airtime2_response_format_text","JSON");
vp_addoption("airtime2_response_format","json");
vp_addoption("airtime3_response_format_text","JSON");
vp_addoption("airtime3_response_format","json");
vp_addoption("data1_response_format_text","JSON");
vp_addoption("data1_response_format","json");
vp_addoption("data2_response_format_text","JSON");
vp_addoption("data2_response_format","json");
vp_addoption("data3_response_format_text","JSON");
vp_addoption("data3_response_format","json");
vp_addoption("cable_response_format_text","JSON");
vp_addoption("cable_response_format","json");
vp_addoption("bill_response_format_text","JSON");
vp_addoption("bill_response_format","json");


vp_addoption("airtime_head","not_concatenated");
vp_addoption("airtime_head2","not_concatenated");
vp_addoption("airtime_head3","not_concatenated");
vp_addoption("data_head","not_concatenated");
vp_addoption("data_head2","not_concatenated");
vp_addoption("data_head3","not_concatenated");
vp_addoption("cable_head","not_concatenated");
vp_addoption("bill_head","not_concatenated");




//SME
vp_updateoption("api0", 1);
vp_updateoption("api1", 2);
vp_updateoption("api2", 3);
vp_updateoption("api3", 4);
vp_updateoption("api4", 5);
vp_updateoption("api5", 6);
vp_updateoption("api6", 7);
vp_updateoption("api7", 8);
vp_updateoption("api8", 9);
vp_updateoption("api9", 10);
vp_updateoption("api10", 11);

vp_updateoption("aapi0", 12);
vp_updateoption("aapi1", 13);
vp_updateoption("aapi2", 14);
vp_updateoption("aapi3", 15);
vp_updateoption("aapi4", 16);
vp_updateoption("aapi5", 17);
vp_updateoption("aapi6", 18);
vp_updateoption("aapi7", 19);
vp_updateoption("aapi8", 20);
vp_updateoption("aapi9", 21);
vp_updateoption("aapi10", 22);

vp_updateoption("9api0", 23);
vp_updateoption("9api1", 24);
vp_updateoption("9api2", 25);
vp_updateoption("9api3", 26);
vp_updateoption("9api4", 27);
vp_updateoption("9api5", 28);
vp_updateoption("9api6", 29);
vp_updateoption("9api7", 30);
vp_updateoption("9api8", 31);
vp_updateoption("9api9", 32);
vp_updateoption("9api10", 33);

vp_updateoption("gapi0", 34);
vp_updateoption("gapi1", 35);
vp_updateoption("gapi2", 36);
vp_updateoption("gapi3", 37);
vp_updateoption("gapi4", 38);
vp_updateoption("gapi5", 39);
vp_updateoption("gapi6", 40);
vp_updateoption("gapi7", 41);
vp_updateoption("gapi8", 42);
vp_updateoption("gapi9", 43);
vp_updateoption("gapi10", 44);
//END SME


//CORPORATE
vp_updateoption("api20", 45);
vp_updateoption("api21", 46);
vp_updateoption("api22", 47);
vp_updateoption("api23", 48);
vp_updateoption("api24", 49);
vp_updateoption("api25", 50);
vp_updateoption("api26", 51);
vp_updateoption("api27", 52);
vp_updateoption("api28", 53);
vp_updateoption("api29", 54);
vp_updateoption("api210", 55);

vp_updateoption("aapi20", 56);
vp_updateoption("aapi21", 57);
vp_updateoption("aapi22", 58);
vp_updateoption("aapi23", 59);
vp_updateoption("aapi24", 60);
vp_updateoption("aapi25", 61);
vp_updateoption("aapi26", 62);
vp_updateoption("aapi27", 63);
vp_updateoption("aapi28", 64);
vp_updateoption("aapi29", 65);
vp_updateoption("aapi210", 66);

vp_updateoption("9api20", 67);
vp_updateoption("9api21", 68);
vp_updateoption("9api22", 69);
vp_updateoption("9api23", 70);
vp_updateoption("9api24", 71);
vp_updateoption("9api25", 72);
vp_updateoption("9api26", 73);
vp_updateoption("9api27", 74);
vp_updateoption("9api28", 75);
vp_updateoption("9api29", 76);
vp_updateoption("9api210", 77);

vp_updateoption("gapi20", 78);
vp_updateoption("gapi21", 79);
vp_updateoption("gapi22", 80);
vp_updateoption("gapi23", 81);
vp_updateoption("gapi24", 82);
vp_updateoption("gapi25", 83);
vp_updateoption("gapi26", 84);
vp_updateoption("gapi27", 85);
vp_updateoption("gapi28", 86);
vp_updateoption("gapi29", 87);
vp_updateoption("gapi210", 88);
//END CORPORATE


//GIFTING
vp_updateoption("api30", 89);
vp_updateoption("api31", 90);
vp_updateoption("api32", 91);
vp_updateoption("api33", 92);
vp_updateoption("api34", 93);
vp_updateoption("api35", 94);
vp_updateoption("api36", 95);
vp_updateoption("api37", 96);
vp_updateoption("api38", 97);
vp_updateoption("api39", 98);
vp_updateoption("api310", 99);

vp_updateoption("aapi30", 100);
vp_updateoption("aapi31", 101);
vp_updateoption("aapi32", 102);
vp_updateoption("aapi33", 103);
vp_updateoption("aapi34", 104);
vp_updateoption("aapi35", 105);
vp_updateoption("aapi36", 106);
vp_updateoption("aapi37", 107);
vp_updateoption("aapi38", 108);
vp_updateoption("aapi39", 109);
vp_updateoption("aapi310", 110);

vp_updateoption("9api30", 111);
vp_updateoption("9api31", 112);
vp_updateoption("9api32", 113);
vp_updateoption("9api33", 114);
vp_updateoption("9api34", 115);
vp_updateoption("9api35", 116);
vp_updateoption("9api36", 117);
vp_updateoption("9api37", 118);
vp_updateoption("9api38", 119);
vp_updateoption("9api39", 120);
vp_updateoption("9api310", 121);

vp_updateoption("gloapi30", 122);
vp_updateoption("gloapi31", 123);
vp_updateoption("gloapi32", 124);
vp_updateoption("gloapi33", 125);
vp_updateoption("gloapi34", 126);
vp_updateoption("gloapi35", 127);
vp_updateoption("gloapi36", 128);
vp_updateoption("gloapi37", 129);
vp_updateoption("gloapi38", 130);
vp_updateoption("gloapi39", 131);
vp_updateoption("gloapi310", 132);
vp_addoption("vpdebug","no");
vp_addoption("checkbal","yes");
vp_addoption('actkey','vtu');
vp_addoption("formwidth",100);
vp_addoption('frmad','block');
vp_addoption('vpid','00');
vp_addoption('vprun','block');
vp_addoption('vpfback','#7FDBFF');
vp_addoption('vptxt','#85144b');
vp_addoption('vpsub','#2ECC40');
vp_addoption('fr', '#AAAAAA');
vp_addoption('see','#DDDDDD');
vp_addoption('seea','hidden');
vp_addoption('suc','/successful');
vp_addoption('fail','/failed');
vp_addoption('resell','no');
vp_updateoption('suc','/successful');


update_option("vtupress_options2","2");
}

$option_array = json_decode(get_option("vp_options"),true);




}

?>