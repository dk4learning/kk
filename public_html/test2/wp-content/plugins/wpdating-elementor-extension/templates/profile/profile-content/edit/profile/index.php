<div class="wpee-edit-profile-inner profile-section-content">
    <?php if ( isset( $success_messages ) && count( $success_messages ) > 0 ): ?>
        <div class="dsp-success-message">
            <?php foreach ( $success_messages as $success_message ) : ?>
                <p><?php echo $success_message; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>    
    <?php if ( isset( $info_messages ) && count( $info_messages ) > 0 ) : ?>
        <div class="dsp-info-message">
            <?php foreach ( $info_messages as $info_message ) : ?>
                <p><?php echo $info_message; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>    
    <form name="frm_u_profile" id="frm_u_profile" action="" method="post" enctype="multipart/form-data" class="dspdp-form-horizontal">
        <div class="wpee-edit-profile-form">
        <div class="heading-submenu">
            <?php echo __('Personal Information', 'wpdating'); ?>
        </div>
        <div class="edit-profile ">
            <div class="form-inline">
                <?php
                $gender      = ( isset( $_POST['gender'] ) && !empty( $_POST['gender'] ) ) ? $_POST['gender'] : ( ( isset( $user_profile->gender ) && !empty( $user_profile->gender ) ) ? $user_profile->gender : 'M' );
                $gender_list = wpee_get_gender_list($gender);
                if ( !empty( $gender_list ) ):
                    ?>
                    <div class="form-group">
                        <label for="gender"><?php echo __('I am', 'wpdating') ?>:</label>
                        <select class="form-control" id="gender" name="gender"
                            <?php echo ( isset( $user_profile->edited ) && $user_profile->edited == 'Y' ) ? "disabled='disabled'" : ''; ?> >
                            <?php
                            echo $gender_list;
                            ?>
                        </select>
                        <?php if ( isset( $form_validator['errors']['gender'] ) ): ?>
                            <span class="error"><?php echo $form_validator['errors']['gender']; ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php
                $seeking      = ( isset( $_POST['seeking'] ) && !empty( $_POST['seeking'] ) ) ? $_POST['seeking'] : ( ( isset( $user_profile->seeking ) && !empty( $user_profile->seeking ) ) ? $user_profile->seeking : 'F' );
                $gender_list  = wpee_get_gender_list($seeking);
                if ( !empty( $gender_list ) ):
                    ?>
                    <div class="form-group">
                        <label for="seeking">
                            <?php echo __('Seeking a:', 'wpdating'); ?></label>
                        <select id="seeking" name="seeking" class="form-control">
                            <?php
                            echo $gender_list;
                            ?>
                        </select>
                        <?php if ( isset( $form_validator['errors']['seeking'] ) ): ?>
                            <span class="error"><?php echo $form_validator['errors']['seeking']; ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            if ( isset( $user_profile ) && !empty( $user_profile->age ) ) {
                $split_age = explode("-", $user_profile->age);
                $year  = ( isset( $_POST['dsp_year'] ) && !empty( $_POST['dsp_year'] ) ) ? $_POST['dsp_year'] : $split_age[0];
                $month = ( isset( $_POST['dsp_mon'] ) && !empty( $_POST['dsp_mon'] ) ) ? $_POST['dsp_mon'] : $split_age[1];
                $day   = ( isset( $_POST['dsp_day'] ) && !empty( $_POST['dsp_day'] ) ) ? $_POST['dsp_day'] : $split_age[2];
            } else {
                $year  = ( isset( $_POST['dsp_year'] ) && !empty( $_POST['dsp_year'] ) ) ? $_POST['dsp_year'] : '';
                $month = ( isset( $_POST['dsp_mon'] ) && !empty( $_POST['dsp_mon'] ) ) ? $_POST['dsp_mon'] : '';
                $day   = ( isset( $_POST['dsp_day'] ) && !empty( $_POST['dsp_day'] ) ) ? $_POST['dsp_day'] : '';
            }
            ?>
            <div class="form-outer-group">
                <label><?php echo __('Age', 'wpdating') ?>:</label>
                <div class="form-inline age-group">
                    <?php
                    $mon = array(
                        1  => __('January', 'wpdating'),
                        2  => __('February', 'wpdating'),
                        3  => __('March', 'wpdating'),
                        4  => __('April', 'wpdating'),
                        5  => __('May', 'wpdating'),
                        6  => __('June', 'wpdating'),
                        7  => __('July', 'wpdating'),
                        8  => __('August', 'wpdating'),
                        9  => __('September', 'wpdating'),
                        10 => __('October', 'wpdating'),
                        11 => __('November', 'wpdating'),
                        12 => __('December', 'wpdating')
                    );
                    ?>
                    <div class="form-group">
                        <select name="dsp_mon" class="form-control ">
                            <?php foreach ($mon as $key => $value):?>
                                <option value="<?php echo $key ?>" <?php echo ($month == $key) ? 'selected' : '' ; ?> >
                                    <?php echo $value ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="dsp_day" class="form-control ">
                            <?php for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++): ?>
                                <option value="<?php echo $dsp_day ?>" <?php echo ($day == $dsp_day) ? 'selected' : '' ; ?> >
                                    <?php echo $dsp_day ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="dsp_year" class="dspdp-form-control dsp-form-control">
                            <?php
                            $check_start_year = wpee_get_setting('start_dsp_year');
                            $check_end_year   = wpee_get_setting('end_dsp_year');
                            $start_dsp_year   = $check_start_year->setting_value;
                            $end_dsp_year     = $check_end_year->setting_value;
                            for ($dsp_year = $start_dsp_year; $dsp_year <= $end_dsp_year; $dsp_year++): ?>
                                <option value="<?php echo $dsp_year ?>" <?php echo ($dsp_year == $year) ? 'selected' : '' ; ?> >
                                    <?php echo $dsp_year ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <label for="cmbCountry"><?php echo __('Country', 'wpdating') ?>:</label>
                    <select id="cmbCountry" name="cmbCountry" class="country_trigger form-control">
                        <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                        <?php
                        $dsp_country_table  = $wpdb->prefix . DSP_COUNTRY_TABLE;
                        $countries          = $wpdb->get_results("SELECT * FROM {$dsp_country_table} ORDER BY name");
                        $default_country    = wpee_get_setting('default_country');
                        $default_country_id = isset($default_country->setting_value) ? $default_country->setting_value : 0;
                        $user_country       = ( isset( $_POST['cmbCountry'] ) && !empty( $_POST['cmbCountry'] ) ) 
                                                ? $_POST['cmbCountry'] 
                                                : ( ( isset( $user_profile->country_id ) && $user_profile->country_id != 0 ) 
                                                    ? $user_profile->country_id 
                                                    : $default_country_id );
                        foreach ($countries as $country): ?>
                            <option value='<?php echo $country->country_id ?>' <?php echo ($user_country == $country->country_id) ? "selected='selected'" : '' ; ?> ><?php echo $country->name; ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                    <span class="input-error">
                    <?php if ( isset( $form_validator['errors']['cmbCountry'] ) ): ?>
                        <?php echo $form_validator['errors']['cmbCountry']; ?>
                    <?php endif; ?>
                    </span>
                </div>
                <div class="form-group">
                    <label for="cmbState"><?php echo __('State', 'wpdating') ?>:</label>
                    <div id="state_change" class="">
                        <select id="cmbState" name="cmbState" class="state_trigger form-control">
                            <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
                            <?php
                            $dsp_state_table  = $wpdb->prefix . DSP_STATE_TABLE;
                            $states           = $wpdb->get_results("SELECT * FROM {$dsp_state_table} where country_id='{$user_country}' ORDER BY name");
                            $user_state       = ( isset( $_POST['cmbState'] ) && !empty( $_POST['cmbState'] ) ) ? $_POST['cmbState'] : ( ( isset( $user_profile->state_id ) && !empty( $user_profile->state_id ) ) ? $user_profile->state_id : 0 );
                            foreach ($states as $state): ?>
                                <option value='<?php echo $state->state_id ?>' <?php echo ($user_state == $state->state_id) ? "selected='selected'" : '' ; ?> ><?php echo $state->name; ?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <label for="cmbCity"><?php echo __('City', 'wpdating') ?>:</label>
                    <div id="city_change" class="">
                        <select id="cmbCity" name="cmbCity" class=" city_trigger form-control dspdp-form-control">
                            <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                            <?php
                            $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
                            if ( $user_profile->state_id != 0 ) {
                                $cities = $wpdb->get_results("SELECT * FROM {$dsp_city_table} where country_id='{$user_country}' and state_id='{$user_state}' ORDER BY name");
                            } else {
                                $cities = $wpdb->get_results("SELECT * FROM {$dsp_city_table} WHERE country_id='{$user_country}' ORDER BY name");
                            }
                            $user_city = ( isset( $_POST['cmbCity'] ) && !empty( $_POST['cmbCity'] ) ) ? $_POST['cmbCity'] : ( ( isset( $user_profile->city_id ) && !empty( $user_profile->city_id ) ) ? $user_profile->city_id : 0 );

                            foreach ($cities as $city) : ?>
                                <option value='<?php echo $city->city_id; ?>' <?php echo ($user_city == $city->city_id) ? "selected='selected'" : '' ; ?> ><?php echo $city->name; ?></option>";
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <?php if ($check_zipcode_mode->setting_status == 'Y') {
                    $zipcode = ( isset( $_POST['zip'] ) && !empty( $_POST['zip'] ) ) ? $_POST['zip'] : ( ( isset( $user_profile->zipcode ) && !empty( $user_profile->zipcode ) ) ? $user_profile->zipcode : '' );
                    ?>
                    <div class="form-group">
                        <label for="zip"><?php echo __('Zip', 'wpdating'); ?>:</label>
                        <span class="dsp-sm-6 dspdp-col-sm-6">
                            <input type="text" id="zip" name="zip" class="form-control" value="<?php echo $zipcode; ?>"/>
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

        <?php require_once 'partials/profile-questions.php'; ?>

        <div class="wpee-edit-profile-form">
        <div class="image-edit-profile form-inline">
            <div class="form-group">
                <img src="<?php echo ( $profile_subtab == 'partner')
                    ? wpee_display_members_partner_photo($user_id, isset($user_profile->gender) ? $user_profile->gender : '', isset($user_profile->make_private) ? $user_profile->make_private : '', content_url('/'))
                    : wpee_display_members_photo($user_id,  content_url('/')); ?>" alt="" class="img dspdp-img-responsive"/>
            </div>
            <div class="form-group">
                <input type="file" name="photo_upload" class="dspdp-form-control dsp-form-control  dspdp-xs-form-group" value="">
                <?php if ( isset( $form_validator['errors']['photo_upload'] ) ): ?>
                    <span class="error"><?php echo $form_validator['errors']['photo_upload']; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group d-flex align-center">
            <?php if (apply_filters('dsp_is_make_private_on', '')) : ?>
                <input name="private" type="checkbox"
                       value="Y" <?php if (isset($user_profile) && $user_profile->make_private == 'Y') { ?> checked="checked"  <?php } ?>/>&nbsp;
                <span class="text"><?php echo __('Make your profile picture Private', 'wpdating'); ?>
                            </span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <input type="hidden" name="mode" value="update"/>
            <input type="submit" name="submit1" class="dsp_submit_button dspdp-btn dspdp-btn-default"
                   value="<?php esc_attr_e('Update', 'wpdating'); ?>"/>
        </div>
    </div>
    </form>
</div>
<?php
$check_force_photo_mode = wpee_get_setting('force_photo');

?>
<script type="text/javascript">

    jQuery(document).ready(function () {

        jQuery('#frm_u_profile').submit( ( event ) => {

            if (document.frm_u_profile.gender.value === '') {
                alert("<?php echo __('Please select gender values.', 'wpdating') ?>");
                document.frm_u_profile.gender.focus();
                return false;
            }

            if (document.frm_u_profile.seeking.value === '') {
                alert("<?php echo __('Please select seeking values.', 'wpdating') ?>");
                document.frm_u_profile.seeking.focus();
                return false;
            }

            if (document.frm_u_profile.cmbCountry.value === '0') {
                alert("<?php echo __('Please select country values.', 'wpdating') ?>");
                document.frm_u_profile.cmbCountry.focus();
                return false;
            }

            for (let i = 0; i < document.frm_u_profile.hidprofileqquesid.length; i++) {
                let q_name = document.frm_u_profile.hidprofileqques[i].value;
                let q_id1 = document.frm_u_profile.hidprofileqquesid[i].value;
                let sel_option_id = document.getElementById("q_opt_ids" + q_id1).value;
                if (sel_option_id === '0') {
                    alert(' <?php echo __("Please select", "wpdating"); ?> ' + q_name + '<?php echo __(" values", "wpdating"); ?>');
                    return false;
                }
            }

            if (document.frm_u_profile.about_me.value === "") {
                alert("<?php echo __('Please enter about me values.', 'wpdating') ?>");
                document.frm_u_profile.about_me.focus();
                return false;
            }

            for (let i = 0; i < document.frm_u_profile.hidtextprofileqquesid.length; i++) {
                let q_name2 = document.frm_u_profile.hidetextqu_name[i].value;
                let q_id2 = document.frm_u_profile.hidtextprofileqquesid[i].value;
                let text_option_id = document.getElementById("text_option_id" + q_id2).value;
                if (text_option_id === "") {
                    alert('<?php echo __("Please enter ", "wpdating"); ?>' + q_name2 + '<?php echo __(" values", "wpdating"); ?>');
                    return false;
                }
            }

            <?php
            if ($profile_subtab != 'partner' && $check_force_photo_mode->setting_status == 'Y') {
                $profile_image_exists          = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_members_photos_table} WHERE user_id='{$user_id}'");
                $user_tmp_profile_image_exists = $wpdb->get_var("SELECT COUNT(*) FROM {$dsp_tmp_members_photos_table} WHERE t_user_id='{$user_id}' AND t_status_id=0");
                if ($profile_image_exists == 0 && $user_tmp_profile_image_exists == 0) {
            ?>
                    let file = jQuery("input[name='photo_upload']").val();
                    if (jQuery.trim(file) === "") {
                        alert("<?php echo __('Please choose profile photo while updating profile.', 'wpdating') ?>");
                        return false;
                    }
            <?php
                }
            }
            ?>
        });
    });
</script>