<?php
global $wpdb;

extract($_REQUEST);
$credits_plan_table = $wpdb->prefix . DSP_CREDITS_PLAN_TABLE;
$dsp_credits_purchase_history = $wpdb->prefix . DSP_CREDITS_PURCHASE_HISTORY_TABLE;
$dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
$user_id = get_current_user_id();
$profile_link = wpee_get_profile_url_by_id($user_id);
$current_url = $profile_link . '/settings/upgrade-account/';

if ( isset( $upgrade_credit ) ) {

    $exist_gateway_address = $wpdb->get_row( "SELECT * FROM $dsp_gateways_table" );
    $business              = $exist_gateway_address->address;
    $currency_code         = $exist_gateway_address->currency;
    $credit_purchase_data  = array(
        'user_id'          => $user_id,
        'status'           => 0,
        'credit_price'     => $credit_amount,
        'credit_purchased' => $no_of_credit_to_purchase,
        'purchase_date'    => date( 'Y-m-d H:i:s' )
    );

    $wpdb->insert( $dsp_credits_purchase_history, $credit_purchase_data );
    $inserted_id = $wpdb->insert_id;
    $user_id = get_current_user_id();
    $profile_link = wpee_get_profile_url_by_id($user_id);
    $current_url = $profile_link . '/settings/credit-upgrade/';

    ?>
    <form name="frm1" action="<?php echo $profile_link . "/settings/dsp_paypal/"; ?>" method="post">
        <input type="hidden" name="business" value="<?php echo $business ?>"/>
        <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>"/>
        <input type="hidden" name="item_name" value="Credits Purchase"/>
        <input type="hidden" name="item_number" value="<?php echo $user_id ?>"/>
        <input type="hidden" name="amount" value="<?php echo $credit_amount ?>"/>
        <input type="hidden" name="return"
               value="<?php echo $profile_link . "/settings/credit-upgrade-details/?credit_purchase_id=" . $inserted_id; ?>">
        <input type="hidden" name="notify_url"
               value="<?php echo $profile_link . "/settings/credit-upgrade-details/?credit_purchase_id=" . $inserted_id; ?>">
    </form>
    <script type="text/javascript">
        document.frm1.submit();
    </script>
    <?php
}
$credit_row          = $wpdb->get_row( "select * from $dsp_credits_table" );
$currency_code_table = $wpdb->get_row( "SELECT currency_symbol FROM $dsp_gateways_table" );
$currency_code       = $currency_code_table->currency_symbol;
?>


