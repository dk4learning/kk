<?php
global $wpdb;
include("../../../../wp-config.php");
include (WP_DSP_ABSPATH . 'mobile/files/includes/english.php');
$DSP_MEMBER_WINKS_TABLE = $wpdb->prefix . DSP_MEMBER_WINKS_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$DSP_USERS_TABLE = $wpdb->prefix . DSP_USERS_TABLE;
$DSP_USER_PROFILES_TABLE = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
$dsp_user_favourites_table = $wpdb->prefix . DSP_FAVOURITE_LIST_TABLE;
include_once(WP_DSP_ABSPATH . 'mobile/dsp_get_image.php');
include_once(WP_DSP_ABSPATH . '/general_settings.php');
$image_path = get_bloginfo('url') . '/wp-content/';  // image Path
$pluginpath = $_GET['pluginpath'];
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
$root_link = $_GET['root_link'];
$pagination_limit = DSP_PAGINATION_LIMIT;
// ------------------------------------------------End Paging code------------------------------------------------------ //
?>
<table width="100%">
    <?php
    $exist_profile_details = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE user_id = '$current_user->ID'");
    if ($exist_profile_details->gender == "M") {
        $gender_check = "and gender='F' ";
    } else
    if ($exist_profile_details->gender == "F") {
        $gender_check = "and gender='M' ";
    } else
    if ($exist_profile_details->gender == "C") {
        $gender_check = "and gender in ('M','F','C') ";
    }

    // ----------------------------------------------- Start Paging code New Member------------------------------------------------------ // 
    if (isset($_GET['page1']))
        $page1 = $_GET['page1'];
    else
        $page1 = 1;
    $max_results1 = $pagination_limit;
    $adjacents = DSP_PAGINATION_ADJACENTS;
    $limit = $max_results1;
    $from1 = (($page1 * $max_results1) - $max_results1);

    //echo "SELECT * FROM $DSP_USER_PROFILES_TABLE WHERE status_id=1 AND last_update_date > DATE_SUB(now(), INTERVAL 14 DAY) ";
    $totalQuery = "SELECT receiver_id,count(wink_id)as wink FROM $DSP_MEMBER_WINKS_TABLE 
							join $DSP_USER_PROFILES_TABLE where receiver_id=user_id $gender_check 
							Group by receiver_id order by wink desc ";


    //echo $totalQuery;			 
    $total_results1 = $wpdb->get_results($totalQuery);
    $total_pages1 = count($total_results1);
// ------------------------------------------------End Paging code------------------------------------------------------ //


    $popularLimitQuery = $totalQuery . " LIMIT " . $from1 . ", " . $max_results1;
    $pop_members = $wpdb->get_results($popularLimitQuery);
    $count_pop_mem = count($pop_members);
