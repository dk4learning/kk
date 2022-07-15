<?php
/*
 Copyright (C) www.wpdating.com - All Rights Reserved!
 Author - www.wpdating.com
 WordPress Dating Plugin
 contact@wpdating.com
*/
// ----------------------------------------- Display top menu header Menus ------------------------------ // 
$pageurl        = get('pid');
$get_sender_id  = get('sender_ID');
$request_Action = get('Act');
if (($request_Action == "R") && ($get_sender_id != "")) {
    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if 
$count_messages              = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");
$count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
$uId                         = get_current_user_id();
$trendingStatusOn            = apply_filters('dsp_check_trending_status', $uId);
$userProfileDetails          = apply_filters('dsp_get_profile_details', $uId);
$userProfileDetailsExist     = $userProfileDetails != false ? true : false;
?>
<div class="dsp-tab-container dspdp-tab-container">
    <div class="dsp-line">
        <div <?php if (($pageurl == "1") || ($pageurl == "")) { ?>class="dsp_tab1-active"
             <?php } else { ?>class="dsp_tab1" <?php } ?>>
            <?php
            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            ?>
            <a href="<?php echo $root_link . "home/mypage/"; ?>"
               title="<?php echo __('Home', 'wpdating') ?>"><?php echo __('Home', 'wpdating') ?></a>
        </div>
        <?php } else { ?>
        <a href="<?php echo wp_login_url(get_permalink()); ?>"
           title="Login"><?php echo __('Home', 'wpdating') ?></a></div>
    <?php } ?>
    <?php if ($check_free_email_access_mode->setting_status == 'Y'): ?>
    <div <?php if ($pageurl == 14) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <?php
        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <?php if ($count_messages == '0') { ?>
            <a href="<?php echo $root_link . "email/inbox/"; ?>"
               title="<?php echo __('Messages', 'wpdating') ?>"><?php echo __('Messages', 'wpdating') ?></a>
        <?php } else { ?>
            <a href="<?php echo $root_link . "email/inbox/"; ?>"
               title="<?php echo __('Messages', 'wpdating') ?>"><?php echo __('Messages', 'wpdating') ?>
                &nbsp;(<?php echo $count_messages ?>)</a>
        <?php } ?>
    </div>
    <?php } else { ?>
    <a href="<?php echo wp_login_url(get_permalink()); ?>"
       title="Login"><?php echo __('Messages', 'wpdating') ?></a></div>
<?php } ?>
<?php endif; ?>
<div <?php if ($pageurl == 2) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <a href="<?php echo $root_link . "edit/"; ?>"
       title="<?php echo __('Edit Profile', 'wpdating') ?>"><?php echo __('Edit Profile', 'wpdating') ?></a>
</div>
<?php } else { ?>
    <a href="<?php echo wp_login_url(get_permalink()); ?>"
       title="Login"><?php echo __('Edit Profile', 'wpdating') ?></a></div>
<?php } ?>
<?php if (($check_picture_gallery_mode->setting_status == 'Y') || ($check_audio_mode->setting_status == 'Y') || ($check_video_mode->setting_status == 'Y')) { ?>
<div <?php if ($pageurl == 4) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo $root_link . "media/photo/"; ?>"
           title="<?php echo __('Media', 'wpdating') ?>"><?php echo __('Media', 'wpdating') ?></a></div>
    <?php } else { ?>
        <a href="<?php echo wp_login_url(get_permalink()); ?>"
           title="Login"><?php echo __('Media', 'wpdating') ?></a></div>
    <?php } ?>
<?php } ?>
<?php if ($check_chat_mode->setting_status == 'Y') { ?>
<div <?php if ($pageurl == 15) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo $root_link . "chat/"; ?>"
           title="<?php echo __('Chat', 'wpdating') ?>"><?php echo __('Chat', 'wpdating') ?></a></div>
    <?php } else { ?>
        <a href="<?php echo wp_login_url(get_permalink()); ?>"
           title="Login"><?php echo __('Chat', 'wpdating') ?></a></div>
    <?php } ?>
<?php } ?>
<div <?php if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')){
 if( strpos($_SERVER["REQUEST_URI"],'members/instant-chat')) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
    <?php
    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
        ?>
        <a href="<?php echo $root_link . "instant-chat/"; ?>"
           title="<?php echo __('Instant Chat','wpdating'); ?>"><?php echo __('Instant Chat','wpdating'); ?></a></div>
    <?php }
    }?>
<?php
// if member is login then this menu will be display
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <div <?php if ($pageurl == 5) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "search/basic_search/"; ?>"
           title="<?php echo __('Search', 'wpdating') ?>"><?php echo __('Search', 'wpdating') ?></a>
    </div>
<?php } ?>
<?php
// if member is login then this menu will be display
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <div <?php if ($pageurl == 6) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "setting/notification/"; ?>"
           title="<?php echo __('Settings', 'wpdating') ?>"><?php echo __('Settings', 'wpdating') ?></a>
    </div>
<?php } ?>
<?php
// if member is login then this menu will be display
if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
    ?>
    <div <?php if ($pageurl == 13) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <?php if (is_user_logged_in()) {  // CHECK MEMBER LOGIN  ?>
            <a href="<?php echo $root_link . "extras/viewed_me/"; ?>"
               title="<?php echo __('Extras', 'wpdating') ?>"><?php echo __('Extras', 'wpdating') ?></a>
        <?php } else { ?>
            <a href="<?php echo wp_login_url(get_permalink()); ?>"
               title="Login"><?php echo __('Extras', 'wpdating') ?></a>
        <?php } ?>
    </div>
    <div <?php if ($pageurl == 10) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1" <?php } ?>>
        <a href="<?php echo $root_link . "online_members/"; ?>"
           title="<?php echo __('Online', 'wpdating') ?>"><?php echo __('Online', 'wpdating') ?></a>
    </div>
