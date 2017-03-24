<?php
declare (strict_types = 1);

session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$houndAdmin = new hound('', '');
$param = $houndAdmin->read_param('../site/config.txt');

if (isset($_GET['template'])) {
    $template = (string) trim($_GET['template']);
}

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <h2>Edit theme file</h2>
            <p>Currently editing <code><?php echo $param['template']; ?></code> (<a href="configuration.php">change active theme</a>).</p>

            <?php
            if ((string) $_POST['op'] === 'mod') {
                $content = $_POST['content'];
                $content = str_replace("\\", "", $content);
                $file = "../site/templates/" . $param['template'] . "/" . $template;

                if (file_put_contents($file, $content)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }

                $template = '';
            }
            ?>

            <div class="thin-ui-select-wrapper">
                <select onchange="document.location.href='template-editor.php?template='+this.value" name="template" id="template">
                    <option value="">Choose a file...</option>
                    <?php
                    $dirtmpl = scandir('../site/templates/' . $param['template'] . '/');
                    foreach($dirtmpl as $itemtpl) {
                        if (!is_dir('../site/templates/' . $param['template'] . '/' . $itemtpl) && $itemtpl != '.' && $itemtpl != '..' && $itemtpl != '.DS_Store' && $itemtpl != 'Thumbs.db') {
                            if ((string) $itemtpl === (string) $param['template']) {
                                $sel2 = 'selected';
                            } else {
                                $sel2 = '';
                            }

                            if ((string) $template === (string) $itemtpl) {
                                echo "<option selected $sel2 value=\"$itemtpl\">$itemtpl</option>";
                            } else {
                                echo "<option $sel2 value=\"$itemtpl\">$itemtpl</option>";
                            }
                        }
                    }
                    ?>
                </select>
            </div>

            <br> 

            <form role="form" id="commentForm" action="template-editor.php?template=<?php echo $template; ?>" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <textarea name="content" class="thin-ui-textarea thin-ui-textarea-code" rows="25"><?php echo file_get_contents('../site/templates/' . $param['template'] . '/' . $template); ?></textarea>
                </p>

                <p><button type="submit" class="thin-ui-button thin-ui-button-primary">Edit file</button></p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
