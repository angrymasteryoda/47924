<header id="header">

    <div class="title">

        <a href="<?php echo APP_URL?>templates/" target="_parent">A Personal Project</a>

    </div>
    <!-- end of site_title -->

    <div class="clear"></div>

</header>

<nav id="menu_wrapper">

    <div class="menu">
        <ul>
            <?php
            switch ( basename( $_SERVER['PHP_SELF'] ) ){
                case 'index.php' :
                    $parsed = parse_url( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" );
//                    Debug::echoArray($parsed);
                    if ( preg_match( '/back/', $parsed['path'] ) ) {
                        $page = 4;
                    }
                    else{
                        $page = 1;
                    }

                    break;
                case 'contact.php':
                    $page = 2;
                    break;
                case 'login.php':
                    $page = 3;
                    break;
                default:
                    $page = 0;
            }
            echo '
            <li><a href="'. APP_URL .'templates/" class="'. ( ($page == 1) ? ('current') : ('') ) .'"><span></span>Home</a></li>
            <li><a href="'. APP_URL .'templates/contact.php" class="'. ( ($page == 2) ? ('current') : ('') ) .'" ><span></span>Contact</a></li>
            ';

            if ( checkLogin(false) ) {
                echo '<div class="floatright clearfix">';
                echo '<li class="floatright "><a href="'.APP_URL.'templates/logout.php"><span></span>Log Out</a></li>';
                if ( Auth::checkPermissions(ADMIN_RIGHTS) ) {
                    echo '<li class=" floatright"><a href="'.APP_URL.'back/" class="'. ( ($page == 4) ? ('current') : ('') ) .'"><span></span>Administrate</a></li>';
                }
                echo '<li class=" floatright"><span class="greeting">Hello '. $_SESSION['username'] .'<span></li>';

                echo '<div class="clear"></div></div>';

            }
            else{
                echo '<li><a href="'.APP_URL.'templates/login.php" class="'. ( ($page == 3) ? ('current') : ('') ) .'"><span></span>Log In</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>