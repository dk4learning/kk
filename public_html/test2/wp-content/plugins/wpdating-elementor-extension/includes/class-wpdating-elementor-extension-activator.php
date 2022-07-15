<?php

/**
 * Fired during plugin activation
 *
 * @link        
 * @since      1.0.0
 *
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpdating_Elementor_Extension
 * @subpackage Wpdating_Elementor_Extension/includes
 * @author       < >
 */
class Wpdating_Elementor_Extension_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;

        $tblname = 'dsp_members_cover_photos';
        $table_name = $wpdb->prefix . "$tblname ";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        #Check to see if the table exists already, if not, then create it
        if( $wpdb->get_var( "show tables like '$table_name'" ) != $table_name ) {
            $sql = "CREATE TABLE " . $table_name . " (
                                  `photo_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                  `user_id` int(11) NOT NULL,
                                  `picture` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                                  `status_id` int(11) NOT NULL
                                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

            dbDelta($sql);
        }

        $dsp_messages_draft_table = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;
        $sql_query = "CREATE TABLE {$dsp_messages_draft_table} (
	    	            is_reply boolean DEFAULT 0);";
        dbDelta($sql_query);

        $members_page = get_option( 'wpee_member_page' );
        if ( ! empty( $members_page ) && ! is_array( $members_page ) ) {
            $post = get_post( $members_page );
            update_option( 'wpee_member_page', [get_locale() => $post->post_name] );
        }

        $profile_page = get_option( 'wpee_profile_page' );
        if ( ! empty( $profile_page ) && ! is_array( $profile_page ) ) {
            $post = get_post( $profile_page );
            update_option( 'wpee_profile_page', [get_locale() => $post->post_name] );
        }
	}

}
