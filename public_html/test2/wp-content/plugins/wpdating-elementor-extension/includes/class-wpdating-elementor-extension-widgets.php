<?php

/**
 * Load Elementor widgets
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
 */

class Wpdating_Elementor_Extension_Widgets {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {		
        add_action( 'elementor/init', [ $this, 'wpee_widget_categories' ] );
        // add_action( 'elementor/frontend/after_register_scripts',array($this,'register_frontend_assets') );
        // add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_frontend_style' ] );
        add_action('elementor/widgets/widgets_registered',array($this,'wpee_register'));
	}

	/**
	 * Add category in elementor panel
	 *
	 * Function to add wpdating category in elementor panel
	 *
	 * @since    1.0.0
	 */

    function wpee_widget_categories() {

        $groups = array(
            'wpdating'  => esc_html__( 'WP Dating', 'wpdating' )
        );

        foreach ( $groups as $key => $value )
        {
            \Elementor\Plugin::$instance->elements_manager->add_category( $key, [ 'title' => $value ], 1 );
        }

    }

    /**
	 * Register elementor widgets
	 *
	 * Register all the elementor widgets
	 *
	 * @since    1.0.0
	 */
    function wpee_register() {
        global $post, $wpee_general_settings;
        $member_pages  = get_option( 'wpee_member_page' );
        $profile_pages = get_option( 'wpee_profile_page' );

        if ( in_array( $post->post_name, $member_pages ) ) {
            require_once plugin_dir_path(__FILE__).'/widgets/member-list.php';
        } else if ( in_array( $post->post_name, $profile_pages ) ) {
            require_once plugin_dir_path(__FILE__).'/widgets/profile.php';
            require_once plugin_dir_path(__FILE__).'/widgets/profile-content.php';
            require_once plugin_dir_path(__FILE__).'/widgets/user-details.php';
            require_once plugin_dir_path(__FILE__).'/widgets/friends-list.php';
            require_once plugin_dir_path(__FILE__).'/widgets/photos-list.php';
            require_once plugin_dir_path(__FILE__).'/widgets/profile-menu.php';
            require_once plugin_dir_path(__FILE__).'/widgets/notification-blocks.php';
            require_once plugin_dir_path(__FILE__).'/widgets/search.php';
        }

        require_once plugin_dir_path(__FILE__).'/widgets/quick-search.php';
        require_once plugin_dir_path(__FILE__).'/widgets/small-quick-search.php';
        require_once plugin_dir_path(__FILE__).'/widgets/member-list-tab.php';
        require_once plugin_dir_path(__FILE__).'/widgets/online-members.php';

        if( $wpee_general_settings->meet_me->status == 'Y' ){
            require_once plugin_dir_path(__FILE__).'/widgets/meet-me.php';
        }

        // Widgets for Theme
        require_once plugin_dir_path(__FILE__).'/widgets/testimonials.php';  
        require_once plugin_dir_path(__FILE__).'/widgets/blog-post.php'; 
        require_once plugin_dir_path(__FILE__).'/widgets/user-stories.php';    
    }

}
$widgets = new Wpdating_Elementor_Extension_Widgets();