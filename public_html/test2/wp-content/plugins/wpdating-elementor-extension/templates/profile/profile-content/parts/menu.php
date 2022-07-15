<?php 
global $wpdb;
$check_chat_mode = wpee_get_setting( 'chat_mode' );
$profile_page = get_option( 'wpee_profile_page', '' );
$profile_page_url = get_permalink( $profile_page );
$profile_url = wpee_user_profile_url();	
$profile_tab = !empty( get_query_var('profile-tab') ) ? get_query_var( 'profile-tab' ) : 'activity';
$check_photos_mode = wpee_get_setting('picture_gallery_module');
$check_message_mode = wpee_get_setting('userplane_instant_messenger');
$user_id = wpee_profile_id();
$current_user_id = get_current_user_id();
?>
<div class="profile-menu-wrapper">
	<ul class="profile-menu-tab">
		<?php 
		if( $user_id == $current_user_id ){ ?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'activity' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/activity"><?php esc_html_e( 'Activity', 'wpdating' ); ?></a></li>
		<?php } ?>
		<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'profile' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/profile"><?php esc_html_e( 'Profile', 'wpdating' ); ?></a></li>
		<?php if( $check_photos_mode->setting_status == 'Y' ): ?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'media' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/media"><?php esc_html_e( 'Media', 'wpdating' ); ?></a></li>
			<?php 
		endif;
		if ($check_message_mode->setting_status == 'Y'): ?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'chat' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/chat"><?php esc_html_e( 'Chat', 'wpdating' ); ?></a></li>
		<?php endif;
		if( $user_id == $current_user_id ): ?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'search' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/search"><?php esc_html_e( 'Search', 'wpdating' ); ?></a></li>
		<?php 
		endif; 
		if ($check_my_friend_module->setting_status == 'Y') : ?>
			<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'friends' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/friends"><?php esc_html_e( 'Friends', 'wpdating' ); ?></a></li>
		<?php endif;?>
	</ul>
</div>