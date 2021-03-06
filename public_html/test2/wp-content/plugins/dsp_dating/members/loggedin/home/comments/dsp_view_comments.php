<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;

$dsp_online_user_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
$count_online_member   = $wpdb->get_var("SELECT COUNT(distinct oln.user_id) FROM $dsp_online_user_table oln INNER JOIN $dsp_user_profiles usr ON ( usr.user_id = oln.user_id ) WHERE oln.status = 'Y' AND usr.country_id !=0 AND usr.stealth_mode = 'N'");
$count_online_member   = isset($count_online_member) ? $count_online_member : '';

$action = get('Action');
$comment_id = get('comment_Id');
if ($action == 'Del') {
    $check_comment = $wpdb->get_var("SELECT count(*) FROM $dsp_comments_table  WHERE comments_id = '$comment_id'");
    if ($check_comment != 0) {
        $delete = $wpdb->query("delete from $dsp_comments_table  WHERE comments_id = '$comment_id' ");
        $delete_comment_msg = "Comment has been Deleted";
    }
    ?><script>location.href = "<?php echo $root_link . "home/comments/"; ?>"</script><?php
}
if ($action == 'approve') {
    $check_comment = $wpdb->get_var("SELECT count(*) FROM $dsp_comments_table  WHERE comments_id = '$comment_id'");
    if ($check_comment != 0) {
        $wpdb->query("update $dsp_comments_table set status_id=1 WHERE comments_id = '$comment_id' ");
        $result = "Comment has been Approved";
    }
    ?><script>location.href = "<?php echo $root_link . "home/comments/"; ?>"</script><?php
}
?>
<?php if (isset($delete_comment_msg) && $delete_comment_msg != "") { ?>
    <div class="thanks">
        <p align="center" class="error"><?php echo $delete_comment_msg ?></p>
    </div>
    <?php
}
if (isset($result) && $result != "") {
    ?><div class="thanks">
        <p align="center" class="error"><?php echo $result ?></p>
    </div>
<?php }
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="dsp-row">
            <div class="dsp-md-3 dsp-block" style="display:none">             
                <div class="box-profile-link">                            

                    <div class="menus-profile">
                        <ul>
                            <li>
                                <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($gender == 'C') {
                                ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo __('View Profile', 'wpdating') ?>" />
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo __('View Profile', 'wpdating') ?>" />     
                                <?php }  } else {  ?> 

                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo __('View Profile', 'wpdating') ?>" />       

                                <?php } ?></a>
                            </li>
                            <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo __('Profile Trending', 'wpdating') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo __('Who Viewed Me', 'wpdating') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo __('Who I Viewed', 'wpdating') ?>" /></a> </li>
                            <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo __('Who\'s Online', 'wpdating') ?>&nbsp;(<?php echo $count_online_member ?>)" /></a></li>
                            <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo __('New Email', 'wpdating'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0"/></a></li>
                        </ul>
                    </div>
                    <div class="clr"></div>
                    <ul class="text-left dsp-user-spec clearfix">
                 
                        <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_active_link" <?php } ?>>
                             <?php if ($count_wink_messages > 0) { ?>
                                <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>"><i class="fa fa-meh-o"></i><?php echo __('Winks', 'wpdating') ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_wink_messages ?>)</span></a>
                            <?php } else { ?>
                                <a href="<?php echo $root_link . "home/view_winks/"; ?>"><i class="fa fa-meh-o"></i><?php echo __('Winks', 'wpdating'); ?></a>
                            <?php } ?>
                            </li>
                        <?php } ?>
   
                        <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_active_link"  <?php } ?>>
                                <a href="<?php echo $root_link . "home/view_friends/"; ?>"><i class="fa fa-users"></i><?php echo __('Friends', 'wpdating'); ?></a>
                            </li>
                        <?php } ?>
                        

                        <li <?php if (($profile_pageurl == "my_favorites")) { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/my_favorites/"; ?>"><i class="fa fa-heart"></i><?php echo __('Favorites', 'wpdating'); ?></a>
                        </li>
                        
                        
                        <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
                        <li <?php if (($profile_pageurl == "virtual_gifts")) { ?>class="dsp_active_link" <?php } ?>>
                            <?php if ($count_friends_virtual_gifts > 0) { ?>
                                <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_virtual_gifts ?>)</span> </a>
                            <?php } else { ?>
                                <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?> </a>
                            <?php } ?>
                        </li>
                        <?php } ?>

                        <li <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/my_matches/"; ?>"><i class="fa fa-star"></i><?php echo __('Matches', 'wpdating'); ?></a>
                        </li>

                        <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_active_link"  <?php } ?>>
                                <a href="<?php echo $root_link . "home/match_alert/"; ?>"><i
                                        class="fa fa-bell"></i><?php echo __('Match alerts', 'wpdating'); ?>
                                </a>
                            </li>
                        <?php } ?>

                        <li <?php if ($profile_pageurl == "alerts") { ?>class="dsp_active_link" <?php } ?>>
                            <?php if ($count_friends_request > 0) { ?>
                                <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo __('Alerts', 'wpdating'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_request ?>)</span> </a>
                            <?php } else { ?>
                                <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo __('Alerts', 'wpdating'); ?></a>
                            <?php } ?>
                        </li>
                        
                        <?php if ($check_comments_mode->setting_status == 'Y') { ?>
                            <li <?php if (($profile_pageurl == "comments")) { ?>class="dsp_active_link" <?php } ?>>

                            <?php if ($check_approve_comments_status->setting_status == 'Y') { ?>
                                <?php if ($count_friends_comments > 0) { ?>
                                <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;">
                                    <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>&nbsp;<span class="dsp-alert-count">(<?php echo $count_friends_comments ?>)</span>
                                </a>
                                <?php } else { ?>
                                <a href="<?php echo $root_link . "home/comments/"; ?>">
                                    <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>
                                </a>
                                <?php } ?>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/comments/"; ?>">
                                        <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                         

                        <li <?php if ($profile_pageurl == "news_feed") { ?>class="dsp_active_link" <?php } ?>>
                            <a href="<?php echo $root_link . "home/news_feed/"; ?>"><i class="fa fa-bullhorn"></i><?php echo __('News Feed', 'wpdating'); ?></a>
                        </li>

                    </ul>

                </div>
            </div>
            <div class="dsp-md-9">
                <?php if ($check_comments_mode->setting_status == 'Y') { 
                    $comment_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_comments_table` where member_id=$user_id");
                    //echo $wpdb->last_query;die;
                    if ($comment_chk != 0) {
                ?>
                    <h3 class="heading-feed margin-btm-2">My Comments</h3>
                <?php
                    
                        $comment_list = $wpdb->get_results("SELECT * FROM `$dsp_comments_table` where member_id=$user_id   ORDER BY `status_id` DESC");
                        foreach ($comment_list as $comments) {
                            $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$comments->user_id'");
                            $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$comments->user_id'");
                            $highlightClass =  $comments->status_id == 0 ? "dspdp-bg-info" : " ";

                            ?>
                            <div class="row-comment row-comment-home dsp-row-comment <?php echo $highlightClass; ?> dsp-clearfix image-container">
                                <a href="<?php
                                if ($check_gender != 'C') {
                                    echo $root_link . get_username($comments->user_id) . "/";
                                } else {
                                    echo $root_link . get_username($comments->user_id) . "/my_profile/";
                                }
                                ?>" class="">
                                <div class="image-box dsp-circular">
                                    <img title="<?php echo $users_details->user_login; ?>" src="<?php echo display_members_photo($comments->user_id, $imagepath); ?>" style="height: 100px;width: 100px;" class="dsp-circular"/></div></a>
                                    <div class="show-comment"><?php echo stripcslashes($comments->comments); ?> 

                                    <?php if($comments->status_id != '1'):?>
                                        <span class="comment-approval"> 
                                            <a class="dsp-btn dsp-btn-default dsp-btn-sm" href="<?php echo $root_link . "home/comments/Action/approve/comment_Id/" . $comments->comments_id; ?>" >Approve</a>
                                            <a class="dsp-btn dsp-btn-danger dsp-btn-sm" href="<?php echo $root_link . "home/comments/Action/Del/comment_Id/" . $comments->comments_id; ?>" onclick="if (!confirm('<?php echo __('Are you sure you want to delete this Virtual gift?', 'wpdating'); ?>'))
                                                return false;"><?php echo __('Delete', 'wpdating'); ?> 
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <?php
                        }
                    }else{
                    ?>
                    <h4 class="heading-feed margin-btm-2"><?php echo __('Empty', 'wpdating');?></h4>
                <?php } ?>    
                <?php }
                ?>
            </div>
        </div>
    </div>
</div>