<?php
$dsp_messages_draft_table = $wpdb->prefix . DSP_MESSAGES_DRAFT_TABLE;
$dsp_user_profiles_table  = $wpdb->prefix . DSP_USER_PROFILES_TABLE;
if ( isset( $_POST['delete-draft'] ) ){
    if ( isset( $_POST['delete-draft-id'] ) && !empty( $_POST['delete-draft-id'] ) ){
        $draft_ids = implode("," ,$_POST['delete-draft-id']);
        $status = $wpdb->query("DELETE FROM {$dsp_messages_draft_table} WHERE draft_id IN($draft_ids)");
        if ( $status > 0){ ?>
            <script>
                toastr.success('<?php echo __('Draft message deleted successfully.', 'wpdating'); ?>');
            </script>
        <?php }
    }
}
$sql_query = "SELECT COUNT(*) FROM {$dsp_messages_draft_table} message_draft
                JOIN {$wpdb->users} receiver
                ON message_draft.receiver_id = receiver.ID
                LEFT JOIN {$dsp_user_profiles_table} receiver_profile
                ON message_draft.receiver_id = receiver_profile.user_id
                WHERE message_draft.sender_id = {$current_user->ID}";

$check_couples_mode = wpee_get_setting( 'couples' );

if ( $check_couples_mode->setting_status == 'Y' ) {
    $sql_query .= " AND receiver_profile.gender != 'C'";
}

$sql_query .= " ORDER BY save_date DESC, draft_id DESC";

$total_count = $wpdb->get_var( $sql_query );
 ?>

<div class="box-border">
    <div class="box-pedding">
       <?php if( $total_count > 0 ){?>
        <div class="heading-submenu"><strong><?php echo __('Draft Messages','wpdating'); ?></strong></div>
        <form name="frmdeldraft" action="" method="post" class="message-block-draft">
            <div class="gray-title-head">
                <div class="heading-top">
                    <strong><?php echo __('Sender', 'wpdating'); ?></strong>
                    <strong><?php echo __('Subject', 'wpdating') ?></strong>
                </div>
            </div>
                <?php
                $page =  isset( $_GET['page1'] ) ? $_GET['page1'] : 1;

                $limit = 10;

                $start = ($page - 1) * $limit;

                $page_name  = "{$message_url}/draft?";
                $image_path = content_url('/');

                $columns   = "message_draft.draft_id draft_id, message_draft.subject draft_subject, message_draft.text_message draft_message,
                            message_draft.save_date draft_save_date, message_draft.is_reply is_reply,
                            receiver.ID receiver_id, receiver.user_login receiver_username, receiver.display_name receiver_name";
                $sql_query = str_replace("COUNT(*)", $columns, $sql_query);

                $messages  = $wpdb->get_results( $sql_query . " LIMIT {$start}, {$limit}" );

                foreach( $messages as $message ) {
                   ?>
                    <div class="dsp_vertical_scrollbar">
                        <ul class="no-list email-page">
                            <li style="width:5%; text-align:center;">
                                <input type="checkbox" name="delete-draft-id[]" class="delete-message-id" value="<?php echo $message->draft_id; ?>" />
                            </li>
                            <li class="draft-detail-wrap">
                                <span class="name">
                                    <a href="<?php echo trailingslashit( wpee_get_profile_url_by_id( $message->receiver_id ) ); ?>">
                                        <?php echo $message->receiver_name; ?>
                                    </a>
                                </span>
                                <br />
                                <?php echo date("F j, Y g:i a", strtotime( $message->draft_save_date ) ) ?>
                            </li>
                            <li style="width:55%;" class="draft-subject-wrap">
                                <?php echo $message->draft_subject; ?>
                            </li>
                            <li class="edit-wrap">
                                <?php
                                if ( $message->is_reply ) {
                                    $edit_url = $message_url . "/reply?draft_id={$message->draft_id}&act=send_draft";
                                } else {
                                    $edit_url = $message_url . "/compose?draft_id={$message->draft_id}&act=send_draft";
                                }
                                ?>
                                <h6>
                                    <a href="<?php echo $edit_url; ?>">
                                        <?php echo __('Edit', 'wpdating'); ?>
                                    </a>
                                </h6>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            <div style="float:left; width:100%;">
                <?php
                    echo get_pagination( $page, $total_count, $limit, $page_name );
                ?>
            </div>
            <div class="btn-delete">
                <input type="submit" class="dsp_submit_button dspdp-btn dspdp-btn-sm dspdp-btn-warning" id="delete-message-button" name="delete-draft"
                       onclick="return confirm('<?php echo __('Are you sure you want to Delete this Message?', 'wpdating'); ?>')"
                       value="<?php echo __('Delete Selected', 'wpdating') ?>" disabled/>
            </div>
        </form>
       <?php } else { ?>
          <div>
              <strong><?php echo __('Empty', 'wpdating'); ?></strong>
          </div>
       <?php } ?>
    </div>
</div>