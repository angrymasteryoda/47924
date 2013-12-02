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


        if ( !isset($_GET['survey']) ) {
            $datas = $collection->find();
            foreach ( $datas as $x ) {
                $data[] = $x;
            }

            if ( empty($data) ) {
                Debug::error(404);
                return;
            }
            echo '
            <form class="pickSurveyForm smallForm">
                <p class="pageTitle">
                    Pick a survey to view
                </p>
                <hr/>
                <label>Pick a survey:
                    <select name="survey">';
            foreach ( $data as $survey ) {
                echo '<option value="'. $survey['hash'] .'">'.$survey['title'].'</option>';
            }

            echo '
                    </select>
                </label>
                <input type="submit" value="Select">
            </form>
            ';
        }
        else {
            $survey = $collection->findOne( array('hash' => Security::sanitize( $_GET['survey'] )) );
            $resultColl = loadDB('results');
            $result = $resultColl->findOne( array('hash' => Security::sanitize( $_GET['survey'] )) );

            if ( empty($survey) ) {
                Debug::error(404);
                return;
            }

            echo '
            <table class="surveyResponses dataTable">
                <tr>
                    <td class="padding5_left">Questions and user responses</td>
                </tr>';

            foreach ( $survey[ 'questions' ] as $key => $val ) {
                echo '
                <tr title="Click me to expand" class="data" question="'.$key.'">
                    <td>Question '.$key.'</td>
                </tr>

                <tr>
                    <td>
                        <div class="padding5_left padding5_right none surveyResponse border_bottom" question="'.$key.'">
                            <p >'.$val['question'].'</p><br>
                            <p>Responses</p>';

                echo '
                            <table class="width100 response border">
                                <tr>
                                    <td>Responses</td>
                                    <td>User</td>
                                </tr>';
                $i = 0;
                foreach ( $result['answers'][$key] as $answers ) {
                    echo '
                            <tr class="data">
                                <td class="width75">'. $answers . '</td>
                                <td>'. $result['details']['takenBy'][$i++] .'</td>
                            </tr>';
                }
                echo '</table>';


                echo '      <br><br>
                        </div>
                    </td>
                </tr>
                ';
            }


            echo '</table>';


            echo '
            <form class="deleteUserResponse smallForm margin10_top">
                <p class="pageTitle">
                    Delete user response
                </p>
                <hr/>
                <input name="hash" type="hidden" value="'. $_GET['survey'] .'" />
                <label>Pick a user:
                    <select name="user">';
            $i = 0;
            foreach ( $result['details']['takenBy'] as $person ) {
                echo '<option value="'. $person .'~~'. $i++ .'">' . $person . '</option>';
            }

            echo '
                    </select>
                </label>
                <input type="submit" value="Delete" />
            </form>
            ';


        }

        ?>


    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>