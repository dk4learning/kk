<div class="latest-blog home-box  dspdp-col-sm-4 dspdp-spacer-lg   dsp-sm-4 dsp-spacer-lg">
    <div class="dsp-home-widget" data-aos="fade-up" data-aos-easing="linear" data-aos-delay="1500" data-aos-duration="1000"><div class="dspdp-h4 dspdp-text-uppercase dspdp-spacer-md dsp-h4 dsp-text-uppercase dsp-spacer-md">
        <span class="heading-text">&nbsp;</span><?php echo __('Latest Blog', 'wpdating'); ?></div>
    <?php
        $args = array('numberposts' => '5');
        $recent_posts = wp_get_recent_posts($args);
        foreach ($recent_posts as $recent) {
            echo '<div class="dspdp-bordered-item  dspdp-small dsp-bordered-item  dsp-small"><div class="title-txt color-txt">' . mysql2date('j M Y', $recent["post_date"]) . '</div><a href="' . get_permalink($recent["ID"]) . '" title="Blog ' . esc_attr($recent["post_title"]) . '" >' . $recent["post_title"] . '</a> </div> ';
        }
    ?></div>
</div>