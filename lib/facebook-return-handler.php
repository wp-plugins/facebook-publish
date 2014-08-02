<?php
	
add_action("init" , "fp_handle_facebook");

function fp_handle_facebook() {

	global $fp_facebook;

	global $fp_settings;

	global $hybrid_config;

	global $fp_hybridauth;

	if( isset($_GET["fb_publish_validate"]) ) {

		try {

			//$fp_hybridauth = new Hybrid_Auth( $hybrid_config );
			
			$fp_facebook = $fp_hybridauth->authenticate( "facebook" );

			$fp_settings['session_data'] = $fp_hybridauth->getSessionData();
			
			$response 	= $fp_facebook->api()->api("/me/accounts");
			
			$pages 		= $response["data"];

			if(count($pages) > 0){

				$fp_settings['pages'] = array( "own"=> array("id" => "own" , "name" => "Own Timeline")  );
				$fp_settings["global_pages"] = array("own");
				foreach($pages as $page) {

					$fp_settings['pages'][$page['id']] = $page;
					$fp_settings["global_pages"][] = $page['id']; 	
				}
			
			}



			update_option("fp_settings" , $fp_settings);

			wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api&fbauth=success") );
		
		}
		catch(Exception $e) {
			
			cdlc_show_notification("Please enter  <b>Application ID</b> and <b>Secret Key</b>" , "error");
			wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api") );
			//echo $e->getMessage();

		} 


	}
	else if(isset($_REQUEST['fp_logout_facebook'])) {

		$facebook_adapter = $fp_hybridauth->getAdapter("facebook");

		$facebook_adapter->logout();
		
		$fp_settings['session_data'] = $fp_hybridauth->getSessionData();

		update_option("fp_settings" , $fp_settings);

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