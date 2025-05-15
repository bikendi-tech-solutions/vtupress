<?php
setcookie("run_code", "vtupress", time() + (30 * 24 * 60 * 60), "/");
if(!isset($_COOKIE["last_bal"])){
  setcookie("last_bal", 0, time() + (30 * 24 * 60 * 60), "/");
}
if(!isset($_COOKIE["last_transaction_time"])){
    setcookie("last_transaction_time", "null", time() + (30 * 24 * 60 * 60), "/");
    setcookie("last_recipient", "null", time() + (30 * 24 * 60 * 60), "/");
}
//setcookie("run_code", "vtupress", time() + (30 * 24 * 60 * 60), "/");
error_reporting(0);
if(!defined('ABSPATH')){
    $pagePath = explode('/wp-content/', dirname(__FILE__));
    include_once(str_replace('wp-content/' , '', $pagePath[0] . '/wp-load.php'));
}
if(strtolower(vp_getoption("vpdebug")) != "yes"){
error_reporting(0);
}
else{
error_reporting(E_ALL & ~E_NOTICE);
}

$mess = vp_option_array($option_array,"vpwalm");
$me = get_user_by("ID",get_current_user_id())->user_login;
/*
add_action('template_redirect', 'vp_user_dashboard', 50);
add_action('init', 'vp_user_dashboard', 50);
add_action('wp_loaded', 'vp_user_dashboard', 50);
*/
if(vp_option_array($option_array,"vp_security") == "yes" && vp_option_array($option_array,"secur_mod") != "off" ){
$ban_ip = vp_option_array($option_array,"vp_ips_ban");
$ban_user = vp_option_array($option_array,"vp_users_ban");

if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
	$system = "Shared Internet";
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$system = "Proxy";
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
	$system = "Remote";
  }
if(is_numeric(stripos($ban_ip,$ip_address)) != false || stripos($ban_ip,$ip_address) != false || is_numeric(stripos($ban_user,$me)) != false || stripos($ban_user,$me) != false  && !empty($me)){
if(vp_option_array($option_array,"access_user_dashboard") == "true"){
	wp_redirect(vp_option_array($option_array,"siteurl"));
}
	
}

if(vp_option_array($option_array,"access_country") == "true"){
$details = json_decode(vp_get_contents("http://ipinfo.io/{$ip_address}/json"));	
	if(strtolower($details->country) != "ng" && !empty($details->country)){	
	die("ACCESS NOT GRANTED - [NG]");
	}
}

}



	?>
<!DOCTYPE html>
<html lang="en"><!-- Basic -->
<head>
<title>Dashboard</title>

<link rel="stylesheet" href="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/css/bootstrap.min.css?v=1';?>" />
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/bootstrap.min.js?v=1';?>" ></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/jquery.js?v=1';?>" ></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/sweet.js?v=1';?>" ></script>
<script src="<?php echo vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/pdf.js?v=1';?>" ></script>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<?php
if(vp_getoption("fcm") == "yes" && vp_getoption("fcm_generate") == "yes"){
?>	
<script src="https://www.gstatic.com/firebasejs/4.9.1/firebase.js"></script>
<link rel="manifest" href="<?php echo esc_url(plugins_url('vtupress/manifest.json'));?>">
<script>
  // Initialize Firebase
  /*Update this config*/
  var config = {
    apiKey: "AIzaSyB4sWwOdxfcrkeyUakVm1xu2FME4ZfnpaE",
    authDomain: "vtupress-test.firebaseapp.com",
    databaseURL: "",
    projectId: "vtupress-test",
    storageBucket: "vtupress-test.appspot.com",
    messagingSenderId: "650482614558"
  };
  firebase.initializeApp(config);

	const messaging = firebase.messaging();
	// Retrieve Firebase Messaging object.
	messaging.requestPermission()
	.then(function() {
	  console.log('Notification permission granted.');
	  // TODO(developer): Retrieve an Instance ID token for use with FCM.
	  	console.log('Token already saved.');
	  	getRegToken();

	})
	.catch(function(err) {
	  console.log('Unable to get permission to notify.', err);
	});

	function getRegToken(argument) {
		messaging.getToken()
		  .then(function(currentToken) {
		    if (currentToken) {
		      console.log(currentToken);
			$.ajax({
			url: '<?php echo esc_url(plugins_url("vtupress/message.php"));?>',
			method: 'post',
			data: 'set_token=' + currentToken
		}).done(function(result){
			console.log(result);
			if(result == "300"){
				jQuery(".notify").addClass("col align-self-center border border-4 border-success mb-3");
			}
			else if(result == "100"){
				jQuery(".notify").addClass("col align-self-center border border-4 border-primary mb-3");
			}
			else{
				jQuery(".notify").addClass("col align-self-center border border-4 border-danger mb-3");
			}
		})
		    } else {
		      console.log('No Instance ID token available. Request permission to generate one.');
		    }
		  })
		  .catch(function(err) {
		    console.log('An error occurred while retrieving token. ', err);
		  });
	}



	messaging.onMessage(function(payload) {
	  console.log("Message received. ", payload);
	  notificationTitle = payload.data.title;
	  notificationOptions = {
	  	body: payload.data.body,
	  	icon: payload.data.icon,
	  	image:  payload.data.image
	  };
	  var notification = new Notification(notificationTitle,notificationOptions);
	});
	
</script>

<?php
}
else{
?>
<script>
jQuery(".notify").addClass("col align-self-center border border-4 border-secondary mb-3");
</script>
<?php	
}
?>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/album/">

<style>
  		/* width */
::-webkit-scrollbar {
    width: 5px;
}

/* button */
::-webkit-scrollbar-button {
    background: #222;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #333;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #666;
}

/* Track */
::-webkit-scrollbar-track {
    background: #585858;
}

/* The track NOT covered by the handle.
::-webkit-scrollbar-track-piece {
    background: #000;
}

/* Corner */
::-webkit-scrollbar-corner {
    background: #999;
}

/* Resizer */
::-webkit-resizer {
    background: #111;
}


.img-sm{
	height:30px;
}
.bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    .fa{
      margin-right:.5em;
    }
</style>
</head>
<body>






<?php
	
if(isset($_REQUEST["vend"]) && isset($_COOKIE["switchto"]) && is_numeric($_COOKIE["switchto"]) && $_REQUEST["vend"] == "switch"){
	$admin_id = $_COOKIE["switchto"];
setcookie("switchto","", time() - 3600, "/");
		wp_clear_auth_cookie();
        wp_set_current_user($admin_id);
        wp_set_auth_cookie($admin_id,true);
		 
}

