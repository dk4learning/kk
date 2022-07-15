<?php

/*
=====================================================================
 # MAKING SHORTCODES FOR TESTOMONIALS
======================================================================
*/

add_shortcode('style_testimonials', 'style_testomonials_fnc');

function style_testomonials_fnc($atts) {
	$testomonials = shortcode_atts( array(
		'columns' => '',
		'per_page' => '',
		'total' => '',
		), $atts);
		echo style_testomonials_render ($testomonials['columns'], $testomonials['per_page'], $testomonials['total']);
		}
function style_testomonials_render($columns, $per_page, $total) {
	$args = array(
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
		'post_type'        => 'testonials',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'post_status'      => 'publish',
		'suppress_filters' => true );

	$myposts = get_posts( $args ); 

	?>
	<div class="la-content-inside" style="padding-top:2rem">
		<div class="container">
			<div class="row manosaryy">
				<?php 
				$count_testomonials = 0;
				
				global $post;


				foreach ( $myposts as $post ) : setup_postdata( $post ); 
				$count_testomonials++;
				?>
				<div class="col-md-4 manosary_item testomonials_background">

					<div class="lr_testomonials">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="lr_testomonials_image">
						<?php the_post_thumbnail('thumbnail'); ?>
						</div>
					<?php } ?>
					
					<div class="lr_testomonials_content">
						<p>
						<?php echo get_post_meta($post->ID, st_testomonials_submitter_message, true); ?>
						</p>
					</div>	
					<div class="lr_testomonials_submitter_details">
						<?php 
							echo get_post_meta($post->ID, st_testomonials_submitter_name, true);
						?> <br/>
						<span class="company"> 
						<?php
							echo get_post_meta($post->ID, st_testomonials_submitter_comp_name, true);
						?>
						</span>

					</div>
					
					
			</div>		
				</div>
				<?php
				endforeach; 
				
				?>
				
			</div>
			
		</div>
	</div>	

	<?php
	wp_reset_postdata();
}