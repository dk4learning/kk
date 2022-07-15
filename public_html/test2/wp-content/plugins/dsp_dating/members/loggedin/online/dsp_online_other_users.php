<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
global $wp_query;
$page_id = $wp_query->post->ID; //fetch post query string id
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$posts_table = $wpdb->prefix . POSTS;
$insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
$wpdb->query($insertMemberPageId);
$posts_table = $wpdb->prefix . POSTS;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
// ROOT PATH
$pieces = explode('/', $_SERVER['REQUEST_URI']);
$page_index = array_search('page', $pieces);

//if (get('page')) $page = get('page'); else $page = 1;
$page = isset($_REQUEST['page']) ? esc_sql($_REQUEST['page']) : $pieces[$page_index + 1];
if($page_index == false)
    $page=0;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = !empty($check_search_result->setting_value) ? $check_search_result->setting_value : 12;
if ($page > 1)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
//var_dump($start);
// ------------------------------------------------End Paging code------------------------------------------------------ 
$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get('gender');
$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');

$page_name = $root_link . "online_members/show/display/gender/";
$page_name .= !empty($gender) ?   'gender/' . $gender . "/": '';
$page_name .= !empty($age_from) ? 'age_from/' . $age_from . "/"  : '';
$page_name .= !empty($age_to) ?   'age_to/' . $age_to . "/" : '';
$filters = '';
$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$random_online_status = $check_online_member_mode->setting_status;
$online_member = array();
$filters = array(
            'age_from' => $age_from,
            'age_to' => $age_to,
            'gender' => $gender,
            'start' => $start,
            'last' => $limit,
        );
if($random_online_status == 'Y')
{
    $random_online_number = $check_online_member_mode->setting_value;
    $online_member = dsp_randomOnlineMembers($random_online_number,$filters);
}else
{   
   $online_member = dsp_getOnlineMembers($filters);
}
$user_count = dsp_getTotalOnlineUsers(false,$random_online_status);
$total_results1 = $user_count;
if($total_results1 > $limit){
    ###### Pagination sections ###### 
    $pagination =  dsp_pagination($total_results1,$limit, $page, $adjacents,$page_name); 
    ###  End Paging code  ##########
}

