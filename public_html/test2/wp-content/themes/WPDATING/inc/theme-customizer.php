<?php

add_action('customize_register', 'blankpress_theme_customize');


function blankpress_theme_customize($wp_customize)
{


    // Setting group for uploading logo

    $wp_customize->add_setting('blankpress_logo', array(

        'default' => '',

        'sanitize_callback' => 'blankpress_sanitize_upload',

    ));


    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'blankpress_logo', array(

        'label' => __('Your Logo', 'blankpress'),

        'section' => 'title_tagline',

        'settings' => 'blankpress_logo',

        'priority' => 1,

    )));


    // Setting group for selecting logo title
    $wp_customize->add_setting('logo_style', array(
        'default' => 'default',
        'sanitize_callback' => 'blankpress_sanitize_logo_style',
    ));

    $wp_customize->add_control('logo_style', array(
        'label' => __('Logo Style', 'blankpress'),
        'section' => 'title_tagline',
        'priority' => 10,
        'type' => 'radio',
        'choices' => array(
            'default' => __('Default Logo', 'blankpress'),
            'custom' => __('Your Logo', 'blankpress'),
            'logotext' => __('Logo with Title and Tagline', 'blankpress'),
            'text' => __('Text Title', 'blankpress'),
        ),
    ));


    $wp_customize->add_section('front_page_customize', array(

        'title' => __('Front Page', 'blankpress'),

    ));

    // site title color
    $wp_customize->add_setting( 'sitetitle', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sitetitle', array(
        'label'   => __( 'Site Title Color', 'blankpress' ),
        'section' => 'title_tagline',
        'settings'   => 'sitetitle'
    ) ) );

    // tagline color
    $wp_customize->add_setting( 'tagline', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tagline', array(
        'label'   => __( 'Tagline Color', 'flat-blankpress' ),
        'section' => 'title_tagline',
        'settings'   => 'tagline'
    ) ) );


    // Setting for Slider of Home Page

    $wp_customize->add_setting('frontpage_display', array(

        'default' => '',

    ));

    // Setting for Slider of Home Page

    $wp_customize->add_setting('slider_display', array(

        'default' => '',

    ));


    // Setting for Search Block of Home Page

    $wp_customize->add_setting('search_display', array(

        'default' => '',

    ));


    // Setting for Member Block

    $wp_customize->add_setting('member_display', array(

        'default' => '',

    ));


    // Setting for Member Online

    $wp_customize->add_setting('member_online', array(

        'default' => '',

    ));


    // Setting for Happy Stories

    $wp_customize->add_setting('happy_stories', array(

        'default' => '',

    ));


    // Setting for Latest Blog

    $wp_customize->add_setting('latest_blog', array(

        'default' => '',

    ));


    // Control for Slider Of Home Page

    $wp_customize->add_control('frontpage_display', array(

        'label' => __('Front Page Display', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

        'priority' => 1,

    ));

    $wp_customize->add_setting('slider_shortcode', array(

        'default' => '',

    ));

    // Control for Pinterest Icon

    $wp_customize->add_control('slider_shortcode', array(

        'setting' => 'slider_shortcode',

        'label' => __('Slider shortcode', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'text',

    ));


    // Control for Slider Of Home Page

    $wp_customize->add_control('slider_display', array(

        'label' => __('Display Slider', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Control for Search Of Home Page

    $wp_customize->add_control('search_display', array(

        'label' => __('Search Display', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Control for Member Block

    $wp_customize->add_control('member_display', array(

        'label' => __('Display Member', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Control for Front Slider

    $wp_customize->add_control('member_online', array(

        'label' => __('Member Online', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Control for Happy Stories

    $wp_customize->add_control('happy_stories', array(

        'label' => __('Happy Stories', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Control for Latest Blog

    $wp_customize->add_control('latest_blog', array(

        'label' => __('Latest Blog', 'blankpress'),

        'section' => 'front_page_customize',

        'type' => 'checkbox',

    ));


    // Adding section for the Page Title Display

    $wp_customize->add_section('page_title_customize', array(

        'title' => __('Page Title', 'blankpress'),

    ));


    // Setting for Slider of Home Page

    $wp_customize->add_setting('page_title_display', array(

        'default' => '',

    ));


    // Control for Slider Of Home Page

    $wp_customize->add_control('page_title_display', array(

        'label' => __('Page Title Display', 'blankpress'),

        'section' => 'page_title_customize',

        'type' => 'checkbox',

    ));


    /* Adding Section For Socail Meda */

    $wp_customize->add_section('social_media', array(

        'title' => __('Social Media', 'blankpress'),

    ));


    // Setting for Facebook Icon

    $wp_customize->add_setting('facebook_uid', array(

        'default' => '',

    ));


    // Control for Facebook Icon

    $wp_customize->add_control('facebook_uid', array(

        'setting' => 'facebook_uid',

        'label' => __('Facebook', 'blankpress'),

        'section' => 'social_media',

        'type' => 'text',

    ));


    // Setting for Twitter Icon

    $wp_customize->add_setting('twitter_uid', array(

        'default' => '',

    ));


    // Control for Twitter Icon

    $wp_customize->add_control('twitter_uid', array(

        'setting' => 'twitter_uid',

        'label' => __('Twitter', 'blankpress'),

        'section' => 'social_media',

        'type' => 'text',

    ));


    // Setting for Google Icon

    $wp_customize->add_setting('google_uid', array(

        'default' => '',

    ));


    // Control for Google Icon

    $wp_customize->add_control('google_uid', array(

        'setting' => 'google_uid',

        'label' => __('Google Plus', 'blankpress'),

        'section' => 'social_media',

        'type' => 'text',

    ));


    // Setting for Pinterest Icon

    $wp_customize->add_setting('pinterest_uid', array(

        'default' => '',

    ));


    // Control for Pinterest Icon

    $wp_customize->add_control('pinterest_uid', array(

        'setting' => 'pinterest_uid',

        'label' => __('Pinterest', 'blankpress'),

        'section' => 'social_media',

        'type' => 'text',

    ));


    // Setting for Social Icon Color Choosing

    $wp_customize->add_setting('social_uid_color', array(

        'default' => '',

    ));


    // Control For Social Icon Color Choosing

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_uid_color', array(

        'label' => __('Choose Icon Color', 'blankpress'),

        'section' => 'social_media',

        'settings' => 'social_uid_color',

    )));


    // Setting for Social Icon Background Color Choosing

    $wp_customize->add_setting('social_uid_bg_color', array(

        'default' => '',

    ));


    // Control for Social Icon Background Color Choosing

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_uid_bg_color', array(

        'label' => __('Choose Icon Background Color', 'blankpress'),

        'section' => 'social_media',

        'settings' => 'social_uid_bg_color',

    )));


    // Setting for Social Icon Hover Color Choosing

    $wp_customize->add_setting('social_uid_hover_color', array(

        'default' => '',

    ));


    // Control for Social Icon Hover Color Choosing

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_uid_hover_color', array(

        'label' => __('Choose Icon Hover Color', 'blankpress'),

        'section' => 'social_media',

        'settings' => 'social_uid_hover_color',

    )));


    // Setting for Social Icon Hover Background Color Choosing

    $wp_customize->add_setting('social_uid_bg_hover_color', array(

        'default' => '',

    ));


    // Control for Social Icon Hover Background Color Choosing

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'social_uid_bg_hover_color', array(

        'label' => __('Choose Icon Hover Background Color', 'blankpress'),

        'section' => 'social_media',

        'settings' => 'social_uid_bg_hover_color',

    )));


    $wp_customize->add_section('footer_section', array(

        'title' => __('Footer', 'blankpress'),

    ));

    $wp_customize->add_setting('footer_text', array(

        'default' => 'Copyright @ WP Dating Theme 2015',

    ));

    // Control for Pinterest Icon

    $wp_customize->add_control('footer_text', array(

        'setting' => 'footer_text',

        'label' => __('Footer text', 'blankpress'),

        'section' => 'footer_section',

        'type' => 'text',

    ));


    /**
     *
    ================================================
    Typography and color setting
    ================================================
     */
    $wp_customize->add_panel( 'typography_setting_panel', array(// Header Panel
        'priority'       => 6,
        'capability'     => 'edit_theme_options',
        'title'          => __('Typography & Colors', 'blankpress'),
        'description'    => __('Edit text,menus color and sizes', 'blankpress'),
    ));

    $wp_customize->add_section( 'menu_color_setting', array(
        'title'          => __( 'Menu Colors', 'blankpress' ),
        'panel'          => 'typography_setting_panel',
        'priority'       => 5,
    ));

    $wp_customize->add_setting( 'menu_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_color', array(
        'label'   => __( 'Menu Color', 'blankpress' ),
        'section' => 'menu_color_setting'
    ) ) );

    $wp_customize->add_setting( 'menu_current_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_current_color', array(
        'label'   => __( 'Menu Current Color', 'blankpress' ),
        'section' => 'menu_color_setting'
    ) ) );

    $wp_customize->add_setting( 'menu_hovercolor', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hovercolor', array(
        'label'   => __( 'Menu Hover Color', 'blankpress' ),
        'section' => 'menu_color_setting'
    ) ) );

    $wp_customize->add_setting( 'menu_login_button', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_login_button', array(
        'label'   => __( 'Menu Login Button Color', 'blankpress' ),
        'section' => 'menu_color_setting'
    ) ) );

    $wp_customize->add_setting( 'menu_register_button', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_register_button', array(
        'label'   => __( 'Menu Register Button Color', 'blankpress' ),
        'section' => 'menu_color_setting'
    ) ) );
    // Lavish search Section color setting
    $wp_customize->add_section( 'search_color_setting', array(
        'title'          => __( 'Search Section Colors', 'blankpress' ),
        'panel'          => 'typography_setting_panel',
        'priority'       => 5,
    ));

    $wp_customize->add_setting( 'search_background_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_background_color', array(
        'label'   => __( 'Search Section Background Color', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    // Lavish search Section text color setting
       $wp_customize->add_setting( 'search_text_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_text_color', array(
        'label'   => __( 'Search Section Text Color', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    // Lavish search Section Text Hover color setting
    $wp_customize->add_setting( 'search_text_hover_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_text_hover_color', array(
        'label'   => __( 'Search Section Text Hover Color', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    // Lavish search Section Button color setting
    $wp_customize->add_setting( 'search_search_button_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_search_button_color', array(
        'label'   => __( 'Search Section Search Button', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    // Lavish search Section Button Hover color setting
    $wp_customize->add_setting( 'search_search_button_text_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_search_button_text_color', array(
        'label'   => __( 'Search Section Search Text Color', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    // Lavish search Section Button Hover color setting
    $wp_customize->add_setting( 'search_search_button_hover_color', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_search_button_hover_color', array(
        'label'   => __( 'Search Section Search Hover Button', 'blankpress' ),
        'section' => 'search_color_setting'
    ) ) );

    $wp_customize->add_section( 'font_color_setting', array(
        'title'          => __( 'Font Colors', 'blankpress' ),
        'panel'          => 'typography_setting_panel',
        'priority'       => 5,
    ));
    $wp_customize->add_setting( 'header2_color_setting', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header2_color_setting', array(
        'label'   => __( 'Header H2', 'blankpress' ),
        'section' => 'font_color_setting'
    ) ) );

/** Footer Background */
    $wp_customize->add_section( 'footer_color_setting', array(
        'title'          => __( 'Footer Colors', 'blankpress' ),
        'panel'          => 'typography_setting_panel',
        'priority'       => 5,
    ));
    $wp_customize->add_setting( 'footer_color_setting', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color_setting', array(
        'label'   => __( 'Footer background color', 'blankpress' ),
        'section' => 'footer_color_setting'
    ) ) );

    /**Footer Menu **/

    $wp_customize->add_setting( 'footer_color_menu_setting', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color_menu_setting', array(
        'label'   => __( 'Footer Menu color', 'blankpress' ),
        'section' => 'footer_color_setting'
    ) ) );

    $wp_customize->add_setting( 'footer_color_menu_hover_setting', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color_menu_hover_setting', array(
        'label'   => __( 'Footer Menu Hover color', 'blankpress' ),
        'section' => 'footer_color_setting'
    ) ) );

    $wp_customize->add_setting( 'footer_color_menu_current_setting', array(
        'default'        => '',
        'sanitize_callback' => 'dsp_sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_color_menu_current_setting', array(
        'label'   => __( 'Footer Menu Current color', 'blankpress' ),
        'section' => 'footer_color_setting'
    ) ) );

    /**
     * adds sanitization callback function for the logo style : radio
     * @package flat_responsive
     */
    function blankpress_sanitize_logo_style($input)
    {
        $valid = array(
            'default' => __('Default Logo', 'flat-responsive'),
            'custom' => __('Your Logo', 'flat-responsive'),
            'logotext' => __('Logo with Title and Tagline', 'flat-responsive'),
            'text' => __('Text Title', 'flat-responsive'),
        );

        if (array_key_exists($input, $valid)) {
            return $input;
        } else {
            return '';
        }
    }

    /**
     * adds sanitization callback function : colors
     *
     * @param $color
     * @return string
     */
    function dsp_sanitize_hex_color($color){
        if ( $unhashed = sanitize_hex_color_no_hash( $color ) )
            return '#' . $unhashed;

        return $color;
    }

    /**
     * adds sanitization callback function for uploading : uploader
     *
     * @package lavish  Special thanks to: https://github.com/chrisakelley
     */

    add_filter('blankpress_sanitize_image', 'blankpress_sanitize_upload');

    add_filter('blankpress_sanitize_file', 'blankpress_sanitize_upload');

    function blankpress_sanitize_upload($input)
    {


        $output = '';


        $filetype = wp_check_filetype($input);


        if ($filetype["ext"]) {


            $output = $input;


        }


        return $output;


    }


}

function mytheme_customize_css()

{

    ?>

    <style type="text/css">

        .footer-social-icon ul li a i {
            color: <?php echo get_theme_mod('social_uid_color'); ?>;
            background-color: <?php echo get_theme_mod('social_uid_bg_color'); ?>;
        }

        .footer-social-icon ul li a i:hover {
            color: <?php echo get_theme_mod('social_uid_hover_color'); ?>;
            background-color: <?php echo get_theme_mod('social_uid_bg_hover_color'); ?>;
        }

    </style>

<?php

}

add_action('wp_head', 'mytheme_customize_css');