<?php
/**
 * VtuPress
 *
 * @package VtuPress
 * @author Akor Victor
 * @copyright 2020 vtupress
 * @license GPL-3.0-or-later
 *
*Plugin Name: VTU Press
*Plugin URI: http://vtupress.com
*Description: This is the very first <b>VTU plugin</b>. It's VTU services are all Automated with wonderful features
*Version: 7.2.5
*Author: Akor Victor
*Author URI: https://facebook.com/vtupressceo
*License: GPL3
*License URI:  https://www.gnu.org/licenses/gpl.html
*Domain Path:  /languages
*/
/*
{Plugin Name} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

{Plugin Name} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {Plugin Name}. If not, see {URI to Plugin License}.
*/
//--hRequires PHP: 7.4

// Ensure ABSPATH is defined and load wp-load.php if not already loaded.
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}

// Suppress errors in production environments if WP_DEBUG is false.
if(WP_DEBUG == false){
    error_reporting(0);
}

// Include necessary WordPress core files and plugin functions.
include_once(ABSPATH ."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php'); // Your plugin's functions file
include_once(ABSPATH .'wp-admin/includes/plugin.php');
require_once(ABSPATH.'wp-admin/includes/upgrade.php');

include_once('database.php');
include_once('registry/function.php');
include_once('actions.php');
include_once('vpcustom.php');
include_once('vpuser.php');

// Define global current timestamp.
global $current_timestamp;
$current_timestamp = current_time('timestamp');


/*
 * NEW: Function to set security headers.
 * This function is hooked to 'plugins_loaded' with a high priority (1)
 * to ensure it runs as early as possible, before any output might occur.
 */
// function vtupress_set_security_headers() {
//     // Check if headers have already been sent before attempting to set them.
//     // This is a safeguard, but the goal is to prevent this check from being necessary
//     // by hooking to a very early action.
//     if (!headers_sent()) {
//         header("Content-Security-Policy: https:");
//         header("strict-transport-security: max-age=31536000 ");
//         header("X-Frame-Options: SAMEORIGIN");
//         header("X-Content-Type-Options: nosniff");
//         header("Referrer-Policy: same-origin");
//         header("X-Xss-Protection: 1");
//         header('Permissions-Policy: geolocation=(self),camera=(self), microphone=(self)');
//     }
// }
// Hook the security header function to the 'plugins_loaded' action with a very high priority.
// add_action('plugins_loaded', 'vtupress_set_security_headers', 1);


/*
 * Original vtupress_user_update function.
 * The header calls have been moved to vtupress_set_security_headers().
 * This function now focuses solely on user data updates.
 */
add_action('init','vtupress_user_update');
function vtupress_user_update(){
    global $current_timestamp;

    if(is_user_logged_in()){
        if(current_user_can("administrator")){
            global $wpdb;
            $user_table = $wpdb->prefix.'users';
            $all_users = $wpdb->get_results("SELECT ID FROM $user_table"); // Only fetch ID for efficiency
            foreach($all_users as $use){
                $id = $use->ID;
                $bal = vp_getuser($id, 'vp_bal', true);
                if(empty($bal)){
                    $bal = 0;
                    vp_updateuser($id, 'vp_bal', "0");
                }
                // Update the user meta in the wp_users table (if vp_bal is stored there directly)
                // Note: If vp_bal is *only* stored in vp_user_data JSON, this update is redundant.
                // It seems you're trying to keep it in both places for some reason.
                // If it's only in JSON, remove this part.
                $arr = ['vp_bal' => $bal ];
                $where = ['ID' => $id];
                $wpdb->update($user_table, $arr, $where);
            }
        }
    }

    // Session timeout logic
    if(vp_getoption("vtu_timeout") != "false" && vp_getoption("vtu_timeout") != "0" && !empty(vp_getoption("vtu_timeout")) && is_user_logged_in() && is_numeric(vp_getoption("vtu_timeout"))){
        if(intval(vp_getoption("vtu_timeout")) <= 60 && intval(vp_getoption("vtu_timeout")) > 0){
            if(isset($_COOKIE["last_login"])){
                $last_login = sanitize_text_field($_COOKIE["last_login"]); // Sanitize cookie value
                $dur = intval(vp_getoption("vtu_timeout"));
                $cur = date('Y-m-d H:i:s',$current_timestamp);
                $timeout = date("Y-m-d H:i:s",strtotime("$last_login +$dur minutes"));

                if(($cur < $timeout)  || (current_user_can("vtupress_admin") || current_user_can("administrator") )){
                    // Set cookie only if headers haven't been sent yet
                    if (!headers_sent()) {
                        setcookie("last_login", date('Y-m-d H:i:s',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
                    }
                } else {
                    wp_logout();
                }
            } else {
                wp_logout();
            }
        }
    }
}


/*
// Plugin Update Checker (commented out in original, but kept for reference)
require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bikendi-tech-solutions/vtupress/',
	__FILE__,
	'vtupress'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

$myUpdateChecker->setAuthentication('your-token-here');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();
*/


// Plugin Update Checker (active in original)
require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/bikendi-tech-solutions/vtupress',
	__FILE__,
	'vtupress'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//Optional: If you're using a private repository, specify the access token like this:
/*
require __DIR__.'/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://vtupress.com/vtupress.json',
	__FILE__, //Full path to the main plugin file or functions.php.
	'vtupress'
);
*/

/*
// Admin styles (commented out in original, but kept for reference)
add_action( 'admin_init', 'wpdocs_plugin_admin_init' );
function wpdocs_plugin_admin_init() {
	wp_register_style( 'wpdocsPluginStylesheet', plugins_url("vtupress/admin")."/dist/css/style.min.css" );
}
add_action( "admin_print_styles", 'wpdocs_plugin_admin_styles' );
function wpdocs_plugin_admin_styles() {
	wp_enqueue_style( 'wpdocsPluginStylesheet' );
}
*/


// Hook for setting login session cookies.
add_action('wp_login','vtupress_login_session');
function vtupress_login_session(){
    global $current_timestamp;
    // Check if headers have already been sent before attempting to set cookies
    if (!headers_sent()) {
        setcookie("vtuloggedin", "yes", time() + (30 * 24 * 60 * 60), "/");
        setcookie("last_login", date('Y-m-d H:i:s',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");
    }
}


// Security module and IP banning logic.
if(isset($_SERVER['HTTP_HOST'])){
    if(vp_getoption("vp_security") == "yes" && vp_getoption("secur_mod") != "off" && !is_admin() &&
       stripos($_SERVER["SCRIPT_NAME"],"system.php") === false &&
       stripos($_SERVER["SCRIPT_NAME"],"vend.php") === false &&
       stripos($_SERVER["SCRIPT_NAME"],"saveauth.php") === false &&
       stripos($_SERVER["SCRIPT_NAME"],"index.php") === false && // Duplicated, keeping for consistency
       stripos($_SERVER["SCRIPT_NAME"],"vpsystem.php") === false){

        $ban_ip = vp_getoption("vp_ips_ban");
        $ip_address = '';
        $system = "Unknown";

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
            $system = "Shared Internet";
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $forwarded_for = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
            $ips = explode(',', $forwarded_for);
            $ip_address = trim(end($ips)); // Get the last IP, often the client IP
            $system = "Proxy";
        } else {
            $ip_address = sanitize_text_field($_SERVER['REMOTE_ADDR']);
            $system = "Remote";
        }

        if(!empty($ip_address) && (is_numeric(stripos($ban_ip, $ip_address)) !== false || stripos($ban_ip, $ip_address) !== false)){
            if(vp_getoption("access_website") == "true"){
                die(
                    '
                    <div style="text-align:center;">
                    <h1>ACCESS DENIED</h1><br>
                    <h4>You Have Been Denied Access To Access This Site [IP - '.esc_html($system)." {".esc_html($_SERVER["SCRIPT_NAME"]).'}]</h4><br>
                    <p>Security Powered By: <a href="https://vtupress.com">vtupress</a></p><br>
                    <p>0edc-8480-3434</p><br>
                    </div>
                    '
                );
            }
        }
    }
}


// Plugin update and database schema management.
$update_vtupress_options = 74;
if(get_option("vtupress_options2") != $update_vtupress_options){
    global $wpdb;

    $table_name = $wpdb->prefix."vp_profile";
    maybe_add_column($table_name,"code","ALTER  TABLE  $table_name ADD code text");


    $table_name = $wpdb->prefix.'vp_verifications';
    $wpdb->query("ALTER TABLE $table_name MODIFY COLUMN vDatas LONGTEXT");





    // Create or update vp_wallet_lock table.
    $table_lock = "{$wpdb->prefix}vp_wallet_lock";
    $wpdb->query("
        CREATE TABLE IF NOT EXISTS {$wpdb->prefix}vp_wallet_lock (
            user_id BIGINT PRIMARY KEY,
            locked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // Convert existing tables to InnoDB engine if not already.
    $tables = ["sairtime","sdata","scable","sbill","vp_wallet"];
    foreach($tables as $tab){
        $table_name = $wpdb->prefix . $tab;
        $table_status = $wpdb->get_row("SHOW TABLE STATUS WHERE Name = '$table_name'");
        $engine = isset($table_status->Engine) ? strtoupper($table_status->Engine) : '';
        if ($engine !== 'INNODB') {
            $wpdb->query("ALTER TABLE {$table_name} ENGINE=InnoDB");
        }
    }

    // Create/update vp_daily_savings_settings table.
    $table_name = $wpdb->prefix.'vp_daily_savings_settings';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    sign_up_fee text,
    cut text,
    cut_type text,
    minimum_amount text,
    interest text,
    status text,
    referer_commission text,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);
    maybe_add_column($table_name,"status","ALTER  TABLE  $table_name ADD status text");
    maybe_add_column($table_name,"cut_type","ALTER  TABLE  $table_name ADD cut_type text");

    $payload = [
        "sign_up_fee" => "1000",
        "cut" => "1",
        "cut_type" => "off",
        "minimum_amount" => "0",
        "interest" => "0",
        "status" => "off",
        "referer_commission" => "400"
    ];

    $results = $wpdb->get_results("SELECT * FROM $table_name");
    if(empty($results)){
        $wpdb->insert($table_name,$payload);
    }

    // Create/update vp_fixed_savings_settings table.
    $table_name = $wpdb->prefix.'vp_fixed_savings_settings';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    sign_up_fee text,
    duration text,
    status text,
    interest text,
    referer_commission text,
    applicable_after text,
    recurrent_after_first text,
    penalty text,
    cut text,
    minimum_amount text,
    cut_type text,
    stop_after_due text,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);
    maybe_add_column($table_name,"penalty","ALTER  TABLE  $table_name ADD penalty text");
    maybe_add_column($table_name,"cut","ALTER  TABLE  $table_name ADD cut text");
    maybe_add_column($table_name,"cut_type","ALTER  TABLE  $table_name ADD cut_type text");
    maybe_add_column($table_name,"recurrent_after_first","ALTER  TABLE  $table_name ADD recurrent_after_first text");

    $fixed_savings_defaults = [
        "3 Months" => [
            "sign_up_fee" => "1000", "duration" => "3 Months", "status" => "off", "cut" => "1",
            "cut_type" => "percentage", "interest" => "4", "applicable_after" => "2 Months",
            "recurrent_after_first" => "no", "minimum_amount" => "0", "referer_commission" => "400",
            "penalty" => "10", "stop_after_due" => "yes"
        ],
        "6 Months" => [
            "sign_up_fee" => "1000", "duration" => "6 Months", "status" => "off", "cut" => "1",
            "cut_type" => "percentage", "interest" => "10", "applicable_after" => "5 Months",
            "recurrent_after_first" => "no", "minimum_amount" => "0", "referer_commission" => "400",
            "penalty" => "10", "stop_after_due" => "yes"
        ],
        "12 Months" => [
            "sign_up_fee" => "1000", "duration" => "12 Months", "status" => "off", "cut" => "1",
            "cut_type" => "percentage", "interest" => "22", "applicable_after" => "10 Months",
            "recurrent_after_first" => "no", "minimum_amount" => "0", "referer_commission" => "400",
            "penalty" => "10", "stop_after_due" => "yes"
        ]
    ];

    foreach($fixed_savings_defaults as $duration => $payload){
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE duration = %s", $duration));
        if(empty($results)){
            $wpdb->insert($table_name,$payload);
        }
    }

    // Create/update vp_savings table.
    $table_name = $wpdb->prefix.'vp_savings';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id text,
    type text,
    duration text,
    save_interval text,
    status text,
    start_amount text,
    amount_saved text,
    interest text,
    applicable_on text,
    next_applicable_on text,
    interest_applied text,
    save_count text,
    automatic text,
    info text,
    last_check text,
    started_at text,
    next_save text,
    ends_at text,
    liquidated text,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);
    maybe_add_column($table_name,"applicable_on","ALTER  TABLE  $table_name ADD applicable_on text");
    maybe_add_column($table_name,"start_amount","ALTER  TABLE  $table_name ADD start_amount text");
    maybe_add_column($table_name,"save_interval","ALTER  TABLE  $table_name ADD save_interval text");
    maybe_add_column($table_name,"save_count","ALTER  TABLE  $table_name ADD save_count text");
    maybe_add_column($table_name,"last_check","ALTER  TABLE  $table_name ADD last_check text");
    maybe_add_column($table_name,"interest_applied","ALTER  TABLE  $table_name ADD interest_applied text");
    maybe_add_column($table_name,"next_applicable_on","ALTER  TABLE  $table_name ADD next_applicable_on text");
    maybe_add_column($table_name,"next_save","ALTER  TABLE  $table_name ADD next_save text");


    // Create/update vp_savings_withdrawal table.
    $table_name = $wpdb->prefix.'vp_savings_withdrawal';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    user_id text,
    type text,
    duration text,
    amount_withdrawn text,
    wallet_id text,
    savings_id text,
    amount_saved text,
    status text,
    the_time text,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);
    maybe_add_column($table_name,"wallet_id","ALTER  TABLE  $table_name ADD wallet_id text");
    maybe_add_column($table_name,"savings_id","ALTER  TABLE  $table_name ADD savings_id text");


    // Add/update various plugin options.
    vp_addoption('enable_raptor',"no");
    vp_addoption("spraycode",uniqid());
    vp_addoption("auto_refund","yes");
    vp_addoption('minimum_amount_transferable',10000);
    vp_addoption('minimum_amount_fundable',1000);
    vp_addoption("vp_users_email","Nast, v1, win2, win");
    vp_addoption('allow_card_method','yes');
    vp_addoption('cron_successful','0');
    vp_addoption('cron_failed','0');
    vp_addoption('enablehollatag','no');
    vp_addoption('hollatagcompany','');
    vp_addoption('hollatagusername','');
    vp_addoption('hollatagpassword','');
    vp_addoption('hollatagservices','');

    add_option("vtupress_options2","0"); // This will be updated below

    update_option('timezone_string',"Africa/Lagos");
    update_option('start_of_week',0);

    vtupress_verification(); // Call verification function

    // Update site URL to HTTPS if not already.
    $url = home_url();
    // $url = str_replace("http://","https://",$url);
    vp_updateoption("siteurl",$url);

    // Update vp_kyc table columns.
    $table_name = $wpdb->prefix.'vp_kyc';
    maybe_add_column($table_name,"accountNumber","ALTER TABLE $table_name ADD accountNumber text");
    maybe_add_column($table_name,"matchRate","ALTER TABLE $table_name ADD matchRate text");
    maybe_add_column($table_name,"accountName","ALTER TABLE $table_name ADD accountName text");
    maybe_add_column($table_name,"bankName","ALTER TABLE $table_name ADD bankName text");
    maybe_add_column($table_name,"bankCode","ALTER TABLE $table_name ADD bankCode text");

    // Add various discount and feature options.
    vp_addoption('allow_crypto','no');
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
    vp_addoption("charge_method", "fixed"); // This option is added multiple times, keeping for consistency.
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

    // SME API options.
    vp_updateoption("api0", 1); vp_updateoption("api1", 2); vp_updateoption("api2", 3); vp_updateoption("api3", 4);
    vp_updateoption("api4", 5); vp_updateoption("api5", 6); vp_updateoption("api6", 7); vp_updateoption("api7", 8);
    vp_updateoption("api8", 9); vp_updateoption("api9", 10); vp_updateoption("api10", 11);
    vp_updateoption("aapi0", 12); vp_updateoption("aapi1", 13); vp_updateoption("aapi2", 14); vp_updateoption("aapi3", 15);
    vp_updateoption("aapi4", 16); vp_updateoption("aapi5", 17); vp_updateoption("aapi6", 18); vp_updateoption("aapi7", 19);
    vp_updateoption("aapi8", 20); vp_updateoption("aapi9", 21); vp_updateoption("aapi10", 22);
    vp_updateoption("9api0", 23); vp_updateoption("9api1", 24); vp_updateoption("9api2", 25); vp_updateoption("9api3", 26);
    vp_updateoption("9api4", 27); vp_updateoption("9api5", 28); vp_updateoption("9api6", 29); vp_updateoption("9api7", 30);
    vp_updateoption("9api8", 31); vp_updateoption("9api9", 32); vp_updateoption("9api10", 33);
    vp_updateoption("gapi0", 34); vp_updateoption("gapi1", 35); vp_updateoption("gapi2", 36); vp_updateoption("gapi3", 37);
    vp_updateoption("gapi4", 38); vp_updateoption("gapi5", 39); vp_updateoption("gapi6", 40); vp_updateoption("gapi7", 41);
    vp_updateoption("gapi8", 42); vp_updateoption("gapi9", 43); vp_updateoption("gapi10", 44);

    // CORPORATE API options.
    vp_updateoption("api20", 45); vp_updateoption("api21", 46); vp_updateoption("api22", 47); vp_updateoption("api23", 48);
    vp_updateoption("api24", 49); vp_updateoption("api25", 50); vp_updateoption("api26", 51); vp_updateoption("api27", 52);
    vp_updateoption("api28", 53); vp_updateoption("api29", 54); vp_updateoption("api210", 55);
    vp_updateoption("aapi20", 56); vp_updateoption("aapi21", 57); vp_updateoption("aapi22", 58); vp_updateoption("aapi23", 59);
    vp_updateoption("aapi24", 60); vp_updateoption("aapi25", 61); vp_updateoption("aapi26", 62); vp_updateoption("aapi27", 63);
    vp_updateoption("aapi28", 64); vp_updateoption("aapi29", 65); vp_updateoption("aapi210", 66);
    vp_updateoption("9api20", 67); vp_updateoption("9api21", 68); vp_updateoption("9api22", 69); vp_updateoption("9api23", 70);
    vp_updateoption("9api24", 71); vp_updateoption("9api25", 72); vp_updateoption("9api26", 73); vp_updateoption("9api27", 74);
    vp_updateoption("9api28", 75); vp_updateoption("9api29", 76); vp_updateoption("9api210", 77);
    vp_updateoption("gapi20", 78); vp_updateoption("gapi21", 79); vp_updateoption("gapi22", 80); vp_updateoption("gapi23", 81);
    vp_updateoption("gapi24", 82); vp_updateoption("gapi25", 83); vp_updateoption("gapi26", 84); vp_updateoption("gapi27", 85);
    vp_updateoption("gapi28", 86); vp_updateoption("gapi29", 87); vp_updateoption("gapi210", 88);

    // GIFTING API options.
    vp_updateoption("api30", 89); vp_updateoption("api31", 90); vp_updateoption("api32", 91); vp_updateoption("api33", 92);
    vp_updateoption("api34", 93); vp_updateoption("api35", 94); vp_updateoption("api36", 95); vp_updateoption("api37", 96);
    vp_updateoption("api38", 97); vp_updateoption("api39", 98); vp_updateoption("api310", 99);
    vp_updateoption("aapi30", 100); vp_updateoption("aapi31", 101); vp_updateoption("aapi32", 102); vp_updateoption("aapi33", 103);
    vp_updateoption("aapi34", 104); vp_updateoption("aapi35", 105); vp_updateoption("aapi36", 106); vp_updateoption("aapi37", 107);
    vp_updateoption("aapi38", 108); vp_updateoption("aapi39", 109); vp_updateoption("aapi310", 110);
    vp_updateoption("9api30", 111); vp_updateoption("9api31", 112); vp_updateoption("9api32", 113); vp_updateoption("9api33", 114);
    vp_updateoption("9api34", 115); vp_updateoption("9api35", 116); vp_updateoption("9api36", 117); vp_updateoption("9api37", 118);
    vp_updateoption("9api38", 119); vp_updateoption("9api39", 120); vp_updateoption("9api310", 121);
    vp_updateoption("gloapi30", 122); vp_updateoption("gloapi31", 123); vp_updateoption("gloapi32", 124); vp_updateoption("gloapi33", 125);
    vp_updateoption("gloapi34", 126); vp_updateoption("gloapi35", 127); vp_updateoption("gloapi36", 128); vp_updateoption("gloapi37", 129);
    vp_updateoption("gloapi38", 130); vp_updateoption("gloapi39", 131); vp_updateoption("gloapi310", 132);

    // Add more general plugin options.
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
    vp_updateoption('suc','/successful'); // Duplicated update, keeping for consistency.

    // Update the plugin version option.
    update_option("vtupress_options2",$update_vtupress_options);
}


// HTTP to HTTPS redirection logic.
if(vp_getoption("http_redirect") == "true"){
    // Hook to template_redirect for redirects, as it's generally the safest place.
    // Removed 'init' and 'wp_loaded' hooks for vp_redirect_core to reduce header conflicts.
    add_action('template_redirect', 'vp_redirect_core', 50);
    function vp_redirect_core(){
        if (!is_ssl()) {
            if(isset($_SERVER['HTTP_HOST'])){
                // Ensure headers haven't been sent before redirecting.
                if (!headers_sent()) {
                    wp_redirect('https://' . sanitize_text_field($_SERVER['HTTP_HOST']) . esc_url_raw($_SERVER['REQUEST_URI']), 301);
                    exit(); // Always exit after a redirect.
                } else {
                    error_log("VP_REDIRECT_CORE: Headers already sent, cannot redirect.");
                }
            }
        }
    }
}


// Shortcode for Airtime service.
function vtupress_airtime(){
    ob_start();
    $phpVersion = phpversion();
    // Check PHP version compatibility.
    if(version_compare($phpVersion, '7.4.0', '>=') && version_compare($phpVersion, '8.1.0', '<')){ // Adjusted to < 8.1.0 for clarity
        include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/airtime.php');
    } else {
        ?>
        <div class="alert alert-primary mb-2" role="alert">
        <b>PHP VERSION ERROR:</b><br>
        PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo esc_html($phpVersion);?>.
        <br>
        <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
        </div>
        <?php
    }
    return ob_get_clean();
}
add_shortcode( 'vtupress_airtime', 'vtupress_airtime' );


// Shortcode for Data service.
function vtupress_data(){
    ob_start();
    $phpVersion = phpversion();
    // Check PHP version compatibility.
    if(version_compare($phpVersion, '7.4.0', '>=') && version_compare($phpVersion, '8.1.0', '<')){ // Adjusted to < 8.1.0 for clarity
        include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/data.php');
    } else {
        ?>
        <div class="alert alert-primary mb-2" role="alert">
        <b>PHP VERSION ERROR:</b><br>
        PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo esc_html($phpVersion);?>.
        <br>
        <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
        </div>
        <?php
    }
    return ob_get_clean();
}
add_shortcode( 'vtupress_data', 'vtupress_data' );


// Shortcode for Betting service.
function vtupress_bet(){
    ob_start();
    $phpVersion = phpversion();
    // Check PHP version compatibility.
    if(version_compare($phpVersion, '7.4.0', '>=') && version_compare($phpVersion, '8.1.0', '<')){ // Adjusted to < 8.1.0 for clarity
        include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/bet.php');
    } else {
        ?>
        <div class="alert alert-primary mb-2" role="alert">
        <b>PHP VERSION ERROR:</b><br>
        PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo esc_html($phpVersion);?>.
        <br>
        <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
        </div>
        <?php
    }
    return ob_get_clean();
}
add_shortcode( 'vtupress_bet', 'vtupress_bet' );


// Conditional shortcodes for Cable and Bill services if 'bcmv' plugin is active.
if(is_plugin_active('bcmv/bcmv.php')){
    add_shortcode( 'vtupress_cable', 'vtupress_cable' );
    add_shortcode( 'vtupress_bill', 'vtupress_bill' );

    function vtupress_cable(){
        ob_start();
        $phpVersion = phpversion();
        // Check PHP version compatibility.
        if (version_compare($phpVersion, '7.4.0', '>=') && version_compare($phpVersion, '8.1.0', '<')) { // Adjusted to < 8.1.0 for clarity
            include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/cable.php');
        } else {
            ?>
            <div class="alert alert-primary mb-2" role="alert">
            <b>PHP VERSION ERROR:</b><br>
            PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo esc_html($phpVersion);?>.
            <br>
            <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
            </div>
            <?php
        }
        return ob_get_clean();
    }

    function vtupress_bill(){
        ob_start();
        $phpVersion = phpversion();
        // Check PHP version compatibility.
        if(version_compare($phpVersion, '7.4.0', '>=') && version_compare($phpVersion, '8.1.0', '<')){ // Adjusted to < 8.1.0 for clarity
            include_once(__DIR__.'/template/'.constant('vtupress_template').'/services/bill.php');
        } else {
            ?>
            <div class="alert alert-primary mb-2" role="alert">
            <b>PHP VERSION ERROR:</b><br>
            PHP Version must be at least 7.4 but not up to 8.1. Your current version is <?php echo esc_html($phpVersion);?>.
            <br>
            <b>Contact Your Hosting Company For A Fix Or Solve The Issue From Cpanel</b>
            </div>
            <?php
        }
        return ob_get_clean();
    }
}


// Admin notice for plugin activation.
add_action("admin_notices","actnote");
function actnote(){
    if(vp_getoption("vp_access_importer") == "yes"){
        $path = get_plugin_data(__FILE__);
        $version = $path["Version"];
        echo'
        <style>
        .vp-not{
        padding:10px;
        }
        </style>
        <div class="notice vp-not notice-success is-dismissible">
        Thank you for installing and Activating <b>VTUPRESS '.esc_html($version).' Stable </b>.
        Are you new to VTUPRESS? kindly get started by visiting <a href="https://vtupress.com/doc">VtuPress Doc</a> to get Started!!!
        Discuss on our forum <a href="https://vtupress.com/forum">VtuPress Forum</a>
        </div>
        ';
    }
}


// Custom page template for 'vpaccount' page.
add_filter( 'page_template', 'vp_plugin_page_template' );
function vp_plugin_page_template( $page_template ){
    if ( is_page( 'vpaccount' )) {
        $page_template = plugin_dir_path( __FILE__ ) . 'template.php';
    }
    return $page_template;
}


// Demo site specific restrictions (redirects, menu removals).
if(vp_getoption("siteurl") == "https://demo.vtupress.com"){
    add_action('init', 'get_your_current_user_id');
    function get_your_current_user_id(){
        $idd = get_current_user_id();
        if($idd != "1"){
            function remove_profile_submenu() {
                global $submenu;
                unset($submenu['profile.php'][5]);
            }
            add_action('admin_head', 'remove_profile_submenu');

            function remove_profile_menu() {
                global $menu;
                unset($menu[70]);
            }
            add_action('admin_head', 'remove_profile_menu');

            function profile_redirect() {
                $result = stripos($_SERVER['REQUEST_URI'], 'profile.php');
                $result2 = stripos($_SERVER['REQUEST_URI'], 'admin.php?page=vplicense');
                if ($result!==false) {
                    // Ensure headers haven't been sent before redirecting.
                    if (!headers_sent()) {
                        wp_redirect(vp_getoption('siteurl'). '/wp-admin/admin.php?page=vtu-settings');
                        exit();
                    } else {
                        error_log("PROFILE_REDIRECT: Headers already sent, cannot redirect.");
                    }
                }
                if ($result2!==false) {
                    // Ensure headers haven't been sent before redirecting.
                    if (!headers_sent()) {
                        wp_redirect(vp_getoption('siteurl'). '/wp-admin/admin.php?page=vtu-settings');
                        exit();
                    } else {
                        error_log("PROFILE_REDIRECT: Headers already sent, cannot redirect.");
                    }
                }
            }
            add_action('admin_menu', 'profile_redirect');
        }
    }
}


// Database table creation for vp_levels.
function vp_levels(){
    global $wpdb;
    $table_name = $wpdb->prefix.'vp_levels';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    name text,
    airtime_pv text , data_pv text , cable_pv text , bill_pv text ,
    mtn_vtu text , glo_vtu text , mobile_vtu text , airtel_vtu text ,
    mtn_awuf text , glo_awuf text , mobile_awuf text , airtel_awuf text ,
    mtn_share text , glo_share text , mobile_share text , airtel_share text ,
    mtn_sme text , glo_sme text , mobile_sme text , airtel_sme text ,
    mtn_corporate text , glo_corporate text , mobile_corporate text , airtel_corporate text ,
    mtn_gifting text , glo_gifting text , mobile_gifting text , airtel_gifting text ,
    cable text , bill_prepaid text ,
    card_mtn text , card_glo text , card_9mobile text , card_airtel text ,
    data_mtn text , data_glo text , data_9mobile text , data_airtel text ,
    epin_waec text , epin_neco text , epin_jamb text , epin_nabteb text ,
    status text, upgrade text, monthly_sub text, airtime_bonus_ex1 text,
    enable_extra_service text, extra_feature_assigned_uId text,
    data_bonus_ex1 text, data_bonus_type_ex1 text, ref_airtime_bonus_ex1 text,
    ref_data_bonus_ex1 text, upgrade_bonus text, upgrade_pv text, developer text,
    charge_back_percentage text, transfer text,
    total_level text , level_1 text , level_1_data text , level_1_cable text ,
    level_1_bill text , level_1_ecards text , level_1_edatas text , level_1_epins text ,
    level_1_pv text , level_1_data_pv text , level_1_cable_pv text ,
    level_1_bill_pv text , level_1_ecards_pv text , level_1_edatas_pv text ,
    level_1_epins_pv text ,
    level_1_upgrade text , level_1_upgrade_pv text ,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);

    // Insert default 'reseller' and 'customer' levels if they don't exist.
    $customer = $wpdb->get_results("SELECT * FROM $table_name WHERE name = 'customer'");
    $reseller = $wpdb->get_results("SELECT * FROM $table_name WHERE name = 'reseller'");

    if(empty($reseller)){
        $wpdb->insert($table_name, array(
            'name'=> "reseller", 'mtn_vtu'=> "0", 'glo_vtu'=> "0", 'mobile_vtu'=> "0", 'airtel_vtu'=> "0",
            'mtn_awuf'=> "0", 'glo_awuf'=> "0", 'mobile_awuf'=> "0", 'airtel_awuf'=> "0",
            'mtn_share'=> "0", 'glo_share'=> "0", 'mobile_share'=> "0", 'airtel_share'=> "0",
            'mtn_sme'=> "0", 'glo_sme'=> "0", 'mobile_sme'=> "0", 'airtel_sme'=> "0",
            'mtn_corporate'=> "0", 'glo_corporate'=> "0", 'mobile_corporate'=> "0", 'airtel_corporate'=> "0",
            'mtn_gifting'=> "0", 'glo_gifting'=> "0", 'mobile_gifting'=> "0", 'airtel_gifting'=> "0",
            'cable'=> "0", 'bill_prepaid'=> "0",
            'card_mtn'=> "0", 'card_glo'=> "0", 'card_9mobile'=> "0", 'card_airtel'=> "0",
            'epin_waec'=> "0", 'epin_neco'=> "0", 'epin_jamb'=> "0", 'epin_nabteb'=> "0",
            'status'=> "active", 'upgrade'=> "1000", 'total_level'=> "0", 'charge_back_percentage' => '0',
            'level_1'=> "0", 'level_1_data'=> "0", 'level_1_cable'=> "0", 'level_1_bill'=> "0",
            'level_1_ecards'=> "0", 'level_1_epins'=> "0",
            'level_1_pv'=> "0", 'level_1_data_pv'=> "0", 'level_1_cable_pv'=> "0",
            'level_1_bill_pv'=> "0", 'level_1_ecards_pv'=> "0", 'level_1_epins_pv'=> "0",
            'level_1_upgrade'=> "0", 'level_1_upgrade_pv'=> "0", 'developer'=> "no", 'transfer'=> "no"
        ));
    }

    if(empty($customer)){
        $wpdb->insert($table_name, array(
            'name'=> "customer", 'mtn_vtu'=> "0", 'glo_vtu'=> "0", 'mobile_vtu'=> "0", 'airtel_vtu'=> "0",
            'mtn_awuf'=> "0", 'glo_awuf'=> "0", 'mobile_awuf'=> "0", 'airtel_awuf'=> "0",
            'mtn_share'=> "0", 'glo_share'=> "0", 'mobile_share'=> "0", 'airtel_share'=> "0",
            'mtn_sme'=> "0", 'glo_sme'=> "0", 'mobile_sme'=> "0", 'airtel_sme'=> "0",
            'mtn_corporate'=> "0", 'glo_corporate'=> "0", 'mobile_corporate'=> "0", 'airtel_corporate'=> "0",
            'mtn_gifting'=> "0", 'glo_gifting'=> "0", 'mobile_gifting'=> "0", 'airtel_gifting'=> "0",
            'cable'=> "0", 'bill_prepaid'=> "0",
            'card_mtn'=> "0", 'card_glo'=> "0", 'card_9mobile'=> "0", 'card_airtel'=> "0",
            'epin_waec'=> "0", 'epin_neco'=> "0", 'epin_jamb'=> "0", 'epin_nabteb'=> "0",
            'status'=> "active", 'upgrade'=> "1000", 'total_level'=> "0", 'charge_back_percentage' => '0',
            'level_1'=> "0", 'level_1_data'=> "0", 'level_1_cable'=> "0", 'level_1_bill'=> "0",
            'level_1_ecards'=> "0", 'level_1_epins'=> "0",
            'level_1_pv'=> "0", 'level_1_data_pv'=> "0", 'level_1_cable_pv'=> "0",
            'level_1_bill_pv'=> "0", 'level_1_ecards_pv'=> "0", 'level_1_epins_pv'=> "0",
            'level_1_upgrade'=> "0", 'level_1_upgrade_pv'=> "0", 'developer'=> "no", 'transfer'=> "no"
        ));
    }
}


