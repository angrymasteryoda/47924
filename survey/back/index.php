<?php
include_once '../config/global.php';
checkLogin()
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
        <div class="backIndex">
            <p class="pageTitle">
                Admin control panel
            </p>


            <div>
                <p class="margin10_bottom margin10_top">Edit & Delete</p>
                <a href="<?php echo APP_URL?>back/users.php">Edit Users</a><br>
                <a href="<?php echo APP_URL?>back/createSurvey.php">Create Survey</a><br>
                <a href="<?php echo APP_URL?>back/removeResponses.php">Remove User Responses</a><br>
                <a href="<?php echo APP_URL?>back/edit.php">Edit Surveys</a><br>
                <a href="<?php echo APP_URL?>back/deleteSurvey.php">Delete Surveys</a><br>
            </div>
        </div>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>