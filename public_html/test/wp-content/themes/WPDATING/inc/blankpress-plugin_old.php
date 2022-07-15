<?php

/* -------------------------------------------------
  BlankPress - Functions
  -------------------------------------------------- */

function dsp_get_new_members($limit = '12') {
    global $wpdb;
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND country_id!=0 order by last_update_date desc LIMIT 21");
    return $new_members;
}

///get online users
function dsp_get_online_users() {
    global $wpdb;
    $dsp_online_table = $wpdb->prefix . DSP_USER_ONLINE_TABLE;
    $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
    $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
    $check_online_member_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'random_online_members'");
    $random_online_status = dsp_check_online_setting();
    // get the random online members setting
    $random_online_number = $check_online_member_mode->setting_value;
    $online_members = ($random_online_status) ? dsp_randomOnlineMembers($random_online_number) : dsp_getOnlineMembers();
    //dsp_debug($wpdb->last_query);die;
//    $online_members = $wpdb->get_results("SELECT distinct online.user_id,gender, profile.age FROM `$dsp_online_table` online inner join $dsp_user_profiles profile on(online.user_id=profile.user_id) and online.status='Y'  and profile.stealth_mode != 'Y'");
    
    return $online_members;
}

///get User Age
if (!function_exists('dsp_get_age')) {

    function dsp_get_age($Birthdate) {
        $dob = strtotime($Birthdate);
        $y = date('Y', $dob);
        if (($m = (date('m') - date('m', $dob))) < 0) {
            $y++;
        } elseif ($m == 0 && date('d') - date('d', $dob) < 0) {
            $y++;
        }
        return date('Y') - $y;
    }

}

if (!function_exists('display_members_photo')) {
    /*     * *******************START FUNCTION CREATE thumb MEMBER PHOTO PATH************************ */

    function display_members_photo($photo_member_id, $path) {
        global $wpdb;
        $favt_mem = array();
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;  // print session USER_ID
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
        $count_member_images = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_members_photos WHERE user_id='$photo_member_id' AND status_id=1");
        if ($count_member_images > 0) {
            $member_exist_picture = $wpdb->get_row("SELECT * FROM $dsp_members_photos WHERE user_id = '$photo_member_id' AND status_id=1");
            $check_gender = $wpdb->get_row("SELECT gender,make_private FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($member_exist_picture->picture == "") {
                if ($check_gender->gender == 'M') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                } else if ($check_gender->gender == 'F') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                } else if ($check_gender->gender == 'C') {
                    $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                }
                //$Mem_Image_path=$path."images/no-image.jpg";
            } else {
                if ($photo_member_id == $user_id) {
                    $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                } else {
                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$photo_member_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    if ($check_gender->make_private == 'Y') {
                        if (!in_array($user_id, $favt_mem)) {
                            $Mem_Image_path = plugins_url('dsp_dating/images/private-photo-pic.jpg');
                        } else {
                            $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                        }
                    } else {
                        $Mem_Image_path = $path . "uploads/dsp_media/user_photos/user_" . $photo_member_id . "/thumbs1/thumb_" . $member_exist_picture->picture;
                    }
                }
                $Mem_Image_path = str_replace(' ', '%20', $Mem_Image_path);
                if (@file_get_contents($Mem_Image_path)) {
                    $Mem_Image_path = $Mem_Image_path;
                } else {
                    if ($check_gender->gender == 'M') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
                    } else if ($check_gender->gender == 'F') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
                    } else if ($check_gender->gender == 'C') {
                        $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
                    }
                }
            }
        } else {
            $check_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles  WHERE user_id = '$photo_member_id'");
            if ($check_gender->gender == 'M') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/male-generic.jpg";
            } else if ($check_gender->gender == 'F') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/female-generic.jpg";
            } else if ($check_gender->gender == 'C') {
                $Mem_Image_path = $path . "plugins/dsp_dating/images/couples-generic.jpg";
            }
            //$Mem_Image_path=$path."images/no-image.jpg";
        }
        return $Mem_Image_path;
    }

}


//Create Pagination
if (!function_exists('dsp_get_pagination')) {

    function dsp_get_pagination() {
        if (get('page') != "")
            $page = get('page');
        else
            $page = 1;
        // How many adjacent pages should be shown on each side?
        $adjacents = 2;
        $limit = 1;
        if ($page)
            $start = ($page - 1) * $limit;    //first item to display on this page
        else
            $start = 0;
        if ($page == 0)
            $page = 1;     //if no page var is given, default to 1.
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_results1 / $limit);  //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ($lastpage > 1) {
            $pagination .= "<div class='wpse_pagination'>";
            //previous button
            if ($page > 1)
                $pagination.= "<div><a class='disabled' href=\"" . $page_name . "page/$prev\">previous</a></div>";
            else
                $pagination.= "<span  class='disabled'>previous</span>";

            //pages
            if ($lastpage <= 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class='current'>$counter</span>";
                    else
                        $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some//5
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                    }
                    $pagination.= "<span>...</span>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination.= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                    $pagination.= "<span>...</span>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<div class='current'>$counter</div>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                    }
                    $pagination.= "<span>...</span>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/$lpm1\">$lpm1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/$lastpage\">$lastpage</a></div>";
                }
                //close to end; only hide early pages
                else {
                    $pagination.= "<div><a href=\"" . $page_name . "page/1\">1</a></div>";
                    $pagination.= "<div><a href=\"" . $page_name . "page/2\">2</a></div>";
                    $pagination.= "<span>...</span>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination.= "<span class='current'>$counter</span>";
                        else
                            $pagination.= "<div><a href=\"" . $page_name . "page/$counter\">$counter</a></div>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination.= "<div class='disabled'><a href=\"" . $page_name . "page/$next\">next</a></div>";
            else
                $pagination.= "<span class='disabled'>next</span>";
            $pagination.= "</div>\n";
        }
        return $pagination;
    }

}

