<?php 
/*
=================================================
This Page Shows Login and Logout Menu on the Head
of the page to control the login and logout users
for dating plugin.
=================================================
*/

if (!is_user_logged_in()) {
	?>
		<ul class="lavish_date_top_menu">
			<li><a href="<?php echo get_bloginfo('url'); ?>/dsp_login"><i class="fa fa-sign-in"></i>&nbsp;<?php echo language_code('DSP_LOGIN'); ?></a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members"><i class="fa fa-pencil"></i>&nbsp;<?php echo language_code('DSP_REGISTER'); ?></a></li>
		</ul>
	<?php
}
else {
	?>
	<ul class="lavish_date_top_menu navmenu">
		<li class="nav_user_click_toggle">
			<?php 
				//get user info and image
				$current_user = wp_get_current_user();
                echo '<div class="lavish_date_user_avator_top">';
                echo get_avatar(($current_user->user_email), '80' );
                echo '</div>';
                echo 'Hi, ';
                echo $current_user->user_login ;
                echo '&nbsp;<i class="down fa fa-sort-desc"></i>';
            ?>
		<ul class="sub-menu">
			<li><a href="<?php echo get_bloginfo('url'); ?>/members"><i class="fa fa-dashboard"></i>&nbsp;Dashboard</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members/email/inbox"><i class="fa fa-envelope"></i>&nbsp;Messages</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members/setting/notification/"><i class="fa fa-bell"></i>&nbsp;Notification</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members/edit"><i class="fa fa-pencil-square-o"></i>&nbsp;Edit Profile</a></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members/setting/account_settings/"><i class="fa fa-cogs"></i>&nbsp;Settings</a></li>
			<li><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><i class="fa fa-power-off"></i>&nbsp;LogOut</a></li>
			<li><br/></li>
			<li><a href="<?php echo get_bloginfo('url'); ?>/members/help"><i class="fa fa-question-circle"></i>&nbsp;Help</a></li>
		</ul>
		</li>
		
		
	</ul>
	<?php
}


