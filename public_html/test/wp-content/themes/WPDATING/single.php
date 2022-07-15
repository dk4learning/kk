<?php
/*-------------------------------------------------
	BlankPress - Default Single Template
 --------------------------------------------------*/
get_header(); ?>

	<section class="main-content content-single group" role="main">
    <div class="content dsp-md-9">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'group' ); ?> role="article">
        <header>
          <h1><?php the_title(); ?></h1>
          <?php if(is_user_logged_in())  edit_post_link( __( 'edit', BP_DOMAIN ) ); ?>
        </header>
        <?php the_post_thumbnail(); ?>
        <?php  blankpress_post_meta(); ?>
        <div class="post-content group"><?php the_content(); ?></div>
        <section class="comment-section">
      <?php       if ( comments_open() || get_comments_number() ) {
      comments_template();
     }
     ?>
          <?php //comment_form(); ?>
          <?php endwhile; endif; ?>
        </section>
      </article>
      
      
    </div>

    

        <?php get_sidebar(); ?>

    
	</section>
	
<?php get_footer();