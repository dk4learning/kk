<?php

require_once('base.php');

class Members extends Base {

    public function __construct() {

        parent::__construct();
    }

    public function get_all_members($page = 1) {

        global $wpdb;

        $limit = 1;

        $offset = $limit * ($page - 1);

        $settings = new Settings();

        $check_member_list_gender_mode = $settings->get_setting('member_list_gender');

        $member_list_gender = $check_member_list_gender_mode->setting_value;

        $check_couples_mode = $settings->get_setting('couples');
        $couples_mode = $check_couples_mode->seting_value;

        if ($member_list_gender == 2)
            $member_gender = 'M';
        elseif ($member_list_gender == 3)
            $member_gender = 'F';
        else
            $member_gender = '';

        if ($member_gender != '') {
            //if gender is specified
            $total_results = $wpdb->get_var("SELECT COUNT(*) as Num FROM $this->user_profiles_table WHERE status_id=1 AND gender='$member_gender' order by user_profile_id DESC");

            if ($couples_mode == 'Y') {
                $new_members = $wpdb->get_results("SELECT * FROM $this->user_profiles_table WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 and stealth_mode='N' Order By user_profile_id DESC LIMIT $offset, $limit");
            } else {
                $new_members = $wpdb->get_results("SELECT * FROM $this->user_profiles_table WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 AND gender!='C' and stealth_mode='N' Order By user_profile_id DESC LIMIT $offset, $limit");
            }
        } else {
            //if gender is not specified
            $total_results = $wpdb->get_var("SELECT COUNT(*) as Num FROM $this->user_profiles_table WHERE status_id=1  order by user_profile_id DESC");

            if ($couples_mode == 'Y') {
                $new_members = $wpdb->get_results("SELECT * FROM $this->user_profiles_table WHERE status_id=1  AND country_id!=0 and stealth_mode='N' Order By user_profile_id DESC LIMIT $offset, $limit");
            } else {
                $new_members = $wpdb->get_results("SELECT * FROM $this->user_profiles_table WHERE status_id=1  AND country_id!=0 AND gender!='C' and stealth_mode='N' Order By user_profile_id DESC LIMIT $offset, $limit");
            }
        }

        return $new_members;
    }

}
