<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $licenseModuleSwitch, $isValidLicense;
$update_msg = isset($_REQUEST['updated']) ? $_REQUEST['updated'] : '';
$errors     = isset($_SESSION['errors']) ? $_SESSION['errors'] : '';
// Import tables if not imported before
if (isset($_POST['import']) && file_exists(WP_DSP_ABSPATH . 'files/dspSQL.php')) {
    include_once(WP_DSP_ABSPATH . 'files/dspSQL.php');
    rename(WP_DSP_ABSPATH . 'files/dspSQL.php', WP_DSP_ABSPATH . 'files/dspSQL_old.php');
}
// In this file we checks Admin General Settings
include_once(WP_DSP_ABSPATH . "general_settings.php");
?>
<script type="text/javascript">
    function showfreetial(free_mode) {
        //var free_mode;
        var freetrialboxdiv = jQuery(".freetrialbox");
        var enableemailboxdiv = jQuery(".enableemailbox");
        var gatewaysemailboxdiv = jQuery("#gatewaysmodebox");
        var limitprofilebox = jQuery(".limitprofilebox");
        var creditmodebox = jQuery("#creditmodebox");
        var freemember = jQuery("#freemember");
        if (free_mode == 'N') {
            freetrialboxdiv.show();
            //enableemailboxdiv.show();
            gatewaysemailboxdiv.show();
            limitprofilebox.show();
            creditmodebox.show();
            freemember.hide();
        } else {
            freetrialboxdiv.hide();
            //enableemailboxdiv.hide();
            gatewaysemailboxdiv.hide();
            limitprofilebox.hide();
            creditmodebox.hide();
            freemember.show();

            document.getElementById("limitprofile").value = 'N';
        }
    }

    function showtraildaysgender(free_trial_mode) {
        //var free_trial_mode;
        var freetrialgenderboxdiv = jQuery(".showdaysgender");
        if (free_trial_mode == 'Y') {
            freetrialgenderboxdiv.show();
        } else {
            freetrialgenderboxdiv.hide();
        }
    }

    function showFacebookApiSetting(facebook_status) {
        var facebookSecretKey = jQuery(".facebooksecretkey");
        var facebookApiKey = jQuery(".facebookapikey");
        if (facebook_status == 'Y') {
            facebookSecretKey.show();
            facebookApiKey.show();
        } else {
            facebookSecretKey.hide();
            facebookApiKey.hide();
        }
    }

    function showGoogleApiSetting(googleStatus) {
        var facebookSecretKey = jQuery(".googlesecretkey");
        var facebookApiKey = jQuery(".googleapikey");
        if (googleStatus == 'Y') {
            facebookSecretKey.show();
            facebookApiKey.show();
        } else {
            facebookSecretKey.hide();
            facebookApiKey.hide();
        }
    }

    function showemailgender(free_enable_email) {
        //var free_enable_email;
        var enablegenderboxdiv = jQuery("#enablegender");
        if (free_enable_email == 'Y') {
            enablegenderboxdiv.show();
        } else {
            enablegenderboxdiv.hide();
        }
    }

    function showprofiletxt(limit_profile) {
        //var limit_profile;
        var showprofileboxdiv = jQuery("#showprofile");
        if (limit_profile == 'Y') {
            showprofileboxdiv.show();
        } else {
            showprofileboxdiv.hide();
        }
    }

    function redirecturltxt(register_page_redirect) {
        //var register_page_redirect;
        var showredirectboxdiv = jQuery("#redirecturl");
        if (register_page_redirect == 'Y') {
            showredirectboxdiv.show();
        } else {
            showredirectboxdiv.hide();
        }
    }

    function afterregisterredirecturltxt(after_register_page_redirect) {
        //var after_register_page_redirect;
        var showredirectboxdiv = jQuery("#afterregisterredirecturl");
        /* var urlTextBox = document.getElementById("afterRegister");
		 urlTextBox.value = '';*/
        if (after_register_page_redirect == 'Y') {
            showredirectboxdiv.show();
        } else {
            showredirectboxdiv.hide();
        }
    }

    function guestprofiletxt(guest_limit_profile) {
        //var guest_limit_profile;
        var guestprofileboxdiv = jQuery("#guestprofile");
        if (guest_limit_profile == 'Y') {
            guestprofileboxdiv.show();
        } else {
            guestprofileboxdiv.hide();
        }
    }

    function onlineMemberstxt(online_members) {
        //var online_members;
        var onlineMembersdiv = jQuery("#onlineMembers");
        if (online_members == 'Y') {
            onlineMembersdiv.show();
        } else {
            onlineMembersdiv.hide();
        }
    }

    function virtualgiftsmax(virtual_gifts) {
        //var virtual_gifts;
        var virtual_gifts_maxdiv = jQuery("#virtualgiftsbox");
        if (virtual_gifts == 'Y') {
            virtual_gifts_maxdiv.show();
        } else {
            virtual_gifts_maxdiv.hide();
        }
    }

    function termsurltxt(terms_page) {
        //var terms_page;
        var showtermsboxdiv = jQuery("#termsurl");
        if (terms_page == 'Y') {
            showtermsboxdiv.show();
        } else {
            showtermsboxdiv.hide();
        }
    }

    function notificationbox(notification) {
        var notificationpostitionbox = jQuery("#notificationpostitionbox");
        var notificationtimebox = jQuery("#notificationtimebox");
        if (notification == 'Y') {
            notificationtimebox.show();
            notificationpostitionbox.show();
        } else {
            notificationtimebox.hide();
            notificationpostitionbox.hide();
        }
    }

    dsp = jQuery.noConflict();
    dsp(document).ready(function () {
        dsp(".randomOnlineMembers").blur(function () {
            var no_random_members = dsp(".randomOnlineMembers").val();
            if (no_random_members > 10) {
                alert('<?php echo __('Please enter less than 10 for random online members', 'wpdating'); ?>');
            }
        })
    });


