<?php

require_once("base.php");

class Settings extends Base {

    public function __construct() {

        parent::__construct();
    }

    public function get_all_settings() {
        global $wpdb;
        $query = "select * from $this->general_settings_table";
        $settings = $wpdb->get_results($query);
        return $settings;
    }

    public function get_setting($setting_name) {
        global $wpdb;
        $query = "select * from $this->general_settings_table where setting_name='$setting_name'";
        $setting = $wpdb->get_row($query);
        return $setting;
    }

}
