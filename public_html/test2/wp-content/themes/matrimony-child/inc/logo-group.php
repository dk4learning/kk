<?php
$logostyle = get_theme_mod('logo_style', 'default');

switch ($logostyle) {
    case "default" : // default theme logo
        ?>

        <div id="dsp-logo-group">
            <a href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                <img class="img-responsive" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png"
                     alt="<?php bloginfo('name'); ?>"/></a>
        </div>

        <?php break;
    case "custom" : // your own logo
        ?>

        <div id="dsp-logo-group">
            <a href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                <img class="img-responsive" src="<?php echo get_theme_mod('blankpress_logo'); ?>"
                     alt="<?php bloginfo('name'); ?>"/>
            </a>
        </div>

        <?php break;
    case "logotext" : // your own logo with text based title and site description
        ?>

        <div id="dsp-logo-group">
            <a href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                <img class="img-responsive" src="<?php echo get_theme_mod('blankpress_logo'); ?>"
                     alt="<?php bloginfo('name'); ?> "/>
            </a>
        </div>

        <div id="dsp-site-title-group"
             style="margin: <?php echo esc_attr(get_theme_mod('titlemargin', '0 0 0 0')); ?>;">
            <h1 id="dsp-site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                       title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                                       rel="home"
                                       style="color: <?php echo esc_attr(get_theme_mod('sitetitle')); ?>;"><?php bloginfo('name'); ?></a>
            </h1>
            <h5 id="dsp-site-tagline"
                style="color: <?php echo esc_html(get_theme_mod('tagline')); ?>;"><?php bloginfo('description'); ?></h5>
        </div>

        <?php break;
    case "text" : // text based title and site description
        ?>
        <div id="dsp-text-group">
            <div id="dsp-site-title-group"
                 style="margin: <?php echo esc_attr(get_theme_mod('titlemargin', '0 0 0 0')); ?>;">
                <h1 id="dsp-site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                           title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>"
                                           rel="home"
                                           style="color: <?php echo esc_attr(get_theme_mod('sitetitle')); ?>;"><?php bloginfo('name'); ?></a>
                </h1>

                <h2 id="dsp-site-tagline"
                    style="color: <?php echo esc_html(get_theme_mod('tagline')); ?>;"><?php bloginfo('description'); ?></h2>
            </div>
        </div>

        <?php break;
}
?>
