<?php

/**
 * Return after login redirect url.
 *
 * @param string $url
 * @return string|string[]
 *
 */
if ( ! function_exists( 'wpee_sidebar_login_current_url' ) ) {
    function wpee_sidebar_login_current_url( $url ) {
        $page_url = 'http://';
        $page_url .= $_SERVER['HTTP_HOST'];
        $language = get_locale();

        $profile_pages = get_option( 'wpee_profile_page' );
        $profile_page  = $profile_pages[$language];

        if ( function_exists( 'pll_get_post_language' ) ) {
            global $post;
            $lang             = pll_get_post_language( $post->ID, 'slug' );
            $default_language = pll_default_language();

            if ( $lang == $default_language ) {
                $page_url .= '/' . $profile_page;
            } else {
                $page_url .= '/' . $lang . '/' . $profile_page;
            }
        } else {
            $page_url .= $_SERVER['REQUEST_URI'] . '/' . $profile_page;
        }

        if ( force_ssl_admin() ) $page_url = str_replace( 'http:', 'https:', $page_url );

        if ( $url != "nologout" ) {
            if ( ! strpos( $page_url, '_login=' ) ) {
                $rand_string = md5( uniqid(rand(), true ) );
                $rand_string = substr( $rand_string, 0, 10 );
                $page_url = add_query_arg( '_login', $rand_string, $page_url );
            }
        }
        return $page_url;
    }
}

$login_error = null;

// Get login redirect URL
$redirect_to = trim( stripslashes( get_option( 'sidebarlogin_login_redirect' ) ) );
if ( empty( $redirect_to ) ) {
    if ( isset( $_REQUEST['redirect_to'] ) ) {
        $redirect_to = esc_url($_REQUEST['redirect_to']);
    } else {
        $redirect_to = wpee_sidebar_login_current_url('nologout');
    }
}
?>
<div class="wpee-lr-content-tab lr-login active">
    <div class="wpee-login-tab">
        <form name="login-form" id="login-form" class="login-form" method="post">
            <?php wp_nonce_field( 'ds-login-action', 'wpee-login-nonce' );?>
            <div class="form-group user-login-group">
                <label for="login-user-login"><?php echo __( 'Username', 'wpdating') . '/' . __( 'Email', 'wpdating' ); ?></label>
                <input type="text" name="user_login" class="form-control" id="login-user-login"
                       placeholder="<?php echo __( 'Username', 'wpdating' ); ?>" required>
            </div>
            <div class="form-group user-password-group">
                <label for="login-user-password"><?php echo __( 'Password', 'wpdating'); ?></label>
                <input type="password" name="user_password" id="login-user-password"
                       class="dsp-form-control"
                       placeholder="<?php echo __( 'Password', 'wpdating' ); ?>" required>
            </div>
            <div class="form-inline remember-me-group">
                <div class="form-group">
                    <input name="remember_me" class="checkbox" id="remember-me" value="forever" type="checkbox" />
                    <label for="remember-me"><?php echo __( 'Remember Me', 'wpdating' ); ?></label>
                </div>
                <div class="form-group">
                    <a class="fpw-link bttn login-tab-trigger" data-id="lr-forget" href="javascript:void(0);" data-tab-content="forget" rel="nofollow"><?php echo __( 'Forgot Password', 'wpdating' ) ?></a>
                </div>
            </div>
            <input type="hidden" name="redirect_to" class="redirect_to" value="<?php echo $redirect_to; ?>" />
            <input type="submit" name="login_button" class="btn btn-primary" value="<?php echo __( 'Login', 'wpdating' ); ?>" />
            <div class="wpdating-theme-fb">
                <?php
                do_action( 'wpdating_facebook_login' );
                ?>
            </div>
        </form>
    </div>
</div>

<script>
    jQuery('#login-form').submit( async ( event ) => {
        event.preventDefault();

        jQuery("#login-form .form-group").removeClass('form-group-error');
        jQuery('#login-form .form-group .input-error').remove();

        const loginForm = event.currentTarget;

        const userLogin = jQuery('input[name="user_login"]', loginForm).val().trim();
        const password  = jQuery('input[name="user_password"]', loginForm).val().trim();

        if ( userLogin.length === 0 || password.length === 0 ){
            jQuery('.login-error-message').html('<?php echo __('All fields required.', 'wpdating'); ?>');
            return false;
        }

        const data = {
            action          : 'sidebar_login_process',
            security        : jQuery('input[name="wpee-login-nonce"]', loginForm).val().trim(),
            user_login      : userLogin,
            user_password   : password,
            remember        : jQuery('input[name="remember_me"]', loginForm ).val().trim(),
            redirect_to     : jQuery('input[name="redirect_to"]', loginForm).val().trim()
        };

        await jQuery.ajax({
            url     : '<?php echo admin_url('admin-ajax.php'); ?>',
            data    : data,
            type    : 'POST',
            dataType: 'jsonp',
            beforeSend: () => {
                jQuery('.login_error').remove();
                jQuery(loginForm).block({
                    overlayCSS  : {
                        backgroundColor : '#fff',
                        opacity         : 0.6,
                    },
                    message             : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
                });
            },
            success: result => {
                if ( result.success === 1 ) {
                    window.location = result.redirect;
                    return true;
                } else {
                    if ( result.error.includes('Unknown email') === true ){
                        jQuery('.user-login-group').addClass('form-group-error');
                        jQuery('.user-login-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Invalid Email', 'wpdating' ); ?>'  + '</span>');
                    } else if ( result.error.includes('Unknown username') === true ) {
                        jQuery('.user-login-group').addClass('form-group-error');
                        jQuery('.user-login-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Invalid Username', 'wpdating' ); ?>'  + '</span>');
                    } else if ( result.error.includes('password') === true ) {
                        jQuery('.user-password-group').addClass('form-group-error');
                        jQuery('.user-password-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Invalid Password', 'wpdating' ); ?>'  + '</span>');
                    }
                    return false;
                }
            },
            error: () => {
                console.warn('Something went wrong');
                return false;
            },
            complete: () => {
                jQuery(loginForm).unblock();
            }
        });
    });

    jQuery('.user-login-group').focusin( event => {
        jQuery(event.currentTarget).removeClass('form-group-error');
        jQuery('.user-login-group .input-error').remove();
    });

    jQuery('.user-password-group').focusin( event => {
        jQuery(event.currentTarget).removeClass('form-group-error');
        jQuery('.user-password-group .input-error').remove();
    });
</script>