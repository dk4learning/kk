/*
 * jQuery File Upload Demo
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */


// import Pintura Image Editor functionality:
import {openDefaultEditor} from "./pintura.min.js";

/* global $ */
$ = jQuery.noConflict();

$(function () {
  'use strict';

  let input = $('input[name="wpdating-gallery-album-id"]');

  // Initialize the jQuery File Upload widget:
  $('#fileupload').fileupload({
    // When editing a file use Pintura Image Editor:
    edit: function (file) {
      return new Promise((resolve, reject) => {
        const editor = openDefaultEditor({
          src: file
        });
        editor.on('process', ({ dest }) => {
          resolve(dest);
        });
        editor.on('close', () => {
          resolve(file);
        });
      });
    },
    // Uncomment the following to send cross-domain cookies:
    //xhrFields: {withCredentials: true},
    url: document.location.origin + '/wp-content/plugins/dsp_dating/wpdating-gallery/lib/server/php/index.php?albumId=' + input.val(),
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
