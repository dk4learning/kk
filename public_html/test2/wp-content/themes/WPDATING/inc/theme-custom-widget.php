<?php

/**

 * Registering Custom Footer Widget

 *

 */

function arphabet_widgets_init() {



    register_sidebar( array(

        'name'          => 'About Us Widget',

        'id'            => 'about_us_widget',

        'before_widget' => '<article id="%1$s" class="widget group %2$s">',

        'after_widget'  => '</article>',

        'before_title'  => '<h2 class="rounded">',

        'after_title'   => '</h2>',

    ) );



     register_sidebar( array(

        'name'          => 'Custom Text Widget 1',

        'id'            => 'custom_text_widget_1',

        'before_widget' => '<article id="%1$s" class="widget group %2$s">',

        'after_widget'  => '</article>',

        'before_title'  => '<h2 class="rounded">',

        'after_title'   => '</h2>',

    ) );



    register_sidebar( array(

        'name'          => 'Custom Text Widget 2',

        'id'            => 'custom_text_widget_2',

        'before_widget' => '<article id="%1$s" class="widget group %2$s">',

        'after_widget'  => '</article>',

        'before_title'  => '<h2 class="rounded">',

        'after_title'   => '</h2>',

    ) );



    register_sidebar( array(

        'name'          => 'Custom Text Widget 3',

        'id'            => 'custom_text_widget_3',

        'before_widget' => '<article id="%1$s" class="widget group %2$s">',

        'after_widget'  => '</article>',

        'before_title'  => '<h2 class="rounded">',

        'after_title'   => '</h2>',

    ) );

    register_sidebar( array(

        'name'          => 'Quick Search',

        'id'            => 'quick_search',

        'before_widget' => '<article id="%1$s" class="widget group %2$s">',

        'after_widget'  => '</article>',

        'before_title'  => '<header><h1 class="widget-title">',

        'after_title'   => '</h1></header>',

    ) );







}

add_action( 'widgets_init', 'arphabet_widgets_init' );