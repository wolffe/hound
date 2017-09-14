<?php
function pluginHoundSlider($pattern, $content, $template) {
    preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE, 3);

    if (!empty($matches)) {
        $files = glob('site/templates/' . $template . '/' . str_replace('"', '', trim($matches[1][0])) . '*.*');
        $gstring = '<div class="lightSliderOuter"><ul id="lightSlider">';

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
                $gstring .= '<li data-thumb="' . $image . '">
                    <img src="' . $image . '" width="' . $width . '" height="' . $height. '" title="' . $gtitle . '" alt="">
                    <div class="lightCaption">' . $gtitle . '</div>
                </li>';
            } else {
                continue;
            }
        }
        $gstring .= '</ul></div>';
        $content = preg_replace($pattern, $gstring, $content);
    }

    return $content;
}
