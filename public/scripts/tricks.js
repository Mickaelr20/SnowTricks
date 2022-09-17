var videoCollectionHolder = $("#trick_edit_videos");
var videoIndex = videoCollectionHolder.find(".video-card").length

var imageCollectionHolder = $("#trick_edit_images");
var imageIndex = videoCollectionHolder.find(".image-card").length

function addVideo() {
    let prototype = videoCollectionHolder.data("prototype");
    prototype = prepareVideoItem(prototype);
    let element = $(prototype);
    element.find(".video-remove").click(function () {
        removeVideo($(this));
    });
    element.find('[id$="_link"]').change(function () {
        displayVideoPreview($(this));
    });
    videoCollectionHolder.append(element);
}

function removeVideo(button) {
    $(button).closest(".video-card").remove();
}

function prepareVideoItem(item) {
    let newItem = item.replace(/__name__/g, videoIndex);
    videoIndex++;
    return newItem;
}

function addImage() {
    let prototype = imageCollectionHolder.data("prototype");
    prototype = prepareImageItem(prototype);
    let element = $(prototype);
    element.find(".image-remove").click(function () {
        removeImage($(this));
    });
    imageCollectionHolder.append(element);
    initUploader(element);
}

function removeImage(button) {
    $(button).closest(".image-card").remove();
}

function prepareImageItem(item) {
    let newItem = item.replace(/__name__/g, videoIndex);
    videoIndex++;
    return newItem;
}

function displayVideoPreview(url_input) {
    let url = url_input.val();
    let encoded_url = encodeURIComponent(url);
    let div = url_input.parents().eq(2).find('.video-preview');

    $.ajax({
        url: "/trick/video_preview",
        data: {
            url: encoded_url
        },
        type: "GET",
        success: function (data) {
            console.log(div);
            div.html(data);
        }
    });
}

$(function () {
    initUploader($("#formThumnbail"));

    $(".image-card .image-preview").each(function () {
        initUploader($(this));
    });

    $("#trick_edit_name").on("keyup change  ", function () {
        let generatedSlug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $("#trick_edit_slug").val(generatedSlug);
    });

    $("#addVideo").click(addVideo);
    $("#addImage").click(addImage);

    $(".video-remove").click(function () {
        removeVideo($(this));
    });

    $(".video-card .video-preview").each(function () {
        let div = $(this);
        let url_input = div.parent().find('.card-body [id$="_link"]');
        let url = url_input.val();
        var encoded_url = encodeURIComponent(url);

        url_input.change(function () {
            displayVideoPreview($(this));
        });

        $.ajax({
            url: "/trick/video_preview",
            data: {
                url: encoded_url
            },
            type: "GET",
            success: function (data) {
                console.log(div);
                div.html(data);
            }
        });
    $(".image-remove").click(function () {
        removeImage($(this));
    });
});
