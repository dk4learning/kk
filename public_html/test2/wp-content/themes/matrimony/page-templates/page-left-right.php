<?php
/**
 *
 * Template Name: Page Left &amp; Right Column
 *
 * Description: A page template with equal width columns (left, content, right)
 * @package lavish
 * @since 1.0.0
 */

get_header(); ?>


<?php get_sidebar('top'); ?>
<?php get_sidebar('inset-top'); ?>

    <section id="fr-content-area" class="fr-contents" role="main">
        <div class="container">
            <div class="row">

                <div class="dsp-md-3">
                    <aside class="sidebar plugin-sidebar">
                        <?php get_sidebar('left'); ?>
                    </aside>
                </div>


                <div class="dsp-md-6">
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <h1 class="dsp-page-title"><?php the_title(); ?></h1>
                        <div class="lavish_date_content">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; endif; ?>
                </div>

                <div class="dsp-md-3">
                    <aside class="sidebar plugin-sidebar">
                        <?php get_sidebar('right'); ?>
                    </aside>
                </div>

            </div>
            <!-- .row -->
        </div>
        <!-- .container -->
    </section>

<?php get_footer(); ?>