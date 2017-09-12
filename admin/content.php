<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$which = houndSanitizeString($_GET['which']);

$houndAdmin = new hound('', '');

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
			$type = houndSanitizeString($_GET['type']);
			$acceptedTypes = array('post', 'page');

			if (!in_array($type, $acceptedTypes)) {
				$type = 'page';

				echo '<div class="thin-ui-notification thin-ui-notification-error">Invalid item type. Switching to page type.</div>';
			}

            if ((string) $_GET['op'] === 'del') {
                $file = '../site/pages/' . $type . '-' . $which . '.txt';

                if (unlink($file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">' . ucwords($type) . ' deleted successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting ' . $type . '.</div>';
                }
            }

            if ($_GET['op'] === 'copy') {
                $file = '../site/pages/' . $type . '-' . $which . '.txt';
                $filecopy = '../site/pages/' . $type . '-' . $which . '-copy.txt';

                if (copy($file, $filecopy)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">' . ucwords($type) . ' copied successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while copying ' . $type . '.</div>';
                }
            }
            ?>
            <h2>Content</h2>
            <div>
                <a href="new.php?type=page" class="thin-ui-button thin-ui-button-primary">New <?php echo $type; ?></a>
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
                    foreach ($fileindir as $file) {
                        if (preg_match("/\b$type\b/i", $file)) {
                            $parampage = $houndAdmin->read_param($file);
                            $listofpage[$i]['title'] = $parampage['title'];
                            //$listofpage[$i]['url'] = $parampage['url'];
                            $listofpage[$i]['slug'] = $parampage['slug'];
                            $nameofpage = str_replace('../site/pages/', "", $file);
                            $nameofpage = str_replace($type . '-', "", $nameofpage);
                            $nameofpage = str_replace('.txt', "", $nameofpage);
                            $i++;

                            $fileinfo = stat($file);
                            echo '<tr>
                                <td>';
                                    if ($parampage['slug'] == "index") {
                                        echo '<i class="fa fa-fw fa-home" aria-hidden="true"></i> ';
                                    }
                                    if (preg_match("/\bcopy\b/i", $file)) {
                                        echo '<i class="fa fa-fw fa-files-o" aria-hidden="true"></i> ';
                                    }
                                    echo '<a href="edit.php?type=' . $type . '&which=' . $nameofpage . '">' . $parampage['title'] . '</a>';
                                    if (preg_match("/\bcopy\b/i", $file)) {
                                        echo ' (copy)';
                                    }
                                echo '</td>';
                                echo '<td>' . $parampage['slug'] . '</td>';
                                echo '<td><code>' . $parampage['template'] . '</code></td>';
                                echo '<td><small>' . date('F d Y H:i:s', filemtime($file)) . ' <code>' . formatSizeUnits($fileinfo['size']) . '</code></small></td>';
                                echo '<td>
                                    <a href="../' . $parampage['slug'] . '">View</a> | ';
                                    if ($parampage['slug'] != 'index') {
                                        echo '<a href="content.php?type=' . $type . '&op=copy&which=' . $nameofpage . '"> Clone</a> | ';
                                    }
                                    if ($parampage['slug'] != 'index') {
                                        echo '<a style="color: #C0392B;" onclick="return confirm(\'Are you sure?\');" href="content.php?type=' . $type . '&op=del&which=' . $nameofpage . '"> Delete</a>';
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
} else {
    php_redirect('index.php?err=1');
}
