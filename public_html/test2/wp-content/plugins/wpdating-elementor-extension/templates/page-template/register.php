<?php 
    get_header(); ?>

    <div id="page" class="site">
    	<div class="container">
	        <div class='wpee-register-form'>
	            <div class="wpee-register-form-wrap">               
	                <?php wpee_locate_template('auth/login-register-pop-up.php');?>
	            </div>
	        </div>
        </div>

    </div><!-- #main -->
<?php

get_sidebar();
get_footer();