<?php
header("Access-Control-Allow-Origin: 'self'");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');


function updateIbroLinks(){
$option_array = json_decode(get_option("vp_options"),true);

if(vp_option_array($option_array,"vtupress_custom_ibrolinks_profit") != "yes" || vp_option_array($option_array,"ibrolinks_profit") < 1){
return; 
}

$profit = intval(vp_option_array($option_array,"ibrolinks_profit"));

$run = false;
if(stripos(vp_option_array($option_array,"databaseurl"),"ibrolink") !== false){

    $secret_key = vp_option_array($option_array,"smeaddvalue1");
    $apikey = vp_option_array($option_array,"datahead1");
    $url = vp_option_array($option_array,"databaseurl");

    if(!empty($apikey) && !empty($secret_key)){
        $run = true;
    }

}
elseif(stripos(vp_option_array($option_array,"r2databaseurl"),"ibrolink") !== false){

    $secret_key = vp_option_array($option_array,"corporateaddvalue1");
    $apikey = vp_option_array($option_array,"r2datahead1");
    $url = vp_option_array($option_array,"r2databaseurl");

    if(!empty($apikey) && !empty($secret_key)){
        $run = true;
    }

}
elseif(stripos(vp_option_array($option_array,"rdatabaseurl"),"ibrolink") !== false){

    $secret_key = vp_option_array($option_array,"directaddvalue1");
    $apikey = vp_option_array($option_array,"rdatahead1");
    $url = vp_option_array($option_array,"rdatabaseurl");

    if(!empty($apikey) && !empty($secret_key)){
        $run = true;
    }



}
else{

}


if(!$run){

  return;
}

if(preg_match("/\/$/",$url)){
$url .= "products/data";
}else{
$url .= "/products/data";

}

$head["cache-control"] = "no-cache";
$head["content-type"] = "application/json";
$head["secret-key"] = $secret_key;
$head["Authorization"] = "Bearer  $apikey";

    $http_args = array(
        'headers' => $head,
        'timeout' => '3000',
        'user-agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
        'blocking'=> true
    );

$response =  wp_remote_retrieve_body(wp_remote_get($url, $http_args));




$array = json_decode($response, true);

if(!isset($array["services"])){
    
    return;
}

$prod_body = $array["services"]["products"];

foreach($prod_body as $this_data_body){

    $ibrolinks_plan = $this_data_body["plans"];
    //check for network
    switch(strtolower($this_data_body["name"])){
        case"mtn":
            //check for ids 
            //start from sme
            //check if sme is using ibrolinks


            

                if(stripos(vp_option_array($option_array,"databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"sme_visible_networks"),"mtn") !== false ){
                    for($i=0; $i<=10; $i++){
                        $ident = "cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){
                                

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);
                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                if(stripos(vp_option_array($option_array,"r2databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"corporate_visible_networks"),"mtn") !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r2cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                
                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                
                if(stripos(vp_option_array($option_array,"rdatabaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"direct_visible_networks"),"mtn") !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "rcdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

            
        break;
        case"glo":
            //check for ids 
            //start from sme
            //check if sme is using ibrolinks
            $network = "glo";
            $added = "g";


            

                if(stripos(vp_option_array($option_array,"databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"sme_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){
                        $ident = "{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){
                                

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                if(stripos(vp_option_array($option_array,"r2databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"corporate_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r2{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                
                if(stripos(vp_option_array($option_array,"rdatabaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"direct_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

            
        break;
        case"9mobile":
            //check for ids 
            //start from sme
            //check if sme is using ibrolinks
            $network = "9mobile";
            $added = "9";


            

                if(stripos(vp_option_array($option_array,"databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"sme_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){
                        $ident = "{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){
                                
                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                if(stripos(vp_option_array($option_array,"r2databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"corporate_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r2{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                
                if(stripos(vp_option_array($option_array,"rdatabaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"direct_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

            
        break;
        case"airtel":
            //check for ids 
            //start from sme
            //check if sme is using ibrolinks
            $network = "airtel";
            $added = "a";


            

                if(stripos(vp_option_array($option_array,"databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"sme_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){
                        $ident = "{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){
                                
                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                if(stripos(vp_option_array($option_array,"r2databaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"corporate_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r2{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

                if(stripos(vp_option_array($option_array,"rdatabaseurl"),"ibrolink") !== false && stripos(vp_option_array($option_array,"direct_visible_networks"),$network) !== false ){
                    for($i=0; $i<=10; $i++){

                        $ident = "r{$added}cdata";
                        $data_id = vp_option_array($option_array,$ident.$i);
                        $data_price = vp_option_array($option_array,$ident."p".$i);

                        foreach($ibrolinks_plan as $this_plan){
                            if($this_plan["id"] == $data_id){

                                vp_updateoption($ident."p".$i,intval($this_plan["amount"])+$profit);

                                
                            }elseif(empty($data_id)){
                                $i = 100;
                                break;
                            }
                        }


                    }

                    
                }

            
        break;
        
    }
}


}


updateIbroLinks();