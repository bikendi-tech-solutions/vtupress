<?php
/**
 * Core functions for the VTUPress WordPress plugin.
 *
 * This file contains custom utility functions for managing user and plugin options,
 * handling pagination, sending notifications, and other core functionalities.
 *
 * @package VTUPress
 */

set_time_limit(3000);

// Ensure WordPress environment is loaded.
if (!defined('ABSPATH')) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
}

// Suppress errors in production.
if (WP_DEBUG === false) {
    error_reporting(0);
}

include_once(ABSPATH . "wp-load.php");
include_once(ABSPATH . 'wp-admin/includes/plugin.php');

global $current_timestamp;
$current_timestamp = current_time('timestamp');

// Sanitize all incoming GET, POST, and REQUEST data as a first line of defense.
// More specific sanitization should occur at the point of use for critical data.
if (isset($_GET) && is_array($_GET)) {
    foreach ($_GET as $key => $val) {
        $_GET[$key] = sanitize_text_field($val);
    }
}
if (isset($_POST) && is_array($_POST)) {
    foreach ($_POST as $key => $val) {
        $_POST[$key] = sanitize_text_field($val);
    }
}
if (isset($_REQUEST) && is_array($_REQUEST)) {
    foreach ($_REQUEST as $key => $val) {
        $_REQUEST[$key] = sanitize_text_field($val);
    }
}

