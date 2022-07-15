<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");
global $wpdb;
$dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
$dsp_members_photos           = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_email_templates_table    = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_admin_emails_table       = $wpdb->prefix . DSP_ADMIN_EMAILS;
$dsp_user_profiles_table      = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$posts_table                  = $wpdb->prefix . POSTS;
$dsp_general_settings         = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID         = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid                = $member_page_title_ID->setting_value;
$post_page_title_ID           = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id               = $post_page_title_ID->ID;  // Print Site root link
$root_url                     = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$messae_send_date             = date('Y-m-d H:i:s');
$member_ids                   = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
$Action                       = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
$pagination                   = "";

// ###########################  delete approve image ########################################
if ($Action == "Delete" && !empty($member_ids)) {
    $fetch_member_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos Where user_id='$member_ids'");

    if ($member_ids != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $member_ids;
        $delete_picture = $directory_path . "/" . $fetch_member_picture->picture;
        unlink($delete_picture);
        $wpdb->query("DELETE from $dsp_members_photos where user_id='$member_ids'");
    } // if($member_ids!="")
} // if(Action=="Delete")   
// ###########################  delete approve image ########################################

// ###########################  delete image ########################################
if ($Action == "Del" && !empty($member_ids)) {
    $fetch_member_pic = $wpdb->get_row("SELECT * FROM {$dsp_tmp_members_photos_table} Where t_user_id='{$member_ids}'");
    $fetch_member_pic->t_picture;

    if ($member_ids != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $member_ids;
        $delete_picture = $directory_path . "/" . $fetch_member_pic->t_picture;
        $delete_thumb_picture1 = $directory_path . "/thumbs/thumb_" . $fetch_member_pic->t_picture;
        $delete_thumb_picture2 = $directory_path . "/thumbs1/thumb_" . $fetch_member_pic->t_picture;
        unlink($delete_picture);
        unlink($delete_thumb_picture1);
        unlink($delete_thumb_picture2);
        $wpdb->query("DELETE from $dsp_tmp_members_photos_table where t_user_id='$member_ids'");
    } // if($member_ids!="")
} // if(Action=="Del")

// ###########################  Approve Image ########################################
if ($Action == "approve" && !empty($member_ids)) {
    $fetch_member_pic = $wpdb->get_row("SELECT * FROM {$dsp_tmp_members_photos_table} Where t_user_id='{$member_ids}'");
    $picture          = $fetch_member_pic->t_picture;
    $exist_photo = $wpdb->get_var("SELECT COUNT(*) as Num FROM {$dsp_members_photos} where user_id='{$user_id}'");
    if ($exist_photo > 0) {
        $wpdb->query("UPDATE {$dsp_members_photos} SET picture='{$picture}',status_id='1' WHERE user_id='{$member_ids}'");
        $wpdb->query("DELETE from {$dsp_tmp_members_photos_table} where t_user_id='{$member_ids}'");
    } else {
        $wpdb->query("INSERT INTO {$dsp_members_photos} SET user_id='{$member_ids}',picture='{$picture}',status_id='1'");
        $wpdb->query("DELETE from {$dsp_tmp_members_photos_table} where t_user_id='{$member_ids}'");
    }

    dsp_add_news_feed($member_ids, 'profile_photo');
    dsp_add_notification($member_ids, 0, 'profile_photo');

    $email_template  = $wpdb->get_row("SELECT * FROM {$dsp_email_templates_table} WHERE mail_template_id='5'");
    $sender_details  = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='1'");
    $reciver_details = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='{$member_ids}'");

    $reciver_name           = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_name            = $sender_details->display_name;

    $url               = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
    $email_subject     = $email_template->subject;
    $mem_email_subject = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;
    $admin_email        = get_option('admin_email');
    $from               = $admin_email;
    dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
    $wpdb->query("INSERT INTO {$dsp_admin_emails_table} SET rec_user_id='{$member_ids}',email_template_id='5', message='{$MemberEmailMessage}',mail_sent_date='{$messae_send_date}'");
}

// ###########################  Reject Image ########################################
if ($Action == "reject" && !empty($member_ids)) {

    $fetch_member_pic = $wpdb->get_row("SELECT * FROM {$dsp_tmp_members_photos_table} Where t_user_id='{$member_ids}'");
    $wpdb->query("UPDATE {$dsp_tmp_members_photos_table} SET t_status_id='2' WHERE t_user_id='{$member_ids}'");

    $email_template  = $wpdb->get_row("SELECT * FROM {$dsp_email_templates_table} WHERE mail_template_id='6'");
    $sender_details  = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='1'");
    $reciver_details = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='{$member_ids}'");

    $reciver_name           = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_name            = $sender_details->display_name;

    $url               = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
    $email_subject     = $email_template->subject;
    $mem_email_subject = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;
    $admin_email        = get_option('admin_email');
    $from               = $admin_email;

    $wpdating_email  = Wpdating_email_template::get_instance();
    $result          = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
    $wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$member_ids',email_template_id='6', message='$MemberEmailMessage',mail_sent_date='$messae_send_date'");
} ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td width="10px" colspan="5"></td></tr>
    <tr><td colspan="5"><div id="general" class="postbox" >
                <h3 class="hndle"><span>
                        <?PHP
