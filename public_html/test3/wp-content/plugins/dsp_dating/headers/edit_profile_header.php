<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$current_user_id        = get_current_user_id();
$dsp_user_profile_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$user_profile           = $wpdb->get_row("SELECT * FROM {$dsp_user_profile_table} WHERE user_id = '{$current_user_id}'");
$edit_profile_pageurl   = get('pagetitle');
?>
	<div class="line">
	    <div class="<?php echo ($edit_profile_pageurl == "my_profile") ? "dsp_tab1-active" : "dsp_tab1";?>">
	        <a href="<?php echo $root_link . "edit/my_profile/"; ?>"><?php echo __('My Profile', 'wpdating'); ?></a>
	    </div>
        <div class="<?php echo ($edit_profile_pageurl == "edit_my_location") ? "dsp_tab1-active" : "dsp_tab1";?>">
            <?php if ( $user_profile && !empty($user_profile->lat) && !empty($user_profile->lng) ) : ?>
                <a href="<?php echo $root_link . "edit/edit_my_location/?lat={$user_profile->lat}&lng={$user_profile->lng}"; ?>" ><?php echo __('Edit Current Location', 'wpdating') ?></a>
            <?php else: ?>
                <a href="<?php echo $root_link . "edit/edit_my_location/"; ?>"  data-siteurl = "<?php echo site_url();?>" id = 'edit_location'><?php echo __('Edit Current Location', 'wpdating') ?></a>
            <?php endif; ?>
        </div>
	    <?php if(($gender == 'C')):?>
        <div class="<?php echo ($edit_profile_pageurl == "partner_profile") ? "dsp_tab1-active" : "dsp_tab1 last";?>">
	        <a href="<?php echo $root_link . "edit/partner_profile/"; ?>"><?php echo __('Partner Profile', 'wpdating'); ?></a>
        </div>
	    <?php endif; ?>
	    <div class="clr"></div>
	</div>
	
<?php
//one to one chat pop up notification 
apply_filters('dsp_get_single_chat_popup_notification',$notification);

if ($edit_profile_pageurl == "my_profile") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit/edit_profile_setup.php");
} else if ($edit_profile_pageurl == "partner_profile") {
    include_once(WP_DSP_ABSPATH . "edit_partner_profile_setup.php");
}else if ($edit_profile_pageurl == "edit_my_location") {
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit_my_location/edit_my_location.php");
}else { 
    include_once(WP_DSP_ABSPATH . "members/loggedin/edit/edit_profile_setup.php");
}
