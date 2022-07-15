<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once("../../../wp-config.php");
include_once("./files/includes/functions.php");
global $wpdb;
$dsp_match_alert_criteria_table         = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
$dsp_profile_setup_table                = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
$dsp_question_details                   = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
$dsp_user_profiles                      = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_match_alert_criteria               = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;
$dsp_user_table                         = $wpdb->prefix . DSP_USERS_TABLE;
$dsp_email_templates_table              = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
$dsp_members_photos                     = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
$dsp_match_alert_email_sent_user_table  = $wpdb->prefix . DSP_MATCH_ALERT_EMAIL_SENT_USER_TABLE;
$site_url                               = get_option('siteurl');

$my_query = $wpdb->get_results("SELECT * FROM $dsp_match_alert_criteria where active='Y'");
foreach ($my_query as $query) {
    $today = date('Y-m-d');
    if ($today < $query->last_updated_date){
        continue;
    }
    $user_id                = $query->user_id;
    $already_matched_users  = apply_filters('dsp_get_already_mail_sent_match_users', $user_id);
    $already_matched_users  .= !empty($already_matched_users) ? ',' .$user_id : $user_id;
    $frequency              = $query->frequency;
    $gender                 = $query->gender;
    $age_from               = $query->age_from;
    $age_to                 = $query->age_to;
    $date                   = $query->last_updated_date;
    $user_email             = $wpdb->get_var("SELECT user_email FROM {$wpdb->users}  WHERE ID='$user_id'");
    $match_email_template   = $wpdb->get_row("SELECT * FROM $dsp_email_templates_table WHERE mail_template_id = 15 ");
    $email_subject          = $match_email_template->subject;
    $email_subject          = str_replace('domain.com', $_SERVER['HTTP_HOST'], $match_email_template->subject);
    $email_body             = $match_email_template->email_body;
    $email_body             = str_replace('domain.com', $_SERVER['HTTP_HOST'], $match_email_template->email_body);

    $profile_question_match_exists = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_profile_setup_table} WHERE display_status='Y'");

    $sql_query = "SELECT user.ID as user_id, user.user_login as username,
                    user_profile.make_private as private_image,
                    user_profile.gender as  user_gender,
                    (SELECT picture FROM $dsp_members_photos WHERE user_id = user.ID ORDER BY photo_id DESC LIMIT 1) as user_image
                    FROM {$wpdb->users} as user
                    JOIN {$dsp_user_profiles} as user_profile 
                    ON user.ID = user_profile.user_id";

    if ($profile_question_match_exists){
        $sql_query .= " JOIN {$dsp_question_details} as question_detail 
                        ON user.ID = question_detail.user_id
                        AND profile_question_option_id IN (
                           SELECT profile_question_option_id FROM {$dsp_question_details} WHERE profile_question_id IN (
                                SELECT profile_setup_id FROM {$dsp_profile_setup_table} WHERE display_status='Y'
                            ) 
                            and user_id='{$user_id}' 
                        )";
    }

    $sql_query .= " WHERE user.ID NOT IN(
                            (SELECT user_id FROM {$dsp_match_alert_email_sent_user_table} WHERE match_id= '{$user_id}')
                            UNION 
                            (SELECT {$user_id} user_id)
                        ) AND 
                        ((year(CURDATE())-year(user_profile.age)) >= '{$age_from}') AND ((year(CURDATE())-year(user_profile.age)) < '{$age_to}') AND 
                        user_profile.gender='{$gender}' GROUP BY user.ID ORDER BY user.ID";

    $matched_users = $wpdb->get_results($sql_query);
    
    if($wpdb->num_rows == 0) {
        continue;
    }

    $message =
            '<div style="color: #FF0000;font-family: arial;font-size: 30px;font-weight: bold;padding: 5px;width: 600px;">' . $email_subject . '</div>
                <div style="background-color: #EFEFEF;border: 1px solid #CCCCCC;padding: 5px;width: 600px;float:left;">
                    <div style="background-color: #FFFFFF;border: 1px solid #CCCCCC;padding: 5px;float:left;width:587px;">
                        <div style="display: block;padding: 1px;width: 100%;">';
    foreach ($matched_users as $matched_user) {
        $userData = array(
            'match_id'  => $user_id,
            'user_id'   => $matched_user->user_id
        );
        do_action('dsp_insert_match_users',$userData);

        $image_path                 = "{$site_url}/wp-content/uploads/dsp_media";
        if($matched_user->private_image  == 'Y'){
            $matched_user_image_path = WPDATE_URL . '/images/private-photo-pic.jpg';
        }else{
            if (!empty($matched_user->user_image)) {
                if (file_exists(WP_CONTENT_DIR . "/uploads/dsp_media/user_photos/user_{$matched_user->user_id}/thumbs1/thumb_{$matched_user->user_image}")){
                    $matched_user_image_path = "{$image_path}/user_photos/user_{$matched_user->user_id}/thumbs1/thumb_{$matched_user->user_image}";
                }else{
                    $matched_user_image_path = "images/no-image.jpg";
                }
            } else {
                if ($matched_user->user_gender == 'M') {
                    $matched_user_image_path = "{$image_path}/male-generic.jpg";
                } else {
                    $matched_user_image_path = "{$image_path}/female-generic.jpg";
                }
            }
        }
        ?>
        <?php

        $message .=
                '<div style="display: block;float: left;text-align: center;width: 25%;">
				    <div>
					    <img src="' . $matched_user_image_path . '" style="height: 100px;width: 100px;border: 1px solid #426082;margin-left: auto;margin-right: auto;padding: 3px;text-align: center;" alt="'. $matched_user->username .'" />
				    </div>
				    <div style="clear: both;"></div>
				    <div style="color: #426082;">' . $matched_user->username . '</div>
			    </div>';
    }
    $message .='
                         <div style="clear: both;"></div>
                         <br>
                       <div style="font-size:14px;font-weight:bold;" >' . $email_body . '</div>
                     </div>
                  </div>
               </div>';

    $subject        = $email_subject;
    $admin_email    = get_option('admin_email');
    // To send HTML mail, the Content-type header must be set
    $headers        = "MIME-Version: 1.0 \r\n
                        Content-type: text/html; charset=iso-8859-1 \r\n
                        To: {$user_email} \r\n
                        From: {$admin_email} \r\n";
    $frequency      = $query->frequency;

    if ($frequency == 'W') {
        $updated_date = date('Y-m-d', strtotime('+7 day'));
    } else if ($frequency == 'D') {
        $updated_date = date('Y-m-d', strtotime('+1 day'));
    } else if ($frequency == 'M') {
        $updated_date = date('Y-m-d', strtotime('+1 month'));
    }

    if (wp_mail($user_email, $subject, $message, $headers)) {
        $updated_date = date('Y-m-d', strtotime('+1 day'));
        $wpdb->update($dsp_match_alert_criteria_table, array(
            'last_updated_date' => $updated_date
        ), array('user_id' => $user_id));
    }
}
