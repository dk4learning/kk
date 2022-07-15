<?php

include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("../general_settings.php");

include_once(WP_DSP_ABSPATH . "/files/includes/dsp_mail_function.php");

global $wpdb;


$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;


$session_user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';

$frnd_userid = isset($_REQUEST['frnd_userid']) ? $_REQUEST['frnd_userid'] : '';

$date = date("Y-m-d");

if ($session_user_id != "" && $frnd_userid != "" && ($session_user_id != $frnd_userid)) {

    $num_rows = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid");

    if ($num_rows <= 0) {
        $check_friend_notification = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE friend_request='N' AND user_id='$frnd_userid'");

        if ($check_friend_notification <= 0) {

            $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id = $session_user_id,friend_uid ='$frnd_userid' ,approved_status='N', date_added='$date'");

            $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='7'");

            $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$frnd_userid'");

            $reciver_name = $reciver_details->display_name;

            $receiver_email_address = $reciver_details->user_email;

            $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$session_user_id'");

            $sender_name = $sender_details->display_name;

            $admin_email = get_option('admin_email');

            $from = $admin_email;

            $url = $_SERVER['HTTP_HOST'];

            $email_subject = $email_template->subject;

            $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);

            $mem_email_subject = $email_subject;

            $email_message = $email_template->email_body;

            $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);

            $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);

            $email_message = str_replace("<#URL#>", $url, $email_message);

            $MemberEmailMessage = $email_message;

            dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");

            $print_msg = __(' Your Friend Request was sent successfully. After your request is accepted, you can view your added friends in your Friends list.', 'wpdating');

            echo $print_msg;
        } else {
            $print_msg = __('Alert Member Notifications-> You can&rsquo;t send Friend Request to this Member.', 'wpdating');
            echo $print_msg;
        }
    } else {
        $num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$session_user_id AND friend_uid=$frnd_userid AND approved_status='N'");

        if ($num_rows2 > 0) {

            $print_msg = __('You have Already sent a request to this Member!', 'wpdating');
            echo $print_msg;
        } else {
            $print_msg = __('Already your Friend!', 'wpdating');
            echo $print_msg;
        }
    }
} else if ($session_user_id == $frnd_userid) {

    $print_msg = __('You can&rsquo;t add yourself!', 'wpdating');
    echo $print_msg;
}
?>