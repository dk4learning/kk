<?php
 global $wpdb, $wpee_general_settings;
$album_id = isset($_GET['album_id']) ? $_GET['album_id'] : '';
$album_button = __( 'Add Album','wpdating');
$action = isset($_GET['action']) ? $_GET['action'] : ''; // For Get method update and delete
$mode = isset($_POST['albummode']) ? $_POST['albummode'] : ''; // For Post method add and update
$user_id = wpee_profile_id();
$current_user = get_current_user_id();
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_link = trailingslashit(wpee_get_profile_url_by_id( $current_user));
$current_url = $profile_link . '/viewed/viewed_by_me';
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_table = $wpdb->users;
$imagepath = content_url('/');

if( is_user_logged_in() && $current_user == $user_id ){
	// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
		$page = ( empty( get_query_var( 'paged' ) ) ||  get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );

		// How many adjacent pages should be shown on each side?
		$adjacents = 2;
		$limit = isset($check_search_result->setting_value) && !empty($check_search_result->setting_value) ? $check_search_result->setting_value : 6;
		if ($page)
		    $start = ($page - 1) * $limit;    //first item to display on this page
		else
		    $start = 0;
		// ----------------------------------------------- Start Paging code------------------------------------------------------ //
		$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
		$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
		$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
		$dsp_counter_hits_query = "SELECT Distinct hits.user_id FROM $dsp_counter_hits_table hits, $dsp_user_profiles_table profile WHERE hits.member_id=profile.user_id AND hits.member_id=$user_id ";
		//$dsp_counter_hits_query = "SELECT Distinct user_id FROM $dsp_counter_hits_table WHERE member_id=$user_id ";
        if ( $wpee_general_settings->couples->status == 'Y' ) {
		    $strQuery = "SELECT * FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where p.user_id=h.user_id and p.status_id=1 and h.member_id=$user_id GROUP BY p.user_id";
		} else {
		    $strQuery = "SELECT * FROM $dsp_user_profiles_table p, $dsp_counter_hits_table h where p.user_id=h.user_id and p.status_id=1 and h.member_id=$user_id and p.gender!='C' GROUP BY p.user_id";
		}

		$intRecordsPerPage = 1;
		$intStartLimit = get('p'); # page selected 1,2,3,4...
		if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
		    $intStartLimit = 1; //default
		}
		$intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
		$strQuery = $strQuery . " ORDER BY p.user_profile_id desc";
		$user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($dsp_counter_hits_query) AS total");
		// ----------------------------------------------- Start Paging code------------------------------------------------------ //
		$page_name = trailingslashit($current_url);
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
		    $pagination .= "<div class='wpse_pagination'>";
		    //previous button
		    if ($page > 1)
		        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev/\">".__('Previous', 'wpdating')."</a></div>";
		    else
		        $pagination.= "<span  class='disabled'>".__('Previous', 'wpdating')."</span>";

		    //pages	
		    if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
		        for ($counter = 1; $counter <= $lastpage; $counter++) {
		            if ($counter == $page)
		                $pagination.= "<span class='current'>$counter</span>";
		            else
		                $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
		        }
		    }
		    elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
		        //close to beginning; only hide later pages
		        if ($page < 1 + ($adjacents * 2)) {
		            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
		                if ($counter == $page)
		                    $pagination.= "<span class='current'>$counter</span>";
		                else
		                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
		            }
		            $pagination.= "<span>...</span>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
		        }
		        //in middle; hide some front and some back
		        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
		            $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
		            $pagination.= "<span>...</span>";
		            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
		                if ($counter == $page)
		                    $pagination.= "<div class='current'>$counter</div>";
		                else
		                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
		            }
		            $pagination.= "<span>...</span>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1/\">$lpm1</a></div>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage/\">$lastpage</a></div>";
		        }
		        //close to end; only hide early pages
		        else {
		            $pagination.= "<div><a href=\"" . $page_name . "page/1/\">1</a></div>";
		            $pagination.= "<div><a href=\"" . $page_name . "page/2/\">2</a></div>";
		            $pagination.= "<span>...</span>";
		            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
		                if ($counter == $page)
		                    $pagination.= "<span class='current'>$counter</span>";
		                else
		                    $pagination.= "<div><a href=\"" . $page_name . "page/$counter/\">$counter</a></div>";
		            }
		        }
		    }

		    //next button
		    if ($page < $counter - 1)
		        $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$next/\">".__('Next', 'wpdating')."</a></div>";
		    else
		        $pagination.= "<span class='disabled'>".__('Next', 'wpdating')."</span>";
		    $pagination.= "</div>\n";
		}

		// ------------------------------------------------End Paging code------------------------------------------------------ // 
		$intTotalRecordsEffected = $user_count;

		if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
		    //print "Total records found: " . $intTotalRecordsEffected;
		} else {
		    ?>

		    <div class="box-border">
		        <div class="box-pedding">
		            <div class="page-not-found">
		                <?php echo __('No record found.', 'wpdating'); ?><br /><br />
		            </div>
		        </div>
		    </div>

		    <?php
		} // if ($intTotalRecordsEffected != '0')	
		$search_members = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
		//echo $strQuery ." LIMIT " . $from1 . "," . $max_results1; 
		?>

	    <?php
	    if (isset($user_count))
	        $intTotalRecordsEffected = $user_count;
	    else
	        $intTotalRecordsEffected = 0;

	    if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
	    ?>
		    <div id="search_width" class="main-member-list-wrap">
	            <?php
	            foreach ( $search_members as $member1 ) {
                    if ( $wpee_general_settings->couples->status == 'Y' ) {
	                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
	                } else {
	                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
	                }
	                $s_user_id = $member->user_id;
		        	$profile_link = trailingslashit(wpee_get_profile_url_by_id( $member1->user_id));
	                $s_country_id = isset($member->country_id) ? $member->country_id : '';
	                $s_gender = isset($member->gender) ? $member->gender : '';
	                $s_seeking = isset($member->seeking) ? $member->seeking : '';
	                $s_state_id = isset($member->state_id) ? $member->state_id : '';
	                $s_city_id = isset($member->city_id) ? $member->city_id : '';
	                $s_age = isset($member->age) ? GetAge($member->age) : '';
	                $s_make_private = isset($member->make_private) ? $member->make_private : '';
	                $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
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
			                    <?php if ($current_user_id != $s_user_id) { 
			                        if (!in_array($current_user_id, $favt_mem)) { 
			                    		$image_src = WPDATE_URL . '/images/private-photo-pic.jpg';
			                        } else {
			                    		$image_src = wpee_display_members_photo_thumb($s_user_id, $imagepath);
			                        }
			                    } else {
			                    	$image_src = wpee_display_members_photo_thumb($s_user_id, $imagepath);
			                        ?>
			                    <?php } ?>
			                <?php }  ?>

		                    <a href="<?php echo $profile_link; ?>">
		                        <img src="<?php echo wpee_display_members_photo_thumb($s_user_id, $imagepath); ?>"
                                     border="0" class="img-big" alt="<?php echo wpee_get_user_display_name_by_id($s_user_id); ?>"/>
		                    </a>
						</figure>
						<div class="user-details">
							<h6 class="member-user-name">
                                <a href="<?php echo $profile_link; ?>">
                                    <?php echo wpee_get_user_display_name_by_id( $s_user_id ); ?>
                                    </a>
                                <span class="online dspdp-online-status">
                                    <?php if ( wpee_get_online_user( $s_user_id ) ) : ?>
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
					</div>
				<?php } ?>
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
	    <?php
	} 
}?>