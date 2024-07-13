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


if(!$_POST){
    die("Post request alone");
}

if(!is_user_logged_in()){
  die("Please login");
}

$id = get_current_user_id();
$satable_name = $wpdb->prefix."vp_savings";
$witable_name = $wpdb->prefix."vp_savings_withdrawal";
$watable_name = $wpdb->prefix."vp_wallet";
$name = "isavings";

if(isset($_POST["daily-settings"])){

    include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

    $payload = [
        "sign_up_fee" => intval($_POST["sign_up_fee"]),
        "cut" => intval($_POST["cut"]),
        "cut_type" => $_POST["cut_type"],
        "minimum_amount" => intval($_POST["minimum_amount"]),
        "interest" => intval($_POST["interest"]),
        "status" => $_POST["status"],
        "referer_commission" => intval($_POST["referer_commission"]),
      ];

      $table_name = $wpdb->prefix."vp_daily_savings_settings";
  
      $wpdb->update($table_name,$payload,['id'=>1]);

      die("100");
}
elseif(isset($_POST["fixed-settings"])){

    include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');


    $payload = [
        "sign_up_fee" => intval($_POST["sign_up_fee"]),
        "duration" => trim($_POST["duration"]),
        "cut" => $_POST["cut"],
        "cut_type" => $_POST["cut_type"],
        "minimum_amount" => intval($_POST["minimum_amount"]),
        "interest" => intval($_POST["interest"]),
        "status" => $_POST["status"],
        "referer_commission" => intval($_POST["referer_commission"]),
        "applicable_after" => $_POST["applicable_after"],
        "recurrent_after_first" => $_POST["recurrent_after_first"],
        "penalty" => intval($_POST["penalty"]),
        "stop_after_due" => "yes",
      ];
      
      $pattern = '/^[0-9]\s(?:month|day|year)s?$/i';
      if(!preg_match($pattern,$payload["duration"])){
        die("Duration pattern doesn't match");
      }
      elseif(!preg_match($pattern,$payload["applicable_after"])){
        die("Applicable After pattern doesn't match");
      }
      


      $table_name = $wpdb->prefix."vp_fixed_savings_settings";

      //check duration
      $duration = substr($payload["duration"],0,4);
      $exist = $wpdb->get_results("SELECT * FROM $table_name WHERE duration LIKE  '%$duration%' ");
      if(!empty($exist)){
        $id = $exist[0]->id;
        $wpdb->update($table_name,$payload,["id"=>$id]);
      }else{
        $wpdb->insert($table_name,$payload);
      }
      
  


      die("100");
}
elseif(isset($_POST["planid"])){

  if(!isset($_POST["saveAmount"]) || !isset($_POST["saveDuration"]) || !isset($_POST["automatic"])){
    die("All fields are required *");
  }
  elseif(empty($_POST["saveAmount"]) || empty($_POST["saveDuration"]) || empty($_POST["automatic"])){
    die("No fields should be empty");
  }

  $planId = intval($_POST["planid"]);
  $saveAmount = intval($_POST["saveAmount"]);
  $saveDuration = strtolower(htmlspecialchars($_POST["saveDuration"]));
  $automatic = $_POST["automatic"];

  if($saveDuration == "day"){
    $next = "Day";
  }
  elseif($saveDuration == "week"){
      $next = "Week";
  }
  else{
      $next = "Month";
  }

  //check plan details
  global $wpdb;
  $table_name = $wpdb->prefix."vp_fixed_savings_settings";
  $exist = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE status = 'on' AND id = %s ", $planId));
  
  if(empty($exist)){
    die("Plan inactive or doesn't exist");
  }
  else{
    $plan = $exist[0];
  }

  //get details
  $sign = $plan->sign_up_fee;
  $minimum_amount = $plan->minimum_amount;
  $duration = $plan->duration;
  $interest = $plan->interest;
  $applicable_after = $plan->applicable_after;
  $recurrent_after_first = $plan->recurrent_after_first;
  $referer_commission = $plan->referer_commission;
  $penalty = $plan->penalty;

  //get User data
  $bal = intval(vp_getuser($id, 'vp_bal', true));
  
  if($sign > $bal){
    die("Insufficient Balance to sign up");
  }else if($bal < $saveAmount){
    die("You can't save beyond your current balance");
  }
  else if($bal < $minimum_amount){
    die("Balance behind the minimum amount");
  }
  else if($saveAmount < $minimum_amount){
    die("Your saving amount is lesser than the minimum Amount");
  }
  elseif(($sign + $saveAmount) > $bal){

    die("Balance too low to perform this transaction. You balance must be above ₦".($sign + $saveAmount));

    //continue
  }


  $current_balance = $bal;
  $tot = $current_balance - ($sign + $saveAmount);

  vp_updateuser($id,"vp_bal",$tot);

  $table_name = $wpdb->prefix.'vp_wallet';
  $added_to_db = $wpdb->insert($table_name, array(
  'name'=> $name,
  'type'=> "Wallet",
  'description'=> "Wallet deducted as fee for signup fee of $sign and initial saving amount of $saveAmount",
  'fund_amount' => ($sign + $saveAmount),
  'before_amount' => $current_balance,
  'now_amount' => $tot,
  'user_id' => $id,
  'status' => "Approved",
  'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
  ));


  $savings_table = $wpdb->prefix."vp_savings";
  $payload = [
    "user_id" => $id,
    "type" => "fixed",
    "duration" => $duration,
    "save_interval" => $saveDuration,
    "save_count" => "1",
    "status" => "active",
    "next_save" => date("Y-m-d",strtotime(date("Y-m-d")." +1 ".$next)),
    "info" => "",
    "amount_saved" => $saveAmount,
    "start_amount" => $saveAmount,
    "interest" => $interest,
    "applicable_on" => $applicable_after,
    "automatic" => $automatic,
    "started_at" => date("Y-m-d"),
    "last_check" => date("Y-m-d"),
    "ends_at" => date("Y-m-d",strtotime(date("Y-m-d")." +".$duration)),
    "interest_applied" => "0",
    "liquidated" => "no"
  ];

  
  $results = $wpdb->get_results("SELECT * FROM $savings_table WHERE duration = '$duration' AND user_id = '$id' AND status = 'active' ");
  if(empty($results)){
    $wpdb->insert($savings_table,$payload);
  }else{
    die("You're already on this plan");
  }


  if($referer_commission < 1){

    $name = get_userdata($id)->user_login;

        //add referer bonus
        $my_d_ref = vp_getuser($id, "vp_who_ref", true); //get my ref id

        if(!is_numeric($my_d_ref)){
          die("100");
        }

        $getRefBal = intval(vp_getuser($my_d_ref,"vp_bal",true));
        $tot = $getRefBal +  intval($referer_commission);
  
        vp_updateuser($my_d_ref,"vp_bal",$tot);
  
  
        $table_name = $wpdb->prefix.'vp_wallet';
        $wpdb->insert($table_name, array(
        'name'=> $name,
        'type'=> "Wallet",
        'description'=> "Commission earned from $name application for a saving plan",
        'fund_amount' => $referer_commission,
        'before_amount' => $getRefBal,
        'now_amount' => $tot,
        'user_id' => $my_d_ref,
        'status' => "Approved",
        'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
        ));

  }

  die("100");
  
  

}
elseif(isset($_POST["daily-planid"])){

  if(!isset($_POST["saveAmount"]) || !isset($_POST["saveDuration"]) || !isset($_POST["automatic"])){
    die("All fields are required *");
  }
  elseif(empty($_POST["saveAmount"]) || empty($_POST["saveDuration"]) || empty($_POST["automatic"])){
    die("No fields should be empty");
  }

  $planId = intval($_POST["daily-planid"]);
  $saveAmount = intval($_POST["saveAmount"]);
  $saveDuration = htmlspecialchars($_POST["saveDuration"]);
  $automatic = $_POST["automatic"];

  //check plan details
  global $wpdb;
  $table_name = $wpdb->prefix."vp_daily_savings_settings";
  $exist = $wpdb->get_results("SELECT * FROM $table_name WHERE status = 'on' ");
  
  if(empty($exist)){
    die("Daily plan inactive");
  }
  else{
    $plan = $exist[0];
  }

  //get details
  $sign = $plan->sign_up_fee;
  $minimum_amount = $plan->minimum_amount;
  $interest = $plan->interest;
  $referer_commission = $plan->referer_commission;

  //get User data
  $bal = intval(vp_getuser($id, 'vp_bal', true));
  
  if($sign > $bal){
    die("Insufficient Balance to sign up");
  }else if($bal < $saveAmount){
    die("You can't save beyond your current balance");
  }
  else if($bal < $minimum_amount){
    die("Balance behind the minimum amount");
  }
  else if($saveAmount < $minimum_amount){
    die("Your saving amount is lesser than the minimum Amount");
  }
  elseif(($sign + $saveAmount) > $bal){

    die("Balance too low to perform this transaction. You balance must be above ₦".($sign + $saveAmount));

    //continue
  }


  $current_balance = $bal;
  $tot = $current_balance - ($sign + $saveAmount);

  vp_updateuser($id,"vp_bal",$tot);

  $table_name = $wpdb->prefix.'vp_wallet';
  $added_to_db = $wpdb->insert($table_name, array(
  'name'=> $name,
  'type'=> "Wallet",
  'description'=> "Wallet deducted as fee for signup fee of $sign and initial saving amount of $saveAmount",
  'fund_amount' => ($sign + $saveAmount),
  'before_amount' => $current_balance,
  'now_amount' => $tot,
  'user_id' => $id,
  'status' => "Approved",
  'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
  ));


  $savings_table = $wpdb->prefix."vp_savings";
  $payload = [
    "user_id" => $id,
    "type" => "daily",
    "save_count" => "1",
    "duration" => "daily",
    "save_interval" => "day",
    "status" => "active",
    "next_save" => date("Y-m-d",strtotime(date("Y-m-d")." +1 Day")),
    "info" => "",
    "amount_saved" => $saveAmount,
    "start_amount" => $saveAmount,
    "interest" => $interest,
    "applicable_on" => "on withdrawal",
    "automatic" => $automatic,
    "started_at" => date("Y-m-d"),
    "last_check" => date("Y-m-d"),
    "ends_at" => date("Y-m-d",strtotime(date("Y-m-d")." +1 day")),
    "liquidated" => "no"
  ];

  
  $results = $wpdb->get_results("SELECT * FROM $savings_table WHERE user_id = '$id' AND status = 'active' ");
  if(empty($results)){
    $wpdb->insert($savings_table,$payload);
  }else{
    die("You're already on this plan");
  }



  if($referer_commission < 1){

      $name = get_userdata($id)->user_login;

          //add referer bonus
          $my_d_ref = vp_getuser($id, "vp_who_ref", true); //get my ref id

          if(!is_numeric($my_d_ref)){
            die("100");
          }

          $getRefBal = intval(vp_getuser($my_d_ref,"vp_bal",true));
          $tot = $getRefBal +  intval($referer_commission);
    
          vp_updateuser($my_d_ref,"vp_bal",$tot);
    
    
          $table_name = $wpdb->prefix.'vp_wallet';
          $wpdb->insert($table_name, array(
          'name'=> $name,
          'type'=> "Wallet",
          'description'=> "Commission earned from $name application for a saving plan",
          'fund_amount' => $referer_commission,
          'before_amount' => $getRefBal,
          'now_amount' => $tot,
          'user_id' => $my_d_ref,
          'status' => "Approved",
          'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
          ));

  }


  die("100");
  
  

}
elseif(isset($_POST["userAction"]) && isset($_POST["savingid"])){

  if(empty($_POST["userAction"]) || empty($_POST["savingid"])){
    die("Action / Id can't be empty");
  }

  $sid = $_POST["savingid"];
  //check that the id is mine

  $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $satable_name WHERE user_id = '$id' AND id = %s ",$sid));

  if(empty($result)){
    die("Savings plan does not eixst or doesnt belong to you");
  }
  //get savings plan
  $dastatus = $result[0]->status;
  if($dastatus != "active"){
    die("This saving plan is not active");
  }

  $start = $result[0]->started_at;
  $end = $result[0]->ends_at;
  $amount_saved = intval($result[0]->amount_saved);
  $interest = intval($result[0]->interest);
  $count = intval($result[0]->save_count);
  $start_amount = intval($result[0]->start_amount);
  $type = $result[0]->type;
  $rduration = $result[0]->duration;
  $duration = substr($result[0]->duration,0,4);
  $today = strtotime(date("Y-m-d"));

  //get original plan and do
  if($type == "fixed"){
    $ptable_name = $wpdb->prefix."vp_fixed_savings_settings";
    $plan_result = $wpdb->get_results("SELECT * FROM $ptable_name WHERE duration LIKE '$duration%'");
    if(empty($plan_result)){
      die("Plan doesn't exist again {fixed}");
    }
    $response = $plan_result[0];

  }else{
    $ptable_name = $wpdb->prefix."vp_daily_savings_settings";
    $plan_result = $wpdb->get_results("SELECT * FROM $ptable_name ");
    if(empty($plan_result)){
      die("Plan doesn't exist again {daily}");
    }
    $response = $plan_result[0];

  }


  $action = $_POST["userAction"];
  switch($action){
    case"liquidate":
      if($today > strtotime($end)){
        die("Please your savings is ripe for withdrawal. Please withdraw");
      }

      if($today <= strtotime($end)){
        $end = date("Y-m-d",strtotime($end." +1 day"));
        die("Too Soon!!! Please your savings is not ripe for withdrawal. Wait till $end");
      }

      if($type == "daily"){
        die("You can't liquidate a daily savings. Please withdraw");
      }


            //update current savings
            $payload = [
              "status" => "liquidated",
              "liquidated" => "yes"
            ];
            $wpdb->update($satable_name,$payload,["id"=>$sid]);


      $penalty = $response->penalty;

      $total_saved = $start_amount * $count;
      $remove = ($total_saved * $penalty)/100;

      $give = $total_saved - $remove;

      $current_balance = vp_getuser($id,"vp_bal",true);
      $tot = $current_balance + $give;

      vp_updateuser($id,"vp_bal",$tot);

      $table_name = $wpdb->prefix.'vp_wallet';
      $wpdb->insert($table_name, array(
      'name'=> $name,
      'type'=> "Wallet",
      'description'=> "Liquidation for your $rduration $type savings @ a penalty of $penalty%",
      'fund_amount' => $give,
      'before_amount' => $current_balance,
      'now_amount' => $tot,
      'user_id' => $id,
      'status' => "Approved",
      'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
      ));
      $wallet_id = $wpdb->insert_id;


      
        //withdrawal
        $total_saved = $start_amount * $count;
        $payload = [
          "user_id" => $id,
          "type" => $type,
          "duration" => $rduration,
          "amount_withdrawn" => $give,
          "amount_saved" => $total_saved,
          "wallet_id" => $wallet_id,
          "savings_id" => $sid,
          "status" => "liquidated",
          "the_time" => date("Y-m-d")
        ];

        $wpdb->insert($witable_name,$payload);


        die("100");

    break;
    case"withdraw":

      $interest_info = "";

     

      if($today <= strtotime($end)){
        $end = date("Y-m-d",strtotime($end." +1 day"));
        die("Too Soon!!! Please your savings is not ripe for withdrawal. Wait till $end");
      }

      $charge = $response->cut;
      $charge_type = $response->cut_type;

      if($charge_type == "off"){
        //
        $start_amount = $result[0]->start_amount;

        //fund with
        $remove = $charge * $start_amount;

        $give = $amount_saved - $remove;

        if($give < 1){
          $give = 0;
          
          die("Please save more as you'd get $give naira after we remove our charge which is not adviceable. {{$type}}");

        }

        

      }else{

        //fund with
        $remove = ($charge * $amount_saved)/100;

        $add = 0;

        if($type == "daily"){
          $add = ($interest * $amount_saved)/100;
          $interest_info .= "plus $interest% of $amount_saved";
        }


        $give = ($amount_saved - $remove) + $add;

        if($give < 1){
          $give = 0;

          die("Please save more as you'd get $give naira after we remove our charge which is not adviceable. {{$type}}");
        }

      }



      //die($give."remove[$remove] = (charge($charge) * amount_saved($amount_saved))" ." - ".$charge_type ." - ".$type);

      

    


          $current_balance = vp_getuser($id,"vp_bal",true);
          $tot = $current_balance + $give;



          if($type == "daily"){

            
                vp_updateuser($id,"vp_bal",$tot);

                          
                //update current savings
                $payload = [
                  "status" => "withdrawn",
                  "liquidated" => "no"
                ];
                $wpdb->update($satable_name,$payload,["id"=>$sid]);


                //wallet
                $table_name = $wpdb->prefix.'vp_wallet';
                $added_to_db = $wpdb->insert($table_name, array(
                'name'=> $name,
                'type'=> "Wallet",
                'description'=> "Savings Withdrawal for your $rduration $type savings $interest_info ",
                'fund_amount' => $give,
                'before_amount' => $current_balance,
                'now_amount' => $tot,
                'user_id' => $id,
                'status' => "Approved",
                'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
                ));
                $wallet_id = $wpdb->insert_id;




              //withdrawal
              $total_saved = $start_amount * $count;
              $payload = [
                "user_id" => $id,
                "type" => $type,
                "duration" => $rduration,
                "amount_withdrawn" => $give,
                "amount_saved" => $total_saved,
                "wallet_id" => $wallet_id,
                "savings_id" => $sid,
                "status" => "withdrawn",
                "the_time" => date("Y-m-d")
              ];

              $wpdb->insert($witable_name,$payload);

          }else{


            //wallet
            $table_name  = $wpdb->prefix."vp_wallet";
            $wpdb->insert($table_name, array(
            'name'=> $name,
            'type'=> "Wallet",
            'description'=> "Savings Withdrawal for your $rduration $type savings",
            'fund_amount' => $give,
            'before_amount' => $current_balance,
            'now_amount' => $tot,
            'user_id' => $id,
            'status' => "pending",
            'the_time' => date('Y-m-d h:i:s A',$current_timestamp)
            ));
            $wallet_id = $wpdb->insert_id;


            //withdrawal
            $total_saved = $start_amount * $count;
            $payload = [
              "user_id" => $id,
              "type" => $type,
              "duration" => $rduration,
              "amount_withdrawn" => $give,
              "wallet_id" => $wallet_id,
              "savings_id" => $sid,
              "amount_saved" => $total_saved,
              "status" => "pending",
              "the_time" => date("Y-m-d")
            ];

            $wpdb->insert($witable_name,$payload);

            //update current savings
            $payload = [
              "status" => "pending-withdrawal",
              "liquidated" => "no"
            ];
            $wpdb->update($satable_name,$payload,["id"=>$sid]);
          }


            die("100");

    break;
  }

}
elseif(isset($_POST["setstatus"]) && isset($_POST["userid"]) && isset($_POST["plan"]) && isset($_POST["wallet_id"]) && isset($_POST["savings_id"])){
  
  include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');

  
  if(empty($_POST["setstatus"]) || empty($_POST["userid"]) || empty($_POST["plan"]) || empty($_POST["wallet_id"]) || empty($_POST["savings_id"])){
    die("All fields are required");
  }

  $opt = $_POST["setstatus"];
  $wallet_id = $_POST["wallet_id"];//this withdrawal history id
  $savings_id = $_POST["savings_id"];//this withdrawal history id
  $userid = $_POST["userid"];
  $type = $_POST["plan"];


  global $wpdb;

  $withdraw_table  = $wpdb->get_results("SELECT * FROM $witable_name WHERE type = '$type' AND wallet_id = '$wallet_id' AND savings_id = '$savings_id' ");

  if(empty($withdraw_table)){
    die("No withdrawal history of this type found");
  }



  $payload = [
    "status" => $opt
  ];

  $wpdb->update($satable_name,$payload,["id"=>$savings_id]);



  if($opt == "withdrawn"){

    if($type == "fixed"){
      global $wpdb;
      $result = $wpdb->get_results("SELECT * FROM $watable_name WHERE id = '$wallet_id' ");

      if(empty($result)){
        die("Wallet id not known");
      }
        $wallet = $result[0];
        $give = $wallet->fund_amount;

        $current_balance = vp_getuser($userid,"vp_bal",true);
        $tot = $current_balance + $give;

        vp_updateuser($userid,"vp_bal",$tot);

    }



    $payload = [
      "status" => "withdrawn"
    ];
    $wpdb->update($witable_name,$payload,["savings_id"=>$savings_id, "wallet_id" => $wallet_id]);


    $payload = [
      "status" => "Approved"
    ];
    $wpdb->update($watable_name,$payload,["id" => $wallet_id]);



  }else{

    $payload = [
      "status" => "rejected"
    ];
    $wpdb->update($witable_name,$payload,["savings_id"=>$savings_id, "wallet_id" => $wallet_id]);


    $payload = [
      "status" => "failed"
    ];
    $wpdb->update($watable_name,$payload,["id" => $wallet_id]);
  }


  die("100");


}else{
  die("Request not known");
}