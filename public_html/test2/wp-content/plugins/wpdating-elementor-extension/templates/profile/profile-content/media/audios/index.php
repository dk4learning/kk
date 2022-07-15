<?php
global $wpdb;
$user_id        = wpee_profile_id( );
$current_user   = wp_get_current_user();
$profile_link   = trailingslashit(wpee_get_profile_url_by_id( $user_id));
$current_url    = $profile_link . 'media/audios';

$dsp_member_audios_table     = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;

$page = get_query_var('paged') ? get_query_var('paged') : 1;

if ( is_user_logged_in() && $current_user->ID == $user_id ){
    $audio_feature_access = wpee_check_membership('Upload Audio', $current_user->ID);
}

if ( function_exists( 'pll_get_post_language' ) ) {
    global $post;
    $lang = pll_get_post_language( $post->ID, 'slug' );
} else {
    $lang = '';
}

?>

<div class="audio-list-inner box-border">
    <div class="box-pedding profile-user-audio-inner wpee-block-content">

        <?php
        if ( is_user_logged_in() && $current_user->ID == $user_id && isset($audio_feature_access) && is_wp_error($audio_feature_access) ) :?>
            <div style="text-align: center; display: grid;" class="wpee-membership-error wpee-error">
                <span style="color: #FF0000;">
                    <?php echo $audio_feature_access->get_error_message(); ?>
                </span>
                <span>
                    <a href="<?php echo esc_url( trailingslashit($profile_link) . 'settings/upgrade-account' ); ?>"><?php echo __('Click here', 'wpdating'); ?></a>
                </span>
            </div>
        <?php
        else: 
            $sql_query = "SELECT COUNT(*) FROM {$dsp_member_audios_table} WHERE user_id = '{$user_id}' AND status_id = 1";

            if( $current_user->ID != $user_id ) {
                $sql_query .= " AND private_audio = 'N'";
            }
            
            $member_audio_count = $wpdb->get_var($sql_query);
            if ( $member_audio_count > 0 ) : ?>

            <ul class="audio-main-wrapper">
                <?php
                $sql_query     = str_replace('COUNT(*)', '*', $sql_query);
                $member_audios = $wpdb->get_results($sql_query . " ORDER BY date_added DESC");

                foreach ($member_audios as $member_audio) :?>
                <li>
                    <div class="audio-wrapper">

                        <audio style="width:100%" controls name="media" class="dsp-spacer">
                                <source src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_{$member_audio->user_id}/{$member_audio->file_name}"; ?>" type="audio/mp3">
                        </audio>

                        <div class="audio-content-wrap">
                                                
                            <div class="title-wrapper">
                                        <span class="date-wrap"><?php echo wpee_get_time_difference( $member_audio->date_added ); ?></span>
                            </div>

                            <?php if( is_user_logged_in() && $current_user->ID == $user_id ):?>
                            <a class="wpee-delete-button" href="#" data-audio-id="<?php echo $member_audio->audio_file_id; ?>" data-user-id="<?php echo $member_audio->user_id; ?>" >
                            <i class="fa fa-trash"></i>
                            </a>
                            <?php endif;?>

                        </div>
                    </div>
                </li>
                <?php 
                endforeach;?>

            </ul>

            <?php
            elseif ( $member_audio_count <= 0 && $current_user->ID != $user_id ) : ?>

            <div style="text-align: center; display: grid;" class="wpee-empty-info wpee-info">
                    <span style="color: #FF0000;"><?php esc_html_e( 'The user has not uploaded any audios.', 'wpdating'); ?></span>
            </div>

            <?php
            elseif ( $member_audio_count <= 0 && $current_user->ID == $user_id ) : ?>

            <ul class="audio-main-wrapper"></ul>

            <?php
            endif; ?>

            <?php if(  is_user_logged_in() && $current_user->ID == $user_id ):?>
                <a class="wpee-create-button" href="#"><?php echo __('Add Audio', 'wpdating'); ?></a>
            <?php endif;?>

        <?php
        endif;?>

    </div>
