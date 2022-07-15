<?php 
global $wpdb;

$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$dsp_user_virtual_gifts = $wpdb->prefix. 'dsp_user_virtual_gifts';
$dsp_my_friends_table   = $wpdb->prefix . 'dsp_my_friends';

$check_my_friend_module         = wpee_get_setting('my_friends');
$check_virtual_gifts_mode       = wpee_get_setting('virtual_gifts');
$check_free_email_access_mode   = wpee_get_setting('free_email_access');

$profile_subtab   = get_query_var( 'profile-subtab' );
$profile_subtab   = !empty( $profile_subtab ) ? $profile_subtab : '';

$user_id          = wpee_profile_id();
$current_user_id  = get_current_user_id();
$profile_link     = trailingslashit(wpee_get_profile_url_by_id( $current_user_id ));


$dsp_my_friends_table       = $wpdb->prefix . 'dsp_my_friends';
$check_my_friend_module     = wpee_get_setting('my_friends');
$check_virtual_gifts_mode   = wpee_get_setting('virtual_gifts');
$profile_subtab             = get_query_var( 'profile-subtab' );
$profile_subtab             = !empty( $profile_subtab ) ? $profile_subtab : '';
$user_id                    = wpee_profile_id();
$current_user_id            = get_current_user_id();

$check_max_gifts = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where status_id=1 and member_id='$user_id'");
$no_of_credits   = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$current_user_id'");
$no_of_credits   = ! empty($no_of_credits) ? $no_of_credits : 0;

$check_credit_mode = wpee_get_setting('credit');

?>
<ul class="wpee-notification-links different-user">
    <li>
        <a href="javascript:void(0);" title="<?php echo __('Add to Favorites', 'wpdating'); ?>" data-nonce="<?php echo wp_create_nonce('wpee_add_favourites_nonce');?>" data-fav-uid="<?php echo esc_attr($user_id );?>" class="wpee-add-fav-btn" ><i class="fa fa-heart"></i><span class="wpee-tooltip"><?php echo __('Add to Favorites', 'wpdating'); ?></span></a>
    </li>

    <?php
    if( is_user_logged_in() ){
    	if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
        <li>
            <a href="javascript:void(0);" class="wpee-gift-list" data-nonce="<?php echo wp_create_nonce('wpee_show_gift_popup_nonce');?>" data-profile-id="<?php echo esc_attr($user_id);?>"><i class="fa fa-gift"></i><span class="wpee-tooltip"><?php echo __('Send Gift', 'wpdating'); ?></span></a>
            <div class="wpee-gift-dropdown">
                <?php
                if(( !is_wp_error(wpee_check_membership('Virtual Gifts', $current_user_id )) && wpee_check_membership('Virtual Gifts', $current_user_id ) == true ) || (($check_credit_mode->setting_status == 'Y') && ($no_of_credits >= dsp_get_credit_setting_value('gifts_per_credit')))){
                    do_action( 'wpee_gift_dropdown' );
                }
                else{
                    wpee_display_error_message(wpee_check_membership('Virtual Gifts', $current_user_id ));
                }?>
            </div>
        </li>
    <?php } ?>

    <?php
        if ($check_free_email_access_mode->setting_status == 'Y'){ ?>
            <li>
                <a href="<?php echo esc_url( trailingslashit( $profile_link . 'message/compose' ) . '?receiver_id=' . $user_id);?>" data-profile-id="<?php echo esc_attr($user_id);?>">
                    <i class="fa fa-envelope-o"></i>
                    <span class="wpee-tooltip"><?php echo __('Send Message', 'wpdating'); ?>
                    </span>
                </a>
            </li>
    <?php }

    }?>

    <li>
        <a href="javascript:void(0);" title="<?php echo __('Report User', 'wpdating'); ?>" data-nonce="<?php echo wp_create_nonce('wpee_report_user_nonce');?>" data-profile-id="<?php echo esc_attr($user_id );?>" class="wpee-report-user-btn" ><i class="fa fa-bullhorn"></i><span class="wpee-tooltip"><?php echo esc_html__('Report User', 'wpdating'); ?></span></a>
        <div class="wpee-report-user-form-wrap wpee-popup-message">
            <div class="wpee-inner-msg">
                <form id="wpee-report-user" method="post">
                    <?php wp_nonce_field('wpee_report_user_nonce','report-nonce');?>
                    <input type="hidden" name="profile-id" value="<?php echo esc_attr($user_id);?>">
                    <div class="form-group">
                        <label><?php esc_html_e( 'Report Message', 'wpdating' );?></label>
                        <textarea name="report-message"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="report-submit" value="<?php esc_html_e('Submit','wpdating');?>">
                    </div>
                </form>
            </div>
        </div>
    </li>
</ul>