<?php
   /* 
   * THIS FILE WILL BE UPDATED WITH EVERY UPDATE
   * IF YOU WANT TO MODIFY THIS FILE, CREATE A CHILD THEME
   *
   * http://codex.wordpress.org/Child_Themes
   */
	//For min and max age
	global $wpdb, $wpee_settings;

    $general_settings       = Wpdating_Elementor_Extension_Helper_Functions::get_settings();
    $genders                = Wpdating_Elementor_Extension_Helper_Functions::get_genders( $general_settings->male->value,
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

    $user_name_filter_active = 0;

    if ( isset( $_REQUEST['submit'] ) ) {
        $filter_active    = 1;
        $where_conditions = [];
   	    if ( isset( $_REQUEST['gender'] ) && ! empty( $_REQUEST['gender'] ) ) {
            $where_conditions[] = "user_profile.seeking = '{$_REQUEST['gender']}'";
        }

        if ( isset( $_REQUEST['seeking'] ) && ! empty( $_REQUEST['seeking'] ) ) {
            $where_conditions[] = "user_profile.gender = '{$_REQUEST['seeking']}'";
        }

        if ( isset( $_REQUEST['cmbCountry'] ) && ! empty( $_REQUEST['cmbCountry'] ) ) {
            $where_conditions[] = "user_profile.country_id = '{$_REQUEST['cmbCountry']}'";
        }

        if ( isset( $_REQUEST['cmbState'] ) && ! empty( $_REQUEST['cmbState'] ) ) {
            $where_conditions[] = "user_profile.state_id = '{$_REQUEST['cmbState']}'";
        }

        if ( isset( $_REQUEST['cmbCity'] ) && ! empty( $_REQUEST['cmbCity'] ) ) {
            $where_conditions[] = "user_profile.city_id = '{$_REQUEST['cmbCity']}'";
        }

        if ( isset( $_REQUEST['username'] ) && ! empty( $_REQUEST['username'] ) ) {
            $user_name_filter_active = 1;
            $where_conditions[]      = "user.user_login LIKE '%{$_REQUEST['username']}%'";
        }

        if ( isset( $_REQUEST['Pictues_only'] ) && $_REQUEST['Pictues_only'] == 'Y' ) {
            $sql_query = str_replace("LEFT JOIN {$wpdb->prefix}dsp_members_photos", "JOIN {$wpdb->prefix}dsp_members_photos", $sql_query );
            $where_conditions[] = "user_profile.make_private != 'Y";
        }

        if ( isset( $_REQUEST['Online_only'] ) && $_REQUEST['Online_only'] == 'Y' ) {
            $sql_query = str_replace("LEFT JOIN {$wpdb->prefix}dsp_user_online", "JOIN {$wpdb->prefix}dsp_user_online", $sql_query );
            $where_conditions[] = "user_profile.make_private != 'Y";
        }

        if ( isset($request['profile_question_option_id[]']) && !empty($request['profile_question_option_id[]']) ){
            $sql_query .= " INNER JOIN {$wpdb->prefix}dsp_profile_questions_details AS profile_question_detail
                            ON user.ID = question.user_id";

            $question_values    = implode(',', $request['profile_question_option_id[]']);
            $where_conditions[] = "question.profile_question_option_id IN({$question_values})";
        }

        if ( count( $where_conditions ) > 0 ) {
            $sql_query .= ' WHERE ' . implode(' AND ', $where_conditions);
        }

        if ( ( isset( $_REQUEST['age_from'] ) && ! empty( $_REQUEST['age_from'] ) ) &&
            ( isset( $_REQUEST['age_to'] ) && ! empty( $_REQUEST['age_to'] ) ) ) {
            $sql_query .= " HAVING user_age BETWEEN '{$_REQUEST['age_from']}' AND '{$_REQUEST['age_to']}'";
        } else if ( ( isset( $_REQUEST['age_from'] ) && ! empty( $_REQUEST['age_from'] ) ) ) {
            $sql_query .= " HAVING user_age >= '{$_REQUEST['age_from']}'";
        } else if ( ( isset( $_REQUEST['age_to'] ) && ! empty( $_REQUEST['age_to'] ) ) ) {
            $sql_query .= " HAVING user_age >= '{$_REQUEST['age_to']}'";
        }

	} else {
   	    $filter_active = 0;
        if ( $general_settings->member_list_gender->value == 2 ) {
            $sql_query .= " WHERE user_profile.gender = 'M'";
        } else if ( $general_settings->member_list_gender->value == 3 ) {
            $sql_query .= " WHERE user_profile.gender = 'F'";
        }
    }

   	$sql_query  .= " ORDER BY user.ID DESC";

    $search_member_count = $wpdb->get_var("SELECT COUNT(*) FROM ({$sql_query}) AS total");

    if ( isset( $wpee_search_error ) && !empty( $wpee_search_error ) ): ?>
		<div class="wpee-search-error wpee-error">
			<?php echo $wpee_search_error;?>
		</div>
	<?php endif; ?>
    <div class="wpee-ajax-filter">
        <form method="post" id="member-ajax-filter" data-page="<?php echo 2; ?>">
            <div class="filter-bar-wrap d-flex space-bet align-center">
                <div class="form-control search-block-wrap">
                    <input type="text" placeholder="<?php _e( 'Search Members', 'wpdating' ); ?>..." name="username"
                           class="form-control username-filter" value="<?php echo esc_attr( ( isset( $_REQUEST['username'] ) ? $_REQUEST['username'] : '' ) );?>">
                </div>
                <div class="trigger-filter-icon">
                    <i class="fa fa-sliders fa-rotate-90 trigger-filter"></i>
                </div>
            </div>

            <div class="offcanvase-filter-wrap">
                <span class="filter-close"></span>
                <div class="offcanvase-filter-content-wrap">
                    <div class="offcanvase-filter-title-wrap d-flex space-bet align-center">
                        <h5 class="form-sidebar-title"><?php esc_html_e('Filter', 'wpdating');?></h5>
                        <div class="filter-button-wrap">
                            <a class="button btn wpee-filter-reset" href="javascript:void(0);"><?php esc_html_e('Clear Filter', 'wpdating');?></a>
                            <input type="submit" name="submit" value="<?php esc_attr_e('Apply', 'wpdating');?>">
                        </div>
                    </div>
                    <div class="form-field-group">
                        <?php
                        $gender_options = Wpdating_Elementor_Extension_Helper_Functions::get_gender_options( $genders, ( isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '' ) );
                        if( ! empty( $gender_options ) ): ?>
		                    <div class="form-group">
		                        <label for="gender">
		                            <?php echo __('I am:', 'wpdating'); ?>
		                        </label>
	                            <select id="gender" class="dspdp-form-control dsp-form-control" name="gender">
                                    <option value=""><?php echo __('Select', 'wpdating'); ?></option>
	                                <?php echo $gender_options; ?>
	                            </select>
		                    </div>
                        <?php endif; ?>
                        <?php
                        $seeking_options = Wpdating_Elementor_Extension_Helper_Functions::get_gender_options( $genders, ( isset($_REQUEST['seeking']) ? $_REQUEST['seeking'] : '' ) );
                        if( ! empty( $seeking_options ) ): ?>
                            <div class="form-group">
                                <label for="seeking">
                                    <?php echo __('Seeking a:', 'wpdating'); ?>
                                </label>
                                <select id="seeking" name="seeking" class="dspdp-form-control dsp-form-control">
                                    <option value=""><?php echo __('Select', 'wpdating'); ?></option>
                                    <?php echo $seeking_options; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="form-group age-wrap d-flex space-bet">
			                    <label>
			                        <?php echo __('Age:', 'wpdating') ?>
			                    </label> 
		                        <select name="age_from" class="dspdp-form-control dsp-form-control"> 
		                        	<option value=""><?php echo __('From','wpdating'); ?></option>
		                            <?php
									$min_selected = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
		                            for ( $i = $general_settings->min_age->value; $i <= $general_settings->max_age->value; $i++ ) : ?>
                                    <option value="<?php echo $i; ?>" <?php echo ( $i == $min_selected ? "selected='selected'" : "" ) ?>><?php echo $i; ?></option>
                                    <?php
		                            endfor; ?>
		                        </select>
		                        <select name="age_to" class="dspdp-form-control dsp-form-control">
		                        	<option value=""><?php echo __('To','wpdating'); ?></option>
		                            <?php
									$max_selected = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
                                    for ( $i = $general_settings->min_age->value; $i <= $general_settings->max_age->value; $i++ ) : ?>
                                        <option value="<?php echo $i; ?>" <?php echo ( $i == $max_selected ? "selected='selected'" : "" ) ?>><?php echo $i; ?></option>
                                    <?php
                                    endfor; ?>
		                        </select>
			                </div>
                            <div class="form-group">
			                    <label for="cmbCountry">
			                        <?php echo __('Country:', 'wpdating'); ?>
			                    </label>
		                        <select id="cmbCountry" name="cmbCountry" class="country_trigger dspdp-form-control dsp-form-control">
		                            <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
		                            <?php
                                    $selected_country_id = isset( $_REQUEST['cmbCountry'] ) ? $_REQUEST['cmbCountry'] : 0;
                                    $countries           = wpee_get_countries();
                                    foreach ( $countries as $country ) : ?>
                                        <option value="<?php echo $country->country_id; ?>" <?php echo ( $country->country_id == $selected_country_id ? "selected='selected'" : "" ); ?>>
                                            <?php echo $country->name; ?>
                                        </option>
                                    <?php
                                    endforeach;
		                            ?>
		                        </select>
			                </div>
                        <div class="form-group">
			                    <label for="cmbState"><?php echo __('State:', 'wpdating'); ?></label>
			                    <!--onChange="Show_state(this.value);"-->
			                    <div id="state_change">
			                        <select id="cmbState" name="cmbState" class="state_trigger dspdp-form-control dsp-form-control">
			                            <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
			                            <?php 
			                                if ( $selected_country_id != 0 ) :
			                                    $states = apply_filters( 'dsp_get_all_States_Or_City', $selected_country_id );
			                                    if( ! empty( $states ) ):
                                                    $selected_state_id = isset( $_REQUEST['cmbState'] ) ? $_REQUEST['cmbState'] : 0;
			                                        foreach ( $states as $state ) : ?>
			                                            <option value='<?php echo esc_attr( $state->state_id ); ?>' <?php echo ( $state->state_id == $selected_state_id ? "selected='selected'" : "" ); ?> >
                                                            <?php echo esc_html( $state->name ); ?>
                                                        </option>
			                            <?php
                                                    endforeach;
			                                    endif;
			                                endif;
			                            ?>
			                        </select>
			                    </div>
			                </div>
                        <div class="form-group">
			                    <label for="cmbCity">
			                    	<?php echo __('City:', 'wpdating'); ?>
			                 	</label> 
			                    <!--onChange="Show_state(this.value);"-->
			                    <div id="city_change">
			                        <select id="cmbCity" name="cmbCity" class="city_trigger dspdp-form-control dsp-form-control">
			                            <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
			                            <?php
                                            if ( isset( $selected_country_id ) && isset( $selected_state_id ) ) {
                                                $cities  = Wpdating_Elementor_Extension_Helper_Functions::get_city_by_country_and_state( $selected_country_id, $selected_state_id);
                                            } elseif ( isset( $selected_country_id ) ) {
                                                $cities  = Wpdating_Elementor_Extension_Helper_Functions::get_city_by_country_and_state( $selected_country_id, 0);
                                            }
			                                if ( ! empty( $cities ) ) :
                                                $selected_city_id = isset( $_REQUEST['cmbCity'] ) ? $_REQUEST['cmbCity'] : 0;
			                                    foreach ( $cities as $city ) : ?>
                                                    <option value='<?php echo esc_attr( $city->city_id ); ?>' <?php echo ( $city->city_id == $selected_city_id ? "selected='selected'" : "" ); ?> ><?php echo esc_html( $city->name ); ?></option>
                                        <?php
			                                    endforeach;
			                                endif;
			                            ?>
			                        </select>
			                    </div>
			                </div>
                        <div class="form-hidden-data">
                            <input type="hidden" name="filter_active" value="<?php echo $filter_active; ?>">
                            <input type="hidden" name="user_name_filter_active" value="<?php echo $user_name_filter_active; ?>">
                            <?php wp_nonce_field('wpee_member_list_filter', 'nonce-filter');?>
                            <input type="hidden" name="action" value="wpee_member_list_ajax_filter">
                        </div>
                    </div>
                    <!-- .form-field-group -->
                </div>
            </div>
        </form>
    </div>
    <div class="wpee-member-list-content">
	    <div class="main-member-list-wrap">
	    <?php
	    if ( $search_member_count > 0 ) :
            $limit          = ! empty( $general_settings->search_result->value ) ? $general_settings->search_result->value : 12;
            $search_members = $wpdb->get_results($sql_query . " LIMIT 0, $limit");

            foreach ( $search_members as $search_member ) :
                $profile_link      = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $search_member->user_name );
                $user_display_name = ( $general_settings->display_user_name->value == 'display_user_name')
                    ? $search_member->user_name :
                    Wpdating_Elementor_Extension_Helper_Functions::get_full_name_by_user_id( $search_member->user_id, $search_member->user_name );
            ?>
                <div class="member-detail-wrap">
                    <figure class="img-holder">
                        <a href="<?php echo $profile_link; ?>">
                            <img src="<?php echo Wpdating_Elementor_Extension_Helper_Functions::members_photo_path( $search_member->user_id, $search_member->user_image,
                                $search_member->user_photo_private, 'N' )->image_350; ?>"
                                 border="0" class="img-big" alt="<?php echo $user_display_name; ?>" />
                        </a>
                    </figure>
                    <div class="user-details">
                        <h6 class="member-user-name">
                            <a href="<?php echo $profile_link; ?>">
                                <?php echo ( strlen( $user_display_name ) > 15 ) ? substr( $user_display_name, 0, 13 ) . '...' : $user_display_name; ?>
                            </a>
                            <span class="online dspdp-online-status">
                                <?php if ( $search_member->user_online_status == 'Y' ) : ?>
                                <span class="dspdp-status-on" <?php echo __('Online', 'wpdating'); ?>></span>
                                <?php else : ?>
                                <span class="dspdp-status-off" <?php echo __('Offline', 'wpdating'); ?>></span>
                                <?php endif; ?>
                            </span>
                        </h6>
                    <div class="user-detail-content">
                        <p><?php echo $search_member->user_age; ?> <span data-label="member-label"><?php echo __('year old', 'wpdating'); ?></span> <span class="gender-<?php echo $genders->{$search_member->user_gender}; ?>"><?php echo $genders->{$search_member->user_gender}; ?></span> <span data-label="member-label"><?php echo __('from', 'wpdating'); ?></span>
                            <br /><?php echo ( ! empty( $search_member->user_city ) ? '<span data-label="member-city">'.$search_member->user_city . ',</span> '  : '' ) . ( ! empty( $search_member->user_state ) ? $search_member->user_state  . ', ': '' ) . ( ! empty( $search_member->user_country ) ? $search_member->user_country : '' ) ?>
                    </div>
            </div>
        </div>
        <?php
            endforeach;
	    else : ?>
            <div class="records-not-found">
                <p><?php echo __('No record found for your search criteria.', 'wpdating'); ?></p>
            </div>
	    <?php
        endif; ?>
        </div>
	    <div id="wpee-loader"></div>
	</div>