<?php
/*
=================================================
Default Page Templates
=================================================
*/
get_header(); ?>
    <div class="lavish_date_content_frame">
        <div class="container">    
            <div class="content-wrapper">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <h1 class="dsp-page-title"><?php the_title(); ?></h1>
                    <div class="lavish_date_content">
                        <?php the_content(); ?>
                    </div>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
<?php get_footer();