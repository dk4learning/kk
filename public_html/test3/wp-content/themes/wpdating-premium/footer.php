<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPDating_Premium
 */

?>

    <footer class="site-footer">
        <div class="wpee-container">
            <div class="content-wrap d-flex col-4 space-bet">
				<div id="footer-1">
				    <div class="company-detail-wrap">
				        <figure class="footer-logo">
				            <!-- <a href="#">
				                <img src="<?php echo get_template_directory_uri();?>/images/footer-logo.png'" alt="Premium">
				            </a> -->
				            <?php 
				            $logo = cs_get_option('footer-logo');
				            if( $logo) :
				                $logo_img = wp_get_attachment_image_src( $logo, 'full' );
				                ?>
				                <a class="navbar-brand" href="<?php echo esc_url( home_url('/') ); ?>">
				                    <img src="<?php echo esc_url( $logo_img[0] ); ?>" alt="">
				                </a>
				                    <?php
				                endif;
				        	?>
				        </figure>
				        
				        <div class="desc-wrap">
					        <?php
					            if(is_active_sidebar('footer-sidebar-1')){
					            dynamic_sidebar('footer-sidebar-1');
					            }
					        ?>
				        </div>
					        <?php 
					            $social_links = cs_get_option( 'footer-social' );
					            if( $social_links ): ?>
					                <?php wp_premium_social_links(); ?>
					        <?php endif; ?>
				    </div>
				</div>
				<div id="footer-2" class="quick-link-wrap">

					<?php
					if(is_active_sidebar('footer-sidebar-2')){
					dynamic_sidebar('footer-sidebar-2');
					}
					?>
				</div>
				<div id="footer-3" class="contact-detail-wrap">
					<?php
					if(is_active_sidebar('footer-sidebar-3')){
					dynamic_sidebar('footer-sidebar-3');
					}
					?>
				</div>
				<div id="footer-4" class="newsletter-wrap">

					<?php
					if(is_active_sidebar('footer-sidebar-4')){
					dynamic_sidebar('footer-sidebar-4');
					}
					?>
				</div>
         	</div>
            <div class="copyright-wrap d-flex space-bet align-center">
                <div class="left-content">
                    <div class="copyright">
                        <p class="mb-0"><?php echo cs_get_option('footer-copyright') ?></p>
                    </div>
                </div>
                <div class="right-content">
                    <?php wp_nav_menu( array( 'theme_location' => 'footer-nav' ) ); 
                        ?>
                </div>
            </div>
        </div>
	</footer>
</div><!-- #page -->

<?php
if (is_user_logged_in() && is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')) :
    do_action('wp_user_list_pop_up');
    ?>
    <input type="hidden" id="user-id" value="<?php echo get_current_user_id();?>">
<?php endif; ?>
<?php wp_footer(); ?>

</body>
<?php
if ( ! is_user_logged_in() ) : ?>
    <script type="text/javascript">
        jQuery('body').addClass('not-logged-in');
    </script>
<?php endif; ?>
</html>
