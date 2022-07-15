<style type="text/css">

    .dsp-menu ul li a {
        color: <?php echo get_theme_mod('menu_color'); ?>!important;
        border-top: 2px solid <?php echo get_theme_mod('menu_color'); ?>!important;
    }

    .dsp-menu .current_page_item>a {
        color: <?php echo get_theme_mod('menu_current_color'); ?>!important;
        border-top: 2px solid <?php echo get_theme_mod('menu_current_color'); ?>!important;
    }

    .dsp-menu ul li a:hover, .dsp-menu ul li a:focus, .dsp-menu ul lia:active {
       color: <?php echo get_theme_mod('menu_hovercolor'); ?>!important;
    }

    .dsp-user .dsp-login  {
        background-color: <?php echo get_theme_mod('menu_login_button'); ?>!important;
    }
    .dsp-user .dsp-register ,.dsp-user-setting{
        background-color: <?php echo get_theme_mod('menu_register_button'); ?>!important;
    }
        /** search background color **/
    .dsp-filter-container , .sbOptions li a, .sbOptions{
        background-color: <?php echo get_theme_mod('search_background_color'); ?>!important;
    }

    /** search text  color **/
    .dsp-filter-container label , .dsp-filter-container a{
        color: <?php echo get_theme_mod('search_text_color'); ?>!important;

    }
    .sbOptions li a{
        border-bottom: 1px solid <?php echo get_theme_mod('search_text_color'); ?>!important;
    }

    /** search text hover color **/
    .sbOptions li a:hover {
        color: <?php echo get_theme_mod('search_text_hover_color'); ?>!important;
    }

    /** Search Button **/
    .dsp-filter-container input.dsp-submit {
        background-color: <?php echo get_theme_mod('search_search_button_color'); ?>!important;

    }
    /** Search Button text color  **/
    .dsp-filter-container input.dsp-submit {
        color: <?php echo get_theme_mod('search_search_button_text_color'); ?>!important;

    }
    /** Search Button Hover**/
    .dsp-filter-container input.dsp-submit:Hover {
        background-color: <?php echo get_theme_mod('search_search_button_hover_color'); ?>!important;
    }

  /**Heading 2 color **/
    h2 span {
        color: <?php echo get_theme_mod('header2_color_setting'); ?>!important;
    }
    /** Footer Background Color **/
    #footer {
        background-color: <?php echo get_theme_mod('footer_color_setting'); ?>!important;
    }

    .footer-menu ul li a{
        color: <?php echo get_theme_mod('footer_color_menu_setting'); ?>!important;
    }

    .footer-menu .current_page_item>a {
        color: <?php echo get_theme_mod('footer_color_menu_current_setting'); ?>!important;
    }

    .footer-menu ul li a:hover, .footer-menu ul li a:focus, .footer-menu ul li a:active {
        color: <?php echo get_theme_mod('footer_color_menu_hover_setting'); ?>!important;
    }

</style>