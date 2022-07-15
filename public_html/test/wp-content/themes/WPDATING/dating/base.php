<?php

/**
 * This is the base class that will be extended by all the other classes
 * All the tables are initialized here and can be used by the child classes directly
 * */
class Base {

    //list all the tables that will be used

    protected $user_table;
    protected $user_online_table;
    protected $user_profiles_table;
    protected $news_feed_table;
    protected $notification_table;
    protected $general_settings_table;

    public function __construct() {

        global $wpdb;

        //initialize user related tables
        $this->user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;

        $this->user_table = $wpdb->prefix . DSP_USERS_TABLE;

        $this->user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

        $this->news_feed_table = $wpdb->prefix . DSP_NEWS_FEED_TABLE;

        $this->notification_table = $wpdb->prefix . DSP_NOTIFICATION_TABLE;


        //initialize settings table
        $this->general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
    }

    public function get_pagination($total_results, $page, $limit) {
        
    }

}
