<?php
include_once '../config/global.php';
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
    loadDB(DB_NAME);
    ?>

    <div id="content">
        <p class="pageTitle">
            Event Info
        </p>
        <div class="eventInfo">
            <?php
            function invalidDate(){
                echo '
                <div class="redHeader">
                    Invalid event
                </div>
                ';
            }
            if ( intval( Security::sanitize( $_GET['id'] ) ) != null ) {
                $q = "SELECT `name`, `description`, DATE_FORMAT(`start_date`,'%m/%d/%Y') AS start_date, `start_time`, `end_time` FROM `mr2358174_karate_entity_events` WHERE `event_id` = '". intval( Security::sanitize( $_GET['id'] ) ) ."';";
                $r = mysql_query($q);
                if( $r ){
                    $events = array();
                    $i = 0;
                    while( ($row = mysql_fetch_assoc( $r ) ) ){
                        $events[$i++] = $row;
                    }
                    if( empty( $events ) ){
                        invalidDate();
                    }
                    else{
                        $event = $events[0];

                        echo '
                        <div>
                            ' . $event['name'] . '
                        </div>
                        <div>
                            ' . $event['description'] . '
                        </div>
                        <div>
                            ' . $event['start_date'] . ' Starting at: ' . Core::getHumanTime($event['start_time']) . ' - ' . Core::getHumanTime($event['end_time']) . '
                        </div>
                        ';
                    }
                }
                else{
                    invalidDate();
                }
            }
            else{
                invalidDate();
            }


            ?>
<!--            <div>-->
<!--                title-->
<!--            </div>-->
<!--            <div>-->
<!--                description blah blah blah blah blah blah blah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blahblah blah-->
<!--            </div>-->
<!--            <div>aslkjlskjaskljdaskldjlaskdjaslkdjasdkljldjdj</div>-->
        </div>

    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>


</body>
</html>


