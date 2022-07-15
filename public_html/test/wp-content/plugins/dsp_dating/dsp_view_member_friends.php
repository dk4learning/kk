<?php
// ----------------------------------Check member privacy Settings------------------------------------
$redirect_location = $root_link . "home/view_friends/";
$request_Action = get('Action');
$del_friend_Id = get('friend_Id');
// ###########################  Reject Image ########################################

if (($request_Action == "Del") && ($del_friend_Id != "")) {
    //delete friend id = 1
    //user_id = 1479
    //$wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id"); //old code
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$del_friend_Id' AND user_id=$user_id");
    $wpdb->query("DELETE from $dsp_my_friends_table WHERE friend_uid = '$user_id' AND user_id=$del_friend_Id");
    //wp_redirect($redirect_location, $redirect_status);
} 
//*************************************************************************//
$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_friends='Y' AND user_id='$member_id'");
if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>
        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo __('You can&rsquo;t view member Friends.', 'wpdating'); ?></div>
            </div>
        </div>
    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>
        <div class="box-border show-details">
            <div class="box-pedding">


                <div class="friends-member">
                    <?php
                    $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table WHERE user_id = '$member_id' AND approved_status='Y'");
                    if(isset($member_exist_friends) && count($member_exist_friends) > 0){
                    $i = 0;
                    foreach ($member_exist_friends as $member_friends) {
                            if (($i % 4) == 0) {
                                ?>
                            <?php }  // End if(($i%4)==0) ?>
                            <p>
                            <div class="image-container">
                            <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  alt="<?php echo  get_username($member_friends->friend_uid); ?>"/></a>
                            </div>
                            </p>
                            <?php
                            $i++;
                        }
                    }else{ ?>
                        <span class="dsp_span_pointer"><?php echo __('No result found !', 'wpdating'); ?></span><br />
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }   // ------------------------------------------------- End if Check in my friend list --------------------------------- //
} else {
// -------------------------------------- else  Privacy Setting for Everyone ------------------------------------------- // 
    ?>
    <div class="box-border ">
        <div class="box-pedding">

            <div class="dsp-row friends-block">
            
                <div class="friends-member dsp-md-12">
                    <div style="clear:both;"></div>
                    <div class="friends-section"><?php
                    if ($check_couples_mode->setting_status == 'Y') {
                        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y'");
                    } else {
                        $member_exist_friends = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.friend_uid=profile.user_id AND friends.user_id = '$member_id' AND friends.approved_status='Y' AND profile.gender!='C'");
                    }
                    if(isset($member_exist_friends) && count($member_exist_friends) > 0){
                        $i = 0;
                        foreach ($member_exist_friends as $member_friends) {
                            $displayed_member_name = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$member_friends->friend_uid'");
                            if (($i % 4) == 0) {
                                ?>
                            <?php }  // End if(($i%4)==0) ?>

                        <div class="dspdp-col-sm-3 dspdp-col-xs-6 dsp-sm-6"><div class="image-container">
                            <div class="dsp-friend-image-holder">
                                <?php if ($user_id == '') { ?>

                                    <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>" title="<?php echo $displayed_member_name; ?>"> <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" /></a>

                                        <?php
                                    } else {
                                        if ($user_id == $member_id) {
                                            ?>
                                            <a href="<?php echo $redirect_location . "Action/Del/friend_Id/" . $member_friends->friend_uid . "/"; ?>"  onclick="if (!confirm('<?php echo __('Are you sure you want to Delete the selected Member?', 'wpdating'); ?>'))
                                                        return false;" class="dspdp-del" ><span class="delete-icon" title="<?php echo __('Delete', 'wpdating'); ?>" >&times;</span></a>
                                               <?php
                                           }
                                           $favt_mem = array();
                                           $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_friends->friend_uid'");
                                           foreach ($private_mem as $private) {
                                               $favt_mem[] = $private->favourite_user_id;
                                           }
                                           ?>

                                        <?php
                                        if ($check_couples_mode->setting_status == 'Y') {
                                            if ($member_friends->gender == 'C') {
                                                ?>
                                                <?php if ($member_friends->make_private == 'Y') { ?>

                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>" >
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                    <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>"  >				
                                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a  href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>"> 
                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
                                                    </a>
                                                <?php } ?>

                                            <?php } else { ?>

                                                <?php if ($member_friends->make_private == 'Y') { ?>

                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>"  title="<?php echo $displayed_member_name; ?>">
                                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>"  title="<?php echo $displayed_member_name; ?>">				
                                                        <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>"/></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>" title="<?php echo $displayed_member_name; ?>"> 
                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>" />
                                                    </a>
                                                <?php } ?>

                                                <?php
                                            }
                                        } else {
                                            ?> 

                                            <?php if ($member_friends->make_private == 'Y') { ?>

                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>" title="<?php echo $displayed_member_name; ?>" >
                                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" style="width:100px; height:100px;"border="0"  alt="Private Photo" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>">				
                                                    <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"     width="100" height="100" alt="<?php echo $displayed_member_name; ?>" /></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" title="<?php echo $displayed_member_name; ?>"> 
                                                <img src="<?php echo display_members_photo($member_friends->friend_uid, $imagepath); ?>"  style="width:100px; height:100px;" alt="<?php echo $displayed_member_name; ?>"/>
                                                </a>
                                            <?php } ?>

                                        <?php } ?>

                                        <?php
                                        unset($favt_mem);
                                    }
                                    ?>
                                </div>

                                <div style="clear:both" class="dsp-none"></div>
                                <span class="user-name-show"><?php if ($member_friends->gender == 'C') {
                                    ?>
                                        <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/my_profile/"; ?>" >	
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($member_friends->friend_uid) . "/"; ?>">		
                                            <?php } ?>
                                            <?php echo $displayed_member_name; ?>
                                        </a>
                                </span>
                                <?php if($user_id == $member_id){?>
                                <a href="<?php echo $redirect_location . "Action/Del/friend_Id/" . $member_friends->friend_uid . "/"; ?>"  onclick="if (!confirm('<?php echo __('Are you sure you want to Delete the selected Member?', 'wpdating'); ?>'))
                                                        return false;" class="dsp-block dsp-delete" style="display:none" ><i class="fa fa-trash-o"></i></a>
                                <?php } ?>
                                <?php if (isset($_REQUEST['pid']) && $_REQUEST['pid'] == 1) { ?>

                                <?php } ?>
                            </div></div>
                            <?php
                            $i++;
                        }
                    }else{ ?>
                        <span class="dsp_span_pointer" style="text-align: center; display: block;"><?php echo __('No result found !', 'wpdating'); ?></span><br />
                    <?php
                    }
                    ?></div>
                </div>
            </div>
        </div>
    </div>
<?php } 