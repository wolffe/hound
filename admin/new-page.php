<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

$houndAdmin = new hound('', '');
$param = $houndAdmin->read_param('../site/config.txt');

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if($_POST['op'] == 'mod') {
                $slug=$_POST['slug'];
                $title=$_POST['title'];
                $content=$_POST['content'];
                $content=str_replace("\n","",$content);
                $content=str_replace("\\","",$content);

                $metatitle=$_POST['metatitle'];
                $metadescription=$_POST['metadescription'];

                if(strlen($metatitle)<=0)$metatitle="$title";
                if(strlen($metadescription)<=0)$metadescription= strip_tags(substr($content,0,160));

                $template=$_POST['template'];

                $file = '../site/pages/page-' . $slug . '.txt';
                echo '<div class="thin-ui-notification thin-ui-notification-success">Created <code>' . $file . '</code>.</div>';

                //create file
                $myfile = fopen($file, "a") or die("Unable to open file!");
                fclose($myfile);

                $arrayvalue = array(
                    'Slug' => $slug,
                    'Title' => $title,
                    'Content' => $content,
                    'Meta.title' => $metatitle,
                    'Meta.description' => $metadescription,
                    'Template'=> $template
                );
                //print_r($arrayvalue);
                if(writeParam($arrayvalue,$file))echo "
                <div class=\"panel panel-success\">
                    <div class=\"panel-heading\">
                      <h3 class=\"panel-title\">Success</h3>
                    </div>
                    <div class=\"panel-body\">
                      Updated
                    </div>
                  </div>
                ";
                else echo "
                  <div class=\"panel panel-error\">
                    <div class=\"panel-heading\">
                      <h3 class=\"panel-title\">Error</h3>
                    </div>
                    <div class=\"panel-body\">
                      I/o error
                    </div>
                  </div>
                ";
            }
            ?>

            <h2>New page</h2>

            <form role="form" name="form1" id="form1" action="new-page.php" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Title</b><br>
                    <input onKeyUp="createslug()" id="title" name="title" required type="text" class="thin-ui-input" size="64">
                    <br><small>Title of page</small>
                </p>

                <p>
                    <b>Slug</b><br>
                    <input name="slug" id="slug" required type="text" class="thin-ui-input" size="64">
                    <br><small>A unique page identification string</small>
                </p>

                <p>
                    <b>Content</b><br>
                    <textarea id="txtTextArea1" name="content" style="width:100%" rows="10"></textarea>
                    <br><small>Content of page</small>
                </p>

                <p>
                    <b>Template</b>
                    <br><small>Template of page</small>
                    <div class="thin-ui-select-wrapper">
                        <select name="template" id="template">
                            <?php
                            $dirtmpl = scandir('../site/templates/' . $param['template'] . '/');
                            foreach($dirtmpl as $itemtpl) {
                                if(!is_dir("../site/templates/".$param['template']."/".$itemtpl) && $itemtpl!="." && $itemtpl!=".." && $itemtpl!=".DS_Store") {
                                    if($itemtpl==$paramofpage['template'])$sel2="selected";
                                    else $sel2="";
                                    echo"<option $sel2 value=\"$itemtpl\">$itemtpl</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </p>

                <p>
                    <b>Meta Title</b><br>
                    <input name="metatitle" type="text" class="thin-ui-input" size="64">
                    <br><small>Search engine Meta Title</small>
                </p>

                <p>
                    <b>Meta description</b><br>
                    <textarea name="metadescription" style="width:100%" rows="3"></textarea>
                    <br><small>Search engine Meta description</small>
                </p>

                <p>
                    <button type="submit" class="thin-ui-button thin-ui-button-primary">Create page</button>
                </p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
