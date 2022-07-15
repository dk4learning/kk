<?php
$profile_subtab   = get_query_var( 'profile-subtab' );
$profile_subtab   = !empty( $profile_subtab ) ? $profile_subtab : '';
$current_user     = wp_get_current_user();
$profile_link     = wpee_get_profile_url_by_id( $current_user->ID );
$user_profile     = wpee_get_user_profile_by( array( 'user_id' => $current_user->ID ) );
$main_user_gender = isset( $user_profile->gender ) ? $user_profile->gender : 'M';
/**
 * Validate the edit profile post
 *
 * Performs a couple of checks to profile form.
 *
 * @param array $request Full details about the input form request.
 * @param array $files Full details about the file request.
 * @return WP_Error|boolean True , if valid, otherwise an error.
 * @since 4.7.0
 */
if ( !function_exists('edit_profile_post_validation') ){
    function edit_profile_form_validation( $request, $files, $user_id, $dsp_profile_setup_table, $partner = false )
    {
        $response = [];

        if ( isset($request['gender']) && empty($request['gender']) ){
            $response['errors']['gender'] = __('Please select gender values.', 'wpdating');
        }

        if ( isset($request['seeking']) && empty($request['seeking']) ){
            $response['errors']['seeking'] = __('Please select seeking values.', 'wpdating');
        }

        if ( !isset($request['cmbCountry']) || ( isset($request['cmbCountry']) && $request['cmbCountry'] == '0' ) ){
            $response['errors']['cmbCountry'] = __('Please select country values.', 'wpdating');
        }

        if ( !isset($request['about_me']) || ( isset($request['about_me']) && empty($request['about_me']) ) ){
            $response['errors']['about_me'] = __('Please enter about me values.', 'wpdating');
        }

        global $wpdb;
        $profile_questions = $wpdb->get_results("SELECT * FROM {$dsp_profile_setup_table} WHERE required='Y'");

        foreach ( $profile_questions as $profile_question ) {
            $question_name = stripslashes($profile_question->question_name);
            switch ( $profile_question->field_type_id ) {

                case 1:
                    if ( $request['option_id'][$profile_question->profile_setup_id] == 0 ) {
                        $response['errors'][$question_name] = __('Please select ', 'wpdating') . $question_name . __(' values.' , 'wpdating');
                    }
                    break;

                case 2:
                    if ( empty( $request['option_id1'][$profile_question->profile_setup_id] ) ) {
                        $response['errors'][$question_name] = __('Please enter ', 'wpdating') . $question_name . __(' values.' , 'wpdating');
                    }
                    break;

                case 3:
                    if ( empty( $request['option_id2'][$profile_question->profile_setup_id] ) ) {
                        $response['errors'][$question_name] = __('Please select ', 'wpdating') . $question_name . __(' values.' , 'wpdating');
                    }
                    break;
            }
        }

        if ( ! $partner ){
            $dsp_members_photos_table      = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
            $dsp_tmp_members_photos_table  = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
            $user_profile_image_exists     = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_members_photos_table} WHERE user_id='{$user_id}'");
            $user_tmp_profile_image_exists = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_tmp_members_photos_table} WHERE t_user_id='{$user_id} AND t_status_id=0'");
            $check_force_photo_mode        = wpee_get_setting('force_photo');
            if ($check_force_photo_mode->setting_status == 'Y'
                && ( $user_profile_image_exists == 0  && $user_tmp_profile_image_exists == 0 )
                && empty( $files['photo_upload']['name'] ) ) {
                $response['errors']['photo_upload'] = __('Please choose profile photo.', 'wpdating');
            }
        }


        if ( !empty( $files['photo_upload']['name'] ) ) {
            if ($files['photo_upload']['error'] > 0) {
                $response['errors']['photo_upload'] = __('Error in file upload.', 'wpdating');
            }

            $image_type_list = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'];

            if (!in_array($files['photo_upload']['type'], $image_type_list)) {
                $response['errors']['photo_upload'] = __('Image type is not supported.', 'wpdating');
            }
        }

        return $response;
    }
}

