<?php if (isset($check_membership_msg)) { ?>

    <div >

        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            if ($check_membership_msg == "Expired") {
                $message = DSP_YOUR_PREMIUM_ACCOUNT_HAS_BEEN_EXPIRE_PLEASE_UPGRADE_UR_ACCOUNT;
            } else if ($check_membership_msg == "Onlypremiumaccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            }
            ?>

            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></td>
            </tr>
        </table>

    </div>
<?php } else if (isset($check_member_trial_msg)) { ?>


    <div>
        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            if ($check_member_trial_msg == "Expired") {
                $message = DSP_YOUR_PREMIUM_ACCOUNT_HAS_BEEN_EXPIRE_PLEASE_UPGRADE_UR_ACCOUNT;
            } else if ($check_member_trial_msg == "NoAccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            } else if ($check_member_trial_msg == "Expired") {
                $message = DSP_YOUR_PREMIUM_ACCOUNT_HAS_BEEN_EXPIRE_PLEASE_UPGRADE_UR_ACCOUNT;
            } else if ($check_member_trial_msg == "Onlypremiumaccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            } else if ($check_member_trial_msg == "NotExist") {
                if ($access_feature_name != '') {
                    $msg = DSP_YOU_MUST_CREATE_YOUR_PROFILE_BEFORE_YOU_CAN_USE . $access_feature_name . DSP_FEATURE;
                } else {
                    $msg = DSP_YOU_MUST_CREATE_YOUR_PROFILE_BEFORE_YOU_CAN_USE . DSP_FEATURE;
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

                $msg = DSP_YOUR_PROFILE_HAS_NOT_BEEN_APPROVED;
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

            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></td>
            </tr>
        </table>
    </div>

<?php } else if (isset($check_free_email_msg)) { ?>
    <div >
        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            if ($check_free_email_msg == "NoAccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            }
            if ($check_free_email_msg == "Onlypremiumaccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            }
            if ($check_free_email_msg == "Expired") {
                $message = DSP_YOUR_PREMIUM_ACCOUNT_HAS_BEEN_EXPIRE_PLEASE_UPGRADE_UR_ACCOUNT;
            }
            ?>

            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></td>
            </tr>
        </table>
    </div>

<?php } else if (isset($check_approved_profile_msg)) { ?>
    <div>
        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            if ($check_approved_profile_msg == "NoAccess") {
                $message = __('Your profile has not been approved yet. Please wait for 24 hours or write to the admin.', 'wpdating');
            } else if ($check_approved_profile_msg == "NoExist") {
                $message = __('No Profile Exists.', 'wpdating');
            }
            ?>
            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></td>
            </tr>
        </table>

    </div>
<?php } else if (isset($check_force_profile_mode)) { ?>

    <div>
        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            if ($check_force_profile_msg == "Expired") {
                $message = DSP_YOUR_PREMIUM_ACCOUNT_HAS_BEEN_EXPIRE_PLEASE_UPGRADE_UR_ACCOUNT;
            } else if ($check_force_profile_msg == "Onlypremiumaccess") {
                $message = DSP_ONLY_PREMIUM_MEMBER_CAN_ACCESS_THIS_FEATURE_PLZ_VISIT_DESKTOP_VERSION_AND_PURCHASE_MEMBERSHIP;
            } else
            if ($check_force_profile_msg == "NoAccess") {
                if ($access_feature_name != '') {
                    $msg = DSP_YOU_MUST_CREATE_YOUR_PROFILE_BEFORE_YOU_CAN_USE . $access_feature_name . DSP_FEATURE;
                } else {
                    $msg = DSP_YOU_MUST_CREATE_YOUR_PROFILE_BEFORE_YOU_CAN_USE . DSP_FEATURE;
                }
                ?><script type="text/javascript">
                            var message = "<?php echo $msg ?>";
                            alert(message);
                            var loc = "<?php echo $root_link ?>";

                            loc += "?pid=2";

                            window.location.href = loc;
                </script>
                <?php
            } else if ($check_force_profile_msg == "Approved") {

                $msg = DSP_YOUR_PROFILE_HAS_NOT_BEEN_APPROVED;
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

            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $message ?></td>
            </tr>
        </table>
    </div>

<?php } else { ?>
    <div>
        <table width="100%" border=0 cellpadding="5" cellspacing="0" valign="top">
            <?php
            $frnd_userid = $_REQUEST['frnd_id'];
            if ($user_id == $frnd_userid) {
                $print_msg = __('You can&rsquo;t send a message to yourself!', 'wpdating');
            } else {
                $print_msg = __('You can&rsquo;t send message, Member not added in your friend list!', 'wpdating');
            }
            ?>
            <tr>  
                <td align="center" valign="top" style="color:#FF0000;"><?php echo $print_msg; ?></td>
            </tr>
            <tr>  
                <td align="center" valign="top"><a href="<?php
                    echo add_query_arg(array(
                        'pid' => 3, 'mem_id' => $frnd_userid), $root_link);
                    ?>"><?php echo __('Back to Profile', 'wpdating') ?></a></td>
            </tr>
        </table>
    </div>

<?php } ?>