<?php
/*
=================================================
Widget That helps users to Show the Happy Stories of Members
=================================================
*/

class shk_blog_blog_contents extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		parent::__construct( 
			'widget_shk_blog_blog_contents',
			__( 'Lavish Blog HomePage Widget', 'lavish-date' ),
			array(
				'classname'   => 'widget_shk_blog_blog_contents',
				'description' => __( 'A custom widget that display Blogs On Front End.', 'lavish-date' )
			) 
		);
	}


	/**
	 * Generates the back-end layout for the widget
	 */

	public function form( $instance ) {
		// Default widget settings
		$members_online = array(
			'title'               => 'Recent Blogs',
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
			echo '<h2 style="text-align:center; margin-bottom:30px;">'.$title.'</h2><br/>';
		}	
		echo '<div class="lavish_date_blog_front">';
		$args = array( 'numberposts' => '3' );
			$recent_posts = wp_get_recent_posts( $args );
				foreach( $recent_posts as $recent ){
				echo '<div class="lavish_blog_widget_front dsp-md-4">';
				

					echo '<div class="lavish_blog_widget_front_thumbnail">';

						echo '<a href="'. get_permalink($recent["ID"]) .'">';

                               	echo get_the_post_thumbnail($recent["ID"], 'image43'); 
                        echo '</a>';
                    echo '</div>';
                	echo '<div class="lavish_blog_widget_inside">';
					echo '<span class="lavish_blog_widget_date"><i class="fa fa-clock-o"></i>&nbsp;'. mysql2date('j M Y',$recent["post_date"]).' &nbsp;/ &nbsp;<i class="fa fa-user"></i>&nbsp; '. get_the_author_meta('display_name', $recent["post_author"]) .'</span>';
					echo '<h5><a href="' . get_permalink($recent["ID"]) . '" title="Blog '.esc_attr($recent["post_title"]).'" >' .  wp_trim_words($recent["post_title"],5,'...') .'</a></h5><hr/>';
					echo '<div class="lavish_blog_widget_excerpt">'. wp_trim_words($recent["post_content"],10, '...') .'</div><br/>';
					echo '<a href="'. get_permalink($recent["ID"]) .'" class="btn btn-sm">'.__('Read More', 'wpdating') . '<i class="fa fa-angle-double-right"></i></a>';
					echo '</div>';
				echo '</div>';
				}
		echo '</div>';
		echo '<div style="clear:both;"></div>';
		// Display the markup after the widget (as defined in functions.php)
		echo $after_widget;
	
	}
}

// Register the widget using an annonymous function
function new_blog_blog_contents(){
    register_widget( 'shk_blog_blog_contents');
}
add_action('widgets_init', 'new_blog_blog_contents')
?>