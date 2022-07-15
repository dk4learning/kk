<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Friend_List extends Widget_Base {

	public function get_name() {
		return 'wpee-friend-list';
	}

	public function get_title() {
		return __( 'Friends', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-person';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Photos List', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-friend-list', 'class', 'wpee-friend-list' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-friend-list' ); ?>>
        	<div class="wpee-friend-list-wrap">			    
	    		<?php wpee_locate_template('profile/profile-content/parts/friends-list.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Friend_List() );
