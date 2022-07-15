<?php
//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>
<form class="dspdp-form-horizontal" name="frmsearch" method="GET" action="<?php echo $root_link . "search/search_result/"; ?>">

    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>
    <div class="heading-submenu dsp-block" style="display:none"><?php echo __('Advanced Search', 'wpdating') ?></div>
    <div class="box-border dsp-form-container">
        <div class="box-pedding">
            <div class="heading-submenu dsp-none"><strong><?php echo __('General', 'wpdating'); ?></strong></div>
            <div class="advance-search-page dspdp-search-options dsp-box-container dsp-space">
                <div class="heading margin-btm-2 dsp-block" style="display:none">
                    <h3><?php echo __('General', 'wpdating'); ?></h3>
                </div>
                <ul class="edit-profile">
                    <?php 
                       $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
                       $genderList = get_gender_list($gender);
                       if(!empty($genderList)):
                    ?>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('I am:', 'wpdating') ?></span><span class="dspdp-col-sm-6 dsp-sm-6">
                                 <select name="gender" class="dspdp-form-control dsp-form-control">
                                    <?php echo $genderList; ?>
                                </select>
                            </span>
                        </li>
                    <?php endif; ?>

                    <?php 
                       $seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
                       $genderList = get_gender_list($seeking);
                       if(!empty($genderList)):
                    ?>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                <?php echo __('Seeking a:', 'wpdating'); ?>
                            </span> 
                            <span class="dspdp-col-sm-6 dsp-sm-6">
                                <select name="seeking" class="dspdp-form-control dsp-form-control">
                                    <?php echo $genderList; ?>
                                </select>
                            </span>
                        </li>
                   <?php endif; ?>
                    
                    
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('Age:', 'wpdating'); ?></span> 
                        <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-xs-form-group"><select name="age_from"  class="dspdp-form-control dsp-form-control"> 
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
                        </select></span>

                        <span class="dspdp-col-sm-3 dsp-sm-3"><select name="age_to"  class="dspdp-form-control dsp-form-control">
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
                        </select></span>
                    </li>
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('Country:', 'wpdating'); ?></span>
                        <span class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbCountry" id="cmbCountry_id" class="dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                                    <?php
                                        $selectedCountryId = isset($check_default_country->setting_value) ? $check_default_country->setting_value : 0;
                                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                                        foreach ($strCountries as $rdoCountries) {
                                            $selected = ($rdoCountries->country_id == $selectedCountryId) ? "selected = selected" : "";
                                            echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
                                        }
                                    ?>
                            </select>
                    </span>
                    </li>
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('State:', 'wpdating'); ?></span>
                        <!--onChange="Show_state(this.value);"-->
                        <div id="state_change" class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbState" id="cmbState_id"  class="dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
                                <?php 
                                    if ($selectedCountryId != 0) {
                                        $selectedCountriesStates = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId);
                                        if(isset($selectedCountriesStates) && !empty($selectedCountriesStates)):
                                            foreach ($selectedCountriesStates as $state) {
                                                echo "<option value='" . $state->state_id . "' >" . $state->name . "</option>";
                                            } 
                                        endif;
                                    }
                                ?>
                            </select>
                        </div>
                    </li class="dspdp-form-group dsp-form-group">
                    <!-- End City combo-->
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('City:', 'wpdating'); ?></span> 
                        <!--onChange="Show_state(this.value);"-->
                        <div id="city_change" class="dspdp-col-sm-6 dsp-sm-6">
                            <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                                <?php 
                                    if ($selectedCountryId != 0) {
                                        $strStatesCheck = 0;
                                        $strStatesCheck = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table where country_id='$selectedCountryId'");
                                        if ($strStatesCheck == 0){
                                            $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City', $selectedCountryId, true);
                                        }

//                                        $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId,true);
                                        if(isset($selectedCountriesCities) && !empty($selectedCountriesCities)):
                                            foreach ($selectedCountriesCities as $city) {
                                                echo "<option value='" . $city->city_id . "' >" . $city->name . "</option>";
                                            } 
                                        endif;
                                    }
                                ?>
                            </select>
                        </div>
                    </li>
                    <!-- End city combo-->
                    <li class="dspdp-form-group dsp-form-group"><span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label"><?php echo __('With Pictures Only', 'wpdating'); ?>:</span> 
                        <span class="dspdp-col-sm-6 dsp-sm-6"><select name="Pictues_only" class="dspdp-form-control dsp-form-control">
                            <option value="P"><?php echo __('No Preference', 'wpdating'); ?></option>
                            <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                            <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                        </select></span></li>
                </ul>
            </div>
                   
        </div>
    </div>
    <?php //-----------------------------------------END GENERAL SEARCH-------------------------------------------// ?>

    <?php //-------------------------------------START ADDITIONAL OPTIONS SEARCH -------------------------------------//   ?>

    <div class="box-border magn-top-15 dsp-form-container margin-btm-3">
        <div class="box-pedding">
            <div class="advance-search-page dspdp-search-options dsp-box-container dsp-space">
                <div class="heading-submenu dsp-none"><strong><?php echo __('Additional Options', 'wpdating'); ?></strong></div>
                 <div class="heading margin-btm-2 dsp-block" style="display:none">
                    <h3><?php echo __('Additional Options', 'wpdating'); ?></h3>
                </div>
                <div style="clear:both;"></div>
                <div class="dsp-row">
                <?php
                $lang_code = dsp_get_current_user_language_code(); 
                if($lang_code=='en')
                {
                    $dsp_question_options_table = $wpdb->prefix . "dsp_question_options";
                    $dsp_profile_setup_table = $wpdb->prefix . "dsp_profile_setup";
                }
                else
                {
                    $dsp_question_options_table = $wpdb->prefix . "dsp_question_options_" . $lang_code;
                    $dsp_profile_setup_table = $wpdb->prefix . "dsp_profile_setup_" . $lang_code;
                }
                
                $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where display_status = 'Y' Order by sort_order");
                foreach ($myrows as $profile_questions) {
                    $ques_id = $profile_questions->profile_setup_id;
                    $profile_ques = $profile_questions->question_name;
                    $profile_ques_type_id = $profile_questions->field_type_id;
                    ?>

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo "$ques_id"?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo "$ques_id"?>" aria-expanded="true" aria-controls="collapse<?php echo "$ques_id"?>">
        <div class="text-title text-title-<?php echo $ques_id; ?>">
        <span class="icon-plus" style="margin-right: 10px;"></span>
            <strong><?php echo __($profile_ques,'wpdating') ; ?></strong></div>
        </a>
      </h4>
    </div>
    <div id="collapse<?php echo "$ques_id"?>" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading<?php echo "$ques_id"?>">
      <div class="panel-body">
      <ul class="option-btn-adv dspdp-row dsp-row">
                                <?php
                                $myrows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");
                                $i = 0;
                                foreach ($myrows_options as $profile_questions_options) {
                                    if (($i % 3) == 0) {
                                        ?>
                                    <?php } ?>
                                    <li class="dspdp-col-sm-4 dsp-sm-4">
                                    <input type="checkbox" name="profile_question_option_id[]" value="<?php echo $profile_questions_options->question_option_id ?>"/>&nbsp;
                                    <?php echo __($profile_questions_options->option_value,'wpdating') ?>
                                    </li>
                                    <?php
                                    $i++;
                                }
                                ?>

                            </ul>          
    </div>
    </div>
  </div>
  
  
</div>
                   

					<span class="dspdp-seprator"></span>
                <?php } ?>
                 </div>
                <?php
                // only login member can save search result 
                if (is_user_logged_in()) {
                    ?>
                    <ul>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="lavish-save-search dspdp-col-sm-12 dsp-sm-4 dspdp-col-xs-10 dsp-xs-10 dspdp-control-label dsp-control-label">
                                <?php echo __('Save this Search as:', 'wpdating'); ?>&nbsp; 
                                <input type="checkbox" class="dspdp-reset-strict" name="check_save" value="SS" />&nbsp;
                            </span>
                            <span  class="lavish-input-search dspdp-col-xs-12 dsp-xs-12 dspdp-col-sm-12 dsp-sm-4 dspdp-xs-form-group dsp-form-group">
                                <input type="text" name="savesearch" value="" class="dspdp-form-control dsp-form-control" placeholder="<?php echo __('Give title to your saved search', 'wpdating') ?>"/>
                                <input type="hidden" name="search_type" value="advance_search"/>
                            </span>
                <?php } // if ( is_user_logged_in() )    ?>
                        <span class="dspdp-col-xs-12 dsp-xs-12 dspdp-col-sm-3 dsp-sm-3 dsp-none">
                            <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo __('Submit', 'wpdating') ?>" onclick="search_by_quick_widget();" />
                        </span>
                        </li>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="lavish-save-search dspdp-col-sm-4 dsp-sm-4 dspdp-col-xs-10 dsp-xs-10 dspdp-control-label dsp-control-label"><?php echo __('Online Only', 'wpdating') ?>:</span>
                            <span class="dspdp-col-sm-4 dsp-sm-4">
                                <select name="Online_only"  class="dspdp-form-control dsp-form-control">
                                <option value="N"><?php echo __('No', 'wpdating') ?></option>
                                <option value="Y"><?php echo __('Yes', 'wpdating') ?></option>
                                </select>
                            </span>
                        </li>
                    </ul>
            </div>
        </div>
    </div>
    <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default dsp-block" value="<?php echo __('Submit', 'wpdating') ?>" onclick="search_by_quick_widget();" style="display:none" />
</form>
