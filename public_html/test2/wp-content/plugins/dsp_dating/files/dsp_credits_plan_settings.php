<?php
$dsp_action           = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$credits_plan_table = $wpdb->prefix . DSP_CREDITS_PLAN_TABLE;

$plan_name          = isset($_REQUEST['txtcredits_name']) ? $_REQUEST['txtcredits_name'] : '';
$plan_amount        = isset($_REQUEST['txtcredits_price']) ? $_REQUEST['txtcredits_price'] : '';
$no_of_credits      = isset($_REQUEST['dsp_no_of_credits']) ? $_REQUEST['dsp_no_of_credits'] : '';
if($dsp_action){
if (isset($_POST['submit'])) {
    $wpdb->query("INSERT INTO $credits_plan_table (`credits_plan_id`, `plan_name`, `amount`, `no_of_credits`) VALUES (NULL, '$plan_name', '$plan_amount', '$no_of_credits');");
}
}
//------------------------start credits membership plan------------------------------------- //
if (isset($_GET['Action']) && $_GET['Action'] == "Del") {
    $credits_plan_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    $success = $wpdb->query("DELETE FROM $credits_plan_table WHERE credits_plan_id = '$credits_plan_id'");
    if ($success){
        ?>
        <div class="updated"><?php echo "Credits Plan has been Sucessfully deleted";?></div>
        <?php
    }
} // END if($_GET['Action']=="Del")
//------------------------ end credits membership plan------------------------------------- //
?>

<div id="general" class="postbox">
    <h3 class="hndle">
        <span>
            <?php echo __('Credits Plan', 'wpdating'); ?>
        </span>
    </h3>

    <div class="credit-box">
        <div class="credit-form">
            <form name="creditsform" method="post">
            <div class="inside">
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label>Credits Plan Name</label>
                    </th>
                    <td>
                        <input type="text" name="txtcredits_name" value="" class="regular-text" >
                    </td>
                </tr>
                                <tr>
                    <th>
                        <label>Credits Amount</label>
                    </th>
                    <td>
                        <input type="text" name="txtcredits_price" value="" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label>Number of Credits</label>
                    </th>
                    <td>
                        <input type="text" name="dsp_no_of_credits" value="" class="regular-text">
                    </td>
                </tr>
            </table>
            <div><input type="hidden" name="mode" value="add"></div>
            <input style="float:none;" type="submit" class="button button-primary" name="submit" value="Save Changes" onclick=" return Checkform();">
            </div>
        </form>
    </div>

<script>
    function Checkform() {
        if (document.creditsform.txtcredits_name.value == "") {
            alert('Please choose Plan Name.');
            document.creditsform.txtcredits_name.focus();
            return false;
        }
        if (document.creditsform.txtcredits_price.value == "") {
            alert('Please enter Amount.');
            document.creditsform.txtcredits_price.focus();
            return false;
        }
        if (document.creditsform.dsp_no_of_credits.value == "") {
            alert('Please enter Number of Credits.');
            document.creditsform.dsp_no_of_credits.focus();
            return false;
        }
        return true;
    }
</script>

</div>
<div id="general" class="postbox" style="padding:">
    <h3 class="hndle"><span><?php echo __('Credits Plan', 'wpdating'); ?></span></h3>
        <form name="updatedisplay_statusfrm" action="" method="post">
        <!--<div><div class="dsp_admin_headings"><?php //echo __('Memberships', 'wpdating');          ?></div></div>-->
        <div class="inside">
            <table class="form-table" style="width:50%" cellpadding="6">
                <tbody>
                <tr>
                    <th><?php echo __('Name', 'wpdating'); ?></th>
                    <th><?php echo __('Price', 'wpdating'); ?></th>
                    <th><?php echo __('Credits', 'wpdating'); ?></th>
                    <th><?php echo __('Actions', 'wpdating'); ?></th>
                </tr>

                <?php
                $myrows = $wpdb->get_results("SELECT * FROM $credits_plan_table Order by plan_name");
                foreach ($myrows as $memberships) { ?>
                    <tr>
                        <?php

                        $membership_name   = $memberships->plan_name;
                        $membership_amount = $memberships->amount;
                        $credits_id     = $memberships->credits_plan_id;
                        $no_of_credits     = $memberships->no_of_credits;
                        /*$check_plan_is_active = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_payments_table where pay_plan_id='$membership_id' AND payment_status='1'");*/
                        ?>
                            <td>
                                <?php _e($membership_name) ?>
                            </td>
                       
                            <td>
                                <label><?php echo $membership_amount; ?></label>
                            </td>

                            <td>
                                <label><?php echo $no_of_credits; ?></label>
                            </td>

                            <td>
                                <label><span onclick="delete_credits_plan(<?php echo $credits_id ?>);"
                                             class="span_pointer">Delete</span></label>
                            </td>

                    </tr>
                    <?php
                } // foreach ($myrows as $memberships)
                ?>

                </tbody>
            </table>
           
        </div>

    </form>

</div>