<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

$access_feature_name = 'Saved Search';
$saved_search_access = false;
if ($check_free_mode->setting_status == "N") {  // free mode is off
    if ($check_free_trail_mode->setting_status == "Y") { // if free trial mode is ON
        $check_member_trial_msg = check_free_trial_feature($access_feature_name, $user_id);
        if ($check_member_trial_msg == "Access") {
            $upload_audio_access = true;
        }
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Access") {
                $saved_search_access = true;
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "Access") {
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Access")
                    $saved_search_access = true;
            }
        } else { // free trial mode is off
            $check_membership_msg = check_membership($access_feature_name, $user_id);

            if ($check_membership_msg == "Access")
                $saved_search_access = true;
        }
    }
} else {
    if ($_SESSION['free_member']) {
        $saved_search_access = true;
    } else {
        if ($check_force_profile_mode->setting_status == "Y") {
            $check_force_profile_msg = check_force_profile_feature($access_feature_name, $user_id);
            if ($check_force_profile_msg == "Access") {
                $saved_search_access = true;
            }
        } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
            $check_approved_profile_msg = check_approved_profile_feature($user_id);
            if ($check_approved_profile_msg == "Access") {
                $check_membership_msg = check_membership($access_feature_name, $user_id);

                if ($check_membership_msg == "Access")
                    $saved_search_access = true;
            }
        } else {
            $check_membership_msg = check_membership($access_feature_name, $user_id);

            if ($check_membership_msg == "Access")
                $saved_search_access = true;
        }
    }
}

if ($saved_search_access) {

    $Search_Id = get('search_Id');
    $Action = get('Action');
    if ($Action == "Del" && !empty($Search_Id)) {   // DELETE PICTURE
        $wpdb->query("DELETE FROM $dsp_user_search_criteria_table WHERE user_search_criteria_id = '$Search_Id'");
    }

    ?>
    <form name="savesearches" method="POST" action="<?php echo $root_link . "search/save_searches/"; ?>">
        <div class="box-border">
            <div class="box-pedding">
              <?php
                 $search_result = $wpdb->get_results("SELECT * FROM $dsp_user_search_criteria_table Where user_id='$current_user->ID' Order by user_search_criteria_id ");
                  if(isset($search_result) && !empty($search_result)):
               ?>
                <div class="heading-submenu"><strong><?php echo __('save searches', 'wpdating'); ?></strong></div>
                <ul class="save-search dsp-save-search clearfix">
                    <li class="dspdp-row">
                        <span class="name dspdp-col-sm-6 dsp-sm-4 dspdp-col-xs-12"><strong><?php echo __('Search Name', 'wpdating') ?></strong></span>
                        <span class="delete dspdp-col-xs-6 dsp-xs-4 dspdp-col-sm-2 dsp-sm-4 dsp-pull-right"><strong><?php echo __('Delete', 'wpdating') ?></strong></span>
                        <span class="type dspdp-col-xs-6 dsp-xs-4    dspdp-col-sm-4 dsp-sm-4 dspdp-text-right"><strong><?php echo __('Search Type', 'wpdating') ?></strong></span>
                    </li>
                    <li class="dsp-none">
                        <hr> <input name="save_search_Id" id="save_search_Id" type="hidden" value="" /></li>
                    <?php
                        foreach ($search_result as $search) {
                            $save_search_id = $search->user_search_criteria_id;
                            ?>
                            <li class="dspdp-row">
                                <a class="name dspdp-col-sm-6 dsp-sm-4 dspdp-col-xs-12" href="<?php
                                if ($search->search_type == 'basic') {
                                    echo $root_link . "search/search_result/basic_search/basic_search/searchbysave/save_search/save_search_Id/" . $save_search_id . "/";
                                }elseif ($search->search_type == 'new_advance_search'){
                                    echo $root_link . "search/new_search/searchbysave/save_search/save_search_Id/" . $save_search_id . "/";
                                }else{
                                    echo $root_link . "search/search_result/searchbysave/save_search/save_search_Id/" . $save_search_id . "/";
                                }?>"><span class="name"><?php echo $search->search_name ?></span></a>
                                <a class="name dspdp-col-xs-6 dsp-sm-4 dspdp-col-sm-2 dspdp-text-danger dsp-pull-right dsp-delete-action" href="<?php echo $root_link . "search/save_searches/Action/Del/search_Id/" . $save_search_id . "/"; ?>" onclick="if (!confirm('<?php echo __('Are you sure you want to delete?', 'wpdating'); ?>'))
                                            return false;"> <span class="delete"><?php echo __('Delete', 'wpdating'); ?></span></a>
                                <span class="type  dspdp-col-xs-6 dspdp-col-sm-4 dsp-sm-4 dspdp-text-right"><?php echo $search->search_type ?></span>
                            </li>
                        <?php } ?>
                </ul>
            <?php else: ?>
                <div class="heading-submenu"><strong><?php echo __('No saved searches yet.', 'wpdating'); ?></strong></div>
            <?php endif; ?>
            </div>
        </div>
    </form>
    <?php
} else{
    include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
}