<?php

$latitude  = isset($_GET['lat']) ? $_GET['lat'] : '';
$longitude = isset($_GET['lng']) ? $_GET['lng'] : '';
$saved = false;
if(isset($_GET['save']) && !empty($latitude) && !empty($longitude)){
    $position = array(
        'lat' => $latitude,
        'lng' => $longitude
    );
    $saved = apply_filters('dsp_savePosition',$position);
}

if(!empty($latitude) && !empty($longitude)) : ?>
 <form action="" method="GET" class="edit_my_location_form">
   <input type="hidden" value="<?php echo $latitude;?>" id="lat" name="lat">
   <input type="hidden" value="<?php echo $longitude;?>" id="lng" name="lng">
   <input name="submit" class="dsp_submit_button dspdp-btn dsp-save-button"type="submit" value="<?php echo __('Save my Location','wpdating'); ?>"  id="edit_my_location" />
   <input type="hidden" name="save" value="submit" />
</form>
   <div id="map_wrapper" style="height: 500px; width:500px; margin-left:-1px;" class="map_wrapper_form">
        <div id="map_canvas" class="mapping" style="width: 100%;height: 100%;"></div>
    </div>
 <?php
else : ?>
    <p> <?php echo __("lattitue & longitude not found", "wpdating"); ?> </p>
 <?php
endif; ?>
 </div>