//$cb = vp_option_array($option_array,"cb");

$id = get_current_user_id();


	
vp_adduser($id, "vp_pin", rand (1000,2000));
vp_adduser($id,'vp_pin_set','no');

$user_array = json_decode(get_user_meta($id,"vp_user_data",true),true);
if(vp_user_array($user_array,$id,'vp_pin_set',true) == "no"){
	?>
<script>
jQuery(window).on("load",function(){
	
swal("Enter Your Transaction Pin Here:", {
  content: "input",
})
.then((value) => {
jQuery("#cover-spin").show();	
var obj = {};
obj["id"] = "<?php echo $id;?>";
obj["set_pin"] = "set_pin";
obj["pin"] = value;
jQuery.ajax({
  url: '<?php echo esc_url(plugins_url("vtupress/vend.php"));?>',
  data: obj,
 dataType: 'json',
  'cache': false,
  "async": true,
  error: function (jqXHR, exception) {
	  jQuery("#cover-spin").hide();
        var msg = "";
        if (jqXHR.status === 0) {
            msg = "No Connection.\n Verify Network.";
     swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
  
        } else if (jqXHR.status == 404) {
            msg = "Requested page not found. [404]";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (jqXHR.status == 500) {
            msg = "Internal Server Error [500].";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "parsererror") {
            msg = "Requested JSON parse failed.";
			   swal({
  title: msg,
  text: jqXHR.responseText,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "timeout") {
            msg = "Time out error.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else if (exception === "abort") {
            msg = "Ajax request aborted.";
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        } else {
            msg = "Uncaught Error.\n" + jqXHR.responseText;
			 swal({
  title: "Error!",
  text: msg,
  icon: "error",
  button: "Okay",
});
        }
    },
  
  success: function(data) {
	  jQuery("#cover-spin").hide();
        if(data.code == "100"){
		  swal({
  title: "Pin Set",
  text: data.message,
  icon: "success",
  button: "Okay",
}).then((value) => {
	location.reload();
});
	  }
	  else{
		 jQuery("#cover-spin").hide();
swal({
  title: "Error",
  text: data.message,
  icon: "error",
  button: "Okay",
}).then((value) => {
	jQuery("#cover-spin").show();
	location.reload();
});

  }
  },
  type: 'POST'

});

});

});

</script>
	
<?php
	
}


/*
foreach(vtupress_user_details() as $key => $value){
	global ${$key};
${$key} = {$value}.'\n';
	
}*/

 extract(vtupress_user_details());

echo'



<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />


<script src="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/js/jquery.js?v=1"></script>
<script src="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/js/popper.min.js?v=1"></script>



<link href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/css2.css?v=1" rel="stylesheet">
<link href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/fontawesome-all.css?v=1" rel="stylesheet">
<link href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/csss/fontawesome-min.css?v=1" rel="stylesheet">

<link href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/css2(1).css?v=1" rel="stylesheet">

<link rel="stylesheet" href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/ionicons.min.css?v=1">
<link rel="stylesheet" href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/adminlte.min.css?v=1">
<link rel="stylesheet" href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/style1.css?v=1">
<link rel="stylesheet" href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/select2.min.css?v=1">

<link type="text/css" href="'.vp_option_array($option_array,'siteurl').'/wp-content/plugins/vtupress/template/default/css/dataTables.checkboxes.css?v=1" rel="stylesheet">


	
	<style>
    .toggle.android { border-radius: 0px;}
    .toggle.android .toggle-handle { border-radius: 0px; }
    .no-touch {
      opacity: 0.65;
      box-shadow: none;
      pointer-events: none;
      cursor: not-allowed;
      -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
          -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently
                                      supported by Chrome, Edge, Opera and Firefox */
    }
     input[type="checkbox"] {
      width:15px;
      height:15px;
      background:white;
      border-radius:5px;
      border:2px solid #555;
    }

    button.multiselect {
      background-color: initial;
      border: 1px solid #ced4da;
      width: 100%;
    }

     .multiselect-container {
        width: 100% !important;
    }

    #cover-spin {
        position:fixed;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        /*display:none;*/
    }

    @-webkit-keyframes spin {
      from {-webkit-transform:rotate(0deg);}
      to {-webkit-transform:rotate(360deg);}
    }

    @keyframes  spin {
      from {transform:rotate(0deg);}
      to {transform:rotate(360deg);}
    }

    #cover-spin::after {
        content:"";
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }

    .text-secondary:hover{
        color:white;
        background-color: transparent;
    }
	
	
	tr td a button {
    border-radius: 50px;
    border: 0;
    background-color: #28a745;
    color: white;
    padding: 5px;
	}


  </style><style data-styled="active" data-styled-version="5.1.1"></style>
		
      <div id="cover-spin" >
	  
	  </div>

 <div id="overlay">
    <div class="loading-container">
        <div class="loading"></div>
        <div id="loading-text"></div>
    </div>
</div>
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a type="button" class="btn btn-outline-secondary" href="#"> <i class="fas fa-user"></i> '.get_current_user_id().'</a>
            <a type="button" class="btn btn-outline-secondary" href="tel:+234'.vp_option_array($option_array,"vp_phone_line").'"> <i class="fas fa-phone"></i> 0'.vp_option_array($option_array,"vp_phone_line").'</a>
            <a class="btn btn-outline-secondary" href="whatsapp://send?phone=234'.vp_option_array($option_array,"vp_whatsapp").'&amp;text=Hi,+I+need+your+help+with"><i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp</a>
			';
if(vp_getoption("vp_whatsapp_group") != "link" && vp_getoption("vp_whatsapp_group") != "false" && !empty(vp_getoption("vp_whatsapp_group"))){
?>
<a type="button" class="btn btn-outline-secondary" href="<?php echo vp_getoption("vp_whatsapp_group");?>">
Whatsapp Group
</a>
<?php
}
echo'
        </div>
      </li>
           
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link text-center">
      <span class="brand-text font-weight-light">';bloginfo('name'); echo'</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAh1BMVEX///8AAAD6+vrv7++0tLTPz8/j4+Pu7u7BwcHg4ODExMS+vr7r6+v39/fc3Nzz8/N6eno0NDTW1tbLy8upqalkZGS2traWlpaNjY2ioqJCQkIUFBQuLi5/f39RUVF1dXWcnJwhISFpaWldXV0+Pj4mJiZKSkoSEhI3Nzc/Pz8cHBxfX19WVlbi3uPrAAAPmUlEQVR4nNVda1vqvBJ9RYrcrxUKglARcSv///cdATFrkrRNZtKWsz7sZ4uSTNrJ3Cf577+y0RhN283FZvy8mhzSl4eH40t6mKyex8mu2Z6OGqXPXyqGzeR1/ZCP9WsyH9ZNKAed5uZfwdoQq828VTfJHmjFzx6LU3hr9usm3QGD3vaFtbwr0nF3UPcS8vAYvwpWd8OpOap7IXYMmiGWd8V+XvdqTERvwZZ3xXZa95IQjfgQeH1nTJp1r+uG/qaE5V2RPNa9uB8MnTRDuh8ni3jea5/Rm8eLZLxPXb64rVtNRh8FFL5u4m4nSzaOWt14UySfTnVaPNNVHmn7RddNg/e7i33uGjslryMLrRz+fI59qerkGULbOvbjaJxFziHh8tU0yZTJSeWmzjKDkslCxlKdRZYzUq3umKb2t7cLYTq3dvbRPyvcjnYGfYuCTRB921k12AT56FpnX4Q1l58S2yRflZhyW8vMaQmbpBF/WWbahJ9IQ2SZ9b1d0mS9iTnZoeTdaOGdVbjtZ6L7aU64LHG+J1OSp70S5ztjboYMPkrTjRYRU+bzvGFnTluSwFkYE42riXYOTOFWivo3jOPP6oz+qWHNjYPPMTK24C74HHkwRNxr4AmG+gQfVYc2W7pU/QpKgSFjqpAwOgw5EHCXxNrQ63pCC0PdyJmFGln3lMq3nLKgC9VumGF17ihbx+dB56YgkWNNir3UG/yaakuM5UNqC/yoO6U5+gy8RG2B9W1BBS1/IDRvNCETgCcCQBMMInETBxwrIJqULIH7pin6+8kGaYSxneJhoHFKgBZqYAaMH+kodadIKOgSU9YYjcMdL1Bf4okzBM0J3ROLXjEj9DHUGFWE91jTQ5forRZ75OtlhtP4oDR6clmffPle9KAOoq6//L5LQrH3YcnYQLbSs883Se3BPdiiWSBZVY+tSEwGliCuDITZnDXaAL91KJM+OYhZsnb91gm/de/lgsQldkwwEsu9zpCFG0jQ30lvEx69ZylzA7KcE5+iePosm7oQaOAr2RX/PZGj974Jr4j8SMa/vpviwAKg4v8o+mOMgdy3JkSgo1dgYj7hK7zrimsCjEYU2KcoZv5fePQM5NNd3h+i+ixk6LsCJm3yKnuwmjJ82KIxak2j2Swa9sMHzlEF5Chx9JrDVlg9dhd7kkVebeOwcQNMwj9l/hWkBI4BJ48SW3XTD/ZxNi2+QJ99m/VH+KaDiZnh5mhf3u9ub4biWBQ2WWof2GgSaNp5UevaD8ZhdjwabxkvEV9hmPxx7NgC9RxkjZhFsgfBoew+iKbo5bInxVuIQm6YzypOURcGyMH0i/oUNASo7kDH1maPQeIxgEGaVQeejX9yRwZEtuWBoUUqfoUDVhubuOwAX6L5WxC2K+lMRgWVI8QRBRjLrOqFX0oF6dxO//r73L4dtZu75DtDiUjrIGBvGHVvkAOQxg9tW3C1nNK9P5gubS1FB5nHhjEmfVvDxhEG8c1C6XRn1wWPu9Rco0xtQJ/Egv4G5YxoCrO+7jWP6WeGSDqKuho6MBD9DXCWbLvrJcurIrkcvWvfeBHtRWB9mhIEr0KU7e1p5LoY8HqtmkiUg5Qjrwpe7rtkfE1NvLpx3Egzf94EFKD9jZ/D5pHImQa1RBfF3/iFJp0kNECBJgoAUE+SjU77sXzUaqgSIBIehmr3lvp0zx9bK8byC1LQqkrnNJkFVjaFvS7w7UeESF87ukO+7c7gBiB/reQ4KCWBTUEyzv4+LZVSfEcD2PTvOYH8EXQxED7jlKeQvfjNJ0QN8qd3YGiBFEMzkzcMsRb4JTwgTW8cCbKazxz4BjKjeQVAWczX+2B13FwoVc8gcCugKMKzdkeB5DrZLxEcjF+z5tH4hIEoBG3E6OPrLbVffrUO8Be/LgEEqVdtkga039ghRth1V/sFTDa2c4belySihFqRnTmB9MvVrlK1DLxS2zPA+5J1BqJS5Y4Bpsfu8kEI4sD7kiVbUO+z97NKel92DCRt2CYbjCFQ1ReAWmWzKcQyzj+21Y/sjB7Y3NJiWxCn7PQQkHMWNSBo2PEDtXteuEPcgDqRy/BgQJ4fuIrm/2OTpcLp8jIxyC5wlRc8pbP9qIwRrrGF21CeloNIC3sjrnEIWC87/QM2A3cIBVCt7CSfMnBPRHmwC9YXcpoAkEGSE7Qmu5IdHVHSOUS1JmxEro0FQYsGKgu2f690WIgCB3ATuc8c/IARLpdNk0rXh2g9AVHDlVsQWhuCIc6vv1DjhShIhRfAzZmCi9iFPcQONYM4DlFwAC+AHVRRQ/RAsLKlBKwwxHFYoF3Z+usLhlAnNvNjlGFXCPqLTZLypJP/UjlLwApD1KkZ7h0DSuNsCMdyocYIIWnAqGFzqZIuY6COfyaZSjmFKN+CfchmKxXaf4MV8o1m5eGH0IcBtAXowBOskE+d4voQNg1ofHbpElrKIVaonliIyuIQIXhlqU2CcKmKGkjSjzdAPTN7DBUL+QoiaaIANCmowfiJUvRYQ6wQjBp53z4EhfnRTVyhkvSCVkNVEyMvE4XgMt8GUT7hMcwKlXAQ1zViwJTPED1YYfr3f0F5JzCF1KoBfS/oiFArTCHUJqliUWQJagwugPAtO/aH0n0CTCGhDRIqglHOUANJjnJQkZBVCP/wP8Kmslrm/EJmZyjB8E2scAEUXbIC3PQhCD24KmWFi+wRMLUktinWKUrq6dE/JMFTPsKU4GJaRlQkqRT0AhlfMiTGcfkyEA+bE511gBwVIiJ8BmbguUIQaBGm6dQ4bYzqyxx0zMDznhUp/ROJZDAbpjisLDOGLY68PTQRj3ADiepr2UQBsNCXE10mtY2yFlqSmYFsojRxhDXQ/mORKyWEpKiHfVYQ6tFJD5Amx4v5xjPIwU3SagBVInQuDVEKUdzdTE4W9dMZ9FBSafNcSh40lHeII9akYevkXtoxoP0I0sPFQHqeRTJUIUnPZZvToxaPruJCvzTjWehkwngXEtSPMt9uaN5F4Tag5dIMWVQSssiXnxVhIlFjvV9uXaxj26nti6kkeq429UQnjT/ozELlBQWtXTPLRStXCJwnfRCwvdl61na9zg0f2Tb03HLJyh+OXIkKSeSm/gFTiBkH3utIpqZcbUSF9yYydyO8st9onfqAVzqpt9dZcdrNlGXf6S6c2vV5fcGgW38/EYaR8jhUx3p1WmUflWEeMnHkhEzV129vDN6BvwQbmA3Lk8jprRqIbV3g/rkGUPC3CDxsRG/Ob5mHX1zMCP+rVycXJjav69n5kgTa8E9yKkHhW8tuXuF1e0Ymqfm4LeTRuHbX188A+fz3GTgufnxv3M7yquytvs/JEc9gEhuq1c/Z7Nu+CIR6GW56X7MWeohyrykFfGvPVVcjXiIeZIBSxODn+8Rz9QWujHqhyOU9Ppt2hs4bPm048FiBHkgsu5s1bY0M6+tvJfnn8KQ7axnVgN3eDUyKZjantlrfLpk2dpSpMD8X2c9T41RnKxUkKVpoeGKG40ha6/0kN4DYibf60QmvSS+/CE67xML1yYOaIuODWeMWa36i87vIgsFw1lzudrvlfDZ0Cahql+a42czwJXpSEuwpNyeRml7h73k7gx4M7xa8gR2hxZRhJJcQAr1Ir6w7XkfUZ3Eo7cTtplntsLEdbAhqsEgT29mgS3SIg0Mhhx7rQ8FRuEmoGN2xiHfDgCyxeDOAd2IwNVhzRTUxpCG55GuIqUQrCgbmngXlkUAnB3wICiacQE+TKGAvkH+W1wTj5OepiRMX+ipJE8R9yTff0NizRAcgapnbUE/ObGe33nuAuNO5fAomqW3L4i0DeS+R2GCVHGmOWam83Aq+basXuHUah1gaZV1XrQHNixzBphorMk5+hGBGjjjFCG45powJYtxk5uJxF2bEmzAJmxXGw03Bb9/3Bc6aeTAnvOmsfl/U+lmGDT7NCi+BwhseMt4PPoVMTw6tTTsz4BEy5ap6ClT89j5VNEOyi1xbRePgMOK+dC/go7W+IfSYc6K+qAlsBS04T8XXXIFna7tXBF9OnhVCbt6xCBv4bdVXQ6C1b3lHGNbLlQ/5NTFou1Z+mR7EpswqyjiXbgRxGww+XLsOUwZQ0utikNSLFfSxErNa49MoZ44KABpD12UYli30x5GhtRcV6KQkLvAB098Q27xwHGIhEQscnYpabnx8zyAM5aiLqUxiMMiM8KTqub8kKyCIprKTiMfQCJqeIMwq8ilyKAO3jbhzTq3WxD9SWzFMM4sIYHAo54fIRsdSC5Iy2N0+BSat0iJFgEHy5z2QTei8e1L81s0KBIlc293AQMNvSrVBwo3ORzrYjhIFW0DUtiACGFW/0pRk4Txqw8k5m8dLCA886F140h0BtstVIxMp42VnkdztxZYHi7XG+53hlZ1/pOeje41Ew9pn7aPUbRURxCxAXmKqX8LgaYXQ5N0bLrnOey0hAr7USl28a/JoWdMWhE+tV5OqqoBnml5kZBdId8ADJG5CnLLDhqonOqZIH0u+Z1QPVhdDtCGrYo5VhN+w14lU7/siplaauO0LfetgAS5mEmBgpYnd1NmxjVbzVfKphSRBmt1Wn16bUXqFUbooTNJaSlrD3z/pBbM5Q3ieiiG76vINb2jqBInjDfoSZa2PcujCNMDF2toSjzXfZE3vQXmYhNg0Ol/UabV1tM6OdRipoBeS1md66w/7PZTY05XGez2c2tA1RUCZYFz3V8eN1oZyDlrL85hqo59CnAHpBaPSOHRY+lufoNrXaPQ9iO9KNGFca/ha3W4cmbZaGRI9uz+mbJh3RpqND0HQN7vOqmDVrjltefrK7CtYl+1MRZZQQ5k2h9EH9MMwIU70zMLwZE74UW6g6OmfZcqy1ji1rO9hV9JkCvo9nJf3GOamcgrzjtIfTKoIuLdsDebpMrB6iq09muW1BVDYXuPDwzYcs07tbd7v1UVQHg0L54LDMoQQGC0zTiEQ39vthVlqp2K1lFk6ozirZbH6xHNmv/bngnsYQmeZ2aW/ryPE17CcSXLDW+xLUX++Ndvxb3gvU+fm4TH3sIG3ZeR2qtkgWm7zOk0PdcZN+gUHKqz3SRy1MhXJ07C52GefsVD/+s7oj/MJvOLzbZzs4t4P2u2ff+bLZLP9yO8QvmJVU3ESwciuHkPgTXokVjDMnU4q8cTXovJISR5aOYKVhe8yTF0huj7HuOTjI76r16fQ6D0XU1+IVRzippPyECWZZ1u5YFvQpX8feOxt/I+n+cF3XGO1lTee2ot98Zr+MBnHtRRWS9GfLTergnPADqekeTdaj4lBP+rFyWa8/zc5fP3YMcfjy9fkdbtJ4vb0SXR2sRP+BxBltc5Ti5dvAAAAAElFTkSuQmCC" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="/vpaccount" class="d-block"> ID / '.$id.'</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="/vpaccount" class="nav-link active">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <p>Dashboard</p>
            </a>
          </li>

		<li class="nav-item has-treeview">
            <a href="?vend=account" class="nav-link ">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My Account
              </p>
            </a>
		</li>
';
if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,"resell") == "yes" && strtolower($kyc_data[0]->enable) == "yes"){
	echo'
		<li class="nav-item has-treeview">
            <a href="?vend=kyc" class="nav-link ">
              <i class="fa fa-check" aria-hidden="true"></i>
              <p>
                Verify Account
              </p>
            </a>
		</li>
		';
		
}
if(vp_option_array($option_array,"setairtime") == "checked"){
	echo'
          <li class="nav-item has-treeview">
            <a href="?vend=airtime" class="nav-link ">
              <i class="nav-icon fas fa-mobile"></i>
              <p>
                Airtime Top Up
              </p>
            </a>
          </li>
		  ';
}
if(vp_option_array($option_array,"setdata") == "checked"){
	echo'
          <li class="nav-item has-treeview">
            <a href="?vend=data" class="nav-link ">
              <i class="nav-icon fas fa-mobile"></i>
              <p>
                Data Bundle
              </p>
            </a>
          </li>
		  ';
}

