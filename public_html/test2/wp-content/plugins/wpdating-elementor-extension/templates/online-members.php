<?php 
global $wpdb, $wpee_general_settings;
$current_user     = wp_get_current_user();
$imagepath        = content_url('/');  // image Path
$user_id          = $current_user->ID;  // print session USER_ID
$profile_page     = get_option( 'wpee_profile_page', '' );
$profile_page_url = trailingslashit(get_permalink( $profile_page ));
$gender           = (isset($_REQUEST['gender']) && $_REQUEST['gender'] !='All') ? $_REQUEST['gender'] : 'all';

$online_users = array();
$filters = array(
            'gender' => $gender,
            'start' =>0,
            'last' => 9,
        );
if ( $wpee_general_settings->random_online_members->status == 'Y' ) {
    $random_online_number = $wpee_general_settings->random_online_members->value;
    $online_users = dsp_randomOnlineMembers($random_online_number,$filters);
} else {
   $online_users = dsp_getOnlineMembers($filters);
}
?>
    <div class="wpee-online-members-inner wpee-block">
        <div class="wpee-block-header">
            <h4 class="wpee-block-title"><?php esc_html_e('Online Members', 'wpdating');?></h4>
        </div> 
        <div class="wpee-online-members-inner wpee-block-content <?php echo (count($online_users) < 1) ? 'no-online' : '';?>">
            <?php
            if (count($online_users) > 0) {?>

                <ul class="online-members-section"><!-- online members-->
                    <?php
                    foreach ($online_users as $online_row) {
                        $displayed_member_name = wpee_get_user_display_name_by_id($online_row->user_id);
                        $imagePath = $online_row->private == 'Y' ? WPDATE_URL . '/images/private-photo-pic.jpg'  : wpee_display_members_photo($online_row->user_id, $imagepath);
                        ?>
                        <li>
                            <a href="<?php
                                echo trailingslashit(wpee_get_profile_url_by_id( $online_row->user_id));
                            ?>">
                                <img src="<?php echo wpee_display_members_photo($online_row->user_id, $imagepath); ?>"
                                     title="<?php echo esc_attr($displayed_member_name); ?>" alt="<?php echo esc_attr($displayed_member_name); ?>" />
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            } else {
                echo '<b>' . __('No user is online', 'wpdating') . '</b>';
            }
            ?>
        </div>
    </div>