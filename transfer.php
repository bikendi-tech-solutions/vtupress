<?php
if (!defined('ABSPATH')) {
  $pagePath = explode('/wp-content/', dirname(__FILE__));
  include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
}
if (WP_DEBUG == false) {
  error_reporting(0);
}
include_once(ABSPATH . "wp-load.php");
include_once(ABSPATH . 'wp-content/plugins/vtupress/functions.php');

if (!is_user_logged_in() && !botAccess()) {
  die("Please login");
} elseif (botAccess()) {
  $id = $_POST["user_id"];
} else {
  $id = get_current_user_id();
}

if (!isset($_POST["action"])) {
  die("Invalid request");
}

$action = $_POST["action"];




if (vp_getoption('allow_to_bank') != "yes" || vp_getoption("vtupress_custom_transfer") != "yes") {
  die("Enable Bank To Bank transfer");
}
$amount = intval(str_replace("-", "", trim($_POST["amount"])));

vp_sessions();



global $wpdb;
$table_lock = "{$wpdb->prefix}vp_wallet_lock";

$wpdb->query("
    CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vp_wallet_lock (
        user_id BIGINT PRIMARY KEY,
        locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB
");

$wpdb->query('START TRANSACTION');


$wpdb->query("INSERT INTO {$wpdb->prefix}vp_wallet_lock (user_id) VALUES ($id)
              ON DUPLICATE KEY UPDATE user_id = user_id");

// Step 3: Lock the user's row in the lock table
$wpdb->get_row("SELECT user_id FROM $table_lock WHERE user_id = $id FOR UPDATE");


$table_name = $wpdb->prefix . 'vp_wallet';

// Step 1: Check the table engine
$table_status = $wpdb->get_row("SHOW TABLE STATUS WHERE Name = '$table_name'");
$engine = isset($table_status->Engine) ? strtoupper($table_status->Engine) : '';

// Step 2: Convert to InnoDB if needed
if ($engine !== 'INNODB') {
  $wpdb->query("ALTER TABLE {$table_name} ENGINE=InnoDB");
}



// Step 4: Lock only the row for the specific user
$result = $wpdb->get_row("
    SELECT * 
    FROM $table_name 
    WHERE user_id = '{$id}' AND type = 'Transfer'
    ORDER BY id DESC 
    LIMIT 1 
    FOR UPDATE
");

$current_balance = floatval(vp_getuser($id, 'vp_bal', true));
//do kyc and other necessity.
//check previous balances to this one:
//$wpdb->query('ROLLBACK');
// 
$lastRecentNowBal = floatval($result->before_amount);
if ($lastRecentNowBal == $current_balance) {
  vp_block_user("Banned because we discovered an anomality. [$current_balance == $lastRecentNowBal]");
  die("Banned because we discovered an anomality. [$current_balance == $lastRecentNowBal]");
}


if (vp_getoption("enable_nomba") == "yes") {
  include_once 'transfer-nomba.php';
}

$secret_key = vp_getoption('psec'); //get_option('jettrade_paystack_secret');
$symbol = vp_country()["currency"];
$name = vp_getuser($id, 'first_name') . " " . vp_getuser($id, 'last_name');
$accountNo = trim($_POST["account_number"]);
$bank_code = trim($_POST["bank_code"]);
$currency = $symbol;
$current_balance = intval(vp_getuser($id, 'vp_bal', true));


$get_details = false;

if (isset($_POST["get_details"])):
  $get_details = true;
endif;



//create recipient

$url = "https://api.paystack.co/transferrecipient";
$fields = [
  'type' => "nuban",
  'name' => $name,
  'account_number' => $accountNo,
  'bank_code' => $bank_code,
  'currency' => $currency
];

$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Authorization: Bearer $secret_key",
  "Cache-Control: no-cache",
));

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);

// die(json_encode($fields)." ---- ".$result);

$json = json_decode($result, true);



if (isset($json["data"]["recipient_code"])):
  $result = $json["data"]["recipient_code"];
else:
  error_log("create recipient error .. " . $result);
  if (isset($json["message"])):
    $wpdb->query('ROLLBACK');
    die($json["message"]);
  endif;
  $wpdb->query('ROLLBACK');
  die("101");
endif;


if (isset($json["data"]["details"]["account_name"])):
  $name = $json["data"]["details"]["account_name"];
  $bank_code = $json["data"]["details"]["bank_name"];
else:
  $name = "";
  $bank_code = "";
endif;

if (empty($name) || empty($bank_code)):
  $wpdb->query('ROLLBACK');
  die("Invalid bank details");
endif;

if ($get_details && !botAccess()):
  $wpdb->query('ROLLBACK');
  die($name);
elseif (botAccess() && $get_details):
  die(json_encode(['success' => true, 'name' => $name]));
endif;

if ($amount < 50000) {
  $charge = 70;
} else {
  $charge = 100;
}

$amountWithCharge = ($amount + $charge);

if ($current_balance < $amount):
  $wpdb->query('ROLLBACK');
  die("Insufficient balance [$current_balance]");
elseif ($amount < 50):
  $wpdb->query('ROLLBACK');
  die("Minimum transfer amount is 50");
elseif ($current_balance < $amountWithCharge):
  $wpdb->query('ROLLBACK');
  die("Insufficient balance to cover transfer fee [$charge] inclusively");
else:
  //charge = 

  $updatedBalance = $current_balance - $amountWithCharge;


endif;

$status = "failed";

//initiate transfer

$url = "https://api.paystack.co/transfer";
$fields = [
  'source' => "balance",
  'reason' => "Money Transfer From Trade",
  'amount' => $amount * 100,
  'reference' => uniqid(),
  'recipient' => $result
];

$fields_string = http_build_query($fields);

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Authorization: Bearer $secret_key",
  "Cache-Control: no-cache",
));

