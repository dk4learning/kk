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

class Wpdating_Elementor_Extension_Search_Block extends Widget_Base {

	public function get_name() {
		return 'wpee-search';
	}

	public function get_title() {
		return __( 'Search Block', 'wpdating' );
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
				'label' => __( 'Search', 'wpdating' ),
			]
		);

  //       $this->add_control(
  //           'placeholder', [
  //               'label' => esc_html__( 'Placeholder', 'wpdating' ),
  //               'type' => Controls_Manager::TEXT,
  //               'default' => 'Search ...',
  //               'label_block' => true,
  //           ]
  //       );


	}

	protected function render() {

		$settings = $this->get_settings();
		global $wpdb;
		//For min and max age
		$check_min_age = wpee_get_setting('min_age');
		$min_age_value = $check_min_age->setting_value;
		$check_max_age = wpee_get_setting('max_age');
		$max_age_value = $check_max_age->setting_value;
		$check_near_me = wpee_get_setting('near_me');
        $this->add_render_attribute( 'wpee-search-block', 'class', 'wpee-search-block' );?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-search-block' ); ?>>
        	<div class="wpee-search-tab">
			    <div class="search-tab">
		            <h4 data-content="basic-search" class="search-tab-title active"><?php echo __('Basic Search', 'wpdating'); ?></h4>
		            <h4 data-content="advance-search" class="search-tab-title"><?php echo __('Advanced Search', 'wpdating'); ?></h4>
			        <?php ///////////////////////////////////// Near Me ///////////////////////////////////////////////////?>
			        <?php if ($check_near_me->setting_status == 'Y') { ?>
			            <h4 data-content="near-me" class="search-tab-title"><?php echo __('Near Me', 'wpdating'); ?></h4>
			        <?php } ?>
			    </div>
			    <div class="tab-content-wrapper">
			    	<div class="tab-content-list basic-search">
			    		<?php wpee_locate_template('search/basic_search.php');?>	    		
			    	</div>
			    	<div class="tab-content-list advance-search" style="display: none;">
			    		<?php wpee_locate_template('search/advance_search.php');?>	    		
			    	</div>
			        <?php if ($check_near_me->setting_status == 'Y') { ?>
				    	<div class="tab-content-list near-me" style="display: none;">
				    		<?php wpee_locate_template('search/near_me.php');?>	    		
				    	</div>
			        <?php } ?>
			    </div>
		   </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Search_Block() );
