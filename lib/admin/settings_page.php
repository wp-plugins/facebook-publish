<?php 

add_action("admin_menu" , "fp_settings");


function fp_settings() {

	add_menu_page( "Facebook Publish", "Facebook Publish", "manage_options", FP_SETTINGS_SLUG, "fp_settings_page"); 
	
}

function fp_settings_page() {

	global $fp_settings;
	global $hybrid_config;
	global $fp_hybridauth;	
	?>
	
	<div class="wrap">		
		
		<h2>Facebook Publish Settings </h2>

		<hr>
		

		<form method="post">
			
			<?php 

				/********************  Showing notification ********************/
				try{

					if(!$fp_hybridauth->isConnectedWith("facebook")) {

						echo "<span class='alert'>To learn how to obtain Facebook APP ID and APP KEY see <a href='http://codeholic.in/how-to-generate-a-facebook-app-id-and-secret-key/' target=_blank>this tutorial</a>. </span><br><br>";
						
						echo "<span class='alert'>Please Authorize Facebook Application. </span>";

						echo "<a class='button ' href='".site_url("/?fb_publish_validate=1")."' >Authorize Now</a>";

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

}



/*------------------------- API Tab  -------------------------*/
function fp_facebook_api_settings() {

	global $fp_settings;
	$ptf_app_id 	= isset($fp_settings["ptf_app_id"])?$fp_settings["ptf_app_id"]:"";
	$ptf_api_key 	= isset($fp_settings["ptf_api_key"])?$fp_settings["ptf_api_key"]:"";
	
	?>

	<table class="form-table">
	
		<tr>
	
			<th scope="row"><label for="ptf_app_id">Application App ID:</label></td>
	
			<td><input type="text" name="ptf_app_id" id="ptf_app_id"  value="<?php echo $ptf_app_id; ?>" /></td>
	
		</tr>
	
		<tr>
	
			<th scope="row"><label for="ptf_api_key">Appplication Key:</label></td>
	
			<td><input type="text" name="ptf_api_key" id="ptf_api_key" value="<?php echo $ptf_api_key; ?>" /></td>
	
		</tr>
	
		<tr>
	
			<td>
	
				<input type="submit" value="Save" class="button button-primary " name="fp_api_settings"/>
	
			</td>

		</tr>

	</table>

	<?php

} //fp_facebook_api_settings



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

		if(isset($_REQUEST['fp_post_cat'])) {

			$fp_post_cat	= $_REQUEST['fp_post_cat'];

			$fp_settings["fp_post_cat"] 	= $fp_post_cat;		
			
		}
		if(isset($_REQUEST['msg_body']) && $_REQUEST['msg_body']) {

			$msg_body = $_REQUEST['msg_body'];
			
			$fp_settings["msg_body"] = $msg_body;
		}

		if(isset($_REQUEST['post_featured_image']) && $_REQUEST['post_featured_image']) {

			$post_featured_image = $_REQUEST['post_featured_image'];
			
			$fp_settings["post_featured_image"] = $post_featured_image;
		}


	} //isset fp_general_settings

	update_option( "fp_settings" , $fp_settings );

} //fp_save_settings

/*------------------------ General Settings Tab --------------------------*/
function fp_general_settings() {

	global $fp_settings;

	?>
	<h3>General Settings</h3>
	
	<table class="form-table fp_general_settings"> 
		<tr>

			<th scope="row">
			
				<label for='fp_all_posts' >Post ALL post types & categories:</label>
			
			</th>
			
			<td>
			
				<input type="checkbox" id="fp_all_posts" name='fp_all_posts' <?php isset($fp_settings["fp_all_posts"])? checked( "1", $fp_settings["fp_all_posts"] ) :""; ?>/>
			
			</td>
		
		</tr>
		
		<tr>
			
			<th scope="row">Enable posting for only these post types:</th>
			

			<td> 
				

				<?php

					$ignore_posts = array("attachment" ,  "revision" , "nav_menu_item");
					
					$post_types = get_post_types( '', 'names' ); 

					foreach ( $post_types as $post_type ) {

						$checked = "";

						if(in_array($post_type, $ignore_posts))
							continue;

						if(isset($fp_settings["fp_post_types"]) && in_array( $post_type, $fp_settings["fp_post_types"]) ) {

							$checked = " checked='checked' ";

						} 

					    echo "<input type='checkbox' class='disablethis' value='$post_type' id='fp_$post_type' name='fp_post_types[]' $checked /><label for='fp_$post_type'>$post_type</label><br>" ;

					}
				?>
				

			</td>

		</tr>

		<tr>
			
			<th scope="row">Enable posting for Categories:</th>
			
			<td> 
				

				<?php
					
					$args = array(
							  'orderby' => 'name',
							  'order' => 'ASC',
							  'hide_empty' => false,
							  ); 

					$categories = get_categories( $args ); 

					foreach ( $categories as $category ) {

						$checked = "";

						if(isset($fp_settings["fp_post_cat"]) && in_array( $category->term_id, $fp_settings["fp_post_cat"]) ) {

							$checked = " checked='checked' ";

						} 
						

						echo "<input  type='checkbox' class='disablethis' value='{$category->cat_ID}' id='fp_{$category->name}' name='fp_post_cat[]' $checked/><label for='fp_{$category->name}'>{$category->name}</label><br>" ;
					
					}
				?>
				

			</td>

		</tr>
		
		<tr>
			<th scope="row">Message Body:</th>

			<td>
				
				<textarea id="msg_body" class="msg_body" name="msg_body" ><?php echo $fp_settings["msg_body"]; ?></textarea>

			</td>
		</tr>
		
		<tr>
			<th scope="row">Post featured images to Facebook?</th>

			<td>
					
				<input type="radio" value="yes" <?php checked("yes" , $fp_settings['post_featured_image'] ); ?>  name="post_featured_image" class="post_featured_image" id="post_featured_image_yes" ><label for="post_featured_image_yes">Yes</label><br>			
				<input type="radio" value="no"  <?php checked("no" , $fp_settings['post_featured_image'] ); ?> name="post_featured_image" class="post_featured_image" id="post_featured_image_no" ><label for="post_featured_image_no">No</label>			
				
			</td>
		</tr>

		<tr>
			
			<td>
				
				<input type="submit" value="Save" class="button button-primary " name="fp_general_settings"/>
				
			</td>

		</tr>

	</table>	
	<script type="text/javascript">
		function fp_update_general_settings() {

		    if(jQuery("#fp_all_posts").is(":checked")) {

		        jQuery(".disablethis").attr("disabled" , "disabled");
		    
		    }
		    else {
		        
		    	jQuery(".disablethis").removeAttr("disabled"); 
		       
		    }
		    
		}

		fp_update_general_settings();

		jQuery("#fp_all_posts").change(

		    function() {
		    
		    	fp_update_general_settings();

		 	}
		);

	</script>
	<?php
}

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
	
	if( !$fp_hybridauth->isConnectedWith("facebook") ) {
	    
	         echo '<div class="update-nag">
	    
	             <p><b>Facebook Publish</b> will not work unless you <a href="?page=facebook-publish&tab=api">authorize the Facebook Application</a>. To learn how to obtain Facebook APP ID and APP KEY see <a href="http://codeholic.in/how-to-generate-a-facebook-app-id-and-secret-key/" target=_blank>this tutorial</a> </p>
	    
	         </div>';
	    
	}
}

add_action('admin_notices', 'my_admin_notice');

?>