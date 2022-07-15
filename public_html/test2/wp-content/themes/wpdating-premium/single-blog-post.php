<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WPDating_Premium
 */

get_header();
?>

<?php 
	$sidebar_position = cs_get_option('blog-list-sidebar-position');
	$container_class = '';
	if( $sidebar_position == 'full' ) {
		$container_class = 'no-sidebar';
	}
	elseif( $sidebar_position == 'left' ) {
		$container_class = 'left-sidebar';
	}
	elseif( $sidebar_position == 'right' ) {
		$container_class = 'right-sidebar';
	}
	else {
		$container_class = 'right-sidebar';
	}
?>

<section class="single-banner-wrap p-rel sec-gap">
	<div class="inner-banner-content p-ab">
		<div class="wpee-container">
			<div class="content-wrap">
				<ul class="meta-wrap no-dot d-flex align-center">
					<li><i class="fa fa-calendar"></i> <?php wpdating_premium_posted_on();?></li>
					<li><i class="fa fa-tag"></i><?php the_category(', ') ?></li>
					<li>
                        <?php if ( comments_open() ) : ?>
			                <a href="<?php comments_link(); ?>">
                                <i class="fa fa-comments" aria-hidden="true"></i>
                                <span class="com-count"><?php comments_number( '0', '1', '%' ); ?></span>
                                Comment
                            </a>
                        <?php endif; ?>
					</li>
					<li>
                        <i class="fa fa-user"></i>
                        <a href="<?php echo get_author_posts_url( $post->post_author ); ?>">
                            <?php the_author_meta( 'display_name', $post->post_author ); ?>
                        </a>
					</li>
				</ul>
				<h1 class="single-title">
					<?php the_title(); ?>
				</h1>
			</div>
		</div>
	</div>
	<?php /*<?php $url = wp_get_attachment_url(the_post_thumbnail( 'full' )); ?> */?>
	<figure class="img-holder overlay bg-img" style="background-image: url('<?php the_post_thumbnail_url(); ?>')">
	</figure>
</section>

	<section class="single-content-wrap sec-gap">
			<div class="wpee-container">
				<div class="content-wrap d-flex space-bet <?php echo esc_attr( $container_class ); ?>">
					<div class="single-body-wrap">
						<?php
						while ( have_posts() ) :
							the_post();

							get_template_part( 'template-parts/content-single', get_post_type() );

							the_post_navigation(
								array(
									'prev_text' => '<span class="nav-subtitle"><i class="fa fa-angle-left" aria-hidden="true"></i>' . esc_html__( 'Previous', 'wpdating-premium' ) . '</span> <span class="nav-title">%title</span>',
									'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'wpdating-premium' ) . '<i class="fa fa-angle-right" aria-hidden="true"></i></span> <span class="nav-title">%title</span>',
								)
							);

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
						?>
					</div>	

					<?php get_sidebar(); ?>

				</div>
			</div>
	</section>
	</main><!-- #main -->

<?php
get_footer();
