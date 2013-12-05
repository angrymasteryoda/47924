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
    <div class="smallForm logout">
        <h2>Contact</h2>
        <div class="margin15_bottom">To contact us please leave your name and email and we will get back to you as soon as we can.</div>
        <form class="loginForm">
            <div class="errors"></div>
            <label>Name
                <input type="text" name="name" placeholder="Name" data-type="username" />
            </label>
            <label>Email
                <input type="text" name="email" placeholder="Email" data-type="email" />
            </label>
            <label>Message
                <textarea name="message" data-type="longWords"></textarea>
            </label>
            <input type="submit" value="Login" />
        </form>
    </div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>