<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       wpdating.com
 * @since      1.0.0
 *
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/includes
 * @author     WPDating <wpdating@gmail.com>
 */
class Wpdating_Newsfeed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpdating-newsfeed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
