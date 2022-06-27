function showPreview(event) {
    if (event.target.files.length > 0) {
        let file = event.target.files[0];
        let src = URL.createObjectURL(file);
        let preview = $("#thumbnailPreview");
        preview.attr("src", src);
        preview.removeClass("d-none");
        let path = $("#thumbnailPath");
        path.text(file.name);
        path.removeClass("d-none");
    }
}

$(function () {
    $("#trick_edit_name").on("keyup change  ", function () {
        let generatedSlug = $(this).val()
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $("#trick_edit_slug").val(generatedSlug);
    });

    $("#trick_edit_thumbnail").on("change", function (event) {
        showPreview(event);
    });

    $('.file-uploader *:not(input[type="file"])').on("click", function () {
        $(this).closest('.file-uploader').find('input[type="file"]').click();
    });

    // set default empty preview
    let preview = $("#thumbnailPreview");
    if (!preview.attr("src")) {
        preview.attr("src", "/images/empty_image.png");
        preview.removeClass("d-none");
    }
});
