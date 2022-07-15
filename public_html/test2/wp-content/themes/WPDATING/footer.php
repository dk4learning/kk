<?php

/*-------------------------------------------------
	BlankPress - Default Footer Template
 --------------------------------------------------*/
$bp_defaults = get_option(THEME_SLUG.'_options');

?>
    </div>
  </div>
</section>

<footer id="footer">

  <!-- <div class="container">

    <div class="row">

      <div class="col-md-8 footer-left">

        <div class="footer-menu">

          <ul>

            <li><a href="#">Disclamer</a></li>

            <li><a href="#">Terms and Conditions</a></li>

            <li><a href="#">Privacy</a></li>

            <li><a href="#">Faq</a></li>

          </ul>

        </div>

      </div>

      <div class="col-md-4 footer-right">

        <div class="copyright text-right">WordPress Dating Plugin</div>

      </div>

    </div>

  </div> -->

<div class="container">
  <div class="dsp-row">

    <div class="footer-text-widget">

      <div class="dsp-md-12 dsp-clear">

        <?php get_sidebar('footer')?>

      </div>



      <div class="clearfix"></div>

    </div>

</div>

<div class="footer-bottom">

<div class="row">

<div class="dsp-md-6 footer-menu">

    <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_class' => 'nav-menu' ) ); ?>

  </div>



  <div class="dsp-md-6">

    <div class="footer-social-icon">

      <!-- <i class="fa fa-facebook"></i>

      <i class="fa fa-twitter"></i>

      <i class="fa fa-google-plus"></i>

      <i class="fa fa-pinterest"></i> -->







  <?php $options = get_theme_mods();

  echo '<div id="social-icons" class="footer-social-icon">

  <ul>';

  if (!empty($options['facebook_uid'])) echo '<li><a title="Facebook" href="' . $options['facebook_uid'] . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';

  if (!empty($options['twitter_uid'])) echo '<li><a title="Twitter" href="' . $options['twitter_uid'] . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';

  if (!empty($options['google_uid'])) echo '<li><a title="Google+" href="' . $options['google_uid'] . '" target="_blank"><i id="google" class="fa fa-google-plus"></i></a></li>';

  if (!empty($options['pinterest_uid'])) echo '<li><a title="Pinterest" href="' . $options['pinterest_uid'] . '" target="_blank"><i id="pinterest" class="fa fa-pinterest"></i></a></li>';

  echo '</ul></div>';

?>

    </div>

  </div>



  <div class="dsp-md-12">

    <div class="footer-copyright">

      <p><?php echo get_theme_mod('footer_text').' ';?>
          <?php

          $Wpdating_Option_Config = Wpdating_Option_Config::getInstance();
          if ($Wpdating_Option_Config->getValue('powered_by') == 1) { ?>
              <span style="font-size: 13px" >
                  <?php echo '<a href="https://www.wpdating.com"> Dating Software Powered by WPDating</a>'; ?>
              </span>
          <?php } ?>

      </p>

    </div>

  </div>

</div>

</div>

</div>

</footer>

</div>

<?php
global $wpdb;
$dsp_general_settings_table = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
$use_po_file         = $wpdb->get_row( "SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'po_language'" );
if($use_po_file->setting_status == 'N' ) {
  $lang_id = isset($_SESSION['default_lang']) ?$_SESSION['default_lang']: null ;
  if(function_exists('dsp_display_language')){
    echo dsp_display_language($lang_id);
  }
}
?>
<?php
if (is_user_logged_in() && is_plugin_active('wpdating-instant-chat/wpdating-instant-chat.php')){
    do_action('wp_user_list_pop_up');

?>
<input type="hidden" id="user-id" value="<?php echo get_current_user_id();?>">
<?php } ?>
<?php wp_footer(); ?>



</body>

</html>