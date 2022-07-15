<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/admin
 * @author       < >
 */
class Wpdating_Elementor_Extension_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpdating-elementor-extension-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpdating-elementor-extension-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add meta box section in create and edit page
     *
     * @since     1.6.0
     */
	public function wpee_add_dating_page_attribute_to_edit_page() {
        add_meta_box(
            'wpee-dating-page-attribute',
            __( 'WPDating Page Attribute', 'wpdating' ),
            array( $this, 'wpee_add_dating_page_attribute_to_edit_page_cb' ),
            'page',
            'side',
            'low'
        );
    }

    /**
     * Handle Callback of wpee_add_dating_page_attribute_to_edit_page
     *
     * @param $post
     * @since     1.6.0
     */
    public function wpee_add_dating_page_attribute_to_edit_page_cb( $post ) {
        $post_type = get_post_meta( $post->ID, 'wpee_dating_page_type', true );
        ?>
        <p>
            <strong><?php echo __( 'Page Type', 'wpdating' ); ?></strong>
        </p>
        <label class="screen-reader-text wpee-label" for="wpee_dating_page_type" style="font-weight: 600;">
            <?php echo __( 'Page Type', 'wpdating' ); ?>
        </label>
        <div>
            <select name="wpee_dating_page_type" id="wpee_dating_page_type" style="width: 60%;">
                <option value="default" <?php echo ( $post_type == 'default' ) ? "selected='selected'" : ''; ?>>
                    <?php echo __( 'Default', 'wpdating'); ?>
                </option>
                <option value="member" <?php echo ( $post_type == 'member' ) ? "selected='selected'" : ''; ?>>
                    <?php echo __( 'Member', 'wpdating'); ?>
                </option>
                <option value="profile" <?php echo ( $post_type == 'profile' ) ? "selected='selected'" : ''; ?>>
                    <?php echo __( 'Profile', 'wpdating'); ?>
                </option>
            </select>
        </div>
        <?php
    }

    /**
     * Handle the save post hook
     *
     * @param $post_id
     * @since     1.6.0
     */
    function wpee_save_dating_page_attribute( $post_id ) {
        if ( isset( $_POST['wpee_dating_page_type'] ) && ! empty( $_POST['wpee_dating_page_type'] ) ) {
            $wpee_dating_page_type = $_POST['wpee_dating_page_type'];

            update_post_meta( $post_id, 'wpee_dating_page_type', $wpee_dating_page_type );

            $members_pages = get_option( 'wpee_member_page' );
            $profile_pages = get_option( 'wpee_profile_page' );

            $post = get_post( $post_id );

            if ( function_exists( 'pll_get_post_language' ) ) {
                $lang = pll_get_post_language( $post->ID, 'locale' );
            } else {
                $lang = get_locale();
            }

            switch ( $wpee_dating_page_type ) {

                case 'member':
                    if ( ! in_array( $post->post_name , $members_pages ) ) {
                        $members_pages[$lang] = $post->post_name;
                        update_option( 'wpee_member_page', $members_pages );
                    }

                    if ( in_array( $post->post_name , $profile_pages ) ) {
                        $key = array_search( $post->post_name, $profile_pages );
                        unset( $profile_pages[$key] );
                        update_option( 'wpee_profile_page', $profile_pages );
                    }
                    break;

                case 'profile':
                    if ( ! in_array( $post->post_name , $profile_pages ) ) {
                        $profile_pages[$lang] = $post->post_name;
                        update_option( 'wpee_profile_page', $profile_pages );
                    }

                    if ( in_array( $post->post_name , $members_pages ) ) {
                        $key = array_search( $post->post_name, $members_pages );
                        unset( $members_pages[$key] );
                        update_option( 'wpee_member_page', $members_pages );
                    }
                    break;

                case 'default':
                    if ( in_array( $post->post_name , $members_pages ) ) {
                        $key = array_search( $post->post_name, $members_pages );
                        unset( $members_pages[$key] );
                        update_option( 'wpee_member_page', $members_pages );
                    }

                    if ( in_array( $post->post_name , $profile_pages ) ) {
                        $key = array_search( $post->post_name, $profile_pages );
                        unset( $profile_pages[$key] );
                        update_option( 'wpee_profile_page', $profile_pages );
                    }
            }
        }
    }
}