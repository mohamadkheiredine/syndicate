$(document).ready(function () {
    // Initialize Datatable
    $(".datatable").each(function () {
        $(this).DataTable({
            aaSorting: [], // Disable auto sorting
            columnDefs: [
                {
                    targets: "no-sort",
                    orderable: false,
                },
            ],
            initComplete: function (settings, json) {
                $(this).addClass("table-responsive");
            },
        });
    });

    // Text Counter
    $("input").each(function () {
        var maxLength = $(this).attr("maxlength");

        // Set Counter Initialy
        var length = $(this).val().length;
        $(this).closest("div").find(".length-js span").text(length);

        // Update Counter
        $(this).keyup(function () {
            var length = $(this).val().length;
            $(this).closest("div").find(".length-js span").text(length++);
        });
    });

    // Textarea Counter
    $("textarea").each(function () {
        var maxLength = $(this).attr("maxlength");

        // Set Counter Initialy
        var length = $(this).val().length;
        $(this).closest("div").find(".length-js span").text(length);

        // Update Counter
        $(this).keyup(function () {
            var length = $(this).val().length;
            $(this).closest("div").find(".length-js span").text(length++);
        });
    });

    // Initialize Select2
    $(".select2-custom").each(function () {
        $(this).select2();
    });

    // Create and Edit Pages Slugify title
    $(".slugify_title").keyup(function () {
        var value = $(this).val();
        value = value
            .toLowerCase()
            .replace(/ +/g, "-")
            .replace(/[^\w-]+/g, "");
        $(".slugify_slug").val(value);
    });

    // Preview image
    $(".file-input-js").change(function () {
        readURL(this, $(this));
    });

    // Initialize quill
    if ($(".quill").length > 0) {
        quilljs_textarea(".quill", {
            modules: {
                toolbar: [
                    [{ size: ["small", false, "large", "huge"] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    [{ indent: "-1" }, { indent: "+1" }],
                    ["bold", "italic", "underline", "strike"],
                    [{ script: "sub" }, { script: "super" }],
                    [{ list: "ordered" }, { list: "bullet" }, { align: [] }],
                    [{ color: [] }, "link", "image"],
                    ["clean", { direction: "rtl" }],
                ],
            },
            theme: "snow",
        });
    }

    if ($('.tinymce').length > 0) {
        console.log('here')
        tinymce.init({
            selector: '.tinymce',
            branding: false,  // This will remove the branding/badge
            promotion: false,
            relative_urls : false,
            convert_urls: false,
            plugins: [
                'table',  // This enables table functionality
                'image',   // This enables image functionality
                'link',  // This enables link functionality
                'code' // This enables code functionality
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | table | image | link | code',  // This adds table and image controls to the toolbar
            // table_appearance_options: false,  // This disables some of the legacy table appearance controls.
            images_upload_url: '/admin/upload-image',  // Replace with your image upload URL
            automatic_uploads: true,  // Enable automatic uploads
            file_picker_types: 'image',
            file_picker_callback: function (cv, value, meta){
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function(){
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function(){
                        var id = 'blobid'+(new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {title:file.name});
                    };
                };
                input.click();
            },
        });
    }

    // Order Page
    $(".sortable").each(function () {
        $(this).sortable({
            update: function (event, ui) {
                $(".sortable .sortable-row").each(function (i) {
                    $(this)
                        .find('[name="pos[]"]')
                        .val(i + 1);
                });
            },
        });
    });
});

// Preview image functions
function readURL(input, $this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $this
                .closest(".form-group")
                .find(".image-preview-js")
                .attr("src", e.target.result);
            $this
                .closest(".form-group")
                .find(".image-preview-js")
                .removeClass("d-none");
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        $this.closest(".form-group").find(".image-preview-js").attr("src", "");
        $this
            .closest(".form-group")
            .find(".image-preview-js")
            .addClass("d-none");
    }
}
