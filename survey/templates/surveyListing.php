<?php
include_once '../config/global.php';
checkLogin();
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
        <p class="pageTitle">
            Surveys Available to Take
        </p>
        
<!--        <div>-->
<!--            <div class="floatleft">Surveys</div>-->
<!--            <div class="floatleft">Take</div>-->
<!--            <div class="floatleft">Delete</div>-->
<!--            <div class="floatleft">Get results</div>-->
<!--        </div>-->
        <table>
            <tr>
                <td>Survey</td>
                <td>Take</td>
            </tr>
            <?php
            $dbName = DB_NAME;
            $connection = new Mongo(DB_HOST);
            $db = $connection->$dbName;
            $collection = $db->surveys;

            $pageData = Core::getPageData('surveys');
            $datas = $collection->find()->limit( $pageData['ipp'] )->skip( $pageData['starting'] );

            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            foreach ( $data as $survey ) {
                echo '
                <tr>
                    <td>'.$survey['title'].'</td>
                    <td>
                        <form action="'.APP_URL.'templates/take.php" method="post">
                            <input type="hidden" name="name" value="'.$survey['hash'].'"/>
                            <input type="submit" value="Take" />
                        </form>
                    </td>


                </tr>
                 ';
            }

            ?>
        </table>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>