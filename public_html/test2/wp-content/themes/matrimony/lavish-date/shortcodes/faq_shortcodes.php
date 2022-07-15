<?php

/*
=====================================================================
 # MAKING SHORTCODES FOR FAQ
======================================================================
*/

add_shortcode('style_faq', 'style_faq_fnc');

function style_faq_fnc($atts) {
	$faq = shortcode_atts( array(
		'total' => '',
		), $atts);
		echo style_faq_render($faq['total']);
		}

function style_faq_render( $total) {
	$args1 = array(
		'posts_per_page'   => $total,	
		'category'         => '',
		'category_name'    => '',
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'include'          => '',
		'exclude'          => '',
		'paged' 		   => $paged,
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'faq',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'post_status'      => 'publish',
		'suppress_filters' =>  true );

	$my_posts = get_posts( $args1 ); 

	?>
	<div class="container">
			<div class="row manosaryy">
					<?php global $post;
					$count_faq = 0;
					?>
					<?php
					foreach ( $my_posts as $post ) : setup_postdata( $post ); 
					$count_faq++; 

					?>

					<div class="col-md-6 lr_faqs manosary_item">
						<div class="lr_faq">
								<h4><i style="font-size:0.75rem" class="fa fa-search"></i>&nbsp; | &nbsp; <?php the_title(); ?></h4>
						<div class="la-faq-answer">
							<?php if ( has_post_thumbnail() ) { ?>
							<div class="lr_faq_image">
								<?php the_post_thumbnail('thumbnail'); ?>
							</div>
						<?php } ?>
							<?php the_content(); ?>
						</div>	

						</div>
					</div>
				
			<?php endforeach; ?>
			
		</div>
		</div>
	

<?php wp_reset_postdata(); 

}