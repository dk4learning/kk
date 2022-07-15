<div class="box-border">
    <div class="box-pedding">
        <div class="lost-password-page">
            <p class="dspdp-font-2x"><strong><?php echo __('Please enter your username or email address. You will receive a link to create a new password via email.', 'wpdating'); ?></strong></p>
			<span class="dspdp-seprator"></span>
            <div class="box-email-datails">
                <strong id="usernemail"><?php echo __('Username or Email', 'wpdating'); ?></strong><br />
                <form method="post" class="dspdp-form-inline">
                    <input type="text" id="user_n_email" class="dspdp-form-control" />
                    <img id="loading_reset" style="display:none;width: 22px;padding-left: 2px;" src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" alt="Loading" />
                    <input type="submit" class="dspdp-btn dspdp-btn-default" id="get-password" value="<?php echo __('Get Password', 'wpdating'); ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(e) {
        jQuery("#get-password").click(function() {
            jQuery("#loading_reset").show();
            jQuery(".notification,.error").slideUp(function() {
                jQuery(".notification,.error").remove();
            });

            var user_n_email = jQuery("#user_n_email").val(),
                ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>",
                ajaxnonce = "<?php echo wp_create_nonce( "email-verification-nonce" ) ?>";
            jQuery.ajax({
                type: "POST",
                url: ajaxurl + "?action=dsp_verify_email&_wpnonce="+ajaxnonce,
                data: {email:user_n_email},
                dataType: 'json',
                success: function(html) {
                    if (jQuery.trim(html['output']) == 1) {
                        jQuery(".lost-password-page").append('<p style="display:none" class="notification"><?php echo __('Please check your e-mail for the confirmation link.', 'wpdating'); ?></p>');
                        jQuery(".lost-password-page .notification").slideDown();
                        jQuery("#user_n_email").val('');
                    }
                    else {

                        jQuery(".lost-password-page").append('<p style="display:none" class="error"><?php echo __('Invalid username or e-mail', 'wpdating'); ?></p>');
                        jQuery(".lost-password-page .error").slideDown();
                    }
                    jQuery("#loading_reset").hide();
                }
            });
            return false;
        });
    });

</script>