// Check for a specific file, possibly for plugin integrity or activation.
if (file_exists(__DIR__ . "/do_not_tamper.php")) {

    // Action hook for Litespeed cache control.
    do_action('litespeed_control_set_nocache', 'nocache due to logged in');

    // Default 'vend' parameter if not set in GET.
    if (!isset($_GET["vend"])) {
        $_GET["vend"] = "dashboard";
    }

    /// BEGINNING OF CUSTOM UPDATE AND ADD FUNCTIONS ///

    // Ensure these functions are not redefined if the plugin is active.
    if (!function_exists("vp_updateuser") || is_plugin_active("vtupress/vtupress.php")) {

        /**
         * Updates a specific meta key within the 'vp_user_data' JSON string for a user.
         *
         * @param int|string $id The user ID.
         * @param string $meta The meta key to update.
         * @param mixed $value The new value for the meta key.
         * @return string "true" on success, "false" on failure.
         */
        function vp_updateuser($id = "", $meta = "", $value = "")
        {
            $update_meta = get_user_meta($id, "vp_user_data", true);

            // Initialize if 'vp_user_data' doesn't exist.
            if (empty($update_meta)) {
                add_user_meta($id, "vp_user_data", '{"default":"yes"}', true);
                $update_meta = '{"default":"yes"}'; // Set for immediate use
            }

            // Attempt to decode, modify, and encode the JSON.
            try {
                $array = json_decode($update_meta, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log("VP_UPDATEUSER Error: Invalid JSON for user $id meta 'vp_user_data'. Resetting.");
                    $array = ['default' => 'yes']; // Fallback to default if JSON is malformed
                }

                $array[$meta] = $value;
                update_user_meta($id, "vp_user_data", json_encode($array));
                return "true";
            } catch (Exception $e) {
                error_log("VP_UPDATEUSER Exception for user $id meta '$meta': " . $e->getMessage());
                return "false";
            }
        }

        /**
         * Updates a specific option key within the 'vp_options' JSON string.
         *
         * @param string $meta The option key to update.
         * @param mixed $value The new value for the option key.
         * @return string "true" on success, "false" on failure.
         */
        function vp_updateoption($meta = "", $value = "")
        {
            $options_json = get_option("vp_options");

            // Initialize if 'vp_options' doesn't exist.
            if (empty($options_json)) {
                add_option("vp_options", '{"default":"yes"}', '', 'no'); // 'no' for autoload
                $options_json = '{"default":"yes"}'; // Set for immediate use
            }

            // Attempt to decode, modify, and encode the JSON.
            try {
                $array = json_decode($options_json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log("VP_UPDATEOPTION Error: Invalid JSON for option 'vp_options'. Resetting.");
                    $array = ['default' => 'yes']; // Fallback to default if JSON is malformed
                }

                $array[$meta] = $value;
                update_option("vp_options", json_encode($array));
                return "true";
            } catch (Exception $e) {
                error_log("VP_UPDATEOPTION Exception for option '$meta': " . $e->getMessage());
                return "false";
            }
        }

        /// END OF CUSTOM UPDATE AND ADD FUNCTIONS ///

        /// BEGINNING OF CUSTOM GET FUNCTIONS ///

        /**
         * Retrieves a specific option value, migrating it to 'vp_options' JSON if found as individual option.
         *
         * @param string $meta The option key to retrieve.
         * @return mixed The option value, or "false" if not found.
         */
        function vp_getoption($meta = "", $default = "false")
        {
            // WordPress core options should be retrieved directly.
            if (in_array($meta, ["siteurl", "blogname", "home", "admin_email", "blogdescription"])) {
                return get_option($meta);
            }

            $value = get_option($meta); // Check for individual option first
            $val = get_option("vp_options"); // Get the main JSON option

            if ($value !== false && $meta !== "vp_options") {
                // If found as individual option, migrate it to the JSON structure.
                vp_updateoption($meta, $value);
                delete_option($meta); // Remove the individual option.

                // Now retrieve from the updated JSON.
                $array = json_decode($val, true); // Re-decode after potential update
                if (isset($array[$meta])) {
                    return $array[$meta];
                } else {
                    return $default;
                }
            } else {
                // If not found as individual, or if it's the 'vp_options' itself.
                if ($val !== false) {
                    $array = json_decode($val, true);
                    if (isset($array[$meta])) {
                        return $array[$meta];
                    } else {
                        return $default;
                    }
                } else {
                    return $default;
                }
            }
        }

        /**
         * Retrieves a specific user meta value, migrating it to 'vp_user_data' JSON if found as individual user meta.
         *
         * @param int|string $id The user ID.
         * @param string $meta The meta key to retrieve.
         * @param bool $single Whether to return a single value (true) or an array of values (false).
         * @return mixed The user meta value, or "false" if not found.
         */
        function vp_getuser($id = "", $meta = "", $single = true)
        {
            $getdata = get_user_meta($id, "vp_user_data", true); // Get the main JSON user meta
            $get_meta = get_user_meta($id, $meta, true); // Check for individual user meta

            // Check if user meta exists explicitly on wp_usermeta and is not 'vp_user_data' itself.
            if ($meta !== "vp_user_data" && (!empty($get_meta) || $get_meta === "0")) {
                // If found as individual meta, migrate it to the JSON structure.
                vp_updateuser($id, $meta, $get_meta);
                delete_user_meta($id, $meta); // Delete the individual user meta.

                // Now retrieve from the updated JSON.
                $array = json_decode($getdata, true); // Re-decode after potential update
                if (isset($array[$meta])) {
                    return $array[$meta];
                } else {
                    return "false";
                }
            } else {
                // If not found as individual, or if it's the 'vp_user_data' itself.
                if (!empty($getdata)) {
                    $array = json_decode($getdata, true);
                    if (isset($array[$meta])) {
                        return $array[$meta];
                    } else {
                        return "false";
                    }
                } else {
                    return "false";
                }
            }
        }

        /**
         * Safely retrieves a value from a provided options array.
         * If not found in the array, attempts to retrieve it via vp_getoption (which might trigger migration).
         *
         * @param array $array The options array.
         * @param string $meta The key to retrieve.
         * @return mixed The value, or "false" if not found.
         */
        function vp_option_array($array = [], $meta = "")
        {
            if (isset($array[$meta]) && !empty($array[$meta])) {
                return $array[$meta];
            } else {
                return vp_getoption($meta); // Fallback to vp_getoption for potential migration
            }
        }

        /**
         * Safely retrieves a value from a provided user meta array.
         * If not found in the array, attempts to retrieve it via vp_getuser (which might trigger migration).
         *
         * @param array $array The user meta array.
         * @param int|string $id The user ID.
         * @param string $meta The key to retrieve.
         * @param bool $single Whether to return a single value.
         * @return mixed The value, or "false" if not found.
         */
        function vp_user_array($array = [], $id = "", $meta = "", $single = true)
        {
            if (isset($array[$meta]) && !empty($array[$meta])) {
                return $array[$meta];
            } else {
                return vp_getuser($id, $meta, $single); // Fallback to vp_getuser for potential migration
            }
        }

        /// END OF CUSTOM GET FUNCTIONS ///

        /**
         * Deletes the 'vp_user_data' JSON string for a user.
         *
         * @param int|string $id The user ID.
         * @return string "true" on success.
         */
        function vp_deleteuser($id = "")
        {
            delete_user_meta($id, "vp_user_data");
            return "true";
        }

        /**
         * Adds a new meta key-value pair to the 'vp_user_data' JSON string for a user.
         * Prevents adding if the key already exists.
         *
         * @param int|string $id The user ID.
         * @param string $meta The meta key to add.
         * @param mixed $value The value for the new meta key.
         * @return string "true" on success, "false" if key already exists or on failure.
         */
        function vp_adduser($id = "", $meta = "", $value = "")
        {
            $ths = get_user_meta($id, "vp_user_data", true);
            if (empty($ths)) {
                add_user_meta($id, "vp_user_data", '{"default":"yes"}', true);
                $ths = '{"default":"yes"}';
            }

            try {
                $array = json_decode($ths, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log("VP_ADDUSER Error: Invalid JSON for user $id meta 'vp_user_data'. Resetting.");
                    $array = ['default' => 'yes'];
                }

                if (isset($array[$meta])) {
                    return "false"; // Key already exists
                } else {
                    $array[$meta] = $value;
                    update_user_meta($id, "vp_user_data", json_encode($array));
                    return "true";
                }
            } catch (Exception $e) {
                error_log("VP_ADDUSER Exception for user $id meta '$meta': " . $e->getMessage());
                return "false";
            }
        }

        /**
         * Adds a new option key-value pair to the 'vp_options' JSON string.
         * Prevents adding if the key already exists.
         *
         * @param string $meta The option key to add.
         * @param mixed $value The new value for the new option key.
         * @return string "true" on success, "false" if key already exists or on failure.
         */
        function vp_addoption($meta = "", $value = "")
        {
            $options_json = get_option("vp_options");
            if (empty($options_json)) {
                add_option("vp_options", '{"default":"yes"}', '', 'no');
                $options_json = '{"default":"yes"}';
            }

            try {
                $array = json_decode($options_json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    error_log("VP_ADDOPTION Error: Invalid JSON for option 'vp_options'. Resetting.");
                    $array = ['default' => 'yes'];
                }

                if (isset($array[$meta])) {
                    return "false"; // Key already exists
                } else {
                    $array[$meta] = $value;
                    update_option("vp_options", json_encode($array));
                    return "true";
                }
            } catch (Exception $e) {
                error_log("VP_ADDOPTION Exception for option '$meta': " . $e->getMessage());
                return "false";
            }
        }
    } // End if(!function_exists("vp_updateuser") || is_plugin_active("vtupress/vtupress.php"))


    
    /**
     * COUNTRY SWITCH
     */

    function vp_country()
    {
        $vp_country = vp_getoption("vp_country", "ng");
        switch ($vp_country) {
            case "ng":
                $vp_services = [
                    "bypass" => false,
                    "currency" => "NGN",
                    "symbol" => "₦",
                    "line_prefix" => "234",
                    "country" => "ng",
                    "minimum_trans_amount" => 50,
                    "airtime" => true,
                    "data" => true,
                    "cabletv" => true,
                    "electricity" => true,
                    "education" => true,
                    "betting" => true,
                    "bills" => true,
                    "others" => true
                ];
                break;
            case "gh":
                $vp_services = [
                    "bypass" => true,
                    "currency" => "GHS",
                    "symbol" => "₵",
                    "line_prefix" => "233",
                    "country" => "gh",
                    "minimum_trans_amount" => 0.50,
                    "airtime" => true,
                    "data" => true,
                    "cabletv" => false,
                    "electricity" => false,
                    "education" => false,
                    "betting" => false,
                    "bills" => false,
                    "others" => false
                ];
            break;
        }
        $networks = [
                    "glo" => vp_getoption("filter_glo","GLO"),
                    "9mobile" => vp_getoption("filter_9mobile","9MOBILE"),
                    "mtn" => vp_getoption("filter_mtn","MTN"),
                    "airtel" => vp_getoption("filter_airtel","AIRTEL"),
        ];

        // print_r($vp_services);
        // die();
        return array_merge($vp_services,$networks);
    }

    /**
     * Fetches content from a URL using WordPress's HTTP API.
     *
     * @param string $url The URL to fetch.
     * @return string The response body, or an error message.
     */
    function vp_get_contents($url)
    {
        $response = wp_remote_get(esc_url_raw($url));
        if (is_wp_error($response)) {
            error_log("VP_GET_CONTENTS Error: " . $response->get_error_message());
            return "error: " . $response->get_error_message(); // Return error message
        }
        return wp_remote_retrieve_body($response);
    }

    /**
     * Generates pagination HTML for backend/admin pages.
     *
     * @param string $name Unique name for this pagination instance.
     * @param string $altname Alternative name for query parameters.
     * @param string $dbname Database table name.
     * @param string $var Global variable name to store results.
     * @param string $where SQL WHERE clause (should be pre-sanitized or built with wpdb->prepare).
     * @return void Echos HTML.
     */
    function pagination_before($name = "", $altname = "", $dbname = "", $var = "none", $where = "")
    {
        global $post, $wpdb, ${$var};

        $limit = isset($_REQUEST["{$altname}{$name}-limit-records"]) ? intval($_REQUEST["{$altname}{$name}-limit-records"]) : 10;
        $page = isset($_GET["{$altname}{$name}-page"]) ? intval($_GET["{$altname}{$name}-page"]) : 1;
        $start = ($page - 1) * $limit;

        $table_name = $wpdb->prefix . $dbname;

        // Ensure $where is safe. If it contains user input, it MUST be prepared.
        // For simplicity, assuming $where is already safe or built from whitelisted values.
        // For dynamic user input in $where, use $wpdb->prepare like:
        // $query = $wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND status = %s ORDER BY ID DESC LIMIT %d, %d", $user_id, $status, $start, $limit);
        // For this function, we'll assume $where is either empty or already safe.
        $query = "SELECT * FROM {$table_name} {$where} ORDER BY ID DESC LIMIT %d, %d";
        ${$var} = $wpdb->get_results($wpdb->prepare($query, $start, $limit));

        if (!isset(${$var})) {
            echo "NO DATABASE FOUND FOR {$var}";
        } elseif (empty(${$var})) {
            echo "{$var} IS EMPTY";
        }

        $num = $wpdb->get_var("SELECT count(id) AS id FROM {$table_name} {$where}");
        $pages = ceil($num / $limit);

        $cur_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

        $Previous = max(1, $page - 1);
        $Next = min($pages, $page + 1);

        echo '
        <div class="container well">
            <div class="row mt-3 md-2">
                <div class=" col-md-10">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="mx-2">
                                <a href="?page=' . esc_attr($cur_page) . '&' . esc_attr($altname) . esc_attr($name) . '-page=' . esc_attr($Previous) . '&' . esc_attr($altname) . esc_attr($name) . '-limit-records=' . esc_attr($limit) . '#' . esc_attr($name) . '" aria-label="Previous">
                                    <span aria-hidden="true">&laquo; Previous</span>
                                </a>
                            </li>
                        ';
        for ($i = 1; $i <= $pages; $i++) {
            $color = (isset($_GET["{$altname}{$name}-page"]) && intval($_GET["{$altname}{$name}-page"]) === $i) ? "text-danger" : "text-primary";
            echo '
                            <li class="border border-primary px-2"><a class="' . esc_attr($color) . '" href="?page=' . esc_attr($cur_page) . '&' . esc_attr($altname) . esc_attr($name) . '-page=' . esc_attr($i) . '&' . esc_attr($altname) . esc_attr($name) . '-limit-records=' . esc_attr($limit) . '#' . esc_attr($name) . '">' . esc_html($i) . '</a></li>
                            ';
        }

        echo '
                            <li class="mx-2">
                                <a href="?page=' . esc_attr($cur_page) . '&' . esc_attr($altname) . esc_attr($name) . '-page=' . esc_attr($Next) . '&' . esc_attr($altname) . esc_attr($name) . '-limit-records=' . esc_attr($limit) . '#' . esc_attr($name) . '" aria-label="Next">
                                    <span aria-hidden="true">Next &raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="text-center col-md-2">
                    <form >
                        <div class="input-group">
                            <span class="input-group-text">Limit</span>
                            <select class="" name="' . esc_attr($altname) . esc_attr($name) . '-limit-records" id="' . esc_attr($altname) . esc_attr($name) . '-limit-records">
                            ';

        foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 200, 250, 300, 350, 400, 450, 500, 700, 1000, 2000] as $limit_option) {
            $echo = (isset($_GET["{$altname}{$name}-limit-records"]) && intval($_GET["{$altname}{$name}-limit-records"]) === $limit_option) ? "selected" : "opt";
            echo '
                                <option ' . esc_attr($echo) . ' value="' . esc_attr($limit_option) . '">' . esc_html($limit_option) . '</option>
                                ';
        }
        echo '
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div>
        ';
    }

    /**
     * Generates closing HTML for backend/admin pagination.
     *
     * @param string $name Unique name for this pagination instance.
     * @param string $altname Alternative name for query parameters.
     * @param string $dbname Database table name.
     * @return void Echos HTML and JavaScript.
     */
    function pagination_after($name = "", $altname = "", $dbname = "none")
    {
        $cur_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
        echo '
        </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $("select#' . esc_attr($altname) . esc_attr($name) . '-limit-records").change(function(){
                    var val = $(this).val();
                    window.location = "?page=' . esc_attr($cur_page) . '&' . esc_attr($altname) . esc_attr($name) . '-page=1&' . esc_attr($altname) . esc_attr($name) . '-limit-records="+val+"#' . esc_attr($name) . '";
                });
            });
        </script>
        ';
    }

    /**
     * Generates pagination HTML for frontend pages.
     *
     * @param string $url Base URL for pagination links.
     * @param string $name Unique name for this pagination instance.
     * @param string $altname Alternative name for query parameters.
     * @param string $dbname Database table name.
     * @param string $var Global variable name to store results.
     * @param string $where SQL WHERE clause (should be pre-sanitized or built with wpdb->prepare).
     * @return void Echos HTML.
     */
    function pagination_before_front($url = "", $name = "", $altname = "", $dbname = "", $var = "none", $where = "")
    {
        global $post, $wpdb, ${$var};

        $limit = isset($_REQUEST["limit-records"]) ? intval($_REQUEST["limit-records"]) : 10;
        $page = isset($_GET['pages']) ? intval($_GET['pages']) : 1;
        $start = ($page - 1) * $limit;

        $recipient = '';
        if (isset($_GET["recipient"])) {
            $recipient = sanitize_text_field($_GET["recipient"]);
            if (isset($_GET["type"])) {
                $type = sanitize_text_field($_GET["type"]);
                // IMPORTANT: Use $wpdb->prepare for dynamic WHERE clauses to prevent SQL injection.
                // The original code directly concatenated $recipient, which is unsafe.
                if ($type === "airtime" || $type === "data") {
                    $where .= $wpdb->prepare(" AND phone LIKE %s", '%' . $wpdb->esc_like($recipient) . '%');
                } elseif ($type === "cable" || $type === "sms") {
                    $where .= $wpdb->prepare(" AND recipient LIKE %s", '%' . $wpdb->esc_like($recipient) . '%');
                } elseif ($type === "bill") {
                    $where .= $wpdb->prepare(" AND iucno LIKE %s", '%' . $wpdb->esc_like($recipient) . '%');
                }
            }
        }

        $table_name = $wpdb->prefix . $dbname;
        $query = "SELECT * FROM {$table_name} {$where} ORDER BY ID DESC LIMIT %d, %d";
        ${$var} = $wpdb->get_results($wpdb->prepare($query, $start, $limit));

        $num = $wpdb->get_var("SELECT count(id) AS id FROM {$table_name} {$where}");
        $pages = ceil($num / $limit);

        $for = isset($_GET["for"]) ? sanitize_text_field($_GET["for"]) : '';
        $type = isset($_GET["type"]) ? sanitize_text_field($_GET["type"]) : '';

        echo '
        <div class="container well">
            <div class="row mt-3 md-2">
                <div class=" col-md-9 table-responsive pe-2">
                    <div class="input-group">
                        <span class="input-group-text">Page</span>
                        <select class="change-page float-left" onchange="changepage();">
                        ';
        for ($i = 1; $i <= $pages; $i++) {
            $selected = (isset($_GET["pages"]) && intval($_GET["pages"]) === $i) ? 'disabled selected' : '';
            $class = (isset($_GET["pages"]) && intval($_GET["pages"]) === $i) ? 'text-danger' : 'text-primary';
            echo '
                            <option value="' . esc_attr($i) . '" ' . esc_attr($selected) . ' class="' . esc_attr($class) . '" >' . esc_html($i) . '</option>
                            ';
        }

        echo '
                        </select>
                        <script>
                            jQuery(".change-page option:not(.opt)").hide();
                            function changepage(){
                                var pg = jQuery(".change-page").val();
                                window.location.href = "' . esc_url($url) . '&for=' . esc_attr($for) . '&type=' . esc_attr($type) . '&pages="+pg+"&limit-records=' . esc_attr($limit) . '";
                            }
                        </script>
                    </div>
                </div>
                <div class="text-center col-md-3">
                    <form >
                        <div class="input-group">
                            <span class="input-group-text">Limit</span>
                            <select class="" name="limit-records" id="limit-records">
                            ';

        foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 200, 250, 300, 350, 400, 450, 500, 700, 1000, 2000] as $limit_option) {
            $echo = (isset($_GET["limit-records"]) && intval($_GET["limit-records"]) === $limit_option) ? "selected" : "opt";
            echo '
                                <option ' . esc_attr($echo) . ' value="' . esc_attr($limit_option) . '">' . esc_html($limit_option) . '</option>
                                ';
        }
        echo '
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0 border rounded-pill search-trans" placeholder="Search By Recipient Number" value="' . esc_attr($recipient) . '"/>
                        <span class="input-group-append">
                            <button onclick="searchtrans(jQuery(\'.search-trans\').val())" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                        <script>
                            function searchtrans(value){
                                var pg = jQuery(".change-page").val();
                                window.location.href = "' . esc_url($url) . '&for=' . esc_attr($for) . '&type=' . esc_attr($type) . '&pages="+pg+"&limit-records=' . esc_attr($limit) . '&recipient="+value;
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div>
        ';
    }

    /**
     * Generates closing HTML for frontend pagination.
     *
     * @param string $url Base URL for pagination links.
     * @param string $name Unique name for this pagination instance.
     * @param string $altname Alternative name for query parameters.
     * @param string $dbname Database table name.
     * @return void Echos HTML and JavaScript.
     */
    function pagination_after_front($url = "", $name = "", $altname = "", $dbname = "none")
    {
        echo '
        </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $("select#limit-records").change(function(){
                    var val = $(this).val();
                    window.location = window.location.href.split("&limit-records")[0] + "&limit-records="+val; // Avoid appending if already exists
                });
            });
        </script>
        ';
    }


    /**
     * Enqueues frontend JavaScript and CSS assets for VTUPress.
     * This function is hooked to 'wp_enqueue_scripts'.
     *
     * @return void
     */
    function vtupress_js_css_user()
    {
        wp_enqueue_script('vtupress-bootstrap-js', plugins_url('vtupress/js/bootstrap.min.js'), array('jquery'), '1.0', true);
        wp_enqueue_script('vtupress-jquery', plugins_url('vtupress/js/jquery.js'), array(), '1.0', true); // jQuery is often already enqueued by WP
        wp_enqueue_script('vtupress-sweet-alert', plugins_url('vtupress/js/sweet.js'), array(), '1.0', true);
        wp_enqueue_script('vtupress-pdf', plugins_url('vtupress/js/pdf.js'), array(), '1.0', true);
        wp_enqueue_script('vtupress-print', plugins_url('vtupress/js/print.js'), array(), '1.0', true);

        wp_enqueue_style('vtupress-bootstrap-css', plugins_url('vtupress/css/bootstrap.min.css'), array(), '1.0');
        wp_enqueue_style('vtupress-font-awesome', plugins_url('vtupress/css/font-awesome.min.css'), array(), '1.0');
        wp_enqueue_style('vtupress-print-css', plugins_url('vtupress/css/print.css'), array(), '1.0');
        wp_enqueue_style('vtupress-all-min-css', plugins_url('vtupress/css/all.min.css'), array(), '1.0');
    }
    add_action('wp_enqueue_scripts', 'vtupress_js_css_user');

    /**
     * Enqueues plain frontend JavaScript and CSS assets for VTUPress (possibly for specific pages).
     *
     * @return void
     */
    function vtupress_js_css_user_plain()
    {
        wp_enqueue_script('vtupress-bootstrap-js-plain', plugins_url('vtupress/js/bootstrap.min.js'), array('jquery'), '1', true);
        wp_enqueue_script('vtupress-jquery-plain', plugins_url('vtupress/js/jquery.js'), array(), '1', true);
        wp_enqueue_script('vtupress-sweet-alert-plain', plugins_url('vtupress/js/sweet.js'), array(), '1', true);
        wp_enqueue_script('vtupress-pdf-plain', plugins_url('vtupress/js/pdf.js'), array(), '1', true);
        wp_enqueue_script('vtupress-print-plain', plugins_url('vtupress/js/print.js'), array(), '1', true);

        wp_enqueue_style('vtupress-all-min-css-plain', plugins_url('vtupress/css/all.min.css'), array(), '1');
        wp_enqueue_style('vtupress-print-css-plain', plugins_url('vtupress/css/print.css'), array(), '1');
    }

    /**
     * Enqueues admin JavaScript and CSS assets for VTUPress.
     * This function should be hooked to 'admin_enqueue_scripts'.
     *
     * @return void
     */
    function vtupress_js_css_user_plain_admin()
    {
        wp_enqueue_script('vtupress-jquery-admin', plugins_url('vtupress/js/jquery.js'), array(), '1', true);
        wp_enqueue_script('vtupress-bootstrap-js-admin', plugins_url('vtupress/js/bootstrap.min.js'), array('jquery'), '1', true);
        wp_enqueue_script('vtupress-sweet-alert-admin', plugins_url('vtupress/js/sweet.js'), array(), '1', true);
        wp_enqueue_script('vtupress-pdf-admin', plugins_url('vtupress/js/pdf.js'), array(), '1', true);
        wp_enqueue_script('vtupress-print-admin', plugins_url('vtupress/js/print.js'), array(), '1', true);

        wp_enqueue_style('vtupress-bootstrap-css-admin', plugins_url('vtupress/css/bootstrap.min.css'), array(), '1');
        wp_enqueue_style('vtupress-font-awesome-admin', plugins_url('vtupress/css/font-awesome.min.css'), array(), '1');
        wp_enqueue_style('vtupress-all-min-css-admin', plugins_url('vtupress/css/all.min.css'), array(), '1');
        wp_enqueue_style('vtupress-print-css-admin', plugins_url('vtupress/css/print.css'), array(), '1');
    }
    // Add this action hook if you want these scripts/styles specifically in the admin area
    // add_action('admin_enqueue_scripts', 'vtupress_js_css_user_plain_admin');


    // Initial option additions (should be handled on plugin activation).
    vp_addoption("vp_whatsapp_group", "link");
    vp_addoption("vp_template", "default");

    // Template selection logic.
    $vp_temp = vp_getoption("vp_template");
    if (vp_getoption("resell") !== "yes") {
        $vp_temp = "default";
    } elseif ($vp_temp !== "default" && $vp_temp !== "classic" && !is_plugin_active("$vp_temp/$vp_temp.php")) {
        $vp_temp = "default";
    }
    define('vtupress_template', $vp_temp);



    global $vp_country,$symbol,$currency;
    $vp_country = vp_country();
    $symbol = $vp_country["symbol"];
    $currency = $vp_country["currency"];
    $country = $vp_country["country"];
    $minimum_trans_amount = $vp_country["minimum_trans_amount"];
    /**
     * Updates KYC (Know Your Customer) limits and displays a notification.
     * This function seems to be called directly or within a hook.
     *
     * @return void Echos HTML if KYC verification is needed.
     */
    function vp_kyc_update()
    {
        global $current_timestamp, $wpdb;

        if (!is_user_logged_in()) {
            return; // Only run for logged-in users.
        }

        $id = get_current_user_id();
        $option_array = json_decode(get_option("vp_options"), true);
        $user_array = json_decode(get_user_meta($id, "vp_user_data", true), true);

        $kyc_status = vp_user_array($user_array, $id, 'vp_kyc_status', true);
        $kyc_end = vp_user_array($user_array, $id, 'vp_kyc_end', true);
        $kyc_total = vp_user_array($user_array, $id, 'vp_kyc_total', true);

        $table_name = $wpdb->prefix . "vp_kyc_settings";
        $kyc_data_results = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");

        if (empty($kyc_data_results)) {
            error_log("VP_KYC_UPDATE: KYC settings not found in database.");
            return;
        }
        $kyc_data = $kyc_data_results[0]; // Get the first row

        if (strtolower($kyc_status) !== "verified" && strtolower($kyc_data->enable) === "yes") {
            $datenow = date("Y-m-d", $current_timestamp);
            $next_end_date = $kyc_end;

            // Check if current date is less than next end date or if next end date is zero/empty.
            if ($next_end_date === "0" || empty($next_end_date) || $datenow >= $next_end_date) {
                // Reset KYC total and set new end date if duration passed or not set.
                if (strtolower($kyc_data->duration) === "day") {
                    vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 days")));
                    vp_updateuser($id, 'vp_kyc_total', "0");
                } elseif (strtolower($kyc_data->duration) === "month") {
                    vp_updateuser($id, "vp_kyc_end", date('Y-m-d', strtotime($datenow . " +1 month")));
                    vp_updateuser($id, 'vp_kyc_total', "0");
                }
            }

            // Re-fetch updated KYC values after potential reset.
            $user_array = json_decode(get_user_meta($id, "vp_user_data", true), true);
            $kyc_total = vp_user_array($user_array, $id, 'vp_kyc_total', true);

            $used = empty($kyc_total) ? 0 : floatval($kyc_total);
            $allowed_duration_text = ucfirst($kyc_data->duration);
            $total_text = ($allowed_duration_text === "Total") ? "Total" : "One " . $allowed_duration_text;
            $limit = floatval($kyc_data->kyc_limit);

            global $symbol;
            echo "
            <div class='row my-3'>
                <div class='col font-bold text-white bg bg-danger p-3 rounded shadow'>
                    You have consumed $symbol" . esc_html($used) . " out of $symbol" . esc_html($limit) . " in " . esc_html($total_text) . "
                    <br>
                    <small>Please verify your account <b><a style='text-decoration:none;' class='text-white' href='?vend=kyc'>{ Here }</a></b></small>
                </div>
            </div>
            ";
        }
    }


    /**
     * Safely retrieves a value, returning "No Value" if not set.
     *
     * @param mixed $value The value to check.
     * @return mixed The value, or "No Value".
     */
    function vp_getvalue($value = "")
    {
        return isset($value) ? $value : "No Value";
    }

    // Scheduled task logic (consider migrating to WP Cron API).
    if (strtolower(vp_getoption('enable-schedule')) === "yes") {
        $time = date('h:i:s', $current_timestamp);
        if (vp_getoption('next-schedule') <= $time) {
            vp_updateoption('last-schedule', $time);
            $response = wp_remote_get(home_url("/wp-content/plugins/vtupress/query.php"));
            if (is_wp_error($response)) {
                error_log("VP_SCHEDULE Error: " . $response->get_error_message());
            }
            $now = date('h:i:s', strtotime(date('h:i:s', $current_timestamp) . '+' . intval(vp_getoption("schedule-time")) . ' Minutes'));
            vp_updateoption("next-schedule", $now);
        }
    }


    /**
     * Sends transaction-related emails and SMS notifications to admin and/or user.
     *
     * @param string $subject Email subject.
     * @param string $topic Main topic/heading for the email.
     * @param string $transaction Transaction ID.
     * @param string $purchased Description of what was purchased.
     * @param string $recipient Recipient of the service (e.g., phone number, meter no).
     * @param float $amount Transaction amount.
     * @param float $prev Previous balance.
     * @param float|string $now Current balance or "Not Applicable".
     * @param bool $admin Whether to send email to admin.
     * @param bool $user Whether to send email to user.
     * @return void
     */
    function vp_transaction_email($subject = "", $topic = "", $transaction = "", $purchased = "", $recipient = "", $amount = "", $prev = "", $now = "", $admin = true, $user = true)
    {
        $verify_email = strtolower(vp_getoption("email_transaction"));
        $id = get_current_user_id();
        $username = get_userdata($id) ? get_userdata($id)->user_login : 'Unknown User';
        $user_email = get_userdata($id) ? get_userdata($id)->user_email : 'unknown@example.com';

        if ($verify_email !== "false" && $verify_email !== "no") {
            $email_headers = array('Content-Type: text/html; charset=UTF-8');
            global $symbol;

            // Build the HTML message without sprintf
            $message_template =
                '<div style="height:fit-content">
                <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin:10px auto; text-align:center; color:white; font-family:cursive; font-size:2em;">
                    <span>' . esc_html($topic) . '</span>
                </div>
                <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin:10px auto; text-align:left; color:black; font-family:sans-serif; font-size:1em;">
                    <p>Username: ' . esc_html($username) . '</p>
                    <p>Email: ' . esc_html($user_email) . '</p>
                    <p>Transaction ID: ' . esc_html($transaction) . '</p>
                    <p>Purchased: ' . esc_html($purchased) . '</p>
                    <p>Recipient: ' . esc_html($recipient) . '</p>
                    <p>Total Amount: ' .$symbol. esc_html($amount) . '</p>
                </div>
                <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin:10px auto; color:white; font-family:cursive; font-size:1em;">
                    <b style="float:left">Previous: ' . esc_html($prev) . '</b>
                    <b style="float:right">Now: ' . esc_html($now) . '</b>
                </div>
            </div>';

            // Send to admin
            if ($admin) {
                $admin_email = get_option("admin_email");
                $admin_subject = "ADMIN NOTICE: " . $subject;
                wp_mail($admin_email, $admin_subject, $message_template, $email_headers);
            }

            // Send to user
            if ($user) {
                wp_mail($user_email, $subject, $message_template, $email_headers);
            }

            // --- SMS Logic ---
            $http_args = array(
                'headers' => array('Content-Type' => 'application/json'),
                'timeout' => 120,
                'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
                'sslverify' => false
            );

            $site = substr(get_bloginfo('name'), 0, 10);
            $sms_message = str_replace(
                ["MTN", "GLO", "AIRTEL", "9MOBILE", "₦", "cash"],
                ["M|N", "G|O", "A|RTEL", "9MOB|LE", "NGN", "cach"],
                $purchased
            );
            $sms_message .= " by " . $username;
            $token = vp_getoption("smspostvalue1");

            if (strtolower(vp_getoption("sms_transaction_admin")) === "yes" && !empty($token)) {
                if (stripos(vp_getoption("smsbaseurl"), "bulksmsnigeria") !== false && (stripos($topic, "airtime") !== false || stripos($topic, "data") !== false)) {
                    $phone = "0" . vp_getoption("vp_phone_line");
                    wp_remote_get("https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=" . esc_attr($token) . "&from=" . esc_attr($site) . "&to=" . esc_attr($phone) . "&body=" . urlencode($sms_message) . "&dnd=1", $http_args);
                }
            }

            if (strtolower(vp_getoption("sms_transaction_user")) === "yes" && !empty($token)) {
                if (stripos(vp_getoption("smsbaseurl"), "bulksmsnigeria") !== false && (stripos($topic, "airtime") !== false || stripos($topic, "data") !== false)) {
                    $phone = vp_getuser($id, 'vp_phone', true);
                    wp_remote_get("https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=" . esc_attr($token) . "&from=" . esc_attr($site) . "&to=" . esc_attr($phone) . "&body=" . urlencode($sms_message) . "&dnd=1", $http_args);
                }
            }
        }
    }



    /**
     * Sends an admin email notification and logs it to vp_notifications table.
     *
     * @param string $subject Email subject.
     * @param string $message Email body message.
     * @param string $type Notification type.
     * @param string $link Admin link (not used in message, but kept for consistency).
     * @return void
     */
    function vp_admin_email($subject = "", $message = "", $type = "", $link = "#")
    {
        global $current_timestamp, $wpdb;

        $uuid = get_current_user_id();
        $sd_name = $wpdb->prefix . 'vp_notifications';
        $wpdb->insert($sd_name, array(
            'user_id' => $uuid,
            'title' => sanitize_text_field($subject),
            'type' => sanitize_text_field($type),
            'admin_link' => "", // Link not used in message, so empty.
            'user_link' => "", // Link not used in message, so empty.
            'message' => sanitize_textarea_field($message),
            'status' => "unread",
            'the_time' => date('Y-m-d H:i:s A', $current_timestamp)
        ));

        $admin_email = get_option("admin_email");
        $email_headers = array('Content-Type: text/html; charset=UTF-8');

        $email_body = <<<EOB
<div style="height:fit-content">
    <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
        <span style="" > {subject} </span>
    </div>
    <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;">
        <p>{message}</p>
    </div>
    <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
    </div>
</div>
EOB;

        $formatted_message = str_replace(
            ['{subject}', '{message}'],
            [esc_html($subject), esc_html($message)],
            $email_body
        );
        wp_mail($admin_email, $subject, $formatted_message, $email_headers);
    }

    /**
     * Sends ban notification emails to admin and the banned user.
     *
     * @return void
     */
    function vp_ban_email()
    {
        $id = get_current_user_id();
        $username = get_userdata($id) ? get_userdata($id)->user_login : 'Unknown User';
        $user_email = get_userdata($id) ? get_userdata($id)->user_email : 'unknown@example.com';
        $admin_email = get_option("admin_email");

        $email_headers = array('Content-Type: text/html; charset=UTF-8');

        // Admin Email
        $admin_subject = "ADMIN BAN NOTICE: Hacker Detected And Banned";
        $admin_message_template = <<<EOB
<div style="height:fit-content">
    <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
        <span style="" > New Hacker Detected And Banned </span>
    </div>
    <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;">
        <p>Username: {username}</p>
        <p>Email: {email}</p>
        <p>User With The Above Details Has Been Banned Due To A Suspicious Occurence On Account</p>
    </div>
    <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
        <b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>
    </div>
</div>
EOB;
        $admin_formatted_message = str_replace(
            ['{username}', '{email}'],
            [esc_html($username), esc_html($email)],
            $admin_message_template
        );
        wp_mail($admin_email, $admin_subject, $admin_formatted_message, $email_headers);

        // User Email
        $user_subject = "NEW BAN NOTICE";
        $user_message_template = <<<EOB
<div style="height:fit-content">
    <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
        <span style="" > You Are Banned!!! </span>
    </div>
    <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;">
        <p>Username: {username}</p>
        <p>Email: {email}</p>
        <p>You Are Banned Due To A Suspicious Occurence On Your Account! Kindly Contact Admin If This Decision Is A Mistake</p>
    </div>
    <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
        <b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>
    </div>
</div>
EOB;

        $user_formatted_message = str_replace(
            ['{username}', '{email}'],
            [esc_html($username), esc_html($user_email)],
            $user_message_template
        );

        wp_mail($user_email, $user_subject, $user_formatted_message, $email_headers);
    }


    /**
     * Destroys all other WordPress sessions for the current user.
     *
     * @return void
     */
    function vp_sessions()
    {
        if (is_user_logged_in() && !current_user_can('administrator') && !current_user_can('vtupress_admin')) {
            $user_id = get_current_user_id();
            $sessions = WP_Session_Tokens::get_instance($user_id);
            $sessions->destroy_others(wp_get_session_token());
        }
    }


    /**
     * Blocks a user by setting their 'vp_user_access' to "ban" and sending notification emails.
     *
     * @param string $reason The reason for blocking the user.
     * @return void
     */
    function vp_block_user($reason = "none")
    {
        global $wpdb;
        if (is_user_logged_in() && !current_user_can("administrator")) {
            $id = get_current_user_id();
            vp_updateuser($id, 'vp_user_access', "ban");

            $arr = ['vp_ban' => 'ban'];
            $where = ['ID' => $id];
            $updated = $wpdb->update($wpdb->prefix . "users", $arr, $where);

            $display_reason = ($reason === "none") ? "Due To A Suspicious Occurence" : "FOR " . $reason;

            $username = get_userdata($id) ? get_userdata($id)->user_login : 'Unknown User';
            $user_email = get_userdata($id) ? get_userdata($id)->user_email : 'unknown@example.com';
            $admin_email = get_option("admin_email");

            $email_headers = array('Content-Type: text/html; charset=UTF-8');

            // Admin Email
            $admin_subject = "ADMIN BAN NOTICE: Hacker Detected And Banned";
            $admin_message_template = <<<EOB
<div style="height:fit-content">
    <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
        <span style="" > New Hacker Detected And Banned </span>
    </div>
    <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;">
        <p>Username: {username}</p>
        <p>Email: {email}</p>
        <p>User With The Above Details Has Been Banned {reason} On Account</p>
    </div>
    <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
        <b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>
    </div>
</div>
EOB;
            $admin_formatted_message = str_replace(
                ['{username}', '{email}', '{reason}'],
                [esc_html($username), esc_html($user_email), esc_html($display_reason)],
                $admin_message_template
            );

            wp_mail($admin_email, $admin_subject, $admin_formatted_message, $email_headers);

            // User Email
            $user_subject = "NEW BAN NOTICE";
            $user_message_template = <<<EOB
<div style="height:fit-content">
    <div style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;" >
        <span style="" > You Are Banned!!! </span>
    </div>
    <div style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;">
        <p>Username: {username}</p>
        <p>Email: {email}</p>
        <p>You Are Banned {reason} On Your Account! Kindly Contact Admin If This Decision Is A Mistake</p>
    </div>
    <div style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;" >
        <b style="float:left" > Previous: ---</b> <b style="float:right" >Now: --- </b>
    </div>
</div>
EOB;


            $user_formatted_message = str_replace(
                ['{username}', '{email}', '{reason}'],
                [esc_html($username), esc_html($user_email), esc_html($display_reason)],
                $user_message_template
            );

            wp_mail($user_email, $user_subject, $user_formatted_message, $email_headers);
        }
    }

    /**
     * Performs a remote POST request using cURL.
     * NOTE: This function is duplicated from vend-api-handlers.php.
     * It's recommended to consolidate and use wp_remote_post for consistency.
     *
     * @param string $url The URL to send the request to.
     * @param array $headers Request headers.
     * @param string $datass The POST data as a string.
     * @return string The response body, or "error" on failure.
     */
    function vp_remote_post_fn($url = "", $headers = [], $datass = "")
    {
        global $added_to_db, $wpdb, $uniqidvalue; // $table_trans is not global here, needs to be passed or re-declared
        $table_trans = $wpdb->prefix . 'vp_transactions'; // Ensure table_trans is defined

        $arra = [];
        foreach ($headers as $key => $value) {
            if (!empty($key) && !empty($value) && isset($key) && strtolower($key) !== "content-type") {
                $arra[] = $key . ": " . $value;
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0, // Consider setting a reasonable timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $datass,
            CURLOPT_HTTPHEADER => $arra,
            CURLOPT_SSL_VERIFYPEER => false // Set to true in production with proper CA certs
        ));
        $response = curl_exec($curl);
        $provider_header_response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $message = NULL;
        if ($provider_header_response >= 100 && $provider_header_response <= 199) {
            $message = "There must be something wrong with the provider I am connected to. \n It returned an Informative Http Status Code [$provider_header_response]";
        } elseif ($provider_header_response >= 300 && $provider_header_response <= 399) {
            $message = "There must be something wrong with the provider I am connected to. \n It returned a Redirection Http Status Code [$provider_header_response]";
        } elseif ($provider_header_response >= 400 && $provider_header_response <= 499) {
            $message = "There must be something wrong with the provider I am connected to. \n It returned a Client Error Response Status Code [$provider_header_response]";
        } elseif ($provider_header_response >= 500 && $provider_header_response <= 599) {
            $message = "There must be something wrong with the provider I am connected to. \n It returned a Server Error Response Status Code [$provider_header_response]";
        } elseif ($provider_header_response === 0 || $provider_header_response === null) {
            $message = "I can't identify the issue with the provider i am connected to (No HTTP Status Code received). Possible connection issue or timeout.";
        }

        if ($message !== NULL) {
            // This logic for deleting unrecorded transaction should ideally be in vend-api-handlers.php
            // and tied to the transaction rollback.
            if (isset($added_to_db) && is_numeric($added_to_db) && $added_to_db > 0) {
                $wpdb->delete($table_trans, array('request_id' => $uniqidvalue));
            }
            error_log("VP_REMOTE_POST_FN Error: " . $message . " Response: " . $response);
            return "error";
        }
        return $response;
    }

    /**
     * Terminates script execution with a plain text message.
     *
     * @param string $string The message to output before dying.
     * @return void
     */
    function vp_die($string = "")
    {
        // For plain text output, simply die with the string.
        // The previous redirect logic is removed as it conflicts with plain text output.
        die($string);
    }

    // Initial option addition (should be handled on plugin activation).
    vp_addoption("vp_check_date", date("Y-m-d h:i", $current_timestamp));


    /**
     * Gathers various user and plugin details into an array.
     * This function is used by the shortcode.
     *
     * @return array An associative array of user and plugin details.
     */
    function vtupress_user_details()
    {
        global $current_timestamp, $wpdb;

        if (!is_user_logged_in()) {
            return []; // Return empty array if user not logged in for security.
        }

        $id = get_current_user_id();
        $user_data_obj = get_userdata($id);
        if (!$user_data_obj) {
            return []; // Return empty if user data not found.
        }

        $array = [];
        $array["user_id"] = $id;
        $array["name"] = $user_data_obj->user_login;
        $array["email"] = $user_data_obj->user_email;

        $option_array = json_decode(get_option("vp_options"), true);
        $user_array = json_decode(get_user_meta($id, "vp_user_data", true), true);

        // Sensitive data should NOT be exposed via shortcodes.
        // These are fetched for internal logic, but won't be returned by the shortcode directly.
        $array["phone"] = vp_user_array($user_array, $id, "vp_phone", true);
        $array["pin"] = vp_user_array($user_array, $id, "vp_pin", true); // Highly sensitive

        $array["option_array"] = $option_array; // Exposing raw options is risky
        $array["user_array"] = $user_array;     // Exposing raw user meta is risky

        $array["kyc_status"] = vp_user_array($user_array, $id, 'vp_kyc_status', true);
        $array["kyc_end"] = vp_user_array($user_array, $id, 'vp_kyc_end', true);
        $array["kyc_total"] = vp_user_array($user_array, $id, 'vp_kyc_total', true);

        $table_name_kyc_settings = $wpdb->prefix . "vp_kyc_settings";
        $array["kyc_data"] = $wpdb->get_results("SELECT * FROM $table_name_kyc_settings WHERE id = 1");

        $array["admin_whatsapp"] = vp_option_array($option_array, "vp_whatsapp");

        $my_pv = intval(get_user_meta($id, "vp_user_pv", true)); // Assuming vp_user_pv is stored as individual meta
        $tab_pv_rules = $wpdb->prefix . 'vp_pv_rules';
        $rules = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tab_pv_rules WHERE required_pv <= %d ORDER BY required_pv DESC LIMIT 1", $my_pv));

        foreach ($rules as $rule) {
            if ($my_pv >= $rule->required_pv && $rule->id != vp_user_array($user_array, $id, "vp_pv_limit", true) && strtolower($rule->upgrade_plan) !== "none") {
                $get_plan = $rule->upgrade_plan;
                $get_bonus = $rule->upgrade_balance;
                vp_updateuser($id, "vr_plan", $get_plan);

                $give_bal = floatval(vp_user_array($user_array, $id, "vp_bal", true)) + intval($get_bonus);
                vp_updateuser($id, "vp_bal", $give_bal);
                vp_updateuser($id, "vp_pv_limit", $rule->id);
            }
        }

        $array["bal"] = vp_getuser($id, "vp_bal", true);
        $array["balance"] = $array["bal"];
        $array["myplan"] = vp_getuser($id, 'vr_plan', true);

        $dplan = $array["myplan"];

        $table_name_levels = $wpdb->prefix . "vp_levels";
        $array["level"] = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name_levels WHERE name = %s", $dplan));

        // Membership Rules
        $memRuleTable = $wpdb->prefix . "vp_membership_rule_stats";
        $membership_rule = $wpdb->get_results($wpdb->prepare("SELECT * FROM $memRuleTable WHERE user_id = %d ORDER BY ID DESC LIMIT 1", $id));

        if ($membership_rule !== NULL && !empty($membership_rule)) {
            $expTotUsers = intval($array["level"][0]->monthly_referee ?? 0);
            $expTotTrans = intval($array["level"][0]->monthly_transactions_number ?? 0);
            $expTotAmount = intval($array["level"][0]->monthly_transactions_amount ?? 0);

            $total_ref = intval($membership_rule[0]->ref);
            $transNo = intval($membership_rule[0]->transaction_number);
            $transAmount = intval($membership_rule[0]->transaction_amount);

            $current_date = date("Y-m-d", $current_timestamp);
            $start_count = $membership_rule[0]->start_count;
            $one_month_after = date("Y-m-d", strtotime($start_count . "+1 month"));

            if ($current_date > $one_month_after) {
                if ($expTotUsers > $total_ref || $expTotTrans > $transNo || $expTotAmount > $transAmount) {
                    vp_updateuser($id, 'vr_plan', "customer");
                }

                $data = [
                    'user_id' => $id,
                    'ref' => 0,
                    'transaction_number' => 0,
                    'transaction_amount' => 0,
                    'start_count' => $current_date
                ];
                $wpdb->insert($memRuleTable, $data);
            }
        }

        $array["levels"] = $wpdb->get_results("SELECT * FROM $table_name_levels");
        $level = $array["level"];

        // Monthly Subscription Logic
        if ($level !== NULL && !empty($level) && vp_option_array($option_array, "vtupress_custom_mlmsub") === "yes") {
            if (isset($level[0]->monthly_sub) && $level[0]->monthly_sub === "yes") {
                $last_sub = vp_user_array($user_array, $id, "vp_monthly_sub", true);
                if ($last_sub !== "false" && !empty($last_sub)) {
                    $one_month = date("Y-m-d H:i:s", strtotime($last_sub . "+1month"));
                    if (date("Y-m-d H:i:s") > $one_month) {
                        $all_my_plans = str_replace($dplan, "", vp_getuser($id, "all_my_plans", true));
                        vp_updateuser($id, "all_my_plans", $all_my_plans);
                        vp_updateuser($id, 'vr_plan', "customer");
                    }
                } else {
                    vp_updateuser($id, 'vp_monthly_sub', date("Y-m-d H:i:s", $current_timestamp));
                }
            }
        }

        $array["mess"] = vp_option_array($option_array, "vpwalm");
        $array["notification"] = $array["mess"];

        // Discount calculations
        $array["vtudiscounts"] = max(array(floatval(vp_option_array($option_array, "vtu_mad")), floatval(vp_option_array($option_array, "vtu_gad")), floatval(vp_option_array($option_array, "vtu_9ad")), floatval(vp_option_array($option_array, "vtu_aad"))));
        $array["sharediscounts"] = max(array(floatval(vp_option_array($option_array, "share_mad")), floatval(vp_option_array($option_array, "share_gad")), floatval(vp_option_array($option_array, "share_9ad")), floatval(vp_option_array($option_array, "share_aad"))));
        $array["awufdiscounts"] = max(array(floatval(vp_option_array($option_array, "awuf_mad")), floatval(vp_option_array($option_array, "awuf_gad")), floatval(vp_option_array($option_array, "awuf_9ad")), floatval(vp_option_array($option_array, "awuf_aad"))));
        $array["airtimediscount"] = max($array["vtudiscounts"], $array["sharediscounts"], $array["awufdiscounts"]);

        $array["smediscounts"] = max(array(floatval(vp_option_array($option_array, "sme_mdd")), floatval(vp_option_array($option_array, "sme_gdd")), floatval(vp_option_array($option_array, "sme_9dd")), floatval(vp_option_array($option_array, "sme_ddd"))));
        $array["directdiscounts"] = max(array(floatval(vp_option_array($option_array, "direct_mdd")), floatval(vp_option_array($option_array, "direct_gdd")), floatval(vp_option_array($option_array, "direct_9dd")), floatval(vp_option_array($option_array, "direct_ddd"))));
        $array["corporatediscounts"] = max(array(floatval(vp_option_array($option_array, "corporate_mdd")), floatval(vp_option_array($option_array, "corporate_gdd")), floatval(vp_option_array($option_array, "corporate_9dd")), floatval(vp_option_array($option_array, "corporate_ddd"))));
        $array["datadiscount"] = max($array["smediscounts"], $array["directdiscounts"], $array["corporatediscounts"]);

        // MLM related details (only if plugin active and enabled)
        if (is_plugin_active("vpmlm/vpmlm.php") && vp_option_array($option_array, 'mlm') === "yes") {
            $array["total_inref3_id"] = vp_user_array($user_array, $id, "vp_tot_in_ref3_id", true);
            $array["cur_suc_trans_amt"] = vp_user_array($user_array, $id, "vp_tot_trans_amt", true);
            $array["total_amount_of_successful_transactions"] = $array["cur_suc_trans_amt"];
            $array["ref"] = vp_user_array($user_array, $id, "vp_ref", true);
            $array["my_upline"] = $array["ref"];
            $array["refbo"] = vp_option_array($option_array, "refbo");
            $array["total_1st_downlines"] = vp_user_array($user_array, $id, "vp_tot_ref", true);
            $array["total_refered"] = $array["total_1st_downlines"];
            $array["total_inrefered"] = vp_user_array($user_array, $id, "vp_tot_in_ref", true);
            $array["total_2nd_downlines"] = $array["total_inrefered"];
            $array["total_inrefered3"] = vp_user_array($user_array, $id, "vp_tot_in_ref3", true);
            $array["total_other_downlines"] = $array["total_inrefered3"];
            $array["total_dir_earn"] = vp_user_array($user_array, $id, "vp_tot_ref_earn", true);
            $array["upgrade_bonus_from_1st"] = $array["total_dir_earn"];
            $array["total_indir_earn"] = vp_user_array($user_array, $id, "vp_tot_in_ref_earn", true);
            $array["upgrade_bonus_from_2nd"] = $array["total_indir_earn"];
            $array["total_indir_earn3"] = vp_user_array($user_array, $id, "vp_tot_in_ref_earn3", true);
            $array["upgrade_bonus_from_others"] = $array["total_indir_earn3"];
            $array["total_trans_bonus"] = vp_user_array($user_array, $id, "vp_tot_trans_bonus", true);
            $array["transaction_bonus"] = $array["total_trans_bonus"];
            $array["total_dirtrans_bonus"] = vp_user_array($user_array, $id, "vp_tot_dir_trans", true);
            $array["transactions_bonus_from_1st"] = $array["total_dirtrans_bonus"];
            $array["total_indirtrans_bonus"] = vp_user_array($user_array, $id, "vp_tot_indir_trans", true);
            $array["transactions_bonus_from_2nd"] = $array["total_indirtrans_bonus"];
            $array["total_indirtrans_bonus3"] = vp_user_array($user_array, $id, "vp_tot_indir_trans3", true);
            $array["transactions_bonus_from_others"] = $array["total_indirtrans_bonus3"];
            $array["total_trans_attempt"] = vp_user_array($user_array, $id, "vp_tot_trans", true);
            $array["total_transaction_attempted"] = $array["total_trans_attempt"];
            $array["total_suc_trans"] = vp_user_array($user_array, $id, "vp_tot_suc_trans", true);
            $array["total_successful_transactions"] = $array["total_suc_trans"];
            $array["total_trans_bonus"] = vp_user_array($user_array, $id, "vp_tot_trans_bonus", true);
            $array["total_transaction_bonus"] = $array["total_trans_bonus"];
            $array["total_withdraws"] = vp_user_array($user_array, $id, "vp_tot_withdraws", true);

            $total_dir_earn = floatval($array["total_dir_earn"]);
            $total_indir_earn = floatval($array["total_indir_earn"]);
            $total_indir_earn3 = floatval($array["total_indir_earn3"]);
            $total_trans_bonus = floatval($array["total_trans_bonus"]);
            $total_dirtrans_bonus = floatval($array["total_dirtrans_bonus"]);
            $total_indirtrans_bonus = floatval($array["total_indirtrans_bonus"]);
            $total_indirtrans_bonus3 = floatval($array["total_indirtrans_bonus3"]);

            $array["total_bal_with"] = $total_dir_earn + $total_indir_earn + $total_indir_earn3 + $total_trans_bonus + $total_dirtrans_bonus + $total_indirtrans_bonus + $total_indirtrans_bonus3;
            $array["total_withdrawal_balance"] = $array["total_bal_with"];

            $array["minwithle"] = vp_option_array($option_array, "vp_min_withdrawal");
            $array["minimum_withdrawal_amount"] = $array["minwithle"];
            $array["ref_by"] = vp_user_array($user_array, $id, "vp_who_ref", true);
            $array["cheepa"] = 0;
        }

        $bank_mode = vp_user_array($user_array, $id, "account_mode", true);
        $array["bank_mode"] = $bank_mode;
        if ($bank_mode === "live") {
            $array["bank_ref"] = vp_user_array($user_array, $id, "bank_reference", true);
            $array["account_name"] = vp_user_array($user_array, $id, "account_name", true);
            $array["account_number"] = vp_user_array($user_array, $id, "account_number", true);

            $bank_name_raw = vp_user_array($user_array, $id, "bank_name", true);
            if (is_numeric(stripos($bank_name_raw, "wema"))) {
                $array["bank_name"] = "WEMA";
            } elseif (is_numeric(stripos($bank_name_raw, "ster"))) {
                $array["bank_name"] = "STERLING";
            } elseif (is_numeric(stripos($bank_name_raw, "mon"))) {
                $array["bank_name"] = "MONNIEPOINT";
            } else {
                $array["bank_name"] = $bank_name_raw; // Keep original if not matched
            }

            if (!empty(vp_user_array($user_array, $id, "account_name1", true)) && vp_user_array($user_array, $id, "account_name1", true) !== "false") {
                $array["account_name1"] = vp_user_array($user_array, $id, "account_name1", true);
                $array["account_number1"] = vp_user_array($user_array, $id, "account_number1", true);
                $bank_name1_raw = vp_user_array($user_array, $id, "bank_name1", true);
                if (is_numeric(stripos($bank_name1_raw, "wema"))) {
                    $array["bank_name1"] = "WEMA";
                } elseif (is_numeric(stripos($bank_name1_raw, "ster"))) {
                    $array["bank_name1"] = "STERLING";
                } elseif (is_numeric(stripos($bank_name1_raw, "mon"))) {
                    $array["bank_name1"] = "MONNIEPOINT";
                } else {
                    $array["bank_name1"] = $bank_name1_raw;
                }
            } else {
                $array["account_name1"] = "NULL";
                $array["account_number1"] = "NULL";
                $array["bank_name1"] = "NULL";
            }

            if (!empty(vp_user_array($user_array, $id, "account_name2", true)) && vp_user_array($user_array, $id, "account_name2", true) !== "false") {
                $array["account_name2"] = vp_user_array($user_array, $id, "account_name2", true);
                $array["account_number2"] = vp_user_array($user_array, $id, "account_number2", true);
                $bank_name2_raw = vp_user_array($user_array, $id, "bank_name2", true);
                if (is_numeric(stripos($bank_name2_raw, "wema"))) {
                    $array["bank_name2"] = "WEMA";
                } elseif (is_numeric(stripos($bank_name2_raw, "ster"))) {
                    $array["bank_name2"] = "STERLING";
                } elseif (is_numeric(stripos($bank_name2_raw, "mon"))) {
                    $array["bank_name2"] = "MONNIEPOINT";
                } else {
                    $array["bank_name2"] = $bank_name2_raw;
                }
            } else {
                $array["account_name2"] = "NULL";
                $array["account_number2"] = "NULL";
                $array["bank_name2"] = "NULL";
            }
        } else {
            $array["bank_ref"] = "NULL";
            $array["account_name"] = "NULL";
            $array["account_number"] = "NULL";
            $array["bank_name"] = "NULL";
            $array["account_name1"] = "NULL";
            $array["account_number1"] = "NULL";
            $array["bank_name1"] = "NULL";
            $array["account_name2"] = "NULL";
            $array["account_number2"] = "NULL";
            $array["bank_name2"] = "NULL";
        }

        $array["template_url"] = plugins_url("vtupress/template");

        return $array;
    }



    /**
     * Dumps an error message and terminates script execution.
     * This function now outputs plain text for consistency with die().
     *
     * @param string $title Error title.
     * @param string $message Error message.
     * @return void
     */
    function dump_error($title = "", $message = "")
    {
        // Output plain text error message.
        // The HTML structure is removed to ensure plain text output as requested.
        die("ERROR: " . $title . " - " . $message);
    }


    /**
     * Shortcode callback for [vtupress get="attr"].
     * Securely retrieves whitelisted user and plugin data.
     *
     * @param array $atts Shortcode attributes.
     * @return string The requested attribute value, or "key_not_found".
     */
    add_shortcode("vtupress", function ($atts = []) {
        $defaults = vtupress_user_details(); // Get all details (internal use)

        // Define whitelisted attributes that are safe to expose via shortcode.
        $whitelisted_attrs = [
            'user_id',
            'name',
            'email',
            'phone',
            'admin_whatsapp',
            'balance',
            'myplan',
            'notification',
            'minimum_withdrawal_amount',
            'kyc_status',
            'kyc_end',
            'kyc_total',
            'airtimediscounts',
            'vtudiscounts',
            'sharediscounts',
            'awufdiscounts',
            'datadiscounts',
            'smediscounts',
            'directdiscounts',
            'corporatediscounts',
            'my_upline',
            'total_1st_downlines',
            'total_2nd_downlines',
            'total_other_downlines',
            'upgrade_bonus_from_1st',
            'upgrade_bonus_from_2nd',
            'upgrade_bonus_from_others',
            'transaction_bonus',
            'transactions_bonus_from_1st',
            'transactions_bonus_from_2nd',
            'transactions_bonus_from_others',
            'total_transaction_attempted',
            'total_successful_transactions',
            'total_transaction_bonus',
            'total_withdraws',
            'total_withdrawal_balance',
            'total_amount_of_successful_transactions',
            'bank_mode',
            'account_name',
            'account_number',
            'bank_name',
            'account_name1',
            'account_number1',
            'bank_name1',
            'account_name2',
            'account_number2',
            'bank_name2',
            'template_url'
        ];

        $attr = array_change_key_case((array) $atts, CASE_LOWER);

        if (isset($attr["get"]) && in_array($attr["get"], $whitelisted_attrs, true)) {
            // Return only whitelisted attributes.
            // Ensure sensitive data like 'pin', 'option_array', 'user_array', 'kyc_data', 'level', 'levels'
            // are NOT returned directly as they contain sensitive or complex data.
            if (isset($defaults[$attr["get"]])) {
                // For arrays/objects, return a placeholder or specific safe values if needed.
                // For example, 'kyc_data', 'level', 'levels' are arrays/objects.
                // Returning them directly can be problematic.
                if (is_array($defaults[$attr["get"]]) || is_object($defaults[$attr["get"]])) {
                    // For now, return a generic message for complex types.
                    // You might want to implement specific sub-attributes for these if needed.
                    if ($attr["get"] === 'kyc_data' || $attr["get"] === 'level' || $attr["get"] === 'levels' || $attr["get"] === 'option_array' || $attr["get"] === 'user_array') {
                        return "Complex_Data_Not_Directly_Exposed";
                    }
                }
                return esc_html($defaults[$attr["get"]]); // Escape output for display.
            }
        }
        return "key_not_found";
    });

    function botAccess(){
        if(!isset($_REQUEST['bot_key'])){
           return false;
        }

        if($_REQUEST['bot_key'] !== vp_getuser('1',"vr_id", false)){
           return false;
        }

        return true;
    }

    /**
     * Auto-override mechanism for VTUPress plugin files.
     * Allows custom versions of plugin files to be loaded from wp-content/vtupress-custom/.
     *
     * @return void
     */
    function vtupress_auto_override()
    {
        if (vp_getoption("vtupress_custom_custom") !== "yes") {
            return;
        }
        // Get the debug backtrace to find the file that called this function
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $original_file = $backtrace[0]['file'];

        // Normalize the path and trim to get relative path after plugins/
        $plugin_base = WP_PLUGIN_DIR . '/';
        $relative_path = str_replace($plugin_base, '', $original_file);

        // Path to the override file
        $custom_file = WP_CONTENT_DIR . '/vtupress-custom/' . $relative_path;

        // If override file exists, load it and stop
        if (file_exists($custom_file)) {
            require_once $custom_file;
            exit; // Prevent original file from continuing
        }
        // Otherwise, allow original file to continue
    }

} // End if(file_exists(__DIR__."/do_not_tamper.php"))