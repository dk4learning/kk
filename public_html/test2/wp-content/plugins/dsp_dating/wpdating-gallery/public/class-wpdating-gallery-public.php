<?php

class Wpdating_Gallery_Public {

    public function __construct() {

    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        wp_enqueue_style('wpdating-gallery-styles', plugin_dir_url( __FILE__ ) . 'css/wpdating-gallery-public.css', array(), '', 'all');
        wp_enqueue_style('wpdating-gallery-pintura-styles', WPDATING_GALLERY_URL . 'lib/css/pintura.min.css', array(), '', 'all');
        wp_enqueue_style('wpdating-gallery-blueimp-styles', '//blueimp.github.io/Gallery/css/blueimp-gallery.min.css', array(), '', 'all');
        wp_enqueue_style('wpdating-gallery-css-fileupload', WPDATING_GALLERY_URL . 'lib/css/jquery.fileupload.css', array(), '', 'all');
        wp_enqueue_style('wpdating-gallery-css-fileupload-ui', WPDATING_GALLERY_URL . 'lib/css/jquery.fileupload-ui.css', array(), '', 'all');
        wp_enqueue_style('wpdating-gallery-toast', '//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css', array(), '', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'wpdating-gallery-widget', WPDATING_GALLERY_URL . 'lib/js/vendor/jquery.ui.widget.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-render', '//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-preview-image', '//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-image-resize', '//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-gallery-script', '//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-XHR', WPDATING_GALLERY_URL . 'lib/js/jquery.iframe-transport.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-file-upload', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-file-upload-process', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-process.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-image-upload', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-image.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-audio-upload', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-audio.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-video-upload', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-video.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-validate', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-validate.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'wpdating-gallery-file-upload-ui', WPDATING_GALLERY_URL . 'lib/js/jquery.fileupload-ui.js', array( 'jquery' ), '', false );
        ?>
        <script type="module" src="<?php echo WPDATING_GALLERY_URL . 'lib/js/demo.js'; ?>"></script>
        <?php
        $data = [
            'siteUrl' => site_url()
        ];
        wp_localize_script( 'wpdating-gallery-main', 'wpdating_gallery_variable', $data );

        wp_enqueue_script( 'wpdating-gallery-ie-delete', WPDATING_GALLERY_URL . 'lib/js/cors/jquery.xdr-transport.js"', array( 'jquery' ), '', false );

    }

}