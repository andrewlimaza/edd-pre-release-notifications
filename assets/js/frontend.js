jQuery(document).ready(function(){

	jQuery("body").on("click", "#pps_edd_prn_submit", function(e){
		
		e.preventDefault();

		var pps_edd_prn_fname = "";
		if( jQuery("#pps_edd_prn_fname").length > 0 ){			
			var pps_edd_prn_fname = jQuery("#pps_edd_prn_fname").val();
		}

		var pps_edd_prn_lname = "";
		if( jQuery("#pps_edd_prn_lname").length > 0 ){			
			var pps_edd_prn_lname = jQuery("#pps_edd_prn_lname").val();
		}

		var pps_edd_prn_email = "";
		if( jQuery("#pps_edd_prn_email").length > 0 ){			
			var pps_edd_prn_email = jQuery("#pps_edd_prn_email").val();
		}

		var data = {
			action: 'pps_edd_prn_subscribe',
			fname: pps_edd_prn_fname,
			lname: pps_edd_prn_lname,
			email: pps_edd_prn_email,
			security: jQuery("#security").val(),
			download: jQuery("#pps_edd_prn_download").val()
		}

		jQuery.post( pps_edd_prn.ajaxurl, data, function( response ){

			if( response ){

				if( pps_edd_prn.redirect === 1 ){
					if( pps_edd_prn.redirect_url !== "" ){
						window.location.href = pps_edd_prn.redirect_url;
					} else {
						jQuery(".pps_edd_prn_subscribe_form").html("<p>"+pps_edd_prn.submitted+"</p>");
					}
				} else {
					jQuery(".pps_edd_prn_subscribe_form").html("<p>"+pps_edd_prn.submitted+"</p>");
				}

			} else {
				jQuery(".pps_edd_prn_subscribe_form").html("<p>Something went wrong.</p>");
			}

		});

		return false;

	});

});