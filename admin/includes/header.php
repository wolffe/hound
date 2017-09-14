<!doctype html>
<html lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Dashboard - Hound CMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.6/tinymce.min.js"></script>
<script src="tinymceplugin/plugin.min.js"></script>
<script src="js/jquery-functions.js"></script>
<script>
tinymce.init({
    selector: '#txtTextArea1',

    extended_valid_elements : "*[*]",
    valid_elements: "*[*]",

    image_caption: true,
    image_advtab: true,
    media_live_embeds: true,

    relative_urls: false,

    plugins: [
        "advlist autolink lists link image imagetools charmap print hr preview anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media table contextmenu paste mediamanager textcolor colorpicker textpattern",
        "emoticons nonbreaking save paste table contextmenu directionality template",
        "codesample"
    ],
    toolbar1: "mediamanager image media | undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | shortcodes forecolor backcolor emoticons | link | codesample | code preview",

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
