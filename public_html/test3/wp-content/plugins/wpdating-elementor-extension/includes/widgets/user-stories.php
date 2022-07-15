<?php
namespace Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wpdating_Elementor_Extension_User_Stories extends Widget_Base {

	public function get_name() {
		return 'wpee-user-stories';
	}

	public function get_title() {
		return __( 'User Stories', 'wpdating' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return [ 'wpdating' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'User Stories', 'wpdating' ),
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
		$this->add_control(
			'highlight',
			[
				'label' => esc_html__( 'Highlight 1st', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'your-plugin' ),
				'label_off' => esc_html__( 'No', 'your-plugin' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

	}

	protected function render() {
		$wpee_settings = $this->get_settings();
		$post_num = isset( $wpee_settings['no_of_posts'] ) ? $wpee_settings['no_of_posts'] : 10;
        $this->add_render_attribute( 'wpee-user-stories', 'class', 'wpee-user-stories' ); ?>
        <div <?php echo $this->get_render_attribute_string( 'wpee-user-stories' ); ?>>
        	<div class="wpee-user-stories-wrap">
						    
	    		<?php					
					$user_stories  = array(
						'post_type'      => 'user-story',
						'posts_per_page' => $post_num
					);
					$stories = new \WP_Query( $user_stories );
					$result  = '<section class = "user-stories-wrap">';
					if($stories->have_posts()) :
						$i = 0;
						$j = 0;
						$total_post = $stories->found_post;
						while($stories->have_posts()) :
						$i++;
						$j++;
						if( $i == 1 ){
							$result .= '<div class="content-wrap d-flex">';
							if($wpee_settings['highlight']=='yes'){
								$result .= '<div class="content-wrap-inner left-wrap">';
							}
						}
						elseif( $i == 2 ){
							if($wpee_settings['highlight']=='yes'){
								$result .= '<div class="content-wrap-inner right-wrap">';
							}
						}
						$stories->the_post() ;
						$link = get_the_permalink();
						$date = get_the_date( 'M j, Y' );
						$result .= '<div class="user-story-wrap">';

						if ($i == 1) {
							$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'user-stories-big');

							if ($image) {
								list( $src, $width, $height ) = $image;
								$result .= "<figure class=\"img-holder\">
												<a href=\"{$link}\">
													<span class=\"" . ( $i == 1 ? 'thumb-big' : 'thumb' )  . "\" style=\"background:url('{$src}') no-repeat center/cover\"></span>
												</a>
											</figure>";
							}
						} else {
							$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'user-stories-small');

							if ($image) {
								list( $src, $width, $height ) = $image;
								$result .= "<figure class=\"img-holder\">
												<a href=\"{$link}\">
													<span class=\"thumb\" style=\"background:url('{$src}') no-repeat center/cover\"></span>
												</a>
											</figure>";
							}
						}

						$result .= '<div class="story-content-wrap">';
						$result .= '<h4 class="post-title"><a href="' . $link . '">' . get_the_title() . '</a></h4>';
						$result .= '<div class="date-wrap"> <i class="fa fa-clock-o" aria-hidden="true"></i>'. $date .'</div>';
						if( $i == 1 ){
							$result .= '<p class="desc">' . wp_trim_words( get_the_content(), 60 ) . '</p>';
							$result .= '<div class="link-wrap line-animate"><a class="read_more" href="' . $link . '"> Read More </a></div>';
						}
						$result .= '</div>';
						
						$result .= '</div>';
				
						if( $i == 1 ||  $i == 5 ){ // closing for both div
							if($wpee_settings['highlight']=='yes'){
								$result .= '</div>';
							}
						}
						if( $i == 5 || $j == $total_post ){
							if($wpee_settings['highlight']=='yes'){
								$result .= '</div>';
							}
						}
						if( $i == 5 ){
							$i = 0;
						}
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

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpdating_Elementor_Extension_User_Stories() );
