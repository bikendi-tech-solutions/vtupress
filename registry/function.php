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
//include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


function convertToCronSchedule($frequency = "minutes", $interval = "5")
{
    switch ($frequency) {
        case 'twice_daily':
            $duration = '0 */12 * * *'; // Runs every 12 hours
            break;
        case 'daily':
            $duration = '0 0 * * *'; // Runs daily at midnight
            break;
        case 'minutes':
            // Runs every $interval minutes
            $duration = "*/$interval * * * *";
            break;
        case 'hours':
            // Runs every $interval hour
            $duration = "* */$interval * * *";
            break;
        case 'days':
            // Runs every $interval days
            $duration = "0 0 */$interval * *";
            break;
        case 'months':
            // Runs every $interval months
            $duration = "0 0 1 */$interval *";
            break;
        case 'custom':
            // Runs every $interval months
            $duration = $interval;
            break;
        default:
            $duration = "*/5 * * * *"; // Invalid frequency
            break;
    }

    return $duration;
}

function vp_insert_registry($key, $name, $value)
{
    global $wpdb;

    if (!is_array($value)) {
        return null;
    }

    $key = strtolower($key);
    $name = strtolower($name);
    $value = $value;

    $registry_table = $wpdb->prefix . "vtupress_registry";

    $registry_table_result = $wpdb->get_results("SELECT * FROM $registry_table WHERE `key` = '$key' AND `name` = '$name' ");

    if ($registry_table_result == NULL || $registry_table_result == false) {
        $data = array(
            'key' => $key,
            'name' => $name,
            'value' => json_encode($value, JSON_UNESCAPED_SLASHES)
        );

        $wpdb->insert($registry_table, $data);

        return true;
    } else {
        return false;
    }
}

function vp_update_registry($key, $name, $value)
{
    global $wpdb;

    if (!is_array($value)) {
        return null;
    }

    $key = strtolower($key);
    $name = strtolower($name);
    $value = $value;

    $registry_table = $wpdb->prefix . "vtupress_registry";

    $registry_table_result = $wpdb->get_results("SELECT * FROM $registry_table WHERE `key` = '$key' AND `name` = '$name' ");

    if ($registry_table_result != NULL && $registry_table_result != false) {
        $data = array(
            'key' => $key,
            'name' => $name,
            'value' => json_encode($value)
        );

        $wpdb->update($registry_table, $data, ["key" => $key, "name" => $name]);

        return true;
    } else {
        return vp_insert_registry($key, $name, $value);
    }
}

function vp_query_registry($key = "", $name = "", $for = "")
{

    if (empty($key) || empty($name) || empty($for)) {
        return null;
    }

    $key = strtolower($key);
    $name = strtolower($name);
    $for = strtolower($for);

    global $wpdb;
    $registry_table_name = $wpdb->prefix . "vtupress_registry";
    $registry_table = $wpdb->get_results("SELECT * FROM $registry_table_name WHERE `key` = '$key' AND name = '$name' AND value LIKE '%\"$for\"%' ");

    if ($registry_table == NULL) {
        return null;
    } else {

        $registry_table_fetched = $registry_table[0];

        $value = $registry_table_fetched->value;
        $result = json_decode($value, true);


        if (!isset($result[$for])) {
            return null;
        } elseif (empty($result[$for])) {
            return null;
        } else {
            return $result[$for];
        }
    }
}

function fetch__path($path_value = "", $mode = "default", $key = "")
{

    if (empty($key)) {
        die("Key can't be empty");
    }

    $name = $path_value;
    $for = "path";

    $mode = strtolower($mode);
    if ($mode == "default") {

        $path = vp_query_registry($key, $name, $for);

        if (preg_match("/\//", $path)) {
            $path = ABSPATH . $path;
        }
        # $path = ABSPATH."wp-content/plugins/vtupress/crons/provider/ibro.php";

    } elseif ($mode == "custom") {
        $path = ABSPATH . $path_value;
        if (!file_exists($path)) {
            die("File does not exist");
        }


    } else {

        $path = null;
    }



    return $path;

}


$registry_version = 5;
if (vp_getoption("registry_version") != $registry_version) {
    global $wpdb;
    $stable_name = $wpdb->prefix . 'vtupress_registry';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $stable_name(
id int NOT NULL AUTO_INCREMENT,
`key` text ,
name text ,
value text ,
status varchar(255) DEFAULT 'active' ,
PRIMARY KEY (id))$charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    //install ibrolinks default value
    $value = [
        "path" => "wp-content/plugins/vtupress/crons/provider/ibro.php"
    ];
    vp_insert_registry("cron", "ibro", $value);

    vp_updateoption("registry_version", $registry_version);



}