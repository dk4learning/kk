<?php
include("../../../../wp-config.php");

global $wpdb;
$user_id = $_REQUEST['free_user_id'];
$membership_id = $_REQUEST['free_membership_id'];

//$user_id = $current_user->ID;
$dsp_payments_table = $wpdb->prefix . DSP_PAYMENTS_TABLE;
$dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;

$check_already_user_exists = $wpdb->get_var("SELECT count(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
$membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_id'");

$pay_plan_id = $membership_id;
$pay_plan_amount = 0;
$pay_plan_days = $membership_plan->no_of_days;
$pay_plan_name = $membership_plan->name;
$payment_date = date("Y-m-d");
$start_date = date("Y-m-d");

$expiration_date = date_create(date("Y-m-d"));
date_add($expiration_date, date_interval_create_from_date_string("'" . $pay_plan_days . " days'"));
$expiration_date = date_format($expiration_date, 'Y-m-d');

$payment_status = 1;

if ($check_already_user_exists > 0) {

    $wpdb->update(
        $dsp_payments_table,
        array(
            'pay_plan_id' => $membership_id,
            'pay_plan_amount' => $pay_plan_amount,
            'pay_plan_days' => $pay_plan_days,
            'pay_plan_name' => $pay_plan_name,
            'payment_date' => $payment_date,
            'start_date' => $start_date,
            'expiration_date' => $expiration_date,
            'payment_status' => $payment_status
        ),
        array('pay_user_id' => $user_id)
    );
} else {
    $wpdb->insert(
        $dsp_payments_table,
        array(
            'pay_user_id' => $user_id,
            'pay_plan_id' => $membership_id,
            'pay_plan_amount' => $pay_plan_amount,
            'pay_plan_days' => $pay_plan_days,
            'pay_plan_name' => $pay_plan_name,
            'payment_date' => $payment_date,
            'start_date' => $start_date,
            'expiration_date' => $expiration_date,
            'payment_status' => $payment_status
        )
    );
}
$success = $pay_plan_name . ' ' . __('Plan', 'wpdating') . ' ' . __('is activated', 'wpdating') ;
$response = array('message'=>$success);
echo json_encode($response);