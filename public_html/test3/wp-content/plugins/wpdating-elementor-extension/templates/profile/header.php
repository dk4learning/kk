<?php 
global $wpdb, $wpee_settings;
$dsp_credits_usage_table = $wpdb->prefix . DSP_CREDITS_USAGE_TABLE;
$dsp_profile_table       = $wpdb->prefix . 'dsp_user_profiles';
$user_id                 = wpee_profile_id();
$current_user_id         = get_current_user_id();
$userprofile             = wpee_get_data( $dsp_profile_table, 'user_id', $user_id ); // fetch profile using userid
$fulname                 = wpee_get_user_display_name_by_id($user_id);
$profile_page            = get_option( 'wpee_profile_page', '' );
$profile_page_url        = get_permalink( $profile_page );
$profile_url             = wpee_user_profile_url();
$profile_tab             = !empty( get_query_var('profile-tab') ) ? get_query_var( 'profile-tab' ) : 'profile';
$imagepath               = content_url('/') ;
$age                     = !empty($userprofile->age) ? GetAge($userprofile->age) : '';
$dsp_cover_photos_table  = $wpdb->prefix .'dsp_members_cover_photos';
$country_name            = !empty($userprofile->country_id) ? wpee_get_country_name($userprofile->country_id) : '';
$cover_image             = $wpdb->get_row("Select picture from $dsp_cover_photos_table where user_id=$user_id;");
$image_link              = $cover_image ? content_url('/uploads/dsp_media/user_photos/user_' . $user_id . '/cover_photo/'. $cover_image->picture) : '';
$check_photos_mode       = wpee_get_setting('picture_gallery_module');
$check_my_friend_module  = wpee_get_setting('my_friends');
$check_message_mode      = wpee_get_setting('free_email_access');
$check_credit_mode       = wpee_get_setting('credit');
$no_of_credits           = $wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$current_user_id'");
$no_of_credits           = ! empty($no_of_credits) ? $no_of_credits : 0;
$is_current_user         = ( $user_id == $current_user_id );
do_action('wpee_profile_viewed', $user_id);
?>
<div class="profile-cover-photo">
	<span class="profile-cover-image overlay" style="background-image:url('<?php echo $image_link;?>');"> </span>
</div>

