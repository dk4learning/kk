<?php

class Wpdating_Gallery
{
    private $album_id;

    public function __construct()
    {
        $this->load_dependencies();
        $this->define_public_hooks();
    }

    public function load_dependencies()
    {
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wpdating-gallery-public.php';
    }

    public function define_public_hooks()
    {
        $plugin_public = new Wpdating_Gallery_Public();
        add_action('wp_gallery_enqueue_styles', array(&$plugin_public, 'enqueue_styles'));
        add_action('wp_gallery_enqueue_scripts', array(&$plugin_public, 'enqueue_scripts'));

        add_action('wpdating_gallery', array($this, 'gallery_task'), 10, 1);
        add_action('wp_ajax_change_album', array($this, 'change_album'));

        add_action('wp_profile_picture_change', array($this, 'profile_picture_change'));
        add_action( 'wp_ajax_upload_profile_pic', array($this,'dsp_upload_profile_pic') );
    }

    public function gallery_task($album_id)
    {
        do_action('wp_gallery_enqueue_styles');
        global $wpdb;
        $this->album_id = $album_id;
        ?>
        <!-- CSS adjustments for browsers with JavaScript disabled -->
        <noscript>
            <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload-noscript.css"/>
        </noscript>
        <noscript>
            <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload-ui-noscript.css"/>
        </noscript>
        <!-- CSS adjustments for browsers with JavaScript disabled -->
        <form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
            <!-- Redirect browsers with JavaScript disabled to the origin page -->
            <noscript>
                <input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"/>
            </noscript>
            <input name="wpdating-gallery-album-id" type="hidden" value="<?php echo $this->album_id ?>">
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="col-lg-7">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="glyphicon glyphicon-plus"></i>
                        <span><?php echo __('Add Photo', 'wpdating') ?></span>
                        <input type="file" name="files[]" multiple />
                    </span>
                    <!-- End Add Photo buttons-->
                    <!-- Begin start upload buttons to start uploading files -->
                    <button type="submit" class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span><?php echo __('Start upload','wpdating'); ?></span>
                    </button>
                    <!-- End start upload buttons to cancel uploading files -->
                    <!-- Begin cancel upload buttons to start uploading files -->
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel upload</span>
                    </button>
                    <!-- End cancel upload buttons to cancel uploading files -->
                    <!-- Begin delete buttons -->
<!--                    <button type="button" class="btn btn-danger delete">-->
<!--                        <i class="glyphicon glyphicon-trash"></i>-->
<!--                        <span>Delete selected</span>-->
<!--                    </button>-->
                    <!-- End delete upload buttons -->
<!--                    <input type="checkbox" class="toggle" />-->
                    <!-- The global file processing state -->
                    <span class="fileupload-process"></span>
                    <!-- The global file processing state -->
                </div>
                <!-- The global progress state -->
                <div class="col-lg-5 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div
                            class="progress progress-striped active"
                            role="progressbar"
                            aria-valuemin="0"
                            aria-valuemax="100"
                    >
                        <div class="progress-bar progress-bar-success"
                                style="width: 0%;"
                        ></div>
                    </div>
                    <!-- The extended global progress state -->
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped">
                <tbody class="files"></tbody>
            </table>
        </form>
        <!-- The file upload form used as target for the file upload widget -->
        <br>

        <!-- The blueimp Gallery widget -->
        <div
                id="blueimp-gallery"
                class="blueimp-gallery blueimp-gallery-controls"
                aria-label="image gallery"
                aria-modal="true"
                role="dialog"
                data-filter=":even"
        >
            <div class="slides" aria-live="polite"></div>
            <h3 class="title"></h3>
            <a
                    class="prev"
                    aria-controls="blueimp-gallery"
                    aria-label="previous slide"
                    aria-keyshortcuts="ArrowLeft"
            ></a>
            <a
                    class="next"
                    aria-controls="blueimp-gallery"
                    aria-label="next slide"
                    aria-keyshortcuts="ArrowRight"
            ></a>
            <a
                    class="close"
                    aria-controls="blueimp-gallery"
                    aria-label="close"
                    aria-keyshortcuts="Escape"
            ></a>
            <a
                    class="play-pause"
                    aria-controls="blueimp-gallery"
                    aria-label="play slideshow"
                    aria-keyshortcuts="Space"
                    aria-pressed="false"
                    role="button"
            ></a>
            <ol class="indicator"></ol>
        </div>
        <?php  do_action('wp_gallery_enqueue_scripts'); ?>
        <!-- The template to display files available for upload -->
        <script id="template-upload" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
              <td>
                  <span class="preview"></span>
              </td>
              <td>
                  <p class="name">{%=file.name%}</p>
                  <strong class="error text-danger"></strong>
              </td>
              <td>
                  <p class="size">Processing...</p>
                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
              </td>
              <td>
                  {% if (!o.options.autoUpload && o.options.edit && o.options.loadImageFileTypes.test(file.type)) { %}
                    <button class="btn btn-success edit" data-index="{%=i%}" disabled>
                        <i class="glyphicon glyphicon-edit"></i>
                        <span><?php echo __('Edit','wpdating'); ?></span>
                    </button>
                  {% } %}
                  {% if (!i && !o.options.autoUpload) { %}
                      <button class="btn btn-primary start" disabled>
                          <i class="glyphicon glyphicon-upload"></i>
                          <span><?php echo __('Start','wpdating'); ?></span>
                      </button>
                  {% } %}
                  {% if (!i) { %}
                      <button class="btn btn-warning cancel">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                          <span><?php echo __('Cancel', 'wpdating'); ?></span>
                      </button>
                  {% } %}
              </td>
          </tr>
        {% } %}

