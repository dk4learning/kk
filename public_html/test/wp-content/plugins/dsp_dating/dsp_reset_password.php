<script>
    jQuery(document).ready(function(e) {
        jQuery("#reset_password").click(function() {
            jQuery(".notification,.error").remove();
            var keypass = jQuery("#pass").val();
            if (keypass == 0) {
                var user_id = jQuery("#user_id").val();
                var password = jQuery("#password").val();
                var cpassword = jQuery("#cpassword").val();
                if (jQuery.trim(password).length < 7) {
                    jQuery("#reset_password").after('<p style="display:none" class="error"><?php echo addslashes(__('The password should be at least seven characters long.', 'wpdating')); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                if (password != cpassword) {
                    jQuery("#reset_password").after('<p style="display:none" class="error"><?php echo addslashes(__('password not match.', 'wpdating')); ?></p>');
                    jQuery(".lost-password-page .error").slideDown();
                    return false;
                }
                else {
                    jQuery.ajax({
                        url: "<?php echo WPDATE_URL . "/dsp_change_password.php"; ?>?user_id=" + user_id + "&password=" + password,
                        cache: false,
                        dataType: 'json',
                        success: function(html) {
                            if (jQuery.trim(html['output']) == 1) {
                                jQuery("#reset_password").after('<p style="display:none" class="notification"><?php echo addslashes(__('Password has been changed successfully.', 'wpdating')); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }else{
                                jQuery("#reset_password").after('<p style="display:none" class="notification"><?php echo addslashes(__('Alert Email Notifications-> We can&rsquo;t send you a reset password beacause your notification setting for reset password is selected NO.', 'wpdating')); ?></p>');
                                jQuery(".lost-password-page .notification").slideDown();
                                jQuery("#password").val('');
                                jQuery("#cpassword").val('');
                            }
                        }

                    });
                }




            }
            return false;
        });
    });

</script>
<?php
extract($_REQUEST);
$data = explode(',', base64_decode($key));
$check_pass_changed = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_table` where user_pass like '" . $data[1] . "'");
?>
<div class="dsp_box-out f_left">
    <div class="dsp_box-in f_left">
        <div class="lost-password-page">
            <?php if ($check_pass_changed > 0) { ?><div style="margin-left:0px;" class="box-email-datails">
                    <strong><?php echo __('Enter your new password below.', 'wpdating'); ?></strong><br />
                    <form method="post" class="dspdp-form-inline">
                        <br/>
                        <label for="password">New Password</label>
                        <input type="password" class="dspdp-form-control dsp-form-control" id="password" /><br /><br />
                        <label for="cpassword">Confirm New Password</label>
                        <input type="password" class="dspdp-form-control dsp-form-control" id="cpassword" /><br /><br />
                        <input type="submit" class="dspdp-btn dspdp-btn-default" id="reset_password" value="<?php echo __('Reset Password', 'wpdating'); ?>" />                        <input type="hidden" id="pass" value="<?php
                        if (isset($key))
                            echo '0';
                        else
                            echo '1';
                        ?>" />
                        <input type="hidden" id="user_id" value="<?php echo $data[0]; ?>" />
                    </form>

                </div><br />
                <p class="hint"><?php echo __('Note: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like !', 'wpdating'); ?></p>
            <?php }else { ?>
                <p class="error"><?php echo __('Reset password link has been expired.', 'wpdating'); ?></p>
            <?php } ?></div>
    </div>
</div>                                    