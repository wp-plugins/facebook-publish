<?php
	
add_action("init" , "fp_handle_facebook");

function fp_handle_facebook() {

	global $fp_facebook;

	global $fp_settings;

	global $hybrid_config;

	global $fp_hybridauth;

	if( isset($_GET["fb_publish_validate"]) ) {

		try {

			$fp_hybridauth 	= new Hybrid_Auth( $hybrid_config );

			$fp_hybridauth->authenticate( "facebook" );

			update_option( "session_data", $fp_hybridauth->getSessionData() );

			wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api&fbauth=success") );
		
		}
		catch(Exception $e) {
		
			//echo $e->getMessage();

		} 


	}
	else if(isset($_REQUEST['fp_logout_facebook'])) {

		$facebook_adapter = $fp_hybridauth->getAdapter("facebook");

		$facebook_adapter->logout();

		wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api&fbauth=loggedout") );
	
	}

	if( isset($_GET["page"]) 
		&& ($_GET["page"] == "facebook-publish") 
		&& isset($_GET["fbauth"]) 
		&& $_GET["fbauth"] == "success" 
		) {

			cdlc_show_notification("Facebook Authorization Successfull" , "success");
			
	}	
	if( isset($_GET["page"]) 
		&& ($_GET["page"] == "facebook-publish") 
		&& isset($_GET["fbauth"]) 
		&& $_GET["fbauth"] == "loggedout" 
		) {

			cdlc_show_notification("You have been unlinked with Facebook" , "success");
			
	}
	if( isset($_GET["page"]) 
		&& ($_GET["page"] == "facebook-publish") 
		&& isset($_GET["fbauth"]) 
		&& $_GET["fbauth"] == "failed" 
		) {

			cdlc_show_notification("Facebook Authorization Failed" , "error");
			
	}

}
	

?>