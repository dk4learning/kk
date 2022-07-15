<?php 
global $wpdb, $wpee_settings; 
$profile_tab = !empty( get_query_var('profile-tab') ) ? get_query_var( 'profile-tab' ) : '';
$profile_url = wpee_user_profile_url();	
$user_id = wpee_profile_id();
$current_user_id = get_current_user_id();

$check_my_friend_module   = wpee_get_setting('my_friends');
$check_virtual_gifts_mode = wpee_get_setting('virtual_gifts');
$check_message_mode       = wpee_get_setting('free_email_access');
$check_photos_mode        = wpee_get_setting('picture_gallery_module');
$check_video_mode         = wpee_get_setting('video_module');
$check_audio_mode         = wpee_get_setting('audio_module');

if($wpee_settings['enable_menu'] ){ ?>
	<div class="profile-menu-wrapper d-flex space-bet">
		<ul class="profile-menu-tab">
			<?php 
			if( $user_id == $current_user_id ){ ?>
				<li class="profile-menu-tab-list <?php echo ( empty( $profile_tab ) && $profile_tab == 'activity' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/activity"><?php esc_html_e( 'Activity', 'wpdating' ); ?></a></li>
			<?php } ?>
			    <li class="profile-menu-tab-list <?php echo ( ( $user_id != $current_user_id && empty( trim($profile_tab) ) ) || $profile_tab == 'profile' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/profile"><?php esc_html_e( 'Profile', 'wpdating' ); ?></a></li>
			<?php if( $check_photos_mode->setting_status == 'Y' || $check_video_mode->setting_status == 'Y' || $check_audio_mode->setting_status == 'Y' ): ?>
				<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'media' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/media"><?php esc_html_e( 'Media', 'wpdating' ); ?></a></li>
				<?php 
			endif;
			if ($check_message_mode->setting_status == 'Y' && $user_id == $current_user_id ): ?>
				<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'message' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/message"><?php esc_html_e( 'Message', 'wpdating' ); ?></a></li>
			<?php endif;?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'search' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/search"><?php esc_html_e( 'Search', 'wpdating' ); ?></a></li>
			<?php if ($check_my_friend_module->setting_status == 'Y') : ?>
				<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'friends' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/friends"><?php esc_html_e( 'Friends', 'wpdating' ); ?></a></li>
			<?php endif;?>
            <?php if ($user_id == $current_user_id && is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')) : ?>
                <li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'instant-chat' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/instant-chat"><?php esc_html_e( 'Chats', 'wpdating' ); ?></a></li>
            <?php endif;?>
		</ul>
		<div class="profile-notification-blocks">
			<div class="wpee-notification-block-wrap">			    
	    		<?php wpee_locate_template('profile/notification-blocks/notification-blocks.php');?>	 
		    </div>
		</div>
	</div>
<?php } ?>
<div class="profile-content-inner <?php echo esc_attr( $profile_tab );?>">	
	<?php 
	if( !empty( $profile_tab ) && $profile_tab == 'activity' && $user_id == $current_user_id ){
		wpee_locate_template('profile/profile-content/activity.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'media' ){
		wpee_locate_template('profile/profile-content/media/header.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'message' && $user_id == $current_user_id ){
			wpee_locate_template('profile/profile-content/message.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'search' && $user_id == $current_user_id ){
		wpee_locate_template('profile/profile-content/search.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'friends' ){
		wpee_locate_template('profile/profile-content/friends.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'edit-profile' && $user_id == $current_user_id ){
		wpee_locate_template('profile/profile-content/edit/header.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'gifts' && $check_virtual_gifts_mode->setting_status == 'Y' ){
		wpee_locate_template('profile/notification-blocks/my-profile/gifts.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'notifications' ){
		wpee_locate_template('profile/notification-blocks/my-profile/notifications.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'friend-request' && $user_id == $current_user_id ){
		wpee_locate_template('profile/notification-blocks/my-profile/friend-request.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'settings' && $user_id == $current_user_id ){
		wpee_locate_template('profile/notification-blocks/my-profile/settings.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'viewed' && $user_id == $current_user_id ){
		wpee_locate_template('profile/profile-content/viewed.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'reset_password' ){
		wpee_locate_template('profile/profile-content/reset-password.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'verify_user' ){
		wpee_locate_template('profile/profile-content/verify-user.php');
	}
	elseif( !empty( $profile_tab ) && $profile_tab == 'view-details' ){
		wpee_locate_template('profile/profile-content/view-details.php');
	} elseif( !empty( $profile_tab ) && $profile_tab == 'instant-chat' ){ ?>
    <div class="instant-chat-wrapper profile-section-wrap main-profile-mid-wrapper">
        <?php do_action('wp_instant_chat_page');?>
        </div>
    </div>
    <?php
    }
	else {
		wpee_locate_template('profile/profile-content/profile-feeds.php');
	}
	if( ( isset( $wpee_settings['meet_me'] ) && $wpee_settings['meet_me'] == 'yes' ) || (isset( $wpee_settings['online_members'] ) && $wpee_settings['online_members'] == 'yes' ) ){ ?>
		<div class="profile-activity-right-sidebar">
			<?php
        	$check_meet_me = wpee_get_setting('meet_me');
			if( isset( $wpee_settings['meet_me'] ) && $wpee_settings['meet_me'] && $check_meet_me->setting_status == 'Y' ){
				wpee_locate_template('meet-me.php');
			}
			if( isset( $wpee_settings['online_members'] ) && $wpee_settings['online_members'] ){
				wpee_locate_template('online-members.php');
			}?>
		</div>
	<?php } ?>
</div>