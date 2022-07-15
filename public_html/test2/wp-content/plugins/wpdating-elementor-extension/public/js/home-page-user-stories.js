var wpeeTestimonials = ($scope, $) => {
    $(".testimonials_section").slick({
        centerMode: true,
        slidesToShow: 3,
        arrows:false,
        dots:true,
        autoplay:true,
        autoplaySpeed: 4000,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }
        ]
    });
};