// ------------ DISPLAY HEADING  ------------ //
                        if ($list_status == 0) {
                            echo __('Photos Waiting to be Approved', 'wpdating');
                        } else if ($list_status == 1) {
                            echo "Approved Photos";
                        } else if ($list_status == 2) {
                            echo __('Rejected Photos', 'wpdating');
                        }
// ----------- DISPLAY HEADING  ----------- //
                        ?>
                    </span>
                </h3>
                <table cellpadding="10" cellspacing="0" border="0" >
                    <?php
                    $page_name        = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=Profile_photos&status={$list_status}";
                    $search_sql_query = '';
                    if ( isset($_GET['search'])) {
                        $page_name        .= "&username={$_GET['username']}&search=Search";
                        $search_sql_query .= " AND user.user_login LIKE '%{$_GET['username']}%'";
                    }

                    if ($list_status == 1) {
                        $sql_query = "SELECT COUNT(*) Num FROM {$dsp_members_photos} member_photo 
                                         JOIN {$wpdb->users} user
                                         ON member_photo.user_id = user.ID
                                         LEFT JOIN {$dsp_user_profiles_table} user_profile
                                         ON member_photo.user_id = user_profile.user_id
                                         WHERE member_photo.status_id = '{$list_status}' {$search_sql_query}";
                    } else {
                        $sql_query = "SELECT COUNT(*) Num FROM {$dsp_tmp_members_photos_table} tmp_member_photo
                                         JOIN {$wpdb->users} user
                                         ON tmp_member_photo.t_user_id = user.ID
                                         LEFT JOIN {$dsp_user_profiles_table} user_profile
                                         ON tmp_member_photo.t_user_id = user_profile.user_id
                                         WHERE tmp_member_photo.t_status_id='{$list_status}' {$search_sql_query}";
                    }

                    $total_results1 = $wpdb->get_var($sql_query);

                    if ($total_results1 > 0) {
                        // ---------------------------------------- PAGING CODE  ------------------------------------------------ //
                        $page      = isset($_GET['page1']) ? $_GET['page1'] : 1;
                        $adjacents = 2;
                        $limit     = 20;
                        $start     = ($page - 1) * $limit;
                        $prev      = $page - 1;
                        $next      = $page + 1;
                        $lastpage  = ceil($total_results1 / $limit);
                        $lpm1      = $lastpage - 1;

                        /*
                          Now we apply our rules and draw the pagination object.
                          We're actually saving the code to a variable in case we want to draw it more than once.
                         */
                        if ($lastpage > 1) {
                            $pagination .= "<div class='wpse_pagination'>";
                            //previous button
                            if ($page > 1)
                                $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$prev\">previous</a></div>";
                            else
                                $pagination.= "<span  class='disabled'>previous</span>";

                            //pages
                            if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                                for ($counter = 1; $counter <= $lastpage; $counter++) {
                                    if ($counter == $page)
                                        $pagination.= "<span class='current'>$counter</span>";
                                    else
                                        $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                                }
                            }
                            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                                //close to beginning; only hide later pages
                                if ($page < 1 + ($adjacents * 2)) {
                                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                        if ($counter == $page)
                                            $pagination.= "<span class='current'>$counter</span>";
                                        else
                                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                                    }
                                    $pagination.= "<span>...</span>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                                }
                                //in middle; hide some front and some back
                                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                                    $pagination.= "<span>...</span>";
                                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                        if ($counter == $page)
                                            $pagination.= "<div class='current'>$counter</div>";
                                        else
                                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                                    }
                                    $pagination.= "<span>...</span>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$lpm1\">$lpm1</a></div>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=$lastpage\">$lastpage</a></div>";
                                }
                                //close to end; only hide early pages
                                else {
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=1\">1</a></div>";
                                    $pagination.= "<div><a href=\"" . $page_name . "&page1=2\">2</a></div>";
                                    $pagination.= "<span>...</span>";
                                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                        if ($counter == $page)
                                            $pagination.= "<span class='current'>$counter</span>";
                                        else
                                            $pagination.= "<div><a href=\"" . $page_name . "&page1=$counter\">$counter</a></div>";
                                    }
                                }
                            }

                            //next button
                            if ($page < $counter - 1)
                                $pagination.= "<div><a style='color:#474545' href=\"" . $page_name . "&page1=$next\">next</a></div>";
                            else
                                $pagination.= "<span class='disabled'>next</span>";
                            $pagination.= "</div>\n";
                        }

                        // ------------------------------------------------End Paging code------------------------------------------------------ //
                        if ($list_status == 1) {
                            $required_fields = "member_photo.photo_id member_photo_id, member_photo.picture member_picture,
                                                    user.ID user_id, user.user_login user_name, user_profile.gender user_gender";
                        } else {
                            $required_fields = "tmp_member_photo.t_photo_id member_photo_id, tmp_member_photo.t_picture member_picture,
                                                   user.ID user_id, user.user_login user_name, user_profile.gender user_gender";
                        }

                        $sql_query       = str_replace('COUNT(*) Num', $required_fields, $sql_query);
                        $sql_query       .= " LIMIT {$start}, {$limit}";

                        $member_photos = $wpdb->get_results($sql_query);
                        $i = 0;
                        foreach ($member_photos as $member_photo) {

                            $image_path       = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_photos/user_" . $member_photo->user_id . "/" . $member_photo->member_picture;
                            $image_thumb_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_photos/user_" . $member_photo->user_id . "/thumbs1/thumb_" . $member_photo->member_picture;

                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($member_photo->user_gender == 'C') {
                                    $user_url = $root_url . $member_photo->user_name . "/my_profile/";
                                } else {
                                    $user_url = $root_url . $member_photo->user_name . "/";
                                }
                            } else {
                                $user_url = $root_url . $member_photo->user_name . "/";
                            }

                            if (($i % 5) == 0) : ?>
                                <tr>
                            <?php endif; ?>
                                <td align="left" class="thumbnails-bg-pix">
                                    <table cellpadding="0" border="0">
                                        <tr><td align="center" colspan="3"><a href="<?php echo $user_url; ?>"><?php echo $member_photo->user_name; ?></a></td></tr>
                                        <tr><td align="center" colspan="3"><a class="group1" href="<?php echo $image_path ?>"><img src="<?php echo $image_thumb_path ?>" alt="<?php echo $member_photo->user_name; ?>" title="<?php echo $member_photo->user_name; ?>"  width="100px" /></a></td></tr>
                                        <tr>
                                            <?php if ($list_status == 0 || $list_status == 2) : ?>
                                            <td><span onclick="approve_images('<?php echo $member_photo->user_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Approve', 'wpdating') ?></span>|</td>
                                            <?php endif; ?>
                                            <?php if ($list_status == 0) : ?>
                                            <td><span onclick="reject_images('<?php echo $member_photo->user_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Reject', 'wpdating') ?></span>|</td>
                                            <?php endif; ?>
                                            <?php if ($list_status == 1) : ?>
                                                <td><span onclick="delete_approve_images('<?php echo $member_photo->user_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Delete', 'wpdating'); ?></span></td>
                                            <?php else : ?>
                                                <td><span onclick="delete_images('<?php echo $member_photo->user_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Delete', 'wpdating'); ?></span></td>
                                            <?php endif; ?>
                                        </tr>
                                    </table>
                                </td>
                             <?php
                            $i++;
                        }
                    } ?>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="paging-box">
                <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>
            </div>
        </td>
    </tr>
