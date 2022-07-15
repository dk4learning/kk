<?php
//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>
<div class="box-border"> 
   <div class="box-pedding">

   <form name="frm_zip_code_search" method="GET" action="<?php echo $root_link . "search/search_result/search_type/distance_search/"; ?>"  class="dspdp-form-horizontal">
        <div class="heading-submenu">
            <strong>
                <?php echo __('Distance Search', 'wpdating'); ?>
            </strong>
        </div>
        <div class=" dsp-box-container dsp-space margin-btm-3 dsp-form-container">
            <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>

            <div class="box-pedding box-border ">
                    <!-- <form action="" method="post"  class="dspdp-form-horizontal "> -->
                <div class=" margin-btm-3">
                    <ul class="zip-search">
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                <?php echo __('Seeking a:', 'wpdating') ?>
                            </span>
                            <span class="dspdp-col-sm-5 dsp-sm-5">
                                <select name="seeking" class="dspdp-form-control dsp-form-control">
                                    <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                                    <?php   
                                            $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
                                            echo get_gender_list($gender); 
                                    ?>
                                </select>
                            </span>
                        </li>
                        <li class="dspdp-form-group dsp-form-group">
                            <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                <?php echo __('Age:', 'wpdating') ?>
                            </span> 
                            <span class="dspdp-col-sm-2 dsp-sm-2 dspdp-xs-form-group">
                                <select name="age_from" class="dspdp-form-control dsp-form-control">
                                    <!-- <?php for ($i = '18'; $i <= '90'; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?> -->
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
                            </span>
                            <span class="dspdp-col-sm-1 dsp-sm-1 dspdp-control-label dsp-control-label">
                                <?php echo __('to:', 'wpdating') ?>
                            </span> 			
                            <span  class="dspdp-col-sm-2 dsp-sm-2">
                                <select  name="age_to" class="dspdp-form-control dsp-form-control">
                                    <!-- <?php for ($j = '90'; $j >= '18'; $j--) { ?>
                                        <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                    <?php } ?> -->
                                    <?php
                                    for ($fromyear = $max_age_value; $fromyear >= $min_age_value; $fromyear--) {
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
                            </span>
                        </li>
                        <!-- <li class="dspdp-form-group dsp-form-group">
                         <span class="dspdp-control-label dspdp-col-sm-3"><?php echo __('Country:', 'wpdating'); ?></span> 
                         <span  class="dspdp-col-sm-5 dsp-sm-5"> 
                          <select name="cmbCountry" class="dspdp-form-control">
                                  <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                                <?php
                                $countries = $wpdb->get_results("SELECT * FROM $dsp_country_table Order by name");
                                foreach ($countries as $country) {
                                    ?>
                                   <option value="<?php echo $country->name; ?>" ><?php echo $country->name; ?></option>

                                <?php } ?>
                          </select>
                         </span> 
                        </li>       -->
                    </ul>
                </div>
                    <!-- </form> -->
                <div class="search-page-zip lm-dst-search">
                    <ul>
                        <li>
                            <span class="dspdp-form-group dsp-form-group clearfix dspdp-block">
                                <strong class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                    <?php echo __('Unit', 'wpdating') ?>
                                </strong>
                                <span  class="dspdp-col-sm-5 dsp-sm-5">
                                    <select name="unit" class="dspdp-form-control dsp-form-control">
	                                    <option value="0"><?php echo __('Select Unit', 'wpdating'); ?></option>
		                                <?php
		                                    $options = array(
		                                                    3959 => __('Miles', 'wpdating'),
		                                                    6371 => __('Km', 'wpdating')
		                                                    );
		                                    foreach ($options as $key=>$option) {
		                                ?>
		                                    <option value="<?php echo $key; ?>" ><?php echo $option; ?></option>
		                                <?php } ?>
                            		</select>
                                </span>
                            </span> 

                             <span class="dspdp-form-group dsp-form-group clearfix dspdp-block">
                                <strong class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                    <?php echo __('Distance', 'wpdating') ?>: 
                                </strong> 
                                <span class="dspdp-col-sm-5 dsp-sm-5 ">
                                     <input id="distance-search" name="distance" type="text" class="dspdp-form-control dsp-form-control" />
                                </span>
                            </span> 
                            
                             <span class="dspdp-form-group dsp-form-group clearfix dspdp-block">
                                <strong class="dspdp-col-sm-3 dsp-sm-3 dspdp-control-label dsp-control-label">
                                    <?php echo __('Place', 'wpdating') ?>: 
                                </strong> 
                                <span class="dspdp-col-sm-5 dsp-sm-5 ">
                                     <input id="autocomplete" name="zip_code" type="text" class="dspdp-form-control dsp-form-control"  placeholder="<?php echo __('PlaceipcodeCountry', 'wpdating'); ?>"/>
                                </span>
                            </span>
                             <span class="dspdp-form-group dsp-form-group clearfix dspdp-block">
                                <input  name="lat" id="lat"  type="hidden" value="" >
								<input  name="lng" id="lng"  type="hidden" value="" >
								<input  name="cmbCountry" id="country"  type="hidden" value="">
                            </span>
                            <span class="dspdp-form-group dsp-form-group  dspdp-block">
                                <span  class="dspdp-col-sm-6 dspdp-col-sm-offset-3">
                                    <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo __('Submit', 'wpdating'); ?>" />
                                </span>
                            </span>	
                        </li>
                    </ul>
                </div>        
            </div>
        </div>
    </form>
    </div>
    </div>



