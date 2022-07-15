<?php

if ( isset( $_POST['delete-thread'] ) ){
    if ( isset( $_POST['delete-thread-id'] ) && !empty( $_POST['delete-thread-id'] ) ){
        $thread_ids = implode("," ,$_POST['delete-thread-id']);
        $status = $wpdb->query("DELETE FROM {$dsp_user_emails_table} WHERE thread_id IN($thread_ids)");
        if ( $status > 0){ ?>
            <script>
                toastr.success('<?php echo __('Message deleted successfully.', 'wpdating'); ?>');
            </script>
        <?php }
    }
}
$dsp_members_photos_table   = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_user_profiles_table    = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$check_couples_mode = wpee_get_setting( 'couples' );

if ( $check_couples_mode->setting_status == 'Y' ) {
    $included_sender_sql_query = "SELECT sender_id FROM {$dsp_user_emails_table} user_email1
                                     LEFT JOIN {$dsp_user_profiles_table} user_profile
                                     ON user_email1.sender_id = user_profile.user_id
                                     WHERE user_email1.receiver_id = {$current_user->ID} GROUP BY sender_id";
}else{
    $included_sender_sql_query = "SELECT sender_id FROM {$dsp_user_emails_table} user_email1
                                     LEFT JOIN {$dsp_user_profiles_table} user_profile
                                     ON user_email1.sender_id = user_profile.user_id
                                     WHERE user_profile.gender != 'C' AND user_email1.receiver_id = {$current_user->ID} GROUP BY sender_id";
}

$sql_query = "SELECT COUNT(*) FROM (SELECT user_email.thread_id FROM {$dsp_user_emails_table} user_email
                    WHERE user_email.sender_id IN ({$included_sender_sql_query}) AND receiver_id = {$current_user->ID} AND user_email.delete_message = 1 
                    GROUP BY user_email.thread_id) as num";

$total_count = $wpdb->get_var($sql_query);

?>
<div class="box-border">
    <div class="box-pedding">
        <?php if( $total_count > 0 ){ ?>
            <div class="heading-submenu">
                <strong><?php echo __('My Messages', 'wpdating'); ?></strong>
            </div>
            <form name="frmdelmessages" action="" method="post" class="message-block-delete">
                <div class="gray-title-head">
                    <div class="heading-top">
                        <strong><?php echo __('Sender', 'wpdating') ?></strong>
                        <strong><?php echo __('Subject', 'wpdating') ?></strong>
                    </div>
                </div>

                <?php
                $page =  isset( $_GET['page1'] ) ? $_GET['page1'] : 1;

                $limit = 10;

                $start = ($page - 1) * $limit;

                $page_name  = $message_url . '/deleted?';
                $image_path = content_url('/');

                $sql_query = "SELECT min(user_email.sender_id) sender_id, user_email.thread_id, max(user_email.sent_date) max_receive_date,
                    (SELECT user_login FROM {$wpdb->users} WHERE ID = min(user_email.sender_id) LIMIT 1) sender_username,
                    (SELECT display_name FROM {$wpdb->users} WHERE ID = min(user_email.sender_id) LIMIT 1) sender_name,
                    (SELECT subject FROM {$dsp_user_emails_table} WHERE thread_id = min(user_email.thread_id) LIMIT 1) subject,
                    (SELECT gender FROM {$dsp_user_profiles_table} WHERE user_id = min(user_email.sender_id) LIMIT 1) gender
                    FROM {$dsp_user_emails_table} user_email
                    WHERE user_email.sender_id IN ({$included_sender_sql_query}) AND user_email.receiver_id = {$current_user->ID} AND user_email.delete_message = 1 
                    GROUP BY user_email.thread_id ORDER BY max_receive_date DESC LIMIT {$start}, {$limit}  ;";

                $messages = $wpdb->get_results( $sql_query );

                foreach ($messages as $message) {
                    ?>
                    <div class="dsp_vertical_scrollbar">
                        <ul class="email-page no-list">
                            <li  class="wpee-received-checkbox">
                                <input type="checkbox" class="delete-message-id" name="delete-thread-id[]" value="<?php echo $message->thread_id; ?>" />
                            </li>
                            <li class="wpee-received-details">
                                <a href="<?php echo trailingslashit(wpee_get_profile_url_by_id( $message->sender_id)); ?>"  class="dsp-email-photo">
                                    <img src="<?php echo wpee_display_members_photo($message->sender_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo $message->sender_username; ?>" />
                                </a>
                                <span class="name">
                                    <a href="<?php echo trailingslashit(wpee_get_profile_url_by_id( $message->sender_id)); ?>">
                                        <?php echo $message->sender_name; ?>
                                    </a>
                                </span>
                                <br />
                                <?php echo date("F j, Y g:i a", strtotime($message->max_receive_date)); ?>
                            </li>
                            <li style="width:55%;">
                                <a href="<?php echo "$message_url/view_message?thread={$message->thread_id}"; ?>">
                                    <span><?php echo $message->subject; ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <div style="float:left; width:100%;">
                    <?php
                    echo get_pagination( $page, $total_count, $limit, $page_name );
                    ?>
                </div>
                <div class="btn-delete">
                    <input type="submit" class="dsp_submit_button  dspdp-btn dspdp-btn-sm dspdp-btn-warning" id="delete-message-button" name="delete-thread"
                           onclick="return confirm('<?php echo __('Are you sure you want to Delete this Message?', 'wpdating'); ?>')"
                           value="<?php echo __('Delete Selected', 'wpdating') ?>" disabled />
                </div>
            </form>
        <?php } else { ?>
            <div>
                <strong><?php echo __('Empty', 'wpdating'); ?></strong>
            </div>
        <?php } ?>
    </div>
</div>