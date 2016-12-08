<!doctype html>
<html lang="">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Dashboard - Hound CMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.4.3/tinymce.min.js"></script>
<script src="tinymceplugin/plugin.min.js"></script>
<script src="js/jquery-functions.js"></script>
<script type="text/javascript">
//visualblocks_default_state: true,
tinymce.init({
    menubar: true,
    statusbar: true,
    //toolbar: false,
    mode : "exact",
    elements : "txtTextArea1",
    extended_valid_elements : "*[*]",
    valid_elements: "*[*]",
    browser_spellcheck : true,
    gecko_spellcheck: true,

    relative_urls: false,
    remove_script_host: false,
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false,
    content_css: 'css/thin-ui.css',
    plugins: [
        "advlist autolink  lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste mediamanager textcolor"
    ],
    toolbar: "mediamanager | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | shortcodesandro forecolor backcolor | link image code",

    setup : function(ed) {
      
      ed.addButton('shortcodesandro', {
         type: 'listbox',
         text: 'bootstrap',
         icon: false,
         onselect: function(e) {
             ed.insertContent(this.value());
            },
            values: [
                {text: '1 row - 1 columns', value: '<div class=\"row\"><div class=\"col-md-12\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: '1 row - 2 columns 6-6', value: '<div class=\"row\"><div class=\"col-md-6\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-6\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: '1 row - 2 columns 3-9', value: '<div class=\"row\"><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-9\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: '1 row - 2 columns 9-3', value: '<div class=\"row\"><div class=\"col-md-9\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: '1 row - 3 columns', value: '<div class=\"row\"><div class=\"col-md-4\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-4\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-4\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: '1 row - 4 columns', value: '<div class=\"row\"><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div><div class=\"col-md-3\">SOME CONTENT <br>SOME CONTENT</div></div><br>'},
                {text: 'Button success', value: '<br><a class=\"btn btn-lg btn-success\">button text</a><br><br>'},
                {text: 'Button warning', value: '<br><a class=\"btn btn-lg btn-warning\">button text</a><br><br>'},
                {text: 'Button info', value: '<br><a class=\"btn btn-lg btn-info\">button text</a><br><br>'},
                {text: 'Divider', value: '<br><br>'},
                {text: 'Paragraph', value: '<p><br></p>'}

            ]
      });
    }
});
</script>

<link href="css/thin-ui.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
</head>
<body>
