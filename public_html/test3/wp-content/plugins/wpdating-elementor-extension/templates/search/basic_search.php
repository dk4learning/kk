<?php
   /* 
   * THIS FILE WILL BE UPDATED WITH EVERY UPDATE
   * IF YOU WANT TO MODIFY THIS FILE, CREATE A CHILD THEME
   *
   * http://codex.wordpress.org/Child_Themes
   */
   
    global $wpdb;
    //For min and max age
    $check_min_age = wpee_get_setting('min_age');
    $min_age_value = $check_min_age->setting_value;
    $check_max_age = wpee_get_setting('max_age');
    $max_age_value = $check_max_age->setting_value;
    $check_near_me = wpee_get_setting('near_me');

    $current_user_profile = wpee_get_user_profile_by( array( 'user_id' => get_current_user_id() ) );
    //search option default value
    if( $current_user_profile ) {
        $selected_gender     = $current_user_profile->gender;
        $selected_seeking    = $current_user_profile->seeking;
        $selected_country_id = $current_user_profile->country_id;
        $selected_state_id   = $current_user_profile->state_id;
        $selected_city_id    = $current_user_profile->city_id;
    } else {
        $selected_gender     = '';
        $selected_seeking    = '';
        $selected_country_id = 0;
        $selected_state_id   = 0;
        $selected_city_id    = 0;
    }

    if( $selected_country_id == 0 ){
        $default_country     = wpee_get_setting('default_country');
        $selected_country_id = isset($default_country->setting_value) ? $default_country->setting_value : 0;
    }
   ?>
   	<form class="dspdp-form-horizontal dsp-form-container" name="frmsearch" method="post"
          action="<?php echo esc_url( Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url() ); ?>">
   		<?php wp_nonce_field( 'wpee_basic_search', 'wpee-basic-search' );?>
        <div class="general-search">
            <div class="heading-submenu dsp-none"><strong><?php echo esc_html__('General', 'wpdating'); ?></strong></div>
            <div class="edit-profile">
                <div class="form-inline">
                    <?php 
                       $gender_list = get_gender_list($selected_gender);
                       if(!empty($gender_list)):?>
                        <div class="form-group">
                            <label>
                                <?php echo esc_html__('I am:', 'wpdating'); ?>
                            </label>
                            <select class="dspdp-form-control dsp-form-control" name="gender">
                                <?php echo $gender_list; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php 
                       $seeking_list = get_gender_list($selected_seeking);
                       if(!empty($seeking_list)): ?>
                        <div class="form-group">
                            <label>
                                <?php echo esc_html__('Seeking a:', 'wpdating'); ?>
                            </label>
                            <select name="seeking" class="dspdp-form-control dsp-form-control">
                                <?php echo $seeking_list; ?>
                            </select>
                        </div>
                    <?php endif; ?>
               </div>
                
                <div class="form-group age-field">
                    <label>
                        <?php echo esc_html__('Age:', 'wpdating') ?>
                    </label> 
                    <select name="age_from" class="dspdp-form-control dsp-form-control"> 
                        <?php
                        for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) :
                            if ($fromyear == $min_age_value) : ?>
                                <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
                            <?php 
                            else : ?>
                                <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
                                <?php
                            endif;
                        endfor; ?>
                    </select>
                    <select name="age_to" class="dspdp-form-control dsp-form-control">
                        <?php
                        for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) :
                            if ($fromyear == $max_age_value) : ?>
                                <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
                            <?php 
                            else : ?>
                                <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
                                <?php
                            endif;
                        endfor; ?>
                    </select>
                </div>

                <div class="form-inline">
                    <div class="form-group">
                        <label>
                            <?php echo esc_html__('Country:', 'wpdating'); ?>
                        </label>
                        <select name="cmbCountry" class="country_trigger dspdp-form-control dsp-form-control">
                            <option value="0">
                                <?php echo esc_html__('Select Country', 'wpdating'); ?>
                            </option>
                            <?php
                                $countries = wpee_get_countries();
                                foreach ($countries as $country) : ?>
                                    <option value='<?php echo esc_attr($country->country_id); ?>' <?php echo ($country->country_id == $selected_country_id) ? "selected = selected" : ""; ?> >
                                        <?php echo esc_html($country->name); ?>
                                    </option>
                                <?php
                                endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <?php echo esc_html__('State:', 'wpdating'); ?>
                        </label>
                        <div id="state_change">
                            <select name="cmbState" class="state_trigger dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo esc_html__('Select State', 'wpdating'); ?></option>
                                <?php 
                                if ($selected_country_id != 0) :
                                    $states = apply_filters('dsp_get_all_States_Or_City',$selected_country_id);
                                    if(isset($states) && !empty($states)) :
                                        foreach ($states as $state) : ?>
                                            <option value='<?php echo esc_attr($state->state_id); ?>' <?php echo ($state->state_id == $selected_state_id) ? "selected = selected" : ""; ?> >
                                                <?php echo esc_html($state->name); ?>
                                             </option>
                                <?php   endforeach;
                                    endif;
                                endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-inline">
                    <div class="form-group">
                        <label><?php echo esc_html__('City:', 'wpdating'); ?></label> 
                        <div id="city_change">
                            <select name="cmbCity" class="city_trigger dspdp-form-control dsp-form-control">
                                <option value="0"><?php echo esc_html__('Select City', 'wpdating'); ?></option>
                                <?php
                                $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
                                if ($selected_state_id != 0) {
                                    $cities = $wpdb->get_results("SELECT * FROM {$dsp_city_table} where country_id='{$selected_country_id}' and state_id='{$selected_state_id}' ORDER BY name");
                                } else {
                                    $cities = $wpdb->get_results("SELECT * FROM {$dsp_city_table} WHERE country_id='{$selected_country_id}' ORDER BY name");
                                }
                                if(isset($cities) && !empty($cities)):
                                    foreach ($cities as $city) : ?>
                                        <option value='<?php echo esc_attr($city->city_id); ?>' <?php echo ($city->city_id == $selected_city_id) ? "selected = selected" : ""; ?> ><?php echo esc_html($city->name); ?></option>
                                <?php
                                    endforeach;
                                endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo esc_html__('Username: ', 'wpdating'); ?></label>
                        <input name="username" type="text" class="dspdp-form-control dsp-form-control lm-username" />
                    </div>
                </div>
            </div>
        </div>

		<div class="additional-search">
            <div class="heading-submenu dsp-none"><strong><?php echo esc_html__('Additional Options', 'wpdating'); ?></strong></div>
            <div class="heading margin-btm-2 dsp-block" style="display:none">
                <h3><?php echo esc_html__('Additional Options', 'wpdating'); ?></h3>
            </div>
            <div class="edit-profile">
                <div class="form-inline">
                    <div class="form-group">
                        <label><?php echo esc_html__('Online Only', 'wpdating') ?>:</label>                        
                        <select name="Online_only"  class="dspdp-form-control dsp-form-control">
                            <option value="N"><?php echo esc_html__('No', 'wpdating') ?></option>
                            <option value="Y"><?php echo esc_html__('Yes', 'wpdating') ?></option>
                        </select>
                    </div>
                    <div class="form-group"><label><?php echo esc_html__('With Pictures Only', 'wpdating') ?>:</label>
                        <select name="Pictues_only"  class="dspdp-form-control dsp-form-control">
                            <option value="P"><?php echo esc_html__('No Preference', 'wpdating') ?></option>
                            <option value="N"><?php echo esc_html__('No', 'wpdating') ?></option>
                            <option value="Y"><?php echo esc_html__('Yes', 'wpdating') ?></option>
                        </select>
    				</div>
                </div>
                <?php if (is_user_logged_in()) : ?>
                    <div class="form-group save-search">
                        <label><input type="checkbox" name="check_save" value="SS" />
    					<?php echo esc_html__('Save this search', 'wpdating'); ?>:&nbsp;</label>
                            
    					<input  class="dspdp-form-control dsp-form-control" type="text" name="savesearch" value="" placeholder="<?php echo esc_html__('Give title to your saved search', 'wpdating') ?>"/><input type="hidden" name="search_type" value="basic_search"/>
                    </div>
                <?php endif;    ?>
            </div>
        </div>
        <div class="form-group">
	       <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dsp-block dspdp-btn-default" value="Submit" />
        </div>
	</form>