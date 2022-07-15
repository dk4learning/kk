<?php 
global $wpdb;
$profile_subtab = get_query_var( 'profile-subtab' );
$profile_subtab = !empty( $profile_subtab ) ? $profile_subtab : 'account';
$user_id = get_current_user_id();
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
$count_my_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$user_id'");
$profile_link = wpee_get_profile_url_by_id($user_id);
?>

<div class="settings-list-wrapper profile-section-wrap main-profile-mid-wrapper">
	<ul class="profile-section-tab">
		<li class="profile-section-tab-title <?php echo ( $profile_subtab == 'account' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/account');?>"><?php esc_html_e( 'Account', 'wpdating' );?></a></li>
		<li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'blocked-members' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/blocked-members');?>"><?php esc_html_e( 'Blocked Members', 'wpdating' );?></a></li>
		<li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'privacy' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/privacy');?>"><?php esc_html_e( 'Privacy', 'wpdating' );?></a></li>
		<li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'delete-account' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/delete-account');?>"><?php esc_html_e( 'Delete Account', 'wpdating' );?></a></li>
		<li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'upgrade-account' ) ? 'active' : '';?>"><a href="<?php echo esc_url(trailingslashit($profile_link).'settings/upgrade-account');?>"><?php esc_html_e( 'Upgrade Account', 'wpdating' );?></a></li>
	</ul>
	<div class="profile-section-content">
	    	<?php
			if( !empty( $profile_subtab ) && $profile_subtab == 'account' ){
				wpee_locate_template('profile/notification-blocks/my-profile/settings/account.php');
			}
			elseif( !empty( $profile_subtab ) && $profile_subtab == 'blocked-members' ){
				wpee_locate_template('profile/notification-blocks/my-profile/settings/blocked-members.php');
			}
			elseif( !empty( $profile_subtab ) && $profile_subtab == 'privacy' ){
				wpee_locate_template('profile/notification-blocks/my-profile/settings/privacy.php');
			}
			elseif( !empty( $profile_subtab ) && $profile_subtab == 'delete-account' ){
				wpee_locate_template('profile/notification-blocks/my-profile/settings/delete-account.php');
			}
			elseif( !empty( $profile_subtab ) && $profile_subtab == 'upgrade-account' ){
				wpee_locate_template('profile/notification-blocks/my-profile/settings/upgrade-account.php');
			}
			elseif( !empty( $profile_subtab ) && $profile_subtab == 'credit-upgrade-details' ){
				wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/credits-upgrade/credit-upgrade-details.php");
			}
			elseif( $profile_subtab == 'thank-you'){
				wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/thank-you.php");
			}
			elseif( $profile_subtab == 'cancel'){
				wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/cancel.php");
			}
			elseif( $profile_subtab == 'dsp_paypal'){
			    $action = isset($_POST['action']) ? $_POST['action'] : '';
			    $discountStatus      = get('discountStatus');
			    $isDiscountModuleOff = dsp_check_discount_code_setting();
			    if ((isset($action) && ! empty($action)) || $isDiscountModuleOff) {
			        $_GET['action'] = 'process';
			    }
			    wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/gateway/paypal/paypal.php");
			}
            elseif( !empty( $profile_subtab ) && $profile_subtab == 'bank-wire'){
                wpee_locate_template("profile/notification-blocks/my-profile/settings/payment/gateway/bank-wire/index.php");
            }
			?>
	</div>
</div>