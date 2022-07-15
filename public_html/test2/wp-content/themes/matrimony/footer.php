<?php
/*
=================================================
Lavish Date Footer
=================================================
*/
?>
<?php get_sidebar('inset-bottom'); ?>
<?php get_sidebar('insetfull'); ?>
<?php get_sidebar('content-bottom'); ?>
<?php get_sidebar('bottom'); ?>


<div class="lavish_footer">
    <div class="container">
        <div class="row">
            <div class="dsp-md-8">
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'footer',
                    'fallback_cb'    => false,
                    'depth'          => 1
                )); ?>
                <div class="copyright" style="clear:both;">
                    <p>
                        <?php esc_attr_e('Copyright &copy; &nbsp;', 'lavish'); ?>
                        <strong><?php echo get_theme_mod('footer_copyright_text',
                                'Your Name'); ?></strong> <?php _e(date('Y')); ?>
                        . <?php esc_attr_e('All rights reserved.', 'lavish'); ?>
                        <?php
                        $Wpdating_Option_Config = Wpdating_Option_Config::getInstance();
                        if ($Wpdating_Option_Config->getValue('powered_by') == 1) {
                            echo '<a id="powered_by_color" href="https://www.wpdating.com">Dating Software Powered by WPDating</a>';
                        }
                        ?>
                    </p>
                </div>
            </div>

            <div class="dsp-md-4">
                <?php
                include('partials/social-bar.php');
                ?>
            </div>
            <?php
            global $wpdb;
            $dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
            $use_po_file                = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'po_language'");
            if ($use_po_file->setting_status == 'N') {
                $lang_id = isset($_SESSION['default_lang']) ? $_SESSION['default_lang'] : null;
                if (function_exists('dsp_display_language')) {
                    echo dsp_display_language($lang_id);
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
if (is_user_logged_in() && is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')){
    do_action('wp_user_list_pop_up');

    ?>
    <input type="hidden" id="user-id" value="<?php echo get_current_user_id();?>">
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>