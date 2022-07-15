<?php
global $wpdb;
$dsp_chat_one = $wpdb->prefix . "dsp_chat_one";
$dsp_chat_table = $wpdb->prefix . DSP_CHAT_TABLE;
$dsp_meet_me = $wpdb->prefix . DSP_MEET_ME_TABLE;
$dsp_notification = $wpdb->prefix . DSP_NOTIFICATION_TABLE;
if (isset($_REQUEST['chat_log'])) {
    $wpdb->query("Truncate $dsp_chat_table");
    echo __('Chat Logs Cleared.', 'wpdating');
}
if (isset($_REQUEST['chat_one_log'])) {
    $wpdb->query("Truncate $dsp_chat_one");
    echo __('One on One Chat Logs are Cleared.', 'wpdating');
}
if (isset($_REQUEST['meet_me_log'])) {
    $wpdb->query("Truncate $dsp_meet_me");
    echo __('Meet Me Logs Cleared.', 'wpdating');
}
if (isset($_REQUEST['notifications'])) {
    $wpdb->query("Truncate $dsp_notification");
    echo __('Notifications Cleared.', 'wpdating');
}
?>
<div id="general" class="postbox">
    <h3 class="hndle"><span><?php echo __('Logs', 'wpdating'); ?></span></h3>
    <br />
    <div class="dsp_thumbnails3" >
        <div >
            <form  method="post"  action="" >
                <div style="float:none;" >
                    <input name="chat_log" type="submit" value="<?php echo __('Clear Group Chat Log', 'wpdating') ?>" class="button">
                    <input name="chat_one_log" type="submit" value="<?php echo __('Clear One on One Chat Log', 'wpdating') ?>" class="button">
                    <input name="meet_me_log" type="submit" value="<?php echo __('Clear Meet Me Log', 'wpdating') ?>" class="button">
                    <input name="notifications" type="submit" value="<?php echo __('Clear Notifications', 'wpdating') ?>" class="button">
                </div>
            </form>
        </div>
        <div>
            <div style="height:40px;"></div>
        </div>
    </div>
    <br />
</div>
