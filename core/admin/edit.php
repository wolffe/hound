<?php
session_start();

include '../config.php';

include 'includes/functions.php';

houndCheckLogin();

$which = houndSanitizeString($_GET['which']);

$param = hound_read_parameter('../../content/site/config.txt');

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="content">
    <div class="content main">
        <?php
		$type = houndSanitizeString($_GET['type']);
		$acceptedTypes = array('post', 'page');

		if (!in_array($type, $acceptedTypes)) {
            echo '<div class="thin-ui-notification thin-ui-notification-error">Invalid item type.</div>';
		}

        if (isset($_POST['op']) && (string) $_POST['op'] === 'mod' && (string) $_POST['title'] !== 'admin') {
            $id = $_POST['post_id'];
            $slug = $_POST['slug'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $content = str_replace("\n", "", $content);
            $content = str_replace("\\", "", $content);

            $slug = trim($slug);

            $template = $_POST['template'];
            // @todo
            $author = 1;

            $arrayvalue = array(
                'post_id' => $id,
                'type' => $type,
                'slug' => $slug,
                'title' => $title,
                'content' => $content,
                'template'=> $template,
                'author'=> $author,
            );

            if (hound_update_post($arrayvalue)) {
                echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
            } else {
                echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
            }

            $which = $id;
        }

        //
        $post = hound_get_post($_GET['which'], $type, '');
        $paramofpage = [];

        if ($post) {
            // Display the post details
            $paramofpage['post_id'] = $post['post_id'];
            $paramofpage['slug'] = $post['post_slug'];
            $paramofpage['title'] = $post['post_title'];
            $paramofpage['type'] = $post['post_type'];
            $paramofpage['content'] = $post['post_content'];
            $paramofpage['template'] = $post['post_template'];
            $paramofpage['date'] = $post['post_date'];
            $paramofpage['author'] = $post['post_author'];
        } else {
            echo 'Post not found.';
        }
        //

        $paramofpage['content'] = str_replace("\\", "", $paramofpage['content']);
        ?>

        <h2>Edit <?php echo $type; ?></h2>

        <form role="form" id="commentForm" action="edit.php?type=<?php echo $type; ?>&which=<?php echo $which; ?>" method="post">
            <input type="hidden" value="mod" name="op">
            <input type="hidden" value="<?php echo $paramofpage['post_id']; ?>" name="post_id">

            <p>
                <b>Title</b><br>
                <input name="title" value="<?php echo $paramofpage['title']; ?>" type="text" class="thin-ui-input" size="64" required>
            </p>

            <?php if ($paramofpage['slug'] != 'index') { ?>
                <p>
                    <b>Permalink</b><br>
                    <input name="slug" value="<?php echo $paramofpage['slug']; ?>" type="text" class="thin-ui-input" size="64" required>
                </p>
            <?php } else { ?>
                <input name="slug" value="index" type="hidden">
            <?php } ?>

            <p>
                <textarea id="txtTextArea1" name="content" style="width:100%" rows="15"><?php echo $paramofpage['content']; ?></textarea>
            </p>

            <p>
                <b>Template</b>
                <br><small>Template of <?php echo $type; ?></small>
                <div class="thin-ui-select-wrapper">
                    <select name="template" id="template">
                        <?php
                        $dirtmpl = scandir('../../content/site/templates/' . $param['template'] . '/');
                        foreach ($dirtmpl as $itemtpl) {
                            if (!is_dir('../../content/site/templates/' . $param['template'] . '/' . $itemtpl) && $itemtpl != '.' && $itemtpl != '..' && $itemtpl != '.DS_Store') {
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

            <p>
                <button type="submit" class="thin-ui-button thin-ui-button-primary">Save Changes</button>
                <a class="thin-ui-button thin-ui-button-primary" target="_blank" href="../<?php echo $which; ?>">Preview</a>
            </p>
        </form>
    </div>
</div>
<?php
include 'includes/footer.php';
