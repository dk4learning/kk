<?php  
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$current_user_id   = get_current_user_id();
$dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
$tbl_name          = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$gender            = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get2('gender');
$age_from          = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get2('age_from');
$age_to            = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get2('age_to');
$country_id        = isset($_REQUEST['cmbCountry']) ? esc_sql($_REQUEST['cmbCountry']) : get2('cmbCountry');
$seeking           = isset($_REQUEST['seeking']) ? esc_sql($_REQUEST['seeking']) : get2('seeking');
$zipcode           = isset($_REQUEST['zip_code']) ?esc_sql(sanitizeData(trim($_REQUEST['zip_code']), 'xss_clean')) : get2('zip_code');
$zipcode           = ltrim($zipcode, '0');
$miles             = isset($_REQUEST['miles']) ?esc_sql(sanitizeData(trim($_REQUEST['miles']), 'xss_clean')) : get2('miles');
$goback            = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']  : '';
$errors            = array();

if(!empty($zipcode) && !empty($miles)) {
    $zipcode_row = $wpdb->get_row("SELECT * FROM {$dsp_zipcode_table} WHERE zipcode = '{$zipcode}'");
    if (!is_null($zipcode_row)) {
        $lat     = $zipcode_row->latitude;
        $lng     = $zipcode_row->longitude;
        if (strpos($zipcode_row->country, '/') !== false) {
            $exploded_arr_country = explode('/', $zipcode_row->country);
            $zipcode_row->country = $exploded_arr_country[0];
        }
        $country = strtolower($zipcode_row->country);
        $r       = 3959;

        $zipcodes_to_include = [];

        $handle = fopen(WP_DSP_ABSPATH . "zipcodes/{$country}_zipcodes.csv", "r");
        if ($handle) {
            while ($data = fgetcsv($handle)) {
                $lat_from = deg2rad($lat);
                $lng_from = deg2rad($lng);
                $lat_to   = deg2rad($data[1]);
                $lng_to   = deg2rad($data[2]);

                $lat_delta = $lat_to - $lat_from;
                $lng_delta = $lng_to - $lng_from;

                $angle = 2 * asin(sqrt(pow(sin($lat_delta / 2), 2) +
                        cos($lat_from) * cos($lat_to) * pow(sin($lng_delta / 2), 2)));
                $distance = $angle * $r;
                if ($distance <= $miles) {
                    $zipcodes_to_include[] = "'{$data[0]}'";
                }
            }
        }
    } else {
        $errors[] = __("Invalid zip code", "wpdating");
    }
}

$errors  = array_filter($errors);
if(empty($errors)) {
    $sql_query = "SELECT user.ID user_id, user.user_login user_name, user.display_name user_display_name,
                     user_profile.gender user_gender, user_profile.seeking user_seeking, 
                     user_profile.age user_age, user_profile.make_private user_make_private, 
                     user_profile.stealth_mode user_stealth_mode,
                     country.name user_country, state.name user_state, city.name user_city,
                     (SELECT member_photo.picture FROM {$dsp_members_photos} member_photo 
                      WHERE member_photo.user_id = user.ID LIMIT 1) AS user_image_name,
                     IF(online_user.session, true, false) is_user_online,
                     IF(user_favourite.favourite_id, true, false) is_user_favourite
                     FROM {$wpdb->users} user 
                     JOIN {$dsp_user_profiles} user_profile
                     ON user.ID = user_profile.user_id
                     LEFT JOIN {$dsp_country_table} country
                     ON user_profile.country_id = country.country_id
                     LEFT JOIN {$dsp_state_table} state
                     ON user_profile.state_id = state.state_id
                     LEFT JOIN {$dsp_city_table} city
                     ON user_profile.city_id = city.city_id
                     LEFT JOIN {$dsp_online_user_table} online_user
                     ON user.ID = online_user.user_id
                     LEFT JOIN {$dsp_user_favourites_table} user_favourite
                     ON user_profile.user_id = user_favourite.user_id AND user_favourite.favourite_user_id = {$current_user_id}
                     WHERE user.ID!='{$current_user_id}'";

    if (count($zipcodes_to_include) > 0) {
        $zipcodes_to_include = implode(",", $zipcodes_to_include);
        $sql_query .= " AND user_profile.zipcode IN({$zipcodes_to_include})";
    }
    if (!empty($age_from) && !empty($age_to)) {
        $sql_query .= " AND ((year(CURDATE())-year(user_profile.age)) > '{$age_from}') AND ((year(CURDATE())-year(user_profile.age)) < '{$age_to}')";
    }

    if ($gender == 'all') {
        if ($check_couples_mode->setting_status == 'Y') {
            $sql_query .= " AND user_profile.gender IN('M','F','C')";
        } else {
            $sql_query .= " AND user_profile.gender IN('M','F')";
        }
    } else {
        $sql_query .= " AND user_profile.gender='{$gender}'";
    }

    if (!empty($country_id) && $country_id != 0) {
        $sql_query .= " AND user_profile.country_id = '{$country_id}' ";
    }

    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ({$sql_query}) AS total");
}
if (!empty($errors)) : ?>
    <div class="box-border">
        <div class="box-pedding">  
            <div class="error">
                <?php foreach ($errors as $error) :?>
                    <span><?php echo $error; ?></span>
                    <span><a href="<?php echo $goback; ?>"><?php echo __('Start a new Search', 'wpdating'); ?></a></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
