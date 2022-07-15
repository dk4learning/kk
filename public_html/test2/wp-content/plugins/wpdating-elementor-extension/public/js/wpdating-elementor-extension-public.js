(function( $ ) {
	'use strict';

	$(document).on("change", '.country_trigger', event => {
		let country = $(event.currentTarget).val();
		country     = country.replace(/ /g, '%20');
		$.get(
			eleObj.ajax_url,
			{'action' : 'wpee_get_state_options', 'country': country, 'lang' : eleObj.lang },
			response => {
				response = JSON.parse(response);
				$(event.currentTarget).parents('form').find('.state_trigger').html(response.content);
			}
		);

		$.get(
			eleObj.ajax_url,
			{'action' : 'wpee_get_city_options', 'country': country, 'lang' : eleObj.lang },
			response => {
				response = JSON.parse(response);
				$(event.currentTarget).parents('form').find('.city_trigger').html(response.content);
			}
		);
	});

	$(document).on("change", '.state_trigger', event => {
		let state   = $(event.currentTarget).val();
		state       = state.replace(/ /g, '%20');
		let country = $(event.currentTarget).parents('form').find('.country_trigger').val();
		country     = country.replace(/ /g, '%20');
		$.get(
			eleObj.ajax_url,
			{'action' : 'wpee_get_city_options', 'country': country, 'state': state, 'lang' : eleObj.lang },
			response => {
				response = JSON.parse(response);
				$(event.currentTarget).parents('form').find('.city_trigger').html(response.content);
			}
		);
	});

	$(document).ready(function(){
		/* 
		Login Register Page
		*/
		$('.login-tab-trigger').on('click', function(e){
    		var tab_id = $(this).attr('data-id');
    		$('.wpee-lr-tab-title').removeClass('active');
    		if( tab_id == 'lr-forget' ||  tab_id == 'lr-login' ){
    			$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-tab .wpee-lr-tab-title:first-child').addClass('active');
    		}
    		else{
    			$(this).addClass('active');
    		}

    		$('.login-tab-content').removeClass('active');
    		$('.login-tab-content.login').addClass('active');
    		$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-tab-content-wrap').attr('data-tab', tab_id );
    		$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-content-tab').removeClass('active');
    		$(this).parents( '.wpee-register-form-wrap' ).find('.wpee-lr-content-tab.' + tab_id ).addClass('active');
	    });

		$('.login-wrap').on('click', '.header-login-btn', function(e){
			e.preventDefault();
			$('.wpee-register-form').addClass('is-open');
			$(document).on('mousedown','body',function(e) {
			    var container = $(".wpee-register-form-wrap");
		        if (!container.is(e.target) && container.has(e.target).length === 0)
		        {
		            container.parents('.wpee-register-form').removeClass('is-open');
		        }
		    });
	    });

		$('.not-logged-in .login-form-trigger').on('click', 'a', function(e){
			e.preventDefault();
			$('.wpee-register-form').addClass('is-open');
			$(document).on('mouseup','body',function(e) {
			    var container = $(".wpee-register-form-wrap");
		        if (!container.is(e.target) && container.has(e.target).length === 0)
		        {
		            container.parents('.wpee-register-form').removeClass('is-open');
		        }
		    });
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
                url: ajaxurl + "?action=wpee_verify_email",
                data: {email:user_n_email},
                dataType: 'json',
                success: function(html) {
                    if ($.trim(html['output']) == 1) {
                        $("#get-password").after('<p style="color:green" class="notification">Please check your e-mail for the confirmation link</p>');
                        $("#get-password .notification").slideDown();
                        $("#user_n_email").val('');
                    } else {
                        $("#get-password").after('<p style="color:green" class="error">Invalid username or e-mail</p>');
                        $("#get-password .error").slideDown();
                    }
                    $("#loading_reset").hide();
                }
            });
            return false;
        });

	    $('.wpee-register-form.popup .wpee-register-form-wrap').prepend('<span class="close-trigger"><i class="fa fa-times"></i></span>');
	    $('.wpee-register-form-wrap .close-trigger').on('click', function(){
	    	$('.wpee-register-form.popup').removeClass('is-open');
	    });

	    /* isotpe */
		$('.member-list-tab-wrap').imagesLoaded( function() {
			//isotope data filter
			var $grid = $('.member-list-tab-content').isotope({
			  itemSelector: '.member-detail-wrap',
			  resize: true,
			  layoutMode: 'fitRows',
			});


			// bind filter button click
			$('.filters-button-group').on('click', 'li', function () {
			  var filterValue = $(this).attr('data-filter');
			  $grid.isotope({filter: filterValue});
			});

			// change is-checked class on buttons
			$('.button-group').each(function (i, buttonGroup) {
			  var $buttonGroup = $(buttonGroup);
			  $buttonGroup.on('click', 'li', function () {
			    $buttonGroup.find('.is-checked').removeClass('is-checked');
			    $(this).addClass('is-checked');
			  });
			});
		});
		/* 
		Delete Friend
		*/
		if($('.wpee-delete-list').length ){
			$('.wpee-delete-list').on('click', '.wpee-friend-delete', function(e){
				e.preventDefault();
				var nonce = $(this).attr('data-nonce');
				var friend = $(this).attr('data-friend-uid');
				var msg = $(this).attr('data-confirm-msg');
				var confirmation = confirm( msg );
				var par = $(this).parents('.friends-section'); // select parent div
			    if (confirmation) {
			     	// execute ajax
			        $.ajax({
						type: 'POST',
						url: eleObj.ajax_url,
						data: {
							'action': 'wpee_delete_friend',
							'nonce': nonce,
							'friend': friend
						},
						cache: false,
						success: function(response){
							// When the response comes back
							response = JSON.parse(response);
							if( response.status == 'success' ){
                        		toastr.success(response.msg);
								$('#friend' + response.friend_uid ).remove();
								if( par.find('.wpee-delete-list').length < 1 ){
									par.append('<span class="dsp_span_pointer" style="text-align: center; display: block;">No result found !</span>');
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

		/* 
		Add to favourites
		*/
		if($('.wpee-add-fav-btn').length ){
			$('.wpee-add-fav-btn').on('click', function(e){
				e.preventDefault();
				var nonce = $(this).attr('data-nonce');
				var fav = $(this).attr('data-fav-uid');
				var par = $(this).parents('.wpee-add-fav-section'); // select parent div
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_add_favourites',
						'nonce': nonce,
						'fav': fav
					},
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
                        toastr.success(response.msg);
					}
	    		});
			});
		}

		/* 
		Add to favourites
		*/
		if($('.wpee-send-wink-msg').length ){
			$('.wpee-send-wink-msg').on('click', '.wpee-send-wink-btn', function(e){
				e.preventDefault();
				var winkForm =$(this).parents('form').serialize();
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: winkForm,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
                        toastr.success(response.msg);
					}
	    		});
			});
		}


		/* To approve or delete gift */
		$('.gift-action').on('click', '.gift-action-trigger', function() {
			var gift_action = $(this).attr('data-action');
			var gift_id = $(this).data('gift-id');
		    if ( gift_action == 'delete') {
				var msg = $(this).attr('data-confirm-msg');
				var confirmation = confirm( msg );
				if( !confirm ){
					return false;
				}
		    }
		    if ( ( gift_action == 'delete' && confirmation ) || gift_action == 'approve' ) {
		     	// execute ajax
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_gift_action',
						'gift_action': gift_action,
						'gift_id': gift_id
					},
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						if( response.status == 'deleted' || response.status == 'approved' ){
							if( response.status == 'deleted' && $('#wpee-gift-' + gift_id ).length ){
								$('#wpee-gift-' + gift_id ).remove();
								if($('.wpee-gifts-received-content').length < 1){
									$('.wpee-gifts-received-wrapper').append(response.empty);
								}
							}
                        	toastr.success(response.msg);
						}
						else{
                        	toastr.error(response.msg);
						}
					}
	    		});
		    }
		});
		/* To show Gift popup form*/
		$('.wpee_gift_popup').on('click', function(e) {
			e.preventDefault();
			var nonce = $(this).attr('data-nonce');
			var profile_id = $(this).attr('data-profile-id');
			$('#wpee-popup-container').remove();
			var ele = $(this);
			$.ajax({
				type: 'POST',
				url: eleObj.ajax_url,
				data: {
					'action': 'wpee_gift_popup',
					'nonce': nonce,
					'profile_id': profile_id,
				},
				beforeSend: function(){
					ele.addClass('loading');
				},
				cache: false,
				success: function(response){
					// When the response comes back
					response = JSON.parse(response);
					if( response.status == 'success' ){
						$('body').append( response.content );
					}
				},
				complete: function(){
					ele.removeClass('loading');
				}
			});
		});
		/* to submit gift popup / dropdown form*/
		$('body').on('click', '.wpee-gift-submit', function(e) {
			e.preventDefault();
			var nonce = $(this).siblings('input[name=wpee-gift-option]').val();
			var gifts = document.querySelectorAll('input[name="wpee-gift"]');
			for (var i = 0; i < gifts.length; i++) {
				if (gifts[i].checked) {
					var gift = gifts[i].value;
				}
			  }
			var profile_id = $(this).siblings('input[name=profile-id]').val();
			var ele = $(this).parent();
			$.ajax({
				type: 'POST',
				url: eleObj.ajax_url,
				data: {
					'action': 'wpee_gift_form_action',
					'nonce': nonce,
					'gift': gift,
					'profile_id': profile_id,
				},
				beforeSend: function(){
					ele.addClass('loading');
				},
				success: function(response){
					// When the response comes back
					response = JSON.parse(response);
					if( response.status == 'success' ){
						toastr.success(response.msg);
					}
					else{
						toastr.error(response.msg);
					}
				},
				complete: function(){
					ele.removeClass('loading');
				}
			});
		});
		/* 
		To block user
		*/
		$(document).on('click', '.wpee-block-trigger', function(e) {
			e.preventDefault();
			var blockElement = $(this);
			var block_action = $(this).attr('data-action');
			var block_id = $(this).attr('data-block-id');
			var profile_id = $(this).attr('data-profile-id');
		    if ( ( block_action == 'delete' ) ) {
		     	// execute ajax
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_block_action',
						'block_action': block_action,
						'block_id': block_id,
						'profile_id': profile_id
					},
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						if( response.status == 'deleted' ){
							if( $('#wpee-block-' + block_id ).length ){ // Condition to remove blocked member from profile blocked page after removed
								$('#wpee-block-' + block_id ).remove();
								if($('.wpee-block-content').length < 1){
									$('.wpee-block-wrapper').append(response.empty);
								}
							}
							if( $('.wpee-block-' + response.profile_id ).length ){ // Condition to change value and text of button in header or other similar ajax buttons

								$('.wpee-block-' + response.profile_id ).attr({"data-action": response.action, "data-block-id": response.block_id});
								$('.wpee-block-' + response.profile_id ).text(response.text);
							}
							toastr.success(response.msg);
						}
						else{
							toastr.error(response.msg);
						}
					}
	    		});
		    }
		    else{
		    	// execute ajax
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_block_action',
						'block_action': block_action,
						'block_id': block_id,
						'profile_id': profile_id
					},
					beforeSend: function(){
						blockElement.addClass('loading');
					},
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						if( response.status == 'added' ){
							if( $('.wpee-block-' + response.profile_id ).length ){ // Condition to change value and test of button in header or other similar ajax buttons

								$('.wpee-block-' + response.profile_id ).attr({"data-action": response.action, "data-block-id": response.block_id});
								$('.wpee-block-' + response.profile_id ).text(response.text);
								//$('.wpee-block-' + response.profile_id ).attr({"data-action": response.action, "data-block-id": response.block_id}).text(response.text);
							}
							toastr.success(response.msg);
						}
						else{
							toastr.success(response.msg);
						}
					},
					complete: function(){
						blockElement.removeClass('loading');
					},
	    		});
		    }
		});
		/* 
		Meet Me Action
		*/
		$(document).on('click', '.wpee-meet-me-trigger', function(e) {
			e.preventDefault();
			const meetAction    = $(this).data('action');
			const profileID     = $(this).parent().data('profile-id');
			const currentUserID = $(this).parent().data('current-user-id');
	     	// execute ajax
	        $.ajax({
				type: 'POST',
				url: eleObj.ajax_url,
				data: {
					'action'         : 'wpee_meet_me_action',
					'meet_action'    : meetAction,
					'current_user_id': currentUserID,
					'user_id'        : profileID,
					'lang'			 : wpee_elementor_js_object.lang
				},
				beforeSend: function(){
					$('.meet-to-info' ).fadeOut();
				},
				success: function(response){
					// When the response comes back
					response = JSON.parse(response);
					$('.meet-to-info' ).html(response.content).fadeIn();
				}
    		});
		});

		/* 
		Add Friend Action
		*/
		$(document).on('click', '.wpee-add-friend-trigger', function(e) {
			e.preventDefault();
			var friend_id = $(this).attr('data-friend-id');
			var wpee_action = $(this).attr('data-action');
			var AddEle = $(this);
	     	// execute ajax
	        $.ajax({
				type: 'POST',
				url: eleObj.ajax_url,
				data: {
					'action': 'wpee_add_friend_action',
					'friend_id': friend_id,
					'wpee_action': wpee_action
				},
				beforeSend: function(){
				},
				success: function(response){
					// When the response comes back
					response = JSON.parse(response);
					if( $('.wpee-friend-' + response.profile_id ).length ){ // Condition to change value and text of button in header or other similar ajax buttons

						$('.wpee-friend-' + response.profile_id ).attr({"data-action": response.action});
						$('.wpee-friend-' + response.profile_id ).text(response.text);
					}
					toastr.success(response.msg);
					if( response.btntext != undefined ){
						AddEle.text( response.btntext );
					}
				}
    		});
		});

		/* 
		Friend Request Action
		*/
		$(document).on('click', '.wpee-friend-request-trigger', function(e) {
			e.preventDefault();
			var profile_id = $(this).data('profile-id');
			var fr_action = $(this).data('action');
			var fr_id = $(this).data('fr-id');
	     	// execute ajax
	        $.ajax({
				type: 'POST',
				url: eleObj.ajax_url,
				data: {
					'action': 'wpee_friend_request_action',
					'profile_id': profile_id,
					'fr_action': fr_action,
					'fr_id': fr_id
				},
				beforeSend: function(){
				},
				success: function(response){
					// When the response comes back
					response = JSON.parse(response);
					$('#fr' + response.profile_id ).remove();
					toastr.success(response.msg);
				}
    		});
		});

		$('.panel-title-toggle').on('click', function(){
			$(this).parents('.panel-heading').siblings('.panel-collapse').toggleClass('in');
		});

		$('#wpee-status-form').on('submit', function(e){
			e.preventDefault();
			var status = $(this).find('textarea[name=profile_status]').val();
			var nonce = $(this).find('input[name=profile-status-nonce]').val();
			if( status != '' && status != null ){
		        $.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: {
						'action': 'wpee_post_status',
						'status': status,
						'nonce': nonce
					},
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						if( response.status == 'success' ){
							$('.profile-status-text').val('');
							toastr.success(response.msg);
						}
						else{
							toastr.error(response.msg);
						}
					}
	    		});
			}
			else{
				alert('Your status is empty');
			}
			return false;
		});

		$('#wpee_edit_location').click(function(e){
			e.preventDefault();
			wpee_getLocation();

		});

		/*
		Report user
		*/
		if($('.wpee-report-user-btn').length ){
			$('.wpee-report-user-btn').on('click', function(e){
				$('.wpee-report-user-form-wrap').addClass('is-open');

				$(document).on('mouseup','body',function(e) {
					var container = $(".wpee-inner-msg");
					if (!container.is(e.target) && container.has(e.target).length === 0)
					{
						$(".wpee-inner-msg").parent().removeClass('is-open');
					}
				});
			});
		}
		if($('#wpee-report-user').length ){
			$('#wpee-report-user').on('submit', function(e){
				e.preventDefault();
				$('.wpee-report-user-form-wrap').addClass('is-open');
				var reportData = $(this).serializeArray();
				var reportEle = $(this);
				reportData.push({ 'name': 'action', 'value': 'wpee_report_user_action'});
				$.ajax({
					type: 'POST',
					url: eleObj.ajax_url,
					data: reportData,
					cache: false,
					success: function(response){
						// When the response comes back
						response = JSON.parse(response);
						toastr.success(response.msg);
						reportEle.find('textarea').val('');
					}
				});
				return false;
			});
		}

		$('.profile-header-menu .profile-menu-wrapper').prepend('<div class="ham-icon"><span></span></div>');

		$('.profile-menu-wrapper .ham-icon').on('click', function(e){
			$(this).toggleClass('is-triggred');
			$('.profile-menu-tab').toggleClass('is-open');
			e.stopPropagation();
		});

		// Media Popup
		$('.wpee-create-button,.wpee-edit-button').on('click', function(e){
			e.preventDefault();
			$('.popup-main-wrapper').addClass('is-open');
		});
		$('.pop-up-bg').on('click', function(){
			$('.popup-main-wrapper').removeClass('is-open');
		});
	});
})( jQuery );

// edit my location called function to get lattitude and longitude values dynamically
function wpee_getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(wpee_showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }

    }
function wpee_showPosition(position) {
       userLat =  position.coords.latitude;
       userLng =  position.coords.longitude;
       var element = document.getElementById('wpee_edit_location');
       var site_url = element.getAttribute("data-siteurl");
       var redirect_url = site_url + "?lat="+userLat+"&lng="+userLng;
       window.location.href = redirect_url;
    }