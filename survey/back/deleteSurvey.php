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
        <?php
            $collection = loadDB('surveys');
            $datas = $collection->find();
            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            if ( empty($data) ) {
                Debug::error(404);
                return;
            }
            echo '
            <form class="pickSurveyForm smallForm" action="'.APP_URL .'libraries/Actions.php" method="post">
                <p class="pageTitle">
                    Pick a survey to delete
                </p>
                <hr/>
                <label>Pick a survey:
                    <select name="hash">';
            foreach ( $data as $survey ) {
                echo '<option value="'. $survey['hash'] .'">'.$survey['title'].'</option>';
            }

            echo '
                    </select>
                </label>
                <input type="hidden" name="header" value="deleteSurvey">
                <input type="hidden" name="redirect" value="true">
                <input type="submit"  class="redButton" value="Delete">
            </form>
            ';
        ?>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>