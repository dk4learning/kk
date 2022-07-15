<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Testimonials extends Widget_Base {

	public function get_name() {
		return 'wpee-testimonials';
	}

	public function get_title() {
		return __( 'Testimonials', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-testimonial';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Testimonials', 'wpdating' ),
			]
		);
		$this->add_control(
            'no_of_posts',
            [
                'label'             => esc_html__( 'No. of Post', 'ut-elementor-addons-lite' ),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 4,
            ]
        );

	}

	protected function render() {
		$wpee_settings = $this->get_settings();
		$post_num = isset( $wpee_settings['no_of_posts'] ) ? $wpee_settings['no_of_posts'] : 10;
        $this->add_render_attribute( 'wpee-testimonials', 'class', 'wpee-testimonials' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-testimonials' ); ?>>
        	<div class="wpee-testimonials-wrap">			    
	    		<?php 
				$slider_args  = array(
					'post_type'      => 'testimonials',
					'posts_per_page' => $post_num
				);

				$slider_query = new \WP_Query( $slider_args );
				$result = '<section class = "variable slider testimonials_section">';
				if($slider_query->have_posts()) :
			 
			        while($slider_query->have_posts()) :
			 
			        $slider_query->the_post() ;
			        
			        $result .= '<div class="testimonials-wrap text-center">';
			        $result .= '<h5 class="title">' . get_the_title() . '</h5>';

			        $result .= '<div class="desc-wrap">';
			        $result .= '<p>' . get_the_content() . '</p>';
			        $result .= '</div>';

			        $result .= '<div class="user-detail-wrap d-flex align-center justify-center">';

			        $thumbnail = get_the_post_thumbnail();
			        if ($thumbnail) {
			        	$result .= '<figure>' . get_the_post_thumbnail() . '</figure>';
			        }


			        $client_name     = get_post_meta( get_the_ID(), 'client_name', true );
			        $client_position = get_post_meta( get_the_ID(), 'client_name', true );

			        if ($client_name || $client_position) {
			        	$result .= '<div class="user-detail-content text-left">';
			        	if ($client_name) {
			        		$result .= '<p class="name heading-font">' . get_post_meta( get_the_ID(), 'client_name', true );'</p>';
			        	}

			        	if ($client_position) {
			        		$result .= '<p class="position">' . get_post_meta( get_the_ID(), 'position', true );'</p>';
			        	}
			        	$result .= '</div>';
			        }


			        $result .= '</div>';

			        $result .= '</div>';
			 
			        endwhile;
			 
			        wp_reset_postdata();
			 
			    endif; 
				$result .= '</section>';

				echo $result;    ?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Testimonials() );