// Database table creation for vp_kyc.
function vp_kyc(){
    global $wpdb;
    $table_name = $wpdb->prefix.'vp_kyc';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    name text,
    method text,
    selfie text,
    proof text,
    user_id int,
    status text,
    the_time text NOT NULL,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);
}


// Database table creation for vp_kyc_settings.
function vp_kyc_settings(){
    global $wpdb;
    $table_name = $wpdb->prefix.'vp_kyc_settings';
    $charset_collate=$wpdb->get_charset_collate();
    $sql= "CREATE TABLE IF NOT EXISTS $table_name(
    id int NOT NULL AUTO_INCREMENT,
    enable text,
    duration text,
    kyc_limit text,
    PRIMARY KEY (id))$charset_collate;";
    dbDelta($sql);

    // Insert default KYC settings if they don't exist.
    $kyc = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
    if(empty($kyc)){
        $wpdb->insert($table_name, array(
            'enable'=> "no",
            'duration'=> "day",
            'kyc_limit'=> "1000"
        ));
    }
}


// Custom user roles for VTUPress.
function vtupress_roles(){
    add_role(
        'vtupress_admin',
        "VTUPRESS ADMIN",
        [
            'read' => true, 'vtupress_access_addons' => true, 'vtupress_access_license' => true,
            'vtupress_access_levels' => true, 'vtupress_access_kyc' => true, 'vtupress_access_security' => true,
            'vtupress_access_gateway' => true, 'vtupress_access_importer' => true, 'vtupress_access_settings' => true,
            'vtupress_access_general' => true, 'vtupress_access_payment' => true, 'vtupress_access_history' => true,
            'vtupress_delete_history' => true, 'vtupress_access_users' => true, 'vtupress_access_users_action' => true,
            'vtupress_access_withdrawal' => true, 'vtupress_access_mlm' => true, 'vtupress_access_vtupress' => true,
            'vtupress_clear_history' => true
        ]
    );

    add_role(
        'vtupress_sales',
        "VTUPRESS SALES MANAGER",
        [
            'read' => true, 'vtupress_access_addons' => false, 'vtupress_access_license' => false,
            'vtupress_access_levels' => false, 'vtupress_access_kyc' => false, 'vtupress_access_security' => false,
            'vtupress_access_gateway' => false, 'vtupress_access_importer' => false, 'vtupress_access_settings' => true,
            'vtupress_access_general' => false, 'vtupress_access_payment' => false, 'vtupress_access_history' => true,
            'vtupress_delete_history' => true, 'vtupress_access_users' => false, 'vtupress_access_users_action' => false,
            'vtupress_access_withdrawal' => true, 'vtupress_access_mlm' => false, 'vtupress_access_vtupress' => true,
            'vtupress_clear_history' => true
        ]
    );

    add_role(
        'vtupress_user',
        "VTUPRESS USER MANAGER",
        [
            'read' => true, 'vtupress_access_addons' => false, 'vtupress_access_license' => false,
            'vtupress_access_levels' => false, 'vtupress_access_kyc' => false, 'vtupress_access_security' => false,
            'vtupress_access_gateway' => false, 'vtupress_access_importer' => false, 'vtupress_access_settings' => true,
            'vtupress_access_general' => false, 'vtupress_access_payment' => false, 'vtupress_access_history' => false,
            'vtupress_delete_history' => false, 'vtupress_access_users' => true, 'vtupress_access_users_action' => true,
            'vtupress_access_withdrawal' => false, 'vtupress_access_mlm' => false, 'vtupress_access_vtupress' => true,
            'vtupress_clear_history' => false
        ]
    );

    // Add 'vtupress_access_vtupress' capability to existing roles.
    $admin_role = get_role( 'vtupress_admin' );
    $admin_role->add_cap( 'vtupress_access_vtupress', true );
    $user_role = get_role( 'vtupress_user' );
    $user_role->add_cap( 'vtupress_access_vtupress', true );
    $sale_role = get_role( 'vtupress_sales' );
    $sale_role->add_cap( 'vtupress_access_vtupress', true );

    // Assign 'vtupress_admin' role to user with ID 1 if exists.
    $user = get_user_by( 'ID', 1 );
    if(isset($user) && !empty($user)){
        $user->add_role('vtupress_admin');
    }
}


