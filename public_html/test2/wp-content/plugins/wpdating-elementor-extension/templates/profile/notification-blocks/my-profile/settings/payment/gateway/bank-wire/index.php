<?php
if ( isset( $_POST['upgrade'] ) ) {
    global $wpdb;
    $wpdb->delete( "{$wpdb->prefix}dsp_temp_payments", array( 'user_id' => $_POST['user_id'] ) );

    $current_date = date('Y-m-d');
    $wpdb->insert( "{$wpdb->prefix}dsp_temp_payments", array(
        'user_id'         => $_POST['user_id'],
        'plan_id'         => $_POST['membership_id'],
        'gateway_id'      => $_POST['gateway_id'],
        'plan_amount'     => $_POST['amount'],
        'plan_days'       => $_POST['no_of_days'],
        'plan_name'       => $_POST['item_name'],
        'payment_date'    => $current_date,
        'start_date'      => $current_date,
        'expiration_date' => date('Y-m-d', strtotime("+{$_POST['no_of_days']} days")),
        'payment_status'  => 0
    ) );

    $gateway_details = apply_filters( 'dsp_get_gateway_details', 'bank_wire' );
    ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <div class="gateway_info" style="text-align: center">
                <h2><?php echo $gateway_details->title;?></h2>
                <div class="description">
                    <p><?php echo $gateway_details->description;?></p>
                </div>
                <div class="instruction">
                    <p><?php echo $gateway_details->instruction;?></p>
                </div>
            </div>
            <div style="color:#FF0000; text-align: center;"><b><?php echo __('Thank you for your payment!', 'wpdating'); ?></b></div>
        </div>
    </div>
    <?php
} else {
    wp_redirect( $_GET['redirect_url'] );
}