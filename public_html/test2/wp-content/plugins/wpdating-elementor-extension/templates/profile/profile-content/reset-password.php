
<?php
extract($_REQUEST);
$data = explode(',', base64_decode($key));

global $wpdb;
$check_pass_changed = $wpdb->get_var("SELECT count(*) FROM `{$wpdb->users}` where user_pass like '{$data[1]}'");
?>
<div class="main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <li class="profile-section-tab-title active"><a href="https://premiumv2.wpdating.com/profile/premiumv2//friends/list"><?php echo __( 'Reset Password','wpdating' );?></a>
        </li>
    </ul>
    <div class="profile-section-content">
        <?php if ($check_pass_changed > 0) : ?>
            <div style="margin-left:0px;" class="box-email-datails">
                <strong><?php echo __('Enter your new password below.', 'wpdating'); ?></strong><br />
                <form method="post" class="dspdp-form-inline">
                    <br/>
                    <label for="password"><?php echo __('New Password','wpdating'); ?></label>
                    <input type="password" class="dspdp-form-control dsp-form-control" id="password" /><br /><br />
                    <label for="cpassword"><?php echo __('Confirm New Password','wpdating'); ?></label>
                    <input type="password" class="dspdp-form-control dsp-form-control" id="cpassword" /><br /><br />
                    <input type="submit" class="dspdp-btn dspdp-btn-default" id="reset_password" value="<?php echo __('Reset Password', 'wpdating'); ?>" />
                    <img id="loading_reset" style="display:none;width: 22px;padding-left: 2px;" src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" alt="Loading" />
                    <input type="hidden" id="pass" value="<?php echo isset($key) ? '0' : '1'; ?>" />
                    <input type="hidden" id="user_id" value="<?php echo $data[0]; ?>" />
                </form>

            </div><br />
            <p class="hint"><?php echo __('Note: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like !', 'wpdating'); ?></p>
        <?php else : ?>
            <p class="error"><?php echo __('Reset password link has been expired.', 'wpdating'); ?></p>
        <?php endif; ?>
    </div>
</div>
<script>
    jQuery(document).ready(function(e) {
        jQuery("#reset_password").click(function() {
            jQuery(".notification,.error").remove();
            let keyPass = parseInt(jQuery("#pass").val());
            if (keyPass === 0) {
                let userID    = jQuery("#user_id").val();
                let password  = jQuery("#password").val();
                let cpassword = jQuery("#cpassword").val();
                if (jQuery.trim(password).length < 7) {
                    jQuery("#reset_password").after('<p style="color:red;margin: 10px 0 0 0;" class="error"><?php echo addslashes(__('The password should be at least seven characters long.', 'wpdating')); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                if (password !== cpassword) {
                    jQuery("#reset_password").after('<p style="color:red;margin: 10px 0 0 0;" class="error"><?php echo addslashes(__('password not match.', 'wpdating')); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                else {
                    let data = {
                        'action'     : 'dsp_change_password',
                        'user_id'    : userID,
                        'password'   : password,
                        'ajax_nonce' : "<?php echo wp_create_nonce("change-password-ajax-nonce") ?>"
                    };
                    jQuery.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        method : 'POST',
                        data: data,
                        cache: false,
                        dataType: 'JSON',
                        beforeSend :  xhr => {
                            jQuery("#loading_reset").show();
                            jQuery('#reset_password').attr('disabled', true);
                        },
                        success: function(html) {
                            if (jQuery.trim(html['output']) === 'success') {
                                jQuery("#reset_password").after('<p style="color:green;margin: 10px 0 0 0;" class="notification"><?php echo addslashes(__('Password has been changed successfully.', 'wpdating')); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }else{
                                jQuery("#reset_password").after('<p style="color:red;margin: 10px 0 0 0;" class="notification"><?php echo addslashes(__('Alert Email Notifications-> We can&rsquo;t send you a reset password because your notification setting for reset password is selected NO.', 'wpdating')); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }
                        },
                        complete : ( xhr, status ) => {
                            jQuery("#loading_reset").hide();
                        }
                    });
                }
            }
            return false;
        });
    });

</script>