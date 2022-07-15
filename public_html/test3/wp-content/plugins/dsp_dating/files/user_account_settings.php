<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$txtusername = $_POST['txtusername'];
if (isset($_POST['change_account'])) {
//Check to make sure sure that a valid email address is submitted
    if (trim($_POST['txtemailbox']) === '') {
        $EmailError = __('You forgot to enter your email address.', 'wpdating');
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['txtemailbox']))) {
        $EmailError = __('You entered an invalid email address.', 'wpdating');
        $hasError = true;
    } else {
        $txtemailbox = trim($_POST['txtemailbox']);
    }



//Check to make sure that the Password Field is not empty
    if (trim($_POST['txtpassword1']) === "") {
        $Pass1Error = __('Please enter Pasword', 'wpdating');
        $hasError = true;
    } else {
        $txtpassword1 = $_POST['txtpassword1'];
    }
//Check to make sure that the Email is not empty
    if (trim($_POST['txtpassword1']) != trim($_POST['txtpassword2'])) {
        $confirmError = __('Please enter the same password in the two password fields.', 'wpdating');
        $hasError = true;
    } else {
        $txtpassword2 = $_POST['txtpassword2'];
    }


    //If there is no error, then profile updated

    if (!isset($hasError)) {

        if (isset($_POST['txtemailbox'])) {
            $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_email = '%s' WHERE ID = $user_id", $_POST['txtemailbox']));
        }

        if (isset($_POST['txtpassword1']) && isset($_POST['txtpassword2']) && $_POST['txtpassword1'] == $_POST['txtpassword2']) {
            $errors = $wpdb->query("UPDATE $wpdb->users SET user_pass = '" . wp_hash_password($_POST['txtpassword1']) . "' WHERE ID = $user_id");
        }
        $updated = true;
    }
}
$user_account_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$user_id'");
?>
<?php if (isset($updated) && $updated == true) { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo __('You Account Settings has been updated.', 'wpdating'); ?></p>
    </div>
<?php } ?>
<?php //---------------------------------------START ACCOUNT SETTINGS ------------------------------------//  ?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div class="box-page">
            <form name="frmuseraccount" method="post" action="">
                <div class="setting-page-account">
                    <p><?php echo __('Username', 'wpdating') ?>:</p>
                    <p><input type="text" name="txtusername" value="<?php echo $user_account_details->user_login ?>" disabled="disabled" />&nbsp;<?php echo __('Can&rsquo;t Change', 'wpdating') ?></p>
                    <p><?php echo __('Password: ', 'wpdating') ?></p>
                    <p><input type="password" name="txtpassword1" value="" />
                        <?php if ($Pass1Error != '') { ?>
                            <span class="error"><?php echo $Pass1Error; ?></span> 
                        <?php } ?></p>
                    <p><?php echo __('Confirm Password', 'wpdating') ?>:</p>
                    <p><input type="password" name="txtpassword2" value="" />
                        <?php if ($confirmError != '') { ?>
                            <span class="error"><?php echo $confirmError; ?></span> 
                        <?php } ?>
                    </p>
                    <p><?php echo __('Email', 'wpdating') ?>:</p>
                    <p><input type="text" name="txtemailbox" value="<?php echo $user_account_details->user_email ?>" />
                        <?php if ($EmailError != '') { ?>
                            <span class="error"><?php echo $EmailError; ?></span> 
                        <?php } ?>
                    </p>
                    <div class="btn-row"><input type="submit" name="change_account" value="<?php echo __('Submit', 'wpdating') ?>" class="dsp_submit_button" /></div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
//------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>