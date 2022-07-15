(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

    var wpeeProfileContent = function ($scope, $) {
    	// Search tab
    	$('.wpee-search-tab').on( 'click', '.search-tab-title', function() {
    		$('.search-tab-title').removeClass('active');
    		var tab_content = $(this).attr('data-content');
    		$(this).addClass('active');
    		$(this).parent().siblings('.tab-content-wrapper').find('.tab-content-list' ).hide();
    		$(this).parent().siblings('.tab-content-wrapper').find('.tab-content-list.' + tab_content ).show();
    	});

    	/*
    	Remove Favorites Friend*/
		if($('.wpee-fav-list').length ){
				$('.wpee-fav-list').on('click', '.wpee-remove-fav', function(e){
					e.preventDefault();
					var nonce = $(this).attr('data-nonce');
					var fav = $(this).attr('data-fav-uid');
					var msg = $(this).attr('data-confirm-msg');
					var confirmation = confirm( msg );
					var par = $(this).parents('.friends-section'); // select parent div
				    if (confirmation) {
				     	// execute ajax
				        $.ajax({
							type: 'POST',
							url: eleObj.ajax_url,
							data: {
								'action': 'wpee_remove_favourites',
								'nonce': nonce,
								'fav': fav
							},
							cache: false,
							success: function(response){
								// When the response comes back
								response = JSON.parse(response);
								if( response.status == 'success' ){
									toastr.success(response.msg);
									$('#fav' + response.fav_uid ).remove();
									if( par.find('.wpee-fav-list').length < 1 ){
										par.append('<span class="dsp_span_pointer" style="text-align: center; display: block;">'+response.empty_msg+'</span>');
									}
								}
								else{
									toastr.error(response.msg);
								}
							}
			    		});
				    }
				});
			}

		if($('#news-feed').length){

			const load_feeds = () => {
			    const page      = $('#news-feed').attr('data-page');
			    const feed_type = $('#news-feed').attr('data-type');
			    const user_id   = $('#news-feed').attr('data-user-id');
			    $.ajax({
					type: 'GET',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_profile_activity_feeds',
						'feed_type': feed_type,
						'page': page,
						'user_id': user_id,
						'lang': eleObj.lang
					},
					beforeSend: function(){
						$('#news-feed').addClass('loading');
					    $('#loadMore').remove();
					},
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						if( response.class == 'feeds'){
							$('#news-feed .feeds-content').removeClass('no-feeds');
						}
						else if( response.class == 'no-feeds'){
							$('#news-feed .feeds-content').addClass('no-feeds');
						}
						$('#news-feed .feeds-content').append(response.content).fadeIn();
					    $('#news-feed').attr('data-page',response.page);
					},
					complete: function(){
						$('#news-feed').removeClass('loading');
					},
	    		});
			}

			load_feeds(); // intial news feed load

			$(window).scroll(function(){
	 
			  var position = $(window).scrollTop();
			  var news_top = $('#news-feed').offset().top;
			  var news_height = $('#news-feed').height();
			  var bottom = parseInt(news_top) + parseInt(news_height) - parseInt($(window).height());
			  if( position >= bottom && position <= bottom + 50  && !$('#news-feed .feeds-content').hasClass('no-feeds')  && !$('#news-feed').hasClass('loading')){
			    load_feeds();
			  }

		 	});
		}
    };

    var wpeeSearch = function ($scope, $) {
    	// Search tab
    	$('.wpee-search-tab').on( 'click', '.search-tab-title', function() {
    		$('.search-tab-title').removeClass('active');
    		var tab_content = $(this).attr('data-content');
    		$(this).addClass('active');
    		$(this).parent().siblings('.tab-content-wrapper').find('.tab-content-list' ).hide();
    		$(this).parent().siblings('.tab-content-wrapper').find('.tab-content-list.' + tab_content ).show();
    	});
    };

    var wpeeMemberList = function ($scope, $) {

    	$('.form-sidebar-wrapper').on( 'click', '.trigger-filter', function() {
    		$(this).parent().siblings('.form-sidebar-container').toggleClass('show-sidebar');
    	});
    	// Ajax filter
    	$('#member-ajax-filter').on( 'submit', function( e ) {
    		e.preventDefault();
			$('input[name="filter_active"]', $(this)).val(1);
			const formData = $(this).serializeArray();
    		member_ajax_filter( formData );
    	});
    	$('.wpee-filter-reset').on( 'click', event => {
    		const currentTarget = event.currentTarget;
			$('input[name="filter_active"]', $(currentTarget)).val(0);
			$(currentTarget).parents('form').find('input[name="filter_active"]').val(0);
			$(currentTarget).parents('form').find('input[name="username"]').val('');
			$(currentTarget).parents('form').find('select option').removeAttr('selected');
    	});
    	// Ajax filter
		var timeout = null;
    	$('.wpee-ajax-filter .username-filter').on('keyup',event => {
			event.preventDefault();
			const currentTarget = event.currentTarget;
		    clearTimeout(timeout);
		    if ( $(currentTarget).val() === '' ) {
				$(currentTarget).parents('form').find('input[name="user_name_filter_active"]').val(0);
			} else {
				$(currentTarget).parents('form').find('input[name="user_name_filter_active"]').val(1);
			}
		    timeout = setTimeout(function() {
	    		const formData = $(currentTarget).parents("form").serializeArray();
	    		member_ajax_filter( formData );
		    }, 1000);
		});

		$(window).scroll(function(){ 
		  let position       = $(window).scrollTop();
		  let members_top    = $('.wpee-member-list-content').offset().top;
		  let members_height = $('.wpee-member-list-content').height();
		  let bottom         = parseInt(members_top) + parseInt(members_height) - parseInt($(window).height());
		  if( position >= bottom && position <= bottom + 50 && !$('.wpee-member-list-content').hasClass('loading') && !$('.wpee-member-list-content').hasClass('no-results')){	
    		let formData = $("#member-ajax-filter").serializeArray();
    		const page     = $("#member-ajax-filter").attr('data-page');
			formData.push({ 'name': 'page', 'value': page});
			formData.push({ 'name': 'lang', 'value':  eleObj.lang});
			$.ajax({
					type: 'GET',
					url: eleObj.ajax_url,
					data: formData,
					cache: false,
					beforeSend: function(){
						$('.wpee-member-list-content').addClass('loading');
					},
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						$('.main-member-list-wrap').append(response.content);
						$("#member-ajax-filter").attr('data-page',response.page);
						$('.wpee-member-list-content').removeClass('loading');
						if( response.class !== undefined ){
							$('.wpee-member-list-content').addClass(response.class);
						}
					}

	    	});
		  }

	 	});
    	// $('.wpse_pagination').on('click','a', function(e){
    	// 	e.preventDefault();
    	// 	var paged = $(this).text();
    	// 	var url = $(this).attr('href');
    	// 	$('#pagination-form').attr('action', url.split('?')[0]);
    	// 	$('#pagination-form input[name=paged]').val(paged);
    	// 	$('#pagination-form input[name=submit]').click();
    	// });
    };
    var wpeeProfileHeader = function ($scope, $) {
    	$('.trigger-cover-photo').on('click', function(){
    		$(this).siblings('form').find('#file').click();
    	});
   //  	$('#file').on('change', function(){
   //  		$("#formId").submit();
   //  	});
   //  	$("#formId").on('submit', function(e)
			// {
		 //        e.preventDefault();
				
			// 	// get file field value using field id
			// 	var fileInputElement = document.getElementById("file");
		 //  		var fileName = fileInputElement.files[0].name;
				
				
			// 	if($.trim(fileName)=="")
			// 	{
			// 		alert('Upload your file');
			// 		return false;
			// 	}
			// 	else
			// 	{
			// 		var ajax_url = eleObj.ajax_url;
			// 		$.ajax({
			// 			url:ajax_url,
			// 			type:"POST",
			// 			processData: false,
			// 			contentType: false,
			// 			data:  new FormData(this),
			// 			success : function( response ){
			// 				var returnedData = JSON.parse(response);
			// 				alert(returnedData.msg);
			// 			},
			// 		});
			// 		return false;
			// 	}
			// 	return false;
		 //    });
    };
    var wpeeLoginRegister = function ($scope, $) {

    	$('.wpee-lr-tab-title').on('click', function(e){
    		var tab_id = $(this).attr('id');
    		$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-content-tab').removeClass('active');
    		$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-content-tab.' + tab_id ).addClass('active');
	    });
    	$('.login-tab-trigger').on('click', function(e){
    		e.preventDefault();
    		var ltab = $(this).attr('data-tab-content');
    		$(this).parents( '.wpee-login-tab' ).find('.login-tab-content').removeClass('active');
    		$(this).parents( '.wpee-login-tab' ).find('.login-tab-content.' + ltab ).addClass('active');
	    });
	    $("#get-password").click(function(e) {
	    	e.preventDefault();
            $("#loading_reset").show();
            $(".notification,.error").slideUp(function() {
                $(".notification,.error").remove();
            });

            var user_n_email = $("#user_n_email").val(),
                ajaxurl = eleObj.ajax_url;
            $.ajax({
                type: "POST",
                url: ajaxurl + "?action=dsp_verify_email",
                data: {email:user_n_email},
                dataType: 'json',
                success: function(html) {
                    if ($.trim(html['output']) == 1) {
                        $(".lost-password-page").append('<p style="display:none" class="notification">Please check your e-mail for the confirmation link</p>');
                        $(".lost-password-page .notification").slideDown();
                        $("#user_n_email").val('');
                    }
                    else {

                        $(".lost-password-page").append('<p style="display:none" class="error">Invalid username or e-mail</p>');
                        $(".lost-password-page .error").slideDown();
                    }
                    $("#loading_reset").hide();
                }
            });
            return false;
        });

    };

    $(window).on('elementor/frontend/init', function () {
        const addHandler = ($element) => {
            elementorFrontend.elementsHandler.addHandler(WidgetHandlerClass, {
                $element,
            });
        };
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-search.default', wpeeSearch);
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-member.default', wpeeMemberList);
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-profile-header.default', wpeeProfileHeader);
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-content.default', wpeeProfileContent);
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-register-form.default', wpeeLoginRegister);
        elementorFrontend.hooks.addAction('frontend/element_ready/wpee-testimonials.default', wpeeTestimonials);
    });


    /*
    * Member list ajax filter
    */
    function member_ajax_filter( formData = '' ){
    	if( formData !== '' ){
			formData.push({ 'name': 'page', 'value': 1});
			formData.push({ 'name': 'lang', 'value': eleObj.lang});
	    	$.ajax({
					type: 'GET',
					url: eleObj.ajax_url,
					data: formData,
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						$('.main-member-list-wrap').html(response.content);
						$("#member-ajax-filter").attr('data-page',2);
					}
	    	});
    	}
	}

	
})( jQuery );