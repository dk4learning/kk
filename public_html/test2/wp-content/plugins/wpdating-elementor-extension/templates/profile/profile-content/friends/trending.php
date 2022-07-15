<?php 
global $wpdb;
$user_id = get_current_user_id();
$member_id = wpee_profile_id();
$dsp_user_profiles = $wpdb->prefix . 'dsp_user_profiles';
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
$dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$check_couples_mode = wpee_get_setting('couples');
$check_search_result = wpee_get_setting('search_result');
$check_my_friend_module = wpee_get_setting( 'my_friends' );
$imagepath = content_url('/') ;
$profile_page = get_option( 'wpee_profile_page', '' );
$profile_page_url = get_permalink( $profile_page );	
global $wp;
$current_url =  trailingslashit( home_url( $wp->request ) ); 
$profile_page_url = $current_url;
if (strpos($profile_page_url, '/page') == true) {
	$pos = strpos($current_url , '/page');
	// remove string from the specific postion
	$profile_page_url = trailingslashit( substr( $current_url, 0, $pos ) ); // remove /page/.. from url for pagination
}
?>
<div class="profile-section-content profile-friends-trending">
    	<?php
	    $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
		$dsp_messages_table = $wpdb->prefix . DSP_EMAILS_TABLE;
		$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
		$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
		$dsp_favourites_list_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
	    $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
	    $dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
	    $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
		// ----------------------------------------------- Start Paging code------------------------------------------------------ //
		    // if (isset($profile_filter) && $profile_filter == 'all') {
		    $strQuery = "SELECT p.user_id, p.country_id, p.state_id, p.city_id, p.gender, p.seeking, p.zipcode, p.age,  p.pic_status,p.about_me,p.status_id,p.reason_for_status,p.edited,p.last_update_date as count from $dsp_user_profiles_table p where p.country_id > 0 ";
		    $strQuery .= empty($gender) ? '' :  " AND p.gender='$gender' ";
		    $page = (get_query_var('paged')) ? esc_sql(get_query_var('paged')) : 1;
		    // How many adjacent pages should be shown on each side?
		    $adjacents = 2;
		    $limit = intval($check_search_result->setting_value)>0?$check_search_result->setting_value : 12;
		    if ($page)
		        $start = ($page - 1) * $limit;    //first item to display on this page
		    else
		        $start = 0;
		    $intRecordsPerPage = 1;
		    $intStartLimit = get('p'); # page selected 1,2,3,4...
		    if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
		        $intStartLimit = 1; //default
		    }
		    $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
		    //$strQuery .= " AND `stealth_mode`='N' ";
		    @$strQuery = $strQuery . " ORDER BY count desc";
		    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");		    
		   
		    $total_results1 = $user_count;
		    // Calculate total number of pages. Round up using ceil()
		    //$total_pages1 = ceil($total_results1 / $max_results1); 
		    if ($page == 0)
		        $page = 1;     //if no page var is given, default to 1.
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
		            $pagination.= "<div><a style='color:#365490' href=\"". esc_url($profile_page_url)."page/" . ($prev) . $page_name ."\">".__('Previous', 'wpdating')."</a></div>";
		        else
		            $pagination.= "<span  class='disabled'>".__('Previous', 'wpdating')."</span>";

		        //pages
		        if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
		            for ($counter = 1; $counter <= $lastpage; $counter++) {
		                if ($counter == $page)
		                    $pagination.= "<span class='current'>$counter</span>";
		                else
		                    $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $counter  . $page_name . "\">$counter</a></div>";
		            }
		        }
		        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
		            //close to beginning; only hide later pages
		            if ($page < 1 + ($adjacents * 2)) {
		                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
		                    if ($counter == $page)
		                        $pagination.= "<span class='current'>$counter</span>";
		                    else
		                        $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $counter .'/'. $page_name . "\">$counter</a></div>";
		                }
		                $pagination.= "<span>...</span>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $lpm1 .'/'. $page_name . "\">$lpm1</a></div>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $lastpage .'/'. $page_name . "\">$lastpage</a></div>";
		            }
		            //in middle; hide some front and some back
		            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . 1 .'/'. $page_name . "\">1</a></div>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . 2 .'/'. $page_name . "\">2</a></div>";
		                $pagination.= "<span>...</span>";
		                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
		                    if ($counter == $page)
		                        $pagination.= "<div class='current'>$counter</div>";
		                    else
		                        $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $counter .'/'. $page_name . "\">$counter</a></div>";
		                }
		                $pagination.= "<span>...</span>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $lpm1 .'/'. $page_name . "\">$lpm1</a></div>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . $lastpage .'/'. $page_name . "\">$lastpage</a></div>";
		            }
		            //close to end; only hide early pages
		            else {
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . 1 .'/'. $page_name . "\">1</a></div>";
		                $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . 2 .'/'. $page_name . "\">2</a></div>";
		                $pagination.= "<span>...</span>";
		                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
		                    if ($counter == $page)
		                        $pagination.= "<span class='current'>$counter</span>";
		                    else
		                        $pagination.= "<div><a href=\"". esc_url($profile_page_url)."page/" . ($counter) .'/'. $page_name . "\">$counter</a></div>";
		                }
		            }
		        }

		        //next button
		        if ($page < $counter - 1)
		            $pagination.= "<div><a style='color:#365490' href=\"". esc_url($profile_page_url)."page/" . ($next) .'/'. $page_name . "\">".__('Next', 'wpdating')."</a></div>";
		        else
		            $pagination.= "<span class='disabled'>".__('Next', 'wpdating')."</span>";
		        $pagination.= "</div>\n";
		    }

		// ------------------------------------------------End Paging code------------------------------------------------------ // 
		?>
		<?php
		if ($user_count > 0) : 
		    //echo $strQuery . " LIMIT $start, $limit  ";
		    $search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
		?>
    	<ul class="friends-section"><!-- friends-section class used -->
            <?php
            foreach ($search_members as $member1) { 
                if ($member1->user_id != 0) {
                        if ($check_couples_mode->setting_status == 'Y') {
                            $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                        } else {
                            $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                        }
                        $s_user_id = $member->user_id;
                        $stealth_mode = $member->stealth_mode;
                        $s_country_id = $member->country_id;
                        $s_gender = $member->gender;
                        $s_seeking = $member->seeking;
                        $s_state_id = $member->state_id;
                        $s_city_id = $member->city_id;
                        $s_age = GetAge($member->age);
                        $s_make_private = $member->make_private;
                        $s_user_pic = isset($member->user_pic) ? $member->user_pic : '';
                        $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
                        $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
                        $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
                        $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $tbl_name WHERE user_id=$s_user_id"); 
                        $favt_mem = array();
                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
						$check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$member1->user_id' AND user_id='$user_id' AND approved_status='Y'");
						$check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$member1->user_id");
                		$exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$s_user_id'");
                        foreach ($private_mem as $private) {
                            $favt_mem[] = $private->favourite_user_id;
                        }
                        $online =  (($stealth_mode == 'N') && $check_online_user > 0) ? " dspdp-status-on " : " dspdp-status-off "; 
                ?>
               
               	<li class="member-detail-wrap">
               		<?php
                    $profile_link = wpee_get_profile_url_by_id( $s_user_id );
	            	$displayed_member_name = wpee_get_user_display_name_by_id($s_user_id);
                    if ($exist_make_private->make_private == 'Y') { 
                        if (!in_array($current_user->ID, $favt_mem)) {
                            $profile_image= WPDATE_URL. '/images/private-photo-pic.jpg';
                        }
                        else{
                            $profile_image = wpee_display_members_photo($s_user_id, $imagepath);
                        }

                    }
                    else{
                        $profile_image = wpee_display_members_photo($s_user_id, $imagepath);
                    }
                     ?>
                    <figure>                                
                        <a href="<?php echo esc_url($profile_link);?>" > 
                            <img src="<?php echo $profile_image; ?>" class="dsp_img3 iviewed-img"/>
                        </a>
                    </figure>
					<div class="user-details">
						<h6 class="member-user-name">
			                <span class="user-name-show">
	                            <a href="<?php echo esc_url( $profile_link );?>">		
		                            <?php echo $displayed_member_name; ?>
		                        </a>
			                </span>
	    					<span class="wpee-user-status <?php echo ( wpee_get_online_user($s_user_id) ) ? 'wpee-online' : 'wpee-offline';?>"></span>
						</h6>
						<div class="user-detail-content">
							<p><?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($s_gender); ?> <?php echo __('from', 'wpdating'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?></p>
						</div>
					</div>
				</li>
                <?php
                    }
                } 
            ?>
        </ul>
        <div class="row-paging"> 
            <div style="float:left; width:100%;">
                <?php
                // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                 echo $pagination
                // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>
            </div>  
        </div>  
		<?php else : ?>
        <div class="box-border">
            <div class="box-pedding">
                <div class="page-not-found">
                    <?php echo __('No result found !', 'wpdating'); ?><br /><br />
                </div>
            </div>
        </div>
	<?php endif; ?>
</div>