//Member Page
if (!function_exists('dsp_get_all_members')) {

    function dsp_get_all_members() {
        global $wpdb;
        $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
        $start = 0;
        $limit = 10;
        $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $check_member_list_gender_mode = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'member_list_gender'");
        $member_list_gender = $check_member_list_gender_mode->setting_value;
        $page_name = $root_link . "ALL/";
        if ($member_list_gender == 2) {
            $member_gender = 'M';
        } else if ($member_list_gender == 3) {
            $member_gender = 'F';
        } else
            $member_gender = '';
        if ($member_gender != '') {
            $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1 AND gender='$member_gender' order by user_profile_id DESC");
        } else {
            $total_results1 = $wpdb->get_var("SELECT COUNT(*) as Num FROM $dsp_user_profiles WHERE status_id=1  order by user_profile_id DESC");
        }
        //die($total_results1);
        if ($member_gender != '') {

            if ($check_couples_mode->setting_status == 'Y') {
                $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 and stealth_mode='N' Order By user_profile_id DESC LIMIT $start, $limit");
            } else {

                $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1  AND gender='$member_gender' AND country_id!=0 AND gender!='C' and stealth_mode='N' Order By user_profile_id DESC LIMIT $start, $limit");
            }
        } else {
            if ($check_couples_mode->setting_status == 'Y') {
                $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND country_id!=0 and stealth_mode='N' Order By user_profile_id DESC LIMIT $start, $limit");
            } else {
                $new_members = $wpdb->get_results("SELECT * FROM $dsp_user_profiles WHERE status_id=1 AND country_id!=0 AND gender!='C' and stealth_mode='N' Order By user_profile_id DESC LIMIT $start, $limit");
            }
        }
        return $new_members;
    }

}

if(!function_exists('dsp_get_membership_type')){
    function dsp_get_membership_type($user_id){

        global $wpdb;
        $dsp_payments_table = $wpdb->prefix .DSP_PAYMENTS_TABLE;
        $payment_row = $wpdb->get_row("SELECT * FROM $dsp_payments_table WHERE pay_user_id=$user_id");
        $count_payment_row=count($payment_row);    
        if($count_payment_row>0){
            return 'premium';
        }
        else{
            return 'standard';
        }
    }
}

if(!function_exists('dsp_get_expiry_date')){

    function dsp_get_expiry_date($user_id){
       
        
        $now = time(); // or your date as well
        $your_date = strtotime($payment_row->expiration_date);
        $datediff = $your_date - $now;
        $days=floor($datediff/(60*60*24));

        return $expiry_date;
    }

}


