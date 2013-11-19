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
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            View Students
        </p>
        <div>
            <?php
            loadDB(DB_NAME);
            $sort ='';
            $sortBy = ( (( (empty($_GET['ob'])) ? ('0') : ($_GET['ob']) ) == '1') ? ( 'DESC' ) : ( 'ASC' ) );
            switch(  ( (empty($_GET['o'])) ? ('') : ($_GET['o']) ) ){
                case 1:
                    $sort = '`name`';
                    break;
                case 2:
                    $sort = '`age`';
                    break;
                case 3:
                    $sort = '`phone`';
                    break;
                case 4:
                    $sort = '`email`';
                    break;
                case 5:
                    $sort = '`date`';
                    break;
                default:
                    $sort = '`date`';
            }

            $pageData = Core::getPageData('mr2358174_karate_entity_student');
            $limit = 'LIMIT ' . $pageData['starting'] .',' . $pageData['ipp'];

            $q = "SELECT `student_id`, `name`, `age`, `phone`, `email`, `past_experience`, `belts`, `is_child`, `parent_name`, `parent_cell`, `comments`, `current_belt`, `new`, UNIX_TIMESTAMP(`date`) as `date` FROM `mr2358174_karate_entity_student`
                ORDER BY `new` DESC, $sort $sortBy $limit;";
            $r = mysql_query($q);
            $students = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $students[$i++] = $row;
            }
            //Debug::echoArray($students);

            echo '
            <table class="margin10 students">
                <tbody>
                    <tr>
                        <td>Student Name'. Core::sortIcons(1) .'</td>
                        <td>Age'. Core::sortIcons(2) .'</td>
                        <td>Phone'. Core::sortIcons(3) .'</td>
                        <td>Email'. Core::sortIcons(4) .'</td>
                        <td>Date Registered'. Core::sortIcons(5) .'</td>
                    </tr>
                    <tr class="redHeader"><td>New Students</td></tr>
            ';

            $printRead = false;
            $count = 0;
            if ( @$students[0]['new'] == 0 || empty( $students ) ) {
                echo '<tr class="read"><td colspan="5" class="bold aligncenter">No New Students</td></tr>';
            }
            foreach ( $students as $student ) {
                if ( ( !$student['new'] && !$printRead) || empty($students)) {
                    echo '<tr class="redHeader"><td>Current Students</td></tr>';
                    $printRead = true;
                }
                $isChild = false;
                $hasExperience = false;
                if ( $student['is_child'] ) {
                    $isChild = true;
                }
                if ( $student['past_experience'] ) {
                    $hasExperience = true;
                }

                echo '
                    <tr class="' . ( ($printRead) ? ('read') : ('unread') ). ' student" studentId="'. $student['student_id'] .'">
                        <td>'.$student['name'].'</td>
                        <td>'.$student['age'].'</td>
                        <td>'.$student['phone'].'</td>
                        <td>'.$student['email'].'</td>
                        <td>'. Core::messageSentDate( $student['date'] ).'</td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div studentId="'. $student['student_id'] .'" class="none fullStudent margin5_left">
                                <span class="studentDelete underline pointer floatright font11pt margin5_right">Delete</span>'.
                                ( ($student['new']) ?
                                    ('<span class="markStudentAs underline pointer floatright font11pt margin5_right" data-state="0">Mark as Not New</span>') :
                                    ('<span class="markStudentAs underline pointer floatright font11pt margin5_right" data-state="1">Mark as New</span>') ) . '
                            <span class="studentEdit underline pointer floatright font11pt margin5_right" data-edit="1">Edit</span>
                                <br>
                                <div>
                                    Student Name: '. $student['name'] .'<br>
                                    Age: '. $student['age'] .'<br>
                                    Phone: '. $student['phone'] .'<br>
                                    Email: '. $student['email'] .'<br>'.
                                    ( ($hasExperience) ? ('Experience: Yes<br>Belt: ' . $student['belts'] .'<br>') : ('') ) .
                                    ( ($isChild) ? ('Parent Name: ' . $student['parent_name'] .'<br>Parent Cell: ' . $student['parent_cell']. '<br>') : ('') ).
                                    'Comments: ' . $student['comments'] . '<br>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
            }
            if( empty($students) ){
                echo '<tr class="redHeader"><td>Current Students</td></tr>';
                echo '<tr class="read"><td colspan="5" class="bold aligncenter">No Current Students</td></tr>';
            }
            echo'</tbody>
            </table>';

            Core::printPageLinks($pageData);
            ?>
        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
