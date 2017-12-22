<?php
/*
Plugin Name: Fotorama
Plugin URI: https://getbutterfly.com/hound/plugins/fotorama
Description: Display a gallery carousel with next/previous controls and thumbnail navigation.
Version: 1.0.0
Author: Ciprian Popescu
Author URI: https://getbutterfly.com/
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Fotorama
Copyright (C) 2017, 2018 Ciprian Popescu (getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
 *
 * Usage: [gallery "/path/to/gallery/"]
 */
add_listener('content', 'pluginGallery');

function pluginGallery($args) {
    $config = hound_read_parameter('site/config.txt');

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
