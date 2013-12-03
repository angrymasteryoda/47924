<header id="header">

    <div class="title">

        <a href="<?php echo APP_URL?>templates/" target="_parent">Some Blog</a>

    </div>
    <!-- end of site_title -->

    <div class="clear"></div>

</header>

<nav id="menu_wrapper">

    <div class="menu">
        <ul>
            <li><a href="<?php echo APP_URL?>templates/" class="current"><span></span>Home</a></li>
            <li><a href="#"><span></span>Contact</a></li>

            <?php
            if ( checkLogin(false) ) {
                echo '<li class="floatright clearfix"><a href="'.APP_URL.'templates/logout.php"><span></span>Log Out</a></li>';
                if ( Auth::checkPermissions(ADMIN_RIGHTS) ) {
                    echo '<li class="floatright clearfix"><a href="'.APP_URL.'back/"><span></span>Administrate</a></li>';
                }
                echo '<li class="floatright clearfix"><span class="greeting">Hello '. $_SESSION['username'] .'<span></li>';


            }
            ?>
        </ul>
    </div>
</nav>