<?php

/*-------------------------------------------------

	BlankPress - Slider

 --------------------------------------------------*/


/* blankpres_slider class */

if (!class_exists('blankpres_slider')):



    class blankpres_slider
    {

        private $defaults = array(

            /*"image_width"		=> 960,

              "image_height"	=> 440,*/


            "init_script" => "$('#camera_wrap_2').camera({

						 	 	height: '30%',

								thumbnails: false

							});"

        );

        private $slider_id;

        private static $uploadUrl;

        private static $uploadDir;


        function __construct()
        {

            $upload_dir = wp_upload_dir();

            self::$uploadDir = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'bpslider' . DIRECTORY_SEPARATOR;

            self::$uploadUrl = $upload_dir['baseurl'] . DIRECTORY_SEPARATOR . 'bpslider' . DIRECTORY_SEPARATOR;

            if (!file_exists(self::$uploadDir)) {

                mkdir(self::$uploadDir);

                chmod(self::$uploadDir, 0777);

            }

            add_action('init', array(&$this, 'create_slider_post_type'));

            add_action('do_meta_boxes', array(&$this, 'slider_image_box'));

            add_action('do_meta_boxes', array(&$this, 'add_slider_meta_box'));

            add_action('do_meta_boxes', array(&$this, 'add_shortcode_info'));

            add_action('admin_head', array(&$this, 'add_slider_icon'));

            add_action('wp_enqueue_scripts', array(&$this, 'wp_enqueue_scripts_styles'));

            add_filter('enter_title_here', array(&$this, 'change_default_title'));

            add_action('admin_menu', array(&$this, 'slider_enable_pages'));

            add_action('save_post', array(&$this, 'save_slider_custom_fields'));

            add_shortcode('WPDating_slider', array(&$this, 'WPDating_slider'));

            add_filter('manage_slider_posts_columns', array(&$this, 'slider_columns_head'));

            add_action('manage_slider_posts_custom_column', array(&$this, 'slider_columns_content'), 10, 2);

            add_filter('wp_handle_upload_prefilter', array(&$this, 'wpdating_handle_upload_prefilter'));

            add_filter('wp_handle_upload', array(&$this, 'wpdating_handle_upload'));

        }


        public function wpdating_handle_upload_prefilter($file)
        {

            add_filter('upload_dir', array(&$this, 'wpdating_custom_upload_dir'));

            return $file;

        }


        public function wpdating_handle_upload($fileinfo)
        {

            remove_filter('upload_dir', array(&$this, 'wpdating_custom_upload_dir'));

            return $fileinfo;

        }


        public function wpdating_custom_upload_dir($args)
        {

            if (isset($_POST['html-upload']) && $_POST['html-upload'] == 'Upload') {

                $args['path'] = $args['basedir'] . DIRECTORY_SEPARATOR . "bpslider";

                $args['url'] = $args['baseurl'] . DIRECTORY_SEPARATOR . "bpslider";

            }

            return $args;

        }


        public function create_slider_post_type()
        {


            register_post_type('slider',

                array(

                    'exclude_from_search' => false,
                    'labels' => array(

                        'name' => __('WP Dating Slider', WPDATING_DOMAIN),
                        'singular_name' => __('WPDating Slider', WPDATING_DOMAIN),
                        'add_new' => __('Add New', WPDATING_DOMAIN),
                        'add_new_item' => __('Add New Slider', WPDATING_DOMAIN),
                        'edit' => __('Edit', WPDATING_DOMAIN),
                        'edit_item' => __('Edit WPDating Slider', WPDATING_DOMAIN),
                        'new_item' => __('New WPDating Slider', WPDATING_DOMAIN),
                        'view' => __('View WPDating Slider', WPDATING_DOMAIN),
                        'view_item' => __('View WPDating Slider', WPDATING_DOMAIN),
                        'search_items' => __('Search WPDating Slider', WPDATING_DOMAIN),
                        'not_found' => __('No WPDating sliders found', WPDATING_DOMAIN),
                        'not_found_in_trash' => __('No WPDating sliders found in Trash', WPDATING_DOMAIN)

                    ),
                    'public' => true,
                    'hierarchical' => true, // allow posts to behave like Hierarchy Pages

                    'has_archive' => true,
                    'supports' => array(    // support title, editor, excerpt and thumbnail

                        'title',
                        //'editor',

                        //'excerpt',

                        'thumbnail'

                    ),
                    'can_export' => true // allow export in Tools > Export

                ));

        }


        public function add_slider_icon()
        { ?>

            <style type="text/css" media="screen">

                #adminmenu #menu-posts-slider div.wp-menu-image {

                    background: url("<?php echo IMG_URL.'bpslider-icon.png'; ?>") no-repeat 6px 6px /50% !important;

                }

                #adminmenu #menu-posts-slider div.wp-menu-image:before {

                    content: "";

                }

            </style>

        <?php

        }


        public function wp_enqueue_scripts_styles()
        {

        }


        public function slider_enable_pages()
        {

            add_submenu_page(

                'edit.php?post_type=slider', //$parent_slug

                'WPDating Slider Settings', //$page_title

                'Slider Settings', //$menu_title

                'manage_options', //$capability

                WPDATING_DOMAIN . '-settings', //$menu_slug

                array(&$this, 'slider_settings') //$function

            );

        }


        public function slider_settings()
        {

            echo '<div id="icon-edit" class="icon32 icon32-posts-slider"><br></div>';

            echo '<h2>WPDating Slider Settings</h2>';

            echo "I swear I'll add settings next time.";

            //echo WPDATING_DOMAIN.'_options';

        }


        public function change_default_title($title)
        {

            $screen = get_current_screen();

            if ('slider' == $screen->post_type) {

                $title = 'Enter slider title';

            }

            return $title;

        }


        public function slider_image_box()
        {

            remove_meta_box('postimagediv', 'slider', 'side');

            //add_meta_box('postimagediv', __('Slider Image'), 'post_thumbnail_meta_box', 'slider', 'normal', 'high');

            add_meta_box(

                "blankpress_gallery",

                __("Add Slides", WPDATING_DOMAIN),

                array(&$this, "display_slides_panel"),

                "slider",

                "advanced",

                "default"

            );

        }


        public function add_slider_meta_box()
        {

            add_meta_box(

                "blankpress_slider",                // HTML id  attribute of the edit screen section

                __("Slider Configuration", WPDATING_DOMAIN),                // title of the edit screen section

                array(&$this, "display_slider_metaboxes"),    //callback function that prints html

                "slider",                // post type on which to show edit screen

                "advanced",                    // context - part of page where to show the edit screen

                "default"                        // priority where the boxes should show

            );

        }


        public function add_shortcode_info()
        {

            add_meta_box(

                "blankpress_shortcode",

                __("Slider Shortcode", WPDATING_DOMAIN),

                array(&$this, "display_shortcode_info"),

                "slider",

                "side",

                "high"

            );

        }


        public function display_shortcode_info()
        {

            global $post;

            echo '<div class="row">';

            echo "<strong>[WPDating_slider id=$post->ID]</strong>";

            echo "<span class='description'>" . __('Use this shortcode to display the slider.', WPDATING_DOMAIN) . "</span>";

            echo '</div>';

        }


        public function display_slides_panel()
        { ?>

            <div class="row tab_content_gallery">

                <ul class="socialmedia">

                    <?php

                    global $post;

                    $slider_data = get_post_meta($post->ID, THEME_SLUG . '_options', true);

                    $slider_image_url = ($slider_data) ? $slider_data['bpslider_imageurl'] : '';

                    if (!empty($slider_image_url)) {

                        $slider_link = $slider_data['bpslider_link'];

                        $slider_text = $slider_data['bpslider_text'];

                        $slider_button = $slider_data['bpslider_button'];

                        $slider_buttontext = $slider_data['bpslider_buttontext'];


                        for ($i = 0; $i < count($slider_image_url); $i++) { ?>

                            <li>

                                <div class="banner-admin-left">

                                    <img class="media_image <?php if ($slider_image_url[$i] != '') {
                                        echo 'no_background';
                                    } ?> " src="<?php echo $slider_image_url[$i]; ?>">

                                </div>

                                <div class="banner-admin-right">

                                    <label class="banner-admin-right-label">Banner Image Path</label>

                                    <input type="text" name="bpslider_imageurl[]"
                                           value="<?php echo $slider_image_url[$i]; ?>"
                                           class="media_imageurl bpslider_imageurl"/>

                                    <label class="banner-admin-right-label">Banner Heading</label>

                                    <input type="text" name="bpslider_link[]" value="<?php echo $slider_link[$i]; ?>"
                                           class="bpslider_link"/>

                                    <a href="#" title="remove" class="pkg_option_remove"><span>x</span></a>

                                    <label class="banner-admin-right-label">Banner Text</label>

                                    <input type="text" name="bpslider_text[]" value="<?php echo $slider_text[$i]; ?>"
                                           class="bpslider_text"/>

                                    <label class="banner-admin-right-label">Banner Button Link</label>

                                    <input type="text" name="bpslider_button[]"
                                           value="<?php echo $slider_button[$i]; ?>" class="bpslider_button"/>

                                    <label class="banner-admin-right-label">Banner Button Text</label>

                                    <input type="text" name="bpslider_buttontext[]"
                                           value="<?php echo $slider_buttontext[$i]; ?>" class="bpslider_buttontext"/>

                                </div>

                            </li>

                        <?php }

                    } ?>

                </ul>


                <a class="pkg_media_add" href="#"><span>+</span> <?php _e('add more'); ?></a>


            </div>

        <?php }

        /*

          public function display_slides_panel() { ?>

            <div class="row tab_content_gallery">

            <ul class="socialmedia">

                <?php

                global $post;

            $slider_data = get_post_meta($post->ID, THEME_SLUG.'_options', true);

                $slider_image_url = ($slider_data)? $slider_data['bpslider_imageurl'] : '';

                if(!empty($slider_image_url)){

              $slider_link =  $slider_data['bpslider_link'];

              $slider_text =  $slider_data['bpslider_text'];

                    for($i=0; $i< count($slider_image_url); $i++ ){ ?>

                        <li>

                <img class="media_image <?php if($slider_image_url[$i] != '') echo 'no_background'; ?> " src="<?php echo $slider_image_url[$i]; ?>">

                <input type="text" name ="bpslider_imageurl[]" value="<?php echo $slider_image_url[$i]; ?>" class="media_imageurl bpslider_imageurl" />

                <input type="text" name ="bpslider_link[]" value="<?php echo $slider_link[$i]; ?>" class="bpslider_link" />

                <a href="#" title="remove" class="pkg_option_remove"><span>x</span></a>

                <input type="text" name="bpslider_text[]"  value="<?php echo $slider_text[$i]; ?>" class="bpslider_text" />

                        </li>

                    <?php }

                } ?>

            </ul>

            <span class="description">

            <?php echo __("click on image to upload image or add image link directly on first box, add url on second box , and text on the third box", BP_DOMAIN); ?></span>

            <a class="pkg_media_add" href="#"><span>+</span> <?php _e('add more'); ?></a>



            </div>

        <?php } */


        public function display_slider_metaboxes()
        {

            global $post;

            $options = get_post_meta($post->ID, THEME_SLUG . '_options', true);

            $init_script = isset($options['init_script']) ? stripslashes($options['init_script']) : $this->defaults['init_script'];

            $width = isset($options['image_width']) ? $options['image_width'] : $this->defaults['image_width'];

            $height = isset($options['image_height']) ? $options['image_height'] : $this->defaults['image_height'];

            ?>

            <div class="row">

                <input type="number" name="image_width" value="<?php echo $width; ?>"/>

                <input type="number" name="image_height" value="<?php echo $height; ?>"/>

				<span
                    class="description"> <?php echo __("Set the thumbnail width and height.", WPDATING_DOMAIN); ?></span>

                <textarea name="init_script" class="bpslider_config"><?php echo $init_script; ?></textarea>

				<span
                    class="description"> <?php echo __("Edit the script to initialize the slider or leave as is.", WPDATING_DOMAIN); ?></span>

            </div>

            <?php

            //echo '<pre>'; print_r($options); echo '</pre>';

        }


        public function save_slider_custom_fields()
        {

            global $post;

            $options = array();

            /*print_r($_POST);die;*/

            if ($post) {

                $fields = array(
                    'bpslider_imageurl',
                    'bpslider_link',
                    'bpslider_text',
                    'bpslider_button',
                    'bpslider_buttontext',
                    'init_script',
                    'image_width',
                    'image_height'
                );

                foreach ($_POST as $key => $value) {

                    if (in_array($key, $fields)) {
                        $options[$key] = $value;
                    }

                }

                update_post_meta($post->ID, THEME_SLUG . '_options', $options);


            }

        }


        public function WPDating_slider($atts, $content = null)
        {

            extract(shortcode_atts(array(

                'id' => ''

            ), $atts));

            ob_start();


            $slider_data = get_post_meta($id, THEME_SLUG . '_options', true);

            if (!$slider_data) {
                return;
            }


            $slider_data = array_merge($this->defaults, $slider_data);

            $slider_image_url = $slider_data['bpslider_imageurl'];


            $this->slider_id = $id;

            add_action('wp_footer', array($this, 'add_slider_script'), 99);


            //var_dump($slider_data);

            if (!empty($slider_image_url)) {

                $slider_link = $slider_data['bpslider_link'];

                $slider_text = $slider_data['bpslider_text'];

                $slider_button = $slider_data['bpslider_button'];

                $slider_buttontext = $slider_data['bpslider_buttontext'];

                echo '<div class="bp_slider camera_wrap camera_magenta_skin" id="camera_wrap_2">';

                /*echo '<ul class="slides">';

                  for($i=0; $i< count($slider_image_url); $i++ ){

                    $size = array($slider_data['image_width'], $slider_data['image_height']);

                    echo '<li>';

                      $image_url = $this->get_slide_image_from_url($slider_image_url[$i], $size);

                      echo "<img src='$image_url' alt='$slider_text[$i]' >";

                      if($slider_text[$i] != '') echo "<div class='caption fadeInRight animated hiddenee'>";

                        if($slider_link[$i] != '')  echo "<a href='".$slider_link[$i]."'>";

                          echo $slider_text[$i];

                        if($slider_link[$i] != '')  echo "</a>";

                      if($slider_text[$i] != '') echo "</div>";

                            echo '</li>';

                  }

                echo '</ul>';*/

                for ($i = 0; $i < count($slider_image_url); $i++) {

                    $size = array($slider_data['image_width'], $slider_data['image_height']);

                    $image_url = $this->get_slide_image_from_url($slider_image_url[$i], $size);
                    /**
                     * Hide box in the slider when there is no button text
                     *
                     */
                    if ($slider_buttontext[$i] == "") {
                        echo "<div data-src='{$image_url}'><div class='camera_caption fadeFromBottom'><h3>" . __($slider_link[$i]) . "</h3><p>" . __($slider_text[$i]) . "</p>
				<a href='" . $slider_button[$i] . "'>" . $slider_buttontext[$i] . "</a></div></div>";
                    } else {
                        echo "<div data-src='{$image_url}'><div class='camera_caption fadeFromBottom' id='box'><h3>" . __($slider_link[$i]) . "</h3><p>" . __($slider_text[$i]) . "</p>

                <a href='" . $slider_button[$i] . "'>" . __($slider_buttontext[$i]) . "</a></div></div>";
                    }
                }
                echo '</div>';
            }
            $content = ob_get_contents();

            ob_end_clean();

            return $content;

        }


        public function add_slider_script()
        {


            $slider_data = get_post_meta($this->slider_id, THEME_SLUG . '_options', true);

            echo "<script  type='text/javascript'>

          jQuery(document).ready(function($) {

            " . $slider_data['init_script'] . "

          });

        </script>";

        }


        public function slider_columns_head($defaults)
        {

            $defaults['slider_shortcode'] = 'Slider Shortcode';

            return $defaults;

        }


        public function slider_columns_content($column_name, $post_ID)
        {

            if ($column_name == 'slider_shortcode') {

                echo "<strong>[WPDating_slider id=$post_ID]</strong>";


            }

        }


        public function get_slide_image_from_url($url, $size)
        {


            $image_parts = explode('/', $url);

            $name_parts = explode('.', end($image_parts));

            $file_abs = self::$uploadDir . $name_parts[0] . '-' . $size[0] . 'x' . $size[1] . '.' . $name_parts[1];

            $file_rel = self::$uploadUrl . $name_parts[0] . '-' . $size[0] . 'x' . $size[1] . '.' . $name_parts[1];
            
            $upload_dir = wp_upload_dir();
            
            $image_path = $upload_dir['basedir'] . DIRECTORY_SEPARATOR . $name_parts[0] .'.'. $name_parts[1];

            if (!file_exists($file_abs)) {

                //$image = wp_get_image_editor($url);
                
                $image = wp_get_image_editor($image_path);

                if (!is_wp_error($image)) {

                    $image->resize($size[0], $size[1], true);

                    $image->save($file_abs);

                }

            }


            return $file_rel;

        }


    }



endif;


new blankpres_slider();