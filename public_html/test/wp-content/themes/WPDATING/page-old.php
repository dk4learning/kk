<?php
/*-------------------------------------------------
	BlankPress - Default Page Template
 --------------------------------------------------*/
get_header(); ?>

  <div class="dsp-md-9">
    
    <div class="content-wrapper">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div class="dsp-page-title">

        <h1><?php the_title(); ?></h1>

      </div>

      <div class="dsp-content">
          <?php the_content(); ?>
      </div>
      <?php endwhile; endif; ?>

    </div>

  </div>

<?php //get_sidebar(); ?>

<?php get_footer();