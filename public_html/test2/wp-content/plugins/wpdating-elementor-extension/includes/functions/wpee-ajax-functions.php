<?php

/**
 * Define ajax functions for Wpdating_Elementor_Extension
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

/* 
Ajax Member listing
*/
add_action( "wp_ajax_wpee_member_list_ajax_filter", "wpee_member_list_ajax_filter" ); 
add_action( "wp_ajax_nopriv_wpee_member_list_ajax_filter", "wpee_member_list_ajax_filter" );

function wpee_member_list_ajax_filter(){
    ob_start();
    wpee_locate_template('member/member_list_ajax.php');
    $output['content'] = ob_get_clean();
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    $output['page'] = $page + 1;
    if(empty($output['content'])){
        $output['class'] = 'no-results';
    }
    echo json_encode( $output );
    die();
} 
/* 
Ajax Member listing by username search
*/
add_action( "wp_ajax_wpee_member_list_ajax_username", "wpee_member_list_ajax_username" ); 
add_action( "wp_ajax_nopriv_wpee_member_list_ajax_username", "wpee_member_list_ajax_username" );

function wpee_member_list_ajax_username(){
    ob_start();
    wpee_locate_template('member/member_list_ajax_username.php');
    $output = ob_get_clean();
    echo json_encode( $output );
    die();
} 

/* 
Update Cover Photo
Ajax function
*/
add_action( "wp_ajax_wpee_profile_cover_photo", "wpee_profile_cover_photo" ); 

function wpee_profile_cover_photo(){
    $response['msg'] = __('Failed to upload cover photo', 'wpdating');
    // check security nonce which one we created in html form and sending with data.
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if ( ! isset( $nonce ) 
    || ! wp_verify_nonce( $nonce, 'wpee_profile_cover_nonce' ) 
    ) {
       exit;
    }
    $user_id = get_current_user_id();
    $output['status'] = 'error';
    if (!file_exists(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id)) {

        if (!file_exists(ABSPATH . '/wp-content/uploads')) {
            mkdir(ABSPATH . '/wp-content/uploads', 0777);
        }
        if (!file_exists('wp-content/uploads/dsp_media')) {
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media', 0777);
        }
        if (!file_exists('wp-content/uploads/dsp_media/user_photos')) {
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos', 0777);
        }

        // it will default to 0755 regardless
        $status = mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);
        mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo', 0755);
        // Finally, chmod it to 777
        chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);
        chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo', 0777);
    } else if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo')) {
        mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo', 0755);

        chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo', 0777);
    }

    $extension = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    $fileinfo = @getimagesize($_FILES['file']["tmp_name"]);
    $width = $fileinfo[0];
    $height = $fileinfo[1];
    if ( !in_array($_FILES['file']['type'], $extension) ) {
        $response['msg'] = 'Unknown extension!';
    }
    elseif($width < "1920" || $height < "400") {
        $response['msg']    = __('Image should be 1920 * 400 or greater.', 'wpdating');
    } else {
        switch ($_FILES['file']['type']) {
            case 'image/jpg':
                $new_name = 'image_' . time() . '.jpg';
                break;
            case 'image/jpeg':
                $new_name = 'image_' . time() . '.jpeg';
                break;
            case 'image/png':
                $new_name = 'image_' . time() . '.png';
                break;
            case 'image/gif':
                $new_name = 'image_' . time() . '.gif';
                break;
        }
      
        $image_link = content_url('/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo/' . $new_name );
        $newname = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/cover_photo/" . $new_name;
        $copied = compress_image($_FILES['file']['tmp_name'], $newname);
        if (!$copied) {
            $response['msg'] = __('Something went wrong!', 'wpdating');
        } else {
            global $wpdb;
            $dsp_members_cover_photos  = $wpdb->prefix . 'dsp_members_cover_photos';
            $my_img = $wpdb->get_row("select * from $dsp_members_cover_photos where user_id=$user_id",
            ARRAY_A);
            if (!is_null($my_img)){
                $wpdb->query("UPDATE $dsp_members_cover_photos SET picture = '$new_name',status_id=1 WHERE user_id  = '$user_id'");
                unlink(ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/cover_photo/" . $my_img['picture']);
            } else {
                $wpdb->query("INSERT INTO $dsp_members_cover_photos SET picture = '$new_name',status_id=1,user_id='$user_id'");
            } //  if($count_rows>0)
            include_once(WP_DSP_ABSPATH . "/files/includes/functions.php");
            dsp_delete_news_feed($user_id, 'cover_photo');
            dsp_add_news_feed($user_id, 'cover_photo');
            dsp_add_notification($user_id, 0, 'cover_photo');
            $response['msg']    = __('Cover Photo has been updated.', 'wpdating');
            $response['image_path'] = $image_link;
            $response['img_path'] = $image_link;
            $response['status'] = 'success';
        }
    }

    die(json_encode($response));
} 



/* 
Delete Friend
Ajax function
*/
add_action( "wp_ajax_wpee_delete_friend", "wpee_delete_friend" ); 
function wpee_delete_friend(){
    global $wpdb;
    $dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
    $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
    $del_friend_Id = isset( $_REQUEST['friend'] ) ? $_REQUEST['friend'] : '';
    $user_id = get_current_user_id();
    $response['status'] = 'error';
    $response = array();
    if ( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_delete_friend_nonce' ) ) {
        $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id");
        $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$user_id' AND user_id=$del_friend_Id");

        $response['friend_uid'] = $del_friend_Id;
        $response['status'] = 'success';
        $response['msg'] = __( 'Deleted', 'wpdating' );
    }else{        
        $response['msg'] = __( 'Failed to delete friend', 'wpdating' );
    }
    die(json_encode($response));
} 


/* 
Remove Favorites
Ajax function
*/
add_action( "wp_ajax_wpee_remove_favourites", "wpee_remove_favourites" ); 
function wpee_remove_favourites(){
    global $wpdb;
    $dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';
    $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
    $del_fav = isset( $_REQUEST['fav'] ) ? $_REQUEST['fav'] : '';
    $user_id = get_current_user_id();

    if ( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_remove_favourites_nonce' ) ) {
        
        $result = $wpdb->query("DELETE FROM $dsp_user_favourites_table where favourite_id = '$del_fav'");
        $response['empty_msg'] = __('Currently,you have no Favorites!', 'wpdating');
        if( $result ){
            $response['fav_uid'] = $del_fav;
            $response['status'] = 'success';
            $response['msg'] = __( 'Deleted', 'wpdating' );
            $response['empty_msg'] = __('Currently,you have no Favorites!', 'wpdating');
        }
    } 

    die(json_encode($response));
} 

/* 
Remove Favorites
Ajax function
*/
add_action( "wp_ajax_wpee_add_favourites", "wpee_add_favourites" ); 
function wpee_add_favourites(){
    global $wpdb;
    $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
    $fav_user_id = isset( $_REQUEST['fav'] ) ? $_REQUEST['fav'] : '';
    $response['msg'] = __('Failed to add favorites', 'wpdating');
    if( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_add_favourites_nonce') && !empty( $fav_user_id ) ){
        $user_id = get_current_user_id();
        $dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';
        $dsp_notification = $wpdb->prefix . DSP_NOTIFICATION_TABLE;  
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $exist_fav_user_screenname = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$fav_user_id'");
        $fav_screenname = $exist_fav_user_screenname->display_name;
        $exist_fav_user_title = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$fav_user_id'");
        $fav_title = isset($exist_fav_user_title->title) ? $exist_fav_user_title->title : '';
        $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_favourites_table WHERE user_id=$user_id AND favourite_user_id=$fav_user_id");

        if ($num_rows <= 0) {
            $wpdb->query("INSERT INTO $dsp_user_favourites_table SET user_id = $user_id,favourite_user_id ='$fav_user_id' ,fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N'");
            dsp_add_notification($user_id, $fav_user_id, 'add_favourites');


            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='9'");
            $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$fav_user_id'");
            $reciver_name = wpee_get_user_display_name_by_id($fav_user_id);
            $receiver_email_address = $reciver_details->user_email;
            $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");  
            $sender_name = wpee_get_user_display_name_by_id($user_id);
            $email_subject = $email_template->subject;
            $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
            $mem_email_subject = $email_subject;

            $email_message = $email_template->email_body;
            $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
            $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

            $MemberEmailMessage = $email_message;
            $admin_email = get_option('admin_email');
            $from = $admin_email;
            // wp_mail($receiver_email_address, $mem_email_subject, $MemberEmailMessage);
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );


            $print_msg = __('Profile added as Favorites!', 'wpdating');
        } else {
            $print_msg = __('Already added in your favorites list!', 'wpdating');
            // $wpdb->query("UPDATE $dsp_user_favourites_table SET fav_screenname='$fav_screenname',fav_date_added='$date',fav_title='$fav_title',fav_private='N' where user_id=$session_user_id AND favourite_user_id=$fav_user_id");
        }
    }    
    $response['msg'] = $print_msg;
    die(json_encode($response));
} 

