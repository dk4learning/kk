<?php

/*-------------------------------------------------

	BlankPress - Enqueue Styles and Scripts

 --------------------------------------------------*/



/*  enqueue modernizr only, on head for front end. */

add_action( 

	'wp_enqueue_scripts',		// tag (string required none) - name of the action to which the function_to_add is hooked

	'enqueue_modernizer_cb',	// function_to_add (callback required none) - name of the function to be hooked

	10							// priority (int optional 10) - order in which fxn associated with particular action are executed, lower number = earlier execution

);



if (!function_exists('enqueue_modernizer_cb')){

	function enqueue_modernizer_cb() {

		// register modernizr 

		wp_register_script(

			'modernizr', 	// handle (string required none)- unique id use ? to pass query string

			get_template_directory_uri() . '/js/modernizr.js',  //src (string optional false)- URL to the script

			false, 			// deps (array optional array()) - array of the handles that this script depends on

			'2.0.6', 		// ver(string optional false) - script version number. if no version specified or set to false wp adds its current version , if null no version number is added

			false			// in_footer (boolean optional false) - whether to place script in footer

		); 

		// enqueue modernizr

		wp_enqueue_script('modernizr');

	}

}



/*  enqueue styles on head for front end. */

add_action( 'wp_enqueue_scripts', 'enqueue_styles_cb', 11 );



if (!function_exists('enqueue_styles_cb')){

	function enqueue_styles_cb() {

		// register stylesheet files

		wp_dequeue_style('user_section_styles', plugins_url('dsp_dating/css/user_section_styles.css'));

    	wp_register_style( 'Common', CSS_URL. 'common.css', array(), '1.0', 'all' );

		//Font Awesome Icons
        wp_register_style( 'Fontawesome', CSS_URL. 'font-awesome/css/font-awesome.min.css', array(), '4.2.0', 'all' );

		wp_register_style( 'Plugin', CSS_URL. 'plugin.css', array(), '1.0', 'all');

		wp_register_style( 'camerastyle', CSS_URL. 'camera.css', array(), '1.0', 'all');

 		wp_register_style( 'JcarouselResponsive', CSS_URL. 'jcarousel.responsive.css', array(), '1.0', 'all');

		wp_register_style( 'Responsive', CSS_URL. 'responsive.css', array(), '1.0', 'all');

		// first load other stylesheets at once

		wp_enqueue_style( array('user_section_styles','Common','Fontawesome','Plugin', 'camerastyle', 'JcarouselResponsive', 'Responsive' ));

		// then load our main stylesheet

		wp_enqueue_style( 'blankpress-style', get_stylesheet_uri() );



		// finally load the Internet Explorer specific stylesheet.

		//global $wp_styles;

		//wp_enqueue_style( 'blankpress-ie', get_template_directory_uri() . '/css/ie.css', array( 'blankpress-style' ), '20130213' );

		//$wp_styles->add_data( 'blankpress-ie', 'conditional', 'lt IE 9' );

		//wp_style_add_data( 'blankpress-ie', 'conditional', 'lt IE 9' );

	}

}



/*  enqueue scripts at the end for front end. */

add_action( 'wp_enqueue_scripts', 'enqueue_scripts_cb' );



if (!function_exists('enqueue_scripts_cb')){

	function enqueue_scripts_cb() {

		if (!is_admin()) {

			// add JavaScript to pages with the comment form to enable threaded comments

			if (is_singular() AND comments_open() AND (get_option('thread_comments') === 1)) {

				wp_enqueue_script('comment-reply');

			}



			//wp_deregister_script( 'jquery' ); // Load Local Jquery (development environment)  or from GoogleApis (production environment)

			//wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js', false, '1.8.2', true);

			wp_register_script( 'jquery', JS_URL.'jquery.min.js', false, '1.7.0', false);

			wp_enqueue_script( 'jquery' );

			

			// register JavaScript files

			wp_register_script( 'plugin',  JS_URL.'plugins.js', array( 'jquery' ), '1.0', false );

			wp_register_script( 'camerascript',    JS_URL.'camera.min.js', array( 'jquery' ), '1.3.0', false );

			wp_register_script( 'easing',    JS_URL.'jquery.easing.1.4.1.min.js', array( 'jquery' ), '1.4.1', false );

			wp_register_script( 'jcarouselmin',    JS_URL.'jquery.jcarousel.min.js', array( 'jquery' ), '0.2.7', false );

			wp_register_script( 'jcarousell',    JS_URL.'jcarousel.responsive.js', array( 'jquery' ), '1.0.1', false );

			wp_register_script( 'imagesloaded',    JS_URL.'imagesloaded.pkgd.min.js', array('jquery'), '4.1.4', false );

			wp_register_script( 'imagefill',    JS_URL.'jquery-imagefill.js', array( 'jquery' ), '1.0', false );	

			wp_register_script( 'scripts',    JS_URL.'scripts.js', array( 'jquery' ), '1.0', false );

            wp_localize_script( 'scripts', 'scriptjsdata_profile',array('my_profile'=>language_code('DSP_MENU_EDIT_MY_PROFILE'),
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

			//load all scripts at once
			wp_enqueue_script( array('plugin','camerascript', 'easing', 'jcarouselmin', 'jcarousell','imagesloaded','imagefill', 'scripts' ));

			wp_localize_script('scripts','dsp', array( 'adminUrl' => admin_url("admin-ajax.php") ));

		}

	}

}