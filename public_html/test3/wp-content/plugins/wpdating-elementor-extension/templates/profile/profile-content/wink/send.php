<?php
$sender_ID = get_current_user_id();
$get_receiver_id = get('receiver_id');
// GET post values
$cmbwinktext = isset($_REQUEST['cmbwinktext_id']) ? $_REQUEST['cmbwinktext_id'] : '';
$sender_id = isset($_REQUEST['sender_id']) ? $_REQUEST['sender_id'] : '';
$receiver_id = isset($_REQUEST['receiver_id']) ? $_REQUEST['receiver_id'] : '';
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$dateTimeFormat = dsp_get_date_timezone();
$send_date = date("Y-m-d H:m:s");
// 
if(dsp_issetGivenEmailSetting($get_receiver_id,'wink')){
    if ($mode == "sent") {
        if (trim($cmbwinktext) == 0) {
            $winktext_Error = __('You forgot to select Wink text.', 'wpdating');
            $hasError = true;
        } else {
            $friend_id = trim($cmbwinktext);
        }


        // Checked member is in user blocked list
        $checked_block_member = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blocked_members_table WHERE user_id=$receiver_id AND block_member_id='$user_id'");

        //checked blocked member 
        if ($checked_block_member > 0) {
            $blocked_Error = "blocked";
            $hasError = true;
        } else {
            $receiver_id = trim($receiver_id);
        }

        //If there is no error, then Message sent
        if (!isset($hasError) ) {
            $wpdb->query("INSERT INTO $dsp_member_winks_table SET sender_id='$sender_id',receiver_id='$receiver_id',wink_id='$cmbwinktext',send_date='$send_date',wink_read='N'");
            dsp_add_notification($sender_id, $receiver_id, 'send_wink');

            $wink_message = $wpdb->get_row("SELECT * FROM $dsp_flirt_table WHERE Flirt_ID='$cmbwinktext'");
            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='1'");
            $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$receiver_id'");
            $reciver_name = $reciver_details->display_name;
            $receiver_email_address = $reciver_details->user_email;
            $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$sender_id'");
            $sender_name = $sender_details->display_name;
            
            $url = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
            $email_subject = $email_template->subject;
            $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
            $mem_email_subject = $email_subject;

            $email_message = $email_template->email_body;
            $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
            $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
            $email_message = str_replace("<#WINK_MESSAGE#>", $wink_message->flirt_Text, $email_message);
            $email_message = str_replace("<#URL#>", $url, $email_message);
            $MemberEmailMessage = $email_message;
            // dsp_send_email($receiver_email_address, get_option('admin_email'), $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
            
            $wpdating_email  = Wpdating_email_template::get_instance();
            $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $message_sent = true;
        }
    }
    ?>
<?php }else{ ?>
        <div class="dsp_box-out">
            <div class="dsp_box-in">
                <p style="color:#FF0000; padding-left:30px;">
                     <?php    
                         $print_msg = __('Alert Member Notifications-> You can&rsquo;t send wink to this Member.', 'wpdating');
                         echo $print_msg;
                     ?>     
                </p>
                <p style="text-align:center;">
                    <a href="<?php echo $root_link . get_username($get_receiver_id) . "/"; ?>" class="dspdp-btn dspdp-btn-info"><?php echo __('Back to Profile', 'wpdating') ?></a>
                </p>    
            </div>
        </div>
<?php } ?>