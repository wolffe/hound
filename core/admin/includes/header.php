<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard - Hound CMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.13/tinymce.min.js"></script>
<script src="tinymceplugin/plugin.min.js"></script>
<script src="js/jquery-functions.js"></script>
<script>
tinymce.init({
    selector: '#txtTextArea1',
    branding: false,

    extended_valid_elements : "*[*]",
    valid_elements: "*[*]",

    image_caption: true,
    image_advtab: true,
    media_live_embeds: true,

    relative_urls: false,
    menubar: 'edit insert format table',

    plugins: [
        "advlist autolink lists link image imagetools hr preview",
        "searchreplace code save paste codesample",
        "media table contextmenu mediamanager textcolor colorpicker textpattern"
    ],
    toolbar1: "mediamanager image | undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | shortcodes forecolor backcolor | link | codesample | code preview",

    setup : function(ed) {
        ed.addButton('shortcodes', {
            type: 'listbox',
            text: 'Shortcodes',
            icon: false,
            onselect: function(e) {
                ed.insertContent(this.value());
            },
            values: [
                {text: 'Gallery', value: '[gallery "/path/to/directory/"]'},
            ]
        });
    },
});
</script>

<link href="css/thin-ui.css" rel="stylesheet">
</head>
<body>