if(vp_option_array($option_array,"betcontrol") == "checked"){
	echo'
          <li class="nav-item has-treeview">
            <a href="?vend=bet" class="nav-link ">
              <i class="nav-icon fas fa-mobile"></i>
              <p>
                Bet Funding
              </p>
            </a>
          </li>
		  ';
}

if(vp_option_array($option_array,"datascontrol") == "checked"  && vp_option_array($option_array,"resell") == "yes"){
	echo'
          <li class="nav-item has-treeview">
            <a href="?vend=datacard" class="nav-link ">
              <i class="nav-icon fas fa-mobile"></i>
              <p>
                Data Card
              </p>
            </a>
          </li>
		  ';
}
		  if(is_plugin_active("vpsms/vpsms.php") && vp_option_array($option_array,"smscontrol") == "checked" && vp_option_array($option_array,"resell") == "yes"){
			  echo'
          <li class="nav-item has-treeview">
            <a href="?vend=sms" class="nav-link ">
              <i class="nav-icon fas fa-envelope"></i>
              <p>Bulk SMS</p>
            </a>
          </li>
		  ';
		  }
		  if(is_plugin_active("vpepin/vpepin.php") && vp_option_array($option_array,"epinscontrol") == "checked" && vp_option_array($option_array,"resell") == "yes"){ 
		  echo'
         <li class="nav-item">
            <a href="?vend=epins" class="nav-link ">
              <i class="fas fa-graduation-cap nav-icon"></i>
              <p>E-Pin</p>
            </a>
          </li>
		  ';
		  }
		  if(is_plugin_active("vpcards/vpcards.php") && vp_option_array($option_array,"cardscontrol") == "checked" && vp_option_array($option_array,"resell") == "yes"){ 
		  echo'
         <li class="nav-item">
            <a href="?vend=cards" class="nav-link ">
              <i class="fa fa-barcode" aria-hidden="true"></i>
              <p>E-Card</p>
            </a>
          </li>
		  ';
		  }
		  if(is_plugin_active("bcmv/bcmv.php")){
			  if(vp_option_array($option_array,"setcable") == "checked"){
			  echo'

          <li class="nav-item">
            <a href="?vend=cable" class="nav-link ">
              <i class="fas fa-tv nav-icon"></i>
              <p>Cable TV</p>
            </a>
          </li>
		  ';
			  }
		if(vp_option_array($option_array,"setbill") == "checked"){
			  echo' 
          <li class="nav-item">
            <a href="?vend=bill" class="nav-link ">
              <i class="fas fa-bolt nav-icon"></i>
              <p>Electricity Bill</p>
            </a>
          </li>';
			 }
		  
		  }
		 if(is_plugin_active("vprest/vprest.php") && vp_option_array($option_array,"resell") == "yes"){
			 if(vp_option_array($option_array,"allow_crypto") == "yes"){
			  
			  echo'
          <li class="nav-item">
            <a href="?vend=crypto" class="nav-link ">
              <i class="fab fa-bitcoin"></i>
              <p>Crypto Coins</p>
            </a>
          </li>
		  ';
			  }
		if(vp_option_array($option_array,"allow_cards") == "yes"){
			  echo' 
          <li class="nav-item">
            <a href="?vend=gift-card" class="nav-link ">
              <i class="fab fa-ebay"></i>
              <p>Gift Cards</p>
            </a>
          </li>';
			 }
		  
		  }
		  
		  if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,"resell") == "yes"){
			  echo'
          <li class="nav-item">
            <a href="?vend=upgrade" class="nav-link ">
             <i class="fa fa-line-chart" aria-hidden="true"></i>
              <p>Upgrade Levels</p>
            </a>
          </li>
		  ';
		  }
		  if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,"resell") == "yes" && isset($level)){
			  echo'
          <li class="nav-item">
            <a href="?vend=pricing" class="nav-link ">
              <i class="fa fa-tags" aria-hidden="true"></i>
              <p>Pricing</p>
            </a>
          </li>
		  ';
		  }
		  if(is_plugin_active("vprest/vprest.php")  && vp_option_array($option_array,"resell") == "yes" && isset($level) && strtolower($level[0]->developer) == "yes"){
			  echo'
		 <li class="nav-item">
            <a href="?vend=developer" class="nav-link ">
              <i class="fa fa-cogs" aria-hidden="true"></i>
              <p>Developers API</p>
            </a>
          </li>
		  ';
		  }
		  
		echo'
          <li class="nav-item">
            <a href="?vend=history&for=wallet" class="nav-link ">
              <i class="fas fa-file-alt nav-icon"></i>
              <p>Histories</p>
            </a>
          </li>
		  
		  <li class="nav-item">
            <a href="?vend=wallet" class="nav-link ">
            <i class="fas fa-credit-card nav-icon"></i> 
			<p>Fund Wallet</p>
            <!-- <span class="float-right text-muted text-sm">Share Money</span> -->
          </a>
          </li>';
		  
		if(strtolower(vp_option_array($option_array,"allow_withdrawal")) == "yes" && strtolower($myplan) != strtolower("customer")){
			  echo'
		  <li class="nav-item">
           <a href="?vend=withdraw" class="nav-link ">
			<i class="fa fa-share-square-o" aria-hidden="true"></i>
			<p>Withdraw Funds</p>
            <!-- <span class="float-right text-muted text-sm">Share Money</span> -->
          </a>
          </li>';
		  
		  }
		 
      if(vp_option_array($option_array,"wallet_to_wallet") == "yes" && isset($level) ){
        if( $level[0]->transfer == "yes"){
			  echo'
		  <li class="nav-item">
           <a href="?vend=transfer" class="nav-link ">
			<i class="fa fa-paper-plane" aria-hidden="true"></i>
			<p>Transfer Funds</p>
            <!-- <span class="float-right text-muted text-sm">Share Money</span> -->
          </a>
          </li>';
        }
		  
		  }
		  
		if(is_plugin_active("vpmlm/vpmlm.php")  && vp_option_array($option_array,'mlm') == "yes"){
			  echo'
		  <li class="nav-item this-nav" style="color:#fff;">
           <a class="nav-link">
			<i class="fas fa-users-cog"></i>
			<p>Referal System</p>
			<span class="float-right text-muted text-sm"><i class="fas fa-angle-right ar"></i> <i class="fas fa-angle-down ad"></i></span>
			
          </a>
          </li>
		  <li class="nav-item ref123">
           <a href="?vend=referral-details" class="nav-link ">
			<i class="fas fa-info-circle"></i>
			<p>Referal Info</p>
			
          </a>
          </li>
		  <li class="nav-item ref123">
           <a href="?vend=referrals" class="nav-link ">
			<i class="fas fa-users"></i>
			<p>My Referals</p>
			
          </a>
          </li>
		  
		  <script>
		  jQuery(".ref123").hide();
		  jQuery(".ar").show();
			jQuery(".ad").hide();
		
		jQuery(".this-nav").on("click", function () {
	
			  jQuery(".ref123").toggle();
			  jQuery(".ar").toggle();
			  jQuery(".ad").toggle();
		  
		});
		  
		  
		  </script>
		  
		  ';
		  
		  }
		  
		  echo'
          <!--<li class="nav-item">-->
        <!-- <a href="https://www.samicsub.com/user/cug" class="nav-link ">-->
          <!--   <i class="fas fa-users nav-icon"></i>-->
          <!--   <p>MTN Closed User Group</p>-->
          <!-- </a>-->
          <!--<li class="nav-item">-->
        <!--   <a href="https://www.samicsub.com/user/products" class="nav-link ">-->
          <!--   <i class="fas fa-shopping-cart nav-icon"></i>-->
          <!--   <p>Products</p>-->
          <!-- </a>-->
          <!--</li>-->
          <!-- <li class="nav-item">-->
        <!-- <a href="https://www.samicsub.com/user/service_provider" class="nav-link ">-->
          <!--   <i class="fas fa-tv nav-icon"></i>-->
          <!--   <p>Service Provider</p>-->
          <!-- </a>-->
          <!--</li>-->
          <li class="nav-item">
           <a class="nav-link" href="';
		  
