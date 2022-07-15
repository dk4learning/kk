<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Profile_Content extends Widget_Base {

	public function get_name() {
		return 'wpee-content';
	}

	public function get_title() {
		return __( 'Profile Content', 'wpdating' );
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
            'enable_menu',
            [
                'label'             => esc_html__( 'Menu', 'wpdating' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => '',
                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
                'label_off'         => esc_html__( 'No', 'wpdating' ),
                'return_value'      => 'yes',                
            ]
        ); 
        $this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Left Sidebar(Activity/Profile)', 'wpdating' ),
			]
		);
			$this->add_control(
	            'user_details',
	            [
	                'label'             => esc_html__( 'Show User Details', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => 'yes',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
			$this->add_control(
	            'photos',
	            [
	                'label'             => esc_html__( 'Show Photos', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => 'yes',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
			$this->add_control(
	            'friends',
	            [
	                'label'             => esc_html__( 'Show Friends', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => 'yes',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
			$this->add_control(
	            'quick_search',
	            [
	                'label'             => esc_html__( 'Show Quick search', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => '',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
        $this->end_controls_section();

		$this->start_controls_section(
			'section_rightsidebar',
			[
				'label' => __( 'Right Sidebar', 'wpdating' ),
			]
		);
			$this->add_control(
	            'meet_me',
	            [
	                'label'             => esc_html__( 'Show Meet Me', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => 'yes',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
			$this->add_control(
	            'online_members',
	            [
	                'label'             => esc_html__( 'Show Online Members', 'wpdating' ),
	                'type'              => Controls_Manager::SWITCHER,
	                'default'           => 'yes',
	                'label_on'          => esc_html__( 'Yes', 'wpdating' ),
	                'label_off'         => esc_html__( 'No', 'wpdating' ),
	                'return_value'      => 'yes',                
	            ]
	        ); 
        $this->end_controls_section();

	}

	protected function render() {
		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-profile-content', 'class', 'wpee-profile-content' );
        $left_sidebar = '';
        $right_sidebar = '';
        $class= '';
		$profile_tab = !empty( get_query_var('profile-tab') ) ? get_query_var( 'profile-tab' ) : 'profile';
		$check_photos_mode = wpee_get_setting('picture_gallery_module'); 
		$user_id = wpee_profile_id();
		$current_user_id = get_current_user_id();
		$check_my_friend_module = wpee_get_setting('my_friends');
		$check_virtual_gifts_mode = wpee_get_setting('virtual_gifts');
		$check_message_mode = wpee_get_setting('userplane_instant_messenger');
		// Below if condition is necessary in case of page not found and load profile as default page
		if( !empty( $profile_tab ) && $profile_tab == 'activity' && $user_id == $current_user_id ){
			$check_leftbar = true;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'media' && $check_photos_mode->setting_status == 'Y' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'message' && $check_message_mode->setting_status == 'Y' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'search' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'friends' && $check_my_friend_module->setting_status == 'Y'){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'edit-profile' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'gifts' && $check_virtual_gifts_mode->setting_status == 'Y' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'notifications' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'friend-request' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'settings' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'viewed' && $user_id == $current_user_id ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'reset_password' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'verify_user' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'view-details' ){
			$check_leftbar = false;
		}
		elseif( !empty( $profile_tab ) && $profile_tab == 'instant-chat' ){
            $check_leftbar = false;
        }
		else {
			$check_leftbar = true;
		}
        if( ( $wpee_settings['user_details'] != "yes" && $wpee_settings['photos'] != "yes" && $wpee_settings['friends'] != "yes" && $wpee_settings['quick_search'] != "yes" ) || !$check_leftbar ){
        	$class .= ' no-left-bar';
        }
        if( ($wpee_settings['meet_me'] != 'yes' && $wpee_settings['online_members'] != 'yes') ){
        	$class .= ' no-right-bar';
        }
        if( $wpee_settings['enable_menu'] ){
        	$class .= ' has-profile-menu';
        }

		?>

        <div <?php echo $this->get_render_attribute_string( 'wpee-profile-content' ); ?>>
			<div class="wpee-container">
	        	<div class="wpee-profile-content-wrap <?php echo esc_attr($class);?>">
		    		<?php wpee_locate_template('profile/content.php');?>	
			   </div>
			</div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Profile_Content() );