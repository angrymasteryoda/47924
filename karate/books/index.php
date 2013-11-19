<?php
include_once '../config/global.php';
if ($_SESSION['time'] + 10 * 60 < time()) {
    unset( $_SESSION['time'] );
    unset( $_SESSION['username'] );
    header( 'Location: ../books/login.php' ) ;
} else {
    if( empty( $_SESSION['username'] )){
        header( 'Location: ../books/login.php' ) ;
    }
    else{
        $_SESSION['time'] = time();
    }
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
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            Administrator Panel
        </p>

        <p>
            Actions
        </p>
        <p>
            <a href="./addUsers.php">Add and remove administrator accounts</a>
        </p>
        <p>
            <a href="./addTerms.php">Add and Delete terms</a>
        </p>
        <p>
            <a href="./addEvent.php">Add and Delete events</a>
        </p>
        <p>
            <a href="./viewContact.php">View Contacts</a>
        </p>
        <p>
            <a href="./viewStudents.php">View Students</a>
        </p>
<!--        <p>-->
<!--            <a href="./addBlackBelts.php">Add and edit black belts</a>-->
<!--        </p>-->

    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
