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
            if($_GET['op']=="del"){
              $file="../site/pages/menu-".$page.".txt";
              if(unlink($file))echo "
                  <div class=\"panel panel-success\">
                    <div class=\"panel-heading\">
                      <h3 class=\"panel-title\">Success</h3>
                    </div>
                    <div class=\"panel-body\">
                      deleted
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
            <h2>Menu</h2>
            <div>
                <a href="new-menu.php" class="thin-ui-button thin-ui-button-primary"><i class="fa fa-plus" aria-hidden="true"></i> New menu item</a>
            </div>

<br>
  <div class="table-responsive">
        <table class="table table-striped table-bordered hd-sortable">
          <thead>
            <tr>
              <th>Order</th>
              <th>Name</th>
              <th>Link</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $fileindir=$houndAdmin->get_files('../site/pages/');
            foreach ($fileindir as $file) {
                if (preg_match("/\menu\b/i", $file)) {
                    $parampage=$houndAdmin->read_param($file);
                    $nameofmenu=str_replace('../site/pages/',"",$file);
                    $nameofmenu=str_replace('menu-',"",$nameofmenu);
                    $nameofmenu=str_replace('.txt',"",$nameofmenu);
                    $i++;

                    echo '<tr>';
                    echo"<td>".$parampage['order']."</td>";
                    echo"<td>".$parampage['item']."</td>";
                    echo"<td>".$parampage['link']."</td>";

                    echo"<td><span class=\"glyphicon glyphicon-pencil\"></span> <a href=\"edit-menu.php?page=$nameofmenu\">Edit</a></td>";
                    echo"<td><span style=\"color:red\" class=\"glyphicon glyphicon-remove-sign\"></span> <a style=\"color:red\" onclick=\"return confirm('are you sure?');\" href=\"menu.php?op=del&page=$nameofmenu\"> Delete</a></td>";
                    echo "</tr>";

                } 
            }
            ?>
        </tbody>
      </table>
</div>


      </div> <!-- container-fluid -->


  </div>  <!-- page-content-wrapper-->

    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}