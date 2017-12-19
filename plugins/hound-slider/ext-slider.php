<?php
add_listener('content', 'pluginHoundSlider');

function pluginHoundSlider($args) {
    $content = $args;

    return $content;
}