$red = vp_getoption("vp_redirect");
if(strtolower($red) == "false"){
echo wp_logout_url(get_permalink());

}
else{
$link = get_site_url().'/'.$red;

echo wp_logout_url($link);
}


		   echo'">
              <i class="fas fa-sign-out-alt nav-icon"></i>
              <p>Logout</p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
    <!-- /.content-header -->

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    

    <div class="content-wrapper" style="min-height: 549.812px;">
                <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            <section class="content">
			';
if((isset($_GET["vend"]) && $_GET["vend"]!="airtime"  && $_GET["vend"]!="bet"   && $_GET["vend"]!="datacard"  && $_GET["vend"]!="for" && $_GET["vend"]!="kyc"  && $_GET["vend"]!="pricing" && $_GET["vend"]!="referral-details" && $_GET["vend"]!="developer"  && $_GET["vend"]!="cards" && $_GET["vend"]!="referrals" && $_GET["vend"]!="gift-card" && $_GET["vend"]!="crypto"  && $_GET["vend"]!="transfer" && $_GET["vend"]!="sms" && $_GET["vend"]!="epins" && $_GET["vend"]!="withdraw" && $_GET["vend"]!="wallet" && $_GET["vend"]!="upgrade" && $_GET["vend"]!="data" && $_GET["vend"]!="cable" && $_GET["vend"]!="bill" && $_GET["vend"]!="history" && $_GET["vend"]!="account")  || !isset($_GET["vend"])){
		
if(vp_option_array($option_array,"show_notify") == "yes"){
	$meq = preg_replace('/\n|\t|\s|\r/'," ",$mess);
	?>
	<script>
	jQuery(window).on("load",function(){
		var word = "<?php echo $meq;?>";
swal({
  title: "NOTIFICATION",
  text: "<?php echo $meq;?>",
  icon: "info",
  button: "Okay"
});
	});
</script>
	<?php
}
	
	?>			



<?php


if(!isset($_COOKIE["switchto"])) {
$user_is = get_userdata($id)->user_login;
}
 else {
 $user_is = $_COOKIE["switchto"];
 
}

			echo'
		
			
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-8 align-self-center">
                            <h4 class="text-themecolor"> Welcome To Your Dashboard,  '.get_userdata($id)->user_login.'</h4>
						<br>
						';
	if(isset($_COOKIE["switchto"]) && is_numeric($_COOKIE["switchto"]) && !empty($_COOKIE["switchto"])) {
?>		
<div class="alert alert-primary mb-2" role="alert">
<b>[<?php echo $_COOKIE["switchto"];?>]User Switch On:</b><br>
You are an admin and have switch in as another user. Kindly <b><a href="?vend=switch">click me</a></b> to go back to be an admin.
<br>
<b>You might need to refresh the page after being switched.</b>
</div>
	<?php

	
	}

			echo'			
                        </div>
                    </div>
                    <!-- Small boxes (Stat box) -->
					<div class="notify">
    <marquee>  <h4 class="text-themecolor">'.$mess.'</h4> </marquee>
						<br>
									
                        </div>
                    <div class="row col">
					';

	
					
          if(vp_getoption("charge_method") == "fixed"){
            $chargef =  "₦".floatval(vp_getoption("charge_back"));
            }
            else{
             $chargef =  floatval(vp_getoption("charge_back"))."%";
            }					
					
					?>
				<style>
/* Shoutout to Maite Rosalie for the gold svg gradient which can be seen here below. */

/* https://codepen.io/maiterosalie/pen/ppRRLV?q=gold+gradient&limit=all&type=type-pens */

.Wrap {
  display: flex;
  justify-content: center;
  align-items: center;
   background: #f4f6f9;
  font-family: 'Roboto', sans-serif;
  font-weight: 400;
}

.Wrap .Base {
  background: #ccc;
  height: 100%;
  width: 100%;
  border-radius: 15px;
}

.Wrap .Inner-wrap {
  background-color: #0c0014;
background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25' viewBox='0 0 1600 800'%3E%3Cg %3E%3Cpolygon fill='%230d1838' points='1600%2C160 0%2C460 0%2C350 1600%2C50'/%3E%3Cpolygon fill='%230e315d' points='1600%2C260 0%2C560 0%2C450 1600%2C150'/%3E%3Cpolygon fill='%230f4981' points='1600%2C360 0%2C660 0%2C550 1600%2C250'/%3E%3Cpolygon fill='%231062a6' points='1600%2C460 0%2C760 0%2C650 1600%2C350'/%3E%3Cpolygon fill='%23117aca' points='1600%2C800 0%2C800 0%2C750 1600%2C450'/%3E%3C/g%3E%3C/svg%3E");
  background-size: auto 147%;
  background-position: center;
  position: relative;
  height: 100%;
  width: 100%;
  border-radius: 13px;
  box-sizing: border-box;
  color: #fff;
}

.Wrap p {
  margin: 0;
  font-size: 2em;
}

/* Controls top right logo */

.Wrap .Logo {
  position: absolute;
  height: 80px;
  width: 80px;
  right: 0;
  top: 0;
  padding: inherit;
  fill: #117aca;
}

/* Controls chip icon */

.Wrap .Chip {
  height: 40px;
  margin: 20px 0 25px 0;
}

.Wrap .gold path{
  fill: url(#gold-gradient);
}

.Wrap svg {
  display: block;
}

/* Controls name size */

.Wrap .Logo-name {
  transform: scale(.5);
  margin-left: -75px;
}

.Wrap .Card-number p {
  text-align: center;
}

.Wrap .Card-number {
  margin-top: -25px;
  display: flex;
  justify-content: center;
  color: rgba(255, 255, 255, 0.9);
}

.Wrap ul {
  padding: 0;
}

.Wrap ul li {
  list-style: none;
  float: left;
  margin: 0px 10px;
  font-size: 2.2em;
}

.Wrap #first-li {
  margin-left: 0;
}

.Wrap #last-li {
  margin-right: 0;
}

