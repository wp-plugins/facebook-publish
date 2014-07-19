<?php
		
	global $fp_settings;

	if(!$fp_settings) {

		$fp_settings = get_option("fp_settings");

		$defaults = array(
							'fp_post_types' 		=> array("post" , "page"),
							'fp_post_cat' 			=> array("Uncategorized"),
							'fp_all_posts' 			=> "1",
							'show_auth_btn' 		=> true,
							'msg_body'				=> "My new Post on ".get_bloginfo("name"), 
							'post_featured_image'	=> "yes", 
						);

		$fp_settings = wp_parse_args( $fp_settings, $defaults );

	}

	update_option( "fp_settings", $fp_settings );

	//exit;

?>