 <?php

/* -------------------------------------------------

  BlankPress - Default Header Template

  -------------------------------------------------- */

$option = get_option('BP_options', false); 

$frontpage_display = get_theme_mod('frontpage_display'); 

$userData = $_POST;

global $error;

$error = null;

if (!empty($userData) && array_key_exists('user_login',$userData) && array_key_exists('user_password',$userData)) {

    $username = $userData['user_login'];

    $password = $userData['user_password'];

    if ($username != '' && $password != '') {

        $user = wp_signon($userData, false);  

        if (is_wp_error($user))

            $error = 'Username/Password is incorrect';

        else

            wp_redirect(site_url('members'));

    } else {
        
        $error = 'Please enter Username and Password';
    }

}



?>

<!doctype html>
<html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
    <head>
        <?php theme_meta(); ?>
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <?php wp_head(); ?>
    </head>
    <body <?php dynamic_body_ids(); ?> <?php body_class('fixed-header'); ?>>
        <?php get_template_part('partials/main_sidebar_mobile'); ?>
        <div class="lavish_date_move_to_top"> 
            <i class="fa fa-arrow-up"></i>
        </div>  
        
        <div class="main-wrapper">
                      
            <!--Header Starts Here -->
            <header id="lavish_dsp_header">
                <div id="header-container">
                    <div class="container">
                        <div class="dsp-row">
                            <div class="dsp-top-header">
                                <div class="dsp-logo-placeholder dsp-lg-3 dsp-md-4 dsp-sm-8 dsp-xs-7">

                                    <?php get_template_part( 'inc/logo-group' ); ?>

                                </div>
                                <a href="#" class="toggle_menu_bar"><i class="fa fa-bars"></i></a>
                                <div class="dsp_menu">
                                    
                                <?php
                                    if (has_nav_menu('primary')) {
                                        wp_nav_menu(array(
                                            'theme_location' => 'primary',
                                            'menu_class' => 'navmenu',
                                        ));
                                }
                                    ?>
                                </div>
                                <div class="dsp_login_logout">
                                    <?php get_template_part('partials/lavish_login', 'logout'); ?>
                                </div>
                                

                            </div>
                        </div>
                        
                        </div>
                    </div>
                
            </header>
            <!--Header Ends Here -->

            <!--Slider Starts Here -->
            <div class="slider-container">
                <?php get_sidebar('banner'); ?>
            </div>
            <!--Slider Ends Here-->
            
           
            

            <?php 
            if (!is_front_page()) {
                do_action('style_breadcrumb');
            } ?>

            <?php get_sidebar( 'cta' ); ?>

            <?php get_sidebar('top'); ?>

            <?php get_sidebar('inset-top'); ?>
