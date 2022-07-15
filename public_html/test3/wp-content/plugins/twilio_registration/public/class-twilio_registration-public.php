<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link        https://www.wpdating.com/
 * @since      1.0.0
 *
 * @package    Twilio_registration
 * @subpackage Twilio_registration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Twilio_registration
 * @subpackage Twilio_registration/public
 * @author      https://www.wpdating.com/ < https://www.wpdating.com/>
 */
class Twilio_registration_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Twilio_registration_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Twilio_registration_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/twilio_registration-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Twilio_registration_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Twilio_registration_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/twilio_registration-public.js', array('jquery'), $this->version, false);
        wp_enqueue_script('registercall', plugin_dir_url(__FILE__) . 'js/registercall.js', array('jquery'), $this->version, false);
       // wp_enqueue_script('ajax-script',  plugin_dir_url(__FILE__) . '/js/my-ajax-script.js', array('jquery'));
        wp_localize_script('registercall', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    function verify()
    {

        global $wpdb;
        $code = $_POST['code'];
        $phone_number = $_POST['phone_number'];
        $phone_verification = $wpdb->prefix . 'dsp_phone_verification';

        $query = "SELECT * FROM $phone_verification where phone='$phone_number'";

        $data = $wpdb->get_results($query);

        $date = date('Y-m-d H:i:s');

        if ($date <= $data[0]->expired_at) {
            if ($code == $data[0]->code) {
                echo '1';

            } else {

                echo 'Not matched. Enter Again';
            }
        } else {
            echo "Expired. Retry";
        }

        die();

    }


    function msg_send()
    {
        global $wpdb;
        $sid = '';
        $phone_number = $_POST['phone_number'];
        $smsCode = mt_rand(10000, 99999);
        $created_at = date('Y-m-d H:i:s');
        $expired_at = date('Y-m-d H:i:s', strtotime($created_at) + 600);
        include( __DIR__ . "/twilio.php");
        $phone_verification = $wpdb->prefix . 'dsp_phone_verification';
        if (!empty($sid)) {
            $query = "SELECT * FROM $phone_verification where phone='$phone_number'";
            $result = $wpdb->get_results($query);
            if (count($result) == 0) {
                $wpdb->query("INSERT INTO $phone_verification (phone,code,created_at,expired_at) values('$phone_number', '$smsCode', '$created_at', '$expired_at')");
            } else {
                $wpdb->query("UPDATE $phone_verification set code='$smsCode',created_at='$created_at',expired_at='$expired_at' where phone='$phone_number'");
            }
            echo true;
        } else {
            echo false;
        }
        die();
    }


    function validate_phone()
    {
        global $wpdb;
        $phone_number = $_POST['phone_number'];
        $query = "SELECT * FROM wp_dsp_user_profiles where phone_number='$phone_number'";
        $data = $wpdb->get_results($query);
        if (count($data) != 0) {
            echo false;
        }
        else {
            echo true;
        }
        die();
    }

    function validate()
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $usernameExist = username_exists($username);
        $emailExist = email_exists($email);
        if (!empty($usernameExist)) {
            echo "Username already exist";
        } else {
            if (!empty($emailExist)) {
                echo "Email already exist";
            } else {
                echo true;
            }
        }
        die();

    }

    public function registration_modal(){
        //including modal for phone verification
        include plugin_dir_path(__FILE__). "modal/phone_verification.php";

    }

    public function add_field()
    {
        $contents = plugin_dir_path(__FILE__). "CountryCodes.json";
        $countryCodes = json_decode(file_get_contents($contents), true);
        ?>
        <li class="dspdp-form-group dsp-form-group">
        <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label">Code</span>
        <span class="dsp-md-6 dspdp-col-sm-6">
                                                        <select name="ccode" id="ccode"
                                                                class="dsp-form-control dspdp-form-control  dspdp-xs-form-group">

                                                            <?php foreach ($countryCodes as $sl) { ?>
                                                                <option
                                                                    value="<?php echo $sl['dial_code']; ?>"><?php echo $sl['name']; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </span>
        </li>
        <li class="dspdp-form-group dsp-form-group">
            <span class="dsp-control-label dsp-md-3 dspdp-col-sm-3 dspdp-control-label">Phone Number</span>
            <span class="dsp-md-6  dspdp-col-sm-6"><input type="text" name="phone_number" id="phone_number"
                                                          class="text dsp-form-control dspdp-form-control validate-empty"
                                                          required/></span>
        </li>
    <?php }
}
