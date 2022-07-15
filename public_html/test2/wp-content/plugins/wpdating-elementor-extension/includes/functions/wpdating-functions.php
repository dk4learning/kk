<?php

/**
 * Define functions for wpdating
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes/functions
 * @author       < >
 */
/**
 * List of functions
 *	- wpee_sanitizeData
 */
	/**
	 * Get details for settings
	 *
	 * @since    1.0.0
	 */


	include_once(WP_DSP_ABSPATH . 'dsp_xss_clean.php');
	if( !function_exists( 'wpee_sanitizeData' )){
		
		function wpee_sanitizeData($var, $type) {
		    $obj_input = new CI_Input();
		    switch ($type) {

		        case 'xss_clean':
		            //echo '<br> i am case xss'.$var.'<br>';
		            $var = $obj_input->xss_clean($var);
		            return $var;
		            break;

		        case 'int': // integer
		            $var = (int) $var;
		            break;
		        case 'str': // trim string
		            $var = trim($var);
		            break;
		        case 'nohtml': // trim string, no HTML allowed
		            $var = htmlentities(trim($var), ENT_QUOTES);
		            break;

		        case 'plain': // trim string, no HTML allowed, plain text
		            $var = htmlentities(trim($var), ENT_NOQUOTES);
		            break;
		        case 'upper_word': // trim string, upper case words
		            $var = ucwords(strtolower(trim($var)));
		            break;
		        case 'ucfirst': // trim string, upper case first word
		            $var = ucfirst(strtolower(trim($var)));
		            break;
		        case 'lower': // trim string, lower case words
		            $var = strtolower(trim($var));
		            break;
		        case 'urle': // trim string, url encoded
		            $var = urlencode(trim($var));
		            break;
		        case 'trim_urle': // trim string, url decoded
		            $var = urldecode(trim($var));
		            break;
		        case 'telephone': // True/False for a telephone number
		            $size = strlen($var);
		            for ($x = 0; $x < $size; $x++) {
		                if (!( ( ctype_digit($var[$x]) || ($var[$x] == '+') || ($var[$x] == '*') || ($var[$x] == 'p')) )) {
		                    return false;
		                }
		            }
		            return true;
		            break;
		        case 'pin': // True/False for a PIN
		            if ((strlen($var) != 13) || (ctype_digit($var) != true)) {
		                return false;
		            }
		            return true;
		            break;
		        case 'id_card': // True/False for an ID CARD
		            if ((ctype_alpha(substr($var, 0, 2)) != true ) || (ctype_digit(substr($var, 2, 6)) != true ) || ( strlen($var) != 8)) {
		                return false;
		            }
		            return true;
		            break;

		        case 'sql': // True/False if the given string is SQL injection safe
		            //  insert code here, I usually use ADODB -> qstr() but depending on your needs you can use mysql_real_escape();
		            // return mysql_real_escape_string($var);
		            if (!is_numeric($var)) {
		                $var = "'" . mysql_real_escape_string($var) . "'";
		            }
		            return $var;
		            break;

		        case 'encode_php_tag': {
		                $var = str_replace(array('<?php', '<?PHP', '<?', '?>'), array('&lt;?php',
		                    '&lt;?PHP', '&lt;?', '?&gt;'), $var);
		                return $var;
		            }
		            break;

		        case 'natural_non_zero':
		            $var = is_natural_no_zero($var);
		            return $var;
		    }
		    return $var;
		}
	}


/**
 * Get user's age by year
 *
 * @since    1.0.0
 * @return int
 */

///get User Age
// if (!function_exists('dsp_get_age')) {

//     function dsp_get_age($Birthdate) {
//         $dob = strtotime($Birthdate);
//         $y = date('Y', $dob);
//         if (($m = (date('m') - date('m', $dob))) < 0) {
//             $y++;
//         } elseif ($m == 0 && date('d') - date('d', $dob) < 0) {
//             $y++;
//         }
//         return date('Y') - $y;
//     }

// }

/**
 *  This function is used to check email notification  setting by user
 *
 * @param [user_id] [Currently logged in user id]
 * @param [column_name] [column name]
 */

