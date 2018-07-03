<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = houndSanitizeString($_GET['page']);

$houndAdmin = new hound('', '');

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if ((string) $_POST['op'] === 'mod') {
                $order = $_POST['order'];
                $item = $_POST['item'];
                $link = $_POST['link'];
                $slug = trim($item);

                $file = '../../content/site/pages/menu-' . $page . '.txt';
                $arrayvalue = array(
                    'Order' => $order,
                    'Item' => $item,
                    'Link' => $link,
                );

                if (writeParam($arrayvalue, $file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }

                rename('../../content/site/pages/menu-' . $page . '.txt', '../../content/site/pages/menu-' . $slug . '.txt');
                $page = $slug;
            }
            $paramofpage = hound_read_parameter('../../content/site/pages/menu-' . $page . '.txt');
            ?>

            <h2>Edit menu</h2>

            <form role="form" id="commentForm" action="edit-menu.php?page=<?php echo $page; ?>" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Menu item</b><br>
                    <input name="item" value="<?php echo $paramofpage['item'];?>" type="text" class="thin-ui-input" size="64" required>
                    <br><small>Menu item title</small>
                </p>

                <p>
                    <b>Menu item link</b><br>
                    <input name="item" value="<?php echo $paramofpage['link'];?>" type="url" class="thin-ui-input" size="64" required>
                    <br><small>Page link (absolute URI)</small>
                </p>

                <p>
                    <b>Order</b><br>
                    <input name="order" value="<?php echo $paramofpage['order'];?>" type="number" min="0" class="thin-ui-input" required>
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
