<?php 

// Blog Post section start

function premium_blog_post() {
	$args = array(
	'labels' => array(
	'name' =>__('Blog'),
	'singular_name' =>__('Blog'),
	'all_items' =>__('All Blog'),
	'add_new_item' =>__('Add New Blog'),
	'edit_item' =>__('Edit Blog'),
	'view_item' =>__('View Blog')
	),
	'public' =>true,
	'has_archive' =>false,
	'rewrite' =>array('slug' =>'blog-post'),
	'show_ui' =>true,
	'show_in_menu' =>true,
	'show_in_nav_menus' =>true,
	'taxonomies' => array( 'category', 'post_tag' ),
	'capability_type' =>'page',
	'supports' =>array('title', 'editor','excerpt', 'thumbnail','comments'),
	'exclude_from_search' =>true,
	'menu_position' =>82,
	'menu_icon' =>'dashicons-format-status'
	);
	register_post_type('blog-post', $args);
}
add_action( 'init', 'premium_blog_post');

//blog post shortcodes start

function blog_post_shortcodes($atts) {
    $user_stories  = array(
		'post_type'      => 'blog-post',
		'posts_per_page' => 3
	);

	$stories = new WP_Query( $user_stories );
	$result .= '<section class = "blog-list-wrapper mb-0">';
	$result .= '<div class="content-wrap d-flex col-3">';
	if($stories->have_posts()) :
 
        while($stories->have_posts()) :
 
        $stories->the_post() ;
        $link = get_the_permalink();
		$date = get_the_date( 'M j, Y' );
        $result .= '<div class="blog-card-wrap">';
        $result .= '<figure class="img-holder"><a href="' . $link . '">' . get_the_post_thumbnail() . '</a></figure>';
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

	return $result;    
}
add_shortcode( 'premium_blog_post', 'blog_post_shortcodes' );

//blog post shortcodes end

?>