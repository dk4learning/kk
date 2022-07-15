<?php
/*-------------------------------------------------
	BlankPress - Default Page Template
 --------------------------------------------------*/
get_header(); ?>

  <div class="dsp-md-12">
    
    <div class="content-wrapper">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

      <div>
       <?php $page_title_display = get_theme_mod('page_title_display');
       if($page_title_display){
       ?>
        <h1 class="dsp-page-title"><?php the_title(); ?></h1>
        <?php
      }
      ?>
      </div>

      <div class="dsp-content">
          <?php the_content(); ?>
      </div>
      <?php endwhile; endif; ?>

    </div>

  </div>

<?php //get_sidebar(); ?>

<?php get_footer();