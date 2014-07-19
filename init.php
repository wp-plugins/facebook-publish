<?php
/*
Plugin Name: Facebook Publish
Plugin URI: http://codeholic.in/
Description: Automatically posts new articles to Facebook. Simple one click Integration. 
Version: 0.9
Author: Pramod Jodhani
Author URI: http://codeholic.in/
*/

define('FP_SETTINGS_SLUG' , "facebook-publish");

$fp_settings = get_option("fp_settings");

$fp_facebook = "";

$hybrid_config = dirname(__FILE__) .'/lib/class/hybridauth/config.php';

require_once( "lib/class/hybridauth/Hybrid/Auth.php" );

$fp_hybridauth = new Hybrid_Auth( $hybrid_config );

if( $fp_session_data = get_option("fp_session_data") ) {

	$fp_hybridauth->restoreSessionData( $fp_session_data );

}


function fp_activate() {

    require "fp_activate_plugin.php";
}
register_activation_hook( __FILE__, 'fp_activate' );

require_once("lib/functions.php");

require_once("lib/admin/init.php");



?>