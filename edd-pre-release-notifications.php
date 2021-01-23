<?php
/*
Plugin Name: Easy Digital Downloads - Pre Release Notifications
Plugin URI: https://pacificplugins.com/downloads/edd-pre-release-notifications/
Description: 
Version: 1.0.0
Author: Pacific Plugins
Author URI: https://pacificplugins.com
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
Text Domain: edd-pre-release-notifications
Domain Path: languages
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EDD_PRE_RELEASE_N_VERSION', '1.0.0' );
define( 'EDD_PRE_RELEASE_N_URL', plugins_url( '', __FILE__ ) );

require plugin_dir_path( __FILE__ ).'includes/functions.php';
require plugin_dir_path( __FILE__ ).'includes/class-admin.php';
require plugin_dir_path( __FILE__ ).'includes/settings.php';

class EDD_Pre_Release_Notifications{

	function __construct(){

		add_action( 'init', array( $this, 'register_cpt_notifications' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'manage_pp_edd_prn_posts_columns', array( $this, 'custom_column_headers' ), 10, 1 );
		add_action( 'manage_pp_edd_prn_posts_custom_column' , array( $this, 'custom_columns' ), 10, 2 );

		add_action( 'edd_pre_add_to_cart', array( $this, 'pre_add_to_cart' ) );
		add_action( 'edd_download_after', array( $this, 'after_cart' ) );
		add_action( 'pre_release_notice', array( $this, 'after_cart' ) );
		add_action( 'edd_purchase_download_form', array( $this, 'download_form' ), 10, 2 );

		add_action( 'wp_ajax_pps_edd_prn_subscribe', array( $this, 'form_submission' ) );
		add_action( 'wp_ajax_nopriv_pps_edd_prn_subscribe', array( $this, 'form_submission' ) );
	}

	public function frontend_scripts(){

		wp_enqueue_script( 'edd-pre-release-notifications-frontend', EDD_PRE_RELEASE_N_URL.'/assets/js/frontend.js' );
		
		$pps_edd_prn = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirect' => intval( edd_get_option( 'pps_edd_prns_redirect' ) ),
			'redirect_url' => edd_get_option( 'pps_edd_prns_redirect_url' ),
			'submitted' => edd_get_option( 'pps_edd_prns_form_submitted_text' )
		);
		wp_localize_script( 'edd-pre-release-notifications-frontend', 'pps_edd_prn', $pps_edd_prn );

		wp_enqueue_style( 'edd-pre-release-notifications-styles', EDD_PRE_RELEASE_N_URL.'/assets/css/frontend.css' );

	}

	public function register_cpt_notifications(){

		$labels = array(
	        'name'                  => _x( 'Pre-Release Notifications', 'Post type general name', 'textdomain' ),
	        'singular_name'         => _x( 'Pre-Release Notification', 'Post type singular name', 'textdomain' ),
	        'menu_name'             => _x( 'Pre-Release Notifications', 'Admin Menu text', 'textdomain' ),
	        'name_admin_bar'        => _x( 'Pre-Release Notification', 'Add New on Toolbar', 'textdomain' ),
	        'add_new'               => __( 'Add New', 'textdomain' ),
	        'add_new_item'          => __( 'Add New Pre-Release Notification', 'textdomain' ),
	        'new_item'              => __( 'New Pre-Release Notification', 'textdomain' ),
	        'edit_item'             => __( 'Edit Pre-Release Notification', 'textdomain' ),
	        'view_item'             => __( 'View Pre-Release Notification', 'textdomain' ),
	        'all_items'             => __( 'All Pre-Release Notifications', 'textdomain' ),
	        'search_items'          => __( 'Search Pre-Release Notifications', 'textdomain' ),
	        'parent_item_colon'     => __( 'Parent Pre-Release Notifications:', 'textdomain' ),
	        'not_found'             => __( 'No pre-release notifications found.', 'textdomain' ),
	        'not_found_in_trash'    => __( 'No pre-release notifications found in Trash.', 'textdomain' ),
	    );
	 
	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'publicly_queryable' => true,
	        'show_ui'            => true,
	        'show_in_menu'       => false,
	        'query_var'          => true,
	        'rewrite'            => array( 'slug' => 'edd-pre-release-notifications' ),
	        'capability_type'    => 'post',
	        'has_archive'        => true,
	        'hierarchical'       => false,
	        'menu_position'      => null,
	        'supports'           => array( 'title', 'author' ),
	        'capabilities' => array(
	            // 'create_posts' => 'do_not_allow',
	            // 'delete_posts' => 'allow'
	        )
	    );
	 
	    register_post_type( 'pp_edd_prn', $args );

	}

	function admin_menu(){

		add_submenu_page( 'edit.php?post_type=download', __('Pre-Release Notifications', 'edd-pre-release-notifications'), __('Pre-Release Notifications', 'edd-pre-release-notifications'), 'manage_options', 'edit.php?post_type=pp_edd_prn' );

	}

	public function custom_column_headers( $columns ){

		unset( $columns['author'] );

		if( apply_filters( 'pps_edd_prn_show_fname', true ) || apply_filters( 'pps_edd_prn_show_lname', true ) ){						
			$columns['pps_edd_name'] = __( 'Name', 'your_text_domain' );
		}
		
		$columns['pps_edd_download'] = __( 'Download', 'your_text_domain' );
		$columns['pps_edd_status'] = __( 'Status', 'your_text_domain' );		

		if( pps_edd_prn_is_mc_configured() ){
			$columns['pps_edd_mailchimp'] = __( 'Subscribed to Mailchimp', 'your_text_domain' );
		}

    	return $columns;

	}

	public function custom_columns( $column, $post_id ){
		if( $column == 'pps_edd_name' ){
			$fname = get_post_meta( $post_id, 'pps_edd_prn_fname', true );
			$lname = get_post_meta( $post_id, 'pps_edd_prn_lname', true );
			echo $fname .' '.$lname;
		}
		if( $column == 'pps_edd_download' ){
			$download = get_post_meta( $post_id, 'pps_edd_prn_download', true );
			if( intval( $download ) > 0 ){
				$download_obj = edd_get_download( intval( $download ) );					
				echo "<a href='".get_the_permalink( $download_obj->ID )."' target='_BLANK'>".$download_obj->post_title."</a>";
			}
		}
		if( $column == 'pps_edd_status' ){
			$subscribed = get_post_meta( $post_id, 'pps_edd_prn_status', true );
			if( $subscribed == 'subscribed' ){
				echo 'Subscribed';
			} else if( $subscribed == 'notified' ){
				echo 'Notified';
			}
		}
	}

	function pre_add_to_cart( $download_id ){

		if( pps_edd_prn_is_prerelease_active( $download_id ) ) {

			$add_text = apply_filters( 'edd_pre_release_pre_add_to_cart', __( 'This download cannot be purchased yet.', 'edd-pre-release-notifications' ), $download_id );

			wp_die( $add_text, '', array( 'back_link' => true ) );

		}

	}

	function after_cart(){

		if ( !pps_edd_prn_is_prerelease_active( get_the_ID() ) ) {
			return;
		}
		
		// admin colum text
		if ( is_admin() ) {

			return apply_filters( 'edd_pre_release_display_admin_text', '<strong>' . __('Pre Release Notifictions Enabled', 'edd-pre-release-notifications' ) . '</strong>' );

		} else {
			
			/**
			 * Load Form Here
			 */
			
			$total_subs = get_total_subcribers( get_the_ID() );

			echo $this->signup_form();
		}

	}

	function download_form( $purchase_form, $args ){

		global $post;

		if ( pps_edd_prn_is_prerelease_active( $args[ 'download_id' ] ) ) {

			/* Display the voting form on single page */
			if ( is_single( $post ) && 'download' == $post->post_type ) {

				return $this->signup_form();

			} else {

				/* Only display the form in the download shortcode if enabled */
				if ( get_post_meta( $post->ID, 'pps_edd_prn_active', true ) === 1 ) {
					return $this->signup_form();
				} else {
					return '';
				}
			}


		}

		return $purchase_form;

	}

	function signup_form(){

		ob_start();
		include plugin_dir_path( __FILE__ ).'includes/form.php';
		$content = ob_get_contents();
		ob_end_clean();

		return $content;

	}

	public function form_submission(){

		if( !isset( $_REQUEST['security'] ) || !wp_verify_nonce( $_REQUEST['security'], 'pps_edd_prn_subscribe' ) ){
   		
   			wp_die( 'Something went wrong.');
		
		} else {

			if( !empty( $_REQUEST['action'] ) && $_REQUEST['action'] == 'pps_edd_prn_subscribe' ){

				$fname = sanitize_text_field( $_REQUEST['fname'] );
				$lname = sanitize_text_field( $_REQUEST['lname'] );
				$email = sanitize_text_field( $_REQUEST['email'] );
				$download = intval( $_REQUEST['download'] );

				$my_post = array(
				  'post_title'    => $email,
				  'post_status'   => 'publish',
				  'post_author'   => 1,
				  'post_type' 	  => 'pp_edd_prn' 
				);

				$post_id = wp_insert_post( $my_post );

				if( $fname !== "" ){ update_post_meta( $post_id, 'pps_edd_prn_fname', $fname ); }
				if( $lname !== "" ){ update_post_meta( $post_id, 'pps_edd_prn_lname', $lname ); }

				update_post_meta( $post_id, 'pps_edd_prn_download', $download );
				update_post_meta( $post_id, 'pps_edd_prn_status', 'subscribed' );

				echo $post_id;

				wp_die();

			}

		}

	}

}

new EDD_Pre_Release_Notifications();