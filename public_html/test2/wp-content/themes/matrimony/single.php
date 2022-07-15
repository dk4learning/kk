<?php
/*
=================================================
Single Blog Post Pages For Lavish Date Theme
=================================================
*/
get_header(); ?>

<?php
/*
=================================================
Main Index Page For Lavish Date
=================================================
*/
get_header(); ?>
<div class="lavish_date_content_frame">
    <div class="container">    
        <div class="content-wrapper">
            <div class="dsp-md-8">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
        
                        <header>
                            <h1><?php the_title(); ?></h1>
                            
                        </header>

                        <div class="post-image">
                        <?php the_post_thumbnail('image21'); ?>
                        </div>

                        <?php  blankpress_post_meta(); ?>

                        <div class="post-content group">
                            <?php the_content(); ?>
                        </div>

                        <?php if(is_user_logged_in())  edit_post_link( __( 'edit', 'lavish-date' ) ); ?>

                        <section class="comment-section">
                        <?php       
                            if ( comments_open() || get_comments_number() ) {
                                echo '<div class="lavish_blog_comment">';
                                comments_template();
                                echo '</div>';
                            }
                        ?>
                        </section>
                    </article>
                <?php endwhile; endif; ?>
            </div>
            <div class="dsp-md-4 lavish_date_blog_sidebar">
                <?php get_sidebar('right'); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer();