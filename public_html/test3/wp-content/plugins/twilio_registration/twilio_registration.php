<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link               https://www.wpdating.com/
 * @since             1.0.1
 * @package           Twilio_registration
 *
 * @wordpress-plugin
 * Plugin Name:       Twilio registration
 * Plugin URI:         https://www.wpdating.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.2
 * Author:             https://www.wpdating.com/
 * Author URI:         https://www.wpdating.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       twilio_registration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TWILIO_REGISTRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-twilio_registration-activator.php
 */
function activate_twilio_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twilio_registration-activator.php';
	Twilio_registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-twilio_registration-deactivator.php
 */
function deactivate_twilio_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-twilio_registration-deactivator.php';
	Twilio_registration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_twilio_registration' );
register_deactivation_hook( __FILE__, 'deactivate_twilio_registration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-twilio_registration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_twilio_registration() {

	$plugin = new Twilio_registration();
	$plugin->run();

}
run_twilio_registration();