<?php } ?>
<?php if ($check_help_tab->setting_status == 'Y') { ?>
    <div <?php if ($pageurl == 16) { ?>class="dsp_tab1-active" <?php } else { ?>class="dsp_tab1 last" <?php } ?>>
        <a href="<?php echo $root_link . "help/"; ?>"
           title="<?php echo __('Help', 'wpdating') ?>"><?php echo __('Help', 'wpdating') ?></a>
    </div>
<?php } ?>
<div class="clr"></div>
</div>


<?php if (is_user_logged_in() && ($pageurl == 17)) {
    echo '</div>';
} //closed a div on stories ?>
<?php

$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
$gender                = isset($exist_profile_details) ? $exist_profile_details->gender : ' ';
if ($pageurl == 1) {
    include_once(WP_DSP_ABSPATH . "headers/mypage_header.php");
} elseif ($pageurl == 2) {
    include_once(WP_DSP_ABSPATH . "headers/edit_profile_header.php");
} elseif ($pageurl == 3) {
    include_once(WP_DSP_ABSPATH . "headers/view_profile_header.php");
} elseif ($pageurl == 4) {
    include_once(WP_DSP_ABSPATH . "headers/add_photos_header.php");
} elseif ($pageurl == 5) {
    include_once(WP_DSP_ABSPATH . "headers/user_search_header.php");
} elseif ($pageurl == 6) {
    include_once(WP_DSP_ABSPATH . "headers/user_settings_header.php");
} elseif ($pageurl == 7) {
    include_once(WP_DSP_ABSPATH . "add_to_favourites.php");
} elseif ($pageurl == 8) {
    include_once(WP_DSP_ABSPATH . "add_as_friend.php");
} elseif ($pageurl == 9) {
    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
} elseif ($pageurl == 10) {
    if ($check_force_profile_mode->setting_status == "Y") {
        // if force profile mode is OFF
        $check_force_profile_msg = check_free_force_profile_feature($user_id);
        if ($check_force_profile_msg == "Approved") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } elseif ($check_force_profile_msg == "Access") {
            include_once(WP_DSP_ABSPATH . "members/loggedin/online/dsp_online_other_users.php");
        } elseif ($check_force_profile_msg == "NoAccess") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } elseif ($check_force_profile_msg == "Expired") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        } elseif ($check_force_profile_msg == "Onlypremiumaccess") {
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        }
    } else {
        include_once(WP_DSP_ABSPATH . "members/loggedin/online/dsp_online_other_users.php");
    }
} elseif ($pageurl == 11) {
    include_once(WP_DSP_ABSPATH . "dsp_fetch_geography.php");
} elseif ($pageurl == 13) {
    include_once(WP_DSP_ABSPATH . "headers/extras_header.php");
} elseif ($pageurl == 14) {
    $access_feature_name  = "Access Email";
    $check_membership_msg = check_membership($access_feature_name, $user_id);
    $addDiv               = $check_free_email_access_mode->setting_status == 'N' ? true : false;
    $check_free_email_access_mode->setting_status == 'Y' ? include_once(WP_DSP_ABSPATH . "user_dsp_my_email.php") : include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
} elseif ($pageurl == 16) {
    if ($check_help_tab->setting_status == 'Y') {
        include_once(WP_DSP_ABSPATH . "dsp_help.php");
    }
} elseif ($pageurl == 17) {
    include_once(WP_DSP_ABSPATH . "dsp_guest_stories.php");
} elseif ($pageurl == 15) {
    $access_feature_name = "Group Chat";
    if ($check_free_mode->setting_status == "N") {  // free mode is off 
        if ($check_force_profile_mode->setting_status == "Y") {
            // if force profile mode is OFF
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Approved") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } elseif ($check_force_profile_msg == "Access") {
                include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
            } elseif ($check_force_profile_msg == "NoAccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } elseif ($check_force_profile_msg == "Expired") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            } elseif ($check_force_profile_msg == "Onlypremiumaccess") {
                include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
            }
        } else {
            include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
        }
    } else {
        if ($_SESSION['free_member']) {
            include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
        } else {
            if ($check_force_profile_mode->setting_status == "Y") {
                // if force profile mode is OFF
                $check_force_profile_msg = check_free_force_profile_feature($user_id);
                if ($check_force_profile_msg == "Approved") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } elseif ($check_force_profile_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
                } elseif ($check_force_profile_msg == "NoAccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } elseif ($check_force_profile_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } elseif ($check_force_profile_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }
            } else {
                $check_membership_msg = check_membership($access_feature_name, $user_id);
                if ($check_membership_msg == "Expired") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                } elseif ($check_membership_msg == "Access") {
                    include_once(WP_DSP_ABSPATH . "user_dsp_chat.php");
                } elseif ($check_membership_msg == "Onlypremiumaccess") {
                    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
                }

            }
        }
    }
}
else if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php') && strpos($_SERVER["REQUEST_URI"],'/instant-chat')){
    echo "</div>";
    do_action('wp_instant_chat_page');
}else {
    include_once(WP_DSP_ABSPATH . "headers/mypage_header.php");
}
?>
