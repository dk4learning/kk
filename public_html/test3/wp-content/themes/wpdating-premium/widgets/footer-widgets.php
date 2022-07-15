<?php

register_sidebar( array(
	'name' => 'Site Information',
	'id' => 'footer-sidebar-1',
	'description' => 'Appears in the footer area',
	) );
	register_sidebar( array(
	'name' => 'Quick Links',
	'id' => 'footer-sidebar-2',
	'description' => 'Appears in the footer area',
	'before_title' => '<h3 class="widget-title footer-title">',
	'after_title' => '</h3>',
	) );
	register_sidebar( array(
	'name' => 'Contact Details widgets',
	'id' => 'footer-sidebar-3',
	'description' => 'Appears in the footer area',
	'before_title' => '<h3 class="widget-title footer-title">',
	'after_title' => '</h3>',
	) );
	register_sidebar( array(
	'name' => 'Subscribe To Newsletter',
	'id' => 'footer-sidebar-4',
	'description' => 'Appears in the footer area',
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<h3 class="widget-title footer-title">',
	'after_title' => '</h3>',
	) );

	
	class wppremium_Text_Widget extends WP_Widget {

		public function __construct() {
	
			parent::__construct(
				'wppremium_text_widget',
				__( 'Contact-details', 'wppremium' ),
				array(
					'description' => __( 'Text or HTML content.', 'wppremium' ),
				)
			);
	
		}
	
		public function widget( $args, $instance ) {
	
			$title = apply_filters( 'widget_title', empty( $instance['wppremium_title'] ) ? '' : $instance['wppremium_title'], $instance, $this->id_base );
			$location  = apply_filters( 'widget_text',  empty( $instance['wppremium_location']  ) ? '' : $instance['wppremium_location'],  $instance );
			$phone  = apply_filters( 'widget_text',  empty( $instance['wppremium_phone']  ) ? '' : $instance['wppremium_phone'],  $instance );
			$email  = apply_filters( 'widget_text',  empty( $instance['wppremium_email']  ) ? '' : $instance['wppremium_email'],  $instance );
			
			// Before widget tag
			echo $args['before_widget'];
			
			// Title
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			
			echo '<ul class="detials-wrap no-dot">';
				if( $location ){
					echo '<li>';
					echo '<i class="fa fa-map-marker" aria-hidden="true"></i>';
					echo '<div class="detail-content">' . $location . '</div>';
					echo '</li>';
				}
				if( $email ){
					echo '<li>';
					echo '<i class="fa fa-envelope" aria-hidden="true"></i>';
					echo '<div class="detail-content"><a href="mailto:' . $email . '">' . $email . '</a></div>';
					echo '</li>';
				}
				if( $phone ){
					echo '<li>';
					echo '<i class="fa fa-phone" aria-hidden="true"></i>';			
					echo '<div class="detail-content">'; 
					$words = explode(',', $phone);
					if(!empty($words)){
						foreach($words as $word){
					        echo '<a href="tel:'.htmlspecialchars($word).'">'.htmlspecialchars($word).'</a>';
					    }
					} 
					echo '</div>';
					echo '</li>';
				}
			echo '</ul>';
			
			// After widget tag
			echo $args['after_widget'];
	
		}

		public function form( $instance ) {
	
			// Set default values
			$instance = wp_parse_args( (array) $instance, array( 
				'wppremium_title' => '',
				'wppremium_location' => '',
				'wppremium_phone' => '',
				'wppremium_email' => '',
			) );
	
			// Retrieve an existing value from the database
			$wppremium_title = !empty( $instance['wppremium_title'] ) ? $instance['wppremium_title'] : '';
			$wppremium_location = !empty( $instance['wppremium_location'] ) ? $instance['wppremium_location'] : '';
			$wppremium_phone = !empty( $instance['wppremium_phone'] ) ? $instance['wppremium_phone'] : '';
			$wppremium_email = !empty( $instance['wppremium_email'] ) ? $instance['wppremium_email'] : '';
	
			// Form fields
			echo '<p>';
			echo '	<label for="' . $this->get_field_id( 'wppremium_title' ) . '" class="wppremium_title_label">' . __( 'Title', 'wppremium' ) . '</label>';
			echo '	<input type="text" id="' . $this->get_field_id( 'wppremium_title' ) . '" name="' . $this->get_field_name( 'wppremium_title' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'wppremium' ) . '" value="' . esc_attr( $wppremium_title ) . '">';
			echo '</p>';
	
			echo '<p>';
			echo '	<label for="' . $this->get_field_id( 'wppremium_location' ) . '" class="wppremium_text_label">' . __( 'location', 'wppremium' ) . '</label>';
			echo '	<textarea id="' . $this->get_field_id( 'wppremium_location' ) . '" name="' . $this->get_field_name( 'wppremium_location' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'wppremium' ) . '">' . $wppremium_location . '</textarea>';
			echo '</p>';

			echo '<p>';
			echo '	<label for="' . $this->get_field_id( 'wppremium_phone' ) . '" class="wppremium_text_label">' . __( 'phone', 'wppremium' ) . '</label>';
			echo '	<textarea id="' . $this->get_field_id( 'wppremium_phone' ) . '" name="' . $this->get_field_name( 'wppremium_phone' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'wppremium' ) . '">' . $wppremium_phone . '</textarea>';
			echo '</p>';

			echo '<p>';
			echo '	<label for="' . $this->get_field_id( 'wppremium_email' ) . '" class="wppremium_text_label">' . __( 'email', 'wppremium' ) . '</label>';
			echo '	<textarea id="' . $this->get_field_id( 'wppremium_email' ) . '" name="' . $this->get_field_name( 'wppremium_email' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'wppremium' ) . '">' . $wppremium_email . '</textarea>';
			echo '</p>';
	
		}
	
		public function update( $new_instance, $old_instance ) {
	
			$instance = $old_instance;
	
			$instance['wppremium_title'] = !empty( $new_instance['wppremium_title'] ) ? strip_tags( $new_instance['wppremium_title'] ) : '';
			$instance['wppremium_location'] = !empty( $new_instance['wppremium_location'] ) ? strip_tags( $new_instance['wppremium_location'] ) : '';
			$instance['wppremium_phone'] = !empty( $new_instance['wppremium_phone'] ) ? strip_tags( $new_instance['wppremium_phone'] ) : '';
			$instance['wppremium_email'] = !empty( $new_instance['wppremium_email'] ) ? strip_tags( $new_instance['wppremium_email'] ) : '';
	
			return $instance;
	
		}
	
	}
	

	function wppremium_register_widgets() {
		register_widget( 'wppremium_Text_Widget' );
	}
	add_action( 'widgets_init', 'wppremium_register_widgets' );

?>