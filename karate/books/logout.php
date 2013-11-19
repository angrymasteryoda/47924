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
<div id="wrapper">
    <?php
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            Logged out Successfully
        </p>

        <?php
            session_destroy();
        ?>
        <p class="aligncenter">
            Redirecting in <span class="countDown">5</span>
        </p>
        <p class="aligncenter">
            <a href="<?php echo APP_URL?>templates/">Click here if you are not redirected</a>
        </p>


    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
