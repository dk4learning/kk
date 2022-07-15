<?php
$profile_subtab = get_query_var( 'profile-subtab' );
?>
<div class="profile-section-content">
    <?php    
    /*
      Copyright (C) www.wpdating.com - All Rights Reserved!
      Author - www.wpdating.com
      WordPress Dating Plugin
      contact@wpdating.com
     */
    //-------------------------------START UPGRADE ACCOUNT SETTINGS ----------------------------------
    //echo 'asdasd';
    //include_once('dsp_upgrade_paypal_advance.php');
    //error_reporting (0);
    //error_reporting(E_ALL);
    //ini_set('display_errors', 'On');
    global $wpdb;
    $dsp_payments_table = $wpdb->prefix. DSP_PAYMENTS_TABLE;
    $dsp_credits_plan_table = $wpdb->prefix. DSP_CREDITS_PLAN_TABLE;
    $dsp_memberships_table = $wpdb->prefix. DSP_MEMBERSHIPS_TABLE;
    $dsp_gateways_table = $wpdb->prefix. DSP_GATEWAYS_TABLE;
    $dsp_temp_payments_table = $wpdb->prefix. DSP_TEMP_PAYMENTS_TABLE;
    $imagepath = content_url('/');
    $check_credit_mode = wpee_get_setting('credit');
    $bloginfo_keys = array('admin_email', 'description', 'name', 'url', 'wpurl');
    $blogInfo      = array();
    $user_id = get_current_user_id();
    $profile_link = wpee_get_profile_url_by_id($user_id);
    $current_url = $profile_link . '/settings/upgrade-account/';
    $success_url = $profile_link . '/settings/thank-you/';
    $cancel_url = $profile_link . '/settings/cancel/';
    foreach ($bloginfo_keys as $bloginfo_key) {
        $blogInfo[$bloginfo_key] = get_bloginfo($bloginfo_key);
    }
    $uploadInfo = wp_upload_dir();

    $upgrade_mode           = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : get('mode');
    $membership_plan        = isset($_REQUEST['item_name']) ? $_REQUEST['item_name'] : '';
    $membership_plan_id     = isset($_REQUEST['membership_id']) ? $_REQUEST['membership_id'] : '';
    $membership_plan_amount = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '';
    $discount_code          = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
    $dateTimeFormat         = dsp_get_date_timezone();
    extract($dateTimeFormat);
    $payment_date = date("Y-m-d");
    // get the subscription detail from db
    $getSubDetailQuery       = "SELECT count(*) FROM $dsp_payments_table WHERE pay_user_id =$user_id AND recurring_profile_status = '1'";
    $recurringPaymentSatatus = $wpdb->get_var($getSubDetailQuery);
    if (($upgrade_mode == 'update') && $membership_plan_id != "") {
        $wpdb->query("DELETE FROM $dsp_temp_payments_table WHERE user_id = '$user_id'");
        $exist_membership_plan = $wpdb->get_row("SELECT * FROM $dsp_memberships_table where membership_id='$membership_plan_id'");
        $plan_days             = $exist_membership_plan->no_of_days;
        $wpdb->query("INSERT INTO $dsp_temp_payments_table SET user_id = '$user_id',plan_id = '$membership_plan_id',plan_amount ='$membership_plan_amount',plan_days='$plan_days',plan_name='$membership_plan',payment_date='$payment_date',start_date='$payment_date',expiration_date=DATE_ADD('$payment_date', INTERVAL $plan_days DAY),payment_status=0");
        $exist_gateway_address = $wpdb->get_row("SELECT * FROM $dsp_gateways_table");
        $business              = $exist_gateway_address->address;
        $currency_code         = $exist_gateway_address->currency;
        if (class_exists('Wpdating_Paypal_Public')) {
            $wpdating_paypal_public = new Wpdating_Paypal_Public();
            $append_name            = 'user_id';
            $append_value           = $user_id;
            $wpdating_paypal_public->get_custom_field_value();
            $custom_field_val = $wpdating_paypal_public->append_values_to_custom_field($append_name, $append_value);
        }
        ?>
        <form name="frm1" action="<?php echo $profile_link . '/settings/dsp_paypal/'; ?>" method="post">
            <input type="hidden" name="business" value="<?php echo $business ?>"/>
            <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>"/>
            <input type="hidden" name="item_name" value="<?php echo $membership_plan ?>"/>
            <input type="hidden" name="item_number" value="<?php echo $user_id ?>"/>
            <input type="hidden" name="amount" value="<?php echo $membership_plan_amount ?>"/>
            <input type="hidden" name="code" id="code" value="<?php echo $discount_code; ?>"/>
            <input type="Hidden" name="return" value="<?php echo $profile_link . '/settings/dsp_paypal/'; ?>">
            <input type="hidden" name="notify_url"
                   value="<?php echo site_url() . '/?wpdating-api=WC_Gateway_Paypal'; ?>">
            <input type="hidden" name="custom" value="<?php echo isset($custom_field_val) ? $custom_field_val : ''; ?>">
        </form>
        <script type="text/javascript">
            document.frm1.submit();
        </script>
        <?php
    }
    ?>

    <div class="wpee-premium-area">
        <?php
        $payment_row       = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
        if ($payment_row != null && strtotime($payment_row->expiration_date) > time()) {
            ?>
            <div class="premium-area">
                <div class="dspdp-row">
                    <div class="logo-premium">
                        <img class="dspdp-block dspdp-spacer-sm"
                             src="<?php echo content_url('/uploads/dsp_media/dsp_images/') . $wpdb->get_var("select image from $dsp_memberships_table where membership_id='" . $payment_row->pay_plan_id . "'"); ?>"
                             alt="<?php echo $payment_row->pay_plan_name; ?>"/><?php echo $payment_row->pay_plan_name; ?>
                    </div>
                    <div class="premium-content">
                        <div class="premium-content-msg">
                            <?php echo strip_tags(__('You\'re currently a Premium Member under <br />the', 'wpdating')); ?>
                            <?php echo $payment_row->pay_plan_name; ?><?php echo __('Plan.', 'wpdating'); ?>
                        </div>
                        <div class="premium-content-date">
                            <?php echo __('Your Monthly Membership expires on', 'wpdating'); ?>
                            <span
                                    class="dsp-emphasis-text dsp-strong"><?php echo date(get_option('date_format'),
                                    strtotime($payment_row->expiration_date)); ?></span>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="wpee-plan-item-wrapper d-flex no-list space-bet">
        <?php
        $exists_memberships_plan = $wpdb->get_results("SELECT * FROM $dsp_memberships_table where display_status='Y' ORDER BY date_added DESC");

        include(WP_DSP_ABSPATH . "members/loggedin/settings/upgrade_accounts/free_plan.php");

        foreach ($exists_memberships_plan as $membership_plan) {
            $price                               = $membership_plan->price;
            $no_of_days                          = $membership_plan->no_of_days;
            $name                                = $membership_plan->name;
            $membership_id                       = $membership_plan->membership_id;
            $desc                                = $membership_plan->description;
            $image                               = $membership_plan->image;
            $free_plan                           = $membership_plan->free_plan;
            $membership_stripe_recurring_plan_id = $membership_plan->stripe_recurring_plan_id;
            ?>

            <?php if ($free_plan == 1) {
                $free_plan_values    = array(
                    'membership_id' => $membership_id,
                    'imagepath'     => $imagepath,
                    'image'         => $image,
                    'name'          => $name,
                    'desc'          => $desc
                );
                $dsp_membership_plan = new dsp_membership_plan($free_plan_values);

                ?>

            <?php } else { ?>

                <div class="wpee-plan-item">
                    <ul class="wpee-plan-item-inner no-list">
                        <li>
                            <div class="wpee-plan-image-wrap">
                                <img class="wpee-plan-image" src='<?php echo $imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $image; ?>' title="<?php echo $name ?>" alt="<?php echo esc_attr($name) ?>"/>
                            </div>
                        </li>
                        <li>
                            <div class="wpee-plan-name"><strong><?php esc_html_e( $name, 'wpdating' ); ?></strong></div>
                        </li>
                        <li>
                            <div class="wpee-plan-desc"><?php esc_html_e( $desc, 'wpdating' ); ?></div>
                        </li>
                        <li>
                            <?php
                            global $wpee_general_settings;
                            if ($wpee_general_settings->gateways->status == 'Y') {
                                $gateway_table = $wpdb->get_results("SELECT * FROM $dsp_gateways_table");

                                foreach ($gateway_table as $gateway) {
                                    if ($gateway->gateway_name == 'paypal' && $gateway->status == 1) {
                                        ?>
                                        <div>
                                            <form name="paymentfrm" action="<?php echo $current_url; ?>" method="post">
                                                <input type="hidden" name="mode" id="mode_name" value="update"/>
                                                <input type="hidden" name="item_name" id="item_name" value="<?php echo $name; ?>"/>
                                                <input type="hidden" name="amount" id="amount" value="<?php echo $price; ?>"/>
                                                <input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership_id; ?>"/>
                                                <input name="upgrade" title="Upgrade / PayPal" type="submit"
                                                       value="<?php echo __('Upgrade / PayPal', 'wpdating') ?>"
                                                       class="dsp_span_pointer dspdp-btn dspdp-btn-default" style="text-decoration:none;"/>
                                                <br/>
                                                <span style="font-size:130%; "><?php echo $gateway->currency_symbol . $price ?></span>
                                                <br/>
                                            </form>
                                        </div>
                                        <?php

                                    }
                                    if ($gateway->gateway_name == 'bank_wire' && $gateway->status == 1) {
                                        ?>
                                        <div>
                                            <form name="bankWirePaymentForm" method="POST" action="<?php echo $profile_link . '/settings/bank-wire/?redirect_url=' . $current_url; ?> ">
                                                <input type="hidden" name="user_id" id="user_id" value="<?php echo get_current_user_id(); ?>"/>
                                                <input type="hidden" name="gateway_id" id="gateway_id" value="<?php echo $gateway->gateway_id; ?>"/>
                                                <input type="hidden" name="item_name" id="item_name" value="<?php echo $name; ?>"/>
                                                <input type="hidden" name="amount" id="amount" value="<?php echo $price; ?>"/>
                                                <input type="hidden" name="no_of_days" id="no_of_days" value="<?php echo $no_of_days; ?>"/>
                                                <input type="hidden" name="membership_id" id="membership_id" value="<?php echo $membership_id; ?>"/>
                                                <input name="upgrade" title="<?php echo __('Upgrade / Bank wire', 'wpdating'); ?>" type="submit"
                                                       value="<?php echo __('Upgrade / Bank wire', 'wpdating') ?>"
                                                       class="dsp_span_pointer dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:none;"/>
                                                <br/>
                                                <span style="font-size:130%; "><?php echo $gateway->currency_symbol; ?><?php echo $price ?></span>
                                                <br/>
                                            </form>
                                        </div>
                                        <?php
                                    }
                                } // end of for each loop

                                // do_action('dsp_payment_addons', $user_id, $membership_id, $name, $price,
                                //     $no_of_days, $desc, $image, $blogInfo, $uploadInfo,
                                //     $membership_stripe_recurring_plan_id, $success_url, $cancel_url );
                                do_action('dsp_payment_addons', $user_id, $membership_id, $name, $price,
                                    $no_of_days, $desc, $image, $blogInfo, $uploadInfo,
                                    $membership_stripe_recurring_plan_id);
                            }
                            else {
                                $gateway_row = $wpdb->get_row("SELECT * FROM $dsp_gateways_table where status='1'");
                                if (isset($gateway_row)) {
                                    if ($gateway_row->gateway_name == 'paypal') {
                                        ?>

                                        <div>
                                            <form name="paymentfrm"
                                                  action="<?php echo $current_url; ?>"
                                                  method="post">
                                                <input type="hidden" name="mode" id="mode_name"
                                                       value="update"/>
                                                <input type="hidden" name="item_name" id="item_name"
                                                       value="<?php echo $name; ?>"/>

                                                <input type="hidden" name="amount" id="amount"
                                                       value="<?php echo $price; ?>"/>
                                                <input type="hidden" name="membership_id"
                                                       id="membership_id"
                                                       value="<?php echo $membership_id; ?>"/>

                                                <input name="upgrade" title="Upgrade / PayPal"
                                                       type="submit"
                                                       value="<?php echo __('Upgrade / PayPal', 'wpdating') ?>"
                                                       class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                                       style="text-decoration:none;"/>
                                                <br/> <span
                                                    style="font-size:130%; "><?php $currencySymbol = isset($gateway_row->currency_symbol) ? $gateway_row->currency_symbol : '';
                                                    echo $currencySymbol; ?><?php echo $price ?></span>
                                                <br/>
                                            </form>
                                        </div>
                                        <?php
                                    } 

                                }
                                do_action('dsp_payment_addons', $user_id, $membership_id, $name, $price,
                                    $no_of_days, $desc, $image, $blogInfo, $uploadInfo,
                                    $membership_stripe_recurring_plan_id);
                            } // else if($check_gateways_mode->setting_status == 'N'){
                            ?>

                            <?php
                            if (is_plugin_active('wpdating-micropayment-addon/wpdating-micropayment-addon.php')) {

                                $user_data = get_userdata($user_id);
                                $data = array(
                                    'user_id'       => $user_id,
                                    'membership_id' => $membership_id,
                                    'title'         => $name,
                                    'paytext'       => $desc,
                                    'amount'        => $price,
                                    'email'         => $user_data->user_email,
                                );
                                do_action('wp_micropayment_form_button', $data);

                            }
                            ?>
                        </li>
                    </ul>

                </div>
            <?php } ?>
        <?php } ?>
        <?php if (dsp_check_discount_mode()) { ?>
            <?php //include("wp-content/plugins/dsp_dating/dsp_discount_mode.php"); ?>
        <?php } ?>
        <?php if ($check_credit_mode->setting_status == 'Y') {
            include_once(WPEE_PATH . "templates/profile/notification-blocks/my-profile/settings/payment/credits-upgrade/credits-upgrade.php"); ?>
        <?php } ?>
    </div>
    <script type="text/javascript">
        function payment(item_name, amount, id) {
            // alert(' paymanet  ' + item_name + ' ' + amount + ' ' + id);
            document.paymentfrm.item_name.value = item_name;
            document.paymentfrm.amount.value = amount;
            document.paymentfrm.membership_id.value = id;
//document.paymentfrm.submit();
        }
    </script>
    <?php
    //-------------------------------END UPGRADE ACCOUNT SETTINGS ---------------------------------- ?>
</div>