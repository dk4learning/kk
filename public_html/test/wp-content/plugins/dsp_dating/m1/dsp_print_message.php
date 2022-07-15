<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?>
    <span class="ui-title" />
   <?php include_once("page_home.php");?>
</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <div class="error-message">

            <?php
            if (isset($check_membership_msg) && $check_membership_msg != "") {

                if ($check_membership_msg == "Expired") {
                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_membership_msg == "Onlypremiumaccess") {
                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>

                <span  class="MenuiPhone" style="color:#FF0000;"><?php echo $message ?></span>
                <span style="color:#FF0000;"><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>


                <div align="center" valign="top" class="MenuiPhone">
                    <a href="dsp_upgrade.html"><?php echo __('Upgrade Here.', 'wpdating'); ?></a>
                </div>



            <?php } else if (isset($check_member_trial_msg) && $check_member_trial_msg != "") { ?>

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

                        $msg = "You must create your profile before you can use '" . $access_feature_name . "' features";
                    } else {

                        $msg = "You must create your profile before you can use this features";
                    }
                } else if ($check_member_trial_msg == "Approved") {



                    $msg = "Your profile has not been Approved";
                }
                ?>


                <span class="MenuiPhone"><?php echo $message ?></span>
                <span  ><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>

                <a  class="MenuiPhone" href="dsp_upgrade.html"><?php echo __('Upgrade Here.', 'wpdating'); ?></a>


            <?php } else if (isset($check_free_email_msg) && $check_free_email_msg != "") { ?>


                <?php
                if ($check_free_email_msg == "NoAccess") {

                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_free_email_msg == "Onlypremiumaccess") {

                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_free_email_msg == "Expired") {

                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>


                <span  class="MenuiPhone" style="color:#FF0000;"><?php echo $message ?></span>
                <span  style="color:#FF0000;"><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>


                <a class="MenuiPhone" href="dsp_upgrade.html"><?php echo __('Upgrade Here.', 'wpdating'); ?></a>



            <?php } else if (isset($check_approved_profile_msg) && $check_approved_profile_msg != '') { ?>


                <?php
                if ($check_approved_profile_msg == "NoAccess") {

                    $message = __('Your profile has not been approved yet. Please wait for 24 hours or write to the admin.', 'wpdating');
                } else if ($check_approved_profile_msg == "NoExist") {

                    $message = __('No Profile Exists.', 'wpdating');
                }
                ?>


                <span class="MenuiPhone"  style="color:#FF0000;"><?php echo $message ?></span>
                <span   style="color:#FF0000;"><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>



            <?php } else if ($check_force_profile_mode != "") { ?>


                <?php
                if ($check_force_profile_msg == "Expired") {

                    $msg = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else if ($check_force_profile_msg == "Onlypremiumaccess") {

                    $msg = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } else

                if ($check_force_profile_msg == "NoAccess") {

                    if (isset($access_feature_name) && $access_feature_name != '') {

                        $msg = "You must create your profile before you can use '" . $access_feature_name . "' features";
                    } else {

                        $msg = "You must create your profile before you can use this features";
                    }
                } else if ($check_force_profile_msg == "Approved") {



                    $msg = "Your profile has not been Approved";
                }
                ?>


                <span class="" style="color:#FF0000;"><?php echo $msg ?></span>
                <span  style="color:#FF0000;"><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>



                <a class="" href="dsp_upgrade.html"><?php echo __('Upgrade Here.', 'wpdating'); ?></a>





            <?php } else if ($check_limit_profile_mode != "") { ?>

                <?php
                if ($check_membership_msg == "NoAccess") {

                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                } if ($check_membership_msg == "Onlypremiumaccess") {

                    $message = __('Only premium member can access this feature, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }if ($check_membership_msg == "Expired") {

                    $message = __('Your Premimum Account has been expired, Please upgrade your account', 'wpdating') . " " . __('to:', 'wpdating') . "&nbsp;" . $access_feature_name . ".";
                }
                ?>



                <span class"MenuiPhone" style="color:#FF0000;"><?php echo $message ?></span>
                <span style="color:#FF0000;"><?php echo language_code('DSP_IPHONE_PREMIUM_MEMBER_EXPIRED_MESSAGE') ?></span>


                <a class="MenuiPhone" href="dsp_upgrade.html"><?php echo __('Upgrade Here.', 'wpdating'); ?></a>

            <?php } else { ?>




                <?php
                $frnd_userid = $_REQUEST['frnd_id'];



                if ($user_id == $frnd_userid) {



                    $print_msg = __('You can&rsquo;t send a message to yourself!', 'wpdating');
                } else {



                    $print_msg = __('You can&rsquo;t send message, Member not added in your friend list!', 'wpdating');
                }
                ?>


                <span style="color:#FF0000;"><?php echo $print_msg; ?></span>



            <?php } ?>
        </div>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>