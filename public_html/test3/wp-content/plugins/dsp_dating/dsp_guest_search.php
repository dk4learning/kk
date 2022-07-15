<form name="frmguestsearch" method="GET" action="<?php echo $root_link . "g_search_result"; ?>">
    <input type="hidden" name="search_type" value="basic_search" />
    <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>
    <div class="box-border">
        <div class="box-pedding">
            <div class="box-page guest-search">
                <?php 
                   $genderList = get_gender_list('M');
                   if(!empty($genderList)):
                ?>
                    <p><span class="dsp_left"><?php echo __('I am:', 'wpdating'); ?></span>
                        <select name="gender">
                            <?php echo $genderList; ?>
                        </select>
                    </p>
                <?php endif; ?>                
                <p>
                    <span class="dsp_left"><?php echo __('Seeking a:', 'wpdating'); ?></span>
                    <select name="seeking">
                        <?php echo get_gender_list('F'); ?>
                    </select>
                </p>	 
                <p>
                    <span class="dsp_left"><?php echo __('Age:', 'wpdating'); ?></span>
                    <select name="age_from" style="width:50px;"> 
                        <?php
                        for ($fromyear = 18; $fromyear <= 50; $fromyear++) {
                            if ($fromyear == 18) {
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
                        <?php
                        for ($toyear = 18; $toyear <= 50; $toyear++) {
                            if ($toyear == 50) {
                                ?>
                                <option value="<?php echo $toyear ?>" selected="selected"><?php echo $toyear ?></option>
                            <?php } else { ?>
                                <option value="<?php echo $toyear ?>"><?php echo $toyear ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <span class="dsp_left dsp-control-label"><?php echo __('Country:', 'wpdating'); ?></span>
                    <select name="cmbCountry" id="cmbCountry_id" class="dsp-form-control">
                        <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                        <?php
                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                        foreach ($strCountries as $rdoCountries) {

                            echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                        }
                        ?>
                    </select>
                </p>
                <p style="float:left;">
                    <span class="dsp_left"><?php echo __('State:', 'wpdating'); ?></span>
                    <!--onChange="Show_state(this.value);"-->
                <div id="state_change">
                    <select name="cmbState" id="cmbState_id" style="width:110px;" class="dspdp-form-control">
                        <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
                    </select>
                </div>
                </p>
                <!-- End City combo-->
                <p  style="float:left;">
                    <span class="dsp_left"><?php echo __('City:', 'wpdating'); ?></span>
                    <!--onChange="Show_state(this.value);"-->
                <div id="city_change">
                    <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control">
                        <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                    </select>
                </div>
                </p>
                <!-- End city combo-->
                <span class="dsp_left"><?php echo __('Online Only', 'wpdating'); ?>:</span>
                <select name="Online_only" class="dspdp-form-control">
                    <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                    <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                </select>
                </p>
                <p>
                    <span class="dsp_left"><?php echo __('With Pictures Only', 'wpdating'); ?>:</span>
                    <select name="Pictues_only" class="dspdp-form-control">
                        <option value="P"><?php echo __('No Preference', 'wpdating'); ?></option>
                        <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                        <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                    </select>
                </p>
                <p>
                    <span class="dsp_left">&nbsp;</span>
                <div class="dsp_right">
                    <input type="submit" name="btnsubmit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo __('Search', 'wpdating'); ?>" onclick="dsp_guest_search();" />
                </div>
                </p>
            </div>
        </div>
    </div>
</form>