<div class="photo-list-inner">
	<?php
    if ( isset( $_GET['album_id'] ) && !empty( $_GET['album_id'] ) ) {
        $dsp_user_albums_table = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;

        $album_id_gallery = $_GET['album_id'];
        $user_album       = $wpdb->get_row("SELECT * FROM {$dsp_user_albums_table} WHERE album_id = '{$_GET['album_id']}'");
    } else {
        $album_id_gallery = 0;
    }

	$current_user_id = get_current_user_id();
	$user_id         = wpee_profile_id();
	$profile_link    = trailingslashit(wpee_get_profile_url_by_id($user_id));

    if ( function_exists( 'pll_get_post_language' ) ) {
        global $post;
        $lang = pll_get_post_language( $post->ID, 'slug' );
    } else {
        $lang = '';
    }
	?>
    <?php if ( isset( $user_album ) ) : ?>
	<div class="wpee-album-details">
        <div class="wpee-album-back-button-section">
            <a href="<?php echo $current_url; ?>" class="dspdp-btn dspdp-btn-sm dspdp-btn-info dsp-btn-info dsp-btn-sm"><?php echo __('Back', 'wpdating'); ?></a>
        </div>
        <div class="wpee-album-name-section">
            <span class="wpee-album-name-label"><?php echo __('Album name', 'wpdating'); ?>:</span>
            <span class="wpee-album-name-value"><?php echo $user_album->album_name; ?></span>
        </div>
    </div>
    <?php endif; ?>
    <?php
	if( $user_id == $current_user_id ):
        $feature_access = wpee_check_membership('Upload Photos', $current_user_id);
        if ( !is_wp_error($feature_access) && $feature_access == true ): ?>
	    <!-- CSS adjustments for browsers with JavaScript disabled -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css"/>
        <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload.css"/>
        <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload-ui.css"/>
        <noscript>
	        <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload-noscript.css"/>
	    </noscript>
	    <noscript>
	        <link rel="stylesheet" href="<?php echo WPDATING_GALLERY_URL; ?>lib/css/jquery.fileupload-ui-noscript.css"/>
	    </noscript>
	     <!-- The template to display files available for download -->
	        <script id="template-download" type="text/x-tmpl">
				{% for (var i=0, file; file=o.files[i]; i++) {  %}
				  <div class="template-download fade{%=file.url?' image':''%}">
				      <div>
				          <span class="preview">
				              {% if (file.url) { %}
				                  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery="profile-photos"><span class='image-bg' style='background-image: url("{%=file.url%}");'><span></a>
				              {% } %}
				          </span>
						  {% if (file.message) { %}
                  		  <strong class="info text-info"><?php echo __('{%=file.message%}', 'wpdating'); ?></strong>
						  {% } %}
						  {% if (file.error) { %}
                  		  <strong class="error text-danger"><?php echo __('{%=file.error%}', 'wpdating'); ?></strong>
						  {% } %}
				      </div>
					  {% if (file.error == undefined && ( file.message == undefined || (file.message && !file.message.includes('approved') ) ) ) { %}
				      <div>
				          {% if (file.deleteUrl) { %}
				              <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
				                  <i class="glyphicon glyphicon-trash"></i>
				                  <span><?php echo __('Delete','wpdating'); ?></span>
				              </button>
				          {% } else { %}
				              <button class="btn btn-warning cancel">
				                  <i class="glyphicon glyphicon-ban-circle"></i>
				                  <span><?php echo __('Cancel','wpdating'); ?></span>
				              </button>
				          {% } %}
				      </div>
					  {% } %}
				  </div>
				{% } %}
	    </script>
	        <!-- The template to display files available for upload -->
	        <script id="template-upload" type="text/x-tmpl">
	        {% for (var i=0, file; file=o.files[i]; i++) { %}
	          <div class="template-upload fade{%=o.options.loadImageFileTypes.test(file.type)?' image':''%}">
	              <div>
	                  <span class="preview"></span>
	                  <strong class="error text-danger"></strong>
	                  <p class="size">Processing...</p>
	                  <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
	              <div>
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
	              </div>
	          </div>
	        {% } %}

	        </script>
	    <!-- CSS adjustments for browsers with JavaScript disabled -->
	    <form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
	        <!-- Redirect browsers with JavaScript disabled to the origin page -->
	        <noscript>
	            <input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"/>
	        </noscript>
	        <input name="wpdating-gallery-album-id" type="hidden" value="<?php echo $album_id_gallery ?>">
	        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->

	        <!-- The table listing the files available for upload/download -->
	        <table role="presentation" class="table table-striped">
	            <tbody class="files"></tbody>
	        </table>
		        <div class="row fileupload-buttonbar">
		            <div class="col-lg-7">
		                <!-- The fileinput-button span is used to style the file input field as button -->
		                <span class="btn btn-success fileinput-button">
		                    <i class="glyphicon glyphicon-plus"></i>
		                    <span><?php echo __( 'Upload Photo', 'wpdating' ); ?></span>
		                    <input type="file" name="files[]" multiple />
		                </span>
		                <!-- End Add Photo buttons-->
		                <!-- Begin start upload buttons to start uploading files -->
		                <button type="submit" class="btn btn-primary start">
		                    <i class="glyphicon glyphicon-upload"></i>
		                    <span><?php echo __( 'Start upload', 'wpdating' ); ?></span>
		                </button>
		                <!-- End start upload buttons to cancel uploading files -->
		                <!-- Begin cancel upload buttons to start uploading files -->
		                <button type="reset" class="btn btn-warning cancel">
		                    <i class="glyphicon glyphicon-ban-circle"></i>
		                    <span><?php echo __( 'Cancel upload', 'wpdating' ); ?></span>
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
	    </form>
	    <!-- The file upload form used as target for the file upload widget -->
	    <!-- The blueimp Gallery widget -->
	    <div
	            id="blueimp-gallery"
	            class="blueimp-gallery blueimp-gallery-controls"
	            aria-label="image gallery"
	            aria-modal="true"
	            role="dialog"
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
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/vendor/jquery.ui.widget.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
        <script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
        <script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
        <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.iframe-transport.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-process.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-image.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-audio.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-video.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-validate.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/jquery.fileupload-ui.js"></script>
        <script src="<?php echo WPDATING_GALLERY_URL ?>lib/js/cors/jquery.xdr-transport.js"></script>
        <script type="text/javascript">
            /* global $ */
            $ = jQuery.noConflict();

            $(function ($) {
                'use strict';
                let input = $('input[name="wpdating-gallery-album-id"]');

                // Initialize the jQuery File Upload widget:
                $('#fileupload').fileupload({
                    doka: window.Doka && Doka.create(),
                    edit:
                        window.Doka &&
                        Doka.supported() &&
                        function (file) {
                            return this.doka.edit(file).then(function (output) {
                                return output && output.file;
                            });
                        },
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: '<?php echo site_url(); ?>/wp-content/plugins/dsp_dating/wpdating-gallery/lib/server/php/index.php?albumId=' + input.val(),
                })
                    .on('fileuploaddestroy', function (e, data) {
                        if (!window.confirm("Do you really want to delete the photo?")) {
                            return false;
                        }
                    });

                // Enable iframe cross-domain access via redirect option:
                $('#fileupload').fileupload(
                    'option',
                    'redirect',
                    window.location.href.replace(/\/[^/]*$/, '/cors/result.html?%s')
                );

                if (window.location.hostname === 'blueimp.github.io') {
                    // Demo settings:
                    $('#fileupload').fileupload('option', {
                        url: '//jquery-file-upload.appspot.com/',
                        // Enable image resizing, except for Android and Opera,
                        // which actually support image resizing, but fail to
                        // send Blob objects via XHR requests:
                        disableImageResize: /Android(?!.*Chrome)|Opera/.test(
                            window.navigator.userAgent
                        ),
                        maxFileSize: 999000,
                        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
                    });
                    // Upload server status check for browsers with CORS support:
                    if ($.support.cors) {
                        $.ajax({
                            url: '//jquery-file-upload.appspot.com/',
                            type: 'HEAD'
                        }).fail(function () {
                            $('<div class="alert alert-danger"></div>')
                                .text('Upload server currently unavailable - ' + new Date())
                                .appendTo('#fileupload');
                        });
                    }
                } else {
                    // Load existing files:
                    $('#fileupload').addClass('fileupload-processing');
                    $.ajax({
                        // Uncomment the following to send cross-domain cookies:
                        //xhrFields: {withCredentials: true},
                        url: $('#fileupload').fileupload('option', 'url'),
                        lang: '<?php echo $lang; ?>',
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    })
                        .always(function () {
                            $(this).removeClass('fileupload-processing');
                        })
                        .done(function (result) {
                            $(this)
                                .fileupload('option', 'done')
                                // eslint-disable-next-line new-cap
                                .call(this, $.Event('done'), { result: result });
                        });
                }
            });
        </script>
        <?php
        else:
        ?>
            <div class="profile-user-photos-inner wpee-block-content">
                <div style="text-align: center; display: grid;" class="wpee-search-error wpee-error">
                    <span style="color: #FF0000;"><?php echo $feature_access->get_error_message(); ?></span>
                    <span>
                        <a href="<?php echo esc_url( trailingslashit($profile_link) . 'settings/upgrade-account' ); ?>"><?php echo __('Click here', 'wpdating'); ?></a>
                    </span>
                </div>
            </div>
    <?php
        endif;
	else:
    	global $wpdb;
		$profile_photos_url = $profile_link . '/media/photos';
    	$dsp_galleries_photos = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
		$userphotos = $wpdb->get_results("SELECT * FROM {$dsp_galleries_photos} galleries WHERE galleries.status_id=1 AND galleries.user_id = '$user_id' AND galleries.album_id ='{$album_id_gallery}'");
		$imagepath = content_url('/') ;
		?>
		<div class="profile-user-photos-inner wpee-block-content">
			<?php if( !empty($userphotos)): ?>
				<ul class="profile-photo-list no-list">
					<?php foreach ($userphotos as $photo) {
						$imagepath = content_url('/uploads/dsp_media/user_photos/user_' . $user_id . '/album_'.$photo->album_id . '/'. $photo->image_name );
						?>
						<li class="photos-list">
							<a rel='example_group' href='<?php echo esc_url( $imagepath );?>'>
								<span class="image-bg" style="background-image: url('<?php echo esc_url($imagepath);?>');"></span>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php
			else: ?>
                <div style="text-align: center; display: grid;" class="wpee-empty-info wpee-info">
                    <span style="color: #FF0000;">
                        <?php if ( isset( $user_album ) ) : ?>
                        <?php esc_html_e( 'Empty', 'wpdating'); ?>
                        <?php else : ?>
                        <?php esc_html_e( 'The user has not uploaded any photos.', 'wpdating'); ?>
                        <?php endif; ?>
                    </span>
                </div>
				<?php
			endif;?>
		</div>
		<?php
	endif;
	?>
</div>