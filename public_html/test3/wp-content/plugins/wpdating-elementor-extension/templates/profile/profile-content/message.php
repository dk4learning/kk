<?php
/**
 * Check if given user has message access or not.
 *
 * @param int $user_id
 * @param string $feature_name
 * @return array
 */
if ( ! function_exists('check_membership_message_access' ) ){
    function check_message_feature_access( $user_id, $feature_name )
    {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_user_profiles_table    = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $user_gender            = $wpdb->get_var("SELECT gender FROM {$dsp_user_profiles_table} WHERE user_id = {$user_id}");
        $email_gender_setting   = wpee_get_setting('free_email_access_gender');

        if ( $email_gender_setting->setting_value == 3 ){
            return [
                'error'  => false,
                'access' => 'free_email_access'
            ];
        }

        if ( $email_gender_setting->setting_value == 1 ){
            $email_gender = 'F';
            if ( $email_gender == $user_gender ) {
                return [
                    'error'  => false,
                    'access' => 'free_email_access'
                ];
            }
        }
        if ( $email_gender_setting->setting_value == 2 ){
            $email_gender = 'F';
            if ( $email_gender == $user_gender ) {
                return [
                    'error'  => false,
                    'access' => 'free_email_access'
                ];
            }
        }

        $dsp_payments_table         = $wpdb->prefix . DSP_PAYMENTS_TABLE;

        $payment_row = $wpdb->get_row("SELECT * FROM {$dsp_payments_table} WHERE pay_user_id = {$user_id}");

        if ( $payment_row ) {
            $current_date      = date('Y-m-d' );
            $current_date_time = date('Y-m-d H:i:s' );

            if ( isset( $payment_row->app_expiration_date ) && !empty( $payment_row->app_expiration_date ) ){
                if ( $payment_row->app_expiration_date >= $current_date_time  ) {
                    $included = check_if_feature_included_in_plan( $feature_name, $payment_row->pay_plan_id );

                    if ( $included ){
                        return [
                            'error'  => false,
                            'access' => 'membership'
                        ];
                    }else{
                        $not_included = true;
                    }
                }else{
                    $expired = true;
                }
            }else {
                if( $payment_row->expiration_date >= $current_date ){
                    $included = check_if_feature_included_in_plan( $feature_name, $payment_row->pay_plan_id );
                    if ( $included ){
                        return [
                            'error'  => false,
                            'access' => 'membership'
                        ];
                    }else{
                        $not_included = true;
                    }
                }else{
                    $expired = true;
                }
            }
        }else{
            $not_purchased = true;
        }

        $credit_mode = $wpdb->get_row("SELECT * FROM {$dsp_general_settings_table} WHERE setting_name = 'credit'");

        if ( $credit_mode->setting_status == 'N' ){
            if ( isset( $expired ) ){
                return [
                    'error'         => true,
                    'error_message' => __( 'Your Premium Account has been expired, Please upgrade your account.', 'wpdating' )
                ];
            }elseif ( isset( $not_included ) ){
                return [
                    'error'         => true,
                    'error_message' => __( $feature_name, 'wpdating' ) . ' ' . __( 'feature not available in your membership plan', 'wpdating' )
                ];
            }else {
                return [
                    'error'         => true,
                    'error_message' => __( 'Only premium member can access this feature, Please upgrade your account', 'wpdating' )
                ];
            }
        }

        $credit_access = check_credit_access( $user_id );

        if ( $credit_access ){
            return [
                'error'  => false,
                'access' => 'credit'
            ];
        }

        if ( isset( $expired ) ){
            return [
                'error'         => true,
                'error_message' => __( 'Your Premium Account has been expired, Please upgrade your account or purchase credits.', 'wpdating' )
            ];
        }elseif ( isset( $not_included ) ){
            return [
                'error'         => true,
                'error_message' => __( $feature_name, 'wpdating' ) . ' ' . __( 'feature not available in your membership plan, Please upgrade your account or purchase credits.', 'wpdating' )
            ];
        }else{
            return [
                'error'         => true,
                'error_message' => __( 'Only premium member can access this feature, Please upgrade your account or purchase credits.', 'wpdating' )
            ];
        }
    }
}