.Wrap .Expire {
  font-size: .75em;
  text-align: center;
}

.Wrap .Expire h4 {
  font-weight: 400;
  color: #aaa;
  margin: 0;
/*   word-spacing: 9999999px; */
  text-transform: uppercase;
}

.Expire p {
  font-size: 1.55em;
  color: rgba(255, 255, 255, 0.9);
}

.Wrap .Name h3 {
  position: relative;
  bottom: 0;
  text-align:center;
  text-transform: uppercase;
  font-weight: 400;
  font-size: 1.35em;
  color: rgba(255, 255, 255, 0.85);
}

.Wrap .Visa {
  width: 115px;
  position: relative;
  right: 0;
}
.justify-content-end{
  text-align:right;
}
</style>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400" rel="stylesheet">


<div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a
                      class="nav-link active"
                      data-bs-toggle="tab"
                      href="#home"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name2;?></span></a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#profile"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name1;?></span></a
                    >
                  </li>
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      data-bs-toggle="tab"
                      href="#messages"
                      role="tab"
                      ><span class="hidden-sm-up"></span>
                      <span class="hidden-xs-down"><?php echo $bank_name;?></span></a
                    >
                  </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content tabcontent-border">

                  <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->
  <div class="Wrap mb-2 cdebit-card   position-relative">
 <!-- <div class="position-absolute bg bg-primary w-100 h-100">

  

