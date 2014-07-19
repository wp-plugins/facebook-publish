<?php

require "facebook-return-handler.php"; 
require "new-post-update-status.php"; 

if(!isset($_SESSION)) {

	session_start();
}

function pre($arr) {

	echo "<pre>";
	print_r($arr);
	echo "</pre>";

}


function cdlc_show_notification($msg="Success" , $type = "success") {

	$_SESSION["fp_msg"] = $msg;
	
	if($type == "success"){
		
		add_action('admin_notices', 'cdlc_success_admin_notice');
		
	}
	else {

		add_action('admin_notices', 'cdlc_error_admin_notice');

	}

}

function cdlc_success_admin_notice(){

	global $fp_settings;

    global $pagenow;
	
	    
    echo '<div class="updated">

         <p>'.$_SESSION["fp_msg"].'</p>

     </div>';
	    
}

function cdlc_error_admin_notice(){

	global $fp_settings;

    global $pagenow;
	
	    
    echo '<div class="error">

         <p>'.$_SESSION["fp_msg"].'</p>

     </div>';
	    
}

