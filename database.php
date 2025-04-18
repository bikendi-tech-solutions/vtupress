<?php
// if(!defined('ABSPATH')){
//     $pagePath = explode('/wp-content/', dirname(__FILE__));
//     include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
// }
// if(WP_DEBUG == false){
// error_reporting(0);	
// }
// include_once(ABSPATH."wp-load.php");
// include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
// include_once(ABSPATH .'wp-content/plugins/vtupress/database.php');




function create_s_transaction(){

  global $wpdb;
  $stable_name = $wpdb->prefix.'vp_auto_manual';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
  id int NOT NULL AUTO_INCREMENT,
  sessionId text ,
  user_id text,
  amount text,
  charge text,
  api_response text,
  the_time text,
  accountNumber text,
  status text,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);
  maybe_add_column($stable_name,"accountNumber","ALTER TABLE $stable_name ADD accountNumber text");


  global $wpdb;
  $stable_name = $wpdb->prefix.'vp_withdrawal';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
  id int NOT NULL AUTO_INCREMENT,
  name text ,
  description text ,
  amount text ,
  status text ,
  user_id int ,
  the_time text ,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);


  global $wpdb;
  $stable_name = $wpdb->prefix.'vp_membership_rule_stats';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
  id int NOT NULL AUTO_INCREMENT,
  ref text ,
  transaction_number text ,
  transaction_amount text ,
  user_id int ,
  start_count text ,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);



  vp_updateoption("vtupress_withdrawal","yes");



    global $wpdb;
    $stable_name = $wpdb->prefix.'vp_wallet';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
    id int NOT NULL AUTO_INCREMENT,
    type text ,
    name text ,
    description text ,
    fund_amount text ,
    before_amount text,
    now_amount text,
    user_id int ,
    the_time text ,
    sender text,
    status text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    maybe_add_column($stable_name,"sender","ALTER TABLE $stable_name ADD sender VARCHAR(255) NOT NULL DEFAULT 'sender' ");


    global $wpdb;
    $stable_name = $wpdb->prefix.'vp_coupon';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
    id int NOT NULL AUTO_INCREMENT,
    code text ,
    applicable_to text ,
    amount text ,
    used_by text,
    status text,
    the_time text ,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    global $wpdb;
    $stable_name = $wpdb->prefix.'vp_message';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $stable_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id text ,
    user_name text ,
    user_token text ,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);


    vp_updateoption('suc','successful');

    global $wpdb;
    $table_name = $wpdb->prefix.'vpwebhook';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    service text,
    service_id text,
    request_id text,
    response_id text,
    resp_log text,
    the_time text ,
    server_ip text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    global $wpdb;
    $table_name = $wpdb->prefix.'sairtime';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    name text ,
    email varchar(255),
    network text,
    phone text,
    bal_bf text,
    bal_nw text,
    amount text,
    resp_log text,
    user_id int,
    status text,
    the_time text,
    request_id text,
    response_id text,
    run_code text,
    via text,
    browser text,
    time_taken text,
    trans_type text,
    trans_method text,
    queried text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
//Default Datas to sairtime (s-airtime db)
function addsdata(){
      global $wpdb;
      $name='Vtupress Plugin';
      $email='vtupress.com@gmail.com';
      $network='mtn';
      $bal_bf ='0';
      $bal_nw ='0';
      $phone= '07049626922';
      $amount= '0';
      $tid = '1';
      $table_name = $wpdb->prefix.'sairtime';
      $wpdb->insert($table_name, array(
      'name'=> $name,
      'email'=> $email,
      'network' => $network,
      'phone' => $phone,
      'bal_bf' => $bal_bf,
      'bal_nw' => $bal_nw,
      'amount' => $amount,
      'resp_log' => "sample of successful airtime log",
      'user_id' => $tid,
      'status' => 'successful',
      'request_id' => '2022',
      'time_taken' => '0s',
      'browser' => 'CHROME',
      'via' => 'site',
      'trans_type' => 'vtu',
      'trans_method' => 'none',
      'queried' => '0',
      'the_time' => current_time('mysql', 1)
      ));
}
//create failed Airtime transaction db

