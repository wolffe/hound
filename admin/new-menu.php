<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if ((string) $temppass === (string) $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">

          <?php
          if($_POST['op']=="mod"){

                $order=$_POST['order'];
                $item=$_POST['item'];
                $link=$_POST['link'];
                $slug=trim($item);

                $file="../site/pages/menu-$slug.txt";

                //create file
                $myfile = fopen($file, "a") or die("Unable to open file!");
                fclose($myfile);

                $arrayvalue = array(
                    'Order' => $order,
                    'Item' => $item,
                    'Link' => $link
                );
                if (writeParam($arrayvalue, $file)) {
                    echo '<div class="thin-ui-notification thin-ui-notification-success">Changes saved successfully.</div>';
                } else {
                    echo '<div class="thin-ui-notification thin-ui-notification-error">An error occurred while saving changes.</div>';
                }
          }
          ?>


          <br>

          <form role="form" name="form1" id="form1" action="new-menu.php" method="post">
          <input type="hidden" value="mod" name="op">

          <div class="form-group form-group-lg">
              <b>Order</b>
              <label class="sr-only" for="inputHelpBlock">Order</label>
              <span class="help-block">Order of item in menu (from 0 to 100)</span>
              <input name="order"  required type="text" class="form-control">
          </div>


          <div class="form-group form-group-lg">
              <b>Item</b>
              <label class="sr-only" for="inputHelpBlock">Item</label>
              <span class="help-block">Menu</span>
              <input name="item" required type="text" class="form-control">
          </div>

          <div class="form-group form-group-lg">
              <b>Link</b>
              <label class="sr-only" for="inputHelpBlock">link</label>
              <span class="help-block">Absolute link of page</span>
              <input name="link" required type="text" class="form-control">
          </div>

          <br>

          <button type="submit" class="btn btn-lg btn-success">Create menu item</button> or <u><a href="pages.php">Cancel</a></u>
          </form>


      </div> <!-- container-fluid -->


  </div>  <!-- page-content-wrapper-->

    <?php
    include 'includes/footer.php';
} else {
    php_redirect('index.php?err=1');
}
