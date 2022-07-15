<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Notification_Block extends Widget_Base {

	public function get_name() {
		return 'wpee-notification-block';
	}

	public function get_title() {
		return __( 'Notification Block', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Notification Block', 'wpdating' ),
			]
		);

	}

	protected function render() {

		global $wpee_settings;
		$wpee_settings = $this->get_settings();
        $this->add_render_attribute( 'wpee-notification-block', 'class', 'wpee-notification-block' ); 
        if( is_user_logged_in() ):
	        ?>
	        <div <?php echo $this->get_render_attribute_string( 'wpee-notification-block' ); ?>>
	        	<div class="wpee-notification-block-wrap">			    
		    		<?php wpee_locate_template('profile/notification-blocks/notification-blocks.php');?>	 
			    </div>
			</div>
			<?php
		endif;
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Notification_Block() );
