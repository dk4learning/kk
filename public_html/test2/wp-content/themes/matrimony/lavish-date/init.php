<?php
/*
=====================================================================
 # STYLED THEMES FRAME SETUP
======================================================================
/*
=====================================================================
 # CUSTOM POST TYPES
======================================================================
*/
include(FRAMEWORK .'/custom_post/hide_title.php');
include(FRAMEWORK .'/custom_post/breadcrumb.php');


/*
=================================================
Include Widgets For Lavish Date Theme
=================================================
*/
include(FRAMEWORK .'/widgets/dating_members_slider.php');
include(FRAMEWORK .'/widgets/dating_featured_members.php');
include(FRAMEWORK .'/widgets/dating_members_online.php');
include(FRAMEWORK .'/widgets/dating_happy_stories.php');
include(FRAMEWORK .'/widgets/dating_blog_contents.php');
include(FRAMEWORK .'/widgets/dating_search_box.php');
include(FRAMEWORK .'/widgets/dating_meet_me.php');

/*
=================================================
Function to Fetch The Country with their id's
=================================================
*/
if (!function_exists('lavish_date_fetch_country')) {
	function lavish_date_fetch_country($id) {
		global $wpdb;
		$table_name = $wpdb->prefix . DSP_COUNTRY_TABLE;
		return $wpdb->get_var($wpdb->prepare("SELECT `name` FROM `$table_name` WHERE country_id = %d", $id));

	}
}

