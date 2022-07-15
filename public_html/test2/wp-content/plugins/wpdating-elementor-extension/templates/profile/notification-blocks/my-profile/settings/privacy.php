<?php
global $wpdb;
$dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
$dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;

$profile_subtab = get_query_var( 'profile-subtab' );
$user_id = get_current_user_id();

extract($_REQUEST);
$private_messages = isset($_REQUEST['private_messages']) ? $_REQUEST['private_messages'] : '';
$friend_requests = isset($_REQUEST['friend_requests']) ? $_REQUEST['friend_requests'] : '';
$view_my_pictures = isset($_REQUEST['view_my_pictures']) ? $_REQUEST['view_my_pictures'] : '';
$view_my_friends = isset($_REQUEST['view_my_friends']) ? $_REQUEST['view_my_friends'] : '';
$view_my_profile = isset($_REQUEST['view_my_profile']) ? $_REQUEST['view_my_profile'] : '';
$view_my_audio = isset($_REQUEST['view_my_audio']) ? $_REQUEST['view_my_audio'] : '';
$view_my_video = isset($_REQUEST['view_my_video']) ? $_REQUEST['view_my_video'] : '';
//$trending_status = isset($_REQUEST['trending_status']) ? $_REQUEST['trending_status'] : '';
$update_mode = isset($_REQUEST['update_mode']) ? $_REQUEST['update_mode'] : '';
if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php'))
    $chat_permission['with'] = isset($_REQUEST['with_chat_permission']) ? $_REQUEST['with_chat_permission'] : '';

if (($update_mode == 'update') && ($user_id != "")) {
    $contact_permission = array();
    $check_user_exists = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_notification_table WHERE user_id='$user_id'");
    $contact_permission['gender'] = implode(',', (array)$privacy_gender);
    $contact_permission['profile_questions'] = isset($profile_question_option_id) ? implode(',', $profile_question_option_id) : '';

    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_privacy_table SET view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "' WHERE user_id = '$user_id'");
    } else {
        $wpdb->query("INSERT INTO $dsp_user_privacy_table SET user_id = '$user_id',view_my_pictures = '$view_my_pictures',view_my_profile='$view_my_profile',view_my_friends='$view_my_friends',view_my_audio='$view_my_audio',view_my_video='$view_my_video',contact_permission='" . serialize($contact_permission) . "'");
    }

    if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php'))
        do_action('wp_update_instant_chat_permission', $chat_permission, $user_id);

    

    if ($check_user_exists > 0) {
        $wpdb->query("UPDATE $dsp_user_notification_table 
                        SET private_messages='$private_messages',
                            friend_request='$friend_requests'
                            WHERE user_id = '$user_id'"
                    );
    } else {
        $wpdb->query("INSERT INTO $dsp_user_notification_table 
                            SET user_id = '$user_id',
                                private_messages = '$private_messages',
                                friend_request='$friend_requests'
                    ");
    }
    $settings_updated = true;
}
$member_privacy_settings = $wpdb->get_row("SELECT * FROM $dsp_user_privacy_table WHERE user_id = $user_id");
$member_notification_settings = $wpdb->get_row("SELECT `private_messages`,`friend_request` FROM $dsp_user_notification_table WHERE user_id = '$user_id'");
$saved_contact_permission = unserialize(isset($member_privacy_settings->contact_permission) ? $member_privacy_settings->contact_permission : '');
?>
<?php if (isset($settings_updated) && $settings_updated == true) { ?>
    <div class="thanks">
        <p align="center" class="dspdp-text-success"><?php echo __('Settings Updated.', 'wpdating'); ?></p>
    </div>
<?php } ?>
<?php
//-------------------------------START PRIVACY SETTINGS ------------------------//

if (is_array($member_privacy_settings) && (count($member_privacy_settings) == 0)) {
    ?>
    <script>
        jQuery(document).ready(function(e) {
            jQuery("input[type=checkbox]").attr('checked', 'checked');
        });
    </script>
<?php } ?>

