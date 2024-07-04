<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$which = '';
if (isset($_GET['which'])) {
    $which = houndSanitizeString($_GET['which']);
}

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            $type = houndSanitizeString($_GET['type']);
            $acceptedTypes = ['post', 'page'];

            if (!in_array($type, $acceptedTypes)) {
                $type = 'page';

                echo '<div class="thin-ui-notification thin-ui-notification-error">Invalid item type. Switching to page type.</div>';
            }

            if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
                $post_id_to_delete = (int) $_GET['which'];

                if (hound_delete_post($post_id_to_delete)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Post deleted successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting the post.</div>';
                }
            } elseif (isset($_GET['op']) && (string) $_GET['op'] === 'copy') {
                $post_id_to_clone = (int) $_GET['which'];

                if (hound_clone_post($post_id_to_clone)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Post copied successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while copying the post.</div>';
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
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Template</th>
                        <th>File Details</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $posts = hound_get_posts($type);

                    foreach ($posts as $post) {
                        echo '<tr>
                            <td>' . $post['post_id'] . '</td>
                            <td>';
                                if ((string) $post['post_slug'] === 'index') {
                                    echo '<i class="fa fa-fw fa-home" aria-hidden="true"></i> ';
                                }
                                //if (preg_match("/\bcopy\b/i", $file)) {
                                //    echo '<i class="fa fa-fw fa-files-o" aria-hidden="true"></i> ';
                                //}
                                echo '<a href="edit.php?type=' . $type . '&which=' . $post['post_id'] . '">' . $post['post_title'] . '</a>';
                                //if (preg_match("/\bcopy\b/i", $file)) {
                                //    echo ' (copy)';
                                //}
                            echo '</td>';
                            echo '<td>' . $post['post_slug'] . '</td>';
                            echo '<td><code>' . str_replace('.php', '', $post['post_template']) . '</code></td>';
                            echo '<td><small>' . date('F d Y H:i:s', strtotime($post['post_date'])) . '</small></td>';
                            //echo '<td><small>' . date('F d Y H:i:s', filemtime($file)) . ' <code>' . formatSizeUnits($fileinfo['size']) . '</code></small></td>';
                            echo '<td>
                                <a href="../../' . $post['post_slug'] . '">View</a> | ';
                                if ($post['post_slug'] != 'index') {
                                    echo '<a href="content.php?type=' . $type . '&op=copy&which=' . $post['post_id'] . '"> Clone</a> | ';
                                }
                                if ($post['post_slug'] != 'index') {
                                    echo '<a style="color: #C0392B;" onclick="return confirm(\'Are you sure?\');" href="content.php?type=' . $type . '&op=del&which=' . $post['post_id'] . '"> Delete</a>';
                                }
                            echo '</td>';
                        echo '</tr>';
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
