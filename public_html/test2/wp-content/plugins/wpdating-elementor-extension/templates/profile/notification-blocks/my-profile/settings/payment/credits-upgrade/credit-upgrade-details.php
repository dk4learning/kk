<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_credits_purchase_history   = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
$dsp_credits_table              = $wpdb->prefix . DSP_CREDITS_TABLE;
$dsp_credits_usage_table        = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_user_table                 = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_email_templates_table      = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;

$pay_member_id = get_current_user_id();
extract($_REQUEST);

$credit_purchase_id = $_GET['credit_purchase_id'];

$credit_purchase_row = $wpdb->get_row("select * from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");

if( $credit_purchase_row->status == 0 ){
    $wpdb->update($dsp_credits_purchase_history, array('status' => 1), array('credit_purchase_id' => $credit_purchase_id));

    $credit             = $credit_purchase_row->credit_purchased;
    $chk_credit_row     = $wpdb->get_var("select count(*) from $dsp_credits_usage_table where user_id='$pay_member_id'");
    $credit_row         = $wpdb->get_row("select * from $dsp_credits_table");
    $emails_per_credit  = $credit_row->emails_per_credit;
    $gift_per_credit    = $credit_row->gifts_per_credit;
    $new_emails         = $credit * $emails_per_credit;
    $new_gifts          = $credit * $gift_per_credit;

    if ($chk_credit_row > 0) {
        $credit_usage_row = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$pay_member_id'");
        $wpdb->update($dsp_credits_usage_table, array('no_of_credits' => $credit_usage_row->no_of_credits + $credit,
            'no_of_emails' => $credit_usage_row->no_of_emails + $new_emails, 'no_of_gifts' => $credit_usage_row->no_of_gifts + $new_gifts), array(
            'user_id' => $pay_member_id));
    } else {
        $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $credit, 'no_of_emails' => $new_emails,'no_of_gifts' => $new_gifts,
            'user_id' => $pay_member_id));
    }
    $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");


    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='20'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $siteurl = get_option('siteurl');
    $email_subject = $email_template->subject;
    $email_message = $email_template->email_body;
    $email_message = str_replace("<#AMOUNT-OF-CREDITS#>", $credit, $email_message);
    $MemberEmailMessage = $email_message;
    $to = $receiver_email_address;
    $subject = $email_subject;
    $message = $MemberEmailMessage;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    $headers = "From: $from";
    // wp_mail($to, $subject, $message, $headers);
    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail( $to, $subject, $message );
    ?>
    <div class="wpee-thank-you">
        <div align="center" style="color:#FF0000;"><b><?php echo __('Thank you for your payment!', 'wpdating'); ?></b></div>
    </div>
    <?php
} else {
    wp_redirect(wpee_user_profile_url());
}

