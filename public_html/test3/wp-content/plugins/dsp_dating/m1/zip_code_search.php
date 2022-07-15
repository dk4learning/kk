<?php
//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;
?>
<div role="banner" class="ui-header ui-bar-a" data-role="header">
     <?php include_once("page_back.php");?>
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Search', 'wpdating'); ?></h1>
     <?php include_once("page_home.php");?>
</div>
<form id="frmsearch">

    <div class="ui-content" data-role="content">
        <div class="content-primary">	


            <input type="hidden" name="pagetitle" value="zipcode_search_result" />

            <input type="hidden" name="zipcode_search" value="zipcode_search" />

            <?php //---------------------------------START  GENERAL SEARCH--------------------------------------- ?>

            <label data-role="fieldcontain" class="select-group">  
                <div class="clearfix">                                    
                    <div class="mam_reg_lf select-label"><?php echo __('Gender:', 'wpdating') ?></div>

                    <select name="gender">
                        <?php echo get_gender_list($gender); ?>

                    </select>
                </div>
            </label>
            <div class="heading-text"><?php echo __('Age:', 'wpdating') ?></div> 
            <div class="col-cont clearfix">
                <div class="col-2">
                    <label class="select-group">
                        <select name="age_from">
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
                    </label>
                </div>
                <div class="col-2">
                    <label class="select-group">

                       <div  class="mam_reg_lf select-label"> 

                        <select  name="age_to">

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

                    </div>
                </label>
            </div>

        </div>

       <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"> <?php echo __('Search', 'wpdating') ." ".__('Miles', 'wpdating') . ' ' . __('from', 'wpdating') ?></div>
                    <input  type="text" name="miles"/>
                    </div>
                    </label>
                        <label data-role="fieldcontain" class="form-group">  
                        <div class="clearfix">                                    
                            <div class="mam_reg_lf form-label"><?php echo __('Zip Code', 'wpdating'); ?>
                    </div>

                    <input style="width:28.5%;" type="text" name="zip_code"/>
                    </div>
                    </label>
                    <input type="hidden" value="<?php echo $user_id ?>" name="user_id" />
                     <input type="hidden" name="search_type" value="zipcode_search"/>
                   <div class="btn-blue-wrap">
                    <input type="button" name="zip_submit" class="mam_btn btn-blue"  onclick="viewSearch(0, 'post')" value="<?php echo __('Submit', 'wpdating'); ?>" />
                </div>
           </div>
</div>
<?php include_once('dspNotificationPopup.php'); // for notification pop up   ?>
</div>



</form>



<?php
//-------------------------------------END ADDITIONAL OPTIONS SEARCH -------------------------------------// ?>