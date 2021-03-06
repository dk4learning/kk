<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once(WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php");
$dsp_tmp_member_audios_table = $wpdb->prefix . DSP_TEMP_MEMBER_AUDIOS_TABLE;
$dsp_member_audios = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_admin_emails_table = $wpdb->prefix . DSP_ADMIN_EMAILS;
$message_send_date = date('Y-m-d H:i:s');
$posts_table = $wpdb->prefix . POSTS;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$member_page_title_ID = $wpdb->get_row("SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'");
$member_pageid = $member_page_title_ID->setting_value;
$post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$member_pageid'");
$member_page_id = $post_page_title_ID->ID;  // Print Site root link
$root_url = get_bloginfo('url') . "/" . $post_page_title_ID->post_name . "/";
$get_audio_Id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
$Action = isset($_REQUEST['Action']) ? $_REQUEST['Action'] : '';
// ########################### Delete Approve Audio ########################################
if ($Action == "Delete" && !empty($get_audio_Id)) {
    $fetch_audio = $wpdb->get_row("SELECT * FROM $dsp_member_audios Where audio_file_id='$get_audio_Id'");
    $fetch_audio->file_name;
    $fetch_audio->user_id;
    if ($get_audio_Id != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_audios/user_' . $fetch_audio->user_id;
        $delete_audio = $directory_path . "/" . $fetch_audio->file_name;
        unlink($delete_audio);
        $wpdb->query("DELETE FROM $dsp_member_audios WHERE audio_file_id = '$get_audio_Id'");
    } // if($get_audio_Id!="")
} // if($_GET['Action']=="Del")   
// ########################### Delete Approve Audio ########################################
// ###########################  delete Audio ########################################
if ($Action == "Del" && !empty($get_audio_Id)) {
    $fetch_audio = $wpdb->get_row("SELECT * FROM $dsp_member_audios Where audio_file_id='$get_audio_Id'");
    $fetch_audio->file_name;
    $fetch_audio->user_id;
    if ($get_audio_Id != "") {
        $directory_path = ABSPATH . '/wp-content/uploads/dsp_media/user_audios/user_' . $fetch_audio->user_id;
        $delete_audio = $directory_path . "/" . $fetch_audio->file_name;
        unlink($delete_audio);
        $wpdb->query("DELETE FROM $dsp_tmp_member_audios_table WHERE t_audio_id = '$get_audio_Id'");
        $wpdb->query("DELETE FROM $dsp_member_audios WHERE audio_file_id = '$get_audio_Id'");
    } // if($get_audio_Id!="")
} // if($_GET['Action']=="Del")   
// ###########################  Approve Audio ########################################
if ($Action == "approve" && !empty($get_audio_Id)) {
    $fetch_audio_user = $wpdb->get_row("SELECT * FROM $dsp_member_audios Where audio_file_id='$get_audio_Id'");
    $audio_user_id = $fetch_audio_user->user_id;

    $wpdb->query("UPDATE $dsp_member_audios SET status_id='1' WHERE audio_file_id='$get_audio_Id'");
    $wpdb->query("DELETE from $dsp_tmp_member_audios_table where t_audio_id ='$get_audio_Id'");
    dsp_add_news_feed($audio_user_id, 'audio');
    dsp_add_notification($audio_user_id, 0, 'audio');

    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='10'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$audio_user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
    $sender_name = $sender_details->display_name;
    $url = '<a href= "'. ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
    $email_subject = $email_template->subject;
    $mem_email_subject = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    // dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
    $wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$audio_user_id',email_template_id='10', message='$MemberEmailMessage',mail_sent_date='$message_send_date'");
}
// ###########################  Reject Audio ########################################

if ($Action == "reject" && !empty($get_audio_Id)) {

    $fetch_member = $wpdb->get_row("SELECT * FROM $dsp_tmp_member_audios_table Where t_audio_id ='$get_audio_Id'");
    $wpdb->query("UPDATE $dsp_tmp_member_audios_table SET t_status_id='2' WHERE t_audio_id='$get_audio_Id'");

    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='11'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$fetch_member->t_user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='1'");
    $sender_name = $sender_details->display_name;
    $url = '<a href= "'.ROOT_LINK . $sender_details->user_login. '">'.$sender_name.'</a>';
    $email_subject = $email_template->subject;
    $mem_email_subject = $email_subject;

    $email_message = $email_template->email_body;
    $email_message = str_replace("<#RECEIVER_NAME#>", $reciver_name, $email_message);
    $email_message = str_replace("<#SENDER_NAME#>", $sender_name, $email_message);
    $email_message = str_replace("<#URL#>", $url, $email_message);

    $MemberEmailMessage = $email_message;
    $admin_email = get_option('admin_email');
    $from = $admin_email;
    // dsp_send_email($receiver_email_address, $from, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");


    $wpdating_email  = Wpdating_email_template::get_instance();
    $result = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
    $wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$fetch_member->t_user_id',email_template_id='11', message='$MemberEmailMessage',mail_sent_date='$message_send_date'");
}


// ---------------------------------------- PAGING CODE  ------------------------------------------------ //
$page_name = $root_link . "/wp-admin/admin.php?page=dsp-admin-sub-page2&pid=media_audios&audio_status=$audio_list_status";
if (isset($_GET['page1']))
    $page = $_GET['page1'];
else
    $page = 1;

// How many adjacent pages should be shown on each side?
$adjacents = 2;
$limit = 20;
if ($page)
    $start = ($page - 1) * $limit;    //first item to display on this page
else
    $start = 0;
// -----------------------------------------------Paging code------------------------------------------------------ //
if ($audio_list_status == 1) {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_member_audios WHERE status_id = '$audio_list_status'");
} else {
    $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_tmp_member_audios_table WHERE t_status_id='$audio_list_status'");
}
//$total_pages1 = ceil($total_results1 / $max_results1); 
//******************************************************************************************************************************************

if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_results1 / $limit);
;  //lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;

