function showPreview(event, element) {
    console.log("sjow preview");
    if (event.target.files.length > 0) {
        let file = event.target.files[0];
        let filesize = file.size;
        let src = URL.createObjectURL(file);
        let preview = $(element).find(".thumbnail-preview");
        preview.attr("src", src);
        preview.removeClass("d-none");
        let sizeInMega = (filesize / (1024 ** 2)).toFixed(2);
        let path = $(element).find(".thumbnail-path");
        path.text(file.name + " <" + sizeInMega + "Mb>");
        path.removeClass("d-none");
    }
}

function initUploader(element) {
    console.log("init uploader");
    $(element).find('input[type="file"]').on("change", function (event) {
        showPreview(event, element);
    });

    // $(element).find('*:not(input[type="file"])').on("click", function () {
    //     console.log("On click");
    //     $(this).closest('.file-uploader').find('input[type="file"]').click();
    // });

    // set default empty preview
    let preview = $(element).find(".thumbnail-preview");
    if (!preview.attr("src")) {
        preview.attr("src", "/images/empty_image.png");
        preview.removeClass("d-none");
    }
}