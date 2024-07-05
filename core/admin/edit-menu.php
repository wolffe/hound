<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = houndSanitizeString($_GET['page']);

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if ((string) $_POST['op'] === 'mod') {
                $node_id = $_POST['node_id'];
                $order = $_POST['order'];
                $item = $_POST['item'];
                $link = $_POST['link'];

                $node_array = [
                    'node_id' => $node_id,
                    'node_url' => $link,
                    'node_title' => $item,
                    'node_order' => $order,
                    'node_location' => 'menu'
                ];

                if (hound_update_node($node_array)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }
            }

            //
            $node = hound_get_node($_GET['page']);
            $paramofpage = [];

            if ($node) {
                // Display the node details
                $paramofpage['node_id'] = $node['node_id'];
                $paramofpage['node_title'] = $node['node_title'];
                $paramofpage['node_url'] = $node['node_url'];
                $paramofpage['node_order'] = $node['node_order'];
                $paramofpage['node_location'] = $node['node_location'];
            } else {
                echo 'Node not found.';
            }
            //
            ?>

            <h2>Edit menu</h2>

            <form role="form" id="commentForm" action="edit-menu.php?page=<?php echo $_GET['page']; ?>" method="post">
                <input type="hidden" value="mod" name="op">
                <input type="hidden" value="<?php echo $paramofpage['node_id'];?>" name="node_id">

                <p>
                    <b>Menu title</b><br>
                    <input name="item" value="<?php echo $paramofpage['node_title'];?>" type="text" class="thin-ui-input" size="64" required>
                    <br><small>Menu title</small>
                </p>

                <p>
                    <b>Menu item link</b><br>
                    <input name="link" value="<?php echo $paramofpage['node_url'];?>" type="url" class="thin-ui-input" size="64" required>
                    <br><small>Page link (absolute URI)</small>
                </p>

                <p>
                    <b>Order</b><br>
                    <input name="order" value="<?php echo $paramofpage['node_order'];?>" type="number" min="0" class="thin-ui-input" required>
                    <br><small>Order of item in menu</small>
                </p>

                <p><button type="submit" class="thin-ui-button thin-ui-button-primary">Save Changes</button></p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}
