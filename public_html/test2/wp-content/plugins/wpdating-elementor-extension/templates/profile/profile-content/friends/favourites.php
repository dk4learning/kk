<?php 
global $wpdb;
$current_user_id = get_current_user_id();
$user_id = wpee_profile_id();
$dsp_user_profiles = $wpdb->prefix . 'dsp_user_profiles';
$dsp_my_friends_table = $wpdb->prefix . 'dsp_my_friends';
$dsp_user_favourites_table = $wpdb->prefix . 'dsp_favourites_list';
$check_couples_mode = wpee_get_setting('couples');
$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;
$imagepath = content_url('/') ;
$count_my_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$user_id'");	
?>
<div class="profile-section-content friends-favourites">
    <ul class="friends-section"><!-- friends-section class used -->
    	<?php
	    $delfavourites = get('favourite_Id');
		$Actiondel = get('Action');
		if (($delfavourites != "") && ($Actiondel == "Del")) {
		    $wpdb->query("DELETE FROM $dsp_user_favourites_table where favourite_id = '$delfavourites'");
		}
		$total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_favourites_table where user_id='$user_id'");
		
            if ($total_results1 > 0) {	            	
                if ($check_couples_mode->setting_status == 'Y') {
                    $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
AND favourites.user_id = '$user_id' ORDER BY favourites.fav_date_added");
                } else {
                    $my_favourites = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table favourites, $dsp_user_profiles profile WHERE favourites.favourite_user_id = profile.user_id
AND favourites.user_id = '$user_id' AND profile.gender!='C' ORDER BY favourites.fav_date_added");
                }
                $i = 0;
                foreach ($my_favourites as $favourites) {
                    $favourite_id = $favourites->favourite_id;
                    $fav_user_id = $favourites->favourite_user_id;
                    $fav_screenname = $favourites->fav_screenname;
                    $favt_mem = array();
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$fav_user_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    if (($i % 3) == 0) {
                        ?>
                    <?php }  // End if(($i%4)==0)
		            $count_friends = $wpdb->get_var("SELECT count(*) FROM $dsp_my_friends_table WHERE user_id = '$favourites->favourite_user_id'");
                	$exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$favourite_id'");

		            $displayed_member_name = wpee_get_user_display_name_by_id($favourites->favourite_user_id);
		            ?>

			        <li id="fav<?php echo esc_attr( $favourites->favourite_id );?>" class="wpee-fav-list">
			            <?php
	                        $profile_link = wpee_get_profile_url_by_id( $favourites->favourite_user_id );

	                        if ($exist_make_private->make_private == 'Y') { 
	                            if (!in_array($current_user->ID, $favt_mem)) {
	                                $profile_image= WPDATE_URL. '/images/private-photo-pic.jpg';
	                            }
	                            else{
	                                $profile_image = wpee_display_members_photo($favourites->favourite_user_id, $imagepath);
	                            }

	                        }
	                        else{
	                            $profile_image = wpee_display_members_photo($favourites->favourite_user_id, $imagepath);
	                        }
	                       ?>
						<figure class="img-holder">				
			                <a href="<?php echo esc_url($profile_link);?>" > 
                                <img src="<?php echo $profile_image; ?>" class="dsp_img3 iviewed-img"/>
                            </a>
    						<div class="wpee-user-status <?php echo ( wpee_get_online_user($favourites->favourite_user_id) ) ? 'wpee-online' : 'wpee-offline';?>"></div>
						</figure>
			                <div class="fav-content">							                
				                <span class="user-name-show">
		                            <a href="<?php echo esc_url( $profile_link );?>">		
			                            <?php echo $displayed_member_name; ?>
			                        </a>
				                </span>  
				                <?php if( $user_id == $current_user_id ): ?>
				            	 <a href="javascript:void(0);" data-nonce="<?php echo wp_create_nonce('wpee_remove_favourites_nonce');?>" data-fav-uid="<?php echo esc_attr( $favourites->favourite_id );?>" data-confirm-msg="<?php esc_html_e('Are you sure you want to remove member from Favorites?', 'wpdating');?>" class="wpee-remove-fav dsp-block dsp-delete" ><i class="fa fa-trash-o"></i></a>
				            	<?php endif;?>
				           	</div>
			        </li>
		            <?php
		            $i++;
                unset($favt_mem);
            }
                ?>
    <?php } else { ?>
        <div align="center">
            <div class="error">
                <strong><?php echo __('Currently,you have no Favorites!', 'wpdating') ?></strong>
            </div>                
        </div>
    <?php } ?>
	</ul>
</div>