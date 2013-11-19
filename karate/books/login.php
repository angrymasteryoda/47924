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
    loadDB(DB_NAME);
    ?>

    <div id="content">
        <p class="pageTitle">
            Administrator Panel
        </p>


        <div class="width35 centermargin">
            <form class="input loginForm" action="">
                <p id="errors">

                </p>
                <label>Username:
                    <input name="username" type="text" placeholder="Username" data-type="username"/>
                </label>
                <label>Password:
                    <input name="password" type="password" placeholder="Password" data-type="complex-password"/>
                </label>
                <input class="submit" type="submit" value="Login In" />
            </form>
        </div>

    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
