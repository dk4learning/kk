<?php
function dsp_sc_home_register()
{
    $pathh = WP_DSP_ABSPATH . "general_settings.php"; //echo $urll; //die;
    include_once($pathh);
    include_once('dsp_validation_functions.php');

    $pathh = WP_DSP_ABSPATH . 'recaptchalib.php';
    include_once($pathh);
    // google api setting
    $siteKey = isset($check_google_app_id) ? $check_google_app_id->setting_value : '';//"6LeaFf8SAAAAAOvDpgAV1P5Wo0tEc2gfi53B0Sl-";
    $secret = isset($check_google_secret_key) ? $check_google_secret_key->setting_value : '';
    $isGoogleApiKeySet = (!empty($siteKey) && !empty($secret)) ? true : false;
    # the response from reCAPTCHA
    $resp = null;
    # the error code from reCAPTCHA, if any
    $error = null;
    $isPaswordOptionEnabled =  ($check_password_option->setting_status == 'Y') ? true : false;
    $isFirstNLastNameEnabled = ($register_form_setting->setting_status == 'Y') ? true : false;
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
    if ($_POST['homeRegister']){

        //We shall SQL escape all inputs
        $username = esc_sql(sanitizeData(trim($_REQUEST['username']), 'xss_clean'));
        $firstname = (isset($_REQUEST['firstname']) && $isFirstNLastNameEnabled) ? esc_sql(sanitizeData(trim($_REQUEST['firstname']), 'xss_clean')): '';
        $lastname = (isset($_REQUEST['lastname']) && $isFirstNLastNameEnabled) ? esc_sql(sanitizeData(trim($_REQUEST['lastname']), 'xss_clean')) : '';
        $usernameExist = username_exists($username);
        if (strpos($username, " ") !== false) {
            $errors[]  = language_code('DSP_USER_NAME_SHOULD_NOT_CONTAIN_SPACES');
        } elseif (empty($username)) {
            $errors[] = language_code('DSP_USER_NAME_SHOULD_NO_BE_EMPTY');
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
                $errors[] = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
            } else if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $confirm_email)) {
                $errors[] = language_code('DSP_PLEASE_ENTER_A_VALID_EMAIL');
            } else if ($email != $confirm_email) {
                $errors[] = language_code('DSP_EMAIL_FIELDS_ARE_NOT_SAME');
            } else if ($check_terms_page_mode->setting_status == 'Y' && $_REQUEST['terms'] == '') {
                $errors[] = language_code('DSP_AGREE_TERMS_AND_CONDITIONDS');
            } else if (($resp == null || !$resp->success ) &&
                $check_recaptcha_mode->setting_status == 'Y'  &&
                $isGoogleApiKeySet
            )
            {
                $errors[] = language_code('DSP_ENTERED_WRONG_CAPTCHA');
            }else if(empty($firstname) && $isFirstNLastNameEnabled){
                $errors[] = language_code('DSP_FIRST_NAME_SHOULD_NO_BE_EMPTY');
            }else if(empty($lastname) && $isFirstNLastNameEnabled){
                $errors[] = language_code('DSP_LAST_NAME_SHOULD_NO_BE_EMPTY');
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
                            $errors[] = $usernameExist ? language_code('DSP_USER_NAME_ALREADY_EXIST') :'';
                        }
                        if (!empty($emailExist)) {
                            $errors[] = $emailExist ? language_code('DSP_EMAIL_ALREADY_EXIST') : '';
                        }
                    } else {
                        $from = get_option('admin_email');
                        $headers = language_code('DSP_FROM') . $from . "\r\n";
                        $subject = language_code('DSP_REGISTERATION_SUCCESSFULL');
                        $message = language_code('DSP_YOUR_LOGIN_DETAIL') . "\n" . language_code('DSP_USER_NAME') . $username . "\n" . language_code('DSP_PASSWORD') . $random_password;
                        $details =  array(
                            'email' => $email,
                            'message'=>$message,
                            'userId' => $user_id,
                            'password' => $random_password

                        );

                        $message = ($sendEmailVerification && $isPaswordOptionEnabled && array_key_exists('password',$_REQUEST)) ? apply_filters('dsp_send_activation_link',$details) : $message;
//                                wp_mail($email, $subject, $message, $headers);
                        $wpdating_email  = Wpdating_email_template::get_instance();
                        $result = $wpdating_email->send_mail( $email, '', $message );
                        $msg = language_code('DSP_NEW_PLEASE_CHECK_YOUR_EMAIL_FOR_LOGIN_DETAIL');
                        if(!$sendEmailVerification){
                            do_action('dsp_auto_logged_in',$user_id);
                        }

                    }
                } else {

                    $errors[] = language_code('DSP_IP_BLACKLIST_TEXT');
                }
            }

        }
    }
    $output = "
        <div class=\"tabcontent\" id=\"tab-register\"> 
                       
                    <div class=\"box-page\">";
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

        if (!empty($errors)){?>
            <div class="result error">
                <?php foreach($errors as $error){?>
                    <span style="color:white;"><?php echo $error; ?></span>
                <?php } ?>
            </div>
            <?php
        } }
    if (get_option('users_can_register')) {
        $output .= "
                            <div id=\"result\"></div> 
                           <form action=\"\" method=\"post\" class=\"dspdp-form-horizontal\" >
                                <div class=\"dsp_reg_main dsp-form-group\">
                                    <ul>
                                        <li class=\"dspdp-form-group dsp-form-group\">
  
                                           <span class=\"dsp-md-5  dspdp-col-sm-5\">
                                                <label for='username'>" . __(language_code('DSP_USER_NAME')) . "</label>
                                                <input type=\"text\" id=\"username\" name=\"username\" class=\"text dsp-form-control dspdp-form-control \" value=\"" . (isset($username) ? $username : ''). "\" required/>
                                           </span>
                                           <span class=\"dsp-md-5 dspdp-col-sm-5\">
                                                <label for='gender'>" . __(language_code('DSP_REGISTER_GENDER')) . "</label>
                                                <select name=\"gender\" id=\"gender\" class=\"dsp-form-control dspdp-form-control\">";
                                                    $g = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
                                                    $output .= get_gender_list($g) .
                                                "</select>                                     
                                           </span>
                                          
                                        </li>";

        if($isFirstNLastNameEnabled){
            $output .= "
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-md-5  dspdp-col-sm-5\">
                                                <label for='firstname'>" . __(language_code('DSP_GATEWAYS_FIRST_NAME')) . "</label>
                                                <input type=\"text\" name=\"firstname\" id=\"firstname\" class=\"text dsp-form-control dspdp-form-control\" value=\"" .  (isset($firstname) ? $firstname : '') . "\" />   
                                            </span>     
                                            <span class=\"dsp-md-5  dspdp-col-sm-5\">
                                                <label for='lastname'>" . __(language_code('DSP_GATEWAYS_LAST_NAME')) . "</label>
                                                <input type=\"text\" name=\"lastname\" id=\"lastname\" class=\"text dsp-form-control dspdp-form-control\" value=\"" .  (isset($lastname) ? $lastname : '') . "\" required/>
                                            </span>
                                          </li>";
        }

        $output .= "
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-md-5  dspdp-col-sm-5\">
                                                <label for='email'>" . __(language_code('DSP_EMAIL_ADDRESS')) . "</label>
                                                <input type=\"text\" name=\"email\" id=\"email\" class=\"text dsp-form-control dspdp-form-control\" value=\"" .  (isset($email) ? $email : '') . "\" required/>
                                            </span>  
                                            <span class=\"dsp-md-5  dspdp-col-sm-5\">
                                                <label for='confirm_email'>" . __(language_code('DSP_CONFIRM_EMAIL_ADDRESS')) . "</label>
                                                <input type=\"text\" name=\"confirm_email\" id=\"confirm_email\" class=\"text dsp-form-control dspdp-form-control\" value=\"" . (isset($confirm_email) ? $confirm_email : '') ."\" required/>
                                            </span>  
                                        </li>  
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-md-3  dspdp-col-sm-3\">
                                                <label for='dsp_day'>" . __('Day of birth', 'wpdating') . "</label>
                                                <select name=\"dsp_day\" id=\"dsp_day\" class=\"dsp-form-control dspdp-form-control dspdp-xs-form-group\" >";
                                                for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
                                                    $selectedDay = isset($_REQUEST['dsp_day']) && $_REQUEST['dsp_day'] == $dsp_day  ? "selected":'';
                                                    $output .= "<option value=\"" . $dsp_day . "\" " . $selectedDay . ">" . $dsp_day . "</option>";
                                                }
        $output .=                          "    </select>
                                            </span>  
                                            <span class=\"dsp-md-4  dspdp-col-sm-4\">
                                                <label for='dsp_mon'>" . __('Month', 'wpdating') . "</label>
                                                <select name=\"dsp_mon\" id=\"dsp_mon\" class=\"dsp-form-control dspdp-form-control  dspdp-xs-form group\" >";
                                                $mon = array(1 => language_code('DSP_JANUARY'),
                                                    language_code('DSP_FABRUARY'),
                                                    language_code('DSP_MARCH'),
                                                    language_code('DSP_APRIL'),
                                                    language_code('DSP_MAY'),
                                                    language_code('DSP_JUNE'),
                                                    language_code('DSP_JULY'),
                                                    language_code('DSP_AUGUST'),
                                                    language_code('DSP_SEPTEMBER'),
                                                    language_code('DSP_OCTOBER'),
                                                    language_code('DSP_NOVEMBER'),
                                                    language_code('DSP_DECEMBER'));
                                                foreach ($mon as $key => $value) {
                                                    $selectedMonth = isset($_REQUEST['dsp_mon']) && $_REQUEST['dsp_mon'] == $key  ? "selected":'';

                                                    $output .= "<option value=\"" . $key . "\" " . $selectedMonth . ">" . $value . "</option>";
                                                }
        $output .=                              "</select>
                                            </span> 
                                            <span class=\"dsp-md-3  dspdp-col-sm-3\">
                                                <label for='dsp_year'>" . __('Year', 'wpdating') . "</label>
                                                <select name=\"dsp_year\" id=\"dsp_year\" class=\"dsp-form-control  dspdp-form-control dspdp-xs-form-group\">";
                                                    $start_dsp_year = $check_start_year->setting_value;
                                                    $end_dsp_year = $check_end_year->setting_value;
                                                    $year = isset($_POST['dsp_year']) ? $_POST['dsp_year'] : $start_dsp_year;

                                                    $output .= dsp_get_year($start_dsp_year, $end_dsp_year, $year);
        $output .=                               "</select>
                                            </span>";


        if ($check_terms_page_mode->setting_status == 'Y') {
            $output .= "
                                             <li class=\"dspdp-form-group dsp-form-group\">
                                                <span class=\"dsp-md-5 dspdp-col-sm-5 dspdp-control-label\"><input class=\"check\" name=\"terms\" type=\"checkbox\" value=\"1\"  />&nbsp;";
            $output .=
                str_replace('[L]', $check_terms_page_mode->setting_value, language_code('DSP_TERMS_TEXT')) . "</span>
                                            </li>";
        }
        if ($check_recaptcha_mode->setting_status == 'Y' && $isGoogleApiKeySet) {
            $output .= '<li class="dspdp-form-group dsp-form-group captcha"><span class="dsp-md-3  dspdp-col-sm-3 dspdp-control-label">' . __(language_code('DSP_CAPTCHA')) . '</span>
                                             <span style="width:60px; margin-right:0px;" class="g-recaptcha"  data-sitekey="' . $siteKey . '">' . language_code('DSP_CAPTCHA') . '</span>
                                        </li>';
        }
        $output .= "<li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-md-9  dspdp-col-sm-9\">
                                                <input type=\"submit\" class=\"dspdp-btn dspdp-btn-default\" id=\"submitbtn\" name=\"homeRegister\" value=\"" . __(language_code('DSP_REGISTER')) . "\" />
                                                <?php if(!$isPaswordOptionEnabled){?>
                                                <span class=\"note-res\" style=\" float:none; margin-left:10px;\">" . __(language_code('DSP_NOTE_A_PASSWORD_WILL_BE_EMAIL_TO_YOU')) . "</span>
                                                <?php } ?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </form>";
        $output .= "
                            <script language=\"javascript\" type=\"text/javascript\">";
        /* this is just a simple reload; you can safely remove it; remember to remove it from the image too */

        $output .= "
                          function reloadCaptcha()
                                {
                                    document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + new Date();
                                }
                            </script>";
    } else {
        $output .= "
                            <span style=\"float:left; width:100%; text-align:center; color:#ff0000;\">" .
            __(language_code('DSP_REGISTRATION_IS_CURRENTLY_DISABLE_PLEASE_TRY_AGAIN_LATER')) .
            "</span>";

    }
    $output .= "
                    </div></div>";
    return $output;
}

add_shortcode('dsp_home_register', 'dsp_sc_home_register');