/* 
Send Wink ajax
*/
$check_flirt_module = wpee_get_setting('flirt_module');
if( $check_flirt_module->setting_value == 'Y'){
    add_action( "wp_ajax_wpee_send_wink", "wpee_send_wink" ); 
    function wpee_send_wink(){
        global $wpdb;
        // $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
        $receiver_id = isset($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : '';
        $wink_text_id = isset($_REQUEST['wink_text_id']) ? $_REQUEST['wink_text_id'] : '';
        $response['msg'] = __('Failed to send wink', 'wpdating');
        $sender_id = get_current_user_id();
        if( !empty( $receiver_id) && !empty($wink_text_id) && $sender_id ){
            $response['test'] = 'test';
            $dateTimeFormat = dsp_get_date_timezone();
            $send_date = date("Y-m-d H:m:s");
            $dsp_member_winks_table = $wpdb->prefix . 'dsp_member_winks';
            $dsp_blocked_members_table = $wpdb->prefix . 'dsp_blocked_members';
            $dsp_email_templates_table = $wpdb->prefix . 'dsp_email_templates';
            $dsp_flirt_table = $wpdb->prefix . 'dsp_flirt';
            $dsp_user_table = $wpdb->users;        
            if(dsp_issetGivenEmailSetting($receiver_id,'wink')){
                // Checked member is in user blocked list
                $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$receiver_id AND block_member_id='$sender_id'");
                $response['che'] = $checked_block_member;
                //If there is no error, then Message sent
                if ( $checked_block_member == 0 ) {
                    $receiver_id = trim($receiver_id);
                    $result = $wpdb->query("INSERT INTO $dsp_member_winks_table SET sender_id='$sender_id',receiver_id='$receiver_id',wink_id='$wink_text_id',send_date='$send_date',wink_read='N'");
                    if( $result ){
                        dsp_add_notification($sender_id, $receiver_id, 'send_wink');

                        $wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID='$wink_text_id'");
                        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='1'");
                        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$receiver_id'");
                        $reciver_name = wpee_get_user_display_name_by_id($receiver_id);
                        $receiver_email_address = $reciver_details->user_email;
                        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$sender_id'");
                        $sender_name = wpee_get_user_display_name_by_id($sender_id);
                        
                        $profile_page = get_option( 'wpee_profile_page', '' );
                        $profile_page_url = get_permalink( $profile_page ); 
                        $url = wpee_get_profile_link_by_id($sender_id);
                        $email_subject = $email_template->subject;
                        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
                        $mem_email_subject = $email_subject;

                        $email_message = $email_template->email_body;
                        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                        $email_message = str_replace("<#WINK_MESSAGE#>", $wink_message->flirt_Text, $email_message);
                        $email_message = str_replace("<#URL#>", $url, $email_message);
                        $MemberEmailMessage = $email_message;
                        // dsp_send_email($receiver_email_address, get_option('admin_email'), $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
                        
                        $wpdating_email  = Wpdating_email_template::get_instance();
                        $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
                        $message_sent = true;                
                        $response['msg'] = __( "Wink Sent", "wpdating-elementor-extension");
                    }
                }
                else{
                    $response['status'] = "blocked";
                    $response['msg'] = __( "Sorry, you can't sent wink to this user.", "wpdating-elementor-extension");
                }
            }    
        }
        die(json_encode($response));
    } 
}

/**
 * This function handles tha ajax request to get activity and profile Feeds
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'wpee_profile_activity_feeds' ) ){
    function wpee_profile_activity_feeds() {
        global $wpdb;
        ob_start();
        $output['class']= 'feeds';

        $page    = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : 1;
        $type    = isset( $_REQUEST['feed_type'] ) ? $_REQUEST['feed_type'] : 'activity';
        $user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : 0;
        $limit   = isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : 8;
        $offset  = ($page - 1) * $limit;

        if( $type == 'profile' ){
            $sql_query = "SELECT * FROM {$wpdb->prefix}dsp_news_feed
                WHERE feed_type NOT IN('login', 'logout') AND user_id = {$user_id} ORDER BY datetime DESC LIMIT {$offset}, {$limit}";
        } else{
            $sql_query = "SELECT feed.feed_type, feed.feed_type_id, feed.datetime, favourite.favourite_user_id AS user_id
                        FROM {$wpdb->prefix}dsp_news_feed AS feed
                        JOIN {$wpdb->prefix}dsp_favourites_list AS favourite
                        ON feed.user_id = favourite.favourite_user_id
                        WHERE feed.feed_type != 'login' AND feed.feed_type != 'logout' AND favourite.user_id = {$user_id}";

            $sql_query1 = "SELECT feed.feed_type, feed.feed_type_id , feed.datetime, friend.friend_uid AS user_id 
                        FROM {$wpdb->prefix}dsp_news_feed AS feed
                        JOIN {$wpdb->prefix}dsp_my_friends AS friend
                        ON feed.user_id = friend.friend_uid
                        WHERE feed.feed_type != 'login' AND feed.feed_type != 'logout' AND friend.user_id = {$user_id} 
                        OR feed.feed_type != 'login' AND feed.feed_type != 'logout' AND friend.friend_uid = {$user_id}";

            $sql_query = "($sql_query)
                      UNION ($sql_query1)
                      ORDER BY datetime DESC LIMIT {$offset}, {$limit}";
        }

        $feeds_count = $wpdb->get_var("SELECT COUNT(*) FROM ({$sql_query}) AS total");

        if ( $feeds_count == 0 ) {
            if ( $offset == 0 ) {
                $output['class']= 'no-feeds'; ?>
                <h4><?php echo __( 'No feeds', 'wpdating' ); ?></h4>
                <?php
                $output['content']= ob_get_clean();
            } else {
                $output['class']= 'no-more-feeds';
            }

            die(json_encode($output));
        }

        $refined_feeds = [];
        $feeds_count   = 0;

        $feeds = $wpdb->get_results( $sql_query );
        foreach ( $feeds as $feed ) {
            if ( $feed->feed_type == 'audio' and $feed->feed_type_id ) {
                $feed_data = $wpdb->get_row("SELECT member_audio.file_name audio_file_name,
                                                        user.ID user_id, user.user_login user_name,
                                                        user_profile.make_private user_photo_private, 
                                                        member_photo.picture as user_image
                                                        FROM {$wpdb->prefix}dsp_member_audios member_audio
                                                        JOIN {$wpdb->users} user 
                                                        ON member_audio.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                                                        ON user.ID = user_profile.user_id
                                                        LEFT JOIN {$wpdb->prefix}dsp_members_photos member_photo
                                                        ON user.ID = member_photo.user_id
                                                        WHERE member_audio.audio_file_id={$feed->feed_type_id} AND member_audio.private_audio = 'N'
                                                        AND member_audio.status_id = 1");
                $feed_type = 'audio';
            } else if ( $feed->feed_type == 'video' and $feed->feed_type_id ) {
                $feed_data = $wpdb->get_row("SELECT member_video.file_name video_file_name,
                                                        user.ID user_id, user.user_login user_name,
                                                        user_profile.make_private user_photo_private,
                                                        member_photo.picture as user_image
                                                        FROM {$wpdb->prefix}dsp_member_videos member_video
                                                        JOIN {$wpdb->users} user 
                                                        ON member_video.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                                                        ON user.ID = user_profile.user_id
                                                        LEFT JOIN {$wpdb->prefix}dsp_members_photos member_photo
                                                        ON user.ID = member_photo.user_id
                                                        WHERE member_video.video_file_id = {$feed->feed_type_id} AND member_video.private_video = 'N' 
                                                        AND member_video.status_id = 1");
                $feed_type = 'video';
            } else if ( $feed->feed_type == 'gallery_photo' and $feed->feed_type_id ) {
                $feed_data = $wpdb->get_row("SELECT gallery_photo.image_name gallery_image_name, gallery_photo.album_id gallery_album_id,
                                                        user.ID user_id, user.user_login user_name,
                                                        user_profile.make_private user_photo_private, 
                                                        member_photo.picture as user_image
                                                        FROM {$wpdb->prefix}dsp_galleries_photos gallery_photo
                                                        JOIN {$wpdb->users} user 
                                                        ON gallery_photo.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                                                        ON user.ID = user_profile.user_id
                                                        LEFT JOIN {$wpdb->prefix}dsp_members_photos member_photo
                                                        ON user.ID = member_photo.user_id
                                                        WHERE gallery_photo.gal_photo_id = {$feed->feed_type_id} 
                                                        AND gallery_photo.status_id = 1");
                $feed_type = 'gallery_photo';
            } else if ( $feed->feed_type == 'profile_photo' ) {
                $feed_data = $wpdb->get_row("SELECT user.ID user_id, user.user_login user_name,
                                                        member_photo.picture as user_image,
                                                        user_profile.make_private user_photo_private
                                                        FROM {$wpdb->prefix}dsp_members_photos member_photo
                                                        JOIN {$wpdb->users} user 
                                                        ON member_photo.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                                                        ON user.ID = user_profile.user_id
                                                        WHERE user.ID = {$feed->user_id} AND user_profile.make_private = 'N'");
                $feed_type = 'profile_photo';
            } else if ( $feed->feed_type == 'status' ) {
                $feed_data = $wpdb->get_row("SELECT user.ID user_id, user.user_login user_name,
                                                        user_profile.my_status profile_my_status,
                                                        user_profile.make_private user_photo_private,
                                                        member_photo.picture as user_image
                                                        FROM {$wpdb->prefix}dsp_user_profiles user_profile
                                                        JOIN {$wpdb->users} user 
                                                        ON user_profile.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_members_photos member_photo
                                                        ON user.ID = member_photo.user_id
                                                        WHERE user.ID = {$feed->user_id}");
                $feed_type = 'status';
            } else if ( $feed->feed_type == 'cover_photo' ) {
                $feed_data = $wpdb->get_row("SELECT user.ID user_id, user.user_login user_name,
                                                        user_profile.make_private user_photo_private,
                                                        cover_photo.picture as cover_image,
                                                        member_photo.picture as user_image
                                                        FROM {$wpdb->prefix}dsp_members_cover_photos cover_photo
                                                        JOIN {$wpdb->users} user 
                                                        ON cover_photo.user_id = user.ID
                                                        JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                                                        ON user.ID = user_profile.user_id
                                                        LEFT JOIN {$wpdb->prefix}dsp_members_photos member_photo
                                                        ON user.ID = member_photo.user_id
                                                        WHERE user.ID = {$feed->user_id}");
                $feed_type = 'cover_photo';
            }

            if ( $feed_data ) {
                $refined_feeds[$feeds_count]            = $feed_data;
                $refined_feeds[$feeds_count]->feed_type = $feed_type;
                $refined_feeds[$feeds_count]->datetime  = $feed->datetime;
                $feeds_count++;
            }
        }

        if ( $feeds_count == 0 ) {
            if ( $offset == 0 ) {
                $output['class']= 'no-feeds'; ?>
                <h4><?php echo __( 'No feeds', 'wpdating' ); ?></h4>
                <?php
                $output['content']= ob_get_clean();
            } else {
                $output['class']= 'no-more-feeds';
            }

            die(json_encode($output));
        } ?>
        <ul>
            <?php
            $general_settings = Wpdating_Elementor_Extension_Helper_Functions::get_settings();
            $site_url         = get_bloginfo( 'url' );
            foreach ( $refined_feeds as $refined_feed ) {
                $profile_link      = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $refined_feed->user_name );
                $user_display_name = ( $general_settings->display_user_name->value == 'display_user_name' )
                    ? $refined_feed->user_name :
                    Wpdating_Elementor_Extension_Helper_Functions::get_full_name_by_user_id( $refined_feed->user_id, $refined_feed->user_name );
                $user_image        = Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $refined_feed->user_id, $refined_feed->user_image,
                    $refined_feed->user_photo_private, 'N' );
                ?>
                <li class='wpee-feed-list'>
                    <?php
                    switch ( $refined_feed->feed_type ) {
                        case 'audio': ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( 'audio.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <div class="audio-box news_feed_audio_box ">
                                    <p>
                                        <span class="fa fa-music"></span>
                                    </p>
                                    <audio controls name="media" class="dsp-spacer" >
                                        <source src='<?php echo $site_url . "/wp-content/uploads/dsp_media/user_audios/user_" . $refined_feed->user_id . "/" . $refined_feed->audio_file_name; ?>' type="audio/mp3">
                                    </audio>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'video': ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( 'video.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <div class='video-box'>
                                    <video id='sampleMovie' src='<?php echo $site_url . "/wp-content/uploads/dsp_media/user_videos/user_" . $refined_feed->user_id . "/" . $refined_feed->video_file_name; ?>'
                                           controls width="200" height="200" scale="tofit" ></video>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'gallery_photo':
                            $image_path        = $site_url . '/wp-content/uploads/dsp_media/user_photos/user_' . $refined_feed->user_id . '/album_' . $refined_feed->gallery_album_id . '/' . $refined_feed->gallery_image_name; ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( 'photo.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <a rel='example_group' href='<?php echo $image_path; ?>'>
                                    <span class="image-bg" style="background-image: url('<?php echo $image_path; ?>');"></span>
                                </a>
                            </div>

                            <?php
                            break;

                        case 'profile_photo': ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( 'profile picture.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <a rel='example_group' href='<?php echo $user_image->image_350; ?>'>
                                    <span class="image-bg" style="background-image: url('<?php echo $user_image->image_350; ?>');"></span>
                                </a>
                            </div>
                            <?php
                            break;

                        case 'status': ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( 'status update.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <div class='status-box'>
                                    <span class='status-wrap'><?php echo esc_html( $refined_feed->profile_my_status );?></span>
                                </div>
                            </div>
                            <?php
                            break;

                        case 'cover_photo':
                            $image_link        = content_url('/uploads/dsp_media/user_photos/user_' . $feed->user_id . '/cover_photo/' . $refined_feed->cover_image ); ?>
                            <div class='feed-header d-flex align-center'>
                                <div class="feed-profile-img">
                                    <a href='<?php echo $profile_link; ?>'>
                                        <img src='<?php echo $user_image->image_350; ?>' alt='<?php echo $user_display_name; ?>'/>
                                    </a>
                                </div>
                                <div class="feed-title-wrap">
                                    <div class="feed-title">
                                        <strong><a href='<?php echo $profile_link ;?>'><?php echo $user_display_name; ?> </a> </strong>
                                        <?php echo __( 'just added new', 'wpdating' ); ?>
                                        <strong><?php echo __( ' cover photo.', 'wpdating' ); ?> </strong>
                                    </div>
                                    <div data-time='<?php echo esc_attr( $refined_feed->datetime ); ?>'>
                                        <?php echo esc_html( date ("d M", strtotime( $refined_feed->datetime ) ) ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class='feed-content'>
                                <a rel='example_group' href='<?php echo $image_link; ?>'>
                                    <span class="image-bg" style="background-image: url('<?php echo $image_link; ?>');"></span>
                                </a>
                            </div>
                            <?php
                            break;
                    } ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
        //code for pagination
        $output['page']   = $page + 1;
        $output['content']= ob_get_clean();

        die(json_encode($output));
    }
}
add_action( "wp_ajax_wpee_profile_activity_feeds", "wpee_profile_activity_feeds" );
add_action( "wp_ajax_nopriv_wpee_profile_activity_feeds", "wpee_profile_activity_feeds" );

/* 
Gift Approve and delete
*/
add_action( "wp_ajax_wpee_gift_action", "wpee_gift_action" ); 
function wpee_gift_action(){
    $output = array();
    $gift_action = isset( $_POST['gift_action'] ) ? $_POST['gift_action'] : '';
    $gift_id = isset( $_POST['gift_id'] ) ? $_POST['gift_id'] : '';
    if ( !empty( $gift_action ) ){
        global $wpdb;
        $users_table = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
        $check_gift = $wpdb->get_var("SELECT count(*) FROM $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id'");
        if ( $gift_action == 'delete' && $check_gift ){
            $result = $wpdb->query("delete from $dsp_user_virtual_gifts  WHERE gift_id = '$gift_id' ");
            $output['msg'] = language_code("DSP_VIRTUAL_GIFT_DELETED");
            $output['status'] = 'deleted';
            $output['empty'] = '<div class="dspdp-col-sm-12 dsp-gift-container dsp-border-container"><div class="dspdp-spacer dspdp-member-col">'. __('No record found for your search criteria.', 'wpdating') . '</div>';
        }
        elseif( $gift_action == 'approve' && $check_gift ){
            $result = $wpdb->query("update $dsp_user_virtual_gifts set status_id=1 WHERE gift_id = '$gift_id' ");
            $output['msg'] = esc_html__("Virtual Gift has been Approved","wpdating-elementor-extension");
            $output['status'] = 'approved';
        }
        if( isset($result) && $result ){
            $count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
            $output['gift_count'] = $count_friends_virtual_gifts;
        }
        else{
            $output['status'] = 'error';
            $output['msg'] = esc_html__("Failed","wpdating-elementor-extension");            
        }
        echo json_encode( $output );
    }
    die();
} 
/*
Ajax Fucntion to show gift popup
*/
add_action( "wp_ajax_wpee_gift_popup", "wpee_gift_popup" ); 
function wpee_gift_popup(){
    global $wpdb;
    $response['status'] = '';
    $output = '';
    $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
    $profile_id = isset( $_REQUEST['profile_id'] ) ? $_REQUEST['profile_id'] : '';
    if( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_show_gift_popup_nonce') ){
        $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
        $virtual_gifts     = $wpdb->get_results( "select * from $dsp_virtual_gifts" );

        $output = '<div id="wpee-popup-container" class="wpee-popup-message-inner"><form id="wpee-gift-form" method="post">';
        foreach ( $virtual_gifts as $gift_row ) {
            $output .= '<input type="radio" name="wpee-gift" class="wpee-gift" value="'.$gift_row->image.'"/><img src= "' . content_url('/uploads/dsp_media/gifts/') . $gift_row->image . '" value= "' . $gift_row->image . '"  Page ' . $gift_row->id . '/>';
        }
        $output .= '<input type="hidden" name="wpee-gift-option"class="wpee-gift-option" value="'.wp_create_nonce("wpee_choose_gift_nonce").'"/><input type="hidden" name="profile-id" class="wpee-profile-id" value="'. esc_attr( $profile_id ) .'"/><input type="submit"   class="wpee-gift-submit btn" value="' . __( 'Submit','wpdating' ) . '" />
                    </form></div>';

        $response['status'] = 'success';
        $response['content'] = $output;
    }
    die(json_encode($response));
} 
/*
Ajax Fucntion to submit gift form
*/
add_action( "wp_ajax_wpee_gift_form_action", "wpee_gift_form_action" ); 
function wpee_gift_form_action(){
    global $wpdb;
    $response['status'] = 'error';
    $response['message'] = __( 'Sorry, Failed to send gift.', 'wpdating');
    $nonce = isset( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';
    $member_id = isset( $_REQUEST['profile_id'] ) ? $_REQUEST['profile_id'] : '';
    $gift = isset( $_REQUEST['gift'] ) ? $_REQUEST['gift'] : '';

    if(empty( $gift )){
        $response['status'] = 'error';
        $response['msg'] = 'Please select one of the gifts first!';
    }
    if( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_choose_gift_nonce') && !empty( $gift ) ){
        $dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
        $dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
        $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
        $check_credit_mode = wpee_get_setting('credit');
        $check_free_mode = wpee_get_setting('free_mode');
        $user_id = get_current_user_id();
        $profile_link = wpee_get_profile_url_by_id( $user_id );
        $response['user_id']= $user_id;
        $date = date('Y-m-d H:i:s');
        if ($user_id != $member_id) {
            $access_feature_name  = 'Virtual Gifts';
            $check_membership_msg = check_membership($access_feature_name, $user_id);

            if (($check_free_mode->setting_status == "Y" && $_SESSION['free_member']) || $check_membership_msg == 'Access') {
                $result = $wpdb->query("insert into $dsp_user_virtual_gifts values('',$user_id,$member_id,'" . $gift . "','$date',0)");
                $response['status'] = 'success';
                $response['msg'] = __( 'Gift has been Sent', 'wpee-elementor-extension');
            } elseif (($check_credit_mode->setting_status == 'Y') && (dsp_get_credit_of_current_user() >= dsp_get_credit_setting_value('gifts_per_credit'))) {
                $result = $wpdb->query("insert into $dsp_user_virtual_gifts values('',$user_id,$member_id,'" . $gift . "','$date',0)");
                ///////// credit code////////
                $gift_per_credit = $wpdb->get_var("select gifts_per_credit from $dsp_credits_table");
                $wpdb->query("update $dsp_credits_usage_table set no_of_credits=no_of_credits-$gift_per_credit where user_id='$user_id'");
                $wpdb->query("update $dsp_credits_table set credit_used=credit_used+$gift_per_credit");
                $response['status'] = 'success';
                $response['msg'] = __( 'Gift has been Sent', 'wpee-elementor-extension');
            } 
            else {
                $response['status'] = 'failed';
                $response['msg'] = __('You don\'t have enough credit to send gift. ', 'wpdating');
            }
        }
    }
    die(json_encode($response));
} 

/* 
Send Gift
Returns form to send gift or error message( expired, upgrade, etc )
*/
add_action( "wp_ajax_wpee_send_gift_action_container", "wpee_send_gift_action_container" ); 
function wpee_send_gift_action_container(){
    $output = array();
    ob_start();
    global $wpdb;
    $users_table             = $wpdb->prefix . DSP_USERS_TABLE;
    $dsp_user_profiles       = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $dsp_user_virtual_gifts  = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
    $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
    $user_id = wpee_profile_id();
    $member_id = get_current_user_id();
    $profile_link = wpee_get_profile_url_by_id( $member_id );
    if ($user_id != $member_id) {
        $check_free_mode        = wpee_get_setting( 'free_mode' );
        $check_free_trail_mode  = wpee_get_setting( 'free_trail_mode' );
        $check_credit_mode      = wpee_get_setting( 'credit' );
        $check_max_gifts        = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where status_id=1 and member_id='$member_id'");
        $no_of_credits          = $wpdb->get_var("select no_of_credits from `$dsp_credits_usage_table` where user_id='$user_id'");
        $no_of_credits          = ! empty($no_of_credits) ? $no_of_credits : 0;
        ?>
        <?php
        if (is_user_logged_in()) {
            $access_feature_name = "Virtual Gifts";
            if ($check_free_trail_mode->setting_status == "N")      // all except free trial
            {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if (($check_free_mode->setting_status == "Y" && $_SESSION['free_member']) || $check_membership_msg == 'Access' || (($check_credit_mode->setting_status == 'Y') && ($no_of_credits >= dsp_get_credit_setting_value('gifts_per_credit')))) {

                    $check_virtual_gifts_mode = wpee_get_setting( 'virtual_gifts' );
                    if ($check_max_gifts < $check_virtual_gifts_mode->setting_value) { ?>

                        <form method="post">
                            <select name="image" style="display:none;"
                                    class='image-picker show-html'>
                                <?php
                                $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
                                $virtual_gifts     = $wpdb->get_results("select * from $dsp_virtual_gifts");
                                foreach ($virtual_gifts as $gift_row) {
                                    ?>
                                    <option
                                            data-img-src='<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image; ?>'
                                            value='<?php echo $gift_row->image; ?>'>
                                        Page <?php echo $gift_row->id; ?>  </option>
                                <?php } ?>
                            </select>
                            <input type="submit" class="dspdp-btn dspdp-btn-default"
                                   value="<?php echo __('Submit', 'wpdating'); ?>"/>
                        </form>

                    <?php } else {
                        ?>
                        <p class="error"><?php echo __('User has received the maximum number of virtual gifts.', 'wpdating'); ?></p>

                    <?php }
                } else if ($check_membership_msg == "Onlypremiumaccess") { ?>

                    <p class="error"><?php echo __('Only premium member can access this feature, Please upgrade your account', 'wpdating') ?>
                        <a href="<?php echo $profile_link . "setting/upgrade_account/"; ?>"
                           class="error dspdp-btn dspdp-btn-default"
                           style="text-decoration:underline;"><?php echo __('Upgrade Here.', 'wpdating') ?></a>
                    </p>

                <?php } else { ?>
                    <p class="error"><?php echo __('Your Premimum Account has been expired.', 'wpdating') ?>
                        <a href="<?php echo $profile_link . "setting/upgrade_account/"; ?>"
                           class="error dspdp-btn dspdp-btn-default"
                           style="text-decoration:underline;"><?php echo __('Upgrade Here.', 'wpdating') ?></a>
                    </p>
                <?php }
            } else  // free trial
            {
                $check_member_trial_msg = check_free_trial_feature($access_feature_name,
                    $user_id);
                if ($check_member_trial_msg == "Expired") {
                    ?>
                    <p class="error"><?php echo __('Your Premimum Account has been expired.', 'wpdating') ?>
                        <a href="<?php echo $profile_link . "setting/upgrade_account/"; ?>"
                           class="error dspdp-btn dspdp-btn-default"
                           style="text-decoration:underline;"><?php echo __('Upgrade Here.', 'wpdating') ?></a>
                    </p>
                <?php } else if ($check_member_trial_msg == "Onlypremiumaccess") { ?>
                    <p class="error"><?php echo __('You must be a premium member to comment.', 'wpdating') ?>
                        <a href="<?php echo $profile_link . "setting/upgrade_account/"; ?>"
                           class="error dspdp-btn dspdp-btn-default"
                           style="text-decoration:underline;"><?php echo __('Upgrade Here.', 'wpdating') ?></a>
                    </p>
                <?php } else if ($check_member_trial_msg == "Access") {
                    ?>
                    <?php

                    if ($check_max_gifts < $check_virtual_gifts_mode->setting_value) {
                        ?>
                        <form method="post">
                            <select name="image" style="display:none;"
                                    class='image-picker show-html'>
                                <?php
                                $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
                                $virtual_gifts     = $wpdb->get_results("select * from $dsp_virtual_gifts");
                                foreach ($virtual_gifts as $gift_row) {
                                    ?>
                                    <option
                                            data-img-src='<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image; ?>'
                                            value='<?php echo $gift_row->image; ?>'><?php echo __('Page', 'wpdating') . "\t";
                                        echo $gift_row->id; ?>  </option>
                                <?php } ?>
                            </select>
                            <input type="submit" class="dspdp-btn dspdp-btn-default"
                                   value="<?php echo __('Submit', 'wpdating'); ?>"/>
                        </form>
                    <?php } else { ?>
                        <p class="error"><?php echo __('User has received the maximum number of virtual gifts.', 'wpdating'); ?></p>
                    <?php } ?>
                    <?php
                }
            }
        } else        // not logged in
        {
            ?>
            <p class="error"><?php echo __('You must log in to send virtual gifts.', 'wpdating'); ?></p>
            <?php
        }
    } else        // both user same
    {
        ?>
        <p class="error"><?php echo __('You can not send yourself a gift', 'wpdating'); ?></p>
    <?php }
    $output['content'] = ob_get_clean();
    echo json_encode( $output );
    die();
} 

/* 
Block User or Remove blocked user
*/
add_action( "wp_ajax_wpee_block_action", "wpee_block_action" ); 
function wpee_block_action(){
    $output = array();
    $block_action = isset( $_POST['block_action'] ) ? $_POST['block_action'] : '';
    $block_id = isset( $_POST['block_id'] ) ? $_POST['block_id'] : '';
    $profile_id = isset( $_POST['profile_id'] ) ? $_POST['profile_id'] : '';
    $user_id = get_current_user_id();
    if ( !empty( $block_action ) ){
        global $wpdb;
        $dsp_blocked_members_table = $wpdb->prefix . 'dsp_blocked_members';
        if ( $block_action == 'delete' ){
            $result = $wpdb->query("DELETE FROM $dsp_blocked_members_table where blocked_id = '$block_id'");
            if( $result ){
                $output['msg'] = esc_html__("Member removed from block list.","wpdating-elementor-extension");
                $output['status'] = 'deleted';
                $output['empty'] = '<div class="dspdp-col-sm-12 dsp-block-container dsp-border-container"><div class="dspdp-spacer dspdp-member-col">'. __('No any blocked members.', 'wpdating') . '</div>';
                $output['block_id'] = $block_id;
                $output['action'] = 'add';
                $output['text'] = __('Block this user', 'wpdating');
            }
            else{                
                $output['msg'] = __('Failed to Unblock.', 'wpdating');
                $output['status'] = 'failed';
            }
        }
        elseif( $block_action == 'add' ){
            if ( ($user_id != $profile_id) && ($user_id != "")) {
                $check_block_mem_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE block_member_id='$profile_id' AND user_id='$user_id'");

                if ($check_block_mem_exist <= 0) {
                    $result = $wpdb->query("INSERT INTO $dsp_blocked_members_table SET user_id = '$user_id',block_member_id ='$profile_id'");
                    if($result){
                        $output['msg'] = __('Blocked this user.', 'wpdating');
                        $output['status'] = 'added';
                        $output['block_id'] = $wpdb->insert_id;
                        $output['action'] = 'delete';
                        $output['text'] = __('Unblock this user', 'wpdating');
                    }
                    else{
                        $output['msg'] = __('Failed to Blocked.', 'wpdating');
                        $output['status'] = 'failed';
                    }
                } else {
                    if ($user_id != "") {
                        $output['msg'] = __('Already Exists in your blocked list.', 'wpdating');
                        $output['status'] = 'failed';
                    }
                }
            }
        }
        $output['profile_id'] = $profile_id;
        echo json_encode( $output );
    }
    die();
} 

/**
 * This function is handle ajax request meet me actioadd_action( "wp_ajax_wpee_meet_me_action", "wpee_meet_me_action" );
n and return the next member profile
 *
 * @return void
 * @since 1.0.0
 */
if ( ! function_exists( 'wpee_meet_me_action' ) ) {
    function wpee_meet_me_action() {
        if ( isset( $_POST['meet_action'] ) && ! empty( $_POST['meet_action'] ) ){
            global $wpdb;
            $output                   = array();
            $dsp_meet_me_table        = $wpdb->prefix . 'dsp_meet_me';
            $current_user_profile_url = wpee_get_profile_url_by_id( $_POST['current_user_id'] );

            $check_row = $wpdb->get_var( "SELECT COUNT(*) FROM {$dsp_meet_me_table} 
                                            WHERE user_id='{$_POST['current_user_id']}' AND member_id='{$_POST['user_id']}'");

            if ( $check_row == 0) {
                $datetime  = date('Y-m-d H:i:s');
                $wpdb->insert( $dsp_meet_me_table, array(
                    'user_id' => $_POST['current_user_id'],
                    'member_id' => $_POST['user_id'],
                    'status' => $_POST['meet_action'],
                    'datetime' => $datetime
                    ) );
                if ( $_POST['meet_action'] == 'yes' && dsp_issetGivenEmailSetting( $_POST['current_user_id'],'meet_me' ) ) {
                    $receiver       = $wpdb->get_row("SELECT * FROM {$wpdb->users} WHERE ID='{$_POST['user_id']}'");
                    $receiver_name  = Wpdating_Elementor_Extension_Helper_Functions::get_user_display_name( $receiver->ID , $receiver->user_login);

                    $sender      = $wpdb->get_row("SELECT * FROM {$wpdb->users} WHERE ID='{$_POST['current_user_id']}'");
                    $sender_name = Wpdating_Elementor_Extension_Helper_Functions::get_user_display_name( $sender->ID , $sender->user_login);

                    $email_template = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}dsp_email_templates 
                                                                WHERE mail_template_id='19'");
                    $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_template->subject);
                    $email_message = $email_template->email_body;
                    $email_message = str_replace("<#RECEIVER_NAME#>", $receiver_name, $email_message);
                    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                    $email_message = str_replace("<#URL#>", $current_user_profile_url, $email_message);

                    $wpdating_email  = Wpdating_email_template::get_instance();
                    $wpdating_email->send_mail( $receiver->user_email, $email_subject, $email_message );
                }
            }
            ob_start();
            global $wpdb, $wpee_general_settings;
            $sql_query = "SELECT DISTINCT user.ID user_id, user.user_login user_name, user.display_name user_display_name,
                user_profile.make_private user_photo_private, user_profile.gender user_gender,
                (year(CURDATE())-year(user_profile.age)) user_age,
                country.name user_country, state.name user_state, city.name user_city,
                members_photo.picture as user_image
                FROM {$wpdb->users} user
                JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                ON user.ID = user_profile.user_id AND user_profile.status_id = 1 AND user_profile.country_id > 0
                LEFT JOIN {$wpdb->prefix}dsp_country country
                ON user_profile.country_id = country.country_id
                LEFT JOIN {$wpdb->prefix}dsp_state state
                ON user_profile.state_id = state.state_id
                LEFT JOIN {$wpdb->prefix}dsp_city city
                ON user_profile.city_id = city.city_id
                LEFT JOIN {$wpdb->prefix}dsp_members_photos members_photo
                ON user.ID = members_photo.user_id
                WHERE user.ID NOT IN (SELECT member_id FROM {$wpdb->prefix}dsp_meet_me WHERE user_id='{$_POST['current_user_id']}')";

            $user_seeking = $wpdb->get_var("SELECT seeking FROM {$wpdb->prefix}dsp_user_profiles where user_id='{$_POST['current_user_id']}'");

            if( ! empty( $user_seeking ) ){
                $sql_query .= " AND user_profile.gender = '{$user_seeking}'";
            }

            if ( $wpee_general_settings->male->status == 'N' ) {
                $sql_query .= " AND user_profile.gender != 'M'";
            }

            if ( $wpee_general_settings->female->status == 'N' ) {
                $sql_query .= " AND user_profile.gender != 'F'";
            }

            if ( $wpee_general_settings->couples->status == 'N' ) {
                $sql_query .= " AND user_profile.gender != 'C'";
            }

            $sql_query .= " LIMIT 1";

            $member_profile = $wpdb->get_row( $sql_query );
            if ( $member_profile ) :
                $profile_link      = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $member_profile->user_name );
                $user_display_name = Wpdating_Elementor_Extension_Helper_Functions::get_user_display_name( $member_profile->user_id , $member_profile->user_name);?>
                <h2><?php echo __('Want To Meet Me?', 'wpdating') ?></h2>
                <div class="image-box dspdp-spacer-md dsp-meetme-image">
                    <a href="<?php echo $profile_link; ?>">
                        <img src="<?php echo Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $member_profile->user_id, $member_profile->user_image,
                                $member_profile->user_photo_private, 'N' )->image_350; ?>" alt="<?php echo esc_attr( $user_display_name );?> "/>
                    </a>
                </div>
                <div class="user-meetto-info dspdp-font-2x  dspdp-spacer-md dsp-meet-details">
                    <div class="user-name">
                        <span class="dspdp-medium"><?php echo $user_display_name; ?></span>
                    </div>
                    <?php echo $member_profile->user_age .
                            ( ! empty( $member_profile->user_city ) ? ', ' . $member_profile->user_city : '' ) .
                            ( ! empty( $member_profile->user_state ) ? ', ' .  $member_profile->user_state : '' ) .
                            ( ! empty( $member_profile->user_country ) ? ', ' . $member_profile->user_country : '' ); ?>
                </div>
                <div class="wpee-meetme-action-wrap" data-current-user-id="<?php echo $_POST['current_user_id']; ?>"
                         data-profile-id="<?php echo $member_profile->user_id; ?>">
                    <a href="javascript:void(0);" class="button wpee-meet-me-trigger" data-action="yes">
                        <i class="fa fa-heart"></i><?php echo __( 'Yes', 'wpdating' ); ?>
                    </a>
                    <a href="javascript:void(0);" class="button wpee-meet-me-trigger" data-action="no">
                        <i class="fa fa-times"></i><?php echo __( 'No', 'wpdating' ); ?>
                    </a>
                </div>
                <?php
            else : ?>
                <h2><?php echo __('Want To Meet Me?', 'wpdating'); ?></h2>
                <div class="meet-to-info no-user-profiles">
                    <b><?php _e( "Sorry there are no users to show.", "wpdating" ); ?></b>
                </div>
            <?php
            endif;
            $output['content'] = ob_get_clean();
            echo json_encode( $output );
        }
        die();
    }
}
add_action( "wp_ajax_wpee_meet_me_action", "wpee_meet_me_action" );

