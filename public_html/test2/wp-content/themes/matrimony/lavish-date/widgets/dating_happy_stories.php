<?php
/*
=================================================
Widget That helps users to Show the Happy Stories of Members
=================================================
*/

class lavish_data_happy_stories extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		parent::__construct( 
			'widget_lavish_date_happy_stories',
			__( 'Lavish Happy Stories', 'lavish-date' ),
			array(
				'classname'   => 'widget_lavish_date_happy_stories',
				'description' => __( 'A custom widget that display Happy Stories of Users.', 'lavish-date' )
			) 
		);
	}


	/**
	 * Generates the back-end layout for the widget
	 */

	public function form( $instance ) {
		// Default widget settings
		$members_online = array(
			'title'               => 'Happy Stories',
		);

		$instance = wp_parse_args( (array) $instance, $members_online);

		// The widget content ?>
		<!-- Title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'lavish' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<?php
	}


	/**
	 * Processes the widget's values
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Update values
		$instance['title']               = strip_tags( stripslashes( $new_instance['title'] ) );
		return $instance;
	}


	/**
	 * Output the contents of the widget
	 */
	public function widget( $args, $instance ) {
		// Extract the arguments
		extract( $args );

		$title              = apply_filters( 'widget_title', $instance['title'] );
	

		// Display the markup before the widget (as defined in functions.php)
		echo $before_widget;
		if ($title) {
			echo '<h2 style="text-align:center; margin-bottom:30px;">'.$title.'</h2>';
		}	
			global $wpdb;
			$dsp_stories= $wpdb->prefix.DSP_STORIES_TABLE;
			$story_result=$wpdb->get_results("select * from $dsp_stories order by date_added desc");

			if(count($story_result) > 0) {
				foreach(array_slice($story_result, 0 ,3) as $story_row) {
					echo '<div class="dsp-md-4 dsp_lavish_stories">
						<div class="dsp_lavish_stories_inside">
						<a class="dsp_lavish_stories_img ">
							<img src="'. get_bloginfo('url') .'/wp-content/uploads/dsp_media/story_images/thumb_'. $story_row->story_image .'" alt="">
						</a>
						<div class="lavish_date_happy_stories_content">
						 	<h5><a>'. wp_trim_words(stripslashes($story_row->story_title), 5 , '...') .'</a></h5><hr/>
						 	<p>
						 		';
						 		$story_content = ltrim(str_replace('\\','', $story_row->story_content));
								echo wp_trim_words($story_content,15,'...')
								.'
						 	</p>
						</div>
						</div>
					</div>';
				}
				echo '<div style="clear:both"></div><br/>';
				echo '<div style="text-align:center">';
				if ( is_user_logged_in() ) {
					echo '<a href="'. get_bloginfo('url').'/members/stories" class="btn">'. __('List All Stories', 'wpdating') .'</a>';
				}
				else {
					echo '<a href="'. get_bloginfo('url') .'/members/stories" class="btn">'. __('List All Stories', 'wpdating') .'</a>';
				}
				echo '</div>';
				
			}
			else {
				echo __('There are no any Happy Stories To Show', 'lavish-date');
			}


			

		
		// Display the markup after the widget (as defined in functions.php)
		echo $after_widget;
	
	}
}

// Register the widget using an annonymous function
function lavish_new_data_stories(){
    register_widget( 'lavish_data_happy_stories');
}
add_action('widgets_init', 'lavish_new_data_stories');
?>