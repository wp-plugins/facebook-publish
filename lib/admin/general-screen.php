<?php

/*------------------------ General Settings Tab --------------------------*/
function fp_general_settings() {
	global $fp_settings;
	?>
	<h3>General Settings</h3>
	
	<table class="form-table fp_general_settings"> 

		<tr>
			<th scope="row">
			
				<label for='fp_all_posts' >Post ALL post types:</label>
			
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
			<th scope="row">Schedule Posts?</th>
			<td>
					
				<input type="radio" value="yes" <?php checked("yes" , $fp_settings['post_schedule'] ); ?>  name="post_schedule" class="post_schedule" id="post_schedule_yes" ><label for="post_schedule_yes">Yes</label><br>			
				<input type="radio" value="no"  <?php checked("no" , $fp_settings['post_schedule'] ); ?> name="post_schedule" class="post_schedule" id="post_schedule_no" ><label for="post_schedule_no">No</label>			
				
			</td>
		</tr>
		<?php if(count($fp_settings['pages'])>0): ?>
		<tr>
			<th scope="row">Facebook Pages:</th>
			<td>
			
				<?php fp_print_fb_pages() ?>
				
			</td>
		</tr>
	<?php endif; ?>
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

/*------------------------ General Settings Tab --------------------------*/