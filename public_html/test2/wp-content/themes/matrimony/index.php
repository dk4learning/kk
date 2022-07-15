<?php
/*
=================================================
Main Index Page For Lavish Date
=================================================
*/
get_header(); 

?>
 <div class="lavish_date_content_frame">
    <div class="container">    
        <div class="dsp-row">
            <div class="dsp-md-8">
                <h1 class="dsp-page-title">Blogs</h1>
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                    <div id="post-<?php the_ID(); ?>" class="post-container" role="article">
                        <div class="post-image">
                            <a href="<?php the_permalink(); ?>">
                              <?php if(has_post_thumbnail()) the_post_thumbnail('image21'); ?>
                            </a>
                        </div>
                        <div class="post-content">
                            <h3><a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h3>
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="clear"></div>
                        
                        <div class="post-meta">
                            <span class="meta-tag pull-left"><i class="fa fa-tags"></i><?php the_tags('',', '); ?></span>
                            <span class="pull-right"><i class="fa fa-th-large"></i><?php the_category(', '); ?></span>
                            <a href="<?php comments_link(); ?>" class="pull-right"><i class="fa fa-comment"></i><?php comments_number( '0', '1', '%' ); ?>.</a>
                            <a href="<?php get_the_author_link(); ?>" class="pull-right"><i class="fa fa-user"></i><?php the_author(); ?></a>
                        </div>
                    </div>

                    <?php endwhile; endif; ?>

                    <?php lavish_date_numeric_posts_nav(); ?>
            </div>
            <div class="dsp-md-4 lavish_date_blog_sidebar">
                <?php get_sidebar('right'); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer();