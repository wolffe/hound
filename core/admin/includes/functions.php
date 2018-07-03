<?php
include_once '../core/hound.php';

function writeParam($arrayvalue, $file) {
    $current = '';
    foreach ($arrayvalue as $value => $key) {
        $current .= "$value: $key\n";
    }

    if (file_put_contents($file, $current)) {
        return 1;
    } else {
        return 0;
    }
}

function get_title($title = '') {
    echo trim($title);
}

function convert($size) {
    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

    return round($size/pow(1024, ($convert = floor(log($size, 1024)))), 2) . $unit[$convert];
}

function houndSizeConversion($size) {
    $unit = array('B','KB','MB','GB','TB','PB');

    return round($size/pow(1024, ($convert = floor(log($size, 1024)))), 2) . $unit[$convert];
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



function recurse_copy($src, $dst) {
    $dir = opendir($src);
    if (!is_dir($dst)) {
        mkdir($dst);
    }
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                echo 'Copying ' . $src . '/' . $file . ' to ' . $dst . $file . '<br>';
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }

    closedir($dir);
}

function deleteDir($path) {
    if (empty($path)) { 
        return false;
    }
    return is_file($path) ?
            unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == rmdir($path);
}

function houndGetContents($url) {
    if (function_exists('curl_exec')) {
        $conn = curl_init($url);

        curl_setopt($conn, CURLOPT_USERAGENT, 'Hound');
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($conn, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        $urlGetContentsData = (curl_exec($conn));
        curl_close($conn);
    } else if (function_exists('file_get_contents')) {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "User-Agent: Hound",
            )
        );
        $urlGetContentsData = file_get_contents($url, false, $context);
    } else if (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = fopen ($url, "r");
        $urlGetContentsData = stream_get_contents($handle);
    } else {
        $urlGetContentsData = false;
    }

    return $urlGetContentsData;
}

// Version Control
function houndUpdateCheck() {
    $current_releases = houndGetContents("https://api.github.com/repos/wolffe/hound/releases");

    if ($current_releases !== false) {
        $releases = json_decode($current_releases);
        $latest_release = $releases[0]->tag_name;
        $latest_release = str_replace('v', '', $latest_release);

        if (version_compare($latest_release, HOUND_VERSION) >= 1) {
            if (isset($_GET['update'])) {
                if (!is_dir('../tmp')) {
                    mkdir('../tmp'); 
                }

                copy('https://github.com/wolffe/hound/archive/v' . $latest_release . '.zip', '../tmp/' . $latest_release . '.zip');
                $zip = new ZipArchive;
                $res = $zip->open('../tmp/' . $latest_release . '.zip');
                if ($res === true) {
                    $zip->extractTo('../tmp');
                    $zip->close();

                    $src = '../tmp/hound-' . $latest_release;
                    //$dst = '../files/update';
                    $dst = '../';

                    // Delete old files
                    deleteDir($dst . 'admin');
                    deleteDir($dst . 'libs');
                    //

                    // Delete unneccesary files from the downloaded package
                    deleteDir($src . '/files');
                    deleteDir($src . '/site');

                    unlink($src . '/.htaccess');
                    unlink($src . '/config.php');
                    //

                    recurse_copy($src, $dst);
                    deleteDir('../tmp');

                    $file = '../site/config.txt';
                    $arrayvalue = array(
                        'Title' => houndGetParameter('title'),
                        'Template' => houndGetParameter('template'),
                        'Version' => $latest_release,
                    );

                    if (writeParam($arrayvalue, $file)) {
                        echo '<div class="thin-ui-notification thin-ui-notification-success">Hound sucessfully updated.</div>';
                    } else {
                        echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while updating Hound.</div>';
                    }
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while updating Hound.</div>';
                }
            }

            echo '<div class="thin-ui-notification thin-ui-notification-info">
                <b>An updated version of Hound is available!</b>
                <br><em>Important: before updating, please backup your files. For help with updates, visit the <a href="https://getbutterfly.com/hound/updating-hound/" target="_blank">Updating Hound</a> page.</em>
            </div>';

            echo '<p>
                <a href="https://github.com/wolffe/hound/releases" class="thin-ui-button thin-ui-button-primary" target="_blank">Download latest version (' . $latest_release . ')</a>
                or <a href="?update" class="thin-ui-button thin-ui-button-primary">Update automatically</a>
            </p>';
        } else {
            echo '<p>You have the latest version of Hound.</p>';
        }
    } else {
        echo '<p><strong>An error occured while checking for the latest version of Hound.</strong></p>';
    }
}

/**
 * Replace a string in a file
 * 
 * @since 0.2.4
 * @author Ciprian Popescu
 * 
 * @param string $filename File name
 * @param string $stringToReplace String to find
 * @param string $replaceWith String to replace
 */
function replaceStringInFile($filename, $stringToReplace, $replaceWith) {
    $content = file_get_contents($filename);
    $contentChunks = explode($stringToReplace, $content);
    $content = implode($replaceWith, $contentChunks);

    file_put_contents($filename, $content);
}

/**
 * Format filesize units
 * 
 * @since 0.2.4
 * @author Ciprian Popescu
 * 
 * @param string $bytes File size
 * @return string
 */
function formatSizeUnits($bytes) {
    $bytes = (int) $bytes;

    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } else if ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } else if ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } else if ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } else if ($bytes === 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

/**
 * Check UNIX write permissions
 * 
 * @since 0.1.4
 * @author Ciprian Popescu
 * 
 * @param string $path Directory path
 * @return bool
 */
function houndCheckWritePermissions($path) {
    $path = (string) trim($path);

    if (is_writable($path)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check OS memory
 * 
 * @since 0.1.4
 * @author Ciprian Popescu
 * 
 * @param string $type Memory check type (available, peak or current usage)
 * @return string
 */
function houndGetMemory($type = 'usage') {
    if ((string) $type === 'available') {
        $memoryAvailable = filter_var(ini_get("memory_limit"), FILTER_SANITIZE_NUMBER_INT);
        $memoryAvailable = $memoryAvailable * 1024 * 1024;
        $size = (int) $memoryAvailable;
    } elseif ((string) $type === 'peak') {
        $size = (int) memory_get_peak_usage(true);
    } elseif ((string) $type === 'usage') {
        $size = (int) memory_get_usage(true);
    } else {
        $size = 0;
    }

    return houndSizeConversion($size);
}

/**
 * Sanitize string
 * 
 * @since 0.1.4
 * @author Ciprian Popescu
 * 
 * @param string $name Name of parameter from configuration file
 * @return string
 */
function houndSanitizeString($string) {
    $string = (string) trim($string);
    $string = filter_var($string, FILTER_SANITIZE_STRING);

    return $string;
}

function houndGetIp() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}

/**
 * Login check
 *
 * Checks if user is logged in.
 * 
 * @since 0.7.0
 * @author Ciprian Popescu
 * 
 * @return bool
 */
function houndCheckLogin() {
    $temppass = $_SESSION['temppass'];

    if ((string) $temppass !== HOUND_PASS) {
        php_redirect('index.php?err=1');
        die('No access.');
    }
}

function makeSafe( $file ) {
    return str_replace( '..', '', urldecode( $file ) );
}