$check_my_friend_module = wpee_get_setting('my_friends');
if ($check_my_friend_module->setting_status == 'Y'){
    add_action( "wp_ajax_wpee_add_friend_action", "wpee_add_friend_action" ); 
    function wpee_add_friend_action(){
        $session_user_id = get_current_user_id();
        $frnd_userid = isset( $_POST['friend_id'] ) ? $_POST['friend_id'] : '';
        $action = isset( $_POST['wpee_action'] ) ? $_POST['wpee_action'] : '';
        $date = date("Y-m-d");
        if ( !empty($session_user_id) && !empty($frnd_userid) && ($session_user_id != $frnd_userid) && $action == 'add') {
            global $wpdb;
            $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
            $dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
            $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
            $dsp_user_table = $wpdb->users;


            $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid");
            //dsp_debug($num_rows);die;
            $output['btntext'] = __('Send Friend Request', 'wpdating');
            if ($num_rows <= 0) {
                $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE friend_request='N' AND user_id='$frnd_userid'");
                if ($check_friend_notification <= 0) {

                    $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id = $session_user_id,friend_uid ='$frnd_userid' ,approved_status='N', date_added='$date'");
                    dsp_add_notification($session_user_id, $frnd_userid, 'friend_request');
                    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='7'");
                    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$frnd_userid'");
                    $reciver_name = wpee_get_user_display_name_by_id($frnd_userid);
                    $receiver_email_address = $reciver_details->user_email;
                    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$session_user_id'");
                    $sender_name = wpee_get_user_display_name_by_id($session_user_id);
                    $admin_email = get_option('admin_email');
                    $from = $admin_email;
                    $url = wpee_get_profile_link_by_id($session_user_id);
                    $email_subject = $email_template->subject;
                    $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
                    
                    $mem_email_subject = $email_subject;
                    $email_message = $email_template->email_body;
                    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                    $email_message = str_replace("<#URL#>", $url , $email_message);
                    $MemberEmailMessage = $email_message;
                    // dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
                    $wpdating_email  = Wpdating_email_template::get_instance();
                    $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
                    $print_msg = __(' Your Friend Request was sent successfully. After your request is accepted, you can view your added friends in your Friends list.', 'wpdating');
                    $output['btntext'] = __('Cancel friend request', 'wpdating');
                    $output['status'] = 'success';
                    $output['profile_id'] = $frnd_userid;
                    $output['action'] = 'delete';
                } else {
                    $print_msg = __('You can&rsquo;t send Friend Request to this Member.', 'wpdating');
                }
            } 
            else {
                $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid AND approved_status='N'");
                if ($num_rows2 > 0) {
                    $print_msg = __('You have Already sent a request to this Member!', 'wpdating');
                    $output['btntext'] = __('Friend Request Sent', 'wpdating');
                } else {
                    $print_msg = __('Already your Friend!', 'wpdating');
                }                
            }
        }
        elseif ( !empty($session_user_id) && !empty($frnd_userid) && ($session_user_id != $frnd_userid) && $action == 'delete') {
            global $wpdb;
            $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
            $num_rows2 = $wpdb->get_var("DELETE from $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid AND approved_status='N'");
            $print_msg = __('Friend request has been cancelled.', 'wpdating');
            $output['btntext'] = __('Send friend request', 'wpdating');
            $output['status'] = 'success';
            $output['profile_id'] = $frnd_userid;
            $output['action'] = 'add';

        }
        $output['msg'] = $print_msg;
        echo json_encode( $output );
        die();
    }
}

