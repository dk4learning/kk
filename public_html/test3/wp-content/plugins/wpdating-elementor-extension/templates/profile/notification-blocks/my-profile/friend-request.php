<?php
global $wpdb;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
$dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
$user_id= get_current_user_id();
$imagepath= content_url('/');
$profile_link = wpee_get_profile_url_by_id($user_id);

$count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
?>
    <div class="profile-section-wrap main-profile-mid-wrapper">
        <ul class="profile-section-tab">
            <li class="profile-section-tab-title active"><a href="<?php echo esc_url(trailingslashit($profile_link).'friend-request');?>"><?php esc_html_e( 'Friend Request', 'wpdating' );?></a></li>
        </ul>
        <div class="profile-section-content wpee-block-content">
            <?php 
            if ($count_friends_request > 0) { ?>
                <ul class="friend-request-list wpee-listing no-list">

                        <?php
                        if ($check_couples_mode->setting_status == 'Y') {
                            $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
            AND friends.friend_uid = '$user_id' AND friends.approved_status='N' LIMIT 20");
                        } else {
                            $frnd_request_members = $wpdb->get_results("SELECT * FROM $dsp_my_friends_table friends, $dsp_user_profiles profile WHERE friends.user_id = profile.user_id
            AND friends.friend_uid = '$user_id' AND profile.gender!='C' AND friends.approved_status='N' LIMIT 20 ");
                        }
                        foreach ($frnd_request_members as $request_mem) {
                            ?>
                            <li id="fr<?php echo esc_attr($request_mem->user_id);?>" class="view-list">
                                <?php
                                $exist_frnd_name = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$request_mem->user_id'");

                                $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$exist_frnd_name->user_id'");
                                $s_user_id = $exist_frnd_name->user_id;
                                $displayed_member_name = wpee_get_user_display_name_by_id($s_user_id);
                                $favt_mem = array();

                                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$exist_frnd_name->user_id'");
                                foreach ($private_mem as $private) {
                                    $favt_mem[] = $private->favourite_user_id;
                                }
                                $profile_link = wpee_get_profile_url_by_id( $s_user_id );

                                if ($exist_make_private->make_private == 'Y') { 
                                    if (!in_array($current_user->ID, $favt_mem)) {
                                        $profile_image= WPDATE_URL. '/images/private-photo-pic.jpg';
                                    }
                                    else{
                                        $profile_image = wpee_display_members_photo($s_user_id, $imagepath);
                                    }

                                }
                                else{
                                    $profile_image = wpee_display_members_photo($s_user_id, $imagepath);
                                }
                                 ?>
                                <figure>                                
                                    <a href="<?php echo esc_url($profile_link);?>" > 
                                        <img src="<?php echo $profile_image; ?>" class="dsp_img3 iviewed-img"/>
                                    </a>
                                </figure>                                
                                <div class="user-details">
                                    <h6 class="member-user-name">
                                    <a href="<?php echo $profile_link; ?>">
                                        <?php
                                            echo $displayed_member_name;
                                        ?>
                                    </a>
                                </div>
                                <div class="wpee-friend-request-action">
                                    <a href="javascript:void(0);" class="wpee-friend-request-trigger" data-action="delete" data-profile-id="<?php echo esc_attr($s_user_id);?>" data-fr-id="<?php echo esc_attr($request_mem->friend_id);?>"><i class="fa fa-times"></i><span class="wpee-tooltip"><?php echo __('Reject', 'wpdating'); ?></span></a>
                                    <a href="javascript:void(0);" class="wpee-friend-request-trigger" data-action="approve" data-profile-id="<?php echo esc_attr($s_user_id);?>" data-fr-id="<?php echo esc_attr($request_mem->friend_id);?>"><i class="fa fa-check"></i><span class="wpee-tooltip"><?php echo __('Approve', 'wpdating'); ?></span></a>
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                <?php
            } else {
                ?>
                <div style="text-align:center;" class="box-page">
                    <strong><?php echo __('Currently you do not have friend request.', 'wpdating') ?></strong>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

