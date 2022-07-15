<?php

if ( isset( $_POST['delete-message'] ) ){
    if ( isset( $_POST['delete-message-id'] ) && !empty( $_POST['delete-message-id'] ) ){
        $message_ids = implode("," ,$_POST['delete-message-id']);
        $status = $wpdb->query("UPDATE {$dsp_user_emails_table} SET delete_message = 1  WHERE message_id IN($message_ids)");
        if ( $status > 0){ ?>
            <script>
                toastr.success('<?php echo __('Message deleted successfully.', 'wpdating'); ?>');
            </script>
        <?php }
    }
}

$dsp_user_profiles_table    = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$sql_query = "SELECT COUNT(*) FROM {$dsp_user_emails_table} user_email
                JOIN {$wpdb->users} receiver
                ON user_email.receiver_id = receiver.ID
                LEFT JOIN {$dsp_user_profiles_table} receiver_profile
                ON user_email.receiver_id = receiver_profile.user_id
                WHERE user_email.sender_id = {$current_user->ID} AND user_email.delete_message = 0";

$check_couples_mode = wpee_get_setting( 'couples' );

if ( $check_couples_mode->setting_status == 'Y' ) {
    $sql_query .= " AND receiver_profile.gender != 'C'";
}

$sql_query .= " ORDER BY sent_date DESC, message_id DESC";

$total_count = $wpdb->get_var($sql_query);
?>
<div class="box-border">
    <div class="box-pedding">
        <?php if ($total_count > 0) {
            $page =  isset( $_GET['page1'] ) ? $_GET['page1'] : 1;

            $limit = 10;

            $start = ($page - 1) * $limit;

            $page_name  = "{$message_url}/sent?";
            $image_path = content_url('/');

            $columns   = "user_email.message_id email_id, user_email.subject email_subject, user_email.text_message email_message,
                            user_email.message_read email_read, user_email.sent_date email_sent_date,
                            receiver.ID  receiver_id, receiver.user_login receiver_username, receiver.display_name receiver_name";
            $sql_query = str_replace("COUNT(*)", $columns, $sql_query);

            $messages = $wpdb->get_results($sql_query . " LIMIT $start, $limit ");
            ?>
            <form name="frmdelmessages" action="" method="post"  class="message-block-sent">
                <div class="dsp_back_inbox dspdp-spacer">
                    <a class="dspdp-btn dspdp-btn-xs dspdp-btn-default" href="<?php echo $message_url . "/inbox/"; ?>"><?php echo __('Back to Inbox', 'wpdating') ?></a>
                </div>
                <div class="clearfix">
                </div>
            <?php
            foreach ($messages as $message) {
                ?>
                <ul class="sent-message-page clearfix no-list">
                    <li class="dspdp-clearfix">
                        <span class="dspdp-check" style="float:left; margin-top:20px;">
                            <input type="checkbox" class="delete-message-id" name="delete-message-id[]" value="<?php echo $message->email_id; ?>" />
                        </span>
                        <span class="image">
                            <a href="<?php echo trailingslashit(wpee_get_profile_url_by_id( $message->receiver_id)); ?>" >
                                <img src="<?php echo wpee_display_members_photo($message->receiver_id, $image_path); ?>" style="width:45px; height:45px;" class="img2" align="left" alt="<?php echo $message->receiver_username; ?>" />
                            </a>
                        </span>
                        <div class="msg-info">
                            <ul class="no-list">
                                <li class="name age-text dspdp-bold"><?php echo $message->receiver_name; ?></li>
                                <li><?php echo __('Sent', 'wpdating'). ' : '. date("F j, Y g:i a", strtotime($message->email_sent_date)) ?></li>
                                <li><?php echo __('Subject', 'wpdating'). ' : '.$message->email_subject ?></li>
                                <li>
                                    <?php
                                    $text_message = $message->email_message;
                                    $msg_length   = strlen( $text_message );
                                    if( $msg_length > 50 ) {
                                        $cut_length = strpos( $text_message,' ', 50 );
                                        echo substr( $text_message, 0, $cut_length );
                                        if ( $msg_length > $cut_length ){ ?>
                                            <span class="show_more_text" style="cursor:pointer;color:green;"> .....more</span>
                                            <span class="long_message" style="display:none;width:200%;"><?php echo substr( $message->email_message, $cut_length ); ?></span>
                                            <span class="hide_more_text" style="display:none;cursor:pointer;color:red;"> (Hide) </span>
                                    <?php
                                        }
                                    } else {
                                        echo $message->email_message;
                                    }
                                    ?>
                                </li>
                            </ul>
                        </div>
                        <div class="read-message">
                            <?php
                            if ($message->email_read == 'Y') {
                                ?>
                                <img src="<?php echo get_site_url(); ?>/wp-content/plugins/dsp_dating/images/env_read.jpg" title="<?php echo __('Email has been Read', 'wpdating') ?>"  alt="env_read"/>
                            <?php } else { ?>
                                <img src="<?php echo get_site_url(); ?>/wp-content/plugins/dsp_dating/images/env_unread.jpg" title="<?php echo __('Email is Unread', 'wpdating') ?>" alt="env_unread"/>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
                    <?php
                }
                ?>
                <div style="float:left; width:100%;">
                    <?php
                    echo get_pagination( $page, $total_count, $limit, $page_name );
                    ?>
                </div>
                <div class="btn-row">
                    <input type="submit" class="dsp_submit_button dspdp-btn dspdp-btn-sm dspdp-btn-warning" id="delete-message-button" name="delete-message"
                           onclick="return confirm('<?php echo __('Are you sure you want to Delete this Message?', 'wpdating'); ?>')"
                           value="<?php echo __('Delete Selected', 'wpdating') ?>" disabled/>
                </div>
        </form>
        <?php }else{ ?>
            <div>
                <strong><?php echo __('Empty', 'wpdating'); ?></strong>
            </div>
    <?php }?>
    </div>
</div>

<script>

jQuery(document).ready(function(){
    jQuery('.show_more_text').click(function(){
            var current_element = jQuery(this);
            current_element.hide();
            current_element.next('span').css('display','block');
            current_element.next('span').next('span').show();
    });
    jQuery('.hide_more_text').click(function(){
            var current_element = jQuery(this);
            current_element.hide();
            current_element.prev('span').hide();
            current_element.prev('span').prev('span').show();    
            var window_height=jQuery(window).height();
            var text_height=current_element.prev('span').height();

            if( text_height >= (window_height/5) )
            {
                jQuery('html, body').animate({
                    scrollTop: current_element.parent().siblings('.name').offset().top
                }, 1000);
            }
    });
});

</script>