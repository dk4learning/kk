<?php
	//update status
	$update_status = isset($_REQUEST['update_status']) ? $_REQUEST['update_status'] : '';
	$new_status = isset($_REQUEST['new_status']) ? $wpdb->escape(trim($_REQUEST['new_status'])) : '';
	if ($update_status == 'Update' && $new_status != "") {
	    $users->update_status($new_status, $current_user->ID);
	}
?>
	<div class="row">
	    <div class="user-quick-info col-md-4 margin-btm-3">
	        <div class="dsp-user-block text-center">
	            <div class="dsp-user-info-container clearfix">
	                <?php if($check_couples_mode->setting_status == 'Y' && $gender=='C'): ?>
	                <a href="<?php echo $root_link.get_username($current_user_id)."/my_profile/"; ?>"><i class="fa fa-user"></i></a>
	            <?php else: ?>
	                <a href="<?php echo $root_link.get_username($current_user_id)."/"; ?>"><i class="fa fa-user"></i></a>
	            <?php endif; ?>
	                <a href="<?php echo $root_link."extras/trending/"; ?>"><i class="fa fa-users"></i></a>
	                <a href="<?php echo $root_link."extras/viewed_me/"; ?>"><i class="fa fa-eye"></i></a>
	                <a href="<?php echo $root_link."extras/i_viewed/"; ?>"><i class="fa fa-bell-o"></i></a>
	                <a href="<?php echo $root_link."online_members/show/all/"; ?>"><i class="fa fa-circle"></i></a>
	                <a href="<?php echo $root_link."email/inbox/"; ?>"><i class="fa fa-envelope-o"></i></a>
	            </div>
	            <div class="clear"></div>
	            <div class="dsp-user-image margin-btm-2">
	            	<div class="circle-image">
	            		<img src="<?php echo display_members_photo($current_user_id, $imagepath); ?>" alt="">
	            	</div>	                
	            </div>
	            <ul class="text-left dsp-user-spec" data-userid="<?php echo $current_user_id; ?>">
	                <li><a class="dsp-ajax" data-action="fetch_wink" id="user-wink" href="<?php echo get_template_directory_uri() . '/members/user/user-wink.php' ?>" ><i class="fa fa-meh-o"></i>Winks</a></li>
	                <li><a class="dsp-ajax" data-action="fetch_friend_list" id="user-friends" href="<?php echo get_template_directory_uri() . '/members/user/user-friends.php' ?>" ><i class="fa fa-users"></i>Friends</a></li>
	                <li><a class="dsp-ajax" data-action="fetch_favorites" id="user-favourites" href="<?php echo get_template_directory_uri() . '/members/user/user-favourites.php' ?>" ><i class="fa fa-heart"></i>Favorites</a></li>
	                <li><a class="dsp-ajax" data-action="fetch_gifts" id="user-gifts" href="<?php echo get_template_directory_uri() . '/members/user/user-gifts.php' ?>" ><i class="fa fa-gift"></i>Gifts </a></li>
	                <li><a class="dsp-ajax" data-action="fetch_matches" id="user-matches" href="<?php echo get_template_directory_uri() . '/members/user/user-matches.php' ?>" ><i class="fa fa-star"></i>Matches</a></li>
	                <li><a class="dsp-ajax" data-action="fetch_alerts" id="user-alerts" href="<?php echo get_template_directory_uri() . '/members/user/user-alerts.php' ?>" ><i class="fa fa-bell"></i>Alerts </a></li>
	                <li><a class="dsp-ajax" data-action="fetch_comments" id="user-comments" href="<?php echo get_template_directory_uri() . '/members/user/user-comments.php' ?>" ><i class="fa fa-comments-o"></i>Comments</a></li>
	                <li><a class="dsp-ajax" data-action="fetch_news" id="user-news" href="<?php echo get_template_directory_uri() . '/members/user/user-news.php' ?>" ><i class="fa fa-bullhorn"></i>News Feed</a></li>
	            </ul>
	        </div>
	    </div>
	    <div id="member-main-content" class="ajax-content col-md-8 margin-btm-3">
	        <div class="dsp-page-title dsp-content-wrapper">
	            <h1>Date Tracker</h1>
	        </div>
	        <div class="dsp-content">
	            <div class="update-row dsp-search-container margin-btm-3">
                    <form method="post" action="">
                        <input type="text" name="new_status" class="white-input" placeholder="Status Updates">
                        <input type="submit" value="Update" class="btn btn-update btn-now" name="update_status">
                    </form>
                </div>
				<div class="status alert alert-success">
					<?php
						$status = $users->get_status($current_user_id);
						echo '<strong>'.$status.'</strong>';
					?>
				</div>				
				<?php if($check_free_mode->setting_status=='Y'){?>
					<h4>Membership: <span>Free Member</span></h4>
					<p>You're currently a free Member. Please upgrade to enjoy all the benefits of a Premium Member.</p>
					<a href="#" class="btn btn-green"><i class="fa fa-arrow-circle-up"></i>Upgrade now</a>
				<?php } else { ?>
				<?php 
					$member_type = dsp_get_membership_type($current_user_id);
					$expiry_date = dsp_get_expiry_date($current_user_id);
					if($member_type == 'standard'){
				?>
					<h4>Membership: <span>Standard Member</span></h4>
					<p>You're currently a Standard Member. Please upgrade to enjoy all the benefits of a Premium Member.</p>
					<a href="#" class="btn btn-green"><i class="fa fa-arrow-circle-up"></i>Upgrade now</a>
					<div class="seperator"></div>
				<?php } else { ?>
					<h4>Membership: <span>Premium Member</span></h4>
					<p>You're currently a Premium Member under <?php echo $payment_row->pay_plan_name;?></p>
					<p>Your Monthly Membership expires on <?php echo $expiry_date ?></p>
					<div class="seperator"></div>
				<?php } } ?>				
				<?php 
					$check_credit_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'credit'");
					if($check_credit_mode->setting_status=='Y'){?>
						<h4>Credits:</h4>
						<?php 
							$no_of_credits=$wpdb->get_var("select no_of_credits from $dsp_credits_usage_table where user_id='$user_id'");
					 		if(count($no_of_credits)==0) $no_of_credits=count($no_of_credits);
						?>
						<p>You currently have <?php echo $no_of_credits;?> credits left.</p>
						<a href="#" class="btn btn-green">
						<i class="fa fa-credit-card"></i>Buy credits</a>
						<div class="seperator"></div>
				<?php } ?>
	        </div>
	    </div>
	</div>

	<div class="dsp-box-container margin-btm-3">
	    <div class="space">



	    </div>
	</div>

	<div class="dsp-related-container">
	    <div class="dsp-related-title">
	        <h2>New members</h2>
	    </div>
	    <div class="row">
				

				<?php 
     if($exist_profile_details->gender=="M")
	 {
	 $gender_check="and gender='F' ";
	 }
	 else
	 if($exist_profile_details->gender=="F")
	 {
	 $gender_check="and gender='M' ";
	 }
	 else
	 if($exist_profile_details->gender=="C")
	 {
	 $gender_check="and gender in ('M','F','C') ";
	 }
   	 if($check_couples_mode->setting_status == 'Y'){	
      $new_members=$wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By Rand() LIMIT 16");
      } else {
	  
	  $new_members=$wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE gender!='C' and status_id=1  AND country_id!=0 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) $gender_check Order By Rand() LIMIT 16");
	  }
       $i=0;
       foreach ($new_members as $member) {
       $exist_user_name=$wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID='$member->user_id'");
       $user_name=$exist_user_name->display_name;
	   $new_member_id=$member->user_id;
	   $favt_mem=array();
		
	   $private_mem=$wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member->user_id'");
		foreach ($private_mem as $private) {
	   $favt_mem[]=$private->favourite_user_id;
		}
		 if(($i%4)==0) { 
        ?>
        <? } // End if(($i%4)==0) ?>
<div class="col-md-3 dsp-home-member">
			<? 
	
			
				if($check_couples_mode->setting_status == 'Y'){ 
				if($member->gender=='C')
				{
				?>

                <?php if($member->make_private =='Y') {?>
                
                 <? if($current_user->ID != $new_member_id) { ?>
				 
				 
				 <? if(!in_array($current_user->ID, $favt_mem)) { ?>
				<div class="circle-image">
                <a href="<?php echo $root_link.get_username($new_member_id)."/my_profile/"; ?>" >
                <img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg" />
                </a>  
                </div>              
                <? } 
				else {?>
				<div class="circle-image">
				<a href="<?php echo $root_link.get_username($new_member_id)."/my_profile/"; ?>" >				
				<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>" /></a>                
                </div>
                <?php }				
				}
				else { ?>
				<div class="circle-image">
                <a href="<?php echo $root_link.get_username($new_member_id)."/my_profile/"; ?>" >				
				<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>" /></a>                
                </div>
                <?php } ?>
       			<?php }
				else { ?>  
				<div class="circle-image">           
				<a href="<?php echo $root_link.get_username($new_member_id)."/my_profile/"; ?>" >				
				<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>"/></a>
                </div>
                <?php } ?>  
				<? } else { ?>                
                <?php if($member->make_private =='Y') {?>
                <? if($current_user->ID != $new_member_id) { ?>
				
                <? if(!in_array($current_user->ID, $favt_mem)) { ?>
                <div class="circle-image"> 
                <a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >
                	<img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg"/>
                </a> 
                </div>               
                <? } 
				else {?>
				<div class="circle-image"> 
				<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>" /></a>                
                </div>
                <?php }		
				 } else { ?>
				<div class="circle-image"> 
                <a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>" /></a>                
                </div>
                <?php } ?>
       			<?php } else { ?>
       			<div class="circle-image"> 
				<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>">				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>"/></a>
                </div>
                <?php } ?>
                
				<?    } 
				} else { ?>
                 
                <?php if($member->make_private =='Y') {?>
                <? if($current_user->ID != $new_member_id) { ?>
                
				 <? if(!in_array($current_user->ID, $favt_mem)) { ?>
				 <div class="circle-image"> 
                <a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >
                	<img src="<?php echo $imagepath?>plugins/dsp_dating/images/private-photo-pic.jpg"/>
                </a>                
                </div>
                <? } 
				else {?>
				<div class="circle-image"> 
				<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>"/></a>                
                </div>
                <?php }		
				 } else { ?>
				 <div class="circle-image"> 
                <a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" >				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>"/></a>                
                </div>
                <?php } ?>
       			<?php } else { ?>
       			<div class="circle-image"> 
				<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>">				
					<img src="<?php echo display_members_photo($new_member_id,$imagepath); ?>"/></a>
                </div>
                <?php } ?>
                
				<? } ?>
            
            
			</a>
			
	           <? 
			   if($check_couples_mode->setting_status == 'Y'){ 
	                    if($member->gender=='C')
	                    {
	               ?>
	               <a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" class="db-member-title">
	    
		           <?=$user_name;?>
	                
	                <? } else { ?>
					<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" class="db-member-title">
		        <?=$user_name;?>
				 <?    } 
				   } else { ?> 
	            	<a href="<?php echo $root_link.get_username($new_member_id)."/"; ?>" class="db-member-title">
		        <?=$user_name;?>
	            <? } ?>
				</a>

			<span class="age-text">
				<?php echo dsp_get_age($member->age)?> Years Old
			</span>

		   	   </div>
			   <?php
			     $i++; 
			unset($favt_mem);
			      } 
			    ?>
	    </div>
	</div>
