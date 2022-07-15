<?php
   /* 
   * THIS FILE WILL BE UPDATED WITH EVERY UPDATE
   * IF YOU WANT TO MODIFY THIS FILE, CREATE A CHILD THEME
   *
   * http://codex.wordpress.org/Child_Themes
   * 
   * This a template is to change when ajax search is done by username
   */


	//For min and max age
	global $wpdb;
	$check_min_age = wpee_get_setting('min_age');
	$min_age_value = $check_min_age->setting_value;
	$check_max_age = wpee_get_setting('max_age');
	$max_age_value = $check_max_age->setting_value;
	$check_near_me = wpee_get_setting('near_me');
	$member_page = get_option( 'wpee_member_page', '' );
	$member_page_url = get_permalink($member_page);
	$profile_page = get_option( 'wpee_profile_page', '' );
	$profile_page_url = get_permalink($profile_page);
	$check_search_result = wpee_get_setting('search_result'); // get setting of search result
	$check_couples_mode = wpee_get_setting('couples'); // get setting of couples
	$check_distance_mode = wpee_get_setting('distance_feature'); // get setting of couples
	$check_flirt_module = wpee_get_setting('flirt_module'); // get setting of flirt module
	$check_my_friend_module = wpee_get_setting('my_friends'); // get setting of friend module

	$user_id = get_current_user_id();

	$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
	$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
	$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
	$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
	$dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
	$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
	$dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
	$dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;

	$dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
	$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
	$dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
	$dsp_user_photos_table = $wpdb->prefix . DSP_USER_PHOTOS_TABLE;
	$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
	$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
	$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
	$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
	$dsp_spam_filter_table = $wpdb->prefix . DSP_SPAM_FILTERS_TABLE;
	$dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
	$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
	$dsp_flirt_table = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
	$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
	$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
	$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
	$dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
	$dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
	$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
	$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
	$dsp_tmp_member_audios_table = $wpdb->prefix . DSP_TEMP_MEMBER_AUDIOS_TABLE;
	$dsp_tmp_member_videos_table = $wpdb->prefix . DSP_TEMP_MEMBER_VIDEOS_TABLE;
	$dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
	$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
	$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
	$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
	$dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
	$imagepath = content_url( '/' );


    $page = (get_query_var('paged')) ? esc_sql(get_query_var('paged')) : 1;
    // How many adjacent pages should be shown on each side?
    $adjacents = 2;
    $limit = intval($check_search_result->setting_value)>0?$check_search_result->setting_value : 12;
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;
    $username = isset($_REQUEST['username']) ? esc_sql(wpee_sanitizeData(trim($_REQUEST['username']), 'xss_clean')) : get('username');
    $adjacents = 2;
    $limit = intval($check_search_result->setting_value)>0?$check_search_result->setting_value : 12;
    if ($page)
        $start = ($page - 1) * $limit;    //first item to display on this page
    else
        $start = 0;
	$strQuery = "SELECT DISTINCT (fb.user_id) FROM $dsp_user_profiles fb, $dsp_user_table users  WHERE fb.user_id=users.ID AND users.user_login like'%$username%' ORDER BY fb.user_profile_id desc";
    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");

    if (isset($user_count))
        $total_results1 = $user_count;
    else
        $total_results1 = 0;
    if ($page == 0){
        $page = 1;     //if no page var is given, default to 1.
    }
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_results1 / $limit);
    ;  //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;

    /*
      Now we apply our rules and draw the pagination object.
      We're actually saving the code to a variable in case we want to draw it more than once.
     */
    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<form id='pagination-form' method='post'>";
        foreach ($page_name_arr as $fields ) {
        	$field = explode( '=', $fields );
        	if( $field[0] == 'submit'){
        		$pagination .= '<input type="submit" style="display:none;" name="'.$field[0].'" value="'.$field[1].'">'; 
        	}
        	else{        		
        		$pagination .= '<input type="hidden" name="'.$field[0].'" value="'.$field[1].'">'; 
        	}
        }
        $pagination .= '<input type="hidden" name="paged" value="">';

        $pagination.= "</form>\n";
        $pagination .= "<div class='wpse_pagination'>";
        //previous button
        if ($page > 1)
            $pagination.= "<div><a style='color:#365490' href=\"". esc_url($member_page_url)."page/" . ($prev) . $page_name ."\">".__('Previous', 'wpdating')."</a></div>";
        else
            $pagination.= "<span  class='disabled'>".__('Previous', 'wpdating')."</span>";

        //pages
        if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class='current'>$counter</span>";
                else
                    $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $counter  . $page_name . "\">$counter</a></div>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $counter .'/'. $page_name . "\">$counter</a></div>";
                }
                $pagination.= "<span>...</span>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $lpm1 .'/'. $page_name . "\">$lpm1</a></div>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $lastpage .'/'. $page_name . "\">$lastpage</a></div>";
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . 1 .'/'. $page_name . "\">1</a></div>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . 2 .'/'. $page_name . "\">2</a></div>";
                $pagination.= "<span>...</span>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<div class='current'>$counter</div>";
                    else
                        $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $counter .'/'. $page_name . "\">$counter</a></div>";
                }
                $pagination.= "<span>...</span>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $lpm1 .'/'. $page_name . "\">$lpm1</a></div>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . $lastpage .'/'. $page_name . "\">$lastpage</a></div>";
            }
            //close to end; only hide early pages
            else {
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . 1 .'/'. $page_name . "\">1</a></div>";
                $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . 2 .'/'. $page_name . "\">2</a></div>";
                $pagination.= "<span>...</span>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<div><a href=\"". esc_url($member_page_url)."page/" . ($counter) .'/'. $page_name . "\">$counter</a></div>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination.= "<div><a style='color:#365490' href=\"". esc_url($member_page_url)."page/" . ($next) .'/'. $page_name . "\">".__('Next', 'wpdating')."</a></div>";
        else
            $pagination.= "<span class='disabled'>".__('Next', 'wpdating')."</span>";
        $pagination.= "</div>\n";
    }
