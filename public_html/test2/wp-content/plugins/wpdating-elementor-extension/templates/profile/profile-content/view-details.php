<?php
global $wpdb;
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : '';
$user_id        = wpee_profile_id();
$profile_link   = wpee_get_profile_url_by_id( $user_id );

$user_profile = wpee_get_user_profile_by( array( 'user_id' => $user_id , 'status_id' => 1 ) );
?>
<div class="profile-section-wrap main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <li class="profile-section-tab-title <?php echo ( empty($profile_subtab) || $profile_subtab == 'my-profile' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($profile_link).'view-details/my-profile');?>">
                <?php esc_html_e( 'View Details', 'wpdating' );?>
            </a>
        </li>
        <?php if ( !empty( $user_profile ) && $user_profile->gender == 'C' ) : ?>
        <li class="profile-section-tab-title <?php echo ( !empty($profile_subtab) && $profile_subtab == 'partner-profile' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($profile_link).'view-details/partner-profile');?>">
                <?php esc_html_e( 'View Partner Details', 'wpdating' );?>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <?php
    if ( $profile_subtab == 'partner-profile' ) {
        $user_profile         = wpee_get_user_partner_profile_by( array( 'user_id' => $user_id, 'status_id' => 1 ) );
        $dsp_question_details = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
    } else {
        $dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
    }
    $check_zipcode_mode = wpee_get_setting( 'zipcode_mode' );
    ?>
    <div class="wpee-edit-profile-inner profile-section-content">
    <?php if ( $user_profile ) : ?>
        <div class="view-details-content-wrap section1">

            <div class="wpee-sub-heading"><?php __( 'Personal Information', 'wpdating' );?></div>
            <ul class="profile-user-info no-list">
                <li>
                    <strong><?php echo __('I am', 'wpdating'); ?>:</strong>
                    <span><?php echo get_gender( !empty( $user_profile->gender ) ? $user_profile->gender : 'M' ); ?></span>
                </li>
                <li>
                    <strong><?php echo __('Seeking a', 'wpdating'); ?>:</strong>
                    <span><?php echo get_gender( !empty( $user_profile->seeking ) ? $user_profile->seeking : ( $user_profile->gender == 'M' ? 'F' : 'M' ) ); ?></span>
                </li>
                <?php if ( !empty( $user_profile->age ) ) : ?>
                    <li>
                        <strong><?php echo __('Age', 'wpdating'); ?>:</strong>
                        <span><?php echo GetAge( $user_profile->age ); ?>
                    </li>
                <?php endif; ?>
                <?php if ($user_profile->country_id != 0) : ?>
                    <li>
                        <strong><?php echo __('Country', 'wpdating'); ?>:</strong>
                        <span><?php echo wpee_get_country_name( $user_profile->country_id ); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ($user_profile->state_id != 0) : ?>
                    <li>
                        <strong><?php echo __('State', 'wpdating'); ?>:</strong>
                        <span><?php echo wpee_get_state_name( $user_profile->state_id ); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ($user_profile->city_id != 0) : ?>
                    <li>
                        <strong><?php echo __('City', 'wpdating'); ?>:</strong>
                        <span><?php echo wpee_get_city_name( $user_profile->city_id ); ?></span>
                    </li>
                <?php endif; ?>
                <?php if ( $check_zipcode_mode->setting_status == 'Y' && !empty( $user_profile->zipcode ) ) : ?>
                    <li>
                        <strong><?php echo __('Zipcode', 'wpdating'); ?>:</strong>
                        <span><?php echo $user_profile->zipcode; ?></span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="view-details-content-wrap section2">
            <div class="wpee-sub-heading"><?php esc_html_e('Interest In','wpdating');?>:</div>
            <?php if( $user_profile->make_private_profile == 1 ): ?>
                <div class="wpee-block-content">
                    <div style="text-align: center; display: grid;" class="wpee-empty-info wpee-info">
                    <span style="color: #FF0000;">
                            <?php esc_html_e( 'Not Allowed', 'wpdating'); ?>
                        </span>
                    </div>
                </div>
            <?php else : ?>
            <ul class="dspdp-other-details dsp-user-detail-info no-list">
                <?php
                $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;

                $all_languages = $wpdb->get_row("SELECT * FROM {$dsp_language_detail_table} where display_status='1' ");
                $language_name = $all_languages->language_name;
                if ($language_name == 'english') {
                    $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
                    $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
                } else {
                    $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE ."_" . strtolower(substr($table_name, 0, 2));
                    $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE . "_" . strtolower(substr($table_name, 0, 2));
                }

                $user_profile_question_details = $wpdb->get_results("SELECT A.question_name as question_name, A.field_type_id as field_type_id,
                                                                            B.option_value as question_value
                                                                            FROM {$dsp_profile_setup_table} A 
                                                                            INNER JOIN {$dsp_question_details} B 
                                                                            ON ( A.profile_setup_id = B.profile_question_id )
                                                                            WHERE B.user_id = '{$user_id}' ORDER BY A.sort_order");

                $multiple_select_questions = [];
                foreach ( $user_profile_question_details as $user_profile_question_detail ) :
                    switch ( $user_profile_question_detail->field_type_id ):
                        case 1: ?>
                            <li class="d-flex">
                                <strong><?php echo $user_profile_question_detail->question_name; ?>:</strong>
                                <span class="details"><?php echo stripslashes($user_profile_question_detail->question_value); ?></span>
                            </li>
                        <?php
                            break;

                        case 2:
                            ?>
                            <li class="d-flex">
                                <strong><?php echo $user_profile_question_detail->question_name; ?>:</strong>
                                <span class="details"><?php echo trim($user_profile_question_detail->question_value); ?></span>
                            </li>
                        <?php
                            break;

                        case 3:
                            $multiple_select_questions[$user_profile_question_detail->question_name][] = $user_profile_question_detail->question_value;
                            break;
                    endswitch;
                endforeach; ?>

                <?php foreach ( $multiple_select_questions as $key => $value ): ?>
                    <li class="d-flex">
                        <strong><?php echo $key; ?>:</strong>
                        <span class="details"><?php echo implode(', ', $value); ?></span>
                    </li>
                <?php endforeach; ?>

                <li class="li-fullwidth">
                    <strong><?php echo __('About Me', 'wpdating'); ?>:</strong>
                    <span class="details"><?php echo $user_profile->about_me; ?></span>
                </li>

                <li class="li-fullwidth">
                    <strong><?php echo __('My Interests', 'wpdating'); ?>:</strong>
                    <span class="details"><?php echo $user_profile->my_interest; ?></span>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    <?php else: ?>
            <div class="wpee-error-text">
                <span><?php echo __('No Profile Exists.', 'wpdating'); ?></span>
            </div>
    <?php endif; ?>
    </div>
</div>