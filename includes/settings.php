<?php

/**
* Register our settings section
*
* @since  2.4
* @return array
*/
function pps_edd_prns_section( $sections ) {

	$sections['pps_edd_prns_settings'] = __( 'Pre-Release Notifications', 'edd-recurring' );

	return $sections;
}
add_filter( 'edd_settings_sections_extensions', 'pps_edd_prns_section' );

/**
* Register our settings
*
* @since  1.0
* @return array
*/
function pps_edd_prns_settings( $settings ) {

	$recurring_settings = array(
		'pps_edd_prns_settings' => array(
			array(
				'id'    => 'pps_edd_prns_redirect',
				'name'  => __( 'Redirect On Submission', 'edd-recurring' ),
				'desc'  => __( 'Check this if you\'d like to add subscribers to your Mailchimp audience.', 'edd-recurring' ),
				'type'  => 'checkbox'
			),	
			array(
				'id'   => 'pps_edd_prns_redirect_url',
				'name' => __( 'Thank You URL', 'edd-recurring' ),
				'desc' => __( 'This text is shown at the top of your subscription form on your download.', 'edd-recurring' ),
				'type' => 'text',
				'std'  => __( 'Subscribe to mailing list and be the first to use this download when launched.', 'edd-recurring' )
			),
			array(
				'id'   => 'pps_edd_prns_form_intro_text',
				'name' => __( 'Form Introduction Text', 'edd-recurring' ),
				'desc' => __( 'This text is shown at the top of your subscription form on your download.', 'edd-recurring' ),
				'type' => 'text',
				'std'  => __( 'Subscribe to mailing list and be the first to use this download when launched.', 'edd-recurring' )
			),
			array(
				'id'   => 'pps_edd_prns_form_submitted_text',
				'name' => __( 'Form Submitted Text', 'edd-recurring' ),
				'desc' => __( 'This text is shown at the top of your subscription form on your download.', 'edd-recurring' ),
				'type' => 'text',
				'std'  => __( 'Subscribe to mailing list and be the first to use this download when launched.', 'edd-recurring' )
			),
			array(
				'id'    => 'pps_edd_prns_enable_mc',
				'name'  => __( 'Enable Mailchimp Integration', 'edd-recurring' ),
				'desc'  => __( 'Check this if you\'d like to add subscribers to your Mailchimp audience.', 'edd-recurring' ),
				'type'  => 'checkbox'
			),			
			array(
				'id'   => 'pps_edd_prns_mc_api_key',
				'name' => __( 'Mailchimp API Key', 'edd-recurring' ),
				'desc' => __( 'Your Mailchimp API Key.', 'edd-recurring' ),
				'type' => 'text',
				'std'  => __( 'Signup Fee', 'edd-recurring' )
			),
			array(
				'id'    => 'pps_edd_prns_enable_welcome',
				'name'  => __( 'Enable Pre-Release Welcome Email', 'edd-recurring' ),
				'desc'  => __( 'Check this if you\'d like to send an email to users when they subscribe for notifications. Typically used to thank them for signing up.', 'edd-recurring' ),
				'type'  => 'checkbox'
			),	
			array(
				'id'    => 'pps_edd_prns_welcome_email',
				'name'  => __( 'Subscribed Email', 'edd-recurring' ),
				'desc'  => __( 'Enter the body text of the email sent when a user subscribes for a pre-release notification.', 'edd-recurring' ),
				'type'  => 'rich_editor',
				'std'   => __( "Hello {name}\n\nYour renewal payment in the amount of {amount} for {subscription_name} has been successfully processed.", 'edd-recurring' )
			),
			array(
				'id'    => 'pps_edd_prns_release_email',
				'name'  => __( 'Release Notification Email', 'edd-recurring' ),
				'desc'  => __( 'Enter the body text of the email that gets sent when you notify users of your download release.', 'edd-recurring' ),
				'type'  => 'rich_editor',
				'std'   => __( "Hello {name}\n\nYour renewal payment in the amount of {amount} for {subscription_name} has been successfully processed.", 'edd-recurring' )
			),
		)
	);

	return array_merge( $settings, $recurring_settings );
}
add_filter( 'edd_settings_extensions', 'pps_edd_prns_settings' );
