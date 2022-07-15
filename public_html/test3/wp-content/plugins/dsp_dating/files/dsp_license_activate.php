<?php
include_once(WP_DSP_ABSPATH . '/files/classes/class-license-handler.php');
global $isValidLicense, $licenseModuleSwitch;
$licenseHandler = new License_Handler();
//$msg = array('success'=> false);
$action = add_query_arg(
    array(
        'pid' => 'license_activate',
        'mode' => 'update'
    ),
    $settings_root_link
);

?>
<div id="general">
    <div class="postbox">
        <h3 class="hndle"><span><?php echo __('Activate License', 'wpdating'); ?></span></h3>
        <?php if ((!$isValidLicense || is_null($isValidLicense)) && $licenseModuleSwitch): ?>

            <form method="POST" action="<?php echo $action; ?>">
                <!-- License Key api settings -->
                <div class="inside">
                    <table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
                        <tbody>
                        <tr class="dsp-license">
                            <td class="form-field form-required" id="head">
                                <label><span><?php echo __('License Key', 'wpdating'); ?></span></label></td>
                            <td>
                                <input name="license_key" class="license_key regular-text" type="text"
                                       value="<?php echo $licenseHandler->getLicKey(); ?>"
                                       placeholder="<?php _e(__('Enter a valid license key for WP Dating Plugin.', 'wpdating')); ?>"
                                       data-nonce="<?php echo wp_create_nonce("license-nonce");; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="left"><span
                                    class="button button-primary license-activate"><?php echo __('Activate', 'wpdating'); ?></span><img
                                    src="<?php echo WPDATE_URL . '/images/ajax-loader.gif' ?>" class="loader"
                                    alt="Loading" style="display:none"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- End of License Key api settings -->
                <input type="hidden" name="license_save" value="save"/>
            </form>

        <?php else : ?>
            <p style="text-align: center;font-weight: bold;color: green;font-size: large;"><?php echo __('License is Activated', 'wpdating'); ?></p>
        <?php endif; ?>
    </div>
</div>
