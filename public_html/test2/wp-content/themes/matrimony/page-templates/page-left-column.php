<?php
/**
 *
 * Template Name: Page Left Column
 *
 * Description: A page template with a left column
 * @package lavish
 * @since 1.0.0
 */

get_header(); ?>


<?php get_sidebar('top'); ?>
<?php get_sidebar('inset-top'); ?>

    <div class="lavish_date_content_frame">
        <div class="container">
            <div class="row">

                <div class="dsp-md-4">
                    <aside class="sidebar plugin-sidebar">
                        <?php get_sidebar('left'); ?>
                    </aside>
                </div>

                <div class="dsp-md-8">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <h1 class="dsp-page-title"><?php the_title(); ?></h1>
                        <div class="lavish_date_content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; endif; ?>
                </div>

            </div>
            <!-- .row -->
        </div>
        <!-- .container -->
    </div>

<?php get_footer(); ?>