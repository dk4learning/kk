<?php
global $wpdb;
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'photos';
$user_id        = wpee_profile_id();
$profile_link   = wpee_get_profile_url_by_id( $user_id);

$check_photos_mode = wpee_get_setting('picture_gallery_module');
$check_video_mode  = wpee_get_setting('video_module');
$check_audio_mode  = wpee_get_setting('audio_module');
switch ($profile_subtab) {
    case 'photos':
        if ($check_photos_mode->setting_status == 'Y') {
            $active_menu = 'photos';
            $template    = 'photos/index.php';
            break;
        }

    case 'albums':
        if ($check_photos_mode->setting_status == 'Y') {
            $active_menu = 'albums';
            $template    = 'albums/main.php';
            break;
        }

    case 'audios':
        if ($check_audio_mode->setting_status == 'Y') {
            $active_menu = 'audios';
            $template    = 'audios/index.php';
            break;
        }

    case 'videos':
        if ($check_video_mode->setting_status == 'Y') {
            $active_menu = 'videos';
            $template    = 'videos/index.php';
        } else {
            wp_redirect($profile_link);
        }
}

?>

<div class="media-list-wrapper profile-section-wrap main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <?php if( $check_photos_mode->setting_status == 'Y' ): ?>
            <li class="profile-section-tab-title <?php echo ( $active_menu == 'photos' ) ? 'active' : ''; ?>">
                <a href="<?php echo esc_url(trailingslashit($profile_link).'media/photos');?>">
                    <?php esc_html_e( 'Photos', 'wpdating' );?>
                </a>
            </li>

            <li class="profile-section-tab-title <?php echo ( $active_menu == 'albums' ) ? 'active' : '';?>">
                <a href="<?php echo esc_url(trailingslashit($profile_link).'media/albums');?>">
                    <?php esc_html_e( 'Albums', 'wpdating' );?>
                </a>
            </li>
        <?php endif; ?>

        <?php if($check_audio_mode->setting_status == 'Y'): ?>
            <li class="profile-section-tab-title <?php echo ( $active_menu == 'audios' ) ? 'active' : '';?>">
                <a href="<?php echo esc_url(trailingslashit($profile_link).'media/audios');?>">
                    <?php esc_html_e( 'Audios', 'wpdating' );?>
                </a>
            </li>
        <?php endif;?>

        <?php if($check_video_mode->setting_status == 'Y'): ?>
        <li class="profile-section-tab-title <?php echo ( $active_menu == 'videos' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($profile_link).'media/videos');?>">
                <?php esc_html_e( 'Videos', 'wpdating' );?>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <div class="profile-section-content">
        <?php
        wpee_locate_template('profile/profile-content/media/' . $template );
        ?>
    </div>
</div>