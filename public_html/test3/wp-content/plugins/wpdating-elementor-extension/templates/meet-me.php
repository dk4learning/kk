<?php 
global $wpdb, $wpee_general_settings;
$sql_query = "SELECT DISTINCT user.ID user_id, user.user_login user_name, user.display_name user_display_name,
                user_profile.make_private user_photo_private, user_profile.gender user_gender,
                (year(CURDATE())-year(user_profile.age)) user_age,
                country.name user_country, state.name user_state, city.name user_city,
                members_photo.picture as user_image
                FROM {$wpdb->users} user
                JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                ON user.ID = user_profile.user_id AND user_profile.status_id = 1 AND user_profile.country_id > 0
                LEFT JOIN {$wpdb->prefix}dsp_country country
                ON user_profile.country_id = country.country_id
                LEFT JOIN {$wpdb->prefix}dsp_state state
                ON user_profile.state_id = state.state_id
                LEFT JOIN {$wpdb->prefix}dsp_city city
                ON user_profile.city_id = city.city_id
                LEFT JOIN {$wpdb->prefix}dsp_members_photos members_photo
                ON user.ID = members_photo.user_id";

if ( is_user_logged_in() ) {
    $user_id   = get_current_user_id();

    $sql_query .= " WHERE user.ID NOT IN (SELECT member_id FROM {$wpdb->prefix}dsp_meet_me WHERE user_id='{$user_id}')";

    $user_seeking = $wpdb->get_var("SELECT seeking FROM {$wpdb->prefix}dsp_user_profiles where user_id='{$user_id}'");

    if( ! empty( $user_seeking ) ){
        $sql_query .= " AND user_profile.gender = '{$user_seeking}'";
    }

    if ( $wpee_general_settings->male->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'M'";
    }

    if ( $wpee_general_settings->female->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'F'";
    }

    if ( $wpee_general_settings->couples->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'C'";
    }
}

$sql_query .= " LIMIT 1";

$member_profile = $wpdb->get_row( $sql_query );
?>

<div class="meet-to-info login-form-trigger dsp-meet-to-info dspdp-text-center">
    <h2><?php echo __('Want To Meet Me?', 'wpdating') ?></h2>
    <?php if ( $member_profile ) :
        $profile_link      = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $member_profile->user_name );
        $user_display_name = ( $wpee_general_settings->display_user_name->value == 'display_user_name')
            ? $member_profile->user_name :
            Wpdating_Elementor_Extension_Helper_Functions::get_full_name_by_user_id( $member_profile->user_id, $member_profile->user_name ); ?>
        <div class="image-box dspdp-spacer-md dsp-meetme-image">
            <a href="<?php echo Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $member_profile->user_name ); ?>">
                <img src="<?php echo Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $member_profile->user_id, $member_profile->user_image,
                    $member_profile->user_photo_private, 'N' )->image_350; ?>" alt="<?php echo $user_display_name; ?> "/>
            </a>
        </div>
        <div class="user-meetto-info dspdp-font-2x  dspdp-spacer-md dsp-meet-details">
            <div class="user-info">
                <?php echo $member_profile->user_age; ?>
                <?php echo ( ! empty( $member_profile->user_city ) ? ', ' . $member_profile->user_city : '' ) . ( ! empty( $member_profile->user_state ) ? ', ' .  $member_profile->user_state : '' ) . ( ! empty( $member_profile->user_country ) ? ', ' . $member_profile->user_country : '' ); ?>
            </div>
            <div class="user-name">
                <span class="dspdp-medium"><?php echo $user_display_name; ?></span>
            </div>
        </div>
        <?php if ( is_user_logged_in() ) : ?>
        <div class="wpee-meetme-action-wrap" data-current-user-id="<?php echo $user_id; ?>"
             data-profile-id="<?php echo $member_profile->user_id; ?>">
            <a href="javascript:void(0);" class="button wpee-meet-me-trigger" data-action="yes">
                <i class="fa fa-heart"></i><?php echo __( 'Yes', 'wpdating' ); ?>
            </a>
            <a href="javascript:void(0);" class="button wpee-meet-me-trigger" data-action="no">
                <i class="fa fa-times"></i><?php echo __( 'No', 'wpdating' ); ?>
            </a>
        </div>
        <?php else : ?>
        <div class="wpee-meetme-action-wrap">
            <a href="javascript:void(0);" class="button login-form-trigger" data-action="yes">
                <i class="fa fa-heart"></i><?php echo __( 'Yes', 'wpdating' ); ?>
            </a>
            <a href="javascript:void(0);" class="button login-form-trigger" data-action="no">
                <i class="fa fa-times"></i><?php echo __( 'No', 'wpdating'); ?>
            </a>
        </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="meet-to-info no-user-profiles">
            <b><?php _e( "Sorry there are no users to show.", "wpdating" ); ?></b>
        </div>
    <?php endif; ?>

</div>