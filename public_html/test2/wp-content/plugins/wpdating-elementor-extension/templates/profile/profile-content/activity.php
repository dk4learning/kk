<?php 
global $wpdb, $wpee_settings;
if( ( isset( $wpee_settings['user_details'] ) && $wpee_settings['user_details'] == 'yes' ) ||
    (isset( $wpee_settings['photos'] ) && $wpee_settings['photos'] == 'yes' ) ||
    ( isset( $wpee_settings['friends'] ) && $wpee_settings['friends'] == 'yes' ) ||
    ( isset( $wpee_settings['quick_search'] ) && $wpee_settings['quick_search'] == 'yes' )
){ ?>
	<div class="profile-activity-sidebar">
		<?php
		if( isset( $wpee_settings['user_details'] ) && $wpee_settings['user_details'] == 'yes' ){
			wpee_locate_template('profile/profile-content/parts/user-details.php');
		}
		if( isset( $wpee_settings['photos'] ) && $wpee_settings['photos'] == 'yes' ){
			wpee_locate_template('profile/profile-content/parts/photos-list.php');
		}
		if( isset( $wpee_settings['friends'] ) && $wpee_settings['friends'] == 'yes' ){
			wpee_locate_template('profile/profile-content/parts/friends-list.php');
		}
		if( isset( $wpee_settings['quick_search'] ) && $wpee_settings['quick_search'] == 'yes' ){
			wpee_locate_template('profile/profile-content/parts/quick-search.php');
		}?>
	</div>
<?php } ?>
<div class="profile-activity-inner main-profile-mid-wrapper">
	<?php wpee_locate_template('profile/profile-content/parts/activity-feeds.php'); ?>
</div>