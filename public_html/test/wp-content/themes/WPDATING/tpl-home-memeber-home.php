<?php
/* -------------------------------------------------
  BlankPress - Default Page Template
  Template Name: Member
  -------------------------------------------------- */
get_header();?>

<div class="dsp-md-9 dsp-content-wrapper dsp-content-member-wrapper">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
       <?php the_content(''); ?>
    <?php endwhile; endif; ?>
</div>

<?php get_sidebar(); ?>
<?php get_footer();
