<?php

add_action('new_to_publish', 'fp_publish_post');
add_action('draft_to_publish', 'fp_publish_post');
add_action('pending_to_publish', 'fp_publish_post');
add_action('auto-draft_to_publish', 'fp_publish_post');
//add_action('publish_post', 'fp_publish_post'); 


function fp_publish_post($post) {

	global $fp_hybridauth;
	global $fp_settings;
	$post_id = $post->ID;

	if($fp_hybridauth->isConnectedWith("facebook")) {

		if(isset($fp_settings['fp_post_all']) && $fp_settings['fp_post_all'] == "0") {

			$post_type 			= get_post_type($post_id);
			
			$post_categories 	= wp_get_post_categories( $post->ID );

			$common_categories 	= array_intersect($post_categories , $fp_settings['fp_post_cat'] );

			if( in_array($post_type, $fp_settings['fp_post_types']) || count($common_categories) ) {

				//fp_post_to_fb($post_id);
				//echo "it will be posted";
			}
			else {
				
				//fp_post_to_fb($post_id);
				//echo "it will not be posted";
				//exit;
			}

		}
		else {
				fp_post_to_fb($post_id);
				//echo "it will be posted";
		}

	}
	else {

		cdlc_show_notification("Post coudln't be published on Facebook. " , "error");

	}

}


function fp_post_to_fb($post_id ) {

	global $fp_hybridauth;
	
	global $fp_settings;

	$facebook_adapter = $fp_hybridauth->getAdapter("facebook");

	$msg_body = $fp_settings['msg_body'];

	$message = $msg_body;

	$url = get_permalink( $post_id );

	$picture = array();

	$config =  array(
					"message" => $message, 
					"link" => $url,  
					); 

	if($fp_settings['post_featured_image']=="yes") {

		if(has_post_thumbnail( $post_id )) {

			$img = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large');

			$img = $img[0];

			$picture = array("picture" =>   $img);
			
			$config  = array_merge($config , $picture);
			
		}

	}

	$facebook_adapter->setUserStatus( $config );

	cdlc_show_notification("Post published on Facebook");


}

?>