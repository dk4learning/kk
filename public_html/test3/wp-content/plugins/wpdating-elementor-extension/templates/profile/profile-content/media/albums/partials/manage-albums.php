<?php
if ( is_user_logged_in() && $current_user->ID == $user_id ){
    $video_feature_access = wpee_check_membership('Create Album', $current_user->ID);
}

if ( function_exists( 'pll_get_post_language' ) ) {
    global $post;
    $lang = pll_get_post_language($post->ID, 'slug');
} else {
    $lang = '';
}
?>
<div class="album-list-inner box-border">
    <div class="box-pedding profile-user-albums-inner wpee-block-content">
        <?php 
        if ( is_user_logged_in() && $current_user->ID == $user_id && isset($video_feature_access) && is_wp_error($video_feature_access) ) :?>
        <div style="text-align: center; display: grid;" class="wpee-membership-error wpee-error">
            <span style="color: #FF0000;"><?php echo $video_feature_access->get_error_message(); ?></span>
            <span>
                 <a href="<?php echo esc_url( trailingslashit($profile_link) . 'settings/upgrade-account' ); ?>"><?php echo __('Click here', 'wpdating'); ?></a>
            </span>
        </div>
        <?php 
        else: 
            $dsp_user_albums_table      = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
            $dsp_galleries_photos_table = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;

            $sql_query = "SELECT album.album_id, album.user_id, album.album_name, album.private_album,
                            ( SELECT COUNT(*) FROM $dsp_galleries_photos_table gallery_photo WHERE gallery_photo.album_id = album.album_id AND gallery_photo.status_id = 1 ) num_of_photos,
                            ( SELECT image_name FROM $dsp_galleries_photos_table gallery_photo WHERE gallery_photo.album_id = album.album_id AND gallery_photo.status_id = 1 ORDER BY gallery_photo.gal_photo_id DESC LIMIT 1 ) album_thumbnail    
                            FROM {$dsp_user_albums_table} album
                            WHERE album.user_id = '{$user_id}'";
            
            if( $current_user->ID != $user_id ) {
                $sql_query .= " AND private_album = 'N'";
            }

            $user_albums = $wpdb->get_results($sql_query . " ORDER BY date_created DESC");
            $album_count = count( $user_albums);
            if ( $album_count > 0 ) :
            ?>
            <ul class="album-main-wrapper">
                <?php 
                foreach ( $user_albums as $user_album ) :
                    $album_thumbnail = $user_album->album_thumbnail 
                                        ? get_site_url() . "/wp-content/uploads/dsp_media/user_photos/user_{$user_album->user_id}/album_{$user_album->album_id}/{$user_album->album_thumbnail}" 
                                        : plugin_dir_url( dirname ( dirname ( dirname ( dirname( dirname(__DIR__ ) ) ) ) ) ). 'assest/images/album-default-image.jpg'; 
                                        ?>
                <li class="album-section-<?php echo $user_album->album_id; ?>">
                    <div class="album-wrapper">
                        <figure class="image-holder">
                            <a href="<?php echo $current_url . "?action=manage_photos&album_id={$user_album->album_id}"; ?>">
                                <span class="image-bg" style="background-image: url('<?php echo $album_thumbnail; ?>');"></span>
                            </a>
                        </figure>
                        <div class="album-content-wrap">
                            <div class="title-wrapper">
                                <?php if( $user_album->num_of_photos > 0 ) : ?>
                                <span class="img-count"><?php echo $user_album->num_of_photos . ' ' . __('photos', 'wpdating'); ?> </span>
                                <?php endif; ?>
                                <h6>
                                    <a class="album-name" href="<?php echo $current_url . "?action=manage_photos&album_id={$user_album->album_id}"; ?>"><?php echo $user_album->album_name; ?></a>
                                </h6>
                            </div>
                            <?php if(  is_user_logged_in() && $current_user->ID == $user_id ):?>
                            <a class="wpee-edit-button" href="#" data-id="<?php echo $user_album->album_id; ?>" data-name="<?php echo $user_album->album_name; ?>" 
                                data-private="<?php echo $user_album->private_album; ?>">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="wpee-delete-button" href="#" data-album-id="<?php echo $user_album->album_id; ?>" data-user-id="<?php echo $user_album->user_id; ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                            <?php endif;?>
                        </div>
                    </div>
                </li>
                <?php 
                endforeach;  ?>
            </ul>
            <?php
            elseif ( $album_count <= 0 && $current_user->ID != $user_id ): ?>
                    <div style="text-align: center; display: grid;" class="wpee-empty-info wpee-info">
                        <span style="color: #FF0000;"><?php esc_html_e( 'The user has not created any albums.', 'wpdating'); ?></span>
                    </div>
            <?php
            endif; ?>

            <?php if( is_user_logged_in() && $current_user->ID == $user_id ):?>
                <a class="wpee-create-button" href="#"><?php echo __('Add Album', 'wpdating'); ?></a>
            <?php endif;?>
        <?php        
        endif; ?>    
    </div>        
