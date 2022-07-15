<?php
/**
 * Check if current user is blocked by or has blocked the other member user.
 *
 * @param int $current_user_id
 * @param int $member_user_id
 * @return array|bool
 */
if ( ! function_exists( 'check_block_member' ) ){
    function check_block_member( $current_user_id, $member_user_id )
    {
        global $wpdb;
        $dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;

        $check_if_blocked_by = $wpdb->get_var( "SELECT COUNT(*) FROM {$dsp_blocked_members_table} WHERE user_id='{$current_user_id}' AND block_member_id='{$member_user_id}'" );

        $response = [];
        if ( $check_if_blocked_by > 0 ) {
            $response['message'] = __( 'Sorry,You are blocked by this user. You can not send a message.', 'wpdating' ) . '</br>';
            $response['block']   = true;
            return $response;
        }else{
            $check_if_blocked = $wpdb->get_var( "SELECT COUNT(*) FROM {$dsp_blocked_members_table} WHERE user_id='{$member_user_id}' AND block_member_id='{$current_user_id}'" );
            if ( $check_if_blocked > 0 ) {
                $response['message'] = __( 'Sorry,You are blocked by this user. You can not send a message.', 'wpdating' ) . '</br>';
                $response['block']   = true;
                return $response;
            }
        }

        $response['block'] = false;
        return $response;
    }
}

/**
 * Check the spam words.
 *
 * @param string $message
 * @return bool
 */
if ( !function_exists( 'has_spam_words' ) ) {
    function has_spam_words($message)
    {
        global $wpdb;
        $dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;

        $check_spam_word = $wpdb->get_results("SELECT * FROM {$dsp_spam_words_table}");

        foreach ($check_spam_word as $spam_word) {
            if (preg_match("/\b" . $spam_word->spam_word . "\b/i", $message)) {
                $spam_words[] = $spam_word->spam_word;
            }
        }

        if (isset($spam_words) && count($spam_words) > 0) {
            return true;
        } else {
            return false;
        }
    }
}

include_once WP_CONTENT_DIR . '/plugins/dsp_dating/dsp_validation_functions.php';
global $wpdb;

$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$hasError = false;

$receiver_id    = isset( $_POST['receiver_id'] ) ? trim( $_POST['receiver_id'] ) : (isset($receiver_id) ? $receiver_id : 0);
$email_message  = isset( $_POST['message'] ) ? trim( $_POST['message'] ) : (isset($email_message) ? $email_message : '');
$email_subject  = isset( $_POST['subject'] ) ? trim( $_POST['subject'] ) : (isset($email_subject) ? $email_subject : '');