</script>
<?php
if (empty($errors)) {
    if ($update_msg == 'true') {
        ?>
        <div class="wrap">
            <div class="updated">
                <p><?php echo $_SESSION['message']; ?></p>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="error">';
    foreach ($errors as $error) {
        echo '<span >' . $error . '</span>';
    }
    echo '</div>';
}
?>
<form name="frmgeneralsettings" method="post" action="<?php echo add_query_arg(array(
    'pid'  => 'update_general_settings',
    'mode' => 'update'
), $settings_root_link); ?>">

    <div class="dsp_settings_left">
        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Search and Chat', 'wpdating'); ?></span></h3>
            <table class="form-table short-input-fields">
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Skype Mode', 'wpdating'); ?></label></th>
                    <td>
                        <select name="skype_mode">
                            <?php
                            if ($check_skype_mode->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description">&nbsp;<?php _e(__('Select ON to activate Skype in the Profiles.', 'wpdating')); ?></span>
                    </td>
                </tr>

                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Zip Codes', 'wpdating'); ?></label></th>
                    <td><select name="zipcode_mode">
                            <?php
                            if ($check_zipcode_mode->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description">&nbsp;<?php _e(__('Select ON to activate the Zip Code field', 'wpdating')) ?></span>
                    </td>
                </tr>

                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Near Me', 'wpdating'); ?></label></th>
                    <td>
                        <select name="nearme">
                            <?php
                            if ($check_near_me->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description">&nbsp;<?php _e(__('Select ON to activate the Near Me field', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Instant Messenger', 'wpdating'); ?></label></th>
                    <td>
                        <select name="userplane_instant_messenger">
                            <?php
                            if ($check_userplane_instant_messenger_mode->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to activate instant Messenger', 'wpdating')) ?></span>
                    </td>
                </tr>

                <tr class="form-field">
                    <th scope="row"><label><?php echo __('IM Recipient', 'wpdating'); ?></label></th>
                    <td>
                        <select name="recipient_premium_member">
                            <?php
                            if ($check_recipient_premium_member_mode->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate IM. Recipient must be premium member', 'wpdating')) ?></span>
                    </td>
                </tr>

                <!------------------------search_result page-------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Search Results', 'wpdating'); ?></label></th>
                    <td>
                        <input name="search_result" type="text"
                               value="<?php echo $check_search_result->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Select count of Search Result.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------search_result page-------------------------------------  -->

                <!------------------------front_page_result page-------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Front Page Result', 'wpdating'); ?></label></th>
                    <td>
                        <input name="front_page_result" type="text"
                               value="<?php echo $check_front_page_result->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Select count of front page Result.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!------------------------front_page_result page-------------------------------------  -->

                <!------------------------chat mode page-------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Group Chat Mode', 'wpdating'); ?></label></th>
                    <td>
                        <select name="chat_mode">
                            <?php if ($check_chat_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate the Chat mode.', 'wpdating')) ?></span>
                    </td>
                </tr>

                <!------------------------chat_one_mode page-------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('One on One Chat', 'wpdating'); ?></label></th>
                    <td>
                        <select name="chat_one_mode">
                            <?php if ($check_chat_one_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate the One on One Chat mode.', 'wpdating')) ?></span>
                    </td>
                </tr>
                

                            <tr class="form-field">
                    <th scope="row"><label><?php echo __( 'Stealth Mode','wpdating' ); ?></label></th>
                    <td>
                        <select name="stealth_tab">
                        <?php
                            if ( $check_stealth_option->setting_status == 'Y' )

                                { ?> <option value="Y" selected="selected"><?php echo __( 'On','wpdating' ); ?></option> <option value="N"><?php echo __( 'Off','wpdating' ); ?></option> <?php }

                            else

                                { ?> <option value="Y"><?php echo __( 'On','wpdating' ); ?></option> <option value="N" selected="selected"><?php echo __( 'Off','wpdating' ); ?></option> <?php }

                            ?>
                        </select>
                    </td>
                    <td>
                        <span
                            class="description"> <?php echo __( 'Select ON to enable offline mode on your site','wpdating' ); ?>
                        </span>
                    </td>
            </tr>

                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Import Zip Codes', 'wpdating'); ?></label></th>
                    <?php $dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE; ?>
                    <td class="link-inputs">
                        <?php
                        $count_zipcodes_entries = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%USA%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=usa_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="USA"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%UK%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=uk_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="UK"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%dutch%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=dutch_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="Dutch"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%french%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=french_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="French"/></a>
                        <?php } ?>
                    </td>
                    <td class="link-inputs">
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%Australia%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=australian_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="Australian"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%Canada%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=canadian_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="Canadian"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%danish%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=danish_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="Danish"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%spanish%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=spanish_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="Spanish"/></a>
                        <?php } ?>
                        <?php
                        $count_zipcodes_entries1 = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_zipcode_table where country like '%german%'");
                        //echo $count_zipcodes_entries;
                        if ($count_zipcodes_entries1 <= 0) {
                            ?>
                            <a href="admin.php?page=dsp-admin-sub-page1&pid=import_zipcodes&file=german_zipcodes"
                               title="import zipcodes" style="text-decoration:none;"><input name="zipcode" type="button"
                                                                                            value="German"/></a>
                        <?php } ?>
                    </td>
                </tr>

            </table>
        </div>

        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Customize', 'wpdating'); ?></span></h3>
            <table class="form-table short-input-fields">
                <!------------------------pagination_color page-------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Pagination Color', 'wpdating'); ?></label></th>
                    <td>
                        <input name="pagination_color" type="text"
                               value="<?php echo $check_pagination_color->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Enter 6 digit color code for Pagination.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!------------------------pagination_color page------------------------------>

                <!------------------------tab_color page------------------------------------->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Tab Color', 'wpdating'); ?></label></th>
                    <td>
                        <input name="tab_color" type="text" value="<?php echo $check_tab_color->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Enter 6 digit color code for Tabs.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------tab_color page------------------------------------->

                <!------------------------non_active_tab_color page-------------------------->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Non-Active Tab Color', 'wpdating'); ?></label></th>
                    <td>
                        <input name="non_active_tab_color" type="text"
                               value="<?php echo $check_non_active_tab_color->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Enter 6 digit color code for Non-Active Tabs.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!------------------------non_active_tab_color page--------------------------->

                <!------------------------button_color page----------------------------------->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Button Color', 'wpdating'); ?></label></th>
                    <td>
                        <input name="button_color" type="text"
                               value="<?php echo $check_button_color->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Enter 6 digit color code for Buttons.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------button_color page----------------------------------->

                <!------------------------Title_color page------------------------------------>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Title Color', 'wpdating'); ?></label></th>
                    <td>
                        <input name="title_color" type="text" value="<?php echo $check_title_color->setting_value ?>"/>
                    </td>
                    <td><span class="description"><?php _e(__('Enter 6 digit color code for Titles.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------button_color page-------------------------------------  -->
            </table>
        </div>

        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Accounts and Payment', 'wpdating'); ?></span></h3>
            <table class="form-table short-input-fields">
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Free Mode', 'wpdating'); ?></label></th>
                    <td>
                        <select name="free_mode" onChange="showfreetial(this.value);">
                            <?php if ($check_free_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description">&nbsp;<?php _e(__('Select ON to activate the site in Free Mode.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field"
                    id='freemember'<?php if ($check_free_mode->setting_status == 'Y') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Gender', 'wpdating'); ?></label></th>
                    <td>
                        <?php $free_member = $check_free_member_mode->setting_value; ?>
                        <select name="free_member">
                            <option
                                    value="1" <?php if ($free_member == 1) { ?> selected="selected"<?php } ?>><?php echo __('Male', 'wpdating') ?></option>
                            <option
                                    value="2" <?php if ($free_member == 2) { ?> selected="selected"<?php } ?>><?php echo __('Female', 'wpdating') ?></option>
                            <option
                                    value="3" <?php if ($free_member == 3) { ?> selected="selected"<?php } ?>><?php echo __('Both', 'wpdating') ?></option>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select which type of member get to use this site for free', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!------------------------GUEST_LIMIT_PROFILE_VIEW-------------------------------------  -->

                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Guest Limit Profile', 'wpdating'); ?></label>
                    </th>
                    <td>
                        <select name="guest_limit_profile" onChange="guestprofiletxt(this.value);">
                            <?php if ($check_guest_limit_profile_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to activate the Guest Limit Profile View mode.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field"
                    id="guestprofile" <?php if ($check_guest_limit_profile_mode->setting_status == 'Y') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Number of profiles', 'wpdating'); ?></label></th>
                    <td>
                        <input name="gno_of_profiles" type="text"
                               value="<?php echo $check_guest_limit_profile_mode->setting_value ?>"/>
                    </td>
                    <td align="left"><span class="description">&nbsp;</span></td>
                </tr>

                <!------------------------ 	Limit Profile View-------------------------------------  -->

                <tr class="form-field limitprofilebox" <?php if ($check_free_mode->setting_status == 'N') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Limit Profile View', 'wpdating'); ?></label></th>
                    <td>
                        <select id="limitprofile" name="limit_profile" onChange="showprofiletxt(this.value);">
                            <?php if ($check_limit_profile_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to activate the Limit Profile View mode.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr id="showprofile"
                    class="form-field limitprofilebox" <?php if (($check_free_mode->setting_status == 'N') && ($check_limit_profile_mode->setting_status == 'Y')) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?> >
                    <th scope="row"><label><?php echo __('Number of profiles', 'wpdating'); ?></label></th>
                    <td>
                        <input name="no_of_profiles" type="text"
                               value="<?php echo $check_limit_profile_mode->setting_value ?>"/>
                    </td>
                    <td><span class="description">&nbsp;</span></td>
                </tr>

                <!--...................Free Trail Div.....................-->
                <tr class="form-field freetrialbox" <?php if ($check_free_mode->setting_status == 'N') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Free Trial', 'wpdating'); ?></label></th>
                    <td>
                        <select name="free_trail_mode" onChange="showtraildaysgender(this.value);">
                            <?php if ($check_free_trail_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to activate Free Trial', 'wpdating')) ?></span>
                    </td>
                </tr>
                <?php
                //if($check_free_trail_mode->setting_status=='Y') {
                $gender = $check_free_trail_gender_mode->setting_value;
                ?>

                <tr class="form-field freetrialbox showdaysgender" <?php if (($check_free_mode->setting_status == 'N') && ($check_free_trail_mode->setting_status == 'Y')) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?> >
                    <th scope="row"><label><?php echo __('Gender', 'wpdating'); ?></label></th>
                    <td>
                        <select name="free_trail_gender">
                            <option
                                    value="1" <?php if ($gender == 1) { ?> selected="selected"<?php } ?>><?php echo __('Male', 'wpdating') ?></option>
                            <option
                                    value="2" <?php if ($gender == 2) { ?> selected="selected"<?php } ?>><?php echo __('Female', 'wpdating') ?></option>
                            <option
                                    value="3" <?php if ($gender == 3) { ?> selected="selected"<?php } ?>><?php echo __('Both', 'wpdating') ?></option>
                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Set gender for Free Trial, Male or Female or Both', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field freetrialbox showdaysgender" <?php if (($check_free_mode->setting_status == 'N') && ($check_free_trail_mode->setting_status == 'Y')) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?> >
                    <th scope="row"><label><?php echo __('Days', 'wpdating'); ?></label></th>
                    <td><input name="free_trail_days_limit" type="text"
                               value="<?php echo $check_free_trail_mode->setting_value ?>"/></td>
                    <td>&nbsp;</td>
                </tr>
                <!--...................End Free Trail Div.....................-->
                <!------------------------gateways-------------------------------------  -->
                <tr class="form-field"
                    id="gatewaysmodebox"<?php if ($check_free_mode->setting_status == 'N') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Gateways', 'wpdating'); ?></label></th>
                    <td>
                        <select name="gateways_mode">
                            <?php if ($check_gateways_mode->setting_status == 'Y') { ?>

                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>

                            <?php } else { ?>

                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>

                            <?php } ?>

                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate the Gateways mode.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------ End gateways-------------------------------------  -->

                <!------------------------credit-------------------------------------  -->
                <tr class="form-field"
                    id="creditmodebox"<?php if ($check_free_mode->setting_status == 'N') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Credits', 'wpdating'); ?></label></th>
                    <td>
                        <select name="credit">
                            <?php if ($check_credit_mode->setting_status == 'Y') { ?>

                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>

                            <?php } else { ?>

                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>

                            <?php } ?>

                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate the Credits mode.', 'wpdating')) ?></span></td>
                </tr>
                <!------------------------ End credit-------------------------------------  -->

                <!-- Discount Coupon Code setting -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Discount Codes', 'wpdating'); ?></label></th>
                    <td>
                        <select name="discount_code">
                            <?php
                            if ($check_discount_code->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span class="description">&nbsp;<?php _e(__('Select ON to enable Discount coupan code option', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!-- End of Discount Coupon Code setting -->

                <tr class="form-field enableemailbox">
                    <th scope="row"><label><?php echo __('Enable Message', 'wpdating'); ?></label></th>
                    <td>
                        <select name="free_email_access" onChange="showemailgender(this.value);">
                            <?php if ($check_free_email_access_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to enable free email access. Choose gender below.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <?php
                //if($check_free_trail_mode->setting_status=='Y') {
                $gender = $check_free_email_access_gender_mode->setting_value;
                ?>
                <tr id='enablegender'
                    class="form-field enableemailbox" <?php if (($check_free_mode->setting_status == 'N') && ($check_free_email_access_mode->setting_status == 'Y')) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?> >
                    <th scope="row"><label><?php echo __('Gender', 'wpdating'); ?></label></th>
                    <td>
                        <select name="free_email_access_gender">
                            <option
                                    value="1" <?php if ($gender == 1) { ?> selected="selected"<?php } ?>><?php echo __('Male', 'wpdating') ?></option>
                            <option
                                    value="2" <?php if ($gender == 2) { ?> selected="selected"<?php } ?>><?php echo __('Female', 'wpdating') ?></option>
                            <option
                                    value="3" <?php if ($gender == 3) { ?> selected="selected"<?php } ?>><?php echo __('Both', 'wpdating') ?></option>
                            <option
                                    value="4" <?php if ($gender == 4) { ?> selected="selected"<?php } ?>><?php echo __('None', 'wpdating') ?></option>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Set Male or Female to allow email access to specific gender', 'wpdating')) ?></span>
                    </td>
                </tr>
                <?php //}     ?>

                <!---------------------------virtual gifts------------------------------  -->
                <tr>
                    <th scope="row"><label><?php echo __('Virtual Gifts', 'wpdating'); ?></label></th>
                    <td>
                        <select name="virtual_gifts" onChange="virtualgiftsmax(this.value);">
                            <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span class="description"><?php _e(__('Select ON to activate the Virtual Gifts.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr id="virtualgiftsbox"<?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Max Gifts', 'wpdating'); ?></label></th>
                    <td><input name="virtual_gifts_max" type="text"
                               value="<?php echo $check_virtual_gifts_mode->setting_value ?>"/></td>
                    <td align="left"><span class="description">&nbsp;</span></td>
                </tr>


            </table>
        </div>

        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Approval', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Authorize Profiles', 'wpdating'); ?></label></th>
                    <td><select name="authorize_profile">
                            <?php
                            if ($check_approve_profile_status->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select ON to automatically approve the members profiles.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Authorize Photos', 'wpdating'); ?></label></th>
                    <td>
                        <select name="authorize_photos">
                            <?php
                            if ($check_approve_photos_status->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select ON to automatically approve photos uploaded by your members', 'wpdating')) ?></span>
                    </td>
                </tr>

                <?php // ***********************************  Authorize Audio & Videos ********************************************** //     ?>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Authorize Audios', 'wpdating'); ?></label></th>
                    <td>
                        <select name="authorize_audios">
                            <?php
                            if ($check_approve_audios_status->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select ON to automatically approve the Audios uploaded by your members.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Authorize Videos', 'wpdating'); ?></label></th>
                    <td>
                        <select name="authorize_videos">
                            <?php
                            if ($check_approve_videos_status->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select ON to automatically approve Videos uploaded by members.', 'wpdating')) ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Authorize Comments', 'wpdating'); ?></label></th>
                    <td>
                        <select name="authorize_comments">
                            <?php
                            if ($check_approve_comments_status->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>&nbsp;<span
                                class="description"><?php _e(__('Select ON to automatically approve comments', 'wpdating')) ?></span>
                    </td>
                </tr>
                <?php // ***********************************  Authorize Audio & Videos  & comments ********************************************** //     ?>
            </table>
        </div>


        <div class="dsp_settings_box">
            <!-- Home page settings -->
            <h3 class="hndle"><span><?php echo __('Home page Elements', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <?php
                $homeElements = array(
                    'N' => __('New Members', 'wpdating'),
                    'O' => __('Online Members', 'wpdating'),
                    'H' => __('Happy Stories', 'wpdating'),
                    'L' => __('Latest Blog', 'wpdating'),
                    'F' => __('Featured Members', 'wpdating')
                );
                $values       = explode(',', $member_elements_status->setting_value);
                $i            = 0;
                ?>
                <tbody>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Enable Home page elements', 'wpdating'); ?></label></th>
                    <td>
                        <?php foreach ($homeElements as $key => $elements): ?>
                            <h4>
                                <input type="checkbox" name="home[<?php echo $i; ?>]" value="<?php echo $key; ?>"
                                    <?php
                                    if (in_array($key, $values)) {
                                        echo 'checked="checked"';
                                    }
                                    ?> />
                                <span><?php echo $elements; ?></span>
                            </h4>
                            <?php $i++; endforeach; ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <td></td>
                    <td>
						<span
                                class="description"><?php echo __('To enable elements of home page elements. Please check the checkbox', 'wpdating'); ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Display options', 'wpdating'); ?></label></th>
                    <td>
                        <select name="display_options">
                            <?php if ($check_member_not_logged_display_options->setting_value == 'to') : //tab only    ?>
                                <option value="to"
                                        selected="selected"><?php echo __('Tab only', 'wpdating'); ?></option>
                                <option value="mu"><?php echo __('Member list up, Tab down', 'wpdating'); ?></option>
                                <option value="tu"><?php echo __('Tab up, Member list down', 'wpdating'); ?></option>

                            <?php elseif ($check_member_not_logged_display_options->setting_value == 'tu') : //tabs_up_members_down   ?>
                                <option value="to"><?php echo __('Tab only', 'wpdating'); ?></option>
                                <option value="mu"><?php echo __('Member list up, Tab down', 'wpdating'); ?></option>
                                <option value="tu"
                                        selected="selected"><?php echo __('Tab up, Member list down', 'wpdating'); ?></option>
                            <?php else : //members_up_tabs_down   ?>
                                <option value="to"><?php echo __('Tab only', 'wpdating'); ?></option>
                                <option value="mu"
                                        selected="selected"><?php echo __('Member list up, Tab down', 'wpdating'); ?></option>
                                <option value="tu"><?php echo __('Tab up, Member list down', 'wpdating'); ?></option>
                            <?php endif; ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <td></td>
                    <td><span
                                class="description">&nbsp;<?php echo __('Select which layouts to show in members not loggedin page', 'wpdating'); ?></span>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row">
                        <label><?php echo __('Enable Registration Field', 'wpdating'); ?></label>
                    </th>
                    <td>
                        <select name="register_form_first_last_name_field">
                            <?php if ($register_form_setting->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <td></td>
                    <td><span
                                class="description"><?php _e(__('Select ON to enable firstname & lastname field in Registration form', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!-- *************** End: Display Options (for "/members" request when user is not logged in) ********************************************** -->
                </tbody>
            </table>
            <!-- Home page settings -->
        </div>

        <!-- Google  api settings for recaptcha -->
        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Google Recaptcha Setting', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <tbody>
                <tr class="form-field googlesecretkey" <?php if ($check_recaptcha_mode->setting_status == 'Y') { ?><?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Site Key', 'wpdating'); ?></label></th>
                    <td><input type="text" name="google_api_key"
                               value="<?php echo $check_google_app_id->setting_value; ?>"></td>
                </tr>
                <tr class="form-field googlesecretkey">
                    <td></td>
                    <td><span class="description"><?php echo __('Enter your Google Site Key here', 'wpdating'); ?></span></td>
                </tr>
                <tr class="form-field googleapikey" <?php if ($check_recaptcha_mode->setting_status == 'Y') { ?><?php } else { ?> style="display:none;" <?php } ?>>
                    <th scope="row"><label><?php echo __('Secret Key', 'wpdating'); ?></label></th>
                    <td><input type="text" name="google_secret_key"
                               value="<?php echo $check_google_secret_key->setting_value; ?>"></td>
                </tr>
                <tr class="form-field googleapikey">
                    <td></td>
                    <td><span class="description"><?php echo __('Enter your Google secret key here.', 'wpdating'); ?></span>
                    </td>
                </tr>
                <!-- Recapcha option -->
                <tr>
                    <th scope="row"><label><?php echo __('Enable Recapcha', 'wpdating'); ?></label></th>
                    <td>
                        <select name="recaptcha_option" onChange="showGoogleApiSetting(this.value);">
                            <?php if ($check_recaptcha_mode->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <td></td>
                    <td><span class="description"><?php _e(__('Select ON to enable Recaptcha', 'wpdating')) ?> <a
                                    href="javascript:"
                                    onclick="window.open('http://www.wpdating.com/2015/02/11/site-key-secret-key-recaptcha-your-domain/');"
                                    target="_blank">   <?php echo __('Click Here To Get API Key', 'wpdating'); ?></a></span></td>
                </tr>
                <!-- End of Recapcha option -->
                </tbody>
            </table>
        </div>
        <!-- Google  api settings for recaptcha -->

        <!--		Google API key-->
        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Google Api Key', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Google Api Key', 'wpdating'); ?></label></th>
                    <td><input type="text" name="google_api_key_zip"
                               value="<?php echo $google_api_key_zip->setting_value ?>"/>
                    </td>
                    <td><span class="description"><a
                                    href="https://www.latecnosfera.com/2016/06/google-maps-api-error-missing-keymap-error-solved.html"><?php _e(__('Click Here To Get API Key', 'wpdating')) ?></a></span>
                    </td>
                </tr>
            </table>
        </div>
        <!--		Google API key-->


    </div>

    <div class="dsp_settings_right">

        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Activation', 'wpdating'); ?></span></h3>
            <table class="form-table short-input-fields">
                <!-- -----------------Email notification to admin---------------------------------- -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Register Email To Admin', 'wpdating'); ?></label></th>
                    <td>
                        <select name="email_admin">
                            <?php
                            if ($check_email_admin->setting_status == 'Y') {
                                ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
						<span class="description">&nbsp;<?php echo __( 'Select ON to Send Email to Admin after New User Registration','wpdating' ); ?></span>
					</td>
				</tr>
				<!-- --------------------------- end of email notification ------------------------ -->

				<!------------------------Male-------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Male','wpdating' ); ?></label></th>
					<td><select name="male">
							<?php if ( $check_male_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the male mode.','wpdating' ) ) ?></span></td>
				</tr>

				<!------------------------Female-------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Female','wpdating' ); ?></label></th>
					<td><select name="female">
							<?php if ( $check_female_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the female mode.','wpdating' ) ) ?></span></td>
				</tr>

				<!------------------------Couples-------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Couples','wpdating' ); ?></label></th>
					<td>
						<select name="couples">
							<?php if ( $check_couples_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the Couples Mode.','wpdating' ) ) ?></span></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Force Profile','wpdating' ); ?></label></th>
					<td>
						<select name="force_profile">
							<?php if ( $check_force_profile_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td>&nbsp;<span
							class="description"><?php _e( __( 'Select ON to force members to create a profile before searching.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Force Photo','wpdating' ); ?></label></th>
					<td>
						<select name="force_photo">
							<?php if ( $check_force_photo_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td>&nbsp;<span
							class="description"><?php _e( __( 'Select ON to upload a photo with their profile.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<!------------------------Register Redirect-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Register Redirec','wpdating' ); ?></label></th>
					<td>
						<select name="register_page_redirect" onChange="redirecturltxt(this.value);">
							<?php if ( $check_register_page_redirect_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description"><?php _e( __( 'Select ON to activate the Register Redirect mode.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field"
				    id="redirecturl" <?php if ( $check_register_page_redirect_mode->setting_status == 'Y' ) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo __( 'URL','wpdating' ); ?></label></th>
					<td>
						<input name="registerurltxt" type="text"
						       value="<?php echo $check_register_page_redirect_mode->setting_value ?>"/>
					</td>
					<td><span class="description">&nbsp;</span></td>
				</tr>

				<!------------------------ End Register Redirect-------------------------------------  -->

				<!------------------------After Register Redirect------------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'After Register Redirect','wpdating' ); ?></label>
					</th>
					<td>
						<select name="after_register_page_redirect" onChange="afterregisterredirecturltxt(this.value);">
							<?php if ( $check_register_after_redirect_url->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating'
                                 ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description"><?php _e( __( 'Select ON to activate the After Register Redirect url mode.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field"
				    id="afterregisterredirecturl" <?php if ( $check_register_after_redirect_url->setting_status == 'Y' ) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo __( 'URL','wpdating' ); ?></label></th>
					<td>
						<input id='afterRegister' name="after_registerurltxt" type="text"
						       value="<?php echo $check_register_after_redirect_url->setting_value ?>"/>
					</td>
					<td><span class="description">&nbsp;</span></td>
				</tr>
				<!------------------------ End After Register Redirect------------------------------------- -->

				<!------------------------ Add on setting for Email verification or Auto login -------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'After User Register','wpdating' ); ?></label></th>
					<td>
						<select name="after_user_register_option">
							<?php if ( $after_user_register->setting_value == 'auto_login' ) { ?>
								<option value="auto_login"
								        selected="selected"><?php echo __( 'Auto Login','wpdating' ); ?></option>
								<option
									value="verify_email"><?php echo __( 'Email Verification','wpdating' ); ?></option>
							<?php } else { ?>
								<option
									value="auto_login"><?php echo str_replace( ':', '', __( 'Auto Login','wpdating' ) ); ?></option>
								<option value="verify_email"
								        selected="selected"><?php echo __( 'Email Verification','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select option to redirect user after registration. Note: You must have dsp_add_on','wpdating' ) ) ?></span>
					</td>
				</tr>
				<!------------------------End of Add on setting for Email verification or Auto login -------------------------------------  -->
								<!-- Help Tab-->
<!-- ----------------Email notification to admin--------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Help','wpdating' ); ?></label></th>
					<td>
						<select name="help_tab">
						<?php
							if ( $check_help_tab->setting_status == 'Y' )

								{ ?> <option value="Y" selected="selected"><?php echo __( 'On','wpdating' ); ?></option> <option value="N"><?php echo __( 'Off','wpdating' ); ?></option> <?php }

							else

								{ ?> <option value="Y"><?php echo __( 'On','wpdating' ); ?></option> <option value="N" selected="selected"><?php echo __( 'Off','wpdating' ); ?></option> <?php }

							?>
						</select>
					</td>
					<td>
						<span
							class="description"> <?php echo language_code( 'DSP_HELP_NOTIFICATION' ); ?>
						</span>
					</td>
			</tr>
									<!-- End Help Tab-->
			</table>
		</div>

		<div class="dsp_settings_box">
			<h3 class="hndle"><span><?php echo __( 'Features','wpdating' ); ?></span></h3>
			<table class="form-table short-input-fields">

				<!------------------------Video Module------------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Video','wpdating' ); ?></label></th>
					<td>
						<select name="video_module">
							<?php if ( $check_video_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the Video.','wpdating' ) ) ?></span></td>
				</tr>

				<!------------------------Audio Module------------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Audio','wpdating' ); ?></label></th>
					<td>
						<select name="audio_module">
							<?php if ( $check_audio_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the Audio.','wpdating' ) ) ?></span></td>
				</tr>

				<!------------------------Match Alert------------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Match Alert','wpdating' ); ?></label></th>
					<td>
						<select name="match_alert">
							<?php if ( $check_match_alert_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( __( 'Select ON to activate the Match Alert.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field">
					<?php
					//if($check_free_trail_mode->setting_status=='Y') {
					$member_list_gender = $check_member_list_gender_mode->setting_value;
					?>
					<th scope="row"><label><?php echo __( 'Member List','wpdating' ); ?></label></th>
					<td>
						<select name="member_list_gender">
							<option
								value="1" <?php if ( $member_list_gender == 1 ) { ?> selected="selected"<?php } ?>><?php echo __( 'All','wpdating' ) ?></option>
							<option
								value="2" <?php if ( $member_list_gender == 2 ) { ?> selected="selected"<?php } ?>><?php echo __( 'Male','wpdating' ) ?></option>
							<option
								value="3" <?php if ( $member_list_gender == 3 ) { ?> selected="selected"<?php } ?>><?php echo __( 'Female','wpdating' ) ?></option>
						</select>
					</td>
					<td>&nbsp;<span
							class="description"><?php _e( __( 'Select which member list will be shown on the front page.','wpdating' ) ) ?></span>
					</td>
				</tr>
				<!------------------------refresh_rate page-------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'Refresh Rate','wpdating' ); ?></label></th>
					<td>
						<input name="refresh_rate" type="text"
						       value="<?php echo $check_refresh_rate->setting_value ?>"/>
					</td>
					<td align="left"><span
							class="description"><?php _e( __( 'Enter duration for Refresh Features','wpdating' ) ) ?></span></td>
				</tr>
				<!------------------------refresh_rate page-------------------------------------  -->

				<!------------------------ Display username or full name option in each user profile page -------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'User Profile Display Name','wpdating' ); ?></label></th>
					<td>
						<select name="display_user_name">
							<?php if ( $display_user_name->setting_value == 'username' ) { ?>
								<option value="username"
								        selected="selected"><?php echo str_replace( ':', '', __( 'Username','wpdating' ) ); ?></option>
								<option value="fullname"><?php echo __( 'Fullname','wpdating' ); ?></option>
							<?php } else { ?>
								<option
									value="username"><?php echo str_replace( ':', '', __( 'Username','wpdating' ) ); ?></option>
								<option value="fullname"
								        selected="selected"><?php echo __( 'Fullname','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description"><?php _e( __( 'Select option to display user profile display name','wpdating' ) ) ?></span>
					</td>
				</tr>
				<!------------------------End of display username or full name option in each user profile page-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo __( 'My Friends','wpdating' ); ?></label></th>
					<td>
						<select name="my_friend_module">
							<?php
							if ( $check_my_friend_module->setting_status == 'Y' ) {
								?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description">&nbsp;<?php _e( language_code( 'DSP_SELECT_MY_FRIENDS_TEXT' ) ) ?></span>
					</td>
				</tr>

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_FLIRT_MODULE' ); ?></label></th>
					<td>
						<select name="flirt_module">
							<?php
							if ( $check_flirt_module->setting_status == 'Y' ) {
								?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description">&nbsp;<?php _e( language_code( 'DSP_SELECT_FLIRT_MODULE_TEXT' ) ) ?></span>
					</td>
				</tr>

				<!------------------------Blog Module------------------------------------- -->

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_BLOG_MODE' ); ?></label></th>
					<td>
						<select name="blog_module">
							<?php if ( $check_blog_module->setting_status == 'Y' ) { ?>

								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } else { ?>

								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } ?>

						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_BLOG_TEXT' ) ) ?></span></td>
				</tr>

				<!------------------------Picture Gallery Module------------------------------------- -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_PICTURE_GALLERY_MODE' ); ?></label></th>
					<td>
						<select name="picture_gallery_module">
							<?php if ( $check_picture_gallery_mode->setting_status == 'Y' ) { ?>

								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } else { ?>

								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } ?>

						</select>
					</td>
					<td><span
							class="description"><?php _e( language_code( 'DSP_SELECT_PICTURE_GALLERY_TEXT' ) ) ?></span>
					</td>

				</tr>

				<!------------------------DATE_TRACKER-------------------------------------  -->
				<tr class="form-field">

					<th scope="row"><label><?php echo language_code( 'DSP_DATE_TRACKER' ); ?></label></th>
					<td>
						<select name="date_tracker">
							<?php if ( $check_date_tracker_mode->setting_status == 'Y' ) { ?>

								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } else { ?>

								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } ?>

						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_DATE_TRACKER_TEXT' ) ) ?></span>
					</td>
				</tr>

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_RATE_PROFILE_MODE' ); ?></label></th>
					<td>
						<select name="rate_profile">
							<?php if ( $check_rate_profile_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td>&nbsp;<span
							class="description"><?php _e( language_code( 'DSP_SELECT_RATE_PROFILE_TEXT' ) ) ?></span>
					</td>
				</tr>

				<!------------------------ Trending status -------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_MENU_TRENDING' ); ?></label></th>
					<td>
						<select name="trending_option">
							<?php if ( $check_trending_option->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_TRENDING_TEXT' ) ) ?></span></td>
				</tr>

				<!------------------------ End Trending status -------------------------------------  -->
				<!------------------------MOBILE-------------------------------------  -->
				<tr class="form-field" style="display: none;">
					<th scope="row"><label><?php echo language_code( 'DSP_MOBILE_MODE' ); ?></label></th>
					<td>
						<select name="mobile_mode">
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php  ?>

						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_MOBILE_TEXT' ) ) ?></span></td>
				</tr>
				<!------------------------ End MOBILE-------------------------------------  -->
				<!------------------------terms page-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label>Terms
							Page<?php //echo __('Register Redirect', 'wpdating');              ?></label></th>
					<td>
						<select name="terms_page" onChange="termsurltxt(this.value);">
							<?php if ( $check_terms_page_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_TERM_TEXT' ) ) ?></span></td>
				</tr>

				<!------------------------terms page url-------------------------------------  -->
				<tr class="form-field"
				    id="termsurl" <?php if ( $check_terms_page_mode->setting_status == 'Y' ) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo language_code( 'DSP_REDIRECT_URL' ); ?></label></th>
					<td>
						<input name="termspageurltxt" type="text"
						       value="<?php echo $check_terms_page_mode->setting_value ?>"/>
					</td>
					<td><span class="description">&nbsp;</span></td>
				</tr>

				<!------------------------comments page-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_COMMENTS_MODE' ); ?></label></th>
					<td>
						<select name="comments">
							<?php if ( $check_comments_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_SELECT_COMMENTS_MODE_TEXT' ) ) ?></span>
					</td>
				</tr>

				<!------------------------comments page-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_ASTROLOGICAL_SIGNS' ); ?></label></th>
					<td>
						<select name="astrological_signs">
							<?php if ( $check_astrological_signs_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_ASTROLOGICAL_SIGNS_TEXT' ) ) ?></span>
					</td>
				</tr>
				<!---------------------------notification------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_NOTIFICATION_SETTING' ); ?></label></th>
					<td>
						<select name="notification" onChange="notificationbox(this.value);">
							<?php if ( $check_notification_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_NOTIFICATION_SETTING_TEXT' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field"
				    id="notificationpostitionbox" <?php if ( $check_notification_mode->setting_status == 'Y' ) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo language_code( 'DSP_NOTIFICATION_POSITION_SETTING' ); ?></label>
					</th>
					<td>
						<select name="notification_postition">
							<option
								value="left-top" <?php if ( $check_notification_postition_mode->setting_value == 'left-top' ) {
								echo 'selected="selected"';
							} ?>><?php echo language_code( 'DSP_OPTION_LEFT_TOP' ); ?></option>
							<option
								value="right-top" <?php if ( $check_notification_postition_mode->setting_value == 'right-top' ) {
								echo 'selected="selected"';
							} ?>><?php echo language_code( 'DSP_OPTION_RIGHT_TOP' ); ?></option>
							<option
								value="left-bottom" <?php if ( $check_notification_postition_mode->setting_value == 'left-bottom' ) {
								echo 'selected="selected"';
							} ?>><?php echo language_code( 'DSP_OPTION_LEFT_BOTTOM' ); ?></option>
							<option
								value="right-bottom" <?php if ( $check_notification_postition_mode->setting_value == 'right-bottom' ) {
								echo 'selected="selected"';
							} ?>><?php echo language_code( 'DSP_OPTION_RIGHT_BOTTOM' ); ?></option>
						</select>
					</td>
					<td><span
							class="description"><?php _e( language_code( 'DSP_NOTIFICATION_POSITION_SETTING_TEXT' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field"
				    id="notificationtimebox" <?php if ( $check_notification_mode->setting_status == 'Y' ) { ?> style="display:table-row;" <?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo language_code( 'DSP_NOTIFICATION_TIME_SETTING' ); ?></label></th>
					<td>
						<input name="notification_time" type="text" style="width:70px;"
						       value="<?php echo $check_notification_time_mode->setting_value ?>"/>
					</td>
					<td><span
							class="description"><?php _e( language_code( 'DSP_NOTIFICATION_TIME_SETTING_TEXT' ) ) ?></span>
					</td>
				</tr>

				<!------------------------meet_me page-------------------------------------  -->

				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_MEET_ME_MODE' ); ?></label></th>
					<td>
						<select name="meet_me">
							<?php if ( $check_meet_me_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_MEET_ME_MODE_TEXT' ) ) ?></span></td>
				</tr>

				<!------------------------meet_me page-------------------------------------  -->

				<!------------------------happening_graph-------------------------------------  -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_HAPPENING_GRAPH' ); ?></label></th>
					<td>
						<select name="happening_graph">
							<?php if ( $check_happening_graph->setting_status == 'Y' ) { ?>

								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } else { ?>

								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>

							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_HAPPENING_GRAPH_TEXT' ) ) ?></span></td>
				</tr>
			</table>
		</div>

		<div class="dsp_settings_box">
			<h3 class="hndle"><span><?php echo language_code( 'DSP_SETTINGS' ); ?></span></h3>
			<table class="form-table">
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_IMAGE_COUNT' ); ?></label></th>
					<td><input type="text" name="count_image" value="<?php echo $check_image_count->setting_value ?>"/>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_IMAGE_COUNT_TEXT' ) ) ?></span></td>
				</tr>
				<?php // ***********************************  Count Audio & Videos ********************************************** //     ?>
				<tr class="form-field">
					<th scope="row"><label>
					<?php echo language_code( 'DSP_AUDIO_COUNT' ); ?></td></label></th>
					<td><input type="text" name="count_audios" value="<?php echo $check_audio_count->setting_value ?>"/>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_AUDIO_COUNT_TEXT' ) ) ?></span></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_VIDEO_COUNT' ); ?></label></th>
					<td><input type="text" name="count_videos" value="<?php echo $check_video_count->setting_value ?>"/>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_VIDEO_COUNT_TEXT' ) ) ?></span></td>
				</tr>
				<?php // ***********************************  Count Audio & Videos **********************************************     ?>
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_PHOTO_MAKE_PRIVATE' ); ?></label></th>
					<td>
						<select name="private_photo">
							<?php if ( $check_private_photo->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span
							class="description"><?php echo _e( language_code( 'DSP_ENABLE_MAKE_PRIVATE_OPTION_TEXT' ) ) ?></span>
					</td>
				</tr>
			</table>
		</div>

		<div class="dsp_settings_box">
			<!-- Other settings   -->
			<h3 class="hndle"><span><?php echo language_code( 'DSP_OTHER_SETTING' ); ?></span></h3>
			<table class="form-table">
				<tbody>
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_START_YEAR' ); ?></label></th>
					<td><input type="text" name="start_dsp_year"
					           value="<?php echo $check_start_year->setting_value; ?>"></td>
					<td><span class="description"><?php echo language_code( 'DSP_START_DSP_YEAR' ); ?></span></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_END_YEAR' ); ?></label></th>
					<td><input type="text" name="end_dsp_year" value="<?php echo $check_end_year->setting_value; ?>">
					</td>
					<td><span class="description"><?php echo language_code( 'DSP_END_YEAR_TEXT' ); ?></span></td>
				</tr>
				<!-- For Minimum and Maximum age -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_MIN_AGE' ); ?></label></th>
					<td><input type="text" name="dsp_min_age"
					           value="<?php echo $check_min_age->setting_value; ?>"></td>
					<td><span class="description"><?php echo language_code( 'DSP_MIN_SEARCH_AGE' ); ?></span></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_MAX_AGE' ); ?></label></th>
					<td><input type="text" name="dsp_max_age" value="<?php echo $check_max_age->setting_value; ?>">
					</td>
					<td><span class="description"><?php echo language_code( 'DSP_MIN_SEARCH_AGE' ); ?></span></td>
				</tr>
				<!-- Random Online members -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_SELECT_ONLINE_MEMBERS' ); ?></label></th>
					<td>
						<select name="random_online_members" onChange="onlineMemberstxt(this.value);">
							<?php if ( $check_online_member_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>

					</td>
					<td><span
							class="description"><?php _e( language_code( 'DSP_SELECT_ONLINE_MEMBERS_VIEW_TEXT' ) ) ?></span>
					</td>
				</tr>
				<tr class="form-field">
					<div
						id="onlineMembers"<?php if ( $check_online_member_mode->setting_status == 'Y' ) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
						<th scope="row">
							<label><?php echo language_code( 'DSP_NO_OF_RANDOM_ONLINE_MEMBERS_VIEW' ); ?></label></th>
						<td>
							<input name="random_online_members_nos" class="randomOnlineMembers" type="text"
							       value="<?php echo $check_online_member_mode->setting_value ?>"/>
						</td>
						<td><span class="description">&nbsp;</span></td>
					</div>
				</tr>
				<!-- End Random Online members -->
				<!-- Module to calculate distance for loggedin users to other members -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_SELECT_DISTANCE_FEATURES' ); ?></label></th>
					<td>
						<select name="distance_feature">
							<?php if ( $check_distance_mode->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
					<td><span class="description"><?php _e( language_code( 'DSP_DISTANCE_MODE_TEXT' ) ) ?></span></td>
				</tr>
				<!-- End  of module to calculate distance for loggedin users to other members -->
				</tbody>
			</table>
			<!-- Other settings   -->
		</div>

		<!-- facebook Login api settings -->
		<div class="dsp_settings_box">
			<h3 class="hndle"><span><?php echo language_code( 'DSP_FACEBOOK_LOGIN_SETTING' ); ?></span></h3>
			<table class="form-table">
				<tbody>
				<tr class="form-field facebooksecretkey" <?php if ( $check_facebook_login->setting_status == 'Y' ) { ?><?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo language_code( 'DSP_APP_ID' ); ?></label></th>
					<td><input type="text" name="facebook_api_key"
					           value="<?php echo $check_facebook_app_id->setting_value; ?>"></td>
				</tr>
				<tr class="form-field facebooksecretkey">
					<td></td>
					<td><span class="description"><?php echo language_code( 'DSP_FACEBOOK_APP_ID_TEXT' ); ?></span></td>
				</tr>
				<tr class="form-field facebookapikey" <?php if ( $check_facebook_login->setting_status == 'Y' ) { ?><?php } else { ?> style="display:none;" <?php } ?>>
					<th scope="row"><label><?php echo language_code( 'DSP_SECRET_ID' ); ?></label></th>
					<td><input type="text" name="facebook_secret_key"
					           value="<?php echo $check_facebook_secret_id->setting_value; ?>"></td>
				</tr>
				<tr class="form-field facebookapikey">
					<td></td>
					<td><span class="description"><?php echo language_code( 'DSP_FACEBOOK_SECRET_ID_TEXT' ); ?></span>
					</td>
				</tr>
				<!-- setting to enable facebook login -->
				<tr class="form-field">
					<th scope="row"><label><?php echo language_code( 'DSP_FACEBOOK_LOGIN' ); ?></label></th>
					<td>
						<select name="facebook_login" onChange="showFacebookApiSetting(this.value);">
							<?php if ( $check_facebook_login->setting_status == 'Y' ) { ?>
								<option value="Y"
								        selected="selected"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } else { ?>
								<option value="Y"><?php echo __( 'On','wpdating' ); ?></option>
								<option value="N"
								        selected="selected"><?php echo __( 'Off','wpdating' ); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr class="form-field">
					<td></td>
					<td>
                        <span class="description">
                        <?php _e(__('Enable Facebook Login module.For more info how to set up Facebook Api  ', 'wpdating')) . ' ' ?>
                            <a href="javascript:" onclick="window.open('http://www.wpdating.com/?p=5309');"
                               target="_blank">
		                        <?php echo __('Click Here To Get API Key', 'wpdating'); ?>
	                        </a>
                        </span>
                    </td>
                </tr>
                <!-- End  of setting to enable facebook login -->
                </tbody>
            </table>
            <!-- End of facebook Login api settings -->
        </div>

        <!-- Addons -->
        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Addons', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <tbody>

                <!------------------------ Password field for registration status -------------------------------------  -->
                <tr class="form-field">
                    <th scope="row"><label><?php echo __('Password: ', 'wpdating'); ?></label></th>
                    <td>
                        <select name="password_option">
                            <?php if ($check_password_option->setting_status == 'Y') { ?>
                                <option value="Y"
                                        selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } else { ?>
                                <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                                <option value="N"
                                        selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><span
                                class="description"><?php _e(__('Select ON to enable password field  option  in registration page', 'wpdating')) ?></span>
                    </td>
                </tr>
                <!------------------------ End Password field for registration status -------------------------------------  -->

                </tbody>
            </table>
        </div>
        <div class="dsp_settings_box">
            <h3 class="hndle"><span><?php echo __('Language', 'wpdating'); ?></span></h3>
            <table class="form-table">
                <th scope="row"><label><?php echo __('Language', 'wpdating'); ?>:</label></th>
                <td>
                    <select name="po_language">
                        <?php if ($use_po_file->setting_status == 'Y') { ?>
                            <option value="Y"
                                    selected="selected"><?php echo __('On', 'wpdating'); ?></option>
                            <option value="N"><?php echo __('Off', 'wpdating'); ?></option>
                        <?php } else { ?>
                            <option value="Y"><?php echo __('On', 'wpdating'); ?></option>
                            <option value="N"
                                    selected="selected"><?php echo __('Off', 'wpdating'); ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><span class="description">Use PO file for Language Translation</span></td>
            </table>
        </div>


    </div>

    <table class="form-table">
        <?php // ***************************  ACTIVE DEACTIVE STATUS *********************************** //     ?>

        <!-- custom hook for add admin setting for add on -->
        <?php echo apply_filters('dsp_add_dsp_admin_setting', ''); ?>

        <tr class="form-field">
            <td>&nbsp;</td>
        </tr>
        <tr class="form-field">
            <td>
                <input type="submit" name="Submit" value="<?php _e('Save Changes', 'dsp_trans_domain') ?>"
                       class="button"/>
            </td>
        </tr>
    </table>

</form>

<br/>

<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>