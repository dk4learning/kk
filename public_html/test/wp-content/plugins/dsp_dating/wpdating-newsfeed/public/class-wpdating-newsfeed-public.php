<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       wpdating.com
 * @since      1.0.0
 *
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpdating_Newsfeed
 * @subpackage Wpdating_Newsfeed/public
 * @author     WPDating <wpdating@gmail.com>
 */
class Wpdating_Newsfeed_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdating_Newsfeed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdating_Newsfeed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( 'wpdating-newsfeed-css', plugin_dir_url( __FILE__ ) . 'css/wpdating-newsfeed-public.css', array(), '', 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpdating_Newsfeed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpdating_Newsfeed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_script('wpdating-newsfeed-js', plugin_dir_url( __FILE__ ) . 'js/wpdating-newsfeed-public.js', array( 'jquery' ));
        wp_localize_script('wpdating-newsfeed-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

    }

	public function news_feed()
    {
        global $wpdb;
        $profile_pageurl = get('pagetitle');
        $dsp_user_table  = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
        $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
        $dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
        $dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
        $posts_table = $wpdb->prefix . POSTS;
        $user_id = get_current_user_id();
        $check_flirt_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'flirt_module'");
        $check_my_friend_module = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'my_friends'");
        $check_virtual_gifts_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'virtual_gifts'");
        $check_match_alert_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'match_alert'");
        $check_comments_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'comments'");
        $count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
        $count_wink_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE wink_read='N' AND receiver_id=$user_id");
        $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
        $check_approve_comments_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_comments'");
        $count_friends_comments = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_comments_table WHERE member_id=$user_id AND status_id=0");
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
        $member_pageid = $member_page_title_ID->setting_value;
        $post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
        $root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/"; // Print Site root link
        include_once('partial/wpdating-newfeed-container.php');
    }

    public function ajax_fetch_news_feed()
    {
        global $wpdb;
        $page = $_POST['page'];
        $dsp_news_feeds = $wpdb->prefix . DSP_NEWS_FEED_TABLE;
        $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
        $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
        $dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
        $dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
        $dsp_member_videos = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;

        $user_id = get_current_user_id();


        $sql_query = "SELECT feed.feed_type, feed.feed_type_id, feed.datetime, favourite.favourite_user_id AS user_id
                        FROM $dsp_news_feeds AS feed
                        JOIN $dsp_user_favourites_table AS favourite
                        ON feed.user_id = favourite.favourite_user_id
                        WHERE feed.feed_type != 'login' AND feed.feed_type != 'logout' AND favourite.user_id = $user_id ";

        $sql_query1 = "SELECT feed.feed_type, feed.feed_type_id , feed.datetime, friend.friend_uid AS user_id 
                        FROM $dsp_news_feeds AS feed
                        JOIN $dsp_my_friends_table AS friend
                        ON feed.user_id = friend.friend_uid
                        WHERE feed.feed_type != 'login' AND feed.feed_type != 'logout' AND friend.user_id = $user_id ";

        if ('All' != $_POST['user_id']) {
            $sql_query = str_replace("favourite.user_id = $user_id", "favourite.user_id = $user_id AND favourite.favourite_user_id = {$_POST['user_id']}", $sql_query);
            $sql_query1 = str_replace("friend.user_id = $user_id", "friend.user_id = $user_id AND friend.friend_uid = {$_POST['user_id']}", $sql_query1);
        }

        $limit = 8;
        $offset = ($page - 1) * $limit;
        $sql_query = "($sql_query)
                      UNION ($sql_query1)
                      ORDER BY datetime DESC
                      ";
        $count = $wpdb->get_results($sql_query, ARRAY_A);
        if (empty($count)){
            print_r("<h1>No feeds</h1>");
            die();
        }
        $count1 = $count;

        for ($i=0; $i<sizeof($count); $i++) {
            if ($count[$i]['feed_type'] == 'audio') {
                if ($count[$i]['feed_type_id']) {
                    $audio_file = $wpdb->get_row("SELECT * FROM $dsp_member_audios WHERE audio_file_id={$count[$i]['feed_type_id']}");
                    if ((!empty($audio_file) && $audio_file->private_audio == 'Y')) {
                        unset($count1[$i]);
                    }
                }
            }
            if ($count[$i]['feed_type'] == 'video') {
                 if ($count[$i]['feed_type_id']) {
                     $video_file = $wpdb->get_row("SELECT * FROM $dsp_member_videos WHERE video_file_id={$count[$i]['feed_type_id']}");
                     if ((!empty($video_file) && $video_file->private_video == 'Y')) {
                         unset($count1[$i]);
                     }
                 }
            }
            if ($count[$i]['feed_type'] == 'gallery_photo') {
                if ($count[$i]['feed_type_id']) {
                    $gallery = $wpdb->get_row("SELECT * FROM $dsp_galleries_photos WHERE gal_photo_id={$count[$i]['feed_type_id']}");
                    if ((!empty($gallery) && $gallery->status_id == 0)) {
                        unset($count1[$i]);
                    }
                }
            }
            if ($count[$i]['feed_type'] == 'profile_photo') {
                $dsp_user_profile_table    = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                    $profile = $wpdb->get_row("SELECT * FROM $dsp_user_profile_table WHERE user_id = {$count[$i]['user_id']}");
                    if ((!empty($profile) && $profile->make_private == 'Y')) {
                        unset($count1[$i]);
                    }
            }
        }
        $lp = ceil(sizeof($count1) / $limit);
        $i = 0;
        $count2 = array();
        foreach ($count1 as $cou)
        {
            $count2[$i] = $cou;
            $i++;
        }

        $data = (object)array_slice($count2,$offset,$offset+$limit);

        $response = $this->prepare_items_for_response($data, $page, $lp);

        print_r($response);
        die;
    }

    public function prepare_items_for_response($request, $current_page, $last_page)
    {
        global $wpdb;
        $output = "";
        foreach ($request as $feed) {
            $feed = (object)$feed;
            $imagepath = get_option('siteurl') . '/wp-content/';  // image Path
            $image = display_members_photo($feed->user_id, $imagepath);
            $link = '';
            $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
            $posts_table = $wpdb->prefix . POSTS;
            $member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
            $member_pageid = $member_page_title_ID->setting_value;
            $post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
            $root_link = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/"; // Print Site root link
            $dsp_user_profile_table    = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
            $profile = $wpdb->get_row("SELECT * FROM $dsp_user_profile_table WHERE user_id = {$feed->user_id}");
            if ($profile->gender == 'C') {
                $link = $root_link . get_username($feed->user_id) . "/my_profile/";
            } else {
                $link = $root_link . get_username($feed->user_id) . "/";
            }
            $inp_date = strtotime($feed->datetime);
            $output_date = date ("d M", $inp_date);
            $output .= "<li>
                            <div class='dspdp-bordered-item'>
                                <div>
                                    <p style='text-align: right'>" . $output_date . "</p>
                                    <a href='$link'><img style='width:50px; height:50px; vertical-align:middle; margin:10px 5px 0px 10px;' src='$image'  alt='<?php echo get_username($feed->user_id); ?>'/></a>
                                    <span class='status_wrap'><span>". get_username($feed->user_id);

            if ($feed->feed_type == 'profile_photo') {
                $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
                $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$feed->user_id' AND status_id=1");
                $imagepath1 = get_option('siteurl') . '/wp-content/uploads/dsp_media/user_photos/user_' . $feed->user_id . '/' . $member_exist_picture->picture;
                $output .= "</span> " . __('just added new profile photo.', 'wpdating') . "</span>
                            </div>
                            <div class='timeline_content'><a rel='example_group' href='". $imagepath1 ."'>
                                <img src='". $imagepath1 ."' style='width:90px; height:auto; vertical-align:middle; margin-right:5px;'/></a>
                            </div>";
            } elseif ($feed->feed_type == 'gallery_photo') {
                $output .="</span> " . __('just added new photo.', 'wpdating') . "</span>
                              </div>";
                $dsp_galleries_photos      = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
                if (null != $feed->feed_type_id){
                    $gallery_image = $wpdb->get_row("SELECT * FROM $dsp_galleries_photos WHERE gal_photo_id='$feed->feed_type_id'");
                    if ($gallery_image) {
                        $gallery_image_path = get_site_url() . '/wp-content/uploads/dsp_media/user_photos/user_' . $feed->user_id . '/album_' . $gallery_image->album_id . '/' . $gallery_image->image_name;
                            $output .= "<div class='timeline_content'><a rel='example_group' href='". $gallery_image_path ."'>
                                    <img class='dc_gallary_view' src='" . $gallery_image_path . "' style='width:90px; height:auto; vertical-align:middle; margin-right:5px;'></a>
                                </div>";
                    }
                }
            } elseif ($feed->feed_type == 'audio') {
                $output .="</span> " . __('just added new audio.', 'wpdating') . "</span>
                                </div>";
                if (null != $feed->feed_type_id) {
                    $dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
                    $audio_file = $wpdb->get_row("SELECT * FROM $dsp_member_audios WHERE audio_file_id='$feed->feed_type_id'");
                    if ($audio_file) {
                        $audio_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $feed->user_id . "/" . $audio_file->file_name;
                            $output .= "<div class=\"audio-box news_feed_audio_box \">
                                        <p>
                                            <span class=\"fa fa-music\"></span>
                                        </p>
                                        <audio style=\"width:100%; height:40px\" controls name=\"media\" class=\"dsp-spacer\"  ><source src='" . $audio_path . "' type=\"audio/mp3\"></audio>
                                    </div>";
                    }
                }
            } elseif ($feed->feed_type == 'video') {
                $output .="</span> " . __('just added new video.', 'wpdating') . "</span>
                                </div>";
                if (null != $feed->feed_type_id){
                    $dsp_member_videos         = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
                    $video = $wpdb->get_row("SELECT * FROM $dsp_member_videos WHERE video_file_id='$feed->feed_type_id'");
                    if ($video) {
                        $videos_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_videos/user_" . $feed->user_id . "/" . $video->file_name;
                            $output .= "<div class='video-box'>
                                    <video id='sampleMovie' src='" . $videos_path . "' controls width=\"200\" height=\"200\" scale=\"tofit\" ></video><br />
                                </div>";
                     }
                }
            } elseif ($feed->feed_type == 'status') {
                $profile = $wpdb->get_row("SELECT * FROM $dsp_user_profile_table WHERE user_id = {$feed->user_id}");
                $output .= "</span> " . __('just added new status update.', 'wpdating') . "</span>
                            </div>
                            <div>
                                  <span class='status_wrap1'>" . $profile->my_status ."</span>
                            </div>";
            }

            $output .= "    </div>
                        </li>";
        }
        //code for pagination
        if ($current_page < $last_page){
            $output .= "<button id='loadMore' onclick='load_more()'>Load More</button>";
        }
        return $output;
    }

}
