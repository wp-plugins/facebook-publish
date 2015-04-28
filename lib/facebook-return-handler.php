<?phpuse Facebook\FacebookRequest;	add_action("init" , "fp_handle_facebook");function fp_handle_facebook() {	global $fp_facebook;	global $fp_settings;	global $fp_cauth;	if( isset($_GET["fb_publish_validate"]) ) {		ob_start();			try {									$fp_facebook = $fp_cauth->authenticate( "Facebook" );			$fp_settings['session_data'] = $fp_cauth->get_session_data();						//$response 	= $fp_facebook->api()->api("/me/accounts");						$request = new FacebookRequest(										  $fp_facebook->api(),										  'GET',										  '/me/accounts'										);			$response = $request->execute();			$graphObject = $response->getGraphObject();			$data = $graphObject->asArray();			$pages 		= $data["data"];			if(count($pages) > 0){				$fp_settings['pages'] = array( "own"=> array("id" => "own" , "name" => "Own Timeline")  );				$fp_settings["global_pages"] = array("own");				foreach($pages as $page) {					$fp_settings['pages'][$page->id]["name"] = $page->name;					$fp_settings['pages'][$page->id]["id"] = $page->id;					$fp_settings['pages'][$page->id]["access_token"] = $page->access_token;					$fp_settings['pages'][$page->id]["category"] = $page->category;					$fp_settings['pages'][$page->id]["perms"] = $page->perms;					$fp_settings["global_pages"][] = $page->id; 					}						}			update_option("fp_settings" , $fp_settings);			wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api&fbauth=success") );				}		catch(Exception $e) {						cdlc_show_notification("Please enter  <b>Application ID</b> and <b>Secret Key</b>" , "error");			wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api") );			//echo $e->getMessage();		} 	}	else if(isset($_REQUEST['fp_logout_facebook'])) {		$facebook_adapter = $fp_cauth->get_provider_adapter("Facebook");		//$facebook_adapter->logout();		$fp_cauth->logout_all_providers();		$fp_settings['session_data'] = $fp_cauth->get_session_data();		update_option("fp_settings" , $fp_settings);		wp_redirect( site_url("/wp-admin/admin.php?page=facebook-publish&tab=api&fbauth=loggedout") );		}	if( isset($_GET["page"]) 		&& ($_GET["page"] == "facebook-publish") 		&& isset($_GET["fbauth"]) 		&& $_GET["fbauth"] == "success" 		) {			cdlc_show_notification("Facebook Authorization Successfull" , "success");				}		if( isset($_GET["page"]) 		&& ($_GET["page"] == "facebook-publish") 		&& isset($_GET["fbauth"]) 		&& $_GET["fbauth"] == "loggedout" 		) {			cdlc_show_notification("You have been unlinked with Facebook" , "success");				}	if( isset($_GET["page"]) 		&& ($_GET["page"] == "facebook-publish") 		&& isset($_GET["fbauth"]) 		&& $_GET["fbauth"] == "failed" 		) {			cdlc_show_notification("Facebook Authorization Failed" , "error");				}}	?>