function create_sd_transaction(){
    global $wpdb;
    $sd_name = $wpdb->prefix.'sdata';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL AUTO_INCREMENT,
    name text ,
    email varchar(255),
    plan text ,
    network text ,
    phone text ,
    bal_bf text,
    bal_nw text,
    amount text ,
    resp_log text ,
    user_id int ,
    status text ,
    the_time text ,
    request_id text ,
    via text ,
    browser text ,
    time_taken text ,
    trans_type text ,
    response_id text,
    run_code text,
    trans_method text ,
    queried text ,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

//Create sbet
function create_sb_transaction(){
      global $wpdb;
      $sd_name = $wpdb->prefix.'sbet';
      $charset_collate=$wpdb->get_charset_collate();
      $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
      id int NOT NULL AUTO_INCREMENT,
      name text ,
      email varchar(255),
      company text ,
      customerid text ,
      bal_bf text,
      bal_nw text,
      amount text ,
      resp_log text ,
      user_id int ,
      status text ,
      the_time text ,
      request_id text ,
      via text ,
      browser text ,
      time_taken text ,
      trans_type text ,
      response_id text,
      run_code text,
      trans_method text ,
      queried text ,
      PRIMARY KEY (id))$charset_collate;";
      require_once(ABSPATH.'wp-admin/includes/upgrade.php');
      dbDelta($sql);
  }

  //Default sbet
  function addsbdata(){
    global $wpdb;
    $sname='Vtupress Plugin';
    $semail='vtupress.com@gmail.com';
    $sphone= '07049626922';
    $bal_bf ='0';
    $bal_nw ='0';
    $samount= '0';
    $tid = '1';
    $sd_name = $wpdb->prefix.'sbet';
    $wpdb->insert($sd_name, array(
    'name'=> $sname,
    'email'=> $semail,
    'customerid' => "0000",
    'company' => "test",
    'bal_bf' => $bal_bf,
    'bal_nw' => $bal_nw,
    'amount' => $samount,
    'resp_log' => "sample of successful bet funding log",
    'user_id' => $tid,
    'status' => "successful",
    'request_id' => '2022',
    'time_taken' => '0s',
    'browser' => 'CHROME',
    'via' => 'site',
    'trans_type' => 'vtu',
    'trans_method' => 'none',
    'queried' => '0',
    'the_time' => current_time('mysql', 1)
    ));
    }

    
    //Default Datas to sdata (s-db)
    function addsddata(){
    global $wpdb;
    $sname='Vtupress Plugin';
    $semail='vtupress.com@gmail.com';
    $splan='MTN 500MB';
    $sphone= '07049626922';
    $bal_bf ='0';
    $bal_nw ='0';
    $samount= '0';
    $tid = '1';
    $sd_name = $wpdb->prefix.'sdata';
    $wpdb->insert($sd_name, array(
    'name'=> $sname,
    'email'=> $semail,
    'plan' => $splan,
    'phone' => $sphone,
    'bal_bf' => $bal_bf,
    'bal_nw' => $bal_nw,
    'amount' => $samount,
    'resp_log' => "sample of successful data log",
    'user_id' => $tid,
    'status' => "successful",
    'request_id' => '2022',
    'time_taken' => '0s',
    'browser' => 'CHROME',
    'via' => 'site',
    'trans_type' => 'vtu',
    'trans_method' => 'none',
    'queried' => '0',
    'the_time' => current_time('mysql', 1)
    ));
}


//create vtu choice db
function vtuchoice(){
  global $wpdb;
  $table_name = $wpdb->prefix.'vtuchoice';
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE IF NOT EXISTS $table_name(
  id int NOT NULL AUTO_INCREMENT,
  vtuchoice text ,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}
//create default data for vtuchoice
function vtuchoiced(){
  global $wpdb;
  $table_name = $wpdb->prefix.'vtuchoice';
  $data = array('vtuchoice' => 'custom');
  $wpdb->insert($table_name,$data);
}
do_action('vpdb');

function vtupress_create_message(){
  global $wpdb;
  $sd_name = $wpdb->prefix.'vp_chat';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
  id int NOT NULL  AUTO_INCREMENT,
  user_id int,
  name text,
  message text,
  type text,
  status text,
  attachment text,
  the_time text,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql); 
}



