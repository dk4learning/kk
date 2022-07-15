<?php

/*-------------------------------------------------

	BlankPress - Default Blog Template

	Template Name: Default template with sidebar

 --------------------------------------------------*/

 

get_header(); ?>









    <div class="dsp-content-wrapper dsp-content-wrapper-form">

    	<div class="dsp-md-8">

        	<div class="content dsp-content">

          <?php if (have_posts())

                 while (have_posts()) : the_post(); ?>

          				<?php the_content(); ?>

          <?php endwhile; ?>

        </div>

        </div>



        	<div class="dsp-md-4">

    <aside class="sidebar plugin-sidebar">

        <?php dynamic_sidebar( 'quick_search' ); ?>

      </aside>

  </div>

    </div>



<?php get_footer();