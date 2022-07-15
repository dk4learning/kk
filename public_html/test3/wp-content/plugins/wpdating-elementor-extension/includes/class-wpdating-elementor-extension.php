<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
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
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
 * @author       < >
 */
class Wpdating_Elementor_Extension {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpdating_Elementor_Extension_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	protected $functions;

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
		if ( defined( 'WPDATING_ELEMENTOR_EXTENSION_VERSION' ) ) {
			$this->version = WPDATING_ELEMENTOR_EXTENSION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wpdating-elementor-extension';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_constant();
        $this->define_globals();
        add_action( 'init', array( $this, 'wpee_rewrites_init' ) );
		add_action( 'wp_footer', array( $this, 'wpee_registration_footer' ) );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpdating_Elementor_Extension_Loader. Orchestrates the hooks of the plugin.
	 * - Wpdating_Elementor_Extension_i18n. Defines internationalization functionality.
	 * - Wpdating_Elementor_Extension_Admin. Defines all hooks for the admin area.
	 * - Wpdating_Elementor_Extension_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpdating-elementor-extension-loader.php';

		/**
		 * Adding WP Dating elementor extension functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/wpdating-elementor-extension-functions.php';
		/**
		 * Adding WP Dating elementor extension functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/wpee-ajax-functions.php';
		/**
		 * Adding WP dating functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions/wpdating-functions.php';

		/**
		 * Adding elementor widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpdating-elementor-extension-widgets.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpdating-elementor-extension-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpdating-elementor-extension-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpdating-elementor-extension-public.php';
		/**
		 * Adding hooks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/hooks/hooks.php';
		/**
		 * Adding Shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/shortcodes/shortcodes.php';

        /**
         * The class responsible for defining all actions as helper functions
         */
        require_once plugin_dir_path( dirname( __FILE__) ) . 'includes/helpers/wpdating-elementor-extension-helper-functions.php';


		$this->loader = new Wpdating_Elementor_Extension_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpdating_Elementor_Extension_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpdating_Elementor_Extension_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wpdating_Elementor_Extension_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'wpee_add_dating_page_attribute_to_edit_page' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'wpee_save_dating_page_attribute' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpdating_Elementor_Extension_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wpdating_Elementor_Extension_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

    /**
     * Define the global variable for this plugin.
     *
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_globals() {

        global $wpee_general_settings, $wpee_genders;

        $wpee_general_settings = $this->general_settings_data();
        $wpee_genders          = $this->genders_data( $wpee_general_settings );
    }

    /**
     * Return the general settings data
     *
     *
     * @since    1.6.0
     * @access   public
     */
    public function general_settings_data() {
        global $wpdb;
        $general_settings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}dsp_general_settings" );

        $wpee_general_settings = new stdClass();
        foreach ( $general_settings as $general_setting ) {
            $object_values = new stdClass();
            $object_values->name   = $general_setting->setting_name;
            $object_values->status = $general_setting->setting_status;
            $object_values->value  = $general_setting->setting_value;

            $wpee_general_settings->{$general_setting->setting_name} = $object_values;
        }

        return $wpee_general_settings;
    }

