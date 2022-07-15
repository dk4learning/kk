<?php
if( !empty($latitude) && !empty($longitude) ) {
    ?>
    <div class="wpee-edit-profile-inner profile-section-content">
        <form action="" method="GET" class="edit_my_location_form">
            <input type="hidden" value="<?php echo $latitude;?>" id="lat" name="lat">
            <input type="hidden" value="<?php echo $longitude;?>" id="lng" name="lng">
            <input name="submit" class="dsp_submit_button dspdp-btn dsp-save-button" type="submit" value="<?php echo __('Save my Location','wpdating'); ?>"  id="edit_my_location" />
            <input type="hidden" name="save" value="submit" />
        </form>
        <div id="map_wrapper" style="height: 500px; width:500px; margin-left:-1px;" class="map_wrapper_form">
            <div id="map_canvas" class="mapping" style="width: 100%;height: 100%;"></div>
        </div>
    </div>
    <?php
} else {
    esc_html_e( 'Latitude & Longitude not found', 'wpdating' );
}