</div>
<div class="album-popup-main-wrapper popup-main-wrapper">
    <span class="album-pop-up-bg pop-up-bg"></span>
    <div class="album-popup-wrapper popup-wrapper">
        <h6 class="album-popup-title popup-title"><?php echo __('Add Album', 'wpdating'); ?></h6>
        <form class="album-popup-form popup-form" methdd="POST">
            <input type="hidden" name="action" id="action" value="add">
            <input type="hidden" name="album_id" id="album_id" value="">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>">
            <div class="form-group">
                <label for="album_name"><?php echo __('Album Name', 'wpdating'); ?></label>
                <input type="text" name="album_name" id="album_name">
                <span class='form-album-name-error-message form-error-message'></span>
            </div>
            <div class="form-group">
                <input type="checkbox" name="make_private" id="make_private" value="Y">
                <label for="make_private"><?php echo __('Make Private', 'wpdating'); ?></label>
            </div>
            <div class="form-group">
                <button class="save-button"><?php echo __('Save', 'wpdating'); ?></button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    let albumCreateEditForm = jQuery('.album-popup-form');

    jQuery('.wpee-create-button').click( event => {
        if( jQuery('input[name="action"]', albumCreateEditForm).val() == 'update' ) {
            jQuery('.album-popup-title').text('<?php echo __( 'Add Album', 'wpdating' ); ?>');
            jQuery('input[name="album_name"]', albumCreateEditForm).val('');
            jQuery('input[name="make_private"]', albumCreateEditForm).prop("checked", false);
            jQuery('input[name="action"]', albumCreateEditForm).val('add');
        }
    });

    jQuery('.wpee-edit-button').click( event => {
        let albumEditButton = event.currentTarget;
        let album_id        = jQuery(albumEditButton).attr('data-id');
        let album_name      = jQuery(albumEditButton).attr('data-name');
        let album_private   = jQuery(albumEditButton).attr('data-private');

        jQuery('.album-popup-title').text('<?php echo __( 'Edit Album', 'wpdating' ); ?>');
        jQuery('input[name="action"]', albumCreateEditForm).val('update');
        jQuery('input[name="album_id"]', albumCreateEditForm).val(album_id);
        jQuery('input[name="album_name"]', albumCreateEditForm).val(album_name);

        if( album_private === 'Y' ){
            jQuery('input[name="make_private"]', albumCreateEditForm).prop("checked", true);
        } else {
            jQuery('input[name="make_private"]', albumCreateEditForm).prop("checked", false);
        }
    });
    

    jQuery('.save-button').click( event => {
        event.preventDefault();

        let albumName = jQuery('input[name="album_name"]', albumCreateEditForm).val().trim();

        if ( albumName.length === 0 ){
            jQuery('.form-album-name-error-message').text('<?php echo __( 'Enter album name', 'wpdating' ); ?>');
            return false;
        }

        jQuery.ajax({
            url     : '<?php echo admin_url('admin-ajax.php'); ?>',
            data    : { action : 'wpee_create_edit_album', lang: '<?php echo $lang; ?>', form_data : albumCreateEditForm.serialize() },
            type    : 'POST',
            beforeSend: () => {
                jQuery(albumCreateEditForm).block({
                    overlayCSS : {
                    backgroundColor : '#fff',
                    opacity         : 0.6
                    },
                    message    : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
                });
            },
            success: result => {
                let response = JSON.parse(result);
                if ( response.success === true ) {    
                    jQuery('.popup-main-wrapper').removeClass('is-open');    
                    if( response.redirect_url === undefined ) {
                        let albumDetail = response.album_detail;
                        jQuery('.album-section-' + albumDetail.album_id + ' .album-name').text(albumDetail.album_name);
                        jQuery('.album-section-' + albumDetail.album_id + ' .wpee-edit-button').attr('data-name',albumDetail.album_name);
                        jQuery('.album-section-' + albumDetail.album_id + ' .wpee-edit-button').attr('data-private',albumDetail.album_private);
                        toastr.success(response.message);
                    } else {
                        window.location.replace(response.redirect_url);
                    }
                    return true;
                } else {
                    if( response.errors === undefined ) {
                        jQuery('.popup-main-wrapper').removeClass('is-open');
                        toastr.error(response.message);
                    } else {
                        for (const [key, value] of Object.entries(response.errors)) {
                            jQuery('.form-'+ key +'-error-message').text(value);
                        }
                    }
                    return false;
                }
            },
            error: () => {
                jQuery('.popup-main-wrapper').removeClass('is-open');
                toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                console.warn('Something went wrong!');
                return false;
            },
            complete: () => {
                jQuery(albumCreateEditForm).unblock();
            }  
        });
    });

    jQuery('.wpee-delete-button').click( event => {
        if( confirm('<?php echo __('Are you sure you want to delete this album file?', 'wpdating'); ?>') ){
            let albumDeleteButton = event.currentTarget;
            let albumID           = jQuery(albumDeleteButton).attr('data-album-id');
            let userID            = jQuery(albumDeleteButton).attr('data-user-id');

            jQuery.ajax({
                url     : '<?php echo admin_url('admin-ajax.php'); ?>',
                data    : { action : 'wpee_delete_album_by_id', lang: '<?php echo $lang; ?>', album_id : albumID, user_id : userID},
                type    : 'POST',
                beforeSend: () => {
                    jQuery(albumDeleteButton).parent().parent().parent().block({
                        overlayCSS  : {
                            backgroundColor : '#fff',
                            opacity         : 0.6
                        },
                        message    : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
                    });
                },
                success: result => {
                    let response = JSON.parse(result);
                    if ( response.success === true ) {
                        jQuery(albumDeleteButton).parent().parent().parent().remove();
                        toastr.success(response.message);
                        jQuery.ajax({
                            url     : '<?php echo admin_url('admin-ajax.php'); ?>',
                            data    : { action : 'wpee_delete_album_photos_by_album_id', album_id : albumID, user_id : userID},
                            type    : 'POST'
                        });
                        return true;
                    } else {
                        jQuery(albumDeleteButton).parent().parent().parent().unblock();
                        toastr.error(response.message);
                        return false;
                    }
                },
                error: () => {
                    jQuery(albumDeleteButton).parent().parent().unblock();
                    toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                    console.warn('Something went wrong!');
                    return false;
                }
            });
        }
    });

    jQuery('input[name="album_name"]', albumCreateEditForm).focusin( event => {
        jQuery('.form-error-message', jQuery(event.currentTarget).parent()).text('');
    });
</script>