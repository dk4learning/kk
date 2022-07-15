<?php

$name  = isset($_POST['item_name']) ? $_POST['item_name'] : '';
$price = isset($_POST['amount']) ? $_POST['amount'] : '';

$membership_id = isset($_POST['membership_id']) ? $_POST['membership_id'] : '';
$user_id       = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$desc          = isset($_POST['desc']) ? $_POST['desc'] : '';
$image         = isset($_POST['image']) ? $_POST['image'] : '';
$image_url     = isset($_POST['image_url']) ? $_POST['image_url'] : '';
$name_1        = isset($_POST['name_1']) ? $_POST['name_1'] : '';
$uploadInfo    = isset($_POST['uploadInfo']) ? $_POST['uploadInfo'] : '';
$ajax_nonce    = wp_create_nonce( "discount-code-nonce" );

$no_of_days                          = isset($_POST['no_of_days']) ? $_POST['no_of_days'] : '';
$membership_stripe_recurring_plan_id = isset($_POST['membership_stripe_recurring_plan_id']) ? $_POST['membership_stripe_recurring_plan_id'] : '';

function add_membership_with_no_cost($days, $user_id, $membership_id, $name)
{
    global $wpdb;
    $exp_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s') . " +{$days}days"));
    $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
    $wpdb->delete($dsp_temp_payments_table, array('user_id' => $user_id));
    $data = array(
        'user_id'                  => $user_id,
        'plan_id'                  => $membership_id,
        'plan_amount'              => 0,
        'plan_days'                => $days,
        'plan_name'                => $name,
        'payment_date'             => date('Y-m-d'),
        'start_date'               => date('Y-m-d'),
        'expiration_date'          => date('Y-m-d',strtotime(date('Y-m-d') . " +{$days}days")),
        'payment_status'           => 1,
        'recurring_profile_id'     => null,
        'gateway_id'               => 0,
        'app_payment_date'         => date('Y-m-d H:i:s'),
        'app_start_date'           => date('Y-m-d H:i:s'),
        'app_expiration_date'      => $exp_time,
        'purchase_from'            => 'web',
        'recurring_profile_status' => 0
    );

    $status = $wpdb->insert($dsp_temp_payments_table,$data);
}
?>
<script>
    jQuery(document).ready(function(){
        jQuery(document).ready(function(){
            jQuery('tr.discount').hide();
            jQuery('tr.amount').hide();
            jQuery('span.dsp-check-coupan-code').click(function(){
                var coupanCode = jQuery('input.dsp-coupan-code').val();
                setCodes(coupanCode);
            });
            jQuery('#dsp_continue_discount').click(function(){
                var coupanCode = jQuery('input.dsp-coupan-code').val();
                set_discount(coupanCode);
            });

        });

        function autohideElements(time){
            setTimeout(function() {
                jQuery('div.error').fadeOut("slow");
            }, time);
        }
        function setCodes(code) {
            let item_name = "<?php echo $name; ?>";
            let amount = "<?php echo $price; ?>";
            let user_id = "<?php echo get_current_user_id(); ?>";
            let ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            let ajaxnonce = "<?php echo $ajax_nonce; ?>";
            if (code.trim() !== "") {
                jQuery.post(ajaxurl + "?action=dsp_coupan_code_calculation&_wpnonce="+ajaxnonce,
                    {code:code,amount:amount,item_name:item_name,id:user_id},
                    function(html1){
                    let html=JSON.parse(html1);
                        if(html.message !== ''){
                            jQuery('div.error').fadeIn();
                            jQuery('#amount').val(amount);
                            jQuery('tr.discount').hide();
                            jQuery('tr.amount').hide();
                            jQuery('div.error span').text(html.message);
                            autohideElements(5000);
                        }else{
                            if(html.hasOwnProperty('amount') && parseFloat(html['amount']) <= 0){ // amount is greater than discount
                                <?php add_membership_with_no_cost($no_of_days, $user_id, $membership_id, $name); ?>
                                window.location.href = "<?php echo site_url() . '/members/setting/dsp_thank_you'; ?>";
                            }else{
                                jQuery('#stripe_coupon').val(code);
                                jQuery('div.ajaxCall').fadeIn();
                                jQuery('div.dsp-discount-info').fadeOut();
                                jQuery('tr.totalAmount td:last-child').text(amount);
                                jQuery('tr.totalAmount').show();
                                jQuery('tr.discount td:last-child').text(html.discount);
                                jQuery('tr.discount').show();
                                jQuery('#amount').val(html.amount);
                                jQuery('tr.amount td:last-child').text(html.amount);
                                jQuery('tr.amount').show();
                            }
                        }
                    }
                );
            }
        }

    });