</table>
<style>
    .dsp_membership_wrap{
        margin-left:2px;
        padding:15px;
        width:1040px;
        display:block;
    }
    .dsp_membership_col1 {
        width:130px;
        padding-left:6px;
        float:left;
        display:block;
        height:25px;
    }
    .dsp_membership_col2 {
        height:20px;
        display:block;
        float:left;
    }
    .dsp_membership_col3 {
        width:260px;
        height:20px;
        display:block;
        float:left;
        text-align:center;
        margin-left: 10px;
    }
</style>
<div id="general" class="postbox" >

    <h3 class="hndle"><span><?php echo "Username Search"; ?></span></h3>
    <div class="dsp_membership_wrap">
        <form name="searchfrm" action="" method="GET">
            <br>
            <div class="dsp_membership_active_col"></div>
            <input type="hidden" name="page" value="dsp-admin-sub-page2"/>
            <input type="hidden" name="pid" value="Profile_photos"/>
            <input type="hidden" name="status" value="<?php echo $list_status; ?>"/>
            <div class="dsp_membership_col1"><?php echo __("Username", "wpdating"); ?> :</div>
            <div class="dsp_membership_col2"><input name="username" type="text" /></div>
            <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="<?php echo __("Search", "wpdating"); ?>"/></div>
            <div class="dsp_clr"></div>
            <br />
        </form>
    </div>
</div>