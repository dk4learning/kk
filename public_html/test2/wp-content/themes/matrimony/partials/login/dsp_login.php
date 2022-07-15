<?php
/*
=================================================
This is a Login Page For Dsp Dating Plugin.
=================================================
*/
?>
<div class="row">
    <div class="dsp-md-12 lavish_login_box">
        <h2 class="special_heading"><strong><?php echo language_code( 'DSP_LOGIN' ); ?></strong></h2>
        <p><?php printf( language_code( '' ) . ' %s', '<a href="' . esc_url( get_bloginfo( 'url' ) ) . '/dsp_register">' . language_code( 'DSP_REGISTER' ) . '</a>' ); ?> </p>
        <hr/>
		<?php global $error;
		if ( isset( $error ) && $error != '' ) {
			echo '<br><span class="error">' . $error . '</span><br><br><br>';
		} ?>
        <!--Login Form Start Here -->
        <form name="login-form" method="post">


            <label for="user_login"><?php echo language_code( 'DSP_USERNAME' ); ?></label>
            <input type="text" name="user_login" class="dsp-form-control"
                   placeholder="<?php echo language_code( 'DSP_USERNAME' ); ?>"><br/>

            <label for="user_password"><?php echo language_code( 'DSP_PASSWORD' ); ?></label>
            <input type="password" name="user_password" class="dsp-form-control"
                   placeholder="<?php echo language_code( 'DSP_PASSWORD' ); ?>"><br/>

            <button type="submit" class="btn btn-primary"><?php echo language_code( 'DSP_SUBMIT_BUTTON' ); ?></button>

        </form>
        <p><?php printf(language_code('') . ' %s',
                '<a href="' . esc_url(get_bloginfo('url')) . '/members/lost_password">' . language_code('DSP_FORGOT_PASSWORD') . '</a>'); ?> </p>
	    <?php
	    do_action( 'wpdating_facebook_login' );
	    ?>
    </div>
    <!--End of Login Form-->
</div>
</div>