</div>-->
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name2;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php echo $account_number2;?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name2;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


                    <!------------------>
                    </div>
                  </div>
                  <div class="tab-pane" id="profile" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->

                    <div class="Wrap mb-2 cdebit-card   position-relative">
 <!-- <div class="position-absolute bg bg-primary w-100 h-100">

  

</div>-->
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name1;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php echo $account_number1;?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name1;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>




                    <!------------------>
                    </div>
                  </div>
                  <div class="tab-pane" id="messages" role="tabpanel">
                    <div class="p-md-20">

                    <!-------------CONTENT----------->


                    <div class="Wrap mb-2 cdebit-card   position-relative">
  <div class="Base">
    <div class="Inner-wrap">

<div class=" container text-white p-4 roundeds">

<div class="row mb-3">
<div class="col Logo-name fs-3">
<?php echo $bank_name;?>
</div>
</div>

<div class="row mb-3">
<div class="col card-number text-center">
<p><?php echo $account_number;?></p>
</div>
</div>

<div class="row mb-3">
<div class="col Name white  text-center">
<p><?php echo $account_name;?></p>
</div>
</div>


<div class="row">
<div class="col fs-5">
VISA
</div>
<div class="col flex justify-content-end  fs-5">
<?php echo $chargef;?> Charge Applied
</div>
</div>

