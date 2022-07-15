<?php
/*
=================================================
Creating Pages Called Login and Logout On
Installation of theme and hooking theme to the 
navmenu login/Logout Option
=================================================
*/

if (!class_exists('lavish_login_logout_menu')){

	class lavish_login_logout_menu {

		public function __construct() {
			add_action('init', array($this, 'lavish_login_logout_menu_initialize_page'));
		}

		public function lavish_login_logout_menu_initialize_page() {
			
			global $wpdb;

			if( null == get_page_by_title('login' ) ) {


			$lavish_login_page_init = array(
			  'post_content'    => '[lavish_date_login]',
			  'post_name'  		=> 'dsp_login',
			  'post_title'   	=> 'Login',
			  'post_status'		=> 'publish',
			  'post_author'     => 1,
			  'post_type'		=> 'page',
			  
			);

			// Insert the post into the database
			wp_insert_post( $lavish_login_page_init, true);
			}
			else {

			}

			if (null == get_page_by_title('register')) {
				
				$lavish_register_page_init = array(
					'post_content'    => '[lavish_date_register]',
					'post_name'  		=> 'dsp_register',
					'post_title'   	=> 'Register',
					'post_status'		=> 'publish',
					'post_author'     => 1,
					'post_type'		=> 'page',
				);

			// Insert the post into the database
			wp_insert_post( $lavish_register_page_init, true);
			}
			else {

			}
			
		}

		public function lavish_login_logout_menu_page_register_content() {
			get_header();
			echo do_shortcode('[dsp_register]');
			get_footer();
		}

		public function lavish_login_logout_menu_page_content() {
			get_header();
			echo 'shekhar_login';
			echo do_shortcode('[dsp_login]');
			get_footer();
		} 


	}
	$lavish_login_logout_menu = new lavish_login_logout_menu();
}

function lavish_date_login_shortcode() {
	include_once('login/dsp_login.php');
}
add_shortcode( 'lavish_date_login', 'lavish_date_login_shortcode' );


function lavish_date_register_shortcode() {
	include_once('register/dsp_register.php');
}
add_shortcode( 'lavish_date_register', 'lavish_date_register_shortcode' );




?>