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

class Wpdating_Elementor_Extension_Member_List_Tab extends Widget_Base {

	public function get_name() {
		return 'wpee-member-list-tab';
	}

	public function get_title() {
		return __( 'Member List Tab', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}
	public function get_style_depends()
    {
        return [
            'isotope',
            'imagesloaded'
        ];
    }

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Member List Tab', 'wpdating' ),
			]
		);

	}

	protected function render() {

		$settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-member-list-tab', 'class', 'wpee-member-list-tab' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-member-list-tab' ); ?>>
        	<div class="wpee-member-list-tab-wrapper">        		
	    		<?php wpee_locate_template('member/member_list_tab.php');?>
		   	</div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Member_List_Tab() );
