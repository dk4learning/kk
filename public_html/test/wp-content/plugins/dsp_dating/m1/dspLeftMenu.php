<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">
//<link href="mobile.css" rel="stylesheet" type="text/css">-->

include("../../../../wp-config.php");


/* ------------- end of show error off code------------------------------------------ */

include_once("dspGetSite.php"); // this page contains the function cleanUrl that will cleasn the url


$dsp_user_emails_table = $wpdb->prefix . DSP_EMAILS_TABLE;
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;
$dsp_member_winks_table = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$dsp_my_friends_table = $wpdb->prefix . DSP_MY_FRIENDS_TABLE;

$url = get_bloginfo('url');
$siteUrl = cleanUrl($url);

$imagepath = get_option('siteurl') . '/wp-content/';

$user_id = $_REQUEST['user_id'];




$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_emails_table WHERE message_read='N' AND receiver_id=$user_id AND delete_message=0");
$count_friends_virtual_gifts = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_virtual_gifts WHERE member_id=$user_id AND status_id=0");
$count_wink_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_member_winks_table WHERE wink_read='N' AND receiver_id=$user_id");
$count_friends_request = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid=$user_id AND approved_status='N'");
$count_friends_comments = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_comments_table WHERE member_id=$user_id AND status_id=0");

$totalAlertCount = $count_friends_virtual_gifts + $count_wink_messages + $count_friends_request + $count_friends_comments;
$dsp_general_settings = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;


$user = $wpdb->get_row("SELECT user_login FROM $dsp_user_table WHERE ID= $user_id");



$user_name = $user->user_login;
?>


<div data-role="panel" id="left-panel"  class="leftmenu ui-panel ui-panel-position-left ui-panel-display-push ui-body-b ui-panel-animate ui-panel-close ui-panel-closed" data-theme="b" data-display="overlay">


   <ul class="menu-list">
    <li class="ui-body ui-body-d ui-corner-all userprofile" >
     <img src="<?php echo display_members_photo($user_id, $imagepath); ?>"  onclick="viewProfile(<?php echo $user_id ?>, 'my_profile')" border="0" class="dsp_img3 userimage"/>
     <div class="menu-userprofile" onclick="viewProfile(<?php echo $user_id ?>, 'my_profile')"><?php echo $user_name;?></div>
     
 </li>
 <li class="ui-body ui-body-d ui-corner-all" >
     <a href="dsp_view_status.html"   class="mam_text_dec"  style="color:black;">
        <img    src="images/icons/status.png" />
        <span><?php echo __('Status', 'wpdating'); ?></span>   
    </a>
</li>



<li class="ui-body ui-body-d ui-corner-all">
 <a href="dsp_view_message.html"    class="mam_text_dec"  style="color:black;">

    <img   src="images/icons/message.png" />
    <?php if ($count_messages > 0) { ?>
    <!--<div class="no_div"><?php echo $count_messages; ?></div>-->

    <span>
        <?php
        echo '<i class="mesg-count">' . $count_messages . "</i>&nbsp;";

        if ($count_messages > 1) {
            echo __('Messages', 'wpdating');
        } else {
            echo __('Message', 'wpdating');
        }
        ?>
    </span>

    <?php } else { ?>

    <span><?php echo __('Message', 'wpdating'); ?></span>
    <?php } ?>
</a>
</li>




<li class="ui-body ui-body-d ui-corner-all">
 <a href="dsp_alert.html" class="mam_text_dec"  style="color:black;">

    <img   src="images/icons/alerts.png" />
    <?php if ($totalAlertCount > 0) { ?>

    <span>
        <?php
        echo '<i class="mesg-count">' . $totalAlertCount . "</i>&nbsp;";

        if ($totalAlertCount > 1) {
            echo __('Alerts', 'wpdating');
        } else {
            echo __('Alert', 'wpdating');
        }
        ?>
    </span>

    <?php } else { ?>

    <span><?php echo __('Alerts', 'wpdating'); ?></span>
    <?php } ?>  
</a>
</li>





<li class="ui-body ui-body-d ui-corner-all">     
 <a href="dsp_online.html" class="mam_text_dec"  style="color:black;">  
    <img  src="images/icons/online.png" />

    <span><?php echo __('Online', 'wpdating'); ?></span>
</a>
</li>

<?php
$chatSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='chat_mode'");
if ($chatSetting == 'Y') {
    ?>

    <li class="ui-body ui-body-d ui-corner-all" >
     <a href="dsp_chat.html"  class="mam_text_dec"  style="color:black;">
        <img  src="images/icons/chat.png"/>
        <span><?php echo __('Chat', 'wpdating'); ?></span>  
    </a>
</li>

<?php } ?>

<li class="ui-body ui-body-d ui-corner-all" >
 <a href="dsp_edit_profile.html" class="mam_text_dec"  style="color:black;">
    <img  src="images/icons/editprofile.png" />
    <span><?php echo __('Edit Profile', 'wpdating'); ?></span>
