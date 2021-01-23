<form method='POST' class='pps_edd_prn_subscribe_form'>
	<?php
		global $post;

		$intro = edd_get_option( 'pps_edd_prns_form_intro_text' );
		if( $intro !== "" ){
			echo "<p>".$intro."</p>";
		}
	?>

	<?php wp_nonce_field( 'pps_edd_prn_subscribe', 'security' ); ?>
	<input type='hidden' name='pps_edd_prn_download' name='pps_edd_prn_download' id='pps_edd_prn_download' value='<?php echo $post->ID; ?>' /> 
	
	<?php if( apply_filters( 'pps_edd_prn_show_fname', true ) ){ ?>
		<label for='pps_edd_prn_fname'><?php _e('First Name', 'edd-pre-release-notifications' ); ?></label>
		<input type='text' name='pps_edd_prn_fname' id='pps_edd_prn_fname' />
	<?php } ?>

	<?php if( apply_filters( 'pps_edd_prn_show_fname', true ) ){ ?>
	<label for='pps_edd_prn_lname'><?php _e('Last Name Name', 'edd-pre-release-notifications' ); ?></label>
	<input type='text' name='pps_edd_prn_lname' id='pps_edd_prn_lname' />
	<?php } ?>

	<label for='pps_edd_prn_email'><?php _e('Email Address', 'edd-pre-release-notifications' ); ?></label>
	<input type='email' name='pps_edd_prn_email' id='pps_edd_prn_email' />

	<input type='submit' name='pps_edd_prn_submit' id='pps_edd_prn_submit' value='<?php _e('Submit', 'edd-pre-release-notifications' ); ?>' />

</form>