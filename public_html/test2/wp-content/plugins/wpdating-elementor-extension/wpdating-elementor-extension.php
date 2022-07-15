<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link               
 * @since             1.0.0
 * @package           Wpdating_Elementor_Extension
 *
 * @wordpress-plugin
 * Plugin Name:       WPDating Elementor Add-on
 * Plugin URI:        http://www.wpdating.com/
 * Description:       WordPress Elementor Add on by Digital Product Labs
 * Version:           2.2.0
 * Author:            Digital Product Labs, Inc.
 * Author URI:        http://www.wpdating.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpdating-elementor-extension
 * Domain Path:       /languages
 */

// error_reporting(E_ALL);
// ini_set('display_errors', 'On');

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPDATING_ELEMENTOR_EXTENSION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpdating-elementor-extension-activator.php
 */
function activate_wpdating_elementor_extension() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-elementor-extension-activator.php';
    Wpdating_Elementor_Extension_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpdating-elementor-extension-deactivator.php
 */
function deactivate_wpdating_elementor_extension() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-elementor-extension-deactivator.php';
    Wpdating_Elementor_Extension_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpdating_elementor_extension' );
register_deactivation_hook( __FILE__, 'deactivate_wpdating_elementor_extension' );

/**
 * Add plugin dependency
 */
add_action( 'admin_init', 'wpee_has_parent_plugin' );
function wpee_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'elemetor/elementor.php' ) &&  !is_plugin_active( 'dsp_dating/dsp_dating.php' ) ) {
        add_action( 'admin_notices', 'wpee_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function wpee_notice(){
    ?><div class="error"><p><?php esc_html_e( "Sorry, but WPDating Elementor Extension requires the Elementor and WP Dating to be installed and active.", "wpdating-elementor-extension");?></p></div><?php
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( is_plugin_active('dsp_dating/dsp_dating.php') &&  is_plugin_active('elementor/elementor.php')){
    require plugin_dir_path( __FILE__ ) . 'includes/class-wpdating-elementor-extension.php';

}
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpdating_elementor_extension() {

    if( is_plugin_active('dsp_dating/dsp_dating.php') &&  is_plugin_active('elementor/elementor.php')){
        $plugin = new Wpdating_Elementor_Extension();
        $plugin->run();
    }
}
run_wpdating_elementor_extension();?>