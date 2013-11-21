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
        <?php
        $dbName = DB_NAME;
        $connection = new Mongo(DB_HOST);
        $db = $connection->$dbName;
        $collection = $db->users;

        $pageData = Core::getPageData('users');

        $datas = $collection->find()->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
        foreach ( $datas as $x ) {
            $data[] = $x;
        }

        echo '<table>';
        foreach ( $data as $user ) {
            echo '
            <tr>
                <td>'.$user['username'].'</td>
                <td>'.$user['email'].'</td>
                <td>'.$user['details']['lastIp'].'</td>
                <td>'. Core::simpleDate( $user['details']['created'] ).'</td>
            </tr>';
        }
        echo '</table>';

        Core::printPageLinks($pageData, true);

        Debug::echoArray($data);
        ?>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>