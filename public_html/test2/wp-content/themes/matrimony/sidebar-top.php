<?php
/**
 * The Top Sidebar
 * @package lavish
 * @since 1.0.0
 */

if (   ! is_active_sidebar( 'top1'  )
	&& ! is_active_sidebar( 'top2' )
	&& ! is_active_sidebar( 'top3'  )
	&& ! is_active_sidebar( 'top4'  )		
		
	)

		return;
	// If we get this far, we have widgets. Let do this.
?>

<div class="lavish_date_widget_top">
    <div class="container">
            <div class="row">
                <div id="top1" class="<?php lavish_date_topgroup(); ?>" role="complementary">
                    <?php dynamic_sidebar( 'top1' ); ?>
                </div><!-- #top1 -->
           
                <div id="top2" class="<?php lavish_date_topgroup(); ?>" role="complementary">
                    <?php dynamic_sidebar( 'top2' ); ?>
                </div><!-- #top2 -->          
            
                <div id="top3" class="<?php lavish_date_topgroup(); ?>"  role="complementary">
                    <?php dynamic_sidebar( 'top3' ); ?>
                </div><!-- #top3 -->

                <div id="top4" class="<?php lavish_date_topgroup(); ?>"  role="complementary">
                    <?php dynamic_sidebar( 'top4' ); ?>
                </div><!-- #top3 -->

            </div>
    </div>
</div>