<div class="wpee-plan-item">
    <script>
        function change_credit(val) {
            var credits_text = "<?php echo ' ' . language_code( 'DSP_CREDIT_MODE' ) ?>";
            var currency_symbol = "<?php echo $currency_code_table->currency_symbol; ?>";
            var per_credit = <?php echo $credit_row->price_per_credit; ?>;
            var new_credits = parseFloat((val * per_credit).toFixed(3));
            jQuery(".no_of_credit_to_purchase").each(function () {
                jQuery(this).val(new_credits);
            });
            if (jQuery.trim(val) != "") {
                jQuery(".credit_price_change").each(function () {
                    jQuery(this).html(new_credits + credits_text);
                });
                jQuery(".credit_amount").each(function () {
                    jQuery(this).val(val);
                });

                var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' )?>';

                var data = {
                    action: 'stripe_ajax',
                    post_var: val
                };

                // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#stripe-test').html(response);
                    jQuery(".no_of_credit_to_purchase").each(function () {
                        jQuery(this).val(new_credits);
                    });
                });


                /**
                 * For CCBILL
                 */

                var data = {
                    action: 'ccbill_ajax',
                    post_var: val
                };

                // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('.ccbill-test').html(response);
                    jQuery(".no_of_credit_to_purchase").each(function () {
                        jQuery(this).val(new_credits);
                    });
                });

                return false;
            }
            else {
                jQuery(".credit_price_change").each(function () {
                    jQuery(this).html(0 + credits_text);
                });
                jQuery(".credit_amount").each(function () {
                    jQuery(this).val(0);
                });

                var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' )?>';

                var data = {
                    action: 'stripe_ajax',
                    post_var: val
                };

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#stripe-test').html(response);
                });

                /**
                 * For CCBILL
                 */

                var data = {
                    action: 'ccbill_ajax',
                    post_var: val
                };

                // the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#ccbill-test').html(response);
                });
                return false;
            }
        }
        jQuery(document).ready(function (e) {
            jQuery("#no_of_credits").keydown(function (event) {
                if (event.shiftKey) {
                    event.preventDefault();
                }

                if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
                }
                else {
                    if (event.keyCode < 95) {
                        if (event.keyCode < 48 || event.keyCode > 57) {
                            event.preventDefault();
                        }
                    }
                    else {
                        if (event.keyCode < 96 || event.keyCode > 105) {
                            event.preventDefault();
                        }
                    }
                }
            });

        });
    </script>
    <ul class="wpee-plan-item-inner no-list">
        <li class="wpee-plan-item-title">
            <div class="purchase-credit-heading"><?php echo __( 'Purchase Credits', 'wpdating' ); ?></div>
            <div class="purchase-credit-image dspdp-xs-form-group"><img
                        class="dspdp-img-responsive dspdp-block-center"
                        src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' alt="credit purchase"/>
            </div>
        </li>
        <li class="wpee-plan-item-credits">
            <div class="input-credits dspdp-spacer">
                <div class="dspdp-input-group dsp-input-group"><input
                            class="dspdp-form-control dsp-form-control" id="no_of_credits" type="text" value=""
                            on onkeyup="change_credit(this.value);"/>
                    <span
                            class="dspdp-input-group-addon dsp-input-group-addon"><?php echo $currency_code ?></span>
                </div>
            </div>
            <span class="default-credit-value dspdp-h4 dspdp-block  dsp-h4 dsp-block">
            <?php echo $credit_row->price_per_credit . ' ' . __( 'Credits Per', 'wpdating' ) . ' ' . $currency_code; ?>
        </span>
            <input type="hidden" id="credits_per_price"
                   value="<?php echo $credit_row->price_per_credit; ?>"/>
        </li>
        <li class="wpee-plan-item-form">

            <?php
            $gateway_table = $wpdb->get_results( "SELECT * FROM $dsp_gateways_table" );
            foreach ( $gateway_table as $gateway ) {
                if ( $gateway->gateway_name == 'paypal' && $gateway->status == 1 ) {
                    ?>
                    <div>
                        <form name="paymentfrm"
                              action="<?php echo $profile_link . "/settings/upgrade-account/mode/update/"; ?>"
                              method="post">
                            <input type="hidden" name="mode" id="mode_name" value="update"/>
                            <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credit_row->price_per_credit; ?>"/>
                            <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="1"/>
                            <input name="upgrade_credit" title="<?php echo __('Upgrade / PayPal', 'wpdating') ?>" type="submit"
                                   value="<?php echo __('Upgrade / PayPal', 'wpdating'); ?>" class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                   style="text-decoration:none;"/>
                            <br/>
                            <span style="font-size:13px; font-weight:bold;" class="credit_price_change">
                                <?php echo $credit_row->price_per_credit . ' ' . __( 'Credits', 'wpdating'); ?>
                            </span>
                            <br/>
                        </form>
                    </div>

                    <?php
                } 
            } // end of for each loop

            do_action( 'dsp_payment_addons_credit', $credit_row->price_per_credit );

            if (is_plugin_active('wpdating-micropayment-addon/wpdating-micropayment-addon.php')){
                $user_id   = get_current_user_id();
                $user_data = get_userdata($user_id);
                $data = array(
                    'user_id'       => $user_id,
                    'amount'        => 1,
                    'title'         => 'Custom credit',
                    'paytext'       => 'Custom credit purchase',
                    'credit_amount' => $credit_row->price_per_credit,
                    'email'         => $user_data->user_email,
                );
                do_action('wp_micropayment_form_button', $data);
            }
            ?>
        </li>
    </ul>
</div>