<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Online_Members extends Widget_Base {

	public function get_name() {
		return 'wpee-online-members';
	}

	public function get_title() {
		return __( 'Online Members', 'wpdating' );
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
				'label' => __( 'Online Members', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-online-members', 'class', 'wpee-online-members' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-online-members' ); ?>>
        	<div class="wpee-online-members-wrap">			    
	    		<?php wpee_locate_template('online-members.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Online_Members() );