    /**
     * Return the genders data
     *
     *
     * @param stdClass $general_settings
     * @return stdClass
     * @since    1.6.0
     * @access   public
     */
    public function genders_data( $general_settings ) {
        global $wpdb;

        $sql_query = "SELECT gender, enum FROM {$wpdb->prefix}dsp_gender_list";

        $conditions = [];
        if ( $general_settings->male->value == 'N' ) {
            $conditions[] = "enum != 'M'";
        }

        if ( $general_settings->female->value == 'N' ) {
            $conditions[] = "enum != 'F'";
        }

        if ( $general_settings->couples->value == 'N' ) {
            $conditions[] = "enum != 'C'";
        }

        if ( count( $conditions ) > 0 ) {
            $sql_query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $genders = $wpdb->get_results( $sql_query );

        $gender_objects = new stdClass();
        foreach ( $genders as $gender ) {
            $gender_objects->{$gender->enum} = __( addslashes($gender->gender), 'wpdating' );
        }
        return $gender_objects;
    }

	/**
	 * Define constants
	 *
	 * @since     1.0.0
	 */
	public function define_constant() {
        defined('WPEE_PATH') or define('WPEE_PATH',plugin_dir_path(__DIR__));
        defined('WPEE_PLUGIN_VERSION') or define('WPEE_PLUGIN_VERSION','1.0.0');
		defined('WPEE_CSS_URL') or define('WPEE_CSS_URL', plugins_url( 'public/css', __DIR__) );
        defined('WPEE_JS_URL') or define('WPEE_JS_URL', plugins_url( 'public/js', __DIR__));
        defined('WPEE_IMG_URL') or define('WPEE_IMG_URL', plugins_url( 'public/images', __DIR__));
        $uploads = wp_upload_dir();
        defined('WPEE_UPLOAD_URL') or define('WPEE_UPLOAD_URL', $uploads['url']);
        
	}

    /**
     * Add query_vars for profile
     * @param $query_vars
     * @return mixed
     * @since 1.0.0
     */
	public function wpee_query_vars( $query_vars ) {
        $query_vars[] = 'lang';
        $query_vars[] = 'user_name';
	    $query_vars[] = 'profile-tab';
	    $query_vars[] = 'profile-subtab';
	    return $query_vars;
	}
	/**
	 * Add rule for profile
	 * @since 1.0.0
	 */
    public function wpee_rewrites_init() {
        $profile_pages = get_option('wpee_profile_page', '');

        if( !empty( $profile_pages ) ){
            add_filter( 'query_vars', array( $this, 'wpee_query_vars' ) );

            foreach ( $profile_pages as $profile_page ) {
                $slug = $profile_page;

                add_rewrite_tag('%'.$slug.'%', '([^&]+)', 'user_name=');

                add_rewrite_rule('^([a-zA-Z]{2,3}/)?'.$slug.'/([^/]*)/([^/]*)/([^/]*)/page/([0-9]{1,})/?','index.php?lang=$matches[1]&pagename='.$slug.'&user_name=$matches[2]&profile-tab=$matches[3]&profile-subtab=$matches[4]&paged=$matches[5]','top');
                add_rewrite_rule('^([a-zA-Z]{2,3}/)?'.$slug.'/([^/]*)/([^/]*)/([^/]*)/?','index.php?lang=$matches[1]&pagename='.$slug.'&user_name=$matches[2]&profile-tab=$matches[3]&profile-subtab=$matches[4]','top');
                add_rewrite_rule('^([a-zA-Z]{2,3}/)?'.$slug.'/([^/]*)/([^/]*)/?','index.php?lang=$matches[1]&pagename='.$slug.'&user_name=$matches[2]&profile-tab=$matches[3]','top');
                add_rewrite_rule('^([a-zA-Z]{2,3}/)?'.$slug.'/([^/]*)/?','index.php?lang=$matches[1]&pagename='.$slug.'&user_name=$matches[2]','top');

            }
            flush_rewrite_rules();
        }
    }

	/*
	public Function to add registration popup in footer
	*/
	public function wpee_registration_footer( ) { 
		if( is_user_logged_in()){
			return false;
		} ?>
        <div class='wpee-register-form popup'>
            <div class="wpee-register-form-wrap">               
                <?php wpee_locate_template('auth/login-register-pop-up.php');?>
            </div>
        </div>
        <?php
	}

	/*
	public Function to add template in header
	Code in this template submits form.
	This code should be in upgrade-account page but it will load half and submit form
	so to remove loading issue, it is add in wp_head action
	*/
	public function wpee_load_template( ) { 
	
		$profile_subtab = get_query_var( 'profile-subtab' );
		if( $profile_subtab == 'dsp_paypal'){
		    $action = isset($_POST['action']) ? $_POST['action'] : '';
		    $discountStatus      = get('discountStatus');
		    $isDiscountModuleOff = dsp_check_discount_code_setting();
		    if ((isset($action) && ! empty($action)) || $isDiscountModuleOff) {
		        $_GET['action'] = 'process';
		    }
		    wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/gateway/paypal/paypal.php");
		}	
	}

}