<?php
/*
=================================================
Register Navmenus For Lavish Date Theme
@package Lavish
=================================================
*/
if (!function_exists('register_blankpress_menus')){
	function register_blankpress_menus(){
		register_nav_menus(array(
			'primary'   => __('Header Menu', 'lavish-date'), 
			'footer' => __('Footer Menu', 'lavish-date')
		));
	}
}
add_action('after_setup_theme', 'register_blankpress_menus');