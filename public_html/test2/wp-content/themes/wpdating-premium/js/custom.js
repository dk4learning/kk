jQuery(document).ready(function($) {

    // Sticky Header
    $(window).scroll(function(){

        if ($(window).scrollTop() >= 100) {
            $('header').addClass('header-shadow');
        }

        else {
            $('header').removeClass('header-shadow');
        }

    });

    var c, currentScrollTop = 0,
    navbar = $('header');

    $(window).scroll(function () {
        var a = $(window).scrollTop();
        var b = navbar.height();

        currentScrollTop = a;

        if (c < currentScrollTop && a > b + b) {
            navbar.addClass("scrollUp");
        } 

        else if (c > currentScrollTop && !(a <= b)) {
            navbar.removeClass("scrollUp");
        }

        c = currentScrollTop;
    });


    $('.trigger-filter-icon').on('click', function(){
        $('.offcanvase-filter-wrap').addClass('is-active');
    });

    $('.offcanvase-filter-wrap .filter-close,.offcanvase-filter-title-wrap input[type="submit"]').on('click', function(){
        $('.offcanvase-filter-wrap').removeClass('is-active');
    });

    $('.offcanvase-filter-wrap .offcanvase-filter-content-wrap').append('<span class="close-filter">Close Filter</span>');
    
    $('.offcanvase-filter-wrap .close-filter').on('click', function(){
        $('.offcanvase-filter-wrap').removeClass('is-active');
    });
    
    $('.site-header .left-content .ham-icon').on('click', function(e){
        $(this).toggleClass('is-triggred');
        $('.site-header .right-content .main-navigation').toggleClass('is-open');
        $('.site-header .left-content').toggleClass('move');
        e.stopPropagation();
    });


    $('body').on('click', function(){
        $('.site-header .right-content .main-navigation').removeClass('is-open');
        $('.site-header .left-content').removeClass('move');
        $('.site-header .left-content .ham-icon').removeClass('is-triggred');
        $('.profile-menu-wrapper .ham-icon').removeClass('is-triggred');
        $('.profile-menu-tab').removeClass('is-open');
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#toTop').addClass('slide-in');
        } else {
            $('#toTop').removeClass('slide-in');
        }
    });
    $('#toTop').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });

});
