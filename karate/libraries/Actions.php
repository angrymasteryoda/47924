<?php
/**
 * Created by IntelliJ IDEA.
 * User: Michael
 * Date: 10/4/13
 * Time: 4:39 PM
 * To change this template use File | Settings | File Templates.
 */
include '../config/global.php';
$paths = glob( '../libraries/class.*.php' );
foreach($paths as $path){
    require_once($path);
}
switch( Security::sanitize( $_POST['header'] ) ){
    //delete a term from data base

    case 'termDelete' :
        loadDB(DB_NAME);
        $id = Security::sanitize( $_POST['termId'] );
        $q = 'DELETE FROM `mr2358174_karate_entity_terms` WHERE termId='. $id .';';
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true );
        }
        else{
            $return = array( 'errors' => false );
        }
        echo json_encode($return);
        closeDB();
        break;

    case 'addTerm':
        $term = Security::sanitize( $_POST['term'], false );
        $meaning = Security::sanitize( $_POST['meaning'], false );
        $section = Security::sanitize( $_POST['section'], false );

        //find this
        $sectionId;
        loadDB(DB_NAME);

        $q = "SELECT `id`, `section` FROM `mr2358174_karate_enum_sections` AS `mr2358174_karate_enum_sections`";
        $r = mysql_query($q);
        $sections = array();
        $i = 0;
        while( ($row = mysql_fetch_assoc( $r ) ) ){
            $sections[$i++] = $row;
        }

        for( $i = 0; $i < count($sections); $i++ ){
            if($sections[$i]['section'] == $section){
                $sectionId = $sections[$i]['id'];
            }
        }

        $q = "INSERT INTO `mr2358174_karate_entity_terms` (`term`,`meaning`,`section`) VALUES ('" . $term ."','" . $meaning . "','" . $sectionId . "');";
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true);
        }
        else{
            $return = array( 'errors' => false, 'term' => $term, 'meaning' => $meaning, 'section' => $section );
        }
        echo json_encode($return);
        closeDB();
        break;

    case 'login' :
        loadDB(DB_NAME);
        $login = false;
        $username = Security::sanitize( $_POST['username'], NO_QUOTES );
        $canProceed = true;

        $errors = Validation::validate( array(
            'username' => $username,
            'complex-password' => Security::sanitize( $_POST['password'], NO_QUOTES )
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if ( $canProceed ) {
            $password = Security::md5( Security::sanitize( $_POST['password'], NO_QUOTES ) );
            $q = "SELECT `username`, `password`, `last_ip` FROM `47924`.`mr2358174_karate_entity_users` AS `mr2358174_karate_entity_users` WHERE `username` = '".$username."' AND `password` = '".$password."';";
            $r = mysql_query($q);
            $users = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $users[$i++] = $row;
            }

            foreach ( $users as $user ) {
                if($user['username'] == $username){
                    if($user['password'] == $password){
                        $browser = Core::parseUserAgent();
                        $login = true;
                        $ip = Core::getClientIP();
                        $q = "UPDATE `mr2358174_karate_entity_users` SET `last_ip`='". $ip ."', `last_browser`='". $browser['browser'] ."',
                                `last_browser_version`='". $browser['version'] ."', `last_platform`='". $browser['platform'] ."'
                                WHERE `username` = '".$username."' AND `password` = '".$password."';";
                        $r = mysql_query($q);
                        $_SESSION['username'] = $username;
                        $_SESSION['time'] = time();
                        $_COOKIE['username'] = $username;
                        //header( 'Location: '. APP_URL . 'books/' ) ;
                    }
                }
            }


        }
        $errors['login'] = $login;
        echo json_encode( $errors );
        closeDB();
        break;

    case 'contact' :
        loadDB(DB_NAME);
        $canProceed = true;
        $errors = Validation::validate( array(
            'name' => Security::sanitize( $_POST['name'], NO_QUOTES ),
            'email' => Security::sanitize( $_POST['email'], NO_QUOTES ),
            'usphone' => Security::sanitize( $_POST['phone'], NO_QUOTES ),
            'extension' => Security::sanitize( $_POST['extension'], NO_QUOTES ),
            'length-7' => Security::sanitize( $_POST['message'], NO_QUOTES )
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if ( $canProceed ) {
            $isExtension = true;
            if ( $_POST['extension'] == '' )$isExtension = false;//is extension there
            $q = "INSERT INTO `mr2358174_karate_entity_contact` (`name`, `email`, `phone`, `message`, `read`) VALUES
            ('" . Security::sanitize( $_POST['name'], NO_QUOTES ) ."',
            '" . Security::sanitize( $_POST['email'], NO_QUOTES ) . "',
            '" . Security::sanitize( $_POST['phone'], NO_QUOTES ) .
                ( ( $isExtension ) ? (' ext '. Security::sanitize( $_POST['extension'], NO_QUOTES ) . "',") : ("',") ) .
            "'" . Security::sanitize( $_POST['message'], NO_QUOTES ) . "',
             0);";
            $r = mysql_query( $q );
            if( !$r ){
                $errors = array( 'info' => mysql_error(), 'errors' => true);
            }
//            echo ( ( $isExtension ) ? (' ext '. Security::sanitize( $_POST['extension'], NO_QUOTES ) . "',") : ("',") );
        }

        echo json_encode( $errors );
        closeDB();
        break;

    case 'readMessage' :
        loadDB(DB_NAME);
        $canProceed = true;
        $errors = Validation::validate( array(
            'numbers' => Security::sanitize( $_POST['messageId'], NO_QUOTES )
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if( $canProceed ){
            $q = "UPDATE `mr2358174_karate_entity_contact` SET `read`=1 WHERE `contact_id` = '" . Security::sanitize( $_POST['messageId'], NO_QUOTES ) . "';";
            $r = mysql_query($q);
        }
        echo json_encode($errors);
        closeDB();
        break;

    case 'deleteMessage' :
        loadDB(DB_NAME);
        $id = Security::sanitize( $_POST['messageId'] );
        $q = 'DELETE FROM `mr2358174_karate_entity_contact` WHERE `contact_id`='. $id .';';
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true );
        }
        else{
            $return = array( 'errors' => false );
        }
        echo json_encode($return);
        closeDB();
        break;

    case 'addUser':
        loadDB(DB_NAME);
        $canProceed = true;
        $errors = Validation::validate( array(
            'username' => Security::sanitize( $_POST['username'] ),
            'complex-password' => Security::sanitize( $_POST['password'] ),
            'email' => Security::sanitize( $_POST['email'] )
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if ( $canProceed ) {
            $username = Security::sanitize( $_POST['username'], NO_QUOTES );
            $password = Security::md5( Security::sanitize( $_POST['password'], NO_QUOTES ) );
            $browser = Core::parseUserAgent();
            $ip = Core::getClientIP();
            $q = "SELECT `username`, `password`, `last_ip` FROM `47924`.`mr2358174_karate_entity_users` AS `mr2358174_karate_entity_users` WHERE `username` = '".$username."' AND `password` = '".$password."';";
            $r = mysql_query($q);
            $users = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $users[$i++] = $row;
            }
            $userExists = false;
            foreach ( $users as $user ) {
                if($user['username'] == $username){
                    $userExists = true;
                }
            }

            if( !$userExists){
                $q = "INSERT INTO `mr2358174_karate_entity_users` (`username`, `password`, `last_ip`, `email`, `last_browser`, `last_browser_version`, `last_platform`) VALUES
                        ('".$username."', '". $password ."',
                         '".$ip."', '".Security::sanitize( $_POST['email'], NO_QUOTES )."', '".$browser['browser']."', '".$browser['version']."', '".$browser['platform']."' );";
                $r = mysql_query($q);
            }

            $errors['login'] = !$userExists;

        }
        echo json_encode($errors);
        closeDB();
        break;

    case 'userDelete' :
        loadDB(DB_NAME);
        $id = Security::sanitize( $_POST['userId'] );
        $q = 'DELETE FROM `mr2358174_karate_entity_users` WHERE user_id='. $id .';';
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true );
        }
        else{
            $return = array( 'errors' => false );
        }
        echo json_encode($return);
        closeDB();
        break;

    case 'addEvent' :
        loadDB(DB_NAME);
        $canProceed = true;
        $name = Security::sanitize( $_POST['name'], NO_QUOTES );
        $desc = Security::sanitize( $_POST['description'], NO_QUOTES );
        $date = Security::sanitize( $_POST['date'], NO_QUOTES );
        $sTime = Security::sanitize( $_POST['startTime'], NO_QUOTES );
        $eTime = Security::sanitize( $_POST['endTime'], NO_QUOTES );
        $errors = Validation::validate( array(
            'length-4' => $name,
            'length-7' => $desc,
            'date' => $date,
            'startTime' => $sTime,
            'endTime' => $eTime
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }
        if ( $canProceed ) {
            $q = "INSERT INTO `mr2358174_karate_entity_events` (`name`,`description`,`start_date`, `start_time`, `end_time`)
                VALUES ('". $name ."', '". $desc ."', '". $date ."', '". $sTime ."', '". $eTime ."' );";
            $r = mysql_query( $q );
            if( !$r ){
                $errors['mysql'] = false;//mysql_error();
            }
        }

        echo json_encode($errors);
        break;

    case 'eventDelete' :
        loadDB(DB_NAME);
        $id = Security::sanitize( $_POST['eventId'] );
        $q = 'DELETE FROM `mr2358174_karate_entity_events` WHERE event_id='. $id .';';
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true );
        }
        else{
            $return = array( 'errors' => false );
        }
        echo json_encode($return);
        closeDB();
        break;
    
    case 'signUp' :
        loadDB(DB_NAME);
        $canProceed = true;
        $name = Security::sanitize( $_POST['name'], NO_QUOTES );
        $email = Security::sanitize( $_POST['email'], NO_QUOTES );
        $phone = Security::sanitize( $_POST['phone'], NO_QUOTES );
        $extension = Security::sanitize( $_POST['extension'], NO_QUOTES );
        $age = Security::sanitize( $_POST['age'], NO_QUOTES );
        $whoFor = Security::sanitize( $_POST['whoFor'], NO_QUOTES );
        $pName = Security::sanitize( $_POST['pName'], NO_QUOTES);
        $pCell = Security::sanitize( $_POST['pCell'], NO_QUOTES);
        $experience = Security::sanitize( $_POST['experience'], NO_QUOTES );
        $belts = Security::sanitize( $_POST['belt'], NO_QUOTES );
        $comments = Security::sanitize( $_POST['comments'], NO_QUOTES );
        
        
        $errors = Validation::validate( array(
            'length-4' => $name,
            'email' => $email,
            'usphone' => $phone,
            'extension' => $extension,
        ));

        switch ($whoFor){
            case 'Self':
                $errors['whoFor']=true;
                $whoFor = 0;
                $pName = '';
                $pCell = '';
                break;
            case 'Child':
                $errors['whoFor']=true;
                $errors['pName']= Validation::validate('pName', $pName);
                $errors['pCell']= Validation::validate('pCell', $pCell);
                $whoFor = 1;
                break;
            default:$errors['whoFor']=false;break;
        }
        switch ($experience){
            case 'Yes':
                $errors['experience']=true;
                $errors['belt'] = Validation::validate('belt', $belts);
                $experience = 1;
                break;
            case 'No':
                $errors['experience']=true;
                $experience = 0;
                $belts ='';
                break;
            default:$errors['experience']=false;break;
        }

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }
        if ( $canProceed ) {
            $age = intval($age);

            $q = "INSERT INTO `mr2358174_karate_entity_student` (`name`, `age`, `phone`, `email`, `past_experience`, `belts`, `is_child`, `parent_name`, `parent_cell`, `comments`, `new`)
            VALUES ('$name', $age, '$phone', '$email', $experience, '$belts', $whoFor, '$pName', '$pCell', '$comments', 1)";
            $r = mysql_query( $q );
            if( !$r ){
                $errors['mysql'] = false;//mysql_error();
            }
        }
        closeDB();
        echo json_encode( $errors );
        break;

    case 'markStudentAs' :
        loadDB(DB_NAME);
        $canProceed = true;
        $errors = Validation::validate( array(
            'numbers' => Security::sanitize( $_POST['studentId'], NO_QUOTES ),
            'boolean' => Security::sanitize( $_POST['markAs'], NO_QUOTES )
        ));

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if( $canProceed ){
            $markAs = Security::sanitize( $_POST['markAs'], NO_QUOTES );
            $q = "UPDATE `mr2358174_karate_entity_student` SET `new`= '$markAs' WHERE `student_id` = '" . Security::sanitize( $_POST['studentId'], NO_QUOTES ) . "';";
            $r = mysql_query($q);
        }
        echo json_encode($errors);
        closeDB();
        break;

    case 'deleteStudent' :
        loadDB(DB_NAME);
        $id = Security::sanitize( $_POST['studentId'], NO_QUOTES );
        $q = 'DELETE FROM `mr2358174_karate_entity_student` WHERE `student_id`='. $id .';';
        $r = mysql_query( $q );
        if( !$r ){
            $return = array( 'info' => mysql_error(), 'errors' => true );
        }
        else{
            $return = array( 'errors' => false );
        }
        echo json_encode($return);
        closeDB();
        break;

    case 'getEditPerm' :
        loadDB(DB_NAME);
        $pageFrom = $_POST['page'];
        $editId = Security::sanitize( $_POST['editId'], NO_QUOTES );

        $canProceed = true;
        $errors = Validation::validate( array(
            'numbers' => $editId
        ));


        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }

        if( $canProceed && isset($_SESSION['username'])){
            $q = "SELECT `title`, `allowed_parents` FROM `mr2358174_karate_entity_edit` WHERE `edit_id` = '$editId'";
            $r = mysql_query($q);
            $edits = array();
            $i = 0;
            while( ($row = mysql_fetch_assoc( $r ) ) ){
                $edits[$i++] = $row;
            }
            mysql_free_result($r);
            $pageFrom = explode('/books/', $pageFrom);
            if ( $edits[0]['allowed_parents'] ==  $pageFrom[1]) {
                //echo md5( rand() );
                $errors['user'] = md5( $_SESSION['username'] );
                $errors['hash'] = md5( rand() );
                $_SESSION['editingId'] = md5( Security::sanitize( $_POST['editingId'] ) );
                $_SESSION['editConfirm'] = $errors['hash'];
                $_SESSION['editId'] = md5( $editId );
            }
        }
        echo json_encode($errors);
        closeDB();
        break;

    case 'editStudent' :
        loadDB(DB_NAME);
        $canProceed = true;
        $id = Security::sanitize( $_POST['id'] );
        $name = Security::sanitize( $_POST['name'], NO_QUOTES );
        $email = Security::sanitize( $_POST['email'], NO_QUOTES );
        $phone = Security::sanitize( $_POST['phone'], NO_QUOTES );
        $extension = Security::sanitize( $_POST['extension'], NO_QUOTES );
        $age = Security::sanitize( $_POST['age'], NO_QUOTES );
        $whoFor = Security::sanitize( $_POST['whoFor'], NO_QUOTES );
        $pName = Security::sanitize( $_POST['pName'], NO_QUOTES);
        $pCell = Security::sanitize( $_POST['pCell'], NO_QUOTES);
        $experience = Security::sanitize( $_POST['experience'], NO_QUOTES );
        $belts = Security::sanitize( $_POST['belt'], NO_QUOTES );
        $comments = Security::sanitize( $_POST['comments'], NO_QUOTES );


        $errors = Validation::validate( array(
            'length-4' => $name,
            'email' => $email,
            'usphone' => $phone,
            'extension' => $extension,
        ));

        switch ($whoFor){
            case 'Self':
                $errors['whoFor']=true;
                $whoFor = 0;
                $pName = '';
                $pCell = '';
                break;
            case 'Child':
                $errors['whoFor']=true;
                $errors['pName']= Validation::validate('pName', $pName);
                $errors['pCell']= Validation::validate('pCell', $pCell);
                $whoFor = 1;
                break;
            default:$errors['whoFor']=false;break;
        }
        switch ($experience){
            case 'Yes':
                $errors['experience']=true;
                $errors['belt'] = Validation::validate('belt', $belts);
                $experience = 1;
                break;
            case 'No':
                $errors['experience']=true;
                $experience = 0;
                $belts ='';
                break;
            default:$errors['experience']=false;break;
        }

        foreach ( $errors as $err ) {
            if ( !$err ) {
                $canProceed = false;
            }
        }
        if ( $canProceed ) {
            $age = intval($age);

            $q = "UPDATE `mr2358174_karate_entity_student` SET `name` = '$name', `age` = $age, `phone` = '$phone', `email` = '$email',
            `past_experience` = $experience, `belts` = '$belts', `is_child` = $whoFor, `parent_name` = '$pName', `parent_cell` = '$pCell',
            `comments` = '$comments' WHERE `student_id` = $id";
            $r = mysql_query( $q );
            if( !$r ){
                $errors['mysql'] = false;//mysql_error();
            }
        }
        closeDB();
        echo json_encode( $errors );
        break;

    default:
        echo 'I derped sorry or i forgot to take a break first';
}
