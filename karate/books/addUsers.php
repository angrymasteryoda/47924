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
            Add Administrator Users
        </p>
        <div class="width35 centermargin">
            <p class="margin0_left">Add Administrator Users</p>
            <form class="input addUserForm" >
                <p id="errors">

                </p>
                <label>Username:
                    <input name="username" type="text" placeholder="Username" data-type="username" value=""/>
                </label>
                <label>Password:
                    <input name="password" type="password" placeholder="Password" data-type="complex-password" value=""/>
                </label>
                <label>Confirm Password:
                    <input name="ConfirmPassword" type="password" placeholder="Confirm Password" data-type="confComplex-password" value=""/>
                </label>
                <label>Email:
                    <input name="email" type="text" placeholder="Email" data-type="email" value=""/>
                </label>
                <input class="submit" type="submit" value="Create Account" />
            </form>
        </div>
        <div>
            <p class="redHeader aligncenter">
                Delete Users
            </p>
            <?php
            $sort ='';
            $sortBy = ( (( (empty($_GET['ob'])) ? ('0') : ($_GET['ob']) ) == '1') ? ( 'DESC' ) : ( 'ASC' ) );
            switch(  ( (empty($_GET['o'])) ? ('') : ($_GET['o']) ) ){
                case 1:
                    $sort = '`username`';
                    break;
                case 2:
                    $sort = '`email`';
                    break;
                case 3:
                    $sort = '`last_browser`';
                    break;
                case 4:
                    $sort = '`last_platform`';
                    break;
                case 5:
                    $sort = '`last_platform`';
                    break;
                default:
                    $sort = '`username`';
            }

            loadDB(DB_NAME);

            $pageData = Core::getPageData('mr2358174_karate_entity_users');
            $limit = 'LIMIT ' . $pageData['starting'] .',' . $pageData['ipp'];

            $q = "SELECT `user_id`, `username`, `email`, `last_ip`, `last_browser`, `last_platform`
            FROM `47924`.`mr2358174_karate_entity_users` AS `mr2358174_karate_entity_users`
            ORDER BY $sort $sortBy $limit;";
            $r = mysql_query($q);
            $users = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $users[$i++] = $row;
            }

            echo '
                <table class="margin10 users">
                <tbody>
                    <tr>
                        <td>Username'. Core::sortIcons(1) .'</td>
                        <td>Email'. Core::sortIcons(2) .'</td>
                        <td>Last used ip'. Core::sortIcons(3) .'</td>
                        <td>Last used browser'. Core::sortIcons(4) .'</td>
                        <td>Last used platform'. Core::sortIcons(5) .'</td>
                        <td>Delete</td>
                    </tr>
            ';

            foreach ( $users as $user ) {
                echo '
                <tr class="data user">
                    <td>'. $user['username'] .'</td>
                    <td>'. $user['email'] .'</td>
                    <td>'. $user['last_ip'] .'</td>
                    <td>'. $user['last_browser'] .'</td>
                    <td>'. $user['last_platform'] .'</td>
                    <td class="userDelete" userId="'.$user['user_id'].'" data-name="'. $user['username'] .'" >Delete</td>
                </tr>
                    ';
            }
            echo '</tbody></table>';
//            echo '
//            <div class="margin20_top margin10_left margin10_right users">
//                    <div class="floatleft width20" >Username</div>
//                    <div class="floatleft width25" >Email</div>
//                    <div class="floatleft width10" >Last used ip</div>
//                    <div class="floatleft width15" >Last used browser</div>
//                    <div class="floatleft width15" >Last used platform</div>
//                    <div class="floatleft width15" >Delete</div>
//                    <div class="clear"></div>
//                ';
//            foreach ( $users as $user ) {
//                echo '
//                <div class="user">
//                    <div class="floatleft width20" >'. $user['username'] .'</div>
//                    <div class="floatleft width25" >'. $user['email'] .'</div>
//                    <div class="floatleft width10" >'. $user['last_ip'] .'</div>
//                    <div class="floatleft width15" >'. $user['last_browser'] .'</div>
//                    <div class="floatleft width15" >'. $user['last_platform'] .'</div>
//                    <div class="floatleft width5 userDelete" userId="'.$user['user_id'].'" data-name="'. $user['username'] .'" >Delete</div>
//                    <div class="clear"></div>
//                </div>
//                    ';
//            }
//            echo '</div>';

            Core::printPageLinks($pageData);
            ?>
        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
