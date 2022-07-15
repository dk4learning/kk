<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings           = array(
  'menu_title'      => 'Premium Options',
  'menu_type' => 'submenu',
  'menu_parent' => 'themes.php', // menu, submenu, options, theme, etc.
  'menu_slug'       => 'wpdating_premium',
  'ajax_save'       => false,
  'show_reset_all'  => false,
  'framework_title' => 'Premium Theme <small>by Digital Product labs</small>',
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options        = array();

/** 
     * General Settings
     */

    $options[] = array(
      'name' => 'general',
      'title' => esc_html__( 'General Settings', 'wpdating_premium' ),
      'icon' => 'fa fa-cog',
      'fields' => array(

          // color options
          // array(
          //     'type' => 'subheading',
          //     'content' => esc_html__( 'Theme Color Scheme', 'wpdating_premium' ),
          // ),

          array(
              'type'    => 'color_picker',
              'id'      => 'primary_color',
              'title'   => esc_html__( 'Primary Color', 'wpdating_premium' ),
              'default'   => '#DF1756',
          ),

          // array(
          //     'type'    => 'color_picker',
          //     'id'      => 'secondary_color',
          //     'title'   => esc_html__( 'Secondary Color', 'wpdating_premium' ),
          //     'default'   => '#5E3DBF',
          // ),
           array(
            'type'    => 'color_picker',
            'id'      => 'body_color',
            'title'   => esc_html__( 'Body Color', 'wpdating_premium' ),
            'default' => '#343434',
            ),
            array(
                'type'    => 'color_picker',
                'id'      => 'heading_color',
                'title'   => esc_html__( 'Heading Color', 'wpdating_premium' ),
                'default' => '#000000',
            ),

          // other settings
          array(
              'type' => 'subheading',
              'content' => esc_html__( 'Logo Options', 'wpdating_premium' ),
          ),

          array(
              'id' => 'default-logo',
              'type' => 'image',
              'title' => esc_html__( 'Default Logo', 'wpdating_premium' ),
              'desc' => esc_html__( 'Non retina version', 'wpdating_premium' ),
          ),

          array(
              'id' => 'retina-logo',
              'type' => 'image',
              'title' => esc_html__( 'Retina Logo', 'wpdating_premium' ),
              'desc' => esc_html__( 'Retina version', 'wpdating_premium' ),
          ),

          array(
              'type' => 'subheading',
              'content' => esc_html__( 'Other Settings', 'wpdating_premium' ),
          ),

          array(
              'id' => 'back-to-top',
              'type' => 'switcher',
              'title' => esc_html__( 'Back to Top', 'wpdating_premium' ),
              'desc' => esc_html__( 'Enable or disable to top', 'wpdating_premium' ),
              'default' => 1
          ),
      )
  );

  /**
   * Typography Options
   */
  $options[] = array(
      'name' => 'typography',
      'title' => esc_html__( 'Typography Options', 'wpdating_premium' ),
      'icon' => 'fa fa-font',
      'fields' => array(
          
          array(
              'id'        => 'body-font',
              'type' => 'typography',
              'title'     => esc_html__('Body Font-family', 'wpdating_premium'),
              'default'   => array(
                'family'  => 'Nunito, sans-serif',
                'font'    => 'google',
                'size'    => '16',
                'color'   => '#5c5f6d',
                'unit'        => 'px'
              ),
              'height' => false,
              'chosen'    => true,
              'preview'   => true,
            ),



          array(
              'id' => 'heading-font',
              'type' => 'typography',
              'title' => esc_html__( 'Heading Font-family', 'wpdating_premium' ),
              'default' => array(
                'family' => 'Libre Franklin, sans-serif',
                  'font' => 'google',
                  'size'    => '16',
                  'color' => '#151514',
                  'unit'        => 'px'
              ),
              'variant' => true,
              'chosen'    => true,
              'size'       => false,
              'preview'   => true,
          ),



          array(
              'type' => 'subheading',
              'content' => esc_html__( 'Font size', 'wpdating_premium' ),
          ),

          array(
              'id' => 'body-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'Body font size','wpdating_premium' ),
              'default' => '16'
          ),

          array(
              'id' => 'h1-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H1 font size','wpdating_premium' ),
              'default' => '55'
          ),

          array(
              'id' => 'h2-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H2 font size', 'wpdating_premium' ),
              'default' => '40',
          ),

          array(
              'id' => 'h3-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H3 font size', 'wpdating_premium' ),
              'default' => '36',
          ),

          array(
              'id' => 'h4-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H4 font size', 'wpdating_premium' ),
              'default' => '30',
          ),

          array(
              'id' => 'h5-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H5 font size', 'wpdating_premium' ),
              'default' => '26',
          ),

          array(
              'id' => 'h6-font-size',
              'type' => 'text',
              'after' => 'px',
              'title' => esc_html__( 'H6 font size', 'wpdating_premium' ),
              'default' => '20',
          ),

          // array(
          //     'type' => 'subheading',
          //     'content' => esc_html__( 'Color Settings', 'wpdating_premium' ),
          // ),

          // array(
          //     'id' => 'anchor-color',
          //     'type' => 'color_picker',
          //     'title' => esc_html__( 'Anchor color', 'wpdating_premium' ),
          // ),

          // array(
          //     'id' => 'anchor-hover-color',
          //     'type' => 'color_picker',
          //     'title' => esc_html__( 'Anchor hover & active color', 'wpdating_premium' ),
          // ),

      )
  );

  /**
   * Header Options
   */
  // $options[] = array(
  //     'name' => 'header',
  //     'title' => esc_html__( 'Header Settings', 'wpdating_premium' ),
  //     'icon' => 'fa fa-header',
  //     'fields' => array(

  //         array(
  //             'id' => 'top-header',
  //             'type' => 'fieldset',
  //             'title' => esc_html__( 'Top Bar', 'wpdating_premium' ),
  //             'fields' => array(
  //                 array(
  //                     'id' => 'top-bar-switch',
  //                     'type' => 'switcher',
  //                     'desc' => esc_html__( 'Enable or disable top bar in header', 'wpdating_premium' ),
  //                     'default' => 0
  //                 ),

  //                 array(
  //                     'id' => 'top-bar-left',
  //                     'type' => 'wysiwyg',
  //                     'title' => esc_html__( 'Content', 'wpdating_premium' ),
  //                     'settings' => array(
  //                         'media_buttons' => false
  //                     ),
  //                     'default' => '',
  //                     'dependency' => array('top-bar-switch', '==', '1')
  //                 ),

  //                 array(
  //                     'id' => 'top-bar-bg',
  //                     'type' => 'color_picker',
  //                     'title' => esc_html__( 'Bar background color', 'wpdating_premium' ),
  //                     'default' => '#151514',
  //                     'dependency' => array('top-bar-switch', '==', '1')
  //                 ),

  //                 array(
  //                     'id' => 'top-bar-text-color',
  //                     'type' => 'color_picker',
  //                     'title' => esc_html__( 'Bar text color','wpdating_premium' ),
  //                     'default' => '#fff',
  //                     'dependency' => array('top-bar-switch', '==', '1')
  //                 ),
  //             )
  //         ),

  //       //   array(
  //       //       'id'  => 'header-style',
  //       //       'type' => 'image_select',
  //       //       'title' => esc_html__( 'Choose Header Style', 'wpdating_premium' ),
  //       //       'options' => array(
  //       //           'style1' => wpdating_premium_IMAGES . 'preset/header/header2.jpg',
  //       //           'style2' => wpdating_premium_IMAGES . 'preset/header/header1.jpg',
  //       //           'style3' => wpdating_premium_IMAGES . 'preset/header/header3.jpg',
  //       //           'style4' => wpdating_premium_IMAGES . 'preset/header/header4.jpg',
  //       //       ),
  //       //       'default' => 'style1'
  //       //   ),

  //         array(
  //             'id'      => 'header-variation',
  //             'type'    => 'radio',
  //             'title'   => esc_html__( 'Header Variation', 'wpdating_premium' ),
  //             'class'   => 'horizontal',
  //             'options' => array(
  //                 'light'    => 'Light',
  //                 'dark'     => 'Dark',
  //             ),
  //             'default'  => 'dark',
  //             'dependency' => array( 'header-style_style3', '==', 'true' ),
  //         ),

  //         array(
  //             'id' => 'sticky',
  //             'type' => 'switcher',
  //             'title' => esc_html__( 'Sticky Header', 'wpdating_premium' ),
  //             'default' => 0
  //         ),
  //         array(
  //             'id' => 'show_login',
  //             'type' => 'switcher',
  //             'title' => esc_html__( 'Show / Hide Login', 'wpdating_premium' ),
  //             'default' => true
  //         ),
  //     )
  // );

  
  

  /**
   * Blog Options
   */

  $options[] = array(
      'name' => 'blog',
      'title' => esc_html__( 'Blog Settings', 'wpdating_premium' ),
      'icon' => 'fa fa-newspaper-o',
      'fields' => array(
         array(
            'id' => 'blog-list-sidebar-position',
            'type' => 'select',
            'title' => esc_html__( 'Sidebar position', 'wpdating_premium' ),
            'default' => 'right',
            'desc' => esc_html__( 'Sidebar position for archive page', 'wpdating_premium' ),
            'options' => array(
                'right' => esc_html__( 'Right Sidebar', 'wpdating_premium' ),
                'left' => esc_html__( 'Left Sidebar', 'wpdating_premium' ),
                'full' => esc_html__( 'no-sidebar', 'wpdating_premium' )
            ),
        ),
    )
  );

  //         array(
  //             'id' => 'blog-title-fieldset',
  //             'type' => 'fieldset',
  //             'title' => esc_html__( 'Blog', 'wpdating_premium' ),
  //             'fields' => array(
  //                 array(
  //                     'id' => 'blog-title-bar',
  //                     'type' => 'switcher',
  //                     'title' => esc_html__( 'Title Bar', 'wpdating_premium' ),
  //                     'default' => ''
  //                 ),
  //                 array(
  //                     'id' => 'blog-archive-title',
  //                     'type' => 'text',
  //                     'title' => esc_html__( 'Blog archive title', 'wpdating_premium' ),
  //                     'default' => esc_html__( 'Blog', 'wpdating_premium' ),
  //                     'dependency' => array('blog-title-bar', '==', '1')
  //                 ),

  //                 array(
  //                     'id' => 'blog-background',
  //                     'type' => 'background',
  //                     'title' => esc_html__( 'Background', 'wpdating_premium' ),
  //                     'default' => array(
  //                         'image' => '',
  //                         'repeat' => 'repeat-x',
  //                         'position' => 'center center',
  //                         'attachment' => 'fixed',
  //                         'color' => '#ffbc00',
  //                     ),
  //                     'dependency' => array('blog-title-bar', '==', '1')
  //                 )
  //             )
  //         ),
  //         array(
  //             'id' => 'blog_layout',
  //             'type' => 'select',
  //             'title' => esc_html__( 'Choose Blog Layout', 'wpdating_premium' ),
  //             'options' => array(
  //                 'classic' => esc_html__( 'Classic', 'wpdating_premium' ),
  //                 'masonry' => esc_html__( 'Masonry', 'wpdating_premium' ),
  //                 'modern' => esc_html__( 'Modern', 'wpdating_premium' ),
  //             ),
  //             'default' => 'classic',
  //         ),

  //          array(
  //             'type' => 'switcher',
  //             'id' => 'featured-post',
  //             'title' => esc_html__( 'Posts Carousel', 'wpdating_premium' ),
  //             'desc' => esc_html__( 'Enable the display of post carousel?', 'wpdating_premium' ),
  //             'dependency' => array( 'blog_layout', '==', 'modern' )
  //         ),

  //         array(
  //             'type' => 'select',
  //             'options' => 'category',
  //             'title' => esc_html__( 'Choose category to display for carousel', 'wpdating_premium' ),
  //             'id' => 'posts_feature',
  //             'dependency' => array( 'blog_layout|featured-post', '==', 'modern|true' )
  //         ),

  //         array(
  //             'id' => 'excerpt-length',
  //             'type' => 'text',
  //             'title' => esc_html__( 'Excerpt Length', 'wpdating_premium' ),
  //             'after' => esc_html__( '(words)', 'wpdating_premium' ),
  //             'default' => '18',
  //             'desc' => ''
  //         ),

  //         array(
  //             'id' => 'pagination-type',
  //             'type' => 'select',
  //             'title' => esc_html__( 'Pagination Type', 'wpdating_premium' ),
  //             'default_option' => esc_html__( 'Select one', 'wpdating_premium' ),
  //             'options' => array(
  //                 'pagination' => esc_html__( 'Pagination', 'wpdating_premium' ),
  //                 'load-more' => esc_html__( 'Load more button', 'wpdating_premium' ),
  //             ),
  //             'default' => 'pagination'
  //         ),

  //         array(
  //             'id' => 'load-more-text',
  //             'type' => 'text',
  //             'title' => esc_html__( 'Button label', 'wpdating_premium' ),
  //             'desc' => esc_html__( 'Load more button label', 'wpdating_premium' ),
  //             'dependency' => array('pagination-type', '==', 'load-more')
  //         ),

  //         array(
  //             'id' => 'post-detail-layout',
  //             'type' => 'radio',
  //             'default' => true,
  //             'options' => array(
  //                 'classic' => esc_html__( 'Classic', 'wpdating_premium' ),
  //                 'modern'  => esc_html__( 'Modern', 'wpdating_premium' ),
  //             ),
  //             'title' => esc_html__( 'Post detail page layout', 'wpdating_premium' ),
  //             'desc' => esc_html__( 'Modern layout will disable sidebar', 'wpdating_premium' ),
  //         ),

  //         array(
  //             'type' => 'notice',
  //             'content' => wp_kses_post( esc_attr__( '<strong>The sidebar options shown below are applicable only in "Classic Blog Layout" and "Classic Post Detail" page layout</strong>', 'wpdating_premium' ) ),
  //             'class' => 'info'
  //         ),

  //         array(
  //             'id' => 'blog-fieldset',
  //             'type' => 'fieldset',
  //             'title' => esc_html__( 'Sidebar', 'wpdating_premium' ),
  //             'fields' => array(
  //                 array(
  //                     'id' => 'blog-list-sidebar-position',
  //                     'type' => 'select',
  //                     'title' => esc_html__( 'Sidebar position', 'wpdating_premium' ),
  //                     'default' => 'right',
  //                     'desc' => esc_html__( 'Sidebar position for archive page', 'wpdating_premium' ),
  //                     'options' => array(
  //                         'left' => esc_html__( 'Left', 'wpdating_premium' ),
  //                         'right' => esc_html__( 'Right', 'wpdating_premium' )
  //                     ),
  //                 ),

  //                 array(
  //                     'id' => 'blog-archive-sidebar-select',
  //                     'type' => 'select',
  //                     'options' => 'sidebar',
  //                     'title' => esc_html__( 'Choose sidebar', 'wpdating_premium' ),
  //                     'default_option' => esc_html__( 'Select one', 'wpdating_premium' ),
  //                     'default' => '',
  //                 ),                    
  //             ),

  //         ),
  //     )
  // );

  /**
   * Footer Settings
   */

  $options[] = array(
      'name'      =>      'footer',
      'title'     =>      esc_html__( 'Footer', 'wpdating_premium' ),
      'icon'      =>      'fa fa-building',
      'fields'        =>      array(
        array(
            'type'    => 'color_picker',
            'id'      => 'Footer_bg',
            'title'   => esc_html__( 'Footer Background', 'wpdating_premium' ),
        ),
        array(
            'type'    => 'color_picker',
            'id'      => 'Footer_text',
            'title'   => esc_html__( 'Footer Text', 'wpdating_premium' ),
        ),
        array(
            'type'    => 'color_picker',
            'id'      => 'Footer_anchor',
            'title'   => esc_html__( 'Footer anchor', 'wpdating_premium' ),
        ),
          array(
              'id' => 'footer-logo',
              'type' => 'image',
              'title' => esc_html__( 'Footer Logo', 'wpdating_premium' ),
              'desc' => esc_html__( 'footer logo', 'wpdating_premium' ),
          ),
          // array(
          //     'id'        =>  'footer-widgets-fieldset',
          //     'type'  =>  'fieldset',
          //     'title' =>  esc_html__( 'Footer Widgets', 'wpdating_premium' ),
          //     'fields'    =>  array(
          //         array(
          //             'id'        =>      'footer-widgets-switcher',
          //             'type'  =>      'switcher',
          //             'default'   =>      '',
          //         ),

          //         array(
          //             'id'    =>  'widgets-column',
          //             'type'  =>  'number',
          //             'title' =>  esc_html__( 'Number of columns', 'wpdating_premium' ),
          //             'default'   =>      '4',
          //             'attributes'    =>  array(
          //                 'max'   =>  4,
          //                 'min'   =>  0
          //             ),
          //             'dependency'    =>  array('footer-widgets-switcher', '==', 1)
          //         )
          //     )
          // ),
          array(
              'id'      => 'google-analytics',
              'type'    => 'textarea',
              'title'   => esc_html__( 'Google Analytics', 'wpdating_premium' ),
              'desc'  =>  esc_html__( 'Place your google analytics code here', 'wpdating_premium' ),
              'before'    =>  esc_html__( 'Do not use &lt;script&gt; &lt;/script&gt; tag, it will be added by default', 'wpdating_premium' ),
              'attributes'    =>  array(
                  'rows'  =>  12,
              ),
              'sanitize'=>    false
          ),


          array(
              'id'        =>  'footer-social',
              'type'  =>  'switcher',
              'title' =>  esc_html__( 'Social Links', 'wpdating_premium' ),
              'desc'  =>  esc_html__( 'Enable or disable social links in footer', 'wpdating_premium' ),
              'default'   =>  1
          ),

          array(
              'id'      => 'footer-copyright',
              'type'    => 'wysiwyg',
              'title'   => esc_html__( 'Copyright message', 'wpdating_premium' ),
              'desc'  =>  wp_kses_post( esc_html__( 'Enter your copyright notice. Shortcode can be used here. Available Shortcodes: [year], [site_name]', 'wpdating_premium' ) ),
              'settings'  =>  array(
                  'textarea_rows' => 10,
                  'tinymce'       => false,
                  'media_buttons' => false,
              ),
              'default'   =>  esc_html__( ' &copy; 2021 Relish Dating. All rights reserved', 'wpdating_premium' ),
          ),
      )
  );

  /**
   * Social Links
   */

  $options[] = array(
      'name'      =>      'social_links',
      'title'     =>      esc_html__( 'Social Media', 'wpdating_premium' ),
      'icon'      =>      'fa fa-globe',
      'fields'        =>      array(

          array(
              'id'        =>  'facebook',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Facebook', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'twitter',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Twitter', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'googleplus',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Google Plus', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'linkedin',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Linkedin', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'youtube',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Youtube', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'vimeo',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Vimeo', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'instagram',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Instagram', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'pinterest',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Pinterest', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'snapchat',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Snapchat', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'dribbble',
              'type'  =>  'text',
              'title' =>  esc_html__( 'Dribbble', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'wordpress',
              'type'  =>  'text',
              'title' =>  esc_html__( 'WordPress', 'wpdating_premium' ),
          ),

          array(
              'id'        =>  'rss',
              'type'  =>  'text',
              'title' =>  esc_html__( 'RSS', 'wpdating_premium' ),
          ),
      )
  );

  /**
   * Custom CSS & JS
   */

  $options[] = array(
      'name'      =>      'css_js',
      'title'     =>      esc_html__( 'Custom CSS & JS', 'wpdating_premium' ),
      'icon'      =>      'fa fa-code',
      'fields'        =>      array(
          array(
              'id'      => 'css',
              'type'    => 'textarea',
              'title'   => esc_html__( 'Custom CSS', 'wpdating_premium' ),
              'desc'  =>  esc_html__( 'All the custom css will be placed within &lt;head&gt; &lt;/head&gt; tag', 'wpdating_premium' ),
              'before'    =>  esc_html__( 'Do not use &lt;style&gt; &lt;/style&gt; tag, it will be added by default', 'wpdating_premium' ),
              'attributes'    =>  array(
                  'rows'  =>  12,
              )
          ),

          array(
              'id'      => 'js',
              'type'    => 'textarea',
              'title'   => esc_html__( 'Custom JS', 'wpdating_premium' ),
              'desc'  =>  esc_html__( 'All the custom js will be placed before closing &lt;/body&gt; tag.', 'wpdating_premium' ),
              'before'    =>  esc_html__( 'Do not use &lt;script&gt; &lt;/script&gt; tag, it will be added by default', 'wpdating_premium' ),
              'attributes'    =>  array(
                  'rows'  =>  12,
              )
          ),
      )
  );



CSFramework::instance( $settings, $options );
