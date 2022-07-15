<?php
/**
 * BlankPress Theme Framework 1.3
 * Date Updated: 2014-3-24
 * Author: Dharma Poudel (@rogercomred)
 */

include_once('inc/dsp_dating_plugin_check.php');    // Checked the Plugin Activated or Not
include_once('inc/blankpress-defaults.php');    // defines defaults and constants
include_once('admin/blankpress-admin.php');    // adds the theme options
include_once('inc/blankpress-setup.php');        // initializes the theme
include_once('inc/blankpress-enqueue.php');        // enqueues styles and scripts
include_once('inc/blankpress-register.php');    // registers widgets and menus
include_once('inc/blankpress-cleanup.php');        // cleans some WordPress mess
include_once('inc/blankpress-slider.php');        // adds BlankPress slider
include_once('inc/blankpress-utilities.php');    // includes some utility functions
include_once('inc/blankpress-shortcodes.php');    // includes theme shortcodes
include_once('inc/blankpress-custompost.php');    // adds BlankPress custom post type
include_once('inc/blankpress-metaboxes.php');    // includes theme metaboxes
include_once('inc/blankpress-sidebars.php');    // includes theme metaboxes
include_once('inc/blankpress-plugin.php');    // includes theme Plugin functions
include_once('inc/blankpress-widget.php'); // includes theme widget
include_once('inc/blankpress-tables.php'); //includes the names of the tables
include_once('inc/custom-breadcums.php');    // Including the Custom breadcums
include_once('inc/theme-customizer.php');    // Including the Custom theme customizer
include_once('inc/theme-custom-widget.php');    // Including the Custom theme customizer
include_once('inc/dating_featured_members.php');
include_once('inc/dating_meet_me.php');

$plugin_file = ABSPATH . "wp-content/plugins/dsp_dating/dsp_dating.php";
$plugin_data = get_plugin_data($plugin_file, $markup = true, $translate = true);

$version = (int)str_replace(".", "", $plugin_data['Version']);
if ($version < 4841) {
    if (file_exists(WP_DSP_ABSPATH . '/incl/fb.php')) {
        include_once(WP_DSP_ABSPATH . '/incl/fb.php');
    }
} else {
    //dsp_debug(WP_DSP_ABSPATH . 'external-lib/fb/fb.php'));die;
    if (file_exists(WP_DSP_ABSPATH . '/external-lib/fb/fb.php')) {
        include_once(WP_DSP_ABSPATH . '/external-lib/fb/fb.php');
    }
}

/**
 * Hide admin bar to all users
 */
show_admin_bar(false);

/**
 * Dequeue User_section_style.css when wpdating theme is used
 */
if (!function_exists('dequeue_user_section_styles')) {
    function dequeue_user_section_styles()
    {
        wp_dequeue_style('dsp-user_section_styles');
    }
}

add_action('wp_enqueue_scripts', 'dequeue_user_section_styles');


    /**
   * Adds customizable styles to your <head>
    */
	function wpdating_theme_customize_css()
	{
    		get_template_part('inc/customizecss');

    }
add_action( 'wp_head', 'wpdating_theme_customize_css');

class Menu_With_Description extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth, $args) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
         
        $class_names = $value = '';
 
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
 
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
 
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
 
        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
 
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= '<br /><span class="sub">' . $item->description . '</span>';
        $item_output .= '</a>';
        $item_output .= $args->after;
 
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}



