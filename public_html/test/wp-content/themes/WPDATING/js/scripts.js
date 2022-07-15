//==============================================================

// CUSTOM SCRIPTS

// 2014

// ==============================================================



jQuery(document).ready(function($) {

    //toggle login Form

    jQuery('.dsp-login').on('click', function(){

        jQuery('.dsp-login-form').toggleClass('hide');

    });



    //toggle login Form

    jQuery('.dsp-register').on('click', function(){

        jQuery('.dsp-user-setting').toggleClass('show');

    });



    //Animation on Homepage

    jQuery('.dsp-home-member').addClass("hiddenee").viewportChecker({

        classToAdd: 'animated fadeInLeft',

        offset: 100

    });



    //toggle side Nav Mobile View

    jQuery('.showform').on('click', function() {

        jQuery('.main-wrapper').toggleClass('show');

    });



    // Primary Menu

    jQuery(".dsp-menu li a").each(function() {

        if (jQuery(this).next().length > 0) {

            jQuery(this).addClass("parent");

        };

    })



    //Primary Menu Toggle

    jQuery(".toggleMenu").click(function(e) {

        e.preventDefault();

        jQuery(this).toggleClass("active");

        jQuery(".dsp-menu").toggle();

    });



    jQuery('#myTab a').click(function(e) {

        e.preventDefault();

        jQuery(this).tab('show');

    });



    //



    jQuery(".dsp-selectbox").selectbox();



    //ajax loading of the member page

    jQuery('a.dsp-ajax').on('click', function(e) {

        var id = jQuery(this).parents('ul.dsp-user-spec').data('userid');

        var action = jQuery(this).data('action');

        jQuery.ajax({

            url: dsp.adminUrl,

            method: 'get',

            type: 'POST',

            data:  { 

                'action':action, 

                'userid':id

            },

            success: function(result) {

                jQuery('div.ajax-content').fadeOut('slow').html('').html(result).fadeIn('600');

            }

        });

        e.preventDefault();

    });





    // image-filler

    jQuery('.upload-photo .image-container a.dspdp-media-images-cont, .dsp-image-fill').imagefill();

	

	

	//home widget same height 

	var max_dsp_hw=0;

	jQuery('.dsp-home-widget').each(function(){

		if(jQuery(this).outerHeight()>max_dsp_hw){

			max_dsp_hw=jQuery(this).outerHeight();

		}

	});

	

	jQuery('.dsp-home-widget').css({'min-height':max_dsp_hw});

	

	

	$('.dsp-user-info-container a').hover(function(){

		

		$(this).removeAttr('title');

		

		if($(this).find('.fa').is('.fa-user')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.my_profile+ '</div>');

		}

		if($(this).find('.fa').is('.fa-users')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.trendings+ '</div>');

		}

		if($(this).find('.fa').is('.fa-eye')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.viewed_me+ '</div>');

		}

		if($(this).find('.fa').is('.fa-bell-o')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.i_viewed+ '</div>');

		}

		if($(this).find('.fa').is('.fa-circle')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.members_online+ '</div>');

		}

		if($(this).find('.fa').is('.fa-envelope-o')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.send_message+ '</div>');

		}

        if($(this).find('.fa').is('.fa-envelope')){

            $(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.inbox+ '</div>');

        }

		if($(this).find('.fa').is('.fa-heart')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.add_to_favourites+ '</div>');

		}

		if($(this).find('.fa').is('.fa-smile-o')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.send_wink+ '</div>');

		}

		if($(this).find('.fa').is('.fa-plus-square')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.add_to_friend+ '</div>');

		}

		if($(this).find('.fa').is('.fa-gift')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.send_gift+ '</div>');

		}

		if($(this).find('.fa').is('.fa-comment')){

			$(this).append('<div class="tooltip-cont">'+scriptjsdata_profile.one_to_one_chat+ '</div>');

		}

		

	}, function(){

		$('.dsp-user-info-container  .tooltip-cont').remove();

	});

	



});





//Responsive menu

jQuery(window).bind('resize orientationchange', function() {

    ww = document.body.clientWidth;

    adjustMenu();

});



var adjustMenu = function() {

    if (ww < 991) {

        jQuery(".toggleMenu").css("display", "block");

        if (!jQuery(".toggleMenu").hasClass("active")) {

            jQuery(".dsp-menu").hide();

        } else {

            jQuery(".dsp-menu").show();

        }

        jQuery(".dsp-menu li").unbind('mouseenter mouseleave');

        jQuery(".dsp-menu li a.parent").unbind('click').bind('click', function(e) {

            // must be attached to anchor element to prevent bubbling

            e.preventDefault();

            jQuery(this).parent("li").toggleClass("hover");

        });

    }

    else if (ww >= 991) {

        jQuery(".toggleMenu").css("display", "none");

        jQuery(".dsp-menu").show();

        jQuery(".dsp-menu li").removeClass("hover");

        jQuery(".dsp-menu li a").unbind('click');

        jQuery(".dsp-menu li").unbind('mouseenter mouseleave').bind('mouseenter mouseleave', function() {

            // must be attached to li so that mouseleave is not triggered when hover over submenu

            jQuery(this).toggleClass('hover');

        });

    }

}
jQuery(document).ready(function(){
        // var registrationmsg = jQuery('#tab-register .box-page .registration-msg');
        // var mainregistrationblock = jQuery('#tab-register .box-page .dsp_reg_main ');

        if(jQuery('#tab-register .box-page .registration-msg').parent().siblings().hasClass('dsp_reg_main')){
            jQuery('.registration-msg').hide();
        }
        else{
            jQuery('.registration-msg').show();
        }

});




