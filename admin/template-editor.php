<?php
session_start();

include '../config.php';
include '../libs/hound.php';
include 'libs/houndAdmin.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = $_GET['page'];

$houndAdmin = new houndAdmin('', '');
$param = $houndAdmin->read_param('../site/config.txt');

$template = $_GET['template'];

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/ace.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/theme-chrome.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/theme-dawn.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/theme-github.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/theme-textmate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/mode-css.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/mode-html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/mode-php.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.5/mode-javascript.js"></script>
    <script src="js/jquery-ace.min.js"></script>

    <div class="content">
        <div class="content main">
            <?php
            if($_POST['op'] == 'mod') {
                $content = $_POST['content'];
                $content = str_replace("\\", "", $content);
                $file = "../site/templates/" . $param['template'] . "/" . $template . ".tpl";
              if(file_put_contents($file, $content))
              echo "
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
                $template="";
          }
          ?>

            <h2>Edit file of template</h2>

            <div class="thin-ui-select-wrapper">
                <select onchange="document.location.href='template-editor.php?template='+this.value" name="template" id="template">
                    <option value="">Choose a file...</option>
                    <?php
                    $dirtmpl = scandir('../site/templates/' . $param['template'] . '/');
                    foreach($dirtmpl as $itemtpl) {
                        if(!is_dir('../site/templates/' . $param['template'] . '/' . $itemtpl) && $itemtpl != '.' && $itemtpl != '..' && $itemtpl != '.DS_Store' && $itemtpl != 'Thumbs.db') {
                            if($itemtpl == $param['template']) {
                                $sel2 = 'selected';
                            } else {
                                $sel2 = '';
                            }
                            //$itemtpl=str_replace(".php","",$itemtpl);
                            if($template == $itemtpl) {
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

            <form role="form" id="commentForm" action="template-editor.php?template=<?php echo $template;?>" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <textarea id="txtTextArea1"  name="content" style="width: 100%;" rows="25"><?php echo str_replace('</textarea>', '&lt;/textarea>', file_get_contents('../site/templates/' . $param['template'] . '/' . $template)); ?></textarea>
                </p>

                <p><button type="submit" class="thin-ui-button thin-ui-button-primary">Edit file</button></p>
            </form>
        </div>
    </div>


<script>
$('#txtTextArea1').ace({
    theme: 'textmate',
    lang: 'php',
})
</script>

</body>
</html>
      

<?php
}
else{
  echo"<script language=javascript>";
  echo"document.location.href='index.php?err=1'";
  echo"</script>";
}
?>