<?php
/**
 * The Content Bottom Sidebar
 * @package lavish
 * @since 1.0.0
 */

if (   ! is_active_sidebar( 'contentbottom1')
	&& ! is_active_sidebar( 'contentbottom2')
	&& ! is_active_sidebar( 'contentbottom3')
	&& ! is_active_sidebar( 'contentbottom4')		
		
	)

		return;
	// If we get this far, we have widgets. Let do this.
	
?>
<div class="lavish_date_widget_content_bottom">
    <div class="container">
        <div id="la-content-bottom-group" class="row">        
            <div id="contentbottom1" <?php lavish_date_contentbottomgroup(); ?> role="complementary">
                <?php dynamic_sidebar( 'contentbottom1' ); ?>
            </div><!-- #top1 -->

            <div id="contentbottom2" <?php lavish_date_contentbottomgroup(); ?> role="complementary">
                <?php dynamic_sidebar( 'contentbottom2' ); ?>
            </div><!-- #top2 -->

            <div id="contentbottom3" <?php lavish_date_contentbottomgroup(); ?> role="complementary">
                <?php dynamic_sidebar( 'contentbottom3' ); ?>
            </div><!-- #top3 -->

            <div id="contentbottom4" <?php lavish_date_contentbottomgroup(); ?> role="complementary">
                <?php dynamic_sidebar( 'contentbottom4' ); ?>
            </div><!-- #top4 -->
        </div>
    </div>
</div>