<?php
header("Access-Control-Allow-Origin: 'self'");

header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(WP_DEBUG == false){
error_reporting(0);	
}
include_once(ABSPATH."wp-load.php");
include_once(ABSPATH .'wp-content/plugins/vtupress/functions.php');

//get todays date
$today = date("Y-m-d");

global $wpdb;
$savings_table = $wpdb->prefix."vp_savings";
$results = $wpdb->get_results("SELECT * FROM $savings_table WHERE last_check != '$today'");

foreach($results as $result){

    $update = [];

    $id = $result->id;
    $userid = $result->user_id;
    $type = $result->type; //fixed / daily
    $duration = $result->duration; //3 months
    $save_interval = strtolower($result->save_interval); //fixed / daily
    $status  = $result->status ; //active/withdrawn e.t.c
    $start_amount  = $result->start_amount; //2000
    $next  = $result->next_save; //date to save next
    $save_count  = $result->save_count; //1,2,3
    $amount_saved  = $result->amount_saved; //20000
    $interest  = $result->interest; //4,5,6
    $applicable_on  = $result->applicable_on; //date to apply
    $automatic  = $result->automatic; //yes / no
    $applied = intval($result->interest_applied); //0 / 1

    $bal = vp_getuser($userid,"vp_bal",true);

    if($status != "active"){
        $payload = [
            "last_check"=>$today
        ];
        $wpdb->update($savings_table,$payload,["id"=>$id]);
        continue;
    }

    //check the plan status

    if($type == "daily"){
        $plan_table = $wpdb->prefix."vp_daily_savings_settings";
        $plan_result = $wpdb->get_results("SELECT * FROM $plan_table WHERE status = 'on' ");

        if(empty($plan_result)){
            continue;
        }

        $plan = $plan_result[0];

    }
    else{
        $plan_table = $wpdb->prefix."vp_fixed_savings_settings";
        $plan_result = $wpdb->get_results("SELECT * FROM $plan_table WHERE status = 'on' AND duration = '$duration' ");
        if(empty($plan_result)){
            continue;
        }


        $plan = $plan_result[0];

    }


    //save
    if($save_interval == "day"){
        $next_dur = "Day";
    }
    elseif($save_interval == "week"){
        $next_dur = "Week";
    }
    else{
        $next_dur = "Month";
    }

    if(!empty($next)){
        if($next <= $today){
            if($bal >= $start_amount ){

                $tot = $bal - $start_amount;
                vp_updateuser($userid,"vp_bal",$tot);

                $name = get_userdata($userid)->user_login;
                $table_name = $wpdb->prefix.'vp_wallet';
                $added_to_db = $wpdb->insert($table_name, array(
                'name'=> $name,
                'type'=> "Wallet",
                'description'=> "Wallet deducted for your active $type $duration savings",
                'fund_amount' => $start_amount,
                'before_amount' => $bal,
                'now_amount' => $tot,
                'user_id' => $userid,
                'status' => "Approved",
                'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
                ));


                $update["amount_saved"] = $amount_saved + $start_amount;
                $update["save_count"] = $save_count + 1;
                $update["next_save"] = date("Y-m-d",strtotime($today." +1".$next_dur));

            }
        }
    }
    

    //add interest as agreed on
    if($type == "fixed"){

            //check if it's time to save
            if(strtotime($today) == strtotime($applicable_on)){
            
                    $give = ($interest * $amount_saved)/100;
                    $tot = $amount_saved + $give;
                    $update["interest_applied"] = $applied + 1;
                    $update["amount_saved"] = $tot;
                
    
                    if( $plan->recurrent_after_first != "no" ){
                        $applicable_after = $plan->applicable_after;
                        //apply the interest again
                        $update["applicable_on"] = date("Y-m-d", strtotime($applicable_on." +".$applicable_after));
                    }
            }

    }

    $update["last_check"] = $today;
    $wpdb->update($savings_table,$update,["id"=>$id]);

    //die("100");

}