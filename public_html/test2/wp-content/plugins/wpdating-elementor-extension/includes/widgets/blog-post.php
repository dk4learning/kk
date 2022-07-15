<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_Blog_Post extends Widget_Base {

	public function get_name() {
		return 'wpee-blog-post';
	}

	public function get_title() {
		return __( 'Blog Posts', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Blog Posts', 'wpdating' ),
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
        $this->add_render_attribute( 'wpee-blog-post', 'class', 'wpee-blog-post' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-blog-post' ); ?>>
        	<div class="wpee-blog-post-wrap">			    
	    		<?php 
	    		$user_stories  = array(
					'post_type'      => 'blog-post',
					'posts_per_page' => 3
				);

				$stories = new \WP_Query( $user_stories );
				$result  = '<section class = "blog-list-wrapper mb-0">';
				$result  .= '<div class="content-wrap d-flex col-3">';
				if($stories->have_posts()) :
			 
			        while($stories->have_posts()) :
			 
			        $stories->the_post() ;
			        $link = get_the_permalink();
					$date = get_the_date( 'M j, Y' );
			        $result .= '<div class="blog-card-wrap">';
			        $result .= '<figure class="img-holder"><a href="' . $link . '">' . get_the_post_thumbnail( get_the_ID(), 'thumb-medium-square',array( 'alt' => get_the_title())) . '</a></figure>';
			        $result .= '<div class="blog-content-wrap">';
			        $result .= '<h4 class="post-title"><a href="' . $link . '">' . get_the_title() . '</a></h4>';
			        $result .= '<div class="date-wrap"> <i class="fa fa-clock-o" aria-hidden="true"></i>'. $date .'</div>';
			        $result .= '</div>';
			        
			        $result .= '</div>';
			 
			        endwhile;
			 
			        wp_reset_postdata();
			 
			    endif;
			    $result .= '</div>';
				$result .= '</section>';

				echo $result;    ?>	 
		    </div>
		</div>
		<?php
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_Blog_Post() );
