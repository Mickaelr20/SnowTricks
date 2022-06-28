function showPreview(event) {
    if (event.target.files.length > 0) {
        let file = event.target.files[0];
        let src = URL.createObjectURL(file);
        let preview = $(".file-uploader .thumbnail-preview");
        preview.attr("src", src);
        preview.removeClass("d-none");
        let path = $(".file-uploader .thumbnail-path");
        path.text(file.name);
        path.removeClass("d-none");
    }
}

$(function () {
    $('.file-uploader input[type="file"]').on("change", function (event) {
        showPreview(event);
    });

    $('.file-uploader *:not(input[type="file"])').on("click", function () {
        $(this).closest('.file-uploader').find('input[type="file"]').click();
    });

    // set default empty preview
    let preview = $(".file-uploader .thumbnail-preview");
    if (!preview.attr("src")) {
        preview.attr("src", "/images/empty_image.png");
        preview.removeClass("d-none");
    }
});