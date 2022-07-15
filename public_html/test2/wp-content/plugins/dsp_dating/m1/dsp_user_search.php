<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Search', 'wpdating'); ?></h1>
    <?php include_once("page_home.php");?>

</div>
<?php
$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;

$root_link = "";

//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>
<form id="frmsearch" name="frmsearch" >

    <div class="ui-content" data-role="content">
        <div class="content-primary">
           <input type="hidden" name="pagetitle" value="search_result" />

           <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

           <input type="hidden" name="basic_search" value="basic_search" />

           <?php //---------------------------------START  GENERAL SEARCH---------------------------------------  ?>


           <div class="heading-text"><strong><?php echo __('General', 'wpdating'); ?></strong></div>

           <label data-role="fieldcontain" class="select-group">
            <div class="clearfix">
                <?php
                $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
                $genderList = get_gender_list($gender);
                if(!empty($genderList)):
                ?>
                <div class="mam_reg_lf select-label"><?php echo __('I am:', 'wpdating'); ?></div>
                <select name="gender">
                   <?php echo $genderList; ?>
                </select>
                <?php endif; ?>
            </div>
        </label>

        <label data-role="fieldcontain" class="select-group">
            <div class="clearfix">
                <?php
                $seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
                $genderList = get_gender_list($seeking);
                if(!empty($genderList)):
                ?>
                <div class="mam_reg_lf select-label"><?php echo __('Seeking a:', 'wpdating'); ?></div>
                <select name="seeking">
                   <?php echo $genderList; ?>
                </select>
                <?php endif; ?>
            </div>
        </label>

        <div class="heading-text"><strong><?php echo __('Age:', 'wpdating') ?></strong></div>
        <div class="col-cont clearfix">
        <div class="col-2">
                <label class="select-group">
                  <select name="age_from" >

                    <!-- <?php
                    for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                        if ($fromyear == 18) {
                            ?>
                            <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                            <?php
                        }
                    }
                    ?> -->
                    <?php
                    for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
                        if ($fromyear == $min_age_value) {
                            ?>
                            <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                            <?php
                        }
                    }
                    ?>

                </select>
            </label>
        </div>
        <div class="col-2">
            <label class="select-group">

               <div  class="mam_reg_lf select-label">
                <select name="age_to" >
                    <!-- <?php
                    for ($toyear = 18; $toyear <= 99; $toyear++) {
                        if ($toyear == 99) {
                            ?>
                            <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $toyear ?>"><?php echo $toyear ?></option>
                            <?php
                        }
                    }
                    ?> -->
                    <?php
                    for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
                        if ($fromyear == $max_age_value) {
                            ?>
                            <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </label>
    </div>
</div>
<label data-role="fieldcontain" class="select-group">
    <div class="clearfix">
        <div class="mam_reg_lf select-label"><?php echo __('Country:', 'wpdating'); ?></div>
        <select name="cmbCountry" id="cmbCountry_id">
            <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
            <?php
            $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
            foreach ($strCountries as $rdoCountries) {
                echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
            }
            ?>
        </select>
    </div>
</label>
<label data-role="fieldcontain" class="select-group">
    <div class="clearfix">
        <div class="mam_reg_lf select-label"><?php echo __('State:', 'wpdating'); ?></div>

        <div id="state_change">
            <select name="cmbState" id="cmbState_id">
                <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
            </select>
        </div>
        </div>
        </label>

        <label data-role="fieldcontain" class="select-group">
            <div class="clearfix">
                <div class="mam_reg_lf select-label"><?php echo __('City:', 'wpdating'); ?></div>

                <div id="city_change">
                    <select name="cmbCity" id="cmbCity_id">
                        <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                    </select>
                </div>
            </div>
        </label>
        <!-- End city combo-->



         <div class="heading-text"><strong><?php echo __('Additional Options', 'wpdating'); ?></strong></div>
                <label data-role="fieldcontain" class="select-group">
            <div class="clearfix">
                <div class="mam_reg_lf select-label"><?php echo __('Online Only', 'wpdating') ?></div>
                        <select name="Online_only">

                            <option value="N"><?php echo __('No', 'wpdating') ?></option>
                            <option value="Y"><?php echo __('Yes', 'wpdating') ?></option>
                        </select>
                        </div>
                        </label>
                     <label data-role="fieldcontain" class="select-group">
            <div class="clearfix">
                <div class="mam_reg_lf select-label"><?php echo __('With Pictures Only', 'wpdating') ?></div>
                        <select name="Pictues_only">

                            <option value="P"><?php echo __('No Preference', 'wpdating') ?></option>

                            <option value="N"><?php echo __('No', 'wpdating') ?></option>

                            <option value="Y"><?php echo __('Yes', 'wpdating') ?></option>
                        </select>
                    </div>
                    </label>

                        <label data-role="fieldcontain" class="form-group">
                        <div class="clearfix">
                            <div class="mam_reg_lf form-label"><?php echo __('Username: ', 'wpdating'); ?></div>
                        <input type="text" name="username" value="" />
                        </div>
                        </label>
                          <label class="search-label" > <input class="checkbox-singleline" type="checkbox" name="check_save" value="SS" />
                            <?php echo __('Save this search', 'wpdating'); ?></label>
                           <input type="text" name="savesearch" value="" class="input-control" placeholder="<?php echo language_code('SEARCH_NAME'); ?>"/>

                           <input type="hidden" name="search_type" value="basic"/>
                           <div class="btn-blue-wrap">
                            <input type="button" name="submit"  class="mam_btn btn-blue" value="<?php echo __('Submit', 'wpdating'); ?>" onclick="viewSearch(0, 'post');" />
                    </div>
                    </div>


        </div>
        <?php include_once('dspNotificationPopup.php'); // for notification pop up      ?>
    </div>

</form>
<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>