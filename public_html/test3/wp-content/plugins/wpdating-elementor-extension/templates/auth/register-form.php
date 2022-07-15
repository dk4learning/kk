<?php
global $wpee_general_settings, $wpee_genders;

$is_first_n_last_name_enabled    = isset( $wpee_general_settings->register_form_setting ) &&
    ! empty( $wpee_general_settings->register_form_setting ) && $wpee_general_settings->register_form_setting->status == 'Y';
$site_key                        = isset( $wpee_general_settings->google_api_key ) &&
    ! empty( $wpee_general_settings->google_api_key ) ? $wpee_general_settings->google_api_key->value : '';
$secret                          = isset( $wpee_general_settings->google_secret_key ) &&
    !empty( $wpee_general_settings->google_secret_key ) ? $wpee_general_settings->google_secret_key->value : '';
$is_google_recaptcha_enable      = isset( $wpee_general_settings->recaptcha_option ) && !empty( $wpee_general_settings->recaptcha_option ) &&
    $wpee_general_settings->recaptcha_option->status == 'Y' && !empty( $site_key ) && !empty( $secret );
$send_email_verification_enabled = isset( $wpee_general_settings->after_user_register_option ) &&
    ! empty( $wpee_general_settings->after_user_register_option ) && $wpee_general_settings->after_user_register_option->value == 'verify_email';
$is_password_option_enabled = isset( $wpee_general_settings->password_option ) &&
        ! empty( $wpee_general_settings->password_option ) && $wpee_general_settings->password_option->status == 'Y';

