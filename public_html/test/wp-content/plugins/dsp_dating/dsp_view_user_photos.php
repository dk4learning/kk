<?php
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  WordPress Dating Plugin
  contact@wpdating.com
 */
$check_user_privacy_settings = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_user_privacy_table WHERE view_my_pictures='Y' AND user_id='$member_id'");
if (($check_user_privacy_settings > 0) && ($user_id != $member_id)) {  // check user privacy settings
    $check_my_friends_list = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_my_friends_table WHERE friend_uid='$user_id' AND user_id='$member_id' AND approved_status='Y'");
    if ($check_my_friends_list <= 0) {   // check member is not in my friend list
        ?>

        <div class="box-border">
            <div class="box-pedding">
                <div align="center"><?php echo __('You are not a friend of this member so you can&rsquo;t view this members pictures.', 'wpdating'); ?></div>
            </div>
        </div>
    <?php } else {   // -----------------------------else Check member is in my friend list ---------------------------- // 
        ?>
<div class="box-border">
    <div class="box-pedding">
        <div class="box-page">
            <div id="links">
                <ul style="" class="albums dspdp-row  dsp-row">
                    <?php
                    $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos galleries WHERE galleries.status_id=1 AND galleries.user_id = '$member_id' AND galleries.album_id =0");
                       if($exists_photos){
                            $i = 0;
                            foreach ($exists_photos as $user_photos) {
                                $photo_id = $user_photos->gal_photo_id;
                                $status_id = $user_photos->status_id;
                                $private = $user_photos->private_album;
                                $file_name = $user_photos->image_name;
                                if ($private == 'Y') {

                                    $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
                                    foreach ($private_mem as $private) {
                                        $favt_mem[] = $private->favourite_user_id;
                                    }

                                    if ($current_user->ID == $member_id) {
                                        $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                        $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                    } else {
                                        if (!in_array($current_user->ID , $favt_mem)) {
                                            $image_path = WPDATE_URL . "/images/private-photo-pic.jpg";
                                         } else {
                                            $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . $album_id . "/" . $file_name;
                                            $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                        }
                                    }
                                } else {
                                    $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                }

                                if (($i % 4) == 0) {
                                    ?>
                                    <?php
                                }
                                ?>
                                <li class="img-box dspdp-col-sm-4 dspdp-col-xs-6  dsp-sm-3 dsp-xs-6">
                                    <div class="image-container dsp-img-fit"><a class="group1  dspdp-media-images-cont dsp-image-fill" href="<?php echo $image_path ?>"><img src="<?php echo $image_path ?>" style=" width:85px;height:85px;" class="img3"   alt="<?php echo $file_name;?>"/></a></div>
                                </li>
                                <?php
                                $i++;
                            }
                }else{
                    ?>
                    <p style="padding-left: 15px; text-align: center;">
                   <?php echo __('The user has not uploaded any photos.', 'wpdating');?>
                    </p>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
   <?php }
    }else{?>
        <div class="box-border">
        <div class="box-pedding">
            <div class="box-page">
               <!-- The Gallery as inline carousel, can be positioned anywhere on the page -->
            <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">???</a>
                <a class="next">???</a>
                <a class="close">??</a>
                <a class="play-pause"></a>
                <ol class="indicator"></ol>
            </div>
                <div id="links" class="links">
                    <ul style="" class="albums dspdp-row  dsp-row">
                        <?php
                        $exists_photos = $wpdb->get_results("SELECT * FROM $dsp_galleries_photos galleries WHERE galleries.status_id=1 AND galleries.user_id = '$member_id' AND galleries.album_id =0");
                           if($exists_photos){
                                $i = 0;
                                foreach ($exists_photos as $user_photos) {
                                    $photo_id = $user_photos->gal_photo_id;
                                    $status_id = $user_photos->status_id;
                                    $private = $user_photos->private_album;
                                    $file_name = $user_photos->image_name;
                                    if ($private == 'Y') {

                                        $private_mem = $wpdb->get_results("SELECT * FROM $dsp_user_favourites_table WHERE user_id='$member_id'");
                                        foreach ($private_mem as $private) {
                                            $favt_mem[] = $private->favourite_user_id;
                                        }

                                        if ($current_user->ID == $member_id) {
                                            $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                            $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                        } else {
                                            if (!in_array($current_user->ID , $favt_mem)) {
                                                $image_path = WPDATE_URL . "/images/private-photo-pic.jpg";
                                             } else {
                                                $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . $album_id . "/" . $file_name;
                                                $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                            }
                                        }
                                    } else {
                                        $image_path = $imagepath . "uploads/dsp_media/user_photos/user_" . $member_id . "/album_0" . "/" . $file_name;
                                    }

                                    if (($i % 4) == 0) {
                                        ?>
                                        <?php
                                    }
                                    ?>
                                    <li class="img-box dspdp-col-sm-4 dspdp-col-xs-6  dsp-sm-3 dsp-xs-6">
                                        <div class="image-container dsp-img-fit"><a class="group1  dspdp-media-images-cont dsp-image-fill" href="<?php echo $image_path ?>"><img src="<?php echo $image_path ?>" style=" width:85px;height:85px;" class="img3"   alt="<?php echo $file_name;?>"/></a></div>
                                    </li>
                                    <?php
                                    $i++;
                                }
                    }else{ ?>
                    <p style="padding-left: 15px; text-align: center;">
                    <?php echo __('The user has not uploaded any photos.', 'wpdating');?>
                    </p>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
            <script>
              document.getElementById('links').onclick = function(event) {
                event = event || window.event
                var target = event.target || event.srcElement,
                  link = target.src ? target.parentNode : target,
                  options = { index: link, event: event },
                  links = this.getElementsByTagName('a')
                blueimp.Gallery(links, options)
              }
            </script>
    </div>

 <?php } ?>