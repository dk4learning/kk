<?php
//<!--<link href="http://code.jquery.com/mobile/1.3.1/jquery.mobile-1.3.1.min.css" rel="stylesheet">
//<link href="index.css" rel="stylesheet" type="text/css">-->
//error_reporting (0);
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


include("../../../../wp-config.php");

/* To off  display error or warning which is set of in wp-confing file --- 
  // use this lines after including wp-config.php file
 */
error_reporting(0);
@ini_set('display_errors', 0);
error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));

/* ------------- end of show error off code------------------------------------------ */

include_once("dspGetSite.php");

$url = get_bloginfo('url');
$siteUrl = cleanUrl($url);
?>

<div data-role="header" class="ui-header ui-bar-a" role="banner">
    <?php include_once("page_menu.php");?> 
    <h1 class="ui-title" role="heading" aria-level="1"><?php echo $siteUrl; ?></h1>
    <?php include_once("page_home.php");?> 
</div>


<?php
include_once("dspFunction.php");

include_once("../general_settings.php");



global $wpdb;

//$img=  get_bloginfo('template_url')."/images/logo.png";


$dsp_user_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;

$dsp_user_profiles_table = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

$dsp_user_table = $wpdb->prefix . DSP_USERS_TABLE;

$dsp_country_table = $wpdb->prefix . DSP_COUNTRY_TABLE;

$dsp_state_table = $wpdb->prefix . DSP_STATE_TABLE;

$dsp_city_table = $wpdb->prefix . DSP_CITY_TABLE;

$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;

$dsp_skype_table = $wpdb->prefix . DSP_SKYPE_TABLE;

$fav_icon_image_path = "images/";

$user_id = $_REQUEST['user_id'];

$gender = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';

$age_from = isset($_REQUEST['age_from']) ? $_REQUEST['age_from'] : '';

$age_to = isset($_REQUEST['age_to']) ? $_REQUEST['age_to'] : '';

$fav_icon_image_path = $imagepath . "plugins/dsp_dating/m1/images/"; // fav,chat,star,friends,mail Icon image path
// save online user // always call this file after fetching user_id
include_once('dspSaveOnline.php');

$user_id = $_REQUEST['user_id'];

$onlinequery = "SELECT * FROM $dsp_user_online_table oln INNER JOIN $dsp_user_profiles_table usr ON(usr.user_id=oln.user_id) WHERE oln.status = 'Y' AND usr.country_id !=0 AND usr.stealth_mode='N'    ";

//echo $onlinequery;

if (isset($age_from) && $age_from >= 18) {

    $onlinequery .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
} else {

    $age_to = 90;

    $age_from = 18;

    $onlinequery .= " and ((year(CURDATE())-year(age)) > '" . $age_from . "') AND ((year(CURDATE())-year(age)) < '" . $age_to . "') AND ";
}



if (isset($gender) && $gender == 'M' && !isset($_REQUEST['show'])) {

    $onlinequery .= " gender='M' ";
} else if (isset($gender) && $gender == 'F' && !isset($_REQUEST['show'])) {

    $onlinequery .= " gender='F' ";
} else if (isset($gender) && $gender == 'C' && !isset($_REQUEST['show'])) {

    $onlinequery .= " gender='C' ";
} else {

    if ($check_couples_mode->setting_status == 'Y') {

        $onlinequery .= " gender IN('M','F','C') ";
    } else {

        $onlinequery .= " gender IN('M','F') ";
    }
}

$onlinequery.= "GROUP BY oln.user_id";

//	echo $onlinequery;

$wpdb->get_results($onlinequery);
$user_count = $wpdb->num_rows;

$total_results1 = $user_count;

//$limit=1;
$limit = 5;

$adjacents = 2;

if (isset($_REQUEST['page1']))
    $page = $_REQUEST['page1'];
else
    $page = 0;

// ------------------------------------------------start Paging code------------------------------------------------------ // 		

if ($page == 0)
    $page = 1;     //if no page var is given, default to 1.

$prev = $page - 1;

$next = $page + 1;

$lastpage = ceil($total_results1 / $limit);
;  //lastpage is = total pages / items per page, rounded up.

$lpm1 = $lastpage - 1;


$start = (($page * $limit) - $limit);