if ( isset( $_POST['send'] ) || isset( $_POST['save'] ) ){
    $message_error = "";

    if ( $receiver_id == 0 ) {
        $message_error .= __( 'You forgot to select your Friend.', 'wpdating' ) . '</br>';
        $hasError = true;
    }

    if ( empty( $email_message ) ) {
        $message_error .= __( 'You forgot to enter Message.', 'wpdating' ) . '</br>';
    } else {
        $email_message = esc_sql( sanitizeData( $email_message, 'xss_clean' ) );

        $check_spam_filter = $wpdb->get_row("SELECT * FROM {$dsp_general_settings_table} WHERE setting_name = 'spam_filter'");
        if ( $check_spam_filter->setting_status == 'Y' ) {
            if ( has_spam_words( $email_message  ) ){
                $message_error .= __( 'Could not continue as text entered contains words included in our spam filter.', 'wpdating' ) . '</br>';
                $hasError      = true;
            }
        }
    }

    if ( empty( $email_subject ) ) {
        $email_subject = "[ No Subject ]";
    } else {
        $email_subject = esc_sql( sanitizeData( $email_subject, 'xss_clean' ) );
    }

    if ( !$hasError ){

        if ( isset( $_POST['send'] ) ){
            $block_status = check_block_member( $current_user->ID, $receiver_id );

            if ( $block_status['block'] == true ){
                $message_error .= $block_status['message'];
                $hasError = true;
            }else{
                $dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
                $check_friend_notification   = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_user_notification_table WHERE private_messages='N' AND user_id='{$receiver_id}'" );
                if ($check_friend_notification > 0){
                    $message_error = __( "Alert Member Notifications-> You can't send a Message to this Member.", "wpdating" );
                    $hasError     = true;
                }else{
                    $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;

                    $receiver = $wpdb->get_row("SELECT * FROM {$wpdb->users} WHERE ID = '{$receiver_id}'");

                    if ( !isset($thread_id) ){
                        $max_thread = $wpdb->get_var( "SELECT thread_id FROM {$dsp_user_emails_table} ORDER BY thread_id DESC LIMIT 1" );

                        $thread_id  = ( $max_thread ? $max_thread : 0 ) + 1;
                    }

                    $email_input = array(
                        'sender_id'     => $current_user->ID,
                        'receiver_id'   => $receiver->ID,
                        'subject'       => $email_subject,
                        'text_message'  => $email_message,
                        'sent_date'     => date('Y-m-d H:i:s'),
                        'message_read'  => 'N',
                        'thread_id'     => $thread_id,
                    );

                    if( is_plugin_active('admin_message_reply/admin_message_reply.php' ) && $receiver->fake_key == 1 ){
                        $email_input['fake_key'] = 1;
                    }

                    $wpdb->insert($dsp_user_emails_table, $email_input);

                    if ( isset( $_GET['act'] ) && $_GET['act'] == 'send_draft' ){
                        $wpdb->delete( $dsp_messages_draft_table, array(
                            'draft_id'  => $draft_id
                        ));
                    }

                    dsp_add_notification( $current_user->ID, $receiver_id, 'send_email' );

                    $email_template = $wpdb->get_row( "SELECT * FROM {$dsp_email_templates_table} WHERE mail_template_id='2'" );

                    $mail_subject     = $email_template->subject;
                    $mail_subject     = str_replace( "<#SENDER_NAME#>", $current_user->display_name , $email_subject );

                    $mail_body = $email_template->email_body;
                    $mail_body = str_replace( "<#RECEIVER_NAME#>", $receiver->display_name, $mail_body );
                    $mail_body = str_replace( "<#SENDER_NAME#>", $current_user->display_name, $mail_body );
                    $url       = "<a href='{$profile_link}'>{$current_user->display_name}</a>";
                    $mail_body = str_replace( "<#URL#>", $url, $mail_body );

                    $admin_email = get_option( 'admin_email' );

                    $headers = "From: {$admin_email}";

                    $wpdating_email = Wpdating_email_template::get_instance();
                    $wpdating_email->send_mail( $receiver->user_email, $mail_subject, $mail_body );

                    if ( $feature_access['access'] == 'credit' ) {
                        $dsp_credits_table       = $wpdb->prefix . DSP_CREDITS_TABLE;
                        $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;

                        $credit_row         = $wpdb->get_row( "SELECT * FROM {$dsp_credits_table}" );
                        $available_credit   = $wpdb->get_var( "SELECT no_of_credits FROM {$dsp_credits_usage_table} WHERE user_id='{$current_user->ID}'" );
                        $no_of_credit       =  $available_credit - $credit_row->emails_per_credit;
                        $no_of_gifts        = floor( $no_of_credit / $credit_row->gifts_per_credit );
                        $no_of_emails       = floor($no_of_credit / $credit_row->emails_per_credit);

                        $wpdb->update( $dsp_credits_usage_table, array(
                            'no_of_credits' => $no_of_credit,
                            'no_of_emails'  => $no_of_emails,
                            'no_of_gifts'   => $no_of_gifts
                        ), array('user_id' => $current_user->ID));

                        $wpdb->query( "UPDATE {$dsp_credits_table} SET credit_used=credit_used+{$credit_row->emails_per_credit}" );
                    }

                    $success_message = __( 'Message Sent Successfully.', 'wpdating' );
                }
            }
        } elseif ( isset( $_POST['save'] )){
            $dsp_messages_draft_table = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;
            $reply = 1;
            if ( $action == 'compose'){
                $thread_id = $reply = 0;
            }

            if ( isset( $_GET['act'] ) && $_GET['act'] == 'send_draft' ){
                $already_exists = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_messages_draft_table} WHERE draft_id = '{$draft_id}'");
                if ( $already_exists ){
                    $wpdb->update( $dsp_messages_draft_table, array(
                        'save_date'    => date('Y-m-d H:i:s'),
                        'text_message' => $email_message,
                    ), array( 'draft_id' => $draft_id ));
                }else{
                    $wpdb->insert( $dsp_messages_draft_table, array(
                        'sender_id'    => $current_user->ID,
                        'receiver_id'  => $receiver_id,
                        'save_date'    => date('Y-m-d H:i:s'),
                        'thread_id'    => $thread_id,
                        'subject'      => $email_subject,
                        'text_message' => $email_message,
                        'is_reply'     => $reply
                    ));
                }
            }else{
                $wpdb->insert( $dsp_messages_draft_table, array(
                    'sender_id'    => $current_user->ID,
                    'receiver_id'  => $receiver_id,
                    'save_date'    => date('Y-m-d H:i:s'),
                    'thread_id'    => $thread_id,
                    'subject'      => $email_subject,
                    'text_message' => $email_message,
                    'is_reply'     => $reply
                ));
            }

            $success_message = __( 'Saved Successfully.', 'wpdating' );
        }
    }
}
?>

