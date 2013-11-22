<header>
    <h1>Survey Chimp</h1>
</header>
<nav>
    <a class="links" href="<?php echo APP_URL?>templates/">Home</a>
    <a class="links" href="<?php echo APP_URL?>templates/login.php">Login</a>

    <span class="floatright clearfix">
    <?php
    if ( isset($_SESSION['time']) ) {
        if ($_SESSION['time'] + 10 * 60 > time()) {
            if( !empty( $_SESSION['username'] )){
                echo 'Hello ' . $_SESSION['username'];
                echo '<a href="' . APP_URL . 'templates/logout.php" class="margin5_right">Logout</a>';
            }
        }
        else{
            unset( $_SESSION['time'] );
            unset( $_SESSION['username'] );
        }
    }
    ?>
    </span>
</nav>