<div class="box-border">
    <div class="box-pedding">  
        <div class="heading-submenu dsp-none">
            <strong><?php echo __('Privacy Setting', 'wpdating'); ?></strong>
        </div>
        <span class="dsp-none"></br></br></span>
        <div class="dsp-form-container">
            <form name="frmprivacysettings" action="" method="post" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="setting-page-account">
                    <div class="dsp-box-container dsp-space">
                        <div class="form-group">
                            <label>
                                <?php echo __('Private messages', 'wpdating'); ?>
                            </label>
                            <select name="private_messages" class="form-control">
                                <?php
                                if ($member_notification_settings->private_messages == 'N') {
                                    ?>
                                    <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                                    <option value="N" selected="selected"><?php echo __('No', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo __('Yes', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
                        </div>                      
                        
	                    <div class="form-group ">
	                        <label>
	                            <?php echo __('Friend requests', 'wpdating'); ?>
	                        </label>
                            <select name="friend_requests" class="form-control">
                                <?php
                                if ($member_notification_settings->friend_request == 'N') {
                                    ?>
                                    <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                                    <option value="N" selected="selected"><?php echo __('No', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y" selected="selected"><?php echo __('Yes', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
	                    </div>
                        <div class="form-group">
                            <label>
                                <?php echo __('Who can view my pictures', 'wpdating'); ?>
                            </label>
                            <select name="view_my_pictures" class="form-control">
                                <?php
                                if ($member_privacy_settings->view_my_pictures == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"  selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
    					
                        <div class="form-group">
                            <label>
                                <?php echo __('Who can view my friends', 'wpdating'); ?>
                            </label>
                            <select name="view_my_friends" class="form-control">
                                <?php
                                if ($member_privacy_settings->view_my_friends == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"  selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
                        </div>

    				    <div class="form-group">	
                            <label>
                                <?php echo __('Who can view my Profile', 'wpdating'); ?>
                            </label>
                            <select name="view_my_profile" class="form-control">
                                <?php
                                if ($member_privacy_settings->view_my_profile == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                    <option value="O"><?php echo __('Only me', 'wpdating'); ?></option>
                                <?php } else if ($member_privacy_settings->view_my_profile == 'N'){ ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"  selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                    <option value="O"><?php echo __('Only me', 'wpdating'); ?></option>
                                <?php } else if ($member_privacy_settings->view_my_profile == 'O'){ ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                    <option value="O" selected="selected"><?php echo __('Only me', 'wpdating'); ?></option>?>
                                <?php } else { ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N" selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                    <option value="O"><?php echo __('Only me', 'wpdating'); ?></option>?>?>
                                <?php } ?>
                            </select>
    					</div>
    					
        				<div class="form-group">	
                            <label><?php echo __('Who can view my Audio', 'wpdating'); ?></label>
                            <select name="view_my_audio" class="form-control">
                                <?php
                                if ($member_privacy_settings->view_my_audio == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"  selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
        				</div>
    					
                        <div class="form-group">
                            <label>
                                <?php echo __('Who can view my Video', 'wpdating'); ?>
                            </label>
                            <select name="view_my_video" class="form-control"> 
                                <?php
                                if ($member_privacy_settings->view_my_video == 'Y') {
                                    ?>
                                    <option value="Y" selected="selected"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } else { ?>
                                    <option value="Y"><?php echo __('Friends', 'wpdating'); ?></option>
                                    <option value="N"  selected="selected"><?php echo __('Everyone', 'wpdating'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                        if (is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php'))
                           do_action('wp_instant_chat_option');
                        ?>                                                    
        				    <div class="select-gender form-group">        
                                <label>
                                    <?php echo __('Contact Permissions (Who can contact me?)', 'wpdating'); ?>
                                </label>
                                <?php /* ?><p><strong class="wid-gender-title"><?php echo __('Gender:', 'wpdating')?></strong></p><?php */ ?>
                                <ul class="contat-permission d-flex no-list">
                                    <?php
                                    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;

                                    $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;

                                    $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");
                                    $check_male_mode  =    $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'male'");
                                    $check_female_mode  =  $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'female'");
                               //     print_r($check_male_mode);

                                    $gender = array();

                                    if($check_male_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'M');
                                    }    
                                    if($check_female_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'F');
                                    }

                                    if($check_couples_mode->setting_status == 'Y')
                                    {
                                        array_push($gender, 'C');
                                    }
                              
                                    $query = "select * from $dsp_gender_list ";


                                    $new_gender_list = array();

                                    $gender_list = $wpdb->get_results($query);

                                    $i = 0; 

                                    foreach ($gender_list as $key=>$value) {

                                        if(in_array($value->enum, $gender)){

                                            $new_gender_list[$i] = new stdClass();

                                            $new_gender_list[$i]->id = $value->id;
                                            $new_gender_list[$i]->gender = $value->gender;
                                            $new_gender_list[$i]->enum = $value->enum;
                                            $new_gender_list[$i]->editable = $value->editable;

                                        }
                            
                                     $i++;
                                    }
                                    $gender_list = $new_gender_list;
                                    $selected_gender = explode(',', $saved_contact_permission['gender']);
                                    foreach ($gender_list as $gender_row) {
                                        if ($gender_row->editable == 'N') {
                                            if (@in_array($gender_row->enum, $selected_gender)) {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" checked="checked" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . $gender_row->gender . '</span></li>';
                                            } else {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . language_code($gender_row->gender) . '</span></li>';
                                            }
                                        } else {
                                            if (@in_array($gender_row->enum, $selected_gender)) {
                                                echo '<li><input type="checkbox" checked="checked" name="privacy_gender[]" value="' . $gender_row->enum . '"> <span class="wid_dsp_gender">' . $gender_row->gender . '</span</li>';
                                            } else {
                                                echo '<li><input type="checkbox" name="privacy_gender[]" value="' . $gender_row->enum . '"><span class="wid_dsp_gender">' . $gender_row->gender . '</span></li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        
                        </div> 
                        <p><input type="hidden" name="update_mode" value="update" /></p>
                        <p><input type="submit" name="submit" value="<?php echo __('Submit', 'wpdating'); ?>" class="dsp_submit_button dspdp-btn dspdp-btn-default"/></p>
                </div>
            </form>
        </div>
    </div>