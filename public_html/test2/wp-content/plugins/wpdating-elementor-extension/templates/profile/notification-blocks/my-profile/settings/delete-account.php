<?php
global $wpdb;

include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_admin_emails_table = $wpdb->prefix . DSP_ADMIN_EMAILS;
$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
$dsp_member_audios_table = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_profile_question_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
$dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
$dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
$dsp_show_profile_table = $wpdb->prefix . DSP_LIMIT_PROFILE_VIEW_TABLE;
$update_profile_status = isset($_REQUEST['update_profile_status']) ? $_REQUEST['update_profile_status'] : '';
$Delete_profile = isset($_REQUEST['Delete_profile']) ? $_REQUEST['Delete_profile'] : '';
$status_id = isset($_REQUEST['status_id']) ? $_REQUEST['status_id'] : '';

$messae_send_date = date('Y-m-d H:i:s');
$request_url = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page2";
$edit_image = WPDATE_URL . "/images/icon_edit.gif";
$user_id = get_current_user_id();

function remove_image($image)
{
    $image_files = scandir($image);
    chdir($image);
    foreach ($image_files as $image_file) {
        if ($image_file != "." and $image_file != "..") {
            if (is_dir($image_file)) {
                remove_image($image_file);
            } else {
                unlink($image_file);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($image);
}

//"""""end of function

function remove_video($name)
{
    $ars = scandir($name);
    chdir($name);
    foreach ($ars as $ar) {
        if ($ar != "." and $ar != "..") {
            if (is_dir($ar)) {
                //echo "found directory $ar <br />";
                remove_dir($ar);
            } else {
                //echo "found a file $ar <br />";
                unlink($ar);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($name);
}

//"""""end of function

function remove_audio($audio)
{
    $audio_files = scandir($audio);
    chdir($audio);
    foreach ($audio_files as $audio_file) {
        if ($audio_file != "." and $audio_file != "..") {
            if (is_dir($audio_file)) {
                //echo "found directory $ar <br />";
                remove_dir($audio_file);
            } else {
                //echo "found a file $ar <br />";
                unlink($audio_file);
            }//"""""""end of if
        }//""""""-end of if
    }//"""""-end of foreach
    chdir("..");
    rmdir($audio);
}

if (isset($_POST['submit'])) {
    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='4'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
    $sender_name = $sender_details->display_name;
    $root_url = get_bloginfo('url');
    $url = site_url();
    $email_subject = $email_template->subject;
    $mem_email_subject = $email_subject;

    $message1 = "Hi $sender_name, $reciver_name has delete his account";
    $email_subject1 = "User has delete his account";
    $admin_email = $sender_details->user_email;
    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail($admin_email, $email_subject1,$message1);

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;

// Add User Data to Deleted Users Table //

    $infoFromUsersTable = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID=$user_id");
    $infoFromProfilesTable = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id=$user_id");
    $infoFromMembersPhotoTable = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id=$user_id");
    $current_datetime = date('Y-m-d H:i:s');
    $userRole = array_keys(get_user_meta($user_id, 'wp_capabilities', true));
    $wpdb->query("INSERT INTO " . $wpdb->prefix . DSP_DELETED_USERS_TABLE . " SET
                                            user_id='$infoFromProfilesTable->user_id',
                                            user_profile_id='$infoFromProfilesTable->user_profile_id', 
                                            country_id='$infoFromProfilesTable->country_id',
                                            city_id='$infoFromProfilesTable->city_id',
                                            gender='$infoFromProfilesTable->gender',
                                            user_login='$infoFromUsersTable->user_login', 
                                            user_pass='$infoFromUsersTable->user_pass',
                                            user_email='$infoFromUsersTable->user_email',
                                            display_name='$infoFromUsersTable->display_name',
                                            user_registered='$infoFromUsersTable->user_registered', 
                                            deleted_on='$current_datetime',
                                            picture='$infoFromMembersPhotoTable->picture'
                           ");

// Copy User Profile Picture to Deleted Users Profile Picture //
    $src = WP_CONTENT_DIR . '/uploads/dsp_media' . '/user_photos/user_' . $user_id . '/';
    $dst = WP_CONTENT_DIR . '/uploads/dsp_media/deleted_users/';
    if (!is_dir($dst)) {
        mkdir($dst, 0777, true);
    }
    $files = glob($src . "*.*");
    if (!empty($files) && $files != false) {
        foreach ($files as $file) {
            $filename = 'user_' . str_replace($src, '', $file);
            $file_to_go = str_replace($src, $dst, $file);
            copy($file, $file_to_go);
        }
    }
// End Copy
// End Add User Data
//***************************** Start Delete Video folder ***************************************
    $pluginpath = WP_CONTENT_DIR . '/uploads/dsp_media';
    $name = $pluginpath . '/user_videos/user_' . $user_id;
    if (file_exists($name)) {
        remove_video($name);
        $wpdb->query("DELETE FROM $dsp_member_videos_table WHERE user_id = '$user_id'");
    }
//***************************** End Delete Video folder ***************************************
//***************************** Start Delete Audio folder ***************************************
    $audio = $pluginpath . '/user_audios/user_' . $user_id;
    if (file_exists($audio)) {
        remove_audio($audio);
        $wpdb->query("DELETE FROM $dsp_member_audios_table WHERE user_id = '$user_id'");
    }
//***************************** End Delete Audio folder  ***************************************
//***************************** Start Delete image folder  ***************************************
    $image = $pluginpath . '/user_photos/user_' . $user_id;
    if (file_exists($image)) {
        remove_image($image);
        $wpdb->query("DELETE FROM $dsp_members_photos WHERE user_id = '$user_id'");
    }
//***************************** End Delete image folder  ***************************************
    $wpdb->query("DELETE FROM $dsp_user_profiles WHERE user_id = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_user_albums_table WHERE user_id = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE user_id = '$user_id'");
    if ($userRole[0] != 'administrator')
        $wpdb->query("DELETE FROM $dsp_user_table WHERE ID = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_profile_question_details_table WHERE user_id = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_user_partner_profiles_table WHERE user_id  = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_members_partner_photos_table WHERE user_id = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_partner_profile_question_details_table WHERE user_id = '$user_id'");
    $wpdb->query("DELETE FROM $dsp_show_profile_table WHERE user_id = '$user_id'");

// delete from other tables - start
    $toDeleteTables = array('usermeta',
        DSP_USER_VIRTUAL_GIFT_TABLE,
        DSP_MY_BLOGS_TABLE,
        DSP_MATCH_CRITERIA_TABLE,
        DSP_MEET_ME_TABLE,
        DSP_USER_NOTIFICATION_TABLE,
        DSP_USER_ONLINE_TABLE,
        DSP_USER_PRIVACY_TABLE,
        DSP_USER_SEARCH_CRITERIA_TABLE,
        DSP_NEWS_FEED_TABLE,
        //DSP_CREDITS_USAGE_TABLE,
        DSP_DATE_TRACKER_TABLE,
        DSP_RATING_USER_PROFILE_TABLE,
        DSP_SKYPE_TABLE,
        DSP_TEMP_INTEREST_TAGS_TABLE
    );
    foreach ($toDeleteTables as $toDeleteTable) {
        $wpdb->query("DELETE FROM " . $wpdb->prefix . $toDeleteTable . " WHERE user_id = '$user_id'");
    }

    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_MY_FRIENDS_TABLE . " WHERE user_id=$user_id OR friend_uid=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE . " WHERE user_id=$user_id OR favourite_user_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_COUNTER_HITS_TABLE . " WHERE user_id=$user_id OR member_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_USER_COMMENTS . " WHERE user_id=$user_id OR member_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_NOTIFICATION_TABLE . " WHERE user_id=$user_id OR member_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE . " WHERE user_id=$user_id OR block_member_id=$user_id");

    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_ONE_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_REQUEST_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_MEMBER_WINKS_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_EMAILS_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id");
    $wpdb->query("DELETE FROM " . $wpdb->prefix . DSP_CHAT_TABLE . " WHERE sender_id=$user_id");
    $member_page_url =  Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url();
    ?>
    <script>
        window.location.href = "<?php echo esc_url( $member_page_url );?>";
    </script>
    <?php
}

?>
<div class="box-border">
<div class="box-pedding">
<form action="" method="post">
    <div class="btn-blue-wrap">
        <input type="submit" name="submit" onclick="if (!confirm('<?php echo __('Are you sure you want to delete your account permanently?', 'wpdating'); ?>')) return false;" class="dspdp-btn dspdp-btn-danger" value="<?php echo __('Delete Account Permanently', 'wpdating') ?>"/>
    </div>
</form>
</div>
</div>