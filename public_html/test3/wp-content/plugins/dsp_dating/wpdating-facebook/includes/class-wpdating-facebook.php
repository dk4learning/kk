<?php

class Wpdating_Facebook
{
    protected $appId;
    protected $secretfb;

    public function __construct()
    {
        define('FACEBOOK_SDK_V4_SRC_DIR', WPDATING_FACEBOOK_ABSPATH . '/facebook-sdk-v5/');
        $this->load_dependencies();
        $this->define_public_hooks();
    }

    public function load_dependencies()
    {
        require_once WPDATING_FACEBOOK_ABSPATH . '/facebook-sdk-v5/autoload.php';
    }

    public function define_public_hooks()
    {
        add_action('parse_request', array($this, 'handle_api_requests'));
        add_action('wpdating_facebook_login', array($this, 'facebook_task'));
        add_action('wp_ajax_nopriv_get_facebook_login_url', array($this, 'dsp_get_facebook_login_url'));
        add_action('wpdating_facebook_api_login-callback', array($this, 'facebook_callback'));
        add_action('wp_logout', array($this, 'logout'));
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles(){
        wp_enqueue_style('wpdating-facebook-css', plugin_dir_url( __DIR__ ) . 'css/wpdating-facebook-css.css', array(), '', 'all');
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts(){
        wp_enqueue_script( 'wpdating-facebook-js', plugin_dir_url( __DIR__ ) . 'js/wpdating-facebook-js.js', array( 'jquery' ), '', true );
        wp_localize_script('wpdating-facebook-js', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function logout()
    {
        if ( ! isset( $_SESSION['FBRLH_state'] ) ) {
            return;
        }

        unset( $_SESSION['FBRLH_state'] );
        $appId = $this->get_api_key();
        if ( ! $appId) {
            return false;
        }

        $secretfb = $this->get_secret_key();
        if ( ! $secretfb) {
            return false;
        }

        $fb = new \Facebook\Facebook([
            'app_id'                => $appId,
            'app_secret'            => $secretfb,
            'default_graph_version' => 'v2.12',
            //'default_access_token' => '{access-token}', // optional
        ]);

        $helper = $fb->getRedirectLoginHelper();


        $helper->getLogoutUrl($_SESSION['facebook_access_token'], site_url() . '/members');

    }

    /**
     * Handle api requests
     */
    public function handle_api_requests()
    {
        global $wp;
        if ( ! empty($_GET['wpdating-facebook-api'])) {
            $wp->query_vars['wpdating-facebook-api'] = $_GET['wpdating-facebook-api'];
        }

        if ( ! empty($wp->query_vars['wpdating-facebook-api'])) {
            // Clean the API request.
            $api_request = strtolower($wp->query_vars['wpdating-facebook-api']);
            do_action('wpdating_facebook_api_' . $api_request);
        }
    }

    /**
     * Facebook callback- response from facebook after clicking the facebook button
     */
    public function facebook_callback()
    {
        $appId    = $this->get_api_key();
        $secretfb = $this->get_secret_key();

        $fb = new \Facebook\Facebook([
            'app_id'                => $appId,
            'app_secret'            => $secretfb,
            'default_graph_version' => 'v2.12',
            //'default_access_token' => '{access-token}', // optional
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client         = $fb->getOAuth2Client();
        $longLivedAccessToken = $accessToken;
        if ( ! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
                exit;
            }
        }

        if (isset($longLivedAccessToken)) {
            // Logged in!
            $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;

            // Now you can redirect to another page and use the
            // access token from $_SESSION['facebook_access_token']
        }

        // Sets the default fallback access token so we don't have to pass it to each request
        $fb->setDefaultAccessToken($longLivedAccessToken);

        try {
            $response = $fb->get('/me?fields=id,name,email,first_name,last_name,gender');
            $userNode = $response->getGraphUser();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $id         = $userNode->getId();
        $name       = $userNode->getName();
        $first_name = $userNode->getFirstName();
        $last_name  = $userNode->getLastName();
        $email      = $userNode->getEmail();
        $gender     = $userNode->getGender();

        $userProfile = compact('id', 'name', 'first_name', 'last_name', 'email', 'gender');
        if (function_exists('dsp_add_new_user')) {
            dsp_add_new_user($userProfile);
        }


        echo "<script>
            window.close();
            window.opener.location.href = '" . site_url() . "/members/';
        </script>";

        exit;
    }

    /**
     * Set appid, app secret key- Facebook buttom
     * @return bool | void
     */
    public function facebook_task()
    {
        $fbSettingStatus = get_facebook_login_setting('facebook_login', 'setting_status');
        if ( ! $fbSettingStatus || $fbSettingStatus == 'N') {
            return false;
        } ?>
        <div class="block">
            <div class="btn-fb-login">
                <a id="fb-btn" >
                    <span class="icon icon-fb"></span>
                    <span class="title"> <?php echo __( 'Facebook Login', 'wpdating' ); ?> </span>
                    <span class="facebook-login-loader">
                        <i class="fa fa-spinner fa-pulse fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </span>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Check if the facebook setting is turned on in dsp admin
     *
     * @param $condition
     * @param string $column
     *
     * @return bool|null|string
     */
    public function get_facebook_login_setting($condition, $column = 'setting_value')
    {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $facebookSettingStatus      = $wpdb->get_var($wpdb->prepare("SELECT `" . $column . "` FROM $dsp_general_settings_table WHERE setting_name = '%s'",
            $condition));
        if ($facebookSettingStatus) {
            return $facebookSettingStatus;
        } else {
            return false;
        }
    }

    /**
     * Get Api Key
     * @return bool|null|string
     */
    public function get_api_key()
    {
        $appId = get_facebook_login_setting('facebook_api_key');
        if ( ! $appId || $appId == '') {
            return false;
        }

        return $appId;
    }

    /**
     * Get Secret Get
     * @return bool|null|string
     */
    public function get_secret_key()
    {

        $secretfb = get_facebook_login_setting('facebook_secret_key');
        if ( ! $secretfb || $secretfb == '') {
            return false;
        }

        return $secretfb;
    }

    /**
     * Get Facebook Login url ajax request
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function dsp_get_facebook_login_url() {
        $api_key = get_facebook_login_setting('facebook_api_key');
        if ( ! $api_key ) {
            die( json_encode( array( 'status' => 'error', 'message' => 'API key missing.' ) ) );
        }

        $secret_key = get_facebook_login_setting('facebook_secret_key');
        if ( ! $secret_key ) {
            die( json_encode( array( 'status' => 'error', 'message' => 'Secret key missing.' ) ) );
        }

        $fb = new \Facebook\Facebook([
            'app_id'                => $api_key,
            'app_secret'            => $secret_key,
            'default_graph_version' => 'v2.12',
        ]);

        $helper      = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // optional
        $login_url   = $helper->getLoginUrl(site_url() . '/members?wpdating-facebook-api=login-callback', $permissions);

        die( json_encode( array( 'status' => 'success', 'login_url' => $login_url ) ) );

    }
}
