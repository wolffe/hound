<?php include get_theme_directory('header.php'); ?>

<div class="page-wrapper post-wrapper">
    <div class="page-wrapper-full">
        <h2>[@title]</h2>

        [@content]

        <?php echo hound::getBlog(); ?>
    </div>
</div>

<?php include get_theme_directory('footer.php'); ?>
