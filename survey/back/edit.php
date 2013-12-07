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
        if ( isset($_SESSION['editHash']) ) {

            $collection = loadDB('surveys');

            $data = $collection->findOne( array( 'hash' => $_SESSION['editHash'] ) );

            echo '
            <div>
            <form class="editSurveyForm">
                <span class="spanTitle">Edit Survey</span>
                <hr />
                <input type="hidden" name="hash" value="'. $_SESSION['editHash'] .'"/>
                <table class="editSurveyTable" id="editSurveyTable">
                    <br/>
                    <div id="errors"> </div>
                    <tr>
                        <td>
                            <label>Enter the title.<br />
                                <input type="text" name="title" placeholder="Survey Title" value="'.$data['title']  .'" data-type="words"/>
                            </label>
                        </td>
                    </tr>
            ';

            $i = 1;
            foreach ( $data[ 'questions' ] as $questions ) {
//                Debug::echoArray($questions);
                echo '
                <tr>
                    <td>
                        <div class="question" data-question='.$i.'>
                            <label>Enter question <span class="questionNumber">'. $i .'</span>.<br>
                                <textarea name="question['. $i .']" placeholder="Question '. $i .'" data-type="words" value="">'. $questions['question'] .'</textarea>
                            </label><br>
                            <input name="ansType['.$i.']" value="'. $questions['answerType'].'" type="hidden" class="answerType"/>
                            ';
                
                if ( $questions['answerType'] == 'multi' ) {
                    echo '
                            <div class="answer">
                                <label>Enter options (separate with commas)<br>
                                    <input type="text" name="multiAnswer['.$i.']" placeholder="Enter Options For Question '.$i.'" data-type="words" value="'. $questions['multiAnswer'] . '">
                                </label>
                            </div>
                    ';
                }
                echo '
                        <hr/>
                        </div>
                    </td>
                </tr>

                ';
                $i++;
            }

            echo '
                <tr>
                    <td>
                        <input type="button" class="createSurveyButton" value="Done.">
                    </td>
                </tr>
            </table>';
        }
        else{
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
                    Pick a survey to edit
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
                <input type="hidden" name="header" value="editPage">
                <input type="hidden" name="redirect" value="true">
                <input type="submit" value="Select">
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