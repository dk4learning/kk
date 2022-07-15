<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$pageURL = isset($_REQUEST['pid']) ? $_REQUEST['pid'] : '';
?>
<div class="wrap"><h2><?php echo __('Dating Site Admin', 'dsp_trans_domain') ?></h2></div>
<div id="navmenu" align="left">
	<ul>
		<li <?php if (($pageURL == "tools_profile") || $pageURL == "") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_profile" title="<?php echo __('Profile', 'wpdating') ?>"><?php echo __('Profile', 'wpdating') ?></a><span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_flirts") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_flirts" title="<?php echo __('Flirts', 'wpdating') ?>"><?php echo __('Flirts', 'wpdating') ?></a> <span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_email_temp") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_email_temp" title="Email Templates"><?php echo __('Email Templates', 'wpdating') ?></a>
			<span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_geography") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_geography" title="Geography"><?php echo __('Geography', 'wpdating') ?></a>
			<span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_language") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_language&dsp_page=search" title="Language"><?php echo __('Language', 'wpdating') ?></a>
			<span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_profile_generator") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_profile_generator" title="Profile Generator"><?php echo __('Profile Generator', 'wpdating'); ?></a><span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "tools_chat") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=tools_chat" title="Chat"><?php echo __('Logs', 'wpdating'); ?></a><span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "gender") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=gender" title="Chat"><?php echo __('Gender', 'wpdating'); ?></a><span class="dsp_tab1_span">|</span></li>
		<li <?php if ($pageURL == "virtual_flirts") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=virtual_flirts" title="Chat"><?php echo __('Virtual Gifts', 'wpdating'); ?></a>
			<span class="dsp_tab1_span">|</span>
		</li>
		<li <?php if ($pageURL == "mobile_app") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=mobile_app" title="Mobile App">
				<?php echo __('Mobile', 'wpdating') ?>
			</a><span class="dsp_tab1_span">|</span>
		</li>
		<li <?php if ($pageURL == "stories") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=stories" title="Stories">
				<?php echo __('Stories', 'wpdating') ?>
			</a><span class="dsp_tab1_span">|</span>
		</li>
		<li <?php if ($pageURL == "import_sql") { ?>class="dsp_tab1-active" <?php } else { ?> class="dsp_tab1"<?php } ?>>
			<a href="admin.php?page=dsp-admin-sub-page3&pid=import_sql" title="Import SQL">
				Import SQL
			</a>
		</li>
	</ul>
</div>
<?php
if ($pageURL == "tools_profile") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_profiles.php');
} else if ($pageURL == "tools_profile_generator") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_profiles_generator.php');
} else if ($pageURL == "tools_flirts") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_flirt.php');
} else if ($pageURL == "tools_email_temp") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_email_templates.php');
} else if ($pageURL == "tools_geography") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_geography.php');
} else if ($pageURL == "tools_language") {
	include_once( WP_DSP_ABSPATH . 'files/includes/dsp_tools_language_subheader.php');
} else if ($pageURL == "tools_language_edit") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_edit_tools_language.php');
} else if ($pageURL == "fetch_state") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_fetch_state.php');
} else if ($pageURL == "tools_profile_option") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_profile_option.php');
} else if ($pageURL == "tools_chat") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_chat.php');
} else if ($pageURL == "gender") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_gender.php');
} else if ($pageURL == "stories") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_stories.php');
} else if ($pageURL == "virtual_flirts") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_virtual_flirts.php');
} else if ($pageURL == "mobile_app") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_mobile_app_setting.php');
} else if ($pageURL == "import_sql") {
	include_once( WP_DSP_ABSPATH . 'files/dsp_import_sql_setting.php');
}
else {
	include_once( WP_DSP_ABSPATH . 'files/dsp_tools_profiles.php');
}

