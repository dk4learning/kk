<?php
/**
* @link https://codex.wordpress.org/Template_Hierarchy

 * @package WPDating_Premium
 * Template Name: testimonials page
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

    <section class="testimonials-listing-wrap">
      <div class="wpee-container">
        <div class="content-wrap d-flex col-2 space-bet">
          <?php
            query_posts( array('posts_per_page'=>10, 'post_type'=>'testimonials') );
            while ( have_posts() ) : the_post();
              $the_post_thumbnail_url = get_the_post_thumbnail_url();
              $client_name            = get_post_meta( get_the_ID(), 'client_name', true );
              $client_position        = get_post_meta( get_the_ID(), 'position', true ); ?>
            <div class="testimonials-wrap text-center">
                <h5 class="title"><?php the_title(); ?></h5>
                <div class="desc-wrap">
                    <p> <?php echo wp_trim_words( get_the_content(), 30 ); ?></p>
                </div>
                <div class="user-detail-wrap d-flex align-center justify-center">
                    <?php if ($the_post_thumbnail_url) : ?>
                    <figure>
                        <img src="<?php echo $the_post_thumbnail_url; ?>">
                    </figure>
                    <?php endif; ?>
                    <?php if ($client_name || $client_position) : ?>
                    <div class="user-detail-content text-left">
                      <?php if ($client_name) : ?>
                      <p class="name heading-font"> <?php echo $client_name; ?></p> </p>
                      <?php endif; ?>
                      <?php if ($client_position) : ?>
                      <p class="position"><?php echo $client_position; ?></p>
                    <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>
            </div>
          <?php endwhile; ?>
      
          <?php wp_reset_query(); // resets main query?>
        </div>
      </div>
    </section>
<?php
get_footer();
