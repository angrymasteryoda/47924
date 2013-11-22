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
        <div>
            <form class="createSurveyForm">
                <span class="spanTitle">Create Survey</span>
                <hr />
                <table class="createSurveyTable" id="createSurveyTable">

                    <br/>
                    <span id="errors"> </span>
                    <tr>
                        <td>
                            <label>Enter the title.</label><br />
                            <input type="text" name="title" placeholder="Survey Title" value=""/>
                        </td>
                    </tr>

                    <tr data-question="1">
                        <td>
                            <div class="question">
                                <label>Enter the question <span class="questionNumber">1</span>.<br>
                                    <textarea placeholder="Question 1"></textarea></label><br>
                                Answer Type:
                                <select class="answerType">
                                    <option value="single">Single Answer</option>
                                    <option value="multi">Multi Answer</option>
                                    <option value="write">Write In</option>
                                    <option value="t/f">True/False</option>
                                </select>
                                <div class="answer none"></div>
                            </div>
                        </td>
                    </tr>

                    <tr class='addButton'>
                        <td>
                            <input type="button" class="addQuestion" value="New question"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="submit" class="createSurveyButton" value="Done. Create It." />
                        </td>
                    </tr>
                </table>
                <div id="waiting" class="none">
                    <img width="70" height="70" src="<?php echo APP_URL?>assets/img/loading.gif" alt="Working"/>
                </div>
            </form>

        </div>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
<script>deleteCookie('qnum');</script>
</body>
</html>