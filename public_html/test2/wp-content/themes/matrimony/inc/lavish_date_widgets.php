<?php
/*
=================================================
lavish Date Theme Widget Positions
This Files will show widgets on the back end of the file
@package lavish-date-date
=================================================
*/
function lavish_date_widgets_init()
{

    register_sidebar(array(
        'name' => __('Blog Sidebar Right', 'lavish-date'),
        'id' => 'blogright',
        'description' => __('This is the right sidebar column that appears on the blog but not the pages.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><hr/>',
    ));
    register_sidebar(array(
        'name' => __('Blog Sidebar Left', 'lavish-date'),
        'id' => 'blogleft',
        'description' => __('This is the left sidebar column that appears on the blog but not the pages.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><hr/>',
    ));
    register_sidebar(array(
        'name' => __('Banner Wide', 'lavish-date'),
        'id' => 'banner-wide',
        'description' => __('This is a full width showcase banner for images or media sliders that can display on your pages.', 'lavish-date'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Top 1', 'lavish-date'),
        'id' => 'top1',
        'description' => __('This is the 1st top widget position located just below the banner area.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Top 2', 'lavish-date'),
        'id' => 'top2',
        'description' => __('This is the 2nd top widget position located just below the banner area.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Top 3', 'lavish-date'),
        'id' => 'top3',
        'description' => __('This is the 3rd top widget position located just below the banner area.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Top 4', 'lavish-date'),
        'id' => 'top4',
        'description' => __('This is the 4th top widget position located just below the banner area.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));


    register_sidebar(array(
        'name' => __('Inset Top', 'lavish-date'),
        'id' => 'insettop',
        'description' => __('This is a single full width widget position just above the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));


    register_sidebar(array(
        'name' => __('Inset Bottom', 'lavish-date'),
        'id' => 'insetbottom',
        'description' => __('This is a single full width widget position just below the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));

    register_sidebar(array(
        'name' => __('Insetfull', 'lavish-date'),
        'id' => 'insetfull',
        'description' => __('This is a Inset Bottom widget position at the very bottom of the page and Full Width Container.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Registration Instructions', 'lavish-date'),
        'id' => 'reg_widget',
        'description' => __('This is a Widget position at the registration page which will allow you to show the registration instructions on the register page. If the Widget is Blank the register page will be in full width.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><strong>',
        'after_title' => '</h3><hr/>',
    ));

    register_sidebar(array(
        'name' => __('Content Bottom 1', 'lavish-date'),
        'id' => 'contentbottom1',
        'description' => __('This is the first content bottom widget position located just below the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Content Bottom 2', 'lavish-date'),
        'id' => 'contentbottom2',
        'description' => __('This is the second content bottom widget position located just below the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Content Bottom 3', 'lavish-date'),
        'id' => 'contentbottom3',
        'description' => __('This is the third content bottom widget position located just below the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Content Bottom 4', 'lavish-date'),
        'id' => 'contentbottom4',
        'description' => __('This is the fourth content bottom widget position located just below the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));

    register_sidebar(array(
        'name' => __('Bottom 1', 'lavish-date'),
        'id' => 'bottom1',
        'description' => __('This is the first bottom widget position located in a coloured background area just above the footer.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Bottom 2', 'lavish-date'),
        'id' => 'bottom2',
        'description' => __('This is the second bottom widget position located in a coloured background area just above the footer.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Bottom 3', 'lavish-date'),
        'id' => 'bottom3',
        'description' => __('This is the third bottom widget position located in a coloured background area just above the footer.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Bottom 4', 'lavish-date'),
        'id' => 'bottom4',
        'description' => __('This is the fourth bottom widget position located in a coloured background area just above the footer.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3><span class="dotbox"></span>',
        'after_title' => '</h3><div class="dotlinebox"><span class="dot"></span></div>',
    ));
    register_sidebar(array(
        'name' => __('Call to Action', 'lavish-date'),
        'id' => 'cta',
        'description' => __('This is a call to action which is normally used to make a message stand out just above the main content.', 'lavish-date'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

}

add_action('widgets_init', 'lavish_date_widgets_init');

/**
 * Count the number of widgets to enable resizable widgets
 */

// lets setup the inset top group 
function lavish_date_topgroup()
{
    $count = 0;
    if (is_active_sidebar('top1'))
        $count++;
    if (is_active_sidebar('top2'))
        $count++;
    if (is_active_sidebar('top3'))
        $count++;
    if (is_active_sidebar('top4'))
        $count++;
    $class = '';
    switch ($count) {
        case '1':
            $class = 'dsp-md-12';
            break;
        case '2':
            $class = 'dsp-md-6';
            break;
        case '3':
            $class = 'dsp-md-4';
            break;
        case '4':
            $class = 'dsp-md-3';
            break;
    }
    if ($class)
        echo $class;
}

// lets setup the content bottom group 
function lavish_date_contentbottomgroup()
{
    $count = 0;
    if (is_active_sidebar('contentbottom1'))
        $count++;
    if (is_active_sidebar('contentbottom2'))
        $count++;
    if (is_active_sidebar('contentbottom3'))
        $count++;
    if (is_active_sidebar('contentbottom4'))
        $count++;
    $class = '';
    switch ($count) {
        case '1':
            $class = 'dsp-md-12';
            break;
        case '2':
            $class = 'dsp-md-6';
            break;
        case '3':
            $class = 'dsp-md-4';
            break;
        case '4':
            $class = 'dsp-md-3';
            break;
    }
    if ($class)
        echo 'class="' . $class . '"';
}

// lets setup the bottom group 
function lavish_date_bottomgroup()
{
    $count = 0;
    if (is_active_sidebar('bottom1'))
        $count++;
    if (is_active_sidebar('bottom2'))
        $count++;
    if (is_active_sidebar('bottom3'))
        $count++;
    if (is_active_sidebar('bottom4'))
        $count++;
    $class = '';
    switch ($count) {
        case '1':
            $class = 'dsp-md-12';
            break;
        case '2':
            $class = 'dsp-md-6';
            break;
        case '3':
            $class = 'dsp-md-4';
            break;
        case '4':
            $class = 'dsp-md-3';
            break;
    }
    if ($class)
        echo 'class="' . $class . '"';
}