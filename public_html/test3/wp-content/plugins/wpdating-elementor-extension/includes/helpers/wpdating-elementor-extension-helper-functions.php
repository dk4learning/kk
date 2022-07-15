<?php

/**
 * The helper function class.
 *
 * This is used to define all helper functions need in the plugins.
 *
 */
class Wpdating_Elementor_Extension_Helper_Functions {

    /**
     * This function is used to get object of members photos url or directory path with original and thumbs from dsp plugin
     *
     * @param int $user_id
     * @param string $image_name
     * @param boolean $private
     * @param boolean $is_fav
     * @param string $path_format
     * @return stdClass
     * @since 1.5.0
     */
    public static function members_photo_path( $user_id, $image_name, $private, $is_fav, $path_format = 'URL' ) {

        if ( $path_format == 'URL' ) {
            $root_path = get_site_url();
        } else {
            $root_path = ABSPATH;
        }

        $image_object = new stdClass();

        if ( $private && ! $is_fav ) {
            $image_object->image_orig = $image_object->image_250 = $image_object->image_350 = "{$root_path}/wp-content/plugins/dsp_dating/images/private-photo-pic.jpg";
            return $image_object;
        }

        if ( ! empty( $image_name ) && file_exists( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$user_id}/{$image_name}" ) ) {
            $image_object->image_orig = "{$root_path}/wp-content/uploads/dsp_media/user_photos/user_{$user_id}/{$image_name}";
            $image_object->image_350  = "{$root_path}/wp-content/uploads/dsp_media/user_photos/user_{$user_id}/thumbs/thumb_{$image_name}";
            $image_object->image_250  = "{$root_path}/wp-content/uploads/dsp_media/user_photos/user_{$user_id}/thumbs1/thumb_{$image_name}";
            return $image_object;
        }

        $image_object->image_orig = $image_object->image_250 = $image_object->image_350 = WPEE_IMG_URL . "/default-profile-img.jpg";

        return $image_object;
    }

    /**
     * This function is used to get setting value using setting name provided
     *
     * @return stdClass
     * @since 1.5.0
     */
    public static function get_settings() {
        global $wpdb;
        $general_settings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dsp_general_settings" );

        $general_setting_object = new stdClass();
        foreach ( $general_settings as $general_setting ) {
            $object_values = new stdClass();
            $object_values->name   = $general_setting->setting_name;
            $object_values->status = $general_setting->setting_status;
            $object_values->value  = $general_setting->setting_value;

            $general_setting_object->{$general_setting->setting_name} = $object_values;
        }

        return $general_setting_object;
    }

    /**
     * This function is used to get profile url by username
     *
     * @param string $username
     * @return string
     * @since 1.5.0
     */
    public static function get_profile_url_by_username( $username ) {
        $lang = get_locale();
        $lang = $lang ? $lang : 'en_US';
        $name = get_option( 'wpee_profile_page' )[$lang];
        $query = new WP_Query([
            "post_type" => 'page',
            "name"      => $name
        ]);

        $post = $query->get_posts()[0];
        $profile_page_url = get_permalink( $post->ID );
        return trailingslashit( $profile_page_url ) . $username;
    }

