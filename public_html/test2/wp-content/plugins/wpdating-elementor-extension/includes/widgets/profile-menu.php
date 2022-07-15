<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Profile_Menu extends Widget_Base {

	public function get_name() {
		return 'wpee-profile-menu';
	}

	public function get_title() {
		return __( 'Profile Menu', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-nav-menu';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Profile Menu', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-profile-menu', 'class', 'wpee-profile-menu' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-profile-menu' ); ?>>
        	<div class="wpee-profile-menu-wrap">			    
	    		<?php wpee_locate_template('profile/profile-content/parts/menu.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Profile_Menu() );