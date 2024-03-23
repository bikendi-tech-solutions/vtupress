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
include_once(ABSPATH .'wp-content/plugins/vtupress/database.php');
include_once(ABSPATH .'wp-content/plugins/vtupress/foradmin.php');




if(!isset($_POST["spraycode"])){
        die("NO SPRAY CODE");
    }
    $spray_code = trim(htmlspecialchars($_POST["spraycode"]));
    $real_code =  vp_getoption("spraycode");
    
    if(empty($spray_code)){
        die("SPRAY CODE CAN'T BE EMPTY");
    }
    elseif(strtolower($spray_code) != "false"){
    
        if($real_code == "false"){
            $cur_id = get_current_user_id();
            $update_code = uniqid("vtu_$cur_id");
            vp_updateoption("spraycode",$update_code);
        }elseif($real_code != $spray_code ){
            die("INVALID SPRAYCODE");
        }else{
          //   die("INVALID SPRAYCODE");
        }
    }elseif(strtolower($spray_code) == "false"){
        if($real_code == "false"){
            $cur_id = get_current_user_id();
            $update_code = uniqid("vtu_$cur_id");
            vp_updateoption("spraycode",$update_code);
        }elseif($real_code != $spray_code ){
            die("INVALID SPRAYCODE");
        }else{
          //   die("INVALID SPRAYCODE");
        }
    }
    
    
    
