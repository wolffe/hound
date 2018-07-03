<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$which = '';
if (isset($_GET['which'])) {
    $which = houndSanitizeString($_GET['which']);
}

$houndAdmin = new hound('', '');

if ((string) $temppass === HOUND_PASS) {
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

            if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
                $file = '../../content/site/pages/' . $type . '-' . $which . '.txt';

                if (unlink($file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">' . ucwords($type) . ' deleted successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting ' . $type . '.</div>';
                }
            } else if (isset($_GET['op']) && (string) $_GET['op'] === 'copy') {
                $file = '../../content/site/pages/' . $type . '-' . $which . '.txt';
                $filecopy = '../../content/site/pages/' . $type . '-' . $which . '-copy.txt';

                if (copy($file, $filecopy)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">' . ucwords($type) . ' copied successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while copying ' . $type . '.</div>';
                }
            }
            ?>
            <h2>Content</h2>
            <div>
                <a href="new.php?type=<?php echo $type; ?>" class="thin-ui-button thin-ui-button-primary">New <?php echo $type; ?></a>
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
                    $fileindir = $houndAdmin->get_files('../../content/site/pages/');
                    foreach ($fileindir as $file) {
                        if (preg_match("/\b$type\b/i", $file)) {
                            $parampage = hound_read_parameter($file);
                            //$listofpage[$i]['title'] = $parampage['title'];
                            //$listofpage[$i]['url'] = $parampage['url'];
                            //$listofpage[$i]['slug'] = $parampage['slug'];
                            $nameofpage = str_replace('../../content/site/pages/', "", $file);
                            $nameofpage = str_replace($type . '-', "", $nameofpage);
                            $nameofpage = str_replace('.txt', "", $nameofpage);
                            //$i++;

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
                                echo '<td><code>' . str_replace('.php', '', $parampage['template']) . '</code></td>';
                                echo '<td><small>' . date('F d Y H:i:s', filemtime($file)) . ' <code>' . formatSizeUnits($fileinfo['size']) . '</code></small></td>';
                                echo '<td>
                                    <a href="../../' . $parampage['slug'] . '">View</a> | ';
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
