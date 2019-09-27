function copyText(element) {
    var copyText = document.getElementById(element);
    copyText.style.display = 'block';

    copyText.select();
    copyText.setSelectionRange(0, 99999);

    document.execCommand("copy");

    copyText.style.display = 'none';
}

function prettyPrint(element) {
    var ugly = element.value;
    var obj = JSON.parse(ugly);
    var pretty = JSON.stringify(obj, undefined, 4);

    element.value = pretty;
}

$(document).ready(function() {
    $('.select2').select2();
    $('.summernote').summernote({
        addclass: {
            debug: true,
            classTags: [
                {
                    title:"Button",
                    value:"btn btn-success"
                },
                "jumbotron",
                "lead",
                "img-rounded",
                "img-circle",
                "img-responsive",
                "btn",
                "btn btn-success",
                "btn btn-danger",
                "text-muted",
                "text-primary",
                "text-warning",
                "text-danger",
                "text-success",
                "table-bordered",
                "table-responsive",
                "alert",
                "alert alert-success",
                "alert alert-info",
                "alert alert-warning",
                "alert alert-danger",
                "visible-sm",
                "hidden-xs",
                "hidden-md",
                "hidden-lg",
                "hidden-print"
            ]
        },
        height: 400,
        toolbar: [
            // [groupName, [list of button]]
            ['img', ['picture']],
            ['style', ['style', 'addclass', 'clear']],
            ['fontstyle', ['bold', 'italic', 'ul', 'ol', 'link', 'paragraph']],
            ['fontstyleextra', ['strikethrough', 'underline', 'hr', 'color', 'superscript', 'subscript']],
            ['extra', ['video', 'table', 'height']],
            ['misc', ['undo', 'redo', 'codeview', 'help']]
        ]
    });
    $('.json').each(function (index, element) {
        prettyPrint(element);
    });
});

