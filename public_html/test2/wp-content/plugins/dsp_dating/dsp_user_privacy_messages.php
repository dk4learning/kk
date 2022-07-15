<?php
$print_msg = $_GET['msg'];
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
?>
<div class="dsp_box-out">
    <div class="dsp_box-in">
        <div align="center">
            <?php
            if ($print_msg == 'profile') {
                echo __('You are not a friend of this member so you can&rsquo;t view this members profile.', 'wpdating');
            }
            ?>
        </div>
    </div>
</div>