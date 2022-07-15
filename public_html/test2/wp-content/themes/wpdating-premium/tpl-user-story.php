<?php
/**
* @link https://codex.wordpress.org/Template_Hierarchy

 * @package WPDating_Premium
 * Template Name: User Story page
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

    <section class="user-stories-wrap listing-wrap">
    <div class="wpee-container">
      <div class="content-wrap d-flex col-2">

        <?php
            query_posts( array('posts_per_page'=>10, 'post_type'=>'user-story') );
            while ( have_posts() ) : the_post();
        ?>
        <?php if ( has_post_thumbnail() ): // check for the featured image ?>
          <div class="user-story-wrap">
            <figure class="img-holder">
              <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="opacity"><?php the_post_thumbnail(); ?></a> <!--echo the featured image-->
            </figure>
            <div class="story-content-wrap">
            <h4 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
            <ul class="meta-wrap d-flex no-dot align-center">
              <li><a href="#"><i class="fa fa-clock-o" aria-hidden="true"></i><?php echo the_modified_date('F j, Y'); ?></a></li>
              <li><a href="#" class="d-flex align-center">
                <?php if ( comments_open() ) { ?>
                <a href="<?php comments_link(); ?>"> <i class="fa fa-comment" aria-hidden="true"></i> <span class="com-count"><?php comments_number( '0', '1', '%' ); ?></span>Comment</a>
                <?php } ?>
              </li>
            </ul>
            <div class="desc-wrap">
              <p>
                <?php
                  endif;
                 echo wp_trim_words( get_the_content(), 30 );
                ?>
              </p>
            </div>
            <div class="link-wrap line-animate">
              <a href="<?php the_permalink() ?>">Read Full Story</a>
            </div>          
          </div>
        </div> 
        <?php endwhile; ?>
        
        <?php wp_reset_query(); // resets main query?>
      </div>
    </div>
  </section>
</div>
<?php
get_footer();
