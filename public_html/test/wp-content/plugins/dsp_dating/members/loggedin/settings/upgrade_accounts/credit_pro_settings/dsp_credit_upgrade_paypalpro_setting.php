<script type="text/javascript">
    function checkValidation()
    {
        if (document.getElementById('customer_credit_card_number').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('cc_expiration_year').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('cc_expiration_month').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('cc_cvv2_number').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_first_name').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_last_name').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_address1').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_city').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_state').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        else if (document.getElementById('customer_zip').value == "")
        {
            alert('<?php echo __('Fields should not be empty.', 'wpdating'); ?>');
            return false;
        }
        return true;

    }
</script>
<?php
$DSP_GATEWAYS_TABLE = $wpdb->prefix . DSP_GATEWAYS_TABLE;

extract($_REQUEST);
$apiDetailsQuery = "SELECT pro_api_username,pro_api_password,pro_api_signature FROM $DSP_GATEWAYS_TABLE where gateway_id=3";
//	echo '<br>'.$apiDetailsQuery;
$apiDetailsRes = $wpdb->get_row($apiDetailsQuery);

$my_api_username = $apiDetailsRes->pro_api_username;

$my_api_password = $apiDetailsRes->pro_api_password;

$my_api_signature = $apiDetailsRes->pro_api_signature;

if ($my_api_username != '' && $my_api_password != '' && $my_api_signature != '') {
    $currency_code = isset($_REQUEST['currency_code']) ? $_REQUEST['currency_code'] : 'USD';
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <?php if (get('reason') != '') { ?>
                <div align="center" style="color:#FF0000;"><b><?php echo urldecode(get('reason')); ?></b></div>
                <?php
            }

            if (get('mode') == 'cancel') {
                ?>
                <script type='text/javascript'> location.href = '<?php echo $root_link . "setting/upgrade_account/"; ?>'</script>
                <?php
            }
            ?>
            <div class="box-page">

                <form action="<?php echo $root_link . "setting/credit_pro_settings_detail/"; ?>" method="post" onsubmit="return checkValidation();">
                    <div style="width: 100%;text-align: right;">
                        <span>*<?php echo __('Fields are mandatory', 'wpdating'); ?></span>
                    </div>
                    <div class="card-box">
                        <div style="margin-bottom: 20px;margin-top: 12px;"><img src="<?php  echo WPDATE_URL .  "/images/discover.png"; ?>" alt="Discover"/></div>
                        <div style="margin-bottom: 20px;"><img src="<?php  echo WPDATE_URL .  "/images/visa.jpg"; ?>" alt="Visa" /></div>
                        <div style="margin-bottom: 20px;"><img src="<?php  echo WPDATE_URL .  "/images/mastercard.jpg"; ?>" alt="Master Card" /></div>
                        <div style="margin-top: 0px;"><img src="<?php echo WPDATE_URL . "/images/americanexpress.jpg"; ?>" alt="American Express" /></div>
                    </div>
                    <ul class="upgrade-details-page">



                        <li><span><font style="color: red">*</font><?php echo __('Credit Card', 'wpdating'); ?>:</span>
                            <input type="radio" name="customer_credit_card_type" value="Visa" checked="checked"><?php echo __('Visa', 'wpdating') ?>
                            <input type="radio" name="customer_credit_card_type" value="MasterCard"><?php echo __('MasterCard', 'wpdating') ?><br>
                            <input type="radio" name="customer_credit_card_type" value="Discover"><?php echo __('Discover', 'wpdating') ?>
                            <input type="radio" name="customer_credit_card_type" value="Amex"><?php echo __('Amex', 'wpdating') ?>

                        </li>
                        <li><span><font style="color: red">*</font><?php echo __('Credit Card Number', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_credit_card_number" name="customer_credit_card_number" value=""> 
                        </li>
                        <li><span><font style="color: red">*</font><?php echo __('Expiration Year(yyyy)', 'wpdating'); ?>:</span> <input type="text" class="text" size="4" id="cc_expiration_year" name="cc_expiration_year" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('Expiration Month(mm)', 'wpdating'); ?>:</span> <input type="text" class="text" size="4" id="cc_expiration_month" name="cc_expiration_month" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('CCV', 'wpdating'); ?>:</span> <input type="text" class="text" size="4" id="cc_cvv2_number" name="cc_cvv2_number" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('First Name', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_first_name" name="customer_first_name" value=""> </li>
                        <li><span><font style="color: red">*</font><?php echo __('Last Name', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_last_name" name="customer_last_name" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('Address-1', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_address1" name="customer_address1" value=""> </li>
                        <li><span><?php echo __('Address-2', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" name="customer_address2"  value=""> </li>
                        <li><span><font style="color: red">*</font><?php echo __('City:', 'wpdating'); ?></span> <input type="text" class="text" size="15" id="customer_city" name="customer_city" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('State', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_state" name="customer_state" value=""></li>
                        <li><span><font style="color: red">*</font><?php echo __('Country:', 'wpdating'); ?></span>

                            <select id="customer_country" name="customer_country">
                                <?php
                                $strCountries = $wpdb->get_results("SELECT * FROM $dsp_country_table ORDER BY name");

                                foreach ($strCountries as $rdoCountries) {

                                    if ($exist_profile_details->country_id == $rdoCountries->country_id) {
                                        echo "<option value='" . $rdoCountries->name . "' selected='selected' >" . $rdoCountries->name . "</option>";
                                    } else {
                                        echo "<option value='" . $rdoCountries->name . "' >" . $rdoCountries->name . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </li>
                        <li><span><font style="color: red">*</font><?php echo __('Zip', 'wpdating'); ?>:</span> <input type="text" class="text" size="15" id="customer_zip" name="customer_zip" value=""></li>
                        <li><input name="submit" type="submit" value="<?php echo __('Submit', 'wpdating'); ?>" /><input name="cancel" type="button" value="<?php echo __('Cancel', 'wpdating'); ?>" onclick="CancelPayment()" style="margin-left:30px;" /></li>
                        <li>
                            <input type="hidden" name="currency_code" value="<?php echo $currency_code ?>" />
                            <input type="hidden" name="payment_amuont" id="credit_amount" value="<?php echo $credit_amount; ?>" /> 
                            <input type="hidden" id="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="<?php echo $no_of_credit_to_purchase; ?>" /></li>
                    </ul>
                    <div style="font-size:12px; float:left; width:100%;"><?php echo __('<b>Please Note:</b> We do not store your credit card information.', 'wpdating') ?></div>
                </form>
            </div>
        </div></div>
<?php } else {
    ?>
    <div class="dsp_search_result_box_out">
        <div class="dsp_search_result_box_in">
            <div align="center" style="color:#FF0000;"><b><?php echo __('There is some internal error ,please contact the admin.', 'wpdating'); ?></b></div>
        </div>
    </div>

<?php }
?>