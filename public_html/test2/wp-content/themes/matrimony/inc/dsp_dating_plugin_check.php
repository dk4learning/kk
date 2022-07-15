<?php 

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( !is_plugin_active('dsp_dating/dsp_dating.php' ) ) {

	

	$default_theme = wp_get_themes();

	$default_themes = array_keys($default_theme);

	$default_theme_name = $default_themes[0];

	$default_theme_name_to_be_used  = '';

	if ($default_theme_name == 'WPDATING') {

		$default_theme_name_to_be_used = $default_themes[1];

	}

	else {

		$default_theme_name_to_be_used = $default_theme_name;

	}



	$load_default_theme = wp_get_theme($default_theme_name_to_be_used);

	

		if ($load_default_theme->exists()) {

			switch_theme( $load_default_theme->get_stylesheet() );

		    add_action( 'admin_notices', 'wp_dating_plugin_not_active_notice' );

		}

		return;

} else {

	//do nothing

}



function wp_dating_plugin_not_active_notice() {

    ?>

    <div class="updated">

        <p><?php _e( 'You Need to Install the WordPress Dating Plugin First to Active this Theme!', 'wp-dating' ); ?></p>

    </div>

    <?php

}