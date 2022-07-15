<?php
   /* 
   * THIS FILE WILL BE UPDATED WITH EVERY UPDATE
   * IF YOU WANT TO MODIFY THIS FILE, CREATE A CHILD THEME
   *
   * http://codex.wordpress.org/Child_Themes
   */
   ?>
   	<?php //---------------------------------START  GENERAL SEARCH---------------------------------------            ?>
<script type="text/javascript" src="https://googlemaps.github.io/js-rich-marker/src/richmarker-compiled.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/js-marker-clusterer/1.0.0/markerclusterer_compiled.js"></script>

<?php
    global $wpdb;

    //For min and max age
    $check_min_age = wpee_get_setting('min_age');
    $min_age_value = $check_min_age->setting_value;
    $check_max_age = wpee_get_setting('max_age');
    $max_age_value = $check_max_age->setting_value;
    $check_near_me = wpee_get_setting('near_me');

    $age_from = $min_age_value;
    $age_to   = $max_age_value;
    $gender   = 'all';
    if (isset($_REQUEST['near_me_submit'])) {
        $gender   = isset($_REQUEST['gender']) ? esc_sql($_REQUEST['gender']) : get('gender');
        $age_from = isset($_REQUEST['age_from']) ? esc_sql($_REQUEST['age_from']) : get('age_from');
        $age_to   = isset($_REQUEST['age_to']) ? esc_sql($_REQUEST['age_to']) : get('age_to');
    } elseif ( is_user_logged_in() ) {
        $current_user_profile = wpee_get_user_profile_by( array( 'user_id' => get_current_user_id() ) );
        if( $current_user_profile ) {
            $gender  = $current_user_profile->seeking;
        }
    }

    $filters = array(
                    'gender'   => $gender,
                    'age_from' => $age_from,
                    'age_to'   => $age_to
                );
    $usermeta_table = dsp_get_near_users($filters);

    if (isset($usermeta_table) && !empty($usermeta_table)) {
    ?>
        <div class="heading-submenu"><strong><?php echo __('Near Me', 'wpdating'); ?></strong>
            <?php 
            if ( is_wp_error( $usermeta_table )){ ?>
                <div class='near-me-message'><?php echo  __( 'You need to set your current location.', 'wpdating' ); ?>
                <a href='<?php echo wpee_get_profile_url_by_id( get_current_user_id() ) . "/edit-profile/location"; ?>'>Click Here</a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="content-search">
            <form action="" method="GET" class="dspdp-form-horizontal dsp-form-horizontal">
                <div class="zip-search form-inline align-bottom">
                    <div class="form-group">
                        <label>
                            <?php echo __('Gender:', 'wpdating') ?>
                        </label>
                        <select name="gender" class="dspdp-form-control dsp-form-control">
                            <option value="all" <?php if ($gender == 'all') { ?> selected="selected" <?php } else { ?> selected="selected"<?php } ?> >All</option>
                            <?php
                                echo get_gender_list($gender);
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <?php echo __('Age:', 'wpdating') ?>
                        </label>
                        <select name="age_from" class="dspdp-form-control dsp-form-control">
                            <?php for ($fromyear = $max_age_value; $fromyear >= $min_age_value; $fromyear--) : ?>
                                <option value="<?php echo $fromyear ?>" <?php echo ($fromyear == $min_age_value) ? 'selected="selected"' : ''; ?>><?php echo $fromyear ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <?php echo __('to:', 'wpdating') ?>
                        </label>
                        <select  name="age_to" class="dspdp-form-control dsp-form-control">
                            <?php for ($fromyear = $max_age_value; $fromyear >= $min_age_value; $fromyear--) : ?>
                                <option value="<?php echo $fromyear ?>" <?php echo ($fromyear == $max_age_value) ? 'selected="selected"' : ''; ?>><?php echo $fromyear ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="near_me_submit" class="dsp_submit_button dspdp-btn dspdp-btn-default" value="<?php echo __('Filter', 'wpdating'); ?>" />
                    </div>
                </div>
            </form>
        </div>
        <div style="clear:both;"></div>
        <?php if(!empty($usermeta_table)){ ?>
        <div id="map_wrapper" style="height: 500px; width:500; margin-left:-1px;">
            <div id="map_canvas" class="mapping" style="width: 100%;height: 100%;"></div>
        </div>
        <?php
            $lt_lg = array();
            foreach ($usermeta_table as $usermeta) {
                $userid    = $usermeta->user_id;
                $private = $usermeta->make_private;
                $lt_lg[] = array("lat" => $usermeta->lat, "long" => $usermeta->lng, "userid" => $userid, 'privatepic' => $private);

            }
            $map_str = '';
            $count = 0;
            foreach ($lt_lg as $ltr) {
                $count+=1;

                if ($ltr['privatepic'] == 'Y') {
                        $image_path = WPDATE_URL . '/images/private-photo-pic.jpg';
                } else {
                    $image_path = display_members_original_photo($ltr['userid'], ABSPATH . 'wp-content/');
                }

                $map_str.="['" . get_username($ltr['userid']) . "'," . $ltr['lat'] . "," . ($ltr['long'] + ($count/10000)) . ",0,'" . WPDATE_URL .  "/thumb.php" . "?src=" . base64_encode($image_path) . "&w=32&h=32" . "','" . $root_link . get_username($ltr['userid']) . "/" . "'],";
            }
            $map_str = rtrim($map_str, ','); /////////Longitude and Langitude
        }

        } else {

            echo "<div class='near-me-message'>" . __('Sorry, we could not find anyone near you. Start a new search.', 'wpdating') . "</div>";
        }


    ?>

<script type="text/javascript">
        
    function initialize() {
        var locations = [<?php echo $map_str;?>];
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var marker, i;

        for (i = 0; i < locations.length; i++) {
            var image = {
                url: locations[i][4],
                size: new google.maps.Size(32, 32)
            };

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                icon: image,
                map: map,
                animation: google.maps.Animation.DROP
            });
            bounds.extend(marker.position);
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent('<a href="' + locations[i][5] + '">' + locations[i][0] + '</a>');
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }

        map.fitBounds(bounds);
        var listener = google.maps.event.addListener(map, "idle", function() {
            map.setZoom(17);
            google.maps.event.removeListener(listener);
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>