 <?php 
$request_Action = get('Action');
$frnd_request_Id = get('frnd_request_Id');
$date = date("Y-m-d");
$url = site_url();
$redirect_location = $url . "/members/home/alerts/";
// ###########################  Approve Friend request ########################################
if (($request_Action == "approve") && ($frnd_request_Id != "")) {
    /*$wpdb->query("UPDATE $dsp_my_friends_table  SET approved_status='Y' WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
    $request_user_id = $wpdb->get_row("SELECT * FROM $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
    $check_friend_in_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE user_id='$user_id' AND friend_uid='$request_user_id->user_id'");
    if ($check_friend_in_list <= 0) {
        $wpdb->query("INSERT INTO $dsp_my_friends_table SET user_id ='$user_id',friend_uid='$request_user_id->user_id',  approved_status='Y' , date_added='$date'");
    }*/

    $email_template = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='8'");
    $reciver_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$request_user_id->user_id'");
    $reciver_name = $reciver_details->display_name;
    $receiver_email_address = $reciver_details->user_email;
    $sender_details = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$user_id'");
    $sender_name = $sender_details->display_name;
    //$site_url=$wpdb->get_row("SELECT * FROM wp_options WHERE option_name='siteurl'");
    // $sender_email_address=$site_url->option_value;
    // $sender_email_address = str_replace ("http://", '', $sender_email_address);

    
    $email_subject = $email_template->subject;
    $email_subject = str_replace("<#SENDER_NAME#>", $sender_name, $email_subject);
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

    $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
    
    echo '<script> window.location.href = "'.$redirect_location .'"</script>';
    

}

// ###########################  Reject Friend request  ########################################

if (($request_Action == "reject") && ($frnd_request_Id != "")) {
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_id = '$frnd_request_Id' AND friend_uid=$user_id");
    echo '<script> window.location.href = "'.$redirect_location .'"</script>';
    $count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
}

