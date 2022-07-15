 <?php

/* -------------------------------------------------

  BlankPress - Default Header Template

  -------------------------------------------------- */

$option = get_option('BP_options', false); ?>

<?php

$userData = $_POST;

$error = null;

if (!empty($userData)) {

    $username = $userData['user_login'];

    $password = $userData['user_password'];

    if ($username != '' && $password != '') {

        $user = wp_signon($userData, false);

        if (is_wp_error($user))

            $error = 'Username/Password is incorrect';

        else

            wp_redirect(site_url('members'));

    }

}

?>

<!doctype html>

<!--[if lt IE 7]>      <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 7]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 ie7"> <![endif]-->

<!--[if IE 8]>         <html <?php language_attributes(); ?> class="no-js lt-ie9 ie8"> <![endif]-->

<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->

    <head>

        <?php theme_meta(); ?>



        <title><?php wp_title('|', true, 'right'); ?></title>



        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">



        <?php wp_head(); ?>

    </head>



    <body <?php dynamic_body_ids(); ?> <?php body_class('fixed-header'); ?>>



        <div class="main-wrapper">



            <header id="dsp-header">



                <div id="header-container" class="container">



                    <div class="row">



                        <div class="dsp-top-header">



                            <div class="dsp-logo-placeholder dsp-lg-3 dsp-md-12 dsp-sm-8 dsp-xs-7">

                                <a href="<?php bloginfo('siteurl'); ?>">

                                    <img src="<?php echo $option['logo_url']; ?>" alt="<?php echo get_option('blogdescription'); ?>">



                                </a>

                            </div>



                            <?php if (!is_user_logged_in()) { ?>

                                <div class="dsp-user dsp-lg-2 dsp-md-3 dsp-sm-4 dsp-xs-5">

                                    <a href="<?php bloginfo('siteurl') ?>/members/register" class="dsp-register pull-right">Register</a>

                                    <a href="javascript:void(0)" class="dsp-login pull-right">Login</a>

                                    <div class="dsp-login-form<?php if ($error === NULL) echo ' hide' ?>">

                                        <div class="dsp-login-form-title">

                                            User Login

                                        </div>

                                        <div class="dsp-login-form-container">

                                            <form name="login-form" method="post">

                                                <?php if ($error !== NULL): ?>

                                                    <div class="alert alert-danger">

                                                        <?php echo $error; ?>

                                                    </div>

                                                <?php endif; ?>

                                                <div class="dsp-login-input-container">

                                                    <div class="dsp-form-group user">

                                                        <div class="row">

                                                            <div class="dsp-md-12">

                                                                <div class="input-group">

                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>

                                                                    <input type="text" name="user_login" class="dsp-form-control" placeholder="Username">

                                                                </div>

                                                            </div>

                                                        </div>



                                                    </div>

                                                    <div class="dsp-form-group password">

                                                        <div class="row">

                                                            <div class="dsp-md-12">

                                                                <div class="input-group">

                                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>

                                                                    <input type="password" name="user_password" class="dsp-form-control" placeholder="Password">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <button type="submit" class="btn btn-primary">Submit</button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            <?php } else { ?>

                                <div class="dsp-user dsp-lg-2 dsp-md-3 dsp-sm-3 dsp-xs-5">

                                    <a href="#" class="dsp-register pull-right">

                                        <?php

                                            $current_user = wp_get_current_user();
                                            echo $current_user->user_login ;

                                        ?>

                                        <i class="fa fa-caret-down"></i>

                                    </a>

                                    <div class="dsp-user-setting">

                                        <ul>

                                            <li>

                                                <a href="<?php bloginfo('siteurl') ?>/members/setting/notification"><i class="fa fa-gear"></i><?php echo language_code('DSP_MENU_SETTINGS') ?></a></li>



                                            <li><a href="<?php

                                    $version = (float) get_bloginfo('version');

                                    if ($version >= 2.7) {

                                        ?><?php

                                        echo wp_logout_url($_SERVER['REQUEST_URI']);

                                    } else {

                                        bloginfo('wpurl');

                                        ?>/wp-login.php?action=logout&redirect_to=<?php echo $_SERVER['REQUEST_URI']; ?><?php } ?>"><i class="fa fa-power-off"></i><?php echo language_code('DSP_LOGOUT') ?></a></li>

                                        </ul>

                                    </div>

                                </div>



                            <?php } ?>





                            <div class="dsp-primary-nav dsp-lg-7 dsp-md-9 dsp-sm-12">

                                <a href="#" class="toggleMenu">Menu</a>



                                <div class="dsp-menu">

                                    <?php

                                    if (has_nav_menu('primary')) {

                                        wp_nav_menu(array(

                                            'theme_location' => 'primary',

                                            'menu' => '',

                                            'container' => false,

                                            'menu_class' => 'vs',

                                            'depth' => 0

                                        ));

                                    } else {

                                        ?>

                                        <ul>

                                            <?php

                                            wp_list_pages(array(

                                                'depth' => 0,

                                                'date_format' => get_option('date_format'),

                                                'title_li' => '',

                                                'echo' => 1,

                                                'sort_column' => 'menu_order, post_title',

                                            ));

                                            ?>

                                        </ul>

                                    <?php } ?>





                                </div>

                            </div>



                        </div>

                    </div>

                </div>



                <?php if (is_front_page()) { ?>



                    <?php $slider_display = get_theme_mod('slider_display');

                        if($slider_display) {

                            ?>



                            <div class="sldier-container">

                            <div class="container">

                                <div class="row">

                                    <?php //echo do_shortcode("[camera slideshow='banner-home']");?>

                                    <?php echo do_shortcode('[bp_slider id=2134]'); ?>

                                </div>

                            </div>

                            </div>

                            <?php

                        }

                    ?>



                    <div class="dsp-revolution-wrapper">

                        <?php $search_display = get_theme_mod('search_display');

                        if($search_display){

                            ?>

                            <div class="dsp-filter-container">

                                <div class="container">

                                    <div class="row">

                                        <div class="dsp-join-searchbox">

                                            <?php

                                            include("inc/blankpress-tables.php");

                                            $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;

                                            $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

                                            @session_start();

                                            $pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path

                                            $path = $pluginpath . 'image.php';

                                            $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

                                            // ROOT PATH

                                            $root_link = get_bloginfo('url') . "/members/";  // Print Site root link

                                            ?>

                                            <?php

                                            // if member is login then this menu will be display



                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN



                                                ?>

                                                <form name="frmquicksearch" class="searchform" method="POST" action="<?php echo get_bloginfo('url'); ?>/<?php echo 'members/search/search_result/basic_search/basic_search' ?>">

                                                    <input type="hidden" name="pid" value="5" />

                                                    <input type="hidden" name="pagetitle" value="search_result" />

                                            <?php } else { ?>

                                                <form name="frmquicksearch" class="searchform" method="POST" action="<?php echo get_bloginfo('url'); ?>/<?php echo 'members/g_search_result/' ?>">

                                            <?php } ?>

                                                    <input type="hidden" name="Pictues_only" value="P" />

                                                    <a href="#" class="showform"><i class="fa fa-search"></i></a>

                                                    <div class="dsp-lg-2 dsp-md-2">

                                                        <h4>Find my matches</h4>

                                                    </div>

                                                    <div class="dsp-lg-5 dsp-md-5">

                                                        <div class="row">

                                                            <div class="dsp-md-5">

                                                                <label> <?php echo language_code('DSP_I_AM'); ?></label>

                                                                <select name="gender" class="gender dsp-selectbox">

                                                                    <?php echo get_gender_list('M'); ?>

                                                                </select>

                                                            </div>

                                                            <div class="dsp-md-7 ">

                                                                <label> <?php echo language_code('DSP_SEEKING_A'); ?></label>

                                                                <select name="seeking" class="seeking dsp-selectbox">

                                                                    <?php echo get_gender_list('F'); ?>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="dsp-lg-5 dsp-md-5">

                                                        <div class="row">

                                                            <div class="dsp-md-5">

                                                                <label> <?php echo language_code('DSP_AGE'); ?></label>

                                                                <select name="age_from" class="age dsp-selectbox">

                                                                    <?php

                                                                        for ($fromyear = 18; $fromyear <= 99; $fromyear++) {

                                                                            if ($fromyear == 18) {

                                                                                ?>

                                                                                <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>

                                                                            <?php } else { ?>

                                                                                <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>

                                                                                <?php

                                                                            }

                                                                        }

                                                                    ?>

                                                                </select>

                                                            </div>

                                                            <div class="dsp-md-4">

                                                                <label> <?php echo language_code('DSP_TO'); ?> </label>

                                                                <select name="age_to" class="ageto dsp-selectbox">

                                                                    <?php

                                                                        for ($toyear = 18; $toyear <= 99; $toyear++) {

                                                                            if ($toyear == 99) {

                                                                                ?>

                                                                                <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>

                                                                            <?php } else { ?>

                                                                                <option value="<?php echo $toyear ?>"><?php echo $toyear ?></option>

                                                                                <?php

                                                                            }

                                                                        }

                                                                    ?>

                                                                </select>

                                                            </div>

                                                           <!--  <div class="dsp-md-4">

                                                               <label> <?php echo language_code('DSP_COUNTRY'); ?> </label>

                                                               <select name="cmbCountry" class="country dsp-selectbox">

                                                                   <option value="0"><?php echo language_code('DSP_SELECT_COUNTRY'); ?></option>

                                                                   <?php

                                                                       $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");

                                                                       foreach ($countries as $country) {

                                                                   ?>

                                                                       <option value="<?php echo $country->name; ?>" ><?php echo $country->name; ?></option>

                                                                   <?php } ?>

                                                               </select>

                                                           </div> -->

                                                            <div class="dsp-md-3">

                                                                <input name="submit" type="submit" class="dsp_submit_button dsp-submit" value="<?php echo language_code('DSP_SEARCH_BUTTON'); ?>" style="

                                                                   background: <?php echo $temp_color; ?>;

                                                                   "/>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <?php

                        }

                        ?>

                    </div>

                </header>

                <section class="dsp-full-width bg-lt-blue space-tp no-space-top">

                    <div class="container">

                        <div class="row">



                 <?php } else {

                            ?>



            </header>

                            <section class="dsp-full-width">

                                <div class="container">

                                    <div class="row">

                            <?php } ?>



