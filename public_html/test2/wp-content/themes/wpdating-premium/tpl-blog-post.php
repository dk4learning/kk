<?php
/**
* @link https://codex.wordpress.org/Template_Hierarchy

 * @package WPDating_Premium
 * Template Name: blog page
 */
wp_enqueue_style( 'font-awesome-solid', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/solid.min.css',false,'5.15.3','all');
get_header(); ?>

  <section class="inner-page-wrap d-flex align-center">
    <div class="wpee-container">
      <h1 class="text-center"><?php the_title(); ?></h1>
      <?php if ( ! empty(get_the_content())) :  ?>
        <div class="desc-wrap">
            <?php the_content() ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

    <section class="blog-list-wrapper">
      <div class="wpee-container">
        <div class="content-wrap d-flex col-3">
          <?php
            query_posts( array('posts_per_page'=>10, 'post_type'=>'blog-post') );
            while ( have_posts() ) : the_post();
          ?>
          <?php if ( has_post_thumbnail() ): // check for the featured image ?>
            <div class="blog-card-wrap">
              <figure class="img-holder">
                <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="opacity"><?php the_post_thumbnail('full'); ?></a>
              </figure> <!--echo the featured image-->
              <div class="blog-content-wrap">
              <ul class="cat-wrap d-flex no-dot">
                <li><a href="#"><?php the_category(', ') ?></a></li>
              </ul>
              <h4 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
              <div class="date-wrap"> <i class="fa fa-clock-o" aria-hidden="true"></i><?php echo get_the_date( 'M j, Y' ); ?></div>
              <?php
                endif;
              ?>
      			
            </div> 
          </div>
          <?php endwhile; ?>
      
          <?php wp_reset_query(); // resets main query?>
        </div>
      </div>
    </section>
<?php
get_footer();
