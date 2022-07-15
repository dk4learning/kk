<?php
global $wpdb;

extract($_REQUEST);
$credits_plan_table    = $wpdb->prefix . DSP_CREDITS_PLAN_TABLE;
$exist_gateway_address = $wpdb->get_row( "SELECT * FROM $dsp_gateways_table" );
$business              = $exist_gateway_address->address;
$currency_code         = $exist_gateway_address->currency;

// echo $upgrade_credit;
if(isset($upgrade_credit)){

	// echo $credit_amount." ".$user_id." ".$no_of_credit_to_purchase;
	$credit_purchase_data  = array(
		'user_id'          => $user_id,
		'status'           => 0,
		'credit_price'     => $credit_amount,
		'credit_purchased' => $no_of_credit_to_purchase,
		'purchase_date'    => date( 'Y-m-d H:i:s' )
	);

	$wpdb->insert( $dsp_credits_purchase_history, $credit_purchase_data );
	$inserted_id = $wpdb->insert_id;
?>

		<form name="frm1" action="<?php echo $root_link . "setting/dsp_paypal/"; ?>" method="post">
			<input type="hidden" name="business" value="<?php echo $business ?>"/>
			<input type="hidden" name="currency_code" value="<?php echo $currency_code ?>"/>
			<input type="hidden" name="item_name" value="Credits Purchase"/>
			<input type="hidden" name="item_number" value="<?php echo $user_id ?>"/>
			<input type="hidden" name="amount" value="<?php echo $credit_amount ?>"/>
			<input type="hidden" name="return"
				value="<?php echo $root_link . "setting/credit_upgrade_account_details/credit_purchase_id/" . $inserted_id . "/"; ?>">
			<input type="hidden" name="notify_url"
				value="<?php echo $root_link . "setting/credit_upgrade_account_details/credit_purchase_id/" . $inserted_id . "/"; ?>">
		</form>

		<script type="text/javascript">
		document.frm1.submit();
	</script>

<?php } ?>
<div class=" "><div class="box-border"><div class="box-pedding clearfix">

	<div class="heading-submenu dsp-upgrade-heading">
        <strong>
            <?php echo __('Credits Plan', 'wpdating'); ?>
        </strong>
    </div>    

	<?php 
		$exists_credits_plan = $wpdb->get_results("SELECT * FROM $credits_plan_table");

		if($exists_credits_plan){
		foreach ($exists_credits_plan as $credits_plan) {
		$credits_name   	= $credits_plan->plan_name;
		$credits_amount 	= $credits_plan->amount;
		$credits_id   		= $credits_plan->credits_plan_id;
		$no_of_credits     	= $credits_plan->no_of_credits;
	?>
		<div class="dspdp-col-sm-12 dsp-sm-4">
			<div class="box-border dsp_upgrade-container">
				<div class="box-pedding dsp-upgrade-container dsp-upgrade-container-custom" style="display: inline-block;">
					<div class="setting-page__disable">
						<ul class="dspdp-row dspdp-xs-text-center">
	                    	<li class="dspdp-col-sm-3 dsp-sm-12">
								<div
									class="purchase-credit-heading dspdp-text-center dspdp-spacer"><?php echo language_code( 'DSP_PURCHASE_CREDITS' ); ?></div>
								<div class="purchase-credit-image dspdp-xs-form-group"><img
										class="dspdp-img-responsive dspdp-block-center"
										src='<?php echo WPDATE_URL . "/images/credit_purchase.png" ?>' alt="credit purchase"/>
								</div>
							</li>
							<li>
	                            <div class="dspdp-spacer dspdp-upgrade-desc"><strong><?php echo $credits_name; ?></strong></div>
	                        </li>
                            <li>
                                <div class="dspdp-spacer dspdp-upgrade-desc"><strong><?php echo $no_of_credits; ?> <?php echo __('Credits', 'wpdating'); ?></strong></div>
                            </li>
	                        <li class="dspdp-col-sm-4 dspdp-text-center dsp-sm-12 dsp-text-center">
	                        	<?php  
	                        		$gateway_table = $wpdb->get_results( "SELECT * FROM $dsp_gateways_table" );
	                        		foreach ($gateway_table as $gateway) {
	                        			 if ( $gateway->gateway_name == 'paypal' && $gateway->status == 1 ) {
	                        			 	?>
	                        			 	<div>
	                        			 		<form name="paymentfrm"
		                                          action="?credits_id=<?php echo $credits_id; ?>"
		                                          method="post">

		                                        <input type="hidden" name="credit_amount" class="credit_amount" value="<?php echo $credits_amount; ?>"/>

		                                        <input type="hidden" class="no_of_credit_to_purchase" name="no_of_credit_to_purchase" value="<?php echo $no_of_credits; ?>"/>

		                                        <input name="upgrade_credit" title="Upgrade / PayPal" type="submit"
		                                               value="<?php echo language_code( 'DSP_UPGRADE_PAYPAL_BTN' ) ?>"
		                                               class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
		                                               style="text-decoration:none;"/>
		                                               <br/>
		                                        <span style="font-size:13px; font-weight:bold;"
										      class="credit_price"><?php echo $currency_code." ".$credits_amount; ?></span>
		                                        <br/>
	                                   		 </form>
	                                   		</div>
	                        			 	<?php
	                        			 } else if ( $gateway->gateway_name == 'authorize' && $gateway->status == 1 ) { ?> 
	                        			 	<div>
	                        			 		<form name="paymentfrm"
                                          action="<?php echo $root_link . "setting/credit_auth_settings/"; ?>"
                                          method="post">
                                        <input type="hidden" name="credit_amount" class="credit_amount"
                                               value="<?php echo $credits_amount; ?>"/>

                                        <input type="hidden" class="no_of_credit_to_purchase"
                                               name="no_of_credit_to_purchase" value="<?php echo $no_of_credits; ?>"/>

                                        <input name="upgrade" title="Upgrade / Credit Card" type="submit"
                                               value="<?php echo language_code( 'DSP_UPGRADE_CREDITCARD_BTN' ) ?>"
                                               class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                               style="text-decoration:none; margin-top:5px;"/>
                                   				 </form>
                                                <span style="font-size:13px; font-weight:bold;"
                                                      class="credit_price"><?php echo $currency_code." ".$credits_amount; ?></span>
	                        			 	</div>
	                        			 	<?php
	                        			 } else if ( $gateway->gateway_name == 'paypal pro' && $gateway->status == 1 ) {
			                                ?>
			                                <div>
			                                    <form name="paymentfrm"
			                                          action="<?php echo $root_link . "setting/credit_pro_settings/"; ?>"
			                                          method="post">
			                                        <input name="upgrade" title="Upgrade / PayPal Pro" type="submit"
			                                               value="<?php echo language_code( 'DSP_UPGRADE_PAYPALPRO_BTN' ) ?>"
			                                               class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
			                                               style="text-decoration:none;"/>
			                                        <input type="hidden" name="credit_amount" class="credit_amount"
			                                               value="<?php echo $credits_amount; ?>"/>

			                                        <input type="hidden" class="no_of_credit_to_purchase"
			                                               name="no_of_credit_to_purchase" value="<?php echo $no_of_credits; ?>"/>
			                                    </form>
                                                <span style="font-size:13px; font-weight:bold;"
                                                      class="credit_price"><?php echo $currency_code." ".$credits_amount; ?></span>
			                                </div>
			                                <?php
			                            } else if ( $gateway->gateway_name == 'paypal advance' && $gateway->status == 1 ) {
                                    ?>
                                    <div>
                                        <form action="<?php echo $root_link . "setting/paypal_advance/"; ?>"
                                              method="post">
                                            <input type="hidden" name="item_name" id="item_name"
                                                   value="Credit Purchase"/>

                                            <input type="hidden" name="credit_amount" class="credit_amount"
                                                   value="<?php echo $credits_amount; ?>"/>

                                            <input type="hidden" class="no_of_credit_to_purchase"
                                                   name="no_of_credit_to_purchase" value="<?php echo $no_of_credits; ?>"/>
                                            <input type="hidden" name="payment_action" value="credit"/>
                                            <!--<input type="submit" value="<?php echo language_code( 'DSP_UPGRADE_PAYPALADV_BTN' ) ?>" name="btn_advance" />-->
                                            <input class="subscribe   dspdp-btn dspdp-btn-default" name="btn_advance"
                                                   type="submit"
                                                   value="<?php echo language_code( 'DSP_UPGRADE_PAYPALADV_BTN' ) ?>"/>
                                        </form>

                                        <span style="font-size:13px; font-weight:bold;"
                                              class="credit_price"><?php echo $currency_code." ".$credits_amount; ?></span>
                                        <br/>
                                    </div>
                                    <?php  
                            		} else if ( $gateway->gateway_name == 'iDEAL' && $gateway->status == 1 ) {
                                ?>
                                <div>
                                    <form action="<?php echo $root_link . "setting/credit_iDEAL/"; ?>" method="post">
                                        <input name="upgrade" title="Upgrade / iDEAL" type="submit"
                                               value="<?php echo language_code( 'DSP_UPGRADE_IDEAL_BTN' ) ?>"
                                               class="dsp_span_pointer  dspdp-btn dspdp-btn-default"
                                               style="text-decoration:none;"/>
                                        <input type="hidden" name="credit_amount" class="credit_amount"
                                               value="<?php echo $credits_amount; ?>"/>

                                        <input type="hidden" class="no_of_credit_to_purchase"
                                               name="no_of_credit_to_purchase" value="<?php echo $no_of_credits; ?>"/>
                                    </form>
                                    <span style="font-size:13px; font-weight:bold;"
                                          class="credit_price"><?php echo $currency_code." ".$credits_amount; ?></span>
                                </div>
                                <?php
                            		} 
	                        	}

	                        	?>
	                        </li>
								<?php 
								do_action( 'dsp_payment_addons_credit_plan', $credits_amount, $no_of_credits ,$credits_id);
								 ?>
                            <?php
                                if (is_plugin_active('wpdating-micropayment-addon/wpdating-micropayment-addon.php')) {
                                    $user_data = get_userdata($user_id);
                                    $data = array(
                                        'user_id'       => $user_id,
                                        'amount'        => $credits_amount,
                                        'title'         => $credits_name,
                                        'paytext'       => $credits_name,
                                        'credit_amount' => $no_of_credits,
                                        'email'         => $user_data->user_email,
                                        'credit_plan'   => $credits_id
                                    );
                                    do_action('wp_micropayment_form_button', $data);
                                }
                            ?>
                 		</ul>
			    	</div>
			    </div>
			</div>
		</div>
		<?php
		   	}
		}
	?>
    </div>

</div></div>