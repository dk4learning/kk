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


$no_of_days                          = isset($_POST['no_of_days']) ? $_POST['no_of_days'] : '';
$membership_stripe_recurring_plan_id = isset($_POST['membership_stripe_recurring_plan_id']) ? $_POST['membership_stripe_recurring_plan_id'] : '';

$user = wp_get_current_user();
$_POST["wpdm_name"] = $user->display_name;
$_POST["wpdm_email"] = $user->user_email;

if(isset($_POST['membership_id']) && isset($_POST['user_id'])) {
    ?>
    <div class=" ">
        <div class="box-border">
            <div class="box-pedding">
                <div class="setting-page">
                    <ul class="dspdp-row dspdp-xs-text-center">
                        <li class="dspdp-col-sm-5">
                            <div class="purchase-credit-heading  dspdp-spacer">
                                <strong><?php echo __('Enter Your Discount Code', 'wpdating'); ?></strong></div>
                        </li>
                        <li class="dspdp-col-sm-7">
                            <div class="input-credits dspdp-spacer">
                                <div class="dspdp-input-group dspdp-col-sm-12">
                                    <div class="dspdp-col-sm-8"><input class="dspdp-form-control dsp-coupan-code"
                                                                       name="discount_code" type="text" value=""/></div>
                                    <span class="dspdp-btn dspdp-btn-primary dspdp-col-sm-4 dsp-mollie-coupan-code"><?php echo __('Submit', 'wpdating'); ?></span>
                                    <input type="hidden" name="membership_id_value" class="membership_id_value"
                                           value="<?php echo $membership_id; ?>">
                                </div>
                            </div>
                            <div class="dsp-discount-info dspdp-col-sm-12">
                                <span class="description"><?php _e(__('If you don not have coupon code,Please leave it blank', 'wpdating')) ?></span>
                            </div>
                        </li>

                    </ul>
                </div>

                <div>
                    <table width="100%" class="mollie-discount-codes-table" style="display:none">
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

            <form action="<?php echo site_url() . "/members/setting/wpdm_mollie/"; ?>" method="post">
                <?php do_action('dsp_mollie_payment_addons'); ?>
                <input type="hidden" name="item_name" id="item_name"
                       value="<?php echo $name; ?>"/>
                <input type="hidden" name="membership_id" id="membership_id"
                       value="<?php echo $membership_id; ?>"/>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
                <input type="hidden" name="no_of_days" value="<?php echo $no_of_days; ?>"/>
                <input type="hidden" name="plan_name" value="<?php echo $desc; ?>"/>
                <input type="hidden" name="mollie_coupon" class="mollie_coupon"
                       value=""/>
                <input type="submit" name="wpdm_submitted" value="Continue">
            </form>

        </div>
    </div>
    <?php
}else{
    ?>
    <form action="<?php echo site_url() . "/members/setting/wpdm_mollie/"; ?>" method="post">
        <?php do_action('dsp_mollie_payment_addons'); ?>
        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>
        <input type="hidden" name="wpdm_credit_payment" class="wpdm_credit_payment"
               value="1"/>
        <input type="submit" name="wpdm_submitted" value="Continue">
    </form>
<?php
}
?>