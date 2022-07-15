<?php 

	/* 
	Shortcode: Header Login
	*/
	add_shortcode('wpee_header_login', 'wpee_header_login_cb');
    function wpee_header_login_cb() { 
    	if( !is_user_logged_in()): ?>
            <div class="btn-wrap primary outline outline-primary-hover has-icon">
                <a href="javascript:void(0);" class="header-login-btn"><i class="fa fa-user"></i> <?php echo __('Login','wpdating') . '/' . __('Register', 'wpdating');?></a>
            </div>
        <?php 
    	else: ?>
    		<div class="user-login">
    			<?php 
				global $wpdb;
				$dsp_user_virtual_gifts     = $wpdb->prefix. 'dsp_user_virtual_gifts';
				$dsp_my_friends_table       = $wpdb->prefix . 'dsp_my_friends';
				$check_my_friend_module     = wpee_get_setting('my_friends');
				$dsp_message_table          = $wpdb->prefix. 'dsp_messages';
				$check_message_mode         = wpee_get_setting('userplane_instant_messenger');
				$check_virtual_gifts_mode   = wpee_get_setting('virtual_gifts');
				$profile_tab                = get_query_var( 'profile-tab' );
				$profile_subtab             = get_query_var( 'profile-subtab' );
				$profile_subtab             = !empty( $profile_subtab ) ? $profile_subtab : '';
				$user_id                    = wpee_profile_id();
				$current_user_id            = get_current_user_id();
				$username                   = wpee_get_username_by_id( $current_user_id );
				$profile_link               = trailingslashit(wpee_get_profile_url_by_id($current_user_id));

				$imagepath = content_url('/') ;

                //For logout url
                if ( function_exists( 'pll_get_post_language' ) ) {
                    global $post;
                    $lang             = pll_get_post_language( $post->ID, 'slug' );
                    $default_language = pll_default_language();

                    if ( $lang == $default_language ) {
                        $redirect_url = get_site_url();
                    } else {
                        $redirect_url = get_site_url() . '/' . $lang;
                    }
                } else {
                    $redirect_url = get_site_url();
                }
                $logout_url = wp_logout_url( $redirect_url ); ?>
				<ul class="wpee-notification-links d-flex align-center">
				    <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
				        <li class="wpee-menu <?php if (($profile_tab == "gifts")) { echo "wpee_active_link"; } ?>">
				            <?php $count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$current_user_id AND status_id=0"); ?>
				                <a href="<?php echo trailingslashit( $profile_link )."gifts"; ?>"><i
				                            class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?>
				                <?php if( $count_friends_virtual_gifts > 0 ){ ?>
			                        <span class="wpee-count-num"><?php echo esc_html($count_friends_virtual_gifts); ?></span>
			                    <?php } ?>
			                </a>
				        </li>
				    <?php } 
				    if ( $check_message_mode->setting_status == 'Y'): ?>
				    <li class="wpee-menu <?php if ($profile_tab == "message") { echo 'wpee_active_link'; } ?>">
				        <?php $count_message = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_message_table WHERE receiver_id=$current_user_id AND message_read='N' AND delete_message = 0"); ?>
				            <a href="<?php echo trailingslashit( $profile_link )."message"; ?>"><i
				                        class="fa fa-envelope"></i><?php echo __('Message', 'wpdating'); ?>
				                <?php if( $count_message > 0 ){ ?>
				                    <span class="wpee-count-num"><?php echo esc_html( $count_message); ?></span>
				                <?php } ?>
				            </a>
				    </li>
				    <?php endif;
				    if ($check_my_friend_module->setting_status == 'Y') { ?>
				        <?php $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$current_user_id AND approved_status='N'"); 
				        ?>
				        <li class="wpee-menu <?php if (($profile_tab == "friend-request")) { echo "wpee_active_link"; } ?>">
				            <a href="<?php echo trailingslashit( $profile_link )."friend-request"; ?>"><i
				                        class="fa fa-users"></i><?php echo __('Friend Request', 'wpdating'); ?>
				                <?php if( $count_friends_request > 0 ){ ?>
				                    <span class="wpee-count-num"><?php echo esc_html( $count_friends_request); ?></span>
				                <?php } ?>
				            </a>
				        </li>
				    <?php } ?>

				    <li class="wpee-menu notification-profile-img">
				        <a class="r-circle overflow-h" href="<?php echo trailingslashit( $profile_link ); ?>">
			       			<img src="<?php echo wpee_display_members_photo($current_user_id, $imagepath); ?>" class="header-profile-image" alt="<?php echo wpee_get_user_display_name_by_id($current_user_id); ?>">
		                    <span class="wpee-tooltip"><?php echo wpee_get_user_display_name_by_id($current_user_id); ?></span>
				        </a>
					    <ul class="wpee-sub-menu no-list">	
						    <li class="wpee-menu">
						        <a href="<?php echo trailingslashit( $profile_link ); ?>">
						        	<i class="fa fa-user"></i>
					       			<?php echo __('My Profile', 'wpdating'); ?>
						        </a>
						    </li>
						    <li class="wpee-menu <?php if (($profile_tab == "settings") && $profile_subtab != 'upgrade-account') { echo "wpee_active_link"; } ?>">
						        <a href="<?php echo trailingslashit( $profile_link )."settings"; ?>"><i
						                    class="fa fa-cog"></i><?php echo __('Settings', 'wpdating'); ?>
						        </a>
						    </li>
							<li class="wpee-menu <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'upgrade-account' ) ? 'wpee_active_link' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/upgrade-account');?>">
				                	<i class="fa fa-level-up"></i>
				                	<?php esc_html_e( 'Upgrade Account', 'wpdating' );?></a></li>
				            <li class="wpee-sub-menu <?php echo ($profile_tab == "viewed") ? "wpee_active_link" : '';?>">
				                <a href="<?php echo trailingslashit( $profile_link )."viewed"; ?>">
				                	<i class="fa fa-eye"></i>
                                    <span><?php echo __( 'I Viewed', 'wpdating' ) . ' / ' . __('Viewed Me', 'wpdating' ); ?></span>
				                </a>
				            </li>
				            <li class="wpee-menu <?php echo ($profile_subtab == "edit-profile") ? "wpee_active_link" : '';?>">
				                <a href="<?php echo trailingslashit( $profile_link )."edit-profile/"; ?>">
				                	<i class="fa fa-pencil-square-o"></i>
				                    <span><?php echo __('Edit Profile', 'wpdating'); ?></span>
				                </a>
				            </li>
							<li class="wpee-menu"><a href="<?php echo $logout_url;?>">
			                	<i class="fa fa-power-off"></i>
			                	<span><?php echo __( 'Logout', 'wpdating' );?><span>
			                	</a>
			                </li>
					    </ul>
				    </li>				   
				</ul>
    		</div>
    	<?php
    	endif;
    }