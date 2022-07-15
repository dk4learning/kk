<?php
$dsp_user_virtual_gifts = $wpdb->prefix . DSP_USER_VIRTUAL_GIFT_TABLE;

$gift_id = isset($_REQUEST['gift_Id']) ? $_REQUEST['gift_Id'] : '';



$gifts = $wpdb->get_row("SELECT * FROM `$dsp_user_virtual_gifts` where gift_id=$gift_id");
?>
<div class="show-comment">
	<img style="margin-left: 20px;width:67px; height:67px;" src="<?php echo get_bloginfo('url') . "/wp-content/uploads/dsp_media/gifts/" . $gifts->gift_image; ?>" />
	 <div class="row-btn-traker">

		<a class="reply-button spacer-bottom-sm" href="javascript:void();" onclick="updateGift('<?php echo $gifts->gift_id ?>', 'approve', '<?php echo __('Are you sure to delete it?', 'wpdating') ?>')"><?php echo __('Approve', 'wpdating'); ?></a> 
		<a class="delete-button spacer-bottom-sm" href="javascript:void();" onclick="updateGift('<?php echo $gifts->gift_id ?>', 'Del', '<?php echo __('Are you sure to delete it?', 'wpdating') ?>')"><?php echo __('Delete', 'wpdating'); ?> </a>
		</div>
</div>