/* 
Post Profile Status
Ajax function
*/
add_action( "wp_ajax_wpee_post_status", "wpee_post_status" ); 
function wpee_post_status(){
    global $wpdb;
    $nonce = isset( $_REQUEST['nonce'] ) ? wp_unslash( $_REQUEST['nonce'] ): '';
    $new_status = isset( $_REQUEST['status'] ) ? esc_sql(wpee_sanitizeData(trim($_REQUEST['status']),
            'xss_clean')) : '';
    $response['msg'] = __('Failed to post status', 'wpdating');
    if( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_profile_status_nonce') && !empty( $new_status ) ){
        $check_approve_profile_status= wpee_get_setting('authorize_profiles');
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $current_user = get_current_user_id();
        $dateTimeFormat = dsp_get_date_timezone();
        extract($dateTimeFormat);
        $errors = array();
        $new_status = apply_filters('dsp_spam_filters', $new_status);
        $errors[]   = $new_status == "" ? str_replace('<#name#>', 'status', __('Either you entered only spam words or no <#name#> entered', 'wpdating')) : '';
        $response['test'] = 'test';
        if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.
            $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' WHERE user_id = $current_user");
            $status_approval_message = __('Your status has been updated!', 'wpdating');
            dsp_delete_news_feed($current_user, 'status');
            dsp_add_news_feed($current_user, 'status');
            dsp_add_notification($current_user, 0, 'status');
        } else {
            $wpdb->query("UPDATE $dsp_user_profiles SET my_status= '$new_status' ,status_id=0 WHERE user_id = $current_user");
            $status_approval_message = __('Please allow 24 hours for profile approval.', 'wpdating');
        }
        $response['status'] = 'success';
        $response['msg'] = $status_approval_message;
    }
    die(json_encode($response));
} 


