<?php
		
	global $fp_settings;

	if(!$fp_settings) {

		$fp_settings = get_option("fp_settings");

		//remove all old settings if outdated version
		if(!isset($fp_settings["version"])) {
			$fp_settings = array();			
		}

		$defaults = array(
							'version' 				=> $fp_version,
							'ptf_app_id' 			=> "",
							'ptf_api_key' 			=> "",
							'fp_post_types' 		=> array("post" , "page"),
							'fp_post_cat' 			=> array("Uncategorized"),
							'fp_all_posts' 			=> "0",
							'show_auth_btn' 		=> true,
							'msg_body'				=> "My new post on {SITE_NAME}", 
							'post_featured_image'	=> "yes", 
							'post_schedule' 		=> "no",
							'queue' 				=> array(),
							'session_data' 			=> "",
							'pages' 				=> array(), 
							'global_pages' 			=> array( "own"=> array("id" => "own" , "name" => "Own Timeline")  ), 
						);

		$fp_settings = wp_parse_args( $fp_settings, $defaults );

	}

	update_option( "fp_settings", $fp_settings );

	//exit;

?>