</script>

<div class=" ">
    <div class="box-border">
        <div class="box-pedding">
            <div class="setting-page">
                <ul class="dspdp-row dspdp-xs-text-center">
                    <li class="dspdp-col-sm-5">
                        <div class="purchase-credit-heading  dspdp-spacer">
                            <strong><?php echo __('Enter Your Discount Code', 'wpdating'); ?></strong></div>
                        <!-- <div class="purchase-credit-image dspdp-xs-form-group"><img class="dspdp-img-responsive dspdp-block-center" src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' /></div> -->
                    </li>
                    <li class="dspdp-col-sm-7">
                        <div class="input-credits dspdp-spacer">
                            <div class="dspdp-input-group dspdp-col-sm-12">
                                <div class="dspdp-col-sm-8"><input class="dspdp-form-control dsp-coupan-code"
                                                                   name="discount_code" type="text" value=""/></div>
                                <span class="dspdp-btn dspdp-btn-primary dspdp-col-sm-4 dsp-check-coupan-code"><?php echo __('Submit', 'wpdating'); ?></span>
                                <input type="hidden" name="membership_id_value" class="membership_id_value"
                                       value="<?php echo $membership_id; ?>">
                            </div>
                        </div>
                        <div class="error dspdp-col-sm-12" style="display: none;">
                            <span></span>
                        </div>
                        <div class="dsp-discount-info dspdp-col-sm-12">
                            <span class="description"><?php _e(__('If you don not have coupon code,Please leave it blank', 'wpdating')) ?></span>
                        </div>
                    </li>

                </ul>
            </div>

            <div>
                <table width="100%" class="stripe-discount-codes-table" style="display:none">
                    <tr class="printMessage">
                        <td style="font-size: 15px; color: red;"></td>
                    </tr>
                </table>
            </div>
            <div class="ajaxCall" style="display:none">
                <table width="100%">
                    <tr class="totalAmount">
                        <td><?php echo __('Amount', 'wpdating'); ?></td>
                        <td></td>
                    </tr>
                    <tr class="discount">
                        <td><?php echo __('Discount', 'wpdating'); ?></td>
                        <td></td>
                    </tr>
                    <tr class="amount">
                        <td><?php echo __('Amount After Discount', 'wpdating'); ?></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <form action="<?php echo site_url() . "/members/setting/upgrade_account/"; ?>" method="post">
            <input type="hidden" name="item_name" id="item_name"
                   value="<?php echo $name; ?>"/>
            <input type="hidden" name="name_1" id="name_1"
                   value="<?php echo $name_1; ?>"/>
            <input type="hidden" name="redirected_after_discount" value=1>
            <input type="hidden" name="amount" id="amount"
                   value="<?php echo $price; ?>"/>
            <input type="hidden" name="membership_id" id="membership_id"
                   value="<?php echo $membership_id; ?>"/>
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
            <input type="hidden" name="no_of_days" value="<?php echo $no_of_days; ?>"/>
            <input type="hidden" name="desc" value="<?php echo $desc; ?>"/>
            <input type="hidden" name="image" value="<?php echo $image; ?>"/>
            <input type="hidden" name="image_url" value="<?php echo $image_url; ?>"/>
            <input type="hidden" name="uploadInfo" value="<?php echo $uploadInfo; ?>"/>
            <input type="hidden" name="membership_stripe_recurring_plan_id"
                   value="<?php echo $membership_stripe_recurring_plan_id; ?>"/>
            <input type="hidden" name="stripe_coupon" id="stripe_coupon" class="stripe_coupon"
                   value=""/>

            <input type="submit" name="btn_Continue" value="Continue">
        </form>

    </div>
</div>