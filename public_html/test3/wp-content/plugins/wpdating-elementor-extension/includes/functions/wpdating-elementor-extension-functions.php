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
	 * Load Template
	 *
	 * load template using locate_template function
	 *
	 * @since    1.0.0
	 * @return array
	 */
	if( !function_exists( 'wpee_locate_template' )){
		function wpee_locate_template( $template_path ) {			
			if(locate_template (array($template_path))){
				locate_template (array('wpdating/'.$template_path), true );
			}
			else{
				include WPEE_PATH.'/templates/'. $template_path;
			}
		}
	}
	/**
	 * Get username by id
	 *
	 * @since    1.0.0
	 * @return string
	 */
	if( !function_exists( 'wpee_get_username_by_id' )){
		function wpee_get_username_by_id( $id ) {			
			$userdata = get_user_by( 'ID', $id );
			return $userdata->data->user_login;
		}
	}
	/**
	 * Get user profile display name by User Profile Display Name option in dsp_admin
	 *
	 * @since    1.0.0
	 * @return string
	 */
	if( !function_exists( 'wpee_get_user_display_name_by_id' )){
		function wpee_get_user_display_name_by_id( $id ) {
			$check_display_user_name = wpee_get_setting( 'display_user_name' );
			if( $check_display_user_name->setting_value == 'username' ){
				$username = wpee_get_username_by_id($id); // set user_login as username
			}
			else{
				$username = !empty(trim(wpee_get_user_fullname($id))) ? wpee_get_user_fullname($id) : get_username($id); // set user Fullname as username
			}
			return $username;
		}
	}
	 
	/**
	 * Get data by parameter
	 *
	 * @since    1.0.0
	 * @return object
	 */
	if( !function_exists( 'wpee_get_data' )){
		function wpee_get_data( $table_name, $key, $value ) {
			global $wpdb;
			$data = $wpdb->get_row("SELECT * FROM $table_name WHERE $key = '$value'");
			return $data;
		}
	}
	 
	/**
	 * Get photos by user id
	 *
	 * @since    1.0.0
	 * @return object
	 */
	if( !function_exists( 'wpee_get_photos' )){
		function wpee_get_photos( $args = array( ) ) {
			if( isset($args['user_id']) && !empty($args['user_id'])){
				if( isset($args['offset']) && !empty($args['offset'])){
					$offset = !empty($args['offset']) ? $args['offset'] : 0;
				}
				else{					
					$offset = 0;
				}
				if( isset($args['limit']) && !empty($args['limit'])){
					$limit = !empty($args['limit']) ? $args['limit'] : 6;
				}
				else{
					$limit = 6;
				}
				$user_id = $args['user_id'];
				global $wpdb;
				$table_name = $wpdb->prefix . 'dsp_galleries_photos';
				$data = $wpdb->get_results("SELECT image_name, album_id, date_added FROM $table_name WHERE user_id=$user_id Limit $offset,$limit");
				return $data;
			}
		}
	}

	/**
	 * Count data by parameter
	 *
	 * @since    1.0.0
	 * @return integer
	 */
	if( !function_exists( 'wpee_get_count_data' )){
		function wpee_get_count_data( $table_name, $key, $value ) {
			global $wpdb;
			$data = $wpdb->get_var("SELECT Count(*) FROM $table_name WHERE $key = '$value'");
			return $data;
		}
	}
	/**
	 * Get details for settings
	 *
	 * @since    1.0.0
	 * @return object
	 */
	if( !function_exists( 'wpee_get_setting' )){
		function wpee_get_setting( $setting_name ) {
			global $wpdb;
			$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
			$setting_data = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = '$setting_name'");
			return $setting_data;
		}
	}

	/**
	 * List countries
	 *
	 * List all the countries 
	 *
	 * @since    1.0.0
	 * @return array
	 */
	if( !function_exists( 'wpee_get_countries' )){
		function wpee_get_countries( ) {
			global $wpdb;
			$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
			$setting_data = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
			return $setting_data;
		}
	}

	/**
	 * List State
	 *
	 * List all the state of the specific country
	 *
	 * @since    1.0.0
	 * @return array
	 */
	if( !function_exists( 'wpee_get_states' )){
		function wpee_get_states( $country_id ) {
			global $wpdb;
   			$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
   			$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
			$setting_data = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table where country_id='$country_id'");
			return $setting_data;
		}
	}

	/**
	 * List City
	 *
	 * List all the cities of the specific country and state
	 *
	 * @since    1.0.0
	 * @return array
	 */
	if( !function_exists( 'wpee_get_cities' )){
		function wpee_get_cities( $country_id ) {
			global $wpdb;
   			$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
			$setting_data = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_city_table where country_id='$country_id'");
			return $setting_data;
		}
	}
	/**
	 * Get data to check whether user is online or not
	 *
	 * @since    1.0.0
	 * @return integer
	 */
	if( !function_exists( 'wpee_get_online_user' )){
		function wpee_get_online_user( $user_id ) {
			global $wpdb;
			$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
			$setting_data = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table where user_id='$user_id'");
			return $setting_data;
		}
	}
	/**
	 * Get Full name of user
	 *
	 * Get full name by object or username or id
	 * 
	 * @since    1.0.0
	 * @return integer
	 */
	if( !function_exists( 'wpee_get_user_fullname' )){
		function wpee_get_user_fullname( $user_id ) {			
			$user_firstname = get_user_meta( $user_id, 'first_name', true );// get the first name of the user as a string
			$user_lastname = get_user_meta( $user_id, 'last_name', true );// get the last name of the user as a string
			if( !empty(trim($user_firstname)) && !empty(trim($user_lastname))){
				$fulname = $user_firstname . ' ' . $user_lastname;
			}
			else{				
				$fulname = wpee_get_username_by_id( $user_id );
			}
		    return $fulname;
		}
	}

	/**
	 * Check friend list
	 *
	 * check whether users are friend or not.
	 * 
	 * @since    1.0.0
	 * @return integer
	 */
	if( !function_exists( 'wpee_get_check_friend' )){
		function wpee_get_check_friend( $user_id, $friend_id ) {
			global $wpdb;
			$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
			$check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$friend_id' AND user_id='$user_id' AND approved_status='Y'");
			return $check_my_friends_list;
		}
	}

	/**
	 * Get gender name by enum
	 *
	 * @since    1.0.0
	 * @return string
	 */

	///get User Age
	if (!function_exists('wpee_get_gender')) {

	    function wpee_get_gender( $enum ) {
	    	if( !empty( $enum ) ){
		        global $wpdb;
		        $table = $wpdb->prefix . 'dsp_gender_list';
		        $data = $wpdb->get_row("Select * from $table where enum='$enum'");
		        return $data->gender;
		    }
	    }

	}


	/**
	 * Get country name by id
	 *
	 * @since    1.0.0
	 * @return string
	 */

	///get User Age
	if (!function_exists('wpee_get_country_name')) {

	    function wpee_get_country_name( $country_id ) {
	    	if( !empty( $country_id ) ){
		        global $wpdb;
		        $country_table = $wpdb->prefix . 'dsp_country';
		        $country = $wpdb->get_row("Select * from $country_table where country_id=$country_id");
		        return $country->name;
		    }
	    }

	}

	/**
	 * Get state name by id
	 *
	 * @since    1.0.0
	 * @return string
	 */

	///get User Age
	if (!function_exists('wpee_get_state_name')) {

	    function wpee_get_state_name( $state_id ) {
	    	if( !empty( $state_id ) ){
		        global $wpdb;
		        $state_table = $wpdb->prefix . 'dsp_state';
		        $state = $wpdb->get_row("Select * from $state_table where state_id=$state_id");
		        return $state->name;
		    }
	    }

	}

	/**
	 * Get city name by id
	 *
	 * @since    1.0.0
	 * @return string
	 */

	///get User Age
	if (!function_exists('wpee_get_city_name')) {

	    function wpee_get_city_name( $city_id ) {
	    	if( !empty( $city_id ) ){
		        global $wpdb;
		        $city_table = $wpdb->prefix . 'dsp_city';
		        $city = $wpdb->get_row("Select * from $city_table where city_id=$city_id");
		        return $city->name;
		    }
	    }

	}


	/**
	 * Check Premium
	 *
	 * check if user is premium user or not
	 *
	 * @since    1.0.0
	 * @return string
	 */

	///get User Age
	if (!function_exists('wpee_is_premium')) {

	    function wpee_is_premium( $user_id ) {
	    	if( !empty( $user_id ) ){
		        global $wpdb;
		        $dsp_payments_table = $wpdb->prefix . 'dsp_payments';
		        $check_free_mode = wpee_get_setting('free_mode');
				$payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
				if ($check_free_mode->setting_status == 'Y' && isset($_SESSION['free_member']) && $_SESSION['free_member'] ) {
					return 'free';
				}
                elseif ($payment_row != null && strtotime($payment_row->expiration_date) > time()) {
					return 'premium';
				}
                else { 
                	return 'standard';
                } 
		    }
		    return 'standard';
	    }

	}



	/**
	 * Profile id
	 *
	 * Get profile id of the user. If user is login and can't find username in url, it returns id of loggedin user
	 * Else if it redirect to member page because user id can't be found to view profile
	 * Returns user id
	 * @since    1.0.0
	 * @return int
	 */

	///get User Age
	if (!function_exists('wpee_profile_id')) {

	    function wpee_profile_id( ) {
			$username = get_query_var('user_name');
			if( !empty($username) ){
				$userdata = get_user_by('login', $username);
				$user = $userdata->data;
				$user_id = $user->ID;
				if( empty($user_id) ){
					wp_redirect( home_url('/404.php') ); 
					exit;
				}
			}
			elseif( is_user_logged_in() ) {	
				$user_id= ( isset($user->ID) && !empty($user->ID) ) ? $user->ID : get_current_user_id() ;
			}
			else{
                $member_page_url = Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url();
				wp_redirect( $member_page_url );
				exit;
			}
			return $user_id;
	    }

	}

	/**
	 * Profile Url
	 *
	 * Get profile url of the user
	 * Returns url of user profile
	 * @since    1.0.0
	 * @return url
	 */

	///get User Age
	if (!function_exists('wpee_user_profile_url')) {

	    function wpee_user_profile_url( ) {
            $lang = get_locale();
            $lang = $lang ? $lang : 'en_US';
            $name = get_option( 'wpee_profile_page' )[$lang];
            $query = new WP_Query([
                "post_type" => 'page',
                "name"      => $name
            ]);

            $post = $query->get_posts()[0];

            $profile_page_url = get_permalink( $post->ID );
            $user_id          = wpee_profile_id();
            $username         = !empty( get_query_var('user_name') ) ? get_query_var('user_name') : wpee_get_username_by_id( $user_id );
            return trailingslashit( $profile_page_url ) . $username;
	    }

	}
	/**
	 * Profile Url by ID
	 *
	 * Get profile url of the user by id
	 * Returns url of user profile by id
	 * @since    1.0.0
	 * @return url
	 */

	///get User Age
	if (!function_exists('wpee_get_profile_url_by_id')) {

	    function wpee_get_profile_url_by_id( $user_id='' ) {
	    	if( !empty($user_id) ){
                $lang = get_locale();
                $lang = $lang ? $lang : 'en_US';
                $name = get_option( 'wpee_profile_page' )[$lang];
                $query = new WP_Query([
                    "post_type" => 'page',
                    "name"      => $name
                ]);

                $post = $query->get_posts()[0];

                $username         = wpee_get_username_by_id($user_id);
                $profile_page_url = get_permalink( $post->ID );
                return trailingslashit( $profile_page_url ) . $username;
			}
	    }
	}

    /**
     * Returns the user profile object
     *
     * @param $conditions array
     * @return object|null
     * @since    1.2.1
     */
    if (!function_exists('wpee_get_user_profile_by')) {
        function wpee_get_user_profile_by( $conditions )
        {
            global $wpdb;
            $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
            $str_conditions = '';
            $len_conditions = count($conditions);
            $i              = 1;
            foreach ( $conditions as $key => $value ){
                $str_conditions .= "{$key}='{$value}'";
                if ( $i < $len_conditions ){
                    $str_conditions .= " AND ";
                }
                $i++;
            }
            return $wpdb->get_row("SELECT * FROM {$dsp_user_profiles_table} WHERE {$str_conditions}");
        }
    }

    /**
     * Returns the user partner profile object
     *
     * @param $conditions array
     * @return object|null
     * @since    1.2.1
     */
    if (!function_exists('wpee_get_user_partner_profile_by')) {
        function wpee_get_user_partner_profile_by( $conditions )
        {
            global $wpdb;
            $dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
            $str_conditions = '';
            $len_conditions = count($conditions);
            $i              = 1;
            foreach ( $conditions as $key => $value ){
                $str_conditions .= "{$key}='{$value}'";
                if ( $i < $len_conditions ){
                    $str_conditions .= " AND ";
                }
                $i++;
            }
            return $wpdb->get_row("SELECT * FROM {$dsp_user_partner_profiles_table} WHERE {$str_conditions}");
        }
    }

	/**
	 * Get user name with profile link
	 *
	 * @since    1.0.0
	 * @return html
	 */

	///get User Age
	if (!function_exists('wpee_get_profile_link_by_id')) {

	    function wpee_get_profile_link_by_id( $user_id='' ) {
	    	if( !empty($user_id) ){
				$profile_url = wpee_get_profile_url_by_id($user_id); 
				$html = " <a href='" . $profile_url . "' >" . wpee_get_user_display_name_by_id( $user_id ) . "</a>";
				return $html;
			}
	    }

	}

    /**
     * Compress the image provided
     *
     * @param $src_image
     * @param $dest_image
     * @param int $thumb_size
     * @param int $jpg_quality
     * @return bool
     * @since 4.7.0
     */
    if (!function_exists('compress_image')) {
        function compress_image($src_image, $dest_image, $jpg_quality = 50)
        {
            // Get dimensions of existing image
            $image = getimagesize($src_image);

            // Check for valid dimensions
            if ($image[0] <= 0 || $image[1] <= 0) {
                return false;
            }

            // Determine format from MIME-Type
            $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

            // Import image
            switch ($image['format']) {
                case 'jpg':
                case 'jpeg':
                    $image_data = imagecreatefromjpeg($src_image);
                    break;
                case 'png':
                    $image_data = imagecreatefrompng($src_image);
                    break;
                case 'gif':
                    $image_data = imagecreatefromgif($src_image);
                    break;
                default:
                    // Unsupported format
                    return false;
                    break;
            }

            // Verify import
            if ($image_data == false) {
                return false;
            }

            // Create thumbnail
            switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($image_data, $dest_image, $jpg_quality);
                    break;
                case 'png':
                    return imagepng($image_data, $dest_image);
                    break;
                case 'gif':
                    return imagegif($image_data, $dest_image);
                    break;
                default:
                    // Unsupported format
                    return false;
                    break;
            }
        }
    }

    /**
     * Return the full user details
     *
     * @param int $user_id
     * @return array|object|void|null
     * @since 4.7.0
     */
    if ( ! function_exists( 'wpee_get_user_details_by_user_id' ) ) {
        function wpee_get_user_details_by_user_id( $user_id ) {
            global $wpdb;
            return $wpdb->get_row( "SELECT DISTINCT user.ID user_id, user.user_login user_name,
                    user.display_name user_display_name, user_profile.make_private user_photo_private,
                    user_profile.gender user_gender, user_profile.seeking user_seeking,
                    (year(CURDATE())-year(user_profile.age)) user_age,
                    country.name user_country, state.name state_name, city.name city_name,
                    members_photo.picture as user_image
                    FROM {$wpdb->users} user
                    JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                    ON user.ID = user_profile.user_id 
                    LEFT JOIN {$wpdb->prefix}dsp_members_photos members_photo
                    on user.ID = members_photo.user_id
                    LEFT JOIN {$wpdb->prefix}dsp_country country
                    on user_profile.country_id = country.country_id
                    LEFT JOIN {$wpdb->prefix}dsp_state state
                    on user_profile.state_id = state.state_id
                    LEFT JOIN {$wpdb->prefix}dsp_city city
                    on user_profile.city_id = city.city_id
                    WHERE user.ID = {$user_id}");
        }
    }


