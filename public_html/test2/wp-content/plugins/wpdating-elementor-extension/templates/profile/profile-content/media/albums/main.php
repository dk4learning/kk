<?php
global $wpdb;
$user_id        = wpee_profile_id();
$current_user   = wp_get_current_user();
$profile_link   = trailingslashit( wpee_get_profile_url_by_id( $user_id ) );
$current_url    = $profile_link . 'media/albums';

$action = ( isset( $_GET['action'] ) && !empty( $_GET['action'] ) ) ? $_GET['action'] : 'manage_album';

switch ( $action ){

    case 'manage_photos':
        if ( isset( $_GET['album_id'] ) && !empty( $_GET['album_id'] ) ) {
            require_once plugin_dir_path(__DIR__) . 'photos/index.php';
            break;
        }

    default:
        require_once 'partials/manage-albums.php';

}