</div>
<div class="audio-popup-main-wrapper popup-main-wrapper">
    <span class="audio-pop-up-bg pop-up-bg"></span>
    <div class="audio-popup-wrapper popup-wrapper">
        <h6 class="audio-popup-title popup-title"><?php echo __('Add Audio', 'wpdating'); ?></h6>
        <form class="audio-popup-form popup-form" methdd="POST">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $current_user->ID; ?>">
            <div class="form-group">
                <input type="file" name="audio_file" id="audio_file">
                <span class='form-audio-file-error-message form-error-message'></span>
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
    let audioCreateForm = jQuery('.audio-popup-form');

    jQuery('.audio-popup-form').submit( event => {
        event.preventDefault();

        let file = jQuery('input[name="audio_file"]', audioCreateForm).val();

        if (jQuery.trim(file) === "") {
            jQuery('.form-audio-name-error-message').text('<?php echo __('No file selected', 'wpdating'); ?>');
            return false;
        }

        let formData = new FormData();

        let audioFormData = audioCreateForm.serializeArray();

        jQuery.each(audioFormData, function (key, input) {
            formData.append(input.name, input.value);
        });

        let audioFileData = jQuery('input[name="audio_file"]', audioCreateForm)[0].files;
        formData.append("audio_file", audioFileData[0]);

        formData.append('action', 'wpee_create_audio');
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
                jQuery(audioCreateForm).block({
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
                    jQuery('input[name="audio_file"]', audioCreateForm).val('');
                    jQuery('input[name="make_private"]', audioCreateForm).prop("checked", false);
                    if(response.audio_detail !== undefined) {
                        let audioDetail      = response.audio_detail;
                        let audioLiStructure = `<li>
                                                    <div class="audio-wrapper">
                                                        <audio style="width:100%" controls name="media" class="dsp-spacer">
                                                            <source src="` + audioDetail.file_path + `" type="audio/mp3">
                                                        </audio>
                                                        <div class="audio-content-wrap">
                                                            <div class="title-wrapper">
                                                                <span class="date-wrap">` + audioDetail.time_diff +`</span>
                                                            </div>
                                                            <a class="wpee-delete-button" href="#" data-audio-id="` + audioDetail.audio_id  + `" data-user-id="` + audioDetail.user_id + `" >
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>`;    
                        jQuery('.audio-main-wrapper').prepend(audioLiStructure);
                    }
                    toastr.success(response.message);
                    return true;
                } else {
                    if( response.errors === undefined ) {
                        jQuery('input[name="audio_file"]', audioCreateForm).val('');
                        jQuery('input[name="make_private"]', audioCreateForm).prop("checked", false);
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
                jQuery('input[name="audio_file"]', audioCreateForm).val('');
                jQuery('input[name="make_private"]', audioCreateForm).prop("checked", false);
                jQuery('.popup-main-wrapper').removeClass('is-open');
                toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                console.warn('Something went wrong!');
                return false;
            },
            complete: () => {
                jQuery(audioCreateForm).unblock();
            }  
        });
    });

    jQuery('.audio-main-wrapper').on( 'click', '.wpee-delete-button', event => {
        if( confirm('<?php echo __('Are you sure you want to delete this audio file?', 'wpdating'); ?>') ){
            let audioDeleteButton = event.currentTarget;
            let audioID           = jQuery(audioDeleteButton).attr('data-audio-id');
            let userID            = jQuery(audioDeleteButton).attr('data-user-id');

            jQuery.ajax({
                url     : '<?php echo admin_url('admin-ajax.php'); ?>',
                data    : { action : 'wpee_delete_audio', audio_id : audioID, user_id : userID, lang: '<?php echo $lang; ?>'},
                type    : 'POST',
                beforeSend: () => {
                    jQuery(audioDeleteButton).parent().parent().parent().block({
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
                        jQuery(audioDeleteButton).parent().parent().parent().remove();
                        toastr.success(response.message);
                        return true;
                    } else {
                        jQuery(audioDeleteButton).parent().parent().parent().unblock();
                        toastr.error(response.message);
                        return false;
                    }
                },
                error: () => {
                    jQuery(audioDeleteButton).parent().parent().unblock();
                    toastr.error('<?php echo __('Something went wrong!', 'wpdating'); ?>');
                    console.warn('Something went wrong!');
                    return false;
                }
            });
        }
    });

    jQuery('input[name="audio_file"]', audioCreateForm).focusin( event => {
        jQuery('.form-error-message', jQuery(event.currentTarget).parent()).text('');
    });
</script>
