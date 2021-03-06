<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
  
$get_sender_id = get('sender_ID');
$request_Action = get('Act');
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="dsp_back_inbox dspdp-spacer"><a class=" dspdp-btn dspdp-btn-default dspdp-btn-xs" href="<?php echo $root_link . "email/inbox/"; ?>"><?php echo __('Back to Inbox', 'wpdating'); ?></a></div>
        <?php
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++
        if (get('page'))
            $page = get('page');
        else
            $page = 1;

        // How many adjacent pages should be shown on each side?
        $adjacents = 2;
        $limit = 10;
        if ($page)
            $start = ($page - 1) * $limit;    //first item to display on this page
        else
            $start = 0;
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $bolIfSearchCriteria = true;
        $strQuery = "SELECT * FROM $dsp_user_emails_table where  (sender_id = $get_sender_id AND receiver_id=$user_id)";
        $intRecordsPerPage = 10;
        $intStartLimit = get('p'); # page selected 1,2,3,4...
        if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
            $intStartLimit = 1; //default
        }
        $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
        if ($bolIfSearchCriteria) {
            $strQuery = $strQuery . " AND delete_message=0 ORDER BY thread_id desc";
            $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
        }
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
        $page_name = $root_link . "email/view_message/sender_ID/" . $get_sender_id . "/";
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()

        $intTotalRecordsEffected = $user_count;
        if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
            //print "Total records found: " . $intTotalRecordsEffected;
        }

        $strQuery . " LIMIT $start, $limit ";
        $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit ");    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++
        $dateTimeFormat = dsp_get_date_timezone();
        extract($dateTimeFormat);
        foreach ($my_messages as $message) {
            $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");
            $message_date = date("$dateFormat $timeFormat", strtotime($message->sent_date));

            /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

            if ($page == 0)
                $page = 1;     //if no page var is given, default to 1.
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_results1 / $limit);
            ;  //lastpage is = total pages / items per page, rounded up.
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
                    $pagination.= "<div><a style='color:#365490' href=\"" . $page_name . "page/$prev\">".__('Previous', 'wpdating')."</a></div>";
                else
                    $pagination.= "<span  class='disabled'>".__('Previous', 'wpdating')."</span>";

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
                    $pagination.= "<div><a class='disabled' href=\"" . $page_name . "page/$next\">".__('Next', 'wpdating')."</a></div>";
                else
                    $pagination.= "<span class='disabled'>".__('Next', 'wpdating')."</span>";
                $pagination.= "</div>\n";
            }

            /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
            ?>
            <div class="dspdp-bordered-item-md"><div class="view-message dspdp-row dsp-row">
                <div class="dspdp-col-sm-2"><?php
                    $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");
                    $exist_make_private->make_private;
                    $favt_mem = array();

                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$message->sender_id'");
                    foreach ($private_mem as $private) {
                        $favt_mem[] = $private->favourite_user_id;
                    }
                    $user_gender = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->sender_id'");
                    $user_gender->gender;


                    if ($check_couples_mode->setting_status == 'Y') {
                        if ($user_gender->gender == 'C') {
                            ?>
                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>" >
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" class="img dspdp-img-responsive" align="left" alt="Private Photo"/>
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>" >				
                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                    <?php
                                }
                            } else {
                                ?>

                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/my_profile/"; ?>">
                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                </a>
                            <?php } ?>

                        <?php } else { ?>

                            <?php if ($exist_make_private->make_private == 'Y') { ?>

                                <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>" >
                                        <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" class="img dspdp-img-responsive" align="left" alt="Private Photo" />
                                    </a>                
                                <?php } else {
                                    ?>
                                    <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>" >				
                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>"/></a>                
                                    <?php
                                }
                            } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>" />
                                </a>
                            <?php } ?>

                            <?php
                        }
                    } else {
                        ?> 

                        <?php if ($exist_make_private->make_private == 'Y') { ?>

                            <?php if (!in_array($current_user->ID, $favt_mem)) { ?>
                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>" >
                                    <img src="<?php echo WPDATE_URL . '/images/private-photo-pic.jpg'; ?>" class="img dspdp-img-responsive" align="left" alt="Private Photo" />
                                </a>                
                            <?php } else {
                                ?>
                                <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>" >				
                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>                
                                <?php
                            }
                        } else {
                            ?>
                            <a href="<?php echo $root_link . get_username($message->sender_id) . "/"; ?>">
                                <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" class="img dspdp-img-responsive" align="left" alt="<?php echo get_username($message->sender_id);?>" /></a>
                        <?php } ?>

                    <?php } ?>
                </div>
                <div class="dspdp-col-sm-9 dsp-sm-9"> <div class="msg-info">
                        <div>
                            <div class="name dspdp-text-info dspdp-h4 dspdp-reset dspdp-spacer-sm"><?php echo $display_sender_name->display_name; ?></div>
                            <div class="dsp-msg-date"><?php echo $message_date ?></div>
                            <?php $msg= str_replace("\\", "", $message->text_message);?>
                            <div class="dspdp-spacer dsp-spacer"><?php echo str_replace('==','<br>', $msg); ?></div>
                            <?php if ($message->sender_id != $user_id) { ?>
                                <div><a class="btn-reply dspdp-btn dspdp-btn-default dspdp-btn-xs" href="<?php echo $root_link . "email/compose/msgid/" . $message->message_id . "/sender_ID/" . $message->sender_id . "/Act/Reply/"; ?>"><?php echo __('Reply', 'wpdating'); ?></a></div>
                            <?php } //end if   ?>
                        </div>
                    </div>
                </div>
            </div></div>
            <?php
            unset($favt_mem);
        } // End for loop  
        ?>
        <?php /* Setup page vars for display. */
        ?>
        <div style="float:left; width:100%;">
            <?php
            // --------------------------------  PRINT PAGING LINKS ------------------------------------------- //
            if (isset($pagination))
                echo $pagination;
            else
                echo '';
// -------------------------------- END OF PRINT PAGING LINKS ------------------------------------- //
            ?>
        </div>
    </div>
</div>