/**
 * Check if given feature is included in given plan.
 *
 * @param int $user_id
 * @param string $feature_name
 * @return bool
 */
if ( ! function_exists('check_membership_message_access' ) ) {
    function check_if_feature_included_in_plan( $feature, $plan_id )
    {
        global $wpdb;
        $dsp_membership_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;

        $plan_row = $wpdb->get_row("SELECT * FROM {$dsp_membership_table} WHERE membership_id = {$plan_id}");

        if ( is_null( $plan_row ) ) {
            return false;
        }

        $features_ids = $plan_row->premium_access_feature;

        return wpee_check_features( $features_ids, $feature );
    }
}

/**
 * Check credit access of given user id.
 *
 * @param int $user_id
 * @return bool
 */
if ( ! function_exists( 'check_credit_access' )){
    function check_credit_access( $user_id )
    {
        global $wpdb;
        $dsp_credits_table      = $wpdb->prefix . DSP_CREDITS_TABLE;
        $dsp_credit_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;

        $credit_row      = $wpdb->get_row("SELECT * FROM {$dsp_credits_table}");
        $user_credit_row = $wpdb->get_row("SELECT * FROM {$dsp_credit_usage_table} WHERE user_id = {$user_id}");

        if ( $user_credit_row && $user_credit_row->no_of_credits >= $credit_row->emails_per_credit ) {
            return true;
        }

        return false;
    }
}

/**
 * Check if given user has message access or not.
 *
 * @param int $page
 * @param int $total_count
 * @param int $limit
 * @param string $page_name
 * @param int $adjacents
 * @return string
 */
if (!function_exists('get_pagination')) {
    function get_pagination($page, $total_count, $limit, $page_name, $adjacents = 2)
    {
        $prev = $page - 1;
        $next = $page + 1;
        $last_page = ceil($total_count / $limit);
        $lpm1 = $last_page - 1;
        $pagination = "";
        if ($last_page > 1) {
            $pagination .= "<div class='wpse_pagination'>";
            if ($page > 1) {
                $pagination .= "<div><a style='color:#365490' href='{$page_name}page1={$prev}'>" . __('Previous', 'wpdating') . "</a></div>";
            } else {
                $pagination .= "<span class='disabled'>" . __('Previous', 'wpdating') . "</span>";
            }

            if ($last_page <= 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $last_page; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<span class='current'>$counter</span>";
                    else
                        $pagination .= "<div><a href='{$page_name}page1={$counter}'>{$counter}</a></div>";
                }
            } elseif ($last_page > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>{$counter}</span>";
                        else
                            $pagination .= "<div><a href='{$page_name}page1={$counter}\">{$counter}</a></div>";
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href='{$page_name}page1={$lpm1}'>{$lpm1}</a></div>";
                    $pagination .= "<div><a href='{$page_name}page1={$last_page}'>{$last_page}</a></div>";
                } elseif ($last_page - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<div><a href='{$page_name}page1=1'>1</a></div>";
                    $pagination .= "<div><a href='{$page_name}page1=2'>2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<div class='current'>$counter</div>";
                        else
                            $pagination .= "<div><a href='{$page_name}page1={$counter}'>{$counter}</a></div>";
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href='{$page_name}page1={$lpm1}'>$lpm1</a></div>";
                    $pagination .= "<div><a href='{$page_name}page1={$last_page}'>$last_page</a></div>";
                } else {
                    $pagination .= "<div><a href='{$page_name}page1=1'>1</a></div>";
                    $pagination .= "<div><a href='{$page_name}page1=2'>2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ($counter = $last_page - (2 + ($adjacents * 2)); $counter <= $last_page; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<span class='current'>{$counter}</span>";
                        else
                            $pagination .= "<div><a href='{$page_name}page1={$counter}'>{$counter}</a></div>";
                    }
                }
            }
            if ($page < $counter - 1)
                $pagination .= "<div><a class='disabled'  href='{$page_name}page1={$next}'>" . __('Next', 'wpdating') . "</a></div>";
            else
                $pagination .= "<span class='disabled'>" . __('Next', 'wpdating') . "</span>";
            $pagination .= "</div>\n";
        }

        return $pagination;
    }
}

