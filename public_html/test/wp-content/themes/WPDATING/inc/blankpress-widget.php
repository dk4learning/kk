<?php 

/*-------------------------------------------------

	BlankPress - Functions

 --------------------------------------------------*/

// Creating the widget 

	function dsp_home_widget($args, $widget_args = 1) {

		

		extract( $args, EXTR_SKIP );

		if ( is_numeric($widget_args) )

			$widget_args = array( 'number' => $widget_args );

		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

		extract( $widget_args, EXTR_SKIP );

	

		$options = get_option('simple_widget');

		if ( !isset($options[$number]) ) 

		return;



		$title = $options[$number]['title']; 		// single value

		$text = $options[$number]['text']; 		// single value

		$text_url = $options[$number]['text_url'];; 		// single value

		$textarea = $options[$number]['textarea']; // single value

			

		echo $before_widget; // start widget display code ?>

			



			<div class="dsp-ico-block pull-left">

				<i class="fa fa-<?=$text?>"></i>

			</div>

			<div class="dsp-block-title pull-left clearfix">

				<a href="<?=$text_url?>"><?=$title?></a>

			</div>

			<div class="clear"></div>

			<p>

				<?=$textarea?>

			</p>

			

	<?php echo $after_widget; // end widget display code

	

	}

	

	

	function dsp_home_widget_control($widget_args) {

	

		global $wp_registered_widgets;

		static $updated = false;

	

		if ( is_numeric($widget_args) )

			$widget_args = array( 'number' => $widget_args );			

		$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

		extract( $widget_args, EXTR_SKIP );

	

		$options = get_option('simple_widget');

		

		if ( !is_array($options) )	

			$options = array();

	

		if ( !$updated && !empty($_POST['sidebar']) ) {

		

			$sidebar = (string) $_POST['sidebar'];	

			$sidebars_widgets = wp_get_sidebars_widgets();

			

			if ( isset($sidebars_widgets[$sidebar]) )

				$this_sidebar =& $sidebars_widgets[$sidebar];

			else

				$this_sidebar = array();

	

			foreach ( (array) $this_sidebar as $_widget_id ) {

				if ( 'dsp_home_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {

					$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

					if ( !in_array( "simple-widget-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed.

						unset($options[$widget_number]);

				}

			}

	

			foreach ( (array) $_POST['simple-widget'] as $widget_number => $simple_widget ) {

				if ( !isset($simple_widget['title']) && isset($options[$widget_number]) ) // user clicked cancel

					continue;

				

				$title = strip_tags(stripslashes($simple_widget['title']));

				$text = strip_tags(stripslashes($simple_widget['text_value']));		

				$text_url = strip_tags(stripslashes($simple_widget['text_url']));		

				$textarea = $simple_widget['textarea_value'];

				

				// Pact the values into an array

				$options[$widget_number] = compact( 'title', 'text', 'text_url', 'textarea' );

			}

	

			update_option('simple_widget', $options);

			$updated = true;

		}

	

		if ( -1 == $number ) { // if it's the first time and there are no existing values

	

			$title = '';

			$text = '';

			$text_url = '';

			$textarea = '';

			$number = '%i%';

			

		} else { // otherwise get the existing values

		

			$title = attribute_escape($options[$number]['title']);

			$text = attribute_escape($options[$number]['text']); // attribute_escape used for security

			$text_url = attribute_escape($options[$number]['text_url']); // attribute_escape used for security

			$textarea = format_to_edit($options[$number]['textarea']);

		}

		

	?>

	<p><label>Widget Title</label><br /><input id="title_value_<?php echo $number; ?>" name="simple-widget[<?php echo $number; ?>][title]" type="text" value="<?=$title?>" /></p>

    <p><label>Icon Class</label><br /><input id="text_value_<?php echo $number; ?>" name="simple-widget[<?php echo $number; ?>][text_value]" type="text" size="30" value="<?=$text?>" /></p>

    <p><label>Link URL</label><br /><input id="text_url_<?php echo $number; ?>" name="simple-widget[<?php echo $number; ?>][text_url]" type="text" size="30" value="<?=$text_url?>" />

    </p>

   

    <p><label>Textarea</label><br /><textarea id="textarea_value_<?php echo $number; ?>" name="simple-widget[<?php echo $number; ?>][textarea_value]" type="text" cols="30" rows="4"><?=$textarea?></textarea></p>

    <input type="hidden" name="simple-widget[<?php echo $number; ?>][submit]" value="1" />

    

	<?php

	}

	

	

	function dsp_home_widget_register() {

		if ( !$options = get_option('simple_widget') )

			$options = array();

		$widget_ops = array('classname' => 'simple_widget', 'description' => __('Test widget form'));

		$control_ops = array('width' => 400, 'height' => 350, 'id_base' => 'simple-widget');

		$name = __('Home Widget');

	

		$id = false;

		

		foreach ( (array) array_keys($options) as $o ) {

	

			if ( !isset( $options[$o]['title'] ) )

				continue;

						

			$id = "simple-widget-$o";

			wp_register_sidebar_widget($id, $name, 'dsp_home_widget', $widget_ops, array( 'number' => $o ));

			wp_register_widget_control($id, $name, 'dsp_home_widget_control', $control_ops, array( 'number' => $o ));

		}

		

		if ( !$id ) {

			wp_register_sidebar_widget( 'simple-widget-1', $name, 'dsp_home_widget', $widget_ops, array( 'number' => -1 ) );

			wp_register_widget_control( 'simple-widget-1', $name, 'dsp_home_widget_control', $control_ops, array( 'number' => -1 ) );

		}

	}



	add_action('init', 'dsp_home_widget_register');



	// Creating the widget 

	class dsp_widget extends WP_Widget {



		function __construct() {

		parent::__construct(

		// Base ID of your widget

		'dsp_widget', 



		// Widget name will appear in UI

		__('DSP Image Widget', 'dsp_widget_domain'), 



		// Widget description

		array( 'description' => __( 'DSP Image Widget', 'dsp_widget_domain' ), ) 

		);

	}



	// Creating widget front-end

	// This is where the action happens

	public function widget( $args, $instance ) {

		$image_url = apply_filters( 'image_url', $instance['image_url'] );

		$image_btn = apply_filters( 'image_btn', $instance['image_btn'] );

		$image_desc = apply_filters( 'image_desc', $instance['image_desc'] );

		$image_link = apply_filters( 'image_link', $instance['image_link'] );



		// before and after widget arguments are defined by themes

		echo $args['before_widget'];





		// This is where you run the code and display the output

		echo '<div class="widget-image-block"><img src="' . $image_url . '"  title="" alt="" /><a href="' . $image_link. '" class="dsp_widget_link"> ' .$image_btn . '</a><span class="dsp_widget_desc">'. $image_desc . '</span></div>';

		

	}

		

// Widget Backend 

	public function form( $instance ) {

		if ( isset( $instance[ 'image_url' ] ) ) {

			$image_url = $instance[ 'image_url' ];

			$image_btn = $instance[ 'image_btn' ];

			$image_desc = $instance[ 'image_desc' ];

			$image_link = $instance[ 'image_link' ];

	}

	else {

		$image_url = __( 'Image Url', 'dsp_widget_domain' );

		$image_btn = __( 'Button Text', 'dsp_widget_domain' );

		$image_desc = __( 'Image Description', 'dsp_widget_domain' );

		$image_link = __( 'Image Link', 'dsp_widget_domain' );

	}

	// Widget admin form

	?>

	<p>

		<label for="<?php echo $this->get_field_id( 'image_url' ); ?>"><?php _e( 'Image Url:' ); ?></label> 

		<input class="widefat" id="<?php echo $this->get_field_id( 'image_url' ); ?>" name="<?php echo $this->get_field_name( 'image_url' ); ?>" type="text" value="<?php echo esc_attr( $image_url ); ?>" />

	</p>

	<p>

		<label for="<?php echo $this->get_field_id( 'image_btn' ); ?>"><?php _e( 'Button Text:' ); ?></label> 

		<input class="widefat" id="<?php echo $this->get_field_id( 'image_btn' ); ?>" name="<?php echo $this->get_field_name( 'image_btn' ); ?>" type="text" value="<?php echo esc_attr( $image_btn ); ?>" />

	</p>

	<p>

		<label for="<?php echo $this->get_field_id( 'image_btn' ); ?>"><?php _e( 'Image Description:' ); ?></label> 

		<input class="widefat" id="<?php echo $this->get_field_id( 'image_desc' ); ?>" name="<?php echo $this->get_field_name( 'image_desc' ); ?>" type="text" value="<?php echo esc_attr( $image_desc ); ?>" />

	</p>

	<p>

		<label for="<?php echo $this->get_field_id( 'image_link' ); ?>"><?php _e( 'Image Link:' ); ?></label> 

		<input class="widefat" id="<?php echo $this->get_field_id( 'image_link' ); ?>" name="<?php echo $this->get_field_name( 'image_link' ); ?>" type="text" value="<?php echo esc_attr( $image_link ); ?>" />

	</p>

	<?php 

	}

	

	// Updating widget replacing old instances with new

	public function update( $new_instance, $old_instance ) {

		$instance = array();

			$instance['image_url'] = ( ! empty( $new_instance['image_url'] ) ) ? strip_tags( $new_instance['image_url'] ) : '';

			$instance['image_btn'] = ( ! empty( $new_instance['image_btn'] ) ) ? strip_tags( $new_instance['image_btn'] ) : '';

			$instance['image_desc'] = ( ! empty( $new_instance['image_desc'] ) ) ? strip_tags( $new_instance['image_desc'] ) : '';

			$instance['image_link'] = ( ! empty( $new_instance['image_link'] ) ) ? strip_tags( $new_instance['image_link'] ) : '';

		return $instance;

		}

	} // Class dsp_widget ends here



	// Register and load the widget

	function wpb_load_widget() {

		register_widget( 'dsp_widget' );

	}

	add_action( 'widgets_init', 'wpb_load_widget' );