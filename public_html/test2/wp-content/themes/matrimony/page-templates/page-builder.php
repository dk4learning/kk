<?php
/**
 * 
   Template Name: Page Builder
 *
 * @package lavish
 * @since 1.0.0
 */

get_header(); ?>


<?php get_sidebar('top'); ?>
<?php get_sidebar('inset-top'); ?>
    <div class="lavish_date_content_frame">
        <div class="container">
            <div class="row">
                <div class="dsp-md-12">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <h1 class="dsp-page-title"><?php the_title(); ?></h1>
                        <div class="lavish_date_content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>