<div class="wpee-container">
	<!-- User Profile Detail Start -->
	<div class="profile-user-details d-flex align-bottom" style="margin-top: -200px;">
		<!-- Profile Image Start -->
		<div class="profile-img change-profile-image p-rel" class="profile_image_change">

		    <div class="loader" style="display: none"></div>

		    <figure class="overflow-h">
			    <a class="group1 profile_picture_link"
			       href="<?php echo display_members_original_photo($user_id, $imagepath); ?>"> 
			       <img src="<?php echo display_members_photo($user_id, $imagepath); ?>" class="img" id="profile_picture" alt="<?php echo wpee_get_user_display_name_by_id($user_id); ?>">
			    </a>
			</figure>

		    <?php if ( is_user_logged_in() && $is_current_user ) : ?>
                <form id="change-profile-pic-form" method="post" enctype="multipart/form-data" autocomplete="off">
                    <?php wp_nonce_field( 'wpee_profile_photo_nonce', 'wpee_profile_photo_security' );?>
                    <input name="user_id" value="<?php echo $current_user_id; ?>" type="hidden"/>
                    <input name="action" value="wpee_change_profile_photo" type="hidden"/>
                    <input type="file" name="update_profile_pic" id="update_profile_pic" style="display: none" accept="image/jpg,image/jpeg,image/gif,image/png">
                </form>
                <div class="update_profile_text_div" onclick="jQuery('#update_profile_pic').click();"
                     title="<?php _e( 'Update Profile Picture', 'wpdating' ); ?>">
                    <a id="change-profile-pic" class="">
                        <i class="fa fa-camera" aria-hidden="true"></i>
                    </a>
                </div>
		    <?php endif; ?>

		    <div class="wpee-user-status <?php echo ( wpee_get_online_user($user_id) ) ? 'wpee-online' : 'wpee-offline';?>"></div>
		</div>
		<!-- Profile Image Start -->

		<!-- User Information Start -->
		<div class="user-info-wrapper">
			<h4 class="username text-white"><?php echo wpee_get_user_display_name_by_id($user_id);?></h4>

			<div class="age-location-wrap text-white">
				<?php if( !empty($age) ):?>
					<div class="profile-age">
						<span class="age-title"><?php echo __( 'Age:','wpdating'); ?></span>
						<?php echo intval($age);?>
					</div>
				<?php endif;?>
				<?php if( !empty($country_name) ):?>
					<div class="profile-location">
						<span class="location-title"><?php echo __( 'Location:','wpdating'); ?></span>
						<?php echo (!empty($userprofile->city_id)) ? wpee_get_city_name($userprofile->city_id) .', '  : ''; ?><?php echo (!empty($userprofile->state_id)) ? wpee_get_state_name($userprofile->state_id) .', '  : ''; ?><?php echo esc_html($country_name); ?>

					</div>
				<?php endif;?>
				<?php if($check_credit_mode->setting_status == 'Y' && get_current_user_id() == $user_id ) {?>
						<div class="profile-credits">
								<span class="location-title"><?php echo __( 'Credits:', 'wpdating' ); ?></span>
								<?php echo esc_html($no_of_credits); ?>
						</div>
					<?php }?>
			</div>

			<div class="account-status">
				<div class="profile-location">
					<span class="profile-status-title"><?php echo __( 'Account Level:', 'wpdating' ); ?></span>
					<?php 
					echo !empty(wpee_is_premium($user_id) ) ? ucwords(wpee_is_premium($user_id)) . ' '. esc_html__('Member', 'wpdating') : ''; ?>
				</div>
			</div>
		</div>
		<!-- User Information End -->
		<?php 
		// Show only if user is logged in
		if( is_user_logged_in() ): ?>
			<?php if( get_current_user_id() == $user_id ): ?>
				<div class="profile-header-right">
					<!-- Cover Image Start -->
					<div class="change-cover-photo">
						<form id="formId" method="post" enctype="multipart/form-data" autocomplete="off">
							<p><?php _e( 'Upload File', 'wpdating'); ?></p>
							<input type="file" name="file" id="file" />
							<?php wp_nonce_field( 'wpee_profile_cover_nonce', 'security' );?>
							<input name="action" value="wpee_profile_cover_photo" type="hidden"/>
							<input name="submit" value="upload" type="submit"/>
						</form>
						<a class="trigger-cover-photo" href="javascript:void(0)"><?php echo __( 'Edit Cover Photo','wpdating'); ?></a>
					</div>
				</div>
			<?php
			else: ?>
				<div class="profile-header-right d-flex">
					<!-- Send Friend Request -->
					<?php if ($check_my_friend_module->setting_status == 'Y') : 

			            $dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;
			            $dsp_user_notification_table = $wpdb->prefix . DSP_USER_NOTIFICATION_TABLE;
			            $dsp_email_templates_table = $wpdb->prefix . DSP_EMAIL_TEMPLATES_TABLE;
			            $dsp_user_table = $wpdb->users;
			            
            			$num_rows = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$current_user_id AND friend_uid=$user_id AND approved_status='Y'");
                		$num_rows2 = $wpdb->get_var("SELECT COUNT(*) FROM  $dsp_my_friends_table WHERE user_id=$current_user_id AND friend_uid=$user_id AND approved_status='N'");
			            if( $num_rows < 1 ){
			            	if( $num_rows2 > 0){
								?>
								<div class="wpee-friend-request">
									<a href="javascript:void(0);"  data-action="delete" data-friend-id="<?php echo esc_attr($user_id);?>" class="dsp-btn-default wpee-add-friend-trigger wpee-friend-<?php echo esc_attr( $user_id );?>"><?php echo __('Cancel Friend Request', 'wpdating') ?></a> 
								</div>
								<?php }
							else{?>
								<div class="wpee-friend-request">
									<a href="javascript:void(0);"  data-action="add" data-friend-id="<?php echo esc_attr($user_id);?>" class="dsp-btn-default wpee-add-friend-trigger wpee-friend-<?php echo esc_attr( $user_id );?>"><?php echo __('Send Friend Request', 'wpdating') ?></a> 
								</div>
								<?php
							}
						} ?>
					<?php endif;?>
					<!-- Send Friend Request -->
					<!-- Block user -->
					<div class="wpee-block-user">
						<?php 
        				$dsp_blocked_members_table = $wpdb->prefix . 'dsp_blocked_members';
						$blocked_id = $wpdb->get_var("Select blocked_id from $dsp_blocked_members_table where user_id=$current_user_id and block_member_id=$user_id");
						if( $blocked_id ):
						?>
							<a href="javascript:void(0);"  data-action="delete" data-block-id="<?php echo esc_attr($blocked_id);?>" data-profile-id="<?php echo esc_attr($user_id);?>" class="dsp-btn-default wpee-block-trigger wpee-block-<?php echo esc_attr( $user_id );?>"><?php echo __('Unblock this user', 'wpdating') ?></a>
						<?php 
						else: ?>							
							<a href="javascript:void(0);"  data-action="add" data-block-id="<?php echo esc_attr($blocked_id);?>" data-profile-id="<?php echo esc_attr($user_id);?>" class="dsp-btn-default wpee-block-trigger wpee-block-<?php echo esc_attr( $user_id );?>"><?php echo __('Block this user', 'wpdating') ?></a>
							<?php
						endif;?> 
					</div>
					<!-- Block user -->
				</div>
			<?php endif;
		endif;?>
	</div>
	<?php
	if( ( isset($wpee_settings['profile_header_notification']) && $wpee_settings['profile_header_notification'] ) || ( isset($wpee_settings['profile_header_menu']) && $wpee_settings['profile_header_menu'] ) ): ?>
	<!-- User Profile Header Menu Starts -->
		<div class="profile-header-menu">
			<div class="profile-menu-wrapper d-flex space-bet">
				<?php
				if(isset($wpee_settings['profile_header_menu']) && $wpee_settings['profile_header_menu'] ): ?>
					<ul class="profile-menu-tab">
						<?php 
						if( $user_id == $current_user_id ): ?>
							<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'activity' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/activity"><?php esc_html_e( 'Activity', 'wpdating' ); ?></a></li>
						<?php endif; ?>
						<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'profile' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/profile"><?php esc_html_e( 'Profile', 'wpdating' ); ?></a></li>
						<?php if( $check_photos_mode->setting_status == 'Y' ): ?>
							<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'media' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/media"><?php esc_html_e( 'Media', 'wpdating' ); ?></a></li>
							<?php 
						endif;
						if ($check_message_mode->setting_status == 'Y' && $user_id == $current_user_id ): ?>
							<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'message' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/message"><?php esc_html_e( 'Message', 'wpdating' ); ?></a></li>
						<?php endif;
						if( $user_id == $current_user_id ): ?>
							<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'search' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/search"><?php esc_html_e( 'Search', 'wpdating' ); ?></a></li>
						<?php 
						endif;
						if ($check_my_friend_module->setting_status == 'Y') : ?>
							<li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'friends' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/friends"><?php esc_html_e( 'Friends', 'wpdating' ); ?></a></li>
						<?php endif;?>
                        <?php if ($user_id == $current_user_id && is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')) : ?>
                            <li class="profile-menu-tab-list <?php echo ( !empty( $profile_tab ) && $profile_tab == 'instant-chat' ) ? 'active' : '';?>"><a href="<?php echo esc_url( $profile_url );?>/instant-chat"><?php esc_html_e( 'Chats', 'wpdating' ); ?></a></li>
                        <?php endif;?>
					</ul>
				<?php
				endif;
				if(isset($wpee_settings['profile_header_notification']) && $wpee_settings['profile_header_notification'] && is_user_logged_in() ): ?>
					<div class="profile-notification-blocks">
						<div class="wpee-notification-block-wrap">			    
				    		<?php wpee_locate_template('profile/notification-blocks/notification-blocks.php');?>	 
					    </div>
					</div>
				<?php endif;?>
			</div>
		</div>
		<!-- User Profile Header Menu #Ends -->
	<?php endif;?>
</div>
<!-- User Profile Detail End -->
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>

<?php if ( is_user_logged_in() && $is_current_user ) :
    do_action('wp_gallery_enqueue_styles');
    ?>
    <script type="module">
        // import Pintura Image Editor functionality:
        import {openDefaultEditor} from "<?php echo WPDATING_GALLERY_URL; ?>lib/js/pintura.min.js";

        const ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';

        // Listen for changes on a profile image file input element
        const profilePic = document.querySelector('.change-profile-image input[type="file"]');
        profilePic.addEventListener('change', e => {
            const file = profilePic.files[0];

            if ( file === 'undefined' ) {
                // alert('Upload your file');
            } else {
                const editor = openDefaultEditor({
                    src: file
                });
                editor.on('process', ({ dest }) => {
                    let form_data = new FormData();
                    form_data.append('file', dest);
                    form_data.append('nonce', jQuery("#change-profile-pic-form input[name=wpee_profile_photo_security]").val());
                    form_data.append('action', jQuery("#change-profile-pic-form input[name=action]").val());
                    form_data.append('user_id', jQuery("#change-profile-pic-form input[name=user_id]").val());
                    <?php if ( function_exists( 'pll_get_post_language' ) ) :
                    global $post;
                    $lang = pll_get_post_language( $post->ID, 'slug' ); ?>
                    form_data.append('lang', '<?php echo $lang; ?>');
                    <?php endif; ?>
                    jQuery.ajax({
                        url: ajax_url,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function() {
                            jQuery(".change-profile-image .loader").show();
                        },
                        success: response => {
                            console.log(response)
                            response = JSON.parse(response);
                            if ( response.success ) {
                                toastr.success(response.message);
                                jQuery('.change-profile-image img').attr('src', response.image_path);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        failure : () => {
                            toastr.error('<?php echo __("Something went wrong", "wpdating");?>');
                        }
                    }).always(function () {
                        jQuery(".change-profile-image .loader").hide();
                        profilePic.value = '';
                    });
                });
                editor.on('close', () => {
                    profilePic.value = '';
                });
            }
            return false;
        });

        // Listen for changes on a cover image file input element
        const coverPic = document.querySelector('.change-cover-photo input[type="file"]');
        coverPic.addEventListener('change', e => {
            let file = coverPic.files[0].name;

            if ( file === 'undefined' ) {
                alert('Upload your file');
            } else {
                const editor   = openDefaultEditor({
                    src: file,
                });
                editor.on('process', ({ dest }) => {
                    let cover_nonce  = jQuery("#formId input[name=security]").val();
                    let cover_action = jQuery("#formId input[name=action]").val();
                    let form_data    = new FormData();
                    form_data.append('file',dest);
                    form_data.append('nonce', cover_nonce);
                    form_data.append('action', cover_action);
                    <?php if ( function_exists( 'pll_get_post_language' ) ) :
                    global $post;
                    $lang = pll_get_post_language( $post->ID, 'slug' ); ?>
                    form_data.append('lang', '<?php echo $lang; ?>');
                    <?php endif; ?>
                    jQuery.ajax({
                        url:ajax_url,
                        type:"POST",
                        processData: false,
                        contentType: false,
                        data:  form_data,
                        beforeSend :  xhr => {
                            jQuery('.profile-cover-image').addClass('loader');
                        },
                        success : function( response ){
                            response = JSON.parse(response);
                            if (response.status === 'success') {
                                toastr.success(response.msg);
                                jQuery('.profile-cover-image').css("background-image", "url('" + response.image_path + "')");
                            }else{
                                toastr.error(response.msg);
                            }
                        },
                        failure : function () {
                            toastr.error('Something went wrong');
                        }
                    }).always(function () {
                        jQuery('.profile-cover-image').removeClass('loader');
                        coverPic.value = '';
                    });
                });
                editor.on('close', () => {
                    coverPic.value = '';
                });
            }
            return false;
        });
    </script>
<?php endif; ?>