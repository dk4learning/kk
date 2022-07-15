<?php
// social links
if( ! function_exists( 'wp_premium_social_links' ) ) {
    function wp_premium_social_links() {
        $facebook = cs_get_option('facebook');
        $twitter = cs_get_option('twitter');
        $linkedin = cs_get_option('linkedin');
        $googleplus = cs_get_option('googleplus');
        $youtube = cs_get_option('youtube');
        $vimeo = cs_get_option('vimeo');
        $instagram = cs_get_option('instagram');
        $pinterest = cs_get_option('pinterest');
        $snapchat = cs_get_option( 'snapchat' );
        $dribbble = cs_get_option('dribbble');
        $wordpress = cs_get_option('wordpress');
        $rss = cs_get_option('rss');

        echo '<ul class="social-links no-dot d-flex">';
            if( $facebook ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($facebook).'"><i class="fa fa-facebook-f"></i></a> </li>';
            }

            if( $twitter ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($twitter).'"><i class="fa fa-twitter"></i></a> </li>';
            }

            if( $instagram ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($instagram).'"><i class="fa fa-instagram"></i></a> </li>';
            }

            if( $pinterest ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($pinterest).'"><i class="fa fa-pinterest"></i></a> </li>';
            }

            if( $googleplus ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($googleplus).'"><i class="fa fa-google-plus"></i></a> </li>';
            }

            if( $linkedin ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($linkedin).'"><i class="fa fa-linkedin"></i></a> </li>';
            }

            if( $youtube ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($youtube).'"><i class="fa fa-youtube"></i></a> </li>';
            }

            if( $vimeo ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($vimeo).'"><i class="fa fa-vimeo"></i></a> </li>';
            }

            if( $snapchat ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($snapchat).'"><i class="fa fa-snapchat"></i></a> </li>';
            }

            if( $dribbble ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($dribbble).'"><i class="fa fa-dribbble"></i></a> </li>';
            }

            if( $wordpress ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($wordpress).'"><i class="fa fa-wordpress"></i></a> </li>';
            }

            if( $rss ){
                echo '<li><a class="d-i-flex align-center justify-center" target="_blank" href="'.esc_url($rss).'"><i class="fa fa-rss"></i></a> </li>';
            }
        echo '</ul>';
        
    }
}
?>