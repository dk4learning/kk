<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_User_Details extends Widget_Base {

	public function get_name() {
		return 'wpee-user-details';
	}

	public function get_title() {
		return __( 'User Details', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'User details', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-user-details', 'class', 'wpee-user-details' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-user-details' ); ?>>
        	<div class="wpee-user-details-wrap">			    
	    		<?php wpee_locate_template('profile/profile-content/parts/user-details.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_User_Details() );
