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

});