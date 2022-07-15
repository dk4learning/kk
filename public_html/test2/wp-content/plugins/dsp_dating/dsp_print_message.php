<?php if (isset($check_membership_msg) && $check_membership_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_membership_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php if ($pageurl  == 15 || (isset($addDiv) && $addDiv)){ ?>
    </div>
<?php } ?>
<?php } else if (isset($check_member_trial_msg) && $check_member_trial_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_member_trial_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "NoAccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_member_trial_msg == "NotExist") {
                    if ($access_feature_name != '') {
                        $msg = __('You must create your profile before you can use this feature.', 'wpdating')." ". $access_feature_name;
                    } else {

                        $msg = __('You must create your profile before you can use this feature.', 'wpdating');
                    }
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                } else if ($check_member_trial_msg == "Approved") {

                    $msg = language_code("DSP_PROFILE_APPROVED_MESSAGE");
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_free_email_msg) && $check_free_email_msg != "") {  ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_free_email_msg == "NoAccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_free_email_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_free_email_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_approved_profile_msg) && $check_approved_profile_msg != '') { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_approved_profile_msg == "NoAccess") {
                    $message = __('Your profile has not been approved yet. Please wait for 24 hours or write to the admin.', 'wpdating');
                } else if ($check_approved_profile_msg == "NoExist") {
                    $message = __('No Profile Exists.', 'wpdating');
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
            </div>
        </div>
    </div>
<?php } else if (isset($check_force_profile_msg) && $check_force_profile_msg != "") { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_force_profile_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else
                if ($check_force_profile_msg == "NoAccess") {
                    if (isset($access_feature_name) && $access_feature_name != '') {
                        $msg = __('You must create your profile before you can use this feature.', 'wpdating')." ". $access_feature_name;
                    } else {
                        $msg = __('You must create your profile before you can use this feature.', 'wpdating');
                    }
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link . "edit/" ?>";

                                //loc +="?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                } else if ($check_force_profile_msg == "Approved") {

                    $msg = language_code("DSP_PROFILE_APPROVED_MESSAGE");
                    ?><script type="text/javascript">
                                var message = "<?php echo $msg ?>";
                                alert(message);
                                var loc = "<?php echo $root_link ?>";

                                loc += "?pid=2";

                                window.location.href = loc;
                    </script>
                    <?php
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php if ($pageurl  == 15){ ?>
    </div>
<?php } ?>
<?php } else if ($check_limit_profile_mode->setting_status == 'Y' && isset($check_membership_msg)) { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                if ($check_membership_msg == "NoAccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_membership_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else if (isset($no_of_credits) && $no_of_credits == 0) {
    ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <div align="center" valign="top"><span  style="color:#FF0000;"><?php echo __('You must be a premium member to send an email', 'wpdating');?></span> <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>"><?php echo __('Click here', 'wpdating'); ?></a></div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
                <?php
                    $frnd_userid = isset($_REQUEST['frnd_id']) ? $_REQUEST['frnd_id'] : '';
                    $print_msg = ($user_id == $frnd_userid) ? __('You can&rsquo;t send a message to yourself!', 'wpdating') : __('Only premium member can access this feature, Please upgrade your account', 'wpdating');
                ?>
                <div align="center" valign="top" style="color:#FF0000;"><?php echo $print_msg; ?></div>
                <div align="center" valign="top"><a href="<?php echo $root_link . get_username($frnd_userid) . "/"; ?>" class="dspdp-btn dspdp-btn-info" ><?php echo __('Back to Profile', 'wpdating') ?></a></div>
            </div>
        </div>
    </div>
<?php } ?>
