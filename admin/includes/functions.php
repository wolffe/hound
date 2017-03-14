<?php
function get_title($title = '') {
    echo trim($title);
}

function convert($size) {
    $unit = array('B','KB','MB','GB','TB','PB');

    return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
}
function houndSizeConversion($size) {
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



function recurse_copy($src,$dst) { 
    $dir = opendir($src);
    if ( !is_dir($dst)) {
        mkdir($dst); 
    }
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                echo '<br>copying ' . $src . '/' . $file . ' to ' . $dst . '/' . $file;
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
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

// Version Control
function houndUpdateCheck() {
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "User-Agent: hound",
        )
    );
    $context = stream_context_create($opts);
    $current_releases = file_get_contents("https://api.github.com/repos/wolffe/hound/releases", false, $context);

    if ($current_releases !== false) {
        $releases = json_decode($current_releases);
        $latest_release = $releases[0]->tag_name;
        $latest_release = str_replace('v', '', $latest_release);

        if (version_compare($latest_release, houndGetParameter('version')) >= 1) {
            if (isset($_GET['update'])) {
                if ( !is_dir('../tmp')) {
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

                    deleteDir($src . '/files');
                    deleteDir($src . '/site');
                    unlink($src . '/.htaccess');
                    unlink($src . '/config.php');
                    recurse_copy($src, $dst);
                    deleteDir('../tmp');

                    $houndAdmin = new houndAdmin('', '');   
                    $file = '../site/config.txt';
                    $arrayvalue = array(
                        'Title' => houndGetParameter('title'),
                        'Template' => houndGetParameter('template'),
                        'Slogan' => houndGetParameter('slogan'),
                        'Version' => $latest_release,
                    );

                    if ($houndAdmin->write_param($arrayvalue, $file)) {
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
            echo '<p><strong>You have the latest version of Hound.</strong></p>';
        }
    } else {
        echo '<p><strong>An error occured while checking for the latest version of Hound.</strong></p>';
    }
}

function houndUpdate() {
    //$version = file_get_contents('domain.com/repository/version.txt');
    $version = '0.1.7';
    if (version_compare($version, houndGetParameter('version'))) {
        copy('https://github.com/wolffe/hound/archive/v' . $version . '.zip', 'tmp/' . $version . '.zip');
        $zip = new ZipArchive;
        $res = $zip->open('tmp/' . $version . '.zip');
        if ($res === true) {
            $zip->extractTo('tmp');
            $zip->close(); 
            echo 'ok';
        } else {
            echo 'failed';
        }
    }
}







function replace_in_file($FilePath, $OldText, $NewText)
{
    $Result = array('status' => 'error', 'message' => '');
    if(file_exists($FilePath)===TRUE)
    {
        if(is_writeable($FilePath))
        {
            try
            {
                $FileContent = file_get_contents($FilePath);
                $FileContent = str_replace($OldText, $NewText, $FileContent);
                if(file_put_contents($FilePath, $FileContent) > 0)
                {
                    $Result["status"] = 'success';
                }
                else
                {
                   $Result["message"] = 'Error while writing file';
                }
            }
            catch(Exception $e)
            {
                $Result["message"] = 'Error : '.$e;
            }
        }
        else
        {
            $Result["message"] = 'File '.$FilePath.' is not writable !';
        }
    }
    else
    {
        $Result["message"] = 'File '.$FilePath.' does not exist !';
    }
    return $Result;
}

function replace_string_in_file($filename, $string_to_replace, $replace_with){
    $content=file_get_contents($filename);
    $content_chunks=explode($string_to_replace, $content);
    $content=implode($replace_with, $content_chunks);
    file_put_contents($filename, $content);
}




    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
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
 * Get configuration parameter
 * 
 * @since 0.1.4
 * @author Ciprian Popescu
 * 
 * @param string $name Name of parameter from configuration file
 * @return string
 */
function houndGetParameter($name) {
    $hound = new hound('', '');
    $parameter = $hound->read_param('../site/config.txt');

    return (string) $parameter[$name];
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
    $string = trim((string) $string);
    $string = filter_var($string, FILTER_SANITIZE_STRING);

    return $string;
}
