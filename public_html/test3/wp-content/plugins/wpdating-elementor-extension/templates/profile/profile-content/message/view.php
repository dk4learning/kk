<?php

if ( !isset( $_GET['thread'] ) ){
    header( 'location:' . $message_url );
}

$thread = $_GET['thread'];

if (isset($_GET['act']) && $_GET['act'] == 'read'){
    $wpdb->update( $dsp_user_emails_table, array(
            'message_read' => 'Y'
    ), array('thread_id' => $thread));
}

$sql_query = "SELECT COUNT(*)
                FROM {$dsp_user_emails_table} user_email
                JOIN {$wpdb->users} sender 
                ON user_email.sender_id = sender.ID
                JOIN {$wpdb->users} receiver
                ON user_email.sender_id = receiver.ID
                WHERE user_email.thread_id ='{$thread}'";

if ( isset( $_GET['from']) && $_GET['from'] = 'inbox'){
    $sql_query .= " AND user_email.delete_message = 0";
}
$sql_query .=" ORDER BY user_email.sent_date DESC, user_email.message_id DESC";

$total_count = $wpdb->get_var($sql_query);

$page =  isset( $_GET['page1'] ) ? $_GET['page1'] : 1;

$limit = 10;

$start = ($page - 1) * $limit;

$page_name  = "{$message_url}/view_message?thread={$thread}&";
$image_path = content_url('/');
?>
<div class="box-border">
    <div class="box-pedding">
        <div class="dsp_back_inbox dspdp-spacer">
            <a class="dspdp-btn dspdp-btn-default dspdp-btn-xs" href="<?php echo "{$message_url}/inbox/"; ?>">
                <?php echo __('Back to Inbox', 'wpdating'); ?>
            </a>
            <?php if ( isset( $_GET['from']) && $_GET['from'] = 'inbox'){ ?>
            <a class="dspdp-btn dspdp-btn-default dspdp-btn-xs" href="<?php echo "{$message_url}/reply?thread={$thread}"; ?>">
                <?php echo __('Reply', 'wpdating'); ?>
            </a>
            <?php } ?>
        </div>
        <?php
        $columns   = "sender.ID sender_id, sender.user_login sender_username, sender.display_name sender_name,
                        receiver.ID receiver_id, receiver.user_login receiver_username, receiver.display_name receiver_name,
                        user_email.sent_date created_date, user_email.text_message message";
        $sql_query = str_replace( 'COUNT(*)', $columns, $sql_query);
        $messages  = $wpdb->get_results( $sql_query . " LIMIT $start,$limit");

        foreach ($messages as $message) {
        ?>
            <div class="dspdp-bordered-item-md <?php echo ($message->sender_id == $current_user->ID) ? 'sender' : 'receiver';?>">
                <div class="view-message dspdp-row dsp-row">
                    <div class="dspdp-col-sm-2">
                        <a href="<?php echo trailingslashit(wpee_get_profile_url_by_id( $message->sender_id ) ); ?>" >
                            <img src="<?php echo display_members_photo($message->sender_id, $image_path); ?>" class="img dspdp-img-responsive" align="left" alt="<?php echo $message->sender_username;?>" />
                        </a>
                    </div>
                    <div class="dspdp-col-sm-9 dsp-sm-9">
                        <div class="msg-info">
                            <div>
                                <div class="name dspdp-text-info dspdp-h4 dspdp-reset dspdp-spacer-sm">
                                    <?php echo $message->sender_name; ?>
                                </div>
                                <div class="dsp-msg-date">
                                    <?php echo date("F j, Y g:i a", strtotime( $message->created_date ) ); ?>
                                </div>
                                <div class="dspdp-spacer dsp-spacer">
                                    <?php echo $message->message; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div style="float:left; width:100%;">
            <?php
                echo get_pagination( $page, $total_count, $limit, $page_name );
            ?>
        </div>
    </div>
</div>