//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>
</div>
<?php if($total_results1 > 0): ?>
<div class="heading-submenu dsp-block" style="display:none"><?php echo __('Online', 'wpdating') ?></div>
<div class="box-border">
    <div class="box-pedding">
        <div class="dsp-form-container" style="">
            <div class="content-search dsp-search-online-members">
                <form action="<?php echo $root_link . "online_members/" ?>" method="post" class="dspdp-form-inline">
                    <div class="dsp-form-group" align="center">
                        <span class="dsp-md-2 dsp-control-label"><?php echo __('Gender:', 'wpdating') ?></span>
                        <span class="dsp-md-2 gender-select">
                            <select name="gender" class="dspdp-form-control">
                                <option value="all" <?php if ($gender == 'all' || isset($_REQUEST['show'])) { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                                <?php 
                                    $gender = isset($gender) && !empty($gender) ? $gender : $userProfileDetails->gender;
                                    echo get_gender_list($gender); 
                                ?>
                            </select>
                        </span> 
                        <span class="dsp-md-1 dsp-control-label"><?php echo __('Age:', 'wpdating') ?></span>
                        <span class="dsp-md-2">
                            <select name="age_from" class="dspdp-form-control">
                                <!-- <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                    <option value="<?php echo $i ?>" <?php echo  $i == $age_from ? 'selected="selected"': '';?>><?php echo $i ?></option>
                                <?php } ?> -->
                                <?php
                                for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
                                    if ($fromyear == $min_age_value) {
                                        ?>
                                        <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </span> 
                        <span class="dsp-md-1 dsp-control-label"><?php echo __('to:', 'wpdating') ?></span> 
                        <span class="dsp-md-2">
                            <select  name="age_to" class="dspdp-form-control dspdp-xs-form-group">
                                <!-- <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                    <option value="<?php echo $j ?>" <?php  echo $j == $age_to ? 'selected="selected"': '';?>><?php echo $j ?></option>
                                <?php } ?> -->
                                <?php
                                for ($fromyear = $max_age_value; $fromyear >= $min_age_value; $fromyear--) {
                                    if ($fromyear == $max_age_value) {
                                        ?>
                                        <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </span> 
                        <span class="dsp-md-2 filter-button-online-members">
                            <input class="dspdp-btn dspdp-btn-default pull-right" name="submit" type="submit" value="<?php echo __('Filter', 'wpdating') ?>" />
                        </span> 
                </form>

            </div>
            <div class="dspdp-seprator"></div>
            </div>
    </div>
    <div id="search_width" class="dspdp-row row dsp-member-container">
        <?php
        foreach ($online_member as $member1) {
            if ($check_couples_mode->setting_status == 'Y') {
                $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
            } else {
                $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
            }
            $s_user_id = $member->user_id;
            $s_country_id = $member->country_id;
            $s_gender = $member->gender;
            $s_seeking = $member->seeking;
            $s_state_id = $member->state_id;
            $s_city_id = $member->city_id;
            $s_age = GetAge($member->age);
            $s_make_private = $member->make_private;
            $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
            //$s_user_pic = $member->user_pic;
            $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
            $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
            $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
            $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
            $favt_mem = array();
            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
            foreach ($private_mem as $private) {
                $favt_mem[] = $private->favourite_user_id;
            }
            ?>
            <div class="dspdp-col-sm-4 dsp-sm-3  dspdp-col-xs-6 dsp-test">
                <div class="box-search-result image-container">
                    <div class="img-box dspdp-spacer circle-image">
                        <span class="online dspdp-online-status">
                                   <?php
                                   $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
                                   $check_online_user = ($stealth_mode == "Y") ? '0' : $check_online_user;
                                   ?>
                             <?php
                                //echo $fav_icon_image_path;
                                if ($check_online_user > 0)
                                    echo '<span class="dspdp-status-on" '.__('Online', 'wpdating').'></span>';
                                else
                                    echo '<span class="dspdp-status-off" '.__('Offline', 'wpdating').'></span>';
                                ?>
                                </span>
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >               
                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/>
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                                <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"     border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                        </a>
                                    <?php } ?>
                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                    </a>
                                <?php } ?>
                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($s_make_private == 'Y') { ?>
                                <?php if ($current_user->ID != $s_user_id) { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  border="0" class="img-big" alt="Private Photo" />
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                            <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>"    border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo_thumb($s_user_id, $imagepath); ?>" border="0" class="img-big" alt="<?php echo get_username($s_user_id); ?>" />
                                </a>
                            <?php } ?>

                        <?php } ?>

                    </div>
                    <div class="user-status dspdp-h5 dspdp-username dsp-username">

                        <span class="user-name"><strong>

                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <?php
                                            if (strlen($displayed_member_name->display_name) > 15)
                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                            else
                                                echo $displayed_member_name->display_name;
                                            ?>                
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                            <?php } ?>
                                        </a>
                                    </a></strong></span>
                    </div>
                    <div class="user-details dspdp-spacer dspdp-user-details dsp-user-details">
                        <?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($s_gender); ?> <?php echo __('from', 'wpdating'); ?> <br />
                        <?php echo $city_name ? $city_name->name . ', ' : ''; ?>
                        <?php echo $state_name ? $state_name->name . ', ' : ''; ?>
                        <?php echo $country_name ? $country_name->name : ''; ?>
                    </div>
                    <div class="user-links dsp-none">
                        <ul class="dspdp-row">
                            <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not  ?>
                                <li class="dspdp-col-xs-3">
                                    <div class="dsp_fav_link_border">
                                        <?php
                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                ?>
                                                <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo __('Add to Friends', 'wpdating'); ?>">
                                                    <span class="fa fa-user"></span></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-user"></span></a> 
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-user"></span></a>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } // END My friends module Activation check condition ?>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border">
                                    <?php
                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                        ?>
                                        <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $s_user_id . "/"; ?>" title="<?php echo __('Add to Favorites', 'wpdating'); ?>"><span class="fa fa-heart"></span></a>
                                    <?php } else { ?>
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-heart"></span></a>
                                    <?php } ?>
                                </div>
                            </li>
                            <li class="dspdp-col-xs-3">
                                <div class="dsp_fav_link_border" >
                                    <?php
                                    if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                        if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                            ?>
                                            <a <?php
                                            $result = check_contact_permissions($s_user_id);
                                            if (!$result) {
                                                ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>" <?php } ?>  title="<?php echo __('Send Message', 'wpdating'); ?>">
                                                <span class="fa fa-envelope-o"></span></a>
                                        <?php } else { ?>
                                            <a <?php
                                            $result = check_contact_permissions($s_user_id);
                                            if (!$result) {
                                                ?> href="javascript:void(0);" onclick="javascript:show_contact_message();" <?php } else { ?> href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>" <?php } ?>  title="<?php echo __('Send Message', 'wpdating'); ?>">
                                                <span class="fa fa-envelope-o"></span></a>
                                        <?php } //if($check_my_friends_list>0)    ?>
                                    <?php } else { ?>
                                        <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-envelope-o"></span></a>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not  ?>
                                <li class="dspdp-col-xs-3">
                                    <div class="dsp_fav_link_border">
                                        <?php
                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                ?>
                                                <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo __('Send Wink', 'wpdating'); ?>">
                                                    <span class="fa fa-smile-o"></span></a>
                                            <?php } else { ?>
                                                <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-smile-o"></span></a>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-envelope-o"></span></a>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } // END My friends module Activation check condition ?> 
                        </ul>
                    </div>
                </div>
            </div>
        <?php }
        ?>
    </div>
</div>
</div>

<div class="row-paging"> 
    <div style="float:left; width:100%;">
        <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
        echo $total_results1 > $limit ? $pagination : '';
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
        ?>
    </div>  
</div>
<?php else : ?>
    <div class="box-border">
            <div class="box-pedding">
                <div class="page-not-found">
                    <?php echo __('No record found.', 'wpdating'); ?><br /><br />
                </div>
            </div>
    </div>
<?php endif; ?>

