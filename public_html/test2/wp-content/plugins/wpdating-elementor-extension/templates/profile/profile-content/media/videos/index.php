<?php
global $wpdb;
$user_id        = wpee_profile_id( );
$current_user   = wp_get_current_user();
$profile_link   = trailingslashit(wpee_get_profile_url_by_id( $user_id));
$current_url    = $profile_link . 'media/videos';

$dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;

$page = get_query_var('paged') ? get_query_var('paged') : 1;

if ( is_user_logged_in() && $current_user->ID == $user_id ){
    $video_feature_access = wpee_check_membership('Upload Video', $current_user->ID);
}

if ($page == 0){
    $page = 1;
}

if ( function_exists( 'pll_get_post_language' ) ) {
    global $post;
    $lang = pll_get_post_language( $post->ID, 'slug' );
} else {
    $lang = '';
}
?>

<div class="video-list-inner box-border">
    <div class="box-pedding profile-user-videos-inner wpee-block-content">

        <?php
        if ( is_user_logged_in() && $current_user->ID == $user_id && isset($video_feature_access) && is_wp_error($video_feature_access) ) :
        ?>
            <div style="text-align: center; display: grid;" class="wpee-membership-error wpee-error">
                <span style="color: #FF0000;">
                    <?php echo $video_feature_access->get_error_message(); ?>
                </span>
                <span>
                    <a href="<?php echo esc_url( trailingslashit($profile_link) . 'settings/upgrade-account' ); ?>"><?php echo __('Click here', 'wpdating'); ?></a>
                </span>
            </div>
        <?php
        else: 
            $sql_query = "SELECT COUNT(*) FROM {$dsp_member_videos_table} WHERE user_id = '{$user_id}' AND status_id = 1";

            if( $current_user->ID != $user_id ) {
                $sql_query .= " AND private_video = 'N'";
            }
        
            $member_video_count = $wpdb->get_var($sql_query);
            if ($member_video_count > 0) : ?>

            <ul class="video-main-wrapper">
                <?php 
                $sql_query     = str_replace('COUNT(*)', '*', $sql_query);
                $member_videos = $wpdb->get_results($sql_query . " ORDER BY date_added DESC");
                foreach ($member_videos as $member_video) :?>
                <li>
                        <div class="video-wrapper">
                            <figure class="image-holder video-thumbnail">
                                <video id="sampleMovie" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_videos/user_" . $member_video->user_id . "/" . $member_video->file_name; ?>" controls width="200" height="200"></video>
                            </figure>
                            <div class="video-content-wrap">
                                <div class="title-wrapper">
                                    <span class="date-wrap"><?php echo wpee_get_time_difference( $member_video->date_added ); ?></span>
                                </div>
                                <?php if( is_user_logged_in() && $current_user->ID == $user_id ):?>
                                <a class="wpee-delete-button" href="#" data-video-id="<?php echo $member_video->video_file_id; ?>" data-user-id="<?php echo $member_video->user_id; ?>" >
                                    <i class="fa fa-trash"></i>
                                </a>
                                <?php endif;?>
                            </div>
                        </div>
                    </li>
                <?php 
                endforeach; ?>
            </ul>

            <?php
            elseif ( $member_video_count <= 0 && $current_user->ID != $user_id ) : ?>

            <div style="text-align: center; display: grid;" class="wpee-empty-info wpee-info">
                <span style="color: #FF0000;"><?php esc_html_e( 'The user has not uploaded any videos.', 'wpdating'); ?></span>
            </div>

            <?php
            elseif ( $member_video_count <= 0 && $current_user->ID == $user_id ) : ?>

            <ul class="video-main-wrapper"></ul>

            <?php
            endif; ?>

            <?php 
            if(  is_user_logged_in() && $current_user->ID == $user_id ):?>
                <a class="wpee-create-button" href="#"><?php echo __('Add Video', 'wpdating'); ?></a>
            <?php 
            endif;?>
        <?php
        endif; ?>
    </div>
</div>

