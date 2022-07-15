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

class Wpdating_Elementor_Extension_Member_List extends Widget_Base {

	public function get_name() {
		return 'wpee-member';
	}

	public function get_title() {
		return __( 'Member List', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Member List', 'wpdating' ),
			]
		);

	}

	protected function render() {

		$settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-member', 'class', 'wpee-member' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-member' ); ?>>
        	<div class="wpee-member-wrapper">        		
	    		<?php wpee_locate_template('member/member_list.php');?>
		   	</div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Member_List() );
