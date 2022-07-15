<script>
    jQuery(document).ready(function(e) {
        jQuery(".tabLink").click(function() {
            if (!jQuery(this).hasClass("activeLink")) {
                jQuery(".tabLink").removeClass("activeLink");
                jQuery(this).addClass("activeLink");
                var id = jQuery(this).attr("id");
                jQuery(".tabcontent").each(function() {
                    if (!jQuery(this).hasClass("hide"))
                        jQuery(this).addClass("hide");
                });
                jQuery("#tab-" + id).removeClass("hide");
            }
            return false;
        });
    });

</script>
<div class="box-border magn-top-25 home-gest-page">
    <div class="box-pedding">
        <div class="tab-box clearfix"> 
            <a href="#" class="tabLink <?php
            if ($pgurl == "" || $pgurl == "ALL")
                echo "activeLink";
            else if ($pgurl == "register")
                echo "activeLink";
            ?>" id="register"><?php echo __('Register', 'wpdating');?></a> 
            <a href="#" class="tabLink <?php if ($pgurl == "stories") echo "activeLink"; ?>" id="stories"><?php echo __('Stories', 'wpdating');?></a> 
            <a href="#" class="tabLink <?php if ($pgurl == "online_members") echo "activeLink"; ?>" id="online"><?php echo __('Online', 'wpdating');?></a> 
            <a href="#" class="tabLink <?php if (in_array($pgurl,array('search','guest_search'))) echo 'activeLink'; ?>" id="search"><?php echo __('Search', 'wpdating');?></a> 
        </div>
        <div class="clearfix"></div>
        <div class="tabcontent <?php
        if ($pgurl != "") {
            if ($pgurl != "register" && $pgurl != "ALL")
                echo "hide";
        }
        ?>" id="tab-register"> 
        <?php
        do_action('phone_modal');
        $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $siteKey = isset($check_google_app_id) ? $check_google_app_id->setting_value : '';//"6LeaFf8SAAAAAOvDpgAV1P5Wo0tEc2gfi53B0Sl-";
        $secret = isset($check_google_secret_key) ? $check_google_secret_key->setting_value : '';
        $isGoogleApiKeySet = (!empty($siteKey) && !empty($secret)) ? true : false;
        $isPaswordOptionEnabled =  ($check_password_option->setting_status == 'Y') ? true : false;
        $isFirstNLastNameEnabled = ($register_form_setting->setting_status == 'Y') ? true : false;
        $sendEmailVerification = ($after_user_register->setting_value == 'verify_email') ? true : false;
        $resp = null;
        if ($check_recaptcha_mode->setting_status == 'Y' &&  $isGoogleApiKeySet ){
            $path = WP_DSP_ABSPATH . 'recaptchalib.php';
            require_once($path);

            // google api setting
            
            # the response from reCAPTCHA
            // reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
            $lang = "en";
            
            # the error code from reCAPTCHA, if any
            
            $sent = false;
            $reCaptcha = new ReCaptcha($secret);
        }

        global $wpdb;
        $current_user = wp_get_current_user();
        $user_ID = $current_user->ID;
        $errors = array();  
        //Check whether the user is already logged in
        if (!$user_ID) {
        global $msg;
        $msg = '';
        if ($_POST){
                
             //We shall SQL escape all inputs
                $username = esc_sql(sanitizeData(trim($_REQUEST['username']), 'xss_clean'));
                $firstname = (isset($_REQUEST['firstname']) && $isFirstNLastNameEnabled) ? esc_sql(sanitizeData(trim($_REQUEST['firstname']), 'xss_clean')): '';
                $lastname = (isset($_REQUEST['lastname']) && $isFirstNLastNameEnabled) ? esc_sql(sanitizeData(trim($_REQUEST['lastname']), 'xss_clean')) : '';
                $usernameExist = username_exists($username);
                if (strpos($username, " ") !== false) {
                $errors[]  = __('Username can not have spaces.', 'wpdating');
                } elseif (empty($username)) {
                $errors[] = __('Fields should not be empty.', 'wpdating');
                } else {
                if ($check_recaptcha_mode->setting_status == 'Y' && $isGoogleApiKeySet){
                //recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
                    if ($_POST["g-recaptcha-response"]) {
                        $resp = $reCaptcha->verifyResponse(
                        $_SERVER["REMOTE_ADDR"],
                        $_POST["g-recaptcha-response"]
                        );

                    }
                }
                $email = esc_sql(sanitizeData(trim($_REQUEST['email']), 'xss_clean'));
                $emailExist =  email_exists($email);
                $confirm_email = esc_sql(sanitizeData(trim($_REQUEST['confirm_email']), 'xss_clean'));
                if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $email)) {
                $errors[] = __('Please enter a valid email address.', 'wpdating');
                } else if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $confirm_email)) {
                $errors[] = __('Please enter a valid email address.', 'wpdating');
                } else if ($email != $confirm_email) {
                $errors[] = __('Provided emails are not same. Please fill again.', 'wpdating');
                } else if ($check_terms_page_mode->setting_status == 'Y' && $_REQUEST['terms'] == '') {
                $errors[] = __('Please accept Terms and Conditions.', 'wpdating');
                } else if (($resp == null || !$resp->success ) && 
                            $check_recaptcha_mode->setting_status == 'Y'  && 
                            $isGoogleApiKeySet
                        )
                {
                    $errors[] = __('Captcha did not match', 'wpdating');
                }else if(empty($firstname) && $isFirstNLastNameEnabled){
                    $errors[] = __('First name field should not be empty.', 'wpdating');
                }else if(empty($lastname) && $isFirstNLastNameEnabled){
                    $errors[] = __('Last name field should not be empty.', 'wpdating');
                }else{
                    $random_password = '';
                    $sent = true;
                    $results = apply_filters('dsp_validate_password',$_REQUEST);
                    if($isPaswordOptionEnabled && array_key_exists('password',$_REQUEST)){
                        if(is_array($results)){
                            foreach ($results as $key => $value) {
                               $errors[] = $value;
                            }
                        }else{
                          $random_password = $results; 
                        }
                        
                    }else{
                        $random_password = wp_generate_password(12, false);
                    }
                    $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
                    $dsp_blacklist_members = $wpdb->prefix . DSP_BLACKLIST_MEMBER_TABLE;
                    //Get the IP of the person registering
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $check_blacklist_ipaddress_table = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_blacklist_members where ip_address = '$ip' AND  ip_status=1 ");
                    if ($check_blacklist_ipaddress_table <= 0) {
                        $status = wp_create_user($username, $random_password, $email);
                        $user_data = $wpdb->get_row("SELECT * FROM $dsp_users_table where user_login='$username' ");
                        $user_id = isset($user_data) ? $user_data->ID : 0; 
                        $ip_address_status = 0;
                        //Add user metadata to the usermeta table
                        //Added by Nikesh
                     if (empty($usernameExist)) {
                        $userDetails = array(
                                        'signup_ip' => $ip,
                                        'ip_address_status' => $ip_address_status,
                                        'first_name' => $firstname,
                                        'last_name' => $lastname,
                                    );
                        /*update_user_meta($user_id, 'signup_ip', $ip);
                        update_user_meta($user_id, 'ip_address_status', $ip_address_status);*/
                        dsp_add_user_details_in_meta_table($userDetails,$user_id);
                    }
                        # commented in version 4.8.3.1 as it doesn't make sense
                        //$wpdb->query("INSERT INTO $dsp_blacklist_members SET user_name = '$username', ip_address ='$ip' ,ip_status=0 ");
                        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
                        $gender = $_POST['gender'];
                        $month = $_POST['dsp_mon'];
                        $day = $_POST['dsp_day'];
                        $year = $_POST['dsp_year'];
                        $age = $year . "-" . $month . "-" . $day;
                        $last_update_date = date("Y-m-d H:m:s");
                        if(!dsp_is_user_exist($user_id) && $user_id > 0)
                        {                  
                            if ($check_approve_profile_status->setting_status == 'Y')   // if Profile approve status is Y then Profile Automatically Approved
                            {
                                $new_user_status = 1;
                            } 
                            else 
                            {
                                $new_user_status = 0;
                            }
                            if (is_plugin_active('twilio_registration/twilio_registration.php')) {
                                $phone = $_POST['ccode'] . $_POST['phone_number'];
                                $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = '$user_id', gender ='$gender' ,age='$age',status_id=$new_user_status, edited='Y', last_update_date='$last_update_date', phone_number='$phone' ");
                            }else{
                                $wpdb->query("INSERT INTO $dsp_user_profiles SET user_id = '$user_id', gender ='$gender' ,age='$age',status_id=$new_user_status, edited='Y', last_update_date='$last_update_date' ");
                            }
                        }
                        if (is_wp_error($status)) {
                            if (!empty($usernameExist)) {
                                $errors[] = $usernameExist ? __('Username already exists', 'wpdating') :'';
                            }
                            if (!empty($emailExist)) {
                                $errors[] = $emailExist ? __('Email already exists', 'wpdating') : '';
                            }
                        } else {
                                $from = get_option('admin_email');
                                $headers = language_code('DSP_FROM') . $from . "\r\n";
                                $subject = __('Registration Successful', 'wpdating');
                                $message = __('Your Login Details', 'wpdating') . "\n" . __('Username: ', 'wpdating') . $username . "\n" . __('Password: ', 'wpdating') . $random_password;
                                $details =  array(
                                                    'email' => $email,
                                                    'message'=>$message,
                                                    'userId' => $user_id,
                                                    'password' => $random_password

                                                );

                                $message = ($sendEmailVerification && $isPaswordOptionEnabled && array_key_exists('password',$_REQUEST)) ? apply_filters('dsp_send_activation_link',$details) : $message;
//                                wp_mail($email, $subject, $message, $headers);
		                        $wpdating_email  = Wpdating_email_template::get_instance();
		                        $result = $wpdating_email->send_mail( $email, $subject, $message );
                                $msg = __('Alright! Your username and password has been emailed to you. Please check your email.', 'wpdating');
                                if(!$sendEmailVerification){
                                    do_action('dsp_auto_logged_in',$user_id);
                                }
                            
                            }
                    } else {

                        $errors[] = __('Your IP is blacklisted by admin', 'wpdating');
                }
            }

         }
        }
        
        ?>
                <div class="box-page">
                    <div style="font-weight:bold;color:<?php echo '#525252'; ?>"><?php //Check whether user registration is enabled by the administrator      ?>
                        <?php
                       //echo $msg;die('h');
                        if($check_register_page_redirect_mode->setting_status == 'Y'){
                            echo '<script> setTimeout("window.location.href=\''. $check_register_page_redirect_mode->setting_value .'\' ",1500);</script>';
                            die;
                        }
                        if (empty($errors) && !empty($msg)){
                            ?> <div class="registration-msg" ><?php
                            echo $msg;
                            ?></div>
                            <?php
                            //echo $check_register_page_redirect_mode->setting_value;
                            if($check_register_after_redirect_url->setting_status == 'Y'){ 
                               echo '<script> setTimeout("window.location.href=\''. $check_register_after_redirect_url->setting_value .'\' ",1500);</script>';
                               die;
                            }

                        }else{
                            ?>
                           <?php  if (!empty($errors)){?>
                          <div class="result error">
                               <?php foreach($errors as $error){?>
                                   <span style="color:white;"><?php echo $error; ?></span>
                               <?php } ?>
                           </div>
                       <?php
                       } }
                       ?></div>
               </div>
                <?php if ($msg != __('Alright! Your username and password has been emailed to you. Please check your email.', 'wpdating')) { ?>
                    <script>
                        jQuery(document).ready(function(e) {
                            jQuery("#recaptcha_area").each(function() {
                                jQuery(this).css({'width':'auto !important'});
                            });
                            jQuery("#recaptcha_area span").each(function() {
                                jQuery(this).css({'line-height': 'inherit !important'});
                            });
                        });
                    </script>
                    <div class="box-page">
                        <?php if(get_option('users_can_register')) { ?>
                            <div id="result"></div> 
                           <form action="<?php echo $root_link . "register" ?>" method="post" class="dspdp-form-horizontal" data-parsley-validate >

                                <div class="dsp_reg_main dsp-form-group">
                                    <ul>
                                        <?php if (is_plugin_active('twilio_registration/twilio_registration.php')) {
                                            do_action('add_phone_number');
                                        }?>
                                        <li class="dspdp-form-group dsp-form-group">
                                            <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('Username: ', 'wpdating'); ?></span>
                                            <span class="dsp-md-6  dspdp-col-sm-6"><input type="text"id="username" name="username" class="text dsp-form-control dspdp-form-control validate-empty " value="<?php echo isset($username)?$username:'';?>" required /></span>
                                        </li>
                                        <?php if($isFirstNLastNameEnabled): ?>
                                            <li class="dspdp-form-group dsp-form-group">
                                                <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('First Name', 'wpdating'); ?></span>
                                                <span class="dsp-md-6  dspdp-col-sm-6"><input type="text" id="firstname" name="firstname" class="text dsp-form-control dspdp-form-control validate-empty " value="<?php echo isset($firstname)?$firstname:'';?>" required /></span>
                                            </li>
                                            <li class="dspdp-form-group dsp-form-group">
                                                <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('Last Name', 'wpdating'); ?></span>
                                                <span class="dsp-md-6  dspdp-col-sm-6"><input type="text" id="lastname" name="lastname" class="text dsp-form-control dspdp-form-control validate-empty " value="<?php echo isset($lastname)?$lastname:'';?>" required /></span>
                                            </li>
                                        <?php endif; ?>
                                        <?php
                                        if($isPaswordOptionEnabled){
                                         $values['password'] = isset($password) ? $password : '';
                                         $values['rePassword'] = isset($rePassword) ? $rePassword : '';
                                         $content = '';
                                         $content = apply_filters('dsp_register_form_filter',$content,$values);
                                         echo $content;

                                        }
                                        ?>
                                        <li class="dspdp-form-group dsp-form-group">
                                            <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('Email:', 'wpdating'); ?></span>
                                            <span class="dsp-md-6  dspdp-col-sm-6"><input type="text" id="dsp-email" data-parsley-type="email" name="email" class="text dsp-form-control dspdp-form-control validate-empty" value="<?php echo isset($email)?$email:'';?>"  data-parsley-trigger="change" required /></span>
                                        </li>
                                        <li class="dspdp-form-group dsp-form-group">
                                            <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('Confirm Email', 'wpdating'); ?></span>
                                            <span class="dsp-md-6  dspdp-col-sm-6"><input type="text" id="confirm_email" name="confirm_email" data-parsley-type="email" class="text dsp-form-control dspdp-form-control validate-empty" value="<?php echo isset($confirm_email)?$confirm_email:'';?>"  data-parsley-equalto="#dsp-email" data-parsley-trigger="change" required /></span>
                                        </li>
                                        <li class="dspdp-form-group dsp-form-group">
                                           <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label"><?php echo __('Gender', 'wpdating'); ?></span>
                                            <span class="dsp-md-6  dspdp-col-sm-6">
                                                <select name="gender" class="dsp-form-control dspdp-form-control">
                                                    <?php
                                                        $g = isset($_REQUEST['gender'])?$_REQUEST['gender']:'';
                                                        global $wpdb;
                                                        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
                                                        $dsp_gender_list            = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
                                                        $check_couples_mode         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
                                                        // check male module must be premium member Mode is Activated or not.
                                                        $check_male_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'male'" );
                                                        // check female module must be premium member Mode is Activated or not.
                                                        $check_female_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'female'" );
                                                        $query             = "SELECT * FROM $dsp_gender_list ";
                                                        $conditions        = array();
                                                        $conditions[]      = ( $check_couples_mode->setting_status == 'N' ) ? "enum != 'C'" : '';
                                                        $conditions[]      = ( $check_male_mode->setting_status == 'N' ) ? "enum != 'M'" : '';
                                                        $conditions[]      = ( $check_female_mode->setting_status == 'N' ) ? "enum != 'F'" : '';
                                                        $conditions        = array_filter( $conditions );
                                                        if ( ! empty( $conditions ) ) {
                                                            $query .= " WHERE " . implode( ' AND ', $conditions );
                                                        }
                                                        $gender_list   = $wpdb->get_results( $query );
                                                        $gender_option = "";
                                                        foreach ( $gender_list as $gender_row ) {

                                                            if ( $gender_row->editable == 'N' ) {
                                                                $gender_string = addslashes($gender_row->gender);
                                                                if ( $gender_row->enum == $g ) {
                                                                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . __( $gender_string, 'wpdating' ) . '</option>';
                                                                } else {
                                                                    $gender_option .= '<option value="' . $gender_row->enum . '">' . __( $gender_string, 'wpdating' ) . '</option>';
                                                                }
                                                            } else {

                                                                if ( $gender_row->enum == $g ) {
                                                                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . $gender_row->gender . '</option>';
                                                                } else {
                                                                    $gender_option .= '<option value="' . $gender_row->enum . '">' . $gender_row->gender . '</option>';
                                                                }
                                                            }
                                                        }
                                                        echo $gender_option;
                                                    ?>
                                                </select>
                                            </span>
                                        </li>
                                        <li class="dspdp-form-group dsp-form-group">
                                            <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label">
                                                <?php echo __('Birth Date', 'wpdating'); ?>
                                            </span>
                                            <?php
                                            //array to store the months
                                            $mon = array(1 => __('January', 'wpdating'),
                                                __('February', 'wpdating'),
                                                __('March', 'wpdating'),
                                                __('April', 'wpdating'),
                                                __('May', 'wpdating'),
                                                __('June', 'wpdating'),
                                                __('July', 'wpdating'),
                                                __('August', 'wpdating'),
                                                __('September', 'wpdating'),
                                                __('October', 'wpdating'),
                                                __('November', 'wpdating'),
                                                __('December', 'wpdating'));

                                            ?>
                                            <span class="dsp-md-3 dspdp-col-sm-3">
                                                <select name="dsp_mon" class="dsp-form-control dspdp-form-control  dspdp-xs-form-group" >
                                                    <?php
                                                    foreach ($mon as $key => $value) {
                                                        $selectedMonth = isset($_REQUEST['dsp_mon']) && $_REQUEST['dsp_mon'] == $key  ? "selected":'';

                                                        if (isset($split_age[1]) &&  $split_age[1] == $key) {
                                                            ?>
                                                            <option value="<?php echo $key ?>" <?php echo $selectedMonth;?>><?php echo $value ?></option>
                                                        <?php } else { ?>
                                                            <option value="<?php echo $key ?>" <?php echo $selectedMonth;?> ><?php echo $value ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </span>
                                            <?php //make the day pull-down menu     ?>
                                            <span class="dsp-md-2 dspdp-col-sm-2">
                                            <select name="dsp_day" class="dsp-form-control dspdp-form-control dspdp-xs-form-group" >
                                                <?php
                                                for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
                                                    $selectedDay = isset($_REQUEST['dsp_day']) && $_REQUEST['dsp_day'] == $dsp_day  ? "selected":'';
                                                    if (isset($split_age[2]) && $split_age[2]  == $dsp_day) {
                                                        ?>
                                                        <option value="<?php echo $dsp_day ?>" <?php echo $selectedDay; ?>><?php echo $dsp_day ?></option>
                                                    <?php } else { ?>
                                                        <option value="<?php echo $dsp_day ?>" <?php echo $selectedDay; ?>><?php echo $dsp_day ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                            </span>
                                            <?php //make the year pull-down menu ?>
                                            <span class="dsp-md-2  dspdp-col-sm-2">
                                            <select name="dsp_year" class="dsp-form-control  dspdp-form-control dspdp-xs-form-group">
                                               <?php
                                                    $start_dsp_year = $check_start_year->setting_value;
                                                    $end_dsp_year = $check_end_year->setting_value;
                                                    $year = isset($_POST['dsp_year']) ? $_POST['dsp_year'] : $start_dsp_year;
                                                    echo dsp_get_year($start_dsp_year,$end_dsp_year,$year);
                                                ?>
                                            </select>
                                            </span>
                                        </li>
                                        <?php if ($check_terms_page_mode->setting_status == 'Y') { ?>
                                            <li class="dspdp-form-group dsp-form-group">
                                                <span class="dsp-md-3  dspdp-col-sm-3 dspdp-control-label">&nbsp;</span>
                                                <span class="dspdp-col-sm-6"><input class="check" name="terms" type="checkbox" value="1"/><?php echo str_replace('[L]', $check_terms_page_mode->setting_value, __('I agree to the <a href=[L] target=_blank>Terms and Conditions</a>.', 'wpdating')); ?></span>
                                            </li>
                                        <?php }
                                        if ($check_recaptcha_mode->setting_status == 'Y' &&  $isGoogleApiKeySet ):
                                        ?>
                                            <li class="dspdp-form-group dsp-form-group"><span class="dsp-md-3  dspdp-col-sm-3 dspdp-control-label"><?php echo __('Captcha:', 'wpdating'); ?></span>
                                                <span class="dsp-md-6  dspdp-col-sm-6 g-recaptcha"  data-sitekey="<?php echo $siteKey; ?>"><?php //echo recaptcha_get_html($publickey, $error); ?></span>

                                            </li>
                                        <?php endif; ?>
                                        <li class="dspdp-form-group dsp-form-group">
                                            <span class="dsp-md-3  dspdp-col-sm-3 dspdp-control-label">&nbsp;</span>
                                            <span class="dsp-md-9  dspdp-col-sm-9">
                                            <?php if(is_plugin_active( 'twilio_registration/twilio_registration.php' ) ){?>
                                                <input type="button" class="dspdp-btn dspdp-btn-default" id="submitbtn" name="submit" value="<?php echo __('Register', 'wpdating'); ?>" />
                                                <input type="submit" name="formsubmit" id="formsubmit" style="display: none;">
                                            <?php } else{ ?>
                                                <input type="submit" class="dspdp-btn dspdp-btn-default" id="submitbtn" name="submit" value="<?php echo __('Register', 'wpdating'); ?>" />
                                             <?php   }    
                                            ?>
                                                <?php if(!$isPaswordOptionEnabled){?>
                                                <span class="note-res" style=" float:none; margin-left:10px;"><?php echo __('Note: A password will be emailed to you.', 'wpdating'); ?></span>
                                                <?php } ?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </form>
                            <?php if ($check_recaptcha_mode->setting_status == 'Y'): ?>
                                <script language="javascript" type="text/javascript">
                                    /* this is just a simple reload; you can safely remove it; remember to remove it from the image too */
                                    function reloadCaptcha()
                                    {
                                        document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + new Date();
                                    }
                                </script>
                            <?php endif; ?>
                        <?php } else {
                            ?>
                            <span style="float:left; width:100%; text-align:center; color:#ff0000;">
                                <?php echo __('Registration is currently disabled.', 'wpdating'); ?>
                            </span>
                            <?php
                        }
                        ?>
                    </div>

                    <?php
                }
            } else {
                wp_redirect(home_url());
                exit;
            }
            ?>
        </div>

        <div class="tabcontent <?php
        if (!isset($pgurl))
            echo "hide";
        else if ($pgurl != "stories")
            echo "hide";
        ?>" id="tab-stories"> 
             <?php
             global $wpdb;
             $dsp_stories = $wpdb->prefix . DSP_STORIES_TABLE;
             $story_result = $wpdb->get_results("select * from $dsp_stories order by date_added desc");
             if (count($story_result) > 0) {
                 ?>
                 <div class="dsp-row">
                    <ul class="story-list">
                        <?php foreach ($story_result as $story_row) {
                            ?>
                            <li class="dsp-sm-4">
                                <div  class="dspdp-bordered-item ">
                                    <div class="guest-story-heading dspdp-h5 age-text dsp-none"><?php echo stripslashes( $story_row->story_title); ?></div>
                                    <div class="guest-story-box dspdp-row">
                                        <div class="guest-story-image dspdp-col-sm-2 dspdp-col-xs-4">
                                            <div class="guest-story-image-container">
                                                <img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/dsp_media/story_images/thumb_' . $story_row->story_image; ?>" width="100" height="100" class="dspdp-img-responsive" alt="<?php echo  $story_row->story_image; ?>"/>
                                            </div>
                                        </div>
                                        <div class="guest-story-heading dsp-block" style="display:none"><?php echo stripslashes( $story_row->story_title); ?></div>    
                                        <div class="guest-story-content dspdp-col-sm-9  dspdp-col-xs-8"><?php echo stripslashes( $story_row->story_content); ?></div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        
        </div>

        <div class="tabcontent <?php
        if (!isset($pgurl))
            echo "hide";
        else if ($pgurl != "online_members")
            echo "hide";
        ?>" id="tab-online">
    <?php
            /*
            Copyright (C) www.wpdating.com - All Rights Reserved!
            Author - www.wpdating.com
            WordPress Dating Plugin
            contact@wpdating.com
            */
// ----------------------------------------------- Start Paging code------------------------------------------------------ //  
            global $wp_query;
            $page_id = $wp_query->post->ID; //fetch post query string id
            $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
            $tbl_name = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
            $posts_table = $wpdb->prefix . POSTS;
            $insertMemberPageId = "UPDATE $dsp_general_settings SET setting_value = '$page_id' WHERE setting_name ='member_page_id'";
            $wpdb->query($insertMemberPageId);
            $posts_table = $wpdb->prefix . POSTS;
            $post_page_title_ID = $wpdb->get_row("SELECT * FROM $posts_table WHERE ID='$page_id'");
            // ROOT PATH
            $pieces = explode('/', $_SERVER['REQUEST_URI']);
            $page_index = array_search('page', $pieces);

            //if (get('page')) $page = get('page'); else $page = 1;
            $page = isset($_REQUEST['page']) ? esc_sql($_REQUEST['page']) : $pieces[$page_index + 1];
            if($page_index == false)
                $page=0;

            // How many adjacent pages should be shown on each side?
            $adjacents = 2;
            $limit = (isset($check_search_result->setting_value) && !empty($check_search_result->setting_value) && is_numeric($check_search_result->setting_value)) ?
                        $check_search_result->setting_value :
                        8;
            if ($page > 1)
                $start = ($page - 1) * $limit;    //first item to display on this page
            else
                $start = 0;
            //var_dump($start);
            // ------------------------------------------------End Paging code------------------------------------------------------ // 
           
            $gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : get('gender');
            $age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : get('age_from');
            $age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : get('age_to');
           
            $page_name = $root_link . "online_members/";
            $page_name .= !empty($gender) ?   'gender/' . $gender . "/": '';
            $page_name .= !empty($age_from) ? 'age_from/' . $age_from . "/"  : '';
            $page_name .= !empty($age_to) ?   'age_to/' . $age_to . "/" : '';

            //For min and max age
            $check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
            $check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
            $min_age_value = $check_min_age->setting_value;
            $max_age_value = $check_max_age->setting_value;
    ?>

            <div class="clearfix" style="margin-bottom: 10px; ">
                <div class="content-search">
                    <form action="<?php echo $root_link . "online_members" ?>" method="post" class="dspdp-form-inline dsp-form-inline dspdp-text-center">
                        <div align="center dsp-form-container">
                            <span class="dsp-control-label dsp-sm-2">
                                <?php echo __('Gender:', 'wpdating') ?>
                            </span>

                            <span class="dsp-sm-2">                                
                                <select name="gender" class="dspdp-form-control dsp-form-control">
                                    <option value="all" <?php if ($gender == 'all' || isset($_REQUEST['show'])) { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> ><?php echo __('All', 'wpdating');?></option>
                                    <?php echo get_gender_list($gender); ?>
                                </select>
                            </span>
                                                        
                            <span class="dsp-control-label dsp-sm-1">
                                <?php echo __('Age:', 'wpdating') ?>
                            </span>

                            <span class="dsp-sm-2">
                                <select name="age_from" class="dspdp-form-control dsp-form-control">
                                    <?php for ($i = $min_age_value; $i <= $max_age_value; $i++) { ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>                                
                            </span>

                            <span class="dsp-control-label dsp-sm-1">
                                <?php echo __('to:', 'wpdating') ?>
                            </span>

                            <span class="dsp-sm-2">
                                <select  name="age_to" class="dspdp-form-control dsp-form-control dspdp-xs-form-group">
                                    <?php for ($j = $max_age_value; $j >= $min_age_value; $j--) { ?>
                                        <option value="<?php echo $j ?>"><?php echo $j ?></option>
                                    <?php } ?>
                                </select>
                            </span>

                            <span class="dsp-sm-2">
                                <input name="submit" class="dspdp-btn dspdp-btn-default" type="submit" value="<?php echo __('Filter', 'wpdating') ?>" />
                            </span>
                    </form>
                </div>
                <div class="dspdp-seprator"></div>
            </div>
        </div>
        

        <?php
        $dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $random_online_status = $check_online_member_mode->setting_status;
        $pagination1 = '';
        $online_member = array();
        $filters = array();
        if($random_online_status == 'Y'){
            $filters = array(
                    'age_from' => $age_from,
                    'age_to' => $age_to,
                    'gender' => $gender,
                    'start' => $start,
                    'last' => $limit,
                );
            $random_online_number = $check_online_member_mode->setting_value;
            $online_member = dsp_randomOnlineMembers($random_online_number,$filters);
        }else
        {   
           $online_member = dsp_getOnlineMembers($filters);
        }
        $user_count = dsp_getTotalOnlineUsers(false,$random_online_status);
        $total_results1 = $user_count;
        if($total_results1 > $limit){
            ###### Pagination sections ###### 
            $pagination1 =  dsp_pagination($total_results1,$limit, $page, $adjacents,$page_name); 
            ###  End Paging code  ##########
        }
        if ($total_results1 > 0) {
        ?>

        <div id="search_width" class="dsp-member-container dspdp-row dsp-row">
            <?php
            foreach ($online_member as $member1) {
                if ($check_couples_mode->setting_status == 'Y') {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$member1->user_id'");
                } else {
                    $member = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE gender!='C' AND user_id = '$member1->user_id'");
                }
                $s_user_id = $member->user_id;
                $s_country_id = $member->country_id;
                $s_gender = $member->gender;
                $s_seeking = $member->seeking;
                $s_state_id = $member->state_id;
                $s_city_id = $member->city_id;
                $s_age = GetAge($member->age);
                $s_make_private = $member->make_private;
                $stealth_mode = isset($member->stealth_mode) ? $member->stealth_mode : '';
//$s_user_pic = $member->user_pic;
                $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");
                $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");
                $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");
                $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");
                $favt_mem = array();
                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");
                foreach ($private_mem as $private) {
                    $favt_mem[] = $private->favourite_user_id;
                }

                ?>
                <div class="dspdp-col-sm-4 dsp-sm-3">
                    <div class="box-search-result image-container">
                    <div class="img-box dspdp-spacer circle-image">
                        <span class="online dspdp-online-status">
                               <?php
                               $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_online_user_table WHERE user_id=$s_user_id");
                               $check_online_user = ($stealth_mode == "Y") ? '0' : $check_online_user;
                               ?>
                            <?php
                            //echo $fav_icon_image_path;
                            if ($check_online_user > 0)
                                echo '<span class="dspdp-status-on" '.__('Online', 'wpdating').'></span>';
                            else
                                echo '<span class="dspdp-status-off" '.__('Offline', 'wpdating').'></span>';
                            ?>
                            
                            </span>
                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private Photo" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" /></a>                
                                            <?php
                                        }
                                    } else {
                                        ?>
                                         <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:135px; height:135px;" border="3" class="img" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                        </a>
                                    <?php }
                                    }
                                 ?>
                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                    </a>
                                <?php } ?>

                            <?php } else { ?>

                                <?php if ($s_make_private == 'Y') { ?>

                                    <?php if ($current_user->ID != $s_user_id) { ?>

                                        <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                                <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private Photo"/>
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                                <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>"/></a>                
                                            <?php
                                        }
                                    } else { ?>
                                         <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:135px; height:135px;" border="3" class="img" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                        </a>
                                    <?php } 
                                        }
                                    ?>
                                <?php } else { ?>

                                    <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                    </a>
                                <?php } ?>
                                <?php
                            }
                        } else {
                            ?> 

                            <?php if ($s_make_private == 'Y') { ?>
                                <?php if ($current_user->ID != $s_user_id) { ?>

                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >
                                            <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>"  style="width:135px; height:135px;" border="3" class="img" alt="Private Photo"/>
                                        </a>                
                                    <?php } else {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>" >              
                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>"/></a>                
                                        <?php
                                    }
                                } else {
                                    ?>
                                     <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>" >
                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:135px; height:135px;" border="3" class="img" />
                                            </a>                
                                        <?php } else {
                                            ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                            </a>
                                        <?php } ?>
                                <?php } ?>

                            <?php } else { ?>

                                <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:135px; height:135px;" border="3" class="img" alt="<?php echo $displayed_member_name->display_name; ?>" />
                                </a>
                            <?php } ?>

                        <?php } ?>

                    </div>
                    <div class="user-status dspdp-h5 dspdp-username">
                        <span class="user-name dsp-username">
                            <strong>
                                <?php
                                if ($check_couples_mode->setting_status == 'Y') {
                                    if ($s_gender == 'C') {
                                        ?>
                                        <a href="<?php echo $root_link . get_username($s_user_id) . "/my_profile/"; ?>">
                                            <?php
                                            if (strlen($displayed_member_name->display_name) > 15)
                                                echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                            else
                                                echo $displayed_member_name->display_name;
                                            ?>                
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?> 
                                            <a href="<?php echo $root_link . get_username($s_user_id) . "/"; ?>">
                                                <?php
                                                if (strlen($displayed_member_name->display_name) > 15)
                                                    echo substr($displayed_member_name->display_name, 0, 13) . '...';
                                                else
                                                    echo $displayed_member_name->display_name;
                                                ?> <?php } ?></a> </strong></span>
                                        </div>
                                        <div class="user-details dspdp-spacer dspdp-user-details">
                                            <?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php echo get_gender($s_gender); ?> <?php echo __('from', 'wpdating'); ?> <br /><?php if (@$city_name->name != "") echo @$city_name->name . ','; ?> <?php if (@$state_name->name != "") echo @$state_name->name . ','; ?> <?php echo @$country_name->name; ?>
                                        </div>
                                        <div class="user-links dsp-none">
                                            <ul class="dspdp-row">
                                                <?php if ($check_my_friend_module->setting_status == 'Y') { // Check My friend module Activated or not    ?>
                                                    <li class="dspdp-col-xs-3">
                                                        <div class="dsp_fav_link_border">
                                                            <?php
                                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                                if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                    ?>
                                                                    <a href="<?php echo $root_link . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $s_user_id . "/"; ?>" title="<?php echo __('Add to Friends', 'wpdating'); ?>">
                                                                        <span class="fa fa-user"></span></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-user"></span></a> 
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-user"></span></a>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } // END My friends module Activation check condition   ?>
                                                <li class="dspdp-col-xs-3">
                                                    <div class="dsp_fav_link_border">
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            ?>
                                                            <a href="<?php echo $root_link . "add_favorites/user_id/" . $user_id . "/fav_userid/" . $s_user_id . "/"; ?>" title="<?php echo __('Add to Favorites', 'wpdating'); ?>"><span class="fa fa-heart"></span></a>
                                                        <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"><span class="fa fa-heart"></span></a>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <li class="dspdp-col-xs-3">
                                                    <div class="dsp_fav_link_border" >
                                                        <?php
                                                        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                            if (isset($check_my_friends_list) && $check_my_friends_list > 0) {
                                                                ?>
                                                                <a href="<?php echo $root_link . "email/compose/frnd_id/" . $s_user_id . "/Act/send_msg/"; ?>" title="<?php echo __('Send Message', 'wpdating'); ?>">
                                                                    <span class="fa fa-envelope-o"></span></a>
                                                            <?php } else { ?>
                                                                <a href="<?php echo $root_link . "email/compose/receive_id/" . $s_user_id . "/"; ?>" title="<?php echo __('Send Message', 'wpdating'); ?>">
                                                                    <span class="fa fa-envelope-o"></span></a>
                                                            <?php } //if($check_my_friends_list>0)     ?>
                                                        <?php } else { ?>
                                                            <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login"> <span class="fa fa-envelope-o"></span></a>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                                <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not    ?>
                                                    <li class="dspdp-col-xs-3">
                                                        <div class="dsp_fav_link_border">
                                                            <?php
                                                            if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
                                                                if ($check_user_profile_exist > 0) {  // check user dating profile exist or not
                                                                    ?>
                                                                    <a href='<?php echo $root_link . "view/send_wink_msg/receiver_id/" . $s_user_id . "/"; ?>' title="<?php echo __('Send Wink', 'wpdating'); ?>">
                                                                        <span class="fa fa-envelope-o"></span></a>
                                                                <?php } else { ?>
                                                                    <a href="<?php echo $root_link . "edit"; ?>" title="Edit Profile"><span class="fa fa-smile-o"></span></a>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <a href="<?php echo wp_login_url(get_permalink()); ?>" title="Login">  <span class="fa fa-smile-o"></span></a>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } // END My friends module Activation check condition     ?> 
                                            </ul>
                                        </div>
                                        </div></div>
                                    <?php }
                                    ?>

                                    </div>

                                    <div class="row-paging"> 
                                        <div style="float:left; width:100%;">
                                            <?php
// --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
                                            echo $pagination1
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
                                            ?>
                                        </div>  
                                    </div>
    <?php } ?>
                            </div>
                    
                                    <div class="tabcontent <?php
                                    
                                    if (!isset($pgurl))
                                        echo "hide";
                                    else if (!in_array($pgurl,array('search','guest_search')))
                                        echo "hide";
                                    ?>" id="tab-search"> 
                                        <form name="frmguestsearch" class="dspdp-form-horizontal" method="GET" action="<?php echo $root_link . "g_search_result"; ?>">
                                            <input type="hidden" name="search_type" value="basic_search" />
                                            <?php //---------------------------------START  GENERAL SEARCH---------------------------------------   ?>
                                            <div class="guest-search dsp-form-container">
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                                        <?php echo __('I am:', 'wpdating'); ?>
                                                    </span>
                                                    <span class="dspdp-col-sm-6 dsp-sm-6">
                                                        <select name="gender" class="dspdp-form-control dsp-form-control">
                                                            <?php echo get_gender_list('M'); ?>
                                                        </select>
                                                    </span>
                                                </p>
                                                <?php 
                                                   $genderList = get_gender_list('F');
                                                   if(!empty($genderList)):
                                                ?>
                                                    <p class="dspdp-form-group dsp-form-group clearfix">
                                                        <span class="dsp_left  dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                                            <?php echo __('Seeking a:', 'wpdating'); ?> 
                                                        </span>
                                                        <span class="dspdp-col-sm-6 dsp-sm-6">
                                                            <select name="seeking"  class="dspdp-form-control dsp-form-control">
                                                                <?php echo $genderList; ?>
                                                            </select>
                                                        </span>
                                                    </p>     
                                                <?php endif; ?>
                                               
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                                        <?php echo __('Age:', 'wpdating'); ?>
                                                    </span>
                                                    <span class="dspdp-col-sm-3 dsp-sm-3 dspdp-xs-form-group">
                                                        <select name="age_from"  class="dspdp-form-control dsp-form-control"> 
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
                                                </p>
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo __('Country:', 'wpdating'); ?></span>
                                                    <span class="dspdp-col-sm-6 dsp-sm-6">
                                                        <select name="cmbCountry" id="cmbCountry_id"  class="dspdp-form-control dsp-form-control">
                                                        <option value="0"><?php echo __('Select Country', 'wpdating'); ?></option>
                                                        <?php
                                                        $selectedCountryId = isset($check_default_country->setting_value) ? $check_default_country->setting_value : 0;
                                                        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");
                                                        foreach ($strCountries as $rdoCountries) {
                                                            $selected = ($rdoCountries->country_id == $selectedCountryId) ? "selected = selected" : "";
                                                            echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
                                                        }
                                                        ?>
                                                    </select></span>
                                                </p>
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">
                                                        <?php echo __('State:', 'wpdating'); ?>
                                                    </span>
                                                    <!--onChange="Show_state(this.value);"-->
                                                    <span id="state_change" class="dspdp-col-sm-6 dsp-sm-6">
                                                        <select name="cmbState" id="cmbState_id"  class="dspdp-form-control dsp-form-control">
                                                            <option value="0"><?php echo __('Select State', 'wpdating'); ?></option>
                                                            <?php 
                                                            if ($selectedCountryId != 0) {
                                                                $selectedCountriesStates = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId);
                                                                if(isset($selectedCountriesStates) && !empty($selectedCountriesStates)):
                                                                    foreach ($selectedCountriesStates as $state) {
                                                                        echo "<option value='" . $state->state_id . "' $selected >" . $state->name . "</option>";
                                                                    } 
                                                                endif;
                                                            }
                                                            ?>
                                                        </select>
                                                    </span>
                                                </p>
                                                <!-- End City combo-->
                                                <p  class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo __('City:', 'wpdating'); ?></span>
                                                    <!--onChange="Show_state(this.value);"-->
                                                    <span id="city_change" class="dspdp-col-sm-6 dsp-sm-6">
                                                        <select name="cmbCity" id="cmbCity_id" class="dspdp-form-control dsp-form-control">
                                                            <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                                                            <?php 
                                                                if ($selectedCountryId != 0) {
                                                                    $strStatesCheck = 0;
                                                                    $strStatesCheck = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_state_table where country_id='$selectedCountryId'");
                                                                    if ($strStatesCheck == 0){
                                                                        $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City', $selectedCountryId, true);
                                                                    }
//                                                                    $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId,true);
                                                                    if(isset($selectedCountriesCities) && !empty($selectedCountriesCities)):
                                                                        foreach ($selectedCountriesCities as $city) {
                                                                            echo "<option value='" . $city->city_id . "' $selected >" . $city->name . "</option>";
                                                                        } 
                                                                    endif;
                                                                }
                                                            ?>
                                                        </select>
                                                    </span>
                                                </p>
                                                <!-- End city combo-->
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                <span class="dsp_left  dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo __('Online Only', 'wpdating'); ?></span>
                                                <span class="dspdp-col-sm-6 dsp-sm-6"><select name="Online_only" class="dspdp-form-control dsp-form-control">
                                                    <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                                                    <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                                                </select></span>
                                                </p>
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3"><?php echo __('With Pictures Only', 'wpdating'); ?></span>
                                                    <span class="dspdp-col-sm-6 dsp-sm-6"><select name="Pictues_only" class="dspdp-form-control dsp-form-control">
                                                        <option value="P"><?php echo __('No Preference', 'wpdating'); ?></option>
                                                        <option value="N"><?php echo __('No', 'wpdating'); ?></option>
                                                        <option value="Y"><?php echo __('Yes', 'wpdating'); ?></option>
                                                    </select></span>
                                                </p>
                                                <p class="dspdp-form-group dsp-form-group clearfix">
                                                    <span class="dsp_left dspdp-control-label dsp-control-label dspdp-col-sm-3 dsp-sm-3">&nbsp;</span>
                                                    <span class="dsp_right dspdp-col-sm-6 dsp-sm-6">
                                                        <input type="submit" name="submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo __('Search', 'wpdating'); ?>" onclick="dsp_guest_search();" />
                                                    </span>
                                                </p>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
</div>