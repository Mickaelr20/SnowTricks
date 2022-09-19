var page = 1;
var pageLength = 6;

$(function () {
    $("#load_more_buttons .load-more").on('click', function () {
        $("#load_more_buttons .load-more").addClass("d-none");
        load_more_tricks();
        $("#load_more_buttons .loading-more").removeClass("d-none");
    });


});

function load_more_tricks() {
    $.ajax({
        url: "/trick/load_more/" + page,
        type: "GET",
        success: function (data) {
            let nbResults = 0;

            if (data) {
                let jdata = $(data);
                console.log(jdata);
                
                for (let i = 0; i < jdata.length; i++) {
                    let e = $(jdata[i]);

                    if (e.is("div")) {
                        $("#tricks .row").append(e);
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