    /**
     * This function is used to get gender object key value
     *
     * @param string $male_mode
     * @param string $female_mode
     * @param string $couple_mode
     * @return stdClass
     * @since 1.5.0
     */
    public static function get_genders( $male_mode, $female_mode, $couple_mode ) {
        global $wpdb;

        $sql_query = "SELECT gender, enum FROM {$wpdb->prefix}dsp_gender_list";

        $conditions = [];
        if ( $male_mode == 'N' ) {
            $conditions[] = "enum != 'C'";
        }

        if ( $female_mode == 'N' ) {
            $conditions[] = "enum != 'C'";
        }

        if ( $couple_mode == 'N' ) {
            $conditions[] = "enum != 'C'";
        }

        if ( count( $conditions ) > 0 ) {
            $sql_query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $genders = $wpdb->get_results( $sql_query );

        $gender_objects = new stdClass();
        foreach ( $genders as $gender ) {
            $gender_objects->{$gender->enum} = __(addslashes($gender->gender) , 'wpdating');
        }
        return $gender_objects;
    }

    /**
     * This function is used to get gender options for select
     *
     * @param $genders
     * @param $selected_gender
     * @return string
     * @since 1.5.0
     */
    public static function get_gender_options( $genders, $selected_gender ) {

        $gender_options = '';
        foreach ( $genders as $key => $value ) {
            $gender_options .= "<option value='{$key}' " . ( $key == $selected_gender  ? "selected='selected'" : '') .">{$value}</option>";
        }
        return $gender_options;
    }

    /**
     * This function is used to get full name fo user
     *
     * @param int $user_id
     * @param string $default
     * @return string
     * @since 1.5.0
     */
    public static function get_full_name_by_user_id( $user_id, $default ) {

        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name  = get_user_meta( $user_id, 'last_name', true );

        if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
            return $first_name . ' ' . $last_name;
        } else if ( ! empty( $first_name ) ) {
            return $first_name;
        } else if ( ! empty( $last_name ) ) {
            return $last_name;
        } else {
            return $default;
        }
    }

    /**
     * This function is used to get city option using country id and state id
     *
     * @param int $country_id
     * @param int $state_id
     * @return string
     * @since 1.5.0
     */
    public static function get_city_by_country_and_state( $country_id, $state_id )
    {
        global $wpdb;
        return $wpdb->get_results("SELECT city_id, name FROm {$wpdb->prefix}dsp_city WHERE country_id = '{$country_id}' AND state_id = '{$state_id}'");
    }

    /**
     * This function is used to get user display name
     *
     * @param int $user_id
     * @param string $user_name
     * @return string
     * @since 1.5.0
     */
    public static function get_user_display_name( $user_id, $user_name ) {
        global $wpee_general_settings;

        if ( $wpee_general_settings->display_user_name->value == 'display_user_name' ) {
            return $user_name;
        }

        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name  = get_user_meta( $user_id, 'last_name', true );

        if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
            return $first_name . ' ' . $last_name;
        } else if ( ! empty( $first_name ) ) {
            return $first_name;
        } else if ( ! empty( $last_name ) ) {
            return $last_name;
        } else {
            return $user_name;
        }
    }

    /**
     * This function is used to return the members page url
     *
     * @return string
     * @since 1.6.0
     */
    public static function get_members_page_url() {
        $lang = get_locale();
        $lang = $lang ? $lang : 'en_US';
        $name = get_option( 'wpee_member_page' )[$lang];
        $query = new WP_Query([
            "post_type" => 'page',
            "name"      => $name
        ]);

        $post = $query->get_posts()[0];

        return get_permalink( $post->ID );
    }

    /**
     * This function is used to clear cache created by wp rocket
     *
     * @return void
     * @since 2.1.0
     */
    public static function clear_wp_rocket_cache() {
        if ( function_exists( 'rocket_clean_domain' ) ) {
            rocket_clean_domain();
        }
    }

    /**
     * Uploads the profile picture of the current user
     *
     * @param $src_image
     * @param $dest_image
     * @param int $thumb_size
     * @param int $jpg_quality
     * @return bool
     * @since 4.7.0
     */
    public static function square_image_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

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

        // Calculate measurements
        if ($image[0] & $image[1]) {
            // For landscape images
            $x_offset    = ($image[0] - $image[1]) / 2;
            $y_offset    = 0;
            $square_size = $image[0] - ($x_offset * 2);
        } else {
            // For portrait and square images
            $x_offset    = 0;
            $y_offset    = ($image[1] - $image[0]) / 2;
            $square_size = $image[1] - ($y_offset * 2);
        }

        // Resize and crop

        $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        if (imagecopyresampled(
            $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size,
            $square_size
        )) {

            // Create thumbnail
            switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($canvas, $dest_image, $jpg_quality);
                    break;
                case 'png':
                    return imagepng($canvas, $dest_image);
                    break;
                case 'gif':
                    return imagegif($canvas, $dest_image);
                    break;
                default:
                    // Unsupported format
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }
}