</div>

</div>
</div>
</div>


                    <!------------------>
                    </div>
                  </div>
                </div>
              </div>
              
					
					<?php
					echo'
                        <div class="col-lg col">
                            <!-- small box -->
                            <div class="small-box bg-maroon bg-darken-5">
                                <div class="inner">
                                    <h3>₦';
									if(!empty($bal)){
echo floatval(round(floatval($bal),2));
}
else{
echo "0.00";
}
									
									echo'</h3>
                                    <p>Wallet Balance</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <a href="?vend=wallet" class="small-box-footer">Fund Wallet <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
						
						';
						
				if(is_plugin_active("vpmlm/vpmlm.php")  && vp_option_array($option_array,'mlm') == "yes"){
							echo'					
                        <!-- ./col -->
                        <div class="col-lg col">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>'.$total_refered.'</h3>

                                    <p>Total Refered</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <a href="#refstats" class="small-box-footer">Referral Stats <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
						';
						
						if(strtolower(vp_getoption("totcons")) == "yes"){
						echo'
						<!-- ./col -->
                        <div class="col-lg col">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>₦'.$cur_suc_trans_amt.'</h3>

                                    <p>Total Transaction Sum</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-chart-line"></i>
                                </div>
                                <a href="#bonusstats" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
						';
						}
				}
						
						echo'
                      
                        <!-- ./col -->
                        <div class="col-lg col">
                            <!-- small box -->
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>'.$myplan.'</h3>

                                    <p>Current Plan</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-coffee"></i>
                                </div>
                                <a href="?vend=upgrade" class="small-box-footer">Upgrade Plan <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                                        </div>

                    <br>
					<div class="services">
    ';
					if(vp_option_array($option_array,"setairtime") == "checked"){
						echo'
						<a href="?vend=airtime">
						<div class="airtime serve">
						Buy Airtime
						</div>
						</a>
						';
					}
					if(vp_option_array($option_array,"setdata") == "checked"){
						echo'
						<a href="?vend=data">
						<div class="serve data">
						Buy Data
						</div>
						</a>
						
						';
					}
          if(vp_option_array($option_array,"betcontrol") == "checked"){
						echo'
						<a href="?vend=bet">
						<div class="serve bet">
						Bet Funding
						</div>
						</a>
						
						';
					}
					if(is_plugin_active("bcmv/bcmv.php")){
							 
						if(vp_option_array($option_array,"setcable") == "checked"){	 
							 echo'
						<a href="?vend=cable">
						<div class="serve cable">
						Buy Cable
						</div>
						</a>';
						}
						if(vp_option_array($option_array,"setbill") == "checked"){
						echo'
						<a href="?vend=bill">
						<div class="serve bill">
						Pay Bill
						</div>
						</a>
						';
						}
					}
					if(is_plugin_active("vpepin/vpepin.php") && vp_option_array($option_array,"resell") == "yes"){
							 
						if(vp_option_array($option_array,"epinscontrol") == "checked"){	 
							 echo'
						<a href="?vend=epins">
						<div class="serve epins">
						Buy Epins
						</div>
						</a>';
						}
					}
					if(is_plugin_active("vpcards/vpcards.php") && vp_option_array($option_array,"resell") == "yes"){
							 
						if(vp_option_array($option_array,"cardscontrol") == "checked"){	 
							 echo'
						<a href="?vend=cards">
						<div class="serve cards">
						Buy Ecards
						</div>
						</a>';
						}
					}
					if(is_plugin_active("vpsms/vpsms.php") && vp_option_array($option_array,"resell") == "yes"){
							 
						if(vp_option_array($option_array,"smscontrol") == "checked"){	 
							 echo'
						<a href="?vend=sms">
						<div class="serve sms">
						Send Sms
						</div>
						</a>';
						}
					}
						 echo '
    
					</div>
						<br>
				
					';
					
		echo'
					
					<br>
				
                    <div class="row">
					
					';
					

	include('sections/statistics.php');	
					echo '
							
                          <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Announcements</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                   '.$mess.'
								</div>
                            </div>
                        </div>
                    </div>
                    
                </div><!-- /.container-fluid -->
            		
		';
}
			
			
			
			
include('sections/history.php');		
include(ABSPATH. 'wp-content/plugins/vtupress/template/classic/ref-info.html');		
include(ABSPATH. 'wp-content/plugins/vtupress/template/classic/sections/kyc.php');		
include('sections/pricing.php');		
include('sections/airtime.php');		
include('sections/developer.php');		
include('sections/data.php');		
include('sections/bet.php');		
include('sections/cable-bills.php');		
include('sections/account.php');		
include('sections/wallet.php');		
include('sections/transfer.php');		
include('sections/upgrade.php');		
include('sections/withdraw-referral.php');		
include('sections/crypto-gift.php');
		
