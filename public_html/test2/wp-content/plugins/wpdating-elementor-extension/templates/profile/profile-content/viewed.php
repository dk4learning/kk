<?php 
global $wpdb;
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'viewed';
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'photos';
$user_id = wpee_profile_id();
$current_user_id = get_current_user_id();
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
// $count_my_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$user_id'");
$profile_link = trailingslashit(wpee_get_profile_url_by_id( $user_id));?>

<div class="media-list-wrapper profile-section-wrap main-profile-mid-wrapper">
	<ul class="profile-section-tab">
		<li class="profile-section-tab-title <?php echo ( $profile_subtab != 'viewed_me' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'/viewed/viewed_by_me');?>"><?php esc_html_e( 'I Viewed', 'wpdating' );?></a></li>
		<li class="profile-section-tab-title <?php echo ( $profile_subtab == 'viewed_me' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'/viewed/viewed_me');?>"><?php esc_html_e( 'Viewed Me', 'wpdating' );?></a></li>
	</ul>
	<div class="profile-section-content">
	    	<?php
			if( !empty( $profile_subtab ) && $profile_subtab == 'viewed_me' ){
		        if( !is_wp_error(wpee_check_membership('Viewed Me', $current_user_id )) && wpee_check_membership('Viewed Me', $current_user_id ) == true ){
					wpee_locate_template('profile/profile-content/viewed/viewed_me.php');
		        }
		        else{
		            wpee_display_error_message(wpee_check_membership('Viewed Me', $current_user_id ));
		        }
			}
			else {
		        if( !is_wp_error(wpee_check_membership('I Viewed', $current_user_id )) && wpee_check_membership('I Viewed', $current_user_id ) == true ){
					wpee_locate_template('profile/profile-content/viewed/viewed_by_me.php');
		        }
		        else{
		            wpee_display_error_message(wpee_check_membership('I Viewed', $current_user_id ));
		        }
			}
			?>
	</div>
</div>