</a>
</li>





<li class="ui-body ui-body-d ui-corner-all" >
 <a onclick="viewProfile(<?php echo $user_id ?>, 'my_profile')" class="mam_text_dec"  style="color:black;"> 
    <img   src="images/icons/profile.png" />
    <span><?php echo __('Profile', 'wpdating'); ?></span>   
</a>
</li>   

<?php
$trackerSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='date_tracker'");
if ($trackerSetting == 'Y') {
    ?>

    <li class="ui-body ui-body-d ui-corner-all" >
      <a href="dsp_date_tracker.html"  class="mam_text_dec"  style="color:black;">
        <img   src="images/icons/tracker.png" />
        <span><?php echo __('Tracker', 'wpdating'); ?></span>
    </a>
</li>

<?php } ?>

<li class="ui-body ui-body-d ui-corner-all" >
  <a href="dsp_my_favorite.html"   class="mam_text_dec"  style="color:black;">
    <img   src="images/icons/fav.png" />
    <span><?php echo __('Favorites', 'wpdating'); ?></span>
</a>
</li>



<?php
$friendSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='my_friends'");
if ($friendSetting == 'Y') {
    ?>

    <li class="ui-body ui-body-d ui-corner-all" >
        <a onclick="viewFriends()" class="mam_text_dec"  style="color:black;">
            <img  src="images/icons/friends.png" />
            <span><?php echo __('Friends', 'wpdating'); ?></span>
        </a>
    </li>   

    <?php }?>

    <li class="ui-body ui-body-d ui-corner-all" >
     <a class="mam_text_dec"  style="color:black;" href="dsp_main_search.html">
        <img src="images/icons/search.png" />
        <span><?php echo __('Search', 'wpdating'); ?></span>
    </a>
</li>


<?php
$gallerySetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='picture_gallery_module'");
if ($gallerySetting == 'Y') {
    ?>

    <li class="ui-body ui-body-d ui-corner-all" >
        <a href="dsp_media.html" class="mam_text_dec"  style="color:black;">
            <img   src="images/icons/gallery.png" />
            <span><?php echo __('Galleries', 'wpdating'); ?></span>
        </a>
    </li>


    <?php } ?>

    <?php
    $audioSettingSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='audio_module'");
    if ($gallerySetting == 'Y') {
        ?>
<!--        <a href="dsp_sound.html"   class="mam_text_dec"  style="color:black;">
            <li class="dsp_home_icon ui-body-d" >
                <img  style="width:40px;height:40px;"      src="images/sound.png" /><br>
                <span><?php echo __('Sound', 'wpdating'); ?></span>
            </li>
        </a>-->
        <?php } ?>



        <li class="ui-body ui-body-d ui-corner-all" >
          <a href="dsp_extras.html"  class="mam_text_dec"  style="color:black;">
            <img  src="images/icons/extra.png" />
            <span><?php echo __('Extras', 'wpdating'); ?></span>
        </a>
    </li>

    <?php
    $videSetting = $wpdb->get_var("select setting_status from  $dsp_general_settings WHERE setting_name ='video_module'");
    if ($videSetting == 'Y') {
        ?>

        <li class="ui-body ui-body-d ui-corner-all" >
            <a  href="dsp_video.html" class="mam_text_dec"  style="color:black;">
                <img  src="images/icons/video.png" />
                <span><?php echo __('Videos', 'wpdating'); ?></span>
            </a>
        </li>

        <?php } ?>

        <li class="ui-body ui-body-d ui-corner-all" >
         <a onclick="callSetting('setting')" class="mam_text_dec"  style="color:black;">
            <img   src="images/icons/setting.png" />
            <span><?php echo __('Settings', 'wpdating'); ?></span>
        </a>
         </li>


    <li class="ui-body ui-body-d ui-corner-all MenuiPhone" >    
     <a href="dsp_membership.html"  class="mam_text_dec "  style="color:black;">
        <img   src="images/icons/membership.png" />
        <span><?php echo __('Membership', 'wpdating'); ?></span>
    </a>
</li>




<li class="ui-body ui-body-d ui-corner-all" >    
  <a href="dsp_upgrade.html"  class="mam_text_dec "  style="color:black;">
    <img     src="images/icons/upgrade.png" />
    <span><?php echo __('Upgrade', 'wpdating'); ?></span>    
</a>
</li>

<li class="ui-body ui-body-d ui-corner-all" >    
   <a class="mam_text_dec" id="btn_logout" href="#" >
    <img src="images/icons/signout.png" />
    <span style="height:54px"><?php echo __('Logout', 'wpdating'); ?></span>    
</a>
</li>                        
</ul>

<div style="height:28px;backround-color:white;"></div>


    </div><!-- /panel -->