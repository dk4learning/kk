<?php	
	/**
	 * Adding hooks
	 */
	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'hooks/registration-hooks.php';


    /**
     * This action is used to display send wink form
     *
     * @return html
     */
    add_action( 'wpee_profile_viewed', 'wpee_profile_viewed_cb' );
	if( !function_exists('wpee_profile_viewed_cb') ){
		function wpee_profile_viewed_cb ( $profile_id ){
			$current_user_id = get_current_user_id();
			if( $profile_id != $current_user_id && is_user_logged_in() ) {
				global $wpdb;
				$dsp_counter_hits_table = $wpdb->prefix . DSP_COUNTER_HITS_TABLE;
				$review_date = date("Y-m-d");
			    $count = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_counter_hits_table WHERE user_id='$current_user_id' AND member_id='$profile_id' AND review_date='$review_date'");
			    if ( $count <= 0 ) {
			        $wpdb->query("INSERT INTO $dsp_counter_hits_table SET user_id='$current_user_id', member_id='$profile_id', review_date='$review_date' ");
			    }
			    dsp_add_notification($current_user_id, $profile_id, 'view_profile');
			}
		}
	}

    /**
     * This action is used to display send wink form
     *
     * @return html
     */
    add_action('wpee_gift_dropdown','wpee_show_gift');
    if( !function_exists( 'wpee_show_gift' ) ){
    	function wpee_show_gift(){
    		global $wpdb;
    		$profile_id = wpee_profile_id();
    		$dsp_virtual_gifts = $wpdb->prefix . DSP_VIRTUAL_GIFT_TABLE;
    	    $virtual_gifts     = $wpdb->get_results( "select * from $dsp_virtual_gifts" );

    	    $output = '<div id="wpee-gift-wrapper" class="wpee-gift-inner"><form method="post">';
    	    foreach ( $virtual_gifts as $gift_row ) {
    	        $output .= '<div class="form-group"><input type="radio" name="wpee-gift" class="wpee-gift" value="'.$gift_row->image.'"/><img src= "' . content_url('/uploads/dsp_media/gifts/') . $gift_row->image . '" value= "' . $gift_row->image . '"  Page ' . $gift_row->id . '/></div>';
    	    }
    	    $output .= '<input type="hidden" name="wpee-gift-option"class="wpee-gift-option" value="'.wp_create_nonce("wpee_choose_gift_nonce").'"/><input type="hidden" name="profile-id" class="wpee-profile-id" value="'. esc_attr( $profile_id ) .'"/><input type="submit"   class="wpee-gift-submit btn" value="' . esc_html__( 'Send','wpdating' ) . '" />
    	                </form></div>';
    	    echo $output;
    	}
    }

    /**
     * This action is used to display send wink form
     *
     * @return html
     */
    add_action( 'wpee_send_wink_form', 'wpee_send_wink_form_cb' );
    if( !function_exists( 'wpee_send_wink_form_cb' ) ){
        function wpee_send_wink_form_cb( $receiver_id) { 
            global $wpdb;
            $dsp_language_detail_table = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
            $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
            $lang_id = null; //default case where session is not set and not loggin
            if (isset($_SESSION['default_lang'])) {
                $lang_id = $_SESSION['default_lang']; //session is set
            } else {
                $all_languages = $wpdb->get_row("SELECT * FROM $dsp_session_language_table where user_id='" . get_current_user_id() . "' ");
                $lang_id = $all_languages->language_id; //logged  in and  session is not set

            }
            $all_languages = $wpdb->get_row("SELECT * FROM $dsp_language_detail_table where language_id='" . $lang_id . "'");
            $language_name = !empty($all_languages->language_name) ? $all_languages->language_name : 'english';
            $table_name = strtolower(trim(esc_sql(substr($language_name, 0, 2))));
            if ($language_name == 'english') {
                $tableName = "dsp_flirt";
            } else {
               $tableName = "dsp_flirt_" . strtolower(substr($table_name, 0, 2));
            }
            $tableName =  $wpdb->prefix .$tableName;

            $wink_text = $wpdb->get_results("SELECT * FROM $tableName Order by Flirt_ID");

            ?>
            <div class="wpee-send-wink-msg">
                <form name="sendwinkfrm" action="" method="post" class="dspdp-form-inline">
                    <input type="hidden" name="receiver_id" value="<?php echo esc_attr( $receiver_id ); ?>" />
                    <input type="hidden" name="action" value="wpee_send_wink" />
                    <?php wp_nonce_field( 'wpee_send_wink_form_nonce', 'wpee-send-wink-nonce' );?>
                    <div class="box-page box-border">
                        <div class="dspdp-spacer-sm"><strong><?php echo esc_html__('Send Wink', 'wpdating'); ?></strong></div>
                        <div class="form-group">
                            <select  name="wink_text_id" class="form-control">
                                <?php
                                foreach ($wink_text as $wink) {
                                    ?>
                                    <option value="<?php echo esc_attr( $wink->Flirt_ID ); ?>" ><?php echo esc_html( $wink->flirt_Text ); ?></option>
                                <?php } ?>
                            </select>                    
                            <input type="button"  class="wpee-send-wink-btn" name="send_flirt" value="<?php echo esc_html__('Send Wink', 'wpdating'); ?>">
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }
    }
	
	/**
	 * This action is used to display the fields in the edit profile section
	 * from given arrays
	 *
	 */

	add_action( 'wpee_display_question_by_order', 'wpee_display_question_by_order_func', 10, 2 );
	if ( ! function_exists( 'wpee_display_question_by_order_func' ) ) {
	    function wpee_display_question_by_order_func( $updateExitOption, $partner = 0 ) {
	        global $wpdb;
	        $dsp_session_language_table = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
	        $dsp_language_detail_table  = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
	        if ( isset( $_SESSION['default_lang'] ) ) {
	            $lang_id = $_SESSION['default_lang'];
	        } else {
	            $adminLangId = $wpdb->get_var( $wpdb->prepare( "SELECT `language_id` FROM {$dsp_language_detail_table} where display_status = '%d'", 1 ) );
	            if ( is_user_logged_in() ) {
	                $userSessionLangId = $wpdb->get_var( "SELECT  `language_id` FROM {$dsp_session_language_table} where user_id='" . get_current_user_id() . "' " );
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
	                    do_action( 'wpee_dropdown_field', $profile_questions, $updateExitOption, $lang_code );
	                    break;

	                case 2:
	                    do_action( 'wpee_textbox_field', $profile_questions, $updateExitOption, $lang_code, $partner );
	                    break;

	                case 3:
	                    do_action( 'wpee_multiple_choice_field', $profile_questions, $updateExitOption, $lang_code );
	                    break;

	                default:
	                    # code...
	                    break;
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

add_action( 'wpee_dropdown_field', 'wpee_dropdown_field_func', 10, 3 );
if ( ! function_exists( 'wpee_dropdown_field_func' ) ) {
    function wpee_dropdown_field_func( $profile_questions, $existOptions, $lang_code ) {
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
        <div class="form-group">
            <label for="q_opt_ids<?php echo $ques_id ?>"><?php echo __( $profile_ques, 'wpdating' ); ?>:</label>
            <?php if ( $profile_questions->required == "Y" ) { ?>
                <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>"/>
                <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>"/>
            <?php } ?>
            <select class="dsp-form-control dspdp-form-control" name="option_id[<?php echo $ques_id ?>]"
                    id="q_opt_ids<?php echo $ques_id ?>">
                <option value="0"><?php echo __( 'Select','wpdating' ); ?></option>
                <?php
                $myrows_options = $wpdb->get_results( "SELECT * FROM {$dsp_question_options_table} Where question_id='{$ques_id}' Order by sort_order" );
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
        </div>
        <?php
    }
}


/**
 * This action is used to add  multiple choice field
 *
 *
 * @return  String
 */

add_action( 'wpee_textbox_field', 'wpee_textbox_field_func', 10, 4 );
if ( ! function_exists( 'wpee_textbox_field_func' ) ) {
    function wpee_textbox_field_func( $profile_questions, $existOptions, $lang_code, $partner = 0 ) {
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
        <div class="form-group">
            <label><?php echo __( $profile_ques, 'wpdating' ); ?>
                :</label>
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
                <textarea class="dsp-form-control dspdp-form-control" name="option_id1[<?php echo $ques_id ?>]"
                              id="text_option_id<?php echo $ques_id ?>"
                              maxlength="<?php echo $profile_ques_max_length; ?>"
                              rows="6"><?php echo trim( $text_value ) ?></textarea>
        </div>
    <?php }
}

/**
 * This action is used to add  multiple choice field
 *
 *
 * @return  String
 */

add_action( 'wpee_multiple_choice_field', 'wpee_multiple_choice_field_func', 10, 3 );
if ( ! function_exists( 'wpee_multiple_choice_field_func' ) ) {
    function wpee_multiple_choice_field_func( $profile_questions, $existOptions, $lang_code ) {
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
        <div class="form-group">
            <label><?php echo __( $profile_ques, 'wpdating' ); ?>
                :</label>
            <?php if ( $required == "Y" ) { ?>
                <input type="hidden" name="hidprofileqques" value="<?php echo $profile_ques; ?>"/>
                <input type="hidden" name="hidprofileqquesid" value="<?php echo $ques_id; ?>"/>
            <?php } ?>
            <select class="dsp-multiple-select dsp-form-control dspdp-form-control chosen chzn-done"
                    name="option_id2[<?php echo $ques_id ?>][]" id="q_opt_ids<?php echo $ques_id ?>"
                    multiple="true">
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
        </div>
    <?php }
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

/**
 * Clean user cache and cokkie
 *
 * @since    1.0.0
 */
if (!function_exists('clean_user_cache_and_cookie')) {
    function clean_user_cache_and_cookie() {
        if( is_user_logged_in() && !is_admin()){
            $user_id = get_current_user_id();
            clean_user_cache( $user_id );
            $user = new WP_User($user_id);
            update_user_caches($user);
        }
    }
}
add_action('init', 'clean_user_cache_and_cookie');