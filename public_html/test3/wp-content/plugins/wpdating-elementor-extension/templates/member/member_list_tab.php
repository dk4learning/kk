<?php
   /* 
   * THIS FILE WILL BE UPDATED WITH EVERY UPDATE
   * IF YOU WANT TO MODIFY THIS FILE, CREATE A CHILD THEME
   *
   * http://codex.wordpress.org/Child_Themes
   */
	//For min and max age
	global $wpdb;
    $general_settings = Wpdating_Elementor_Extension_Helper_Functions::get_settings();
    $genders          = Wpdating_Elementor_Extension_Helper_Functions::get_genders( $general_settings->male->value,
                                                                                    $general_settings->female->value,
                                                                                    $general_settings->couples->value );

    $sql_query = "SELECT DISTINCT user.ID user_id, user.user_login user_name, user.display_name user_display_name,
                    user_profile.make_private user_photo_private, user_profile.gender user_gender,
                    (year(CURDATE())-year(user_profile.age)) user_age,
                    country.name user_country, state.name user_state, city.name user_city,
                    members_photo.picture as user_image,
                    IF(user_online.user_id, 'Y', 'N') user_online_status
                    FROM {$wpdb->users} user
                    JOIN {$wpdb->prefix}dsp_user_profiles user_profile
                    ON user.ID = user_profile.user_id AND user_profile.status_id = 1 AND user_profile.country_id > 0";

    if ( is_user_logged_in() ) {
        $current_user_id = get_current_user_id();
        $sql_query .= " AND user_profile.user_id <> {$current_user_id}";
    }

    if ( $general_settings->male->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'M'";
    }

    if ( $general_settings->female->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'F'";
    }

    if ( $general_settings->couples->status == 'N' ) {
        $sql_query .= " AND user_profile.gender != 'C'";
    }

    $sql_query .= " LEFT JOIN {$wpdb->prefix}dsp_country country
                    ON user_profile.country_id = country.country_id
                    LEFT JOIN {$wpdb->prefix}dsp_state state
                    ON user_profile.state_id = state.state_id
                    LEFT JOIN {$wpdb->prefix}dsp_city city
                    ON user_profile.city_id = city.city_id
                    LEFT JOIN {$wpdb->prefix}dsp_members_photos members_photo
                    on user.ID = members_photo.user_id
                    LEFT JOIN {$wpdb->prefix}dsp_user_online user_online
                    ON user.ID = user_online.user_id";

    $user_count = $wpdb->get_var( "SELECT COUNT(*) FROM ($sql_query) AS total" ); ?>
    <div class="member-list-tab-wrap">
	    <?php if ( $user_count > 0 ) : ?>
		    <header class="member-list-tab-list">
                <ul class="button-group filters-button-group no-list d-flex align-center">
                	<li class="button is-checked" data-filter="*"><i class="fa fa-star"></i><?php esc_html_e('All','wpdating');?></li>
                    <?php if ( $general_settings->male->status == 'Y' ) : ?>
                    <li class="button" data-filter=".man"><i class="fa fa-mars-stroke"></i><?php esc_html_e('Man','wpdating');?></li>
                    <?php endif; ?>
                    <?php if ( $general_settings->female->status == 'Y' ) : ?>
                    <li class="button" data-filter=".women"><i class="fa fa-venus"></i><?php esc_html_e('Woman','wpdating');?></li>
                    <?php endif; ?>
                    <li class="button" data-filter=".online-member"><i class="fa fa-user"></i><?php esc_html_e('Online Members','wpdating');?></li>
                </ul>
            </header>
		    <div class="member-list-tab-content main-member-list-wrap">
	            <?php
                $limit          = ! empty( $general_settings->search_result->value ) ? $general_settings->search_result->value : 12;
                $search_members = $wpdb->get_results( $sql_query . " LIMIT 0, $limit" );
	            foreach ( $search_members as $search_member ) :
                    $profile_link      = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $search_member->user_name );
                    $user_display_name = ( $general_settings->display_user_name->value == 'display_user_name')
                        ? $search_member->user_name :
                        Wpdating_Elementor_Extension_Helper_Functions::get_full_name_by_user_id( $search_member->user_id, $search_member->user_name );
                    $class = '';
                    if ( $search_member->user_gender == 'M' ){
                    	$class .= ' man';
                    } elseif ( $search_member->user_gender == 'F' ){
                    	$class .= ' women';
                    }

                    if ( $search_member->user_online_status == 'Y' ){
                    	$class .= ' online-member';
                    }
	                ?>
	               
	               	<div class="member-detail-wrap <?php echo $class; ?>">
						<figure class="img-holder">				
			                <a href="<?php echo esc_url($profile_link);?>" > 
                                <img src="<?php echo Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $search_member->user_id, $search_member->user_image,
                                    $search_member->user_photo_private, 'N' )->image_350;; ?>" class="dsp_img3 iviewed-img" alt=""/>
                            </a>
	    					<div class="wpee-user-status <?php echo ( $search_member->user_online_status == 'Y' ) ? 'wpee-online' : 'wpee-offline';?>"></div>
						</figure>
						<div class="user-details">
							<h6 class="member-user-name">
				                <span class="user-name-show">
		                            <a href="<?php echo esc_url( $profile_link );?>">		
			                            <?php echo $user_display_name; ?>
			                        </a>
				                </span>
							</h6>
							<div class="user-detail-content">
								<p><?php echo $search_member->user_age; ?> <span data-label="member-label"><?php echo __('year old', 'wpdating'); ?></span> <span class="gender-<?php echo $genders->{$search_member->user_gender}; ?>"><?php echo $genders->{$search_member->user_gender}; ?></span> <span data-label="member-label"><?php echo __('from', 'wpdating'); ?></span>
                                    <br /><?php echo ( ! empty( $search_member->user_city ) ? '<span data-label="member-city">'.$search_member->user_city . ',</span> '  : '' ) . ( ! empty( $search_member->user_state ) ? $search_member->user_state  . ', ': '' ) . ( ! empty( $search_member->user_country ) ? $search_member->user_country : '' ) ?>
							</div>
						</div>
					</div>
	                <?php
	            endforeach; ?>
		    </div>
	    <?php else : ?>
	    	<div class="main-member-list-wrap">
                <div class="records-not-found">
                    <p><?php echo __('No record found.', 'wpdating'); ?></p>
                </div>
	        </div>
	    <?php endif; ?>
	</div>