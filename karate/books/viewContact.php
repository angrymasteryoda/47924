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
            View Messages
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
                    $sort = '`email`';
                    break;
                case 3:
                    $sort = '`phone`';
                    break;
                case 4:
                    $sort = '`date`';
                    break;
                default:
                    $sort = '`date`';
            }

            $pageData = Core::getPageData('mr2358174_karate_entity_contact');
            $limit = 'LIMIT ' . $pageData['starting'] .',' . $pageData['ipp'];

            $q = "SELECT `contact_id`, `name`, `email`, `phone`, `message`, UNIX_TIMESTAMP(`date`) as `date`, `read` FROM `mr2358174_karate_entity_contact`
                ORDER BY `read` ASC, $sort $sortBy $limit";
            $r = mysql_query($q);
            $contacts = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $contacts[$i++] = $row;
            }
            //Debug::echoArray($contacts);

            echo '
            <table class="margin10 messages">
                <tbody>
                    <tr>
                        <td>Name'. Core::sortIcons(1) .'</td>
                        <td>Email'. Core::sortIcons(2) .'</td>
                        <td>Phone'. Core::sortIcons(3) .'</td>
                        <td>Date'. Core::sortIcons(4) .'</td>
                    </tr>
                    <tr class="redHeader"><td>Unread Messages</td></tr>
            ';

            $printRead = false;
            $count = 0;
            if ( @$contacts[0]['read'] == 1 || empty( $contacts ) ) {
                echo '<tr class="read"><td colspan="4" class="bold aligncenter">No Unread Messages</td></tr>';
            }
            foreach ( $contacts as $contact ) {
                if ( ($contact['read'] && !$printRead) || empty($contacts)) {
                    echo '<tr class="redHeader"><td>Read Messages</td></tr>';
                    $printRead = true;
                }

                echo '
                    <tr class="' . ( ($printRead) ? ('read' ) : ('unread') ). ' message" messageId="'. $contact['contact_id'] .'">
                        <td>'.$contact['name'].'</td>
                        <td>'.$contact['email'].'</td>
                        <td>'.$contact['phone'].'</td>
                        <td>'. Core::messageSentDate( $contact['date'] ).'</td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div messageId="'. $contact['contact_id'] .'" class="none fullMessage margin5_left">
                                Message Received: <span class="messageDelete underline pointer floatright font11pt margin5_right">Delete</span>
                                <span class="markRead underline pointer floatright font11pt margin5_right">Mark as Read</span>
                                <br>
                                <span class="margin5_left">
                                    '. $contact['message'] .'


                                </span>
                            </div>
                        </td>
                    </tr>
                ';
            }
            if( empty($contacts) ){
                echo '<tr class="redHeader"><td>Read Messages</td></tr>';
                echo '<tr class="read"><td colspan="4" class="bold aligncenter">No Read Messages</td></tr>';
            }
            echo'</tbody>
            </table>';

            Core::printPageLinks($pageData);
//            echo '
//            <div class="margin20_top margin10_left margin10_right tableView">
//                    <div class="floatleft width20" >Username</div>
//                    <div class="floatleft width20" >Email</div>
//                    <div class="floatleft width15" >Phone</div>
//                    <div class="floatleft width15" >read?</div>
//                    <div class="floatleft width15" >Delete</div>
//                    <div class="clear"></div>
//                ';
//            foreach ( $contacts as $contact ) {
//                echo '
//                <div class="user">
//                    <div class="floatleft width20" >'. $contact['name'] .'</div>
//                    <div class="floatleft width20" >'. $contact['email'] .'</div>
//                    <div class="floatleft width15" >'. $contact['phone'] .'</div>
//                    <div class="floatleft width15" >'. ( ( $contact['read'] ) ? ('<input type="checkbox" checked/>') : ('<input type="checkbox"/>') ) .'</div>
//                    <div class="floatleft width5 userDelete" userId="'.$contact['contact_id'].'" >Delete</div>
//                    <div class="clear"></div>
//                    <div class="" >'. $contact['message'] .'</div>
//                </div>
//                    ';
//            }
//            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    include APP_URL . 'assets/inc/footer.php';
    ?>
</div>
</body>
</html>
