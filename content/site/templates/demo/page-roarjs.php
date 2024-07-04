<?php
include get_theme_directory('header.php');
?>

<h2>[@title]</h2>

[@content]

<link rel="stylesheet" href="<?php echo get_theme_url('lib/roarjs/roar.css'); ?>">

<div class="thin-ui-grid">
    <div class="thin-ui-col thin-ui-col-middle">
        <h2 style="font-size: 48px; font-weight: 800; line-height: 1.25;">RoarJS</h2>
    </div>
    <div class="thin-ui-col thin-ui-col-middle">
        <p>
            <a href="https://getbutterfly.com/roarjs-vanilla-javascript-alert-confirm-replacement/" rel="home" itemprop="url" class="thin-ui-button thin-ui-button-primary thin-ui-button-regular">Get <b>RoarJS</b></a>
            <a href="https://github.com/wolffe/RoarJS" itemprop="url" class="thin-ui-button thin-ui-button-secondary thin-ui-button-regular">GitHub</a>
            <a href="https://getbutterfly.com/how-to-create-a-gdpr-modal-popup-using-roarjs/" itemprop="url" class="thin-ui-button thin-ui-button-neutral thin-ui-button-regular">Tutorial #1</a>
        </p>
    </div>
</div>

<p>A zero-dependency, vanilla JavaScript alert/confirm replacement.</p>
<p>RoarJS is a wonderful, responsive, customizable, accessible (WAI-ARIA), zero-dependency, vanilla JavaScript alert/confirm replacement. RoarJS automatically centers itself on the page and looks great no matter if you are using a desktop computer, mobile, or tablet. RoarJS is free, tiny, and it works in any browser.</p>

<div class="thin-ui-grid">
    <div class="thin-ui-col">
        <h3>Demo buttons</h3>

        <p>
            <button onclick="demo1()">Demo 1</button>
            <button onclick="demo2()">Demo 2</button>
            <button onclick="demo3()">Demo 3</button>
        </p>
    </div>
    <div class="thin-ui-col">
        <p>Check the console for each button callback.</p>
    </div>
</div>

<script src="<?php echo get_theme_url('lib/roarjs/roar.js'); ?>"></script>

<script>
/*
 * Custom demos
 */
function demo1() {
    const options = {
        cancel: true,
        cancelText: "cancel",
        cancelCallBack: (event) => {
            console.log("options.cancelCallBack");
        },
        confirm: true,
        confirmText: "confirm",
        confirmCallBack: (event) => {
            console.log("options.confirmCallBack");
        }
    };
    roar(
        "demo 1",
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin a purus non turpis scelerisque molestie. Pellentesque id interdum nulla. Etiam nec ex porta, blandit felis eget, pulvinar turpis. Nam efficitur placerat nisl, ut tempor leo finibus eget. Praesent non dolor id purus scelerisque elementum ac eu enim. Cras euismod ipsum id mi malesuada, nec porttitor velit pulvinar. Proin hendrerit libero fringilla augue euismod, sit amet molestie dui dapibus. Mauris libero quam, bibendum et quam at, condimentum eleifend libero.",
        options
    );
}
function demo2() {
    const options = {
        cancel: false,
        cancelText: "cancel",
        cancelCallBack: (event) => {
            console.log("options.cancelCallBack");
        },
        confirm: true,
        confirmText: "confirm",
        confirmCallBack: (event) => {
            console.log("options.confirmCallBack");
        }
    };
    roar("demo 2", "demo 2 show message", options);
}
function demo3() {
    const options = {
        cancel: true,
        cancelText: "cancel button",
        cancelCallBack: (event) => {
            console.log("options.cancelCallBack");
        },
        confirm: true,
        confirmText: "confirm button",
        confirmCallBack: (event) => {
            console.log("options.confirmCallBack");
        }
    };
    roar("demo 3", "demo 3 show message", options);
}
</script>

<?php include get_theme_directory('footer.php'); ?>
