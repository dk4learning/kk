
<div class="dsp-tab-container clearfix">
    <ul class="dsp-tab-upper">
        <li <?php if($_GET['p']=='home') echo 'class="active"' ?> ><a href="<?php bloginfo('siteurl')?>/members/?p=home">Home</a></li>
        <li <?php if($_GET['p']=='email') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=email">Email</a></li>
        <li <?php if($_GET['p']=='edit_profile') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=edit_profile">Edit Profile</a></li>
        <li <?php if($_GET['p']=='media') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=media">Media </a></li>
        <li <?php if($_GET['p']=='chat') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=chat">Chat</a></li>
        <li <?php if($_GET['p']=='search') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=search">Search</a></li>
        <li <?php if($_GET['p']=='setting') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=setting">Settings</a></li>
        <li <?php if($_GET['p']=='extras') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=extras">Extras</a></li>
        <li <?php if($_GET['p']=='online') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=online">Online</a></li>
        <li <?php if($_GET['p']=='help') echo 'class="active"' ?>><a href="<?php bloginfo('siteurl')?>/members/?p=help">Help</a></li>
    </ul>
    
    <div class="clear"></div>
    <?php  if($_GET['p']=='email'){ ?> 
        <ul class="dsp-tab-lower">
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/email/inbox.php' ?>">Messages</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/email/compose.php' ?>">Compose</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/email/sent.php' ?>">Sent</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/email/deleted.php' ?>">Deleted</a></li>
        </ul>
    <?php } ?>

    <?php  if($_GET['p']=='media'){ ?> 
        <ul class="dsp-tab-lower">
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/media/creat-album.php' ?>">Create Album</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/media/upload-photo.php' ?>">Upload Photos</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/media/add-audio.php' ?>">Add Audio</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/media/add-video.php' ?>">Add Video</a></li>
        </ul>
    <?php } ?>

    <?php  if($_GET['p']=='search'){ ?> 
        <ul class="dsp-tab-lower">
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/search/search.php' ?>">Search</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/search/advance.php' ?>">Advanced</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/search/near-me.php' ?>">Near Me</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/search/save-searches.php' ?>">Save Searches</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/search/save-result.php' ?>">Save Result</a></li>
        </ul>
    <?php } ?>

    <?php  if($_GET['p']=='setting'){ ?> 
        <ul class="dsp-tab-lower">        
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/account.php' ?>">Account </a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/match-alert.php' ?>">Match alerts</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/blocked.php' ?>">Blocked</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/notification.php' ?>">Notification</a></li>        
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/privacy.php' ?>">Privacy</a></li>       
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/skype.php' ?>">Skype</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/setting/upgrade-account.php' ?>">Upgrade Account</a></li>    
        </ul>
    <?php } ?>

    <?php  if($_GET['p']=='extras'){ ?> 
        <ul class="dsp-tab-lower">           
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/viewed-me.php' ?>">Viewed Me</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/i-viewed.php' ?>">I Viewed</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/trending.php' ?>">Trending</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/interest-cloud.php' ?>">Interest Cloud</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/date-tracker.php' ?>">Date Tracker</a></li>
            <li><a class="dsp-ajax" href="<?php echo get_template_directory_uri() . '/members/extras/meet-me.php' ?>">Meet Me</a></li>
        </ul>
    <?php } ?>

</div>