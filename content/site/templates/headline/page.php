<?php include get_theme_directory('header.php'); ?>

<!-- Page Header -->
<!-- Set your background image for this header on the line below. -->
<header class="intro-header" style="background-image: url([@featuredimage]);">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                <div class="post-heading">
                    <h1>[@title]</h1>
                    <h2 class="subheading">[@meta.description]</h2>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Post Content -->
<article>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                [@content]
            </div>
        </div>
    </div>
</article>

<?php include get_theme_directory('footer.php'); ?>
