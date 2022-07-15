<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Wpdating_Elementor_Extension_Helper_Functions;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Profile_Header extends Widget_Base {

	public function get_name() {
		return 'wpee-profile-header';
	}

	public function get_title() {
		return __( 'Profile Header', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-lock-user';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		
		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'Menu)', 'wpdating' ),
			]
		);
		$this->add_control(
            'profile_header_menu',
            [
                'label'             => esc_html__( 'Menu', 'wpdating' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
                'label_off'         => esc_html__( 'No', 'wpdating' ),
                'return_value' 		=> 'yes',                
            ]
        ); 
		$this->add_control(
            'profile_header_notification',
            [
                'label'             => esc_html__( 'Notification Blocks', 'wpdating' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
                'label_off'         => esc_html__( 'No', 'wpdating' ),
                'return_value' 		=> 'yes',                
            ]
        ); 
        $this->end_controls_section();

	}

	protected function render() {
		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-profile-header', 'class', 'wpee-profile-header' );

		$username = get_query_var('user_name');
		$username = (empty($username) && is_user_logged_in()) ? get_current_user_id() : $username;
		if( empty( $username ) && ! is_user_logged_in() ){
            $member_page_url = Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url();
            wp_redirect( $member_page_url );
			exit;
		}
		?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-profile-header' ); ?>>
        	<div class="wpee-profile-header <?php echo (isset($wpee_settings['profile_header_menu']) && $wpee_settings['profile_header_menu'] ) ? 'has-ph-menu' : 'no-ph-menu';?>">
		    		<?php wpee_locate_template('profile/header.php');?>	
		   </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Profile_Header() );