/*

  Now we apply our rules and draw the pagination object.

  We're actually saving the code to a variable in case we want to draw it more than once.

 */

  $pagination = "";

  if ($lastpage > 1) {

    $pagination .= "<div class='button-area'>";

    //previous button

    if ($page > 1) {
        $pagination.="
        
        <div onclick='getOnlinePage(1)' class='btn-pre1'>
          <img src='images/icons/prev-1.png' />
       </div>";
   } else {
    $pagination.= "
    <div class='btn-pre1'>
      <img src='images/icons/prev-1.png' />
   </div>";
}

if ($page > 1) {
    $pagination.="<div  onclick='getOnlinePage($prev)' class='btn-pre2'>
   <img src='images/icons/prev-all.png'' />
</div>";
} else {
    $pagination.=" <div  class='btn-pre2'>
    <img src='images/icons/prev-all.png'' />
</div>";
}


$pagination.= "<div class='main3'>
<ul class='page_ul'> 
    <li class='para'> Page</li>
    <li class='page_middle'>$page</li>
    <li class='para1'>of $lastpage</li>
</ul>
</div>";

if ($page < $lastpage) {
    $pagination.= "
    <div onclick='getOnlinePage($next)' class='main4' >
       <img src='images/icons/next-all.png' />
    </div>";

    $pagination.= "	<div onclick='getOnlinePage($lastpage)' class='main5'>
    <img src='images/icons/next-1.png' />
</div>";
} else {
    $pagination.= "
    <div class='main4'>
     <img src='images/icons/next-all.png' />
 </div>";

 $pagination.= "	<div class='main5'>
 <img src='images/icons/next-1.png' />
</div>";
}

$pagination.= "</div>\n";
}




// ------------------------------------------------End Paging code------------------------------------------------------ // 
//$limit=5;


$onlinequery.= " LIMIT  $start, $limit";

$online_member = $wpdb->get_results($onlinequery);

//For min and max age
$check_min_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'min_age'");
$check_max_age = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'max_age'");
$min_age_value = $check_min_age->setting_value;
$max_age_value = $check_max_age->setting_value;

?>


