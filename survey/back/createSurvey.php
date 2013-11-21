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
            <span class='questionNumber' style="display: none;">0</span>
            <div class="createSurveyForm">
                <span class="spanTitle">Create Survey</span>
                <hr />
                <table class="createSurveyTable" id="createSurveyTable">

                    <br/>
                    <span id="errors"> </span>
                    <tr>
                        <td>
                            <label>Enter the title.</label><br />
                            <input type="text" id="title" value=""/>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="button" class="addQuestion" value="New question"/>
                        </td>
                        <td>
                            <input type="button" class="addQuestion2" value="New question"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="button" class="button" value="Done. Create It." onclick="createSurvey()" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <?php
            echo sha1('123456') . '<br>';
            echo sha1('admin123456') . '<br>';
            echo sha1('admin') . '<br>';
            echo sha1('123456') . '<br>';
            echo sha1('123456') . '<br>';
        ?>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>