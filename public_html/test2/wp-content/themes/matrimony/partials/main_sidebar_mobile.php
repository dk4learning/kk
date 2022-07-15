<div class="lavish_mobile_toggle">
    <div class="lavish_mobile_toggle_header">
        <ul>
            <li class="lavish_mobile_toggle_Menu"><img src="https://lavishdate.wpdating.com/wp-content/uploads/home-icon.png" style="height: 50px; width: 50px"></li>
            <li class="lavish_mobile_toggle_user">
                <?php
                $current_user = wp_get_current_user();
                echo '<div class="lavish_mobile_toggle_user">';
                echo get_avatar(($current_user->user_email), '80' );
                echo '</div>';
                ?>
            </li>
        <div style="clear:both;"></div>
    </div>


    <div class="nav_mobilemenu">
        <ul class="mobile_menu">


        <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'mobile_menu',
                ));
            }
        ?>
    </ul>
    </div>

    <div class="nav_usermobilemenu">
        <?php
            if (!is_user_logged_in()) {
                ?>
                    <ul class="mobile_menu">
                        <li><a href="<?php echo get_bloginfo('url'); ?>/dsp_login"><i class="fa fa-sign-in"></i>&nbsp;Login</a></li>
                        <li><a href="<?php echo get_bloginfo('url'); ?>/dsp_register"><i class="fa fa-pencil"></i>&nbsp;Register</a></li>
                    </ul>
                <?php
            }
            else {
                ?>
                <ul class="mobile_menu">
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members"><i class="fa fa-dashboard"></i>&nbsp;Dashboard</a></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members/email/inbox"><i class="fa fa-envelope"></i>&nbsp;Messages</a></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members/setting/notification/"><i class="fa fa-bell"></i>&nbsp;Notification</a></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members/edit"><i class="fa fa-pencil-square-o"></i>&nbsp;Edit Profile</a></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members/setting/account_settings/"><i class="fa fa-cogs"></i>&nbsp;Settings</a></li>
                    <li><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><i class="fa fa-power-off"></i>&nbsp;LogOut</a></li>
                    <li><br/></li>
                    <li><a href="<?php echo get_bloginfo('url'); ?>/members/help"><i class="fa fa-question-circle"></i>&nbsp;Help</a></li>
                </ul>
            <?php
        }
        ?>
    </div>
</div>    