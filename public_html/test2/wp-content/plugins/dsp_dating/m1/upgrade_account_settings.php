<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_menu.php"); ?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Upgrade', 'wpdating'); ?></h1>
    <?php include_once("page_home.php"); ?>
</div>

<?php

global $wpdb;

$dsp_gateways_table = $wpdb->prefix . dsp_gateways;
$dsp_payments_table = $wpdb->prefix . dsp_payments;
$dsp_temp_payments_table = $wpdb->prefix . dsp_temp_payments;
$dsp_memberships_table = $wpdb->prefix . dsp_memberships;
$exist_membership_plan = $wpdb->get_results("SELECT * FROM {$dsp_memberships_table}");
//$getUserPaymentExpiryDate = $wpdb->get_row("SELECT expiration_date FROM $dsp_payments_table WHERE pay_user_id =$user_id");
$user_payment_expiry_date = $getUserPaymentExpiryDate->expiration_date;
$payment_date = date("Y-m-d");

if (strtotime($payment_date) > strtotime($user_payment_expiry_date)) {
    $user_payment_expired = true;
} else {
    $user_payment_expired = false;
}

if ($user_payment_expired) {
    ?>
    <div class="AndroidPayment">
        <?php
        foreach ($exist_membership_plan as $exist_memberships) {
            $currency_code_table = $wpdb->get_row("SELECT currency_symbol FROM $dsp_gateways_table");
            $currency_code = $currency_code_table->currency_symbol;
            $price = $exist_memberships->price;
            $no_of_days = $exist_memberships->no_of_days;
            $name = $exist_memberships->name;
            $membership_id = $exist_memberships->membership_id;
            $desc = $exist_memberships->description;
            $image = $exist_memberships->image;

            $getUserPaymentExpiryDate = $wpdb->get_var("SELECT expiration_date FROM $dsp_payments_table WHERE pay_plan_id ='$membership_id' AND pay_user_id= '$user_id'");
            $current_date = date("Y-m-d");
            if ($getUserPaymentExpiryDate == "") {
                $user_can_buy_item = true;
            } else {
                if (strtotime($current_date) > strtotime($getUserPaymentExpiryDate)) {
                    $user_can_buy_item = true;
                } else {
                    $user_can_buy_item = false;
                }
            }
            ?>

            <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                <div>
                    <div>
                        <div class="dsp_mail_lf">
                            <img src='<?php echo $imagepath ?>/uploads/dsp_media/dsp_images/<?php echo $image; ?>'
                                 title="<?php echo $name ?>" width="100" height="100"/>
                            <div style="font-size: 13px; font-weight: bold; text-align: center;">
                                <?php echo $currency_code ?><?php echo $price ?>
                            </div>
                        </div>
                        <div class="dsp_mail_rt">
                            <div style="text-align: left; word-wrap: break-word;padding-top: 10px;float: left;width: 100%;">
                                <?php echo $desc; ?><br/>
                                <?php echo $no_of_days . 'days plan'; ?>
                            </div>
                        </div>
                    </div>
                    <div>
                    <?php
                        if(!$user_can_buy_item){
                            echo "<b class='ui-body ui-body-d ui-corner-all' >
                                    You have already bought this.
                                </b>";
                        }
                    ?>
                    </div>
                </div>
            </li>

            <div class="btn-blue-wrap" style="padding-bottom: 10px;">
                <?php
                    if($user_can_buy_item){
                ?>
                <input type="button" class="mam_btn btn-blue"
                       onclick="upgradeMembership('<?php echo $user_id; ?>','<?php echo $exist_memberships->membership_id; ?>','<?php echo $exist_memberships->price ?>','<?php echo $exist_memberships->name; ?>','<?php echo $exist_memberships->no_of_days; ?>','<?php echo $user_can_buy_item; ?>')"
                       value="Buy">
                       <?php
                   } 
                  ?>
            </div>
        <?php } ?>
    </div>
    <div class="ui-content" data-role="content">
        <div class="content-primary">
            <div class="IosPayment">
                <?php
                    $exists_memberships_plan = $wpdb->get_results("SELECT * FROM $dsp_memberships_table where display_status='Y' ORDER BY date_added DESC");
                    foreach ($exists_memberships_plan as $membership_plan) {
                        $price                               = $membership_plan->price;
                        $no_of_days                          = $membership_plan->no_of_days;
                        $name                                = $membership_plan->name;
                        $membership_id                       = $membership_plan->membership_id;
                        $desc                                = $membership_plan->description;
                        $image                               = $membership_plan->image;
                        $free_plan                           = $membership_plan->free_plan;
                        $membership_stripe_recurring_plan_id = $membership_plan->stripe_recurring_plan_id;
                        if ($free_plan == 1) {
                            $check_already_user_exists = $wpdb->get_var("SELECT count(*) FROM $dsp_payments_table where pay_user_id='$user_id'");
                            if($check_already_user_exists>0) {
                                $check_account_expire = $wpdb->get_row("SELECT * FROM $dsp_payments_table where pay_user_id='$user_id'");
                                $start_date = $check_account_expire->start_date;
                                $payment_status = $check_account_expire->payment_status;
                                $expiration_date = $check_account_expire->expiration_date;
                                $pay_plan_days = $check_account_expire->pay_plan_days;
                                $current_date = date("Y-m-d");
                                $cal_days = daysDifference($current_date, $start_date);
                                if ($cal_days > $pay_plan_days) {
                                    if ($payment_status == '1') {
                                        $wpdb->query("UPDATE $dsp_payments_table SET payment_status=2 WHERE pay_user_id = '$check_account_expire->pay_user_id'");
                                    }
                                    $btn_part = 'value="Apply for free plan"';
                                } else {
                                    $btn_part = 'value="Already Applied" disabled';
                                }
                            } else {
                                $btn_part = 'value="Apply for free plan"';
                            }
                            ?>
                            <input type="hidden" id="free_user_id" name="free_user_id" value=<?php echo $user_id ?>>
                            <input type="hidden" id="free_membership_id" name="free_membership_id" value=<?php echo $membership_id ?>>
                            <?php
                            echo '
                                <div class="btn-blue-wrap" style="padding-bottom: 10px;">
                                    <input type="button" class="mam_btn btn-blue"
                                    onclick="applyForFreePlan()"
                                    id="free_apply_btn"'.$btn_part.'
                                    >
                                </div>';
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <?php
} else { ?>
    <div class="ui-content" data-role="content">
        <div class="content-primary">
            <div class=" userlist alert-message">
                Your account has not expired yet.
            </div>
        </div>
    </div>
<?php } ?>
<script type="text/javascript">

    function payment(item_name, amount, id) {
        alert(' paymanet  ' + item_name + ' ' + amount + ' ' + id);

        document.paymentfrm.item_name.value = item_name;

        document.paymentfrm.amount.value = amount;

        document.paymentfrm.membership_id.value = id;
    }

</script>
<?php include_once("dspLeftMenu.php"); ?>