/* 
Friend Request
*/
add_action( "wp_ajax_wpee_friend_request_action", "wpee_friend_request_action" ); 
function wpee_friend_request_action(){
    $output = array();
    $action = isset( $_POST['fr_action'] ) ? $_POST['fr_action'] : '';
    $frnd_request_Id = isset( $_POST['fr_id'] ) ? $_POST['fr_id'] : ''; // profile id
    $user_id = isset( $_POST['profile_id'] ) ? $_POST['profile_id'] : ''; // profile id
    $current_user = get_current_user_id(); // current user id
    global $wpdb;
    $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
    $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
    $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
    $output['profile_id'] = $user_id;
    $output['action'] = $action;
    if(!empty($action) && $action=="approve"){
        include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");
        $date = date("Y-m-d");
        $wpdb->query("UPDATE $dsp_my_friends_table  SET approved_status='Y' WHERE friend_id = '$frnd_request_Id' AND friend_uid=$current_user");
        $request_user_id = $wpdb->get_row("SELECT * FROM $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
        $check_friend_in_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE user_id='$user_id' AND friend_uid='$request_user_id->user_id'");
        if ($check_friend_in_list <= 0) {
            $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id ='$current_user',friend_uid='$user_id',  approved_status='Y' , date_added='$date'");
        }

        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='8'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
        $reciver_name = wpee_get_user_display_name_by_id($user_id);
        $receiver_email_address = $reciver_details->user_email;
        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
        $sender_name = wpee_get_user_display_name_by_id(get_current_user_id());
        $url = $_SERVER['HTTP_HOST'];
        $email_subject = $email_template->subject;
        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
        $mem_email_subject = $email_subject;

        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
        $email_message = str_replace("<#URL#>", $url, $email_message);

        $MemberEmailMessage = $email_message;

        dsp_send_email($receiver_email_address, $sender_name, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");

        $output['status'] = 'approved';
        $output['msg'] = esc_html__('Approve','wpdating');

    }
    elseif(!empty($action) && $action=="delete"){
        $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$current_user");
        $output['sql'] = "DELETE from $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$current_user";
        $output['status'] = 'rejected';
        $output['msg'] = esc_html__('Rejected','wpdating');

    }
    echo json_encode( $output );
    die();
}

/**
 * Register form data validation
 *
 * Execute a ajax request for register form data validation.
 *
 * @since 1.4.0
 */
if ( !function_exists( 'wpee_validate_register_form' ) ) {
    function wpee_validate_register_form(){

        global $wpdb, $wpee_general_settings;
        $errors = [];

        $registering_user_ip_address = $_SERVER['REMOTE_ADDR'];
        $check_blacklist_ip_address  = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}dsp_blacklist_members WHERE
                                                                    ip_address = '{$registering_user_ip_address}' AND ip_status=1 ");
        if ( $check_blacklist_ip_address > 0 ){
            $errors['toastr']   = __('Your IP is blacklisted by admin', 'wpdating');
            $response['status'] = false;
            $response['errors'] = $errors;
            die(json_encode($response));
        }

        $is_password_option_enabled     = isset( $wpee_general_settings->password_option ) && ! empty( $wpee_general_settings->password_option )
            && $wpee_general_settings->password_option->status == 'Y';
        $site_key                       = ( isset( $wpee_general_settings->google_api_key ) && ! empty( $wpee_general_settings->google_api_key ) )
            ? $wpee_general_settings->google_api_key->value : '';
        $secret                         = ( isset( $wpee_general_settings->google_secret_key ) && ! empty( $wpee_general_settings->google_secret_key ) )
            ? $wpee_general_settings->google_secret_key->value : '';
        $is_first_n_last_name_enabled   = isset( $wpee_general_settings->register_form_setting ) && ! empty( $wpee_general_settings->register_form_setting ) &&
            $wpee_general_settings->register_form_setting->status == 'Y';
        $is_google_recaptcha_enable     = isset( $wpee_general_settings->recaptcha_option ) &&
            ! empty( $wpee_general_settings->recaptcha_option ) && $wpee_general_settings->recaptcha_option->status == 'Y' &&
            ! empty( $site_key ) && !empty( $secret );

        $username = ( isset( $_POST['username'] ) ) ? esc_sql( wpee_sanitizeData( trim( $_POST['username']), 'xss_clean' ) ) : '';
        if ( empty( $username ) ) {
            $errors['username'] = __( 'Enter username.', 'wpdating' );
        } elseif ( strpos($username, " ") !== false ) {
            $errors['username']  = __( 'Username can not have spaces.', 'wpdating' );
        } else {
            $username_exists = username_exists( $username );
            if ( $username_exists ){
                $errors['username'] = __( 'Username already exists.', 'wpdating' );
            }
        }

        if ( $is_first_n_last_name_enabled ){
            $first_name  = ( isset( $_POST['first_name'] ) ) ? esc_sql( wpee_sanitizeData( trim( $_POST['first_name']), 'xss_clean' ) ) : '';

            if ( empty( $first_name ) ) {
                $errors['first-name'] = __( 'Enter first name.', 'wpdating' );
            }

            $last_name  = ( isset( $_POST['last_name'] ) ) ? esc_sql( wpee_sanitizeData( trim( $_POST['last_name']), 'xss_clean' ) ) : '';

            if ( empty( $last_name ) ) {
                $errors['last-name'] = __( 'Enter last name.', 'wpdating' );
            }
        }

        $email = ( isset( $_POST['email'] ) ) ? esc_sql( wpee_sanitizeData( trim( $_POST['email']), 'xss_clean' ) ) : '';

        if ( empty( $email ) ){
            $errors['email'] = __( 'Enter email.', 'wpdating' );
        } elseif ( !preg_match( "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $email ) ) {
            $errors['email']  = __('Please enter a valid email address.', 'wpdating');
        } else {
            $email_exists = email_exists($email);
            if ( $email_exists ){
                $errors['email'] = __('Email already exists', 'wpdating');
            } else{
                $confirm_email = ( isset( $_POST['confirm_email'] ) ) ? esc_sql( wpee_sanitizeData(trim( $_POST['confirm_email']), 'xss_clean' ) ) : '';
                if ( empty( $confirm_email ) ){
                    $errors['confirm-email'] = __( 'Enter confirm email.', 'wpdating' );
                } else if ( $email != $confirm_email ) {
                    $errors['confirm-email'] = __('Provided emails are not same. Please fill again.', 'wpdating');
                }
            }
        }

        if ( $wpee_general_settings->terms_page->status == 'Y' && empty( $_POST ['terms'] ) ) {
            $errors[] = __('Please accept Terms and Conditions.', 'wpdating');
        }

        if ( $is_google_recaptcha_enable ) {

            if ( ! empty( $_POST["g-recaptcha-response"] ) ) {
                require_once WP_DSP_ABSPATH . 'recaptchalib.php';

                $re_captcha = new ReCaptcha($secret);
                $resp = $re_captcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );
                if ( is_null( $resp ) || ! $resp->success ) {
                    $errors['g-recaptcha'] = __('Captcha did not match', 'wpdating');
                }
            } else {
                $errors['g-recaptcha'] = __( 'Invalid google recaptcha.', 'wpdating' );
            }
        }
        if ( $is_password_option_enabled ) {
            $password = isset( $_POST['password'] ) ? esc_sql( wpee_sanitizeData( trim( $_POST['password'] ), 'xss_clean' ) ) : '';

            if ( empty( $password ) ) {
                $errors['password'] = __( 'Enter password.', 'wpdating' );
            } else {
                $re_password = isset( $_POST['re_password'] ) ? esc_sql( wpee_sanitizeData( trim( $_POST['re_password'] ), 'xss_clean' ) ) : '';
                if ( empty( $re_password ) ) {
                    $errors['re-password'] = __( 'Please confirm password.', 'wpdating' );
                } else if ( $password != $re_password ) {
                    $errors['re-password'] = __('Please enter the same password in the two password fields.', 'wpdating');
                }
            }
        }

        if( is_plugin_active( 'twilio_registration/twilio_registration.php' ) ) {
            $phone_number = esc_sql( wpee_sanitizeData( trim( $_POST['phone_code']), 'xss_clean' ) ) . esc_sql( wpee_sanitizeData( trim( $_POST['phone_number']), 'xss_clean' ) );
            $phone_number_exists = $wpdb->get_var("SELECT * FROM {$wpdb->prefix}dsp_user_profiles WHERE phone_number='{$phone_number}'");
            if ( $phone_number_exists ) {
                $errors['phone-number'] = __('Phone number already exists', 'wpdating');
            } else {
                require_once WP_CONTENT_DIR . '/plugins/twilio_registration/lib/twilio/vendor/autoload.php';

                $sid    = get_option('sid_key');
                $token  = get_option('auth_token');

                $twilio_client = new \Twilio\Rest\Client($sid, $token);

                try {
                    $phone_number = $twilio_client->lookups->v1->phoneNumbers($phone_number)
                        ->fetch();
                } catch ( \Twilio\Exceptions\RestException $exception ) {
                    $errors['phone-number'] = __('Enter valid phone number.', 'wpdating');
                } catch ( \Twilio\Exceptions\TwilioException $exception ) {
                    $errors['phone-number'] = __('Enter valid phone number.', 'wpdating');
                }
            }
        }

        if ( count($errors) > 0 ){
            $response['status'] = false;
            $response['errors'] = $errors;
        } else {
            $response['status'] = true;
        }

        die(json_encode($response));
    }
}
add_action( "wp_ajax_nopriv_wpee_validate_register_form", "wpee_validate_register_form" );

/**
 * Ajax request for user registration.
 *
 */
function wpee_wpee_form_registration(){

    check_ajax_referer( 'wpee_registration_page', 'wpee-register-nonce' );

    global $wpdb, $wpee_general_settings;

    $is_first_n_last_name_enabled   = isset( $wpee_general_settings->register_form_setting )
        && ! empty( $wpee_general_settings->register_form_setting ) && $wpee_general_settings->register_form_setting->status == 'Y';
    $is_password_option_enabled      = isset( $wpee_general_settings->password_option ) &&
        ! empty( $wpee_general_settings->password_option ) && $wpee_general_settings->password_option->status == 'Y';
    $send_email_verification_enabled = isset( $wpee_general_settings->after_user_register_option ) &&
        ! empty( $wpee_general_settings->after_user_register_option ) && $wpee_general_settings->after_user_register_option->value == 'verify_email';

    $username = esc_sql( wpee_sanitizeData( trim( $_POST['username']), 'xss_clean' ) );
    $email    = esc_sql( wpee_sanitizeData( trim( $_POST['email']), 'xss_clean' ) );
    if ( $is_password_option_enabled ){
        $password = esc_sql( wpee_sanitizeData( trim( $_POST['password'] ), 'xss_clean' ) );
    } else {
        $password = wp_generate_password( 12, false );
    }

    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        $response['success'] = false;
        die( json_encode( $response ) );
    }

    $user_details = array(
        'signup_ip'         => $_SERVER['REMOTE_ADDR'],
        'ip_address_status' => 0,
    );

    if ($is_first_n_last_name_enabled) {
        $first_name                 = esc_sql( wpee_sanitizeData( trim( $_POST['first_name']), 'xss_clean' ) );
        $last_name                  = esc_sql( wpee_sanitizeData( trim( $_POST['last_name']), 'xss_clean' ) );
        $user_details['first_name'] = $first_name;
        $user_details['last_name']  = $last_name;
    }

    dsp_add_user_details_in_meta_table( $user_details, $user_id );

    $profile_input = [
        'user_id'           => $user_id,
        'gender'            => $_POST['gender'],
        'age'               => $_POST['dsp_year'] . "-" . $_POST['dsp_mon'] . "-" . $_POST['dsp_day'],
        'status_id'         => isset( $wpee_general_settings->authorize_profiles ) && ! empty( $wpee_general_settings->authorize_profiles ) &&
            $wpee_general_settings->authorize_profiles->status == 'Y',
        'edited'            => 'Y',
        'last_update_date'  => date("Y-m-d H:m:s")
    ];

    if ( is_plugin_active( 'twilio_registration/twilio_registration.php' ) ) {
        $profile_input['phone_number'] = $_POST['phone_code'] . $_POST['phone_number'];
        $wpdb->delete( $wpdb->prefix . 'dsp_phone_verification', array( 'phone' => $profile_input['phone_number'] ) );
    }

    $wpdb->insert( $wpdb->prefix . 'dsp_user_profiles', $profile_input );

    $subject = __( 'Registration Successful', 'wpdating' );
    $message = __( 'Your Login Details', 'wpdating' ) . "\n" . __( 'Username: ', 'wpdating' ) . $username . "\n" . __( 'Password: ', 'wpdating' ) . $password;
    $details = array(
        'email'    => $email,
        'message'  => $message,
        'userId'   => $user_id,
        'password' => $password,
        'username' => $username,
    );

    $message = ( $send_email_verification_enabled && $is_password_option_enabled ) ? apply_filters( 'wpee_dsp_get_activation_link', $details ) : $message;

    wp_mail( $email, $subject, $message );
    if ( ! $send_email_verification_enabled ) {
        $register_after_redirect_url = ( isset( $wpee_general_settings->after_registration_redirect ) &&
                    ! empty( $wpee_general_settings->after_registration_redirect ) &&
                    $wpee_general_settings->after_registration_redirect->status == 'Y' )
                    ? $wpee_general_settings->after_registration_redirect->value
                    : Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $username ) . '/edit-profile' ;
        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id );
        $response['auto_login']   = true;
        $response['redirect_url'] = $register_after_redirect_url;
    } else {
        $response['auto_login']   = false;
        if ( $is_password_option_enabled ) {
            $response['message'] = __( 'Please activate your account, an activation link has been emailed to you. Please check your email.', 'wpdating' );
        } else {
            $response['message'] = __( 'Alright! Your username and password has been emailed to you. Please check your email.', 'wpdating' );
        }
    }

    $response['success'] = true;
    die( json_encode( $response ) );

}
add_action( "wp_ajax_nopriv_wpee_wpee_form_registration", "wpee_wpee_form_registration" );