if ( !function_exists('myinterest_cloud') ){
    function myinterest_cloud( $my_interest )
    {
        global $wpdb;
        $dsp_interest_tags_table = $wpdb->prefix . DSP_INTEREST_TAGS_TABLE;
        $strInterest             = $my_interest;
        $tag_array               = explode(",", strtolower(trim($strInterest)));

        for ($intCounter = 0; $intCounter < count($tag_array); $intCounter++) {
            $interest_tags_table = $wpdb->get_var("SELECT count(*) as ifExists FROM " . $dsp_interest_tags_table . " WHERE keyword = '" . strtolower(trim($tag_array[$intCounter])) . "'");
            if ($interest_tags_table == 0) {
                $strExecuteQuery = "INSERT INTO " . $dsp_interest_tags_table . " VALUES (0,'" . esc_sql(strtolower(trim($tag_array[$intCounter]))) . "',1,'NA')";
            } else {
                $strExecuteQuery = "UPDATE " . $dsp_interest_tags_table . " SET weight = weight+1 WHERE keyword = '" . esc_sql(strtolower(trim($tag_array[$intCounter]))) . "'";
            }
            $wpdb->query($strExecuteQuery);
        }
    }
}

if ( !function_exists('square_crop') ) {
    function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90)
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
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }
}

