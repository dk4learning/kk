<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - MyAllenMedia, LLC
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;
$dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_table = $wpdb->users;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

$profile_subtab = get_query_var( 'profile-subtab' );
$user_id = wpee_profile_id();
$current_user = get_current_user_id();
$username = wpee_get_username_by_id( $user_id );
$profile_page = get_option( 'wpee_profile_page', '' );
$profile_page_url = get_permalink( $profile_page ); 
$profile_link = trailingslashit($profile_page_url) . $username;
$imagepath = content_url('/') ;


$check_couples_mode = wpee_get_setting( 'couples' );

$delblock_id = get('Block_Id');
$Actiondel = get('Action');
if (($delblock_id != "") && ($Actiondel == "Del")) {
    $wpdb->query("DELETE FROM $dsp_blocked_members_table where blocked_id = '$delblock_id'");
}
$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_blocked_members_table where user_id='$user_id'");
?>


    <div class="wpee-block-wrapper friends-favourites">
        <ul class="friends-section no-list"><!-- friends-section class used -->
        <?php
        if ($total_results1 > 0) {
                if ($check_couples_mode->setting_status == 'Y') {
                    $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id
AND blocked.user_id = '$user_id'");
                } else {
                    $blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table blocked, $dsp_user_profiles profile WHERE blocked.block_member_id = profile.user_id
AND blocked.user_id = '$user_id' AND profile.gender!='C' ");
                }
//$blocked_members = $wpdb->get_results("SELECT * FROM $dsp_blocked_members_table WHERE user_id = '$user_id' ORDER BY blocked_id");
                $i = 0;
                foreach ($blocked_members as $Member) {
                    $blocked_id = $Member->blocked_id;
                    $block_member_id = $Member->block_member_id;
                    $member_link = trailingslashit($profile_page_url) . wpee_get_user_display_name_by_id($block_member_id);
                    $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$block_member_id'");
                    $exist_make_private->make_private;
                    $favt_mem = array();
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$block_member_id'");
                    $displayed_member_name = wpee_get_user_display_name_by_id($block_member_id);
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    if (($i % 3) == 0) {
                        ?>
                    <?php }  // End if(($i%4)==0) ?>
                    <li id="wpee-block-<?php echo esc_attr($Member->blocked_id);?>" class="wpee-block-content wpee-fav-list">

                        <?php
                            $profile_link = wpee_get_profile_url_by_id( $block_member_id );

                            if ($exist_make_private->make_private == 'Y') { 
                                if (!in_array($current_user->ID, $favt_mem)) {
                                    $profile_image= WPDATE_URL. '/images/private-photo-pic.jpg';
                                }
                                else{
                                    $profile_image = wpee_display_members_photo($block_member_id, $imagepath);
                                }

                            }
                            else{
                                $profile_image = wpee_display_members_photo($block_member_id, $imagepath);
                            }
                           ?>
                        <figure class="img-holder">             
                            <a href="<?php echo esc_url($profile_link);?>" > 
                                <img src="<?php echo $profile_image; ?>" class="dsp_img3 iviewed-img"/>
                            </a>
                            <div class="wpee-user-status <?php echo ( wpee_get_online_user($favourites->favourite_user_id) ) ? 'wpee-online' : 'wpee-offline';?>"></div>
                        </figure>
                        <div class="fav-content">                                           
                            <span class="user-name-show">
                                <a href="<?php echo esc_url( $profile_link );?>">       
                                    <?php echo $displayed_member_name; ?>
                                </a>
                            </span>  
                            <?php //if( $user_id == $current_user_id ): ?>                             
                                <a href="javascript:void(0);"  data-action="delete" data-block-id="<?php echo esc_attr($Member->blocked_id);?>" class="dsp-btn-default wpee-block-trigger"><?php echo __('Unblock', 'wpdating') ?></a> 
                            <?php //endif;?>
                        </div>
                    </li>
                    <?php
                    $i++;
                    unset($favt_mem);
                }
                ?>
        <?php } else { ?>
            <li style="text-align:center"><strong><?php echo __('No Blocked Members!', 'wpdating') ?></strong></li>
                <?php } ?>
        </ul>
    </div>