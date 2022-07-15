<?php 
global $wpdb;
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'basic-search';
$user_id = wpee_profile_id();
$current_user_id = get_current_user_id();
$profile_link = wpee_get_profile_url_by_id( $user_id );
$check_near_me = wpee_get_setting('near_me'); ?>

<div class="wpee-search-tab main-tab-wrap main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <li class="profile-section-tab-title <?php echo ( $profile_subtab != 'advance-search' && ( $profile_subtab != 'near-me' || ($check_near_me->setting_status != 'Y') ) ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'search/basic-search');?>"><?php esc_html_e( 'Basic Search', 'wpdating' );?></a></li>
        <li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'advance-search' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'search/advance-search');?>"><?php esc_html_e( 'Advanced Search', 'wpdating' );?></a></li>
        <?php 
        if( $check_near_me->setting_status == 'Y' ): ?>
            <li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'near-me' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'search/near-me');?>"><?php esc_html_e( 'Near Me', 'wpdating' );?></a></li>
        <?php endif;?>
    </ul>
    <div class="tab-content-wrapper">
            <?php
            if( !empty( $profile_subtab ) && $profile_subtab == 'advance-search' ){
                if( !is_wp_error(wpee_check_membership('Advanced Search', $current_user_id )) && wpee_check_membership('Advanced Search', $current_user_id ) == true ){
                wpee_locate_template('search/advance_search.php');
                }
                else{
                    wpee_display_error_message(wpee_check_membership('Advanced Search', $current_user_id ));
                }
            }
            elseif( !empty( $profile_subtab ) && $profile_subtab == 'near-me' && ($check_near_me->setting_status == 'Y') ){
                wpee_locate_template('search/near_me.php');
            }
            else {
                if( !is_wp_error(wpee_check_membership('Search', $current_user_id )) && wpee_check_membership('Search', $current_user_id ) == true ){
                    wpee_locate_template('search/basic_search.php');
                }
                else{
                    wpee_display_error_message(wpee_check_membership('Search', $current_user_id ));
                }
            }
            ?>
    </div>
</div>