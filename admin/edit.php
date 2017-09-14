<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$which = houndSanitizeString($_GET['which']);

$houndAdmin = new hound('', '');
$param = $houndAdmin->read_param('../site/config.txt');

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
			$type = houndSanitizeString($_GET['type']);
			$acceptedTypes = array('post', 'page');

			if (!in_array($type, $acceptedTypes)) {
				echo '<div class="thin-ui-notification thin-ui-notification-error">Invalid item type.</div>';
			}

            if ((string) $_POST['op'] === 'mod' && (string) $_POST['title'] !== 'admin') {
                $slug = $_POST['slug'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                $content = str_replace("\n", "", $content);
                $content = str_replace("\\", "", $content);

                $slug = trim($slug);

                $template = $_POST['template'];

                $file = '../site/pages/' . $type . '-' . $which . '.txt';
                $arrayvalue = array(
                    'Slug' => $slug,
                    'Title' => $title,
                    'Content' => $content,
                    'Template'=> $template,
                );

                if (writeParam($arrayvalue, $file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }

                rename('../site/pages/' . $type . '-' . $which . '.txt', '../site/pages/' . $type . '-' . $slug . '.txt');
                $which = $slug;
            }
            $paramofpage=$houndAdmin->read_param('../site/pages/' . $type . '-' . $which . '.txt');
            $paramofpage['content'] = str_replace("\\", "", $paramofpage['content']);
            ?>

            <h2>Edit <?php echo $type; ?></h2>
            <div>
                <a class="thin-ui-button thin-ui-button-primary" target="_blank" href="../<?php echo $which; ?>"><?php echo ucwords($type); ?> preview </a>
            </div>    

            <br>

            <form role="form" id="commentForm" action="edit.php?type=<?php echo $type; ?>&which=<?php echo $which; ?>" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Title</b><br>
                    <input name="title" value="<?php echo $paramofpage['title'];?>" type="text" class="thin-ui-input" size="64" required>
                    <br><small>Title of <?php echo $type; ?></small>
                </p>

                <?php if ($paramofpage['slug'] != 'index') { ?>
                    <p>
                        <b>Slug</b><br>
                        <input name="slug" value="<?php echo $paramofpage['slug'];?>" type="text" class="thin-ui-input" size="64" required>
                        <br><small>A unique <?php echo $type; ?> identification separated by "-" (minus)</small>
                    </p>
                <?php } else { ?>
                    <input name="slug" value="index" type="hidden">
                <?php } ?>

                <p>
                    <b>Content</b><br>
                    <textarea id="txtTextArea1" name="content" style="width:100%" rows="15"><?php echo $paramofpage['content']; ?></textarea>
                </p>

                <p>
                    <b>Template</b>
                    <br><small>Template of <?php echo $type; ?></small>
                    <div class="thin-ui-select-wrapper">
                        <select name="template" id="template">
                            <?php
                            $dirtmpl = scandir('../site/templates/' . $param['template'] . '/');
                            foreach ($dirtmpl as $itemtpl) {
                                if (!is_dir('../site/templates/' . $param['template'] . '/' . $itemtpl) && $itemtpl != '.' && $itemtpl != '..' && $itemtpl != '.DS_Store') {
                                    if ($itemtpl == $paramofpage['template'])
                                        $sel2 = 'selected';
                                    else
                                        $sel2 = '';
                                    echo '<option ' . $sel2 . ' value="' . $itemtpl . '">' . $itemtpl . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
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