global $wpdb, $wpee_settings;
$current_user   = wp_get_current_user();
$profile_link   = trailingslashit(wpee_get_profile_url_by_id( $current_user->ID ));
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'inbox';
$message_url    = $profile_link . 'message';

$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;

switch ( $profile_subtab ) {
    case 'compose':
        $feature_access  = check_message_feature_access( $current_user->ID, 'Compose New Email Message' );
        if ( $feature_access['error'] ){
            $feature_error_message = $feature_access['error_message'];
        }else{
            if ( isset( $_GET['receiver_id'] ) && !empty( $_GET['receiver_id'] ) ){
                $receiver = $wpdb->get_row( "SELECT ID, display_name AS name FROM {$wpdb->users} 
                                            WHERE ID = {$_GET['receiver_id']}" );
                $receiver_id   = $receiver->ID;
                $receiver_name = $receiver->name;
            } else {
                if ( isset( $_GET['act'] ) && $_GET['act'] == 'send_draft' ){
                    $draft_id                   = $_GET['draft_id'];
                    $dsp_messages_draft_table   = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;

                    $draft_message  = $wpdb->get_row("SELECT user.ID receiver_id, user.display_name receiver_name,
                                                        message_draft.text_message email_message, message_draft.subject email_subject,
                                                        message_draft.thread_id thread_id
                                                        FROM {$dsp_messages_draft_table} message_draft
                                                        JOIN {$wpdb->users} user
                                                        ON message_draft.receiver_id = user.ID
                                                        WHERE draft_id = '{$draft_id}'");
                    if ( is_null( $draft_message ) ){
                        header( 'location:' . $message_url );
                    }
                    $receiver_id   = $draft_message->receiver_id;
                    $receiver_name = $draft_message->receiver_name;
                    $email_subject = $draft_message->email_subject;
                    $email_message = $draft_message->email_message;
                }
                $dsp_user_favourites_table  = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
                $dsp_my_friends_table       = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;

                $receivers = $wpdb->get_results( "SELECT ID AS receiver_id, display_name AS receiver_name FROM {$wpdb->users} 
                                            WHERE ID IN (
                                                SELECT favourite_user_id as user_id FROM {$dsp_user_favourites_table} WHERE user_id ='{$current_user->ID}'
                                                UNION
                                                SELECT friend_uid as user_id FROM {$dsp_my_friends_table} WHERE user_id ='{$current_user->ID}' AND approved_status='Y'
                                            )" );
            }

            $file_to_include = 'profile/profile-content/message/new.php';
            $action          = 'compose';
        }
        break;

    case 'reply':
        $feature_access  = check_message_feature_access( $current_user->ID, 'Reply Email Message' );
        if ( $feature_access['error'] ){
            $feature_error_message = $feature_access['error_message'];
        }else{
            if ( isset( $_GET['act'] ) && $_GET['act'] == 'send_draft' ){
                $draft_id                   = $_GET['draft_id'];
                $dsp_messages_draft_table   = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;

                $message_object  = $wpdb->get_row("SELECT user.ID receiver_id, user.display_name receiver_name,
                                                        message_draft.text_message email_message, message_draft.subject email_subject, 
                                                        message_draft.thread_id thread_id, message_draft.is_reply is_reply
                                                        FROM {$dsp_messages_draft_table} message_draft
                                                        JOIN {$wpdb->users} user
                                                        ON message_draft.receiver_id = user.ID
                                                        WHERE draft_id = '{$draft_id}'");

                if ( is_null($message_object) ){
                    header( 'location:' . $message_url );
                }
                $email_message = $message_object->email_message;
                $thread_id     = $message_object->thread_id;
            } else {
                $thread_id      = $_GET['thread'];
                $message_object = $wpdb->get_row("SELECT user.ID receiver_id, user.display_name receiver_name, user_email.subject email_subject
                                                    FROM {$dsp_user_emails_table} user_email
                                                    JOIN {$wpdb->users} user
                                                    ON user_email.sender_id = user.ID
                                                    WHERE user_email.thread_id = '{$thread_id}' AND user_email.receiver_id = {$current_user->ID}");
            }
            $receiver_id   = $message_object->receiver_id;
            $receiver_name = $message_object->receiver_name;
            $email_subject = $message_object->email_subject;
            $file_to_include = 'profile/profile-content/message/new.php';
            $action          = 'reply';
        }
        break;

    case 'sent':
        $feature_access  = true;
        $file_to_include = 'profile/profile-content/message/sent.php';
        break;

    case 'draft':
        $feature_access  = true;
        $file_to_include = 'profile/profile-content/message/draft.php';
        break;

    case 'deleted':
        $feature_access  = true;
        $file_to_include = 'profile/profile-content/message/deleted.php';
        break;

    case 'view_message':
        $feature_access  = check_message_feature_access( $current_user->ID, 'View Email Message' );
        $file_to_include = 'profile/profile-content/message/view.php';
        break;

    default:
        $feature_access  = check_message_feature_access( $current_user->ID, 'Access Email' );
        if ( $feature_access['error'] ){
            $feature_error_message = $feature_access['error_message'];
        } else {
            $file_to_include = 'profile/profile-content/message/inbox.php';
        }
        
}


?>
<div class="main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <li class="profile-section-tab-title <?php echo ( $profile_subtab == '' || $profile_subtab == 'inbox' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($message_url) . 'inbox' );?>"><?php esc_html_e( 'Inbox', 'wpdating' );?></a>
        </li>
        <li class="profile-section-tab-title <?php echo ( $profile_subtab == 'compose' || $profile_subtab == 'reply' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($message_url) . 'compose' );?>"><?php esc_html_e( 'Compose', 'wpdating' );?></a>
        </li>
        <li class="profile-section-tab-title <?php echo ( $profile_subtab == 'sent' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($message_url) . 'sent' );?>"><?php esc_html_e( 'Sent', 'wpdating' );?></a>
        </li>
        <li class="profile-section-tab-title <?php echo ( $profile_subtab == 'draft' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($message_url) . 'draft' );?>"><?php esc_html_e( 'Draft', 'wpdating' );?></a>
        </li>
        <li class="profile-section-tab-title <?php echo ( $profile_subtab == 'deleted' ) ? 'active' : '';?>">
            <a href="<?php echo esc_url(trailingslashit($message_url) . 'deleted' );?>"><?php esc_html_e( 'Deleted', 'wpdating' );?></a>
        </li>
    </ul>
    <div class="profile-section-content">
        <?php
        if ( isset( $feature_error_message ) ){ ?>
            <div class="wpee-search-error wpee-error" style="text-align: center;">
                <span style="color:#FF0000;"><?php echo $feature_error_message; ?></span>
                <span>
                    <a href="<?php echo $profile_link . "settings/upgrade-account/"; ?>">
                        <?php echo __('Click here', 'wpdating'); ?>
                    </a>
                </span>
            </div>
        <?php } else {
            include_once  WPEE_PATH.'templates/'. $file_to_include;
        }
        ?>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.delete-message-id').change(function() {
            if (jQuery('.delete-message-id:checked').length > 0) {
                jQuery('#delete-message-button').attr('disabled', false);
            } else {
                jQuery('#delete-message-button').attr('disabled', true);
            }
        });
    });
</script>