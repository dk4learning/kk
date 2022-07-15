<?php

class Wpdating_Search
{
    public function __construct()
    {
        $this->load_dependencies();
        $this->define_public_hooks();
    }

    private function load_dependencies()
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpdating-search-public.php';
    }

    public function define_public_hooks()
    {
        $plugin_public = new Wpdating_Search_Public();
        add_action( 'wp_enqueue_styles', array(&$plugin_public, 'enqueue_styles'));
        add_action( 'wp_enqueue_scripts', array(&$plugin_public, 'enqueue_scripts'));
        add_action( "wp_ajax_get_state_by_country_id", array(&$plugin_public, "get_state_by_country_id"));
        add_action( "wp_ajax_get_city_by_country_id", array(&$plugin_public, "get_city_by_country_id"));
        add_action( "wp_ajax_get_city_by_state_id", array(&$plugin_public, "get_city_by_state_id"));
        add_action( "wp_ajax_load_initial_data", array(&$plugin_public, "ajax_load_initial_data"));
        add_action( "wp_ajax_new_quick_search", array(&$plugin_public, "ajax_new_quick_search"));
        add_action( "wp_ajax_new_search_filter", array(&$plugin_public, "ajax_new_search_filter"));
        add_action( "wp_ajax_saved_search_result", array(&$plugin_public, "ajax_saved_search_result"));
        add_action('wp_new_search', array(&$plugin_public, 'new_search'));
        add_action('wp_search_by_user_login', array(&$plugin_public, 'search_by_user_login'));
    }
}