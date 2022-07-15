<?php 
global $wpdb, $wpee_settings;
$user_id         = wpee_profile_id();
$current_user_id = get_current_user_id();

if ( ( isset( $wpee_settings['user_details'] ) && $wpee_settings['user_details'] )
    || (isset( $wpee_settings['photos'] ) && $wpee_settings['photos'])
    || ( isset( $wpee_settings['friends'] ) && $wpee_settings['friends'] ) ) : ?>
	<div class="profile-activity-sidebar">
		<?php
		if( isset( $wpee_settings['user_details'] ) && $wpee_settings['user_details'] ){
			wpee_locate_template('profile/profile-content/parts/user-details.php');
		}
		if( isset( $wpee_settings['photos'] ) && $wpee_settings['photos'] ){
			wpee_locate_template('profile/profile-content/parts/photos-list.php');
		}
		if( isset( $wpee_settings['friends'] ) && $wpee_settings['friends'] ){
			wpee_locate_template('profile/profile-content/parts/friends-list.php');
		}
        if( isset( $wpee_settings['quick_search'] ) && $wpee_settings['quick_search'] ){
            wpee_locate_template('profile/profile-content/parts/quick-search.php');
        }?>
	</div>
<?php endif; ?>
<div class="profile-activity-inner main-profile-mid-wrapper">
    <?php
    if ( $user_id == $current_user_id ) :
        $user_details      = wpee_get_user_details_by_user_id( $user_id );
        $first_name        = get_user_meta( $user_details->user_id, 'first_name', true ); ?>
		<div class="profle-status">
            <?php if ( isset( $errors ) ) : ?>
            <?php echo apply_filters( 'dsp_display_errors', $errors ); ?>
            <?php endif; ?>
            <form id="wpee-status-form" method="post" class="wpee-status-form">
                <div class="profile-status-img">
                	<img src="<?php echo Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $user_details->user_id, $user_details->user_image,
                        $user_details->user_photo_private, 'N' )->image_350; ?>"
                         class="img" id="profile_picture"
                         alt="<?php echo Wpdating_Elementor_Extension_Helper_Functions::get_full_name_by_user_id( $user_details->user_id, $user_details->user_id ); ?>">
                </div>
                <div class="form-group">
                	<textarea class="form-control profile-status-text"
                              placeholder="<?php echo __('What\'s new,', 'wpdating') . ' ' . ( !empty( $first_name ) ? $first_name : $user_details->user_name ) .'?'; ?>"
                              name="profile_status" type="text" maxlength="100"></textarea>
                	<?php wp_nonce_field( 'wpee_profile_status_nonce', 'profile-status-nonce');?>
                    <input class="button wpee-btn" type="submit"
                           value="<?php echo __('Post', 'wpdating'); ?>"/>
               	</div>
            </form>
        </div>
    <?php endif; ?>
	<div id="news-feed" data-type="profile" data-user-id="<?php echo esc_attr( $user_id );?>" data-page="1">
		<div class="feeds-content"></div>
		<div class="loader"></div>
	</div>
</div>