</div>
<div class="box-border">
    <div class="box-pedding">
        <div class="box-page dsp-favourite-notification">
            <?php
            $session_user_id = get('user_id');
            $frnd_userid = get('frnd_userid');
            $date = date("Y-m-d");
            if ($session_user_id != "" && $frnd_userid != "" && ($session_user_id != $frnd_userid)) {
                $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid");
                //dsp_debug($num_rows);die;
                if ($num_rows <= 0) {
                    $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE friend_request='N' AND user_id='$frnd_userid'");
                    if ($check_friend_notification <= 0) {

                        $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id = $session_user_id,friend_uid ='$frnd_userid' ,approved_status='N', date_added='$date'");
                        dsp_add_notification($session_user_id, $frnd_userid, 'friend_request');
                        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='7'");
                        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$frnd_userid'");
                        $reciver_name = $reciver_details->display_name;
                        $receiver_email_address = $reciver_details->user_email;
                        $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$session_user_id'");
                        $sender_name = $sender_details->display_name;
                        $admin_email = get_option('admin_email');
                        $from = $admin_email;
                        $url = apply_filters('dsp_get_message_based_on_gender',$session_user_id);
                        $email_subject = $email_template->subject;
                        $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
                        
                        $mem_email_subject = $email_subject;
                        $email_message = $email_template->email_body;
                        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
                        $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
                        $email_message = str_replace("<#URL#>", $url , $email_message);
                        $MemberEmailMessage = $email_message;
                        // dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
                        $wpdating_email  = Wpdating_email_template::get_instance();
                        $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
                        $print_msg = __(' Your Friend Request was sent successfully. After your request is accepted, you can view your added friends in your Friends list.', 'wpdating');
                    } else {
                        $print_msg = __('Alert Member Notifications-> You can&rsquo;t send Friend Request to this Member.', 'wpdating');
                    }
                } else {
                    $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid AND approved_status='N'");
                    if ($num_rows2 > 0) {
                        $print_msg = __('You have Already sent a request to this Member!', 'wpdating');
                    } else {
                        $print_msg = __('Already your Friend!', 'wpdating');
                    }
                    
                }
            } else if ($session_user_id == $frnd_userid) {
                $print_msg = __('You can&rsquo;t add yourself!', 'wpdating');
            }
            ?>
            <div class="msg">
                <?php echo $print_msg; ?>
            </div>
            <div style="text-align:center;"><a href="<?php echo $root_link . get_username($frnd_userid) . "/"; ?>" class="dspdp-btn dspdp-btn-info"><?php echo __('Back to Profile', 'wpdating') ?></a></div>
        </div>
    </div>
</div>