/* 
Report User
*/
add_action( "wp_ajax_wpee_report_user_action", "wpee_report_user_action" ); 
function wpee_report_user_action(){
    $output = array();    
    global $wpdb;
    $dsp_my_friends_table = $wpdb->prefix . 'dsp_reported_user';
    $nonce = isset( $_POST['report-nonce'] ) ? $_POST['report-nonce'] : '';
    $report_message = isset( $_POST['report-message'] ) ? $_POST['report-message'] : '';
    $mem_id = isset( $_POST['profile-id'] ) ? $_POST['profile-id'] : '';
    $reported_by = get_current_user_id();
    if ( !empty( $nonce ) && wp_verify_nonce( $nonce, 'wpee_report_user_nonce' ) && !empty($mem_id) ) {
        $table_name_report_user = $wpdb->prefix . 'dsp_reported_user';
        $users_table = $wpdb->prefix . DSP_USERS_TABLE;
        $err_msg                = 0;
        $report_desc            = '';
        if (isset($report_message) && !empty($report_message)) {
            $report_desc = $report_message;
            $err_msg = 1;
        } else {
            $message = esc_html__('Please enter the reason for report.', 'wpdating');
        }

        $reported_to = $mem_id;
        if ($err_msg == 1) {
            $insert_report_id = $wpdb->query($wpdb->prepare(
                "
                INSERT INTO $table_name_report_user(
                reported_by,reported_to, reason)
                values(%d, %d, %s)
                ",
                $reported_by,
                $reported_to,
                $report_desc

            ));

            if ($insert_report_id) {
                $message = esc_html__('Your report was successfully submitted.', 'wpdating');
                $admin_email    = get_option('admin_email');
                $subject        = __('A user has been reported.','wpdating');

                $reported_to_user = get_userdata($reported_to);
                $reported_to_user = $reported_to_user->user_login;

                $reported_by_user = get_userdata($reported_by);
                $reported_by_user = $reported_by_user->user_login;

                $email_message  = __('The user with username ','wpdating') . "$reported_to_user" . __(' has been reported by ','wpdating'). $reported_by_user;

                $wpdating_email = Wpdating_email_template::get_instance();
                $result         = $wpdating_email->send_mail($admin_email, $subject, $email_message);
                $message = esc_html__('Your report has been submitted.', 'wpdating');

            } else {
                $message = esc_html__('Your report was not submitted.', 'wpdating');
                $err_msg = 1;
            }
            $output['msg'] = $message;
        }
    }
    echo json_encode( $output );
    die();
}

