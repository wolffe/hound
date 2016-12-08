<!doctype html>
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Hound</title>
<link href="css/thin-ui.css" rel="stylesheet">
<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
    font-size: 14px;
    line-height: 1.5;
    color: #333333;
    background-color: #FFFFFF;
}
input, button {
    font-family: inherit;
    font-size: inherit;
}

.wrapper {
    margin: 72px auto 24px auto;
}
.form-signin {
    max-width: 320px;
    padding: 24px 32px;
    margin: 0 auto;
    background-color: #FAFAFA;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.form-signin-heading {
    margin: 0 0 32px 0;
    font-weight: 300;
    font-size: 24px;
}
</style>

</head>
<body>

<div class="wrapper">
    <form action="login.php" method="post" name="Login_Form" class="form-signin">
        <h3 class="form-signin-heading">Sign in to Hound</h3>
        <p>
            <label for="ps">Password</label><br>
            <input type="password" size="32" class="thin-ui-input" id="ps" name="ps" placeholder="Password" required>
        </p>
        <p>
            <button class="thin-ui-button thin-ui-button-primary" name="Submit" value="Login" type="Submit">Log In</button>
        </p>
        <p><small>Powered by Hound</small></p>
    </form>			
</div>

</body>
</html>