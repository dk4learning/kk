<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
include_once( ABSPATH . '/wp-admin/includes/plugin.php' );
//build menus
if ( is_admin() ) {
    add_action( 'admin_menu', 'dsp_add_pages' );
}


/**
 * Function to Create Menu and submenu in Admin
 * action function for above hook
 */
if ( ! function_exists( 'dsp_add_pages' ) ) {
    function dsp_add_pages() {
        $optionpage_top_level = "DSP Admin";
        // userlevel=8 restrict users to "Administrators" only
        // Add a new submenu under Options:
        // add_options_page('Test Options', 'Test Options', 'administrator', 'testoptions', 'dsp_options_page');
        // Add a new top-level menu (ill-advised): add_menu_page(page_title, menu_title, capability, handle, [function], [icon_url])
        add_menu_page( $optionpage_top_level, $optionpage_top_level, 'administrator', 'dsp-admin-sub-page1', 'dsp_settings_page' );
        $adminMenuValues = array(
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Settings',
                'menu_title' => __( 'Settings', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page1',
                'func'       => 'dsp_settings_page'
            ),
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Media',
                'menu_title' => __( 'Media', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page2',
                'func'       => 'dsp_media_page'
            ),
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Tools',
                'menu_title' => __( 'Tools', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page3',
                'func'       => 'dsp_tools_page'
            ),
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Reports',
                'menu_title' => __( 'Reports', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page4',
                'func'       => 'dsp_reports_page'
            ),
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Discount Codes',
                'menu_title' => __( 'Discount Codes', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page5',
                'func'       => 'dsp_discount_page'
            ),
            array(
                'parent'     => 'dsp-admin-sub-page1',
                'page_title' => 'DSP Marketing',
                'menu_title' => __( 'Marketing', 'wpdating' ),
                'capability' => 'administrator',
                'handle'     => 'dsp-admin-sub-page6',
                'func'       => 'dsp_marketing_page'
            ),


        );
        // filter for adding menus in DSP admin section
        $adminMenuValues = apply_filters( 'dsp_add_submenu', $adminMenuValues );
        //dsp_debug($adminMenuValues);die;
        foreach ( $adminMenuValues as $options ) {
            // Add a submenu to the custom top-level menu: add_submenu_page(parent, page_title, menu_title, capability required, file/handle, [function])
            add_submenu_page( $options['parent'], $options['page_title'], $options['menu_title'], $options['capability'], $options['handle'], $options['func'] );
        }
    }
}


/**
 * Function to displays the page content for the Settings submenu
 * of the DSP ADMIN Toplevel menu
 */

if ( ! function_exists( 'dsp_settings_page' ) ) {
    function dsp_settings_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_settings_header.php' );
    }
}


/**
 * Function to displays the page content for the Media submenu
 */

if ( ! function_exists( 'dsp_media_page' ) ) {
    function dsp_media_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_media_header.php' );
    }
}

/**
 * Function to displays the page content for the Tools submenu
 */

if ( ! function_exists( 'dsp_tools_page' ) ) {
    function dsp_tools_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_tools_header.php' );
    }
}

/**
 * Function to displays the page content for the custom Test Toplevel menu
 */

if ( ! function_exists( 'dsp_home_page' ) ) {
    function dsp_home_page() {
        echo "<h2>DSP Admin</h2>";
    }
}

/**
 * Function to displays the page content for the Media submenu
 */

if ( ! function_exists( 'dsp_discount_page' ) ) {
    function dsp_discount_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_discount_header.php' );
    }
}

/**
 * Function to displays the page content for the Report submenu
 */

if ( ! function_exists( 'dsp_reports_page' ) ) {
    function dsp_reports_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_reports_header.php' );
    }
}

/**
 * Function to displays the page content for the Media submenu
 */

if ( ! function_exists( 'dsp_marketing_page' ) ) {
    function dsp_marketing_page() {
        include_once( WP_DSP_ABSPATH . 'files/includes/dsp_marketing_header.php' );
    }
}

/**
 * Function to enqueue the styles
 */

