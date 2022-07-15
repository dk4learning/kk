<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <a class="ui-btn-left ui-btn-corner-all ui-icon-back ui-btn-icon-notext ui-shadow"  onclick="viewSetting(0, 'setting')" href="#" >
            </a> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Match Alert', 'wpdating'); ?></h1>
     <?php include_once("page_home.php");?> 

</div>

<?php
$dsp_match_alert_criteria_table = $wpdb->prefix . DSP_MATCH_CRITERIA_TABLE;

if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "update") {

    $active = isset($_REQUEST['active']) ? $_REQUEST['active'] : '';

    $frequency = isset($_REQUEST['frequency']) ? $_REQUEST['frequency'] : '';

    $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

    $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';

    $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';

    $date = date("Y-m-d");



    $check_user = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_match_alert_criteria_table where user_id='$user_id'");


    if ($check_user <= 0) {

        $wpdb->query("INSERT INTO $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date', user_id=$user_id,last_updated_date='$date'");
    } else {

        $wpdb->query("UPDATE $dsp_match_alert_criteria_table SET active = '$active', frequency = '$frequency',gender='$gender',age_from = '$age_from',age_to = '$age_to',date = '$date',last_updated_date='$date' WHERE user_id=$user_id");
    }
    $msg = language_code("DSP_SETTINGS_UPDATED");
}





$match_alert_criteria_query = $wpdb->get_row("SELECT * FROM $dsp_match_alert_criteria_table WHERE user_id = '$user_id' ");

//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>

