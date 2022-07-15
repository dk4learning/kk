<?php
/*
=================================================
Widget That helps users to carasaul their contents
on front page or any pages that they want.
=================================================
*/

class wpdating_featured_members_carousel extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		parent::__construct(
			'widget_lavish_date_featured_members_carousel',
			__( 'WPDating Featured Members Carousel', 'lavish-date' ),
			array(
				'classname'   => 'widget_lavish_date_featured_members_carousel',
				'description' => __( 'A custom widget that displays Carousel of Featured Users.', 'lavish-date' )
			)
		);
	}


	/**
	 * Generates the back-end layout for the widget
	 */

	public function form( $instance ) {
		// Default widget settings
		$members_carousel = array(
			'title'               => 'Featured Members',
		);

		$instance = wp_parse_args( (array) $instance, $members_carousel);

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
		if ($title) {?>
            <div class="dsp-heading-block text-center dsp-md-12">

				<h2><span><?php echo $title; ?></span></h2>

			</div>

<?php
		}
		echo '
			
				<div class="clearfix">';
                    global $wpdb;
                    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
                    $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
					$new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles_table profile INNER JOIN $dsp_users_table user ON profile.user_id=user.ID WHERE profile.featured_member > 0 AND profile.featured_expiration_date>CURDATE() ORDER BY profile.featured_member DESC");
					
					echo '<div class="dsp-new-member-slider jcarousel-wrapper jcarousel-wrapper-frontpage">
		                <div class="jcarousel">
		                    <ul class="slides">
						';
						$imagepath = get_option('siteurl').'/wp-content/';  // image Path
						$i = 0;
						foreach($new_members as $mem){
							$user_id = $mem->user_id;
							$username = get_userdata($user_id);
							$name = substr($username->display_name,0,7);
							$gender = $mem->gender;
							$age = $mem->age;
							$country = $mem->country_id;
						echo '<li>
							<div class="dsp-md-2 dsp-home-member dsp-sm-3">

								<div class="member-image">
									<a href="'. home_url() .'/members/'. get_username($mem->user_id) .'">
										<img src="'. display_members_photo($user_id, $imagepath) .'"/>
									</a>
								</div>
								<div class="members-details">
								<a href="'. home_url() .'/members/'. get_username($mem->user_id) .'" class="db-member-title">'. $name .'</a>
								</div>
								<div class="cara_members_age">
									<span>'. dsp_get_age($age).'&nbsp'. language_code( 'DSP_YEARS_OLD' ) .'
								</span>
								</div>
								</div>
						</li>';
					}
					echo '</ul></div></div></div>';
		// Display the markup after the widget (as defined in functions.php)
		echo $after_widget;
	}
}

// Register the widget using an annonymous function
function featured_members_carousel(){
   return register_widget( "wpdating_featured_members_carousel" );
}
add_action('widgets_init', 'featured_members_carousel');
?>