//So that curl_exec returns the contents of the cURL; rather than echoing it
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);

$json = json_decode($result, true);

if (isset($json["data"]["status"])):
  $status = $json["data"]["status"];
else:
  error_log("running transfer error .. " . $result);
  if (isset($json["message"])):
    $wpdb->query('ROLLBACK');
    die($json["message"]);
  endif;
  $wpdb->query('ROLLBACK');
  die("102");
endif;



if (preg_match("/succ/", $status) || preg_match("/receiv/", $status) || preg_match("/pend/", $status) || preg_match("/proc/", $status)):
  $return = "success";

  //$user_details["balance"] = $updatedBalance;
  //$wpdb->update( $user_table, $user_details, array( 'user_id' => $id ) );
  vp_updateuser($id, "vp_bal", $updatedBalance);

  $data = [
    'user_id' => $id,
    'amount' => $amount,
    'amount_before' => $current_balance,
    'amount_now' => $updatedBalance,
    'status' => "success",
    'bank_details' => $name . "-" . $accountNo . "-" . $bank_code,
  ];

  $wallet = [
    'user_id' => $id,
    'amount' => $amount,
    'amount_before' => $current_balance,
    'amount_now' => $updatedBalance,
    'type' => "debit"
  ];

  //  global $wpdb;
  //  $table_name = $wpdb->prefix. "jettrade_wallet_history";
  // $wpdb->insert( $table_name, $wallet );
  // insert to wallet


  $sta = "approved";
  $chag = "@ #$charge charge";
  //debit user
else:
  $return = "failed";

  $data = [
    'user_id' => $id,
    'amount' => $amount,
    'amount_before' => $current_balance,
    'amount_now' => $current_balance,
    'status' => "failed",
    'bank_details' => $name . "-" . $accountNo . "-" . $bank_code,
  ];

  $updatedBalance = $current_balance;
  $sta = "declined";
  $chag = " failed";

endif;

$userData = get_userdata($id);

// global $wpdb;
//   $table_name = $wpdb->prefix. "jettrade_withdrawals";

//   $wpdb->insert( $table_name, $data );


global $wpdb;
$table_name = $wpdb->prefix . 'vp_wallet';
maybe_add_column($table_name, "bank", "ALTER TABLE $table_name ADD bank text");
maybe_add_column($table_name, "charge", "ALTER TABLE $table_name ADD charge text");

$added_to_db = $wpdb->insert($table_name, array(
  'name' => $userData->user_login,
  'type' => "Transfer",
  'description' => "Bank Transfer to [ $accountNo <=> $bank_code ] $chag",
  'fund_amount' => $amount,
  'before_amount' => $current_balance,
  'now_amount' => $updatedBalance,
  'user_id' => $id,
  'status' => $sta,
  'bank' => $data["bank_details"],
  'charge' => $charge,
  'the_time' => date('Y-m-d h:i:s A', $current_timestamp)
));


$wpdb->query('COMMIT');
if(botAccess()){
  die(json_encode($data));
}
die($return);