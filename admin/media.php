<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$nome = $_GET['nome'];
$temppass = $_SESSION['temppass'];
$page = $_GET['page'];

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <h2>Media</h2>
            <form action="media.php" method="get">
                <p>
                    <input type="text" name="nome" value="<?php echo $nome; ?>" placeholder="Search image name..." class="thin-ui-input" size="24">
                    <input class="thin-ui-button thin-ui-button-primary" type="submit" value="Search">
                    <a class="thin-ui-button thin-ui-button-primary" href="media.php"><i class="fa fa-refresh" aria-hidden="true"></i></a>
                </p>
            </form>

            <?php
            $folder = '../files/images/';

            if($_POST['op'] == 'insx') {
                $filename = basename($_FILES['foto1']['name']);
                $uploadfile = $folder . $ante . $_FILES['foto1']['name'];

                if(move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadfile)) {
                    // success
                } else {
                    // error
                    echo  "Error<br>";
                }
            }

            if($_GET['op'] == 'del') {
                $file = $_GET['file'];
                $delfile = makeSafe($file);
                if(file_exists('../' . $delfile)) {
                    echo "<script>alert('$delfile deleted!');</script>";
                    unlink("../".$delfile );
                }
            }
?>

<?php
$primavolta=$_GET['pv'];   //fix bugs

//  $folder="../public/";

$colonne = 8;                //mumero delle colonne
$perpagina = 32;              //numero immagini per pagina

//fix bugs
$s = $_GET['s'];   //fix bugs
$e = $_GET['e'];   //fix bugs
if(strlen($s)<=0 && strlen($e)<=0){
     echo"<script>location.href='$PHP_SELF?s=0&e=$perpagina&pv=1&nome=$nome';</script>";
}

//apre la directory della variabile $folder
//e mette tutti i file letti in un array
$i = 0;
$handle = opendir($folder);
while($file = readdir($handle)) {
    if($file != "." && $file != ".."  && $file != ".DS_Store") {
        if(strlen($nome) > 0) {
            if(trovaStringa($file, $nome)) {
                $files[$i] = $file;
                $i++;
            }     
        } else {
            $files[$i] = $file;
            $i++;
        }
    }
}
closedir($handle);

//setta le variabili
$so = count($files);
$totale = $so;
$ss = $s + 1;
$st = $so -1;
$so -= $s;
$ee = $e;

if($e > $totale) {
    $ee = $totale;
};

echo '<p>' . $totale . ' images found.</p>';

echo '<table class="table table-bordered table-media" width="100%">';
    // show pictures
    $sn = $s;        // next button start
    $en = $e;        // next button end
    $sp = $s;        // prev button start
    $ep = $e;        //prev button end

    echo '<tr>';
    $di = 0;
    for($d = $colonne; $d <= $totale; $d += $colonne) {
        $da[$di] = $d;
        $di++;
    };

    $col = '';
while ($s != $e && $so !=0 ){
    $ext = pathinfo($folder . $files[$s], PATHINFO_EXTENSION);

    echo '<td style="background-color: #FFFFFF; margin: 4px; vertical-align: top;">';
        echo '<div>
            <a target="_blank" href="' . $folder . $files[$s] . '"><i class="fa fa-link" aria-hidden="true"></i></a>
            <a style="color: red; float: right;" onclick="return confirm(\'are you sure?\');" href="media.php?op=del&file=' . $folder . $files[$s] . '"><i class="fa fa-times" aria-hidden="true"></i></a>
        </div>';

        echo '<div style="font-size: 11px;">' . $files[$s] . '</div>';

        if((string) $ext === 'jpg' || (string) $ext === 'jpeg' || (string) $ext === 'png' || (string) $ext === 'gif') {
            echo '<img src="' . $folder . $files[$s] . '" alt="">';
        }

    echo '</td>';

      $s++;
      $so--;

      if ( $s == $st){$col = "colspan=\"$colonne\"";}
      else{ $col = ""; };
      foreach($da as $value) {
            if ( $s == $value) {
                 echo "</tr>\n";
                 echo "<tr>\n";
            };
       };
};
        echo "</tr>\n";

        if(strlen($primavolta)>0){
        // next & prev buttons
        $sn += $perpagina;
        $en += $perpagina;
        $sp -= $perpagina;
        $ep -= $perpagina;
        }

        else{
        // next & prev buttons
        $sn += 0;
        $en += $perpagina;
        $sp -= $perpagina;
        $ep -= $perpagina;

        }
        
         if ($sp < 0 ) { $prev = "&nbsp;";}
        else {$prev = "<a class=\"btn btn-default\" href=".$PHP_SELF."?s=".$sp."&e=".$ep."&nome=$nome> previous page</a>";};

        if ($sn > $st ){$next = "&nbsp;";}
        else{$next = "<a class=\"btn btn-default\" href=".$PHP_SELF."?s=".$sn."&e=".$en."&pv=1&nome=$nome> next page </a>";};
        
        
echo"</table> <br>";

echo"<table>";
echo "<tr>\n";
echo "<td align=\"left\" colspan=\"2\">".$prev."</td>\n";
echo "<td align=\"right\" colspan=\"2\">".$next."</td>\n";
echo "</tr>\n</table>  \n";
?>

 <div class="row" style="background:#ffffff">
      <div class="col-md-12">
         
          <h4>Upload new image</h4>
          <br>
          <form action="media.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
          <input type="hidden" name="op" value="insx"> 
          <input type="file" name="foto1">  <br><input type="submit" class="btn btn-info" value="Upload">
          </form>

       </div>
</div>       




      </div> <!-- container-fluid -->


  </div>  <!-- page-content-wrapper-->

</div> <!-- wrapper -->

</body>
</html>
      

<?php
}
else{
  echo"<script language=javascript>";
  echo"document.location.href='index.php?err=1'";
  echo"</script>";
}

function trovaStringa($text,$wordToSearch)  
{  
    $offset=0;  
    $pos=0;  
    while (is_integer($pos)){  
        $pos = strpos($text,$wordToSearch,$offset);     
        if (is_integer($pos)) {  
            $arrPos[] = $pos;  
            $offset = $pos+strlen($wordToSearch);  
        }  
    }  
    if (isset($arrPos)) {  
        return 1;  
    }  
    else {  
        return 0;  
    }   
}  

function makeSafe( $file ) {
        return str_replace( '..', '', urldecode( $file ) );
}
