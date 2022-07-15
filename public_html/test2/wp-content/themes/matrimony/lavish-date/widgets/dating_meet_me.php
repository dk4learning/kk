<?php

// Creating the widget
class wpdating_meet_me extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
// Base ID of your widget
            'wpdating_meet_me',

// Widget name will appear in UI
            __('WPDating Meet Me', 'lavish-date'),

// Widget description
            array('description' => __('Meet Me', 'lavish-date'),)
        );
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
// before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
//        echo __('Hello, World!', 'wpb_widget_domain');
        echo $args['after_widget'];
        $this->meet_me();
    }

// Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Meet Me', 'lavish-date');
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
    <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    public function meet_me()
    {
        $meet_me_request = WPDATE_URL . "dsp_meet_me_request.php";
        ?>
        <div id="dsp_meet_me_box_widget">
            <?php
            global $current_user;
            $user_id = $current_user->ID;
            $meet_me_content = wp_remote_get(WPDATE_URL . "dsp_meet_me_box.php?user_id=$user_id");
            $meet_me_content = array_key_exists('body', $meet_me_content) ? $meet_me_content['body'] : '';
            echo $meet_me_content;
            ?>
        </div>
        <script>
            jQuery(document).ready(function () {
                var meetMeFn = function () {

                    var value = jQuery(this).val();
                    jQuery("#dsp_meet_me_box_widget").fadeOut();
                    var user_id = jQuery("#dsp_meet_me_user").val();

                    jQuery.ajax({
                        url: "<?php echo $meet_me_request ?>?user_id=" + user_id + "&action=" + value,
                        cache: false,
                        success: function (html) {
                            jQuery("#dsp_meet_me_box_widget").load("<?php echo WPDATE_URL . "dsp_meet_me_box.php?user_id=$user_id"; ?>");
                            jQuery("#dsp_meet_me_box_widget").fadeIn();
                        }
                    });
                };
                jQuery('#dsp_meet_me_click').live('click', meetMeFn);

            });
        </script>
    <?php


    }
}

// Class wpb_widget ends here


// Register and load the widget
function wpdating_meet_me_widget()
{
    register_widget('wpdating_meet_me');
}

add_action('widgets_init', 'wpdating_meet_me_widget');
