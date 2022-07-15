<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/public
 * @author       < >
 */
class Wpdating_Elementor_Extension_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdating_Elementor_Extension_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdating_Elementor_Extension_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpdating-elementor-extension-public.css', array(), $this->version, 'all' );
        wp_enqueue_style('wpee-toastr', '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css', array(), '', 'all');

    }

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdating_Elementor_Extension_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdating_Elementor_Extension_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpdating-elementor-extension-public.js', array( 'jquery','isotope','imagesloaded' ), $this->version, true );
		$eleObj['wpdate_url'] = WPDATE_URL;
		$eleObj['ajax_url']   = admin_url('admin-ajax.php');
        if ( function_exists( 'pll_get_post_language' ) ) {
            global $post;
            $eleObj['lang'] = pll_get_post_language( $post->ID, 'slug' );
        } else {
            $eleObj['lang'] = '';
        }
        wp_localize_script( $this->plugin_name, 'wpee_elementor_js_object', $eleObj );
        wp_register_script( 'wpee-custom-elementor', plugin_dir_url( __FILE__ ) . 'js/custom-elementor.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( 'wpee-custom-elementor', 'eleObj', $eleObj );	
		wp_enqueue_script( 'wpee-custom-elementor' );
        wp_enqueue_script( 'wpee-relish-home-page-user-stories', plugin_dir_url( __FILE__ ) . 'js/home-page-user-stories.js', array( 'jquery' ), $this->version, true );
        wp_register_script( 'isotope', plugin_dir_url( __FILE__ ) . 'js/isotope.pkgd.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'imagesloaded', plugin_dir_url( __FILE__ ) . 'js/imagesloaded.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'wpee-toastr', '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', array( 'jquery' ), $this->version, true );
    }

}
