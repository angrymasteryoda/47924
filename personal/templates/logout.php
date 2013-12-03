<?php
include_once '../config/global.php';
logout();
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
    <div class="smallForm logout aligncenter">
        <h2>Logged out Successfully</h2>
        <p class="font13pt">
        Redirecting in
            <span class="countDown">5</span>
        </p>
        <p class="">
            <a href="<?php echo APP_URL?>templates/" goto>Click here if you are not redirected</a>
        </p>
    </div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>