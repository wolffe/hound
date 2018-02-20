<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$houndAdmin = new hound('', '');
$param = hound_read_parameter('../site/config.txt');
$templateDir = '../site/templates/';

if (isset($_GET['template'])) {
    $template = houndSanitizeString($_GET['template']);
}

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <h2>Edit theme file</h2>
            <p>Currently editing <code><?php echo houndSanitizeString($param['template']); ?></code> (<a href="configuration.php">change active theme</a>).</p>

            <?php
            if ((string) $_POST['op'] === 'mod') {
                $content = $_POST['content'];
                $content = str_replace("\\", "", $content);
                $file = $templateDir . $param['template'] . "/" . $template;

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
                    $dirtmpl = scandir($templateDir . $param['template'] . '/');
                    foreach ($dirtmpl as $itemtpl) {
                        if (!is_dir($templateDir . $param['template'] . '/' . $itemtpl) && $itemtpl != '.' && $itemtpl != '..' && $itemtpl != '.DS_Store' && $itemtpl != 'Thumbs.db') {
                            if ((string) $template === (string) $itemtpl) {
                                $sel2 = 'selected';
                            } else {
                                $sel2 = '';
                            }

                            echo '<option ' . $sel2 . ' value="' . $itemtpl . '">' . $itemtpl . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <br> 

            <form role="form" id="commentForm" action="template-editor.php?template=<?php echo houndSanitizeString($template); ?>" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <?php $content = file_get_contents($templateDir . $param['template'] . '/' . $template); ?>
                    <textarea name="content" class="thin-ui-textarea thin-ui-textarea-code" rows="25"><?php echo $content; ?></textarea>
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
