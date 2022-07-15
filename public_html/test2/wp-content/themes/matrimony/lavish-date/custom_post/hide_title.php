<?php

/*
=====================================================================================================
 # Custom POST FIELDS SETUP TO HIDE THE TITLE OF THE PAGES IN SINGULAR POSTS IF CHECKED
======================================================================================================
*/

if (!class_exists('st_hide_titles')) {

	class st_hide_titles {

	public function __construct() {
		add_action('add_meta_boxes', array($this, 'st_hide_title_init'));
      	add_action('save_post', array($this, 'st_hide_title_save'));
      	add_action( 'wp_head', array( $this, 'st_hide_title_css' ), 3000 );
    }

    public function st_hide_title_init() {
      add_meta_box( 
        'hide-titles',
        'Hide Titles',
        array($this, 'st_hide_title_box'),
        'page',
        'side',
        'high'
      );
    }

    public function st_hide_title_box() {
    	global $post;
    	$checked = get_post_meta($post->ID, 'st_hide_title_check_option',true);
           	
    	$check_results = '';
    	if ($checked) {
    		$check_results = 'checked="checked"';
    	}
    	else {
    		$check_results = '';
    	}
         	echo '<input type="checkbox" name="st_hide_title_check_option" class="widefat" '.$check_results.'/>
    		 <label><strong>Hide Page Titles</strong></label>';
    }

    public function st_hide_title_save($post_id) {
    	update_post_meta($post_id, 'st_hide_title_check_option', $_POST['st_hide_title_check_option']);
        
    }

    public function st_hide_title_css() {
        global $post;
        $is_shown_to_be_hidden = get_post_meta($post->ID, 'st_hide_title_check_option', true);
        if (empty($is_shown_to_be_hidden)) {
            return;
        } 
        else {
            ?>
        <style type="text/css">
        .page-id-<?php echo $post->ID; ?> .dsp-page-title{display:none;}
        </style>

        <?php   } 
        
    }
}

    

    $st_hide_titles = new st_hide_titles();
}

?>