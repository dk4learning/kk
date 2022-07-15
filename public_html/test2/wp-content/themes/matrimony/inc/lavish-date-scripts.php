<?php
/*
=================================================
Lavish Theme CSS, JQUERY, GOOGLE FONTS ENQUEUER
@package LavishDate
=================================================
*/
add_action('wp_enqueue_scripts', 'lavish_date_modernizer_scripts', 10);
if (!function_exists('lavish_date_modernizer_scripts')){
	function lavish_date_modernizer_scripts() {
		wp_enqueue_script('modernizr',	get_template_directory_uri() . '/js/modernizr.js', false, '2.0.6', false); 
	}
}
/*
=================================================
enqueue styles on head for front end.
=================================================
*/
add_action( 'wp_enqueue_scripts', 'lavish_dates_styles', 11 );
if (!function_exists('lavish_dates_styles')){
	function lavish_dates_styles() {
		wp_dequeue_style('user_section_styles', plugins_url('dsp_dating/css/user_section_styles.css'));
    	wp_dequeue_style('dsp-user_section_styles');
    	wp_enqueue_style( 'lavish-date-common', get_template_directory_uri(). '/css/common.css', array(), '1.0', 'all' );
    	//Font Awesome Icons
    	wp_enqueue_style( 'lavish-date-fontawesome', get_template_directory_uri().'/css/font-awesome/css/font-awesome.min.css', array(), '1.0', 'all' );
		//Lato Font    	
    	wp_enqueue_style( 'lavish-date-lato', '//fonts.googleapis.com/css?family=Lato:100,300,400,700,900,700italic', array(), '1.0', 'all' );
    	//Monteserrat Font
    	wp_enqueue_style( 'lavish-date-monteserrat', '//fonts.googleapis.com/css?family=Montserrat:400,700', array(), '1.0', 'all' );
    	wp_enqueue_style( 'lavish-date-satisfy', '//fonts.googleapis.com/css?family=Satisfy', array(), '1.0', 'all' );
    	wp_enqueue_style( 'lavish_date_wow', get_template_directory_uri() . '/css/animate.css', array( ), '1.0', 'all' );
		wp_enqueue_script('lavish_date_wow_js', get_template_directory_uri() . '/js/wow.js', array('jquery'), '1.0', true);
    	wp_enqueue_style( 'lavish-date-plugin', get_template_directory_uri(). '/css/plugin.css', array(), '1.0', 'all');
		wp_enqueue_style( 'lavish-date-camerastyle', get_template_directory_uri().'/css/camera.css', array(), '1.0', 'all');
 		wp_enqueue_style( 'lavish-date-JcarouselResponsive', get_template_directory_uri(). '/css/jcarousel.responsive.css', array(), '1.0', 'all');
		wp_enqueue_style( 'lavish-date-responsive', get_template_directory_uri(). '/css/responsive.css', array(), '1.0', 'all');
		wp_enqueue_style( 'lavish-date-main', get_template_directory_uri(). '/css/lavish-date.css', array(), '1.0', 'all');
		wp_enqueue_style( 'lavish-date-style', get_stylesheet_uri());
	}
}
/*
=================================================
enqueue scripts at the end for front end.
=================================================
*/
add_action( 'wp_enqueue_scripts', 'lavish_date_enqueue_scripts' );
if (!function_exists('lavish_date_enqueue_scripts')){
	function lavish_date_enqueue_scripts() {
		if (!is_admin()) {
			if (is_singular() AND comments_open() AND (get_option('thread_comments') === 1)) {
				wp_enqueue_script('comment-reply');
			}
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'lavish-date-plugin', get_template_directory_uri().'/js/plugins.js', array( 'jquery' ), '1.0', false );
			wp_enqueue_script( 'lavish-date-camerascript', get_template_directory_uri().'/js/camera.min.js', array( 'jquery' ), '1.3.0', false );
			wp_enqueue_script( 'lavish-date-easing', get_template_directory_uri().'/js/jquery.easing.1.3.js', array( 'jquery' ), '1.3.0', false );
			wp_enqueue_script( 'lavish-date-jcarouselmin', get_template_directory_uri().'/js/jquery.jcarousel.min.js', array( 'jquery' ), '0.3.1', false );
			wp_enqueue_script( 'lavish-date-jcarousell', get_template_directory_uri().'/js/jcarousel.responsive.js', array( 'jquery' ), '1.0.1', false );
			wp_enqueue_script( 'lavish-date-imagesloaded', get_template_directory_uri().'/js/imagesloaded.pkgd.min.js', array('jquery'), '1.0', false );	
			wp_enqueue_script( 'lavish-date-imagefill', get_template_directory_uri().'/js/jquery-imagefill.js', array( 'jquery' ), '1.0', false );	
			wp_enqueue_script( 'lavish-date-scripts', get_template_directory_uri().'/js/scripts.js', array( 'jquery' ), '1.0', false );
            wp_localize_script( 'lavish-date-scripts', 'scriptjsdata_profile',array('my_profile'=>language_code('DSP_MENU_EDIT_MY_PROFILE'),
                'trendings' => language_code('DSP_MENU_TRENDING'),
                'viewed_me' => language_code('DSP_MENU_VIEWED_ME'),
                'i_viewed' => language_code('DSP_MENU_I_VIEWED'),
                'members_online' => language_code('DSP_ONLINE_MEMBER_TEXT'),
                'send_message' => language_code('DSP_SEND_MSG_BUTTON'),
                'inbox' => language_code('DSP_INBOX'),
                'add_to_favourites' => language_code('DSP_ADD_TO_FAVOURITES'),
                'send_wink' => language_code('DSP_SEND_WINK'),
                'add_to_friend' => language_code('DSP_ADD_TO_FRIENDS'),
                'send_gift' => language_code('DSP_SEND_GIFT'),
                'one_to_one_chat' => language_code('DSP_CHAT_ONE_MODE')
            ));
			wp_localize_script('scripts','dsp', array( 'adminUrl' => admin_url("admin-ajax.php") ));
		}
	}
}