/**
 * Create user album
 *
 * Execute a ajax request for user album update.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_create_edit_album') ){
    function wpee_create_edit_album(){
        parse_str($_POST['form_data'], $form_data);

        if ( empty( $form_data['album_name'] ) ) {
            die( json_encode( array( 'success' => false, 'errors' => array( 'album-name' => __("Please enter album name.", "wpdating") ) ) ) );
        }
        
        switch( $form_data['action'] )  {
            case 'add':
                $response = wpee_create_album( $form_data );
                break;

            case 'update':
                $response = wpee_update_album( $form_data );
                break;

        }

        die( json_encode( $response ) );
    }
}
add_action( "wp_ajax_wpee_create_edit_album", "wpee_create_edit_album" );

/**
 * Create user album by using id  
 *
 * Execute a ajax request for user album update.
 * 
 * @param $form_data array
 * @return array
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_create_album') ){
    function wpee_create_album( $form_data ){

        global $wpdb;
        $dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
        
        $album_name_already_exists = $wpdb->get_var( "SELECT COUNT(*) FROM {$dsp_user_albums_table} WHERE album_name = '{$form_data['album_name']}'");
        if ( $album_name_already_exists > 0 ) {
            return array( 'success' => false, 'errors' => array( 'album-name' => __("Album name", "wpdating" ) .  " '{$form_data['album_name']}' " . __( "already exists", "wpdating" ) ) );
        }
    
        $status = $wpdb->insert( $dsp_user_albums_table, array(
            'user_id'       => $form_data['user_id'],
            'album_name'    => $form_data['album_name'],
            'date_created'  => date('Y-m-d H:i:s'),
            'status_id'     => 1,
            'private_album' => isset($form_data['make_private']) ? $form_data['make_private'] : 'N'
        ));

        if( ! $status ) {
            return array( 'success' => false, 'message' => __( "Failed to add album", "wpdating" ) );
        }
        
        $profile_link = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( get_username( $form_data['user_id'] ) );

        return array( 'success' => true, 'message' => __("Album added successfully", "wpdating"), 
                        'redirect_url' => "{$profile_link}/media/albums?action=manage_photos&album_id={$wpdb->insert_id}" );
    }
}

/**
 * Update user album
 *
 * Execute a ajax request for user album update.
 *
 * @param $form_data array
 * @return array
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_update_album') ){
    function wpee_update_album( $form_data ){
        global $wpdb;
        $dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

        $user_album = $wpdb->get_row("SELECT * FROM {$dsp_user_albums_table} WHERE album_id = '{$form_data['album_id']}'");
        
        if( is_null( $user_album ) ) {
            return array( 'success' => false, 'message' => __("Something went wrong. Please try again.", "wpdating") );
        }

        if ( $form_data['album_name'] !=  $user_album->album_name ){
            $album_name_already_exists = $wpdb->get_var( "SELECT COUNT(*) FROM {$dsp_user_albums_table} WHERE album_name = '{$form_data['album_name']}'");
            if ( $album_name_already_exists > 0 ) {
                return array( 'success' => false, 'errors' => array( 'album-name' => __("Album name", "wpdating" ) .  " '{$form_data['album_name']}' " . __( "already exists", "wpdating" ) ) );
            }
        }
        
        $album_private = isset($form_data['make_private']) ? $form_data['make_private'] : 'N';
        $status = $wpdb->update( $dsp_user_albums_table, array(
            'album_name'    => $form_data['album_name'],
            'private_album' => $album_private
        ), array( 'album_id' => $form_data['album_id'] ) );

        if( ! $status ) {
            return array( 'success' => false, 'message' => __("Failed to update album. Please try again.", "wpdating") );
        }

        return array( 'success' => true, 'message' => __("Album updated successfully.", "wpdating"),
                        'album_detail' => array( 'album_id' => $form_data['album_id'], 'album_name' => $form_data['album_name'], 'album_private' => $album_private )  );
    }
}

/**
 * Delete user album by using id  
 *
 * Execute a ajax request for user album delete.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_delete_album_by_id') ){
    function wpee_delete_album_by_id(){
        global $wpdb;
        $dsp_user_albums_table      = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

        $user_album_exists     = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_user_albums_table} WHERE album_id = '{$_POST['album_id']}'");
    
        if( ! $user_album_exists ) {
            die( json_encode( array( 'success' => false, 'message' => __("Something went wrong. Please try again.", "wpdating") ) ) );
        }
    
        $status = $wpdb->delete( $dsp_user_albums_table, array('album_id' => $_POST['album_id'] ));
    
        if( ! $status ) {
            die( json_encode( array( 'success' => false, 'message' => __("Failed to delete album.", "wpdating") ) ) );
        }

        die( json_encode( array( 'success' => true, 'message' => __("Album deleted successfully.", "wpdating") ) ) );
    }
}
add_action( "wp_ajax_wpee_delete_album_by_id", "wpee_delete_album_by_id" );

/**
 * Delete user album photos by album id 
 *
 * Execute a ajax request for user album photos delete.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_delete_album_photos_by_album_id') ){
    function wpee_delete_album_photos_by_album_id(){
        global $wpdb;

        $dsp_galleries_photos_table = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;

        $wpdb->delete( $dsp_galleries_photos_table, array('album_id' => $_POST['album_id'] ));

        if (is_dir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}/thumbnail" )) {
            $dir_handle = opendir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}/thumbnail" );

            while( $file = readdir( $dir_handle ) ) {
                if ($file != "." && $file != "..") {
                    unlink(WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}/thumbnail/".$file);
                }
            }
        }

        rmdir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}/thumbnail");

        if (is_dir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}" )) {
            $dir_handle = opendir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}" );

            while( $file = readdir( $dir_handle ) ) {
                if ($file != "." && $file != "..") {
                    unlink(WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}/".$file);
                }
            }
        }

        rmdir( WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$_POST['user_id']}/album_{$_POST['album_id']}");
    }
}
add_action( "wp_ajax_wpee_delete_album_photos_by_album_id", "wpee_delete_album_photos_by_album_id" );

/**
 * Create member audio
 *
 * Execute a ajax request for member audio create.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_create_audio') ){
    function wpee_create_audio()
    {
        if ( $_FILES['audio_file']['error'] > 0 ) {

            if ( $_FILES['audio_file']['error'] == 4) {
                die( json_encode( array( 'success' => false, 'errors' => array( 'audio-file' => __('No file selected', 'wpdating') ) ) ) );
            }

            die( json_encode( array( 'success' => false, 'message' => __("Error uploading audio file. Please try again.", "wpdating" ) ) ) );
        }

        if ( $_FILES['audio_file']['size'] >= 51200000) {
            die( json_encode( array( 'success' => false, 'errors' => array( 'audio-file' => __('File is too large', 'wpdating') ) ) ) );
        }

        $permitted_file_type = array('audio/mp3', 'audio/mpeg', 'audio/mpeg3', 'audio/x-mpeg-3');

        if (!in_array($_FILES['audio_file']['type'], $permitted_file_type)) {
            die( json_encode( array( 'success' => false, 'errors' => array( 'audio-file' => __('File type is not supported', 'wpdating') ) ) ) );
        }

        global $wpdb;
        $permitted_audio_count   = wpee_get_setting('count_audios');
        $dsp_member_audios_table = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
        $count_uploaded_audios   = $wpdb->get_var("SELECT COUNT(*) AS Num FROM {$dsp_member_audios_table} WHERE user_id='{$_POST['user_id']}'");

        if ( $count_uploaded_audios >= $permitted_audio_count ){
            die( json_encode( array( 'success' => false, 'message' => __( 'You can only upload', 'wpdating' ) . ' ' . $permitted_audio_count . __( 'audios.', 'wpdating' ) ) ) );
        }

        if ( !file_exists(WP_CONTENT_DIR . '/uploads/dsp_media/user_audios') ) {
            mkdir( WP_CONTENT_DIR . '/uploads/dsp_media/user_audios', 0777);
        }

        if ( !file_exists(WP_CONTENT_DIR . '/uploads/dsp_media/user_audios/user_' . $_POST['user_id']) ) {
            mkdir( WP_CONTENT_DIR . '/uploads/dsp_media/user_audios/user_' . $_POST['user_id'], 0777);
        }

        $audio_name = time() . '.' . pathinfo($_FILES['audio_file']['name'], PATHINFO_EXTENSION);
        $audio_path = WP_CONTENT_DIR . '/uploads/dsp_media/user_audios/user_' . $_POST['user_id'] . '/' . $audio_name;

        $success = move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_path);
        
        if ( $success ) {
            $current_date                = date("Y-m-d H:i:s");
            $check_approve_audios_status = wpee_get_setting('authorize_audios');
            if ( $check_approve_audios_status->setting_status == 'Y' ) {
                $status = $wpdb->insert($dsp_member_audios_table, array(
                    'user_id'       => $_POST['user_id'],
                    'file_name'     => $audio_name,
                    'date_added'    => $current_date,
                    'status_id'     => 1,
                    'private_audio' => isset($_POST['make_private']) ? $_POST['make_private'] : 'N'
                ));
                
                if( ! $status ) {
                    die( json_encode( array( 'success' => false, 'message' => __("Error uploading audio file. Please try again.", "wpdating" ) ) ) );
                }

                $audio_detail = array(
                    'user_id'   => $_POST['user_id'],
                    'file_path' => get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_{$_POST['user_id']}/{$audio_name}",
                    'audio_id'  => $wpdb->insert_id,
                    'time_diff' => '0 ' . __('sec ago', 'wpdating')
                );
                dsp_add_news_feed( $_POST['user_id'], 'audio', $wpdb->insert_id );

                die( json_encode( array( 'success' => true, 'message' => __("Audio file uploaded successfully.", "wpdating" ), 'audio_detail' => $audio_detail ) ) );
            } 
            $wpdb->insert($dsp_member_audios_table, array(
                'user_id'       => $_POST['user_id'],
                'file_name'     => $audio_name,
                'date_added'    => $current_date,
                'status_id'     => 0,
                'private_audio' => isset($_POST['make_private']) ? $_POST['make_private'] : 'N'
            ));
            $dsp_tmp_member_audios_table = $wpdb->prefix . DSP_TEMP_MEMBER_AUDIOS_TABLE;
            $wpdb->insert($dsp_tmp_member_audios_table, array(
                't_audio_id'   => $wpdb->insert_id,
                't_user_id'    => $_POST['user_id'],
                't_filename'   => $audio_name,
                't_date_added' => $current_date,
                't_status_id'  => 0,
            ));

            die( json_encode( array( 'success' => true, 'message' => __("Your audio_file files will be approved within 24 hours.", "wpdating" ) ) ) );
        }

        die( json_encode( array( 'success' => false, 'message' => __("Error uploading audio file. Please try again.", "wpdating" ) ) ) );
    }
}
add_action( "wp_ajax_wpee_create_audio", "wpee_create_audio" );

/**
 * Delete user audio  
 *
 * Execute a ajax request for member audio delete.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_delete_audio') ){
    function wpee_delete_audio(){
        global $wpdb;
        $dsp_member_audios_table = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;

        $member_audio = $wpdb->get_row("SELECT * FROM {$dsp_member_audios_table} WHERE audio_file_id = '{$_POST['audio_id']}'");
    
        if( is_null( $member_audio ) ) {
            die( json_encode( array( 'success' => false, 'message' => __("Something went wrong. Please try again.", "wpdating") ) ) );
        }
    
        $status = $wpdb->delete( $dsp_member_audios_table, array('audio_file_id' => $member_audio->audio_file_id ));
    
        if( ! $status ) {
            die( json_encode( array( 'success' => false, 'message' => __("Failed to delete audio.", "wpdating") ) ) );
        }

        unlink(WP_CONTENT_DIR . "/uploads/dsp_media/user_audios/user_{$member_audio->user_id}/{$member_audio->file_name}");

        dsp_delete_news_feed( $member_audio->user_id, 'audio', $member_audio->audio_file_id );

        die( json_encode( array( 'success' => true, 'message' => __("Audio deleted successfully.", "wpdating") ) ) );
    }
}
add_action( "wp_ajax_wpee_delete_audio", "wpee_delete_audio" );

/**
 * Create member video
 *
 * Execute a ajax request for member video create.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_create_video') ){
    function wpee_create_video()
    {
        if ( $_FILES['video_file']['error'] > 0 ) {

            if ( $_FILES['video_file']['error'] == 4) {
                die( json_encode( array( 'success' => false, 'errors' => array( 'video-file' => __('No file selected', 'wpdating') ) ) ) );
            }

            die( json_encode( array( 'success' => false, 'message' => __("Error uploading video file. Please try again.", "wpdating" ) ) ) );
        }

        if ( $_FILES['video_file']['size'] >= 51200000) {
            die( json_encode( array( 'success' => false, 'errors' => array( 'video-file' => __('File is too large', 'wpdating') ) ) ) );
        }

        $permitted_file_type = array('video/quicktime', 'video/x-ms-wmv', 'video/mp4', 'video/avi', 'application/octet-stream');

        if (!in_array($_FILES['video_file']['type'], $permitted_file_type)) {
            die( json_encode( array( 'success' => false, 'errors' => array( 'video-file' => __('File type is not supported', 'wpdating') ) ) ) );
        }

        global $wpdb;
        $permitted_video_count   = wpee_get_setting('count_videos');
        $dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
        $count_uploaded_videos   = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_member_videos_table} WHERE user_id='{$_POST['user_id']}'");

        if ( $count_uploaded_videos >= $permitted_video_count ){
            die( json_encode( array( 'success' => false, 'message' => __( 'You can only upload', 'wpdating' ) . ' ' . $permitted_video_count . ' ' . __( 'videos.', 'wpdating' ) ) ) );
        }

        if (!file_exists( WP_CONTENT_DIR .'/uploads/dsp_media/user_videos')) {
            mkdir(WP_CONTENT_DIR . '/uploads/dsp_media/user_videos' , 0777);
        }

        if (!file_exists(WP_CONTENT_DIR . '/uploads/dsp_media/user_videos/user_' . $_POST['user_id'])) {
            mkdir(WP_CONTENT_DIR . '/uploads/dsp_media/user_videos/user_' . $_POST['user_id'], 0777);
        }

        $video_name = time() . '.' . pathinfo($_FILES['video_file']['name'], PATHINFO_EXTENSION);
        $video_path = WP_CONTENT_DIR . '/uploads/dsp_media/user_videos/user_' . $_POST['user_id'] . '/' . $video_name;

        $success = move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path);
        
        if ( $success ) {
            $current_date                = date("Y-m-d H:i:s");
            $check_approve_videos_status = wpee_get_setting('authorize_videos');
            if ( $check_approve_videos_status->setting_status == 'Y' ) {
                $status = $wpdb->insert($dsp_member_videos_table, array(
                    'user_id'       => $_POST['user_id'],
                    'file_name'     => $video_name,
                    'date_added'    => $current_date,
                    'status_id'     => 1,
                    'private_video' => isset($_POST['make_private']) ? $_POST['make_private'] : 'N'
                ));
                
                if( ! $status ) {
                    die( json_encode( array( 'success' => false, 'message' => __("Error uploading video file. Please try again.", "wpdating" ) ) ) );
                }

                $video_detail = array(
                    'user_id'   => $_POST['user_id'],
                    'file_path' => get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_videos/user_{$_POST['user_id']}/{$video_name}",
                    'video_id'  => $wpdb->insert_id,
                    'time_diff' => '0 ' . __('sec ago', 'wpdating')
                );

                dsp_add_news_feed( $_POST['user_id'], 'video', $wpdb->insert_id );

                die( json_encode( array( 'success' => true, 'message' => __("Video file uploaded successfully.", "wpdating" ), 'video_detail' => $video_detail ) ) );
            } 

            $wpdb->insert($dsp_member_videos_table, array(
                'user_id'       => $_POST['user_id'],
                'file_name'     => $video_name,
                'date_added'    => $current_date,
                'status_id'     => 0,
                'private_video' => isset($_POST['make_private']) ? $_POST['make_private'] : 'N'
            ));
            $dsp_tmp_member_videos_table = $wpdb->prefix . DSP_TEMP_MEMBER_VIDEOS_TABLE;
            $wpdb->insert($dsp_tmp_member_videos_table, array(
                't_video_id'   => $wpdb->insert_id,
                't_user_id'    => $_POST['user_id'],
                't_filename'   => $video_name,
                't_date_added' => $current_date,
                't_status_id'  => 0,
            ));

            die( json_encode( array( 'success' => true, 'message' => __("Your video file files will be approved within 24 hours.", "wpdating" ) ) ) );
        }

        die( json_encode( array( 'success' => false, 'message' => __("Error uploading video file. Please try again.", "wpdating" ) ) ) );
    }
}
add_action( "wp_ajax_wpee_create_video", "wpee_create_video" );

/**
 * Delete member video  
 *
 * Execute a ajax request for member video delete.
 *
 * @since 1.2.1
 */ 
