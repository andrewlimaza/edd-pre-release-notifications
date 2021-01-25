<?php

class PPSEDDPRNMailchimp{

	function __construct(){

		$this->api_key = edd_get_option( 'pps_edd_prns_mc_api_key' );

		$this->url_args = array(
			'timeout' => 90,
			'headers' => array(
				'Authorization' => 'EDD_PRN_MC ' . $this->api_key
			),
		);

		$dataCenter = substr($this->api_key, strpos($this->api_key,'-') + 1);
		
		$this->api_url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0';

	}

	function get_lists(){

		$lists = array( '' => 'Select an Audience' );

		$max_lists = apply_filters('pps_edd_prn_mc_max_lists', 15);		
		
		$url = $this->api_url . "/lists/?count={$max_lists}";

		$response = wp_remote_get($url, $this->url_args);		
		$resp_code = wp_remote_retrieve_response_code( $response );

		if( is_numeric( $resp_code ) && 200 == $resp_code ) {
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if( !empty( $body->lists ) ){
				foreach( $body->lists as $list ){
					$lists[$list->id] = $list->name;
				}
			}
			return $lists;
		} else {
			return false;
		}

		return true;


	}

	function subscribe( $email, $merge_tags ){

		$api_key = edd_get_option( 'pps_edd_prns_mc_api_key' );

		$audience = edd_get_option( 'pps_edd_prns_mc_audience' );

		$memberId = md5(strtolower($email));

		$url = $this->api_url.'/lists/' . $audience . '/members/' . $memberId;

		$data = array(
            'email_address' => $email,
            'status' => 'subscribed'
        );

        if( !empty( $merge_tags ) ){
        	$data['merge_fields'] = $merge_tags;
        }

        $packet = json_encode($data);

    	$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $this->api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $packet);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $result;

	}

}
