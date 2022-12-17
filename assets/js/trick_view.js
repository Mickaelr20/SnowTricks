import '../styles/trick_view.scss';
import '../styles/comment_view.scss';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';

var carousel = $('.owl-carousel');
var trickId = null;
var page = 1;
var pageLength = 5;

function load_more_comments() {
    console.log(trickId);
    $.ajax({
        url: "/trick/load_more_comments",
        data: {
            trickId: trickId,
            page: page
        },
        success: function (data) {
            console.log(data);
            let nbResults = 0;

            if (data) {
                let jdata = $(data);

                for (let i = 0; i < jdata.length; i++) {
                    let e = $(jdata[i]);

                    if (e.is("li")) {
                        $("#commentList").append(e);
                        nbResults += 1;
                    }
                }
                page += 1;
            }

            $("#load_more_buttons .load-more").removeClass("d-none");
            $("#load_more_buttons .loading-more").addClass("d-none");

            if (nbResults < pageLength) {
                $("#load_more_buttons .load-more").text('Pas plus de tricks à charger');
                $("#load_more_buttons .load-more").attr('disabled', true);
            }
        }
    });
}

$(function () {
    trickId = $('#trickViewData').data("trickId");

    $("#load_more_buttons .load-more").on('click', function () {
        $("#load_more_buttons .load-more").addClass("d-none");
        load_more_comments();
        $("#load_more_buttons .loading-more").removeClass("d-none");
    });

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