?>
    <div class="profile-section-wrap main-profile-mid-wrapper">
        <?php
        switch ( $profile_subtab ){
            case 'location':
                $latitude  = isset($_GET['lat']) ? $_GET['lat'] : '';
                $longitude = isset($_GET['lng']) ? $_GET['lng'] : '';
                $saved = false;
                if(isset($_GET['save'])){
                    if(!empty($latitude) && !empty($longitude)){
                        $position = array(
                            'lat' => $latitude,
                            'lng' => $longitude
                        );
                        $saved = apply_filters('dsp_savePosition',$position);
                    }
                }
                require_once 'partials/menu.php';
                require_once 'location/index.php';
                break;

            default:
                global $wpdb;

                $user_id            = $current_user->ID;
                $check_zipcode_mode = wpee_get_setting( 'zipcode_mode' );

                if (isset($_SESSION['default_lang'])) {
                    $language_id = $_SESSION['default_lang'];
                } else {
                    $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;

                    $language_id = $wpdb->get_var("SELECT language_id FROM {$dsp_session_language_table} where user_id='{$user_id}' ");
                }

                $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

                if ( $profile_subtab == 'partner' ){
                    $dsp_user_profiles_table      = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
                    $dsp_members_photos_table     = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
                    $dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PARTNER_PHOTOS_TABLE;
                    $dsp_question_details_table   = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
                } else {
                    $dsp_user_profiles_table      = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                    $dsp_members_photos_table     = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
                    $dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
                    $dsp_question_details_table   = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
                }

                $language_detail = $wpdb->get_row("SELECT * FROM {$dsp_language_detail_table} where language_id='{$language_id}'");
                $language_name   = ! empty($language_detail->language_name) ? $language_detail->language_name : 'english';
                if ($language_name == 'english') {
                    $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
                    $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
                } else {
                    $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE ."_" . strtolower(substr($table_name, 0, 2));
                    $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE . "_" . strtolower(substr($table_name, 0, 2));
                }

                if ( isset( $_POST['submit1'] ) && $_POST['mode'] == 'update' ) {

                    $form_validator = edit_profile_form_validation( $_POST, $_FILES, $user_id, $dsp_profile_setup_table, ( $profile_subtab == 'partner' ) );
                    $info_message   = [];

                    if ( ! isset( $form_validator['errors'] ) ) {

                        if ( !empty( $_FILES['photo_upload']['name'] ) ) {

                            if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id)) {

                                if (!file_exists('wp-content/uploads')) {
                                    mkdir('wp-content/uploads', 0777);
                                }
                                if (!file_exists('wp-content/uploads/dsp_media')) {
                                    mkdir('wp-content/uploads/dsp_media', 0777);
                                }
                                if (!file_exists('wp-content/uploads/dsp_media/user_photos')) {
                                    mkdir('wp-content/uploads/dsp_media/user_photos', 0777);
                                }
                                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);
                                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
                                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
                            } else if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs')) {
                                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
                                mkdir('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);
                            }

                            $filename = stripslashes($_FILES['photo_upload']['name']);

                            $img_name = $user_id . "_" . time() . '.' . pathinfo($_FILES['photo_upload']['name'], PATHINFO_EXTENSION);
                            $img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $img_name;
                            $copied = copy($_FILES['photo_upload']['tmp_name'], $img_path);
                            if ( ! $copied ) {
                                $file_validator['errors']['photo_upload'] = __('Error in file upload.', 'wpdating');
                            } else {
                                $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $img_name;
                                $thumb1 = square_crop($img_path, $thumb_name1, 250);

                                $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $img_name;
                                $thumb = square_crop($img_path, $thumb_name, 350);

                                $member_photo = $wpdb->get_row("SELECT * FROM {$dsp_members_photos_table} WHERE user_id='{$user_id}'");

                                if ( $member_photo ){
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $member_photo->picture);
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $member_photo->picture);
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $member_photo->picture);
                                    $wpdb->delete($dsp_members_photos_table, array( 'user_id' => $user_id ));
                                }

                                $tmp_members_photo = $wpdb->get_row("SELECT * FROM {$dsp_tmp_members_photos_table} WHERE t_user_id='{$user_id}'");

                                if ( $tmp_members_photo ){
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $tmp_members_photo->t_picture);
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $tmp_members_photo->t_picture);
                                    unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $tmp_members_photo->t_picture);
                                    $wpdb->delete($dsp_tmp_members_photos_table, array( 't_user_id' => $user_id ));
                                }

                                $check_approve_photos_status = wpee_get_setting('authorize_photos');
                                if ($check_approve_photos_status->setting_status == 'Y') {
                                    $photo_input  = [
                                        'user_id'   => $user_id,
                                        'picture'   => $img_name,
                                        'status_id' => 1
                                    ];

                                    $wpdb->insert($dsp_members_photos_table, $photo_input);

                                    dsp_delete_news_feed($user_id, 'profile_photo');
                                    dsp_add_news_feed($user_id, 'profile_photo');
                                    dsp_add_notification($user_id, 0, 'profile_photo');
                                } else {
                                    $photo_input       = [
                                        't_user_id'     => $user_id,
                                        't_picture'     => $img_name,
                                        't_status_id'   => 0
                                    ];

                                    $wpdb->insert($dsp_tmp_members_photos_table, $photo_input);

                                    $to_be_approved_subjects['picture'] = 'profile picture';
                                }
                            }
                        }

                        if ( ! isset( $form_validator['errors'] ) ) {
                            $profile_input = [
                                'seeking'               => trim( $_POST['seeking'] ),
                                'country_id'            => trim( $_POST['cmbCountry'] ),
                                'state_id'              => isset( $_POST['cmbState'] ) ? trim( $_POST['cmbState'] ) : 0,
                                'city_id'               => isset( $_POST['cmbCity'] ) ? trim( $_POST['cmbCity'] ) : 0,
                                'age'                   => trim( $_POST['dsp_year'] ) . "-" . trim( $_POST['dsp_mon'] ) . "-" . trim( $_POST['dsp_day'] ),
                                'about_me'              => trim( $_POST['about_me'] ),
                                'my_interest'           => ( isset( $_POST['my_interest'] ) && !empty( trim( $_POST['my_interest'] ) ) ) ? trim( $_POST['my_interest'] ) : '',
                                'last_update_date'      => date('Y-m-d H:i:s'),
                                'make_private'          => isset( $_POST['private'] ) ? $_POST['private'] : 'N',
                            ];

                            if ( $profile_subtab != 'partner' ){
                                $profile_input['make_private_profile'] = isset( $_POST['make_private_profile'] ) ? $_REQUEST['make_private_profile'] : 0;
                            }

                            if ( isset($_POST['gender'] ) ) {
                                $profile_input['gender'] = trim( $_POST['gender'] );
                            }

                            if ($check_zipcode_mode->setting_status == 'Y') {
                                $profile_input['zipcode'] = ( isset( $_POST['zip'] ) && !empty( trim( $_POST['zip'] ) ) ) ? trim( $_POST['zip'] ) : '';
                            }

                            $check_approve_profile_status   = wpee_get_setting( 'authorize_profiles' );

                            if ( $check_approve_profile_status->setting_status == 'Y' ) {
                                $profile_input['status_id'] = 1;
                            } else {
                                $profile_input['status_id'] = 0;
                                $to_be_approved_subjects['profile']  = 'profile';
                            }

                            $profile_exists          = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_user_profiles_table} WHERE user_id={$user_id}");

                            $site_url = get_site_url();
                            $to       = get_option('admin_email');
                            $from     = $to;
                            $headers  = __('From', 'wpdating') . $from . "\r\nContent-type: text/html; charset=us-ascii\n";

                            if ($profile_exists) {
                                $wpdb->update($dsp_user_profiles_table, $profile_input, array('user_id' => $user_id));

                                $subject = "New profile created";
                                $message = "User '{$current_user->user_login}' has edited a profile. You can view their profile by <a href='{$site_url}/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profile_view&mode=edit&profile_id={$user_id}'>clicking here</a>";
                                wp_mail($to, $subject, $message, $headers);
                            } else {
                                $profile_input['user_id'] = $user_id;

                                $wpdb->insert($dsp_user_profiles_table, $profile_input);

                                $subject = "New profile created";
                                $message = "User '{$current_user->user_login}' has created a profile. You can view their profile by <a href='{$site_url}/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_profile_view&mode=edit&profile_id={$user_id}'>clicking here</a>";

                                wp_mail($to, $subject, $message, $headers);
                            }

                            if ( isset($_POST['my_interest']) && !empty($_POST['my_interest'])) {
                                myinterest_cloud($_POST['my_interest']);
                            }

                            $question_detail_exists = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_question_details_table} WHERE user_id='{$user_id}'");
                            if ( $question_detail_exists ) {
                                $wpdb->delete( $dsp_question_details_table, array( 'user_id' => $user_id ) );
                            }

                            if ( isset( $_POST['option_id'] ) ) {
                                foreach ($_POST['option_id'] as $key => $value) {
                                    if ( $value != 0 ) {
                                        $option_value = $wpdb->get_var("SELECT `option_value` FROM {$dsp_question_options_table} WHERE question_option_id = '{$value}'");
                                        $wpdb->insert($dsp_question_details_table, array(
                                            'user_id'                    => $user_id,
                                            'profile_question_id'        => $key,
                                            'profile_question_option_id' => $value,
                                            'option_value'               => $option_value
                                        ));
                                    }
                                }
                            }

                            if ( isset( $_POST['option_id1'] )) {
                                foreach ( $_POST['option_id1'] as $key => $value ) {
                                    if ( !empty( $value ) ) {
                                        $wpdb->insert( $dsp_question_details_table, array(
                                            'user_id'                    => $user_id,
                                            'profile_question_id'        => $key,
                                            'profile_question_option_id' => 0,
                                            'option_value'               => esc_sql($value)
                                        ));
                                    }
                                }
                            }

                            if ( isset( $_POST['option_id2'] ) ) {
                                $question_option_id2 = apply_filters('dsp_filter_empty_array_values', $_POST['option_id2']);
                                foreach ($question_option_id2 as $k => $values) {
                                    if ( !empty( $values ) ) {
                                        foreach ($values as $key => $value) {
                                            $option_value = $wpdb->get_var("SELECT `option_value` FROM {$dsp_question_options_table} WHERE question_option_id = '{$value}'");
                                            $wpdb->insert( $dsp_question_details_table, array(
                                                'user_id'                    => $user_id,
                                                'profile_question_id'        => $k,
                                                'profile_question_option_id' => $value,
                                                'option_value'               => $option_value
                                            ));
                                        }
                                    }
                                }
                            }
                            
                            if ( isset( $to_be_approved_subjects['profile'] )  && isset( $to_be_approved_subjects['picture'] ) ) {
                                $to_be_approved_subjects     = array_reverse( $to_be_approved_subjects );
                                $to_be_approved_subjects_str = implode( ' and ', $to_be_approved_subjects );
                                $info_messages[] = __( "Your {$to_be_approved_subjects_str} will be updated within 24 hours.", 'wpdating' );
                            } else if ( isset( $to_be_approved_subjects['picture'] ) ) {
                                $info_messages[] = __( "Your {$to_be_approved_subjects['picture']} will be updated within 24 hours.", 'wpdating' );
                            }

                            if ( ! isset( $to_be_approved_subjects['profile'] ) ) {
                                $success_messages[] = __( 'Your profile has been updated.', 'wpdating' );
                            }

                            Wpdating_Elementor_Extension_Helper_Functions::clear_wp_rocket_cache();
                        }
                    }

                    $user_profile = wpee_get_user_profile_by( array( 'user_id' => $current_user->ID ) );
                }

                if ( $profile_subtab == 'partner' ){
                    $user_profile = wpee_get_user_partner_profile_by( array( 'user_id' => $current_user->ID ) );
                } else {
                    $main_user_gender = ( isset( $_POST['gender'] ) && !empty( $_POST['gender'] ) ) ? $_POST['gender'] : ( isset( $user_profile->gender ) ? $user_profile->gender : 'M' );
                }

                require_once 'partials/menu.php';
                require_once 'profile/index.php';
        }
        ?>
    </div>
<?php

