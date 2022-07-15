<?php
/*
=================================================
Functions For Lavish Date Theme 
These Function are the functional part of the theme
and changing theme might change the function of theme
Most of the parts used in theme are included from inc folder
@package Lavish Date
=================================================
*/
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_file = ABSPATH . "wp-content/plugins/dsp_dating/dsp_dating.php";
$plugin_data = get_plugin_data($plugin_file, $markup = true, $translate = true);
$version = (int) str_replace(".","",$plugin_data['Version']);
if($version < 4841){
	if(file_exists(WP_DSP_ABSPATH . '/incl/fb.php')){
		include_once(WP_DSP_ABSPATH . '/incl/fb.php');
	}
} else{
	if(file_exists(WP_DSP_ABSPATH . '/external-lib/fb/fb.php')){
		include_once(WP_DSP_ABSPATH . '/external-lib/fb/fb.php');
	}
}






/*
===================================================================
Check Whether the DSP Dating Plugin has been enabled or Not if 
not revert the theme to another theme, provide a notice to the user
that they must install DSP Dating Plugin To Install the Theme
===================================================================
*/
include_once('inc/dsp_dating_plugin_check.php');

/*
=================================================
Load the Menus Used in The Theme
=================================================
*/
include_once('inc/lavish_date_menus.php');
/*
=================================================
Enqueue All The Scripts and Js Required For the Theme
=================================================
*/
include_once('inc/lavish-date-scripts.php');

/*
=================================================
Include THe Post Excerpt Funtion to The USers
=================================================
*/
function lavish_date_excerpt( $length ) {
	return 50;
}
add_filter( 'excerpt_length', 'lavish_date_excerpt', 999 );
/*
=================================================
Include the Slider For the Theme
=================================================
*/
include_once('inc/lavish-date-slider.php');


include_once('inc/blankpress-defaults.php');	// defines defaults and constants
include_once('admin/blankpress-admin.php');	// adds the theme options
include_once('inc/blankpress-setup.php');		// initializes the theme

include_once('inc/blankpress-utilities.php');	// includes some utility functions
include_once('inc/blankpress-shortcodes.php');	// includes theme shortcodes
include_once('inc/blankpress-metaboxes.php');	// includes theme metaboxes
include_once('inc/blankpress-sidebars.php');	// includes theme metaboxes
include_once('inc/blankpress-plugin.php');	// includes theme Plugin functions
include_once('inc/blankpress-widget.php'); // includes theme widget
include_once('inc/blankpress-tables.php'); //includes the names of the tables
include_once('inc/custom-breadcums.php');	// Including the Custom breadcums
include_once('inc/theme-customizer.php');	// Including the Custom theme customizer
include_once('inc/lavish_date_widgets.php');	// Including the Custom theme customizer

    /**
    * Adds customizable styles to your <head>
    */
	function lavish_dating_theme_customize_css()
	{
		get_template_part('inc/customizecss');

	}
	add_action( 'wp_head', 'lavish_dating_theme_customize_css');

show_admin_bar(false);

/*
=================================================
Include The Lavish Login and Logout Page Functions
=================================================
*/
include_once('partials/lavish_login_logout_functions.php');


/*
=================================================
Include The Framework Which will gave users to 
show/hide Titles Create Faq Page, Create Testomonials
Page.
@ FRAMEWORK DEFINE
=================================================
*/
define('FRAMEWORK', get_template_directory().'/lavish-date');

include(FRAMEWORK.'/init.php');

$plugin_file = ABSPATH . "wp-content/plugins/dsp_dating/dsp_dating.php";
$plugin_data = get_plugin_data($plugin_file, $markup = true, $translate = true);
$version = (int) str_replace(".","",$plugin_data['Version']);
if($version < 4841){
 if(file_exists(WP_DSP_ABSPATH . '/incl/fb.php')){
  include_once(WP_DSP_ABSPATH . '/incl/fb.php');
 }
}else{
 //dsp_debug(WP_DSP_ABSPATH . 'external-lib/fb/fb.php'));die;
 if(file_exists(WP_DSP_ABSPATH . '/external-lib/fb/fb.php')){
  include_once(WP_DSP_ABSPATH . '/external-lib/fb/fb.php');
 }
}

/**
 * Hide admin bar to all users
 */
show_admin_bar(false);




