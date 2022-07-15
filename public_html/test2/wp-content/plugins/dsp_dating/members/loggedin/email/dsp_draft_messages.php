<?php 
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */

global $wpdb;

$dsp_draft_table = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;
$count_messages = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_draft_table WHERE  sender_id=$user_id ");
if ( isset( $_POST['deldraft'] ) && $_POST['mode'] == "delete_draft" ) {
    $draft_IDs = $_POST['deldraft'];
    for ($i = 0; $i < sizeof($draft_IDs); $i++) {
        $wpdb->query("DELETE FROM $dsp_draft_table WHERE draft_id = '" . $draft_IDs[$i]."'");
    } // End for loop
}
 ?>

<div class="box-border">
    <div class="box-pedding">
       <?php if($count_messages > 0):?> 
        <div class="heading-submenu"><strong><?php echo __('Draft Messages','wpdating'); ?></strong></div>
        <form name="frmdeldraft" action="" method="post" class="dsp-form">
            <div class="gray-title-head">
                <div class="heading-top"><strong><?php echo __('Sender', 'wpdating'); ?></strong></div>
                <strong><?php echo __('Subject', 'wpdating') ?></strong>
            </div>
            
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
                $dsp_user_profiles = $wpdb->prefix . DSP_USER_PROFILES_TABLE;

                // if ($check_couples_mode->setting_status == 'Y') {
                //     $strQuery = "SELECT * FROM $dsp_draft_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 group by m.sender_id";
                // } else {
                //     $strQuery = "SELECT * FROM $dsp_draft_table m,$dsp_user_profiles p WHERE m.sender_id = p.user_id AND m.receiver_id=$user_id and m.delete_message=0 AND p.gender!='C' group by m.sender_id";
                // }
                 $strQuery = "SELECT * FROM $dsp_draft_table WHERE sender_id = $user_id ";

                $intRecordsPerPage = 10;
                $intStartLimit = get('p'); # page selected 1,2,3,4...
                if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                    $intStartLimit = 1; //default
                }
                $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;
                if ($bolIfSearchCriteria) {
                    $strQuery = $strQuery . " ORDER BY save_date desc ";
                    $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
                }
// ----------------------------------------------- Start Paging code------------------------------------------------------ //
                $page_name = $root_link . "email/draft/";
                $total_results1 = $user_count;
// Calculate total number of pages. Round up using ceil()
                //$total_pages1 = ceil($total_results1 / $max_results1); 
// ------------------------------------------------End Paging code------------------------------------------------------ // 
                $intTotalRecordsEffected = $user_count;
                if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {
                    //print "Total records found: " . $intTotalRecordsEffected;
                }

                $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit  ");
                $dateTimeFormat = dsp_get_date_timezone();
                extract($dateTimeFormat);
                foreach ($my_messages as $message) {
                    $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->receiver_id'");
                    $message_date = date("$dateFormat $timeFormat", strtotime($message->save_date));
                    $message_subject = $wpdb->get_row("SELECT subject,text_message FROM $dsp_draft_table WHERE draft_id=$message->draft_id ");

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
                            $pagination.= "<div><a class='disabled'  href=\"" . $page_name . "page/$next\">".__('Next', 'wpdating')."</a></div>";
                        else
                            $pagination.= "<span class='disabled'>".__('Next', 'wpdating')."</span>";
                        $pagination.= "</div>\n";
                    }

                    /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
                    $exist_make_private = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id='$message->receiver_id'");

                   ?>
                    <div class="dsp_vertical_scrollbar">
                        <ul class="email-page dspdp-bg-info" style="margin:0px; padding:0px;">
                                <li style="width:5%; text-align:center;"><input type="checkbox" name="deldraft[]" id="deldraft[]" value="<?php echo $message->draft_id; ?>" /></li>
                                <li style="width:33%; margin-right:10px;">
                                    
                                <span class="name">
                                <!-- // new code for custom  -->
                                    <?php if(($message->receiver_id)==0) { ?>

                                        <?php  echo "[".__('Draft','wpdating')."]"; ?>

                                        <?php } else {?>
                                                 <a href="<?php echo $root_link . get_username($message->receiver_id) ; ?>">
                                                        <?php echo $display_sender_name->display_name; ?>
                                                    </a>
                                                <?php } ?>
                                 <!-- end of new code -->
                                    </span>
                                    <br /><?php echo $message_date ?> </li>

                                        <li  style="width:40%;"><?php echo str_replace('\\', '', $message_subject->subject); ?>
                                            
                                        </li>
                                        <li  style="width:20%;">
                                            <a href="<?php echo $root_link . "email/compose/dftid/" .$message->draft_id. "/receive_id/" . $message->receiver_id . "/Act/Send_Draft/"; ?>">
                                                <h4><u><?php echo __('edit', 'wpdating'); ?></u></h4></a>
                                        </li>

                                    

                                    
                        </ul> 
                    </div>

                    <?php
                } //for loop ends
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
            <div class="btn-delete">
                <input type="hidden" name="mode" id="mode" value="" />
                <input type="button" class="dsp_submit_button  dspdp-btn dspdp-btn-sm dspdp-btn-warning" name="deldraft" value="<?php echo __('Delete Selected', 'wpdating') ?>" onclick="delete_dsp_drafts();"/>
            </div>
        </form>
       <?php else: ?>
          <div class="heading-submenu"><strong><?php echo __('Empty', 'wpdating'); ?></strong></div>
       <?php endif; ?> 
    </div>
</div>