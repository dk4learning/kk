<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       wpdating.com
 * @since      1.0.0
 *
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/includes
 * @author     WPDating <wpdating@gmail.com>
 */
class Wpdating_Newsfeed {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpdating_Newsfeed_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPDATING_NEWSFEED_VERSION' ) ) {
			$this->version = WPDATING_NEWSFEED_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpdating-newsfeed';
		$this->load_dependencies();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpdating_Newsfeed_Loader. Orchestrates the hooks of the plugin.
	 * - Wpdating_Newsfeed_i18n. Defines internationalization functionality.
	 * - Wpdating_Newsfeed_Admin. Defines all hooks for the admin area.
	 * - Wpdating_Newsfeed_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpdating-newsfeed-public.php';

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

        $plugin_public = new Wpdating_Newsfeed_Public();

		add_action( 'wp_news_feed_enqueue_styles', array($plugin_public, 'enqueue_styles'));
		add_action( 'wp_news_feed_enqueue_scripts', array($plugin_public, 'enqueue_scripts' ));
        add_action('wp_news_feed', array(&$plugin_public, 'news_feed'));
        add_action( "wp_ajax_fetch_news_feed", array(&$plugin_public, "ajax_fetch_news_feed"));
    }

}