//echo $popularLimitQuery;
    $i = 0;
    foreach ($pop_members as $popmember) {
        //   echo 'check couple'. $check_couples_mode->setting_status ;
        if ($check_couples_mode->setting_status == 'Y') {
            $exist_pop_members = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE where user_id='$popmember->receiver_id' AND country_id!=0");
        } else {
            $exist_pop_members = $wpdb->get_row("SELECT * FROM $DSP_USER_PROFILES_TABLE where user_id='$popmember->receiver_id' AND gender!='C' AND country_id!=0");
        }
        if (count($exist_pop_members) > 0) {
            $exist_user_name = $wpdb->get_row("SELECT * FROM $DSP_USERS_TABLE WHERE ID='$popmember->receiver_id'");
            $user_name = $exist_user_name->display_name;
            $pop_member_id = $exist_pop_members->user_id;
            $favt_mem = array();
            $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$pop_member_id'");
            foreach ($private_mem as $private) {
                $favt_mem[] = $private->favourite_user_id;
            }
            if ($pop_member_id != '') {
                if (($i % 2) == 0) {
                    ?>

                    <tr>

                    <?php } // End if(($i%4)==0) ?>

                    <td <?php
                    if (($i % 2) == 0) {
                        echo 'width="30%"';
                    }
                    ?>  align="left">

                        <table cellpadding="0" cellspacing="0" border="0" align="left">

                            <tr>

                                <td align="center">

                                    <?php
                                    if ($check_couples_mode->setting_status == 'Y') {
                                        if ($exist_pop_members->gender == 'C') {
                                            ?>
                                            <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                                <?php if ($current_user->ID != $pop_member_id) { ?>

                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $pop_member_id,
                                                            'pagetitle' => "view_profile",
                                                            'view' => "my_profile"), $root_link);
                                                        ?>" >
                                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:100px; height:100px;" border="0" class="dsp_img3" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $pop_member_id,
                                                            'pagetitle' => "view_profile",
                                                            'view' => "my_profile"), $root_link);
                                                        ?>" >				
                                                            <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:100px; height:55px;"/></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $pop_member_id,
                                                        'pagetitle' => "view_profile",
                                                        'view' => "my_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                
                                                <?php } ?>
                                            <?php } else { ?>   
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $pop_member_id,
                                                    'pagetitle' => "view_profile",
                                                    'view' => "my_profile"), $root_link);
                                                ?>">    
                                                    <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"   class="dsp_img3" style="width:55px; height:55px;"/></a>
                                            <?php } ?>

                                        <?php } else { ?>

                                            <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                                <?php if ($current_user->ID != $pop_member_id) { ?>
                                                    <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $pop_member_id,
                                                            'pagetitle' => "view_profile"), $root_link);
                                                        ?>" >
                                                            <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;" border="0" class="dsp_img3" />
                                                        </a>                
                                                    <?php } else {
                                                        ?>
                                                        <a href="<?php
                                                        echo add_query_arg(array(
                                                            'pid' => 3, 'mem_id' => $pop_member_id,
                                                            'pagetitle' => "view_profile"), $root_link);
                                                        ?>" >				
                                                            <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3, 'mem_id' => $pop_member_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                
                                                <?php } ?>
                                            <?php } else { ?> 
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $pop_member_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>">
                                                    <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"   class="dsp_img3" style="width:55px; height:55px;"/></a>
                                            <?php } ?>

                                            <?php
                                        }
                                    } else {
                                        ?> 

                                        <?php if ($exist_pop_members->make_private == 'Y') { ?>

                                            <?php if ($current_user->ID != $pop_member_id) { ?>
                                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array(
                                                        'pid' => 3,
                                                        'mem_id' => $pop_member_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >
                                                        <img src="<?php echo $image_path ?>plugins/dsp_dating/images/private-photo-pic_mb.jpg" style="width:55px; height:55px;" border="0" class="dsp_img3" />
                                                    </a>                
                                                <?php } else {
                                                    ?>
                                                    <a href="<?php
                                                    echo add_query_arg(array('pid' => 3,
                                                        'mem_id' => $pop_member_id,
                                                        'pagetitle' => "view_profile"), $root_link);
                                                    ?>" >				
                                                        <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <a href="<?php
                                                echo add_query_arg(array('pid' => 3,
                                                    'mem_id' => $pop_member_id,
                                                    'pagetitle' => "view_profile"), $root_link);
                                                ?>" >				
                                                    <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"    class="dsp_img3" style="width:55px; height:55px;"/></a>                
                                            <?php } ?>
                                        <?php } else { ?>   
                                            <a href="<?php
                                            echo add_query_arg(array('pid' => 3,
                                                'mem_id' => $pop_member_id,
                                                'pagetitle' => "view_profile"), $root_link);
                                            ?>">
                                                <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"   class="dsp_img3" style="width:55px; height:55px;"/></a>
                                        <?php } ?>

                                    <?php } ?>

                                    <!--  <a href="<?php
                                    echo add_query_arg(array(
                                        'pid' => 3, 'mem_id' => $pop_member_id, 'pagetitle' => 'view_profile'), $root_link);
                                    ?>">

                                      <img src="<?php echo display_members_photo_mb($pop_member_id, $image_path); ?>"  width="55px" height="55px" class="dsp_img3"/></a>-->

                                </td>

                            </tr>

                            <tr>

                                <td class="dsp_name" align="center">

                                    <a href="<?php
                                    echo add_query_arg(array('pid' => 3, 'mem_id' => $pop_member_id,
                                        'pagetitle' => 'view_profile'), $root_link);
                                    ?>"><?php echo $user_name; ?></a>

                                </td>

                            </tr>

                        </table>

                    </td>

                    <?php
                    if ($count_pop_mem == '1') {
                        ?>
                        <td >&nbsp;</td>		

                        <?php
                    }
                    $i++;
                    unset($favt_mem);
                } // if member id is not blank
            }// end of if user exist
        }// end of for each 
        ?>
    </tr>    	
