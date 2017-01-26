<?php
session_start();

include '../config.php';
include '../libs/hound.php';
include 'libs/houndAdmin.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = $_GET['page'];

$houndAdmin = new houndAdmin('', '');
$param = $houndAdmin->read_param('../site/config.txt');

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if($_GET['op'] == 'del') {
                $file = '../site/pages/post-' . $page . '.txt';
                if(unlink($file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Post deleted successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting post.</div>';
                }
            }

            if($_GET['op'] == 'copy') {
                $file = '../site/pages/post-' . $page . '.txt';
                $filecopy = '../site/pages/post-' . $page . '-copy.txt';

                if(copy($file, $filecopy)) {
                    echo '<div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Success</h3>
                        </div>
                        <div class="panel-body">
                            deleted
                        </div>
                    </div>';
                } else {
                    echo '<div class="panel panel-error">
                        <div class="panel-heading">
                            <h3 class="panel-title">Error</h3>
                        </div>
                        <div class="panel-body">
                            I/o error. ' . error_get_last() . '
                        </div>
                    </div>';
                }
            }
            ?>
            <h2>Posts</h2>
            <div>
                <a href="new-post.php" class="thin-ui-button thin-ui-button-primary">New post</a>
            </div>
            <br>

            <table data-table-theme="default zebra hd-sortable">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Template</th>
                        <th>File Details</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fileindir = $houndAdmin->get_files('../site/pages/');
                    foreach($fileindir as $file) {
                        if(preg_match("/\bpost\b/i", $file)) {
                            $parampage = $houndAdmin->read_param($file);
                            $listofpage[$i]['title'] = $parampage['title'];
                            //$listofpage[$i]['url'] = $parampage['url'];
                            $listofpage[$i]['slug'] = $parampage['slug'];
                            $nameofpage = str_replace('../site/pages/', "", $file);
                            $nameofpage = str_replace('post-', "", $nameofpage);
                            $nameofpage = str_replace('.txt', "", $nameofpage);
                            $i++;

                            $fileinfo = stat($file);
                            echo '<tr>
                                <td>';
                                    if($parampage['slug'] == "index") {
                                        echo '<i class="fa fa-fw fa-home" aria-hidden="true"></i> ';
                                    }
                                    if(preg_match("/\bcopy\b/i", $file)) {
                                        echo '<i class="fa fa-fw fa-files-o" aria-hidden="true"></i> ';
                                    }
                                    echo $parampage['title'];
                                    if(preg_match("/\bcopy\b/i", $file)) {
                                        echo ' (copy)';
                                    }
                                echo '</td>';
                                echo '<td>' . $parampage['slug'] . '</td>';
                                echo '<td><code>' . $parampage['template'] . '</code></td>';
                                echo '<td><small>' . date('F d Y H:i:s', filemtime($file)) . '<br>' . formatSizeUnits($fileinfo['size']) . '</small></td>';
                                echo '<td>
                                    <a href="../' . $parampage['slug'] . '">View</a> | 
                                    <a href="edit-post.php?page=' . $nameofpage . '">Edit</a> | ';
                                    if($parampage['slug'] != 'index') {
                                        echo '<a href="posts.php?op=copy&page=' . $nameofpage . '"> Copy</a> | ';
                                    }
                                    if($parampage['slug'] != 'index') {
                                        echo '<a style="color: #C0392B;" onclick="return confirm(\'Are you sure?\');" href="posts.php?op=del&page=' . $nameofpage . '"> Delete</a>';
                                    }
                                echo '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}