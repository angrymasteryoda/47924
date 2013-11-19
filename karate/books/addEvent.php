<?php
include_once '../config/global.php';
if ($_SESSION['time'] + 10 * 60 < time()) {
    unset( $_SESSION['time'] );
    unset( $_SESSION['username'] );
    header( 'Location: ../books/login.php' ) ;
} else {
    if( empty( $_SESSION['username'] )){
        header( 'Location: ../books/login.php' ) ;
    }
    else{
        $_SESSION['time'] = time();
    }
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
    include APP_URL . 'assets/inc/header.php';
    ?>

    <div id="content">
        <p class="pageTitle">
            Add Events
        </p>
        <div class="width35 centermargin">
            <p class="margin0_left">Add Events</p>
            <form class="input addEventForm" >
                <p id="errors">

                </p>
                <label>Name:
                    <input name="name" type="text" placeholder="Name" data-type="length-4"/>
                </label>
                <label>Description:
                    <textarea name="description" placeholder="Description" data-type="length-7" ></textarea>
                </label>
                <label>Date:
                    <input name="date" placeholder="Date" type="date" data-type="date"/>
                </label>
                <label>Start Time:
                    <input name="startTime" placeholder="Start Time" type="time" data-type="time"/>
                </label>
                <label>End Time:
                    <input name="endTime" placeholder="End Time" type="time" data-type="time"/>
                </label>
                <input class="submit" type="submit" value="Add Event" />
            </form>
        </div>
        <div>
            <p class="redHeader aligncenter">
                Delete Events
            </p>
            <?php
            $sort ='';
            $sortBy = ( (( (empty($_GET['ob'])) ? ('0') : ($_GET['ob']) ) == '1') ? ( 'DESC' ) : ( 'ASC' ) );
            switch(  ( (empty($_GET['o'])) ? ('') : ($_GET['o']) ) ){
                case 1:
                    $sort = '`name`';
                    break;
                case 2:
                    $sort = '`Description`';
                    break;
                case 3:
                    $sort = '`start_date`';
                    break;
                case 4:
                    $sort = '`start_time`';
                    break;
                case 5:
                    $sort = '`end_time`';
                    break;
                default:
                    $sort = '`start_date`';
            }

            loadDB(DB_NAME);
            $pageData = Core::getPageData('mr2358174_karate_entity_events');
            $startingPoint = $pageData['starting'];
            $ipp = $pageData['ipp'];
            //normal query

            $q = "SELECT `event_id`, `name`, `description`, `start_date`, `start_time`, `end_time` FROM `mr2358174_karate_entity_events`
            ORDER BY $sort $sortBy LIMIT $startingPoint, $ipp;";
            $r = mysql_query($q);
            $events = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $events[$i++] = $row;
            }

            echo '
                <table class="margin10 events">
                <tbody>
                    <tr>
                        <td>Name'. Core::sortIcons(1) .'</td>
                        <td>Description'. Core::sortIcons(2) .'</td>
                        <td>Date'. Core::sortIcons(3) .'</td>
                        <td>Start time'. Core::sortIcons(4) .'</td>
                        <td>End time'. Core::sortIcons(5) .'</td>
                        <td>Delete</td>
                    </tr>
            ';
            foreach ( $events as $event ) {
                echo '
                    <tr class="data">
                        <td>'.$event['name'] . '</td>
                        <td>'.$event['description'].'</td>
                        <td>'.$event['start_date'] .'</td>
                        <td>'.$event['start_time'] .'</td>
                        <td>'.$event['end_time'] .'</td>
                        <td class="eventDelete" eventId="'. $event['event_id'].'" data-name="'. $event['name'] .'" >Delete</td>
                    </tr>
                    ';
            }
            echo '</table>';

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
