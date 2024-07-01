<?php
if(!defined('ABSPATH')){
die();
}

$option_array = json_decode(get_option("vp_options"),true);
add_action("vtupress_actions","0");
if(get_option("vtupress_actions") == "0"){

vp_addoption('manual_funding', "no message");
vp_addoption("show_services_bonus", "yes");
vp_addoption("refbo",0);
vp_addoption("cb",0);
vp_addoption("vpap","no");
vp_addoption("vpwalm","No message");


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



vp_updateoption("cablename0","GOTV");
vp_updateoption("cablename1","DSTV");
vp_updateoption("cablename2","STARTIMES");

update_option("vtupress_actions","1");
}


?>