<?php 
$user_id = wpee_profile_id();
if( $user_id == get_current_user_id() && is_user_logged_in() ){
    wpee_locate_template('profile/notification-blocks/my-profile/my-profile.php');
}
else {
    wpee_locate_template('profile/notification-blocks/others-profile/others-profile.php');
}
?>