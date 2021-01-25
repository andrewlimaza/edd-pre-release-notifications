<?php

function pps_edd_prn_is_mc_configured(){

	if( intval( edd_get_option( 'pps_edd_prns_enable_mc' ) ) === 1 && edd_get_option( 'pps_edd_prns_mc_api_key' ) !== "" ){
		return true;
	}
	
	return false;

}

function pps_edd_prn_is_prerelease_active( $download_id ){

	if( intval( get_post_meta( $download_id, 'pps_edd_prn_active', true ) ) === 1 ){
		return true;
	}

	return false;

}

function get_total_subcribers( $download_id ){

	$args = array(
		'post_type' => 'pp_edd_prn',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'pps_edd_prn_download',
				'value' => $download_id,
				'compare' => '='
			)
		)
	);

	$the_query = new WP_Query( $args );

	return $the_query->found_posts;

}

function pps_edd_prn_mailchimp_audience(){

	if( pps_edd_prn_is_mc_configured() ){

		$mc = new PPSEDDPRNMailchimp();

		$lists = $mc->get_lists();

		return $lists;

	} else {

		return array( '' => __('Enter Your Mailchimp API Key', 'edd-pre-release-notifications' ) );
	}

}