<?php
include_once '../config/global.php';
header('Location:' . APP_URL . 'templates/surveyListing.php');
if ( checkLogin(false) ) {
    header('Location:' . APP_URL . 'templates/');
}
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
    include APP_URL .'assets/inc/header.php';
    ?>

    <div class="content">
        <div class=""></div>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>