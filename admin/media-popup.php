<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];
$page = $_GET['page'];
$nome = $_GET['nome'];

if($temppass == $password) { ?>
<!doctype>
<html>
<head>
<title>Media</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
</head>
<body style="padding:10px">


          
<?php
$folder="../files/images/";          //nome della cartella da cui prendere le immagini

if($_POST['op']=="insx"){

  $filename = basename($_FILES['foto1']['name']);
  $ext = substr($filename, strrpos($filename, '.') + 1);
  if (($ext == "jpeg") || ($ext == "jpg") || ($ext == "JPG") || ($ext == "gif") || ($ext == "GIF") || ($ext == "png") || ($ext == "PNG")) {

    $uploadfile = $folder .$ante. $_FILES['foto1']['name'];
      if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadfile)) {
    } else {
        print "Image not uploaded<br>";
    }

  }else echo"<script>alert('you can upload only images');</script>";


}

if($_GET['op']=="del"){
        $file=$_GET['file'];
        $delfile = makeSafe($file);
        if (file_exists("../". $delfile )) {
            echo"<script>alert('$delfile deleted!');</script>";
            unlink("../".$delfile );
        }
}
?>

<?php
$primavolta=$_GET['pv'];   //fix bugs

//  $folder="../public/";

$colonne=6;                //mumero delle colonne
$perpagina=32;              //numero immagini per pagina
$altezza="style=\"max-width:200px;max-height:150px;overflow:auto;height:250px;width:300px;\"";     //altezza dell'immagine     (lasciare vuoto $altezza=""; se non la si vuole impostare
$larghezza="";    //larghezza dell'immagine   (lasciare vuoto $larghezza=""; se non la si vuole impostare

//fix bugs
$s=$_GET['s'];   //fix bugs
$e=$_GET['e'];   //fix bugs
if(strlen($s)<=0 && strlen($e)<=0){
     echo"<script>location.href='$PHP_SELF?s=0&e=$perpagina&pv=1&nome=$nome';</script>";
}

//apre la directory della variabile $folder
//e mette tutti i file letti in un array
$i=0;
$handle=opendir($folder);
while ($file = readdir ($handle)){
     if ($file != "." && $file != ".."  && $file != ".DS_Store")     {
     
          if(strlen($nome)>0){
            if(trovaStringa($file,$nome)){
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
$totale = $so;
$ss = $s + 1;
$st = $so -1;
$so -= $s;
$ee = $e;


if ( $e > $totale ){
     $ee = $totale;
};


?>

<form action="media-popup.php" method="get">
  <input type="text" name="nome" value="<?php echo $nome;?>" placeholder="name of images"><input class="btn btn-info" type="submit" value="search">
 </form>

<?php
echo "<table class=\"table table-bordered\" width=\"100%\">\n";

// show pictures
$sn = $s;        // next button start
$en = $e;        // next button end
$sp = $s;        // prev button start
$ep = $e;        //prev button end

echo "<tr>\n";
$di=0;
for ($d=$colonne; $d <= $totale; $d += $colonne){
      $da[$di] = $d;
      $di++;
};

$col = "";
while ($s != $e && $so !=0 ){
      echo "
      <td style=\"background:#ffffff;margin:5px\">
      
      <div style=\"width:1px;height:1px;overflow:hidden;\">
          <img id=\"img$s\" src=\"$folder/".$files[$s]."\"  border=0 >
      </div>
      <center><img src=\"$folder/".$files[$s]."\" width=\"100\" height=\"100\" border=0 ></center>
      
          <span style=\"font-size:11px\">".substr($files[$s],0,25)."</span>
          <br>
          <a href=\"javascript:void(0);\" onclick=\"top.tinymce.activeEditor.insertContent('<img src=$folder/$files[$s]>');\"><span class=\"glyphicon glyphicon-download\"></span></a>
          &nbsp; <a style=\"color:red\" onclick=\"return confirm('are you sure?');\" href=\"media-popup.php?op=del&file=$folder".$files[$s]."\"><span style=\"color:red\" class=\"glyphicon glyphicon-remove-sign\"></span></a>
          <br>
      
      </td>\n";

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
          <form action="media-popup.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
          <input type="hidden" name="op" value="insx"> 
          <input type="file" name="foto1">  <br><input type="submit" class="btn btn-info" value="Upload">
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
