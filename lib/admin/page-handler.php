<?php


add_action("admin_menu" , "fp_settings");


function fp_settings() {

	$icon_url =  plugins_url( '/assets/fp_logo_small.png' , __FILE__ );
	add_menu_page( "Facebook Publish", "Facebook Publish", "manage_options", FP_SETTINGS_SLUG, "fp_settings_page" , $icon_url ); 
	
}

function fp_settings_page() {

	global $fp_settings;
	global $hybrid_config;
	global $fp_hybridauth;
	$icon_url =  plugins_url( '/assets/fp_logo.png' , __FILE__ );
	?>
	
	<div class="wrap">		
		
		<h2><img src="<?php echo $icon_url ?>" alt="" class='fp_logo'>Facebook Publish Settings </h2>

		<hr>

		<form method="post" id="fp_api_frm">
			
			<?php 

				/********************  Showing notification ********************/
				try{

					if(!$fp_hybridauth->isConnectedWith("facebook")) {

						echo "<span class='alert'>To learn how to obtain Facebook APP ID and APP KEY see <a href='http://codeholic.in/how-to-generate-a-facebook-app-id-and-secret-key/' target=_blank>this tutorial</a>. </span><br><br>";
						
						echo "<span class='alert'>Please Authorize Facebook Application. </span>";

						echo "<a id='fp_authorize_btn' class='button ' href='".site_url("/?fb_publish_validate=1")."' >Authorize Now</a>";

					}
					else {
						
						echo "<span class='alert'>You are authorized with facebook. &nbsp; </span>";
						
						echo "<a class='button ' href='".site_url("/?fp_logout_facebook=1")."' >Logout?</a>";

					}
				}
				catch(Exception $e) {
					pre($e);
				}
				/********************  Showing tabs ********************/

				$tab = "api";

				if(isset($_GET['tab']))
					$tab = $_GET['tab'];

				fp_admin_tabs($tab);

				/*******************  Tab content  ***********************/
				switch($tab) {

					case "api": 

						fp_facebook_api_settings();
					
					break;

					case "general":

						if(isset($fp_settings["show_auth_btn"])) {
						
							fp_general_settings();
						
						}
					
					break;

				}

		
			?>
			
		</form>
	
	</div> <!-- wrap-->

	<?php

	fp_authorize_btn_js(); //It will add a JS alert notifying user to save app id and secret key before authorizing with facebook 
}

/*--------------------------- Saving fields in options table -------------------------*/
add_action("init" , "fp_save_settings");

function fp_save_settings() {

	global $fp_settings;

	if(isset($_REQUEST["fp_api_settings"])) {

		$ptf_app_id	 = $_REQUEST["ptf_app_id"];

		$ptf_api_key = $_REQUEST["ptf_api_key"];

		if(!empty($ptf_app_id) && !empty($ptf_api_key)) {

			$fp_settings['ptf_app_id'] 		= $ptf_app_id;
				
			$fp_settings['ptf_api_key'] 	= $ptf_api_key;

			$fp_settings['show_auth_btn'] 	= true;


		}

	}

	if(isset($_REQUEST['fp_general_settings'])) {

		if(isset($_REQUEST['fp_all_posts'])) {

			$fp_settings["fp_all_posts"] = "1";

		}
		else {

			$fp_settings["fp_all_posts"] = "0";
			
		}

		
		if(isset($_REQUEST['fp_post_types'])) {

			$fp_post_types 	= $_REQUEST['fp_post_types'];

			$fp_settings["fp_post_types"] 	= $fp_post_types;
			
		}


		if(isset($_REQUEST['msg_body']) && $_REQUEST['msg_body']) {

			$msg_body = $_REQUEST['msg_body'];
			
			$fp_settings["msg_body"] = $msg_body;
		}

		if(isset($_REQUEST['post_featured_image']) && $_REQUEST['post_featured_image']) {

			$post_featured_image = $_REQUEST['post_featured_image'];
			
			$fp_settings["post_featured_image"] = $post_featured_image;
		}		

		if(isset($_REQUEST['post_schedule']) && $_REQUEST['post_schedule']) {

			$post_schedule = $_REQUEST['post_schedule'];
			
			$fp_settings["post_schedule"] = $post_schedule;
		}

		if(isset($_REQUEST['global_pages'])) {

			$global_pages = $_REQUEST['global_pages'];
			
			$fp_settings["global_pages"] = $global_pages;
		}


	} //isset fp_general_settings

	update_option( "fp_settings" , $fp_settings );

} //fp_save_settings


/*------------------- Tabs Function-----------------------*/
function fp_admin_tabs( $current = 'api' ) {

    $tabs = array( 'api' => 'API Settings', 'general' => 'General' );
    
    echo '<div id="icon-themes" class="icon32"><br></div>';
    
    echo '<h2 class="nav-tab-wrapper">';
    
    foreach( $tabs as $tab => $name ){
    
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
    
        echo "<a class='nav-tab$class' href='?page=facebook-publish&tab=$tab'>$name</a>";

    }
    
    echo '</h2>';
}



/*---------------------------- Show alert notification on dashboard--------------------------*/
function my_admin_notice(){

	global $fp_settings;

    global $pagenow;

    global $fp_hybridauth;
	
	$fp_hybridauth->restoreSessionData( $fp_settings['session_data'] );

	if( !$fp_hybridauth->isConnectedWith("facebook") ) {
	    
	         echo '<div class="update-nag">
	    
	             <p><b>Facebook Publish</b> will not work unless you <a href="?page=facebook-publish&tab=api">authorize the Facebook Application</a>. To learn how to obtain Facebook APP ID and APP KEY see <a href="http://codeholic.in/how-to-generate-a-facebook-app-id-and-secret-key/" target=_blank>this tutorial</a> </p>
	    
	         </div>';
	    
	}
}

add_action('admin_notices', 'my_admin_notice');
/*---------------------------- Show alert notification on dashboard--------------------------*/

/*Javascript code for api screen*/
function fp_authorize_btn_js() {
	global $fp_settings;
	if(empty($fp_settings['ptf_app_id']) && empty($fp_settings['ptf_api_key'])) {
		//show js code
		?>
			<script type="text/javascript">
				jQuery(document).ready(
				function() {

					jQuery("#fp_authorize_btn").click(
					function(e) {

						e.preventDefault();
						alert("Please save App ID and Application Key first.");
						return false;
					}
					);

				});
			</script>
		<?php
	}

}

/*---------------------------- Enqueue CSS --------------------------*/
function fp_wp_admin_style() {

		$url 		=  plugins_url( '/assets/fp-style.css' , __FILE__ );
		$asset_url 	=  plugins_url( '/assets/' , __FILE__ );
        wp_register_style( 'facebook-publish', $url , false, '1.0.0' );
        wp_enqueue_style( 'facebook-publish' );

        wp_register_style( 'datetimepickr', $asset_url."jquery.datetimepicker.css" , false, '1.0.0' );
        wp_register_script( 'datetimepickr-js', $asset_url."jquery.datetimepicker.js" , "jquery", '1.0.0' );
        wp_register_script( 'time-function', $asset_url."jquery.datetimepicker.js" , "jquery", '1.0.0' );
        
        wp_enqueue_style( 'datetimepickr' );
        wp_enqueue_script('datetimepickr-js' );
}
add_action( 'admin_enqueue_scripts', 'fp_wp_admin_style' );
/*---------------------------- Enqueue CSS --------------------------*/
