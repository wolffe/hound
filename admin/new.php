<?php
session_start();

include '../core/config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$houndAdmin = new hound('', '');
$param = hound_read_parameter('../site/config.txt');

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            $type = 'page';
            if (isset($_GET['type'])) {
                $type = houndSanitizeString($_GET['type']);
            } else if (isset($_POST['type'])) {
                $type = houndSanitizeString($_POST['type']);
            }

            $acceptedTypes = array('post', 'page');

			if (!in_array($type, $acceptedTypes)) {
				$type = 'page';

				echo '<div class="thin-ui-notification thin-ui-notification-error">Invalid item type. Switching to page type.</div>';
			}

            if (isset($_POST['op']) && (string) $_POST['op'] === 'mod') {
                $slug = $_POST['slug'];
                $title = $_POST['title'];
                $content = $_POST['content'];
                $content = str_replace("\n", "", $content);
                $content = str_replace("\\", "", $content);

                $template = $_POST['template'];

                $file = '../site/pages/' . $type . '-' . $slug . '.txt';
                echo '<div class="thin-ui-notification thin-ui-notification-success">Created <code>' . $file . '</code>.</div>';

                //create file
                $myfile = fopen($file, "a") or die("Unable to open file!");
                fclose($myfile);

                $arrayvalue = array(
                    'Slug' => $slug,
                    'Title' => $title,
                    'Content' => $content,
                    'Template'=> $template
                );
                if (writeParam($arrayvalue, $file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }
            }
            ?>

            <h2>New <?php echo $type; ?></h2>

            <form role="form" name="form1" id="form1" action="new.php" method="post">
                <input type="hidden" value="mod" name="op">
                <input type="hidden" value="<?php echo $type; ?>" name="type">

                <p>
                    <b>Title</b><br>
                    <input onkeyup="houndSlugify('title', 'slug');" id="title" name="title" required type="text" class="thin-ui-input" size="64">
                    <br><small>Title of <?php echo $type; ?></small>
                </p>

                <p>
                    <b>Slug</b><br>
                    <input name="slug" id="slug" required type="text" class="thin-ui-input" size="64">
                    <br><small>A unique <?php echo $type; ?> identification string</small>
                </p>

                <p>
                    <b>Content</b><br>
                    <textarea id="txtTextArea1" name="content" style="width:100%" rows="20"></textarea>
                    <br><small>Content of <?php echo $type; ?></small>
                </p>

                <p>
                    <b>Template</b>
                    <br><small>Template of <?php echo $type; ?></small>
                    <div class="thin-ui-select-wrapper">
                        <select name="template" id="template">
                            <?php
                            $dirtmpl = scandir('../site/templates/' . $param['template'] . '/');
                            foreach ($dirtmpl as $itemtpl) {
                                if (!is_dir("../site/templates/".$param['template']."/".$itemtpl) && $itemtpl!="." && $itemtpl!=".." && $itemtpl!=".DS_Store") {
                                    if ($itemtpl == $paramofpage['template']) {
                                        $sel2="selected";
                                    } else {
                                        $sel2="";
                                    }
                                    echo"<option $sel2 value=\"$itemtpl\">$itemtpl</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </p>

                <p>
                    <button type="submit" class="thin-ui-button thin-ui-button-primary">Create <?php echo $type; ?></button>
                </p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}