?>
<div class="box-border">
    <div class="box-pedding">
        <div class="row">
            <div class="dsp-md-3 dsp-block" style="display:none">             
                <div class="box-profile-link">                            

                    <div class="menus-profile">
                        <ul>
                            <li>
                                <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($gender == 'C') {
                                ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/my_profile/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title="<?php echo __('View Profile', 'wpdating') ?>" alt="<?php echo __('View Profile', 'wpdating') ?>" />
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo __('View Profile', 'wpdating') ?>" alt="<?php echo __('View Profile', 'wpdating') ?>" />     
                                <?php }  } else {  ?> 

                                    <a href="<?php echo $root_link . get_username($user_id) . "/"; ?>"><img src="<?php echo $fav_icon_image_path ?>view_profile.jpg" title=" <?php echo __('View Profile', 'wpdating') ?>" alt="<?php echo __('View Profile', 'wpdating') ?>" />       

                                <?php } ?></a>
                            </li>
                            <li><a href="<?php echo $root_link . "extras/trending/"; ?>"><img src="<?php echo $fav_icon_image_path ?>profile.jpg" title="<?php echo __('Profile Trending', 'wpdating') ?>"  alt="<?php echo __('Profile Trending', 'wpdating') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "extras/viewed_me/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_viewedme.jpg" title="<?php echo __('Who Viewed Me', 'wpdating') ?>" alt="<?php echo __('Who Viewed Me', 'wpdating') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "extras/i_viewed/"; ?>"><img src="<?php echo $fav_icon_image_path ?>who_iviewed.jpg" title="<?php echo __('Who I Viewed', 'wpdating') ?>"  alt="<?php echo __('Who I Viewed', 'wpdating') ?>"/></a> </li>
                            <li><a href="<?php echo $root_link . "online_members/show/all/"; ?>"><img src="<?php echo $fav_icon_image_path ?>whos_online.jpg" title="<?php echo __('Who's Online', 'wpdating') ?>&nbsp;(<?php echo $count_online_member ?>)" alt="<?php echo __('Who's Online', 'wpdating') ?>" /></a></li>
                            <li><a href="<?php echo $root_link . "email/inbox/"; ?>"><img src="<?php echo $fav_icon_image_path ?>message.jpg" title="<?php echo __('New Email', 'wpdating'); ?>&nbsp;(<?php echo $count_inbox_messages ?>)"  border="0" alt="<?php echo __('New Email', 'wpdating') ?>" /></a></li>
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
                <?php
                   
                    if ($count_friends_request > 0) {
                        ?>
                    <div class="dsp-md-9">
                     <h3 class="heading-feed margin-btm-2"><?php echo __('Alerts', 'wpdating') ?></h3>
                        <div class="title-txt dspdp-h5"><?php echo __('Pending Friend Requests.', 'wpdating') ?></div>
                        
                        
                            <?php if ($check_couples_mode->setting_status == 'Y') {
                                $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
                                 AND friends.friend_uid = '$user_id' AND friends.approved_status='N' LIMIT 20");
                            } else {
                                $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
                                AND friends.friend_uid = '$user_id' AND profile.gender!='C' AND friends.approved_status='N' LIMIT 20 ");
                            }
                            $i = 0;
                            foreach ($frnd_request_members as $request_mem) {
                            if ($i == 0) {
                            ?>
                                <div class="dspdp-row"> 
                            <?php } // End if(($i%4)==0) ?>
                                <div class="dspdp-col-sm-4 dspdp-col-xs-6">
                                    <div class="image-container">
                                        <div class="dsp-friend-image-holder">
                                            <?php
                                            $exist_frnd_name = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$request_mem->user_id'");
                                            $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$exist_frnd_name->user_id'");
                                            $exist_make_private->make_private;
                                            $favt_mem = array();
                                            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$exist_frnd_name->user_id'");
                                            foreach ($private_mem as $private) {
                                                $favt_mem[] = $private->favourite_user_id;
                                            }
                                           
                                            if ($check_couples_mode->setting_status == 'Y') {
                                                if ($request_mem->gender == 'C') {
                                                    if ($exist_make_private->make_private == 'Y') {
                                                        if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                            <a href="<?php
                                                                echo $root_link . get_username($exist_frnd_name->user_id) . "/my_profile/";
                                                                ?>" >
                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?> " style="width:100px; height:100px;" border="0" class="dsp_img3" alt="<?php echo get_username($exist_frnd_name->user_id); ?>"/>
                                                            </a>                
                                                    <?php } else { ?>
                                                            <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/my_profile/";
                                                            ?>" >
                                                            <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                            ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>"  /></a>                
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>

                                                        <a href="<?php
                                                           echo $root_link . get_username($exist_frnd_name->user_id) . "/my_profile/";
                                                           ?>"> 
                                                            <img src="<?php
                                                                 echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                                 ?>" class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>" />
                                                        </a>
                                                                 <?php
                                                             }
                                                             ?>

                                            <?php   } else { 
                                                        if ($exist_make_private->make_private == 'Y') {
                                                           if (!in_array($current_user->ID, $favt_mem)) {
                                                            ?>
                                                            <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/"; ?>" >
                                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private photo"/>
                                                            </a>                
                                                            <?php
                                                            } else {
                                                                ?>
                                                                <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/";?>" >
                                                                <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                                ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>" /></a>                
                                                            <?php }
                                                        } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/";
                                                            ?>"> 
                                                            <img src="<?php
                                                        echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                        ?>" class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>" />
                                                        </a>
                                                        <?php
                                                    }
                                                    ?>

                                                       <?php
                                                   }
                                               } else {
                                                   ?> 

                                                         <?php
                                                         if ($exist_make_private->make_private == 'Y') {
                                                            if (!in_array($current_user->ID, $favt_mem)) {
                                                            ?>
                                                                <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/"; ?>" >
                                                                   <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;" border="0" class="dsp_img3" alt="Private photo"/>
                                                                </a>                
                                                            <?php } else { ?>
                                                                <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/";?>" >
                                                                <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                                ?>"    class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>" /></a>                
                                                            <?php }
                                                        } else {
                                                        ?>
                                                            <a href="<?php echo $root_link . get_username($exist_frnd_name->user_id) . "/";?>"> 
                                                            <img src="<?php echo display_members_photo($exist_frnd_name->user_id, $imagepath);
                                                            ?>" class="dsp_img3" style="width:100px; height:100px;" alt="<?php echo get_username($exist_frnd_name->user_id); ?>"  />
                                                            </a>
                                                        <?php
                                                       }
                                                    }
                                                    ?>
                                        </div>
                                        <div class="dsp_name dsp-none" align="center">
                                            <a  class="dspdp-btn-sm dspdp-btn dspdp-btn-success dspdp-xs-block" href="<?php
                                            echo $root_link . "home/alerts/Action/approve/frnd_request_Id/" . $request_mem->friend_id . "/";
                                            ?>" onclick="if (!confirm('<?php
                                            echo __('Are you sure you want to Approve the selected friend request?', 'wpdating');
                                            ?>'))
                                            return false;"><span class="dsp_span_pointer"><?php
                                            echo __('Approve', 'wpdating');
                                            ?></span></a> 
                                            <a  class="dspdp-btn-sm dspdp-btn dspdp-btn-danger  dspdp-xs-block" href="<?php
                                                     echo $root_link . "home/alerts/Action/reject/frnd_request_Id/" . $request_mem->friend_id . "/";
                                                     ?>" onclick="if (!confirm('<?php
                                                     echo __('Are you sure you want to Reject the selected friend request?', 'wpdating');
                                                     ?>'))
                                            return false;"><span class="dsp_span_pointer"><?php
                                                     echo __('Reject', 'wpdating');
                                                     ?></span></a>
                                        </div>

                                        <span class="dsp-block" style="display:none">
                                            <a class="dsp-delete" href="<?php
                                                     echo $root_link . "home/alerts/Action/reject/frnd_request_Id/" . $request_mem->friend_id . "/";
                                                     ?>" onclick="if (!confirm('<?php
                                            echo __('Are you sure you want to Reject the selected friend request?', 'wpdating');
                                            ?>'))
                                            return false;"><i class="fa fa-trash-o"></i></a>
                                            <a class="dsp-success" href="<?php
                                            echo $root_link . "home/alerts/Action/approve/frnd_request_Id/" . $request_mem->friend_id . "/";
                                            ?>"  onclick="if (!confirm('<?php
                                            echo __('Are you sure you want to Approve the selected friend request?', 'wpdating');
                                            ?>'))
                                            return false;"><i class="fa fa-thumbs-up"></i></a>                                           
                                        </span>
                                    </div>
                                </div>
                                <?php
                                $i++;
                                if($i % 3 == 0):?>
                                </div>
                                <div class="dspdp-row"> 
                                <?php 
                                endif;
                                
                            }
                        
        ?>
                   
            </div>
         </div>
            <?php } else { ?>
                <div style="text-align:center;" class="box-page">
                    <div class="error">
                        <strong><?php echo __('Currently you donot have friend request.', 'wpdating') ?></strong>
                    </div>
                    
                </div>
            <?php } ?>
       
    </div>
</div>
</div>