do_action("user_feature");
		

		


		
		
		echo '
			
	
          </section>
            <!-- /.content -->

    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>

<!-- /.content -->
</div>
<!-- /.content-wrapper --->

<footer class="main-footer">
    <strong>Copyright ©'; echo date("Y").' <a href="/">';bloginfo('name'); echo'</a>.</strong>
</footer>



<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-light">
    <!-- Control sidebar content goes here -->
<div class="p-3 control-sidebar-content"><h5>Customize AdminLTE</h5><hr class="mb-2"><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>No Navbar border</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Body small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Navbar small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Footer small text</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav flat style</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav legacy style</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav compact</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Sidebar nav child indent</span></div><div class="mb-1"><input type="checkbox" value="1" class="mr-1"><span>Main Sidebar disable hover/focus auto expand</span></div><div class="mb-4"><input type="checkbox" value="1" class="mr-1"><span>Brand small text</span></div><h6>Navbar Variants</h6><div class="d-flex"><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-secondary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-cyan elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-light elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-white elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div></div><h6>Accent Color Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Dark Sidebar Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Light Sidebar Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-navy elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-fuchsia elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-maroon elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-lime elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-olive elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div></div><h6>Brand Logo Variants</h6><div class="d-flex"></div><div class="d-flex flex-wrap mb-3"><div class="bg-primary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-secondary elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-info elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-success elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-danger elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-indigo elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-purple elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-pink elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-teal elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-cyan elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray-dark elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-gray elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-light elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-warning elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-white elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><div class="bg-orange elevation-2" style="width: 40px; height: 20px; border-radius: 25px; margin-right: 10px; margin-bottom: 10px; opacity: 0.8; cursor: pointer;"></div><a href="javascript:void(0)">clear</a></div></div></aside>
<!-- /.control-sidebar -->
<div id="sidebar-overlay"></div></div>
<!--
<!-- Bootstrap 4 -->
<!--<script src="https://www.samicsub.com/new/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://www.samicsub.com/new/toggle/js/bootstrap-toggle.min.js"></script>
<!--<script src="./SamicSub _ Users Dashboard_files/popper.min.js.download"></script>
<script src="./SamicSub _ Users Dashboard_files/select2.min.js.download"></script>-->
<!--<script src="https://www.samicsub.com/new/bootstrap-multiselect.min.js"></script>
<!-- AdminLTE App -->
<!--<script src="https://www.samicsub.com/new/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="https://www.samicsub.com/new/dist/js/demo.js"></script>
<script src="https://www.samicsub.com/datatables/clipboard.js"></script>

<!---Datatables -->
<!---Datatables -->
<!--<script src="https://www.samicsub.com/datatables/datatables.js"></script>
<script src="https://www.samicsub.com/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="https://www.samicsub.com/datatables/js/dataTables.responsive.min.js"></script>
<script src="https://www.samicsub.com/datatables/js/responsive.bootstrap4.min.js"></script>
<!--<script type="text/javascript" src="./SamicSub _ Users Dashboard_files/dataTables.checkboxes.min.js.download"></script>-->
<!--<script src="https://www.samicsub.com/datatables/samicDataTablesControl.js"></script>-->

<script>

jQuery("body").addClass("sidebar-mini");
    jQuery(window).on("load", function() {
        jQuery("#cover-spin").hide()
    });
    var hostname = jQuery(location).attr("host");
    var web_url = jQuery("#web_url").val();
</script>



 <script>
	
	jQuery(".navbar-nav").click(function(){
	if(!jQuery("body").hasClass("sidebar-open")){
	jQuery("body").addClass("sidebar-open");
	jQuery("body").removeClass("sidebar-collapse");
	}
	else{
	jQuery("body").removeClass("sidebar-open");
	jQuery("body").addClass("sidebar-collapse");
	}
	});
	
	jQuery("#sidebar-overlay").click(function(){
	jQuery("body").removeClass("sidebar-open");
	});
	
	
	
	
	
function printContent(areaID){
printJS({printable: areaID, type: "html", css: "'. esc_url(plugins_url("vtupress/css/bootstrap.min.css")).'"});
}
</script>	
		
		';


include_once(ABSPATH."wp-content/plugins/vtupress/do_not_tamper.php");
		?>