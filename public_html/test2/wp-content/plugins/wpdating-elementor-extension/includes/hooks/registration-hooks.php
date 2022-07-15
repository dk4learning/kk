<?php
	/**
	 * 
	 * This function  is used  to auto login user after user register
	 * @access public
	 * @param null
	 * @author neil
	 */
	if( !function_exists( 'dsp_auto_login_after_register' )){
		add_action('dsp_auto_logged_in',  'dsp_auto_login_after_register');
		function dsp_auto_login_after_register($user_id) {

		        do_action('dsp_setFlash',array('message' => language_code('DSP_PLEASE_CHECK_YOUR_EMAIL_FOR_LOGIN_DETAIL')));
		        wp_set_current_user($user_id);
		        wp_set_auth_cookie($user_id);
		         // You can change home_url() to the specific URL,such as wp_redirect( 'http://www.wpcoke.com' );
		        return true;
		       
		}
	}
    /**
	*
	* This is filter used to validate the password field in  user registration page
	* @param Array It takes values  for password and confirm password fields as an array
	* @return HTML formatted output to show password field in the registration form
	*/
	if( !function_exists( 'dsp_validate_password' )){
		add_action('dsp_validate_password',  'dsp_validate_password');
	    function dsp_validate_password($posts) {
	    	extract($posts);
	    	$errors = array();
	    	$password = isset($password) ? esc_sql(sanitizeData(trim($password), 'xss_clean')) : '';
	        $rePassword = isset($re_password) ? esc_sql(sanitizeData(trim($re_password), 'xss_clean')) : '';  
	    	$errors[] = (empty($password)) ? language_code('DSP_ENTER_PASSWORD') : '';
	    	$errors[] = ($password != $rePassword) ? language_code('DSP_PASSWORD_NOT_MATCH_CONFIRM') : '';
	    	$errors = array_filter($errors);
	    	return (empty($errors)) ?  $password : $errors;
	    }
	}


    /**
	*
	* This is method used to send email for activation after user register
	* @param array
	*
     * @return string | boolean
	*/
	if( !function_exists( 'wpee_dsp_get_activation_link' )){
		add_filter('wpee_dsp_get_activation_link', 'wpee_dsp_get_activation_link');
	    function wpee_dsp_get_activation_link( $user_details ) {
	        if ( ! is_array( $user_details ) ) {
	            return false;
            }
            $new_message =  $user_details['message'];
	   		$hashKey     = base64_encode( $user_details['userId'] . ',' . $user_details['password'] );
	   		add_user_meta( $user_details['userId'], '_dsp_confirm', 'false' );

			$profile_page_url = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $user_details['username'] );
	        $new_message .= "\nClick <a href='" . trailingslashit($profile_page_url) . 'verify_user/?email='.$user_details['email'] .
                '&key=' . $hashKey ."'>here</a> to activate your account.";
	   		return $new_message;
	    }
	}


/**
 *  This function is used to send an email for verification to reset password
 *
 * @param   [Integer] [membership_plan_id] [membership plan Id]
 *
 * @return  [boolean]
 */

if ( ! function_exists( 'wpee_verify_email' ) ) {
    function wpee_verify_email() {
        global $wpdb;
        include_once( WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php" );
        $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $dsp_user_table            = $wpdb->prefix . DSP_USERS_TABLE;
        $user_n_email              = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$username = $user_n_email;
        $result                    = array();
        $check_user_exist          = $wpdb->get_row( "SELECT * FROM `$dsp_user_table` where user_login like '$user_n_email' or user_email like '$user_n_email'" );
        if ( count( $check_user_exist ) > 0 ) {
            $email_template         = $wpdb->get_row( "SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='22'" );
            $reciver_details        = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID='" . $check_user_exist->ID . "'" );
            $reciver_name           = $reciver_details->user_login;
            $receiver_email_address = $reciver_details->user_email;
            $admin_email            = get_option( 'admin_email' );
            $from                   = $admin_email;
            $url                    = site_url();
            $email_subject          = $email_template->subject;
            $mem_email_subject      = $email_subject;
            $email_message          = $email_template->email_body;
            $email_message          = str_replace( "[SITE_URL]", $url, $email_message );
            $email_message          = str_replace( "[USERNAME]", $reciver_name, $email_message );
			$profile_page = get_option( 'wpee_profile_page', '' );
			$profile_page_url = get_permalink( $profile_page );	
			if(is_email($user_n_email) ){
				$userdata = get_user_by('email', $user_n_email );
				$username = $userdata->data->user_login;
			}
            $email_message          = str_replace( "[RESET_URL]", trailingslashit($profile_page_url) . $username . '/reset_password/?key=' . base64_encode( $check_user_exist->ID . ',' . $check_user_exist->user_pass ), $email_message );
            $MemberEmailMessage     = $email_message;
            // wp_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $wpdating_email   = Wpdating_email_template::get_instance();
            $result_mail      = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $result['output'] = 1;
        } else {
            $result['output'] = 0;
        }

        echo json_encode( $result );
        exit;
    }
}

add_action( 'wp_ajax_nopriv_wpee_verify_email', 'wpee_verify_email' );


if ( ! function_exists( 'dsp_email_admin_alert' ) ) {
    function dsp_email_admin_alert() {
        // check if email_admin is enabled or not
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $check_email_admin          = $wpdb->get_row( "SELECT setting_status FROM $dsp_general_settings_table WHERE setting_name = 'email_admin'" );
        if ( $check_email_admin->setting_status == 'Y' ) {
            $email   = get_bloginfo( 'admin_email' );
            $subject = __('New User Registered','wpdating');
            $message = __('A new member has been created on your site.','wpdating');
            $message .= '<br />User Name: ' . $_REQUEST['username'];
            $message .= '<br />Email: ' . $_REQUEST['email'];
			$profile_page = get_option( 'wpee_profile_page', '' );
			$profile_page_url = get_permalink( $profile_page );	
            $message .= '<br/><a href="' . trailingslashit($profile_page_url) . $_REQUEST['username'] . '">'. __('View User Profile', 'wpdating') . '</a>';
            $message .= '<br /><br /><br /><i>NOTE: '. __('Auto generated from site', 'wpdating') . '</i>';
            wp_mail( $email, $subject, $message );

        }

    }
}
add_action( 'user_register', 'dsp_email_admin_alert' );