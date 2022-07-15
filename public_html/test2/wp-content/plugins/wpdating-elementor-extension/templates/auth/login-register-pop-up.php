
<div class="wpee-lr-tab">
    <h4 data-id="lr-login" class="wpee-lr-tab-title login-tab-trigger active"><?php echo __( 'Login', 'wpdating' ); ?></h4>
    <h4 data-id="lr-register" class="wpee-lr-tab-title login-tab-trigger"><?php echo __( 'Register', 'wpdating'); ?></h4>
</div>
<div class="wpee-lr-tab-content-wrap" data-tab="lr-login">
    <div class="wpee-lr-content-tab lr-forget">
        <div class="lost-password-page">
            <p class="dspdp-font-2x">
                <strong>
                    <?php echo __( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'wpdating' ); ?>
                </strong>
            </p>
            <div class="box-email-datails">
                <form method="post" class="dspdp-form-inline">
                    <div class="form-group">
                        <label for="user_n_email"><?php echo __('Username or Email', 'wpdating'); ?></label>
                        <input type="text" id="user_n_email" class="form-control" />
                        <input type="submit" class="dspdp-btn dspdp-btn-default" id="get-password" value="<?php echo __('Get Password', 'wpdating'); ?>" />
                        <img id="loading_reset" src="<?php echo WPDATE_URL . '/images/loading.gif' ?>" alt="Loading" />
                        <a class="fpw-link bttn login-tab-trigger" data-id="lr-login" rel="nofollow" data-tab-content="login"><?php esc_html_e('Back to login','wpdating');?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        require_once 'login-form.php';
        require_once 'register-form.php';
    ?>

</div>
