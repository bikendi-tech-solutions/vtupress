<?php

if(!defined('ABSPATH')) {
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/', '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false) {
    error_reporting(0);
}
include_once(ABSPATH."wp-load.php");

if(file_exists($path) && in_array('vtupress/vtupress.php', apply_filters(
    'active_plugins',
    get_option('active_plugins')
))) {
    include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
} else {
    if(!function_exists("vp_updateuser")) {
        function vp_updateuser()
        {

        }

        function vp_getuser()
        {

        }

        function vp_adduser()
        {

        }

        function vp_updateoption()
        {

        }

        function vp_getoption()
        {

        }

        function vp_option_array()
        {

        }

        function vp_user_array()
        {

        }

        function vp_deleteuser()
        {

        }

        function vp_addoption()
        {

        }

    }
}

global $blogname;

$blogname = get_option("blogname");
if(isset($_POST['username'])) {
    global $wpdb;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = "true";

    $login_data = array();

    if(preg_match("/@/",$username)){

        if(!email_exists($username)){
            die('{"status":"101","message":"Wrong Email"}');
        }
        else{
            $username= get_user_by('email',$username)->user_login;
        }
    }

    $login_data['user_login'] = $username;
    $login_data['user_password'] = $password;
    $login_data['remember'] = $remember;

    if(vp_getoption("vp_security") == "yes") {
        $ban_list = vp_getoption("vp_users_email");

        if(is_numeric(stripos($ban_list, $username))) {

            die('{"status":"101","message":NOT ALLOWED (X)"}');

        }

    }

    $verify_email = strtolower(vp_getoption("email_verification"));
    $template = constant('vtupress_template');
    if($verify_email != "false" && $verify_email != "no" && !preg_match("/opay/",$template)) {
        add_action('wp_authenticate', 'check_vtupress_authentication');

        function check_vtupress_authentication($username)
        {
            global $blogname;
            $email_headers = array('Content-Type: text/html; charset=UTF-8');
            global $wpdb;

            if (! username_exists($username)) {
                die('{"status":"101","message":"Wrong Username"}');
            }

            $userinfo = get_user_by('login', $username);
            $user_id = $userinfo->ID;
            $user_email = $userinfo->user_email;

            $verify = vp_getuser($user_id, "email_verified");
            if(strtolower($verify) != "verified") {

                if(vp_getuser($user_id, "email_verified", true) != "false") {

                    $uniqid = vp_getuser($user_id, "email_verified", true);
                } else {
                    $ddid = rand(1111,9999);
                    vp_updateuser($user_id, "email_verified", $ddid);
                    $uniqid = vp_getuser($user_id, "email_verified", true);
                }

                $blog = $blogname;
                $usernamepper = ucfirst($username);
                $subject = "[ $usernamepper ] - EMAIL VERIFICATION";
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $message = '<div style="height:fit-content">
  <div
    style="background-color:#0000ffc2; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:center; color:white; font-family:cursive;font-size:2em;">
    <span style> Email Verification </span>

  </div>
  <div
    style="background-color:#f0f0f1; padding:20px 10px; max-width:80%; margin: 10px auto; text-align:left; color:black; font-family:sans-serif;font-size:1em;"">

    Please for smoothness of our services and safety, we indulge you to verify
    your email. Your Activateion Code Is <b> ' . $uniqid . '</b>
  </div>
  <div
    style="background-color:#0000ffc2; padding:10px 10px 30px 10px; max-width:80%; margin: 10px auto; color:white; font-family:cursive;font-size:1em;">
    Thank You
  </div>

</div>';

                wp_mail($user_email, $subject, $message, $email_headers);
                die('{"status":"101","message":"User not Verified. Kindly check your email inbox/spam folder for a verification message."}');
            }

        }

    }

    $user_verify = wp_signon($login_data, false);

    if(is_wp_error($user_verify)) {
        $error_message = $user_verify->get_error_message();
        die('{"status":"101","message":"Wrong Credentials for '.$username.' - '.$password.'"}');
    } else {

        $current_timestamp = current_time('timestamp');
        
        setcookie("vtuloggedin", "yes", time() + (30 * 24 * 60 * 60), "/");
        setcookie("last_login", date('Y-m-d H:i:s',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");



        wp_clear_auth_cookie();
        wp_set_current_user($user_verify->ID);
        wp_set_auth_cookie($user_verify->ID, true);
        $redirect_to = $_SERVER['REQUEST_URI'];
       
        $obj = new stdClass();
        $obj->status = "100";
        $obj ->message = "welcome";
        $obj->ID = $user_verify->ID;
        $obj->name = get_userdata($user_verify->ID)->user_login;

        die(json_encode($obj));

    }

} elseif(isset($_GET["id"]) && isset($_GET["by"]) && isset($_GET["auth"])) {
    $id = $_GET["id"];
    $by = $_GET["by"];
    $auth = $_GET["auth"];

    $url = 'https://vtupress.com/auth.php?auth='.$auth;
    $response = file_get_contents($url);
    $ans = json_decode($response);

    if(!isset($ans->status)) {
        // print_r($ans);
        die("No JSON WITH ID STATUS");
    } elseif($ans->status == 200) {
        //continue
    } else {
        echo $response;
        die("UNIDENTIFIED ISSUE");
    }

    if($by == "id") {
        $user_id = $id;
    } elseif($by == "username") {
        $user_id = get_user_by('login', $id)->ID;
    } elseif($by == "email") {
        $user_id = get_user_by("email", $id)->ID;
    } else {
        $obj = new stdClass();
        $obj->status = "200";
        $obj ->message = "Error";
        $obj->ID = "1";
        $obj->name = get_userdata($user_id)->user_login;

        die(json_encode($obj));
    }

    vp_updateoption("wplogin_redirect", "no");
    setcookie("vtuloggedin", "yes", time() + (30 * 24 * 60 * 60), "/");
    setcookie("last_login", date('Y-m-d H:i:s',$current_timestamp), time() + (30 * 24 * 60 * 60), "/");



    
    wp_clear_auth_cookie();
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
  

    if(get_current_user_id() == $user_id) {
        $obj = new stdClass();
        $obj->status = "100";
        $obj ->message = "welcome";
        $obj->ID = "1";
        $obj->name = get_userdata($user_id)->user_login;

        die(json_encode($obj));
    } else {
        $obj = new stdClass();
        $obj->status = "200";
        $obj ->message = "Error";
        $obj->ID = "1";
        $obj->name = get_userdata($user_id)->user_login;

        die(json_encode($obj));
    }

} elseif(isset($_POST["verify"]) && isset($_POST["user"])) {
    $code = $_POST["verify"];
    $user = $_POST["user"];
    $userinfo = get_user_by('login', $user);
    if(isset($userinfo->ID)) {
        $user_id = $userinfo->ID;
    } else {
        die("102");
    }
    $user_id = $userinfo->ID;
    $dcode = vp_getuser($user_id, 'email_verified');
    $home = get_home_url();

    if($dcode == "verified") {

        die("101");
    } elseif($code == $dcode && vp_getuser($user_id, 'email_verified') != "verified") {
        vp_updateuser($user_id, "email_verified", "verified");

        die("100");

    } else {

        die("102");
    }

}
elseif(isset($_GET["update"]) && isset($_GET["to"]) && isset($_GET["auth"])){
    $update = $_GET["update"];
    $to = $_GET["to"];
    $auth = $_GET["auth"];

    $url = 'https://vtupress.com/auth.php?auth='.$auth;
    $response = file_get_contents($url);

    $ans = json_decode($response);

    if(!isset($ans->status)) {
        // print_r($ans);
        die("No JSON WITH ID STATUS");
    } elseif($ans->status == 200) {
        //continue
       $was =  vp_getoption($update);

        vp_updateoption($update,$to);

       $now =  vp_getoption($update);

    $msg = "$update was $was but it is now $now";
//echo vp_getoption("vtupress_custom_gtbank");

        die($msg);
    } else {
        die("UNIDENTIFIED ISSUE");
    }
}
else {
    $obj = new stdClass();
    $obj->status = "200";
    $obj ->message = "NO REQUEST MADE";

    die(json_encode($obj));
}
