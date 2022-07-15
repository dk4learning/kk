<?php
function dsp_sc_register()
{
    $pathh = WP_DSP_ABSPATH . "general_settings.php"; //echo $urll; //die;
    include_once($pathh);

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

    $output = "
        <div class=\"tabcontent\" id=\"tab-register\"> 
                       
                    <div class=\"box-page\">";

    if (get_option('users_can_register')) {
        $output .= "
                            <div id=\"result\"></div> 
                           <form action=\"" . get_site_url() . "/members/register" . "\" method=\"post\" class=\"dspdp-form-horizontal\" >
                                <div class=\"dsp_reg_main dsp-form-group\">
                                    <ul>
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('Username: ', 'wpdating')) . "</span>
                                            <span class=\"dsp-md-6  dspdp-col-sm-6\"><input type=\"text\" name=\"username\" class=\"text dsp-form-control dspdp-form-control \" value=\"" . "\" /></span>

                                        </li>";
        
                                    if($isFirstNLastNameEnabled){
                                           $output .= "
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                        <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('First Name', 'wpdating')) . "</span>
                                        <span class=\"dsp-md-6  dspdp-col-sm-6\"><input type=\"text\" name=\"firstname\" class=\"text dsp-form-control dspdp-form-control\" value=\"" . "\" /></span>
                                        </li>
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                        <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('Last Name', 'wpdating')) . "</span>
                                        <span class=\"dsp-md-6  dspdp-col-sm-6\"><input type=\"text\" name=\"lastname\" class=\"text dsp-form-control dspdp-form-control\" value=\"" . "\" /></span>
                                        </li>";
                                    }
                                    if($isPaswordOptionEnabled){
                                    	
                                         $values['password'] = isset($password) ? $password : '';
                                         $values['rePassword'] = isset($rePassword) ? $rePassword : '';
                                         $content = '';
                                         $content = apply_filters('dsp_register_form_filter',$content,$values); 
                                         $output .= $content;
                                    }


        $output .= "
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('Email:', 'wpdating')) . "</span>
                                            <span class=\"dsp-md-6  dspdp-col-sm-6\"><input type=\"text\" name=\"email\" class=\"text dsp-form-control dspdp-form-control\" value=\"" . "\" /></span>
                                        </li>
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('Confirm Email', 'wpdating')) . "</span>
                                            <span class=\"dsp-md-6  dspdp-col-sm-6\"><input type=\"text\" name=\"confirm_email\" class=\"text dsp-form-control dspdp-form-control\" value=\"" . "\" /></span>
                                        </li>
                                         
                                        <li class=\"dspdp-form-group dsp-form-group\"> 
                                           <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">" . __(__('Gender', 'wpdating')) . "</span>
                                            <span class=\"dsp-md-6  dspdp-col-sm-6\">
                                                <select name=\"gender\" class=\"dsp-form-control dspdp-form-control\">";

        $g = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
        $output .= get_gender_list($g) .

            "</select>
                                            </span>
                                        </li>
                                        <li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label\">";
        $output .= __(__('Birth Date', 'wpdating')) .
            "</span>";

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


        $output .= "
                                            <span class=\"dsp-md-3 dspdp-col-sm-3\">
                                                <select name=\"dsp_mon\" class=\"dsp-form-control dspdp-form-control  dspdp-xs-form-group\" >";

        foreach ($mon as $key => $value) {
            $selectedMonth = '';

            $output .= "
                                                            <option value=\"" . $key . "\" " . $selectedMonth . ">" . $value . "</option>";

        }
        $output .= "
                                                </select>
                                            </span>";
        //make the day pull-down menu
        $output .= "
                                            <span class=\"dsp-md-2 dspdp-col-sm-2\">
                                            <select name=\"dsp_day\" class=\"dsp-form-control dspdp-form-control dspdp-xs-form-group\" >";

        for ($dsp_day = 1; $dsp_day <= 31; $dsp_day++) {
            $selectedDay = '';

            $output .= "
                                                        <option value=\"" . $dsp_day . "\" " . $selectedDay . ">" . $dsp_day . "</option>";

        }
        $output .= "
                                            </select>
                                            </span>";
        $start_dsp_year = $check_start_year->setting_value;
        $end_dsp_year = $check_end_year->setting_value;                                    

        $output .= "
                                            <span class=\"dsp-md-2  dspdp-col-sm-2\">
                                            <select name=\"dsp_year\" class=\"dsp-form-control  dspdp-form-control dspdp-xs-form-group\">";
        $output .= dsp_get_year($start_dsp_year, $end_dsp_year, 1);
        $output .= "
                                            </select>
                                            </span>
                                        </li>";

        if ($check_terms_page_mode->setting_status == 'Y') {
            $output .= "
                                             <li class=\"dspdp-form-group dsp-form-group\">
                                                <span class=\"dsp-md-3  dspdp-col-sm-3 dspdp-control-label\">&nbsp;</span>";
            $output .=
                str_replace('[L]', $check_terms_page_mode->setting_value, __('I agree to the <a href=[L] target=_blank>Terms and Conditions</a>.', 'wpdating')) . "<input class=\"check\" name=\"terms\" type=\"checkbox\" value=\"1\"  />
                                            </li>";
        }
        if ($check_recaptcha_mode->setting_status == 'Y' && $isGoogleApiKeySet) {
            $output .= '<li class="dspdp-form-group dsp-form-group captcha"><span class="dsp-md-3  dspdp-col-sm-3 dspdp-control-label">' . __(__('Captcha:', 'wpdating')) . '</span>
                                             <span style="width:60px; margin-right:0px;" class="g-recaptcha"  data-sitekey="' . $siteKey . '">' . __('Captcha:', 'wpdating') . '</span>
                                        </li>';
        }
        $output .= "<li class=\"dspdp-form-group dsp-form-group\">
                                            <span class=\"dsp-md-3  dspdp-col-sm-3 dspdp-control-label\">&nbsp;</span>
                                            <span class=\"dsp-md-9  dspdp-col-sm-9\">
                                                <input type=\"submit\" class=\"dspdp-btn dspdp-btn-default\" id=\"submitbtn\" name=\"submit\" value=\"" . __(__('Register', 'wpdating')) . "\" />
                                                <?php if(!$isPaswordOptionEnabled){?>
                                                <span class=\"note-res\" style=\" float:none; margin-left:10px;\">" . __(__('Note: A password will be emailed to you.', 'wpdating')) . "</span>
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
            __(__('Registration is currently disabled.', 'wpdating')) .
            "</span>";

    }
    $output .= "
                    </div></div>";
    return $output;
}

add_shortcode('dsp_register', 'dsp_sc_register');