<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$page = '';
if (isset($_GET['page'])) {
    $page = houndSanitizeString($_GET['page']);
}

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if (isset($_GET['op']) && (string) $_GET['op'] === "del") {
                if (hound_delete_node($page)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Menu item deleted successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while deleting menu item.</div>';
                }
            }
            ?>
            <h2>Menu</h2>
            <div>
                <a href="new-menu.php" class="thin-ui-button thin-ui-button-primary"><i class="fa fa-plus" aria-hidden="true"></i> New menu item</a>
            </div>

            <br>
            <table class="default zebra hd-sortable" data-table-theme="default zebra hd-sortable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Order</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                    $posts = hound_get_nodes('');

                    foreach ($posts as $post) {
                        echo '<tr>
                            <td>' . $post['node_id'] . '</td>
                            <td>';
                                echo '<a href="edit-menu.php?page=' . $post['node_id'] . '">' . $post['node_title'] . '</a>';
                            echo '</td>';
                            echo '<td>' . $post['node_url'] . '</td>';
                            echo '<td>' . $post['node_order'] . '</td>';
                            echo '<td><small>' . $post['node_location'] . '</small></td>';
                            echo '<td>
                                    <a href="edit-menu.php?page=' . $post['node_id'] . '">Edit</a> | 
                                    <a style="color: red" onclick="return confirm(\'Are you sure?\');" href="menu.php?op=del&page=' . $post['node_id'] . '">Delete</a>
                                </td>';
                        echo '</tr>';
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
