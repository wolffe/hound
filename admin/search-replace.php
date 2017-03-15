<?php
session_start();

include '../config.php';
include '../libs/hound.php';

include 'includes/functions.php';

$temppass = $_SESSION['temppass'];

if($temppass == $password) {
    include 'includes/header.php';
    include 'includes/sidebar.php'; ?>

    <div class="content">
        <div class="content main">
            <?php
            if($_POST['op'] == 'mod') {
                $files = glob('../site/pages/*.txt');
                $counter = 0;
                for($i = 1; $i < count($files); $i++) {
                    $image = $files[$i];
                    $supported_file = array(
                        'md',
                        'txt',
                    );
                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                    if(in_array($ext, $supported_file)) {
                        //replace_in_file($image, $_POST['search_from'], $_POST['search_to']);
                        replace_string_in_file($image, $_POST['search_from'], $_POST['search_to']);
                        ++$counter;
                    } else {
                        continue;
                    }
                }

                echo '<div class="panel panel-success">
                    <div class="panel-heading">
                      <h3 class="panel-title">Success</h3>
                    </div>
                    <div class="panel-body">
                      Updated '.$counter.'
                    </div>
                  </div>';
            }
            ?>

            <h2>Search &amp; Replace</h2>

            <form role="form" id="commentForm" action="search-replace.php" method="post">
                <input type="hidden" value="mod" name="op">

                <p>
                    <b>Search</b><br>
                    <input name="search_from" type="text" id="search_from" class="thin-ui-input" size="64">
                    <br><small>String to search</small>
                </p>

                <p>
                    <b>Replace with</b><br>
                    <input name="search_to" type="text" id="search_to" class="thin-ui-input" size="64">
                    <br><small>String to replace</small>
                </p>

                <p><button type="submit" class="thin-ui-button thin-ui-button-primary">Start</button></p>
            </form>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
else {
    php_redirect('index.php?err=1');
}
