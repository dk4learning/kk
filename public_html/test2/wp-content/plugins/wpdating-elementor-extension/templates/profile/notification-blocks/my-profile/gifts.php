<?php
global $wpdb;
$users_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;

$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'received';
$user_id = wpee_profile_id();
$username = wpee_get_username_by_id( $user_id );
$current_user_id = get_current_user_id();
$profile_page = get_option( 'wpee_profile_page', '' );
$profile_page_url = get_permalink( $profile_page ); 
$profile_link = trailingslashit(wpee_get_profile_url_by_id($current_user_id));
$check_virtual_gifts_mode = wpee_get_setting('virtual_gifts');
$imagepath = content_url('/') ;
?>
<div class="gift-list-wrapper main-profile-mid-wrapper">
    <ul class="profile-section-tab">
        <li class="profile-section-tab-title <?php echo ($profile_subtab != 'sent') ? 'active': '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'gifts/received');?>"><?php esc_html_e( 'Gifts Received', 'wpdating' );?></a></li>
        <li class="profile-section-tab-title <?php echo ($profile_subtab == 'sent') ? 'active': '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'gifts/sent');?>"><?php esc_html_e( 'Gifts Sent', 'wpdating' );?></a></li>
    </ul>
    <div class="profile-section-content">
            <?php

        if( !is_wp_error(wpee_check_membership('Virtual Gifts', $current_user_id )) && wpee_check_membership('Virtual Gifts', $current_user_id ) == true ){
            if( !empty( $profile_subtab ) && $profile_subtab == 'received' ){?>
                <div class="wpee-gifts-received-wrapper">
                    <?php
                    //$gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0 ");
                    $gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where member_id=$user_id");
                    if ($gift_chk != 0) {     
                        //$gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where member_id=$user_id and status_id=0  ORDER BY `date_added` DESC");
                        $gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where member_id=$user_id ORDER BY `date_added` DESC");
                        foreach ($gift_list as $gifts) {
                            $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$gifts->user_id'");
                            $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$gifts->user_id'");
                            $member_profile_link = trailingslashit(wpee_get_profile_url_by_id($gifts->user_id));
                            ?>
                                <div id="wpee-gift-<?php echo esc_attr($gifts->gift_id);?>" class="wpee-gifts-received-content dspdp-member-col">
                                    <div class="row-comment">
                                        <div class="gift-user-image">
                                            <a href="<?php echo $member_profile_link;?>">
                                                <div class="image-box">
                                                    <img class="dspdp-img-responsive dspdp-block-center  dspdp-spacer" title="<?php echo $users_details->user_login; ?>" alt="<?php echo $users_details->user_login; ?>" src="<?php echo wpee_display_members_photo($gifts->user_id, $imagepath); ?>" />
                                                </div>
                                            </a>
                                        </div>
                                        <h6 class="gift-user-name">
                                             <a href="<?php echo $member_profile_link;?>"><?php echo wpee_get_user_display_name_by_id($users_details->ID); ?></a>
                                        </h6>
                                        <div class="gift-image">
                                            <img class="gift-img" src="<?php echo content_url('/uploads/dsp_media/gifts/') . $gifts->gift_image; ?>" alt="<?php echo $gifts->gift_image;?>"/>
                                        </div>
                                        <div class="gift-action">
                                            <?php if($gifts->status_id == 0): ?>
                                                    <a class="gift-action-trigger dspdp-btn-danger" href="javascript:void(0);" data-action="delete" data-gift-id="<?php echo esc_attr( $gifts->gift_id);?>" data-confirm-msg="<?php echo __('Are you sure you want to delete this Virtual gift?', 'wpdating'); ?>" ><i class="fa fa-trash-o"></i><span class="gift-action-text"><?php echo __('Delete', 'wpdating'); ?></span></a>
                                                    <a class="gift-action-trigger dspdp-btn-success" href="javascript:void(0);" data-action="approve" data-gift-id="<?php echo esc_attr( $gifts->gift_id);?>"><i class="fa fa-gift"></i><span class="gift-action-text"><?php echo __('Approve', 'wpdating'); ?></span></a>
                                            <?php /*else: 
                                                    if ($user_id != $member_id && is_user_logged_in()) { ?>
                                                        <a class="gift-send-trigger dspdp-btn-success" href="javascript:void(0);" data-action="send"><i class="fa fa-gift"></i><span class="gift-action-text"><?php echo __('Send gift', 'wpdating'); ?></span></a>
                                                <?php
                                                    }*/
                                                endif; ?>
                                            
                                        </div>
                                    </div>
                                </div>
                        <?php
                            } 
                    }
                    else
                        {
                        ?>
                        <div class="wpee-gifts-no-content">
                        <?php
                            echo __('No record found for your search criterias.', 'wpdating'); 
                        ?>
                        </div>
                        <?php 
                    } ?>
                </div>
                <?php
                }
            elseif( !empty( $profile_subtab ) && $profile_subtab == 'sent' ){                
                $gift_chk = $wpdb->get_var("SELECT count(*) FROM `$dsp_user_virtual_gifts` where user_id=$user_id");
                if ($gift_chk != 0) {
                    $gift_list = $wpdb->get_results("SELECT * FROM `$dsp_user_virtual_gifts` where user_id=$user_id ORDER BY `date_added` DESC");
                    foreach ($gift_list as $gifts) {
                        $users_details = $wpdb->get_row("SELECT ID,user_login FROM $users_table  WHERE ID='$gifts->member_id'");
                        $check_gender = $wpdb->get_var("SELECT gender FROM $dsp_user_profiles  WHERE user_id = '$gifts->member_id'");
                        $member_profile_link = trailingslashit(wpee_get_profile_url_by_id($gifts->member_id));
                        ?>
                        <div class="gift-sent-wrap dsp-gift-container dsp-border-container">
                            <div class="dspdp-spacer dspdp-member-col">
                                <div class="row-comment d-flex align-center">
                                    <div class="dsp-friend-image-holder">
                                        <a href="<?php echo esc_url($member_profile_link);?>">
                                            <div class="image-box">
                                                <img class="dspdp-img-responsive dspdp-block-center  dspdp-spacer" title="<?php echo $users_details->user_login; ?>" alt="<?php echo $users_details->user_login; ?>" src="<?php echo wpee_display_members_photo($gifts->member_id, $imagepath); ?>" />
                                            </div>
                                        </a>
                                     </div>
                                     <h6 class="gift-user-name">
                                         <a href="<?php echo esc_url($member_profile_link);?>"><?php echo $users_details->user_login; ?></a>
                                    </h6>
                                    <div class="show-comment">
                                        <img class="dspdp-img-responsive dspdp-block-center dsp-none" src="<?php echo content_url('/uploads/dsp_media/gifts/') . $gifts->gift_image; ?>" alt="<?php echo $gifts->gift_image;?>"/>
                                        
                                        <?php if($gifts->status_id == 1): ?>
                                            <span class="dsp-none">
                                                <?php echo __('Approved', 'wpdating'); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php                 
                    } 
                } 
                else
                {
                ?>
                <div class="wpee-gifts-no-content">
                <?php
                    echo __('No record found for your search criteria.', 'wpdating'); 
                ?>
                </div>
            <?php }
            }
        }
        else{
            wpee_display_error_message(wpee_check_membership('Virtual Gifts', $current_user_id ));
        }
            ?>
    </div>
</div>