        </script>
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl">
      {% for (var i=0, file; file=o.files[i]; i++) { %}
          <tr class="template-download fade{%=file.thumbnailUrl?' image':''%}">
              <td>
                  <span class="preview">
                      {% if (file.thumbnailUrl) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                      {% } %}
                  </span>
              </td>
              <td>
                  <p class="name">
                      {% if (file.url) { %}
                          <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                      {% } else { %}
                          <span>{%=file.name%}</span>
                      {% } %}
                  </p>
                  <p class="name">
                      {% if (file.message) { %}
                      <div class=" has-success">
                        <label class="control-label for="inputSuccess3">
                            {%=file.message%}
                        </label>
                      </div>
                      {% } %}
                  </p>
                  {% if (file.error) { %}
                      <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                  {% } %}
              </td>
              <td>
                  <span class="size">{%=o.formatFileSize(file.size)%}</span>
              </td>
              <td>
                  {% if (file.deleteUrl) { %}
                      <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                          <i class="glyphicon glyphicon-trash"></i>
                          <span>Delete</span>
                      </button>
<!--                      <input type="checkbox" name="delete" value="1" class="toggle">-->
                  {% } else { %}
                      <button class="btn btn-warning cancel">
                          <i class="glyphicon glyphicon-ban-circle"></i>
                          <span>Cancel</span>
                      </button>
                  {% } %}
              </td>
          </tr>
      {% } %}
    </script>
        <?php
    }

    /**
     * Uploads the profile picture of the current user
     *
     * @since 4.7.0
     *
     */
    function profile_picture_change()
    {
        do_action('wp_gallery_enqueue_styles');
        ?>
        <input type="file" name="update_profile_pic" id="update_profile_pic" style="display: none" accept="image/jpg,image/jpeg,image/gif,image/png">
        <div class="update_profile_text_div" onclick="jQuery('#update_profile_pic').click();" title="Update Profile Picture">
            <a id="change-profile-pic" class="">
                <i class="fa fa-camera" aria-hidden="true"></i>
            </a>
        </div>
        <!-- The Templates plugin is included to render the upload/download listings -->
        <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
        <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
        <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
        <!-- The Canvas to Blob plugin is included for image resizing functionality -->
        <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
        <script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>

        <script type="module">
            // import Pintura Image Editor functionality:
            import {openDefaultEditor} from "<?php echo WPDATING_GALLERY_URL; ?>lib/js/pintura.min.js";

            // Listen for changes on a file input element
            const input = document.querySelector('.change-profile-image input[type="file"]');
            input.addEventListener('change', e => {
                const editor = openDefaultEditor({
                    src: input.files[0]
                });
                editor.on('process', ({ dest }) => {
                    let ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
                    let form_data = new FormData();
                    form_data.append('file', dest);
                    form_data.append('action', 'upload_profile_pic')
                    jQuery.ajax({
                        url: ajax_url,
                        type: 'POST',
                        contentType: false,
                        processData: false,
                        data: form_data,
                        beforeSend: function() {
                            jQuery(".change-profile-image .loader").show();
                        },
                        success: function (response) {
                            response = JSON.parse(response);
                            if (response.success) {
                                toastr.success(response.success);
                                jQuery('.change-profile-image img').attr('src', response.image_path);
                            }else{
                                toastr.error(response.error);
                            }
                        },
                        failure : function () {
                            toastr.error('<?php echo __("Something went wrong", "wpdating");?>');
                        }
                    }).always(function () {
                        jQuery(".change-profile-image .loader").hide();
                        input.value = '';
                    });
                });
                editor.on('close', () => {
                    input.value = '';
                });
                return false;
            });
        </script>
<?php
    }

    /**
     * Uploads the profile picture of the current user
     *
     *
     * @since 4.7.0
     *
     */
    function dsp_upload_profile_pic()
    {
        $response = [];

        $user_id = get_current_user_id();
        if (!file_exists(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id)) {

            if (!file_exists(ABSPATH . '/wp-content/uploads')) {
                mkdir(ABSPATH . '/wp-content/uploads', 0777);
            }
            if (!file_exists('wp-content/uploads/dsp_media')) {
                mkdir(ABSPATH . '/wp-content/uploads/dsp_media', 0777);
            }
            if (!file_exists('wp-content/uploads/dsp_media/user_photos')) {
                mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos', 0777);
            }

            // it will default to 0755 regardless
            $status = mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0755);
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);
            // Finally, chmod it to 777
            chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id, 0777);
            chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
            chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
        } else if (!file_exists('wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs')) {
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0755);
            mkdir(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0755);

            chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs', 0777);
            chmod(ABSPATH . '/wp-content/uploads/dsp_media/user_photos/user_' . $user_id . '/thumbs1', 0777);
        }

        global $wpdb;
        $dsp_members_photos = $wpdb->prefix . DSP_MEMBERS_PHOTOS_TABLE;
        $my_img = $wpdb->get_row("select * from $dsp_members_photos where user_id=$user_id",
            ARRAY_A);

        if (!is_null($my_img)){
            $old_img = $my_img['picture'];
            $del_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $old_img;
            $del_thumb_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $old_img;
            $del_thumb1_img_path = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $old_img;

            unlink($del_img_path);
            unlink($del_thumb_img_path);
            unlink($del_thumb1_img_path);
        }


        define("MAX_SIZE", "100000");
        define("WIDTH", "150");
        define("HEIGHT", "150");
        define("width", "100");
        define("height", "100");

        $extension = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

        if ( !in_array($_FILES['file']['type'], $extension) ) {
            $response['error'] = 'Unknown extension!';
        } else {
            switch ($_FILES['file']['type']) {
                case 'image/jpg':
                    $new_name = 'image_' . time() . '.jpg';
                    break;
                case 'image/jpeg':
                    $new_name = 'image_' . time() . '.jpeg';
                    break;
                case 'image/png':
                    $new_name = 'image_' . time() . '.png';
                    break;
                case 'image/gif':
                    $new_name = 'image_' . time() . '.gif';
                    break;
            }

            $newname = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/" . $new_name;
            $copied = copy($_FILES['file']['tmp_name'], $newname);
            if (!$copied) {
                $response['error'] = __('Something went wrong!', 'wpdating');
            } else {
                $thumb_name1 = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs1/thumb_" . $new_name;
                $this->square_image_crop($newname, $thumb_name1, 250);

                $thumb_name = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/thumbs/thumb_" . $new_name;
                $this->square_image_crop($newname, $thumb_name, 350);

                $dsp_general_settings_table  = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
                $check_approve_photos_status = $wpdb->get_row("SELECT * FROM $dsp_general_settings_table WHERE setting_name = 'authorize_photos'");
                if ($check_approve_photos_status->setting_status == 'Y') {  // if photo approve status is Y then photos Automatically Approved.
                    if (!is_null($my_img)){
                        $wpdb->query("UPDATE $dsp_members_photos SET picture = '$new_name',status_id=1 WHERE user_id  = '$user_id'");
                    } else {
                        $wpdb->query("INSERT INTO $dsp_members_photos SET picture = '$new_name',status_id=1,user_id='$user_id'");
                    } //  if($count_rows>0)
                    $response['success']    = __('Profile Picture updated successfully.', 'wpdating');
                    $response['image_path'] = display_members_photo($user_id, get_option('siteurl') . '/wp-content/');
                    dsp_delete_news_feed($user_id, 'profile_photo');
                    dsp_add_news_feed($user_id, 'profile_photo');
                    dsp_add_notification($user_id, 0, 'profile_photo');
                } else {
                    $dsp_tmp_members_photos_table = $wpdb->prefix . DSP_TMP_MEMBERS_PHOTOS_TABLE;
                    if (!is_null($my_img)){
                        $count_rowsin_tmp = $wpdb->get_var("SELECT COUNT(*) FROM $dsp_tmp_members_photos_table WHERE t_user_id=$user_id");
                        if ($count_rowsin_tmp > 0) {
                            $wpdb->query("UPDATE $dsp_tmp_members_photos_table SET t_picture='$new_name',t_status_id=0 WHERE t_user_id=$user_id");
                        } else {
                            $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET t_user_id=$user_id,t_picture='$new_name',t_status_id=0");
                        } //  if($count_rowsin_tmp>0){
                    } else {
                        $wpdb->query("INSERT INTO $dsp_members_photos SET picture = '$new_name',status_id=0,user_id='$user_id'");
                        $wpdb->query("INSERT INTO $dsp_tmp_members_photos_table SET  t_user_id='$user_id', t_picture = '$new_name',t_status_id=0");
                    }  // if($count_rows>0){
                    $response['success']   = __('Pictures you uploaded will be approved within 24 hours.', 'wpdating');
                    $response['image_path'] = display_members_photo($user_id, get_option('siteurl') . '/wp-content/');
                } // if($check_approve_photos_status->setting_status=='Y')
            }
        }

        die(json_encode($response));
    }

    /**
     * Uploads the profile picture of the current user
     *
     * @param $src_image
     * @param $dest_image
     * @param int $thumb_size
     * @param int $jpg_quality
     * @return bool
     * @since 4.7.0
     */
    function square_image_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90)
    {

        // Get dimensions of existing image
        $image = getimagesize($src_image);

        // Check for valid dimensions
        if ($image[0] <= 0 || $image[1] <= 0) {
            return false;
        }

        // Determine format from MIME-Type
        $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

        // Import image
        switch ($image['format']) {
            case 'jpg':
            case 'jpeg':
                $image_data = imagecreatefromjpeg($src_image);
                break;
            case 'png':
                $image_data = imagecreatefrompng($src_image);
                break;
            case 'gif':
                $image_data = imagecreatefromgif($src_image);
                break;
            default:
                // Unsupported format
                return false;
                break;
        }

        // Verify import
        if ($image_data == false) {
            return false;
        }

        // Calculate measurements
        if ($image[0] & $image[1]) {
            // For landscape images
            $x_offset    = ($image[0] - $image[1]) / 2;
            $y_offset    = 0;
            $square_size = $image[0] - ($x_offset * 2);
        } else {
            // For portrait and square images
            $x_offset    = 0;
            $y_offset    = ($image[1] - $image[0]) / 2;
            $square_size = $image[1] - ($y_offset * 2);
        }

        // Resize and crop

        $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        if (imagecopyresampled(
            $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size,
            $square_size
        )) {

            // Create thumbnail
            switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
                case 'jpg':
                case 'jpeg':
                    return imagejpeg($canvas, $dest_image, $jpg_quality);
                    break;
                case 'png':
                    return imagepng($canvas, $dest_image);
                    break;
                case 'gif':
                    return imagegif($canvas, $dest_image);
                    break;
                default:
                    // Unsupported format
                    return false;
                    break;
            }
        } else {
            return false;
        }
    }
}