elseif ($user_count == 0) : ?>
    <div class="box-border">
        <div class="box-pedding">  
            <div class="page-not-found">
                <?php echo __('No record found for your search criteria.', 'wpdating'); ?><br /><br />
                <?php if(is_user_logged_in()):?>
                    <span><a href="<?php echo $root_link . "search/zipcode_search/"; ?>"><?php echo __('Start a new Search', 'wpdating'); ?></a></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
else : ?>
<div class="box-border dsp-member-container">
    <div class="box-pedding dspdp-row dsp-row">
        <?php
        // ----------------------------------------------- Start Paging code------------------------------------------------------ //
        $page_name = $root_link . "search/zipcode_search_result/zipcode_search/zipcode_search";
        if ($gender != "") {
            $page_name .= "/gender/" . $gender;
        }
        if ($age_from != "") {
            $page_name .= "/age_from/" . $age_from;
        }
        if ($age_to != "") {
            $page_name .= "/age_to/" . $age_to;
        }
        if (!empty($country_id) && $country_id != 0) {
            $page_name .= "/cmbCountry/" . $country_id;
        }
        if ($zipcode != "") {
            $page_name .= "/zip_code/" . $zipcode;
        }
        if ($miles != "") {
            $page_name .= "/miles/" . $miles;
        }

        $page = get2('page') ? get2('page') : 1;

        // How many adjacent pages should be shown on each side?
        $adjacents = 2;
        $limit     = isset($check_search_result->setting_value) ? $check_search_result->setting_value : 10;
        if ($page) {
            $start = ($page - 1) * $limit;
        } else {
            $start = 0;
        }

        $prev     = $page - 1;
        $next     = $page + 1;
        $lastpage = ceil($user_count / $limit);;  //lastpage is = total pages / items per page, rounded up.
        $lpm1     = $lastpage - 1;

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class='wpse_pagination'>";
            //previous button
            if ($page > 1) {
                $pagination .= "<div><a style='color:#365490' href=\"" . $page_name . "/page/$prev/\">" . __('Previous', 'wpdating') . "</a></div>";
            } else {
                $pagination .= "<span  class='disabled'>" . __('Previous', 'wpdating') . "</span>";
            }

            //pages
            if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<span class='current'>$counter</span>";
                    } else {
                        $pagination .= "<div><a href=\"" . $page_name . "/page/$counter/\">$counter</a></div>";
                    }
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<span class='current'>$counter</span>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "/page/$counter/\">$counter</a></div>";
                        }
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/$lpm1/\">$lpm1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/$lastpage/\">$lastpage</a></div>";
                } //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<div><a href=\"" . $page_name . "/page/1/\">1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/2/\">2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<div class='current'>$counter</div>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "/page/$counter/\">$counter</a></div>";
                        }
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/$lpm1/\">$lpm1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/$lastpage/\">$lastpage</a></div>";
                } //close to end; only hide early pages
                else {
                    $pagination .= "<div><a href=\"" . $page_name . "/page/1/\">1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "/page/2/\">2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<span class='current'>$counter</span>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "/page/$counter/\">$counter</a></div>";
                        }
                    }
                }
            }

            //next button
            if ($page < $counter - 1) {
                $pagination .= "<div><a style='color:#365490' href=\"" . $page_name . "/page/$next/\">" . __('Next', 'wpdating') . "</a></div>";
            } else {
                $pagination .= "<span class='disabled'>" . __('Next', 'wpdating') . "</span>";
            }
            $pagination .= "</div>\n";
        }
        // ------------------------------------------------End Paging code------------------------------------------------------ //

        $search_members = $wpdb->get_results("{$sql_query} LIMIT {$start}, {$limit}");

        foreach ($search_members as $search_member) :
            $user_id        = $search_member->user_id;
            $gender         = $search_member->user_gender;
            $seeking        = $search_member->user_seeking;
            $country_name   = $search_member->user_country;
            $state_name     = $search_member->user_state;
            $city_name      = $search_member->user_city;
            $make_private   = $search_member->user_make_private;

            $is_member_online = $search_member->user_stealth_mode == 'Y' ? true : $search_member->is_user_online;
            if ($search_member->user_stealth_mode == 'Y' || $search_member->is_user_online) {
                $user_online_class   = 'dspdp-status-on';
                $user_online_fav_img = $fav_icon_image_path . 'online-chat.png';
                $user_online_alt     = __('Online', 'wpdating');
            } else {
                $user_online_class   = 'dspdp-status-off';
                $user_online_fav_img = $fav_icon_image_path . 'off-line-chat.png';
                $user_online_alt     = __('Offline', 'wpdating');
            }

            if ($search_member->user_gender == 'C') {
                $member_profile_url = $root_link . $search_member->user_name . "/my_profile/";
            } else {
                $member_profile_url = $root_link . $search_member->user_name;
            } ?>
                <div class="dspdp-col-sm-3 dspdp-col-xs-6 dsp-sm-3">
                    <div  class="box-search-result image-container">
					<span class="online dspdp-online-status dsp-block dsp-selected" style="display:none; top:0px;">
                        <span class="<?php echo $user_online_class; ?>"></span>
                    </span>
                    <div class="img-box dspdp-spacer  circle-image">
                        <span class="online dspdp-online-status dsp-none">
                            <img class="icon-on-off" src="<?php echo $user_online_fav_img;  ?>" title="<?php echo $user_online_alt; ?>" border="0" alt="<?php echo $user_online_alt;?>"/>
                        </span>
                        <a href="<?php echo $member_profile_url; ?>" >
                            <img src="<?php echo dsp_get_member_image_urls($search_member->user_id, $search_member->user_image_name,
                                $search_member->user_make_private, $search_member->is_user_favourite, $search_member->user_gender)->image_350; ?>"
                                 border="0" class="img-big"  alt="<?php echo $search_member->user_display_name; ?>"/>
                        </a>
                    </div>
                    <div class="user-status">
                        <span class="user-name dspdp-h5 dspdp-username dsp-username">
                            <strong>
                                <a href="<?php echo $member_profile_url; ?>">
                                    <?php echo (strlen($search_member->user_display_name) > 15) ? substr($search_member->user_display_name, 0, 13) . '...' : $search_member->user_display_name; ?>
                                </a>
                            </strong>
                        </span>
                    </div>
                    <div class="user-details dspdp-spacer dspdp-user-details  dsp-user-details">
                        <?php echo GetAge($search_member->user_age); ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($search_member->user_gender); ?> <?php echo __('from', 'wpdating'); ?> <br />
                        <?php echo !empty($search_member->user_city)?  $search_member->user_city . ',' : ''; ?> <?php echo !empty($search_member->user_state)?  $search_member->user_state . ',' : ''; ?> <?php echo $search_member->user_country; ?>
                    </div>
                    <div class="user-links dsp-none">
                        <ul class="user-links">
                            <?php if ($check_my_friend_module->setting_status == 'Y') : // Check My friend module Activated or not  ?>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border">
                                    <?php if (is_user_logged_in()) :  // CHECK MEMBER LOGIN
                                        if ($check_user_profile_exist > 0) :  // check user dating profile exist or not ?>
                                            <a href="<?php echo $root_link . "add_friend/user_id/" . $current_user_id . "/frnd_userid/" . $search_member->user_id . "/"; ?>"
                                               title="<?php echo __('Add to Friends', 'wpdating'); ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="<?php echo __('Friend', 'wpdating'); ?>" />
                                            </a>
                                        <?php
                                        else : ?>
                                            <a href="<?php echo $root_link . "edit"; ?>" title="<?php echo __('Edit Profile', 'wpdating'); ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="<?php echo __('Friend', 'wpdating'); ?>" />
                                            </a>
                                        <?php
                                        endif; ?>
                                    <?php else : ?>
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="<?php echo __('Login', 'wpdating'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>friends.jpg" border="0" alt="<?php echo __('Friend', 'wpdating'); ?>" />
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <?php endif; // END My friends module Activation check condition ?>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border">
                                    <?php if (is_user_logged_in()) : ?>
                                            <a href="<?php echo $root_link . "add_favorites/user_id/" . $current_user_id . "/fav_userid/" . $search_member->user_id . "/"; ?>"
                                               title="<?php echo __('Add to Favorites', 'wpdating'); ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" alt="<?php echo __('Star', 'wpdating'); ?>" />
                                            </a>
                                    <?php else : ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="<?php echo __('Login', 'wpdating'); ?>">
                                                <img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" alt="<?php echo __('Star', 'wpdating'); ?>"/>
                                            </a>
                                    <?php endif; ?>
                                </div>
                            </li>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border" >
                                <?php
                                if (is_user_logged_in()) :
                                    $result = check_contact_permissions($search_member->user_id);
                                    if (isset($check_my_friends_list) && $check_my_friends_list > 0) : ?>
                                        <a <?php echo !$result ? 'href="javascript:void(0);" onclick="javascript:show_contact_message();"' :
                                                    'href="' . $root_link . 'email/compose/frnd_id/' . $search_member->user_id . '/Act/send_msg/"'; ?>
                                                title="<?php echo __('Send Message', 'wpdating'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" alt="<?php echo __('Mail', 'wpdating'); ?>" />
                                        </a>
                                    <?php
                                    else : ?>
                                        <a <?php echo !$result ? 'href="javascript:void(0);" onclick="javascript:show_contact_message();"' :
                                            'href="' . $root_link . 'email/compose/receive_id/' . $search_member->user_id .'"'; ?>
                                                title="<?php echo __('Send Message', 'wpdating'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" alt="<?php echo __('Mail', 'wpdating'); ?>" />
                                        </a>
                                    <?php
                                    endif;
                                else : ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="<?php echo __('Login', 'wpdating'); ?>">
                                        <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" alt="<?php echo __('Mail', 'wpdating'); ?>" />
                                    </a>
                                <?php
                                endif; ?>
                                </div>
                            </li>
                            <?php if ($check_flirt_module->setting_status == 'Y') : // Check FLIRT (WINK) module Activated or not  ?>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border">
                                    <?php
                                    if (is_user_logged_in()) :  // CHECK MEMBER LOGIN
                                        if ($check_user_profile_exist > 0) :  // check user dating profile exist or not ?>
                                        <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $search_member->user_id . "/"; ?>'
                                           title="<?php echo __('Send Wink', 'wpdating'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo __('Wink', 'wpdating'); ?>" />
                                        </a>
                                        <?php
                                        else : ?>
                                        <a href="<?php echo $root_link . "edit"; ?>" title="<?php echo __('Edit Profile', 'wpdating'); ?>">
                                            <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo __('Wink', 'wpdating'); ?>"/>
                                        </a>
                                        <?php
                                        endif; ?>
                                    <?php
                                    else : ?>
                                    <a href="<?php echo wp_login_url(get_permalink()); ?>" title="<?php echo __('Login', 'wpdating'); ?>">
                                        <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" alt="<?php echo __('Wink', 'wpdating'); ?>"/>
                                    </a>
                                    <?php
                                    endif; ?>
                                </div>
                            </li>
                            <?php endif; // END My friends module Activation check condition  ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <?php
            endforeach; ?>
    </div>
</div>
<div class="row-paging">
    <div style="float:left; width:100%;">
            <?php
    // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
            echo $pagination
    // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
            ?>
        </div>  
</div>
<?php endif; ?>