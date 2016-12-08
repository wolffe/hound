<?php
session_start();

include '../config.php';
include '../libs/hound.php';
include 'libs/houndAdmin.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$houndAdmin = new houndAdmin('', '');
$param = $houndAdmin->read_param('../site/config.txt');

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="toolbar">
            <a href="../" target="_blank"><i class="fa fa-fw fa-external-link-square" aria-hidden="true"></i> Site preview</a> &nbsp;&nbsp;
            <a href="logout.php"><i class="fa fa-fw fa-sign-out" aria-hidden="true"></i> Logout</a>
        </div>
        <div class="content main">
            <?php
            $hound = new hound('', '');
            $param = $hound->read_param('../site/config.txt');
            ?>

            <h2>Dashboard</h2>

            <?php
            hound_update_check();

            if(is_writable('../site')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Content (templates and pages) folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Content (templates and pages) folder is not writable.</div>';
            }

            if(is_writable('../files')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Uploads folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Uploads folder is not writable.</div>';
            }

            $memoryAvailable = filter_var(ini_get("memory_limit"), FILTER_SANITIZE_NUMBER_INT);
            $memoryAvailable = $memoryAvailable * 1024 * 1024;
            ?>

            <br>
            <?php
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> cURL is ', function_exists('curl_version') ? 'enabled (' . curl_version()['version'] . '/' . curl_version()['host'] . '/' . curl_version()['ssl_version'] . ')</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> <code>file_get_contents()</code> is ', file_get_contents(__FILE__) ? 'enabled</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> Current theme folder is <code>/' . $param['template'] . '/</code></div>';
            ?>

            <p>
                You are using Hound <strong><?php echo HOUND_VERSION; ?></strong> on PHP <?php echo PHP_VERSION; ?>.
                <br><small>Current memory usage is <?php echo convert(memory_get_usage(true)); ?> (<?php echo convert(memory_get_peak_usage(true)); ?>) out of <?php echo convert($memoryAvailable); ?> allocated.</small>
            </p>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}