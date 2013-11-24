<?php
include_once '../config/global.php';
checkLogin();
$collection = loadDB('surveys');
$data = $collection->findOne(array('hash' => $_POST['name']));
if ( isset( $data ) ) {
    goToError(404);
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
    include APP_URL .'assets/inc/header.php';
    ?>

    <div class="content">
        <div>
            <form class="surveyForm">
                <?php
                if( isset( $data ) ){
                    echo '<div class="alignCenter">';
                    Debug::echoArray($data);
                    echo '</div>';

                    echo '<div class="pageTitle">'. $data['title'] .'</div><hr />';

                    echo '<form class="">';
                    $i = 1;
                    foreach ( $data[ 'questions' ] as $question ) {
                        Core::printQuestion($question, $i);
                        $i++;
                    }
                    echo '<input type="submit" value="Submit" />';
                }
                else{
                    Debug::error(404);
                }
                ?>

            </form>
<!--                <span class="spanTitle">Survey title</span>-->
<!--                <hr />-->
<!--                <table class="surveyTable" id="surveyTable">-->
<!---->
<!--                    <br/>-->
<!--                    <span id="errors"> </span>-->
<!--                    <tr>-->
<!--                        <td>-->
<!--                            <label>Enter the title.</label><br />-->
<!--                            <input type="text" name="title" placeholder="Survey Title" value=""/>-->
<!--                        </td>-->
<!--                    </tr>-->
<!---->
<!--                    <tr data-question="1">-->
<!--                        <td>-->
<!--                            <div class="question" data-question="1">-->
<!--                                <label>Enter the question <span class="questionNumber">1</span>.<br>-->
<!--                                    <textarea name="question[1]" placeholder="Question 1"></textarea></label><br>-->
<!--                                Answer Type:-->
<!--                                <select name="ansType[1]" class="answerType">-->
<!--                                    <option value="single" title="Single textfield is given to survey taker">Single Answer</option>-->
<!--                                    <option value="multi" title="Radio boxs are given to survey taker">Multi Answer</option>-->
<!--                                    <option value="write" title="A textare is given to survey taker">Write In</option>-->
<!--                                    <option value="t/f" title="A true false option is given to survey taker">True/False</option>-->
<!--                                </select>-->
<!--                                <div class="answer none"></div>-->
<!--                                <hr>-->
<!--                            </div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!---->
<!--                    <tr class='addButton'>-->
<!--                        <td>-->
<!--                            <input type="button" class="addQuestion" value="New question"/>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>-->
<!--                            <input type="button" class="createSurveyButton" value="Done. Create It." />-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </table>-->
                <div id="waiting" class="none">
                    Thanks for voting.<br>
                    Lat me do the paperwork real quick<br><br>
                    <img width="70" height="70" src="<?php echo APP_URL?>assets/img/loading.gif" alt="Working"/>
                    <br>
                </div>
            </form>

        </div>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>