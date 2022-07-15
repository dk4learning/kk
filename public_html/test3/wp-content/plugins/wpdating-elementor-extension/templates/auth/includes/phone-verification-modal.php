<form name="phone-verification-form" id="phone-verification-form" action="" method="post" style="display: none;">
    <div class="header">
        <h5><?php echo __('Phone Verification', 'wpdating'); ?></h5>
    </div>
    <div class="body">
        <input type="hidden" class="form-control" id="verification-phone-number" name="phone_number" />
        <div class="form-group phone-verification-sms-code-group">
            <label for="sms-code" class="col-form-label"><?php _e( 'Enter Code', 'wpdating'); ?>:</label>
            <input type="text" class="form-control" id="sms-code" name="sms_code" />
        </div>
        <div class="form-inline verification-option-button-group">
            <div class="form-group">
                <a class="fpw-link bttn verification-option-button" data-id="back" href="javascript:void(0);" ><?php _e( 'Back', 'wpdating' ) ?></a>
            </div>
            <div class="form-group">
                <a class="fpw-link bttn verification-option-button" data-id="resend-code" href="javascript:void(0);" ><?php _e( 'Resend Code', 'wpdating' ) ?></a>
            </div>
        </div>
    </div>
    <div class="footer">
        <input type="submit" class="btn btn-secondary" name="verify" id="verify-phone-verification-code" value="<?php _e( 'Verify', 'wpdating' ); ?>">
    </div>
</form>
<script type="text/javascript">
    jQuery('.phone-verification-sms-code-group').click( event => {
        jQuery('.phone-verification-sms-code-group').removeClass('form-group-error');
        jQuery('.phone-verification-sms-code-group .input-error').remove();
    });

    jQuery('.verification-option-button').click( event => {
        const verificationOptionButton = event.currentTarget;
        const option = jQuery(verificationOptionButton).attr('data-id');

        switch (option) {
            case 'back':
                jQuery('#phone-verification-form #verification-phone-number').val('');
                jQuery('.wpee-lr-tab-content-wrap').removeClass('verification-form-content-active');
                jQuery('#phone-verification-form').hide();
                jQuery('#wpee-registration-form').show();
                break;

            case 'resend-code':
                const fullPhoneNumber = jQuery('#phone-verification-form #verification-phone-number').val();
                const sendMsgAjaxRequestData = {
                    'action'      : 'sendmsg_action',
                    'phone_number': fullPhoneNumber,
                };
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: sendMsgAjaxRequestData,
                    beforeSend  : () => {
                        displayBlock( '#phone-verification-form', 'show' );
                    },
                    success: response => {
                        if ( response === '' ) {
                            toastr.error('<?php echo __( 'Something went wrong. Please try again later!', 'wpdating' ); ?>');
                        } else {
                            toastr.success('<?php echo __( 'Code sent successfully.', 'wpdating' ); ?>');
                        }
                    },
                    error: (request, status, error) => {
                        toastr.error('<?php echo __( 'Something went wrong. Please try again later!', 'wpdating' ); ?>');
                        console.warn('Something went wrong');
                        return false;
                    },
                    complete: () => {
                        displayBlock( '#phone-verification-form', 'hide' );
                    }
                });
                break;
        }

    });
</script>