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

    image_caption: true,
    image_advtab: true,
    media_live_embeds: true,
    theme: 'modern',

    relative_urls: false,
    remove_script_host: false,
    forced_root_block : false,
    force_br_newlines : true,
    force_p_newlines : false,
    content_css: 'css/thin-ui.css',
    plugins: [
        "advlist autolink lists link image imagetools charmap print hr preview anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media table contextmenu paste mediamanager textcolor colorpicker textpattern",
        "emoticons nonbreaking save paste table contextmenu directionality template",
        "codesample"
    ],
    toolbar1: "mediamanager | undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | shortcodesandro forecolor backcolor emoticons | link image media | code",
    toolbar2: "insertfile insert codesample | print preview | mybutton",

    style_formats: [
        { title: 'Bold text', inline: 'strong' },
        { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
        { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
        { title: 'Badge', inline: 'span', styles: { display: 'inline-block', border: '1px solid #2276d2', 'border-radius': '5px', padding: '2px 5px', margin: '0 2px', color: '#2276d2' } },
        { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
    ],

    templates: [
        { title: 'Test template 1', content: 'Test 1' },
        { title: 'Test template 2', content: 'Test 2' }
    ],

    setup : function(ed) {
      
      ed.addButton('shortcodesandro', {
         type: 'listbox',
         text: 'shortcodes',
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

           ed.addButton('mybutton', {
      text: 'My button',
      icon: false,
      onclick: function () {
        ed.insertContent('&nbsp;<b>It\'s my button!</b>&nbsp;');
      }
    });
  },
 
    
});
</script>

<link href="css/thin-ui.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
</head>
<body>
