<?php
$profile_questions = $wpdb->get_results( "SELECT * FROM {$dsp_profile_setup_table}  Order by sort_order" );
$user_profile_question_details =  $wpdb->get_results("SELECT * FROM {$dsp_question_details_table} WHERE user_id = '{$user_id}'");
$user_question_options = [];
if ( ! empty( $user_profile_question_details ) ) {
    foreach ( $user_profile_question_details  as $user_profile_question_detail) {
        if ( isset( $user_question_options[$user_profile_question_detail->profile_question_id] ) ){
            if ( is_array( $user_question_options[$user_profile_question_detail->profile_question_id]['option_id'] ) ){
                $user_question_options[$user_profile_question_detail->profile_question_id]['option_id'][] = $user_profile_question_detail->profile_question_option_id;
            } else {
                $existing_value = $user_question_options[$user_profile_question_detail->profile_question_id]['option_id'];
                $user_question_options[$user_profile_question_detail->profile_question_id]['option_id']  = [];
                $user_question_options[$user_profile_question_detail->profile_question_id]['option_id'] = [
                    $existing_value,
                    $user_profile_question_detail->profile_question_option_id
                ];
            }
        } else {
            $user_question_options[$user_profile_question_detail->profile_question_id]['option_id']     = $user_profile_question_detail->profile_question_option_id;
            $user_question_options[$user_profile_question_detail->profile_question_id]['option_value']  = trim( $user_profile_question_detail->option_value );
        }
    }
}
?>
<div class="wpee-edit-profile-form">
    <div class="heading-submenu">
        <strong><?php echo __('Profile Questions', 'wpdating'); ?></strong>
    </div>
    <div class="wpee-question">
        <?php
        foreach ( $profile_questions as $profile_question ) : ?>

            <div class="form-group">

            <?php
            switch ( $profile_question->field_type_id ) {
                case 1:
                    ?>
                    <label for="q_opt_ids<?php echo $profile_question->profile_setup_id ?>"><?php echo __( stripslashes( $profile_question->question_name ), 'wpdating' ); ?>:</label>
                    <?php if ( $profile_question->required == "Y" ): ?>
                    <input type="hidden" name="hidprofileqques" value="<?php echo stripslashes( $profile_question->question_name ); ?>"/>
                    <input type="hidden" name="hidprofileqquesid" value="<?php echo $profile_question->profile_setup_id; ?>"/>
                    <?php endif; ?>
                    <select class="dsp-form-control dspdp-form-control" name="option_id[<?php echo $profile_question->profile_setup_id; ?>]"
                            id="q_opt_ids<?php echo $profile_question->profile_setup_id; ?>">
                        <option value="0"><?php echo __( 'Select','wpdating' ); ?></option>
                        <?php
                        $question_options           = $wpdb->get_results( "SELECT * FROM {$dsp_question_options_table} WHERE question_id='{$profile_question->profile_setup_id}' Order by sort_order" );
                        $user_question_option_value = isset( $_POST["option_id"][$profile_question->profile_setup_id] )
                            ? $_POST["option_id"][$profile_question->profile_setup_id]
                            : ( isset( $user_question_options[$profile_question->profile_setup_id] )
                                ? $user_question_options[$profile_question->profile_setup_id]['option_id']
                                : 0 );
                        foreach ( $question_options as $question_option ):
                            ?>
                            <option value="<?php echo $question_option->question_option_id ?>"
                                <?php echo ($user_question_option_value == $question_option->question_option_id ) ? "selected='selected'" : ''; ?> >
                                <?php echo __( stripslashes( $question_option->option_value ), 'wpdating' ); ?>
                            </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                <?php
                    break;

                case 2:
                    $user_question_option_value = isset( $_POST["option_id1"][$profile_question->profile_setup_id] )
                                                    ? $_POST["option_id1"][$profile_question->profile_setup_id]
                                                    : (isset( $user_question_options[$profile_question->profile_setup_id] )
                                                        ? $user_question_options[$profile_question->profile_setup_id]['option_value']
                                                        : '');
                    ?>
                    <label for="text_option_id<?php echo $profile_question->profile_setup_id; ?>"><?php echo __( stripslashes( $profile_question->question_name ), 'wpdating' ); ?>:</label>
                    <?php if ( $profile_question->required == "Y" ) { ?>
                    <input type="hidden" name="hidetextqu_name" value="<?php echo stripslashes( $profile_question->question_name ); ?>"/>
                    <input type="hidden" name="hidtextprofileqquesid" id="hidtextprofileqquesid"
                           value="<?php echo $profile_question->profile_setup_id; ?>"/>
                    <?php } ?>
                    <textarea class="dsp-form-control dspdp-form-control" name="option_id1[<?php echo $profile_question->profile_setup_id; ?>]"
                              id="text_option_id<?php echo $profile_question->profile_setup_id; ?>"
                              maxlength="<?php echo $profile_question->max_length; ?>"
                              rows="6"><?php echo trim( $user_question_option_value ); ?></textarea>
                <?php
                    break;

                case 3:
                    ?>
                    <label for="q_opt_ids<?php echo $profile_question->profile_setup_id; ?>"><?php echo __( $profile_question->question_name, 'wpdating' ); ?>:</label>
                    <?php if ( $profile_question->required == "Y" ) { ?>
                    <input type="hidden" name="hidprofileqques" value="<?php echo $profile_question->question_name; ?>"/>
                    <input type="hidden" name="hidprofileqquesid" value="<?php echo $profile_question->profile_setup_id; ?>"/>
                    <?php } ?>
                    <select class="dsp-multiple-select dsp-form-control dspdp-form-control chosen chzn-done"
                            name="option_id2[<?php echo $profile_question->profile_setup_id; ?>][]" id="q_opt_ids<?php echo $profile_question->profile_setup_id; ?>"
                            multiple="true">
                        <?php
                        $question_options           = $wpdb->get_results( "SELECT * FROM {$dsp_question_options_table} Where question_id='{$profile_question->profile_setup_id}' Order by sort_order" );
                        $user_question_option_value = isset( $_POST["option_id2"][$profile_question->profile_setup_id] )
                            ? $_POST["option_id2"][$profile_question->profile_setup_id]
                            : ( isset( $user_question_options[$profile_question->profile_setup_id] )
                                ? $user_question_options[$profile_question->profile_setup_id]['option_id']
                                : 0 );
                        foreach ( $question_options as $question_option )  : ?>
                            <option value="<?php echo $question_option->question_option_id ?>"
                                <?php echo ( is_array( $user_question_option_value ) && in_array( $question_option->question_option_id, $user_question_option_value )
                                    || $question_option->question_option_id == $user_question_option_value )
                                    ? "selected='selected'"
                                    : ''; ?> >
                                <?php echo stripslashes( __($question_option->option_value, 'wpdating' ) ); ?>
                            </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                <?php
                    break;
            }
        ?>
                <?php if ( isset( $form_validator['errors'][$profile_question->question_name] ) ): ?>
                    <span class="error"><?php echo $form_validator['errors'][$profile_question->question_name]; ?></span>
                <?php endif; ?>
            </div>
        <?php
        endforeach;
        ?>

        <div class="form-group">
            <?php
            $about_me = ( isset( $_POST['about_me'] ) && !empty( $_POST['about_me'] ) ) ? $_POST['about_me'] : ( isset( $user_profile->about_me ) ? trim( $user_profile->about_me) : '' );
            ?>
            <label for="about_me"><?php echo __('About Me', 'wpdating') ?>:</label>
            <textarea id="about_me" name="about_me" class="form-control" rows="6"><?php echo str_replace('\\', '', $about_me); ?></textarea>
            <?php if ( isset( $form_validator['errors']['about_me'] ) ): ?>
                <span class="error"><?php echo $form_validator['errors']['about_me']; ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <?php
            $my_interest = ( isset( $_POST['my_interest'] ) && !empty( $_POST['my_interest'] ) ) ? $_POST['my_interest'] : ( isset( $user_profile->my_interest ) ? trim( $user_profile->my_interest) : '' );
            ?>
            <label for="my_interest"><?php echo __('My Interests', 'wpdating'); ?>:</label>
            <textarea id="my_interest" name="my_interest" rows="6" class="form-control"><?php echo stripslashes($user_profile->my_interest); ?></textarea>
        </div>

        <?php if ( $profile_subtab != 'partner' ): ?>
        <div class="form-group d-flex align-center">
            <input name="make_private_profile" type='checkbox' id="make_private_profile"
                   value="1" <?php echo ( isset( $user_profile ) && $user_profile->make_private_profile == 1) ? 'checked' : '' ?>/>&nbsp;
            <span><?php echo __('Make these profile questions private', 'wpdating'); ?> </span>
        </div>
        <?php endif; ?>
    </div>
</div>