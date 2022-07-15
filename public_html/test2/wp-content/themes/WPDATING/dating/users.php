<?php

require_once 'base.php';

class Users extends Base {

    public function __construct() {

        parent::__construct();
    }

    /* count number of online users */

    public function count_online_users() {
        global $wpdb;

        $num_online_users = $wpdb->get_var("SELECT COUNT(distinct oln.user_id) FROM $this->user_online_table oln INNER JOIN $this->user_profiles_table usr ON ( usr.user_id = oln.user_id ) WHERE oln.status = 'Y' AND usr.country_id !=0 AND usr.stealth_mode = 'N'");
        return $num_online_users;
    }

    public function get_status($user_id) {

        global $wpdb;

        $my_status = $wpdb->get_row("select my_status from $this->user_profiles_table where user_id= $user_id");
        $status = $my_status->my_status;
        return $status;
    }

    public function update_status($new_status, $user_id) {
        global $wpdb;

        $settings = new Settings();

        $check_approve_profile_status = $settings->get_setting('authorize_profiles');

        if ($check_approve_profile_status->setting_status == 'Y') {  // if Profile approve status is Y then Profile Automatically Approved.');
            $wpdb->query("UPDATE $this->user_profiles_table SET my_status= '$new_status' WHERE user_id = $user_id");

            $status_approval_message = "Your status has  been updated.";

            $this->add_news_feed($user_id, 'status');

            $this->add_notification($user_id, 0, 'status');
        } else {

            $wpdb->query("UPDATE $this->user_profiles_table SET my_status= '$new_status' ,status_id=0 WHERE user_id = $user_id");

            $status_approval_message = 'Your status has been updated';
        }
    }

    public function add_news_feed($user_id, $type) {
        global $wpdb;

        $wpdb->query("insert into $this->news_feed_table values('','$user_id','$type','" . date("Y-m-d H:i:s") . "')");
    }

    public function add_notification($user_id, $member_id, $type) {

        global $wpdb;

        $settings = new Settings();

        $check_notification_mode = $settings->get_setting('notification');

        if ($check_notification_mode->setting_status == 'Y') {

            if ($user_id > 0) {

                $wpdb->query("insert into $this->notification_table values('','$user_id','$member_id','$type','" . date("Y-m-d H:i:s") . "','Y')");
            }
        }
    }

}
