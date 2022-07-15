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
            __('WPDating Meet Me', 'WPDATING_DOMAIN'),

// Widget description
            array('description' => __('Meet Me', 'WPDATING_DOMAIN'),)
        );
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance)
    {
        $page_url = get('pagetitle');
        if ($page_url != 'meet_me'){
            $title = apply_filters('widget_title', $instance['title']);
            // before and after widget arguments are defined by themes
            echo $args['before_widget'];
            if (!empty($title))
                echo $args['before_title'] . $title . $args['after_title'];

            // This is where you run the code and display the output
            echo $args['after_widget'];

            $this->meet_me();
        }
    }

// Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'WPDATING_DOMAIN');
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
