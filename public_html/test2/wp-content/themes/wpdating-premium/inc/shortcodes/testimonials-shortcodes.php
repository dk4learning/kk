<?php
//
function premium_testimonials() {
	$args = array(
	'labels' => array(
	'name' =>__('Testimonials'),
	'singular_name' =>__('Testimonials'),
	'all_items' =>__('All Testimonials'),
	'add_new_item' =>__('Add New Testimonial'),
	'edit_item' =>__('Edit Testimonial'),
	'view_item' =>__('View Testimonial')
	),
	'public' =>true,
	'has_archive' =>true,
	'rewrite' =>array('slug' =>'testimonials'),
	'show_ui' =>true,
	'show_in_menu' =>true,
	'show_in_nav_menus' =>true,
	'capability_type' =>'page',
	'supports' =>array('title', 'editor', 'thumbnail'),
	'exclude_from_search' =>true,
	'menu_position' =>80,
	'menu_icon' =>'dashicons-format-status'
	);
	register_post_type('testimonials', $args);
	}
	add_action( 'init', 'premium_testimonials');

	function premium_testimonials_meta_box(){
		add_meta_box( 'testimonial-details', 'Testimonial Details', 'my_meta_box_cb', 'testimonials', 'normal', 'default');
		}
		function my_meta_box_cb($post){
		$values = get_post_custom( $post-> ID );
		$client_name = isset( $values['client_name'] ) ? esc_attr( $values['client_name'][0] ) : "";
		$position = isset( $values['position'] ) ? esc_attr( $values['position'][0] ) : "";
		wp_nonce_field( 'testimonial_details_nonce_action', 'testimonial_details_nonce' );
		$html = '';
		$html .= '<label>Client Name</label>';
		$html .= '<input id="client_name" style="margin-top: 15px; margin-left: 9px; margin-bottom: 10px;" name="client_name" type="text" value="'. $client_name .'" />
		';
		$html .= '<label>position</label>';
		$html .= '<input id="position" style="margin-left: 25px; margin-bottom: 15px;" name="position" type="text" value="'. $position .'" />';
		echo $html;
		}
		function my_save_meta_box($post_id){
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		// if our nonce isn't there, or we can't verify it, bail
		if( !isset( $_POST['testimonial_details_nonce'] ) || !wp_verify_nonce( $_POST['testimonial_details_nonce'], 'testimonial_details_nonce_action' ) ) return;
		// if our current user can't edit this post, bail
		if( !current_user_can( 'edit_post' ) ) return;
		if(isset( $_POST['client_name'] ) )
		update_post_meta( $post_id, 'client_name', $_POST['client_name']);
		if(isset( $_POST['position'] ) )
		update_post_meta( $post_id, 'position', $_POST['position']);
		}
		add_action( 'add_meta_boxes', 'premium_testimonials_meta_box' );
		add_action( 'save_post', 'my_save_meta_box' );

		
// Add Shortcode
function testimonials_shortcodes() {
	$slider_args  = array(
		'post_type'      => 'testimonials',
		'posts_per_page' => 10
	);

	$slider_query = new WP_Query( $slider_args );
	$result .= '<section class = "variable slider testimonials_section">';
	if($slider_query->have_posts()) :
 
        while($slider_query->have_posts()) :
 
        $slider_query->the_post() ;
        
        $result .= '<div class="testimonials-wrap text-center">';
        $result .= '<h5 class="title">' . get_the_title() . '</h5>';

        $result .= '<div class="desc-wrap">';
        $result .= '<p>' . get_the_content() . '</p>';
        $result .= '</div>';

        $result .= '<div class="user-detail-wrap d-flex align-center justify-center">';
        $result .= '<figure>' . get_the_post_thumbnail() . '</figure>';
        $result .= '<div class="user-detail-content text-left">';
        $result .= '<p class="name heading-font">' . get_post_meta( get_the_ID(), 'client_name', true );'</p>';
        $result .= '<p class="position">' . get_post_meta( get_the_ID(), 'position', true );'</p>';

        $result .= '</div>';
        $result .= '</div>';

        $result .= '</div>';
 
        endwhile;
 
        wp_reset_postdata();
 
    endif; 
	$result .= '</section>';

	return $result;    
}
add_shortcode( 'premium_testimonials', 'testimonials_shortcodes' );


?>