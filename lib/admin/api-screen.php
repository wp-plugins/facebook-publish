<?php
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

	<script type="text/javascript">
	jQuery("#fp_api_frm").submit(
	  function() {

	      pf_app_id = jQuery("#ptf_app_id").val();
	      pf_api_key = jQuery("#ptf_api_key").val();

	      if(pf_app_id.trim() =="" || pf_api_key.trim() =="") {
	        alert("Please enter application ID and Secret key.");
	        return false;
	      }
	  
	  });
	</script>
	<?php
} //fp_facebook_api_settings

/*------------------------- API Tab Ends  -------------------------*/