<?php
global $wpdb, $wpee_general_settings, $wpee_genders;
$user_id      = wpee_profile_id();
$user_profile = wpee_get_user_details_by_user_id( $user_id );
?>
<div class="profile-user-details wpee-block">
    <div class="wpee-block-header">
        <h4 class="wpee-block-title"><?php _e( 'User Details', 'wpdating' );?></h4>
    </div>
    <?php if ( $user_profile ) :
        $profile_link = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $user_profile->user_name ); ?>
        <div class="wpee-user-details-inner wpee-block-content">
            <ul class="profile-detail-list">
                <?php if ( ! empty( $user_profile->user_gender ) ) : ?>
                    <li class="profile-gender">
                        <span class="gender-title"><?php _e( 'I am','wpdating' ); ?>:</span>
                        <?php _e( $wpee_genders->{$user_profile->user_gender}, 'wpdating' ); ?>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $user_profile->user_seeking ) ) : ?>
                    <li class="profile-seeking">
                        <span class="seeking-title"><?php _e( 'Seeking a', 'wpdating' ); ?>:</span>
                        <?php _e( $wpee_genders->{$user_profile->user_seeking}, 'wpdating' ); ?>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $user_profile->user_age ) ) : ?>
                    <li class="profile-age">
                        <span class="age-title"><?php esc_html_e( 'Age', 'wpdating' ); ?>:</span>
                        <?php echo $user_profile->user_age; ?>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $user_profile->user_country ) ) : ?>
                    <li class="profile-country">
                        <span class="country-title"><?php _e( 'Country', 'wpdating' ); ?>:</span>
                        <?php _e( $user_profile->user_country, 'wpdating' ); ?>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $user_profile->user_state ) ) : ?>
                    <li class="profile-state">
                        <span class="state-title"><?php _e( 'State', 'wpdating'); ?>:</span>
                        <?php _e( $user_profile->user_state, 'wpdating' ); ?>
                    </li>
                <?php endif; ?>
                <?php if ( ! empty( $user_profile->user_city ) ) : ?>
                    <li class="profile-city">
                        <span class="city-title"><?php _e( 'City', 'wpdating' ); ?>:</span>
                        <?php _e( $user_profile->user_city, 'wpdating' ); ?>
                    </li>
                <?php endif;?>
                <?php if ( $wpee_general_settings->zipcode_mode->value == 'Y' && ! empty( $user_profile->zipcode ) ): ?>
                    <li class="profile-zipcode">
                        <span class="zipcode-title"><?php _e( 'Zipcode', 'wpdating' ); ?>:</span>
                        <?php echo $user_profile->zipcode; ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php if ( $user_id == get_current_user_id() ) : ?>
        <div class="wpee-block-footer">
            <a href="<?php echo esc_url( $profile_link ) . '/edit-profile';?>" class="edit-profile-link">
                <?php _e( 'Edit Profile', 'wpdating' );?>
            </a>
        </div>
    <?php else : ?>
        <div class="wpee-block-footer">
            <a href="<?php echo esc_url($profile_link) . '/view-details/my-profile';?>" class="view-detail-link">
                <?php _e( 'View Details', 'wpdating' );?>
            </a>
        </div>
    <?php endif; ?>
    <?php else : ?>
        <div class="wpee-user-details-inner wpee-block-content wpee-error-text">
            <span> <?php _e( 'No Profile Exists.', 'wpdating' ); ?> </span>
        </div>
    <?php endif; ?>
</div>