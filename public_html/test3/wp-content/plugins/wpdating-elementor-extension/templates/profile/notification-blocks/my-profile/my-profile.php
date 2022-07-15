<?php 
global $wpdb;
$dsp_user_virtual_gifts     = $wpdb->prefix. 'dsp_user_virtual_gifts';
$dsp_my_friends_table       = $wpdb->prefix . 'dsp_my_friends';
$dsp_message_table          = $wpdb->prefix. 'dsp_messages';
$check_message_mode         = wpee_get_setting('userplane_instant_messenger');
$check_my_friend_module     = wpee_get_setting('my_friends');
$check_virtual_gifts_mode   = wpee_get_setting('virtual_gifts');
$profile_tab                = get_query_var( 'profile-tab' );
$profile_subtab             = get_query_var( 'profile-subtab' );
$profile_subtab             = !empty( $profile_subtab ) ? $profile_subtab : '';
$user_id                    = wpee_profile_id();
$current_user_id            = get_current_user_id();
$profile_link               = trailingslashit(wpee_get_profile_url_by_id($user_id)); ?>
<ul class="wpee-notification-links">
    <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
        <li class="wpee-menu <?php if (($profile_tab == "gifts")) { echo "wpee_active_link"; } ?>">
            <?php $count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$current_user_id AND status_id=0"); ?>
                <a href="<?php echo trailingslashit( $profile_link )."gifts"; ?>"><i
                            class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?>
                    <?php if( $count_friends_virtual_gifts > 0 ){ ?>
                        <span class="wpee-count-num"><?php echo esc_html($count_friends_virtual_gifts); ?></span>
                    <?php } ?>
                </a>
        </li>
    <?php } 
    if ( $check_message_mode->setting_status == 'Y'): ?>
    <li class="wpee-menu <?php if ($profile_tab == "message") { echo 'wpee_active_link'; } ?>">
        <?php $count_message = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_message_table WHERE receiver_id=$current_user_id AND message_read='N' AND delete_message = 0"); ?>
            <a href="<?php echo trailingslashit( $profile_link )."message"; ?>"><i
                        class="fa fa-envelope"></i><?php echo __('Message', 'wpdating'); ?>
                <?php if( $count_message > 0 ){ ?>
                    <span class="wpee-count-num"><?php echo esc_html( $count_message); ?></span>
                <?php } ?>
            </a>
    </li>
    <?php endif;
    if ($check_my_friend_module->setting_status == 'Y') { ?>
        <?php $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'"); 
        ?>
        <li class="wpee-menu <?php if (($profile_tab == "friend-request")) { echo "wpee_active_link"; } ?>">
            <a href="<?php echo trailingslashit( $profile_link )."friend-request"; ?>"><i
                        class="fa fa-users"></i><?php echo __('Friend Request', 'wpdating'); ?>
                <?php if( $count_friends_request > 0 ){ ?>
                    <span class="wpee-count-num"><?php echo esc_html( $count_friends_request); ?></span>
                <?php } ?>
            </a>
        </li>
    <?php } ?>

    <li class="wpee-menu <?php if (($profile_tab == "settings") && $profile_subtab != 'upgrade-account') { echo "wpee_active_link"; } ?>">
        <a href="<?php echo trailingslashit( $profile_link )."settings"; ?>"><i class="fa fa-cog"></i><?php echo __('Settings', 'wpdating'); ?>
        </a>
    </li>
   
    <li class="wpee-menu wpee-others">
        <a href="javascript:void(0);"><i class="fa fa-ellipsis-h"></i><span><?php echo __('Others', 'wpdating'); ?></span>
        </a>
        <ul class="wpee-sub-menu no-list">
            <li class="wpee-menu <?php echo ($profile_tab == "viewed") ? "wpee_active_link" : '';?>">
                <a href="<?php echo trailingslashit( $profile_link )."viewed"; ?>">
                    <i class="fa fa-eye"></i>
                    <span><?php echo __( 'I Viewed', 'wpdating' ) . ' / ' . __('Viewed Me', 'wpdating' ); ?></span>
                </a>
            </li>
            <li class="wpee-menu <?php echo ($profile_subtab == "edit-profile") ? "wpee_active_link" : '';?>">
                <a href="<?php echo trailingslashit( $profile_link )."edit-profile/"; ?>">
                    <i class="fa fa-pencil-square-o"></i>
                    <span><?php echo __('Edit Profile', 'wpdating'); ?></span>
                </a>
            </li>
            <li class="wpee-menu <?php echo ($profile_subtab == "upgrade-account") ? "wpee_active_link" : '';?>">
                <a href="<?php echo trailingslashit( $profile_link )."settings/upgrade-account/"; ?>">
                    <i class="fa fa-level-up"></i>
                    <span><?php echo __('Upgrade Account', 'wpdating'); ?></span>
                </a>
            </li>
        </ul>
    </li>
</ul>