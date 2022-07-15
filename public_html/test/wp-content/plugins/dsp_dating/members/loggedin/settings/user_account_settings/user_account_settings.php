<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */
$DSP_PAYMENTS_TABLE = $wpdb->prefix . DSP_PAYMENTS_TABLE;
//----- code for cancel subscription------------------------
$getMemProfIDQuery = "select recurring_profile_id from $DSP_PAYMENTS_TABLE where  pay_user_id=$user_id and recurring_profile_status='1'";
$recurring_profile_res = $wpdb->get_row($getMemProfIDQuery);
//----- code for cancel subscription------------------------
$txtusername = isset($_REQUEST['txtusername']) ? esc_sql(sanitizeData(trim($_REQUEST['txtusername']), 'xss_clean')) : '';
if (isset($_POST['change_account'])) {
//Check to make sure sure that a valid email address is submitted
    if (trim($_POST['txtemailbox']) === '') {
        $EmailError = __('You forgot to enter your email address.', 'wpdating');
        $hasError = true;
    }  else if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i", trim($_POST['txtemailbox']))) {
        $EmailError = __('You entered an invalid email address.', 'wpdating');
        $hasError = true;
    } else {
        $txtemailbox = esc_sql(sanitizeData(trim($_POST['txtemailbox']), 'xss_clean'));
    }



//Check to make sure that the Password Field is not empty
    if (trim($_POST['txtpassword1']) === "") {
        $Pass1Error = __('Please enter Pasword', 'wpdating');
        $hasError = true;
    } else {
        $txtpassword1 = esc_sql(sanitizeData(trim($_POST['txtpassword1']), 'xss_clean'));
    }
//Check to make sure that the Email is not empty
    if (trim($_POST['txtpassword1']) != trim($_POST['txtpassword2'])) {
        $confirmError = __('Please enter the same password in the two password fields.', 'wpdating');
        $hasError = true;
    } else {
        $txtpassword2 = esc_sql(sanitizeData(trim($_POST['txtpassword2']), 'xss_clean'));
    }


    //If there is no error, then profile updated

    if (!isset($hasError)) {

        if (isset($_POST['txtemailbox'])) {
            $wpdb->query($wpdb->prepare("UPDATE $wpdb->users SET user_email = '%s' WHERE ID = $user_id", $txtemailbox));
        }

        if (isset($_POST['txtpassword1']) && isset($_POST['txtpassword2']) && $_POST['txtpassword1'] == $_POST['txtpassword2']) {
            $errors = $wpdb->query("UPDATE $wpdb->users SET user_pass = '" . wp_hash_password($txtpassword1) . "' WHERE ID = $user_id");
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

<div class="box-border">
    <div class="box-pedding">
        <div class="heading-submenu"><strong><?php echo __('Account Setting', 'wpdating'); ?></strong></div>
        <div class="dsp-form-container">
            <form name="frmuseraccount" method="post" action="" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo __('Username', 'wpdating') ?>:
                        </p>
                        <p class="dspdp-col-sm-6 dsp-sm-6">
                            <input type="text" name="txtusername" class="dspdp-form-control dsp-form-control" value="<?php echo $user_account_details->user_login ?>" disabled="disabled" /> 
                        </p>
                        <span class="dspdp-help-block dspdp-col-sm-3 dsp-sm-3">
                            <?php echo __('Can&rsquo;t Change', 'wpdating') ?>
                        </span>
                    </div>                    
    				<div class="dspdp-form-group dsp-form-group clearfix">
                        <p class="bold-text dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                            <?php echo __('New Password: ', 'wpdating') ?>
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="password" name="txtpassword1" id="txtpassword1" value="" />
                            <?php if (isset($Pass1Error) && $Pass1Error != '') { ?>
                                <span class="error dspdp-text-danger dspdp-help-block dspdp-col-sm-3 dsp-sm-3"><?php echo $Pass1Error; ?></span> 
                            <?php } ?>
                        </p>
                    </div>    				
    				<div class="dspdp-form-group dsp-form-group clearfix">
    				    <p class="bold-text dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label ">
                            <?php echo __('Confirm Password', 'wpdating') ?>:
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="password" name="txtpassword2" value="" id="txtpassword2" onkeyup='checkPass();'/>
                            <?php if (isset($confirmError) && $confirmError != '') { ?>
                                <span class="error dspdp-text-danger dspdp-help-block dspdp-col-sm-3 dsp-sm-3"><?php echo $confirmError; ?></span>              
                            <?php } ?>
                        </p>
                        <span class="error" id='message'></span>
                    </div>    				
    				
                    <div class="dspdp-form-group dsp-form-group clearfix">
    				    <p class="bold-text dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label ">
                            <?php echo __('Email', 'wpdating') ?>:
                        </p>
                        <p class=" dspdp-col-sm-6 dsp-sm-6">
                            <input class="dspdp-form-control dsp-form-control" type="text" name="txtemailbox" value="<?php echo $user_account_details->user_email ?>" />
                            <?php if (isset($EmailError) && $EmailError != '') { ?>
                                <span class="error"><?php echo $EmailError; ?></span> 
                            <?php } ?>
                        </p>
    				</div>
    				
                    <div class="dspdp-form-group dsp-form-group clearfix">
                        <div class="btn-row dspdp-col-sm-offset-3 dspdp-col-sm-6 dsp-sm-6">
                            <input type="submit" name="change_account" id="change_account" value="<?php echo __('Submit', 'wpdating') ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default" />
                        </div>
                    </div>
                </div>
                <?php
    // show cancel membership only user has payment done and he has his recurring profile id
                if ($recurring_profile_res != null) {
                    $recurring_profile_id = $recurring_profile_res->recurring_profile_id;
                    if ($recurring_profile_id) {
                        ?>
                        <div style="padding-top: 32px; float: right; padding-right: 10px;">
                            <a onclick="return confirmAction('<?php echo __('Are you sure you want to cancel the membership', 'wpdating'); ?>?');" href="<?php echo $root_link . "setting/dsp_cancel_membership/"; ?>">
                                <span style="color: red"><?php echo __('CANCEL MY MEMBERSHIP', 'wpdating'); ?></span>	
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php //------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>
<script type="text/javascript">
    function confirmAction(msg)
    {
        //alert('ssd');
        var confirmed = confirm(msg);
        return confirmed;
    }
    function checkPass(){
         var pass  = document.getElementById("txtpassword1").value;
         var rpass  = document.getElementById("txtpassword2").value;
        if(pass != rpass){
            document.getElementById("change_account").disabled = true;
            document.getElementById('message').innerHTML = "Password don't match";
        }else{
            document.getElementById('message').innerHTML = '';
            document.getElementById("change_account").disabled = false;
            message
        }
}
</script>