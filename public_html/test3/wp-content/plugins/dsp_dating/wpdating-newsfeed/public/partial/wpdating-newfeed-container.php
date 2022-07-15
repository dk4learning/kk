<?php
    $pId = get('pid');
?>

<div class="box-border dsp-news-feed-container">
    <div class="box-pedding clearfix">
        <div class="dsp-row">
            <?php if($pId != '3'): ?>
                <div class="dsp-md-3">
                
                    <div class="user-friends dsp-user-friends">
                        <h3 class="heading-feed"><?php echo __('Friends', 'wpdating'); ?></h3>
                        <ul class="friends-list dspdp-nav dspdp-nav-tabs">
                            <li class="all"><a id="update_new_news_feed_box" onclick="update_news_feed('All')"><?php echo __('All', 'wpdating'); ?></a></li>
                            <?php
                            $feed_users = $wpdb->get_results("(SELECT friend_uid as userid FROM `$dsp_my_friends_table` WHERE user_id='$user_id') union (SELECT favourite_user_id as userid FROM `$dsp_user_favourites_table` WHERE user_id='$user_id')");
                            foreach ($feed_users as $users) {
                                $username = $wpdb->get_var("SELECT display_name FROM $dsp_user_table WHERE ID = '$users->userid'");
                                ?>
                                <li><a id="update_new_news_feed_box"  onclick="update_news_feed(<?php echo $users->userid; ?>)"><?php echo $username; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="box-profile-link dsp-block" style="display:none">
                        <div class="clr"></div>
                        <ul class="text-left dsp-user-spec clearfix ">

                            <?php if ($check_flirt_module->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "view_winks")) { ?>class="dsp_active_link" <?php } ?>>
                                    <?php if ($count_wink_messages > 0) { ?>
                                        <a href="<?php echo $root_link . "home/view_winks/Act/R/"; ?>"><i class="fa fa-meh-o"></i><?php echo __('Winks', 'wpdating') ?>&nbsp;(<?php echo $count_wink_messages ?>)</a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/view_winks/"; ?>"><i class="fa fa-meh-o"></i><?php echo __('Winks', 'wpdating'); ?></a>
                                    <?php } ?>
                                </li>
                            <?php } ?>

                            <?php if ($check_my_friend_module->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "view_friends")) { ?>class="dsp_active_link"  <?php } ?>>
                                    <a href="<?php echo $root_link . "home/view_friends/"; ?>"><i class="fa fa-users"></i><?php echo __('Friends', 'wpdating'); ?></a>
                                </li>
                            <?php } ?>


                            <li <?php if (($profile_pageurl == "my_favorites")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/my_favorites/"; ?>"><i class="fa fa-heart"></i><?php echo __('Favorites', 'wpdating'); ?></a>
                            </li>


                            <?php if ($check_virtual_gifts_mode->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "virtual_gifts")) { ?>class="dsp_active_link" <?php } ?>>
                                    <?php if ($count_friends_virtual_gifts > 0) { ?>
                                        <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?>&nbsp;(<?php echo $count_friends_virtual_gifts ?>) </a>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/virtual_gifts/"; ?>"><i class="fa fa-gift"></i><?php echo __('Gifts', 'wpdating'); ?> </a>
                                    <?php } ?>
                                </li>
                            <?php } ?>

                            <li <?php if (($profile_pageurl == "my_matches")) { ?>class="dsp_active_link" <?php } ?>>
                                <a href="<?php echo $root_link . "home/my_matches/"; ?>"><i class="fa fa-star"></i><?php echo __('Matches', 'wpdating'); ?></a>
                            </li>

                            <?php if ($check_match_alert_mode->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "match_alert")) { ?>class="dsp_active_link"  <?php } ?>>
                                    <a href="<?php echo $root_link . "home/match_alert/"; ?>"><i
                                            class="fa fa-bell"></i><?php echo __('Match alerts', 'wpdating'); ?>
                                    </a>
                                </li>
                            <?php } ?>

                            <li <?php if ($profile_pageurl == "alerts") { ?>class="dsp_active_link" <?php } ?>>
                                <?php if ($count_friends_request > 0) { ?>
                                    <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo __('Alerts', 'wpdating'); ?>&nbsp;(<?php echo $count_friends_request ?>) </a>
                                <?php } else { ?>
                                    <a href="<?php echo $root_link . "home/alerts/"; ?>"><i class="fa fa-bell"></i><?php echo __('Alerts', 'wpdating'); ?></a>
                                <?php } ?>
                            </li>

                            <?php if ($check_comments_mode->setting_status == 'Y') { ?>
                                <li <?php if (($profile_pageurl == "comments")) { ?>class="dsp_active_link" <?php } ?>>

                                    <?php if ($check_approve_comments_status->setting_status == 'Y') { ?>
                                        <?php if ($count_friends_comments > 0) { ?>
                                            <a href="<?php echo $root_link . "home/comments/"; ?>" style="color:#FF0000;">
                                                <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>&nbsp;(<?php echo $count_friends_comments ?>)
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?php echo $root_link . "home/comments/"; ?>">
                                                <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <a href="<?php echo $root_link . "home/comments/"; ?>">
                                            <i class="fa fa-comments-o"></i><?php echo __('Comments', 'wpdating'); ?>
                                        </a>
                                    <?php } ?>
                                </li>
                            <?php } ?>

                        </ul>

                    </div>
                </div>
            <?php endif; ?>
            <div class="dsp-md-9">
                <div class="news-box">
                    <?php if($pId != '3'): ?>
                        <h3 class="heading-feed margin-btm-2"></h3>
                    <?php endif; ?>
                    <div id="news_feed_box">
                        <ul class='news-feed-page' id="news-feed-page">
                        </ul>
                    </div>
                    <input type="hidden" id="dsp_feed_page" name="page" value="1">
                    <input type="hidden" id="user" name="user" value="All">
                </div>
            </div>
        </div>
    </div>
</div>
<?php