// WordPress login redirection logic.
vp_addoption("wplogin_redirect", "no"); // Ensure this option is set.

add_action( 'plugins_loaded', 'wplogin_redirect', 1 ); // Hook early to prevent header issues
function wplogin_redirect() {
    vp_sessions(); // Destroy other sessions

    $verif_email = strtolower(vp_getoption("wplogin_redirect"));
    if($verif_email != "false" && $verif_email != "no" ){
        if(is_numeric(stripos($_SERVER["REQUEST_URI"],'wp-login.php')) || is_numeric(stripos($_SERVER["REQUEST_URI"],'wp-register.php'))){
            $home = get_home_url()."/vpaccount";
            // Check if headers have already been sent before redirecting.
            if (!headers_sent()) {
                wp_redirect($home);
                exit(); // Always exit after a redirect.
            } else {
                error_log("WPLOGIN_REDIRECT: Headers already sent, cannot redirect.");
            }
        }
    }
}


// Plugin activation hooks.
register_activation_hook(__FILE__, 'vtupress_create_monwebhook');
register_activation_hook(__FILE__, 'vtupress_create_trans_log');
register_activation_hook(__FILE__, 'vp_levels');
register_activation_hook(__FILE__, 'vp_kyc');
register_activation_hook(__FILE__, 'vp_kyc_settings');
register_activation_hook(__FILE__, 'vtupress_create_message');
register_activation_hook(__FILE__, 'vtupress_add_message');
register_activation_hook(__FILE__, 'create_vpaccount');
register_activation_hook(__FILE__, 'create_s_transaction');
register_activation_hook(__FILE__, 'addsdata');
register_activation_hook(__FILE__, 'create_sd_transaction');
register_activation_hook(__FILE__, 'create_sb_transaction');
register_activation_hook(__FILE__, 'addsddata');
register_activation_hook(__FILE__, 'addsbdata');
register_activation_hook(__FILE__, 'vtuchoice');
register_activation_hook(__FILE__, 'vtuchoiced');
register_activation_hook(__FILE__, 'vtupress_db_man');
register_activation_hook(__FILE__, 'vtupress_verification');
register_activation_hook(__FILE__, 'vtupress_create_profile');
register_activation_hook(__FILE__, 'vtupress_create_notification');
register_activation_hook(__FILE__,  'vtupress_roles');

// Include other plugin files.
do_action("vtupressmain");
