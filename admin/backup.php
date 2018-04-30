<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            function zipData($source, $destination) {
                if (extension_loaded('zip')) {
                    if (file_exists($source)) {
                        $zip = new ZipArchive();
                        if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                            $source = realpath($source);
                            if (is_dir($source)) {
                                $iterator = new RecursiveDirectoryIterator($source);
                                $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
                                $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
                                foreach ($files as $file) {
                                    $file = realpath($file);
                                    if (is_dir($file)) {
                                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                    } else if (is_file($file)) {
                                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                    }
                                }
                            } else if (is_file($source)) {
                                $zip->addFromString(basename($source), file_get_contents($source));
                            }
                        }
                        return $zip->close();
                    }
                }
                return false;
            }

            if (isset($_POST['backup'])) {
                $backupSite = 'backup-site-' . date('Y-m-d-') . substr(md5(microtime()), rand(0, 26), 5) . '.zip';
                $backupFiles = 'backup-files-' . date('Y-m-d-') . substr(md5(microtime()), rand(0, 26), 5) . '.zip';

                if (zipData('../site/', '../backup/' . $backupSite) !== false) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Site backup completed successfully.</div>';
                }
                if (zipData('../files/', '../backup/' . $backupFiles) !== false) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Files backup completed successfully.</div>';
                }
            }
            ?>

            <h2>Backup</h2>

            <p>Backup your site's content and templates. Note that you should regularly remove the files in your <code>/backup/</code> directory and store them offsite.</p>

            <table data-table-theme="default zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>File Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $files = glob('../backup/*.zip');
                    usort($files, 'hound_compare');

                    for($i=0; $i<count($files); $i++) {
                        $backup = $files[$i];
                        echo '<tr>
                            <td><a href="' . $backup . '">' . $backup . '</a></td>
                            <td><small>' . date('F d Y H:i:s', filemtime($backup)) . '<br>' . formatSizeUnits(filesize($backup)) . '</small></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <form action="" method="post">
                <p><button type="submit" class="thin-ui-button thin-ui-button-primary" name="backup">Backup</button></p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}