function vtupress_verification(){
    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_verifications';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL  AUTO_INCREMENT,
    user_id int,
    name text,
    card_type text,
    value text,
    type text,
    status text,
    fund_amount text,
    before_amount text,
    now_amount text,
    vDatas text,
    the_time text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
 }

function vtupress_create_notification(){
  global $wpdb;
  $sd_name = $wpdb->prefix.'vp_notifications';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
  id int NOT NULL AUTO_INCREMENT,
  user_id int,
  title text,
  type text,
  admin_link text,
  user_link text,
  status text,
  message text,
  the_time text,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql); 
}

function vtupress_create_profile(){
  global $wpdb;
  $sd_name = $wpdb->prefix.'vp_profile';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
  id int NOT NULL AUTO_INCREMENT,
  user_id int,
  photo_link text,
  the_time text,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql); 
}


function vtupress_create_trans_log(){
    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_transactions';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    name text,
    email text,
    service text,
    request_id text,
    bal_bf text,
    bal_nw text,
    recipient text,
    amount text,
    the_time text,
    status text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
 }

 function vtupress_create_monwebhook(){
  global $wpdb;
  $sd_name = $wpdb->prefix.'vp_wallet_webhook';
  $charset_collate=$wpdb->get_charset_collate();
  $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
  id int NOT NULL AUTO_INCREMENT,
  user_id int,
  gateway text,
  amount text,
  referrence text,
  status text,
  response text,
  the_time text,
  PRIMARY KEY (id))$charset_collate;";
  require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  dbDelta($sql); 
 }


//Default Datas to sdata (s-db)
function vtupress_add_message(){
    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_chat';
    $wpdb->insert($sd_name, array(
    'user_id'=> "1",
    'name' => 'Akor Victor',
    'message'=> "Welcome To VTUPRESS. It is a priviledge to add this wonderful MINI chat system!",
    'type' => "received",
    'status' => "unread",
    'attachment' => "none",
    'the_time' => date(current_time('mysql').' A')
    ));
}


