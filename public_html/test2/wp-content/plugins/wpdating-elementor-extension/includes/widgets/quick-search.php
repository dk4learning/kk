<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Quick_Search extends Widget_Base {

	public function get_name() {
		return 'wpee-quick-search';
	}

	public function get_title() {
		return __( 'Sidebar Search', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-site-search';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Quick Search', 'wpdating' ),
			]
		);

	}

	protected function render() {
		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-quick-search', 'class', 'wpee-quick-search' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-quick-search' ); ?>>
        	<div class="wpee-quick-search-wrap">			    
	    		<?php wpee_locate_template('profile/profile-content/parts/quick-search.php');?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Quick_Search() );
