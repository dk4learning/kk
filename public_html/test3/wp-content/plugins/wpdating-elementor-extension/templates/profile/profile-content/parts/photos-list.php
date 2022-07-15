<?php 
global $wpdb;
$user_id            = wpee_profile_id();
$user_profile       = wpee_get_user_details_by_user_id( $user_id );
$profile_link       = Wpdating_Elementor_Extension_Helper_Functions::get_profile_url_by_username( $user_profile->user_name );
$profile_photos_url = $profile_link . '/media/photos';
$args = array( 
	'user_id' => $user_id 
	);
$userphotos = wpee_get_photos( $args ); // fetch profile using userid
$imagepath = content_url('/') ; 
?>
<div class="profile-user-photos wpee-block">
	<div class="wpee-block-header">
		<h4 class="wpee-block-title"><?php esc_html_e( 'Photos', 'wpdating');?></h4>
	</div>
	<div class="profile-user-photos-inner wpee-block-content">
		<ul class="profile-photo-list no-list d-flex">
			<?php 
		    if(isset($userphotos) && count($userphotos) > 0){
		    	foreach ($userphotos as $photo) {
				$imagepath = content_url('/uploads/dsp_media/user_photos/user_' . $user_id . '/album_'.$photo->album_id . '/'. $photo->image_name );
				?>
				<li class="photos-list">
					<a rel='example_group' href='<?php echo esc_url( $imagepath );?>'>
						<span class="image-bg" style="background-image: url('<?php echo esc_url($imagepath);?>');"></span>
					</a>
				</li>
			<?php 
				}
		    }else{ ?>
		        <li class="dsp_span_pointer"><?php echo __('No Photos added', 'wpdating'); ?></li>
		    <?php
		    } ?>
		</ul>
	</div>
	<div class="wpee-block-footer">
		<a href="<?php echo esc_url( $profile_photos_url );?>" class="edit-profile-link"><?php esc_html_e('View Photos','wpdating');?></a>
	</div>
</div>