<?php
if ( isset( $success_message ) && !empty( $success_message ) ) {
	?>
	<div class="box-border">
		<div class="box-pedding">
			<p class="dspdp-text-success" style="text-align:center;"><?php echo $success_message; ?></p>
			<div style="text-align:center;">
                <a href="<?php echo trailingslashit($message_url) . 'received'; ?>" class="dspdp-btn dspdp-btn-info">
                    <?php echo __( 'Back to Inbox', 'wpdating' ) ?>
                </a>
			</div>
		</div>
	</div>
<?php } else {
    if ( isset( $message_error ) && !empty( $message_error ) ) { ?>
        <div class="thanks">
            <p style="text-align: center;" class="error">
                <?php echo $message_error ?>
            </p>
        </div>
    <?php } ?>
<div class="heading-submenu"><strong><?php echo __( 'New Message', 'wpdating' ); ?></strong></div>
<form name="compose-form" action="" method="post" class="message-block-compose">

    <div class="form-group">
        <label for="receiver_id"> <?php echo __( 'To', 'wpdating' ); ?>:</label>
        <select name="receiver_id" id="receiver_id" class="field1 dspdp-form-control">
            <?php
            if ( $action == 'reply' ){ ?>
                <option value="<?php echo $receiver_id?>" selected><?php echo $receiver_name; ?></option>
                <?php
            } else if ( isset( $_GET['receiver_id'] ) && !empty( $_GET['receiver_id'] ) ){ ?>
                <option value="<?php echo $receiver_id?>" selected><?php echo $receiver_name; ?></option>
                <?php
            }  else {
            ?>
                <option value="0"><?php echo __( 'Select', 'wpdating' ); ?></option>
                <?php
                foreach ($receivers as $receiver){ ?>
                    <option value="<?php echo $receiver->receiver_id; ?>" <?php echo ($receiver_id == $receiver->receiver_id) ? 'selected' : ''; ?>><?php echo $receiver->receiver_name; ?></option>
                <?php
                }
            } ?>
        </select>
    </div>

    <div class="form-group">
        <label for="subject"><?php echo __( 'Subject', 'wpdating' ) ?>:</label>
        <?php if ( $action == 'reply' ){ ?>
            <input type="text" id="subject" class="field1 dsp-form-control dspdp-form-control" value="<?php echo $email_subject; ?>" name="subject" readonly />
        <?php }else{ ?>
            <input type="text" id="subject" class="field1 dsp-form-control dspdp-form-control" name="subject" value="<?php echo $email_subject; ?>"/>
        <?php } ?>
    </div>

    <div class="form-group">
        <label for="message"><?php echo __( 'Message', 'wpdating' ) ?>:</label>
        <textarea class="dsp-form-control dsp-textarea dspdp-form-control" name="message" id="message" rows="10" required autofocus><?php echo $email_message; ?></textarea>
    </div>

    <div class="form-inline">
        <div class="form-group">
            <input type="submit" class="dspdp-btn dspdp-btn dspdp-btn-default" name="send" value="<?php echo __( 'Send Message', 'wpdating' ); ?>">
        </div>
        <div class="form-group">
            <input type="submit" class=" dspdp-btn dspdp-btn dspdp-btn-default" name="save" value="<?php echo __( 'Save', 'wpdating' ); ?>">
        </div>
    </div>
</form>
<?php } ?>