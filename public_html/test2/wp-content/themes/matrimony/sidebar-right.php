<?php
/**
 * The Right Sidebar for the blog
 * @package lavish
 * @since 1.0.0
 */

if (   ! is_active_sidebar( 'blogright'  ))
	return;
  
	dynamic_sidebar( 'blogright' );
?>