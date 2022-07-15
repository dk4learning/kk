<?php 
global $wpdb, $wpee_settings;
$user_id = wpee_profile_id();
$profile_link = trailingslashit(wpee_get_profile_url_by_id( $user_id));
$chat_url= $profile_link . 'chat'; ?>
<div class="main-profile-mid-wrapper">
	<ul class="profile-section-tab">
    	<li class="profile-section-tab-title active"><a href="<?php echo esc_url($chat_url);?>"><?php esc_html_e('Chat','wpdating');?></a></li>
    </ul>
	<div class="profile-section-content">
		<?php
		if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php') && strpos($_SERVER["REQUEST_URI"],'/chat')){
		    do_action('wp_instant_chat_page');?>
			</div>
		    <?php
		} ?> <?php // do_action('wp_instant_chat_page'); added to fixed </div> closing issue in this action   ?>
	</div>
</div>