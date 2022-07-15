<?php
//Add facebook PHP-SDK
 include_once "src/dspfacebook.php";

/**
 * This function is used to get setting from dsp plugin
 */
if(!function_exists('get_facebook_login_setting')) : 
    function get_facebook_login_setting($condition,$column = 'setting_value') {
        global $wpdb;
        $dsp_general_settings_table = $wpdb->prefix . "dsp_general_settings";
        $facebookSettingStatus = $wpdb->get_var($wpdb->prepare("SELECT `".$column."` FROM $dsp_general_settings_table WHERE setting_name = '%s'",$condition));
        return $facebookSettingStatus;
    }
endif;
 //facebook api setting
$fbSettingStatus = get_facebook_login_setting('facebook_login','setting_status');
$appId =  get_facebook_login_setting('facebook_api_key');
$secretfb =  get_facebook_login_setting('facebook_secret_key');
$facebookCredentials = (!empty($appId) &&  !empty($secretfb)) ? true : false;
$isFacebookLoginSettingOn = ($fbSettingStatus == 'Y') ? true : false;
$siteUrl = site_url();
//Instantiate facebook object
$facebook = new DspFacebook(array(
            'appId' => $appId,
            'secret' => $secretfb
        ));
//Get user token
$user = $facebook->getUser();

//dsp_debug($user);
if($user == 0  || empty($_REQUEST['code'])){ 
 //login url
 $loginUrl = $facebook->getLoginUrl(array(
                'scope' => "read_stream, publish_stream, email, user_photos, user_birthday, user_location, user_hometown, user_website",                  
                'redirect_uri' => $siteUrl,
                'display' => 'popup' 
             ));
    
} else {  
 try{
     //Assume that the user is logged in and authenticated
     $accessToken = $facebook->getAccessToken();
     $userProfile = $facebook->api("/".$user);

    //logout url
     $logoutUrl = $facebook->getLogoutUrl(array('next' => $siteUrl . "/logout.php"));
    } catch(FacebookApiException $e){
     error_log($e);
     $user = NULL;
    }
}

if( $user ){   
 if( isset($_POST['status']) ){
    try{
        $status = $facebook->api('/me/feed', 'post', array('message' => $_POST['status']));
    } catch(FacebookApiException $e){
        error_log($e);
    }
 }
 
 if( isset($_POST['publish']) ){         
     try{
         $publish = $facebook->api("/me/feed", 'post', array(
                                'message' => "Auto Status Update Check - Facebook PHP-SDK",
                                'link' => $siteUrl,
                                'picture' => $siteUrl,
                                'name' => "",
                                'caption' => "",
                                'description' => "Checking Auto Status Update using Facebook PHP-SDK"                                    
                    ));
         
        } catch(FacebookApiException $e){
         echo $e->getMessage();
         error_log($e);
        }
 }
}

//if (isset($_REQUEST['state']) && isset($_REQUEST['code'])) {
//    echo "<script>
////            window.close();
////            window.opener.location.href = '" .$siteUrl . "/members/';
//        </script>";
// }

function time_elapsed($time) {
    sscanf($time,"%u-%u-%uT%u:%u:%u+0000",$year,$month,$day,$hour,$min,$sec);
    $time_seconds = time() - ((int)substr(date('O'),0,3)*60*60) - mktime($hour,$min,$sec,$month,$day,$year);
    
    if($time_seconds < 1) return '0 seconds';
    
    $arr = array(12*30*24*60*60 => 'year',
                30*24*60*60     => 'month',
                24*60*60        => 'day',
                60*60           => 'hour',
                60              => 'minute',
                1               => 'second'
                );
    
    foreach($arr as $secs => $str){
        $d = $time_seconds / $secs;
        if($d >= 1){
            $r = floor($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}

 function newsitem($profile_pic,$from,$to,$message,$picture,$name,$link,$caption,$description,$icon,$time,$comments,$likes)
    {
        if($to) $to_section = '<div class="to" >to</div><div class="news-friend-name"><strong><a>' . $to . '</a></strong></div><div class="clear"></div>';
        else $to_section = '';
                
        if($message) $message_section = '<div class="message">' . $message . '</div>';
        else $message_section = '';
            
        if($picture) $picture_section = '<div class="external-image left" style="margin-right: 10px"><img src="' . $picture . '"/></div><div class="news-external">';
        else $picture_section = '<div class="news-external" style="width: 410px;">';
        
        if(!$link) $link='#';
        
        if($name) $name_section = '<div class="news-title" style="margin-bottom:10px"><h3><a href="' . $link . '" target="_blank">' . $name . '</a></h3></div>';
        else $name_section = '';
        
        if($caption) $caption_section = '<div class="news-caption"><i>' . $caption . '</i></div>';
        else $caption_section = '';
        
        if($description) $description_section = '<div class="news-desc">' . $description . '</div>';
        else $description_section = '';
        
        if($icon) $icon_section = '<div class="feed-icon left" ><img src="' . $icon . '" /></div>';
        else $icon_section = '';
        
        $time_converted = time_elapsed($time);
        
        $news = '<div class="news">
                        <div class="thumb"><img src="' . $profile_pic . '"/></div>
                        
                        <div class="news-content left">
                            <div class="from"><strong><a>'. $from . '</a></strong></div>
                            <div class="clear"></div><br/>' . $message_section . '<div class="externalLinkWrapper">' . $picture_section . $name_section . $caption_section . $description_section .
                            '</div></div>
                            <div class="clear"></div><br/>
                            <div class="comment-like left">' . $time_converted . ' ago  &middot;  ' . $comments . ' comments &middot; ' . $likes . ' likes</div>
                        </div>
                    </div><div class="clear"></div>';
            return $news;
    }