<?php

/*-------------------------------------------------

	BlankPress - Default Blog Template

 --------------------------------------------------*/

 

get_header(); ?>



<section class="dp-full-width space-tp">

  <div class="container">

    <div class="row">     

      <div class="dsp-md-8">

        <div class="content-wrapper">

          <div class="dsp-page-title">

            <h1>Blogs</h1>

          </div>

          <div class="dsp-content">



            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <div id="post-<?php the_ID(); ?>" class="post-container" role="article">
             <div class="post-content">

                <a href="<?php the_permalink(); ?>" class="post-title"><?php the_title(); ?></a>

                <div class="post-image pull-left">

                <a href="<?php the_permalink(); ?>">

                  <?php if(has_post_thumbnail()) the_post_thumbnail('image21'); ?>

                </a>

                </div>


                <?php the_excerpt(); ?> 
              </div>

              <div class="clear"></div>

              <div class="post-meta">

                <span class="meta-tag pull-left"><i class="fa fa-tags"></i><?php the_tags('',', '); ?></span>

                <span class="pull-right"><i class="fa fa-th-large"></i><?php the_category(', '); ?></span>

                <a href="<?php comments_link(); ?>" class="pull-right"><i class="fa fa-comment"></i><?php comments_number( '0', '1', '%' ); ?>.</a>

                <a href="<?php the_author_link() ?>" class="pull-right"><i class="fa fa-user"></i><?php get_the_author(); ?></a>

              </div>

            </div>

            <?php endwhile; endif; ?>







            <!--<div class="dp-pagination clearfix">

              <ul class="pagination pull-right">

                <li><a href="#">Prev</a></li>

                <li><a href="#">1</a></li>

                <li><a href="#">2</a></li>

                <li><a href="#">3</a></li>

                <li><a href="#">Next</a></li>

              </ul>

			  

            </div>-->

			<?php wpbeginner_numeric_posts_nav(); ?>

          </div>

        </div>

      </div>

    

      <?php get_sidebar(); ?>



    </div>

  </div>

</section>



  

  



<?php get_footer();