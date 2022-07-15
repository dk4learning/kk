<?php
$dsp_comments_table = $wpdb->prefix . DSP_USER_COMMENTS;

$comments_id = $_REQUEST['comments_id'];

$comment_list = $wpdb->get_var("SELECT comments FROM `$dsp_comments_table` where comments_id='$comments_id' ");
?>
<div><?php echo $comment_list; ?></div>
<span> <br />
    <a  onclick="updateComment('<?php echo $comments_id ?>', 'approve', '<?php echo __('Are you sure to delete it?', 'wpdating') ?>')"><?php echo __('Approve', 'wpdating'); ?></a>&nbsp;|&nbsp;<a  onclick="updateComment('<?php echo $comments_id ?>', 'Del', '<?php echo __('Are you sure to delete it?', 'wpdating') ?>')"><?php echo __('Delete', 'wpdating'); ?> </a>
</span>