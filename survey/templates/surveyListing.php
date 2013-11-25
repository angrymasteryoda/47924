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
        <div class="mainForm width65">
            <p class="pageTitle">
                Surveys Available to Take
            </p>
            <table class="width100">
                <tr>
                    <td>Survey</td>
                    <td class="width25 aligncenter">Take</td>
                </tr>
                <?php
                $collection = loadDB('surveys');

                $pageData = Core::getPageData('surveys');
                $datas = $collection->find()->limit( $pageData['ipp'] )->skip( $pageData['starting'] );

                foreach ( $datas as $x ) {
                    $data[] = $x;
                }

                $userCollection = loadDB('users');
                $user = $userCollection->findOne( array( 'username' => $_SESSION['username'] ) );

                echo '<tr>';
                echo 't' . Auth::checkPermissions(SURVEY_TAKE_RIGHTS);
                echo '</tr>';

                foreach ( $data as $survey ) {
                    echo '
                    <tr>
                        <td>'.$survey['title'].'</td>
                        <td>
                            <form action="'.APP_URL.'templates/take.php" method="post">
                                <input type="hidden" name="name" value="'. $survey['hash'] .'"/>';

                    $canTake = true;
                    if ( is_array( $user[ 'surveys' ][ 'taken' ] ) ) {
                        foreach ( $user[ 'surveys' ][ 'taken' ] as $userSurvey ) {
                            //echo $userSurvey;
                            if ( $userSurvey == $survey['hash'] && Auth::checkPermissions( SURVEY_TAKE_RIGHTS ) ) {
                                echo '<input type="button" class="redButton" value="Already taken" />';
                            }
                            else if ( !Auth::checkPermissions( SURVEY_TAKE_RIGHTS ) ) {
                                echo '<input type="button" class="redButton" value="Can\'t take" />';
                            }
                            else {
                                echo '<input type="submit" value="Take" />';
                            }
                        }
                        if ( empty( $user[ 'surveys' ][ 'taken' ] ) ) {
                            if ( !Auth::checkPermissions( SURVEY_TAKE_RIGHTS ) ) {
                                echo '<input type="button" class="redButton" value="Can\'t take" />';
                            }
                            else{
                                echo '<input type="submit" value="Take" />';
                            }

                        }
                    }
                    echo '
                            </form>
                        </td>


                    </tr>
                     ';
                }

                ?>
            </table>
        </div>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>