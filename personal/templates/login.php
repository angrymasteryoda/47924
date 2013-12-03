<?php
include_once '../config/global.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include '../assets/inc/meta.php';
    ?>
</head>
<body>

<?php
include '../assets/inc/header.php';
?>
<!-- end of header -->

<div id="main" class="clear"><span class="tm_bottom"></span>
    <div class="smallForm login">
        <div class="loginWrapper">
            <h1>Login</h1>
            <div class="margin15_bottom aligncenter">Don't have an account register one <a class="signUpLink">here</a></div>
            <form class="loginForm">
                <div class="errors"></div>
                <label>Username
                    <input type="text" name="username" placeholder="Username" data-type="username" />
                </label>
                <label>Password
                    <input type="password" name="password" placeholder="Password" data-type="password" />
                </label>
                <input type="submit" value="Login" />
            </form>
        </div>
        <div class="signUpWrapper none">
            <h1>SignUp</h1>
            <div class="margin15_bottom aligncenter">Have an account login <a class="signUpLink">here</a></div>
            <form class="signUpForm">
                <div class="errors"></div>
                <label>Username
                    <input type="text" name="username" placeholder="Username" data-type="username" />
                </label>
                <label>Password
                    <input type="password" name="password" placeholder="Password" data-type="password" />
                </label>
                <label>Confirm Password
                    <input type="password" name="confPassword" placeholder="Confirm Password" data-type="confPassword" />
                </label>
                <label>Email
                    <input type="text" name="email" placeholder="Email" data-type="email" />
                </label>
                <label>Confirm Email
                    <input type="text" name="email" placeholder="Confirm Email" data-type="confEmail" />
                </label>
                <input type="submit" value="Sign Up" />
            </form>
        </div>
    </div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>