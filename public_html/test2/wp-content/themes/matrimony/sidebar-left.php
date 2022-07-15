<?php
/**
 * The Left Sidebar for the blog
 * @package lavish
 * @since 1.0.0
 */

if (   ! is_active_sidebar( 'blogleft'  ))
    return;

dynamic_sidebar( 'blogleft' );

?>