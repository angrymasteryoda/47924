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
                Debug::echoArray($questions);
                echo '
                <tr>
                    <td>
                        <label>Enter question <span class="questionNumber">'. $i .'</span>.<br>
                            <textarea name="question['. $i .']" placeholder="Question '. $i .'" data-type="words" value="">'. $questions['question'] .'</textarea>
                        </label><br>
                    </td>
                </tr>
                ';
            }


//            Debug::echoArray($data);

            echo '</table>';
        }
        ?>
    </div>
</div>
<?php
include '../assets/inc/footer.php';
?>
</body>
</html>