$is_twilio_registration_enabled = is_plugin_active( 'twilio_registration/twilio_registration.php' );
?>
<div class="wpee-lr-content-tab lr-register">
    <div class="box-page">
        <div class="success-message-info">
            <span></span>
        </div>
    </div>
    <?php
    if ( get_option('users_can_register') ) : ?>
        <form id="wpee-registration-form" method="post" class="dspdp-form-horizontal">
            <?php wp_nonce_field( 'wpee_registration_page', 'wpee-register-nonce' );?>
            <div class="dsp_reg_main dsp-form-group">
                <?php if ( $is_twilio_registration_enabled ) :
                    $country_codes = json_decode(file_get_contents(WP_CONTENT_DIR . "/plugins/twilio_registration/public/CountryCodes.json"), true); ?>
                <div class="form-group register-phone-code-group">
                    <label for="phone-code"><?php echo __('Phone Code', 'wpdating'); ?></label>
                    <select name="phone_code" id="phone-code" class="dsp-form-control dspdp-form-control">
                        <?php foreach ($country_codes as $country_code) : ?>
                            <option value="<?php echo $country_code['dial_code']; ?>"><?php echo $country_code['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group register-phone-number-group">
                    <label for="phone-number"><?php echo __('Phone Number', 'wpdating'); ?></label>
                    <input type="text" id="phone-number" name="phone_number" class="text dsp-form-control dspdp-form-control validate-empty " required />
                </div>
                <?php endif; ?>
                <div class="form-group register-username-group">
                    <label for="username"><?php echo __('Username', 'wpdating'); ?></label>
                    <input type="text" id="username" name="username" class="text dsp-form-control dspdp-form-control validate-empty " required />
                </div>
                <?php if ( $is_first_n_last_name_enabled ) : ?>
                    <div class="form-group">
                        <label for="first-name"><?php echo __('First Name', 'wpdating'); ?></label>
                        <input type="text" id="first-name" name="first_name" class="text dsp-form-control dspdp-form-control validate-empty " required />
                    </div>
                    <div class="form-group">
                        <label for="last-name"><?php echo __('Last Name', 'wpdating'); ?></label>
                        <input type="text" id="last_name" name="last_name" class="text dsp-form-control dspdp-form-control validate-empty " required />
                    </div>
                <?php endif; ?>
                <?php if ( $is_password_option_enabled ) : ?>
                    <div class="form-group register-password-group">
                        <label for="password"><?php echo __( 'Password', 'wpdating' );?></label>
                        <input type="password" id="password" name="password" class="text form-control " data-parsley-trigger="change" data-parsley-minlength="4" required />
                    </div>
                    <div class="form-group register-re-password-group">
                        <label for="re-password"><?php echo __( 'Confirm Password', 'wpdating' );?></label>
                        <input type="password" id="re-password" name="re_password" class="text form-control " data-parsley-equalto="#dsp-password"
                               data-parsley-trigger="change" data-parsley-minlength="4" required/>
                    </div>
                <?php endif; ?>
                <div class="form-group register-email-group">
                    <label for="email"><?php echo __('Email', 'wpdating'); ?></label>
                    <input type="text" id="email" data-parsley-type="email" name="email" class="text dsp-form-control dspdp-form-control validate-empty"
                           value="<?php echo isset($email)?$email:'';?>"  data-parsley-trigger="change" required />
                </div>
                <div class="form-group register-confirm-email-group">
                    <label for="confirm-email"><?php echo __('Confirm Email', 'wpdating'); ?></label>
                    <input type="text" id="confirm-email" name="confirm_email" data-parsley-type="email" class="text dsp-form-control dspdp-form-control validate-empty"
                           value="<?php echo isset($confirm_email)?$confirm_email:'';?>"  data-parsley-equalto="#dsp-email" data-parsley-trigger="change" required />
                </div>
                <div class="form-group register-gender-group">
                    <label for="gender"><?php echo __('Gender', 'wpdating'); ?></label>
                    <select name="gender" id="gender" class="dsp-form-control dspdp-form-control">
                        <?php foreach ( $wpee_genders as $key => $value ) : ?>
                            <option value="<?php echo $key; ?>" ><?php _e( $value, 'wpdating' ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-inline dob-wrap register-birth-date-group">
                    <label>
                        <?php echo __('Birth Date', 'wpdating'); ?>
                    </label>
                    <?php
                    $mon = array(
                        __('January', 'wpdating'),
                        __('February', 'wpdating'),
                        __('March', 'wpdating'),
                        __('April', 'wpdating'),
                        __('May', 'wpdating'),
                        __('June', 'wpdating'),
                        __('July', 'wpdating'),
                        __('August', 'wpdating'),
                        __('September', 'wpdating'),
                        __('October', 'wpdating'),
                        __('November', 'wpdating'),
                        __('December', 'wpdating')
                    ); ?>
                    <select name="dsp_year" class="dsp-form-control  dspdp-form-control dspdp-xs-form-group">
                        <?php
                        $start_dsp_year = $wpee_general_settings->start_dsp_year->value;
                        $end_dsp_year   = $wpee_general_settings->end_dsp_year->value;
                        echo dsp_get_year( $start_dsp_year, $end_dsp_year, $year );
                        ?>
                    </select>
                    <select name="dsp_mon" class="dsp-form-control dspdp-form-control  dspdp-xs-form-group" >
                        <?php foreach ($mon as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="dsp_day" class="dsp-form-control dspdp-form-control dspdp-xs-form-group" >
                        <?php for ( $i = 1; $i <= 31; $i++) : ?>
                            <option value="<?php echo $i; ?>" <?php echo $i; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <?php if ( $wpee_general_settings->terms_page->status == 'Y' ) : ?>
                    <div class="form-group register-terms-group">
                        <input id="terms" class="check validate-empty" name="terms" type="checkbox" value="1" required/>
                        <label for="terms"><?php echo str_replace('[L]', $wpee_general_settings->terms_page->value,
                                __( 'I agree to the <a href=[L] target=_blank>Terms and Conditions</a>.', 'wpdating' ) ); ?></label>
                    </div>
                <?php endif; ?>
                <?php if ( $is_google_recaptcha_enable ) : ?>
                    <div class="form-group register-g-recaptcha-group">
                        <span><?php echo __( 'Captcha:', 'wpdating' ); ?></span>
                        <span class="dsp-md-6  dspdp-col-sm-6 g-recaptcha"  data-sitekey="<?php echo $site_key; ?>">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <input type="submit" class="dspdp-btn dspdp-btn-default" name="submit" value="<?php echo __('Register', 'wpdating'); ?>" />
                    <?php if ( ! $is_password_option_enabled ) : ?>
                        <span class="note-res" style="float:none; margin-left:10px;"><?php echo __('Note: A password will be emailed to you.', 'wpdating'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    <?php else : ?>
        <span style="float:left; width:100%; text-align:center; color:#ff0000;">
            <?php echo __('Registration is currently disabled.', 'wpdating'); ?>
        </span>
    <?php endif; ?>
    <?php
    if ( $is_twilio_registration_enabled ) {
        require_once 'includes/phone-verification-modal.php';
    }
    ?>
</div>

<script type="text/javascript">

    /**
     * hide or show block section.
     *
     */
    const displayBlock = ( selector, option ) => {
        if ( option === 'show' ) {
            jQuery(selector).block({
                overlayCSS  : {
                    backgroundColor : '#fff',
                    opacity         : 0.6
                },
                message             : '<h1><?php _e( 'Please Wait', 'wpdating' ); ?>...</h1>'
            });
        } else {
            jQuery(selector).unblock();
        }
    }

    <?php if ( $is_google_recaptcha_enable ) : ?>
        function reloadCaptcha() {
            document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + new Date();
        }
    <?php endif; ?>

    <?php if ( $is_first_n_last_name_enabled ) : ?>
        jQuery('#wpee-registration-form .register-first-name-group #first-name').focusin( event => {
            jQuery('#wpee-registration-form .register-first-name-group').removeClass('form-group-error');
            jQuery('#wpee-registration-form .register-first-name-group .input-error').remove();
        });

        jQuery('#wpee-registration-form .register-last-name-group #last-name').focusin( event => {
            jQuery('#wpee-registration-form .register-last-name-group').removeClass('form-group-error');
            jQuery('#wpee-registration-form .register-last-name-group .input-error').remove();
        });
    <?php endif; ?>

    <?php if ( $is_password_option_enabled ) : ?>
        jQuery('.register-password-group #password').focusin( event => {
            jQuery('.register-password-group').removeClass('form-group-error');
            jQuery('.register-password-group .input-error').remove();
        });

        jQuery('.register-re-password-group #re-password').focusin( event => {
            jQuery('.register-re-password-group').removeClass('form-group-error');
            jQuery('.register-re-password-group .input-error').remove();
        });
    <?php endif; ?>

    <?php if ( $is_google_recaptcha_enable ) : ?>
        jQuery('.register-g-recaptcha-group').click( event => {
            jQuery('#wpee-registration-form .register-g-recaptcha-group').removeClass('form-group-error');
            jQuery('#wpee-registration-form .register-g-recaptcha-group .input-error').remove();
        });
    <?php endif; ?>

    <?php if ( $is_twilio_registration_enabled ) : ?>
        jQuery('.register-phone-number-group').click( event => {
            jQuery('#wpee-registration-form .register-phone-number-group').removeClass('form-group-error');
            jQuery('#wpee-registration-form .register-phone-number-group .input-error').remove();
        });
    <?php endif; ?>

    jQuery('.register-username-group #username').focusin( event => {
        jQuery('#wpee-registration-form .register-username-group').removeClass('form-group-error');
        jQuery('#wpee-registration-form .register-username-group .input-error').remove();
    });

    jQuery('.register-email-group #email').focusin( event => {
        jQuery('.register-email-group').removeClass('form-group-error');
        jQuery('.register-email-group .input-error').remove();
    });

    jQuery('.register-confirm-email-group #confirm-email').focusin( event => {
        jQuery('.register-confirm-email-group').removeClass('form-group-error');
        jQuery('.register-confirm-email-group .input-error').remove();
    });

    let registerFormData;
    let registerForm;
    jQuery("#wpee-registration-form").submit(event => {
        event.preventDefault();

        registerForm = event.currentTarget;

        displayBlock( registerForm, 'show' );

        jQuery('#wpee-registration-form .form-group').removeClass('form-group-error');
        jQuery('#wpee-registration-form .form-group .input-error').remove();

        let error    = [];

        <?php if ( $is_twilio_registration_enabled ) : ?>
        const phoneCode   = jQuery('select[name="phone_code"]', registerForm).val().trim();
        const phoneNumber = jQuery('input[name="phone_number"]', registerForm).val().trim();
        if ( phoneNumber.length === 0 ){
            jQuery('.register-phone-number-group').addClass('form-group-error');
            jQuery('.register-phone-number-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter phone number.', 'wpdating' ); ?>'  + '</span>');
            error['phone-number'] = true;
        } else if ( isNaN(phoneNumber) ){
            jQuery('.register-phone-number-group').addClass('form-group-error');
            jQuery('.register-phone-number-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter valid phone number.', 'wpdating' ); ?>'  + '</span>');
            error['phone-number'] = true;
        }
        <?php endif; ?>

        const username = jQuery('input[name="username"]', registerForm).val().trim();
        if ( username.length === 0 ){
            jQuery('.register-username-group').addClass('form-group-error');
            jQuery('.register-username-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter username.', 'wpdating' ); ?>'  + '</span>');
            error['username'] = true;
        } else if ( username.includes(" ") ){
            jQuery('.register-username-group').addClass('form-group-error');
            jQuery('.register-username-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Username can not have spaces.', 'wpdating' ); ?>'  + '</span>');
            error['username'] = true;
        }

        const email = jQuery('input[name="email"]', registerForm).val().trim();
        const emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if ( email.length === 0 ){
            jQuery('.register-email-group').addClass('form-group-error');
            jQuery('.register-email-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter email.', 'wpdating' ); ?>'  + '</span>');
            error['email'] = true;
        } else if ( !emailReg.test( email ) ) {
            jQuery('.register-email-group').addClass('form-group-error');
            jQuery('.register-email-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __('Please enter a valid email address.', 'wpdating'); ?>' + '</span>');
            error['email'] = true;
        }

        const confirmEmail = jQuery('input[name="confirm_email"]', registerForm).val().trim();
        if ( confirmEmail.length === 0 ){
            jQuery('.register-confirm-email-group').addClass('form-group-error');
            jQuery('.register-confirm-email-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Confirm email.', 'wpdating' ); ?>'  + '</span>');
            error['confirm-email'] = true;
        } else if ( confirmEmail !== email ) {
            jQuery('.register-confirm-email-group').addClass('form-group-error');
            jQuery('.register-confirm-email-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __('Provided emails are not same. Please fill again.', 'wpdating'); ?>' + '</span>');
            error['confirm-email'] = true;
        }

        <?php if ( $is_first_n_last_name_enabled ) : ?>
        const first_name = jQuery('input[name="first_name"]', registerForm).val().trim();
        if ( first_name.length === 0 ){
            jQuery('.register-first-name-group').addClass('form-group-error');
            jQuery('.register-first-name-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter first name.', 'wpdating' ); ?>'  + '</span>');
            error['first-name'] = true;
        }

        const last_name = jQuery('input[name="last_name"]', registerForm).val().trim();
        if ( last_name.length === 0 ){
            jQuery('.register-last-name-group').addClass('form-group-error');
            jQuery('.register-last-name-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter last name.', 'wpdating' ); ?>'  + '</span>');
            error['last-name'] = true;
        }
        <?php endif; ?>

        <?php if ( $is_password_option_enabled ) : ?>
        const password = jQuery('input[name="password"]', registerForm).val().trim();
        if ( password.length === 0 ){
            jQuery('.register-password-group').addClass('form-group-error');
            jQuery('.register-password-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Enter password.', 'wpdating' ); ?>'  + '</span>');
            error['password'] = true;

            const rePassword = jQuery('input[name="re_password"]', registerForm).val().trim();
            if ( rePassword.length === 0 ){
                jQuery('.register-re-password-group').addClass('form-group-error');
                jQuery('.register-re-password-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Confirm password.', 'wpdating' ); ?>'  + '</span>');
                error['re-password'] = true;
            } else if ( rePassword !== password ) {
                jQuery('.register-re-password-group').addClass('form-group-error');
                jQuery('.register-re-password-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __('Please enter the same password in the two password fields.', 'wpdating'); ?>' + '</span>');
                error['re-password'] = true;
            }
        }
        <?php endif; ?>

        <?php if ( $wpee_general_settings->terms_page->value == 'Y' ) : ?>
        if ( !jQuery('input[name="terms"]', registerForm).is(':checked') ){
            jQuery('.register-terms-group').addClass('form-group-error');
            jQuery('.register-terms-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + '<?php echo __( 'Please accept Terms and Conditions.', 'wpdating' ); ?>'  + '</span>');
            error['terms'] = true;
        }
        <?php endif; ?>

        if ( Object.keys( error ).length > 0 ) {
            displayBlock( registerForm, 'hide' );
            let [first] = Object.keys( error );
            document.querySelector('.register-' + first + '-group').scrollIntoView({ behavior: 'smooth' })
            return false;
        }

        let registerValidationFormData = jQuery(registerForm).serializeArray();
        registerValidationFormData.push({ 'name': 'action', 'value': 'wpee_validate_register_form'});

        jQuery.ajax({
            url         : '<?php echo admin_url('admin-ajax.php'); ?>',
            type        : 'POST',
            data        : registerValidationFormData,
            success     : response => {
                response = JSON.parse(response);
                if ( ! response.status ) {
                    displayBlock( registerForm, 'hide' );
                    jQuery.each( response.errors, ( index, value ) => {
                        if ( index === 'toastr' ) {
                            toastr.error(value);
                        } else {
                            jQuery('.register-' + index + '-group').addClass('form-group-error');
                            jQuery('.register-' + index + '-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + value + '</span>');
                        }
                    });
                    let [first] = Object.keys(response.errors);
                    document.querySelector('.register-' + first + '-group').scrollIntoView({ behavior: 'smooth' });
                    return false;
                } else {
                    registerFormData = jQuery(registerForm).serializeArray();
                    registerFormData.push({ 'name': 'action', 'value': 'wpee_wpee_form_registration'});

                    <?php if ( function_exists( 'pll_get_post_language' ) ) :
                    global $post;
                    $lang = pll_get_post_language( $post->ID, 'slug' );
                    ?>
                    registerFormData.push({ 'name': 'lang', 'value': '<?php echo $lang; ?>'});
                    <?php endif; ?>
                    <?php if ( $is_twilio_registration_enabled ) : ?>
                    const fullPhoneNumber = phoneCode + phoneNumber;
                    const sendMsgAjaxRequestData = {
                        'action'      : 'sendmsg_action',
                        'phone_number': fullPhoneNumber,
                    };
                    jQuery.ajax({
                        url         : '<?php echo admin_url('admin-ajax.php'); ?>',
                        type        : 'POST',
                        data        : sendMsgAjaxRequestData,
                        success     : response => {
                            if (response) {
                                jQuery('.wpee-lr-tab-content-wrap').addClass('verification-form-content-active');
                                jQuery('#phone-verification-form #verification-phone-number').val(fullPhoneNumber);
                                jQuery(registerForm).hide();
                                jQuery('#phone-verification-form').show();
                                return true;
                            } else {
                                toastr.error('<?php echo __('Something went wrong. Please try again later!', 'wpdating'); ?>');
                                return false;
                            }
                        },
                        error       : ( request, status, error ) => {
                            toastr.error('<?php echo __('Something went wrong. Please try again later!', 'wpdating'); ?>');
                            console.warn('Something went wrong');
                            return false;
                        },
                        complete    : () => {
                            displayBlock( registerForm, 'hide' );
                        }
                    });
                    <?php else : ?>
                    submitRegisterFormAjaxRequest(registerForm);
                    <?php endif; ?>
                }
            },
            error       : ( request, status, error ) => {
                displayBlock( registerForm, 'hide' );
                toastr.error('<?php echo __('Something went wrong. Please try again later!', 'wpdating'); ?>');
                console.warn('Something went wrong');
                return false;
            }
        });
    });

    <?php if ( $is_twilio_registration_enabled ) : ?>
    jQuery('#phone-verification-form').submit( event => {
        jQuery('.form-group', jQuery(event.currentTarget)).removeClass('form-group-error');
        jQuery('.form-group .input-error', jQuery(event.currentTarget)).remove();
        event.preventDefault();
        displayBlock( event.currentTarget, 'show' );

        const phoneVerificationForm = event.currentTarget;

        const phoneNumber = jQuery('input[name="phone_number"]', phoneVerificationForm).val().trim();
        const smsCode     = jQuery('input[name="sms_code"]', phoneVerificationForm).val().trim();

        const verifySMSCodeRequest = {
            'action'        : 'verification_action',
            'phone_number'  :  phoneNumber,
            'code'          :  smsCode
        };

        jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', verifySMSCodeRequest, function(response) {
            if (response === '1') {
                jQuery('.phone-verification-modal').hide();
                submitRegisterFormAjaxRequest( event.currentTarget );
                return true;
            }else{
                displayBlock( event.currentTarget, 'hide' );
                jQuery('.phone-verification-sms-code-group').addClass('form-group-error');
                jQuery('.phone-verification-sms-code-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + response  + '</span>');
                return false;
            }
        });
    });
    <?php endif; ?>

    /**
     * Send ajax request for register form submit to server
     *
     */
    function submitRegisterFormAjaxRequest( parentTarget ) {
        jQuery.ajax({
            url         : '<?php echo admin_url('admin-ajax.php'); ?>',
            type        : 'POST',
            data        : registerFormData,
            success     : response => {
                if ( response ){
                    response = JSON.parse(response);
                    if ( response.success === true ) {
                        if ( response.auto_login === true ){
                            window.location.href = response.redirect_url;
                        } else {
                            console.log(response);
                            jQuery(parentTarget).hide();
                            jQuery('.success-message-info span').html(response.message);
                        }
                        return true;
                    } else {
                        jQuery.each( response.errors, ( index, value ) => {
                            jQuery('.register-' + index + '-group').addClass('form-group-error');
                            jQuery('.register-' + index + '-group').append('<span class="input-error"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>' + value + '</span>');
                        });
                        let [first] = Object.keys(response.errors);
                        document.querySelector('.register-' + first + '-group').scrollIntoView({ behavior: 'smooth' });
                        return false;
                    }
                } else {
                    toastr.error('<?php echo __('Something went wrong. Please try again later!', 'wpdating'); ?>');
                    console.warn('Something went wrong');
                    return false;
                }
            },
            error       : ( request, status, error ) => {
                toastr.error('<?php echo __('Something went wrong. Please try again later!', 'wpdating'); ?>');
                console.warn('Something went wrong');
                return false;
            },
            complete       : () => {
                displayBlock( parentTarget, 'hide' );
            }
        });
    }
</script>
