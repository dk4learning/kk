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
	        <div class="dp-user-block text-center">

	            <div class="dp-user-info-container clearfix">
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
	            <div class="dp-user-image margin-btm-2">
	                <img src="<?php echo display_members_photo($current_user_id, $imagepath); ?>" alt="">
	            </div>
	            <ul class="text-left dp-user-spec">
	                <li><a class="dsp-ajax" id="user-wink" href="<?php echo get_template_directory_uri() . '/members/user/user-wink.php' ?>" ><i class="fa fa-meh-o"></i>Winks</a></li>
	                <li><a class="dsp-ajax" id="user-friends" href="<?php echo get_template_directory_uri() . '/members/user/user-friends.php' ?>" ><i class="fa fa-users"></i>Friends</a></li>
	                <li><a class="dsp-ajax" id="user-favourites" href="<?php echo get_template_directory_uri() . '/members/user/user-favourites.php' ?>" ><i class="fa fa-heart"></i>Favorites</a></li>
	                <li><a class="dsp-ajax" id="user-gifts" href="<?php echo get_template_directory_uri() . '/members/user/user-gifts.php' ?>" ><i class="fa fa-gift"></i>Gifts </a></li>
	                <li><a class="dsp-ajax" id="user-matches" href="<?php echo get_template_directory_uri() . '/members/user/user-matches.php' ?>" ><i class="fa fa-star"></i>Matches</a></li>
	                <li><a class="dsp-ajax" id="user-alerts" href="<?php echo get_template_directory_uri() . '/members/user/user-alerts.php' ?>" ><i class="fa fa-bell"></i>Alerts </a></li>
	                <li><a class="dsp-ajax" id="user-comments" href="<?php echo get_template_directory_uri() . '/members/user/user-comments.php' ?>" ><i class="fa fa-comments-o"></i>Comments</a></li>
	                <li><a class="dsp-ajax" id="user-news" href="<?php echo get_template_directory_uri() . '/members/user/user-news.php' ?>" ><i class="fa fa-bullhorn"></i>News Feed</a></li>
	            </ul>
	        </div>
	    </div>
	    <div id="member-main-content" class="ajax-content col-md-8 margin-btm-3">
	        <div class="dp-page-title dp-content-wrapper">
	            <h1>Date Tracker</h1>
	        </div>
	        <div class="dp-content">
	            <div class="update-row dp-search-container margin-btm-3">
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
					if($member_type == 'standard'){
				?>
				<h4>Membership: <span>Standard Member</span></h4>

				<p>You're currently a Standard Member. Please upgrade to enjoy all the benefits of a Premium Member.</p>
				<a href="#" class="btn btn-green"><i class="fa fa-arrow-circle-up"></i>Upgrade now</a>
				<div class="seperator"></div>
				die('standard');

				<?php } else { ?>

					<h4>Membership: <span>Premium Member</span></h4>
					<p>You're currently a Standard Member. Please upgrade to enjoy all the benefits of a Premium Member.</p>

					<div class="seperator"></div>
					die('preimium');

				<?php } } ?> 

				

				<h4>Credits:</h4>
				<p>You currently have 0 credits left.</p>
				<a href="#" class="btn btn-green">
					<i class="fa fa-credit-card"></i>Buy credits</a>

				<div class="seperator"></div>



	        </div>
	    </div>
	</div>

	<div class="dp-box-container margin-btm-3">
	    <div class="space">



	    </div>
	</div>

	<div class="dp-related-container">
	    <div class="dp-related-title">
	        <h2>New members</h2>
	    </div>
	    <div class="row">
	        <div class="col-md-3 dp-home-member">
	            <a href="#">
	                <img src="images/circle-1.jpg" alt="">
	            </a>
	            <a href="#" class="db-member-title">Jaden Taylor</a>
	            <span class="blue-txt">25 yrs old</span>
	        </div>

	        <div class="col-md-3 dp-home-member">
	            <a href="#">
	                <img src="images/circle-1.jpg" alt="">
	            </a>
	            <a href="#" class="db-member-title">Jaden Taylor</a>
	            <span class="blue-txt">25 yrs old</span>
	        </div>

	        <div class="col-md-3 dp-home-member">
	            <a href="#">
	                <img src="images/circle-1.jpg" alt="">
	            </a>
	            <a href="#" class="db-member-title">Jaden Taylor</a>
	            <span class="blue-txt">25 yrs old</span>
	        </div>

	        <div class="col-md-3 dp-home-member">
	            <a href="#">
	                <img src="images/circle-1.jpg" alt="">
	            </a>
	            <a href="#" class="db-member-title">Jaden Taylor</a>
	            <span class="blue-txt">25 yrs old</span>
	        </div>
	    </div>
	</div>
