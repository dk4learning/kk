<?php 
global $wpdb;
$check_min_age   = wpee_get_setting('min_age');
$min_age_value   = $check_min_age->setting_value;
$check_max_age   = wpee_get_setting('max_age');
$max_age_value   = $check_max_age->setting_value;
$check_near_me   = wpee_get_setting('near_me');
$member_page_url =  Wpdating_Elementor_Extension_Helper_Functions::get_members_page_url();

//search option default value
$selected_gender     = '';
$selected_seeking    = '';
$selected_country_id = 0;
if( is_user_logged_in() ) {
	$current_user_id      = get_current_user_id();
	$current_user_profile = wpee_get_user_profile_by( array( 'user_id' => $current_user_id ) );
	$advance_search_url   = wpee_get_profile_url_by_id($current_user_id) . '/search/advance-search';

	if( $current_user_profile ){
		$selected_gender     = $current_user_profile->seeking;
		$selected_seeking    = $current_user_profile->gender;
		$selected_country_id = $current_user_profile->country_id;
	}
}

if( $selected_country_id == 0 ){
	$default_country     = wpee_get_setting('default_country');
	$selected_country_id = isset($default_country->setting_value) ? $default_country->setting_value : 0;
}

?>
<div class="profile-quick-search wpee-block">
	<div class="wpee-block-header">
		<h4 class="wpee-block-title"><?php esc_html_e( 'Quick Search', 'wpdating');?></h4>
	</div>
	<div class="profile-quick-search-inner wpee-block-content">
		<?php 
	   ?>
	   	<form class="dspdp-form-horizontal dsp-form-container" name="frmsearch" method="post" action="<?php echo esc_url($member_page_url); ?>">
	   		<?php wp_nonce_field( 'wpee_basic_search', 'wpee-basic-search' );?>
	        <div class="box-pedding dsp-space  general-search">
	            <div class="heading-submenu dsp-none"><strong><?php echo esc_html__('General', 'wpdating'); ?></strong></div>
	            <div class="heading margin-btm-2 dsp-block" style="display:none">
	                <h3><?php echo esc_html__('General', 'wpdating'); ?></h3>
	            </div>
	            <ul class="edit-profile">
	                <?php 
	                   $gender_list = wpee_get_gender_list($selected_gender);
	                   if(!empty($gender_list)):
	                ?>
	                    <li class="dspdp-form-group dsp-form-group">
	                        <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
	                            <?php echo esc_html__('I am:', 'wpdating'); ?>
	                        </span>
	                        <span class="dspdp-col-sm-6 dsp-sm-6">
	                            <select class="dspdp-form-control dsp-form-control" name="gender">
	                                <?php echo $gender_list; ?>
	                            </select>
	                        </span>
	                    </li>
	                <?php endif; ?>

	                <?php 
	                   $gender_list = wpee_get_gender_list($selected_seeking);
	                   if(!empty($gender_list)):
	                ?>
	                    <li class="dspdp-form-group dsp-form-group">
	                        <span class="dspdp-control-label dsp-control-label dsp-sm-3 dspdp-col-sm-3">
	                            <?php echo esc_html__('Seeking a:', 'wpdating'); ?>
	                        </span>
	                        <span class="dspdp-col-sm-6 dsp-sm-6">
	                            <select name="seeking" class="dspdp-form-control dsp-form-control">
	                                <?php echo $gender_list; ?>
	                            </select>
	                        </span>
	                    </li>
	                <?php endif; ?>
	               
	                
	                <li class="dspdp-form-group dsp-form-group">
	                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
	                        <?php echo esc_html__('Age:', 'wpdating') ?>
	                    </span> 
	                    <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-xs-form-group dsp-xs-form-group">
	                        <select name="age_from" class="dspdp-form-control dsp-form-control">
	                        	<option value=""><?php echo esc_html__('From','wpdating'); ?></option>
	                            <?php
								$min_selected = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';
	                            for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
	                                if ($fromyear == $min_selected) {
	                                    ?>
	                                    <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
	                                <?php } else { ?>
	                                    <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
	                                    <?php
	                                }
	                            }
	                            ?>
	                        </select>
	                    </span>
	                    <span class="dspdp-col-sm-3	dsp-sm-3">
	                        <select name="age_to" class="dspdp-form-control dsp-form-control">
	                        	<option value=""><?php echo esc_html__('To','wpdating'); ?></option>
	                            <?php
								$max_selected = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';
	                            for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
	                                if ($fromyear == $max_selected) {
	                                    ?>
	                                    <option value="<?php echo esc_attr($fromyear); ?>" selected="selected"><?php echo esc_html($fromyear); ?></option>
	                                <?php } else { ?>
	                                    <option value="<?php echo esc_attr($fromyear); ?>"><?php echo esc_html($fromyear); ?></option>
	                                    <?php
	                                }
	                            }
	                            ?>
	                        </select>
	                    </span>
	                </li>
	                <li class="dspdp-form-group dsp-form-group">
	                    <span class="dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
	                        <?php echo esc_html__('Country:', 'wpdating'); ?>
	                    </span>
	                    <span class="dspdp-col-sm-6 dsp-sm-6">
	                        <select name="cmbCountry" class="country_trigger dspdp-form-control dsp-form-control">
	                            <option value="0">
	                                <?php echo esc_html__('Select Country', 'wpdating'); ?>
	                            </option>
	                            <?php
	                                $strCountries = wpee_get_countries();
	                                foreach ($strCountries as $rdoCountries) {
	                                    $selected = ($rdoCountries->country_id == $selected_country_id) ? "selected = selected" : "";
	                                    echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
	                                }
	                            ?>
	                        </select>
	                    </span>
	                </li>	                          
	            </ul>
	        </div>
		    
		    <div class="login-form-trigger">
		    	<a href="#" class="link-cover"></a>
		    	<input type="submit" name="submit" class="dsp_submit_button dspdp-btn dsp-block dspdp-btn-default login-form-trigger" value="<?php echo esc_html__('Submit', 'wpdating'); ?>" onclick="//search_by_quick_widget();" style="display:none" />
		    </div>
		</form>
	</div>
	<?php if( isset( $advance_search_url) ) : ?>
	<div class="wpee-block-footer login-form-trigger">
		<a href="<?php echo esc_url($advance_search_url);?>" class="edit-profile-link"><?php esc_html_e('Advanced Search','wpdating');?></a>
	</div>
	<?php endif; ?>
</div>