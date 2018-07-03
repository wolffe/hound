<?php
session_start();

include '../config.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = isset($_GET['page']) ? $_GET['page'] : '';
$nome = $_GET['nome'];

if ((string) $temppass === HOUND_PASS) {
    include 'includes/header.php'; ?>

    <div class="content-media-modal">
        <?php
        $mediaFolder = '../../content/files/images/';

        if (isset($_POST['op']) && (string) $_POST['op'] === 'insx') {
            $filename = basename($_FILES['foto1']['name']);
            $ext = substr($filename, strrpos($filename, '.') + 1);

            // @todo: refactor extension check // mimetype check
            if (in_array($ext, array('jpg', 'jpeg', 'JPG', 'JPEG', 'gif', 'GIF', 'png', 'PNG'))) {
                $uploadfile = $mediaFolder . $_FILES['foto1']['name'];

                if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadfile)) {
                    print "Image uploaded<br>";
                } else {
                    print "Image not uploaded<br>";
                }
            } else {
                echo"<script>alert('you can upload only images');</script>";
            }
        }

        if (isset($_GET['op']) && (string) $_GET['op'] === 'del') {
            $file = $_GET['file'];
            $delfile = makeSafe($file);
        if (file_exists("../". $delfile )) {
            echo"<script>alert('$delfile deleted!');</script>";
            unlink("../".$delfile );
        }
    }

    $primavolta = isset($_GET['pv']) ? (int) $_GET['pv'] : 0;

    $perpagina = 32;

    $s = $_GET['s'];
    $e = $_GET['e'];

    if (strlen($s) <= 0 && strlen($e) <= 0) {
        echo"<script>location.href='$PHP_SELF?s=0&e=$perpagina&pv=1&nome=$nome';</script>";
    }

    //apre la directory della variabile $mediaFolder
    //e mette tutti i file letti in un array
    $i=0;
    $handle=opendir($mediaFolder);
    $files = array();
    while ($file = readdir ($handle)){
        if ($file != "." && $file != ".." && $file != ".DS_Store")     {
            if(strlen($nome)>0){
                if(findString($file,$nome)){
                    $files[$i] = $file;
                    $i++;
                }     
            }else{
                $files[$i] = $file;
                $i++;
            }
        }
    }
    closedir($handle);

    //setta le variabili
    $so = count($files);
    $ss = $s + 1;
    $st = $so -1;
    $so -= $s;
    ?>

    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.0/build/pure-min.css" integrity="sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w" crossorigin="anonymous">

    <div class="wrapper">
        <form action="media-popup.php" method="get" class="pure-form">
            <input type="text" name="nome" value="<?php echo $nome; ?>" placeholder="Search image...">
            <input class="pure-button pure-button-primary" type="submit" value="search">
        </form>
    </div>

    <?php
    // show pictures
    $sn = $s;        // next button start
    $en = $e;        // next button end
    $sp = $s;        // prev button start
    $ep = $e;        //prev button end

    echo '<div class="pure-g">';
    while ($s != $e && $so !=0 ){
        echo '<div class="pure-u-1-5">
            <img src="' . $mediaFolder . $files[$s] . '" width="100" height="100">
            <br><small>' . substr($files[$s], 0, 25) . '</small>
            <br>
            <a href="javascript:void(0);" onclick="top.tinymce.activeEditor.insertContent(\'<img src=' . $mediaFolder . $files[$s] . '>\');">Insert image</a> | 
            <a onclick="return confirm(\'are you sure?\');" href="media-popup.php?op=del&file=' . $mediaFolder . $files[$s] . '">Remove</a>
        </div>';

        $s++;
        $so--;
    }
    echo '</div>';

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

    if ($sp < 0) {
        $prev = '';
    } else {
        $prev = '<a class="pure-button" href="' . $PHP_SELF . '?s=' . $sp . '&e=' . $ep . '&nome=' . $nome . '">Previous</a>';
    }

    if ($sn > $st) {
        $next = '';
    } else {
        $next = '<a class="pure-button" href="' . $PHP_SELF . '?s=' . $sn . '&e=' . $en . '&pv=1&nome=' . $nome . '">Next</a>';
    }

    echo '<div class="pure-button-group" role="group">' . $prev . $next . '</div>';
    ?>

    <div class="wrapper">
        <h4>Upload new image</h4>

        <form action="media-popup.php" method="post" enctype="multipart/form-data" class="pure-form">
            <fieldset>
                <input type="hidden" name="op" value="insx"> 
                <input type="file" name="foto1"> 
                <input type="submit" class="pure-button pure-button-primary" value="Upload">
            </fieldset>
        </form>
    </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
  php_redirect('index.php?err=1');
}



function findString($text, $wordToSearch) {
    $offset = 0;
    $pos = 0;

    while (is_integer($pos)) {
        $pos = strpos($text, $wordToSearch, $offset);

        if (is_integer($pos)) {
            $arrPos[] = $pos;
            $offset = $pos + strlen($wordToSearch);
        }
    }

    if (isset($arrPos)) {
        return 1;
    } else {
        return 0;
    }
}