// ------------------------------------------------End Paging code------------------------------------------------------ //
	    if (isset($user_count))
	        $intTotalRecordsEffected = $user_count;
	    else
	        $intTotalRecordsEffected = 0;

	    if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
	    ?>
		    <div id="search_width" class="main-member-list-wrap">
		            <?php
		            //echo $kontry;
		            //echo $strQuery ." LIMIT $start, $limit  ";//die;
		            $search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");  
		            if (isset($username) && $username != '' && !empty($search_members) )

		            {

		                foreach($search_members as $member1){
			                $search_user_id = $member1->user_id;
			                $search_members = $wpdb->get_results("SELECT *,(year(CURDATE())-year(fb.age)) age  FROM $dsp_user_profiles fb WHERE fb.user_id = '$search_user_id'");

			                //new code
			                    if ($check_couples_mode->setting_status == 'Y') {
			                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
			                } else {
			                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
			                }
			                $s_user_id = $member->user_id;
				        	$username = wpee_get_user_display_name_by_id( $s_user_id );
				        	$profile_link = trailingslashit($profile_page_url) . $username;
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
			                $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
			                $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
			                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
			                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
			                $favt_mem = array();
			                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
			                foreach ($private_mem as $private) {
			                    $favt_mem[] = $private->favourite_user_id;
			                }
			                ?>
			               
			               	<div class="member-detail-wrap">
								<figure class="img-holder">				
					                <?php if ($s_make_private == 'Y') { ?>

					                    <?php if ($current_user_id != $s_user_id) { ?>

					                        <?php if (!in_array($current_user_id, $favt_mem)) { ?>
					                            <a href="<?php echo $profile_link; ?>" >
					                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
					                            </a>
					                        <?php } else {
					                            ?>
					                            <a href="<?php echo $profile_link; ?>" >
					                                <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>
					                            <?php
					                        }
					                    } else {
					                        ?>
					                        <a href="<?php echo $profile_link ; ?>">
					                            <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
					                        </a>
					                    <?php } ?>
					                <?php } else { ?>

					                    <a href="<?php echo $profile_link ; ?>">
					                        <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
					                    </a>
					                <?php } ?>
								</figure>
								<div class="user-details">
									<h6 class="member-user-name">
									<?php
					                if ($check_couples_mode->setting_status == 'Y') {
					                    if ($s_gender == 'C') {
					                        ?>
					                        <a href="<?php echo $profile_link; ?>">
					                            <?php
					                            if (strlen($displayed_member_name->display_name) > 15)
					                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
					                            else
					                                echo $displayed_member_name->display_name;
					                            ?>
					                   		 </a>
					                <?php } else { ?>
					                    <a href="<?php echo $profile_link; ?>">
					                        <?php
					                        if (strlen($displayed_member_name->display_name) > 15)
					                            echo substr($displayed_member_name->display_name, 0, 13) . '...';
					                        else
					                            echo $displayed_member_name->display_name;
					                        ?>
					                    </a>
					                        <?php
					                    }
					            } else {
					                ?>
					                <a href="<?php echo $profile_link; ?>">
					                    <?php
					                    if (strlen($displayed_member_name->display_name) > 15)
					                        echo substr($displayed_member_name->display_name, 0, 13) . '...';
					                    else
					                        echo $displayed_member_name->display_name;
					                    ?>
					                    </a>
					                <?php } ?>
									<span class="online dspdp-online-status">
										<?php
					                    //echo $fav_icon_image_path;
					                    if ($check_online_user > 0)
					                        echo '<span class="dspdp-status-on" '.__('Online', 'wpdating').'></span>';
					                    else
					                        echo '<span class="dspdp-status-off" '.__('Offline', 'wpdating').'></span>';
					                    ?>
									</span>
									</h6>
									<div class="user-detail-content">
										<p><?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($s_gender); ?> <?php echo __('from', 'wpdating'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?></p>
									</div>
								</div>
							</div>
			                <?php

			                //last of new code

			            }
		            }
		            else{
		            foreach ($search_members as $member1) {
		                if ($check_couples_mode->setting_status == 'Y') {
		                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
		                } else {
		                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
		                }
		                $s_user_id = $member->user_id;
			        	$username = wpee_get_user_display_name_by_id( $s_user_id );
			        	$profile_link = trailingslashit($profile_page_url) . $username;
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
		                $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
		                $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
		                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
		                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
		                $favt_mem = array();
		                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
		                foreach ($private_mem as $private) {
		                    $favt_mem[] = $private->favourite_user_id;
		                }
		                ?>
		               
		               	<div class="member-detail-wrap">
							<figure class="img-holder">				
				                <?php if ($s_make_private == 'Y') { ?>

				                    <?php if ($current_user_id != $s_user_id) { ?>

				                        <?php if (!in_array($current_user_id, $favt_mem)) { ?>
				                            <a href="<?php echo $profile_link; ?>" >
				                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"   border="0" class="img-big" alt="Private Photo" />
				                            </a>
				                        <?php } else {
				                            ?>
				                            <a href="<?php echo $profile_link; ?>" >
				                                <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"      border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>
				                            <?php
				                        }
				                    } else {
				                        ?>
				                        <a href="<?php echo $profile_link ; ?>">
				                            <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
				                        </a>
				                    <?php } ?>
				                <?php } else { ?>

				                    <a href="<?php echo $profile_link ; ?>">
				                        <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"  border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
				                    </a>
				                <?php } ?>
							</figure>
							<div class="user-details">
								<h6 class="member-user-name">
								<?php
				                if ($check_couples_mode->setting_status == 'Y') {
				                    if ($s_gender == 'C') {
				                        ?>
				                        <a href="<?php echo $profile_link; ?>">
				                            <?php
				                            if (strlen($displayed_member_name->display_name) > 15)
				                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
				                            else
				                                echo $displayed_member_name->display_name;
				                            ?>
				                   		 </a>
				                <?php } else { ?>
				                    <a href="<?php echo $profile_link; ?>">
				                        <?php
				                        if (strlen($displayed_member_name->display_name) > 15)
				                            echo substr($displayed_member_name->display_name, 0, 13) . '...';
				                        else
				                            echo $displayed_member_name->display_name;
				                        ?>
				                    </a>
				                        <?php
				                    }
				            } else {
				                ?>
				                <a href="<?php echo $profile_link; ?>">
				                    <?php
				                    if (strlen($displayed_member_name->display_name) > 15)
				                        echo substr($displayed_member_name->display_name, 0, 13) . '...';
				                    else
				                        echo $displayed_member_name->display_name;
				                    ?>
				                    </a>
				                <?php } ?>
								<span class="online dspdp-online-status">
									<?php
				                    //echo $fav_icon_image_path;
				                    if ($check_online_user > 0)
				                        echo '<span class="dspdp-status-on" '.__('Online', 'wpdating').'></span>';
				                    else
				                        echo '<span class="dspdp-status-off" '.__('Offline', 'wpdating').'></span>';
				                    ?>
								</span>
								</h6>
								<div class="user-detail-content">
									<p><?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($s_gender); ?> <?php echo __('from', 'wpdating'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?></p>
								</div>
							</div>
						</div>
		                <?php
		            }// foreach($search_members as $member)
		        }
		            ?>
		    </div>
		    <div class="row-paging">
		        <div style="float:left; width:100%;">
		            <?php
		            // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
		            echo $pagination;
		// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
		            ?>
		        </div>
		    </div>
	    <?php } else { ?>
	        <div class="box-border">
	            <div class="box-pedding">
	                <div class="page-not-found">
                    	<p><?php echo __('No record found for your search criteria.', 'wpdating'); ?></p>
	                </div>
	            </div>
	        </div>
	    <?php } ?>