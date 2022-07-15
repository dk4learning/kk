<?php

/**
 * Fired during plugin activation
 *
 * @link        https://www.wpdating.com/
 * @since      1.0.0
 *
 * @package    Twilio_registration
 * @subpackage Twilio_registration/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Twilio_registration
 * @subpackage Twilio_registration/includes
 * @author      https://www.wpdating.com/ < https://www.wpdating.com/>
 */
class Twilio_registration_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

        global $wpdb;
        $charset_collate      = $wpdb->get_charset_collate();
        $phone_verification = $wpdb->prefix . "dsp_phone_verification";
        $dsp_user_profiles_table = $wpdb->prefix . 'dsp_user_profiles';

        $sql = "CREATE TABLE $phone_verification (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  phone varchar(55),
			  code varchar(55),
			  created_at datetime  NULL,
              expired_at datetime NULL ,
			  PRIMARY KEY  (id)
			) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $wpdb->query("ALTER TABLE $dsp_user_profiles_table
                ADD phone_number varchar(55) NOT NULL");


    }
}
