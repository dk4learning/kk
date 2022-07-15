
<?php 
global $wpdb;
$user_id   = get_current_user_id();
$member_id = wpee_profile_id();

$dsp_user_profiles         = $wpdb->prefix . 'dsp_user_profiles';
$dsp_my_friends_table      = $wpdb->prefix . 'dsp_my_friends';
$dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';

$check_couples_mode  = wpee_get_setting('couples');
$dsp_user_table      = $wpdb->prefix . DSP_USERS_TABLE;
$imagepath           = content_url('/') ;
$user_profile        = wpee_get_user_details_by_user_id( $member_id );
$profile_link        = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $user_profile->user_name );
$profile_friends_url = $profile_link . '/friends/list';
?>
<div class="friends-member wpee-block">
	<div class="wpee-block-header">
		<h4 class="wpee-block-title"><?php esc_html_e( 'Friends', 'wpdating');?></h4>
	</div>
	<div class="profile-friends-member-inner wpee-block-content">
	    <ul class="friends-section profile-friends-list"><!-- friends-section class used -->
	    	<?php
		    if ($check_couples_mode->setting_status == 'Y') {
		        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
		    } else {
		        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
		    }
		    if(isset($member_exist_friends) && count($member_exist_friends) > 0){
		        $i = 0;
		        foreach ($member_exist_friends as $member_friends) {
					$profile_link = trailingslashit(wpee_get_profile_url_by_id( $member_friends->friend_uid ));
		            $count_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$member_friends->friend_uid'");
		            $displayed_member_name = wpee_get_user_display_name_by_id($member_friends->friend_uid);
		            if (($i % 4) == 0) {
		                ?>
		            <?php }  // End if(($i%4)==0) ?>

				        <li id="friend<?php echo esc_attr( $member_friends->friend_uid );?>" class="<?php echo ( $user_id == $member_id ) ? 'wpee-delete-list' : '';?>">
				            <figure>
				                <?php if ($user_id == '') { ?>

				                    <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>"> <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" /></a>

				                        <?php
				                    } else {
				                       	$favt_mem = array();
				                       	$private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_friends->friend_uid'");
				                       	foreach ($private_mem as $private) {
				                           $favt_mem[] = $private->favourite_user_id;
				                       	}
				                       ?>

				                        <?php
				                        if ($check_couples_mode->setting_status == 'Y') {
				                            if ($member_friends->gender == 'C') {
				                                ?>
				                                <?php if ($member_friends->make_private == 'Y') { ?>

				                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
				                                        <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>" >
				                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
				                                        </a>                
				                                    <?php } else {
				                                        ?>
				                                    <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>"  >				
				                                        <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
				                                        <?php
				                                    }
				                                } else {
				                                    ?>

				                                    <a  href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>"> 
				                                    <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
				                                    </a>
				                                <?php } ?>

				                            <?php } else { ?>

				                                <?php if ($member_friends->make_private == 'Y') { ?>

				                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
				                                        <a href="<?php echo esc_url( $profile_link );?>"  title="<?php echo $displayed_member_name; ?>">
				                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
				                                        </a>                
				                                    <?php } else {
				                                        ?>
				                                        <a href="<?php echo esc_url( $profile_link );?>"  title="<?php echo $displayed_member_name; ?>">				
				                                        <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>"/></a>                
				                                        <?php
				                                    }
				                                } else {
				                                    ?>

				                                    <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>"> 
				                                    <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
				                                    </a>
				                                <?php } ?>

				                                <?php
				                            }
				                        } else {
				                            ?> 

				                            <?php if ($member_friends->make_private == 'Y') { ?>

				                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
				                                    <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>" >
				                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
				                                    </a>                
				                                <?php } else {
				                                    ?>
				                                    <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>">				
				                                    <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
				                                    <?php
				                                }
				                            } else {
				                                ?>

				                                <a href="<?php echo esc_url( $profile_link );?>" title="<?php echo $displayed_member_name; ?>"> 
				                                <img src="<?php echo wpee_display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/>
				                                </a>
				                            <?php } ?>

				                        <?php } ?>
				                        <?php
				                        unset($favt_mem);
				                    }
				                    ?>
		                    	</figure>
				                <span class="user-name-show">
			                            <a href="<?php echo esc_url( $profile_link );?>">	
				                            <?php echo $displayed_member_name; ?>
				                        </a>
				                        <span class="count-friends"><?php echo __( 'Friends','wpdating') .'('. $count_friends . ')';?></span>
				                </span>
				        </li>
		            <?php
		            $i++;
		        }
		    }else{ ?>
		        <span class="dsp_span_pointer"><?php echo __('No Friends Added', 'wpdating'); ?></span>
		    <?php
		    }
		    ?>
		</ul>
	</div>
	<div class="wpee-block-footer">
		<a href="<?php echo esc_url($profile_friends_url);?>" class="edit-profile-link"><?php esc_html_e('View All Friends','wpdating');?></a>
	</div>
</div>