<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <h2>Dashboard</h2>

            <div class="thin-ui-flex-grid">
                <div class="thin-ui-infobox">
                    <?php houndUpdateCheck(); ?>
                </div>
            </div>

            <div class="thin-ui-flex-grid">
                <div class="hound-at-a-glance thin-ui-infobox">
                    <h3>At a Glance</h3>
                    <div class="inside">
                        <strong><?php echo hound_count_content('post'); ?></strong> post(s)<br>
                        <strong><?php echo hound_count_content('page'); ?></strong> page(s)<br>
                        <strong><?php echo hound_count_content('menu'); ?></strong> menu item(s)<br>
                        <strong><?php echo hound_count_content('backup'); ?></strong> backup file(s)<br>
                        <strong><?php echo hound_count_content('asset'); ?></strong> asset(s) (documents and images)
                    </div>
                </div>

                <div class="thin-ui-infobox">
                    <h3>Activity</h3>
                    <div class="inside">
                        <p>
                            <small>You are using Hound <strong><?php echo HOUND_VERSION; ?></strong> on PHP <?php echo PHP_VERSION; ?></small>
                            <br><small>Current memory usage is <?php echo houndGetMemory('usage'); ?> (<?php echo houndGetMemory('peak'); ?>) out of <?php echo houndGetMemory('available'); ?> allocated</small>
                            <?php if (function_exists('sys_getloadavg')) {
                                $load = sys_getloadavg();
                                ?><br><small><?php echo 'Current CPU load is ' . implode(', ', $load); ?></small>
                            <?php } ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="thin-ui-flex-grid">
                <div class="thin-ui-infobox">
                    <h3>Compatibility</h3>
                    <div class="inside">
                        <?php
                        if (houndCheckWritePermissions('../../content/site')) {
                            echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Content (templates and pages) folder is writable.</div>';
                        } else {
                            echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Content (templates and pages) folder is not writable.</div>';
                        }

                        if (houndCheckWritePermissions('../../content/files')) {
                            echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Uploads folder is writable.</div>';
                        } else {
                            echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Uploads folder is not writable.</div>';
                        }

                        if (houndCheckWritePermissions('../../content/files/update')) {
                            echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Update folder is writable.</div>';
                        } else {
                            echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Update folder is not writable.</div>';
                        }

                        if (class_exists('ZipArchive') || extension_loaded('zip')) {
                            echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Zip functionality is available.</div>';
                        } else {
                            echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Zip functionality is not available. Backups and automatic updates will not work.</div>';
                        }
                        ?>

                        <br>
                        <?php
                        echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> cURL is ', function_exists('curl_version') ? 'enabled (' . curl_version()['version'] . '/' . curl_version()['host'] . '/' . curl_version()['ssl_version'] . ')</div>' : 'disabled</div>';
                        echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> <code>file_get_contents()</code> is ', file_get_contents(__FILE__) ? 'enabled</div>' : 'disabled</div>';
                        echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> Current theme folder is <code>/' . hound_get_parameter('template') . '/</code></div>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
