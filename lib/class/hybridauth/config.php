<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------
global $fp_settings;
//pre($fp_settings);

return 
	array(
		"base_url" => site_url( "/wp-content/plugins/facebook-publish/lib/class/hybridauth/" ), 

		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"AOL"  => array ( 
				"enabled" => true 
			),

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ), 
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id"		=> isset($fp_settings["ptf_app_id"])?$fp_settings["ptf_app_id"]:"",
									 "secret" 	=> isset($fp_settings["ptf_api_key"])?$fp_settings["ptf_api_key"]:"" ),
				"scope" => "email, user_about_me, publish_stream"  
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			// windows live
			"Live" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),

			"MySpace" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => "logger.txt",
	);
