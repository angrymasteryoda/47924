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
        <h1>View Messages</h1>

        <table class="dataTable">
            <tr>
                <td class="width50">Name <?php echo Core::sortIcons(1)?></td>
                <td class="width40">Email <?php echo Core::sortIcons(2)?></td>
                <td class="width10">Created <?php echo Core::sortIcons(3)?></td>
            </tr>

            <?php
            $collection = loadDB('contact');

            $pageData = Core::getPageData('contact', 5);

            switch(  ( (empty($_GET['o'])) ? ('') : ($_GET['o']) ) ){
                case 1:
                    $sort = 'name';
                    break;
                case 2:
                    $sort = 'email';
                    break;
                case 3:
                    $sort = 'details.created';
                    break;
                default:
                    $sort = 'details.created';
            }

            $ob = intval( ( (empty($_GET['ob'])) ? (MongoCollection::DESCENDING) : ($_GET['ob']) ) );

            $datas = $collection->find()->sort( array( $sort =>  $ob ))->limit( $pageData['ipp'] )->skip( $pageData['starting'] );
            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            foreach ( $data as $message ) {
                echo '
                <tr class="data">
                    <td>'. $message['name'] .'</td>
                    <td>'. $message['email'] .'</td>
                    <td>'. Core::simpleDate( $message['details']['created'] ) .'</td>
                </tr>
                <tr class="data">
                    <td colspan="3" >
                        <div>
                            Message: <br>
                            '. nl2br( $message['message'] ) .'
                        </div>
                    </td>
                </tr>
                ';
            }
            echo '<tr><td colspan="3" class="border_bottom">'.Core::printPageLinks($pageData, false) .'</td></tr>';
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