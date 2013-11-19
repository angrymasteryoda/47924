<div id='header'>
    <div id="logo">
        <a href="<?php echo APP_URL?>templates/">
            <img src="<?php echo APP_URL ?>assets/img/uiLogo.png"/>

        </a>
    </div>
    <div id="nav">
        <a href="<?php echo APP_URL?>templates/">Home</a>
        <a href="<?php echo APP_URL?>templates/instructors.php">Instructors</a>
        <a href="<?php echo APP_URL?>templates/biography.php">Biography</a>
        <a href="<?php echo APP_URL?>templates/terms.php">Terminology</a>
        <a href="<?php echo APP_URL?>templates/contact.php">Contact</a>
        <a href="<?php echo APP_URL?>templates/signUp.php">New Student</a>
<!--        <a href="--><?php //echo APP_URL?><!--templates/test.php">Testing</a>-->

        <span class="floatright">
        <?php
        if ( isset($_SESSION['time']) ) {
            if ($_SESSION['time'] + 10 * 60 > time()) {
                if( !empty( $_SESSION['username'] )){
                    echo 'Hello ' . $_SESSION['username'];
                    echo '<a href="' . APP_URL . 'books/">Admin</a>';
                    echo '<a href="' . APP_URL . 'books/logout.php" class="margin5_right">Logout</a>';
                }
            }
            else{
                unset( $_SESSION['time'] );
                unset( $_SESSION['username'] );
            }
        }

        ?>
        </span>
        <span class="clear"></span>
    </div>
</div>