<?php
class Wpdating_Search_Public
{
    public function __construct()
    {
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style( 'wpdating-search-css', plugin_dir_url( __FILE__ ) . 'css/wpdating-search-public.css', array(), '', 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
    }

    public function new_search()
    {
        wp_enqueue_script('wpdating-search-js', plugin_dir_url( __FILE__ ) . 'js/wpdating-search-public.js', array( 'jquery' ));
        wp_localize_script('wpdating-search-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));

        $user_id  = get_current_user_id();
        $access_feature_name        = "Advanced Search";

        $response = $this->check_membership_access($user_id, $access_feature_name);

        if ('Access' == $response['value']) {
            $this->search_display();
        }else{
            $pageurl             = get('pid');
            $root_link           = get_site_url() . '/members/';
            ${$response['name']} = $response['value'];
            include_once(WP_DSP_ABSPATH . "dsp_print_message.php");
        }
    }

    public function check_membership_access($user_id, $access_feature_name)
    {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $check_free_mode            = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_mode'");
        $setting                    = array();


        if ($check_free_mode->setting_status == "N") {  // free mode is off
            $check_free_trail_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_mode'");
            if ($check_free_trail_mode->setting_status == "Y") { // if free trial mode is ON
                $setting = ['name' => 'check_free_trail_mode',
                    'value' => check_free_trial_feature($access_feature_name, $user_id)];
            } else {
                $check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_profile'");
                $check_approve_profile_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_profiles'");

                if ($check_force_profile_mode->setting_status == "Y") {
                    $access_feature_name = isset($access_feature_name) ? $access_feature_name : '';
                    $setting = ['name' => 'check_force_profile_msg',
                        'value' => check_force_profile_feature($access_feature_name, $user_id)];
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        $setting = ['name' => 'check_approved_profile_msg',
                            'value' => $check_approved_profile_msg];
                    } else if ($check_approved_profile_msg == "Access") {
                        $setting = ['name' => 'check_membership_msg',
                            'value' => check_membership($access_feature_name, $user_id)];
                    }
                } else { // free trial mode is off
                    $setting = ['name' => 'check_membership_msg',
                        'value' => check_membership($access_feature_name, $user_id)];
                }
            }
        } else {
            if ($_SESSION['free_member']) {
                $setting = ['name' => 'free_membership_msg',
                    'value' => 'Access'];
            } else {
                $check_force_profile_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'force_profile'");
                $check_approve_profile_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_profiles'");

                if ($check_force_profile_mode->setting_status == "Y") {
                    $setting = ['name' => 'check_force_profile_msg',
                        'value' => check_force_profile_feature($access_feature_name, $user_id)];
                } else if ($check_approve_profile_status->setting_status == "N") { // if approve profile mode is OFF
                    $check_approved_profile_msg = check_approved_profile_feature($user_id);
                    if ($check_approved_profile_msg == "NoAccess") {
                        $setting = ['name' => 'check_approved_profile_msg',
                            'value' => $check_approved_profile_msg];
                    } else if ($check_approved_profile_msg == "Access") {
                        $setting = ['name' => 'check_membership_msg',
                            'value' => check_membership($access_feature_name, $user_id)];
                    }
                } else {
                    $setting = ['name' => 'check_membership_msg',
                        'value' => check_membership($access_feature_name, $user_id)];
                }
            }

        }
        return $setting;

    }

    public function search_display()
    {

        global $wpdb;
        $user_id                     = get_current_user_id();
        $userProfileDetails          = apply_filters('dsp_get_profile_details', $user_id);
        $userProfileDetailsExist     = $userProfileDetails != false ? true : false;        $gender = $userProfileDetailsExist ? $userProfileDetails->gender : '';
        $gender_list = get_gender_list($gender);
        //For min and max age
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_country_table          = $wpdb->prefix . DSP_COUNTRY_TABLE;
        $check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
        $check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
        $min_age_value = $check_min_age->setting_value;
        $max_age_value = $check_max_age->setting_value;

        $check_default_country = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'default_country'");
        $selectedCountryId = isset($check_default_country->setting_value) ? $check_default_country->setting_value : 0;
        $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");

        $pieces = $_SERVER["REQUEST_URI"];
        $pieces = explode('/', $pieces);
        $page_index = array_search('save_search_Id', $pieces);
        $save_page_index = !empty($page_index) ? $pieces[$page_index + 1] : 0;
    ?>
            <input type="hidden" id="page_index" name="page_index" value="<?php echo $save_page_index; ?>">
            <div>

                <div class="row">
                    <div class="col-lg-12 quick_search">
                        <form name="quick_search_form" id="quick_search_form" method="GET">
                            <div class="col-lg-2">
                                <label> Name:</label>
                                <input type="text" name="user_name" id="user_name">
                            </div>
                            <div class="col-lg-2">
                                <label><?php echo __('I am:', 'wpdating'); ?></label>
                                <select name="gender" id="gender">
                                    <?php echo $gender_list; ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label><?php echo __('Seeking a:', 'wpdating'); ?></label>
                                <select name="seeking_gender" id="seeking_gender">
                                    <?php echo $gender_list; ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label><?php echo __('Age:', 'wpdating'); ?></label>
                                <select name="age_from" id="age_from" >
                                    <?php
                                        for ($from = $min_age_value; $from <= $max_age_value; $from++) {
                                            if ($from == $min_age_value) {
                                                ?>
                                                <option value="<?php echo $from ?>" selected="selected"><?php echo $from ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $from ?>"><?php echo $from ?></option>
                                                <?php
                                            }
                                        }
                                     ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>to:</label>
                                <select name="age_to" id="age_to">
                                    <?php
                                    for ($from = $min_age_value; $from <= $max_age_value; $from++) {
                                        if ($from == $max_age_value) {
                                            ?>
                                            <option value="<?php echo $from ?>" selected="selected"><?php echo $from ?></option>
                                        <?php } else { ?>
                                            <option value="<?php echo $from ?>"><?php echo $from ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                        <div class="col-lg-2 search_btn">
                            <label>&nbsp;</label>
                            <button onclick="quick_search()">Search</button>
                        </div>
                    </div>
                </div>

        </div>
        <div class="row">
            <div class="col-lg-2 advance_search">
                <div>
                    <h4 class="advance_search_title">Advance search</h4>
                </div>
                <div class="dsp-row">
                    <form  name="filter_search_form" id="filter_search_form"  action="" method="GET">
                        <div class="dsp_country">
                            <label>  <?php echo __('Country:', 'wpdating'); ?> </label>
                            <select name="country_id" id="country_id" onchange="country_selection()">
                                <option value="0">
                                    <?php echo __('Select Country', 'wpdating'); ?>
                                </option>
                                <?php
                                foreach ($strCountries as $rdoCountries) {
                                    $selected = ($rdoCountries->country_id == $selectedCountryId) ? "selected = selected" : "";
                                    echo "<option value='" . $rdoCountries->country_id . "' $selected >" . $rdoCountries->name . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="dsp_state">
                            <label> <?php echo __('State:', 'wpdating'); ?></label>
                            <select name="state_id" id="state_id" onchange="state_selection()">
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
                        <div class="dsp_city">
                            <label><?php echo __('City:', 'wpdating'); ?></label>
                            <select name="city_id" id="city_id">
                                <option value="0"><?php echo __('Select City', 'wpdating'); ?></option>
                                <?php
                                if ($selectedCountryId != 0) {
                                    $selectedCountriesCities = apply_filters('dsp_get_all_States_Or_City',$selectedCountryId,true);
                                    if(isset($selectedCountriesCities) && !empty($selectedCountriesCities)):
                                        foreach ($selectedCountriesCities as $city) {
                                            echo "<option value='" . $city->city_id . "' >" . $city->name . "</option>";
                                        }
                                    endif;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="dsp_pictures_only">
                            <label>
                                <input type="checkbox" name="with_pictures" id="with_pictures" value="Y"><?php echo __('With Pictures Only', 'wpdating'); ?>
                            </label>
                        </div>
                        <div class="online_only">
                            <label>
                                <input type="checkbox" name="online_only" id="online_only" value="Y"><?php echo __('Online Only', 'wpdating') ?>
                            </label>
                        </div>
                        <div class="save_search">
                            <label>
                                <input type="checkbox" name="save_search" id="save_search" data-toggle="collapse" data-target="#search_name">
                                <?php echo __('Save this Search as:', 'wpdating'); ?>
                            </label>
                        </div>
                        <div class="collapse" id="search_name" >
                            <input type="text" name="search_name" id="search_name" placeholder="Title for this search"/>
                        </div>
                        <div class="add_option">
                            <a role="button" data-toggle="collapse" data-target="#demo"><span class="icon-plus" style="margin-right: 10px;"></span>More option</a>
                            <div id="demo" class="collapse">
                                <?php
                                $myrows = $this->get_additional_question_items();
                                foreach ($myrows as $profile_questions) {
                                    $ques_id = $profile_questions->profile_setup_id;
                                    $profile_ques = $profile_questions->question_name;
                                ?>
                                <div>
                                    <div class="panel-heading" role="tab" id="heading<?php echo $ques_id; ?>">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse"  href="#collapse<?php echo "$ques_id"?>" aria-expanded="true" aria-controls="collapse<?php echo "$ques_id"?>">
                                                <div class="text-title text-title-<?php echo $ques_id; ?>">
                                                    <span class="icon-plus" style="margin-right: 10px;"></span>
                                                    <label><?php echo __($profile_ques,'wpdating') ; ?></label>
                                                </div>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse<?php echo "$ques_id"?>" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading<?php echo "$ques_id"?>">
                                        <div class="panel-body">
                                            <ul class="option-btn-adv dspdp-row dsp-row">
                                                <?php
                                                $myrows_options = $this->get_question_options($ques_id);
                                                foreach ($myrows_options as $profile_questions_options) { ?>
                                                    <li class="dspdp-col-sm-12 dsp-sm-12">
                                                        <input type="checkbox" name="profile_question_option_id[]" value="<?php echo $profile_questions_options->question_option_id ?>"/>&nbsp;
                                                        <?php echo __($profile_questions_options->option_value,'wpdating') ?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                    <div class="dsp_filter_btn">
                        <label>&nbsp;</label>
                        <button onclick="search_filter()">Filter</button>
                    </div>
                    <input type="hidden" id="dsp_search_page" value="1">
                    <input type="hidden" id="search_status" value="0">
                </div>
            </div>
            <div class="ScrollStyle col-lg-10">
                <div class="col-lg-12" id="results">

                </div>
            </div>
        </div>

    <?php
    }

    public function ajax_load_initial_data()
    {
        $page = (isset($_POST['page'])) ? $_POST['page'] : 1;
        global $wpdb;
        $dsp_users           = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_user_profiles  = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_user_online    = $wpdb->prefix . DSP_USER_ONLINE_TABLE;

        $user_id            = get_current_user_id();

        $sql_query = "SELECT * from $dsp_users AS user 
                          INNER JOIN $dsp_user_profiles AS user_profile
                          on user.ID = user_profile.user_id
                          LEFT JOIN $dsp_user_online AS user_online
                          on user.ID = user_online.user_id
                          AND user.ID != $user_id ORDER BY user_online.time DESC, user.user_registered DESC";

        $offset = ($page - 1) * 16;
        $sql_query = "$sql_query LIMIT {$offset}, 16";
        $data = $wpdb->get_results($sql_query, ARRAY_A);

        $response = $this->prepare_items_for_response($data);

        print_r($response);
        die;
    }

    public function ajax_new_quick_search()
    {
        $data = $_GET['data'];
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;

        $values = array();
        foreach ($data as $d){
            $values[$d['name']] = $d['value'];
        }

        if (empty($values['user_name'])){
            $response = $this->get_results_by_other_criteria($values, $page);
        }else{
            $response = $this->get_result_by_name($values, $page);
        }
        print_r($response);
        die;
    }

    public function ajax_saved_search_result()
    {
        global $wpdb;
        $dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
        $search_id = $_GET['search_id'];
        $page   = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $values = array();
        $search1 = $wpdb->get_row("SELECT * FROM $dsp_user_search_criteria_table Where user_search_criteria_id='{$search_id}'");
        $values['gender'] = $search1->user_gender;
        $values['seeking_gender'] = $search1->seeking_gender;
        $values['age_to'] = $search1->age_to;
        $values['age_from'] = $search1->age_from;
        $values['country_id'] = $search1->country_id;
        $values['state_id'] = $search1->state_id;
        $values['city_id'] = $search1->city_id;
        $values['user_name'] = $search1->username;
        $values['with_pictures'] = $search1->with_pictures;
        $values['online_only']   = $search1->online_only;
        $values['profile_question_option_id[]'] = explode(',',$search1->Profile_questions_option_ids);
        if (empty($values['user_name'])){
            $response = $this->get_results_by_other_criteria($values, $page, false);
        }else{
            $response = $this->get_result_by_name($values, $page, false);
        }
        print_r($response);
        die;
    }

    function get_result_by_name($request, $page, $quick = true)
    {
        global $wpdb;
        $dsp_users           = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_user_profiles  = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_online    = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
        $user_name          = $request['user_name'];
        $seeking            = $request['seeking_gender'];
        $age_from           = $request['age_from'];
        $age_to             = $request['age_to'];
        $gender             = $request['gender'];
        $country_id         = $request['country_id'];
        $state_id           = $request['state_id'];
        $city_id            = $request['city_id'];
        $pictures_only      = isset($request['with_pictures']) && !empty($request['with_pictures']) ? $request['with_pictures'] : 'N';
        $online_only        = isset($request['online_only']) && !empty($request['online_only']) ? $request['online_only'] : 'N';
        $question_values    = isset($request['profile_question_option_id[]']) && !empty($request['profile_question_option_id[]']) ? implode(',', $request['profile_question_option_id[]']) : '';
        $search_name       = isset($request['search_name']) ? $request['search_name'] : '';

        $user_id            = get_current_user_id();
        //calculate the age to which to select
        $time_to         = strtotime(date('Y-m-d') . '-' . $age_from . ' year');
        $date_to = date('Y-m-d', $time_to);

        //calculate the age from which to select
        $time_from         = strtotime(date('Y-m-d') . '-' . $age_to. ' year');
        $date_from = date('Y-m-d', $time_from);
        $sql_query = "";
        if (true == $quick){
            $sql_query .= "SELECT * from $dsp_users AS user 
                          INNER JOIN $dsp_user_profiles AS user_profile
                          on user.ID = user_profile.user_id
                          LEFT JOIN $dsp_user_online AS user_online
                          on user.ID = user_online.user_id
                          WHERE user_profile.gender = '{$seeking}'
                          AND user_profile.seeking = '{$gender}'
                          AND user_profile.age BETWEEN '{$date_from}' AND '{$date_to}'
                          AND user.display_name LIKE '%{$user_name}%'
                          AND user.ID != $user_id";
        }else{
            $sql_query .= " SELECT * from $dsp_users AS user 
                             INNER JOIN $dsp_user_profiles AS user_profile
                             on user.ID = user_profile.user_id
                             LEFT JOIN $dsp_user_online AS user_online
                             on user.ID = user_online.user_id";

            if ('Y' == $pictures_only){
                $sql_query .= " INNER JOIN $dsp_members_photos AS user_photos
                               on user.ID = user_photos.user_id";
            }

            if ('Y' == $online_only){
                $sql_query_1 = "LEFT JOIN ";
                $sql_query_2 = " INNER JOIN ";

                $sql_query = str_replace($sql_query_1, $sql_query_2, $sql_query);
            }

            if (!empty($question_values)){
                $sql_query .= " INNER JOIN $dsp_question_details AS question
                               on user.ID = question.user_id";
            }

            $sql_query .=  " WHERE user_profile.gender = '{$seeking}'
                             AND user_profile.seeking = '{$gender}'
                             AND user_profile.age BETWEEN '{$date_from}' AND '{$date_to}'
                             AND user.display_name LIKE '%{$user_name}%'
                             AND user.ID != $user_id";

            if (!empty($question_values)){
                $sql_query .= " AND question.profile_question_option_id IN({$question_values})";
            }

            $sql_query .= (0 < $country_id) ? " AND user_profile.country_id = {$country_id}" : '';
            $sql_query .= (0 < $state_id) ? " AND user_profile.state_id = {$state_id}" : '';
            $sql_query .= (0 < $city_id) ? " AND user_profile.city_id = {$city_id}" : '';

            if (!empty($search_name)){
                $this->save_search($request);
            }

        }
        $offset = ($page - 1) * 16;
        $sql_query .= " ORDER BY user_online.time DESC LIMIT {$offset}, 16";
        $data = $wpdb->get_results($sql_query, ARRAY_A);

        if (1 == $page &&  empty($data)){
            return "<h3>No record found for your search criteria.</h3>";
        }

        $response = $this->prepare_items_for_response($data);

        return $response;
    }

    function get_results_by_other_criteria($request, $page, $quick = true)
    {
        global $wpdb;
        $dsp_users           = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_user_profiles  = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_online    = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
        $seeking            = $request['seeking_gender'];
        $age_from           = $request['age_from'];
        $age_to             = $request['age_to'];
        $gender             = $request['gender'];
        $country_id         = (isset($request['country_id'])) ? $request['country_id'] : '';
        $state_id           = (isset($request['state_id'])) ? $request['state_id'] : '';
        $city_id            = (isset($request['city_id'])) ? $request['city_id'] : '';
        $pictures_only      = isset($request['with_pictures']) && !empty($request['with_pictures']) ? $request['with_pictures'] : 'N';
        $online_only        = isset($request['online_only']) && !empty($request['online_only']) ? $request['online_only'] : 'N';
        $question_values    = isset($request['profile_question_option_id[]']) && !empty($request['profile_question_option_id[]']) ? implode(',', $request['profile_question_option_id[]']) : '';
        $search_name       = isset($request['search_name']) ? $request['search_name'] : '';

        $user_id            = get_current_user_id();
        //calculate the age to which to select
        $time_to         = strtotime(date('Y-m-d') . '-' . $age_from . ' year');
        $date_to = date('Y-m-d', $time_to);

        //calculate the age from which to select
        $time_from         = strtotime(date('Y-m-d') . '-' . $age_to. ' year');
        $date_from = date('Y-m-d', $time_from);

        $sql_query = "";

        if (true == $quick) {
            $sql_query .= " SELECT * from $dsp_users AS user 
                             INNER JOIN $dsp_user_profiles AS user_profile
                             on user.ID = user_profile.user_id
                             LEFT JOIN $dsp_user_online AS user_online
                             on user.ID = user_online.user_id
                             WHERE user_profile.gender = '{$seeking}'
                             AND user_profile.seeking = '{$gender}'
                             AND (user_profile.age BETWEEN '{$date_from}' AND '{$date_to}')
                             AND user.ID != $user_id";
        } else{
            $sql_query .= " SELECT * from $dsp_users AS user 
                             INNER JOIN $dsp_user_profiles AS user_profile
                             on user.ID = user_profile.user_id
                             LEFT JOIN $dsp_user_online AS user_online
                             on user.ID = user_online.user_id";

            if ('Y' == $pictures_only){
                $sql_query .= " INNER JOIN $dsp_members_photos AS user_photos
                               on user.ID = user_photos.user_id";
            }

            if ('Y' == $online_only){
                $sql_query_1 = "LEFT JOIN ";
                $sql_query_2 = " INNER JOIN ";

                $sql_query = str_replace($sql_query_1, $sql_query_2, $sql_query);
            }

            if (!empty($question_values)){
                $sql_query .= " INNER JOIN $dsp_question_details AS question
                               on user.ID = question.user_id";
            }

            $sql_query .=  " WHERE user_profile.gender = '{$seeking}'
                             AND user_profile.seeking = '{$gender}'
                             AND (user_profile.age BETWEEN '{$date_from}' AND '{$date_to}')
                             AND user.ID != $user_id";

            if (!empty($question_values)){
                $sql_query .= " AND question.profile_question_option_id IN({$question_values})";
            }

            $sql_query .= (0 < $country_id) ? " AND user_profile.country_id = {$country_id} " : '';
            $sql_query .= (0 < $state_id) ? " AND user_profile.state_id = {$state_id} AND" : '';
            $sql_query .= (0 < $city_id) ? " AND user_profile.city_id = {$city_id} AND" : '';

            if (!empty($search_name)){
                $this->save_search($request);
            }
        }

        $offset = ($page - 1) * 16;
        $sql_query .= " ORDER BY user_online.time DESC, user.user_registered DESC LIMIT {$offset}, 16";

        $data = $wpdb->get_results($sql_query, ARRAY_A);

        if (1 == $page &&  empty($data)){
            return "<h3>No record found for your search criteria.</h3>";
        }

        $response = $this->prepare_items_for_response($data);

        return $response;
    }

    public function prepare_items_for_response($request)
    {
        global $wpdb;
        $data   = array();
        $fields = [
            'ID',
            'user_id',
            'user_profile_id',
            'user_login',
            'display_name',
            'country_id',
            'state_id',
            'city_id',
            'gender',
            'seeking',
            'age',
            'status_id',
            'make_private',
            'status'
        ];
        $i = 0;
        foreach ($request as $rq) {
            foreach ($fields as $fs) {
                if (isset($rq[$fs])){
                    $data[$i][$fs] = $rq[$fs];
                }
            }
            $i++;
        }

        //get city and country and image

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['country'] = $this->get_country_name($data[$i]['country_id']);
            $data[$i]['city'] = $this->get_city_name($data[$i]['city_id']);
            $data[$i]['age'] = $this->get_age($data[$i]['age']);

            if ($data[$i]['make_private'] == 'N') {
                $data[$i]['image'] = $this->get_image($data[$i]['ID'], false, $data[$i]['gender']);
            } else {
                $data[$i]['image'] = $this->get_image($data[$i]['ID'], true, $data[$i]['gender']);
            }
        }

        $output = '';
        $i = 0;
        $root_link = get_bloginfo('url');
        while ($i < count($data)){
                $output .= "<div class='col-lg-3 col-sm-4 col-xs-6 search_result_user'>
                                <div class='search_single_wrap'>
                                    <div class='search_img_part'>
                                    <a href='" . $root_link . "/members/" . get_username($data[$i]['ID']) . "'><img src='". $data[$i]['image'] . "' alt='no image' style=\"width:200px; height:200px;\" /></a>
                                    </div>";
                if (isset($data[$i]['status']) && 'Y' == $data[$i]['status']){
                       $output .= "<li class='user_status'> Online </li>";
                }
                        $output .="<div class='search_text_part'> <li class='username'><a href='" . $root_link . "/members/" . get_username($data[$i]['ID']) . "'>";

                if (strlen($data[$i]['display_name']) > 15){
                    $output .= substr($data[$i]['display_name'], 0, 13) . "...";
                } else {
                    $output .= $data[$i]['display_name'];
                }

                        $output .= "</a></li>
                                    <li class='age'>" . $data[$i]['age'] . "</li> 
                                    <li class='country'>" . $data[$i]['country'] . "</li>
                                    </div>";

                $output .= "   </div>
                            </div>
                            ";
                ++$i;
        }

        return $output;
    }

    public function get_country_name($id)
    {
        global $wpdb;
        $dsp_country           = $wpdb->prefix . DSP_COUNTRY_TABLE;

        $country = $wpdb->get_row("SELECT * FROM $dsp_country WHERE country_id = {$id}");

        if (null == $country){
            return null;
        }

        return $country->name;
    }

    public function get_city_name($id)
    {
        global $wpdb;
        $dsp_city           = $wpdb->prefix . DSP_CITY_TABLE;

        $city = $wpdb->get_row("SELECT * FROM $dsp_city WHERE city_id = {$id}");

        if (null == $city){
            return null;
        }

        return $city->name;
    }
    public function get_age($age)
    {
        $date = new DateTime($age);
        $now = new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }

    public function get_image($user_id, $private, $status = "M")
    {
        global $wpdb;

        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $my_img             = $wpdb->get_row("Select picture from $dsp_members_photos where user_id=$user_id");
        $image              = '';
        if ($private == true) {
            $image = get_site_url() . "/wp-content/plugins/dsp_dating/images/private-photo-pic.jpg";
        } else {
            if (null != $my_img) {
                $image = get_site_url() . "/wp-content/uploads/dsp_media/user_photos/user_{$user_id}/thumbs/thumb_{$my_img->picture}";
            }else{
                if ($status == 'F'){
                    $image = get_site_url() . "/wp-content/plugins/dsp_dating/images/female-generic.jpg";
                }else if ($status == 'C'){
                    $image = get_site_url() . "/wp-content/plugins/dsp_dating/images/couples-generic.jpg";
                }else{
                    $image = get_site_url() . "/wp-content/plugins/dsp_dating/images/male-generic.jpg";
                }
            }
        }
        return $image;
    }

    public function get_city_by_country_id()
    {
        global $wpdb;
        $country_id = $_POST['country_id'];
        $selected_country_cities = apply_filters('dsp_get_all_States_Or_City', $country_id, true);
        $output = "<option value='0'>" . __('Select City', 'wpdating') . "</option>";
        if (!empty($selected_country_cities)) {
            foreach ($selected_country_cities as $city) {
                $output .= "<option value='" . $city->city_id . "'> " . $city->name . "</option>";
            }
        }

        print_r($output);
        die();
    }

    public function get_state_by_country_id()
    {
        global $wpdb;
        $country_id = $_POST['country_id'];
        $selected_country_states = apply_filters('dsp_get_all_States_Or_City', $country_id, false);
        $output = "<option value='0'>" . __('Select State', 'wpdating') . "</option>";
        if (!empty($selected_country_states)) {
            foreach ($selected_country_states as $state) {
                $output .= "<option value='" . $state->state_id . "'> " . $state->name . "</option>";
            }
        }

        print_r($output);
        die();
    }

    public function get_city_by_state_id()
    {
        global $wpdb;
        $state_id = $_POST['state_id'];
        $dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;
        $selected_state_cities = $wpdb->get_results("SELECT * FROM $dsp_city_table WHERE state_id = {$state_id}");
        $output = "<option value='0'>" . __('Select City', 'wpdating') . "</option>";
        if (!empty($selected_state_cities)) {
            foreach ($selected_state_cities as $city) {
                $output .= "<option value='" . $city->city_id . "'> " . $city->name . "</option>";
            }
        }

        print_r($output);
        die();
    }

    public function get_additional_question_items()
    {
        global $wpdb;
        $lang_code = dsp_get_current_user_language_code();
        if($lang_code=='en')
        {
            $dsp_profile_setup_table = $wpdb->prefix . "dsp_profile_setup";
        }
        else
        {
            $dsp_profile_setup_table = $wpdb->prefix . "dsp_profile_setup_" . $lang_code;
        }

        $myrows = $wpdb->get_results("SELECT * FROM $dsp_profile_setup_table Where display_status = 'Y' Order BY sort_order");

        return $myrows;
    }

    public function get_question_options($ques_id)
    {
        global $wpdb;
        $lang_code = dsp_get_current_user_language_code();

        if($lang_code=='en')
        {
            $dsp_question_options_table = $wpdb->prefix . "dsp_question_options";
        }
        else
        {
            $dsp_question_options_table = $wpdb->prefix . "dsp_question_options_" . $lang_code;
        }

        $my_rows_options = $wpdb->get_results("SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order");

        return $my_rows_options;
    }

    public function ajax_new_search_filter()
    {
        $data = $_GET['data'];
        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;
        $values = array();
        $i      = 0;
        foreach ($data as $d){
            if ($d['name'] == 'profile_question_option_id[]'){
                $values[$d['name']][$i] = $d['value'];
                $i++;
            }else{
                $values[$d['name']] = $d['value'];
            }
        }
        if (empty($values['user_name'])){
            $response = $this->get_results_by_other_criteria($values, $page, false);
        }else{
            $response = $this->get_result_by_name($values, $page, false);
        }
        print_r($response);
        die;
    }

    public function save_search($request)
    {
        global $wpdb;
        $user_id               = get_current_user_id();
        $dsp_user_search_criteria_table = $wpdb->prefix . DSP_USER_SEARCH_CRITERIA_TABLE;
        $user_gender = dsp_get_user_profile_details($user_id)->gender;
        $search_type = 'new_advance_search';
        $online_only = (isset($request['online_only'])) ? $request['online_only'] : 'N';
        $with_pictures          = (isset($request['with_pictures'])) ? $request['with_pictures'] : '';
        $Profile_questions_option_ids  = (isset($request['profile_question_option_id[]'])) ? implode($request['profile_question_option_id[]']) : '';
        $wpdb->insert($dsp_user_search_criteria_table, array(
                'user_id'      =>  $user_id,
                'username'     =>  $request['user_name'],
                'user_gender'  =>  $request['gender'],
                'seeking_gender'=> $request['seeking_gender'],
                'age_from'      => $request['age_from'],
                'age_to'        => $request['age_to'],
                'country_id'    => $request['country_id'],
                'city_id'       => $request['city_id'],
                'state_id'      => $request['state_id'],
                'online_only'   => $online_only,
                'with_pictures' => $with_pictures,
                'Profile_questions_option_ids' => $Profile_questions_option_ids,
                'search_name'   => $request['search_name'],
                'search_type'   => $search_type,
            )
        );

    }
}
 
