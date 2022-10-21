import '../styles/trick_edit.scss';
import '../styles/file_uploader.scss';
import FileUploader from './file_uploader';

var videoCollectionHolder = $("#trick_edit_videos");
var videoIndex = videoCollectionHolder.find(".video-card").length

var imageCollectionHolder = $("#trick_edit_images");
var imageIndex = videoCollectionHolder.find(".image-card").length

function addVideo() {
    let prototype = videoCollectionHolder.data("prototype");
    prototype = prepareVideoItem(prototype);
    let element = $(prototype);
    element.find(".video-remove").on('click', function () {
        removeVideo($(this));
    });
    element.find('[id$="_link"]').on('click', function () {
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
    element.find(".image-remove").on('click', function () {
        removeImage($(this));
    });
    imageCollectionHolder.append(element);
    FileUploader.initUploader(element);
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
    FileUploader.initUploader($("#formThumnbail"));

    $(".image-card .image-preview").each(function () {
        FileUploader.initUploader($(this));
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

    $("#addVideo").on('click', addVideo);
    $("#addImage").on('click', addImage);

    $(".video-remove").on('click', function () {
        removeVideo($(this));
    });

    $(".image-remove").on('click', function () {
        removeImage($(this));
    });
});
