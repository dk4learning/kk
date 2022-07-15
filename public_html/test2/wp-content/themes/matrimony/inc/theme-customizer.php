<?php
    add_action('customize_register', 'blankpress_theme_customize');

    function blankpress_theme_customize( $wp_customize ) {

        $wp_customize->remove_control("header_image");
        $wp_customize->remove_section("background_image");

        // Setting group for selecting logo title
        $wp_customize->add_setting('logo_style', array(
            'default' => 'default',
            'sanitize_callback' => 'blankpress_sanitize_logo_style',
        ));

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

        $wp_customize->add_control('logo_style', array(
            'label' => __('Logo Style', 'lavish-date'),
            'section' => 'title_tagline',
            'priority' => 10,
            'type' => 'radio',
            'choices' => array(
                'default' => __('Default Logo', 'lavish-date'),
                'custom' => __('Your Logo', 'lavish-date'),
                'logotext' => __('Logo with Title and Tagline', 'lavish-date'),
                'text' => __('Text Title', 'lavish-date'),
            ),
        ));

        // site title color
        $wp_customize->add_setting( 'sitetitle', array(
            'default'        => '',
            'sanitize_callback' => 'dsp_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sitetitle', array(
            'label'   => __( 'Site Title Color', 'lavish-date' ),
            'section' => 'title_tagline',
            'settings'   => 'sitetitle'
        ) ) );

        // tagline color
        $wp_customize->add_setting( 'tagline', array(
            'default'        => '',
            'sanitize_callback' => 'dsp_sanitize_hex_color',
        ) );

        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tagline', array(
            'label'   => __( 'Tagline Color', 'lavish-date' ),
            'section' => 'title_tagline',
            'settings'   => 'tagline'
        ) ) );
        /*


        /*
        =================================================
        Social Icons Setup
        =================================================
        */
        $wp_customize->add_panel( 'social_networking_panel', array(// Header Panel
            'priority'       => 6,
            'capability'     => 'edit_theme_options',
            'title'          => __('Social Networking', 'lavish-date'),
            'description'    => __('Deals with the social networking of your theme', 'lavish-date'),
        ));

        $wp_customize->add_section( 'social_networking', array(
            'title'          => __( 'Social Networking Links', 'lavish-date' ),
            'priority'       => 1,
            'panel'          => 'social_networking_panel',
        ));

        // Setting group for Twitter
        $wp_customize->add_setting( 'twitter_uid', array(
            'default'        => '',
        ) );

        $wp_customize->add_control( 'twitter_uid', array(
            'settings' => 'twitter_uid',
            'label'    => __( 'Twitter', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 2,
        ));

        // Setting group for Facebook
        $wp_customize->add_setting( 'facebook_uid', array(
            'default'        => '',
        ) );

        $wp_customize->add_control( 'facebook_uid', array(
            'settings' => 'facebook_uid',
            'label'    => __( 'Facebook', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 3,
        ));

        // Setting group for Google+
        $wp_customize->add_setting( 'google_uid', array(
            'default'        => '',
        ));

        $wp_customize->add_control( 'google_uid', array(
            'settings' => 'google_uid',
            'label'    => __( 'Google+', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 4,
        ));


        // Setting group for Pinterest
        $wp_customize->add_setting( 'pinterest_uid', array(
            'default'        => '',
        ));

        $wp_customize->add_control( 'pinterest_uid', array(
            'settings' => 'pinterest_uid',
            'label'    => __( 'Pinterest', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 6,
        ));

        // Setting group for Flickr
        $wp_customize->add_setting( 'flickr_uid', array(
            'default'        => '',
        ));

        $wp_customize->add_control( 'flickr_uid', array(
            'settings' => 'flickr_uid',
            'label'    => __( 'Flickr', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 7,
        ));

        // Setting group for Youtube
        $wp_customize->add_setting( 'youtube_uid', array(
            'default'        => '',
        ));

        $wp_customize->add_control( 'youtube_uid', array(
            'settings' => 'youtube_uid',
            'label'    => __( 'Youtube', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 8,
        ));

        // Setting group for Instagram
        $wp_customize->add_setting( 'instagram_uid', array(
            'default'        => '',
        ));

        $wp_customize->add_control( 'instagram_uid', array(
            'settings' => 'instagram_uid',
            'label'    => __( 'Instagram', 'lavish-date' ),
            'section'  => 'social_networking',
            'type'     => 'text',
            'priority' => 10,
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
            'title'          => __('Typography & Colors', 'lavish-date'),
            'description'    => __('Edit text,menus color and sizes', 'lavish-date'),
        ));
        
        $wp_customize->add_section( 'menu_color_setting', array(
            'title'          => __( 'Menu Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
        $wp_customize->add_setting( 'menu_backgroundcolor', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_backgroundcolor', array(
    		'label'   => __( 'Menu Background Color', 'lavish-date' ),
    		'section' => 'menu_color_setting'			
    	) ) );
        
        $wp_customize->add_setting( 'menu_hovercolor', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'menu_hovercolor', array(
    		'label'   => __( 'Menu Hover Color', 'lavish-date' ),
    		'section' => 'menu_color_setting'			
    	) ) );
        
        // Slider hover setting
        $wp_customize->add_section( 'slider_color_setting', array(
            'title'          => __( 'Slider Button Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'slidernav_hovercolor', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'slidernav_hovercolor', array(
    		'label'   => __( 'Slider Nav Hover Color', 'lavish-date' ),
    		'section' => 'slider_color_setting'			
    	) ) );
        
        // Lavish search Section color setting
        $wp_customize->add_section( 'search_color_setting', array(
            'title'          => __( 'Search Section Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'search_background_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'search_background_color', array(
    		'label'   => __( 'Search Section Background Color', 'lavish-date' ),
    		'section' => 'search_color_setting'			
    	) ) );
        
        // Lavish Button color setting
        $wp_customize->add_section( 'button_color_setting', array(
            'title'          => __( 'Button Colors Setting', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'button_background_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_background_color', array(
    		'label'   => __( 'Button Background Color', 'lavish-date' ),
    		'section' => 'button_color_setting'			
    	) ) );
        
        $wp_customize->add_setting( 'button_hover_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_hover_color', array(
    		'label'   => __( 'Button Hover Color', 'lavish-date' ),
    		'section' => 'button_color_setting'			
    	) ) );
        
        // Lavish Member info label color
        $wp_customize->add_section( 'member_info_color_setting', array(
            'title'          => __( 'Member Label Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'member_info_button_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'member_info_button_color', array(
    		'label'   => __( 'Member Background Color', 'lavish-date' ),
            'description' => __('Member Info Label Background Color','lavish-date'),
    		'section' => 'member_info_color_setting'			
    	) ) );
        
        // Lavish Stories color option
        $wp_customize->add_section( 'stories_color_setting', array(
            'title'          => __( 'Stories Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'stories_bk_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'stories_bk_color', array(
    		'label'   => __( 'Stories Section Background Color', 'lavish-date' ),
    		'section' => 'stories_color_setting'			
    	) ) );
        
        // Footer color option
        $wp_customize->add_section( 'footer_color_setting', array(
            'title'          => __( 'Footer Color options', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'middlefooter_bk_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'middlefooter_bk_color', array(
    		'label'   => __( 'Middle Footer Background Color', 'lavish-date' ),
    		'section' => 'footer_color_setting'			
    	) ) );
        
        $wp_customize->add_setting( 'buttomfooter_bk_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'buttomfooter_bk_color', array(
    		'label'   => __( 'Buttom Footer Background Color', 'lavish-date' ),
    		'section' => 'footer_color_setting'			
    	) ) );
        
        // Footer color option
        $wp_customize->add_section( 'members_color_setting', array(
            'title'          => __( 'Member Menu Colors', 'lavish-date' ),
            'panel'          => 'typography_setting_panel',
            'priority'       => 5,
        ));
        
         $wp_customize->add_setting( 'member_menu_border_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'member_menu_border_color', array(
    		'label'   => __( 'Members Menu Border Color', 'lavish-date' ),
    		'section' => 'members_color_setting'			
    	) ) );
        
        $wp_customize->add_setting( 'member_menu_background_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'member_menu_background_color', array(
    		'label'   => __( 'Members Menu Background Color', 'lavish-date' ),
    		'section' => 'members_color_setting'			
    	) ) );
        
        $wp_customize->add_setting( 'member_menu_hover_color', array(
    		'default'        => '',
    		'sanitize_callback' => 'lavish_date_sanitize_hex_color',
    	) );
    	
    	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'member_menu_hover_color', array(
    		'label'   => __( 'Members Menu hover Color', 'lavish-date' ),
    		'section' => 'members_color_setting'			
    	) ) );
        

        /*
        =================================================
        Footer COpyright Text
        =================================================
        */
        $wp_customize->add_section( 'footer_copyright', array(
            'title'          => __( 'Copyright Name', 'lavish-date' ),
            'priority'       => 6,
        ));

        // Setting group for Twitter
        $wp_customize->add_setting( 'footer_copyright_text', array(
            'default'        => '',
        ) );

        $wp_customize->add_control( 'footer_copyright_text', array(
            'settings' => 'footer_copyright_text',
            'label'    => __( 'Copyright Text', 'lavish-date' ),
            'section'  => 'footer_copyright',
            'type'     => 'text',
            'priority' => 2,
        ));


        /**
         * adds sanitization callback function for the logo style : radio
         * @package flat_responsive
         */
        function blankpress_sanitize_logo_style($input)
        {
            $valid = array(
                'default' => __('Default Logo', 'lavish-date'),
                'custom' => __('Your Logo', 'lavish-date'),
                'logotext' => __('Logo with Title and Tagline', 'lavish-date'),
                'text' => __('Text Title', 'lavish-date'),
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

  @package lavish  Special thanks to: https://github.com/chrisakelley

 */

add_filter( 'blankpress_sanitize_image', 'blankpress_sanitize_upload' );

add_filter( 'blankpress_sanitize_file', 'blankpress_sanitize_upload' );


/**
 * adds sanitization callback function : colors
 * @package lavish_dating 
*/
	function lavish_date_sanitize_hex_color( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) )
		return '#' . $unhashed;

	return $color;
}

function blankpress_sanitize_upload( $input ) {



        $output = '';



        $filetype = wp_check_filetype($input);



        if ( $filetype["ext"] ) {



                $output = $input;



        }



        return $output;



}



    }

    function mytheme_customize_css()

    {

        ?>

             <style type="text/css">

                .footer-social-icon ul li a i { color:<?php echo get_theme_mod('social_uid_color'); ?>; background-color:<?php echo get_theme_mod('social_uid_bg_color'); ?>;}

                .footer-social-icon ul li a i:hover { color:<?php echo get_theme_mod('social_uid_hover_color'); ?>; background-color:<?php echo get_theme_mod('social_uid_bg_hover_color'); ?>;}

             </style>

        <?php

    }

    add_action( 'wp_head', 'mytheme_customize_css');