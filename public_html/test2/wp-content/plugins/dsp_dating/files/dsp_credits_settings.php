<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author -  www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
extract($_REQUEST);
//var_dump($_REQUEST);die;
if (isset($save_credit)) {
    $wpdb->query("update $dsp_credits_table set price_per_credit='$price_per_credit',emails_per_credit='$emails_per_credit',gifts_per_credit='$gifts_per_credit'");
}
$credit_row = $wpdb->get_row("select * from $dsp_credits_table");
?>
<div id="general" class="postbox">
    <h3 class="hndle">
        <span>
            <?php echo __('Credits', 'wpdating'); ?>
        </span>
    </h3>

    <div class="credit-box">

        <div class="credit-form">
            <form method="post">
                <div class="credit-row">

                    <div class="credit-form-heading">
                        <?php echo __('Credit Per Price', 'wpdating'); ?>
                    </div>

                    <input type="text" name="price_per_credit" value="<?php echo $credit_row->price_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo __('This is the credit per price. Do not use the currency sign. Just put the number.', 'wpdating'); ?>
                    </div>
                </div>
                <div class="credit-row">
                    <div class="credit-form-heading">
                        <?php echo __('Credit Per Emails', 'wpdating'); ?>
                    </div>

                    <input type="text" name="emails_per_credit" value="<?php echo $credit_row->emails_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo __('This is the number of credits required to send per email.', 'wpdating'); ?>
                    </div>
                </div>
                <div class="credit-row">
                    <div class="credit-form-heading">
                        <?php echo __('Gifts Per Credit', 'wpdating'); ?>
                    </div>

                    <input type="text" name="gifts_per_credit" value="<?php echo $credit_row->gifts_per_credit; ?>"/>

                    <div class="credit-form-desc">
                        <?php echo __('This is the number of credits required to send per gifts.', 'wpdating'); ?>
                    </div>
                </div>

                <div class="credit-save">
                    <p>
                        <input name="save_credit" type="submit" value="<?php echo __('Save Changes', 'wpdating'); ?>"
                               class="button button-primary">
                    </p>
                </div>

            </form>
        </div>
        <!--  <div class="note-credit">
            <span class="credit-note-head">
                <?php echo __('Note:', 'wpdating'); ?>
            </span><div class="credit-note">
                <?php echo __('The number of emails a user can send per credit is based on the Emails per Credit you setup.', 'wpdating'); ?>
                <br />
                <?php echo __('For example, if you set the price at $1 dollar and set the Emails per Credit to 4 then when that', 'wpdating'); ?>
                <br />
                <?php echo __('User buys 1 credit, he can send 4 emails. After that he has to buy more credits or purchase a membership.', 'wpdating'); ?>
                </p>
            </div>
        </div> -->
    </div>