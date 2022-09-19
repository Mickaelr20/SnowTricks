var carousel = $('.owl-carousel');

$(function () {
    carousel.owlCarousel({
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            500: {
                items: 2
            },
            1000: {
                items: 4
            },
            1800: {
                items: 8
            }
        }
    });
});