if ( ! function_exists( 'wpee_issetGivenEmailSetting' ) ) {
    function wpee_issetGivenEmailSetting( $user_id, $column_name ) {
        global $wpdb;
        $dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
        $status                      = $wpdb->get_row( $wpdb->prepare( "SELECT $column_name FROM $dsp_user_notification_table WHERE user_id='%d'", $user_id ), ARRAY_N );
        if ( isset( $status ) && !empty( $status ) && $status[0] == 'N' ) {
            return false;
        } else {
            return true;
        }
    }
}


/**
 *  This function is used to get gender list html formatted way
 */

if ( ! function_exists( 'wpee_get_gender_list' ) ) {
    function wpee_get_gender_list( $selected = "", $gender_label= '' ) {
        if( empty( $gender_label ) ){
            $gender_label = __('Select', 'wpdating');
        }
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_gender_list            = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
        $check_couples_mode         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
        // check male module must be premium member Mode is Activated or not.
        $check_male_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'male'" );
        // check female module must be premium member Mode is Activated or not.
        $check_female_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'female'" );
        $query             = "SELECT * FROM $dsp_gender_list ";
        $conditions        = array();
        $conditions[]      = ( $check_couples_mode->setting_status == 'N' ) ? "enum != 'C'" : '';
        $conditions[]      = ( $check_male_mode->setting_status == 'N' ) ? "enum != 'M'" : '';
        $conditions[]      = ( $check_female_mode->setting_status == 'N' ) ? "enum != 'F'" : '';
        $conditions        = array_filter( $conditions );
        if ( ! empty( $conditions ) ) {
            $query .= " WHERE " . implode( ' AND ', $conditions );
        }
        $gender_list   = $wpdb->get_results( $query );
        $gender_option = "";
        if ( '' == $selected ) {
            $gender_option .= '<option value="" selected="selected">' . $gender_label . '</option>';
        } else {
            $gender_option .= '<option value="">' . $gender_label . '</option>';
        }
        foreach ( $gender_list as $gender_row ) {
            $gender = __( addslashes($gender_row->gender) , 'wpdating' );

            if ( $gender_row->editable == 'N' ) {
                if ( $gender_row->enum == $selected ) {
                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . $gender . '</option>';
                } else {
                    $gender_option .= '<option value="' . $gender_row->enum . '">' .  $gender . '</option>';
                }
            } else {

                if ( $gender_row->enum == $selected ) {
                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . $gender . '</option>';
                } else {
                    $gender_option .= '<option value="' . $gender_row->enum . '">' . $gender . '</option>';
                }
            }
        }

        return $gender_option;
    }
}






    if( !function_exists('wpee_check_membership') ){
        //function for checking membership
        function wpee_check_membership($access_feature_name, $user_id) {
            global $wpdb;
            $dsp_payments_table      = $wpdb->prefix . 'dsp_payments';
            $dsp_membership_table    = $wpdb->prefix . 'dsp_memberships';
            $dsp_user_profiles_table = $wpdb->prefix . 'dsp_user_profiles';

            $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE user_id = $user_id");

            if ($wpdb->num_rows == 0) {  //if user doesn't have profile
                return new WP_Error('wpdating_no_profile',
                    __("You must create your profile before you can use  $access_feature_name  features", 'wpdating-api'),
                    array('status' => 400));
            }
            $payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id = $user_id");

            if ($payment_row) { // if user have access to a membership
                if (isset($payment_row->app_expiration_date)) { //if app_expiration_date field is set
                    if ($payment_row->app_expiration_date != null) {  //if app_expiration_date field is not null
                        $current_date = date('Y-m-d H:i:s');    //get current date

                        if (strtotime($payment_row->app_expiration_date) < strtotime($current_date)) { //if membership has been expired
                            $access_status = wpee_check_free_and_free_trial_mode($user_id);

                            if (is_wp_error($access_status)) {
                                return new WP_Error('wpdating_membership_expired',
                                    __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating'), array('status' => 400));
                            }

                            return $access_status;
                        }
                    } else { //if app_expiration_date field is null
                        $current_date = date('Y-m-d');
                        if (strtotime($payment_row->expiration_date) < strtotime($current_date)) { //if membership has been expired
                            $access_status = wpee_check_free_and_free_trial_mode($user_id);

                            if (is_wp_error($access_status)) {
                                return new WP_Error('wpdating_membership_expired',
                                    __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating'), array('status' => 400));
                            }

                            return $access_status;
                        }
                    }
                } else {    //if app_expiration_date field is not set
                    $current_date = date('Y-m-d');
                    if (strtotime($payment_row->expiration_date) < strtotime($current_date)) { //if membership has been expired
                        $access_status = wpee_check_free_and_free_trial_mode($user_id);

                        if (is_wp_error($access_status)) {
                            return new WP_Error('wpdating_membership_expired',
                                __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating'), array('status' => 400));
                        }

                        return $access_status;
                    }
                }

                //if membership has not been expired
                $plan_row = $wpdb->get_row("SELECT * FROM $dsp_membership_table WHERE membership_id = $payment_row->pay_plan_id");

                if ($wpdb->num_rows == 0) {
                    return new WP_Error("wpdating_membership_does_not_exist",
                        __("Membership does not exist", 'wpdating-api'), array('status' => 400));
                }
                $features_id = $plan_row->premium_access_feature;   //get users premium features

                //check access to the features
                $premium_features_access = wpee_check_features($features_id, $access_feature_name);

                if ($premium_features_access == false) {    //if user doesn't access to the feature
                    $access_status = wpee_check_free_and_free_trial_mode($user_id);

                    if (is_wp_error($access_status)) {
                        return new WP_Error('wpdating_premium_access',
                            __( $access_feature_name , 'wpdating' ) . __(" feature not available in your membership plan", 'wpdating'),
                            array('status' => 400));
                    }
                    return $access_status;
                }

                return $premium_features_access;
            }

            //if user doesn't have membership
            $access_status = wpee_check_free_and_free_trial_mode($user_id);
            return $access_status;
        }
    }

    if( !function_exists('wpee_check_free_and_free_trial_mode') ){
        function wpee_check_free_and_free_trial_mode($user_id){
            global $wpdb;
            $free_mode = wpee_get_setting( 'free_mode' );

            if ($free_mode->setting_status == 'Y') {    //if free mode is on
                $free_mode_status = wpee_check_free_mode_access($user_id); //check free mode

                return $free_mode_status;
            }

            //if free mode is off
            $free_trial_mode_status = wpee_check_free_trial_mode_access($user_id);    //check free trial mode
            if (is_wp_error($free_trial_mode_status)) {
                return $free_trial_mode_status;
            }

            return $free_trial_mode_status;
        }
    }


    if( !function_exists('wpee_check_free_mode_access') ){
        //for checking free mode
        function wpee_check_free_mode_access($user_id)
        {
            //check gender access mode
            $gender_access = wpee_check_gender_access($user_id, true);

            return $gender_access;
        }
    }

    if( !function_exists('wpee_check_gender_access') ){
        //function for checking acces to user gender
        function wpee_check_gender_access($user_id, $free_mode)
        {
            global $wpdb;
            $dsp_user_profiles_table    = $wpdb->prefix . 'dsp_user_profiles';
            $dsp_general_settings_table = $wpdb->prefix . 'dsp_general_settings';
            $up                         = $wpdb->get_row("SELECT * FROM $dsp_user_profiles_table WHERE user_id = $user_id");

            if ($free_mode == true) {
                $free_mode_gender = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_member'");

                if ($free_mode_gender->setting_value != 3) {
                    $gender = ($free_mode_gender->setting_value == 1) ? 'M' : 'F';
                    if ($gender != $up->gender) {
                        return new WP_Error('wpdating_upgrade_membership', __('Only premium member can access this feature, Please upgrade your account', 'wpdating'),
                            array('status' => 400));
                    }
                    return true;
                }
                return true;
            }

            $free_trial_mode_gender = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_gender'");
            if ($free_trial_mode_gender->setting_value != 3) { //if gender is not both
                $gender = ($free_trial_mode_gender->setting_value == 1) ? 'M' : 'F';
                if ($gender != $up->gender) { //if gender doesn't match
                    return new WP_Error('wpdating_upgrade_membership', __('Only premium member can access this feature, Please upgrade your account', 'wpdating'),
                        array('status' => 400));
                }
                return true;
            }
            return true;
        }
    }

    if( !function_exists('wpee_check_features') ){
        //function for checking feature access
        function wpee_check_features($features_id, $access_feature_name)
        {
            global $wpdb;
            $dsp_features_table = $wpdb->prefix . 'dsp_features';
            $features_id        = explode(',', $features_id);

            foreach ($features_id as $fid) {
                $features = $wpdb->get_row("SELECT * FROM $dsp_features_table WHERE feature_id = $fid");
                if (strtolower($features->feature_name) == strtolower($access_feature_name)) {   //if feature match
                    return true;
                }
            }

            return false;
        }
    }

    if( !function_exists('wpee_check_free_trial_mode_access') ){
        //for checking free trial mode
        function wpee_check_free_trial_mode_access($user_id)
        {
            global $wpdb;
            $free_trial_mode = wpee_get_setting('free_trail_mode');

            if ($free_trial_mode->setting_status == 'N') {
                return new WP_Error('wpdating_upgrade_membership', __('Only premium member can access this feature, Please upgrade your account', 'wpdating'),
                    array('status' => 400));
            }

            //check gender access mode
            $gender_access = wpee_check_gender_access($user_id, false);

            if (is_wp_error($gender_access)) {
                return $gender_access;
            }

            $user         = _wp_get_current_user();
            $expired_date = date('Y-m-d H:i:s',
                strtotime($user->user_registered . " + $free_trial_mode->setting_value day"));
            $current_date = date('Y-m-d H:i:s');

            if (strtotime($current_date) > strtotime($expired_date)) {   //if free trial has expired
                return new WP_Error('wpdating_free_trial_expired', __('Only premium member can access this feature, Please upgrade your account', 'wpdating'),
                    array('status' => 400));
            }

            return true;
        }
    }

    if( !function_exists('wpee_display_error_message') ){
        function wpee_display_error_message( $error ){ 
            if( is_wp_error($error) && isset($error->errors) ):
                foreach($error->errors as $error_value ){ ?>
                <p><?php echo isset($error_value[0]) ? $error_value[0] : '';?></p>
            <?php
                }
            endif;
        }
    }

    if (!function_exists('wpee_display_members_photo_thumb')) {

        /*
        * Replace previous display_members_photo_thumb function
        */

        function wpee_display_members_photo_thumb($photo_member_id, $path) {
            global $wpdb;

            $favt_mem = array();
            $current_user = wp_get_current_user();

            $user_id = $current_user->ID;  // print session USER_ID

            $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

            $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

            $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

            $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");

            if ($count_member_images > 0) {

                $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
                $check_gender = $wpdb->get_row("SELECT gender,make_private FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

                if ($member_exist_picture->picture == "") {

                    $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";

                } else {
                    if ($photo_member_id == $user_id) {
                        $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                        $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                    } else {
                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$photo_member_id'");

                        foreach ($private_mem as $private) {

                            $favt_mem[] = $private->favourite_user_id;
                        }
                        if ($check_gender->make_private == 'Y') {
                            if (!in_array($user_id, $favt_mem)) {
                                $Mem_Image_path = plugins_url('dsp_dating/images/private-photo-pic.jpg');
                                $physical_image_path = ABSPATH . '/wp-content/plugins/'.'dsp_dating/images/private-photo-pic.jpg';
                            } else {
                                $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                                $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                            }
                        } else {
                            $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                            $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                        }
                    }
                    $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

                    if (file_exists($physical_image_path)) {

                        $Mem_Image_path = $Mem_Image_path;
                    } else {
                        $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";
                    }
                }
            } else {

                $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");

                $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";
            }

            return $Mem_Image_path;
        }

    }

if (!function_exists('wpee_display_members_photo')) {

    /** *******************START FUNCTION CREATE thumb MEMBER PHOTO PATH************************ */

    function wpee_display_members_photo($photo_member_id, $path) { 
        global $wpdb;
        $favt_mem = array();
        
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;  // print session USER_ID

        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            $check_gender = $wpdb->get_row("SELECT gender,make_private FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($member_exist_picture->picture == "") {
                $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";
            } else {
                if ($photo_member_id == $user_id) {
                    $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                    $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                } else {
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$photo_member_id'");

                    foreach ($private_mem as $private) {

                        $favt_mem[] = $private->favourite_user_id;
                    }
                    if ($check_gender->make_private == 'Y') {
                        if (!in_array($user_id, $favt_mem)) {
                            $Mem_Image_path = plugins_url('dsp_dating/images/private-photo-pic.jpg');
                            $physical_image_path = ABSPATH . '/wp-content/plugins/'.'dsp_dating/images/private-photo-pic.jpg';
                        } else {
                            $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                            $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                        }
                    } else {
                        $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                        $physical_image_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $member_exist_picture->picture;
                    }
                }
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);

                if (file_exists($physical_image_path)) {

                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";
                }
            }
        } else {

            $Mem_Image_path = WPEE_IMG_URL . "/default-profile-img.jpg";
        }

        return $Mem_Image_path;
    }

}

/** Returns th profile picture of user partner
 * @param $photo_member_id
 * @param $gender
 * @param $private_photo
 * @param $path
 * @return string
 */
if (!function_exists('wpee_display_members_partner_photo')) {
    function wpee_display_members_partner_photo($photo_member_id, $gender, $private_photo, $path) {
        global $wpdb;

        $current_user_id = get_current_user_id();

        if ( $private_photo == 'Y' && $photo_member_id != $current_user_id ) {
            $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

            $is_favourite = $wpdb->get_results("SELECT * FROM {$dsp_user_favourites_table} WHERE user_id = '{$photo_member_id}' AND favourite_user_id = '{$current_user_id}'");

            if ( ! $is_favourite ) {
                return plugins_url('dsp_dating/images/private-photo-pic.jpg');
            }
        }

        $dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;

        $partner_member_photo = $wpdb->get_row("SELECT * FROM {$dsp_members_partner_photos_table} WHERE user_id='{$photo_member_id}' AND status_id=1");

        if ( $partner_member_photo && $photo_member_id == $current_user_id ){
            if ( file_exists(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $photo_member_id . "/thumbs/thumb_" . $partner_member_photo->picture) ){
                return $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs/thumb_" . $partner_member_photo->picture;
            }
        }

        switch ( $gender ){
            case 'M':
                return get_site_url() . "/wp-content/plugins/dsp_dating/images/male-generic.jpg";

            case 'F':
                return get_site_url() . "/wp-content/plugins/dsp_dating/images/female-generic.jpg";;

            case 'C':
                return get_site_url() . "/wp-content/plugins/dsp_dating/images/couples-generic.jpg";;

            default:
                return  WPEE_IMG_URL . "/default-profile-img.jpg";

        }
    }
}

/**
 * Get time difference in ago format
 *
 * @param string $date
 * @return array
 * @since 1.2.1
 */ 
if( ! function_exists('wpee_get_time_difference') ){
    function wpee_get_time_difference( $date ) {
        $now  = new DateTime();
        $date = new DateTime( $date );
    
        $difference = $now->diff( $date );
    
        if ( $difference->y > 0 ){
            return $difference->y . ' ' . __('year ago', 'wpdating');
        } 
    
        if ( $difference->m > 0 ) {
            return $difference->m . ' ' . __('month ago', 'wpdating');
        }
    
        if ( $difference->d > 0 ) {
            return $difference->d . ' ' . __('day ago', 'wpdating');
        }
    
        if ( $difference->h > 0 ) {
            return $difference->h . ' ' . __('hour ago', 'wpdating');
        }
    
        if ( $difference->i > 0 ) {
            return $difference->i . ' ' . __('min ago', 'wpdating');
        }
    
        return $difference->s . ' ' . __('sec ago', 'wpdating');
    }
}