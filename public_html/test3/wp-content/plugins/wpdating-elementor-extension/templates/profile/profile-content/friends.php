<?php 
global $wpdb, $wp_query;
$profile_subtab       = get_query_var( 'profile-subtab' );
$user_id              = wpee_profile_id();
$username             = wpee_get_username_by_id( $user_id );
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
$count_my_friends     = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$user_id' and approved_status='Y'");
$profile_link         = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $username );
?>
<div class="friends-list-wrapper profile-section-wrap main-profile-mid-wrapper">
	<ul class="profile-section-tab">
		<li class="profile-section-tab-title <?php echo (  $profile_subtab == '' || $profile_subtab == 'list' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($profile_link).'/friends/list');?>">
                <?php esc_html_e( 'Friends', 'wpdating' );?>(<?php echo $count_my_friends;?>)</a></li>
		<li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'favourites' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($profile_link).'/friends/favourites');?>"><?php esc_html_e( 'My Favorites', 'wpdating' );?>
            </a>
        </li>
	</ul>
	<div class="profile-section-content">
	    	<?php
			if ( ! empty( $profile_subtab ) && $profile_subtab == 'favourites' ){
				wpee_locate_template('profile/profile-content/friends/favourites.php');
			} else {
				wpee_locate_template('profile/profile-content/friends/list.php');
			}
			?>
	</div>
</div>