if(isset($_POST["action"])){

$case = $_POST["action"];
$database = $_POST["database"];

switch($database){
    case"sairtime":
        global $wpdb;
        $airtime = $wpdb->prefix.'sairtime';
        
        switch($case){
        
            case"fixit":
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
                maybe_add_column($airtime,"name", "ALTER TABLE $airtime ADD name text");
                maybe_add_column($airtime,"email", "ALTER TABLE $airtime ADD email text");
                maybe_add_column($airtime,"network", "ALTER TABLE $airtime ADD network text");
                maybe_add_column($airtime,"phone", "ALTER TABLE $airtime ADD phone text");
                maybe_add_column($airtime,"bal_bf", "ALTER TABLE $airtime ADD bal_bf text");
                maybe_add_column($airtime,"bal_nw", "ALTER TABLE $airtime ADD bal_nw text");
                maybe_add_column($airtime,"amount", "ALTER TABLE $airtime ADD amount text");
                maybe_add_column($airtime,"resp_log", "ALTER TABLE $airtime ADD resp_log text");
                maybe_add_column($airtime,"user_id", "ALTER TABLE $airtime ADD user_id text");
        
                maybe_add_column($airtime,"status", "ALTER TABLE $airtime ADD status text");
                maybe_add_column($airtime,"the_time", "ALTER TABLE $airtime ADD the_time text");
        
                maybe_add_column($airtime,"id", "ALTER TABLE $airtime ADD id int NOT NULL AUTO_INCREMENT");
        
        die("100");
                break;
                case"startit":
        $sql = "DROP TABLE IF EXISTS $airtime";
        $wpdb->query($sql);
        
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
        
        die("101");
                break;
        
        
        }
        
            break;

            case"sdata":
                global $wpdb;
                $data = $wpdb->prefix.'sdata';
                
                switch($case){
                
                    case"fixit":
                        maybe_add_column($data,"network", "ALTER TABLE $data ADD network text");
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
                        maybe_add_column($data,"name", "ALTER TABLE $data ADD name text");
                        maybe_add_column($data,"email", "ALTER TABLE $data ADD email text");
                        maybe_add_column($data,"network", "ALTER TABLE $data ADD network text");
                        maybe_add_column($data,"phone", "ALTER TABLE $data ADD phone text");
                        maybe_add_column($data,"bal_bf", "ALTER TABLE $data ADD bal_bf text");
                        maybe_add_column($data,"bal_nw", "ALTER TABLE $data ADD bal_nw text");
                        maybe_add_column($data,"amount", "ALTER TABLE $data ADD amount text");
                        maybe_add_column($data,"resp_log", "ALTER TABLE $data ADD resp_log text");
                        maybe_add_column($data,"user_id", "ALTER TABLE $data ADD user_id text");
                        maybe_add_column($data,"status", "ALTER TABLE $data ADD status text");
                        maybe_add_column($data,"the_time", "ALTER TABLE $data ADD the_time text");
                        maybe_add_column($data,"id", "ALTER TABLE $data ADD id int NOT NULL AUTO_INCREMENT");
                
                die("100");
                        break;
                        case"startit":
                $sql = "DROP TABLE IF EXISTS $data";
                $wpdb->query($sql);
                
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
                
                die("101");
                        break;
                
                
                }
                
                    break;

                    case"scable":
                        global $wpdb;
                        $cable = $wpdb->prefix.'scable';
                        
                        switch($case){
                        
                            case"fixit":
                                $wpdb->query("ALTER TABLE $cable MODIFY COLUMN iucno text");
                                $wpdb->query("ALTER TABLE $cable MODIFY COLUMN time text");
                                maybe_add_column($cable,"browser", "ALTER TABLE $cable ADD browser text");
                                maybe_add_column($cable,"queried", "ALTER TABLE $cable ADD queried text");
                                maybe_add_column($cable,"trans_type", "ALTER TABLE $cable ADD trans_type text");
                                maybe_add_column($cable,"trans_method", "ALTER TABLE $cable ADD trans_method text");
                                maybe_add_column($cable,"via", "ALTER TABLE $cable ADD via text");
                                maybe_add_column($cable,"time_taken", "ALTER TABLE $cable ADD time_taken text");
                                maybe_add_column($cable,"response_id", "ALTER TABLE $cable ADD response_id text");
                                maybe_add_column($cable,"run_code", "ALTER TABLE $cable ADD run_code text");
                                maybe_add_column($cable,"status", "ALTER TABLE $cable ADD status text");

                                maybe_add_column($cable,"name", "ALTER TABLE $cable ADD name text");
                                maybe_add_column($cable,"email", "ALTER TABLE $cable ADD email text");
                                maybe_add_column($cable,"iucno", "ALTER TABLE $cable ADD iucno text");
                                maybe_add_column($cable,"product_id", "ALTER TABLE $cable ADD product_id text");
                                maybe_add_column($cable,"phone", "ALTER TABLE $cable ADD phone text");
                                maybe_add_column($cable,"type", "ALTER TABLE $cable ADD type text");
                                maybe_add_column($cable,"bal_bf", "ALTER TABLE $cable ADD bal_bf text");
                                maybe_add_column($cable,"bal_nw", "ALTER TABLE $cable ADD bal_nw text");
                                maybe_add_column($cable,"amount", "ALTER TABLE $cable ADD amount text");

                                maybe_add_column($cable,"resp_log", "ALTER TABLE $cable ADD resp_log text");
                                maybe_add_column($cable,"user_id", "ALTER TABLE $cable ADD user_id text");
                                maybe_add_column($cable,"time", "ALTER TABLE $cable ADD time text");
                                maybe_add_column($cable,"request_id", "ALTER TABLE $cable ADD request_id text");
                        
                        die("100");
                                break;
                                case"startit":
                        $sql = "DROP TABLE IF EXISTS  $cable";
                        $wpdb->query($sql);
                        
                        global $wpdb;
                        $scable = $wpdb->prefix.'scable';
                        $charset_collate = $wpdb->get_charset_collate();
                        $sql = "CREATE TABLE IF NOT EXISTS $scable(
                        id int NOT NULL AUTO_INCREMENT,
                        name text ,
                        email varchar(255) ,
                        iucno text ,
                        product_id text ,
                        phone text,
                        type text ,
                        bal_bf text,
                        bal_nw text,
                        amount text ,
                        resp_log text ,
                        user_id int ,
                        status text ,
                        time text ,
                        request_id text ,
                        response_id text,
                        run_code text,
                        via text ,
                        browser text ,
                        time_taken text ,
                        trans_type text ,
                        trans_method text ,
                        queried text ,
                        PRIMARY KEY  (id))$charset_collate;";
                        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                        dbDelta($sql);
                        
                        die("101");
                                break;
                        
                        
                        }
                        
                            break;


                            case"sbill":
                                global $wpdb;
                                $bill = $wpdb->prefix.'sbill';
                                
                                switch($case){
                                
                                    case"fixit":    
                                    $wpdb->query("ALTER TABLE $bill MODIFY COLUMN meterno text");
                                    $wpdb->query("ALTER TABLE $bill MODIFY COLUMN time text");
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
        
                                        maybe_add_column($bill,"name", "ALTER TABLE $bill ADD name text");
                                        maybe_add_column($bill,"email", "ALTER TABLE $bill ADD email text");
                                        maybe_add_column($bill,"meterno", "ALTER TABLE $bill ADD meterno text");
                                        maybe_add_column($bill,"product_id", "ALTER TABLE $bill ADD product_id text");
                                        maybe_add_column($bill,"phone", "ALTER TABLE $bill ADD phone text");
                                        maybe_add_column($bill,"type", "ALTER TABLE $bill ADD type text");
                                        maybe_add_column($bill,"meter_token", "ALTER TABLE $bill ADD meter_token text");
                                        maybe_add_column($bill,"bal_bf", "ALTER TABLE $bill ADD bal_bf text");
                                        maybe_add_column($bill,"bal_nw", "ALTER TABLE $bill ADD bal_nw text");
                                        maybe_add_column($bill,"amount", "ALTER TABLE $bill ADD amount text");
        
                                        maybe_add_column($bill,"resp_log", "ALTER TABLE $bill ADD resp_log text");
                                        maybe_add_column($bill,"user_id", "ALTER TABLE $bill ADD user_id text");
                                        maybe_add_column($bill,"time", "ALTER TABLE $bill ADD time text");
                                
                                die("100");
                                        break;
                                        case"startit":
                                $sql = "DROP TABLE IF EXISTS  $bill";
                                $wpdb->query($sql);
                                
                                global $wpdb;
                                $sbill = $wpdb->prefix.'sbill';
                                $charset_collate = $wpdb->get_charset_collate();
                                $sql = "CREATE TABLE IF NOT EXISTS $sbill(
                                id int NOT NULL AUTO_INCREMENT,
                                name text ,
                                email varchar(255) ,
                                meterno text ,
                                product_id text ,
                                phone text,
                                type text ,
                                meter_token text,
                                bal_bf text,
                                bal_nw text,
                                amount text ,
                                resp_log text ,
                                user_id int ,
                                status text ,
                                time text ,
                                request_id text ,
                                via text ,
                                browser text ,
                                charge text,
                                time_taken text ,
                                trans_type text ,
                                trans_method text ,
                                response_id text,
                                run_code text,
                                queried text ,
                                PRIMARY KEY  (id))$charset_collate;";
                                require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                                dbDelta($sql);
                                
                                die("101");
                                        break;
                                
                                
                                }
                                
                                    break;



                                    case"scards":
                                        global $wpdb;
                                        $scard = $wpdb->prefix.'scards';
                                        
                                        switch($case){
                                        
                                            case"fixit":    
                                                $scard = $wpdb->prefix."scards";
                                                $wpdb->query("ALTER TABLE $scard MODIFY COLUMN pin text");
                                                maybe_add_column($scard,"status", "ALTER TABLE $scard ADD status text");
                                        
                                        die("100");
                                                break;
                                                case"startit":
                                        $sql = "DROP TABLE IF EXISTS  $scard";
                                        $wpdb->query($sql);
                                        
                                        global $wpdb;
                                        $table_name = $wpdb->prefix.'scards';
                                        $charset_collate=$wpdb->get_charset_collate();
                                        $sql= "CREATE TABLE IF NOT EXISTS $table_name(
                                        id int NOT NULL AUTO_INCREMENT,
                                        name text NOT NULL,
                                        email varchar(255) DEFAULT '' NOT NULL,
                                        type text NOT NULL,
                                        value text NOT NULL,
                                        pin text NOT NULL,
                                        quantity text NOT NULL,
                                        bal_bf text NOT NULL,
                                        bal_nw text NOT NULL,
                                        amount text NOT NULL,
                                        user_id int NOT NULL,
                                        the_time text NOT NULL,
                                        status text NOT NULL,
                                        PRIMARY KEY (id))$charset_collate;";
                                        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                                        dbDelta($sql);
                                        
                                        die("101");
                                                break;
                                        
                                        
                                        }
                                        
                                            break;


                                            case"sepins":
                                                global $wpdb;
                                                $sepin = $wpdb->prefix.'sepins';
                                                
                                                switch($case){
                                                
                                                    case"fixit":    
                                                        $sepin = $wpdb->prefix."sepins";
                                                        $wpdb->query("ALTER TABLE $sepin MODIFY COLUMN pin text");
                                                        maybe_add_column($sepin,"status", "ALTER TABLE $sepin ADD status text");
                                                
                                                die("100");
                                                        break;
                                                        case"startit":
                                                $sql = "DROP TABLE IF EXISTS  $sepin";
                                                $wpdb->query($sql);
                                                
                                                global $wpdb;
                                                $table_name = $wpdb->prefix.'sepins';
                                                $charset_collate=$wpdb->get_charset_collate();
                                                $sql= "CREATE TABLE IF NOT EXISTS $table_name(
                                                id int NOT NULL AUTO_INCREMENT,
                                                name text NOT NULL,
                                                email varchar(255) DEFAULT '' NOT NULL,
                                                type text NOT NULL,
                                                pin text NOT NULL,
                                                quantity text NOT NULL,
                                                bal_bf text NOT NULL,
                                                bal_nw text NOT NULL,
                                                amount text NOT NULL,
                                                user_id int NOT NULL,
                                                the_time text NOT NULL,
                                                status text,
                                                PRIMARY KEY (id))$charset_collate;";
                                                require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                                                dbDelta($sql);
                                                
                                                die("101");
                                                        break;
                                                
                                                
                                                }
                                                
                                                    break;



                                                    case"ssms":
                                                        global $wpdb;
                                                        $ssms = $wpdb->prefix.'ssms';
                                                        
                                                        switch($case){
                                                        
                                                            case"fixit":
                                                        
                                                        die("001");
                                                                break;
                                                                case"startit":
                                                        $sql = "DROP TABLE IF EXISTS  $ssms";
                                                        $wpdb->query($sql);
                                                        
                                                        global $wpdb;
                                                        $table_name = $wpdb->prefix.'ssms';
                                                        $charset_collate=$wpdb->get_charset_collate();
                                                        $sql= "CREATE TABLE IF NOT EXISTS $table_name(
                                                        id int NOT NULL AUTO_INCREMENT,
                                                        name text,
                                                        email varchar(255) DEFAULT '',
                                                        sender text ,
                                                        receiver text,
                                                        bal_bf text,
                                                        bal_nw text,
                                                        amount text,
                                                        user_id int,
                                                        the_time text,
                                                        resp_log text,
                                                        status text,
                                                        PRIMARY KEY (id))$charset_collate;";
                                                        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
                                                        dbDelta($sql);
                                                        
                                                        die("101");
                                                                break;
                                                        
                                                        
                                                        }
                                                        
                                                            break;


                                                            case"vp_chat":
                                                                global $wpdb;
                                                                $vp_chat = $wpdb->prefix.'vp_chat';
                                                                
                                                                switch($case){
                                                                
                                                                    case"fixit":
                                                                
                                                                die("001");
                                                                        break;
                                                                        case"startit":
                                                                $sql = "DROP TABLE IF EXISTS  $vp_chat";
                                                                $wpdb->query($sql);
                                                                
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
                                                                
                                                                die("101");
                                                                        break;
                                                                
                                                                
                                                                }
                                                                
                                                             break;

                                                             case"vp_transactions":
                                                                global $wpdb;
                                                                $vp_transactions = $wpdb->prefix.'vp_transactions';
                                                                
                                                                switch($case){
                                                                
                                                                    case"fixit":
                                                                
                                                                die("001");
                                                                        break;
                                                                        case"startit":
                                                                $sql = "DROP TABLE IF EXISTS  $vp_transactions";
                                                                $wpdb->query($sql);
                                                                
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
                                                                
                                                                die("101");
                                                                        break;
                                                                
                                                                
                                                                }
                                                                
                                                             break;

                                                             
                                                             case"vp_levels":
                                                                $id = 1;
                                                                global $wpdb;
                                                                $table_name = $wpdb->prefix."vp_levels";
                                                
                                                                switch($case){
                                                                
                                                                    case"fixit":
                                                                        
                                                                        $datas = $wpdb->get_results("SELECT * FROM  $table_name WHERE id = $id ");
                                                                        $total_level = floatval($datas[0]->total_level);
                                                                        $next = $total_level + 1;
                                                                        $new_level = "level_".$next;
                                                                        $new_upgrade = "level_".$next."_upgrade";
                                                                        $new_upgrade_pv = "level_".$next."_upgrade_pv";

                                                                        $tbcol = array(
                                                                                'airtime_pv'=> "0",
                                                                                'data_pv'=> "0",
                                                                                'cable_pv'=> "0",
                                                                                'bill_pv'=> "0",
                                                                                'mtn_vtu'=> "0",
                                                                                'glo_vtu'=> "0",
                                                                                'mobile_vtu'=> "0",
                                                                                'airtel_vtu'=> "0",
                                                                                
                                                                                'mtn_awuf'=> "0",
                                                                                'glo_awuf'=> "0",
                                                                                'mobile_awuf'=> "0",
                                                                                'airtel_awuf'=> "0",
                                                                                
                                                                                'mtn_share'=> "0",
                                                                                'glo_share'=> "0",
                                                                                'mobile_share'=> "0",
                                                                                'airtel_share'=> "0",
                                                                                
                                                                                'mtn_sme'=> "0",
                                                                                'glo_sme'=> "0",
                                                                                'mobile_sme'=> "0",
                                                                                'airtel_sme'=> "0",
                                                                                
                                                                                'mtn_corporate'=> "0",
                                                                                'glo_corporate'=> "0",
                                                                                'mobile_corporate'=> "0",
                                                                                'airtel_corporate'=> "0",
                                                                                'charge_back_percentage'=> "0",
                                                                                'upgrade_pv' => '0',
                                                                                'mtn_gifting'=> "0",
                                                                                'glo_gifting'=> "0",
                                                                                'mobile_gifting'=> "0",
                                                                                'airtel_gifting'=> "0",
                                                                                
                                                                                'cable'=> "0",
                                                                                
                                                                                'bill_prepaid'=> "0",
                                                                                
                                                                                'card_mtn'=> "0",
                                                                                'card_glo'=> "0",
                                                                                'card_9mobile'=> "0",
                                                                                'card_airtel'=> "0",
                                                                                
                                                                                'data_mtn'=> "0",
                                                                                'data_glo'=> "0",
                                                                                'data_9mobile'=> "0",
                                                                                'data_airtel'=> "0",
                                                                                
                                                                                'epin_waec'=> "0",
                                                                                'epin_neco'=> "0",
                                                                                'epin_jamb'=> "0",
                                                                                'epin_nabteb'=> "0",
                                                                                
                                                                                'airtime_bonus_ex1'=> "0",
                                                                                'extra_feature_assigned_uId'=> "0",
                                                                                'data_bonus_ex1'=> "0",
                                                                                'ref_airtime_bonus_ex1'=> "0",
                                                                                'ref_data_bonus_ex1'=> "0",
                                                                                'data_bonus_type_ex1'=> "0",
                                                                                'enable_extra_service'=> "0",
                                                                                
                                                                                'status'=> "in-active",
                                                                                
                                                                                'upgrade'=> "000",
                                                                                'monthly_sub'=> "no",
                                                                                'upgrade_bonus'=> "000",
                                                                                'developer'=> "no",
                                                                                'transfer'=> "no",
                                                                                'monthly_referee'=> "0",
                                                                                'monthly_transactions_number'=> "0",
                                                                                'monthly_transactions_amount'=> "0",
                                                                                
                                                                                'total_level'=> "0",
                                                                                'level_1'=> "0",
                                                                                'level_1_data'=> "0",
                                                                                'level_1_cable'=> "0",
                                                                                'level_1_bill'=> "0",
                                                                                'level_1_ecards'=> "0",
                                                                                'level_1_edatas'=> "0",
                                                                                'level_1_epins'=> "0",
                                                                                
                                                                                'level_1_pv'=> "0",
                                                                                'level_1_data_pv'=> "0",
                                                                                'level_1_cable_pv'=> "0",
                                                                                'level_1_bill_pv'=> "0",
                                                                                'level_1_ecards_pv'=> "0",
                                                                                'level_1_edatas_pv'=> "0",
                                                                                'level_1_epins_pv'=> "0",
                                                                                
                                                                                
                                                                                'level_1_upgrade'=> "0",
                                                                                'level_1_upgrade_pv'=> "0"
                                                                                
                                                                                
                                                                        );

                                                                        foreach($tbcol as $key => $val){
                                                                        maybe_add_column($table_name,$key, "ALTER TABLE $table_name ADD $key TEXT");
                                                                        }

                                                                        maybe_add_column($table_name,$new_level, "ALTER TABLE $table_name ADD $new_level DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_data", "ALTER TABLE $table_name ADD {$new_level}_data DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_cable", "ALTER TABLE $table_name ADD {$new_level}_cable DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_bill", "ALTER TABLE $table_name ADD {$new_level}_bill DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_ecards", "ALTER TABLE $table_name ADD {$new_level}_ecards DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_epins", "ALTER TABLE $table_name ADD {$new_level}_epins DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_pv", "ALTER TABLE $table_name ADD {$new_level}_pv DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_data_pv", "ALTER TABLE $table_name ADD {$new_level}_data_pv DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_cable_pv", "ALTER TABLE $table_name ADD {$new_level}_cable_pv DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_bill_pv", "ALTER TABLE $table_name ADD {$new_level}_bill_pv DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_epins_pv", "ALTER TABLE $table_name ADD {$new_level}_epins_pv DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,$new_level."_ecards_pv", "ALTER TABLE $table_name ADD {$new_level}_ecards_pv DECIMAL(5,2)");
                                                        
                                                                        maybe_add_column($table_name,"$new_upgrade", "ALTER TABLE $table_name ADD $new_upgrade DECIMAL(5,2)");
                                                                        maybe_add_column($table_name,"$new_upgrade_pv", "ALTER TABLE $table_name ADD $new_upgrade_pv DECIMAL(5,2)");
                                                                        $where = [ 'id' => $id ];
                                                                        $arr = [$new_level => "0", $new_upgrade => "0", "total_level" => $next ];
                                                        $updated = $wpdb->update( $wpdb->prefix.'vp_levels', $arr, $where);
                                                                die("100");
                                                                        break;
                                                                }
                                                                
                                                             break;
        
                    
}






}


?>