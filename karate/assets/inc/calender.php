<?php
function drawCalendar($className, $widthOffset = 0, $month = null, $year = null, $width = 294, $border = false){
    $border = (( $border )?( 'border="1"' ):( '' ));

    $month = (( isset($month) ) ? ( $month ):( date('m') ));
    $year = (( isset($year) ) ? ( $year ):( date('Y') ));

    $firstDay = mktime(0,0,0, $month, 1, $year);

    $monthName = date('F', $firstDay);

    $startingDay = date('D', $firstDay);
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $blank = 0;

    $widthDay = ($width-1) / 7;
    switch($startingDay){
        case 'Sun' : $blank = 0;break;
        case 'Mon' : $blank = 1;break;
        case 'Tue' : $blank = 2;break;
        case 'Wed' : $blank = 3;break;
        case 'Thu' : $blank = 4;break;
        case 'Fri' : $blank = 5;break;
        case 'Sat' : $blank = 6;break;
    }

    //TODO events
    $startMonth = $year . '-' . $month . '-' . date('d', $firstDay);;
    $endMonth = $year . '-' . $month . '-' . $daysInMonth;
    $q = "SELECT `event_id`, `name`, `description`, `start_date`, `start_time`, `end_time` FROM `47924`.`mr2358174_karate_entity_events`
            AS `mr2358174_karate_entity_events` WHERE `start_date` >= {D '". $startMonth . "' } AND `start_date` <= {D '". $endMonth. "' }";
    //echo $q;
    $r = mysql_query($q);
    $events = array();
    $i = 0;
    while( ($row = mysql_fetch_assoc( $r ) ) ){
        $events[$i++] = $row;
    }
    //TODO end events


    $str = '<div style="width:' . ($width + $widthOffset) . 'px;" class="' . $className .' unselectable">';
    $str .= "<div style='text-align: center;'> $monthName $year </div>";
    $dayNames = array('S','M','T','W','T','F','S');
        foreach($dayNames as $d){
            $str .= '<div style="width:' . $widthDay . 'px;float: left;text-align: center;" class="day title">' . $d .'</div>';
        }
    $str .= '<div style="clear: both;"></div>';

    $dayCount = 1;
    $str .= "";
    while ( $blank > 0 ){
        $str .= '<div style="width:' . $widthDay . 'px;float: left;text-align: center;" class="day blank">&nbsp;</div>';
        $blank = $blank-1;
        $dayCount++;
    }

    $dayNum = 1;

    while ( $dayNum <= $daysInMonth ){

        //TODO events
        $eventStyle='';
        $eventClass='';
        $eventAttr = '';
        foreach($events as $event){
            $eventDay = $event['start_date'];
            $eventDay = split('-', $eventDay);
            if ( $eventDay[2] == $dayNum ) {
                $eventStyle = 'background-color: rgba(128, 1, 1, 0.89);';
                $eventClass = 'event';
                $eventAttr = 'eventId="'. $event['event_id'] .'"';
                break;
            }
        }
        //TODO end events


        $str .=  '<div style="width:' . $widthDay . 'px;float: left;text-align: center;'.$eventStyle.'" class="day '.$eventClass.'"'. $eventAttr .'>' . $dayNum . '</div>';
        $dayNum++;
        $dayCount++;

        if ($dayCount > 7){
            $str .= '<div style="clear: both;"></div>';
            $dayCount = 1;
        }
    }

    while ( $dayCount >1 && $dayCount <=7 ){
        $str .= '<div style="width:' . $widthDay . 'px;float: left;text-align: center;" class="day blank">&nbsp;</div>';
        $dayCount++;
    }
    $str .= '<div style="clear: both;"></div>';
    $str .= '</div>';


    $toJS = array();
    foreach($events as $event){
        $toJS[$event['event_id']] = $event;
    }
?>
<script type="text/javascript">
    var EVENTS = <?php echo json_encode($toJS)?>;
</script>
<?php
    return $str;
}
echo drawCalendar('calendar', 13);

?>

