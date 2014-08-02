<?php

require "facebook-return-handler.php"; 
require "new-post-update-status.php"; 

if(!isset($_SESSION)) {

	session_start();

}

if(isset($_SESSION['fp_msg'])) {
	cdlc_show_notification($_SESSION['fp_msg'] , $_SESSION['fp_msg_type'] );	
}

function pre($arr) {

	echo "<pre>";
	print_r($arr);
	echo "</pre>";

}


function cdlc_show_notification($msg="Success" , $type = "success") {

	$_SESSION["fp_msg"] = $msg;
	$_SESSION["fp_msg_type"] = $type;
	
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

         <p> <b>'.FP_PLUGIN_NAME.':</b>'.$_SESSION["fp_msg"].'</p>

     </div>';
	   
	unset($_SESSION["fp_msg"]);	
	unset($_SESSION["fp_msg_type"]);	
}

function cdlc_error_admin_notice(){

	global $fp_settings;

    global $pagenow;
	
	    
    echo '<div class="error">

         <p> <b>'.FP_PLUGIN_NAME.':</b> '.$_SESSION["fp_msg"].'</p>

    	 </div>';

 	unset($_SESSION["fp_msg"]); 
 	unset($_SESSION["fp_msg_type"]); 
}

function fp_print_fb_pages() {

	global $fp_settings;

	$checked = "";

	if(in_array( "own", $fp_settings["global_pages"])) {
		$checked = "checked";
	}
	
	//echo "<input $checked type='checkbox' value='own' name='global_pages[]' class='global_pages' id='global_pages_own'  ><label for='global_pages_own'> Own Timeline</label><br>";		
	
	 	foreach($fp_settings['pages'] as $page ) {

	 		$checked = (in_array( $page['id'] , $fp_settings["global_pages"]))? " checked " : "";
			
			echo '<input '.$checked.' type="checkbox" value="'.$page['id'].'" name="global_pages[]" class="global_pages" id="global_pages_'.$page['id'].'"  ><label for="global_pages_'.$page['id'].'"> '.$page['name'].' </label><br>';		
	
	 	}

}

