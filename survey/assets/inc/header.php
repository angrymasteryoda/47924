<header>
    <h1>Survey Chimp</h1>
</header>
<nav>
    <a class="links" href="<?php echo APP_URL?>templates/">Home</a>

    <?php
    $loggedIn = @checkLogin(false);
    $isAdmin = Auth::checkPermissions( ADMIN_RIGHTS );

    if ( $loggedIn ) {
        echo '<a class="links" href="'.APP_URL.'templates/surveyListing.php">Surveys</a>';
    }
    else {
        echo '<a class="links" href="'.APP_URL.'templates/login.php">Login</a>';
    }
    ?>


    <span class="floatright clearfix">
    <?php
    if ( $loggedIn ) {
        echo 'Hello ' . $_SESSION['username'];
        if ( $isAdmin ) {
            echo '<a href="' . APP_URL . 'back/" class="margin5_right">Admin</a>';
        }
        echo '<a href="' . APP_URL . 'templates/logout.php" class="margin5_right">Log Out</a>';
    }
//    else {
//    }
    ?>
    </span>
</nav>