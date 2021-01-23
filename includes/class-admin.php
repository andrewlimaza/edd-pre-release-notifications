<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EDD_Pre_Release_Notifications_Admin {

	public function __construct() {
      
        add_action( 'edd_meta_box_settings_fields', array( $this, 'render_option' ), 100 );
        add_action( 'edd_save_download', array( $this, 'save_download' ), 10, 2 );
        add_filter( 'edd_download_price', array( $this, 'admin_price_column' ), 20, 2 );
        add_filter( 'edd_price_range', array( $this, 'admin_price_column' ), 20, 2 );
        add_filter( 'edd_metabox_fields_save', array( $this, 'metabox_fields_save' ) );
		    
	}

    public function render_option( $post_id ) {

        $coming_soon = get_post_meta( $post_id, 'pps_edd_prn_active', true );
        
        ?>
        
        <p>
            <label for="edd_coming_soon">
                <input type="checkbox" name="pps_edd_prn_active" id="pps_edd_prn_active" value="1" <?php checked( 1, $coming_soon ); ?> />
                <?php _e( 'Enable Pre-Release Notification Form.', 'edd-coming-soon' ); ?>
            </label>
        </p>
        
    <?php
    }

    public function save_download( $post_id, $post ) {

        if( isset( $_REQUEST['pps_edd_prn_active'] ) ){
            update_post_meta( $post_id, 'pps_edd_prn_active', intval( $_REQUEST['pps_edd_prn_active'] ) );
        } else {
            update_post_meta( $post_id, 'pps_edd_prn_active', 0 );
        }

    }

    public function admin_price_column( $price, $download_id ) {

        $is_active = pps_edd_prn_is_prerelease_active( $download_id );

        if ( $is_active ) {

            $price .= '<br /><strong>' . __( 'Subscribers: ', 'edd-coming-soon' ) . "<a href='".admin_url( 'edit.php?post_type=pp_edd_prn&edd_download='.$download_id )."' title='View Subscribers' >" . get_total_subcribers( $download_id ) . "</a>" . '</strong>';

        }
        
        return $price;

    }
                    
    public function metabox_fields_save( $fields ) {

        $fields[] = 'pps_edd_prn_active';

        return $fields;

    }

}

new EDD_Pre_Release_Notifications_Admin();