</table><br>
<?php
/* Setup page vars for display. */
if ($page1 == 0)
    $page1 = 1;     //if no page var is given, default to 1.
$prev = $page1 - 1;       //previous page is page - 1
$next = $page1 + 1;       //next page is page + 1
$lastpage = ceil($total_pages1 / $limit);
//lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;      //last page minus 1
// echo 'page1='.$page1.' last page='.$lastpage.'  total page='.$total_pages1.'limit='.$limit;
/*
  Now we apply our rules and draw the pagination object.
  We're actually saving the code to a variable in case we want to draw it more than once.
 */
$pagination = "";
if ($lastpage > 1) {
    $pagination .= "<div class=\"dspmb_pagination\">";
    //previous button
    if ($page1 > 1)
        $pagination.= "<div><a onclick=\"getPopMemPage('" . $prev . "','" . $root_link . "');\" >Previous</a></div>";
    else
        $pagination.= "<span class=\"disabled\">previous</span>";

    //pages	
    if ($lastpage <= 3 + ($adjacents * 2)) { //not enough pages to bother breaking it up//4
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page1)
                $pagination.= "<span class=\"current\">$counter</span>";
            else
                $pagination.= "<div><a onclick=\"getPopMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
        }
    }
    elseif ($lastpage > 3 + ($adjacents * 2)) { //enough pages to hide some//5
        //close to beginning; only hide later pages
        if ($page1 <= 1 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= 1 + ($adjacents * 2); $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getPopMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
            $pagination.="<div><a onclick=\"getPopMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

            $pagination.="<div><a onclick=\"getPopMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
        }
        //in middle; hide some front and some back
        elseif ($lastpage - ($adjacents * 2) > $page1 && $page1 > ($adjacents * 2)) {
            $pagination.="<div><a onclick=\"getPopMemPage('1','" . $root_link . "')\">1</a></div>";

            $pagination.= "<div><a onclick=\"getPopMemPage('2','" . $root_link . "')\">2</a></div>";
            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
            for ($counter = $page1 - $adjacents; $counter <= $page1 + $adjacents; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<div class=\"current\">$counter</div>";
                else
                    $pagination.= "<div><a onclick=\"getPopMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
            $pagination.= "<div class='dspmb_pagination_dot'>...</div>";
            $pagination.= "<div><a onclick=\"getPopMemPage('" . $lpm1 . "','" . $root_link . "')\">" . $lpm1 . "</a></div>";

            $pagination.= "<div><a onclick=\"getPopMemPage('" . $lastpage . "','" . $root_link . "')\">" . $lastpage . "</a></div>";
        }
        //close to end; only hide early pages
        else {
            $pagination.= "<div><a onclick=\"getPopMemPage('1','" . $root_link . "')\">1</a></div>";
            $pagination.= "<div><a onclick=\"getPopMemPage('2','" . $root_link . "')\">2</a></div>";
            $pagination.="<div class='dspmb_pagination_dot'>...</div>";
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page1)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<div><a onclick=\"getPopMemPage('" . $counter . "','" . $root_link . "')\">" . $counter . "</a></div>";
            }
        }
    }

    //next button
    if ($page1 < $lastpage)
        $pagination.="<div><a onclick=\"getPopMemPage('" . $next . "','" . $root_link . "');\" >next</a></div>";
    else
        $pagination.= "<span class=\"disabled\">next</span>";
    $pagination.= "</div>\n";
}
?>
<div class="dspmb_main_paging">
    <?php echo $pagination ?>
</div>