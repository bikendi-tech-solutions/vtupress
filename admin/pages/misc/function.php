<?php
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

if(isset($_POST["action"])){

    switch($_POST["action"]){

        case"scan":
            $num = 0;
            $content = ABSPATH."wp-content";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_folder_scan",1);
                $num += 25;
            }
            else{
                vp_updateoption("vp_content_folder_scan",0);
            }

            $content = ABSPATH."wp-content/plugins";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_plugin_scan",1);
                $num += 25;
            }
            else{

                vp_updateoption("vp_content_plugin_scan",0);
            }

            $content = ABSPATH."wp-content/plugins/vtupress";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_vtupress_scan",1);
                $num += 25;
            }
            else{

                vp_updateoption("vp_content_vtupress_scan",0);
            }

            $vend = ABSPATH."wp-content/plugins/vtupress/vend.php";
            $vtupress = ABSPATH."wp-content/plugins/vtupress/vtupress.php";
            $functions = ABSPATH."wp-content/plugins/vtupress/functions.php";
            $permissions = fileperms($vend );
            $permissions = fileperms($vtupress );
            $permissions = fileperms($functions);
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0644"){

                vp_updateoption("vp_content_vend_scan",1);
                $num += 25;
            }
            else{

                vp_updateoption("vp_content_vend_scan",0);
            }
            if($num <= 25){
                vp_updateoption("vp_folder_scan",25);
            }
            elseif($num <= 50){
                vp_updateoption("vp_folder_scan",50);
            }
            elseif($num <= 80){
                vp_updateoption("vp_folder_scan",80);
            }
            elseif($num >= 80){
                
                if(vp_getoption("vp_security") == "yes" && $num >= 80){
                    vp_updateoption("vp_folder_scan",100);
                }
                else{
                    vp_updateoption("vp_folder_scan",90);
                }
            }

            die("100");
        break;

        case"fix":
            $content = ABSPATH."wp-content";
            $plugin = ABSPATH."wp-content/plugins";
            $vtupress = ABSPATH."wp-content/plugins/vtupress";
            $functions = ABSPATH."wp-content/plugins/vtupress/functions.php";
            $vtufile = ABSPATH."wp-content/plugins/vtupress/vtupress.php";
            $vend = ABSPATH."wp-content/plugins/vtupress/vend.php";

            chmod($content, 0755);
            chmod($plugin, 0755);
            chmod($vtupress, 0755);
            chmod($functions, 0644);
            chmod( $vtufile, 0644);
            chmod( $vend, 0644);
            

            $content = ABSPATH."wp-content";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_folder_scan",1);
            }
            else{

                vp_updateoption("vp_content_folder_scan",0);
            }

            $content = ABSPATH."wp-content/plugins";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_plugin_scan",1);
            }
            else{

                vp_updateoption("vp_content_plugin_scan",0);
            }

            $content = ABSPATH."wp-content/plugins/vtupress";
            $permissions = fileperms($content );
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0755"){

                vp_updateoption("vp_content_vtupress_scan",1);
            }
            else{

                vp_updateoption("vp_content_vtupress_scan",0);
            }

            $vend = ABSPATH."wp-content/plugins/vtupress/vend.php";
            $vtupress = ABSPATH."wp-content/plugins/vtupress/vtupress.php";
            $functions = ABSPATH."wp-content/plugins/vtupress/functions.php";
            $permissions = fileperms($vend );
            $permissions = fileperms($vtupress );
            $permissions = fileperms($functions);
            $code = substr(sprintf('%o', $permissions), -4); //0666
            if($code == "0644"){

                vp_updateoption("vp_content_vend_scan",1);
            }
            else{

                vp_updateoption("vp_content_vend_scan",0);
            }

            die("100");


        break;
    }



}?>