if ( ! function_exists( 'plugin_admin_head_css' ) ) {
    function plugin_admin_head_css() {
        wp_enqueue_style( 'thickbox' ); // call to media files in wp
        wp_enqueue_style( 'dsp_style', plugins_url( 'dsp_dating/css/dsp_styles.css' ) );
        wp_enqueue_style( 'jquery-ui-dialog', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js' );
        wp_enqueue_style( 'jquery-easy-select-css', WPDATE_URL . 'css/chosen.min.css', array(), PLUGIN_VERSION );
    }
}

add_action( 'admin_head', 'plugin_admin_head_css' );

/**
 * Function to enqueue the scripts
 */

if ( ! function_exists( 'plugin_admin_head_js' ) ) {
    function plugin_admin_head_js() {
        if ( function_exists( 'wp_enqueue_media' ) ) {
            //call for new media manager
            wp_enqueue_media();
        }
        if ( is_admin() ) {
            wp_deregister_script( 'dsp_admin_script' );
            wp_enqueue_script( 'jquery-datepicker', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js' );
            wp_register_script( 'dsp_admin_script', WPDATE_URL . '/js/functions.js' );
            wp_enqueue_script( 'dsp_admin_script' );
            wp_enqueue_script( 'thickbox' );
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'google-jsapi', "https://www.google.com/jsapi" );
            wp_enqueue_script( 'custom-google', WPDATE_URL . '/js/pie_chart.js' );
            wp_enqueue_script( 'jquery-easy-select-js', WPDATE_URL . '/js/chosen.jquery.min.js', array(), PLUGIN_VERSION );
           
        }
    }
}
add_action( 'admin_head', 'plugin_admin_head_js' );


/**
 * This function is used for debugs purpose using var_dump core php function
 *
 * @param accepts any data into formatted ways
 */

if ( ! function_exists( 'dsp_debug' ) ) {
    function dsp_debug( $data ) {
        echo "<pre>";
        var_dump( ( $data ) );
    }
}

/**
 * This function is used for debugs purpose using print_r core php function
 *
 * @param accepts any data into formatted ways
 */

if ( ! function_exists( 'dsp_pr' ) ) {
    function dsp_pr( $data ) {
        echo "<pre>";
        print_r( ( $data ) );
    }
}

/**
 * This function is used for writing error logs into wordpress error_log files
 *
 * @param [String] [$log] [It accepts the error texts ]
 */

if ( ! function_exists( 'dsp_write_log' ) ) {
    function dsp_write_log( $log ) {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}


/**
 * -------------------------Created Dsp Browse Widget--------------------------------------------------
 */

/**
 * This function is used for Browse widget form
 */

if ( ! function_exists( 'dspBrowsewidgetform' ) ) {
    function dspBrowsewidgetform() {
        include_once( WP_DSP_ABSPATH . 'wp_dsp_browse.php' );
    }
}

/**
 * This function is used for Browse widget form
 */

if ( ! function_exists( 'widget_dspbrowse' ) ) {
    function widget_dspbrowse( $args ) {
        extract( $args );
        echo $before_widget;
        echo $before_title;
        echo $after_title;
        dspBrowsewidgetform();
        echo $after_widget;
    }
}

/**
 * This function is used for DSP Browse widget control
 */

if ( ! function_exists( 'widget_dspbrowse_control' ) ) {
    function widget_dspbrowse_control() {
        $options = get_option( "widget_dspBrowse" );
        if ( ! is_array( $options ) ) {
            $options = array(
                'gender' => '',
                'age'    => ''
            );
        }
        global $wpdb;
        if ( isset( $_REQUEST['sideFeature-Submit'] ) ) {
            extract( $_REQUEST );
            $gender            = implode( ',', $sideFeature_gender );
            $age               = implode( ',', $sideFeature_age );
            $options['gender'] = $gender;
            $options['age']    = $age;
        }
        update_option( "widget_dspBrowse", $options );
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_gender_list            = $wpdb->prefix . DSP_GENDER_LIST_TABLE;
        $check_couples_mode         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );
        $query                      = "select * from $dsp_gender_list ";
        if ( $check_couples_mode->setting_status == 'N' ) {
            $query .= " where enum!='C' ";
        }
        $gender_list = $wpdb->get_results( $query );
        ?><p>
        <label
                for="sideFeature-WidgetTitle"><?php echo __( 'Pick what you want to show:','wpdating' ) ?> </label><br/><br/>
        <strong class="wid-gender-title"><?php echo __( 'Gender:','wpdating' ) ?></strong><br/>
        <?php
        $selected_gender = explode( ',', $options['gender'] );
        foreach ( $gender_list as $gender_row ) {
            if ( $gender_row->editable == 'N' ) {
                if ( in_array( $gender_row->enum, $selected_gender ) ) {
                    echo '<span class="wid_dsp_gender">' . __( $gender_row->gender,'wpdating' ) . '</span><input type="checkbox" name="sideFeature_gender[]" checked="checked" value="' . $gender_row->enum . '"><br>';
                } else {
                    echo '<span class="wid_dsp_gender">' . __( $gender_row->gender,'wpdating' ) . '</span><input type="checkbox" name="sideFeature_gender[]" value="' . $gender_row->enum . '"><br>';
                }
            } else {
                if ( in_array( $gender_row->enum, $selected_gender ) ) {
                    echo '<span class="wid_dsp_gender">' . $gender_row->gender . '</span><input type="checkbox" checked="checked" name="sideFeature_gender[]" value="' . $gender_row->enum . '"><br>';
                } else {
                    echo '<span class="wid_dsp_gender">' . $gender_row->gender . '</span><input type="checkbox" name="sideFeature_gender[]" value="' . $gender_row->enum . '"><br>';
                }
            }
        }
        $selected_age = explode( ',', $options['age'] );
        ?>
        <br/>
        <strong class="wid-age-title"><?php echo __( 'Age:','wpdating' ) ?></strong><br/>
        <span class="wid_dsp_age">18 <?php echo __('to:','wpdating') ?> 21</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="18-21" <?php if ( in_array( '18-21', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">22 <?php echo __('to:','wpdating') ?> 25</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="22-25" <?php if ( in_array( '22-25', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">26 <?php echo __('to:','wpdating') ?> 30</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="26-30" <?php if ( in_array( '26-30', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">31 <?php echo __('to:','wpdating') ?> 35</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="31-35" <?php if ( in_array( '31-35', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">36 <?php echo __('to:','wpdating') ?> 40</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="36-40" <?php if ( in_array( '36-40', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">41 <?php echo __('to:','wpdating') ?> 45</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="41-45" <?php if ( in_array( '41-45', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">46 <?php echo __('to:','wpdating') ?> 50</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="46-50" <?php if ( in_array( '46-50', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">51 <?php echo __('to:','wpdating') ?> 55</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="51-55" <?php if ( in_array( '51-55', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">56 <?php echo __('to:','wpdating') ?> 60</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="56-60" <?php if ( in_array( '56-60', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">61 <?php echo __('to:','wpdating') ?> 65</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="61-65" <?php if ( in_array( '61-65', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">66 <?php echo __('to:','wpdating') ?> 70</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="66-70" <?php if ( in_array( '66-70', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">71 <?php echo __('to:','wpdating') ?> 75</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="71-75" <?php if ( in_array( '71-75', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">76 <?php echo __('to:','wpdating') ?> 80</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="76-80" <?php if ( in_array( '76-80', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">81 <?php echo __('to:','wpdating') ?> 85</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="81-85" <?php if ( in_array( '81-85', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <span class="wid_dsp_age">86 <?php echo __('to:','wpdating') ?> 90</span><input type="checkbox"
                                                                                             name="sideFeature_age[]"
                                                                                             value="86-90" <?php if ( in_array( '86-90', $selected_age ) ) {
            echo 'checked="checked"';
        } ?>><br>
        <input type="hidden" id="sideFeature-Submit" name="sideFeature-Submit" value="1"/>
        </p>
        <?php
    }
}

/**
 * This function is used for intializing DSP Browse widget during plugin loaded
 */

if ( ! function_exists( 'dspbrowse_init' ) ) {
    function dspbrowse_init() {
        wp_register_sidebar_widget(
            'dsp_browse_widget', // your unique widget id
            'DSP Browse', // widget name
            'widget_dspbrowse', // callback function
            array(// options
                'description' => 'A DSP Browse widget that displays dsp dating quick Search according to gender and age.'
            )
        );
        wp_register_widget_control(
            'dsp_browse_widget', // your unique widget id
            'DSP Browse', // widget name
            'widget_dspbrowse_control', // callback function
            array(// options
                'description' => 'A DSP Browse widget that displays dsp dating quick Search according to gender and age.'
            )
        );
    }
}

add_action( "plugins_loaded", "dspbrowse_init" );

/**
 * -------------------------Created Quick Search Widget--------------------------------------------------
 */

/**
 * -------------------------Created Quick Search Widget--------------------------------------------------
 */

/**
 * This function is used  to generate Quick search widget
 */

if ( ! function_exists( 'quickSearchwidgetform' ) ) {
    function quickSearchwidgetform() {
        include_once( WP_DSP_ABSPATH . 'wp_search_wiget_form.php' );
    }
}

/**
 * This function is used  to handle shortcode for Quick search widget
 */

if ( ! function_exists( 'widget_dspquicksearch' ) ) {
    function widget_dspquicksearch( $args ) {
        extract( $args );
        echo $before_widget;
        echo $before_title;
        echo __( 'Quick search','wpdating' );
        echo $after_title;
        quickSearchwidgetform();
        echo $after_widget;
    }
}

/**
 * This function is used  to intialize Quick search widget
 */

if ( ! function_exists( 'dspquicksearch_init' ) ) {
    function dspquicksearch_init() {
        wp_register_sidebar_widget(
            'dsp_quick_search_widget', // your unique widget id
            'Quick Search Widget', // widget name
            'widget_dspquicksearch', // callback function
            array(// options
                'description' => 'A Quick Search widget that displays dsp dating quick Search form.'
            )
        );
    }
}

add_action( "plugins_loaded", "dspquicksearch_init" );

/**
 * -------------------------Created Quick Search Widget--------------------------------------------------
 */

/**
 * -------------------------Created Quick Chat Widget--------------------------------------------------
 */

/**
 * This function is used  to include  Quick Chat widget
 */

if ( ! function_exists( 'chatwidget' ) ) {
    function chatwidget() {
        include_once( WP_DSP_ABSPATH . 'wp_chat_wiget.php' );
    }
}

/**
 * This function is used  to handle shortcode for  Quick Chat widget
 */

if ( ! function_exists( 'widget_dspchat' ) ) {
    function widget_dspchat( $args ) {
        extract( $args );
        echo '<div class="dsp_chat_widget dspdp-spacer-hg dspdp-plugin">';
        echo $before_widget;
        echo $before_title;
        echo __( 'Chat','wpdating' );
        echo $after_title;
        chatwidget();
        echo $after_widget;
        echo '</div>';
    }
}

/**
 * This function is used  to intialize  Quick Chat widget
 */

if ( ! function_exists( 'dspchat_init' ) ) {
    function dspchat_init() {
        wp_register_sidebar_widget(
            'dsp_chat_widget', // your unique widget id
            'Chat Widget', // widget name
            'widget_dspchat', // callback function
            array(// options
                'description' => __( 'A chat widget.','wpdating' )
            )
        );
    }
}


add_action( "plugins_loaded", "dspchat_init", 12 );

/**
 * -------------------------Created Quick Search Widget--------------------------------------------------
 */

/**
 * -------------------------Created Online Member Widget--------------------------------------------------
 */

/**
 * This function is used  to include  Online Member file
 */

if ( ! function_exists( 'quickonlinemembersform' ) ) {
    function quickonlinemembersform() {
        include_once( WP_DSP_ABSPATH . 'wp_online_members.php' );
    }
}


/**
 * This function is used  to include  Online Member widget
 */

if ( ! function_exists( 'widget_dsponlinemember' ) ) {
    function widget_dsponlinemember( $args ) {
        extract( $args );
        echo $before_widget;
        echo $before_title;
        echo __( 'Online','wpdating' );
        echo $after_title;
        quickonlinemembersform();
        echo $after_widget;
    }
}

/**
 * This function is used  to intialize  Online Member widget
 */

if ( ! function_exists( 'dsponlinemebers_init' ) ) {
    function dsponlinemebers_init() {
        wp_register_sidebar_widget(
            'dsp_online_members_widget', // your unique widget id
            'Online Members Widget', // widget name
            'widget_dsponlinemember', // callback function
            array(// options
                'description' => __( 'DSP_ONLINE_MEMBER_WIDGET_DESCRIPTION','wpdating' )
            )
        );
    }
}

add_action( "plugins_loaded", "dsponlinemebers_init", 12 );

/*
*  This function is used to cache all the language data for 30 days using transient method wordpress
*
*/

if ( ! function_exists( 'dsp_cache_all_language_data' ) ) {
    function dsp_cache_all_language_data() {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $tableNames                = $wpdb->get_results( "SELECT `language_id`,`table_name` FROM  $dsp_language_detail_table" );

        $languageCodes = array();
        $data          = array();
        $counter       = 0;
        if ( ! empty( $tableNames ) ) {
            foreach ( $tableNames as $key => $value ) {
                $tableName   = $wpdb->prefix . trim( $value->table_name, "'" );
                $language_id = $value->language_id;
                $results     = $wpdb->get_results( "SELECT `code_name`,`text_name` FROM $tableName" );
                foreach ( $results as $key => $value ) {
                    $codeName = $value->code_name;
                    $textName = $value->text_name;
                    set_transient( $codeName . "_" . $language_id, $textName, 60 * 60 * 24 * 30 );

                }

            }

        }
    }
}


/**
 * This function is used to get value of codename passed
 *
 * @param   [string] [$code] [It takes code name as string]
 * @param   [Integer] [$langId] [It takes id of language]
 * @param   [Boolean] [$poEntry]
 *
 * @return  [translated text according to code and language]
 */

if ( ! function_exists( 'dsp_get_translated_text' ) ) {
    function dsp_get_translated_text( $code, $langId, $poEntry = false ) {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        if ( empty( $langId ) || $langId == null ) {
            $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where display_status='1'" );
        } else {
            $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where language_id='" . $langId . "' " );
        }
        $language_name  = $language_info->language_name;
        $tableName      = $language_info->table_name;
        $DSP_TABLE_NAME = $wpdb->prefix . $tableName;

        $dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
        $text_name          = $wpdb->get_var( $wpdb->prepare( "SELECT text_name FROM $DSP_TABLE_NAME WHERE code_name = '%s'", $code ) );
        $poEntry ? Sepia\PoParserUsed::updatePo( $code, $text_name ) : '';

        return $text_name;
    }
}

/**
 *  This function is used to get language name based on id
 *
 * @param [Integer]
 *
 * @return  [Array]
 */

if ( ! function_exists( 'dsp_get_po_data' ) ) {
    function dsp_get_po_data( $langId ) {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        if ( ! isset( $_SESSION[ 'id_' . $langId ]['language_name'] ) && empty( $_SESSION[ 'id_' . $langId ]['language_name'] ) ) {
            $languageName                                 = $wpdb->get_var( $wpdb->prepare( "SELECT `language_name` FROM $dsp_language_detail_table where language_id='%d'", $langId ) );
            $languageName                                 = strtolower( trim( esc_sql( $languageName ) ) );
            $_SESSION[ 'id_' . $langId ]['language_name'] = $languageName;
        }
        $filePath = PO_PATH . $_SESSION[ 'id_' . $langId ]['language_name'] . '/' . $langId . '.po';
        Sepia\PoParserUsed::setBasicValues( $_SESSION[ 'id_' . $langId ]['language_name'], $filePath );
        $poData = array(
            'file_path'     => $filePath,
            'language_name' => $_SESSION[ 'id_' . $langId ]['language_name']
        );

        return $poData;
    }
}

/**
 *  This function is used to get all the data from dsp_language_table by using
 *  respective language id
 *
 * @param [Integer] [$langId] [It takes id of language]
 *
 * @return  [Array]
 */

if ( ! function_exists( 'dsp_get_language_data_by_id' ) ) {
    function dsp_get_language_data_by_id( $langId ) {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $text_name                 = '';
        if ( empty( $langId ) || $langId == null ) {
            $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where display_status='1'" );
        } else {
            $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where language_id='" . $langId . "' " );
        }

        $language_name = $language_info->language_name;
        $tableName     = $language_info->table_name;

        $DSP_TABLE_NAME     = $wpdb->prefix . $tableName;
        $dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
        $languageData       = $wpdb->get_results( "SELECT `code_name`  msgid,`text_name` msgstr FROM $DSP_TABLE_NAME ", ARRAY_A );

        return $languageData;

    }
}

/**
 *  This function is used to get po header
 * @return  [String]
 */

if ( ! function_exists( 'dsp_get_po_header' ) ) {
    function dsp_get_po_header( $languageName ) {
        //$pluginVersion = PLUGIN_VERSION;
        return <<<EOH
# PLUGIN_VERSION language/translation.
#
# Source:  DSP DATING SOLUTION
#
# Copyright (c) 2013 The Open University.
# This file is distributed under the same license as the PACKAGE package.
# IET-OU <EMAIL@ADDRESS>, YEAR.
#
#, fuzzy
msgid ""
msgstr ""
"Project-Id-Version: PLUGIN_VERSION \\n"
"Report-Msgid-Bugs-To: iet-webmaster+@+open.ac.uk\\n"
"POT-Creation-Date: 2013-10-02 14:00+0100\\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\\n"
"Last-Translator: FULL NAME <EMAIL@ADDRESS>\\n"
"Language-Team: LANGUAGE <LL@li.org>\\n"
"Language: $languageName\\n"
"MIME-Version: 1.0\\n"
"Content-Type: text/plain; charset= utf-8\\n"
"Content-Transfer-Encoding: 8bit\\n"
"Plural-Forms: nplurals=INTEGER; plural=EXPRESSION;\\n"


EOH;
    }
}

/**
 * This function is used to generate po file for particular language
 *
 * @param [Integer]
 */
if ( ! function_exists( 'dsp_generate_po_file' ) ) {
    function dsp_generate_po_file( $langId, $languageName ) {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $dsp_language_table        = $wpdb->prefix . DSP_LANGUAGE_TABLE;
        if ( ! file_exists( ABSPATH . 'wp-content/uploads/po' ) ) {
            mkdir( ABSPATH . 'wp-content/uploads/po', 0755 ); // it will default to 0755 regardless
            chmod( ABSPATH . 'wp-content/uploads/po', 0777 );
        }
        $folderPath = PO_PATH . $languageName . '/';
        $filePath   = $folderPath . $langId . '.po';
        if ( ! file_exists( $folderPath ) ) {
            mkdir( $folderPath, 0777 );
            chmod( $folderPath, 0777 );
        }
        $languageData = dsp_get_language_data_by_id( $langId );
        $source       = 'DSP-Plugin';
        $output       = dsp_get_po_header( $languageName );

        foreach ( $languageData as $data ) {
            foreach ( $data as $key => $value ) {
                $output .= $key . '  "' . $value . '"' . "\n";
            }
        }
        $bytes = file_put_contents( $filePath, $output );
        if ( file_exists( $filePath ) ) {
            chmod( $filePath, 0777 );
        }

        return true;
    }
}

/**
 * This function is used to get po file and if msgid is not found then it updates tha po file
 *
 * @param [Array]
 * @param [String]
 * @param [Integer]
 *
 * @return [<String></String>]
 */
if ( ! function_exists( 'dsp_get_translate_text' ) ) {
    function dsp_get_translate_text( $poData, $code, $langId, $fromPo = true ) {
        $text_name = '';
        if ( $fromPo ) { // to get translated text from po
            //Sepia\PoParserUsed::setBasicValues($poData['language_name'] , $poData['file_path']);
            if ( false === ( $text_name = Sepia\PoParserUsed::get_translated_text( $code ) ) ) { // check for new code not exist in po file
                $text_name = dsp_get_translated_text( $code, $langId, true ); //entry new msgstr in po
            }

            return $text_name;
        }

        // Else get the translated text using transient
        $key = $code . '_' . $langId;
        if ( false === ( $text_name = get_transient( $key ) ) || empty( $text_name ) ) {
            $text_name = dsp_get_translated_text( $code, $langId );
            set_transient( $key, $text_name, 60 * 60 * 24 * 30 );
        }

        return $text_name;


    }
}


/**
 *  This function is used to translate text according
 *  to langage code passed as parameter
 *
 * @param [$code] [Takes language code]
 *
 * @return  [translated text according to code and language]
 */

if ( ! function_exists( 'language_code' ) ) {
    function language_code( $code ) {
        global $wpdb;

        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $use_po_file                = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'po_language'" );
        if ( $use_po_file->setting_status == 'Y' ) {
            $dsp_language_table = $wpdb->prefix . DSP_LANGUAGE_TABLE;
            $language           = $wpdb->get_var( "SELECT text_name FROM $dsp_language_table WHERE code_name='$code'" );

            return __( $language, 'wpdating' );
        }

        global $current_user;
        global $prevLangId;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $lang_id                    = 1; //default case where session is not set and not loggin
        $text_name                  = '';
        if ( isset( $_SESSION['default_lang'] ) ) {
            $lang_id = $_SESSION['default_lang']; //session is set
        } else {
            $adminLangId = $wpdb->get_var( $wpdb->prepare( "SELECT `language_id` FROM $dsp_language_detail_table where display_status = '%d'", 1 ) );
            if ( is_user_logged_in() ) {
                $userSessionLangId = $wpdb->get_var( "SELECT  `language_id` FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' " );
                $lang_id           = isset( $userSessionLangId ) && ! empty( $userSessionLangId ) ? $userSessionLangId : $adminLangId; //logged  in and  session is not set
            } else {
                $lang_id = $adminLangId;
            }
            $_SESSION['default_lang'] = $lang_id;
        }

        $poData = dsp_get_po_data( $lang_id ); // get po file path and language name

        if ( file_exists( $poData['file_path'] ) ) { //check for po file exist
            // var_dump($lang_id);
            return dsp_get_translate_text( $poData, $code, $lang_id );
        } else {
            $generator = dsp_generate_po_file( $lang_id, $poData['language_name'] ); //generate whole language data po file with respective [folder name : language name] [file name : languageId.po]

            if ( $generator && file_exists( $poData['file_path'] ) ) {
                return dsp_get_translate_text( $poData, $code, $lang_id );
            } else {
                return dsp_get_translate_text( $poData, $code, $lang_id, false ); // use transient or language table to get tranlated text
            }
        }

    }
}


/**
 * -------------------------Created Language Function--------------------------------------------------
 */


/**
 *  This function is used to check status wheather or not user blacklisted
 */

if ( ! function_exists( 'checkstatus' ) ) {
    function checkstatus() {
        global $wpdb;
        $current_user = wp_get_current_user();
        $ip           = $_SERVER['REMOTE_ADDR'];
        $row          = $wpdb->get_var( "SELECT ip_status FROM " . $wpdb->prefix . "dsp_blacklist_members WHERE ip_address = '" . $ip . "' LIMIT 0,1" );
        if ( $row == 1 ) {
            wp_redirect( get_option( 'siteurl' ) . '/wp-login.php?disabled=true' );
            wp_logout();
            $setMessage = 1;
        }
    }
}
add_action( 'init', 'checkstatus' );

/**
 *  This function is used to display_message
 */

if ( ! function_exists( 'display_message' ) ) {
    function display_message() {
        if ( isset( $_GET['disabled'] ) ) {
            $message = '<div id="login_error">  <strong>ERROR</strong>: Admin disabled your account.<br></div>';

            return $message;
        }
    }
}

add_filter( 'login_message', 'display_message' );


/**
 *  create the shortcode [include] that accepts a filepath and query string
 *  this function was modified from a post on www.amberpanther.com you can find it at the link below:
 *  http://www.amberpanther.com/knowledge-base/using-the-wordpress-shortcode-api-to-include-an-external-file-in-the-post-content/
 *  BEGIN amberpanther.com code
 *  END amberpanther.com code
 *  shortcode with sample query string:
 *  [include filepath="profile_header.php"]
 */


if ( ! function_exists( 'include_file' ) ) {
    function include_file( $atts ) {
        // echo "<pre>";print_r($atts);die;
        //if filepath was specified
        $filepath = shortcode_atts(
            array(
                'filepath' => 'NULL'
            ), $atts, 'include'
        );
        if( $filepath != '' ) {
            $filepath = $filepath[ 'filepath' ];
        } else {
            return;
        }

        //BEGIN modified portion of code to accept query strings
        //check for query string of variables after file path
        if( empty( $filepath ) ) {
            return;
        }
        if ( strpos( $filepath, "?" ) ) {

            $query_string_pos = strpos( $filepath, "?" );

            //create global variable for query string so we can access it in our included files if we need it
            //also parse it out from the clean file name which we will store in a new variable for including

            global $query_string;

            $query_string = substr( $filepath, $query_string_pos + 1 );

            $clean_file_path = substr( $filepath, 0, $query_string_pos );

            //if there isn't a query string
        } else {

            $clean_file_path = $filepath;
        }

        global $currentTemplatePath;
        $currentTemplatePath = $clean_file_path;
        /*
        * check if love-match is used
        * If love-match is active then please pull the code froom love-match/inc/dsp_dating/
        */
        $current_theme = get_option( 'template' );
        if( $current_theme == 'love-match' || $current_theme == 'dating-club' ) {
            ob_start();
            include_once( $clean_file_path );
            $content = ob_get_clean();

            //return the $content
            //return is important for the output to appear at the correct position
            //in the content

            return $content;
        } else {
            //END modified portion of code
            //check if the filepath was specified and if the file exists

            if ( $filepath != 'NULL' && file_exists( WP_DSP_ABSPATH . "/" . $clean_file_path ) ) {

                //turn on output buffering to capture script output

                ob_start();

                //include the specified file

                include_once( WP_DSP_ABSPATH . $clean_file_path );

                //assign the file output to $content variable and clean buffer

                $content = ob_get_clean();

                //return the $content
                //return is important for the output to appear at the correct position
                //in the content

                return $content;
            }
        }
    }
}


//register the Shortcode handler

add_shortcode( 'include', 'include_file' );


/**
 *  Add new news feed into database
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [type] [Feed type]
 * @param   [feed_type_id] [Feed type id]
 */

if ( ! function_exists( 'dsp_add_news_feed' ) ) {
    function dsp_add_news_feed( $user_id, $type, $feed_type_id = null ) {
        global $wpdb;
        $dsp_news_feed_table = $wpdb->prefix . DSP_NEWS_FEED_TABLE;
        $values              = array(
            'user_id'       => $user_id,
            'feed_type'     => $type,
            'datetime'      => date( 'Y-m-d H:i:s' ),
            'feed_type_id'  => $feed_type_id
        );
        $format              = array( '%d', '%s', '%s', '%d' );
        $wpdb->insert( $dsp_news_feed_table, $values, $format );
    }
}


/**
 *  Delete news feed in database
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [type] [Feed type]
 */

if ( ! function_exists( 'dsp_delete_news_feed' ) ) {
    function dsp_delete_news_feed( $user_id, $type, $feed_type_id = null ) {
        global $wpdb;
        $dsp_news_feed_table = $wpdb->prefix . DSP_NEWS_FEED_TABLE;
        if ('profile_photo' == $type && 'status' == $type){
            $where              = array(
                'user_id'       => $user_id,
                'feed_type'     => $type,
            );
        }else{
            $where              = array(
                'user_id'       => $user_id,
                'feed_type'     => $type,
                'feed_type_id'  => $feed_type_id
            );
        }
        $wpdb->delete( $dsp_news_feed_table, $where);
    }
}


/**
 *  This function is used to add notification into database
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [member_id] [Notification send user id]
 * @param   [type] [Notification type]
 */

if ( ! function_exists( 'dsp_add_notification' ) ) {
    function dsp_add_notification( $user_id, $member_id, $type ) {
        global $wpdb;
        $dsp_notification           = $wpdb->prefix . DSP_NOTIFICATION_TABLE;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $check_notification_mode    = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'notification'" );
        if ( $check_notification_mode->setting_status == 'Y' ) {
            if ( $user_id > 0 ) {
                $values = array(
                    'user_id'   => $user_id,
                    'member_id' => $member_id,
                    'type'      => $type,
                    'datetime'  => date( 'Y-m-d H:i:s' ),
                    'status'    => 'Y',
                );
                $format = array( '%d', '%d', '%s', '%s', '%s' );
                $wpdb->insert( $dsp_notification, $values, $format );
            }
        }
    }
}

/**
 *  This function is used to destroy session
 *
 * @param   [user_id] [currently loggen user id]
 */

if ( ! function_exists( 'dsp_logout_session_destroy' ) ) {
    function dsp_logout_session_destroy( $user_id = null ) {
        global $wpdb;
        $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $wpdb->query( "DELETE FROM $dsp_online_table  WHERE `user_id` = '$user_id'" );

    }
}

/**
 *  This function is used to check wheather or not user_profile edited
 *
 * @param   [user_id] [currently loggen user id]
 */

if ( ! function_exists( 'dsp_is_user_profile_edited' ) ) {
    function dsp_is_user_profile_edited( $user_id = null ) {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $edited            = $wpdb->query( $wpdb->prepare( "SELECT `user_id` FROM $dsp_user_profiles  WHERE `user_id` = '%d' AND country_id > 0 ", array( $user_id ) ) );

        return ( $edited > 0 ) ? true : false;
    }
}

/**
 *  This function is used to saved session
 *
 * @param   [user_id] [currently loggen user id]
 * @param   [session_id] [ session id ]
 */

if ( ! function_exists( 'dsp_login_session_saved' ) ) {
    function dsp_login_session_saved( $user_id, $session_id ) {
        global $wpdb;
        $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $time             = time();
        $time_check       = $time - 600; //SET TIME 10 Minute 600
        $exist_users      = $wpdb->query( $wpdb->prepare( "SELECT 'user_id' FROM $dsp_online_table  WHERE `user_id` = '%d'", $user_id ) );
        $isEdited         = dsp_is_user_profile_edited( $user_id );
        if ( $user_id != "" && $user_id != 0 ) {
            $status = 'Y';
        } else {
            $status = 'N';
        }
        if ( $isEdited ) {
            if ( $exist_users == "0" ) {
                $wpdb->query( "INSERT INTO $dsp_online_table (session,user_id, status,time)VALUES('$session_id','$user_id','$status','$time')" );
            } else {
                $wpdb->query( "UPDATE $dsp_online_table  SET time='$time',user_id='$user_id',status='$status' WHERE `user_id` = '$user_id'" );
            }

            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists( 'dsp_login_user' ) ) {
    function dsp_login_user( $login ) {
        $session_id = session_id();
    }
}

/**
 *  This function is used to saved current user position
 *
 * @param   [user_id] [currently loggen user id]
 */

if ( ! function_exists( 'dsp_save_current_user_position' ) ) {
    function dsp_save_current_user_position( $user_id ) {
        global $wpdb;
        $user_id           = array( 'user_id' => $user_id );
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $result            = $wpdb->update(
            $dsp_user_profiles,
            $position,
            $user_id,
            array( '%f', '%f' ),
            array( '%d' )
        );

        return $result;
    }
}

add_filter( 'dsp_save_current_user_position', 'dsp_save_current_user_position' );

/**
 *  This function is used to login user.It uses wp_login action hook
 *
 * @param   [login] [currently logged in user details]
 */


if ( ! function_exists( 'dsp_logged_in_user' ) ) {
    function dsp_logged_in_user( $login ) {
        $session_id = session_id();
        $user       = get_user_by( 'login', $login );
        dsp_add_news_feed( $user->ID, 'login' );
        dsp_add_notification( $user->ID, 0, 'login' );
        dsp_login_session_saved( $user->ID, $session_id );
        //dsp_save_current_user_position($user->ID);
    }
}
add_action( 'wp_login', 'dsp_logged_in_user' );


/**
 * This is a function to redirect back to the homepage if there is an error during Login
 */
if ( ! function_exists( 'dsp_incorrect_login' ) ) {
    function dsp_incorrect_login( $redirect_to, $request, $user ) {
        //is there a user to check?
        global $user;
        //$error_redirect = $_SERVER['HTTP_REFERER'];

        if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
            $error_redirect = $_SERVER['HTTP_REFERER'];
        }

        if ( ( is_wp_error( $user ) && ! empty( $user->errors ) ) && ( isset( $error_redirect ) ) && ( strpos( $error_redirect, 'wp-login' ) === false ) && ( strpos( $error_redirect, 'wp-admin' ) === false ) )      // Error during Front Login But Ignore ALL from admin login(wp-admin)
        {
            $error_msg = 'Error';
            if ( isset( $user->errors['incorrect_password'] ) ) {
                $error_msg = 'incorrect_password';
            } elseif ( isset( $user->errors['invalid_username'] ) ) {
                $error_msg = 'invalid_username';
            }
            $end = strpos( $_SERVER['HTTP_REFERER'], '?' );

            if ( $end != false ) {
                $error_redirect = substr( $_SERVER['HTTP_REFERER'], 0, $end );
            }

            wp_safe_redirect( $error_redirect . '?error_msg=' . $error_msg );
            exit;
        } elseif ( is_wp_error( $user ) && empty( $user->errors ) && ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) === false || ( isset( $_GET['loggedout'] ) && $_GET['loggedout'] == true ) ) ) {
            wp_safe_redirect( $error_redirect );
            exit;
        }

        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            //check for admins
            if ( in_array( 'administrator', $user->roles ) ) {
                // redirect them to the default place
                return $redirect_to;
            } else {
                //return home_url();
                return $redirect_to;
            }
        } else {
            return $redirect_to;
        }
    }
}
add_filter( 'login_redirect', 'dsp_incorrect_login', 10, 3 );

/**
 *  This function is used to logout user.It uses wp_logout action hook
 */
if ( ! function_exists( 'dsp_logout_user' ) ) {
    function dsp_logout_user() {

        $user_id = get_current_user_id();

        dsp_add_news_feed( $user_id, 'logout' );

        dsp_add_notification( $user_id, 0, 'logout' );

        dsp_logout_session_destroy( $user_id );

    }
}

add_action( 'wp_logout', 'dsp_logout_user' );


/**
 *  This function is used to get gender list html formatted way
 */

if ( ! function_exists( 'get_gender_list' ) ) {
    function get_gender_list( $selected = "F" ) {
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
            $gender = __(addslashes($gender_row->gender), 'wpdating');

            if ( $gender_row->editable == 'N' ) {
                if ( $gender_row->enum == $selected ) {
                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . $gender . '</option>';
                } else {
                    $gender_option .= '<option value="' . $gender_row->enum . '">' .  $gender . '</option>';
                }
            } else {

                if ( $gender_row->enum == $selected ) {
                    $gender_option .= '<option value="' . $gender_row->enum . '" selected="selected">' . $gender . '</option>';
                } else {
                    $gender_option .= '<option value="' . $gender_row->enum . '">' . $gender . '</option>';
                }
            }
        }

        return $gender_option;
    }
}


/**
 *  This function is used to get gender
 */
if ( ! function_exists( 'get_gender' ) ) {
    function get_gender( $enum = 'M' ) {

        global $wpdb;

        $dsp_gender_list = $wpdb->prefix . DSP_GENDER_LIST_TABLE;

        $query = "select * from $dsp_gender_list where enum='$enum'";

        $gender_row = $wpdb->get_row( $query );

        return __(addslashes($gender_row->gender) , 'wpdating');

    }
}


/**
 *  This function is used to Copy folders and files
 */
if ( ! function_exists( 'rcopy' ) ) {
    function rcopy( $src, $dst ) {
        if ( file_exists( $dst ) ) {
            rrmdir( $dst );
        }
        if ( is_dir( $src ) ) {
            mkdir( $dst );
            $files = scandir( $src );
            foreach ( $files as $file ) {
                if ( $file != "." && $file != ".." ) {
                    rcopy( "$src/$file", "$dst/$file" );
                }
            }
        } else if ( file_exists( $src ) ) {
            copy( $src, $dst );
        }
    }
}

/**
 *  This function is used to create path
 */
if ( ! function_exists( 'createPath' ) ) {
    function createPath( $path ) {
        if ( is_dir( $path ) ) {
            return true;
        }
        $prev_path = substr( $path, 0, strrpos( $path, '/', - 2 ) + 1 );
        $return    = createPath( $prev_path );

        return ( $return && is_writable( $prev_path ) ) ? mkdir( $path ) : false;
    }
}
/////////////////////////////////enqueue/////////////////////////////////////////

/**
 *  This function is used to enqueue style
 */

if ( ! function_exists( 'dsp_enqueue_style' ) ) {
    function dsp_enqueue_style() {
        if ( is_front_page() ) {

            //to include image-slider.css for image slider on front page.
            wp_deregister_style( 'image-slider' );
            //wp_register_style('image-slider', plugins_url('dsp_dating/css/js-image-slider.css'));
            wp_enqueue_style( 'image-slider' );
        } else {

            // to include colorbox stylesheet in plugin.

            wp_deregister_style( 'colorbox' );

            wp_register_style( 'colorbox', WPDATE_URL . '/css/colorbox.css' );

            wp_enqueue_style( 'colorbox' );

            // to include pagination stylesheet in plugin.

            wp_deregister_style( 'paging' );

            wp_register_style( 'paging', WPDATE_URL . '/css/pagination.css' );

            wp_enqueue_style( 'paging' );

            if ( get( 'pagetitle' ) != "" && get( 'pagetitle' ) == 'view_profile' ) {

                // to include image picker in view profile page.

                wp_deregister_style( 'image_picker' );

                wp_register_style( 'image_picker', WPDATE_URL . 'css/image-picker.css' );

                wp_enqueue_style( 'image_picker' );
            }
        }
        // template styles
        wp_enqueue_style( 'slippary', WPDATE_URL . 'templates/layouts/slider-members/css/slippry.css', array(), PLUGIN_VERSION );
        wp_enqueue_style( 'jquery-easy-select-css', WPDATE_URL . 'css/chosen.min.css', array(), PLUGIN_VERSION );

        // to include plugin stylesheet.
        wp_enqueue_style( 'dating-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,300,600,700' );
        //to include jquery-ui.css for dialog box on front page.
        wp_deregister_style( 'jquery-ui-smoothness' );
        wp_register_style( 'jquery-ui-smoothness', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css' );
        wp_enqueue_style( 'jquery-ui-smoothness' );
       
        global $wpdb;
        $DSP_GENERAL_SETTINGS_TABLE = $wpdb->prefix . "dsp_general_settings";
        $dspMbDir                   = WP_DSP_ABSPATH . "mobile";
        $mobileStatus               = $wpdb->get_var( "SELECT setting_status FROM $DSP_GENERAL_SETTINGS_TABLE where setting_name = 'mobile'" );
        $themeName                  = wp_get_theme();
        $themeName                  = str_replace( ' ', '_', trim( $themeName['Name'] ) );
        wp_enqueue_style( 'dsp-user_section_styles', WPDATE_URL . '/css/user_section_styles.css', false, PLUGIN_VERSION );

        if ( file_exists( $dspMbDir ) && is_dir( $dspMbDir ) && $mobileStatus == 'Y' ) { // mobile folder exist
            $wptouch_plugin_obj = new WPtouchPlugin();
            if ( $wptouch_plugin_obj->applemobile || $wptouch_plugin_obj->desired_view == 'mobile' ) {
                // wp_enqueue_style('dsp-user_section_styles', WPDATE_URL . '/css/user_section_styles.css' ,false,PLUGIN_VERSION);
                wp_enqueue_style( 'dating-icons', WPDATE_URL . 'css/dating-icons.css', false, PLUGIN_VERSION );

            } else {
                // wp_enqueue_style('dsp-user_section_styles', WPDATE_URL . '/css/user_section_styles.css' ,false,PLUGIN_VERSION);
                wp_enqueue_style( 'dating-icons', WPDATE_URL . 'css/dating-icons.css', false, PLUGIN_VERSION );

            }
        } else {

            //wp_enqueue_style('dsp-user_section_styles', WPDATE_URL . '/css/user_section_styles.css' ,false,PLUGIN_VERSION);
            wp_enqueue_style( 'dating-icons', WPDATE_URL . 'css/dating-icons.css', false, PLUGIN_VERSION );

        }
    }
}

/**
 *  This function is used to enqueue script
 */

if ( ! function_exists( 'dsp_enqueue_script' ) ) {
    function dsp_enqueue_script() {
        global $wp_version;


        wp_enqueue_script( 'jquery' );

        if ( is_front_page() ) {
            // to include jquery dialog box js..
            wp_enqueue_script( 'modernizr', WPDATE_URL . 'js/modernizr-2.8.3.min.js' );
            wp_enqueue_script( 'colorbox', WPDATE_URL . 'colorbox/jquery.colorbox.min.js', array(), '1.6.4', true );
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_script( 'dsp_respond_script', WPDATE_URL . 'js/respond.min.js' );
            // to include image-slider js on home page
            wp_enqueue_script( 'image-slider', WPDATE_URL . 'js/jquery.cycle2.min.js', array(), '', true );
        } else {

            // to include colorbox script in plugin.
            wp_enqueue_script( 'modernizr', WPDATE_URL . 'js/modernizr-2.8.3.min.js' );
            wp_enqueue_script( 'colorbox', WPDATE_URL . 'colorbox/jquery.colorbox.min.js', array(), '1.6.4', true );
            wp_enqueue_script( 'dsp_respond_script', WPDATE_URL . 'js/respond.min.js' );
            wp_enqueue_script( 'report-comment', WPDATE_URL . 'js/report-comment.js' );


            if ( get( 'pagetitle' ) != "" && get( 'pagetitle' ) == 'view_profile' ) {

                // to include image-picker script on view profile page.

                wp_enqueue_script( 'image_picker1', WPDATE_URL . 'js/image-picker.js' );

                wp_enqueue_script( 'image_picker2', WPDATE_URL . 'js/image-picker.min.js' );
            }
        }
        // templates javascript
        wp_enqueue_script( 'slippry', WPDATE_URL . 'templates/layouts/slider-members/slippry.min.js' );
        wp_enqueue_script( 'facebook', 'https://connect.facebook.net/en_US/all.js' );
        // to include custom javascript,google api,google recaptcha api
        //wp_deregister_script('jquery');

        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $google_api_key_zip         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'google_api_key_zip'" );

        if ( $google_api_key_zip->setting_value != '' ) {
            wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?libraries=places&key=' . $google_api_key_zip->setting_value );
            //wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false&key=' . $google_api_key_zip->setting_value );
        } else {
            //wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?libraries=places&sensor=false' );
            wp_enqueue_script( 'googleapis', 'https://maps.googleapis.com/maps/api/js?libraries=places' );
        }

        wp_enqueue_script( 'googlecaptchaapis', 'https://www.google.com/recaptcha/api.js' );
        wp_enqueue_script( 'custom-front', WPDATE_URL . 'js/custom-front.js', array(), '2.0' );
        wp_enqueue_script( 'jquery-easy-select-js', WPDATE_URL . 'js/chosen.jquery.min.js', array(), PLUGIN_VERSION );

        wp_enqueue_script( 'parsley', WPDATE_URL . 'js/parsley-form-validation.min.js' );

        wp_localize_script( 'parsley', 'validation_messages', array(
            'defaultMessage' => __( 'This value seems to be invalid.','wpdating' ),
            'email'          => __( 'This value should be a valid email.','wpdating' ),
            'url'            => __( 'This value should be a valid url.','wpdating' ),
            'number'         => __( 'This value should be a valid number.','wpdating' ),
            'integer'        => __( 'This value should be a valid integer.','wpdating' ),
            'digits'         => __( 'This value should be digits.','wpdating' ),
            'alphanum'       => __( 'This value should be alphanumeric.','wpdating' ),
            'notblank'       => __( 'This value should not be blank.','wpdating' ),
            'required'       => __( 'This value is required.','wpdating' ),
            'pattern'        => __( 'This value seems to be invalid.','wpdating' ),
            'min'            => __( 'This value should be greater than or equal to %s.','wpdating' ),
            'max'            => __( 'This value should be lower than or equal to %s.','wpdating' ),
            'range'          => __( 'This value should be between %s and %s.','wpdating' ),
            'minlength'      => __( 'This value is too short. It should have %s characters or more.','wpdating' ),
            'maxlength'      => __( 'This value is too long. It should have %s characters or fewer.','wpdating' ),
            'length'         => __( 'This value length is invalid. It should be between %s and %s characters long.','wpdating' ),
            'mincheck'       => __( 'You must select at least %s choices.','wpdating' ),
            'maxcheck'       => __( 'You must select %s choices or fewer.','wpdating' ),
            'check'          => __( 'You must select between %s and %s choices.','wpdating' ),
            'equalto'        => __( 'This value should be the same.','wpdating' )
        ) );

        wp_enqueue_script( 'jquery-rotate', WPDATE_URL . 'js/jquery.rotate.1-1.js' );

        // For template files javascripts
        if ( $wp_version < 4.1 ) {
            wp_enqueue_script( 'dspjqui', includes_url() . 'js/jquery/ui/jquery.ui.core.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui2', includes_url() . 'js/jquery/ui/jquery.ui.mouse.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui3', includes_url() . 'js/jquery/ui/jquery.ui.resizable.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui4', includes_url() . 'js/jquery/ui/jquery.ui.draggable.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui5', includes_url() . 'js/jquery/ui/jquery.ui.button.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui7', includes_url() . 'js/jquery/ui/jquery.ui.dialog.min.js', array(), PLUGIN_VERSION, true );
        } else {
            wp_enqueue_script( 'dspjqui', includes_url() . 'js/jquery/ui/core.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui2', includes_url() . 'js/jquery/ui/mouse.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui3', includes_url() . 'js/jquery/ui/resizable.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui4', includes_url() . 'js/jquery/ui/draggable.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui5', includes_url() . 'js/jquery/ui/button.min.js', array(), PLUGIN_VERSION, true );
            wp_enqueue_script( 'dspjqui7', includes_url() . 'js/jquery/ui/dialog.min.js', array(), PLUGIN_VERSION, true );

        }

        global $wpdb;

        $DSP_GENERAL_SETTINGS_TABLE = $wpdb->prefix . "dsp_general_settings";

        $dspMbDir = WP_DSP_ABSPATH . "mobile";

        $mobileStatus = $wpdb->get_var( "SELECT setting_status FROM $DSP_GENERAL_SETTINGS_TABLE where setting_name = 'mobile'" );


        if ( file_exists( $dspMbDir ) && is_dir( $dspMbDir ) && $mobileStatus == 'Y' ) { // mobile folder exist
            $wptouch_plugin_obj = new WPtouchPlugin();

            if ( $wptouch_plugin_obj->applemobile || $wptouch_plugin_obj->desired_view == 'mobile' ) {

                wp_register_script( 'countryStateCity', WPDATE_URL . 'mobile/js/countryStateCity.js' );

                wp_enqueue_script( 'countryStateCity' );
            }
        }
    }
}

add_action( 'wp_enqueue_scripts', 'dsp_enqueue_style' );

add_action( 'wp_enqueue_scripts', 'dsp_enqueue_script' );

/**
 *  This function is check valid username
 */

if ( ! function_exists( 'disable_spaces_in_username' ) ) {
    function disable_spaces_in_username( $valid, $username ) {
        if ( preg_match( "/\\s/", $username ) ) {
            return $valid = false;
        }

        return $valid;
    }
}
add_filter( 'validate_username', 'disable_spaces_in_username', 10, 2 );

/**
 *  This function is check if dsp login is activated or not if activated then redirect to setting value
 */

if ( ! function_exists( 'dsp_login_activated' ) ) {
    function dsp_login_activated() {
        $custom = get_bloginfo( 'url' ) . '/members';
        if ( is_plugin_active( 'dsp-login/sidebar-login.php' ) ) {
            // plugin is activated
            $url         = get_option( 'sidebarlogin_login_redirect' );
            $redirect_to = ( isset( $url ) && ! empty( $url ) ) ? $url : $custom;
        } else {
            $redirect_to = $custom;
        }

        return $redirect_to;
    }
}


add_action( 'plugins_loaded', 'dsp_login_activated' );

/**
 *  This function creates footer section language selection
 */
if ( ! function_exists( 'dsp_display_language' ) ) {
    function dsp_display_language( $lang_id = null ) {
        //echo $_SESSION['default_lang'];die;
        global $wpdb;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $current_user               = $wpdb->get_row( "SELECT * FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "'" );
        $all_languages              = $wpdb->get_results( "SELECT * FROM $dsp_language_detail_table " );
        $number_language            = $wpdb->num_rows;
        $url                        = ROOT_LINK;
        $html                       = "";
        if ( $number_language > 1 ) {
            $html = '<div style="float:left; width:100%; text-align:center; margin-bottom:10px;">';
            if ( $current_user->user_id == 0 ) {
                if ( ! empty( $lang_id ) ) {
                    $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where language_id='" . $lang_id . "' " );
                } else {
                    $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where display_status = '1' " );
                }
            } else {
                $language_info = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where language_id='" . $current_user->language_id . "' " );
            }
            // echo "<pre>";print_r($language_info);die;
            foreach ( $all_languages as $lang ) {
                $displayStatus = ! empty( $lang->display_status ) ? $lang->display_status : 0;
                $imagePath     = get_bloginfo( 'url' ) . '/wp-content/uploads/flags/' . $lang->flag_image;
                if ( $lang->language_id != $language_info->language_id ) {
                    $html .= '<span onclick="language_status(' . $lang->language_id . ',' . $displayStatus . ',\'' . $url . '\')" class="dsp_span_pointer" style="cursor:pointer;">';
                    $html .= '<img height="24" src="' . $imagePath . '" style="padding-right:5px;" /></span>';
                } else {
                    $html .= '<span><img height="24" src="' . $imagePath . '" style="padding-right:5px;"/></span>';
                }
            }
            $html .= '</div>';
        }

        return $html;
    }
}

add_action( 'plugins_loaded', 'dsp_display_language' );

/**
 *  This function is used to intialize session
 */
if ( ! function_exists( 'dsp_register_session' ) ) {
    function dsp_register_session() {
        if ( ! session_id() ) {
            session_start();
        }

    }
}
add_action( 'init', 'dsp_register_session' );

/**
 *  This function is used to check wheather or not user already exist in DSP_USER_PROFILES table
 */

if ( ! function_exists( 'dsp_is_user_exist' ) ) {
    function dsp_is_user_exist( $userId ) {
        global $wpdb;
        $dsp_user_profiles = $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $query             = "SELECT user_profile_id FROM $dsp_user_profiles WHERE user_id = '" . $userId . "'";
        $userInfo          = $wpdb->query( $query );
        if ( ! empty( $userInfo ) ) {
            return true;
        } else {
            return false;
        }
    }
}
add_action( 'plugins_loaded', 'dsp_is_user_exist' );

/**
 *  This function is used to clean string
 *
 * @param [string] [string to clean]
 */

if ( ! function_exists( 'dsp_clean_string' ) ) {
    function dsp_clean_string( $string ) {
        $string = str_replace( ' ', ' ', $string ); // Replaces all spaces with hyphens.
        $string = preg_replace( '/[^a-zA-Z0-9\s]/', '', $string ); // Removes special chars.

        return preg_replace( '/-+/', '-', $string ); // Replaces multiple hyphens with single one.
    }
}

/**
 *  create new user if not exist using facebook profile
 *
 * @param [user] [logged  in user details]
 */

if ( ! function_exists( 'dsp_add_new_user' ) ) {
    function dsp_add_new_user( $user ) {

        if ( isset( $user ) && ! empty( $user ) ) {
            global $wpdb;
            $dsp_users_table   = $wpdb->prefix . DSP_USERS_TABLE;
            $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
            if ( ! function_exists( 'wp_generate_password' ) ) {
                include_once( ABSPATH . 'wp-includes/pluggable.php' );
            }
            $random_password  = wp_generate_password( 12, false );
            $user_login       = strtolower( $user['first_name'] ) . $user['last_name'];
            $user_nicename    = $user['first_name'];
            $fbId             = $user['id'];
            $user_email       = $user['email'];
            $display_name     = $user['name'];
            $gender           = strtolower( $user['gender'] ) == 'male' ? 'M' : 'F';
            $age              = date_format( date_create( $user['birthday'] ), 'Y-m-d' );
            $last_update_date = date( 'Y-m-d H:i:s' );
            $isUserExist      = ( dsp_check_fb_user_exist( $fbId ) || email_exists( $user_email ) ) ? true : false;
            if ( $isUserExist ) {
                $existUser = get_user_by( 'email', $user_email );
                if ( ! dsp_check_fb_user_exist( $fbId ) ) {
                    add_user_meta( $existUser->ID, 'fbId', $fbId, true );
                }
                dsp_auto_login( $existUser->user_login );
            } else {
                if ( username_exists( $user_login ) ) {
                    $user_login = dsp_getUniqueUserName( $user_login );
                }

                $userdata = array(
                    'user_login'    => $user_login,
                    'user_pass'     => $random_password,
                    'user_nicename' => $user_nicename,
                    'user_email'    => $user_email,
                    'display_name'  => $display_name,
                );

                $user_id = wp_insert_user( $userdata );


                if ( ! empty( $user_id ) ) {
                    add_user_meta( $user_id, 'fbId', $fbId, true );
                    $wpdb->query( "INSERT IGNORE INTO $dsp_user_profiles (`user_id`,`gender`,`age`,`last_update_date`)
                                    VALUES('$user_id','$gender','$age','$last_update_date')" );
                    $from    = get_option( 'admin_email' );
                    $headers = __( 'from','wpdating' ) . $from . "\r\n";
                    $subject = __( 'Registration Successful','wpdating' );
                    $message = __( 'Your Login Details','wpdating' ) . "\n" . __( 'Username:','wpdating' ) . $username . "\n" . __( 'Password:','wpdating' ) . $random_password;
                    wp_mail( $email, $subject, $message, $headers );
                    dsp_auto_login( $user_login );
                }

            }
        }
    }
}

/**
 *  This function is used for auto login if user wanna logged in with fb id
 *
 * @param [user_login] [logged  in user details]
 */
if ( ! function_exists( 'dsp_auto_login' ) ) {
    function dsp_auto_login( $user_login ) {
        ob_start();
        $user_data = get_user_by( 'login', $user_login );
        $user_id   = $user_data->ID;
        wp_set_current_user( $user_id, $user_login );
        wp_set_auth_cookie( $user_id );
        do_action( 'wp_login', $user_login, $user_data);
        ob_end_flush();
    }
}

add_action( 'dsp_automatic_login', 'dsp_auto_login' );

/**
 *  This function is used for get unique name
 *
 * @param [user_login] [logged  in user details]
 */
if ( ! function_exists( 'dsp_getUniqueUserName' ) ) {
    function dsp_getUniqueUserName( $user_login ) {
        $i = 0;
        while ( true ) {
            $uniqueUsername = $user_login . $i;
            if ( ! username_exists( $uniqueUsername ) ) {
                return $uniqueUsername;
            }
            $i ++;
        }
    }
}


/**
 *  This function is used for checking facebook user already user exist or not
 *
 * @param [fbId] [Facebook id]
 */

if ( ! function_exists( 'dsp_check_fb_user_exist' ) ) {
    function dsp_check_fb_user_exist( $fbId ) {
        global $wpdb;
        $user_meta_table = $wpdb->prefix . "usermeta";
        $user_id         = $wpdb->get_var( "SELECT user_id FROM `$user_meta_table` WHERE `meta_value` LIKE '$fbId'" );
        //echo $wpdb->last_query;die;
        if ( ! empty( $user_id ) ) {
            return true;
        } else {
            return false;
        }

    }
}


/**
 *  This function is used to call when logout
 *
 */

if ( ! function_exists( 'dsp_facebook_user_logout' ) ) {
    function dsp_facebook_user_logout() {
        // delete cookie data
        if ( isset( $_SERVER['HTTP_COOKIE'] ) ) {
            $cookies = explode( ';', $_SERVER['HTTP_COOKIE'] );
            foreach ( $cookies as $cookie ) {
                $parts = explode( '=', $cookie );
                $name  = trim( $parts[0] );
                setcookie( $name, '', time() - 1000 );
                setcookie( $name, '', time() - 1000, '/' );
            }
        }
    }
}

add_action( 'wp_logout', 'dsp_facebook_user_logout' );


/**
 *  This function is used to check email notification  setting by user
 *
 * @param [user_id] [Currently logged in user id]
 * @param [column_name] [column name]
 */

if ( ! function_exists( 'dsp_issetGivenEmailSetting' ) ) {
    function dsp_issetGivenEmailSetting( $user_id, $column_name ) {
        global $wpdb;
        $dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
        $status                      = $wpdb->get_results( $wpdb->prepare( "SELECT $column_name FROM $dsp_user_notification_table WHERE user_id='%d'", $user_id ), ARRAY_N );
        if ( isset( $status ) &&
             ! empty( $status ) &&
             $status[0][0] == 'N'
        ) {
            return false;
        } else {
            return true;
        }
    }
}

/**
 *  This function is used to check email notification  setting by user
 *
 * @param   [email] [Email of logged in user]
 */

if ( ! function_exists( 'dsp_get_admin_username' ) ) {
    function dsp_get_admin_username( $email ) {
        global $wpdb;
        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $admin_email    = $wpdb->get_row( "SELECT `user_login` FROM `$dsp_user_table` WHERE `user_email`='$email'" );

        return $admin_email->user_login;
    }
}

/**
 *  This function is used to check discount mode
 */

if ( ! function_exists( 'dsp_check_discount_mode' ) ) {
    function dsp_check_discount_mode() {
        global $wpdb;
        $dsp_discount_codes_table = $wpdb->prefix . DSP_DISCOUNT_CODES_TABLE;
        $isDiscountModeOn         = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_discount_codes_table WHERE status='1'" );

        return $isDiscountModeOn;
    }
}


/**
 *  This function is used to check email notification  setting by user
 *
 * @param   [discount_code] [Discount code to check]
 */

if ( ! function_exists( 'dsp_update_discount_coupan_used' ) ) {
    function dsp_update_discount_coupan_used( $discount_code ) {
        global $wpdb;
        $dsp_discount_codes_table = $wpdb->prefix . DSP_DISCOUNT_CODES_TABLE;
        $discount_information     = $wpdb->get_row( "SELECT uses FROM $dsp_discount_codes_table WHERE code='$discount_code'" );
        if ( isset( $discount_information ) && ! empty( $discount_information ) ) {
            $uses = $discount->information->uses + 1;
            if ( isset( $discount_code ) && ! empty( $discount_code ) ) {
                $wpdb->update( $dsp_discount_codes_table,
                    array( 'uses' => $uses ),
                    array( 'code' => $discount_code ),
                    array( '%d' ),
                    array( '%s' )
                );
            }

        }
    }
}


/**
 *  This function is used to get setting for free mode by gender
 *
 * @param   [gender] [gender to check]
 */

if ( ! function_exists( 'dsp_get_setting_free_mode_gender' ) ) {
    function dsp_get_setting_free_mode_gender( $gender ) {
        switch ( $gender ) {
            case 1:
                return 'M';
                break;
            case 2:
                return 'F';
                break;
            default:
                return 'C';
                break;
        }

    }
}

/**
 *  This function is used to get gender users
 */

if ( ! function_exists( 'dsp_get_gender_users' ) ) {
    function dsp_get_gender_users() {
        global $wpdb;
        global $current_user;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_user_profiles          = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $user_id                    = $current_user->ID;
        $setting_value              = $wpdb->get_row( "SELECT setting_value FROM $dsp_general_settings_table WHERE setting_name = 'free_member'" );
        $check_free_mode            = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_mode'" );
        if ( $check_free_mode->setting_status == 'N' ) {
            $_SESSION['free_member'] = false;

            return false;
        } else {
            if ( $setting_value->setting_value == 3 ) {
                $_SESSION['free_member'] = true;

                //echo "inside=\t".$_SESSION['free_member'];die;
                return false;
            } else {
                $free_member_gender      = dsp_get_setting_free_mode_gender( $setting_value->setting_value );
                $is_free_member          = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $dsp_user_profiles where user_id = %d AND gender= %s", $user_id, $free_member_gender ) );
                $_SESSION['free_member'] = ( $is_free_member > 0 ) ? true : false;
                //echo "outside=\t".$_SESSION['free_member'];die;
            }
        }
    }
}

add_action( 'init', 'dsp_get_gender_users' );

/**
 *  This function is used to get extension like .jpg/.png
 *
 * @param  [str] [full name file]
 */

if ( ! function_exists( 'dsp_getExtension' ) ) {
    function dsp_getExtension( $str ) {
        $i = strrpos( $str, "." );
        if ( ! $i ) {
            return "";
        }
        $l   = strlen( $str ) - $i;
        $ext = substr( $str, $i + 1, $l );

        return $ext;
    }
}


/**
 *  This function is used to insert template values
 *
 * @param  [data] [full name file]
 * @param  [id] [Currently loggedin user id]
 */

if ( ! function_exists( 'dsp_insert_template_values' ) ) {
    function dsp_insert_template_values( $data = '', $id = '' ) {
        global $wpdb;
        $dsp_template_images_table = $wpdb->prefix . DSP_TEMPLATE_IMAGES;
        if ( ! empty( $id ) ) {
            $format   = array( '%s', '%s', '%s', '%s', '%s' );
            $updateId = array( 'ID' => $id );
            $idFormat = array( '%d' );
            $result   = $wpdb->update( $dsp_template_images_table, $data, $updateId, $format, $idFormat );
            if ( $result > 0 ) {
                return true;
            }

            return false;
        } else {
            if ( ! empty( $data ) ) {
                /* $query = "INSERT INTO $dsp_template_images_table ('caption','template_image','url','file_type','display_status') VALUES(%s,%s,%s,%s,%s)";
                 $saved = $wpdb->query($wpdb->prepare($query,$data));*/
                $format = array( '%s', '%s', '%s', '%s', '%s' );
                $result = $wpdb->insert( $dsp_template_images_table, $data, $format );
                if ( $result > 0 ) {
                    return true;
                }

                return false;
            }
        }
    }
}

/**
 * Override the default upload path.
 *
 * @param   array $dir
 *
 * @return  array
 */
if ( ! function_exists( 'dsp_temp_upload_dir' ) ) {
    function dsp_temp_upload_dir( $dir ) {
        return array(
                   'path'   => $dir['basedir'] . '/template_images',
                   'url'    => $dir['baseurl'] . '/template_images',
                   'subdir' => '/template_images',
               ) + $dir;
    }
}

/**
 *  This function is used to get all template image
 *
 * @param  [display_status] [status of image]
 * @param  [id] [Currently loggedin user id]
 */


if ( ! function_exists( 'dsp_get_all_template_image' ) ) {
    function dsp_get_all_template_image( $id = '', $display_status = '' ) {
        global $wpdb;
        $dsp_template_images_table = $wpdb->prefix . DSP_TEMPLATE_IMAGES;
        $query                     = "SELECT * FROM $dsp_template_images_table ";

        $conditions   = array();
        $conditions[] = ! empty( $id ) ? " `id` = $id " : '';
        $conditions[] = ! empty( $display_status ) ? " `display_status` = '$display_status' " : '';

        $conditions = array_filter( $conditions );
        if ( ! empty( $conditions ) ) {
            $query .= " WHERE " . implode( ' AND ', $conditions );
        }

        return $wpdb->get_results( $query );
    }
}


/**
 *  This function is used to destroy template image
 *
 * @param  [id] [Currently loggedin user id]
 */

if ( ! function_exists( 'dsp_destroy_template_image' ) ) {
    function dsp_destroy_template_image( $id = '' ) {
        global $wpdb;
        $dsp_template_images_table = $wpdb->prefix . DSP_TEMPLATE_IMAGES;
        if ( ! empty( $id ) ) {
            return $wpdb->delete( $dsp_template_images_table, array( 'id' => $id ), array( '%d' ) );;
        }
    }
}

/**
 *  This function is used check zip code
 *
 * @param  [zipCode]
 */

if ( ! function_exists( 'dsp_isZipCodeExist' ) ) {
    function dsp_isZipCodeExist( $zipCode ) {
        global $wpdb;
        $dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
        $findzipcodelatlng = $wpdb->get_row( "SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipCode'" );

        return ! empty( $findzipcodelatlng ) ? true : false;
    }
}

/**
 *  This function is used get template path
 *
 * @param  [templateName]
 */

if ( ! function_exists( 'dsp_get_template_path' ) ) {
    function dsp_get_template_path( $templatePath = '' ) {
        $path = plugins_url( "../../templates/$templatePath[1]/", __FILE__ );

        return $path;

    }
}

/**
 *  This function is used delete extra random users
 *
 * @param  [limit]
 */

if ( ! function_exists( 'dsp_deleteExtrarandomUsers' ) ) {
    function dsp_deleteExtrarandomUsers( $limit ) {
        global $wpdb;
        $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $query            = "DELETE FROM `$dsp_online_table`  WHERE  is_random = '1' ";
        $query            .= " LIMIT $limit";
        $wpdb->query( $query );

    }
}


/**
 *  This function is used delete update random online user time
 */

if ( ! function_exists( 'dsp_autoUpdateRandomOnlineUserTime' ) ) {
    function dsp_autoUpdateRandomOnlineUserTime() {
        global $wpdb;
        $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $time             = time();

        $sql_query        = "UPDATE $dsp_online_table SET time = $time WHERE user_id IN (
                            SELECT user_id FROM (SELECT user_id FROM `$dsp_online_table`  WHERE  is_random = '1' ) as user_id)";

        $wpdb->query($sql_query);
    }
}

// if ( ! function_exists( 'dsp_autoUpdateRandomOnlineUserTime' ) ) {
//     function dsp_autoUpdateRandomOnlineUserTime() {
//         global $wpdb;
//         $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
//         $query            = "SELECT user_id FROM `$dsp_online_table`  WHERE  is_random = '1' ";
//         $randomUsers      = $wpdb->get_results( $query , ARRAY_A );
//         $time             = time();
//         $wpdb->query( "UPDATE $dsp_online_table SET time = $time WHERE user_id IN ( $randomUsers)" );
//     }
// }
/**
 *  This function is used to get total online users
 *
 * @param  [random] [admin setting for random users]
 * @param  [random_online_status] [flag to check random online status]
 */

if ( ! function_exists( 'dsp_getTotalOnlineUsers' ) ) {
    function dsp_getTotalOnlineUsers( $random = true, $random_online_status = '' ) {
        global $wpdb;
        $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $query            = "SELECT COUNT(*) FROM `$dsp_online_table` ";
        if ( $random ) {
            $query .= "  WHERE is_random = '1' ";
        }
        $query            .= ( $random_online_status == 'N' ) ? "  WHERE is_random = '0'" : " ";
        $totalRandomUsers = $wpdb->get_var( $query );

        return $totalRandomUsers;
    }
}

/**
 *  This function is used to get online users
 *
 * @param  [filters] [admin setting for random users]
 * @param  [randomUserDisplaystatus] [flag to check random online status]
 */

if ( ! function_exists( 'dsp_getOnlineMembers' ) ) {
    function dsp_getOnlineMembers( $filters = null, $randomUserDisplaystatus = true ) {
        global $wpdb;
        $dsp_user_profiles          = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $dsp_online_table           = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        dsp_autoUpdateRandomOnlineUserTime();
        // check couples module must be premium member Mode is Activated or not.
        $check_couples_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'couples'" );

        $query = "SELECT distinct online.user_id,gender, profile.age ,profile.country_id, profile.make_private private FROM `$dsp_online_table` online inner join $dsp_user_profiles profile on(online.user_id=profile.user_id) WHERE  online.status='Y' AND  online.time >= unix_timestamp(CURRENT_TIMESTAMP - INTERVAL 6 MINUTE) AND profile.status_id=1";
        $query .= " AND profile.stealth_mode = 'N'  AND profile.country_id > 0 ";
        if ( $randomUserDisplaystatus ) {
            $query .= " AND is_random = '0'";
        }

        if ( ! empty( $filters ) ) {
            extract( $filters );
            $age_from     = ( empty( $age_from ) || ! is_numeric( $age_from ) ) ? 18 : $age_from;
            $age_to       = ( empty( $age_to ) || ! is_numeric( $age_to ) ) ? 90 : $age_to + 1;
            $start        = empty( $start ) ? 0 : $start;
            $last         = empty( $last ) ? 10 : $last;
            $conditions   = array();
            $conditions[] = ( isset( $age_from ) && $age_from >= 18 ) ? " AND ((year(CURDATE())-year(profile.age)) BETWEEN $age_from AND $age_to)  " :
                " AND ((year(CURDATE())-year(profile.age)) BETWEEN $age_from AND $age_to)   ";

            if ( isset( $gender ) && $gender == 'M' && ! get( 'show' ) ) {
                $conditions[] .= " gender='M' ";
            } else if ( isset( $gender ) && $gender == 'F' && ! get( 'show' ) ) {
                $conditions[] .= " gender='F' ";
            } else if ( isset( $gender ) && $gender == 'C' && ! get( 'show' ) ) {
                $conditions[] .= " gender='C' ";
            } else {
                if ( isset( $check_couples_mode ) && $check_couples_mode->setting_status == 'Y' ) {
                    $conditions[] .= " gender IN('M','F','C') ";
                } else {
                    $conditions[] .= " gender IN('M','F') ";
                }
            }

            $conditions = array_filter( $conditions );
            if ( ! empty( $conditions ) ) {
                $query .= implode( ' AND ', $conditions );
            }
            $query .= "GROUP BY online.user_id";
            $query .= " LIMIT  $start, $last";

        }
        $online_members = $wpdb->get_results( $query );

        return $online_members;
    }
}

/**
 *  This function is used to get new users
 */
if ( ! function_exists( 'dsp_getNewMembers' ) ) {
    function dsp_getNewMembers() {
        global $wpdb;
        $dsp_user_profiles          = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        // check Member List Gender Access  Mode is Activated or not.
        $gender = $wpdb->get_var( "SELECT `setting_value` FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'" );
        switch ( $gender ) {
            case 2:
                $gender = 'M';
                break;
            case 3:
                $gender = 'F';
                break;

            default:
                $gender = '';
                break;
        }
        $query       = "SELECT * FROM $dsp_user_profiles WHERE status_id= 1  AND country_id != 0 AND stealth_mode='N' ";
        $query       .= ! empty( $gender ) ? " AND gender = '%s' " : '';
        $query       .= " ORDER by last_update_date desc LIMIT 21";
        $new_members = ! empty( $gender ) ? $wpdb->get_results( $wpdb->prepare( $query, $gender ) ) : $wpdb->get_results( $query );

        return $new_members;
    }
}

/**
 * Modules for getting random online female users using backend setting
 *
 * @param  [$end][total no. of random user to be online ]
 * @param  [$filters][Random online member setting is on or off]
 */

if ( ! function_exists( 'dsp_randomOnlineMembers' ) ) {
    function dsp_randomOnlineMembers( $end = 3, $filters = null ) {
        global $wpdb;
        $dsp_online_table  = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $onlineUserIds     = array();
        $online_members    = dsp_getOnlineMembers( '', false );
        foreach ( $online_members as $member ) {
            $onlineUserIds[] = $member->user_id;
        }
        $onlineUserIds    = implode( ",", $onlineUserIds );
        $randomUsers      = $wpdb->get_results( "SELECT distinct online.user_id,gender, profile.age, profile.country_id FROM `$dsp_online_table` online inner join $dsp_user_profiles profile on(online.user_id=profile.user_id) WHERE is_random = '1'  " );
        $totalRandomUsers = count( $randomUsers );
        // if admin entered 6 random users then change it to less than 6
        if ( $end < $totalRandomUsers ) {
            $extraRandomUsers = $totalRandomUsers - $end;
            dsp_deleteExtrarandomUsers( $extraRandomUsers );
        }
        $totalRandomUsers = dsp_getTotalOnlineUsers( true );
        if ( empty( $randomUsers ) || $totalRandomUsers < $end ) {
            $end -= $totalRandomUsers;
            if ( $end > 0 ) {
                $query       = "SELECT distinct user_id  FROM $dsp_user_profiles  profile where profile.stealth_mode = 'N' AND country_id > 0 ";
                $query       .= ( ! empty( $onlineUserIds ) && is_null($onlineUserIds) && count( $onlineUserIds ) > 0 ) ? "  AND user_id NOT IN($onlineUserIds) " : " ";
                $query       .= " ORDER BY RAND() LIMIT 0,$end";
                $randomUsers = $wpdb->get_results( $query );
                foreach ( $randomUsers as $onlineUser ) {
                    $session_id = session_id();
                    $sql        = 'INSERT IGNORE INTO ' . $dsp_online_table . ' (`session`,`user_id`, `status`, `time`,`is_random`) values
                    (\'' . $session_id . '\', \'' . $onlineUser->user_id . '\' , \'Y\',\'' . time() . '\',\'1\')';
                    $wpdb->query( $sql );
                }
            }

        }
        $random_online_members = ! empty( $filters ) ? dsp_getOnlineMembers( $filters, false ) : dsp_getOnlineMembers( '', false );

        return $random_online_members;
    }
}


/**
 * This function is used to create dating solution own pagination module to create dynamically pagination
 *
 * @param  [$total_result][total no. of results to output]
 * @param  [$limit][total no. of result currently want to show]
 * @param  [$page][current page no.]
 * @param  [$adjacents][How many adjacent pages should be shown on each side?]
 * @param  [$page_name][full link ]
 *
 * @return [total html with link and list in no. like 1.2.3. etc. inside <div> with
 *          pagination class]
 */

if ( ! function_exists( 'dsp_pagination' ) ) {
    function dsp_pagination( $total_result, $limit, $page, $adjacents, $page_name ) {

        // Calculate total number of pages. Round up using ceil()
        //$total_pages1 = ceil($total_results1 / $max_results1);
        //******************************************************************************************************************************************
        $limit = $limit > 0 ? $limit : 9;
        if ( $page == 0 ) {
            $page = 1;
        }     //if no page var is given, default to 1.
        $prev     = $page - 1;
        $next     = $page + 1;
        $lastpage = ceil( $total_result / $limit );
        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ( $lastpage > 1 ) {
            $pagination .= "<div class='wpse_pagination'>";
            //previous button
            if ( $page > 1 ) {
                $pagination .= "<div><a class='disabled' href=\"" . $page_name . "page/$prev\">" . __( 'Previous','wpdating' ) . "</a></div>";
            } else {
                $pagination .= "<span  class='disabled'>" . __( 'Previous','wpdating' ) . "</span>";
            }

            //pages
            if ( $lastpage <= 7 + ( $adjacents * 2 ) ) { //not enough pages to bother breaking it up//4
                for ( $counter = 1; $counter <= $lastpage; $counter ++ ) {
                    if ( $counter == $page ) {
                        $pagination .= "<span class='current'>$counter</span>";
                    } else {
                        $pagination .= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                    }
                }
            } elseif ( $lastpage > 5 + ( $adjacents * 2 ) ) { //enough pages to hide some//5
                //close to beginning; only hide later pages
                if ( $page < 1 + ( $adjacents * 2 ) ) {
                    for ( $counter = 1; $counter < 4 + ( $adjacents * 2 ); $counter ++ ) {
                        if ( $counter == $page ) {
                            $pagination .= "<span class='current'>$counter</span>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                        }
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                } //in middle; hide some front and some back
                elseif ( $lastpage - ( $adjacents * 2 ) > $page && $page > ( $adjacents * 2 ) ) {
                    $pagination .= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ( $counter = $page - $adjacents; $counter <= $page + $adjacents; $counter ++ ) {
                        if ( $counter == $page ) {
                            $pagination .= "<div class='current'>$counter</div>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                        }
                    }
                    $pagination .= "<span>...</span>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                } //close to end; only hide early pages
                else {
                    $pagination .= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                    $pagination .= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                    $pagination .= "<span>...</span>";
                    for ( $counter = $lastpage - ( 2 + ( $adjacents * 2 ) ); $counter <= $lastpage; $counter ++ ) {
                        if ( $counter == $page ) {
                            $pagination .= "<span class='current'>$counter</span>";
                        } else {
                            $pagination .= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                        }
                    }
                }
            }

            //next button
            if ( $page < $counter - 1 ) {
                $pagination .= "<div class='disabled'><a href=\"" . $page_name . "page/$next\">" . __( 'Next','wpdating' ) . "</a></div>";
            } else {
                $pagination .= "<span class='disabled'>" . __( 'Next','wpdating' ) . "</span>";
            }
            $pagination .= "</div>\n";
        }

        return $pagination;

    } //End fxn pagination
}


/**
 * find the full path to wp-config.php
 */

if ( ! function_exists( 'dsp_find_wp_config_path' ) ) {
    function dsp_find_wp_config_path() {
        $dir = dirname( __FILE__ );
        do {
            if ( file_exists( $dir . "/wp-config.php" ) ) {
                return $dir;
            }
        } while ( $dir = realpath( "$dir/.." ) );

        return null;
    }
}


/**
 * get the timezone,datetime format set in the backend
 */


if ( ! function_exists( 'dsp_get_date_timezone' ) ) {
    function dsp_get_date_timezone() {
        $dateTimeSettingValues = array(
            'timezone'   => get_option( 'timezone_string' ),
            'dateFormat' => get_option( 'date_format' ),
            'timeFormat' => get_option( 'time_format' ),
        );

        return $dateTimeSettingValues;
    }
}


/**
 * use curl if ini_get return false otherwise use file_get_contents
 *
 * @param   [$url] [get full url like http://ip.info ]
 *
 * @return  [json data extracted from url]
 */

if ( ! function_exists( 'dsp_fetchUrl' ) ) {
    function dsp_fetchUrl( $url ) {
        $allowUrlFopen = preg_match( '/1|yes|on|true/i', ini_get( 'allow_url_fopen' ) );
        if ( $allowUrlFopen ) {  //die('file_get_contents called');
            try {
                return file_get_contents( $url );
            } catch ( Exception $e ) {
                return $e->getMessage();
            }
        } elseif ( function_exists( 'curl_init' ) ) {  //die('curl called');
            try {
                $c = curl_init( $url );
                curl_setopt( $c, CURLOPT_RETURNTRANSFER, 1 );
                $contents = curl_exec( $c );
                curl_close( $c );
                if ( is_string( $contents ) ) {
                    return $contents;
                }
            } catch ( Exception $e ) {
                return $e->getMessage();
            }
        }

        return false;
    }
}


/**
 * save current logged in user lattitude and longitude value into user_profile table
 *
 * @param [$position] [it takes position array which contains lattitude and longitude value as  associative array]
 *
 * @return   [boolean value wheather or not the value is saved or not]
 */

if ( ! function_exists( 'dsp_savePosition' ) ) {
    function dsp_savePosition( $position ) {
        global $wpdb;
        global $current_user;
        $cronUpdateLatLng = isset( $position['cronUpdateLatLng'] ) ? $position['cronUpdateLatLng'] : false;
        $user_id          = ( $cronUpdateLatLng === true ) ? array( 'user_id' => $position['userid'] ) : array( 'user_id' => $current_user->ID );
        if ( $cronUpdateLatLng == true ) {
            unset( $position['cronUpdateLatLng'] );
            unset( $position['userid'] );
        }
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $result            = $wpdb->update(
            $dsp_user_profiles,
            $position,
            $user_id,
            array( '%f', '%f' ),
            array( '%d' )
        );

        return $result;
    }

}

add_filter( 'dsp_savePosition', 'dsp_savePosition' );


/**
 * It returns lattitude & longitude from string into array or null value if count($str) < 2
 *
 * @param [$str] [string contains lattitude & longitude ]
 *
 * @return   [array value contains lat and lng]
 */

if ( ! function_exists( 'dsp_extractlatlong' ) ) {
    function dsp_extractlatlong( $str ) {
        $latlong = explode( ",", $str );
        if ( count( $latlong ) == 2 ) {
            $lat     = preg_replace( "%[^0-9.]%", "", $latlong[0] );
            $long    = preg_replace( "%[^0-9.]%", "", $latlong[1] );
            $latlong = array( "lat" => $lat, "lng" => $long );

            return $latlong;
        } else {
            return null;
        }
    }
}


/*
* This filter is used  for getting all the gateways details
* @param [$gateway_name]
* @return [gateway details]
*/

if ( ! function_exists( 'dsp_is_user_profile_exist' ) ) {
    function dsp_is_user_profile_exist() {
        global $wpdb;
        global $current_user;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $user_id           = $current_user->ID;
        $result            = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id AND country_id > 0 AND status_id = 1" );
        if ( ! empty( $result ) && $result > 0 ) {
            return true;
        } else {
            return false;
        }

    }
}


/**
 * This filter is used  for getting all the bank & cheque users details
 *
 * @param [$gateway_name]
 *
 * @return [gateway details]
 */

if ( ! function_exists( 'dsp_get_gateway_details' ) ) {
    function dsp_get_gateway_details( $gateway_name = '' ) {
        global $wpdb;
        global $current_user;
        $dsp_gateways_table = $wpdb->prefix . DSP_GATEWAYS_TABLE;
        $gateway_details    = $wpdb->get_row( "SELECT * FROM $dsp_gateways_table WHERE gateway_name= '$gateway_name' " );

        return $gateway_details;
    }

}

add_filter( 'dsp_get_gateway_details', 'dsp_get_gateway_details' );

/*
* This filter is used  for getting all the bank & cheque users details
*/

if ( ! function_exists( 'dsp_get_payments_users_by_bankwire_cheque' ) ) {
    function dsp_get_payments_users_by_bankwire_cheque( $gateway_id ) {
        global $wpdb;
        global $current_user;
        $dsp_gateways_table      = $wpdb->prefix . DSP_GATEWAYS_TABLE;
        $dsp_temp_payments_table = $wpdb->prefix . DSP_TEMP_PAYMENTS_TABLE;
        $query                   = "SELECT payment_id,user_id,plan_amount,plan_name,plan_days,payment_status,expiration_date
                                    FROM $dsp_gateways_table dgt
                                        INNER JOIN $dsp_temp_payments_table  dtpt
                                        ON  dgt.`gateway_id` = dtpt.`gateway_id`
                                    WHERE dtpt.`gateway_id` = %d";
        $gateway_details         = $wpdb->get_results( $wpdb->prepare( $query, $gateway_id ), ARRAY_A );

        return $gateway_details;
    }

}

add_filter( 'dsp_get_payments_users_by_bankwire_cheque', 'dsp_get_payments_users_by_bankwire_cheque' );

/*
* This filter is used  to extract the states which belongs to country id passed as an arguments
* @param [integer] [Coutnry id]
* @returns [array] [States]
*/

if ( ! function_exists( 'dsp_get_all_States_Or_City' ) ) {
    function dsp_get_all_States_Or_City( $countryId, $city = false ) {

        global $wpdb;
        $dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;
        $dsp_city_table  = $wpdb->prefix . DSP_CITY_TABLE;
        $query           = "SELECT ";
        $query           .= ( $city == true ) ? " city_id,name " : " state_id,name ";
        $query           .= " FROM ";
        $query           .= ( $city == true ) ? " $dsp_city_table " : " $dsp_state_table  ";
        $query           .= " WHERE `country_id` = %d ORDER BY name";
        $lists           = $wpdb->get_results( $wpdb->prepare( $query, $countryId ) );

        return $lists;
    }

}

add_filter( 'dsp_get_all_States_Or_City', 'dsp_get_all_States_Or_City', 10, 2 );

/*
* This filter is used  to check status of trending features
*/

if ( ! function_exists( 'dsp_check_trending_status' ) ) {
    function dsp_check_trending_status( $uId ) {
        global $wpdb;
        $uId                    = get_current_user_id();
        $dsp_user_privacy_table = $wpdb->prefix . DSP_USER_PRIVACY_TABLE;
        $trendingStatus         = $wpdb->get_var( $wpdb->prepare( "Select trending_status FROM $dsp_user_privacy_table WHERE user_id='%d'", $uId ) );

        return $trendingStatus == 'Y' ? true : false;
    }

}

add_filter( 'dsp_check_trending_status', 'dsp_check_trending_status' );

/*
* This function is used  to calculate membership price
* @param [Integer] [membership_plan_id]
* @returns [float][price]
*/

if ( ! function_exists( 'dsp_get_membership_amount' ) ) {
    function dsp_get_membership_amount( $membership_plan_id ) {
        global $wpdb;
        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $exist_membership_plan = $wpdb->get_row( $wpdb->prepare( "SELECT price FROM $dsp_memberships_table where membership_id='%d'", $membership_plan_id ) );
        $price                 = isset( $exist_membership_plan ) ? $exist_membership_plan->price : '';

        return $price;
    }
}

/*
* Discount code calculation
* @param [$item_name]
* @return [price for getting item]
*/

if ( ! function_exists( 'dsp_get_item_price' ) ) {
    function dsp_get_item_price( $item_name ) {
        global $wpdb;
        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $amount                = 0;
        $membership            = $wpdb->get_row( "SELECT price FROM $dsp_memberships_table where display_status='Y' AND name='$item_name'" );
        if ( ! empty( $membership ) && isset( $membership ) ) {
            return $membership->price;
        }

        return $amount;
    }
}

/*
* checks coupan code is alreay used by the user or not
* @param [$user_id] [$discount_code]
* @return [boolean value]
*/

if ( ! function_exists( 'dsp_is_coupan_used' ) ) {
    function dsp_is_coupan_used( $user_id, $discount_code ) {
        $existCoupan = get_user_meta( $user_id, 'discount_code', true );
        if ( isset( $existCoupan ) && ! empty( $existCoupan ) && $existCoupan == $discount_code ) {
            return true;
        } else {
            return false;
        }
    }
}


/*
* checks coupan code is alreay used by the user or not
* @param  [$discount_code]
* @return [boolean value]
*/

if ( ! function_exists( 'dsp_is_valid_code' ) ) {
    function dsp_is_valid_code( $discount_code ) {
        global $wpdb;
        $dsp_discount_codes_table = $wpdb->prefix . DSP_DISCOUNT_CODES_TABLE;
        $existCoupan              = $wpdb->get_row( "SELECT id FROM $dsp_discount_codes_table WHERE code='$discount_code'" );
        if ( isset( $existCoupan ) && ! empty( $existCoupan ) ) {
            return true;
        } else {
            return false;
        }
    }
}

/*
* calculate discount price
* @param  [$discount_code]
* @return [calculated discount price]
*/

if ( ! function_exists( 'dsp_get_discount_price' ) ) {
    function dsp_get_discount_price( $discount_code, $amount ) {
        global $wpdb;
        $dsp_discount_codes_table = $wpdb->prefix . DSP_DISCOUNT_CODES_TABLE;
        $discount_information     = $wpdb->get_row( "SELECT amount,type,uses FROM $dsp_discount_codes_table WHERE code='$discount_code'" );
        $value                    = 0;
        if ( isset( $discount_information ) && ! empty( $discount_information ) ) {
            if ( isset( $discount_code ) && ! empty( $discount_code ) ) {
                /*$wpdb->update($dsp_discount_codes_table,
                                    array('uses'=>$uses),
                                    array('code'=>$discount_code),
                                    array('%d'),
                                    array('%s')
                                );*/
            }
            $value = $discount_information->amount;

            if ( $discount_information->type == '%' ) {
                $value = $amount * ( $value / 100 );
            }


        }

        return $value;
    }
}

/*
* All calcuation after valid & unique coupan code is used for discount price using wp_ajax
* @return [Data with message , amount before coupan code used & after used it]
*/

if ( ! function_exists( 'dsp_coupan_code_calculation' ) ) {
    function dsp_coupan_code_calculation() {
        $nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
        if ( dsp_verify_nonce_value( $nonce ) ) :
            $discount_code                   = isset( $_POST['code'] ) ? $_POST['code'] : '';
            $item_name                       = isset( $_POST['item_name'] ) ? $_POST['item_name'] : '';
            $user_id                         = isset( $_POST['id'] ) ? $_POST['id'] : '';
            $amount                          = dsp_get_item_price( $item_name );
            $_SESSION['afterDiscountAmount'] = $amount;

            $_SESSION['code'] = $discount_code;
            $isValideCode     = dsp_is_valid_code( $discount_code );
            ob_clean();
            if ( $isValideCode ) {
                $isCoupanUsed = dsp_is_coupan_used( $user_id, $discount_code );
                if ( ! $isCoupanUsed ) {
                    $amount         = dsp_get_item_price( $item_name );
                    $discount_price = dsp_get_discount_price( $discount_code, $amount );
                    if ( $discount_price <= $amount ) {
                        $afterDiscountAmount             = $amount - $discount_price;
                        $afterDiscountAmount             = round( $afterDiscountAmount, 2 );
                        $_SESSION['afterDiscountAmount'] = $afterDiscountAmount;
                        $data['message']                 = '';
                        $data['amount']                  = $afterDiscountAmount;
                        $data['discount']                = $discount_price;
                    } else {
                        $data['message']  = '';
                        $data['amount']   = $amount;
                        $data['discount'] = $discount_price;

                    }
                    echo json_encode( $data );
                } else {
                    $data['message'] = __( 'Coupan already used','wpdating' );
                    echo json_encode( $data );
                }
            } else {
                $data['message'] = __( 'Wrong Coupan Code Entered','wpdating' );
                echo json_encode( $data );
            }
            die();
        endif;
    }
}


add_action( 'wp_ajax_dsp_coupan_code_calculation', 'dsp_coupan_code_calculation' );


/*
* This function is  used to verify language name
* @return [boolean value]
*/

if ( ! function_exists( 'dsp_verify_lang_name' ) ) {
    function dsp_verify_lang_name( $nonce = '' ) {
        global $wpdb;
        $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $nonce                     = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
        $langName                  = isset( $_POST['langName'] ) ? $_POST['langName'] : '';
        if ( ! wp_verify_nonce( $nonce, 'lang-nonce' ) ) :
            die( __( 'Security Viloation','wpdating' ) );
        endif;
        $count        = $wpdb->get_var( "SELECT COUNT(*)
                                    FROM `$dsp_language_detail_table`
                                        WHERE `language_name` LIKE '%$langName%'"
        );
        $msg['count'] = $count;
        echo json_encode( $msg );
        die();

    }
}
add_action( 'wp_ajax_dsp_verify_lang_name', 'dsp_verify_lang_name' );

/*
* checks nonce value to prevent from csrf attacks
* @return [boolean value]
*/

if ( ! function_exists( 'dsp_verify_nonce_value' ) ) {
    function dsp_verify_nonce_value( $nonce = '' ) {
        if ( ! wp_verify_nonce( $nonce, 'discount-code-nonce' ) ) :
            die( __( 'Security Viloation','wpdating' ) );
        endif;

        return true;
    }
}


/**
 * This function is used to get last user_profile_id in dsp_user_profile table
 * @return [int] [$lastUserProfileId]
 */

if ( ! function_exists( 'dsp_get_last_user_id' ) ) {
    function dsp_get_last_user_id() {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $query             = "SELECT dup.`user_profile_id`
                    FROM $dsp_user_profiles dup ";
        $query             .= " ORDER BY user_profile_id DESC LIMIT 1 ";
        $lastUserProfileId = $wpdb->get_var( $query );

        return $lastUserProfileId;

    }
}
add_filter( 'dsp_get_last_user_id', 'dsp_get_last_user_id' );


/**
 *  This function is used to get unique users from user profiles table
 *
 * @param   [int] [start]
 *
 * @return  [Object][Unique users whose lattitude and longitude is not updated]
 */

if ( ! function_exists( 'dsp_get_eighteen_unique_users' ) ) {
    function dsp_get_eighteen_unique_users( $start ) {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
        $dsp_city_table    = $wpdb->prefix . DSP_CITY_TABLE;
        $dsp_state_table   = $wpdb->prefix . DSP_STATE_TABLE;
        $query             = "SELECT dup.`user_profile_id`,dup.`user_id`,country.`name`  country,city.`name` city,state.`name` state
                    FROM $dsp_user_profiles dup
                        INNER JOIN $dsp_country_table country ON dup.country_id = country.country_id
                        INNER JOIN $dsp_city_table city ON dup.city_id = city.city_id
                        LEFT JOIN $dsp_state_table state ON dup.state_id = state.state_id ";
        $query             .= " ORDER BY `user_profile_id` ASC LIMIT %d,18 ";
        $users             = $wpdb->get_results( $wpdb->prepare( $query, $start ) );

        return ( isset( $users ) && count( $users > 0 ) ) ? $users : null;
    }
}


/**
 *  The wordpress builtin filter  create_schedule is used to create custom time interval 10 minutes
 */

add_filter( 'cron_schedules', 'dsp_cron_schedules' );

if ( ! function_exists( 'dsp_cron_schedules' ) ) {
    function dsp_cron_schedules( $schedules ) {
        $schedules['ten-minutes'] = array(
            'interval' => 600,
            'display'  => __( 'Every Ten Minutes' ),
        );

        return $schedules;
    }
}


/**
 * This function is used to automatically update each users lattitude and longitude values using wp_cron  and google api
 *
 */
if ( ! function_exists( 'dsp_auto_update_lat_lng' ) ) {
    function dsp_auto_update_lat_lng() {
        $lastUserProfileId        = apply_filters( 'dsp_get_last_user_id', '' );
        $optionName               = 'last_user_id_latlng_updated';
        $lastUpdatedUserProfileId = get_option( $optionName );
        if ( $lastUpdatedUserProfileId == $lastUserProfileId ) {
            wp_clear_scheduled_hook( 'dsp_every_ten_minutes_event_hook' );
        } else {
            if ( ! wp_next_scheduled( 'dsp_every_ten_minutes_event_hook' ) ) {
                wp_schedule_event( time(), 'ten-minutes', 'dsp_every_ten_minutes_event_hook' );
            }

        }
    }
}


add_action( 'init', 'dsp_auto_update_lat_lng' );


/**
 *  This is custom hook to automatically update eighteen users lattitude and longitude in the interval of ten minutes
 *  using wp_cron
 */

if ( ! function_exists( 'dsp_update_lat_lng_values' ) ) {
    function dsp_update_lat_lng_values() {
        $optionName  = 'last_user_id_latlng_updated';
        $lastUserId  = get_option( $optionName );
        $start       = ( $lastUserId !== false ) ? $lastUserId : 0;
        $uniqueUsers = dsp_get_eighteen_unique_users( $start );
        if ( ! empty( $uniqueUsers ) ) {
            $lastUser   = count( $uniqueUsers ) > 0 ? end( $uniqueUsers ) : '';
            $lastUserId = ( isset( $lastUser ) && ! empty( $lastUser ) ) ? $lastUser->user_profile_id : 0;
            if ( get_option( $optionName ) !== false ) {
                // The option already exists, so we just update it.
                update_option( $optionName, $lastUserId );

            } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                $deprecated = null;
                $autoload   = 'no';
                add_option( $optionName, $lastUserId, $deprecated, $autoload );
            }
            foreach ( $uniqueUsers as $user ) {
                if ( isset( $user ) && ( count( $user ) > 0 ) && ! empty( $user ) ) {
                    $userid    = $user->user_id;
                    $address   = array();
                    $address[] = $user->country;
                    $address[] = $user->state;
                    $address[] = $user->city;
                    $address   = array_filter( $address );
                    $address   = urlencode( implode( ',', $address ) );
                    try {
                        $latlangValues = wp_remote_get( "https://maps.google.com/maps/api/geocode/json?address=" . $address );
                        if ( $latlangValues['response']['code'] == 200 ) {
                            $latlangValues = array_key_exists( 'body', $latlangValues ) ? $latlangValues['body'] : '';
                            $latlangValues = json_decode( $latlangValues );
                            if ( $latlangValues->status == 'OK' ) {
                                $lat   = isset( $latlangValues->results[0]->geometry->location->lat ) ? $latlangValues->results[0]->geometry->location->lat : '';
                                $lang  = isset( $latlangValues->results[0]->geometry->location->lng ) ? $latlangValues->results[0]->geometry->location->lng : '';
                                $lt_lg = array(
                                    "lat"              => $lat,
                                    "lng"              => $lang,
                                    "userid"           => $userid,
                                    'cronUpdateLatLng' => true
                                );
                                apply_filters( 'dsp_savePosition', $lt_lg );
                            }
                            $log = 'The following UserId :' . $userid . ' has got the status ' . $latlangValues->status;
                            dsp_write_log( $log );

                        }
                    } catch ( Exception $e ) {
                        $log = 'The following Exception occured: ' . $e->getMessage();
                        dsp_write_log( $log );
                    }

                }
            }

        }
    }
}

add_action( 'dsp_every_ten_minutes_event_hook', 'dsp_update_lat_lng_values' );


/*
* This filter is used  to display the one to one chat pop up notification
* @returns [sting] [$output as html format]
*/

if ( ! function_exists( 'dsp_get_single_chat_popup_notification' ) ) {
    function dsp_get_single_chat_popup_notification( $notification = '' ) {
        $output = '<div id="chat_popup"  class="dspdp-row"></div>' . $notification;
        echo $output;
    }

}

add_filter( 'dsp_get_single_chat_popup_notification', 'dsp_get_single_chat_popup_notification' );

/**
 * This action is used  to set new language id into the session table for each users whose language id is deleted
 *
 * @param   [int] [$oldLid language id to be updated]
 */
if ( ! function_exists( 'dsp_update_each_users_session_language_id' ) ) {
    function dsp_update_each_users_session_language_id( $oldLid ) {
        global $wpdb;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $query                      = " SELECT `language_id` FROM $dsp_language_detail_table WHERE display_status = '1'";
        $languageId                 = $wpdb->get_var( $query );
        $query                      = " SELECT `user_id` FROM $dsp_session_language_table WHERE language_id = '%d'";
        $usersToUpdate              = $wpdb->get_results( $wpdb->prepare( $query, $oldLid ) );
        foreach ( $usersToUpdate as $user ) {
            $lId     = array( 'language_id' => $languageId );
            $user_id = array( 'user_id' => $user->user_id );
            $result  = $wpdb->update(
                $dsp_session_language_table,
                $lId,
                $user_id,
                array( '%d' ),
                array( '%d' )
            );
        }
    }

}

add_action( 'dsp_update_each_users_session_language_id', 'dsp_update_each_users_session_language_id' );

/**
 * This action is used  to set any random language as default
 *
 * @param   [int] [$lid language id to be deleted]
 */
if ( ! function_exists( 'dsp_set_another_default_language' ) ) {
    function dsp_set_another_default_language( $lid = '' ) {
        global $wpdb;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $query                      = "SELECT `language_id` FROM $dsp_language_detail_table WHERE  language_id <> '%d'  ORDER BY RAND() LIMIT 1";
        $newlangId                  = $wpdb->get_var( $wpdb->prepare( $query, $lid ) );
        $result                     = $wpdb->update(
            $dsp_language_detail_table,
            array( 'display_status' => '0' ),
            array( 'language_id' => $lid ),
            array( '%s' ),
            array( '%d' )
        );
        $result                     = $wpdb->update(
            $dsp_language_detail_table,
            array( 'display_status' => '1' ),
            array( 'language_id' => $newlangId ),
            array( '%s' ),
            array( '%d' )
        );
        do_action( 'dsp_update_each_users_session_language_id', $lid );
    }

}

add_action( 'dsp_set_another_default_language', 'dsp_set_another_default_language' );

// emails admin when new user is registered.

if ( ! function_exists( 'dsp_email_admin_alert' ) ) {
    function dsp_email_admin_alert() {
        // check if email_admin is enabled or not
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $check_email_admin          = $wpdb->get_row( "SELECT setting_status FROM $dsp_general_settings_table WHERE setting_name = 'email_admin'" );
        if ( $check_email_admin->setting_status == 'Y' ) {
            $email   = get_bloginfo( 'admin_email' );
            $subject = 'New User Registered';
            $message = 'A new member has been created on your site.';
            $message .= '<br />User Name: ' . $_REQUEST['username'];
            $message .= '<br />Email: ' . $_REQUEST['email'];
            $message .= '<br/><a href="' . get_site_url() . '/members/' . $_REQUEST['username'] . '">View User Profile</a>';
            $message .= '<br /><br /><br /><i>NOTE: Auto generated from site</i>';
            wp_mail( $email, $subject, $message );

        }

    }
}
add_action( 'user_register', 'dsp_email_admin_alert' );

/**
 * This filter is used to DSP admin setting for make private photo enable or not
 * @return [boolean] [It returns true if status is 'Y'  else false]
 */

if ( ! function_exists( 'dsp_is_make_private_on' ) ) {
    function dsp_is_make_private_on() {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $check_private_photo        = $wpdb->get_row( "SELECT `setting_status` FROM $dsp_general_settings_table WHERE setting_name = 'private_photo'" );

        return ( $check_private_photo->setting_status == 'Y' ) ? true : false;
    }

}
add_filter( 'dsp_is_make_private_on', 'dsp_is_make_private_on' );

/**
 * This filter is used to get message as link for user according to gender of that user
 * @return [Integer] [user_id] [It's an user_id for user whose link to be sent as message]
 */

if ( ! function_exists( 'dsp_get_message_based_on_gender' ) ) {
    function dsp_get_message_based_on_gender( $user_id ) {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $query             = "SELECT `gender` FROM $dsp_user_profiles WHERE user_id = %d";
        $gender            = $wpdb->get_var( $wpdb->prepare( $query, $user_id ) );
        $nonCoupleMessage  = " <a href='" . ROOT_LINK . get_username( $user_id ) . "' > " . get_username( $user_id ) . "</a>";
        $coupleMessage     = " <a href='" . ROOT_LINK . get_username( $user_id ) . "/my_profile/" . "' >  " . get_username( $user_id ) . "</a>";

        return $gender == 'C' ? $coupleMessage : $nonCoupleMessage;
    }

}

add_filter( 'dsp_get_message_based_on_gender', 'dsp_get_message_based_on_gender' );


/**
 * This is custom action hook to used for automatically logged in after register
 * @return [boolean] [It returns true if status is 'Y'  else false]
 */

add_action( 'dsp_custom_auto_login', 'dsp_custom_auto_login' );

if ( ! function_exists( 'dsp_custom_auto_login' ) ) {
    function dsp_custom_auto_login( $user_id ) {
        return do_action( 'dsp_auto_logged_in', $user_id );
    }
}

add_action( 'init', 'dsp_start_output_buffer' );

/**
 *  This function is used to start output buffering during wordpress initilize
 */

if ( ! function_exists( 'dsp_start_output_buffer' ) ) {
    function dsp_start_output_buffer() {
        ob_start();
    }
}


//add_action('wp_footer', 'dsp_end_output_buffer');

/**
 *  This function is used to end & flush output buffering
 */

/*if(!function_exists('dsp_end_output_buffer')){
    function dsp_end_output_buffer()
    {
        ob_end_flush();
    }
}*/


/**
 *  This function is used to get currently loggedin user country & city
 */

if ( ! function_exists( 'dsp_get_current_user_city_country' ) ) {
    function dsp_get_current_user_city_country() {
        global $wpdb;
        global $current_user;
        $user_id           = $current_user->ID;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
        $dsp_city_table    = $wpdb->prefix . DSP_CITY_TABLE;
        $query             = "SELECT country_id,city_id,lat,lng
                        FROM $dsp_user_profiles profile
                            WHERE profile.`user_id` = %d";
        $results           = $wpdb->get_row( $wpdb->prepare( $query, $user_id ) );

        return $results;

    }
}


/**
 *  This function is used to get all near user country,city,user_id of loggedin user
 *
 * @param   [array] [$filters] [This is filters for near me results]
 */

if ( ! function_exists( 'dsp_get_near_users' ) ) {
    function dsp_get_near_users( $filters = array() ) {
        global $wpdb;
        global $current_user;
        extract( $filters );
        $user_id           = $current_user->ID;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
//        $dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;
//        $dsp_city_table    = $wpdb->prefix . DSP_CITY_TABLE;
        $userDetails       = dsp_get_current_user_city_country();
//        $userCountryId     = '';
//        $userCityId        = '';
        $lat               = '';
        $lng               = '';
        $distance          = 2000;
        $constantValues    = 3959; //  To calculate distance in miles
        if ( isset( $userDetails ) && ! empty( $userDetails ) ) {
//            $userCountryId = $userDetails->country_id;
//            $userCityId    = $userDetails->city_id;
            $lat           = $userDetails->lat;
            $lng           = $userDetails->lng;
        }
//        $query = "SELECT profile.`user_id`,profile.`country_id`,profile.`city_id`,country.`name` country ,city.`name`  city ,profile. `make_private`,profile. `lat` , profile. `lng` ";

        $query = "SELECT profile.user_id, profile.lat, profile.lng, profile.make_private";

        if ( ! empty( $lat ) && ! empty( $lng ) ) {
            $query .= ",( $constantValues * acos( cos( radians({$lat}) ) * cos( radians( `lat` ) ) * cos( radians( `lng` ) - radians({$lng}) ) + sin( radians({$lat}) ) * sin( radians( `lat`) ) ) ) AS distance  ";
        }else{
            return new WP_Error();
        }
        $query .= " FROM $dsp_user_profiles profile 
                    WHERE profile.`user_id` != $user_id";
//                            INNER JOIN $dsp_country_table country ON  profile.country_id = country.country_id
//                            INNER JOIN $dsp_city_table city ON  profile.city_id = city.city_id
//                            WHERE profile.`user_id` != $user_id";
//                                             AND profile.`country_id`= $userCountryId
//                                             AND profile.`city_id`= $userCityId ";
        if ( $age_from >= 18 ) {
            $query .= " AND ((year(CURDATE())-year(profile.age)) >= '" . $age_from . "') AND ((year(CURDATE())-year(profile.age)) < '" . $age_to . "') AND ";
        }
        if ( $gender == 'M' ) {
            $query .= " profile.gender='M' ";
        } else if ( $gender == 'F' ) {
            $query .= " profile.gender='F' ";
        } else if ( $gender == 'C' ) {
            $query .= " profile.gender='C' ";
        } else if ( $gender == 'all' ) {
            $query .= " profile.gender IN('M','F','C') ";
        }
        $query   .= ( ! empty( $lat ) && ! empty( $lng ) && ! empty( $distance ) ) ? " HAVING distance < $distance " : ' ';
        $results = $wpdb->get_results( $query );

        return $results;

    }
}

/**
 * This function is used to check the status of random online members settings
 * @return  [boolean] [It return true and false value depends upon the random online setting at backend]
 */


if ( ! function_exists( 'dsp_check_online_setting' ) ) {
    function dsp_check_online_setting() {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $query                      = "SELECT setting_status FROM $dsp_general_settings_table WHERE setting_name = 'random_online_members'";
        $settingStatus              = $wpdb->get_var( $query );

        return $settingStatus == 'Y' ? true : false;
    }
}


/**
 * This function is used to check the status of random online members settings
 * @return  [boolean] [It return true and false value depends upon the random online setting at backend]
 */


if ( ! function_exists( 'dsp_check_discount_code_setting' ) ) {
    function dsp_check_discount_code_setting() {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $query                      = "SELECT setting_status FROM $dsp_general_settings_table WHERE setting_name = 'discount_code'";
        $settingStatus              = $wpdb->get_var( $query );

        return $settingStatus == 'N' ? true : false;
    }
}

/**
 *  This function is used validate zipcode based on
 *
 * @param  [zipCode]
 */

/*if(!function_exists('dsp_is_valid_zipcode')){
    function dsp_is_valid_zipcode(){
        $zipCode = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
        $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : '';
        if (dsp_verify_nonce_value( $nonce) ) :
            global $wpdb;
            $dsp_zipcode_table = $wpdb->prefix . DSP_ZIPCODES_TABLE;
            $findzipcodelatlng = $wpdb->get_row("SELECT * FROM $dsp_zipcode_table WHERE zipcode = '$zipCode'");
            $data['msg'] = !empty($findzipcodelatlng) ? true : false;
            echo json_encode($data);
        endif;
    }
}


add_action('wp_ajax_dsp_is_valid_zipcode','dsp_is_valid_zipcode');*/

if ( ! function_exists( 'dsp_remove_image' ) ) {
    function dsp_remove_image( $image ) {
        $image_files = scandir( $image );
        chdir( $image );
        foreach ( $image_files as $image_file ) {
            if ( $image_file != "." and $image_file != ".." ) {
                if ( is_dir( $image_file ) ) {
                    dsp_remove_image( $image_file );
                } else {
                    unlink( $image_file );
                }//"""""""end of if
            }//""""""-end of if
        }//"""""-end of foreach
        chdir( ".." );
        rmdir( $image );
    }
}


if ( ! function_exists( 'dsp_remove_video' ) ) {
    function dsp_remove_video( $name ) {
        $ars = scandir( $name );
        chdir( $name );
        foreach ( $ars as $ar ) {
            if ( $ar != "." and $ar != ".." ) {
                if ( is_dir( $ar ) ) {
                    //echo "found directory $ar <br />";
                    remove_dir( $ar );
                } else {
                    //echo "found a file $ar <br />";
                    unlink( $ar );
                }//"""""""end of if
            }//""""""-end of if
        }//"""""-end of foreach
        chdir( ".." );
        rmdir( $name );
    }
}


if ( ! function_exists( 'dsp_remove_audio' ) ) {
    function dsp_remove_audio( $audio ) {
        $audio_files = scandir( $audio );
        chdir( $audio );
        foreach ( $audio_files as $audio_file ) {
            if ( $audio_file != "." and $audio_file != ".." ) {
                if ( is_dir( $audio_file ) ) {
                    //echo "found directory $ar <br />";
                    remove_dir( $audio_file );
                } else {
                    //echo "found a file $ar <br />";
                    unlink( $audio_file );
                }//"""""""end of if
            }//""""""-end of if
        }//"""""-end of foreach
        chdir( ".." );
        rmdir( $audio );
    }
}


/**
 *  This is an action to perform when user is deleted from backend of core wordpress
 *
 */

if ( ! function_exists( 'dsp_delete_profile_user' ) ) {
    function dsp_delete_profile_user( $user_id ) {
        global $wpdb;
        include_once( WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php" );
        include_once( WP_DSP_ABSPATH . "include_dsp_tables.php" );
        if ( ! defined( 'WP_CONTENT_DIR' ) ) {
            define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
        }
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_email_templates_table = $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_users_table = $wpdb->prefix . DSP_USERS_TABLE;
        $dsp_members_photos = $wpdb->prefix . "dsp_members_photos";
        $dsp_member_videos_table = $wpdb->prefix . DSP_MEMBER_VIDEOS_TABLE;
        $dsp_member_audios_table = $wpdb->prefix . DSP_MEMBER_AUDIOS_TABLE;
        $dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
        $dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
        $dsp_profile_question_details_table = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
        $dsp_user_partner_profiles_table = $wpdb->prefix . DSP_USER_PARTNER_PROFILES_TABLE;
        $dsp_members_partner_photos_table = $wpdb->prefix . DSP_MEMBERS_PARTNER_PHOTOS_TABLE;
        $dsp_partner_profile_question_details_table = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
        $dsp_show_profile_table = $wpdb->prefix . DSP_LIMIT_PROFILE_VIEW_TABLE;

        $profileid       = $wpdb->get_var( "SELECT `user_profile_id` FROM $dsp_user_profiles WHERE user_id='$user_id'" );
        $profile_user_id = $wpdb->get_row( "SELECT * FROM $dsp_user_profiles WHERE user_profile_id='$profileid'" );
        $email_template  = $wpdb->get_row( "SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='4'" );

        $reciver_details        = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID='$user_id'" );
        $reciver_name           = $reciver_details->display_name;
        $receiver_email_address = $reciver_details->user_email;
        $sender_details         = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID='1'" );
        $sender_name            = $sender_details->display_name;
        //$site_url=$wpdb->get_row("SELECT * FROM wp_options WHERE option_name='siteurl'");
        // $sender_email_address=$site_url->option_value;
        // $sender_email_address = str_replace ("http://", '', $sender_email_address);
        $root_url = get_bloginfo( 'url' );
        //$url=add_query_arg( array('pid' =>3), $root_url);
        $url               = site_url();
        $email_subject     = $email_template->subject;
        $mem_email_subject = $email_subject;

        $email_message = $email_template->email_body;
        $email_message = str_replace( "<#RECEIVER_NAME#>", $reciver_name, $email_message );
        $email_message = str_replace( "<#SENDER_NAME#>", $sender_name, $email_message );
        $email_message = str_replace( "<#URL#>", $url, $email_message );

        $MemberEmailMessage = $email_message;

        // Add User Data to Deleted Users Table //
        $infoFromUsersTable        = $wpdb->get_row( "SELECT * FROM $dsp_users_table WHERE ID=$user_id" );
        $infoFromProfilesTable     = $wpdb->get_row( "SELECT * FROM $dsp_user_profiles WHERE user_id=$user_id" );
        $infoFromMembersPhotoTable = $wpdb->get_row( "SELECT * FROM $dsp_members_photos WHERE user_id=$user_id" );
        $current_datetime          = date( 'Y-m-d H:i:s' );
        $wpdb->query( "INSERT INTO " . $wpdb->prefix . DSP_DELETED_USERS_TABLE . " SET
                                user_id='$infoFromProfilesTable->user_id',
                                user_profile_id='$infoFromProfilesTable->user_profile_id',
                                country_id='$infoFromProfilesTable->country_id',
                                city_id='$infoFromProfilesTable->city_id',
                                gender='$infoFromProfilesTable->gender',
                                user_login='$infoFromUsersTable->user_login',
                                user_pass='$infoFromUsersTable->user_pass',
                                user_email='$infoFromUsersTable->user_email',
                                display_name='$infoFromUsersTable->display_name',
                                user_registered='$infoFromUsersTable->user_registered',
                                deleted_on='$current_datetime',
                                picture='$infoFromMembersPhotoTable->picture'
               " );

        // Copy User Profile Picture to Deleted Users Profile Picture //
        $src = WP_CONTENT_DIR . '/uploads/dsp_media' . '/user_photos/user_' . $user_id . '/';
        $dst = WP_CONTENT_DIR . '/uploads/dsp_media/deleted_users/';
        if ( ! is_dir( $dst ) ) {
            mkdir( $dst, 0777, true );
        }
        $files = glob( $src . "*.*" );
        if ( ! empty( $files ) && $files != false ) {
            foreach ( $files as $file ) {
                $filename   = 'user_' . str_replace( $src, '', $file );
                $file_to_go = str_replace( $src, $dst, $file );
                copy( $file, $file_to_go );
            }
        }
        // End Copy
        // End Add User Data

        //send_email($receiver_email_address, $sender_name, $sender_name, $mem_email_subject, $MemberEmailMessage, $message_html = "");
        //$wpdb->query("INSERT INTO $dsp_admin_emails_table SET rec_user_id='$user_id',email_template_id='4', message='$MemberEmailMessage',mail_sent_date='$messae_send_date'");
//***************************** Start Delete Video folder ***************************************
        $pluginpath = WP_CONTENT_DIR . '/uploads/dsp_media';
        $name       = $pluginpath . '/user_videos/user_' . $user_id;
        if ( file_exists( $name ) ) {
            dsp_remove_video( $name );
            $wpdb->query( "DELETE FROM $dsp_member_videos_table WHERE user_id = '$user_id'" );
        }
//***************************** End Delete Video folder ***************************************
//***************************** Start Delete Audio folder ***************************************
        $audio = $pluginpath . '/user_audios/user_' . $user_id;
        if ( file_exists( $audio ) ) {
            dsp_remove_audio( $audio );
            $wpdb->query( "DELETE FROM $dsp_member_audios_table WHERE user_id = '$user_id'" );
        }
//***************************** End Delete Audio folder  ***************************************
//***************************** Start Delete image folder  ***************************************
        $image = $pluginpath . '/user_photos/user_' . $user_id;
        if ( file_exists( $image ) ) {
            dsp_remove_image( $image );
            $wpdb->query( "DELETE FROM $dsp_members_photos WHERE user_id = '$user_id'" );
        }
//***************************** End Delete image folder  ***************************************
        $wpdb->query( "DELETE FROM $dsp_user_profiles WHERE user_profile_id = '$profileid'" );
        $wpdb->query( "DELETE FROM $dsp_user_albums_table WHERE user_id = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_galleries_photos WHERE user_id = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_users_table WHERE ID = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_profile_question_details_table WHERE user_id = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_user_partner_profiles_table WHERE user_id  = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_members_partner_photos_table WHERE user_id = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_partner_profile_question_details_table WHERE user_id = '$user_id'" );
        $wpdb->query( "DELETE FROM $dsp_show_profile_table WHERE user_id = '$user_id'" );

        // delete from other tables - start
        $toDeleteTables = array(
            'usermeta',
            DSP_USER_VIRTUAL_GIFT_TABLE,
            DSP_MY_BLOGS_TABLE,
            DSP_MATCH_CRITERIA_TABLE,
            DSP_MEET_ME_TABLE,
            DSP_USER_NOTIFICATION_TABLE,
            DSP_USER_ONLINE_TABLE,
            DSP_USER_PRIVACY_TABLE,
            DSP_USER_SEARCH_CRITERIA_TABLE,
            DSP_NEWS_FEED_TABLE,
            //DSP_CREDITS_USAGE_TABLE,
            DSP_DATE_TRACKER_TABLE,
            DSP_RATING_USER_PROFILE_TABLE,
            DSP_SKYPE_TABLE,
            DSP_TEMP_INTEREST_TAGS_TABLE
        );
        foreach ( $toDeleteTables as $toDeleteTable ) {
            $wpdb->query( "DELETE FROM " . $wpdb->prefix . $toDeleteTable . " WHERE user_id = '$user_id'" );
        }

        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_MY_FRIENDS_TABLE . " WHERE user_id=$user_id OR friend_uid=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE . " WHERE user_id=$user_id OR favourite_user_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_COUNTER_HITS_TABLE . " WHERE user_id=$user_id OR member_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_USER_COMMENTS . " WHERE user_id=$user_id OR member_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_NOTIFICATION_TABLE . " WHERE user_id=$user_id OR member_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE . " WHERE user_id=$user_id OR block_member_id=$user_id" );

        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_CHAT_ONE_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_CHAT_REQUEST_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_MEMBER_WINKS_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_EMAILS_TABLE . " WHERE sender_id=$user_id OR receiver_id=$user_id" );
        $wpdb->query( "DELETE FROM " . $wpdb->prefix . DSP_CHAT_TABLE . " WHERE sender_id=$user_id" );
        //  delete from other tables - end
    }
}
add_action( 'delete_user', 'dsp_delete_profile_user' );

/**
 *  This function is used for register firstname & lastname of register user into usermeta
 *
 * @param array $userDetails register user details
 * @param Integer $userId User id
 */
if ( ! function_exists( 'dsp_add_user_details_in_meta_table' ) ) {
    function dsp_add_user_details_in_meta_table( $userDetails = array(), $userId ) {
        if ( isset( $userDetails ) && ( count( $userDetails ) > 0 ) ) {
            foreach ( $userDetails as $key => $value ) {
                update_user_meta( $userId, $key, $value );
            }
        }
    }
}

/**
 *  This function is used to get gender of currently logged in user
 *`
 */


if ( ! function_exists( 'dsp_get_logged_in_user_gender' ) ) {
    function dsp_get_logged_in_user_gender( $freeTrialGender ) {
        global $wpdb;
        $userId            = get_current_user_id();
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $gender            = $wpdb->get_var( $wpdb->prepare( "SELECT `gender` FROM $dsp_user_profiles  WHERE `user_id` = '%d' ", $user_id ) );

        return $gender;
    }
}


/**
 *  This function returns user full name
 *
 * @param   [Integer] [user_id] [currently loggen user id]
 *
 * @return  [string]
 */

if ( ! function_exists( 'dsp_get_fullname' ) ) {
    function dsp_get_fullname( $userId ) {
        $userMeta = get_user_meta( $userId );

        return $userMeta['first_name'][0] . ' ' . $userMeta['last_name'][0];
    }
}

/**
 *  This function check for zero membership plan
 *
 * @param   [Integer] [membership_plan_id] [membership plan Id]
 *
 * @return  [boolean]
 */

if ( ! function_exists( 'dsp_check_zero_membership' ) ) {
    function dsp_check_zero_membership( $membership_plan_id ) {
        global $wpdb;
        $dsp_memberships_table = $wpdb->prefix . DSP_MEMBERSHIPS_TABLE;
        $memberShipAmount      = $wpdb->get_var( $wpdb->prepare( " SELECT `price` FROM $dsp_memberships_table WHERE membership_id='%d'"
            , $membership_plan_id
        )
        );

        return ( $memberShipAmount === 0 ) ? true : false;
    }
}


/**
 *  This function is used to send an email for verification to reset password
 *
 * @param   [Integer] [membership_plan_id] [membership plan Id]
 *
 * @return  [boolean]
 */

if ( ! function_exists( 'dsp_verify_email' ) ) {
    function dsp_verify_email() {
        global $wpdb;
        include_once( WP_DSP_ABSPATH . "files/includes/dsp_mail_function.php" );
        $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
        $dsp_user_table            = $wpdb->prefix . DSP_USERS_TABLE;
        $user_n_email              = isset( $_POST['email'] ) ? $_POST['email'] : '';
        $result                    = array();
        $check_user_exist          = $wpdb->get_row( "SELECT * FROM `$dsp_user_table` where user_login like '$user_n_email' or user_email like '$user_n_email'" );
        if ( count( $check_user_exist ) > 0 ) {
            $email_template         = $wpdb->get_row( "SELECT * FROM $dsp_email_templates_table WHERE mail_template_id='22'" );
            $reciver_details        = $wpdb->get_row( "SELECT * FROM $dsp_user_table WHERE ID='" . $check_user_exist->ID . "'" );
            $reciver_name           = $reciver_details->user_login;
            $receiver_email_address = $reciver_details->user_email;
            $admin_email            = get_option( 'admin_email' );
            $from                   = $admin_email;
            $url                    = site_url();
            $email_subject          = $email_template->subject;
            $mem_email_subject      = $email_subject;
            $email_message          = $email_template->email_body;
            $email_message          = str_replace( "[SITE_URL]", $url, $email_message );
            $email_message          = str_replace( "[USERNAME]", $reciver_name, $email_message );
            $email_message          = str_replace( "[RESET_URL]", get_site_url() . '/members/reset_password/?key=' . base64_encode( $check_user_exist->ID . ',' . $check_user_exist->user_pass ), $email_message );
            $MemberEmailMessage     = $email_message;
            // wp_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $wpdating_email   = Wpdating_email_template::get_instance();
            $result_mail      = $wpdating_email->send_mail( $receiver_email_address, $mem_email_subject, $MemberEmailMessage );
            $result['output'] = 1;
        } else {
            $result['output'] = 0;
        }

        echo json_encode( $result );
        exit;
    }
}

add_action( 'wp_ajax_nopriv_dsp_verify_email', 'dsp_verify_email' );

/**
 *  This function is used as helper to get all the array value into string
 *  for given user
 *
 * @param   [Array] [data]
 *
 * @return  [boolean]
 */

if ( ! function_exists( 'dsp_map_array_into_string' ) ) {
    function dsp_map_array_into_string( $data ) {
        $sentUserId = array();
        foreach ( $data as $value ) {
            $sentUserId[] = $value->user_id;
        }

        return implode( ',', $sentUserId );

    }
}


/**
 *  This function is used to get all users that has already sent match alert emails
 * for given user
 *
 * @param   [Integer] [userId]
 *
 * @return  [boolean]
 */

add_filter( 'dsp_get_already_mail_sent_match_users', 'dsp_get_match_alert_email_sent_users' );

if ( ! function_exists( 'dsp_get_match_alert_email_sent_users' ) ) {
    function dsp_get_match_alert_email_sent_users( $userId ) {
        global $wpdb;
        $dsp_match_alert_email_sent_user_table = $wpdb->prefix . DSP_MATCH_ALERT_EMAIL_SENT_USER_TABLE;
        $query                                 = "SELECT `user_id` FROM  $dsp_match_alert_email_sent_user_table WHERE `match_id`= '%d'";
        $emailAlreadySentUsers                 = $wpdb->get_results( $wpdb->prepare( $query, $userId ) );
        $users                                 = dsp_map_array_into_string( $emailAlreadySentUsers );

        return $users;
    }
}

/**
 *  This function is used to insert into email already sent match users table
 *
 * @param   [Array] [userData]
 *
 * @return  [boolean]
 */

add_action( 'dsp_insert_match_users', 'dsp_add_email_sent_match_users' );

if ( ! function_exists( 'dsp_add_email_sent_match_users' ) ) {
    function dsp_add_email_sent_match_users( $userData ) {
        global $wpdb;
        $dsp_match_alert_email_sent_user_table = $wpdb->prefix . DSP_MATCH_ALERT_EMAIL_SENT_USER_TABLE;
        $result                                = $wpdb->insert( $dsp_match_alert_email_sent_user_table, $userData, array(
            '%d',
            '%d'
        ) );

        return $result > 0 ? true : false;
    }
}

/**
 * This function is used to get currently logged in user information
 *
 * @param   [UserId]
 *
 * @return  [Object]
 */

if ( ! function_exists( 'dsp_get_user_profile_details' ) ) {
    function dsp_get_user_profile_details( $uId ) {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $userDetails       = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM  $dsp_user_profiles WHERE user_id = '%d'", $uId ) );

        return ! empty( $userDetails ) ? array_pop( $userDetails ) : false;
    }
}

add_filter( 'dsp_get_profile_details', 'dsp_get_user_profile_details' );


/**
 * This function is used to get credit of user
 *
 * @param   [UserId]
 *
 * @return  [Object]
 */

if ( ! function_exists( 'dsp_get_credits_of_user' ) ) {
    function dsp_get_credits_of_user( $uId ) {
        global $wpdb;
        $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
        $credits                 = $wpdb->get_var( $wpdb->prepare( "SELECT `no_of_credits` FROM  $dsp_credits_usage_table WHERE user_id = '%d'", $uId ) );

        return ! empty( $credits ) ? $credits : 0;;
    }
}

/**
 * This function is used to get the form with all gifts
 *
 * @param   [UserId]
 *
 * @return  [Object]
 */

if ( ! function_exists( 'dsp_create_gift_form' ) ) {
    function dsp_create_gift_form() {
        global $wpdb;
        $dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
        $virtual_gifts     = $wpdb->get_results( "select * from $dsp_virtual_gifts" );

        $output = '';
        $output = '<form method="post"><select name="image" style="display:none;" class= "image-picker show-html">';
        foreach ( $virtual_gifts as $gift_row ) {
            $output .= '<option data-img-src= "' . get_bloginfo( 'url' ) . "/wp-content/uploads/dsp_media/gifts/" . $gift_row->image . '" value= "' . $gift_row->image . '">  Page ' . $gift_row->id . ' </option> ';
        }
        $output .= '</select>
                        <input type="submit"   class="dspdp-btn dspdp-btn-default" value="' . __( 'Submit','wpdating' ) . '" />
                    </form>';

        return $output;
    }
}
add_filter( 'dsp_build_gift_form', 'dsp_create_gift_form' );


/**
 * This function is used to get credit of current user
 * @return  [String]
 */
if ( ! function_exists( 'dsp_get_credit_of_current_user' ) ) {
    function dsp_get_credit_of_current_user() {
        global $wpdb;
        $uId                     = get_current_user_id();
        $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
        $noOfCredits             = $wpdb->get_var( $wpdb->prepare( "SELECT no_of_credits FROM $dsp_credits_usage_table WHERE user_id='%d'", $uId ) );
        $noOfCredits             = ! empty( $noOfCredits ) ? $noOfCredits : 0;

        return $noOfCredits;
    }
}

/**
 * This function is used to check for  credit required to sent gift setting in admin section
 *
 * @param   [String]
 *
 * @return  [Boolean]
 */
if ( ! function_exists( 'dsp_get_credit_setting_value' ) ) {
    function dsp_get_credit_setting_value( $column = '' ) {
        global $wpdb;
        $dsp_credits_table = $wpdb->prefix . DSP_CREDITS_TABLE;
        $giftSettingValue  = $wpdb->get_var( "SELECT `" . $column . "` from $dsp_credits_table" );

        return $giftSettingValue;
    }
}

/**
 * This function is used to check for  virtual gift feature
 *
 * @param   [Array]
 *
 * @return  [String]
 */

add_filter( 'dsp_is_user_premium__or_has_credits_fn', 'dsp_is_user_premium__or_has_credits', 10, 2 );

if ( ! function_exists( 'dsp_is_user_premium__or_has_credits' ) ) {
    function dsp_is_user_premium__or_has_credits( $values, $freeMode = false ) {
        global $wpdb;
        extract( $values );
        extract( $ids );
        $dsp_user_virtual_gifts  = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
        $dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
        $check_max_gifts         = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM `$dsp_user_virtual_gifts` WHERE status_id= '%d' AND member_id='%d'", array(
            1,
            $member_id
        ) ) );
        $no_of_credits           = dsp_get_credit_of_current_user();
        $giftSettingValue        = dsp_get_credit_setting_value( 'gifts_per_credit' );
        $checkMembershipMsg      = ( $freeMode ) ? "Access" : $checkMembershipMsg;
        if ( $checkMembershipMsg == "Access" ) {
            if ( $check_max_gifts < $check_virtual_gifts_mode->setting_value ) {
                echo apply_filters( 'dsp_build_gift_form', '' );
            } else {
                ?>
                <p class="error"><?php echo __( 'User has received the maximum number of virtual gifts.','wpdating' ); ?></p>
                <?php
            }
        } else if ( ( $no_of_credits >= $giftSettingValue ) && $check_credit_mode->setting_status == 'Y' ) {
            echo apply_filters( 'dsp_build_gift_form', '' );
        } else {
            if ( $checkMembershipMsg == 'Expired' ) :
                ?>          <p class="error"><?php echo __( 'Your Premimum Account has been expired.','wpdating' ) ?></p>
            <?php else : ?>
                <p class="error"><?php echo __( 'Only premium member can access this feature, Please upgrade your account','wpdating' ) ?> </p>
            <?php endif; ?>
            <p class="error"><?php echo __( 'Buy Credits','wpdating' ); ?></p>
            <a href="<?php echo $root_link . "setting/upgrade_account/"; ?>" class="error dspdp-btn dspdp-btn-default"
               style="text-decoration:underline;"><?php echo __( 'Upgrade Here.','wpdating' ) ?></a>
            <?php
        }
    }
}


/**
 * This function is used to check for features
 *
 * @param   [Array] Ids of user_id & memberId
 * @param   [String] feature name like Virtual gift,Email etc..
 *
 * @return  [Object]
 */

if ( ! function_exists( 'dsp_is_features_allowed' ) ) {
    function dsp_is_features_allowed( $ids, $access_feature_name ) {
        global $wpdb;
        extract( $ids );
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $check_virtual_gifts_mode   = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'virtual_gifts'" );
        // check Free Mode is Activated or not.
        $check_free_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_mode'" );
        // check Free Trail Mode is Activated or not.
        $check_free_trail_mode = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'free_trail_mode'" );
        $check_credit_mode     = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'credit'" );
        $values                = array(
            'check_virtual_gifts_mode' => $check_virtual_gifts_mode,
            'check_credit_mode'        => $check_credit_mode,
            'ids'                      => $ids
        );
        if ( is_user_logged_in() ) {
            if ( $check_free_mode->setting_status == "N" ) {  // free mode is off
                if ( $check_free_trail_mode->setting_status == "N" ) {
                    $values['checkMembershipMsg'] = check_membership( $access_feature_name, $user_id );
                    apply_filters( 'dsp_is_user_premium__or_has_credits_fn', $values );
                } else {
                    $values['checkMembershipMsg'] = check_free_trial_feature( $access_feature_name, $user_id );
                    apply_filters( 'dsp_is_user_premium__or_has_credits_fn', $values );
                }
            } else {
                $checkMembershipMsg = check_membership( $access_feature_name, $user_id );
                $freeMode           = ( $checkMembershipMsg == 'Access' || $_SESSION['free_member'] ) ? true : false;
                apply_filters( 'dsp_is_user_premium__or_has_credits_fn', $values, $freeMode );
            }
        } else { ?>
            <p class="error"><?php echo __( 'You must log in to send virtual gifts.','wpdating' ); ?></p>
            <?php
        }
    }
}


/**
 * This function is check to user is already a friend or not
 *
 * @param   [Integer] friend user id
 *
 * @return  [Boolean]
 */
add_action( 'dsp_is_friend', 'dsp_is_friend_fn' );
if ( ! function_exists( 'dsp_is_friend_fn' ) ) {
    function dsp_is_friend_fn( $frenUId ) {
        global $wpdb;
        $uId                  = get_current_user_id();
        $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
        $numRows              = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id= '%d' AND friend_uid= '%d' AND approved_status='Y' ", array(
            $frenUId,
            $uId
        ) ) );

        return $numRows > 0 ? true : false;
    }
}


/**
 * This function is get general setting value
 *
 * @param   String or Array setting name
 * @param [String] Column name
 *
 * @return  [Object]
 */

add_filter( 'dsp_get_general_setting_value', 'dsp_get_general_setting_value_fn', 10, 2 );
if ( ! function_exists( 'dsp_get_general_setting_value_fn' ) ) {
    function dsp_get_general_setting_value_fn( $settingNames = '', $settingStatus = false ) {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        if ( $settingStatus ) {
            if ( is_array( $settingNames ) ) {
                $returnSettingsStatus = array();
                foreach ( $settingNames as $key => $name ) {
                    $query                        = "SELECT  `setting_status` FROM $dsp_general_settings_table WHERE setting_name = '%s' ";

                    $status                       = $wpdb->get_var( $wpdb->prepare( $query, $name ) );
                    $returnSettingsStatus[ $key ] = $status == 'Y' ? true : false;
                }

                return $returnSettingsStatus;
            }

            $query  = "SELECT  `setting_status` FROM $dsp_general_settings_table WHERE setting_name = '%s' ";
            $status = $wpdb->get_var( $wpdb->prepare( $query, $settingNames ) );

            return $status == 'Y' ? true : false;
        }
        $settingValues = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = '%s' ", $settingNames ) );

        return count( $settingValues ) > 0 ? array_pop( $settingValues ) : $settingValues;
    }
}
/**
 *  This function is used to get dsp options according to setting values
 *
 */

if ( ! function_exists( 'dsp_get_year' ) ) {
    function dsp_get_year( $startYear, $endYear, $selected ) {
        $contents  = '';
        $startYear = ! empty( $startYear ) ? $startYear : 1925;
        $endYear   = ! empty( $endYear ) ? $endYear : date( 'Y' );
        for ( $dsp_year = $endYear; $dsp_year >= $startYear; $dsp_year -- ) {
            $select   = ( $selected == $dsp_year ) ? 'selected' : '';
            $contents .= '<option value="' . $dsp_year . '" ' . $select . '>' . $dsp_year . '</option>';
        }

        return $contents;
    }
}

if ( ! function_exists( 'dsp_check_user_profile_exist' ) ) {
    function dsp_check_user_profile_exist() {
        global $wpdb, $current_user;
        $user_id                  = $current_user->ID;
        $dsp_user_profiles        = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $check_user_profile_exist = $wpdb->get_var( "SELECT COUNT(*) FROM $dsp_user_profiles WHERE user_id=$user_id" );

        return $check_user_profile_exist;
    }
}

add_filter( 'dsp_add_profile_link', 'dsp_add_profile_link_func', 10, 2 );
if ( ! function_exists( 'dsp_add_profile_link_func' ) ) {
    function dsp_add_profile_link_func( $content, $memberId ) {
        $content = '';

        /*global $wpdb,$current_user;
        $user_id = $current_user->ID;
        $profileExist = dsp_check_user_profile_exist();
        $content = '<li><div class="fav_icons_border">';
        if (is_user_logged_in()) {  // CHECK MEMBER LOGIN
            if ($profileExist > 0 ) {  // check user dating profile exist or not
                $content =  '<a href="'. ROOT_LINK . "add_friend/user_id/" . $user_id . "/frnd_userid/" . $memberId . "/" .'" title="' . __('Add to Friends', 'wpdating') .'">
                                        <span class="fa fa-plus-square"></span></a>';
             } else {
                $content .= '<a href="' . ROOT_LINK . "edit".'" title="' . __('Add to Friends', 'wpdating') .'"><span class="fa fa-plus-square"></span>test</a>';
            }
        } else {
                 $content .= '<a onClick="javascript:not_loggedin_message();" title="'. __('Login', 'wpdating').'"><span class="fa fa-plus-square"></span>test</a>';
        }
        $content .= '</div>
        </li>';*/

        return $content;
    }
}


add_filter( 'dsp_add_dsp_admin_setting', 'dsp_add_dsp_admin_setting_func', 10, 2 );
if ( ! function_exists( 'dsp_add_dsp_admin_setting_func' ) ) {
    function dsp_add_dsp_admin_setting_func() {

    }
}

if ( ! function_exists( 'dsp_filter_spam_words' ) ) {
    function dsp_filter_spam_words( $word ) {
        global $wpdb;
        $dsp_spam_words_table = $wpdb->prefix . DSP_SPAM_WORDS_TABLE;
        $spamWords            = $wpdb->get_col( "SELECT `spam_word` FROM $dsp_spam_words_table " );

        return ! in_array( $word, $spamWords ) ? $word : '';
    }
}


/**
 * This is filter for spam words from provided string
 *
 * @param   String $string
 *
 * @return  String
 */

add_filter( 'dsp_spam_filters', 'dsp_spam_filters_func' );
if ( ! function_exists( 'dsp_spam_filters_func' ) ) {
    function dsp_spam_filters_func( $string ) {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $words                      = explode( ' ', $string );
        $words                      = array_filter( $words, 'dsp_filter_spam_words' );

        return implode( ' ', $words );

    }
}

/**
 * This function is used to display all errors
 *
 * @param Array $errors
 *
 * @return String
 */
add_filter( 'dsp_display_errors', 'dsp_display_errors_func' );
if ( ! function_exists( 'dsp_display_errors_func' ) ) {
    function dsp_display_errors_func( $errors ) {
        $content = '';
        if ( empty( $errors ) ) {
            return $content;
        }
        $content = '<div>';
        foreach ( $errors as $key => $error ) {
            $content .= '<spam class="error">' . $error . '</span>';
        }
        $content .= '</div>';

        return $content;
    }
}

/**
 * This is filter for display facebook login
 *
 * @param   String $string
 *
 * @return  String
 */
add_filter( 'dsp_facebook_login', 'dsp_facebook_login_func' );
if ( ! function_exists( 'dsp_facebook_login_func' ) ) {
    function dsp_facebook_login_func() {
        include_once( WP_DSP_ABSPATH . 'external-lib/fb/fb.php' );
        global $wpdb, $loginUrl;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        // facebook_login Setting
        $checkFacebookLogin       = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'facebook_login'" );
        $isFacebookLoginSettingOn = ( $checkFacebookLogin->setting_status == 'Y' ) ? true : false;
        $facebookCredentials      = ( ! empty( $appId ) && ! empty( $secretfb ) ) ? true : false;
        $fbLogin                  = '';
        $event                    = "window.open('" . $loginUrl . "', 'Authenticate', 'width=650, height=350')";
        if ( ! is_user_logged_in() && $isFacebookLoginSettingOn && $facebookCredentials ) {
            $fbLogin = '<a href="' . $loginUrl . '" class="dspdp-btn dspdp-btn-primary" onclick="' . $event . 'return false;">' . __( 'Facebook Login','wpdating' ) . '</a>';
        } else {
            $fbLogin = '';
        }

        return $fbLogin;
    }
}

/**
 * This function is used to set array according to
 * its key
 *
 * @param Array $existProfileQuestions
 *
 * @return String
 */

add_filter( 'dsp_get_nested_array', 'dsp_get_nested_array_func' );
if ( ! function_exists( 'dsp_get_nested_array_func' ) ) {
    function dsp_get_nested_array_func( $existProfileQuestions = array() ) {
        if ( is_array( $existProfileQuestions ) && count( $existProfileQuestions ) == 0 ) {
            return false;
        }

        global $wpdb;
        $prev     = '';
        $newArray = array();
        foreach ( $existProfileQuestions as $k => $q ) {
            $qId           = $q->profile_question_id;
            $question_name = $q->question_name;
            //$option_value = $q->option_value;

            $current_language_code = dsp_get_current_user_language_code();
            if ( $current_language_code == 'en' ) {
                $dsp_question_options_table = $wpdb->prefix . "dsp_question_options";
            } else {
                $dsp_question_options_table = $wpdb->prefix . "dsp_question_options_" . $current_language_code;
            }
            $option_value = $wpdb->get_row( "SELECT `option_value` FROM $dsp_question_options_table WHERE `question_option_id`=$q->profile_question_option_id" );

            if ( ! empty( $option_value ) ) {
                $option_value = $option_value->option_value;
            } else {
                $option_value = $q->option_value;
            }

            if ( $prev == $qId ) {
                $newArray[ $question_name ][] = $option_value;
            }
            $newArray[ $question_name ][] = $option_value;
            $prev                         = $qId;
        }

        return $newArray;
    }
}


/**
 * This function is used to get the current language code of user
 * @return string
 */

if ( ! function_exists( 'dsp_get_current_user_language_code' ) ) {
    function dsp_get_current_user_language_code() {
        global $wpdb;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $lang_id                    = null; //default case where session is not set and not loggin
        if ( isset( $_SESSION['default_lang'] ) ) {
            $lang_id = $_SESSION['default_lang']; //session is set
        } else {
            $adminLangId = $wpdb->get_var( $wpdb->prepare( "SELECT `language_id` FROM $dsp_language_detail_table where display_status = '%d'", 1 ) );
            if ( is_user_logged_in() ) {
                $userSessionLangId = $wpdb->get_var( "SELECT  `language_id` FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' " );
                $lang_id           = isset( $userSessionLangId ) && ! empty( $userSessionLangId ) ? $userSessionLangId : $adminLangId; //logged  in and  session is not set
            } else {
                $lang_id = $adminLangId;
            }
        }

        $all_languages = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table where language_id='" . $lang_id . "'" );
        $language_name = ! empty( $all_languages->language_name ) ? $all_languages->language_name : 'english';

        return strtolower( trim( esc_sql( substr( $language_name, 0, 2 ) ) ) );
    }
}

/**
 * This function is used to display profile questions
 *
 * @param Array $existProfileQuestions
 *
 * @return String
 */

add_filter( 'dsp_display_profile_questions', 'dsp_display_profile_questions_func' );
if ( ! function_exists( 'dsp_display_profile_questions_func' ) ) {
    function dsp_display_profile_questions_func( $existProfileQuestions = array() ) {
        if ( is_array( $existProfileQuestions ) && count( $existProfileQuestions ) > 0 ) {
            $content                  = '';
            $filteredProfileQuestions = apply_filters( 'dsp_get_nested_array', $existProfileQuestions );
            foreach ( $filteredProfileQuestions as $key => $ques ) {
                $ques    = implode( ',', array_unique( $ques ) );
                $content .= sprintf( "<li class='dsp-md-6'><span>%s :</span>\t<div class='details'>%s</div></li>", $key, $ques );
            }

            return $content;
        }
    }
}

/**
 * This method is used to activate License
 *
 * @param null
 *
 * @since 5.0
 */
add_action( 'wp_ajax_dsp_validate_license_key', 'dsp_validate_license_key' );
if ( ! function_exists( 'dsp_validate_license_key' ) ) {
    function dsp_validate_license_key() {
        global $licenseModuleSwitch, $isValidLicense;
        $nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : '';
        //dsp_debug(wp_verify_nonce( $nonce, 'license-nonce' ));die;
        $message = array(
            'msg' => '',
            'err' => ''
        );
        if ( ! wp_verify_nonce( trim( $nonce ), 'license-nonce' ) ) {

            $message['err'] = 'Invalid nonce key';
            echo json_encode( $message );

            return false;
        }
        $licKey = isset( $_REQUEST['licKey'] ) ? $_REQUEST['licKey'] : '';

        if ( ! class_exists( 'LicenceChecker' ) ) {
            include_once( WP_DSP_ABSPATH . 'class/class-license-checker.php' );
        }
        if ( ! class_exists( 'License_Handler' ) ) {
            include_once( WP_DSP_ABSPATH . '/files/classes/class-license-handler.php' );
        }

        if ( ! empty( $licKey ) ) {
            $licenseChk = new LicenceChecker();
            $config     = array(
                'secret_key'         => LICENSE_SECRET_KEY,
                //'item_reference' => 'dsp_dating',
                'license_key'        => $licKey,
                'license_server_url' => LICENSE_SERVER_URL
            );
            $licenseChk->licenseIsValid( $config );
            if ( $isValidLicense ) {
                $licenseHandler = new License_Handler();
                $licenseHandler->setLicKey( $licKey );
                $licenseHandler->saveLicKey();
                $message['msg'] = 'License Validated Successfully';
            } else {
                foreach ( $licenseChk->errors as $key => $value ) {
                    $message['err'] = $value;
                }
            }
        } else {
            $message['err'] = 'License Key Empty';
        }
        echo json_encode( $message );
        wp_die();
    }
}


/**
 * This is action is used to copy image from image folder
 *
 * @param   String $fileNames
 * @param   Array $location
 *
 * @return  String
 */
add_action( 'dsp_copy_images', 'dsp_copy_images_func', 10, 2 );
if ( ! function_exists( 'dsp_copy_images_func' ) ) {
    function dsp_copy_images_func( $fileNames, $location ) {
        extract( $location );
        $src  = is_array( $fileNames ) ? $src . $fileNames[0] : $src . $fileNames;
        $dest = is_array( $fileNames ) ? $dest . $fileNames[0] : $dest . $fileNames;
        if ( ! empty( $fileNames ) ) {
            if ( ! file_exists( $dest ) ) {
                if ( copy( $src, $dest ) ) {
                    //unlink($src);
                } // end copy
            } // end image exist check
        }

    }
}


/**
 * This filter is used to check for  credit required to sent gift setting in admin section
 *
 * @param   String
 * @param   String
 * @param   Array
 *
 * @return  Array
 */
add_filter( 'dsp_allow_menu_page', 'dsp_allow_menu_page_func', 10, 3 );
if ( ! function_exists( 'dsp_allow_menu_page_func' ) ) {
    function dsp_allow_menu_page_func( $feature, $pageUrl, $accessMenus = array() ) {
        if ( in_array( $pageUrl, $accessMenus ) || $_SESSION['free_member'] ) {
            return array(
                'status' => true,
            );
        }
        $user_id        = get_current_user_id();
        $return         = array();
        $settingValues  = array(
            'forceProfileStatus'   => 'force_profile',
            'freeTrialStatus'      => 'free_trial_mode',
            'approveProfileStatus' => 'authorize_profiles'
        );

        $settingsStatus = apply_filters( 'dsp_get_general_setting_value', $settingValues, true );
        $forceProfileStatus = $settingsStatus['forceProfileStatus'];
        $freeTrialStatus = $settingsStatus['freeTrialStatus'];
        $approveProfileStatus = $settingsStatus['approveProfileStatus'];

        if ( $forceProfileStatus ) {
            $check_force_profile_msg           = check_force_profile_feature( $feature, $user_id );
            $allowMenuPageStatus               = $check_force_profile_msg == "Access" ? true : false;
            $return['check_force_profile_msg'] = $check_force_profile_msg;
        } else {
            if ( $freeTrialStatus ) {
                $check_member_trial_msg = check_free_trial_feature( $feature, $user_id );
                $allowMenuPageStatus    = $check_member_trial_msg == "Access" ? true : false;
                //array_push('check_member_trial_msg' => $check_member_trial_msg);
                $return['check_member_trial_msg'] = $check_member_trial_msg;

            } else if ( ! $approveProfileStatus ) {
                $check_approved_profile_msg = check_approved_profile_feature( $user_id );
                $allowMenuPageStatus        = $check_approved_profile_msg == "Access" ? true : false;
                //array_push('check_approved_profile_msg' => $check_approved_profile_msg);
                $return['check_approved_profile_msg'] = $check_approved_profile_msg;
            } else { // if free trial mode is off
                $check_membership_msg = check_membership( $feature, $user_id );
                $allowMenuPageStatus  = $check_membership_msg == "Access" ? true : false;
                //array_push('check_membership_msg' => $check_membership_msg);

                $return['check_membership_msg'] = $check_membership_msg;
            }
        }
        $return['status'] = $allowMenuPageStatus;

        return $return;
    }
}

/**
 * This filter is used to check for  credit required
 * to sent gift setting in admin section
 *
 * @return  String
 */

add_filter( 'dsp_display_search_form_setting', 'dsp_display_search_form_setting_func' );
if ( ! function_exists( 'dsp_display_search_form_setting_func' ) ) {
    function dsp_display_search_form_setting_func() {
        global $current_user;
        $current_user_id = $current_user->ID;

        include_once( WP_DSP_ABSPATH . 'class/class-search-form.php' );
        $searchForm = new wpdating_search_form( $current_user_id );
        //echo $searchForm->dsp_display_search_form_setting_func();
    }
}

/**
 * This action is call on wp_init hoook to
 * initialize the language id from session
 *
 * @return  String
 */

add_action( 'init', 'dsp_init_session_language_id', 7 );
if ( ! function_exists( 'dsp_init_session_language_id' ) ) {
    function dsp_init_session_language_id() {
        $lang_id        = isset( $_REQUEST['lid'] ) ? $_REQUEST['lid'] : '';
        $action         = isset( $_REQUEST['Action'] ) ? $_REQUEST['Action'] : '';
        $display_status = isset( $_REQUEST['status'] ) ? $_REQUEST['status'] : '';
        $siteurl        = get_option( 'siteurl' ) . "/";
        if ( $action == 'language_status' ) {
            if ( ! empty( $user_id ) ) {
                if ( function_exists( 'dsp_session_language_initialize' ) ) {
                    dsp_session_language_initialize( true, $current_user, $lang_id );
                }
            } else {
                if ( function_exists( 'dsp_session_language_initialize' ) ) {
                    dsp_session_language_initialize( false, null, $lang_id );
                }
            }
        }
    }
}

/**
 * This action is used to add  multiple choice field
 *
 *
 * @return  String
 */

add_action( 'dsp_multiple_choice_field', 'dsp_multiple_choice_field_func', 10, 3 );
if ( ! function_exists( 'dsp_multiple_choice_field_func' ) ) {
    function dsp_multiple_choice_field_func( $profile_questions, $existOptions, $lang_code ) {
        global $wpdb;
        if ( $lang_code == 'en' ) {
            $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
            $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
        } else {
            $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE . '_' . $lang_code;
            $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE . '_' . $lang_code;
        }
        //$dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
        $ques_id              = isset( $profile_questions->profile_setup_id ) ? $profile_questions->profile_setup_id : '';
        $profile_ques         = isset( $profile_questions->question_name ) ? stripslashes( $profile_questions->question_name ) : '';
        $profile_ques_type_id = isset( $profile_questions->field_type_id ) ? $profile_questions->field_type_id : '';
        $required             = isset( $profile_questions->required ) ? $profile_questions->required : 'N';
        ?>
        <li class="dsp-form-group dspdp-form-group ">
            <span class="dsp-sm-3 dsp-control-label dspdp-col-sm-3 dspdp-control-label"><?php echo __( $profile_ques, 'wpdating' ); ?>
                :</span>
            <?php if ( $required == "Y" ) { ?>
                <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>"/>
                <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>"/>
            <?php } ?>
            <span class="dsp-sm-6 dspdp-col-sm-6">
                        <select class="dsp-multiple-select dsp-form-control dspdp-form-control chosen chzn-done"
                                name="option_id2[<?php echo $ques_id ?>][]" id="q_opt_ids<?php echo $ques_id ?>"
                                multiple="true">
                            <?php /* ?><option value=" "><?php echo __('Select', 'wpdating'); ?></option><?php */ ?>
                            <?php
                            $myrows_options = $wpdb->get_results( "SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order" );
                            foreach ( $myrows_options as $profile_questions_options ) {
                                if ( @in_array( $profile_questions_options->question_option_id, $existOptions ) ) {
                                    ?>
                                    <option value="<?php echo $profile_questions_options->question_option_id ?>"
                                            selected="selected"><?php echo stripslashes(__( $profile_questions_options->option_value , 'wpdating') ); ?></option>
                                <?php } else { ?>
                                    <option
                                            value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo stripslashes( __($profile_questions_options->option_value,'wpdating') ); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </span>
        </li>
    <?php }
}


/**
 * This action is used to add  multiple choice field
 *
 *
 * @return  String
 */

add_action( 'dsp_dropdown_field', 'dsp_dropdown_field_func', 10, 3 );
if ( ! function_exists( 'dsp_dropdown_field_func' ) ) {
    function dsp_dropdown_field_func( $profile_questions, $existOptions, $lang_code ) {
        global $wpdb;
        if ( $lang_code == 'en' ) {
            $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
            $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
        } else {
            $dsp_profile_setup_table    = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE . '_' . $lang_code;
            $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE . '_' . $lang_code;
        }

        $ques_id              = $profile_questions->profile_setup_id;
        $profile_ques         = stripslashes( $profile_questions->question_name );
        $profile_ques_type_id = $profile_questions->field_type_id;
        ?>
        <li class="dsp-form-group dspdp-form-group ">
            <span class="dsp-sm-3 dsp-control-label dspdp-control-label dspdp-col-sm-3"><?php echo __( $profile_ques, 'wpdating' ); ?>
                :</span>
            <?php if ( $profile_questions->required == "Y" ) { ?>
                <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>"/>
                <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>"/>
            <?php } ?>
            <span class="dsp-sm-6 dspdp-col-sm-6">
                <select class="dsp-form-control dspdp-form-control" name="option_id[<?php echo $ques_id ?>]"
                        id="q_opt_ids<?php echo $ques_id ?>">
                    <option value="0"><?php echo __( 'Select','wpdating' ); ?></option>
                    <?php
                    $myrows_options = $wpdb->get_results( "SELECT * FROM $dsp_question_options_table Where question_id=$ques_id Order by sort_order" );
                    foreach ( $myrows_options as $profile_questions_options ) {
                        if ( @in_array( $profile_questions_options->question_option_id, $existOptions ) ) {
                            ?>
                            <option value="<?php echo $profile_questions_options->question_option_id ?>"
                                    selected="selected"><?php echo __( stripslashes( $profile_questions_options->option_value ), 'wpdating' ); ?></option>
                        <?php } else { ?>
                            <option
                                    value="<?php echo $profile_questions_options->question_option_id ?>"><?php echo __( stripslashes( $profile_questions_options->option_value ), 'wpdating' ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </span>
        </li>
        <?php
    }
}


/**
 * This action is used to add  multiple choice field
 *
 *
 * @return  String
 */

add_action( 'dsp_textbox_field', 'dsp_textbox_field_func', 10, 4 );
if ( ! function_exists( 'dsp_textbox_field_func' ) ) {
    function dsp_textbox_field_func( $profile_questions, $existOptions, $lang_code, $partner = 0 ) {
        global $wpdb, $current_user;
        $dsp_question_options_table = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
        if ($partner){
            $dsp_question_details = $wpdb->prefix . DSP_PARTNER_PROFILE_QUESTIONS_DETAILS;
        }else{
            $dsp_question_details = $wpdb->prefix . DSP_PROFILE_QUESTIONS_DETAILS_TABLE;
        }
        $ques_id                    = $profile_questions->profile_setup_id;
        $profile_ques               = stripslashes( $profile_questions->question_name );
        $profile_ques_type_id       = $profile_questions->field_type_id;
        $profile_ques_max_length    = $profile_questions->max_length;
        ?>
        <li class="dsp-row dsp-form-group dspdp-form-group">
            <span class="dsp-sm-3 dsp-control-label dspdp-control-label dspdp-col-sm-3"><?php echo __( $profile_ques, 'wpdating' ); ?>
                :</span>
            <?php
            $check_exist_profile_text_details = $wpdb->get_var( "SELECT count(*) FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id" );
            if ( $check_exist_profile_text_details > 0 ) {
                $exist_profile_text_details = $wpdb->get_row( "SELECT * FROM $dsp_question_details WHERE user_id = '$current_user->ID' AND profile_question_id=$ques_id" );
                $text_value                 = stripslashes( $exist_profile_text_details->option_value );
            } else {
                $text_value = isset( $question_option_id1[ $profile_ques_type_id ] ) ? $question_option_id1[ $profile_ques_type_id ] : '';
            }
            ?>
            <?php if ( $profile_questions->required == "Y" ) { ?>
                <input type="hidden" name="hidetextqu_name" value="<?php echo $profile_ques; ?>"/>
                <input type="hidden" name="hidtextprofileqquesid" id="hidtextprofileqquesid"
                       value="<?php echo $ques_id; ?>"/>
            <?php } ?>
            <span class="dsp-sm-6 dspdp-col-sm-9">
                    <textarea class="dsp-form-control dspdp-form-control" name="option_id1[<?php echo $ques_id ?>]"
                              id="text_option_id<?php echo $ques_id ?>"
                              maxlength="<?php echo $profile_ques_max_length; ?>"
                              rows="6"><?php echo trim( $text_value ) ?></textarea>
                </span>
        </li>
    <?php }
}


/**
 * This filter is used to remove empty value
 * from given arrays
 *
 * @return  Array
 */

add_action( 'dsp_filter_empty_array_values', 'dsp_filter_empty_array_values_func', 10, 2 );
if ( ! function_exists( 'dsp_filter_empty_array_values_func' ) ) {
    function dsp_filter_empty_array_values_func( $beforeMultipleQnIds ) {
        $afterMultipleQnIds = array();
        foreach ( $beforeMultipleQnIds as $key => $questionIds ) {
            foreach ( $questionIds as $k => $qid ) {
                if ( ! empty( $qid ) && $qid != ' ' ) {
                    $afterMultipleQnIds[ $key ][ $k ] = $qid;
                }
            }
        }

        return $afterMultipleQnIds;
    }
}

/**
 * This action is used to display the fields in the edit profile section
 * from given arrays
 *
 * @return  Array
 */

add_action( 'dsp_display_question_by_order', 'dsp_display_question_by_order_func', 10, 2 );
if ( ! function_exists( 'dsp_display_question_by_order_func' ) ) {
    function dsp_display_question_by_order_func( $updateExitOption, $partner = 0 ) {
        global $wpdb;
        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
        if ( isset( $_SESSION['default_lang'] ) ) {
            $lang_id = $_SESSION['default_lang'];
        } else {
            $adminLangId = $wpdb->get_var( $wpdb->prepare( "SELECT `language_id` FROM $dsp_language_detail_table where display_status = '%d'", 1 ) );
            if ( is_user_logged_in() ) {
                $userSessionLangId = $wpdb->get_var( "SELECT  `language_id` FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' " );
                $lang_id           = isset( $userSessionLangId ) && ! empty( $userSessionLangId ) ? $userSessionLangId : $adminLangId; //logged  in and  session is not set
            } else {
                $lang_id = $adminLangId;
            }
            $_SESSION['default_lang'] = $lang_id;
        }
        $language  = $wpdb->get_row( "SELECT * FROM $dsp_language_detail_table  WHERE `language_id`=$lang_id " );
        $lang_code = strtolower( trim( esc_sql( substr( $language->language_name, 0, 2 ) ) ) );
        if ( $language->language_name == 'english' ) {
            $dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
        } else {
            $dsp_profile_setup_table = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE . '_' . $lang_code;
        }

        $myrows = $wpdb->get_results( "SELECT * FROM $dsp_profile_setup_table  Order by sort_order" );
        foreach ( $myrows as $profile_questions ) {
            $profile_ques_type_id = $profile_questions->field_type_id;
            switch ( $profile_ques_type_id ) {
                case 1:
                    do_action( 'dsp_dropdown_field', $profile_questions, $updateExitOption, $lang_code );
                    break;

                case 2:
                    do_action( 'dsp_textbox_field', $profile_questions, $updateExitOption, $lang_code, $partner );
                    break;

                case 3:
                    do_action( 'dsp_multiple_choice_field', $profile_questions, $updateExitOption, $lang_code );
                    break;

                default:
                    # code...
                    break;
            }

        }
    }
}


/**
 * This function is used to print the change display order link of everything in database that has display order
 *
 * @return html
 */
if ( ! function_exists( 'dsp_print_change_display_order_link' ) ) {
    function dsp_print_change_display_order_link( $id_field, $id, $order_field, $table_name, $parent_up_level = '', $parent_id_field = '', $parent_id = '' ) {
        $identifier = '';

        if ( $parent_id_field != '' && $parent_id != '' ) {
            $up_arr_params = array(
                'id_field'        => $id_field,
                'id'              => $id,
                'direction'       => 'up',
                'order_field'     => $order_field,
                'table_name'      => $table_name,
                'parent_id_field' => $parent_id_field,
                'parent_id'       => $parent_id
            );
        } else {
            $up_arr_params = array(
                'id_field'    => $id_field,
                'id'          => $id,
                'direction'   => 'up',
                'order_field' => $order_field,
                'table_name'  => $table_name
            );
        }

        $up_link      = add_query_arg( $up_arr_params );
        $up_image_src = WPDATE_URL . 'images/up_arrow.png';
        $up_image     = '<img class="direction_arrows" width="18" src="' . $up_image_src . '" />';

        if ( $parent_id_field != '' && $parent_id != '' ) {
            $down_arr_params = array(
                'id_field'        => $id_field,
                'id'              => $id,
                'direction'       => 'down',
                'order_field'     => $order_field,
                'table_name'      => $table_name,
                'parent_id_field' => $parent_id_field,
                'parent_id'       => $parent_id
            );
        } else {
            $down_arr_params = array(
                'id_field'    => $id_field,
                'id'          => $id,
                'direction'   => 'down',
                'order_field' => $order_field,
                'table_name'  => $table_name
            );
        }

        $down_link      = add_query_arg( $down_arr_params );
        $down_image_src = WPDATE_URL . 'images/down_arrow.png';
        $down_image     = '<img class="direction_arrows" width="18" src="' . $down_image_src . '" />';

        if ( isset( $_GET['slide_down'] ) && isset( $_GET['slide_up'] ) ) {
            $slide_down = filter_var( strip_tags( $_GET['slide_down'] ), FILTER_SANITIZE_NUMBER_INT );
            if ( is_numeric( $slide_down ) && $slide_down == $id ) {
                $identifier = 'id="slide_me_down" ';
            }

            $slide_up = filter_var( strip_tags( $_GET['slide_up'] ), FILTER_SANITIZE_NUMBER_INT );
            if ( is_numeric( $slide_up ) && $slide_up == $id ) {
                $identifier = 'id="slide_me_up" ';
            }

            if ( isset( $parent_up_level ) && is_int( $parent_up_level ) && $slide_down == $id ) // run it once only
            {
                echo '<script>dsp_hide_selected(' . $parent_up_level . ');</script>';
                echo '<script>jQuery(document).ready(function(){ dsp_change_display_order_animation(' . $parent_up_level . '); });</script>';
            }
        }

        $link = '<a ' . $identifier . 'href="' . $up_link . '">' . $up_image . '</a><a href="' . $down_link . '">' . $down_image . '</a>';
        echo $link;
    }
}


/**
 * This action is used to change the display order of everything in database that has display order
 *
 * @return boolean
 */
add_action( 'dsp_change_display_order', 'dsp_change_display_order', 10, 7 );
if ( ! function_exists( 'dsp_change_display_order' ) ) {
    function dsp_change_display_order( $id_field, $id, $direction, $order_field, $table_names, $parent_id_field = '', $parent_id = '' ) {
        global $wpdb;
        $table_names = explode( ' ', $table_names );

        foreach ( $table_names as $table_name ) {
            $current  = $wpdb->get_row( "SELECT * FROM $table_name WHERE `$id_field`=$id" );
            $other_id = '';
            $changed  = 0;
            if ( ! empty( $current ) ) {
                $current_order = $current->$order_field;
                if ( $direction == 'up' ) {
                    if ( $parent_id_field != '' && $parent_id != '' ) {
                        $new = $wpdb->get_results( "SELECT * FROM $table_name WHERE `$order_field` < $current_order AND `$parent_id_field`=$parent_id ORDER BY `$order_field` DESC" );
                    } else {
                        $new = $wpdb->get_results( "SELECT * FROM $table_name WHERE `$order_field` < $current_order ORDER BY `$order_field` DESC" );
                    }

                    if ( ! empty( $new ) ) {
                        $other_id   = $new[0]->$id_field;
                        $new_order  = $new[0]->$order_field;
                        $slide_up   = $id;
                        $slide_down = $other_id;
                    }
                } elseif ( $direction == 'down' ) {
                    if ( $parent_id_field != '' && $parent_id != '' ) {
                        $new = $wpdb->get_results( "SELECT * FROM $table_name WHERE `$order_field` > $current_order AND `$parent_id_field`=$parent_id ORDER BY `$order_field` ASC" );
                    } else {
                        $new = $wpdb->get_results( "SELECT * FROM $table_name WHERE `$order_field` > $current_order ORDER BY `$order_field` ASC" );
                    }

                    if ( ! empty( $new ) ) {
                        $other_id   = $new[0]->$id_field;
                        $new_order  = $new[0]->$order_field;
                        $slide_up   = $other_id;
                        $slide_down = $id;
                    }
                }

                if ( $other_id != '' ) {
                    $wpdb->query( "UPDATE $table_name SET `$order_field` = $new_order WHERE `$id_field` = $id" );
                    $wpdb->query( "UPDATE $table_name SET `$order_field` = $current_order WHERE `$id_field` = $other_id" );
                    $changed = 1;
                }
            }
        }

        $http_referrer = remove_query_arg( array(
            'id_field',
            'id',
            'direction',
            'order_field',
            'table_name',
            'parent_id_field',
            'parent_id'
        ) );

        if ( $changed == 1 ) {
            $arr_params    = array( 'slide_up' => $slide_up, 'slide_down' => $slide_down );
            $http_referrer = add_query_arg( $arr_params, $http_referrer );
        }

        wp_safe_redirect( $http_referrer );
        exit;
    }
}

/**
 * This action is used to detect the change display order link
 *
 * @return  null
 */
add_action( 'admin_enqueue_scripts', 'dsp_detect_change_display_order_link' );
if ( ! function_exists( 'dsp_detect_change_display_order_link' ) ) {
    function dsp_detect_change_display_order_link( $hook ) {
        $script_src = WPDATE_URL . 'js/change_display_order.js';
        wp_enqueue_script( 'change_display_order_script', $script_src );
        $errors = 0;
        if ( isset( $_GET['id_field'] ) ) {
            $id_field = filter_var( strip_tags( $_GET['id_field'] ), FILTER_SANITIZE_STRING );
        } else {
            $errors ++;
        }

        if ( isset( $_GET['id'] ) ) {
            $id = filter_var( strip_tags( $_GET['id'] ), FILTER_SANITIZE_NUMBER_INT );
        } else {
            $errors ++;
        }

        if ( isset( $_GET['direction'] ) ) {
            $direction = filter_var( strip_tags( $_GET['direction'] ), FILTER_SANITIZE_STRING );
        } else {
            $errors ++;
        }

        if ( isset( $_GET['order_field'] ) ) {
            $order_field = filter_var( strip_tags( $_GET['order_field'] ), FILTER_SANITIZE_STRING );
        } else {
            $errors ++;
        }

        if ( isset( $_GET['table_name'] ) ) {
            $table_name = filter_var( strip_tags( $_GET['table_name'] ), FILTER_SANITIZE_STRING );
        } else {
            $errors ++;
        }

        if ( isset( $_GET['parent_id_field'] ) ) {
            $parent_id_field = filter_var( strip_tags( $_GET['parent_id_field'] ), FILTER_SANITIZE_STRING );
        } else {
            $parent_id_field = '';
        }

        if ( isset( $_GET['parent_id'] ) ) {
            $parent_id = filter_var( strip_tags( $_GET['parent_id'] ), FILTER_SANITIZE_STRING );
        } else {
            $parent_id = '';
        }

        if ( $errors == 0 ) {
            do_action( 'dsp_change_display_order', $id_field, $id, $direction, $order_field, $table_name, $parent_id_field, $parent_id );
        } else {

        }
    }
}

/**
 * This function returns the members page url
 *
 * @return  String
 */

if ( ! function_exists( 'dsp_get_members_page_url' ) ) {
    function dsp_get_members_page_url() {
        global $wpdb;
        global $current_user;
        $posts_table          = $wpdb->prefix . POSTS;
        $dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $member_page_title_ID = $wpdb->get_row( "SELECT setting_value FROM $dsp_general_settings WHERE setting_name='member_page_id'" );
        $member_pageid        = $member_page_title_ID->setting_value;
        $post_page_title_ID   = $wpdb->get_row( "SELECT * FROM $posts_table WHERE ID='$member_pageid'" );
        $member_page_id       = isset( $post_page_title_ID ) ? $post_page_title_ID->ID : "";
        $member_page_name     = isset( $post_page_title_ID ) ? $post_page_title_ID->post_name : "";
        $root_link            = get_bloginfo( 'url' ) . "/" . $member_page_name . "/";

        return $root_link;
    }
}

/**
 * This function returns the membership no. of days formatted as required by paypal subscription/ recurring along with it's validity
 *
 * @return  Array
 */
if ( ! function_exists( 'paypal_recurring_formatted_duration' ) ) {
    function paypal_recurring_formatted_duration( $no_of_days ) {
        $paypal_subscription_duration = $no_of_days;
        $paypal_subscription_unit     = 'D';
        $duration_error               = false;

        if ( $paypal_subscription_duration <= 90 ) {
            $duration_error = false;
        } elseif ( $paypal_subscription_duration % 365 == 0 ) {
            $paypal_subscription_unit     = 'Y';
            $paypal_subscription_duration = $paypal_subscription_duration / 365;
            if ( $paypal_subscription_duration > 5 ) {
                $duration_error = true;
            }
        } elseif ( $paypal_subscription_duration % 30 == 0 ) {
            $paypal_subscription_unit     = 'M';
            $paypal_subscription_duration = $paypal_subscription_duration / 30;
            if ( $paypal_subscription_duration > 24 ) {
                $duration_error = true;
            }
        } elseif ( $paypal_subscription_duration % 7 == 0 ) {
            $paypal_subscription_unit     = 'W';
            $paypal_subscription_duration = $paypal_subscription_duration / 7;
            if ( $paypal_subscription_duration > 52 ) {
                $duration_error = true;
            }
        } else {
            $duration_error = true;
        }

        $result['error']  = $duration_error;
        $result['unit']   = $paypal_subscription_unit;
        $result['period'] = $paypal_subscription_duration;

        return $result;
    }
}

/**
 * This function returns a status of whether or not a user is currently using a paypal subscription for a membership plan
 *
 * @return  Array
 */
if ( ! function_exists( 'using_paypal_subscription_for_membership' ) ) {
    function using_paypal_subscription_for_membership( $user_id, $membership_id, $no_of_days ) {
        global $wpdb;
        //$current_time = time();
        //$membership_time_period = $no_of_days*24*60*60;
        $dsp_paypal_recurring_table = $wpdb->prefix . "dsp_paypal_recurring";
        $exists_recurring_profile   = $wpdb->get_results( "SELECT * FROM $dsp_paypal_recurring_table where status='active' "
                                                          . "AND txn_type='subscr_signup' AND user_id='$user_id' "
                                                          . "AND membership_id='$membership_id'" );
        if ( count( $exists_recurring_profile ) == 1 ) {
            return true;
        } else {
            return false;
        }
    }
}

if ( ! function_exists( 'dsp_get_default_country' ) ) {
    function dsp_get_default_country() {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $defaultCountry             = $wpdb->get_var( "SELECT `setting_value` FROM $dsp_general_settings_table WHERE setting_name = 'default_country'" );

        return $defaultCountry;
    }
}


/**
 * Check the blocked status using the blocker user id and blocked user id.
 *
 * @param int $blocker_user_id
 * @param int $blocked_user_id
 * @return bool
 */
if ( ! function_exists( 'check_blocked_status' ) ) {
    function check_blocked_status($blocker_user_id, $blocked_user_id)
    {
        global $wpdb;
        $dsp_blocked_members_table = $wpdb->prefix . DSP_BLOCKED_MEMBERS_TABLE;
        $block_status = $wpdb->get_var("SELECT COUNT(*) as num FROM $dsp_blocked_members_table
                                                            WHERE user_id = '{$blocker_user_id}' AND block_member_id = '{$blocked_user_id}'");

        return $block_status > 0;
    }
}
