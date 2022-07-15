<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Register_Form extends Widget_Base {

	public function get_name() {
		return 'wpee-register-form';
	}

	public function get_title() {
		return __( 'Register Form', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Register Form', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-register-form', 'class', 'wpee-register-form' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-register-form' ); ?>>
        	<div class="wpee-register-form-wrap">			    
	    		<?php wpee_locate_template('login-register-pop-up.php');?>
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Register_Form() );
