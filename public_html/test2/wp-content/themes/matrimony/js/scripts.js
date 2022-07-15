//==============================================================

// CUSTOM SCRIPTS

// 2014

// ==============================================================






jQuery(document).ready(function($) {

    $('.dspdp-btn').addClass('btn').removeClass('dspdp-btn');

    //Move To Top
    var window_height = jQuery(window).height();
    var window_height = (window_height) + (50);
    
    

    jQuery(window).scroll(function() {
        var scroll_top = $(window).scrollTop();
        if (scroll_top > window_height) {
            jQuery('.lavish_date_move_to_top').show();
        }
        else {
            jQuery('.lavish_date_move_to_top').hide();   
        }
    });

    jQuery('.lavish_date_move_to_top').click(function(){
        jQuery('html, body').animate({scrollTop:0}, 'slow');
        return false;
    });

    /*
    =================================================
    Mobile Menus
    =================================================
    */
    function create_toggle_menu() {
        jQuery('html, body').animate({scrollTop:0}, 'fast');
        jQuery('.lavish_mobile_toggle').show();
        jQuery('.toggle_menu_bar').find('i').removeClass('fa-bars');
        jQuery('.toggle_menu_bar').find('i').addClass('fa-times');
        jQuery('.main-wrapper').css({"left":"-250px", "position": "relative"});
    }

    function create_toggle_close(){
            jQuery('.lavish_mobile_toggle').hide();
            jQuery('.toggle_menu_bar').find('i').removeClass('fa-times');
            jQuery('.toggle_menu_bar').find('i').addClass('fa-bars');
            jQuery('.main-wrapper').css({"left":"0px", "position": "relative"});
     }

    jQuery('.toggle_menu_bar').toggle(function() {   
        create_toggle_menu();
    },
    function() {
        create_toggle_close();
    });


    //Menu Sidebar Toggle 
    jQuery('.nav_usermobilemenu').hide();
        
        /*
        jQuery('.lavish_mobile_toggle_Menu').click(function(){
           jQuery('.lavish_mobile_toggle').hide();
           jQuery('.main-wrapper').css({"left":"0px", "position": "relative"});
           // jQuery('.nav_usermobilemenu').hide();
           // jQuery('.nav_mobilemenu').show();
        });
        */
        
        jQuery('.lavish_mobile_toggle_user').click(function(){
            jQuery('.nav_mobilemenu').hide();
            jQuery('.nav_usermobilemenu').show();
        });
        jQuery('.lavish_mobile_toggle_Menu').click(function(){
            jQuery('.nav_mobilemenu').show();
            jQuery('.nav_usermobilemenu').hide();
        });
    //change toggle menu on screen more than 1000
    jQuery(window).resize(function(){
        var window_width= jQuery(window).width();
        if (window_width > 1000) {
            create_toggle_close();
        }
    });

    /*
    =================================================
    Sticky Menu
    =================================================
    */
    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
            $("#lavish_dsp_header").css({"position":"fixed", "right":"0px", "left":"0px", "top": "0px","z-index":"1080"});
            
        }
        else {
            $("#lavish_dsp_header").css({"position":"relative", 'box-shadow':'none'});
        }
    });
  
        
    

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