<div class="video-popup-main-wrapper popup-main-wrapper">
    <span class="video-pop-up-bg pop-up-bg"></span>
    <div class="video-popup-wrapper popup-wrapper">
        <h6 class="video-popup-title popup-title"><?php echo __('Add Video', 'wpdating'); ?></h6>
        <form class="video-popup-form popup-form" methdd="POST">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>">
            <div class="form-group">
                <input type="file" name="video_file" id="video-file">
                <span class='form-video-file-error-message form-error-message'></span>
            </div>
            <div class="form-group">
                <input type="checkbox" name="make_private" id="make-private" value="Y">
                <label for="make_private"><?php echo __('Make Private', 'wpdating'); ?></label>
            </div>
            <div class="form-group">
                <button class="save-button"><?php echo __('Save', 'wpdating'); ?></button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    let videoCreateForm = jQuery('.video-popup-form');

    jQuery('.save-button').click( event => {
        event.preventDefault();

        let file = jQuery('input[name="video_file"]', videoCreateForm).val();

        if (jQuery.trim(file) === "") {
            jQuery('.form-video-name-error-message').text('<?php echo __('No file selected', 'wpdating'); ?>');
            return false;
        }

        let formData = new FormData();

        let videoFormData = videoCreateForm.serializeArray();

        jQuery.each(videoFormData, function (key, input) {
            formData.append(input.name, input.value);
        });

        let videoFileData = jQuery('input[name="video_file"]', videoCreateForm)[0].files;
        formData.append("video_file", videoFileData[0]);

        formData.append('action', 'wpee_create_video');
        formData.append('lang', '<?php echo $lang; ?>');

        jQuery.ajax({
            method     : 'post',
            processData: false,
            contentType: false,
            cache      : false,
            data       : formData,
            enctype    : 'multipart/form-data',
            url        : '<?php echo admin_url('admin-ajax.php'); ?>',
            beforeSend: () => {
                jQuery(videoCreateForm).block({
                    overlayCSS  : {
                    backgroundColor : '#fff',
                    opacity         : 0.6
                    },
                    message      : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
                });
            },
            success: result => {
                let response = JSON.parse(result);
                if ( response.success === true ) {    
                    jQuery('.popup-main-wrapper').removeClass('is-open');
                    jQuery('input[name="video_file"]', videoCreateForm).val('');
                    jQuery('input[name="make_private"]', videoCreateForm).prop("checked", false);
                    if(response.video_detail !== undefined) {
                        let videoDetail      = response.video_detail;
                        let videoLiStructure = `<li>
                                                    <div class="video-wrapper">
                                                        <figure class="image-holder video-thumbnail">
                                                            <video id="sampleMovie" src="` + videoDetail.file_path + `" controls width="200" height="200"></video>
                                                        </figure>
                                                        <div class="video-content-wrap">
                                                            <div class="title-wrapper">
                                                                <span class="date-wrap">` + videoDetail.time_diff + `</span>
                                                            </div>
                                                            <a class="wpee-delete-button" href="#" data-video-id="` + videoDetail.video_id + `" data-user-id="` + videoDetail.user_id + `" >
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                        </div>
                                                    </div>
                                                </li>`;    
                        jQuery('.video-main-wrapper').prepend(videoLiStructure);
                    }
                    toastr.success(response.message);
                    return true;
                } else {
                    if( response.errors === undefined ) {
                        jQuery('input[name="video_file"]', videoCreateForm).val('');
                        jQuery('input[name="make_private"]', videoCreateForm).prop("checked", false);
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
                jQuery('input[name="video_file"]', videoCreateForm).val('');
                jQuery('input[name="make_private"]', videoCreateForm).prop("checked", false);
                jQuery('.popup-main-wrapper').removeClass('is-open');
                toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                console.warn('Something went wrong!');
                return false;
            },
            complete: () => {
                jQuery(videoCreateForm).unblock();
            }  
        });
    });

    jQuery('.video-main-wrapper').on( 'click', '.wpee-delete-button', event => {
        if( confirm('<?php echo __('Are you sure you want to delete this video file?', 'wpdating'); ?>') ){
            let videoDeleteButton = event.currentTarget;
            let videoID           = jQuery(videoDeleteButton).attr('data-video-id');
            let userID            = jQuery(videoDeleteButton).attr('data-user-id');

            jQuery.ajax({
                url     : '<?php echo admin_url('admin-ajax.php'); ?>',
                data    : { action : 'wpee_delete_video', video_id : videoID, user_id : userID, lang: '<?php echo $lang; ?>'},
                type    : 'POST',
                beforeSend: () => {
                    jQuery(videoDeleteButton).parent().parent().parent().block({
                        overlayCSS  : {
                            backgroundColor : '#fff',
                            opacity         : 0.6
                        },
                        message      : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
                    });
                },
                success: result => {
                    let response = JSON.parse(result);
                    if ( response.success === true ) {
                        jQuery(videoDeleteButton).parent().parent().parent().remove();
                        toastr.success(response.message);
                        return true;
                    } else {
                        jQuery(videoDeleteButton).parent().parent().parent().unblock();
                        toastr.error(response.message);
                        return false;
                    }
                },
                error: () => {
                    jQuery(videoDeleteButton).parent().parent().parent().unblock();
                    toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                    console.warn('Something went wrong!');
                    return false;
                }
            });
        }
    });

    jQuery('input[name="video_file"]', videoCreateForm).focusin( event => {
        jQuery('.form-error-message', jQuery(event.currentTarget).parent()).text('');
    });
</script>