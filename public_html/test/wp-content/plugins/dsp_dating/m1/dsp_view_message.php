<?php

$get_sender_id = isset($_REQUEST['sender_ID']) ? $_REQUEST['sender_ID'] : '';


$request_Action = isset($_REQUEST['Act']) ? $_REQUEST['Act'] : '';



if (($request_Action == "R") && ($get_sender_id != "")) {



    $wpdb->query("UPDATE $dsp_user_emails_table  SET message_read='Y' WHERE sender_id = '$get_sender_id'");
} // End if 

$root_link = "";
?>

<div role="banner" class="ui-header ui-bar-a" data-role="header">
    <?php include_once("page_back.php");?> 
    <h1 aria-level="1" role="heading" class="ui-title"><?php echo __('Messages', 'wpdating');?></h1>
    <?php include_once("page_home.php");?> 

</div>
<div class="ui-content" data-role="content">
    <div class="content-primary">
        <a href="dsp_inbox.html"><?php echo __('Back to Inbox', 'wpdating'); ?></a> 
        <ul data-divider-theme="e" data-theme="d" data-inset="true" data-role="listview" class="ui-listview ui-listview-inset ui-corner-all dsp_ul userlist">





            <?php
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++

            if (isset($_GET['page1']))
                $page = $_GET['page1'];
            else
                $page = 1;



// How many adjacent pages should be shown on each side?

            $adjacents = 2;

            $limit = 5;
//$limit = 1; 	

            if ($page)
                $start = ($page - 1) * $limit;    //first item to display on this page
            else
                $start = 0;

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++







            $bolIfSearchCriteria = true;

            $strQuery = "SELECT * FROM $dsp_user_emails_table where  (sender_id = $get_sender_id AND receiver_id=$user_id)";





            $intRecordsPerPage = 10;



            $intStartLimit = isset($_REQUEST['p']) ? $_REQUEST['p'] : ''; # page selected 1,2,3,4...



            if ((!$intStartLimit) || (is_numeric($intStartLimit) == false) || ($intStartLimit < 0)) {#|| ($pageNum > $totalPages)) 
                $intStartLimit = 1; //default
            }



            $intStartPage = ($intStartLimit - 1) * $intRecordsPerPage;



            if ($bolIfSearchCriteria) {



                $strQuery = $strQuery . " AND delete_message=0 ORDER BY thread_id desc";

                $user_count = $wpdb->get_var("SELECT COUNT(*) FROM ($strQuery) AS total");
            }



// ----------------------------------------------- Start Paging code------------------------------------------------------ //





            $page_name = $root_link . "?pid=14&pagetitle=my_email&message_template=view_message&sender_ID=" . $get_sender_id;   //+++++++++++++++++++++++++++++++++++++++++++++++++++++++



            $total_results1 = $user_count;

// Calculate total number of pages. Round up using ceil()







            $intTotalRecordsEffected = $user_count;



            if ($intTotalRecordsEffected != '0' && $intTotalRecordsEffected != '') {

                //print "Total records found: " . $intTotalRecordsEffected;
            }

            $strQuery . " LIMIT $start, $limit ";

            $my_messages = $wpdb->get_results($strQuery . " LIMIT $start, $limit ");    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++

            foreach ($my_messages as $message) {
                $display_sender_name = $wpdb->get_row("SELECT * FROM $dsp_user_table WHERE ID = '$message->sender_id'");

                $message_date = date("m/d/Y h:i", strtotime($message->sent_date));

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

                    $pagination .= "<div class='button-area'>";

                    //previous button

                    if ($page > 1) {
                        $pagination.="
			 
				<div onclick='viewRecMsg(1,\"view\")' class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                    } else {
                        $pagination.= "
				<div class='btn-pre1'>
					<img src='images/icons/prev-1.png' />
				</div>";
                    }

                    if ($page > 1) {
                        $pagination.="<div  onclick='viewRecMsg($prev,\"view\")' class='btn-pre2'>
							<img src='images/icons/prev-all.png' />
						</div>";
                    } else {
                        $pagination.=" <div  class='btn-pre2'>
							<img src='images/icons/prev-all.png' />
						</div>";
                    }


                    $pagination.= "<div class='main3'>
							<ul class='page_ul'> 
								<li > Page</li>
								<li >$page</li>
								<li >of $lastpage</li>
							</ul>
						</div>";

                    if ($page < $lastpage) {
                        $pagination.= "
			<div onclick='viewRecMsg($next,\"view\")' class='main4' >
				<img src='images/icons/next-all.png' />
			</div>";

                        $pagination.= "	<div onclick='viewRecMsg($lastpage,\"view\")' class='main5'>
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



                /* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
                ?>



                <li data-corners="false" data-shadow="false" class="ui-body ui-body-d ui-corner-all">
                    <div class="dsp_mail_lf"><?php
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



                                    <?php if (!in_array($user_id, $favt_mem)) { ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                        </a>                

                                    <?php } else {
                                        ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 				

                                            <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                        <?php
                                    }
                                } else {
                                    ?>



                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                    </a>

                                <?php } ?>



                            <?php } else { ?>



                                <?php if ($exist_make_private->make_private == 'Y') { ?>



                                    <?php if (!in_array($user_id, $favt_mem)) { ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                            <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/>

                                        </a>                

                                    <?php } else {
                                        ?>

                                        <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 				

                                            <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"    style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                        <?php
                                    }
                                } else {
                                    ?>

                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                    </a>

                                <?php } ?>



                                <?php
                            }
                        } else {
                            ?> 



                            <?php if ($exist_make_private->make_private == 'Y') { ?>



                                <?php if (!in_array($user_id, $favt_mem)) { ?>

                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                        <img src="<?php echo WPDATE_URL. '/images/private-photo-pic.jpg' ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" />

                                    </a>                

                                <?php } else {
                                    ?>

                                    <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 				

                                        <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>"   style="width:75px; height:75px;" class="dsp_img3" border="0" align="left"/></a>                

                                    <?php
                                }
                            } else {
                                ?>

                                <a onclick ="viewProfile('<?php echo $message->sender_id ?>', 'my_profile')"> 

                                    <img src="<?php echo display_members_photo($message->sender_id, $imagepath); ?>" style="width:75px; height:75px;" class="dsp_img3" border="0" align="left" /></a>

                            <?php } ?>



                        <?php } ?>

                    </div>

                    <div class="dsp_mail_rt"> 
                        <strong><?php echo $display_sender_name->display_name; ?></strong><br>
                        <span><?php echo $message->subject; ?></span><br>
                        <time><?php echo $message_date ?></time>
                    </div>

                    <div class="dsp_mail_full">
                        <?php echo nl2br($message->text_message) ?><br>
                        <?php if ($message->sender_id != $user_id) {
                            ?>
                            <a  class="button-edit spacer-bottom-xs" onclick="composeMessage('<?php echo $message->sender_id ?>', '<?php echo $message->message_id; ?>')" ><?php echo __('Reply', 'wpdating'); ?></a>
                        <?php } //end if    ?>
                    </div>
                </li>




                <?php
                unset($favt_mem);
            } // End for loop  
            ?>

            <?php /* Setup page vars for display. */
            ?>
        </ul>

        <div class="ds_pagination" > 
            <?php echo $pagination ?>
        </div>
    </div>
    <?php include_once('dspNotificationPopup.php'); // for notification pop up     ?>
</div>