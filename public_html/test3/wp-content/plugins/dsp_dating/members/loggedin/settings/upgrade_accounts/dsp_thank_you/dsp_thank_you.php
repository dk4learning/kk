<?php
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
$discount_code = isset($_SESSION['code'])? $_SESSION['code']: '';

$update_payment_details = $wpdb->get_row("SELECT * FROM $dsp_temp_payments_table where user_id='$user_id'");
if ($update_payment_details) {
    $data1                   = array(
        'pay_plan_id'              => $update_payment_details->plan_id,
        'pay_plan_amount'          => $update_payment_details->plan_amount,
        'pay_plan_days'            => $update_payment_details->plan_days,
        'pay_plan_name'            => $update_payment_details->plan_name,
        'payment_date'             => $update_payment_details->payment_date,
        'start_date'               => $update_payment_details->start_date,
        'expiration_date'          => $update_payment_details->expiration_date,
        'payment_status'           => 1,
        'app_payment_date'         => $update_payment_details->app_payment_date,
        'app_start_date'           => $update_payment_details->app_start_date,
        'app_expiration_date'      => $update_payment_details->app_expiration_date,
        'purchase_from'            => $update_payment_details->purchase_from,
        'recurring_profile_status' => $update_payment_details->recurring_profile_status
    );
    $payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id = '$update_payment_details->user_id'");

    if (null == $payment_row){
        $data1['pay_user_id'] = $update_payment_details->user_id;
        $wpdb->insert($dsp_payments_table,$data1);
        $id = $wpdb->insert_id;

    }else{
        $wpdb->update($dsp_payments_table,$data1,array('pay_user_id' => $update_payment_details->user_id));
        $id = $payment_row->payment_id;
    }

    $wpdb->delete($dsp_temp_payments_table, array('user_id' => $update_payment_details->user_id));

    if( isset($discount_code) && !empty($discount_code)){
        dsp_update_discount_coupan_used($discount_code);
        add_user_meta(get_current_user_id(),'discount_code',$discount_code);
    }

    if (is_plugin_active('wpdating-micropayment-addon/wpdating-micropayment-addon.php')){
        if (isset($_GET['my-user-id'])){
            do_action('wp_update_payment_id',array($_GET['my-user-id'], $id ));
        }
    }

    if(dsp_issetGivenEmailSetting($user_id,'payment_successful')){
        $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='16'");
        $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
        $reciver_name = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;
        $siteurl = get_option('siteurl');
        $email_subject = $email_template->subject;
        $email_message = $email_template->email_body;
        $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
        $email_message = str_replace("<#DOMAIN_NAME#>", $siteurl, $email_message);
        $MemberEmailMessage = $email_message;
        $to = $receiver_email_address;
        $subject = $email_subject;
        $message = $MemberEmailMessage;
        $admin_email = get_option('admin_email');
        $from = $admin_email;
        $headers = "From: $from";
        // wp_mail($to, $subject, $message, $headers);
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $to, $subject, $message);
    }


} else {
    extract($_REQUEST);
    $credit_purchase_id = $wpdb->get_var("SELECT credit_purchase_id FROM `$dsp_credits_purchase_history` where user_id ='$user_id' and status ='0' ORDER BY  `credit_purchase_id` DESC  limit 1");
    $wpdb->update($dsp_credits_purchase_history, array('status' => 1), array('credit_purchase_id' => $credit_purchase_id));
    $credit = $wpdb->get_var("select credit_purchased from $dsp_credits_purchase_history where credit_purchase_id='$credit_purchase_id'");
    $credit_usage_row   = $wpdb->get_row("select * from $dsp_credits_usage_table where user_id='$user_id'");
    $credit_row = $wpdb->get_row("select * from $dsp_credits_table");
    $emails_per_credit  = $credit_row->emails_per_credit;
    $gifts_per_credit   = $credit_row->gifts_per_credit;

    $new_credits        = isset($credit_usage_row->no_of_credits) ? $credit_usage_row->no_of_credits + $credit : $credit;
    $new_emails         = floor($new_credits / $emails_per_credit);
    $new_gifts          = floor($new_credits / $gifts_per_credit);
    if ($credit_usage_row) {
        $wpdb->update($dsp_credits_usage_table, array(
            'no_of_credits' => $new_credits,
            'no_of_emails'  => $new_emails,
            'no_of_gifts'   => $new_gifts,
        ), array(
            'user_id' => $user_id));
    } else {
        $wpdb->insert($dsp_credits_usage_table, array('no_of_credits' => $new_credits,
            'no_of_emails' => $new_emails,
            'no_of_gifts'   => $new_gifts,
            'user_id' => $user_id));
    }

    $wpdb->query("update $dsp_credits_table set credits_purchased=credits_purchased+$credit");

    if(dsp_issetGivenEmailSetting($user_id,'credit_purchase')){
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
        $wpdating_email  = Wpdating_email_template::get_instance();
        $result = $wpdating_email->send_mail( $to, $subject, $message);
        // wp_mail($to, $subject, $message, $headers);
    }
}

if(isset($_SESSION['code'])){
unset($_SESSION['code']);
}
$url = get_site_url() .'/members';
header( "refresh:3;url=$url" );
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div align="center" style="color:#FF0000;"><b><?php echo __('Thank you for your payment!', 'wpdating'); ?></b></div>
    </div>
</div>