<?php
/*
=================================================
This is a Class which shows the Search Form on the Plugin
=================================================
*/

if (!class_exists('wpdating_search_form')) {
  
  class wpdating_search_form {
    private $_db;
      public static $searchFormOption = '';

    public function __construct($uid) {
      global $wpdb;
      $error = null;
        $this->_db = $wpdb;
        $this->setGeographySetting();

        $isDistanceModeOn = ($check_distance_mode->setting_status == 'Y') ? true : false;
        $searchFormSettings = (isset($check_search_from_option) && !empty($check_search_from_option)) ?
                        $check_search_from_option->setting_value : 
                        '';
    
    
    $userProfileDetailsExist = false;
    // get currently logged in user profile details
    if(!is_null($uid)){
        $userProfileDetails = apply_filters('dsp_get_profile_details',$uid); 
        $userProfileDetailsExist = $userProfileDetails != false  ? true :false;
    }


      $this->wpdating_search_form_fnc();
    }

    /**
      * search form setting  value
      *  ow = Old way Search form with list of country
      *  nw = New way Search form using google geography
      *  nn = Search form without Location 
      */
    public function class_name_search() {
      if( self::$searchFormOption == 'nn' ) {
        return 'dsp-md-2';
      }
      else if (self::$searchFormOption == 'nw') {
        return 'dsp-md-3';
      }
      else {
        return 'dsp-md-4';
      }
    }

    public function class_name_search1() {
      if( self::$searchFormOption == 'nn' ) {
        return 'dsp-md-3';
      }
      else if (self::$searchFormOption == 'nw') {
        return 'dsp-md-3';
      }
      else {
        return 'dsp-md-4';
      }
    }

    public function button_margin() {
      if( self::$searchFormOption == 'nn' ) {
        return 'dsp-md-3';
      }
      else if (self::$searchFormOption == 'nw') {
        echo 'margin-top:-13px';
      }
      else {
        return 'dsp-md-2';
      }
    }

    /**
       * This method is used set geography setting for
       * search form 
       *
       * @param public
       * @since version 4.8.5
       * @return String
       */

      public function setGeographySetting() {
            $dsp_general_settings_table = $this->_db->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $query = "SELECT `setting_value` FROM $dsp_general_settings_table WHERE setting_name =  %s ";
        // check setting for search form in home page
            // 
      self::$searchFormOption = $this->_db->get_var($this->_db->prepare($query,'search_form_options'));

    }

      /**
       * This method is used to display
       * search form 
       *
       * @param public
       * @since version 4.8.5
       * @return String
       */
      public function dsp_display_search_form_setting_func() {
        if( self::$searchFormOption == 'nn' )
          return false;

          return self::$searchFormOption == 'nw' ? $this->dsp_get_new_country_field() : $this->dsp_old_country_field();

          
      }


      /**
       * This method is used display for distance mode setting for
       * placeholder
       *
       * @param public
       * @since version 4.8.5
       * @return String
       */
      public function dsp_distance_mode_setting() {
            $dsp_general_settings_table = $this->_db->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $query = "SELECT `setting_status` FROM $dsp_general_settings_table WHERE setting_name =  %s ";
        // check setting for search form in home page
      $distanceMode = $this->_db->get_var($this->_db->prepare($query,'distance_feature'));
      return $distanceMode == 'Y' ? true : false;
      }


      /**
       * This method is used to get google geography api integrated 
       * country field for search form 
       *
       * @param public
       * @since version 4.8.5
       * @return String
       */
      
      public function dsp_get_new_country_field() {
        $options = array(
                3959 => __('Miles', 'wpdating'),
                6371 => __('Km', 'wpdating')
            );
            $content = '';
            $isDistanceModeOn = $this->dsp_distance_mode_setting();
            $placeholder = $isDistanceModeOn ? __('PlaceipcodeCountry', 'wpdating') : __('Country:', 'wpdating'); 
        if($isDistanceModeOn) {
          $content .= '<div class="'. $this->class_name_search() .'" >
                          <label>' . __('Distance', 'wpdating') . '</label>';
                $content .= '<input name="distance" type="text" class="dspdp-form-control dsp-form-control" /></div>';
                $content .= '<div class="'. $this->class_name_search() .'">
                          <label">' . __('Unit', 'wpdating') . '</label>
                            <select name="unit" class="country dsp-selectbox">
                                <option value="0">' . __('Select Unit', 'wpdating'). '</option>';
                foreach ($options as $key => $option) {
                    $content .= '<option value="'. $key . '" >'.$option . '</option>';
        } 
              $content .= '</select>
                      </div>';
            }
            
            $content .= '<div class="'. $this->class_name_search() .'">
                        <label>' . __('Country:', 'wpdating') . '</label>';
            $content .= '<input id="autocomplete" name="zip_code" type="text" class="dspdp-form-control dsp-form-control"  placeholder="' . $placeholder  . '" />';
                 
            $content .= '</div>
                    <div class="'. $this->class_name_search() .'">
                        <input  name="lat" id="lat"  type="hidden" value="" >
                        <input  name="lng" id="lng"  type="hidden" value="" >
                        <input  name="cmbCountry" id="country"  type="hidden" value="">
                    </div>';
            return $content;
      }


      /**
       * This method is used to get default country list for
       * search form 
       *
       * @param public
       * @since version 4.8.5
       * @return String
       */
      public function dsp_old_country_field() {
        $dsp_country_table = $this->_db->prefix . DSP_COUNTRY_TABLE;
        $countries = $this->_db->get_results("SELECT * FROM $dsp_country_table Order by name");
        $defaultCountry = dsp_get_default_country(); 
        $content = sprintf("<div class='%s' style='padding:0px;'>
                    <label>%s</label>
                                        <select name='cmbCountry' class='country dsp-selectbox'>", $this->class_name_search(), __('Country','wpdating'
                               ));                                                              
            foreach ($countries as $country) {
                $selected = ($country->country_id == $defaultCountry) ? 'selected = "selected"' : '';
            $content .= sprintf("<option value='%s'  %s > %s </option>", $country->country_id,$selected, $country->name);
             } 
            $content .= '</select></div>';
            return $content;
      }



    public function wpdating_search_form_fnc() {
      include_once(WP_DSP_ABSPATH . "include_dsp_tables.php");
      $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
      $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
      $pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_option('siteurl') . '/', str_replace('\\', '/', dirname(__FILE__))) . '/';  // Plugin Path

      $check_couples_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'");

      //For min and max age
			$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
			$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
			$min_age_value = $check_min_age->setting_value;
			$max_age_value = $check_max_age->setting_value;

      if (is_user_logged_in() ) { ?>
          <form name="frmquicksearch" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK .'search/search_result/basic_search/basic_search' ?>">
          <input type="hidden" name="pid" value="5" />
          <input type="hidden" name="pagetitle" value="search_result" />
        <?php } else { ?>
          <form name="frmquicksearch" id="frmquicksearch" method="GET" action="<?php echo ROOT_LINK . 'g_search_result/' ?>">
        <?php } ?>      
        
        <input type="hidden" name="Pictues_only" value="P" />
        <div class="dsp-filter-container">
          <div class="dsp-join-searchbox">
          <div class="container">
          <div class="dsp-row wp_dating_search_form">

          <div class="dsp-lg-2 dsp-md-2">
            <h4>Find my matches</h4>
                    </div>

                    <div class="dsp-md-10">
                      <div class="dsp-row">
                        <div class="<?php echo $this->class_name_search1(); ?>">
                <label> <?php echo __('I am:', 'wpdating'); ?></label>
                <select name="gender" class="gender dsp-selectbox">
                                <?php echo get_gender_list('M'); ?>
                            </select>
              </div>

              <?php
              var_dump($userProfileDetailsExist);
              $seeking = $userProfileDetailsExist ? $userProfileDetails->seeking : 'F';
              $genderList = get_gender_list($seeking);
              if (!empty($genderList)){ ?>
                <div class="<?php echo $this->class_name_search1(); ?>">
                  <label><?php echo __('Seeking a:', 'wpdating'); ?></label>
                  <select name="seeking"  class="gender dsp-selectbox">
                    <?php echo $genderList; ?>
                  </select>
                </div>
              <?php } ?>

              <div class="<?php  echo $this->class_name_search(); ?>">
                <label><?php echo __('Age:', 'wpdating'); ?></span></label>
                <select name="age_from"   class="gender dsp-selectbox">
                  <!-- <?php
                    for ($fromyear = 18; $fromyear <= 99; $fromyear++) {
                      if ($fromyear == 18) { 
                  ?>
                  <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                    <?php } else { ?>
                  <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                  <?php } 
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
                </select>
              </div>

              <div class="<?php echo $this->class_name_search(); ?>">
                <label><?php echo __('to:', 'wpdating'); ?></label>
                <select name="age_to"  class="gender dsp-selectbox">
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
                </select>
              </div>

              <?php if($isDistanceModeOn) { ?>
              <div class="<?php echo $this->class_name_search(); ?>">
                <label><?php echo __('Distance', 'wpdating'); ?></label>
                <select name="unit" class="gender dsp-selectbox">
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
              </div>
              <?php } ?>

              <?php echo $this->dsp_display_search_form_setting_func(); ?>

              <div class="<?php echo $this->class_name_search(); ?>">
                <input name="submit" type="submit" class="dsp_submit_button dsp-submit" value="<?php echo __('Search', 'wpdating'); ?>" style="<?php $this->button_margin() ?>;"/>
              </div>

                      </div>
                    </div>
                    </div><!--End of Container-->
          </div><!--End of SearchBox-->
          </div>
        </div>

        </form>
        <script type="text/javascript">
        function autoSubmitForm() {
          document.frmquicksearch.submit();
        }
        dsp = jQuery.noConflict();
        </script>  
      <?php }
  
  }

  //new wpdating_search_form();
}

?>