<ul class="profile-section-tab">
    <li class="profile-section-tab-title <?php echo ( empty($profile_subtab) ) ? 'active' : '';?>">
        <a href="<?php echo esc_url(trailingslashit($profile_link).'edit-profile/');?>">
            <?php esc_html_e( 'Edit your profile', 'wpdating' );?>
        </a>
    </li>

    <li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'location' ) ? 'active' : '';?>">
        <a id="wpee_edit_location" href="javascript:void(0);" data-siteurl="<?php echo esc_url(trailingslashit($profile_link).'edit-profile/location');?>">
            <?php esc_html_e( 'Edit Location', 'wpdating' );?>
        </a>
    </li>

    <?php if ( $main_user_gender == 'C' ):?>
    <li class="profile-section-tab-title <?php echo ( !empty( $profile_subtab ) && $profile_subtab == 'partner' ) ? 'active' : '';?>">
        <a href="<?php echo esc_url(trailingslashit($profile_link).'edit-profile/partner/');?>">
            <?php esc_html_e( 'Edit your partner profile', 'wpdating' );?>
        </a>
    </li>
    <?php endif; ?>
</ul>