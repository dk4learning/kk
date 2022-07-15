<?php
//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>
<form name="frmadvsearch" method="GET" action="">
    <input type="hidden" name="pid" value="5" />
    <input type="hidden" name="pagetitle" value="search_result" />
    <?php //---------------------------------START  ADVANCED SEARCH--------------------------------------- ?>
    <script  type="text/javascript" language="javascript">
        // State lists
        var states = new Array();
        // City lists
        var cities = new Array();
    </script>
    <?php 
# get state list
    $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
    foreach ($strCountries as $rdoCountries) {
        $strStateList = "";
        $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . " ORDER BY name");
        foreach ($strStates as $rdoStates) {
            $strStateList .= "'" . $rdoStates->name . "',";
        } // end $rdoStates
        if (strlen($strStateList) > 0) {
            $strStateList = substr($strStateList, 0, strlen($strStateList) - 1);
        }
        ?>
        <script  type="text/javascript" language="javascript">
            states['<?php echo $rdoCountries->name; ?>'] = new Array('Select',<?php echo $strStateList; ?>);
        </script>
        <?php
        $bolinitialiseArray = true;
        $strStates = $wpdb->get_results("SELECT * FROM $dsp_state_table WHERE country_id = " . $rdoCountries->country_id . " ORDER BY name");
        foreach ($strStates as $rdoStates) {
            if ($bolinitialiseArray == true) {
                ?>
                <script  type="text/javascript" language="javascript">
                    cities['<?php echo $rdoCountries->name; ?>'] = new Array();
                </script>
                <?php
            }
            $strCityList = "";
            $strCities = $wpdb->get_results("SELECT * FROM $dsp_city_table WHERE country_id = " . $rdoCountries->country_id . " AND state_id = " . $rdoStates->state_id . " ORDER BY name");
            foreach ($strCities as $rdoCities) {
                $replacequotesfromcity = str_replace("'", "`", $rdoCities->name);
                $strCityList .= "'" . $replacequotesfromcity . "',";
            } // end $rdocitie
            if (strlen($strCityList) > 0) {
                $strCityList = substr($strCityList, 0, strlen($strCityList) - 1);
            }
            ?>
            <script  type="text/javascript" language="javascript">
                cities['<?php echo $rdoCountries->name; ?>']['<?php echo $rdoStates->name; ?>'] = new Array('Select',<?php echo $strCityList; ?>);
            </script>
            <?php
            $bolinitialiseArray = false;
        } // end $rdoStates
    } // end $rdoCountries
    ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <div class="box-page">
                <div class="heading-text"><strong><?php echo __('General', 'wpdating'); ?></strong></div>
                <div class="advance-search-page">
                    <ul class="edit-profile">
                        <li><span><?php echo __('I am:', 'wpdating') ?></span> <select name="gender">
                                <option value="M"><?php echo __('Man', 'wpdating'); ?></option>
                                <option value="F"><?php echo __('Woman', 'wpdating'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C"><?php echo __('Couples', 'wpdating'); ?></option>
                                <?php } ?>
                            </select></li>
                        <li><span><?php echo __('Seeking a:', 'wpdating'); ?></span> 
                            <select name="seeking">
                                <option value="F"><?php echo __('Woman', 'wpdating'); ?></option>
                                <option value="M"><?php echo __('Man', 'wpdating'); ?></option>
                                <?php if ($check_couples_mode->setting_status == 'Y') { ?>
                                    <option value="C"><?php echo __('Couples', 'wpdating'); ?></option>
                                <?php } ?>
                            </select></li>
                        <li><span><?php echo __('Age:', 'wpdating'); ?></span> 
                            <select name="age_from" style="width:50px;"> 
                                <!-- <?php
                                for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                                    if ($fromyear == 18) {
                                        ?>
                                        <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                                    <?php } else { ?>
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

                            <select name="age_to" style="width:50px;">
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
                        </li>
                        <li><span><?php echo __('Country:', 'wpdating'); ?></span>
                            <select name="cmbCountry"  style="width:30%;" id="cmbCountry_id1" onchange="javascript :setStates();">
                                <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                                <?php
                                $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                                foreach ($countries as $country) {
                                    ?>
                                    <option value="<?php echo $country->name; ?>" ><?php echo $country->name; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        <li><span><?php echo __('State:', 'wpdating'); ?></span>
                            <!--onChange="Show_state(this.value);"-->
                            <select name="cmbState" id="cmbState_id1" style="width:110px;" onchange="javascript : setCities();">
                                <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
                            </select>
                        </li>
                        <!-- End City combo-->
                        <li><span><?php echo __('City:', 'wpdating'); ?></span>
                            <!--onChange="Show_state(this.value);"-->
                            <select name="cmbCity" id="cmbCity_id1">
                                <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                            </select>
                        </li>
                        <!-- End city combo-->
                        <li><span><?php echo __('With Pictures Only', 'wpdating'); ?>:</span> 
                            <select name="Pictues_only">
                                <option value="P"><?php echo __('No Preference', 'wpdating'); ?></option>
                                <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                                <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                            </select></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php //-----------------------------------------END GENERAL SEARCH-------------------------------------------//  ?>
    <div class="gap-bottom"></div>
    <?php //-------------------------------------START ADDITIONAL OPTIONS SEARCH -------------------------------------//    ?>
    <div class="dsp_box-out">
        <div class="dsp_box-in">
            <div class="box-page">
                <div class="advance-search-page">
                    <div class="heading-text"><strong><?php echo __('Additional Options', 'wpdating'); ?></strong></div>
                    <?php
                    $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where field_type_id=1 Order by sort_order");
                    foreach ($myrows as $profile_questions) {
                        $ques_id = $profile_questions->profile_setup_id;
                        $profile_ques = $profile_questions->question_name;
                        $profile_ques_type_id = $profile_questions->field_type_id;
                        ?>
                        <div class="text-title"><strong><?php echo $profile_ques; ?></strong></div>
                        <ul class="option-btn-adv">
                            <?php
                            $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                            $i = 0;
                            foreach ($myrows_options as $profile_questions_options) {
                                if (($i % 3) == 0) {
                                    ?>
                                <?php } ?>
                                <li><input type="checkbox" name="profile_question_option_id[]" value="<?php echo $profile_questions_options->question_option_id ?>"/>&nbsp;<?php echo $profile_questions_options->option_value ?></li>
                                <?php
                                $i++;
                            }
                            ?>

                        </ul>
                    <?php } ?>
                    <?php
                    // only login member can save search result 
                    if (is_user_logged_in()) {
                        ?>
                        <ul>
                            <li><?php echo __('Save this Search as:', 'wpdating'); ?>&nbsp;<input type="checkbox" name="check_save" value="SS" />&nbsp;<input type="text" name="savesearch" value=""/><input type="hidden" name="search_type" value="Advanced"/></li>
                        <?php } // if ( is_user_logged_in() )   ?>
                        <li><input type="submit" name="submit" class="dsp_submit_button" value="<?php echo __('Submit', 'wpdating') ?>" onclick="search_by_quick_widget();" /></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>