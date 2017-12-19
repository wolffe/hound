<?php
// https://cdnjs.com/libraries/fotorama
// http://fotorama.io/
// https://github.com/artpolikarpov/fotorama

// [gallery "/images/gallery-2014/"]
add_listener('content', 'pluginGallery');

function pluginGallery($args) {
    $config = hound::read_param('site/config.txt');

    $pattern = '/\[gallery(.*?)?\](?:(.+?)?\[\/gallery\])?/';
    $content = $args[0];
    $template = $config['template'];

    preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE, 3);

    if (!empty($matches)) {
        $files = glob('site/templates/' . $template . '/' . str_replace('"', '', trim($matches[1][0])) . '*.*');
        $gstring = '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="plugins/gallery/fotorama/fotorama.js"></script>
<link href="plugins/gallery/fotorama/fotorama.css" rel="stylesheet">
<link href="plugins/gallery/fotorama/fotorama-config.css" rel="stylesheet">

        <div class="fotorama" data-nav="thumbs" data-width="800" data-height="600" data-allowfullscreen="true" data-hash="true" data-loop="true">';

        for ($i=1; $i<count($files); $i++) {
            $image = $files[$i];
            $supportedFile = array(
                'gif',
                'jpg',
                'jpeg',
                'png',
            );
            $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
            if (in_array($ext, $supportedFile)) {
                $gtitle = ucwords(str_replace(array('-', '_'), ' ', pathinfo($image, PATHINFO_FILENAME)));
                $gstring .= '<img src="' . $image . '" title="' . $gtitle . '" data-caption="' . $gtitle . '" alt="">';
            } else {
                continue;
            }
        }
        $gstring .= '</div>';
        $content = preg_replace($pattern, $gstring, $content);
    }

    return $content;
}
