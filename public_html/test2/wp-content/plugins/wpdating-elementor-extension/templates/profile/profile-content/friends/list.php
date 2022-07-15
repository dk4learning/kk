<?php 
global $wpdb;
$user_id = get_current_user_id();
$member_id = wpee_profile_id();
$dsp_user_profiles = $wpdb->prefix . 'dsp_user_profiles';
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
$dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';
$check_couples_mode = wpee_get_setting('couples');
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$imagepath = content_url('/') ;
$count_my_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$member_id'");	
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
?>
<div class="profile-section-content profile-friends-list">
    <ul class="friends-section main-member-list-wrap no-list"><!-- friends-section class used -->
    	<?php
	    if ($check_couples_mode->setting_status == 'Y') {
	        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
	    } else {
	        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
	    }
	    if(isset($member_exist_friends) && count($member_exist_friends) > 0){
	        $i = 0;
	        foreach ($member_exist_friends as $member_friends) {
				$profile_link = trailingslashit(wpee_get_profile_url_by_id( $member_friends->friend_uid));
	            $count_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$member_friends->friend_uid'");
	            $displayed_member_name = wpee_get_user_display_name_by_id($member_friends->friend_uid);
                $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$member_friends->friend_uid'");
                if ($check_couples_mode->setting_status == 'Y') {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member_friends->friend_uid'");
                } else {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member_friends->friend_uid'");
                }
                $s_country_id = isset($member->country_id) ? $member->country_id : '';
                $s_gender = isset($member->gender) ? $member->gender : '';
                $s_seeking = isset($member->seeking) ? $member->seeking : '';
                $s_state_id = isset($member->state_id) ? $member->state_id : '';
                $s_city_id = isset($member->city_id) ? $member->city_id : '';
                $s_age = isset($member->age) ? GetAge($member->age) : '';
                $s_make_private = isset($member->make_private) ? $member->make_private : '';
                $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
                if(  isset($check_distance_mode->setting_status) &&
                     $check_distance_mode->setting_status == 'Y'  && 
                     $search_type == "distance_search" &&
                     $latlngSet
                ) {
                    $s_distance = isset($member1->distance) ? $member1->distance : 0;
                }
                $displayed_member_name = wpee_get_user_display_name_by_id($member->user_id);
                $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
                ?>

			        <li id="friend<?php echo esc_attr( $member_friends->friend_uid );?>" class="member-detail-wrap <?php echo ( $user_id == $member_id ) ? 'wpee-delete-list' : '';?>">
		                <?php //if (!empty($member_id)) { 
		                       	$favt_mem = array();
		                       	$private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_friends->friend_uid'");
		                       	foreach ($private_mem as $private) {
		                           $favt_mem[] = $private->favourite_user_id;
		                       	}
		                        $profile_link = wpee_get_profile_url_by_id( $member_friends->friend_uid );

		                        if ($exist_make_private->make_private == 'Y') { 
		                            if (!in_array($current_user->ID, $favt_mem)) {
		                                $profile_image= WPDATE_URL. '/images/private-photo-pic.jpg';
		                            }
		                            else{
		                                $profile_image = wpee_display_members_photo($member_friends->friend_uid, $imagepath);
		                            }

		                        }
		                        else{
		                            $profile_image = wpee_display_members_photo($member_friends->friend_uid, $imagepath);
		                        }
		                       ?>
							<figure class="img-holder">				
				                <a href="<?php echo esc_url($profile_link);?>" > 
                                    <img src="<?php echo $profile_image; ?>" class="dsp_img3 iviewed-img"/>
                                </a>
	    						<div class="wpee-user-status <?php echo ( wpee_get_online_user($member_friends->friend_uid) ) ? 'wpee-online' : 'wpee-offline';?>"></div>
							</figure>
			                <div class="user-details">
								<h6 class="member-user-name">
			                    <a href="<?php echo $profile_link; ?>">
			                        <?php
			                        if (strlen($displayed_member_name) > 15)
			                            echo substr($displayed_member_name, 0, 13) . '...';
			                        else
			                            echo $displayed_member_name;
			                        ?>
			                    </a>
								<span class="online dspdp-online-status">
									<?php if ( wpee_get_online_user( $member_friends->friend_uid ) ) : ?>
				                        <span class="dspdp-status-on" <?php _e( 'Online', 'wpdating' ); ?>></span>
				                    <?php else : ?>
				                        <span class="dspdp-status-off"  <?php _e( 'Offline', 'wpdating' ); ?>></span>
				                    <?php endif; ?>
								</span>
								</h6>
								<div class="user-detail-content">
									<p>
                                        <?php echo $s_age ?> <span data-label="member-label"><?php echo __('year old', 'wpdating'); ?></span> <span class="gender-<?php echo get_gender($s_gender); ?>"><?php echo get_gender($s_gender); ?></span>
                                        <?php if ( $city_name || $state_name || $country_name ){
                                            echo '<span data-label="member-label">'.__('from', 'wpdating') . "</span><br/>" .
                                                ( $city_name ? '<span data-label="member-city">'.$city_name->name . ',</span> ' : '' ) .
                                                ( $state_name ? $state_name->name . ', ' : '' ) .
                                                ( $country_name ? $country_name->name : '' ); ?>
                                        <?php } ?>
                                    </p>
								</div>
							</div>
			        </li>
	            <?php
	            $i++;
	        }
	    }else{ ?>
	        <span class="dsp_span_pointer" style="text-align: center; display: block;"><?php echo __('No Friends Added', 'wpdating'); ?></span>
	    <?php
	    }
	    ?>
</div>