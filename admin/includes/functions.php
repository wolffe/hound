<?php
function get_title($title = '') {
    echo trim($title);
}

function convert($size) {
    $unit = array('B','KB','MB','GB','TB','PB');

    return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
}

function php_redirect($url) {
    // Get and append query string
    if(!empty($_SERVER['QUERY_STRING'])) {
        $url .= '?' . $_SERVER['QUERY_STRING'];
    }

    $content = sprintf('<!doctype html><html class="no-js"><head><meta charset="utf-8"><meta http-equiv="refresh" content="0;url=%1$s"><title>Redirecting to %1$s...</title></head><body>Redirecting to <a href="%1$s">%1$s</a>...</body></html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

    header('Location: ' . $url, true, 301);
    die($content);
}

// Version Control
function hound_update_check() {
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "User-Agent: hound",
        )
    );
    $context = stream_context_create($opts);
    $current_releases = file_get_contents("https://api.github.com/repos/wolffe/hound/releases", false, $context);

    if($current_releases !== false) {
        $releases = json_decode($current_releases);
        $latest_release = $releases[0]->tag_name;
        $latest_release = str_replace('v', '', $latest_release);

        if(version_compare($latest_release, HOUND_VERSION) >= 1) {
            echo '<p>
                <strong>An updated version of Hound is available.</strong>
                <br><em>Important: before upgrading, please backup your database and files. For help with updates, visit the Updating Hound page.</em>
            </p>
            <p><a href="https://github.com/wolffe/hound/releases" class="thin-ui-button thin-ui-button-primary" target="_blank">Get latest version (' . $latest_release . ')</a></p>';
        } else {
            echo '<p><strong>You have the latest version of Hound.</strong></p>';
        }
    } else {
        echo '<p><strong>An error occured while checking for the latest version of Hound.</strong></p>';
    }
}
