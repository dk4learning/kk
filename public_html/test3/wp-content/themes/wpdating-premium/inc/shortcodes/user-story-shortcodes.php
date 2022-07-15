<?php

//user story shortcodes 1 start

function premium_user_story() {
    $args = array(
    'labels' => array(
    'name' =>__('User Story'),
    'singular_name' =>__('User Story'),
    'all_items' =>__('All User Story'),
    'add_new_item' =>__('Add New User Story'),
    'edit_item' =>__('Edit User Story'),
    'view_item' =>__('View User Story')
    ),
    'public' =>true,
    'has_archive' =>false,
    'rewrite' =>array('slug' =>'user-story'),
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
    register_post_type('user-story', $args);
}
add_action( 'init', 'premium_user_story');

function user_story_shortcodes_1($atts) {
    $user_stories  = array(
		'post_type'      => 'user-story',
		'posts_per_page' => 1
	);

	$stories = new WP_Query( $user_stories );
	$result .= '<section class = "user-stories-wrap">';
	$result .= '<div class="content-wrap d-flex">';
	if($stories->have_posts()) :
 
        while($stories->have_posts()) :
 
        $stories->the_post() ;
        $link = get_the_permalink();
		$date = get_the_date( 'M j, Y' );
        $result .= '<div class="user-story-wrap">';
        $result .= '<figure class="img-holder"><a href="' . $link . '">' . get_the_post_thumbnail() . '</a></figure>';
        $result .= '<div class="story-content-wrap">';
        $result .= '<h4 class="post-title"><a href="' . $link . '">' . get_the_title() . '</a></h4>';
        $result .= '<div class="date-wrap"> <i class="fa fa-clock-o" aria-hidden="true"></i>'. $date .'</div>';
        $result .= '<p class="desc">' . wp_trim_words( get_the_content(), 60 ) . '</p>';
        $result .= '<div class="link-wrap line-animate"><a class="read_more" href="' . $link . '"> Read More </a></div>';
        $result .= '</div>';
        
        $result .= '</div>';
 
        endwhile;
 
        wp_reset_postdata();
 
    endif;
    $result .= '</div>';
	$result .= '</section>';

	return $result;      
}
add_shortcode( 'premium_user_story_1', 'user_story_shortcodes_1' );

//user story shortcodes 1 end


//user story shortcodes 2 start

function user_story_shortcodes_2($atts) {
    $user_stories  = array(
		'post_type'      => 'user-story',
		'posts_per_page' => 2
	);

	$stories = new WP_Query( $user_stories );
	$result .= '<section class = "user-stories-wrap">';
	$result .= '<div class="content-wrap">';
	if($stories->have_posts()) :
 
        while($stories->have_posts()) :
 
        $stories->the_post() ;
        $link = get_the_permalink();
		$date = get_the_date( 'M j, Y' );
        $result .= '<div class="user-story-wrap">';
        $result .= '<figure class="img-holder"><a href="' . $link . '">' . get_the_post_thumbnail() . '</a></figure>';
        $result .= '<div class="story-content-wrap">';
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
add_shortcode( 'premium_user_story_2', 'user_story_shortcodes_2' );

//user story shortcodes 2 end


?>