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

if ( isset( $_REQUEST['filter_active'] ) && $_REQUEST['filter_active'] == 1 || ( isset( $_REQUEST['user_name_filter_active'] ) && $_REQUEST['user_name_filter_active'] == 1 ) ) {
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
        $where_conditions[] = "user.user_login LIKE '%{$_REQUEST['username']}%'";
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
    if ( $general_settings->member_list_gender->value == 2 ) {
        $sql_query .= " WHERE user_profile.gender = 'M'";
    } else if ( $general_settings->member_list_gender->value == 3 ) {
        $sql_query .= " WHERE user_profile.gender = 'F'";
    }
}

$limit      = ! empty( $general_settings->search_result->value ) ? $general_settings->search_result->value : 12;
$start      = ( $_REQUEST['page'] - 1 ) * $limit;

$sql_query  .= " ORDER BY user.ID DESC LIMIT $start, $limit";

$search_member_count = $wpdb->get_var( "SELECT COUNT(*) FROM ({$sql_query}) AS total");

if ( $search_member_count == 0 && $_REQUEST['page'] == 1 ) : ?>
    <div class="records-not-found">
        <p><?php echo __('No record found for your search criteria.', 'wpdating'); ?></p>
    </div>
<?php
else:

    $search_members = $wpdb->get_results( $sql_query );

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
                                $search_member->user_photo_private, 'N')->image_350; ?>"
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
endif;