function vtupress_db_man(){
    vp_addoption("vp_funds_fixed",0);
    vp_addoption("vp_trans_fixed",0);
    vp_addoption("vp_enable_registration","yes");
   
   global $wpdb;
   $user = $wpdb->prefix.'users';
   $airtime = $wpdb->prefix.'sairtime';
   $data = $wpdb->prefix.'sdata';
   

   
   maybe_add_column($user,"vp_ban", "ALTER TABLE $user ADD vp_ban text");
   maybe_add_column($user,"vp_bal", "ALTER TABLE $user ADD vp_bal text");
   
   $all_users = $wpdb->get_results("SELECT * FROM $user");
   
   foreach($all_users as $use){
   
    $id = $use->ID;
    $bal = vp_getuser($id, 'vp_bal', true);
    $access = vp_getuser($id, 'vp_user_access', true);
   
   $arr = ['vp_bal' => $bal, 'vp_ban' => $access ];
   $where = ['ID' => $id];
   $updated = $wpdb->update($user, $arr, $where);
   
   }
   
   maybe_add_column($airtime,"browser", "ALTER TABLE $airtime ADD browser text");
   maybe_add_column($airtime,"queried", "ALTER TABLE $airtime ADD queried text");
   maybe_add_column($airtime,"trans_type", "ALTER TABLE $airtime ADD trans_type text");
   maybe_add_column($airtime,"trans_method", "ALTER TABLE $airtime ADD trans_method text");
   maybe_add_column($airtime,"via", "ALTER TABLE $airtime ADD via text");
   maybe_add_column($airtime,"time_taken", "ALTER TABLE $airtime ADD time_taken text");
   maybe_add_column($airtime,"request_id", "ALTER TABLE $airtime ADD request_id text");
   maybe_add_column($airtime,"response_id", "ALTER TABLE $airtime ADD response_id text");
   maybe_add_column($airtime,"run_code", "ALTER TABLE $airtime ADD run_code text");
   $wpdb->query("ALTER TABLE $airtime MODIFY COLUMN phone text");
   $wpdb->query("ALTER TABLE $airtime MODIFY COLUMN the_time text");
   
   maybe_add_column($data,"browser", "ALTER TABLE $data ADD browser text");
   maybe_add_column($data,"queried", "ALTER TABLE $data ADD queried text");
   maybe_add_column($data,"trans_type", "ALTER TABLE $data ADD trans_type text");
   maybe_add_column($data,"trans_method", "ALTER TABLE $data ADD trans_method text");
   maybe_add_column($data,"via", "ALTER TABLE $data ADD via text");
   maybe_add_column($data,"time_taken", "ALTER TABLE $data ADD time_taken text");
   maybe_add_column($data,"request_id", "ALTER TABLE $data ADD request_id text");
   maybe_add_column($data,"response_id", "ALTER TABLE $data ADD response_id text");
   maybe_add_column($data,"run_code", "ALTER TABLE $data ADD run_code text");
   $wpdb->query("ALTER TABLE $data MODIFY COLUMN phone text");
   $wpdb->query("ALTER TABLE $data MODIFY COLUMN the_time text");
   
   if(is_plugin_active('bcmv/bcmv.php')){
    $cable = $wpdb->prefix.'scable';
    $bill = $wpdb->prefix.'sbill';
    $wpdb->query("ALTER TABLE $cable MODIFY COLUMN iucno text");
    $wpdb->query("ALTER TABLE $cable MODIFY COLUMN time text");
    $wpdb->query("ALTER TABLE $bill MODIFY COLUMN meterno text");
    $wpdb->query("ALTER TABLE $bill MODIFY COLUMN time text");
    
    
    maybe_add_column($cable,"browser", "ALTER TABLE $cable ADD browser text");
    maybe_add_column($cable,"queried", "ALTER TABLE $cable ADD queried text");
    maybe_add_column($cable,"trans_type", "ALTER TABLE $cable ADD trans_type text");
    maybe_add_column($cable,"trans_method", "ALTER TABLE $cable ADD trans_method text");
    maybe_add_column($cable,"via", "ALTER TABLE $cable ADD via text");
    maybe_add_column($cable,"time_taken", "ALTER TABLE $cable ADD time_taken text");
    maybe_add_column($cable,"response_id", "ALTER TABLE $cable ADD response_id text");
    maybe_add_column($cable,"run_code", "ALTER TABLE $cable ADD run_code text");
    maybe_add_column($cable,"status", "ALTER TABLE $cable ADD status text");
    
    maybe_add_column($bill,"browser", "ALTER TABLE $bill ADD browser text");
    maybe_add_column($bill,"queried", "ALTER TABLE $bill ADD queried text");
    maybe_add_column($bill,"trans_type", "ALTER TABLE $bill ADD trans_type text");
    maybe_add_column($bill,"trans_method", "ALTER TABLE $bill ADD trans_method text");
    maybe_add_column($bill,"via", "ALTER TABLE $bill ADD via text");
    maybe_add_column($bill,"time_taken", "ALTER TABLE $bill ADD time_taken text");
    maybe_add_column($bill,"request_id", "ALTER TABLE $bill ADD request_id text");
    maybe_add_column($bill,"charge", "ALTER TABLE $bill ADD charge text");
    maybe_add_column($bill,"response_id", "ALTER TABLE $bill ADD response_id text");
    maybe_add_column($bill,"run_code", "ALTER TABLE $bill ADD run_code text");
    maybe_add_column($bill,"status", "ALTER TABLE $bill ADD status text");
    }
   
    if(is_plugin_active('vpcards/vpcards.php')){
     $scard = $wpdb->prefix."scards";
     $wpdb->query("ALTER TABLE $scard MODIFY COLUMN pin text");
     maybe_add_column($scard,"status", "ALTER TABLE $scard ADD status text");
     
     }
     
    if(is_plugin_active('vpepin/vpepin.php')){
     $sepin = $wpdb->prefix."sepins";
     $wpdb->query("ALTER TABLE $sepin MODIFY COLUMN pin text");
     maybe_add_column($sepin,"status", "ALTER TABLE $sepin ADD status text");
     
     }
   
   
   
   
     global $wpdb;
     $table_name = $wpdb->prefix.'vp_transfer';
     $charset_collate=$wpdb->get_charset_collate();
     $sql= "CREATE TABLE IF NOT EXISTS $table_name(
     id int NOT NULL  AUTO_INCREMENT,
     tfrom text,
     tto text,
     amount text,
     status text,
     the_time text,
     PRIMARY KEY (id))$charset_collate;";
     require_once(ABSPATH.'wp-admin/includes/upgrade.php');
     dbDelta($sql);


     global $wpdb;
     $table_name = $wpdb->prefix.'vp_smsblaster';
     $charset_collate=$wpdb->get_charset_collate();
     $sql= "CREATE TABLE IF NOT EXISTS $table_name(
     id int NOT NULL  AUTO_INCREMENT,
     phone text,
     message text,
     sent VARCHAR(255) DEFAULT 'no',
     logged VARCHAR(255) DEFAULT 'no',
     the_time text,
     PRIMARY KEY (id))$charset_collate;";
     require_once(ABSPATH.'wp-admin/includes/upgrade.php');
     dbDelta($sql);
      
   

   
}


