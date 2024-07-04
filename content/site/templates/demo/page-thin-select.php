<?php
include get_theme_directory('header.php');
?>

<h2>[@title]</h2>

[@content]

<link rel="stylesheet" href="<?php echo get_theme_url('lib/thin-select/thin-select.css'); ?>">


<div class="thin-ui-grid">
    <div class="thin-ui-col thin-ui-col-middle">
        <h2 style="font-size: 48px; font-weight: 800; line-height: 1.25;">Thin Select</h2>
    </div>
    <div class="thin-ui-col thin-ui-col-middle">
        <p>
            <a href="https://getbutterfly.com/" rel="home" itemprop="url" class="thin-ui-button thin-ui-button-primary thin-ui-button-regular">Get <b>Thin Select</b></a>
        </p>
    </div>
</div>

<p>Custom <code>&lt;select&gt;</code> element with vanilla JavaScript and CSS. Drop your <code>&lt;select&gt;</code> elements inside a <code>&lt;div class="thin-select"&gt;&lt;/div&gt;</code> element and call it a day.</p>

<div class="thin-ui-grid">
    <div class="thin-ui-col">
        <h3>Regular &lt;select&gt; dropdown</h3>

        <select>
            <option value="0">Select car...</option>
            <option value="1">Audi</option>
            <option value="2">BMW</option>
            <option value="3">Citroen</option>
            <option value="4">Ford</option>
            <option value="5">Honda</option>
            <option value="6">Jaguar</option>
            <option value="7">Land Rover</option>
            <option value="8">Mercedes</option>
            <option value="9">Mini</option>
            <option value="10">Nissan</option>
            <option value="10">Porsche Panamera Turbo S E-Hybrid Sport Turismo</option>
            <option value="11">Toyota</option>
            <option value="12">Volvo</option>
        </select>
    </div>
    <div class="thin-ui-col">
        <h3>Thin Select &lt;select&gt; dropdown</h3>

        <div class="thin-select" style="width:320px;">
            <select>
                <option value="0">Select car...</option>
                <option value="1">Audi</option>
                <option value="2">BMW</option>
                <option value="3">Citroen</option>
                <option value="4">Ford</option>
                <option value="5">Honda</option>
                <option value="6">Jaguar</option>
                <option value="7">Land Rover</option>
                <option value="8">Mercedes</option>
                <option value="9">Mini</option>
                <option value="10">Nissan</option>
                <option value="10">Porsche Panamera Turbo S E-Hybrid Sport Turismo</option>
                <option value="11">Toyota</option>
                <option value="12">Volvo</option>
            </select>
        </div>

        <br>

        <div class="thin-select" style="width:240px;">
            <select>
                <option value="0">Select car...</option>
                <option value="1">Audi</option>
                <option value="2">BMW</option>
                <option value="3">Citroen</option>
                <option value="4">Ford</option>
                <option value="5">Honda</option>
                <option value="6">Jaguar</option>
                <option value="7">Land Rover</option>
                <option value="8">Mercedes</option>
                <option value="9">Mini</option>
                <option value="10">Nissan</option>
                <option value="10">Porsche Panamera Turbo S E-Hybrid Sport Turismo</option>
                <option value="11">Toyota</option>
                <option value="12">Volvo</option>
            </select>
        </div>

    </div>
</div>

<script src="<?php echo get_theme_url('lib/thin-select/thin-select.js'); ?>"></script>

<?php include get_theme_directory('footer.php'); ?>
