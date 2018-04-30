<?php
session_start();

include '../core/config.php';

include 'includes/functions.php';

houndCheckLogin();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<div class="content">
    <div class="content main">
        <h2>Media</h2>
        <div>
            <a class="thin-ui-button thin-ui-button-secondary" href="media.php"><i class="fa fa-refresh" aria-hidden="true"></i></a>
        </div>
        <br>

        <?php
        $folder = '../files/images/';

        if (isset($_POST['op']) && (string) $_POST['op'] === 'insx') {
            $filename = basename($_FILES['foto1']['name']);
            $uploadfile = $folder . $ante . $_FILES['foto1']['name'];

            if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadfile)) {
                // success
            } else {
                // error
                echo  "Error<br>";
            }
        }

        if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
            $file = $_GET['file'];
            $delfile = makeSafe($file);
            if(file_exists('../' . $delfile)) {
                echo "<script>alert('$delfile deleted!');</script>";
                unlink("../".$delfile );
            }
        }

        $primavolta = isset($_GET['pv']) ? $_GET['pv'] : 1;
        $perpagina = 12;

        $s = isset($_GET['s']) ? $_GET['s'] : 0;
        $e = isset($_GET['e']) ? $_GET['e'] : $perpagina;
        if (strlen($s) <= 0 && strlen($e) <= 0) {
            echo '<script>location.href="' . $_REQUEST['PHP_SELF'] . '?s=0&e=' . $perpagina . '&pv=1";</script>';
        }

        $files = glob($folder . '*');

        $so = count($files);
        $totale = $so;
        $ss = $s + 1;
        $st = $so -1;
        $so -= $s;
        $ee = $e;

        if ($e > $totale) {
            $ee = $totale;
        };

        echo '<p>' . $totale . ' images found.</p>';

        // show pictures
        $sn = $s;        // next button start
        $en = $e;        // next button end
        $sp = $s;        // prev button start
        $ep = $e;        //prev button end
        ?>
        <table data-table-theme="default zebra hd-sortable">
            <thead>
                <tr>
                    <th></th>
                    <th>Media</th>
                    <th>Media Details</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($s!=$e && $so!=0) {
                    $ext = pathinfo($files[$s], PATHINFO_EXTENSION);
                    $fileinfo = stat($files[$s]);
                    $fileName = str_replace('../files/images/', '', $files[$s]);

                    if ($fileName !== 'index.php') {
                        echo '<tr>';
                            if ((string) $ext === 'jpg' || (string) $ext === 'jpeg' || (string) $ext === 'png' || (string) $ext === 'gif') {
                                echo '<td><img src="' . $files[$s] . '" alt="" height="40"></td>';
                            } else {
                                echo '<td></td>';
                            }
                            echo '<td>
                                ' . $fileName . '
                                <br><code>' . $files[$s] . '</code>
                                </td>
                                <td><small>' . date('F d Y H:i:s', filemtime($files[$s])) . ' <code>' . formatSizeUnits($fileinfo['size']) . '</code></small></td>
                                <td>
                                    <a href="' . $files[$s] . '" target="_blank">View</a> | 
                                    <a style="color: red;" onclick="return confirm(\'are you sure?\');" href="media.php?op=del&file=' . $files[$s] . '">Delete</a>
                                </td>
                            </tr>';
                    }

                    $s++;
                    $so--;
                }
                ?>
            </tbody>
        </table>

        <?php
        if (strlen($primavolta) > 0) {
            // next & prev buttons
            $sn += $perpagina;
            $en += $perpagina;
            $sp -= $perpagina;
            $ep -= $perpagina;
        } else {
            // next & prev buttons
            $sn += 0;
            $en += $perpagina;
            $sp -= $perpagina;
            $ep -= $perpagina;
        }

        if ($sp < 0 ) {
            $prev = "&nbsp;";
        } else {
            $prev = "<a class=\"btn btn-default\" href=".$PHP_SELF."?s=".$sp."&e=".$ep."> previous page</a>";
        }

        if ($sn > $st ) {
            $next = "&nbsp;";
        } else {
            $next = "<a class=\"btn btn-default\" href=".$PHP_SELF."?s=".$sn."&e=".$en."&pv=1> next page </a>";
        }

        echo '<p>' . $prev . ' | ' . $next . '</p>';
        ?>

        <h4>Upload new image</h4>
        <form action="media.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
            <input type="hidden" name="op" value="insx"> 
            <input type="file" name="foto1">  <br><input type="submit" class="btn btn-info" value="Upload">
        </form>
    </div>
</div>
<?php
include 'includes/footer.php';