<div data-role="content" class="ui-content" role="main" >

    <form id="frm_online">
       <fieldset>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
        <label data-role="fieldcontain" class="select-group">  
          <div class="clearfix">    
            <div  class="mam_reg_lf select-label"><?php echo __('Gender:', 'wpdating') ?></div>
            <select name="gender" >

                <option value="all" <?php if ($gender == 'all' || isset($_REQUEST['show'])) { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>

                <option value="M" <?php if ($gender == 'M' && !isset($_REQUEST['show'])) { ?> selected="selected" <?php } ?> ><?php echo __('Male', 'wpdating') ?></option>

                <option value="F" <?php if ($gender == 'F' && !isset($_REQUEST['show'])) { ?> selected="selected" <?php } ?> ><?php echo __('Female', 'wpdating') ?></option>

                <?php if ($check_couples_mode->setting_status == 'Y') { ?>

                <option value="C" <?php if ($gender == 'C' && !isset($_REQUEST['show'])) { ?> selected="selected" <?php } ?> ><?php echo __('Couples', 'wpdating') ?></option>

                <?php } ?>

            </select>
        </div>
    </label>

    <div>
        <div class="col-cont clearfix">
            <div class="col-2">
              <label data-role="fieldcontain" class="select-group">  
                  <div  class="mam_reg_lf select-label"><?php echo __('Age:', 'wpdating') ?></div>
                  <select name="age_from" >

                    <!-- <?php for ($i = '18'; $i <= '90'; $i++) { ?>

                    <option value="<?php echo $i ?>" ><?php echo $i ?></option>

                    <?php } ?> -->
                    <?php
                    for ($fromyear = $min_age_value; $fromyear <= $max_age_value; $fromyear++) {
                        if ($fromyear == $min_age_value) {
                            ?>
                            <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                
            </label>
        </div>
        <div class="col-2">
            <label class="select-group">
             
             <div  class="mam_reg_lf select-label">      
                <?php echo __('to:', 'wpdating') ?></div>
                
                <select  name="age_to" >

                    <!-- <?php for ($j = '90'; $j >= '18'; $j--) { ?>

                    <option value="<?php echo $j ?>"><?php echo $j ?></option>

                    <?php } ?> -->
                    <?php
                    for ($fromyear = $max_age_value; $fromyear >= $min_age_value; $fromyear--) {
                        if ($fromyear == $max_age_value) {
                            ?>
                            <option value="<?php echo $fromyear ?>" selected="selected"><?php echo $fromyear ?></option>
                        <?php } else { ?>
                            <option value="<?php echo $fromyear ?>"><?php echo $fromyear ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                
            </label>
            
        </div>
    </div>
    <div class="btn-blue-wrap">
        <input name="submit" onclick="getOnlinePage(0)" id="login_filter" class="mam_btn btn-blue" type="button" value="<?php echo __('Filter', 'wpdating') ?>" />
    </div>
</fieldset>
</form>
<ul data-divider-theme="d" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all  dsp_ul userlist">

    <?php
    //echo $onlinequery;
    //See if there are online members or not
    if(count($online_member)<=1){
        echo '<script>
        navigator.notification.alert("No members present now.\nTry later.",null,"Alert");
        </script> ';
    }
    foreach ($online_member as $member) {

        $euser_id = $member->user_id;

        $s_user_id = $member->user_id;

        $s_country_id = $member->country_id;

        $s_gender = $member->gender;

        $s_seeking = $member->seeking;

        $s_state_id = $member->state_id;

        $s_city_id = $member->city_id;

        $s_age = GetAge($member->age);

        $s_make_private = $member->make_private;

        if($user_id != $s_user_id) { 


            $displayed_member_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$s_user_id'");

            $country_name = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$s_country_id");

            $state_name = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$s_state_id");

            $city_name = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$s_city_id");



            if ($s_user_id != '') {



                $favt_mem = array();

                $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$s_user_id'");

                foreach ($private_mem as $private) {

                    $favt_mem[] = $private->favourite_user_id;
                }


                $check_user_profile_exist = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_profiles_table WHERE user_id=$s_user_id");
                ?>
                <!--<body class="ui-mobile-viewport ui-overlay-b">-->

                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">

                    <div class="dsp_pro_full_view">


                        <div class="profile_img_view">

                            <?php
                            if ($check_couples_mode->setting_status == 'Y') {
                                if ($s_gender == 'C') {
                                    if ($s_make_private == 'Y') {
                                        if ($user_id != $s_user_id) {
                                            if (!in_array($user_id, $favt_mem)) {
                                                ?>

                                                <a onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                    <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                                </a>                

                                                <?php } else {
                                                    ?>

                                                    <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">				

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;"  class="dsp_img3" border="0"/></a>                

                                                        <?php
                                                    }
                                                } else {
                                                    ?>

                                                    <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                                    </a>

                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0"/>

                                                </a>

                                                <?php
                                            }
                                        } else {
                                            if ($s_make_private == 'Y') {
                                                if ($user_id != $s_user_id) {
                                                    if (!in_array($user_id, $favt_mem)) {
                                                        ?>

                                                        <a   onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                                        </a>                
                                                        <?php
                                                    } else {
                                                        ?>

                                                        <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"     style="width:100px; height:100px;"  class="dsp_img3" border="0"/></a>                

                                                            <?php
                                                        }
                                                    } else {
                                                        ?>

                                                        <a   onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                            <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0"/>

                                                        </a>

                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a   onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                        <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                                    </a>

                                                    <?php
                                                }
                                            }
                                        } else {
                                            ?> 


                                            <?php if ($s_make_private == 'Y') { ?>

                                            <?php if ($user_id != $s_user_id) { ?>



                                            <?php if (!in_array($user_id, $favt_mem)) { ?>

                                            <a   onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>"  style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                            </a>                

                                            <?php } else {
                                                ?>

                                                <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">			

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>"    style="width:100px; height:100px;"  class="dsp_img3" border="0"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>

                                                <a   onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0" />
                                                </a>

                                                <?php } ?>





                                                <?php } else { ?>



                                                <a  onclick="viewProfile('<?php echo $s_user_id; ?>', 'my_profile')">

                                                    <img src="<?php echo display_members_photo($s_user_id, $imagepath); ?>" style="width:100px; height:100px;"  class="dsp_img3" border="0" />

                                                </a>

                                                <?php } ?>





                                                <?php } ?>
                                            </div>

                                            <div class="dsp_on_lf_view">
                                                <div class="user-name spacer-bottom-xs spacer-top-sm">
                                                 

                                                    <?php
                                                    if ($check_couples_mode->setting_status == 'Y') {
                                                        if ($s_gender == 'C') {
                                                            ?>

                                                            <?php echo $displayed_member_name->display_name ?>                
                                                            <?php
                                                        } else {
                                                            ?>

                                                            <?php echo $displayed_member_name->display_name ?>

                                                            <?php
                                                        }
                                                    } else {
                                                        ?> 

                                                        <?php echo $displayed_member_name->display_name ?>
                                                        <?php } ?>
                                                    </div>
                                                    
                                                    <div class="user-details">
                                                        <?php echo $s_age ?> <?php echo __('year old', 'wpdating'); ?> <?php
                                                        if ($s_gender == 'M')
                                                            echo __('Man', 'wpdating');
                                                        else if ($s_gender == 'F')
                                                            echo __('Woman', 'wpdating');
                                                        else if ($s_gender == 'C')
                                                            echo __('Couples', 'wpdating');
                                                        ?> <?php echo __('from', 'wpdating'); ?> <br />
                                                        <?php
                                                        if (count($city_name) > 0) {
                                                            if ($city_name->name != "")
                                                                echo $city_name->name . ',';
                                                        }
                                                        ?> 
                                                        <?php
                                                        if (count($state_name) > 0) {
                                                            if ($state_name->name != "")
                                                                echo $state_name->name . ',';
                                                        }
                                                        ?> 
                                                        <?php echo $country_name->name; ?>
                                                    </div>
                                                    
                                                    <div class="dsp_prof_view">
                                                        <div style="width: 100%">
                                                            <?php if ($check_my_friend_module->setting_status == 'Y') {
                                                                ?>
                                                                <div>
                                            <?php if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 	 
                                                ?>
                                                <a  onclick="addFriend('<?php echo $s_user_id; ?>')"  title="<?php echo __('Add to Friends', 'wpdating'); ?>">
                                                    <img src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" />
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick="redirectEditProfile('<?php echo __('Please update Profile Before Add to friend.', 'wpdating') ?>');" title="Edit Profile">
                                                    <img src="<?php echo $fav_icon_image_path ?>friend.jpg" border="0" />
                                                </a> 
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                            <div>
                                                <a onclick="addFavourite('<?php echo $s_user_id; ?>')" title="<?php echo __('Add to Favorites', 'wpdating'); ?>">
                                                    <img src="<?php echo $fav_icon_image_path ?>star.jpg" border="0" />
                                                </a>
                                            </div>
                                            <div>
                                                <a onclick="composeMessage('<?php echo $s_user_id ?>', 0)"  title="<?php echo __('Send Message', 'wpdating'); ?>">
                                                    <img src="<?php echo $fav_icon_image_path ?>mail.jpg" border="0" />
                                                </a>
                                            </div>

                                            
                                    <?php if ($check_flirt_module->setting_status == 'Y') { // Check FLIRT (WINK) module Activated or not 
                                        ?>
                                        <div>
                                            <?php
                                            // CHECK MEMBER LOGIN
                                            if ($check_user_profile_exist > 0) {  // check user dating profile exist or not 		  
                                                ?>
                                                <a onclick="sendWink('<?php echo $s_user_id; ?>')"  title="<?php echo __('Send Wink', 'wpdating'); ?>">
                                                    <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <a onclick="redirectEditProfile('<?php echo __('Please update Profile Before Add to friend.', 'wpdating') ?>');" title="Edit Profile">
                                                    <img src="<?php echo $fav_icon_image_path ?>wink.jpg" border="0" />
                                                </a>
                                                <?php } ?>
                                            </div>
                                            <?php
                                    } // END My friends module Activation check condition 
                                    // check if one to one csetting is yes
                                    if ($check_chat_one_mode->setting_status == 'Y') {
                                        // send chat request if user is online
                                        if ($s_user_id != $user_id) { // if this member is not user itself
                                            $check_online_user = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_online_table WHERE user_id=$s_user_id");

                                            if ($check_online_user > 0) { // show chat icon if user is online
                                                ?>
                                                <div>
                                                    <a onclick="openChatRoom('<?php echo $s_user_id; ?>', 'send_request')">
                                                        <img style="width:20px;height:20px" src="<?php echo $fav_icon_image_path ?>chat.jpg" border="0" />
                                                    </a>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                    ?>
                                </div>

                            </div>


                        </div>








                    </div>
                    <!--</body>-->

                    <?php
                }

                unset($favt_mem);
                ?>
            </li>  

            <?php  } } ?>
            
        </ul>

       
        <?php include_once('dspNotificationPopup.php'); // for notification pop up    ?>
    </div>
 <div class="ds_pagination" > 
            <?php echo $pagination; ?>
        </div>
    <?php include_once("dspLeftMenu.php"); ?>
