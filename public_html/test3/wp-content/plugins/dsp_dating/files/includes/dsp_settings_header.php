<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
$site_url = get_bloginfo('url');
if (!empty($_SERVER['HTTPS'])) {
    $https = true;
}
if(strpos($site_url, 'https') === false && isset($https)){
    $site_url = str_replace('http', 'https', $site_url);
}
$settings_root_link = $site_url . "/wp-admin/admin.php?page=dsp-admin-sub-page1&pid=" . $pageURL;

wp_deregister_style('paging');
wp_register_style('paging', plugins_url('dsp_dating/css/pagination.css'));
wp_enqueue_style('paging');

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$check_pagination_color = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'pagination_color'");
$check_credit_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'credit'");
/////////pagination dynamic color from admin start/////////////////
?>
<style>
    #wpbody-content .paging-box-withbtn .wpse_pagination .current{
        background:#<?php echo $check_pagination_color->setting_value; ?>; 
    }
</style>
<?php
/////////pagination dynamic color from admin start/////////////////
?>
<div class="wrap"><h2><?php echo __(__('Dating Site Admin', 'wpdating'), 'dsp_trans_domain') ?></h2></div>
<div id="navmenu" align="left">
    <ul>
        <li <?php if (($pageURL == "general_settings") || ($pageURL == "")) { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=general_settings" title="<?php echo __('General', 'wpdating') ?>">
                <?php echo __('General', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "membership_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=membership_settings" title="<?php echo __('Memberships', 'wpdating') ?>">
                <?php echo __('Memberships', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <?php if($check_credit_mode->setting_status == 'Y'): ?>
        <li <?php if ($pageURL == "credits") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=credits" title="<?php echo __('Credits', 'wpdating') ?>">
                <?php echo __('Credits', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>

        <li <?php if ($pageURL == "credits_usage") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=credits_usage" title="<?php echo __('Credits Usage', 'wpdating') ?>">
                <?php echo __('Credits Usage', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        
        <li <?php if ($pageURL == "credits_plan") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=credits_plan" title="<?php echo __('Credits Plan', 'wpdating') ?>">
                <?php echo __('Credits Plan', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>

        <?php endif; ?>
        <li <?php if ($pageURL == "gateways_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=gateways_settings" title="<?php echo __('Gateways', 'wpdating') ?>">
                <?php echo __('Gateways', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "spam_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=spam_settings" title="<?php echo __('Spam', 'wpdating') ?>">
                <?php echo __('Spam', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "matches_settings") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=matches_settings" title="<?php echo __('Matches', 'wpdating') ?>">
                <?php echo __('Matches', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "blacklist") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=blacklist" title="<?php echo __('Blacklists', 'wpdating') ?>">
                <?php echo __('Blacklists', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "premium_member") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=premium_member" title="<?php echo __('Premium Member', 'wpdating') ?>">
                <?php echo __('Premium Member', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "featured_member") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=featured_member" title="<?php echo __('Featured Member', 'wpdating') ?>">
                <?php echo __('Featured Member', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "instant_messenger") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=instant_messenger" title="<?php echo __('Instant Messenger', 'wpdating') ?>">
                <?php echo __('Instant Messenger', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "dsnews") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=dsnews" title="<?php echo __('DS News', 'wpdating') ?>">
                <?php echo __('DS News', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
        <li <?php if ($pageURL == "update_database") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=update_database" title="<?php echo __('Update Database', 'wpdating') ?>">
                <?php echo __('Update Database', 'wpdating') ?></a><span class="dsp_tab1_span">|</span>
        </li>
         <li <?php if ($pageURL == "license_activate") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
            <a href="admin.php?page=dsp-admin-sub-page1&pid=license_activate" title="<?php echo __('Activate License', 'wpdating') ?>">
                <?php echo __('Activate License', 'wpdating') ?></a>
        </li>
    </ul>
</div>
<?php
if ($pageURL == "membership_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_settings_memberships.php');
} else if ($pageURL == "gateways_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_gateways_settings.php');
} else if ($pageURL == "spam_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_spam_settings.php');
} else if ($pageURL == "matches_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_matches_settings.php');
} else if ($pageURL == "premium_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_premium_access_settings.php');
} else if ($pageURL == "blacklist") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_blacklist_settings.php');
} else if ($pageURL == "update_general_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_general_setings.php');
} else if ($pageURL == "update_spam_settings") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_spam_settings.php');
} else if ($pageURL == "check_blacklist_ipaddress") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_check_blacklist_ipaddress.php');
} else if ($pageURL == "import_zipcodes") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_process/update_import_zipcodes.php');
} else if ($pageURL == "premium_member") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_premium_member_settings.php');
} else if ($pageURL == "featured_member") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_featured_member_settings.php');    
} else if ($pageURL == "instant_messenger") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_userplane_settings.php');
} else if ($pageURL == "dsnews") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_dsnews_settings.php');
} else if ($pageURL == "update_database") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_update_database_settings.php');
} else if ($pageURL == "credits") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_credits_settings.php');
} else if ($pageURL == "credits_usage") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_credits_usage_settings.php');
} else if ($pageURL == "credits_plan") {
    include_once( WP_DSP_ABSPATH . 'files/dsp_credits_plan_settings.php');
} else if ($pageURL == "upgrade") {
   include_once( WP_DSP_ABSPATH . 'upgrade.php');
} else if ($pageURL == "license_activate") {
   include_once( WP_DSP_ABSPATH . 'files/dsp_license_activate.php');
} else {
    include_once( WP_DSP_ABSPATH . 'files/dsp_general_settings.php');
}
?>