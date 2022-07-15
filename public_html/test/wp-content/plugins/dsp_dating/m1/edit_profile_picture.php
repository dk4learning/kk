
<!--<div role="banner" class="ui-header ui-bar-a" data-role="header">
                    <div class="back-image">
                    <a href="#"  data-rel="back"><?php echo __('Back', 'wpdating'); ?></a>
                    </div>
                <h1 aria-level="1" role="heading" class="ui-title"><?php
echo __('Add Photo', 'wpdating');
;
?></h1>
                
</div>-->




<span style="padding-right:10px;float: left;">
    <a onclick="getPhoto();">
        <img src="<?php echo display_members_photo($user_id, $imagepath); ?>" style="width:100px; height:100px;" class="img" />
    </a>
</span>

<span>
    <div style="padding-bottom: 20px;">
        <input onclick="savePrivateStatus(this.value)" type="checkbox" value="Y" name="private"><?php echo __('Make Private', 'wpdating') ?>
    </div>
    <button onclick="getPhoto();"><?php echo __('Browse', 'wpdating') ?></button>	
</span>