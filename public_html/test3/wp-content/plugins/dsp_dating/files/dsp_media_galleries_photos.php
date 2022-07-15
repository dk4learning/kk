<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");

$dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
$dsp_galleries_photos           = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
$dsp_email_templates_table      = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_admin_emails_table         = $wpdb->prefix . DSP_ADMIN_EMAILS;
$posts_table                    = $wpdb->prefix . POSTS;
$dsp_general_settings           = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM {$dsp_general_settings} WHERE setting_name='member_page_id'");
$member_pageid        = $member_page_title_ID->setting_value;
$post_page_title_ID   = $wpdb->get_row("SELECT * FROM {$posts_table} WHERE ID='{$member_pageid}'");
$member_page_id       = $post_page_title_ID->ID;  // Print Site root link
$root_url             = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$messae_send_date     = date('Y-m-d H:i:s');
$get_image_Id         = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
$Action               = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';

// ###########################  delete approve image ########################################
if ($Action == "Delete" && !empty($get_image_Id)) {
    $fetch_gallery_pic = $wpdb->get_row("SELECT * FROM {$dsp_galleries_photos} Where gal_photo_id='{$get_image_Id}'");
    if ($get_image_Id != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $fetch_gallery_pic->user_id . '/album_' . $fetch_gallery_pic->album_id;
        $delete_picture = $directory_path . "/" . $fetch_gallery_pic->image_name;
        unlink($delete_picture);
        $wpdb->query("DELETE FROM $dsp_galleries_photos WHERE gal_photo_id = '$get_image_Id'");
    } // if($imageid!="")
} // if($_GET['Action']=="Delete")   
// ###########################  delete approve image ########################################

// ###########################  delete image ########################################
if ($Action == "Del" && !empty($get_image_Id)) {
    $fetch_gallery_pic = $wpdb->get_row("SELECT * FROM {$dsp_galleries_photos} Where gal_photo_id='{$get_image_Id}'");
    if ($get_image_Id != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $fetch_gallery_pic->user_id . '/album_' . $fetch_gallery_pic->album_id;
        $delete_picture = $directory_path . "/" . $fetch_gallery_pic->image_name;
        unlink($delete_picture);
        $wpdb->query("DELETE FROM {$dsp_galleries_photos} WHERE gal_photo_id = '{$get_image_Id}'");
    } // if($imageid!="")
} // if($_GET['Action']=="Del")

// ###########################  Approve Image ########################################
if ($Action == "approve" && !empty($get_image_Id)) {
    $fetch_gallery_user = $wpdb->get_row("SELECT * FROM {$dsp_galleries_photos} Where gal_photo_id='{$get_image_Id}'");
    $gallery_user_id    = $fetch_gallery_user->user_id;

    $wpdb->query("UPDATE {$dsp_galleries_photos} SET status_id='1' WHERE gal_photo_id='{$get_image_Id}'");
    dsp_add_news_feed($gallery_user_id, 'gallery_photo', $get_image_Id);
    dsp_add_notification($gallery_user_id, 0, 'gallery_photo');

    $email_template  = $wpdb->get_row("SELECT * FROM {$dsp_email_templates_table} WHERE mail_template_id='5'");
    $sender_details  = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
    $reciver_details = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='{$gallery_user_id}'");

    $sender_name            = $sender_details->display_name;
    $reciver_name           = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $url                    = site_url();

    $email_subject          = $email_template->subject;
    $mem_email_subject      = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;
    $admin_email        = get_option('admin_email');
    $from               = $admin_email;
    dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
    $wpdb->query("INSERT INTO {$dsp_admin_emails_table} SET rec_user_id='{$gallery_user_id}',email_template_id='5', message='{$MemberEmailMessage}',mail_sent_date='$messae_send_date'");
}

// ###########################  Reject Image ########################################
if ($Action == "reject" && !empty($get_image_Id)) {

    $fetch_member = $wpdb->get_row("SELECT * FROM {$dsp_galleries_photos} Where gal_photo_id ='{$get_image_Id}'");
    $wpdb->query("UPDATE {$dsp_galleries_photos} SET status_id='2' WHERE gal_photo_id='{$get_image_Id}'");

    $email_template  = $wpdb->get_row("SELECT * FROM {$dsp_email_templates_table} WHERE mail_template_id='6'");
    $sender_details  = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
    $reciver_details = $wpdb->get_row("SELECT * FROM {$dsp_user_table} WHERE ID='{$fetch_member->gal_user_id}'");

    $reciver_name           = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_name            = $sender_details->display_name;
    $url                    = site_url();

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
    $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
    $wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$fetch_member->gal_user_id',email_template_id='6', message='$MemberEmailMessage',mail_sent_date='$messae_send_date'");
}

