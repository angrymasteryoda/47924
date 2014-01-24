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

<?php
include '../assets/inc/header.php';
?>
<!-- end of header -->

<div id="main" class="clear"><span class="tm_bottom"></span>

    <div class="largeForm">
        <h1>Delete A Post</h1>

        <table class="dataTable">
            <tr>
                <td class="width80">Title</td>
                <td class="width10">Created</td>
                <td class="width10">Delete</td>
            </tr>

            <?php
            $collection = loadDB('posts');

            $pageData = Core::getPageData('posts');

            $datas = $collection->find()->sort( array( 'details.created' => MongoCollection::ASCENDING ) )->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            foreach ( $data as $post ) {
                echo '
                <tr class="data">
                    <td>'. $post['title'] .'</td>
                    <td>'. Core::simpleDate( $post['details']['created'] ) .'</td>
                    <td><form class="deleteForm" action="'.APP_URL.'libraries/Actions.php" method="post"><input type="hidden" value="deletePost" name="header" /><input type="hidden" value="'. $post['hash'] .'" name="hash" /><input type="submit" value="Delete" class="redButton" /></form></td>
                </tr>
                ';
            }

            ?>
        </table>

    </div>
    <div class="clear"></div>
</div>
<!-- end of main -->

<?php
include APP_URL . 'assets/inc/footer.php';
?>
</body>
</html>