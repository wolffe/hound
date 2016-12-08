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
        <div class="toolbar">
            <a href="../" target="_blank"><i class="fa fa-fw fa-external-link-square" aria-hidden="true"></i> Site preview</a> &nbsp;&nbsp;
            <a href="logout.php"><i class="fa fa-fw fa-sign-out" aria-hidden="true"></i> Logout</a>
        </div>
        <div class="content main">
            <?php
            if($_GET['op'] == 'del') {
                $file = '../site/pages/page-' . $page . '.txt';
                if(unlink($file)) {
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
                            I/o error
                        </div>
                    </div>';
                }
            }

            if($_GET['op'] == 'copy') {
                $file = '../site/pages/page-' . $page . '.txt';
                $filecopy = '../site/pages/page-' . $page . '-copy.txt';

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
            <h2>Pages</h2>
            <div>
                <a href="new-page.php" class="thin-ui-button thin-ui-button-primary">New page</a>
            </div>
            <br>

            <table data-table-theme="default zebra">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Template</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $fileindir = $houndAdmin->get_files('../site/pages/');
                    foreach($fileindir as $file) {
                        if(preg_match("/\bpage\b/i", $file)) {
                            $parampage = $houndAdmin->read_param($file);
                            $listofpage[$i]['title'] = $parampage['title'];
                            //$listofpage[$i]['url'] = $parampage['url'];
                            $listofpage[$i]['slug'] = $parampage['slug'];
                            $nameofpage = str_replace('../site/pages/', "", $file);
                            $nameofpage = str_replace('page-', "", $nameofpage);
                            $nameofpage = str_replace('.txt', "", $nameofpage);
                            $i++;

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
                                echo '<td><a href="../' . $parampage['slug'] . '">View</a></td>';
                                echo '<td><a href="edit-page.php?page=' . $nameofpage . '">Edit</a></td>';
                                echo '<td>';
                                    if($parampage['slug'] != 'index') {
                                        echo '<a href="pages.php?op=copy&page=' . $nameofpage . '"> Copy</a>';
                                    }
                                echo '</td>';
                                echo '<td>';
                                    if($parampage['slug'] != 'index') {
                                        echo '<a style="color: #C0392B;" onclick="return confirm(\'Are you sure?\');" href="pages.php?op=del&page=' . $nameofpage . '"> Delete</a>';
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