$nw_updt = 24;

if(vp_getoption("fix_version") != $nw_updt){

  //create lockfile
  // add_option("vtupress-vend","default");
  // add_option("msorg-main","default");
  // add_option("opay-main","default");
  // add_option("index","default");

  global $wpdb;
  $lsd_name = $wpdb->prefix.'vp_wallet';
  // maybe_add_column($lsd_name,'sender', "ALTER TABLE $lsd_name ADD sender text ");
  maybe_add_column($lsd_name,"sender","ALTER TABLE $lsd_name ADD sender VARCHAR(255) NOT NULL DEFAULT 'sender' ");



  global $current_timestamp;

  $next = date("Y-m-d H:i A",$current_timestamp);
  vp_updateoption("vp_check_date", $next);

  vp_addoption("vtu_timeout","5");

  global $wpdb;
  $lsd_name = $wpdb->prefix.'vp_verifications';
  maybe_add_column($lsd_name,'card_type', "ALTER TABLE $lsd_name ADD card_type text ");


  $lsd_name = $wpdb->prefix.'vp_levels';
  maybe_add_column($lsd_name,'data_mtn', "ALTER TABLE $lsd_name ADD data_mtn text ");
  maybe_add_column($lsd_name,'data_glo', "ALTER TABLE $lsd_name ADD data_glo text ");
  maybe_add_column($lsd_name,'data_airtel', "ALTER TABLE $lsd_name ADD data_airtel text ");
  maybe_add_column($lsd_name,'data_9mobile', "ALTER TABLE $lsd_name ADD data_9mobile text ");

    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_wallet_webhook';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    gateway text,
    amount text,
    referrence text,
    status text,
    response text,
    the_time text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql); 
   
    global $wpdb;
    $usrs = $wpdb->prefix."users";
    maybe_add_column($usrs,'vp_user_pv', "ALTER TABLE $usrs ADD vp_user_pv text ");


    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_pv_rules';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL AUTO_INCREMENT,
    required_pv text,
    upgrade_plan text,
    upgrade_balance text,
    status text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    global $wpdb;
    $sd_name = $wpdb->prefix.'vp_transactions';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $sd_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id int,
    name text,
    email text,
    service text,
    request_id text,
    bal_bf text,
    bal_nw text,
    recipient text,
    amount text,
    the_time text,
    status text,
    PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);
   
  global $wpdb;
  $table = $wpdb->prefix."vp_levels";
  $wpdb->query("ALTER TABLE $table MODIFY COLUMN upgrade text");

  global $wpdb;
  $usrs = $wpdb->prefix."vp_transactions";
  maybe_add_column($usrs,'api_response', "ALTER TABLE $usrs ADD api_response text ");
  maybe_add_column($usrs,'api_from', "ALTER TABLE $usrs ADD api_from text ");

    vtupress_db_man();

  vp_updateoption("fix_version",$nw_updt);
}



?>