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

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if($_POST['op'] == 'mod') {
                $sitename = $_POST['sitename'];
                $templatename = $_POST['templatename'];
                $slogan = $_POST['slogan'];

                $houndAdmin = new houndAdmin('', '');   
                $file = '../site/config.txt';
                $arrayvalue = array(
                    'Title' => $sitename,
                    'Template' => $templatename,
                    'Slogan' => $slogan,
                );
                if($houndAdmin->write_param($arrayvalue, $file)) {
                    echo '<div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title">Success</h3>
                    </div>
                    <div class="panel-body">
                      Updated
                    </div>
                  </div>';
                } else {
                    echo '<div class="panel panel-error">
                    <div class="panel-heading">
                      <h3 class="panel-title">Error</h3>
                    </div>
                    <div class="panel-body">
                      I/o error
                    </div>
                  </div>';
                }
            }

            $hound = new hound('', '');
            $param = $hound->read_param('../site/config.txt');
            ?>

            <h2>Configuration</h2>

            <?php
            if(is_writable('../site')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Content (templates and pages) folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Content (templates and pages) folder is not writable.</div>';
            }

            if(is_writable('../files')) {
                echo '<div><i class="fa fa-fw fa-check" aria-hidden="true"></i> Uploads folder is writable.</div>';
            } else {
                echo '<div><i class="fa fa-fw fa-times" aria-hidden="true"></i> Uploads folder is not writable.</div>';
            }

            $memoryAvailable = filter_var(ini_get("memory_limit"), FILTER_SANITIZE_NUMBER_INT);
            $memoryAvailable = $memoryAvailable * 1024 * 1024;
            ?>

            <br>
            <?php
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> cURL is ', function_exists('curl_version') ? 'enabled (' . curl_version()['version'] . '/' . curl_version()['host'] . '/' . curl_version()['ssl_version'] . ')</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> <code>file_get_contents()</code> is ', file_get_contents(__FILE__) ? 'enabled</div>' : 'disabled</div>';
            echo '<div><i class="fa fa-fw fa-info" aria-hidden="true"></i> Current theme folder is <code>/' . $param['template'] . '/</code></div>';
            ?>

            <p>
                You are using Hound <strong><?php echo HOUND_VERSION; ?></strong> on PHP <?php echo PHP_VERSION; ?>.
                <br><small>Current memory usage is <?php echo convert(memory_get_usage(true)); ?> (<?php echo convert(memory_get_peak_usage(true)); ?>) out of <?php echo convert($memoryAvailable); ?> allocated.</small>
            </p>

            <form role="form" id="commentForm" action="configuration.php" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Site name</b><br>
                    <input name="sitename" value="<?php echo $param['title'];?>" required type="text" id="sitename" class="thin-ui-input" size="64">
                    <br><small>The name of your website - appear in meta title after the title</small>
                </p>

                <p>
                    <b>Slogan</b><br>
                    <input name="slogan" value="<?php echo $param['slogan'];?>" type="text" id="slogan" class="thin-ui-input" size="64">
                    <br><small>The slogan</small>
                </p>

                <p>
                    <b>Site template</b>
                    <br><small>Name of template folder.</small>
                    <div class="thin-ui-select-wrapper">
                        <select name="templatename" id="templatename">
                            <?php
                $dirtmpl=scandir("../site/templates");
                foreach ($dirtmpl as $itemtpl) {
                  if( is_dir("../site/templates/".$itemtpl) && $itemtpl!="." && $itemtpl!=".."){
                    if($itemtpl==$param['template'])$sel2="selected";
                    else $sel2="";
                    echo"<option $sel2 value=\"$itemtpl\">$itemtpl</option>";
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