if ( !function_exists('wpee_delete_video') ){
    function wpee_delete_video(){
        global $wpdb;
        $dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;

        $member_video = $wpdb->get_row("SELECT * FROM {$dsp_member_videos_table} WHERE video_file_id = '{$_POST['video_id']}'");
    
        if( is_null( $member_video ) ) {
            die( json_encode( array( 'success' => false, 'message' => __("Something went wrong. Please try again.", "wpdating") ) ) );
        }
    
        $status = $wpdb->delete( $dsp_member_videos_table, array('video_file_id' => $member_video->video_file_id ));
    
        if( ! $status ) {
            die( json_encode( array( 'success' => false, 'message' => __("Failed to delete video.", "wpdating") ) ) );
        }

        unlink(WP_CONTENT_DIR . '/uploads/dsp_media/user_videos/user_' . $member_video->user_id . '/' . $member_video->file_name);

        dsp_delete_news_feed( $member_video->user_id, 'video',  $member_video->video_file_id );

        die( json_encode( array( 'success' => true, 'message' => __("Video deleted successfully.", "wpdating") ) ) );
    }
}
add_action( "wp_ajax_wpee_delete_video", "wpee_delete_video" );

/**
 * Returns the state option list according to country
 *
 * Execute a ajax request for state options.
 *
 * @since 1.2.1
 */
if ( !function_exists('wpee_get_state_options') ){
    function wpee_get_state_options(){
        global $wpdb;

        $states  = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dsp_state WHERE country_id = '{$_GET['country']}' ORDER BY name" );
        $options = "<option value='0'>" . __('Select State', 'wpdating') . "</option>";
        if ( ! empty( $states ) ) {
            foreach ( $states as $state ) {
                $options .= "<option value='" . $state->state_id . "'> " . $state->name . "</option>";
            }
        }
        $output['content'] = $options;
        die( json_encode( $output ) );
    }
}
add_action( "wp_ajax_wpee_get_state_options", "wpee_get_state_options" );

/**
 * Returns the city option list according to country
 *
 * Execute a ajax request for city options.
 *
 * @since 1.2.1
 */
if ( !function_exists('wpee_get_city_options') ){
    function wpee_get_city_options(){
        global $wpdb;
        $state     = ( isset( $_GET['state'] ) ) ? $_GET['state'] : 0;
        $sql_query = "SELECT * FROM {$wpdb->prefix}dsp_city WHERE country_id = '{$_GET['country']}' 
                        AND state_id = '{$state}' ORDER BY name";

        $cities  = $wpdb->get_results( $sql_query );
        $options = "<option value='0'>" . __('Select City', 'wpdating') . "</option>";
        if ( ! empty( $cities ) ) {
            foreach ( $cities as $city ) {
                $options .= "<option value='" . $city->city_id . "'> " . $city->name . "</option>";
            }
        }
        $output['content'] = $options;
        die( json_encode( $output ) );
    }
}
add_action( "wp_ajax_wpee_get_city_options", "wpee_get_city_options" );

/**
 * Change the profile picture of the requested user
 *
 * Execute a ajax request for profile picture update.
 *
 * @since 1.2.1
 */
function wpee_change_profile_photo(){
    // check security nonce which one we created in html form and sending with data.
    if (
            ! isset( $_POST['nonce'] ) ||
            ( ! isset( $_POST['user_id'] ) || ( isset( $_POST['user_id'] ) && empty( $_POST['user_id'] ) ) ) ||
            ! wp_verify_nonce( $_POST['nonce'], 'wpee_profile_photo_nonce' )
    ) {
        die( json_encode( ['success' => false, 'message' => __( 'Something went wrong!', 'wpdating' ) ] ) );
    }
    $user_id = $_POST['user_id'];

    $response = [];
    if ( ! file_exists( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id ) ) {

        if ( ! file_exists( ABSPATH . '/wp-content/uploads' ) ) {
            mkdir( ABSPATH . '/wp-content/uploads', 0777 );
        }
        if ( ! file_exists(ABSPATH . '/wp-content/uploads/dsp_media' ) ) {
            mkdir( ABSPATH . '/wp-content/uploads/dsp_media', 0777 );
        }
        if ( ! file_exists( ABSPATH . '/wp-content/uploads/dsp_media/user_photos' ) ) {
            mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos', 0777 );
        }

        // it will default to 0755 regardless
        mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id,
            0755 );
        mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs',
            0755 );
        mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1',
            0755 );
        // Finally, chmod it to 777
        chmod( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id,
            0777);
        chmod( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs',
            0777 );
        chmod( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1',
            0777 );
    } else if ( ! file_exists( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs' ) ) {
        mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs',
            0755);
        mkdir( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1',
            0755);

        chmod( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs',
            0777 );
        chmod( ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1',
            0777 );
    }

    $extension = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    if ( ! in_array( $_FILES['file']['type'], $extension ) ) {
        die( json_encode( ['success' => false, 'message' => __( 'Unknown Extension!', 'wpdating' ) ] ) );
    }

    switch ( $_FILES['file']['type'] ) {
        case 'image/jpg':
            $new_name = 'image_' . time() . '.jpg';
            break;
        case 'image/jpeg':
            $new_name = 'image_' . time() . '.jpeg';
            break;
        case 'image/png':
            $new_name = 'image_' . time() . '.png';
            break;
        case 'image/gif':
            $new_name = 'image_' . time() . '.gif';
            break;
    }

    $image_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;

    $copied = copy( $_FILES['file']['tmp_name'], $image_path );
    if ( ! $copied ) {
        die( json_encode( ['success' => false, 'message' => __( 'Unknown Extension!', 'wpdating' ) ] ) );
    }

    $thumb1_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $new_name;
    Wpdating_Elementor_Extension_Helper_Functions::square_image_crop( $image_path, $thumb1_path, 250 );

    $thumb_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $new_name;
    Wpdating_Elementor_Extension_Helper_Functions::square_image_crop( $image_path, $thumb_path, 350 );

    global $wpdb, $wpee_general_settings;

    $member_photo = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}dsp_members_photos 
                                                WHERE user_id = {$user_id}" );
    if ( $wpee_general_settings->authorize_photos->status == 'Y' ) {
        if ( $member_photo ) {
            unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
                $user_id . "/" . $member_photo->picture );
            unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
                $user_id . "/thumbs/thumb_" . $member_photo->picture );
            unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
                $user_id . "/thumbs1/thumb_" . $member_photo->picture );
            $wpdb->update($wpdb->prefix . 'dsp_members_photos',
                array(
                    'picture' => $new_name,
                    'status_id' => 1
                ),
                array( 'photo_id' => $member_photo->photo_id )
            );
            $photo_id = $member_photo->photo_id;
        } else {
            $wpdb->insert($wpdb->prefix . 'dsp_members_photos', array(
                    'picture' => $new_name,
                    'status_id' => 1,
                    'user_id' => $user_id
            ) );
            $photo_id = $wpdb->insert_id;
        }

        $member_photo = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}dsp_members_photos 
                                                WHERE photo_id = {$photo_id}" );

        $response['success']    = true;
        $response['message']    = __( 'Profile Picture updated successfully.', 'wpdating' );
        $response['image_path'] = get_site_url() . "/wp-content/uploads/dsp_media/user_photos/user_" .
            $user_id . "/thumbs/thumb_" . $member_photo->picture;
        dsp_delete_news_feed( $user_id, 'profile_photo' );
        dsp_add_news_feed( $user_id, 'profile_photo' );
        dsp_add_notification( $user_id, 0, 'profile_photo' );

        Wpdating_Elementor_Extension_Helper_Functions::clear_wp_rocket_cache();

        die( json_encode( $response ) );
    }

    $tmp_member_photo = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}dsp_tmp_members_photos
                                                    WHERE t_user_id = '{$user_id}'");
    if ( $tmp_member_photo ) {
        unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
            $user_id . "/" . $tmp_member_photo->t_picture );
        unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
            $user_id . "/thumbs/thumb_" . $tmp_member_photo->t_picture );
        unlink( ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" .
            $user_id . "/thumbs1/thumb_" . $tmp_member_photo->t_picture );
        $wpdb->update($wpdb->prefix . 'dsp_tmp_members_photos', array(
            't_picture'   => $new_name,
            't_status_id' => 0
        ), array( 't_user_id'   => $user_id ) );
    } else {
        $wpdb->insert($wpdb->prefix . 'dsp_tmp_members_photos', array(
            't_picture'   => $new_name,
            't_status_id' => 0,
            't_user_id'   => $user_id
        ) );
    }

    if ( $member_photo ) {
        $response['image_path'] = get_site_url() . "/wp-content/uploads/dsp_media/user_photos/user_" .
            $user_id . "/thumbs/thumb_" . $member_photo->picture;
    } else {
        $response['image_path'] = WPEE_IMG_URL . "/default-profile-img.jpg";;
    }

    $response['success'] = true;
    $response['message'] = __( 'Pictures you uploaded will be approved within 24 hours.', 'wpdating' );

    die(json_encode($response));
}
add_action( "wp_ajax_wpee_change_profile_photo", "wpee_change_profile_photo" );