?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
    <tr><td colspan="5">
            <div id="general" class="postbox" >
                <h3 class="hndle">
                    <span>
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
                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <?php
                        $page_name        = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=gallery_photos&status={$list_status}";
                        $pagination       = "";
                        $search_sql_query = "";

                        if ( isset($_GET['search'])) {
                            $page_name        .= "&username={$_GET['username']}&search=Search";
                            $search_sql_query .= " AND user.user_login LIKE '%{$_GET['username']}%'";
                        }

                        $sql_query = "SELECT COUNT(*) AS num FROM {$dsp_galleries_photos} gallery_photo
                                            JOIN {$wpdb->users} user
                                            ON gallery_photo.user_id = user.ID
                                            LEFT JOIN {$dsp_user_profiles} user_profile
                                            ON gallery_photo.user_id = user_profile.user_id
                                            WHERE gallery_photo.status_id='{$list_status}' {$search_sql_query} ORDER BY gallery_photo.date_added DESC";

                        $total_results1 = $wpdb->get_var($sql_query);

                        if ( $total_results1 > 0 ) {
                            // ---------------------------------------- PAGING CODE  ------------------------------------------------ //
                            $page        = (isset($_GET['page1'])) ? $_GET['page1'] : 1;

                            // How many adjacent pages should be shown on each side?
                            $adjacents = 2;
                            $limit     = 20;
                            $start     = ($page - 1) * $limit;
                            $next      = $page + 1;
                            $lastpage  = ceil($total_results1 / $limit);
                            $lpm1      = $lastpage - 1;

                            $pagination = "";
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

                            $required_fields = "gallery_photo.gal_photo_id gallery_image_id, gallery_photo.image_name gallery_image_name,
                                                    gallery_photo.album_id gallery_album_id, user.ID user_id, user.user_login user_name, user_profile.gender user_gender";
                            $sql_query       = str_replace('COUNT(*) AS num', $required_fields, $sql_query);
                            $sql_query       .= " LIMIT {$start}, {$limit}";

                            $gallery_photos = $wpdb->get_results($sql_query);

                            $i = 0;
                            foreach ($gallery_photos as $gallery_photo) {
                                $image_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_photos/user_" . $gallery_photo->user_id . "/album_" . $gallery_photo->gallery_album_id . "/" . $gallery_photo->gallery_image_name;

                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($gallery_photo->user_gender == 'C') {
                                        $user_url = $root_url . $gallery_photo->user_name . "/my_profile/";
                                    } else {
                                        $user_url = $root_url . $gallery_photo->user_name . "/";
                                    }
                                } else {
                                    $user_url = $root_url . $gallery_photo->user_name . "/";
                                }

                                if (($i % 5) == 0) {
                                ?>
                                    </tr>
                                    <tr>
                                <?php
                                } ?>
                                        <td align="left" class="thumbnails-bg-pix">
                                            <table cellpadding="0" border="0">
                                                <tr><td align="center" colspan="3"><a href="<?php echo $user_url; ?>"><?php echo $gallery_photo->user_name; ?></a></td></tr>
                                                <tr><td align="center" colspan="3"><a class="group1" href="<?php echo $image_path ?>"><img src="<?php echo $image_path ?>"  title="<?php echo $gallery_photo->user_name; ?>"  width="100px"  alt="<?php echo $gallery_photo->user_name; ?>"/></a></td></tr>
                                                <tr>
                                                    <?php if ($list_status == 0 || $list_status == 2) : ?>
                                                    <td><span onclick="approve_images('<?php echo $gallery_photo->gallery_image_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Approve', 'wpdating') ?></span>|</td>
                                                    <?php endif; ?>
                                                    <?php if ($list_status == 0) : ?>
                                                    <td><span onclick="reject_images('<?php echo $gallery_photo->gallery_image_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Reject', 'wpdating') ?></span>|</td>
                                                    <?php endif; ?>
                                                    <?php if ($list_status == 1) : ?>
                                                    <td><span onclick="delete_approve_images('<?php echo $gallery_photo->gallery_image_id; ?>')" class="span_pointer" style="font-size:12px;margin-left:22px; text-decoration:none;"><input name="<?php echo __('Delete', 'wpdating'); ?>" type="button" value="<?php echo __('Delete', 'wpdating'); ?>" class="button" /></span></td>
                                                    <?php else : ?>
                                                    <td><span onclick="delete_images('<?php echo $gallery_photo->gallery_image_id; ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Delete', 'wpdating'); ?></span></td></tr>
                                                    <?php endif; ?>
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
        <td align="center">
            <div class="paging-box">
                <?php
                // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                echo $pagination
                // -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>
            </div> 
        </td>
    </tr>
    <tr>
        <td>
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
                <h3 class="hndle"><span><?php echo __("Username Search", "wpdating"); ?></span></h3>
                <div class="dsp_membership_wrap">
                    <form name="searchfrm" action="" method="GET">
                        <br>
                        <div class="dsp_membership_active_col"></div>
                        <input type="hidden" name="page" value="dsp-admin-sub-page2"/>
                        <input type="hidden" name="pid" value="gallery_photos"/>
                        <input type="hidden" name="status" value="<?php echo $list_status; ?>"/>
                        <div class="dsp_membership_col1"><?php echo __("Username", "wpdating"); ?> :</div>
                        <div class="dsp_membership_col2"><input name="username" type="text" /></div>
                        <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="<?php echo __("Search", "wpdating"); ?>"/></div>
                        <div class="dsp_clr"></div>
                        <br />
                    </form>
                </div>
            </div>
        </td>
    </tr>
</table>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>