if(!function_exists('dsp_get')){
    function dsp_get($string)
    {

       $count=2;

       $root=get_bloginfo('url');
       $Url = explode('/',$root);
       unset($Url[0], $Url[1]);
       $length= sizeof($Url);

       $url = explode('/', $_SERVER["REQUEST_URI"]);
        unset($url[0], $url[1]);

        if($length>1) // check the level of member page if page is on 3 level like www.abc.com/dating/members
        {
            $count++;
        }
        else if($length==0)// if page is on 0 level like www.abc.com/ home page is member page
        {
            $count--;
        }
        // if $length==1 then page is on 2 level like www.abc.com/members

        $i2=$count;
        $i3=$count+1;
        $i4=$count+2;
        $i5=$count+3;
        $i6=$count+4;

        print_r($url);
        die('asdf');

      if(get_userid($url[$i2])!="")
        {

            if(!is_user_logged_in())
            {
                if(''!=$url[$i3])
                {
                    if($url[$i3]=='my_profile' || $url[$i3]=='partner_profile')
                        $get=array('pgurl'=>'view_member','guest_pageurl'=>'view_mem_profile','mem_id'=>get_userid($url[$i2]),'view'=>$url[$i3]);
            else
                    {
                        $get=array('pgurl'=>'view_member','guest_pageurl'=>'view_mem_'.$url[$i3],'mem_id'=>get_userid($url[$i2]));
                         if($length>1) //
                        {
                            $j=5;
                        }
                        else
                        {
                            $j=4;
                        }
                        for ($i=$j;$i<count($url);$i=$i+$i2)
                        {
                            $get[$url[$i]] = $url[$i + 1];
                        }
                    }
                }
                else
            {
             $get=array('pgurl'=>'view_member','guest_pageurl'=>'view_mem_profile','mem_id'=>get_userid($url[$i2]));
                }
                  //  print_r($get);
        }
        else
            {
               //  echo '<br>url-3'.$url[$i3];
          if(''!=$url[$i3])
                       {
                  if($url[$i3]=='my_profile' || $url[$i3]=='partner_profile')
                              {
                                  if($url[$i4]=='Action')
                                  {
                                      $get=array('pid' =>3,'mem_id'=>get_userid($url[$i2]), 'pagetitle'=>"view_profile", 'view'=>$url[$i3], 'Action'=>$url[$i5],$url[$i6]=>$url[$i7]);
                                  }
                                  else
                                  {
                                      $get=array('pid' =>3,'mem_id'=>get_userid($url[$i2]), 'pagetitle'=>"view_profile", 'view'=>$url[$i3]);
                                  }

                              }
                              else if($url[$i3]=='Action')
                              {
                                  $get=array('pid' =>3,'mem_id'=>get_userid($url[$i2]), 'pagetitle'=>"view_profile", 'Action'=>$url[$i4],$url[$i5]=>$url[$i6]);
                              }
                  else
                              {

                                $get=array('pid' =>3,'mem_id'=>get_userid($url[$i2]), 'pagetitle'=>"view_".$url[$i3]);
                                if($length>1) //
                                {
                                  $j=5;
                                }
                                else
                                {
                                    $j=4;
                                }
                    for ($i=$j;$i<count($url);$i=$i+$i2)
                    {
                                   $get[$url[$i]] = $url[$i + 1];
                                }
                  }
                             // print_r($get);
              }
              else
              {
                 $get=array('pid' =>3,'mem_id'=>get_userid($url[$i2]), 'pagetitle'=>"view_profile");
              }

          }

      }
      else {
        if(!is_user_logged_in())
            {
                if($length>1) //
                {
                    $k=1;
                    $m=1;
                }
                else
                {
                    $k=0;
                    $m=0;
                }
                array_splice($url,$k,0,'pgurl');
                //print_r($url);
                //echo '<br>========';
                for ($i=$m;$i<count($url);$i=$i+2)
            {
                    $get[$url[$i]] = $url[$i + 1];
                }
               // print_r($get);

        }
        else
            {
                // echo 'page-id=='.get_pageid($url[$i2]).' url2=='.$url[$i2];
                if(get_pageid($url[$i2])!="")
                {
                // array_splice($url,0,0,'pid');
                 $get['pid']=get_pageid($url[$i2]);
                            // echo 'call=='.get_pageid($url[$i2]);
                  if(get_pageid($url[$i2])==14)
                                {

                                // print_r($_REQUEST);
                                 if(isset($_REQUEST['mode']) && isset($_REQUEST['delmessage']) )
                                 {
                                    $get=array('pid' =>14,'message_template'=>$url[$i3],'mode'=>$_REQUEST['mode'],'delmessage'=>$_REQUEST['delmessage']);

                                 }
                                 else
                                 {
                                      $get['message_template']=$url[$i3];
                                 }


                                }
                 else if(get_pageid($url[$i2])==15) { $get['pagetitle']='chat'; }
                 else if(get_pageid($url[$i2])==10) { $get['pagetitle']='online_mem'; }
                 else if(get_pageid($url[$i2])==8 || get_pageid($url[$i2])==7) {  }
                 else
                             {
                                 $get['pagetitle']=$url[$i3];
                                // echo $get['pagetitle'].''.$url[$i3].''.$i3;
                             }

                              if($length>1) //
                                    {
                                    $j=5;

                                    }
                                    else
                                    {
                                         $j=4;
                                    }

                 if(get_pageid($url[$i2])==8 || get_pageid($url[$i2])==7)
                                {
                                 if($length>1) //
                                    {
                                    $j=4;

                                    }
                                    else
                                    {
                                        $j=3;
                                    }

                                }

                            // echo 'id==='.get_pageid($url[$i2]).'  =='.$url[$i3];

                             if(get_pageid($url[$i2])==13 && $url[$i3]=='blogs')
                             {
                               $get['subpage']=$url[$i4];
                                if($length>1) //
                                {
                                    $j=6;

                                }
                                else
                                {
                                        $j=5;
                                }

                             }
                             else  if(get_pageid($url[$i2])==13)  // check if page is trending save it's request with get
                             {
                                   if(isset($_REQUEST['profile_filter']) || isset($_REQUEST['gender_filter']) )
                                 {
                                    $get=array('pid' =>13,'pagetitle'=>$url[$i3],'gender_filter'=>$_REQUEST['gender_filter'],'profile_filter'=>$_REQUEST['profile_filter']);
                                 }
                                 else
                                 {
                                   $get=array('pid' =>13,'pagetitle'=>$url[$i3]);
                                 }
                             }


                            for ($i=$j;$i<count($url);$i=$i+2)
                {
                                $get[$url[$i]] = $url[$i + 1];

                  }
                //   print_r($get);
             }

         }
      }
      //print_r($get);
        return $get[$string];

    }
}