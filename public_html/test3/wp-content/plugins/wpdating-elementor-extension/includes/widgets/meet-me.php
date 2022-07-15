<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Meet_ME extends Widget_Base {

	public function get_name() {
		return 'wpee-meet-me';
	}

	public function get_title() {
		return __( 'Meet Me', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Meet Me', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-meet-me', 'class', 'wpee-meet-me' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-meet-me' ); ?>>
        	<div class="wpee-meet-me-wrap">			    
	    		<?php wpee_locate_template('meet-me.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Meet_ME() );