<div class="ui-content" data-role="content">
    <div class="content-primary">	
                       <form name="frmuseraccount" method="post" action="" id="dspAccount">

                
                            <?php if (isset($msg)) {
                                ?>
                                <div class="success-message">
                                    <span>&nbsp;</span>
                                    <?php echo $msg; ?>
                                </div>
                            <?php } ?>
                            <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo __('Active', 'wpdating') ?>:</div>  
                                <select name="active">

                                    <?php if ($match_alert_criteria_query->active == 'N') { ?>

                                        <option value="Y"><?php echo __('Yes', 'wpdating') ?> </option>

                                        <option value="N" selected="selected"><?php echo __('No', 'wpdating') ?></option>

                                    <?php } else if ($match_alert_criteria_query->active == 'Y') { ?>

                                        <option value="Y" selected="selected"><?php echo __('Yes', 'wpdating') ?> </option>

                                        <option value="N"><?php echo __('No', 'wpdating') ?></option>

                                    <?php } else { ?>

                                        <option value="Y"><?php echo __('Yes', 'wpdating') ?> </option>

                                        <option value="N" selected="selected"><?php echo __('No', 'wpdating') ?></option>

                                    <?php } ?>

                                </select>
                                </div>
                                </label>
                            <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo __('Frequency', 'wpdating') ?>:</div>
                                <select name="frequency">

                                    <?php if ($match_alert_criteria_query->frequency == 'W') { ?>

                                        <option value="D"><?php echo __('Daily', 'wpdating') ?> </option>

                                        <option value="W" selected="selected"><?php echo __('Weekly', 'wpdating') ?></option>

                                        <option value="M"><?php echo __('Monthly', 'wpdating') ?></option>

                                    <?php } else if ($match_alert_criteria_query->frequency == 'D') { ?>

                                        <option value="D" selected="selected"><?php echo __('Daily', 'wpdating') ?> </option>

                                        <option value="W"><?php echo __('Weekly', 'wpdating') ?></option>

                                        <option value="M"><?php echo __('Monthly', 'wpdating') ?></option>	

                                    <?php } else if ($match_alert_criteria_query->frequency == 'M') { ?>

                                        <option value="D"><?php echo __('Daily', 'wpdating') ?> </option>

                                        <option value="W"><?php echo __('Weekly', 'wpdating') ?></option>

                                        <option value="M" selected="selected"><?php echo __('Monthly', 'wpdating') ?></option>

                                    <?php } else { ?>

                                        <option value="D"><?php echo __('Daily', 'wpdating') ?> </option>

                                        <option value="W" selected="selected"><?php echo __('Weekly', 'wpdating') ?></option>

                                        <option value="M"><?php echo __('Monthly', 'wpdating') ?></option>

                                    <?php } ?>

                                </select>
                            </div>
                            </label>
                            <label data-role="fieldcontain" class="select-group">  
            <div class="clearfix">                                    
                <div class="mam_reg_lf select-label"><?php echo __('Gender:', 'wpdating') ?></div>
                                <select name="gender" >

                                    <?php if ($match_alert_criteria_query->gender == 'F') { ?>

                                        <option value="M"><?php echo __('Man', 'wpdating'); ?></option>

                                        <option value="F" selected="selected"><?php echo __('Woman', 'wpdating'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo __('Couples', 'wpdating'); ?></option>

                                        <?php } ?>



                                    <?php } else if ($match_alert_criteria_query->gender == 'M') { ?>



                                        <option value="M" selected="selected"><?php echo __('Man', 'wpdating'); ?></option>

                                        <option value="F"><?php echo __('Woman', 'wpdating'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo __('Couples', 'wpdating'); ?></option>

                                        <?php } ?>



                                    <?php } else if ($match_alert_criteria_query->gender == 'C') { ?>



                                        <option value="M"><?php echo __('Man', 'wpdating'); ?></option>

                                        <option value="F"><?php echo __('Woman', 'wpdating'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C"  selected="selected"><?php echo __('Couples', 'wpdating'); ?></option>

                                        <?php } ?>



                                    <?php } else { ?>



                                        <option value="M"><?php echo __('Man', 'wpdating'); ?></option>

                                        <option value="F" selected="selected"><?php echo __('Woman', 'wpdating'); ?></option>

                                        <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                                            <option value="C" ><?php echo __('Couples', 'wpdating'); ?></option>	

                                        <?php } ?>  

                                    <?php } ?>

                                </select>
                            </div>
                            </label>
                             <div class="heading-text"><?php echo __('Age:', 'wpdating') ?></div>
                             <div class="col-cont clearfix">
            <div class="col-2">
                <label class="select-group">
                                <select name="age_from" >

                                    <?php if ($match_alert_criteria_query->age_from != '') { ?>

                                        <?php for ($i = $match_alert_criteria_query->age_from; $i <= $max_age_value; $i++) { ?>

                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>

                                        <?php } ?>



                                    <?php } else { ?>



                                        <?php for ($i = $min_age_value; $i <= $max_age_value; $i++) { ?>

                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>

                                        <?php } ?>

                                    <?php } ?>
                                </select>
                                </label>
        </div>
        <div class="col-2">
            <label class="select-group">

             <div  class="mam_reg_lf select-label"> 
<select  name="age_to" style="width: 60px;">

                                        <?php if ($match_alert_criteria_query->age_to != '') { ?>



                                            <?php for ($j = $match_alert_criteria_query->age_to; $j >= $min_age_value; $j--) { ?>

                                                <option value="<?php echo $j ?>"><?php echo $j ?></option>

                                            <?php } ?>



                                        <?php } else { ?>



                                            <?php for ($j = $max_age_value; $j >= $min_age_value; $j--) { ?>

                                                <option value="<?php echo $j ?>"><?php echo $j ?></option>

                                            <?php } ?>

                                        <?php } ?>



                                    </select>
                                </div>
        </label>
    </div>
</div>
                               
                                <input type="hidden" name="pagetitle" value="<?php echo $profile_pageurl; ?>" />
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                                <input type="hidden" name="mode" value="<?php echo 'update'; ?>" />
                                 <div class="btn-blue-wrap">
                                <input type="button" onclick="viewSetting(0, 'post')" class="mam_btn btn-red" name="submit" value="Save" /></li>
                        </div>
                       


                        <div class="spacer-top notice-message"><?php echo __('<b>Note:</b> Matches are sent to the email you registered with. If you don't recieve them, please check your spam folder.', 'wpdating') ?></div>


                    </div>



                </form>
            

    </div>


    <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
</div>



<?php
//------------------------------------- END ACCOUNT SETTINGS  ------------------------------------------ // ?>