/*
  Now we apply our rules and draw the pagination object.
  We're actually saving the code to a variable in case we want to draw it more than once.
 */
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
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr><td colspan="5"><div id="general" class="postbox" >
                <h3 class="hndle"><span>
                        <?PHP
// ------------ DISPLAY HEADING  ------------ //
                        if ($audio_list_status == 0) {
                            echo "Audios Waiting to Approve";
                        } else if ($audio_list_status == 1) {
                            echo "Approved Audios";
                        } else if ($audio_list_status == 2) {
                            echo "Rejected Audios";
                        }
// ----------- DISPLAY HEADING  ----------- //
                        ?>
                    </span></h3>
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <?php
                        if ($audio_list_status == 1) {
                            if (isset($_POST['search'])) {
                                $username = $_POST['username'];
                                if ($username != '') {
                                    $search_username = $wpdb->get_results("SELECT * FROM $dsp_user_table WHERE user_login like '%$username%'");
                                    foreach ($search_username as $username) {
                                        $user_id = $username->ID;

                                        $myrows = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE status_id = '$audio_list_status' AND user_id='$user_id' LIMIT $start, $limit");
                                    }
                                } else {
                                    $myrows = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE status_id = '$audio_list_status' LIMIT $start, $limit");
                                }
                            }   //if(isset($_POST['search']))
                            else
                                $myrows = $wpdb->get_results("SELECT * FROM $dsp_member_audios WHERE status_id = '$audio_list_status' LIMIT $start, $limit");
                        } else {
                            $myrows = $wpdb->get_results("SELECT * FROM $dsp_tmp_member_audios_table WHERE t_status_id ='$audio_list_status' LIMIT $start, $limit");
                        }
                        $i = 0;
                        foreach ($myrows as $fivefiles) {
                            if ($audio_list_status == 1) {
                                $audio_file_id = $fivefiles->audio_file_id;
                                $audio_user_id = $fivefiles->user_id;
                                $audio_file_name = $fivefiles->file_name;
                            } else {
                                $audio_file_id = $fivefiles->t_audio_id;
                                $audio_user_id = $fivefiles->t_user_id;
                                $audio_file_name = $fivefiles->t_filename;
                            }

                            $audio_username = $wpdb->get_var("select user_login from $dsp_user_table where ID=" . $audio_user_id . "");
                            $audio_file_path = get_bloginfo('url') . "/wp-content/uploads/dsp_media/user_audios/user_" . $audio_user_id . "/" . $audio_file_name;
                            $player_path = WPDATE_URL . "/flash/player_mp3.swf";
                            if (($i % 5) == 0) {
                                ?>
                            <tr>
                                <?php
                            }
                            if ($audio_list_status == 0) {
                                ?>
                                <td align="left" class="thumbnails-bg">
                                    <table cellpadding="0" border="0">
                                        <tr><td align="center" colspan="3"><table align="center" style="border:1px solid #CCCCCC;">
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td align="center">
                                                            <img src="<?php echo WPDATE_URL . '/images/music3.png'; ?>" height="50px" border="0" align="center" title="<?php echo $audio_username; ?>" alt="<?php echo $audio_username; ?>"/>
                                                        </td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>
                                                            <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">
                                                                <param name="movie" value="<?php echo $player_path ?>" />
                                                                <param name="FlashVars" value="mp3=<?php echo $audio_file_path ?>&amp;showstop=1&amp;showinfo=1" />
                                                            </object>
                                                        </td></tr>
                                                </table></td></tr>
                                        <tr>
                                            <td><span onclick="approve_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Approve', 'wpdating') ?></span>|</td>
                                            <td><span onclick="reject_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Reject', 'wpdating') ?></span>|</td>
                                            <td><span onclick="delete_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Delete', 'wpdating'); ?></span></td></tr>
                                    </table>
                                </td>
                                <?php
                            } //End if($audio_list_status==0)

                            if ($audio_list_status == 2) {
                                ?>
                                <td class="thumbnails-bg">
                                    <table cellpadding="0" border="0">
                                        <tr><td align="center" colspan="2"><table align="center" style="border:1px solid #CCCCCC;">
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td align="center">
                                                            <img src="<?php echo WPDATE_URL . '/images/music3.png'; ?>" height="50px" border="0" align="center" title="<?php echo $audio_username; ?>" alt="<?php echo $audio_username; ?>"/>
                                                        </td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>
                                                            <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">
                                                                <param name="movie" value="<?php echo $player_path ?>" />
                                                                <param name="FlashVars" value="mp3=<?php echo $audio_file_path ?>&amp;showstop=1&amp;showinfo=1" />
                                                            </object>
                                                        </td></tr>
                                                </table></td></tr>
                                        <tr>
                                            <td><span onclick="approve_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Approve', 'wpdating') ?></span>&nbsp;&nbsp;&nbsp;|</td>
                                            <td><span onclick="delete_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;"><?php echo __('Delete', 'wpdating') ?></span></td></tr>
                                    </table>
                                </td>
                                <?php
                            } //End if($audio_list_status==2)

                            if ($audio_list_status == 1) {
                                ?>
                                <td class="thumbnails-bg">
                                    <table cellpadding="0" border="0">
                                        <tr><td align="center" colspan="2">
                                                <table align="center" style="border:1px solid #CCCCCC;">
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td align="center">
                                                            <img src="<?php echo WPDATE_URL . '/images/music3.png'; ?>" height="50px" border="0" align="center" title="<?php echo $audio_username; ?>" alt="<?php echo $audio_username; ?>"/>
                                                        </td></tr>
                                                    <tr><td>&nbsp;</td></tr>
                                                    <tr><td>
                                                            <object type="application/x-shockwave-flash" data="<?php echo $player_path ?>" width="150px" height="25">
                                                                <param name="movie" value="<?php echo $player_path ?>" />
                                                                <param name="FlashVars" value="mp3=<?php echo $audio_file_path ?>&amp;showstop=1&amp;showinfo=1" />
                                                            </object>
                                                        </td></tr>
                                                </table>
                                            </td></tr>
                                        <tr>   <td><span onclick="delete_approve_audio('<?php echo $audio_file_id ?>')" class="span_pointer" style="font-size:12px;margin-left:55px; text-decoration:none;">
                                                    <input class="button" name="<?php echo __('Delete', 'wpdating'); ?>" type="button" value="<?php echo __('Delete', 'wpdating'); ?>" /></span></td></tr>
                                    </table>
                                </td>
                                <?php
                            } //End if($audio_list_status==1)

                            $i++;
                        } //foreach ($myrows as $fivefiles)
                        ?>
                    </tr>
                    <tr><td colspan="5">&nbsp;</td></tr>
                    <tr><td colspan="5">&nbsp;</td></tr>
                </table>
        </td></tr>
    <tr><td  align="right" height="20px">
            <div class="paging-box">
                <?php
                // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                echo $pagination
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                ?>
            </div>  
        </td></tr>
    <tr><td>
            <?php if (isset($_REQUEST['audio_status']) && $_REQUEST['audio_status'] == 1) { ?>	
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
                        <form name="searchfrm" action="" method="post">
                            <br>
                            <div class="dsp_membership_active_col"></div>
                            <div class="dsp_membership_col1">Username :</div>
                            <div class="dsp_membership_col2"><input name="username" type="text" /></div>
                            <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="Search"/></div>
                            <div class="dsp_clr"></div>
                            <?php
                            $dsp_membership_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
                            $myrows = $wpdb->get_results("SELECT * FROM $dsp_membership_table Order by name");
                            ?>
                            <div class="dsp_clr"></div>
                            <br />
                        </form>
                    </div></div>
            <?php } ?>
        </td></tr>
</table>
</td></tr></table>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>